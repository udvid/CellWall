 // JavaScript Document
/*$(document).ready(function() {
	$("#welcomeBar").on("click",function() {
        $(this).css("background-color","#3CC");
    });
});
$(document).ready(function() {
	$(window).scroll(function() {
        if($(window).scrollTop() > 10){
			$("#welcomeBar").animate({height: "0"},500);
		}
    });
});*/

// URL MANAGER CLASS
var UrlManager = function () {
        this.urlBits = "";
}
	
UrlManager.prototype.parseUrl = function () {
	var path = window.location.pathname;
	path = path.substr(1, path.length - 1);
	if (path.substr(path.length - 1,1) === "/") {
		path = path.substr(0, path.length - 1);
	}
	this.urlBits = path.split("/");
},
UrlManager.prototype.getUrlBit = function (key) {
	return this.urlBits[key];
}
UrlManager.prototype.countUrlBits = function () {
	return this.urlBits.length;
}

// REGISTRY CLASS
var Registry = function () {
	this.objects = [];
}
Registry.prototype.storeObject = function (object, key) {
	this.objects[key] = new window[object]();
},
Registry.prototype.getObject = function (key) {
	return this.objects[key];
}

// APPLICATION CLASS :: COMMON TASKS USED BY THE WEB APPLICATION
var Application = function(){}
Application.prototype.createErrorMsg = function(elem,msg){
	$(elem).html("<span class='errorMsg mf-B'>" + msg + "</span>");
},
Application.prototype.createSuccessMsg = function(elem,msg){
	$(elem).html("<span class='successMsg mf-B'>" + msg + "</span>");
},
Application.prototype.emptyMsg = function(elem){
	setTimeout(function(){
		$(elem).html("");
	},3000);
},
Application.prototype.reloadPage = function(){
	setTimeout(function(){
		location.reload();
	},3000);
},
Application.prototype.redirect = function(url){
	setTimeout(function(){
		window.location = url;
	},3000);
},
Application.prototype.disableInput = function(elem){
	$(elem).prop('disabled', true);
},
Application.prototype.enableInput = function(elem){
	$(elem).prop('disabled', false);
},
Application.prototype.emptyInput = function(elem){
	$(elem).val("");
},
Application.prototype.escapeHTML = function(str){
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};
	return str.replace(/[&<>"']/g, function(m) { return map[m]; });
},
Application.prototype.currentTime = function(){
	var date = new Date();
	var d = date.getDate();
	var monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
	var dayNames = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var m = monthNames[date.getMonth()].substring(0,3);
	var y = date.getFullYear();
	var dy = dayNames[date.getDay()].substring(0,3);
	var mn = date.getMinutes();
	if(mn<10) {mn='0' + mn}
	var h = date.getHours();
	var mrd = "";
	if(h==0) {
		h=12;
		mrd = ' am';
	}
	else if(h<10) {
		h='0' + h;
		mrd = ' am';
	}
	else if(h<12) {
		mrd = ' am';
	}
	else if(h==12) {
		mrd = ' pm';
	}
	else if(h>12 && h<22) {
	h='0'+(h-12);
	mrd = ' pm';
	}
	else if(h>=22) {
	h=h-12;
	mrd = ' pm';
	}
	var format = d + ' ' + m + ' ' + y + ', ' + h + ':' + mn;
	var time = format + mrd;
	return time;
},
Application.prototype.createCookie = function(name, value, expire, path){
},
Application.prototype.checkCookie = function(name){
},
Application.prototype.readCookie = function(name){
},
Application.prototype.updateCookie = function(name, value, expire, path){
},
Application.prototype.deleteCookie = function(name){
},
Application.prototype.placeCaretAtEnd = function(elem){
	if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
		var range = document.createRange();
		range.selectNodeContents(elem);
		range.collapse(false);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
	}
	else if (typeof document.body.createTextRange != "undefined") {
		var textRange = document.body.createTextRange();
		textRange.moveToElementText(elem);
		textRange.collapse(false);
		textRange.select();
	}
},
Application.prototype.configAce = function(editor,theme,mode,fontSize,wrapping,behaviour,highlightActiveLine,useWorker){
	editor.setTheme("ace/theme/" + theme);
    editor.getSession().setMode("ace/mode/" + mode);
	document.getElementById(editor).style.fontSize=fontSize;
	editor.getSession().setUseWrapMode(wrapping);
	editor.setBehavioursEnabled(behaviour);
	editor.setHighlightActiveLine(highlightActiveLine);
	editor.getSession().setUseWorker(useWorker);
}

