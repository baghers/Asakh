<?php 
/*

//appinvestigation/applicantstates_master.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicantstates.php
/appinvestigation/applicant_end.php
 
 
-
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

//print $login_CityId;
if ($login_Permission_granted==0) header("Location: ../login.php");

/*
17 ناظر مقیم
31 کارشناس آبرسانی
32 مدیر آبرسانی
*/
if (in_array($login_RolesID, array("17","31", "32"))) 
    $login_DesignerCoID='67';
    
$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) : $login_ostanId.'00000';
if ($g1id>0) $login_os=$g1id;

    
    if ($login_RolesID=='17') //ناضر مقیم
    $condition1=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) and applicantmaster.DesignerCoID=67";
    else
    if ($login_RolesID=='14') //ناظر عالی
    $condition1=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
    else if ($login_RolesID!='1')//مدیر پیگیری 
        $condition1=" and applicantmaster.DesignerCoID='$login_DesignerCoID' ";


if ($login_RolesID=='17') //ناضر مقیم
    $condition2=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) and applicantmaster.DesignerCoID=67";
    else
    if ($login_RolesID=='14') //ناظر عالی
    $condition2=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
    else if ($login_RolesID!='1')//مدیر پیگیری 
    $condition2=" and applicantmaster.operatorcoID='$login_OperatorCoID' ";
    /*
    applicantmaster جدول مشخصات طرح
    designerco جدول طراح ها
    operatorco جدول مجری ها
    DesignerCoID شناسه طراح
    operatorcoID شناسه پیمانککار
    cityid شناسه شهر
    */
$sql="SELECT distinct 'طراح' ltype,1 type, designerco.title tbltitle,applicantmaster.DesignerCoID ID
  from applicantmaster 
inner join designerco on designerco.DesignerCoid=applicantmaster.DesignerCoid
where  substring(applicantmaster.cityid,1,2)=substring('$login_os',1,2) $condition1 
union all 
SELECT distinct 'مجری' ltype,2 type, operatorco.title tbltitle,applicantmaster.operatorcoID ID from applicantmaster 
inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
where  substring(applicantmaster.cityid,1,2)=substring('$login_os',1,2) $condition2 ORDER BY type,tbltitle  COLLATE utf8_persian_ci ;";        

//print $sql;

$result = mysql_query($sql);
						try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست شرکت ها جهت مشاهده طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
	   
        window.location.href ='?g1id=' +document.getElementById('g1id').value;
	}

    
    </script>
    <!-- /scripts -->
</head>
<body >

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
                        
                        <h1 align="center">  لیست شرکت های مختلف جهت مشاهده طرح ها </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
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
                    if (strlen($enrolmsg)>0)
                    {
                        echo $enrolmsg;
                        exit;
                    }
                    
                print "</td>
                        </tr>
                   </tbody>
                </table>";
                if ($login_designerCO==1) 
                {
                    $ost='';
                }
                else
                {
                    $ost="and substring(clerk.cityid,1,2)='$login_ostanId'";
                }

                $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
                inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
                $ost
                order by _key  COLLATE utf8_persian_ci";
                $allg1id = get_key_value_from_query_into_array($sqlselect);
								try 
							  {		
								mysql_query($sqlselect);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                 print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
                 
                ?>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%"></th>
                        	<th width="15%">شرکت</th>
                        	<th width="40%">عنوان</th>
                        	<th width="7%"><?php if ($login_RolesID=='1') echo 'ثبت'; ?></th>
                        	<th width="7%"><?php if ($login_RolesID=='1') echo 'ورود'; ?></th>
                        	<th width="7%"><?php if ($login_RolesID=='1') echo 'تعدادطرح'; ?></th>
                        	<th width="7%"><?php if ($login_RolesID=='1') echo 'ثبت'; ?></th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    $sumrefreshnocnt=0;
                    $sumClerkActivity=0;
                    $sumsavescnt=0;
                    $sumcostsavescnt=0;
                    $sumloginhistorycnt=0;
                    $sumaplicantcountcnt=0;
                    $Total=0;
                    $rown=0;
                    while($row = mysql_fetch_assoc($result)){

                        $title = $row['tbltitle'];
                        $ID=$row['ID']."_".$row['type']."_".$login_os;
                        $rown++;
                        //if ($row['ClerkActivity']>=$row['savescnt'])
                       //     $transaction=$row['ClerkActivity']+$row['costsavescnt'];
                       // else 
                            $transaction=$row['savescnt']+$row['costsavescnt'];
                        
                        
?>                      

                        <tr>
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $row['ltype']; ?></td>
                            <td><?php echo $title; ?></td>
                            <td><?php if ($login_RolesID=='1')  echo $row['savescnt']; ?></td>
                            <td><?php if ($login_RolesID=='1')  echo $row['loginhistorycnt']; ?></td>
                            <td><?php if ($login_RolesID=='1')  echo $row['aplicantcountcnt']; ?></td>
                            <td><?php if ($login_RolesID=='1')  echo $transaction; ?></td>
                            <td><a href=<?php print "applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 50%;" src="../img/comment.png" title='  مشاهده طرح ها  ' ></a></td>
                            
                        </tr><?php
                        
                    $sumrefreshnocnt+=$row['refreshnocnt'];
                    $sumClerkActivity+=$row['ClerkActivity'];
                    $sumsavescnt+=$row['savescnt'];
                    $sumcostsavescnt+=$row['costsavescnt'];
                    $sumloginhistorycnt+=$row['loginhistorycnt'];
                    $sumaplicantcountcnt+=$row['aplicantcountcnt'];
                    $Total+=$transaction;
                    }
?>
                    <tr>
                            <td><?php if ($login_RolesID=='1') echo ''; ?></td>
                            <td><?php if ($login_RolesID=='1') echo ''; ?></td>
                            <td><?php if ($login_RolesID=='1') echo 'مجموع'; ?></td>
                            <td><?php if ($login_RolesID=='1') echo number_format($sumsavescnt); ?></td>
                            <td><?php if ($login_RolesID=='1') echo number_format($sumloginhistorycnt); ?></td>
                            <td><?php if ($login_RolesID=='1') echo number_format($sumaplicantcountcnt); ?></td>
                            <td><?php if ($login_RolesID=='1') echo number_format($Total); ?></td>
                            <td></td>
                            
                        </tr>
                        
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
