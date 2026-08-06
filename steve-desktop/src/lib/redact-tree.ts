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

/**
 * How the defense-in-depth sweep matches a known value in free text.
 *
 * Plain substring matching poisons the whole site from one data cell. A course listing has an
 * item-type column reading "Forum" / "Assessment", so those WORDS enter the dictionary as data —
 * and every later occurrence is swapped, including the "Forums" nav label and the `/forums/`
 * path segment. A live document came out describing `/⟦D41⟧s/⟦D41⟧s.php` and telling the reader to
 * "enumerate ⟦D100⟧s". Accurate, and impossible to act on.
 *
 * The split is between VOCABULARY and IDENTIFIERS, and a digit tells them apart:
 *
 *   no digit ("Forum", "Doe, Jane") — a word. Match on word boundaries, so "Forums" and
 *     "showassess.php" survive while "Contact Doe, Jane" is still caught. A name is bounded by
 *     punctuation or space wherever it appears, so nothing identifying is given up.
 *   has a digit ("7158619", "a7f3b21") — an identifier. Keep plain substring matching, because an
 *     id can be fused into a longer token ("user7158619profile") with no boundary to anchor to.
 *
 * So this fixes the vocabulary breakage without weakening id redaction at all — deliberately, since
 * that is the FERPA-critical half. The `< 3 chars` skip above is the same instinct, one size cruder.
 */
