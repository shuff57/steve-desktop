import json, io, os, sys, time
SEC = {"P3":("339304","34"), "P4":("334243","34"), "P7":("339625","78")}
sec = sys.argv[1]; cid, block = SEC[sec]
OUT = r"C:/Users/shuff/AppData/Local/Temp/claude/C--Users-shuff-Documents-GitHub-steve-desktop/a660c767-7ab0-4a24-9aa4-8c94654cc9bf/scratchpad/times-%s.jsonl" % sec
done = set()
if os.path.exists(OUT):
    for line in io.open(OUT, encoding='utf-8'):
        try: done.add(json.loads(line)['aid'])
        except Exception: pass
print("resuming: %d already read" % len(done))

goto_url("https://www.myopenmath.com/course/course.php?cid=%s&folder=0"%cid); wait_for_load()
time.sleep(3)
prev = -1
stable = 0
for _ in range(10):
    js("""(function(){[...document.querySelectorAll('a[id^=blockh]')].forEach(function(h){
      var t=document.getElementById(h.id.replace('blockh','block'));
      if(t&&t.classList.contains('hidden')) h.click();});})()""")
    time.sleep(2.5)
    n = int(js("new Set([...document.querySelectorAll('a[href*=\"aid=\"]')].map(function(a){return a.href.match(/aid=(\d+)/)[1];})).size"))
    hid = int(js("document.querySelectorAll('div[id^=block].hidden').length"))
    if hid == 0 and n == prev:
        stable += 1
        if stable >= 2: break
    else:
        stable = 0
    prev = n
items = json.loads(js(r"""(function(){
  var m={},out=[];
  [...document.querySelectorAll('a[href*="aid="]')].forEach(function(a){
    var id=(a.href.match(/aid=(\d+)/)||[])[1]; if(!id||m[id]) return;
    m[id]=1; out.push(id);});
  return JSON.stringify(out);})()"""))
print("%s: %d assessments, %d to read" % (sec, len(items), len([i for i in items if i not in done])))
if len(items) < 80:
    raise SystemExit("ABORT %s: only %d assessments found, expected ~92 - block expansion incomplete" % (sec, len(items)))

f = io.open(OUT, "a", encoding="utf-8", newline="\n")
for n, aid in enumerate(items):
    if aid in done: continue
    rec = None
    for attempt in (1,2,3):
        try:
            goto_url("https://www.myopenmath.com/course/addassessment2.php?id=%s&cid=%s"%(aid,cid))
            wait_for_load()
            rec = json.loads(js(r"""(function(){var o={};
              ['sdate','stime','edate','etime','name'].forEach(function(n){
                var e=document.getElementsByName(n)[0]; o[n]=e?e.value:null;});
              ['sdatetype','edatetype'].forEach(function(n){
                var c=document.querySelector('[name='+n+']:checked'); o[n]=c?c.value:null;});
              return JSON.stringify(o);})()"""))
            break
        except Exception as e:
            print("   retry %d on aid %s (%s)" % (attempt, aid, str(e)[:60])); time.sleep(3)
    if rec is None:
        print("   GAVE UP on aid %s" % aid); continue
    rec['aid'] = aid; rec['cid'] = cid; rec['block'] = block; rec['sec'] = sec
    f.write(json.dumps(rec) + "\n"); f.flush()
    if n % 20 == 0: print("   ...%d/%d" % (n, len(items)))
f.close()
print("DONE %s" % sec)
