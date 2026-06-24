import { describe, it, expect } from 'vitest';
import { anthropicAuthHeaders, getAnthropicAccessToken } from './anthropic-auth';

describe('anthropicAuthHeaders', () => {
  it('uses OAuth bearer + beta header when signed in', () => {
    const h = anthropicAuthHeaders({ mode: 'oauth', accessToken: 'tok-123' });
    expect(h['Authorization']).toBe('Bearer tok-123');
    expect(h['anthropic-beta']).toContain('oauth-2025-04-20');
    expect(h['x-api-key']).toBeUndefined(); // never send both
  });

  it('uses x-api-key when using an API key', () => {
    const h = anthropicAuthHeaders({ mode: 'apikey', apiKey: 'sk-ant-xyz' });
    expect(h['x-api-key']).toBe('sk-ant-xyz');
    expect(h['Authorization']).toBeUndefined();
  });

  it('always carries the anthropic-version header', () => {
    expect(anthropicAuthHeaders({ mode: 'oauth', accessToken: 't' })['anthropic-version']).toBeTruthy();
    expect(anthropicAuthHeaders({ mode: 'apikey', apiKey: 'k' })['anthropic-version']).toBeTruthy();
  });

  it('refuses OAuth mode without a token rather than sending a broken request', () => {
    expect(() => anthropicAuthHeaders({ mode: 'oauth' })).toThrow();
  });

  it('refuses API-key mode without a key', () => {
    expect(() => anthropicAuthHeaders({ mode: 'apikey', apiKey: '' })).toThrow();
  });
});

describe('getAnthropicAccessToken (via ant CLI)', () => {
  it('returns the trimmed token printed by the ant CLI', async () => {
    const run = async () => '  oat01-abc\n';
    expect(await getAnthropicAccessToken(run)).toBe('oat01-abc');
  });

  it('throws a clear error when the ant CLI is missing', async () => {
    const run = async () => {
      throw new Error('program not found');
    };
    await expect(getAnthropicAccessToken(run)).rejects.toThrow(/ant.? CLI/i);
  });

  it('throws when the CLI returns nothing (not logged in)', async () => {
    const run = async () => '   ';
    await expect(getAnthropicAccessToken(run)).rejects.toThrow();
  });
});
