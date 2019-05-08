<?php 
/*

//appinvestigation/allapplicantquotatotal.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

-
*/

include('../includes/connect.php'); 
include('../includes/check_user.php');
include('../includes/functions.php');


/*
کوئری طرحح ها انعقاد قرارداد برگشتی که هنوز انعقاد قرارداد نشده اند
select * from applicantmaster where ApplicantMasterID in (
SELECT distinct(`ApplicantMasterID`) FROM `appchangestate` WHERE `applicantstatesID` in (33,50)
and ApplicantMasterID in (select ApplicantMasterID from appchangestate where `applicantstatesID` in (37,22))
and ApplicantMasterID not in (select ApplicantMasterID from applicantmaster  where `applicantstatesID` in (37,22))
) ORDER BY `applicantmaster`.`applicantstatesID` ASC
*/

$Datefrom=$_POST['Datefrom'];//از تاریخ
$Dateto=$_POST['Dateto'];//تا تاریخ
$Datefroms=jalali_to_gregorian($_POST['Datefrom']);//از تاریخ شمسی
$Datetos=jalali_to_gregorian($_POST['Dateto']);//تا تاریخ شمسی
 
if ($login_Permission_granted==0) header("Location: ../login.php");
//شناسه وضعیت طرح های در وضعیت طراحی
$indesignstates=array("2","3","4","5","6","7","11","25","46");
$showc=0;//نمایش طرح های با اعتبار بانک یا صندوق
$showa=0;//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
if ($_POST['showc']=='on')
    $showc=1;
if ($_POST['showa']=='on')
    $showa=1;
$yearid=$_POST['YearID'];//سال نمایش پروژه ها
$cond="";//محدودیت های پروژه

    if (strlen(trim($_POST['Datefrom']))>0)//از تاریخ
    /*
    applicantmasteroplist جدول مشخصات طرح صورت وضعیت
    applicantmasterop جدول مشخصات طرح اجرایی
    applicantmaster مشخصات طرح صورت وضعیت
    TMDate آخرین تاریخ تغییر وضعیت
    */
        $cond.=" and case applicantmasteroplist.TMDate>0 when 1 then applicantmasteroplist.TMDate else 
        case applicantmasterop.TMDate>0 when 1 then applicantmasterop.TMDate else applicantmaster.TMDate end  end>='$Datefroms'";
    if (strlen(trim($_POST['Dateto']))>0)//تا تاریخ
        $cond.=" and case applicantmasteroplist.TMDate>0 when 1 then applicantmasteroplist.TMDate else 
        case applicantmasterop.TMDate>0 when 1 then applicantmasterop.TMDate else applicantmaster.TMDate end  end <='$Datetos'";
        

if ($yearid>0) $cond.="and applicantmaster.YearID='$yearid'";//فیلتر سال پروژه  
/*
    value جدول سهمیه شهرستان
    year جدول سالها
    YearID شناسه سال
*/
    $sql = "SELECT value  FROM year where YearID='$_POST[YearID]' ";
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
 if ($row['value']>0)   $yearvalue="سال".$row['value'];

$creditsourceID=$_POST['creditsourceID'];//شناسه منبع تامین اعتبار
if ($creditsourceID>0) $cond.="and applicantmaster.creditsourceID='$creditsourceID'"; 
if ($_POST['city']>0)//فیلتر شهر انتخابی
{
    $selectedCity=$_POST['city'];
    $cond.="and substring(applicantmaster.cityid,1,4)=substring($selectedCity,1,4)";
}

if ($_POST['DesignSystemGroupsID']>0)//فیلتر سیستم آبیاری انتخابی
{
    $DesignSystemGroupsID=$_POST['DesignSystemGroupsID'];
    $cond.="and applicantmaster.DesignSystemGroupsID=$DesignSystemGroupsID ";
}


$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)//شناسه استان
$selectedCityId=$_POST['ostan'];
$cond.="and substring(applicantmaster.cityid,1,2)=substring($selectedCityId,1,2)";//نمایش طرح های شهرستان انتخاب شده

if ($showc==1) $cond.=" and ifnull(applicantmaster.criditType,0)=1 ";//تجمیع باشد
if ($showc==1) $head.="طرح های تجمیع";


if ($login_RolesID=='16')//صندوق
{
    $cond.=" and creditsource.creditbank=2";   
} 
else   if ($login_RolesID=='7')//بانک
{
    $cond.=" and creditsource.creditbank=1"; 
}

    
if ($login_RolesID=='17') //ناظر مقیم
    $cond.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";//فیلتر طرح های شهر ناظر مقیم مربوطه
else if (($login_RolesID=='14') && ($showa==0))//ناظر عالی 
        $cond.=" and substring(applicantmaster.cityid,1,4) 
		in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";//فیلتر شهرهای ناظر عالی

$credityear=$_POST['credityear'];//سال اعتبار
if ($credityear>0) $cond.="and creditsource.credityear='$credityear'"; 
  
