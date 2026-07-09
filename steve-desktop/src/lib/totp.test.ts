import { describe, it, expect } from 'vitest';
import { totpNow } from './totp';

// RFC 6238 Appendix B test vectors (SHA-1), seed = ASCII "12345678901234567890"
// -> base32 "GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ". The RFC lists 8-digit codes;
// the 6-digit code is the last 6 of that value.
const SEED = 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ';

describe('totpNow', () => {
  it('matches RFC 6238 vectors (6 digits)', async () => {
    expect(await totpNow(SEED, { t: 59 })).toBe('287082'); // 94287082
    expect(await totpNow(SEED, { t: 1111111109 })).toBe('081804'); // 07081804
    expect(await totpNow(SEED, { t: 1234567890 })).toBe('005924'); // 89005924
  });

  it('tolerates lowercase and spaces in the seed', async () => {
    const spaced = 'gezd gnbv gy3t qojq gezd gnbv gy3t qojq';
    expect(await totpNow(spaced, { t: 59 })).toBe('287082');
  });
});
