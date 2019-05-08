<?php
/*
login.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

	session_start();

 
include('includes/connect.php'); 

$ClerkID_post = 0;
$username_post = $_POST['username'];
$password_post = $_POST['password'];

 
 


 $Cond=" AND ifnull(MMC,0)<=0 AND ifnull(HW,0)<=0 AND ifnull(BR,0)<=0  ";
 if($_POST['selectedrolesID']==1)//مجری
    $Cond = "AND MMC = '$_POST[CoID]'";
else if($_POST['selectedrolesID']==2)//تولیدکننده
    $Cond = "AND HW = '$_POST[CoID]'";
else if($_POST['selectedrolesID']==3)//طراح
    $Cond = "AND BR = '$_POST[CoID]'";

$secerror="";
if ($username_post != ''){


	if( ($_SESSION['security_number'] == $_POST['secure'] && !empty($_SESSION['security_number'] ) )
    
    ||( in_array($_POST['selectedrolesID'], array(1,2,3)))
     ) 
	{
		unset($_SESSION['security_number']);
		
        
        //دیکود کردن کلمه عبور و نام کاربری
            $string=$password_post;
         $ascii = NULL;
             if ((strlen($string)+5)<10)
                $ascii =$ascii.'00'. (strlen($string)+5);
            else if ((strlen($string)+5)<100)
                $ascii =$ascii.'0'. (strlen($string)+5);    
            else $ascii =$ascii.(strlen($string)+5);
            
        for ($i = 0; $i < strlen($string); $i++)
        {
            if (ord($string[$i])<10)
                $ascii =$ascii.'00'. ord($string[$i]);
            else if (ord($string[$i])<100)
                $ascii =$ascii.'0'. ord($string[$i]);    
            else $ascii =$ascii.ord($string[$i]);
        }
        while (strlen($ascii)<120)
            $ascii =$ascii.rand(100,999);
            $ascii2=$ascii;
        $string=$username_post;
         $ascii = NULL;
             if ((strlen($string)+5)<10)
                $ascii =$ascii.'00'. (strlen($string)+5);
            else if ((strlen($string)+5)<100)
                $ascii =$ascii.'0'. (strlen($string)+5);    
            else $ascii =$ascii.(strlen($string)+5);
            
        for ($i = 0; $i < strlen($string); $i++)
        {
            if (ord($string[$i])<10)
                $ascii =$ascii.'00'. ord($string[$i]);
            else if (ord($string[$i])<100)
                $ascii =$ascii.'0'. ord($string[$i]);    
            else $ascii =$ascii.ord($string[$i]);
        }
        while (strlen($ascii)<120)
            $ascii =$ascii.rand(100,999);
            
            
        //clerk جدول کاربران
		$query = "SELECT ClerkID, NOC,MMC FROM clerk WHERE 
        substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3) AND 
        substr(WN,4,(substr(WN,1,3)-5)*3)=substr('$ascii2',4,(substr('$ascii2',1,3)-5)*3)
         $Cond";
		
	//	print $query;
		
        $result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		$ClerkID_post = $row['ClerkID'];
		$DesignerCoID_post = $row['MMC'];
		$operatorcoid_post = $row['HW'];
		$ProducersID_post = $row['BR'];
        
		if ($ClerkID_post != 0){
			$expire_date = time() + 21600;
			///setcookie("userid", $ClerkID_post, $expire_date);
            $_SESSION['userid'] = $ClerkID_post;
            //$_SESSION['useridexpire'] = $expire_date;
            //loginhistory جدول تاریخه ورود
     		$userIPAddress = $_SERVER['REMOTE_ADDR'];
    		$query = "Insert into loginhistory (ClerkID, user_ip, logout_time, login_time, status) values ('$ClerkID_post', '$userIPAddress', 
            '0000-00-00 00:00:00',  NOW(), 'Signed in')";
            
            //print $query;
            //exit;
            //if (($ClerkID_post!=4) && ($ClerkID_post!=22) )
                mysql_query($query);
                
                
                
		}
   	} 
	else 
	{
		$secerror= '<p class="error">کد امنیتی نادرست می باشد</p>';
		//	header("Location: home.php");
	}
	
	
}
include('includes/elements.php');
include('includes/check_user.php'); 

if ($login_user)
{

    
    header("Location: home.php");
    exit;


    
}




?>
<!DOCTYPE html>
<html>
<head>
  	<title>ورود</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	
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
</script>
      <script >
  function isenamad()
  {
    if (document.getElementById('username').value=='namadtest')
    {
         $('#divCoID').hide();
         $('#prodid').hide();
         $('#selectedrolesID').hide();
    }
    else
    {
         $('#divCoID').show();
         $('#prodid').show();
         $('#selectedrolesID').show();
    }
    
    
  }
function FilterComboboxes(Url,Tabindex)
{ 
    var selectedrolesID=document.getElementById('selectedrolesID').value;
    var selectedusername=document.getElementById('username').value;
    //alert (selectedrolesID);
    $.post(Url, {selectedrolesID:selectedrolesID,selectedusername:selectedusername}, function(data){
    //alert (data.val1);
	       $('#divCoID').html(data.val0);
           
            $('#prodid').show();
           if (!data.val0) 
            $('#prodid').hide();
           
           
           
       }, 'json');    

                               
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
			<div id="content"><?php

				if ($_POST){
					echo "<p class='error'>خطا در ورود</p> $secerror";
				}
				
				
	
	

?>
                <form action="login.php" method="post">
                  <table width="400" class="form">
                   <tbody>
                    <tr>
                     <td width="20%" dir="rtl" class="label">نام&nbsp;کاربری:</td>
                     <td width="80%" class="data"><input onblur=" isenamad();" name="username" type="text" class="textbox" id="username" dir="ltr" size="25" maxlength="15" /></td>
                    </tr>
                    <tr>
                     <td class="label">کلمه&nbsp;عبور:</td>
                     <td class="data"><input name="password" type="password" class="textbox" id="password" dir="ltr" size="25" maxlength="15" /></td>
                    </tr>
                    
                    <?php   
                    
                        $query = "SELECT '1' _value, 'طراح،ناظر،بازرس' _key 
                             union all SELECT '2' _value, 'مجری،پیمانکار' _key 
                             union all SELECT '3' _value, 'فروشنده،تولیدکننده' _key  
                             union all SELECT '4' _value, 'مدیریت' _key
                             union all SELECT '5' _value, 'کشاورز، بازبین' _key
                             order by _key  COLLATE utf8_persian_ci";
        
    				 $selectedrolesID = get_key_value_from_query_into_array($query);         
    				 print 
					 select_option('selectedrolesID','نقش:',',',$selectedrolesID,'','','','','rtl','','','',
                     "onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/login_jr.php',this.tabIndex);\"",164).
                     "</tr><tr><td class='label'  name='prodid' id='prodid' >شرکت/ فروشگاه:</td> ";
    				 print 
					 select_option('CoID','',',',$CoID,'','','','','','','','','',164,'');
                     
					 
					  ?>
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
  						 <input type="text" name="secure" value="" onclick="this.value=''" />
 						</td>
					  </tr>



                   </tbody>
                   <tfoot>
                    <tr>
                     <td>&nbsp;</td>
                     <td><input name="submit" type="submit" class="button" id="submit" value="ورود" /></td>
                    </tr>
                    <tr>
                    
                     <td colspan="2">در صورت اطمینان از صحت نام کاربری و کلمه عبور و  وجود مشکل در ورود، دو دکمه Ctrl و F5 را همزمان بفشارید و دوباره وارد شوید.</td>
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