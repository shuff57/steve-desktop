// === NAME - DESCRIPTION: The Complement Rule - P(X > x) = 1 - P(X < x) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Four flat densities; a left area and its complement. Parts:
// (a) numfunc - P(X < x) (the left area), (b) numfunc - P(X > x) = 1 - (a).
// Invariant: (a) + (b) = 1 exactly on every seed.

$anstypes = array("numfunc", "numfunc")

$as = array(0, 2, 5, 1)
$bs = array(20, 10, 15, 9)
$xs = array(8, 5, 9, 3)

$lefts = array(0.4, 0.3, 0.4, 0.25)
$rights = array(0.6, 0.7, 0.6, 0.75)

$i = rand(0, 3)
$a = $as[$i]
$b = $bs[$i]
$x = $xs[$i]
$width = $b - $a

$answer[0] = $lefts[$i]
$answer[1] = $rights[$i]
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
      <p><span class="term-label">Part (a): the left area.</span> The shaded region to the left of `x = ' . $x . '` is a rectangle with base `' . ($x - $a) . '` and height `1/(' . ($b - $a) . ')`:</p>
      <p>`P(X < ' . $x . ') = (' . ($x - $a) . ') * (1/' . ($b - $a) . ') = ` <b>' . $answer[0] . '</b></p>
      <p><span class="term-label">Part (b): the complement.</span> The area to the right is whatever is left over: the two regions together are the entire rectangle, whose area is 1.</p>
      <p>`P(X > ' . $x . ') = 1 - P(X < ' . $x . ') = 1 - ' . $answer[0] . ' = ` <b>' . $answer[1] . '</b></p>
      <p>You never have to measure the right-hand region directly: the complement rule turns "area to the left" into "area to the right" whenever you need it.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider `f(x) = 1/$width` for `$a <= x <= $b`, and `0` everywhere else.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find `P(X < $x)`.
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find `P(X > $x)`.
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
