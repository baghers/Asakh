<?php 

/*

//insert/apprequest.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

-

*/


include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php'); 

//$login_ostanId شناسه استان
//تابع دریافت مشخصات پیکربندی
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);


$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$operatorcoID=$linearray[0];//شناسه پیمانکار
if ($login_isfulloption==1) $showkejra=$linearray[1];//مجوز نمایش طرح ها

automated_propose_transfer();//تابع انتقال پیشنهادات قیمت بعد از تاریخ و تعداد مورد نیاز

if ($login_RolesID==1)//نقش مدیر پیگیری 
{
    $str="";
    $selectedCityId=$login_CityId;//شناسه شهر
    if ($_POST['ostan']>0)  $selectedCityId=$_POST['ostan'];//شناسه استان
    if (strlen(trim($_POST['ostan']))>0) $str.="and substring(applicantmasterop.cityid,1,2)=substring('$_POST[ostan]',1,2)";//محدودیت استان در پرس و جو
}	
else 
{
	$str="and applicantmasterop.operatorcoid='$login_OperatorCoID'";//محدودیت شناسه پیمانکار
	$str.="and substring(applicantmasterop.cityid,1,2)=substring('$login_CityId',1,2) ";//افزودن محدودیت شهر به پرس و جو
}


if (!$_POST)
{
/*
    applicantmaster جدول مشخصات طرح
    ApplicantMasterID شناسه طرح مطالعاتی
    operatorcoID شناسه پیمانکار
    operatorapprequest جدول پیشنهادات قیمت
    state انتخاب شدن یا نشدن
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterIDmaster شناسه طرح اجرایی
    cityid شناسه شهر طرح
    proposestate وضعیت پیشنهاد قیمت
    applicantstatesID شناسه وضعیت طرح
    $login_OperatorCoID شناسه پیمانکار لاگین شده
    $login_CityId شناسه شهر کاربر لاگین شده
*/
		$query="
		SELECT count(*) cnt
		FROM applicantmaster 
		left outer join (select ApplicantMasterID,operatorcoID from operatorapprequest where state=1) reqwin on reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID 
		inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID 
		left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster 
		where 
		substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2) and 
		case ifnull(applicantmaster.proposestate,0) 
		  when 3 then 
				case applicantmasterop.applicantstatesID 
				  when 38 then 'تحویل موقت' 
				  when 30 then 'تایید پیش فاکتورها' 
				  when 35 then 'آزادسازی ظرفیت' 
				else 
				concat(case ifnull(applicantmasterop.ApplicantMasterID,0) when 0 then '*' else '' end,'تایید پیشنهاد') end end='*تایید پیشنهاد' and 
		reqwin.operatorcoID='$login_OperatorCoID'";
      $result = mysql_query($query);  
      $row = mysql_fetch_assoc($result); 
	  
        if ($row['cnt']>0)//تعداد طرح هایی که پیمانکار  لاگین شده آنها را برنده شده ولی هنوز اقدام به ثبت آنها ننموده است
        {
            echo"<script>alert('کاربر محترم، \\n لطفا مشخصات اولیه طرحهای جدید برنده پیشنهاد قیمت شده را ثبت نمایید. با تشکر')</script>";
            //Permissionvals['permitNotInvoice'] تعداد مجاز طرح هایی که پیمانکاران می توانند ثبت نکرده داشته باشند
            if ($row['cnt']>$Permissionvals['permitNotInvoice'])
                echo"<script>window.location='applicant_list.php'</script>";
        }
}

$maxAreasmalls=10.9;//حداکثر مساحت طرح های کوچک قابل انتخاب
$maxAreasmall=14.9;//حداکثر مساحت طرح های جزء ظرفیت
$zarib5=(100+$Permissionvals['hmmp5zarib'])/100;//ضریب پنجم پیشنهاد قیمت
$maxAreacorank5 = $Permissionvals['hmmp5']* $zarib5;//حداکثر مساحت قابل اجرای پایه 5

//$login_OperatorCoID شناسه پیمانکار لاگین شده
//$login_DesignerCoID شناسه مشاور طراح لاگین شده
//---------------------------------------------------

if ($login_Permission_granted==0) header("Location: ../login.php");


//مشاهده و ارسال پیشنهاد قیمت از ساعت 13 تا 9 صبح فعال می باشد و در این قسمت بررسی می شود که آیا در این فاصله زمانی هستیم یا خیر
//$early صفر در صورتی که در فاصله زمانی مجاز نباشیم و یک در صورتی که در فاصله زمانی مجاز باشیم
$early=0;
if (date('G', time())<13 && date('G', time())>9)
{
     $early=1;
}



