/**
 * mom-island templates — known starting points for new questions. Each entry
 * points at a real `mom/questions/{family}/q*-*.php` file in the user's mom
 * repo. The UI lets the user pick a template and `createDraft` copies the
 * file to a working location (Tauri app-data) for editing.
 *
 * The list is hardcoded for now: the goal is to make "new question" feel
 * like a two-click action — pick a family, get a working file. When the user
 * reports missing families, add them here.
 */
export interface MomTemplate {
  family: string;
  /** Display label shown in the picker. */
  label: string;
  /** Path under <MOM_ROOT>/questions/<family>/... */
  sourcePath: string;
  /** Default answer type the new question starts as. */
  anstype: string;
}

const TEMPLATES: MomTemplate[] = [
  {
    family: 'frq',
    label: 'FRQ — free-response numeric',
    sourcePath: 'frq/descriptive-statistics/q1-test.php',
    anstype: 'num',
  },
  {
    family: 'mcq',
    label: 'MCQ — multiple choice',
    sourcePath: 'mcq/basics/q1-test.php',
    anstype: 'choices',
  },
  {
    family: 'tf',
    label: 'True / False',
    sourcePath: 'tf/basics/q1-test.php',
    anstype: 'tf',
  },
  {
    family: 'num',
    label: 'Numerical (typed)',
    sourcePath: 'num/basics/q1-test.php',
    anstype: 'num',
  },
  {
    family: 'essay',
    label: 'Essay / long-form',
    sourcePath: 'essay/basics/q1-test.php',
    anstype: 'essay',
  },
];

export function getTemplates(): readonly MomTemplate[] {
  return TEMPLATES;
}

export function findTemplate(family: string): MomTemplate | null {
  return TEMPLATES.find((t) => t.family === family) ?? null;
}
