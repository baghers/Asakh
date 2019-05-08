<?php 
/*
//appinvestigation/allapplicantrequestdetail2.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantrequestp.php

*/

include('../includes/connect.php'); 
include('../includes/check_user.php'); 
include('../includes/functions.php');

if ($login_Permission_granted==0) header("Location: ../login.php");
//$login_ostanId شناسه استان
//تابع دریافت مشخصات پیکربندی
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId); 
//$Permissionvals['smallapplicantsize'] حداکثر مساحت پروژه کوچک   				
//Permissionvals['percentapplicantsize'] درصد افزایش اندازه پروژه
$smalha=$Permissionvals['smallapplicantsize']*$Permissionvals['percentapplicantsize']/100+$Permissionvals['smallapplicantsize'];
    
if ($_POST)//درصورتی که دکمه سابمیت کلیک شده باشد
{   
    $Description=$_POST['Description'];//شرح  
    $type=$_POST['type'];//نوع  
     
    if ($_POST['tempsubmitexcept'])//در صورتی که دکمه مجوز مدیر آب و خاک کلیک شده بود
    {
        /*
            producerapprequest جدول پیشنهاد قیمت های لوله طرح
            ApplicantMasterID شناسه طرح
            ecept اعطاء مجوز
        */
        $query = " update producerapprequest set ecept=1 WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' and state=1;";
                    
        try 
            {		
                mysql_query($query);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
        if ($_POST['prjtypeid']>0) header("Location: allapplicantrequestws.php"); else header("Location: allapplicantrequestp.php");
    }
    else if ($_POST['tempsubmit1'])//دکمه ارجاع به مدیر آبیاری/برگشت به وضعیت پیشنهاد قیمت
    {
        if($_POST['proposestatep']>0)//برگشت به وضعیت پیشنهاد قیمت
        {
            /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                proposestatep وضعیت پیشنهاد قیمت
                ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
                ApplicantMasterID شناسه طرح
            */
            $query = " update applicantmaster set 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
            SaveDate = '" . date('Y-m-d') . "', 
            ClerkID = '" . $login_userid . "',
            proposestatep=0 WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";   
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
                proposestatep وضعیت پیشنهاد قیمت
                ADate تاریخ شروع پیشنهاد قیمت یا ارجاعات
                ApplicantMasterID شناسه طرح
            */
            $query = " update applicantmaster set 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
            SaveDate = '" . date('Y-m-d') . "', 
            ClerkID = '" . $login_userid . "',
            proposestatep=1,ADate='".date('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";
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
        
       
        if ($_POST['prjtypeid']>0) header("Location: allapplicantrequestws.php"); else header("Location: allapplicantrequestp.php");
    }
    else     if ($_POST['tempsubmit2'])//دکمه ارجاع به ناظر عالی
    {
        /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            proposestatep وضعیت پیشنهاد قیمت
            ADate2 تاریخ شروع پیشنهاد قیمت یا ارجاعات
            ApplicantMasterID شناسه طرح
        */ 
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestatep=2,ADate2='".date('Y-m-d')."' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' ;";    
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
	
		   else     if ($_POST['submitedit'])//دکمه ویرایش
    {
			$querys =  " Select * from producerapprequest
							WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' and state=1;";
			$result = mysql_query($querys);
			$row = mysql_fetch_assoc($result);
			$producerapprequestID=$row['producerapprequestID'];
		    $linearray = explode('_',$row['Description']);//توضیحات طرح
			$Description=$linearray[0].' _  '.$_POST['Description'].'  _  '.$login_fullname.':اصلاحیه مورخ '.gregorian_to_jalali(date('Y-m-d')).' '.'PE32='.$row['PE32app'].'PE40='.$row['PE40app'].'PE80='.$row['PE80app'].'PE100='.$row['PE100app'];//توضیحات  
		
			$querytr =  " update producerapprequest set  PE32app='".str_replace(',', '', $_POST['PE32'])."',PE40app='".str_replace(',', '', $_POST['PE40'])."',
			PE80app='".str_replace(',', '', $_POST['PE80'])."',PE100app='".str_replace(',', '', $_POST['PE100'])."', 
			Description='$Description' WHERE producerapprequestID = '$producerapprequestID' ;";
		
  			try 
                  {		
                    mysql_query($querytr);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }
			if ($_POST['prjtypeid']>0) header("Location: allapplicantrequestws.php"); else header("Location: allapplicantrequestp.php");
 
	}

	
    else if (($_POST['rgs']>0) && ($login_userid>0) )//ثبت منتخب پیشنهاد
    {
        $i=0;
        $Description=$_POST['Description'];//توضیحات    
        if ($_POST['Datebandp']>0)//پروژه به صورت ترک تشریفات بود
        $Windate=$_POST['Datebandp'];//تاریخ انتخاب تولیدکننده
        else
        $Windate=date('Y-m-d');//تاریخ انتخاب تولیدکننده
        $querytr="";
        while (isset($_POST['producerapprequestID'.++$i]))//پیمایش کلیه پیشنهادات ارسال شده
        {
            if (!($_POST["showm"]>0) && ($_POST['errors'.$i]!=''))//در صورتی که خطا دار بود تادریافت مجوز مدیر آب و خاک تاریخ انتخاب تولیدکننده خالی می باشد
            $Windatestr='';
            else $Windatestr=",Windate='$Windate'";//تاریخ انتخاب
            
            $producerapprequestID=$_POST['producerapprequestID'.$i];//شناسه ردیف پیشنهاد قیمت
            $errors=$_POST['errors'.$i];//پیغام های عدم صلاحیت
            /*
                producerapprequest جدول پیشنهاد قیمت های طرح    
                ordering ترتیب مبلغ پیشنهادی
                state برنده شدن یا نشدن
                errors پیغام های عدم صلاحیت
                producerapprequestID شناسه ردیف پیشنهاد قیمت
            */
            $query= " update producerapprequest set ordering='$i',state=0,errors='$errors' $Windatestr WHERE producerapprequestID ='$producerapprequestID' ;";
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
        
           if ($_POST["DesignArea"]>$_POST['smallapplicantsize'])
                $appsize=1;//کوچک بودن پروژه
           else $appsize=0;
           
        
        
         /*
                producerapprequest جدول پیشنهاد قیمت های طرح    
                PE32app مبلغ تایید شده برای لوله های 32
                PE40app مبلغ تایید شده برای لوله های 40
                PE80app مبلغ تایید شده برای لوله های 80
                PE100app مبلغ تایید شده برای لوله های 100
                state برنده شدن یا نشدن
                producerapprequestID شناسه ردیف پیشنهاد قیمت
                appsize کوچک بودن پروژه
                ClerkID کاربر
                Description شرح
            */
        $querytr.=  " update producerapprequest set  PE32app='".str_replace(',', '', $_POST['PE32'])."',PE40app='".str_replace(',', '', $_POST['PE40'])."',
        PE80app='".str_replace(',', '', $_POST['PE80'])."',PE100app='".str_replace(',', '', $_POST['PE100'])."',appsize='$appsize', 
        state=1,ClerkID='$login_userid',Description='$Description',transportless=1 WHERE producerapprequestID ='$_POST[rgs]' ;";
        //$result = mysql_query($query);
         mysql_query($querytr);
         
         $querytr="";
        //print "sa".$querytr;
        //exit;
        
        /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            proposestatep وضعیت پیشنهاد قیمت
            surveyDate تاریخ شروع پیشنهاد قیمت یا ارجاعات
            ApplicantMasterID شناسه طرح
        */        
        $querytr.= " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestatep=3,surveyDate='' WHERE ApplicantMasterID='$_POST[ApplicantMasterID]';";
        //$result = mysql_query($query);    
        
        
        //پرس و جوی استخراج اعتبار گواهی ارزش افزوده
        /*
        producers جدول تولید کنندگان
        valueaddedvalidate تاریخ اعتبار ارزش افزوده
        SaveDate تاریخ
        producersID شناسه تولید کننده
        producerapprequest جدول پیشنهادات
        producerapprequestID شناسه جدول پیشنهادات
        */
        $queryp = "SELECT valueaddedvalidate,producerapprequest.SaveDate,producerapprequest.producersID FROM producers 
        inner join producerapprequest on producerapprequest.producerapprequestID='$_POST[rgs]' and producerapprequest.producersID=producers.producersID";
           //$Description=$queryp; 
    	$result = mysql_query($queryp);
	    $row = mysql_fetch_assoc($result);
        $valueaddedvalidate=$row['valueaddedvalidate'];
        $selproducersID=$row['producersID'];
        $InvoiceDate=gregorian_to_jalali($row['SaveDate']) ;
        $taxless='0';//با ارزش افزوده
    	if (compelete_date($valueaddedvalidate)<=compelete_date($InvoiceDate))
            $taxless='1';//بدون ارزش افزوده
        
        /*
        invoicemaster جدول لیست لوازم
        SaveTime زمان
        InvoiceDate تاریخ
        taxless بدون ارزش افزوده
        producersID شناسه تولید کننده
        proposable ارسال شده به پیشنهاد قیمت
        */
         mysql_query(" update invoicemaster set SaveTime='".date('Y-m-d H:i:s')."',InvoiceDate='".gregorian_to_jalali(date('Y-m-d'))."',taxless='$taxless',ProducersID='$selproducersID' 
        WHERE ApplicantMasterID='$_POST[ApplicantMasterID]' 
        and proposable=1;");

        /*
        invoicedetailid شناسه ریز لوازم
        ToolsMarksID شناسه کالا و مارک
        invoicemasterid شناسه عنوان پیش فاکتور
        ApplicantMasterID شناسه طرح
        proposable ارسال شده به پیشنهاد قیمت
        invoicemaster جدول لیست لوازم
        producersID شناسه تولید کننده
        gadget3ID شناسه کالای سطح 3
        */         
        
        $query = " select  invoicedetailid,invoicedetail.ToolsMarksID,toolsmarksnew.ToolsMarksID ToolsMarksIDn from  invoicedetail 
        inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid and invoicemaster.ApplicantMasterID='$_POST[ApplicantMasterID]'
        and invoicemaster.proposable=1
        inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
        inner join toolsmarks toolsmarksnew on toolsmarksnew.ProducersID='$selproducersID'
        and toolsmarksnew.gadget3ID=toolsmarks.gadget3ID";
        //print $query;
        $result = mysql_query($query);
        while($row = mysql_fetch_assoc($result))//پیمایش ریز لوازمی که به پیشنهاد قیمت رفته اند
        {
            //بروز رسانی لوازم با مارک تولید کننده انتخابی
            $cursql1=" update invoicedetail set ToolsMarksID='$row[ToolsMarksIDn]' where invoicedetailid='$row[invoicedetailid]';";
            mysql_query($cursql1);
            $querytr.=$cursql1; 
        }
        
        //اجرای تراکنش    
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
        
        //بازگشت به صفات ارجاعی پس از پایان عملیات        
         if ($_POST['prjtypeid']>0) header("Location: allapplicantrequestws.php"); else header("Location: allapplicantrequestp.php");
        //header("Location: allapplicantrequest.php");
        //errors$rown        
    }

}
else
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $uid=$_GET["uid"];
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
   $type=$linearray[1];//نوع
     
    $showm=is_numeric($_GET["showm"]) ? intval($_GET["showm"]) : 0;//نمایش تمام پیشنهادات صلاحیت دار و ندار

    
    /*
    $login_RolesID=="13" نقش مدیرآبیاری تحت فشار
    $login_RolesID=="14" نقش ناظر عالی
    
    */
    if ( ($login_RolesID=="13" || $login_RolesID=="14" )&& (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestp.php'))>0) )
    {    
        /*
         applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            proposestatep وضعیت پیشنهاد قیمت
            surveyDate تاریخ شروع پیشنهاد قیمت یا ارجاعات
            ApplicantMasterID شناسه طرح
        */
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        surveyDate='".date('Y-m-d')."' 
        where ApplicantMasterID='$ApplicantMasterID' and ifnull(surveyDate,'')='' and proposestatep=2 ;";
         
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

    //بررسی اینکه تولیدکننده انتخاب شده یا خیر
    $querys = "SELECT count(*) cnt  from producerapprequest where state=1 and 
    case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID else -producerapprequest.ApplicantMasterID end ='$ApplicantMasterID' ";
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
          /*
    applicantmaster جدول مشخصات طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    DesignArea مساحت طرح
    ApplicantMasterID شناسه طرح
    CPI نام کاربر
    DVFS نام خانوادگی کاربر
    ClerkID کاربر ثبت
    producerapprequest جدول پیشنهاد قیمت های طرح    
    state برنده شدن یا نشدن
    clerk جدول کاربران
    */
    
    $querys = "SELECT ApplicantName,applicantmaster.proposestatep,shahr.cityname shahrcityname,ApplicantFName,DesignArea,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID,Datebandp,
    operatorcoid from applicantmaster 
    left outer join (select case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID else -producerapprequest.ApplicantMasterID end ApplicantMasterID,ProducersID,ClerkID from producerapprequest where state=1) reqwin on 
    reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID
    left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID

    inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
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
    $ApplicantName="$rows[ApplicantFName] $rows[ApplicantName] - $rows[DesignArea]  ";//عنوان پروژه
    $Appname="$rows[ApplicantFName] $rows[ApplicantName]  ";//عنوان متقاضی
    $cityname=$rows['shahrcityname'];// نام شهر
    $proposestatep=$rows['proposestatep'];//وضعیت ارجاعات پیشنهاد قیمت
  		$encrypted_string=$rows['CPI'];//نام کاربر
		$encryption_key="!@#$8^&*";//کلید
		$decrypted_string="";//نام کاربر دیکود شده
		for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
				$decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $encrypted_string=$rows['DVFS'];//نام خانوادگی کاربر
    $encryption_key="!@#$8^&*";
    $decrypted_string.=" ";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    
    
    $spname=$decrypted_string;//نام کاربر
    $spDesignArea="$rows[DesignArea]";//مساحت
    $Datebandp="$rows[Datebandp]";//تاریخ ترک تشریفات
    

                  
                        
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
    
    */
    $sql = "SELECT distinct producerapprequest.*,producers.*,producers.rank corank,producers.Title producersTitle,concat(producers.CompanyAddress,' -تلفن: ',
    producers.Phone2,' - ',producers.bossmobile,' -مدیر عامل: ',producers.BossName,' ',producers.bosslname) CoAddress
    ,producerapprequest.SaveDate producerapprequestSaveDate,producerapprequest.validday,
    producerapprequest.state  producerapprequeststate,producerapprequestID,boardvalidationdate,copermisionvalidate,joinyear,
    errors
    ,producerapprequest.PE32 pPE32,producerapprequest.PE40 pPE40,producerapprequest.PE80 pPE80,producerapprequest.PE100 pPE100
    ,producerapprequest.PE32app ,producerapprequest.PE40app ,producerapprequest.PE80app ,producerapprequest.PE100app 
    ,round((producerapprequest.PE80*PE80tonaj+producerapprequest.PE100*PE100tonaj)/1000000,1) pipval,prjtype.title prjtypetitle
    ,ifnull(applicantmasterdetail.prjtypeid,0) prjtypeid
    ,case ifnull(applicantmasterdetail.prjtypeid,0) when 0 then producers.guaranteepayval else guarantee.guaranteepayval end prjguaranteepayval
    ,case ifnull(applicantmasterdetail.prjtypeid,0) when 0 then producers.guaranteeExpireDate else guarantee.guaranteeExpireDate end prjguaranteeExpireDate
    FROM producerapprequest 
    inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end='$ApplicantMasterID'
    
    left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
    
    inner join producers on producers.ProducersID=producerapprequest.ProducersID
    left outer join guarantee on producers.producersid=CoID and CoType=1 and guarantee.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
    
    where case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID 
    else -producerapprequest.ApplicantMasterID end='$ApplicantMasterID' 
    ORDER BY ifnull(producerapprequest.ordering,0),
    (producerapprequest.PE32*PE32tonaj+producerapprequest.PE40*PE40tonaj+producerapprequest.PE80*PE80tonaj+producerapprequest.PE100*PE100tonaj),
    producers.rank,producers.emtiaz desc,producerapprequest.savetime   ;";
    
    //print $sql;
    try 
        {		
            $result = mysql_query($sql); 
        $resquery = mysql_fetch_assoc($result);
        $prjtypeid=$resquery['prjtypeid'];//نوع پروژه
        mysql_data_seek( $result, 0 );
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 
        
    

    
    if (!$done)
    {
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
        $query = "
			select count(*) _value,invoicemaster.ProducersID _key from invoicemaster 
			
			inner join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
			where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemasterpipe  
			on invoicemasterpipe.ApplicantMasterID=invoicemaster.ApplicantMasterID and invoicemasterpipe.invoicemasterid=invoicemaster.invoicemasterid
			
		inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid and applicantmaster.DesignArea>10 
		left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID 
		inner join (select applicantmasterid from producerapprequest where state=1) producerapprequest 
		on producerapprequest.applicantmasterid=invoicemaster.applicantmasterid 
        inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid
        and ifnull(applicantmasterdetail.prjtypeid,0)=0
		where ifnull(invoicemaster.proposable,0)=1 and ifnull(invoicetiming.ApproveA,'')=''
        and ifnull(applicantmaster.applicantstatesID,0) not in (34)
        and ifnull(InvoiceMasterIDmaster,0)=0 and ifnull(pricenotinrep,0)=0 and ifnull(costnotinrep,0)=0 
		group by invoicemaster.ProducersID";
			$invoicenotdelivered = get_key_value_from_query_into_array($query);
        /*
        producerapprequest جدول پیشنهادات قیمت
        PE32tonaj تناژ لوله 32
        PE40tonaj تناژ لوله 40
        PE80tonaj تناژ لوله 880 
        PE100tonaj تناژ لوله 100
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        invoicemaster جدول لیست لوازم
        producersID شناسه تولید کننده
        proposable ارسال شده به پیشنهاد قیمت
        invoicemasterid شناسه جدول لیست لوازم
        state وضعیت انتخابی
        ApproveA تایید ارسال لوازم
        applicantstatesID شناسه وضعیت طرح
        */			
			$query = "
			select round((sum(ifnull(PE32tonaj,0)+ifnull(PE40tonaj,0)+ifnull(PE80tonaj,0)+ifnull(PE100tonaj,0))/1000),1) _value,ProducersID _key from 
			(select distinct producerapprequest.* from
			producerapprequest
			inner join applicantmaster on applicantmaster.applicantmasterid=producerapprequest.applicantmasterid
			where producerapprequest.state=1
			and producerapprequest.applicantmasterid not in (select invoicemaster.applicantmasterid from invoicetiming 
			inner join invoicemaster on invoicemaster.InvoiceMasterID=invoicetiming.InvoiceMasterID
			where ifnull(invoicetiming.ApproveA,'')<>'')
            and ifnull(applicantmaster.applicantstatesID,0) not in (34)
            
            ) v1
			
			group by ProducersID";
            $tonajkeyvalues = get_key_value_from_query_into_array($query);
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
            */
			
			  $query = "
			select ROUND(sum(gadget3.UnitsCoef2*invoicedetail.Number)/1000,1) val
			from invoicedetail 
			inner join invoicemaster on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid  and ifnull(invoicemaster.proposable,0)=1 and ifnull(invoicemaster.InvoiceMasterIDmaster,0)=0 and ifnull(invoicemaster.pricenotinrep,0)=0 and ifnull(invoicemaster.costnotinrep,0)=0 
			and invoicemaster.applicantmasterid='$ApplicantMasterID'
			inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID 
			inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID 
			left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID and ifnull(invoicetiming.ApproveA,'')='' ";
			$result2 = mysql_query($query); 
			$resquery2 = mysql_fetch_assoc($result2);
			$projtonajval=$resquery2["val"];//مقدار تناژ
			
    }
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیشنهاد قیمت لوله</title>

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

	
<script type="text/javascript">
function showdiv(id)
{
//alert('ss');
var elem = document.getElementById(id + '_content');
if(elem.style.display=='none')
{
elem.style.display='';
}
else
{
elem.style.display='none';
}
}
</script>	
	
	
    <script>

function tempvalchange()
{
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
	 else if  (document.getElementById("submitedit"))
    {
        if (document.getElementById('submitedit').value!="" )
            return confirm('آیا مطمئن هستید؟ '); 
    }   
    
    else
    {
        
        
        if (
		!(document.getElementById('selp').value>'') ||
		!(document.getElementById('PE32').value.replace(',', '')>60000) ||
		!(document.getElementById('PE40').value.replace(',', '')>60000) ||
        !(document.getElementById('PE80').value.replace(',', '')>60000) ||
        !(document.getElementById('PE100').value.replace(',', '')>60000))
            {
                alert('لطفا پس از انتخاب منتخب پیشنهاد، مبلغ تایید شده انواع لوله ها را وارد نمایید');return false;
            }
        
                  
          
          return confirm(' آیا از انتخاب شرکت '+document.getElementById('selp').value+' مطمئن هستید؟ ');  
        
        
    }
   


    
	
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function calc()
    {
    }
    
    function changeradio(PE32,PE40,PE80,PE100,producertitle)
    {
        document.getElementById('selp').value=producertitle;
        //document.getElementById('PE32').value=numberWithCommas(PE32);
        //document.getElementById('PE40').value=numberWithCommas(PE40);
        //document.getElementById('PE80').value=numberWithCommas(PE80);
        //document.getElementById('PE100').value=numberWithCommas(PE100);
    }  

	function selectpage(){
	   var vshowm=0;
	   if (document.getElementById('showm').checked) vshowm=1;
       
	   window.location.href ='?uid=' +document.getElementById('uid').value
        + '&showm=' + vshowm;
        
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
            
            <form action="allapplicantrequestdetail2.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                
                
                
                <table align='center' class="page" border='1'>              
                    <tr>    
                            <th colspan='19' class="f10_fontb"
                            >کلیه اطلاعات پیشنهاد قیمت در اختیار مدیریت آب و خاک می باشد، لطفاً جهت هرگونه اعلام نظر و تغییرات با مدیر آب و خاک تماس گرفته شود. </th>
                            
                            
                            <?php
                            if ($done==1)
                            if ($login_RolesID==18 || $login_designerCO==1)
                            echo "
                        <td><a 
                            href=\"allapplicantrequestdetail_discard.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999)."\"
                            onClick=\"return confirm('مطمئن هستید که برنده پیشنهاد لغو شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a></td>";
                            else
                            echo "
                        <td><a href='' > <img style = 'width: 25px;' src='../img/delete.png' title='لغو برنده پیشنهاد توسط مدیر آب و خاک'> </a></td>";
                            
                             ?>
                        </tr>
				  <tr> 
                  
                            <td colspan="19"
                            <span class="f14_fontb" >لیست پیشنهاد قیمت لوله های طرح <?php 
                            if ($prjtypeid==0)
                                $ApplicantName.=' هکتار ';
                            else if ($prjtypeid==1)
                                $ApplicantName.=' متر ';
                            $ApplicantName.=' شهرستان '.$cityname; echo $ApplicantName; ?> </span>  
                         
				<?php	$ID = $ApplicantMasterID."_11_0_0_".$row['applicantstatesID']."_1";
                //print "<br>".$ID;
                //exit;
                            echo "
							<a  target='_blank' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/full_page.png' title=' لوله ها '></a></td>";
                     $showm=1;
					?>		
                            <td class="data"><input name="showm" type="checkbox" id="showm" <?php if($login_RolesID=="1" || $login_RolesID=="18" || $login_RolesID=="13" || $login_RolesID=="14" || $login_RolesID=="31" || $login_RolesID=="32")  echo "onChange=\"selectpage()\""; ?>  value='<?php echo $showm."'"; ?>' <?php if ($showm>0) echo "checked"; ?> /></td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                           <td class="data"><input name="type" type="hidden" class="textbox" id="type"  value="<?php echo $type; ?>"  /></td>
                           <td class="data"><input name="prjtypeid" type="hidden" class="textbox" id="prjtypeid"  value="<?php echo $prjtypeid; ?>"  /></td>
                            <td class="data"><input name="smallapplicantsize" type="hidden" class="textbox" id="smallapplicantsize"  value="<?php echo $Permissionvals['smallapplicantsize']; ?>"  /></td>
                            <td class="data"><input name="DesignArea" type="hidden" class="textbox" id="DesignArea"  value="<?php echo $spDesignArea; ?>"  /></td>
                            <td class="data"><input name="Datebandp" type="hidden" class="textbox" id="Datebandp"  value="<?php echo $Datebandp; ?>"  /></td>
                            
                            
                            
				   </tr>
                     
                     <?php
                      if ($resquery['isbandp']>0) 
					  $isbandhide='display:none';
                      if ($done>0 || ($resquery['proposestatep']!=0))
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >پیشنهاددهنده</th>
                            <th colspan=\"4\" class=\"f10_fontb\" >مجوز </th>
							<th colspan=\"7\" class=\"f10_fontb\" >پيشنهاد قيمت  </th>
                            <th class=\"f10_fontb\"  style=$isbandhide >دلايل</th>
                            <th class=\"f10_fontb\" >اسکن فرم</th>
                        </tr>
		
					 <tr>
                          <th ></th>
                          <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >فروشنده</th>
                            <th class=\"f10_fontb\" >رتبه</th>
                            <th class=\"f10_fontb\" >زمان تحویل هر تن</th>
                            <th class=\"f10_fontb\" >حجم تناژ پیش فاکتور های همزمان</th>
                            <th class=\"f10_fontb\" >ضمانتنامه</th>
                            <th class=\"f10_fontb\" >PE32 (ریال)</th>
                            <th class=\"f10_fontb\" >PE40 (ریال)</th>
                            <th class=\"f10_fontb\" >PE80 (ریال)</th>
                            <th class=\"f10_fontb\" >PE100 (ریال)</th>
                            <th class=\"f10_fontb\" >م برآوردی* پیشنهادی</th>
                            <th class=\"f10_fontb\" >ت صدور <br> ت اعتبار</th>
                            <th class=\"f10_fontb\" >آدرس</th>
                            <th class=\"f10_fontb\" style=$isbandhide>عدم صلاحیت</th>
                            <th class=\"f10_fontb\" >پيشنهاد قيمت</th>
                        </tr>";
                     else
                        echo "<tr>
                            <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >پیشنهاددهنده</th>
                            <th colspan=\"5\" class=\"f10_fontb\" >مجوز (مطابق با فرایند پیشنهادی انتخاب تولیدکننذه لوله پلی اتیلن)</th>
                            <th colspan=\"2\" class=\"f10_fontb\" > <label class='no-print'>پروژه در دست اجرا</label> </th>
							<th colspan=\"7\" class=\"f10_fontb\" >پيشنهاد قيمت  </th>
                            <th class=\"f10_fontb\" style=$isbandhide >دلايل</th>
                            <th class=\"f10_fontb\" >اسکن فرم</th>
                        </tr>
					 <tr>
                          <th ></th>
                            <th class=\"f10_fontb\"></th>
                            <th class=\"f10_fontb\" >فروشنده</th>
                            <th class=\"f10_fontb\" >رتبه</th>
                            <th class=\"f10_fontb\" >زمان تحویل هر تن</th>
                            <th class=\"f10_fontb\" >تعداد پیش فاکتور همزمان</th>
                            <th class=\"f10_fontb\" >حجم تناژ پیش فاکتور های همزمان</th>
                            <th class=\"f10_fontb\" >ضمانتنامه</th>
                            <th class=\"f10_fontb\" ><label class='no-print'>تعداد >10</label></th>
                            <th class=\"f10_fontb\" ><label class='no-print'>تن</label></th>
                            <th class=\"f10_fontb\" >PE32</th>
                            <th class=\"f10_fontb\" >PE40</th>
                            <th class=\"f10_fontb\" >PE80</th>
                            <th class=\"f10_fontb\" >PE100</th>
                            <th class=\"f10_fontb\" >م برآوردی* پیشنهادی</th>
                            <th class=\"f10_fontb\" >ت صدور <br> ت اعتبار</th>
                            <th class=\"f10_fontb\" >آدرس</th>
                            <th class=\"f10_fontb\" style=$isbandhide>عدم صلاحیت</th>
                            <th class=\"f10_fontb\" >پيشنهاد قيمت</th>
                        </tr>";
                     
                     
                     
                      ?>
                        
<?php
                     
                    
                    
                    
                    if ($Datebandp>0)
                        $datetoprint=gregorian_to_jalali($Datebandp);
                    else
                        $datetoprint=gregorian_to_jalali(date('Y-m-d'));
        
                    $br="<br><font color='gray'>";
                    $pipeproposerror=$Permissionvals['pipeproposerror'];  
                    $pipeproposetonaj=$Permissionvals['pipeproposetonaj'];  
                    $proposecoless=$Permissionvals['proposecoless'];
                    $proposepermissionless=$Permissionvals['proposepermissionless'];
                    $pdeliverytonday=$Permissionvals['pdeliverytonday'];
                    $maxpe32pipeprice=$Permissionvals['maxpe32pipeprice'];
                    $maxpe40pipeprice=$Permissionvals['maxpe40pipeprice'];
                    $maxpe80pipeprice=$Permissionvals['maxpe80pipeprice'];
                    $maxpe100pipeprice=$Permissionvals['maxpe100pipeprice'];
                       
                    $p1Zemanat=$Permissionvals['p1Zemanat']*10;      
                    $p2Zemanat=$Permissionvals['p2Zemanat']*10;      
                    $p3Zemanat=$Permissionvals['p3Zemanat']*10;      
                    $p4Zemanat=$Permissionvals['p4Zemanat']*10;      
                    $p5Zemanat=$Permissionvals['p5Zemanat']*10; 
                    $p1Zpishhamzamanvol=$Permissionvals['p1Zpishhamzamanvol'];
                    $p2Zpishhamzamanvol=$Permissionvals['p2Zpishhamzamanvol'];
                    $p3Zpishhamzamanvol=$Permissionvals['p3Zpishhamzamanvol'];
                    $p4Zpishhamzamanvol=$Permissionvals['p4Zpishhamzamanvol'];
                    $p5Zpishhamzamanvol=$Permissionvals['p5Zpishhamzamanvol'];
                    $p1Zpishhamzaman=$Permissionvals['p1Zpishhamzaman'];
                    $p2Zpishhamzaman=$Permissionvals['p2Zpishhamzaman'];
                    $p3Zpishhamzaman=$Permissionvals['p3Zpishhamzaman'];
                    $p4Zpishhamzaman=$Permissionvals['p4Zpishhamzaman'];
                    $p5Zpishhamzaman=$Permissionvals['p5Zpishhamzaman'];
                    $trysnt=0;
                    $errless=2;
                    while (1==1)
                    {
                        $Total=0;
                        $rown=0;
                        $Description="بنام خدا :
با توجه به درخواست متقاضی ...................... ثبت شده به شماره ............ مورخ .......... ایشان شرکت ................... را به عنوان تولیدکننده لوله های پلی اتیلن خود انتخاب و معرفی می نماید.";

                        $errorlesscnt=0;
                        mysql_data_seek( $result, 0 );
                        $rowsstr="";
                        while($resquery = mysql_fetch_assoc($result))
                        {
                            $prjtypeid=$resquery['prjtypeid'];
                            if ($resquery["corank"]==1) $corankstr= "A" ; 
                                else if ($resquery["corank"]==2) $corankstr= "A*" ;
                                else if ($resquery["corank"]==3) $corankstr= "B" ;
                                else if ($resquery["corank"]==4) $corankstr= "B*" ;
                                else if ($resquery["corank"]==5) $corankstr= "C" ;
                                else $corankstr= "" ;
                            
                            if ($resquery["corank"]==1) $pcurZemanat=$p1Zemanat; 
                                else if ($resquery["corank"]==2) $pcurZemanat=$p2Zemanat;
                                else if ($resquery["corank"]==3) $pcurZemanat=$p3Zemanat;
                                else if ($resquery["corank"]==4) $pcurZemanat=$p4Zemanat;
                                else if ($resquery["corank"]==5) $pcurZemanat=$p5Zemanat; 
                            
                            if ($resquery["corank"]==1) $pcurZpishhamzamanvol=$p1Zpishhamzamanvol; 
                                else if ($resquery["corank"]==2) $pcurZpishhamzamanvol=$p2Zpishhamzamanvol;
                                else if ($resquery["corank"]==3) $pcurZpishhamzamanvol=$p3Zpishhamzamanvol;
                                else if ($resquery["corank"]==4) $pcurZpishhamzamanvol=$p4Zpishhamzamanvol;
                                else if ($resquery["corank"]==5) $pcurZpishhamzamanvol=$p5Zpishhamzamanvol;   
                                
                            if ($resquery["corank"]==1) $pcurZpishhamzaman=$p1Zpishhamzaman; 
                                else if ($resquery["corank"]==2) $pcurZpishhamzaman=$p2Zpishhamzaman;
                                else if ($resquery["corank"]==3) $pcurZpishhamzaman=$p3Zpishhamzaman;
                                else if ($resquery["corank"]==4) $pcurZpishhamzaman=$p4Zpishhamzaman;
                                else if ($resquery["corank"]==5) $pcurZpishhamzaman=$p5Zpishhamzaman;  
                            
                            $pcurZpishhamzaman+=$trysnt;
                            $pcurZpishhamzamanvolprint=$pcurZpishhamzamanvol;
                            $pcurZpishhamzamanvol=round((1+($trysnt/5))*$pcurZpishhamzamanvol);
                          
							$smalpipeprj=0;
						  if ($resquery["price"]<2100 && $prjtypeid==0) $smalpipeprj=1;
                            //print "<br> ".( ((1+($trysnt/10)))*$pcurZpishhamzamanvol );
                            
                            if ($resquery['state']>0)
                            if ($resquery['Description']!='')
							{
							 $linearray = explode('_',$resquery['Description']);
							 $Description=$linearray[0].' '.$linearray[1];
                             if ($type<>6) $Description=$resquery['Description'];
							}
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
                                    if ($ID==$resquery['producerapprequestID'] )
                                        $fstr1="<a target='blank' href='../../upfolder/proposep/$file' ><img style = 'width: 25px;' src='../img/full_page.png' title='اسکن پیشنهاد' ></a>";           
                                }
                            }
                            $errors="";
                            $date = new DateTime($resquery["producerapprequestSaveDate"]);
                            $date->modify('+'.$resquery['validday'].' day');
                            if ($pipeproposerror==0)
                            {    
                                if (gregorian_to_jalali($date->format('Y-m-d'))<gregorian_to_jalali(date('Y-m-d')))
                                    $errorsb.="<br>*اعتبار پیشفاکتور (".gregorian_to_jalali($date->format('Y-m-d')).") منقضی شده است";
                                 
                            }   
                            if ($resquery["prjguaranteeExpireDate"]<gregorian_to_jalali(date('Y-m-d')))
                                    $errors.="<br>*تاریخ اعتبار ضمانتنامه بانکی (".$resquery["prjguaranteeExpireDate"].") منقضی شده است";
                            if ($resquery["prjguaranteepayval"]<$pcurZemanat)
                                    $errors.="<br>*مقدار ضمانت نامه (".$resquery["prjguaranteepayval"].")کافی نمی باشد";
                               
                                
                            $resquery["tonajval"]=0;
                           foreach ($tonajkeyvalues as $key => $value)
                           {
                            if ($resquery["ProducersID"]==$key)
                                $resquery["tonajval"]=$value;     
                           } 
                                
                           // if ($prjtypeid==0)
							if ($smalpipeprj==0)  							
                            if (($pipeproposetonaj==0) && (($resquery["tonajval"]+$resquery["projecthektar92"]+$projtonajval)>$pcurZpishhamzamanvol))
                            {
                                if ($login_RolesID==1)
                                $errors.="<br>*تناز پیش فاکتور همزمان (".($resquery["tonajval"]+$resquery["projecthektar92"]+$projtonajval).") بیشتر از حد مجاز است";
                                else
                                $errors.="<br>*تناز پیش فاکتور همزمان  بیشتر از حد مجاز است";
                            }
                                
                             
           					if ($proposecoless==0)
                            if (compelete_date($resquery["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d')))
                                $errors.="<br>*تاریخ اعتبار هیئت مدیره منقضی شده است.";
    						
    						if ($proposepermissionless==0)
							{
    				        if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
                                $errors.="<br>*تاریخ مجوز شرکت منقضی شده است.";
                            if (($resquery["corank"]<1)||($resquery["corank"]>5)  ) 
                                $errors.="<br>*رتبه شرکت نامعتبر می باشد";
							}
                           $resquery["invoicenotdeliveredcnt"]=0;
                           foreach ($invoicenotdelivered as $key => $value)
                                {
                                    if ($resquery["ProducersID"]==$key)
                                        $resquery["invoicenotdeliveredcnt"]=$value;     
                                } 
                            
							if ($prjtypeid==0 && $smalpipeprj==0)    
                            if (($resquery["invoicenotdeliveredcnt"]+$resquery["projectcount92"])>=$pcurZpishhamzaman) 
                            {
                                if ($login_RolesID==1)
                                $errors.="<br>*تعداد پیش فاکتور همزمان (".($resquery["invoicenotdeliveredcnt"]+$resquery["projectcount92"]).") بیشتر از حد مجاز است";
                                else
                                $errors.="<br>*تعداد پیش فاکتور همزمان  بیشتر از حد مجاز است";
                                
                            }     
                                 
                                
                            if ($done>0 && $resquery["Windate"]=='')
                                continue;
                            if ($done>0)
                                $errors=$resquery['errors'] ;    
                            if ($resquery['isbandp']>0)
                                $errors='';
                           if (strlen($errors)>0 && !($Datebandp>0)) $cl='ff0000'; else $cl='000000';    
                            if (!($showm>0) && !($done>0) && strlen($errors)>0 && !($Datebandp>0)) 
                                $htype='style="display:none"'; 
    						else 
    							$htype='';
                                    
                            $rown++;
                            if ($rown%2==1) 
                                $b=''; else $b='b';
                            
                            $rowsstr.= "<tr $htype><td class='data' $htype><input name='producerapprequestID$rown' type='hidden' class='textbox' id='producerapprequestID$rown' 
                                  value='$resquery[producerapprequestID]'  /></td>";
                            if ($done>0)
                            {
                                if ($resquery["producerapprequeststate"]>0) 
                                    $rowsstr.= "<td class='f10_font$b'  colspan='1' $htype ><img style = 'width:30px;' src='../img/accept.png' title=''></td>";
                                    else $rowsstr.= "<td class='f10_font$b'  colspan='1' $htype></td>"; 
                            }
                            else $rowsstr.= "<td class='f10_font$b'  colspan='1' $htype ><input onChange='changeradio(\"$resquery[PE32]\",\"$resquery[PE40]\",\"$resquery[PE80]\",\"$resquery[PE100]\",\"$resquery[producersTitle]\")'  type='radio' name='rgs' value='$resquery[producerapprequestID]'  /></td>";
                            
                            $rowsstr.= "<td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[producersTitle]</td>
                                  <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"> $corankstr</td>
                                  <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"> $pdeliverytonday</td>"; 
                                  
                            if (!($done>0) && ($resquery['proposestatep']==0) )
                            {
                                if ($prjtypeid==0)
                                $rowsstr.= "<td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$pcurZpishhamzaman</td>";
                                else $rowsstr.= "<td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"></td>";
                            }
                            if ($prjtypeid==0)
                            $rowsstr.= "<td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$pcurZpishhamzamanvolprint</td>
                                  <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$pcurZemanat</td>"; 
                            else $rowsstr.= "<td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"></td>
                            <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"></td>";    
                            if (!($done>0) && ($resquery['proposestatep']==0) )
                                $rowsstr.= "<td  $htype  class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"> <label class='no-print'>$resquery[invoicenotdeliveredcnt]</label></td>
                                <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><label class='no-print'>$resquery[tonajval]</label>  </td>
                                ";
                            $rowsstr.= " <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".number_format($resquery["PE32"])."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".number_format($resquery["PE40"])."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".number_format($resquery["PE80"])."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".number_format($resquery["PE100"])."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".($resquery["costprice"]/10).$br.($resquery["price"]/10)."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".(gregorian_to_jalali($resquery["producerapprequestSaveDate"])."<br>".gregorian_to_jalali($date->format('Y-m-d')))."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".$resquery["CoAddress"]."</td>
                                    <td $htype class=\"f10_font$b\"  style=$isbandhide \"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".substr($errors,4)."</td>
                                    <td $htype class=\"f10_font$b\"  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$fstr1</td>
                                    ";
                            if ($done!=1)
                                if ($login_RolesID==18 || $login_RolesID==27 || $login_designerCO==1)
                                    $rowsstr.= "<td  $htype ><a href=\"allapplicantrequestdetail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery['producerapprequestID'].rand(10000,99999)."\"onClick=\"return confirm('مطمئن هستید که  پیشنهاد حذف شود ؟');\"> <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a></td>";
                                else
                                    $rowsstr.= "<td  $htype ><a href='' > <img style = 'width: 25px;' src='../img/delete.png' title='حذف  پیشنهاد توسط مدیر آب و خاک'> </a></td>";
                            $rowsstr.= "<td $htype class='data'><input name='errors$rown' type='hidden' class='textbox' id='errors$rown'  value='$errors'  /></td></tr>";
                            $prjtypetitle=$resquery['prjtypetitle'];
                            
                            if ($resquery['state']==1)
                            { 
                                $selp= $resquery["producersTitle"];
                                $PE32= number_format($resquery["PE32app"]);
                                $PE40= number_format($resquery["PE40app"]);
                                $PE80= number_format($resquery["PE80app"]);
                                $PE100= number_format($resquery["PE100app"]);
                                $datetoprint=gregorian_to_jalali($resquery["Windate"]);
                                $winerrors=$resquery['errors'] ;
                            }
                            else if ($rown==1)
                            {
                                $PE32= number_format($resquery["PE32"]);
                                $PE40= number_format($resquery["PE40"]);
                                $PE80= number_format($resquery["PE80"]);
                                $PE100= number_format($resquery["PE100"]);
                                
                            } 
                            if ($errors=="") $errorlesscnt++;
                            
                        }
                        //print $rown."-".$errorlesscnt."-".$trysnt."-".$errless."<br>";
						
                        if ($rown==$errorlesscnt) break;
						if ($errorlesscnt>=$errless) break;
						
                        if ($done>0) break;
                        $trysnt++;
                        if ($trysnt>=10) break;
                    }
                    echo "$rowsstr<tr>";
					//echo $trysnt;
					$darsad=100*$trysnt/5;
                    if ($trysnt>0)
                    echo "<td colspan='18'>$darsad%&nbsp;-  تعداد و حجم تناژ پیش فاکتورهای همزمان طبق صورتجلسه پیشنهاد قیمت لوله های پلی اتیلن مدیریت آب و خاک افزایش پیدا نمود</td>";
                    echo "<td colspan='18'>&nbsp;</td> </tr>";
					if ($smalpipeprj==1)
				         echo "<td colspan='18'>&nbsp;-  تعداد و حجم تناژ پیش فاکتورهای همزمان طبق صورتجلسه پیشنهاد قیمت لوله های پلی اتیلن مدیریت آب و خاک افزایش پیدا نمود</td>";
						echo "<td colspan='18'>&nbsp;</td> </tr>
					
                    <tr>
				    <td colspan='18' class='data'><input name='below3' type='hidden' class='textbox' id='below3'  value='0'  /></td>
					   
					   
			     </tr>	";
            

					if ($done>0 && strlen($winerrors)>0 && $login_RolesID==18)	  
			         echo "<tr> 
                        
                          <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  
                            value='$ApplicantMasterID'  /></td>
                            <td colspan='17'>
                          <input name='tempsubmitexcept' type='submit' class='button' id='tempsubmitexcept' value='اعطاء مجوز مدیر آب و خاک'/></td>
                          
                        </tr> ";
                        
                        //print "sa".$proposestatep;
                        
					if ($login_RolesID==18 && $proposestatep<=2)
                    {
                        if ($proposestatep==1 || $proposestatep==2)
                            $btntitle="برگشت به وضعیت دریافت پیشنهاد";
                        else if ($proposestatep==0) 
                            $btntitle="ارجاع به مدیر ".$prjtypetitle;    
                        echo "<tr> 
                        
                          <td colspan='18'><input name='tempsubmit1' type='submit' class='button' id='tempsubmit1' value='$btntitle'/></td>
                          
                        </tr> ";
                        
                        
                        
                    }
                    else if (($login_RolesID==13 || $login_RolesID==32) && $proposestatep==1)
                    {
                        if ($login_RolesID==13)
                        $btntitle="ارجاع به ناظر عالی";
                        else if ($login_RolesID==32)
                        $btntitle="ارجاع به کارشناس آب رسانی";
                         
                    echo "<tr> 
                    
                      <td colspan='18'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
                      
                    </tr> ";
                    }   
				    else if ($login_RolesID=='17' && $spDesignArea<$smalha) 
                    {
					 if ($proposestatep==1 || $proposestatep==2){$btntitle="برگشت به وضعیت دریافت پیشنهاد";
							echo "<tr> 
                              <td colspan='18'><input name='tempsubmit1' type='submit' class='button' id='tempsubmit1' value='$btntitle'/></td>
							  </tr><tr> <td colspan='18'>&nbsp</td></tr> ";}
						 if ($proposestatep!=2 && $proposestatep!=3) {$btntitle="اتمام پیشنهاد قیمت";
							echo "<tr> 
							<td colspan='18'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
							</tr> ";}
				    }   
				
                    if ($proposestatep==1 && ($login_RolesID==18 || $login_RolesID==1))
                        {
                            $btntitle="ارجاع به ناظر عالی";
                            echo "<tr> 
                            <td colspan='17'></td>
                              <td colspan='1'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' value='$btntitle'/></td>
                              
                            </tr> ";
                        }
					
					
                    if ((!($done>0) && $login_RolesID==18) || $proposestatep==1 || $proposestatep==0)
                    echo "";
                    else                 
                       echo " 
                       <tr>
                       <td colspan='3'  class='label'></td>
                       <td colspan='2'  class='label'>PE32</td>
                       <td colspan='1'  class='label'>PE40</td>
                       <td colspan='1'  class='label'>PE80</td>
                       <td colspan='3'  class='label'>PE100</td>
                       </tr>
                	
                              <tr>
                                <td colspan='3'  class='label'>مبلغ تایید شده:</td> 
                              <td colspan='2' class='data'><div id='divPE32'>
                            <input  name='PE32' onKeyUp=\"convert('PE32')\" onChange='valchange();'  type='text' class='textbox' id='PE32' value='$PE32' 
							 style='background-color:#ffff00;width: 60px' /></div>
                            </td>
                            <td colspan='1' class='data'><div id='divPE40'>
                            <input  name='PE40' onKeyUp=\"convert('PE40')\" onChange='valchange();'  type='text' class='textbox' id='PE40'  value='$PE40' 
							 style='background-color:#ffff00;width: 60px' /></div>
                            </td> 
                            <td colspan='1' class='data'><div id='divPE80'>
                            <input  name='PE80' onKeyUp=\"convert('PE80')\" onChange='valchange();' type='text' class='textbox' id='PE80'  value='$PE80' 
							 style='background-color:#ffff00;width: 60px' /></div>
                            </td> 
                            <td colspan='3' class='data'><div id='divPE100'>
                            <input  name='PE100' onKeyUp=\"convert('PE100')\" onChange='valchange();' type='text' class='textbox' id='PE100'  value='$PE100' 
							 style='background-color:#ffff00;width: 60px' /></div>
                            </td>
                            <input   name='selp' type='hidden' class='textbox'  id='selp'  style='width:160px'  readonly
                        value='$selp' />
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
                                  <td colspan='16' width='80%' class='data'>
                                  <textarea id='Description' name='Description' rows='5' cols='140'>$Description</textarea></td>
                                  </tr>
                               <tr>
                               <td colspan='16'  class='label' style = \"font-size:14;font-weight: bold;font-family:'B Nazanin';\">
                                  * ترتیب لیست تا هنگام تکمیل ظرفیت (پیشفاکتورهای همزمان) اولویت با رتبه های بالاتر خواهد بود
								  <br>
                                  * ترتیب لیست تا هنگام تعیین نحوه برآورد قیمت لوله های نرم بر اساس حجم لوله های سخت خواهد بود
                                  <br>
                                  * تعداد کل پیشنهادها ".mysql_num_rows($result)." مورد می باشد.
                                  </td>	
                                  ";
                   
                   
                                  echo " <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  
                            value='$ApplicantMasterID'  />
                            <input name='proposestatep' type='hidden' class='textbox' id='proposestatep'  
                            value='$proposestatep'  /></td>";
                      if (!($done>0))
                        {
                  
					 
					 $matn="";
                             if ($login_RolesID!=18 && $proposestatep==2)
							 {
							print " 
							<td colspan='3'><input name='submit' type='submit' class='button' id='submit' value='ثبت منتخب پیشنهاد' />
							 </td>
							 <td colspan='8' ><td colspan='9' style = \"text-align:left;font-size:20;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
							 <a href='javascript:void();' onclick='showdiv(id);' id='test'>
							 كاربر: $login_fullname
							 </a>
							 </td></tr>
							 ";
							 
?>						
 <tr>  <td colspan='18' >   
<div id="test_content" style="display:none;" class='f13_font'>

<font face="B Nazanin" >
<?php 

if ($prjtypeid==0)
print "
<br>&nbsp;
                         مدیریت محترم آب و خاک و امور فنی و مهندسی      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                         <br>با سلام و احترام
                         <br>بدینوسیله اینجانب/شرکت « $Appname » متقاضی اجرای طرح آبياري قطره‌اي/باراني/كم فشار در سطح $spDesignArea هکتار  پس از بررسی های لازم تولید کننده لوازم پروژه خود را شرکت ....................................
                         انتخاب نموده ام و شرکت ............................................. متعهد گردیده است که لوازم اینجانب را با پایین ترین مبلغ تایید شده در جدول فوق مطابق پیشفاکتور پیوست ارسال نماید. 
                         خواهشمند است اقدام لازم مبذول فرمایند.
                         <br>&nbsp;
                         <br>&nbsp;
                         تبصره: متقاضی متعهد می گردد از تاریخ $datetoprint حداکثر ظرف مدت 2 روز نسبت به مشخص نمودن تولیدکننده لوازم خود از بین شرکتهای فوق با حداقل قیمت پیشنهادی اقدام و نتیجه را کتبا به مدیریت آب و خاک و امور فنی مهندسی اطلاع رسانی نماید.
                         <br>&nbsp;
                         <br>&nbsp;
                         <br>&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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

";
else if ($prjtypeid==1)
print "
<br>&nbsp;
                         مدیریت محترم آب و خاک و امور فنی و مهندسی      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                         <br>با سلام و احترام
                         <br>بدینوسیله اینجانب/شرکت « $Appname » متقاضی اجرای طرح آبرسانی به طول $spDesignArea متر  پس از بررسی های لازم تولید کننده لوازم پروژه خود را شرکت ....................................
                         انتخاب نموده ام و شرکت ............................................. متعهد گردیده است که لوازم اینجانب را با پایین ترین مبلغ تایید شده در جدول فوق مطابق پیشفاکتور پیوست ارسال نماید. 
                         خواهشمند است اقدام لازم مبذول فرمایند.
                         <br>&nbsp;
                         <br>&nbsp;
                         تبصره: متقاضی متعهد می گردد از تاریخ $datetoprint حداکثر ظرف مدت 2 روز نسبت به مشخص نمودن تولیدکننده لوازم خود از بین شرکتهای فوق با حداقل قیمت پیشنهادی اقدام و نتیجه را کتبا به مدیریت آب و خاک و امور فنی مهندسی اطلاع رسانی نماید.
                         <br>&nbsp;
                         <br>&nbsp;
                         <br>&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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

";
 ?>

</font>


</div>
			 
  </td>  </tr>   
  
<?php
							 }
                          
                        }
                         else 
						{ 
						if ($type==6)
							print " 
							<td colspan='2'><input name='submitedit' type='submit' class='button' id='submitedit' value='اصلاحیه  ' />
							 </td>
					";
			 
						 echo 
                         "
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='1'><td colspan='18' class='f14_fontb'>برنده پیشنهاد قیمت برای طرح $ApplicantName شرکت $selp می باشد.</td> </tr>
                     <tr >
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                         <tr><td colspan='18'>&nbsp;</td> </tr>
                           
                              <td colspan='1'></td> 
                                    <td  colspan='6' > امضاء&nbspمتقاضی <br>$Appname</td> 
                                    <td  colspan='6' > امضاء&nbspکارشناس-ناظر عالی <br>$spname</td> 
                               
                                </tr>
                                
                                <tr >  
                              <td colspan='1'></td>
                              
                                    <td colspan='6' >&nbsp  </td> 
                                    <td colspan='8' > </td> 
                                    <td colspan='4' >&nbsp</td> 
                                    
                                </tr>    
				    
                   ";
                  
				  }
                         
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
