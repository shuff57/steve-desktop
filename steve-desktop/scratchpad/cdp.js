const [port, urlSub = '', expr, exprFile] = process.argv.slice(2);
let code = expr;
if (exprFile) {
  const raw = (await Bun.file(exprFile).text()).trim();
  code = raw.startsWith('(async') || raw.startsWith('async') ? raw : `(async()=>{\n${raw}\n})()`;
} else if (!expr) {
  console.error('usage: bun cdp.js <port> <urlSubstring> "<js expression>" [exprFile]');
  process.exit(1);
}

const targets = await (await fetch(`http://127.0.0.1:${port}/json/list`)).json();
const page = targets.find((t) => t.type === 'page' && t.url.includes(urlSub));
if (!page) {
  console.error(`no page target with url containing: ${urlSub}`);
  console.error('targets:', targets.map((t) => `${t.type}:${t.url}`).join('\n'));
  process.exit(1);
}

const ws = new WebSocket(page.webSocketDebuggerUrl);
let id = 0;
const pending = new Map();

const send = (method, params = {}) =>
  new Promise((resolve, reject) => {
    const msgId = ++id;
    pending.set(msgId, { resolve, reject });
    ws.send(JSON.stringify({ id: msgId, method, params }));
  });

ws.onmessage = (ev) => {
  const msg = JSON.parse(ev.data);
  if (!msg.id) return;
  const p = pending.get(msg.id);
  if (!p) return;
  pending.delete(msg.id);
  if (msg.error) p.reject(new Error(JSON.stringify(msg.error)));
  else p.resolve(msg.result);
};

await new Promise((resolve) => (ws.onopen = resolve));
const result = await send('Runtime.evaluate', {
  expression: code,
  awaitPromise: true,
  returnByValue: true,
});
console.log(JSON.stringify(result.result?.result?.value ?? result, null, 2));
ws.close();
process.exit(0);
