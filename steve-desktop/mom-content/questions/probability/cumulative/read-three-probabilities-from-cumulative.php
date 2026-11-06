// === NAME - DESCRIPTION: Read Three Probabilities from a Cumulative Table - Convert more than, between and fewer than to inequalities, then subtract cumulative entries ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four cumulative tables (x = 0..3). Parts: (a) P(X > 1); (b) P(1 <= X <= 2); (c) P(X < 2).
// Invariant: (a) = 1 - F(1), (b) = F(2) - F(0), (c) = F(1), and all three lie in [0, 1].

$anstypes = array("numfunc", "numfunc", "numfunc")

$f0s = array("0.15", "0.20", "0.10", "0.25")
$f1s = array("0.50", "0.45", "0.35", "0.60")
$f2s = array("0.80", "0.75", "0.70", "0.85")
$f3s = array("1.00", "1.00", "1.00", "1.00")
$gt1s = array(0.50, 0.55, 0.65, 0.40)
$betweens = array(0.65, 0.55, 0.60, 0.60)
$lt2s = array(0.50, 0.45, 0.35, 0.60)

$contexts = array(
  "A delivery van makes 0, 1, 2 or 3 stops on a short route. `X` is the number of stops.",
  "A student misses 0, 1, 2 or 3 days of class in a month. `X` is the number of missed days.",
  "A machine produces 0, 1, 2 or 3 defective parts in a batch. `X` is the number of defective parts.",
  "A cafe sells 0, 1, 2 or 3 specials at lunch. `X` is the number of specials sold."
)

$picked = jointrandfrom($f0s, $f1s, $f2s, $f3s, $gt1s, $betweens, $lt2s, $contexts)
$f0 = $picked[0]
$f1 = $picked[1]
$f2 = $picked[2]
$f3 = $picked[3]
$answer[0] = $picked[4]
$answer[1] = $picked[5]
$answer[2] = $picked[6]
$ctx = $picked[7]
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
      <p><span class="term-label">Part (a): more than 1.</span> More than 1 means `X > 1`, so start at 1 and take what is left: `1 - F(1) = 1 - ' . $f1 . ' = ` <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): between 1 and 2, inclusive.</span> Between 1 and 2 inclusive means `1 <= X <= 2`. The total at 2 counts 0 and 1 as well, so subtract the total at 0: `F(2) - F(0) = ' . $f2 . ' - ' . $f0 . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): fewer than 2.</span> Fewer than 2 means `X < 2`, which is `X <= 1` for whole-number values. Read the total at 1: <b>' . $answer[2] . '</b>.</p>
      <p><span class="term-label">The habit.</span> Write the phrase as an inequality first, then decide which cumulative entries to subtract. The strict sign matters: `X < 2` stops before 2, while `X <= 2` includes it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">$ctx The table gives the cumulative chance `F(x) = P(X <= x)`.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`x`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`F(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the chance that `X` is more than 1.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the chance that `X` is between 1 and 2, inclusive.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the chance that `X` is fewer than 2.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
