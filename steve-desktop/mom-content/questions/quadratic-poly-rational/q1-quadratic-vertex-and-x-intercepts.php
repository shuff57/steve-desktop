// === NAME - DESCRIPTION: Quadratic Vertex and X-Intercepts - Find vertex coordinates and x-intercepts of a quadratic function ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats");

$anstypes = array("number", "number", "ntuple")
$displayformat[2] = "set"
$answerformat[2] = "anyorder"

// Five quadratics with clean integer roots. Each row: a, b, c, vertex_x (as fraction string), vertex_y, root1, root2
// Verified: discriminant = b^2 - 4ac is a perfect square; roots = (-b +/- sqrt(disc)) / (2a)
// (1)  x^2 - 5x + 4:    a=1,b=-5,c=4   vx=5/2, vy=-9/4  roots 1,4  -- non-integer vx, skip
// Use only cases where vertex_x = -b/(2a) is a clean half-integer or integer and vy is clean.
// (A) x^2 - 6x + 8:    a=1,b=-6,c=8   vx=3,   vy=-1    roots 2,4
// (B) x^2 - 4x - 5:    a=1,b=-4,c=-5  vx=2,   vy=-9    roots -1,5
// (C) x^2 - 2x - 8:    a=1,b=-2,c=-8  vx=1,   vy=-9    roots -2,4
// (D) x^2 + 2x - 8:    a=1,b=2, c=-8  vx=-1,  vy=-9    roots -4,2
// (E) x^2 + 4x + 3:    a=1,b=4, c=3   vx=-2,  vy=-1    roots -3,-1

$as     = array(1,    1,    1,    1,    1)
$bs     = array(-6,   -4,   -2,    2,    4)
$cs     = array(8,    -5,   -8,   -8,    3)
$vxs    = array(3,     2,    1,   -1,   -2)
$vys    = array(-1,   -9,   -9,   -9,   -1)
$r1s    = array(2,    -1,   -2,   -4,   -3)
$r2s    = array(4,     5,    4,    2,   -1)
$eqs    = array("f(x) = x^2 - 6x + 8", "f(x) = x^2 - 4x - 5", "f(x) = x^2 - 2x - 8", "f(x) = x^2 + 2x - 8", "f(x) = x^2 + 4x + 3")

$picked = jointrandfrom($as, $bs, $cs, $vxs, $vys, $r1s, $r2s, $eqs)
$a_val  = $picked[0]
$b_val  = $picked[1]
$c_val  = $picked[2]
$vx     = $picked[3]
$vy     = $picked[4]
$r1     = $picked[5]
$r2     = $picked[6]
$eq     = $picked[7]

$answer[0] = $vx
$answer[1] = $vy
$answer[2] = "{" . $r1 . "," . $r2 . "}"

// Build b display (handle negative sign cleanly)
$b_abs = abs($b_val)
$b_sign = "+"
if ($b_val < 0) { $b_sign = "-" }
$c_abs = abs($c_val)
$c_sign = "+"
if ($c_val < 0) { $c_sign = "-" }

// Discriminant for solution
$disc = $b_val * $b_val - 4 * $a_val * $c_val
$sqrtdisc = round($disc^0.5, 0)

$vx_num = -1 * $b_val
$vx_den = 2 * $a_val

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
      <p><b>Given:</b> `'.$eq.'` where `a = '.$a_val.'`, `b = '.$b_val.'`, `c = '.$c_val.'`.</p>
      <p><b>(a) x-coordinate of vertex:</b> Use `x = -b/(2a)`.</p>
      <p style="margin-left:1em;">`x = -('.$b_val.')/(2 * '.$a_val.') = '.$vx_num.'/'.$vx_den.' = '.$vx.'`</p>
      <p><b>(b) y-coordinate of vertex:</b> Substitute `x = '.$vx.'` into `f(x)`.</p>
      <p style="margin-left:1em;">`f('.$vx.') = ('.$vx.')^2 ' . $b_sign . ' '.$b_abs.'('.$vx.') ' . $c_sign . ' '.$c_abs.' = '.$vy.'`</p>
      <p><b>(c) x-intercepts:</b> Set `f(x) = 0` and use the quadratic formula.</p>
      <p style="margin-left:1em;">`discriminant = ('.$b_val.')^2 - 4('.$a_val.')('.$c_val.') = '.$disc.'`</p>
      <p style="margin-left:1em;">`sqrt('.$disc.') = '.$sqrtdisc.'`</p>
      <p style="margin-left:1em;">`x = (-('.$b_val.') +/- '.$sqrtdisc.')/(2) = '.$r1.' or '.$r2.'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Vertex: <b>('.$vx.', '.$vy.')</b> &nbsp;&bull;&nbsp; x-intercepts: <b>x = '.$r1.' and x = '.$r2.'</b>
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">Consider the quadratic function:</p>
    <p style="margin:0 0 0 0; text-align:center;">`$eq`</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>x-coordinate</b> of the vertex? Use the formula `x = -b/(2a)`. <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What is the <b>y-coordinate</b> of the vertex? Substitute your answer from (a) into `f(x)`. <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find the two <b>x-intercepts</b> of `f(x)`. Set `f(x) = 0` and solve. Enter as a set, e.g. <code>{-2, 5}</code>. <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>


// === ANSWER ===

$solutionguide
