"""Pull a bookSHelf section's problem set out to plain text.

    python reference/problem-set.py <section.html> [--solutions]

Prints each Problem N with its prompt, and with --solutions the worked steps too.

Exists because the front half of authoring a set -- read the section's numbered problems, decide
which the bank already covers, write only the rest -- was ad hoc every session. This script lived
in a session scratchpad and died with it, so the same extraction got rebuilt from scratch each
time. It is small on purpose: bookSHelf pages are ordinary HTML and the problem set is everything
from "Problem 1." onward.

Save the section page's HTML locally first (the book lives on bookSHelf; the app's MOM Write rail
fetches it, or save it from the browser). Then run this against the file.

--solutions matters more than it looks: a question taken from a numbered problem must repackage
THAT problem's own worked steps into its $solutionguide, because that is the explanation the
student was taught from. Read them before writing, not after.
"""
import html
import io
import re
import sys

path = sys.argv[1]
want_sol = "--solutions" in sys.argv

t = io.open(path, encoding="utf-8", errors="replace").read()
t = re.sub(r"<script.*?</script>", " ", t, flags=re.S)
t = re.sub(r"<style.*?</style>", " ", t, flags=re.S)
t = html.unescape(re.sub(r"<[^>]+>", "\n", t))
lines = [L.strip() for L in t.split("\n") if L.strip()]

# the problem set is everything from the first "Problem 1." onward
try:
    start = next(i for i, L in enumerate(lines) if re.match(r"^Problem 1\.$", L))
except StopIteration:
    start = next(i for i, L in enumerate(lines) if L.startswith("Problem 1"))

buf = []
cur = None
insol = False
for L in lines[start:]:
    if re.match(r"^Problem \d+\.$", L):
        if cur:
            buf.append(cur)
        cur = {"n": L, "prompt": [], "sol": []}
        insol = False
        continue
    if cur is None:
        continue
    if L == "Solution":
        insol = True
        continue
    (cur["sol"] if insol else cur["prompt"]).append(L)
if cur:
    buf.append(cur)

for p in buf:
    print("\n" + "=" * 70)
    print(p["n"])
    print("-" * 70)
    print(" | ".join(p["prompt"])[:1800])
    if want_sol:
        print("  SOLUTION: " + " | ".join(p["sol"])[:900])
print("\n\n%d problems" % len(buf))
