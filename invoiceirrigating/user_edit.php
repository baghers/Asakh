<?php
/*
user_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 
*/
 session_start();
 include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); 
      include('includes/elements.php'); ?>
	  <?php
require_once('class/fieldType.class.php');
$drop=new fieldType();

require_once('funcs.php');
if ($login_Permission_granted==0) header("Location: ../login.php");
if ($login_RolesID==17) //ناظر مقیم فقط نقش کشاورز را می بیند
{$cond.=" and roles.rolesid in (26) ";$hide2='style=display:none';}
else {$cond.=" ";$hide=' ';}

	
	//$uid = is_numeric($_GET["uid"]) ? intval($_GET["uid"]) : 0;
	$ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
        $linearray = explode('_',$ID);
        $uid=$linearray[0];
        $emailsend=$linearray[1];
        
    if ($_POST["uid"]>0)
	$uid = $_POST["uid"];
	
/*
clerk جدول کاربران
tax_tbcity7digit جدول شهرها
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
$query = "SELECT clerk.*,ostan.id ostanid,shahr.id shahrid FROM clerk 
left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(clerk.cityid,1,4) and substring(shahr.id,5,3)='000' 
and substring(shahr.id,3,5)<>'00000'
WHERE clerk.ClerkID = " . $uid . ";";

//print $query; 


$result = mysql_query($query);
$user = mysql_fetch_assoc($result);
    $soo=$user["ostanid"];
    $sos=$user["shahrid"];
	$mobileold=$user["mobile"];
	$userNOC=decrypt($user['NOC']);
	$userCPI=decrypt($user['CPI']);
	$userDVFS=decrypt($user['DVFS']);
	$userGE=$user['GE'];
	$userWN=decrypt($user['WN']);
	$usermobile=$user['mobile'];
	$usercity=$user['city'];
	$userMMC=$user['MMC'];
	$userHW=$user['HW'];
	$userBR=$user['BR'];
	$Disable=$user['Disable'];
    $isglobal=$user['isglobal'];
    $isfulloption=$user['isfulloption'];
    $isfulloptiondate=$user['isfulloptiondate'];
	$userEmail=decrypt($user['email']);
	$usermelicode=$user['melicode'];
///////////////////////////////////////////////////////////////////////////////////////////////////	
if(isset($_POST["submit"])){

	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$gender = $_POST["gender"];
	$username = $_POST["username"];
	$mobile = $_POST["mobile"];
	$password = $_POST["password"];
	$passwordr = $_POST["passwordr"];
	    if ($_POST["DesignerCoID"]>0)
	   $DesignerCoID = $_POST["DesignerCoID"];
    else
        $DesignerCoID=0;
    if ($_POST["ProducersID"]>0)
	   $ProducersID = $_POST["ProducersID"];
    else
       $ProducersID=0;
	$RolesID = $_POST["RolesID"];
    if ($_POST["operatorcoID"]>0)
	   $operatorcoID = $_POST["operatorcoID"];
    else
        $operatorcoID=0;
    
	$CityId = $_POST["city"];
	$Email = $_POST["Email"];
	$melicode =$_POST["melicode"];
	if ($_POST['Disable']=='on')  $Disable=1;else $Disable='0';
	
    $query = "SELECT clerk.* FROM clerk WHERE NOC='$username' and ClerkID <>'$uid' ;";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
 
    $resmbil = @mysql_query("select * from clerk where mobile=".$_POST["mobile"]." and ClerkID <>'$uid' ");
    $mbil = @mysql_num_rows($resmbil);
 //echo $query;
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
	$vals = array('نام'=>$first_name,'نام خانوادگی'=>$last_name,'ایمیل'=>$Email
      ,'نام کاربری'=>$username ,'کلمه عبور'=>$password ,'تکرار کلمه عبور'=>$_POST["passwordr"]  
      ,'کد ملی'=>$melicode ,'تلفن همراه'=>$mobile ,'نقش کاربر'=>$RolesID
      ,'استان'=>$_POST["soo"],'دشت/شهرستان'=>$CityId  
            );
             $valid->addSource($vals);
             $valid->addRule('نام', 'persian_str', true, 1, 50, true)
                   ->addRule('نام خانوادگی', 'persian_str', true,1, 50, true)
                   ->addRule('نام کاربری','string', true, 4, 8, true)
                   ->addRule('ایمیل', 'email', true, 1, 255, true)
                   ->addRule('تکرار کلمه عبور', 'match', true, $password, 255, true)
                   ->addRule('کد ملی', 'melicode', true, 10, 10, true)
                   ->addRule('تلفن همراه', 'mobile', true, 10, 10, true)
                   ->addRule('نقش کاربر', 'string', true, '1', '50',true)
                   ->addRule('استان', 'string', true, '1', '50', true)
                   ->addRule('دشت/شهرستان', 'string', true,'1', '50',true);
                       
            $valid->run();
        
 /////////////////////////////////////////////////////////////////////////////////////////////
		 if($mobile!=$mobileold)
		    $password=rand_Pass();
    		$query = "
    		UPDATE clerk SET
    		NOC = '" . encrypt($username) . "', 
    		mobile = '" . $mobile . "', 
    		CPI = '" . encrypt($first_name). "', 
    		DVFS = '" . encrypt($last_name) . "', 
    		GE = '" . $gender . "',
    		city = '" . $RolesID . "',
    		MMC = '" . $DesignerCoID . "',
    		HW = '" . $operatorcoID . "',
    		BR = '" . $ProducersID . "',
    		CityId = '" . $CityId . "',
    		Disable = '" . $Disable . "',
    		email = '" .encrypt($Email) . "',
    		melicode = '" . $melicode . "',
    		WN = '" . encrypt($password) . "'
    		WHERE ClerkID = " . $uid . ";";
       $_SESSION['erors']='';
	   $_SESSION['usreror']='';
	   $_SESSION['mbileror']='';
	   //echo sizeof($valid->errors).' '.$duplicate.' '.$cumbil;
	   if((sizeof($valid->errors)== 0) && ($duplicate==0) && ($cumbil==0) )
	   	  $result = mysql_query($query);
	   else
	   {
	   	 if(sizeof($valid->errors) > 0)
	       $_SESSION['erors']=$valid->errors;
	     if($duplicate!=0)
	       $_SESSION['usreror']='نام کاربری تکراری است';
	     if($cumbil!=0)
	       $_SESSION['mbileror']='تلفن همراه تکراری است';
           
           
           echo $_SESSION['erors']."<br>";
           echo $_SESSION['usreror']."<br>";
           echo $_SESSION['mbileror']."<br>";
           
	   }
       //echo $query;exit;
            $query = "SELECT clerk.* FROM clerk WHERE 
            substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$username',4,(substr('$username',1,3)-5)*3) 
            and substr(CPI,4,(substr(CPI,1,3)-5)*3)=substr('$first_name',4,(substr('$first_name',1,3)-5)*3) 
            and substr(DVFS,4,(substr(DVFS,1,3)-5)*3)=substr('$last_name',4,(substr('$last_name',1,3)-5)*3)  
            and substr(WN,4,(substr(WN,1,3)-5)*3)=substr('" . encrypt($password) . "',4,(substr('" . encrypt($password) . "',1,3)-5)*3) 
            
            and  ClerkID = " . $uid . ";";
            $result1 = mysql_query($query);

        
		$userNOC=$_POST["username"];
		$userCPI=$_POST["first_name"];
		$userDVFS=$_POST["last_name"];
		$userEmail=$_POST["Email"];
		$usermelicode=$_POST["melicode"];
		$userGE=$gender;
		$usermobile=$mobile;
		$usercity=$RolesID;
		$userMMC=$DesignerCoID;
		$userHW=$operatorcoID;
		$userBR=$ProducersID;
		$userWN=$password;
    
    
    	
   
}


	if(isset($_POST["submitemail"]) || $emailsend==1 )
		{
    		
			$HOST="";
			

		require_once('d/class.phpmailer.php');
			$message1 ="
			<div dir='rtl'>
				<div style='background-color: #bbe1ff;text-align: center;padding:10px;margin-top:10%;'>
				<font color='red' style='font-family: Tahoma;font-size: 13px;'></font>
				<table style='background-color: #fff;' align='center'>
					<tr>
						<td colspan='2'>
							<center>
							<img src='trd.jpg' />
							<br />
							</center>
						</td>
					</tr>
					<tr>
						First Control Project Management of Irrigation
						<br/>
						اولین سامانه مدیریت کنترل پروژه طرحهای آب و خاک 
						<br/>
						اساخ
						<br/>
						نام کاربری : $userNOC
						<br/>
				";
				$message2 ="				
						کلمه عبور : $userWN
				";
				$message3 ="				
						کلمه عبور : ********
				";
				$message4 ="
						<br/>
							$HOST
		
					</tr>
				</table>
				</div>
			</div>";
				$message=$message1.$message2.$message4;
				
				$mail = new PHPMailer(true); 
				try {
				    
require_once('d/class.phpmailer.php');
$from = "toosraham@gmail.com";
$to='baghersalami@gmail.com';
$subject="sa";
$body="sa";
$mail = new PHPMailer();
$mail->IsSMTP(true); // SMT	P
$mail->SMTPAuth   = true;  // SMTP authentication
$mail->Mailer = "smtp";
$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       = 465;                    // set the SMTP port
$mail->Username   = "toosraham@gmail.com";  // SES SMTP  username
$mail->Password   = "0934302219";  // SES SMTP password
$mail->SetFrom($from, 'From Name');
						  $mail->AddAddress($to);
					 	  $mail->CharSet = 'UTF-8';
						  $mail->Subject = 'ارسال نام کاربری';
						  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; 
						  $mail->MsgHTML($body);
						  $rets=$mail->Send();
	echo $rets; exit;
                        /*
						$from_name = "سامانه اساخ";
						$from_email = 'info@fcpm.ir';
						$toosraham = 'toosraham@gmail.com';
						$username=$userCPI.' '.$userDVFS;
						$SaveTime=date('Y-m-d H:i:s');
						$SaveDate=date('Y-m-d');
						$mesg='ارسال نام کاربری:'.$username.' به '.$userEmail.' '.$userNOC.' '.$HOST;

						  $mail->AddReplyTo($from_email, $from_name);
						  $mail->SetFrom($from_email, $from_name);
						  $mail->AddAddress($userEmail);
					 	  $mail->CharSet = 'UTF-8';
						  $mail->Subject = 'ارسال نام کاربری';
						  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; 
						  $mail->MsgHTML($message);
						  $mail->Send();
	
				
						$query = "INSERT INTO smssent(kind,ClerkIDR,message,ErrorDescription,SaveTime,SaveDate,ClerkID) 
									VALUES(4,'".$uid."','$mesg','$ErrorDescription','$SaveTime','$SaveDate','$login_userid');";
									mysql_query($query);
						echo $message1.$message3.$message4."<br> پیام با موفقیت ارسال شد\n";
							echo " <td style=\"text-align: center;\" colspan=\"2\">
								<a href=\"./user_list.php\" ><b>برگشت</b></a>
								</td>";
						  */
                          return 1;
                          

				} 
				catch (phpmailerException $e) {
						echo $e->errorMessage(); 
					} catch (Exception $e) {
						echo $e->getMessage(); 
					}
				
		
    
				
		}
