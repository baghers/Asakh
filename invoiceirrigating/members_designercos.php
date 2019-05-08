<?php 
/*
members_designercos.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php include('includes/elements.php'); ?>
<?php


//if ($_POST)
//{       

//}
//else
//{
		$ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $action=$linearray[0];
        $TBLID=$linearray[1];
	    $showa=0;
		if ($_POST['showa']=='on')  $showa=1;

        

    
	$condition="and substring(clerk.CityId,1,4)=substring('$login_CityId',1,4)";

	$condition.="and designerco.DesignerCoID <> 67";

    if ($login_RolesID==17 || ($login_designerCO==1 && $showa==1))//ناظر مقیم یا طراح یا تیک همه
    $condition="";
	
	  if ($_POST['ostan']>0) 
			{$selectedCityId=$_POST['ostan'];$condition="and substring(clerk.CityId,1,2)=substring('$_POST[ostan]',1,2)";}

  /*
    designerco جدول شرکت های طراح
    designer جدول شرکت های طراح
    members جدول اعضای هیئت مدیره
    operatorapprequest جدول پیشنهاد قیمت های طرح
    clerk جدول کاربران
    fundationYear تاریخ تاسیس شرکت 
    fundationno شماره مدرک تاسیس 
    fundationIssuer مرجع صادر کننده صلاحیت 
    boardchangeno شماره نامه آخرین تغییرات
    boardchangedate تاریخ آخرین تغییرات هیئت مدیره
    boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
    boardIssuer مرجع صادرکننده مدرک هیئت مدیره
    copermisionno تعداد پروژه های قابل انجام
    StarCo تعداد ستاره های شرکت
    ent_Num تعداد انتظامی بودن شرکت
    ent_DateTo پایان انتظامی بودن شرکت
    copermisiondate تاریخ مجوز شرکت
    copermisionvalidate تاریخ اعتبار مجوز شرکت
    copermisionIssuer مرجع صادر کننده مجوز شرکت
    contractordate تاریخ قرارداد شرکت
    contractorvalidate تاریخ اعتبار قرارداد شرکت
    contractorno شماره نامه قرارداد شرکت
    contractorIssuer مرجع صادرکننده قرارداد شرکت
    contractorRank1 رتبه شرکت نفر 1
    contractorField1 شرح رتبه شرکت نفر 1
    contractorRank2 رتبه شرکت نفر 2
    contractorField2 شرح رتبه شرکت نفر 2
    engineersystemdate تاریخ مدرک مهندس شرکت
    engineersystemvalidate تاریخ اعتبار مدرک مهندس شرکت
    engineersystemno شماره مدرک مهندس شرکت
    engineersystemIssuer مرجع صادر کننده مدرک مهندس شرکت
    engineersystemRank رتبه  مهندس شرکت
    engineersystemField شرح مهندس شرکت
    valueaddeddate تاریخ گواهی ارزش افزوده
    valueaddedvalidate تاریخ اعتبار گواهی ارزش افزوده
    valueaddedno شماره گواهی ارزش افزوده
    valueaddedIssuer مرجع گواهی ارزش افزوده
    operatorcoID شناسه شرکت مجری
    membersinfo.FName نام
    membersinfo.LName نام خانوادگی
    projectcount92 تعداد پروژه های اول دوره 
    projecthektar92 مساحت پروژه های انجام داده شده 
    Title عنوان شرکت
	CompanyAddress آدرس شرکت
    Phone2 تلفن دوم شرکت
    bossmobile موبایل مدیر عامل شرکت 
    corank رتبه شرکت
    firstperiodcoprojectarea مجموع مساحت پروژه های انجام داده اول دوره شرکت
    firstperiodcoprojectnumber تعداد  پروژه های انجام داده اول دوره شرکت
    coprojectsum مجموع تعدادی پروژه های شرکت
    projecthektardone پروژه های انجام داده شرکت
    simultaneouscnt تعداد پروژه های همزمان
    thisyearprgarea مساحت پرژه های امسال
    above20cnt تعداد پروژه های بالای 20 هکتار
    above55cnt تعداد پروژه های بالای 55 هکتار
    currentprgarea مساحت پروژه های جاری
    projectcountdone تعداد پروژه های انجام داده شرکت
    clerk.clerkid شناسه کاربر
    designerinfo.designercnt تعداد کارشناسان طراح شرکت
    designerinfo.dname نام کارشناس طراح
    designerinfo.duplicatedesigner داشتن کارشناسی که در دو شرکت فعالیت نماید
    membersinfo.duplicatemembers عضو هیئت مدیره که در دو شرکت فعالیت نماید
    allreq.cnt reqcnt تعداد پیشنهادات ارسال شده
    allwinreq.wincnt تعداد پیشنهادات انتخاب شده
    avgpmreq.avg میانگین ظرایب پیشنهاد قیمت های شرکت
    avgpmreqa.avga میانگین ظرایب پیشنهاد قیمت های انتخابی
    BossName نام مدیر عامل
    bosslname نام خانوادگی مدیر عامل
    */	
	
	
    $sql = "SELECT distinct designerco.DesignerCoID,
	coprojectnumber,coprojectarea,coprojectsum,fundationno,fundationIssuer,boardIssuer,copermisionno,boardchangeno,
    copermisiondate,copermisionvalidate,copermisionIssuer,contractordate,contractorvalidate,contractorno,contractorIssuer,
        engineersystemdate,engineersystemvalidate,engineersystemno,engineersystemIssuer,valueaddeddate,valueaddedvalidate,
        valueaddedno,valueaddedIssuer,designerco.fundationYear,designerco.boardvalidationdate,designerco.boardchangedate,designerco.BossName,
		designerco.bosslname,designerco.projectcount92,designerco.Title designercoTitle,corank,
		concat(designerco.CompanyAddress,' -تلفن: ',designerco.Phone2,' - ',designerco.bossmobile) CoAddress
        
