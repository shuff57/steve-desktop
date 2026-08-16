// === NAME - DESCRIPTION: The Two Sanity Checks - catch a probability bigger than 1 or negative ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities. Each seed shows three computed values: the correct probability, an
// IMPOSSIBLE one (either > 1 or negative), and a plausible-but-wrong one. Parts: (a) choices -
// which listed value is impossible; (b) numfunc - the correct probability; (c) choices - which
// sanity check catches the impossible value.
// Invariant: exactly one listed value is impossible; (b) is the correct value in (0,1); (c)
// names the check that catches the wrong one.
//   S0: U(0,20) strip (2,10): 0.4; impossible 1.4 (>1); wrong 0.8
//   S1: U(1,9)  strip (2,6):  0.5; impossible -0.25 (neg); wrong 0.25
//   S2: U(0,10) strip (3,7):  0.4; impossible 1.6 (>1); wrong 0.7
//   S3: U(2,12) strip (3,5):  0.2; impossible -0.5 (neg); wrong 0.3

$anstypes = array("choices", "numfunc", "choices")

$as = array(0, 1, 0, 2)
$bs = array(20, 9, 10, 12)
$cs = array(2, 2, 3, 3)
$ds = array(10, 6, 7, 5)
$corrects = array(0.4, 0.5, 0.4, 0.2)
$imposs = array(1.4, -0.25, 1.6, -0.5)
$wrongs = array(0.8, 0.25, 0.7, 0.3)
$bigger = array(0, 1, 0, 1)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$c = $cs[$i]
$d = $ds[$i]

$pos = rand(0, 2)
$opt0 = $corrects[$i]
$opt1 = $imposs[$i]
$opt2 = $wrongs[$i]
if ($pos == 1) {
  $opt0 = $imposs[$i]
  $opt1 = $corrects[$i]
}
if ($pos == 2) {
  $opt0 = $wrongs[$i]
  $opt1 = $corrects[$i]
  $opt2 = $imposs[$i]
}

$questions[0] = array("" . $opt0 . "", "" . $opt1 . "", "" . $opt2 . "")
$answer[0] = $pos
$noshuffle[0] = "all"

$answer[1] = $corrects[$i]
$abstolerance[1] = 0.005

$questions[2] = array(
  "The value is bigger than 1",
  "The value is negative",
  "Neither — the checks only catch setup mistakes, and the setup here is fine"
)
$answer[2] = $bigger[$i]
$noshuffle[2] = "all"

$impossibleVal = $imposs[$i]

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
      <p><span class="term-label">The correct probability.</span> The strip runs from `x = ' . $c . '` to `x = ' . $d . '`, so the base is `' . ($d - $c) . '` and the height is `1/(' . ($b - $a) . ')`:</p>
      <p>`P(' . $c . ' < x < ' . $d . ') = (' . ($d - $c) . ')(1/' . ($b - $a) . ') = ` <b>' . $answer[1] . '</b></p>
      <p><span class="term-label">The impossible value.</span> A probability is a number between 0 and 1. The value `' . $impossibleVal . '` cannot be a probability: ';
  if ($impossibleVal > 1) { $solutionguide .= 'it came out bigger than 1, which means the endpoints were subtracted backwards (the base came out as the whole interval or wider).' }
  if ($impossibleVal < 0) { $solutionguide .= 'it came out negative, which means the subtraction ran the wrong way (the right end minus the left end instead of the other way around).' }
  $solutionguide .= '</p>
      <p><span class="term-label">The two sanity checks.</span> Before trusting any probability you compute, check the value is between 0 and 1. Bigger than 1 and negative are the two classic failure shapes, and each points at a specific arithmetic mistake, so the check tells you how to fix it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/(' . ($b - $a) . ')` for `' . $a . ' <= x <= ' . $b . '`, and `0` everywhere else. Three students each computed `P(' . $c . ' < x < ' . $d . ')` and got the values below.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which computed value is impossible &mdash; it cannot be a probability at all?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the correct value of `P(' . $c . ' < x < ' . $d . ')`?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which sanity check catches that mistake?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
