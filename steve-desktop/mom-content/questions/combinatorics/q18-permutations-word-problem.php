// === NAME - DESCRIPTION: Permutations Word Problem - Count ordered arrangements in a real-world podium scenario ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

loadlibrary("stats")

$anstypes = array("numfunc")

/* ---------- 1. Randomized scenarios ---------- */
// Each row: [n, r, nPr_answer, context_phrase, slot_label]
// nPr verified by hand: n * (n-1) * ... down to (n-r+1) factors

$ns    = array(6,  7,  8,  9, 10,  6,  7,  8)
$rs    = array(3,  3,  3,  3,  3,  4,  4,  4)
$nprs  = array(120, 210, 336, 504, 720, 360, 840, 1680)

$ctx_phrases = array(
  "In a local 5K race with 6 finishers, how many different ways can the gold, silver, and bronze medals be awarded?",
  "A swim meet has 7 competitors. How many different first, second, and third place finishes are possible?",
  "Eight runners compete in a 100-meter dash. In how many ways can the top three finishing positions (1st, 2nd, 3rd) be filled?",
  "A regional science fair has 9 student projects. In how many ways can the judges award 1st, 2nd, and 3rd place ribbons?",
  "Ten musicians auditioned for a showcase. How many ways can the director assign the three featured performance slots (opening, middle, closing)?",
  "A cooking competition has 6 chefs. How many different ways can the show assign distinct placements for the top four ranked finishers?",
  "In a talent show with 7 acts, the director must choose and rank the top four acts for the awards ceremony. How many ordered rankings are possible?",
  "Eight candidates applied for four distinct officer roles in a student club (president, vice president, secretary, treasurer). How many ways can the four roles be filled?"
)

$step_phrases = array(
  "6 &times; 5 &times; 4",
  "7 &times; 6 &times; 5",
  "8 &times; 7 &times; 6",
  "9 &times; 8 &times; 7",
  "10 &times; 9 &times; 8",
  "6 &times; 5 &times; 4 &times; 3",
  "7 &times; 6 &times; 5 &times; 4",
  "8 &times; 7 &times; 6 &times; 5"
)

$picked = jointrandfrom($ns, $rs, $nprs, $ctx_phrases, $step_phrases)
$nval  = $picked[0]
$rval  = $picked[1]
$answer[0] = $picked[2]
$ctx   = $picked[3]
$steps = $picked[4]

/* ---------- 2. Solution Guide ---------- */
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
      <p>Order matters here (different placements give different outcomes), so use the permutation formula:</p>
      <p style="text-align:center;">`P(n,r) = (n!)/((n-r)!) = n(n-1)(n-2)cdots` with `r` factors</p>
      <p>With n = '.$nval.' and r = '.$rval.':</p>
      <p style="text-align:center;">`P('.$nval.','.$rval.') = '.$steps.' = '.$answer[0].'`</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        There are <b>'.$answer[0].'</b> different ordered arrangements.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$ctx</p>
    <p style="margin:0;">Enter your answer below.</p>
    <div style="margin-top:12px; text-align:center;">$answerbox[0]</div>
  </div>
</div>

// === ANSWER ===

$solutionguide
