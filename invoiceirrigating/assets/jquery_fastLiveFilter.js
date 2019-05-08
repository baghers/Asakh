From: "Saved by Windows Internet Explorer 10"
Subject: 
Date: Wed, 11 Dec 2013 16:00:51 +0330
MIME-Version: 1.0
Content-Type: text/html;
	charset="utf-8"
Content-Transfer-Encoding: quoted-printable
Content-Location: https://raw.github.com/awbush/jquery-fastLiveFilter/master/jquery.fastLiveFilter.js
X-MimeOLE: Produced By Microsoft MimeOLE V6.2.9200.16384

=EF=BB=BF<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!DOCTYPE html PUBLIC "" ""><HTML><HEAD><META content=3D"IE=3D10.000"=20
http-equiv=3D"X-UA-Compatible">

<META http-equiv=3D"Content-Type" content=3D"text/html; =
charset=3Dutf-8">
<META name=3D"GENERATOR" content=3D"MSHTML 10.00.9200.16384"></HEAD>
<BODY>
<PRE>/**=0A=
 * fastLiveFilter jQuery plugin 1.0.3=0A=
 * =0A=
 * Copyright (c) 2011, Anthony Bush=0A=
 * License: &lt;http://www.opensource.org/licenses/bsd-license.php&gt;=0A=
 * Project Website: =
http://anthonybush.com/projects/jquery_fast_live_filter/=0A=
 **/=0A=
=0A=
jQuery.fn.fastLiveFilter =3D function(list, options) {=0A=
	// Options: input, list, timeout, callback=0A=
	options =3D options || {};=0A=
	list =3D jQuery(list);=0A=
	var input =3D this;=0A=
	var lastFilter =3D '';=0A=
	var timeout =3D options.timeout || 0;=0A=
	var callback =3D options.callback || function() {};=0A=
	=0A=
	var keyTimeout;=0A=
	=0A=
	// NOTE: because we cache lis &amp; len here, users would need to =
re-init the plugin=0A=
	// if they modify the list in the DOM later.  This doesn't give us that =
much speed=0A=
	// boost, so perhaps it's not worth putting it here.=0A=
	var lis =3D list.children();=0A=
	var len =3D lis.length;=0A=
	var oldDisplay =3D len &gt; 0 ? lis[0].style.display : "block";=0A=
	callback(len); // do a one-time callback on initialization to make sure =
everything's in sync=0A=
	=0A=
	input.change(function() {=0A=
		// var startTime =3D new Date().getTime();=0A=
		var filter =3D input.val().toLowerCase();=0A=
		var li, innerText;=0A=
		var numShown =3D 0;=0A=
		for (var i =3D 0; i &lt; len; i++) {=0A=
			li =3D lis[i];=0A=
			innerText =3D !options.selector ? =0A=
				(li.textContent || li.innerText || "") : =0A=
				$(li).find(options.selector).text();=0A=
			=0A=
			if (innerText.toLowerCase().indexOf(filter) &gt;=3D 0) {=0A=
				if (li.style.display =3D=3D "none") {=0A=
					li.style.display =3D oldDisplay;=0A=
				}=0A=
				numShown++;=0A=
			} else {=0A=
				if (li.style.display !=3D "none") {=0A=
					li.style.display =3D "none";=0A=
				}=0A=
			}=0A=
		}=0A=
		callback(numShown);=0A=
		// var endTime =3D new Date().getTime();=0A=
		// console.log('Search for ' + filter + ' took: ' + (endTime - =
startTime) + ' (' + numShown + ' results)');=0A=
		return false;=0A=
	}).keydown(function() {=0A=
		clearTimeout(keyTimeout);=0A=
		keyTimeout =3D setTimeout(function() {=0A=
			if( input.val() =3D=3D=3D lastFilter ) return;=0A=
			lastFilter =3D input.val();=0A=
			input.change();=0A=
		}, timeout);=0A=
	});=0A=
	return this; // maintain jQuery chainability=0A=
}=0A=
</PRE></BODY></HTML>
