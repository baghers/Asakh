<?php 
/*

//appinvestigation/aaapplicantfree.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود
/reports/aaapplicantfree.php
-
*/
include('../includes/connect.php'); 
include('../includes/check_user.php'); 
include('../includes/elements.php'); 
if ($login_Permission_granted==0) header("Location: ../login.php");
    $showa=0;
 $yearid='';//شناسه سال		
if ($_POST)//در صورتی که دکمه ثبت کلیلک شده باشد
{   
    $yearid=$_POST['YearID'];//سال
	 $DesignAreafrom=$_POST['DesignAreafrom'];//مساحت از
    $DesignAreato=$_POST['DesignAreato'];//مساحت تا
    $sos=$_POST['sos'];//شهرستان
    $sob=$_POST['sob'];//بخش
    $operatorcoid=$_POST['operatorcoid'];//شناسه پیمانکار
    $applicantstatesID=$_POST['applicantstatesID'];//شناسه وضعیت طرح
    $creditcsourceID=$_POST['creditcsourceID'];//شناسه منبع تامین اعتبار
    $BankCode=$_POST['BankCode'];//کد رهگیری طرح
    $dateID=$_POST['dateID'];//شناسه تاریخ کومبوباکس فیلتر یک تاریخ خاص
	$ApplicantFname=$_POST['ApplicantFname'];//نام متقاضی طرح
    $Applicantname=$_POST['ApplicantName'];//عنوان پروژه
    $designsystemgroupsTitle=$_POST['designsystemgroupsTitle'];//سستم آبیاری پروژه
    if ($_POST['showa']=='on')
        $showa=1;//نمایش تمام طرح ها شامل طرح های شهرستان های سایر ناظرین عالی
}
if (strlen(trim($_POST['designsystemgroupsTitle']))>0)//فیلتر سیستم آبیاری انتخاب شده
        $str.=" and designsystemgroups.designsystemgroupsid='$_POST[designsystemgroupsTitle]'";	
        
	if (trim($_POST['creditcsourceID'])==-2)//فیلتر طرح ها با منابع اعتباری خالی
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)//فیلتر طرح ها با منابع اعتباری غیر خالی
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0)//فیلتر طرح ها با منبع تامین اعتبار انتخاب شده
        $str.=" and applicantmasterall.creditsourceID='$_POST[creditcsourceID]'"; 
    if ($applicantstatesID>0)//فیلتر طرح ها با شناسه وضعیت انتخاب شده   
        $str.=" and applicantmastersurat.applicantstatesID='$applicantstatesID'"; 
    if (strlen(trim($_POST['sos']))>0)//فیلتر طرح ها با فیلتر شهرستان انتخاب شده
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)//فیلتر طرح ها با فیلتر پیمانکار انتخاب شده
        $str.=" and applicantmaster.operatorcoid='$_POST[operatorcoid]'";
	if (strlen(trim($_POST['ApplicantFname']))>0)//فیلتر طرح ها با فیلتر نام متقاضی انتخاب شده
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//فیلتر طرح ها با فیلتر عنوان پروژه انتخاب شده
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
    //فیلتر طرح ها با فیلتر مساحت انتخاب شده
    if (strlen(trim($_POST['IDArea']))>0)
		if (trim($_POST['IDArea'])==1)
        $str.=" and applicantmaster.DesignArea>0 and applicantmaster.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $str.=" and applicantmaster.DesignArea>10 and applicantmaster.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $str.=" and applicantmaster.DesignArea>20 and applicantmaster.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $str.=" and applicantmaster.DesignArea>50 and applicantmaster.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $str.=" and applicantmaster.DesignArea>100 and applicantmaster.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $str.=" and applicantmaster.DesignArea>200 and applicantmaster.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $str.=" and applicantmaster.DesignArea>500 and applicantmaster.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $str.=" and applicantmaster.DesignArea>1000";
    //فیلتر طرح ها با فیلتر هزینه های کل انتخاب شده
    if (trim($_POST['IDprice1'])==-2)
        $str.=" and ifnull(applicantmaster.LastTotal,0)=0";
    else if (trim($_POST['IDprice1'])==-1)
        $str.=" and ifnull(applicantmaster.LastTotal,0)>0";
    else if (strlen(trim($_POST['IDprice1']))>0)	
        if (trim($_POST['IDprice1'])==1)
		$str.=" and applicantmaster.LastTotal>0 and applicantmaster.LastTotal<=1000000000";
		else if (trim($_POST['IDprice1'])==2)
		$str.=" and applicantmaster.LastTotal>1000000000 and applicantmaster.LastTotal<=1500000000";
		else if (trim($_POST['IDprice1'])==3)
		$str.=" and applicantmaster.LastTotal>1500000000 and applicantmaster.LastTotal<=2000000000";
		else if (trim($_POST['IDprice1'])==4)
		$str.=" and applicantmaster.LastTotal>2000000000 and applicantmaster.LastTotal<=3000000000";
		else if (trim($_POST['IDprice1'])==5)
		$str.=" and applicantmaster.LastTotal>3000000000 and applicantmaster.LastTotal<=5000000000";
		else if (trim($_POST['IDprice1'])==6)
		$str.=" and applicantmaster.LastTotal>5000000000 and applicantmaster.LastTotal<=8000000000";
		else if (trim($_POST['IDprice1'])==7)
		$str.=" and applicantmaster.LastTotal>8000000000 and applicantmaster.LastTotal<=10000000000";
		else if (trim($_POST['IDprice1'])==8)
		$str.=" and applicantmaster.LastTotal>10000000000";

        //فیلتر طرح ها با فیلتر مبلغ بلاعوض انتخاب شده
        if (trim($_POST['IDprice2'])==-2)
        $str.=" and ifnull(applicantmaster.belaavaz,0)=0";
    else if (trim($_POST['IDprice2'])==-1)
        $str.=" and ifnull(applicantmaster.belaavaz,0)>0";
    else if (strlen(trim($_POST['IDprice2']))>0)	
        if (trim($_POST['IDprice2'])==1)
		$str.=" and applicantmaster.belaavaz>0 and applicantmaster.belaavaz<=1000";
		else if (trim($_POST['IDprice2'])==2)
		$str.=" and applicantmaster.belaavaz>1000 and applicantmaster.belaavaz<=1500";
		else if (trim($_POST['IDprice2'])==3)
		$str.=" and applicantmaster.belaavaz>1500 and applicantmaster.belaavaz<=2000";
		else if (trim($_POST['IDprice2'])==4)
		$str.=" and applicantmaster.belaavaz>2000 and applicantmaster.belaavaz<=3000";
		else if (trim($_POST['IDprice2'])==5)
		$str.=" and applicantmaster.belaavaz>3000 and applicantmaster.belaavaz<=5000";
		else if (trim($_POST['IDprice2'])==6)
		$str.=" and applicantmaster.belaavaz>5000 and applicantmaster.belaavaz<=8000";
		else if (trim($_POST['IDprice2'])==7)
		$str.=" and applicantmaster.belaavaz>8000 and applicantmaster.belaavaz<=10000";
		else if (trim($_POST['IDprice2'])==8)
		$str.=" and applicantmaster.belaavaz>10000";  
                
    //فیلتر طرح ها با فیلتر مبلغ آزادسازی قسط اول انتخاب شده
    if (trim($_POST['IDprice3'])==-2)
        $str.=" and ifnull(applicantfreedetail1.Price,0)=0";
    else if (trim($_POST['IDprice3'])==-1)
        $str.=" and ifnull(applicantfreedetail1.Price,0)>0";
    else if (strlen(trim($_POST['IDprice3']))>0)	
        if (trim($_POST['IDprice3'])==1)
		$str.=" and applicantfreedetail1.Price>0 and applicantfreedetail1.Price<=1000000000";
		else if (trim($_POST['IDprice3'])==2)
		$str.=" and applicantfreedetail1.Price>1000000000 and applicantfreedetail1.Price<=1500000000";
		else if (trim($_POST['IDprice3'])==3)
		$str.=" and applicantfreedetail1.Price>1500000000 and applicantfreedetail1.Price<=2000000000";
		else if (trim($_POST['IDprice3'])==4)
		$str.=" and applicantfreedetail1.Price>2000000000 and applicantfreedetail1.Price<=3000000000";
		else if (trim($_POST['IDprice3'])==5)
		$str.=" and applicantfreedetail1.Price>3000000000 and applicantfreedetail1.Price<=5000000000";
		else if (trim($_POST['IDprice3'])==6)
		$str.=" and applicantfreedetail1.Price>5000000000 and applicantfreedetail1.Price<=8000000000";
		else if (trim($_POST['IDprice3'])==7)
		$str.=" and applicantfreedetail1.Price>8000000000 and applicantfreedetail1.Price<=10000000000";
		else if (trim($_POST['IDprice3'])==8)
		$str.=" and applicantfreedetail1.Price>10000000000";
    //فیلتر طرح ها با فیلتر مبلغ آزادسازی قسط دوم انتخاب شده
    if (trim($_POST['IDprice4'])==-2)
        $str.=" and ifnull(applicantfreedetail2.Price,0)=0";
    else if (trim($_POST['IDprice4'])==-1)
        $str.=" and ifnull(applicantfreedetail2.Price,0)>0";
    else if (strlen(trim($_POST['IDprice4']))>0)	
        if (trim($_POST['IDprice4'])==1)
		$str.=" and applicantfreedetail2.Price>0 and applicantfreedetail2.Price<=1000000000";
		else if (trim($_POST['IDprice4'])==2)
		$str.=" and applicantfreedetail2.Price>1000000000 and applicantfreedetail2.Price<=1500000000";
		else if (trim($_POST['IDprice4'])==3)
		$str.=" and applicantfreedetail2.Price>1500000000 and applicantfreedetail2.Price<=2000000000";
		else if (trim($_POST['IDprice4'])==4)
		$str.=" and applicantfreedetail2.Price>2000000000 and applicantfreedetail2.Price<=3000000000";
		else if (trim($_POST['IDprice4'])==5)
		$str.=" and applicantfreedetail2.Price>3000000000 and applicantfreedetail2.Price<=5000000000";
		else if (trim($_POST['IDprice4'])==6)
		$str.=" and applicantfreedetail2.Price>5000000000 and applicantfreedetail2.Price<=8000000000";
		else if (trim($_POST['IDprice4'])==7)
		$str.=" and applicantfreedetail2.Price>8000000000 and applicantfreedetail2.Price<=10000000000";
		else if (trim($_POST['IDprice4'])==8)
		$str.=" and applicantfreedetail2.Price>10000000000";
    //فیلتر طرح ها با فیلتر مبلغ آزادسازی قسط سوم انتخاب شده
     if (trim($_POST['IDprice5'])==-2)
        $str.=" and ifnull(applicantfreedetail3.Price,0)=0";
    else if (trim($_POST['IDprice5'])==-1)
        $str.=" and ifnull(applicantfreedetail3.Price,0)>0";
    else if (strlen(trim($_POST['IDprice5']))>0)	
        if (trim($_POST['IDprice5'])==1)
		$str.=" and applicantfreedetail3.Price>0 and applicantfreedetail3.Price<=1000000000";
		else if (trim($_POST['IDprice5'])==2)
		$str.=" and applicantfreedetail3.Price>1000000000 and applicantfreedetail3.Price<=1500000000";
		else if (trim($_POST['IDprice5'])==3)
		$str.=" and applicantfreedetail3.Price>1500000000 and applicantfreedetail3.Price<=2000000000";
		else if (trim($_POST['IDprice5'])==4)
		$str.=" and applicantfreedetail3.Price>2000000000 and applicantfreedetail3.Price<=3000000000";
		else if (trim($_POST['IDprice5'])==5)
		$str.=" and applicantfreedetail3.Price>3000000000 and applicantfreedetail3.Price<=5000000000";
		else if (trim($_POST['IDprice5'])==6)
		$str.=" and applicantfreedetail3.Price>5000000000 and applicantfreedetail3.Price<=8000000000";
		else if (trim($_POST['IDprice5'])==7)
		$str.=" and applicantfreedetail3.Price>8000000000 and applicantfreedetail3.Price<=10000000000";
		else if (trim($_POST['IDprice5'])==8)
		$str.=" and applicantfreedetail3.Price>10000000000";
    //فیلتر طرح ها با فیلتر مبلغ آزادسازی قسط چهارم انتخاب شده
     if (trim($_POST['IDprice6'])==-2)
        $str.=" and ifnull(applicantfreedetail4.Price,0)=0";
    else if (trim($_POST['IDprice6'])==-1)
        $str.=" and ifnull(applicantfreedetail4.Price,0)>0";
    else if (strlen(trim($_POST['IDprice6']))>0)	
        if (trim($_POST['IDprice6'])==1)
		$str.=" and applicantfreedetail4.Price>0 and applicantfreedetail4.Price<=1000000000";
		else if (trim($_POST['IDprice6'])==2)
		$str.=" and applicantfreedetail4.Price>1000000000 and applicantfreedetail4.Price<=1500000000";
		else if (trim($_POST['IDprice6'])==3)
		$str.=" and applicantfreedetail4.Price>1500000000 and applicantfreedetail4.Price<=2000000000";
		else if (trim($_POST['IDprice6'])==4)
		$str.=" and applicantfreedetail4.Price>2000000000 and applicantfreedetail4.Price<=3000000000";
		else if (trim($_POST['IDprice6'])==5)
		$str.=" and applicantfreedetail4.Price>3000000000 and applicantfreedetail4.Price<=5000000000";
		else if (trim($_POST['IDprice6'])==6)
		$str.=" and applicantfreedetail4.Price>5000000000 and applicantfreedetail4.Price<=8000000000";
		else if (trim($_POST['IDprice6'])==7)
		$str.=" and applicantfreedetail4.Price>8000000000 and applicantfreedetail4.Price<=10000000000";
		else if (trim($_POST['IDprice6'])==8)
		$str.=" and applicantfreedetail4.Price>10000000000";
    //فیلتر طرح ها با فیلتر مبلغ آزادسازی کل اقساط  انتخاب شده
     if (trim($_POST['IDprice7'])==-2)
        $str.=" and ifnull(applicantfreedetail.Price,0)=0";
    else if (trim($_POST['IDprice7'])==-1)
        $str.=" and ifnull(applicantfreedetail.Price,0)>0";
    else if (strlen(trim($_POST['IDprice7']))>0)	
        if (trim($_POST['IDprice7'])==1)
		$str.=" and applicantfreedetail.Price>0 and applicantfreedetail.Price<=1000000000";
		else if (trim($_POST['IDprice7'])==2)
		$str.=" and applicantfreedetail.Price>1000000000 and applicantfreedetail.Price<=1500000000";
		else if (trim($_POST['IDprice7'])==3)
		$str.=" and applicantfreedetail.Price>1500000000 and applicantfreedetail.Price<=2000000000";
		else if (trim($_POST['IDprice7'])==4)
		$str.=" and applicantfreedetail.Price>2000000000 and applicantfreedetail.Price<=3000000000";
		else if (trim($_POST['IDprice7'])==5)
		$str.=" and applicantfreedetail.Price>3000000000 and applicantfreedetail.Price<=5000000000";
		else if (trim($_POST['IDprice7'])==6)
		$str.=" and applicantfreedetail.Price>5000000000 and applicantfreedetail.Price<=8000000000";
		else if (trim($_POST['IDprice7'])==7)
		$str.=" and applicantfreedetail.Price>8000000000 and applicantfreedetail.Price<=10000000000";
		else if (trim($_POST['IDprice7'])==8)
		$str.=" and applicantfreedetail.Price>10000000000";
	  if($yearid>0)  $str.=" and applicantmaster.yearid='$yearid' ";//فیلتر پروژه های یک سال مشخص
	
	
      if ($login_RolesID=='16')//نقش صندوق طرح های در مرحله صورت وضعیت را مشاهده می نماید
            $str.=" and ifnull(app22.ApplicantMasterID,0)>0 and applicantmaster.applicantstatesID in(30,34,35,38)";    
    else   if ($login_RolesID=='7') //نقش بانک طرح های در مرحله صورت وضعیت را مشاهده می نماید
            $str.=" and ifnull(app37.ApplicantMasterID,0)>0 and applicantmaster.applicantstatesID in(30,34,35,38)";  
            
                                                                       
    if ($login_RolesID=='17')//ناظر مقیم 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";//فیلتر شهرستان ناظر مقیم
