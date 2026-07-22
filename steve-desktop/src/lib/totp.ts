// TOTP (RFC 6238) code generation for saved 2FA secrets.
// Uses the webview's built-in Web Crypto — no dependency. The base32 seed is
// what a site shows as "can't scan the QR? enter this key" during 2FA setup.

const BASE32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

/** Decode an RFC 4648 base32 string (case/space/padding tolerant) to bytes. */
function base32Decode(s: string): Uint8Array {
  let bits = 0;
  let value = 0;
  const out: number[] = [];
  for (const ch of s.toUpperCase()) {
    const idx = BASE32.indexOf(ch);
    if (idx === -1) continue; // skip spaces, '=', and any stray character
    value = (value << 5) | idx;
    bits += 5;
    if (bits >= 8) {
      bits -= 8;
      out.push((value >>> bits) & 0xff);
    }
  }
  return new Uint8Array(out);
}

/**
 * Current TOTP code for a base32 secret. Async because HMAC runs on Web Crypto.
 * @param opts.t override the unix time (seconds) — for tests. Defaults to now.
 */
export async function totpNow(
  secret: string,
  opts: { digits?: number; period?: number; t?: number } = {},
): Promise<string> {
  const digits = opts.digits ?? 6;
  const period = opts.period ?? 30;
  const t = opts.t ?? Math.floor(Date.now() / 1000);

  // 8-byte big-endian counter = floor(time / period).
  let counter = Math.floor(t / period);
  const msg = new Uint8Array(8);
  for (let i = 7; i >= 0; i--) {
    msg[i] = counter & 0xff;
    counter = Math.floor(counter / 256);
  }

  const key = await crypto.subtle.importKey(
    'raw',
    // Cast: lib.dom types Uint8Array's backing buffer as ArrayBufferLike (could be SharedArrayBuffer),
    // which no longer satisfies BufferSource under TS 5.7. The decode always yields a plain ArrayBuffer.
    base32Decode(secret) as BufferSource,
    { name: 'HMAC', hash: 'SHA-1' },
    false,
    ['sign'],
  );
  const hmac = new Uint8Array(await crypto.subtle.sign('HMAC', key, msg));

  // Dynamic truncation (RFC 4226 §5.3).
  const offset = hmac[hmac.length - 1] & 0x0f;
  const bin =
    ((hmac[offset] & 0x7f) << 24) |
    (hmac[offset + 1] << 16) |
    (hmac[offset + 2] << 8) |
    hmac[offset + 3];
  return (bin % 10 ** digits).toString().padStart(digits, '0');
}
