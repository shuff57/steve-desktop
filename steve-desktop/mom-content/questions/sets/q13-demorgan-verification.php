// === NAME - DESCRIPTION: De Morgan via Survey - Find how many take neither subject by computing the union, then the complement, then identifying it as A^c n B^c ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "choices")
$displayformat[2] = "select"
$noshuffle[2] = "all"

// Survey scenarios. Goal: find # who take NEITHER subject. Steps:
//   (a) |M u E| via Addition Rule
//   (b) |neither| = |U| - (a)
//   (c) Choose the set notation for "neither": M^c n E^c (De Morgan)
$totals    = array(60,  100, 80)
$ms        = array(28,  60,  35)
$es        = array(22,  40,  30)
$boths     = array(12,  25,  10)
$labels_M  = array("Math",     "Math",      "Spanish")
$labels_E  = array("English",  "English",   "French")
$unions    = array(38,  75,  55)
$neithers  = array(22,  25,  25)
$picked = jointrandfrom($totals, $ms, $es, $boths, $labels_M, $labels_E, $unions, $neithers)
$N = $picked[0]
$M = $picked[1]
$E = $picked[2]
$both = $picked[3]
$labelM = $picked[4]
$labelE = $picked[5]
$answer[0] = $picked[6]
$answer[1] = $picked[7]
$answer[2] = 1

$choices[2] = array(
  "M<sup>c</sup> &cup; E<sup>c</sup>",
  "M<sup>c</sup> &cap; E<sup>c</sup>",
  "M &cup; E",
  "M &cap; E"
)

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Find how many of the '.$N.' surveyed take NEITHER '.$labelM.' nor '.$labelE.'.</p>
      <p><b>(a) Number taking at least one (Addition Rule).</b> '.$M.' + '.$E.' &minus; '.$both.' = <b>'.$answer[0].'</b>.</p>
      <p><b>(b) Number taking neither.</b> Subtract from the total: '.$N.' &minus; '.$answer[0].' = <b>'.$answer[1].'</b>.</p>
      <p><b>(c) Set notation for &ldquo;neither&rdquo;.</b> &ldquo;Not in M AND not in E&rdquo; is M<sup>c</sup> &cap; E<sup>c</sup>. By De Morgan&rsquo;s Law this equals (M &cup; E)<sup>c</sup>, which is exactly what we used in step (b).</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: '.$answer[0].', '.$answer[1].', M<sup>c</sup> &cap; E<sup>c</sup>.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey of <b>$N students</b> found <b>$M take $labelM</b>, <b>$E take $labelE</b>, and <b>$both take both</b>. Let M = "takes $labelM" and E = "takes $labelE".</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find the number of students who take <b>at least one</b> of $labelM or $labelE.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find the number of students who take <b>neither</b> $labelM nor $labelE.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which set-notation expression describes the &ldquo;neither&rdquo; group?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