else if (($login_RolesID=='14') && ($showa==0))//ناظر عالی
    //فیلتر شهرستان های مربوطه ناظر عالی
        $str.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
 	
	
	switch ($_POST['IDorder'])//شناسه ترتیب 
     {
        //ترتیب بر اساس عنوان پروژه
        case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
        //ترتیب بر اساس نام متقاضی پروژه
        case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
        //ترتیب بر اساس مساحت پروژه
        case 3: $orderby=' order by applicantmaster.DesignArea,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
        //ترتیب بر اساس شهر پروژه
        case 4: $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
        //ترتیب بر اساس پیمانکار پروژه
        case 5: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
        //ترتیب بر اساس تاریخ تغییر وضعیت پروژه
        case 6: $orderby=' order by applicantmaster.TMDate,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	   //ترتیب بر اساس منبع تامین اعتیار پروژه
        case 7: $orderby=' order by creditsource.creditsourceid,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	   //ترتیب بر اساس کد صندوق پروژه
        case 8: $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	   default: 
            if ($login_RolesID=='7' || $login_RolesID=='16')//نقش بانک یا صندوق
                //ترتیب بر اساس کد صندوق پروژه 
                $orderby='order by cast(applicantmasterall.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci';
            else 
                //ترتیب بر اساس عنوان پروژه
                $orderby='order by  applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;  
    }
 
 
 if ($login_RolesID=='2')//کاربر لاگین شده پیمانکار باشد 
{
    //فیلتر طرح ها با فیلتر پیمانکار لاگین شده
    $str.="and applicantmaster.operatorcoID='$login_OperatorCoID'";
    $hide='display:none';
}
    //فیلتر طرح های در مرحله صورت وضعیت
	$str.="and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0";
 
 $selectedCityId=$login_CityId;
