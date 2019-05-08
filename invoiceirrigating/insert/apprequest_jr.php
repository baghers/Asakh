<?php 

/*

insert/apprequest_jr.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/apprequest.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');

 //ÝÑã åÇíí ˜å Çíä ÕÝÍå ÏÇÎá ÂäåÇ ÝÑÇÎæÇäí ãí ÔæÏ 

 
    $Permissionvals=supervisorcoderrquirement_sql($login_ostanId); //دریافت تنظیمات پیکربندی
	$zarib5=(100+$Permissionvals['hmmp5zarib'])/100;
	
	$selectedBankcodes = array("30-142571-1","30-0001385000000-30");
	
	$selectedBankcode=trim($_POST['selectedBankcode']);
	if (in_array($selectedBankcode, $selectedBankcodes)) 
    $maxAreacorank5 = $Permissionvals['hmmp5']* $zarib5;
    else
    $maxAreacorank5 = $Permissionvals['hmmp5'] ;
	
	$maxAreasmalls=10.9;
	$maxAreasmall=14.9;
	
	
    if (!($login_OperatorCoID>0 ))
        $temp_array = array('error' => '1'
            ,'errors' => '');
    else
    {
        /*
            applicantmaster جدول مشخصات طرح
            ApplicantMasterID شناسه طرح مطالعاتی
            operatorcoID شناسه پیمانکار
            operatorapprequest جدول پیشنهادات قیمت
            $login_OperatorCoID شناسه پیمانکار لاگین شده
        */
        $query = "select count(*) cnt from operatorapprequest 
        inner join applicantmaster on applicantmaster.applicantmasterid=operatorapprequest.applicantmasterid
        where operatorapprequest.operatorcoID='$login_OperatorCoID' and TRIM(Bankcode)='$selectedBankcode'";
        		  	  			try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
  
        $row = mysql_fetch_assoc($result); 		
        if ($row['cnt']>0)
        {
             $temp_array = array('error' => '2'
            ,'errors' => '');
        }
        else
        {
           /*
           
            applicantmaster جدول مشخصات طرح
            operatorcoID شناسه پیمانکار
            Bankcode کد رهگیری
            proposestate وضعیت پیشنهاد
           */ 
            
            $query = "select count(*) cnt from applicantmaster
            where ifnull(operatorcoID,0)=0 and TRIM(Bankcode)='$selectedBankcode' and ifnull(proposestate,0)>0";
            		  			try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
  
            $row = mysql_fetch_assoc($result); 		
            if ($row['cnt']>0)
            {
                 $temp_array = array('error' => '3'
                ,'errors' => '');
            }
            else 
            {
           /*
           
            applicantmaster جدول مشخصات طرح
            operatorcoID شناسه پیمانکار
            Bankcode کد رهگیری
            proposestate وضعیت پیشنهاد
            ApplicantMasterID شناسه طرح مطالعاتی
           */       
                $query = "select count(*) cnt from applicantmaster
                where ifnull(operatorcoID,0)=0 and TRIM(Bankcode)='$selectedBankcode' 
                

                and applicantmaster.applicantstatesID not in (37,22,24)
                and applicantmaster.applicantmasterid not in (select applicantmasterid from operatorapprequest)";
            		  			try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
                $row = mysql_fetch_assoc($result); 
                if ($row['cnt']>0)
                {
                     $temp_array = array('error' => '4'
                    ,'errors' => '');
                }            
                else
                {        
                  
                   $currentdatefrom=jalali_to_gregorian((substr(gregorian_to_jalali(date('Y-m-d')),0,4)-1)."/07/01");  
                    $currentdateto=jalali_to_gregorian(substr(gregorian_to_jalali(date('Y-m-d')),0,4)."/06/31");
                  
                    /*
                    applicantmaster جدول مشخصات طرح
                    creditsourcetitle عنوان منبع تامین اعتبار
                    designername عنوان طراح
                    designsystemgroupstitle سیستم آبیاری
                    shahrcityname نام شهر
                    operatorcoTitle عنوان پیمانکار
                    operatorco.StarCo تعداد ستاره های شرکت
                    operatorco.ent_Num تعداد انتظامی بودن شرکت
                    operatorco.ent_DateTo پایان انتظامی بودن شرکت
                    designer.LName نام خانوادگی طراح
                    designer.FName نام طراح
                    operatorapprequest جدول پیشنهاد قیمت های طرح
                    operatorco جدول پیمانکار
                    operatorco.Title عنوان پیمانکار
                    operatorcoID شناسه پیمانکار
                    state برنده شدن یا نشدن
                    clerk جدول کاربران
                    applicantmaster جدول مشخصات طرح
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
                    private شخصی بودن طرح
                    numfield شماره پرونده طرح
                    criditType تجمیع بودن یا نبودن طرح
                    ClerkIDsurveyor شناسه کاربر نقشه بردار
                    year جدول سال
                    YearID سال طرح
                    mobile تلفن همراه
                    melicode کد/شناسه ملی
                    SurveyArea مساحت نقشه برداری شده
                    surveyDate تاریخ نقشه برداری
                    coef5 ضریب منطقه ای طرح
                    designer جدول طراحان
                    DesignerCoIDnazer شناسه مشاور ناظر طرح
                    operatorcoid شناسه پیمانکار
                    DesignerCoID شناسه مشاور طراح
                    costpricelistmaster جدول فهرست بها
                    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
                    DesignerID شناسه طراح طرح
                    applicantstatesID شناسه وضعیت طرح
                    operatorco.boardchangeno شماره نامه آخرین تغییرات
                    operatorco.boardchangedate تاریخ آخرین تغییرات هیئت مدیره
                    operatorco.boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
                    operatorco.boardIssuer مرجع صادرکننده مدرک هیئت مدیره
                    operatorco.copermisionno تعداد پروژه های قابل انجام
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
                    coef1 ضریب اول اجرای طرح
                    coef2 ضریب دوم اجرای طرح
                    coef3 ضریب سوم اجرای طرح
                    ent_DateFrom شروع انتظامی بودن شرکت
                    ent_DateTo پایان انتظامی بودن شرکت
                    ent_Hectar هکتار انتظامی بودن شرکت
                    ent_Num تعداد انتظامی بودن شرکت
                    operatorco.Disabled فعال و غیر فعال بودن شرکت
                    operatorco.Code سریال شرکت
                    percentapplicantsize درصد افزایش اندازه پروژه
                    applicantmasterdetail جدول ارتباطی طرح ها
                    */  
                    $sql = "SELECT distinct applicantmaster.*,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
                ,designsystemgroups.title designsystemgroupstitle,operatorco.Title operatorcoTitle
                ,operatorco.StarCo,operatorco.ent_DateFrom,operatorco.ent_DateTo,operatorco.ent_Hectar,operatorco.ent_Num
                ,operatorco.Disabled
                ,corank,firstperiodcoprojectarea,case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end firstperiodcoprojectnumber,coprojectsum,boardvalidationdate,
                yearcost.Value fb,copermisionvalidate,joinyear,MaxDone
                
                ,ifnull(operatorco.projecthektar92 ,0)+
                ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID in (34,35,38) 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) projecthektardone
                
                ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0)+
                ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=0  and applicantmasterall.DesignArea>$maxAreasmall
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) simultaneouscnt 
                
                ,ifnull(firstperiodcoprojectarea ,0)+
                ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and operatorapprequestin.Windate>='$currentdatefrom' and operatorapprequestin.Windate<='$currentdateto' 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) thisyearprgarea
                  
                ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=1  and applicantmasterall.DesignArea>$maxAreasmalls
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) above20cnt
                ,
                case ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0) when 0 then
                (select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID')
                else case SUBSTR(ifnull(firstperiodcoprojectarea,0)/ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)-50,0,1) when '-' then
                (select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID')
                else 
                ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) end end above55cnt
                
                
                ,(select max(applicantmasterall.DesignArea) maxarea from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=1 
                and operatorapprequestin.operatorcoID='$login_OperatorCoID')  above20max
    
                   
                ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid) designercnt
                ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid
                and NationalCode in (SELECT NationalCode FROM `designer` GROUP BY NationalCode HAVING count( * ) >1)) duplicatedesigner,yearcost.Value fb
                ,applicantmasterdetail.proposelimitless
                FROM applicantmaster 
            
                inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
                
                inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
                and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
                inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
                and substring(shahr.id,3,5)<>'00000'
                left outer join designer on designer.designerid=applicantmaster.designerid
                left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'ÞØÑå Çí/ ÈÇÑÇäí' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
                inner join operatorco on operatorco.operatorcoID='$login_OperatorCoID' 
                
                left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
                left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID
            
                    
                    where applicantmaster.DesignerCoID>0 and applicantmaster.proposestatep<>-1 and TRIM(applicantmaster.Bankcode)='$selectedBankcode'
                    and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and  ifnull(applicantmaster.isbandp,0)=0";
                    
					      		try 
								  {		
									      $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
 
                    $resquery = mysql_fetch_assoc($result);
				             
                    if ($resquery["DesignArea"]>0)
                    {                
                        $ApplicantName = $resquery["ApplicantName"];
                    	$DesignArea = $resquery["DesignArea"];
                        $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                        $shahrcityname = $resquery["shahrcityname"];
                        $designername = $resquery["designername"];
                        $ApplicantMasterID = $resquery["ApplicantMasterID"];
                        //$linearray = explode('_',rettotalsumtarh($ApplicantMasterID));
                        $Applicanttotal=floor($linearray[0]/100000)/10;
                        $applicantferestbahabase=floor($linearray[1]/100000)/10;
					    //if ($DesignArea>11) $applicantferestbahabase=floor($linearray[1]/100000)/20;
                        $applicantferestbaha=floor($linearray[5]/100000)/10;
                        $appcoef1=round($linearray[2],2);
                        $appcoef2=round($linearray[3],2);
                        $appcoef3=round($linearray[4],2);    
                        $fb=$resquery["fb"];             
						$applicantferestbahabase=round($resquery["LastFehrestbaha"]/1000000,1);             
                    
                                $errors="";
                                
                                $joinyearlow=0;
                                $date = new DateTime(jalali_to_gregorian($resquery["joinyear"]));
                                $date->modify('+720 day');
                                
                                //$date->add(new DateInterval('P2Y'));
                                if ($date->format('Y-m-d')>date('Y-m-d'))
                                    $joinyearlow=1;
                                
                                 $date = new DateTime(date('Y-m-d'));
                                 $date->modify('+30 day');
                                
                                //$date->add(new DateInterval('P1M'));
                                
                                if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali($date->format('Y-m-d')) && ($Permissionvals['propose30daypermissionless']==0) )
                                $errors="-1";
                                
                                $smallapplicantsize=$Permissionvals['smallapplicantsize'];
                                $linearray = explode('_',$resquery['CountyName']);
                                $apps=$linearray[5];
                                if ($apps==1)
                                {
                                    $smallapplicantsize=$resquery["DesignArea"];
                                }
                            
                                else if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')) && ($Permissionvals['proposepermissionless']==0) )
                                    $errors="1";
                                else if (!($resquery["designercnt"]>=1)  && ($Permissionvals['proposedesignerless']==0) )
                                    $errors="2";
                                else if ($resquery["duplicatedesigner"]>=1)
                                    $errors="3";
                                else if (($resquery["simultaneouscnt"]>=$Permissionvals['tmphtp']))
                                    $errors="4";
                                else if (!($resquery["proposelimitless"]>0))
                                {
                                    if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp1']))  
                                        $errors="5";  
                                    else if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp2']))  
                                        $errors="6";  
                                    else if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp3']))  
                                        $errors="7";  
                                    else if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp4']))  
                                        $errors="8";  
                                    else if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp5']))  
                                        $errors="9";  
                                    else if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$maxAreacorank5) && ($resquery["above50cnt"]>=$Permissionvals['tmtb50hp5']))  
                                        $errors="10";  
                                        
                                    else if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$Permissionvals['hmmp1']))  
                                        $errors="11";  
                                    else if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$Permissionvals['hmmp2']))  
                                        $errors="12";  
                                    else if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$Permissionvals['hmmp3']))  
                                        $errors="13";  
                                    else if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$Permissionvals['hmmp4']))  
                                        $errors="14";  
                                    else if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['hmmp5']*$zarib5))  
                                        $errors="15";  
                                        
                                }
                                
                                    
                                else if (($resquery["corank"]==1) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp1']))  
                                    $errors="16";  
                                else if (($resquery["corank"]==2) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp2']))  
                                    $errors="17";  
                                else if (($resquery["corank"]==3) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp3']))  
                                    $errors="18";  
                                else if (($resquery["corank"]==4) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp4']))  
                                    $errors="19";  
                                else if (($resquery["corank"]==5) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp5']))  
                                    $errors="20";  
                                
                                /*else if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['experimentthreshold']) && ($joinyearlow==1))  
                                    $errors="21";  
                               */
                               
                            if ($resquery["StarCo"]==1)
								$errors="22"; 
                            else if ($resquery["ent_Num"]>0 && compelete_date($resquery["ent_DateTo"])>=gregorian_to_jalali(date('Y-m-d')) )
                                {
                                    if (($resquery["DesignArea"]>=$resquery["ent_Hectar"])||
                                     ($resquery["simultaneouscnt"]>=$resquery["ent_Num"])  )
                                    {
                                        $errors="22";
                                    }
                                }
                                
                                if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d'))   && ($Permissionvals['proposecoless']==0)  )
                                $errors="23";
                        /*
                                if ($resquery["projecthektardone"]>100)
                                    $Max=110;
                                else if ($resquery["projecthektardone"]>50 && ($resquery["MaxDone"]>$Permissionvals['experimentthreshold']))    
                                    $Max=$resquery["MaxDone"];
                                else $Max=$Permissionvals['experimentthreshold'];    
                                
                                if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Max))  
                                        $errors="24";
                                        
                                if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20max"]>$smallapplicantsize) && (($resquery["above20max"]+$resquery["DesignArea"])>$Permissionvals['hmmp5'])) 
                                    $errors="25";  
                                    */
                             if ($ApplicantMasterID==$resquery["StarCo"]) 
							 $errors='';
                                 
                        $temp_array = array(
                        'error' => '0'
                        ,'ApplicantName' => $ApplicantName
                    	,'DesignArea' => $DesignArea
                        ,'designsystemgroupstitle' => $designsystemgroupstitle
                        ,'shahrcityname' => $shahrcityname  
                        ,'designername' => $designername
                        ,'applicantferestbaha' => $applicantferestbaha
                        ,'Applicanttotal' => $Applicanttotal
                        ,'ApplicantMasterID' => $ApplicantMasterID
                        ,'applicantferestbahabase' => $applicantferestbahabase
                        ,'appcoef1' => $appcoef1
                        ,'appcoef2' => $appcoef2
                        ,'appcoef3' => $appcoef3
                        ,'fb' => $fb
                        ,'errors' => $errors);   
                    }
                    else
                    {
                         $temp_array = array('error' => '5'
                        ,'errors' => '');
                        
                    }    
                }
            }                
        }
    }    
	 
    
    

        echo json_encode($temp_array);
		exit();
    			
	
   
   
   
			
			
		
	

?>