if ($_POST['clerksup']>0)//کارشناس
{
    $selectedsupId=$_POST['clerksup'];
    $cond.=" and substring(applicantmaster.cityid,1,4) 
        in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$selectedsupId') ";//فیلتر شهرهای کارشناس انتخابی
}
try 
    {		
        $result = mysql_query(retqueryaggregated($cond).$login_limited);
    }
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }  

/*
$total1 //مجموع هزینه های  کل طرح ها
$total2 //مجموع هزینه های مرحله طراحی
$total3 //مجموع هزینه های تکمیل پرونده
$total4 //مجموع هزینه های ارسال به صندوق یا بانک دریافت پیشنهاد
$total5 //مجموع هزینه های تکمیل تضامین
$total6 //مجموع هزینه های انعقاد قرارداد و دریافت پیشنهاد
$total7 //مجموع هزینه های درحال پیشنهاد قیمت
$total8 //مجموع هزینه های تهیه پیش فاکتور
$total9 //مجموع هزینه های تایید نهایی پیشفاکتور و آزادسازی 
$total10 //مجموع هزینه های درحال اجرا
$total11 //مجموع هزینه های آزادسازی ظرفیت
$total12 //مجموع هزینه های ـحویل موقت
$total13 //مجموع هزینه های ـحویل دائم
$total14 //مجموع هزینه های انصراف از اجرا
$total15 //متغیر موقت
$total16 //متغیر موقت
*/
$total1=0;
$total2=0;
$total3=0;
$total4=0;
$total5=0;
$total6=0;
$total7=0;
$total8=0;
$total9=0;
$total10=0;
$total11=0;
$total12=0;
$total13=0;
$total14=0;
$total15=0;
$total16=0;
/*
$hek1 //مجموع هکتار کل طرح ها
$hek2 //مجموع هکتارمرحله طراحی
$hek3 //مجموع هکتارتکمیل پرونده
$hek4 //مجموع هکتارارسال به صندوق یا بانک دریافت پیشنهاد
$hek5 //مجموع هکتارتکمیل تضامین
$hek6 //مجموع هکتارانعقاد قرارداد و دریافت پیشنهاد
$hek7 //مجموع هکتاردرحال پیشنهاد قیمت
$hek8 //مجموع هکتارتهیه پیش فاکتور
$hek9 //مجموع هکتارتایید نهایی پیشفاکتور و آزادسازی 
$hek10 //مجموع هکتاردرحال اجرا
$hek11 //مجموع هکتارآزادسازی ظرفیت
$hek12 //مجموع هکتارـحویل موقت
$hek13 //مجموع هکتارـحویل دائم
$hek14 //مجموع هکتارانصراف از اجرا
*/

$hek1=0;
$hek2=0;
$hek3=0;
$hek4=0;
$hek5=0;
$hek6=0;
$hek7=0;
$hek8=0;
$hek9=0;
$hek10=0;
$hek11=0;
$hek112=0;
$hek13=0;
$hek14=0;
/*
$bela1 //مجموع بلاعوض  کل طرح ها
$bela2 //مجموع بلاعوض مرحله طراحی
$bela3 //مجموع بلاعوض تکمیل پرونده
$bela4 //مجموع بلاعوض ارسال به صندوق یا بانک دریافت پیشنهاد
$bela5 //مجموع بلاعوض تکمیل تضامین
$bela6 //مجموع بلاعوض انعقاد قرارداد و دریافت پیشنهاد
$bela7 //مجموع بلاعوض درحال پیشنهاد قیمت
$bela8 //مجموع بلاعوض تهیه پیش فاکتور
$bela9 //مجموع بلاعوض تایید نهایی پیشفاکتور و آزادسازی 
$bela10 //مجموع بلاعوض درحال اجرا
$bela11 //مجموع بلاعوض آزادسازی ظرفیت
$bela12 //مجموع بلاعوض ـحویل موقت
$bela13 //مجموع بلاعوض ـحویل دائم
$bela14 //مجموع بلاعوض انصراف از اجرا
*/

$bela1=0;
$bela2=0;
$bela3=0;
$bela4=0;
$bela5=0;
$bela6=0;
$bela7=0;
$bela8=0;
$bela9=0;
$bela10=0;
$bela11=0;
$bela12=0;
$bela13=0;
$bela14=0;
/*
$bela1 //مجموع خودیاری  کل طرح ها
$bela2 //مجموع خودیاری مرحله طراحی
$bela3 //مجموع خودیاری تکمیل پرونده
$bela4 //مجموع خودیاری ارسال به صندوق یا بانک دریافت پیشنهاد
$bela5 //مجموع خودیاری تکمیل تضامین
$bela6 //مجموع خودیاری انعقاد قرارداد و دریافت پیشنهاد
$bela7 //مجموع خودیاری درحال پیشنهاد قیمت
$bela8 //مجموع خودیاری تهیه پیش فاکتور
$bela9 //مجموع خودیاری تایید نهایی پیشفاکتور و آزادسازی 
$bela10 //مجموع خودیاری درحال اجرا
$bela11 //مجموع خودیاری آزادسازی ظرفیت
$bela12 //مجموع خودیاری ـحویل موقت
$bela13 //مجموع خودیاری ـحویل دائم
$bela14 //مجموع خودیاری انصراف از اجرا
*/

