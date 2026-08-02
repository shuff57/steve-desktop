# mom-content

The MyOpenMath question bank. This directory is the source of truth — the standalone `mom` repo
it came from was retired on 2026-07-30 and is now a read-only archive.

The app reads AND writes here at runtime, which is why it lives in the repo rather than in app
data: a question the agent writes is a file you can review in a diff.

| Path | What |
|------|------|
| `questions/<family>/[<subtopic>/]*.php` | The questions themselves. 417, all rendering clean. |
| `books/<book>/<track>/<assignment>.json` | Assignment manifests — which questions, in what order, worth what. `.md` sidecars are the blueprint prose. |
| `books/_books.json` | Book registry, so a course with no assignments yet is still listed. |
| `reference/` | Authoring docs the writer agent is pointed at (`reference/index.md`). |

Questions are course content, never student data, so the whole file goes to the model unredacted.
