import { readFileSync } from 'node:fs';
import { join } from 'node:path';

const schema = readFileSync(join(__dirname, './schema.sql'), 'utf-8');

describe('steve.db schema', () => {
  it('contains all required tables', () => {
    expect(schema).toContain('oauth_tokens');
    expect(schema).toContain('provider_configs');
    expect(schema).toContain('app_settings');
    expect(schema).toContain('skills');
    expect(schema).toContain('site_profiles');
  });

  // Matches a table declaration, not a bare mention — comments are free to name ogre
  // concepts (e.g. "rubric") without tripping this.
  const declaresTable = (name: string) =>
    new RegExp(`CREATE TABLE (IF NOT EXISTS )?${name}\\b`, 'i').test(schema);

  it('has no grading tables', () => {
    for (const t of ['grading_session', 'batch_session', 'response_embedding', 'visible_columns', 'rubrics?']) {
      expect(declaresTable(t)).toBe(false);
    }
  });

  it('seeds setup_complete as false', () => {
    expect(schema).toContain("'setup_complete', 'false'");
  });
});
