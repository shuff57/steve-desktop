(function(){
  var NL = String.fromCharCode(10);
  var body = document.body.innerText;
  if (body.indexOf('No Questions currently in assessment') >= 0)
    return JSON.stringify({empty:true, rows:[], note:'explicit empty message'});
  // anchor: the element whose text STARTS with the heading
  var all = document.querySelectorAll('h1,h2,h3,h4,b,strong,div,td,p,span'), anchor=null;
  for (var i=0;i<all.length;i++){
    var t=(all[i].innerText||'');
    if (t.indexOf('Questions in Assessment') === 0){ anchor = all[i]; break; }
  }
  if (!anchor) return JSON.stringify({empty:false, rows:[], note:'no heading anchor'});
  // first table AFTER the anchor in document order
  var tables = document.querySelectorAll('table'), tbl=null;
  for (var i=0;i<tables.length;i++){
    var pos = anchor.compareDocumentPosition(tables[i]);
    if (pos & Node.DOCUMENT_POSITION_FOLLOWING){
      if (tables[i].querySelector('a[href*="moddataset"]')){ tbl = tables[i]; break; }
    }
  }
  if (!tbl) return JSON.stringify({empty:false, rows:[], note:'no table after anchor'});
  var rows = tbl.querySelectorAll('tr');
  // header map from the first row containing the word Description
  var col = {};
  for (var r=0;r<rows.length;r++){
    var cs = rows[r].querySelectorAll('th,td'), txt=[];
    for (var k=0;k<cs.length;k++) txt.push((cs[k].innerText||'').trim());
    if (txt.indexOf('Description') >= 0){
      for (var k=0;k<txt.length;k++){
        if(!txt[k]) continue;
        col[txt[k]] = k;
        var head = txt[k].split(NL)[0].trim();
        if (head && col[head]===undefined) col[head] = k;
      }
      break;
    }
  }
  var out=[];
  for (var r=0;r<rows.length;r++){
    var row=rows[r];
    var cs = row.querySelectorAll('td');
    if (!cs.length) continue;
    var cell = function(name){ var i=col[name]; return (i!==undefined && cs[i]) ? (cs[i].innerText||'').trim() : null; };
    // order: prefer the row's select (checkbox layout), else the Order cell text
    var sel = row.querySelector('select'), order=null;
    if (sel && sel.selectedIndex>=0){
      var o=(sel.options[sel.selectedIndex].text||'').trim();
      if (o.length>1 && o.charAt(0)==='Q' && o.indexOf(' ')<0) order=o;
    }
    if (!order){
      var oc = cell('Order');
      if (oc && oc.indexOf(NL)<0 && oc.charAt(0)==='Q') order = oc;
    }
    var qid0=null, l0=row.querySelectorAll('a[href*="moddataset"]');
    for (var k=0;k<l0.length;k++){ if((l0[k].getAttribute('href')||'').indexOf('template=true')<0){ qid0=1; break; } }
    // a pool row inside a group has NO Order cell; its first cell is the Ungroup action
    if (!order && !qid0) continue;
    if (!order && qid0) order = null;
    var qid=null, links=row.querySelectorAll('a[href*="moddataset"]');
    for (var k=0;k<links.length;k++){
      var h=links[k].getAttribute('href')||'';
      if (h.indexOf('template=true')>=0) continue;
      var a=h.indexOf('id='); if(a<0) continue;
      var rest=h.slice(a+3), stop=rest.length;
      for (var j=0;j<rest.length;j++){var c=rest.charAt(j); if(c<'0'||c>'9'){stop=j;break;}}
      qid=rest.slice(0,stop); break;
    }
    var pts=null, pi=col['Points'];
    if (pi!==undefined && cs[pi]){
      var pin=cs[pi].querySelector('input');
      var pv = pin ? (pin.value||'').trim() : (cs[pi].innerText||'').trim();
      if (pv.length && !isNaN(parseFloat(pv))) pts=parseFloat(pv);
    }
    var rec={order:order, qid:qid, title:cell('Description'), points:pts, qtype:cell('Type'), features:cell('Features')};
    if (order && qid===null){ lastGroup = order; rec.group = true; }
    else if (!order){ rec.in_group = lastGroup; }
    out.push(rec);
  }
  return JSON.stringify({empty:false, rows:out, cols:col});
})()
