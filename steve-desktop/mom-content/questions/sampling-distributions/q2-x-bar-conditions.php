// === NAME - DESCRIPTION: Sampling distribution of x-bar - check normality of population OR n>=30 and decide whether the sampling distribution is approximately normal ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","choices","choices")
$displayformat = "select"
$answerboxsize = 14

// Each scenario sets context, the stated population shape, and sample size n.
// Conditions: sampling distribution of x-bar is approximately normal if
//   (population is approximately normal) OR (n >= 30).

// shape codes: 0 = "approximately normal", 1 = "right-skewed", 2 = "not stated / unknown"
// Scenario 0: SAT scores, shape=0 (normal), n=20 -> pop normal pass -> APPROX NORMAL
// Scenario 1: salaries, shape=1 (right-skewed), n=10 -> both fail -> NOT NORMAL
// Scenario 2: adult heights, shape=0 (normal), n=15 -> pop normal pass -> APPROX NORMAL
// Scenario 3: commute times, shape=1 (right-skewed), n=50 -> n>=30 pass -> APPROX NORMAL
// Scenario 4: home prices, shape=1 (right-skewed), n=25 -> both fail -> NOT NORMAL
// Scenario 5: weekly grocery spending, shape=2 (unknown), n=40 -> n>=30 pass -> APPROX NORMAL
// Scenario 6: time to graduate, shape=2 (unknown), n=18 -> both fail -> NOT NORMAL

$ctxs = array(
  "SAT math scores in a large population are <b>approximately normally distributed</b>. A counselor takes a random sample of <b>`n = 20`</b> students and records the sample mean `bar(x)`.",
  "Annual salaries in a large company are <b>strongly right-skewed</b>. An HR analyst takes a random sample of <b>`n = 10`</b> employees and records the sample mean `bar(x)`.",
  "Adult heights in a large population are <b>approximately normally distributed</b>. A researcher takes a random sample of <b>`n = 15`</b> adults and records the sample mean `bar(x)`.",
  "Daily commute times in a metro area are <b>right-skewed</b> with a long upper tail. A transportation study takes a random sample of <b>`n = 50`</b> commuters and records the sample mean `bar(x)`.",
  "Home sale prices in a city are <b>right-skewed</b>. A realtor takes a random sample of <b>`n = 25`</b> recent sales and records the sample mean `bar(x)`.",
  "Weekly grocery spending in a region has an <b>unknown distribution</b>. A market researcher takes a random sample of <b>`n = 40`</b> households and records the sample mean `bar(x)`.",
  "Time-to-graduate (years) for transfer students has an <b>unknown distribution</b>. An advisor takes a random sample of <b>`n = 18`</b> students and records the sample mean `bar(x)`."
)

$ns       = array(20, 10, 15, 50, 25, 40, 18)
// 0 = Yes (stated approximately normal), 1 = No (skewed or unknown)
$ansShape = array(0,  1,  0,  1,  1,  1,  1)
// 0 = Yes (n>=30), 1 = No (n<30)
$ansN     = array(1,  1,  1,  0,  1,  0,  1)
// 0 = Yes (approx normal), 1 = No (NOT approx normal)
$ansFinal = array(0,  1,  0,  0,  1,  0,  1)

$picked = jointrandfrom($ctxs, $ns, $ansShape, $ansN, $ansFinal)
$ctx   = $picked[0]
$n     = $picked[1]

$questions[0] = array("Yes, the population is approximately normal", "No, the population is skewed or unknown")
$answer[0] = $picked[2]

$questions[1] = array("Yes, the sample size satisfies `n >= 30`", "No, the sample size is less than 30")
$answer[1] = $picked[3]

$questions[2] = array("Yes, the sampling distribution of `bar(x)` is approximately normal", "No, the sampling distribution of `bar(x)` is NOT approximately normal")
$answer[2] = $picked[4]

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
      <p><b>Rule:</b> The sampling distribution of `bar(x)` is approximately normal when EITHER of the following holds:</p>
      <ul>
        <li>The population itself is approximately normal (any `n` works), OR</li>
        <li>The sample size is large: `n >= 30` (Central Limit Theorem).</li>
      </ul>
      <p>If <i>both</i> fail (skewed or unknown population AND `n < 30`), then we cannot conclude the sampling distribution is approximately normal.</p>
      <p><b>(a)</b> Look at the population shape in the prompt. Is the population stated to be approximately normal?</p>
      <p><b>(b)</b> Compare `n = ' . $n . '` to 30.</p>
      <p><b>(c)</b> If at least one of (a) or (b) is "Yes", the sampling distribution of `bar(x)` is approximately normal. If both are "No", it is not.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        <b>Sanity check:</b> with a normal population, even `n = 5` gives a normal sampling distribution. With a skewed or unknown population, you need the CLT, which requires `n >= 30`.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 8px 0;">$ctx</p>
    <p style="margin:0;">Decide whether the sampling distribution of the sample mean `bar(x)` is approximately normal.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Is the <b>population</b> stated to be approximately normal?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Does the sample size satisfy `n >= 30`?
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Based on parts (a) and (b), is the sampling distribution of `bar(x)` approximately normal?
    <div style="margin-top:12px;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
