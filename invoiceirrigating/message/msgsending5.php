<?php 
/*
message/msgsending5.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending5.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

 
if ($login_Permission_granted==0) header("Location: ../login.php");

//----------
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

$sql = "SELECT messages.*,clerk.CPI,DVFS,ifnull(designerco.Title,'') dTitle,ifnull(producers.Title,'') pTitle,ifnull(roles.Title,'') rTitle
 FROM messages
inner join clerk on clerk.ClerkID=messages.ClerkID  and substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)
left outer join designerco on designerco.DesignerCoID=clerk.MMC
left outer join producers on producers.ProducersID=clerk.BR
left outer join roles on roles.RolesID=clerk.city
  
where status=2 and ((Kind=1 and ReceiverID='$login_ProducersID' and ReceiverID>0) or 
(Kind=2 and ReceiverID='$login_DesignerCoID' and ReceiverID>0) or 
(Kind=3 and ReceiverID='$login_RolesID' and ReceiverID>0) or 
(Kind=5 and ReceiverID='$login_userid' and ReceiverID>0) or 
(Kind=4 and ReceiverID='$login_OperatorCoID' and ReceiverID>0))
order by SaveDate desc";
		  		           	try 
								  {		
									  	  	  $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }


?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پیغام های بایگانی شده</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    </script>
    <!-- /scripts -->
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="25%">فرستنده</th>
                            <th width="15%">عنوان</th>
                            <th width="55%">شرح</th>
                            <th width="10%">تاریخ</th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $sender=decrypt($row['CPI'])." ".decrypt($row['DVFS'])." ".$row['dTitle']." ".$row['pTitle']." ".$row['rTitle'];
                        $msgHeader = $row['Header'];
						$comments = $row['comments'];
						
                        $SaveDate = $row['SaveDate'];
                        $ID = $row['MessagesID'];
?>                      
                        <tr>    
                            <td><?php echo $sender; ?></td>
                            <td><?php echo $msgHeader; ?></td>
                            <td><?php echo $comments; ?></td>
                            <td><?php echo $SaveDate; ?></td>
                            <td><a href=<?php print "msgsending5_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 20px" src="../img/mail_lock.png" title=' مشاهده پیام ' ></a></td>
                        </tr><?php
                    }
?>
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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
