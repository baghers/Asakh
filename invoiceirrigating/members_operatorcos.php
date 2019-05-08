<?php 
/*
members_operatorcos.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
viewapplicantstate.php
*/
	include('includes/connect.php'); 
		include('includes/check_user.php'); 
		include('includes/functions.php'); 

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

/*تابع ایجاد رشته پرس و جوی مشخصات و وضعیت پیمانکاران
$condition شرط محدود کننده پرس و جو
$orderby دستور مرتب سازی پرس و جو
*/
function member_op_sql($condition,$orderby)
{   /*
    operatorco جدول شرکت های پیمانکار
    designer جدول شرکت های طراح
    members جدول اعضای هیئت مدیره
    operatorapprequest جدول پیشنهاد قیمت های طرح
    clerk جدول کاربران
    operatorco.fundationYear تاریخ تاسیس شرکت پیمانکار
    operatorco.fundationno شماره مدرک تاسیس پیمانکار
    operatorco.fundationIssuer مرجع صادر کننده صلاحیت پیمانکار
    operatorco.boardchangeno شماره نامه آخرین تغییرات
    operatorco.boardchangedate تاریخ آخرین تغییرات هیئت مدیره
    operatorco.boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
    operatorco.boardIssuer مرجع صادرکننده مدرک هیئت مدیره
    operatorco.copermisionno تعداد پروژه های قابل انجام
    operatorco.StarCo تعداد ستاره های شرکت
    operatorco.ent_Num تعداد انتظامی بودن شرکت
    operatorco.ent_DateTo پایان انتظامی بودن شرکت
    operatorco.copermisiondate تاریخ مجوز شرکت
    operatorco.copermisionvalidate تاریخ اعتبار مجوز شرکت
    operatorco.copermisionIssuer مرجع صادر کننده مجوز شرکت
    operatorco.contractordate تاریخ قرارداد شرکت
    operatorco.contractorvalidate تاریخ اعتبار قرارداد شرکت
    operatorco.contractorno شماره نامه قرارداد شرکت
    operatorco.contractorIssuer مرجع صادرکننده قرارداد شرکت
    operatorco.contractorRank1 رتبه شرکت نفر 1
    operatorco.contractorField1 شرح رتبه شرکت نفر 1
    operatorco.contractorRank2 رتبه شرکت نفر 2
    operatorco.contractorField2 شرح رتبه شرکت نفر 2
    operatorco.engineersystemdate تاریخ مدرک مهندس شرکت
    operatorco.engineersystemvalidate تاریخ اعتبار مدرک مهندس شرکت
    operatorco.engineersystemno شماره مدرک مهندس شرکت
    operatorco.engineersystemIssuer مرجع صادر کننده مدرک مهندس شرکت
    operatorco.engineersystemRank رتبه  مهندس شرکت
    operatorco.engineersystemField شرح مهندس شرکت
    operatorco.valueaddeddate تاریخ گواهی ارزش افزوده
    operatorco.valueaddedvalidate تاریخ اعتبار گواهی ارزش افزوده
    operatorco.valueaddedno شماره گواهی ارزش افزوده
    operatorco.valueaddedIssuer مرجع گواهی ارزش افزوده
    operatorco.operatorcoID شناسه شرکت مجری
    membersinfo.FName نام
    membersinfo.LName نام خانوادگی
    operatorco.projectcount92 تعداد پروژه های اول دوره پیمانکار
    operatorco.projecthektar92 مساحت پروژه های انجام داده شده پیمانکار
    operatorco.Title عنوان شرکت
	operatorco.CompanyAddress آدرس شرکت
    operatorco.Phone2 تلفن دوم شرکت
    operatorco.bossmobile موبایل مدیر عامل شرکت 
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
    operatorco.BossName نام مدیر عامل
    operatorco.bosslname نام خانوادگی مدیر عامل
    */
    $sql = "SELECT distinct operatorco.fundationYear,operatorco.fundationno,operatorco.fundationIssuer,operatorco.boardchangeno
				,operatorco.boardchangedate,operatorco.boardvalidationdate,operatorco.boardIssuer,operatorco.copermisionno,operatorco.StarCo
				,operatorco.ent_Num,operatorco.ent_DateTo
				,operatorco.copermisiondate,operatorco.copermisionvalidate,operatorco.copermisionIssuer,operatorco.contractordate
				,operatorco.contractorvalidate,operatorco.contractorno,operatorco.contractorIssuer,operatorco.contractorRank1
				,operatorco.contractorField1,operatorco.contractorRank2,operatorco.contractorField2,operatorco.engineersystemdate
				,operatorco.engineersystemvalidate,operatorco.engineersystemno,operatorco.engineersystemIssuer,operatorco.engineersystemRank
				,operatorco.engineersystemField,operatorco.valueaddeddate
				,operatorco.valueaddedvalidate,operatorco.valueaddedno,operatorco.valueaddedIssuer,operatorco.operatorcoID
				,membersinfo.FName BossName,membersinfo.LName bosslname,operatorco.projectcount92,operatorco.projecthektar92,operatorco.Title operatorcoTitle
				,concat(operatorco.CompanyAddress,' -تلفن: ',operatorco.Phone2,' - ',operatorco.bossmobile) CoAddress
				,corank
				,firstperiodcoprojectarea,firstperiodcoprojectnumber,coprojectsum
				,projecthektardone,simultaneouscnt,thisyearprgarea,above20cnt,above55cnt,currentprgarea
				,projectcountdone,clerk.clerkid ClerkIDinvestigation
                ,designerinfo.designercnt
                ,designerinfo.dname
                ,designerinfo.duplicatedesigner
                ,membersinfo.duplicatemembers
                ,allreq.cnt reqcnt,allwinreq.wincnt,avgpmreq.avg avgpmreq,avgpmreqa.avga avgpmreqa
                ,operatorco.BossName BossNameop,operatorco.bosslname bosslnameop
                FROM operatorco

                left outer join (
                select count(*) designercnt,max(concat(designer.FName,' ',designer.LName)) dname,designer.operatorcoid
				,case designer2.NationalCode>0 when 1 then 1 else 0 end duplicatedesigner
                 from designer 
                left outer join designer designer2 on designer2.NationalCode=designer.NationalCode 
                and (designer2.operatorcoid<>designer.operatorcoid or designer2.designercoID<>designer.designercoID
                or (designer2.designercoID>0 and designer.operatorcoid>0 and designer2.designercoID<>designer.operatorcoid)
                or (designer.designercoID>0 and designer2.operatorcoid>0 and designer.designercoID<>designer2.operatorcoid)
                )
                where designer.operatorcoid>0
                group by designer.operatorcoid) designerinfo on designerinfo.operatorcoid=operatorco.operatorcoID
             

   left outer join (
                select count(*) memberscnt,members.FName,members.LName,members.operatorcoid,members.Position
				,case members2.NationalCode>0 when 1 then 1 else 0 end duplicatemembers
                from members 
				left outer join members members2 on members2.NationalCode=members.NationalCode and members2.operatorcoid<>members.operatorcoid
                where members.operatorcoid>0 and members.Position=1
                group by members.operatorcoid) membersinfo on membersinfo.operatorcoid=operatorco.operatorcoID
                
             
			 
                left outer join (SELECT operatorco.operatorcoid,count(*) cnt FROM `operatorapprequest` 
                inner join operatorco on operatorco.operatorcoid= operatorapprequest.operatorcoid group by operatorco.operatorcoid) allreq on 
                allreq.operatorcoid=operatorco.operatorcoid

                left outer join (SELECT operatorco.operatorcoid,count(*) wincnt FROM `operatorapprequest` 
                inner join operatorco on operatorco.operatorcoid= operatorapprequest.operatorcoid where state=1 group by operatorco.operatorcoid) allwinreq on 
                allwinreq.operatorcoid=operatorco.operatorcoid
				
                left outer join (SELECT operatorco.operatorcoid,avg(price/(costprice*1.3*1.05))avg FROM `operatorapprequest` 
                inner join operatorco on operatorco.operatorcoid= operatorapprequest.operatorcoid 
                where price/(costprice*1.3*1.05)<=2 
                group by operatorco.operatorcoid) avgpmreq on 
                avgpmreq.operatorcoid=operatorco.operatorcoid

				left outer join (SELECT operatorco.operatorcoid,avg(price/(costprice*1.3*1.05))avga FROM `operatorapprequest` 
                inner join operatorco on operatorco.operatorcoid= operatorapprequest.operatorcoid 
                where price/(costprice*1.3*1.05)<=2 and state=1
                group by operatorco.operatorcoid) avgpmreqa on avgpmreqa.operatorcoid=operatorco.operatorcoid
                
				inner join clerk on clerk.HW=operatorco.operatorcoid
				
				
                where ifnull(operatorco.Disabled,0)<>1 and ifnull(clerk.Disable,0)<>1 $condition 
				";
    
    
    
    /*
    tmpco جدول اطلاعات ثبتی و پیشنهادی شرکت های پیمانکار
    tmpco.fundationYear تاریخ تاسیس شرکت پیمانکار
    tmpco.fundationno شماره مدرک تاسیس پیمانکار
    tmpco.fundationIssuer مرجع صادر کننده صلاحیت پیمانکار
    tmpco.boardchangeno شماره نامه آخرین تغییرات
    tmpco.boardchangedate تاریخ آخرین تغییرات هیئت مدیره
    tmpco.boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
    tmpco.boardIssuer مرجع صادرکننده مدرک هیئت مدیره
    tmpco.copermisionno تعداد پروژه های قابل انجام
    tmpco.StarCo تعداد ستاره های شرکت
    tmpco.ent_Num تعداد انتظامی بودن شرکت
    tmpco.ent_DateTo پایان انتظامی بودن شرکت
    tmpco.copermisiondate تاریخ مجوز شرکت
    tmpco.copermisionvalidate تاریخ اعتبار مجوز شرکت
    tmpco.copermisionIssuer مرجع صادر کننده مجوز شرکت
    tmpco.contractordate تاریخ قرارداد شرکت
    tmpco.contractorvalidate تاریخ اعتبار قرارداد شرکت
    tmpco.contractorno شماره نامه قرارداد شرکت
    tmpco.contractorIssuer مرجع صادرکننده قرارداد شرکت
    tmpco.contractorRank1 رتبه شرکت نفر 1
    tmpco.contractorField1 شرح رتبه شرکت نفر 1
    tmpco.contractorRank2 رتبه شرکت نفر 2
    tmpco.contractorField2 شرح رتبه شرکت نفر 2
    tmpco.engineersystemdate تاریخ مدرک مهندس شرکت
    tmpco.engineersystemvalidate تاریخ اعتبار مدرک مهندس شرکت
    tmpco.engineersystemno شماره مدرک مهندس شرکت
    tmpco.engineersystemIssuer مرجع صادر کننده مدرک مهندس شرکت
    tmpco.engineersystemRank رتبه  مهندس شرکت
    tmpco.engineersystemField شرح مهندس شرکت
    tmpco.valueaddeddate تاریخ گواهی ارزش افزوده
    tmpco.valueaddedvalidate تاریخ اعتبار گواهی ارزش افزوده
    tmpco.valueaddedno شماره گواهی ارزش افزوده
    tmpco.valueaddedIssuer مرجع گواهی ارزش افزوده
    tmpco.operatorcoID شناسه شرکت مجری
    membersinfo.FName نام
    membersinfo.LName نام خانوادگی
    tmpco.projectcount92 تعداد پروژه های اول دوره پیمانکار
    tmpco.projecthektar92 مساحت پروژه های انجام داده شده پیمانکار
    tmpco.Title عنوان شرکت
	tmpco.CompanyAddress آدرس شرکت
    tmpco.Phone2 تلفن دوم شرکت
    tmpco.bossmobile موبایل مدیر عامل شرکت 
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
    tmpco.BossName نام مدیر عامل
    tmpco.bosslname نام خانوادگی مدیر عامل
    */
    
    $sql="select 
	distinct allco.fundationYear,allco.fundationno,allco.fundationIssuer,allco.boardchangeno,
	allco.boardchangedate,allco.boardvalidationdate,allco.boardIssuer,allco.copermisionno,allco.StarCo,allco.ent_Num,allco.ent_DateTo,
	allco.copermisiondate,allco.copermisionvalidate,
	allco.copermisionIssuer,allco.contractordate,allco.contractorvalidate,allco.contractorno,allco.contractorIssuer
	,allco.contractorRank1,allco.contractorField1,allco.contractorRank2,allco.contractorField2,allco.engineersystemdate,
	allco.engineersystemvalidate,allco.engineersystemno,allco.engineersystemIssuer,allco.engineersystemRank
	,allco.engineersystemField,allco.valueaddeddate,allco.valueaddedvalidate,
	allco.valueaddedno,allco.valueaddedIssuer,allco.operatorcoID,allco.BossName,allco.bosslname,allco.projectcount92,allco.projecthektar92
	,allco.CoAddress,allco.operatorcoTitle,allco.corank,allco.firstperiodcoprojectarea,allco.firstperiodcoprojectnumber,allco.coprojectsum
,allco.projecthektardone,allco.simultaneouscnt,allco.thisyearprgarea,allco.currentprgarea,allco.above20cnt,allco.above55cnt,allco.projectcountdone	
	
	,allco.designercnt,allco.dname,allco.duplicatedesigner,allco.reqcnt,allco.wincnt,allco.avgpmreq,allco.avgpmreqa,
    allco.BossNameop,allco.bosslnameop,
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
    when 1 then 1 else 0 end ischange,allco.ClerkIDinvestigation

 from ($sql) allco 
left outer join tmpco on tmpco.UID=allco.operatorcoID and type='2' 

$orderby;";
 
 //echo $sql; 
return ($sql);
 
 } 




