import http from 'node:http';
import { readFileSync } from 'node:fs';
import { join, extname } from 'node:path';

const root = new URL('.', import.meta.url).pathname.replace(/^\/([A-Za-z]:)/, '$1');
const mime = {
  '.html': 'text/html; charset=utf-8',
  '.js': 'text/javascript; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
};

http
  .createServer((req, res) => {
    const pathname = decodeURIComponent(new URL(req.url, 'http://localhost').pathname);
    const file = join(root, pathname === '/' ? 'roster.html' : pathname);
    try {
      const body = readFileSync(file);
      res.writeHead(200, { 'Content-Type': mime[extname(file)] ?? 'application/octet-stream' });
      res.end(body);
    } catch {
      res.writeHead(404, { 'Content-Type': 'text/plain' });
      res.end('not found: ' + pathname);
    }
  })
  .listen(5199, '127.0.0.1', () => console.log('fixture server on http://127.0.0.1:5199/roster.html'));
