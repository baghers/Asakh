<?php 
/*
//insert/applicant_list.php
فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/states_detail.php
*/
//اتصال به دیتا بیس
require_once('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
require_once('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی امال صفحات 
require_once('../includes/elements.php');
//صفحاتی که این صفه از طریق انها این صفحه فراخوانی می شود
if (! $_POST)//در صورتی که دکمه ثبت کلیک نشده باشد
{
    $uid=$_GET["uid"];//شنایه ای که در آدرس صفحه با متد گت ارسال شده است
	$linearr = explode('^',$_GET["uid"]);//تفکیک آی دی های ارسال شده که با کاراکتر هت تفکیک شده و تبدیل به آرایه می شود
	$uid1=$linearr[0];//شناسه آدرس که در بازگت به صفحه قبل استفاده می شود
	$showm=$_GET["showm"];// تمام طر ها اعم از بایگانی نمایش داده شود
	$IDorder=$_GET["IDorder"];//شناسه آیتمی که لیست بر اساس آن مرتب شود
}
//$login_RolesID=17 شناسه نقش کاربر مقیم
//ناظر شهرستانی
//در صورتی که ناظر مقیم طرح را ثبت نماید شرکت مشاور طرا آن 67 که مشاور طراح خط تیره می باشد تعیین می شود
//اقای اسماعیلی یا انتزام
//زیرا طرح های مطالعاتی باید یک مشاور طراح داشته باشند که بعد از ارجاع طرح به استان توسط کاربر مدیریت پرونده ها مشاور طراح قطعی آن تعیین می شود
if ($login_RolesID==17 || $login_RolesID==26)
    $login_DesignerCoID='67';
//$criditType نوع اعتبار آن
//در صورتی که تجمیع باشد 1
//در غیر اینصورت غیر تجمیع
//تجمیع یعنی مجموعی از خرده مالکین
$criditType=0;
if ($_POST )//درصورتی که دکمه ثبت کلیک شده باشد
{
    //در صورتی که کاربر لاگین نکرده باشد یا جلسه کاری آن به پایان رسیده باشد
     if (!($login_userid>0)) header("Location: ../login.php");
     //$login_RolesID 19 شناسه مدیریت پرونده ها
     //$login_RolesID 24 نقشه بردار
     //$login_OperatorCoID شناسه پیمانکار لاگین شده
     //$login_DesignerCoID شناسه شرکت مشاور طراح لاگین شده
     //در صورتی که یکی از کاربران فوق نباشند از صفحه خارج می شود
      if ($login_RolesID<>24 && $login_RolesID<>19)
        if (!($login_OperatorCoID>0) && !($login_DesignerCoID>0) ) header("Location: ../login.php");
    //$_POST['sob'] شناسه شهرستان محل اجرای طرح
    if (!($_POST['sob']>0))
    {
            echo "شهرستان محل اجرای طرح را انتخاب نمایید";
            exit;
        } 
    $melicode= $_POST['melicode'];//کد/شناسه ملی بهره بردار
    //کد رهگیری از تهران اخذ شده
	//به استان 
    $BankCode1=trim($_POST['BankCode1']);//بخش اول کد رهگیری طرح
	//
    $BankCode2=trim($_POST['BankCode2']);//بخش دوم کد رهگیری طرح
	//
    $BankCode3=trim($_POST['BankCode3']);//بخش سوم کد رهگیری طرح
    $BankCode=trim("$BankCode1-$BankCode2-$BankCode3");
//login_OperatorCoID پیمانکار
    if ($login_OperatorCoID>0 || $login_DesignerCoID>0)//در صورتی که کاربر شرکت پیمانکار باشد یا مهندسین مشاور باید تکراری نبودن کد رهگیری چک شود
    {
        if ($login_OperatorCoID>0)//در صورتی که کاربر پیمانکار باشد
        {
            //در این پرس و جو تعداد طرح هایی استخراج می شود که شناسه پیمانکار آن پیمانکار وارد شده و کد رهگیری بزرگتر از صفر آن 
            //با کد رهگیری طرحی که مشغول ثبت آن می باشد برابر باشد
             $query = "SELECT count(*) cnt 
                     FROM applicantmaster 
                     where  ifnull(OperatorCoID,0)='$login_OperatorCoID'  
                     and BankCode='$BankCode' and ifnull(BankCode,0)>0 "; 
             try 
            {		
                $result = mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
             
        	$row = mysql_fetch_assoc($result);
            if ($row['cnt']>0)
            {
                echo "کد رهگیری وارد شده به طرح دیگری اختصاص داده شده است";
                exit;
            }           
        }

        else//در صورتی که کاربر مهندسین مشاور باشد
        {
            //در این پرس و جو طرح هایی استخراج می شود که کد رهگیری آن با کد رهگیری طر فعلی در حال ثبت برابر باشد
            $query = "SELECT count(*) cnt 
                 FROM applicantmaster 
                 where BankCode='$BankCode' and ifnull(BankCode,0)>0 ";
            
            try 
            {		
                $result = mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            
        	$row = mysql_fetch_assoc($result);
            if ($row['cnt']>0)
            {
                echo $query."<br>";
                echo "کد رهگیری وارد شده به طرح دیگری اختصاص داده شده است یا توسط نقشه بردار ثبت شده است";
                exit;
            }     
        } 
    }
    
    $SurveyArea=$_POST['SurveyArea'];//مساحت نقشه برداری شده
    $surveyDate=compelete_date($_POST['surveyDate']);//تاریخ نقشه برداری
    
    $numfield=$_POST['numfield'];//شماره پرونده
    $Code=$_POST['Code'];//سریال طرح
	$DesignArea=$_POST['DesignArea'];//مساحت قطعی طرح
	$Debi=$_POST['Debi'];//دبی طرح
	$ApplicantName=$_POST['ApplicantName'];//نام خانوادگی /مدیر عامل
	$ApplicantFName=$_POST['ApplicantFName'];// نام/ عنوان شرکت
	$DesignSystemGroupsID=$_POST['DesignSystemGroupsID'];//سیستم آبیاری طرح
	$CostPriceListMasterID=$_POST['CostPriceListMasterID'];//شناسه فهرست بهای آبیاری تحت فشار طرح
	$TransportCostTableMasterID=$_POST['TransportCostTableMasterID'];//شناسه جدول هزینه حمل طرح
	$RainDesignCostTableMasterID=$_POST['RainDesignCostTableMasterID'];//شناسه جدول هزینه های طراحی طرح های بارانی
	$DropDesignCostTableMasterID=$_POST['DropDesignCostTableMasterID'];//شناسه جدول هزینه های طراحی طرح های قطره ای
	$CityId=$_POST['sob'];//شناسه شهرستان طرح
	
    $_POST['private'] = $_POST['private'];//شخصی بودن طرح
    $private= $_POST['private'];

    //در صورتی که کاربر ناظر مقیم باشد طرح به عنوان غیر شخصی ثبت می شود	  
    if ($login_RolesID==17 || $login_RolesID==26)
        $private=0;
    //در صورتی که طرح تجمیع باشد مقدار یک می گیرد
    if ($_POST['criditType']=='on')  $criditType=1;

	
	$mobile=$_POST['mobile'];//تلفن همراه
    
    //ترکیب نام روستا محل صدور شناسنامه نام پدر تاریخ تولد و ش شناسنامه
	$CountyName="$_POST[CountyName]_$_POST[registerplace]_$_POST[fathername]_$_POST[birthdate]_$_POST[shenasnamecode]";
    
    $creditsourceID=0;//منبع تامین اعتبار
    
    if ($_POST['prjtypeid']==1)//در صورتی که نوع پروژه آبرسانی و انتقال آب باشد منبع آن کد 21 که بند دال آبرسانی می باشد  
    {
        $queryad = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='watersuplydefaultcreditsourceid' and ostan ='19' ";
        try 
            {		
                $resultad = mysql_query($queryad);
                $rowad = mysql_fetch_assoc($resultad);
                $watersuplydefaultcreditsourceid=$rowad['ValueInt'];
                $creditsourceID=$watersuplydefaultcreditsourceid; 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }        
    }

            
       
    
     
        
        
    $DesignerID=$_POST['DesignerID'];//شناسه طراح طرح
    $YearID=$_POST['YearID'];//سال طرح
    $SaveTime=date('Y-m-d H:i:s');//ساعت و تاریخ فعلی
    $SaveDate=date('Y-m-d');//تاریخ فعلی
    $ClerkID=$login_userid;//کاربر لاگین شده
    $DesignerCoIDnazer=0;
    if ($_POST['DesignerCoIDnazer']>0)
    $DesignerCoIDnazer=$_POST['DesignerCoIDnazer'];//مشاور ناظر طرح
    
    //در صورتی که کاربر لاگین نکرده باشد یا جلسه کاری آن به پایان رسیده باشد
     if (!($login_userid>0)) header("Location: ../login.php");
     
     //$login_RolesID 19 شناسه مدیریت پرونده ها
     //$login_RolesID 24 نقشه بردار
     //$login_OperatorCoID شناسه پیمانکار لاگین شده
     //$login_DesignerCoID شناسه شرکت مشاور طراح لاگین شده
     //در صورتی که یکی از کاربران فوق نباشند از صفحه خارج می شود
    if ($login_RolesID!=24 && $login_RolesID!=19 && !($login_OperatorCoID>0) && !($login_DesignerCoID>0))
    header("Location: ../login.php");
 
    //در صورتی که مدیریت پرونده ها یا نقشه بردار باشد اطلاعات محدود تری ثبت می شود
    /*
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
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    operatorcoid شناسه پیمانکار
    DesignerCoID شناسه مشاور طراح
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
    creditsourceID منبع تامین اعتبار طرح
    */
    if ($login_RolesID==24 || $login_RolesID==19)
    $query = "INSERT INTO applicantmaster(Debi,DesignArea,Code,BankCode,ApplicantName,ApplicantFName,SaveTime,SaveDate,ClerkID
    ,CityId,CountyName,private,numfield,criditType,ClerkIDsurveyor,YearID,mobile,melicode,SurveyArea,surveyDate,coef5,DesignerCoIDnazer,proposestatep,PriceListMasterID) 
    VALUES('$Debi','$SurveyArea','". $Code . "', '" . $BankCode . "', '" . $ApplicantName . "', '" . $ApplicantFName . 
    "','$SaveTime','$SaveDate','$ClerkID','$CityId','$CountyName','$private','$numfield','$criditType','$ClerkID','$YearID','$mobile','$melicode','$SurveyArea','$surveyDate',1,'$DesignerCoIDnazer',0,0);";
 else    
    $query = "INSERT INTO applicantmaster(DesignArea, Code, BankCode,Debi,ApplicantName,ApplicantFName,SaveTime,SaveDate,ClerkID,operatorcoid,DesignerCoID
    ,CostPriceListMasterID,DesignSystemGroupsID,TransportCostTableMasterID,RainDesignCostTableMasterID
    ,DropDesignCostTableMasterID,CityId,CountyName,private,criditType,DesignerID,YearID,mobile,melicode,
    StationNumber,XUTM1,YUTM1,SoilLimitation,coef5,DesignerCoIDnazer,creditsourceID,proposestatep,PriceListMasterID) VALUES('" .
            $DesignArea . "', '" . $Code . "', '" . $BankCode . "', '" . $Debi . "', '" . $ApplicantName . "', '" . $ApplicantFName . "'
            , '$SaveTime','$SaveDate','$ClerkID','$login_OperatorCoID','$login_DesignerCoID','$CostPriceListMasterID'
		 
            ,'$DesignSystemGroupsID','$TransportCostTableMasterID','$RainDesignCostTableMasterID','$DropDesignCostTableMasterID',
            '$CityId','$CountyName','$private','$criditType','$DesignerID','$YearID','$mobile','$melicode','$_POST[YUTM2]$_POST[StationNumber]',
            '$_POST[XUTM1]','$_POST[YUTM1]','$_POST[SoilLimitation]',1,'$DesignerCoIDnazer','$creditsourceID',0,0);";
    
    try 
        {		
            mysql_query($query);
          
          echo $query;exit;;
            //پرس و جوی استخراج شناسه طرح درج شده
            $query = "SELECT ApplicantMasterID FROM applicantmaster 
                        where ApplicantMasterID = last_insert_id()";
            try 
            {		
                $result = mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
    		
    		$row = mysql_fetch_assoc($result);
            $last_insert_ApplicantMasterID=$row['ApplicantMasterID'];
        
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
    $querytr="update applicantmasterdetail set prjtypeid='$_POST[prjtypeid]' where ApplicantMasterID='$last_insert_ApplicantMasterID';";
    $querytr.="INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,SaveTime,SaveDate,ClerkID) 
    VALUES( '$last_insert_ApplicantMasterID',1,23, '" .
     date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');
     
     ";
        //print $querytr;
    //exit;
    try 
        {		
           
           $coni=mysqli_connect($_server, $_server_user, $_server_pass,$_server_db);
            // Check connection
            if (mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            if (!mysqli_multi_query($coni,$querytr))
            {
                print "START TRANSACTION;".$querytr.";COMMIT;";
                exit;                   
            }
            mysqli_close($coni);
        }
        //catch exception
        catch(Exception $e) 
        {
             try 
            {		
                mysql_query("delete ROM applicantmaster where ApplicantMasterID = '$last_insert_ApplicantMasterID' ");
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
      
        
    //  print $query;
    if ($last_insert_ApplicantMasterID>0)//در صورتی که درج انجام شده باشد در جدول تغییر وضعیت یک ردیف وضعیت ثبت اولیه با کد 23 درج می شود
    {  
        /*
        appchangestate جدول تغییر وضعیت های انجام شده طرح
        ApplicantMasterID شناسه طرح
        stateno شماره ردیف وضعیت طرح
        applicantstatesID شناسه وضعیت طرح
        SaveTime زمان ثبت طرح
        SaveDate تاریخ ثبت طرح
        ClerkID شناسه کاربر طرح
        */
       
         
       
        
         
        //در صورتی که کاربر ناظر مقیم باشد و طرح انتقال آب باشد یک پیش فاکتور لوله برای آن ثبت می شود
        if ($login_RolesID==17 && ($_POST['prjtypeid']==1) )
        {
            /*
            invoicemaster جدول پیش فاکتورهای طرح
            ApplicantMasterID شناسه طرح
            ProducersID شناسه تولید کننده طرح
			//ProducersID =148 پیش فرض 
            Serial سریال
            Title عنوان
            Description توضیحات
            TransportCost هزینه حمل
            Discont تخفیف
            InvoiceDate تاریخ پیش فاکتور
            Rowcnt تعداد ردیف پیش فاکتور
            pricenotinrep مبلغ پیش فاکتور در هزینه های طرح لحاظ شود
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            taxless ارزش افزوده شامل نشود
            PriceListMasterID شناسه جدول لیست قیمت
            */
            
            $queryad = "SELECT ValueStr FROM supervisorcoderrquirement WHERE KeyStr ='watersuplydefaultinvoicedate' and ostan ='19' ";
            try 
            {		
                $resultad = mysql_query($queryad);
                $rowad = mysql_fetch_assoc($resultad);
                $watersuplydefaultinvoicedate=$rowad['ValueStr'];
                
                $query = "
                insert into invoicemaster (ApplicantMasterID,ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep
                ,SaveTime,SaveDate,ClerkID,taxless,PriceListMasterID)
                values('$row[ApplicantMasterID]',148,1,'pipe','',0,0,'$watersuplydefaultinvoicedate',5,0
                ,'" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."',0,25);";
        		     //print $query;exit;
                 mysql_query($query);
                 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            } 
            
            
            
            
        }
        echo "طرح مورد نظر با کد ثبت سامانه".$last_insert_ApplicantMasterID." با موفقیت ثبت شد";
    }
    
}

//ایجاد پرس و جوی لیست طرح های کاربر/////////////////////////////////////////////////////////////////////////////////////////////
    
    //کشاورز
    if ($login_RolesID==26) 
    $condition1=" where applicantstates.applicantstatesID in (23,59)  and melicode in (select clerk.melicode from clerk where clerk.ClerkID='$login_userid')"  ;
    else
    //در صورتی که کاربر ناظر مقیم باشد طرح های شهرستان مربوط به خود که در فاز مطالعاتی هستند را مشاهده می نماید
    if ($login_RolesID==17) $condition1=" where substring(applicantmaster.CityId,1,4)=substring('$login_CityId',1,4) and ifnull(applicantmaster.operatorcoid,0)=0";
    //در صورتی که کاربر مشاور طراح باشد طرح های شرکت خود را مشاهده می نماید									
    else if ($login_DesignerCoID>0) $condition1=" where applicantmaster.DesignerCoID='$login_DesignerCoID' and ifnull(applicantmaster.DesignerCoID,0)<>0";
    //در صورتی که کاربر پیمانکار باشد طرح های شرکت  خود را مشاهده می نماید
    else if ($login_OperatorCoID>0) $condition1=" where applicantmaster.operatorcoid='$login_OperatorCoID' and ifnull(applicantmaster.operatorcoid,0)<>0";
    //در صورتی که کاربر مدیریت پرونده ها باشد فقط طرح های ثبت اولیه ای که هنوز شرکت مشاور طراح و پیمانکار آن هنوز تعیین نشده است را مشاهده می کند
    else if ($login_RolesID==19) $condition1=" where applicantstates.applicantstatesID=23 and ifnull(operatorcoid,0)=0 and ifnull(designercoid,0)=0"  ;
    // در غیر این صورت بهره بردار است که طرح های با شناسه ملی خود را مشاهده می نماید که هنوز مشاور طراح آن تعیین نشده است
    
    //echo $condition1;exit;



    //پرس و جوی استخراج آخرین سریال ثبت شده برای هر کاربر که از طریق شروط فوق محدود شده است
    /*
	applicantmaster.Code سریال طرح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
    لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
    این جدول دارای ستون های ارتباطی زیر می باشد
    ApplicantMasterID شناسه طرح مطالعاتی
    ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
    prjtype جدول نوع پروژه ها
    
    */
    $query = "
    SELECT max(CAST(applicantmaster.Code AS UNSIGNED))+1 maxcode FROM applicantmaster
    left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
    applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
    applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
    left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0) $condition1";
    try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
		
        if ($result)
		$row = mysql_fetch_assoc($result);
		
        if ($row['maxcode']>0)//آخرین سریال ثبت شده به علاوه یک 
		  $maxcode = $row['maxcode'];
        else $maxcode = 1;


 
 switch ($IDorder) //مرتب سازی پرس و جو
  {
    case 1: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;//نوع پروژه و عنوان پروژه 
    case 2: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.ApplicantFName COLLATE utf8_persian_ci'; break;//نوع پروژه و عنوان اول پروژه
    case 3: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.DesignArea'; break;//نوع پروژه و مساحت
	case 4: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.Debi'; break;//نوع پروژه و دبی
    case 5: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),shahrcityname COLLATE utf8_persian_ci'; break;//نوع پروژه و نام شهر
    case 6: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),designername COLLATE utf8_persian_ci'; break;//نوع پروژه و نام طراح
    case 7: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantstatestitle COLLATE utf8_persian_ci'; break;//نوع پروژه و عنوان وضعیت طرح
    case 8: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.TMDate,applicantmaster.ApplicantMasterID'; break;//نوع پروژه و تاریخ آخرین تغییر وضعیت طرح و شناسه طرح
    case 9: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),applicantmaster.ApplicantMasterID '; break;//نوع پروژه و شناسه طرح
    case 10: $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),cast(applicantmaster.Code as decimal)'; break;//نوع پروژه و سریال طرح
    default: //نوع پروژه و نام شهر و سریال طرح و تاریخ ثبت طرح
        $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),shahrcityname COLLATE utf8_persian_ci,cast(applicantmaster.Code as decimal),applicantmaster.savetime '; break; 
  }
 
 
 $opbbc="";
 //در پرس و جوی زیر می خواهیم کد رهگیری های مجازی که پیمانکار امکان ثبت آنرا دارد استخراج کرده و تنها اجازه دهیم پیمانکار این کد رهگیری ها را ثبت نماید
 if ($login_OperatorCoID>0)//در صورتی که پیمانکار وارد شده باشد
 {
    /*
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
    لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
    این جدول دارای ستون های ارتباطی زیر می باشد
    ApplicantMasterID شناسه طرح مطالعاتی
    ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
    prjtype جدول نوع پروژه ها
    operatorapprequest جدول پیشنهاد قیمت اجرای طرح
    BankCode کد رهگیری طرح
    ApplicantMasterID شناسه طرح
    state=1 انتخاب شدن پیشنهاد توسط کشاورز
    operatorcoID شناسه پیمانکار
    
    where ifnull(applicantmasterdetail.ApplicantMasterIDmaster,0)=0 اینکه طرح اجرایی هنوز ثبت نشده باشد
    */
    $que1="select applicantmaster.BankCode from applicantmasterdetail
    inner join applicantmaster on applicantmaster.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID and 
    operatorapprequest.state=1 and operatorapprequest.operatorcoID='$login_OperatorCoID'
    where ifnull(applicantmasterdetail.ApplicantMasterIDmaster,0)=0";  
    $result1 = mysql_query($que1);
    while($row1 = mysql_fetch_assoc($result1))
    {
        if ($opbbc=='')
            $opbbc=$row1['BankCode'];
        else 
            $opbbc.="_".$row1['BankCode'];
    }     
 }
//print $opbbc;
if ($login_RolesID==17 || $login_RolesID==26) // در صورتی که کاربر لاگین شده ناظر مقیم باشد
/*
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
freestateid شناسه مرحله آزادسازی
yearcost.Value سال فهرست بهای آبیاری تحت فشار
applicantstatestitle عنوان وضعیت طرح
applicantstatesID شناسه وضعیت طرح
errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
RoleID نقش کاربر ثبت کننده جدول زمانبندی
emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
ostancityname نام استان طرح
shahrcityname نام شهر طرح
bakhshcityname نام بخش طرح
privatetitle شخصی بودن طرح
prjtypetitle عنوان نوع پروژه
prjtypeid شناسه نوع پروژه
RolesID نقش کاربر
applicantstatesID شناسه وضعیت طرح
applicantstates جدول تغییر وضعیت های طرح
costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
year جدول سال ها
YearID شناسه سال طرح
tax_tbcity7digit جدول شهرهای مختلف
applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
applicanttiming جدول زمانبندی اجرای طرح
*/
$sql = "SELECT applicantmaster.*,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
,applicantfreedetail.freestateid ,yearcost.Value fb
,applicantstates.title applicantstatestitle,applicantmaster.applicantstatesID 
,applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz
,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,case private when 1 then 'شخصی' else '' end privatetitle
,prjtype.title prjtypetitle,applicantmasterdetail.prjtypeid
FROM applicantmaster 

inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID 
and (applicantstates.RolesID in ($login_RolesID) or (applicantstates.applicantstatesID=23 and ifnull(applicantmaster.private,0)=0))

left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join (select max(freestateid) freestateid,ApplicantMasterID from applicantfreedetail where freestateid=142 group by ApplicantMasterID)
 applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster
left outer join (select max(errnum) errnum,max(emtiaz) emtiaz,10 RoleID,ApplicantMasterID from applicanttiming where RoleID='10' group by ApplicantMasterID ) applicanttiming on applicanttiming.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster

left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)


$condition1 
$orderby ;";
else if ($login_RolesID==2)//در صورتی که کاربر لاگین شده پیمانکار باشد
/*
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
freestateid شناسه مرحله آزادسازی
yearcost.Value سال فهرست بهای آبیاری تحت فشار
applicantstatestitle عنوان وضعیت طرح
applicantstatesID شناسه وضعیت طرح
errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
RoleID نقش کاربر ثبت کننده جدول زمانبندی
emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
ostancityname نام استان طرح
shahrcityname نام شهر طرح
bakhshcityname نام بخش طرح
privatetitle شخصی بودن طرح
prjtypetitle عنوان نوع پروژه
prjtypeid شناسه نوع پروژه
RolesID نقش کاربر
applicantstatesID شناسه وضعیت طرح
applicantstates جدول تغییر وضعیت های طرح
costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
year جدول سال ها
YearID شناسه سال طرح
tax_tbcity7digit جدول شهرهای مختلف
applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
applicanttiming جدول زمانبندی اجرای طرح
applicantstates.RolesID=2 نقش پیمانکار باشد
*/
$sql = "SELECT applicantmaster.*,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
,applicantfreedetail.freestateid ,yearcost.Value fb
,applicantstates.title applicantstatestitle,applicantmaster.applicantstatesID 
,applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz
,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,case private when 1 then 'شخصی' else '' end privatetitle
,prjtype.title prjtypetitle,applicantmasterdetail.prjtypeid
FROM applicantmaster 
inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID 
and (applicantstates.RolesID=2 or applicantstates.applicantstatesID=23 )
left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join (select max(freestateid) freestateid,ApplicantMasterID from applicantfreedetail where freestateid=142 group by ApplicantMasterID)
 applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster
left outer join (select max(errnum) errnum,max(emtiaz) emtiaz,10 RoleID,ApplicantMasterID from applicanttiming where RoleID='10' group by ApplicantMasterID ) applicanttiming on applicanttiming.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster

left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)


$condition1 
$orderby ;";

else
/*
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
freestateid شناسه مرحله آزادسازی
yearcost.Value سال فهرست بهای آبیاری تحت فشار
applicantstatestitle عنوان وضعیت طرح
applicantstatesID شناسه وضعیت طرح
errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
RoleID نقش کاربر ثبت کننده جدول زمانبندی
emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
ostancityname نام استان طرح
shahrcityname نام شهر طرح
bakhshcityname نام بخش طرح
privatetitle شخصی بودن طرح
prjtypetitle عنوان نوع پروژه
prjtypeid شناسه نوع پروژه
RolesID نقش کاربر
applicantstatesID شناسه وضعیت طرح
applicantstates جدول تغییر وضعیت های طرح
costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
year جدول سال ها
YearID شناسه سال طرح
tax_tbcity7digit جدول شهرهای مختلف
applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
applicanttiming جدول زمانبندی اجرای طرح
applicantmaster.applicantstatesID =46 وضعیت طرح م ج شهرستان به کارشناس باشد
*/
$sql = "SELECT applicantmaster.*,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
,applicantfreedetail.freestateid ,yearcost.Value fb
,applicantstates.title applicantstatestitle,applicantmaster.applicantstatesID 
,applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz
,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,case private when 1 then 'شخصی' else '' end privatetitle
,prjtype.title prjtypetitle,applicantmasterdetail.prjtypeid
FROM applicantmaster 

inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID 
and (applicantstates.RolesID in ('$login_RolesID') or (applicantmaster.applicantstatesID =23 
and (applicantmaster.operatorcoid>0 or substring(applicantmaster.cityid,1,2)!=19))
or (applicantmaster.applicantstatesID =46 and applicantmaster.DesignerCoID>0))

left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join (select max(freestateid) freestateid,ApplicantMasterID from applicantfreedetail where freestateid=142 group by ApplicantMasterID)
 applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster
left outer join (select max(errnum) errnum,max(emtiaz) emtiaz,10 RoleID,ApplicantMasterID from applicanttiming where RoleID='10' group by ApplicantMasterID ) applicanttiming on applicanttiming.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster

left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)