$self1=0;
$self2=0;
$self3=0;
$self4=0;
$self5=0;
$self6=0;
$self7=0;
$self8=0;
$self9=0;
$self10=0;
$self11=0;
$self12=0;
$self13=0;
$self14=0;
/*
$lasttotal1 //مجموع مبلغ  کل طرح ها
$lasttotal2 //مجموع مبلغ مرحله طراحی
$lasttotal3 //مجموع مبلغ تکمیل پرونده
$lasttotal4 //مجموع مبلغ ارسال به صندوق یا بانک دریافت پیشنهاد
$lasttotal5 //مجموع مبلغ تکمیل تضامین
$lasttotal6 //مجموع مبلغ انعقاد قرارداد و دریافت پیشنهاد
$lasttotal7 //مجموع مبلغ درحال پیشنهاد قیمت
$lasttotal8 //مجموع مبلغ تهیه پیش فاکتور
$lasttotal9 //مجموع مبلغ تایید نهایی پیشفاکتور و آزادسازی 
$lasttotal10 //مجموع مبلغ درحال اجرا
$lasttotal11 //مجموع مبلغ آزادسازی ظرفیت
$lasttotal12 //مجموع مبلغ ـحویل موقت
$lasttotal13 //مجموع مبلغ ـحویل دائم
$lasttotal14 //مجموع مبلغ انصراف از اجرا
*/

$lasttotal1=0;
$lasttotal2=0;
$lasttotal3=0;
$lasttotal4=0;
$lasttotal5=0;
$lasttotal6=0;
$lasttotal7=0;
$lasttotal8=0;
$lasttotal9=0;
$lasttotal10=0;
$lasttotal11=0;
$lasttotal12=0;
$lasttotal13=0;
$lasttotal14=0;
/*
$lastfehrest1 //مجموع فهرست بهای  کل طرح ها
$lastfehrest2 //مجموع فهرست بهای مرحله طراحی
$lastfehrest3 //مجموع فهرست بهای تکمیل پرونده
$lastfehrest4 //مجموع فهرست بهای ارسال به صندوق یا بانک دریافت پیشنهاد
$lastfehrest5 //مجموع فهرست بهای تکمیل تضامین
$lastfehrest6 //مجموع فهرست بهای انعقاد قرارداد و دریافت پیشنهاد
$lastfehrest7 //مجموع فهرست بهای درحال پیشنهاد قیمت
$lastfehrest8 //مجموع فهرست بهای تهیه پیش فاکتور
$lastfehrest9 //مجموع فهرست بهای تایید نهایی پیشفاکتور و آزادسازی 
$lastfehrest10 //مجموع فهرست بهای درحال اجرا
$lastfehrest11 //مجموع فهرست بهای آزادسازی ظرفیت
$lastfehrest12 //مجموع فهرست بهای ـحویل موقت
$lastfehrest13 //مجموع فهرست بهای ـحویل دائم
$lastfehrest14 //مجموع فهرست بهای انصراف از اجرا
*/

$lastfehrest1=0;
$lastfehrest2=0;
$lastfehrest3=0;
$lastfehrest4=0;
$lastfehrest5=0;
$lastfehrest6=0;
$lastfehrest7=0;
$lastfehrest8=0;
$lastfehrest9=0;
$lastfehrest10=0;
$lastfehrest11=0;
$lastfehrest12=0;
$lastfehrest13=0;
$lastfehrest14=0;
/*
$Totlainvoice1 //مجموع لوازم  کل طرح ها
$Totlainvoice2 //مجموع لوازم مرحله طراحی
$Totlainvoice3 //مجموع لوازم تکمیل پرونده
$Totlainvoice4 //مجموع لوازم ارسال به صندوق یا بانک دریافت پیشنهاد
$Totlainvoice5 //مجموع لوازم تکمیل تضامین
$Totlainvoice6 //مجموع لوازم انعقاد قرارداد و دریافت پیشنهاد
$Totlainvoice7 //مجموع لوازم درحال پیشنهاد قیمت
$Totlainvoice8 //مجموع لوازم تهیه پیش فاکتور
$Totlainvoice9 //مجموع لوازم تایید نهایی پیشفاکتور و آزادسازی 
$Totlainvoice10 //مجموع لوازم درحال اجرا
$Totlainvoice11 //مجموع لوازم آزادسازی ظرفیت
$Totlainvoice12 //مجموع لوازم ـحویل موقت
$Totlainvoice13 //مجموع لوازم ـحویل دائم
$Totlainvoice14 //مجموع لوازم انصراف از اجرا
*/

