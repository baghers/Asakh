<?php 
/*
message/msgsending7.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending7.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

 
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
//----------
$stateid=1;
if (!$_POST)
    $str.=" and messages.status='1'";
if (strlen(trim($_POST['send']))>0)
        $str.=" and messages.ReceiverID='$_POST[send]'";
        
if (strlen(trim($_POST['state']))>0)
{
    $stateid=$_POST['state'];
    $str.=" and messages.status='$_POST[state]'";
}
        
            
$sql = "SELECT messages.*,clerk.CPI,DVFS,
case Kind 
when 1 then producers.title 
when 2 then designerco.title 
when 3 then roles.title 
when 4 then operatorco.title 
when 5 then '' 
end  sender FROM messages
left outer join clerk on clerk.ClerkID=messages.ReceiverID  and substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)
left outer join designerco on designerco.DesignerCoID=messages.ReceiverID
left outer join producers on producers.ProducersID=messages.ReceiverID
left outer join roles on roles.RolesID=messages.ReceiverID
left outer join operatorco on operatorco.operatorcoID=messages.ReceiverID
  
where  messages.clerkid='$login_userid' $str
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


//print $sql;

$query="
select 'خوانده نشده' _key,1 as _value union all 
select 'خوانده شده' _key,2 as _value ";

$state = get_key_value_from_query_into_array($query);

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پیغام های بایگانی شده</title>
<meta http-equiv="X-Frame-Options" content="deny" />
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
                <form action="msgsending7.php" method="post">
                <?php require_once('../includes/csrf_pag.php'); ?>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">شماره</th>
                        	<th width="20%">گیرنده</th>
                            <th width="15%">عنوان</th>
                            <th width="45%">شرح</th>
                            <th width="10%">تاریخ</th>
                            <th width="20%">وضعیت</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                    
                   <tbody>
                   <tr>
				   <td colspan="1"></td>
				   <?php print select_option('send','',',',$send,0,'','','1','rtl',0,'',$sendid,'','150');?>
                    <td colspan="3"></td>
                    <?php print select_option('state','',',',$state,0,'','','1','rtl',0,'',$stateid,'','100');?>
                    <td colspan="2"> <input name="submit" type="submit" class="button" id="submit" size="10" value="جستجو" /></td>
                    </tr>            
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){
                        if ($row['Kind']<>5)
                        $sender = $row['sender'];
                        else
                        $sender = decrypt($row['CPI'])." ".decrypt($row['DVFS']);
                        
                        $msgHeader = $row['Header'];
						$comments = $row['comments'];
						
                        $SaveDate = $row['SaveDate'];
                        $ID = $row['MessagesID'];
?>                      
                        <tr style="height: 20px;">    
                            <td><?php echo $ID; ?></td>
                            <td><?php echo $sender; ?></td>
                            <td><?php echo $msgHeader; ?></td>
                            <td><?php echo $comments; ?></td>
                            <td><?php echo $SaveDate; ?></td>
                            <td><?php if ($row['status']==2) echo 'خوانده شده'; ?></td>
                            <td><a href=<?php print "msgsending7_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 50%;" src="../img/mail_lock.png" title=' مشاهده پیام ' ></a></td>
                            
                        <?php
                        if  ($row['status']==2)
                        echo "<td/>";
                        else echo "<td><a 
                                        href='msgsending7_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'
                                        onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                                        > <img style = 'width: 50%;' src='../img/delete.png' title='حذف'> </a></td>";
                        echo "</tr>";
                    }
?>
                   
                    </tbody>
                   
                </table>
                </form>
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