// MODAL CLASS
var Modal = function(id){
	this.id = "#"+id;
	this.modal = $(this.id);
	this.wall = $(this.id + "-Wall");
	this.title = $(this.id + "-Title");
	this.content = $(this.id + "-Content");
	this.footer = $(this.id + "-Footer");
}
Modal.prototype = {
	build : function(titleText,contentHTML,footerHTML,empty){
		if(typeof empty == "undefined"){
			this.empty();
		}
		this.title.html(titleText);
		this.content.html(contentHTML);
		this.footer.html(footerHTML);
		this.show();
	},
	buildFromURL : function(titleText,url,footerHTML){
		this.title.html(titleText);
		this.content.load(url);
		this.footer.html(footerHTML);
		this.show();
	},
	empty : function(){
		this.title.html("");
		this.content.html("");
		this.footer.html("");
	},
	show : function(){
		this.modal.show();
		this.wall.show();
		$("body").css("overflow","hidden");
	},
	hide : function(){
		this.modal.hide();
		this.wall.hide();
		$("body").css("overflow","auto");
	},
	destroy : function(){
		this.empty();
		this.hide();
	}
}

// COMMON CONFIGURATIONS
var Config = {
	IMAGE_PATH: "/Assets/Images/",
	CSS_PATH: "/Assets/Stylesheets/",
	JS_PATH: "/Assets/Javascripts/",
}

// INITIALIZE REGISTRY OBJECTS
var registry = new Registry();
registry.storeObject("UrlManager", "urlmanager");
registry.storeObject("Application", "app");
registry.getObject("urlmanager").parseUrl();

// GENERAL FUNCTIONS
$(document).ready(function() {
	$(document).on("click",".actionBtn",function(e){
		e.preventDefault();
		var ref = $(this);
		var subject = $(this).attr('data-subject');
		var action = $(this).attr('data-action');
		var controller = new window[subject + 'Action'](ref);
		controller[action]();
	});
});
$(document).ready(function() {
	$(document).mouseup(function (e) {
		var container = $(".clickHider");
	
		if (!container.is(e.target) && container.has(e.target).length === 0){
			container.hide();
		}
	});
});
$(document).ready(function() {
	$(document).mouseup(function (e){
		var container = $(".modal");	
		if (!container.is(e.target) && container.has(e.target).length === 0){
			container.hide();
			$(".modalWall").hide();
		}
	});
});


/*TAB SYSTEM*/
/*(C) Kuhira.*/
$(document).ready(function() {
    $(".yTabBtn").on("click",function(e){
		e.preventDefault();
		var yActiveTabBtn = this;
		var activeTab = $(this).attr("href");
		var tabBtnHolder = $(this).parent("div.tabBtnHolder");
		var tabContent = $(this).parent("div.tabBtnHolder").next("div.tabContent");
		$(yActiveTabBtn).removeClass("yInactiveTabBtn");
		$(yActiveTabBtn).addClass("yActiveTabBtn");
		$(activeTab).removeClass("inactiveTab");
		$(activeTab).addClass("activeTab");
		tabBtnHolder.find('a.yTabBtn').not($(yActiveTabBtn)).removeClass("yActiveTabBtn");
		tabBtnHolder.find('a.yTabBtn').not($(yActiveTabBtn)).addClass("yInactiveTabBtn");
		tabContent.find('div.tab').not($(activeTab)).removeClass("activeTab");
		tabContent.find('div.tab').not($(activeTab)).addClass("inactiveTab");
	});
});
$(document).ready(function() {
    $(document).on("click",".tabBtn",function(e){
		e.preventDefault();
		var activeTabBtn = $(this);
		var activeTab = $($(this).attr("href"));
		var tabBtnContainer = $(this).parent(".tabBtnContainer");
		var tabContainer = $("#"+$(this).parent(".tabBtnContainer").attr("data-target"));
		activeTabBtn.removeClass("inactiveTabBtn");
		activeTabBtn.addClass("activeTabBtn");
		activeTab.removeClass("inactiveTab");
		activeTab.addClass("activeTab");
		tabBtnContainer.find(".tabBtn").not(activeTabBtn).removeClass("activeTabBtn");
		tabBtnContainer.find(".tabBtn").not(activeTabBtn).addClass("inactiveTabBtn");
		tabContainer.find(".tab").not(activeTab).removeClass("activeTab");
		tabContainer.find(".tab").not(activeTab).addClass("inactiveTab");
	});
});

/*SLIDEDOWN*/
