
<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); ?>
<?php 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
 include('../includes/check_user.php'); ?>
<?php 
// توابع مرتبط با المنت های اچ تی امال صفحات
include('../includes/elements.php'); ?>
<?php


 // صفحه  گزارش پیشرفت پروزه یک بهرهبردار ولاگ های سیستمی که  برای این پروژه اتفاق افتاده است 
 
//echo $login_RolesID.'lll';

//نام جدول لیست طرح های طراحی 
$tblname='applicantmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");

//نقش هایی که مجوز ثبت گزارش پیشرفت دارند
//از جدول نقش های اخذ می شود
$permitrolsidforsave = array("1","2","4","13","14","17","18","5","10","20","21","23","31"); 

//در سابمیت صفحه عملیات زیر انجام می شود شامل باگذاری گزارش پیشرفت پروژه می باشد.
if (($_POST) && in_array($login_RolesID, $permitrolsidforsave))
{
    //درصورتی که مشاور ناظر طرح با مشاور ناظر ثبت شده این طرح متفاوت باشد از صفحه خارج می شود
    if ($_POST['DesignerCoIDnazer']!=$login_DesignerCoID && $login_RolesID=='10') header("Location: ../login.php");
  
    $OperatorCoID=$_POST['OperatorCoID'];
	//شناسه طرح
    $ApplicantMasterID=$_POST['ApplicantMasterID'];
	//
    // کجا استفاده شده است
	// احتمالا در یک صفحه دیگر گزارش اسناد بارگزاری شده
	
	$HeaderTitle=$_POST['IDreport'].'~~'.$_POST['IDreporttype'].'~~'.$_POST['HeaderTitle'];
	
    $Description=$_POST['Descriptionel'];
//	print $_POST['IDreport'].'*'.$_POST['IDreporttype'].$_POST['HeaderTitle'];
    
    //ثبت گزارش مشاور ناظر در جدول گزارش های پیشرفت
	//TRY CATCH()
	if ($_POST['IDreport']>0 || $_POST['IDreporttype']>0 || $_POST['HeaderTitle']>0) 
	{
	$sql="INSERT INTO applicantreports(ApplicantMasterID,HeaderTitle, Description,SaveTime,SaveDate,ClerkID)
            values ('$ApplicantMasterID','$HeaderTitle','$Description','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
	
    try 
    {		
        mysql_query($sql); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }

	$applicantreportsID=-1;
	
    $query = "select applicantreportsID from applicantreports where applicantreportsID = last_insert_id()";
    
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
    $applicantreportsID = $row['applicantreportsID'];
	
	}
	
	else 
	{	
	print "!خطا در ثبت : لطفا نوع گزارش  و گزارش عملیات را انتخاب نمایید";
    }
    //دریافت شناسه گزارش درج شده جهت استفاده در نام فایل بارگذاری
   
    
	
    
    //بارگذاری فایل گزارش مهندسین مشاور ناظر
    if (($_FILES["file1"]["size"] / 1024)<=200)
    {
        if ($_FILES["file1"]["error"] > 0) 
        {
            echo "Error: " . $_FILES["file1"]["error"] . "<br>";
        } 
        else 
        {
            $ext = end((explode(".", $_FILES["file1"]["name"])));
			
			//یک فایل خواهد بود ولی برای اطمینان از حقه برا حذف و در خط بعدی این فایل در مسیر ذخیره می شود 
            foreach (glob("../../upfolder/applicantreports/" . $applicantreportsID.'_1*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/applicantreports/" . $applicantreportsID.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
                    
        }        
    }
    
// if اتمام
//$post
    
    //header("Location: "."allapplicantstatesop.php");
}

else
	//اگر بار اول  لود صفحه باشد
{
	//نمونه لینک
	
   $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
	//شناسه طرح
    $ApplicantMasterID=$linearray[0];
	// نوع گزارش
	//type= می تواند شامل دو مقدار 4 و  خالی باشد
    //در صورتی که برابر4 باشد لینک ثبت ارزشیابی پیمانکار نمایش داده می شود  
    //در صورتی که خالی باشد لینک ارزشیابی پیمانکار نشان داده نمی شود
    $type=$linearray[1];
	//شرکت مشاور طراح
    $DesignerCoID=$linearray[2];
	//شناسه شرکت پیمانکار
    $OperatorCoID=$linearray[3];
	//شناسه وضعیت طرح
    $applicantstatesID=$linearray[4];
	
           
    //if ($DesignerCoID>0) 
    //    $ID=$DesignerCoID.'_1';
    //else if ($OperatorCoID>0) 
    //    $ID=$OperatorCoID.'_2'; 
}

if ($login_RolesID==1)
	//شرط برای کویری 
    $clerkidfilter="";
else
//4 و22 شناسه نقش هستند
    $clerkidfilter=" and clerkid not in (4,22)";

//کوئری دریافت مشخصات طرح جهت نمایش در بالای صفحه
$sql = "SELECT ApplicantName,DesignArea,shahr.cityname FROM applicantmaster 
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
where applicantmaster.ApplicantMasterID='$ApplicantMasterID' ";

//try 



try 
{		
    $result = mysql_query($sql); 
}
//catch exception
catch(Exception $e) 
{
    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
}
        
$row = mysql_fetch_assoc($result);
$ApplicantName=$row['ApplicantName'].' '.$row['DesignArea'].' هکتار شهرستان '.$row['cityname'] ;

//عنوان کالا که از اتصال ستون های مختلف از جداول میختلف می باشد عنوان قبل از تغییر
// قسمتی از کویری
//gadget3 
//spec2
//spec3sizeunits

$goodstitle="replace(replace(replace(replace(
CONCAT(
gadget2.Title,' '
,ifnull(materialtype.title,''),' '
,ifnull(gadget3.spec1,''),' '
,ifnull(gadget3.Title,''),' '
,ifnull(gadget3.size11,''),' '
,ifnull(operator.Title,''),' '
,ifnull(gadget3.size12,''),' '
,ifnull(gadget3.size13,''),' '
,ifnull(sizeunits.title,''),' '
,ifnull(gadget3.zavietoolsorattabaghe,''),' '
,ifnull(sizeunitszavietoolsorattabaghe.title,''),' '
,ifnull(gadget3.fesharzekhamathajm,''),' '
,ifnull(spec2.title,''),' '
,ifnull(sizeunitsfesharzekhamathajm.title,''),' '
,ifnull(spec3.Title,''),' '
,ifnull(gadget3.spec3size,''),' '
,ifnull(spec3sizeunits.title,''),'(',producers.title,'-',marks.title,')'),'  ',' '),'  ',' '),'  ',' '),'  ',' ')";

