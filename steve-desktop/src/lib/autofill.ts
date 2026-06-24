// ── Type Definitions ────────────────────────────────────────────────────────

/**
 * Represents a stored site credential for auto-fill.
 */
export interface SiteCredential {
  id: number;
  site_name: string;
  username: string;
  password: string;
  url_pattern: string;
}

// ── LMS Login Form Selectors ────────────────────────────────────────────

/**
 * Selector configuration for a single LMS login form.
 */
interface LmsLoginSelectors {
  /** Human-readable LMS name */
  name: string;
  /** URL substring used to identify the LMS */
  urlPattern: string;
  /** CSS selector for the username/email input */
  usernameSelector: string;
  /** CSS selector for the password input */
  passwordSelector: string;
}

/**
 * Login form selector map for supported LMS platforms.
 * Each entry maps a site name to its login form CSS selectors.
 */
export const LMS_LOGIN_SELECTORS: LmsLoginSelectors[] = [
  {
    name: 'MyOpenMath',
    urlPattern: 'myopenmath.com',
    usernameSelector: 'input[name="username"]',
    passwordSelector: 'input[name="password"]',
  },
  {
    name: 'Canvas',
    urlPattern: 'instructure.com',
    usernameSelector: 'input#pseudonym_session_unique_id',
    passwordSelector: 'input#pseudonym_session_password',
  },
  {
    name: 'Blackboard',
    urlPattern: 'blackboard.com',
    usernameSelector: 'input#user_id',
    passwordSelector: 'input#password',
  },
  {
    name: 'Moodle',
    urlPattern: 'moodle.org',
    usernameSelector: 'input#username',
    passwordSelector: 'input#password',
  },
];

// ── Login field discovery (for capturing a password off the live page) ──────

export interface LoginFieldSelectors {
  usernameSelector: string;
  passwordSelector: string;
}

// Generic fallback when the site isn't a known LMS: first password input + a likely username.
const GENERIC_LOGIN: LoginFieldSelectors = {
  usernameSelector:
    'input[type="email"], input[name*="user" i], input[name*="email" i], input[type="text"]',
  passwordSelector: 'input[type="password"]',
};

/** CSS selectors for the username/password fields on the page — LMS-specific when known. */
export function loginFieldSelectors(url: string): LoginFieldSelectors {
  const u = (url || '').toLowerCase();
  const lms = LMS_LOGIN_SELECTORS.find((s) => u.includes(s.urlPattern));
  return lms
    ? { usernameSelector: lms.usernameSelector, passwordSelector: lms.passwordSelector }
    : GENERIC_LOGIN;
}

// ── Auto-Fill Script Generator ──────────────────────────────────────────

/**
 * Generate a JavaScript string that, when injected into a webview page,
 * will attempt to fill username and password fields for known LMS login forms.
 *
 * The generated script:
 * - Checks the current page URL against known LMS patterns
 * - Finds login fields using the corresponding CSS selectors
 * - Sets values and dispatches 'input' events (for React/Angular compatibility)
 * - Retries up to 3 times with exponential backoff (1s, 2s, 4s)
 * - Does NOT auto-submit the form
 *
 * @param username - The username to fill
 * @param password - The password to fill
 * @returns JavaScript code string ready for webview injection
 */
