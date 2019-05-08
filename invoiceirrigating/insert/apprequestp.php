<?php 

/*
//insert/apprequestp.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

insert/summaryinvoice.php

*/


include('../includes/connect.php'); 
include('../includes/check_user.php');
include('../includes/elements.php');



$errval='';//متغیر نمایش  خطا ردیف فعلی
$errvals='';//متغیر نمایش خطاها
$showkejra=$_GET["uid"];//نمایش همه طر های مختخب و غیر منتخب 

if ($login_RolesID==3 && $login_PipeProducer==0) //در صورتی که کاربر تولیدکننده بود ولی تولید کننده لوله نباشد از این صفحه خارج شود
    header("Location: ../login.php");
if ($login_Permission_granted==0) //در صورتی که مجوز این صفحه را نداشته باشد
    header("Location: ../login.php");
if ($login_ProducersID>0 && $login_PipeProducer<>1) //در صورتی که کاربر تولیدکننده بود ولی تولید کننده لوله نباشد از این صفحه خارج شود
    header("Location: ../login.php");
$early=0;//متغیر اینکه زمان پیشنهاد قیمت زودتر از موعد می باشد یا خیر
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);//استخراج اطلاعات مدیریتی و قرارگرفتن در آرایه
if ($_POST)//در صورتی که دکمه ثبت کلیک شده باشد
{
    if ($login_RolesID==3)//کاربر لاگین شده تولیدکننده باشد
    {	
	   if ($login_corank<=0)//رتبه شرکت لاگین شده
		{
				$errvals.="<br> رتبه شرکت نا معتبر می باشد";
		} 
        if (($_FILES["file1"]["size"] / 1024)>170)//اندازه فایل پیشنهاد قیمت
        {
            $errvals.="<br> حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
        }
        
        //پرس و جوی بررسی اینکه قبلا برای این طرح پیشنهاد داده شده است
        /*
        producerapprequest جدول پیشنهاد قیمت لوله
        ApplicantMasterID شناسه طرح
        producersID شناسه تولید کننده
        */
         $query = "SELECT count(*) cnt FROM producerapprequest          
         where ApplicantMasterID='$_POST[ApplicantMasterID]'
         and ProducersID='$login_ProducersID'";
         try 
            {		
                $result = mysql_query($query);
                $row = mysql_fetch_assoc($result);
                if ($row['cnt']>0)
                {
                    $errvals.="<br> قبلا برای این طرح پیشنهاد داده شده است";
                }
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
    
        
        $costprice = $_POST['eval']*10;//قیمت برآوردی
        $costpricewithcoef = $_POST['prop']*10;//قیمت برآوردی با ضرایب پیمان
        $PE32=str_replace(',', '', $_POST['PE32']);//قیمت لوله های 32
        $PE40=str_replace(',', '', $_POST['PE40']);//قیمت لوله های 40
        $PE80=str_replace(',', '', $_POST['PE80']);//قیمت لوله های 80
        $PE100=str_replace(',', '', $_POST['PE100']);//قیمت لوله های 100
        $validday=$_POST['validday'];
        //بررسی اینکه قیمت پیشنهاد داده شده دررنج صحیح باشد
        //$Permissionvals['maxpe32pipeprice'] سقف قیمت لوله های 32
        $peval=$Permissionvals['maxpe32pipeprice']*(1+$_POST['tanzilpipe']/100);
        if ($PE32>$peval)  $errval.='<br> قیمت پیشنهادی لوله های 32 در رنج مناسب نمی باشد.  ';		 
        //$Permissionvals['maxpe40pipeprice'] سقف قیمت لوله های 40
        $peval=$Permissionvals['maxpe40pipeprice']*(1+$_POST['tanzilpipe']/100);
        if ($PE40>$peval)  $errval.='<br> قیمت پیشنهادی لوله های 40 در رنج مناسب نمی باشد.  ';		
        //$Permissionvals['maxpe80pipeprice'] سقف قیمت لوله های 80
        $peval=$Permissionvals['maxpe80pipeprice']*(1+$_POST['tanzilpipe']/100);
        if ($PE80>$peval)  $errval.='<br> قیمت پیشنهادی لوله های 80 در رنج مناسب نمی باشد.  ';
	    //$Permissionvals['maxpe80pipeprice'] سقف قیمت لوله های 80
        $peval=$Permissionvals['maxpe100pipeprice']*(1+$_POST['tanzilpipe']/100);
        if ($PE100>$peval) $errval.='<br> قیمت پیشنهادی لوله های 100 در رنج مناسب نمی باشد. ';
		
		$errvalue='';//متغیر پیغام خطا
        //$_POST['Datebandp'] طرح ترک تشریفات می باشد یا خیر
		if ((strlen($errval)>0 && !$_POST['Datebandp']>0) || strlen($errvals)>0 || $_POST['errorsa'])
			{
				if (strlen($errval)>0 && !$_POST['Datebandp']>0) $errvalue.=$errval;//طرح خارج از تشریفات باشد
				if (strlen($errvals)>0) $errvalue.=$errvals;
				if ($_POST['errorsa']) $errvalue.=$_POST['errorsa'];
				echo 'خطا در ثبت.... <br>'.$errvalue;
			}
		else	
			{    
		      //هر پیش فاکتور اعلامی قیمت یک مدت اعتبار دارد که در زیر مورد بررسی قرار می گیرد
				if ($validday<$Permissionvals['validday'])//
					$validday=$Permissionvals['validday'];//مدت اعتبار
				$SaveTime=date('Y-m-d H:i:s');//زمان
				$SaveDate=date('Y-m-d');//تاریخ
				$ClerkID=$login_userid;//کاربر
				if ($_POST['ApplicantMasterID']>0 && $login_ProducersID>0)//درج پیشنهاد قیمت
				{
				    /*
                        producerapprequest جدول پیشنهاد قیمت های لوله طرح
                        ApplicantMasterID شناسه طرح
                        ProducersID شناسه تولید کننده
                        costprice قیمت برآوردی
                        price قیمت اعلامی
                        state منتخب یا عدم انتخاب شدن
                        PE32 مبلغ اعلام شده برای لوله های 32
                        PE40 مبلغ اعلام شده برای لوله های 40
                        PE80 مبلغ اعلام شده برای لوله های 80
                        PE100 مبلغ اعلام شده برای لوله های 100
                        PE32tonaj تناژ برای لوله های 32
                        PE40tonaj تناژ برای لوله های 40
                        PE80tonaj تناژ برای لوله های 80
                        PE100tonaj تناژ برای لوله های 100
                        validday مدت اعتبار پیششنهاد
                        ClerkID کاربر
                        SaveTime زمان
                        SaveDate تاریخ
                    */
					$sql="INSERT INTO producerapprequest(ProducersID,ApplicantMasterID, costprice,price,state,PE32,PE40,PE80,PE100,PE32tonaj,PE40tonaj,PE80tonaj,PE100tonaj,validday,SaveTime,SaveDate,ClerkID)
						values ('$login_ProducersID','$_POST[ApplicantMasterID]','$costprice','$costpricewithcoef',0,'$PE32','$PE40','$PE80','$PE100',
						'$_POST[pe32tOTALw]','$_POST[pe40tOTALw]','$_POST[pe80tOTALw]','$_POST[pe100tOTALw]'
						
						,'$validday', '$SaveTime'
						,'$SaveDate','$ClerkID');";
					   // print $sql;
					 
                    try 
                        {		
                            mysql_query($sql);
                        }
                        //catch exception
                        catch(Exception $e) 
                        {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                        }
                  	
					$query = "SELECT producerapprequestID,savedate FROM producerapprequest where producerapprequestID = last_insert_id() and SaveTime='$SaveTime' 
					and ClerkID='$ClerkID'";
                    try 
                        {		
                            //print $query;
                            $result = mysql_query($query);
                        }
                        //catch exception
                        catch(Exception $e) 
                        {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                        }
                             
					$row = mysql_fetch_assoc($result);
					//بارگذذاری اسکن پیشنهاد قیمت
					if ($_FILES["file1"]["error"] > 0) 
					{
						//echo "Error: " . $_FILES["file1"]["error"] . "<br>";
						//exit;
					} 
					else 
					{
						$ext = end((explode(".", $_FILES["file1"]["name"])));
						$attachedfile=$row['producerapprequestID'].'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
						move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/proposep/" .$attachedfile);   
					}
					
				   // print "پیشنهاد قیمت با کد پیگیری ".$resquery["producerapprequestID"].strtotime($SaveDate)." با موفقیت ثبت شد.";
					
				}  
				
			}				
    }
    else 
    {
        $condition1="";
        if (strlen(trim($_POST['ProducersID']))>0)//فیلتر شناسه تولید کننده
            $condition1.=" and producers.ProducersID='$_POST[ProducersID]'";
        if (strlen(trim($_POST['BankCode']))>0)//فیلتر کد رهگیری
            $condition1.=" and applicantmaster.BankCode='$_POST[BankCode]'";
        if (strlen(trim($_POST['ApplicantName']))>0)//فیلتر عنوان پروژه
            $condition1.=" and producerapprequest.ApplicantMasterID='$_POST[ApplicantName]'";
        if (strlen(trim($_POST['City']))>0)//فیلتر شهر پروژه
            $condition1.=" and shahr.id='$_POST[City]'";
        if (strlen(trim($_POST['Designer']))>0)//فیلتر طراح پروژه
            $condition1.=" and designer.DesignerID='$_POST[Designer]'";
        //فیلتر اندازه پروژه
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
        //فیلتر مبلغ برآوردی
        if (strlen(trim($_POST['IDcostprice']))>0)	
        if (trim($_POST['IDcostprice'])==1)
		$condition1.=" and producerapprequest.costprice>0 and producerapprequest.costprice<=100";
		else if (trim($_POST['IDcostprice'])==2)
		$condition1.=" and producerapprequest.costprice>100 and producerapprequest.costprice<=150";
		else if (trim($_POST['IDcostprice'])==3)
		$condition1.=" and producerapprequest.costprice>150 and producerapprequest.costprice<=200";
		else if (trim($_POST['IDcostprice'])==4)
		$condition1.=" and producerapprequest.costprice>200 and producerapprequest.costprice<=300";
		else if (trim($_POST['IDcostprice'])==5)
		$condition1.=" and producerapprequest.costprice>300 and producerapprequest.costprice<=500";
		else if (trim($_POST['IDcostprice'])==6)
		$condition1.=" and producerapprequest.costprice>500 and producerapprequest.costprice<=800";
		else if (trim($_POST['IDcostprice'])==7)
		$condition1.=" and producerapprequest.costprice>800 and producerapprequest.costprice<=1000";
		else if (trim($_POST['IDcostprice'])==8)
		$condition1.=" and producerapprequest.costprice>1000";
        //فیلتر مبلغ پیشنهادی
        if (strlen(trim($_POST['IDprice']))>0)	
        if (trim($_POST['IDprice'])==1)
		$condition1.=" and producerapprequest.price>0 and producerapprequest.price<=100";
		else if (trim($_POST['IDprice'])==2)
		$condition1.=" and producerapprequest.price>100 and producerapprequest.price<=150";
		else if (trim($_POST['IDprice'])==3)
		$condition1.=" and producerapprequest.price>150 and producerapprequest.price<=200";
		else if (trim($_POST['IDprice'])==4)
		$condition1.=" and producerapprequest.price>200 and producerapprequest.price<=300";
		else if (trim($_POST['IDprice'])==5)
		$condition1.=" and producerapprequest.price>300 and producerapprequest.price<=500";
		else if (trim($_POST['IDprice'])==6)
		$condition1.=" and producerapprequest.price>500 and producerapprequest.price<=800";
		else if (trim($_POST['IDprice'])==7)
		$condition1.=" and producerapprequest.price>800 and producerapprequest.price<=1000";
		else if (trim($_POST['IDprice'])==8)
		$condition1.=" and producerapprequest.price>1000";
        
        if (strlen($_POST['Datefrom'])>0)//از تاریخ
        $condition1.=" and (date(producerapprequest.SaveDate)>='".jalali_to_gregorian($_POST['Datefrom'])."')";
        if (strlen($_POST['Dateto'])>0)//تا تاریخ
        $condition1.=" and (date(producerapprequest.SaveDate)<='".jalali_to_gregorian($_POST['Dateto'])."')";
        
        if (strlen(trim($_POST['IDwin']))>0)//شناسه  منتخب پیشنهاد	
        if (trim($_POST['IDwin'])==0)//طرح هایی که شرکت لاگین شده منتخب بوده است را نشان ندهد
		$condition1.=" and applicantmaster.ApplicantMasterID NOT IN(select ApplicantMasterID from producerapprequest producerapprequestin
        where producerapprequestin.ApplicantMasterID=producerapprequest.ApplicantMasterID and producerapprequestin.state=1)";
		else if (trim($_POST['IDwin'])==1)//طرح هایی که شرکت لاگین شده منتخب شده است را نشان دهد یا هنوز برگزار نشده است
		$condition1.=" and applicantmaster.ApplicantMasterID IN(select ApplicantMasterID from producerapprequest producerapprequestin
        where producerapprequestin.ApplicantMasterID=producerapprequest.ApplicantMasterID and producerapprequestin.state=1) and producerapprequest.state=0";
		else if (trim($_POST['IDwin'])==2)//طرح هایی که شرکت لاگین شده منتخب بوده است
		$condition1.=" and producerapprequest.state='1'";
    }
}

    /*
        invoicemaster جدول لیست لوازم
        producersID شناسه تولید کننده
        proposable ارسال شده به پیشنهاد قیمت
        invoicemasterid شناسه جدول لیست لوازم
        applicantmaster جدول مشخصات طرح
        DesignArea مساحت طرح
        ApplicantMasterID شناسه طرح
        producerapprequest جدول پیشنهادات قیمت
        state وضعیت انتخابی
        ApproveA تایید ارسال لوازم
        applicantstatesID شناسه وضعیت طرح
        InvoiceMasterIDmaster شناسه طرح اجرایی
        pricenotinrep مبلغ در هزینه های طر اعمال نشود
        costnotinrep هزینه اجرا در هزینه های طرح اعمال نشود
    */
    $sql1="
    select count(*) invoicenotdeliveredcnt from 
    ( select distinct invoicemaster.applicantmasterid from
    invoicemaster 
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
    inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid and applicantmaster.proposestatep=3
    inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid and ifnull(prjtypeid,0)=0
    where ifnull(invoicetiming.ApproveA,'')='' 
    and ifnull(applicantmaster.applicantstatesID,0) not in (34)
     and ifnull(applicantmaster.private,0)=0  
    and substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2) and ifnull(invoicemaster.proposable,0)>0
    and ProducersID='$login_ProducersID')
    "; 	
    //print $sql1;
    $resultup = mysql_query($sql1);	
    $resquery = mysql_fetch_assoc($resultup);		
    $invoicenotdeliveredcnt=$resquery["invoicenotdeliveredcnt"];
    /*
        gadget3 جدول
        UnitsCoef2 ضریب تبدیل واحد اصلی به فرعی
        Number تعداد
        invoicedetail  جدول ریز لوازم
        invoicemaster جدول لیست لوازم
        producersID شناسه تولید کننده
        proposable ارسال شده به پیشنهاد قیمت
        invoicemasterid شناسه جدول لیست لوازم
        gadget3ID شناسه سطح 3 ابزار
        ProducersID شناسه جدول تولیدکننده
        toolsmarks جدول مارک و ابزار
        ToolsMarksID شناسه جدول مارک ابزار
        invoicetiming جدول زمان بندی تولید
        ApproveA تایید ارسال لوازم
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        producersID شناسه تولید کننده
        proposestatep وضعیت انتخابی
        applicantstatesID شناسه وضعیت طرح
    */
    $sql1="select ROUND(sum(gadget3.UnitsCoef2*invoicedetail.Number)/1000,1) tonajval from invoicemaster 
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
    inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
    inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
    inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID and gadget3.gadget2ID in (495,494,202,376)
    inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid  and applicantmaster.proposestatep=3
    where ifnull(invoicetiming.ApproveA,'')='' 
    and ifnull(applicantmaster.applicantstatesID,0) not in (34)
     and ifnull(applicantmaster.private,0)=0  
    and substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2) and invoicemaster.ProducersID<>148
    where invoicemaster.ProducersID='$login_ProducersID'"; 	
    $resultup = mysql_query($sql1);	
    $resquery = mysql_fetch_assoc($resultup);		
    $tonajval=$resquery["tonajval"];
     /*
        applicantmaster جدول مشخصات طرح
        creditsource جدول منابع اعتباری
        creditsourcetitle عنوان منبع اعتباری
        tanzilpipe نرخ تنزیل قیمت 
        designer جدول مشخصات طراحان
        shahr جدول شهرها
        designsystemgroups جدول سیستم آبیاری
        producerapprequest جدول پیشنهادات قیمت
        state وضعیت انتخابی
        producers جدول مشخصات تولیدکنندگان
        producers.rank رتبه تولید کننده
        producers.Title عنوان تولید کننده
        producers.CompanyAddress آدرس تولید کننده
        SaveDate تاریخ
        Freestate وضعیت آزادسازی
        prjtypetitle نوع پروژه
        validday اعتبار پیشنهاد فیمت اعلامی
        producerapprequestID شناسه جدول پیشنهاد قیمت
        boardvalidationdate اعتبار تاریخ هیئت مدیره
        copermisionvalidate تاریخ اعتبار مجوز شرکت
        joinyear تاریخ تاسیس شرکت
        errors پیغام های عدم صلایت کاربر
        PE32 مبلغ  پیشنهادی برای لوله های 32
        PE40 مبلغ  پیشنهادی   برای لوله های 40
        PE80 مبلغ  پیشنهادی   برای لوله های 80
        PE100 مبلغ  پیشنهادی   برای لوله های 100
        PE32app مبلغ تایید شده برای لوله های 32
        PE40app مبلغ تایید شده برای لوله های 40
        PE80app مبلغ تایید شده برای لوله های 80
        PE100app مبلغ تایید شده برای لوله های 100
        prjtype.title عنوان نوع پروژه
        producers.guaranteepayval مبلغ ضمانت نامه شرکت
        producers.guaranteeExpireDate تاریخ اعتبار ضمانت نامه بانکی
        applicantmasterdetail جدول ارتباطی  طرح ها
        ApplicantMasterID شناسه مطالعات
        ApplicantMasterIDmaster شناسه طر اجرایی
        ApplicantMasterIDsurat شناسه طرح صورت وضعیت
        prjtype جدول انواع پروژه ها
    */
    $sql = "SELECT distinct applicantmaster.*,creditsource.title creditsourcetitle,tanzilpipe,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
                ,designsystemgroups.title designsystemgroupstitle,producers.*,producers.rank corank,producers.Title producercoTitle
                ,boardvalidationdate,
                copermisionvalidate,joinyear,applicantmasterd.Freestate Freestated,prjtype.title prjtypetitle
    ,case ifnull(applicantmasterdetail.prjtypeid,0) when 0 then producers.guaranteepayval else guarantee.guaranteepayval end prjguaranteepayval
    ,case ifnull(applicantmasterdetail.prjtypeid,0) when 0 then producers.guaranteeExpireDate else guarantee.guaranteeExpireDate end prjguaranteeExpireDate
                ,proposed.applicantmasterid proposedapplicantmasterid
                FROM applicantmaster 
                inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
                and substring(shahr.id,3,5)<>'00000' and  substring(shahr.id,1,2)=substring('$login_CityId',1,2)
                left outer join designer on designer.designerid=applicantmaster.designerid
                left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
                inner join producers on producers.ProducersID='$login_ProducersID' 
                left outer join (select applicantmasterid from producerapprequest where ProducersID='$login_ProducersID') proposed
                on proposed.applicantmasterid=applicantmaster.applicantmasterid
     inner join (select distinct applicantmasterid applicantmasteridallproposable from invoicemaster where proposable=1) allproposable on 
    allproposable.applicantmasteridallproposable=applicantmaster.applicantmasterid
    inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then applicantmasterdetail.ApplicantMasterID else
    applicantmasterdetail.ApplicantMasterIDmaster end=applicantmaster.ApplicantMasterID
    inner join applicantmaster applicantmasterd on applicantmasterd.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
    left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
    left outer join creditsource on creditsource.creditsourceid=applicantmasterd.creditsourceid
    left outer join guarantee on producers.producersid=CoID and CoType=1 and guarantee.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)  