// قسمتی از کویری
//عنوان کالا که از اتصال ستون های مختلف از جداول میختلف می باشد  عنوان بعد از تغییر
$goodstitlen="replace(replace(replace(replace(CONCAT(
gadget2n.Title,' '
,ifnull(materialtypen.title,''),' '
,ifnull(gadget3n.spec1,''),' '
,ifnull(gadget3n.Title,''),' '
,ifnull(gadget3n.size11,''),' '
,ifnull(operatorn.Title,''),' '
,ifnull(gadget3n.size12,''),' '
,ifnull(gadget3n.size13,''),' '
,ifnull(sizeunitsn.title,''),' '
,ifnull(gadget3n.zavietoolsorattabaghe,''),' '
,ifnull(sizeunitszavietoolsorattabaghen.title,''),' '
,ifnull(gadget3n.fesharzekhamathajm,''),' '
,ifnull(spec2n.title,''),' '
,ifnull(sizeunitsfesharzekhamathajmn.title,''),' '
,ifnull(spec3n.Title,''),' '
,ifnull(gadget3n.spec3size,''),' '
,ifnull(spec3sizeunitsn.title,''),'(',producers.title,'-',marks.title,')'),'  ',' '),'  ',' '),'  ',' '),'  ',' ')";


/*کوئری گردش عملیات که از جدول tbl_log کلیه عملیات صورت گرفته روی طرح استخراج می شود








*/
//
///
//

if (! in_array($login_RolesID, array(1,13,14,31)))
$sqltrig="";
else

//union 1  تغییر وضعیت های طرح و تغییرات در مشخصات طرح
//tbl_log جدول لاگ ها
//در این جدول تغییرات مقادیر اطلاعات جداول مرتبط با طرح ذخیره می شود که دارای ستون های زیر می باشد
//applicantmaster_logID شاسه جدول لاگ
//tName نام جدولی که تغییر در آن رخ داده است
//tID شناسه رکورد از جدولی که تغییر در آن رخ داده است
//colname ستونی که تغییر در آن رخ داده است
//oldval مقدار قبل از تغییر
//newval مقدار بعد از تغییر
//SaveDate تاریخ ثبت این تغییر
//SaveTime ساعت ثبت این تغییر
//ClerkID شناسه کاربری که این تغییر را داده است


