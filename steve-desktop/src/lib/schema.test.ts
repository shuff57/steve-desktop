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

  it('has no grading tables', () => {
    expect(schema).not.toContain('grading_session');
    expect(schema).not.toContain('batch_session');
    expect(schema).not.toContain('response_embedding');
    expect(schema).not.toContain('visible_columns');
    expect(schema).not.toContain('rubric');
  });

  it('seeds setup_complete as false', () => {
    expect(schema).toContain("'setup_complete', 'false'");
  });
});
