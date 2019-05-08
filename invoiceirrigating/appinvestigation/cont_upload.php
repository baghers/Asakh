<?php 

/*

//appinvestigation/cont_upload.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicantstates.php
 -
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$permitrolsidforselectproposable = array("1","13","14");
 $permitrolsidforselectproposablevals=implode(",", $permitrolsidforselectproposable);
if ($login_Permission_granted==0) header("Location: ../login.php");
if (! $_POST)
{
$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$ApplicantMasterID=$linearray[0];//شناسه طرح
$type=$linearray[1];//نوع





/*
    invoicetiming جدول زمانبندی اجرای طرح ها
    ApproveA تایید ارسال لوله ها توسط بازرس
    BOLNO شماره بارنامه لوله
    ApproveP تاریخ اعلامی تولیدکننده جهت ارسال لوازم به محل پروژه
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    criditType تجمیع بودن یا نبودن طرح
    DesignSystemGroupsID نوع سیستم آبیاری
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
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
$query = "SELECT applicantmaster.*,operatorapprequesting.ApplicantMasterID operatorapprequestingApplicantMasterID
,appchangestateTD.Description DescriptionTD
,appchangestateTS.Description DescriptionTS
,appchangestateTD.SaveDate temporarydeliverydateTD
,appchangestateTS.SaveDate temporarydissdateTS
,appchangestateR.Description DescriptionR
,appchangestateTM.Description DescriptionTM ,appchangestateTM.SaveDate TechDate
,ostan.id ostanid,shahr.id shahrid,bakhsh.id bakhshid,applicantstates.applicantstatesID
,applicantmaster.ApplicantMasterIDmaster
,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
,max(applicantfreedetail.freestateID) freestate
,appchangestatestateno.stateno statenom,operatorapprequest.coef3,operatorapprequest.coef3changedescription 
,case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then applicantmasterdetail.ApplicantMasterIDsurat else 
applicantmasterdetail.ApplicantMasterIDmaster end amidmaster 
,applicantmasterdetail.ApplicantMasterID ApplicantMasterIDd,applicantmasterdetail.ApplicantMasterIDmaster ApplicantMasterIDop,
applicantmasterdetail.ApplicantMasterIDsurat ApplicantMasterIDoplist
 FROM applicantmaster 
left outer join applicantmasterdetail on 
(applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID'
or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid
left outer join appchangestate appchangestateR on appchangestateR.ApplicantMasterID='$ApplicantMasterID' and appchangestateR.applicantstatesID=24 
left outer join appchangestate appchangestateTM on appchangestateTM.ApplicantMasterID='$ApplicantMasterID' and appchangestateTM.applicantstatesID=1 
left outer join appchangestate appchangestateTS on appchangestateTS.ApplicantMasterID='$ApplicantMasterID' and appchangestateTS.applicantstatesID=34
 and appchangestateTS.stateno=(select max(stateno) from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID=34) 
left outer join appchangestate appchangestateTD on appchangestateTD.ApplicantMasterID='$ApplicantMasterID' and appchangestateTD.applicantstatesID=35 
and appchangestateTD.stateno=(select max(stateno) from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID=35)
left outer join operatorapprequest on operatorapprequest.ApplicantMasterID='$ApplicantMasterID' and state=1
left outer join (select distinct ApplicantMasterID from operatorapprequest)operatorapprequesting on 
operatorapprequesting.ApplicantMasterID='$ApplicantMasterID'
left outer join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno 
 on appchangestatestateno.ApplicantMasterID=applicantmaster.ApplicantMasterID
inner join appchangestate  on appchangestate.ApplicantMasterID=applicantmaster.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
left outer join applicantfreedetail on applicantfreedetail.ApplicantMasterID = applicantmaster.ApplicantMasterIDmaster
WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID';";

//print $query;exit;
$result = mysql_query($query);
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $resquery = mysql_fetch_assoc($result);
    $ApplicantMasterIDd=$resquery["ApplicantMasterIDd"];
    $ApplicantMasterIDop=$resquery["ApplicantMasterIDop"];
    $ApplicantMasterIDoplist=$resquery["ApplicantMasterIDoplist"];
    if ($resquery["operatorapprequestingApplicantMasterID"]>0 && !($resquery["coef3"]>0))
    {
        $inproposing=1;
        
    }
    $DesignerCoID=0;
    $operatorcoID=0;
    if ($resquery["ApplicantMasterIDd"]==$ApplicantMasterID)
        $DesignerCoID=$resquery["DesignerCoID"];
    else $operatorcoID=$resquery["operatorcoid"];
    
    
    $ids = $ApplicantMasterID."_".$type."_".$DesignerCoID."_".$operatorcoID;

if ($DesignerCoID>0) 
    $ID=$DesignerCoID.'_1';
else if ($operatorcoID>0) 
    $ID=$operatorcoID.'_2';
        
    
    $amidmaster=$resquery["amidmaster"];
    $coef3=$resquery["coef3"];
    $coef3changedescription=$resquery["coef3changedescription"];
    $applicantstatesID=$resquery["applicantstatesID"];
    $criditType=$resquery["criditType"];
	$Code = $resquery["Code"];
    $issurat=$resquery["issurat"];
	$SelectedYearID = $resquery["YearID"];
	$ApplicantName = $resquery["ApplicantName"];
	$ApplicantFName = $resquery["ApplicantFName"];
	$SelectedMonthID = $resquery["MonthID"];  
    $CostPriceListMasterID=$resquery['CostPriceListMasterID'];
	$DesignArea = $resquery["DesignArea"];
	$statenom = $resquery["statenom"];
	$ApplicantMasterIDmaster = $resquery["ApplicantMasterIDmaster"];
    $freestate = $resquery["freestate"];
	$proposestate = $resquery["proposestate"];
	
	$numfield2array = explode('_',$resquery["numfield2"]);
    $contletterno=$numfield2array[0];
    $contletterdate=$numfield2array[1];

 //print $ApplicantMasterID.'*'.$ApplicantMasterIDmaster;exit; 
 
	$BankCode = $resquery["BankCode"];
	$belaavaz = ($resquery["belaavaz"]);
    $numfield=$resquery["numfield"];
	$Debi = $resquery["Debi"];
	$DesignSystemGroupsID = $resquery["DesignSystemGroupsID"];
	$TransportCostTableMasterID = $resquery["TransportCostTableMasterID"];
	$RainDesignCostTableMasterID = $resquery["RainDesignCostTableMasterID"];
	$DropDesignCostTableMasterID = $resquery["DropDesignCostTableMasterID"];
    $soo=$resquery["ostanid"];
    $sos=$resquery["shahrid"];
    $sob=$resquery["bakhshid"];
   // $ADate=$resquery["ADate"];
    $RDate=$resquery["RDate"];
    if ($resquery["temporarydeliverydateTD"]<>"") $temporarydeliverydate=gregorian_to_jalali($resquery["temporarydeliverydateTD"]);
	if ($resquery["temporarydissdateTS"]<>"") $temporarydissdate=gregorian_to_jalali($resquery["temporarydissdateTS"]);
	$Descriptiontemporarydissdate=$resquery["DescriptionTS"];
    $Descriptiontemporarydeliverydate=$resquery["DescriptionTD"];
    
	if ($resquery["TechDate"]<>"")
    $TechDate=gregorian_to_jalali($resquery["TechDate"]);
    $DescriptionR=$resquery["DescriptionR"];
    $DescriptionTM=$resquery["DescriptionTM"];
    $creditsourceID=$resquery["creditsourceID"];
    $selfcashhelpdate=$resquery["selfcashhelpdate"];
    $selfcashhelpval=number_format($resquery["selfcashhelpval"]);
    $selfcashhelpdescription=$resquery["selfcashhelpdescription"];
    $letterno=$resquery["letterno"];
    $letterdate=$resquery["letterdate"];
    $sandoghcode=$resquery["sandoghcode"];
    
    if (strlen(trim($resquery["selfnotcashhelpdetail"]))>0)
    {
        $larr = explode('_',$resquery["selfnotcashhelpdetail"]);
        if ($larr[0]>0)
        $selfnotcashhelpval1=number_format($larr[0]);
        $selfnotcashhelpdate1=$larr[1]; 
        if ($larr[2]>0)  
        $selfnotcashhelpval2=number_format($larr[2]);
        $selfnotcashhelpdate2=$larr[3];
        if ($larr[4]>0)
        $selfnotcashhelpval3=number_format($larr[4]);
        $selfnotcashhelpdate3=$larr[5];
    }
    else
    {
        $selfnotcashhelpval1=number_format($resquery["selfnotcashhelpval"]);
        $selfnotcashhelpdate1=$resquery["selfnotcashhelpdate"];    
    }
    
    
    
    $DesignerCoIDnazer=$resquery["DesignerCoIDnazer"];
	if ($resquery["creditsourceID"]>0)
        $selectedcreditsourceID=$resquery["creditsourceID"];
    else $selectedcreditsourceID=4;    
    
    
    //print "salam $CostPriceListMasterID";
    $DesignerID=$resquery["DesignerID"];
    $private= $resquery['private'];
    if ($private>0)      
       $private="checked";
      
	$sumsurat=$resquery['LastTotal']; 
    $criditType= $resquery['criditType'];
    $criditTypes= $resquery['criditType'];
    if ($criditType>0)      
       $criditType="checked";
$sysbelaavaz=0;
                            
   // if (!$resquery["Code"]) header("Location: ../logout.php");
   
      
  
}


$register = false;

if ($_POST){
     if (!($login_userid>0)) header("Location: ../login.php");  		 
	   
       
	 
    $ids = $_POST['ids'];
    $ApplicantMasterIDd = $_POST['ApplicantMasterIDd'];
    $ApplicantMasterIDop = $_POST['ApplicantMasterIDop'];
    $ApplicantMasterIDoplist = $_POST['ApplicantMasterIDoplist'];
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];
    $type=$linearray[1];
    $DesignerCoID=$linearray[2];
    $operatorcoID=$linearray[3];

    if ($DesignerCoID>0) 
        $ID=$DesignerCoID.'_1';
    else if ($operatorcoID>0) 
        $ID=$operatorcoID.'_2';
    
    
    
   //  print $login_RolesID;
   //  print $operatorcoID;
   //  print_r($permitrolsidforselectproposable);

      if (!($_FILES["file1"]["error"] > 0)) 
        {   
            
            if (($_FILES["file1"]["size"] / 1024)>100)
            {
                print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
            $ext = end((explode(".", $_FILES["file1"]["name"])));
            foreach (glob("../../upfolder/contract/" . $ApplicantMasterIDd.'*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/contract/" .$ApplicantMasterIDd.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
            
        }

      if (!($_FILES["file2"]["error"] > 0)) 
        {   
            
            if (($_FILES["file2"]["size"] / 1024)>100)
            {
                print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
            $ext = end((explode(".", $_FILES["file2"]["name"])));
            foreach (glob("../../upfolder/contract/" . $ApplicantMasterIDop.'*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file2"]["tmp_name"],"../../upfolder/contract/" .$ApplicantMasterIDop.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
            
        }
      if (!($_FILES["file3"]["error"] > 0)) 
        {   
            
            if (($_FILES["file3"]["size"] / 1024)>100)
            {
                print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
            $ext = end((explode(".", $_FILES["file3"]["name"])));
            foreach (glob("../../upfolder/contract/" . $ApplicantMasterIDoplist.'*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file3"]["tmp_name"],"../../upfolder/contract/" .$ApplicantMasterIDoplist.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
            
        }  
            
        
    
        
    
            
        
        $register = true;

}



?>
<!DOCTYPE html>
<html>
<head>
	<title>بارگذاری اسناد طرح</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
    


    
            $(function() {
                $("#RDate, #simpleLabel").persiandatepicker();  
                $("#TechDate, #simpleLabel").persiandatepicker(); 
                $("#temporarydeliverydate, #simpleLabel").persiandatepicker(); 
                $("#temporarydissdate, #simpleLabel").persiandatepicker(); 
                $("#selfcashhelpdate, #simpleLabel").persiandatepicker();    
                $("#selfnotcashhelpdate, #simpleLabel").persiandatepicker();  
            });
            
            
            
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
        
        
    </script>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
				if ($_POST){
					if ($register){
						//echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Code = "";
						$YearID = "";
                        if ($type==2)
                        {
                            header("Location: applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                        }
                        else if ($type==1)
                        {
                            header("Location: allapplicantstates.php");
                        }
                        
                        else if ($type==3)
                        {
                            header("Location: allapplicantstatesop.php");
                        }
                        else if ($type==4)
                        {
                            header("Location: allapplicantstatesoplist.php");
                        }
                        
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
                <form action="cont_upload.php" method="post"  enctype="multipart/form-data" >
                   <table width="600" align="center" class="form">
                    <tbody>
                 <div style = "text-align:rigth;">
                 
				 <?php 
                    $permitrolsid = array("1","5", "19");
                    if (in_array($login_RolesID, $permitrolsid) && $DesignerCoID>0 )
                    {
					$imgfile='';
					$numname='';
			        $IDUser =$SelectedYearID.'p'.$ApplicantMasterID;
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/sandugh/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                     {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDU=$linearray[0];
                            $No=$linearray[1];
							$num=$linearray[2];
				            if (($IDU==$IDUser) && ($No==1) ) {$imgfile=$file;$numname=$num;}
			            }
				     }
                  ?> 
	<td colspan="5" class='data'><input type='file' name='filep' id='filep' value='123' >شماره نامه ارسال پرونده:
	<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\" 
			name='numname' type='text' class='textbox' id='numname' value='<?php echo $numname; ?>' size='10' maxlength='10' /></td>	
		<td><?php print '<img src='.'/upfolder/sandugh/'.$imgfile.' width=35 height=25>';?></td>
		<td> <input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>"></td>
		<td> <input type="hidden" name="path" value ="<?php echo $path; ?>"></td>
		<td> <input type="hidden" name="inproposing" value ="<?php echo $inproposing; ?>"></td>
        
        

          		<?php
                        echo "<a  target='".$target."' href='applicant_tosandogh.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).
                                    "'><img style = 'width: 5%;' 
                                    src='../img/mail_send.png' title=' نامه ارسال پرونده طرح به صندوق جهت تامین اعتبار '></a>";
                     
					}
                    $permitrolsid = array("1","5", "19","13","14");
                    if (in_array($login_RolesID, $permitrolsid))
                    {

					 
                         echo "<a  target='".$target."' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1_0_0_'.$applicantstatesID.rand(10000,99999)."'>
                            <img style = 'width: 5%;' src='../img/search_page.png' title=' ريز '></a>"; 
                    }
                    $permitrolsid = array("1","19");
                    if (in_array($login_RolesID, $permitrolsid))
                    
                    echo
                    "<a target='_blank' href='../insert/applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999)."'>
										<img style = 'width: 5%;' src='../img/file-edit-icon.png' title=' ویرایش طرح '></a>";
                      
                      $permitrolsid = array("1","14", "17","10","5","8","13","20","21","23");
                    if (in_array($login_RolesID, $permitrolsid)  && $issurat==1)
                    {
                        
                        echo "<a  target='".$target."' href='applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID."_5_".$applicantstatesID.rand(10000,99999).
                                    "'><img style = 'width: 5%;' 
                                    src='../img/folder_accept.png' title='صورتجلسه تحویل موقت'></a>";
                                    
                    }


$ID = $ApplicantMasterID.'_5_'.$DesignerCoID.'_'.$operatorcoid.'_'.$applicantstatesID.'_'.$ApplicantMasterIDmaster;
  // print $ID;exit;
 if ($ApplicantMasterIDmaster>0 && (in_array($applicantstatesID, array("40","45")) && in_array($login_RolesID, array("13","1","18"))))
                             {
                                if ($freestate=='' && $applicantstatesID==40)
                                    print " <a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('پیش فاکتور طرح تایید گردیده است. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 25px;' src='../img/next.png' title='برگشت به کارتابل'> </a>";
                                else if (($freestate!='143' && $applicantstatesID==45) || ($login_RolesID==18 && $applicantstatesID==45))
                                    print "<a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm(' صورت وضعیت طرح تایید گردیده است. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 25px;' src='../img/nextr.png' title='برگشت به کارتابل'> </a>";    
								 else 
                                    print "<a 
                                    href='allapplicantstatesoplist.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('امکان تغییر وضعیت وجود ندارد!');\"
                                    > <img style = 'width: 25px;' src='../img/nextr.png' title='برگشت به کارتابل'> </a>";    
                             }


					
                     ?>
                     <a  href=<?php 
                    if ($type==2)
                        {
                            print "applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); 
                        }
                        else if ($type==1)
                        {
                            print "allapplicantstates.php"; 
                        } 
                        else if ($type==3)
                        {
                            print "allapplicantstatesop.php"; 
                        } 
                        else if ($type==4)
                        {
                            print "allapplicantstatesoplist.php"; 
                        }
                    ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a>
					
					
                    </div>
                            
                    
                     <?php
                     
                     
                     $readonly="readonly";   
                         
                     print "
					 </tr>
                         <tr>
                          <td colspan='8' >نام خانوادگی:
                          <input  value='$ApplicantName' $readonly
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='ApplicantName' type='text' class='textbox' id='ApplicantName'    size='15'  />
						  
						  &nbsp;&nbsp;نام: 
						  <input  value='$ApplicantFName' $readonly
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='ApplicantFName' type='text' class='textbox' id='ApplicantFName'    size='15'  />
                       
					      &nbsp;&nbsp;مساحت (هکتار):
                          <input  value='$DesignArea' $readonly style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\"
                           name='DesignArea' type='text' class='textbox' id='DesignArea'  />
                          
						  &nbsp;&nbsp;دبی L/s:
                          <input  style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 65px\"
                           name='Debi'  value='$Debi' $readonly type='text' class='textbox' id='Debi'    /></td> </tr>
                         ";
                     
                     
                
                    
                     
                        ///////////////////فایل قرارداد/////////////////////////
                        
                        $fstr1="";
                        $fstr2="";
                        $fstr3="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/contract/';
                        $handler = opendir($directory);
        
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if ($ID==$ApplicantMasterIDd)
                                {
                                    $fstr1="<td><a target='_blank' href='../../upfolder/contract/$file' ><img style = 'width: 30%;' src='../img/accept.png' 
                                    title='اسکن' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>اسکن قرارداد با موفقیت بارگذاری شد.</font></td>
                                    ";
                                }
                                if ($ID==$ApplicantMasterIDop)
                                {
                                    $fstr2="<td><a target='_blank' href='../../upfolder/contract/$file' ><img style = 'width: 30%;' src='../img/accept.png' 
                                    title='اسکن' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>اسکن تحویل موقت با موفقیت بارگذاری شد.</font></td>
                                    ";
                                }
                                
                                if ($ID==$ApplicantMasterIDoplist)
                                {
                                    $fstr3="<td><a target='_blank' href='../../upfolder/contract/$file' ><img style = 'width: 30%;' src='../img/accept.png' 
                                    title='اسکن' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>اسکن تحویل دائم با موفقیت بارگذاری شد.</font></td>
                                    ";
                                }
                            }
                        }
                        //////////////////////////////////////////////////////
                        if ($DesignerCoID>0)
                          print " <tr>
                         <td>اسکن &nbspقرارداد:</td>
                         
                        <td colspan='1' class='label'> (حداکثر 100 کیلوبایت)</td>
                        <td colspan='1' class='data'><input type='file' name='file1' id='file1' ></td>
                        $fstr1
                        </tr>
                        ";
                        else 
                        print "
                        <tr>
                         <td>اسکن &nbspتحویل موقت:</td>
                         
                        <td colspan='1' class='label'> (حداکثر 100 کیلوبایت)</td>
                        <td colspan='1' class='data'><input type='file' name='file2' id='file2' ></td>
                        $fstr2
                        </tr>
                        
                        <tr>
                         <td>اسکن &nbspتحویل دائم:</td>
                         
                        <td colspan='1' class='label'> (حداکثر 100 کیلوبایت)</td>
                        <td colspan='1' class='data'><input type='file' name='file3' id='file3' ></td>
                        $fstr3
                        </tr>";
                     
                     
                     
                     echo "
                      
                      
                     </tr>
                     
                     
                     </tbody>
                    <tfoot>
                   
                    
                    
                     
                     
                      <td colspan='1'><input name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                      <td class='data'><input name='ids' type='hidden' class='textbox' id='ids'  value='$ids'  /></td>
                      <td class='data'><input name='issurat' type='hidden' class='textbox' id='issurat'  value='$issurat'  /></td>
                      <td class='data'><input name='applicantstatesID' type='hidden' class='textbox' id='applicantstatesID'  value='$applicantstatesID'  /></td>
                      <td class='data'><input name='ApplicantMasterIDd' type='hidden' class='textbox' id='ApplicantMasterIDd'  value='$ApplicantMasterIDd'  /></td>
                      <td class='data'><input name='ApplicantMasterIDop' type='hidden' class='textbox' id='ApplicantMasterIDop'  value='$ApplicantMasterIDop'  /></td>
                      <td class='data'><input name='ApplicantMasterIDoplist' type='hidden' class='textbox' id='ApplicantMasterIDoplist'  value='$ApplicantMasterIDoplist'  /></td>
                      <td class='data'><input name='ApplicantMasterIDmaster' type='hidden' class='textbox' id='ApplicantMasterIDmaster'  value='$ApplicantMasterIDmaster'  /></td>
                      
                      
                     </tr>
                     </tfoot>
                     ";

                    $oldval="
                    
                     <tr><td class='label'>تاریخ تایید کمیته فنی:</td>
                      <td class='data'><input placeholder='انتخاب تاریخ' name='ADate' type='text' class='textbox' id='ADate' 
                      value='$ADate' size='10' maxlength='10' /></td>
                     <span id='span2'></span>
                       <td  class='label'>توضیحات:</td><td colspan='5'><textarea id='DescriptionTM' name='DescriptionTM' rows='2'  cols='89' >$DescriptionTM</textarea></td>
                      ";


					  ?>

                     
                     
                    
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