export function sweepPattern(value: string): RegExp {
  const escaped = escapeRegExp(value);
  if (/\d/.test(value)) return new RegExp(escaped, 'gi'); // identifier — substring, as before
  const pre = /^\w/.test(value) ? '\\b' : '';
  const suf = /\w$/.test(value) ? '\\b' : '';
  return new RegExp(pre + escaped + suf, 'gi');
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
      out = out.replace(sweepPattern(value), token);
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
 * A structural KEYWORD value: an enum, slug or filter token that routes the page.
 *
 * Numeric ids alone weren't enough. On scrapethissite.com the crawl stored
 * /pages/frames/?frame=i&family=⟦D5⟧ — the value "Cheloniidae" also appeared in the page's
 * table, so the content-token dictionary substituted it inside the URL and made the page
 * unloadable. Sibling params ?gotcha=headers and ?frame=i survived only because their values
 * happened not to appear in the page text, which is not a property worth relying on.
 *
 * Deliberately narrow: one bare token, no whitespace, no @, no comma. A student name
 * ("John Smith"), an email, or any free-text search value fails this and is still tokenized.
 */
const KEYWORD_VALUE = /^[A-Za-z0-9][A-Za-z0-9._-]{0,39}$/;

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
    // A candidate's `value` is a selector, not data — but a selector can EMBED data. Canvas
    // links to people as /users/12345, so `a[href="/users/12345"]` carried 178 real student ids
    // to disk (53 distinct) even after the href beside it was correctly tokenized. Selectors
    // keep their structure; only person ids inside them are replaced.
    if (STRUCTURAL_KEYS.has(key ?? '') || (inCandidates && key === 'value')) return scrubPersonIds(value);
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

/**
 * A control label shaped like a person's name: "Jane Doe" or "Doe, Jane".
 *
 * Deliberately narrow — it is applied ONLY on people surfaces (see redactProfileForStorage),
 * where a row's label is the person, so over-matching costs a legible label on a page that is
 * mapped for its shape anyway. Applying it everywhere would tokenize "Chapter Summary".
 */
/**
 * The separator between a first and last name: spaces, never a line or cell break.
 *
 * `\s+` here was a live over-match. Run against a real gradebook's dehydrated page text, where
 * cells are tab-separated and blocks are newline-separated, it fused ADJACENT CELLS into
 * person-shaped pairs — "Gradebook\nCourse Home" scored as the person "Gradebook Course", and
 * "Email\tTotal Score" as "Email Total". Each fake name then registered its parts, so the sweep
 * went on to eat `gradebook.php` in the URL and the "Total Score" column header. A name is
 * written with a space; a newline or a tab is a boundary between two different things.
 */
const NAME_GAP = '[ \\u00a0]+';

/**
 * Lowercase particles that connect surname parts without breaking the name run: "de la Cruz",
 * "van der Berg". A closed list, not a general "any lowercase word between two capitals" rule —
 * a general rule would fuse ordinary prose into one match ("Sarah and Chen" is two people and a
 * conjunction, not a compound surname). Matched case-insensitively (both cases enumerated,
 * rather than an `i` flag on the whole pattern, so the surrounding capital-required word shapes
 * stay case-SENSITIVE): "Van" is frequently capitalized in real names ("Nguyen Van An") and this
 * is what lets that run qualify as a name at all — see NAME_RUN below. `O'Brien`-style
 * apostrophes are NOT a particle: the apostrophe sits inside a single word, handled in NAME_WORD.
 *
 * Longer alternatives are listed before shorter overlapping ones ("della" before "del") so the
 * regex engine finds the right one without backtracking, not just for correctness (backtracking
 * would still find it, just slower).
 *
 * Coverage: Dutch/German ("van der Berg", "van den Broek"), Portuguese/Spanish/Italian ("de la
 * Cruz", "dos Santos", "da Silva", "dello", "della", "degli"), Scandinavian nobility ("af"),
 * Arabic patronymics ("bin", "bint", "ibn", "abu"), Vietnamese gender-marking middle names
 * ("văn" for a man, "thị" for a woman — "Nguyễn Văn An"). Excluded "av" (the Norwegian variant
 * of "af"): it collides with "A/V" (audio-visual), a real lowercase abbreviation in
 * classroom-tech UI elsewhere in this app's surface, and "af" alone already covers the
 * Scandinavian case without that risk.
 *
 * "văn"/"thị" are not lowercase connector words the way the others are — they are themselves
 * capitalized, addressed components of a Vietnamese name. They are here anyway, on the same
 * case-insensitive footing as "Van", because the alternative is the SAME leak the ASCII "Nguyen
 * Van An" case already fixed: without them, "Nguyễn Văn An" only partial-masks its leading pair
 * ("⟦STU⟧ An") since "Văn" (with its Vietnamese breve) is not the same string as ASCII "Van" and
 * so never matched the particle list at all. The diacritic makes an English-UI collision
 * essentially impossible, so — unlike the ASCII particles — there is no over-masking trade-off
 * to weigh here.
 */
const NAME_PARTICLE =
  '(?:[Dd]ella|[Dd]ello|[Dd]egli|[Dd]el|[Dd]en|[Dd]er|[Dd]es|[Dd]al|[Dd]os|[Dd]i|[Dd]u|[Dd]a|[Dd]e|' +
  '[Ll]a|[Ll]e|[Vv]an|[Vv](?:ă|a\u0306)n|[Vv]on|[Tt]h(?:ị|i\u0323)|[Tt]er|[Tt]en|[Bb]int|[Bb]in|[Ii]bn|[Aa]bu|[Aa]f)';

/**
 * One Unicode letter, plus any combining marks riding on it (`\p{Mn}` — accents, tone marks,
 * anything a NORMALIZATION FORM might spell as a separate codepoint instead of folding into the
 * base letter). `LU`/`LL`/`ANY` mirror `\p{Lu}`/`\p{Ll}`/`\p{L}`, just each one-letter-at-a-time
 * so a multi-letter run (`(?:${LETTER.LL})+`, not `\p{Ll}+\p{Mn}*`) attaches marks to the RIGHT
 * letter instead of only tolerating one trailing mark on the run's last letter.
 *
 * This is what makes the grammar work on BOTH Unicode normalization forms without normalizing
 * anything: NFC spells "é" as one precomposed codepoint (plain `\p{Ll}`, `\p{Mn}*` matches zero
 * marks); NFD spells it as `e` + a separate combining acute (`\p{Ll}` matches the `e`, `\p{Mn}*`
 * eats the mark right after it). Both forms are valid Unicode for the same text — this is not
 * malformed input to normalize away, it is a shape the grammar itself needs to accept.
 *
 * A `.normalize('NFC')` call before matching was tried first and reverted: `maskPersonNames`
 * hands its `token` callback the MATCHED substring, which page-agent-mask.ts's `PageMask` stores
 * verbatim as the value to rehydrate back later. Normalizing the text before matching means that
 * stored value — and every un-matched character around it in the returned string — silently
 * becomes NFC, even when the original was NFD. Measured directly: masking an NFD name and
 * rehydrating the result came back NFC, not the original NFD bytes. Matching the ORIGINAL bytes
 * via this letter-plus-marks grammar instead means the token map and the returned string are
 * exactly what was on the page — nothing to launder, nothing to get wrong.
 */
const LETTER = {
  LU: '\\p{Lu}\\p{Mn}*',
  LL: '\\p{Ll}\\p{Mn}*',
  ANY: '\\p{L}\\p{Mn}*',
};

/**
 * The O'/Mc/Mac-shaped subset of NAME_WORD, split out because it also stands as its own
 * justification for a 3+-word run (NAME_RUN below) — a run does not need a particle if one of
 * its words already carries this strong a name-signal.
 *
 * Unicode letter classes (`\p{Lu}`/`\p{Ll}`, via LETTER above), not `[A-Z]`/`[a-z]` — those are
 * ASCII-only, so "José", "Nguyễn", "Müller", "Søren" matched NOTHING, in any tier, full stop.
 * Every RegExp built from these fragments MUST carry the `u` flag or `\p{...}` is either a syntax
 * error or matches the two literal characters `p{Lu}` — see the flag on every `new RegExp(...)`
 * call below.
 */
const SPECIAL_NAME_WORD =
  `(?:Mc${LETTER.LU}(?:${LETTER.LL})+|Mac${LETTER.LU}(?:${LETTER.LL})+|${LETTER.LU}['’]${LETTER.LU}(?:${LETTER.LL})+)`;

/**
 * One name "word". Either the special shape above (`McDonald`, `MacArthur`, `O'Brien`,
 * `D'Angelo` — the last two: capital on BOTH sides of the apostrophe, distinct from the infix
 * form below, because `\p{Lu}\p{Ll}+` alone cannot start a match at "O'" — no lowercase letter
 * follows the O — so a match used to begin one letter late, at "Brien", leaving "O'" in the
 * clear), or an ordinary word with an optional infix hyphen/apostrophe run (`Jean-Luc`,
 * `Ana-Lucía`).
 */
const NAME_WORD = `(?:${SPECIAL_NAME_WORD}|${LETTER.LU}(?:${LETTER.LL})+(?:[-'](?:${LETTER.ANY})+)*)`;

/**
 * A gap carrying 1-4 particles — "␣de␣la␣", "␣Van␣" — distinct from a bare gap: this is the
 * signal a 3+-word run needs to qualify as a name (NAME_RUN below).
 *
 * Capped at 4, not unbounded (`+`): an unbounded repeated group immediately followed by a
 * mandatory NAME_WORD is a textbook catastrophic-backtracking shape when that mandatory word
 * never arrives. Measured directly — a page containing a long run of particle-shaped words with
 * no capitalized terminator ("de la de la de la … " with no closing name, plausible in ordinary
 * French/Spanish/Vietnamese prose) took the unbounded version from 14ms at 1.2K chars to 4.4
 * SECONDS at 19K chars, roughly quadratic. Real name particle chains are 1-3 words ("van der",
 * "de la del"); 4 is generous headroom, and capping removes the exponential search space entirely
 * rather than just making it less likely to trigger.
 */
const PARTICLE_CONNECTOR = `${NAME_GAP}(?:${NAME_PARTICLE}${NAME_GAP}){1,4}`;

/**
 * A 3+ word run is a name ONLY when something beyond bare capitalization justifies it: a
 * particle junction anywhere in the run (plain words may sit before and after it — "Ana Maria
 * de la Cruz" as much as "Maria de la Cruz"), or an O'/Mc/Mac-shaped word anywhere in it
 * ("Katie O'Brien Wilson").
 *
 * Without this gate, EVERY run of 3+ capitalized words matched, because an ordinary capitalized
 * UI phrase is structurally indistinguishable from a name once you allow arbitrary length —
 * "View Student Progress", "Print Class Roster" and "Late Work Policy" all masked whole, roughly
 * doubling the site map's over-masking versus the pre-existing two-word-only baseline. That
 * measured regression is why this gate exists, not a hypothetical worry.
 *
 * What this still gives up, deliberately: "Mary Jane Watson" (3 plain words, no particle, no
 * special word) only masks its first two words ("⟦STU⟧ Watson"), same as before this whole
 * change. Closing that specific gap is exactly what caused the regression above — do not remove
 * this gate to close it without re-measuring against the chrome-survival list in
 * redact-tree.test.ts first.
 */
// Prefix/suffix "extra plain word" runs around a particle or special-word junction, capped at 4
// for the SAME catastrophic-backtracking reason PARTICLE_CONNECTOR is capped: unbounded
// (`(?:${NAME_WORD}${NAME_GAP})*`) immediately followed by a construct that might not exist (a
// particle, a special word) is what turned ordinary REPEATED chrome text — no name, no particle,
// nothing exotic, just "Total Score Last Login Course Home Send Message" over and over, which is
// exactly what a real gradebook page looks like — into a multi-second hang: 3.4s at 19K chars,
// 18s at 38K, quadratic, with zero particles anywhere in the input. Bounding this prefix/suffix
// is what actually removes the blowup; nobody has 5+ middle names, so 4 is generous, not tight.
const EXTRA_WORDS_BEFORE = `(?:${NAME_WORD}${NAME_GAP}){0,4}`;
const EXTRA_WORDS_AFTER = `(?:${NAME_GAP}${NAME_WORD}){0,4}`;

const LONG_RUN_PARTICLE =
  `${EXTRA_WORDS_BEFORE}${NAME_WORD}${PARTICLE_CONNECTOR}${NAME_WORD}${EXTRA_WORDS_AFTER}`;
const LONG_RUN_SPECIAL =
  `(?:${SPECIAL_NAME_WORD}${NAME_GAP}${NAME_WORD}${EXTRA_WORDS_AFTER}` +
  `|(?:${NAME_WORD}${NAME_GAP}){1,4}${SPECIAL_NAME_WORD}${EXTRA_WORDS_AFTER})`;

/** Exactly two plain words — the ORIGINAL, unwidened two-word scope. A bare pair of capitalized
 *  words is always a name, exactly as before this whole particle/long-run change; the LABEL_WORD
 *  allowlist below is what saves ordinary two-word UI wording from this, same as it always did. */
const TWO_WORD_PLAIN = `${NAME_WORD}${NAME_GAP}${NAME_WORD}`;

const NAME_RUN = `(?:${LONG_RUN_PARTICLE}|${LONG_RUN_SPECIAL}|${TWO_WORD_PLAIN})`;

/**
 * One side of a comma-form label: a name word that — ONLY here, on the family-name side of a
 * comma — may itself OPEN with a particle: "de la Cruz, Maria" (the particle is the very first
 * thing on that side, with nothing before it to attach to as a connector). Beyond the first
 * word, further words are absorbed only through a particle connector too, for the identical
 * reason NAME_RUN gates its 3+ case: an unrestricted plain chain here would over-match the GIVEN
 * side just as easily as the plain form ("Grade, Attendance Report"). The leading group is capped
 * at 4 for the same catastrophic-backtracking reason PARTICLE_CONNECTOR is: unbounded, immediately
 * followed by a mandatory NAME_WORD, is the shape that blew up to 4+ seconds on adversarial input.
 */
const NAME_GROUP = `(?:${NAME_PARTICLE}${NAME_GAP}){0,4}${NAME_WORD}(?:${PARTICLE_CONNECTOR}${NAME_WORD})*`;

/**
 * The comma form alone — "Doe, Jane" or "de la Cruz, Maria". Roster tables render it and almost
 * nothing else does.
 *
 * Split out because the two forms deserve different trust: see looksLikeRoster in
 * people-pointer.ts, where the plain form was measured against a live course and found to be
 * the shape of ordinary UI wording, not a person signal. Anything masking text it cannot
 * classify as a people surface should use this half only.
 */
const PERSON_LABEL_COMMA_SRC = `${NAME_GROUP},[ \\u00a0]*${NAME_GROUP}`;
// 'u' is not decoration — every fragment above uses \p{...} Unicode property escapes, which are a
// SyntaxError (or, worse, match the literal characters "p{Lu}") without it. Every RegExp built
// from these fragments needs it, including reconstructions — see maskPersonNames below.
const PERSON_LABEL_COMMA = new RegExp(PERSON_LABEL_COMMA_SRC, 'gu');

/** Plain-or-comma form: a 2+ word run joined by spaces (NAME_RUN), or the comma form above. */
const PERSON_LABEL = new RegExp(`(?:${NAME_RUN}|${PERSON_LABEL_COMMA_SRC})`, 'gu');

/**
 * Replace every person-shaped run in free text, asking `token` what to put in its place.
 *
 * One definition, three callers: the storage pass below (fixed ⟦STU⟧), and the page-agent
 * mask (reversible per-name tokens). `commaOnly` is the safety valve for text whose surface is
 * unknown — see PERSON_LABEL_COMMA.
 *
 * Matches directly against `text` — no `.normalize()` call. See the comment on NAME_WORD for why
 * a normalize-first approach was tried and reverted: it silently breaks rehydration.
 */
export function maskPersonNames(
  text: string,
  token: (name: string) => string,
  opts: { commaOnly?: boolean } = {},
): string {
  const src = opts.commaOnly ? PERSON_LABEL_COMMA : PERSON_LABEL;
  // Fresh RegExp per call: the module-level ones are /g and would carry lastIndex between callers.
  // 'u' again — reconstructing from .source drops flags, so this needs its own, not src's.
  return text.replace(new RegExp(src.source, 'gu'), (m) => (isCommonLabel(m) ? m : token(m)));
}

/** Does free text contain a person-shaped name anywhere? Unlike looksLikePersonName, this is
 * deliberately not anchored: roster controls often append a grade or an action to the name. */
export function containsPersonName(text: string): boolean {
  return maskPersonNames(text, () => '⟦PERSON⟧') !== text;
}

/**
 * Capitalised words that are ordinary UI wording, not a person. Without this, a gradebook's
 * own column headers ("Total Score", "Last Login") tokenize to ⟦STU⟧ and the map of the page
 * stops being readable. `isCommonLabel` below keeps a match only when EVERY word in it is in
 * here — that's what makes it safe to broaden: "Chapter Review" becomes an ordinary label, but
 * "Sarah Chapter" still masks, because "Sarah" is not in the set. It already generalizes past
 * two words for the same reason — "every", not "both" — so a 3+ word run like "Chapter Review
 * Session" is spared only if all three are common label words, same rule, no separate case.
 * Assessment vocabulary (midterm/exam/quiz/…) lives here too: a course's own assignment names
 * ("Midterm Exam", "Chapter Review") were tokenizing as if they were people (teach-tokens.ts's
 * promotion guard hit this first), and the fix is app-wide — it also stops those names
 * tokenizing into the site map.
 */
const LABEL_WORD = new Set(
  `total score last login first name due date late work best attempt raw points final grade
   time spent user settings my classes log out skip navigation home page course map data sets
   letter grade extra credit drop lowest all students by student per question item analysis
   message board send message view all show all hide all export csv print view class list
   late passes login grid item results course reports content stats question errors
   non locked add tutors copy emails un lock course home site home skip navigation
   midterm exam quiz test homework assignment chapter unit lab project review practice final
   section module worksheet packet`
    .split(/\s+/),
);

export const isCommonLabel = (m: string) =>
  m.split(/[\s,]+/).every((w) => LABEL_WORD.has(w.toLowerCase()));

/** Non-global twin of PERSON_LABEL, anchored, for testing a single label — built from the same
 *  NAME_RUN / NAME_GROUP fragments so it can't drift from what PERSON_LABEL actually matches. */
const ONE_PERSON_LABEL = new RegExp(`^(?:${NAME_RUN}|${PERSON_LABEL_COMMA_SRC})$`, 'u');

/**
 * Does this single label read as a person's name rather than UI wording?
 *
 * Exported so there is ONE definition: people-pointer.ts classifies whole pages with it, and a
 * second copy would inevitably drift from the allowlist that keeps "Total Score" readable.
 *
 * No `.normalize()` here either, for the same reason `maskPersonNames` doesn't have one — this
 * function returns a boolean, not text, so there's no rehydration risk specific to IT, but
 * `ONE_PERSON_LABEL` is built from the same NFC/NFD-tolerant NAME_WORD/LETTER fragments, so an
 * NFD label ("José García", diacritic as a separate combining-mark codepoint) is already
 * recognized without normalizing anything.
 */
export function looksLikePersonName(label: string): boolean {
  const t = label.trim();
  return ONE_PERSON_LABEL.test(t) && !isCommonLabel(t);
}

/**
 * Tokenize person-shaped runs on a page that lists people.
 *
 * Covers every place a name was measured on disk after a live 306-page capture, not just the
 * obvious one:
 *   interactive.buttons[].text / inputs[].label   — the visible row label
 *   interactive.*[].selector                      — `[role=button][name="Doe, Jane"]`
 *   interactive.*[].candidates[].value            — the role-name candidate IS the name
 * Fixing only the first left 2 of 3 names on disk.
 *
 * A selector keyed by a student's name is not worth preserving anyway: it breaks next term, and
 * automation should address roster rows positionally. Mapping the SHAPE is the goal.
 */
function tokenizePersonLabels<T>(profile: T): T {
  const FIELDS = new Set(['text', 'label', 'selector', 'value', 'purpose', 'nearestHeading']);
  const swap = (s: string) => maskPersonNames(s, () => STUDENT_TOKEN);
  const walk = (v: unknown, key = ''): unknown => {
    if (typeof v === 'string') return FIELDS.has(key) ? swap(v) : v;
    if (Array.isArray(v)) return v.map((x) => walk(x, key));
    if (v && typeof v === 'object') {
      const out: Record<string, unknown> = {};
      for (const [k, x] of Object.entries(v as Record<string, unknown>)) out[k] = walk(x, k);
      return out;
    }
    return v;
  };
  return walk(profile) as T;
}

/** An href that addresses ONE person: a person-selecting query param, or a person path segment. */
const PERSON_HREF = /[?&](uid|stu|stuid|student|studentid|userid|filteruid|sid|learner)=|\/users?\/[^/?#]+|\/submissions?\/[^/?#]+|\/grades\/[^/?#]+/i;

/**
 * Tokenize the label of any link that POINTS AT A PERSON, on any page.
 *
 * The people-surface test asks "is this page a roster?" and a Canvas discussion board is not one
 * — it is course content we want mapped. But every post shows its author as
 * `<a href="/courses/31407/users/12345">Jane Doe</a>`, so a live Canvas crawl put **26 real
 * student names** on disk (confirmed by hashing them against the live roster; the names were
 * never printed). Not one was comma-form, so `looksLikeRoster` could not see them either, and
 * `discussion_topics` matches no people-surface pattern — correctly, since it is content.
 *
 * The page was never the right unit. A LINK is: whatever page it sits on, a label on a link that
 * addresses one person IS that person. It needs no vocabulary and no page classification, and it
 * covers both LMSs — MyOpenMath's `?stu=` rows and Canvas's `/users/<id>` authors.
 *
 * Verified on the stored crawl: 26 roster names before, 0 after.
 */
function tokenizePersonLinkedLabels<T>(profile: T): T {
  const p = profile as { interactive?: { links?: { text?: string; href?: string }[] } };
  const names = new Set<string>();
  for (const l of p.interactive?.links ?? []) {
    const text = (l.text ?? '').trim();
    if (text && !text.includes('⟦') && PERSON_HREF.test(l.href ?? '')) names.add(text);
  }
  if (!names.size) return profile;
  // Longest first, so a full name wins over a surname that is a substring of it.
  const ordered = [...names].sort((a, b) => b.length - a.length);
  const swap = (s: string) => {
    let out = s;
    for (const n of ordered) out = out.split(n).join(STUDENT_TOKEN);
    return out;
  };
  const FIELDS = new Set(['text', 'label', 'selector', 'value', 'purpose', 'nearestHeading']);
  const walk = (v: unknown, key = ''): unknown => {
    if (typeof v === 'string') return FIELDS.has(key) ? swap(v) : v;
    if (Array.isArray(v)) return v.map((x) => walk(x, key));
    if (v && typeof v === 'object') {
      const out: Record<string, unknown> = {};
      for (const [k, x] of Object.entries(v as Record<string, unknown>)) out[k] = walk(x, k);
      return out;
    }
    return v;
  };
  return walk(profile) as T;
}

/**
 * @param peopleSurface true when this page LISTS PEOPLE (gradebook, roster, message list).
 *
 * `redactTree` keeps control labels on purpose — a map is useless if every button reads ⟦D7⟧.
 * But on a roster the label IS the person, so those labels never entered the value dictionary
 * and survived to disk. A fresh 306-page capture of a live course stored 3 distinct student
 * names this way, in `course-gradebook-*.json` and `course-listusers-*.json`. They never reach
 * a model — fragment prompts carry only "pageName — path — counts" — but they were on disk.
 */
export function redactProfileForStorage<T extends { domain: string; url?: string; pageName?: string }>(
  profile: T,
  redact: (t: string) => string,
  peopleSurface = false,
): T {
  // Person-linked labels are scrubbed on EVERY page — a discussion board is content, and still
  // names its authors. The people-surface pass then handles rosters, where the label is a person
  // but there may be no link to prove it.
  const linked = tokenizePersonLinkedLabels(profile);
  const source = peopleSurface ? tokenizePersonLabels(linked) : linked;
  const redactValues = (p: T) => redactStrings(p, redact) as T;
  const safe = { ...(redactValues(source) as T), domain: profile.domain };
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
  // The path is NOT automatically "just structure" — Canvas addresses people positionally as
  // /users/12345, and a query-only rule wrote 53 real student ids to disk.
  const path = redactUserPath(q === -1 ? raw : raw.slice(0, q));
  if (q === -1) return path;
  return path + redactQuery(raw.slice(q), redact);
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
        // So are keyword values (?frame=i, ?gotcha=headers, ?family=Cheloniidae). The key is not
        // student-ish or STUDENT_PARAM would have caught it above.
        if (KEYWORD_VALUE.test(decoded)) return pair;

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

/**
 * Strip person ids out of a string that is STRUCTURE (a selector), not prose.
 *
 * Kept separate from the text dictionary on purpose: an id that never appeared as visible page
 * text is not in the dictionary, so redact() cannot see it. Only the id changes — the selector
 * around it survives, because a selector aimed at one student's row is not reusable anyway.
 */
export function scrubPersonIds(s: string, token: (id: string) => string = () => STUDENT_TOKEN): string {
  return redactUserPath(s, token)
    // ?uid=123 / &stu=123, the query form, inside a selector string
    .replace(
      /\b(uid|stu|stuid|student|studentid|user|userid|filteruid|sid|learner)=(\d+)/gi,
      (_m, k, id) => `${k}=${token(id)}`,
    )
    // #student_123 / #submission_123 — Canvas builds DOM ids this way, so a CSS candidate can
    // carry a person id with no slash anywhere in it.
    .replace(
      /\b(user|student|learner|enrollment|profile|submission)[_-](\d+)/gi,
      (_m, k, id) => `${k}_${token(id)}`,
    );
}

/**
 * A person id addressed positionally: /users/12345, /people/99, /enrollments/7.
 *
 * Matched as a regex rather than by splitting on "/" because the same id has to be found inside
 * strings that are not bare paths — `a[href="/users/12345"]` splits into a final segment of
 * `12345"]`, which no numeric test matches, and that is exactly how 178 ids survived in
 * candidate selectors after the href beside them was already tokenized.
 *
 * `submissions/` and `grades/` are here because Canvas addresses a person's WORK the same way it
 * addresses the person: /assignments/844633/submissions/127333 and /courses/31407/grades/127333
 * both end in a user id. Naming only the people-ish nouns missed 89 of them on disk.
 */
const PERSON_ID_IN_PATH =
  /\b(users?|students?|learners?|people|profiles?|enrollments?|submissions?|grades?)\/(\d+)/gi;

/**
 * Tokenize a person's id when it lives in the PATH rather than the query.
 *
 * STUDENT_PARAM only ever covered `?uid=`/`?stu=`. Canvas addresses people positionally —
 * /users/12345 — so a Student View crawl of one real course wrote 53 distinct student ids to
 * disk across 9 pages. No names came with them (the links are avatars with empty text), but a
 * Canvas user id identifies a student just as well as `stu=` does.
 *
 * Only a numeric segment directly after a person-ish segment is replaced, so /courses/31407 and
 * /modules/items/1904067 stay legible — the id is course structure there, not a person.
 */
export function redactUserPath(
  pathname: string,
  token: (id: string) => string = () => STUDENT_TOKEN,
): string {
  return pathname.replace(PERSON_ID_IN_PATH, (_m, seg: string, id: string) => `${seg}/${token(id)}`);
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
    const path = redactUserPath(u.pathname);
    const file = pathSlug(path);
    // pageName = filename + the ALREADY-redacted query, so a secret in the query stays a token
    // in the name too, while the query still disambiguates same-filename pages.
    return { url: u.origin + path + redQuery, pageName: file + redQuery };
  } catch {
    const red = redact(rawUrl); // not a parseable URL — leave it fully redacted
    return { url: red, pageName: red };
  }
}
