// === NAME - DESCRIPTION: Lurking Variables in an Observational Study - critique a self-selected comparison for hidden confounders and propose a design fix ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("choices","essay")
$displayformat[1] = 'editornopaste'

// Randomize the supplement/product and the health outcome so each student
// sees a different version of the same self-selection critique.
$products = array("a daily multivitamin","fish oil","vitamin D","probiotics","ginkgo biloba","turmeric","green tea extract")
$pi = rand(0, count($products)-1)
$product = $products[$pi]

$outcomes = array("fewer colds during flu season","lower blood pressure","better cholesterol levels","fewer headaches","higher energy levels","better sleep quality")
$oi = rand(0, count($outcomes)-1)
$outcome = $outcomes[$oi]

$n = rand(6, 24) * 50

// Part a: what is wrong with the causal conclusion.
// Index 0 (lurking variables from self-selection) is correct. The other three
// are the mistakes students actually reach for instead: blaming sample size,
// distrusting self-report as a measurement, and confusing random SELECTION
// (sampling) with random ASSIGNMENT (the thing this comparison actually lacks).
$questions[0] = array(
  "The two groups were not created by the researchers &#8212; adults sorted themselves into &quot;uses $product&quot; and &quot;does not&quot; on their own. Adults who choose to use $product may also differ in other health habits, such as exercise, diet, sleep, or smoking, and one or more of these lurking variables &#8212; not $product itself &#8212; could be responsible for the difference in $outcome.",
  "The sample of $n adults is too small for a difference of this kind to be meaningful.",
  "Because $outcome was based on participants' own reports, it cannot be measured at all, so no conclusion of any kind can be drawn from this data.",
  "The $n adults were not randomly selected from the general population, so the results cannot be generalized to all adults, which is why a cause-and-effect claim is not justified."
)
$answer[0] = 0

$solutionguide = "
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
</style>
<div class='sol-wrap' style='font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;'>
  <details>
    <summary>
      <span class='sol-arrow-closed'>&#9656;</span><span class='sol-arrow-open'>&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class='sol-body'>
      <p><b>Step 1 &#8212; Notice how the groups were formed.</b> No one assigned adults to use $product or not; each adult made that choice individually. That makes this an observational comparison of two self-selected groups, not an experiment.</p>
      <p><b>Step 2 &#8212; Ask what else might differ between the groups.</b> Adults who choose to use $product on their own may also tend to exercise more, eat differently, sleep better, or smoke less than adults who do not. Any of these other health habits is a lurking variable: something that differs between the groups besides the one thing being studied, and that could itself affect $outcome.</p>
      <p><b>Step 3 &#8212; Rule out the other explanations.</b> The sample size, the fact that $outcome was self-reported, and how the adults were originally selected for the survey are all separate issues from the one at hand. None of them is why a causal claim is unjustified here &#8212; the real problem is that a lurking variable, not necessarily $product, could explain the observed difference.</p>
      <p><b>Step 4 &#8212; Fix the design.</b> To rule out lurking variables, the researchers would need to recruit a group of volunteers and randomly assign each one to either use $product or an identical-looking placebo, then compare $outcome between the two groups after a fixed period. Random assignment tends to spread exercise habits, diet, sleep, smoking, and every other lurking variable evenly across both groups, so any remaining difference in $outcome can then be attributed to $product itself.</p>
      <p><b>Answer:</b> (a) One or more lurking variables &#8212; other health habits that differ between the self-selected groups &#8212; may be responsible for the difference in $outcome, not $product itself. (b) Randomly assign volunteers to a treatment group (using $product) and a control or placebo group, then compare $outcome between the groups; random assignment balances lurking variables across the groups so a causal conclusion becomes justified.</p>
    </div>
  </details>
</div>"

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">A researcher surveys $n adults and asks each one whether they take $product regularly. The adults who report taking $product regularly have $outcome, on average, than the adults who do not. The researcher concludes that taking $product causes $outcome.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is wrong with the researcher's conclusion that $product causes $outcome?
    <div style="margin-top:12px;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Describe one specific change to how this study is conducted that would let the researcher draw a cause-and-effect conclusion about $product and $outcome.
    <div style="margin-top:12px;">$answerbox[1]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