/* 
$sqltrig="union all
SELECT tbl_log.clerkid 
--  شناسه کاربر 
,tbl_log.SaveDate 
--  تاریخ ثبت تغییر

 , 1700 stateno 
 --  ترتیب تغییر
 
--  در سویچ کیس زیر بررسی می شودکه تغییر در کدام ستون انجام شده است
--  در صورتی که تغییر در ستونproposestate
--   انجام شده باشد به منزله تغییر وضعیت پیشنهاد قیمت اجرا می باشد 
--   مقادیر مختلف  ستونproposestate :
--   0 دریافت پیشنهاد
--   1 ارجاع پیشنهاد اجرا به مدیر آبیاری
--   2 ارجاع پیشنهاد اجرا  به ناظر عالی
--   3 مشخص نمودن منتخب پیشنهاد اجرا 
  
,case tbl_log.colname
when 'proposestate ' then 
case oldval=0 and newval=1 when 1 then 'ارجاع پیشنهاد اجرا به مدیر آبیاری' 
    else case oldval=0 and newval=2 when 1 then 'ارجاع سیستمی به   ناظر عالی' 
    else case oldval=1 and newval=2 when 1 then 'ارجاع پیشنهاد اجرا  به ناظر عالی' 
            else case oldval=2 and newval=3 when 1 then 'مشخص نمودن منتخب پیشنهاد اجرا ' 
                    else case oldval=3 and newval=2 when 1 then 'حذف برنده پیشنهاد اجرا ' 
                            else case newval=0 when 1 then 'بازگشت به دریافت پیشنهاد اجرا ' 
                                else '' end end end end end end
 
--  در صورتی که تغییر در ستون proposestatep
--   انجام شده باشد به منزله تغییر وضعیت پیشنهاد قیمت اجرا می باشد 
--   مقادیر مختلف  ستون proposestatep :
--   0 دریافت پیشنهاد
--   1 ارجاع پیشنهاد لوله  به مدیر آبیاری
--   2 ارجاع پیشنهاد لوله  به ناظر عالی
--   3 مشخص نمودن منتخب پیشنهاد لوله 
  
when 'proposestatep ' then 
case oldval=0 and newval=1 when 1 then 'ارجاع پیشنهاد لوله  به مدیر آبیاری' 
    else case oldval=1 and newval=2 when 1 then 'ارجاع پیشنهاد لوله  به ناظر عالی' 
            else case oldval=2 and newval=3 when 1 then 'مشخص نمودن منتخب پیشنهاد لوله ' 
                    else case oldval=3 and newval=2 when 1 then 'حذف برنده پیشنهاد لوله ' 
                            else case newval=0 when 1 then 'بازگشت به دریافت پیشنهاد لوله ' 
                                else '' end end end end end
-- private یکی از ویژگی های طرح می باشد که در صورتی که شرکت ها بخواهند طرح تستی و آزمایشی داشته باشند آنرا شخصی می کنند								
when 'private ' then case newval when 1 then 'شخصی نمودن طرح' else 'غیر شخصی نمودن طرح' end

-- CostPriceListMasterID شناسه سال هزینه های اجرایی طرح 
when 'CostPriceListMasterID' then concat('تغییر فهرست بهای طرح از ',ifnull(yearold.value,'-'),' به ',ifnull(yearnew.value,'-'))

--  creditsourceID شناسه جدول منبع تامین اعتبار
when 'creditsourceID' then concat(' تغییر منبع تامین اعتبار از ',ifnull(creditsourceold.title,'-'),' به ',ifnull(creditsourcenew.title,'-'))

-- شناسه مشاور بازبین
when 'DesignerCoIDnazer' then 
case applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' when 1 then 
concat(' تغییر مشاور بازبین از',ifnull(tbl_log.oldval,'-'),' به ',ifnull(tbl_log.newval,'-')) else
concat(' تغییر مشاور بازبین/ناظر از ',ifnull(designercoidnazerold.title,'-'),' به ',ifnull(designercoidnazernew.title,'-')) end

when 'DesignerID' then concat(' تغییر طراح از ',ifnull(designerold.LName,'-'),' به ',ifnull(designernew.LName,'-'))
when 'DesignSystemGroupsID' then concat(' تغییر سیستم آبیاری طرح ',ifnull(designsystemgroupsold.title,'-'),' به ',ifnull(designsystemgroupsnew.title,'-'))
else
case ifnull(COLUMN_COMMENT,'')<>'' when 1 then concat('تغییر ',COLUMN_COMMENT,' طرح از',oldval,' به ',newval) 
else concat('تغییر ',tbl_log.colname,' طرح از',oldval,' به ',newval) end end COLLATE utf8_general_ci Description,
-- تعیین اینکه  تغییر صورت گرفته در کدامیک از مراحل پروژه شامل طراحی، پیش فاکتور و صورت وضعیت رخ داده است
case applicantmasterdetail.ApplicantMasterID=tbl_log.tID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=tbl_log.tID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle

,clerk.CPI -- نام کاربر تغییر دهنده
,clerk.DVFS -- نام خانوادگی کاربر تغییر دهنده

,''  appstatesID -- وضعیت طرح
,'' ApplicantName -- عنوان پروژه
,'$login_DesignerCoID' DesignerCoIDnazer -- شرکت مهندسین مشاور
,0 applicantreportsID -- شناسه گزارش
FROM `tbl_log`
-- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
-- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
-- این جدول دارای ستون های ارتباطی زیر می باشد
-- ApplicantMasterID شناسه طرح مطالعاتی
-- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
-- ApplicantMasterIDsurat شناسه طرح صورت وضعیت



inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=tbl_log.tID or
applicantmasterdetail.ApplicantMasterIDmaster=tbl_log.tID or applicantmasterdetail.ApplicantMasterIDsurat=tbl_log.tID)
-- به شمای ستون های پایگاه داده  جوین زده شده تا رکودهایی که دارای اسم ستون معتبر هستند استخراج شوند
inner join INFORMATION_SCHEMA.COLUMNS cols  on cols.TABLE_SCHEMA = '$_server_db' and cols.TABLE_NAME='applicantmaster' and cols.COLUMN_NAME=tbl_log.colname
-- clerk جدول کاربران
left outer join clerk on clerk.clerkid=tbl_log.clerkid
-- costpricelistmaster هزینه های اجرایی طرح ها
left outer join costpricelistmaster costpricelistmasterold on costpricelistmasterold.costpricelistmasterID=case tbl_log.colname when 'CostPriceListMasterID' then tbl_log.oldval else 0 end
-- year جدول سال
left outer join year as yearold on yearold.YearID=costpricelistmasterold.YearID 
-- costpricelistmaster هزینه های اجرایی طرح ها
left outer join costpricelistmaster costpricelistmasternew on costpricelistmasternew.costpricelistmasterID=case tbl_log.colname when 'CostPriceListMasterID' then tbl_log.newval else 0 end
-- year جدول سال
left outer join year as yearnew on yearnew.YearID=costpricelistmasternew.YearID 
-- creditsource جدول منابع اعتباری
left outer join creditsource creditsourceold on creditsourceold.creditsourceID=case tbl_log.colname when 'creditsourceID' then tbl_log.oldval else 0 end
left outer join creditsource creditsourcenew on creditsourcenew.creditsourceID=case tbl_log.colname when 'creditsourceID' then tbl_log.newval else 0 end
-- designerco جدول شرکت های طراح
left outer join designerco designercoidnazerold on designercoidnazerold.designercoID=case tbl_log.colname when 'DesignerCoIDnazer' then tbl_log.oldval else 0 end
left outer join designerco designercoidnazernew on designercoidnazernew.designercoID=case tbl_log.colname when 'DesignerCoIDnazer' then tbl_log.newval else 0 end
-- designer جدول طراحان
left outer join designer designerold on designerold.DesignerID=case tbl_log.colname when 'DesignerID' then tbl_log.oldval else 0 end
left outer join designer designernew on designernew.DesignerID=case tbl_log.colname when 'DesignerID' then tbl_log.newval else 0 end
-- designsystemgroups سیستم آبیاری
left outer join designsystemgroups designsystemgroupsold on designsystemgroupsold.DesignSystemGroupsID=case tbl_log.colname when 'DesignSystemGroupsID' then tbl_log.oldval else 0 end
left outer join designsystemgroups designsystemgroupsnew on designsystemgroupsnew.DesignSystemGroupsID=case tbl_log.colname when 'DesignSystemGroupsID' then tbl_log.newval else 0 end

-- شرط هایی که بررسی می شوند اولا شناسه آن با شناسه طرح برابر باشد
-- ثانیا چون هر طرح در سه مرحله مطالعات، پیش فاکتور و صورت وضعیت می باشد و مرجع بلاعوض طرح مطالعات می باشد ما در شرط دوم بررسی کرده ایم که 
--  ستون تغییر یافته بلاعوض نباشد و اگر بلاعوض بود آن بلاعوض مطالعات باشد
WHERE `tbl_log`.`tName` = 'applicantmaster' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
and (tbl_log.colname<>'belaavaz' || (applicantmasterdetail.ApplicantMasterID=tbl_log.tID && tbl_log.colname='belaavaz'))

-- union 2 تغییرات در لوازم طرح
union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Number' then concat(' تغییر مقدار  کالای: ',$goodstitle,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'deactive' then case tbl_log.newval when 1 then concat(' حذف هزینه های اجرایی کالای: ',$goodstitle) else concat(' افزودن هزینه اجرایی کالای: ',$goodstitle) end  
when 'ToolsMarksID' then concat(' تغییر کالای: ',$goodstitle,' به ',$goodstitlen)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
-- ریز لوازم مورد استفاده در طرح
inner join invoicedetail on invoicedetail.invoicedetailid=tbl_log.tID
-- لیست عناوین پیش فاکتور
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid

-- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
-- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
-- این جدول دارای ستون های ارتباطی زیر می باشد
-- ApplicantMasterID شناسه طرح مطالعاتی
-- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
-- ApplicantMasterIDsurat شناسه طرح صورت وضعیت

inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=invoicemaster.ApplicantMasterID)
-- clerk جدول کاربران
left outer join clerk on clerk.clerkid=tbl_log.clerkid
-- جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
-- ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
-- gadget3ID شناسه سطح 3 ابزار
-- ProducersID شناسه جدول تولیدکننده
-- MarksID شناسه جدول مارک

inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID

-- جدول سطح سوم لوازم طرح
inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID

-- جدول سطح دوم لوازم طرح
inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
-- جدول واحدهای اندازه گیری کالا
left outer join units on gadget3.unitsID=units.unitsID
-- جدول مارک های کالا
inner join marks on marks.MarksID=toolsmarks.MarksID
--  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
-- جدول عملگر های تشکیل دهنده نام کالا
left outer join operator on operator.operatorID=gadget3.operatorID
-- مشخصه 2 کالا ها
left outer join spec2 on spec2.spec2id=gadget3.spec2id
-- مشخصه 3 کالا ها
left outer join spec3 on spec3.spec3id=gadget3.spec3id
--  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
--  نوع مواد ابزار مانند چدنی، پلی اتیلن و
left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
-- جدول تولیدکننده کالا
left outer join producers on producers.ProducersID=toolsmarks.ProducersID

-- مشابه فوق
left outer join toolsmarks toolsmarksn on toolsmarksn.ToolsMarksID=case tbl_log.colname when 'ToolsMarksID' then tbl_log.newval else 0 end
left outer join gadget3 gadget3n on gadget3n.gadget3ID=toolsmarksn.gadget3ID
left outer join gadget2 gadget2n on gadget2n.gadget2ID=gadget3n.gadget2ID
left outer join units unitsn on gadget3.unitsID=unitsn.unitsID
left outer join marks marksn on marksn.MarksID=toolsmarksn.MarksID
left outer join sizeunits  sizeunitszavietoolsorattabaghen on sizeunitszavietoolsorattabaghen.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajmn on sizeunitsfesharzekhamathajmn.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits sizeunitsn on sizeunitsn.SizeUnitsID=gadget3.sizeunitsID 
left outer join operator operatorn on operatorn.operatorID=gadget3.operatorID
left outer join spec2 spec2n on spec2n.spec2id=gadget3.spec2id
left outer join spec3 spec3n on spec3n.spec3id=gadget3.spec3id
left outer join sizeunits spec3sizeunitsn on spec3sizeunitsn.SizeUnitsID=gadget3.spec3sizeunitsid
left outer join materialtype materialtypen on materialtypen.materialtypeid=gadget3.materialtypeid
left outer join producers producersn on producersn.ProducersID=toolsmarks.ProducersID

-- با شرط اول رکوردهایی استخراج شوند که تغییرات مربوط به جدول ریز لوازم باشد
-- شرط دوم بررسی می کند که رکوردهایی را برگرداند شناسه مطالعات، پیش فاکتور و یا صورت وضعیت آن با شناسه طرح که از طریق متد گت گرفته ایم برابر باشد
WHERE `tbl_log`.`tName` = 'invoicedetail' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')

union 3 تغییر هزینه های اجرای طرح
union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Unit' then concat(' تغییر واحد هزینه اجرایی : ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'Code' then concat(' تغییر کد هزینه اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'Title' then concat('تغییر عنوان هزینه اجرایی: ',manuallistprice.title,' به ',tbl_log.newval) 
when 'Price' then concat(' تغییر مبلغ هزینه های اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'AddOrSub' then case tbl_log.newval when 1 then concat(' تغییر کسربها به اضافه بها: ',manuallistprice.title) else concat(' تغییر اضافه بها به کسربها: ',manuallistprice.title) end  
when 'Number' then concat('تغییر مقدار هزینه اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=manuallistprice.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=manuallistprice.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
-- manuallistprice جدول ثبت هزینه های اجرایی طرح
inner join manuallistprice on manuallistprice.manuallistpriceid=tbl_log.tID

-- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
-- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
-- این جدول دارای ستون های ارتباطی زیر می باشد
-- ApplicantMasterID شناسه طرح مطالعاتی
-- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
-- ApplicantMasterIDsurat شناسه طرح صورت وضعیت
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=manuallistprice.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=manuallistprice.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=manuallistprice.ApplicantMasterID)
-- جدول کاربران
left outer join clerk on clerk.clerkid=tbl_log.clerkid

-- شرط اول رکوردهایی را استخراج می کند که مربوط به هزینه های اجرایی طرح باشد
-- شرط دوم بررسی می کند که رکوردهایی را برگرداند شناسه مطالعات، پیش فاکتور و یا صورت وضعیت آن با شناسه طرح که از طریق متد گت گرفته ایم برابر باشد
WHERE `tbl_log`.`tName` = 'manuallistprice' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')

union 4 تغییر سایر فهارس بها
union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Price' then concat(' تغییر مبلغ فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'Number' then concat('تغییر مقدار فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'fehrestsID' then concat('تغییر مقدار فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=manuallistpriceall.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=manuallistpriceall.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
-- جدول فهارس بها
inner join manuallistpriceall on manuallistpriceall.manuallistpriceallid=tbl_log.tID
-- جدول نوع فهرست بها مثلا ابنیه، زه کشی
inner join fehrests on fehrests.fehrestsid=manuallistpriceall.fehrestsid
-- جدول سازه های طرح ها
inner join appfoundation on appfoundation.appfoundationid=manuallistpriceall.appfoundationid

-- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
-- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
-- این جدول دارای ستون های ارتباطی زیر می باشد
-- ApplicantMasterID شناسه طرح مطالعاتی
-- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
-- ApplicantMasterIDsurat شناسه طرح صورت وضعیت
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=manuallistpriceall.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=manuallistpriceall.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=manuallistpriceall.ApplicantMasterID)
-- جدول کاربران
left outer join clerk on clerk.clerkid=tbl_log.clerkid
-- جدول نوع فهرست بها مثلا ابنیه، زه کشی
left outer join fehrests fehrestsn on fehrestsn.fehrestsid=case tbl_log.colname when 'fehrestsid' then tbl_log.newval else 0 end
-- شرط اول رکودهایی را استخراج می کند که مربوط به جدول فهارس بها می باشد
-- شرط دوم رکودهایی که شناسه مربوط به جدول فهارس بهای آن بزرگتر از صفر باشد
-- شرط سوم بررسی می کند که رکوردهایی را برگرداند شناسه مطالعات، پیش فاکتور و یا صورت وضعیت آن با شناسه طرح که از طریق متد گت گرفته ایم برابر باشد
WHERE `tbl_log`.`tName` = 'manuallistpriceall' and tbl_log.tID>0
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')

union all
-- union 5 تغییر تجهیز و برچیدن کارگاه
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Price' then concat('تغییر مبلغ تجهیز و برچیدن کارگاه : ',equip.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=appequip.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=appequip.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
-- جدول تجهیز و برچیدن طرح ها
inner join appequip on appequip.appequipid=tbl_log.tID
-- جدول آیتم های تجهیز و برچیدن
inner join equip on equip.equipid=appequip.equipid
-- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
-- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
-- این جدول دارای ستون های ارتباطی زیر می باشد
-- ApplicantMasterID شناسه طرح مطالعاتی
-- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
-- ApplicantMasterIDsurat شناسه طرح صورت وضعیت
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=appequip.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=appequip.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=appequip.ApplicantMasterID)
-- جدول کاربران
left outer join clerk on clerk.clerkid=tbl_log.clerkid
-- شرط اول رکوردهایی را استخراج می کند که مربوط به جدول تجهیز و برچیدن کارگاه باشد
-- شرط دوم ردیف هایی که شناسه طرح آنها بزرگتر از صفر باشد
-- شرط سوم بررسی می کند که رکوردهایی را برگرداند شناسه مطالعات، پیش فاکتور و یا صورت وضعیت آن با شناسه طرح که از طریق متد گت گرفته ایم برابر باشد
WHERE `tbl_log`.`tName` = 'appequip'  and appequip.ApplicantMasterID>0
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
";
*/

