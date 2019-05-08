<?php
/*
account.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('includes/connect.php');
include('includes/check_user.php'); 
include('includes/elements.php');
require_once('class/fieldType.class.php');
require_once 'class/upload.class.php';
$drop=new fieldType();
 
//بروز رسانی اطلاعات کاربران
if ($login_user && $_POST)//بارگذاری تصویر شخص 
{
    if ($_POST["email"]=='.')
    {
        chmod('./var/www/invoiceirrigating', 0777);
        new Upload('file1', '','2',"", $_FILES['file1']['name']);
        chmod('./var/www/invoiceirrigating', 0555);
        
    }
    else
    {
        chmod($_SERVER['DOCUMENT_ROOT'].'/invoiceirrigating', 0777);
        chmod($_SERVER['DOCUMENT_ROOT'].'/invoiceirrigating/'.$_POST["email"], 0777);
        new Upload('file1', '','2',$_POST["email"]."/", $_FILES['file1']['name']);
        chmod($_SERVER['DOCUMENT_ROOT'].'/invoiceirrigating/'.$_POST["email"], 0555);
        chmod($_SERVER['DOCUMENT_ROOT'].'/invoiceirrigating', 0555);        
    }

}

/*
'نام'=>$first_name,
'نام خانوادگی'=>$last_name,
'ایمیل'=>$email,
'نام کاربری'=>$username ,
'کلمه عبور'=>$password ,
'تکرار کلمه عبور'=>$_POST["passwordr"]  ,
'کد ملی'=>$melicode ,
'تلفن همراه'=>$mobile ,
'نقش کاربر'=>$RolesID,
'استان'=>$_POST["soo"],
'دشت/شهرستان'=>$CityId 
*/

if (!$login_user) header("Location: login.php");
//clerk جدول کاربران
$query = "SELECT clerk.* FROM clerk WHERE ClerkID = '" . $login_userid . "';";
$result = mysql_query($query);
$user = mysql_fetch_assoc($result);
//echo $_SESSION['login_RolesID'];
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$mobile = $_POST["mobile"];
	$melicode = $_POST["melicode"];
	$email = $_POST["email"];
	$notgetsms = $_POST["notgetsms"];
	$first_name = $_POST["first_name"];
	$last_name =  $_POST["last_name"];
	$gender =  $_POST["gender"];
	$username =  $_POST["username"];
	$password = $_POST["password"];
	$passwordr = $_POST["passwordr"];
    
    
    $query = "SELECT clerk.* FROM clerk WHERE NOC='$username' and ClerkID <>'$login_userid' ;";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    
    $resmbil = @mysql_query("select * from clerk where mobile=".$_POST["mobile"]." and city=".$_SESSION['login_RolesID']." ");
    $mbil = @mysql_num_rows($resmbil);
   // echo "select * from clerk where mobile=".$_POST["mobile"]." and city=".$_SESSION['login_RolesID']." ";
    
    $duplicate=0;
    $cumbil=0;
    if ($row['ClerkID']>0)
      $duplicate=1;
    if($mbil>0)
      $cumbil=1;
  ////////////////////////////////////////////////////////////////////////////////////////////////
  ///php validation/////////////////////////////////////////////////////////////////////////////////
    require_once('class/validation.class.php');
    $valid = new validation;	
	$vals = array('نام'=>$first_name,'نام خانوادگی'=>$last_name,'ایمیل'=>$email
      ,'نام کاربری'=>$username ,'کلمه عبور'=>$password ,'تکرار کلمه عبور'=>$_POST["passwordr"]  
      ,'کد ملی'=>$melicode ,'تلفن همراه'=>$mobile ,'نقش کاربر'=>$RolesID
      ,'استان'=>$_POST["soo"],'دشت/شهرستان'=>$CityId   
            );
             $valid->addSource($vals);
             $valid->addRule('نام', 'persian_str', true, 1, 50, true)
                   ->addRule('نام خانوادگی', 'persian_str', true,1, 50, true)
                   ->addRule('نام کاربری','string', true, 4, 8, true)
                   ->addRule('ایمیل', 'email', true, 1, 255, true)
                   ->addRule('کلمه عبور', 'password', true, 8, 15, true)
                   ->addRule('تکرار کلمه عبور', 'match', true, $password, 255, true)
                   ->addRule('کد ملی', 'melicode', true, 10, 10, true)
                   ->addRule('تلفن همراه', 'mobile', true, 10, 10, true);
                  
            $valid->run();
        
 /////////////////////////////////////////////////////////////////////////////////////////////
            

           	$IDUser = $_POST['IDUser'];
			$path = $_POST['path'];
			//$ext = end((explode(".", $_FILES["file1"]["name"])));
           // $attachedfile=$IDUser.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
				
            new Upload('file1', 'image/jpeg','2', '../upfolder/profile/',$IDUser.'_1_' ,$IDUser.'_1*');
    		//clerk جدول کاربران
            $query = "
    		UPDATE clerk SET
    		NOC = '" . encrypt($username). "', 
    		CPI = '" . encrypt($first_name). "', 
    		mobile = '" . $mobile . "', 
    		email = '".encrypt($email)."', 
    		melicode = '" . $melicode . "',
    		notgetsms = '" . $notgetsms . "', 
    		DVFS = '" . encrypt($last_name). "', 
    		GE = '" . $gender . "',
    		WN = '" . encrypt($password) . "'
    		WHERE ClerkID = " . $login_userid . ";";
    		$_SESSION['erors']='';
	        $_SESSION['usreror']='';
	        $_SESSION['mbileror']='';
	       if((sizeof($valid->errors)== 0) && ($duplicate==0))
	   	      $result = mysql_query($query);
	      else
	      {
	   	    if(sizeof($valid->errors) > 0)
	          $_SESSION['erors']=$valid->errors;
	        if($duplicate!=0)
	          $_SESSION['usreror']='نام کاربری تکراری است';
	        if($cumbil!=0)
	          $_SESSION['mbileror']='تلفن همراه تکراری است';
	      }
    	
           // echo $query;
            $query = "SELECT clerk.* FROM clerk WHERE 
            NOC='$username' and 
            CPI='$first_name' and 
            DVFS='$last_name' and 
            substr(WN,4,(substr(WN,1,3)-5)*3)=substr('" . encrypt($password) . "',4,(substr('" . encrypt($password) . "',1,3)-5)*3)
            and  ClerkID = " . $login_userid . ";";
            $result1 = mysql_query($query);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////


       $IDUser =$login_userid;
 //print $IDUser;
