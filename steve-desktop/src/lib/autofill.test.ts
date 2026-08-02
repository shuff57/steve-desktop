import { describe, it, expect } from 'vitest';
import {
  generateAutoFillScript,
  matchCredentialsToUrl,
  credentialSecrets,
  loginFieldSelectors,
  LMS_LOGIN_SELECTORS,
  type SiteCredential,
} from './autofill';
import { Redactor } from './redact';

describe('generateAutoFillScript — picking one login of several', () => {
  it('yields to values already in the fields on the automatic on-load fill', () => {
    const s = generateAutoFillScript('user', 'pw', false, '');
    expect(s).toContain('var FORCE = false');
    expect(s).toContain('if (!FORCE && (userEl.value || passEl.value))');
  });

  it('overwrites them when a specific login was picked, and re-arms submit', () => {
    // Two logins saved for one site: the on-load fill puts the first one in, so
    // a yielding fill silently keeps it and logs in as the wrong account.
    const s = generateAutoFillScript('user', 'pw', true, '', true);
    expect(s).toContain('var FORCE = true');
    expect(s).toContain('window.__steveAutoSubmitted = false');
  });

  it('fills sites that are not a known LMS instead of doing nothing', () => {
    expect(generateAutoFillScript('user', 'pw')).toContain('findMatchingLms() || GENERIC');
  });

  it('skips passkey-style decoy buttons when auto-submitting', () => {
    expect(generateAutoFillScript('user', 'pw', true)).toContain('passkey|webauthn');
  });
});

describe('generateAutoFillScript — auto-submit option', () => {
  it('does not submit by default', () => {
    const s = generateAutoFillScript('u', 'p');
    expect(s).toContain('var AUTO_SUBMIT = false;');
  });
  it('submits once per page when enabled, guarded against loops', () => {
    const s = generateAutoFillScript('u', 'p', true);
    expect(s).toContain('var AUTO_SUBMIT = true;');
    expect(s).toContain('__steveAutoSubmitted');
    expect(s).toContain('requestSubmit');
  });
  it('yields to the user: never overwrites an already-filled field (account switch)', () => {
    // The guard returns before setInputValue when either field already has a value,
    // so logging out to sign in with a different account is not fought back.
    // Explicitly picking a login from the password list passes force and is the
    // one case that does overwrite — covered above.
    const s = generateAutoFillScript('u', 'p');
    expect(s).toContain('var FORCE = false');
    expect(s).toContain('if (!FORCE && (userEl.value || passEl.value))');
  });
});

describe('loginFieldSelectors — pick the right login fields to capture a password', () => {
  it('uses LMS-specific selectors for a known site', () => {
    const sel = loginFieldSelectors('https://www.myopenmath.com/index.php');
    expect(sel.usernameSelector).toBe('input[name="username"]');
    expect(sel.passwordSelector).toBe('input[name="password"]');
  });
  it('falls back to generic selectors for an unknown site', () => {
    const sel = loginFieldSelectors('https://some-sis.example.edu/login');
    expect(sel.passwordSelector).toBe('input[type="password"]');
    expect(sel.usernameSelector).toContain('email');
  });
});

describe('credentialSecrets — keep saved logins out of any model payload', () => {
  const creds: SiteCredential[] = [
    { id: 1, site_name: 'Canvas', url_pattern: 'instructure.com', username: 'teacher@example.edu', password: 'hunter2' },
    { id: 2, site_name: 'MOM', url_pattern: 'myopenmath.com', username: 'mr_t', password: '' },
  ];

  it('collects every non-empty username and password', () => {
    const secrets = credentialSecrets(creds);
    expect(secrets).toContain('teacher@example.edu');
    expect(secrets).toContain('hunter2');
    expect(secrets).toContain('mr_t');
    expect(secrets).not.toContain(''); // empty password dropped
  });

  it('seeds a Redactor that strips credentials from an outbound payload', () => {
    const redactor = new Redactor(credentialSecrets(creds));
    const out = redactor.redact('login as teacher@example.edu / hunter2');
    expect(out.text).not.toContain('teacher@example.edu');
    expect(out.text).not.toContain('hunter2');
  });
});

describe('generateAutoFillScript', () => {
  it('returns a script string', () => {
    const script = generateAutoFillScript('user@example.com', 'password123');
    expect(typeof script).toBe('string');
    expect(script.length).toBeGreaterThan(0);
  });

  it('includes the username in the script', () => {
    const script = generateAutoFillScript('testuser', 'testpass');
    expect(script).toContain('testuser');
  });

  it('includes the password in the script', () => {
    const script = generateAutoFillScript('testuser', 'testpass');
    expect(script).toContain('testpass');
  });

  it('escapes special characters in username', () => {
    const script = generateAutoFillScript("user'with'quotes", 'pass');
    expect(script).toContain("\\'");
  });

  it('escapes backslashes in password', () => {
    const script = generateAutoFillScript('user', 'pass\\with\\backslash');
    expect(script).toContain('\\\\');
  });

  it('escapes newlines in credentials', () => {
    const script = generateAutoFillScript('user\nname', 'pass\nword');
    expect(script).toContain('\\n');
  });

  it('includes LMS selectors in the script', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain('myopenmath.com');
    expect(script).toContain('instructure.com');
    expect(script).toContain('blackboard.com');
    expect(script).toContain('moodle.org');
  });

  it('includes retry logic with exponential backoff', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain('MAX_RETRIES');
    expect(script).toContain('RETRY_DELAYS');
    expect(script).toContain('1000');
    expect(script).toContain('2000');
    expect(script).toContain('4000');
  });

  it('includes event dispatching for framework compatibility', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain('dispatchEvent');
    expect(script).toContain('input');
    expect(script).toContain('change');
  });

  it('does not auto-submit by default (submit path gated off)', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain('var AUTO_SUBMIT = false;');
  });

  it('handles empty credentials', () => {
    const script = generateAutoFillScript('', '');
    expect(typeof script).toBe('string');
    expect(script.length).toBeGreaterThan(0);
  });

  it('wraps script in IIFE for isolation', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain('(function()');
    expect(script).toContain('})()');
  });

  it('uses strict mode', () => {
    const script = generateAutoFillScript('user', 'pass');
    expect(script).toContain("'use strict'");
  });
});

