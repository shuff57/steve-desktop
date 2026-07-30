# Find missing work — canvas.butte.edu

Answer "who/what is missing work" for a Canvas course **without reading a single student name**.

## Rule for this host: no student identity, ever

This account is a teacher's. Student data here is FERPA-protected and the standing constraint is
**ids only, never names** — including in the transcript. So:

- **Do not scrape the gradebook DOM.** The names are in the markup; reading `innerText` puts them in
  the transcript whether you wanted them or not.
- **Use the REST API and project fields inside the page**, so only the fields you name cross the CDP
  boundary. `js()` returns what the expression returns — make the expression return counts or ids.
- Never pass `include[]=user`, never select `user`, `user_name`, `login_id`, `sis_user_id`, `email`.
- Nothing identifying goes in a file. Templates only: `{courseId}`, `{assignmentId}`, `{studentId}`.

## Fastest correct route: `submission_summary` (zero identifiers at all)

Per-assignment counts, no user ids in the response. Prefer this — it cannot leak identity.

```
GET /api/v1/courses/{courseId}/assignments?per_page=100
GET /api/v1/courses/{courseId}/assignments/{assignmentId}/submission_summary
    -> { graded, ungraded, not_submitted }
```

`not_submitted` is the missing count. Sum over published assignments for the course total.

## Two traps that cost a run each

**1. Check the role before believing a 403.** `submission_summary` returns
`403 {"status":"unauthorized"}` when the role is not a grader. It is not a bug and not a session
problem. Course `31407` is a **`designer`** enrolment — every grader endpoint 403s there while
`/assignments` still returns 200, which looks exactly like a broken session.

Confirm the role from the course object, not from `enrollments?user_id=self` (that returned `[]` on a
course where the account really is teacher):

```js
(await g(`/api/v1/courses/${cid}`)).enrollments.map(e => e.type)   // ["teacher"] | ["designer"] | ...
```

To list courses that actually permit grading: `GET /api/v1/courses?enrollment_type=teacher&per_page=50`.

**2. Do not use `needs_grading_count` as a permission probe.** It is absent from the assignments index
unless requested via `include[]`, so its absence proves nothing about grading rights. It read as
"no grader access" on a course whose `submission_summary` returned 200.

## Sequential fetches time out — parallelise inside the page

28 sequential `submission_summary` calls exceeded the IPC read timeout and killed the whole
`Runtime.evaluate` (`helpers.py:117`). One `Promise.all` over the assignment list completes in seconds.

```js
const rows = await Promise.all(assigns.map(async a => {
  const s = await g(`/api/v1/courses/${cid}/assignments/${a.id}/submission_summary`);
  return s && { t: a.name, due: a.due_at, missing: s.not_submitted|0, ungraded: s.ungraded|0 };
}));
```

## Per-student detail, only if actually needed

`submission_summary` gives no ids. If a specific student's gaps are required, use ids and stop there —
resolve id → name in the app UI, never in the agent:

```
GET /api/v1/courses/{courseId}/students/submissions?student_ids[]=all&per_page=100
    -> select ONLY { assignment_id, user_id, workflow_state, missing, late, excused }
```

Same 403 rule applies: grader role required.

## Driving the browser on this machine

The app's own tab layer may be wedged; do not depend on it and do not call `ensure_real_tab()` — the
only pre-existing page target is the app UI at `localhost:5174` and it can be navigated away.

```
BH_TELEMETRY=0 BU_NAME=steve BU_CDP_URL=http://127.0.0.1:<port> browser-harness
```

`BU_NAME` is mandatory: the `default` daemon holds a connection to the personal Chrome and ignores a
later `BU_CDP_URL`. Assert `cdp("Browser.getVersion")["product"]` starts with `Edg/` before acting.
Then make your own target and close it in a `finally`:

```python
tid = cdp("Target.createTarget", url=f"https://canvas.butte.edu/courses/{cid}")["targetId"]
try:    js("…", target_id=tid)
finally: cdp("Target.closeTarget", targetId=tid)
```

Canvas LTI iframes appear as extra `type == "iframe"` targets and are reaped shortly after the page
target closes — a transient count above your own target is normal, not a leak.

## Verified

2026-07-29, course `40160` (teacher, 34 students): 20 published assignments, **77 not-submitted**,
1 ungraded, 602 graded. Zero names and zero user ids crossed the boundary.
