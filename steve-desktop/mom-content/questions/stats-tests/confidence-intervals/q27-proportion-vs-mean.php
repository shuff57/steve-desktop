// === NAME - DESCRIPTION: Proportion or Mean - which signal says which, and the sample proportion ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// One scenario per context, three questions about the SAME study. Parts: (a) choices - which
// signal says a proportion problem (b) choices - which signal says a mean problem
// (c) numfunc - the sample proportion from the counts.
// Invariant: (a) and (b) are constant, (c) = x/n exactly on every seed.

$anstypes = array("choices", "choices", "numfunc")

$contexts = array(
  array("a poll of 500 adults, 421 of whom own smartphones", 421, 500),
  array("a survey of 600 students, 480 of whom are against the new legislation", 480, 600),
  array("a study measuring the heights of 48 male Swedes, with a sample mean of 71 inches", 0, 1)
)
// [ctx, x, n]: x=0 marks the mean scenario (n=1 so x/n is defined but unused)

$i = rand(0, 2)
$ctx = $contexts[$i][0]
$x = $contexts[$i][1]
$n = $contexts[$i][2]

$questions[0] = array(
  "The data are counts of successes and failures, and there is no mention of a mean or an average anywhere in the question",
  "The data are measurements with a mean",
  "The sample size is large"
)
$answer[0] = 0
$noshuffle[0] = "all"

$questions[1] = array(
  "The data are counts of successes and failures, and there is no mention of a mean or an average anywhere in the question",
  "The data are measurements with a mean",
  "The sample size is large"
)
$answer[1] = 1
$noshuffle[1] = "all"

$answer[2] = ($x > 0) ? $x / $n : 0
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
      <p><span class="term-label">How do you know you are dealing with a proportion problem?</span> Two signals. First, the underlying distribution is a binomial distribution: the data are counts of successes and failures, not measurements. Second, there is no mention of a mean or an average anywhere in the question.</p>
      <p><span class="term-label">Part (a): the proportion signal.</span> ' . ($x > 0 ? "The data are counts of successes and failures, and there is no mention of a mean or an average anywhere in the question." : "The data are measurements with a mean.") . '</p>
      <p><span class="term-label">Part (b): the mean signal.</span> ' . ($x > 0 ? "The data are measurements with a mean." : "The data are counts of successes and failures, and there is no mention of a mean or an average anywhere in the question.") . '</p>
      <p><span class="term-label">Part (c): the sample proportion.</span> ' . ($x > 0 ? "`p\' = x/n = ' . $x . '/' . $n . ' = ' . round($x / $n, 4) . '`" : "This is a mean problem, so there is no sample proportion to compute.") . '</p>
      <p>If X is a binomial random variable, then `X ~ B(n, p)`. To turn that count into a proportion, take X and divide it by n. The random variable `P\'` is that proportion.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">Consider this study: $ctx.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Which signal says this is a PROPORTION problem?
    <span style="margin-left:8px;">$answerbox[0]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Which signal says this is a MEAN problem?
    <span style="margin-left:8px;">$answerbox[1]</span>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px 20px; margin:6px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> ' . ($x > 0 ? "The sample proportion `p\' = x/n`. (Round to 4 decimal places.)" : "This is a mean problem: enter 0 for the sample proportion.") . '
    <span style="margin-left:8px;">$answerbox[2]</span>
  </div>
</div>

// === ANSWER ===

$solutionguide
