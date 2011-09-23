var pmsearch = (function(){

function cE(e){return document.createElement(e);}

$(function(){
    list = [];
    $.get('search/ajax',load_all);
    $('#queue').slideUp();
    $('#queuelink').click(function(){$('#queue').slideToggle();});
});

var filter = 'all';

var list = [];

var last = [];

var that = {};

var queue = that.queue = [];

function loadqueue(){
    var ret = [];
    $("#plugin-manager-search-form input[type=hidden][value=true]").each(function(i,x){ret.push(x.name);});
    return ret;
}

function load_all(x){
    var lines = x.split(/\n/g);
    var _queue = loadqueue();
    for (var i=0;i<lines.length;i++){
        if (!lines[i])continue;
        var als = lines[i].split(':',3);
        list.push(lines[i].split(':',3));
        if (_queue.indexOf(als[0])!=-1){
            var tr = cE('tr');
            var b = tr.appendChild(cE('td')).appendChild(cE('button'));
           (function(b,n){
            b.onclick = function(){that.unqueue(b,n);};
            b.innerHTML = 'Remove';
           })(b,als[0]);
            tr.appendChild(cE('td')).innerHTML = als[1];
            tr.appendChild(cE('td')).innerHTML = als[2];
            var a = tr.appendChild(cE('td')).appendChild(cE('a'));
            a.target = '_blank';
            a.href = 'http://drupal.org/project/'+als[0];
            a.innerHTML = 'Project Page';
            $('#queue')[0].appendChild(tr);
            queue.push(als[0]);
        }
    }
    if (queue.length){
        $('#queue').slideDown();
    }
    $('#queuelink').html('Install Queue ('+queue.length+')');
    
    $('#sinput').keyup(reload);
    $('#search').click(reload);
    $('#install').click(function(){$('#plugin-manager-search-form')[0].submit();});
    $('#showall').click(function(){show('all')});
    $('#showmodules').click(function(){show('modules')});
    $('#showthemes').click(function(){show('themes')});
    
    that.list = list;
    that.lines = lines;
}

if (![].indexOf){
    Array.prototype.indexOf = function(x){for (var i=0;i<this.length;i++){if (this[i]==x)return i;}}
}

function show(what){
    $('#show'+filter).removeClass('selected');
    filter = what;
    $('#show'+filter).addClass('selected');
    load(last);
}

function makelist(x){
    $('#res').html('loaded');
    list = eval(x);
    $('#sinput').keyup(reload);
    $('#search').click(reload);
}

function reload(){
    var terms = $('#sinput')[0].value
    if (!terms)return;
    var projects = search(terms).slice(0,50);
    load(projects);
}

function load(projects){
    last = projects;
    var start = new Date().getTime();
    var text = '<tr><th>Queue</th><th>Name</th><th>Tags</th><th>Project Page</th></tr>';
    for (var i=0;i<projects.length;i++){
        if (queue.indexOf(projects[i][0])!=-1)continue;
        if (filter!='all' && projects[i][2].toLowerCase().indexOf(filter)==-1)continue;
        text += "<tr><td><button onclick='pmsearch.enqueue(this,\""+projects[i][0]+"\")'>Add</button></td><td>"+projects[i][1]+"</td><td>"+projects[i][2]+"</td><td><a target='_blank' href='http://drupal.org/project/"+projects[i][0]+"'>Project Page</a></td></tr>";//<td>"+projects[i][2]+"</td>
    }
    $('table.pmsearch').html(text);
    var time = new Date().getTime()-start;
}

that.showtag = function(tag){
    var res = [];
    tag = tag.toLowerCase();
    for (var i=0;i<list.length;i++){
        if (list[i].length<3)continue;
        if (list[i][2].toLowerCase().indexOf(tag)!=-1)res.push(list[i]);
    }
    load(res);
}

that.showletter = function(l){
    var res = [];
    l = l.toLowerCase();
    for (var i=0;i<list.length;i++){
        if (list[i][1][0].toLowerCase()==l)res.push(list[i]);
    }
    load(res);
}

var search = that.search = function(what){
    if (!what)return [];
    var res = [];
    if (typeof(what)=='string')what = what.toLowerCase();
    for (var i=0;i<list.length;i++){
        if (list[i][1].toLowerCase().search(what)!=-1)res.push(list[i]);
    }
    return res;
}

that.enqueue = function(which,name){
    queue.push(name);
    var ni = $('#plugin-manager-search-form')[0].appendChild(cE("input"));
    ni.type='hidden';
    ni.name=name;
    ni.value='true';
    $('#queue')[0].appendChild(which.parentNode.parentNode);
    which.innerHTML = "Remove";
    which.onclick = function(){that.unqueue(which,name);}
    $('#install').attr('disabled',false);
    $('#queuelink').html('Install Queue ('+queue.length+')');
}

that.unqueue = function(which,name){
    if (queue.indexOf(name)==-1)return;
    $('#queue')[0].removeChild(which.parentNode.parentNode);
    queue.splice(queue.indexOf(name),1);
    var node = $('#plugin-manager-search-form')[0][name];
    node.parentNode.removeChild(node);
    $('#queuelink').html('Install Queue ('+queue.length+')');
}


return that;})();