where  
ifnull(applicantmaster.proposestatep,0)=0  
$condp
and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and ifnull(applicantmaster.isbandp,0)=0
ORDER BY applicantmaster.DesignArea ";
if ($login_ProducersID>0)  $resultup = mysql_query($sql);
//print $sql;exit;

if ($showkejra==3)
{
  if ($login_DesignerCoID>0 || $login_designerCO==0 ) 
        $condition1="where producerapprequest.ProducersID='$login_ProducersID'";
        else if (! $_POST) $condition1="where case producerapprequest.state when 1 then 'برنده پیشنهاد' else 
    case ifnull((select ApplicantMasterID from producerapprequest producerapprequestin
    where producerapprequestin.ApplicantMasterID=case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID else -producerapprequest.ApplicantMasterID end and producerapprequestin.state=1),0) when 0 then 'برگزار نشده' 
    else 'عدم انتخاب' end end='برگزار نشده'";
    /*
        producerapprequest جدول پیشنهادات قیمت
        state وضعیت انتخابی
        producers جدول مشخصات تولیدکنندگان
        producers.rank رتبه تولید کننده
        producers.Title عنوان تولید کننده
        producers.CompanyAddress آدرس تولید کننده
        SaveDate تاریخ
        validday اعتبار پیشنهاد فیمت اعلامی
        producerapprequestID شناسه جدول پیشنهاد قیمت
        boardvalidationdate اعتبار تاریخ هیئت مدیره
        copermisionvalidate تاریخ اعتبار مجوز شرکت
        joinyear تاریخ تاسیس شرکت
        errors پیغام های عدم صلایت کاربر
        PE32 مبلغ  پیشنهادی برای لوله های 32
        PE40 مبلغ  پیشنهادی   برای لوله های 40
        PE80 مبلغ  پیشنهادی   برای لوله های 80
        PE100 مبلغ  پیشنهادی   برای لوله های 100
        PE32app مبلغ تایید شده برای لوله های 32
        PE40app مبلغ تایید شده برای لوله های 40
        PE80app مبلغ تایید شده برای لوله های 80
        PE100app مبلغ تایید شده برای لوله های 100
        prjtype.title عنوان نوع پروژه
        producers.guaranteepayval مبلغ ضمانت نامه شرکت
        producers.guaranteeExpireDate تاریخ اعتبار ضمانت نامه بانکی
        applicantmasterdetail جدول ارتباطی  طرح ها
        ApplicantMasterID شناسه مطالعات
        ApplicantMasterIDmaster شناسه طر اجرایی
        ApplicantMasterIDsurat شناسه طرح صورت وضعیت
        prjtype جدول انواع پروژه ها
        applicantmaster جدول مشخصات طرح
        creditsource جدول منابع اعتباری
        creditsourcetitle عنوان منبع اعتباری
        tanzilpipe نرخ تنزیل قیمت 
        designer جدول مشخصات طراحان
        shahr جدول شهرها
    
    */
    
    $sql = "SELECT distinct producerapprequest.producerapprequestID,producerapprequest.costprice,producerapprequest.price
    ,((producerapprequest.price-producerapprequest.costprice)/producerapprequest.costprice) percentage
    ,producerapprequest.validday,producerapprequest.PE32tonaj,producerapprequest.PE40tonaj,producerapprequest.PE80tonaj,producerapprequest.PE100tonaj
    ,applicantmaster.ApplicantName,applicantmaster.DesignArea,applicantmaster.BankCode,CONCAT(designer.LName,' ',designer.FName) designername 
    ,shahr.cityname shahrcityname,producers.Title producercoTitle 
    ,designsystemgroups.title designsystemgroupstitle,producerapprequest.state, applicantmaster.LastFehrestbaha,
    case producerapprequest.state when 1 then 'برنده پیشنهاد' else 
    case ifnull((select ApplicantMasterID from producerapprequest producerapprequestin
    where producerapprequestin.ApplicantMasterID=case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID else -producerapprequest.ApplicantMasterID end and producerapprequestin.state=1),0) when 0 then 'برگزار نشده' 
    else 'عدم انتخاب' end end winstate,producerapprequest.SaveDate producerapprequestSaveDate
    ,ifnull(applicantmaster.proposestatep,0) proposestatep,applicantmasterd.Freestate Freestated,creditsource.title creditsourcetitle,tanzilpipe
    FROM applicantmaster 
    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    left outer join designer on designer.designerid=applicantmaster.designerid
    left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
    inner join producerapprequest on case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID else -producerapprequest.ApplicantMasterID end=applicantmaster.ApplicantMasterID
    inner join producers on producers.ProducersID=producerapprequest.ProducersID
        inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then applicantmasterdetail.ApplicantMasterID else
    applicantmasterdetail.ApplicantMasterIDmaster end=applicantmaster.ApplicantMasterID
        
    inner join applicantmaster applicantmasterd on applicantmasterd.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
    left outer join creditsource on creditsource.creditsourceid=applicantmasterd.creditsourceid
    $condition1 
    ORDER BY producerapprequestID DESC,applicantmaster.BankCode COLLATE utf8_persian_ci ;";
//print $sql;

//print $sql;exit;

    try 
        {		
            $result = mysql_query($sql);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 


}

//پرس و جوی مربوط به کومبوباکس انتخاب شدن یا نشدن
$query="
select 'برگزار نشده' _key,0 as _value union all 
select 'عدم انتخاب' _key,1 as _value union all
select 'منتخب پیشنهاد' _key,2 as _value ";
$IDwin = get_key_value_from_query_into_array($query);

//پرس و جوی مربوط به کومبوباکس لیست تولیدکنندگان
$query="select distinct producers.ProducersID _value, producers.Title _key from  producers 
inner join producerapprequest on producerapprequest.ProducersID=producers.ProducersID order by _key  COLLATE utf8_persian_ci";
$ID1 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به کومبوباکس کد های رهگیری
$query="select distinct applicantmaster.BankCode _key ,applicantmaster.BankCode _value from applicantmaster 
inner join producerapprequest on applicantmaster.ApplicantMasterID=producerapprequest.ApplicantMasterID order by _key  COLLATE utf8_persian_ci";
$ID2 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به کومبوباکس عناوین پروژه ها
$query="select distinct applicantmaster.ApplicantMasterID _value, applicantmaster.ApplicantName _key from applicantmaster 
inner join producerapprequest on applicantmaster.ApplicantMasterID=producerapprequest.ApplicantMasterID order by _key  COLLATE utf8_persian_ci";
$ID3 = get_key_value_from_query_into_array($query);
//پرس و جوی مربوط به کومبوباکس  شهرها
$query="select distinct id _value,CityName _key from applicantmaster
inner join tax_tbcity7digit on substring(tax_tbcity7digit.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,5)<>'00000' and ifnull(applicantmaster.DesignerCoID,0)>0 
";
$ID4 = get_key_value_from_query_into_array($query);




?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیشنهاد قیمت لوله </title>

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
   
	if (document.getElementById('login_RolesID').value==3)
    {   

		if (!(document.getElementById('trans').checked))
    	{
            alert('لطفا هزینه حمل تا محل پروژه را تایید نمایید.');return false;
        }
        if (!(document.getElementById('file1').value != "">0))
        {
            alert('لطفا اسکن فایل پیشنهاد قیمت را انتخاب نمایید!');//return false;
        }
		if (!(numberWithoutCommas(document.getElementById('PE32').value)>0))
		{
			alert('مبلغ  پیشنهادی PE32 نا معتبر می باشد!');return false;
		}   
		if (!(numberWithoutCommas(document.getElementById('PE40').value)>0))
		{
			alert('مبلغ  پیشنهادی PE40 نا معتبر می باشد!');return false;
		}   
		if (!(numberWithoutCommas(document.getElementById('PE80').value)>0))
		{
			alert('مبلغ  پیشنهادی PE80 نا معتبر می باشد!');return false;
		}   
		if (!(numberWithoutCommas(document.getElementById('PE100').value)>0))
		{
			alert('مبلغ  پیشنهادی PE100 نا معتبر می باشد!');return false;
		}   
       if (!(numberWithoutCommas(document.getElementById('prop').value)>0))
        {
            alert('مبلغ  پیشنهادی نا معتبر می باشد!');return false;
        }        
		 
							
		return confirm(' *جمع مبلغ پیشنهادی محاسبه شده با جمع اسکن پیش فاکتور (بدون ارزش افزوده) بایستی مطابقت داشته باشد. \n *پیشنهاد قیمت با اسکن ناخوانا حذف خواهد شد.  \n *قیمتهای پیشنهادی جهت انتخاب تولیدکننده (فروشنده) در اختیار متقاضی (کشاورز)  قرار خواهد گرفت. \n *پیشنهاد قیمت با اسکن ناخوانا حذف خواهد شد.  \n *درصورت عدم رعایت هرکدام از شرایط سامانه پیشنهاد قیمت حذف خواهد شد. \n این شرکت ضمن مطالعه و قبول شرایط ذیل پیشنهاد قیمت و آگاهی از ضوابط و قوانین استفاده از سامانه، پیشنهاد قیمت خود را ارسال می نماید. ');			
					
    }
  }
 function numberWithoutCommas(x) {
    var number = x.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
    return number;    
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
    function fillform(Url)
    {
                   var selectedBankcode=document.getElementById('Bankcode').value;
                    var selectedlogin_ProducersID=document.getElementById('login_ProducersID').value;
                    //alert(selectedlogin_ProducersID);
                     if (selectedBankcode.length>0)
                    {
                        $("#loading-div-background").show();
                        //alert(selectedBankcode);
                        $.post(Url, {selectedBankcode:selectedBankcode,selectedlogin_ProducersID:selectedlogin_ProducersID}, function(data){
                       // alert(data.ApplicantMasterID);
                        $("#loading-div-background").hide();  
                       
                                    
                        if (data.error==1)  
                            alert('امکان پیشنهاد قیمت فقط برای فروشندگان فراهم می باشد');  
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
                                    $('#shahrcityname').val(data.shahrcityname);
                                    $('#eval').val(Math.floor(data.eval/100000)/10) ;
                                    $('#pe32tOTALw').val(data.totalpe32num);
                                    $('#pe40tOTALw').val(data.totalpe40num);
                                    $('#pe80tOTALw').val(data.totalpe80num);
                                    $('#pe100tOTALw').val(data.totalpe100num);
                                    $('#Datebandp').val(data.Datebandp); 
									$('#Datebandp').val(data.Datebandp);
                                    $('#ApplicantMasterID').val(data.ApplicantMasterID);
                                    valchange();
                               // alert(1);
                                }  
							
                                }, 'json');
								
                    }
    }
    
     function valchange()
    {
       // alert(1);
        if (document.getElementById('PE32').value.length==0) document.getElementById('PE32').value=0;
        if (document.getElementById('PE40').value.length==0) document.getElementById('PE40').value=0;
        if (document.getElementById('PE80').value.length==0) document.getElementById('PE80').value=0;
        if (document.getElementById('PE100').value.length==0) document.getElementById('PE100').value=0;
        document.getElementById('prop').value=(Math.round(
                                                    document.getElementById('pe32tOTALw').value*numberWithoutCommas(document.getElementById('PE32').value)+
                                                    document.getElementById('pe40tOTALw').value*numberWithoutCommas(document.getElementById('PE40').value)+
                                                    document.getElementById('pe80tOTALw').value*numberWithoutCommas(document.getElementById('PE80').value)+
                                                    document.getElementById('pe100tOTALw').value*numberWithoutCommas(document.getElementById('PE100').value)));
												document.getElementById('prop').value=(Math.floor(document.getElementById('prop').value/100000)/10);
    }
   function checkchange()
   {
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
				var uid=document.getElementById('uid1').value;
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
            
            <form action="apprequestp.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                <table id="records" width="95%" align="center">
                       
                   <tbody >
                              <thead style = "text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';">
                             <td class='data'><input name='tanzilpipe' type='hidden' readonly class='textbox' id='tanzilpipe' value="<?php echo $tanzilpipe?>" /></td>
                              
                              
                              
                             <td class='data'><input name='login_RolesID' type='hidden' readonly class='textbox' id='login_RolesID' value="<?php echo $login_RolesID?>" /></td>
                             <td class='data'><input name='login_ProducersID' type='hidden' readonly class='textbox' id='login_ProducersID' value="<?php echo $login_ProducersID?>" /></td>
                             <td class='data'><input name='propose30daypermissionless' type='hidden' readonly class='textbox' id='propose30daypermissionless' value="<?php echo $Permissionvals['propose30daypermissionless']?>" /></td>
          <?php                  
            //              <tr>
              //              <th colspan="12"  style = "text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';" ><a target='blank' href='../../upfolder/formpishnahad.docx' >دانلود فرم پیشنهاد قیمت</a></th>
                //          </tr>
           ?>                   
                          <tr>
                            <th colspan="12"  style = "text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';">لیست طرح های قابل پیشنهاد</th>
							<th><a  target='_blank' href='../temp/lulepropose.htm'>راهنما</a></th>	 
                          </tr>
                         <tr>
                            <th colspan="12"  style = "color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';">+(بعد از <?php echo $Permissionvals['proposedaycnt']?> روز از تاریخ شروع دریافت پیشنهاد هر طرح، در صورت رسیدن به حد نصاب، دریافت پیشنهاد قیمت طرح توسط مدیریت آب و خاک متوقف می شود.)</th>
                          </tr>
						    </thead>
                          <tr>
                            <td width="5%" style ="background-color:#f3f3f3;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">ردیف</td>
                            <td width="15%" colspan="1" style ="background-color:#f1f1f1;border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">کد رهگیری</td>
                            <td width="15%" colspan="1" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">پروژه</td>
                            <td width="15%" colspan="1" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">متقاضي</td>
                            <td width="10%" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">شهرستان</td>
                            <td width="5%" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">Ha/m</td>
                            <td width="15%" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">تاریخ شروع واتمام+ دریافت پیشنهاد</td>
                            <td colspan="1" width="27%" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;"><?php echo str_replace(' ', '&nbsp;', "دلایل عدم صلاحیت"); ?> </td>
                             <td colspan="1" width="1%" style ="border:1px solid black;border-color:#D1D1D1 #D1D1D1;text-align:center;">ریز</td>
                        </tr>
          <?php
                    	 //$lenn = (strtotime($Permissionvals['proposedaycnt']))*86400;		
					
                    $Total=0; $rown=0; $contin=0;$Datebandp='';
                    if ($login_ProducersID>0)
                    while($resquery = mysql_fetch_assoc($resultup)){
                        if ($resquery["proposedapplicantmasterid"]>0)  continue;
							$ApId=$resquery['ApplicantMasterID']-floor($resquery['ApplicantMasterID']/10)*10;
						//if ($resquery["prjtypetitle"]=='آبرسانی کم فشار' && ($ApId==1 || $ApId==3 || $ApId==4)) continue;
                        
                            $Freestate=$resquery["Freestated"];
							$timeto=0;
                            $timeto=$Permissionvals['proposedaycnt']*86399+strtotime($resquery['TMDate']);
							$timeto=gregorian_to_jalali(date('Y-m-d', $timeto));
								
							$Datebandp=$resquery["Datebandp"];							
                            $ApplicantName = $resquery["ApplicantFName"] .' '.$resquery["ApplicantName"];
                        	$DesignArea = $resquery["DesignArea"];
                            $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                            $shahrcityname = $resquery["shahrcityname"];
                            $designername = $resquery["designername"];
  							$errors="";
                        /*
                            $joinyearlow=0;
                            $date = new DateTime(jalali_to_gregorian($resquery["joinyear"]));
                            $date->modify('+720 day');
                            //$date->add(new DateInterval('P2Y'));
                            if ($date->format('Y-m-d')>date('Y-m-d'))
                                $joinyearlow=1;
								
                            if ($resquery["corank"]==1 && ( ($tonajval+$resquery["projtonajval"])>$Permissionvals['p1Zpishhamzamanvol'] ))      $errors.="<br>*تناز پیش فاکتور همزمان (".($tonajval+$resquery["projtonajval"]).") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==2 && (($tonajval+$resquery["projtonajval"])>$Permissionvals['p2Zpishhamzamanvol'])) $errors.="<br>*تناز پیش فاکتور همزمان (".($tonajval+$resquery["projtonajval"]).") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==3 && (($tonajval+$resquery["projtonajval"])>$Permissionvals['p3Zpishhamzamanvol'])) $errors.="<br>*تناز پیش فاکتور همزمان (".($tonajval+$resquery["projtonajval"]).") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==4 && (($tonajval+$resquery["projtonajval"])>$Permissionvals['p4Zpishhamzamanvol'])) $errors.="<br>*تناز پیش فاکتور همزمان (".($tonajval+$resquery["projtonajval"]).") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==5 && (($tonajval+$resquery["projtonajval"])>$Permissionvals['p5Zpishhamzamanvol'])) $errors.="<br>*تناز پیش فاکتور همزمان (".($tonajval+$resquery["projtonajval"]).") بیشتر از حد مجاز است"; 
                       */
                       $errors=""; $errorsa="";
                      
					    if ($resquery["prjguaranteeExpireDate"]<gregorian_to_jalali(date('Y-m-d')))
                        $errorsa.="<br>*تاریخ اعتبار ضمانتنامه بانکی (".$resquery["prjguaranteeExpireDate"].") منقضی شده است";
                       
                       if ($resquery["corank"]==1 && ($resquery["prjguaranteepayval"]<$Permissionvals['p1Zemanat']*10))      $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نیست "; 
                            else if ($resquery["corank"]==2 && ($resquery["prjguaranteepayval"]<$Permissionvals['p2Zemanat']*10)) $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نیست";
                            else if ($resquery["corank"]==3 && ($resquery["prjguaranteepayval"]<$Permissionvals['p3Zemanat']*10)) $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نیست";
                            else if ($resquery["corank"]==4 && ($resquery["prjguaranteepayval"]<$Permissionvals['p4Zemanat']*10)) $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نیست";
                            else if ($resquery["corank"]==5 && ($resquery["prjguaranteepayval"]<$Permissionvals['p5Zemanat']*10)) $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نیست"; 
					      
                            if ($resquery["corank"]==1 && ($invoicenotdeliveredcnt>=$Permissionvals['p1Zpishhamzaman']))      $errors.="<br>*تعداد پیش فاکتور همزمان (".$invoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==2 && ($invoicenotdeliveredcnt>=$Permissionvals['p2Zpishhamzaman'])) $errors.="<br>*تعداد پیش فاکتور همزمان (".$invoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==3 && ($invoicenotdeliveredcnt>=$Permissionvals['p3Zpishhamzaman'])) $errors.="<br>*تعداد پیش فاکتور همزمان (".$invoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==4 && ($invoicenotdeliveredcnt>=$Permissionvals['p4Zpishhamzaman'])) $errors.="<br>*تعداد پیش فاکتور همزمان (".$invoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                            else if ($resquery["corank"]==5 && ($invoicenotdeliveredcnt>=$Permissionvals['p5Zpishhamzaman'])) $errors.="<br>*تعداد پیش فاکتور همزمان (".$invoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                      
                        if (($resquery["corank"]<1)||($resquery["corank"]>5)) 
                        $errorsa.="<br>*رتبه شرکت نامعتبر می باشد";
                       
                        if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errors.="<br>تاریخ اعتبار هیئت مدیره منقضی شده است.";
                        if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
                            $errorsa.="<br>تاریخ مجوز شرکت منقضی شده است.";
                        //if (($resquery["corank"]==5) && ($resquery["DesignArea"]>55) && ($joinyearlow==1))  
                        //        $errors.="<br>شرکت سابقه کافی جهت پیشنهاد قیمت این طرح را دارا نمی باشد.";  
                       //  if (strlen($errors)==0) $contin=1;
                		if ($Permissionvals['hidecredit']==1) $hide="display:none;"; else $hide="";
						if (strlen($errorsa)>0) $cl='ff0000'; else $cl='000000'; 
						if (strlen($Datebandp)>0) $clr='FF5C5C'; else $clr=$cl; 
						
						$rown++;					   
                       //$errors='';
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
                                        $fstr1="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 35px;' src='../img/attachment.png' title='فایل اتوکد' ></a>";
                                    
                                    if (($ID==$resquery['ApplicantMasterID']) && ($No==2) )
                                        $fstr2="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 35px;' src='../img/full_page.png' title='دفترچه طراحی' ></a>";
                                    
                                    if (($ID==$resquery['ApplicantMasterID']) && ($No==3) )
                                        $fstr3="<a target='_blank' href='../../upfolder/$file' ><img style = 'width: 35px;' src='../img/new_page.png' title='دفترچه محاسبات' ></a>";        
                                }
                            }
                            
                        }
	                    $ID = $resquery['ApplicantMasterID']."_11_0_0_".$resquery['applicantstatesID']."_$login_ProducersID"; 
						if ( ($login_userid!=$resquery["Disabled"]) || ($resquery['BankCode']!=$resquery["StarCo"]) )
						{ 
						if ($contin==1 && $DesignArea>11)
						{ ?>
						<?php continue;}
						else
						{ ?>
                        <tr>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $clr; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["BankCode"]; ?></td>
                            <td colspan="1" class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["prjtypetitle"]; ?></td>
                            <td colspan="1" class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $ApplicantName; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $shahrcityname ?></td>
 							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php 
                    		if ($hide!="")
							echo $DesignArea;
                            else
							{
							echo $DesignArea.str_replace(' ', '&nbsp;', "<br>".$resquery['creditsourcetitle']);
                            if ($Freestate>0) echo '<br>(اسناد خزانه اسلامی)';
                            }
                             ?></td>
							<td class="f10_font<?php echo $b; ?>"   style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  gregorian_to_jalali( $resquery['TMDate']).'.....'. $timeto?></td>
                            <td colspan="1" class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo substr($errors,4).substr($errorsa,4) ?></td> 
	                       <td class='data'><input name='errorsa' type='hidden' readonly class='textbox' id='errorsa' value="<?php echo $errorsa?>" /></td>
     				
						<?php
						//if ($early==0) 
						
                        print "
						<td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">
                        <a  target=\"_blank\"  href='summaryinvoice.php?uid=".
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).$ID.rand(10000,99999).
                        "'><img style = 'width: 25px;' src='../img/search_page.png' title=' ريز '></a></td>";				 	
/*                        print "
                        <td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">$fstr1</td>
                        <td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">$fstr2</td>
                        <td style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">$fstr3</td></tr>";
*/
						}
					}
                    else 
						echo  "<tr>
                            <th colspan='12'  style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"
                            >شرکت محترم.
                            امکان مشاهده کامل پیشنهاد های قیمت برای شما فراهم نمی باشد. لطفا با مدیریت آب و خاک تماس حاصل فرمایید.</th>
                          </tr> ";
				}  
