<?php
/*
members_producers.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
 include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php include('includes/functions.php'); ?>
<?php


  //این تابع با دریافت یک سری ویژگی های المنت اینپوت آرا ایجاد می نماید
  /*
  $name نام المنت
  $lable لیبل کنار المنت اینپوت
  $accesskey کلید شورتکات المنت که با آن کلید فوکوس روی آن المنت می شود
  $kind نوع المنت اینپوت مثلا فقط عدد باشد یا زمان باشد و ... که در ادامه توضیح کامل داده شده است
  $tabindex ایندکس تب
  $maxlength طول حداکثر اینپوت
  $size اندازه اینپوت
  $onblur ایونت خروج از المنت
  $disabled_str اینکه المنت غیر فعال شود
  $colspan تعداد ستون های  اشغالی اینپوت در صفحه
  $type نوع المنت اینپوت که تکست چکباکس و... باشد
  $dir جهت المنت که از راست به چپ یا از چپ به راست باشد
  $access_etitle شورتکات دسترسی به المنت
  $width عرض المنت با واحد پیکسل
  $Originalonblure ایونت خروج از المنت برای نوع تاریخ
  */
  function input($name,$lable='',$accesskey='',$kind='number,readonly,persian,date,zero_mask,goodscode,accountcode,time,latindate',
  $tabindex=0,$maxlength=10,$size=10,$onblur='return true;',$disabled_str='',$colspan=1,$type='text',$dir='rtl',$access_etitle='',
  $width=0,$Originalonblure='return true;')
  {
    switch ($kind)//بر اساس نوع المنت عملیات خاصی انجام می شود
    {
	    case 'number'://اگر نوع نامبر باشد موقع فشردن کلید عدد بودن آن چک می شود
        $force_events="onkeypress='return onlynumber(this,event)' onchange=\"$onblur\" ";
	      break;
	    case 'persian':// در صورت فارسی بودن نوع ایونت خروج از المنت $onblur اجرا می شود
        $force_events=" onchange=\"$onblur\" ";
	      break;
	    case 'readonly'://در صورتی که نوع فقط خواندنی باشد امکان دریافت هیچ کلیدی وجود
	      $force_events="onkeypress='return nonekey(this,event)' onchange=\"$onblur\"";
	      break;
	    case 'zero_mask'://در صورتی که عدد باشد و پیش فرض مسک صفر خواسته باشند
	      $force_events="onkeypress='return onlynumber(this,event)' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'goodscode'://در صورتی که مسک کالا که شامل کد سه بخشی می شود و  با کاراکتر ویرگول جدا شده باشند
	      $force_events="onkeypress='return goodscode(this,event);' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'accountcode'://در صورتی که کد سه سطحی حسابداری باشد
	      $force_events="onkeypress='return accountcode(this,event);' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'date'://در صورتی که تاریخ باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"$onblur\" ; onblur=\"$Originalonblure\"  ";
	      break;
	    case 'time'://در صورتی که نوع زمان باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return timemask(this,event);' onchange=\"$onblur\" ";
	      break;
	    case 'latindate'://در صورتی که نوع تاریخ میلادی باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return onlylatindate(this,event);' onchange=\"$onblur\" ";
	      break;
    	default ://در صورتی که نوع اینپوت مشخص نباشد فقط به المنت ایونت افزوده می شود
        $force_events="onchange=\"$onblur\"";
	      break;
    }
    //-------------------------------------------------------------
		$result='';//رشته خروجی
    //-------------------------------------------------------------
    //-------------------------------------------------------------

    	if ($width>0)//در صورتی که سایز پیکسلی ارسال شده باشد به اتریبیوت های المنت افزوده می شود
	$stylestr=" style='width:$width"."px'"; else $stylestr='';

	if ($size>0)//در صورتی که سایز عددی ارسال شده باشد به اتریبیوت های المنت افزوده می شود
	$sizeStr=" size='$size'"; else $sizeStr='';

    //-------------------------------------------------------------
    if (strtolower($type)=='checkbox')//در صورتی که نوع اینپوت چکباکس باشد 
    {
      if ($lable!='')//در صورتی که لیبل چک باکس ارسال شده باشد یک ستون برای لیبل آن اختصاص داده می شود
      {
        $colspan++;//تعداد ستون های المنت اینپوت

      }
     $result.="<td colspan='$colspan'>";//افزودن اتریبیوت تعداد ستون های المنت
     
     //تخصیص اتریبیوت های ارسالی به تابع  به رشته خروجی
      $result=$result."<input class='no_print'   type='$type' name='$name' id='$name' $sizeStr $stylestr maxlength='$maxlength' dir='$dir' $force_events onFocus='this.select();' tabindex=\"$tabindex\" value='1' ";

      
      if ($lable!='' and $type!='hidden')//در صورتی که نوع اینپوت پنهان نبود و دارای لیبل بود یک المنت لیبل به رشته خروجی افزوده می شود
        $result=$result.">&nbsp;<label   for=$name accesskey=$accesskey>$lable</label>";
    }
    else//در صورتی که اینپوت چکباکس نباشد
    {
      if ($lable!='')//
      {//در صورتی که نوع اینپوت پنهان نبود و دارای لیبل بود یک المنت لیبل به رشته خروجی افزوده می شود
        $result=$result."<td>";
        if ($type!='hidden')
          $result=$result."<label   for=$name accesskey=$accesskey>$lable</label>";
        $result=$result."</td>";
      }

      $result.="<td colspan='$colspan'>";//افزودن اتریبیوت تعداد ستون های المنت

        //تخصیص اتریبیوت های ارسالی به تابع  به رشته خروجی
	  	$result.="<div id='div$name' ><input    $disabled_str type='$type' 
          name='$name' id='$name'  $sizeStr $stylestr maxlength='$maxlength' dir='$dir' $force_events
           onFocus=\"this.select();\" tabindex='$tabindex' value=\"$_POST[$name]\" >";

	}
    //بستن تگ های رشته خروجی
    $result=$result."</div></td>";
    //-------------------------------------------------------------

    return $result;
  }