if ($_POST)
{
    if ($login_RolesID==2)//نقش کاربر لاگین شده پیمانکار باشد
    {
        if (($_FILES["file1"]["size"] / 1024)>100)//بررسی اندازه اسکن پیشنهاد قیمت که بیشتر از 100 کیلوبایت نباشد
        {
            print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
        
        //پرس و جوی بررسی اینکه قبلا برای این طرح پیشنهاد داده شده است یا خیر
         $query = "SELECT count(*) cnt FROM operatorapprequest where ApplicantMasterID='$_POST[ApplicantMasterID]'
         and operatorcoID='$login_OperatorCoID'";
         try 
            {		
                $result = mysql_query($query);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
        $row = mysql_fetch_assoc($result);
        if ($row['cnt']>0)
        {
            print "قبلا برای این طرح پیشنهاد داده شده است";
            exit;
        }    
        
        $costprice = round(str_replace(',', '', $_POST['costprice']),1);//مبلغ برآورد مطالعات
        $costpricewithcoef = round(str_replace(',', '', $_POST['costpricewithcoef']),1);//مبلغ برآورد مطالعات با اعمال ضرایب
        
        $SaveTime=date('Y-m-d H:i:s');//زمان
        $SaveDate=date('Y-m-d');//تاریخ
        $ClerkID=$login_userid;//کاربر
        //$_POST['ApplicantMasterID'] شناسه طرحی که برای آن پیشنهاد داده شده
        //$login_OperatorCoID شناسه پیمانکار لاگین شده
        if ($_POST['ApplicantMasterID']>0 && $login_OperatorCoID>0)
        {
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
            $sql="INSERT INTO operatorapprequest(costyear,operatorcoID,ApplicantMasterID, 
            costprice,price,state,coef1,coef2,coef3,SaveTime,SaveDate,ClerkID)
                values ('$_POST[costyear]','$login_OperatorCoID','$_POST[ApplicantMasterID]','$costprice','$costpricewithcoef',0,'$_POST[coef1]','$_POST[coef2]','$_POST[coef3]', '$SaveTime','$SaveDate','$ClerkID');";
            try 
            {		
                mysql_query($sql);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
            
            $query = "SELECT operatorapprequestID,savedate FROM operatorapprequest where operatorapprequestID = last_insert_id() and SaveTime='$SaveTime' 
            and ClerkID='$ClerkID'";
            
            try 
            {		
                $result = mysql_query($query);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
    		
    		$row = mysql_fetch_assoc($result);
    		
            //بارگذاری اسکن فایل پیشنهاد قیمت
            if ($_FILES["file1"]["error"] > 0) 
            {
                echo "Error: " . $_FILES["file1"]["error"] . "<br>";
                exit;
            } 
            else 
            {
                $ext = end((explode(".", $_FILES["file1"]["name"])));
                $attachedfile=$row['operatorapprequestID'].'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/propose/" .$attachedfile);   
            }
            
            
            print "پیشنهاد قیمت با کد پیگیری ".
            $resquery["operatorapprequestID"].strtotime($SaveDate)
            ." با موفقیت ثبت شد.";
            
        }        
    }
    else 
    {
        $condition1="";//شرط محدودیت
            if (strlen(trim($_POST['operatorcoID']))>0)//شناسه پیمانکار
        $condition1.=" and operatorco.operatorcoID='$_POST[operatorcoID]'";
    if (strlen(trim($_POST['BankCode']))>0)//کد رهگیری
        $condition1.=" and applicantmaster.BankCode='$_POST[BankCode]'";
    if (strlen(trim($_POST['ApplicantName']))>0)//نام پروژه
        $condition1.=" and operatorapprequest.ApplicantMasterID='$_POST[ApplicantName]'";
    if (strlen(trim($_POST['City']))>0)//شهر پروژه
        $condition1.=" and shahr.id='$_POST[City]'";
    if (strlen(trim($_POST['Designer']))>0)//طراح پروژه
        $condition1.=" and designer.DesignerID='$_POST[Designer]'";
    
	
    //فیلتر طرح ها بر اساس بازه مساحت انتخابی
    if (strlen(trim($_POST['IDArea']))>0)
		if (trim($_POST['IDArea'])==1)
        $condition1.=" and applicantmaster.DesignArea>0 and applicantmaster.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $condition1.=" and applicantmaster.DesignArea>10 and applicantmaster.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $condition1.=" and applicantmaster.DesignArea>20 and applicantmaster.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $condition1.=" and applicantmaster.DesignArea>50 and applicantmaster.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $condition1.=" and applicantmaster.DesignArea>100 and applicantmaster.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $condition1.=" and applicantmaster.DesignArea>200 and applicantmaster.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $condition1.=" and applicantmaster.DesignArea>500 and applicantmaster.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $condition1.=" and applicantmaster.DesignArea>1000";
        
        //فیلتر طرح ها بر اساس بازه مبلغ برآوردی اجرا
        if (strlen(trim($_POST['IDcostprice']))>0)	
        if (trim($_POST['IDcostprice'])==1)
		$condition1.=" and operatorapprequest.costprice>0 and operatorapprequest.costprice<=100";
		else if (trim($_POST['IDcostprice'])==2)
		$condition1.=" and operatorapprequest.costprice>100 and operatorapprequest.costprice<=150";
		else if (trim($_POST['IDcostprice'])==3)
		$condition1.=" and operatorapprequest.costprice>150 and operatorapprequest.costprice<=200";
		else if (trim($_POST['IDcostprice'])==4)
		$condition1.=" and operatorapprequest.costprice>200 and operatorapprequest.costprice<=300";
		else if (trim($_POST['IDcostprice'])==5)
		$condition1.=" and operatorapprequest.costprice>300 and operatorapprequest.costprice<=500";
		else if (trim($_POST['IDcostprice'])==6)
		$condition1.=" and operatorapprequest.costprice>500 and operatorapprequest.costprice<=800";
		else if (trim($_POST['IDcostprice'])==7)
		$condition1.=" and operatorapprequest.costprice>800 and operatorapprequest.costprice<=1000";
		else if (trim($_POST['IDcostprice'])==8)
		$condition1.=" and operatorapprequest.costprice>1000";
        
        
        //فیلتر طرح ها بر اساس بازه مبلغ برآوردی کل پروژه   
        if (strlen(trim($_POST['IDprice']))>0)	
        if (trim($_POST['IDprice'])==1)
		$condition1.=" and operatorapprequest.price>0 and operatorapprequest.price<=100";
		else if (trim($_POST['IDprice'])==2)
		$condition1.=" and operatorapprequest.price>100 and operatorapprequest.price<=150";
		else if (trim($_POST['IDprice'])==3)
		$condition1.=" and operatorapprequest.price>150 and operatorapprequest.price<=200";
		else if (trim($_POST['IDprice'])==4)
		$condition1.=" and operatorapprequest.price>200 and operatorapprequest.price<=300";
		else if (trim($_POST['IDprice'])==5)
		$condition1.=" and operatorapprequest.price>300 and operatorapprequest.price<=500";
		else if (trim($_POST['IDprice'])==6)
		$condition1.=" and operatorapprequest.price>500 and operatorapprequest.price<=800";
		else if (trim($_POST['IDprice'])==7)
		$condition1.=" and operatorapprequest.price>800 and operatorapprequest.price<=1000";
		else if (trim($_POST['IDprice'])==8)
		$condition1.=" and operatorapprequest.price>1000";
        
        //فیلتر بر اساس بازه تاریخ پیشنهادی
        if (strlen($_POST['Datefrom'])>0)
        $condition1.=" and (date(operatorapprequest.SaveDate)>='".jalali_to_gregorian($_POST['Datefrom'])."')";
        if (strlen($_POST['Dateto'])>0)
        $condition1.=" and (date(operatorapprequest.SaveDate)<='".jalali_to_gregorian($_POST['Dateto'])."')";

        //فیلتر طرح ها بر اساس انتخاب شدن یا انتخاب نشدن پیمانکاران
        if (strlen(trim($_POST['IDwin']))>0)	
        if (trim($_POST['IDwin'])==0)
		$condition1.=" and applicantmaster.ApplicantMasterID NOT IN(select ApplicantMasterID from operatorapprequest operatorapprequestin
        where operatorapprequestin.ApplicantMasterID=operatorapprequest.ApplicantMasterID and operatorapprequestin.state=1)";
		else if (trim($_POST['IDwin'])==1)
		$condition1.=" and applicantmaster.ApplicantMasterID IN(select ApplicantMasterID from operatorapprequest operatorapprequestin
        where operatorapprequestin.ApplicantMasterID=operatorapprequest.ApplicantMasterID and operatorapprequestin.state=1) and operatorapprequest.state=0";
		else if (trim($_POST['IDwin'])==2)
		$condition1.=" and operatorapprequest.state='1'";
    }

}


/*
    $querys = "SELECT KeyStr,ValueInt FROM supervisorcoderrquirement WHERE KeyStr in ('tmtb10hp5','tmtb10hp4','tmtb10hp3','tmtb10hp2','tmtb10hp1'
    ,'tmtb50hp5','tmphtp','hmmp5','hmmp4','hmmp3','hmmp2','hmmp1','hmmsmp5','hmmsmp4','hmmsmp3','hmmsmp2','hmmsmp1','propose30daypermissionless',
    'proposedaycnt','smallapplicantsize','proposenumcnt','proposeautomat','percentapplicantsize','proposeprojectless','proposecoless')  and ostan='$login_ostanId' ";
    $results = mysql_query($querys);
    $Permissionvals=array();
    while ($rows = mysql_fetch_assoc($results))
    $Permissionvals[$rows['KeyStr']]=$rows['ValueInt'];
 */   
	    				
    
    $currentdatefrom=jalali_to_gregorian((substr(gregorian_to_jalali(date('Y-m-d')),0,4)-1)."/07/01");//از تاریخ  
    $currentdateto=jalali_to_gregorian(substr(gregorian_to_jalali(date('Y-m-d')),0,4)."/06/31");//تا تاریخ
    
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
    $sql = "SELECT distinct applicantmaster.*,creditsource.title creditsourcetitle,alld.creditsource,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
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
                
                where state=1 and ifnull(applicantmasterop.TMDate,'0')<>'0' and applicantmasterop.applicantstatesID in (35,38)
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) projecthektardone
                
                ,ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                
                where state=1  and applicantmasterop.applicantstatesID not in (34,35,38)
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0)+
                ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                where state=1  and ifnull(appsize,0)=0 and applicantmasterall.DesignArea>$maxAreasmall and applicantmasterop.applicantstatesID not in (34,35,38)
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
                where state=1  and ifnull(appsize,0)=1 and applicantmasterop.applicantstatesID not in (34,35,38) and applicantmasterop.DesignArea>$maxAreasmalls
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) above20cnt
                

                                
                ,case ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0) when 0 then
                (select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                 
                where state=1  and applicantmasterall.DesignArea>$maxAreacorank5  and applicantmasterop.applicantstatesID not in (34,35,38)
                and operatorapprequestin.operatorcoID='$login_OperatorCoID')
                else case SUBSTR(ifnull(firstperiodcoprojectarea,0)/ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)-50,0,1) when '-' then
                (select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                
                where state=1  and applicantmasterall.DesignArea>$maxAreacorank5 and applicantmasterop.applicantstatesID not in (34,35,38)
                and operatorapprequestin.operatorcoID='$login_OperatorCoID')
                else 
                ifnull(case firstperiodcoprojectnumber>0 when 1 then firstperiodcoprojectnumber else 0 end ,0)+
                ifnull((select count(*) cnt from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                
                where state=1  and applicantmasterall.DesignArea>$maxAreacorank5 and applicantmasterop.applicantstatesID not in (34,35,38)
                and operatorapprequestin.operatorcoID='$login_OperatorCoID'),0) end end above55cnt
                
                
                
                ,(select max(applicantmasterall.DesignArea) maxarea from operatorapprequest operatorapprequestin 
                inner join applicantmaster applicantmasterall on  applicantmasterall.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)   
                left outer join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0 
            and applicantmasterop.operatorcoID=operatorapprequestin.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                
                where state=1  and ifnull(appsize,0)=1 and applicantmasterop.applicantstatesID not in (34,35,38)
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
                left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
                inner join operatorco on operatorco.operatorcoID='$login_OperatorCoID' 
                
                left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
                left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 
                left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
                inner join 
                (select distinct applicantmaster.ApplicantMasterID,case applicantmaster.applicantstatesID when 22 then 'صندوق' when 37 then 'بانک' end 
                creditsource from applicantmaster 
where  ifnull(applicantmaster.isbandp,0)=0 and applicantmaster.applicantstatesID in (37,22,24)
) alld on alld.ApplicantMasterID=applicantmaster.ApplicantMasterID 

                where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)  



