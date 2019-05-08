var opacityStepValue = 0.05;
var opacityStepTime = 1;
var minOpacity = 0.7;
var maxOpacity = 1;
var borderHoverColor = '#006699';
var borderWhiteColor = '#eeeeee';


var isIE = /(msie|internet explorer)/i.test(navigator.userAgent);

var Gallery = {};

Gallery.currentTarget = null;

Gallery.onItemHover = function(event, tooltipText) {
	Gallery.currentTarget = event.target != undefined ? event.target : event.srcElement;
	if (Gallery.currentTarget == null) return;
	var interval;
	Gallery.currentTarget.onmouseout = function() {
		clearInterval(interval);
		interval = Gallery.onItemOut();
	}
	clearInterval(interval);
	interval = highlight(Gallery.currentTarget);
};

Gallery.onItemOut = function() {
	if (Gallery.currentTarget == null) return;
	return unhighlight(Gallery.currentTarget);
};

function setLink(link) {
	var rel = String(link.rel);
	if (rel == null || rel.length == 0) return;
	
	var relInfo = String(rel).split(';');
	if (relInfo.length != 4) return;

	var path = link.href;
	
	var tooltipText = relInfo[0];
	var popupTitle = relInfo[1];
	var popupWitdh = relInfo[2];
	var popupHeight = relInfo[3];
	
	link.onclick = function(event) {
		if (window.event) {
			window.event.returnValue = false;
			window.event.cancelBubble = true;
		}else if (event) {
			event.stopPropagation();
			event.preventDefault();
		}
		Gallery.showChart(path, popupTitle, popupWitdh, popupHeight);
	};
}

Gallery.initItem = function(target) {
	
	if (target == null) return;
	
	var image;
	
	var images = target.getElementsByTagName('img');
	if (images.length != 1) return;
	image = images[0];
	
	var newImage = new Image();
	
	newImage.onload = function() {
		
		target.style.background = "none";
		image.src = newImage.src;
		image.style.display = "block";
		image.style.marginLeft = "auto";
		image.style.marginRight = "auto";
		
		setOpacity(image,minOpacity);
		
		var link = target.getElementsByTagName('a')[0];
		
		setLink(link);
		
		link.onmouseover = function(event) {
			Gallery.onItemHover(event != undefined ? event : window.event, "");
		};

		var descriptionLinks = target.parentNode.parentNode.getElementsByTagName("a");
		for (var i = 0;i<descriptionLinks.length;i++) {			
			if (descriptionLinks[i].onclick == undefined) {
				setLink(descriptionLinks[i]);
			}
		}

		this.onLoad = function(){};
		//stack overflow IE bugfix
		if (isIE) 
			setTimeout(Gallery.initNextItem, 1);
		else 
			Gallery.initNextItem();
	}
	
	newImage.src = image.getAttribute("rel");
	
	newImage.width = image.width;
	newImage.height = image.height;
};

Gallery.items = null;
Gallery.index = 0;

Gallery.initNextItem = function() {
	Gallery.index ++;
	if (Gallery.index < Gallery.items.length) {
		try {
			Gallery.initItem(Gallery.items[Gallery.index]);
		}catch (e) {}
	}
}

Gallery.init = function() {
	
	var tmp = new Image();
	tmp.onload = function() {
		Gallery.items = new Array();
		var items = document.getElementsByTagName('div');
		for (var i = 0;i<items.length;i++) {
			var item = items[i];
			if (item.getAttribute('name') == 'gallerySampleItem')
				Gallery.items.push(item);
		}
		Gallery.initItem(Gallery.items[Gallery.index]);
		
		
		tmp.onload = function() {};
	}
	tmp.src = './../../img/loading.gif';
};

Gallery.windowPreDefinedParams = "scrollbars=1,resizable=1";

Gallery.showChart = function(path, name, width, height) {
	name = 'Sample';
	var left = (screen.width) ? (screen.width - width)/2 : 0;
	var top = (screen.height) ? (screen.height - height)/2 : 0;
	
	var params = Gallery.windowPreDefinedParams + ',width='+width+',height='+height+',left='+left+',top='+top;
	var w = window.open(path,name,params);
	return null;
};

//gallery


function setOpacity(target, value) {
	if (isIE) {
		target.style.filter = "alpha(opacity:"+(value*100)+")";
	}else {
		try {
		target.style.MozOpacity = value;
		}catch (e) {
			target.style.opacity = value;
		}
	}
};

function getCurrentOpacity(target) {
	if (isIE) {
		var opacityStr = String(target.style.filter);
		//e.g. alpha(opacity:...)
		var indexOfAlpha = opacityStr.indexOf('alpha');
		if (indexOfAlpha == -1) return 1;
		var indexOfOpacity = opacityStr.indexOf('opacity:', indexOfAlpha);
		var endIndex = opacityStr.indexOf(')',indexOfOpacity);
		return Number(opacityStr.substring(indexOfOpacity+8,endIndex))/100;
	}else {
		try {
			return Number(target.style.opacity);
		}catch (e) {
			try {
				return Number(target.style.MozOpacity);				
			}catch (e) { };
		}
	}
	return 1;
};

function highlight(target) {
	var opacity = getCurrentOpacity(target);
	setOpacity(target, opacity);
	target.parentNode.parentNode.style.borderColor = borderHoverColor;
	var animator = setInterval(function() {
		opacity += opacityStepValue;
		setOpacity(target, opacity);
		if (opacity >= maxOpacity) clearInterval(animator);
	},opacityStepTime);
	return animator;
};

function unhighlight(target) {
	var opacity = getCurrentOpacity(target);
	if (opacity <= minOpacity) {
		target.parentNode.parentNode.style.borderColor = borderWhiteColor;
		setOpacity(target, minOpacity);
		return;
	}
	var animator = setInterval(function() {
		opacity -= opacityStepValue;
		setOpacity(target, opacity);
		if (opacity <= minOpacity) {
			target.parentNode.parentNode.style.borderColor = borderWhiteColor;
			clearInterval(animator); 
		}
	},opacityStepTime);
	return animator;
}