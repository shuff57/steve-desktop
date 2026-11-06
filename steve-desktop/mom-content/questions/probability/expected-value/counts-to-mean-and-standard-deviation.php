// === NAME - DESCRIPTION: Counts to Mean and Standard Deviation - Turn token counts into chances, read one chance, then find the mean and the standard deviation ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Five bags of 20 tokens (four point values each). Parts: (a) numfunc - P(X = a named value) = count / total;
// (b) numfunc - mu = sum x*P(x); (c) numfunc - sigma = sqrt(sum (x-mu)^2 P(x)).
// Invariant: counts sum to the stated total on every row; mu and sigma rounded to 4 places in the generator.

$anstypes = array("numfunc", "numfunc", "numfunc")

$v0s = array(0, 0, 1, 0, 2)
$v1s = array(1, 1, 2, 5, 4)
$v2s = array(2, 3, 3, 10, 6)
$v3s = array(5, 10, 4, 20, 8)
$c0s = array(8, 10, 5, 12, 4)
$c1s = array(6, 5, 7, 5, 8)
$c2s = array(4, 4, 5, 2, 6)
$c3s = array(2, 1, 3, 1, 2)
$totals = array(20, 20, 20, 20, 20)
$sels = array(2, 3, 3, 10, 6)
$pas = array(0.2, 0.2, 0.25, 0.1, 0.3)
$mus = array(1.2, 1.35, 2.3, 3.25, 4.6)
$sigmas = array(1.4697, 2.2864, 1.005, 5.0683, 1.8)

$picked = jointrandfrom($v0s, $v1s, $v2s, $v3s, $c0s, $c1s, $c2s, $c3s, $totals, $sels, $pas, $mus, $sigmas)
$v0 = $picked[0]
$v1 = $picked[1]
$v2 = $picked[2]
$v3 = $picked[3]
$c0 = $picked[4]
$c1 = $picked[5]
$c2 = $picked[6]
$c3 = $picked[7]
$tot = $picked[8]
$sel = $picked[9]
$answer[0] = $picked[10]
$answer[1] = $picked[11]
$answer[2] = $picked[12]

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
      <p><span class="term-label">Counts to chances.</span> Each token is equally likely, so the chance of a value is its count over the total of ' . $tot . '. That gives the chance table, and the chances add to 1.</p>
      <p><span class="term-label">Part (a).</span> `P(X = ' . $sel . ')` is the number of tokens with that value over ' . $tot . ': <b>' . $answer[0] . '</b>.</p>
      <p><span class="term-label">Part (b).</span> `mu = sum x * P(x) = (' . $v0 . ' x ' . $c0 . ' + ' . $v1 . ' x ' . $c1 . ' + ' . $v2 . ' x ' . $c2 . ' + ' . $v3 . ' x ' . $c3 . ') / ' . $tot . ' = ` <b>' . $answer[1] . '</b>.</p>
      <p><span class="term-label">Part (c).</span> Multiply each squared distance `(x - mu)^2` by its chance, add the four products to get the variance, and take the square root: `sigma = ` <b>' . $answer[2] . '</b>.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A bag holds $tot tokens. One token is drawn at random. `X` is the points on the token drawn.</p>
    <table style="border-collapse:collapse; margin:0 auto; font-size:16px;">
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">Value of a token</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$v0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$v1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$v2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$v3</td>
      </tr>
      <tr>
        <th style="border:1px solid #21242c; padding:6px 14px; background:#f0f4ff;">Number of tokens</th>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$c0</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$c1</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$c2</td>
        <td style="border:1px solid #21242c; padding:6px 14px; text-align:center;">$c3</td>
      </tr>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is `P(X = $sel)`?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the mean `mu`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> What is the standard deviation `sigma`? Round to 4 decimals.
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