and ifnull(applicantmaster.proposestate,0)=0   and applicantmaster.proposestatep<>-1 and applicantmaster.applicantmasterid 
not in (select applicantmasterid from operatorapprequest where operatorcoID='$login_OperatorCoID')
and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and ifnull(applicantmaster.isbandp,0)=0 and ifnull(operatorco.corank,0)>0
ORDER BY applicantmaster.DesignArea ";
  if ($login_RolesID==1)//مدیر پیگیری
    $sql = "SELECT distinct applicantmaster.*,creditsource.title creditsourcetitle,alld.creditsource,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
                ,designsystemgroups.title designsystemgroupstitle
				        FROM applicantmaster 
                inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
                inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
                and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
                inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
                and substring(shahr.id,3,5)<>'00000'
                left outer join designer on designer.designerid=applicantmaster.designerid
                left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
                left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
                left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 
                left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
                inner join 
                (select distinct applicantmaster.ApplicantMasterID,case applicantmaster.applicantstatesID when 22 then 'صندوق' when 37 then 'بانک' end 
                creditsource from applicantmaster 
where  ifnull(applicantmaster.isbandp,0)=0 and applicantmaster.applicantstatesID in (37,22,24)
) alld on alld.ApplicantMasterID=applicantmaster.ApplicantMasterID 
                where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)  and
                ifnull(proposestate,0)=0 
ORDER BY applicantmaster.DesignArea ";
        try 
            {		
                $resultup = mysql_query($sql);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
            

  if ($login_DesignerCoID>0 || ($login_designerCO==0) ) //در صورتی که کاربر لاگین شونده مشاور طراح بود
        $condition1.=" and operatorapprequest.operatorcoID='$login_OperatorCoID' ";
        else if ($showkejra==0) // نمایش طرح هایی که هنوز پیش فاکتور آنها ثبت نشده است     
		$condition1.=" and ifnull(applicantmasterdetail.ApplicantMasterIDmaster,0)=0 ";
		
	//	if (strlen(trim($_POST['operatorcoID']))>0 && $showkejra==3)
	//	$condition1=" and operatorco.operatorcoID='$operatorcoID' ";
	if ($showkejra==5)//در صورتی که پیمانکار باشد فیلتر پیمانکار افزوده می شود
		$condition1=" and operatorco.operatorcoID='$operatorcoID' ";
		 
		if ($login_isfulloption==1) //در صورت مجوز داشتن نمایش اطلاعات
    /*
        operatorapprequest جدول پیشنهاد قیمت های طرح 
        operatorapprequestID شناسه پیشنهاد قیمت طرح
        costprice مبلغ برآورد طراحی
        price مبلغ پیشنهادی
        ApplicantName عنوان پروژه
        DesignArea مساحت
        BankCode کدرهگیری
        LName نام خانوادگی طراح
        FName نام طراح
        shahrcityname نام شهر
        operatorcoTitle عنوان پیمانکار
        designsystemgroupstitle عنوان سیستم آبیاری
        state وضعیت انتخاب شدن یا نشدن پیمانکار
        LastFehrestbaha مبلغ هزینه های اجرایی پروژه
        SaveDate تاریخ پیشنهاد
        Freestate وضعیت آزادسازی
        creditsourcetitle منبع تامین اعتبار پروژه
        applicantmaster جدول مشخصات طرح
        tax_tbcity7digit جدول شهرها
        designer جدول طراحان
        designsystemgroups جدول سیستم های آبیاری
        operatorco جدول پیمانکاران
        applicantmasterdetail جدول ارتباطی طرح ها
        creditsource جدول منابع اعتباری
    */   
    $sql = "SELECT distinct operatorapprequest.operatorapprequestID,operatorapprequest.costprice,operatorapprequest.price,applicantmaster.ApplicantName
    ,DesignArea,applicantmaster.BankCode,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
    ,operatorco.Title operatorcoTitle 
    ,designsystemgroups.title designsystemgroupstitle,operatorapprequest.state, applicantmaster.LastFehrestbaha,
    case operatorapprequest.state when 1 then 'منتخب پیشنهاد' else 
    case ifnull(applicantmaster.proposestate,0) 
    when 0 then ' دریافت پیشنهاد' 
    when 3 then 'عدم انتخاب' 
    else 'انتخاب مجری' end end winstate
    ,operatorapprequest.SaveDate operatorapprequestSaveDate,applicantmaster.Freestate,creditsource.title creditsourcetitle
    FROM applicantmaster 
    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    left outer join designer on designer.designerid=applicantmaster.designerid
    left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID
    inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
    inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=operatorapprequest.ApplicantMasterID
    left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
    where 1=1 $condition1 
    union all 
    select operatorapprequest.operatorapprequestID,operatorapprequest.costprice,operatorapprequest.price,'' ApplicantName,'' DesignArea,
    '' BankCode,'' designername ,'' shahrcityname,operatorco.Title operatorcoTitle 
    ,''  designsystemgroupstitle,operatorapprequest.state, '' LastFehrestbaha,
    case operatorapprequest.state when 1 then 'منتخب پیشنهاد' else 
    case ifnull(applicantmaster.proposestate,0) 
    when 0 then ' دریافت پیشنهاد' 
    when 3 then 'عدم انتخاب' 
    else 'انتخاب مجری' end end winstate
   ,operatorapprequest.SaveDate operatorapprequestSaveDate,applicantmaster.Freestate,creditsource.title creditsourcetitle
    from operatorapprequest 
    inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
    inner join applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID
    inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
    left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
    where operatorapprequest.applicantmasterid=0 $condition1
    ORDER BY winstate COLLATE utf8_persian_ci,BankCode COLLATE utf8_persian_ci ;";

    try 
    {		
        if ($login_isfulloption==1)//در صورتی که کاربر مجوز مشاهده اطلاعات را داشت
        $result = mysql_query($sql);
    }
	
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    } 
            
