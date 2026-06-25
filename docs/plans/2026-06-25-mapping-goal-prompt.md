# Mapping Goal Prompt (copy-paste)

Reusable prompt for mapping + validating a site in steve-desktop. Fill the
`{{slots}}` from the per-site table, then run it. Engine: browser-harness to
explore, the steve-desktop app's `SiteMapper` to validate.

```
Map and validate {{SITE_NAME}} for the steve-desktop automation layer.

LOGIN
- Site is already logged in (creds saved in the app). Confirm the authenticated
  home (screenshot + page_info). If bounced to an SSO/2FA wall, STOP and ask.

MAP  (mode = {{MODE}})
- page       → capture this one page.
- site       → BFS crawl, same-origin, skip logout/submit/role-switch links.
- generalize → map ONE representative instance ({{INSTANCE_NOTE}}); then open a
               SECOND instance and assert the same selectors resolve. Record only
               class-agnostic selectors, not per-instance URLs/IDs.

VERIFY
- Re-resolve every captured selector live; assert each still matches an element.
- Run site checks: {{VERIFY_CHECKS}}.
- On a broken selector: re-capture that page, diff, fix the map, repeat to green.

PII GATE
- {{PII_RULE}}  (default: run scripts/verify_no_pii.py over the saved profile
  JSON; FAIL the run if any name/email/phone/ID/DOB survived redaction.)

REPORT
- Per page: pass/fail, selector count, PII verdict. List trim suggestions.
- Save nothing containing student data outside the redacted profile JSON.
```

## Per-site slots

| Slot | Keenan SafeSchool | MyOpenMath | Ares LMS | Gmail |
|------|-------------------|------------|----------|-------|
| MODE | site | generalize | site | page |
| INSTANCE_NOTE | — | map one course; re-prove on a second (cid differs) | — | — |
| VERIFY_CHECKS | dashboard → My Assignments → course player; steve anchors resolve | editor + testquestion2 preview; CC/QT/Answer fields locatable | parent-email by structural selector, never value | Compose → To/Subject/Body → Send |
| PII_RULE | standard | standard (rosters) | **HARD — must pass** | standard (compose chrome only) |

## Notes

- **MyOpenMath authoring** reuses `mom/` (`/mom-section-to-questions`); docs are a
  pointer `authoring_docs: ../mom/reference/index.json`, not mapped pages.
- **Ares** — prefer redact-before-save; gate with `verify_no_pii.py` regardless.
- **Keenan** — reuse the `steve` skill for the actual course-taking.
