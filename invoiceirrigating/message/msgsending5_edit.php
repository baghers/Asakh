<?php
/*
message/msgsending5_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending5.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

$MessagesID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
 

    /*
messages جدول پیغام ها
clerk جدول کاربران
designerco جدول طراحان
producers جدول تولیدکنندگان
roles جدول نقش ها
CPI نام کاربر
DVFS نام خانوادگی کاربر
producers.Title عنوان تولیدکننده
roles.Title عنوان نقش
messages جدول پیغام ها
status وضعیت
Kind نوع
ReceiverID شناسه گیرنده
Header عنوان
comments شرح
MessagesIDReply کاربر پاسخ دهنده
*/

$register = false;

if ($_POST){
    
        	
	if ($_POST['MessagesID'] != "")
    {
	
        $query = "
		UPDATE  messages SET
		status = '1'
        WHERE MessagesID = " . $_POST['MessagesID'] . ";";
	
    	
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
        //print $query;
	//exit;
	
	}
}
else
{
    $sql = "SELECT messages.*,
    clerk.CPI,DVFS,ifnull(designerco.Title,'') dTitle,ifnull(producers.Title,'') pTitle,ifnull(roles.Title,'') rTitle  FROM messages
    inner join clerk on clerk.ClerkID=messages.ClerkID  and substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)
    left outer join designerco on designerco.DesignerCoID=clerk.MMC
    left outer join producers on producers.ProducersID=clerk.BR
    left outer join roles on roles.RolesID=clerk.city      
    left outer join operatorco on operatorco.operatorcoID=clerk.HW   
     where MessagesID='$MessagesID'";
    		  		           	try 
								  {		
									  	  	  $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $row = mysql_fetch_assoc($result);
    
    $sender=decrypt($row['CPI'])." ".decrypt($row['DVFS'])." ".$row['dTitle']." ".$row['pTitle']." ".$row['rTitle'];
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>مشاهده پیام</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						header("Location: msgsending5.php");
                        
					}else{
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
                <form action="msgsending5_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href="msgsending5.php"><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
                    
                     
                     <tr>
                      <td class="label">تاریخ:</td>
                      <td class="data"><input name="SaveDate" type="text" class="textbox" id="SaveDate" value="<?php echo $row['SaveDate']; ?>"    size="50" maxlength="50" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">فرستنده:</td>
                      <td class="data"><input name="sender" type="text" class="textbox" id="sender" value="<?php echo $sender; ?>"    size="50" maxlength="50" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">عنوان:</td>
                      <td class="data"><input name="msgHeader" type="text" class="textbox" id="msgHeader" value="<?php echo $row['Header']; ?>"    size="50" maxlength="50" /></td>
                     </tr>
                     
                     
                     <tr>
                      <td class="label">متن:</td>
                      <td class="data">
                      <textarea  name="comments" id="comments"  class="textbox"  maxlength="1000" cols="100" rows="6"><?php echo $row['comments']; ?></textarea>
                      </td>
                     </tr>
                     
                     
                     <tr>
                      <td class="data"><input name="MessagesID" type="hidden" class="textbox" id="MessagesID"  value="<?php echo $row['MessagesID']; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="انتقال به پیام های جاری" /></td>
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