//پرس و جوی مربوط به فیلتر مساحت پروژه ها
$query="
select '0-10' _key,1 as _value union all 
select '10-20' _key,2 as _value union all
select '20-50' _key,3 as _value union all
select '50-100' _key,4 as _value union all
select '100-200' _key,5 as _value union all
select '200-500' _key,6 as _value union all
select '500-1000' _key,7 as _value union all
select '<1000' _key,8 as _value ";
$IDArea = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به فیلتر مبلغ پروژه
$query="
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDcostprice = get_key_value_from_query_into_array($query);
$IDprice = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به فیلتر وضعیت انتخاب یا عدم انتخاب شدن
$query="
select 'برگزار نشده' _key,0 as _value union all 
select 'عدم انتخاب' _key,1 as _value union all
select 'منتخب پیشنهاد' _key,2 as _value ";
$IDwin = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به فیلتر پیمانکاران پیشنهاد دهنده قیمت
$query="select distinct operatorco.operatorcoID _value, operatorco.Title _key from  operatorco 
inner join operatorapprequest on operatorapprequest.operatorcoID=operatorco.operatorcoID order by _key  COLLATE utf8_persian_ci";
$ID1 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به کدهای رهگیری پروژه ها  جهت فیلتر کردن
$query="select distinct applicantmaster.BankCode _key ,applicantmaster.BankCode _value from applicantmaster 
inner join operatorapprequest on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID order by _key  COLLATE utf8_persian_ci";
$ID2 = get_key_value_from_query_into_array($query);
//پرس و جوی به عناوین پروژ ها جهت فیلتر کردن
$query="select distinct applicantmaster.ApplicantMasterID _value, applicantmaster.ApplicantName _key from applicantmaster 
inner join operatorapprequest on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID order by _key  COLLATE utf8_persian_ci";
$ID3 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به شهر طرح ها  جهت فیلتر کردن
$query="select distinct id _value,CityName _key from applicantmaster
inner join tax_tbcity7digit on substring(tax_tbcity7digit.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,5)<>'00000' and ifnull(applicantmaster.DesignerCoID,0)>0 
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID order by _key  COLLATE utf8_persian_ci";
$ID4 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به نام طراحان  جهت فیلتر کردن
$query="select distinct designer.DesignerID _value, CONCAT(designer.LName,' ',designer.FName) _key from designer 
inner join applicantmaster on applicantmaster.DesignerID=designer.DesignerID 
inner join operatorapprequest on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID order by _key COLLATE utf8_persian_ci";
$ID5 = get_key_value_from_query_into_array($query);


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
 <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
 
    </script>
    
    <script type="text/javascript">
            $(function() {
                $("#Datefrom, #simpleLabel").persiandatepicker();   
                $("#Dateto, #simpleLabel").persiandatepicker();   
				
            });
        
        
    </script>
    
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
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

    <script>
function CheckForm()
{
    
            
    if (document.getElementById('Bankcode').value=='XXX' && document.getElementById('propose30daypermissionless').value!=1) 
        {
            alert('مجوز شرکت شما به پایان رسیده یا کمتر از یک ماه آینده به پایان می رسد. امکان پشنهاد قیمت وجود ندارد!');
            return false;
        }
        
    
    if (document.getElementById('Bankcode').value=='XXX') 
        {
            alert('صلاحیت پیشنهاد قیمت برای این طرح کافی نمی باشد. امکان پشنهاد قیمت وجود ندارد!');
            return false;
        }
        
    if (document.getElementById('login_RolesID').value==2)
    {
        if ( ((document.getElementById('costpricewithcoef').value*1)/(document.getElementById('applicantferestbahabase').value*1))>=3
        ||
        !((document.getElementById('costpricewithcoef').value*1)>1)) 
        {
            alert('مبلغ وارده منطقی به نظر نمیرسد!');return false;
        }    
        
        if ( (document.getElementById('coef1').value*1)>(document.getElementById('appcoef1').value*1)) 
        {
            alert('ضریب بالاسری حد اکثر مقدار ضریب بالاسری طرح باید باشد!');return false;
        }    
        //alert(document.getElementById('coef2').value);
        //alert(document.getElementById('appcoef2').value);
        
        if ( (document.getElementById('coef2').value*1)>(document.getElementById('appcoef2').value*1)) 
        {
            alert('ضریب تجهیز حد اکثر مقدار ضریب تجهیز طرح باید باشد!');return false;
        }    
        if ( ((document.getElementById('coef3').value*1)<0) || ((document.getElementById('coef3').value*1)>2) )
        {
            alert('ضریب پلوس/مینوس باید بین 0 تا 2 باشد!');return false;
        }    
        if (!(document.getElementById('file1').value != "">0))
        {
            alert('لطفا اسکن فایل پیشنهاد قیمت را انتخاب نمایید!');return false;
        }        
    }


     return confirm('این شرکت ضمن مطالعه و قبول شرایط ذیل پیشنهاد قیمت و آگاهی از ضوابط و قوانین استفاده از سامانه، پیشنهاد قیمت خود را ارسال می نماید.');
	
}

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
    