if (!$_POST) 
{ 
        $IDUser =$login_userid;
        $path = "../upfolder/profile/";
} 
	
$query = "SELECT clerk.* FROM clerk WHERE ClerkID = '" . $login_userid . "';";
//echo $query;
$result = mysql_query($query);
$user = mysql_fetch_assoc($result);	
		
?>
<!DOCTYPE html>
<html>
<head>
  	<title>ویرایش پروفایل</title>
<meta http-equiv="X-Frame-Options" content="deny" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />
    <script type="text/javascript" src="funcs.js"></script>	
    <script type="text/javascript" src="ajax.js"></script>
    <link rel="stylesheet" href="css/styl.css" type="text/css" />
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
            
            <?php include('includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('includes/header.php'); ?> 
			<!-- /header -->
            <?php echo '<div id="dvmsg">پرکردن همه فیلدها به جز عکس کاربر الزامی است</div>'; ?>
			<!-- content -->
			<div id="content">
         
		    <form action="account.php" method="post" autocomplete="off" enctype="multipart/form-data">
		    <?php require_once('includes/csrf_pag.php'); ?>
		    <input name="RolesID" type="hidden" class="textbox" id="RolesID" value="<?php echo $_SESSION['login_RolesID']; ?>"  />
			   <table id="recordtable" width="99%" align="center">
                   <tbody>           
	
                     <tr>
                      <td width="20%" class="label">نام: </td>
                      <td width="80%" class="data"><input name="first_name" readonly type="text" class="textbox" id="first_name" value="<?php echo decrypt($user['CPI']); ?>" size="15" maxlength="15" title="لطفا حروف فارسی وارد نمایید" required  /></td>
                     </tr>
                     <tr>
                      <td class="label">نام خانوادگی:</td>
                      <td class="data"><input name="last_name" type="text" readonly class="textbox" id="last_name" value="<?php echo decrypt($user['DVFS']); ?>" size="15" maxlength="15" title="لطفا حروف فارسی وارد نمایید" required  /></td>
                     </tr>
                     <tr>
                      <td class="label">جنسیت:</td>
                      <td class="data">
                       <input name="gender" type="radio" id="gender" value="0"<?php if ($user['GE'] == 0) echo " checked"; ?>>زن
                       <input name="gender" type="radio" id="gender" value="-1" <?php if ($user['GE'] == -1) echo " checked"; ?>>مرد
                      </td>
                     </tr>
                     <tr>
                      <td class="label">نام کاربری:</td>
                      <td class="data"><input name="username" type="text" autocomplete="off" readonly class="textbox" id="username" dir="ltr" value="<?php echo decrypt($user['NOC']); ?>" size="15" maxlength="15" pattern="[a-zA-Z0-9]{4,8}" title="نام کاربری حداقل 4 و حداکثر 8 کاراکتر انگلیسی" required/>
                      
                      <span class="txtvalid">(حداقل 4 و حداکثر 8 کاراکتر انگلیسی)</span></td>
                     </tr>
                     <tr>
                     <?php if($login_RolesID==24) $auto='off';else $auto='on';  ?>    
                      <td class="label">کلمه عبور:</td>
                      <td class="data">
                      <input type="text" style="display:none">
                      <input name="password" type="password" autocomplete="off"  class="textbox" id="password" dir="ltr"  size="15" maxlength="20"  <?php echo "autocomplete=\"$auto\"";if (! $login_designerCO) echo "pattern=\"(?=.*\\d)(?=.*[a-zA-Z]).{8,15}\"  required"; ?>
                      title="کلمه عبور حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی"/>
                      <input type="password" style="display:none">
                      <span class="txtvalid">(حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی)</span></td>
                     
                     </tr>
                     <tr>
                      <td class="label">تکرار کلمه عبور:</td>
                      <td class="data">
                      <input type="text" style="display:none">
                      <input name="passwordr" type="password" autocomplete="off" class="textbox" id="passwordr" dir="ltr" value='' size="15" maxlength="20" <?php if (! $login_designerCO) echo "required onblur=\"return match_pass()\" "; print $readonly; ?>/>
                      <input type="password" style="display:none">
                      <span id='rpass'></span></td>
                     
                     </tr>
                     <tr>
                      <td colspan="1" class="label">تلفن همراه</td>
                      <td width="80%" class="data"><input name="mobile" type="text" class="textbox" id="mobile" value="<?php echo $user['mobile']; ?>" size="10" maxlength="10"  pattern="[9]{1}[0-9]{9}" title="(رقم10)" <?php if (! $login_designerCO) echo "required"; ?>  onblur="ajxmobile()"/><span class="txtvalid">(10 رقم و بدون صفر ابتدا )</span></td>
                     </tr>
                    <tr>
                      <td colspan="1" class="label">کد ملی</td>
                      <td width="80%" class="data"><input name="melicode" type="text" class="textbox" id="melicode" value="<?php echo $user['melicode']; ?>" size="10" maxlength="11"  pattern="[0-9]{1,2}[0-9]{9}" title="(10 رقم)" <?php if (! $login_designerCO) echo "required"; ?>/></td>
                     </tr>
                      <tr>
                      <td colspan="1" class="label">ایمیل</td>
                      <td width="80%" class="data"><input name="email" type="<?php if (! $login_designerCO) echo "email"; ?>" class="textbox" id="email" value="<?php echo decrypt($user['email']); ?>"   <?php if (! $login_designerCO) echo "required"; ?>  /></td>
                     </tr>
                     <tr>
                      <td class="label">دریافت&nbsp;پیامک:</td>
                      <td class="data">
                       <input name="notgetsms" type="radio" id="notgetsms" value="0"<?php if ($user['notgetsms'] == 0) echo " checked"; ?>>فعال
                       <input name="notgetsms" type="radio" id="notgetsms" value="1"<?php if ($user['notgetsms'] == 1) echo " checked"; ?>>غیر فعال
                      </td>
                     </tr>
                               
                     


       

                   <?php         
                    $fstr1="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/profile/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
							$path1 = $path.$file;
                            if (($ID==$IDUser) && ($No==1) )
                                $fstr1="<a target='blank' href='$path1' ><img style = 'width: 20px;' src='img/accept.png'  ></a>";
                        }
				    }
				   ?>
            	    <tr >
					<td class='data'><input type='file' name='file1' id='file1' value='123' accept='image/jpeg'>عکس کاربر:</td>
					<td><?php print '<img src='.'/upfolder/profile/'.$imgprofile.' width=25 height=25>';?></td>
			        
      					<td colspan="8"><input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>">
			     		<input type="hidden" name="path" value ="<?php echo $path; ?>">
					
					</tr>
		
					 
                    </tbody>
                    <tfoot>
                     <tr>
                      <td></td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="بروزرسانی" /></td>
                     </tr>
                    </tfoot>
                   </table>
                  </form>
               <div style="padding:10px;color:red;text-align: center;">
                 <?php
              if(isset($_POST["submit"]))
                 {
                  if(($_SESSION['erors']!='') || ($_SESSION['usreror']!=''))
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
                  }
                  else 
                     echo 'بروزرسانی با موفقیت انجام شد';
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