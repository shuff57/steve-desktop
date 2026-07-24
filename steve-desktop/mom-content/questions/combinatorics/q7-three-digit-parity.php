// === NAME - DESCRIPTION: Three-Digit Numbers - Slot-by-slot counting with a parity rule ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("number")

// Three scenarios: any three-digit, three-digit odd, three-digit even.
$kinds = array("three-digit", "three-digit odd", "three-digit even")
$ans   = array(900, 450, 450)
$expls = array("First digit cannot be 0 (9 choices), tens digit is unrestricted (10 choices), units digit is unrestricted (10 choices): `9 \cdot 10 \cdot 10 = 900`.",
               "First digit cannot be 0 (9 choices), tens digit is unrestricted (10 choices), units digit must be in {1,3,5,7,9} (5 choices): `9 \cdot 10 \cdot 5 = 450`.",
               "First digit cannot be 0 (9 choices), tens digit is unrestricted (10 choices), units digit must be in {0,2,4,6,8} (5 choices): `9 \cdot 10 \cdot 5 = 450`.")
$picked = jointrandfrom($kinds, $ans, $expls)
$kind = $picked[0]
$answer[0] = $picked[1]
$expl = $picked[2]

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
      <p><b>Resolve the restricted slots first, then multiply the available choices for each remaining slot.</b></p>
      <p>'.$expl.'</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answer: '.$answer[0].' numbers.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">How many <b>$kind</b> numbers are there? <i>(Digits may repeat. The first digit cannot be 0.)</i></p>
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
