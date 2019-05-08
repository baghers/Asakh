<?php
/*

//appinvestigation/allapplicantrequestdetailchart.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantrequest.php

*/

include('../includes/connect.php');
include('../includes/check_user.php'); 
include('../includes/functions.php');

  
  if ($login_Permission_granted==0) header("Location: ../login.php");

//$login_ostanId شناسه استان
	$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);//تابع دریافت اطلاعات پیکربندی سیستم    				

    
if ($_POST)//در صورت کلیک دکمه سابمیت
{   
    $Description=$_POST['Description'];//توضیحات  
        if ($_POST['Datebandp']>0)//پروژه به صورت ترک تشریفات بود
        $ADate=$_POST['Datebandp'];
        else
        $ADate=date('Y-m-d');//تاریخ شروع پیشنهاد قیمت
        
    if ($_POST['tempsubmitexcept'])//در صورتی که دکمه مجوز مدیر آب و خاک کلیک شده بود
    {
         /*
            operatorapprequest جدول پیشنهاد قیمت های طرح
            applicantmaster جدول مشخصات طرح
            BankCode کد رهگیری طرح
            ApplicantMasterID شناسه طرح
            ecept اعطاء مجوز
            errors توضیحات
        */
        $query = " update operatorapprequest set ecept=1 WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' and state=1;";
        try 
            {		
                mysql_query($query);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }  
                    
        
        

        
        header("Location: allapplicantrequestwitherrors.php");
        //print $query;
        //exit;
    }
    else if ($_POST['tempsubmit1'])//دکمه ارجاع به مدیر آبیاری/برگشت به وضعیت پیشنهاد قیمت
    {
        if($_POST['proposestate']>0)//برگشت به وضعیت پیشنهاد قیمت
        {
            $date = new DateTime($ADate);
            $date->modify('-'.$Permissionvals['proposedaycnt'].' day');//تاریخ شروع پیشنهاد قیمت
            /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                proposestate وضعیت پیشنهاد قیمت
                ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
                ApplicantMasterID شناسه طرح
            */
            $query = " update applicantmaster set 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
            proposestate=0,ADate='".$date->format('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
            try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }   
        }
        else// ارجاع به مدیر آبیاری
        {
            /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                proposestate وضعیت پیشنهاد قیمت
                ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
                ApplicantMasterID شناسه طرح
            */
            $query = " update applicantmaster set 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
            proposestate=1,ADate='".$ADate."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
            try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }           
        }
        header("Location: allapplicantrequest.php");
    }
    else     if ($_POST['tempsubmit2'])//دکمه ارجاع به ناظر عالی
    {
        /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            proposestate وضعیت پیشنهاد قیمت
            ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
            ApplicantMasterID شناسه طرح
        */       
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestate=2,ADate='".$ADate."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
        try 
            {		
                mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }   
        
        header("Location: allapplicantrequest.php");    
        //print $query;
        //exit;
    }
    else if (($_POST['rgs']>0) && ($login_userid>0) )//ثبت منتخب پیشنهاد
    {
        $i=0;
        $Description=$_POST['Description'];//توضیحات   
        if ($_POST['Datebandp']>0)//پروژه به صورت ترک تشریفات بود
        $Windate=$_POST['Datebandp'];
        else
        $Windate=date('Y-m-d');//تاریخ انتخاب مجری
        while (isset($_POST['operatorapprequestID'.++$i]))//پیمایش کلیه پیشنهادات انجام شده
        {
            $operatorapprequestID=$_POST['operatorapprequestID'.$i];//شناسه ردیف پیشنهاد قیمت
            $errors=$_POST['errors'.$i];//پیغام های عدم صلاحیت
            /*
                operatorapprequest جدول پیشنهاد قیمت های طرح    
                ordering ترتیب مبلغ پیشنهادی
                state برنده شدن یا نشدن
                errors پیغام های عدم صلاحیت
                operatorapprequestID شناسه ردیف پیشنهاد قیمت
            */
            $query = " update operatorapprequest set state=0,errors='$errors',Windate='$Windate' WHERE operatorapprequestID ='$operatorapprequestID' ;";
            try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }
            //print $query;
        }    
        //$_POST["DesignArea"] مساحت پروژه
        //$_POST['smallapplicantsize'] حداکثر مساحت پروژه کوچک
        //$_POST['apps'] کوچک بودن پروژه
           if ($_POST["DesignArea"]>$_POST['smallapplicantsize'])
                $appsize=1;//کوچک بودن پروژه
           else $appsize=0;
           
        
        if ($_POST['approvedcoef']>0)//ضریب سوم انتخابی    
        $newset="coef3='$_POST[approvedcoef]',";
        else
        $newset="";
        /*
                operatorapprequest جدول پیشنهاد قیمت های طرح    
                ordering ترتیب مبلغ پیشنهادی
                state برنده شدن یا نشدن
                errors پیغام های عدم صلاحیت
                operatorapprequestID شناسه ردیف پیشنهاد قیمت
                coef1 ضریب 1
                coef2 ضریب 2
                coef3 ضریب 3
                appsize کوچک بودن پروژه
                apval مبلغ تایید شده
                ClerkID کاربر
                Description شرح
        */
        $query = " update operatorapprequest set  $newset coef1=1.3,coef2=1.05,appsize='$appsize', state=1,apval='$_POST[seltotal]',ClerkID='$login_userid',Description='$Description' WHERE operatorapprequestID ='$_POST[rgs]' ;";
        try 
            {		
                mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }
        /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            proposestate وضعیت پیشنهاد قیمت
            ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
            ApplicantMasterID شناسه طرح
        */ 
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestate=3 WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
        try 
            {		
                mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }   
        
        header("Location: allapplicantrequest.php");
        //errors$rown        
    }

}
else
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $uid=$_GET["uid"];
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $pagetype=$linearray[1];//نوع پروژه
    
    $showm=is_numeric($_GET["showm"]) ? intval($_GET["showm"]) : 0;//نمایش تمام پیشنهادات صلاحیت دار و ندار
    /*
        operatorapprequest جدول پیشنهاد قیمت های طرح    
        operatorco جدول پیمانکار
        operatorco.Title عنوان پیمانکار
        operatorcoID شناسه پیمانکار
        state برنده شدن یا نشدن
        ApplicantMasterID شناسه طرح
    */
    //بررسی اینکه مجری انتخاب شده یا خیر
    $querys = "SELECT count(*) cnt,operatorco.Title  from operatorapprequest 
    inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
    where state=1 and operatorapprequest.ApplicantMasterID ='$ApplicantMasterID' 
    group by operatorco.Title";
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
    if ($rows['cnt']>0)
        $done=1;
    else $done=0;   
    $operatorcoTitle=$rows['Title'];//عنوان پیمانکار
    /*
    applicantmaster جدول مشخصات طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    DesignArea مساحت طرح
    CPI نام کاربر
    DVFS نام خانوادگی کاربر
    ClerkID کاربر ثبت
    Datebandp تاریخ ترک تشریفات
    CountyName روستای طرح
    operatorapprequest جدول پیشنهاد قیمت های طرح    
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    state برنده شدن یا نشدن
    clerk جدول کاربران
    */
    $querys = "SELECT ApplicantName,ApplicantFName,DesignArea,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID,Datebandp from applicantmaster 
    left outer join (select ApplicantMasterID,operatorcoID,ClerkID from operatorapprequest where state=1) reqwin on 
    reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID
    left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID

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
    $ApplicantName="$rows[ApplicantFName] $rows[ApplicantName] - $rows[DesignArea] هکتار شهرستان";
    $Appname="$rows[ApplicantFName] $rows[ApplicantName]  ";
    
    $encrypted_string=$rows['CPI'];//نام کاربر
    $encryption_key="!@#$8^&*";//کلید
		$decrypted_string="";//نام کاربر دیکود شده
		for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
				$decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $encrypted_string=$rows['DVFS'];//نام خانوادگی کاربر
    $encryption_key="!@#$8^&*";//کلید
    $decrypted_string.=" ";//نام خانوادگی کاربر دیکود شده
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    
    
    $spname=$decrypted_string;//نام کاربر
    $spDesignArea="$rows[DesignArea]";//مساحت
    $Datebandp="$rows[Datebandp]";//تاریخ ترک تشریفات
    

    /*
    applicantmaster جدول مشخصات طرح
    designer.LName نام خانوادگی طراح
    designer.FName نام طراح
    shahrcityname نام شهر
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
    */
                        

    $sql = "SELECT applicantmaster.*,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
    ,operatorapprequest.*,operatorco.Title operatorcoTitle,concat(operatorco.CompanyAddress,' -تلفن: ',operatorco.Phone2,' - ',operatorco.bossmobile) CoAddress
    ,corank,firstperiodcoprojectarea,case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end firstperiodcoprojectnumber ,coprojectsum,operatorapprequest.SaveDate operatorapprequestSaveDate,
    operatorapprequest.state  operatorapprequeststate,operatorapprequestID,yearcost.Value fb,boardvalidationdate,copermisionvalidate,joinyear,errors,StarCo,MaxDone
    ,operatorapprequest.coef1 pcoef1,operatorapprequest.coef2 pcoef2
    , operatorapprequest.coef3 pcoef3
    ,operatorapprequest.coef3 approvedcoef,operatorapprequest.apval
    
    ,ifnull(operatorco.projecthektar92 ,0)+
                ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0  
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1 and applicantmasterop.applicantstatesID in (34,35,38) 
                and operatorapprequestin.operatorcoID=operatorco.operatorcoID),0) projecthektardone
                
    ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0)
    +ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=0  and applicantmasterall.DesignArea>10
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0)
     simultaneouscnt 
    
    ,ifnull(firstperiodcoprojectarea ,0)+
    ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) 
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) thisyearprgarea
    
    
    
    ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=1
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) above20cnt
    
    
    ,
    case ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0) when 0 then
    (select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>55 and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)
    else case SUBSTR(ifnull(firstperiodcoprojectarea,0)/ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)-50,0,1) when '-' then
    (select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>55 and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)
    else 
    ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterall.DesignArea>55 
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) end end above55cnt
    
    
    ,(select max(applicantmasterall.DesignArea) maxarea from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and applicantmasterop.applicantstatesID not in (34,35,38) and ifnull(appsize,0)=1 
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)  above20max
    
       
    ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid) designercnt
    ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid
    and NationalCode in (SELECT NationalCode FROM `designer` GROUP BY NationalCode HAVING count( * ) >1)) duplicatedesigner
    FROM applicantmaster 
    
    left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
    left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    left outer join designer on designer.designerid=applicantmaster.designerid
    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID
    inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
    
    where operatorapprequest.ApplicantMasterID='$ApplicantMasterID' and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 
    ORDER BY operatorapprequest.price  ;";
    //print $sql;
    try 
        {		
            $result = mysql_query($sql);    
            $resquery = mysql_fetch_assoc($result);
            mysql_data_seek( $result, 0 );
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }  
    

	//////////////////////////////c1 c2 calculation	
	if ($resquery['proposestate']>=1)
		$tend=$resquery["ADate"];
	else
		$tend=$ADate=date('Y-m-d');
		$C1='';
		$C2='';
		$Po='';
		$Betazarib='';
		$yzarib='';
		$average='';
		$stdev='';
		$tzarib='';
		$rown=1;
		while($resquery2 = mysql_fetch_assoc($result))
		{
            /*
            $resquery2["apval"] مبلغ برآورد مطالعات
            $resquery2["operatorcoID"]  شناسه پیمانکار
            $resquery2["operatorcoTitle"] عنوان پیمانکار
            $resquery2["price"] مبلغ
            $resquery2["LastFehrestbaha"] مبلغ هزینه های اجرای طرح
            $resquery2["YearID"] سال طرح
            */	
			$arrayin[$rown][0]=$resquery2["operatorcoTitle"];		
			$arrayin[$rown][1]=$resquery2["price"];		
			$arrayin[$rown][2]=$resquery2["LastFehrestbaha"];
			$arrayin[$rown][3]=$resquery2["YearID"];
			$rown++;
		}
            /*
            operatorapprequest جدول پیشنهاد قیمت
            C1 ضریب c1
            C2 ضریب c2
            Po ضریب Po
            ApplicantMasterID شناسه طرح
            */
		$linearray = explode('_',calculatec1c2($arrayin,$tend));
		$C1=$linearray[0];
		$C2=$linearray[1];
		$Po=$linearray[2];
		$Betazarib=$linearray[3];
		$yzarib=$linearray[4];
		$average=$linearray[5];
		$stdev=$linearray[6];
		$tzarib=$linearray[7];
		//print "$C1 $C2"; 		
	
	/////////////////////////////////////////////
	
    mysql_data_seek( $result, 0 );//انتقال اشاره گر آرایه نتایج پرس و جو به ابتدا
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
	
