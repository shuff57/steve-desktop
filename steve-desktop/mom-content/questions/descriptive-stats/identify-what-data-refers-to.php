// === NAME - DESCRIPTION: Identify What the Key Term Data Refers To - separate the recorded values themselves from the summary of them, the variable being measured, and the group measured ===
// === SET QUESTION TYPE TO: choices ===

// === COMMON CONTROL ===

$n = 40
$ci = rand(0, 2)
$measures = array(
  "the number of days from the start of treatment until symptoms were relieved",
  "the number of days each patient stayed in the hospital",
  "the number of days until each patient returned to work"
)
$shorts = array("survival length in days", "length of stay in days", "days until return to work")
$measure = $measures[$ci]
$short = $shorts[$ci]

$questions = array(
  "the " . $short . " recorded for each patient, 3, 4, 11, 15, and so on, one value per patient",
  "the mean " . $short . " computed across all " . $n . " patients",
  "the " . $n . " patients the physician treated",
  "the question of whether the treatment shortens recovery"
)
$answer = 0

$solutionguide = '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px; margin:1em 0;">
  <p><b>Step 1: recall what <i>data</i> means.</b> Data are the actual values recorded on the individuals in the study: not a summary of them, and not the question being asked.</p>
  <p><b>Step 2: apply it here.</b> The physician recorded, for each of the ' . $n . ' patients, ' . $measure . '. Those ' . $n . ' recorded numbers are the data.</p>
  <p><b>Why each of the others is a different key term:</b></p>
  <ul>
    <li>The <b>mean</b> across the patients is a <i>statistic</i>: a number computed <i>from</i> the data, not the data.</li>
    <li>The ' . $n . ' patients are the <i>sample</i>: who was measured, not what was recorded.</li>
    <li>Whether the treatment shortens recovery is the <i>research question</i>, which the data are collected to answer.</li>
  </ul>
  <p><b>Answer:</b> the data are the ' . $short . ' values, one for each of the ' . $n . ' patients.</p>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <p style="margin:0;">A physician studying a new treatment recorded, for each of <b>$n</b> patients, $measure.</p>
    <p style="margin:12px 0 0 0;">Determine what the key term <b>data</b> refers to in this study.</p>
  </div>
</div>

// === ANSWER ===

$solutionguide
