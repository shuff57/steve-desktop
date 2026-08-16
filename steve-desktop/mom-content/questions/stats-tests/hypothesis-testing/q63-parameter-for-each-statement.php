// === NAME - DESCRIPTION: Parameter for Each Statement - mu or p for each of three statements about the same study ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Three statements about the same study. Parts: (a) choices - the parameter for the first
// statement (b) choices - the parameter for the second (c) choices - the parameter for the
// third.
// Invariant: all three answers are constant per scenario and each matches its own statement.

$anstypes = array("choices", "choices", "choices")

$cases = array(
  array("A study of college students reports: (1) the mean number of hours studied per week is 15; (2) the proportion who live on campus is 0.62; (3) the mean GPA is 3.1.",
        "`mu` (population mean)", "`p` (population proportion)", "`mu` (population mean)"),
  array("A study of a city\'s residents reports: (1) the proportion who own a car is 0.78; (2) the mean commute time is 28 minutes; (3) the proportion who work from home is 0.19.",
        "`p` (population proportion)", "`mu` (population mean)", "`p` (population proportion)"),
  array("A study of a factory\'s output reports: (1) the mean part length is 12.5 mm; (2) the proportion of defective parts is 0.03; (3) the mean daily output is 1,200 parts.",
        "`mu` (population mean)", "`p` (population proportion)", "`mu` (population mean)")
)

$i = rand(0, count($cases)-1)
$ctx = $cases[$i][0]
$p1 = $cases[$i][1]
$p2 = $cases[$i][2]
$p3 = $cases[$i][3]

$questions[0] = array($p1, "`bar(x)` (sample mean)", "`sigma` (population standard deviation)")
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array($p2, "`bar(x)` (sample mean)", "`sigma` (population standard deviation)")
$answer[1] = 0
$noshuffle[1] = "all"

$questions[2] = array($p3, "`bar(x)` (sample mean)", "`sigma` (population standard deviation)")
$answer[2] = 0
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
      <p><span class="term-label">The rule.</span> A statement about an average uses a population mean `mu`; a statement about a percentage or a chance uses a population proportion `p`. Decide each one from its own wording, not from the study\'s topic.</p>
      <p><span class="term-label">Statement by statement.</span> ' . $p1 . ' for the first, ' . $p2 . ' for the second, and ' . $p3 . ' for the third.</p>
      <p>The parameter picks the whole row of the hypothesis-testing table, so naming it correctly is the first step of every test.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$ctx</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which parameter is the first statement about?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which parameter is the second statement about?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Which parameter is the third statement about?
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