$sqltrig="union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'proposestate ' then 
case oldval=0 and newval=1 when 1 then 'ارجاع پیشنهاد اجرا به مدیر آبیاری' 
    else case oldval=0 and newval=2 when 1 then 'ارجاع سیستمی به   ناظر عالی' 
    else case oldval=1 and newval=2 when 1 then 'ارجاع پیشنهاد اجرا  به ناظر عالی' 
            else case oldval=2 and newval=3 when 1 then 'مشخص نمودن منتخب پیشنهاد اجرا ' 
                    else case oldval=3 and newval=2 when 1 then 'حذف برنده پیشنهاد اجرا ' 
                            else case newval=0 when 1 then 'بازگشت به دریافت پیشنهاد اجرا ' 
                                else '' end end end end end end
 
when 'proposestatep ' then 
case oldval=0 and newval=1 when 1 then 'ارجاع پیشنهاد لوله  به مدیر آبیاری' 
    else case oldval=1 and newval=2 when 1 then 'ارجاع پیشنهاد لوله  به ناظر عالی' 
            else case oldval=2 and newval=3 when 1 then 'مشخص نمودن منتخب پیشنهاد لوله ' 
                    else case oldval=3 and newval=2 when 1 then 'حذف برنده پیشنهاد لوله ' 
                            else case newval=0 when 1 then 'بازگشت به دریافت پیشنهاد لوله ' 
                                else '' end end end end end
								