if ($_POST['ostan']>0)//شناسه استان
        $selectedCityId=$_POST['ostan'];
        
    //فیلتر شهرستان
	$str.=" and substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)";
         
    
    $kejra1=0;$kejra2=0;$kejra3=0;$kejra4=0;$sqlPricekejra=0;$Pricek=0;$Pricekejra=0;//متغیر های پرس و جوی آزادسازی
    //تابع ایجاد پرس و جوی آزادسازی
    /*
    $ApplicantMasterID شناسه طرح
    $login_CityId شناسه شهر کاربر لاگین کرده
    $kejra1 کسر از قسط 1
    $kejra2 کسر از قسط 2
    $kejra3 کسر از قسط 3
    $kejra4 کسر از قسط 4
    $sqlPricekejra مبلغ اضافه به صورت وضعیت
    $Pricek حسن انجام کار
    $Pricekejra سن انجام تعهدات
    $str شرط های محدودیت ها
    $orderby رشته ترتیب پرس و جود
    */
    $sql=freequery($ApplicantMasterID,$login_CityId,$kejra1,$kejra2,$kejra3,$kejra4,$sqlPricekejra,$Pricek,$Pricekejra,$str,$orderby);
     
    $result = mysql_query($sql.$login_limited); 

    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID9[' ']=' ';