if ($_POST)
{       

}
else
{
     $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $action=$linearray[0];
        $TBLID=$linearray[1];
	  
                      
                        

    if ($login_ProducersID>0)  $condition=" and producers.PipeProducer=1 ";
    $condition="  ";

  /*
    producers جدول تولیدکنندگان
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
    $sql = "SELECT producers.*
	,concat(producers.CompanyAddress,' -تلفن: ',producers.Phone2,' - ',producers.bossmobile) CoAddress,producers.rank corank
        
	,case producers.PipeProducer when 1 then 'لوله پلي اتيلن' when 2 then 'نوار تيپ' when 3 then 'فيلتراسيون' when 4 then 'پمپ و الكتروموتور' 
	                             when 5 then 'دستگاه باراني' when 6 then 'ساير اتصالات' 
								 when 101 then 'لوله پلي اتيلن' when 102 then 'نوار تيپ' when 103 then 'فيلتراسيون' when 104 then 'پمپ و الكتروموتور' 
	                             when 105 then 'دستگاه باراني' when 106 then 'ساير اتصالات' when 107 then '' end producTitle 
    
	 ,case producers.PipeProducer when 1 then 'شرکت' when 2 then 'شرکت' when 3 then 'شرکت' when 4 then 'شرکت' when 5 then 'شرکت' when 6 then 'شرکت'
	                              
								  when 101 then 'فروشگاه' when 102 then 'فروشگاه' when 103 then 'فروشگاه' when 104 then 'فروشگاه' 
								  when 105 then 'فروشگاه' when 106 then 'فروشگاه' when 107 then 'فروشگاه' end producType 

	 ,case producers.PipeProducer 
	 when 1 then  case producers.rank when 1 then 'A' when 2 then 'A' when 3 then 'B' when 4 then 'B' when 5 then 'C' end 
	 when 3 then  case producers.rank when 1 then 'A' when 2 then 'A' when 3 then 'B' when 4 then 'B' when 5 then 'C' end 
	 
	 end producPipeRank
								  
								  
    ,case 
    ifnull(tmpco.fundationYear,ifnull(producers.fundationYear,''))<>ifnull(producers.fundationYear,'') or
    ifnull(tmpco.fundationno,ifnull(producers.fundationno,''))<>ifnull(producers.fundationno,'') or
    ifnull(tmpco.fundationIssuer,ifnull(producers.fundationIssuer,''))<>ifnull(producers.fundationIssuer,'') or
    ifnull(tmpco.boardchangeno,ifnull(producers.boardchangeno,''))<>ifnull(producers.boardchangeno,'') or
    ifnull(tmpco.boardchangedate,ifnull(producers.boardchangedate,''))<>ifnull(producers.boardchangedate,'') or
    ifnull(tmpco.boardvalidationdate,ifnull(producers.boardvalidationdate,''))<>ifnull(producers.boardvalidationdate,'') or
    ifnull(tmpco.boardIssuer,ifnull(producers.boardIssuer,''))<>ifnull(producers.boardIssuer,'') or
    ifnull(tmpco.copermisionno,ifnull(producers.copermisionno,''))<>ifnull(producers.copermisionno,'') or
    ifnull(tmpco.copermisiondate,ifnull(producers.copermisiondate,''))<>ifnull(producers.copermisiondate,'') or
    ifnull(tmpco.copermisionvalidate,ifnull(producers.copermisionvalidate,''))<>ifnull(producers.copermisionvalidate,'') or
    ifnull(tmpco.copermisionIssuer,ifnull(producers.copermisionIssuer,''))<>ifnull(producers.copermisionIssuer,'') or
    ifnull(tmpco.contractordate,ifnull(producers.contractordate,''))<>ifnull(producers.contractordate,'') or
    ifnull(tmpco.contractorvalidate,ifnull(producers.contractorvalidate,''))<>ifnull(producers.contractorvalidate,'') or
    ifnull(tmpco.contractorno,ifnull(producers.contractorno,''))<>ifnull(producers.contractorno,'') or
    ifnull(tmpco.contractorIssuer,ifnull(producers.contractorIssuer,''))<>ifnull(producers.contractorIssuer,'') or
    ifnull(tmpco.engineersystemdate,ifnull(producers.engineersystemdate,''))<>ifnull(producers.engineersystemdate,'') or
    ifnull(tmpco.engineersystemvalidate,ifnull(producers.engineersystemvalidate,''))<>ifnull(producers.engineersystemvalidate,'') or
    ifnull(tmpco.engineersystemno,ifnull(producers.engineersystemno,''))<>ifnull(producers.engineersystemno,'') or
    ifnull(tmpco.engineersystemIssuer,ifnull(producers.engineersystemIssuer,''))<>ifnull(producers.engineersystemIssuer,'') or
    ifnull(tmpco.valueaddeddate,ifnull(producers.valueaddeddate,''))<>ifnull(producers.valueaddeddate,'') or
    ifnull(tmpco.valueaddedvalidate,ifnull(producers.valueaddedvalidate,''))<>ifnull(producers.valueaddedvalidate,'') or
    ifnull(tmpco.valueaddedno,ifnull(producers.valueaddedno,''))<>ifnull(producers.valueaddedno,'') or
    ifnull(tmpco.valueaddedIssuer,ifnull(producers.valueaddedIssuer,''))<>ifnull(producers.valueaddedIssuer,'')
    when 1 then 1 else 0 end ischange
    ,round(guarantee.guaranteepayval/10000000) guaranteepayval2,guarantee.guaranteeExpireDate guaranteeExpireDate2 
                FROM producers
			    left outer join guarantee on guarantee.CoID=producers.ProducersID
				left outer join tmpco on tmpco.UID=producers.producersID and type='1' 
                where ProducersID not in (142) $condition 
                ORDER BY ischange desc,PipeProducer Asc,corank,producTitle COLLATE utf8_persian_ci  ;";
   // print $sql;
    $result = mysql_query($sql); 
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست وضعیت فروشندگان/تولیدکنندگان</title>

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
            
            <form action="reports3_alloperatorstates.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                  <table align='center' border='1' id="table2">              
                   <thead>
					<tr> 
                  
                            <td colspan="10"
                            <span class="f14_fontb" >لیست وضعیت تولیدکنندگان/فروشندگان</span>  
                            </td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
				   </tr>
                     <?php 
                         $permitrolsid = array("1", "13", "14","18","20","19");
						 $permitrolsid3 = array("1", "3", "4", "5", "13", "14", "6", "15", "16", "7", "17","18","20","19");
						 
                         if (in_array($login_RolesID, $permitrolsid3))
                         echo "<tr>
                            <th colspan=\"6\" class=\"f13_fontb\" >شرکت</th>
                            <th colspan=\"2\" class=\"f13_fontb\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
							 <th class=\"f13_fontb\" >فروشنده</th>
                            <th colspan=\"1\" class=\"f13_fontb\" >دلایل</th>
							
                        </tr>
		
						<tr>
                            <th class=\"f10_fontb\" ></th>
                            <th class=\"f10_fontb\" >نام</th>
                            <th class=\"f10_fontb\" >تاریخ تاسیس</th>
                            <th class=\"f10_fontb\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_fontb\" >مدیرعامل</th>
							<th class=\"f10_fontb\" >آدرس</th>
		
                            <th class=\"f10_fontb\" >گرید</th>
                            <th class=\"f10_fontb\" >تاریخ اعتبار</th>
							<th class=\"f10_fontb\" ></th>
                            <th class=\"f10_fontb\" >عدم صلاحیت</th>
                        </tr>";
                            
                        else
                        
                         echo "<tr>
                            <th colspan=\"6\" class=\"f13_fontb\" >شرکت</th>
                            <th colspan=\"2\" class=\"f13_fontb\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                        </tr>
		
						<tr>
                            <th class=\"f10_fontb\" ></th>
                            <th class=\"f10_fontb\" >نام</th>
                            <th class=\"f10_fontb\" >تاریخ تاسیس</th>
                            <th class=\"f10_fontb\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_fontb\" >مدیرعامل</th>
							<th class=\"f10_fontb\" >آدرس</th>
		                    <th class=\"f10_fontb\" >'گرید</th>
                            <th class=\"f10_fontb\" >تاریخ اعتبار</th>
                        </tr>";    
            ?> 	</thead>
<?php        
                    $Permissionvals=supervisorcoderrquirement_sql($login_ostanId);
                    $Total=0;
                    $rown=0;
                    $Description="";
					if ($login_isfulloption==1)
                    while($resquery = mysql_fetch_assoc($result))
                    {    
						if ($resquery['ProducersID']==148 || $resquery['ProducersID']==183) continue;
						$TBLNAME= "producers";
						$TITLE = 'شركت';
						$IDproducer = $resquery['ProducersID'];
						$ID = $TBLNAME.'_'.$TITLE.'_'.$IDproducer;						
						$errors="";
                        
						$retarrayval= producer_error($Permissionvals['p1Zemanat'],$Permissionvals['p2Zemanat'],$Permissionvals['p3Zemanat'],$Permissionvals['p4Zemanat']
						,$Permissionvals['p5Zemanat'],$Permissionvals['p1Zpishhamzaman'],$Permissionvals['p2Zpishhamzaman'],$Permissionvals['p3Zpishhamzaman']
						,$Permissionvals['p4Zpishhamzaman'],$Permissionvals['p5Zpishhamzaman'],$Permissionvals['p1Zpishhamzamanvol'],$Permissionvals['p2Zpishhamzamanvol']
						,$Permissionvals['p3Zpishhamzamanvol'],$Permissionvals['p4Zpishhamzamanvol'],$Permissionvals['p5Zpishhamzamanvol']
						,gregorian_to_jalali(date('Y-m-d')),$resquery["corank"],$resquery["guaranteeExpireDate"],$resquery["guaranteepayval"]
						,0,0,0,$resquery["boardvalidationdate"],$resquery["copermisionvalidate"],$resquery["valueaddedvalidate"]);



if ($resquery["guaranteeExpireDate2"]>=gregorian_to_jalali(date('Y-m-d'))) {$retarrayval[1]='';$retarrayval[2]='';}

						foreach($retarrayval as $key=>$value)
						{
							if ( ($resquery["PipeProducer"]>0 && $resquery["PipeProducer"]<6) ) $errors.=$value;
						}
											
                       if ($resquery["ischange"])
                                $cl='6100ff'; 
                            else if (strlen($errors)>0) $cl='ff0000'; else $cl='000000';    
                         //if (!($showm>0) && strlen($errors)>0) continue;
                            
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                             print "<tr '>";
                          if ($login_designerCO==1) $PipeProducer=$resquery["PipeProducer"];
?>                      
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["producType"] .'<br>'. $resquery["Title"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["fundationYear"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["boardchangedate"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["BossName"].' '.$resquery["bosslname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo $resquery["CoAddress"] ; ?></td>
                             <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["producPipeRank"] ; ?></td>
                            <?php  
                            echo "
                            <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[copermisionvalidate]</td>";
							?>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["producTitle"].'<br>'.$PipeProducer ; ?></td>
							<?php

						   if (($login_ProducersID !=$resquery['ProducersID']))
							{ $permit=$permitrolsid;} else {$permit=$permitrolsid3;}				
												
							if (in_array($login_RolesID, $permit))
                            {
								  echo "
								  <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>"; 
								   $permitrolsid1 = array("1", "4","18","20","21");
								   $permitrolsid2 = array("1", "2","3","9","10");

									if (in_array($login_RolesID, $permitrolsid1)) 
									{ ?>
										<td><a target="_blank" href="<?php print "codding/codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
											<img style = 'width: 20px;' src='img/file-edit-icon.png' title=' ويرايش '></a></td>
										<td><a target="_blank" href="<?php print "insert/approvedocumentcompany.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
											<img style = 'width: 20px;' src='img/app-delete-icon.png' title=' تاییدیه '></a></td>
										<td><a target="_blank" href="<?php print "insert/approveaccessories.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
											<img style = 'width: 20px;' src='img/accept.png' title=' تاییدیه لوازم '></a></td>
										<td><a target="_blank" href="<?php print "insert/entezami.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
										<img style = 'width: 20px;' src='img/Editinf.jpg' '></a></td>
							  <?php } 			   
								  else if (in_array($login_RolesID, $permitrolsid2))
									{ ?>
										<td><a target="_blank" href="<?php print "insert/approvedocumentcompany1.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
											<img style = 'width: 20px;' src='img/file-edit-icon.png' title='مشخصات'></a></td>
							  <?php } ?>
							  
								
							   <?php
							}
                            echo "</tr>";
                     }
                    echo "<tr><td colspan='18'>&nbsp;</td> </tr>";
                  echo " <tr><td colspan='18' </td></tr> ";
				?>
              </table>
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
