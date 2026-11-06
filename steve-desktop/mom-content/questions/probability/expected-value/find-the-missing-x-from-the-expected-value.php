// === NAME - DESCRIPTION: Find the Missing x from the Expected Value - The expected value is given and one x value is hidden: add the known rows, solve for the missing x, then scale to 50 plays ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five tables with three known x values and one hidden value v (5 to 10). The mean is given.
// Parts: (a) numfunc - sum of the three known x*P(x) terms; (b) numfunc - the hidden x; (c) numfunc - expected total of 50 plays.
// Invariant: probabilities sum to 1 and (mu - known) / p3 = v on every row (checked in the generator).

$anstypes = array("numfunc", "numfunc", "numfunc")

$x1s = array(1, 0, 1, 2, 0)
$x2s = array(2, 2, 3, 4, 1)
$x3s = array(3, 4, 5, 6, 2)
$p0s = array("0.30", "0.20", "0.10", "0.25", "0.35")
$p1s = array("0.25", "0.30", "0.40", "0.25", "0.30")
$p2s = array("0.25", "0.30", "0.30", "0.30", "0.25")
$p3s = array("0.20", "0.20", "0.20", "0.20", "0.10")
$mus = array(2.75, 3.4, 4.2, 5.3, 1.7)
$knowns = array(1.55, 1.8, 2.8, 3.3, 0.8)
$vs = array(6, 8, 7, 10, 9)
$totals = array(137.5, 170, 210, 265, 85)
$ctxs = array("A player earns points on one turn of a board game. `X` is the points earned.", "A spinner awards points on each spin. `X` is the points awarded.", "A quiz round awards points for each team. `X` is the points awarded.", "A carnival booth awards points for each try. `X` is the points awarded.", "A game show gives out points on each question. `X` is the points given.")

$picked = jointrandfrom($x1s, $x2s, $x3s, $p0s, $p1s, $p2s, $p3s, $mus, $knowns, $vs, $totals, $ctxs)
$x1 = $picked[0]
$x2 = $picked[1]
$x3 = $picked[2]
$pd0 = $picked[3]
$pd1 = $picked[4]
$pd2 = $picked[5]
$pd3 = $picked[6]
$mu = $picked[7]
$answer[0] = $picked[8]
$answer[1] = $picked[9]
$answer[2] = $picked[10]
$ctx = $picked[11]

$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

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
      <p><span class="term-label">The idea.</span> The mean is the sum of every `x * P(x)`. Three rows are known, so add them first. What is left of the mean belongs to the missing row.</p>
      <p><span class="term-label">Part (a).</span> `' . $x1 . '(' . $pd0 . ') + ' . $x2 . '(' . $pd1 . ') + ' . $x3 . '(' . $pd2 . ') = ` <b>' . $answer[0] . '</b></p>
      <p><span class="term-label">Part (b).</span> The missing row must supply `' . $mu . ' - ' . $answer[0] . '`. That is the missing `x` times ' . $pd3 . ', so the missing `x = (' . $mu . ' - ' . $answer[0] . ') / ' . $pd3 . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c).</span> The mean is the average per play, so 50 plays are expected to total `50 x ' . $mu . ' = ` <b>' . $answer[2] . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The mean is `mu = $mu`. One value of `x` is hidden.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$x1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$x2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$x3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">?</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`P(X = x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$pd3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Add `x * P(x)` for the three rows where `x` is known. What is the total?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the missing value of `x`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> The game is played 50 times. What total do you expect?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
