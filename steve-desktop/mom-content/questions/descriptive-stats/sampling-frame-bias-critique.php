// === NAME - DESCRIPTION: Sampling Frame and Selection Bias in a Retailer Study - Check every genuine sampling-frame or selection-bias flaw in a textbook-availability study, and reject false statements that sound plausible but are not actual problems ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

// Randomize the reporter, so the wording differs student to student.
$replist = array("Avery Whitlock", "Jordan Reyes", "Kiran Osei", "Devon Marsh", "Sam Falcone", "Riley Tanaka", "Morgan Aldous", "Casey Nwosu")
$reporter = $replist[rand(0, count($replist)-1)]

// Randomize how many subjects/textbooks are investigated, and which ones.
$subjectpool = array("calculus", "biology", "chemistry", "physics", "statistics", "geology", "general engineering", "computer science", "economics", "psychology")
$nsub = rand(5, 7)
$subjects = diffrandsfrom($subjectpool, $nsub)
$subjectstr = $subjects[0] . ", " . $subjects[1] . ", " . $subjects[2] . ", " . $subjects[3] . ", and " . $subjects[4] if ($nsub==5)
$subjectstr = $subjects[0] . ", " . $subjects[1] . ", " . $subjects[2] . ", " . $subjects[3] . ", " . $subjects[4] . ", and " . $subjects[5] if ($nsub==6)
$subjectstr = $subjects[0] . ", " . $subjects[1] . ", " . $subjects[2] . ", " . $subjects[3] . ", " . $subjects[4] . ", " . $subjects[5] . ", and " . $subjects[6] if ($nsub==7)

// Randomize the number of retailers sampled.
$nretail = rand(8, 15)

// Randomize when stock was checked, to create a timing mismatch with "at the start of the term".
$timelist = array("three weeks after the semester began", "midway through the term", "during final exam week", "a month after classes started")
$timing = $timelist[rand(0, count($timelist)-1)]

// The four genuine problems.
$t1 = "$reporter selected the best-selling, most widely adopted textbook in each subject &mdash; exactly the titles a retailer is most likely to keep in stock &mdash; so the study is biased toward the easiest cases and will overstate how available college textbooks are in general."
$t2 = "The $nsub subjects investigated ($subjectstr) are a narrow, mostly STEM-heavy slice of a college catalog. Humanities, arts, nursing, and many other programs are not represented at all, so the sample is not representative of college textbooks as a whole."
$t3 = "$nsub textbooks is far too small a sample to support a conclusion about the tens of thousands of titles used across all college courses, no matter how carefully those $nsub titles were chosen."
$t4 = "$reporter checked stock $timing, but the concern raised in the article is whether students can get their books at the very start of the term &mdash; checking availability at a different point in the term does not answer that question."

// The three statements that are NOT problems with this study.
$f1 = "The study is invalid because the sample of $nretail retailers was chosen at random."
$f2 = "Because each textbook was the best-selling title in its subject, the study is guaranteed to give an unbiased estimate of overall textbook availability."
$f3 = "Sampling a larger number of retailers would fix the bias created by only sampling best-selling textbooks."

// Correct options first so the answer key is fixed; MOM shuffles the display order for the student.
$questions = array($t1, $t2, $t3, $t4, $f1, $f2, $f3)
$answers = "0,1,2,3"

$truelist = '<div class="term-row">&bull; ' . $t1 . '</div><div class="term-row">&bull; ' . $t2 . '</div><div class="term-row">&bull; ' . $t3 . '</div><div class="term-row">&bull; ' . $t4 . '</div>'

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
      <p><b>Step 1 &mdash; Is the sample of textbooks representative?</b> No. The claim is about <i>all college textbooks</i>, but the ' . $nsub . ' titles were chosen to be the best-selling, most widely adopted book in each subject. Popular books in high-enrollment courses are exactly the books a retailer is most likely to stock, so the sampling frame &mdash; the list a title could even be drawn from &mdash; already excludes every hard-to-stock case.</p>
      <p><b>Step 2 &mdash; Name the sources of bias.</b></p>
      <div class="term-row"><span class="term-label">Problems this study has (check these):</span></div>
      ' . $truelist . '
      <p><b>Step 3 &mdash; How the bias moves the result.</b> Every one of these pushes the same direction: toward overstating how available college textbooks are online. The ' . $nretail . '-retailer sample being random does not rescue this &mdash; it fixes how the retailers were chosen, not how the ' . $nsub . ' textbooks were chosen.</p>
      <p style="margin-top:1em;"><b>Why the other statements do not belong:</b></p>
      <div class="term-row">&bull; ' . $f1 . ' &mdash; this is the trap. A random sample of retailers is the one part of this design that <i>is</i> sound. The flaw is entirely in which textbooks were selected, not in which retailers were asked.</div>
      <div class="term-row">&bull; ' . $f2 . ' &mdash; backwards. Choosing only bestsellers is exactly what biases the estimate toward the optimistic side; it guarantees the opposite of an unbiased result.</div>
      <div class="term-row">&bull; ' . $f3 . ' &mdash; a bigger retailer sample shrinks random sampling error in the retailer count, but it cannot touch a bias baked into which ' . $nsub . ' textbooks were chosen before a single retailer was ever visited.</div>
      <p><b>Step 4 &mdash; Suggest improvements.</b> Build a sampling frame from the textbooks actually assigned at a set of colleges that term &mdash; a set of campus bookstore adoption lists is exactly this &mdash; and take a random sample from that frame instead of the bestseller list. Cover every division of the catalog, not just ' . $nsub . ' subjects. Use far more titles, and check availability at the start of the term, when it actually matters to students.</p>
      <p style="margin-top:1em;"><b>Answer:</b> the sample of textbooks is not representative &mdash; bestsellers in ' . $nsub . ' large subjects are the titles most likely to be in stock, the subject coverage is narrow, ' . $nsub . ' titles is too small a base for a catalog-wide claim, and the timing of the check does not match when students actually need their books. The ' . $nretail . '-retailer sample being random is the one part of the design that is fine.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0;">$reporter, a college newspaper reporter, is investigating textbook availability at online retailers.</p>
    <p style="margin:12px 0 0 0;">They pick one textbook for each of $nsub subjects ($subjectstr), choosing the best-selling, most widely adopted title in each subject. $reporter then visits a random sample of $nretail major online textbook retailers and looks up each of these $nsub textbooks to see whether it is in stock for quick delivery. $reporter checked this stock $timing. Based on this investigation, $reporter writes an article drawing conclusions about the overall availability of all college textbooks through online retailers.</p>
    <p style="margin:12px 0 0 0;"><b>Select every genuine flaw in this study.</b> More than one statement is a real problem, and a statement counts only if it is genuinely a problem with <i>this</i> study.</p>
  </div>
  $answerbox
</div>

// === ANSWER ===

$solutionguide
