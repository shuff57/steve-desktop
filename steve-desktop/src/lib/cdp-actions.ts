import { invoke } from '@tauri-apps/api/core';
import { cdp } from './cdp-client';

export interface ActionResult {
  success: boolean;
  data?: unknown;
  error?: string;
}

const DANGEROUS_JS_PATTERNS = [
  'localStorage',
  'sessionStorage',
  'document.cookie',
  'window.location=',
  'window.location.href=',
  'fetch(',
  'XMLHttpRequest',
  'WebSocket(',
];

function checkDangerousPatterns(code: string): string | null {
  for (const pattern of DANGEROUS_JS_PATTERNS) {
    if (code.includes(pattern)) return pattern;
  }
  return null;
}

function escapeSelector(selector: string): string {
  return selector.replace(/'/g, "\\'");
}

export async function connectCDP(port?: number): Promise<boolean> {
  try {
    let resolvedPort = port;
    if (resolvedPort === undefined || resolvedPort === null) {
      const tauriPort = await invoke<number | null>('get_cdp_port');
      if (tauriPort === null || tauriPort === undefined) return false;
      resolvedPort = tauriPort;
    }

    const wsUrl = await invoke<string | null>('discover_cdp_target', { port: resolvedPort });
    if (!wsUrl) return false;

    return await cdp.connectToUrl(wsUrl);
  } catch {
    return false;
  }
}

export async function connect(port?: number): Promise<boolean> {
  return await connectCDP(port);
}

export async function disconnectCDP(): Promise<void> {
  await cdp.disconnect();
}

export async function disconnect(): Promise<void> {
  await disconnectCDP();
}

export async function send(method: string, params?: Record<string, unknown>): Promise<unknown> {
  return await cdp.send(method, params);
}

export function isConnected(): boolean {
  return cdp.isConnected();
}

export async function evalScript(script: string): Promise<ActionResult> {
  try {
    if (!cdp.isConnected()) return { success: false, error: 'Not connected to CDP' };
    const blocked = checkDangerousPatterns(script);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    const result = (await cdp.send('Runtime.evaluate', {
      expression: script,
      returnByValue: true,
    })) as { result?: { value?: unknown } };

    return { success: true, data: result.result?.value };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function injectScript(script: string): Promise<ActionResult> {
  try {
    const blocked = checkDangerousPatterns(script);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    await invoke('inject_webview_script', { script });
    return { success: true };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function captureWebviewScreenshot(): Promise<ActionResult> {
  try {
    const base64 = await invoke<string>('capture_webview_screenshot');
    return { success: true, data: base64 };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function cdpScreenshot(): Promise<string> {
  const result = (await cdp.send('Page.captureScreenshot', {
    format: 'jpeg',
    quality: 80,
  })) as { data: string };

  return 'data:image/jpeg;base64,' + result.data;
}

export async function pwClick(selector: string): Promise<ActionResult> {
  const expression = `document.querySelector('${escapeSelector(selector)}')?.click();`;
  return await evalScript(expression);
}

export async function pwType(selector: string, text: string, clear?: boolean): Promise<ActionResult> {
  try {
    if (!cdp.isConnected()) return { success: false, error: 'Not connected to CDP' };

    const escapedSelector = escapeSelector(selector);
    const escapedText = JSON.stringify(text);
    const expression = clear
      ? `(function(){const el=document.querySelector('${escapedSelector}');if(!el) return false; el.focus(); el.value=''; el.dispatchEvent(new Event('input',{bubbles:true})); return true;})()`
      : `!!document.querySelector('${escapedSelector}')`;

    const blocked = checkDangerousPatterns(expression);
    if (blocked) return { success: false, error: `Blocked dangerous pattern: ${blocked}` };

    const focused = (await cdp.send('Runtime.evaluate', {
      expression,
      returnByValue: true,
    })) as { result?: { value?: boolean } };

    if (!focused.result?.value) return { success: false, error: `Element not found: ${selector}` };

    await cdp.send('Runtime.evaluate', {
      expression: `(function(){const el=document.querySelector('${escapedSelector}'); if(!el) return false; el.focus(); return true;})()`,
      returnByValue: true,
    });
    await cdp.send('Input.insertText', { text: JSON.parse(escapedText) as string });

    return { success: true };
  } catch (error: unknown) {
    return { success: false, error: error instanceof Error ? error.message : String(error) };
  }
}

export async function pwGetText(selector?: string): Promise<ActionResult> {
  const expression = selector
    ? `document.querySelector('${escapeSelector(selector)}')?.textContent ?? ''`
    : `(document.body.innerText || '')`;
  return await evalScript(expression);
}