function mult()
{	
    document.getElementById('costpricewithcoef').value=Math.round( document.getElementById('costprice').value*
    document.getElementById('coef1').value*document.getElementById('coef2').value*document.getElementById('coef3').value*10)/10;
}

    function fillform(Url)
    {     
                    
                    var selectedBankcode=document.getElementById('Bankcode').value;
                    if (selectedBankcode.length>0)
                    {
                        $("#loading-div-background").show();
                        //alert(selectedBankcode);
                        $.post(Url, {selectedBankcode:selectedBankcode}, function(data){
                            //alert(data.errors);                                                        
                       // alert(data.ApplicantMasterID);
                        $("#loading-div-background").hide();  
                       if ((data.errors)!=''    &&  (<?php echo $login_userid; ?>!=226 || data.ApplicantMasterID!=2799)    )
                        {
                           if (data.errors=='-1')
                            {
                                if (<?php echo $Permissionvals['propose30daypermissionless']; ?>==0) 
                                {
                                    $('#Bankcode').val('XXX');
                                    alert('مجوز شرکت شما به پایان رسیده یا کمتر از یک ماه آینده به پایان می رسد. امکان پشنهاد قیمت وجود ندارد!');
                                    
                                }
                                
                                //return;
                            }
                            
                            //alert("errors"); 
                                switch (data.errors) {
                                case '1':
                                    if (<?php echo $Permissionvals['proposepermissionless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا - تاریخ مجوز شرکت منقضی شده است.");
                                    }
                                    break;
                                case '2':
                                    if (<?php echo $Permissionvals['proposedesignerless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -شرکت فاقد کارشناس طراح است.");
                                    }
                                    break;
                                case '3':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -کارشناس طراح این شرکت در بیش از یک شرکت شاغل می باشد.");
                                    }
                                    break;
                                case '4':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد پروژه های جاری این شرکت بیشتر از تعداد مجاز است.");
                                    }
                                    break;
                                case '5':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بزرگ پایه بیشتر از حد مجاز می باشد.");
                                    }
                                    break;
                                case '6':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بزرگ پایه بیشتر از حد مجاز می باشد.");
                                        
                                    }
                                    break;
                                case '7':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بزرگ پایه بیشتر از حد مجاز می باشد.");
                                    }
                                    break;
                                case '8':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بزرگ پایه بیشتر از حد مجاز می باشد.");
                                    }
                                    break;
                                case '9':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بزرگ پایه بیشتر از حد مجاز می باشد.");
                                    }
                                    break;
                                case '10':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -تعداد مجاز طرح های بالای 50 هکتار پایه  بیشتر از حد مجاز می باشد.");
                                    }
                                    break;
                                case '11':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه می باشد.");
                                    }
                                    break;
                                case '12':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه می باشد.");
                                    }
                                    break;
                                case '13':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه می باشد.");
                                    }
                                    break;
                                case '14':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه می باشد.");
                                    }
                                    break;
                                case '15':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه می باشد.");
                                    }
                                    break;
                                case '16':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز   می باشد.");
                                    }
                                    break;
                                case '17':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز   می باشد.");
                                    }
                                    break;
                                case '18':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز   می باشد.");
                                    }
                                    break;
                                case '19':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز   می باشد.");
                                    }
                                    break;
                                case '20':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا -مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز   می باشد.");    
                                    }
                                    break;
                                case '21':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا- شما سابقه کافی جهت پیشنهاد قیمت این طرح را دارا نمی باشید.");
                                    }
                                    break;
                               case '22':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا- شرکت طبق مصوبه کمیته فنی آب و خاک و آیین نامه  مجاز به پیشنهاد قیمت این طرح نمی باشد .");
                                    }
                                    break;
                               case '23':
                                    if (<?php echo $Permissionvals['proposecoless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا-تاریخ اعتبار هیئت مدیره منقضی شده است");
                                    }
                                    break;
                               case '24':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا-شرکت طبق مصوبه کمیته فنی آب و خاک  فاقد سابقه کار مورد نیاز می باشد");
                                    }
                                    break;
                               /*case '25':
                                    if (<?php echo $Permissionvals['proposeprojectless']; ?>==0) 
                                    {
                                        $('#Bankcode').val('XXX');
                                        alert("خطا-مجموع دو طرح بزرگ، بیش تر از سقف مجاز می باشد");      
                                    }
                                    break;*/
                            } 

                        }        
                        if (data.error==1)  
                            alert('امکان پیشنهاد قیمت فقط برای شرکت های مجری فراهم می باشد');  
                        else if (data.error==2)
                            alert('شما قبلا برای این طرح پیشنهاد قیمت داده اید');  
                            else if (data.error==3)
                                alert('مهلت ارسال پیشنهاد قیمت برای این طرح به پایان رسیده است');  
                            else if (data.error==4)
                                alert('امکان ارسال پیشنهاد برای این طرح هنوز فراهم نشده است');  
                                else if (data.error==5)
                                alert('کد رهگیری نامعتبر می باشد');  
                                else
                                { 
                                $('#tableproducers').html(data.boxstr);
                                //$('#Code').val(data.Code);
                                $('#ApplicantName').val(data.ApplicantName);
                                $('#DesignArea').val(data.DesignArea);
                                $('#designsystemgroupstitle').val(data.designsystemgroupstitle);
                                $('#shahrcityname').val(data.shahrcityname);
                                $('#designername').val(data.designername);
                                $('#applicantferestbaha').val(data.applicantferestbahabase);
                                //$('#costpricewithcoef').val(data.applicantferestbaha);
                                var fval='';
                                
                                $('#Applicanttotal').val(data.Applicanttotal);
                                $('#ApplicantMasterID').val(data.ApplicantMasterID);
                               // alert(1);
                                $('#applicantferestbahabase').val(data.applicantferestbahabase);
                                $('#costprice').val(data.applicantferestbahabase);
                                $('#costyear').val(data.fb);
                                

                                $('#appcoef1').val(data.appcoef1);
                                $('#appcoef2').val(data.appcoef2);
                                $('#appcoef3').val(data.appcoef3);
                                document.getElementById('coef1').value=data.appcoef1;
                                document.getElementById('coef2').value=data.appcoef2;
                                document.getElementById('coef3').value=1;
                            
                                }    
                                }, 'json');                           
                    }
    }
       

  function checkchange(){
		   if (document.getElementById('showkejra').checked)
			{
						//var sysbelaavaz2=document.getElementById('sysbelaavaz').value;
						var uid3=document.getElementById('uid3').value;
						//alert(uid3);
						//<?php $sys ?> = sysbelaavaz2;
							window.location.href =document.getElementById('uid3').value;
		
			}		
			else 
			{		
				var uid1=document.getElementById('uid1').value;
		        window.location.href =document.getElementById('uid1').value;
    		
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
                       
                   <tbody >
                             <thead style = "text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';">
                             <td class='data'><input name='login_RolesID' type='hidden' readonly class='textbox' id='login_RolesID' value="<?php echo $login_RolesID?>" /></td>
                             <td class='data'><input name='propose30daypermissionless' type='hidden' readonly class='textbox' id='propose30daypermissionless' value="<?php echo $Permissionvals['propose30daypermissionless']?>" /></td>
                            

<?php				if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        
                        print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','90');
                     }
					if ($Permissionvals['hidecredit']==1) $hide="display:none;"; else $hide="";
					 
    ?>      
               
							
							
                     <tr>
                     <th colspan="12"  style = "text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';" ><a target='blank' href='../../upfolder/formpishnahad.docx' >دانلود فرم پیشنهاد قیمت</a></th>
                     </tr>
             
							
                     <tr>
                      <th colspan="12"  style = "text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';background-color:#A4F5BA;">لیست طرح های قابل پیشنهاد (مبالغ به میلیون ریال می باشد)</th>
                     </tr>
                     <tr>
                      <th colspan="12"  style = "color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';">(بعد از <?php echo $Permissionvals['proposedaycnt']?> روز از تاریخ شروع دریافت پیشنهاد هر طرح، در صورت رسیدن به حد نصاب، دریافت پیشنهاد قیمت طرح توسط مدیریت آب و خاک متوقف می شود.)</th>
                      </tr>
                          
						  <tr>
						    <th></th>
                            <th style="text-align: center;" colspan="1">کد رهگیری</th>
                            <th style="text-align: center;"colspan="1">متقاضي</th>
                            <th style="text-align: center;">شهرستان</th>
                            <th style="text-align: center;">Ha</th>
                            <th style=" <?php echo $hide;?>; text-align: center;">اعتبار</th>
                            <th style="text-align: center;">تاریخ شروع</th>
                            <th style="text-align: center;"colspan="1" width="27%"><?php echo str_replace(' ', '&nbsp;', "دلایل عدم صلاحیت"); ?> </th>
                            <th style="text-align: center;">ریز</th>
                            <th  style="text-align: center;">نقشه</th>
                            <th  style="text-align: center;">طراحی</th>
                            <th  style="text-align: center;">محاسبات</th>
							<th></th>
                            
                        </tr>
                        
                        
                        
                        
                        </thead>
         
         <?php
                     
                    

                     if ($showkejra==4) print 'pay='.$linkpay.'<br> cmd='.$linkcmd.'<br>';
      
                    $Total=0;$rown=0;$contin=0;
                  //  if ($login_OperatorCoID>0)
                    while($resquery = mysql_fetch_assoc($resultup)){
                            $Freestate= $resquery["Freestate"];
                            $ApplicantName = $resquery["ApplicantName"];
                        	$DesignArea = $resquery["DesignArea"];
                            $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                            $shahrcityname = $resquery["shahrcityname"];
                            $designername = $resquery["designername"];
							$rcorank=$resquery["corank"];
  							$errors="";
                            $joinyearlow=0;
                            $date = new DateTime(jalali_to_gregorian($resquery["joinyear"]));
                            $date->modify('+720 day');
                            //$date->add(new DateInterval('P2Y'));
                            if ($date->format('Y-m-d')>date('Y-m-d'))
                                $joinyearlow=1;
							
						
						 
                     	if ($Permissionvals['proposeprojectless']==0)
                        {    
                            $smallapplicantsize=$Permissionvals['smallapplicantsize'];
                            $linearray = explode('_',$resquery['CountyName']);
                            $apps=$linearray[5];
                            if ($apps==1)  $smallapplicantsize=$resquery["DesignArea"];
							
                            if (($resquery["simultaneouscnt"]>=$Permissionvals['tmphtp']))
                                $errors.="<br>تعداد پروژه های جاری این شرکت بیشتر از سقف مجاز می باشد";
                            if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp1']))  
                                $errors.="<br>تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                            if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp2']))  
                                $errors.="<br>تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                            if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp3']))  
                                $errors.="<br>تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                            if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp4']))  
                                $errors.="<br>تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
						   if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20cnt"]>=$Permissionvals['tmtb10hp5']))  
                                $errors.="<br>تعداد مجاز طرح های بزرگ پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
                            /*
                            if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$smallapplicantsize) && ($resquery["above20max"]>$smallapplicantsize) && (($resquery["above20max"]+$resquery["DesignArea"])>$Permissionvals['hmmp5'])) 
                                $errors.="<br>مجموع دو طرح بزرگ، بیش تر از سقف مجاز می باشد ";  
                                */
								
                            if (!($resquery["proposelimitless"]>0))
                            {
							
								if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$maxAreacorank5*$zarib5) && ($resquery["above55cnt"]>=$Permissionvals['tmtb50hp5']))  
									$errors.="<br>تعداد مجاز طرح های بالای 50 هکتار پایه $resquery[corank] بیشتر از حد مجاز می باشد.";  
									
								if (($resquery["corank"]==1) && ($resquery["DesignArea"]>$Permissionvals['hmmp1']))  
									$errors.="<br>مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
								if (($resquery["corank"]==2) && ($resquery["DesignArea"]>$Permissionvals['hmmp2']))  
									$errors.="<br>مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
								if (($resquery["corank"]==3) && ($resquery["DesignArea"]>$Permissionvals['hmmp3']))  
									$errors.="<br>مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
								if (($resquery["corank"]==4) && ($resquery["DesignArea"]>$Permissionvals['hmmp4']))  
									$errors.="<br>مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
								if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['hmmp5']*$zarib5))  
									$errors.="<br>مساحت این پروژه بیشتر از مساحت حداکثر مجاز پایه $resquery[corank] می باشد.";  
									
								if (($resquery["corank"]==1) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp1']))  
									$errors.="<br>مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
								if (($resquery["corank"]==2) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp2']))  
									$errors.="<br>مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
								if (($resquery["corank"]==3) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp3']))  
									$errors.="<br>مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
								if (($resquery["corank"]==4) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp4']))  
									$errors.="<br>مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
								if (($resquery["corank"]==5) && (($resquery["DesignArea"]+$resquery["thisyearprgarea"])>$Permissionvals['hmmsmp5']))  
									$errors.="<br>مجموع مساحت پروژه های این شرکت بالاتر از حداكثر مساحت مجموع سالانه مجاز  می باشد.";  
								   
								if ($resquery["projecthektardone"]>100)
									$Max=110;
								else if ($resquery["projecthektardone"]>50 && ($resquery["MaxDone"]>$Permissionvals['experimentthreshold']))    
									$Max=$resquery["MaxDone"];
								else $Max=$Permissionvals['experimentthreshold'];    
								
								if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Max))  
										$errors.="<br>شرکت طبق مصوبه کمیته فنی آب و خاک  فاقد سابقه کار مورد نیاز می باشد.";  
                            }
                            //print $Permissionvals['experimentthreshold']."sa";
                             /*
                            if (($resquery["corank"]==5) && ($resquery["DesignArea"]>$Permissionvals['experimentthreshold']) && ($joinyearlow==1))  
                                    $errors.="<br>شرکت سابقه کافی جهت پیشنهاد قیمت این طرح را دارا نمی باشد.";  
                                    */
                        }
				
						if ($resquery["ApplicantMasterID"]==$resquery["StarCo"]) {$errors=""; $contin=0;}
						
						
						if ($Permissionvals['proposedesignerless']==0)
							{
								if (!($resquery["designercnt"]>=1))
									$errors.="<br>شرکت فاقد کارشناس فنی است.";
								if (($resquery["duplicatedesigner"]>=1))
									$errors.="<br>کارشناس فنی این شرکت در بیش از یک شرکت شاغل می باشد.";
                            }
 										if ($Permissionvals['proposepermissionless']==0)
                        if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errors.="<br>تاریخ مجوز شرکت منقضی شده است.";
                        if (strlen($errors)>0) $contin=1;
						

						
 						if ($resquery["StarCo"]==1)
                            $errors="<br>شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت نمي باشد."; 
                        else if ($resquery["ent_Num"]>0 && compelete_date($resquery["ent_DateTo"])>=gregorian_to_jalali(date('Y-m-d')) )
                                {
                                    if (($resquery["DesignArea"]>=$resquery["ent_Hectar"])||
                                     ($resquery["simultaneouscnt"]>=$resquery["ent_Num"])  )
                                    {
                                        $errors.="<br> شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت انتظامی میباشد .";
                                    }
                                }
	                    if ($Permissionvals['proposecoless']==0)
                        if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errors.="<br>تاریخ اعتبار هیئت مدیره منقضی شده است.";
							
						if ($login_RolesID<>19)	
						//if ($login_Domain=='loc'|| $showkejra==4) print $ApplicantName.':'.$errors.'</br>';
                    	if ($login_Domain=='loc') print $ApplicantName.':'.$errors.'</br>';
                    
                       //if (strlen($errors)>0)  continue;
					   //if ( ($login_userid==$resquery["Disabled"]) && ($resquery['BankCode']==$resquery["StarCo"]) ) continue;
						$rown++;
						if (strlen($errors)>0)   $errorsID[$rown]=$resquery['ApplicantMasterID'];
						if (strlen($errors)>0) $cl='ff0000'; else $cl='000000';

					if ($login_RolesID==1 || $login_RolesID==19) $errors="";

					   
					   //print_r ($errorsBankCode);
                       //exit;
                        if ($early==0)
                        {
                            $fstr1="";
                            $fstr2="";
                            $fstr3="";
                            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/';
                            $handler = opendir($directory);
                            while ($file = readdir($handler)) 
                            {
                                // if file isn't this directory or its parent, add it to the results
                                if ($file != "." && $file != "..") 
                                {
                                  $linearray = explode('_',$file);
                                    $ID=$linearray[0];
                                    $No=$linearray[1];
                                    if (($ID==$resquery['ApplicantMasterID']) && ($No==1) )
                                        $fstr1="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 30px;' src='../img/attachment.png' title='فایل اتوکد' ></a>";
                                    
                                    if (($ID==$resquery['ApplicantMasterID']) && ($No==2) )
                                        $fstr2="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 30px;' src='../img/full_page.png' title='دفترچه طراحی' ></a>";
                                    
                                    if (($ID==$resquery['ApplicantMasterID']) && ($No==3) )
                                        $fstr3="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 30px;' src='../img/new_page.png' title='دفترچه محاسبات' ></a>";        
                                }
                            }
                       }
                        $ID = $resquery['ApplicantMasterID'].'_13_0_0_'.$resquery['applicantstatesID']; 
 						//print $contin.'*'.$resquery["DesignArea"].'*'.$login_OperatorCoID;
				 if ($contin==1 && $resquery["DesignArea"]>11 && $login_OperatorCoID>0) continue;
				 if ( ($login_userid!=$resquery["Disabled"]) || ($resquery['BankCode']!=$resquery["StarCo"]) )
					{ 	?>
								<tr>
								<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
								<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["BankCode"]; ?></td>
								<td colspan="1" class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $ApplicantName;
								if ($apps==1) echo "(این طرح کوچک می باشد)";
								 ?></td>
								<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $shahrcityname ?></td>
								<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $DesignArea; ?></td>
								<td class="f10_font<?php echo $b; ?>"  style="<?php echo $hide; ?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
								echo $resquery['creditsource']."<br>".str_replace(' ', '&nbsp;', $resquery['creditsourcetitle']);
								if ($Freestate>0) echo '<br>(اسناد خزانه اسلامی)';
								?>
								</td>
								<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  gregorian_to_jalali( $resquery['ADate']) ?></td>
								<td colspan="1" class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo substr($errors,4) ;
								if ($resquery["proposelimitless"]>0) echo "(مجاز برای تمامی پایه ها)";
								?></td> 
							<?php
						  //if ($early==1)
							print "<td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">
							<a  target=\"_blank\"  href='summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
							rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
							"'><img style = 'width: 35px;' src='../img/search_page.png' title=' ريز '></a></td>";
							print "
							<td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">$fstr1</td>
							<td >$fstr2</td>
							<td >$fstr3</td>
							<td ></td>
							</tr>";
					}
						else 
						echo  " <tr>
								<th colspan='12'  style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"
								>شرکت مجری محترم.
								امکان مشاهده کامل پیشنهاد های قیمت برای شما فراهم نمی باشد. لطفا با مدیریت آب و خاک تماس حاصل فرمایید.</th>
							  </tr>	";
						
	}
	
				
						if ($contin==1)
						{
							echo  " <tr>
									<th colspan='12'  style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"
									>شرکت مجری محترم.
									امکان مشاهده کامل پیشنهاد های قیمت برای شما فراهم نمی باشد. لطفا با مدیریت آب و خاک تماس حاصل فرمایید.</th>
								  </tr>	";
								if ($rcorank==0) 
							echo  " <tr>
									<th colspan='12'  style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"
									>شرکت مجری محترم.
									رتبه شرکت شما معتبر نمی باشد لطفا با مدیریت آب و خاک تماس حاصل فرمایید.</th>
								  </tr>	";
						}	  
	