export function generateAutoFillScript(username: string, password: string, autoSubmit = false): string {
  // Escape special characters for safe embedding in JS string literals
  const safeUsername = escapeJsString(username);
  const safePassword = escapeJsString(password);

  // Serialize the selector map into the injected script
  const selectorsJson = JSON.stringify(LMS_LOGIN_SELECTORS);

  return `
(function() {
  'use strict';

  var SELECTORS = ${selectorsJson};
  var MAX_RETRIES = 3;
  var RETRY_DELAYS = [1000, 2000, 4000];
  var AUTO_SUBMIT = ${autoSubmit ? 'true' : 'false'};

  /**
   * Find the matching LMS config for the current page URL.
   */
  function findMatchingLms() {
    var url = window.location.href.toLowerCase();
    for (var i = 0; i < SELECTORS.length; i++) {
      if (url.indexOf(SELECTORS[i].urlPattern) !== -1) {
        return SELECTORS[i];
      }
    }
    return null;
  }

  /**
   * Set a value on an input element and dispatch events for framework compatibility.
   */
  function setInputValue(el, value) {
    // Use native setter to bypass React/Angular controlled input wrappers
    var nativeSetter = Object.getOwnPropertyDescriptor(
      window.HTMLInputElement.prototype, 'value'
    );
    if (nativeSetter && nativeSetter.set) {
      nativeSetter.set.call(el, value);
    } else {
      el.value = value;
    }

    // Dispatch events that frameworks listen for
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  /**
   * Attempt to fill the login form fields.
   * Returns true if both fields were found and filled.
   */
  function tryFill(lms) {
    var userEl = document.querySelector(lms.usernameSelector);
    var passEl = document.querySelector(lms.passwordSelector);

    if (!userEl || !passEl) {
      return false;
    }

    setInputValue(userEl, '${safeUsername}');
    setInputValue(passEl, '${safePassword}');

    // Optionally submit — once per page load, so a failed login can't loop.
    if (AUTO_SUBMIT && !window.__steveAutoSubmitted) {
      window.__steveAutoSubmitted = true;
      var form = passEl.form || userEl.form;
      if (form) {
        setTimeout(function() {
          var btn = form.querySelector('button[type=submit], input[type=submit]');
          if (btn) { btn.click(); }
          else if (form.requestSubmit) { form.requestSubmit(); }
          else { form.submit(); }
        }, 400);
      }
    }
    return true;
  }

  /**
   * Main entry: match LMS, then try fill with retries.
   */
  function main() {
    var lms = findMatchingLms();
    if (!lms) {
      return;
    }


    var attempt = 0;

    function attemptFill() {
      if (tryFill(lms)) {
        return;
      }

      attempt++;
      if (attempt < MAX_RETRIES) {
        setTimeout(attemptFill, RETRY_DELAYS[attempt - 1]);
      } else {
      }
    }

    attemptFill();
  }

  // Run after DOM is ready (or immediately if already loaded)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', main);
  } else {
    main();
  }
})();
`;
}

// ── Credential-URL Matching ─────────────────────────────────────────────

/**
 * Converts SQL-style wildcard pattern (%) to regex pattern
 * Escapes special regex characters except %
 */
function wildcardToRegex(pattern: string): RegExp {
  // Escape special regex characters except %
  const escaped = pattern.replace(/[.+?^${}()|[\]\\]/g, '\\$&');
  // Convert % to .* (match any characters)
  const regexPattern = escaped.replace(/%/g, '.*');
  // Match entire string (anchored)
  return new RegExp(`^${regexPattern}$`, 'i');
}

/**
 * Find the best matching credential for a given URL.
 *
 * Checks each credential's `url_pattern` against the URL.
 * Returns the first credential whose url_pattern is contained in the URL,
 * or null if no match is found.
 *
 * @param url - The page URL to match against
 * @param credentials - Array of stored site credentials
 * @returns The best matching SiteCredential, or null
 */
export function matchCredentialsToUrl(
  url: string,
  credentials: SiteCredential[],
): SiteCredential | null {
  if (!url || !credentials || credentials.length === 0) {
    return null;
  }

  const normalizedUrl = url.toLowerCase();

  for (const cred of credentials) {
    const pattern = cred.url_pattern.toLowerCase();

    // If pattern contains %, treat as wildcard pattern
    if (pattern.includes('%')) {
      const regex = wildcardToRegex(pattern);
      if (regex.test(normalizedUrl)) {
        return cred;
      }
    } else {
      // No wildcards - use simple substring matching
      if (normalizedUrl.includes(pattern)) {
        return cred;
      }
    }
  }

  return null;
}

// ── Redactor registration ───────────────────────────────────────────────

/**
 * Every saved username and password, so they can be registered as redactor
 * secrets. AGENTS.md trust-boundary rule: credentials must never reach a model.
 * Seed a Redactor with these and any outbound payload has them stripped.
 */
export function credentialSecrets(credentials: SiteCredential[]): string[] {
  const secrets: string[] = [];
  for (const cred of credentials) {
    if (cred.username) secrets.push(cred.username);
    if (cred.password) secrets.push(cred.password);
  }
  return secrets;
}

// ── Helpers ─────────────────────────────────────────────────────────────

/**
 * Escape a string for safe embedding inside a JavaScript single-quoted string literal.
 * Handles backslashes, single quotes, newlines, and carriage returns.
 */
function escapeJsString(s: string): string {
  return s
    .replace(/\\/g, '\\\\')
    .replace(/'/g, "\\'")
    .replace(/\n/g, '\\n')
    .replace(/\r/g, '\\r');
}
