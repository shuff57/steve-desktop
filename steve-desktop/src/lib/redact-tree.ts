import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';

function escapeRegExp(value: string): string {
  return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Slot-level, deny-by-default redaction (the inversion).
//
// The old model redacted by *value* — it could only strip strings it already knew.
// Computed accessible names, free-text notes, and name variants slipped through.
// This redacts by *slot*: any node that is a data container has its content tokenized
// REGARDLESS of contents, so unknown PII cannot leak by being "not in the dictionary".
// Chrome (controls, headings, field labels) is kept so the model can still act.
//
// The values discovered here seed a second-pass value-dictionary swap (Redactor) for the
// free-text fields (dom string, messages) — defense in depth. See redact-tree.test.ts.

// Allow-list: nodes whose visible text is a label/control, not data.
const CHROME_TAGS = new Set([
  'button', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'th', 'label', 'legend', 'summary', 'caption', 'nav',
]);
const CHROME_ROLES = new Set(['button', 'link', 'heading', 'tab', 'menuitem']);

export function isChromeNode(node: SnapshotNode): boolean {
  const role = (node.attrs?.['role'] ?? '').toLowerCase();
  if (CHROME_ROLES.has(role)) return true;
  return CHROME_TAGS.has((node.tag ?? '').toLowerCase());
}

export interface TreeRedaction {
  /** Serialized, safe-to-send representation: every data slot is a token. */
  redactedText: string;
  /** token -> original value (local only, for rehydration). */
  map: Record<string, string>;
  rehydrate(text: string): string;
  /** Apply the value->token dictionary to arbitrary text (e.g. a profile JSON
   *  before it's saved), so persisted artifacts hold tokens, not raw PII. */
  redact(text: string): string;
}

export interface RedactTreeOptions {
  /** Known PII values (e.g. roster) to also strip from free text via the dictionary pass. */
  extraSecrets?: string[];
}

// Form controls: their aria-label/title is the field LABEL (keep it — the model needs it);
// only their `value` is data.
const FORM_TAGS = new Set(['input', 'textarea', 'select']);
// Computed-name attrs that carry data on a data container (a cell/row), not a form control.
const DATA_ATTRS = ['aria-label', 'title', 'alt'];

export function redactTree(snapshot: SnapshotResult, opts: RedactTreeOptions = {}): TreeRedaction {
  const valueToToken = new Map<string, string>();
  const tokenToValue = new Map<string, string>();

  const tokenFor = (value: string): string => {
    const v = value.trim();
    if (!v) return value;
    const existing = valueToToken.get(v);
    if (existing) return existing;
    const token = `⟦D${valueToToken.size + 1}⟧`;
    valueToToken.set(v, token);
    tokenToValue.set(token, v);
    return token;
  };

  // 1) Walk the tree; tokenize every data slot's content (deny-by-default).
  const redactedNodes: SnapshotNode[] = snapshot.nodes.map((node) => {
    const chrome = isChromeNode(node);
    const attrs: Record<string, string> = { ...node.attrs };

    // Input values are always data, even on otherwise-structural nodes.
    if (attrs['value']?.trim()) attrs['value'] = tokenFor(attrs['value']);

    let text = node.text;
    if (!chrome) {
      if (text?.trim()) text = tokenFor(text);
      // computed name / tooltip on a data container can carry PII — but NOT on a form
      // control, where aria-label is the field's label and the model needs it.
      if (!FORM_TAGS.has((node.tag ?? '').toLowerCase())) {
        for (const a of DATA_ATTRS) {
          if (attrs[a]?.trim()) attrs[a] = tokenFor(attrs[a]);
        }
      }
    }
    return { ...node, text, attrs };
  });

  let redactedText = JSON.stringify({ ...snapshot, nodes: redactedNodes });

  // 2) Register known secrets (e.g. roster) — they may sit inside chrome text the slot
  //    pass deliberately kept (a heading "Welcome, Jane Doe").
  for (const s of opts.extraSecrets ?? []) tokenFor(s);

  // 3) Defense-in-depth sweep: replace any remaining raw occurrence of a known value with
  //    its token — one token per value (longest-first so "Jane Doe" wins over "Doe").
  const byLongest = Array.from(valueToToken.entries()).sort((a, b) => b[0].length - a[0].length);
  // ponytail: skip <3-char values in the global sweep so a grade "A" doesn't nuke every
  // "a" in chrome text — the slot pass already tokenized their specific data cells.
  const redact = (text: string): string => {
    let out = text;
    for (const [value, token] of byLongest) {
      if (value.trim().length < 3) continue;
      out = out.replace(new RegExp(escapeRegExp(value), 'gi'), token);
    }
    return out;
  };
  redactedText = redact(redactedText);

  const map = Object.fromEntries(tokenToValue);
  return {
    redactedText,
    map,
    redact,
    rehydrate(text: string): string {
      let out = text;
      for (const [token, value] of tokenToValue) out = out.split(token).join(value);
      return out;
    },
  };
}

/**
 * Redact a captured profile for storage, WITHOUT letting the redactor rewrite the fields that
 * identify where the profile belongs.
 *
 * The redactor is a case-insensitive substring swap with no word boundaries (see redact.ts), so
 * a page value that happens to appear inside the hostname rewrites the hostname too. This is not
 * hypothetical: a course named "Math 12" made every capture of www.myopenmath.com save itself as
 * "www.⟦D15⟧.com". Profiles scattered into one bogus directory per token index and the site map
 * became unfindable, so the panel reported nothing to clear while ~50 files sat on disk.
 *
 * `domain` is restored because it is a public hostname — routing information, not user data.
 * `url` and `pageName` stay redacted: a URL carries uid=/stu= student identifiers and a page
 * title can carry a name, and neither is worth un-redacting to tidy a filename.
 */
/**
 * Fields that are STRUCTURE, not data: selectors and their ranked candidate values are how an
 * agent finds an element, and any PII inside them was already tokenized during tree redaction.
 * Rewriting them again corrupts them.
 */
const STRUCTURAL_KEYS = new Set(['selector', 'domain', 'profiledAt', 'type']);

/** Keys whose value is a URL: the path stays navigable, the query is redacted per parameter. */
const URL_KEYS = new Set(['href', 'url']);

/**
 * Query parameters that name a STUDENT. Their values are never stored, whether or not the value
 * happened to appear as page text — the dictionary only holds visible text, so relying on it to
 * catch an id that lives solely in a URL would leak.
 */
const STUDENT_PARAM = /^(uid|stu|stuid|student|studentid|user|userid|filteruid|sid|learner)$/i;

/** Placeholder for a dropped student identifier — deliberately not navigable. */
const STUDENT_TOKEN = '⟦STU⟧';

/**
 * A bare structural id: course, category, assignment, folder, page number. Confirmed with the
 * account owner that course/category/assignment ids carry no student information, so these stay
 * legible — tokenizing them only broke navigation (cid=⟦D34⟧, cat=⟦D105⟧ made pages unloadable
 * and got them pruned as dead). Anything TEXTUAL in a query is still redacted, so a name or a
 * free-text value in an unrecognized parameter is caught even without naming that parameter.
 */
const STRUCTURAL_ID = /^[\d][\d.\-_]*$/;

/**
 * Redact every DATA string in an object graph, walking it structurally.
 *
 * Replaces the old `JSON.parse(redact(JSON.stringify(profile)))`, which string-replaced over
 * SERIALIZED JSON and corrupted it two ways on a live MyOpenMath course — enough that the
 * gradebook, coursemap and course home never captured at all:
 *   - a captured value containing a double quote ate the JSON's own delimiters, producing
 *     `"selector":⟦D526⟧` (unquoted) → JSON.parse threw → the whole capture failed;
 *   - a short numeric value (e.g. `163`) matched INSIDE unrelated numbers, rewriting the course
 *     id in every URL: `cid=3⟦D526⟧41`, a URL that can never load.
 * Walking the graph touches only string values, so JSON syntax and URLs can't be damaged.
 * `href`/`url` are exempt here and redacted by redactUrlForStorage, which keeps the path
 * navigable and tokenizes only the query, where identifiers live.
 */
function redactStrings(value: unknown, redact: (t: string) => string, key?: string, inCandidates = false): unknown {
  if (typeof value === 'string') {
    // A candidate's `value` is a selector, not data.
    if (STRUCTURAL_KEYS.has(key ?? '') || (inCandidates && key === 'value')) return value;
    // A link's href is navigation, but its query can carry a student id — keep the path, scrub
    // the query. Exempting hrefs wholesale would write `stu=7158619` straight into the site map.
    if (URL_KEYS.has(key ?? '')) return redactUrlString(value, redact);
    return redact(value);
  }
  if (Array.isArray(value)) return value.map((v) => redactStrings(v, redact, key, inCandidates || key === 'candidates'));
  if (value && typeof value === 'object') {
    return Object.fromEntries(
      Object.entries(value as Record<string, unknown>).map(([k, v]) => [
        k,
        redactStrings(v, redact, k, inCandidates || k === 'candidates'),
      ]),
    );
  }
  return value; // numbers, booleans, null — never carry text
}

export function redactProfileForStorage<T extends { domain: string; url?: string; pageName?: string }>(
  profile: T,
  redact: (t: string) => string,
): T {
  const redactValues = (p: T) => redactStrings(p, redact) as T;
  const safe = { ...(redactValues(profile) as T), domain: profile.domain };
  if (profile.url) {
    const r = redactUrlForStorage(profile.url, redact);
    safe.url = r.url;
    // Name the page after its legible URL, not a bare token, so profiles save under readable,
    // DISTINCT filenames (a token index used to disambiguate `course.php?folder=0` from
    // `…folder=0-9-1` by accident; the query does it on purpose here).
    safe.pageName = r.pageName;
  }
  return safe;
}

/**
 * Redact a URL for storage while keeping it legible and navigable, and derive a matching page
 * name. Scheme+host+PATH are kept intact (structural navigation — directories and filenames
 * like `course.php`), and only the QUERY is redacted, where identifiers live (uid=, stu=). A
 * captured secret in the query stays a token, so that page fails to navigate and is reported
 * honestly rather than writing a student id to disk.
 *
 * The path is trusted as structural: this assumes identifiers live in query params (true on
 * MyOpenMath). A site that put a raw student id in a PATH segment would have it written to the
 * local map; same-origin, own-session capture bounds that exposure. Widen to per-segment
 * redaction if a target site is found that puts ids in the path.
 */
/**
 * Redact a query string per PARAMETER VALUE rather than as flat text.
 *
 * Flat replacement corrupted live URLs: a captured page value of `163` matched inside the course
 * id `316341`, yielding `cid=3⟦D526⟧41` — a URL that can never load, which the mapper then
 * reported as unreachable. A numeric id that is only PARTIALLY rewritten is therefore kept whole;
 * a value the dictionary matches outright (a name, a student id) is still tokenized.
 */
function redactUrlString(raw: string, redact: (t: string) => string): string {
  const q = raw.indexOf('?');
  if (q === -1) return raw; // no query → nothing but structure
  return raw.slice(0, q) + redactQuery(raw.slice(q), redact);
}

function redactQuery(rawQuery: string, redact: (t: string) => string): string {
  const lead = rawQuery.startsWith('?') ? '?' : '';
  const body = lead ? rawQuery.slice(1) : rawQuery;
  if (!body) return rawQuery;
  return (
    lead +
    body
      .split('&')
      .map((pair) => {
        const eq = pair.indexOf('=');
        if (eq === -1) return pair;
        const key = pair.slice(0, eq);
        const value = pair.slice(eq + 1);
        let decoded = value;
        try { decoded = decodeURIComponent(value); } catch { /* keep raw */ }

        // A student identifier never reaches disk. `0`/empty means "no filter", not a person.
        if (STUDENT_PARAM.test(key)) return decoded && Number(decoded) !== 0 ? `${key}=${STUDENT_TOKEN}` : pair;
        // Course / category / assignment / folder ids are structure — keep them navigable.
        if (STRUCTURAL_ID.test(decoded)) return pair;

        const red = redact(value);
        return red === value ? pair : `${key}=${red}`;
      })
      .join('&')
  );
}

/**
 * Page name from the WHOLE path, not just its last segment.
 *
 * The last segment alone collides catastrophically on path-based sites: a regression crawl of
 * quotes.toscrape mapped 177 pages into 61 profile files because `/page/1/`, `/tag/love/page/1/`
 * and 110 others all reduced to "1". Each collision silently overwrote a profile, so the map
 * pointed 111 pages at selectors captured from a different page — an agent acting on that map
 * would drive the wrong page. Query-based sites (MyOpenMath) hid it because the query
 * disambiguated. Long paths are truncated with a hash of the full path so they stay unique.
 */
function pathSlug(pathname: string): string {
  const segs = decodeURIComponent(pathname).split('/').filter(Boolean);
  if (!segs.length) return 'home';
  const full = segs.join('-');
  if (full.length <= 80) return full;
  let h = 5381;
  for (let i = 0; i < full.length; i++) h = ((h << 5) + h + full.charCodeAt(i)) >>> 0;
  return `${full.slice(0, 72)}-${h.toString(36)}`;
}

export function redactUrlForStorage(
  rawUrl: string,
  redact: (t: string) => string,
): { url: string; pageName: string } {
  // Split on the raw string, not new URL().search, so the query redaction matches the dictionary
  // (which holds decoded values) instead of a re-encoded %20 form.
  const qIdx = rawUrl.indexOf('?');
  const pathPart = qIdx === -1 ? rawUrl : rawUrl.slice(0, qIdx);
  const rawQuery = qIdx === -1 ? '' : rawUrl.slice(qIdx);
  try {
    const u = new URL(pathPart);
    const redQuery = rawQuery ? redactQuery(rawQuery, redact) : '';
    const file = pathSlug(u.pathname);
    // pageName = filename + the ALREADY-redacted query, so a secret in the query stays a token
    // in the name too, while the query still disambiguates same-filename pages.
    return { url: u.origin + u.pathname + redQuery, pageName: file + redQuery };
  } catch {
    const red = redact(rawUrl); // not a parseable URL — leave it fully redacted
    return { url: red, pageName: red };
  }
}
