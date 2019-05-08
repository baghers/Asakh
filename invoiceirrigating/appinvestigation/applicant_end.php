<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
include('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی ام ال صفحات
include('../includes/elements.php'); 
      
?>
<?php
//درصورتی که کاربر مجوز این صفحه را نداشته باشد از این صفحه خارج می شود
if ($login_Permission_granted==0) header("Location: ../login.php");
    
    //اطلاعات مورد نظر از لینک گت    
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $uid=$_GET["uid"];
    $linearray = explode('_',$ids);
    
    //شناسه طرح
    $ApplicantMasterID=$linearray[0];
    
    //در صورتی که مرحله آزادسازی 6 باشد به منزله آماده تحویل دائم در غیر اینصورت تحویل موقت می باشد
    $freenum=$linearray[1];
    
    //شناسه وضعیت طرح صورت وضعیت طرح
    $applicantstatesIDsurat=$linearray[2];
	
    //شناسه وضعیت 45 یعنی صورت وضعیت نهایی شده است
    //در صورتی که صورت وضعیت طرح نهایی نشده باشد یک هشدار به کاربر داده خواهد شد
	if ($applicantstatesIDsurat<>45)
	{
	  echo"<script>alert('صورت وضعیت طرح نهایی نشده است!')</script>";
      //echo"<script>window.location='applicantstates_master.php'</script>";
	}	

/*
    		$query = "SELECT 
			-- جدول مشخصات طرح ها
            applicantmaster.*,
            
            --  کد رهگیری طرح
            applicantmaster.Bankcode Bankcode,
			
            -- نام شهر و استان
			shahr.CityName CityName,ostan.CityName Ostan,
            
            -- عنوان سیستم آبیاری
            designsystemgroups.Title designsystemgroupsTitle,
			
            -- عنوان پیمانکار طرح
            operatorco.Title operatorcoTitle,
			
            -- فیلد creditbank مشخص می کند که اعتبار از بانک است یا صندوق درصورتی که یک باشد یعنی از بانک و در غیر اینصورت از صندوق است
            -- creditbank این ستون مشخص می کند که کارشناس منبع تامین اعتبار این طرح بانک است یا صندوق
            case ifnull(creditsource.creditbank,0) when 1 then 'کارشناس بانک' else '' end creditbank,
            
            -- عنوان شرکت مشاور ناظر
			designercos.Title nazercoTitle,
            
            -- عنوان شرکت مشاور طراح
            designerco.Title designercoTitle,
            
            -- عنوان منبع تامین اعتبار
            creditsource.title creditsourcetitle,
            
            -- محصولات قابل کشت در طرح با کاراکتر فاصله جدا می شود
        	GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' ') designsystemgroupsdetailyeild,
			
            -- کد صندوق
			applicantmasterd.sandoghcode sandoghcode,
            
            -- شناسه طرح مطالعاتی
			applicantmasterd.applicantmasterid applicantmasteridD,
            
            -- شناسه نوع اعتبار 
			applicantmasterd.criditType criditTypeD,
            
            -- نام منبع تامین اعتبار
			applicantmasterd.creditsourceid creditsourceidD,
            
            -- جمع کل هزینه های طرح
			round(applicantmasterd.LastTotal/1000000,1) LastTotald,
            
            -- مبلغ بلاعوض طرح مطالعاتی
			round(applicantmasterd.belaavaz,1) belaavazdesign,
            
            -- شماره پروانه آب طرح
			applicantmasterd.numfield2 numfield2d,
			
            -- applicantmasteri در صورتی که طرح در مرحله پیش فاکتور باشد و هنوز صورت وضعیت صادر نشده باشد این ردیف طرح پیش فاکتور است و در غیر اینصورت در وضعیت صورت وضعیت
			-- در ستون زیر می خواهیم آخرین مبلغ طرح را داشته باشیم
            -- یعنی اگر طرح در مرحله صورت وضعیت است مبلغ صورت وضعیت
            -- در صورتی که طرح در مرحله پیش فاکتور است مبلغ پیش فاکتور
            -- و اگر طرح هنوز در مرحله مطالعاتی است مبلغ کل طرح مطالعاتی
            case ifnull (applicantmasteri.LastTotal,0) when 0 then round(applicantmasterd.LastTotal/1000000,1) else round(applicantmasteri.LastTotal/1000000,1) end LastTotali,
			
            -- آخرین مبلغ خودیاری غیر نقدی مانند سه وضعیت فوق 
            case ifnull (applicantmasteri.selfnotcashhelpval,0) when 0 then round(applicantmasterd.selfnotcashhelpval/1000000,1) else round(applicantmasteri.selfnotcashhelpval/1000000,1) end selfnotcashhelpvali,
			
            -- آخرین مبلغ خودیاری نقدی مانند مانند سه وضعیت فوق 
            case ifnull (applicantmasteri.selfcashhelpval,0) when 0 then round(applicantmasterd.selfcashhelpval/1000000,1) else round(applicantmasteri.selfcashhelpval/1000000,1) end selfcashhelpvali,
		    
            -- آخرین شناسه طرح ثبت شده
            applicantmasteri.ApplicantMasterID ApplicantMasterIDchange,
			
            -- آخرین شماره پروانه آب طرح
            applicantmasteri.numfield2 numfield2i,
		        
            -- آخرین تاریخ تغییر وضعیت طرح
			max(appchangestate.SaveDate) SaveDatechange,
			
            -- آخرین شناسه  وضعیت طرح
            appchangestate.applicantstatesID applicantstatesID
			
            -- جدول مشخصات طرح
			FROM applicantmaster 
			
            -- جدول استان ها
            left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
			
			-- جدول شهرها
    		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
			
            -- جدول پیمانکاران				
            inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
            
            -- ردیف طرح مطالعاتی در جدول طرح ها
            inner join applicantmaster applicantmasterd on applicantmasterd.BankCode=applicantmaster.BankCode and applicantmasterd.DesignerCoID>0
            
            -- آخرین ردیف طرح ثبت شده در جدول طرح ها ککه صورت وضعیت یا پیش فاکتور می باشد
            -- درصورتی که طرح در مرحله صورت وضعیت می باشد شناسه طرح صورت وضعیت در غیر اینصورت شناسه طرح پیش فاکتور
			inner join applicantmaster applicantmasteri on applicantmasteri.BankCode=applicantmaster.BankCode and applicantmasteri.ApplicantMasterIDmaster = (SELECT 
					case ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0  when 0 then applicantmaster.ApplicantMasterIDmaster else applicantmaster.ApplicantMasterID end ApplicantMasterIDmaster
					FROM applicantmaster 
					WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID')
			
            -- جدول ارتباطی سه وضعیت طرح ها
			left outer join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
			
            -- شرکت های مشاور طراح
			left outer join designerco on designerco.DesignerCoID=applicantmasterd.DesignerCoID

			
			-- شرکت مشاور ناظر
            left outer join designerco designercos on designercos.DesignerCoid=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end

            -- جدول انواع سیستم های آبیاری
			left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
			
            جدول سیستم های مختلف آبیاری هر طرح
			left outer join designsystemgroupsdetail on designsystemgroupsdetail.ApplicantMasterID='$ApplicantMasterID'
            
            -- جدول منابع اعتباری مختلف
			left outer join creditsource on creditsource.creditsourceid=applicantmasterd.creditsourceid
			
            -- جدول تغییر وضعیت های مختلف طرح ها
            inner join appchangestate on appchangestate.ApplicantMasterID=applicantmasteri.ApplicantMasterID 
			
            -- متغیر $ApplicantMasterID که از متد گت خوانده شده و شناسه طرح مورد نظر می باشد
            --  در صورتی که آخرین شناسه طرح برابر متغیر فوق باشد باید بررسی شود
			WHERE applicantmaster.ApplicantMasterID = (SELECT 
case ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0  when 0 then applicantmaster.ApplicantMasterIDmaster 
else applicantmaster.ApplicantMasterID end ApplicantMasterIDmaster
			FROM applicantmaster 
			WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID')
			
			;";
*/

    		$query = "SELECT 
			applicantmaster.*,applicantmaster.Bankcode Bankcode,
			
			shahr.CityName CityName,ostan.CityName Ostan,
            designsystemgroups.Title designsystemgroupsTitle,
			operatorco.Title operatorcoTitle,
			case ifnull(creditsource.creditbank,0) when 1 then 'کارشناس بانک' else '' end creditbank,
			designercos.Title nazercoTitle,designerco.Title designercoTitle,creditsource.title creditsourcetitle,
        	GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' ') designsystemgroupsdetailyeild,
			
			applicantmasterd.sandoghcode sandoghcode,
			applicantmasterd.applicantmasterid applicantmasteridD,
			applicantmasterd.criditType criditTypeD,
			applicantmasterd.creditsourceid creditsourceidD,
			round(applicantmasterd.LastTotal/1000000,1) LastTotald,
			round(applicantmasterd.belaavaz,1) belaavazdesign,
			applicantmasterd.numfield2 numfield2d,
			
			case ifnull (applicantmasteri.LastTotal,0) when 0 then round(applicantmasterd.LastTotal/1000000,1) else round(applicantmasteri.LastTotal/1000000,1) end LastTotali,
			case ifnull (applicantmasteri.selfnotcashhelpval,0) when 0 then round(applicantmasterd.selfnotcashhelpval/1000000,1) else round(applicantmasteri.selfnotcashhelpval/1000000,1) end selfnotcashhelpvali,
			case ifnull (applicantmasteri.selfcashhelpval,0) when 0 then round(applicantmasterd.selfcashhelpval/1000000,1) else round(applicantmasteri.selfcashhelpval/1000000,1) end selfcashhelpvali,
		    applicantmasteri.ApplicantMasterID ApplicantMasterIDchange,
			applicantmasteri.numfield2 numfield2i,
		        
		
			max(appchangestate.SaveDate) SaveDatechange,
			appchangestate.applicantstatesID applicantstatesID
			
			FROM applicantmaster 
			
            left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
			
			
    		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
							
            inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
            inner join applicantmaster applicantmasterd on applicantmasterd.BankCode=applicantmaster.BankCode and applicantmasterd.DesignerCoID>0
            
			inner join applicantmaster applicantmasteri on applicantmasteri.BankCode=applicantmaster.BankCode and applicantmasteri.ApplicantMasterIDmaster = (SELECT 
					case ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0  when 0 then applicantmaster.ApplicantMasterIDmaster else applicantmaster.ApplicantMasterID end ApplicantMasterIDmaster
					FROM applicantmaster 
					WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID')
					
			left outer join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
			
			left outer join designerco on designerco.DesignerCoID=applicantmasterd.DesignerCoID

			
			
left outer join designerco designercos on designercos.DesignerCoid=
		case ifnull(applicantmasterdetail.nazerID,0) when 0 then 
shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end

				
			left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
			
		
			left outer join designsystemgroupsdetail on designsystemgroupsdetail.ApplicantMasterID='$ApplicantMasterID'

			left outer join creditsource on creditsource.creditsourceid=applicantmasterd.creditsourceid
			inner join appchangestate on appchangestate.ApplicantMasterID=applicantmasteri.ApplicantMasterID 
			

			WHERE applicantmaster.ApplicantMasterID = (SELECT 
case ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0  when 0 then applicantmaster.ApplicantMasterIDmaster else applicantmaster.ApplicantMasterID end ApplicantMasterIDmaster
			FROM applicantmaster 
			WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID')
			
			;";
       //   print $query;
       try 
        {		
            $result = mysql_query($query); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
       
			
			$resquery = mysql_fetch_assoc($result);
    //بخش دوم مقدار $resquery["numfield2"] برابر تاریخ تحویل موقت پروژه می باشد
	$numfield2array = explode('_',$resquery["numfield2"]);
    
    // تاریخ تحویل موقت پروژه  
    $Tahvilmovaghtdate=$numfield2array[1];
    
    //بخش دوم مقدار $resquery["numfield2i"] برابر تاریخ تحویل دائم پروژه می باشد
	$numfield2array = explode('_',$resquery["numfield2i"]);
    
    // تاریخ تحویل دائم پروژه  
    $Tahvildaemdate=$numfield2array[1];
//	print $Tahvildaemdate;

    //بخش دوم مقدار $resquery["numfield2i"] برابر تاریخ  انعقاد قرارداد پروژه می باشد
	$numfield2array = explode('_',$resquery["numfield2d"]);
    
    //تاریخ  انعقاد قرارداد پروژه  
    $contracdate=$numfield2array[1];
			
    //عنوان مشاور ناظر پروژه
    $nazercoTitle=$resquery['nazercoTitle'];
    
    //اعتبار بانکی بودن
    $creditbank=$resquery['creditbank'];

    //عنوان مشاور طراح
    $designercoTitle=$resquery['designercoTitle'];

    //عنوان پیمانکار پروژه
    $operatorcoTitle=$resquery['operatorcoTitle'];

    //نام شهر محل اجرای پروژه
    $CityName = $resquery['CityName'];

    //نام استان محل اجرای پروژه
    $Ostan = $resquery['Ostan'];
    
    //شناسه آخرین وضعیت پروژه
    $applicantstatesID = $resquery['applicantstatesID'];

    //آخرین مبلغ خودیاری غیر نقدی طرح
    $selfnotcashhelpvali = $resquery['selfnotcashhelpvali'];
    
    //آخرین مبلغ خودیاری نقدی طرح
    $selfcashhelpvali = $resquery['selfcashhelpvali'];
    
    //کل مبلغ خودیاری طرح
    $selfhelpsvali =$selfnotcashhelpvali+$selfcashhelpvali;

    //بلاعوض مصوب طرح مطالعاتی
    $belaavazdesign=$resquery['belaavazdesign'];

    //آخرین مبلغ کل هزینه های طرح    
    $LastTotali=$resquery['LastTotali'];
    
    //درصورتی که آخرین هزینه کل طرح بزرگتر از کل هزینه های فاز مطالعات باشد، مقدار کمتر یعنی مبلغ کل هزینه ها در فاز مطالعات به عنوان جمع کل هزینه های طرح در محاسبات بعدی لحاظ خواهد شد.
    if ($resquery['LastTotali']>$resquery['LastTotald']) $LastTotali=$resquery['LastTotald'];


    //شناسه طرح مطالعاتی
    $applicantmasteridD=$resquery['applicantmasteridD'];
    
    //شناسه طرح صورت وضعیت
    $ApplicantMasterIDs=$resquery['ApplicantMasterID'];

    //استخراج کل محصولاتی که در پروژه تولید می شود در طرح پیش فاکتور
    //designsystemgroupsdetail جدول تفکیک سطح و محصولات در پروژه
    $sqy="SELECT  ifnull(GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' ')) designsystemgroupsdetailyeild 
    FROM `designsystemgroupsdetail` where ApplicantMasterID=$ApplicantMasterID ";
    try 
        {		
            $resulty = mysql_query($sqy); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
    if ($resulty) $resqy = mysql_fetch_assoc($resulty);
    //استخراج کل محصولاتی که در پروژه تولید می شود در طرح مطالعاتی 
    //designsystemgroupsdetail جدول تفکیک سطح و محصولات در پروژه
    
    //در صورتی که پرس و جوی قبلی نتیجه ای نداشت به طرح مطالعاتی در زیر مراجعته می کنیم
    if (!$resqy['designsystemgroupsdetailyeild'])
    $sqy="SELECT  ifnull(GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' '),0) designsystemgroupsdetailyeild 
    FROM `designsystemgroupsdetail` where ApplicantMasterID=$applicantmasteridD ";
    try 
        {		
            $resulty = mysql_query($sqy);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
    if ($resulty) $resqy = mysql_fetch_assoc($resulty);




    //استخراج کل محصولاتی که در پروژه تولید می شود در طرح صورت وضعیت 
    //designsystemgroupsdetail جدول تفکیک سطح و محصولات در پروژه
    //در صورتی که پرس و جوی قبلی نتیجه ای نداشت به طرح صورت وضعیت در زیر مراجعته می کنیم
    if (!$resqy['designsystemgroupsdetailyeild'])
    $sqy="SELECT  GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' ') designsystemgroupsdetailyeild 
    FROM `designsystemgroupsdetail` where ApplicantMasterID=$ApplicantMasterIDs ";
    try 
        {		
            $resulty = mysql_query($sqy);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
    if ($resulty) $resqy = mysql_fetch_assoc($resulty);
    
    $designsystemgroupsdetailyeild=$resqy['designsystemgroupsdetailyeild'];




    //شناسه نوع اعتبار طرح
    $criditType=$resquery['criditTypeD'];
    // محاسبه بلاعوض سیستمی
    //در صورتی که طرح تجمیعی باشد به معنی آن است که پروژه شامل خرده مالکین هست و دولت به این طرح ها فارق از نوع سیستم حد اککثر بلاعوض قابل مصوب که برابر
    //85 درصد کل هزینه های پروژه می باشد پرداخت می نماید
    //در صورتی ککه در پروژه های غیر تجمیع سیستم های ککم فشار بلاعوض کم و ثابتی به ازای هر هکتار متناسب با هر منبع اعتباری پرداخت می شود
    //در متغیر زیر تجمیع بودن پروژه مشخص می شود
    if ($criditType>0) {$creditTypetitle=' (طرح تجمیع) ';}
    
    //$resquery["creditsourceidD"] منبع اعتباری فاز مطالعات
    //$resquery["creditsourceid"] آخرین منبع اعتباری ثبت شده  در صورت وضعیت یا پیش فاکتور
    if ($resquery["creditsourceidD"]>0)	$selectedcreditsourceID=$resquery["creditsourceidD"];
	else if ($resquery["creditsourceid"]>0) $selectedcreditsourceID=$resquery["creditsourceid"];
    
    //متغیر $LastTotali بر اساس میلیون ریال می باشد که در دستور زیر به ریال تبدیل می شود
    $sumsurat=$LastTotali*1000000;
 
    //تابع محاسبه آخرین بلاعوض بر اساس نوع تجمیعی بودن و جمع کل هزینه های طرح
    //در تابع زیر
    //$selectedcreditsourceID آخرین شناسه وضعیت پروژه
    //$ApplicantMasterID شناسه جدول مشخصات طرح
    //$sumsuratجمع کل هزینه های طرح
    //$criditType نوع تجمیعی بودن یا نبودن 1 تجمیعی است و در غیر اینصورت غیر تجمیع
    $sysbelaavaz=calculatebelavaz($selectedcreditsourceID,$ApplicantMasterID,$sumsurat,$criditType);

    //محاسبه سهم خودیاری پروژه که اختلاف کل هزینه های طرح و مبلغ بلاعوض پروژه می باشد
    $selfhelps=$LastTotali-$sysbelaavaz;

    //عنوان سیستم آبیاری پروژه
    $designsystemgroupsTitle=$resquery['designsystemgroupsTitle'];

    //مساحت پروژه بر اساس هکتار
    $DesignArea=$resquery['DesignArea'];
    
    //عنوان پروژه
    $ApplicantFName=$resquery['ApplicantFName'].' '.$resquery['ApplicantName'];
    
    //عنوان منبع اعتباری پروژه
    $creditsourcetitle=$resquery['creditsourcetitle'];
    
    //کد رهگیری پروژه
    $Bankcode=$resquery['Bankcode'];
    
    //کد صندوق
    $sandoghcode=$resquery['sandoghcode'];
    
    //دبی پروژه
    $Debi=$resquery['Debi'];
    
    //تاریخ آخرین تغییرات انجام شده در پروژه
    $SaveDate=gregorian_to_jalali($resquery['SaveDatechange']);
   	if ($applicantstatesIDsurat<>45)
	{
      $SaveDate= gregorian_to_jalali(date('Y-m-d'));
	}
 
/*

$sql2="SELECT 	
-- عنوان پیش فاکتور
invoicemaster.Title invoicemasterTitle,
--  نام تولید کننده
producers.Title producersTitle,
-- اینکه تولید کننده لوله تولید می کند یا خیر
producers.PipeProducer ProducerType,
--  شناسه مارک کالا
marks.marksid,
-- عنوان مارک
marks.title markstitle
-- عنوان سطح یک ابزار,
gadget1.Title gadget1Title,
-- شناسه سطح یک ابزر
gadget1.Gadget1ID Gadget1ID

-- جدول ریز لوازم مورد استفاده در طرح                     
FROM invoicedetail

-- جدول ابزار که سه ستون 
inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
inner join gadget3 on gadget3.Gadget3ID=toolsmarks.gadget3ID
inner join gadget2 on gadget2.Gadget2ID=gadget3.Gadget2ID
inner join gadget1 on gadget1.Gadget1ID=gadget2.Gadget1ID     
inner join marks on marks.MarksID=toolsmarks.MarksID
inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
inner join producers on producers.ProducersID=invoicemaster.ProducersID
where invoicemaster.ApplicantMasterID='$ApplicantMasterID'
group by gadget1.Gadget1ID,marks.MarksID
order by ProducerType
        ";
        
*/

$sql2="SELECT 	invoicemaster.Title invoicemasterTitle,
producers.Title producersTitle,producers.PipeProducer ProducerType
,marks.marksid,marks.title markstitle,gadget1.Title gadget1Title,gadget1.Gadget1ID Gadget1ID                     
FROM invoicedetail
inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
inner join gadget3 on gadget3.Gadget3ID=toolsmarks.gadget3ID
inner join gadget2 on gadget2.Gadget2ID=gadget3.Gadget2ID
inner join gadget1 on gadget1.Gadget1ID=gadget2.Gadget1ID     
inner join marks on marks.MarksID=toolsmarks.MarksID
inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
inner join producers on producers.ProducersID=invoicemaster.ProducersID
where invoicemaster.ApplicantMasterID='$ApplicantMasterID'
group by gadget1.Gadget1ID,marks.MarksID
order by ProducerType
        ";
    try 
        {		
            $result2 = mysql_query($sql2);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }        
        

//print $sql2;
					            $rown=0;
                                $alldata=array();
                                while($row = mysql_fetch_assoc($result2))
                                {
                                    $rown++;
						
									$alldata[$rown][0]=$row['ProducerType'];
                                    $alldata[$rown][1]=$row['gadget1Title'];
                        			if ($row['producersTitle']=='شرکت مجری' || $row['producersTitle']==$row['markstitle']) $alldata[$rown][2]=='';
									else $alldata[$rown][2]=$row['producersTitle'];
									$alldata[$rown][3]=$row['markstitle'];
									$alldata[$rown][4]=$row['invoicemasterTitle'];
									if ($row['Gadget1ID']==60) { //الکتروپمپ
									$productpumpmark=$row['markstitle'].'،'.$productpumpmark;
									$productpumpprud=$row['producersTitle'];}
									
									if ($row['Gadget1ID']==55) { //دستگاه بارانی
									$productbaranimark=$row['markstitle'].'،'.$productbaranimark;
									$productbaraniprud=$row['producersTitle'];}
									if ($productpumpprud) $productpump=$productpumpprud.' ('.$productpumpmark.')';
									if ($productbaraniprud) $productbarani=$productbaraniprud.' ('.$productbaranimark.')';
                                }  
//	$freenum=6;

							
	  $Anjoman='';	
	  $TahvildaemdateTemp='';
	  $TahvilTitle=' صورتجلسه تحویل موقت طرح آبیاری ';
	  
							$date = new DateTime($resquery['SaveDatechange']);
							$date->modify('+365 day');
							$SaveDateTemp=$date->format('Y-m-d');
							$TahvilmovaghtTitle='تاریخ ثبت تحویل موقت:'.$Tahvilmovaghtdate;	
                            
            
                            	if (strlen($Tahvilmovaghtdate)>0 && strlen($Tahvilmovaghtdate)<10)
                                {
                                    print "تاریخ تحویل موقت به صورت کامل و ده رقمی وارد نشده است".$Tahvilmovaghtdate;
           	                
                                }
                            if ($Tahvilmovaghtdate)
							{
							$TahvildaemdateTemp = new DateTime(jalali_to_gregorian($Tahvilmovaghtdate) );
							$TahvildaemdateTemp->modify('+365 day');
							$TahvildaemdateTemp=$TahvildaemdateTemp->format('Y-m-d');
							}			
						
							
						
	  if ($freenum==6)  {	$TahvilTitle=' صورتجلسه تحویل دائم طرح آبیاری ';
							
							if (!$Tahvilmovaghtdate) $TahvilD='(تاریخ تحویل موقت:'.$SaveDate.')';
							else $TahvilD='(تاریخ ثبت تحویل موقت:'.$Tahvilmovaghtdate.')';
							
							$TahvilmovaghtTitle='تاریخ  تایید صورت وضعیت:'.$SaveDate;
							$Anjoman='';
							$date = new DateTime($resquery['SaveDatechange']);
							$date->modify('+365 day');
							$SaveDateD=$date->format('Y-m-d');
							if ($SaveDateD<date('Y-m-d') ) $SaveDateD=date('Y-m-d');
							$SaveDate= gregorian_to_jalali(date('Y-m-d'));
							
						}
		
	  
	   ?>	
								

<!DOCTYPE html>
<html>
<head>


	<title>صورتجلسه تحویل پروژه</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
  
  
  <style>
p {
    display: block;
    margin-top: 0em;
    margin-bottom: 0em;
    margin-left: 30;
    margin-right: 35;
}

.f16_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:16pt;line-height:100%;font-weight: bold;font-family:'B Titr';                        
}
.f14_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:150%;font-family:'B zar'; 
}
.f13_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:150%;font-family:'B Nazanin'; 
}

.f14_fontr{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:150%;font-family:'B zar'; 
}
.f14_fontborder{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:220%;font-family:'B zar'; 
}
.f14_fonthigh{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:500%;font-family:'B zar'; 
}

.f8_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:8pt;line-height:150%;font-family:'B zar'; 
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
  


</style>

<?php 
/*p:before { content: url("../img/bak.gif"); }
@media print {
    body:after {
        content:url("../img/bak.gif");
        position: absolute;
        z-index: -1;
      }
}

*/
if ($applicantstatesIDsurat<>45 ) { ?>
   <style>
.tbl {
    background-image: url("../img/bak.gif") !important;
    background-repeat: repeat-y;
} 

</style>
<?php } ?>

    <script>

  function checkchange(){
		   if (document.getElementById('freenum').checked)
			{
						//var sysbelaavaz2=document.getElementById('sysbelaavaz').value;
						var uid3=document.getElementById('uid3').value;
						//alert(uid3);
						//<?php $sys ?> = sysbelaavaz2;
							window.location.href =document.getElementById('uid3').value;
		
			}		
			else 
			{		
				var uid=document.getElementById('uid1').value;
		        window.location.href =document.getElementById('uid1').value;
    		
			}	
		
        }
		
  

	   
    </script>


<body>

	
				<!-- container 
	<div id="container" >-->

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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
	<div id="content">
			
	
                           
                <table style="float:right" border='1' id="table2" class="tbl" >  
				
                  <thead>
				  <tr> 

				  
				   <td colspan="2"  >  </td>
                  <td colspan="3">  <span class="f8_font" >تاریخ چاپ:<?php echo gregorian_to_jalali(date('Y-m-d'));?></span>  </td>
				 	
				  <td colspan="4" > <span class="f14_font" ></span>  </td>
			<?php
			
			 //print $TahvildaemdateTemp.'<br>'.jalali_to_gregorian($TahvildaemdateTemp).'<br>'.date('Y-m-d').'<br>'.$applicantstatesIDsurat.'<br>'.$SaveDateTemp;
			 
				if (
				($applicantstatesIDsurat==45 && $SaveDateTemp<date('Y-m-d')) ||
				($applicantstatesIDsurat==45 && ($TahvildaemdateTemp)<=date('Y-m-d') && $TahvildaemdateTemp>0) 
					)


				{
									$uid3="applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).$ApplicantMasterID."_6_".$applicantstatesIDsurat.rand(10000,99999);
									
									$uid1="applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).$ApplicantMasterID."_5_".$applicantstatesIDsurat.rand(10000,99999);
			?>			  
 			      <td colspan="1"  <span class="f13_font no-print" >تحویل دائم</span>  
		 			<input name='freenum' type='checkbox' id='freenum' onChange='checkchange()' <?php if ($freenum==6) echo "checked";?>>
				    <input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  />
                    <input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  /></td>
        
			<?php	}
            else
            {
                print " عدم تحویل دائم به دلایل زیر";
                if ($applicantstatesIDsurat!=45)
                    print "<br>صورت وضعیت طرح تایید نهایی نشده است";  
                if ($TahvildaemdateTemp<0)
                    print "<br>تاریخ تحویل موقت ثبت نشده است"; 
                    
                if ((($TahvildaemdateTemp))>date('Y-m-d'))
                    print "<br>عدم سپری شدن یکسال از تاریخ تحویل موقت ".gregorian_to_jalali($TahvildaemdateTemp);  
                
            }
            
             	?>			  
 				 </tr>
			
			
				   <tr> 
					<td colspan="1"  >  </td>
					<td colspan="1"  >  </td>
					<td colspan="3" style="<?php if (!$contracdate) echo 'visibility:hidden'; ?>" > <span class="f8_font" >تاریخ ثبت قرارداد :<?php echo $contracdate;?></span>  </td>
					
					<td colspan="4"  <span class="f13_font" >بسمه تعالی</span>  </td>
				   </tr>
				   
				   <tr> 
					<td colspan="1"  >  </td>
					<td colspan="1"  >  </td>
					
					<td colspan="3" style="<?php if (!$Tahvilmovaghtdate) echo 'visibility:hidden';?>" > <span class="f8_font" >
					<?php echo $TahvilmovaghtTitle;?></span>  </td>
										
					<td colspan="4"  <span class="f13_font" >فرم شماره6</span>  </td>
						   <td colspan="1">  <span class="f13_font" > تاریخ:<?php echo $SaveDate;?></span>  </td>
            
                   </tr>
				   
				   <tr> 
					<td colspan="2"  >  </td>
					<td colspan="3" style="<?php if (!$Tahvildaemdate || $Tahvilmovaghtdate>=$Tahvildaemdate ) echo 'visibility:hidden'; ?>" > <span class="f8_font" >تاریخ ثبت :
					<?php echo $Tahvildaemdate;?></span>  </td>
							
					 <td colspan="4">  <span class="f14_font" ><?php echo $TahvilTitle.''.$designsystemgroupsTitle.'<br>'.$TahvilD;?></span>  </td>
				   
			       </tr>
				 
  				   <tr> 
				   <td colspan="2"  >  </td>
				   <td colspan="1"  >  </td>
				   
                   <td colspan="1" > <span class="f13_font" >کد پروژه: </span>  </td>
				   <td colspan="1">  <span class="f13_font" ><?php echo $Bankcode;?></span>  </td>
				   <td colspan="2"  >  </td>
					
				   <td colspan="5" > <span class="f13_font" >نام بهره بردار:<?php echo $ApplicantFName;?></span>  </td>
                   </tr>
				            
				   <tr> 
				   <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="3"  <span class="f13_font" ><?php echo $Ostan;?></span>  </td>
				   
				   <td colspan="2"  <span class="f13_font" >شهرستان:<?php echo $CityName;?> </span>  </td>
				   
				   <td colspan="4"  <span class="f13_font" >کد بانک/صندوق:<?php echo $sandoghcode;?> </span>  </td>
				   </tr>
				   
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="3"  <span class="f13_font" >طراح:<?php echo $designercoTitle;?></span>  </td>
				   <td colspan="4"  <span class="f13_font" >پیمانکار طرح:<?php echo $operatorcoTitle;?></span>  </td>
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="3"  <span class="f13_font" >ناظر:<?php echo $nazercoTitle;?></span>  </td>
				   <td colspan="5"  <span class="f13_font" >تصویب کننده طرح:مدیریت آب و خاک و امور سرمایه گذاری</span>  </td>
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="9" >  </td>
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1" >&nbsp;
				    </td>
                   <td colspan="6"  <span class="f14_fontborder" >مشخصات فیزیکی طرح</span>  </td>
				   <td colspan="4"  <span class="f14_fontborder" >مشخصات اعتباری طرح</span>  </td>
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" >نوع سیستم:<?php echo $designsystemgroupsTitle.$creditTypetitle;?></span>  </td>
				   <td colspan="4"  <span class="f14_fontborder" > کمک بلاعوض دولتی<?php 
                   if ($applicantstatesIDsurat==45 || $applicantstatesIDsurat==43)
                   {
    				   if ($sysbelaavaz>$belaavazdesign) 
                       {
                            $currbelaavaz=$belaavazdesign;
                            echo ':'.$currbelaavaz; 
                       }
                        else 
                        {
                            $currbelaavaz=$sysbelaavaz;
                            echo ' اصلاحی :'.$currbelaavaz;    
                        }     
                        print "  میلیون ریال";               
                   }
                   else   print ":...............میلیون ریال";
                    
                      ?></span>  </td>
                   </tr>
				
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" >مساحت زیر پوشش:<?php echo $DesignArea;?> هکتار</span>  </td>
				   <td colspan="4"  <span class="f14_fontborder" >محل تامین اعتبار:<?php 
                   
                   if ($applicantstatesIDsurat==45 || $applicantstatesIDsurat==43)
                   echo $creditsourcetitle;?></span>  </td>
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" > دستگاه بارانی مکانیزه و کارخانه سازنده:<br><?php echo $productbarani;?></span>  </td>
				  
                  
                  <td colspan="4"  <span class="f14_fontborder" >سهم آورده نقدی شخصی<?php 
				   if ($applicantstatesIDsurat==45 || $applicantstatesIDsurat==43)
                   {
                       if ($selfhelps==$selfhelpsvali) 
                       {
                            $curcashhelp=$selfcashhelpvali;
                            echo ':'.$curcashhelp; 
                       }
                        
    				   else if (($selfhelps-$selfnotcashhelpvali)>0) 
                        {
                            $curcashhelp=($selfhelps-$selfnotcashhelpvali);
                            echo ' اصلاحی :'.round($curcashhelp,1);
                        }
                        else
                        {
                            $curcashhelp=0;
                             echo ': '.$curcashhelp;
                        }
    				  print " میلیون ریال ";  
                   }
                   else   print ":...............میلیون ریال";
                    
				    ?></span>  </td>
                   </tr>
				
	               <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" >نوع پمپ و دبی و کارخانه سازنده:<?php echo $Debi;?> لیتردرثانیه 
				   <br><?php echo $productpump;?></span>  </td>
				   
                   
                   
                   <td colspan="4"  <span class="f14_fontborder" >مبلغ وام (آورده غیرنقدی):<?php 
                   if ($applicantstatesIDsurat==45 || $applicantstatesIDsurat==43)
					{
                       if ($LastTotali==($currbelaavaz+$curcashhelp+$selfnotcashhelpvali))
                       echo $selfnotcashhelpvali;
                       else echo "اصلاحی :".round($LastTotali-$currbelaavaz-$curcashhelp); 
					   print " میلیون ریال"; 
					}
                    else   print "...............میلیون ریال";
                       
                   
                   ?></span>  </td>
                   
                   
                   
                   </tr>
				
				   <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" >نوع محصول:<?php echo $designsystemgroupsdetailyeild;?></span>  </td>
				   <td colspan="4"  <span class="f14_fontborder" >جمع کل هزینه های طرح:<?php 
                   if ($applicantstatesIDsurat==45 || $applicantstatesIDsurat==43)
				   {
                   echo $LastTotali."  میلیون ریال ";
				   }
                    else   print "...............میلیون ریال";
                    
				   ?></span>  </td>
                   </tr>
	
	               <tr> <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="6"  <span class="f14_fontborder" >درصد پیشرفت فیزیکی: 100%</span>  </td>
				   <td colspan="4"  <span class="f14_fontborder" >درصد پیشرفت مالی:100%</span>  </td>
                   </tr>
	
				   <tr> 
				   <td colspan="1"  >  </td>
					
				   <td colspan="1"  >  </td>
                   <td colspan="8"  <span class="f14_fontborder" >شرکتهای تولید کننده لوازم و تجهیزات</span>  </td>
	               </tr>
                      <?php     
					$rownj=$rown;
					for($i=1;$i<=(ceil($rown/2));$i++)
                    {  ?>      
						
	                 <tr> 
				   <td colspan="1"  >  </td>
					 <td colspan="1"  >  </td>
							<td colspan="1" <span class="f10_font"> <?php echo $i; ?>  </span> </td>
                            <td colspan="2" <span class="f10_font">  <?php echo  $alldata[$i][1]; ?> </span> </td>
                            <td colspan="1" <span class="f10_font">  <?php echo  $alldata[$i][2].' '.$alldata[$i][3]; ?> </span> </td>
                            
					<?php if ($rownj>=$rown/2)  $index=$rown/2; else   $index=ceil(($rownj)/2);?>
							
                            <td colspan="2" <span class="f10_font"> <?php echo ceil($rown/2)+$i; ?>  </span> </td>
                            <td colspan="1" <span class="f10_font">  <?php echo $alldata[$i+$index][1]; ?> </span> </td>
                            <td colspan="1" <span class="f10_font">  <?php echo $alldata[$i+$index][2].' '.$alldata[$i+$index][3]; ?> </span> </td>
                            
                     </tr>
				  
				  <?php } ?>
					
	                   <tr><td colspan="10"   > &nbsp; </td></tr>
                  <tr> 
				   <td colspan="2"  >  </td>
				   <td colspan="4"  <span class="f14_font" >مدیریت جهاد کشاورزی </span><br/><span class="f8_font" >مهر و امضاء </span>  </td>
				   <td colspan="4"  <span class="f14_font" >دستگاه نظارت </span><br/><span class="f8_font" >مهر و امضاء </span>  </td>
                   </tr>
                   
	
	                <tr><td colspan="10" class="f14_fonthigh" > &nbsp; </td></tr>
                   
	                <tr> 
				   <td colspan="2"  >  </td>
				   <td colspan="4"  <span class="f14_font" >نماینده بهره بردار </span><br/><span class="f8_font" >مهر و امضاء </span>  </td>
				   <td colspan="4"  <span class="f14_font" >شرکت مجری </span><br/><span class="f8_font" >مهر و امضاء </span>  </td>
                   </tr>
			         
                     
			</tbody>
		</table>
 </div>
</div>



            <!-- footer -->
			<?php  include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->
	
</body>	
</html>	
