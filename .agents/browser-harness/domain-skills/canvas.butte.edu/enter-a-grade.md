# Enter a grade — canvas.butte.edu

**A grade is not a retryable operation.** This is the one task on this host that writes to a student's
academic record. Read all of it before the first call.

## Never write without this preflight

Refuse the write unless ALL of these hold. Assert them in the same expression that writes, not in a
previous call — state can change between calls.

```js
// 1. right browser: our WebView2, not the personal Chrome
//    (python side) assert "Edg/" in cdp("Browser.getVersion")["product"]
// 2. right role
((await g(`/api/v1/courses/${CID}`)).enrollments||[]).map(e=>e.type)   // must include "teacher"
// 3. rehearsing? then the course must be EMPTY
const c    = await g(`/api/v1/courses/${CID}?include[]=total_students`);
const real = await g(`/api/v1/courses/${CID}/users?enrollment_type[]=student&per_page=5`);
if ((c.b.total_students|0) !== 0 || (real.b||[]).length !== 0) return 'ABORT: course is not empty';
```

## Rehearse here first: the sandboxes

`34903` "SANDBOX2 - S. Huff" — active `TeacherEnrollment`, **0 students**, 37 gradeable assignments,
unpublished. `30504` is a second one. A wrong click in either reaches nobody.

They already contain a **Test Student** (`StudentViewEnrollment`), which is the only gradeable subject
in an empty course:

```
GET /api/v1/courses/{courseId}/users?enrollment_type[]=student_view   -> [{ id, name: "Test Student" }]
```

Confirm `/test student/i.test(name)` before writing. If absent,
`POST /api/v1/courses/{courseId}/student_view_student` creates it.

## The write

Cookie auth requires a CSRF header on every non-GET. The token is the `_csrf_token` cookie,
**URL-decoded**:

```js
const tok = decodeURIComponent((/(?:^|;\s*)_csrf_token=([^;]+)/.exec(document.cookie)||[])[1]||'');
fetch(url, { method:'PUT', credentials:'same-origin',
             headers:{ Accept:'application/json', 'X-CSRF-Token': tok } });
```

```
PUT /api/v1/courses/{courseId}/assignments/{assignmentId}/submissions/{studentId}
    ?submission[posted_grade]={value}
```

## Always: read before, verify after, and be able to revert

Capture the prior score first, so the write is reversible by construction. Clearing a grade is
`posted_grade=` (empty string), which is NOT the same as `0`.

```js
const before  = await g(base);                       // capture
await g(`${base}?submission[posted_grade]=7`, {method:'PUT'});
const after   = await g(base);                       // verify it landed
const restore = (before.b.score ?? '') === '' ? '' : before.b.score;
await g(`${base}?submission[posted_grade]=${encodeURIComponent(restore)}`, {method:'PUT'});
```

Verified 2026-07-29 on `34903` / assignment `985355` / Test Student:
`null → PUT 200 → 7 → revert 200 → null`. No residue.

## Before touching a REAL course

1. **The app's own report is not evidence of what the app did.** Verify the mutation externally — for
   MyOpenMath that is the audit log; for Canvas, re-`GET` the submission *and* check the assignment's
   gradebook history (`/api/v1/courses/{courseId}/gradebook_history/feed`).
2. **One cell, one value, decided in advance.** Never a loop, never a bulk apply, on a first real run.
3. **Every teaching course here is from a past term** (FA25 / SP26 as of 2026-07). Writing to a
   concluded course edits a final grade record. Confirm the target term is intended.
4. Do not infer the target from context. The course, assignment, student and value are the teacher's to
   name.

## Never in a file

No student names, no user ids, no `/users/<id>` literals. Templates only: `{courseId}`,
`{assignmentId}`, `{studentId}`. See `find-missing-work.md` for the id-only rule and the role-403 trap.
