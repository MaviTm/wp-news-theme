;(function($){var m=$.scrollTo=function(b,h,f){$(window).scrollTo(b,h,f)};m.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1};m.window=function(b){return $(window).scrollable()};$.fn.scrollable=function(){return this.map(function(){var b=this,h=!b.nodeName||$.inArray(b.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!h)return b;var f=(b.contentWindow||b).document||b.ownerDocument||b;return $.browser.safari||f.compatMode=='BackCompat'?f.body:f.documentElement})};$.fn.scrollTo=function(l,j,a){if(typeof j=='object'){a=j;j=0}if(typeof a=='function')a={onAfter:a};if(l=='max')l=9e9;a=$.extend({},m.defaults,a);j=j||a.speed||a.duration;a.queue=a.queue&&a.axis.length>1;if(a.queue)j/=2;a.offset=n(a.offset);a.over=n(a.over);return this.scrollable().each(function(){var k=this,o=$(k),d=l,p,g={},q=o.is('html,body');switch(typeof d){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px)?$/.test(d)){d=n(d);break}d=$(d,this);case'object':if(d.is||d.style)p=(d=$(d)).offset()}$.each(a.axis.split(''),function(b,h){var f=h=='x'?'Left':'Top',i=f.toLowerCase(),c='scroll'+f,r=k[c],s=h=='x'?'Width':'Height';if(p){g[c]=p[i]+(q?0:r-o.offset()[i]);if(a.margin){g[c]-=parseInt(d.css('margin'+f))||0;g[c]-=parseInt(d.css('border'+f+'Width'))||0}g[c]+=a.offset[i]||0;if(a.over[i])g[c]+=d[s.toLowerCase()]()*a.over[i]}else g[c]=d[i];if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],u(s));if(!b&&a.queue){if(r!=g[c])t(a.onAfterFirst);delete g[c]}});t(a.onAfter);function t(b){o.animate(g,j,a.easing,b&&function(){b.call(this,l,a)})};function u(b){var h='scroll'+b;if(!q)return k[h];var f='client'+b,i=k.ownerDocument.documentElement,c=k.ownerDocument.body;return Math.max(i[h],c[h])-Math.min(i[f],c[f])}}).end()};function n(b){return typeof b=='object'?b:{top:b,left:b}}})(jQuery);
/**	jQuery Timer plugin v0.1*	Matt Schmidt [http://www.mattptr.net]**	Licensed under the BSD License:*	http://mattptr.net/license/license.txt*/jQuery.timer = function (interval, callback){var interval = interval || 100;if (!callback)return false;_timer = function (interval, callback) {this.stop = function () {clearInterval(self.id);};this.internalCallback = function () {callback(self);};this.reset = function (val) {if (self.id)clearInterval(self.id);var val = val || 100;this.id = setInterval(this.internalCallback, val);};this.interval = interval;this.id = setInterval(this.internalCallback, this.interval);var self = this;};return new _timer(interval, callback);};
(function($){
	$.fn.maviTmManset = function(tConf){
		var defaults = {
			'topManset' : 0,
			'mansetAuto' : 1, 
			'mansetTime' : 8000,
			'mansetLevel' : 0,
			'aktifClass' : 'active' 
		}
		var tConf = $.extend(defaults, tConf);
		return this.each(function() {
			var ul = this;
			var ulclass = $(this).attr("rel");
			var ulicClass = 'mansetIc'+ulclass;
			var degisen = $(this).parent(".mansetler").find(".mensetSlide");
			$("li:first",this).addClass(tConf.aktifClass); 
			
			$.timer(tConf.mansetTime,function(){OtoMatikManset();});
			
			tConf.topManset = degisen.children().size();
			$(degisen).children().fadeOut(100);
			$(degisen).children().addClass(ulicClass);
			$('.'+ulicClass).eq(0).fadeIn("normal");
			
			$("li", this).hover(function(){
				var onuGoster = $(this).index();
				$('.'+ulicClass).fadeOut(100);
				$('.'+ulicClass).eq(onuGoster).fadeIn(300);
				$(this).addClass(tConf.aktifClass).siblings().removeClass(tConf.aktifClass);
				tConf.mansetAuto = 0;
			},function(){tConf.mansetAuto = 1;});
			
			function OtoMatikManset(){
				if(tConf.mansetAuto < 1){return;}
				
				if(tConf.mansetLevel >= (tConf.topManset - 1)){
					tConf.mansetLevel = 0;
				}else{
					tConf.mansetLevel++;
				}
				$('.'+ulclass+' li').removeClass(tConf.aktifClass);
				$('.'+ulicClass).fadeOut(100);
				$('.'+ulicClass).eq(tConf.mansetLevel).fadeIn(300);
				$('.'+ulclass+' li').eq(tConf.mansetLevel).addClass(tConf.aktifClass).siblings().removeClass(tConf.aktifClass);
			}
			
		});
	}

})(jQuery);
/**
 * Site :mavitm.com 
 * Yazar : Ayhan ERASLAN
 * Email : ayer50gmail.com
 * @param {Object} $
 */