$condition1 
$orderby ;";
//print $sql;

try 
        {		
            $result = mysql_query($sql);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }


 
 
?>
<style>


.f14_font{
	border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:14pt;line-height:95%;font-weight: bold;font-family:'B lotus';                        
}
.f12_font{
	border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12pt;line-height:95%;font-weight: bold;font-family:'B lotus';                        
}
.f11_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:11pt;line-height:95%;font-weight: bold;font-family:'B lotus';                           
  }
.f10_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:9pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                           
  }
	
</style>

<!DOCTYPE html>
<html>
<head>
  	<title>لیست طرح ها</title>
<meta http-equiv="X-Frame-Options" content="deny" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script> <!--jQuery JavaScript Library https://jquery.com/download/ -->
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script><!--A simple queue for your ajax requests in jQuery. https://jquery.com/download/ -->
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script><!--Custom fork of the popular jQuery modal box, lightbox, etc extension. https://jquery.com/download/ -->
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script><!--Custom fork of the popular jQuery modal box, lightbox, etc extension. http://jquery.com/demo/thickbox/ -->
<script type='text/javascript' src='../jquery.autocomplete.js'></script><!--Ajax Autocomplete for jQuery allows you to easily create autocomplete/autosuggest boxes for text input fields. https://jquery.com/download/ -->