when 'private ' then case newval when 1 then 'شخصی نمودن طرح' else 'غیر شخصی نمودن طرح' end
when 'CostPriceListMasterID' then concat('تغییر فهرست بهای طرح از ',ifnull(yearold.value,'-'),' به ',ifnull(yearnew.value,'-'))
when 'creditsourceID' then concat(' تغییر منبع تامین اعتبار از ',ifnull(creditsourceold.title,'-'),' به ',ifnull(creditsourcenew.title,'-'))
when 'DesignerCoIDnazer' then 
case applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' when 1 then 
concat(' تغییر مشاور بازبین از',ifnull(tbl_log.oldval,'-'),' به ',ifnull(tbl_log.newval,'-')) else
concat(' تغییر مشاور بازبین/ناظر از ',ifnull(designercoidnazerold.title,'-'),' به ',ifnull(designercoidnazernew.title,'-')) end

when 'DesignerID' then concat(' تغییر طراح از ',ifnull(designerold.LName,'-'),' به ',ifnull(designernew.LName,'-'))
when 'DesignSystemGroupsID' then concat(' تغییر سیستم آبیاری طرح ',ifnull(designsystemgroupsold.title,'-'),' به ',ifnull(designsystemgroupsnew.title,'-'))
else
case ifnull(COLUMN_COMMENT,'')<>'' when 1 then concat('تغییر ',COLUMN_COMMENT,' طرح از',oldval,' به ',newval) 
else concat('تغییر ',tbl_log.colname,' طرح از',oldval,' به ',newval) end end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=tbl_log.tID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=tbl_log.tID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=tbl_log.tID or
applicantmasterdetail.ApplicantMasterIDmaster=tbl_log.tID or applicantmasterdetail.ApplicantMasterIDsurat=tbl_log.tID)
inner join INFORMATION_SCHEMA.COLUMNS cols  on cols.TABLE_SCHEMA = '$_server_db' and cols.TABLE_NAME='applicantmaster' and cols.COLUMN_NAME=tbl_log.colname
left outer join clerk on clerk.clerkid=tbl_log.clerkid
left outer join costpricelistmaster costpricelistmasterold on costpricelistmasterold.costpricelistmasterID=case tbl_log.colname when 'CostPriceListMasterID' then tbl_log.oldval else 0 end
left outer join year as yearold on yearold.YearID=costpricelistmasterold.YearID 
left outer join costpricelistmaster costpricelistmasternew on costpricelistmasternew.costpricelistmasterID=case tbl_log.colname when 'CostPriceListMasterID' then tbl_log.newval else 0 end
left outer join year as yearnew on yearnew.YearID=costpricelistmasternew.YearID 
left outer join creditsource creditsourceold on creditsourceold.creditsourceID=case tbl_log.colname when 'creditsourceID' then tbl_log.oldval else 0 end
left outer join creditsource creditsourcenew on creditsourcenew.creditsourceID=case tbl_log.colname when 'creditsourceID' then tbl_log.newval else 0 end
left outer join designerco designercoidnazerold on designercoidnazerold.designercoID=case tbl_log.colname when 'DesignerCoIDnazer' then tbl_log.oldval else 0 end
left outer join designerco designercoidnazernew on designercoidnazernew.designercoID=case tbl_log.colname when 'DesignerCoIDnazer' then tbl_log.newval else 0 end
left outer join designer designerold on designerold.DesignerID=case tbl_log.colname when 'DesignerID' then tbl_log.oldval else 0 end
left outer join designer designernew on designernew.DesignerID=case tbl_log.colname when 'DesignerID' then tbl_log.newval else 0 end
left outer join designsystemgroups designsystemgroupsold on designsystemgroupsold.DesignSystemGroupsID=case tbl_log.colname when 'DesignSystemGroupsID' then tbl_log.oldval else 0 end
left outer join designsystemgroups designsystemgroupsnew on designsystemgroupsnew.DesignSystemGroupsID=case tbl_log.colname when 'DesignSystemGroupsID' then tbl_log.newval else 0 end
WHERE `tbl_log`.`tName` = 'applicantmaster' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
and (tbl_log.colname<>'belaavaz' || (applicantmasterdetail.ApplicantMasterID=tbl_log.tID && tbl_log.colname='belaavaz'))

union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Number' then concat(' تغییر مقدار  کالای: ',$goodstitle,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'deactive' then case tbl_log.newval when 1 then concat(' حذف هزینه های اجرایی کالای: ',$goodstitle) else concat(' افزودن هزینه اجرایی کالای: ',$goodstitle) end  
when 'ToolsMarksID' then concat(' تغییر کالای: ',$goodstitle,' به ',$goodstitlen)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
inner join invoicedetail on invoicedetail.invoicedetailid=tbl_log.tID
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=invoicemaster.ApplicantMasterID)
left outer join clerk on clerk.clerkid=tbl_log.clerkid
inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
left outer join units on gadget3.unitsID=units.unitsID
inner join marks on marks.MarksID=toolsmarks.MarksID
left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
left outer join operator on operator.operatorID=gadget3.operatorID
left outer join spec2 on spec2.spec2id=gadget3.spec2id
left outer join spec3 on spec3.spec3id=gadget3.spec3id
left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
left outer join producers on producers.ProducersID=toolsmarks.ProducersID
left outer join toolsmarks toolsmarksn on toolsmarksn.ToolsMarksID=case tbl_log.colname when 'ToolsMarksID' then tbl_log.newval else 0 end
left outer join gadget3 gadget3n on gadget3n.gadget3ID=toolsmarksn.gadget3ID
left outer join gadget2 gadget2n on gadget2n.gadget2ID=gadget3n.gadget2ID
left outer join units unitsn on gadget3.unitsID=unitsn.unitsID
left outer join marks marksn on marksn.MarksID=toolsmarksn.MarksID
left outer join sizeunits  sizeunitszavietoolsorattabaghen on sizeunitszavietoolsorattabaghen.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajmn on sizeunitsfesharzekhamathajmn.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits sizeunitsn on sizeunitsn.SizeUnitsID=gadget3.sizeunitsID 
left outer join operator operatorn on operatorn.operatorID=gadget3.operatorID
left outer join spec2 spec2n on spec2n.spec2id=gadget3.spec2id
left outer join spec3 spec3n on spec3n.spec3id=gadget3.spec3id
left outer join sizeunits spec3sizeunitsn on spec3sizeunitsn.SizeUnitsID=gadget3.spec3sizeunitsid
left outer join materialtype materialtypen on materialtypen.materialtypeid=gadget3.materialtypeid
left outer join producers producersn on producersn.ProducersID=toolsmarks.ProducersID
WHERE `tbl_log`.`tName` = 'invoicedetail' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')


