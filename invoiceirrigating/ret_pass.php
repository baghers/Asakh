<?php
/*
ret_pass.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
session_start();
include('includes/connect.php'); 
include('includes/elements.php');
include('includes/check_user.php');
$kind=0;$ids='';
if($_GET["uid"]!='')
{ 
	$ids = $_GET["uid"];
	$linearray = explode('_',$ids);
	$kind=$linearray[0];
	$SaveTime=date('Y-m-d H:i:s');
	$SaveDate=date('Y-m-d');
	$ClerkID=$login_userid;
	if ($kind>0) {
			 $message='ارسال پسورد :'.$linearray[1];
			 $clerkIDR=$linearray[2];
			 $ErrorDescription=$linearray[3];
//smssent جدول پیامک های ارسالی	
			   $query = "INSERT INTO smssent(kind,ClerkIDR,message,ErrorDescription,SaveTime,SaveDate,ClerkID) 
				VALUES(4,'".$clerkIDR."','$message','$ErrorDescription','$SaveTime','$SaveDate','$ClerkID');";
				mysql_query($query);
		}
		
	
	
 header("Location: invoiceirrigating/home.php");   		
}

$sqlselect="
SELECT '1' _value, 'مشاور طراح' _key 
                             union all SELECT '2' _value, 'مدير مشاور(طراح و ناظر)' _key 
                             union all SELECT '3' _value, 'مجری' _key 
                             union all SELECT '4' _value, 'فروشنده' _key  
                             union all SELECT '5' _value, 'بازبین' _key
                             union all SELECT '6' _value, 'مدیریت' _key
                             order by _value  ";
?>

<!DOCTYPE html>
<html>
<head>
  	<title>بازيابي كلمه عبور</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type="text/javascript" language='javascript' src='ajax.js'></script>
<script type="text/javascript" language='javascript' src='assets/jquery2.js'></script>
<script type="text/javascript" src="lib/jquery2.js"></script>
<script type='text/javascript' src='lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="lib/thickbox.css" />
<link rel="stylesheet" href="assets/style.css" type="text/css" />
<script language="javascript" type="text/javascript">
/* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
function reloadCaptcha()
{
	document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
}
function chekvalu()
{
  //if(document.getElementById("retp").checked=='true')
    //inp='mobile1';
  //else 
  
    inp='email';
    alert(document.getElementById('CoID1').value);
    alert(document.getElementById('secure').value);
    alert(document.getElementById('user1').value);
  if((document.getElementById('CoID1').value=='') 
  || (document.getElementById('secure').value=='')||(document.getElementById('user1').value=='')  )
  {
    alert('لطفا اطلاعات را وارد نماييد')
    return false;
  }
 else 
	  return true;
	//alert('sfdsdf')
    
  

}
</script>
<style>
 #dvmsg
 {
   color:red;
   text-align:center;
   padding:5px 40% 10px 40%;
 }
 #frmsms,#frmemail
 {
   display:none;
 }
</style>
<script type="text/javascript">
(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#rest-form").validate({
                rules: {
                    selectedrolesID1: "required",
					
					email: ""
				
              
                },
                messages: {
                    selectedrolesID1: "",
                   
				
					email: ""
                },
                submitHandler: function(form) {
                   form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);
</script> 
<script>
function ShowHideDv(chk,val)
 {
 //alert(val)
 if(val=='1')
 {
   document.getElementById('spdes').style.display='inline';
   document.getElementById('spoprat').style.display='none';
   document.getElementById('frmsms').style.display='inline';
   document.getElementById('frmemail').style.display='none';
 }
 else
 { 
   document.getElementById('spoprat').style.display='inline';
   document.getElementById('spdes').style.display='none';
   document.getElementById('frmsms').style.display='none';
   document.getElementById('frmemail').style.display='inline';
 }
    
 }
 
function OnSubmitForm()
{
    /*
  if(document.myform.retp[0].checked == true)
  {
    document.myform.action ="http://192.168.1.111:8888/localsms.php";
  }
  else
  if(document.myform.retp[1].checked == true)
  {
    */
    document.myform.action ="ret_pass1.php";
  //}
  return true;
}
</script>
</head>
<body>
	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('includes/top.php'); ?>
            <!-- /top -->
            
            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('includes/header.php'); ?> 
			<!-- /header -->

			<!-- content -->
			<div id="content">
			<?php
			 
             echo '<div id="dvmsg">'.$masg.'</div>';
			 ?>
			 
              <form name="myform" id="myform" action="ret_pass1.php" method="post"     > 
		      
      <!---   <form action="retp.php" method="post" onsubmit="return chekvalu()"> --->
				 <input  name="user1" id="user1" type="hidden" />
				 <input  name="password1" id="password1" type="hidden" />
				 <input  name="Roles1" id="Roles1" type="hidden" />
                 <input  name="clerkID1" id="clerkID1" type="hidden" />	
                 <input  name="kind" id="kind" type="hidden"  value="4" />				 
			 	 
                  <table width="60%" align="center" class="form" border="3">
                   <tbody>
                   <tr>
                     <td width="20%" dir="rtl" class="label">نقش: </td>
                     <td width="80%" class="data">
					 <?php
					$result1 = mysql_query($sqlselect);
					?>
				  <select onclick="ajxselct(this.value)" name="selectedrolesID1" id="selectedrolesID1" >
                    <?php 
					while($row1 = mysql_fetch_assoc($result1))
					{
                      echo"<option value=".$row1['_value'].">".$row1['_key']."</option>";
                    }
				   echo" </select>";
					 ?>
					 </td>
                     </tr>
				   <tr>
                     <td width="20%" dir="rtl" class="label"  ><span id="spCoID">شرکت/ فروشگاه:</span></td>
                     <td width="80%"  id="tdCoID">
				     <select name='CoID1'  id='CoID1'>
                        <option></option>
                     </select>
					 </td>
                    </tr>
                    
					<tr>
                     <td  dir="rtl" class="label">
					   <input name='retp' id='retp' value=2 type='radio' onclick='ShowHideDv(this,this.value)'> ايميل
					 </td>
                     <td class="data">
					    <span id='spdes'   style='display:none'>تلفن همراه:<input  name="mobile1" id="mobile1" type="text" class="textbox"  dir="ltr" size="25"  onblur="ajxretpass('1')"/></span>
					</td>
					
                    </tr> 
				    
					  <tr>
 						<td valign="top">
  							<label for="security_code"></label>
 						</td>
 						<td valign="top">
  							 <img src="image.php" alt="Click to reload image" title="Click to reload image" id="captcha" onclick="javascript:reloadCaptcha()" />
 						</td>
					  </tr>
					  <tr>
 						<td valign="top">
 							<label for="security_code"> کد امنیتی </label>
 						</td>
 						<td valign="top">
						  <span class="explain">براي لود شدن دوباره عكس روي آن كليك كنيد</span>
  						 <input type="text" name="secure" id="secure" value="" onblur="ajxsectycod()"  />
 						</td>
					  </tr>
                   </tbody>
                   <tfoot>
				  
                    <tr>
                     <td><input name="ret_pass" id="ret_pass" type="submit" class="button" onclick="return chekvalu()"  value="تاييد" />
					   </td>
                    </tr>
                   </tfoot>
                  </table>
                 </form>
            </div> 
			<!-- /content -->
            
            <!-- footer -->
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