, suratprg.notdonprg suratcntonrun,suratprg.donprg suratcntdone       
,suratprg.notdonhektarprg surathektonrun,suratprg.donhektarprg surathektdone 
         
,(ifnull(firstperiodcoprojectarea,0)+allprgs.donhektarprg)doneprg
,(ifnull(firstperiodcoprojectnumber,0)+allprgs.donprg)doneprgcnt,allprgs.notdonhektarprg curprg,allprgs.notdonprg curprgcnt

               ,designerinfo.designercnt
                ,designerinfo.dname
                ,designerinfo.duplicatedesigner
                
                
                FROM designerco
                
                left outer join (
                select count(*) designercnt,max(concat(designer.FName,' ',designer.LName)) dname,designer.designercoID,
                case designer2.NationalCode>0 when 1 then 1 else 0 end duplicatedesigner
                 from designer 
                left outer join designer designer2 on designer2.NationalCode=designer.NationalCode 
                and (designer2.operatorcoid<>designer.operatorcoid or designer2.designercoID<>designer.designercoID
                or (designer2.designercoID>0 and designer.operatorcoid>0 and designer2.designercoID<>designer.operatorcoid)
                or (designer.designercoID>0 and designer2.operatorcoid>0 and designer.designercoID<>designer2.operatorcoid)
                )
                where designer.designercoID>0
                group by designer.designercoID) designerinfo on designerinfo.designercoID=designerco.designercoID
                
left outer join 
                (select sum(case applicantmaster.applicantstatesID=45 when 1 then 1 else 0 end) donprg,
                sum(case applicantmaster.applicantstatesID=45 when 1 then 0 else 1 end) notdonprg,
                sum(case applicantmaster.applicantstatesID=45 when 1 then applicantmaster.DesignArea else 0 end) donhektarprg,
                sum(case applicantmaster.applicantstatesID=45 when 1 then 0 else applicantmaster.DesignArea end) notdonhektarprg,
                applicantmaster.DesignerCoIDnazer from applicantmaster 
                where ifnull(applicantmaster.ApplicantMasterIDmaster,0)>0 and applicantmaster.operatorcoid>0
                group by DesignerCoIDnazer) suratprg on  suratprg.DesignerCoIDnazer=designerco.designercoID
                
                