<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
<style>
	.tabledata tr {
	height:35px;
}



</style>

    <!-- /scripts -->
    
    <script >
 
 
function include(arr, obj) {
    for(var i=0; i<arr.length; i++) {
        if (arr[i] == obj) return true;
    }
    return false;
}
               
    function removeadditional()//این تابع چک می کند در صورتی که آیتم های کومبو باکس یک مورد باشد آنرا نشان نمی دهد و آن یک آیتم پیش فرض انتخاب می شود
    {   
        if ($('#TransportCostTableMasterID').length > 0)//شناسه جدول هزینه حمل طرح
        if (document.getElementById("TransportCostTableMasterID").length==2)
        {
            document.getElementById("TransportCostTableMasterID").selectedIndex=1;  
            $('#divTransportCostTableMasterID').hide();
            $('#TransportCostTableMasterIDlbl').hide();
        }
        if ($('#RainDesignCostTableMasterID').length > 0)//شناسه جدول هزینه های طراحی طرح های بارانی
        if (document.getElementById("RainDesignCostTableMasterID").length==2)
        {
            document.getElementById("RainDesignCostTableMasterID").selectedIndex=1;  
            $('#divRainDesignCostTableMasterID').hide();
            $('#RainDesignCostTableMasterIDlbl').hide();
        }
        if ($('#DropDesignCostTableMasterID').length > 0)//شناسه جدول هزینه های طراحی طرح های قطره ای
        if (document.getElementById("DropDesignCostTableMasterID").length==2)
        {
            document.getElementById("DropDesignCostTableMasterID").selectedIndex=1;  
            $('#divDropDesignCostTableMasterID').hide();
            $('#DropDesignCostTableMasterIDlbl').hide();
        }
        if ($('#CostPriceListMasterID').length > 0)//فهرست بهای آبیاری تحت فشار
        if (document.getElementById("CostPriceListMasterID").length==2)
        {
            document.getElementById("CostPriceListMasterID").selectedIndex=1;  
            $('#divCostPriceListMasterID').hide();
            $('#CostPriceListMasterIDlbl').hide();
        }
        if ($('#soo').length > 0)//شهر
        if (document.getElementById("soo").length==2)
        {
            document.getElementById("soo").selectedIndex=1;  
            $('#divsoo').hide();
            if ($('#soolbl').length > 0)
                $('#soolbl').hide();
            document.getElementById("soo").onchange();
        }
        if ($('#YearID').length > 0)//سال
        if (document.getElementById("YearID").length==2)
        {
            document.getElementById("YearID").selectedIndex=1;  
            $('#divYearID').hide();
            $('#YearIDlbl').hide();
        }
        if ($('#DesignerID').length > 0)//طراح
        if (document.getElementById("DesignerID").length==2)
        {
            document.getElementById("DesignerID").selectedIndex=1;  
            $('#divDesignerID').hide();
            $('#DesignerIDlbl').hide();
        }
        if ($('#DesignSystemGroupsID').length > 0)//سیستم آبیاری تحت فشار
        if (document.getElementById("DesignSystemGroupsID").length==2)
        {
            document.getElementById("DesignSystemGroupsID").selectedIndex=1;  
            $('#divDesignSystemGroupsID').hide();
            $('#DesignSystemGroupsIDlbl').hide();
        }
        if ($('#prjtypeid').length > 0)//شناسه نوع پروژه
        if (document.getElementById("prjtypeid").length==3)
        {
            document.getElementById("prjtypeid").selectedIndex=1;  
            //$('#divprjtypeid').hide();
            //$('#prjtypeidlbl').hide();
        }
         if ($('#SoilLimitation').length > 0)//محدودیت خاک
        if (document.getElementById("SoilLimitation").length==2)
        {
            document.getElementById("SoilLimitation").selectedIndex=1;  
            $('#divSoilLimitation').hide();
            $('#SoilLimitationlbl').hide();
        }              
    }
    

