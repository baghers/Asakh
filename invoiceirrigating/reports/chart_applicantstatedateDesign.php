<?php 
/*
reorts/chart_applicantstatedateDesign.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
if ($_GET) {
  $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $TBLID=$linearray[1];
		if  ($TBLID>0) $str = " and designerco.DesignerCoID = $TBLID ";
	}

	if ($login_RolesID=='10') 
		{$str.="and designerco.DesignerCoID='$login_DesignerCoID'";}

/*
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    DesignerCoIDnazer شناسه مشاور ناظر طرح
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
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignSystemGroupsID نوع سیستم آبیاری
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر
    */	
	
$sql = " SELECT applicantmaster.ApplicantMasterID,applicantmaster.cityid,applicantstates.title applicantstatestitle,designerco.DesignerCoID, 
designerco.title DesignerCotitle,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
,appchangestate.SaveDate laststatedate,(applicantsavedate1.firstsave) firstsave,(applicantsavedate2.sendtobazbin) sendtobazbin,(applicantsavedate3.bazbintomoshaver) bazbintomoshaver,(applicantsavedate4.lastsendtoBazbin) lastsendtoBazbin,(applicantsavedate5.sendtabokhak) sendtabokhak,(applicantsavedate6.abokhaktosandogh) abokhaktosandogh,(applicantsavedate7.lasttaid) lasttaid
,designsystemgroups.title DesignSystemGroupstitle
FROM applicantmaster 
left outer join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno  on appchangestatestateno.ApplicantMasterID=applicantmaster.ApplicantMasterID

inner join appchangestate  on appchangestate.ApplicantMasterID=applicantmaster.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID

left outer join (select ApplicantMasterID,SaveDate firstsave from appchangestate where applicantstatesID=23 group by ApplicantMasterID) applicantsavedate1 on applicantsavedate1.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,min(SaveDate) sendtobazbin from appchangestate where applicantstatesID=5 group by ApplicantMasterID) applicantsavedate2 on applicantsavedate2.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,min(SaveDate) bazbintomoshaver from appchangestate where applicantstatesID=4 group by ApplicantMasterID) applicantsavedate3 on applicantsavedate3.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,max(SaveDate) lastsendtoBazbin from appchangestate where applicantstatesID=5 group by ApplicantMasterID) applicantsavedate4 on applicantsavedate4.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate sendtabokhak from appchangestate where applicantstatesID=8 group by ApplicantMasterID) applicantsavedate5 on applicantsavedate5.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate abokhaktosandogh from appchangestate where applicantstatesID=12 group by ApplicantMasterID) applicantsavedate6 on applicantsavedate6.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate lasttaid from appchangestate where applicantstatesID=22 group by ApplicantMasterID) applicantsavedate7 on applicantsavedate7.ApplicantMasterID =applicantmaster.applicantmasterid

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

