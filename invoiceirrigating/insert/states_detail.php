<?php 

/*

insert/states_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/foundation_applicant_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='applicantmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$ApplicantMasterID=$linearray[0];//شناسه طرح
$type=$linearray[1];//نوع
//print $type;
$DesignerCoID=$linearray[2];//طراح
$OperatorCoID=$linearray[3];//مجری
        
if ($DesignerCoID>0) 
    $ID=$DesignerCoID.'_1';
else if ($OperatorCoID>0) 
    $ID=$OperatorCoID.'_2';
    
             /* 
           applicantmaster جدول مشخصات طرح
           designerco جدول شرکت های طراح
           designer جدول طراحان
           clerk جدول کاربران
           applicantmaster جدول مشخصات طرح
           ApplicantMasterID شناسه طرح
           state=1 انتخاب شدن پیشنهاد توسط کشاورز
           appchangestate جدول تغییر وضعیت طرح
           stateno شماره تغییر وضعیت
           applicantstatesID شناسه تغییر وضعیت
        */ 
        
$sql = "SELECT appchangestate.*,applicantstates.title applicantstatestitle,clerk.CPI,clerk.DVFS,applicantmaster.DesignerCoID,applicantmaster.ApplicantName 
FROM appchangestate 
inner join applicantmaster on applicantmaster.applicantmasterid=appchangestate.applicantmasterid
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
inner join clerk on clerk.clerkid=appchangestate.clerkid
where appchangestate.ApplicantMasterID='$ApplicantMasterID'
order by appchangestate.stateno";

//print $sql;


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


?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیگیری طرح</title>
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
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">  پیگیری طرح <?php print $row['ApplicantName'] ?> </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <div style = "text-align:left;"><a href=<?php 
                          
                          if ($type==1)
                          print "applicant_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999);
                          else if ($type==2)
                          print "summaryinvoicemaster.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999);
                          else if ($type==3)
                          print "manualcostlist_applicant_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999);
                          else if ($type==4)
                          print "invoice_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999);
                          else if ($type==5)
                          print "foundation_applicant_list.php";
                          
                           ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th >ردیف</th>
                            <th >وضعیت</th>
                            <th >تاریخ</th>
                            <th >توضیحات</th>
                            <th >کاربر</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                    do{
                        $encrypted_string=$row['CPI'];
                        $encryption_key="!@#$8^&*";
                        $decrypted_string="";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                        $encrypted_string=$row['DVFS'];
                        $encryption_key="!@#$8^&*";
                        $decrypted_string.=" ";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
	
    
                        $name = $decrypted_string;
                        $applicantstatestitle = $row['applicantstatestitle'];
                        $SaveDate = $row['SaveDate'];
                        $stateno = $row['stateno'];
                        $Description = $row['Description'];
?>                      
                        <tr>
                            
                            <td><textarea id='Description' name='Description' rows='2'  cols='2' readonly="1"><?php echo $stateno; ?></textarea></td>
                            <td><textarea id='Description' name='Description' rows='2'  cols='30' readonly="1"><?php echo $applicantstatestitle; ?></textarea></td>
                            <td><textarea id='Description' name='Description' rows='2'  cols='10' readonly="1"><?php echo gregorian_to_jalali($SaveDate); ?></textarea></td>
                            <td><textarea id='Description' name='Description' rows='2'  cols='85' readonly="1"><?php echo $Description; ?></textarea></td>
                            <td><textarea id='Description' name='Description' rows='2'  cols='30' readonly="1"><?php echo $name; ?></textarea></td>
                        </tr><?php

                    }
                    while($row = mysql_fetch_assoc($result));

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