$orderby=' order by ischange desc,corank desc,operatorcoTitle COLLATE utf8_persian_ci  ';
$showm=0;//نمایش اطلاعات تکمیلی
$showa=0;//نمایش تمام شرکت های فعال و غیر فعال

if ($_POST)//در صورتی که دکمه سابمیت کلیک شده باشد
{       

  switch ($_POST['IDorder']) //شناسه مرتب سازی
  {
    case 1: $orderby=' order by operatorcoTitle COLLATE utf8_persian_ci'; break; //عنوان مرتب سازی
    case 2: $orderby=' order by fundationYear ,operatorcoTitle COLLATE utf8_persian_ci  '; break;//سال تاسیس و عنوان شرکت
    case 3: $orderby=' order by boardvalidationdate ,operatorcoTitle COLLATE utf8_persian_ci  '; break;//تاریخ اعتبار هیئت مدیره و عنوان شرکت
    case 4: $orderby=' order by corank desc,operatorcoTitle COLLATE utf8_persian_ci  '; break;//رتبه شرکت و عنوان شرکت
    case 5: $orderby=' order by copermisionvalidate,operatorcoTitle COLLATE utf8_persian_ci   '; break;//تاریخ مجوز شرکت و عنوان شرکت
    case 6: $orderby=' order by projecthektardone ,operatorcoTitle COLLATE utf8_persian_ci  '; break;//مساحت پروژه های انجام داده و عنوان شرکت
    case 7: $orderby=' order by thisyearprgarea ,operatorcoTitle COLLATE utf8_persian_ci  '; break;// تعداد پروژه های جاری و عنوان شرکت
    case 8:  $orderby=' order by simultaneouscnt,operatorcoTitle COLLATE utf8_persian_ci   '; break; //مساحت پروژه های امسال و عنوان شرکت
     case 9:  $orderby=' order by currentprgarea,operatorcoTitle COLLATE utf8_persian_ci   '; break; //پروژه های جاری شرکت و عنوان شرکت
    default: $orderby=' order by corank desc,operatorcoTitle COLLATE utf8_persian_ci  '; break;//رتبه شرکت و عنوان شرکت 
  }

 
if ($_POST['showm']=='on')  $showm=1;
if ($_POST['showa']=='on')  $showa=1;
      
  
}
//استخراج اطلاعات تنظیمی برنامه از جدول تنظیمات برنامه
//$login_ostanId شناسه استان
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);    				
	                 
                        
  if ($_POST['ostan']>0)//افزودن محدودیت استان به پرس و جو 
   {$selectedCityId=$_POST['ostan'];$condition="and substring(clerk.CityId,1,2)=substring('$_POST[ostan]',1,2)";}
  else
  {
    $selectedCityId=$login_CityId;
    $condition="and substring(clerk.CityId,1,2)=substring('$login_CityId',1,2)";
  }
  
 
 switch ($_POST['IDrank'])// افزودن محدودیت فیلتر رتبه شرکت ها 
  {
    case 1: $condition.=' and operatorco.corank=1 '; break; 
    case 2: $condition.=' and operatorco.corank=2  '; break;
    case 3: $condition.=' and operatorco.corank=3  '; break;
    case 4: $condition.=' and operatorco.corank=4  '; break;
    case 5: $condition.=' and operatorco.corank=5   '; break;
    case 6: $condition.=' and operatorco.corank=5  '; break;
    case 7: $condition.=' and operatorco.corank in (1,2,3,4)  '; break;
     case 8: $condition.=' and operatorco.corank in (1,2,3)  '; break;
    case 9: $condition.=' and operatorco.corank=0  '; break;
    default: $condition.='   '; break; 
  }
 
