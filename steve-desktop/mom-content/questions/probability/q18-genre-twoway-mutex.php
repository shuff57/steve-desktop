// === NAME - DESCRIPTION: Genre Two-Way Mutex Check - Joint, union, and mutually-exclusive yes/no from a 2x2 movie genre table ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "choices")
$displayformat[2] = "select"
$noshuffle[2] = "all"

// 2x2 table - Movie Genre Pref (Action / Drama) x Watched Last Week (Yes / No). Total = 150 fans.
$total = 150
$a = rand(35, 55)   // Action AND Watched
$b = rand(20, 35)   // Action AND Not Watched
$c = rand(25, 40)   // Drama AND Watched
$d = $total - $a - $b - $c

$action = $a + $b
$drama  = $c + $d
$watched = $a + $c
$notwatched = $b + $d

$answer[0] = $a / $total
$answer[1] = ($action + $watched - $a) / $total
$answer[2] = 1
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005

$pAB_show = round($answer[0], 4)
$pAuB_show = round($answer[1], 4)

$cell_plain = 'border:1px solid #dee1e3; padding:8px 14px; text-align:center;'
$cell_green = 'border:2px solid #10b981; padding:8px 14px; text-align:center; background:#bbf7d0; font-weight:700; color:#065f46;'
$head_style = 'border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px; font-weight:600;'

$tbl_inter = '<table style="border-collapse:collapse; margin:0.5em auto;"><tr><th style="' . $head_style . '">&nbsp;</th><th style="' . $head_style . '">Watched</th><th style="' . $head_style . '">Did Not Watch</th><th style="' . $head_style . '">Total</th></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Prefers Action</th><td style="' . $cell_green . '">' . $a . '</td><td style="' . $cell_plain . '">' . $b . '</td><td style="' . $cell_plain . '">' . $action . '</td></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Prefers Drama</th><td style="' . $cell_plain . '">' . $c . '</td><td style="' . $cell_plain . '">' . $d . '</td><td style="' . $cell_plain . '">' . $drama . '</td></tr>'
$tbl_inter = $tbl_inter . '<tr><th style="' . $head_style . '">Total</th><td style="' . $cell_plain . '">' . $watched . '</td><td style="' . $cell_plain . '">' . $notwatched . '</td><td style="' . $cell_plain . '">' . $total . '</td></tr>'
$tbl_inter = $tbl_inter . '</table>'

$choices[2] = array("Yes &mdash; A and W cannot occur together (A &cap; W = &empty;)", "No &mdash; A and W CAN occur together (A &cap; W &ne; &empty;)")
$labels = array("Yes - mutually exclusive", "No - not mutually exclusive")

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
      <p>Let A = "Prefers Action" and W = "Watched Last Week".</p>
      '.$tbl_inter.'
      <p><b>(a) P(A &cap; W).</b> The Action AND Watched cell holds '.$a.' fans out of '.$total.' total: '.$a.'/'.$total.' = <b>'.$pAB_show.'</b>.</p>
      <p><b>(b) P(A &cup; W).</b> Addition Rule: P(A) + P(W) &minus; P(A &cap; W) = '.$action.'/'.$total.' + '.$watched.'/'.$total.' &minus; '.$a.'/'.$total.' = '.($action+$watched-$a).'/'.$total.' = <b>'.$pAuB_show.'</b>.</p>
      <p><b>(c) Mutually exclusive?</b> Two events are mutually exclusive when they cannot both happen, i.e. A &cap; W = &empty;. Here '.$a.' fans satisfy both, so A &cap; W &ne; &empty;. <b>Not mutually exclusive.</b></p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: '.$pAB_show.', '.$pAuB_show.', No &mdash; not mutually exclusive.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A theater surveyed $total movie fans about their genre preference and whether they watched a movie last week.</p>
    <table style="border-collapse:collapse; margin:1em auto;">
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">&nbsp;</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Watched</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Did Not Watch</th><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Prefers Action</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$a</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$b</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$action</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Prefers Drama</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$c</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$d</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$drama</td></tr>
      <tr><th style="border:1px solid #dee1e3; background:#f7f9fa; padding:8px 14px;">Total</th><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$watched</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$notwatched</td><td style="border:1px solid #dee1e3; padding:8px 14px; text-align:center;">$total</td></tr>
    </table>
    <p style="margin:0.5em 0 0 0;">A fan is selected at random. Let <b>A = "Prefers Action"</b> and <b>W = "Watched Last Week"</b>.</p>
    <p style="margin:0.5em 0 0 0; font-size:14px; color:#555;">Enter probability answers as decimals rounded to 4 places (or exact fractions).</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(A &cap; W)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(A &cup; W)</b>.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Are A and W <b>mutually exclusive</b>?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
