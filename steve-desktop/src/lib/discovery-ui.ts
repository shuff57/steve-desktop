import { evalScript } from './browser';

export const SELECTOR_LABELS: Record<string, string> = {
  videoPlayer: 'Video Player',
  interactiveForm: 'Interactive Form',
  navigationControl: 'Navigation Control',
  quizElement: 'Quiz Element',
};

export async function highlightSelector(selector: string) {
  if (!selector) return;

  try {
    await evalScript(`(function(selector) {
      document.querySelectorAll('[data-steve-refine-highlight]').forEach(function(el) {
        el.style.outline = el.dataset.steveOriginalOutline || '';
        el.style.outlineOffset = el.dataset.steveOriginalOutlineOffset || '';
        el.removeAttribute('data-steve-refine-highlight');
        el.removeAttribute('data-steve-original-outline');
        el.removeAttribute('data-steve-original-outline-offset');
      });
      if (!selector) return;
      try {
        var matches = document.querySelectorAll(selector);
        for (var i = 0; i < matches.length; i++) {
          var el = matches[i];
          el.dataset.steveOriginalOutline = el.style.outline || '';
          el.dataset.steveOriginalOutlineOffset = el.style.outlineOffset || '';
          el.style.outline = '3px dashed #f59e0b';
          el.style.outlineOffset = '2px';
          el.setAttribute('data-steve-refine-highlight', 'true');
        }
      } catch (e) {}
    })(${JSON.stringify(selector)})`);
  } catch {}
}

export async function clearRefinementHighlights() {
  try {
    await evalScript(`(function() {
      document.querySelectorAll('[data-steve-refine-highlight]').forEach(function(el) {
        el.style.outline = el.dataset.steveOriginalOutline || '';
        el.style.outlineOffset = el.dataset.steveOriginalOutlineOffset || '';
        el.removeAttribute('data-steve-refine-highlight');
        el.removeAttribute('data-steve-original-outline');
        el.removeAttribute('data-steve-original-outline-offset');
      });
    })()`);
  } catch {}
}