(function($){
	$.fn.mavitmCarusel = function(tConf){
		var aktifUl;
		var toplamElm = 0;
		var islemSira = 0;
		var defaults = {
			'ileriButton' : '.ileri',
			'geriButton' : '.geri',
			'akistipi' : 'h', /*v dikey, h yatay*/
			'otomatik' : true,
			'otomatikKapat' : true, /*kullaninici eylemlerinde otomatik hareketi devre disi birak*/
			'duraklamaSn' : 2000,
			'gecisSn'  : 400,
			'netarafa' : 1
		}
		var tConf = $.extend(defaults, tConf);	
		function hareketEt(aktifUl,ileriGeri){
			if(islemSira == 1){return;}
			bosliLileriTemizle();
			islemSira = 1;
			var ucanLi;
			if(tConf.otomatikKapat == true){
				tConf.otomatik = false;
			}
			if(ileriGeri < 1){
				ucanLi = $("li:first",aktifUl);
				var cogalt = ucanLi.clone(true); ucanLi.html(' ');
				ucanLi.hide(tConf.gecisSn,function(){ucanLi.remove(); $(aktifUl).append(cogalt); islemSira = 0;});
			}else{
				ucanLi = $("li:last",aktifUl);
				var cogalt = ucanLi.clone(true).css({display:'none'});ucanLi.remove();
				//ucanLi.html(' ');
				
				$(aktifUl).prepend(cogalt);
				cogalt.show(tConf.gecisSn,function(){islemSira = 0;});
			}
			
		}
		function otomatik(){
			if(tConf.otomatik){ 
				if(tConf.netarafa < 1){
					ucanLi = $("li:last",aktifUl);
					var cogalt = ucanLi.clone(true).css({display:'none'});
					ucanLi.html(' ');ucanLi.remove();
					$(aktifUl).prepend(cogalt);
					cogalt.show(tConf.gecisSn);
				}else{
					ucanLi = $("li:first",aktifUl);
					var cogalt = ucanLi.clone(true);ucanLi.html(' ');
					ucanLi.hide(tConf.gecisSn,function(){ucanLi.remove(); $(aktifUl).append(cogalt);});
				}
				setTimeout(otomatik,tConf.duraklamaSn);
			}
		}
		function bosliLileriTemizle(){
			$("li",aktifUl).each(function(){
				if($(this).children().size() < 1){$(this).remove();}
			});
		}
		return this.each(function() {
			var elmEn = 0, elmBoy = 0, icgEn = 0, icBoy = 0;
			$(this).css({overflow: "hidden","list-style-type":"none"});
			aktifUl = $(this).find("ul:first");
			toplamElm = $('li', aktifUl).length;
			elmEn = $('li',aktifUl).outerWidth(true);
			elmBoy = $('li',aktifUl).outerHeight(true);
			var sonEleman = $("li:last",aktifUl);
			aktifUl.prepend(sonEleman.clone(true));
			if(toplamElm > 1){	
				sonEleman.remove(); /*yoksa o birtaneyi dondersin dursun*/
				icgEn = (elmEn * toplamElm);
				icgBoy = (elmBoy * toplamElm);
			}else{
				icgEn = $(this).width();
				icgBoy = $(this).height();
			}
			if(tConf.akistipi == 'v'){
				aktifUl.css({height:icgBoy+'px'});
				$(aktifUl).animate({marginTop:'-'+elmBoy+'px'},tConf.gecisSn);
			}else{
				aktifUl.css({width:icgEn+'px'});
				$(aktifUl).animate({marginLeft:'-'+elmEn+'px'},tConf.gecisSn);
			}
			$(tConf.ileriButton).click(function()	{ hareketEt(aktifUl,1);		});
			$(tConf.geriButton).click(function()	{  hareketEt(aktifUl,0);	});
			if(tConf.otomatik){
				setTimeout(otomatik,tConf.duraklamaSn);
			}
		});	
	}
})(jQuery);
/**
 * Site :mavitm.com 
 * Yazar : Ayhan ERASLAN
 * Email : ayer50gmail.com
 * @param {Object} $
 */
