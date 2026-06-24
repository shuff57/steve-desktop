import type { SnapshotResult, SnapshotNode } from './dom-snapshot-types';
import type { SiteProfile, InputElement, ButtonElement, LinkElement } from './types/site-profile';

// Deterministic discovery: turn a DOM snapshot into a site-profile field map and
// flag which input fields are person identifiers. Identifier flags are what the
// redactor uses for a structured field-swap (see redact.ts).

// ponytail: keyword heuristic, good enough for the field types we support. Upgrade
// to per-site overrides in the site-profile if a field is mis-classified.
const IDENTIFIER_PATTERNS = [
  /\bname\b/, /first.?name/, /last.?name/, /full.?name/,
  /\bid\b/, /student.?id/, /employee.?id/, /\buser.?id\b/,
  /\bssn\b/, /social.?security/,
  /\bdob\b/, /date.?of.?birth/, /birth.?date/,
  /\bemail\b/, /\bphone\b/, /address/,
];

export function isIdentifierField(field: {
  label?: string;
  name?: string;
  id?: string;
  placeholder?: string;
}): boolean {
  const hay = [field.label, field.name, field.id, field.placeholder]
    .filter(Boolean)
    .join(' ')
    .replace(/([a-z])([A-Z])/g, '$1 $2') // split camelCase: studentName -> student Name
    .replace(/[^a-zA-Z]+/g, ' ') // underscores/digits -> spaces: student_id -> student id
    .toLowerCase()
    .trim();
  if (!hay) return false;
  return IDENTIFIER_PATTERNS.some((re) => re.test(hay));
}

function labelOf(node: SnapshotNode): string {
  return (node.attrs['aria-label'] ?? node.attrs['placeholder'] ?? node.attrs['name'] ?? node.attrs['id'] ?? node.text ?? '').trim();
}

function selectorOf(node: SnapshotNode): string {
  if (node.attrs['id']) return `#${node.attrs['id']}`;
  if (node.attrs['name']) return `${node.tag}[name="${node.attrs['name']}"]`;
  if (node.attrs['aria-label']) return `${node.tag}[aria-label="${node.attrs['aria-label']}"]`;
  return node.tag;
}

export function buildProfileFromSnapshot(snapshot: SnapshotResult, url: string): SiteProfile {
  const inputs: InputElement[] = [];
  const buttons: ButtonElement[] = [];
  const links: LinkElement[] = [];

  for (const node of snapshot.nodes) {
    if (node.tag === 'input' || node.tag === 'textarea' || node.tag === 'select') {
      const label = labelOf(node);
      inputs.push({
        label,
        selector: selectorOf(node),
        type: node.attrs['type'],
        identifier: isIdentifierField({ label, name: node.attrs['name'], id: node.attrs['id'], placeholder: node.attrs['placeholder'] }),
      });
    } else if (node.tag === 'button' || node.attrs['role'] === 'button') {
      buttons.push({ text: node.text ?? '', selector: selectorOf(node), disabled: node.attrs['disabled'] === 'true' });
    } else if (node.tag === 'a') {
      links.push({ text: node.text ?? '', selector: selectorOf(node), href: node.attrs['href'] });
    }
  }

  let domain = '';
  let pageName = 'page';
  try {
    const u = new URL(url);
    domain = u.hostname;
    pageName = u.pathname.split('/').filter(Boolean).pop() ?? 'home';
  } catch {
    domain = 'unknown';
  }

  const interactive = { buttons, links, inputs, selects: [], checkboxes: [], radios: [], forms: [] };
  const headings = snapshot.nodes
    .filter((n) => /^h[1-6]$/.test(n.tag) && n.text)
    .map((n) => ({ level: Number(n.tag[1]), text: n.text as string }));

  return {
    url,
    domain,
    pageName,
    profiledAt: new Date().toISOString(),
    interactive,
    headings,
    summary: {
      buttons: buttons.length,
      links: links.length,
      inputs: inputs.length,
      selects: 0,
      checkboxes: 0,
      radios: 0,
      forms: 0,
      landmarks: 0,
      headings: headings.length,
    },
  };
}

/** Selectors/field keys of inputs discovery flagged as identifiers. */
export function identifierFieldKeys(profile: SiteProfile): string[] {
  return profile.interactive.inputs
    .filter((i) => i.identifier)
    .map((i) => i.selector.replace(/^[#.]/, '').replace(/^\w+\[name="(.+)"\]$/, '$1').replace(/^\w+\[aria-label="(.+)"\]$/, '$1'));
}