$Totlainvoice1=0;
$Totlainvoice2=0;
$Totlainvoice3=0;
$Totlainvoice4=0;
$Totlainvoice5=0;
$Totlainvoice6=0;
$Totlainvoice7=0;
$Totlainvoice8=0;
$Totlainvoice9=0;
$Totlainvoice10=0;
$Totlainvoice11=0;
$Totlainvoice12=0;
$Totlainvoice13=0;
$Totlainvoice14=0;



while($row = mysql_fetch_assoc($result))
{   
    /*
    $row['DesignAread'] مساحت
    $row['belaavazd'] بلاعوض
    $row['LastTotald'] کل هزینه ها
    $row['LastFehrestbahad'] فهرست بها
    $row['TotlainvoiceValuesd'] مبلغ کل پیش فاکتورها
    $row['selfnotcashhelpval'] مبلغ خودیاری غیر نقدی
    $row['selfcashhelpval'] مبلغ خودیاری نقدی
    */
    //کل طرح ها
    $total1++;
    if ($id==1){if ($total1%2==1) $b='b'; else $b=''; echorow($row,$total1,$b);}
    $hek1+=$row['DesignAread'];
    $bela1+=$row['belaavazd'];
    $lasttotal1+=$row['LastTotald'];
    $lastfehrest1+=$row['LastFehrestbahad'];
    $Totlainvoice1+=$row['TotlainvoiceValuesd'];
    $self1+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
    if (in_array($row['applicantstatesIDd'],$indesignstates))//درحال طراحی
    {
        $total2++;
    if ($id==2){if ($total2%2==1) $b='b'; else $b=''; echorow($row,$total2,$b);}
        $hek2+=$row['DesignAread'];
        $bela2+=$row['belaavazd'];
        $lasttotal2+=$row['LastTotald'];
        $lastfehrest2+=$row['LastFehrestbahad'];
        $Totlainvoice2+=$row['TotlainvoiceValuesd'];
		
        $self2+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
    }
    else  if (in_array($row['applicantstatesIDd'],array("12","36","22","37","24")))//ارسال به صندوق یا بانک دریافت پیشنهاد
    {
        $total4++;
        if ($id==4){if ($total4%2==1) $b='b'; else $b=''; echorow($row,$total4,$b);}
        $hek4+=$row['DesignAread'];
        $bela4+=$row['belaavazd'];
        $lasttotal4+=$row['LastTotald'];
        $lastfehrest4+=$row['LastFehrestbahad'];
        $Totlainvoice4+=$row['TotlainvoiceValuesd'];
        $self4+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
        
        if (in_array($row['applicantstatesIDd'],array("12","36")))//تکمیل تضامین
        {
            $total5++;
            if ($id==5){if ($total5%2==1) $b='b'; else $b=''; echorow($row,$total5,$b);}
            $hek5+=$row['DesignAread'];
            $bela5+=$row['belaavazd'];   
            $lasttotal5+=$row['LastTotald'];
            $lastfehrest5+=$row['LastFehrestbahad'];
            $Totlainvoice5+=$row['TotlainvoiceValuesd'];
            $self5+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
        }
        else //انعقاد قرارداد و دریافت پیشنهاد
        {
            $total6++;
            if ($id==6){if ($total6%2==1) $b='b'; else $b=''; echorow($row,$total6,$b);}
            $hek6+=$row['DesignAread'];
            $bela6+=$row['belaavazd'];  
            $lasttotal6+=$row['LastTotald'];
            $lastfehrest6+=$row['LastFehrestbahad'];
            $Totlainvoice6+=$row['TotlainvoiceValuesd'];
            $self6+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];  
            if(!($row['applicantstatesIDop']>0))//درحال پیشنهاد قیمت
            {
                $total7++;
                if ($id==7){if ($total7%2==1) $b='b'; else $b=''; echorow($row,$total7,$b);}
                $hek7+=$row['DesignAread'];
                $bela7+=$row['belaavazd'];
                $lasttotal7+=$row['LastTotald'];
                $lastfehrest7+=$row['LastFehrestbahad'];
                $Totlainvoice7+=$row['TotlainvoiceValuesd'];
                $self7+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
            }       
            else if (in_array($row['applicantstatesIDop'],array("30","35","38")))//تایید نهایی پیشفاکتور و آزادسازی 
            {
                $total15++;
                if ($id==15){if ($total15%2==1) $b='b'; else $b=''; echorow($row,$total15,$b);}
                $total9++;
                if ($id==9){if ($total9%2==1) $b='b'; else $b=''; echorow($row,$total9,$b);}
                $hek9+=$row['DesignAreaop'];
                $bela9+=$row['belaavazop'];   
                $lasttotal9+=$row['LastTotalop'];
                $lastfehrest9+=$row['LastFehrestbahaop'];
                $Totlainvoice9+=$row['TotlainvoiceValuesop'];
                $self9+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                if ($row['permanentfree']==1 && $row['applicantstatesIDoplist']==45)//ـحویل دائم
                {
                    $total16++;
                    if ($id==16){if ($total16%2==1) $b='b'; else $b=''; echorow($row,$total16,$b);}
                    $total13++;
                    if ($id==13){if ($total13%2==1) $b='b'; else $b=''; echorow($row,$total13,$b);}
                    $hek13+=$row['DesignAreaoplist'];
                    $bela13+=$row['belaavazoplist'];  
                    $lasttotal13+=$row['LastTotaloplist'];
                    $lastfehrest13+=$row['LastFehrestbahaoplist'];
                    $Totlainvoice13+=$row['TotlainvoiceValuesoplist'];
                    $self13+=$row['selfcashhelpval']+$row['selfnotcashhelpval']; 
                }
                else if ($row['applicantstatesIDoplist']==45)//ـحویل موقت
                {
                    $total16++;
                    if ($id==16){if ($total16%2==1) $b='b'; else $b=''; echorow($row,$total16,$b);}
                    $total12++;
                    if ($id==12){if ($total12%2==1) $b='b'; else $b=''; echorow($row,$total12,$b);}
                    $hek12+=$row['DesignAreaoplist'];
                    $bela12+=$row['belaavazoplist'];  
                    $lasttotal12+=$row['LastTotaloplist']; 
                    $lastfehrest12+=$row['LastFehrestbahaoplist'];
                    $Totlainvoice12+=$row['TotlainvoiceValuesoplist'];
                    $self12+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                }
                else if ($row['applicantstatesIDop']==35)//آزادسازی ظرفیت
                {
                    $total16++;
                    if ($id==16){if ($total16%2==1) $b='b'; else $b=''; echorow($row,$total16,$b);}
                    $total11++;
                    if ($id==11){if ($total11%2==1) $b='b'; else $b=''; echorow($row,$total11,$b);}
                    $hek11+=$row['DesignAreaoplist'];
                    $bela11+=$row['belaavazoplist']; 
                    $lasttotal11+=$row['LastTotaloplist'];
                    $lastfehrest11+=$row['LastFehrestbahaoplist'];
                    $Totlainvoice11+=$row['TotlainvoiceValuesoplist'];
                    $self11+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];  
                }
                else //درحال اجرا
                {
                    $total10++;
                    if ($id==10){if ($total10%2==1) $b='b'; else $b=''; echorow($row,$total10,$b);}
                    $hek10+=$row['DesignAreaop'];
                    $bela10+=$row['belaavazop'];   
                    $lasttotal10+=$row['LastTotalop'];
                    $lastfehrest10+=$row['LastFehrestbahaop'];
                    $Totlainvoice10+=$row['TotlainvoiceValuesop'];
                    $self10+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                }
                
            }
            else if ($row['applicantstatesIDop']==34)//انصراف از اجرا
            {
                $total14++;
                if ($id==14){if ($total14%2==1) $b='b'; else $b=''; echorow($row,$total14,$b);}
                $hek14+=$row['DesignAreaop'];
                $bela14+=$row['belaavazop'];
                $lasttotal14+=$row['LastTotalop'];
                $lastfehrest14+=$row['LastFehrestbahaop'];
                $Totlainvoice14+=$row['TotlainvoiceValuesop'];
                $self14+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                
            }
                
            else//تهیه پیش فاکتور
            {
                $total15++;
                if ($id==15){if ($total15%2==1) $b='b'; else $b=''; echorow($row,$total15,$b);}
                $total8++;
                if ($id==8){if ($total8%2==1) $b='b'; else $b=''; echorow($row,$total8,$b);}
                $hek8+=$row['DesignAread'];
                $bela8+=$row['belaavazd'];
                $lasttotal8+=$row['LastTotald'];
                $lastfehrest8+=$row['LastFehrestbahad'];
                $Totlainvoice8+=$row['TotlainvoiceValuesd'];
                $self8+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
            }  
        }
    }
    else//تکمیل پرونده
    {
        $total3++;
        if ($id==3){if ($total3%2==1) $b='b'; else $b=''; echorow($row,$total3,$b);}
        $hek3+=$row['DesignAread'];
        $bela3+=$row['belaavazd'];
        $lasttotal3+=$row['LastTotald'];
        $lastfehrest3+=$row['LastFehrestbahad'];
        $Totlainvoice3+=$row['TotlainvoiceValuesd'];
        $self3+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
    }
    
    
    
}




