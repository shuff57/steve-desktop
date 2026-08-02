// === NAME - DESCRIPTION: Critiquing a Self-Funded Volunteer Study - Check every problem in a company-funded study of self-selected volunteers with no control group, and recognise that a large sample size fixes sampling error but never sampling bias ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

// Randomize the spokesperson, the company and the product, so the wording differs student to student.
$replist = array("Hector Ramos", "Dana Whitfield", "Priya Sundar", "Marcus Bell", "Elena Costa", "Tomas Lindqvist", "Grace Okonkwo", "Ryan Delacroix")
$rep = $replist[rand(0, count($replist)-1)]

$brandlist = array("Lustre Labs", "BrightMile", "Kova Health", "Nordvale", "Aurelia Skin", "Peak Forge")
$productlist = array("shampoo", "whitening toothpaste", "joint supplement", "sleep spray", "skin cream", "protein powder")
$ci = rand(0, count($brandlist)-1)
$brand = $brandlist[$ci]
$product = $productlist[$ci]

// A randomized, deliberately LARGE sample and a randomized, deliberately HIGH success rate.
$thou = rand(2, 6)
$nstr = $thou . ",000"
$pct = rand(84, 96)
$pctstr = $pct . "%"

// The five genuine problems.
$t1 = "The study was paid for by the company that sells the product, so it is a self-funded, self-interest study."
$t2 = "The participants were volunteers, so the sample is self-selected."
$t3 = "The volunteers came from the company&rsquo;s own mailing list &mdash; people already interested in the product &mdash; so the sample is a convenience sample rather than a cross-section of users."
$t4 = "There is no comparison group, so any improvement is confounded with other possible causes."
$t5 = "Improvement was self-reported by the participants rather than measured independently."

// The three statements that are NOT problems with this study.
$f1 = "The sample size is too small for the results to mean anything."
$f2 = "The large sample size corrects for the way the participants were recruited."
$f3 = "Because the company paid for the study, its results can be dismissed without reading it."

// Correct options first so the answer key is fixed; MOM shuffles the display order for the student.
$questions = array($t1, $t2, $t3, $t4, $t5, $f1, $f2, $f3)
$answers = "0,1,2,3,4"

$truelist = '<div class="term-row">&bull; ' . $t1 . '</div><div class="term-row">&bull; ' . $t2 . '</div><div class="term-row">&bull; ' . $t3 . '</div><div class="term-row">&bull; ' . $t4 . '</div><div class="term-row">&bull; ' . $t5 . '</div>'

$solutionguide = '
<style>
  .sol-wrap details { width:100%; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff; }
  .sol-wrap summary { cursor:pointer; display:block; width:100%; background:#f0f4ff; color:#21242c; padding:0.5em 0.75em; font-weight:700; font-size:15px; border-bottom:1px solid #e5e7eb; list-style:none; }
  .sol-wrap summary::-webkit-details-marker { display:none; }
  .sol-arrow-open { display:none; }
  .sol-wrap details[open] .sol-arrow-closed { display:none; }
  .sol-wrap details[open] .sol-arrow-open { display:inline; }
  .sol-body { padding:0.75em; background:#fafafa; }
  .term-row { margin:0.6em 0; }
  .term-label { font-weight:700; color:#1865f2; }
</style>
<div class="sol-wrap" style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <details>
    <summary>
      <span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span>
      Step-by-Step Solution
    </summary>
    <div class="sol-body">
      <p><b>Who paid for it.</b> ' . $brand . ' funded a study about its own ' . $product . ', so this is a <b>self-funded / self-interest study</b>. That does not automatically make it wrong &mdash; it means the design and the reporting deserve extra scrutiny before the number is believed.</p>
      <p><b>Who is in the sample.</b> The ' . $nstr . ' participants <i>volunteered</i>, so the sample is <b>self-selected</b>; and they were drawn from the mailing list of people already interested in ' . $brand . ', so it is also a <b>convenience sample</b>. Neither group is a cross-section of ' . $product . ' users.</p>
      <p><b>What ' . $pctstr . ' actually measures.</b> Improvement was a <b>self-reported</b> judgment, and nobody was tracked for comparison. With <b>no control group</b>, the improvement is <b>confounded</b> with everything else that changed over the same weeks &mdash; the season, other products, and plain expectation.</p>
      <div class="term-row"><span class="term-label">Problems this study has (check these):</span></div>
      ' . $truelist . '
      <p style="margin-top:1em;"><b>Why the other statements do not belong:</b></p>
      <div class="term-row">&bull; ' . $f1 . ' &mdash; ' . $nstr . ' is a <i>large</i> sample. Size is the one thing this study is not short of.</div>
      <div class="term-row">&bull; ' . $f2 . ' &mdash; this is the trap. <b>Sample size fixes sampling error, not sampling bias.</b> A biased recruiting method run on ' . $nstr . ' people gives a more precise estimate of the wrong quantity: a bigger self-selected sample is a worse one, not a better one.</div>
      <div class="term-row">&bull; ' . $f3 . ' &mdash; too far the other way. A self-funded study is read with extra care and judged on its merits, not thrown out on sight.</div>
      <p style="margin-top:1em;"><b>The one idea to carry forward:</b> a large `n` shrinks random error only. Nothing about how many people answered can repair <i>who</i> was asked.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;"><b>$rep</b>, a spokesperson for <b>$brand</b>, presents a study the company funded itself.</p>
    <p style="margin:12px 0 0 0;">$nstr volunteers were recruited from the company's own mailing list and used the $brand $product for eight weeks. Afterwards, $pctstr of them said their condition had improved. No other group of people was followed over those eight weeks.</p>
    <p style="margin:12px 0 0 0;"><b>Select every problem this study has.</b> More than one statement is a real problem, and a statement counts only if it is genuinely a problem with <i>this</i> study.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
