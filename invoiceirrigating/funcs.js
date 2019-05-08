function jsrole(val)
{
  //alert(val)
   if(val=='3')
    document.getElementById('tdrole').style.display='inline';
  else 
    document.getElementById('tdrole').style.display='none';  
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
function ShowHideDiv(chk,idf)
 {
     var dv = document.getElementById('sp'+idf);
     dv.style.visibility = chk.checked ? "visible" : "hidden";  
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
function ShowHideDiv2(chk,val)
 {
 //alert(val)
 if(val=='1')
 {
   document.getElementById('spdes').style.display='inline';
   document.getElementById('spoprat').style.display='none';
 }
 else
 { 
   document.getElementById('spoprat').style.display='inline';
   document.getElementById('spdes').style.display='none';
 }
    
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
function ShowHideDiv3(des,mojvz)
 {
  if(des==0)
  {   
     document.getElementById('spoprat').style.display='inline';
	 document.getElementById('spdes').style.display='none';
  }
   else 
   {
      document.getElementById('spdes').style.display='inline';
	  document.getElementById('spoprat').style.display='none';
   }
  if(mojvz==1)
    document.getElementById('spmojavez').style.visibility='visible'; 
 else 
	document.getElementById('spmojavez').style.visibility='hidden';
 }