$dasrow=0;

//حلقه پر کردن آرایه های کلید و مقدار مربوط به کومبوباکس های فیلتر
while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;    
    $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);//شهر
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);//عنوان پروژه
    $ID3[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);//شناسه پیمانکار
    $ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);//نام متقاضی   
    $ID5[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);//شناسه منبع تامین اعتبار
    $ID6[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);//آخرین وضعیت طرح
    
	if ($row['freestateID']<>$row['Freestate'])//تغییر آخرین وضعیت آزاد سازی
	{
    	$ApplicantMasterID=$row['applicantmasterid'];//شناسه طرح
        $freestateID=$row['freestateID'];//شناسه آخرین وضعیت آزادسازی
    	$querys= "UPDATE applicantmaster SET 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    SaveDate = '" . date('Y-m-d') . "', 
                    ClerkID = '" . $login_userid . "',
        Freestate = '$freestateID' where applicantmaster.ApplicantMasterID = $ApplicantMasterID ";
        try 
          {		
             mysql_query($querys); 
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
          
	}
    
    $ID9[trim($row['designsystemgroupsTitle'])]=trim($row['DesignSystemGroupsid']);//سیستم آبیاری
}
//مرتب سازی آرایه های کلید و مقدار مربوط به کومبوباکس ها 
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);
$ID9=mykeyvalsort($ID9);