switch ($_POST['IDabove20cnt'])//افزودن محدودیت تعداد پروژه های بالای ده هکتار 
  {
    case 1: $condition.=' and operatorco.above20cnt=1 '; break; 
    case 2: $condition.=' and operatorco.above20cnt=2  '; break;
    case 3: $condition.=' and operatorco.above20cnt=3  '; break;
    case 4: $condition.=' and operatorco.above20cnt>2  '; break;
    case 5: $condition.=' and operatorco.above20cnt=0   '; break;
    default: $condition.='   '; break; 
  }

switch ($_POST['IDzarfiat'])//افزودن شرط میزان ظرفیت 
  {
    //شرکت های دارای ظرفیت
    case 1: $condition.=' and operatorco.simultaneouscnt<7 and case operatorco.corank when 5 then 2 when 4 then 3 when 3 then 3 when 2 then 3 when 1 then 3 else 0 end>operatorco.above20cnt '; break; 
    //شرکت های فاقد ظرفیت
    case 2: $condition.=' and operatorco.simultaneouscnt>6 or case operatorco.corank when 5 then 2 when 4 then 3 when 3 then 3 when 2 then 3 when 1 then 3 else 0 end<=operatorco.above20cnt '; break; 
    //شرکت های دارای ظرفیت
    case 3: $condition.=' and operatorco.simultaneouscnt<7 and case operatorco.corank when 5 then 2 when 4 then 3 when 3 then 3 when 2 then 3 when 1 then 3 else 0 end>operatorco.above20cnt '; break; 
    default: $condition.='   '; break; 
  }

 
  $sql=member_op_sql($condition,$orderby);//ایجاد رشته پرس و جو با شرط و مرتب سازی
  try 
  {		
    $result = mysql_query($sql);
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
  }
              

     
    //پرس و جوی کومبو باکس مرتب سازی
	$query="
		select 'نام شركت' _key,1 as _value union all
		select 'تاريخ تاسيس' _key,2 as _value union all 
		select 'اعتبار هيئت مديره' _key,3 as _value union all
		select 'پایه' _key,4 as _value union all
		select 'اعتبار مجوز' _key,5 as _value union all
		select 'مساحت پروژه انجام داده' _key,6 as _value union all
		select 'پروژه در دست اجرا سال' _key,7 as _value union all
		select 'تعداد کل پروژه در اجرا' _key,8 as _value union all
		select 'مساحت پروژه در اجرا' _key,9 as _value ";
		$IDorder = get_key_value_from_query_into_array($query);

		if (!$_POST['IDorder'])
			$IDorderval=4;
		else $IDorderval=$_POST['IDorder'];
        
        //پرس و جوی کومبوباکس فیلتر رتبه شرکت ها
		$query="
		select '1' _key,1 as _value union all
		select '2' _key,2 as _value union all 
		select '3' _key,3 as _value union all
		select '4' _key,4 as _value union all
		select '5' _key,5 as _value union all
		select '5+' _key,6 as _value union all
		select '1,2,3,4' _key,7 as _value union all
		select '1,2,3' _key,8 as _value union all
		select '0' _key,9 as _value union all
	 	select '' _key,10 as _value ";
		$IDrank = get_key_value_from_query_into_array($query);

		if (!$_POST['IDrank'])
			$IDrankval=10;
		else $IDrankval=$_POST['IDrank'];

        //پرس و جوی کومبوباکس فیلتر تعداد طرح های بالای 20 هکتار
		$query="
		select '1' _key,1 as _value union all
		select '2' _key,2 as _value union all 
		select '3' _key,3 as _value union all
		select '3+' _key,4 as _value union all
		select '0' _key,5 as _value union all
	 	select '' _key,6 as _value ";
		$IDabove20cnt = get_key_value_from_query_into_array($query);

		if (!$_POST['IDabove20cnt'])
			$IDabove20cntval=6;
		else $IDabove20cntval=$_POST['IDabove20cnt'];
		
		//پرس و جوی فیلتر نوع شرکت ها اعم از ظرفیت دار و فاقد ظرفیت
		$query="
		select 'دارای ظرفیت' _key,1 as _value union all
		select 'فاقد ظرفیت' _key,2 as _value union all 
		select 'دارای ظرفیت صلاحیتدار' _key,3 as _value union all 
	 	select '' _key,4 as _value ";
		$IDzarfiat = get_key_value_from_query_into_array($query);

		if (!$_POST['IDzarfiat'])
			$IDzarfiatval=4;
		else $IDzarfiatval=$_POST['IDzarfiat'];
		
		
					$sum1=0;//مجموع پروژه های انجام داده شده
                    $sum2=0;//مجموع مساحت پروژه های انجام داده شده
                    $sum3=0;//مجموع پروژه های در دست اجرای شرکت ها
                    $sum31=0;//مجموع پروژه های بالای 20 هکتار شرکت ها
                    $sum4=0;//مجموع پروژه های در دست اجرای امسال شرکت ها
                    $sum5=0;//مجموع تعداد پروژه های اول دوره شرکت ها
                    $sum6=0;//مجموع مساحت پروژه های اول دوره شرکت ها
                    $sum7=0;//مجموع ظرایب پیشنهاد قیمت های شرکت
                    $sum8=0;//مجموع ظرایب پیشنهاد قیمت های انتخابی
                    $resqueryprojectcountdone=0;$resqueryprojecthektardone=0;
					$npishnahad=0;
					while($resquery = mysql_fetch_assoc($result))//حلقه محاسبه مجموع  مقادیر
                    {  
					$resqueryprojectcountdone=$resquery['projectcountdone']-$resquery['projectcount92'];
  				    $resqueryprojecthektardone=$resquery['projecthektardone']-$resquery['projecthektar92'];
					
			           $sum1+=$resqueryprojectcountdone;
				       $sum2+=$resqueryprojecthektardone;
					   $sum3+=$resquery["simultaneouscnt"];
					   $sum31+= $resquery["above20cnt"];
                       $sum4+=round($resquery["currentprgarea"],1);
					   $sum5+=$resquery["projectcount92"];
				       $sum6+=$resquery["projecthektar92"];
					   $sum7+=round($resquery["reqcnt"],1);
					   $sum8+=round($resquery["wincnt"],1);
					   if ($resquery["reqcnt"]>0) $npishnahad++;
                        $strallval.=$resquery['operatorcoTitle'].'_'.$resquery['corank'].'_'.
                        $resqueryprojectcountdone.'_'.$resqueryprojecthektardone.'_'.$resquery['simultaneouscnt'].'_'.$resquery['currentprgarea'].'_'.
                        $resquery['projectcount92'].'_'.$resquery['projecthektar92'].'_';//متغیر اطلاعات کلی شرکت ها
 				
					  }	
			  