inner join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)  and ifnull(applicantmaster.private,0)=0 $str order by applicantstatestitle COLLATE utf8_persian_ci,appchangestate.SaveDate";
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
  $rown=0;
				   
				                    
						$sumtarah = 0;
						$sumbazbin = 0;
						$sumabokhak = 0;
						$sumsandoogh = 0;
						$rowtarah=0;
						$rowbazbin = 0;
						$rowabokhak = 0;
						$rowsandoogh = 0;
						
                        while($row2 = mysql_fetch_assoc($result)){
						
                            $firstsave=$row2['firstsave'];
                            $sendtobazbin=$row2['sendtobazbin'];
                            $bazbintomoshaver=$row2['bazbintomoshaver'];
                            $lastsendtoBazbin=$row2['lastsendtoBazbin'];
                            $sendtabokhak=$row2['sendtabokhak'];
                            $abokhaktosandogh=$row2['abokhaktosandogh'];
                            $lasttaid=$row2['lasttaid'];
							$intervaltarah1 = 0;
							$intervaltarah2 = 0;
							$intervalbazbin1 = 0;
							$intervalbazbin2 = 0;
							$intervalabokhak = 0;
							$intervalsandoogh =0;
							if ($_GET) $designer= $row2['DesignerCotitle'];
						
						$totalM =0;
						if ($sendtobazbin!="" && $firstsave!="") 
						$intervaltarah1 = abs((strtotime($sendtobazbin) - strtotime($firstsave))/86400);
						else if ($sendtobazbin=="" || $firstsave=="")  $intervaltarah1 = 0;
						
						if ($lastsendtoBazbin!="" && $bazbintomoshaver!="")
						$intervaltarah2 = abs((strtotime($lastsendtoBazbin) - strtotime($bazbintomoshaver))/86400);
						else if ($lastsendtoBazbin=="" || $bazbintomoshaver=="") $intervaltarah2 = 0;
						
						$intervaltarah = $intervaltarah1 + $intervaltarah2;
						if ($sendtobazbin!="" || $firstsave!="" || $lastsendtoBazbin!="" || $bazbintomoshaver!="") $totalM =1;
						
						
					   if ($sendtabokhak!="" && $lastsendtoBazbin!="")
						$intervalbazbin1 = abs((strtotime($sendtabokhak) - strtotime($lastsendtoBazbin))/86400);
						else if ($sendtabokhak=="" || $lastsendtoBazbin=="") $intervalbazbin1 = 0;
						if ($sendtobazbin!="" && $bazbintomoshaver!="")
						$intervalbazbin2 = abs((strtotime($bazbintomoshaver) - strtotime($sendtobazbin))/86400);
						else if ($bazbintomoshaver=="" || $sendtobazbin=="")
						$intervalbazbin2 = 0;

                        $intervalbazbin = $intervalbazbin1 + $intervalbazbin2;
						if ($sendtabokhak!="" || $lastsendtoBazbin!="" || $sendtobazbin!="" || $bazbintomoshaver!="") $totalM =2;
						
                        if ($sendtabokhak!="" && $abokhaktosandogh!="")
						$intervalabokhak = abs((strtotime($abokhaktosandogh) - strtotime($sendtabokhak))/86400);
						else if ($sendtabokhak=="" || $abokhaktosandogh=="") $intervalabokhak = 0;
						if ($sendtabokhak!="" || $abokhaktosandogh!="") $totalM =3;
						
						if ($lasttaid!="" && $abokhaktosandogh!="")
						$intervalsandoogh = abs((strtotime($lasttaid) - strtotime($abokhaktosandogh))/86400);
						else if ($lasttaid=="" || $abokhaktosandogh=="") $intervalsandoogh = 0;
						if ($lasttaid!="" || $abokhaktosandogh!="") $totalM =4;
						
						$suminterval = $intervaltarah + $intervalbazbin + $intervalabokhak + $intervalsandoogh;
                        $sumtarah+= $intervaltarah;
						$sumbazbin+= $intervalbazbin;
						$sumabokhak+= $intervalabokhak;
						$sumsandoogh+= $intervalsandoogh;
						                                             
                        if ($totalM==4) {$rowtarah++; $rowbazbin++; $rowabokhak++; $rowsandoogh++;}
						else if ($totalM==3) {$rowtarah++; $rowbazbin++; $rowabokhak++;}
						else if ($totalM==2) {$rowtarah++; $rowbazbin++;}
						else if ($totalM==1) {$rowtarah++;}
						
												
   }
		if ($rowtarah>0 && $rowbazbin>0 && $rowabokhak>0 && $rowsandoogh>0) {				
   $sum  = round($sumtarah/$rowtarah) + round($sumbazbin/$rowbazbin)+ round($sumabokhak/$rowabokhak) + round($sumsandoogh/$rowsandoogh);
?>                      

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار پيشرفت طرح هاي مطالعاتي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		.highcharts-title {
		font-family:'B lotus';
		font-size:25px;
		}
		#container {
		border:1px dotted #ccc;
		}
		
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'نمودار پيشرفت زماني مطالعات طرح هاي آبياري تحت فشار ميانگين<?php echo ' ' .$designer;?><?php echo $sum;?> روز'
        },
        tooltip: {
            pointFormat: '% <b>{point.percentage:.1f}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 60,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'طرحهاي مطالعاتي',
            data: [
                ['مشاور طراح <?php if ($rowtarah>0) echo ":".round($sumtarah/$rowtarah)." روز"; ?>',   <?php if ($rowtarah>0) echo round($sumtarah/$rowtarah); ?>],
                ['بازبيني <?php if ($rowbazbin>0) echo ":".round($sumbazbin/$rowbazbin)." روز"; ?>',       <?php if ($rowbazbin>0) echo round($sumbazbin/$rowbazbin); ?>],
                {
                    name: 'مديريت آب و خاك - تكميل پرونده<?php if ($rowabokhak>0) echo ":" .round($sumabokhak/$rowabokhak)." روز";?>',
                    y: <?php if ($rowabokhak>0) echo round($sumabokhak/$rowabokhak); ?>,
                    sliced: true,
                    selected: true
                },
                ['صندوق - عقد قرارداد <?php if ($rowsandoogh>0) echo ":".round($sumsandoogh/$rowsandoogh)." روز"; ?>',     <?php if ($rowsandoogh>0) echo round($sumsandoogh/$rowsandoogh); ?>]
               
            ]
        }]
    });
});
		</script>
	</head>
	<body>

<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="height: 400px"></div>
	</body>
</html>
<?php } ?>
                 