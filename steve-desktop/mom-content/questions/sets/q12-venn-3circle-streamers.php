// === NAME - DESCRIPTION: 3-Circle Venn - Streaming services, count regions ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number", "number", "number")

// Randomize the 7 disjoint regions of the 3-circle Venn diagram directly,
// then reconstruct the counts students see (|N|, |D|, |H|, |N n D|, |N n H|, |D n H|, |all|).
// "Exactly one" = n_only + d_only + h_only
// "At least two" = nd_only + nh_only + dh_only + all3
// "None"        = total - (exactly_one + at_least_two)
$n_only   = rand(20, 35)
$d_only   = rand(15, 30)
$h_only   = rand(10, 25)
$nd_only  = rand(12, 22)
$nh_only  = rand(6, 14)
$dh_only  = rand(3, 10)
$all3     = rand(5, 15)
$none     = rand(5, 15)

$N  = $n_only + $nd_only + $nh_only + $all3
$D  = $d_only + $nd_only + $dh_only + $all3
$H  = $h_only + $nh_only + $dh_only + $all3
$ND = $nd_only + $all3
$NH = $nh_only + $all3
$DH = $dh_only + $all3
$total = $n_only + $d_only + $h_only + $nd_only + $nh_only + $dh_only + $all3 + $none

$exactly_one  = $n_only + $d_only + $h_only
$at_least_two = $nd_only + $nh_only + $dh_only + $all3

$answer[0] = $exactly_one
$answer[1] = $at_least_two
$answer[2] = $none

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .sol-wrap table { border-collapse:collapse; margin:0.5em 0; }
  .sol-wrap th, .sol-wrap td { border:1px solid #dee1e3; padding:6px 10px; text-align:center; }
  .sol-wrap th { background:#f7f9fa; font-weight:600; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p>Fill the three-circle diagram from the center outward:</p>
      <table>
        <tr><th>Region</th><th>Count</th><th>Work</th></tr>
        <tr><td>All three (N &cap; D &cap; H)</td><td><b>'.$all3.'</b></td><td>given</td></tr>
        <tr><td>Exactly N and D</td><td><b>'.$nd_only.'</b></td><td>'.$ND.' &minus; '.$all3.'</td></tr>
        <tr><td>Exactly N and H</td><td><b>'.$nh_only.'</b></td><td>'.$NH.' &minus; '.$all3.'</td></tr>
        <tr><td>Exactly D and H</td><td><b>'.$dh_only.'</b></td><td>'.$DH.' &minus; '.$all3.'</td></tr>
        <tr><td>N only</td><td><b>'.$n_only.'</b></td><td>'.$N.' &minus; '.$nd_only.' &minus; '.$nh_only.' &minus; '.$all3.'</td></tr>
        <tr><td>D only</td><td><b>'.$d_only.'</b></td><td>'.$D.' &minus; '.$nd_only.' &minus; '.$dh_only.' &minus; '.$all3.'</td></tr>
        <tr><td>H only</td><td><b>'.$h_only.'</b></td><td>'.$H.' &minus; '.$nh_only.' &minus; '.$dh_only.' &minus; '.$all3.'</td></tr>
      </table>
      <p><b>(a)</b> Exactly one service: '.$n_only.' + '.$d_only.' + '.$h_only.' = <b>'.$exactly_one.'</b>.</p>
      <p><b>(b)</b> At least two: '.$nd_only.' + '.$nh_only.' + '.$dh_only.' + '.$all3.' = <b>'.$at_least_two.'</b>.</p>
      <p><b>(c)</b> None: '.$total.' &minus; ('.$exactly_one.' + '.$at_least_two.') = <b>'.$none.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Exactly one: '.$exactly_one.' &nbsp;&bull;&nbsp; At least two: '.$at_least_two.' &nbsp;&bull;&nbsp; None: '.$none.'
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A survey of <b>$total</b> streamers found:</p>
    <ul style="margin:0.5em 0 0 1.2em;">
      <li>$N use Netflix, $D use Disney+, $H use Hulu</li>
      <li>$ND use Netflix and Disney+, $NH use Netflix and Hulu, $DH use Disney+ and Hulu</li>
      <li>$all3 use all three</li>
    </ul>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> How many use <b>exactly one</b> service?
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> How many use <b>at least two</b> services?
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> How many use <b>none</b> of the three?
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