mysql_data_seek( $result, 0 );
					  
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست وضعیت شرکت های مجری</title>

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
	background-color:#f1f1f1;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f14_fontright{
	background-color:#f1f1f1;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:right;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	background-color:#f1f1f1;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		background-color:#ffffff;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#f1f1f1;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
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
<?php if ($showm==0)
		{ 
 
		if ($login_designerCO==1)
		{	
		?>

			<!-- content -->
			<div id="content">
            
          
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
          
                <table align='center' class="page" border='1'>              
				
          	 <tr> 
			  <form action="members_operatorcos.php" method="post">
			  <?php require('includes/csrf_pag.php'); ?>
			 <td colspan="1" ></td>
              <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,'','100');?> 
				     <td colspan="1" class="label" >اطلاعات&nbsp;تکمیلی</td>
                     <td class="data" ><input name="showm" type="checkbox" id="showm"  <?php if ($showm>0) echo "checked"; ?> /></td>
					 
				<?php
                    $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                     where substring(ostan.id,3,5)='00000'
                     order by _key  COLLATE utf8_persian_ci";
                     $allg1id = get_key_value_from_query_into_array($sqlselect);

				if ($login_designerCO==1)
				  {print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId,'','100');?>
				 <td colspan="1" class="label" >همه</td>
                     <td class="data" ><input name="showa" type="checkbox" id="showa"  <?php if ($showa>0) echo "checked"; }?> /></td>
					 
				  <?php print select_option('IDrank','پایه',',',$IDrank,0,'','','1','rtl',0,'',$IDrankval,'','100');?> 
				  <?php print select_option('IDabove20cnt','تعداد',',',$IDabove20cnt,0,'','','1','rtl',0,'',$IDabove20cntval,'','100');?> 
				  <?php print select_option('IDzarfiat','',',',$IDzarfiat,0,'','','1','rtl',0,'',$IDzarfiatval,'','100');?> 

					 
			 <td colspan="1" > <input   name="submit" type="submit" id="submit" value="جستجو" /></td>
			 </form>
         	 </tr> 
                </table>              
               <table align='center' class="page" border='1'>              
				  
			<?php if($_SESSION['userid']!=419){?>
				 <tr> 
				
			<form action="chart_member2.php" method="post"  target="_blank" >
			  <?php //require('includes/csrf_pag.php'); ?>
				 <?php 
				 $strallval5=$strallval.'~5~'.$sum5;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval5'  /></td>";?> 
				 <td colspan="3"  >پروژه های قبل از 92<input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="تعداد"  /></td>
				 </form>
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval6=$strallval.'~6~'.$sum6;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval6'  /></td>";?> 
				 <td colspan="2"  > <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="مساحت" /></td>
				 </form>
				 
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval1=$strallval.'~1~'.$sum1;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval1'  /></td>";?> 
				 <td colspan="3"  > پروژه های انجام شده <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="تعداد" /></td>
				 </form>
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval2=$strallval.'~2~'.$sum2;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval2'  /></td>";?> 
				 <td colspan="2"  > <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="مساحت" /></td>
				 </form>
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval3=$strallval.'~3~'.$sum3;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval3'  /></td>";?> 
				 <td colspan="3"  > پروژه های در دست انجام <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="تعداد" /></td>
				 </form>
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval4=$strallval.'~4~'.$sum4;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval4'  /></td>";?> 
				 <td colspan="2"  > <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="مساحت" /></td>
				 </form>
				 
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval7=$strallval.'~7~'.$sum7;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval7'  /></td>";?> 
				 <td colspan="3"  > کلاسه پیشنهادات <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="کلاسه" /></td>
				 </form>
			<form action="chart_member2.php" method="post" target="_blank">
				 <?php 
				 $strallval8=$strallval.'~8~'.$sum8;
				 print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval8'  /></td>";?> 
				 <td colspan="2"  > <input style = "text-align:center;font-size:16;line-height:100%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="کلاسه برنده" /></td>
				 </form>
				 
				 
				 
				 
           </tr>
<?php 	
		}
		}?>
                  <table align='center' border='1' id="table2">              
                   <thead>

                    <form action="chart_member.php" method="post" target="_blank">
                        <tr> 
                        <td colspan="20"
                          <span class="f14_font" >لیست وضعیت شرکت های مجری</span>
                          <?php if($_SESSION['userid']!=419){?>  
					      <input type="submit" name="submit" value="نمودار"  class="button"  background="img/chart.png" />
                        <?php }?>
                        </td>
                        <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
						</tr>

						<?php 
						//echo $login_RolesID;
                          $permitrolsid = array("1","13","14","18","20","22","23","10","19");
						 $permitrolsid3 = array("1","2","13","18","20","22","23","10");
                           $permitnazer = array("1","2","13","18","20","22","23","10");						 
                      						 
                    
	//	 $permitrolsid3 = array("1");
    //   $permitnazer=array("1");						 
                    					
						 if (in_array($login_RolesID, $permitrolsid3))
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"5\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                            <th colspan=\"2\" class=\"f13_font\" >پروژه انجام داده</th>
                            <th colspan=\"2\" class=\"f13_font\" >پروژه در دست اجرا</th>
                            <th colspan=\"3\" class=\"f13_font\" >پیشنهاد قیمت</th>
                            <th class=\"f13_font\"  >دلایل</th>
						
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
                            <th class=\"f10_font\" >تعداد</th>
                            <th class=\"f10_font\" >انتخاب</th>
                            <th class=\"f10_font\" >متوسط ضریب</th>
							
                            <th class=\"f10_font\" >عدم.صلاحیت</th>
							
                        </tr>";
                            
                         
                 else 
                        
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"4\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
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
                            <th class=\"f10_font\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
			
			
                        </tr>";    
           ?> 	</thead>
