// === NAME - DESCRIPTION: The Theoretical PDF - P(X = 2) and P(X = 0) from the binomial formula ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// The lab's Theoretical-Distribution step. Parts: (a) numfunc - P(X = 2) ~ 0.2816
// (b) numfunc - P(X = 0) ~ 0.0563.
// Invariant: ~ 0.2816 and ~ 0.0563 on every seed.

$anstypes = array("numfunc", "numfunc")

$answer[0] = 0.2816
$answer[1] = 0.0563
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><span class="term-label">Part (a): P(X = 2).</span> Two diamonds among ten draws can happen in `C(10, 2) = 45` different orders. Take the probability of any one such order and multiply:</p>
      <p>`P(X = 2) = 45 (0.25)^2 (0.75)^8 = 45 (0.0625)(0.1001) ~= 0.2816`</p>
      <p><span class="term-label">Part (b): P(X = 0).</span> There is exactly one way to get no diamonds at all, and `(0.25)^0 = 1`, so the whole expression collapses to the probability that all ten draws miss:</p>
      <p>`P(X = 0) = C(10, 0)(0.25)^0 (0.75)^10 = (0.75)^10 ~= 0.0563`</p>
      <p>The eleven entries of the `P(x)` column must add to exactly 1. Use it as your check: if they total 0.87 you have dropped or mis-entered a row, and the histogram you build next will be wrong in a way that is hard to see by eye.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">For the theoretical distribution `X ~ B(10, 0.25)`, compute the following from the binomial formula.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> `P(X = 2) =`
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> `P(X = 0) =`
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