left outer join ( SELECT sum(case applicantstates.applicantstategroupsID=27 when 1 then 1 else 0 end) donprg,
                sum(case applicantstates.applicantstategroupsID=27 when 1 then 0 else 1 end) notdonprg,
                sum(case applicantstates.applicantstategroupsID=27 when 1 then applicantmaster.DesignArea else 0 end) donhektarprg,
                sum(case applicantstates.applicantstategroupsID=27 when 1 then 0 else applicantmaster.DesignArea end) notdonhektarprg,
                applicantmaster.designercoID
                FROM `applicantmaster`
                inner join applicantstates on applicantstates.applicantstatesid=applicantmaster.applicantstatesid
                where applicantmaster.applicantstatesID<>23 and ifnull(applicantmaster.private,0)=0 and applicantmaster.designercoID>0
                group by applicantmaster.designercoID) allprgs on  allprgs.designercoID=designerco.designercoID



                inner join clerk on clerk.MMC=designerco.designercoID
                
                where  ifnull(designerco.Disabled,0)<>1 $condition ";
                
                    $sql="select allco.coprojectnumber,allco.coprojectarea,allco.fundationYear,allco.boardvalidationdate,allco.DesignerCoID,
					allco.boardchangedate,allco.BossName,allco.bosslname,allco.projectcount92,allco.designercoTitle,allco.corank,
					allco.doneprg,allco.doneprgcnt,allco.coprojectsum,allco.curprg,allco.curprgcnt,
                    allco.copermisionvalidate,allco.designercnt,allco.dname,allco.duplicatedesigner,allco.CoAddress
                    ,allco.suratcntonrun
,allco.suratcntdone
,allco.surathektonrun
,allco.surathektdone,
case 
    ifnull(tmpco.fundationYear,ifnull(allco.fundationYear,''))<>ifnull(allco.fundationYear,'') or
    ifnull(tmpco.fundationno,ifnull(allco.fundationno,''))<>ifnull(allco.fundationno,'') or
    ifnull(tmpco.fundationIssuer,ifnull(allco.fundationIssuer,''))<>ifnull(allco.fundationIssuer,'') or
    ifnull(tmpco.boardchangeno,ifnull(allco.boardchangeno,''))<>ifnull(allco.boardchangeno,'') or
    ifnull(tmpco.boardchangedate,ifnull(allco.boardchangedate,''))<>ifnull(allco.boardchangedate,'') or
    ifnull(tmpco.boardvalidationdate,ifnull(allco.boardvalidationdate,''))<>ifnull(allco.boardvalidationdate,'') or
    ifnull(tmpco.boardIssuer,ifnull(allco.boardIssuer,''))<>ifnull(allco.boardIssuer,'') or
    ifnull(tmpco.copermisionno,ifnull(allco.copermisionno,''))<>ifnull(allco.copermisionno,'') or
    ifnull(tmpco.copermisiondate,ifnull(allco.copermisiondate,''))<>ifnull(allco.copermisiondate,'') or
    ifnull(tmpco.copermisionvalidate,ifnull(allco.copermisionvalidate,''))<>ifnull(allco.copermisionvalidate,'') or
    ifnull(tmpco.copermisionIssuer,ifnull(allco.copermisionIssuer,''))<>ifnull(allco.copermisionIssuer,'') or
    ifnull(tmpco.contractordate,ifnull(allco.contractordate,''))<>ifnull(allco.contractordate,'') or
    ifnull(tmpco.contractorvalidate,ifnull(allco.contractorvalidate,''))<>ifnull(allco.contractorvalidate,'') or
    ifnull(tmpco.contractorno,ifnull(allco.contractorno,''))<>ifnull(allco.contractorno,'') or
    ifnull(tmpco.contractorIssuer,ifnull(allco.contractorIssuer,''))<>ifnull(allco.contractorIssuer,'') or
    ifnull(tmpco.engineersystemdate,ifnull(allco.engineersystemdate,''))<>ifnull(allco.engineersystemdate,'') or
    ifnull(tmpco.engineersystemvalidate,ifnull(allco.engineersystemvalidate,''))<>ifnull(allco.engineersystemvalidate,'') or
    ifnull(tmpco.engineersystemno,ifnull(allco.engineersystemno,''))<>ifnull(allco.engineersystemno,'') or
    ifnull(tmpco.engineersystemIssuer,ifnull(allco.engineersystemIssuer,''))<>ifnull(allco.engineersystemIssuer,'') or
    ifnull(tmpco.valueaddeddate,ifnull(allco.valueaddeddate,''))<>ifnull(allco.valueaddeddate,'') or
    ifnull(tmpco.valueaddedvalidate,ifnull(allco.valueaddedvalidate,''))<>ifnull(allco.valueaddedvalidate,'') or
    ifnull(tmpco.valueaddedno,ifnull(allco.valueaddedno,''))<>ifnull(allco.valueaddedno,'') or
    ifnull(tmpco.valueaddedIssuer,ifnull(allco.valueaddedIssuer,''))<>ifnull(allco.valueaddedIssuer,'')
    when 1 then 1 else 0 end ischange
    
 from ($sql) allco
 left outer join tmpco on tmpco.UID=allco.DesignerCoID and type='3'

 ORDER BY ischange desc,corank desc  ;";
                
    //print $sql;
    $result = mysql_query($sql); 
