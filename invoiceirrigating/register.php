<?php
/*
register.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
session_start();
include('includes/connect.php'); 
include('includes/check_user.php'); 
include('includes/elements.php'); 
require_once('class/fieldType.class.php');
$drop=new fieldType();
//ثبت نام کاربر
$msg='';
$register = false;
if(isset($_POST['send']))
{
 
     $string=$_POST['username'];
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
            
    $first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$gender = $_POST["gender"];
	$username = $_POST["username"];
	$mobile = $_POST["mobile"];
	$password = $_POST["password"];
	$passwordr = $_POST["passwordr"];
	$RolesID = $_POST["RolesID"];
	$cityId = $_POST["city"];
	$email = $_POST["email"];
////////////////////////////////////////////////////////////////////////////////////////////////
///php validation/////////////////////////////////////////////////////////////////////////////////
    require_once('class/validation.class.php');
    $valid = new validation;	
	$vals = array('شركت / فروشگاه'=>$first_name,'نام شركت / فروشگاه'=>$last_name,
	'جنسیت'=>$gender,'ایمیل'=>$email
      ,'نام کاربری'=>$username ,'کلمه عبور'=>$password ,'تکرار کلمه عبور'=>$_POST["passwordr"]  
      ,'تلفن همراه'=>$mobile ,'نقش'=>$RolesID
      ,'استان'=>$_POST["ostan"],'دشت/شهرستان'=>$cityId  
            );
             $valid->addSource($vals);
             $valid->addRule('شركت / فروشگاه', 'persian_str', true, 1, 50, true)
                   ->addRule('نام شركت / فروشگاه', 'persian_str', true,1, 50, true)
                   ->addRule('جنسیت', 'string', true,'1', '1', '')
                   ->addRule('نام کاربری','string', true, 4, 8, true)
                   ->addRule('ایمیل', 'email', true, 1, 255, true)
                   ->addRule('کلمه عبور', 'password', true, 8, 15, true)
                   ->addRule('تکرار کلمه عبور', 'match', true, $password, 255, true)
                   ->addRule('تلفن همراه', 'mobile', true, 10, 10, true)
                   ->addRule('نقش', 'string', true, '1', '50',true)
                   ->addRule('استان', 'string', true, '1', '50', true)
                   ->addRule('دشت/شهرستان', 'string', true,'1', '50',true);
           
            $valid->run();
        
 /////////////////////////////////////////////////////////////////////////////////////////////
    $query = "SELECT clerk.* FROM clerk WHERE substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3);";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
//echo "select * from clerk where mobile=".$_POST["mobile"]." and city=".$RolesID." ";	
    $resmbil = @mysql_query("select * from clerk where mobile=".$_POST["mobile"]." and city=".$RolesID." ");
    $mbil = @mysql_num_rows($resmbil);
	
    $duplicate=0;
	$cumbil=0;
	$se=0;
	if($mbil>0)
      $cumbil=1;
    if($row['ClerkID']>0)
      $duplicate=1;
    if(($_SESSION['security_number']!= $_POST['secure'] || empty($_SESSION['security_number']))) 
      $se=1;
      //clerk جدول کاربران
	$query = "INSERT INTO clerk(NOC, WN, CPI, DVFS, GE, MMC ,BR,HW 
            ,city,SaveTime,SaveDate,ClerkIDSaving,CityId,mobile,email,Disable) 
            VALUES('" . encrypt($username) . "', '" . encrypt($password) . "', '" . encrypt($first_name) . "', '" . encrypt($last_name) . "', 
            '$gender', '$DesignerCoID', '$ProducersID', '$operatorcoID', '$RolesID' 
            , '" .$save_date . "','".date('Y-m-d')."','$login_userid','$cityId','$mobile', '".encrypt($email). "',1)";
	   $_SESSION['erors']='';
	   $_SESSION['usreror']='';
	   $_SESSION['mbileror']='';
	   $_SESSION['seseror']='';
	   if((sizeof($valid->errors)== 0) && ($duplicate==0) && ($cumbil==0) && ($se==0))
	   	  $result = mysql_query($query);
	   else
	   {
	   	 if(sizeof($valid->errors) > 0)
	       $_SESSION['erors']=$valid->errors;
	     if($duplicate!=0)
	       $_SESSION['usreror']='نام کاربری تکراری است';
	     if($cumbil!=0)
	       $_SESSION['mbileror']='تلفن همراه تکراری است';
	     if($cumbil!=0)
	       $_SESSION['seseror']='کد امنیتی نادرست می باشد';
	   }    


}

//if($_SESSION['mesg']=='suc')
$linearray = explode('~',$_GET['msg']);
//if(isset($_GET['msg']))
 // echo '<br>'.$linearray[2].' f'.$_GET['msg'].'<br>';
$ErrorDescription=$linearray[2];


?>
<!DOCTYPE html>
<html>
<head>
  	<title>عضویت</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="funcs.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.validate.min.js"></script>

<link rel="stylesheet" href="css/styl.css" type="text/css" />
<link rel="stylesheet" href="assets/style.css" type="text/css" />

<script>  
function reloadCaptcha()
	{
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
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

            <!-- /main navigation -->
            
            <?php include('includes/subnavigation.php'); ?>
            
			<!-- header -->
            <?php
			include('includes/header.php');
			  echo '<div id="dvmsg">پرکردن همه فیلدها الزامی است<br>
			  نام کاربری حداقل 4 و حداکثر 8 کاراکتر انگلیسی<br>
			  کلمه عبور حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی<br>
			  تلفن همراه 10 رقم و بدون صفر ابتدا 
			  </div>'; 
			 ?> 
			<!-- /header -->

			<!-- content -->
			
			<div id="content">
              <form action="" method="post" id="" autocomplete="off">
                 <table width="900" align="center" class="form" border="1">
                   <tbody>
                    <tr>
                      <td width="20%" class="label">شركت / فروشگاه</td>
                      <td class="data"><input name="first_name" type="text" class="textbox"  id="first_name"  size="12"  title="لطفا حروف فارسی وارد نمایید" required  /></td>
                      <td class="label">نام شركت / فروشگاه</td>
                      <td class="data"><input name="last_name" type="text" class="textbox" id="last_name" size="12" maxlength="50" title="لطفا حروف فارسی وارد نمایید" required  /></td>
                      <td class="label">جنسیت:</td>
                      <td class="data">
                       <input name="gender" type="radio" id="gender" value="0" required > زن
                       <input name="gender" type="radio" id="gender" value="-1"  > مرد
                      </td>
                    
					  </tr>
					 <tr>
                      <td class="label">نام کاربری:</td>
                      <td class="data"><input name="username" autocomplete="off" type="text" class="textbox" id="username"  dir="ltr"  size="12" maxlength="50" pattern="[a-zA-Z0-9]{4,8}"  title="نام کاربری حداقل 4 و حداکثر 8 کاراکتر انگلیسی" required onblur="ajxrep()" />
					  </td>
                      <td class="label">کلمه عبور:</td>
                      <td class="data">
                      <input type="text" style="display:none">
                      <input name="password" autocomplete="off" type="password" class="textbox" id="password" value="" dir="ltr" size="12" maxlength="20" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,15}" <?php print $readonly; ?> required title="کلمه عبور حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی" />
                      <input type="password" style="display:none">
                      </td>
                      <td class="label">تکرار کلمه عبور:</td>
                      <td class="data">
                      <input type="text" style="display:none">
                      <input name="passwordr" autocomplete="off" type="password" class="textbox" id="passwordr" dir="ltr" size="12" maxlength="50" required onblur="return match_pass()" <?php print $readonly; ?> />
                      <input type="password" style="display:none">
                      <span id='rpass'></span></td>
					 
                     </tr>
					 <tr>
                      <td class="label">تلفن همراه</td>
                      <td class="data"><input name="mobile" type="text" class="textbox" id="mobile" dir="ltr"     pattern="[9]{1}[0-9]{9}" title="(رقم10)" required onblur="ajxmobile()" /></td>
					  <td class="label">ايميل:</td>
                      <td class="data">
				       <input name="email" type="email" class="textbox" id="email" dir="ltr" required  />
					  </td>
					 
                     <td class="label">نقش:</td>
                      <td class="data">
				 <?php
				 $query="
                             SELECT '9' _value, 'طراح (کارشناس مشاور)' _key 
                             union all SELECT '10' _value, 'ناظر(مدیر مشاور)' _key
                             union all SELECT '2' _value, 'مجری(پيمانكار)' _key 
                             union all SELECT '3' _value, 'فروشنده(توليدكننده)' _key  
                             order by _value  ";
					echo $drop->dropDb2('RolesID','_key','_value',$query,'','required onchange="ajxmobile()"')
				  ?>
					  </td>
					   <td></td>
                     </tr>
					 <tr>
					  <td class="label">استان:</td>
                      <td class="data">
				 <?php
					$query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' group by id  ";
					echo $drop->dropDb2('ostan','_key','_value',$query,'','required onclick="ajxcity(this.value)"') 
	              ?>
					</td>
					 <td class="label">دشت/شهرستان:</td>
                     <td id="tdcity"></td>
					  <tr>
 						<td valign="top">
  							<label for="security_code"></label>
 						</td>
 						<td valign="top">
  							 <img src="image.php" alt="Click to reload image" title="Click to reload image" id="captcha" onclick="javascript:reloadCaptcha()" />
 						</td>
 						 <td colspan="2"></td>
					  </tr>
					 <tr>
 						<td valign="top">
 							<label for="security_code"> کد امنیتی </label>
 						</td>
 						<td valign="top">
						  <span class="explain">براي لود شدن دوباره عكس روي آن كليك كنيد</span>
  						 <input type="text" id="secure" name="secure" value="" onblur="" onclick="this.value=''" />
 						</td>
					  </tr>
					 <tr>
                      <td colspan="4" align="center"><input name="send" type="submit" class="button" id="send" onfocus="" onclick=""  value="ثبت"  /></td>
                     </tr>
                    </tbody>
                    <tfoot>
                     
                    </tfoot>
                   </table>
                  </form>
              <div style="padding:10px;color:red;text-align: center;">
                 <?php
                 if(isset($_POST["send"]))
                 {
                  if(($_SESSION['erors']!='') || ($_SESSION['usreror']!='')|| ($_SESSION['mbileror']!='')|| ($_SESSION['seseror']!=''))
                  {
                     if($_SESSION['erors']!='')
                     {
                       foreach($_SESSION['erors'] as $eror)
                          echo $eror.'<br>';
                     }
                     if($_SESSION['usreror']!='')
                        echo '<br>'.$_SESSION['usreror'];
                     if($_SESSION['mbileror']!='')
                        echo '<br>'.$_SESSION['mbileror'];
                     if($_SESSION['seseror']!='')
                        echo '<br>'.$_SESSION['seseror'];
                  }
                  else 
                     echo 'ثبت با موفقیت انجام شد ';
                 }
                 ?>
               </div>
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