/**
 * Let the teacher TYPE an answer into the preview and be told whether it is right.
 *
 * The answer key already shows what the answer is; this checks what you entered against it, which
 * is the difference between reading a number and confirming the question can actually be answered.
 *
 * Same one-pass rule as the key (see answer-key.ts): questions randomize, so the correct values
 * have to come from the SAME render as the boxes the teacher is typing into. They are substituted
 * into a hidden element in the question text and read back by a small script inside the iframe —
 * no second POST, no cross-frame messaging.
 *
 * This is a teacher-facing preview, so the expected values sitting in the DOM is fine; it is never
 * a page a student sees.
 */

import { answerTypes } from './answer-key';

const QUESTION_TEXT = '// === QUESTION TEXT ===';
const ANSWER = '// === ANSWER ===';

/** How many parts the body asks for. Mirrors answer-key.ts. */
function answerBoxCount(source: string): number {
  const seen = new Set<number>();
  for (const m of source.matchAll(/\$answerbox\[(\d+)\]/g)) seen.add(Number(m[1]));
  return seen.size;
}

/**
 * Types this can judge honestly.
 *
 * `number`/`calculated` compare numerically; `choices` compares the selected index, which is what
 * the radio's `value` already is. `numfunc` is deliberately absent: judging it means deciding
 * whether two expressions are equivalent, and a string compare would call `2x` wrong against
 * `x*2` — worse than declining, because the teacher would go hunting for a bug that is not there.
 */
const CHECKABLE = new Set(['number', 'calculated', 'choices']);

/**
 * The answer type for part `i`.
 *
 * `$anstypes` is optional: a single-answer question often declares its type ONLY in the
 * `SET QUESTION TYPE TO:` header (regression/intro/q18 is a real `choices` question with no
 * `$anstypes` at all). Without this fallback the type resolves to empty and the checker disables
 * itself on a question it can judge perfectly well.
 */
function typeFor(source: string, types: string[], i: number, indexed: boolean): string {
  const declared = indexed ? types[i] : types[0];
  if (declared) return declared;
  const header = source.match(/^\/\/ === SET QUESTION TYPE TO:\s*([a-z]+)/im);
  const h = header?.[1]?.toLowerCase() ?? '';
  return h === 'multipart' ? '' : h;
}

/** Absolute tolerance the question declares, if any. */
function tolerance(source: string): string {
  const m = source.match(/^\s*\$abstolerance\s*=\s*([0-9.eE+-]+)/m);
  return m ? m[1] : '';
}

/**
 * An empty slot the script fills with that part's Check button.
 *
 * Substituted right after `$answerbox[i]`, exactly where the answer-key chip goes, so the button
 * sits WITH the box it belongs to. Inserting it after the input element instead put it wherever
 * the field happened to fall — mid-sentence for an inline box, and after the first option's
 * container for a radio group, which read as "somewhere in the middle of the question".
 */
function slot(i: number): string {
  return `<span class="momcheck" data-part="${i}"></span>`;
}

/**
 * Put a Check slot after every answer box in the body.
 *
 * Indexed boxes are matched first: a bare `$answerbox` pattern would otherwise also match the
 * `$answerbox` prefix of `$answerbox[0]` and split it. Same rule as the answer key.
 */
