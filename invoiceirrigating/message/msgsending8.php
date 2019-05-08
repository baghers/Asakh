<?php

/*
message/msgsending8.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending8.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
 
$register = false;
$uid=$_GET["uid"];

if ($_POST){
        /*
messages جدول پیغام ها
status وضعیت
Kind نوع
ReceiverID شناسه گیرنده
Header عنوان
comments شرح
MessagesIDReply کاربر پاسخ دهنده
*/
	$_POST['comments']='ایمیل: '.$_POST['email'].' همراه: '.$_POST['mobile'].'&#10;'.$_POST['comments'];      	
	if (($_POST['RID']>0) && $_POST['comments'] != "" && $login_userid>0)
    {
        $query = "INSERT INTO messages (Header, Kind, comments, status, ReceiverID, MessagesIDReply,SaveTime,SaveDate,ClerkID) 
            VALUES( 
            '$_POST[msgHeader]'
            , '5', 
            '$_POST[comments]'
            , '1', '$_POST[RID]', '0' 
            , '" . date('Y-m-d H:i:s'). "','".gregorian_to_jalali(date('Y-m-d'))."','$login_userid');";
	
    		
								try 
								  {		
									  	   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		//header("Location: clerk.php");
			$register = true;
			//print $query;exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>ارسال پیام</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
				if ($_POST)
				{
					if ($register)
					{
							if ($_POST['uid']==2)	header('Location: msgsending7.php');
							echo '<p class="note">ثبت با موفقيت انجام شد</p>';
							//header("Location: msgsending8.php");
					}
					else
					{
							if ($_POST['uid']==2)	header('Location: msgsending8.php?uid=2');
							echo '<p class="error">خطا در ثبت...</p>';
					}
				}
?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="msgsending8.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    
                            
                     <?php
	              if ($login_RolesID==1) {$str=' ';} else {$str='clerkID<>4 and';} 
$IDn='';$value="";
if ($uid==2) {$value="گزارش خطا";$str='clerkID=22 and';$IDn=22;}


					 $query="select clerkID,clerk.CPI,DVFS from clerk 
					 where $str substring(cityid,1,2)=substring('$login_CityId',1,2)";
                     $result = mysql_query($query);
					 //print $query;
                     $ID[' ']=' ';
                     while($row = mysql_fetch_assoc($result))
                     $ID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                     $ID=mykeyvalsort($ID);
                     print "</tr><tr>".select_option('RID','گیرنده:',',',$ID,0,'','','1','rtl',0,'',$IDn,'','','');
					 
					 
					 $query="select concat(0,mobile) mobile,email from clerk
					 where clerkID=$login_userid ";
					 $result = mysql_query($query);
					 $row = mysql_fetch_assoc($result);
					 $mobile=$row['mobile'];
					 $email=decrypt($row['email']);
					 
					 
					 
				  ?>

                     <tr>
                      <td class="label">عنوان:</td>
                      <td class="data"><input name="msgHeader" type="text" class="textbox" id="msgHeader"    size="30" maxlength="50" value="<?php print $value; ?>" /></td>
                      <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"    size="5" maxlength="5" value="<?php print $uid; ?>" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">متن:</td>
                      <td class="data">
                      <textarea  name="comments" id="comments"  class="textbox" maxlength="1000" cols="100" rows="6"></textarea>
                      </td>
                     </tr>
                     
                     <tr>
                      <td class="label"> همراه:</td>
                      <td class="data"><input name="mobile" type="textbox" class="textbox" id="mobile"    size="30" maxlength="11" value="<?php print $mobile; ?>" /></td>
			         </tr>
					 
					 <tr>
                      <td class="label">ایمیل:</td>
				      <td class="data"><input name="email" type="textbox" class="textbox" id="email"    size="30" maxlength="50" value="<?php print $email; ?>" /></td>
			    	 </tr>
					  
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ارسال" /></td>
                     </tr>
                    </tfoot>
                   </table>
                  </form>
            </div>
			<!-- /content -->

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>