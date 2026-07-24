// === NAME - DESCRIPTION: Addition Rule Formula - Given P(A), P(B), P(A∩B), find P(A∪B), P(A and not B), P(neither) ===
// === SET QUESTION TYPE TO: multipart ===

// === COMMON CONTROL ===

$anstypes = array("numfunc", "numfunc", "numfunc")

// Randomize the context. Two label variants: plural-verb form for "people ___" sentences,
// and bare-infinitive form for "does not ___" / "neither ___" constructions.
$ctx_verb_a = array("own a car", "drink coffee", "use Spotify")
$ctx_verb_b = array("own a house", "drink tea", "use Apple Music")
$ctx_short_a = array("car", "coffee", "Spotify")
$ctx_short_b = array("house", "tea", "Apple Music")
$ctx_intro   = array(
  "A consumer survey records, for each adult in a city, whether they own a car and whether they own a house.",
  "A barista's regulars were polled about which hot drink they ordered this week: coffee, tea, or both.",
  "A streaming-industry report tracks which subscribers actively use Spotify, Apple Music, or both."
)

$ci = rand(0, 2)
$v_a = $ctx_verb_a[$ci]
$v_b = $ctx_verb_b[$ci]
$sh_a  = $ctx_short_a[$ci]
$sh_b  = $ctx_short_b[$ci]
$intro = $ctx_intro[$ci]

// Pick integer percentages, then convert. Constraints:
//   pA >= pInt + 10, pB >= pInt + 10, pA + pB - pInt <= 95.
$pInt = rand(10, 25)
$pA   = rand($pInt + 15, 60)
$pB_lo = $pInt + 15
$pB_hi = 95 - $pA + $pInt
if ($pB_hi < $pB_lo) { $pB_hi = $pB_lo }
$pB = rand($pB_lo, $pB_hi)

$p_a   = $pA / 100
$p_b   = $pB / 100
$p_int = $pInt / 100
$p_un  = $p_a + $p_b - $p_int
$p_a_not_b = $p_a - $p_int
$p_neither = 1 - $p_un

$answer[0] = $p_un
$answer[1] = $p_a_not_b
$answer[2] = $p_neither
$abstolerance[0] = 0.005
$abstolerance[1] = 0.005
$abstolerance[2] = 0.005

$pA_str = $pA . "%"
$pB_str = $pB . "%"
$pInt_str = $pInt . "%"

$un_show = round($p_un, 4)
$anb_show = round($p_a_not_b, 4)
$nei_show = round($p_neither, 4)

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
      <p>Let A = "people who '.$v_a.'" and B = "people who '.$v_b.'". We are given P(A) = '.$p_a.', P(B) = '.$p_b.', P(A &cap; B) = '.$p_int.'.</p>
      <p><b>(a) P(A &cup; B).</b> Addition Rule: P(A &cup; B) = P(A) + P(B) &minus; P(A &cap; B) = '.$p_a.' + '.$p_b.' &minus; '.$p_int.' = <b>'.$un_show.'</b>.</p>
      <p><b>(b) P(A &cap; <span style="text-decoration:overline;">B</span>) = P(A and not B).</b> Anyone in A is either in both or in A only: P(A) = P(A &cap; B) + P(A &cap; <span style="text-decoration:overline;">B</span>), so P(A &cap; <span style="text-decoration:overline;">B</span>) = P(A) &minus; P(A &cap; B) = '.$p_a.' &minus; '.$p_int.' = <b>'.$anb_show.'</b>.</p>
      <p><b>(c) P(neither) = P(<span style="text-decoration:overline;">A &cup; B</span>).</b> Complement rule: 1 &minus; P(A &cup; B) = 1 &minus; '.$un_show.' = <b>'.$nei_show.'</b>.</p>
      <div style="margin:10px 0; padding:0.6em 1em; background:#e8f5e9; border-left:4px solid #4CAF50; border-radius:0 8px 8px 0;">
        Answers: P(A &cup; B) = '.$un_show.', P(A &cap; <span style="text-decoration:overline;">B</span>) = '.$anb_show.', P(neither) = '.$nei_show.'.
      </div>
    </div>
  </details>
</div>'

// === QUESTION TEXT ===

<div style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif; font-size:16px; line-height:1.6; color:#21242c; max-width:688px;">
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <p style="margin:0 0 0.6em 0;">$intro</p>
    <p style="margin:0;">Let <b>A</b> = "people who $v_a" and <b>B</b> = "people who $v_b". The survey found:</p>
    <ul style="margin:0.4em 0 0 0; padding-left:1.4em;">
      <li><b>P(A)</b> = $pA_str of people $v_a.</li>
      <li><b>P(B)</b> = $pB_str of people $v_b.</li>
      <li><b>P(A &cap; B)</b> = $pInt_str of people $v_a and $v_b.</li>
    </ul>
    <p style="margin:0.6em 0 0 0;">A person is selected at random.</p>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">a.</span> Find <b>P(A &cup; B)</b>, the probability that the person is in A, in B, or in both.
    <div style="margin-top:12px;text-align:center;">$answerbox[0]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">b.</span> Find <b>P(A &cap; <span style="text-decoration:overline;">B</span>)</b>, the probability that the person is in A but <b>not</b> in B.
    <div style="margin-top:12px;text-align:center;">$answerbox[1]</div>
  </div>
  <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin:10px 0; box-shadow:0 4px 6px -1px rgba(0,0,0,0.07),0 2px 4px -2px rgba(0,0,0,0.04);">
    <span style="display:inline-block; background:#e8f0fe; color:#1865f2; border-radius:6px; padding:3px 10px; font-size:13px; font-weight:700; margin-right:10px; vertical-align:middle;">c.</span> Find <b>P(neither)</b>, the probability that the person is in neither A nor B.
    <div style="margin-top:12px;text-align:center;">$answerbox[2]</div>
  </div>
</div>


// === ANSWER ===

$solutionguide