//}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست وضعیت مهندسين مشاور</title>

<meta http-equiv="X-Frame-Options" content="deny" />
	
<script type="text/javascript" language='javascript' src='assets/jquery2.js'></script>

<script type="text/javascript" src="lib/jquery2.js"></script>
<script type='text/javascript' src='lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery.freezeheader.js"></script>
<script language="javascript" type="text/javascript">
        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
    </script>

<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="lib/thickbox.css" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />

    <script>

          
    </script>
    
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#ffffff;background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

  
</style>

    <!-- /scripts -->
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            	
            <form action="members_designercos.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
							
<tbody >
                
                <table align='center' border='1' id="table2">              
                   <thead>
				 <tr> 
		
					<?php  if ($login_designerCO==1) {
					  $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                     where substring(ostan.id,3,5)='00000'
                     order by _key  COLLATE utf8_persian_ci";
                     $allg1id = get_key_value_from_query_into_array($sqlselect);
					print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId,'','200');?>
				    <td class="data" >همه<input name="showa" type="checkbox" id="showa"  <?php if ($showa>0) echo "checked"; ?> /></td>
					<td colspan="1" > <input   name="submit" type="submit" id="submit" value="جستجو" /></td>
					<?php  } ?>
				  	 </tr> 

				  <tr> 
                  
                            <td colspan="17"
                            <span class="f14_font" >لیست وضعیت شرکت های مهندسین مشاور</span>  
                            </td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
				   </tr>
			
                     <?php 

                         $permitrolsid = array("1", "4", "5", "13", "14", "6", "15", "16", "7", "17","18","20","19");
                         $permitrolsid3 = array("1", "10", "9", "4", "5", "13", "14", "6", "15", "16", "7", "17","18","20","19");
                 
   				        if (in_array($login_RolesID, $permitrolsid3))
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"5\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                            <th colspan=\"2\" class=\"f13_font\" >پروژه انجام داده</th>
                            <th colspan=\"2\" class=\"f13_font\" >پروژه در دست اجرا</th>
                            <th class=\"f13_font\" >دلایل</th>
                        </tr>
		
					 <tr>
                            <th class=\"f10_font\" ></th>
                            <th class=\"f10_font\" >نام</th>
                            <th class=\"f10_font\" >تاریخ تاسیس</th>
                            <th class=\"f10_font\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_font\" >مدیرعامل</th>
                            <th class=\"f10_font\" >کارشناس فنی</th>
							<th class=\"f10_font\" >آدرس</th>
							
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            <th class=\"f10_font\" >تعداد</th>
                            <th class=\"f10_font\" >مجموع مساحت</th>
                            <th class=\"f10_font\" >تعداد</th>
                            <th class=\"f10_font\" >مجموع مساحت</th>
                            <th class=\"f10_font\" >عدم صلاحیت</th>
                        </tr>";
                            
                        else
                        
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"5\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                        </tr>
		
					 <tr>
                            <th class=\"f10_font\" ></th>
                            <th class=\"f10_font\" >نام</th>
                            <th class=\"f10_font\" >تاریخ تاسیس</th>
                            <th class=\"f10_font\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_font\" >مدیرعامل</th>
                            <th class=\"f10_font\" >کارشناس فنی</th>
							<th class=\"f10_font\" >آدرس</th>
							<th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                        </tr>";    
                     
           ?> 	</thead>