?>

</table>
<table id="records" width="95%" align="center">
                       
                   <tbody >

                    <thead style = "text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';">

					
<?php   
				   
				   
                   if ($early==0)
                   {
                     print "  
 		            <tr>&nbsp&nbsp</tr><tr>
                                     <th colspan='13' style = \"text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';background-color:#A4F5BA;\"
                            >ثبت پیشنهاد قیمت (میلیون ریال)</th>
                </tr>    
                        <tr>    
                            <th colspan='13' style = \"text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';\"
                            >کلیه اطلاعات پیشنهاد قیمت در اختیار مدیریت آب و خاک می باشد، لطفاً جهت هرگونه اعلام نظر و تغییرات با مدیر آب و خاک تماس گرفته شود. </th>
                        </tr>
                        
						<tr>
                          <th colspan=8></th>
                            <th colspan=5>اسكن پيشنهاد قيمت کاملا خوانا(حداکثر 100 کیلوبایت) 
                          </th>
                        </tr>
                        
						 <tr>
                        	<th ></th>
                            <th style = \"text-align:center;\">کد رهگیری</th>
                            <th style = \"text-align:center;\">نام متقاضي</th>
                            <th style = \"text-align:center;\">سیستم آبیاری</th>
                            <th style = \"text-align:center;\">Ha</th>
                            <th style = \"text-align:center;\">شهرستان</th>
                            <th style = \"text-align:center;\">طراح</th>
                            <th style = \"text-align:center;\">م فهرست بهای طرح (بدون ضرایب)</th>
                            <th style = \"text-align:center;\">م پيشنهاد فهرست بها (با ضرایب)</th>
							<th colspan=4 style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"> 
							پیشنهاد قیمت با اسکن ناخوانا حذف خواهد شد
							</th>
							
                         </tr>
                       
                    </thead>  
					        ";
	
	
	



   			if ($linkpay && $login_opDisabled<>7 && $login_opDisabled<>9)
				{				
					print  "<tr>   <td />
					<td class='data'> <div id='divBankcode'>
			        <input name='Bankcode' type='text' class='textbox' id='Bankcode'  style='background-color:#ffff00;width: 90px' 
                    $linkpay /></div> </td>";
					
		       	} else {
//print $casedebt.'*'.$linkcmd.'*'.$linkpay.'*'.$userdebt.'*'.$startpay.'*'.$ncorank.'*'.$Disabled;exit;
				
            		 print  "
                            <tr>   <td />
                            <td class='data'><div id='divBankcode'>
                            <input name='Bankcode' type='text' class='textbox' id='Bankcode'  style='background-color:#ffff00;width: 90px' 
                            onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/apprequest_jr.php');\"
							/></div>
                            </td>
   			    	        ";
                     }
					 
				    print  "
                            
                            <td class='data'><div id='divApplicantName'>
                            <input name='ApplicantName' readonly type='text' class='textbox' id='ApplicantName'  style='width: 120px' /></div>
                            </td>
                            
                            <td class='data'><div id='divdesignsystemgroupstitle'>
                            <input name='designsystemgroupstitle' readonly type='text' class='textbox' id='designsystemgroupstitle'  style='width: 100px' /></div>
                            </td>
                            <td class='data'><div id='divDesignArea'>
                            <input name='DesignArea' readonly type='text' class='textbox' id='DesignArea'  style='width: 50px' /></div>
                            </td>
                            
                            
                            <td class='data'><div id='divshahrcityname'>
                            <input name='shahrcityname' readonly type='text' class='textbox' id='shahrcityname'  style='width: 80px' /></div>
                            </td>
                            
                            
                            <td class='data'><div id='divdesignername'>
                            <input name='designername' readonly type='text' class='textbox' id='designername'  style='width: 75px' /></div>
                            </td>
                            
                            
                            
                            <td class='data'><div id='divapplicantferestbaha'>
                            <input name='   ' readonly type='text' class='textbox' id='applicantferestbaha'  style='width: 170px' /></div>
                            </td>
                           
                            <td class='data'><div id='divcostpricewithcoef'>
                            <input name='costpricewithcoef'   type='text' class='textbox' id='costpricewithcoef'  
                            style='background-color:#ffff00;width: 170px' 
                            onblur=\"
                                if (document.getElementById('costpricewithcoef').value>0)
                                if ( ((document.getElementById('costpricewithcoef').value*1)/(document.getElementById('applicantferestbahabase').value*1.365))>=0.91
                                    ||
                                !((document.getElementById('costpricewithcoef').value*1)>1)) 
                                {
                                    alert('مبلغ وارده بالاتر از مبلغ مصوب كميته فني مي‌باشد!');
                                } 
                            \"  />
                            </div>
                            </td>
                            
                                  
                            
                            <td colspan='1' class='data'><input type='file' name='file1' id='file1' style='width: 80px'></td>
                            
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت'  $linkcmd /></td>
                            
							<td class='data'><input name='ApplicantMasterID' type='hidden' readonly class='textbox' id='ApplicantMasterID' /></td>
                            
                            
                    </tr>
                    
                    <tr>
                            
                            <td class='data'><div id='divcostyear'>
                            <input  name='costyear' readonly type='text' class='textbox' id='costyear' maxlength='4'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                                   
                            <td class='data'><div id='divcostprice'>
                            <input  onchange = \"mult();\" readonly name='costprice'  type='text' class='textbox' id='costprice' maxlength='6'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                            
                            <td class='data'><div id='divcoef1'>
                            <input onchange = \"mult();\"  name='coef1'  type='text' class='textbox' id='coef1' maxlength='4' style='visibility: hidden;background-color:#ffff00;width: 1px' /></div>
                            </td>
                            <td colspan='3'/>
                            <td colspan='2' class='data'><div id='divformula'>
                            <input  name='formula' readonly type='text' class='textbox'  dir='ltr' id='formula' maxlength='4'  style='width: 247px' /></div>
                            </td>
                            
                            <td class='data'><div id='divcoef2'>
                            <input onchange = \"mult();\"  name='coef2'  type='text' class='textbox' id='coef2' maxlength='4' style='visibility: hidden;background-color:#ffff00;width: 1px' /></div>
                            </td>
                            
                            <td class='data'><div id='divcoef3'>
                            <input onchange = \"mult();\"  name='coef3'  type='text' class='textbox' id='coef3' maxlength='5' style='visibility: hidden;background-color:#ffff00;width: 1px' /></div>
                            </td>
                     
                            <td class='data'><div id='divApplicanttotal'>
                            <input name='Applicanttotal' readonly type='text' class='textbox' id='Applicanttotal'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                           
                     
                            <td class='data'><div id='divprice'>
                            <input name='price'  type='text'  class='textbox' id='price' maxlength='7' style='visibility: hidden;background-color:#ffff00;width: 1px' /></div>
                            </td>
                     </tr>  
                     <tr>  
                     <td colspan='8'></td>
                            
                            <td class='data'><div id='divappcoef1'>
                            <input name='appcoef1' readonly type='text' class='textbox' id='appcoef1'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                            
                            <td class='data'><div id='divappcoef2'>
                            <input name='appcoef2' readonly type='text' class='textbox' id='appcoef2'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                            
                            <td class='data'><div id='divappcoef3'>
                            <input name='appcoef3' readonly type='text' class='textbox' id='appcoef3'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                            
                            
                            <td class='data'><div id='divapplicantferestbahabase'>
                            <input name='applicantferestbahabase' readonly type='text' class='textbox' id='applicantferestbahabase'  style='visibility: hidden;width: 1px' /></div>
                            </td>
                      </tr>             
                    
                    <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >قیمت های پیشنهادی قطعی نبوده و پس از اعلام منتخب و بررسی پیش فاکتور های لوازم پروژه، قیمت نهایی فهرست بهای اجرایی بر حسب حجم عملیات اجرایی محاسبه خواهد گردید.</span>  </td>
                   </tr>  
                   <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            لطفا جهت تغییر وضعیت  طرح های منتخب در پیشنهاد قیمت ، کد رهگیری و شهرستان محل اجرای طرح  را به درستی در سربرگ ثبت طرح آبیاری وارد نمایید.</span>  </td>
                   </tr>
                   <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            قیمتهای پیشنهادی جهت انتخاب پیمانکار(مجری) در اختیار متقاضی (کشاورز) قرار خواهد گرفت.</span>  </td>
                   </tr>
                   <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            در صورت مشاهده هرگونه خطا با پشتیبان سامانه مکاتبه نمایید.</span>  </td>
                   </tr>
                   <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            ضریب پیشنهادی مطابق با مصوبات کمیته فنی و با تقسیم مبلغ پیشنهادی بر مبلغ پایه فهرست بهای طرح و ضرایب 1.3 و 1.05 محاسبه می گردد.</span>  </td>
                   </tr>
                   
                   <tr> 
                    
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            حداکثر سطح هر پروژه مطابق با مصوبه کمیته فنی مدیریت آب و خاک در نظر گرفته شده است.</span>  </td>
                   </tr>
                   ";
                   
                   }
                   else 
                   echo "
                   <tr>
                   <th colspan=\"12\"  style = \"color:#ff0000;text-align:center;font-size:16;font-weight: bold;font-family:'B Nazanin';\"
                   >توجه! امکان ثبت پیشنهاد قیمت از ساعت 1 بعد از ظهر تا ساعت 9 صبح فراهم می باشد</th>
                   </tr>
				    ";
          
				echo "
                   <tr>&nbsp</tr><tr>&nbsp</tr>
				   <tr>
                   <th colspan=\"12\"  style = \"color:#0000ff;text-align:center;font-size:16;font-weight: bold;font-family:'B Nazanin';background-color:#A4F5BA\"
                   >لیست پیشنهاد های ارسال شده (میلیون ریال)</th>
                   </tr>
				   
				    ";
          
					$uid3="apprequest.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['operatorcoID']."_3_".$login_ostanId.rand(10000,99999);
					$uid1="apprequest.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['operatorcoID']."_1_".$login_ostanId.rand(10000,99999);
         ?>
                            
				<table id="records" width="95%" align="center">
                   <tbody >
                          <tr>
                            <th ></th>
                            <th  style="text-align: center;" >شرکت</th>
                            <th   style="text-align: center;" colspan="1">کد رهگیری</th>
                            <th   style="text-align: center;" colspan="1">متقاضي</th>
                            <th  style="text-align: center;" >Ha</th>
                            <th  style="<?php echo $hide;?>; text-align: center;" >اعتبار</th>
                            <th  style="text-align: center;" >شهرستان</th>
                            <th  style="text-align: center;" >فهرست بها</th>
                            <th  style="text-align: center;" >م پيشنهادی</th>
                            <th  style="text-align: center;" >کد پیگیری</th>
                            <th  style="text-align: center;" >تاریخ پیشنهاد</th>
                            <th  style="text-align: center;" >وضعیت
					<input name='showkejra' type='checkbox' id='showkejra' onChange='checkchange()' <?php if ($showkejra==3) echo "checked";?>>
				    <input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  />
                    <input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  />
                 
							</th>
                        </tr>
                         
                        <?php
                        if ($login_designerCO==1)
                        {
                            print "<tr>
						<td></td>";
                        print select_option('operatorcoID','',',',$ID1,0,'','','1','rtl',0,'',$operatorcoID,'','90'); 
                         print select_option('BankCode','',',',$ID2,0,'','','1','rtl',0,'',$BankCode,'','120'); 
						print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'','100'); 
						 print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','50'); 
						 print select_option('City','',',',$ID4,0,'','','1','rtl',0,'',$City,'','80'); 