function annotateBoxes(body: string): string {
  const out = body.replace(/\$answerbox\[(\d+)\]/g, (m, d) => m + slot(Number(d)));
  return out.replace(/\$answerbox(?!\[|<span)/g, (m) => m + slot(0));
}

/**
 * Add the answers and the per-part Check slots to the QUESTION TEXT section.
 *
 * Values go through MOM's own substitution (`$answer[0]`), so they are the values from this render.
 * Only the BODY is annotated — the control block is code, and the ANSWER section is dropped by the
 * sandbox, so a slot in either place would be wrong or invisible.
 */
export function withCheckData(source: string): string {
  const qtIndex = source.indexOf(QUESTION_TEXT);
  if (qtIndex < 0) return source;

  const n = answerBoxCount(source);
  const types = answerTypes(source);
  const parts = n === 0 ? 1 : n;

  const attrs: string[] = [`data-n="${parts}"`, `data-tol="${tolerance(source)}"`];
  for (let i = 0; i < parts; i++) {
    const type = typeFor(source, types, i, n !== 0);
    attrs.push(`data-t${i}="${type}"`);
    attrs.push(`data-a${i}="${n === 0 ? '$answer' : `$answer[${i}]`}"`);
  }

  const bodyStart = qtIndex + QUESTION_TEXT.length;
  const ansIndex = source.indexOf(ANSWER, bodyStart);
  const bodyEnd = ansIndex < 0 ? source.length : ansIndex;

  const body = annotateBoxes(source.slice(bodyStart, bodyEnd));
  const meta = `\n<div id="__momcheck" style="display:none" ${attrs.join(' ')}></div>\n`;
  return source.slice(0, bodyStart) + body.replace(/\s*$/, '') + meta + source.slice(bodyEnd);
}

/** True when at least one part can be judged — used to explain an inert Check button. */
export function checkableParts(source: string): number {
  const n = answerBoxCount(source);
  const types = answerTypes(source);
  const parts = n === 0 ? 1 : n;
  let count = 0;
  for (let i = 0; i < parts; i++) {
    if (CHECKABLE.has(typeFor(source, types, i, n !== 0))) count++;
  }
  return count;
}

/**
 * The in-iframe checker.
 *
 * Fields are paired to parts by DOM ORDER rather than by id, because the sandbox names text inputs
 * `qn1000+i` but a single-part question just `qn0` — order is the one thing both agree on. Radios
 * sharing a name are one field, not four.
 */
const CHECKER = `<script>
(function () {
  var meta = document.getElementById('__momcheck');
  if (!meta) return;
  var n = +meta.getAttribute('data-n');
  var tol = parseFloat(meta.getAttribute('data-tol'));
  if (!isFinite(tol)) tol = 0;

  // One entry per part, in document order; radios sharing a name collapse to a single field.
  var fields = [], seen = {};
  var inputs = document.querySelectorAll('input[type=text], input[type=radio], select, textarea');
  for (var i = 0; i < inputs.length; i++) {
    var el = inputs[i], key = el.name || el.id || ('_' + i);
    if (seen[key]) continue;
    seen[key] = 1;
    fields.push({ key: key, el: el, radio: el.type === 'radio' });
  }

  function valueOf(f) {
    if (!f.radio) return f.el.value.trim();
    var picked = document.querySelector('input[name="' + f.key + '"]:checked');
    return picked ? picked.value : '';
  }

  function verdict(got, want, type) {
    if (got === '') return null;
    if (type === 'choices') return got === String(want).trim();
    var a = parseFloat(got.replace(/[$,\\s]/g, '')), b = parseFloat(want);
    if (!isFinite(a) || !isFinite(b)) return null;
    // Fall back to a relative epsilon when the question declares no tolerance, so 9259.26 is not
    // called wrong against 9259.2593.
    var slack = tol > 0 ? tol : Math.max(1e-9, Math.abs(b) * 1e-6);
    return Math.abs(a - b) <= slack + 1e-12;
  }

  for (var p = 0; p < n && p < fields.length; p++) {
    (function (p) {
      var f = fields[p];
      var type = meta.getAttribute('data-t' + p) || '';
      var want = meta.getAttribute('data-a' + p);
      var out = document.createElement('span');
      out.style.cssText = 'margin-left:8px;font:600 13px Arial;vertical-align:middle';
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = 'Check';
      btn.style.cssText = 'margin-left:6px;padding:2px 9px;border-radius:10px;border:1px solid #9aa;background:#f6f7f9;cursor:pointer;font:13px Arial';
      var can = ${JSON.stringify([...CHECKABLE])}.indexOf(type) >= 0;
      if (!can) {
        btn.disabled = true;
        btn.title = 'Cannot check a "' + type + '" answer — it needs MyOpenMath to judge equivalence';
        btn.style.opacity = '.5';
        btn.style.cursor = 'default';
      }
      btn.onclick = function () {
        var v = verdict(valueOf(f), want, type);
        if (v === null) { out.textContent = 'enter a value'; out.style.color = '#8a6d3b'; }
        else if (v) { out.textContent = 'correct'; out.style.color = '#1b5e20'; }
        else { out.textContent = 'not correct'; out.style.color = '#b91c1c'; }
      };
      // The slot was substituted right after this part's $answerbox, so the button lands with its
      // box rather than wherever the input element happened to sit. Fall back to inserting after
      // the field only if the question has no slot (no $answerbox marker to attach to).
      var wrap = document.querySelector('.momcheck[data-part="' + p + '"]');
      if (!wrap) {
        wrap = document.createElement('span');
        wrap.className = 'momcheck';
        // A choices question has no $answerbox to substitute a slot after, so anchor to the WHOLE
        // radio group. Anchoring to the nearest container instead lands on the first option's
        // <li>, dropping the button between option 1 and option 2 — visibly mid-question.
        var anchor = f.radio
          ? f.el.closest('[role=radiogroup]') || f.el.closest('ul,ol,table,fieldset') || f.el.parentNode
          : f.el;
        if (!anchor || !anchor.parentNode) return;
        anchor.parentNode.insertBefore(wrap, anchor.nextSibling);
      }
      wrap.appendChild(btn);
      wrap.appendChild(out);
    })(p);
  }
})();
</script>`;

/** Put the checker at the end of the body so the fields it wires up already exist. */
export function injectChecker(html: string): string {
  if (html.includes('data-momcheck_script')) return html;
  const script = CHECKER.replace('<script>', '<script data-momcheck_script>');
  return html.includes('</body>') ? html.replace('</body>', `${script}</body>`) : html + script;
}
