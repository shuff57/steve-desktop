// Anthropic auth: two legitimate modes, side by side.
//  - 'oauth'  : browser login via the Anthropic `ant` CLI (no pasted key).
//               The CLI mints/refreshes a short-lived token; we send it as a
//               Bearer token with the oauth beta header.
//  - 'apikey' : a console API key sent as x-api-key.
// Both bill the Anthropic API account — OAuth login is not a consumer subscription.

export type AnthropicAuthMode = 'oauth' | 'apikey';

export interface AnthropicCreds {
  mode: AnthropicAuthMode;
  apiKey?: string;
  accessToken?: string;
}

const ANTHROPIC_VERSION = '2023-06-01';

export function anthropicAuthHeaders(creds: AnthropicCreds): Record<string, string> {
  if (creds.mode === 'oauth') {
    if (!creds.accessToken) {
      throw new Error('Anthropic OAuth selected but no access token is available — sign in first.');
    }
    return {
      Authorization: `Bearer ${creds.accessToken}`,
      'anthropic-beta': 'oauth-2025-04-20',
      'anthropic-version': ANTHROPIC_VERSION,
    };
  }
  if (!creds.apiKey) {
    throw new Error('Anthropic API key selected but no key was provided.');
  }
  return {
    'x-api-key': creds.apiKey,
    'anthropic-version': ANTHROPIC_VERSION,
  };
}

/**
 * Fetch a fresh OAuth access token from the Anthropic `ant` CLI. `run` executes
 * `ant auth print-credentials --access-token` (the CLI refreshes the token if
 * needed) and returns its stdout — injected so this is testable and so the Tauri
 * shell plugin owns the actual process spawn.
 */
export async function getAnthropicAccessToken(run: () => Promise<string>): Promise<string> {
  let out: string;
  try {
    out = await run();
  } catch {
    throw new Error('Could not run the Anthropic `ant` CLI. Install it and run `ant auth login` to use browser sign-in.');
  }
  const token = out.trim();
  if (!token) {
    throw new Error('Not signed in to Anthropic. Run `ant auth login` (or use an API key instead).');
  }
  return token;
}