describe('matchCredentialsToUrl', () => {
  const mockCredentials = [
    {
      id: 1,
      site_name: 'MyOpenMath',
      username: 'user1',
      password: 'pass1',
      url_pattern: 'myopenmath.com',
    },
    {
      id: 2,
      site_name: 'Canvas',
      username: 'user2',
      password: 'pass2',
      url_pattern: 'instructure.com',
    },
    {
      id: 3,
      site_name: 'Custom',
      username: 'user3',
      password: 'pass3',
      url_pattern: 'https://custom.example.com/course/%',
    },
  ];

  it('returns null for empty credentials array', () => {
    const result = matchCredentialsToUrl('https://example.com', []);
    expect(result).toBeNull();
  });

  it('returns null for empty URL', () => {
    const result = matchCredentialsToUrl('', mockCredentials);
    expect(result).toBeNull();
  });

  it('returns null for null credentials', () => {
    const result = matchCredentialsToUrl('https://example.com', null as any);
    expect(result).toBeNull();
  });

  it('matches simple substring pattern', () => {
    const result = matchCredentialsToUrl(
      'https://www.myopenmath.com/course/123',
      mockCredentials
    );
    expect(result).not.toBeNull();
    expect(result?.site_name).toBe('MyOpenMath');
  });

  it('matches case-insensitively', () => {
    const result = matchCredentialsToUrl(
      'https://www.MYOPENMATH.COM/course/123',
      mockCredentials
    );
    expect(result).not.toBeNull();
    expect(result?.site_name).toBe('MyOpenMath');
  });

  it('returns first matching credential', () => {
    const result = matchCredentialsToUrl(
      'https://www.myopenmath.com/course/123',
      mockCredentials
    );
    expect(result?.id).toBe(1);
  });

  it('matches wildcard patterns with %', () => {
    const result = matchCredentialsToUrl(
      'https://custom.example.com/course/123',
      mockCredentials
    );
    expect(result).not.toBeNull();
    expect(result?.site_name).toBe('Custom');
  });

  it('does not match wildcard pattern if base URL differs', () => {
    const result = matchCredentialsToUrl(
      'https://wrong.example.com/course/123',
      mockCredentials
    );
    expect(result?.site_name).not.toBe('Custom');
  });

  it('returns null for no matching pattern', () => {
    const result = matchCredentialsToUrl(
      'https://unknown-site.com/page',
      mockCredentials
    );
    expect(result).toBeNull();
  });

  it('handles special regex characters in pattern', () => {
    const credsWithSpecialChars = [
      {
        id: 1,
        site_name: 'Special',
        username: 'user',
        password: 'pass',
        url_pattern: 'example.com/path?query=value&other=123',
      },
    ];
    const result = matchCredentialsToUrl(
      'https://example.com/path?query=value&other=123',
      credsWithSpecialChars
    );
    expect(result).not.toBeNull();
  });
});

describe('LMS_LOGIN_SELECTORS', () => {
  it('exports selector array', () => {
    expect(Array.isArray(LMS_LOGIN_SELECTORS)).toBe(true);
    expect(LMS_LOGIN_SELECTORS.length).toBeGreaterThan(0);
  });

  it('includes MyOpenMath', () => {
    const mom = LMS_LOGIN_SELECTORS.find((s) => s.name === 'MyOpenMath');
    expect(mom).toBeDefined();
    expect(mom?.urlPattern).toBe('myopenmath.com');
  });

  it('includes Canvas', () => {
    const canvas = LMS_LOGIN_SELECTORS.find((s) => s.name === 'Canvas');
    expect(canvas).toBeDefined();
    expect(canvas?.urlPattern).toBe('instructure.com');
  });

  it('includes Blackboard', () => {
    const bb = LMS_LOGIN_SELECTORS.find((s) => s.name === 'Blackboard');
    expect(bb).toBeDefined();
    expect(bb?.urlPattern).toBe('blackboard.com');
  });

  it('includes Moodle', () => {
    const moodle = LMS_LOGIN_SELECTORS.find((s) => s.name === 'Moodle');
    expect(moodle).toBeDefined();
    expect(moodle?.urlPattern).toBe('moodle.org');
  });

  it('each selector has required fields', () => {
    LMS_LOGIN_SELECTORS.forEach((selector) => {
      expect(selector.name).toBeDefined();
      expect(selector.urlPattern).toBeDefined();
      expect(selector.usernameSelector).toBeDefined();
      expect(selector.passwordSelector).toBeDefined();
    });
  });
});