if ($dasrow)
mysql_data_seek( $result, 0 );



//پرس و جوی مربوط به اینکه مبلغ نمایش داده شده در ستون مبلغ کدامیک از آیتم های زیر باشد
$query="
select 'کل' _key,1 as _value union all
select 'فهرست بهای اجرای' _key,2 as _value union all 
select 'لوازم' _key,3 as _value  union all 
select 'سایر هزینه ها' _key,4 as _value";
$IDTotlalType = get_key_value_from_query_into_array($query);
if (!$_POST['IDTotlalType'])
    $IDTotlalTypeval=1;
else $IDTotlalTypeval=$_POST['IDTotlalType'];


//پرس و جوی مربوط به کومبوباکس انتخاب مرتب سازی گزارش 
$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'شهرستان' _key,4 as _value union all
select 'شرکت طراح' _key,5 as _value union all
select 'تاریخ' _key,6 as _value union all
select 'اعتبار' _key,7 as _value union all
select 'کد' _key,8 as _value ";
$IDorder = get_key_value_from_query_into_array($query);
if (!$_POST['IDorder'])
    $IDorderval=7;
else $IDorderval=$_POST['IDorder'];
//پرس و جوی مربوط به کومبوباکس انتخاب مساحت  گزارش 
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
if ($_POST['IDArea']>0)
    $IDAreaval=$_POST['IDArea'];
