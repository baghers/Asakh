<?php 

/*

insert/chart_applicant_timing.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/applicant_timing.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>

<?php

if ($_GET) {
	$uid=$_GET["uid"];
	$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	$ids2 = substr($_GET["uid"],-1);
	$linearray = explode('_',$ids);
	$ApplicantMasterID=$linearray[0];
	$typechart=$linearray[1];
	}
//print  $ids2;
 //if ($ids2==2) {

    /*
    applicantmaster جدول مشخصات طرح
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    proposestatep وضعیت پیشنهاد قیمت
    ApplicantMasterID شناسه طرح
    creditsourcetitle عنوان منبع تامین اعتبار
    credityear سال اعتبار طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    ADate تاریخ شروع پیشنهاد قیمت
    BankCode کد رهگیری طرح
    designername عنوان طراح
    designsystemgroupstitle سیستم آبیاری
    shahrcityname نام شهر
    designer.LName نام خانوادگی طراح
    designer.FName نام طراح
    DesignArea مساحت طرح
    CityId شناسه شهر طرح
    designer جدول طراحان
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    operatorcoid شناسه پیمانکار
    DesignerCoID شناسه مشاور طراح
    applicantmasterdetail جدول ارتباطی طرح ها
    */  
     
$sql = "SELECT 
applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,
ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 
inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.ApplicantMasterID
left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID
inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid
left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID and 
applicanttiming.RoleID='2' where applicantmaster.ApplicantMasterID='$ApplicantMasterID'";

//}   else {
 
$sqln = "SELECT 
applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,
ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.ApplicantMasterID
left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID and 
applicanttiming.RoleID='10' where applicantmaster.ApplicantMasterID='$ApplicantMasterID'";

//case applicanttiming.RoleID when 2 then applicanttiming.RoleID='$login_RolesID' else applicanttiming.RoleID='10'end
//where applicantmaster.ApplicantMasterID='$ApplicantMasterID'";


//}

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
//print $sql;

$len1=0;$len2=0;$len3=0;$len4=0;$len5=0;$len6=0;$len7=0;$len8=0;$len9=0;$len11=0;$len12=0;$len13=0;$len14=0;
$lenn1=0;$lenn2=0;$lenn3=0;$lenn4=0;$lenn5=0;$lenn6=0;$lenn7=0;$lenn8=0;$lenn9=0;$lenn11=0;$lenn12=0;$lenn13=0;$lenn14=0;


if ($row['tahvildate']>0){
$tahvildate=gregorian_to_jalali($row['tahvildate']);
}