//						 print select_option('Designer','',',',$ID5,0,'','','1','rtl',0,'',$Designer,'','75'); 
						 print select_option('IDcostprice','',',',$IDcostprice,0,'','','1','rtl',0,'',$IDcostprice,'','90'); 
						 print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice,'','90')."
                        <td><input placeholder=\"انتخاب تاریخ\"  name=\"Datefrom\" type=\"text\" class=\"textbox\" 
                        id=\"Datefrom\" value=\"$Datefrom\" style='width: 80px'/></td>
                        <td><input placeholder=\"انتخاب تاریخ\"  name=\"Dateto\" type=\"text\" class=\"textbox\" id=\"Dateto\" 
                        value=\"$Datefrom\" style='width: 65px' /></td>
                         ";
                         print select_option('IDwin','',',',$IDwin,0,'','','1','rtl',0,'',$IDwin,'','75')."
                         <td><input   name='search' type='submit' class='button' id='search ' value='جستجو' /></td></tr>"; 
                        }
                        ?> 
						
                        
                        </thead>
         
         <?php
                     
                    
                     
                    $Total=0;
                    $rown=0;
					
                    while($resquery = mysql_fetch_assoc($result))
                    {
                        $Freestate= $resquery["Freestate"];

					if ($showkejra<=1 && $resquery["winstate"]=='عدم انتخاب') continue;
					
                            $ApplicantName = $resquery["ApplicantName"];
                        	$DesignArea = $resquery["DesignArea"];
                            $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                            $shahrcityname = $resquery["shahrcityname"];
                            $designername = $resquery["designername"];
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                            
                            if ($resquery["winstate"]=='منتخب پیشنهاد') $cl='4AA143'; 
                            else if ($resquery["winstate"]=='عدم انتخاب') $cl='ff5500'; else $cl='888888';  
                       
                            
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
                                    if ($ID==$resquery["operatorapprequestID"] )
                                        $fstr1="<a target='blank' href='../../upfolder/propose/$file' ><img style = 'width: 30%;' src='../img/full_page.png' title='اسکن پیشنهاد' ></a>";
                                    
                                    
                                }
                            }
                            
                            
                            
?>                      


                        <tr>
                        
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcoTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["BankCode"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $ApplicantName; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($DesignArea,2); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="<?php echo $hide;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery['creditsource']."<br>".str_replace(' ', '&nbsp;', $resquery['creditsourcetitle']);
                            if ($Freestate>0) echo '<br>(اسناد خزانه اسلامی)'; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $shahrcityname ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastFehrestbaha"]/100000)/10;; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["price"]*10)/10;; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $fstr1.'<br>'.$resquery["operatorapprequestID"].strtotime($resquery["operatorapprequestSaveDate"]); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo gregorian_to_jalali($resquery["operatorapprequestSaveDate"]);; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["winstate"] ?></td>
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
