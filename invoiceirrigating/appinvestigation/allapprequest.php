<?php 

/*

//appinvestigation/allapprequest.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود 
-
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
if ($login_Permission_granted==0) header("Location: ../login.php");

  if ($login_DesignerCoID>0 || $login_designerCO==0 ) 
        $condition1="where operatorapprequest.operatorcoID='$login_OperatorCoID'";
/*
 
    applicantmaster جدول مشخصات طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر    
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    applicantstates.title عنوان وضعیت پروژه
    hektar سطح پروژه
    prjtypeid نوع پروژه
    nazerID ناظر پروژه
    creditsourceTitle عنوان منبع تامین اعتبار
    ApplicantMasterIDmaster شناسه طرح اجرایی
    DesignerCoID شناسه مشاور طراح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    designsystemgroupsdetail جدول ریز سیستم های آبیاری
    appstatesee لیست وضعیت هایی که هر نقش می بیند
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح

    */    
    $sql = "SELECT applicantmaster.*,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
    ,designsystemgroups.title designsystemgroupstitle,operatorapprequest.*
    FROM applicantmaster 
    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    left outer join designer on designer.designerid=applicantmaster.designerid
    left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID
    
    $condition1 
    ORDER BY applicantmaster.ApplicantName COLLATE utf8_persian_ci ;";
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
  	<title>پیشنهاد قیمت طرح</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />

    <script>
function CheckForm()
{
    if (!(document.getElementById('file1').value != "">0))
    {
        alert('لطفا اسکن فایل پیشنهاد قیمت را انتخاب نمایید!');return false;
    }
  return true;  
}
    function fillform(Url)
    {
                    
                    var selectedBankcode=document.getElementById('Bankcode').value;
                    //alert(selectedBankcode.length);
                    if (selectedBankcode.length>0)
                    {
                        $("#loading-div-background").show();
                        $.post(Url, {selectedBankcode:selectedBankcode}, function(data){
                        $("#loading-div-background").hide();    
                        $('#tableproducers').html(data.boxstr);
                        //$('#Code').val(data.Code);
                        $('#ApplicantName').val(data.ApplicantName);
                        $('#DesignArea').val(data.DesignArea);
                        $('#designsystemgroupstitle').val(data.designsystemgroupstitle);
                        $('#shahrcityname').val(data.shahrcityname);
                        $('#designername').val(data.designername);
                        $('#applicantferestbaha').val(data.applicantferestbaha);
                        $('#Applicanttotal').val(data.Applicanttotal);
                        $('#ApplicantMasterID').val(data.ApplicantMasterID);
                           }, 'json');                           
                    }
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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="apprequest.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">کد رهگیری</th>
                            <th width="15%">متقاضي</th>
                            <th width="5%">Ha</th>
                            <th width="5%">سیستم آبیاری</th>
                            <th width="10%">شهرستان</th>
                            <th width="5%">طراح</th>
                            <th width="10%">م فهرست بهای طرح</th>
                            <th width="10%">م جمع کل طرح</th>
                            <th width="5%">م فهرست بهای پیشنهادی</th>
                            <th width="10%">م جمع کل پیشنهادی</th>
                            <th width="10%">اسکن</th>
                            <th width="5%"></th>
                        </tr> 
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                               
                   <?php             
                     print "
                            <td class='data'><div id='divBankcode'>
                            <input name='Bankcode' type='text' class='textbox' id='Bankcode'  style='width: 90px' 
                            onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/apprequest_jr.php');\"/></div>
                            </td>
                     
                            
                            <td class='data'><div id='divApplicantName'>
                            <input name='ApplicantName' readonly type='text' class='textbox' id='ApplicantName'  style='width: 100px' /></div>
                            </td>
                            
                            <td class='data'><div id='divDesignArea'>
                            <input name='DesignArea' readonly type='text' class='textbox' id='DesignArea'  style='width: 50px' /></div>
                            </td>
                            
                            <td class='data'><div id='divdesignsystemgroupstitle'>
                            <input name='designsystemgroupstitle' readonly type='text' class='textbox' id='designsystemgroupstitle'  style='width: 75px' /></div>
                            </td>
                            
                            <td class='data'><div id='divshahrcityname'>
                            <input name='shahrcityname' readonly type='text' class='textbox' id='shahrcityname'  style='width: 80px' /></div>
                            </td>
                            
                            
                            <td class='data'><div id='divdesignername'>
                            <input name='designername' readonly type='text' class='textbox' id='designername'  style='width: 75px' /></div>
                            </td>
                            
                            <td class='data'><div id='divapplicantferestbaha'>
                            <input name='applicantferestbaha' readonly type='text' class='textbox' id='applicantferestbaha'  style='width: 75px' /></div>
                            </td>
                     
                            <td class='data'><div id='divApplicanttotal'>
                            <input name='Applicanttotal' readonly type='text' class='textbox' id='Applicanttotal'  style='width: 75px' /></div>
                            </td>
                                   
                            <td class='data'><div id='divcostprice'>
                            <input name='costprice'  type='text' class='textbox' id='costprice'  style='width: 75px' /></div>
                            </td>
                            
                            <td class='data'><div id='divprice'>
                            <input name='price'  type='text' class='textbox' id='price'  style='width: 75px' /></div>
                            </td>
                            <td colspan='1' class='data'><input type='file' name='file1' id='file1' style='width: 80px'></td>
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                            <td class='data'><input name='ApplicantMasterID' type='hidden' readonly class='textbox' id='ApplicantMasterID' /></td>
                    ";
                    
                    
         ?>
                          <tr>
                            <th width="15%" colspan="2">متقاضي</th>
                            <th width="5%">Ha</th>
                            <th width="5%">سیستم آبیاری</th>
                            <th width="10%">شهرستان</th>
                            <th width="5%">طراح</th>
                            <th width="5%">م فهرست بهای پیشنهادی</th>
                            <th width="10%">م جمع کل پیشنهادی</th>
                            <th width="5%"></th>
                        </tr>
           
         
         <?php
                     
                    
                    $Total=0;
                    $rown=0;
                    while($resquery = mysql_fetch_assoc($result)){

                            $ApplicantName = $resquery["ApplicantName"];
                        	$DesignArea = $resquery["DesignArea"];
                            $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                            $shahrcityname = $resquery["shahrcityname"];
                            $designername = $resquery["designername"];
                            $rown++;
?>                      
                        <tr>
                            
                            <td colspan="1"><?php echo $rown; ?></td>
                            <td><?php echo $ApplicantName; ?></td>
                            <td><?php echo $DesignArea; ?></td>
                            <td><?php echo $designsystemgroupstitle; ?></td>
                            <td><?php echo $shahrcityname ?></td>
                            <td colspan="1"><?php echo $designername ?></td>
                            <td><?php echo $resquery["costprice"] ?></td>
                            <td><?php echo $resquery["price"] ?></td>
                        </tr><?php

                    }

?>

                        
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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
