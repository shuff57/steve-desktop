// === NAME - DESCRIPTION: Phrase to Inequality to Probability - Turn at most, at least, more than, fewer than, no more than and no fewer than into an inequality, then read the cumulative table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five cumulative tables (x = 0..4). Parts: (a) numfunc - the probability for phrase A;
// (b) numfunc - the probability for phrase B; (c) choices - the inequality that matches phrase A.
// Invariant: at most k -> F(k); fewer than k -> F(k-1); at least k -> 1 - F(k-1); more than k -> 1 - F(k).
// Every precomputed probability is checked against that rule.

$anstypes = array("numfunc", "numfunc", "choices")

$f0s = array("0.10", "0.15", "0.20", "0.05", "0.25")
$f1s = array("0.35", "0.40", "0.50", "0.30", "0.45")
$f2s = array("0.65", "0.70", "0.75", "0.60", "0.70")
$f3s = array("0.85", "0.90", "0.90", "0.80", "0.95")
$f4s = array("1.00", "1.00", "1.00", "1.00", "1.00")

$pas = array("at most 2", "at least 3", "no more than 1", "more than 3", "fewer than 2")
$pbs = array("more than 2", "fewer than 3", "no fewer than 2", "at most 3", "at least 2")
$kas = array(2, 3, 1, 3, 2)
$typeAs = array(0, 2, 0, 3, 1)
$ansAs = array(0.65, 0.30, 0.50, 0.20, 0.45)
$ansBs = array(0.35, 0.70, 0.50, 0.80, 0.55)
$paRules = array(
  "at most 2 means `X <= 2`, so read the total at 2.",
  "at least 3 means `X >= 3`, so take 1 minus the total at 2.",
  "no more than 1 means `X <= 1`, so read the total at 1.",
  "more than 3 means `X > 3`, so take 1 minus the total at 3.",
  "fewer than 2 means `X < 2`, so read the total at 1."
)
$pbRules = array(
  "more than 2 means `X > 2`, so take 1 minus the total at 2.",
  "fewer than 3 means `X < 3`, so read the total at 2.",
  "no fewer than 2 means `X >= 2`, so take 1 minus the total at 1.",
  "at most 3 means `X <= 3`, so read the total at 3.",
  "at least 2 means `X >= 2`, so take 1 minus the total at 1."
)
$paMaths = array(
  "F(2) = 0.65",
  "1 - F(2) = 1 - 0.70 = 0.30",
  "F(1) = 0.50",
  "1 - F(3) = 1 - 0.80 = 0.20",
  "F(1) = 0.45"
)
$pbMaths = array(
  "1 - F(2) = 1 - 0.65 = 0.35",
  "F(2) = 0.70",
  "1 - F(1) = 1 - 0.50 = 0.50",
  "F(3) = 0.80",
  "1 - F(1) = 1 - 0.45 = 0.55"
)

$contexts = array(
  "A delivery van makes 0, 1, 2, 3 or 4 stops on a route. `X` is the number of stops.",
  "A student misses 0, 1, 2, 3 or 4 days of class in a month. `X` is the number of missed days.",
  "A machine produces 0, 1, 2, 3 or 4 defective parts in a batch. `X` is the number of defective parts.",
  "A cafe sells 0, 1, 2, 3 or 4 specials at lunch. `X` is the number of specials sold.",
  "A cyclist hits 0, 1, 2, 3 or 4 red lights on a commute. `X` is the number of red lights."
)

$picked = jointrandfrom($f0s, $f1s, $f2s, $f3s, $f4s, $pas, $pbs, $kas, $typeAs, $ansAs, $ansBs, $paRules, $pbRules, $paMaths, $pbMaths, $contexts)
$f0 = $picked[0]
$f1 = $picked[1]
$f2 = $picked[2]
$f3 = $picked[3]
$f4 = $picked[4]
$pa = $picked[5]
$pb = $picked[6]
$ka = $picked[7]
$answer[2] = $picked[8]
$answer[0] = $picked[9]
$answer[1] = $picked[10]
$paRule = $picked[11]
$pbRule = $picked[12]
$paMath = $picked[13]
$pbMath = $picked[14]
$ctx = $picked[15]
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$questions[2] = array(
  "`X <= $ka`",
  "`X < $ka`",
  "`X >= $ka`",
  "`X > $ka`"
)
$noshuffle[2] = "all"

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
      <p><span class="term-label">Part (a): phrase A.</span> ' . $paRule . ' ' . $paMath . ', so the chance is <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b): phrase B.</span> ' . $pbRule . ' ' . $pbMath . ', so the chance is <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c): the inequality.</span> Phrase A is "' . $pa . '". The matching inequality is `' . $answer[2] . '`. "At most" and "no more than" use `<=`; "fewer than" and "less than" use `<`; "at least" and "no fewer than" use `>=`; "more than" uses `>`.</p>
      <p><span class="term-label">Why the strict sign matters.</span> The table is cumulative, so the total at `k` already includes the row for `k`. If the phrase says "fewer than `k`", stop at the total just before `k`. If it says "more than `k`", take everything after the total at `k`.</p>
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
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">4</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">`F(x)`</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f3</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$f4</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the chance that `X` is $pa.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the chance that `X` is $pb.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which inequality matches "$pa"?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