function tempvalchange()
{
    document.getElementById('seltotaltemp').value=document.getElementById('seltotaltemp').value.replace("/", ".");
    document.getElementById('seltotal').value=document.getElementById('seltotaltemp').value;  
    calc();  
}
function CheckForm()
{
    if (document.getElementById("tempsubmitexcept"))
        return true; 
    
    
    if(document.getElementById('below3').value>0)
    {
        alert('مدت زمان دریافت پیشنهاد هنوز به پایان نرسیده است');
        return false;   
    }
    
    if (document.getElementById("tempsubmit1"))
    {
        if (document.getElementById('tempsubmit1').value!="" )
            return confirm('آیا مطمئن هستید؟ '); 
    }
    else if (document.getElementById("tempsubmit2"))
    {
        if (document.getElementById('tempsubmit2').value!="" )
            return confirm('آیا مطمئن هستید؟ '); 
    }   
    
    else
    {
        
        if (document.getElementById('seltotaltemp').value>0)
            tempvalchange();
        
        if (!(document.getElementById('selbase').value>0))
            {
                alert('لطفا یکی از پیشنهاد ها را انتخاب نمایید!');return false;
            }
            
            
            
        if (document.getElementById('seltotal').value>0 )
        {
            
                        
            if (!(document.getElementById('seltotaltemp').value>0))
            {
                alert("لطفا مبلغ تعیین شده توسط ناظر عالی جهت اجرا را وارد نمایید!");
                document.getElementById('seltotaltemp').focus();
                return false;
            }
            
               
            
            if ( ((document.getElementById('seltotal').value*1)/(document.getElementById('selbase').value*1))>=3
            ||
            !((document.getElementById('seltotal').value*1)>1)) 
            {
                alert('مبلغ وارده منطقی به نظر نمیرسد!');return false;
            }
        
            
    	   return confirm(' آیا از انتخاب شرکت '+document.getElementById('selop').value+' با مبلغ کل اجرای '+
            document.getElementById('seltotal').value+' میلیون ریال مطمئن هستید؟ ');  
        }
        
    }
   


    
	
}

    function calc()
    {
        document.getElementById('seltotal').value=document.getElementById('seltotal').value.replace("/", ".");
        document.getElementById('approvedcoef').value=Math.floor(document.getElementById('seltotal').value/(
        document.getElementById('selbase').value*1.3*1.05)*1000)/1000;
        /*
        document.getElementById('seltotal').value=Math.floor(
        document.getElementById('selbase').value*
        document.getElementById('selc1').value*
        document.getElementById('selc2').value*
        document.getElementById('approvedcoef').value*10)/10;*/
        //alert (val) ;
    }
    
    function changeradio(selop,selc1,selc2,approvedcoef,price,selbase)
    {
        document.getElementById('selop').value=selop;
        document.getElementById('selc1').value=1.3;
        document.getElementById('selc2').value=1.05;
        document.getElementById('approvedcoef').value=Math.floor(price/(selbase*1.3*1.05)*100)/100;
        document.getElementById('selbase').value=Math.floor(selbase*10)/10;
        document.getElementById('seltotal').value=Math.floor(price*10)/10;
        //alert (val) ;
    }  

	function selectpage(){
	   var vshowm=0;
	   if (document.getElementById('showm').checked) vshowm=1;
       
	   window.location.href ='?uid=' +document.getElementById('uid').value
        + '&showm=' + vshowm;
        
	}
          
    </script> 
	
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:right;font-size:13pt;line-height:150%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
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
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="allapplicantrequestdetail.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                       
                
                <table align='center' class="page" border='1'>              
                    <tr>    
                            <th colspan='15' class="f10_font"
                            >کلیه اطلاعات پیشنهاد قیمت در اختیار مدیریت آب و خاک می باشد، لطفاً جهت هرگونه اعلام نظر و تغییرات با مدیر آب و خاک تماس گرفته شود. </th>
                            
                            
                                </tr>
				  <tr> 
                  
                            <td colspan="15"
                            <span class="f14_font" >لیست پیشنهاد قیمت های انجام شده طرح <?php echo $ApplicantName=$ApplicantName.' '.$resquery[shahrcityname];?> (مبالغ بر حسب میلیون ریال)</span>  
                            </td>
                            <td class="data"><input name="showm" type="checkbox" id="showm" onChange="selectpage()" value='<?php echo $showm."'"; ?>' <?php if ($showm>0) echo "checked"; ?> /></td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                            <td class="data"><input name="smallapplicantsize" type="hidden" class="textbox" id="smallapplicantsize"  value="<?php echo $Permissionvals['smallapplicantsize']; ?>"  /></td>
                            <td class="data"><input name="DesignArea" type="hidden" class="textbox" id="DesignArea"  value="<?php echo $spDesignArea; ?>"  /></td>
                            <td class="data"><input name="Datebandp" type="hidden" class="textbox" id="Datebandp"  value="<?php echo $Datebandp; ?>"  /></td>
                            
                            
                            
				   </tr>
                     
                     <?php
					if ($pagetype<>6) {$hide="display:none;";}
   
                      if ($resquery['isbandp']>0)  $isbandhide='display:none';
                      
					  if ($done>0)
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_font\"></th>
                            <th class=\"f10_font\" >پیشنهاددهنده</th>
                            <th colspan=\"4\" class=\"f10_font\" >مجوز </th>
                            <th colspan=\"2\" class=\"f10_font\" >مبلغ طرح</th>
							<th colspan=\"3\" class=\"f10_font\" style=$hide >پيشنهاد قيمت  </th>
                            <th class=\"f10_font\"  style=$isbandhide >دلايل</th>
                            <th class=\"f10_font\"  >اسکن فرم</th>
                        </tr>
		
					 <tr>
                          <th ></th>
                            <th class=\"f10_font\"></th>
                            <th class=\"f10_font\" >شركت مجري</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >فهرست بها</th>
                            <th class=\"f10_font\" >مبلغ </th>
                            <th class=\"f10_font\" style=$hide> اجراي پروژه</th>
                            <th class=\"f10_font\"  style=$hide>تاریخ</th>
                            <th class=\"f10_font\"  style=$hide >شاخص مالی</th>
                            <th class=\"f10_font\" style=$isbandhide >عدم صلاحیت</th>
                            <th class=\"f10_font\" >پيشنهاد قيمت</th>
                        </tr>";
                     else
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_font\"></th>
                            <th class=\"f10_font\" >پیشنهاددهنده</th>
                            <th colspan=\"4\" class=\"f10_font\" >مجوز </th>
                            <th colspan=\"2\" class=\"f10_font\" >پروژه در دست اجرا</th>
                            <th colspan=\"2\" class=\"f10_font\" >مبلغ طرح</th>
							<th colspan=\"3\" class=\"f10_font\" style=$hide >پيشنهاد قيمت  </th>
                            <th class=\"f10_font\" style=$isbandhide >دلايل</th>
                            <th class=\"f10_font\"  >اسکن فرم</th>
                        </tr>
		
					 <tr>
                          <th ></th>
                            <th class=\"f10_font\"></th>
                            <th class=\"f10_font\" >شركت مجري</th>
                            <th class=\"f10_font\" >پایه</th>
                            <th class=\"f10_font\" >سطح هر پروژه</th>
                            <th class=\"f10_font\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_font\" >مجموع سطح در سال</th>
                            <th class=\"f10_font\" >تعداد</th>
                            <th class=\"f10_font\" >مساحت</th>
                            <th class=\"f10_font\" >فهرست بها</th>
                            <th class=\"f10_font\" >مبلغ </th>
                            <th class=\"f10_font\" style=$hide> اجراي پروژه</th>
                            <th class=\"f10_font\" style=$hide >تاریخ</th>
                            <th class=\"f10_font\" style=$hide >شاخص مالی</th>
                            <th class=\"f10_font\" style=$isbandhide>عدم صلاحیت</th>
                            <th class=\"f10_font\"  >پيشنهاد قيمت</th>
                        </tr>";
                     
                     
                     
                      ?>
                        
