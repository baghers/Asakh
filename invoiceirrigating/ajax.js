function ajxselct(val)
{
//
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
  //alert(val)
 //alert(httpxml.responseText)
   document.getElementById('tdCoID').innerHTML=httpxml.responseText;
   if((val=='5') || (val=='6'))
     document.getElementById('spCoID').style.display='none';
  else 
	 document.getElementById('spCoID').style.display='inline'; 
}
}

var url='get_ajax.php';
url=url+"?ajxselct="+val;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxcity(val)
{
//alert(val)
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
  //alert(httpxml.responseText)
   document.getElementById('tdcity').innerHTML=httpxml.responseText;
   
}
}
var url='get_ajax.php';
url=url+"?ajxcity="+val;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxretpass()
{
//alert('gjhg')
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
 var str = httpxml.responseText;
 var res2 = str.split("@");
 var res = res2[0].split("~");
 if(res2[1]=="2")
  {
    document.getElementById('user1').value="";
    document.getElementById('password1').value="";
    document.getElementById('Roles1').value="";
    document.getElementById('clerkID1').value="";
	document.getElementById('mobile1').value="";
	document.getElementById('dvmsg').innerHTML='تلفن همراه نامعتبراست';
   
  }
  else 
  {
  	
   document.getElementById('user1').value=res[0];
   document.getElementById('password1').value=res[1];
   document.getElementById('Roles1').value=res[2];
   document.getElementById('clerkID1').value=res[3];
  	
    
  }
}
}

var e1 = document.getElementById("selectedrolesID1");
var rolesID = e1.options[e1.selectedIndex].value;
//alert(rolesID)
if((rolesID==5) || (rolesID==6))
  var CoID =0;
else
{ 
  var e = document.getElementById("CoID1");
  var CoID = e.options[e.selectedIndex].value;
}
mobile=document.getElementById('mobile1').value;
email=document.getElementById('email').value;
var url='get_ajax.php';
url=url+"?retpass="+rolesID+"&CoID="+CoID+"&mobile="+mobile;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxadd()
{
//alert('xxs')
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
//alert(httpxml.responseText)
masg=httpxml.responseText;
if(masg='msg')
{
 //alert('tekrar');
  document.getElementById('speror').innerHTML=httpxml.responseText;
 document.getElementById('ClerkID').value=masg;
}
else 
 document.getElementById('ClerkID').value=httpxml.responseText;

}
}
//
name = document.getElementById("first_name").value;
famliy= document.getElementById("last_name").value;
gender = document.getElementById("gender").value;
user = document.getElementById("username").value;
pass = document.getElementById("password").value;
//alert('ghg')
//var form = document.getElementById("gender");
//var CoID = e.options[e.checked].value;
mobile=document.getElementById('mobile').value;
email=document.getElementById('email').value; 
var e1 = document.getElementById("selectedrolesID");
var rolesID = e1.options[e1.selectedIndex].value;
var e2 = document.getElementById("soo");
var ostan = e2.options[e2.selectedIndex].value;
var e3 = document.getElementById("sos");
var city = e3.options[e3.selectedIndex].value;        



var parms=name+'~'+famliy+'~'+gender+'~'+user+'~'+pass+'~'+mobile+'~'+email+'~'+rolesID+'~'+ostan+'~'+city;
//var parms='ff';
//alert(parms)
var url='get_ajax.php';
url=url+"?ajxadd="+parms;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxrep()
{
//alert('kk')
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
 msg=httpxml.responseText;
 //alert(msg)
 if(msg==1)
 {
 	document.getElementById('dvmsg').innerHTML='نام کاربری تکراری است';
    document.getElementById("username").value='';
   
 }
 else
 {
  document.getElementById('dvmsg').innerHTML='';
 } 
   
}
}
user = document.getElementById("username").value;
//alert(user)
var url='get_ajax.php';
url=url+"?ajxrep="+user;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxmobile()
{
//alert('kk')
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
 msg=httpxml.responseText;
if(msg==2)
{
   document.getElementById('dvmsg').innerHTML='تلفن همراه تکراری است';
   document.getElementById("mobile").value='';
}
 else 
   document.getElementById('dvmsg').innerHTML='';
 
}
}
mobile = document.getElementById("mobile").value;
RolesID = document.getElementById('RolesID').value;
//alert(mobile)
var url='get_ajax.php';
url=url+"?ajxmobile="+mobile+"&RolesID="+RolesID;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ajxsectycod()
{
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
 msg=httpxml.responseText;
if(msg==0)
{
   document.getElementById("secure").value='';
   document.getElementById('dvmsg').innerHTML=httpxml.responseText;
}
 else 
   document.getElementById('dvmsg').innerHTML='';
 
}
}
secure = document.getElementById("secure").value;
//alert(user)
var url='get_ajax.php';
url=url+"?ajxsecure="+secure;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ajaxview(val)
{
//alert(val)
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
//alert(httpxml.responseText)
  document.getElementById('dvdet').style.display='inline';
  document.getElementById('dvdet').innerHTML=httpxml.responseText;
 
  
   
}
}
var url='get_ajax.php';
//var url='viewapplicantstate1.php';
//var url='a.php';
url=url+"?ajaxview="+val;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ajaxview2()
{
//alert('ghjgh')
var httpxml;
try
{
httpxml=new XMLHttpRequest();
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
httpxml=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e)
{
alert("Your browser does not support AJAX!");
return false;
}
}
}
function stateChanged3()
{
if(httpxml.readyState==4 || httpxml.readyState=="complete")
{
//alert(httpxml.responseText)
  document.getElementById('dvdet').style.display='none';
  document.getElementById('dvlist').innerHTML=httpxml.responseText;
  
   
}
}
var e1 = document.getElementById("fieldselect");
var selfild = e1.options[e1.selectedIndex].value;
var e2 = document.getElementById("select");
var selop = e2.options[e2.selectedIndex].value;
var txt=document.getElementById("txtsrch1").value;
var year=document.getElementById("year").value;
if(document.getElementById("chkstat").checked) 
  var chk='1';
  else 
  chk='';  
//alert(selop);
var url='get_ajax.php';
url=url+"?ajaxview2="+txt+"&selfild="+selfild+"&selop="+selop+"&chk="+chk+"&year="+year;
url=url+"&sid="+Math.random();
httpxml.onreadystatechange=stateChanged3;
httpxml.open("GET",url,true);
httpxml.send(null);
}