function FilterComboboxes(Url,Tabindex)//فیلتر کردن کومبو باکس جدول هزینه های حمل ب اساس فهرست بهای آبیاری تحت فشار
{ 
    //alert(1);
    var type=2;
    var selectedCostPriceListMasterID;//شناسه فهرست بهای آبیاری تحت فشار
    //alert(<?php print $login_ostanId; ?>);
    if ($('#CostPriceListMasterID').length > 0)
        selectedCostPriceListMasterID=document.getElementById('CostPriceListMasterID').value;
    if (selectedCostPriceListMasterID>0)
    selectedCostPriceListMasterID=selectedCostPriceListMasterID;
    else
    selectedCostPriceListMasterID=0;
    
    var selectedsoo=document.getElementById('soo').value;//شناسه شهر طرح
    $.post(Url, {type:type,selectedsoo:selectedsoo,ostanid:<?php print $login_ostanId; ?>,selectedCostPriceListMasterID:selectedCostPriceListMasterID}, function(data){
    //alert (data.val2);
           
               
           if ($('#divTransportCostTableMasterID').length > 0)// جدول هزینه حمل طرح
           {
            if (selectedCostPriceListMasterID>0)
	           $('#divTransportCostTableMasterID').html(data.val2);
           }
       }, 'json');                      
}
function FilterComboboxes2(Url,Tabindex)//فیلتر بخش های یک شهرستان
{ 
    var type=3;
    //alert('<?php print $login_ostanId; ?>');
    var selectedsoo=document.getElementById('soo').value;//شناسه استان
    var selectedsos=document.getElementById('sos').value;//شناسه شهرستان
    <?php if($login_RolesID==17 || $login_RolesID==26) echo 'selectedsos='.$login_CityId;?>
    //alert(Url);
    
    $.post(Url, {type:type,selectedsoo:selectedsoo,ostanid:<?php print $login_ostanId; ?>,selectedsos:selectedsos}, function(data){
    //alert (data.val1);
           
    $('#divsos').html(data.val0);//کومبوباکس شهرستان
    $('#divsob').html(data.val1);//کومبوباکس بخش ها
               
          
       }, 'json');                      
}

