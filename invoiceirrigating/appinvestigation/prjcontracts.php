<?php

/*

//appinvestigation/prjcontracts.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
 -
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

//print $login_RolesID.'';
if ($login_Permission_granted==0) header("Location: ../login.php");

$mycond="";
if ($login_RolesID==10)
$mycond=" and designercocontract.DesignerCoID='$login_DesignerCoID'";

if (! $_POST)
{
$ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

/*

    ApplicantName عنوان طرح
    ApplicantFName نام متقاضی
    CPI نام کاربر
    DVFS نام خانوادگی کاربر
    ClerkID شناسه کاربر
    clerk جدول کاربران
    tax_tbcity7digit شهرها
    id شناسه شهر
    CountyName روستای طرح
    applicantmaster جدول مشخصات طرح
    ApplicantMasterID شناسه طرح
    operatorcoid شناسه پیمانکار
    DesignArea مساحت طرح
    Code سریال طرح

    */
    $querys = "SELECT ApplicantName,ApplicantFName,DesignArea,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID,Datebandp,CountyName,shahr.cityname 
    from applicantmaster 
    left outer join (select ApplicantMasterID,operatorcoID,ClerkID from operatorapprequest where state=1) reqwin on 
    reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID
    left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID
    left outer join tax_tbcity7digit shahr 
    on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

    where applicantmaster.ApplicantMasterID ='$ApplicantMasterID'  ";
    
    
   
						try 
							  {		
								 $results = mysql_query($querys);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $rows = mysql_fetch_assoc($results);
    $ApplicantName="$rows[ApplicantFName] $rows[ApplicantName] - $rows[DesignArea] هکتار شهرستان ".$rows['cityname'];
    
    /*
    contracttype نوع قراردادها
    contracttypeID شناسه نوع قرار داد
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    prjtypeid نوع پروژه
    designercocontract قراردادهای مشاورین
    designercocontract.No شماره
    designercocontract.contractDate تاریخ
    designercocontract.Title عنوان
    */
    $query = "SELECT distinct contracttype.contracttypeID,contracttype.Title contracttypeTitle,applicantmasterdetail.applicantmasterdetailID,
    applicantmasterdetail.prjtypeid,
    case applicantcontracts.applicantcontractsID>0 when 1 then designercocontract.designercocontractID else '' end designercocontractID,
    
    case applicantcontracts.applicantcontractsID>0 when 1 then concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title) else '' end
    designercocontractTitle
     
    FROM contracttype
    
    inner join applicantmasterdetail on   
    (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
    or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID') 
    
    inner join designercocontract on designercocontract.contracttypeID=contracttype.contracttypeID
    and designercocontract.prjtypeid=applicantmasterdetail.prjtypeid $mycond
    
    left outer join applicantcontracts on applicantcontracts.ApplicantMasterdetailID=applicantmasterdetail.ApplicantMasterdetailID
    and applicantcontracts.designercocontractID=designercocontract.designercocontractID
    
    order by contracttypeTitle,designercocontractID desc
    ";
   
        //print $query;
  					try 
							  {		
								 $result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }
                      
    if (!$ApplicantMasterID) header("Location: ../logout.php");
}

    $register = false;

if ($_POST){
    
    $applicantmasterdetailID = $_POST['applicantmasterdetailID'];
    $PriceListMasterID=$_POST["cont$row[contracttypeID]"];
    
    if ($applicantmasterdetailID>0)
    {
       // print "sa";exit;
       //applicantcontracts قراردادهای طرح
    mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$applicantmasterdetailID'");
            
    $query = "SELECT distinct contracttype.contracttypeID FROM contracttype";
   
					try 
							  {		
								 $result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }
   
     while($row = mysql_fetch_assoc($result))
     {
        if ($_POST["cont$row[contracttypeID]"]>0)
        {
            mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
            VALUES ('$applicantmasterdetailID','".$_POST["cont$row[contracttypeID]"]."','" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','$login_userid')  ");
        }
     }
     $register = true;        
    }

             
    }
	
	
        




?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت قرارداد های پروژه</title>
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
						$Serial = "";
						$ProducersID = "";
                        //header("Location: "."prjcontracts.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999));
                        
                        
                        
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
                <form action="prjcontracts.php" method="post"  onSubmit="return CheckForm()">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php 
                        echo "<tr>
                                <td class='f14_font' colspan=2>$ApplicantName</td></tr>";
                   
                         
                    $rown=0;
                    $oldcontracttypeID=0;
					
					if ($login_RolesID==10)
					
                    print "<div style = \"text-align:left;\"><a  href=../reports/reports_contract.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.
                    rand(10000,99999)."><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>"; 
					else
			        print "<div style = \"text-align:left;\"><a  href=applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.
                    rand(10000,99999)."><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>"; 
					
                    while($row = mysql_fetch_assoc($result))
                    {
                        if ($oldcontracttypeID==$row['contracttypeID']) continue;
                        else
                        $oldcontracttypeID=$row['contracttypeID'];
                         
                         $query="SELECT designercocontractID AS _value, 
                    substring(concat(designerco.Title,designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,150) AS _key
                                FROM designercocontract 
								left outer join designerco on designerco.DesignerCoID=designercocontract.DesignerCoID
                                where  designercocontract.contracttypeID='$row[contracttypeID]' and 
                                designercocontract.prjtypeid='$row[prjtypeid]' $mycond
								ORDER BY designerco.Title COLLATE utf8_persian_ci
                    ";
                    //echo $query;
        				 $ID = get_key_value_from_query_into_array($query);
                         
                        $rown++;
                        print "<tr>
                                <td class='f24_font'>$row[contracttypeTitle]</td>
                                
                                ".select_option("cont$row[contracttypeID]",'',',',$ID,0,'','','1','rtl',0,'',$row['designercocontractID'],"",'435')."
                                <input class='no-print' name=\"oldcont$row[contracttypeID]\" type='hidden' class='textbox' id=\"oldcont$row[contracttypeID]\"
                     value='$row[designercocontractID]'  />
                     
                                ";
                                
                                
                    if ($rown==1) print "<input class='no-print' name='applicantmasterdetailID' type='hidden' class='textbox' id='applicantmasterdetailID'
                     value='$row[applicantmasterdetailID]'  />";
                     echo "</tr>";
                     
                    }
                    
                    
                    
                    ?>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
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