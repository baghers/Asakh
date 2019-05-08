<?php 
/*

//appinvestigation/allapplicantrequestdetail.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantrequest.php

*/

include('../includes/connect.php'); 
include('../includes/check_user.php'); 
include('../includes/functions.php');

$zarib5=1;//ضریب پنجم پیشنهاد قیمت
$coef1=1.3;//ضریب اول پیشنهاد قیمت
$coef2=1.05;//ضریب دوم پیشنهاد قیمت
$maxAreacorank5=55;//حداکثر مساحت قابل اجرای پایه 5
$maxAreasmalls=10.9;//حداکثر مساحت طرح های کوچک قابل انتخاب
$maxAreasmall=14.9;//حداکثر مساحت طرح های جزء ظرفیت
  
if ($login_Permission_granted==0) header("Location: ../login.php");

//$login_ostanId شناسه استان
 
 $Permissionvals=supervisorcoderrquirement_sql($login_ostanId);//تابع دریافت اطلاعات پیکربندی سیستم
    
if ($_POST)//در صورت کلیک دکمه سابمیت
{   
    $Description=$_POST['Description'];//توضیحات  
   
    if ($_POST['Datebandp']>0)//پروژه به صورت ترک تشریفات بود
        $ADate=$_POST['Datebandp'];//تاریخ شروع پیشنهاد قیمت
        else
        $ADate=date('Y-m-d');
        
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
		
        $query = " update operatorapprequest set ecept=1,errors=concat( '$_POST[winerrors] <br>','_ $login_fullname _".date('Y-m-d H:i:s')."_".
        $_POST['Description']."') WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' and state=1;";
		//	print $query;exit;	
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
            proposestate=1,ADate='".date('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
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
    else if ($_POST['tempsubmit2'])//دکمه ارجاع به ناظر عالی
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
        proposestate=2,ADate2='".date('Y-m-d')."',ADate='".date('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
            
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
    }
    else if (($_POST['rgs']>0) && ($login_userid>0) )//ثبت منتخب پیشنهاد
    {
        $i=0;
        $Description=$_POST['Description'];//توضیحات  
        if ($_POST['Datebandp']>0)//پروژه به صورت ترک تشریفات بود
        $Windate=$_POST['Datebandp'];//تاریخ انتخاب مجری
        else
        $Windate=date('Y-m-d');//تاریخ انتخاب مجری
        while (isset($_POST['operatorapprequestID'.++$i]))//پیمایش کلیه پیشنهادات انجام شده
        {
            if ($_POST['errors'.$i]!='')//در صورتی که خطا دار بود تادریافت مجوز مدیر آب و خاک تاریخ انتخاب مجری خالی می باشد
            $Windatestr='';
            else 
            $Windatestr=",Windate='$Windate'";//تاریخ انتخاب مجری
            if ($_POST['apps']==1)//کوچک بودن طرح
                $_POST['errors'.$i].="<br>این طرح به عنوان طرح کوچک در نظر گرفته شده است";
            
            if ($_POST['cappacityless']==1)//خارج از ظرفیت بودن طرح
                $_POST['errors'.$i].="<br>پس از تاییدآب وخاک به عنوان طرح خارج از ظرفیت در نظر گرفته می شود";
                   
            $operatorapprequestID=$_POST['operatorapprequestID'.$i];//شناسه ردیف پیشنهاد قیمت
            $errors=$_POST['errors'.$i];//پیغام های عدم صلاحیت
            /*
                operatorapprequest جدول پیشنهاد قیمت های طرح    
                ordering ترتیب مبلغ پیشنهادی
                state برنده شدن یا نشدن
                errors پیغام های عدم صلاحیت
                operatorapprequestID شناسه ردیف پیشنهاد قیمت
            */
            $query = " update operatorapprequest set ordering='$i',state=0,errors='$errors' $Windatestr WHERE operatorapprequestID ='$operatorapprequestID' ;";
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
        //$_POST["DesignArea"] مساحت پروژه
        //$_POST['smallapplicantsize'] حداکثر مساحت پروژه کوچک
        //$_POST['apps'] کوچک بودن پروژه
           if (($_POST["DesignArea"]>$_POST['smallapplicantsize']) || ($_POST['apps']!=1))
                $appsize=1;//کوچک بودن پروژه
           else $appsize=0;
           
        
        if ($_POST['approvedcoef']>0)//ضریب سوم انتخابی   
			$newset="coef3='$_POST[approvedcoef]',";
        else $newset="";
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
        $query = " update operatorapprequest set  $newset coef1=$coef1,coef2=$coef2,appsize='$appsize', state=1,apval='$_POST[seltotal]',ClerkID='$login_userid'
						,Description='$Description' WHERE operatorapprequestID ='$_POST[rgs]' ;";
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
        proposestate=3,ADate='".date('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";    
        try 
            {		
                mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }
            		
				
        // آپدیت مشخصات پیمانکار
        //$_POST["DesignArea"] مساحت پروژه
        //$_POST['smallapplicantsize'] حداکثر مساحت پروژه کوچک
        //above20cnt تعداد طرح های بالای 20 هکتار
        if ($_POST["DesignArea"]>$_POST['smallapplicantsize'])
            $above=",above20cnt='1'+'$_POST[selabove20cnt]'";
        //above55cnt تعداد طرح های بالای 55 هکتار
        if ($_POST["DesignArea"]>$maxAreacorank5)
            $above.=",above55cnt='1'+'$_POST[selabove55cnt]'"; 
        /*
        operatorco جدول مشخصات پیمانکار
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        simultaneouscnt تعداد پروژه های همزمان
        currentprgarea مجموع مساحت کارهای جاری
        projecthektardone مجموع مساحت انجام داده
        projectcountdone تعداد مساحت انجام داده
        corank رتبه شرکت
        thisyearprgarea مجموع مسات سالانه جاری
        operatorcoID شناسه پیمانکار
        */
        $query = " update operatorco set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "', 
		 simultaneouscnt=1+simultaneouscnt
		 ,currentprgarea='$_POST[DesignArea]'+'".round($_POST['selsimhektar'],1)."'
		 ,projecthektardone='$_POST[selprojecthektardone]'
		 ,projectcountdone='$_POST[selprojectcountdone]'
		 ,corank='$_POST[selcorank]'
         ,thisyearprgarea='$_POST[DesignArea]'+'".round($_POST['selthisyearprgarea'],1)."'
		
			$above
			WHERE operatorcoID ='$_POST[selopID]' ;";
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
    }	
}
else
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $uid=$_GET["uid"];
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $showm=is_numeric($_GET["showm"]) ? intval($_GET["showm"]) : 0;//نمایش تمام پیشنهادات صلاحیت دار و ندار
	$shown=is_numeric($_GET["shown"]) ? intval($_GET["shown"]) : 0;//نمایش خطاهای ناظر

	if ($login_designerCO==1)//شرکت مهندسی مشاور
    {
        $selectedCityId=substr($linearray[4],0,2);//شناسه طرح
       	$Permissionvals=supervisorcoderrquirement_sql($selectedCityId);//تابع دریافت اطلاعات پیکربندی سامانه    				
    }
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
    $querys = "SELECT ApplicantName,ApplicantFName,DesignArea,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID,Datebandp,CountyName,ifnull(tanzilejra,0) tanzilejra from applicantmaster 
    inner join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
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
    $tanzilejraPlussone=round($rows['tanzilejra']/100+1,2);
    $ApplicantName="$rows[ApplicantFName] $rows[ApplicantName] - $rows[DesignArea] هکتار شهرستان";//عنوان پروژه
    $Appname="$rows[ApplicantFName] $rows[ApplicantName]  ";//عنوان متقاضی
    $linearray = explode('_',$rows['CountyName']);
    $CountyName=$linearray[0];//روستا
    $registerplace=$linearray[1];//محل ثبت
    $fathername=$linearray[2];//نام پدر
    $birthdate=$linearray[3];//تاریخ تولد
    $shenasnamecode=$linearray[4];//ش شناسنامه
    $apps=$linearray[5];//اندازه پروژه
    $cappacityless=$linearray[6];//طرح خارج از ظرفیت می باشد یا خیر
    if ($apps==1)
    {
        $Permissionvals['smallapplicantsize']=$rows['DesignArea'];//مساحت پروژه
    }
    
    

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
    if ($login_designerCO==1)//شرکت مهندسین مشاور
    $j="";
    else 
    $j="and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)"; //محدودیت شهرستان             
    $currentdatefrom=jalali_to_gregorian((substr(gregorian_to_jalali(date('Y-m-d')),0,4)-1)."/07/01");//از تاریخ  
    $currentdateto=jalali_to_gregorian(substr(gregorian_to_jalali(date('Y-m-d')),0,4)."/06/31");//تا تاریخ                    
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
    ,operatorapprequest.*,operatorco.Title operatorcoTitle,concat(operatorco.CompanyAddress,' -تلفن: ',operatorco.Phone2,' - ',operatorco.bossmobile
    ,' -مدیر عامل: ',operatorco.BossName,' ',operatorco.bosslname) CoAddress
    ,operatorco.StarCo,operatorco.ent_DateFrom,operatorco.ent_DateTo,operatorco.ent_Hectar,operatorco.ent_Num
                ,operatorco.Disabled,operatorco.Code Codeop,
    case corank when 5 then 
        case floor((ifnull(operatorco.projecthektar92 ,0)+
                ifnull((
                select sum(ifnull(applicantmasterop.DesignArea,0)) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=operatorapprequestin.ApplicantMasterID
                left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster  
                where ifnull(applicantmasterop.applicantstatesID,0) in (35,38) 
                and operatorapprequestin.operatorcoID=operatorco.operatorcoID and operatorapprequestin.state=1
                ),0))/100)
                when 0 then '5' else '5+' end
                
                  else corank end corank
    ,firstperiodcoprojectarea,case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end firstperiodcoprojectnumber ,coprojectsum,operatorapprequest.SaveDate operatorapprequestSaveDate,
    operatorapprequest.state  operatorapprequeststate,operatorapprequestID,yearcost.Value fb,boardvalidationdate,copermisionvalidate,joinyear,errors,StarCo,MaxDone
    ,operatorapprequest.coef1 pcoef1,operatorapprequest.coef2 pcoef2
    , operatorapprequest.coef3 pcoef3
    ,case ifnull(operatorapprequest.coef3,0) when 0 then  operatorapprequest.coef3 else ifnull(operatorapprequest.coef3,0) end approvedcoef,
    case ifnull(operatorapprequest.coef3,0) when 0 then  0 else operatorapprequest.coef3 end approvedcoef,
    operatorapprequest.apval
    
    ,ifnull(operatorco.projecthektar92 ,0)+
                ifnull((
                select sum(ifnull(applicantmasterop.DesignArea,0)) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=operatorapprequestin.ApplicantMasterID
                left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster  
                where ifnull(applicantmasterop.applicantstatesID,0) in (35,38) 
                and operatorapprequestin.operatorcoID=operatorco.operatorcoID and operatorapprequestin.state=1
                ),0) projecthektardone
    
    ,ifnull(operatorco.projectcount92 ,0)+
                ifnull((
                select count(ifnull(applicantmasterop.DesignArea,0)) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=operatorapprequestin.ApplicantMasterID
                left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster  
                where ifnull(applicantmasterop.applicantstatesID,0) in (35,38) 
                and operatorapprequestin.operatorcoID=operatorco.operatorcoID and operatorapprequestin.state=1
                ),0) projectcountdone
                            
      
    ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select sum(case applicantmasterall.DesignArea>
    case operatorco.corank 
    when 1 then  10000 
    when 2 then  10000 
    when 3 then  10000
    when 4 then  ($Permissionvals[hmmp4]/(1+$Permissionvals[percentapplicantsize4]/100))*1.1
    when 5 then  10000 end and substring(applicantmasterall.CityId,1,2)='19' when 1 then 2 else 1 end) cnt 
    from operatorapprequest operatorapprequestin 
    inner join operatorco on operatorco.operatorcoID=operatorapprequestin.operatorcoID
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0)+
    ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select 
    sum(case applicantmasterall.DesignArea>
    case operatorco.corank 
    when 1 then  10000 
    when 2 then  10000 
    when 3 then  10000
    when 4 then  ($Permissionvals[hmmp4]/(1+$Permissionvals[percentapplicantsize4]/100))*1.1
    when 5 then  10000 end and substring(applicantmasterall.CityId,1,2)='19' when 1 then 2 else 1 end)
    
     cnt from operatorapprequest operatorapprequestin 
     inner join operatorco on operatorco.operatorcoID=operatorapprequestin.operatorcoID
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and ifnull(appsize,0)=0 and applicantmasterall.DesignArea>$maxAreasmall
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0)
    
     simultaneouscnt
    
    ,ifnull(firstperiodcoprojectarea ,0)+
    ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt
     from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) simultaneoushectar
    
    ,ifnull(firstperiodcoprojectarea ,0)+
    ifnull((select sum(case ifnull(applicantmasterop.DesignArea,0) when 0 then applicantmasterall.DesignArea else applicantmasterop.DesignArea end) cnt 
    from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode 
    and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and operatorapprequestin.Windate>='$currentdatefrom' and operatorapprequestin.Windate<='$currentdateto'
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) thisyearprgarea
    
    
    
    ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select 
    sum(case applicantmasterall.DesignArea>
    case operatorco.corank 
    when 1 then  10000 
    when 2 then  10000 
    when 3 then  10000
    when 4 then  ($Permissionvals[hmmp4]/(1+$Permissionvals[percentapplicantsize4]/100))*1.1
    when 5 then  10000 end and substring(applicantmasterall.CityId,1,2)='19' when 1 then 2 else 1 end)
    
     cnt from operatorapprequest operatorapprequestin 
     inner join operatorco on operatorco.operatorcoID=operatorapprequestin.operatorcoID
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and ifnull(appsize,0)=1 and applicantmasterall.DesignArea>$maxAreasmalls
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) above20cnt
    
    
    ,case ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0) when 0 then
    (select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)
    else case SUBSTR(ifnull(firstperiodcoprojectarea,0)/ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)-50,0,1) when '-' then
    (select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)
    else 
    ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
    ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and applicantmasterall.DesignArea>$maxAreacorank5 
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID),0) end end above55cnt
    
    
    ,(select max(applicantmasterall.DesignArea) maxarea from operatorapprequest operatorapprequestin 
    inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
    left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
    where state=1 and ifnull(applicantmasterop.applicantstatesID,0) not in (34,35,38) and ifnull(appsize,0)=1 and applicantmasterall.DesignArea>$maxAreasmalls 
    and operatorapprequestin.operatorcoID=operatorapprequest.operatorcoID)  above20max
    
       
    ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid) designercnt
    ,(select ifnull(count(*),0) from designer where designer.operatorcoid=operatorco.operatorcoid
    and NationalCode in (SELECT NationalCode FROM `designer` GROUP BY NationalCode HAVING count( * ) >1)) duplicatedesigner
    ,applicantmasterdetail.proposelimitless
    FROM applicantmaster 
    
    inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
    
    left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
    left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    
    $j
    
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    left outer join designer on designer.designerid=applicantmaster.designerid
    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID
    inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
    
    where operatorapprequest.ApplicantMasterID='$ApplicantMasterID' and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 
    ORDER BY ifnull(operatorapprequest.ordering,0),operatorapprequest.price  ;";
     //    print $sql;exit;
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
	if ($resquery['proposestate']>=1)//وضعیت پیشنهاد قیمت ارجاع شده بود
		$tend=$resquery["ADate"];//تاریخ ارجاع
	else
		$tend=date('Y-m-d');
	
	    $rown=1;
		while($resquery2 = mysql_fetch_assoc($result))
		{
            try 
                {	
                    /*
                    operatorco جدول مشخصات پیمانکار
                    SaveTime زمان
                    SaveDate تاریخ
                    ClerkID کاربر
                    simultaneouscnt تعداد پروژه همزمان
                    currentprgarea  مساحت پروژه های جاری
                    projecthektardone تعداد پروژه های انجام داده شرکت
                    projectcountdone تعداد پروژه های انجام داده شرکت
                    thisyearprgarea مساحت پرژه های امسال
                    above20cnt تعداد پروژه های بالای 20 هکتار
                    above55cnt تعداد پروژه های بالای 55 هکتار
                    operatorcoID شناسه پیمانکار
                    */
                    mysql_query("update operatorco set SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID ='$login_userid',
    		                  simultaneouscnt='$resquery2[simultaneouscnt]',currentprgarea='$resquery2[simultaneoushectar]',projecthektardone='$resquery2[projecthektardone]'
                                ,projectcountdone='$resquery2[projectcountdone]',thisyearprgarea='$resquery2[thisyearprgarea]' 
                                ,above20cnt='$resquery2[above20cnt]',above55cnt='$resquery2[above55cnt]'
                                WHERE operatorcoID ='$resquery2[operatorcoID]' ;");
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                }
        	
            /*
            $resquery2["apval"] مبلغ برآورد مطالعات
            $resquery2["operatorcoID"]  شناسه پیمانکار
            $resquery2["operatorcoTitle"] عنوان پیمانکار
            $resquery2["price"] مبلغ
            $resquery2["LastFehrestbaha"] مبلغ هزینه های اجرای طرح
            $resquery2["YearID"] سال طرح
            */	      
            if ($resquery2["apval"]>0) $operatorcoID=$resquery2["operatorcoID"];
			$arrayin[$rown][0]=$resquery2["operatorcoTitle"];		
			$arrayin[$rown][1]=$resquery2["price"];		
			$arrayin[$rown][2]=$resquery2["LastFehrestbaha"];
			$arrayin[$rown][3]=$resquery2["YearID"];
			$rown++;
		}
		//print_r ($arrayin);exit;
		$linearray = explode('_',calculatec1c2($arrayin,$tend));
		$C1=$linearray[0];//ضریب c1
		$C2=$linearray[1];//ضریب c2
		$Po=$linearray[2];//ضریب Po
		
        if (!$resquery['C1']>0 || !$resquery['C2']>0)
        {
            /*
            operatorapprequest جدول پیشنهاد قیمت
            C1 ضریب c1
            C2 ضریب c2
            Po ضریب Po
            ApplicantMasterID شناسه طرح
            */
            $queryc = " update operatorapprequest set C1='$C1',C2='$C2',Po='$Po' WHERE ApplicantMasterID='$ApplicantMasterID';";
            try 
              {		
                mysql_query($queryc);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
              
                        
        }
    
    
    $errType=$resquery["errType"];//خطای موجود در طرح
        
    if ($shown==5 && $errType<>5)
    {
        /*
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        errType خطای موجود در طرح
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        */
        $queryc = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                        SaveDate = '" . date('Y-m-d') . "', 
                        ClerkID = '" . $login_userid . "',
        errType='5' WHERE ApplicantMasterID='$ApplicantMasterID' ;";
        try 
              {		
                mysql_query($queryc);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
              
        
        $errType=5;
    }
    
    if ($shown==4 && $errType==5)
    {
        /*
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        errType خطای موجود در طرح
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        */        $queryc = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                        SaveDate = '" . date('Y-m-d') . "', 
                        ClerkID = '" . $login_userid . "',
        errType='' WHERE ApplicantMasterID='$ApplicantMasterID' ;";
        try 
              {		
                mysql_query($queryc);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
        $errType=0;
}	
	/////////////////////////////////////////////
    mysql_data_seek( $result, 0 );//تغییر اشاره گر به ابتدای آرایه
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
function checkvaluec()
{
	if (document.getElementById('seltotal').value>(document.getElementById('selbase').value*<?php echo $tanzilejraPlussone; ?>))
	return 0;
    else
    return 1;
}
function tempvalchange()
{
 
    document.getElementById('seltotaltemp').value=document.getElementById('seltotaltemp').value.replace("/", ".");
    document.getElementById('seltotal').value=document.getElementById('seltotaltemp').value;  
	
    calc();  
	
	if (checkvaluec()==0)
	{
       alert('مبلغ تایید شده از اعتبار اجرایی طرح بیشتر است.');
	   return false;
	}
  //     return confirm('مبلغ تایید شده از اعتبار اجرایی طرح بیشتر است.');
 	
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
        {
            tempvalchange();
           	if (checkvaluec()==0)
        	{
               return false;
        	}
            
        }
            
        
        if (!(document.getElementById('selbase').value>0))
            {
                alert('لطفا یکی از پیشنهاد ها را انتخاب نمایید!');return false;
            }
            
            
            
        if (document.getElementById('seltotal').value>0 )
        {
            
      
		
            if (!(document.getElementById('seltotaltemp').value>0))
            {
                alert("لطفا مبلغ تعیین شده توسط ناظرین جهت اجرا را وارد نمایید!");
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
    
    function changeradio(selop,selopID,selc1,selc2,approvedcoef,price,selbase,err,selsimcnt,selsimhektar,selabove20cnt,selabove55cnt,selthisyearprgarea,selprojecthektardone,projectcountdone,corank,caperr)
    {
//	alert(1);
	document.getElementById('submit').type='submit';
//	alert(2);
        if (err!='')
		{
			document.getElementById('msgerror').value='*پروژه جهت تکمیل فرایند نیازمند مجوز مدیریت  آب و خاک می باشد.';
			if (17==<?php echo $login_RolesID;?> && <?php echo $spDesignArea;?>>10.9 && caperr==0)
			document.getElementById('submit').type='textbox';
			alert(document.getElementById('msgerror').value);	 
		}
        else 
        document.getElementById('msgerror').value='';
		
		
		document.getElementById('seltotaltemp').value='';
        document.getElementById('selop').value=selop;
	    document.getElementById('selopID').value=selopID;
        document.getElementById('selc1').value=1.3;
        document.getElementById('selc2').value=1.05;
        document.getElementById('approvedcoef').value=Math.floor(price/(selbase*1.3*1.05)*1000)/1000;
        document.getElementById('selbase').value=Math.floor(selbase*10)/10;
        document.getElementById('seltotal').value=Math.floor(price*10)/10;
        document.getElementById('selsimcnt').value=selsimcnt;
        document.getElementById('selsimhektar').value=selsimhektar;
		document.getElementById('selabove20cnt').value=selabove20cnt;
		document.getElementById('selabove55cnt').value=selabove55cnt;
		document.getElementById('selthisyearprgarea').value=selthisyearprgarea;
		document.getElementById('selprojecthektardone').value=selprojecthektardone;
		document.getElementById('selprojectcountdone').value=projectcountdone;
		document.getElementById('selcorank').value=corank;

		 Descrip();
		 //alert (Description) ;
    }  

	function Descrip(){
		price=document.getElementById('seltotal').value;
		selbase=document.getElementById('selbase').value;
		selop=document.getElementById('selop').value;
	   
			if (document.getElementById('fbtxt').value>1395) fbtxt=1395; else 	fbtxt=document.getElementById('fbtxt').value;	 
			if (price*1>selbase*1)	var	minos=26.7;	else	var	minos=Math.round((100-Math.floor(price/(selbase*1.3*1.05)*1000)/10)*100)/100;
			var Description=' بنام خدا : \n'+'با توجه به درخواست متقاضی ' + '<?php echo $Appname;?>' + 'ثبت شده به شماره ............ مورخ .......... '
			+ 'ایشان که شرکت ' + selop + ' را به عنوان شرکت مجری پروژه ' + '<?php echo $spDesignArea;?>' + ' هکتاری خود انتخاب و معرفی نموده و شرکت ' 
			+ selop	+ '(ردیف ... استعلام) ' + ' متعهد میباشد که بر اساس پایین ترین قیمت تایید شده استعلام فوق و برابر فهرست بهای سال ' + fbtxt + 'با (' 
			+ minos + ' درصد مینوس ) طرح مذکور را بطور کامل اجرا نماید. ' ;
			document.getElementById('Description').value= Description;
			//alert (Description) ;
		}
		
	function selectpage(){
	   var vshowm=0;
	   if (document.getElementById('showm').checked) vshowm=1;
	   if ((document.getElementById('showerr').value)==0){
	   window.location.href ='?uid=' +document.getElementById('uid').value
        + '&showm=' + vshowm;
        }
		
		
	}
	
  function selectpagenazer(){
	   
	if (document.getElementById('showerrnazer').checked)   var vshown=5; else var vshown=4;
	   window.location.href ='?uid=' +document.getElementById('uid').value
        + '&shown=' + vshown;
		   
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
                            <th colspan='15' class="f10_fontb"
                            >کلیه اطلاعات پیشنهاد قیمت در اختیار مدیریت آب و خاک می باشد، لطفاً جهت هرگونه اعلام نظر و تغییرات با مدیر آب و خاک تماس گرفته شود. 
							<?php
							  $ID = $ApplicantMasterID.'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID'].'_'.$login_RolesID;
                    		 print "<a  target='".$target."' 
                            href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/search.png' title=' ريز '></a>";
							
							?>
							
							</th>
                        <?php
						   $ID = $ApplicantMasterID.'_6_'.$row['DesignerCoID'].'_'.$row['operatorcoID'];
						 	   echo "<td class='no-print'><a  target='_blank' href='allapplicantrequestdetailchart.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ID.rand(10000,99999)."'><img style = 'width: 55%;' src='../img/chart.png' title=' دامنه متناسب پیشنهاد قیمت '></a></td>"; ?>    
                           
                            
                            <?php
                            if ($done==1)
                            if ($login_RolesID==18 || $login_RolesID==27 || $login_designerCO==1 || ($login_RolesID=='17' && $spDesignArea<=$maxAreasmalls) )
                            echo "
                        <td><a 
                            href=\"allapplicantrequestdetail_discard.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.'_'.$spDesignArea.'_'.$Permissionvals['smallapplicantsize'].'_'.$operatorcoID.'_'.$login_ostanId.rand(10000,99999)."\"
                            onClick=\"return confirm('مطمئن هستید که منتخب پیشنهاد لغو شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a></td>";
                            else
                            echo "
                        <td><a href='' > <img style = 'width: 25px;' src='../img/delete.png' title='لغو منتخب پیشنهاد توسط مدیر آب و خاک'> </a></td>";
                            
                             ?>
                        </tr>
				  <tr> 
                            <td colspan="15"
                            <span class="f14_fontb" >لیست پیشنهاد قیمت اجرای  انجام شده طرح <?php 
                            
                            $shahrcityname=$resquery['shahrcityname'];
                            
                            $ApplicantName.=' '.$shahrcityname; echo $ApplicantName;?> (مبالغ بر حسب میلیون ریال)</span>  
                            </td>
                            <td class="data"><input name="showm" type="hidden" id="showm" onChange="<?php if (!$done>0) $selectpage='selectpage()';
							echo $selectpage; ?>" value='<?php echo $showm."'"; ?>' <?php if ($showm>0) echo "checked"; ?> /></td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                            <td class="data"><input name="smallapplicantsize" type="hidden" class="textbox" id="smallapplicantsize"  value="<?php echo $Permissionvals['smallapplicantsize']; ?>"  /></td>
                            <td class="data"><input name="DesignArea" type="hidden" class="textbox" id="DesignArea"  value="<?php echo $spDesignArea; ?>"  /></td>
                            <td class="data"><input name="Datebandp" type="hidden" class="textbox" id="Datebandp"  value="<?php echo $Datebandp; ?>"  /></td>
                            <td class="data"><input name="apps" type="hidden" class="textbox" id="apps"  value="<?php echo $apps; ?>"  /></td>
                            
                            
                            
				   </tr>
                     
                     <?php
					 
					          if ($done>0)
								{
									$fstrm="";
									$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/proposm/';
									$handler = opendir($directory);
        
									while ($file = readdir($handler)) 
									{
										// if file isn't this directory or its parent, add it to the results
										if ($file != "." && $file != "..") 
										{
											$linearray = explode('_',$file);
											$ID=$linearray[0];
											if ($ID==$ApplicantMasterID)
												{
													$fstrm2="<a target='$target' href='../../upfolder/proposm/$file' ><img style = 'width: 100px;' src='../../upfolder/proposm/$file'  title='اسکن' ></a>";
													$fstrm="<td><a target='$target' href='../../upfolder/proposm/$file' ><img style = 'width: 30px;' src='../img/accept_page.png'  title='اسکن' ></a></td>";
												}
										}
									}
								}
					 
                      if ($resquery['isbandp']>0) 
					  $isbandhide='display:none';
                      if ($done>0)
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >پیشنهاددهنده</th>
                            <th colspan=\"4\" class=\"f10_fontb\" >مجوز </th>
                            <th colspan=\"2\" class=\"f10_fontb\" >مبلغ طرح</th>
							<th colspan=\"3\" class=\"f10_fontb\" >پيشنهاد قيمت  </th>
                            <th class=\"f10_fontb\"  style=$isbandhide >دلايل</th>
                            <th class=\"f10_fontb\" >اسکن فرم</th>
                        </tr>
		
					 <tr>
                          <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >شركت مجري</th>
                            <th class=\"f10_fontb\" >پایه</th>
                            <th class=\"f10_fontb\" >سطح هر پروژه</th>
                            <th class=\"f10_fontb\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_fontb\" >مجموع سطح در سال</th>
                            <th class=\"f10_fontb\" >تامین اعتبار</th>
                            <th class=\"f10_fontb\" >مبلغ </th>
                            <th class=\"f10_fontb\" > اجراي پروژه</th>
                            <th class=\"f10_fontb\" >تاریخ</th>
                            <th class=\"f10_fontb\" >آدرس</th>
                            <th class=\"f10_fontb\" style=$isbandhide >عدم صلاحیت</th>
                            <th class=\"f10_fontb\" >پيشنهاد قيمت</th>
                        </tr>";
                     else {
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >پیشنهاددهنده</th>
                            <th colspan=\"4\" class=\"f10_fontb\" >مجوز </th>
                            <th colspan=\"2\" class=\"f10_fontb\" >پروژه در دست اجرا</th>
                            <th colspan=\"2\" class=\"f10_fontb\" >اعتبار اجرایی طرح</th>
							<th colspan=\"3\" class=\"f10_fontb\" >پيشنهاد قيمت 
							";
							if ($login_RolesID==18 || $login_RolesID==27 || $login_designerCO==1 || ($login_RolesID=='17' && $spDesignArea<=10.9))
							{
							echo"<br> مجوز نمایش عدم صلاحیتها 
							<input name='showerrnazer' type='checkbox' id='showerrnazer' onChange='selectpagenazer()' value='$showerrnazer'";if ($errType==5) echo "checked";
							echo "/><input name='showerrnazer' type='hidden' class='textbox' id='showerrnazer'  value='$showerrnazer'  />";
							}
							echo"
							</th>
                            <th class=\"f10_fontb\" style=$isbandhide >دلايل</th>
                            <th class=\"f10_fontb\" >اسکن فرم</th>
                        </tr>
		
					 <tr>
                          <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >شركت مجري</th>
                            <th class=\"f10_fontb\" >پایه</th>
                            <th class=\"f10_fontb\" >سطح هر پروژه</th>
                            <th class=\"f10_fontb\" >حداکثر تعداد پروژه همزمان*</th>
                            <th class=\"f10_fontb\" >مجموع سطح در سال</th>
                            <th class=\"f10_fontb\" >تعداد</th>
                            <th class=\"f10_fontb\" >مساحت</th>
                            <th class=\"f10_fontb\" >تامین اعتبار</th>
                            <th class=\"f10_fontb\" >مبلغ </th>
                            <th class=\"f10_fontb\" > اجراي پروژه</th>
                            <th class=\"f10_fontb\" >تاریخ</th>
                            <th class=\"f10_fontb\" >آدرس</th>
                            <th class=\"f10_fontb\" style=$isbandhide>عدم صلاحیت</th>
                            <th class=\"f10_fontb\" >پيشنهاد قيمت</th>
                        </tr>";
                     
                     
                     }
                    
                    $Total=0;
                    $rown=0;
					
                    $Description="بنام خدا :
با توجه به درخواست متقاضی ...................... ثبت شده به شماره ............ مورخ .......... ایشان که شرکت ................... را به عنوان شرکت مجری پروژه ..... هکتاری خود انتخاب و معرفی نموده و شرکت ................. (ردیف ...... استعلام ) متعهد میباشد که بر اساس پایین ترین قیمت تایید شده استعلام فوق، برابر فهرست بهای سال .... با (...... درصد مینوس ) طرح مذکور را بطور کامل اجرا نماید.";


			 
                    
                    if ($Datebandp>0)
                        $datetoprint=gregorian_to_jalali($Datebandp);
                    else
                        $datetoprint=gregorian_to_jalali(date('Y-m-d'));
        
                            
                    while($resquery = mysql_fetch_assoc($result))
                    {
                        
						
						$linearray = explode('_',$resquery["Codeop"]); 
						$login_Codeop1=$linearray[0];
						$login_Codeop2=$linearray[1];
						$login_Codeop3=$linearray[2];
    
						
						$fbtxt=$resquery['costyear'];
                        if ($resquery['state']>0)
                        if ($resquery['Description']!='')
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
                        $errorsa="";
                         
                        $hmmp5=$Permissionvals['hmmp5'];
                        $tmtb50hp5=$Permissionvals['tmtb50hp5'];
                        if (gregorian_to_jalali($resquery["operatorapprequestSaveDate"])<='1396/05/14')
                            {
                                $hmmp5=$Permissionvals['hmmp5']*2;
								//print gregorian_to_jalali($resquery["operatorapprequestSaveDate"]);
                                $tmtb50hp5=1;
                            }  
                        
                            /*$joinyearlow=0;
                            $date = new DateTime(jalali_to_gregorian($resquery["joinyear"]));
                            $date->modify('+720 day');
                            //$date->add(new DateInterval('P2Y'));
                            if ($date->format('Y-m-d')>date('Y-m-d'))
                                $joinyearlow=1;
                                */
								
						if ($Permissionvals['proposepermissionless']==0)
                        if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d'))
								||
							$login_Codeop3==1
							)
                            $errorsa.="<br>*اعتبار مجوز شرکت منقضی شده است.";
							
						 
						if ($Permissionvals['proposecoless']==0)
                        if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errorsa.="<br>* اعتبار هیئت مدیره منقضی شده است.";
					 
						if ($Permissionvals['proposedesignerless']==0)
						{
							if (!($resquery["designercnt"]>=1))
								$errorsa.="<br>*شرکت فاقد کارشناس فنی است.";
							if (($resquery["duplicatedesigner"]>=1))
								$errorsa.="<br>*کارشناس فنی این شرکت در بیش از یک شرکت شاغل می باشد.";
						}
						
						if ($resquery["StarCo"]==1)
                            $errorsa="<br>*شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت نمي باشد."; 
                        else if ($resquery["ent_Num"]>0 && compelete_date($resquery["ent_DateTo"])>=gregorian_to_jalali(date('Y-m-d')) )
                                {
                                    if (($resquery["DesignArea"]>=$resquery["ent_Hectar"])||
                                     ($resquery["simultaneouscnt"]>=$resquery["ent_Num"])  )
                                    {
                                        $errorsa.="<br> شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت انتظامی میباشد .";
                                    }
                                }
    					
					   $errors="";
                   	
					//if (!$cappacityless>0)
                
						if ($Permissionvals['proposeprojectlessCnt']==0)
                        {
						
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
							
                            if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) 
                            && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp5']))  
								$errors.="<br>*تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
								//$errors.="<br>".$resquery["above20cnt"];
								/*
									if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['smallapplicantsize']) && ($resquery["above20max"]>$Permissionvals['smallapplicantsize']) && (($resquery["above20max"]+$resquery["DesignArea"])>$Permissionvals['hmmp5'])) 
									$errors.="<br>مجموع دو طرح بزرگ، بیش تر از سقف مجاز می باشد ";  
                                */
								//print $resquery["corank"]."-".$resquery["DesignArea"]."-".$resquery["above55cnt"]."-".$Permissionvals['tmtb55hp5'];
						}		
						
					    if ($Permissionvals['proposeprojectlessHa']==0)
                        {			
							if (!($resquery["proposelimitless"]>0))
                            {
                                /*if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$maxAreacorank5*$zarib5) && ($resquery["above55cnt"]>=$tmtb50hp5))  
								$errors.="<br>*تعداد مجاز طرح های بالای 50 هکتار پایه $resquery[corank] بیشتر از حد مجاز می باشد."
                                ."$resquery[above55cnt] $tmtb50hp5"; 
                                */
                                
                                if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$Permissionvals['hmmp1']))  
    								$errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
    							if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$Permissionvals['hmmp2']))  
    								$errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
    							if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$Permissionvals['hmmp3']))  
    								$errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
    							if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$Permissionvals['hmmp4']))  
									$errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
    					            
                                if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$hmmp5*$zarib5))  
								$errors.="<br>*مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
								
                                if ($resquery["projecthektardone"]>100)
								$Max=$hmmp5*$zarib5;
						     	else if ($resquery["projecthektardone"]>50 && ($resquery["MaxDone"]>55))    
								$Max=$resquery["MaxDone"];
							    else $Max=55;    
						
					     		if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Max))  
									$errors.="<br>*شرکت طبق مصوبه کمیته فنی آب و خاک  فاقد سابقه کار مورد نیاز می باشد.";  
                            }
                            	
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
    						
                            if (($resquery["corank"]==4)&& ($resquery["DesignArea"]>(($Permissionvals['hmmp4']/(1+$Permissionvals['percentapplicantsize4']/100))*1.1) )
                            && ($resquery["above20cnt"]>=($Permissionvals['tmtb10hp4']-1)))
                            $errors.="<br>*ظرفیت کافی برای این طرح بزرگتر از حد مجاز وجود ندارد."; 
                            
							/*    if (($resquery["corank"]==3)&& ($resquery["DesignArea"]>(($Permissionvals['hmmp3']/(1+$Permissionvals['percentapplicantsize3']/100))*1.1) )
									&& ($resquery["above20cnt"]>=($Permissionvals['tmtb10hp3']-1)))
									$errors.="<br>*ظرفیت کافی برای این طرح بزرگتر از حد مجاز وجود ندارد."; 
							*/  
						
							//if (($resquery["corank"]==5) && ($resquery["DesignArea"]>55) && ($joinyearlow==1))  
							//        $errors.="<br>*شرکت سابقه کافی جهت پیشنهاد قیمت این طرح را دارا نمی باشد.";  
					
							if ($Permissionvals['proposedamane']==0)
								{    
								  // print $tend;
								   $errD=0;
								   if ((floor(100*$resquery["price"]/$Po*10)/10)<$C1 || (floor(100*$resquery["price"]/$Po*10)/10)>$C2)
									{if ($tend>'2015-12-22')	$errors.="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.";$errD=1;}
								}						
							if ($errD==1 && $errors=="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.") $cl='ff6666';
						}						
					
				
			
					$caperr=0;$fcl="";
					if ($cappacityless==1 && strlen($errorsa)=="" && strlen($errors)!="")  
							$errors.="<br>*پس از تایید به عنوان طرح خارج از ظرفیت در نظر گرفته می شود";
					
					$errors.=$errorsa;
				
					if (strlen($errors)>0 && !($Datebandp>0) && ($apps!=1) )  $cl='ff0000';
					if (strlen($errors)>0) $cl='ff0000'; else $cl='000000';
					
					if ($cappacityless==1 && strlen($errorsa)=="")  
						{$fcl="color='gray'";$cl='000000';$caperr=1;}
				
				
                     	
                        if ($done>0)
                            {
								$errors=$resquery['errors'] ; 
								$linearray = explode('_',$errors);
								if ($login_designerCO==1) 
								$printerrors=$errors;
								else
								$printerrors=$linearray[0];
						 
							if (strlen($errors)>0 && $cappacityless!=1)   $cl='ff0000'; else {$cl='000000';$fcl="color='gray'";}
							}   
                        if ($resquery['isbandp']>0)
                            $errors='';
                    
                    
                        if (!($showm==0) && !($done>0) && strlen($errors)>0 && !($Datebandp>0)) 
							$htype='style="display:none"'; 
						else 
							$htype='';
                            $rown++;
							
							if (strlen($errors)>0) $rowerrors++;
							
                            if ($rown%2==1) 
                            $b=''; else $b='b';
                        
                             echo "<tr  $htype ><td class='data'   $htype ><input name='operatorapprequestID$rown' type='hidden' class='textbox' id='operatorapprequestID$rown' 
                              value='$resquery[operatorapprequestID]'  /></td>";
							  
                              if ($done>0)
                              {
							   
						        if ($resquery["operatorapprequeststate"]>0) 
									{
									$ecept=$resquery['ecept'];
									if ($fstrm)
										echo $fstrm;
									else
										echo "<td class='f10_font$b'  colspan='1' $htype><img style = 'width:30px;' src='../img/accept.png' title=''></td>";
									}
                                else echo "<td class='f10_font$b'  colspan='1' $htype></td>"; 
                              }
                              else echo "<td class='f10_font$b'  colspan='1' $htype><input 
							  onChange='changeradio(
							  \"$resquery[operatorcoTitle]\",
							  \"$resquery[operatorcoID]\",
							  \"$resquery[pcoef1]\",\"$resquery[pcoef2]\",\"$resquery[pcoef3]\",\"$resquery[price]\",\"".(floor($resquery["LastFehrestbaha"]/100000)/10)."\",\"$errors\",
							   \"$resquery[simultaneouscnt]\",
							  \"$resquery[simultaneoushectar]\",
							  \"$resquery[above20cnt]\",
							  \"$resquery[above55cnt]\",
							 \"$resquery[thisyearprgarea]\",
							 \"$resquery[projecthektardone]\",
							 \"$resquery[projectcountdone]\",
							 \"$resquery[corank]\",
							 \"$caperr\"
							 
							  )'  type='radio' name='rgs' value='$resquery[operatorapprequestID]'  /></td>";
                              
							  
