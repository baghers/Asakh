<?php

/*

insert/applicantstates_detail.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
appinvestigation/applicantstates.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='applicantmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");

$ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

/*
appchangestate جدول تغییر وضعیت طرح ها
clerk جدول کاربران
first_name نام
ApplicantName عنوان پروژه
applicantmaster جدول مشخصات طرح
applicantstates جدول وضعیت های طرح
stateno شماره وضعیت
*/ 
        
$sql = "SELECT appchangestate.*,applicantstates.title applicantstatestitle,CONCAT(CONCAT(clerk.first_name,' '),last_name) name,applicantmaster.ApplicantName 
FROM appchangestate 
inner join applicantmaster on applicantmaster.applicantmasterid=appchangestate.applicantmasterid
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
inner join clerk on clerk.clerkid=appchangestate.clerkid
where appchangestate.ApplicantMasterID='$ApplicantMasterID'
order by appchangestate.stateno";


	  	  					try 
								  {		
									       $result = mysql_query($sql.$login_limited);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$row = mysql_fetch_assoc($result)

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
                          <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxcode; ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                          <div style = "text-align:left;"><a href=<?php print "applicantstates.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
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
                        	<th width="5%">ردیف</th>
                            <th width="10%">وضعیت</th>
                            <th width="5%">تاریخ</th>
                            <th width="10%">کاربر</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                    do{
                        $name = $row['name'];
                        $applicantstatestitle = $row['applicantstatestitle'];
                        $SaveDate = $row['SaveDate'];
                        $stateno = $row['stateno'];
?>                      
                        <tr>
                            
                            <td><?php echo $stateno; ?></td>
                            <td><?php echo $applicantstatestitle; ?></td>
                            <td><?php echo gregorian_to_jalali($SaveDate); ?></td>
                            <td><?php echo $name; ?></td>
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
