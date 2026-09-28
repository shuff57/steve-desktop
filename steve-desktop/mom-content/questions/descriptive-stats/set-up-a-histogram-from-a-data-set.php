// === NAME - DESCRIPTION: Set Up a Histogram from a Data Set - From twenty unsorted whole-number measurements work out the range, choose a class width, place a stated value in its class, count that class, name the next class boundary, and say why histogram bars touch ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Every number below is a literal, and all four cases were checked outside PHP: the class
// frequencies sum to 20, the stated range equals max minus min of the printed list, and
// ceil(range/5) is the stated width in every case. That matters because this dialect has no
// nested loops, no array_slice and no computed array writes, so anything derived would have
// been untestable. The ranges are deliberately not multiples of 5, which is what keeps
// ceil(range/5) equal to the width that five integer-limit classes actually need.
$anstypes = array("number", "number", "number", "number", "number", "choices");

$ci = rand(0, 3);
if ($ci == 0) {
  $intro = "A podcast producer recorded the running time of each episode in a season.";
  $unitWord = "minutes";
  $dataList = "25, 24, 20, 26, 16, 16, 29, 14, 23, 10, 18, 15, 24, 17, 22, 13, 12, 21, 15, 19";
  $dmin = 10;
  $dmax = 29;
  $range = 19;
  $rawWidth = "3.8";
  $width = 4;
  $c1lo = 10;
  $c1hi = 13;
  $c5lo = 26;
  $c5hi = 29;
  $checkVal = 14;
  $checkClass = 2;
  $checkFreq = 6;
  $nextLo = 18;
  $freqLine = "3, 6, 4, 5, 2";
  $classLine = "10-13, 14-17, 18-21, 22-25, 26-29";
}
elseif ($ci == 1) {
  $intro = "A teacher recorded the number of pages each student read over the weekend.";
  $unitWord = "pages";
  $dataList = "107, 128, 110, 100, 119, 121, 106, 102, 130, 116, 118, 114, 115, 120, 104, 113, 132, 134, 131, 127";
  $dmin = 100;
  $dmax = 134;
  $range = 34;
  $rawWidth = "6.8";
  $width = 7;
  $c1lo = 100;
  $c1hi = 106;
  $c5lo = 128;
  $c5hi = 134;
  $checkVal = 114;
  $checkClass = 3;
  $checkFreq = 6;
  $nextLo = 121;
  $freqLine = "4, 3, 6, 2, 5";
  $classLine = "100-106, 107-113, 114-120, 121-127, 128-134";
}
elseif ($ci == 2) {
  $intro = "A transit planner recorded the door-to-door commute time for 20 riders.";
  $unitWord = "minutes";
  $dataList = "39, 23, 34, 35, 15, 38, 36, 33, 19, 31, 20, 30, 29, 21, 32, 22, 24, 22, 27, 25";
  $dmin = 15;
  $dmax = 39;
  $range = 24;
  $rawWidth = "4.8";
  $width = 5;
  $c1lo = 15;
  $c1hi = 19;
  $c5lo = 35;
  $c5hi = 39;
  $checkVal = 20;
  $checkClass = 2;
  $checkFreq = 6;
  $nextLo = 25;
  $freqLine = "2, 6, 3, 5, 4";
  $classLine = "15-19, 20-24, 25-29, 30-34, 35-39";
}
else {
  $intro = "A school nurse recorded the number of pencils found in 20 students' pockets.";
  $unitWord = "pencils";
  $dataList = "6, 6, 13, 12, 5, 4, 9, 2, 2, 4, 7, 15, 16, 11, 3, 15, 10, 8, 12, 14";
  $dmin = 2;
  $dmax = 16;
  $range = 14;
  $rawWidth = "2.8";
  $width = 3;
  $c1lo = 2;
  $c1hi = 4;
  $c5lo = 14;
  $c5hi = 16;
  $checkVal = 5;
  $checkClass = 2;
  $checkFreq = 4;
  $nextLo = 8;
  $freqLine = "5, 4, 3, 4, 4";
  $classLine = "2-4, 5-7, 8-10, 11-13, 14-16";
}

$answer[0] = $range;
$answerformat[0] = "integer";
$answer[1] = $width;
$answerformat[1] = "integer";
$answer[2] = $checkClass;
$answerformat[2] = "integer";
$answer[3] = $checkFreq;
$answerformat[3] = "integer";
$answer[4] = $nextLo;
$answerformat[4] = "integer";

$questions[5] = array(
  "The classes are intervals on a number line, so each one begins exactly where the one before it ended and no gap is left between them.",
  "A histogram is a bar graph with taller bars, and bar graphs always have gaps between the bars.",
  "Rounding the class width up is what removes the gap between neighbouring classes."
)
$answer[5] = 0;

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
      <p><span class="term-label">Step 1: sort, then take the range.</span> The smallest value is <b>' . $dmin . '</b> and the largest is <b>' . $dmax . '</b> ' . $unitWord . ', so the range is ' . $dmax . ' &minus; ' . $dmin . ' = <b>' . $range . '</b>.</p>
      <p><span class="term-label">Step 2: the class width.</span> Divide by the 5 classes you want: ' . $range . ' &divide; 5 = ' . $rawWidth . '. A class has to hold a whole number of ' . $unitWord . ', so round UP: <b>' . $width . '</b>. The classes therefore run ' . $classLine . ', and the last one reaches ' . $c5hi . ', which is where the largest value sits.</p>
      <p><span class="term-label">Step 3: place ' . $checkVal . '.</span> The classes start at ' . $c1lo . ' and each one is ' . $width . ' ' . $unitWord . ' wide, so ' . $checkVal . ' ' . $unitWord . ' lands in class <b>' . $checkClass . '</b>. Reading across the class list, the five frequencies are ' . $freqLine . ', so that class holds <b>' . $checkFreq . '</b> values.</p>
      <p><span class="term-label">Step 4: the next boundary.</span> Class ' . $checkClass . ' ends at ' . ($checkClass * $width + $c1lo - 1) . ' ' . $unitWord . ', and the class after it begins at the next whole number, <b>' . $nextLo . '</b>.</p>
      <p><span class="term-label">Step 5: why the bars touch.</span> A histogram bar covers an interval of the number line rather than a named category, and consecutive intervals share an endpoint. There is no category gap to leave, so the bars meet. A bar graph leaves gaps because its bars stand for separate categories, where a gap says "these are not neighbours".</p>
      <p><b>Answer:</b> (a) ' . $range . ' &nbsp;&nbsp; (b) ' . $width . ' &nbsp;&nbsp; (c) ' . $checkClass . ' &nbsp;&nbsp; (d) ' . $checkFreq . ' &nbsp;&nbsp; (e) ' . $nextLo . '</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 12px 0;">$intro Here are the 20 measurements, in $unitWord, in the order they were recorded.</p>
    <p style="margin:0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; font-family:ui-monospace,Menlo,Consolas,monospace; font-size:15px; line-height:1.8;">$dataList</p>
    <p style="margin:12px 0 0 0; font-size:15px; color:#444;">You want a grouped frequency table with 5 classes, and each class covers whole $unitWord only.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> What is the <b>range</b> of the data? $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> What <b>class width</b> do you get? $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Counting the classes from 1, which class does <b>$checkVal $unitWord</b> fall into? $answerbox[2]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">d.</span> How many of the 20 measurements fall in that same class? $answerbox[3]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">e.</span> What is the <b>lower class limit</b> of the class immediately after it? $answerbox[4]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">f.</span> In a histogram the bars touch each other. Why? $answerbox[5]
  </div>
</div>

// === ANSWER ===

$solutionguide