if ($row['pathend']>0 && $row['pathstart']>0){
$len1 = floor((strtotime($row['pathend']) - strtotime($row['pathstart']))/86400+1);
$min1 = floor((strtotime($row['pathstart']) - strtotime($row['pathstart']))/86400);
if ($row['pathend']=="" || $row['pathstart']=="" )  $min1 = 0; 
$max1 =  $len1 + $min1;
$pathstart=gregorian_to_jalali($row['pathstart']).' تا '.gregorian_to_jalali($row['pathend']);
$pathstartX=gregorian_to_jalali($row['pathstart']);
}
if ($row['transportend']>0 && $row['transportstart']>0){
$len2 = floor((strtotime($row['transportend']) - strtotime($row['transportstart']))/86400+1);
$min2 = floor((strtotime($row['transportstart']) - strtotime($row['pathstart']))/86400);
if ($row['transportend']=="" || $row['transportstart']=="")  $min2 = 0; 

$max2 =  $len2 + $min2;
$transportstart=gregorian_to_jalali($row['transportstart']).' تا '.gregorian_to_jalali($row['transportend']);

}
if ($row['drillingend']>0 && $row['drillingstart']>0){
$len3 = floor((strtotime($row['drillingend']) - strtotime($row['drillingstart']))/86400+1);
$min3 = floor((strtotime($row['drillingstart']) - strtotime($row['pathstart']))/86400);
if ($row['drillingstart']=="" || $row['drillingend']=="")  $min3 = 0; 
$max3 =  $len3 + $min3;
$drillingstart=gregorian_to_jalali($row['drillingstart']).' تا '.gregorian_to_jalali($row['drillingend']);

}
if ($row['rglazhend']>0 && $row['rglazhstart']>0){
$len4 = floor((strtotime($row['rglazhend']) - strtotime($row['rglazhstart']))/86400+1);
$min4 = floor((strtotime($row['rglazhstart']) - strtotime($row['pathstart']))/86400);
if ($row['rglazhstart']=="" || $row['rglazhend']=="")  $min4 = 0; 
$max4 =  $len4 + $min4;
$rglazhstart=gregorian_to_jalali($row['rglazhstart']).' تا '.gregorian_to_jalali($row['rglazhend']);

}
if ($row['intubationend']>0 && $row['intubationstart']>0){
$len5 = floor((strtotime($row['intubationend']) - strtotime($row['intubationstart']))/86400+1);
$min5 = floor((strtotime($row['intubationstart']) - strtotime($row['pathstart']))/86400);
if ($row['intubationstart']=="" || $row['intubationend']=="")  $min5 = 0; 
$max5 =  $len5 + $min5;
$intubationstart=gregorian_to_jalali($row['intubationstart']).' تا '.gregorian_to_jalali($row['intubationend']);

}
if ($row['pondend']>0 && $row['pondstart']>0){
$len6 = floor((strtotime($row['pondend']) - strtotime($row['pondstart']))/86400+1);
$min6 = floor((strtotime($row['pondstart']) - strtotime($row['pathstart']))/86400);
if ($row['pondstart']=="" || $row['pondend']=="")  $min6 = 0; 
$max6 =  $len6 + $min6;
$pondstart=gregorian_to_jalali($row['pondstart']).' تا '.gregorian_to_jalali($row['pondend']);

}
if ($row['pumpingstationend']>0 && $row['pumpingstationstart']>0){
$len7 = floor((strtotime($row['pumpingstationend']) - strtotime($row['pumpingstationstart']))/86400+1);
$min7 = floor((strtotime($row['pumpingstationstart']) - strtotime($row['pathstart']))/86400);
if ($row['pumpingstationstart']=="" || $row['pumpingstationend']=="")  $min7 = 0; 
$max7 =  $len7 + $min7;
$pumpingstationstart=gregorian_to_jalali($row['pumpingstationstart']).' تا '.gregorian_to_jalali($row['pumpingstationend']);

}
if ($row['soilpipeend']>0 && $row['soilpipestart']>0){
$len8 = floor((strtotime($row['soilpipeend']) - strtotime($row['soilpipestart']))/86400+1);
$min8 = floor((strtotime($row['soilpipestart']) - strtotime($row['pathstart']))/86400);
if ($row['soilpipestart']=="" || $row['soilpipeend']=="")  $min8 = 0; 
$max8 =  $len8 + $min8;
$soilpipestart=gregorian_to_jalali($row['soilpipestart']).' تا '.gregorian_to_jalali($row['soilpipeend']);

}
if ($row['networktestend']>0 && $row['networkteststart']>0){
$len9 = floor((strtotime($row['networktestend']) - strtotime($row['networkteststart']))/86400+1);
$min9 = floor((strtotime($row['networkteststart']) - strtotime($row['pathstart']))/86400);
if ($row['networkteststart']=="" || $row['networktestend']=="")  $min9 = 0; 
$max9 =  $len9 + $min9;
$networkteststart=gregorian_to_jalali($row['networkteststart']).' تا '.gregorian_to_jalali($row['networktestend']);

}
if ($row['soilintrenchend']>0 && $row['soilintrenchstart']>0){
$len10 = floor((strtotime($row['soilintrenchend']) - strtotime($row['soilintrenchstart']))/86400+1);
$min10 = floor((strtotime($row['soilintrenchstart']) - strtotime($row['pathstart']))/86400);
if ($row['soilintrenchstart']=="" || $row['soilintrenchend']=="")  $min10 = 0; 
$max10 =  $len10 + $min10;
$soilintrenchstart=gregorian_to_jalali($row['soilintrenchstart']).' تا '.gregorian_to_jalali($row['soilintrenchend']);

}
if ($row['dispersiveend']>0 && $row['dispersivestart']>0){
$len11 = floor((strtotime($row['dispersiveend']) - strtotime($row['dispersivestart']))/86400+1);

$min11 = floor((strtotime($row['dispersivestart']) - strtotime($row['pathstart']))/86400);
if ($row['dispersiveend']=="" || $row['dispersivestart']=="")  $min11 = 0; 
$max11 =  $len11 + $min11;
$dispersivestart=gregorian_to_jalali($row['dispersivestart']).' تا '.gregorian_to_jalali($row['dispersiveend']);

}

