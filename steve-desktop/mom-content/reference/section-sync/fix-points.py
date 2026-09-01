import json, io, time
plan = {p['sec']+'-'+p['kind']: p for p in json.load(io.open(r"C:/Users/shuff/AppData/Local/Temp/claude/C--Users-shuff-Documents-GitHub-steve-desktop/a660c767-7ab0-4a24-9aa4-8c94654cc9bf/scratchpad/secplan.json", encoding='utf-8'))}
for tag in ("P4-practice","P7-practice"):
    p = plan[tag]; cid, aid = p['cid'], p['aid']
    want = {q['qsetid']: q['points'] for q in p['q']}
    goto_url("https://www.myopenmath.com/course/addquestions.php?cid=%s&aid=%s"%(cid,aid)); wait_for_load()
    rows = json.loads(js("JSON.stringify(itemarray.map(function(r){return [String(r[0]),String(r[1])];}))"))
    print("\n=== %s  %d instances" % (tag, len(rows)))
    for inst, qset in rows:
        target = want[qset]
        goto_url("https://www.myopenmath.com/course/modquestion2.php?id=%s&aid=%s&cid=%s"%(inst,aid,cid)); wait_for_load()
        r = js("""(function(){
          var e=document.getElementsByName('points')[0];
          if(!e) return 'NOFIELD';
          var was=e.value;
          var set=Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype,'value').set;
          set.call(e, '%d');
          e.dispatchEvent(new Event('input',{bubbles:true}));
          e.dispatchEvent(new Event('change',{bubbles:true}));
          var b=[...document.querySelectorAll('input[type=submit]')].find(x=>/Save Settings/i.test(x.value));
          if(!b) return 'NOSAVE was='+was;
          b.click(); return 'was='+was;})()""" % target)
        time.sleep(1.2)
        print("   inst %s qset %s -> %d  (%s)" % (inst, qset, target, r))
    goto_url("https://www.myopenmath.com/course/addquestions.php?cid=%s&aid=%s"%(cid,aid)); wait_for_load()
    chk = json.loads(js("""JSON.stringify({n:itemarray.length,
      total:itemarray.reduce(function(s,r){return s+Number(r[4]);},0),
      rows:itemarray.map(function(r){return [String(r[1]),Number(r[4])];})})"""))
    exp = [[q['qsetid'], q['points']] for q in p['q']]
    print("  VERIFY %s: %d q, %d pts, order %s" % (tag, chk['n'], chk['total'],
          "MATCH" if chk['rows']==exp else "MISMATCH"))
