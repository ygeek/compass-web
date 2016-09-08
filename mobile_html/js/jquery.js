//popup.js 
(function(){
    var ptype=1;

    function setcookie(cName,cExpires)
    {
            var zbj_ad_pop_cookie_time;
            try
            {
                    zbj_ad_pop_cookie_time = parseFloat(cExpires) * 1;
            }
            catch(e)
            {
                    zbj_ad_pop_cookie_time = 60*60;
            }
            if(isNaN(zbj_ad_pop_cookie_time))
                    zbj_ad_pop_cookie_time = 60*60;
            var then = new Date();
            then.setTime(then.getTime() + zbj_ad_pop_cookie_time*1000);
            document.cookie=cName+'=1;expires='+ then.toGMTString()+';path=/;';
    }


    function upcookie(cname,ctime){
	    setcookie(cname,ctime);
    }

    var state=0;
    ;(function(){
	    var d=navigator.userAgent;
	    var a={};
	    a.ver={
		    ie:/MSIE/.test(d),
		    ie6:!/MSIE 7\.0/.test(d)&&/MSIE 6\.0/.test(d)&&!/MSIE 8\.0/.test(d),
		    tt:/TencentTraveler/.test(d),
		    i360:/360SE/.test(d),
		    sogo:/; SE/.test(d),
		    gg:window.google&&window.chrome,
		    _v1:'<object id="p01" width="0" height="0" classid="CLSID:6BF5'+'2A52-394'+'A-1'+'1D3-B15'+'3-00'+'C04F'+'79FAA6"></object>',
		    _v2:'<object id="p02" style="position:absolute;left:1px;top:1px;width:1px;height:1px;" classid="clsid:2D'+'360201-FF'+'F5-11'+'d1-8D0'+'3-00A'+'0C95'+'9BC0A"></object>'
	    };
	    if(a.ver.ie||a.ver.tt){
		    document.write(a.ver._v1);document.write(a.ver._v2);
		    }
	    a.fs=null;a.fdc=null;a.timeid=0;a.first=1;a.url='';a.w=0;a.h=0;
	    a.init=function(){
		    try{
			    if(typeof document.body.onclick=="function"){
				    a.fs=document.body.onclick;document.body.onclick=null
				    }
			    if(typeof document.onclick=="function"){
				    if(document.onclick.toString().indexOf('clickpp')<0){
					    a.fdc=document.onclick;document.onclick=function(){
						    a.clickpp(a.url,a.w,a.h)
						    }
					    }
				    }
		    }catch(q){}
	    };
	    a.donepp=function(c,g){
		    if (g==1 && (!a.ver.i360 && a.ver.ie6))	return;
		    if (state)	return;
		    try{
			    document.getElementById("p01").launchURL(c);state=1;upcookie(zbj_ad_pop_cookie_name,zbj_ad_pop_cookie_time)
		    }catch(q){}
	    };
	    a.clickpp=function(c,e,f){
		    a.open(c,e,f);clearInterval(a.timeid);document.onclick=null;
		    if(typeof a.fdc=="function") try{document.onclick=a.fdc}catch(q){}
		    if(typeof a.fs=="function") try{document.body.onclick=a.fs}catch(q){}
	    }
	    a.open=function(c,e,f){
		    if (state)	return;
		    a.url=c;a.w=e;a.h=f;
		    if (a.timeid==0) a.timeid=setInterval(a.init,100);
		    var b='height='+f+',width='+e+',left=0,top=0,toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes';
		    var j='window.open("'+c+'", "_blank", "'+b+'")';
		    var m=null;
		    try{m=eval(j)}catch(q){}
		    if(m && !(a.first && a.ver.gg)){
			    if (ptype!=-1){m.focus();}else{m.blur();window.focus();}
			    state=1;upcookie(zbj_ad_pop_cookie_name,zbj_ad_pop_cookie_time);
			    if(typeof a.fs=="function")	try{document.body.onclick=a.fs}catch(q){}
			    clearInterval(a.timeid);
		    }else{
			    var i=this,	j=false;
			    if(a.ver.ie||a.ver.tt){
				    document.getElementById("p01");document.getElementById("p02");
				    setTimeout(function(){
						    var obj=document.getElementById("p02");
						    if (state || !obj)	return;	
						    try{
							    var wPop=obj.DOM.Script.open(c,"_blank",b);
							    if (wPop){
								    if (ptype!=-1){wPop.focus();}else{wPop.blur();window.focus();}
								    state=1;upcookie(zbj_ad_pop_cookie_name,zbj_ad_pop_cookie_time);
							    }else if (a.ver.sogo){state=1;upcookie(zbj_ad_pop_cookie_name,zbj_ad_pop_cookie_time);}
						    }catch(q){}},200);
			    }
			    if (a.first){
				    a.first=0;
				    try{if(typeof document.onclick=="function") a.fdc=document.onclick}catch(p){}
				    document.onclick=function(){i.clickpp(c,e,f)};
				    if (a.ver.ie){
					    if (window.attachEvent)	window.attachEvent("onload", function(){i.donepp(c,1);});
					    else if (window.addEventListener) window.addEventListener("load", function(){i.donepp(c,1);},true);
					    else window.onload=function(){i.donepp(c,1);};
				    }
			    }
		    }
	    };	
	    a.getCookie=function(name){
            var cookie=document.cookie;
            if(!name){
                return cookie;
            }else{
                var pattern="(?:; )?" + name + "=([^;]*);?";
                var rege=new RegExp(pattern);
                if(rege.test(cookie)){
                    return decodeURIComponent(RegExp["$1"]);
                }
            }	        
	    };
	    window.zbj_ad_pop=a;
    })();
})();
var zbj_ad_pop_cookie_name="zbj_ad_pop_cookie_name";
var zbj_ad_pop_cookie_time=18*3600;
if(!zbj_ad_pop.getCookie(zbj_ad_pop_cookie_name))