//پرس و جوی مربوط به کومبوباکس انتخاب مبلغ  گزارش 
$query="
select ' خالی' _key,-2 as _value union all 
select ' غیرخالی' _key,-1 as _value union all 
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDprice = get_key_value_from_query_into_array($query);
if ($_POST['IDprice']>0)
    $IDpriceval=$_POST['IDprice'];    
    
    

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست آزادسازی</title>

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
            
            <form action="aaapplicantfree.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                
                <table id="records" width="95%" align="center">
                
                  <tr> 
				 
                         <?php  
                            $query="SELECT YearID as _value,Value as _key FROM `year` 
                             where YearID in (select YearID from cityquota)
                             
                             ORDER BY year.Value DESC";
            				 $ID = get_key_value_from_query_into_array($query);
                             print 
                             select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                          
                    if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        
                        print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','75');
                     }
                    
                    print select_option('designsystemgroupsTitle','',',',$ID9,0,'','','1','rtl',0,'',$designsystemgroupsTitle,'','100%');
                     
						    print select_option('IDTotlalType','نوع',',',$IDTotlalType,0,'','','3','rtl',0,'',$IDTotlalTypeval,"",'100');
                       
						    print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                            print select_option('creditcsourceID','اعتبار',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','95');
							
							print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa'";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
                          ?>
  					      
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
		                  <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="15" value="جستجو" /></td>
    
				   </tr>
				   
				   
		        </table>
                 <table align='center' border='1' id="table2">              
                   <thead>           
				   <tr>
                              <td colspan="24"
                            <span class="f14_fontb" >لیست آزاد سازی (مبالغ به میلیون ریال)</span>  
                            </td>
                    <tr>        
					 <tr>
                            <th class="f9_fontb" > رديف   </th>
                            <th class="f9_fontb" >کد   </th>
							<th class="f10_fontb"> نام   </th>
							<th class="f10_fontb"> نام خانوادگی  </th>
							<th class="f9_fontb"> مساحت </span>(ha)  </th>
						    <th class="f9_fontb">دشت/ شهرستان </th>
							<th class="f9_fontb">شركت مجری </th>
							<th class="f9_fontb">وضعیت</th>
							<th class="f10_fontb">اعتبار</th>
						
							<th class="f10_fontb"> مبلغ کل طراحی</th>
							<th class="f10_fontb"> مبلغ کل اجرا *</th>
							<th class="f9_fontb"> اختلاف طراحی و اجرا</th>
							<th class="f10_fontb"> بلاعوض مصوب</th>
							<th class="f10_fontb">کمک بلاعوض</th>
                   		<th class="f10_fontb">مانده بلاعوض</th>
							
							<th class="f9_fontb">خودیاری نقد<br>(سهم شریک)</th>
							<th class="f9_fontb">خودیاری غیرنقد <br>(تسهیلات)</th>
							<th class="f10_fontb"> جمع خودیاری</th>
							<th class="f10_fontb">قسط اول </th>
							<th class="f10_fontb">قسط دوم </th>
							<th class="f10_fontb">قسط سوم </th>
							<th class="f10_fontb">قسط چهارم </th>
							<th class="f10_fontb">مجموع </th>
							<th class="f10_fontb">مانده </th>
                         </tr>
                        
                        </thead> 
                        <tr class='no-print'>    
							<td class="f14_font"></td>
                            <td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFname,'','100%'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','100%'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','100%'); ?>
					       <?php print select_option('sos','',',',$ID1,0,'','','1','rtl',0,'',$sos,"",'100%'); ?>  
					       <?php print select_option('operatorcoid','',',',$ID3,0,'','','1','rtl',0,'',$operatorcoid,'','100%') ?>
							
					      <?php print select_option('applicantstatesID','',',',$ID6,0,'','','1','rtl',0,'',$applicantstatesID,'','100%');?>
                               <?php print select_option('creditcsourceID','',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','100%'); ?> 
                    
                            <td></td> 
					       <?php print select_option('IDprice1','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice1,'','100%'); ?>  
					       <td></td>  <td></td> 
                            <?php print select_option('IDprice2','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice2,'','100%'); ?> 
                              <td></td> <td></td> 
					        <td></td> 
					        <td></td> 
					       <?php print select_option('IDprice3','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice3,'','100%'); ?> 
					       <?php print select_option('IDprice4','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice4,'','100%'); ?> 
					       <?php print select_option('IDprice5','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice5,'','100%'); ?> 
					       <?php print select_option('IDprice6','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice6,'','100%'); ?> 
					       <?php print select_option('IDprice7','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice7,'','100%'); ?> 
                           <td></td> 
					        <td></td>
					        <td></td>  
					    
					 
					 </tr> 
                     
<?php
                    $Total=0;
                    $rown=0;
                    $Description="";
					$sumarea=0;
                    $sum1=0;
                    $sum2=0;
                    $sum3=0;
                    $sum4=0;
                    $sumall=0;
                    $LastTotal=0;
                    $LastTotald=0;
                    $LastTotaldif=0;
                    $selfnotcashhelpval=0;
					$selfcashhelpval=0;
					$selfhelp=0;
                    $belaavaz=0;
                    $remain=0;
                    while($resquery = mysql_fetch_assoc($result))
                    { 
					
					$numfield2array = explode('_',$resquery["numfield2"]);
					$contracdate=$numfield2array[1];
					if ($contracdate) $state='تحویل دائم'; else $state=$resquery['applicantstatestitle'];
					if ($contracdate && (!$showa>0)) continue;
                           if ($criditType>0) {$creditTypetitle='طرح تجمیع';}
                              if ($resquery["creditsourceid"]>0)
                            				$selectedcreditsourceID=$resquery["creditsourceid"];
                            			else $selectedcreditsourceID=4;    
                            //print $selectedcreditsourceID.'*'.$ApplicantMasterID.'*'.$sumsurat.'*'.$criditType;exit;
                            if ($showbel==2) $sysbelaavaz=$resquery["belaavaz"];
                            else 
                            $sysbelaavaz=calculatebelavaz($selectedcreditsourceID,$resquery['applicantmasteridsurat'],$resquery['LastTotals'],$resquery['criditType']);
                            
                            if ($sysbelaavaz>$resquery['belaavaz'] || $sysbelaavaz==0)  $sysbelaavaz=$resquery['belaavaz'];
                            
                        $Total=0;
                        $Totald=0;
                        if ($IDTotlalTypeval==1)
                        {
                            if ($resquery["applicantstatesIDsurat"]<>45)
                            $Total=$resquery["LastTotal"];
                            else $Total=$resquery["LastTotals"];
                            
                            
                            $Totald=$resquery["LastTotald"];                        
                        } 
                        else if ($IDTotlalTypeval==2)
                        {
                            $Total=$resquery["LastFehrestbaha"];
                            $Totald=$resquery["LastFehrestbahad"];                        
                        }
                        else if ($IDTotlalTypeval==3)
                        {
                            $Total=$resquery["TotlainvoiceValues"];
                            $Totald=$resquery["TotlainvoiceValuesd"];                        
                        }
                        else if ($IDTotlalTypeval==4)
                        {
                            $Total=$resquery["LastTotal"]-$resquery["LastFehrestbaha"]-$resquery["TotlainvoiceValues"];
                            $Totald=$resquery["LastTotald"]-$resquery["LastFehrestbahad"]-$resquery["TotlainvoiceValuesd"];                        
                        }
                        
					    $sumarea+=$resquery["DesignArea"];
                        $sum1+=$resquery["Price1"];
                        $sum2+=$resquery["Price2"];
                        $sum3+=$resquery["Price3"];
                        $sum4+=$resquery["Price4"];
                        $sumall+=$resquery["Priceall"];
                        $LastTotal+=$Total;
                        $LastTotald+=$Totald;
                        if ($Total<$Totald)	$LastTotaldifr=$Total-$Totald; else $LastTotaldifr=0;
                        $LastTotaldif+=$LastTotaldifr;
						$selfnotcashhelpval+=$resquery["selfnotcashhelpval"];
                        $selfcashhelpval+=$resquery["selfcashhelpval"];
                    	$selfhelp+=$resquery["selfhelp"]+$resquery["othercosts5"];
               //   if ($resquery['applicantmasterid']==504) print $resquery["Priceall"] .'*'. $resquery["Pricek"].'*'.$Total;
				      
					  //if(($resquery["Priceall"]-$resquery["Pricek"])>$resquery["LastTotal"]) $cl='ff0000'; else $cl='';
					  $cl='';
					  
				       if ($resquery["errType"]==2) $cl='8A2BE2';
					   //$resquery["errType"]=2 آبی
                       if ($resquery["applicantstatesIDsurat"]<>45)
					   $bel=$resquery['belaavaz'];
                       else
                       $bel=$sysbelaavaz;
                       
					  $remains=(floor(($resquery["selfhelp"]+$resquery["othercosts5"])/100000)/10+round($bel,1)-floor($resquery["Priceall"]/100000)/10);
					   if ($remains<0) $cl='ff0000'; else $remain+=$remains;
					   if ($resquery["othercosts5"]>0) $cl='gg0000';
						$belaavaz+=round($bel,1);
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
					$belaavazd=$resquery['belaavaz'];
					 $beld+=$resquery["belaavaz"];
					$checkbox="";
					if($belaavazd-$bel)
					$checkbox="<input type='checkbox' name='chk$rown' title='انتقال مبلغ بلاعوض' value='1'>";
						
                             print "<tr '>";      
?>                      
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo "($resquery[sandoghcode])" ;?></td>
														
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery['ApplicantFName']; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["ApplicantName"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["DesignArea"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["shahrcityname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcotitle"] ; ?></td>
							
						
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
								  print "<a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$resquery['applicantmasteridsurat'].'_4_'.$resquery['DesignerCoID'].'_'.$resquery['operatorcoid'].rand(10000,99999).
                            "'>
							<font color='black' >$state</font>
							</a>";?></td>
							
							
							
							
                           <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["creditsourcetitle"] ; ?></td>
						   
						   
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Totald/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Total/100000)/10; 
							if ($resquery["applicantstatesIDsurat"]<>45) echo '</br> <font color=\"aa0000\"> ! </font>';
							?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor(($LastTotaldifr)/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($belaavazd,1); ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($bel,1); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round(($resquery['belaavaz']-$bel),1).'<br>'.$checkbox; ?></td>
 
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfcashhelpval"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfnotcashhelpval"]/100000)/10;if($resquery["othercosts5"]>0) {echo "</br>".floor($resquery["othercosts5"]/100000)/10;} ?></td>
							  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor(($resquery["selfhelp"]+$resquery["othercosts5"])/100000)/10; ?></td>
																				
						  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price1"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price2"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price3"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price4"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Priceall"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo 
							round((floor(($resquery["selfhelp"]+$resquery["othercosts5"])/100000)/10+round($bel,1)-floor($resquery["Priceall"]/100000)/10),1); ?></td>
                        
                        
                          <?php
						  	 print  "<td class=\"f7_font$b'\"><a target='".$target."' href='aaapplicantfreep.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_1_'.$resquery["applicantstatesIDsurat"].'_'.
                                $resquery['operatorcoid']."_".$resquery['applicantmasteridsurat'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/process.png' title=' پیشنهاد آزادسازی'></a></td>";

						  $permitrolsid = array("1","18","13","14","16","7","28");
						  //$permitrolsid = array("1");
						  if ( in_array($login_RolesID, $permitrolsid) || $login_username=='entezam' || $login_username=='saradr')
						  {
  						
                               print  "<td class=\"f7_font$b'\"><a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_2_0_'.$resquery['operatorcoid'].'_'.$resquery['operatorcoTitle'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/search.png' title='آزادسازی'></a></td>";
                                
                               print  "<td class=\"f7_font$b'\"><a target='".$target."' href='invoicemasterfree_list2.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_2_0_'.$resquery['operatorcoid'].'_'.$resquery['operatorcoTitle'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/dolar.jpg' title='مدیریت آزادسازی'></a></td>";
								
						             print  "<td class=\"f7_font$b'\"><a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_1_0_'.$resquery['operatorcoid'].'_'.$resquery['operatorcoTitle'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/Actions-document-export-icon.png' title='آزادسازی'></a></td>";
                          
                          
                            
                             }
                                
                            echo "
                                                  
                        
                        </tr>
                        ";                     
                    }               
                         
?>

                   <tr>
                            <td rowspan="2" colspan="4" class="f14_fontb" ><?php echo 'مجموع';   ?></td>
                            <td rowspan="2" colspan="5" class="f14_fontb" ><?php echo floor($sumarea).' هکتار';   ?></td>
                            <td colspan="2" class="f131_fontb" ><?php echo floor($LastTotald/1000000);   ?></td>
                            <td colspan="2" class="f131_fontb" ><?php echo floor($LastTotaldif/1000000);   ?></td>
							<td colspan="2" class="f131_fontb" ><?php echo floor($belaavaz); ?></td>
						 	<td rowspan="1" colspan="1" class="f131_fontb" ><?php echo floor($selfcashhelpval/1000000);   ?></td>
			                <td colspan="2" class="f131_fontb" ><?php echo floor($selfnotcashhelpval/1000000);   ?></td>
               
                            <td colspan="2" class="f131_fontb" ><?php echo floor($sum1/1000000);  ?></td>
                         	<td colspan="2" class="f131_fontb" ><?php echo floor($sum3/1000000);  ?></td>
                            <td colspan="2" class="f131_fontb" ><?php echo floor($sumall/1000000);   ?></td>
                       
				   </tr>
  
                   <tr>
        					<td colspan="2" class="f132_fontb" ><?php echo floor($LastTotal/1000000);   ?></td>
							
							<td colspan="2" class="f132_fontb" ><?php echo floor($beld);   ?></td>
                        	<td colspan="2" class="f132_fontb" ><?php echo floor($beld-$belaavaz);   ?></td>
                            <td colspan="3" class="f132_fontb" ><?php echo floor($selfhelp/1000000);   ?></td>
                         	
			            
			                <td colspan="2" class="f132_fontb" ><?php echo floor($sum2/1000000);  ?></td>
                            <td colspan="2" class="f132_fontb" ><?php echo floor($sum4/1000000);  ?></td>
                        	<td colspan="2" class="f132_fontb" ><?php echo $remain;   ?></td>
				
                   </tr>
    
			
                </table>
                
                
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
	             <tr><td colspan="18" class="f11_font" ><?php echo '' ;   ?></td></tr>
                 
				 <tr><td colspan="18" class="f11_font" ><?php echo '* در ستون مبلغ کل اجرا ، در پروژه های به اتمام رسیده مبلغ کل صورت وضعیت نهایی و در پروژه های در حال اجرا مبلغ کل پیش فاکتورهای اجرایی آورده شده است.';?></td></tr>
				 </br>
				 <tr><td colspan="18" > <?php echo '<font color=\"aa0000\">! صورت وضعیت پروژه نهایی نشده است</font>';   ?></td></tr>
  			 </br>
				 <tr><td colspan="18" class="f11_font" ><?php echo '* حداکثر مبلغ پروژه برابر با مبلغ مطالعات درنظر گرفته شده است.';?></td></tr>
				 </br>
				 <tr><td colspan="18" > <?php echo '<font color=\"ff0000\">* آزادسازی(بدون احتساب خودیاری عودتی) بیشتر از مبلغ صورت وضعیت انجام شده است.</font>';   ?></td></tr>
    			 </br>
				 <tr><td colspan="18" > <?php echo '<font color=\"0,0,255\">* آزادسازی خودیاری عودتی بیشتر از مبلغ محاسبه شده است.</font>';   ?></td></tr>
       
                 <tr > <span colsapn="1" id="fooBar">  &nbsp;</span> </tr>
	
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