?>



<!DOCTYPE html>
<html>
<head>
  	<title>گزارش کامل عملکرد</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    <!-- /scripts -->
    
  	<style>
		td.rowtable {
		text-align:left; height:20px; vertical-align:middle;
		border:0px solid blue	;
		}
				td.rowtableR {
		text-align:center; height:20px; vertical-align:middle;
		border:0px solid blue	;
		}

	</style>


  
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
	<div id="content">
          
            <form action="allapplicantquotatotal.php" method="post">
                <table width="95%" align="center">
                 <tbody >
                    <tr>
                        <?php
						 $query="SELECT YearID as _value,Value as _key FROM `year` 
						 where YearID in (select YearID from cityquota)
						 ORDER BY year.Value DESC";
						 $ID = get_key_value_from_query_into_array($query);
						 
						 $query="SELECT creditsourceID as _value,Title as _key FROM `creditsource`
							where ostan=substring($selectedCityId,1,2)
						  ";
						 $ID1 = get_key_value_from_query_into_array($query);
						 
                         
						 $query="SELECT credityear as _value,credityear as _key FROM `creditsource` order by credityear desc";
						 $ID2 = get_key_value_from_query_into_array($query);
                         
						 print 
							select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
							
								$permitrolsid = array("1","18", "13");
				if (in_array($login_RolesID, $permitrolsid))
                     {
							$sqlsup = "select distinct clerk.CPI,clerk.DVFS,clerk.clerkid from tax_tbcity7digit 
								left outer join tax_tbcity7digit TAX_tbCity7Digitgardesh on substring(TAX_tbCity7Digitgardesh.id,1,4)=substring(tax_tbcity7digit.id,1,4) and substring(TAX_tbCity7Digitgardesh.id,5,3)!='000'
								left outer join clerk on clerk.clerkid=tax_tbcity7digit.ClerkIDExcellentSupervisor
								where substring(tax_tbcity7digit.id,1,2)='19' and substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,4)!='0000' 
									";
							//print $sqlsup;
							$resultsup = mysql_query($sqlsup);
							$allg1idsup[' ']=' ';
							while($rowsup = mysql_fetch_assoc($resultsup))
							{
							$allg1idstr=trim(decrypt($rowsup['CPI'])." ".decrypt($rowsup['DVFS']));
							$allg1idsup[$allg1idstr]=trim($rowsup['clerkid']);
							}
							$allg1idsup=mykeyvalsort($allg1idsup);
							mysql_data_seek( $resultsup, 0 );
						//	print_r ($allg1idsup);
    				    print select_option('clerksup','کارشناس',',',$allg1idsup,0,'','','1','rtl',0,'',$selectedsupId,'','75');
					}
		
							
						print  
							select_option('creditsourceID','اعتبارات',',',$ID1,0,'','','1','rtl',0,'',$creditsourceID,'','120').
							select_option('credityear','سال',',',$ID2,0,'','','1','rtl',0,'',$credityear);				 
					$checked="";
                    if ($showc>0) $checked="checked";
                    print "<td colspan='1' class='label'>تجمیع</td>
                     <td class='data'><input name='showc' type='checkbox' id='showc' $checked /></td>";
                    
                    print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
                         if ($showa>0) echo 'checked';
                         print " /></td>";
                     		
                    if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        
                        print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','80px');
                     }
                     
                     
                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($selectedCityId,1,2)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
                    $IDcity = get_key_value_from_query_into_array($query);
	               
                    print select_option('city','شهرستان',',',$IDcity,0,'','','1','rtl',0,'',$selectedCity,'','70px');
					
				              $query="select DesignSystemGroupsID _value,Title _key from designsystemgroups 
								order by _key  COLLATE utf8_persian_ci";
								$IDs = get_key_value_from_query_into_array($query);
		
                   print select_option('DesignSystemGroupsID','سیستم',',',$IDs,0,'','','1','rtl',0,'',$DesignSystemGroupsID,'','100px');
                        
							
						 ?>
		<td  class="data">تاریخ از:</td> <td><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1393/01/01'; } ?>" size="10" maxlength="10" />
					 </td> <td> تا:</td> <td>
                      <input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
                      
        		      
                
					  
					  
                      <td><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                   </tr>
			     </tbody>
				</table>
                
				<tbody>
                 <table align='center' border='1' id="table2" style="height:80px; vertical-align:middle; font-weight:bold; text-align:center;">              
                  <thead>
				   <tr> 
                            <td colspan="25"
                            <span class="f14_fontcb" >  گزارش کامل عملکرد طرح های سامانه های نوین آبیاری  <?php print $yearvalue."(مبالغ به میلیون ریال) <br>".$head;?></span>  
                            
                            
                             </td>
                             <td colspan="1" class="f7_fontb"><?php echo gregorian_to_jalali(date('Y-m-d')); ?></td>
                   </tr>
                   
                   </thead>
                   <?php 
				   
                   echo "
				   <tr class='f14_fontb'>
				   
				   	    <td class='rowtableR' colspan='8'>شرح</td>
						
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;تعداد </td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>مساحت(ha)</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>فهرست بها</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>لوازم</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>سایر هزینه ها</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>خودیاری/تسهیلات</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>بلاعوض</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>جمع کل</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td></td>
                        <td class='rowtableR' >مسئول پیگیری</td>
                   
				   </tr>
				        
				   
				   <tr class='f14_font'>
				   	    <td class='rowtable' colspan='1'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'1'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'> طرح های مطالعاتی :</a></td>
					 <td class='rowtable' colspan='1'>--</td>
					<td class='rowtable' colspan='6'>-----------------------------------</td>
						
				   	     <td class='rowtableR' colspan='2'> $total1</td>
                         <td class='rowtableR' colspan='2'>".round($hek1)."</td>
                        <td class='rowtableR' colspan='12'>
                       </tr>
                     
                     <tr class='f14_fontb'>
                    	<td class='rowtable' colspan='2'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'2'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'> در دست طراحی: </a></td>
					 <td class='rowtable' colspan='2'>-----</td>
					<td class='rowtable' colspan='4'>------------------------------</td>
					
					 	 <td class='rowtableR' colspan='2'> $total2</td>
                        <td class='rowtableR' colspan='2'>".round($hek2)."</td>
                         <td colspan='12'></td>
						 <td></td>
						 <td> مشاورین طراح، ناظر شهرستان</td>
						 
						 
                       </tr>
					   
                     <tr class='f14_font'>
                        <td class='rowtable' colspan='2'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'3'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'> تکمیل پرونده:</a> -</td>
					 <td class='rowtable' colspan='2'> -----</td>
					<td class='rowtable' colspan='4'>------------------------------</td>
						
					   <td class='rowtableR' colspan='2'> $total3</td>
                        <td class='rowtableR' colspan='2'>".round($hek3)."</td>
                    <td colspan='12'>
							 <td></td>
						 <td>متقاضی، مدیریت آب و خاک</td>
				
                          </tr>
                     
                     <tr class='f14_fontb'>
                        <td class='rowtable' colspan='4'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'4'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>  ارسال به صندوق/بانک:</a></td>
				   <td class='rowtable' colspan='4'>------------------------------</td>
					  		
					     <td class='rowtableR' colspan='2'> $total4</td>
                      <td class='rowtableR' colspan='2'>".round($hek4)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal4,1)."</td>
                     <td></td>
						<td></td>
				      
                        </tr>

                     
                      <tr class='f14_font'>
                        <td class='rowtable' colspan='4'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'5'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'> تکمیل تضامین:</a></td>
						<td class='rowtable' colspan='3'>--------------------</td>
					<td class='rowtable' colspan='1'>----------</td>
					
						 <td class='rowtableR' colspan='2'> $total5</td>
                            <td class='rowtableR' colspan='2'>".round($hek5)."</td>
                          <td class='rowtableR' colspan='2'>".round($lastfehrest5,1)."</td>
                          <td class='rowtableR' colspan='2'>".round($Totlainvoice5,1)."</td>
                          <td class='rowtableR' colspan='2'>".round($last5,1)."</td>
                          <td class='rowtableR' colspan='2'>".round($self5,1)."</td>
                          <td class='rowtableR' colspan='2'>".round($bela5,1)."</td>
                          <td class='rowtableR' colspan='2'>".round($lasttotal5,1)."</td>
                		<td></td>
						<td>متقاضی، صندوق/بانک</td>
				     
                         </tr>
                     
                     <tr class='f14_fontb'>
                        <td class='rowtable' colspan='4'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'6'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'> انعقاد قرارداد:</a>-</td>
						<td class='rowtable' colspan='3'>--------------------</td>
						<td class='rowtable' colspan='1'>----------</td>
					
					 <td class='rowtableR' colspan='2'>$total6</td>
                    <td class='rowtableR' colspan='2'>".round($hek6)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest6,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice6,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last6,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self6,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela6,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal6,1)."</td>
					 <td></td>
						<td></td>
				      
                         </tr>
					   
                     
                     <tr class='f14_font'>
                        <td class='rowtable' colspan='7'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'7'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>در حال پیشنهاد قیمت:</a> -------</td>
						<td class='rowtable' colspan='1'>----------</td>
					
					  <td class='rowtableR' colspan='2'> $total7</td>
                     <td class='rowtableR' colspan='2'>".round($hek7)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest7,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice7,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last7,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self7,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela7,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal7,1)."</td>
					 <td></td>
						<td>متقاضی، مدیریت آب و خاک، ناظرعالی</td>
				     
                         </tr>
           
					<tr class='f14_fontb'>
							<td class='rowtable' colspan='7'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'14'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>انصراف از اجرا و اختلافات:</a>-----</td>
							<td class='rowtable' colspan='1'>----------</td>
					
							<td class='rowtableR' colspan='2'>$total14</td>
							<td class='rowtableR' colspan='2'>".round($hek14)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest14,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice14,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last14,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self14,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela14,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal14,1)."</td>
								<td></td>
							<td></td>
						
                    </tr>
					
                    <tr class='f14_font'>
							<td class='rowtable' colspan='7'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'15'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>در دست اجرا:</a> -------------</td>
							<td class='rowtable' colspan='1'>----------</td>
					
							<td class='rowtableR' colspan='2'>".round($total8+$total9)."</td>
							<td class='rowtableR' colspan='2'>".round($hek8+$hek9)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest8+$lastfehrest9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice8+$Totlainvoice9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last8+$last9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self8+$self9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela8+$bela9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal8+$lasttotal9,1)."</td>
							<td></td>
							<td> شرکتهای مجری، مشاورین ناظر، ناظرعالی، ناظرشهرستان</td>
				    </tr >
					
           		   
                    <tr class='f14_fontb'>
							<td class='rowtable' colspan='7'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'8'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>تهیه پیش فاکتور:</a> ----</td>
							<td class='rowtable' colspan='1'>----------</td>
					
							<td class='rowtableR' colspan='2'> $total8</td>
							<td class='rowtableR' colspan='2'>".round($hek8)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest8,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice8,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last8,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self8,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela8,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal8,1)."</td>
							<td></td>
							<td></td>
				    </tr>
                     
                     
                     
                     <tr  class='f14_font'>
                        <td class='rowtable' colspan='7'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'9'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>تایید نهایی پیش فاکتور: </a></td>
						<td class='rowtable' colspan='1'>----------</td>
					
						<td class='rowtableR' colspan='2'> $total9</td>
						<td class='rowtableR' colspan='2'>".round($hek9)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela9,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal9,1)."</td>
						<td></td>
						<td></td>
				     
                    </tr>
                     
					

                    <tr class='f14_fontb'>
                        <td class='rowtable' colspan='8	'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'10'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>در حال اجرا:</a> ------------</td>
						<td class='rowtableR' colspan='2'>$total10</td>
						<td class='rowtableR' colspan='2'>".round($hek10)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest10,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice10,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last10,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self10,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela10,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal10,1)."</td>
						<td></td>
						<td></td>
							 
                    </tr>

					<tr class='f14_font'> 
                        <td class='rowtable' colspan='8'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'16'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>اجرا شده:</a> -------------</td>
						<td class='rowtableR' colspan='2'>".round($total11+$total12+$total13)."</td>
						<td class='rowtableR' colspan='2'>".round($hek11+$hek12+$hek13)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest11+$lastfehrest12+$lastfehrest13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice11+$Totlainvoice12+$Totlainvoice13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last11+$last12+$last13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self11+$self12+$self13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela11+$bela12+$bela13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal11+$lasttotal12+$lasttotal13,1)."</td>
						<td></td>
						<td></td>
				    </tr>
              

					
                    <tr class='f14_fontb'> 
                        <td class='rowtable' colspan='8'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'11'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>آزادسازی ظرفیت:</a> ----</td>
						<td class='rowtableR' colspan='2'>$total11</td>
						<td class='rowtableR' colspan='2'>".round($hek11)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest11,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice11,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last11,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self11,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela11,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal11,1)."</td>
						<td></td>
						<td></td>
				    </tr>
                     
                     <tr class='f14_font'>
                        <td class='rowtable' colspan='8'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'12'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>تحویل موقت:</a> -------</td>
						
						<td class='rowtableR' colspan='2'> $total12</td>
						<td class='rowtableR' colspan='2'>".round($hek12)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest12,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice12,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last12,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self12,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela12,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal12,1)."</td>
					  	<td></td>
						<td></td>
				    </tr>
                     
                    <tr class='f14_fontb'>
                        <td class='rowtable' colspan='8'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'13'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']."_".$_POST['ostan']."_".$_POST['credityear']."_".
                        rand(10000,99999)."' target='_blank'>تحویل دائم:</a> --------</td>
						
						<td class='rowtableR' colspan='2'>$total13</td>
						<td class='rowtableR' colspan='2'>".round($hek13)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela13,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal13,1)."</td>
						<td></td>
						<td></td>
				     
                     </tr>
                     

					<tr class='f16_fontb'>
				   
				   	    <td  class='rowtableR' colspan='8'>مجموع</td>
						<td class='rowtableR' colspan='2'>$total1</td>
    					<td  class='rowtableR' colspan='2'>".round($hek1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lastfehrest4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($Totlainvoice4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($last4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($self4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($bela4,1)."</td>
                      <td class='rowtableR' colspan='2'>".round($lasttotal4,1)."</td>
                   <td></td>
						<td></td>
				     
				   </tr>
				   
					   
					   
					   
                     ";
                   
                   
                   ?> 
                        
                        
				  
				  
				  
				  
                   
                </table>
				                
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
    

                    </tbody>
                   
                      
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