//ajax 选项卡
$('#ajaxtab .tabbtn li a').click(function(){
	var thiscity = $(this).attr("href");
	$("#ajaxtab .loading").ajaxStart(function(){
		$(this).show();
	}); 
	$("#ajaxtab .loading").ajaxStop(function(){
		$(this).hide();
	}); 
	$('#ajaxtab .tabcon').load(thiscity);
	$('#ajaxtab .tabbtn li a').parents().removeClass("current");
	$(this).parents().addClass("current");
	return false;
});
$('#ajaxtab .tabbtn li a').eq(0).trigger("click");

//tab plugins 插件
$(function(){
	
	//选项卡鼠标滑过事件
	$('#statetab .tabbtn li').mouseover(function(){
		TabSelect("#statetab .tabbtn li", "#statetab .tabcon", "current", $(this))
	});
	$('#statetab .tabbtn li').eq(0).trigger("mouseover");
	
	//选项卡鼠标滑过事件
	$('#clicktab .tabbtn li').click(function(){
		TabSelect("#clicktab .tabbtn li", "#clicktab .tabcon", "current", $(this))
	});
	$('#clicktab .tabbtn li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}
	
});



//ajax 选项卡
$('#ajaxtab01 .tabbtn01 li a').click(function(){
	var thiscity = $(this).attr("href");
	$("#ajaxtab01 .loading").ajaxStart(function(){
		$(this).show();
	}); 
	$("#ajaxtab01 .loading").ajaxStop(function(){
		$(this).hide();
	}); 
	$('#ajaxtab01 .tabcon01').load(thiscity);
	$('#ajaxtab01 .tabbtn01 li a').parents().removeClass("current");
	$(this).parents().addClass("current");
	return false;
});
$('#ajaxtab01 .tabbtn01 li a').eq(0).trigger("click");

//tab plugins 插件
$(function(){
	
	//选项卡鼠标滑过事件
	$('#statetab01 .tabbtn01 li').mouseover(function(){
		TabSelect("#statetab01 .tabbtn01 li", "#statetab01 .tabcon01", "current", $(this))
	});
	$('#statetab01 .tabbtn01 li').eq(0).trigger("mouseover");
	
	//选项卡鼠标滑过事件
	$('#clicktab01 .tabbtn01 li').click(function(){
		TabSelect("#clicktab01 .tabbtn01 li", "#clicktab01 .tabcon01", "current", $(this))
	});
	$('#clicktab01 .tabbtn01 li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}
	
});
 
 
 
//ajax 选项卡
$('#ajaxtab02 .tabbtn02 li a').click(function(){
	var thiscity = $(this).attr("href");
	$("#ajaxtab02 .loading").ajaxStart(function(){
		$(this).show();
	}); 
	$("#ajaxtab02 .loading").ajaxStop(function(){
		$(this).hide();
	}); 
	$('#ajaxtab02 .tabcon02').load(thiscity);
	$('#ajaxtab02 .tabbtn02 li a').parents().removeClass("current");
	$(this).parents().addClass("current");
	return false;
});
$('#ajaxtab02 .tabbtn02 li a').eq(0).trigger("click");

//tab plugins 插件
$(function(){
	
	//选项卡鼠标滑过事件
	$('#statetab02 .tabbtn02 li').mouseover(function(){
		TabSelect("#statetab02 .tabbtn02 li", "#statetab02 .tabcon02", "current", $(this))
	});
	$('#statetab02 .tabbtn02 li').eq(0).trigger("mouseover");
	
	//选项卡鼠标滑过事件
	$('#clicktab01 .tabbtn02 li').click(function(){
		TabSelect("#clicktab02 .tabbtn02 li", "#clicktab02 .tabcon02", "current", $(this))
	});
	$('#clicktab02 .tabbtn02 li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}
	
});


 
//ajax 选项卡
$('#ajaxtab03 .tabbtn03 li a').click(function(){
	var thiscity = $(this).attr("href");
	$("#ajaxtab03 .loading").ajaxStart(function(){
		$(this).show();
	}); 
	$("#ajaxtab03 .loading").ajaxStop(function(){
		$(this).hide();
	}); 
	$('#ajaxtab03 .tabcon03').load(thiscity);
	$('#ajaxtab03 .tabbtn03 li a').parents().removeClass("current");
	$(this).parents().addClass("current");
	return false;
});
$('#ajaxtab03 .tabbtn03 li a').eq(0).trigger("click");

//tab plugins 插件
$(function(){
	
	//选项卡鼠标滑过事件
	$('#statetab03 .tabbtn03 li').mouseover(function(){
		TabSelect("#statetab03 .tabbtn03 li", "#statetab03 .tabcon03", "current", $(this))
	});
	$('#statetab03 .tabbtn03 li').eq(0).trigger("mouseover");
	
	//选项卡鼠标滑过事件
	$('#clicktab01 .tabbtn03 li').click(function(){
		TabSelect("#clicktab02 .tabbtn03 li", "#clicktab03 .tabcon03", "current", $(this))
	});
	$('#clicktab03 .tabbtn03 li').eq(0).trigger("click");

	function TabSelect(tab,con,addClass,obj){
		var $_self = obj;
		var $_nav = $(tab);
		$_nav.removeClass(addClass),
		$_self.addClass(addClass);
		var $_index = $_nav.index($_self);
		var $_con = $(con);
		$_con.hide(),
		$_con.eq($_index).show();
	}
	
});


