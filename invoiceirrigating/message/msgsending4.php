<?php 
/*
message/msgsending4.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending4.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
 

if ($login_Permission_granted==0) header("Location: ../login.php");
$shown=0;//نمایش تمام پیغام ها
$str='';

/*
messages جدول پیغام ها
status وضعیت
Kind نوع
ReceiverID شناسه گیرنده
*/

if ($_POST['shown']=='on')  {$shown=1;}
if ($shown==1) $str.='';
 else $str.="and status=1 and ((Kind=1 and ReceiverID='$login_ProducersID' and ReceiverID>0) or 
								(Kind=2 and ReceiverID='$login_DesignerCoID' and ReceiverID>0) or 
								(Kind=3 and ReceiverID='$login_RolesID' and ReceiverID>0) or 
								(Kind=5 and ReceiverID='$login_userid' and ReceiverID>0) or 
								(Kind=4 and ReceiverID='$login_OperatorCoID' and ReceiverID>0))";
		
if ($_POST['Receiver']) {
if (strlen(trim($_POST['Receiver']))==0 || strlen(trim($_POST['Receiver']))<0 || strlen(trim($_POST['Receiver']))=='')
        $str.='';else 
        $str.=" and messages.ReceiverID='$_POST[Receiver]'"; 
}

if ($_POST['send']) {
if (strlen(trim($_POST['send']))==0 || strlen(trim($_POST['send']))<0 || strlen(trim($_POST['send']))=='')
        $str.='';else 
        $str.=" and messages.ClerkID='$_POST[send]'"; 
}

		
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
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
*/
$sql = "SELECT messages.*,clerk.CPI,DVFS,ifnull(designerco.Title,'') dTitle,ifnull(producers.Title,'') pTitle,ifnull(roles.Title,'') rTitle
FROM messages 
left outer join clerk on clerk.ClerkID=messages.ClerkID and substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)
left outer join designerco on designerco.DesignerCoID=clerk.MMC
left outer join producers on producers.ProducersID=clerk.BR
left outer join roles on roles.RolesID=clerk.city
  
where 1=1  $str
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
 if ($login_designerCO==1) $hide =''; else {$hide = "style='display: none;'";}                          
  $ID8[' ']=' ';
  $ID9[' ']=' ';
$dasrow=0;

while($row2 = mysql_fetch_assoc($result)){
 $sender=decrypt($row2['CPI'])." ".decrypt($row2['DVFS'])." - ".$row2['dTitle']." ".$row2['pTitle']." ".$row2['rTitle'];
 $ID8[trim($sender)]=trim($row2['ClerkID']);
 $ID9[trim($row2['ReceiverID'])]=trim($row2['ReceiverID']);
 $dasrow=1;
}           
 $ID8=mykeyvalsort($ID8);
 $ID9=mykeyvalsort($ID9);
 if ($dasrow)  mysql_data_seek($result, 0 );
       
//$send = get_key_value_from_query_into_array($query);

 
 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پیغام ها</title>
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
                <form action="msgsending4.php" method="post" >
            
                <table id="records" width="95%" align="center" >
                    <thead>
					                    
                        <tr>
                            <td>
                                                   

                       <tr>
                        	<th >شماره</th>
                        	<th >فرستنده</th>
                            <th >عنوان</th>
                            <th >شرح</th>
                            <th  <?php echo $hide; ?>>گیرنده</th>
                            <th >تاریخ</th>
	                        <th ></th>
				       
				                       
							  
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
				   
                   <tr <?php echo $hide; ?>>
				    <td colspan="1"> </td>
					
				   <?php print select_option('send','',',',$ID8,0,'','','1','rtl',0,'',$sendid,'','150');?>
				    <td colspan="1"> </td>
					<td colspan="1"> </td>
					
                    <?php print select_option('Receiver','',',',$ID9,0,'','','1','rtl',0,'',$Receiveid,'','50');?>
					 <td colspan="1"> <input name="submit" type="submit" class="button" id="submit" size="10" value="جستجو" /></td>
					  <td class="data" ><input name="shown" type="checkbox" id="shown" <?php if ($shown>0) echo "checked"; ?> /></td>
       	               
				    </tr>            
                    
                                
            <?php
			
                    if ($result)
                    while($row = mysql_fetch_assoc($result))
					{
						$sender=decrypt($row['CPI'])." ".decrypt($row['DVFS'])."<br> ".$row['dTitle']." ".$row['pTitle']." ".$row['rTitle'];
                        $msgHeader = $row['Header'];
						$comments = $row['comments'];
						$SaveDate = $row['SaveDate'];
                        $ID = $row['MessagesID'];
						$Receiver=$row['ReceiverID'];
						
			?>                      
                        <tr>    
                            <td><?php echo $ID; ?></td>
                        	<td><?php echo $sender; ?></td>
                        	<td><?php echo $msgHeader; ?></td>
					        <td ><?php echo $comments; ?></td>
						    <td <?php echo $hide; ?>><?php echo $Receiver; ?></td>
                            <td><?php echo $SaveDate; ?></td>
                            <td><a href=<?php print "msgsending4_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 80%;" src="../img/mail.png" title=' مشاهده پیام ' ></a></td>
                        </tr>
			<?php
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
