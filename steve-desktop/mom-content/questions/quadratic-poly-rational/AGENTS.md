# Quadratic, Polynomial, and Rational Questions

**Parent:** `../../AGENTS.md`
**Files:** 3 auto-graded questions covering quadratic vertex/intercepts, polynomial end behavior, and rational asymptotes

## OVERVIEW

These questions were drafted for a College Algebra-style course. The deployed Applied Finite Math course does not use them -- they are kept here for future-semester use if the course scope expands to include these topics. All three are auto-graded multipart questions with no FRQ component.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-quadratic-vertex-and-x-intercepts.php` | 3 | number, number, ntuple (set) | Find vertex (x, y) and x-intercepts of a randomized quadratic with clean integer roots |
| `q2-polynomial-end-behavior.php` | 2 | choices, choices | Describe end behavior as x→+∞ and x→−∞ using the leading term test; select dropdown |
| `q3-rational-asymptotes.php` | 3 | number, number, choices | Find vertical asymptote, horizontal asymptote, and domain of a linear rational function |

## CONVENTIONS

1. **Auto-graded only.** No essay or FRQ parts.
2. **Randomization via `jointrandfrom(...)`.** All scenarios and their precomputed answers live in parallel arrays indexed together. No in-flight calculation of graded answers -- every answer in `$answer[n]` is pulled from a precomputed array value.
3. **Select dropdowns with `$noshuffle = "all"`.** Any part using `$displayformat[n] = "select"` with an index-based correct answer (`$answer[n] = 0` or `1`) must also set `$noshuffle[n] = "all"` to prevent MOM from shuffling the choices list.
4. **Solution guide pattern.** Each file builds a `$solutionguide` string in Common Control using the `.sol-wrap` details/summary collapsible. Referenced in the Answer section after `///`.
5. **Card-per-part layout.** Question text uses the style-guide card-per-part layout with chip labels (a., b., c.) and `<span style="margin-left:8px;">$answerbox[n]</span>` inline.
6. **`loadlibrary("stats")` at top of Common Control.** Required by house style even when stats functions are not used.
7. **No blocked functions.** Use `^` for exponentiation (not `pow()`), `jointrandfrom()` for randomization (not `array_rand()`, `shuffle()`). No `implode()`, `array_slice()`, `number_format()`, `exp()`.

## ADDING A NEW QUESTION

1. Identify the answer shape: numeric parts use `number`, option lists use `choices` with `$displayformat[n] = "select"` and `$noshuffle[n] = "all"`, set answers use `ntuple` with `$displayformat[n] = "set"` and `$answerformat[n] = "anyorder"`.
2. Build 4-5 scenarios as parallel arrays. Precompute every graded answer -- do not derive answers in the question text section.
3. Use `jointrandfrom(...)` to pick one scenario row. Assign picked values to named scalar variables before building the question text or solution guide.
4. Build `$solutionguide` as a string variable in Common Control with the `.sol-wrap` collapsible pattern. Reference it in the Answer section.
5. Set question type to **Multipart** in the MOM editor.

## GOTCHAS

- `$answer[n] = 0` with a shuffled choices dropdown will grade wrong answers as correct whenever the chosen row is not first in the rendered list. Always pair `$displayformat[n] = "select"` with `$noshuffle[n] = "all"` when the answer is index-based.
- `ntuple` set answers require both `$displayformat[n] = "set"` AND `$answerformat[n] = "anyorder"`. Either alone is insufficient.
- Quadratic discriminant must be a perfect square for roots to be clean integers. Verify each scenario offline before adding it to the parallel arrays.
- `round($x^0.5, 0)` is the correct way to compute an integer square root. `pow()` is blocked; `sqrt()` availability varies -- use `$x^0.5` instead.