<?php        
                      						
                     
                    $Total=0;
                    $rown=0;
                    $Description="";
                    
					$sum1=0;
                    $sum2=0;
                    $sum3=0;
                    $sum31=0;
                    $sum4=0;
                    $sum5=0;
                    $sum6=0;
                    $sum7=0;
                    $sum8=0;
                    $nmojavez=0;
					$npishnahad=0;
					$strallval="";
					$errorsNum=0;
					 if ($login_isfulloption==1) 
					while($resquery = mysql_fetch_assoc($result))
		              {  
					  if (($resquery["operatorcoID"]==108 || $resquery["operatorcoID"]==115) && ($showa==0 || $login_RolesID<>1)) continue;
				   
					  /* $queryss = " update operatorco set guaranteeExpireDate='
						".jalali_to_gregorian(compelete_date($resquery["copermisionvalidate"]))."'	
						WHERE operatorcoid='$resquery[operatorcoID]';";
 						mysql_query($queryss); 
					  */
					  
					   $sumrown+=$rown;
					   $sum1+=$resquery[projectcountdone];
				       $sum2+=$resquery[projecthektardone];
					   $sum3+=$resquery[simultaneouscnt];
					   $sum31+= $resquery[above20cnt];

					   $sum4+=round($resquery["currentprgarea"],1);
					   $sum5+=round($resquery["reqcnt"],1);
					   $sum6+=round($resquery["wincnt"],1);
					   $sum7+=round($resquery["avgpmreq"],2);
                       $sum8+=round($resquery["avgpmreqa"],2);
 						
					$TBLNAME= "operatorco";
					$TITLE = 'شركت';
					$IDoperator = $resquery['operatorcoID'];
					$ID = $TBLNAME.'_'.$TITLE.'_'.$IDoperator;
					$errors="";
               
               $retarrayval = member_op_error (
				compelete_date($resquery["copermisionvalidate"]),gregorian_to_jalali(date('Y-m-d')),compelete_date($resquery["boardvalidationdate"]),
				$resquery["designercnt"],$resquery["StarCo"],$resquery["duplicatedesigner"],$resquery["simultaneouscnt"]
                ,$Permissionvals['tmphtp'],$resquery["corank"],0,$Permissionvals['smallapplicantsize']
                ,$resquery["above20cnt"],$Permissionvals['tmtb10hp1'],$Permissionvals['tmtb10hp2'],$Permissionvals['tmtb10hp3']
                ,$Permissionvals['tmtb10hp4'],$Permissionvals['tmtb10hp5'],$resquery["above55cnt"],$Permissionvals['tmtb50hp5']
                ,$Permissionvals['hmmp1'],$Permissionvals['hmmp2'],$Permissionvals['hmmp3'],$Permissionvals['hmmp4']
                ,$Permissionvals['hmmp5'],$resquery["currentprgarea"],$Permissionvals['hmmsmp1'],$Permissionvals['hmmsmp2']
                ,$Permissionvals['hmmsmp3'],$Permissionvals['hmmsmp4'],$Permissionvals['hmmsmp5'],$resquery['engineersystemvalidate']
				,$resquery['ent_Num'],compelete_date($resquery['ent_DateTo']),(compelete_date($resquery['valueaddedvalidate']))
				);
               //echo compelete_date($resquery['valueaddedvalidate']);
               
              // print_r($retarrayval);
               
               foreach($retarrayval as $key=>$value)
									$errors.= $value;
 			   
                             $cl='000000';						
                            if ($resquery["ischange"])  $cl='6100ff'; 
                            if (strlen($errors)>0) {$cl='ff0000';$errorsNum++;}
							if (strlen($errors)>0 && $resquery["ischange"]>0) $cl='ff00ff';     
	                    
                        //if (!($showm>0) && strlen($errors)>0) continue;
					   if (($IDzarfiatval==3) && strlen($errors)>0) continue;
						
                          $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
               
					if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d'))) $nmojavez++;
                    print "<tr '>";
         ?>                      
                                
              <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["operatorcoTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo  $resquery["fundationYear"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo  $resquery["boardvalidationdate"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["BossName"].' '.$resquery["bosslname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["dname"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo $resquery["CoAddress"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["corank"] ; ?></td>
                            <?php  
                            
							
                            if (in_array($login_RolesID, $permitrolsid3))
                            {
                            
                            echo "
                            <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            ";
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmp5'] ;
                            echo "</td>";
                                
                           }
                            
                            ?>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $Permissionvals['tmphtp'];  ?></td>
                            <td class="f10_font<?php echo $b; ?>"  colspan="1" style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmsmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmsmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmsmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmsmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmsmp5'] ;
                            
							if (($login_OperatorCoID ==$resquery["operatorcoID"]) || in_array($login_RolesID, $permitrolsid))
                            echo "<br>(".round($resquery["thisyearprgarea"],1).")";
							
							echo "
							</td>
							  <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[copermisionvalidate]</td>
                             ";
                            
							if ($resquery["reqcnt"]>0) $npishnahad++;
                            
	


							 
           if (($login_OperatorCoID !=$resquery["operatorcoID"]))
	
	        {	   					
			   $permit=$permitrolsid;					
			 		if ($login_RolesID==2) echo"
                    <td colspan='8' class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>
                     ";
					 
				
			}else{			
			
               $permit=$permitrolsid3;					
			}		
				//print_r($permit);
							if (in_array($login_RolesID, $permit))
							{
								
                                if (in_array($login_RolesID, $permitnazer))
							     {
									$firn="";if ($resquery['firstperiodcoprojectnumber'] && $login_RolesID==1) $firn="<br>**$resquery[firstperiodcoprojectnumber]";
									$firh="";if ($resquery['firstperiodcoprojectnumber'] && $login_RolesID==1) $firh="<br><br>**$resquery[firstperiodcoprojectarea]";
										//,firstperiodcoprojectarea,firstperiodcoprojectnumber
							  echo "<td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[projectcountdone]</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[projecthektardone]</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".$resquery['simultaneouscnt']."<br>($resquery[above20cnt])"."$firn"."</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".round($resquery["currentprgarea"],1)."$firh"."</td>
							  
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".
							  	 "<a  target='".$target."' href='insert/apprequest.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['operatorcoID']."_5_".$login_ostanId.rand(10000,99999).
                                    "'><font color=\"$cl\">".
								round($resquery["reqcnt"],1)."</a>"."</td>
							  
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".
							  "<a target='".$target."' href='appinvestigation/allapplicantrequest.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['operatorcoID']."_1_".$login_ostanId.rand(10000,99999).
                                    "'><font color=\"$cl\">".
								round($resquery["wincnt"],1)."</td>
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".round($resquery["avgpmreq"],2)."</td>
							  "; }?>
							  
							  
                              <?php 
							//  if ($login_RolesID==1) 
							 // $XX=$resquery["BossNameop"].' '.$resquery["bosslnameop"];
							  echo"
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4).$XX."</td>
                              "; ?>
                              


							  
							<?php 
							  $permitrolsid0 = array("1", "4","18","20");
							  $permitrolsid1 = array("1", "4","18","20","21","23");
							  $permitrolsid2 = array("1", "2","3","9");
							  $permitrolsid4 = array("1", "13","14","18","10","23","27","30","29");
							  
                              if (in_array($login_RolesID, $permitrolsid0)) {?>
							  <td><a  target="_blank" href="<?php print "codding/codding4table_detail_edit.php?uid=".rand(10000,99999).
                              rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                              .rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/Actions-document-export-icon.png' title=' ويرايش '></a>
							  </td>
					  
                           <?php  
								}
						  if (in_array($login_RolesID, $permitrolsid4)) {
						   print "<td><a  target='".$target."' href='appinvestigation/form.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery["ClerkIDinvestigation"].'_'.$ID.rand(10000,99999).
                            "'>
                            <img style = 'width: 20px;' src='img/Editevaluate.jpg' title=' ارزیابی کاربران '></a></td>
                            
                            
                            
							  <td><a target=\"_blank\" href='insert/approvedocumentcompany.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                              rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
                              <img style = 'width: 25px;' src='img/app-delete-icon.png' title=' تائيديه ها  '></a></td>
                            ";
                            
                           }
						   
						   if ($resquery['StarCo']==1)  $Editinf="img/Editinf.jpg"; else $Editinf="img/Editinf2.jpg";
                           
							   if (in_array($login_RolesID, $permitrolsid1)) { ?>
							  
							  
					  	  <td><a target="_blank" href="<?php print "instruction/law_detaillist.php?cid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/law.png' title=' ابلاغیه  '></a></td>
							  
						<td><a target="_blank" href="<?php print "insert/entezami.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='<?php print $Editinf;?>' title='انتظامی'></a></td>
							  
                           <?php  } else if (in_array($login_RolesID, $permitrolsid2)){?>
						      <td><a target="_blank" href="<?php print "insert/approvedocumentcompany1.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/file-edit-icon.png' title='مشخصات' ></a></td>

						   
                            
						   
						   
						   
						   <?php  }
						   }
						   
                            echo "</tr>";
                      
                        }
                        $permitrolsids = array("1", "13","14","18","22","23");
						  if (in_array($login_RolesID, $permitrolsids))
                                if (in_array($login_RolesID, $permitnazer))
							     {
					   
                       {
?>			
		
			
                        <tr>
                            
                            <td rowspan="2" colspan="12" class="f14_font" ><?php echo ' مجموع';   ?></td>
                            <td colspan="2" class="f14_fontright" ><?php echo $sum1;   ?></td>
							<td colspan="2" class="f14_fontright" ><?php echo $sum3.'('.$sum31.')';   ?></td>
							<td colspan="2" class="f14_fontright" ><?php echo $sum5;   ?></td>
							<td colspan="2" class="f14_fontright" ></td>
							
							
                        </tr>
						  <tr>
                            
                            <td colspan="2" class="f14_font" ><?php echo $sum2;   ?></td>
							<td colspan="2" class="f14_font" ><?php echo $sum4;   ?></td>
							<td colspan="2" class="f14_font" ><?php echo $sum6;   ?></td>
							<td colspan="2" class="f14_font" ></td>
							
							
                        </tr>
						<?php $nmojavez1=($rown-$nmojavez);
						      
						//print $nmojavez1;
						?>
                        <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت های مجوزدار';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo ($rown-$nmojavez);   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت های فاقد صلاحیت';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo $errorsNum;   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت های پیشنهاد دهنده';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo $npishnahad;   ?></td>
                        </tr> 
                        
                         <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'میانگین ضرایب پیشنهادی';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo round(($sum7/$npishnahad),2);   ?></td>
                        </tr> 
                        <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'میانگین ضرایب برنده پیشنهاد';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo round(($sum8/$npishnahad),2);   ?></td>
                        </tr> 
                        
	                               
                    <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            حداکثر تعداد پروژه های همزمان با احتساب پروژه های کوچک می باشد .
                            </span>  </td>
                   </tr>
                   <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            سطح هر پرو‍ژه طبق مصوبه كميته فني آب و خاك و با  10% افزايش در نظر گرفته شد.
                            </span>  </td>
                   </tr>
                    <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            کل پروژه های جاری (پروژه های با مساحت بزرگ)
                            </span>  </td>
                   </tr>
   
                   <tr>
				      <td colspan='18'> </td>
					
			     </tr>
			<input type="hidden" name="sum1" value = <?php echo $sum1;?> />
			<input type="hidden" name="sum2" value = <?php echo $sum2;?> />
			<input type="hidden" name="sum3" value = <?php echo $sum3;?> />
			<input type="hidden" name="sum4" value = <?php echo $sum4;?> />
			<input type="hidden" name="sum5" value = <?php echo $sum5;?> />
			<input type="hidden" name="sum6" value = <?php echo $sum6;?> />
			<input type="hidden" name="npishnahad" value = <?php echo $npishnahad;?> />
			<input type="hidden" name="nmojavez1" value = <?php echo $nmojavez1;?> />
			<input type="hidden" name="rown" value = <?php echo $rown;?> />
			<?php 
            }}
            ?>
			</form>
    
                </table>
     
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
               
            </div>
			<!-- /content -->

<?php 
//=================================================================================================================================================
   }
if ($showm==1){

 ?>


			<!-- content -->
			<div id="content">
            
          
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
          
                <table align='center' class="page" border='1'>              
				
          	 <tr> 
			  <form action="members_operatorcos.php" method="post">
			 <td colspan="1" ></td>
			  
              <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');?> 
			 <td colspan="1" > <input   name="submit" type="submit" id="submit" value="جستجو" /></td>
			 </form>
           </tr>
                 <table align='center' border='1' id="table2">              
                   <thead>
                   <?php if($_SESSION['userid']!=419){?>
                    <form action="chart_member.php" method="post">
                    <?php }?>
                        <tr> 
                        <td colspan="20"
                          <span class="f14_font" >لیست وضعیت شرکت های مجری</span> 
                           <?php if($_SESSION['userid']!=419){?>
					      <input type="submit" name="submit" value="نمودار"  class="button"  background="img/chart.png" />
                        <?php }?>
                        </td>
                        <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
						</tr>

						<?php 
						
                         $permitrolsid = array("1", "13","14","18","20","22","23","10");
						 $permitrolsid3 = array("1","2","13","18","20","22","23","10");
                         $permitnazer=array("1","2","13","18","20","22","23");						 
                         
						 if (in_array($login_RolesID, $permitrolsid3))
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"4\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                            <th colspan=\"5\" class=\"f13_font\" >گواهینامه سازمان مدیریت و برنامه ریزی</th>
                            <th colspan=\"3\" class=\"f13_font\" >نظام مهندسی کشاورزی</th>
                            <th class=\"f13_font\"  >دلایل</th>
						
                        </tr>
		     			<tr>
                            <th class=\"f9_font\" ></th>
                            <th class=\"f10_font\" >نام</th>
                            <th class=\"f10_font\" >تاریخ تاسیس</th>
                            <th class=\"f10_font\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_font\" >مدیرعامل</th>
                            <th class=\"f10_font\" >کارشناس فنی</th>
							<th class=\"f10_font\" >آدرس</th>
							
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            
                            <th class=\"f10_font\" >عدم.صلاحیت</th>
							
                        </tr>";
                            
                         
                 else 
                        
                         echo "<tr>
                            <th colspan=\"7\" class=\"f13_font\" >شرکت</th>
                            <th colspan=\"4\" class=\"f13_font\" >مجوز دفتر توسعه سامانه های نوین آبیاری </th>
                            <th colspan=\"5\" class=\"f13_font\" >گواهینامه سازمان مدیریت و برنامه ریزی</th>
                            <th colspan=\"3\" class=\"f13_font\" >نظام مهندسی کشاورزی</th>
                        
                        </tr>
		     			<tr>
                            <th class=\"f9_font\" ></th>
                            <th class=\"f10_font\" >نام</th>
                            <th class=\"f10_font\" >تاریخ تاسیس</th>
                            <th class=\"f10_font\" >تاریخ آخرین تغییرات</th>
                            <th class=\"f10_font\" >مدیرعامل</th>
                            <th class=\"f10_font\" >کارشناس فنی</th>
							<th class=\"f10_font\" >آدرس</th>
							
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >رشته</th>
                            <th class=\"f10_font\" >تاریخ اعتبار</th>
                        	
                        </tr>";
                            
           ?> 	</thead>
<?php        
                     						
                     
                    $Total=0;
                    $rown=0;
                    $Description="";
                    
					$sum1=0;
                    $sum2=0;
                    $sum3=0;
                    $sum4=0;
                    $sum5=0;
                    $sum6=0;
                    $sum7=0;
                    $sum8=0;
                    $nmojavez=0;
					$npishnahad=0;
					
					$nmojavezab=0;
					$mojavezab=0;
					$nmojavezabkes=0;
					
					while($resquery = mysql_fetch_assoc($result))
					
                    {  
				//   	         if ($resquery["operatorcoTitle"]=='متفرقه') continue;
                 		  if (($resquery["operatorcoID"]==108 || $resquery["operatorcoID"]==115) && ($showa==0 || $login_RolesID<>1)) continue;
			          
                    
					  /* $queryss = " update operatorco set guaranteeExpireDate='
".jalali_to_gregorian(compelete_date($resquery["copermisionvalidate"]))."'					   WHERE operatorcoid='$resquery[operatorcoID]';";
        
						mysql_query($queryss); 
					   */
					   $sumrown+=$rown;
					   $sum1+=$resquery[projectcountdone];
				       $sum2+=$resquery[projecthektardone];
					   $sum3+=$resquery[simultaneouscnt];
					   $sum31+= $resquery[above20cnt];
					   $sum4+=round($resquery["currentprgarea"],1);
					   $sum5+=round($resquery["reqcnt"],1);
					   $sum6+=round($resquery["wincnt"],1);
					   $sum7+=round($resquery["avgpmreq"],2);
                       $sum8+=round($resquery["avgpmreqa"],2);
                    
 

              	
       					
					$TBLNAME= "operatorco";
					$TITLE = 'شركت';
					$IDoperator = $resquery['operatorcoID'];
					$ID = $TBLNAME.'_'.$TITLE.'_'.$IDoperator;
                        $errors="";
                    	
			$retarrayval=member_op_error (
				compelete_date($resquery["copermisionvalidate"]),gregorian_to_jalali(date('Y-m-d')),compelete_date($resquery["boardvalidationdate"]),
				$resquery["designercnt"],$resquery["StarCo"],$resquery["duplicatedesigner"],$resquery["simultaneouscnt"],
                $Permissionvals['tmphtp'],$resquery["corank"],$resquery["DesignArea"],$Permissionvals['smallapplicantsize'],
                $resquery["above20cnt"],$Permissionvals['tmtb10hp1'],$Permissionvals['tmtb10hp2'],$Permissionvals['tmtb10hp3'],
                $Permissionvals['tmtb10hp4'],$Permissionvals['tmtb10hp5'],$resquery["above55cnt"],$Permissionvals['tmtb50hp5'],
                $Permissionvals['hmmp1'],$Permissionvals['hmmp2'],$Permissionvals['hmmp3'],$Permissionvals['hmmp4'],$Permissionvals['hmmp5']
                ,$resquery["currentprgarea"],$Permissionvals['hmmsmp1'],$Permissionvals['hmmsmp2'],$Permissionvals['hmmsmp3']
                ,$Permissionvals['hmmsmp4'],$Permissionvals['hmmsmp5'],$resquery['engineersystemvalidate'],$resquery['ent_Num']
				,compelete_date($resquery['ent_DateTo']),compelete_date($resquery['valueaddedvalidate'])
				);
                
                //print_r($retarrayval);
               
               
    foreach($retarrayval as $key=>$value)
	$errors.= $value;
			
             if ($retarrayval[0]!='')
             {  
                $nmojavezab++;
            }
            else if ($resquery["contractorRank1"]>0) 
                $mojavezab++;
			
			
            if ($retarrayval[22]!='')
            {
                $nmojavezabkes++;
            }
						
		    if ((compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d'))) )
            {
                $nmojavez++;
            }
                           
						
                            $cl='000000';						
                            if ($resquery["ischange"])  $cl='6100ff'; 
                            if (strlen($errors)>0) $cl='ff0000';
							if (strlen($errors)>0 && $resquery["ischange"]>0) $cl='ff00ff';     
					      
                       
                        //if (!($showm>0) && strlen($errors)>0) continue;
                          $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                            
                             print "<tr '>";
                              
         ?>                      
                                
              <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["operatorcoTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo  $resquery["fundationYear"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo  $resquery["boardvalidationdate"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["BossName"].' '.$resquery["bosslname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["dname"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:9.0pt;font-family:'B Nazanin';"><?php echo $resquery["CoAddress"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["corank"] ; ?></td>
                            <?php  
                            
							
                            if (in_array($login_RolesID, $permitrolsid3))
                            {
                            
                            echo "
                            <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            ";
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmp5'] ;
                            echo "</td>";
                                
                            }
                            
                            ?>
                            
						   <td class="f10_font<?php echo $b; ?>"  colspan="1" style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmsmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmsmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmsmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmsmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmsmp5'] ;
                            
                  echo "<br>($resquery[currentprgarea])</td><td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[copermisionvalidate]</td>";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[contractorRank1] </td> ";
                  echo "<td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[contractorField1] </td> ";
                  echo "<td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[contractorRank2] </td> ";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[contractorField2] </td> ";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[contractorvalidate]</td> ";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[engineersystemRank] </td> ";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[engineersystemField] </td> ";
                  echo " <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[engineersystemvalidate]</td> ";
                           

      if (($login_OperatorCoID !=$resquery["operatorcoID"]))
	
	        {			
			   $permit=$permitrolsid;					
			}else{			
               $permit=$permitrolsid3;					
			}				
	
			
			if (in_array($login_RolesID, $permit))
					{
                                echo"
                              <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>
                              "; 
							  ?>
                              <?php 
							  
							  $permitrolsid0 = array("1", "4","18","20","21","23");
							  $permitrolsid1 = array("1", "4","18","20","21","22","23","10");
							  $permitrolsid2 = array("1", "2","3","9","10");
							  
                              if (in_array($login_RolesID, $permitrolsid0)) { ?>
							  <td><a  target="_blank" href="<?php print "codding/codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/Actions-document-export-icon.png' title=' ويرايش '></a>
							  
					  
                           <?php 
                            
                           
                            }
                           
							   if (in_array($login_RolesID, $permitrolsid1)) { ?>
							  
							  <td><a target="_blank" href="<?php print "insert/approvedocumentcompany.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/app-delete-icon.png' title=' تائيديه ها  '></a></td>
					  
                           <?php  } else if (in_array($login_RolesID, $permitrolsid2)){?>
						      <td><a target="_blank" href="<?php print "insert/approvedocumentcompany1.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                              <img style = 'width: 25px;' src='img/file-edit-icon.png' title='مشخصات'></a></td>

						   
						   
						   <?php  }
						   
						 
						   }
						   

                            
                            echo "</tr>";
                        }
 
	
 $permitrolsids = array("1", "13","14","18","22","23");
                      if (in_array($login_RolesID, $permitrolsids))
					   if (in_array($login_RolesID, $permitnazer))
							     {
					   
                        {
			
					          $nmojavez1=($rown-$nmojavez);
						      $nmojavezab1=($rown-$nmojavezab);
						      $mojavezab1=($rown-$mojavezab);
						
						//print $nmojavez1;
						?>
                        <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت دارای مجوز دفتر توسعه سامانه های نوین آبیاری ';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo ($rown-$nmojavez);   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت دارای گواهی صلاحیت رشته آب از سازمان مدیریت و برنامه ریزی';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo ($mojavezab);   ?></td>
                        </tr> 
                        
                         <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت دارای گواهی صلاحیت از سازمان مدیریت و برنامه ریزی';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo ($rown-$nmojavezab);   ?></td>
                        </tr> 
                        <tr>
                            
                            <td colspan="12" class="f14_font" ><?php echo 'تعداد شرکت دارای مجوز از نظام مهندسی کشاورزی';   ?></td>
                            <td colspan="8" 
                            class="f14_font" 
                            ><?php echo ($rown-$nmojavezabkes);   ?></td>
                        </tr> 
                        
	                               
                 
                   <tr>
				      <td colspan='18'> </td>
					
			     </tr>
			<input type="hidden" name="sum1" value = <?php echo $sum1;?> />
			<input type="hidden" name="sum2" value = <?php echo $sum2;?> />
			<input type="hidden" name="sum3" value = <?php echo $sum3;?> />
			<input type="hidden" name="sum4" value = <?php echo $sum4;?> />
			<input type="hidden" name="sum5" value = <?php echo $sum5;?> />
			<input type="hidden" name="sum6" value = <?php echo $sum6;?> />
			<input type="hidden" name="npishnahad" value = <?php echo $npishnahad;?> />
			<input type="hidden" name="nmojavez1" value = <?php echo $nmojavez1;?> />
			<input type="hidden" name="rown" value = <?php echo $rown;?> />
	<?php 
      } }
     ?>
			</form>
    
                </table>
     
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
               
            </div>
			<!-- /content -->

<?php 
   }
 ?>

            <!-- footer -->
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>



