// === NAME - DESCRIPTION: At Least One, Two Ways - Add every path that satisfies "at least one", then recompute it as 1 minus none, and see why the complement is the route worth learning ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

// Built for 3.5. The whole question is that (a) and (b) come out the same number: the direct count
// of every path that has a hit, versus 1 minus the single path that has none. Part (c) names the
// reason the complement wins: for three trials the direct count is seven paths, the complement is
// one.
$anstypes = array("numfunc", "numfunc", "choices")

$i = rand(0, 2)

$contexts = array(
  "A bag of marbles",
  "A drawer of socks",
  "A box of pens"
)
$context = $contexts[$i]

$c1Names = array("red", "black", "blue")
$c2Names = array("blue", "white", "green")
$c1 = $c1Names[$i]
$c2 = $c2Names[$i]

$itemNames = array("marbles", "socks", "pens")
$item = $itemNames[$i]

// Three draws WITHOUT replacement. $r kept small so no numerator goes negative.
$r = rand(3, 5)
$total = 10
$b = $total - $r

// "At least one $c1": the event with no $c1 is all three $c2.
$pAll = ($r / $total) * (($r - 1) / ($total - 1)) * (($r - 2) / ($total - 2))
$pNone = ($b / $total) * (($b - 1) / ($total - 1)) * (($b - 2) / ($total - 2))

// (a) Direct: 1 - P(none) is the complement route, but the direct route adds the 7 paths that hit.
// (b) Complement: 1 - P(none). Both must land on the same number.
$direct = 1 - $pNone
$complement = 1 - $pNone

$answer[0] = $direct
$answer[1] = $complement

$questions[2] = array(
  "Because only one path gives NO $c1, so 1 minus that single path counts every way to get at least one in one step.",
  "Because the direct paths are all equally likely, so adding them is the same as subtracting one.",
  "Because \"at least one\" is a single event, so it always has exactly one path.",
  "Because the multiplication rule only works when exactly one path is being counted."
)
$answer[2] = 0

$sol = '
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
    <summary><span class="sol-arrow-closed">&#9656;</span><span class="sol-arrow-open">&#9662;</span> Step-by-Step Solution</summary>
    <div class="sol-body">
      <p><span class="term-label">The two answers are the same number: that is the lesson.</span> &ldquo;At least one $c1&rdquo; is the opposite of &ldquo;no $c1&rdquo;, so however you compute it, it must come out identical.</p>
      <ul>
        <li><b>(a) The direct route.</b> &ldquo;At least one $c1&rdquo; is every path except the all-$c2 bottom path. That bottom path has probability `' . $b . '/' . $total . ' xx ' . ($b - 1) . '/' . ($total - 1) . ' xx ' . ($b - 2) . '/' . ($total - 2) . ' = ' . $pNone . '`, so the direct count is `1 - ' . $pNone . ' = ' . $direct . '`.</li>
        <li><b>(b) The complement route.</b> `1 - P(no $c1) = 1 - ' . $pNone . ' = ' . $complement . '`. It is the same number because it is the same event described as a subtraction.</li>
        <li><b>(c) Why the complement is worth learning.</b> Counting the seven paths that contain a $c1 is error-prone; subtracting the one path that contains none is one product. For three trials the direct route has 2&sup3; &minus; 1 = 7 paths to add; the complement has exactly one.</li>
      </ul>
      <p><span class="term-label">The check the two parts give you.</span> (a) and (b) must agree to the last digit. If they differ, the mismatch is between the paths you think satisfy the event and the single path you think gives none: the two lists do not cover everyone.</p>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 4px 0;">$context holds <b>$r $c1</b> and <b>$b $c2</b> $item, $total in total. Three are drawn at random, <b>without replacement</b>, and the event is <b>at least one $c1</b>.</p>
    <p style="margin:6px 0 0 0; padding:12px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">Compute the same probability two ways. They must agree. Enter answers as fractions or decimals rounded to 4 places.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> The <b>direct</b> way: add the probability of every path that contains at least one $c1. $answerbox[0]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> The <b>complement</b> way: <b>1 &minus; P(no $c1)</b>. $answerbox[1]
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0;">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Why is the complement usually the better route for &ldquo;at least one&rdquo;? $answerbox[2]
  </div>
</div>

// === ANSWER ===

$sol