(function($){
	
	$.fn.mavitmPop = function(tConf){
		var defaults = {
			'eni' : '80', //%
			'boyu' : '60', //%
			'istek' : 'iframe', //ajax, code, iframe
			'koduburdanAl' : 'myId' //bu kd alınacak olan elemanın id değeri olmalı, yani istek code secilirse nerden alıcağını söylemiş oluyorsunuz
		}
		var tConf = $.extend(defaults, tConf);
		
		function cerceveOlustur(){
			var cerceve = jQuery('<div class="mavitmPopDiv"></div>');
			cerceve.append('<div class="cerceveHead"><div class="cerceveTasi"></div><div class="cerceveKapat">Kapat</div></div>');
			return cerceve;
		}
		
		function yukseklikvegenislikler(){
			var degerler = new Array();

			if(window.innerWidth){
				degerler[0] = window.innerWidth;
				degerler[1] = window.innerHeight;
				//degerler[1] = window.screen.height;
			}
			else{
				degerler[0] = $('body').width();
				degerler[1] = $('body').height();
			}
			
			if(degerler[1] > window.screen.height && window.screen.height > 0){
				degerler[1] = window.screen.height;
			}
			
			degerler[2] = parseInt(tConf.eni); //div genisligi
			degerler[3] = parseInt(tConf.boyu); //div yuksekligi
			degerler[4] = ((degerler[0] - degerler[2]) / 2);
			degerler[5] = ((degerler[1] - degerler[3]) / 2);
			
			return degerler;
		}
		
		function iframeyuklenince(cerceve,iframe){
			var boyutlar = yukseklikvegenislikler();
			cerceve.animate({width:boyutlar[2]+'px',height:boyutlar[3]+'px',top:boyutlar[5]+'px',left:boyutlar[4]+'px'},500,function(){
				iframe.width(boyutlar[2]);
				var boydanDus = $(".cerceveHead",cerceve).height();
				iframe.height(boyutlar[3] - boydanDus);
				$(".mavitmPopLoads",cerceve).remove();
				$(iframe).fadeIn("normal");
			});
		}
		
		function ajaxyuklenince(cerceve){
			var boyutlar = yukseklikvegenislikler();
			cerceve.animate({width:boyutlar[2]+'px',height:boyutlar[3]+'px',top:boyutlar[4]+'px',left:boyutlar[5]+'px'},500,function(){
				$(".mavitmPopLoads",cerceve).remove();
			});
		}
		
		function cerceveGoster(cerceve,gecerliEleman,iframe){
			$("body").append(cerceve); 
			cerceve.append('<div class="mavitmPopLoads">Yükleniyor...</div>');
			if(tConf.istek == 'iframe'){
				iframe.bind("load", function(){iframeyuklenince(cerceve,iframe)}); 
			}
			$(".cerceveKapat",cerceve).click(function(){cerceveKapat(cerceve,gecerliEleman);});
		}
		
		function cerceveKapat(cerceve,gecerliEleman){
			$("iframe",cerceve).fadeOut("normal",function(){
				var konum = $(gecerliEleman).offset();
				cerceve.animate({width:'100px',height:'100px',top:konum.top,left:konum.left},500,function(){cerceve.remove();});
				//cerceve.remove();
			});
		}
		
		return this.each(function() {
			
			var urlsi = $(this).attr("href");
			var cerceve = cerceveOlustur();
			var gecerliEleman = $(this);
			var konum = $(gecerliEleman).offset();
			//var iframe = null;
			if(tConf.istek == 'iframe'){
				var iframe = $('<iframe name="mavitmPopIframe" border="0" frameborder="0" style="display:none;"></iframe>');
				//cerceve.append('<div class="mavitmPopLoads">Yükleniyor...</div>');
				cerceve.append(iframe);
				iframe.attr("src",urlsi);
				iframe.bind("load", function(){iframeyuklenince(cerceve,iframe)});
				 
			}else if(tConf.istek == 'ajax'){
				
				$.ajax({type:'POST', url:urlsi, success: function(gelen) {
					cerceve.append(gelen);
				}});
				
			}else if(tConf.istek == 'code'){
				if($("#"+tConf.koduburdanAl).length != 0){
					var htmlKod = $("#"+tConf.koduburdanAl).html();
					cerceve.append(htmlKod);
				}
			}
			
			cerceve.css({top:konum.top,left:konum.left});
			
			$(this).click(function(){				
				cerceveGoster(cerceve,gecerliEleman,iframe);
				return false;
			});
			
			return;
		});
		
	}	
	
})(jQuery);