<?php        
 			
                    
                    $Total=0;
                    $rown=0;
                    $Description="";
                    while($resquery = mysql_fetch_assoc($result))
                    {      

						$TBLNAME= "designerco";
						$TITLE = 'شركت';
						$IDdesigner = $resquery['DesignerCoID'];
						$ID = $TBLNAME.'_'.$TITLE.'_'.$IDdesigner;	
						
                        $errors="";
						$retarrayval =  member_de_error($resquery["copermisionvalidate"],gregorian_to_jalali(date('Y-m-d')),
										compelete_date($resquery["boardvalidationdate"]),
										$resquery["designercnt"],$resquery["duplicatedesigner"]);
						
						foreach($retarrayval as $key=>$value)
						$errors.= $value;
							
                       if ($resquery["ischange"])
                                $cl='6100ff'; 
                            else if (strlen($errors)>0) $cl='ff0000'; else $cl='000000';    
                        
                       
                        //if (!($showm>0) && strlen($errors)>0) continue;
                            
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                             print "<tr '>";
                              
?>                      
                            
                            
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["designercoTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["fundationYear"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["boardchangedate"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["BossName"].' '.$resquery["bosslname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["dname"] ; ?></td>
                            
                            
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo $resquery["CoAddress"] ; ?></td>
                            
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["corank"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php  echo $resquery['coprojectarea'] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery['coprojectnumber'];  ?></td>
                            <td class="f10_font<?php echo $b; ?>"  colspan="1" style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                            echo $resquery["coprojectsum"]."</td>
                            <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[copermisionvalidate]</td>";
                         



           if (($login_DesignerCoID !=$resquery['DesignerCoID']))
	
	        {
			   $permit=$permitrolsid;					
			}else{			
               $permit=$permitrolsid3;					
			}				
	
						 
                            if (in_array($login_RolesID, $permit))
                            {
                              echo "<td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[doneprgcnt] <br>($resquery[suratcntdone])</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".round($resquery["doneprg"],1)." <br>(".round($resquery["surathektdone"],1).")</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".round($resquery["curprgcnt"],1)."<br> (".$resquery["suratcntonrun"].")</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".round($resquery["curprg"],1)." <br>(".round($resquery["surathektonrun"],1).")</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>"; ?>
							  <td><a target="_blank" href="<?php print "reports/chart_applicantstatedateDesign.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'_'.$IDdesigner.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/chart.png' title=' نمودار '></a></td>
							  <?php 
							  
							  $permitrolsid1 = array("1", "4","18","20","21","17");
							  $permitrolsid2 = array("1", "2","3","9","10","17");
  
								if (in_array($login_RolesID, $permitrolsid1)) { ?>
                               <td><a target="_blank" href="<?php print "codding/codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/file-edit-icon.png' title=' ويرايش '></a></td>
							  <td><a target="_blank" href="<?php print "insert/approvedocumentcompany.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/app-delete-icon.png' title=' تاییدیه '></a></td>
                              <td><a target="_blank" href="<?php print "insert/entezami.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/Editinf.jpg' '></a></td>
							  
							  
                             <?php  } else if (in_array($login_RolesID, $permitrolsid2)){?>
						      <td><a target="_blank" href="<?php print "insert/approvedocumentcompany1.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/file-edit-icon.png' title='مشخصات'></a></td>

						
                         <?php    }
						 }
                        
                            
                            echo "</tr>";
                        
                       } 
                        
                    echo "<tr><td colspan='18'>&nbsp;</td> </tr>";
            

	              echo " 
                    
                   
                   <tr>
				      <td colspan='18' </td>
					   
			     </tr>	   
			
			              ";
                         
?>

                   
                </table>
                          </tbody>
                
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form>   
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
