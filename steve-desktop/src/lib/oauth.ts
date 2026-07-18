import { open } from '@tauri-apps/plugin-shell';

export interface DeviceFlowResult {
  userCode: string;
  verificationUrl: string;
  poll: () => Promise<{ success: boolean; accessToken?: string; error?: string }>;
  cancel: () => void;
  submitCode?: (code: string) => void;
}

const POLLING_TIMEOUT_MS = 5 * 60 * 1000;
const POLLING_SAFETY_MARGIN_MS = 3000;

const GITHUB_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP'; // OGRE: Iv1.b507a08c87ecfe98
const OPENAI_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP'; // OGRE: app_EMoamEEZ73f0CkXaXp7hrann
const ANTHROPIC_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP'; // OGRE: 9d1c250a-e61b-44d9-88ed-5944d1962f5e
const GOOGLE_CLIENT_ID = 'TODO_REGISTER_STEVE_OAUTH_APP'; // OGRE: UNREGISTERED_GOOGLE_OAUTH_CLIENT

const GITHUB_DEVICE_CODE_URL = 'https://github.com/login/device/code';
const GITHUB_ACCESS_TOKEN_URL = 'https://github.com/login/oauth/access_token';

const OPENAI_DEVICE_CODE_URL = 'https://auth.openai.com/api/accounts/deviceauth/usercode';
const OPENAI_DEVICE_TOKEN_URL = 'https://auth.openai.com/api/accounts/deviceauth/token';

const ANTHROPIC_AUTH_URL = 'https://claude.ai/oauth/authorize';
const ANTHROPIC_TOKEN_URL = 'https://console.anthropic.com/v1/oauth/token';
const ANTHROPIC_SCOPE = 'org:create_api_key user:profile user:inference';
const ANTHROPIC_REDIRECT_URI = 'https://console.anthropic.com/oauth/code/callback';

const GOOGLE_DEVICE_CODE_URL = 'https://oauth2.googleapis.com/device/code';
const GOOGLE_TOKEN_URL = 'https://oauth2.googleapis.com/token';
const GOOGLE_SCOPE = 'https://www.googleapis.com/auth/generative-language.retriever';

const sleep = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

function generateCodeVerifier(): string {
  const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
  const randomValues = crypto.getRandomValues(new Uint8Array(128));
  return Array.from(randomValues, (v) => charset[v % charset.length]).join('');
}

async function generateCodeChallenge(verifier: string): Promise<string> {
  const data = new TextEncoder().encode(verifier);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Buffer.from(hash)
    .toString('base64')
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/, '');
}

function generateState(): string {
  const bytes = crypto.getRandomValues(new Uint8Array(16));
  return Array.from(bytes, (b) => b.toString(16).padStart(2, '0')).join('');
}

export async function startGitHubDeviceFlow(): Promise<DeviceFlowResult> {
  const res = await fetch(GITHUB_DEVICE_CODE_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: JSON.stringify({
      client_id: GITHUB_CLIENT_ID,
      scope: 'read:user',
    }),
  });

  if (!res.ok) throw new Error(`GitHub device code request failed: ${res.status}`);
  const data = await res.json();

  const { device_code, user_code, verification_uri, interval: rawInterval } = data;
  let interval = (rawInterval ?? 5) * 1000 + POLLING_SAFETY_MARGIN_MS;
  let cancelled = false;

  await open(verification_uri);

  return {
    userCode: user_code,
    verificationUrl: verification_uri,
    cancel: () => {
      cancelled = true;
    },
    poll: async () => {
      const deadline = Date.now() + POLLING_TIMEOUT_MS;

      while (!cancelled && Date.now() < deadline) {
        await sleep(interval);
        if (cancelled) return { success: false, error: 'Cancelled' };

        const tokenRes = await fetch(GITHUB_ACCESS_TOKEN_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          body: JSON.stringify({
            client_id: GITHUB_CLIENT_ID,
            device_code,
            grant_type: 'urn:ietf:params:oauth:grant-type:device_code',
          }),
        });

        const tokenData = await tokenRes.json();

        if (tokenData.error === 'authorization_pending') continue;
        if (tokenData.error === 'slow_down') {
          interval += 5000;
          continue;
        }
        if (tokenData.error) {
          return { success: false, error: tokenData.error_description || tokenData.error };
        }

        if (tokenData.access_token) {
          return { success: true, accessToken: tokenData.access_token };
        }
      }

      return { success: false, error: cancelled ? 'Cancelled' : 'Timeout waiting for authorization' };
    },
  };
}

export async function startChatGPTDeviceFlow(): Promise<DeviceFlowResult> {
  const res = await fetch(OPENAI_DEVICE_CODE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ client_id: OPENAI_CLIENT_ID }),
  });

  if (!res.ok) throw new Error(`OpenAI device code request failed: ${res.status}`);
  const data = await res.json();

  const { device_auth_id, user_code, interval: rawInterval } = data;
  const interval = (rawInterval ?? 5) * 1000 + POLLING_SAFETY_MARGIN_MS;
  let cancelled = false;

  const verificationUrl = `https://auth.openai.com/codex/device?user_code=${user_code}`;
  await open(verificationUrl);

  return {
    userCode: user_code,
    verificationUrl,
    cancel: () => {
      cancelled = true;
    },
    poll: async () => {
      const deadline = Date.now() + POLLING_TIMEOUT_MS;

      while (!cancelled && Date.now() < deadline) {
        await sleep(interval);
        if (cancelled) return { success: false, error: 'Cancelled' };

        const tokenRes = await fetch(OPENAI_DEVICE_TOKEN_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            device_auth_id,
            grant_type: 'device_code',
          }),
        });

        const tokenData = await tokenRes.json();

        if (tokenData.error === 'authorization_pending') continue;
        if (tokenData.error === 'slow_down') continue;
        if (tokenData.error) {
          return { success: false, error: tokenData.error_description || tokenData.error };
        }

        if (tokenData.access_token) {
          return { success: true, accessToken: tokenData.access_token };
        }
      }

      return { success: false, error: cancelled ? 'Cancelled' : 'Timeout waiting for authorization' };
    },
  };
}