?>

                </table>
                <table id="records" width="95%" align="center">
                    <thead style = "text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';">
             
                   <?php   
                   if ($early==0)
                   {
					  print "  
			            <tr>&nbsp</tr><tr>
                          <th colspan='13' style = \"text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';background-color:#A4F5BA;\"
                            >ثبت پیشنهاد قیمت (ریال)</th>
                        </tr>    
                        <tr>    
                            <th colspan='13' style = \"text-align:center;font-size:18;font-weight: bold;font-family:'B Nazanin';\"
                            >کلیه اطلاعات پیشنهاد قیمت در اختیار مدیریت آب و خاک می باشد، لطفاً جهت هرگونه اعلام نظر و تغییرات با مدیر آب و خاک تماس گرفته شود. </th>
                        </tr>
                    </thead>
					
                          <tr>
                        	<td ></td>
                            <td >کد رهگیری</td>
                            <td > نام متقاضي</td>
                            <td >شهرستان</td>
                            <td >PE32</td>
                            <td >PE40</td>
                            <td >PE80</td>
                            <td >PE100</td>
                            <td >برآورد طراحی</td>
							<td >جمع پیشنهادی</td>
							<td >اعتبار(روز)</td>
							<td >اسکن پیش فاکتور </td>
						    </tr>
                    ";
			
            		 print  "<tr>   
                            <td colspan='2' class='data'><div id='divBankcode'>
                            <input name='Bankcode' type='text' class='textbox' id='Bankcode'  style='background-color:#ffff00;width: 150px' 
                            onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/apprequestp_jr.php');\"/></div>
                            </td>
                            
                            <td class='data'><div id='divApplicantName'>
                            <input name='ApplicantName' readonly type='text' class='textbox' id='ApplicantName'  style='width: 120px' /></div>
                            </td>
                            
                            <td class='data'><div id='divshahrcityname'>
                            <input name='shahrcityname' readonly type='text' class='textbox' id='shahrcityname'  style='width: 100%' /></div>
                            </td>
                            
                            <td class='data'><div id='divPE32'>
                            <input name='PE32' onKeyUp=\"convert('PE32')\" onblur='valchange();'  type='text' class='textbox' id='PE32'  
							placeholder='".number_format($Permissionvals['maxpe32pipeprice'])."' style='background-color:#ffff00;width: 100%' /></div>
                            </td>
                                  
                            <td class='data'><div id='divPE40'>
                            <input name='PE40' onKeyUp=\"convert('PE40')\" onblur='valchange();'  type='text' class='textbox' id='PE40'  
							placeholder='".number_format($Permissionvals['maxpe40pipeprice'])."' style='background-color:#ffff00;width: 100%' /></div>
                            </td>
                                  
                            <td class='data'><div id='divPE80'>
                            <input name='PE80' onKeyUp=\"convert('PE80')\" onblur='valchange();' type='text' class='textbox' id='PE80'  
							placeholder='".number_format($Permissionvals['maxpe80pipeprice'])."' style='background-color:#ffff00;width: 100%' /></div>
                            </td>
                                  
                            <td class='data'><div id='divPE100'>
                            <input name='PE100' onKeyUp=\"convert('PE100')\" onblur='valchange();' type='text' class='textbox' id='PE100'  
							placeholder='".number_format($Permissionvals['maxpe100pipeprice'])."' style='background-color:#ffff00;width: 100%' /></div>
                            </td>
                                  
                            <td class='data'><div id='diveval'>
                            <input name='eval'  type='text' class='textbox' id='eval'  style='width: 100%' /></div>
                            </td>
                                  
                            <td class='data'><div id='divprop'>
                            <input name='prop'  type='text' readonly class='textbox' id='prop'  style='background-color:#F0E68C;width: 100%' /></div>
                            </td>
                                   
                            <td class='data'><div id='divvalidday'>
                            <input name='validday'  type='text' class='textbox' id='validday'  min='$Permissionvals[validday]'   value='$Permissionvals[validday]' 
							 style='background-color:#ffff00;width: 100%' /></div>
                            </td>
                            
                            <td colspan='1' class='data'><input type='file' name='file1' id='file1' style='width: 100%'></td>
                            
                            
							<td><input   name='submit' type='submit' class='button' id='submit' style='width: 100%' value='ثبت'  $linkcmd /></td>
                            
							
							<td class='data'><input name='ApplicantMasterID' type='hidden' readonly class='textbox' id='ApplicantMasterID' /></td>
							<td class='data'><input name='Datebandp' type='hidden' readonly class='textbox' id='Datebandp'   /></td>
							<td class='data'><input name='pe32tOTALw' type='hidden' readonly class='textbox' id='pe32tOTALw'  size=1/></td>
							<td class='data'><input name='pe40tOTALw' type='hidden' readonly class='textbox' id='pe40tOTALw' size=1/></td>
							<td class='data'><input name='pe80tOTALw' type='hidden' readonly class='textbox' id='pe80tOTALw' size=1/></td>
							<td class='data'><input name='pe100tOTALw' type='hidden' readonly class='textbox' id='pe100tOTALw' size=1/></td>
							
                    </tr>
                	
					
					
                   <tr> 
				   
				    <th class='data'> </th>
                    <th colspan='5'>*در قیمت پیشنهادی کرایه حمل تا محل پروژه منظور شده است.<input name='trans' type='checkbox' id='trans'> </th>
                        
                    <th colspan='6'>* اسكن پيشنهاد قيمت کاملا خوانا(حداکثر 100 کیلوبایت) </br>
                            <label style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"
                            > پیشنهاد قیمت با اسکن ناخوانا حذف خواهد شد</label></th>
                   </tr>  
                   <tr> 
                        <td colspan='12'>* 
                        <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" 
						>قیمت های پیشنهادی قطعی نبوده و پس از اعلام برنده و بررسی پیش فاکتور های لوازم پروژه، قیمت نهایی محاسبه خواهد گردید.</span>  </td>
                   </tr>  
				   <tr> 
                        <td colspan='12'>* 
                        <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" 
						>قیمتهای پیشنهادی جهت انتخاب تولیدکننده (فروشنده) در اختیار متقاضی (کشاورز)  قرار خواهد گرفت.</span>  </td>
                   </tr>  
				   
                   <tr> 
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            لطفا جهت تغییر وضعیت  طرح های برنده در پیشنهاد قیمت ، کد رهگیری و شهرستان محل اجرای طرح  را به درستی در سربرگ ثبت طرح آبیاری وارد نمایید.</span>  </td>
                   </tr>
                   <tr> 
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            اسناد و مدارک پروژه های آماده اجرا را از دفتر مدیریت آب و خاک دریافت نمایید. مبالغ برآوردطراحی و جمع پیشنهادی بر حسب میلیون ریال می باشند.</span>  </td>
                   </tr>
                   <tr> 
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            جمع مبلغ پیشنهادی محاسبه شده با جمع اسکن پیش فاکتور (بدون ارزش افزوده) بایستی مطابقت داشته باشد.</span>  </td>
                   </tr>
                   <tr> 
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            جهت مشاهده جدول وزن لوله ها به سربرگ گزارش- لیست وزن لوله ها مراجعه نمایید.</span>  </td>
                   </tr>
                   <tr> 
                        <td colspan='12'>* 
                            <span style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\" >
                            حداکثر ظرفیت مطابق با مصوبه کمیته فنی مدیریت آب و خاک در نظر گرفته شده است.</span>  </td>
                   </tr>
                   ";
                   
                   }
                   else 
                   echo "
                   <tr>
                   <th colspan=\"13\"  style = \"color:#ff0000;text-align:center;font-size:16;font-weight: bold;font-family:'B Nazanin';\"
                   >توجه! امکان ثبت پیشنهاد قیمت از ساعت 1 بعد از ظهر تا ساعت 9 صبح فراهم می باشد</th>
                   </tr>
				    ";
          
				echo "
                   <tr>&nbsp</tr><tr>
                   <th colspan=\"14\"  style = \"color:#0000ff;text-align:center;font-size:16;font-weight: bold;font-family:'B Nazanin';background-color:#A4F5BA\"
                   >لیست پیشنهاد های ارسال شده (میلیون ریال)</th>
                   </tr>
				   
				    ";
          
  					$uid3="apprequestp.php?uid=3";
					$uid1="apprequestp.php?uid=1";
		
         ?>
                            
				<table id="records" width="95%" align="center">
                   <tbody >
                          <tr>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">شرکت</th>
                            <th style="text-align: center;" colspan="1">کد رهگیری</th>
                            <th style="text-align: center;" colspan="1">متقاضي</th>
                            <th  style=" <?php echo $hide;?>; text-align: center;" >اعتبار</th>
                            <th style="text-align: center;">شهرستان</th>
                            <th style="text-align: center;">کد پیگیری</th>
                            <th style="text-align: center;">م پیشنهادی</th>
                            <th style="text-align: center;">ت صدور </th>
                            <th style="text-align: center;">اعتبار</th>
                            <th style="text-align: center;">ت اعتبار</th>
                            <th style="text-align: center;">وضعیت
					
					<input name='showkejra' type='checkbox' id='showkejra' onChange='checkchange()' <?php if ($showkejra==3) echo "checked";?>>
				    <input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  />
                    <input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  />
                 
							</th>
							
							
                        </tr>
                         
                        <?php
                        if ($login_designerCO==1)
                        {
                            print "<tr>
						<td>ردیف</td>";
                        print select_option('ProducersID','',',',$ID1,0,'','','1','rtl',0,'',$ProducersID,'','100%'); 
                         print select_option('BankCode','',',',$ID2,0,'','','1','rtl',0,'',$BankCode,'','100%'); 
						print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'','100%'); 
						 print select_option('City','',',',$ID4,0,'','','1','rtl',0,'',$City,'','100%'); 
						 print select_option('Designer','',',',$ID5,0,'','','1','rtl',0,'',$Designer,'','100%'); 
						 print select_option('IDcostprice','',',',$IDcostprice,0,'','','1','rtl',0,'',$IDcostprice,'','100%'); 
						 print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice,'','100%')."
                        <td><input placeholder=\"انتخاب تاریخ\"  name=\"Datefrom\" type=\"text\" class=\"textbox\" 
                        id=\"Datefrom\" value=\"$Datefrom\" style='width: 80px'/></td>
                        <td><input placeholder=\"انتخاب تاریخ\"  name=\"Dateto\" type=\"text\" class=\"textbox\" id=\"Dateto\" 
                        value=\"$Datefrom\" style='width: 80px' /></td>";
                        
					     print select_option('IDwin','',',',$IDwin,0,'','','1','rtl',0,'',$IDwin,'','100%')."
                         <td><input   name='search' type='submit' class='button' id='search ' value='جستجو' /></td></tr>"; 
                        }
                        ?> 
						
                        
         <?php
                     
                    
                    $Total=0;
                    $rown=0;
					if ($login_isfulloption==1)
                    while($resquery = mysql_fetch_assoc($result))
                    {
						$Freestate=$resquery["Freestated"];
					
						if ($login_designerCO<>1)
						if ($showkejra<=1 && $resquery["winstate"]=='عدم انتخاب') continue;
						//if ($login_isfulloption<>1 && $resquery["winstate"]!='برگزار نشده') continue;
					
                            $ApplicantName = $resquery["ApplicantName"];
                        	$DesignArea = $resquery["DesignArea"];
                            $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                            $shahrcityname = $resquery["shahrcityname"];
                            $designername = $resquery["designername"];
							$producercoTitle = $resquery["producercoTitle"];
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                            
                            if ($resquery["winstate"]=='برنده پیشنهاد') $cl='4AA143'; 
                            else if ($resquery["winstate"]=='عدم انتخاب') $cl='ff5500'; else $cl='888888';  
                       
                            
                            $fstr1="";
                            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/proposep/';
                            $handler = opendir($directory);
                            while ($file = readdir($handler)) 
                            {
                                // if file isn't this directory or its parent, add it to the results
                                if ($file != "." && $file != "..") 
                                {
                                    
                                    $linearray = explode('_',$file);
                                    $ID=$linearray[0];
                                    if ($ID==$resquery["producerapprequestID"] )
                                        $fstr1="<a target='blank' href='../../upfolder/proposep/$file' ><img style = 'width: 30%;' src='../img/full_page.png' title='اسکن پیشنهاد' ></a>";
                                    
                                    
                                }
                            }
                            
                            
                        //PE40tonaj 
					//	$report=1;
					if ($report==1) {$type="display:none";$types="";} else {$types="display:none";$type="";}
										
?>                       


                        <tr>
                        
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $producercoTitle; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["BankCode"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $ApplicantName; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style=" <?php echo $hide;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';">
							<?php echo str_replace(' ', '&nbsp;', "<br>".$resquery['creditsourcetitle']);
                            if ($Freestate>0) echo '<br>(اسناد خزانه اسلامی)'; ?>
							</td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $shahrcityname; ?></td>
                            
							<td class="f10_font<?php echo $b; ?>"  style="<?php echo $type;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["producerapprequestID"].strtotime($resquery["producerapprequestSaveDate"]); ?></td>
							
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo ($resquery["price"]/10); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="<?php echo $type;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo gregorian_to_jalali($resquery["producerapprequestSaveDate"]);; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="<?php echo $type;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery['validday'].$fstr1; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="<?php echo $type;?>; color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php
                            
                            $date = new DateTime($resquery["producerapprequestSaveDate"]);
                            $date->modify('+'.$resquery['validday'].' day');
                             echo gregorian_to_jalali($date->format('Y-m-d')); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["winstate"] ?></td>
	
	
						    <td class="f10_font<?php echo $b; ?>"  style="<?php echo $types;?>;color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["DesignArea"] ?></td>
						    <td class="f10_font<?php echo $b; ?>"  style="<?php echo $types;?>;color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["PE32tonaj"] ?></td>
						    <td class="f10_font<?php echo $b; ?>"  style="<?php echo $types;?>;color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["PE40tonaj"] ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="<?php echo $types;?>;color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["PE80tonaj"] ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="<?php echo $types;?>;color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["PE100tonaj"] ?></td>
							
							
                        <?php
                        
                        if ($resquery["winstate"]=='برگزار نشده')
                        {
                            if (gregorian_to_jalali($date->format('Y-m-d'))<gregorian_to_jalali(date('Y-m-d')))
                            echo "
                        <td  style=$type; $htype ><a 
                            href=\"../appinvestigation/allapplicantrequestdetail_extend.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery['producerapprequestID'].rand(10000,99999)."\"
                            onClick=\"return confirm('مطمئن هستید که  پیشنهاد تمدید شود ؟');\"
                            > <img style = 'width: 20px;' src='../img/up.png' title='تمدید اعتبار'> </a></td>";
                        
                        
                        
                            if ($resquery["proposestatep"]==0 && (gregorian_to_jalali($date->format('Y-m-d'))<gregorian_to_jalali(date('Y-m-d'))))
                            echo "
                        <td  $htype ><a 
                            href=\"../appinvestigation/allapplicantrequestdetail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery['producerapprequestID'].rand(10000,99999)."\"
                            onClick=\"return confirm('مطمئن هستید که  پیشنهاد حذف شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a></td>";
                            
                        }
                        
                            
                        echo "</tr>";    
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
