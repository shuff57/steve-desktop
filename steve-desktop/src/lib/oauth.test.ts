import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';

vi.mock('@tauri-apps/plugin-shell', () => ({
  open: vi.fn(),
}));

import { open } from '@tauri-apps/plugin-shell';
import {
  startAnthropicDeviceFlow,
  startChatGPTDeviceFlow,
  startGitHubDeviceFlow,
  startGoogleDeviceFlow,
  refreshAnthropicToken,
  revokeGoogleToken,
  validateOllamaConnection,
} from './oauth';

type MockFetch = ReturnType<typeof vi.fn>;

function mockJsonResponse(body: unknown, init: ResponseInit = {}): Response {
  return new Response(JSON.stringify(body), {
    status: 200,
    headers: { 'Content-Type': 'application/json' },
    ...init,
  });
}

function generateVerifierFromBytes(bytes: Uint8Array): string {
  const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
  return Array.from(bytes, (v) => charset[v % charset.length]).join('');
}

async function sha256Base64Url(input: string): Promise<string> {
  const data = new TextEncoder().encode(input);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Buffer.from(hash)
    .toString('base64')
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/, '');
}

describe('oauth flows', () => {
  let fetchMock: MockFetch;
  let originalFetch: typeof globalThis.fetch;

  beforeEach(() => {
    fetchMock = vi.fn();
    originalFetch = globalThis.fetch;
    globalThis.fetch = fetchMock as unknown as typeof fetch;
    vi.useFakeTimers();
  });

  afterEach(() => {
    vi.useRealTimers();
    globalThis.fetch = originalFetch;
    vi.restoreAllMocks();
  });

  it('GitHub device flow posts to device code endpoint and polls token endpoint', async () => {
    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        device_code: 'gh-device-code',
        user_code: 'GH-USER',
        verification_uri: 'https://github.com/login/device',
        interval: 0,
      }),
    );

    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        access_token: 'gh-access-token',
        token_type: 'bearer',
      }),
    );

    const flow = await startGitHubDeviceFlow();
    const pollPromise = flow.poll();
    vi.advanceTimersByTime(5000);
    const pollResult = await pollPromise;

    expect(fetchMock).toHaveBeenCalledWith(
      'https://github.com/login/device/code',
      expect.objectContaining({ method: 'POST' }),
    );

    expect(fetchMock).toHaveBeenCalledWith(
      'https://github.com/login/oauth/access_token',
      expect.objectContaining({ method: 'POST' }),
    );

    expect(open).toHaveBeenCalledWith('https://github.com/login/device');
    expect(pollResult).toEqual({ success: true, accessToken: 'gh-access-token' });
  });

  it('OpenAI device flow polls expected token URL', async () => {
    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        device_auth_id: 'openai-device-auth-id',
        user_code: 'OPENAI-CODE',
        interval: 0,
      }),
    );

    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        access_token: 'openai-access-token',
      }),
    );

    const flow = await startChatGPTDeviceFlow();
    const pollPromise = flow.poll();
    vi.advanceTimersByTime(5000);
    const pollResult = await pollPromise;

    expect(fetchMock).toHaveBeenCalledWith(
      'https://auth.openai.com/api/accounts/deviceauth/token',
      expect.objectContaining({ method: 'POST' }),
    );
    expect(pollResult).toEqual({ success: true, accessToken: 'openai-access-token' });
  });

  it('Anthropic flow generates PKCE challenge as SHA-256(verifier)', async () => {
    const verifierBytes = new Uint8Array(128);
    for (let i = 0; i < verifierBytes.length; i += 1) verifierBytes[i] = i;

    const randomSpy = vi
      .spyOn(crypto, 'getRandomValues')
      .mockImplementation(<T extends ArrayBufferView | null>(arr: T): T => {
        if (arr instanceof Uint8Array) {
          if (arr.length === 128) {
            arr.set(verifierBytes);
          } else {
            arr.fill(7);
          }
        }
        return arr;
      });

    const flow = await startAnthropicDeviceFlow();

    const authUrl = new URL(flow.verificationUrl);
    const challenge = authUrl.searchParams.get('code_challenge');

    const expectedVerifier = generateVerifierFromBytes(verifierBytes);
    const expectedChallenge = await sha256Base64Url(expectedVerifier);

    expect(challenge).toBe(expectedChallenge);
    expect(authUrl.searchParams.get('code_challenge_method')).toBe('S256');
    expect(randomSpy).toHaveBeenCalled();
  });

  it('Google flow opens browser with verification URL', async () => {
    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        device_code: 'google-device-code',
        user_code: 'GOOGLE-CODE',
        verification_url: 'https://www.google.com/device',
        interval: 0,
      }),
    );

    await startGoogleDeviceFlow();

    expect(open).toHaveBeenCalledWith('https://www.google.com/device');
    expect(fetchMock).toHaveBeenCalledWith(
      'https://oauth2.googleapis.com/device/code',
      expect.objectContaining({ method: 'POST' }),
    );
  });

  it('validateOllamaConnection returns false on network error', async () => {
    fetchMock.mockRejectedValueOnce(new Error('network down'));

    const ok = await validateOllamaConnection('http://localhost:11434');

    expect(ok).toBe(false);
  });

  it('refreshAnthropicToken calls the correct token endpoint', async () => {
    fetchMock.mockResolvedValueOnce(
      mockJsonResponse({
        access_token: 'new-token',
        refresh_token: 'new-refresh',
        expires_in: 3600,
      }),
    );

    const data = await refreshAnthropicToken('refresh-123');

    expect(fetchMock).toHaveBeenCalledWith(
      'https://console.anthropic.com/v1/oauth/token',
      expect.objectContaining({ method: 'POST' }),
    );

    expect(data.access_token).toBe('new-token');
  });

  it('revokeGoogleToken does best-effort revocation', async () => {
    fetchMock.mockResolvedValueOnce(new Response('', { status: 200 }));

    await expect(revokeGoogleToken('google-access-token')).resolves.toBeUndefined();

    expect(fetchMock).toHaveBeenCalledWith(
      'https://oauth2.googleapis.com/revoke?token=google-access-token',
      expect.objectContaining({ method: 'POST' }),
    );
  });
});