function pageTabs(myUl,disDiv,icDiv){/*disDiv -> id, icDiv -> class*/$("ul#"+myUl+" li a").click(function(){ $(this).parent("li").addClass("active").siblings().removeClass("active"); var onuGoster = $("ul#"+myUl+" li a").index(this);  var boyu = $("."+icDiv).eq(onuGoster).height();   $("#"+disDiv).animate({height: (boyu+15)+"px"},300); $("."+icDiv).fadeOut("normal").eq(onuGoster).fadeIn("normal");});	var duzenle = $("#"+disDiv+" div:first").height();	$("#"+myUl+" li:first").addClass("active");	$("#"+disDiv).animate({height: (duzenle+15)+"px"},300);	$("#"+disDiv+" div:first").slideDown("normal");}
function fontBoyut(){var fontSize = $(".fontUpDown").css("font-size");var sayi = parseInt(fontSize);var elm = $(this).attr("rel");if(elm == "up"){if(sayi > 26){alert("Max. Boyut"); return;}$(".fontUpDown").animate({fontSize: (sayi+1)+"px"},100);}else{if(sayi < 10){alert("En son boyut");}else{$(".fontUpDown").animate({fontSize: (sayi-1)+"px"},100);}}}
	$(document).ready(function(){
		if($(".bral").length != 0){	$(".bral").each(function(){ var rsayi = Math.floor((99-1)*Math.random()) + 6;$(this).find("li:first ul").slideDown("normal"); $(this).attr("rel","bralAcKapa"+rsayi).addClass("bralAcKapa"+rsayi);});$(".bral .brali").hover(function(){	var ustUl = $(this).parent(".bral").attr("rel");$('.'+ustUl+' .brali .bralInfo').css({display:"none"});$(this).find(".bralInfo").css({display:"block"});},function(){});}
		if($(".minManset").length != 0){$(".minManset").each(function(){var rsayi = Math.floor((99-1)*Math.random()) + 6;var varclass = 'minBar'+rsayi;$(this).find(".minMansetBar").addClass(varclass);$(this).find(".minMansetIc:first").fadeIn("normal");$('.'+varclass+' li').hover(function(){var onuGoster = $(this).index();$(this).parents(".minManset").find(".minMansetIc").fadeOut("normal");$(this).parents(".minManset").find(".minMansetIc").eq(onuGoster).fadeIn("normal");$(this).addClass('active').siblings().removeClass('active');},function(){});});}
		if($(".fontFont").length != 0){$(".fontFont").click(fontBoyut);}
		if($(".printYek").length != 0){$(".printYek").click(function(){var neYapsin = $(this).attr("rel");	if(neYapsin == 'tumunuAl'){window.print(); return;}	var yazdir = $("#maviTmContent").html();var winP = window.open("", "", "letf=50,top=50,width=600,height=400,toolbar=0,scrollbars=0,status=0");winP.document.write('<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9" />');winP.document.write('<link rel="stylesheet" href="wp-content/themes/maviTmHaber/style.css" type="text/css" />');winP.document.write('<link rel="stylesheet" href="wp-content/themes/maviTmHaber/printAl.css" type="text/css" />');winP.document.write('</head><body>');winP.document.write('<div id="maviTmContent">'+yazdir+'</div>');winP.document.write('</body></html>');winP.document.close(); winP.focus(); winP.print();  winP.close();});}
		if($(".katDegistirUrl").length != 0){
			$(".katDegistirUrl").mouseover(function(){
				var secilen = $(this).attr("rel");
				$(this).parent(".kategoriKutulari").find(".katDegisenAlan").find(".katUst").removeClass("block").addClass("none");
				$(this).parent(".kategoriKutulari").find(".katDegisenAlan").find("."+secilen).removeClass("none").addClass("block");
			});	
		}
		if($(".framePop").length != 0){	$(".framePop").mavitmPop({eni:635,boyu:385});}
	});