// === NAME - DESCRIPTION: Grouped Frequency Table Interval Boundary Reasoning - Select every true statement about why a continuous measurement's interval boundaries end in a fraction, against the common misconceptions ===
// === SET QUESTION TYPE TO: multans ===

// === COMMON CONTROL ===

$anstypes = "multans"

$fracChoices = array(0.05, 0.25, 0.45, 0.75, 0.95)
$frac = randfrom($fracChoices)
$start = rand(8, 45)
$width = rand(2, 6)

$bounds = array()
$bounds[0] = round($start + $frac, 2)
$bounds[1] = round($bounds[0] + $width, 2)
$bounds[2] = round($bounds[1] + $width, 2)
$bounds[3] = round($bounds[2] + $width, 2)
$bounds[4] = round($bounds[3] + $width, 2)
$b0 = $bounds[0]
$b1 = $bounds[1]

$intLabels = array()
$intLabels[0] = $bounds[0] . "&ndash;" . $bounds[1]
$intLabels[1] = $bounds[1] . "&ndash;" . $bounds[2]
$intLabels[2] = $bounds[2] . "&ndash;" . $bounds[3]
$intLabels[3] = $bounds[3] . "&ndash;" . $bounds[4]

$freqs = rands(4, 22, 4)

$tableRows = ""
$tableRows = $tableRows . "<tr><td>" . $intLabels[0] . "</td><td>" . $freqs[0] . "</td></tr>"
$tableRows = $tableRows . "<tr><td>" . $intLabels[1] . "</td><td>" . $freqs[1] . "</td></tr>"
$tableRows = $tableRows . "<tr><td>" . $intLabels[2] . "</td><td>" . $freqs[2] . "</td></tr>"
$tableRows = $tableRows . "<tr><td>" . $intLabels[3] . "</td><td>" . $freqs[3] . "</td></tr>"

$subjects = array("the finish times, in seconds, of the runners in a 5K race", "the birth weights, in ounces, of a litter of piglets", "the commute distances, in miles, of a company's employees", "the shelf lives, in days, of the loaves in a bakery's morning batch")
$units = array("seconds", "ounces", "miles", "days")
$picked = jointrandfrom($subjects, $units)
$subj = $picked[0]
$unit = $picked[1]

$questions = array("Every measurement falls inside exactly one interval, because the boundary values themselves are not values the data can take.", "No measurement can land exactly on a boundary, since the boundaries fall between the actual possible data values rather than on one of them.", "The interval boundaries must be whole numbers for a frequency table to be valid.", "Every interval in a grouped table must contain the same number of data values.", "Because the boundaries include a fraction, the intervals no longer all have the same width.", "The fractional boundary values are just a rounding artifact and have no effect on how the data are counted.")
$answers = "0,1"
$scoremethod = "allornothing"

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
      <p><span class="term-label">Step 1: What a grouped table does.</span> The measurement is continuous, so instead of one row per data value, the values are sorted into intervals such as ' . $intLabels[0] . ' and ' . $intLabels[1] . '.</p>
      <p><span class="term-label">Step 2: Why the boundaries end in a fraction.</span> A boundary like ' . $b0 . ' is not a value the data can actually equal: the ' . $unit . ' were only ever going to land above or below it. That guarantees every measurement falls inside exactly one interval and that no measurement can ever land exactly on a boundary. Those are the two true statements.</p>
      <p><span class="term-label">Step 3: Why the rest are false.</span> Boundaries do not need to be whole numbers: using a fraction is what makes them work. Nothing forces the frequencies to match either: this table&#8217;s counts (' . $freqs[0] . ', ' . $freqs[1] . ', ' . $freqs[2] . ', ' . $freqs[3] . ') are not required to be equal from interval to interval. Every interval here still spans exactly ' . $width . ' ' . $unit . ', so the fraction changes where the boundaries fall, not how wide the intervals are. And the fraction is a deliberate design choice, not a rounding artifact: it is precisely what keeps data off the boundaries.</p>
      <p><b>Answer:</b> The true statements are &#8220;Every measurement falls inside exactly one interval&#8230;&#8221; and &#8220;No measurement can land exactly on a boundary&#8230;&#8221;</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">A researcher recorded $subj, and grouped the values into the interval frequency table below.</p>
    <table style="border-collapse:collapse; width:100%; margin:10px 0;">
      <thead>
        <tr style="background:#f0f4ff;">
          <th style="border:1px solid #d1d5db; padding:8px; text-align:center; font-weight:700;">Interval (in $unit)</th>
          <th style="border:1px solid #d1d5db; padding:8px; text-align:center; font-weight:700;">Frequency</th>
        </tr>
      </thead>
      <tbody>
        $tableRows
      </tbody>
    </table>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 10px 0;">Notice that every boundary, $b0, $b1, and so on, ends in a fraction rather than falling on a whole number of $unit.</p>
    <p style="margin:0 0 10px 0;"><strong>Select all statements below that correctly explain why the table's boundaries are constructed this way.</strong></p>
    $answerbox
  </div>
</div>

// === ANSWER ===

$solutionguide