?>                     
                            
                            
                            <td <?php print $htype; ?> class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';">
							<?php 
							echo "<a target='".$target."' href='allapplicantrequest.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['operatorcoID']."_1_".$login_ostanId.rand(10000,99999).
                                    "'><font color=\"$cl\">". $resquery['operatorcoTitle']; ?></font></td>
                            <td <?php print $htype; ?> class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["corank"] ; ?></td>
                            <td <?php print $htype; ?> class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php  
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmp4'] ;
                            else if ($resquery["corank"]==5) echo $hmmp5 ;
                            
                            ?></td>
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $Permissionvals['tmphtp'];  ?></td>
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  colspan="1" style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                            if ($resquery["corank"]==1) echo $Permissionvals['hmmsmp1'] ; 
                            else if ($resquery["corank"]==2) echo $Permissionvals['hmmsmp2'] ;
                            else if ($resquery["corank"]==3) echo $Permissionvals['hmmsmp3'] ;
                            else if ($resquery["corank"]==4) echo $Permissionvals['hmmsmp4'] ;
                            else if ($resquery["corank"]==5) echo $Permissionvals['hmmsmp5'] ;
                            echo "</td>";
                            if (!($done>0))
                            echo "<td $htype  class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[simultaneouscnt]</td>
                            <td  $htype  class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".(floor($resquery["simultaneoushectar"]*10)/10)."</td>
                            ";
                            
                              ?>
                            
                            
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["letterdate"]; ?></td>
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastFehrestbaha"]/100000)/10; ?></td>
                            <!--
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef1"],2); ?></td>
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef2"],2); ?></td>
                            <td class="f10_font<?php //echo $b; ?>"  style="color:#<?php //echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php //echo round($resquery["pcoef3"],2); ?></td>
                            !-->
                            
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["price"]*10)/10; ?></td>
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo gregorian_to_jalali($resquery["operatorapprequestSaveDate"]); ?></td>
                            <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["CoAddress"] ?></td>
                     
                     <?php 
                     
                        echo "<td  $htype class=\"f10_font$b\"  style=$isbandhide \"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><font $fcl>".substr($printerrors,4)."</font></td>";
                     
                     ?>
                        <td <?php print $htype; ?>  class="f10_font<?php echo $b; ?>"  ><?php echo $fstr1; ?></td>
                        <?php
                                if ($done!=1)
                            if ($login_RolesID==18 || $login_RolesID==27 || $login_designerCO==1 || ($login_RolesID=='17' && $spDesignArea<=$maxAreasmalls))
                            echo "
                        <td  $htype ><a 
                            href=\"allapplicantrequestdetail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery['operatorapprequestID'].rand(10000,99999)."\"
                            onClick=\"return confirm('مطمئن هستید که  پیشنهاد حذف شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a></td>";
                            else
                            echo "
                        <td  $htype ><a href='' > <img style = 'width: 25px;' src='../img/delete.png' title='حذف  پیشنهاد توسط مدیر آب و خاک'> </a></td>";
                            
                             
                            echo "
                              <td   $htype  class='data'><input name='errors$rown' type='hidden' class='textbox' id='errors$rown'  
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
                         
                            $approvedcoef=round($resquery['approvedcoef'],3);   
                            $selop= $resquery["operatorcoTitle"];
                            $selopID= $resquery["operatorcoID"];
                            $selsimcnt= $resquery["simultaneouscnt"];
                            $selsimhektar= $resquery["simultaneoushectar"];
                            $selabove20cnt=$resquery["above20cnt"];
							$selabove55cnt=$resquery["above55cnt"];
							$selthisyearprgarea=$resquery["thisyearprgarea"];
							$selprojecthektardone=$resquery["projecthektardone"];
							$selprojectcountdone=$resquery["projectcountdone"];
							$selcorank=$resquery["corank"];
							
                            
							
							$selbase= floor($resquery["LastFehrestbaha"]/100000)/10;
                            $selc1= floor($resquery["pcoef1"]*100)/100;
                            $selc2= floor($resquery["pcoef2"]*100)/100;
                            //$seltotal= floor($resquery["LastFehrestbaha"]/100000)/10*$resquery["pcoef1"]*$resquery["pcoef2"]*$approvedcoef*10)/10;
                            $seltotal= $resquery["apval"];
                            
                            if ($resquery["Windate"]!="")
                            $datetoprint=gregorian_to_jalali($resquery["Windate"]);
                            $winerrors=$resquery['errors'] ;
                            
                        }
                       
                        
                    }
                    echo "<tr><td $htype  colspan='18'>&nbsp;</td> </tr>";
                    
                     
                    
                    
                    
			if ($login_RolesID!=17 && $login_RolesID!=18 && $login_RolesID!=27 && $login_designerCO!=1 && $errType!=5 )
			if (($rown-$rowerrors)>2 && ($rown-$rowerrors)>$Permissionvals['proposenumcnt'])				
				$showerr=1; else $showerr=0;
              echo "<td colspan='18' class='data'><input name='showerr' type='hidden' class='textbox' id='showerr'  value='$showerr'  /></td>";
				
                if ($done>0 && strlen($winerrors)>0)
                $valve='*پروژه جهت تکمیل فرایند نیازمند مجوز مدیر آب و خاک می باشد.';
                else
                $valve='';
				
	
	              echo " 
                    
              <tr>
                    <td colspan='4' class='label'>پیشنهاد تعدیل شده با ضرایب روند:</td>
                    <td colspan='3' class='data'>
                      <input  name='selop' type='text' class='textbox'  id='selop'  style='width:160px'  readonly  value='$selop' />
					</td>
					
			  <td> 
			  <input type='hidden' name='selopID' type='text' class='textbox' id='selopID' style='width:20px' value='$selopID' /> 
			  <input type='hidden' name='selsimcnt' type='text' class='textbox' id='selsimcnt' style='width:20px' value='$selsimcnt' /> 
			  <input type='hidden' name='selsimhektar' type='text' class='textbox' id='selsimhektar' style='width:50px' value='$selsimhektar' />
			  <input type='hidden' name='selabove20cnt' type='text' class='textbox' id='selabove20cnt' style='width:50px' value='$selabove20cnt' /> 
			  <input type='hidden' name='selabove55cnt' type='text' class='textbox' id='selabove55cnt' style='width:20px' value='$selabove55cnt' /> 
			  <input type='hidden' name='selthisyearprgarea' type='text' class='textbox' id='selthisyearprgarea' style='width:20px' value='$selthisyearprgarea' /> 
			  <input type='hidden' name='selprojecthektardone' type='text' class='textbox' id='selprojecthektardone' style='width:20px' value='$selprojecthektardone' /> 
			  <input type='hidden' name='selprojectcountdone' type='text' class='textbox' id='selprojectcountdone' style='width:20px' value='$selprojectcountdone' /> 
			  <input type='hidden' name='selcorank' type='text' class='textbox' id='selcorank' style='width:20px' value='$selcorank' /> 
			  <input type='hidden' name='fbtxt' type='text' class='textbox' id='fbtxt' style='width:20px' value='$fbtxt' /> 
			  
			    
							
			  </td>
							
  
                      <td class='data'> 
                      <input name='selbase' type='text' class='textbox' id='selbase' style='width:50px; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                        value='$selbase' /></td>
                      
          
 
                      <td class='data'>
                      <input name='selc1' type='text' class='textbox' id='selc1' style='width:40px; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                        value='$selc1'/></td>
                      
                    
            
  
                      <td class='data' >
                      <input name='selc2' type='text' class='textbox' id='selc2' style='width:40px; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       value='$selc2' /></td>
                      
   
                      <td  class='data'>
                      <input  name='approvedcoef' type='text' class='textbox' id='approvedcoef' style='width:40px ; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' readonly
                       maxlength='5' value='$approvedcoef'/></td>
                      
                    
           
   
                      
                      <input onChange='calc();Descrip();' name='seltotal' type='text' class='textbox' id='seltotal' style='visibility: hidden;width:60px; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' 
                        value='$seltotal'/></td>
                      
                    
				   </tr>
				   
				   
				    </tr>
					         <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
							قیمت های پیشنهادی فوق قطعی نبوده و پس از اعلام منتخب و بررسی پیش فاکتور های لوازم پروژه، قیمت نهایی فهرست بهای اجرایی بر حسب حجم عملیات اجرایی محاسبه خواهد گردید.
							</span>  </td>
                   </tr>
                    <tr> 
                    <td colspan='1'></td>
                        <td colspan='8'>* 
                            <span style = \";text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            حداکثر تعداد پروژه های همزمان با احتساب پروژه های کوچک می باشد .
                            </span>  
                            </td><td colspan='9' >
                            <input size=50  name='msgerror' type='text' class='textbox'  id='msgerror' 
                                value='$valve'
                            style = \";border:0px;text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\"
                            
                               
                        value='' />
                        
                        
                        
                        </td>
                   </tr>
                   
                   
                    <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            حداکثر سطح هر پروژه مطابق با مصوبه کمیته فنی مدیریت آب و خاک در نظر گرفته شده است.
                            </span>  </td>
                   </tr>
                   <tr> 
                  <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                             ضریب پیشنهادی مطابق با مصوبه کمیته فنی و با تقسیم مبلغ پیشنهادی بر مبلغ پایه فهرست بهای طرح و ضرایب 1.3 و 1.05 محاسبه می گردد.
                            </span>  </td>
                   </tr>
                   <tr> 
                    <td colspan='1'></td>
                        <td colspan='18'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            تعداد کل پیشنهادها ".mysql_num_rows($result)." مورد می باشد.
                            </span>  </td>
                   </tr>
                   
				   
                   <tr>
				    <td colspan='18' class='data'><input name='below3' type='hidden' class='textbox' id='below3'  value='$below3'  /></td>
			
					   
			     </tr>	   
			
			
			              ";
						 
					if ($done>0 && strlen($winerrors)>0 && ($login_RolesID==18 || $login_RolesID==27 || $login_RolesID==32))
                    {
                        $linearray = explode('_',$winerrors);
                        $winerrors=$linearray[0];
                        if ($linearray[3]!="")
                        $Description=$linearray[3];
                        else
                        $Description="با عنایت به نامه شماره .............. تاریخ ............... مدیریت جهاد شهرستان $shahrcityname ، مجوز انتخاب شرکت پیمانکار داده می شود.";
                        
                        $Description=str_replace('<br>', '', $Description);
	                    
                     echo "
                     
                       <tr>
                               <td colspan='3'  class='label'>توضیحات:</td>				   
            			    
                                
                                  <td colspan='12' width='80%' class='data'>
                                  <textarea id='Description' name='Description' rows='6' cols='100'>$Description </textarea>
								  $fstrm2
								  </td>
                                <td class='data'><input name='winerrors' type='hidden' class='textbox' id='winerrors'  
                            value='$winerrors'  /></td>
                           
                        </tr> 
                     ";
					 if ($ecept!=1)
					 echo "
						<tr> 
                          <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  
                            value='$ApplicantMasterID'  /></td>
                            <td colspan='17'>
                          <input name='tempsubmitexcept' type='submit' class='button' id='tempsubmitexcept' 
						  onClick=\"return confirm('آیا از اعطاء مجوز مطمئنید؟ ')\"
						  
						  value='اعطاء مجوز مدیر آب و خاک'/></td>
                         </tr>
                             ";
					else
					 echo "
						<tr> 
	                      <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  
                            value='$ApplicantMasterID'  /></td>
							<td colspan=11></td>
							<td colspan=3>
							<input name='tempsubmitexcept' type='submit' class='button' id='tempsubmitexcept'
							 onClick=\"return confirm('آیا از اعطاء مجوز مطمئنید؟ ')\"
							value='ویرایش مجوز'/>
						   </td>
                         </tr>
                             ";
					echo "<td colspan='17' style = \"text-align:left;font-size:12;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">كاربر: $login_fullname</td>    ";
 	 
							 
                        exit;
                        
                    }
                   	  
			            
                        /*if ($done>0 &&  ($login_RolesID==1 || $login_RolesID==18 || $login_RolesID==27))
                        {
                            echo "
                            <tr>
                            <td colspan='1'></td>
                              <td class='data'><input name='ncoef3' type='text' class='textbox' id='ncoef3' size=5  value='$ncoef3'  /></td>
                              <td colspan='3' class='data'><input type='file' name='file1' id='file1' style='width: 100px'></td>
                                <td colspan='10'>
                              <input name='tempsubmitchangecoef' type='submit' class='button' id='tempsubmitchangecoef' value='تصحیح ضریب منتخب پیشنهاد '/></td>  
                            </tr>";
                            
                            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/propose';
                            $handler = opendir($directory);
                            while ($file = readdir($handler)) 
                            {
                                // if file isn't this directory or its parent, add it to the results
                                if ($file != "." && $file != "..") 
                                {
                                    
                                    $linearray = explode('_',$file);
                                    $ID=$linearray[1];
                                    $No=$linearray[0];
                                    if (($ID==$ApplicantMasterID) && ($No==-1) )
                                        echo "<td><a href='../../upfolder/propose/$file' ><img style = 'width: 30%;' src='../img/accept.png' title='مصوبه' ></a></td>
                                        <td colspan='3'><font color='green' size='2'>مصوبه تصحیح قیمت.</font></td>
                                        ";
                                           
                                    
                                }
                            }
                            
                        }*/
					
		
					if (($login_RolesID==18 && $proposestate!=1) && $proposestate<=2)
                    {
                        if ($proposestate==1 || $proposestate==2)
                            $btntitle="برگشت به وضعیت دریافت پیشنهاد";
                        else if ($proposestate==0) 
                            $btntitle="ارجاع به مدیر آبیاری";    
                        echo "<tr> 
                              <td colspan='18'><input name='tempsubmit1' type='submit' class='button' id='tempsubmit1' value='$btntitle'/></td>
							</tr> ";
                    }
                    else if (($login_RolesID==13 || $login_RolesID==27 ) && $proposestate!=2)
                    {
                        $btntitle="ارجاع به ناظرین";
						echo "<tr> 
							<td colspan='18'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
							</tr> ";
                    }   
			        else if ($login_RolesID=='17' && $spDesignArea<11) 
                    {
					 if ($proposestate==1 || $proposestate==2){$btntitle="برگشت به وضعیت دریافت پیشنهاد";
							echo "<tr> 
                              <td colspan='18'><input name='tempsubmit1' type='submit' class='button' id='tempsubmit1' value='$btntitle'/></td>
							  </tr><tr> <td colspan='18'>&nbsp</td></tr> ";}
						 if ($proposestate!=2 && $proposestate!=3) {$btntitle="اتمام پیشنهاد قیمت";
							echo "<tr> 
							<td colspan='18'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
							</tr> ";}
				    }   
					
                    if ($proposestate==1 && ($login_RolesID==18 || $login_RolesID==1))
                        {
                        $btntitle="ارجاع به ناظرین";
                            echo "<tr> 
                            <td colspan='17'></td>
                              <td colspan='1'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
                              
                            </tr> ";
                        }
                        
				    if ((!($done>0) && $login_RolesID==18 ) || $proposestate==1 || $proposestate==0)
                    echo "";
                    else                 
                       echo " 	
                              <tr>
            				    <td colspan='3'  class='label'>مبلغ تایید شده:</td>
                                   <td colspan='10' class='data'>
                                  <input onChange='tempvalchange();' name='seltotaltemp' value='$seltotal'
                                  type='text' class='textbox' pattern='[0-9.]{2,}'
                                  id='seltotaltemp' style='background-color:#ffff00 ;width:60px; text-align:center;font-size:10pt;line-height:100%;font-weight: bold; font-family:B Nazanin' 
                                    /></td>  
            				 </tr>	   
                              <tr>
            					   <td colspan='3'  class='label'>تاریخ:</td>
                                   <td colspan='10' class='data'>
                                  <input  value='$datetoprint' readonly
                                  id='seltotaltemp' style='width:65px; text-align:center;font-size:10pt;line-height:150%;font-weight: bold; font-family:B Nazanin' 
                                    /></td>  
            				 </tr>
                               <tr>
                               <td colspan='3'  class='label'>توضیحات:</td>				   
                             	<td colspan='1' />
                                  <td colspan='12' width='80%' class='data'>
                                  <textarea id='Description' name='Description' rows='6' cols='120'>".$Description."</textarea>
								
								  </td>";
								  
                   
                   
                        if (!($done>0))
                        {
                            
                            echo " <tr><td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  
                            value='$ApplicantMasterID'  /></td>
                            <td class='data'><input name='proposestate' type='hidden' class='textbox' id='proposestate'  
                            value='$proposestate'  /></td>";
                            
                            if ($login_RolesID==17)
                            $toletter="مدیریت محترم جهاد کشاورزی شهرستان ".$shahrcityname;
                            else
                            $toletter="مدیریت محترم آب و خاک و امور فنی و مهندسی";

                        if ($login_RolesID!=18 && $proposestate==2)
                        echo " 
                        <td colspan='3'><input name='submit' type='submit' class='button' id='submit' value='ثبت منتخب پیشنهاد' /></td>
						 <td colspan='8' ><td colspan='9' style = \"text-align:left;font-size:20;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">كاربر: $login_fullname</td></tr>
                         <tr><td colspan='1'><td colspan='18' 
                         style = \"text-align:right;font-size:16;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
                         <br>&nbsp;
                         $toletter      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                         <br>با سلام و احترام
                         <br>بدینوسیله اینجانب/شرکت « $Appname » متقاضی اجرای طرح آبياري قطره‌اي/باراني/كم فشار در سطح $spDesignArea هکتار  پس از بررسی های لازم مجری پروژه خود را شرکت ....................................
                         انتخاب نموده ام و شرکت ............................................. متعهد گردیده است که اجرای پروژه اینجانب را با پایین ترین مبلغ تایید شده در جدول فوق اجرا نماید.
                         خواهشمند است اقدام لازم مبذول فرمایند.
                         <br>&nbsp;
                         <br>&nbsp;
                         تبصره: متقاضی متعهد می گردد از تاریخ $datetoprint حداکثر ظرف مدت 7 روز نسبت به مشخص نمودن پیمانکار (مجری) پروژه خود از بین شرکتهای فوق با حداقل قیمت پیشنهادی اقدام و نتیجه را کتبا به مدیریت آب و خاک و امور فنی مهندسی اطلاع رسانی نماید.
                         <br>&nbsp;
					     <br>&nbsp;
                         <br>&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         مهر و امضاء مدیرعامل شرکت ...........................    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    امضاء متقاضی $Appname
                         <br>&nbsp;
                         <br>&nbsp;
                         <br>&nbsp;
                         </td> </tr>
                         ";   
                        }
                         else echo 
                         "
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='1'><td colspan='18' class='f14_fontb'>منتخب پیشنهاد قیمت برای طرح $ApplicantName شرکت $operatorcoTitle می باشد.</td> </tr>
                     <tr >
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                           
                              <td colspan='1'></td>
                                    <td colspan='7'  > امضاء&nbspپیمانکار <br>شرکت $operatorcoTitle</td> 
                                    <td  colspan='4' > امضاء&nbspمتقاضی <br>$Appname</td> 
                                    <td  colspan='6' > امضاء&nbspناظرین <br>$spname</td> 
                               
                                </tr>
                                
                                <tr >  
                              <td colspan='1'></td>
                              
                                    <td colspan='6' >&nbsp  </td> 
                                    <td colspan='8' > </td> 
                                    <td colspan='4' >&nbsp</td> 
                                    
                                </tr>    
				    
                   ";
                   
                         
?>

                   
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