export async function startAnthropicDeviceFlow(): Promise<DeviceFlowResult> {
  const code_verifier = generateCodeVerifier();
  const code_challenge = await generateCodeChallenge(code_verifier);
  const state = generateState();

  const authUrl = new URL(ANTHROPIC_AUTH_URL);
  authUrl.searchParams.set('code', 'true');
  authUrl.searchParams.set('response_type', 'code');
  authUrl.searchParams.set('client_id', ANTHROPIC_CLIENT_ID);
  authUrl.searchParams.set('redirect_uri', ANTHROPIC_REDIRECT_URI);
  authUrl.searchParams.set('code_challenge', code_challenge);
  authUrl.searchParams.set('code_challenge_method', 'S256');
  authUrl.searchParams.set('scope', ANTHROPIC_SCOPE);
  authUrl.searchParams.set('state', state);

  const verificationUrl = authUrl.toString();
  await open(verificationUrl);

  let resolveCode: ((code: string) => void) | null = null;
  const codePromise = new Promise<string>((resolve) => {
    resolveCode = resolve;
  });

  return {
    userCode: '',
    verificationUrl,
    submitCode: (code: string) => {
      resolveCode?.(code);
    },
    cancel: () => {
      resolveCode?.('');
    },
    poll: async () => {
      const pastedCode = await codePromise;
      if (!pastedCode) return { success: false, error: 'Cancelled' };

      const [code, pastedState] = pastedCode.split('#');
      const res = await fetch(ANTHROPIC_TOKEN_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          grant_type: 'authorization_code',
          client_id: ANTHROPIC_CLIENT_ID,
          code: code.trim(),
          redirect_uri: ANTHROPIC_REDIRECT_URI,
          code_verifier,
          state: pastedState?.trim() || state,
        }),
      });

      if (!res.ok) {
        return { success: false, error: `Token exchange failed (${res.status})` };
      }

      const data = await res.json();
      if (!data.access_token) {
        return { success: false, error: 'No access_token in response' };
      }

      return { success: true, accessToken: data.access_token };
    },
  };
}

export async function startGoogleDeviceFlow(): Promise<DeviceFlowResult> {
  const body = new URLSearchParams({
    client_id: GOOGLE_CLIENT_ID,
    scope: GOOGLE_SCOPE,
  });

  const res = await fetch(GOOGLE_DEVICE_CODE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body,
  });

  if (!res.ok) throw new Error(`Google device code request failed: ${res.status}`);
  const data = await res.json();

  const { device_code, user_code, verification_url, interval: rawInterval } = data;
  let interval = (rawInterval ?? 5) * 1000 + POLLING_SAFETY_MARGIN_MS;
  let cancelled = false;

  await open(verification_url);

  return {
    userCode: user_code,
    verificationUrl: verification_url,
    cancel: () => {
      cancelled = true;
    },
    poll: async () => {
      const deadline = Date.now() + POLLING_TIMEOUT_MS;

      while (!cancelled && Date.now() < deadline) {
        await sleep(interval);
        if (cancelled) return { success: false, error: 'Cancelled' };

        const tokenRes = await fetch(GOOGLE_TOKEN_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({
            client_id: GOOGLE_CLIENT_ID,
            device_code,
            grant_type: 'urn:ietf:params:oauth:grant-type:device_code',
          }),
        });

        const tokenData = await tokenRes.json();

        if (tokenData.error === 'authorization_pending') continue;
        if (tokenData.error === 'slow_down') {
          interval += 5000;
          continue;
        }
        if (tokenData.error) {
          return { success: false, error: tokenData.error_description || tokenData.error };
        }

        if (tokenData.access_token) {
          return { success: true, accessToken: tokenData.access_token };
        }
      }

      return { success: false, error: cancelled ? 'Cancelled' : 'Timeout waiting for authorization' };
    },
  };
}

export async function refreshAnthropicToken(refreshToken: string): Promise<{
  access_token: string;
  refresh_token?: string;
  expires_in?: number;
}> {
  const res = await fetch(ANTHROPIC_TOKEN_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      grant_type: 'refresh_token',
      refresh_token: refreshToken,
      client_id: ANTHROPIC_CLIENT_ID,
    }),
  });

  if (!res.ok) throw new Error(`Token refresh failed: ${res.status}`);
  return res.json();
}

export async function revokeGoogleToken(accessToken: string): Promise<void> {
  try {
    await fetch(`https://oauth2.googleapis.com/revoke?token=${accessToken}`, {
      method: 'POST',
    });
  } catch {
  }
}

export async function validateOllamaConnection(apiUrl: string, apiKey?: string): Promise<boolean> {
  const baseUrl = apiUrl.replace(/\/$/, '');
  const headers: Record<string, string> = {};
  if (apiKey) headers.Authorization = `Bearer ${apiKey}`;

  try {
    const res = await fetch(`${baseUrl}/api/tags`, { headers });
    return res.ok;
  } catch {
    return false;
  }
}
// TODO: unimplemented. Callers get an empty model list, so provider model dropdowns are
// always empty. Typechecks cleanly, so nothing flags it — see ProviderSettings/SetupWizard.
export async function fetchAvailableModels(providerKey: string): Promise<string[]> { return []; }