<?php
                    
                    
                    $Total=0;
                    $rown=0;
                    $Description="";
                    
                    if ($Datebandp>0)
                        $datetoprint=gregorian_to_jalali($Datebandp);
                    else
                        $datetoprint=gregorian_to_jalali(date('Y-m-d'));
        
                            
                    while($resquery = mysql_fetch_assoc($result))
                    {
                        
                        if ($resquery['state']>0)
                        $Description=$resquery['Description'];
                        $fstr1="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/propose/';
                        $handler = opendir($directory);
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                    
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if ($ID==$resquery['operatorapprequestID'] )
                                    $fstr1="<a target='blank' href='../../upfolder/propose/$file' ><img style = 'width: 25px;' src='../img/full_page.png' title='اسکن پیشنهاد' ></a>";
                                    
                                    
                            }
                        }
                            
                        $errors="";
                        
                                   
                        
                            /*$joinyearlow=0;
                            $date = new DateTime(jalali_to_gregorian($resquery["joinyear"]));
                            $date->modify('+720 day');
                            //$date->add(new DateInterval('P2Y'));
                            if ($date->format('Y-m-d')>date('Y-m-d'))
                                $joinyearlow=1;
                                */
                                
                        if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errors.="<br>*تاریخ مجوز شرکت منقضی شده است.";
                        if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errors.="<br>تاریخ اعتبار هیئت مدیره منقضی شده است.";
                        if (!($resquery["designercnt"]>=1))
                            $errors.="<br>*شرکت فاقد کارشناس طراح است.";
                        if (($resquery["duplicatedesigner"]>=1))
                            $errors.="<br>*کارشناس طراح این شرکت در بیش از یک شرکت شاغل می باشد.";
                        if (($resquery["simultaneouscnt"]>=$Permissionvals['tmphtp']))
                            $errors.="<br>*تعداد پروژه های جاری این شرکت 5 یا بیشتر می باشد.";
                        if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp1']))  
                            $errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                        if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp2']))  
                            $errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                        if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp3']))  
                            $errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                        if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp4']))  
                            $errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                        if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp5']))  
                            $errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                        
                        /*
                        if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20max"]>$Permissionvals['smallapplicantsize']) && (($resquery["above20max"]+$resquery["DesignArea"])>$Permissionvals['hmmp5'])) 
                            $errors.="<br>*مجموع دو طرح بزرگ، بیش تر از سقف مجاز می باشد  ";  
                        */
                        
                        
                        
                        if (($resquery["corank"]==5) && ($resquery["DesignArea"]>55) && ($resquery["above55cnt"]>=$Permissionvals['tmtb50hp5']))  
                            $errors.="<br>*تعداد مجاز طرح های بالای 55 هکتار پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                            
                            //print $resquery["corank"]."-".$resquery["DesignArea"]."-".$resquery["above55cnt"]."-".$Permissionvals['tmtb55hp5'];
                            
                        if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$Permissionvals['hmmp1']))  
                            $errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
                        if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$Permissionvals['hmmp2']))  
                            $errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
                        if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$Permissionvals['hmmp3']))  
                            $errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
                        if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$Permissionvals['hmmp4']))  
                            $errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
                        if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['hmmp5']))  
                            $errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
                            
                        if (($resquery["corank"]==1) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp1']))  
                            $errors.="<br>*مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
                        if (($resquery["corank"]==2) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp2']))  
                            $errors.="<br>*مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
                        if (($resquery["corank"]==3) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp3']))  
                            $errors.="<br>*مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
                        if (($resquery["corank"]==4) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp4']))  
                            $errors.="<br>*مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
                        if (($resquery["corank"]==5) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp5']))  
                            $errors.="<br>*مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
                        
                        //if (($resquery["corank"]==5) && ($resquery["DesignArea"]>55) && ($joinyearlow==1))  
                        //        $errors.="<br>*شرکت سابقه کافی جهت پیشنهاد قیمت این طرح را دارا نمی باشد.";  
                        
                        if ($resquery["projecthektardone"]>100)
                            $Max=110;
                        else if ($resquery["projecthektardone"]>50 && ($resquery["MaxDone"]>55))    
                            $Max=$resquery["MaxDone"];
                        else $Max=55;    
                        
                        if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Max))  
                                $errors.="<br>*شرکت طبق مصوبه کمیته فنی آب و خاک  فاقد سابقه کار مورد نیاز می باشد.";  
                                
                        if ($resquery["StarCo"]==1 && $resquery["DesignArea"]>$Permissionvals['smallapplicantsize'])
                       
                                $errors.="<br>*شرکت طبق مصوبه کمیته فنی آب و خاک و آیین نامه  مجاز به پیشنهاد قیمت نمی باشد.";  
                       
					         // print $tend;
					 // print (floor(100*$resquery["price"]/$Po*10)/10).'$'.$C1;
                       if ((floor(100*$resquery["price"]/$Po*10)/10)<$C1 || (floor(100*$resquery["price"]/$Po*10)/10)>$C2)
						{if ($tend>'2015-12-12')	$errors.="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.";}
					//	{$errors.="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.";}
					
                    
                        //if ($resquery["operatorapprequestSaveDate"]>$resquery["Windate"] && ($resquery["Windate"]!=''))
                        //$errors.="<br>*تاریخ پیشنهاد قیمت بعد از انتخاب منتخب پیشنهاد می باشد.";  
                        //else 
                        if ($done>0 && $resquery["Windate"]=='')
                        continue;
                        if ($done>0)
                            $errors=$resquery['errors'] ;    
                        if ($resquery['isbandp']>0)
                            $errors='';
                       
						if (strlen($errors)>0 && !($Datebandp>0)) $cl='ff0000'; else $cl='000000';    
                                            
                       
                        if (!($showm>0) && !($done>0) && strlen($errors)>0 && !($Datebandp>0)) continue;
                            
                            $rown++;
					   if ($rown%2==1) 
                            $b='b'; else $b='';
                       
                             print "<tr '><td class='data'><input name='operatorapprequestID$rown' type='hidden' class='textbox' id='operatorapprequestID$rown' 
                              value='$resquery[operatorapprequestID]'  /></td>";
                              if ($done>0)
                              {
                                if ($resquery["operatorapprequeststate"]>0) 
                                    echo "<td class='f10_font$b'  colspan='1' ><img style = 'width:30px;' src='../img/accept.png' title=''></td>";
                                else echo "<td class='f10_font$b'  colspan='1' ></td>"; 
                              }
                              else echo "<td class='f10_font$b'  colspan='1' ><input onChange='changeradio(\"$resquery[operatorcoTitle]\",\"$resquery[pcoef1]\",\"$resquery[pcoef2]\",\"$resquery[pcoef3]\",\"$resquery[price]\",\"".(floor($resquery["LastFehrestbaha"]/100000)/10)."\")'  type='radio' name='rgs' value='$resquery[operatorapprequestID]'  /></td>";
                              
?>                      
                            
                            
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $Aichart[$rown]=$resquery["operatorcoTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["corank"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php  
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmp5'] ;
                            
                            ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $Permissionvals['tmphtp'];  ?></td>
                            <td class="f10_font<?php echo $b; ?>"  colspan="1" style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmsmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmsmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmsmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmsmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmsmp5'] ;
                            echo "</td>";
                            if (!($done>0))
                            echo "<td class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[simultaneouscnt]</td>
                            <td class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".(floor($resquery["thisyearprgarea"]*10)/10)."</td>
                            ";
                            
                              ?>
                            
                            
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["costyear"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastFehrestbaha"]/100000)/10; ?></td>
                            <!--
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef1"],2); ?></td>
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef2"],2); ?></td>
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef3"],2); ?></td>
                            !-->
                            <td  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>; <?php echo $hide;?> text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["price"]*10)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;  <?php echo $hide;?>text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo gregorian_to_jalali($resquery["operatorapprequestSaveDate"]); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;  <?php echo $hide;?> text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $Xichart[$rown]=floor(100*$resquery["price"]/$Po*10)/10; ?></td>
                     <?php 
                     
                        echo "<td class=\"f10_font$b\"  style=$isbandhide \"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>";
                     
                     ?>
                        <td  class="f10_font<?php echo $b; ?>"   <?php if ($hide) echo '';else echo $fstr1; ?></td>
                        <?php
                            
                             
                            echo "
                              <td class='data'><input name='errors$rown' type='hidden' class='textbox' id='errors$rown'  
                        value='$errors'  /></td>
                                                  
                        
                        </tr>
                        ";
                        
                        
                         $below3=0;
                        if ($resquery['proposestate']==0 && !($resquery['isbandp']>0))
                        {
                            $date = new DateTime($resquery["ADate"]);
                            $date->modify('+'.$Permissionvals['proposedaycnt'].' day');
                            if ($date->format('Y-m-d')>date('Y-m-d'))
                                $below3=1;     
                                                   
                        }

                            
                        $proposestate=$resquery['proposestate'];
                        if ($resquery['state']==1)
                        {
                         
                            $approvedcoef=$resquery['approvedcoef'];   
                            $selop= $resquery["operatorcoTitle"];
                            $selbase= floor($resquery["LastFehrestbaha"]/100000)/10;
                            $selc1= floor($resquery["pcoef1"]*100)/100;
                            $selc2= floor($resquery["pcoef2"]*100)/100;
                            //$seltotal= floor($resquery["LastFehrestbaha"]/100000)/10*$resquery["pcoef1"]*$resquery["pcoef2"]*$approvedcoef*10)/10;
                            $seltotal= $resquery["apval"];
                            $datetoprint=gregorian_to_jalali($resquery["Windate"]);
                            $winerrors=$resquery['errors'] ;
                            
                        }
				
			        }

					


				
			          echo "<tr><td colspan='18'>&nbsp;</td> </tr>";
          	
	              echo " 
                    
				<tr>
                    <td colspan='6'></td>
                      <td colspan='5' class='data'>محاسبه برآورد به هنگام (Po):
                      <input  name='Po' type='text' class='textbox' id='Po' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='$Po'/>
                      
				
			
                      <td  class='data'>
                      <input  name='Po' type='text' class='textbox' id='Po' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='100'/></td>
                      
            
				   </tr>
				   
			<tr>
                     
                      <td  colspan='18' class='data'>ضریب به هنگام سازی (β): 
                      <input  name='Betazarib' type='text' class='textbox' id='Betazarib' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='$Betazarib'/>
                      
					   ضریب پیشبینی قیمت(γ):
                      <input  name='yzarib' type='text' class='textbox' id='yzarib' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='$yzarib'/>
                      
					   اهمیت پیشنهاد قیمت: 
                      <input  name='yzarib' type='text' class='textbox' id='yzarib' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='متوسط'/>
                      
					  ضریب اهمیت  (t):
                      <input  name='tzarib' type='text' class='textbox' id='tzarib' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='$tzarib'/></td>
                      
					  
            
				   </tr>
		
	
				   
				<tr>
                     
                      <td  colspan='18' class='data'>میانگین (m):
                      <input  name='average' type='text' class='textbox' id='average' style='width:60px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='7' value='$average'/>
                      
					  انحراف معیار(s):
                      <input  name='stdev' type='text' class='textbox' id='stdev' style='width:60px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='7' value='$stdev'/>
                      
					  حد پایین دامنه قیمت(C1):
                      <input  name='C1' type='text' class='textbox' id='C1' style='width:60px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='7' value='$C1'/>
                      
					  حد بالای دامنه قیمت (C2):
                      <input  name='C2' type='text' class='textbox' id='C2' style='width:60px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='8' value='$C2'/></td>
            	   </tr>
	                 
			             ";
					     
?>

               </table>
		              </form>   
            </div>
			<!-- /content -->


            <!-- footer -->
			
            <!-- /footer -->
	 <?php print "-------------------------------------------------------------------------------------- نمودار دامنه متناسب قیمتهای پیشنهادی--------------------------------------------------------------------------"; ?>

		</div>
		
        <!-- /wrapper -->
	</div>
    <!-- /container -->

	
	
</body>
</html>		
   
<?php
//print_r ($Xichart);
  	$maxi=count($Xichart);
	
	$Cmin=round(($Xichart[1]*0.85),1);$Cmax=round(($Xichart[$maxi]*1.1),1); 
		  $datazone1='['.$C1.','.'0'.'],';
  		  $datazone2='['.$C2.','.'0'.'],';
       
	
	for($i=1;$i<$maxi+1;$i++)
        {
		  $size=0;
		  $data.='{name: "'.$Aichart[$i].'", x:'.$Xichart[$i].', y:'.$i.', size:'.$size.'} ,';
		  $datazone1.='['.$C1.','.$i.'],';
  		  $datazone2.='['.$C2.','.$i.'],';
       
		}
    $data=rtrim($data,',');$maxi=$maxi+1;
	 $datazone1.='['.$C1.','.$maxi.'],';
  	 $datazone2.='['.$C2.','.$maxi.'],';
       
	$datazone3='['.$C1.','.$maxi.'],';
    $datazone3.='['.$C2.','.$maxi.'],';	
		?>
		
<script src="../js/anychart-bundle.min.js"></script>
  <style>
     html, body, #container {
      width: 94%;
      height: 80%;
      margin: 20px;
      padding: 0;
    }
     </style>

    <script>
	c0=<?php echo '['.$Cmin.']'; ?>;c1=<?php echo '['.$C1.']'; ?>;c2=<?php echo '['.$C2.']'; ?>;c3=<?php echo '['.$Cmax.']'; ?>;;
	
anychart.onDocumentReady(function() {
  var chart = anychart.scatterChart();

  chart.title().text('Springfield\'s Clusters\nLabels and Tooltips Settings')
    .hAlign('center');


  var chart = anychart.scatterChart();

  chart.title().text('Springfield\'s Clusters\nLabels and Tooltips Settings')
    .hAlign('center');

  // y scale settings
  chart.yScale()
    .minimum(0)
    .maximum(<?php echo $rown+1; ?>)
    .ticks()
      .interval(1);

  // adjust y axis labels representation
  chart.yAxis().labels().textFormatter(function(){
    return this.value + ' '
  });
  
       
  chart.yAxis().title().text('****************');

  // x scale settings
  chart.xScale()
    .minimum(<?php echo $Cmin; ?>)
    .maximum(<?php echo $Cmax; ?>)
    .ticks()
      .interval(1);

  // adjust x axis labels representation
  chart.xAxis().labels().textFormatter(function(){
    return '' + this.value / 1 + ''
  });

  chart.xAxis().title().text('شاخص مالی');

  // data for chart
  var data = anychart.data.set([
    <?php echo $data; ?>
  ]);

  // map data for future usage
  var view = data.mapAs();

  // data for line series
  var bubble = chart.bubble(data);

  
  // set labels and adjust visualization
  bubble.fill('orange')
    .hoverFill('DodgerBlue')
    .hoverHatchFill('zigzag')
    .minimumSize(1)
    .maximumSize(3)
    .stroke(anychart.color.darken('DodgerBlue'))
    .hoverStroke('#000000')
    .labels()
      .enabled(true)
      .position('top')
      .anchor('topCenter')
      .fontColor(anychart.color.darken('green'))
      .fontSize(12)
      .fontWeight(40)
      .textFormatter(function(){
        return view.get(
          this.index,           // index of a current point is used to get row with point's data
          'name'                // field to display
        );
      });

  // set tooltips
  bubble.tooltip().contentFormatter(function(){
    return view.get(this.index, 'name') + '\n  ' + this.x / 1 + ' ' + '\n  ' + this.value;
  });

  chart.container('container').draw();
chart.background().fill('#f5deb3');
  
  chart.line([
    <?php echo $datazone1; ?>
  ]);
  chart.draw();
  
  chart.line([
    <?php echo $datazone2; ?>
  ]);
  chart.draw();
  
  
  chart.line([
    <?php echo $datazone3; ?>
  ]);
  chart.draw();
  

   
});

	
</script>