///////////////////////////////////////////////////////////////////////////////////////////////////	
?>
<!DOCTYPE html>
<html>
<head>
  	<title>ویرایش پروفایل</title>
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

<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="funcs.js"></script>
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
            <?php include('includes/header.php'); ?> 
			<!-- /header -->

			<!-- content -->
			<div id="content">
            <div style = "text-align:left;">
                            <a  href='user_list.php'>
                            <img style = "width: 4%;" src="img/Return.png" title='بازگشت' ></a>
                            
                          </div>
			
		
                <form action="user_edit.php" method="post" >
                   <table width="600" align="center" class="form">
                    <tbody>
                     <tr>
                      <td width="20%" class="label">نام کاربری:</td>
                      <td width="80%" class="data"><?php echo $userNOC; ?></td>
					    
					     <td colspan="1" class="label" >غیر&nbsp;فعال</td>
                     <td class="data" ><input name="Disable" type="checkbox" id="Disable"  <?php if ($Disable>0) echo "checked"; ?> /></td>
					
                   	   
                     </tr>
                      <td class="label">نام:</td>
                      <td class="data"><input name="first_name" type="text" class="textbox" id="first_name" value="<?php echo $userCPI; ?>" size="12" maxlength="50"  title="لطفا حروف فارسی وارد نمایید" required /></td>
                      <td class="label">نام خانوادگی:</td>
                      <td class="data"><input name="last_name" type="text" class="textbox" id="last_name" value="<?php echo $userDVFS; ?>" size="15" maxlength="50" title="لطفا حروف فارسی وارد نمایید" required  /></td>
                      <td class="label">جنسیت:</td>
                      <td class="data">
                       <input name="gender" type="radio" id="gender" value="0" <?php if ($userGE == 0) echo " checked"; ?>>زن
                       <input name="gender" type="radio" id="gender" value="-1" <?php if ($userGE == -1) echo " checked"; ?>>مرد
                      </td>
                      <td class="label">نام کاربری:</td>
                      <td class="data"><input name="username" type="text" class="textbox" id="username" dir="ltr" value="<?php echo $userNOC; ?>" size="12" maxlength="50" pattern="[a-zA-Z0-9]{4,8}"   title="نام کاربری حداقل 4 و حداکثر 8 کاراکتر انگلیسی" required/></td>
                     </tr>
                     
                      <?php if($login_RolesID==24) $auto='off';else $auto='on';  ?>  
                     <tr>
                      <td class="label">کلمه عبور:</td>
                      <td class="data"><input name="password" type="text" class="textbox" id="password" autocomplete="<?php echo $auto;?>"   value="<?php echo $userWN; 
                      
                      
                      ?>" dir="ltr" size="12" maxlength="20"  <?php print $readonly; ?> required title="کلمه عبور حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی"  /></td>
                      <td class="label">تکرار کلمه عبور:</td>
                      <td class="data"><input name="passwordr" type="text" class="textbox" id="passwordr" dir="ltr" value="<?php echo $userWN; ?>" size="12" maxlength="20" required onblur="return match_pass()" /><span id='rpass'></span></td>
					   <td class="label">کد ملی</td>
                      <td class="data"><input name="melicode" type="text" class="textbox" id="melicode" dir="rtl" value="<?php echo $usermelicode; ?>" size="10" maxlength="11" pattern="[0-9]{1,2}[0-9]{9}" title="(10 رقم)" required /></td>
                     
                      <td <?php echo $hide; ?> colspan="1" class="label">همراه(بدون صفرابتدا)</td>
                      <td <?php echo $hide; ?> class="data"><input name="mobile" type="text" class="textbox" id="mobile" dir="ltr" value="<?php echo $usermobile; ?>" size="10" maxlength="10" pattern="[9]{1}[0-9]{9}" title="(رقم10)" required /></td>
                     </tr>
                     
                    <?php
						 if ($login_designerCO==1) {$Role='';$ost='';}
					 else $ost ="and substring(tax_tbcity7digit.id,1,2)='$login_ostanId'";
 
                     
                     $query='select RolesID as _value,Title as _key from roles '. $strrol .' order by _key  COLLATE utf8_persian_ci';
					
					 echo "<tr><td>نقش کاربر</td><td>".$drop->dropDb2('RolesID','_key','_value',$query,$usercity,'required')."</td>"; 
                    
               
			   if ($login_RolesID==1 || $login_RolesID==20) {
					 $query='select DesignerCoID as _value,Title as _key from designerco  order by _key  COLLATE utf8_persian_ci';
    			   
    			   echo "<td>شرکت طراح</td><td>".$drop->dropDb('DesignerCoID','_key','_value',$query,$userMMC)."</td>"; 
                     
                    
					 $query='select operatorcoID as _value,Title as _key from operatorco  order by _key  COLLATE utf8_persian_ci';
    				 
    				 echo "<td>شرکت مجری</td><td>".$drop->dropDb('operatorcoID','_key','_value',$query,$userHW)."</td>"; 
        
                     
                     $query='select ProducersID as _value,Title as _key from producers  order by _key  COLLATE utf8_persian_ci';
    				
    				 echo "<td>فروشنده/تولیدکننده</td><td>".$drop->dropDb('ProducersID','_key','_value',$query,$userBR)."</td>"; 
                    

 }
									 
                     $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' 
					 $ost
					 order by _key  COLLATE utf8_persian_ci";
    				 $ID1 = get_key_value_from_query_into_array($query);
                     echo "</tr><tr><td>استان</td><td>".$drop->dropDb2('soo','_key','_value',$query,$soo,'required onclick="ajxcity(this.value)"')."</td>";
				
                    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring('$soo',1,2)
                    and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    				
                   
                     echo"<td>دشت/شهرستان</td><td id='tdcity'>".$drop->dropDb2('city','_key','_value',$query,$sos,'required')."</td>";
                    
                    
				 
				 print "
				 
	     <td  class='label'>ایمیل:</td>
                      <td colspan='3' class='data'><input 
                       name='Email' value='$userEmail' type='email' class='textbox' id='Email'  required  /></td>
                       <input name='uid' type='hidden' class='textbox' id='uid'  value='$uid'  />
                </tr>
                ";
                
    
			
					  ?>
					
					
                     
                    
                    </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="بروزرسانی" /></td>
					   <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      <td><input name="submitemail" type="submit" class="button" id="submitemail" 
					   onClick="return confirm('مطمئن هستید که ارسال شود  ؟');"
					  value="ارسال ایمیل" /></td>
					  
					  
					  
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

			<!-- sidebar -->
            <!-- /sidebar -->
            
            <!-- footer -->
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>