function FilterComboboxes3(Url,Tabindex)//با توجه به شناسه ملی سایر اطلاعات مثا نام و نام خانوادگی و... استخراج می شود
{ 
    var type=1;
    var melicode=document.getElementById('melicode').value;//کد/شناسه ملی
    $.post(Url, {type:type,melicode:melicode}, function(data){
        if (!(data.val2>0))
            alert('کد/شناسه ملی یافت نشد. لطفا از منوی ثبت کشاورز مشخصات متقاضی را ثبت نمایید');
        else
        {
            //alert (data.val0);
            document.getElementById('ApplicantFName').value=data.val0;// نام/ عنوان شرکت
            document.getElementById('ApplicantName').value=data.val1;//نام خانوادگی /مدیر عامل
            //document.getElementById('shenasnamecode').value=data.val2;//کد ملی/ شناسه ملی
            document.getElementById('registerplace').value=data.val3;// تاریخ تولد/تاریخ تاسیس
            document.getElementById('fathername').value=data.val4;//نام پدر/نماینده شرکت
            document.getElementById('birthdate').value=data.val5;//تاریخ تولد/تاریخ تاسیس
            document.getElementById('mobile').value=data.val6;//همراه             
        }
   
       }, 'json');                      
}
function CheckForm()//کنترل های قبل از ثبت فرم
{
    if('<?php 
    //در صورتی که تعداد پروژه های عدم تحویل موقت یا دائم پروژه بیشتر از 6 تا باشد امکان ثبت طرح جدید برای پیمانکار وجود ندارد
    if ($login_Codeop2>=6) echo 1; else echo 0;?>'=='1')
    {
        alert('به دلیل عدم تحویل موقت یا دائم پروژه ها امکان ثبت طرح جدید فراهم نمی باشد.!');return false;
    }
    
    
    var str1='<?php echo $opbbc;?>';//کد های رهگیری قابل ثبت برای پیمانکار که با زیر خط جدا شده اند
    var res = str1.split('_');
    //کد رهگیری طرح فعلی
    var neddle=document.getElementById('BankCode1').value+'-'+document.getElementById('BankCode2').value+'-'+document.getElementById('BankCode3').value;
            
    if (include(res,neddle))//اگر کد رهگیری فعلی در کد های رهگیری قابل ثبت پیمانکار باشد
    {
        
    }
    else if ('1'=='<?php if ($login_OperatorCoID>0) echo 1; else echo 0;  ?>')//این شرکت منتخب پیشنهاد قیمت پروژه با کد رهگیری وارد شده نمی باشد
        {
            
            alert ('این شرکت منتخب پیشنهاد قیمت پروژه با کد رهگیری وارد شده نمی باشد');return false;
        }
    if ($('#DesignerCoIDnazer').length > 0)//بازبین
    if (!(document.getElementById('DesignerCoIDnazer').value>0))
    {
        alert('بازبین پروژه را مشخص نمایید.!');return false;
    } 
        
        
    if ($('#ApplicantFName').length > 0)//نام متقاضی
    if (!(document.getElementById('ApplicantFName').value.length>0))
    {
        alert('نام متقاضی را وارد نمایید!');return false;
    }    
    if ($('#ApplicantName').length > 0)//نام خانوادگی
    if (!(document.getElementById('ApplicantName').value.length>0))
    {
        alert('نام خانوادگی متقاضی را وارد نمایید!');return false;
    }
    if ($('#DesignArea').length > 0)//مساحت
    if (!(document.getElementById('DesignArea').value.length>0))
    {
        alert("مساحت یا متراژ طرح را وارد نمایید!");return false;
    }
    if ($('#Debi').length > 0)//دبی
    if (!(document.getElementById('Debi').value.length>0))
    {
        alert('دبی طرح را وارد نمایید!');return false;
    }
    if ($('#soo').length > 0)//استان
    if (!(document.getElementById('soo').value>0))
    {
        alert('استان طرح را وارد نمایید!');return false;
    }
    if ($('#sos').length > 0)//شهرستان
    if (!(document.getElementById('sos').value>0))
    {
        alert('شهرستان طرح را وارد نمایید!');return false;
    }
    
    if ($('#sob').length > 0)//شهر/بخش
    if (!(document.getElementById('sob').value>0))
    {
        alert('شهر/بخش طرح را وارد نمایید!');return false;
    }
    
    if ($('#CountyName').length > 0)//روستا
    if (!(document.getElementById('CountyName').value.length>0))
    {
        alert('روستای طرح را وارد نمایید!');return false;
    }
    
	if ($('#BankCode1').length > 0)// بخش اول كد رهگيري
    if (!(document.getElementById('BankCode1').value.length>0) && !(document.getElementById('login_OperatorCoID').value>0))
    {
        alert('لطفا بخش اول كد رهگيري را وارد نماييد!');return false;
    }
    if ($('#BankCode2').length > 0)// بخش اول دوم رهگيري
    if (!(document.getElementById('BankCode2').value.length>0) && !(document.getElementById('login_OperatorCoID').value>0))
    {
        alert('لطفا بخش دوم كد رهگيري را وارد نماييد!');return false;
    }
    if ($('#BankCode3').length > 0)// بخش سوم كد رهگيري
    if (!(document.getElementById('BankCode3').value.length>0) && !(document.getElementById('login_OperatorCoID').value>0))
    {
        alert('لطفا بخش سوم كد رهگيري را وارد نماييد!');return false;
    }
        
        
    if ($('#XUTM1').length > 0)//XUTM1
    if (!(document.getElementById('XUTM1').value>0))
    {
        alert('لطفا XUTM1 را وارد نماييد');return false;
    }
    if ($('#YUTM2').length > 0)//YUTM2
    if (!(document.getElementById('YUTM2').value>0))
    {
        alert('لطفا YUTM2 را وارد نماييد');return false;
    }
    if ($('#YUTM1').length > 0)//YUTM1
    if (!(document.getElementById('YUTM1').value>0))
    {
        alert('لطفا YUTM1 را وارد نماييد!');return false;
    }	
  
    if ($('#CostPriceListMasterID').length > 0)//فهرست بهای طرح
    if (!(document.getElementById('CostPriceListMasterID').value>0))
    {
        alert('فهرست بهای طرح را وارد نمایید!');return false;
    }
    
    if ($('#DesignSystemGroupsID').length > 0)//سیستم آبیاری
    if (!(document.getElementById('DesignSystemGroupsID').value>0) && !(document.getElementById('DesignSystemGroupsID').value==-1))
    {
        alert('سیستم آبیاری طرح را وارد نمایید!');return false;
    }
    
    if ($('#TransportCostTableMasterID').length > 0)// جدول ضرایب 
    if (!(document.getElementById('TransportCostTableMasterID').value>0))
    {
        alert('جدول جدول ضرایب (حمل،تجهیز و...)  طرح را وارد نمایید!');return false;
    }
    if ($('#RainDesignCostTableMasterID').length > 0)//جدول حق الزحمه طراحی بارانی
    if (!(document.getElementById('RainDesignCostTableMasterID').value>0))
    {
        alert('جدول حق الزحمه طراحی بارانی طرح را وارد نمایید!');return false;
    }
    if ($('#DropDesignCostTableMasterID').length > 0)//جدول حق الزحمه طراحی قطره ای/تلفیقی
    if (!(document.getElementById('DropDesignCostTableMasterID').value>0))
    {
        alert('جدول حق الزحمه طراحی قطره ای/تلفیقی طرح را وارد نمایید!');return false;
    } 
    if ($('#DesignerID').length > 0)// کارشناس طراح
    if (!(document.getElementById('DesignerID').value>0))
    {
        alert('لطفا کارشناس طراح را وارد نمایید!');return false;
    }    
    
  return true;
}
  function checkchange()//در صورتی که کومبوباکس ترتیب تغییر کند لیست بر اساس آیتم جدید مرتب می شود و صفحه رفرش می شود
  {
		   if (document.getElementById('showm').checked)
			{
				if (document.getElementById('IDorder').value>0)
				{
			    window.location.href =document.getElementById('uid1').value +'&showm=1' +'&IDorder=' +document.getElementById('IDorder').value;
				}
		    	else
				{
				window.location.href =document.getElementById('uid1').value +'&showm=1' ;
				}
			}		
			else 
			{	 		
				var uid =document.getElementById('uid1').value;
				
				if (document.getElementById('IDorder').value>0)
				{
			     window.location.href =document.getElementById('uid1').value +'&IDorder=' +document.getElementById('IDorder').value;
				}
		    	else
				{
			    window.location.href =document.getElementById('uid1').value;
				}
			}	
  }
	
function showhidediv(id)//فعال و غیر فعال نمودن باکس های مشخصات
{
    var elem = document.getElementById(id);
    if(elem.style.display=='none')
    {
        elem.style.display='';
   	    document.getElementById('i'+id).style.color='blue';
		document.getElementById('i'+id).style.height = '40px';

    }
    else
    {
        elem.style.display='none';
	    document.getElementById('i'+id).style.color='';
		document.getElementById('i'+id).style.height = '';
    }
    
}

    </script>

    
</head>
<body onload="removeadditional();"><!-- این تابع چک می کند در صورتی که آیتم های کومبو باکس یک مورد باشد آنرا نشان نمی دهد و آن یک آیتم پیش فرض انتخاب می شود -->

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php require_once('../includes/top.php'); 
            
            
            ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php require_once('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php require_once('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			
			<!-- content -->
			<div id="content">
            <br/><br/>
            <form action="applicant_list.php" method="post" onSubmit="return CheckForm()">
              <?php require_once('../includes/csrf_pag.php'); ?>
                <table width="100%" align="center" class="tabledata">
                    <tbody>
                          
               <?php
               //$ostcod قسمت اول کد رهگیری
               //قسمت اول کد رهگیری یک عدد مرتبط با استان می باشد که برای استان خراسان رضوی 30 می باشد
			   if  ($login_ostanId==19 && $login_RolesID!=17) $ostcod=30; 
                    else if ($login_ostanId==21) $ostcod=31; //استان خراسان شمالی قسمت اول کد رهگیری 31 می باشد
                        else if ($login_ostanId==31) $ostcod=29;//استان خراسان جنوبی قسمت اول کد رهگیری 29 می باشد
			   /*
               $login_RolesID=25 نقش مدیر نقشه برداری
               $login_ostanId=19 کاربر لاگین شده از استان خراسان رضوی  می باشد
               $login_RolesID=9 نقش کاربر مهندسین مشاور
               $login_RolesID=10 نقش مدیر مهندسین مشاور
               $login_RolesID=119 نقش مدیریت پرونده ها
               کاربر در صورتی که مدیر نقشه برداری نباشد و کار بر و مدیر مهندسین مشاور و مدیریت پرونده ها نباشد امکان ثبت طرح فراهم می باشد
               */
if ($login_RolesID<>25 && (($login_ostanId!=19 || ($login_RolesID<>9 && $login_RolesID<>10) ) ) ){
if ($login_RolesID<>119){
    
                   
                    if ($login_userid>0)//درصورتی که کاربر لاگین نموده و جلسه کاری آن به پایان نرسیده باشد
                    {
                        /*
                        //Code سريال
                        //melicode کد/شناسه ملي
                        //ApplicantFName نام/ عنوان شرکت
                        //ApplicantName نام خانوادگی /مدیر عامل
                        //shenasnamecode شماره شناسنامه/ثبت
                        //fathername نام پدر/نماینده شرکت
                        //birthdate تاریخ تولد/تاریخ تاسیس
                        //registerplace محل صدور/ثبت
                        //Mobile تلفن همراه
                        */
                        print "
			<table width='100%' >
					 <tr><td colspan=10 class='f12_fontsb'
			         onclick=\"showhidediv('moteghazi');\"> مشخصات متقاضی:</td></tr>
			</table>
			<table id='moteghazi' > 
	
	
                     <tr>
                      <td colspan='15' class='data'>سريال:
                      <input
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 35px\"
                             name='Code' type='text' class='textbox' id='Code' value='$maxcode'   />
                      کد/شناسه ملي:
                      <input
                      onblur = \"FilterComboboxes3('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\" 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='melicode' type='text' class='textbox' id='melicode' size='15' maxlength='50' pattern=\"[0-9]{1,2}[0-9]{9}\" title=\"(10 رقم)\" required />
					  نام :
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='ApplicantFName' type='text' class='textbox' id='ApplicantFName'      size='15' maxlength='50' />
                      عنوان فاز:
                      <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='ApplicantName' type='text' class='textbox' id='ApplicantName'    size='15' maxlength='50' />
					  
                      شماره شناسنامه/ثبت:
                      <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='shenasnamecode' type='text' class='textbox' id='shenasnamecode' size='15' maxlength='50'  required />
					  
                      محل صدور/ثبت:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='registerplace' type='text' class='textbox' id='registerplace' size='15' maxlength='50'  required />
					  
                      نام پدر:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='fathername' type='text' class='textbox' id='fathername' size='15' maxlength='50'  required />
					  
                      تاریخ تولد:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='birthdate' type='text' class='textbox' id='birthdate' size='15' maxlength='50'  required />
					  
                      تلفن همراه:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='mobile' type='text' class='textbox' id='mobile' size='16' maxlength='50' /></td>
			</tr>
					  <tr>
					  
		</table>";
                   

		    
        
		echo "<table width='100%' >
					 <tr><td colspan=10 class='f12_fontsb'
			         onclick=\"showhidediv('project');\">محل پروژه:</td></tr>
			</table>
			<table id='project' > 
						  
			     "; 
				  /*
                  soo استان
                  sos دشت/شهرستان
                  CountyName روستا
                  Debi دبی
                  */
                  
                    $query="select id _value,substring(CityName,7) _key from tax_tbcity7digit where substring(id,3,5)='00000' and  substring(id,1,2)=substring('$login_CityId',1,2) order by _key  COLLATE utf8_persian_ci";
    				 $ID = get_key_value_from_query_into_array($query);
					 
                     print "<td id='soolbl' >استان:".select_option('soo','',',',$ID,0,'','','1','rtl',0,'',0,"onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'135')
                     ;
                      
                     print 
                     "".select_option('sos','دشت/شهرستان:',',',array(),0,'','','1','rtl',0,'',0,
                      "onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'135').
                     select_option('sob','شهر/بخش:',',',array(),0,'','','1','rtl',0,'',0,'','75').
                     " </td > 
                    <td   class='label'>روستا: </td>
                     <td colspan=3><input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='CountyName' type='text' class='textbox' id='CountyName'    /></td>
                     
                    </tr><tr>
					
		</table>
						
				<table width='100%' >
					 <tr><td colspan=10 class='f12_fontsb'
			         onclick=\"showhidediv('projec');\">مشخصات پروژه:</td></tr>
			</table>
			<table id='projec' > 
			
                     <td class='data'>
                     دبی L/s:
                      <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='Debi' type='text' class='textbox' id='Debi'    /></td>
           		      
                     ";
                 
				 //در صورتی که ناظر مقیم باشد و واحد طرح های آبرسانی طول می باشد متراژ خواهیم داشت و در غیر اینصورت مساحت
				 if($login_RolesID==17) $heklbl='هکتار/متراژ'; else $heklbl='مساحت (هکتار)'; 
                 //DesignArea مساحت/متراژ
                 print "
					  <td colspan='4' class='data'>$heklbl:
					 <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='DesignArea' type='text' class='textbox' id='DesignArea'   /></td>
                      ";
				/*
                $login_RolesID=24 نقش مدیر نقشه برداری
                $login_RolesID=19 نقش مدیریت پرونده ها
                
                SurveyArea مساحت
                BankCode1 بخش اول کد رهگیری
                BankCode2 بخش دوم کد رهگیری
                BankCode3 بخش سوم کد رهگیری
                */
                 if ($login_RolesID==24 || $login_RolesID==19)
                 {
                    print "
					  <td   class='label'>مساحت (هکتار):</td>
					 <td class='data'><input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='SurveyArea' type='text' class='textbox' id='SurveyArea'   /></td>
           		      
                      
					  <td   class='label'>کد رهگیری:</td>
                      
                      <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode3'  min='1' max='99' type='number' class='textbox' id='BankCode3'    />
                       
                       -
                      <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 90px\"
                       name='BankCode2' min='1' max='9999999999' type='number' class='textbox' id='BankCode2'    />
                       -
                       <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode1' min='25' max='99' type='number' class='textbox' id='BankCode1'   value='$ostcod' />
                       
                       </td>
                      ";
                    
					  
                 }
                  }
                 if ($login_userid>0)
                 {
                 	 if($login_RolesID==17)//در صورتی که نقش ناظ مقیم باشد  به دلیل آینکه فقط طرح های آبرسانی را ثبت می کند نوع سیستم فقط قطره ای بارانی می باشد
                     
					 $query="SELECT -1 _value, 'قطره اي/ باراني' _key";
                        else
                        //designsystemgroups جدول سیستم های مختلف آبیاری
                        //DesignSystemGroupsID شناسه سیستم های مختلف آبیاری
					 $query="SELECT DesignSystemGroupsID AS _value, Title AS _key
						FROM designsystemgroups
						WHERE DesignSystemGroupsID <>4
						UNION ALL SELECT -1 _value, 'قطره اي/ باراني' _key";
    				 $ID = get_key_value_from_query_into_array($query);
                     //DesignSystemGroupsID سیستم آبیاری
                     
                     print "<td id='DesignSystemGroupsIDlbl'  class='label'>سیستم آبیاری:</td>".
                     select_option('DesignSystemGroupsID','',',',$ID,0,'','','1','rtl',0,'',0,'','100');
                    /*
                    BankCode1 بخش اول کد رهگیری
                    BankCode2 بخش دوم کد رهگیری
                    BankCode3 بخش سوم کد رهگیری
                    */
                    print"
					        <td   class='label'>کد رهگیری:</td>
                      <td class='data' colspan='1'>
                      <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode3' min='1' max='99' type='number'  class='textbox' id='BankCode3'    />
                       
                      -
                      <input style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 90px\"
                       name='BankCode2' min='1' max='9999999999' type='number' class='textbox' id='BankCode2'    />
                       -
                       <input style = \";
border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode1' min='25' max='99' type='number' class='textbox' id='BankCode1'  value='$ostcod'  />
                        
						
                       </td>
                     
                     
                         
                ";
                /*
                year جدول سال ها
                cityquota جدول سهمیه های شهرستانی
                YearID شناسه سال
                Value عنوان سال
                */
				     $query="SELECT distinct YearID as _value,Value as _key FROM `year`
                     where YearID=(select max(YearID) from cityquota)
                     ORDER BY year.Value DESC";
    			//YearID شناسه سال
                    $ID = get_key_value_from_query_into_array($query);
                     print "<td id='YearIDlbl'  class='label'>سهمیه شهرستانی:</td>".
                     select_option('YearID','',',',$ID,0,'','','1','rtl',0,'',0,'','80').
					"         
		   ";
                 /*
                 private شخصی بودن طرح
                 criditType تجمیع بودن طرح
                 */   
                     
                      echo " طرح شخصی 
                      <input name='private' type='checkbox' id='private'  value='1' $private /></td>";
                
                 echo "<td colspan='3' class='label'>+ پروژه تجمیع
                      <input name='criditType' type='checkbox' id='criditType'  />";      
               }
                   
				 ?>
				 
                  <?php  
				    
		if ($login_userid>0)
        print"
			</table>
						
				<table width='100%' >
					 <tr><td  class='f12_fontsb'
			         onclick=\"showhidediv('proje');\">مشخصات طرح:</td></tr>
			</table>
			<table id='proje' > 
	<tr>
		";			
					
		/*
        $login_RolesID=24 نقش مدیر نقشه برداری
        */
	
	if ($login_RolesID!=24 )
        {
            //در پرس و جوی زیر می خواهیم فهرست بهای آبیاری فعاب برای هر گروه را استخراج نماییم و در کومبو باکس قرار دهیم
                     $limited = array("9");//نقش کاربر مهندسین مشاور
					if ($login_RolesID==17 || $login_RolesID==26)//ناظر مقیم
                    /*
                    costpricelistmaster فهرست بهای آبیاری تحت فشار
                    year سال
                    month ماه
                    
                    CostPriceListMasterID شناسه فهرست بها
                    YearID شناسه سال
                    MonthID شناسه ماه
                    pfd فعال برای مشاورین
                    pfo فعال برای پیمانکاران
                    */
                    $query="SELECT max(CostPriceListMasterID) as _value,
                             max(year.Value) as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfd=1 
                             ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                    else if ( in_array($login_RolesID, $limited))
					   $query="SELECT CostPriceListMasterID as _value,
                             year.Value as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfd=1
                             ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                     else $query="SELECT CostPriceListMasterID as _value,
                             year.Value as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfo=1
                             ORDER BY year.Value DESC ,month.Code DESC ";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "
                     <td id='CostPriceListMasterIDlbl'  class='label'>فهرست بها:</td>
                     <td>"
					 .select_option('CostPriceListMasterID','',',',$ID,0,'','','1','rtl',0,'',0,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'100');
		 
                     if ($login_RolesID==17 || $login_RolesID==26)
                    $query="select max(TransportCostTableMasterID) as _value,max(CONCAT(CONCAT(year.Value,' '),month.Title)) as _key from transportcosttablemaster
                            inner join year on year.YearID=transportcosttablemaster.YearID
                             inner join month on month.MonthID=transportcosttablemaster.MonthID
                             where pfd=1 and ostan='$login_ostanId' ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                      else        
                     $query="select TransportCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from transportcosttablemaster
                            inner join year on year.YearID=transportcosttablemaster.YearID
                             inner join month on month.MonthID=transportcosttablemaster.MonthID
                             where pfd=1 and ostan='$login_ostanId' ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='TransportCostTableMasterIDlbl'  class='label'> جدول ضرایب (حمل،تجهیز و...):</td>"
					 .select_option('TransportCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',0,'','100').
					
			           "";  
					  
					 $query="select RainDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from raindesigncosttablemaster
                            inner join year on year.YearID=raindesigncosttablemaster.YearID
                             inner join month on month.MonthID=raindesigncosttablemaster.MonthID
                             where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='RainDesignCostTableMasterIDlbl'  class='label'>جدول حق الزحمه طراحی بارانی:</td> "
					 .select_option('RainDesignCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',0,'','75')   ;

					 $query="select DropDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from dropdesigncosttablemaster
                            inner join year on year.YearID=dropdesigncosttablemaster.YearID
                             inner join month on month.MonthID=dropdesigncosttablemaster.MonthID
                             where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='DropDesignCostTableMasterIDlbl'  class='label'>جدول حق الزحمه طراحی قطره ای/تلفیقی:</td> "
					 .select_option('DropDesignCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',0,'','75').
                     " ";

	
			/*	
			 if (($login_OperatorCoID>0)||($login_DesignerCoID>0))
				{
					$linearray = explode('*',calculatedisabled($login_userid,$login_OperatorCoID,$login_DesignerCoID,$login_CityId,'2'));
					$linkcmd=$linearray[0];
					$Disabled=$linearray[1];    				
					$casedebt=$linearray[2];    				
					$linkpay=$linearray[3];    				
				}	
			*/

			//$linkpay=link_pay($login_opDisabled);
			//$linkcmd=link_cmd($login_opDisabled);
			//if ($linkpay) $linkcmd=$linkpay;
					
                    if ($login_RolesID==17 || $login_RolesID==26)
                     $condition1=" where DesignerCoID='67'";
                     else
					if ($login_DesignerCoID>0) $condition1=" where DesignerCoID='$login_DesignerCoID' and ifnull(DesignerCoID,0)<>0";
                    else if ($login_OperatorCoID>0) $condition1=" where operatorcoid='$login_OperatorCoID' and ifnull(operatorcoid,0)<>0";
                    else $condition1=" where ifnull(DesignerCoID,0)<>0 ";

                        $query="select designerID as _value,CONCAT(LName,' ',FName) as _key from designer 
                        $condition1 ORDER BY LName";
                       
            
			
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='DesignerIDlbl'  class='label'>طراح:</td>".select_option('DesignerID','',',',$ID,0,'','','1','rtl',0,'',0,'','100');
                	   "
                      

					  <td class='data'><input name='login_DesignerCoID' type='hidden' class='textbox' id='login_DesignerCoID'  value='$login_DesignerCoID'  /></td>
					 
                            </tr>";
							
//	  <td class='data'><input name='login_OperatorCoID' type='hidden' class='textbox' id='login_OperatorCoID'  value='$login_OperatorCoID'  /></td>
							
  
 echo "
   	<table width='100%' >
					 <tr><td colspan=10 class='f12_fontsb'
			         onclick=\"showhidediv('mokhtasat');\"> مشخصات آب و خاک:</td></tr>
					 
		</table>";
  ?>
    <table id='mokhtasat' > 
	
                    
   <tr>
                      
                      <td class='label'>نام و مختصات منبع آبی :</td>
                      <td colspan='1' class='data'><input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='StationNumber' type='text' class='textbox' id='StationNumber'    size='15' maxlength='50' /></td>
                      
                      <td colspan='1' class='data'>Xutm:<input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='XUTM1' type='text' class='textbox' id='XUTM1'    size='15' maxlength='50' /></td>
					  	    <td class='label' colspan="1">Yutm:
                     <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='YUTM1' type='text' class='textbox' id='YUTM1' size='15' maxlength='50' /></td>
					  
                      <td class='data'>Zone:
					    <select name="YUTM2" >
						  <option value="40">40</option>
						  <option value="41">41</option>
						</select>
					
                      </td>		  
					  
					  
                     
					  <?php
                      
                    if ($login_RolesID==17 || $login_RolesID==26)
                       $query="SELECT max(SoilTextureID) as _value,max(Title) as _key from soiltexture ";
					else
					   $query="SELECT SoilTextureID as _value,Title as _key from soiltexture ";
					   $ID = get_key_value_from_query_into_array($query);
					   echo 
                       "<td class='label' id='SoilLimitationlbl'>بافت خاك:".
                       select_option('SoilLimitation','',',',$ID,0,'','','1','rtl',0,'',0,'','80').
                       "</td>";
                      
                    if ($login_RolesID==26)
                     
                       $query="SELECT 0 as _value,'آبیاری تحت فشار' as _key
                       ";
                       else
                       if ($login_RolesID==17)
                     
                       $query="SELECT 1 as _value,'آبرسانی' as _key 
                       union all SELECT 0 as _value,'آبیاری تحت فشار' as _key
                       ";
                       else
                       $query="SELECT prjtypeid as _value,Title as _key from prjtype";
					   $ID = get_key_value_from_query_into_array($query);
					   echo 
                       "<td class='label' id='prjtypeidlbl'>نوع پروژه:".
                       select_option('prjtypeid','',',',$ID,0,'','','1','rtl',0,'',0,'','80').
                       "</td>";
                       
                       
                      
                        $permitrolsid = array("1", "5", "9", "10", "20");
                        if (in_array($login_RolesID, $permitrolsid))
                        {
                            if ($login_designerCO==1)
                                $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11";
                            else
                                $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11 and  
                                substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)";
                            $resultx = mysql_query($query);
                             $allclerkID[' ']=' ';
                             while($rowx = mysql_fetch_assoc($resultx))
                                if (decrypt($rowx['DVFS'])<>'ج')
                                $allclerkID[trim(decrypt($rowx['CPI'])." ".decrypt($rowx['DVFS']))]=trim($rowx['clerkID']);
                             $allclerkID=mykeyvalsort($allclerkID);
                             
                             
                            print 
                            "<td class='label'>بازبین:".
                       select_option('DesignerCoIDnazer','',',',$allclerkID,0,'','','1','rtl','','',0,'','125').
                       "</td>";
                            
                        }
                     
                       
					  ?>
					  
                      
                      
			</tr>     
                        
          <?php        
        }

                     if ($login_RolesID==24  || $login_RolesID==19)
                     {
                        echo " 
                        <tr><td  colspan='2' >تاریخ نقشه برداری:
                        <input placeholder='انتخاب تاریخ'  name='surveyDate' type='text' class='textbox' id='surveyDate' value='$surveyDate' size='10' maxlength='10' />
                         </td>
                        ";
						
			            echo " 
					<td colspan='2' >شماره پرونده:
                      <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\" name='numfield' type='text' class='textbox' id='numfield' value='$numfield' size='15' maxlength='50' /></td>
					  ";
            
						
                     }
					 
               ?>
			</tr>
</div>			
  </table>
    <table>
  <div>			
			
            <?php 
            if ($login_userid>0)
                print "<tr><td  colspan='1' ><input    name='submit' type='submit' class='button' id='submit' value='ثبت طرح'  $linkcmd /></td></tr>";
            
            } 
				
}			
	

					
if (!$IDorder)
    $IDorderval=5;
else $IDorderval=$IDorder;

$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'دبی' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'کدسامانه' _key,9 as _value union all
select 'سریال' _key,10 as _value ";
$IDorder = get_key_value_from_query_into_array($query);
 	
			?>
</div>
    </table>
            
	<div>
       <table id="records" width="100%" align="center">
         <tbody>
					<?php 
					$uid1='applicant_list.php?uid='.$uid1;
			     	$uid='applicant_list.php?uid='.$uid;
					?>    
					<tr><td colspan="11" class='data' style = "border:0px solid black;border-color:#0000ff #0000ff;text-align:center;
						font-size:12pt;line-height:155%;font-weight: bold;font-family:'B Nazanin';">  لیست طرح های مختلف 
						<input name='showm' type='checkbox' id='showm' onChange='checkchange()' <?php if ($showm==1) echo "checked";?>>
				    	<input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  />
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
						
					<br>
					<?php 
                    if ($login_userid>0)
                    echo"*با فشردن كليد"." <img style = 'width: 1%;' src='../img/Editinf.jpg'></a>"."اطلاعات  طرح را تكميل نماييد*"; ?>
					</td></tr>            
           </tbody>
		</table>
		
        <table id="records" width="95%" align="center">
          <thead>
                        <tr>
                        	<th  
                           	<span class="f9_fontb" width="1%" > رديف </span> </th>
							<th  
                           	<span class="f12_fontb">سریال </span> </th>
							<th  
                           	<span class="f12_fontb">کدسامانه </span> </th>
							<th   
                           	<span class="f11_fontb" width="10%">کد رهگیری</span> </th>
							<th   
                           	<span class="f11_fontb" width="10%">هکتار/متراژ</span> </th>
							
							<th  
                    		<span class="f12_fontb"> دبي </span>
							(l/s)  </span> </th>
						    <th  
                            <span class="f12_fontb" width="10%">نام </span>
							 </th><th  
                            <span class="f12_fontb" width="25%">نام خانوادگی </span>
							 </th>
                            <th 
                            <span class="f12_fontb" width="15%">دشت/شهرستان</span> </th>
							<th 
                            <span class="f12_fontb" width="10%" >شهر/بخش</span> </th>
							<?php if ($login_userid>0) {?>
                            <th 
							<span class="f12_fontb">پروژه</span> </th> <?php }?>
                            
                            <th  
							<span class="f12_fontb" width="10%" >وضعیت</th>
                            <th  
							<span class="f12_fontb" width="10%" >تاریخ</th>
                            <?php if ($login_userid>0) {?>
                            <th 
							<span class="f12_fontb">نوع</span> </th> <?php }?>
                                 <th width="5%">
							
								 <?php
                                 if ($login_userid>0)
								print select_option('IDorder','ترتیب',',',$IDorder,0,'','','4','rtl',0,'',$IDorderval,"onChange=\"checkchange();\"",'120');
								?>
             
							</th>
                          <th width="5%">
							</th>
                        
            	
						
                        </tr>
                   <?php
                   $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {
			  	   if($row['archive']==1 && $showm==0) {continue;}
				   $continue=0;
				    	
				

                        $rown++;   
                        
                        
                        $DesignerCoID=$row['DesignerCoID'];
                        $operatorcoid=$row['operatorcoid'];
                        
                        
                            
                        $Code = $row['Code'];
                        $ID = $row['ApplicantMasterID'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $ApplicantName = $row['ApplicantName'];
                        $monthtitle = $row['monthtitle'];
                        $BankCode=$row['BankCode'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
						 $fieldCode=$row['fieldCode'];
                       
						if ($row['criditType']>0) $cr='+';else $cr='';
						
						if ($rown%2==1) $background='style="background-color:#D6F1FF"';else $background='style="background-color:#F5FBFF"';
	           
    			   $permitrolsid = array("13","14","15","17","18","19","24","25","26");
			         if (in_array($login_RolesID, $permitrolsid))
						 
    			 	 $Codes=$fieldCode.'/'.$numfield; else $Codes=$Code; 
					     if ($rown%2==1) 
                        $b='b'; else $b='';
                    
						
?>                      
                        <tr <?php echo $background; ?> >
                            
                            <td
                            <span class="f10_font<?php echo $b; ?>" width="1%">  <?php echo $rown; ?> </span> </td>
                            <td
                            <span class="f10_font<?php echo $b; ?>" >  <?php echo '('.$Code.')'; ?> </span> </td>
						  <td
                            <span class="f10_font<?php echo $b; ?>" >  <?php echo $row['ApplicantMasterID']; ?> </span> </td>
							
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $BankCode; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['Debi']; ?> </span> </td>
                           
                            <td
							<span class="f11_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f11_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $row['bakhshcityname']   ; ?> </span> </td>
                           
                           <?php if ($login_userid>0) {?>
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php 
                            echo $row['prjtypetitle']  ; ?> </span> </td>
                            
                            <?php }?>
                            <td
                            <span class="f9_font<?php echo $b; ?>"><?php if($continue==0) echo $applicantstatestitle; ?></td>
                            
                           <td
                            <span class="f9_font<?php echo $b; ?>"><?php echo gregorian_to_jalali($row['TMDate'])   ; ?> </td>
                            <?php if ($login_userid>0) {?>
                            <td <span class="f9_font<?php echo $b; ?>">  <?php 
                            if (!($login_OperatorCoID>0)) echo str_replace(' ', '&nbsp;', $cr.$row['privatetitle']); }
				          if ($login_userid>0)
                          {
                                if($continue==0)
						      {
						      
                            print "</span> </td>
							<td ><a href='states_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_1'.rand(10000,99999); ?>'>
                            <img style = "width: 20px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            
                            
                            
                            <?php 
                            
                            
                                } 
						   else 
						    print "</span> </td><td></td>";
                            
                            if ( ($row['issurat']==1 && $row['freestateid']!=142) && ($row['issurat']==1 && $row['errnum']<8)  )
						
                            echo "<td><a onClick=\"alert('جدول زمانبندی پیش فاکتور توسط شرکت مشاور ناظر یا شرکت مجری ثبت نشده است');\" >
                            <img style = 'width: 20px;' src='../img/search_page.png' title=' ريز '></a></td><td></td>";
                            else
                            
                            {
                            
                                    if ($row['ApplicantMasterIDmaster']>0)
										echo "<td></td>";
                                    else 
										echo "<td><a href='applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
										<img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش $row[ApplicantMasterID]'></a></td>";
                                    
                                    if ($row['ApplicantMasterIDmaster']>0)
										echo "<td></td>";
                                    else 
										echo "<td><a 
                                        href='applicant_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'
                                        onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                                        > <img style = 'width: 20px;' src='../img/delete.png' title='حذف'> </a></td>";
									
									$ID2=rand(10000,99999).rand(10000,99999).$ID.'0a'.rand(10000,99999).rand(10000,99999).$login_userid;	
                                    
                                    
                                        
                                    if ($login_RolesID!=17 && $login_RolesID!=26)
                                    {
                                         
							
                            echo "<td><a 
                            href='alldetailinfo_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID."_".rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید  کلیه لوازم و پیش فاکتورها، فهرست بها، فهارس بها   حذف شود ؟');\"
                            > <img style = 'width: 30px;' src='../img/delete.png' title='حذف کلیه لوازم و پیش فاکتورها، فهرست بها، فهارس بها'> </a>
                            </td>";			
										if ($login_RolesID<>2) 
										{  
									   
											$t=" اطلاعات تكميلي سیستم و محصولات طرح ".$ApplicantFName." ".$ApplicantName;
											$ID = "applicantsystemtype_".$t."_0_ApplicantMasterID_".$row['ApplicantMasterID'];
										   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
										   rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
										   rand(10000,99999)."' target=\"_blank\" >
										 <img style = 'width: 20px;' src='../img/giah.jpg' title=' اطلاعات تكميلي سیستم و محصولات'></a></td>";
										 
                                     
											$t=" اطلاعات تكميلي منبع آبی طرح ".$ApplicantFName." ".$ApplicantName;
											$ID = "applicantwsource_".$t."_0_ApplicantMasterID_".$row['ApplicantMasterID'];
										   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
										   rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
										   rand(10000,99999)."' target=\"_blank\" >
										 <img style = 'width:15px;' src='../img/ab.png' title=' اطلاعات تكميلي منبع آبی'></a></td>";
										 
											$t=" اطلاعات نقشه برداری طرح ".$ApplicantFName." ".$ApplicantName;
											$ID = "applicantsurvey_".$t."_0_ApplicantMasterID_".$row['ApplicantMasterID'];
										 echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
										   rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
										   rand(10000,99999)."' target=\"_blank\" >
										 <img style = 'width: 20px;' src='../img/nagshe.jpg' title=' اطلاعات نقشه برداری '></a></td>";
										 
                                     
										} 
                                  	
									 
									 $t="زیر پروژه های طرح ".$ApplicantFName." ".$ApplicantName;
                                     $ID = "appsubprj_".$t."_0_ApplicantMasterID_".$row['ApplicantMasterID'];
									  
									     echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/desktopshare.png' title=' زیر پروژه ها'></a></td>";
                                   } 
								
                                        
                            }  
                          }
						  
									if ($row['prjtypeid']==1 || ($login_RolesID==17))
                                    echo "<td><a href=\"invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_'.$row['issurat'].'-'.$row['applicantstatesID'].rand(10000,99999).
                                    "\">
                                    <img style = 'width: 20px;' src='../img/search_page.png' title=' مشاهده لیست پیش فاکتور/لیست لوازمها '></a>";
                                    
                                       
                                                                       
                    }
                    //$permitrolsid = array("1", "24","25","17","19");
                    //if (in_array($login_RolesID, $permitrolsid) || (!($DesignerCoID>0) && !($operatorcoid>0)) )
										print "<td class='no-print'><a  target='".$target."' 
										href='../insert/approvedocumentapplicantmaster.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
										"'><img style = 'width: 20px;' src='../img/search.png' title=' مدارک طرح '></a></td>";
                                         /*
                                         
                                         echo "<td><a href='applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
										<img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش $row[ApplicantMasterID]'></a></td>";
                                        
                                         print "</span> </td>
							<td ><a href='states_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_1'.rand(10000,99999)."'>
                            <img style = \"width: 20px;\" src=\"../img/refresh.png\" title=' مشاهده ریز عملیات ' ></a></td>"; */
                            
                            
                                     echo "</tr>";
                                     
?>

                    </tbody>
                </table>
                      
                   <tr>
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form>   
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php require_once('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