union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Unit' then concat(' تغییر واحد هزینه اجرایی : ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'Code' then concat(' تغییر کد هزینه اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval) 
when 'Title' then concat('تغییر عنوان هزینه اجرایی: ',manuallistprice.title,' به ',tbl_log.newval) 
when 'Price' then concat(' تغییر مبلغ هزینه های اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'AddOrSub' then case tbl_log.newval when 1 then concat(' تغییر کسربها به اضافه بها: ',manuallistprice.title) else concat(' تغییر اضافه بها به کسربها: ',manuallistprice.title) end  
when 'Number' then concat('تغییر مقدار هزینه اجرایی: ',manuallistprice.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=manuallistprice.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=manuallistprice.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
inner join manuallistprice on manuallistprice.manuallistpriceid=tbl_log.tID
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=manuallistprice.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=manuallistprice.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=manuallistprice.ApplicantMasterID)
left outer join clerk on clerk.clerkid=tbl_log.clerkid
WHERE `tbl_log`.`tName` = 'manuallistprice' 
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')


union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Price' then concat(' تغییر مبلغ فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'Number' then concat('تغییر مقدار فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
when 'fehrestsID' then concat('تغییر مقدار فهرست بهای: ',fehrests.title,' سازه ',appfoundation.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=manuallistpriceall.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=manuallistpriceall.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
inner join manuallistpriceall on manuallistpriceall.manuallistpriceallid=tbl_log.tID
inner join fehrests on fehrests.fehrestsid=manuallistpriceall.fehrestsid
inner join appfoundation on appfoundation.appfoundationid=manuallistpriceall.appfoundationid
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=manuallistpriceall.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=manuallistpriceall.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=manuallistpriceall.ApplicantMasterID)
left outer join clerk on clerk.clerkid=tbl_log.clerkid
left outer join fehrests fehrestsn on fehrestsn.fehrestsid=case tbl_log.colname when 'fehrestsid' then tbl_log.newval else 0 end
WHERE `tbl_log`.`tName` = 'manuallistpriceall' and tbl_log.tID>0
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')

union all
SELECT tbl_log.clerkid,tbl_log.SaveDate, 1700 stateno,
case tbl_log.colname
when 'Price' then concat('تغییر مبلغ تجهیز و برچیدن کارگاه : ',equip.title,' از ',tbl_log.oldval,' به ',tbl_log.newval)
end COLLATE utf8_general_ci Description,
case applicantmasterdetail.ApplicantMasterID=appequip.ApplicantMasterID when 1 then 'لیست طرح های طراحی'
else case applicantmasterdetail.ApplicantMasterIDmaster=appequip.ApplicantMasterID when 1 then 'لیست طرح های اجرایی (پیش فاکتور)' else 'لیست صورت وضعیت ها' end end applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID,'' ApplicantName,'$login_DesignerCoID' DesignerCoIDnazer,0 applicantreportsID
FROM `tbl_log`
inner join appequip on appequip.appequipid=tbl_log.tID
inner join equip on equip.equipid=appequip.equipid
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=appequip.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=appequip.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=appequip.ApplicantMasterID)
left outer join clerk on clerk.clerkid=tbl_log.clerkid
WHERE `tbl_log`.`tName` = 'appequip'  and appequip.ApplicantMasterID>0
and (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
";

//کوئری دریافت تغییر وضعیت های مختلف طرح
//یونیون دوم گزارشات آپلود شده مشاوران ناظر
//print $type;
$sql = "SELECT appchangestate.clerkid,appchangestate.SaveDate,appchangestate.stateno,appchangestate.Description,applicantstates.title applicantstatestitle
,clerk.CPI,clerk.DVFS,applicantstates.applicantstatesID  appstatesID
,applicantmaster.ApplicantName,case ifnull(applicantmasterdetail.nazerID,0) when 0 then 
tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end DesignerCoIDnazer,0 applicantreportsID 
FROM appchangestate 
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
inner join applicantmaster on applicantmaster.applicantmasterid='$ApplicantMasterID'
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
left outer join clerk on clerk.clerkid=appchangestate.clerkid
left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(tax_tbcity7digitnazer.id,5,3)='000'
where (applicantmasterdetail.ApplicantMasterID=appchangestate.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=appchangestate.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=appchangestate.ApplicantMasterID)


$sqltrig

union all
SELECT applicantreports.clerkid,applicantreports.SaveDate,10000 stateno,applicantreports.Description,applicantreports.HeaderTitle applicantstatestitle
,clerk.CPI,clerk.DVFS,''  appstatesID
,'' ApplicantName,$login_DesignerCoID DesignerCoIDnazer,applicantreportsID 
FROM applicantreports
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')

left outer join clerk on clerk.clerkid=applicantreports.clerkid

where (applicantmasterdetail.ApplicantMasterID=applicantreports.ApplicantMasterID or
applicantmasterdetail.ApplicantMasterIDmaster=applicantreports.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=applicantreports.ApplicantMasterID)



";

//print $sql;
//exit;
//کوئری نهایی که بر اساس تاریخ و شماره وضعیت مرتب شده است

//$login_limited "top select 5" in var  $login_limited    limit 5


$sql="select * from ($sql) view1 where 1=1 $clerkidfilter order by SaveDate desc,stateno desc ".$login_limited;
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
        

$row = mysql_fetch_assoc($result);
//print $applicantstatesID.'eee';
//print $row['DesignerCoIDnazer']."<br>$login_DesignerCoID";exit;

//echo 'sa';exit;

if ($row['DesignerCoIDnazer']!=$login_DesignerCoID && $login_RolesID=='10')
    header("Location: ../login.php");
	
  //گزارش ریز مراحل عملیاتی که مهندسین مشاور ناظر بارگذاری می کنند
	$query="
select 'تحويل زمين' _key,1 as _value union all 
select 'پياده كردن مسير' _key,2 as _value union all 
select 'تهيه و حمل لوازم طرح' _key,3 as _value union all
select 'حفر تراشه لوله گذاري' _key,4 as _value union all
select 'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه' _key,5 as _value union all
select 'لوله گذاري خط اصلي و فرعي و نصب اتصالات' _key,6 as _value union all
select 'ساختن حوضچه پمپاژ و فونداسيون' _key,7 as _value union all
select 'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي' _key,8 as _value union all
select 'ريختن خاك نرم يا سرندي روي لوله' _key,9 as _value union all
select 'تست شبكه' _key,10 as _value union all
select 'برگرداندن خاك درون تراشه' _key,11 as _value union all
select 'مونتاژ بالهاي آبياري و
پاشنده ها و گسيلنده ها' _key,12 as _value union all
select 'راه اندازي طرح' _key,13 as _value union all
select 'تحويل موقت' _key,14 as _value union all
select 'تحويل دائم' _key,15 as _value union all

select 'سایر' _key,16 as _value ";
$IDreport = get_key_value_from_query_into_array($query);


//گزارش انواع مختلف گزارشی که مهندسین مشاور بارگذاری می کنند
	$query="
select 'شروع عملیات' _key,1 as _value union all 
select 'عملیات در حال انجام' _key,2 as _value union all 
select 'اتمام عملیات' _key,3 as _value union all
select 'سایر' _key,4 as _value ";
$IDreporttype = get_key_value_from_query_into_array($query);

	
	
	
?>

<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پیشرفت طرح</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    

    </script>
    <!-- /scripts -->
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top منوی بالا با توجه به نقش  -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation     فعلا استفاده نشده-->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation      فعلا استفاده نشده-->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header             صفحه انتظار برای لود داده های اجکس -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content   enctype="multipart/form-data" for access upload file -->
			<div id="content">
			
            <form action="applicantstates_detail.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                          
                          <?php
                        echo "<h1 align='center'>گزارش پیشرفت طرح $ApplicantName </h1>";
                        //شناسه طرح
                        echo "<INPUT type='hidden' id='ApplicantMasterID' name='ApplicantMasterID' value='$ApplicantMasterID'/>";
                        //شناسه شرکت پیمانکار طرح
                        echo "<INPUT type='hidden' id='OperatorCoID' name='OperatorCoID' value='$OperatorCoID'/>";
                        //شناسه مهند مشاور ناظر طرح
                        echo "<INPUT type='hidden' id='DesignerCoIDnazer' name='DesignerCoIDnazer' value='$row[DesignerCoIDnazer]'/>";  
                          
						  //دریافت اطلاعات جدول زمانبندی اجرای پروژه
                        
                        
                        try {		
                                $res = mysql_query("select * from  applicanttiming 
                                            inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicanttiming.ApplicantMasterID or
                                            applicantmasterdetail.ApplicantMasterIDmaster=applicanttiming.ApplicantMasterID or applicantmasterdetail.ApplicantMasterIDsurat=
                                            applicanttiming.ApplicantMasterID)
                                            and ((applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or
                                            applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'))
                                            where  (RoleID='10') "); 
	                       }
                            //catch exception
                            catch(Exception $e) 
                            {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                            }
        
                        
						
                        $nums = mysql_num_rows($res);
						
                        
                        	// دکمه بازگشت به صفحه قبل
						  ?>
                          <div style = "text-align:left;">
						  
						  <a href=<?php echo $_SERVER['HTTP_REFERER'];
					  
                         
                           ?>>
						 <img style = "width: 25px;" src="../img/Return.png" title='بازگشت' ></a>
						 <?php 
						 /* <a href="../samplec/index.php/pages/pymankar/<?php echo $ID2; ?> " target="<?php echo $target;?>" >*/
						
                        // login_DesignerCoID شرکت طراح لاگین کرده بود
  					    if( !($login_DesignerCoID>0) && $nums==0 && $type==4) 
						     echo "<div style='float:left;color:red;padding-right:20px'>جهت ثبت امتیاز ارزشیابی پیمانکار لطفا جدول زمانبندی را تکمیل نمایید.</div>";
					 if($nums>0)  
						 {  
					 //login_isfulloption شرکت مشاور طرح قرارداد نظارت هم داشته باشد
					 //جدول login_OperatorCo filed login_isfulloption
					 //صفحه members_desighnercos.php 
					 
							if($login_OperatorCoID>0 && $login_isfulloption<>1)
							{
							print "";
							}
							else
							{
							 //لینک  ثبت امتیاز ارزشیابی 
							 //97-08-12
						  ?>
							<a  target='<?php echo $target;?>' href=<?php
                            print "../insert/pymankar_1qq.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.'_5_'.$applicantstatesID.rand(10000,99999); ?>>
                            <img style = "width: 35px;" src="../img/Editevaluate.jpg" title=' ثبت امتیاز ارزشیابی '>ارزشیابی</a>
						<?php 
							}
						 }
						 ?>
						 </div>
                    



					
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}
                    echo "</td>
                        </tr>
                   </tbody>
                </table>";
                
                   if ( ($OperatorCoID>0) && in_array($login_RolesID, $permitrolsidforsave))
                    {
					   //کومبوباکس نوع گزارش
					   echo  "<td>".select_option('IDreport','نوع گزارش:',',',$IDreport,0,'','','1','rtl',0,'',$IDreport,'','30%');
					   
                       //کومبوباکس که ریز عملیات مرتبط با  گزارش را نشان می دهد
					    echo   select_option('IDreporttype','گزارش عملیات:',',',$IDreporttype,0,'','','1','rtl',0,'',$IDreporttype,'','20%');
					     echo "</td>";
                         //عنوان گزارش
                         echo "<td  class='label'>عنوان</td><td><textarea id='HeaderTitle' name='HeaderTitle' rows='3'  cols='15' >$stateno</textarea></td>";
                         //شرح گزارش
                         echo "<td  class='label'>شرح</td><td class='data'><textarea id='Descriptionel' colspan='2' name='Descriptionel' rows='3' cols='80'  ></textarea></td>";
                         //فایل بارگذاری گزارش
                         echo "<td colspan='1' class='data'><input type='file' name='file1' id='file1'>(حداکثر 200 کیلوبایت)</td>";   
                         
                        //دکمه ثبت و بارگذاری گزارش
                        echo "<td><input onClick=\"return confirm('مطمئن هستید که ثبت شود ؟');\" name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" 
                        value=\"ثبت\" /></td>";
		       ?>
								
		                 
    <?php 						
                    }
					
                    
                ?>
				
				
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th >ردیف</th>
                            <th >وضعیت/عنوان</th>
                            <th >تاریخ</th>
                            <th >توضیحات</th>
                            <th >کاربر</th>
                            <th ></th>
                        </tr>
                    </thead>
               
                   <?php
                    $stateno=0;
                    
                    $oSaveDate = "";
                    $oapplicantstatestitle="";
                    $oCPI="";
                    $oDVFS="";
                    $oDescription="";
                    $oapplicantreportsID="";
                    
                    mysql_data_seek( $result, 0 );
                    //چاپ ردیف های گزارش
            while($row = mysql_fetch_assoc($result))
            {
			
			
								
				
                        if ($row['stateno']==1700 && ( ($oSaveDate == $row['SaveDate'] && $oapplicantstatestitle==$row['applicantstatestitle']
                        && $oCPI==$row['CPI'] && $oDVFS==$row['DVFS'])||($oSaveDate =="" && $oapplicantstatestitle==""
                        && $oCPI=="" && $oDVFS=="") ) )
                        {
                            $oSaveDate = $row['SaveDate'];
                            $oapplicantstatestitle=$row['applicantstatestitle'];
                            $oCPI=$row['CPI'];
                            $oDVFS=$row['DVFS'];
                            $oapplicantreportsID=$row['applicantreportsID'];
                            $oDescription.="\n".$row['Description'];
                                continue;
                        }
                        else
                        {
                          if ($oSaveDate!="")
                          {  
                        
                        $fstr1="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/applicantreports/';
                        $handler = opendir($directory);
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                $No=$linearray[1];
                                if (($ID==$oapplicantreportsID) && ($No==1) )
                                    $fstr1="<td><a href='../../upfolder/applicantreports/$file' >
                                    <img style = \"width: 75%;\" src=\"../img/mail.png\" title='thdg' ></a></td>";
                                
                            }
                        }
                        $encrypted_string=$oCPI;
                        $encryption_key="!@#$8^&*";
                        $decrypted_string="";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                        $encrypted_string=$oDVFS;
                        $encryption_key="!@#$8^&*";
                        $decrypted_string.=" ";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
	
    
                        $name = $decrypted_string;
						$linearray = explode('~~',$oapplicantstatestitle);
                                $IDreport=$linearray[0];
                                $IDreporttype=$linearray[1];
								$applicantstatestitle=$linearray[2];
	
                 switch ($IDreport) 
                  {
                    case 1: $IDreport= 'تحويل زمين'; break; 
                    case 2: $IDreport= 'پياده كردن مسير'; break; 
                    case 3: $IDreport= 'تهيه و حمل لوازم طرح'; break; 
                    case 4: $IDreport= 'حفر تراشه لوله گذاري'; break; 
                    case 5: $IDreport= 'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه'; break; 
                    case 6: $IDreport= 'لوله گذاري خط اصلي و فرعي و نصب اتصالات'; break; 
                    case 7: $IDreport= 'ساختن حوضچه پمپاژ و فونداسيون'; break; 
                    case 8: $IDreport= 'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي'; break; 
                    case 9: $IDreport= 'ريختن خاك نرم يا سرندي روي لوله'; break; 
                    case 10: $IDreport= 'تست شبكه'; break; 
                    case 11: $IDreport= 'برگرداندن خاك درون تراشه'; break; 
                    case 12: $IDreport= 'مونتاژ بالهاي آبياري و
                    پاشنده ها و گسيلنده ها'; break; 
                    case 13: $IDreport= 'راه اندازي طرح'; break; 
                    case 14: $IDreport= 'تحويل موقت'; break; 
                    case 15: $IDreport= 'تحويل دائم'; break; 
                    case 16: $IDreport= ' '; break; 
                   }
                 
                switch ($IDreporttype) 
                  {
                    case 1: $IDreporttype='شروع عملیات'; break; 
                    case 2: $IDreporttype='عملیات در حال انجام'; break; 
                    case 3: $IDreporttype='اتمام عملیات'; break; 
                    case 4: $IDreporttype=''; break; 
                   }
	
	 //   print     $IDreport.'$'.$IDreporttype.'$'.$applicantstatestitle;
					
                        $SaveDate = $oSaveDate;
                        $stateno++;
                        $Description = $oDescription;
                        $XL=floor(strlen($Description)/100)+1;
                        if (strlen($Description)>600)
                            print "
                              
                            <tr>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$stateno</textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$IDreporttype $IDreport \n $applicantstatestitle </textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                            <td>
                            <textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$Description</textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$name</textarea></td>
                            $fstr1
                            </tr>
                              "; 
                            else if (strlen($Description)>100)
                            print "
                              
                            <tr >
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$stateno</textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$IDreporttype $IDreport \n $applicantstatestitle </textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                            <td>
                            <textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$Description</textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$name</textarea></td>
                            $fstr1
                            </tr>
                              "; 
                              else 
                            print "
                            <tr >
                            <td><input id='Description' name='Description' value='$stateno' size=7% readonly></input></td>
                            <td><input id='Description' name='Description' value='$IDreporttype $IDreport \n $applicantstatestitle' size=40% readonly></input></td>
                            <td><input id='Description' name='Description' value='".gregorian_to_jalali($SaveDate)."' size=12%  readonly></input></td>
                            <td><input id='Description' name='Description' value='$Description' size=120% readonly></input></td>
                            <td><input id='Description' name='Description' value='$name' size=30% readonly></input></td>
                            $fstr1
                            </tr>
                              ";
                            }
                            $oSaveDate = $row['SaveDate'];
                            $oapplicantstatestitle=$row['applicantstatestitle'];
                            $oCPI=$row['CPI'];
                            $oDVFS=$row['DVFS'];
                            $oDescription=$row['Description'];
                            $oapplicantreportsID=$row['applicantreportsID'];
                        }

            }
                   $fstr1="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/applicantreports/';
                        $handler = opendir($directory);
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                $No=$linearray[1];
                                if (($ID==$oapplicantreportsID) && ($No==1) )
                                    $fstr1="<td><a href='../../upfolder/applicantreports/$file' >
                                    <img style = \"width: 75%;\" src=\"../img/mail.png\" title='thdg' ></a></td>";
                                
                            }
                        }
                        $encrypted_string=$oCPI;
                        $encryption_key="!@#$8^&*";
                        $decrypted_string="";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                        $encrypted_string=$oDVFS;
                        $encryption_key="!@#$8^&*";
                        $decrypted_string.=" ";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
	
    
                        $name = $decrypted_string;
						$linearray = explode('~~',$oapplicantstatestitle);
                                $IDreport=$linearray[0];
                                $IDreporttype=$linearray[1];
								$applicantstatestitle=$linearray[2];
	
                 switch ($IDreport) 
                  {
                    case 1: $IDreport= 'تحويل زمين'; break; 
                    case 2: $IDreport= 'پياده كردن مسير'; break; 
                    case 3: $IDreport= 'تهيه و حمل لوازم طرح'; break; 
                    case 4: $IDreport= 'حفر تراشه لوله گذاري'; break; 
                    case 5: $IDreport= 'رگلاژ و ريختن خاك نرم يا سرندي كف تراشه'; break; 
                    case 6: $IDreport= 'لوله گذاري خط اصلي و فرعي و نصب اتصالات'; break; 
                    case 7: $IDreport= 'ساختن حوضچه پمپاژ و فونداسيون'; break; 
                    case 8: $IDreport= 'نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي'; break; 
                    case 9: $IDreport= 'ريختن خاك نرم يا سرندي روي لوله'; break; 
                    case 10: $IDreport= 'تست شبكه'; break; 
                    case 11: $IDreport= 'برگرداندن خاك درون تراشه'; break; 
                    case 12: $IDreport= 'مونتاژ بالهاي آبياري و
                    پاشنده ها و گسيلنده ها'; break; 
                    case 13: $IDreport= 'راه اندازي طرح'; break; 
                    case 14: $IDreport= 'تحويل موقت'; break; 
                    case 15: $IDreport= 'تحويل دائم'; break; 
                    case 16: $IDreport= ' '; break; 
                   }
                 
                switch ($IDreporttype) 
                  {
                    case 1: $IDreporttype='شروع عملیات'; break; 
                    case 2: $IDreporttype='عملیات در حال انجام'; break; 
                    case 3: $IDreporttype='اتمام عملیات'; break; 
                    case 4: $IDreporttype=''; break; 
                   }
	
	 //   print     $IDreport.'$'.$IDreporttype.'$'.$applicantstatestitle;
					
                        $SaveDate = $oSaveDate;
                        $stateno++;
                        $Description = $oDescription;
                        $XL=floor(strlen($Description)/100)+1;
                        if (strlen($Description)>600)
                            print "
                              
                            <tr>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$stateno</textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$IDreporttype $IDreport \n $applicantstatestitle </textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                            <td>
                            <textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$Description</textarea></td>
                            <td><textarea id='Description' name='Description' rows='8'  style='width: 100%;' readonly>$name</textarea></td>
                            $fstr1
                            </tr>
                              "; 
                            else if (strlen($Description)>100)
                            print "
                              
                            <tr>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$stateno</textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$IDreporttype $IDreport \n $applicantstatestitle </textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                            <td>
                            <textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$Description</textarea></td>
                            <td><textarea id='Description' name='Description' rows='$XL'  style='width: 100%;' readonly>$name</textarea></td>
                            $fstr1
                            </tr>
                              "; 
                              else 
                            print "
                            <tr>
                            <td><input id='Description' name='Description' value='$stateno' size=7% readonly></input></td>
                            <td><input id='Description' name='Description' value='$IDreporttype $IDreport \n $applicantstatestitle' size=40% readonly></input></td>
                            <td><input id='Description' name='Description' value='".gregorian_to_jalali($SaveDate)."' size=12%  readonly></input></td>
                            <td><input id='Description' name='Description' value='$Description' size=120% readonly></input></td>
                            <td><input id='Description' name='Description' value='$name' size=30% readonly></input></td>
                            $fstr1
                            </tr>
                              ";
                    

?>
                    
                   
                </table>
                        
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