if ($row['commissionend']>0 && $row['commissionstart']>0){
$len12 = floor((strtotime($row['commissionend']) - strtotime($row['commissionstart']))/86400+1);
$min12 = floor((strtotime($row['commissionstart']) - strtotime($row['pathstart']))/86400);
if ($row['commissionstart']=="" || $row['commissionend']=="")  $min12 = 0; 
$max12 =  $len12 + $min12;
$commissionstart=gregorian_to_jalali($row['commissionstart']).' تا '.gregorian_to_jalali($row['commissionend']);

}
if ($row['statementend']>0 && $row['statementstart']>0){
$len13 = floor((strtotime($row['statementend']) - strtotime($row['statementstart']))/86400+1);
$min13 = floor((strtotime($row['statementstart']) - strtotime($row['pathstart']))/86400);
if ($row['statementstart']=="" || $row['statementend']=="")  $min13 = 0; 
$max13 =  $len13 + $min13;
$statementstart=gregorian_to_jalali($row['statementstart']).' تا '.gregorian_to_jalali($row['statementend']);

}
if ($row['workdeliveryend']>0 && $row['workdeliverystart']>0){
$len14 = floor((strtotime($row['workdeliveryend']) - strtotime($row['workdeliverystart']))/86400+1);
$min14 = floor((strtotime($row['workdeliverystart']) - strtotime($row['pathstart']))/86400);
if ($row['workdeliveryend']=="" || $row['workdeliverystart']=="")  $min14 = 0; 
$max14 =  $len14 + $min14;
$workdeliverystart=gregorian_to_jalali($row['workdeliverystart']).' تا '.gregorian_to_jalali($row['workdeliveryend']);

}





					   		try 
								  {		
									     $resultn = mysql_query($sqln);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$rown = mysql_fetch_assoc($resultn);
//print $sqln;

if ($rown['tahvildate']>0){
$tahvildaten=gregorian_to_jalali($rown['tahvildate']);
}

if ($rown['pathend'] && $rown['pathstart']){
$lenn1 = floor((strtotime($rown['pathend']) - strtotime($rown['pathstart']))/86400+1);
$minn1 = floor((strtotime($rown['pathstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pathend']=="" || $rown['pathstart']=="" )  $minn1 = 0; 
$maxn1 =  $lenn1 + $minn1;
$pathstartn=gregorian_to_jalali($rown['pathstart']).' تا '.gregorian_to_jalali($rown['pathend']);
$pathstartXn=gregorian_to_jalali($rown['pathstart']);

}
if ($rown['transportend'] && $rown['transportstart']){
$lenn2 = floor((strtotime($rown['transportend']) - strtotime($rown['transportstart']))/86400+1);
$minn2 = floor((strtotime($rown['transportstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['transportend']=="" || $rown['transportstart']=="")  $minn2 = 0; 
$maxn2 =  $lenn2 + $minn2;
$transportstartn=gregorian_to_jalali($rown['transportstart']).' تا '.gregorian_to_jalali($rown['transportend']);

}

if ($rown['drillingend'] && $rown['drillingstart']){
$lenn3 = floor((strtotime($rown['drillingend']) - strtotime($rown['drillingstart']))/86400+1);
$minn3 = floor((strtotime($rown['drillingstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['drillingstart']=="" || $rown['drillingend']=="")  $minn3 = 0; 
$maxn3 =  $lenn3 + $minn3;
$drillingstartn=gregorian_to_jalali($rown['drillingstart']).' تا '.gregorian_to_jalali($rown['drillingend']);

}
if ($rown['rglazhend'] && $rown['rglazhstart']){
$lenn4 = floor((strtotime($rown['rglazhend']) - strtotime($rown['rglazhstart']))/86400+1);
$minn4 = floor((strtotime($rown['rglazhstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['rglazhstart']=="" || $rown['rglazhend']=="")  $minn4 = 0; 
$maxn4 =  $lenn4 + $minn4;
$rglazhstartn=gregorian_to_jalali($rown['rglazhstart']).' تا '.gregorian_to_jalali($rown['rglazhend']);

}
if ($rown['intubationend'] && $rown['intubationstart']){
$lenn5 = floor((strtotime($rown['intubationend']) - strtotime($rown['intubationstart']))/86400+1);
$minn5 = floor((strtotime($rown['intubationstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['intubationstart']=="" || $rown['intubationend']=="")  $minn5 = 0; 
$maxn5 =  $lenn5 + $minn5;
$intubationstartn=gregorian_to_jalali($rown['intubationstart']).' تا '.gregorian_to_jalali($rown['intubationend']);

}
if ($rown['pondend'] && $rown['pondstart']){
$lenn6 = floor((strtotime($rown['pondend']) - strtotime($rown['pondstart']))/86400+1);
$minn6 = floor((strtotime($rown['pondstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pondstart']=="" || $rown['pondend']=="")  $minn6 = 0; 
$maxn6 =  $lenn6 + $minn6;
$pondstartn=gregorian_to_jalali($rown['pondstart']).' تا '.gregorian_to_jalali($rown['pondend']);

}
if ($rown['pumpingstationend'] && $rown['pumpingstationstart']){
$lenn7 = floor((strtotime($rown['pumpingstationend']) - strtotime($rown['pumpingstationstart']))/86400+1);
$minn7 = floor((strtotime($rown['pumpingstationstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pumpingstationstart']=="" || $rown['pumpingstationend']=="")  $minn7 = 0; 
$maxn7 =  $lenn7 + $minn7;
$pumpingstationstartn=gregorian_to_jalali($rown['pumpingstationstart']).' تا '.gregorian_to_jalali($rown['pumpingstationend']);

}
if ($rown['soilpipeend'] && $rown['soilpipestart']){
$lenn8 = floor((strtotime($rown['soilpipeend']) - strtotime($rown['soilpipestart']))/86400+1);
$minn8 = floor((strtotime($rown['soilpipestart']) - strtotime($rown['pathstart']))/86400);
if ($rown['soilpipestart']=="" || $rown['soilpipeend']=="")  $minn8 = 0; 
$maxn8 =  $lenn8 + $minn8;
$soilpipestartn=gregorian_to_jalali($rown['soilpipestart']).' تا '.gregorian_to_jalali($rown['soilpipeend']);

}
if ($rown['networktestend'] && $rown['networkteststart']){
$lenn9 = floor((strtotime($rown['networktestend']) - strtotime($rown['networkteststart']))/86400+1);
$minn9 = floor((strtotime($rown['networkteststart']) - strtotime($rown['pathstart']))/86400);
if ($rown['networkteststart']=="" || $rown['networktestend']=="")  $minn9 = 0; 
$maxn9 =  $lenn9 + $minn9;
$networkteststartn=gregorian_to_jalali($rown['networkteststart']).' تا '.gregorian_to_jalali($rown['networktestend']);

}
if ($rown['soilintrenchend'] && $rown['soilintrenchstart']){
$lenn10 = floor((strtotime($rown['soilintrenchend']) - strtotime($rown['soilintrenchstart']))/86400+1);
$minn10 = floor((strtotime($rown['soilintrenchstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['soilintrenchstart']=="" || $rown['soilintrenchend']=="")  $minn10 = 0; 
$maxn10 =  $lenn10 + $minn10;
$soilintrenchstartn=gregorian_to_jalali($rown['soilintrenchstart']).' تا '.gregorian_to_jalali($rown['soilintrenchend']);

}
if ($rown['dispersiveend'] && $rown['dispersivestart']){
$lenn11 = floor((strtotime($rown['dispersiveend']) - strtotime($rown['dispersivestart']))/86400+1);
$minn11 = floor((strtotime($rown['dispersivestart']) - strtotime($rown['pathstart']))/86400);
if ($rown['dispersiveend']=="" || $rown['dispersivestart']=="")  $minn11 = 0; 
$maxn11 =  $lenn11 + $minn11;
$dispersivestartn=gregorian_to_jalali($rown['dispersivestart']).' تا '.gregorian_to_jalali($rown['dispersiveend']);

}
if ($rown['commissionend'] && $rown['commissionstart']){
$lenn12 = floor((strtotime($rown['commissionend']) - strtotime($rown['commissionstart']))/86400+1);
$minn12 = floor((strtotime($rown['commissionstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['commissionstart']=="" || $rown['commissionend']=="")  $minn12 = 0; 
$maxn12 =  $lenn12 + $minn12;
$commissionstartn=gregorian_to_jalali($rown['commissionstart']).' تا '.gregorian_to_jalali($rown['commissionend']);

}
if ($rown['statementend'] && $rown['statementstart']){
$lenn13 = floor((strtotime($rown['statementend']) - strtotime($rown['statementstart']))/86400+1);
$minn13 = floor((strtotime($rown['statementstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['statementstart']=="" || $rown['statementend']=="")  $minn13 = 0; 
$maxn13 =  $lenn13 + $minn13;
$statementstartn=gregorian_to_jalali($rown['statementstart']).' تا '.gregorian_to_jalali($rown['statementend']);

}
if ($rown['workdeliveryend'] && $rown['workdeliverystart']){
$lenn14 = floor((strtotime($rown['workdeliveryend']) - strtotime($rown['workdeliverystart']))/86400+1);
$minn14 = floor((strtotime($rown['workdeliverystart']) - strtotime($rown['pathstart']))/86400);
if ($rown['workdeliveryend']=="" || $rown['workdeliverystart']=="")  $minn14 = 0; 
$maxn14 =  $lenn14 + $minn14;
$workdeliverystartn=gregorian_to_jalali($rown['workdeliverystart']).' تا '.gregorian_to_jalali($rown['workdeliveryend']);
}
$delay="";
$delayval = floor((strtotime($rown['commissionend']) - strtotime($row['commissionend']))/86400);
if ($delayval>0) $delay=" تاخیر راه اندازی پروژه: $delayval روز";

$delaylen = $lenn12-$len12;
if ($delayval>0 && $delaylen>0) $delay.="<br>میزان تاخیر راه اندازی پروژه: $delaylen روز";


?>

<?php if ($ids2==2) { ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار جدول زمانبندي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		g.highcharts-axis-labels {
		font-family:tahoma;
		}
		g.highcharts-axis-labels text tspan {
		font-size:9px;
		}
		text.highcharts-title tspan  {
		font-family:'B Nazanin';
		font-size:25px;
		}
		text.highcharts-subtitle tspan {
		font-family:'B Nazanin';
		font-size:20px;
		}
		text.highcharts-yaxis-title tspan {
		font-family:'B Nazanin';
		font-size:18px;
		direction:rtl;
		}
		</style>
		<script type="text/javascript">
$(function () {

    $('#container').highcharts({

        chart: {
            type: 'columnrange',
            inverted: true
        },

        title: {
            text: 'نمودار پیشنهادی زمانبندي طرح آبياري آقاي <?php print $row['ApplicantFName']."  " .$row['ApplicantName'] ."  ". $row["DesignArea"] ." هكتار "."شهرستان " . $row['shahrcityname']; ?>'
        },


        subtitle: {
            text: '<?php echo    "  پيمانكار :". $row['operatorcoTitle']."--- مشاور ناظر : ".$row['DesignerCotitle']  ?>'
        },

        xAxis: {
            categories: [

			'تحویل زمین  <?php print ' ---------------------------------------------------- '.$tahvildate; ?>',
			'پياده كردن مسير طرح <?php print ' -------------------------- '.$pathstart; ?>',
			'تهيه و حمل لوازم طرح <?php print ' -------------------------- '.$transportstart; ?>',
			'حفر ترانشه لوله گذاري <?php print ' ------------------------- '.$drillingstart; ?>' ,
			'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه <?php print ' ---- '.$rglazhstart; ?>',
			'لوله گذاري خط اصلي و فرعي و نصب اتصالات <?php print ' -- '.$intubationstart; ?>',
			'ساختن حوضچه پمپاژ فوندانسيون <?php print ' -------------- '.$pondstart; ?>',
			'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي <?php print ' - '.$pumpingstationstart; ?>',
			'ريختن خاك نرم يا سرندي روي لوله <?php print ' ------------- '.$soilpipestart; ?>',
			'تست شبكه <?php print ' ------------------------------------ '.$networkteststart; ?>',
			'برگرداندن خاك درون ترانشه <?php print ' --------------------- '.$soilintrenchstart; ?>',
			'نصب و راه اندازي و مونتاژ بال هاي آبياري <?php print ' ------- '.$dispersivestart; ?>',
			'راه اندازي طرح <?php print ' ---------------------------------- '.$commissionstart; ?>',
			'تحويل صورت وضعيت <?php print ' --------------------------- '.$statementstart; ?>',
			'تحويل كار <?php print ' --------------------------------------- '.$workdeliverystart; ?>'
			]
        },

        yAxis: {
	
            title: {
			 
                text: 'تعداد روز'
            },
				
			             categories: [ '<?php echo 'شروع عملیات  <br>'. $pathstartX; ?>'],
						 
						formatter: function() { 
						return '  <br>'  (ret ? ret : this.value);
						},
        },

        tooltip: {
		            valueSuffix: '',
			enabled:true
        },

        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
					
					     return ;
                    }
                }
            }
        },

        legend: {
            enabled: false
        },

        series: [{
            name: 'مدت زمان انجام كار',
            data: [
			
                [0,0],
                [<?php echo $min1;?>,<?php echo $max1;?>],
                [<?php echo $min2;?>,<?php echo $max2;?>],
                [<?php echo $min3;?>,<?php echo $max3;?>],
                [<?php echo $min4;?>,<?php echo $max4;?>],
                [<?php echo $min5;?>,<?php echo $max5;?>],
                [<?php echo $min6;?>,<?php echo $max6;?>],
                [<?php echo $min7;?>,<?php echo $max7;?>],
                [<?php echo $min8;?>,<?php echo $max8;?>],
                [<?php echo $min9;?>,<?php echo $max9;?>],
                [<?php echo $min10;?>,<?php echo $max10;?>],
                [<?php echo $min11;?>,<?php echo $max11;?>],
                [<?php echo $min12;?>,<?php echo $max12;?>],
                [<?php echo $min13;?>,<?php echo $max13;?>],
                [<?php echo $min14;?>,<?php echo $max14;?>]
				
            ]
        }]
		

    });

});
		</script>
	</head>
	<body>
<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-more.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="min-width: 350px; height: 550px; margin: 0 auto"></div>

	</body>
</html>

<?php } ?>


<?php if ($ids2==1) { ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار جدول زمانبندي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		g.highcharts-axis-labels {
		font-family:tahoma;
		}
		g.highcharts-axis-labels text tspan {
		font-size:9px;
		}
		text.highcharts-title tspan  {
		font-family:'B Nazanin';
		font-size:25px;
		}
		text.highcharts-subtitle tspan {
		font-family:'B Nazanin';
		font-size:20px;
		}
		text.highcharts-yaxis-title tspan {
		font-family:'B Nazanin';
		font-size:18px;
		direction:rtl;
		}
		</style>
		<script type="text/javascript">
$(function () {

    $('#container').highcharts({

        chart: {
            type: 'columnrange',
            inverted: true
        },

        title: {
            text: 'نمودار زمانبندي طرح آبياري  <?php print $row['ApplicantFName']."  " .$row['ApplicantName'] ."  ". $row["DesignArea"] ." هكتار "."شهرستان " . $row['shahrcityname']; ?>'
        },

		subtitle: {
            text: '<?php print "مشاور ناظر : ".$row['DesignerCotitle'] . "  ---   پيمانكار :". $row['operatorcoTitle']?>'
        },

        xAxis: {
            categories: [
			'تحویل زمین  <?php print ' ---------------------------------------------------- '.$tahvildaten; ?>',
			'پياده كردن مسير طرح <?php print ' -------------------------- '.$pathstartn; ?>',
			'تهيه و حمل لوازم طرح <?php print ' -------------------------- '.$transportstartn; ?>',
			'حفر ترانشه لوله گذاري <?php print ' ------------------------- '.$drillingstartn; ?>' ,
			'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه <?php print ' ---- '.$rglazhstartn; ?>',
			'لوله گذاري خط اصلي و فرعي و نصب اتصالات <?php print ' -- '.$intubationstartn; ?>',
			'ساختن حوضچه پمپاژ فوندانسيون <?php print ' -------------- '.$pondstartn; ?>',
			'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي <?php print ' - '.$pumpingstationstartn; ?>',
			'ريختن خاك نرم يا سرندي روي لوله <?php print ' ------------- '.$soilpipestartn; ?>',
			'تست شبكه <?php print ' ------------------------------------ '.$networkteststartn; ?>',
			'برگرداندن خاك درون ترانشه <?php print ' --------------------- '.$soilintrenchstartn; ?>',
			'نصب و راه اندازي و مونتاژ بال هاي آبياري <?php print ' ------- '.$dispersivestartn; ?>',
			'راه اندازي طرح <?php print ' ---------------------------------- '.$commissionstartn; ?>',
			'تحويل صورت وضعيت <?php print ' --------------------------- '.$statementstartn; ?>',
			'تحويل كار <?php print ' --------------------------------------- '.$workdeliverystartn; ?>'
			]
        },

        yAxis: {
            title: {
			 
                text: 'تعداد روز'
            },
				
			             categories: [ '<?php echo 'شروع عملیات  <br>'. $pathstartXn; ?>'],
						 
						formatter: function() { 
						return '  <br>'  (ret ? ret : this.value);
						},
             },

        tooltip: {
		            valueSuffix: '',
			enabled:true
        },

        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
					
					     return ;
                    }
                }
            }
        },

        legend: {
            enabled: false
        },

        series: [{
            name: 'مدت زمان انجام كار',
            data: [
			
                [0,0],
                [<?php echo $minn1;?>,<?php echo $maxn1;?>],
                [<?php echo $minn2;?>,<?php echo $maxn2;?>],
                [<?php echo $minn3;?>,<?php echo $maxn3;?>],
                [<?php echo $minn4;?>,<?php echo $maxn4;?>],
                [<?php echo $minn5;?>,<?php echo $maxn5;?>],
                [<?php echo $minn6;?>,<?php echo $maxn6;?>],
                [<?php echo $minn7;?>,<?php echo $maxn7;?>],
                [<?php echo $minn8;?>,<?php echo $maxn8;?>],
                [<?php echo $minn9;?>,<?php echo $maxn9;?>],
                [<?php echo $minn10;?>,<?php echo $maxn10;?>],
                [<?php echo $minn11;?>,<?php echo $maxn11;?>],
                [<?php echo $minn12;?>,<?php echo $maxn12;?>],
                [<?php echo $minn13;?>,<?php echo $maxn13;?>],
                [<?php echo $minn14;?>,<?php echo $maxn14;?>]
				
            ]
			,color: '#FF0000'
        }]
		

    });

});
		</script>
	</head>
	<body>
<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-more.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="min-width: 350px; height: 550px; margin: 0 auto"></div>

	</body>
</html>

<?php } ?>





<?php if ($ids2==3) { ?>


<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title> مقایسه نمودار جدول زمانبندي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
	

		g.highcharts-axis-labels {
		font-family:tahoma;
		}
		g.highcharts-axis-labels text tspan {
		font-size:9px;
		}
		text.highcharts-title tspan  {
		font-family:'B Nazanin';
		font-size:25px;
		}
		text.highcharts-subtitle tspan {
		font-family:'B Nazanin';
		font-size:20px;
		}
		text.highcharts-yaxis-title tspan {
		font-family:'B Nazanin';
		font-size:18px;
		direction:rtl;
		}
		</style>
		
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'نمودار زمانبندي طرح آبياري <?php print $row['ApplicantFName']."  " .$row['ApplicantName'] ."  ". $row["DesignArea"] ." هكتار "."شهرستان " . $row['shahrcityname']; ?>'
        },
        subtitle: {
            text: 'مقایسه نمودار پیشنهادی پیمانکار <?php print $row['operatorcoTitle'] ?> و مشاور ناظر <?php print $row['DesignerCotitle'] ?>'
        },
        xAxis: {
		  tickPositions:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14],
            categories: [
			 'پياده كردن مسير طرح  <?php if ($len1<$lenn1) print '%'.round($len1/$lenn1*100,1); else print '%100'; ?>',
			 'تهيه و حمل لوازم طرح <?php if ($len2<$lenn2) print '%'.round($len2/$lenn2*100,1); else print '%100'; ?>',
			 'حفر ترانشه لوله گذاري <?php if ($len3<$lenn3) print '%'.round($len3/$lenn3*100,1); else print '%100'; ?>',
			 'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه <?php if ($len4<$lenn4) print '%'.round($len4/$lenn4*100,1); else print '%100'; ?>',
			 'لوله گذاري خط اصلي و فرعي و نصب اتصالات <?php if ($len5<$lenn5) print '%'.round($len5/$lenn5*100,1); else print '%100'; ?>',
			 'ساختن حوضچه پمپاژ فوندانسيون <?php if ($len6<$lenn6) print '%'.round($len6/$lenn6*100,1); else print '%100'; ?>',
			 'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي <?php if ($len7<$lenn7) print '%'.round($len7/$lenn7*100,1); else print '%100'; ?>',
			 'ريختن خاك نرم يا سرندي روي لوله <?php if ($len8<$lenn8) print '%'.round($len8/$lenn8*100,1); else print '%100'; ?>',
			 'تست شبكه <?php if ($len9<$lenn9) print '%'.round($len9/$lenn9*100,1); else print '%100'; ?>',
			 'برگرداندن خاك درون ترانشه <?php if ($len10<$lenn10) print '%'.round($len10/$lenn10*100,1); else print '%100'; ?>',
			 'نصب و راه اندازي و مونتاژ بال هاي آبياري <?php if ($len11<$lenn11) print '%'.round($len11/$lenn11*100,1); else print '%100'; ?>',
			 'راه اندازي طرح <?php if ($len12<$lenn12) print '%'.round($len12/$lenn12*100,1); else print '%100'; ?>',
			 'تحويل صورت وضعيت <?php if ($len13<$lenn13) print '%'.round($len13/$lenn13*100,1); else print '%100'; ?>',
			 'تحويل كار <?php if ($len14<$lenn14) print '%'.round($len14/$lenn14*100,1); else print '%100'; ?>'
					],
            title: {
                text: null
            }  
 
			
			
        },
        yAxis: {
		    min: 0,
            title: {
                text: 'روز <?php echo '<br>'.$delay;?>',
                align: 'low'
				
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' روز'
        },
				plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
		 
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -20,
            y: 30,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#F0F0F0'),
            shadow: true
        },
        credits: {
            enabled: false
        },
		 plotOptions: {
        series: {
            showCheckbox: true,
			 pointPadding: 0.1,
            groupPadding: 0.1,
			 pointIntervalUnit :'',
			 tickInterval:0.1,
        }
    },
        series: [{
            name: 'مشاور ناظر',
            data: [<?php echo $lenn1;?>,<?php echo $lenn2;?>, <?php echo $lenn3;?>, <?php echo $lenn4;?>,<?php echo $lenn5;?>,
					<?php echo $lenn6;?>,<?php echo $lenn7;?>, <?php echo $lenn8;?>,<?php echo $lenn9;?>, <?php echo $lenn10;?>,
					<?php echo $lenn11;?>, <?php echo $lenn12;?>, <?php echo $lenn13;?>, <?php echo $lenn14;?>
					]
					,color: '#FF0000'
					
        }, {
		
            name: 'پیمانکار',
            data: [<?php echo $len1;?>, <?php echo $len2;?>, <?php echo $len3;?>, <?php echo $len4;?>, <?php echo $len5;?>, 
					<?php echo $len6;?>, <?php echo $len7;?>, <?php echo $len8;?>, <?php echo $len9;?>, <?php echo $len10;?>,
					<?php echo $len11;?>, <?php echo $len12;?>, <?php echo $len13;?>, <?php echo $len14;?>]
				,color: '#0088FF'
		
        }]
    });
});
		</script>
	</head>
	<body>
<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>

	</body>
</html>





<?php } ?>
	