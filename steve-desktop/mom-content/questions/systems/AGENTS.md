# Systems Questions — Solving Systems of Linear Equations

**Parent:** `../../AGENTS.md`
**Files:** 1 autograded question covering algebraic elimination for a 2x2 integer-solution system

## OVERVIEW

The systems family currently has one item: solving a 2x2 linear system by elimination, with eight construct-from-solution scenarios that guarantee clean integer answers. The solution guide walks through multiplying equations, subtracting to eliminate x, and back-substituting.

## QUESTION TYPES

| File | Parts | Answer Types | Description |
|------|-------|--------------|-------------|
| `q1-system-2-equations-algebraic.php` | 2 | number, number | x and y values for a randomized 2x2 integer-solution system; 8 parallel-array scenarios |

## CONVENTIONS

1. **Construct from solution.** All 8 scenarios are built by choosing `(solx, soly)` first, then deriving coefficients. This guarantees integer solutions and clean elimination steps.
2. **Parallel-array randomization.** All 8 coefficient arrays plus solution arrays are picked atomically with `jointrandfrom(...)`.
3. **Display helpers.** `$a1x`, `$b1y_disp`, `$b2y_disp` handle the `1x` -> `x` and `-1x` -> `-x` simplifications and sign display. Extend these when adding scenarios with coefficient 1 or -1 for other terms.
4. **Elimination display.** The solution guide precomputes `$mult1 = $a2`, `$mult2 = $a1`, then shows the scaled equations and subtraction step. The `$soly_check` and `$solx_check` values are derived for display -- they should match the stored `$soly` and `$solx`.
5. **Solution guide.** `$solutionguide` uses the standard `.sol-wrap details/summary` collapsible. Step 1 shows the elimination; Step 2 shows back-substitution. AsciiMath backticks wrap all equation fragments.

## ADDING A NEW SYSTEMS QUESTION

1. For a 2x2 system with fractional solutions, copy `q1` and adjust `$abstolerance`. For a 3x3 system, build a new file from scratch using the matrix-question conventions.
2. Always construct from solution first; pick small solution values and derive coefficients so arithmetic in the solution guide stays readable.
3. Add numbered subsection comments in CC (`/* ---------- 1. Randomization ---------- */`, etc.) and place `loadlibrary("stats")` + `$anstypes` immediately after `// === COMMON CONTROL ===`.
4. Set question type to **Multipart** in MOM.

## GOTCHAS

- The back-substitution display at line 114 contains an inline ternary-style concatenation (`$b1 >= 0 ? " + " : " + ("`) -- this is PHP ternary syntax which IMathAS supports. If adding new display logic, prefer `if` blocks for clarity and to match the house style.
- `$abstolerance[0] = 0.01` and `$abstolerance[1] = 0.01` are set even though all current solutions are exact integers. Keep this tolerance in case future scenarios produce non-integer display steps.
- `$solx_check` and `$soly_check` are computed in CC but only used in the solution HTML. If you add more scenarios, verify both check values match the stored solutions before deploying.
