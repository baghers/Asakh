<?php 

/*

//insert/summaryinvoice.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantstatesoplist.php

*/

include('../includes/connect.php');
include('../includes/check_user.php'); 
include('../includes/functions.php');



/////////////////////////////////////////////////
$Rmargin='5px';//حاشیه راست
$Lmargin='450px';//حاشیه چپ
 $Pwidth=100;//عرض باکس های بخش های مختلف اطلاعات
 $Rwidth=50;//عرض باکس لیست لوازم
 $c1margin='10px';//عرض ستون دوم قبل از ردیف
 $P1title=70;//عرض توضیحات فهرست بها
 //$P2title=50;
 //$P3title=10;
 $Titlefmargin='150px';//عرض مجموع فهرست بها
///////////////////////////////////////////////                        
$showm=is_numeric($_GET["showm"]) ? intval($_GET["showm"]) : 0;//نمایش تمام صفحات و اطلاعات
$showotherz=is_numeric($_GET["showotherz"]) ? intval($_GET["showotherz"]) : 0;//نمایش سایر هزینه ها
$subprj=is_numeric($_GET["subprj"]) ? intval($_GET["subprj"]):0;//شناسه زیر پروژه ها
if ($subprj>0)
{
    $subprjcondition=" and appsubprjID='$subprj'";//شرط محدود کننده زیر پروژه
}
$uid=$_GET["uid"];//متغیر موجود در آدرس
if ($_POST)
{
    $showm=$_POST['showm'];//نمایش تمام صفحات و اطلاعات
    $showotherz=$_POST['showotherz'];//نمایش سایر هزینه ها
    
}


if (!($login_RolesID>0)) $login_RolesID=0;//در صورتی که نال بود صفر می شود
$register=0;//عملیات ثبت انجام شد یا خیر
if ($_POST)//در صورتی که کلید سابمیت کلیک شده باشد
    {
        
        if (!($login_userid>0)) //در صورتی که کاربر لاگین نکرده باشد یا جلسه کاری به پایان رسیده شده باشد
        {
            header("Location: ../login.php");
            exit;
        }  
        if ($_POST['ApplicantstatesID']>0)//در صورتی که شناسه طرح بزرگتر باشد
        {
            /*
            ApplicantMasterID شناسه طرح
            producerapprequest جدول پیشنهادات قیمت لوله
            state منتخب مشخص می باشد 
            
            در این پرس و جو بررسی می شود که آیا پروژه در حال انتخاب تولید کننده لوله پلی اتیلن می باشد یا خیر
            در صورتی که پروژه در حال انتخاب تولید کننده لوله پلی اتیلن باشد امکان تغییر اطلاعات طرح وجود ندارد
            */
            $query ="select  ApplicantMasterID from producerapprequest 
                    where ApplicantMasterID='$_POST[ApplicantMasterID]' and ApplicantMasterID not in 
            		(select ApplicantMasterID from producerapprequest where state=1) ";
            
            try 
              {		
                $result = mysql_query($query);
            	$row = mysql_fetch_assoc($result);
                if ($row['ApplicantMasterID']>0)
                {
                    echo "پروژه در حال انتخاب تولید کننده لوله پلی اتیلن می باشد و امکان تغییر وضعیت وجود ندارد";
                    exit;
                }
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
        }
        /*
        $_POST['ApplicantstatesID']==46 وضعیت مدیریت جهاد شهرستان به استان
        $login_RolesID ناظر مقیم
        $_POST['prjtypeid']==1 پروژه آبرسانی
        */
        if ($_POST['ApplicantstatesID']==46 && $login_RolesID==17 && $_POST['prjtypeid']==1)
        {
            /*
            invoicemaster جدول لیست لوازم و پیش فاکتورها
            invoicedetail جدول ریز لوازم و پیش فاکتور ها
            invoicemasterid شناسه پیش فاکتور و لیست لوازم
            ApplicantMasterID شناسه طرح
            
            پرس و جوی زیر بررسی می کند که آیا پیش فاکتور ها یا لیست لوازم ثبت شده یا خیر
            */
            
            $query = "SELECT ValueStr FROM supervisorcoderrquirement WHERE KeyStr ='watersuplydefaultinvoicedate' ";
       	    $result = mysql_query($query);
       	    $row = mysql_fetch_assoc($result);
            $InvoiceDate=$row['ValueStr'];
                
            
            $query = "
            select count(*) cnt,max(InvoiceDate) InvoiceDate from invoicemaster 
            inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
            where  ApplicantMasterID='$_POST[ApplicantMasterID]' ";
            //echo $query;
            try 
              {		
                $result = mysql_query($query);
                $row = mysql_fetch_assoc($result);
                $cnt=$row['cnt'];  
        		if ($cnt<=0)
                {
                    print "لطفا قبل از تغییر وضغیت لیست لوازم/پیش فاکتور طرح را ثبت نمایید.";
                    if ($login_RolesID!=1)
                    exit;
                }
                
                if ($row['InvoiceDate']!=$InvoiceDate)
                {
                    echo "تاریخ پیش فاکتور لوله نا معتبر می باشد <br> باید $InvoiceDate باشد";exit;
                }
                
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
        }
        
        //در این بخش می خواهیم با توجه به شرکت مشاور، قرارداد نظارت بر اجرای آنرا ثبت کنیم
        if (substr($login_CityId,0,2)=='19' && $_POST['ApplicantstatesID']>0)//استان خراسان و شناسه طر بزرگ
        {
            if ($login_RolesID==10 && $_POST['operatorcoid']>0 )//نظارت بر اجرا
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                    try 
                      {
                        /*
                        applicantcontracts جدول قرارداد های پروژه ها
                        ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                        designercocontractID شناسه قرارداد مشاور
                        designercocontract جدول قرارداد مشاوران
                        contracttypeID شناسه قرارداد
                        prjtypeid نوع پروژه
                        */
                        //حذف قرارداد ثبت شده
                        mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                        and designercocontractID in (select designercocontractID from designercocontract where 
                        designercocontract.contracttypeID='1' and 
                                    designercocontract.prjtypeid='$_POST[prjtypeid]' ); ");
                        //ثبت قرارداد جدید
                        mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                        VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                        date('Y-m-d H:i:s') . "','$login_userid')  ");
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                    }
                }
                else
                {
                    echo "شرکت محترم ناظر، لطفا قرارداد نظارت این پروژه را انتخاب نمایید.";
                    exit;
                }
            }  
            if ($login_RolesID==9 )//قرارداد مطالعات
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    //print $_POST['designercocontractIDold'].$_POST['designercocontractID'];exit;
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                        try 
                        {
                            /*
                            applicantcontracts جدول قرارداد های پروژه ها
                            ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                            designercocontractID شناسه قرارداد مشاور
                            designercocontract جدول قرارداد مشاوران
                            contracttypeID شناسه قرارداد
                            prjtypeid نوع پروژه
                            */
                            //حذف قرارداد ثبت شده
                            mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                             and designercocontractID in (select designercocontractID from designercocontract where 
                             designercocontract.contracttypeID='4' and 
                                            designercocontract.prjtypeid='$_POST[prjtypeid]' ); ");
                            //ثبت قرارداد جدید
                            mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                            VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                            date('Y-m-d H:i:s') . "','$login_userid')  ");
                        }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
          
                    }
                    
                     
            
                }
                else
                {
                    echo "شرکت محترم  لطفا قرارداد  این پروژه را انتخاب نمایید.";
                    exit;
                }
            } 
            
            if ($login_RolesID==11 )//قرارداد بازبینی
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    //print $_POST['designercocontractIDold'].'s'.$_POST['designercocontractID'];exit;
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                        try 
                        {
                            /*
                            applicantcontracts جدول قرارداد های پروژه ها
                            ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                            designercocontractID شناسه قرارداد مشاور
                            designercocontract جدول قرارداد مشاوران
                            contracttypeID شناسه قرارداد
                            prjtypeid نوع پروژه
                            */
                            //حذف قرارداد ثبت شده
                            mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                            and designercocontractID in (select designercocontractID from designercocontract where 
                            designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$_POST[prjtypeid]' ");
                            //ثبت قرارداد جدید
                            mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                            VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                            date('Y-m-d H:i:s') . "','$login_userid')  ");
                        }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
          
                    }
                }
                else
                {
                    echo "شرکت محترم  لطفا قرارداد  این پروژه را انتخاب نمایید.";
                    exit;
                }
            }  
        }
        
        
        
        //در صورتی که کاربر لاگین شده پیمانکار، مشاور طراح و مدیریت آب و خاک باشد ثبت بودن کد رهگیری چک می شود
		if (in_array($login_RolesID, array(5,2,9)))
		{
            /*
            applicantmaster جدول مشخصات طرح
            BankCode کد رهگیری
            ApplicantstatesID شناسه طرح
            TotlainvoiceValues جمع کل لوازم طرح
            LastFehrestbaha جمع کل فهرست بهای طرح
            */
            $query = "SELECT BankCode,ApplicantstatesID,TotlainvoiceValues,LastFehrestbaha FROM applicantmaster where  ApplicantMasterID='$_POST[ApplicantMasterID]' ";
            try 
            {
                $result = mysql_query($query);
        	   $row = mysql_fetch_assoc($result);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            $curbankcode=$row['BankCode'];//کد رهگیری
            $TotlainvoiceValuesfo=$row['TotlainvoiceValues'];//جمع کل لوازم طرح
            $LastFehrestbahafo=$row['LastFehrestbaha'];//جمع کل فهرست بهای طرح
            $currApplicantstatesID=$row['ApplicantstatesID'];//شناسه طرح
    		if (strlen($row['BankCode'])==0)//کد رهگیری
            {
                print_r($_POST);
                print "<br>";
                print $query."<br>";
                print $curbankcode."<br>";
                print "لطفا قبل از تغییر وضعیت کد رهگیری طرح را ثبت نمایید.";
                exit;
            }   
        }    
        
        //print $_POST['transportless'].'sa';
        
        $type=$_POST['typestr'];//نوع نمایش صفحه
        $_POST['transportless'] = $_POST['transportless'];//هزینه حمل لحاظ شود یا خیر
          $_POST['digitless'] = $_POST['digitless'];//مبلغ کل روند شود یا خیر
        $transportlessstr="";
        //print $_POST['transportless'].'sb';
        if (isset($_POST['transportless']))
        {
            
		if ($_POST['transportless']=='on' && $_POST['digitless']==1)	$transportless= 11;
		else if ($_POST['transportless']=='on')	$transportless= 1;
		else if ($_POST['digitless']==1)	$transportless= 10;  
        
        $transportlessstr="transportless = '" . str_replace(',','',$transportless) . "',";
        
            //print $transportlessstr;exit;;
        }
        
        
		
		
        $removeup="0";//هزینه های پیش بینی نشده نمایش داده شود یا خیر
		if ($_POST['removeup'])
            $removeup=$_POST['removeup'];
        else if ($_POST['removeupval'])
            $removeup=$_POST['removeupval'];
        
            
        /*
        $_POST['othercosts4text'] عنوان سایر هزینه های 4 ام طرح
        $_POST['othercosts3text'] عنوان سایر هزینه های 3 ام طرح
        $_POST['removetax'] حذف مالیات بر ارزش افزوده اجرای طرح
        $removeup هزینه های پیش بینی نشده نمایش داده شود یا خیر
        POST['totinvoicemanual'] جمع کل لوازم دستی توسط مدیر
        $_POST['totfehrestmanual'] جمع کل هزینه های اجرای طرح توسط مدیر
        
        */
        $_POST['othercosts4text']=$_POST['othercosts4text'].'_'.$_POST['othercosts3text']."_".$_POST['removetax']."_".$removeup."_".
        str_replace(',','',$_POST['totinvoicemanual'])
        ."_".str_replace(',','',$_POST['totfehrestmanual']);
		
        
        /*
        $type نوع نمایش صفحه
        $login_RolesID=1 مدیر پیگیری
        $login_RolesID==2 پیمانکار
        $login_RolesID==9 کاربر مشاور طراح
        $login_RolesID==10 مدیر مشاور طراح
        $login_RolesID==13 ناظر عالی
        $login_RolesID==14 مدیر آبیاری تحت فشار
        $login_RolesID==27 مدیر سامانه ها
        $login_RolesID==19 مدیر پرونده ها
        */
        if ( (($type==1 || $type==3) && ($login_RolesID==1 || $login_RolesID==2 ||$login_RolesID==9 
        ||$login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ||$login_RolesID==19)  ) )
        {
            /*
            $_POST['coef1'] ضریب اول اجرای طرح
            $_POST['coef2'] ضریب دوم اجرای طرح
            $_POST['coef3'] ضریب سوم اجرای طرح
            $_POST['coef4'] ضریب چهارم اجرای طرح
            $_POST['coef5'] ضریب پنجم اجرای طرح
            
            */
            $cstr="";
            if ($_POST['coef1']<=1.3 && $_POST['coef1']>0)
            $cstr.="coef1 = '" . str_replace(',','',$_POST['coef1']) . "',";
            if ($_POST['coef2']<=1.05 && $_POST['coef2']>0)
            $cstr.="coef2 = '" . str_replace(',','',$_POST['coef2']) . "',";
            
            //print "sa".$_POST['wincoef3']."_".$_POST['coef3'];exit;
            
            if ($_POST['coef3']<=$_POST['wincoef3'] && $_POST['wincoef3']>0 && $_POST['coef3']>0)
                $cstr.="coef3 = '" . str_replace(',','',$_POST['coef3']) . "',";
            else if ($_POST['wincoef3']>0) 
                $cstr.="coef3 = '" . str_replace(',','',$_POST['wincoef3']) . "',";
            
            if ($_POST['coef51']==0 && $_POST['hcoef5']>0)
            $cstr.="coef5 = '0',";
            else if ($_POST['hcoef5']!=$_POST['coef51'] && $_POST['coef51']<=1.2 && $_POST['coef51']>=1)
            $cstr.="coef5 = '" . str_replace(',','',$_POST['coef51']) . "',";
            
            /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID شناسه کاربر
            othercosts1 سایر هزین های 1
            othercosts2 سایر هزین های 2
            othercosts3 سایر هزین های 3
            othercosts4 سایر هزین های 4
            othercosts5 سایر هزین های 5
            othercosts4text عنوان سایر هزینه های 4
            */
            $query = "
        		UPDATE applicantmaster SET
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        		$cstr
        		othercosts1 = '" . str_replace(',','',$_POST['othercosts1']) . "', 
        		othercosts2 = '" . str_replace(',','',$_POST['othercosts2']) . "',
        		othercosts3 = '" . str_replace(',','',$_POST['othercosts3']) . "', 
        		othercosts4 = '" . str_replace(',','',$_POST['othercosts4']). "', 
        		$transportlessstr 
        		othercosts4text = '" .$_POST['othercosts4text']. "',  
        		othercosts5 = '" . str_replace(',','',$_POST['othercosts5']). "' 
        		WHERE ApplicantMasterID = " . $_POST['ApplicantMasterID'] . ";";
                
            try 
              {		
                mysql_query($query);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
      
                 
        }
       
        if ($type==1 || $type==5 )
        {
            /*
            پرس و جوی حذف سیستم های مختلف طرح
            designsystemgroupsdetail جدول سیستم های مختلف
            ApplicantMasterID شناسه طرح
            */
            $query = "delete from designsystemgroupsdetail
            where ApplicantMasterID='".$_POST['ApplicantMasterID']."';";
            try 
              {		
                mysql_query($query);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }
              
            
            $cnth=1;
            while(isset($_POST['DesignSystemGroupsID'.$cnth]))
            {
                //درج الگوی کشت و سیستم آبیاری در بخش جدول تفکیک سطح
                //$_POST['syshek'.$cnth] هکتار
                //$_POST['sysprice'.$cnth] مبلغ
                if ($_POST['syshek'.$cnth]>0 || $_POST['sysprice'.$cnth]>0)
                {
                    $query = "INSERT INTO designsystemgroupsdetail(ApplicantMasterID, DesignSystemGroupsID, hektar,price,yeild,SaveTime,SaveDate,ClerkID) VALUES(
                    '".$_POST['ApplicantMasterID']."','".$_POST['DesignSystemGroupsID'.$cnth]."','".$_POST['syshek'.$cnth]."','".
                    str_replace(',','',$_POST['sysprice'.$cnth])."', '" . $_POST['yeild'.$cnth]."','".
                    date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                    mysql_query($query);
                    
                }
                $cnth++;
            }
            
    
            $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح
            $coef4='0';//ضریب چهارم
            //پرس و جوی استخراج ضریب ارزش افزوده اجرای طر با توجه به سال طرح
            if (! $_POST['removetax'])//در صورتی که ارزش افزوده توسط مدیر حذف نشده باشد
            {
                if ($login_OperatorCoID>0 && ($_POST['issurat']!=1))//در صورتی که صورت وضعیت نباشد صورت وضعیت امکان تغییر ارزش افزوده ندارد
                {
                    $coef4='1';//ضریب ارزش افزوده
                    //valueaddedvalidate تاریخ ارزش افزوده  دارای اعتبار 
                    //operatorco جدول مشخصات پیمانکاران
                    //operatorcoid شناسه پیمانکار
                    $queryp = "SELECT valueaddedvalidate FROM operatorco WHERE operatorcoid ='$login_OperatorCoID' ";
                   	
                    try 
                      {		
                        $result = mysql_query($queryp);
                        $row = mysql_fetch_assoc($result);
                        $valueaddedvalidate=$row['valueaddedvalidate'];
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                      
    	            
                    //در صورتی که تاریخ اعتبار ارزش افزوده شرکت منقضی نشده باشد
        	        if (compelete_date($valueaddedvalidate)>=compelete_date(gregorian_to_jalali(date('Y-m-d'))))
                        {
                            
                            $InvoiceYear = substr(gregorian_to_jalali(date('Y-m-d')),0,4);//تاریخ فعلی
                            /*
                            taxpercent جدول نرخ های ارزش افزوده
                            year جدول شال ها
                            value مقدار ضریب ارزش افزوده
                            YearID شناسه سال طرح
                            */
                            $query = "SELECT taxpercent.value FROM taxpercent 
                            inner join year on year.YearID=taxpercent.YearID
                            where  year.Value = '" . $InvoiceYear."'" ;
                            //print $query;
                            try 
                              {		
                                $result = mysql_query($query);
                                $resquery = mysql_fetch_assoc($result);
                                $coef4 = 1+round($resquery['value']/100,2);
                              }
                              //catch exception
                              catch(Exception $e) 
                              {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                              }
                        }
                }   
            }
            
		
            /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID شناسه کاربر
            othercosts1 سایر هزین های 1
            othercosts2 سایر هزین های 2
            othercosts3 سایر هزین های 3
            othercosts4 سایر هزین های 4
            othercosts5 سایر هزین های 5
            othercosts4text عنوان سایر هزینه های 4
            */
		  if ($coef4>0 || $_POST['removetax'])
            $query = "
        		UPDATE applicantmaster SET
                LastTotal='" . str_replace(',','',$_POST['AllSumAll']) . "',
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
                
        		coef4 = '" . str_replace(',','',$coef4) . "',
        		othercosts1 = '" . str_replace(',','',$_POST['othercosts1']) . "', 
        		othercosts2 = '" . str_replace(',','',$_POST['othercosts2']) . "',
        		othercosts3 = '" . str_replace(',','',$_POST['othercosts3']) . "', 
        		othercosts4 = '" . str_replace(',','',$_POST['othercosts4']). "',  
        		othercosts4text = '" .$_POST['othercosts4text']. "',  
        		othercosts5 = '" . str_replace(',','',$_POST['othercosts5']). "' 
        		WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
           else
           /*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID شناسه کاربر
            othercosts1 سایر هزین های 1
            othercosts2 سایر هزین های 2
            othercosts3 سایر هزین های 3
            othercosts4 سایر هزین های 4
            othercosts5 سایر هزین های 5
            othercosts4text عنوان سایر هزینه های 4
            */
            $query = "
        		UPDATE applicantmaster SET
                LastTotal='" . str_replace(',','',$_POST['AllSumAll']) . "',
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
                
        		othercosts1 = '" . str_replace(',','',$_POST['othercosts1']) . "', 
        		othercosts2 = '" . str_replace(',','',$_POST['othercosts2']) . "',
        		othercosts3 = '" . str_replace(',','',$_POST['othercosts3']) . "', 
        		othercosts4 = '" . str_replace(',','',$_POST['othercosts4']). "',  
        		othercosts4text = '" .$_POST['othercosts4text']. "',  
        		othercosts5 = '" . str_replace(',','',$_POST['othercosts5']). "' 
        		WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
                
                 
         try 
            {		
                $result = mysql_query($query);
            }
         //catch exception
         catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
                              
                     
       
                $register=1; 
                if ($login_RolesID!=1)
                {
                    header("Location: ".$_POST['sref']);
                    exit(); 
                    
                }           
				
        }
        
        if ($type==3 || $login_RolesID==1)
        {
            //بررسی اینکه آیا در زمان انتخاب مجری شرکت صلایت های لازم را دارا بوده یا خیر
            //در صورتی که دارا نبوده باید تاییدیه مدیر آب و خاک را دریافت نماید
            /*
            operatorapprequest جدول پیشنهاد قیمت های طرح
            applicantmaster جدول مشخصات طرح
            BankCode کد رهگیری طرح
            ApplicantMasterID شناسه طرح
            state=1 انتخاب شدن پیشنهاد توسط کشاورز
            operatorcoID شناسه پیمانکار
            coef1 ضریب اول اجرای طرح
            coef2 ضریب دوم اجرای طرح
            coef3 ضریب سوم اجرای طرح
            coef4 ضریب چهارم اجرای طرح
            coef5 ضریب پنجم اجرای طرح
            ecept مقدار یک در صورتی که مدیر آب و خاک مجوز داده
            cityid شناسه شهر طرح
            */
            $query = "
            select distinct operatorapprequest.errors,operatorapprequest.ecept,operatorapprequest.ApplicantMasterID
            FROM operatorapprequest
            inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=operatorapprequest.ApplicantMasterID
            inner join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode 
            and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4) 
            
            where ifnull(applicantmasterop.operatorcoid,0)>0 
            and (applicantmasterop.coef1>operatorapprequest.coef1 or applicantmasterop.coef2>operatorapprequest.coef2 or 
            applicantmasterop.coef3>operatorapprequest.coef3 or (length(operatorapprequest.errors)>0 and ifnull(operatorapprequest.ecept,0)=0)) 
            and applicantmasterop.ApplicantMasterID='$_POST[ApplicantMasterID]'  and operatorapprequest.state=1 
            ";
            try 
            {		
                $result = mysql_query($query);
        		$row = mysql_fetch_assoc($result);
                if (strlen($row['errors'])>0 && $row['ecept']==0)
                 {
                    print "شرکت مجری محترم شما در زمان انتخاب به عنوان مجری این طرح صلاحیت لازم را دارا نبوده اید. لطفا جهت تایید با مدیر آب و خاک تماس حاصل نمایید.";
                    exit;
                }
                else if (strlen($row['ApplicantMasterID'])>0 && $login_OperatorCoID>0)
                {
                    print "شرکت مجری محترم. ضرایب بالاسری و تجهیز و پلوس مینوس طرح با مقادیرر پیشنهادی در پیشنهاد قیمت شما یکسان نمی باشد";
                    exit;
                }
            }
         //catch exception
         catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            
            
            if ($login_RolesID==2 && ($_POST['ApplicantstatesID']==31 || $_POST['ApplicantstatesID']==41 || $currApplicantstatesID==42|| $currApplicantstatesID==23))
            {
                /*
            پرس و جوی حذف سیستم های مختلف طرح
            designsystemgroupsdetail جدول سیستم های مختلف
            ApplicantMasterID شناسه طرح
            hektar هکتار
            price مبلغ
            yeild محصول
            DesignSystemGroupsID شناسه سیتم آبیاری
             applicantmaster جدول مشخصات طرح
            BankCode کد رهگیری طرح
            ApplicantMasterID شناسه طرح
            */
            
            $query = "delete from designsystemgroupsdetail where ApplicantMasterID='$_POST[ApplicantMasterID]';";$result = mysql_query($query);   
            $query = "insert into designsystemgroupsdetail (price,hektar,yeild,DesignSystemGroupsID,ApplicantMasterID)
                select price,hektar,yeild,DesignSystemGroupsID,ApplicantMasterID from (
                select price,hektar,yeild,DesignSystemGroupsID,'$_POST[ApplicantMasterID]' ApplicantMasterID from designsystemgroupsdetail
                where ApplicantMasterID=(select ApplicantMasterID FROM applicantmaster  
                where BankCode='$curbankcode' and designercoid>0
                and ApplicantMasterID in (select ApplicantMasterID from operatorapprequest where state=1
                )
                )
                ) view1;";
                try 
                  {		
                    $result = mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }
                  
                  
            }
            
            $checkrolsid = array("2","9");//نقش پیمانکار و مشاور طراح
            //بررسی اینکه مساحت کل طرح با جمع هکتارهای سیستم های آبیاری یکسان می باشد یا خیر
              /*
            designsystemgroupsdetail جدول سیستم های مختلف
            ApplicantMasterID شناسه طرح
            hektar هکتار
            price مبلغ
            */
            
            $errormsg="";
            $query = "select sum(price) price,sum(hektar) hektar from designsystemgroupsdetail
            where ApplicantMasterID='$_POST[ApplicantMasterID]';";
            
            try 
                  {		
                    $result = mysql_query($query);
                    $row = mysql_fetch_assoc($result);
                     if ($_POST['issurat']!=1 && $_POST['prjtypeid']==0)
                    if (abs($row['hektar']-$_POST['DesignArea'])>=0.1)
                        $errormsg.="مساحت کل طرح با جمع هکتارهای سیستم های آبیاری یکسان نمی باشد".($row['hektar'])." ".($_POST['DesignArea']);
                    if ((in_array($login_RolesID, $checkrolsid) || $type==1 || $type==5) && $errormsg!="")    
                    {
                        
                        print $errormsg;
                        exit;
                    }
                    
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                  
            
            if ($_POST['ApplicantstatesID']==24)//وضعیت دیافت پیشنهاد قیمت 
            {
                /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                ADate تاریخ شروع پیشنهاد قیمت
                ApplicantMasterID شناسه طرح
                */
                $query = "
        		UPDATE applicantmaster SET
                SaveTime = '" . date('Y-m-d H:i:s') . "',  
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
                ADate='".date('Y-m-d')."'
        		WHERE ApplicantMasterID = " . $_POST['ApplicantMasterID'] . ";";
                
                try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                  
                 
            }
            /*
            $login_RolesID==2 پیمانکار
            $login_RolesID==10 مدیر مشاور طراح
            $login_RolesID==13 مدیر آبیاری تحت فشار
            $login_RolesID==14 ناظر عالی
            $_POST['issurat']==1 طرح صورت وضعیت می باشد
            */
	       if (($login_RolesID==17 || $login_RolesID==1 || $login_RolesID==2 || $login_RolesID==10 ||$login_RolesID==13 ||$login_RolesID==14) && ($_POST['issurat']==1))
           {
                /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                letterno شماره نامه صندوق
                ApplicantMasterID شناسه طرح
                */
                $query = "
        		UPDATE applicantmaster SET
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        		letterno = '".$_POST['hatval']."_".$_POST['hattitle']."_".$_POST['hasanprice']."'
        		WHERE ApplicantMasterID = " . $_POST['ApplicantMasterID'] . ";";
                try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                
           }
           
           /*
           $login_RolesID=='16' نقش صندوق
           $login_RolesID=='7' نقش بانک
           $login_RolesID=='6' نقش کارشناس سرمایه گذاری
           */
            if ($login_RolesID=='16'  || $login_RolesID=='7' || $login_RolesID=='6')//صندوق
            {
                $updatestr="";//رشته بروز رسانی
                //$_POST["creditsourceID"] منبع تامین اعتبار
                if ($_POST["creditsourceID"]>0) $updatestr.=",creditsourceID = '$_POST[creditsourceID]'";
                else
                {
                    print "لطفا منبع تامین اعتبار را انتخاب نمایید";
                    exit;
                }
                $_POST['selfnotcashhelpval1']=str_replace(',', '', $_POST['selfnotcashhelpval1']);//خودیاری غیر نقدی 1
                $_POST['selfnotcashhelpval2']=str_replace(',', '', $_POST['selfnotcashhelpval2']);//خودیاری غیر نقدی 2
                $_POST['selfnotcashhelpval3']=str_replace(',', '', $_POST['selfnotcashhelpval3']);//خودیاری غیر نقدی 3
                $_POST['bela_sand']=str_replace(',', '', $_POST['bela_sand']);//مبلغ بلاعوض اعلامی صندوق
                
                $selfnotcashhelpdetail="$_POST[selfnotcashhelpval1]_$_POST[selfnotcashhelpdate1]_$_POST[selfnotcashhelpval2]_$_POST[selfnotcashhelpdate2]_$_POST[selfnotcashhelpval3]_$_POST[selfnotcashhelpdate3]";
                $selfnotcashhelpval=$_POST['selfnotcashhelpval1']+$_POST['selfnotcashhelpval2']+$_POST['selfnotcashhelpval3'];
                $selfnotcashhelpdate=$_POST['selfnotcashhelpdate1'];
                
                $bela_sandstr="";
                if ($_POST['bela_sand']>0) 
                $bela_sandstr=" belaavaz='$_POST[bela_sand]' ,";
                /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                ApplicantMasterID شناسه طرح
                selfcashhelpval مجموع خودیاری نقدی طرح های منبع اعتباری
                selfnotcashhelpdetail خودیاری غیر نقدی
                selfnotcashhelpval مبلغ خودیای غیر نقدی
                selfcashhelpdate تاریخ پرداخت خودیاری نقدی
                selfnotcashhelpdate تاریخ پرداخت خودیاری
                selfcashhelpdescription توضیح پرداخت خودیاری نقدی
                letterno شماره نامه صندوق
                letterdate تاریخ نامه صندوق
                sandoghcode کد صندوق
                proposestate وضعیت پیشنهاد قیمت اجرا
                ADate تاریخ شروع
                */
                $query = "
        		UPDATE applicantmaster SET
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        		selfcashhelpval = '" .  str_replace(',', '', $_POST['selfcashhelpval']) . "', 
                selfnotcashhelpdetail='$selfnotcashhelpdetail',
                selfnotcashhelpval = '$selfnotcashhelpval', 
  		        selfcashhelpdate = '" . $_POST['selfcashhelpdate'] . "', 
  		        selfnotcashhelpdate = '$selfnotcashhelpdate', 
  		        selfcashhelpdescription = '" . $_POST['selfcashhelpdescription'] . "' $updatestr, 
        		letterno = '$_POST[letterno]',
        		letterdate = '$_POST[letterdate]',
        		sandoghcode  = '$_POST[sandoghcode]',
        		ز  = '$_POST[sokuk]',
                $bela_sandstr
                proposestate=ifnull(proposestate,0),
                ADate='".date('Y-m-d')."'
        		WHERE ApplicantMasterID = " . $_POST['ApplicantMasterID'] . ";";
                 
                
                try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                  
                
                if ($_POST['ApplicantstatesID']==1)//انعقاد قرارداد آبرسانی
                {
                   
                    try 
                        {	
                            /*
                            invoicemaster جدول لیست لوازم و پیش فاکتورها
                            applicantmaster جدول مشخصات طرح
                            proposable شروع پیشنهاد قیمت
                            ApplicantMasterID شناسه طرح
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            bankcode کد رهگیری
                            RDate تاریخ شروع پیشنهاد قیمت لوله آبرسانی
                            */
                            mysql_query("update invoicemaster set proposable=1 where ApplicantMasterID='$_POST[ApplicantMasterID]'");
                            mysql_query("update applicantmaster set 
                            SaveTime = '" . date('Y-m-d H:i:s') . "', 
                            SaveDate = '" . date('Y-m-d') . "', 
                            ClerkID = '" . $login_userid . "',
                            bankcode='44-$_POST[ApplicantMasterID]-11',
                            RDate='".date('Y-m-d')."' where ApplicantMasterID='$_POST[ApplicantMasterID]'");
                        }
                        //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                  
                    
            
                }
				
				
				if ($_POST['nazerID'])//
                {
                    try 
                      {	
                        /*
                        applicantmasterdetail جدول ارتباطی طرح ها
                        nazerID شناسه مشاور ناظر طرح
                        ApplicantMasterID
                        
                        */
                        mysql_query("update applicantmasterdetail set nazerID='$_POST[nazerID]' where ApplicantMasterID='$_POST[ApplicantMasterID]'");
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                  
                    
				}
	                 
            }
            
            
                    
            /*
            $login_RolesID=='13' مدیر آبیاری تحت فشار
            $login_RolesID=='18' مدیر آب و خاک
            $_POST['prjtypeid']==1 پروژه آبرسانی
            $login_RolesID==9 نقش کاربر مهندسین مشاور
            */
            if ($login_RolesID=='13'  || $login_RolesID=='18' || ($_POST['prjtypeid']==1 &&  $login_RolesID==9 ))
            {
                if ($_POST['isbandp']=='on') $val=1; else $val=0;//طرح ترک تشریفات می باشد یا خیر 
                if ($_POST['Datebandp']>0)
                    $Datebandp=jalali_to_gregorian($_POST['Datebandp']);//تاریخ انجام ترک تشریفات
                
                /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                operatorcoIDbandp پیمانکار ترک تشریفات
                ApplicantMasterID شناسه طرح
                Datebandp تاریخ انجام ترک تشریفات
                isbandp طرح ترک تشریفات می باشد یا خیر
                */
                $query = "
        		UPDATE applicantmaster SET
                SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        		operatorcoIDbandp  = '$_POST[operatorcoIDbandp]',
        		Datebandp  = '$Datebandp',
                isbandp= '$val'
        		WHERE ApplicantMasterID = " . $_POST['ApplicantMasterID'] . ";";
                
                try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
      
                 
            }
           /*
           $login_RolesID=='16' نقش صندوق
           $login_RolesID=='7' نقش بانک
           $_POST['prjtypeid']==1 پروژه آبرسانی
           $login_RolesID==9 نقش کاربر مهندسین مشاور
           operatorcoIDbandp پیمانکار ترک تشریفات
           */ 
           
            if ($login_RolesID=='16'  || $login_RolesID=='7' || ($_POST['prjtypeid']==1 &&  $login_RolesID==9 ) )//صندوق
            {
                if (($_POST['prjtypeid']==1 &&  $login_RolesID==9 ))
                    $selectedoperatorcoIDbandp=$_POST['operatorcoIDbandp'];
                else
                    $selectedoperatorcoIDbandp=$_POST['selectedoperatorcoIDbandp'];
                
                 if ($selectedoperatorcoIDbandp>0 )
                 {
                    /*
                    operatorapprequest جدول پیشنهاد قیمت های طرح
                    costyear سال فهرست بها
                    costprice مبلغ برآورد فهرست بها
                    price مبلغ پیشنهادی
                    applicantmaster جدول مشخصات طرح
                    BankCode کد رهگیری طرح
                    ApplicantMasterID شناسه طرح
                    state=1 انتخاب شدن پیشنهاد توسط کشاورز
                    operatorcoID شناسه پیمانکار
                    coef1 ضریب 1
                    coef2 ضریب 2
                    coef3 ضریب 3
                    Windate تاریخ انتخاب
                    SaveTime زمان
                    SaveDate تاریخ
                    ClerkID کاربر
                    */
                     $sql="INSERT INTO operatorapprequest(costyear,operatorcoID,ApplicantMasterID, costprice,price
                     ,state,coef1,coef2,coef3,SaveTime,SaveDate,ClerkID,Windate)
                    values ('$fb','$selectedoperatorcoIDbandp','$_POST[ApplicantMasterID]','100','100',1,'1','1','1', '" . 
                    date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."','".date('Y-m-d')."');";
                    //print $sql;
                    try 
                      {		
                        mysql_query($sql);
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                  
                     
                 }       
            }
                
             
            $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح
            $Description=$_POST['Description'];//توضیحات
            $ApplicantstatesID=$_POST['ApplicantstatesID'];//شناسه وضعیت طرح 
            $SaveTime=date('Y-m-d H:i:s');//زمان
            $SaveDate=date('Y-m-d');//تاریخ
            $ClerkID=$login_userid;//کاربر
            
            
            /*
            در صورتی که فیلد
            ApplicantMasterIDmaster
            مقدار داشته باشد
            نشان میدهد که طرح در مرحله صورت وضعیت می باشد و پیش فاکتور آن نباید تغییر نماید
            */
                     
            try 
                {		
                    $query = "SELECT *  FROM applicantmaster  where ApplicantMasterIDmaster='$ApplicantMasterID'";
                    $result = mysql_query($query);
                    $row = mysql_fetch_assoc($result);
                    
                    if ($row['ApplicantMasterID']>0) 
                    {
                        echo "$query <br>";
                        echo " طرح در مرحله صورت وضعیت می باشد و پیش فاکتور آن نباید تغییر نماید";
                        exit;
                    }
                    
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    exit;
                }
                      
                    
            
            try 
                {	
                    //پرس و جوی بررسی اینکه پیش فاکتورهای طرح ثبت شده یا خیر
                    $query = "
                    select count(*) cnt,max(proposable) hasproposable from invoicemaster 
                    inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
                    where  ApplicantMasterID='$_POST[ApplicantMasterID]' ";
                    $result = mysql_query($query);
                	$row = mysql_fetch_assoc($result);
                    $cnt=$row['cnt'];
                    $hasproposable=$row['hasproposable'];
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    exit;
                }
    		if ($hasproposable<=0 && $ApplicantstatesID==30)
            {
                print "لطفا قبل از تغییر وضغیت ارسال به پیشنهاد قیمت لوله انجام شود";
                exit;
            }                            
            
    		if ($cnt<=10 && $ApplicantstatesID==30)
            {
                print "لطفا قبل از تغییر وضغیت لیست لوازم/پیش فاکتور طرح را کامل نمایید";
                exit;
            }
            
            
            //پرس و جوی محاسبه شماره تغییر وضعیت بعدی طرح
            $query = "SELECT max(stateno)+1 stateno FROM appchangestate 
                     where ApplicantMasterID='$ApplicantMasterID'";
                                
                                //print $query;exit;
            try 
              {		
                $result = mysql_query($query);
                $row = mysql_fetch_assoc($result);
                $maxstateno=$row['stateno'];//شماره تغییر وضعیت بعدی طرح
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }                    
            
            
            try 
              {		
                //پرس و جوی استخراج مشخصات تغییر وضعیت فعلی طرح
                 $query = "SELECT applicantstatesID 
                 FROM appchangestate 
                 where ApplicantMasterID='$_POST[ApplicantMasterID]' and stateno='".($maxstateno-1)."'";
                 $result = mysql_query($query);
        		 $row = mysql_fetch_assoc($result);
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
              }  
                          
            //در صورتی که شناسه طرح بزرگتر از یک بود و  شناسه تغییر وضعیت فعلیو قبلی تغییر کرده بود
            if (($ApplicantstatesID>0) &&($_POST['ApplicantMasterID']>0) && ($row['applicantstatesID']!=$ApplicantstatesID))
            {
                $applicantmasteridold=$_POST['ApplicantMasterID'];//شناسه طرح
				if ($ApplicantstatesID==30)//وضعیت تایید پیش فاکتور
                {
                    $YearID=$_POST['YearID'];//سال طرح
                    $SaveTime=date('Y-m-d H:i:s');//زمان
                    $SaveDate=date('Y-m-d');//تاریخ
                    $ClerkID=$login_userid;//کاربر
                    //تابع ثبت صورت وضعیت اولیه که کپی پیش فاکتور تایید نهایی شده می باشد
                    insertsurat($applicantmasteridold,$Description,$login_userid,$_server, $_server_user, $_server_pass,$_server_db);
                }
                else
                {
                    /*
                        appchangestate جدول تغییر وضعیت طرح
                        ApplicantMasterID شناسه طرح
                        stateno شماره تغییر وضعیت
                        applicantstatesID شناسه تغییر وضعیت
                        Description توضیح
                        SaveTime زمان
                        SaveDate تاریخ 
                        ClerkID کاربر
                    */
                    $querytr= "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES(
                    '$applicantmasteridold',$maxstateno,'$ApplicantstatesID','$Description', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".
                    $login_userid."');";
                    mysql_query($querytr);
                    if ($ApplicantstatesID==52)//شناسه تغییر وضعیت رئیس م زراعی به کارشناس استان
                    {
                        //در صورتی که طرح توسط مهندسی زراعی برگشت بخورد لول ان نه می شود
                        $querytr= "update applicantmasterdetail set level=9 where level>0 and ApplicantMasterID='$applicantmasteridold';";
                        mysql_query($querytr);
                    }
                }
            }
            
            /*
            $login_RolesID==13 مدیر آبیاری تحت فشار
            $login_RolesID==10 مدیر مهندسین مشاور
            $login_RolesID==11 مشاور بازبین
            $login_RolesID==27 مدیر سامانه ها
            $login_RolesID==2 پیمانکار
            $login_RolesID==9 کاربر مهندسین مشاور
            $login_RolesID==1 مدیر پیگیری
            $showm تمام اطلاعات نمایش داده شود
            */
            if ( ( (!in_array($login_RolesID, array(13)) ||   $showm>0) && ($login_RolesID==11 ||$login_RolesID==10 || $login_RolesID==13 ||$login_RolesID==27  ||$login_RolesID==14  ||$login_RolesID==4
            ||$login_RolesID==2 ||$login_RolesID==9) ) ||$login_RolesID==1)
            {
                /*
                invoicedetail ریز لوازم طرح
                invoicemaster عنوان لیست لوازم طرح
                ApplicantMasterID شناسه طرح
                InvoiceMasterID شناسه لیست لوازم
                */
                $query = "select invoicedetail.* from invoicedetail
                            inner join invoicemaster on invoicemaster.InvoiceMasterID=invoicedetail.InvoiceMasterID 
                            and invoicemaster.ApplicantMasterID='$_POST[ApplicantMasterID]' ";
                
                try 
                  {		
                    $result = mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                  
                
          		while ($row = mysql_fetch_assoc($result))
                {
                    $Number=$_POST['Number'.$row['InvoiceDetailID']];  
                    if ((strlen(trim($Number))>0))
                    {
                        /*
                        invoicedetail ریز لوازم طرح
                        val3 مقدار تاییدی ناظر عالی
                        val2 مقدار مشاور ناظر یا بازبین
                        val1 مقدار پیمانککار یا کاربر مشاور طراح
                        Number تعداد/مقدار
                        InvoiceDetailID شناسه لیست لوازم ریز
                        SaveTime زمان
                        SaveDate تاریخ
                        ClerkID کاربر
                        */
                            if (($login_RolesID==13 ||$login_RolesID==14 || $login_RolesID==27))          
                            $query = "UPDATE invoicedetail SET val3='$Number',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]' and Number<>'$Number';";
                            else if ($login_RolesID==10 || $login_RolesID==11)   
                            $query = "UPDATE invoicedetail SET val2='$Number',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]' and Number<>'$Number';";
                            else if ($login_RolesID==4||$login_RolesID==2 ||$login_RolesID==9)   
                            $query = "UPDATE invoicedetail SET val1='$Number',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]' and Number<>'$Number';";
                             
                        try 
                          {		
                            mysql_query($query); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                                                     
                    }
                    //print $_POST['chk'.$row['InvoiceDetailID']];
                    if (isset($_POST['Number'.$row['InvoiceDetailID']]))
                    {
                        /*
                        invoicedetail ریز لوازم طرح
                        deactive غیر فعال کردن هزینه اجرایی
                        SaveTime زمان
                        SaveDate تاریخ
                        ClerkID کاربر
                        */
                        
                        if ($_POST['chk'.$row['InvoiceDetailID']]=='on')
                        $query = "UPDATE invoicedetail SET deactive=1,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]';";
                        else
                        $query = "UPDATE invoicedetail SET deactive=0,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]';";
                            //print $query."<br>";
                        try 
                          {		
                            mysql_query($query); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                                                    
                    }
                    

                } //exit;  
                   
                if ($login_RolesID==10 ||$login_RolesID==13 ||$login_RolesID==14 || $login_RolesID==4||$login_RolesID==2 ||$login_RolesID==9 ||$login_RolesID==1)
                {     
                    
                    /*
                    manuallistprice فهرست بهای دستی
                    ApplicantMasterID شناسه طرح
                    */
                    $query = "select manuallistprice.* from manuallistprice
                            where ApplicantMasterID='$_POST[ApplicantMasterID]' ";
                    try 
                      {		
                        $result = mysql_query($query);
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                    
            		while ($row = mysql_fetch_assoc($result))
                    {
                        $Number=round($_POST['FNumber'.$row['ManualListPriceID']],3);//تعداد 
                        $Number2=round($_POST['FNumber2'.$row['ManualListPriceID']],3);//تعداد جزء 
                        $nval=round($_POST['FNumber2'.$row['ManualListPriceID']]*$_POST['FNumber'.$row['ManualListPriceID']],2); //تعداد تغییری کاربر 
                        
                        $Price= str_replace(',', '', $_POST['Price'.$row['ManualListPriceID']]);//مبلغ
                        $Price= str_replace('-', '', $Price);
                        $Price= str_replace('+', '', $Price);
                        
                        if ( (strlen(trim($Number))>0) && (strlen(trim($Price))>0) && $_POST['TCode'.$row['ManualListPriceID']]==3)
                        {
                            /*
                            manuallistprice فهرست بهای دستی
                            nval3 مقدار تاییدی ناظر عالی
                            nval2 مقدار مشاور ناظر یا بازبین
                            nval1 مقدار پیمانککار یا کاربر مشاور طراح
                            pval3 مبلغ تاییدی ناظر عالی
                            pval2 مبلغ مشاور ناظر یا بازبین
                            pval1 مبلغ پیمانککار یا کاربر مشاور طراح
                            Number تعداد/مقدار
                            Number2 تعداد جزء
                            ManualListPriceID شناسه فهرست بهای دستی
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            */
                            if ($login_RolesID==13 ||$login_RolesID==14 || $login_RolesID==27)          
                            $query = "UPDATE manuallistprice SET pval3='$Price',price='$Price',nval3='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceID ='$row[ManualListPriceID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end <>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==10 || $login_RolesID==11)   
                            $query = "UPDATE manuallistprice SET pval2='$Price',price='$Price',nval2='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceID ='$row[ManualListPriceID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==4||$login_RolesID==2 ||$login_RolesID==9 ||$login_RolesID==1)   
                            $query = "UPDATE manuallistprice SET pval1='$Price',price='$Price',nval1='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceID ='$row[ManualListPriceID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                                            
                        }
                        try 
                          {		
                            mysql_query($query);
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                        
                        //print $query.'<br>';
                    }   
                    
                        //exit;
                    
                     
                    /*
                    manuallistpriceall فهارس بهای دستی
                    ApplicantMasterID شناسه طرح
                    */
                    $query = "select * from manuallistpriceall
                            where ApplicantMasterID='$_POST[ApplicantMasterID]' ";
                    $result = mysql_query($query);
            		while ($row = mysql_fetch_assoc($result))
                    {
                        $Number=round($_POST['FNumber'.$row['ManualListPriceAllID']],3); 
                        $Number2=round($_POST['FNumber2'.$row['ManualListPriceAllID']],3); 
                        $nval=round($_POST['FNumber'.$row['ManualListPriceAllID']]*$_POST['FNumber2'.$row['ManualListPriceAllID']],2);  
                        
                        $Price= str_replace(',', '', $_POST['Price'.$row['ManualListPriceAllID']]);
                        $Price= str_replace('-', '', $Price);
                        $Price= str_replace('+', '', $Price);
                        
                        //print "sa".strlen(trim($Number));exit;
                        if ((strlen(trim($Number))>0) && (strlen(trim($Price))>0)  && $_POST['TCode'.$row['ManualListPriceAllID']]==4)
                        {
                            /*
                            manuallistprice فهارس بهای 
                            nval3 مقدار تاییدی ناظر عالی
                            nval2 مقدار مشاور ناظر یا بازبین
                            nval1 مقدار پیمانککار یا کاربر مشاور طراح
                            pval3 مبلغ تاییدی ناظر عالی
                            pval2 مبلغ مشاور ناظر یا بازبین
                            pval1 مبلغ پیمانککار یا کاربر مشاور طراح
                            Number تعداد/مقدار
                            Number2 تعداد جزء
                            manuallistpriceallID شناسه فهارس بهای 
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            */
                            
                            if ($login_RolesID==13 ||$login_RolesID==14 || $login_RolesID==27)          
                            $query = "UPDATE manuallistpriceall SET pval3='$Price',price='$Price',nval3='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==10 || $login_RolesID==11)   
                            $query = "UPDATE manuallistpriceall SET pval2='$Price',price='$Price',nval2='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==4||$login_RolesID==2 ||$login_RolesID==9)   
                            $query = "UPDATE manuallistpriceall SET pval1='$Price',price='$Price',nval1='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                                            
                        }
                        else if ((strlen(trim($Number))>0)  && $_POST['TCode'.$row['ManualListPriceAllID']]==4)
                        {
                            /*
                            manuallistprice فهارس بهای 
                            nval3 مقدار تاییدی ناظر عالی
                            nval2 مقدار مشاور ناظر یا بازبین
                            nval1 مقدار پیمانککار یا کاربر مشاور طراح
                            pval3 مبلغ تاییدی ناظر عالی
                            pval2 مبلغ مشاور ناظر یا بازبین
                            pval1 مبلغ پیمانککار یا کاربر مشاور طراح
                            Number تعداد/مقدار
                            Number2 تعداد جزء
                            manuallistpriceallID شناسه فهارس بهای 
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            */
                            if ($login_RolesID==13 ||$login_RolesID==14 || $login_RolesID==27)          
                            $query = "UPDATE manuallistpriceall SET nval3='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==10 || $login_RolesID==11)   
                            $query = "UPDATE manuallistpriceall SET nval2='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                            else if ($login_RolesID==4||$login_RolesID==2 ||$login_RolesID==9)   
                            $query = "UPDATE manuallistpriceall SET nval1='$nval',Number2='$Number2',Number='$Number',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                    		SaveDate = '" . date('Y-m-d') . "', 
                    		ClerkID = '" . $login_userid . "' WHERE ManualListPriceAllID ='$row[ManualListPriceAllID]' and (case ifnull(Number2,0) when 0 then 1 else Number2 end<>'$Number2' or Number<>'$Number' or pval3<>'$Price');";
                                        
                        }
                         try 
                          {		
                            mysql_query($query);
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                        
                    }  
                                     
                }    
            
                //$login_RolesID==10 مدیر مهندسین مشاور
                //$login_RolesID==13 مدیر آبیاری تحت فشار
                //$login_RolesID==14 ناظر عالی
                if ($login_RolesID==10 || $login_RolesID==13 ||$login_RolesID==14)
                {    
                    /*
                    appfoundationID شناسه سازه طرح
                    appfoundation جدول سازه های طرح
                    applicantmasterdetail جدول ارتباطی  طرح ها
                    ApplicantMasterIDmaster شناسه طر اجرایی
                    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
                    */
                    $query = "select distinct appfoundationID from appfoundation 
                    inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterIDmaster='$_POST[ApplicantMasterID]'
                    or applicantmasterdetail.ApplicantMasterIDsurat='$_POST[ApplicantMasterID]'
                    ) ";
                    
                    try 
                          {		
                            $result = mysql_query($query);
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                    
                    //print $query;exit;
                    
            		while ($row = mysql_fetch_assoc($result))
                    {
                        $appfoundationID=$row['appfoundationID'];
                        $Price= str_replace(',', '', $_POST['val1'.$appfoundationID]);
                        $Price= str_replace('-', '', $Price);
                        $Price= str_replace('+', '', $Price);
                        $Description=$_POST['val2'.$appfoundationID]; 
                        $fehrestsmasterID=0;
                        $fehrestsmasterID=$_POST['val3'.$appfoundationID];  
                        
                        /*
                        print strlen(trim($Price))."_".$fehrestsmasterID."_".$appfoundationID."_".$_POST['oldval1'.$appfoundationID]
                        ."_".$_POST['val1'.$appfoundationID]."_".$_POST['oldval2'.$appfoundationID]."_".$_POST['val2'.$appfoundationID]
                        ."_".$_POST['oldval3'.$appfoundationID]."_".$_POST['val3'.$appfoundationID]."<br>";
                        
                      
                      print exit; */
                        if ( (strlen(trim($Price))>0) && $fehrestsmasterID>0 && $appfoundationID>0 &&
                        ($_POST['oldval1'.$appfoundationID]!=$_POST['val1'.$appfoundationID] ||
                        $_POST['oldval2'.$appfoundationID]!=$_POST['val2'.$appfoundationID] ||
                        $_POST['oldval3'.$appfoundationID]!=$_POST['val3'.$appfoundationID]) )
                        {
                            
                            
                            try 
                              {	
                                /*
                                appfoundationmoderate جدول تعدیل سازه های طرح
                                appfoundationID شناسه سازه طرح
                                */
                                $querys = "delete from appfoundationmoderate where appfoundationID='$appfoundationID';";
                                mysql_query($querys); 
                              }
                              //catch exception
                              catch(Exception $e) 
                              {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                                exit;
                              }
                              
                            /*
                                appfoundationmoderate جدول تعدیل سازه های طرح
                                appfoundationID شناسه سازه طرح
                                Price مبلغ
                                fehrestsmasterID شناسه فصل آیتم سازه
                                Description توضیحات
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                                */
                            $querys = "insert into appfoundationmoderate (appfoundationID,Price,fehrestsmasterID,Description,SaveTime,SaveDate,ClerkID) 
                            values('$appfoundationID','$Price','$fehrestsmasterID','$Description','" . date('Y-m-d H:i:s') . "','" 
                            . date('Y-m-d') . "','" . $login_userid . "');";
                           /// print $querys;
                    
                          
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
                    }
                }    
                //در صورتی که تغییروضعیت های زیر باشد تغییرات قبلی لوازم حذف و تغییرات جدید درج می شود
                //$ApplicantstatesID==4 بازبین به مدیر مشاور طراح
                //$ApplicantstatesID==42 مشاور ناظر به مجري
				if ($ApplicantstatesID==4 || $ApplicantstatesID==42)
                {
                     try 
                          {		/*
                                invoicedetailviewed جدول تغییرات لوازم توسط پیمانکار یا مشاور ناظر
                                InvoiceMasterID شناسه عنوان پیش فاکتور
                                invoicemaster جدول عنوان پیش فاکتور
                                ApplicantMasterID شناسه طرح
                                ToolsMarksID شناسه ابزار مارک
                                Number تعداد
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                                */
                                mysql_query("delete from invoicedetailviewed 
                                where InvoiceMasterID in (select InvoiceMasterID from invoicemaster where ApplicantMasterID='$_POST[ApplicantMasterID]')");
                                
                                mysql_query("INSERT INTO  invoicedetailviewed (InvoiceMasterID,ToolsMarksID,Number,SaveTime,SaveDate,ClerkID) 
                                select InvoiceMasterID,ToolsMarksID,Number,'" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."' from invoicedetail
                                where InvoiceMasterID in (select InvoiceMasterID from invoicemaster where ApplicantMasterID='$_POST[ApplicantMasterID]')"); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          

                }  
                //در صورتی که تغییروضعیت های زیر باشد تغییرات قبلی لوازم حذف می شود زیرا طرح توسط بازبین  یا ناظر تایید شده است
                //$ApplicantstatesID==8 بازبین به دفترمدیریت آب و خاک
                //$ApplicantstatesID==43 مشاور ناظر به ناظر عالي
				
                else if ($ApplicantstatesID==8 || $ApplicantstatesID==43)
                {
                    
                    try 
                          {		
                            /*
                                invoicedetailviewed جدول تغییرات لوازم توسط پیمانکار یا مشاور ناظر
                                InvoiceMasterID شناسه عنوان پیش فاکتور
                                invoicemaster جدول عنوان پیش فاکتور
                                ApplicantMasterID شناسه طرح
                                */
                              mysql_query("delete from invoicedetailviewed 
                                where InvoiceMasterID in (select InvoiceMasterID from invoicemaster where ApplicantMasterID='$_POST[ApplicantMasterID]')"); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                    
                }
                
                //gadget3operational لیست هزینه های اجرایی مرتبط با لوازم
                $query = "select * from gadget3operational";
                            
                 try 
                          {		
                              $result = mysql_query($query); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                                
                $result = mysql_query($query);
                while ($row = mysql_fetch_assoc($result))
                {
                    $Code=$_POST['Code'.$row['gadget3operationalID']];//کد هزینه
                    $GCode=$_POST['GCode'.$row['gadget3operationalID']]; //کد کالا
                    $oldCode=$_POST['oldCode'.$row['gadget3operationalID']];//کد هزینه قبلی
                    $oldGCode=$_POST['oldGCode'.$row['gadget3operationalID']];//کد کالای قبلی
                    if ($Code<>$oldCode || $GCode<>$oldGCode)//در صورتی که کد کالا یا ککد هزینه با رکورد قبلی متفاوت بود عملیات زیر انجام می شود
                    {
                        try 
                            {
                                /*
                                GCode کد کالا
                                TCode کد هزینه
                                applicantcostcodechange جدول تغییرات هزینه اجرایی مرتبط با لوازم طرح
                                gadget3operationalID شناسه هزینه اجرایی مرتبط با لوازم
                                ApplicantMasterID شناسه طرح
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                                */
                                $query = "delete from applicantcostcodechange WHERE gadget3operationalID ='$row[gadget3operationalID]' and ApplicantMasterID='$_POST[ApplicantMasterID]';";mysql_query($query);
                                $query = "insert into applicantcostcodechange 
                                (`ApplicantMasterID`, `gadget3operationalID`, `GCode`, `TCode`, `SaveDate`, `SaveTime`, `ClerkID`) 
                                values ('$_POST[ApplicantMasterID]','$row[gadget3operationalID]','$GCode','$Code','".
                                date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                                mysql_query($query);
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }      
                    }
                } 
             }
            
            $register=1;//ثبت انجام شده است
            //$_POST['sref'] آدرس لینک ارجاع به این صفحه            
            header("Location: ".$_POST['sref']);
            exit(); 
            
        }
    }
    else
    {
        
        $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        //print $ids.'salam';
        $linearray = explode('_',$ids);
        $ApplicantMasterID=$linearray[0];//شناسه طرح
        $type=$linearray[1];//نوع صفحه که انواع مختلف میزان اطلاعات مختلفی را نمایش می دهد
        if (!($ApplicantMasterID>0))
        {
            print "آی دی طرح ناشناخته است";
            exit;
        }
        
        
        $DesignerCoID=$linearray[2];//شرکت مشاور طراح
        $OperatorCoID=$linearray[3];//شناسه شرکت پیمانکار
        $ApplicantstatesID=$linearray[4];//شناسه طرح
        $PCoID=$linearray[5];//شناسه سازه طرح
        $InvoiceMasterIDselectedp=$linearray[7];//شناسه پیش فاکتور طرح
        $CoTitleinPrint="";//عنوان امضا کننده که مشاور طراح یا پیمانکار هست
        
        /*
        applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
        لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
        این جدول دارای ستون های ارتباطی زیر می باشد
        ApplicantMasterID شناسه طرح مطالعاتی
        ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
        ApplicantMasterIDsurat شناسه طرح صورت وضعیت
        */
        $query1 ="
        select applicantmasterdetail.ApplicantMasterID,applicantmasterdetail.prjtypeid,ApplicantMasterdetailID from applicantmasterdetail
        where applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or 
        applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or
        applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'";
        
        try 
          {		
            $result1 = mysql_query($query1);
      		$row1 = mysql_fetch_assoc($result1);     
            $ApplicantMasterIDd=$row1['ApplicantMasterID'];//شناسه طرح
            $prjtypeid=$row1['prjtypeid'];//نوع پروژه
            $ApplicantMasterdetailID=$row1['ApplicantMasterdetailID'];//شناسه جدول ازتباطی
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
      
        
        
        //در صورتی که نوع پروژه انتقال آب بود و شناسه طرح بزرگتر از یک بود
        if ($prjtypeid==1 && $ApplicantMasterID>0)
        {
            //تابع ذخیره اطلاعات آبرسانی در جدول ارتباطی
            savewsvals ($ApplicantMasterID,$prjtypeid,$ApplicantstatesID);
        }
        
        if ($DesignerCoID>0 && $ApplicantMasterIDd==$ApplicantMasterID) 
        {
            $returnID=$DesignerCoID.'_1';
            $CoTitleinPrint="مهندسین مشاور طراح";//عنوان امضا کننده که مشاور طراح یا پیمانکار هست
        }
        else if ($OperatorCoID>0) 
        {
            $returnID=$OperatorCoID.'_2';
            $CoTitleinPrint="شرکت مجری";//عنوان امضا کننده که مشاور طراح یا پیمانکار هست
            /*
            بررسی اینکه آیا  سیستم آبیاری یا مساحت  طرح نسبت به مطالعات تغییر نموده است یا خیر
            DesignSystemGroupsID شناسه سیستم آبیاری طرح
            designsystemgroupsdetail جدول سیستم ها و الگوی کشت طرح 
            ApplicantMasterID شناسه طرح
            hektar هکتار
            */
            $query1 ="SELECT DesignSystemGroupsID from designsystemgroupsdetail
                        where ApplicantMasterID='$ApplicantMasterID' and hektar>0  and DesignSystemGroupsID not in 
                        (select DesignSystemGroupsID from designsystemgroupsdetail designsystemgroupsdetailin 
                        where designsystemgroupsdetailin.ApplicantMasterID='$ApplicantMasterIDd' 
                        and designsystemgroupsdetailin.hektar>0 and designsystemgroupsdetailin.hektar=designsystemgroupsdetail.hektar)  ";
                
            try 
              {		
                    $result1 = mysql_query($query1);
              		$row1 = mysql_fetch_assoc($result1);         
                    $operatorviolated=0;
                    if ($row1['DesignSystemGroupsID']>0)
                    print "توجه: سیستم آبیاری یا مساحت  طرح نسبت به مطالعات تغییر نموده است."; 
              }
              //catch exception
              catch(Exception $e) 
              {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                exit;
              }
      

        }
         
        //تعیین آدرس برگشتی با توجه به نوع صفه
        if ($type==1)
            $masterpagename="summaryinvoicemaster.php";
        else if ($type==2)
            $masterpagename="../appinvestigation/applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$returnID.rand(10000,99999);
        else if ($type==3)
            $masterpagename="../appinvestigation/sendtoanjoman.php";
        else if ($type==4)
            $masterpagename="../appinvestigation/allapplicantstates.php";
        else if ($type==5)
            $masterpagename="../appinvestigation/aaapplicantfreep.php";
        else if ($type==10)
            $masterpagename="apprequest.php";
        else if ($type==13)
            $masterpagename="apprequest.php";
        else if ($type==11)
            $masterpagename="apprequestp.php";
        else if ($type==12)
            $masterpagename="foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
            .rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999);
            
            //print $ApplicantstatesID."salam";
        //تعیین مجوز نمایش کامل اطلاعات برای نقش های مختلف با توجه به نوع
       if ( ( in_array($ApplicantstatesID,array("23","50","24","2","1","3","4")) && ($DesignerCoID>0) ) || $type==10) 
       {
	    $ApplicantMasterID=$ApplicantMasterID;
            $permitrolsidforviewdetail = array("0","1","3","17");
            $permitrolsidforviewdetailcost = array("2");
       }        
       else if ($type==11)
       {
            $permitrolsidforviewdetail = array("0","2","3","1","11","12","14","20","21","22","17");
            $permitrolsidforviewdetailcost = array();
       }
       else if ($type==10)
       {
            $permitrolsidforviewdetail = array("0","2","3","1","4","9","10","13","11","12","14","20","21","22","17");
            $permitrolsidforviewdetailcost = array();
       }     
       else if ($type==1)
       {
            $permitrolsidforviewdetail = array("1","3","20","21","22","17");
            $permitrolsidforviewdetailcost = array();
       }     
       else if ($type==5)
       {
            $permitrolsidforviewdetail = array("0","1","3","17");
            $permitrolsidforviewdetailcost = array();
       }     
       else if ($showm>0 )
       {
           $permitrolsidforviewdetail = array("1", "2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","31","17");
            $permitrolsidforviewdetailcost = array();     
       }
       else
       {
           $permitrolsidforviewdetail = array("0","1","2","3","9","10","11","12","14","20","21","22","31","17"); 
            $permitrolsidforviewdetailcost = array();
       }

     
        /*
        پرس و جوی نمایش گردش های صورت گرفته در طرح
        بانک و صندوق فقط گردش های خود را می بینند و سایر کاربران تمام گردش ها را مشاهده می کنند
        appchangestate جدول گردش های صورت گرفته روی طرح
        applicantstates.title عنوان گردش
        applicantstates جدول وضعیت های مختلف پروژه ها
        applicantstatesID شناسه وضعیت
        ApplicantMasterID شناسه طرح
        stateno ترتیب تغییر وضعیت انجام شده
        applicantstatesID 17 صندوق به جهاد کشاورزی
        applicantstatesID 12 مدیریت آب و خاک به صندوق
        applicantstatesID 36 مدیریت آب و خاک به بانک
        applicantstatesID 18 بانک به جهاد کشاورزی
        */
        if ($login_RolesID=='16' )//نقش صندوق
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID' and appchangestate.applicantstatesID in (17,12)
        order by appchangestate.stateno";   
        
        else  if ($login_RolesID=='7')//نقش بانک
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID' and appchangestate.applicantstatesID in (36,18)
        order by appchangestate.stateno";        
        else 
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID'
        order by appchangestate.stateno";
        try 
          {		
            $resultallstates = mysql_query($queryallstates);
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
      
        
        
        
     
            
        
        
        
        $wincoef1=0;//ضریب  اول برنده پیشنهاد قیمت 
        $wincoef2=0;//ضریب  دوم برنده پیشنهاد قیمت 
        $wincoef3=0;//ضریب  سوم برنده پیشنهاد قیمت 
        if ($type==1 || $type==3 || $type==5)
        {
            /*
                operatorapprequest جدول پیشنهاد قیمت های طرح
                applicantmaster جدول مشخصات طرح
                BankCode کد رهگیری طرح
                ApplicantMasterID شناسه طرح
                state=1 انتخاب شدن پیشنهاد توسط کشاورز
                operatorcoID شناسه پیمانکار
                coef1 ضریب اول اجرای طرح
                coef2 ضریب دوم اجرای طرح
                coef3 ضریب سوم اجرای طرح
                coef4 ضریب چهارم اجرای طرح
                coef5 ضریب پنجم اجرای طرح
                ecept مقدار یک در صورتی که مدیر آب و خاک مجوز داده
                cityid شناسه شهر طرح            
            */
                $query1 ="select operatorapprequest.coef1,operatorapprequest.coef2,operatorapprequest.coef3
                FROM operatorapprequest
                inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=operatorapprequest.ApplicantMasterID
                inner join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode 
                and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID and 
                substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)        
                where ifnull(applicantmasterop.operatorcoid,0)>0 and 
                (applicantmasterop.coef1<>operatorapprequest.coef1 or applicantmasterop.coef2<>operatorapprequest.coef2 or 
                applicantmasterop.coef3>operatorapprequest.coef3) and applicantmasterop.ApplicantMasterID='$ApplicantMasterID'  
                and operatorapprequest.state=1 
                ";
                try 
                  {		
                    $result1 = mysql_query($query1);
              		$row1 = mysql_fetch_assoc($result1);   
                    $operatorviolated=0;
                    $wincoef1=$row1['coef1'];
                    $wincoef2=$row1['coef2'];
                    $wincoef3=$row1['coef3'];
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                  }
                  

        }
    
         /* 
           applicantmaster جدول مشخصات طرح
           BankCode کدرهگیری طرح
           belaavaz بلاعوض
           criditType تجمیع بودن یا نبودن
           LastTotal جمع کل هزینه های طرح
           private یکی از ویژگی های طرح می باشد که در صورتی که شرکت ها بخواهند طرح تستی و آزمایشی داشته باشند آنرا شخصی می کنند								
           CostPriceListMasterID شناسه سال هزینه های اجرایی طرح 
           creditsourceID شناسه جدول منبع تامین اعتبار
           DesignerCoIDnazer شناسه مشاور بازبین
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           costpricelistmaster هزینه های اجرایی طرح ها
           year جدول سال
           costpricelistmaster هزینه های اجرایی طرح ها
           creditsource جدول منابع اعتباری
           designerco جدول شرکت های طراح
           designer جدول طراحان
           designsystemgroups سیستم آبیاری
           manuallistprice جدول ثبت هزینه های اجرایی طرح
           manuallistpriceall جدول فهارس بها
           appfoundation جدول سازه های طرح ها
           applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
           لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
           این جدول دارای ستون های ارتباطی زیر می باشد
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           clerk جدول کاربران
           operatorapprequest جدول پیشنهاد قیمت های طرح
           applicantmaster جدول مشخصات طرح
           BankCode کد رهگیری طرح
           ApplicantMasterID شناسه طرح
           state=1 انتخاب شدن پیشنهاد توسط کشاورز
           operatorcoID شناسه پیمانکار
           coef1 ضریب اول اجرای طرح
           coef2 ضریب دوم اجرای طرح
           coef3 ضریب سوم اجرای طرح
           coef4 ضریب چهارم اجرای طرح
           coef5 ضریب پنجم اجرای طرح
           selfnotcashhelpval خودیاری غیر نقدی
           selfcashhelpval خودیاری نقدی
           selfcashhelpdescription توضیحات خودیاری نقدی
        */       
        $sql = "SELECT applicantmaster.BankCode,ifnull(applicantmaster.belaavaz,0) belaavaz,applicantmaster.creditsourceid,applicantmaster.criditType,applicantmaster.ApplicantMasterIDmaster
		,applicantmaster.LastTotal,ostan.id ostanid,
        applicantmaster.operatorcoIDbandp,applicantmaster.Datebandp,applicantmaster.letterno,applicantmaster.isbandp
		,applicantmaster.letterdate,applicantmaster.sandoghcode,applicantmaster.Freestate,
        applicantmaster.selfcashhelpval,applicantmaster.selfcashhelpdate,applicantmaster.selfcashhelpdescription
        ,applicantmaster.selfnotcashhelpdetail,applicantmaster.selfnotcashhelpval,
        applicantmaster.selfnotcashhelpdate,applicantmaster.DesignerCoIDnazer
        ,applicantmaster.cityid,substring(applicantmaster.cityid,1,4) cityid14,substring(applicantmaster.cityid,1,5) cityid15,
        applicantmaster.costpricelistmasterID,applicantmaster.transportless,applicantmaster.DesignArea,ifnull(applicantmaster.DesignerCoID,0) DesignerCoID ,
        ApplicantFName,ApplicantName,othercosts1,othercosts2,othercosts3,othercosts4,othercosts4text,othercosts5,transportcosttable.unpredictedcost,
        case ifnull(applicantmaster.coef1,0) when 0 then transportcosttable.coef1 else applicantmaster.coef1 end coef1,
        case ifnull(applicantmaster.coef2,0) when 0 then transportcosttable.coef2 else applicantmaster.coef2 end coef2,
        case ifnull(applicantmaster.coef3,0) when 0 then transportcosttable.coef3 else applicantmaster.coef3 end coef3,
        case ifnull(applicantmaster.coef4,0) when 0 then transportcosttable.coef4 else applicantmaster.coef4 end coef4,
        applicantmaster.coef5 appcoef5,shahr.cityname shahrcityname,
        
        transportcosttable.Cost
        ,concat(designer.Lname,' ',designer.Fname) designerTitle,designerco.Title DesignerCoTitle,operatorco.Title operatorcoTitle, applicantmaster.operatorcoid 
        ,yearcost.Value fb
        ,operatorco.AccountNo operatorcoAccountNo,operatorco.AccountBank operatorcoAccountBank
        ,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
        ,producerapprequestmaster.ApplicantMasterID ApplicantMasterIDprop2,incnt.cnt incntcnt
        FROM applicantmaster 
        
        left outer join (select distinct ApplicantMasterID from producerapprequest)
        producerapprequestmaster on producerapprequestmaster.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster
        
        left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
    
        left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
        and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
    
        left outer join transportcosttable on transportcosttable.TransportCostTableMasterID=applicantmaster.TransportCostTableMasterID
        and applicantmaster.DesignArea between transportcosttable.MinArea and transportcosttable.MaxArea
        
        left outer join raindesigncosttable on raindesigncosttable.RainDesignCostTableMasterID=applicantmaster.RainDesignCostTableMasterID
        and applicantmaster.DesignArea between raindesigncosttable.MinArea and raindesigncosttable.MaxArea
        
        
        left outer join dropdesigncosttable on dropdesigncosttable.DropDesignCostTableMasterID=applicantmaster.DropDesignCostTableMasterID
        and applicantmaster.DesignArea between dropdesigncosttable.MinArea and dropdesigncosttable.MaxArea
        
        left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
        left outer join month as monthcost on monthcost.MonthID=costpricelistmaster.MonthID 
        left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

        
        left outer join designer on designer.designerid=applicantmaster.designerid
        left outer join designerco on designerco.DesignerCoid=applicantmaster.DesignerCoid
        left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
        left outer join (select count(*) cnt,invoicemaster.ApplicantMasterID from invoicemaster 
        left outer join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
        group by ApplicantMasterID) incnt on 
        incnt.ApplicantMasterID=applicantmaster.ApplicantMasterID
        
        WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID'";
        
        try 
          {		
                $count = mysql_fetch_assoc(mysql_query($sql));
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
          }
      
        
        
        $creditsourceid = $count['creditsourceid'];//شناسه منبع اعتباری
        $currbelaavaz= $count['belaavaz'];//بلاعوض
        $incntcnt=$count['incntcnt'];//تعداد لیست لوازم
        if (!($prjtypeid==0 && $ApplicantstatesID==23))//در صورتی که طرح ثبت اولیه باشد و پروژه آبیاری تحت فشار بود ثبت کردن لوازم چک شود و هشدار داده شود
        if ($count['incntcnt']<=0  )
        {
            print "لطفا قبل از تغییر وضغیت لیست لوازم/پیش فاکتور طرح را ثبت نمایید.";
        }
        $soo=$count["ostanid"];//شناسه استان
        $criditType = $count['criditType'];//تجمیع بودن یا نبودن
        $ApplicantName = $count['ApplicantFName']." ".$count['ApplicantName']." شهرستان  ".$count['shahrcityname'];//عنوان پروژه
        $DesignerCoIDnazer=$count['DesignerCoIDnazer'];//مشاور ناظر
        $cityid14=$count['cityid14'];//4 رقم اول شناسه شهر
        $cityid15=$count['cityid15'];//5  رقم اول شناسه شهر
        $designerTitle = $count['designerTitle'];//عنوان طراح
        $issurat= $count['issurat'];//صورت وضعیت بودن یا نبودن
        $ApplicantMasterIDmaster=$count['ApplicantMasterIDmaster'];//شناسه طرح اجرایی یا پیش فاکتور
        $opacc="";//مشخصات حساب بانکی پیمانکار
        if ($count['operatorcoid']>0)//شناسه پیمانکار
        {
            $CoTitle = $count['operatorcoTitle'];//عنوان پیمانکار
            $opacc = "($CoTitle $count[operatorcoAccountNo] $count[operatorcoAccountBank])";//مشخصات حساب بانکی پیمانکار
        }
        else $CoTitle = $count['DesignerCoTitle'];//عنوان مشاور طراح
        $operatorcoid=$count['operatorcoid'];//شناسه پیمانکار
        $transportless= $count['transportless'];//هزینه حمل لاظ شود یا خیر
        if ($transportless==1 || $transportless==11)      
           $transportless="checked";
           else
            $transportless="";
		if ($count['transportless']==10 || $count['transportless']==11) //هزینه حمل لاظ شود یا خیر     
           $digitless="checked";
        $costpricelistmasterID=$count['costpricelistmasterID'];//شناسه فهرست بها
        
        if (!($costpricelistmasterID>0))//شناسه فهرست بها
        {
            print "<br>سال فهرست بها نامعتبر می باشد.<br>";
        }
        $DesignArea= $count['DesignArea'];//مساحت
        $othercosts1=$count['othercosts1'];//سایر هزینه ها 1
        $othercosts2=$count['othercosts2'];//سایر هزینه ها 2
        $othercosts3=$count['othercosts3'];//سایر هزینه ها 3
        $othercosts4=$count['othercosts4'];//سایر هزینه ها 4
        $othercosts5=$count['othercosts5'];//سایر هزینه ها 5
   	    $othercoststext = explode('_',$count["othercosts4text"]);//عنوان سایر هزینه ها 4
   	    $totinvoicemanual = $othercoststext[4];//جمع کل مبلغ پیش فاکتورها
   	    $totfehrestmanual = $othercoststext[5];//جمع کل مبلغ هزینه های اجرایی
		if (abs($count['othercosts4'])>0 && $othercoststext[0]=='')//عنوان سایر هزینه ها 4
					{$othercosts4text='اتاقک پمپاژ و...(بر اساس دستور العمل)';}//عنوان سایر هزینه ها 4
		else if (!$count['othercosts4']>0 && $othercoststext[0]=='')//عنوان سایر هزینه ها 4
					{$othercosts4text='سایر هزینه ها';}//عنوان سایر هزینه ها 4  
		else 
		  {$othercosts4text=$othercoststext[0];$othercosts4textv=$othercosts4text;}//عنوان سایر هزینه ها 4	    
	

		if ($othercoststext[1]=='')//عنوان سایر هزینه ها 3
				   $othercosts3text='هزینه مطالعات/تعدیل و...';//عنوان سایر هزینه ها 3
		else
		  {$othercosts3text = $othercoststext[1];$othercosts3textv = $othercoststext[1];}//عنوان سایر هزینه ها 3
		
        $removeup="";//عدم نمایش سایر هزینه های خالی
        $removeupval=$othercoststext[3];
        if ($othercoststext[3])
            $removeup="checked";
        
		$removetax="";//عدم نمایش مالیات بر ارزش افزوده
        $coef4=round($count['coef4'],2);
        if ($othercoststext[2])
        {
            $removetax="checked";
            $coef4=1;
        }
        /*if ($login_RolesID==1 )
        {
            echo $sql;
            echo $count['coef4'];
            exit;
        }*/
        $Cost=$count['Cost'];//هزینه اجرایی
        $designcost=0;//هزینه طراحی
        $coef1=round($count['coef1'],2);//ضریب اول اجرای طرح
        $coef2=round($count['coef2'],2);//ضریب دوم اجرای طرح
         if ($wincoef3>0) //ضریب سوم اجرای طرح
        {    
            if (round($count['coef3'],3)<round($wincoef3,3))
                $coef3=round($count['coef3'],3);
            else    
                $coef3=round($wincoef3,3);   
        }
         else $coef3=round($count['coef3'],3);
        $appcoef5=$count['appcoef5'];//ضریب پنجم اجرای طرح
        $unpredictedcost=$count['unpredictedcost'];//هزینه های پیش بینی نشده
        $DesignerCoID=$count['DesignerCoID'];//شناسه مشاور طرا
        $fb=$count['fb'];//سال فهرست بها
        $pr=$count['pr'];//مبلغ پروژه        
        $selfcashhelpdate=$count["selfcashhelpdate"];//تاریخ فیش خودیاری نقدی
        $selfcashhelpval=number_format($count["selfcashhelpval"]);//مبلغ خودیاری نقدی
        $selfcashhelpdescription=$count["selfcashhelpdescription"];//توضیحات خودیاری نقدی
        $letterno=$count["letterno"];//شماره نامه خودیاری
        if ($issurat==1)
        {
       	    $hatarray = explode('_',$count["letterno"]);
            $hatval=str_replace(',', '', $hatarray[0]);
       	    $hattitle = $hatarray[1];//عنوان حسن انجام کار
       	    $hasanprice = str_replace(',', '', $hatarray[2]);//مبلغ حسن انجام کار
        }
        $letterdate=$count["letterdate"];//تاریخ نامه خودیاری
        $sandoghcode=$count["sandoghcode"];//کد صندوق
        $Freestate=$count["Freestate"];//شماره قسط آزادسازی
        $operatorcoIDbandp=$count["operatorcoIDbandp"];//پیمانکار ترک تشریفات در صورت وجود
        if ($count["Datebandp"]>0)//تاریخ ترک تشریفات در صورت وجود
        $Datebandp=gregorian_to_jalali($count["Datebandp"]);
        $isbandp=$count["isbandp"];// ترک تشریفات در صورت وجود
        if (strlen(trim($resquery["selfnotcashhelpdetail"]))>0)//خودیاری غیر نقدی
        {
            $larr = explode('_',$resquery["selfnotcashhelpdetail"]);
            if ($larr[0]>0)
            $selfnotcashhelpval1=number_format($larr[0]);//مقدار خودیاری غیر نقدی 1
            $selfnotcashhelpdate1=$larr[1]; //تاریخ پرداخت خودیاری غیر نقدی 1
            if ($larr[2]>0)  
            $selfnotcashhelpval2=number_format($larr[2]);//مقدار خودیاری غیر نقدی 2
            $selfnotcashhelpdate2=$larr[3];//تاریخ پرداخت خودیاری غیر نقدی 2
            if ($larr[4]>0)
            $selfnotcashhelpval3=number_format($larr[4]);//مقدار خودیاری غیر نقدی 3
            $selfnotcashhelpdate3=$larr[5];//تاریخ پرداخت خودیاری غیر نقدی 3
        }
        else
        {
            $selfnotcashhelpval1=number_format($resquery["selfnotcashhelpval"]);//مقدار خودیاری غیر نقدی 
            $selfnotcashhelpdate1=$resquery["selfnotcashhelpdate"];//تاریخ پرداخت خودیاری غیر نقدی     
        }
        $BankCode=$count['BankCode'];//کد رهگیری
        if ($issurat==1)//در صورتی که طرح صورت وضعیت باشد
            {
                /*
                در این پرس و جو بررسی می شود که آیا مبلغ صورت وضعیت بیشتر از مبلغ مصوب طراحی می باشد یا خیر
                
               applicantmaster جدول مشخصات طرح
               BankCode کدرهگیری طرح
               belaavaz بلاعوض
               criditType تجمیع بودن یا نبودن
               LastTotal جمع کل هزینه های طرح
               creditsourceID شناسه جدول منبع تامین اعتبار
               applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
               لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
               این جدول دارای ستون های ارتباطی زیر می باشد
               ApplicantMasterID شناسه طرح مطالعاتی
               ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
               ApplicantMasterIDsurat شناسه طرح صورت وضعیت
               BankCode کد رهگیری طرح
               ApplicantMasterID شناسه طرح
               operatorcoID شناسه پیمانکار
                */
                 $sqlin="SELECT applicantmasterall.LastTotal,applicantmasterall.creditsourceid,applicantmasterall.criditType
                    from applicantmaster applicantmasterop         
                    inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode 
                    and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
                    inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
                    and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID
                    where applicantmasterop.BankCode='$count[BankCode]'  and  ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0";
                    try 
                      {		
                        $countin = mysql_fetch_assoc(mysql_query($sqlin));
                      }
                      //catch exception
                      catch(Exception $e) 
                      {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                      }
                    if ($creditsourceid<=0)//منبع تامین اعتبار
                        $creditsourceid = $countin['creditsourceid'];
                        
                        
                    if ($criditType<=0)//تجمیع بودن
                        $criditType = $countin['criditType'];
        			//$login_RolesID==2 پیمانکار	
                    //$countin['LastTotal'] کل مبلغ مصوب
                    //$count['LastTotal'] کل مبلغ صورت وضعیت
                    if (($count['LastTotal']>$countin['LastTotal'])&& $login_RolesID==2)
                    {
                        $errmosavab="مبلغ صورت وضعیت بیشتر از مبلغ مصوب طراحی می باشد";
                        
                        
                    }
            }
    }    
?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	
<STYLE >
p {
    margin: 0;
    padding: 0;
}
 p.page {
    page-break-after: always;
   }
   
table tr.page-break{
  page-break-before:always
} 
</STYLE>
	
    <style>
    
	

.f1_font{
	border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                       
}
.f1R_font{
	border:0px solid black;text-align:right;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                       
}
.f2_font{
	border:0px solid black;width: 10%;                        
}
.f3_font{
	border:0px solid black;border-color:#000000 #000000;text-align:left;font-size:8pt;line-height:100%;font-family:'B zar'; 
}
.f4_font{
	border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                        
}
.f5_font{
    border:0px solid black;width: 80%;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f6_font{
    border:0px solid black;width: 5%;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f7_font{
    border:0px solid black;width: 10%;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f8_font{
    border:0px solid black;width: 90%;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f9_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f10_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
}
.f11_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fontn{
    background-color:#ffffcc;border-right: 0px;border-left: 0px;border-top: 0px;border-bottom: 0px;border-color:#0000ff #0000ff;text-align:center;font-size:11.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fontnR{
    background-color:#ffffcc;border-right: 0px solid #ff0000;border-left: 0px ;border-top: 0px;border-bottom: 0px;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fontnRf{
    background-color:#ffffff;border-right: 0px solid #ff0000;border-left: 0px ;border-top: 0px;border-bottom: 0px;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fonttR{
    border-right: 0px;border-left: 1px solid #ff0000;border-top: 0px ;border-bottom: 1px solid #ff0000;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fontt{
    border-right: 0px;border-left: 0px;border-top: 0px;border-bottom: 1px solid #ff0000;;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fonttm{
    border-right: 0px;border-left: 0px;border-top: 0px;border-bottom: 1px solid #ff0000;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f11_fontth{
   text-decoration: line-through; border-right: 0px;border-left: 0px;border-top: 0px;border-bottom: 1px solid #ff0000;;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}.f12_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:110%;font-weight: bold;font-family:'B Nazanin';
}
.f13_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;height:20px;font-weight: bold;font-family:'B Nazanin';
}
.f13_fonts{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:9.0pt;line-height:95%;height:20px;font-weight: bold;font-family:'B Nazanin';
}
.f14_font{
    vertical-align:middle;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f15_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;width: 130px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f16_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f17_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f18_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f19_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:300%;font-weight: bold;font-family:'B Nazanin';
}
.f20_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f21_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;vertical-align: middle;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
}
.f21_fontr{
    background-color:#fecde3;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}.f26_fontr{
    background-color:#fecde3;width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';vertical-align: middle;
}
.f25_fontr{
    background-color:#fecde3;width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}

.f22_font{
    background-color:#b0eab9;width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f23_font{
    width: 350px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f24_font{
    width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f25_font{
    width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f26_font{
    width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';vertical-align: middle;
}
.f27_font{
    width: 80px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f28_font{
    width: 200px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';

}
.f29_font{
    width: 100px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f30_font{
    width: 80px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f31_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:16.0pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';
}
.f32_font{
    width: 550px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f33_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 400px;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f34_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;width: 195px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f35_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f36_font{
    border-left: 1px solid black;border-color:#0000ff #0000ff;
}
.f37_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;width: 120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f38_font{
    border-bottom: 1px solid black;border-left: 1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f39_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f40_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f41_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;width: 100%;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f42_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f37L_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f43_font{
    border-left: 1px solid black;border-color:#0000ff #0000ff;
}
.f43_fontR{
    border-left: 1px solid black;border-right: 1px solid black;border-color:#0000ff #0000ff;
}
.f44_font{
    border:0px solid black;background-color:#ffff00;text-align:center;border-color:#0000ff #0000ff;width: 30px;font-size:11.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f44_fontw{
    border:0px solid black;background-color:#ffffff;text-align:center;border-color:#0000ff #0000ff;width: 30px;font-size:11.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}.f45_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f46_font{
    background-color:#ffff00;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f47_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:16.0pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';
}
.f48_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f49_font{
    background-color:#b0eab9;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f50_font{

    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f51_font{
    background-color:#ffff00;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width:120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f510_font{
    background-color:#ffff00;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;width:350px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f52_font{
    border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px;
}
.f53_font{
    width: 300px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f54_font{
    width: 215px;background-color:#b0eab9;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';
}
.f55_font{
    width: 20px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';
}
.f56_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:500%;font-weight: bold;font-family:'B Nazanin';
}


.checkbox {
  margin: 0 0 1em 2em;
}
.checkbox .tag {
  color: #595959;
  display: block;
  float: left;
  font-weight: bold;
  position: relative;
  width: 120px;
}
.checkbox label {
  display: inline;
}
.checkbox .input-assumpte {
  display: none;
}
.input-assumpte + label {
  -webkit-appearance: none;
  background-color: #ffffff;
  border: 1px solid #cacece;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
  padding: 6px;
  display: inline-block;
  position: relative;
}
.input-assumpte:checked + label:after {
  background-color: #ff0000;
  color: #ff0000;
  content: '\2714';
  font-size: 10px;
  left: 0px;
  padding: 2px 2px 2px 2px;
  position: absolute;
  top: 0px;
}

      </style>
	<!-- scripts -->


    <!-- /scripts -->
</head>
<body>

 <script>

function tempvalchange()
{
    document.getElementById('othercosts5').value=document.getElementById('seltotaltemp').value;  
}




function numberWithCommas(x) {
    /*if (x.substr(0, 1)=='-')
    {
        x = x.replace('/-','/');
        
        return '-'+x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
    }*/
    
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function convert(aa) {

        
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
 </script>
   
    <script>

    
    
function p_tarkib(_value)
{
 var _len;var _inc;var _str;var _char;var _oldchar;_len=_value.length;_str='';
 for(_inc=0;_inc<_len;_inc++)
 {
   _char=_value.charAt(_inc);
   if (_char=='1' || _char=='2' || _char=='3' || _char=='4' || _char=='5' || _char=='6' || _char=='7' || _char=='8' || _char=='9' || _char=='0' || _char=='-') 
      _str=_str+_char;
   else
      if (_char!=',') return 'error';
 }
 return _str;
}

function calc2()
{
    var cnt=1;
    var sumy=0,sump=0;
    while (document.getElementById('yeild'+cnt))
    {
		if (document.getElementById('yeild'+cnt).value) sumy++;
	    cnt++;
    }    
    document.getElementById('cntyield').value=sumy;
	}

function calc1()
{
    var cnt=1;
    var sumh=0,sump=0;
    while (document.getElementById('syshek'+cnt))
    {
        sumh+=document.getElementById('syshek'+cnt).value*1;
        cnt++;
    }    
    document.getElementById('totsyshek').value=Math.round(sumh*100)/100 ;
	}
function CheckForm()
{
    
       if (document.getElementById('row2ProducersID').value=='')
        {
			return confirm('پیشنهاد قیمت لوله پلی اتیلن انجام نشده است\n تغییرات ذخیره گردد؟');
        }
 	
	var cnt=1;
    var sumh=0,sump=0;
    while (document.getElementById('syshek'+cnt))
    {
        sumh+=document.getElementById('syshek'+cnt).value*1;
        cnt++;
    }
    if (document.getElementById('Description'))
    {
        if (document.getElementById('Description').value=='')
        {
       	    
			return confirm('جهت تغییر وضعیت توضیحات را تکمیل نمایید\nتغییرات ذخیره گردد؟');
        }
    }
      
	   if (document.getElementById('highprice').value=='1')
        {
			if (document.getElementById('ApplicantstatesID').value=='43')
				{
					alert('هزینه تیپ بیشتر از سقف می باشد \n لطفا با مدیریت آب و خاک تماس گرفته شود.');
					return false;
				}
		 
			else	
			return confirm('هزینه تیپ بیشتر از سقف می باشد \n لطفا با مدیریت آب و خاک تماس گرفته شود.');
		}
   

    
    if ((document.getElementById('RolesID').value!=2) || (document.getElementById('RolesID').value==9) )
	{
		if(cnt>1)
		{
			if (round(document.getElementById('DesignArea').value,1)!=round(sumh,1)) 
			{
				alert('جمع هکتار سیستم های آبیاری با مساحت طراحی کل طرح برابر نمی باشد');
			//	alert(sumh);
				
				return false;
			}
			
			//if (p_tarkib(document.getElementById('AllSumAll').value)!=sump) 
		   // {
		   //     alert('جمع مبالغ سیستم های آبیاری با جمع کل هزینه های طرح برابر نمی باشد');
		   //     return false;
		   // }
		}
		/*if (document.getElementById('cntyield').value<1 && (document.getElementById('RolesID').value==9 || document.getElementById('RolesID').value==2)) 
        {
            alert('نوع محصول را مشخص نمایید');
            return false;
        }*/
	}  
    return confirm('مطمئن هستید که تغییر  اعمال شود ؟');;
}

    
function summ()
{	
    if(document.getElementById('coef1').value>1.3) {alert('ضریب  بالاسری بزرگتر از حد مجاز میباشد!');return false;}
    if(document.getElementById('coef2').value>1.05) {alert('ضریب  تجهیزوبرچیدن بزرگتر از حد مجاز میباشد!');return false;}
    if(document.getElementById('coef3').value>2) {alert('ضریب  پیشنهادی بزرگتر از حد مجاز میباشد!');return false;}
    if(document.getElementById('coef4').value>1.09) {alert('ضریب  ارزش افزوده بزرگتر از حد مجاز میباشد!');return false;}
    
    document.getElementById('sumcoststotalh').value=0;
    
    for (var i=1;i<=document.getElementById('cntf').value;i++)
    {
        var coef5=1;
        if (document.getElementById('coef5'+i))
            coef5=document.getElementById('coef5'+i).value;
        else
            coef5=document.getElementById('coef51').value;
        
        var curval=Math.round(document.getElementById('coef1').value*1*document.getElementById('coef2').value*1*document.getElementById('coef3').value*1
                                *coef5*1*p_tarkib(document.getElementById('sumcosts'+i).value));    
            
        document.getElementById('SumPricecosts'+i).value=numberWithCommas(curval);    
        document.getElementById('sumcoststotalh').value=document.getElementById('sumcoststotalh').value*1+curval*1;
    }
    
    if (document.getElementById('sumcoststotal'))
        document.getElementById('sumcoststotal').value=numberWithCommas(document.getElementById('sumcoststotalh').value);
    
    
    //alert(document.getElementById('sumcosts').value);
    
    //alert(document.getElementById('sumcosts').value);
    
    
        document.getElementById('SumPricecoef4').value=numberWithCommas(Math.round(
        (p_tarkib(document.getElementById('sumcoststotalh').value)*1+p_tarkib(document.getElementById('lasttajhiz').value)*1)
        *document.getElementById('coef4').value*1)
        );
    
    
    var othercosts1='';
    var othercosts2='';
    var othercosts3='';
    var othercosts4='';
    var othercosts5='';
    var unpredictedval='';
    if (document.getElementById('othercosts1'))
        othercosts1=document.getElementById('othercosts1').value;
    if (document.getElementById('othercosts2'))
        othercosts2=document.getElementById('othercosts2').value;
    if (document.getElementById('othercosts3'))
        othercosts3=document.getElementById('othercosts3').value;
    if (document.getElementById('othercosts4'))
        othercosts4=document.getElementById('othercosts4').value;
    if (document.getElementById('othercosts5'))
        othercosts5=document.getElementById('othercosts5').value;
    if (document.getElementById('unpredictedval'))
        unpredictedval=document.getElementById('unpredictedval').value;
    
    document.getElementById('AllSumAll').value=
    numberWithCommas(
    Math.round(
    p_tarkib(document.getElementById('SumPricecoef4').value)*1+
    document.getElementById('TotlainvoiceValues').value*1+
    p_tarkib(unpredictedval)*1+
    p_tarkib(othercosts1)*1+
    p_tarkib(othercosts2)*1+
    p_tarkib(othercosts3)*1+
    p_tarkib(othercosts4)*1+
    p_tarkib(othercosts5)*1
    )
    )
    ;
    
//alert (document.getElementById('TpC').value);

        
        
    
            if (document.getElementById("TransportCostunder"))
            {
                document.getElementById('AllSumAll').value=p_tarkib(document.getElementById('AllSumAll').value);
                document.getElementById('TpC').value=p_tarkib(document.getElementById('TpC').value);
    			if (document.getElementById('transportless').checked) 
    			{
                    document.getElementById('TransportCostunder').value=0;	
    			}	
    			else
    			{
    				document.getElementById('AllSumAll').value=
    				(document.getElementById('AllSumAll').value*1+p_tarkib(document.getElementById('TpC').value)*1);
                    document.getElementById('TransportCostunder').value=numberWithCommas(document.getElementById('TpC').value);	
    			}
                document.getElementById('AllSumAll').value=numberWithCommas(document.getElementById('AllSumAll').value);                  
            }
        
            
				  
                                                                          
    
    document.getElementById('AllSumAllnotround').value=p_tarkib(document.getElementById('AllSumAll').value);
    if (document.getElementById('digitless'))
        if (document.getElementById('digitless').checked) 
            digit();
}
function digit()
{
    document.getElementById('AllSumAll').value=p_tarkib(document.getElementById('AllSumAll').value);
    if (document.getElementById('digitless').checked) 
    {
        document.getElementById('AllSumAllnotround').value=document.getElementById('AllSumAll').value;
        document.getElementById('AllSumAll').value=numberWithCommas(Math.floor((document.getElementById('AllSumAll').value)/100000)*100000);   
    }
    else
    {
        document.getElementById('AllSumAllnotround').value=p_tarkib(document.getElementById('AllSumAllnotround').value);
        document.getElementById('AllSumAll').value=numberWithCommas(Math.round((document.getElementById('AllSumAllnotround').value
																),1));
    }
}
function selectpage()
{
    vsubprj=0;
    if (document.getElementById('subprj'))
    {
        vsubprj=document.getElementById('subprj').value;
    }
        
    var vshowm=0;
    if (document.getElementById('showm'))
    {
        
        if (document.getElementById('showm').checked) 
            vshowm=1;
        else vshowm=0;
    }
    else vshowm=1;
    
    vshowotherz=0;
    if (document.getElementById('showotherz'))
    {
        if (document.getElementById('showotherz').checked) 
        vshowotherz=1;
    }
    
    window.location.href ='?uid=' +document.getElementById('uid').value+ '&showm=' + vshowm+'&showotherz=' + vshowotherz+'&subprj=' + vsubprj;
}
function pagebreakallsh()
{
    var tables = document.getElementsByTagName("TABLE");
    for (var i=tables.length-1; i>=0;i-=1)
        if ((tables[i].id).substring(0, 3)=='sh_')
        {
            setpagereak('p'+tables[i].id);
            if (document.getElementById('chk'+tables[i].id).checked)
                document.getElementById('chk'+tables[i].id).checked=false;
            else
                document.getElementById('chk'+tables[i].id).checked=true;
                
                
        }    
}

 
function setpagereaktr(id)
{
 //document.getElementById("footer").style.pageBreakAfter = "always";
    //alert( document.getElementById(id).style);
//	var elem = document.getElementById(id);
	//style='page-break-before: always'
    if (document.getElementById(id).style.pageBreakBefore == 'always')
        document.getElementById(id).style.pageBreakBefore = "";
    else
        document.getElementById(id).style.pageBreakBefore = 'always';
    
}

function setpagereak(id)
{
    //alert(document.getElementById(id).className);
    if (document.getElementById(id).className=="page")
        document.getElementById(id).className = "";
    else
        document.getElementById(id).className = "page";
    
}
function  showallsh()
{
    var tables = document.getElementsByTagName("TABLE");
    for (var i=tables.length-1; i>=0;i-=1)
        if ((tables[i].id).substring(0, 3)=='sh_')
        {
            //alert(tables[i].id);
            showhidediv(tables[i].id);
            
        }
}	
function showhidediv(id)
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







function showdiv(id)
{

    //alert('ss');
	if(id=='othercosts')
	var elem = document.getElementById(id);
	else
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
     
	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
            if ($_POST){
					if ($register){
						$Serial = "";
						$ProducersID = "";
                        //header("Location: summaryinvoicemaster.php");
                        exit(); 
                        
					}else{
						print '<label class="error">خطا در ثبت...</label>';
					}
				}
                ?>
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
            
            <form action="summaryinvoice.php" method="post" onSubmit="return CheckForm()">
                    <tbody>
                
  <?php
    $highprice=0; 
    
    $query="select Title _key, appsubprjID _value from appsubprj where ApplicantMasterID='$ApplicantMasterID'
    order by  _key COLLATE utf8_persian_ci";
                    
    $ID = get_key_value_from_query_into_array($query);
    if (count($ID)>1) 
    $globalprint=select_option('subprj',"<font color='red'>زیر پروژه</font>",',',$ID,0,'','','1','rtl',0,'',$subprj,"onChange='selectpage()'",'135');
                     
  
                $globalprint.= "<table width=\"95%\" align=\"left\">";
                
  
    $globalprint.= "<div style = 'text-align:left;' class='no-print'>";
    
	if ($showm>0) $gc= "checked";else $gc="";
    if (! in_array($login_RolesID, $permitrolsidforviewdetail) || $showm>0) 
    $globalprint.= "<input title='نمایش همه صفحات' class='no-print' name='showm' type='checkbox' id='showm' onChange='selectpage()' value='$showm' $gc />"; 
	
    
    if ($type!=1)
		$globalprint.= "
         <a onclick='window.print()'><img  class='no-print' style = 'width: 2%;' src='../img/print.png' title='چاپ' ></a>";
   if (in_array($login_RolesID, array("1","10","11","12","13","14","19")) || $type==1 || $type==5) 
   {
        $globalprint.= "<a target='_blank'  href='sinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_$count[ApplicantFName] $count[ApplicantName]_$count[shahrcityname]_$count[DesignArea]_$count[BankCode]_$count[costpricelistmasterID]_$count[cityid]_0".
                            rand(10000,99999)."'><img class='no-print' style = 'width: 25px;' src='../img/comment.png' title=' خلاصه پروژه '></a>
                            <a target='_blank'  href='../appinvestigation/applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_1_".$DesignerCoID."_".$operatorcoid.rand(10000,99999)."'>
                            <img class='no-print' style = 'width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a>
                            
                            <a target='_blank' class='no-print' href=summaryinvoice_changed.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.
                            rand(10000,99999).">
                            <img class='no-print' style = 'width: 25px;' src='../img/folder_accept.png' title='اصلاحات درخواستی مشاور بازبین'>
                            </a>
                            
                            <a target='_blank' class='no-print' href=../appinvestigation/opchangestodesign.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$BankCode.
                            rand(10000,99999).">
                            <img class='no-print' style = 'width: 25px;' src='../img/accept_page.png' title='تغییرات'>
                            </a>
                            
                            
                            <a  target='_blank' href=equip_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_10_0_0_".rand(10000,99999)."'>
                            <img class='no-print' style = 'width: 25px;' src='../img/protection.png' title=' تجهیز و برچیدن کارگاه '></a>
                            <a target='_blank' href='foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_0_سازه های_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/saze.png' title='سازه های طرح'></a>
                            
                            <a target='_blank' href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fm.png' title=' فهرست بهای دستی'></a>
                            <a target='_blank' href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_2'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fs.png' title='  فهارس بها '></a>".
                            
                            "<a  target='_blank' href='../appinvestigation/allinvoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    $ApplicantMasterID.'_0_0_'.$login_CityId.rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/full_page.png' title='لیست پیشفاکتورها'></a>";
                                    
                                    if ($OperatorCoID>0)
                                    {
                                        $globalprint.="<a target='_blank' href='foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1_کارهای قیمت جدید_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/saze2.png' title='کارهای قیمت جدید طرح'></a>";
                                    }
                                    
                                    if ($DesignerCoID>0)
                                    
                                    "<a 
                                   target='_blank' href='../appinvestigation/applicant_form10.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/mail_send.png' title='فرم تاییدیه کمیته فنی'></a>";
                                    
                            
        if ($operatorcoid>0 && !($issurat))
            $globalprint.= "<a  target='_blank'  href='../appinvestigation/invoicemasterfree_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.'_2_0_'.$operatorcoid
                            .'_'.$count['operatorcoTitle'].rand(10000,99999).
                            "'><img class='no-print' style = 'width: 25px;' src='../img/mail_send.png' title='ليست آزادسازي'></a>";
    }                                
    $globalprint.= "      
         
         <a  href='$masterpagename'><img class='no-print' style = 'width: 2%;' src='../img/Return.png' ></a> 
         <input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  value='$ApplicantMasterID'   />
         <input class='no-print' name='applicantmasterdetailID' type='hidden' class='textbox' id='applicantmasterdetailID'
                     value='$ApplicantMasterdetailID'  />  
         
         <input class='no-print' name='operatorcoid' type='hidden' class='textbox' id='operatorcoid'
                     value='$operatorcoid'  />
                                
         <input class='no-print' name='RolesID' type='hidden' class='textbox' id='RolesID'  value='$login_RolesID'  /> 
         <input class='no-print' name='uid' type='hidden' class='textbox' id='uid'  value='$uid'  /> 
         <input class='no-print' name='sref' type='hidden' class='textbox' id='sref'  value='$_SERVER[HTTP_REFERER]'  /> 
         <input class='no-print' name='issurat' type='hidden' class='textbox' id='issurat'  value='$issurat'  /> 
         <input class='no-print' name='prjtypeid' type='hidden' class='textbox' id='prjtypeid'  value='$prjtypeid'  /> 
         
         
					  <input name='DesignArea' type='hidden' class='textbox' id='DesignArea'  value='$DesignArea'   />
                     <input name='wincoef3' type='hidden' class='textbox' id='wincoef3'  value='$wincoef3'   />
                     <input name='TotlainvoiceValues' type='hidden' class='textbox' id='TotlainvoiceValues'  value='$TotlainvoiceValues'   />
                     <input name='typestr' type='hidden' class='textbox' id='typestr'  value='$type'   />
                     <input name='selectedoperatorcoIDbandp' type='hidden' class='textbox' id='selectedoperatorcoIDbandp'  value='$operatorcoIDbandp'   />
                     
                      
                </div>
                ";
     
       
       if ($type!=12)
       {
     
     
            if ($issurat==1)
            {$titles="✔ صورت وضعیت "; $nazer="مشاور ناظر";}
            else if ($OperatorCoID>0)
            {$titles="✔ پیش فاکتور"; $nazer="مشاور ناظر";}
    		
            else
            {$titles="✔ لیست لوازم ";$nazer="مشاور بازبین";}
            $printdate=compelete_date(gregorian_to_jalali(date('Y-m-d')));
            if (in_array($login_RolesID, $permitrolsidforviewdetail)) 
            {
                
                $globalprint.= "<div style = 'text-align:center;'> <tr >
						<td > 
                           <input title='نمایش صفحات' class='no-print' name='showall' type='checkbox' id='showall' onChange='showallsh()'/>	
					       <input title='چاپ صفحه ای' class='no-print' name='pagebreakall' type='checkbox' id='pagebreakall' onChange='pagebreakallsh()'/>		 
						</td>       
                            <td  class='f1R_font'>$titles و هزینه های اجرایی طرح $ApplicantName </td>
                            <td  class='f6_font'> </td>
                            <td>&nbsp;</td> 
                
					    </tr>
                        <tr >
                         <td colsan=2>&nbsp;</td> 
                        <td style = 'text-align:center;' class='f6_font'>".str_replace(' ', ' ', "تاریخ چاپ: $printdate")."</td>
                        </tr >
                         </div >
                        ";
            }
         if ($issurat==1)
            $titles="✔ صورت وضعیت ";
            else
            $titles="";  
      
      
      
      $arrayinvoices = array();
      $arrayindexinvoice=0;
      $arrayindexinvoicedone=0;
      $arrayinvoicesdone = array();
        ////////////////بخش چاپ پیش فاکتور/لیست لوازم ها
        $condlimited='';
           
            if ($type==11)
            {
				//$prjtypeid==0
                //print $InvoiceMasterIDselectedp;exit;
                if ($InvoiceMasterIDselectedp>0)
                $pcond=" and invoicemaster.InvoiceMasterID='$InvoiceMasterIDselectedp'";
                else 
                $pcond=" and ifnull(invoicemaster.proposable,0)=1";
            }
            else $pcond="";
                
                //    print $pcond;
           if ($type==13 && $login_isfulloption!=1)
           {
                $pcond="  and gadget3.gadget2id in (495,494,202,376)";
           }
            if ($DesignerCoID>0) $condlimited=' and pfd=1 '; 
            
            $appmasterinv=0;
            if ($ApplicantMasterIDmaster>0)
            {
            $sql2="select PE32app,PE40app,PE80app,PE100app,ProducersID,transportless from producerapprequest where state=1 
            and ApplicantMasterID='$ApplicantMasterIDmaster'";
            
            $appmasterinv="left outer join invoicemaster invoicemastermaster on invoicemastermaster.invoicemasterID=invoicemaster.InvoiceMasterIDmaster";    
            }    
            
            else
            {
            $sql2="select PE32app,PE40app,PE80app,PE100app,ProducersID,transportless from producerapprequest where state=1 
            and ApplicantMasterID='$ApplicantMasterID'"; 
            
            $appmasterinv="left outer join invoicemaster invoicemastermaster on invoicemastermaster.invoicemasterID=invoicemaster.invoicemasterID"; 
            }   
           //print $sql2;exit;
            
            $result2 = mysql_query($sql2);
            $row2 = mysql_fetch_assoc($result2);
			
            $row2ProducersID=1;
			if ($OperatorCoID)
				$row2ProducersID=$row2['ProducersID'];
				
		//print 'www'.$row2ProducersID;	
            //{
                $guerypipeprice="left outer join (select '$row2[ProducersID]' ProducersID, '$row2[PE32app]' PE32,'$row2[PE40app]' PE40,'$row2[PE80app]' PE80,'$row2[PE100app]' PE100 )
                 pipeprice on pipeprice.ProducersID=toolsmarks.ProducersID";
            //}
            //else 
            //$guerypipeprice="left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=invoicemaster.InvoiceDate $condlimited) and pipeprice.ProducersID=toolsmarks.ProducersID"; 
            $prodcond="";
            if ($login_ProducersID>0)
            {
                $prodcond=" and gadget3.gadget3ID in (select toolsmarks.gadget3ID from toolsmarks where toolsmarks.ProducersID='$login_ProducersID')";
            }
            
            $proposabletransportless=$row2['transportless'];
            $PipeProducer=0;
        $sql = "
            SELECT toolsmarks.ToolsMarksID,ifnull(invoicemaster.pricenotinrep,0) pricenotinrep,invoicemaster.proposable,taxpercent.value taxpercentvalue,ifnull(invoicemaster.taxless,0) taxless,invoicemaster.costnotinrep,invoicemaster.pricenotinrep,invoicemaster.InvoiceMasterID,invoicemaster.ProducersID,invoicemaster.TransportCost,
        invoicemaster.Discont,invoicemaster.InvoiceDate,invoicemaster.Rowcnt,invoicemaster.Serial,
        case ifnull(invoicemaster.taxless,0) when 1 then invoicemaster.Title when 2 then concat('(.) ',invoicemaster.Title) else concat('(+) ',invoicemaster.Title) end Title  
        ,producers.Title as PTitle,producers.AccountNo,producers.AccountBank,producers.PipeProducer,invoicemaster.Description
		,toolsmarks.ProducersID,invoicedetail.InvoiceDetailID,gadget3.size11,
                gadget3.Code,gadget3.gadget3ID,gadget2.gadget2ID,toolsmarks.MarksID,units.title utitle,invoicedetail.Number,invoicedetail.val1,invoicedetail.val2,invoicedetail.val3,invoicedetail.deactive,pricelistdetail.pricelistdetailID
            ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT
			(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT
			(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' ')
			,ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),'')
			,ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') )))
			,ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' ')
			,ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' ')
			,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),'')
			,ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
             gadget3Title
            ,marks.Title as MarksTitle
            ,case gadget3.gadget2id 
            when 202 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE80 else pipeprice.PE80 end) 
            when 376 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE100 else pipeprice.PE100 end) 
            when 495 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE32 else pipeprice.PE32 end) 
            when 494 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE40 else pipeprice.PE40 end)
                else case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
                syntheticgoodsprice.price end  end Price,invoicemaster.PriceListMasterID
                
            ,case gadget3.gadget2id when 495 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe32num
            ,case gadget3.gadget2id when 494 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe40num
            ,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe80num
            ,case gadget3.gadget2id when 376 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe100num
            
            FROM invoicedetail 
            inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
            inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID $prodcond
            inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
            left outer join units on gadget3.unitsID=units.unitsID
            inner join marks on marks.MarksID=toolsmarks.MarksID
            inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID 
            and invoicemaster.ApplicantMasterID ='$ApplicantMasterID' $subprjcondition
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
            left outer join toolspref on toolspref.PriceListMasterID=invoicemaster.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            left outer join pricelistdetail on  pricelistdetail.PriceListMasterID=invoicemaster.PriceListMasterID and 
                                                pricelistdetail.toolsmarksID = (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
            
             left outer join syntheticgoodsprice on syntheticgoodsprice.PriceListMasterID=invoicemaster.PriceListMasterID and 
            syntheticgoodsprice.gadget3ID=gadget3.gadget3ID
            
            inner join producers on producers.ProducersID=invoicemaster.ProducersID $pcond
            
            $guerypipeprice
            left outer join pipeprice pipepricew on pipepricew.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=invoicemaster.InvoiceDate $condlimited) and pipepricew.ProducersID=toolsmarks.ProducersID
            $appmasterinv
            
                    
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            left outer join year on year.Value = substring(invoicemaster.InvoiceDate,1,4)
            left outer join taxpercent on year.YearID=taxpercent.YearID
            ORDER BY cast(invoicemaster.Serial as decimal),invoicedetail.invoicemasterID,invoicedetail.InvoiceDetailID
            ";
           //print $sql; exit;
      
        //print "در حال بروز رسانی! لطفا چند دقیقه دیگر مراجعه فرمایید.";exit;
        $pipeproposeval=0;
        $InvoiceMasterIDold=0;
        $result = mysql_query($sql.$login_limited);
      //print $sql.$login_limited; exit;
         
        while($resquery = mysql_fetch_assoc($result))
        {
            if ($resquery['PipeProducer']==1)
                $PipeProducer+=$resquery['PipeProducer'];
            if (!($resquery['Price']>0))
                $MarksTitle='';
                else
                $MarksTitle=$resquery['MarksTitle'];
				
     	if ($prjtypeid==1 && $resquery['gadget2ID']==202 && $resquery['size11']>125) 
			{
				print "لطفا قبل از تغییر وضغیت نوع لوله را صحیح انتخاب کنید. </br> درصورت نیاز به انتخاب لوله مذکور با مدیریت آب و خاک تماس بگیرید.
				</br> d>125PE100
				";
				if ($login_RolesID==17)
				exit;
			}
			
            if ($InvoiceMasterIDold<>$resquery['InvoiceMasterID'])
            {
                
                if ($InvoiceMasterIDold>0 )
                {
                    $totinv=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);
                    if (in_array($login_RolesID, $permitrolsidforviewdetail))
                    {
                        if (! $pricenotinrep)
                        {
                            $prep1="";
                            $prep2="";
                        }
                        else
                        {
                            $prep1=" <font color='red'> ";
                            $prep2=" </font> ";
                        }    
                        
                        $globalprint.= "  <tr>
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f12_font' >$prep1 جمع  (ریال)$prep2</td>
                                      <td colspan='2'  class='f16_font' >$prep1 ".number_format($sum)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>
                                      ";
                              if ($TransportCost>0)
                                      if (in_array($login_RolesID, $permitrolsidforviewdetail))
                                      $globalprint.= "<tr>
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f12_font' >$prep1 هزینه های جانبی( ریال ) $Description2 $prep2</td>
                        
                                      <td  colspan='2' class='f16_font' >$prep1 ".number_format($TransportCost)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>";
                            if ($Discont>0)
                                      if (in_array($login_RolesID, $permitrolsidforviewdetail)) $globalprint.= "<tr>
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f12_font' >$prep1 تخفیف/تعدیل( ریال ) $Description3 $prep2</td>
                                      
                                      <td  colspan='2' class='f16_font' >$prep1 ".number_format($Discont)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>
                                      ";
                            
                            if (in_array($login_RolesID, $permitrolsidforviewdetail)) $globalprint.= "
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f17_font' >$prep1 جمع  با ارزش افزوده(ریال)$prep2</td>
                                      
                                      <td colspan='2'  class='f18_font'>$prep1 ".number_format($totinv)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>
                                      </table>"; 
                    } 
                    
                                      
                                      /*$globalprint.= "
                                      <tr>
                                      <td colspan='1' class='f20_font' ></td>
                                      <td colspan='5' class='f12_font' >مالیات بر ارزش  (ریال)</td>
                                      
                                      <td colspan='2'  class='f16_font' >".number_format($TAXPercent*$sum/100)."</td>
                                      </tr>
                                      ";
                                      */
                                      
                                    
                
       
                    
                    if (! $pricenotinrep) 
                    {
                        $arrayindexinvoice++;
                        if ($operatorcoid>0)
                            $arrayinvoices[$arrayindexinvoice.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$totinv;  
                        else
                            $arrayinvoices[$arrayindexinvoice.'-'.$Title]=$totinv; 
                      mysql_query("update invoicemaster set tot='$totinv' where InvoiceMasterID='$InvoiceMasterIDold';");
                      /*
                      علیرضا محمدزاده
                      غلامرضا صبوري 
                      محمدرضا  جعفرنیا و شرکا 
                      
                      */
                      if ($proposable>0  &&  $resquery['pricenotinrep']==0 && $proposabletransportless>0
                &&  !in_array($ApplicantMasterID,array(2804,2138,2374)) )    
                      {
                            //print "sa";
                            $pipeproposeval+=$totinv;
                            
                      }  
                             
                    }
                    else
                    {
                        $arrayindexinvoicedone++;
                        if ($operatorcoid>0)
                            $arrayinvoicesdone[$arrayindexinvoicedone.'-'.$Title."($PTitle)"]=$totinv;  
                        else
                            $arrayinvoicesdone[$arrayindexinvoicedone.'-'.$Title]=$totinv; 
                    }
                }
    
                $proposable=$resquery['proposable'];
                $InvoiceMasterIDold=$resquery['InvoiceMasterID']; 
                $taxless=$resquery['taxless'];
                $masterProducersID = $resquery['ProducersID'];
                $TransportCost = $resquery['TransportCost'];
                $Discont = $resquery['Discont'];                        
                $np = $resquery['Rowcnt'];
                $Serial = $resquery['Serial'];
                $Title = $resquery['Title'];
                $AccountNo = $resquery['AccountNo'];
                $AccountBank = $resquery['AccountBank'];
                if ($MarksTitle=='')
                    $PTitle='';
                else
                    $PTitle = $resquery['PTitle'];
                
                $linearray = explode('_',$resquery['Description']);
                $Description=$linearray[0];
                if ($linearray[1]!='')
                $Description2="($linearray[1])";
                if ($linearray[2]!='')
                $Description3="($linearray[2])";
                
    
                $InvoiceDate = $resquery['InvoiceDate'];
                $pricenotinrep = $resquery['pricenotinrep'];
                $costnotinrep = $resquery['costnotinrep'];
                $PriceListMasterID=$resquery['PriceListMasterID'];
                
                //print "sa".$PriceListMasterID;
                
                $owncost='';
                if ($pricenotinrep) $owncost='خرید لوازم با هزینه شخصی متقاضی';
                if ($costnotinrep) $owncost.='  لوازم اجرا شده ';
                if ($owncost!='') $owncost="($owncost)";
                $TAXPercent=0;
                if (strlen($resquery['InvoiceDate'])>0 && $taxless==0)
                    $TAXPercent = $resquery['taxpercentvalue'];     
                    
                if (in_array($login_RolesID, $permitrolsidforviewdetail))        
                {
                    $fstr1="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/invoice/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
                            if (($ID==$InvoiceMasterIDold) && ($No==1) )
                                $fstr1="<a target='blank' href='../../upfolder/invoice/$file' ><img style = 'width: 30%;' src='../img/full_page.png' title='اسکن پیش فاکتور' ></a>";
                                
                                
                        }
                    }
                    $pr='';
                    if ($resquery['PipeProducer']!=1)
                    {
                     $queryPriceListMasterID = "
                    select CONCAT(month.Title,' ',year.Value) pr from pricelistmaster
                    inner join year on year.YearID=pricelistmaster.YearID
                    inner join month on month.MonthID=pricelistmaster.MonthID
                    WHERE   PriceListMasterID='$PriceListMasterID'";
                    $resultPriceListMasterID = mysql_query($queryPriceListMasterID);
    	            $rowPriceListMasterID = mysql_fetch_assoc($resultPriceListMasterID);
                    $pr=$rowPriceListMasterID['pr'];
                    if (strlen($pr)>0) $pr="($pr)";
                        
                    }
                    $limited = array("9");
                    if ( in_array($login_RolesID, $limited))
    	               $globalprint.= "
                       
	                   <p id='psh_invm$InvoiceMasterIDold' ></p>
                    <table id='ish_invm$InvoiceMasterIDold' width='$Rwidth%'>
                    <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" >
					<td  class='no-print'
                    onclick=\"showhidediv('sh_invm$InvoiceMasterIDold');\">
					
					".str_replace(' ', '&nbsp;', $Serial." : ".$Title." ".$owncost." ".$PTitle." ".$pr)."
                    
					</div></td>
                    </tr>
					</table>
					
                    
           <table  id='sh_invm$InvoiceMasterIDold'  style='display:none;' width='$Pwidth%'>
					
                     <tr>
                    <td colspan='7'><input class='no-print' id='chksh_invm$InvoiceMasterIDold' type='checkbox' onChange=\"setpagereak('psh_invm$InvoiceMasterIDold')\"/><label class='no-print'><font size='1'><font size='1'>چاپ در ابتدای صفحه</font></font></label></td>
					</tr>
				   <tr>
                                          <td colspan='7' class='f4_font'> $Title $owncost</td>
											<td></td>
                                               </tr><tr>
                                            <td class='f2_font'></td>
                                            <td colspan='7' class='f5_font'> $PTitle $pr</td>
											<td></td>
                                            <td class='f6_font' style = 'text-align:left'>     </td>
                                            <td class='f6_font'>     </td>
                                        </tr> ";
                    else $globalprint.= "
	                   <p id='psh_invm$InvoiceMasterIDold' ></p>
                    <table id='ish_invm$InvoiceMasterIDold' width='$Rwidth%'>
                    <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" >
					<td  class='no-print'
                    onclick=\"showhidediv('sh_invm$InvoiceMasterIDold');\">
					
					".str_replace(' ', '&nbsp;', $Serial." : ".$Title." ".$owncost." ".$PTitle." ".$pr)."
                    
					</div></td>
                    </tr>
					</table>
					
                    
                    <table  id='sh_invm$InvoiceMasterIDold'  style='display:none;' width='$Pwidth%'>
					
                                        <tr>
						<td colspan='4'><input class='no-print' id='chksh_invm$InvoiceMasterIDold' type='checkbox' onChange=\"setpagereak('psh_invm$InvoiceMasterIDold')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
						</tr><tr><td></td>
					                        <td colspan='7' class='f4_font'> $Title $owncost</td>
											<td></td> 
                                            <td class='f6_font' style = 'text-align:left'>   شماره:  </td>
                                            <td class='f6_font'>    $Serial   </td>
                                        </tr><tr>
                                            <td ></td>
                                            <td colspan='7' class='f5_font'> $PTitle $pr</td>
											<td></td>
                                            <td class='f6_font' style = 'text-align:left'>   تاریخ :  </td>
                                            <td class='f6_font'>    $InvoiceDate </td>
                                        </tr> ";
                    
                    
						//if ($login_RolesID==1) print 'sa'.$fstr1.$InvoiceMasterID;
						if ($fstr1!='' || $Description!='')
							$globalprint.= "<tr>
                                            <td ></td>
                                            <td colspan='9' class='f6_font'>توضیحات: $Description </td>
                                            
                                            <td class='f6_font'>اسکن:    $fstr1 </td>
                                        </tr> ";
                    
                    }
                    
                       // print "salam -$proposabletransportless- $resquery[proposable];";
                    if ($resquery['proposable']>0 && $resquery['pricenotinrep']==0 && $proposabletransportless>0 
                    && in_array($login_RolesID, $permitrolsidforviewdetail) && ($issurat!=1))
                    {
                        $globalprint.= "<tr>
                                            <td ></td>
                                            <td colspan='5' class='f5_font'>  هزینه حمل در فی لوازم لحاظ شده است.</td>
                                        </tr>
                                         ";   
                    }
                    else if ($resquery['PipeProducer']==1 && in_array($login_RolesID, $permitrolsidforviewdetail)  && ($issurat!=1) && ($OperatorCoID>0))
                    $globalprint.= "
                    <tr>
                                            <td ></td>
                                            <td colspan='5' class='f5_font'>  هزینه حمل در فی لوازم لحاظ شده است - فاقد پیشنهاد قیمت</td>
                                        </tr>
                    
                    <tr>
                        <!--<td colspan='2'            class='f7_font'>   توضیحات:  </td>
                        <td colspan='4' class='f8_font'> $Description </td>
                        -->
                        </tr>
                          
                    ";   
                    else if (in_array($login_RolesID, $permitrolsidforviewdetail) && ($issurat!=1) && ($OperatorCoID>0) && $resquery['PipeProducer']==1) $globalprint.= "
                    <tr>
                                            <td ></td>
                                            <td colspan='5' class='f5_font'>  هزینه حمل در فی لوازم لحاظ شده است. </td>
                                        </tr>
                    
                    <tr>
                        <!--<td colspan='2'            class='f7_font'>   توضیحات:  </td>
                        <td colspan='4' class='f8_font'> $Description </td>
                        -->
                        </tr>
                          
                    ";   
                    $cnt=0;
                    $rown=0;
                    $sum=0;
                    if (in_array($login_RolesID, $permitrolsidforviewdetail))
                    $globalprint.= "<tr>
                                         <td class='print'  class='f9_font' ><div style=\"width:  $Rmargin;\"></div> </td>
                                        <th colspan=2 align='center' class='f10_font' >ردیف </th>
                                        <th align='center' class='f10_font'>شرح </th>
                                        <th align='center' class='f10_font'>مارک</th>
                                        <th align='center' class='f10_font'>واحد</th>
                                        <th colspan=3 align='center' class='f10_font'>مقدار</th>
                                        <th align='center' class='f10_font'>فی(ریال)</th>
                                        <th align='center' class='f10_font'>جمع (ریال)</th>
									    <td class='print'  class='f9_font' ><div style=\"width:  $Lmargin;\"></div> </td>
                                   	
                                    </tr>";
            }
                if ($resquery['proposable']>0  &&  $resquery['pricenotinrep']==0 && $proposabletransportless>0
                &&  in_array($ApplicantMasterID,array(2804,2138,2374)) )
                {
                    $pipeproposeval+=$resquery['Number']*$resquery['Price'];
                    //print "<br>($pipeproposeval)";
                }
                
                $InvoiceDetailID = $resquery['InvoiceDetailID'];
                $Code = $resquery['Code'];
                $gadget3ID = $resquery['gadget3ID'];
                $gadget2ID = $resquery['gadget2ID'];
                $gadget1ID = $resquery['gadget1ID'];
                $ProducersID = $resquery['ProducersID'];
                
                //if ($resquery['MarksTitle']!='--' && $resquery['MarksTitle']!='..')
                
                $utitle = trim($resquery['utitle']);
                $gadget3Title = $resquery['gadget3Title'];
                $Number = str_replace(",","",number_format($resquery['Number']));
                $Number = $resquery['Number'];
                
                $val1 = ($resquery['val1']);
                $val2 = ($resquery['val2']);
                $val3 = ($resquery['val3']);
                $deactive = ($resquery['deactive']);
                $Price = number_format($resquery['Price']);
                $SumPrice = number_format($resquery['Number']*$resquery['Price']);
                
                $linearray = explode('_',$resquery['Description']);
                $Description=$linearray[0];
                if ($linearray[1]!='')
                $Description2="($linearray[1])";
                if ($linearray[2]!='')
                $Description3="($linearray[2])";
                
                $sum+=$resquery['Number']*$resquery['Price'];     
                
                $readonlydesc='readonly';   
                if (($type==3) && ($login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ||$login_RolesID==4))
                $readonlydesc='';     
                    
                
                if ($Number>0)
                {
                       if ($login_userid==4) $msg="-$resquery[ToolsMarksID] -$gadget3ID -$ProducersID -$resquery[MarksID]";
                    $rown++;
                    if (in_array($login_RolesID, $permitrolsidforviewdetail)) 
                    {   
                        $chk='';
                        //if ($readonlydesc=='')
					if ($deactive>0 )
                                $chk="<div class=\"checkbox\">
                                
						<input   type=\"checkbox\" class=\"input-assumpte\" name='chk$InvoiceDetailID' id='chk$InvoiceDetailID' $readonlydesc  checked>
						<label title='هزینه اجرایی حذف شده' for='chk$InvoiceDetailID'></label>
						</div>";
                    else
                                $chk="<div class=\"checkbox\">
						<input   type=\"checkbox\" class=\"input-assumpte\" name='chk$InvoiceDetailID' id='chk$InvoiceDetailID' $readonlydesc  >
						<label title='حذف هزینه اجرایی' for='chk$InvoiceDetailID'></label>
						</div>";
						
                    $globalprint.= " 
						<tr>
                                <td rowspan='2' class='print'  class='f9_font'></td>
                                <td rowspan='2' class='f11_font'><div style=\"width:  $c1margin;\">$msg $chk </div></td>
                                <td rowspan='2' class='f11_font'>$rown </td>
                                <td rowspan='2' class='f12_font'>$gadget3Title</td>
                                <td rowspan='2' class='f13_fonts'>$MarksTitle</td>
                                <td rowspan='2' class='f13_fonts'>$utitle</td>
                                <td colspan='3'  class='f11_fontn'><div id='divNumber$InvoiceDetailID' >      
									<input  $readonlydesc  name='Number$InvoiceDetailID' type='text' class='f11_fontn'  
									      id='Number$InvoiceDetailID'     value='$Number'  /></div></td>
                                <td rowspan='2' class='f11_font'>$Price</td>
                                <td rowspan='2' class='f11_font'>$SumPrice</td>
								<td></td>
                     	</tr>";
                    
                   if ($gadget2ID==194) 
                   {
                    if ($DesignerCoID>0 && $resquery['Price']<1300)
                        $highprice=1;
                    else
                    
                    if ($resquery['Price']>1150) $highprice=1;
                   }
                   
			                
    				$th1='';$th2='';$th3='';
    			    if ($val1>0 && $Number<>$val1)  $th1='h';
    				if ($val2>0 && $Number<>$val2) $th2='h';
    				if ($val3>0 && $Number<>$val3) $th3='h';
                 
                        if ( ($val1>0 && $Number<>$val1) || ($val2>0 && $Number<>$val2) || ($val3>0 && $Number<>$val3) )
                        {
                            if (!($val3>0) ) $val3='';
                    $globalprint.= "<tr>
                        <td class='f11_fontt$th1'><font color='blue'>$val1 </font></td>
                        <td class='f11_fontt$th2'><font color='red'>$val2 </font></td>
                        <td class='f11_fontt$th3'><font color='green'>$val3 </font></td>
                    </tr>
                    ";                         
                        }
    
                    else
                    $globalprint.= "<tr>
                        <td class='f11_fontt'><font color='blue'></font></td>
                        <td class='f11_fontt'><font color='red'></font></td>
                        <td class='f11_fontt'><font color='green'></font></td>
                    </tr>
                    "; 
                    
                               
                    }
      
                    
                } 
            
                
            
               
            
            
        
        }
        $totinv=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);
        if (in_array($login_RolesID, $permitrolsidforviewdetail))
        {
             if (! $pricenotinrep)
             {
                $prep1="";
                $prep2="";
             }
             else
             {
                $prep1=" <font color='red'> ";
                $prep2=" </font> ";
             } 
                        
            $globalprint.= "  <tr>
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f12_font' >$prep1 جمع  (ریال)$prep2</td>
                                      <td colspan='2'  class='f16_font' >$prep1".number_format($sum)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>
                                      ";
            if ($TransportCost>0)
                                      if (in_array($login_RolesID, $permitrolsidforviewdetail))
                                      $globalprint.= "<tr>
                                      <td class='print' colspan='1'   ></td>
                                      <td colspan='8' class='f12_font' >$prep1 هزینه های جانبی(ریال ) $Description2 $prep2</td>
                        
                                      <td  colspan='2' class='f16_font' >$prep1".number_format($TransportCost)."$prep2</td>
                                      <th class='no-print'  ></th>
                                      </tr>";
            if ($Discont>0)
                          if (in_array($login_RolesID, $permitrolsidforviewdetail)) $globalprint.= "<tr>
                          <td class='print' colspan='1'   ></td>
                          <td colspan='8' class='f12_font' >$prep1 تخفیف/تعدیل(ریال ) $Description3 $prep2</td>
                          <td  colspan='2' class='f16_font' >$prep1".number_format($Discont)."$prep2</td>
                          <th class='no-print'  ></th>
                          </tr>";
            $totinv=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);
            if (in_array($login_RolesID, $permitrolsidforviewdetail)) $globalprint.= "
                          <td class='print' colspan='1'   ></td>
                          <td colspan='8' class='f17_font' >$prep1 جمع  با ارزش افزوده(ریال)$prep2</td>
                          <td colspan='2'  class='f18_font'>$prep1".number_format($totinv)."$prep2</td>
                          <th class='no-print'  ></th></tr></table>";        
            
        }
        
                    
                    
            
                    if (! $pricenotinrep) 
                    {
                        $arrayindexinvoice++;
                        if ($operatorcoid>0)
                            $arrayinvoices[$arrayindexinvoice.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$totinv;  
                        else
                            $arrayinvoices[$arrayindexinvoice.'-'.$Title]=$totinv; 
                             
                       mysql_query("update invoicemaster set tot='$totinv' where InvoiceMasterID='$InvoiceMasterIDold';");       
                        if ($proposable>0  &&  $resquery['pricenotinrep']==0 && $proposabletransportless>0
                        &&  !in_array($ApplicantMasterID,array(2804,2138,2374)) )    
                      {
                            //print "sa";
                            $pipeproposeval+=$totinv;
                            
                      } 
                      
                    }
                    else
                    {
                        $arrayindexinvoicedone++;
                        if ($operatorcoid>0)
                            $arrayinvoicesdone[$arrayindexinvoicedone.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$totinv;  
                        else
                            $arrayinvoicesdone[$arrayindexinvoicedone.'-'.$Title]=$totinv; 
                    }
                       
             
        if ($type==11)
        {
            print $globalprint;
            
            // print "salam".strlen($globalprint);
            exit;
        }
        
        
       }
        
    /////////////////////////بخش هزینه های اجرایی
    $sumfehrest=array();
    
    if (in_array($login_RolesID, $permitrolsidforviewdetail) ||in_array($login_RolesID, $permitrolsidforviewdetailcost) ) 
    {
  /*      if ($type!=12)
   $globalprint.= "</table>
    <table>
           <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
					onclick=\"showhidediv('costsoptable');\"> هزینه های اجرایی
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td></tr></table>
				
            <table id='costsoptable' 
     width=\"95%\" align=\"left\"> ";
    else
  */  
  /*
    $globalprint.= "
		</table>
        <p id='psh_costsoptable' ></p>
		<table width='100%' id='ish_costsoptable'>
			   <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;\" ><td colspan=11 class='no-print'
			         onclick=\"showhidediv('sh_costsoptable');\"> هزینه های اجرایی
                     </td></tr>
					 
		</table>
            ";
			
    $globalprint.= "
    <table id='sh_costsoptable' style='display:none;'> 
			<tr>        
              <td colspan='4'><input class='no-print' id='chksh_costsoptable' type='checkbox' onChange=\"setpagereak('psh_costsoptable')\"/>
              <label class='no-print'>".str_replace(' ', '&nbsp;', "چاپ در ابتدای صفحه")."</label>
			  
			  </td>
<td colspan='8' align='center' class='f1_font' >$titles هزینه های اجرایی  $PCotitle طرح $ApplicantName</td>
            <tr>  
       ";
       */
 }
    
    
    $fcond=" ";
    if ($type==12)
        {
            $fcond=" and appfoundationID='$PCoID' ";
        }
        $sqlouterauto="";
    $fautomatic=1;
    $fmandal=1;

    if ($type==13  && $login_isfulloption!=1) 
    {
        echo $globalprint;
        exit;
        
    }
    
    if ($login_ProducersID>0)
    {
        echo $globalprint;
        exit;        
    }
    $farmerdutyarraycnt=0;
    $farmerdutyarray=array();
                    /*
                    appfoundationID شناسه سازه طرح
                    appfoundation جدول سازه های طرح
                    applicantmasterdetail جدول ارتباطی  طرح ها
                    ApplicantMasterIDmaster شناسه طر اجرایی
                    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
                    */
                    $queryfd = "select distinct appfoundationID from appfoundation 
                    inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterIDmaster='$_POST[ApplicantMasterID]'
                    or applicantmasterdetail.ApplicantMasterIDsurat='$_POST[ApplicantMasterID]'
                    ) where farmerduty=1";
                    
                    try 
                          {		
                            $resultfd = mysql_query($queryfd);
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                    
                    //print $query;exit;
                    $hasfd=0;
            		while ($rowfd = mysql_fetch_assoc($resultfd))
                    {
                        $farmerdutyarray[$farmerdutyarraycnt++]=$rowfd['appfoundationID'];
                        $querys = "delete from appfoundationmoderate where appfoundationID='$rowfd[appfoundationID]' and price=0;";
                        mysql_query($querys);
                        $querys = "insert into appfoundationmoderate (appfoundationID,Price,fehrestsmasterID,Description,SaveTime,SaveDate,ClerkID) 
                        
                        select distinct appfoundationID,0,fehrestsmasterID,'0','" . date('Y-m-d H:i:s') . "','" 
                                    . date('Y-m-d') . "','" . $login_userid . "' from manuallistpriceall
inner join fehrests on fehrests.fehrestsid=manuallistpriceall.fehrestsid
where  manuallistpriceall.appfoundationID='$rowfd[appfoundationID]'
union all 
select distinct appfoundationID,0,fehrestsmasterID,'0','" . date('Y-m-d H:i:s') . "','" 
                                    . date('Y-m-d') . "','" . $login_userid . "' from manuallistprice
inner join fehrestsfasls on fehrestsfasls.fehrestsfaslsID=manuallistprice.fehrestsfaslsID
where  manuallistprice.appfoundationID='$rowfd[appfoundationID]';";
                        mysql_query($querys);
                                    
                    }   

    if ($prjtypeid!=0) $fautomatic=0;
    //print $fautomatic."sa";
    $sqlouter=fehrestquery($fautomatic,$fmandal,$ApplicantMasterID,$costpricelistmasterID,$cityid15,$subprjcondition,$fcond).$login_limited;
    //if ($login_RolesID==1) print $sqlouter;
 // if ($login_RolesID==1) $globalprint.= $sqlouter;
    $oldToolsGroupsCode=0;
    $oldftype='';
    $oldCostsGroupsTitle='';
    $rown=0;
    $sumin=0;
    $resultouter = mysql_query($sqlouter);
    $ID1[' ']=' ';
    $i1=0;
	while($resquery = mysql_fetch_assoc($resultouter))
    {
        if (!($resquery['appfoundationID']>0))
            continue;
        $Number2="";
        $Number3="";
        $Number4="";
        $Number5="";
        $Number6="";
		  $Number2=round($resquery['Number2'],3);
          $Number3=round($resquery['Number3'],3);
          $Number4=round($resquery['Number4'],3);
          $Number5=round($resquery['Number5'],3);
          $Number6=round($resquery['Number6'],3);
       	if ($Number3>0) $Number3=$Number3; else $Number3 = '';
	    if ($Number4>0) $Number4=$Number4; else $Number4 = '';
	    if ($Number5>0) $Number5=$Number5; else $Number5 = '';
	    if ($Number6>0) $Number6=$Number6; else $Number6 = '';
        $i1++;
        if ($resquery['ToolsGroupsCode']<10) $ToolsGroupsCode="0".$resquery['ToolsGroupsCode']; else $ToolsGroupsCode=$resquery['ToolsGroupsCode'];
	
	$printf=$ToolsGroupsCode
        ."<br>_".$resquery['Code']
        ."<br>_".$resquery['ftype']
        ."<br>_".$resquery['Title']
        ."<br>_".$resquery['unit']
        ."<br>_".$Number2
        ."<br>_".$Number3
        ."<br>_".$Number4
        ."<br>_".$Number5
        ."<br>_".$Number6
        ."<br>_".$resquery['FNumber']
        ."<br>_".$resquery['Price']
        ."<br>_".$resquery['Total']
        ."<br>_".$resquery['Description']
		."<br>_".$resquery['fehrestsmasterID']
		."<br>_".$resquery['fehrestsfaslsID']
  		."<br>_".$i1
		."<br>_".$resquery['aut']
		."<br>_".round($resquery['Number'],3);
        $ID1[$printf]=$resquery['appfoundationID']."_".$i1; 
        
       // if ($resquery['Code']=='1105022')
       //     echo $printf;
        
      }
//if ($login_RolesID==1)  print_r($ID1);    
	
    $ID1=mykeyvalsort($ID1);
    //exit;
    mysql_data_seek( $resultouter, 0 );
    $arraycosts = array();
    $rownf=1;           
    while($resquery = mysql_fetch_assoc($resultouter))
    {
        $SumPrice = $resquery['Total'];
        $Price = $resquery['Price'];
        $Price2 = $resquery['price2'];
        $appfoundationtitle=$resquery['appfoundationtitle'];
		$fehrestsmasterID=$resquery['fehrestsmasterID'];
        $unit = $resquery['unit'];
        $Number = $resquery['Number'];
        $FNumber = $resquery['FNumber'];
        $Number=round($Number,3);
        $Number2=round($resquery['Number2'],3);
        $nval=round($resquery['Number']*$resquery['Number2'],2);
        $Title = $resquery['Title'];
        $CostsGroupsTitle = $resquery['ftype'].' '.$resquery['ToolsGroupsCode'].': '.$resquery['CostsGroupsTitle'];
        $Code = $resquery['Code'];
        $ToolsGroupsCode = $resquery['ToolsGroupsCode'];
        $ID=$resquery['Code'].'_'.$ApplicantMasterID."_".$type;
        $ftype=$resquery['ftype'];
        $sazetitle='';
        if ($ftype<>'آبیاری تحت فشار')
            $sazetitle='سازه';
        
        $tedadtitle='';
        if ($ftype<>'آبیاری تحت فشار')
            $tedadtitle='تعداد';
			
    	if (($ftype== 'آبیاری تحت فشار') ||	 ($resquery['appfoundationID'] && $ftype<>'آبیاری تحت فشار'))	
		$sumfehrest["$ftype"]+=$SumPrice;
		
        $regioncoefval["$ftype"]=$resquery['regioncoefval'];
        $fcolspan="";$fhid="";
		if ($ftype=='آبیاری تحت فشار')
		{$fcolspan="colspan='2'";$fhid="hidden";}
        ////////////////////////////////////آرایه سازه ها
        if ($sazetitle<>'')
        {
	//	  if ($Number2<>1) $tdNumber=$Number."×".$Number2;else $tdNumber=$Number;
		  $Number3=round($resquery['Number3'],3);
          $Number4=round($resquery['Number4'],3);
          $Number5=round($resquery['Number5'],3);
          $Number6=round($resquery['Number6'],3);
       	if ($Number3>0) $Number3=$Number3; else $Number3 = '';
	    if ($Number4>0) $Number4=$Number4; else $Number4 = '';
	    if ($Number5>0) $Number5=$Number5; else $Number5 = '';
	    if ($Number6>0) $Number6=$Number6; else $Number6 = '';
	    				
            $cntappfoundationtitle["$appfoundationtitle"]++;
            $strappfoundationtitle["$appfoundationtitle"][$cntappfoundationtitle["$appfoundationtitle"]]=
                            "
                    <tr><td></td>
                            <td class='f28_font'>".$cntappfoundationtitle["$appfoundationtitle"]."</td>
                            <td class='f28_font'>$ToolsGroupsCode</td>
                            <td class='f28_font'>$ftype</td>
                            <td class='f28_font'>$Code</td>
                            <td class='f11_font' style='text-align: justify;width: 100%;'>$Title</td>
                            <td class='f28_font'>$unit</td>
                            <td class='f28_font' >$Number2</td>
                            <td class='f28_font' >$Number</td>
                              <td class='f28_font'>$FNumber</td>
                            <td class='f11_font'>".number_format($Price)."</td>
                            <td class='f11_font'>".number_format($Price*$Number2*$Number*$FNumber)."</td>
							<td></td>
                    </tr>";
            $sumappfoundationtitle["$appfoundationtitle"][$cntappfoundationtitle["$appfoundationtitle"]]=$Price*$Number2*$Number*$FNumber;
            
            //////////////////////////////////group
            if ($oldnumbergroup["$appfoundationtitle"]>0 & $oldcodegroup["$appfoundationtitle"]!=$Code)
            {
                //print $cntappfoundationtitlegroup["$appfoundationtitle"];
            $cntappfoundationtitlegroup["$appfoundationtitle"]++;
            $strappfoundationtitlegroup["$appfoundationtitle"][$cntappfoundationtitlegroup["$appfoundationtitle"]]=
                            "
                    <tr><td></td>
                            <td class='f28_font'>".$cntappfoundationtitlegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldToolsGroupsCodegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldftypegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldcodegroup["$appfoundationtitle"]."</td>
                            <td class='f11_font' style='text-align: justify;width: 100%;'>".$oldTitlegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldunitgroup["$appfoundationtitle"]."</td>
                            <td class='f28_font' >1</td>
                            <td class='f28_font' >".$oldnumbergroup["$appfoundationtitle"]."</td>
                              <td class='f28_font'>".$oldFNumbergroup["$appfoundationtitle"]."</td>
                            <td class='f11_font'>".number_format($oldPricegroup["$appfoundationtitle"])."</td>
                            <td class='f11_font'>".number_format($oldPricegroup["$appfoundationtitle"]*$oldnumbergroup["$appfoundationtitle"]*$oldFNumbergroup["$appfoundationtitle"])."</td>
							<td></td>
                    </tr>";
            $sumappfoundationtitlegroup["$appfoundationtitle"][$cntappfoundationtitlegroup["$appfoundationtitle"]]=$oldPricegroup["$appfoundationtitle"]*$oldnumbergroup["$appfoundationtitle"]*$oldFNumbergroup["$appfoundationtitle"];
            
            $oldnumbergroup["$appfoundationtitle"]=0;
            }
            $oldcodegroup["$appfoundationtitle"]=$Code;
            $oldToolsGroupsCodegroup["$appfoundationtitle"]=$ToolsGroupsCode;
            $oldftypegroup["$appfoundationtitle"]=$ftype;
            $oldTitlegroup["$appfoundationtitle"]=$Title;
            $oldunitgroup["$appfoundationtitle"]=$unit;
            $oldFNumbergroup["$appfoundationtitle"]=$FNumber;
            $oldPricegroup["$appfoundationtitle"]=$Price;
            $oldnumbergroup["$appfoundationtitle"]=$oldnumbergroup["$appfoundationtitle"]+($Number2*$Number);   
             
                      
            //////////////////////////////////////
            
        }
		
        ////////////////////////////////////////////////
        
        if ($oldToolsGroupsCode<>$ToolsGroupsCode)
        {
            
            if ($oldToolsGroupsCode>0 || ($oldftype!=''))
            {
                if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                {
                    if ($sumin<>0)
                    {
						if ($oldftype==$ftype)
						$globalprint.= "<tr><td></td>
							  <td  class='f11_fonttm' $fhid></td>
							 <td colspan='12' class='f11_fonttm' style=\"border-top: 1px solid blue;\" ></td>
							 <td colspan='1'  class='f11_fonttm'>".number_format($sumin)."</td>
							 </tr>"; 
						else
						$globalprint.= "<tr><td></td>
							  <td  class='f11_fonttm' style=\"border-bottom: 0px solid #ff0000;\" $fhid></td>
							 <td colspan='11' class='f11_fonttm' style=\"border-bottom: 0px solid #ff0000;\" ></td>
							 <td colspan='1'  class='f11_fonttm' style=\"border-bottom: 0px solid #ff0000;\">".number_format($sumin)."</td>
							 </tr>";                         
					}
							// آبیاری
                          //<tr class='page-break'>
                      if ($oldftype==$ftype && $Number>0)
                      $globalprint.= "
					  	
					  <tr id='psh_costsoptableR$ToolsGroupsCode$ftype'>
					  <td colspan='1'  style=\"border:0px solid black; \" >	
							    <input class='no-print' id='chksh_costsoptableR$ToolsGroupsCode' type='checkbox'  Title='چاپ در ابتدای صفحه' onChange=\"setpagereaktr('psh_costsoptableR$ToolsGroupsCode$ftype')\"/> 
			  
						</td>
				 
				          	<th align='center' class='f21_font' >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th align='center' class='f10_font' $fcolspan style='width: $P1title%;'>شرح</th>
                            <th align='center' class='f21_font' $fhid >$sazetitle</th>
                            <th align='center' class='f21_font'>واحد</th>
                            <th align='center' colspan='3' class='f21_font' ><div style=\"width: 100px;\">مقدار<br><font size='2'>تعداد  × مقدار جزء</font> </div></th>
                            <th align='center' class='f21_font' $fhid>$tedadtitle</th>
                            <th align='center' colspan='3' class='f21_font' >بهاء(ریال)</th>
                            <th align='center' class='f21_font'>بهای کل(ریال)</th>
                            <th align='center' ><div style=\"width: $Lmargin;\"></div></th>
					
                    </tr>";                    
                }

                      
                if (isset($CostsGroupsTitle) && $sumin<>0)
                $arraycosts[($rownf++)."-".$oldCostsGroupsTitle]=$sumin;
                $rown=0;
                $sumin=0;
            }
            
            
            $oldToolsGroupsCode=$ToolsGroupsCode;
            $oldCostsGroupsTitle=$CostsGroupsTitle;
            
        }
        
        if ($oldftype<>$ftype)
        {
            if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "
            
				<tr>
					<td >&nbsp;</td>
				</tr>      
                </table>
					
					<p id='psh_costsoptable$fehrestsmasterID' ></p>
		<table width='$Rwidth%' id='ish_costsoptable$fehrestsmasterID'>
						<tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;border:1px solid black;border-color:#D1D1D1;\" >
							<td colspan=11 class='no-print'	onclick=\"showhidediv('sh_costsoptable$fehrestsmasterID');\"> هزینه های اجرایی فهرست بهاء $ftype $fb
							</td>
						</tr>
		</table>
        
		<table id='sh_costsoptable$fehrestsmasterID' style='display:none;' width='$Pwidth%'>      
                <tr>
					 
					<td colspan='4'><input class='no-print' id='chksh_costsoptable$fehrestsmasterID' type='checkbox' onChange=\"setpagereak('psh_costsoptable$fehrestsmasterID')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
				</tr>
                <tr>
					<td></td>
				    <td colspan='3' class='f6_font'>   فهرست بهاء:  </td>
                    <td colspan='9' class='f6_font'>    $ftype   $fb</td>
			   
                </tr>
				 
				
					<tr  id='psh_costsoptableR$ToolsGroupsCode$ftype' >
				        	<th><div style=\"width:  $Rmargin;\"></div>		
								</th>
							<th align='center' class='f21_font' >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th align='center' class='f21_font' $fcolspan style='width: $P1title%;'>شرح</th>
                            <th align='center' class='f21_font' $fhid >$sazetitle</th>
                            <th align='center' class='f21_font'>واحد</th>
                            <th align='center' colspan='3' class='f21_font' >مقدار<br><font size='2'>تعداد  × مقدار جزء</font></th>
                            <th align='center' class='f21_font' $fhid>$tedadtitle</th>
                            <th align='center' colspan='3' class='f21_font' >بهاء(ریال)</th>
                            <th align='center' class='f21_font' >بهای کل(ریال)</th>
                            <th align='center' ><div style=\"width: $Lmargin;\"></div></th>
                    </tr>
				</div>				
                        
                ";
                //آبیاری تحت فشار
                //$oldToolsGroupsCode=0;
                
            $sumin=0;
            $groupnumber=0;
        }
        
        
        $readonlydesc='readonly';   
        if ((($type==3) && ($login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ||$login_RolesID==4
                    ||$login_RolesID==2||$login_RolesID==9) ) || $login_RolesID==1)
                        $readonlydesc=''; 
        
        $oldftype=$ftype;
		
        if ($Number>0 && ($Price>0 || $Price2>0|| $resquery['TCode']!=2))
        {
            $rown++;

			if (($ftype== 'آبیاری تحت فشار') ||	 ($resquery['appfoundationID'] && $ftype<>'آبیاری تحت فشار'))	
	        $sumin+=$SumPrice;
			
            if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost))
            {
                
    		if (($ftype== 'آبیاری تحت فشار') ||	 ($resquery['appfoundationID'] && $ftype<>'آبیاری تحت فشار'))	
               
                if ($resquery['TCode']==3 || $resquery['TCode']==4)
                {
                    $pval2='';
                    $pval3='';
                    $nval2='';
                    $nval3='';
                    
                    $nval1=round($resquery['nval1'],2);
                    if ($resquery['nval3']>0)
                    $nval3=round($resquery['nval3'],2);
                    if ($resquery['nval2']>0)
                    $nval2=round($resquery['nval2'],2);
                    
                    $pval1=($resquery['pval1']);
                    if ($resquery['pval2']>0)
                    $pval2=($resquery['pval2']);
                    if ($resquery['pval3']>0)
                    $pval3=($resquery['pval3']);
                    if ($resquery['TCode']==3)
                    {$fntcl="f21_fontr";$fntcl12="f26_fontr";$fntcl12C="f25_fontr";}
                    else 
					  {$fntcl="f28_font";$fntcl12="f26_font";$fntcl12C="f25_font";}
                    $snv="";$snvwidth="";
                    if ($Number2==1)
                    {
                        $snv="style='display:none'";
						$snvwidth="120px";
						
                    }
                    $linearray = explode('_',$appfoundationtitle);
                    $ftitle=$linearray[0];
    ////////////   //////////////////////////////////////////////////////////////////////   now   //////////////////////////////// 
			         $globalprint.= "     
					 <tr id='psh_costsoptableR$ToolsGroupsCode$ftype'><th rowspan='2 align='center'  ></th>
					        <td rowspan='2' class='$fntcl12C'>$rown</td>
                            <td rowspan='2' class='$fntcl12C'><div style=\"width:  $c1margin;\">$ToolsGroupsCode</div></td>
                            <td rowspan='2' class='$fntcl12C'>$Code</td>
                            <td rowspan='2' class='$fntcl12' $fcolspan > $Title </td>
                            <td rowspan='2' class='$fntcl' $fhid   >$ftitle</td>
                            <td rowspan='2' class='$fntcl'>$unit</td>
                            <td colspan='3'  >
								<div id='divFNumber$resquery[tblid]' align='center' style=\"width:  $snvwidth;\">      
								<input $snv  $readonlydesc  name='FNumber2$resquery[tblid]' type='text' class='f11_fontn' 
									  id='FNumber2$resquery[tblid]'     value='".$Number2."' size='5' />
									<labbel $snv>  × </labbel>
								<input  $readonlydesc  name='FNumber$resquery[tblid]' type='text' class='f11_fontn' 
									  id='FNumber$resquery[tblid]'     value='".$Number."' size='5' />
								</div>
							</td> ";
                                  
                           if ($Price2<>$Price)
                                  $globalprint.=  "
                            <td rowspan='2' class='$fntcl12C' $fhid>$FNumber</td>
							<td colspan='3' style=\"border-right: 1px solid blue;border-top: 1px solid blue;\">
								<div id='divPrice$resquery[tblid]'\">      
								<input  $readonlydesc  name='Price$resquery[tblid]' type='text' class='f11_fontnR' \" 
                                  id='Price$resquery[tblid]'     value='".number_format($Price)."'  /></div>
							</td>";
                          else
                                  $globalprint.= "
                            <td rowspan='2' class='$fntcl12C' $fhid>$FNumber</td>
                            <td colspan='3' style=\"border-right: 1px solid blue;border-top: 1px solid blue;\" >
								<div  \">      
								<input  readonly   type='text' class='f11_fontnRf'\" 
                                       value='".number_format($Price)."'  /></div>
							</td>";
                                  
                                  $globalprint.=  "
                            <td rowspan='2' class='f11_font'\">".number_format($SumPrice)."
                                  <input  $readonlydesc  name='TCode$resquery[tblid]' type='hidden'
                                  id='TCode$resquery[tblid]'     value='$resquery[TCode]'  />
							</td>";
                        
                            if ($Code>0 && ($resquery['TCode']==2))
                            {
                              if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                                    $globalprint.= "<td><a class='no-print' href=summaryinvoice_detail.php?np=10&uid=".
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).">ریز</a></td>";  
                            }
                            else $globalprint.="<td></td>";
                        
                                
                          $globalprint.= "
						</tr>";
                    
					$th1='';$th2='';$th3='';$th4='';$th5='';$th6='';
                        
					if ($nval1>0 && $nval<>$nval1)  $th1='h';
					if ($nval2>0 && $nval<>$nval2) $th2='h';
					if ($nval3>0 && $nval<>$nval3) $th3='h';
					if ($pval1>0 && $Price<>$pval1)  $th4='h';
					if ($pval2>0 && $Price<>$pval2) $th5='h';
					if ($pval3>0 && $Price<>$pval3) $th6='h';
             
                    if ( ($nval1>0 && $nval<>$nval1) 
                      || ($nval2>0 && $nval<>$nval2) || ($nval3>0 && $nval<>$nval3) || ($nval2>0 && $nval1<>$nval2) || ($nval3>0 && $nval1<>$nval3)
                      || ($pval1>0 && $Price<>$pval1) || ($pval2>0 && $Price<>$pval2) || ($pval3>0 && $Price<>$pval3) 
                      || ($pval2>0 && $pval1<>$pval2) || ($pval3>0 && $pval1<>$pval3)
                        )
							{
                                $globalprint.= "<tr>";
                                if ( ($nval1>0 && $nval<>$nval1) 
									|| ($nval2>0 && $nval<>$nval2) || ($nval3>0 && $nval<>$nval3) || ($nval2>0 && $nval1<>$nval2) || ($nval3>0 && $nval1<>$nval3)
									)
									{
										if (!($nval3>0))
											$nval3='';
										$globalprint.= "
											<td class='f11_fontt$th1'><font color='blue'>$nval1 </font></td>
											<td class='f11_fontt$th2'><font color='red'>$nval2 </font></td>
											<td class='f11_fontt$th3'><font color='green'>$nval3 </font></td>
											";                            
									}
									else 
									$globalprint.= "<td colspan=3 class='f11_fontt$th1'></td>";
									
							if ( ($pval1>0 && $Price<>$pval1) || ($pval2>0 && $Price<>$pval2) || ($pval3>0 && $Price<>$pval3) 
								|| ($pval2>0 && $pval1<>$pval2) || ($pval3>0 && $pval1<>$pval3)
								)
								$globalprint.= "
                                    <td class='f11_fontt$th4'><font color='blue'>".number_format($pval1)."</font></td>
                                    <td class='f11_fontt$th5'><font color='red'>".number_format($pval2)."</font></td>
                                    <td class='f11_fontt$th6'><font color='green'>".number_format($pval3)."</font></td>
                                ";
								
								
                             //$globalprint.= "</tr>";   
							 
                            }
    
                            else
                            {
                                $globalprint.= "<tr>
                                <td class='f11_fontt'><font color='blue'></font></td>
                                <td class='f11_fontt'><font color='red'></font></td>
                                <td class='f11_fontt'><font color='green'></font></td>
                                <td class='f11_fontt'><font color='blue'></font></td>
                                <td class='f11_fontt'><font color='red'></font></td>
                                <td class='f11_fontt'><font color='green'></font></td>
                                ";
    
                            }
                        
                }
                else    
                {
            
                    $globalprint.= "<tr id='psh_costsoptableR$ToolsGroupsCode$ftype'>
								<td></td>
                                <td class='f24_font'>$rown  </td>
                                <td class='f25_font'>
                                <div id='divGCode$resquery[gadget3operationalID]' style=\"width: 30px;\">      
                            <input  $readonlydesc  name='GCode$resquery[gadget3operationalID]' type='text' class='f11_fontn' style=\"width: 27px;\" 
                                  id='GCode$resquery[gadget3operationalID]'     value='$ToolsGroupsCode'  />
                                  <input  $readonlydesc  name='oldGCode$resquery[gadget3operationalID]' type='hidden'
                                  id='oldGCode$resquery[gadget3operationalID]'     value='$ToolsGroupsCode'  />
                                  </div>
                            
                                  
                                </td>
                                <td class='f25_font'>
                                <div id='divCode$resquery[gadget3operationalID]' style=\"width: 45px;\">       
                            <input  $readonlydesc  name='Code$resquery[gadget3operationalID]' type='text' class='f11_fontn' style=\"width: 43px;\" 
                                  id='Code$resquery[gadget3operationalID]'     value='$Code'  />
                                  <input  $readonlydesc  name='oldCode$resquery[gadget3operationalID]' type='hidden'
                                  id='oldCode$resquery[gadget3operationalID]'     value='$Code'  />
                                  </div>
                                  </td>
                                  
                                <td class='f26_font' $fcolspan  >$Title</td>
                                <td class='f24_font' $fhid>$appfoundationtitle</td>
                                <td class='f13_fonts'>$unit</td>
                                <td colspan='3' class='f30_font'>".round($Number,3)."</td>
                                <td class='f27_font' $fhid>$FNumber</td>
                                <td colspan='3' class='f11_font'>".number_format($Price)."</td>
                                <td class='f11_font'>".number_format($SumPrice)."</td>
                            ";      
                    if ($Code>0  && ($resquery['TCode']==2))
                    {
                        if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                            $globalprint.= "<td><a class='no-print' href=summaryinvoice_detail.php?np=10&uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ID.rand(10000,99999).">ریز</a></td>";
                    }
                    else $globalprint.="<td></td>";
                        
                      
                }         
              
            }
            
            if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                $globalprint.= "</tr>";

         
        }
         
    
    }
	

            //////////////////////////////////group
           
            
            foreach($strappfoundationtitle as $key=> $value)
            {
                    $appfoundationtitle=$key;
             
             
                   
            $cntappfoundationtitlegroup["$appfoundationtitle"]++;
            $strappfoundationtitlegroup["$appfoundationtitle"][$cntappfoundationtitlegroup["$appfoundationtitle"]]=
                            "
                    <tr><td></td>
                            <td class='f28_font'>".$cntappfoundationtitlegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldToolsGroupsCodegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldftypegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldcodegroup["$appfoundationtitle"]."</td>
                            <td class='f11_font' style='text-align: justify;width: 100%;'>".$oldTitlegroup["$appfoundationtitle"]."</td>
                            <td class='f28_font'>".$oldunitgroup["$appfoundationtitle"]."</td>
                            <td class='f28_font' >1</td>
                            <td class='f28_font' >".$oldnumbergroup["$appfoundationtitle"]."</td>
                              <td class='f28_font'>".$oldFNumbergroup["$appfoundationtitle"]."</td>
                            <td class='f11_font'>".number_format($oldPricegroup["$appfoundationtitle"])."</td>
                            <td class='f11_font'>".number_format($oldPricegroup["$appfoundationtitle"]*$oldnumbergroup["$appfoundationtitle"]*$oldFNumbergroup["$appfoundationtitle"])."</td>
							<td></td>
                    </tr>";
            $sumappfoundationtitlegroup["$appfoundationtitle"][$cntappfoundationtitlegroup["$appfoundationtitle"]]=$oldPricegroup["$appfoundationtitle"]*$oldnumbergroup["$appfoundationtitle"]*$oldFNumbergroup["$appfoundationtitle"];
            
            }
             
                      
            //////////////////////////////////////
            
    
    
    
    $modfehrest=array();
    $modfehrestcnt=0;
    foreach($strappfoundationtitle as $key=> $value)
    {
        $linearray = explode('_',$key);
        $ftitle=$linearray[0];
        $appfoundationID=$linearray[1];
        $Pricemod=$linearray[2];
        $fehrestsmasterID=$linearray[3];
        $sumsaze=0;
        foreach($strappfoundationtitle[$key] as $key1=> $value1)   
        {
            $sumsaze+=$sumappfoundationtitle[$key][$key1];
            
        }
        if($Pricemod>0 || (in_array($appfoundationID, $farmerdutyarray)))
        {
            $modfehrestcnt++;
            $modfehrest[$key]=round($Pricemod-$sumsaze);
        }
        else
        $modfehrest[$key]=0;
    }
    
        //$Tdpix
        if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "<tr>
							
							     <td  class='f24_font' style=\"border:0px solid black;\" $fhid>&nbsp</td>
								 <td colspan='12' class='f24_font' style=\"border:0px solid black;\" >&nbsp</td>
                          	 <td colspan='1' class='f24_font' style=\"border:0px solid black;\" >&nbsp</td>
                                 <td  class='f24_font' style=\"border:0px solid black;\">".number_format($sumin)."</td>
                      </tr>
                     </table>"; 
					  
    if (isset($CostsGroupsTitle)) 
        $arraycosts[($rownf++)."-".$oldCostsGroupsTitle]=$sumin;

    if ($type==12)
    {
        $sumcostsf=0;
        foreach ($arraycosts as $i => $value) 
            $sumcostsf+=$value;
                
        if (in_array($login_RolesID, $permitrolsidforviewdetail)) $globalprint.= "
        		</table>
				<table width='$Pwidth%' align=\"$tableAli\">
				
				 <tr>
	                 	  <td class='print'  class='f9_font'></td>
                          <td class='f17_font' >جمع فهرست بها(ریال)</td>
                          <td   class='f18_font'>".number_format($sumcostsf)."</td>
						<td align='center' >  <div style=\"width:  $Rmargin;\"></div> </td>
                                   </td>
    				                     
                          </tr>
                          
                          
                          
                    </table>";
                    
        print $globalprint;
        exit;
    }

         	
    ////////////////////////////////////////////////تجهیز/////////////////////////////////////////
    $tajhizglobalprint="";
            $tajhiztitle="تجهیز و برچیدن کارگاه (مقطوع)";
    $sqlt = "SELECT equip.Code,equip.Title,equip.equipID,appequip.Price,appequip.appequipID FROM equip 
    left outer join appequip on equip.equipID=appequip.equipID and ApplicantMasterID ='$ApplicantMasterID'
    where appequip.Price>0
    order by equip.Code" ;
    $resultt = mysql_query($sqlt);
    $r=mysql_num_rows($resultt);
    if ($r>0)
    {
	  
	  if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost))
				$globalprint.= " 
		   </table> 
			<p id='psh_tajhiztable' ></p>
			<table width='$Rwidth%' id='ish_tajhiztable'>
					 <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
						border:1px solid black;border-color:#D1D1D1;
						\" ><td colspan=11 class='no-print'
						onclick=\"showhidediv('sh_tajhiztable');\">".str_replace(' ', '&nbsp;', "تجهیز و برچیدن کارگاه").
						"
						</td></tr>
						</table>
					"; 
	
			$globalprint.= " 
				<table id='sh_tajhiztable' style='display:none;' width='$Pwidth%'> 
			<tr><td colspan='4'><input  class='no-print' id='chksh_tajhiztable' type='checkbox' onChange=\"setpagereak('psh_tajhiztable')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label>
			</td></tr>
						<tr><td colspan='1'></td>
						 <td colspan='3' class='f6_font'> فهرست بها:</td>
						<td colspan='3' class='f6_font'>تجهیز و برچیدن کارگاه</td>
						<td></td>
						</tr>
						<tr>		
								<td   ><div style=\"width:  $Rmargin;\"> </td>
								<th align='center' class='f21_font' >ردیف</th>
								<th align='center' class='f21_font' >فصل</th>
								<th align='center' class='f21_font' >کد</th>
								<th colspan='1' align='center' class='f21_font' style='width: $P1title%;'>شرح</th>
								<th align='center' class='f22_font' >واحد</th>
								<th align='center'  class='f22_font'>بها(ریال)</th>
					 <td colspan='1'  class='f26_font' style=\"border:0px solid black; \" ><div style=\"width: $Lmargin;\"></div></td>
	   
						</tr>";
			
		
        $rown=0;
        $sumtajhiz=0;
        while($resqueryt = mysql_fetch_assoc($resultt))
        {
                $rown++;
                $Price = $resqueryt['Price'];
                $sumtajhiz+=$Price;
                $Title = $resqueryt['Title'];
                $Code = $resqueryt['Code'];
                $tajhizglobalprint.= "<tr>
                     			<td class='f24_font' style=\"border:0px solid black; \"></div> </td>
                              	<td class='f24_font'>$rown</td>
                                <td class='f24_font'>42</td>
                                <td class='f24_font'>$Code</td>
                                <td colspan='1'  class='f26_font'  >$Title</td>
                                <td class='f13_fonts'>مقطوع</td>
                                <td  class='f11_font'>".number_format($Price)."</td>";
        }
            
				$tajhizglobalprint.= "</tr><tr><td class='f24_font' style=\"border:0px solid black; \"> </td>
                              
						                 <td colspan='5' class='f24_font' >
									<a href=equip_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_10_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
                            مجموع</a>
								</td>
								<td  class='f24_font'>".number_format($sumtajhiz)."</td>
                           <tr><td class='f24_font' style=\"border:0px solid black; \"> </td>
                              
                                <td colspan='5' class='f24_font'>تجهیز و برچیدن کارگاه(مقطوع) با اعمال ضریب  ($coef3)</td>
                                <td  class='f24_font'>".number_format($sumtajhiz*$coef3)."</td>";   
                
                        
        //if ($issurat==1)
        //{
            $sumtajhiz=$sumtajhiz*$coef3;
            $tajhiztitle="تجهیز و برچیدن کارگاه(مقطوع) با اعمال ضریب ";
        //}            
        $tajhizglobalprint.= "</table>";
    }
    if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
    $globalprint.=$tajhizglobalprint;    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
 ////////////////////////////////////////////////آیتم های قیمت جدید/////////////////////////////////////////
    
    
    $newpriceglobalprint="";
            $newpricetitle="آیتم های قیمت جدید";
    $sqlt = "
     select  fehrestsmaster.Title ftype,fehrestsmaster.fehrestsmasterID,nval1,nval2,nval3
,case manuallistprice.AddOrSub>=1 when 1 then pval1 else -1*pval1 end pval1 
,case manuallistprice.AddOrSub>=1 when 1 then pval2 else -1*pval2 end pval2
,case manuallistprice.AddOrSub>=1 when 1 then pval3 else -1*pval3 end pval3
,manuallistprice.ManualListPriceID tblid,_utf8'فهرست بهاي دستي' AS `Type`,3 AS `TCode`,cast(fehrestsfasls.fasl as decimal(10,0)) AS `ToolsGroupsCode`,
fehrestsfasls.Title AS `CostsGroupsTitle`,
concat(
case when (`manuallistprice`.`AddOrSub` = 1 ) then _utf8' اضافه بها ' when (`manuallistprice`.`AddOrSub` = 2 ) then ' ' else _utf8' کسربها ' end
,`manuallistprice`.`Title`) AS `Title`,
`manuallistprice`.`Code` AS `Code`,`manuallistprice`.`Number` AS `Number`,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber
,case ifnull(manuallistprice.Number2,0) when 0 then 1 else manuallistprice.Number2 end Number2,manuallistprice.Description,`manuallistprice`.`Unit` AS `unit`,
((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) AS Price,(((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) * `manuallistprice`.`Number`*case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end*case ifnull(manuallistprice.Number2,0) when 0 then 1 else manuallistprice.Number2 end) AS `Total`
,`manuallistprice`.`ApplicantMasterID` AS `ApplicantMasterID`,'' NGCode,'' NTCode,'' gadget3operationalID,'' price2,concat(appfoundation.title,'_',appfoundation.appfoundationID,'_',ifnull(appfoundationmoderate.Price,0),'_',ifnull(appfoundationmoderate.fehrestsmasterID,0)) appfoundationtitle
,manuallistprice.appfoundationID appfoundationID
from `manuallistprice` 
inner join fehrestsfasls on fehrestsfasls.fehrestsfaslsID = manuallistprice.fehrestsfaslsID
inner join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrestsfasls.fehrestsmasterID
left outer join appfoundation on appfoundation.appfoundationID=manuallistprice.appfoundationID
left outer join appfoundationmoderate on appfoundationmoderate.appfoundationID=manuallistprice.appfoundationID 
where manuallistprice.ApplicantMasterID ='$ApplicantMasterID' and manuallistprice.appfoundationID=-1

union all select  fehrestsmaster.Title ftype,fehrestsmaster.fehrestsmasterID,nval1,nval2,nval3,pval1 ,pval2,pval3
,manuallistpriceall.ManualListPriceAllID tblid,_utf8'فهارس بها' AS `Type`,4 AS `TCode`,cast(fehrestsfasls.fasl as decimal(10,0)) AS `ToolsGroupsCode`,
fehrestsfasls.Title AS `CostsGroupsTitle`,
fehrests.Title AS `Title`,
fehrests.Code AS `Code`,manuallistpriceall.Number,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber
,case ifnull(manuallistpriceall.Number2,0) when 0 then 1 else manuallistpriceall.Number2 end Number2,manuallistpriceall.Description
,fehrests.UnitTitle AS `unit`,case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end Price,
case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end*manuallistpriceall.Number*case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end*case ifnull(manuallistpriceall.Number2,0) when 0 then 1 else manuallistpriceall.Number2 end  Total
,manuallistpriceall.ApplicantMasterID,'' NGCode,'' NTCode,'' gadget3operationalID,pricelistdetailall.price price2,concat(appfoundation.title,'_',appfoundation.appfoundationID,'_',ifnull(appfoundationmoderate.Price,0),'_',ifnull(appfoundationmoderate.fehrestsmasterID,0)) appfoundationtitle
,manuallistpriceall.appfoundationID appfoundationID

from manuallistpriceall 
inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID
inner join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrests.fehrestsmasterID
inner join fehrestsfasls on fehrestsfasls.fasl = substring(fehrests.Code,1,2) and fehrestsfasls.fehrestsmasterID=fehrests.fehrestsmasterID
left outer join pricelistdetailall on pricelistdetailall.fehrestsID=fehrests.fehrestsID and pricelistdetailall.CostPriceListMasterID='$costpricelistmasterID'
left outer join appfoundation on appfoundation.appfoundationID=manuallistpriceall.appfoundationID
left outer join appfoundationmoderate on appfoundationmoderate.appfoundationID=manuallistpriceall.appfoundationID
where manuallistpriceall.ApplicantMasterID ='$ApplicantMasterID' and manuallistpriceall.appfoundationID=-1
    
order by ftype,ToolsGroupsCode,ifnull(appfoundationtitle,''),cast(Code as decimal)" ;
    
    //print $sqlt;exit;
    
    $resultt = mysql_query($sqlt);
    $r=mysql_num_rows($resultt);
    if ($r>0)
    {
	
			$globalprint.= " 
       </table> 
        <p id='psh_newpricetable' ></p>
        <table width='$Rwidth%' id='ish_newpricetable'>
                 <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
					onclick=\"showhidediv('sh_newpricetable');\">".str_replace(' ', '&nbsp;', "$newpricetitle").
					"
                    </td></tr>
					</table>
				"; 
    
        $globalprint.= " 
            <table id='sh_newpricetable' style='display:none;' width='$Pwidth%'> 
					<tr>
						<td colspan='7'><input class='no-print' id='chksh_newpricetable' type='checkbox' onChange=\"setpagereak('psh_newpricetable')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
					</tr>
		
					<tr>
						      	<th ></th>
									<td colspan='4' class='f6_font'>       فهرست بها $fb</td>
					
						 <td colspan='10' class='f6_font'>$newpricetitle</td>
						 
					</tr>
                    <tr>
                        <th ><div style=\"width:  $Rmargin;\"></th>
                      	<th align='center' class='f21_font' >ردیف</th>
                        <th align='center' class='f21_font' >فهرست بها</th>
                        <th align='center' class='f21_font' >فصل</th>
                        <th align='center' class='f21_font' >کد</th>
                        <th align='center' class='f21_font'  style='width: $P1title%;'>شرح</th>
                        <th align='center' class='f21_font'>واحد</th>
                        <th align='center'  class='f21_font' >مقدار</th>
                        <th align='center'  class='f21_font'>بهاء(ریال)</th>
                        <th align='center' class='f21_font'>بهای کل(ریال)</th>
                        <td colspan='1'  class='f26_font' style=\"border:0px solid black; \" ><div style=\"width: $Lmargin;\"></div></td>
                    </tr>
					";
        $rown=0;
        $sumnewprice=0;
        while($resqueryt = mysql_fetch_assoc($resultt))
        {
                $rown++;

        $SumPricenp = $resqueryt['Total'];
        $Price = $resqueryt['Price'];
        $Price2 = $resqueryt['price2'];
        $appfoundationtitle=$resqueryt['appfoundationtitle'];
		$fehrestsmasterID=$resqueryt['fehrestsmasterID'];
        $unit = $resqueryt['unit'];
        $Number = $resqueryt['Number'];
        $FNumber = $resqueryt['FNumber'];
        $Number=round($Number,3);
        $Number2=round($resqueryt['Number2'],3);
        $nval=round($resqueryt['Number']*$resqueryt['Number2'],2);
        $Title = $resqueryt['Title'];
        $CostsGroupsTitle = $resqueryt['ftype'].' '.$resqueryt['ToolsGroupsCode'].': '.$resqueryt['CostsGroupsTitle'];
        $Code = $resqueryt['Code'];
        $ToolsGroupsCode = $resqueryt['ToolsGroupsCode'];
        $ID=$resqueryt['Code'].'_'.$ApplicantMasterID;
        $ftype=$resqueryt['ftype'];
                        $sumnewprice+=$SumPricenp;

                $newpriceglobalprint.= "
                <tr>
                           	<td class='f24_font' style=\"border:0px solid black;  \"> </td>
                            <td class='f11_font'>$rown</td>
                            <td class='f11_font'><div >$ftype</div></td>
                            <td class='f28_font'>$ToolsGroupsCode</td>
                            <td class='f28_font'>$Code</td>
                            <td class='f11_font'>$Title</td>
                            <td class='f13_fonts'>$unit</td>
                            <td class='f11_font' >$nval</td>
                            <td class='f11_font'>".number_format($Price)."</td>
                            <td class='f11_font'>".number_format($SumPricenp)."</td>
                            </tr>";
        }
            
        $newpriceglobalprint.= "<tr><td  ></td>
                                        <td colspan='8' class='f24_font'>
										<a href=equip_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										$ApplicantMasterID."_".$fehrestsmasterID."_10_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
										مجموع</a>
									</td>
                           <td  class='f24_font'>".number_format($sumnewprice)."</td>
                                </tr><tr><td ></td>
                                  <td colspan='8' class='f24_font'>$newpricetitle با اعمال ضریب  ($coef1)</td>
                                <td  class='f24_font'>".number_format($sumnewprice*$coef1)."</td>";   
                
                        
        //if ($issurat==1)
        //{
            $sumnewprice=$sumnewprice*$coef1;
            $newpricetitle="$newpricetitle با اعمال ضریب ";
        //}            
        $newpriceglobalprint.= "</table>";
    }
    if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost) && $sumnewprice!=0) 
    $globalprint.=$newpriceglobalprint;    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////
 
 
	$ApplicantNamep=$ApplicantName;
	if ($type==10) $ApplicantNamep='';

 
 
		$globalprint.= " 
        
        <p id='psh_summarytable' ></p>
        <table width='$Rwidth%' id='ish_summarytable'>
                 <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
					onclick=\"showhidediv('sh_summarytable');\">".str_replace(' ', '&nbsp;', "خلاصه  هزینه های طرح $ApplicantNamep " ).
					"
                    </td></tr>
					</table>
				"; 
				
    	$titlesp=$titles;
	if ($type==10) $titlesp='';
 
        $globalprint.= " 
            <table id='sh_summarytable' style='display:none;' width='$Pwidth%'
         > 
    		<tr><td colspan='7'><input class='no-print' id='chksh_summarytable' type='checkbox' onChange=\"setpagereak('psh_summarytable')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td></tr>
	         
               <tr >        
                    <td ></td>
                   <td colspan='7' align='center' class='f1_font'> خلاصه $titlesp هزینه های طرح </td>
                   <td class='f2_font'></td>
                   <td class='f2_font'></td>
			  </tr>
                
              <tr >        
                    <td ></td>
                    <td colspan='7' align='center' class='f1_font'> $ApplicantName </td>
              </tr>
                      
                            
                ";
                $rown=0;
 		
		 
                   


                            
        $rowcounter=1;
	   foreach ($arrayinvoices as $i => $value)
        $rowcounter++;
				
		   $TotlaValues=0;
        
                      
        foreach ($arrayinvoices as $i => $value) 
        {
            $rown++;
            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
            {
                 if($rown==1)
    			$globalprint.= "   
                
                <tr >  
                    <td ><div style=\"width:  $Rmargin;\"></td>
                    <td colspan=10 class='f31_font'>شرح هزینه</td> 
                    <td colspan=1 class='f31_font'>قیمت (ریال)</td> 
							 <td colspan='1'  class='f26_font' style=\"border:0px solid black; \" ><div style=\"width: $Lmargin;\"></div></td>
   
                </tr>
                                    
                <tr> 
					<td></td>
					<td rowspan='$rowcounter'  align='center' class='f33_font'>  خرید لوازم طرح و ایستگاه پمپاژ	 </td>
					<td colspan=6  align='center' class='f34_font'>$i</td>
    				<td colspan=3 class='f35_font' ><div id='divSumPrice$rown' > <input  class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='20' readonly /></div></td>
                    <td class='f36_font'>
	               </td>
  
                </tr>";
                else 
    			$globalprint.= "    
				<tr > 
					<td  ></td>
					<td colspan=6  align='center' class='f34_font'><div >$i</div></td>
					<td colspan=3 class='f35_font' ><div id='divSumPrice$rown' > <input  class='f37_font'  name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='20' readonly /></div></td>
                    <td class='f36_font'></td>
                </tr>";
                //$globalprint.= $i." ".$value;               
            }
    
			$TotlaValues+=$value;


			
        }
        $TotlainvoiceValues=$TotlaValues;
        
        
        $globalprint.= " <tr > 
        <td  ></td>
                           
                            <td colspan=9  align='center' class='f34_font'>جمع لوازم(ریال)</td>
							<td class='f38_font'><div id='divSumPrice$rown' >      <input  class='f39_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($TotlainvoiceValues)."' readonly /></div></td>
                            
                        </tr>
                           
						   ";  
                           
        


                                
           /* ثبت پیش فاککتور و هزینه اجرای سایر هزینه ها
           
           if ($login_isfulloption==1 || ($login_RolesID!=2 && $login_RolesID!=9))
          $globalprint.= " <tr > 
        <td  ></td>
                           
                            <td colspan=9  align='center' class='f34_font'>جمع لوازم(ریال)</td>
							<td class='f38_font'><div id='divSumPrice$rown' >      <input  class='f39_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($TotlainvoiceValues)."' readonly /></div></td>
                            
                        </tr>
                           
						   ";  
         else
          $globalprint.= " <tr > 
        <td  ></td>
                           
                            <td colspan=9  align='center' class='f34_font'>جمع لوازم(ریال)</td>
							<td class='f38_font'><div id='divtotinvoicemanual' >      <input  class='f46_font' name='totinvoicemanual' type='text' class='textbox'   
                                id='totinvoicemanual'       value='".number_format($totinvoicemanual)."' 
                                
                                onKeyUp=\"convert('totinvoicemanual');summ();\"
                                 /></div></td>
                            
                        </tr>
                           
						   ";*/  
                           
         
                           
         
        
        

        /////////////////////////////////محاسبه حمل
       //if ($issurat==1 && $pipeproposeval>0)
       //print "salam -$TotlainvoiceValues- $pipeproposeval";
       if ($pipeproposeval>0)
       $TransportCostunder=round($Cost*($TotlainvoiceValues-$pipeproposeval)/100); 
       else
        $TransportCostunder=round($Cost*$TotlainvoiceValues/100);
		$TpC=$TransportCostunder;
        if ( ($transportless=="checked")) 
            $TransportCostunder=0;
        //$globalprint.= $TransportCost.'sa';
          //print $transportless;exit;
        ////////////////////////////////////////////
        
            $rowcounter=3+$modfehrestcnt;
	   foreach ($arraycosts as $i => $value)
        $rowcounter++;
        
        if (count($sumfehrest)<=1)
            $rowcounter--;
            
            $rowcounter+=count($sumfehrest);
                $rown=0;
                $sumceilling=0;
                $sumcosts=0;
                $sumcostscoefless=0;
				
        if ($sumnewprice!=0)
            $rowcounter++;
        if ($coef4>0)  
            $rowcounter++;  
            
            $rowcounter++;     
        foreach ($arraycosts as $i => $value) 
        {
            $rown++;
			if($rown==1)
			$globalprint.= "     <tr >
           <td  ></td>
                                               
                            <td rowspan='$rowcounter'  align='center' class='f40_font'>عملیات اجرایی
                            
                                    <input  name='removetax' type='checkbox' id='removetax' $removetax   />
                                    
                                    <font size='1.5'>حذف ارزش افزوده</font>
                                    
                            
                            </td>
							<td colspan=6  align='center' class='f41_font'>$i</td>
							<td colspan='3' class='f42_font' ><div id='divSumPrice$rown'>      <input class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='23' readonly /></div></td>
                            <td style=\"border-top: 1px solid black;border-left: 1px solid black;border-color:#0000ff #0000ff;\"></td>
                        
                        </tr>";
            else
            $globalprint.= "     <tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>$i</td>
							<td colspan='3' class='f42_font' ><div id='divSumPrice$rown'>      <input class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='23' readonly /></div></td>
                            <td class='f43_font'></td>
                        
                        </tr>";
            //$globalprint.= $i." ".$value;
            
        }
        foreach($modfehrest as $key=> $value)
        {
            //print "sa$value";
            $linearray = explode('_',$key);
            $ftitle=$linearray[0];
            $appfoundationID=$linearray[1];
            $Pricemod=$linearray[2];
            $fehrestsmasterID=$linearray[3];
            if ((in_array($appfoundationID, $farmerdutyarray)))
            {
                $queryd = "select Title from fehrestsmaster where fehrestsmasterID='$fehrestsmasterID'";
                $resultd = mysql_query($queryd);
                $rowd = mysql_fetch_assoc($resultd);
                if($rowd['Title']<>'')
                $sumfehrest["$rowd[Title]"]+=$value;    
             $globalprint.= "     <tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'><font color='red'> $rowd[Title]: $ftitle در تعهد پیمانکار/متقاضی</font></td>
							<td colspan='3' class='f41_font' ><font color='red'> ".number_format($value)."</font></td>
							
                            <td class='f43_font'></td>
                        
                        </tr>"; 
            }
            else if ($value<>'')
            {
                $queryd = "select Title from fehrestsmaster where fehrestsmasterID='$fehrestsmasterID'";
                $resultd = mysql_query($queryd);
                $rowd = mysql_fetch_assoc($resultd);
                if($rowd['Title']<>'')
                $sumfehrest["$rowd[Title]"]+=$value;    
             $globalprint.= "     <tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>$rowd[Title]: $appfoundationID تعدیل $ftitle </td>
							<td colspan='3' class='f42_font' ><div id='divSumPrice$rown'>      
                            <input class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'      
                             value='".number_format($value)."' maxlength='23' readonly /></div></td>
                            <td class='f43_font'></td>
                        
                        </tr>";            
            }

            
        }
        //print $queryd; 
		     
            if ($type!=1 && $type!=3 )
                $readonlycoef="readonly";
            else if ($operatorcoid>0)    
                $readonlycoef=""; 
                
        $rowspan=0;$cntf=count($sumfehrest)+1;$rowsp=0;
            foreach($sumfehrest as $keyf=>$valuef)
            {
                $regcoef5=0;
                foreach($regioncoefval as $keyv=>$valuev)
                    if ($keyv==$keyf)
                        $regcoef5=$valuev;
                if ($appcoef5>0)
                    $c5=$appcoef5;
                else if ($regcoef5>0) 
                    $c5=$regcoef5;     
                    else
                        $c5=1;       
                        
                if (!($regcoef5>0))
                    $regcoef5=1;
			 $rowsp++;
			  if ($cntf>1 && $rowspan=="") $rowspan="rowspan='$cntf'";
              if (abs($valuef)<=2) $valuef='0';
                $sumc=round($valuef*$coef1*$coef2*$coef3*$c5);
                $sumcostscoefless+=$valuef;
			//	print $valuef.'*'.$coef1.'*'.$coef2.'*'.$coef3.'<br>';
			//<div style=\"width: 220px;\">
                $globalprint.= "    
                             <tr > 
                                <td  ></td>
								<td align='center' class='f14_font' ><div style=\"width: $Titlefmargin;\"> جمع فهرست بهاء $keyf (ریال)</div></td>
								<td colspan='1' class='f42_font'><div id='divsumcosts$rowsp' >     
									<input class='f37L_font' name='sumcosts$rowsp' type='text' class='textbox' 
                                      id='sumcosts$rowsp'       value='".number_format($valuef)."' readonly /></div></td>";
			if ($rowsp==1)
						$globalprint.= "    
								<td class='f14_font' $rowspan><div id='divcoef1'>      <input title=\"ضریب بالاسری\"    name='coef1' type='text'  class='f44_font'      id='coef1'  $readonlycoef     value='$coef1'  maxlength='12' onchange=\"summ()\"  /></div></td>
                                <td class='f14_font' $rowspan><div id='divcoef2' >     <input title=\"ضریب تجهیز و برچیدن\"   name='coef2' type='text'    class='f44_font'     id='coef2' $readonlycoef      value='$coef2'  maxlength='12' onchange=\"summ()\" /></div></td>
                                <td class='f14_font' $rowspan><div id='divcoef3'>      <input title=\"ضریب پیشنهادی\"  name='coef3' type='text'    class='f44_font'    id='coef3'   $readonlycoef    value='$coef3'  maxlength='12' onchange=\"summ()\" /></div></td>
						";
			if ($appcoef5>0)
            {
                if ($rowsp==1)
                    $globalprint.= "<td class='f14_font' $rowspan><div id='divcoef5$rowsp'>      <input title=\"ضریب منطقه ای\"  name='coef5$rowsp' type='text'    
                        class='f44_font'    id='coef5$rowsp'   $readonlycoef    value='$appcoef5'  maxlength='12' onchange=\"summ()\" /></div></td>
						";
            	
			   
            }
            else
            {
                if ($rowsp==1)
                    $globalprint.= "    
				               <td class='f14_font'><div id='divcoef5' >     <input title=\"ضریب منطقه ای\"   name='coef5$rowsp'
                                type='text'    class='f44_font'     id='coef5$rowsp'  $readonlycoef    value='$regcoef5'  maxlength='12' 
                                onchange=\"summ()\" />
                                
                                <input name='hcoef5' type='hidden'    id='hcoef5'     value='$regcoef5'   />
                                
                                </div></td>
                               ";
                    else
                        $globalprint.= "    
				               <td class='f14_font'><div id='divcoef5' >     <input title=\"ضریب منطقه ای\"   name='coef5$rowsp'
                                type='text'    class='f44_fontw'     id='coef5$rowsp'  readonly    value='$regcoef5'  maxlength='12' 
                                onchange=\"summ()\" /></div></td>
                               ";
                
            }			
                               $globalprint.= "
				               <td colspan=3>
                               <div id='divSumPricecosts$rowsp'>      <input class='f39_font'
                                name='SumPricecosts$rowsp' type='text' class='textbox'       id='SumPricecosts$rowsp'       
                                value='".number_format($sumc)."'  readonly /></div></td>
                                <td class='f43_fontR'> </td>
                            </tr>";


                if ($keyf=="آبیاری تحت فشار")
                {
                    $sumceilling+=$sumc*0.05;
                }
                else
                {
                    $sumceilling+=$sumc*0.04;
                    
                }
                
                        $sumcosts+=$sumc;   
		 		 if ($cntf>1) $rowspan="hidden";
             }
             if ($totfehrestmanual<=0)
                $totfehrestmanual=$sumcostscoefless;

           /*ثبت پیش فاککتور و هزینه اجرای سایر هزینه ها
            if ($login_isfulloption==1 || ($login_RolesID!=2 && $login_RolesID!=9))
            $globalprint.= "    
                             <tr > 
                                <td  ></td>
								<td align='center' class='f14_font' ><div style=\"width: $Titlefmargin;\"> جمع فهارس بهاء (ریال بدون ضرایب)</div></td>
								<td colspan='1' class='f42_font'><div id='divsumcosts$rowsp' >     
									<input class='f37L_font' name='sumcosts$rowsp' type='text' class='textbox' 
                                      id='sumcosts$rowsp'       value='".number_format($sumcostscoefless)."' readonly /></div></td>";
         else 
            $globalprint.= "    
                             <tr > 
                                <td  ></td>
								<td align='center' class='f14_font' ><div style=\"width: $Titlefmargin;\"> جمع فهارس بهاء (ریال بدون ضرایب)</div></td>
								<td colspan='1' class='f42_font'><div id='divtotfehrestmanual' >     
									<input class='f46_font' name='totfehrestmanual' type='text' class='textbox' 
                                      id='totfehrestmanual'       value='".number_format($totfehrestmanual)."'  /></div></td>";
                                      
         */
           $globalprint.= "    
                             <tr > 
                                <td  ></td>
								<td align='center' class='f14_font' ><div style=\"width: $Titlefmargin;\"> جمع فهارس بهاء (ریال بدون ضرایب)</div></td>
								<td colspan='1' class='f42_font'><div id='divsumcosts$rowsp' >     
									<input class='f37L_font' name='sumcosts$rowsp' type='text' class='textbox' 
                                      id='sumcosts$rowsp'       value='".number_format($sumcostscoefless)."' readonly /></div></td>";
                                      
                                      
                                      
              $globalprint.= "
				               <td colspan=3>
                               <div id='divSumPricecosts$rowsp'>      <input class='f39_font'
                                name='SumPricecosts$rowsp' type='text' class='textbox'       id='SumPricecosts$rowsp'       
                                  readonly /></div></td>
                                <td class='f43_fontR'> </td>
                            </tr>";
             
             
             
            if (count($sumfehrest)>1)
                $globalprint.= "     <tr > 
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'><div >جمع فهارس بهاء(ریال) </div></td>
                            <td colspan='3' class='f42_font'><div id='divsumcoststotal'>      <input class='f37_font' name='sumcoststotal' type='text'
                             class='textbox'       id='sumcoststotal'       value='".number_format($sumcosts)."' maxlength='23' readonly /></div></td>
                            <td class='f43_font'></td>
							<td><div style=\"width:  $Rmargin;\"></div> </td>
                        </tr>
                        ";
                        
            $rown++;
            if ($sumtajhiz>($sumceilling) && ($login_RolesID==13 || $login_RolesID==14))
        	$errortajhiz="<a>مبلغ تجهیز از حد مبلغ مجاز بالاتر می باشد</a>";
            else
        	   $errortajhiz="";    
            if ($coef4==1)
            $sumoptitle="جمع عملیات اجرایی $opacc";
            else
            
            $sumoptitle="جمع عملیات اجرایی با ارزش افزوده ($coef4) $opacc";
           if ($readonlydesc!='')
           $tajstr="<tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>تجهیز و برچیدن کارگاه مقطوع (ریال) $errortajhiz</td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'  id='lasttajhiz'  name='lasttajhiz'       
                              value='".number_format($sumtajhiz)."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr>";
            else
            $tajstr="<tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>
                            
                            <a href=equip_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_10_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
                            
                            
                            
                            تجهیز و برچیدن کارگاه مقطوع (ریال) $errortajhiz</a></td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'   id='lasttajhiz'  name='lasttajhiz'    
                              value='".number_format($sumtajhiz)."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr>";
                        
            $sumnewpricestr="";
        if ($sumnewprice!=0)
            $sumnewpricestr=" <tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>$newpricetitle(ریال)</td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'  id='lastnewprice'  name='lastnewprice'       
                              value='".number_format($sumnewprice)."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr> ";
            
            $globalprint.=" $tajstr $sumnewpricestr";
            
            $sumcoststaxless=$sumcosts+$sumtajhiz+$sumnewprice;
            
            if ($coef4>1)
            $globalprint.="<tr >
                           
                           <td  ></td>
                            <td colspan=6  align='center' class='f41_font'>جمع عملیات اجرایی(ریال)</td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'  id='lastnewprice'  name='lastnewprice'       
                              value='".number_format(($sumcoststaxless) )."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr>";
            
            $globalprint.= "    
                        
                      
                        
                        
                        
                        
                        <tr > 
        <td  ></td>
                           
                            <td colspan=9  align='center' class='f34_font'><div >$sumoptitle</div></td>
							<td class='f38_font'><div id='divSumPricecoef4' >      
                            <input  class='f39_font' name='SumPricecoef4' type='text' class='textbox'      
                             id='SumPricecoef4'       value='".number_format(($sumcosts+$sumtajhiz+$sumnewprice)*$coef4 )."' readonly /></div></td>
                            
                        </tr>
                        
                        ";
                        $sumcosts=($sumcosts+$sumtajhiz+$sumnewprice)*$coef4;
            $TotlaValues+=$sumcosts ;
            
    ///////////////////////////////////////////////////////////////////////////////سایر هزینه هل
    if ($subprjcondition=="")
    {            
            //$globalprint.= $login_OperatorCoID.'salam';
            if ($operatorcoid>0 || $removeup!="")
                $unpredictedval=0;
            else $unpredictedval=round($TotlaValues*$unpredictedcost/100);

            $TotlaValues=$TotlaValues+$othercosts1+$othercosts2+$othercosts3+$othercosts4+$othercosts5+$TransportCostunder+$designcost+$unpredictedval;
            if ($type!=1)
                $readonlyother="readonly";
            else
                $readonlyother="";
           
           
           if (($type==3) && ($login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ) )
                $readonlyother=''; 

         //print $TotlaValues;
	     $globalprint.= "   	 <td ><input name='TotlaValues' type='hidden' class='textbox' id='TotlaValues'  value='$TotlaValues'   /></td>
					<td ><input name='TpC' type='hidden' class='textbox' id='TpC'  value='$TpC'   /></td>
					<td ><input   name='coef4' type='hidden'    class='f44_font'     id='coef4'   value='$coef4' /> </td>
                         
		 ";
                      			
         //if ($operatorcoid>0) 
            $strtransportless="<input onchange='summ()' name='transportless' type='checkbox' id='transportless'   $transportless />";   
            
		 if ($digitless=='checked') $rond='مبلغ روند شده!';else $rond='روند مبلغ';
			$strdigitless="<input onchange='digit()' name='digitless' type='checkbox' id='digitless'  value='1' $digitless /> <font size='1.5'>$rond</font>";   
        $_POST['showotherz']=$showotherz;
        $rowspanothercost=7;
        if (!(abs($othercosts3)>0) && !($_POST['showotherz']>0))//هزینه مطالعات
		  $rowspanothercost--;
        //if (!($TransportCostunder>0))//هزینه حمل
		//  $rowspanothercost--;
        if (!(abs($othercosts1)>0)  && !($_POST['showotherz']>0))
		  $rowspanothercost--;
        if (!(abs($othercosts2)>0) && !($_POST['showotherz']>0))
		  $rowspanothercost--;
        
        if (!($unpredictedval>0))
		  $rowspanothercost--;
          
          foreach ($arrayinvoicesdone as $i => $value)  
        $rowspanothercost++; 
        
        
            $showotherzselected="";
          if ($showotherz>0)
            $showotherzselected="checked";
        $globalprint.= "
                           
                    
                                <tr > 
                                <td  ></td>
                           
                                    <td rowspan='$rowspanothercost'  align='center' class='f40_font' onclick=\"showdiv('othercosts');\">سایر هزینه ها
                                    <input onChange='selectpage()' name='showotherz' type='checkbox' id='showotherz'  value='1' $showotherzselected />
                                    <input  name='showotherz2' type='hidden' id='showotherz2'   />
                                    
                                    <font size='1.5'>نمایش همه</font>
                                    </td>
                            ";
        if ((abs($othercosts1)>0)  || ($_POST['showotherz']>0))
        $globalprint.="        
									<td colspan=9 class='f14_font'>فونداسیون/اتاقک پمپاژ(براساس دستورالعمل)</td>
                                    <td   class='f14_font'><div id='divothercosts1'>      <input class='f46_font' $readonlyother onKeyUp=\"convert('othercosts1');summ();\"   name='othercosts1'  type='text' class='textbox'       id='othercosts1'       value='".number_format($othercosts1)."'  /></div></td>
                                </tr>
                              
                                <tr > 
                                    <td  ></td>
                                      ";
        if ((abs($othercosts2)>0) || ($_POST['showotherz']>0))                              
        $globalprint.="
                           <td colspan=9 class='f14_font'>حوضچه پمپاژ/استخر(براساس دستورالعمل)</td>
						   
                                    <td    class='f14_font'><div id='divothercosts2'>      <input class='f46_font' $readonlyother onKeyUp=\"convert('othercosts2');summ();\" name='othercosts2' type='text' class='textbox'       id='othercosts2'       value='".number_format($othercosts2)."'  /></div></td>
                                </tr>
                       
                                <tr > 
    <td ></td>
     ";
        $globalprint.="
	<td colspan=9 ><input  placeholder='".$othercosts4text."' type='text' class='f510_font' class='textbox'  name='othercosts4text'  id='othercosts4text'     value='".$othercosts4textv."'  /></td>
	 
    <td class='f14_font'><div id='divothercosts4'> 
				<input class='f46_font' $readonlyother onKeyUp=\"convert('othercosts4');summ();\" name='othercosts4' type='text' class='textbox'       
					id='othercosts4'       value='".number_format($othercosts4)."'  /></div></td>
                                </tr>
                                
                              ";
        //if ($TransportCostunder>0)
        if ($login_RolesID==1)
        $trant="هزینه حمل(بر اساس دستور العمل $Cost%)";
        else
        $trant="هزینه حمل";
   
   if ((abs($TransportCostunder)>0) || ($_POST['showotherz']>0))
            $globalprint.="<tr ><td  ></td>
                           <td colspan=9 class='f14_font'>$trant
                           $strtransportless
                           </td><td   class='f14_font'><div id='divTransportCostunder'>      <input class='f45_font' name='TransportCostunder'  type='text' class='textbox'       id='TransportCostunder'  readonly    value='".number_format($TransportCostunder)."'  /></div></td>
                           </tr>";
	else 					   
	   $globalprint.="<tr ><td ></td>
                           <td colspan=9 class='f14_font'></td>
						   <td   class='f14_font'></td>
                           </tr>";
						   
//		                           <td colspan=9 class='f14_font'>هزینه مطالعات/تعدیل و...</td>
        if ((abs($othercosts3)>0) || ($_POST['showotherz']>0))//هزینه مطالعات
            $globalprint.="<tr ><td  ></td>
			
						   	<td colspan=9 ><input  placeholder='".$othercosts3text."' type='text' class='f510_font' class='textbox'  name='othercosts3text'  id='othercosts3text'     value='".$othercosts3textv."'  /></td>

                           <td  class='f14_font'><div id='divothercosts3'>      <input class='f46_font' $readonlyother onKeyUp=\"convert('othercosts3');summ();\" name='othercosts3' type='text' class='textbox'       id='othercosts3'       value='".number_format($othercosts3)."'  /></div></td>
                           </tr>";
        if ($unpredictedval>0)
        $globalprint.="
                                <tr > 
                                    <td  ></td>
                           <td colspan=9 class='f14_font'>هزینه های پیش بینی نشده(بر اساس دستور العمل $unpredictedcost%) 
                           <input  name='removeup' type='checkbox' id='removeup' $removeup   />
                           </td>
                                    <td  class='f14_font'> <input class='f45_font' name='unpredictedval'  type='text' class='textbox'    readonly   id='unpredictedval'       value='".number_format($unpredictedval)."'   /></td>
                                </tr>
                                <tr > 
                                    <td  ></td>";
        else
        $globalprint.="<tr > 
                                    <td  ><input  name='removeupval' type='hidden' id='removeupval' value='$removeupval'   />&nbsp;&nbsp;&nbsp;</td>";        
        $globalprint.="                        
                                 
                           <td colspan=9 class='f14_font'>
                           <a  target='_blank' href=appfarmerbring_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".rand(10000,99999)."'>
                             مبلغ برآورد کالا و خدمات متقاضی (آورده متقاضی) </a>
                           
                           </td>
                                    <td  class='f14_font'><div id='divothercosts5'>      
                                    <input class='f46_font' $readonlyother onKeyUp=\"convert('othercosts5');summ();\" name='othercosts5' 
                                    type='text' class='textbox'       id='othercosts5'   readonly    value='".number_format($othercosts5)."'  /></div></td>
                                </tr>
                                
                                
                    
                ";
			
            
   foreach ($arrayinvoicesdone as $i => $value)
       {
       $rown++;
            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
            {
                $globalprint.= "    
				<tr > 
					<td  ></td>
					<td colspan=6  align='center' class='f34_font'><div >$i (در تعهد پیمانکار و متقاضی)</div></td>
					<td colspan=3 class='f35_font' ><div id='divSumPrice$rown' > <input  class='f37_font'  name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='20' readonly /></div></td>
                    <td class='f36_font'></td>
                </tr>";
                //$globalprint.= $i." ".$value;               
            }        
       }
       
                $TotlaValuesnotround=$TotlaValues;	                       
        if ($digitless=="checked") 
            {
			     $TotlaValues=floor($TotlaValues/100000)*100000;
			}
                $TotlaValuesperha='0';
                if ($DesignArea>0)
                    $TotlaValuesperha=$TotlaValues/$DesignArea;
            
            
            $strbelaavaz="";
            $belaavaz=calculatebelavaz($creditsourceid,$ApplicantMasterID,$TotlaValues,$criditType);
            //if ($currbelaavaz==0 || $prjtypeid==0)     
            //    $strbelaavaz=",belaavaz='$belaavaz'";              
            mysql_query("update applicantmaster set SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',LastFehrestbaha='$sumcostscoefless',LastFehrestbahawithcoef='$sumcosts'
                                ,LastChangeDate='".date('Y-m-d H:i:s')."',LastTotal='$TotlaValues',
                                unpredictedcost='$unpredictedcost',TotlainvoiceValues='$TotlainvoiceValues'
                                ,TransportCostunder='$TransportCostunder' $strbelaavaz where ApplicantMasterID='$ApplicantMasterID'"); 
                                
            
			
			
			
            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
            $globalprint.= "
								<tr >  
                                <td  ></td>
                           
                                    <td colspan=10 class='f47_font'>جمع کل(ریال)&nbsp (فهرست بها:$fb) $strdigitless</td> 
                                    <td   class='f48_font'>
                                    <input class='f49_font'
 name='AllSumAll' type='text' class='textbox' id='AllSumAll' value='".number_format($TotlaValues)."'  readonly />
 <input 
 name='AllSumAllnotround' type='hidden' class='textbox' id='AllSumAllnotround' value='".number_format($TotlaValuesnotround)."'  readonly /></td>
                                </tr>
                                
                                <tr>  
                               <td  ></td>
                           
                                    <td colspan=10 class='f47_font'>هزینه در واحد هکتار &nbsp(ریال)($DesignArea هکتار)&nbsp</td> 
                                    <td   class='f48_font'><input class='f49_font'
 name='AllSumAllperha' type='text' class='textbox' id='AllSumAllperha' value='".number_format($TotlaValuesperha)."'  readonly /></td>
                                </tr>
                                <tr>  
                                <td  ></td>
                           
                                    <td colspan=10 class='f47_font'>حسن انجام کار(ریال)</td> 
                                    <td   class='f48_font'><input class='f49_font'
 name='hasanprice' type='text' class='textbox' id='hasanprice' value='".number_format(round( ($sumcosts+$othercosts1+$othercosts2+$othercosts4)/10))."'  readonly /></td>
                                </tr>
                                <tr>  
                               <td  ></td>
                           
                                    <td colspan=2 class='f47_font'>حسن انجام تعهدات (ریال)</td> 
                                    <td colspan=8  class='f48_font'><input class='f510_font'   name='hattitle' 
                                    type='text' class='textbox'       id='hattitle'       value='".($hattitle)."'  /></td>
                                    <td   class='f48_font'><input class='f46_font'  onKeyUp=\"convert('hatval');\" name='hatval' 
                                    type='text' class='textbox'       id='hatval'       value='".number_format($hatval)."'  /></td>
                                </tr>
                                ";
}
/////////////////////////////////////////////////////////////////////////////////////////

                                
                $globalprint.="
                                
                      <input name='sumcoststotalh' id='sumcoststotalh' type='hidden'      value='".number_format($sumcosts)."' />  </td>   
                            
                      ";
                      
                        if ($type==1 || $type==5)
                            $ronlytafkik="";
                        else
                            $ronlytafkik="readonly";
                            
                        if ($login_RolesID!=2)
                        {
                            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
                            $globalprint.= "</tr>
							</table>
							
                            <p id='psh_areatable' ></p>
							 <table width='$Rwidth%' id='ish_areatable'>
           <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
			         onclick=\"showhidediv('sh_areatable');\">جدول تفکیک سطح سیستم های آبیاری طرح
					 </td>
						 </tr>
					 </table>
                    
            ";
			 
    $globalprint.= "
    <table id='sh_areatable' style='display:none;' width='$Pwidth%'> 
   <tr><td colspan='7'><input class='no-print' id='chksh_areatable' type='checkbox' onChange=\"setpagereak('psh_areatable')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td></tr>
					 
							<tr>
							<td  ></td>
							</tr>
							<tr>
                            <td /><div style=\"width:  $Rmargin;\"><td colspan='11' class='f31_font'>جدول تفکیک سطح سیستم های آبیاری طرح</td> 
							 <td><div style=\"width:  $Lmargin;\"></div></td>
				
                            </tr><div style='text-align:center;' >";  
                         //صورت وضعیت $ApplicantMasterIDmaster
						 //مطالعات $ApplicantMasterID
						 
									$queryhc="select creditsourceID,ApplicantMasterID from applicantmaster 
									where applicantmaster.BankCode='$BankCode' and applicantmaster.DesignerCoID>0
									";
									//print $queryhc;
									$resulthc = mysql_query($queryhc);
									$rowhc = mysql_fetch_assoc($resulthc);
                            		$creditsourceidj=$rowhc['creditsourceID'];
									$ApplicantMasterIDj=$rowhc['ApplicantMasterID'];
						 
						 
						 
						 
						 
						 
                             $queryh="Select designsystemgroups.DesignSystemGroupsID,designsystemgroups.Title
							 ,designsystemgroupsdetail.hektar,designsystemgroupsdetail.price,designsystemgroupsdetail.yeild
							  ,case designsystemgroupsdetail.DesignSystemGroupsID 
								when 1 then creditsource.baranival 
								when 2 then creditsource.ghatreeval 
								when 3 then creditsource.sathival 
								when 4 then creditsource.sathival 
								when 5 then creditsource.ghatreeval 
								when 6 then creditsource.centerval 
								when 7 then creditsource.zirval 
								else 0 end belaavazval
								,case designsystemgroupsdetail.DesignSystemGroupsID 
								when 1 then creditsource.baraniper 
								when 2 then creditsource.ghatreeper 
								when 3 then creditsource.sathiper 
								when 4 then creditsource.sathiper 
								when 5 then creditsource.ghatreeper 
								when 6 then creditsource.centerper 
								when 7 then creditsource.zirper 
								else 0 end belaavazper
							   
                               from designsystemgroups
							    left outer join designsystemgroupsdetail on 			
									designsystemgroupsdetail.DesignSystemGroupsID = designsystemgroups.DesignSystemGroupsID
                                    and ApplicantMasterID='$ApplicantMasterIDj'
									
								left outer join creditsource on creditsource.creditsourceID='$creditsourceidj'	
                                    order by designsystemgroups.Code";
                            $cnth=0;
                            $resulth = mysql_query($queryh);
							
                           //print $queryh;
                         //exit;
                           
                            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
                            $globalprint.= "<tr><td><td />
							
										<td  colspan='1' class='f50_font' style='width: 60%;'>سیستم آبیاری</td> 
                                    	<td  colspan='2' class='f50_font' style='width: 20%;'>سقف بلاعوض (م ر) </td> 
                                        <td  colspan='1' class='f50_font' style='width: 20%;'>مساحت(هکتار)</td>
                                        </tr>";
                            $totprice=0;
                            $totsyshek=0;
                            $cntyield=0;
							$sumbelaavazval=0;
    	                    while($rowh = mysql_fetch_assoc($resulth))
                            {
							    if ($rowh['yeild']) $cntyield++;
                                $cnth++;
								$belaavazval='';
                                //echo $rowh['belaavazval'].'sa';
                               $belaavazval= $rowh['hektar']*$rowh['belaavazval']/1000000;
							   $sumbelaavazval+=$belaavazval;
							   if ($belaavazval>0)
							   $belaavazval=$belaavazval.' = '.$rowh['belaavazval'].' × '.$rowh['hektar'];
							   else $belaavazval='';
                                        if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
                                        $globalprint.= "<tr>
										<td><td/>
										
										<td  colspan='1' class='f10_font'>$rowh[Title] 
											<input $ronlytafkik  name='DesignSystemGroupsID$cnth' type='hidden' class='textbox' id='DesignSystemGroupsID$cnth' value='".$rowh['DesignSystemGroupsID']."'  />
										</td>
									
										<td  colspan='2' class='f10_font'> 
										$belaavazval
										</td>
										  
										<td  colspan='1' class='f10_font'>
											<input $ronlytafkik class='f51_font' onKeyUp=\"calc1();\" name='syshek$cnth' type='text' class='textbox'       id='syshek$cnth'       value='".$rowh['hektar']."'  />
										</td>
										
                                        </tr>";
                                
                            $totprice+=$rowh['price'];
                            $totsyshek+=$rowh['hektar'];
                                
                            }
							$belaavasum=0;
							$belaavasum=floor($TotlaValuesnotround*0.85/100000)/10;
							
                             if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
                                        $globalprint.= "<tr>
										<td><td/>
										<td  colspan='1' class='f50_font'>سقف بلاعوض بر اساس نوع سیستم</td> 
										<td  colspan='1' class='f50_font'>$sumbelaavazval</td> 
										<td  colspan='1' class='f50_font'>مجموع</td> 
										<td  colspan='1' class='f10_font'><input $ronlytafkik class='f51_font' name='totsyshek' type='text' class='textbox'       id='totsyshek'       value='".$totsyshek."'  /></td>
										
									    </tr>
									
										<tr>
										<td><td/>
										<td  colspan='1' class='f50_font'>سقف بلاعوض بر اساس مبلغ کل</td> 
										<td  colspan='1' class='f50_font'>$belaavasum</td> 
											
										<td  colspan='2' class='f50_font'></td> 
										
										</tr>
										
										";
                        
                        }
                            

                                             
                $globalprint.= "
				</table>
				<table align=\"left\">
				";
            
       if ($type==1 || $type==5)
         {
		     $globalprint.= "<td></td><td ><input   name='submit' type='submit' class='no-print' id='submit' value='ثبت' /></td></tr></div>";  
         }
           else if ($type==3)
         {
            

    
                        //print $issurat.'sa';
                        if (($operatorcoid>0)&& ($login_RolesID==10))
                        {
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (43,42)
                                order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($issurat==1 && ($login_RolesID==2 || $login_RolesID==13 || $login_RolesID==14 ) )
                        {
                              switch ($login_RolesID) 
                              {
                                case 2: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID=41
                                order by _key   COLLATE utf8_persian_ci"; break; 
                                
                                case 13: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (44,45)
                                order by _key   COLLATE utf8_persian_ci"; break;
                                case 14: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (44,45)
                                order by _key   COLLATE utf8_persian_ci"; break;    
                              }
                        }
                        else if ($login_RolesID==32)//رئیس مهندسی زراعی
                        {
                            if ($creditsourceid==21)
                        $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from applicantstates
                                where applicantstates.applicantstatesID in (0,20,52)
                                        order by _key   COLLATE utf8_persian_ci";
                        else    
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='32' and appstatedone.ostan=substring('19',1,2)
                                and appstatedone.prjtypeid='1'
                                        order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($login_RolesID=='13')
                        {
                            if ($operatorcoid>0) 
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (15,28,9)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            else if ($prjtypeid==1)
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='31' and applicantstates.applicantstatesID not in (15,28,9)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='1'
                                order by _key   COLLATE utf8_persian_ci";
                            else
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (27,32,28,30,47)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($login_RolesID=='17' && $prjtypeid==0)
                        {
                            
            
                            if ($issurat==1)
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (24,30,46)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            
                            else if ($operatorcoid>0) 
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (24,45,46)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            else if ($incntcnt>0 && $ApplicantstatesID!=23)
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (30,45,44,46)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            else
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID  in (34,46)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            //print $query;
                        }
                        
                        else if ($login_RolesID=='18')
                        {
                            $query18="Select applicantstatesID from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID in (10,39)
                            and stateno=(Select max(stateno) stateno from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID in (10,39))";
                            $result18 = mysql_query($query18);
                            $row18 = mysql_fetch_assoc($result18);
                            if ($row18['applicantstatesID']=='10')  
                                      
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID<>36
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                        order by _key   COLLATE utf8_persian_ci";
                            else $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID<>12
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                        order by _key   COLLATE utf8_persian_ci";
                            //print $query;
                                            
                        }
                        else if ($prjtypeid==1 && $login_RolesID==14)
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='31' and applicantstates.applicantstatesID not in (15,28,9)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='1'
                                order by _key   COLLATE utf8_persian_ci";
                                
                        else
                        $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and appstatedone.ostan=substring('$login_CityId',1,2)
                                and appstatedone.prjtypeid='$prjtypeid'
                                        order by _key   COLLATE utf8_persian_ci";
                        
                        
                        
                        if ($prjtypeid==1)
                        if ($login_RolesID=='17' || $login_RolesID=='13' || $login_RolesID=='14')
                            {
                                if ($login_RolesID=='17')
                                $sql="select round(sum(invoicemaster.tot)/1000000) done,ifnull(wsquota.val,0)+ifnull(wsquota.val2,0) wsquotaval 
                                from applicantmaster
                                    inner join invoicemaster on invoicemaster.applicantmasterid=applicantmaster.applicantmasterid
                                    inner join applicantmasterdetail on 
                        case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
                        applicantmasterdetail.ApplicantMasterIDmaster 
                        else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
                        and ifnull(applicantmasterdetail.prjtypeid,0)=1 
                        and substring(applicantmaster.cityid ,1,4)=substring('$cityid14' ,1,4)
                                    and applicantmaster.applicantstatesID not in (23,51,25)
                                    inner join wsquota on wsquota.creditsourceID= applicantmaster.creditsourceID 
                                    and substring(wsquota.CityId,1,4)=substring(applicantmaster.CityId,1,4)  
                                    where applicantmaster.creditsourceID='$creditsourceid'
                                    ";
                                    else 
                                    $sql="select round(sum(invoicemaster.tot)/1000000) done,ifnull(wsquota.val,0)+ifnull(wsquota.val2,0) wsquotaval 
                                    from applicantmaster
                                    inner join invoicemaster on invoicemaster.applicantmasterid=applicantmaster.applicantmasterid
                                    inner join applicantmasterdetail on 
                        case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
                        applicantmasterdetail.ApplicantMasterIDmaster 
                        else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
                        and ifnull(applicantmasterdetail.prjtypeid,0)=1 
                        and substring(applicantmaster.cityid ,1,4)=substring('$cityid14' ,1,4)
                                    and applicantmaster.applicantstatesID not in (23,52,46,51,25)
                                    inner join wsquota on wsquota.creditsourceID= applicantmaster.creditsourceID 
                                    and substring(wsquota.CityId,1,4)=substring(applicantmaster.CityId,1,4)  
                                    where applicantmaster.creditsourceID='$creditsourceid'
                                    ";
                                    
                                $result = mysql_query($sql);
                                $row = mysql_fetch_assoc($result);
                                    print "<br>سهمیه:".$row['wsquotaval']." میلیون ریال";
                                    print "<br>ارسال شده:".$row['done']." میلیون ریال";
                                    
                                //print $sql;
                                if ($row['done']>$row['wsquotaval'])
                                {
                                    print "به دلیل اتمام سهمیه اعتبار شهرستانی، امکان ارجاع  فراهم نمی باشد";
                                    $query="";
                                    if ($login_RolesID=='13' || $login_RolesID=='14')
                                    $query="Select '0' As _value, ' ' As _key Union All
                                    Select '51' As _value, 'کارشناس استان به م ج شهرستان' As _key
                                        order by _key   COLLATE utf8_persian_ci";
                                    //exit;
                                    
                                }
                            }
                        
                        //print $query;
                        if ($prjtypeid==1 && ($login_RolesID==13 || $login_RolesID==14))
                        $query18="Select appstatesee.applicantstatesID from appstatesee 
                        inner join applicantmaster on applicantmaster.applicantmasterid='$ApplicantMasterID' and 
                        appstatesee.applicantstatesID=applicantmaster.applicantstatesID
                        where RolesID='31' and ostan=substring('$login_CityId',1,2) 
                        and prjtypeid='$prjtypeid';";
                        else
                        $query18="Select appstatesee.applicantstatesID from appstatesee 
                        inner join applicantmaster on applicantmaster.applicantmasterid='$ApplicantMasterID' and 
                        appstatesee.applicantstatesID=applicantmaster.applicantstatesID
                        where RolesID='$login_RolesID' and ostan=substring('$login_CityId',1,2) 
                        and prjtypeid='$prjtypeid';";
                        //print $query18;
                        
                            $result18 = mysql_query($query18);
                            $row18 = mysql_fetch_assoc($result18);
                            if ($row18['applicantstatesID']>0)
                            {
                                $IDapplicantstatesID='';
                                
                                //{
                                    $result = mysql_query($query);
        
        	                    $IDapplicantstatesID[' ']=' ';
        	                    while($row = mysql_fetch_assoc($result))
                                {
                                    $IDapplicantstatesID[$row['_key']]=$row['_value'];
                                    
                                }
                            }
                        
                       // }
                        //if ($login_RolesID==1) 
                        //print $query18;
    
      
                            
                            $globalprint.= "
                             <tr>
                             ";
                             //if (in_array($login_RolesID, $permitrolsidforviewdetail)) 
                             if ($ApplicantstatesID==43)//مشاور ناظر به ناظر عالي
                             {
                                $globalprint.="
                             <tr>
                             <td  >&nbsp;</td>
                
                              <td  colspan='1' class='label'>سازه</td>
                              <td  colspan='1' class='label'>فهرست بها</td>
                              <td  colspan='8' class='label'>توضیحات:</td>
                              </tr>
                             
                             "; 
                             /*
                                appfoundationID شناسه سازه طرح
                                appfoundation جدول سازه های طرح
                                applicantmasterdetail جدول ارتباطی  طرح ها
                                ApplicantMasterIDmaster شناسه طر اجرایی
                                ApplicantMasterIDsurat شناسه طرح صورت وضعیت
                                */
                                $queryfd = "select distinct appfoundation.appfoundationID _value,appfoundation.Title _key from appfoundation
                                where (ApplicantMasterID='$ApplicantMasterID' or ApplicantMasterID in 
                                (select ApplicantMasterIDmaster from applicantmasterdetail where ApplicantMasterIDsurat='$ApplicantMasterID'))
                                 ";
            					$allg2fd = get_key_value_from_query_into_array($queryfd);
                                //echo $queryfd;
                                for ($i12=1;$i12<=5;$i12++)
                                $globalprint.="
                             <tr>
                
                              <td  colspan='1' class='label'>".select_option("sazeadd$i12",'',',',$allg2fd)."</td>
                              <td  colspan='1' class='label'>فهرست بها</td>
                              <td  colspan='8' class='label'>توضیحات:</td>
                              </tr>
                             
                             ";   
                             }
                             
                             $globalprint.="
                              </tr>
                               <tr>
                             <td  >&nbsp;</td>
                
                              <td  colspan='1' class='label'>تاریخ</td>
                              <td  colspan='1' class='label'>وضعیت</td>
                              <td  colspan='8' class='label'>توضیحات:</td>
                              </tr>
                              ";
                        while($rowallstates = mysql_fetch_assoc($resultallstates))
                        { 
                            $SaveDate = $rowallstates['SaveDate'];
                            $laststateno=$rowallstates['stateno'];
                            $lastDescription=$rowallstates['Description'];
                            $applicantstatestitle=$rowallstates['applicantstatestitle'];  
                            if (strlen($lastDescription)>1)
                            $globalprint.= "
                              
                              <tr>
                               <td  >&nbsp;</td>
                
                                <td><textarea id='Descriptiondate' name='Descriptiondate' rows='3'  cols='7' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                              
                              <td colspan='1' ><textarea id='laststatetitle' name='laststatetitle'   cols='28' readonly rows='3'>$applicantstatestitle</textarea></td>
                            
                              
                              <td  colspan='8'>
                              <textarea id='lastDescription' colspan='1' name='lastDescription' rows='3' cols='120' readonly >".$lastDescription."</textarea></td>
                              </tr>
                              "; 
                              else
                            $globalprint.= "
                              
                              <tr> <td  >&nbsp;</td>
                
                              <td colspan='1' ><input   value='".gregorian_to_jalali($SaveDate)."' size=10 readonly ></input></td>
                             
                              <td colspan='1' ><input id='laststatetitle' name='laststatetitle'   value='$applicantstatestitle' size=31 readonly ></input></td>
                             
                              
                              <td  colspan='8'>
                              <input id='lastDescription' colspan='1' name='lastDescription' value='$lastDescription' size=120 readonly ></input></td>
                              </tr>
                              "; 
                                
                        }
                       
                    if (($login_RolesID=='18')&& ($DesignerCoID>0) )
                    {
                        $queryselect='select operatorcoID as _value,Title as _key from operatorco  order by _key  COLLATE utf8_persian_ci';
                       
                        $result = mysql_query($queryselect);
                        
                       $IDselect[' ']=' ';
	                    while($row = mysql_fetch_assoc($result))
                        {
                            $IDselect[$row['_key']]=$row['_value'];
                            
                        }
                        if ($isbandp>0) $checked='checked'; else $checked='';
                        $globalprint.= "  <tr>
                            <td>&nbsp </td> 
                              </tr>
                              
                              <tr><td class='label'>ترک تشریفات:</td><td class='data'>
                              <input name='isbandp' $checked type='checkbox' class='textbox' id='isbandp'  /></td></tr>
                              
                             <tr>
                      ".select_option('operatorcoIDbandp','شرکت&nbspمجری',',',$IDselect,0,'','','2','rtl',0,'',$operatorcoIDbandp,'','225')."
                              <tr>
                              </tr>
                            
                            
                             <td  class='label'>تاریخ انتخاب مجری:</td> 
                      <td ><input   value='$Datebandp'
                      class='f52_font'
                             name='Datebandp' type='text' class='textbox' id='Datebandp'    /></td>
                            
                            <tr>
                              </tr>
                              
                              
                            
                             ";
                    }
                      
                if ($login_RolesID=='16'  || $login_RolesID=='6' || $login_RolesID=='7' || $login_RolesID=='1')//صندوق
                {
                  $globalprint.= "  <table> 
								<tr><td>&nbsp </td>  
									<td  colspan='15'>---------------------------------------------------------------------------------------------------------------------------------------------------------- </td> 
								</tr>";    
					
					$query="select creditsourceID as _value,title as _key from creditsource 
                             where ostan=substring($soo,1,2) ORDER BY sortorder Desc"; //print $query;exit;
        				 $ID = get_key_value_from_query_into_array($query);
						 
					$query="select DesignerCoID as _value,Title as _key from designerco where isnazer=1 ORDER BY _key";
                            $IDn = get_key_value_from_query_into_array($query);
                            
						 if ($login_RolesID=='16' || $login_RolesID=='6'  || $login_RolesID=='7' || $login_RolesID=='1')
                         {
                            $globalprint.= "
								<tr><td>&nbsp </td>"
							.select_option('creditsourceID','منبع تامین اعتبار: ',',',$ID,0,'','','1','rtl','','',$creditsourceid,'','125')
							.select_option('nazerID','مشاور ناظر:',',',$IDn,0,'','','1','rtl','','',$nazerID,'','125');
                            
                            if ($prjtypeid==0)
                            $globalprint.= "
                            <td  colspan='1' class='label'>بلاعوض پیشنهادی:</td>
									<td >
                                    <input readonly type='text' class='textbox'  value='$belaavaz' size='12' />
                                    
                                    </td>
                                    
                            <td  colspan='1' class='label'>بلاعوض تایید شده:</td>
									<td >
                                    <input name='bela_sand' type='text' class='textbox' id='bela_sand' value='$currbelaavaz' onKeyUp=\"convert('bela_sand')\" size='12' />
                                    
                                    </td>";
                     		
                        }
						 
                    $strbring="";
                    $strsokuk="";
					
					 if ($login_RolesID==16 || $login_RolesID==1) 
                     {
                        $lbl='کد صندوق:';$titr1='خودیاری نقدی (ریال):';$titr2='خودیاری غیرنقدی (ریال):';
                        
                        $strbring=" 
								<tr>
									<td  colspan='2' class='label'>مبلغ برآورد کالا و خدمات متقاضی:</td>
									<td ><input onChange='tempvalchange();' readonly value='".number_format($othercosts5)."'  class='f52_font'
											name='seltotaltemp' type='text' class='textbox' id='seltotaltemp'    /></td>
								</tr>";
                              
                              $strsokuk=" 
								<tr>
									<td colspan='2' class='label'>درصداعتبار از اسناد خزانه اسلامی:</td>
									<td ><input   value='".number_format($Freestate)."' class='f52_font'
											name='sokuk' type='text' class='textbox' id='sokuk'    /></td>
								</tr>";
                              
                     }
					 if ($login_RolesID==7) {$lbl='کد بانک(پروژه):';$titr1='سهم نقدی شریک(ریال):';$titr2='سهم بانک(ریال):';}

                        $globalprint.= "  
								<tr><td>&nbsp </td> </tr>
								
								<tr><td>&nbsp </td> 
									<td  class='label'>شماره نامه تاییدیه:</td>
									<td ><input   value='$letterno'  class='f52_font'
											name='letterno' type='text' class='textbox' id='letterno'    /></td>
								</tr>
								
								<tr><td>&nbsp </td>
									<td  class='label'>تاریخ نامه تاییدیه:</td> 
									<td ><input   value='$letterdate'	class='f52_font'
											name='letterdate' type='text' class='textbox' id='letterdate'    /></td>
								</tr>
								
								<tr><td>&nbsp </td>
									<td  class='label'>$lbl</td>
									<td ><input  value='$sandoghcode'  class='f52_font'
											name='sandoghcode' type='text' class='textbox' id='sandoghcode'    /></td>
								</tr>
                           ";
					if ($login_RolesID=='16'  || $login_RolesID=='7' || $login_RolesID=='1')	
					$globalprint.= " 						
                        <tr>
							<td  colspan='2' id='creditsourceIDlbl'  class='label'>$titr1 
							</td><td colspan='5'>
										<input name='selfcashhelpval' type='text' class='textbox' id='selfcashhelpval' value='$selfcashhelpval'  
										onKeyUp=\"convert('selfcashhelpval')\" size='12' />
									تاریخ:	
										<input placeholder='انتخاب تاریخ'  name='selfcashhelpdate' type='text' class='textbox' 
										id='selfcashhelpdate' value='$selfcashhelpdate' size='10'/></td>
                        </tr>
                         
                         
                        <tr>
							<td   colspan='2'>$titr2 1
							</td><td colspan='5'>
									<input name='selfnotcashhelpval1' type='text' class='textbox' id='selfnotcashhelpval1' value='$selfnotcashhelpval1' onKeyUp=\"convert('selfnotcashhelpval1')\" size='12' />
									تاریخ:
									<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate1' type='text' class='textbox' id='selfnotcashhelpdate1' value='$selfnotcashhelpdate1' size='10'/>
							</td>                
                        </tr>
						
                        <tr>
							<td    colspan='2'>$titr2 2
							</td><td colspan='5'>
									<input name='selfnotcashhelpval2' type='text' class='textbox' id='selfnotcashhelpval2' value='$selfnotcashhelpval2' onKeyUp=\"convert('selfnotcashhelpval2')\" size='12' />
									تاریخ:
									<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate2' type='text' class='textbox' id='selfnotcashhelpdate2' value='$selfnotcashhelpdate2' size='10'/>
							</td> 		
                        </tr>
                        <tr>
							<td    colspan='2'>$titr2 3
							</td><td colspan='5'>
									<input name='selfnotcashhelpval3' type='text' class='textbox' id='selfnotcashhelpval3' value='$selfnotcashhelpval3' onKeyUp=\"convert('selfnotcashhelpval3')\" size='12' />
									تاریخ:
									<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate3' type='text' class='textbox' id='selfnotcashhelpdate3' value='$selfnotcashhelpdate3' size='10'/>
							</td> 
                        </tr>
                        
                        <tr>
							<td   class='label'>شرح :</td>
							<td  colspan='9'><textarea id='selfcashhelpdescription' name='selfcashhelpdescription' rows='2'  cols='50' >$selfcashhelpdescription</textarea></td>
                         
                        </tr>     
								$strbring 
                            
								$strsokuk
                            
                             ";
                  $globalprint.= "  <tr><td>&nbsp </td>  <td  colspan='15'>---------------------------------------------------------------------------------------------------------------------------------------------------------- </td> </tr>
		 </table>		  <table>
				  
				  ";    
                             
                }  
                   
                   
                   if (substr($login_CityId,0,2)=='19')
                   {
                        if ($login_RolesID==10 && $operatorcoid>0 )//نظارت بر اجرا
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='1' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
                            $result = mysql_query($query);
                            $row = mysql_fetch_assoc($result);
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='1' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                         
                         if ($login_RolesID==9)//قرارداد مطالعات
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='4' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
                            $result = mysql_query($query);
                            $row = mysql_fetch_assoc($result);
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='4' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                         
                         if ($login_RolesID==11)//قراردادبازبینی
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID=
                                    case '$login_userid'
                                    when 88 then 111
                                    when 662 then 69
                                    when 36 then 57
                                    else 0 end
                                     
                                     
                                     
                                     
                                     
                                     ";
                                     //print $query;exit;
                            $result = mysql_query($query);
                            $row = mysql_fetch_assoc($result);
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID=case '$login_userid'
                                    when 88 then 111
                                    when 662 then 69
                                    when 36 then 57
                                    else 0 end ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                   }
                   
                   
                   
                      if (strlen($errmosavab)>0 && $type==3)
                      echo $errmosavab;
                      else
                      $globalprint.= "<tr>".select_option('ApplicantstatesID','وضعیت&nbspجدید:',',',$IDapplicantstatesID,0,'','','2','rtl',0,'',0,'','215')."
                      
                      <td  > شرح:
                      <textarea id='Description' name='Description' rows='3' cols='120'></textarea></td>
                      
                      <td><input   name='submit' type='submit' class='button' id='submit' value='ارسال/ثبت' /></td>
                      </tr>
                               
                          "; 
                           
        }
        $inspectortile="";
        if ($DesignerCoID>0)
        {
            if($DesignerCoIDnazer>0)
            $inspectortile=getnamefromclerkid($DesignerCoIDnazer);
            else
            {
                $query="SELECT max(ClerkIDinspector) ClerkIDinspector  FROM tax_tbcity7digit where substring(Id,1,4)='$cityid14'";
                
                //print $query;
                $result = mysql_query($query);
                $row = mysql_fetch_assoc($result);
                if($row['ClerkIDinspector']>0)
                $inspectortile=getnamefromclerkid($row['ClerkIDinspector']);
            }
        }
		
		
		
         if ($type!=1)
		 		$globalprint.= " 
        
        <p id='psh_manager' ></p>
        <table width='$Rwidth%' id='ish_manager'>
                 <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
					onclick=\"showhidediv('sh_manager');\">".str_replace(' ', '&nbsp;', "تاییدیه کارشناسان").
					"
                    </td></tr>
					</table>
		    
		<table id='sh_manager' style='display:none;' width='$Pwidth%'> 
			<tr><td colspan='7'><input class='no-print' id='chksh_manager' type='checkbox' onChange=\"setpagereak('psh_manager')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td></tr>
				
									<tr>
									<td  ><div style=\"width:  $Rmargin;\"></td>
									<td  colspan='1' class='f50_font' style='width: 35%;'>نام و نام خانوادگی کارشناس فنی&nbsp <br>  امضاء&nbsp</td> 
									<td  colspan='1' class='f50_font' style='width: 30%;'>$CoTitleinPrint&nbsp<br>مهر و امضاء&nbsp</td> 
									<td  colspan='1' class='f50_font' style='width: 35%;'>نام و نام خانوادگی $nazer &nbsp<br>مهر و امضاء&nbsp</td> 
								 <td><div style=\"width:  $Lmargin;\"></div></td>
				
									</tr>
										
                                        
                                
                                <tr >  
                                <td  ></td>
									
                                    <td colspan='1' class='f56_font'><div >&nbsp $designerTitle</div> </td> 
                                    <td colspan='1' class='f56_font'><div >شرکت&nbsp$CoTitle</div> </td> 
                                    <td colspan='1'  class='f56_font'><div >$inspectortile</div></td> 
                                <td >&nbsp;</td>
                                    
                                </tr>  
                                
                                "; 
                         
         $globalprint.= "</table>";
         
                if (!($PipeProducer>0) && ($OperatorCoID>0)) echo "پروژه فاقد تولید کننده لوله می باشد <br>";
                
               
                
                
                
                $globalprint.=  "<table  align=\"left\">";
                
                foreach($strappfoundationtitlegroup as $key=> $value)
                {
                    $linearray = explode('_',$key);
                    $ftitle=$linearray[0];
                    $appfoundationID=$linearray[1];
                    
                    if ($appfoundationID>0)
                    {
                        $sqlselect="
                        SELECT fehrestsmaster.fehrestsmasterID _value,fehrestsmaster.Title _key FROM manuallistpriceall
                        left outer join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID
                        left outer join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrests.fehrestsmasterID
                        where appfoundationID='$appfoundationID'
                        union all
                        SELECT fehrestsmaster.fehrestsmasterID _value,fehrestsmaster.Title _key FROM manuallistprice
                        left outer join fehrestsfasls on fehrestsfasls.fehrestsfaslsID=manuallistprice.fehrestsfaslsID
                        left outer join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrestsfasls.fehrestsmasterID
                        where appfoundationID='$appfoundationID'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg2id = get_key_value_from_query_into_array($sqlselect);       
                        //print $sqlselect;
                        $query="SELECT *  FROM appfoundationmoderate where appfoundationID='$appfoundationID'";
                        $result = mysql_query($query);
                        $row = mysql_fetch_assoc($result);
                                 
                    }
                    else continue;
					
	  if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost))		
		$globalprint.= " 

        <p id='psh_sazetable$appfoundationID' ></p>
        <table width='$Rwidth%' id='ish_sazetable$appfoundationID'>
           <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
			         onclick=\"showhidediv('sh_sazetable$appfoundationID');\">جدول سازه $ftitle
					 </td></tr>
					 </table>
                    
            ";
			
    $globalprint.= "
    <table id='sh_sazetable$appfoundationID' style='display:none;' width='$Pwidth%' > 
                  <tr>  
				  <td colspan='5'><input class='no-print' id='chksh_sazetable$appfoundationID' type='checkbox' onChange=\"setpagereak('psh_sazetable$appfoundationID')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
				   
			
				  </tr>
				 
						  
					<tr>
                		<td colspan='1'><div style=\"width:  $Rmargin;\"></td>
						<td colspan='4' class='f6_font'>       فهرست بها $fb</td>
						<td colspan='6' class='f6_font'>   سازه: $ftitle</td>
						<td colspan='1' class='f3_font'>$printdate</td>
							<td colspan='1'><div style=\"width:  $Lmargin;\"></td>
					
                    </tr>
                    <tr>
				        	<th align='center' class='f21_font' style=\" background-color:#ffffff;border:0px solid black;border-color:#0000ff #0000ff \"></th>
							<th align='center' class='f21_font' >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >فهرست بها</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th align='center' class='f21_font' style='width: $P1title%;'>شرح</th>
                            <th align='center' class='f21_font'>واحد</th>
                            <th align='center' class='f21_font'>تعداد</th>
							<th align='center' class='f21_font'>مقدار</th>
                            <th align='center' class='f21_font'>تعداد سازه</th>
                            <th align='center' class='f21_font'>بها(ریال)</th>
                            <th align='center' class='f21_font'>بهای کل(ریال)</th>
                    </tr>";
                    $sumsaze=0;
                    foreach($strappfoundationtitlegroup[$key] as $key1=> $value1)   
                    {
                        $globalprint.=  $value1;
                        $sumsaze+=$sumappfoundationtitlegroup[$key][$key1];
                    }
                    $strtmoderate="<td colspan='1'  class='f24_font'>".number_format($sumsaze)."</td>";
                    if ($row['Price']>0)
                        $strtmoderate="<td colspan='1'  class='f24_font'><font style='text-decoration: line-through;'>".number_format($sumsaze)."</font><br><font color='green'>".number_format($row['Price'])."</font></td>";
                    $globalprint.=  "<tr><td></td>
                       <td colspan='3' class='f11_fontt' onclick=\"showdiv('$appfoundationID');\"></font><br><font color='green'>تعدیل</font></td>
                      <td colspan='7' class='f11_fonttm' onclick=\"showdiv('$appfoundationID');\">مجموع (ریال)</td>
                      
                      $strtmoderate
                                    
                      </tr>
                      
                      <tr>  
                        <td colspan='1' class='f20_font' ></td>
                        <td colspan='9' >   
                            <table id='".$appfoundationID."_content' style='display:none;' class='f13_font'>
                                       <tr>
                                        <td colspan='12'>مبلغ تعدیل شده: 
                                        <input style = \"background-color:#f1f5b8;border:0px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';\"
                                        onKeyUp=\"convert('val1$appfoundationID');\" name='val1$appfoundationID' type='text' class='textbox' id='val1$appfoundationID' value='".number_format($row['Price'])."' size=15/>
                                        ریال
										&nbsp
										شرح: 
                                        <input style = \"background-color:#f1f5b8;border:0px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';\"
                                        name='val2$appfoundationID' type='text' class='textbox' id='val2$appfoundationID' value='$row[Description]' size=30/>
                                        ".select_option("val3$appfoundationID",'فهرست بها:',',',$allg2id,'0','','',1,'','','',$row['fehrestsmasterID'])."
                                        <input type='hidden' id='oldval1$appfoundationID' name='oldval1$appfoundationID' value='".number_format($row['Price'])."'  />
                                        <input type='hidden' id='oldval2$appfoundationID' name='oldval2$appfoundationID' value='$row[Description]'  />
                                        <input type='hidden' id='oldval3$appfoundationID' name='oldval3$appfoundationID' value='$row[fehrestsmasterID]'  />
                                        </td>
                                       
                                        </tr>
                                        
                            </table>
                        </td>  
                    </tr> 
                      
                      
                      <tr><td>&nbsp;</td></tr></table>  
                ";
                }
                
                
                
                
                
                
                
                
                
                
                
                $globalprint.=  "<table  align=\"left\">";
                foreach($strappfoundationtitle as $keym=> $value)
                {
                    $linearray = explode('_',$keym);
                    $ftitle=$linearray[0];
                    $appfoundationID=$linearray[1];
                    
                    $totaltot=0;
                    
                    
                    if ($appfoundationID>0)
                    {
                        $sqlselect="
                        SELECT fehrestsmaster.fehrestsmasterID _value,fehrestsmaster.Title _key FROM manuallistpriceall
                        left outer join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID
                        left outer join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrests.fehrestsmasterID
                        where appfoundationID='$appfoundationID'
                        union all
                        SELECT fehrestsmaster.fehrestsmasterID _value,fehrestsmaster.Title _key FROM manuallistprice
                        left outer join fehrestsfasls on fehrestsfasls.fehrestsfaslsID=manuallistprice.fehrestsfaslsID
                        left outer join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrestsfasls.fehrestsmasterID
                        where appfoundationID='$appfoundationID'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg2id = get_key_value_from_query_into_array($sqlselect);       
                        //print $sqlselect;
                        $query="SELECT *  FROM appfoundationmoderate where appfoundationID='$appfoundationID'";
                        $result = mysql_query($query);
                        $row = mysql_fetch_assoc($result);
                                 
                    }
                    else continue;
		
	 
 if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost))		
		$globalprint.= " 

        <p id='psh_rizmetretable$appfoundationID' ></p>
        <table width='$Rwidth%' id='ish_rizmetretable$appfoundationID'>
           <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
			         onclick=\"showhidediv('sh_rizmetretable$appfoundationID');\">ریزمتره سازه $ftitle
					 </td></tr>
					 </table>
                    
            ";
			
    $globalprint.= "
    <table id='sh_rizmetretable$appfoundationID' style='display:none;' width='$Pwidth%' > 
                  <tr>  
				  <td colspan='5'><input class='no-print' id='chksh_rizmetretable$appfoundationID' type='checkbox' onChange=\"setpagereak('psh_rizmetretable$appfoundationID')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
				   
			
				  </tr>
				 
						  
					<tr>
                		<td colspan='1'><div style=\"width:  $Rmargin;\"></td>
						<td colspan='4' class='f6_font'>       فهرست بها $fb</td>
						<td colspan='9' class='f6_font'>   سازه: $ftitle</td>
						<td colspan='1' class='f3_font'>$printdate</td>
							<td colspan='1'><div style=\"width:  $Lmargin;\"></td>
					
                    </tr>
                    <tr>
				        	<th align='center' class='f21_font' style=\" background-color:#ffffff;border:0px solid black;border-color:#0000ff #0000ff \"></th>
							<th align='center' class='f21_font'  >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >فهرست بها</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th align='center' class='f21_font' style='width: $P1title%;'>شرح</th>
                            <th align='center' class='f21_font'>واحد</th>
                            <th align='center' class='f21_font' style=\"font-size:10.0pt; \">تعداد</th>
                            <th align='center' class='f21_font' style=\"font-size:10.0pt; \">طول</th>
                            <th align='center' class='f21_font' style=\"font-size:10.0pt; \">عرض</th>
                            <th align='center' class='f21_font' style=\"font-size:10.0pt; \">ضخامت/ وزن</th>
							<th align='center' class='f21_font' style=\"font-size:10.0pt; \">ضریب</th>
                            <th align='center' class='f21_font'>تعداد سازه</th>
                            <th align='center' class='f21_font'>بها(ریال)</th>
                            <th align='center' class='f21_font'>بهای کل(ریال)</th>
                    </tr>";
                    $sumsaze=0;
                    $cc1cnt=0;
                    
                    $ToolsGroupsCodeold="";
                    $ftypeold="";
                    $Codeold="";
                    $Titleold="";
                    $unitold="";
                    $Number2old="";
                    $Number4old="";
                    $Number5old="";
                    $Number3old="";
                    $Number6old="";
                    $FNumberold="";
                    $Priceold="";
                    $SumPriceold="";
                    $snumber=0;
                    $totaltot=0;
                    foreach ($ID1 as $key => $value)
                    {
                        $linearray2 = explode('_',$value);
                        $IDval1=$linearray2[0];
                        if ($appfoundationID!=$IDval1)
                            continue;
                        
                        $ToolsGroupsCode="";;
                        $Code="";
                        $ftype="";
                        $Number2="";
                        $Number3="";
                        $Number4="";
                        $Number5="";
                        $Number6="";
						$Title="";
                        $unit="";
                        $FNumber="";
                        $Price="";
                        $SumPrice="";
                        $Description="";
                        $linearray = explode('_',$key);
                        $ToolsGroupsCode=$linearray[0];
                        $Code=$linearray[1];
                        $ftype=$linearray[2];
                        $Title=$linearray[3];
                        $unit=$linearray[4];
						
                        $Number2=$linearray[5];
                        $Number3=$linearray[6];
                        $Number4=$linearray[7];
                        $Number5=$linearray[8];
                        $Number6=$linearray[9];
						
                        $FNumber=$linearray[10];
                        $Price=$linearray[11];
                        $SumPrice=$linearray[12];
                        $Description=$linearray[13];
						 $fehrestsmasterID=$linearray[14];
						$fehrestsfaslsID=$linearray[15];
						$aut=$linearray[17];
						$Number=$linearray[18];
						if ($Number<=0) $Number=1;
                        $fehrestsmasterID=str_replace("<br>","",$fehrestsmasterID);
                        $fehrestsfaslsID=str_replace("<br>","",$fehrestsfaslsID);
                        
                        //print "sa".$ApplicantMasterID."_".$fehrestsmasterID."_".$aut."_".$fehrestsfaslsID."_".$appfoundationID."_"."sa<br>";
                        if ($Codeold>0 && $Codeold!=$Code)
                        {
                            $target="";
                            if ($type==3 || $login_RolesID==1)
                            $target="<td><a target='_blank' href='manualcostlist_pluscostlist_detail2.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_".$fehrestsfaslsID."_".$aut."_".$appfoundationID."_".rand(10000,99999)."'>
                            ریز</a></td>";
                            
                            $cc1cnt++;
                            $snumber=round($snumber,3);
							
                            $globalprint.=  "
                        <tr><td></td>
                                <td class='f28_font' style=\" border-right: 2px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$cc1cnt</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$ToolsGroupsCodeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$ftypeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$Codeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; text-align: justify;padding-bottom: 10px;width: 100%; \" >$Titleold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$unitold</td>
								<td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; \" colspan=5 >$snumber</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$FNumberold</td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 1px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">".number_format($Priceold)."</td>
                                <td class='f11_font' style=\" border-right: 0px solid #0000ff;border-left: 2px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">".number_format($snumber*$Priceold*$FNumberold)."</td>
    							$target</tr>
                        ";
						
					     
			 
						
                        $totaltot+=round($snumber*$Priceold*$FNumberold);
                        $snumber=0;
                        }
					    
                        $ToolsGroupsCodeold=$ToolsGroupsCode;
                        $ftypeold=$ftype;
                        $Codeold=$Code;
                        $Titleold=$Title;
                        $unitold=$unit;
                        $Number2old=$Number2;
                        $Number4old=$Number4;
                        $Number5old=$Number5;
                        $Number3old=$Number3;
                        $Number6old=$Number6;
                        $FNumberold=$FNumber;
                        $Priceold=$Price;
                        $SumPriceold=$SumPrice;
                        
                           if ($Number2<=0) $Number2=1;
                        if ($Number3<=0) $Number3=1;
                        if ($Number4<=0) $Number4=1;
                        if ($Number5<=0) $Number5=1;
                        if ($Number6<=0) $Number6=1;
                        if ($Number2==1 && $Number3==1 && $Number4==1 && $Number5==1 && $Number6==1  )
                        {
                            $snumber+=$Number;
                            $Number2=$Number;
                            
                        }
                        else
                        $snumber+=($Number2*$Number4*$Number5*$Number3*$Number6);
                        
                        $globalprint.=  "
                        <tr><td></td>
                                <td class='f11_font' style=\" border-right: 2px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                                <td class='f28_font' style=' text-align:right; padding-right: 25px; width: 100%; font-size:11.0pt;'>$Description</td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                            <td class='f28_font' >$Number2</td>
                            <td class='f28_font' >$Number5</td>
                            <td class='f28_font' >$Number4</td>
                            <td class='f28_font' >$Number3</td>
                            <td class='f28_font' >$Number6</td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
								<td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 2px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 0px solid #0000ff; \"></td>
                    			<td></td>
                        </tr>";
                        
                        
                        
                    }
                      $cc1cnt++;
                      
                            $snumber=round($snumber,2);
                            $globalprint.=  "
                        <tr><td></td>
                                <td class='f28_font' style=\" border-right: 2px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$cc1cnt</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$ToolsGroupsCodeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$ftypeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$Codeold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; text-align: justify;padding-bottom: 10px; width: 100%;\">$Titleold</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$unitold</td>
                            <td class='f28_font'     style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 1px solid #F0F8FF;border-bottom: 2px solid #0000ff; \" colspan=5 >$snumber</td>
                                <td class='f28_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">$FNumberold</td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 0px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">".number_format($Priceold)."</td>
                                <td class='f11_font' style=\" border-right: 1px solid #0000ff;border-left: 2px solid #0000ff;border-top: 0px solid #F0F8FF;border-bottom: 2px solid #0000ff; \">".number_format($snumber*$Priceold*$FNumberold)."</td>
    							<td></td>
                        </tr>";
                        
                    //$totaltot+=round($snumber*$Priceold*$FNumberold);
                    $totaltot=0;
                    foreach($strappfoundationtitle[$keym] as $key1=> $value1)   
                    {
                        $totaltot+=$sumappfoundationtitle[$keym][$key1];
                    }
                    
                    $globalprint.=  "<tr><td></td>
                       <td colspan='3' class='f11_fontt' onclick=\"showdiv('$appfoundationID');\"></font><br><font color='green'></font></td>
                      <td colspan='10' class='f11_fonttm' onclick=\"showdiv('$appfoundationID');\">مجموع (ریال)</td>
                      
                      <td colspan='1'  class='f24_font'>".number_format($totaltot)."</td>
                                    
                      </tr>
                      
                       
                      
                      
                      <tr><td>&nbsp;</td></tr></table>  
                ";
                }
                ////////////////////////////////////ریز فهرست بها
                $gadget3operationalstr=retgadget3operational($ApplicantMasterID);      
                //print $gadget3operationalstr;exit;
                //print "sa2";
                //----------
                $sql = "select replace(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(`gadget2`.`Title`,_utf8' '),ifnull(`materialtype`.`Title`,_utf8'')),_utf8' '),ifnull(`gadget3kala`.`spec1`,_utf8'')),_utf8' '),ifnull(`gadget3kala`.`Title`,_utf8'')),concat(_utf8' ',concat(concat(concat(concat(concat(concat(concat(ifnull(`gadget3kala`.`size11`,_utf8''),_utf8''),ifnull(`operator`.`Title`,_utf8'')),_utf8''),ifnull(`gadget3kala`.`size12`,_utf8'')),_utf8''),ifnull(`gadget3kala`.`size13`,_utf8' ')),concat(ifnull(`sizeunits`.`Title`,_utf8''),_utf8' ')))),ifnull(`gadget3kala`.`zavietoolsorattabaghe`,_utf8'')),_utf8''),ifnull(`sizeunitszavietoolsorattabaghe`.`Title`,_utf8'')),_utf8''),ifnull(`spec2`.`Title`,_utf8'')),_utf8' '),ifnull(`gadget3kala`.`fesharzekhamathajm`,_utf8'')),_utf8''),ifnull(`sizeunitsfesharzekhamathajm`.`Title`,_utf8'')),_utf8' '),concat(concat(concat(concat(concat(ifnull(`spec3`.`Title`,_utf8''),_utf8''),ifnull(`gadget3kala`.`spec3size`,_utf8'')),_utf8''),ifnull(`spec3sizeunits`.`Title`,_utf8'')),_utf8' ')),_utf8''),_utf8' '),_utf8' '),_utf8'  ',_utf8' ') AS `gadget3Title`,`gadget3costs`.`Title` AS `Title`,`gadget3costs`.`Code` AS `Code`,`invoicedetail`.`Number` AS `Number`,`gadget3operational`.`CostCoef` AS `CostCoef`,`units`.`Title` AS `unit`,`costpricelistdetail`.`Price` AS `Price`,((`costpricelistdetail`.`Price` * `invoicedetail`.`Number`) * `gadget3operational`.`CostCoef`) AS `Total`,`invoicemaster`.`ApplicantMasterID` AS `ApplicantMasterID`,`invoicemaster`.`Title` AS `invoicemasterTitle` from ((((((((((((((((((((
                `invoicedetail` 
                join `toolsmarks` on((`toolsmarks`.`ToolsMarksID` = `invoicedetail`.`ToolsMarksID`))) 
                join `invoicemaster` on(((`invoicemaster`.`InvoiceMasterID` = `invoicedetail`.`InvoiceMasterID`) and (ifnull(`invoicemaster`.`costnotinrep`,0) = 0)))) 
                join `gadget3` `gadget3kala` on((`gadget3kala`.`Gadget3ID` = `toolsmarks`.`gadget3ID`))) 
                left join $gadget3operationalstr gadget3operational 
                                        on ((gadget3operational.gadget3ID = gadget3kala.Gadget3ID and gadget3operational.invoicemasterid=invoicemaster.invoicemasterid)))
                
                left join `gadget3` `gadget3costs` on((`gadget3costs`.`Gadget3ID` = `gadget3operational`.`Gadget3IDOperational`))) 
                left join `gadget2` on((`gadget2`.`Gadget2ID` = `gadget3kala`.`Gadget2ID`))) 
                left join `gadget1` on(((`gadget1`.`Gadget1ID` = `gadget2`.`Gadget1ID`) and (`gadget1`.`IsCost` = 1)))) 
                left join `units` on((`units`.`UnitsID` = `gadget3costs`.`unitsID`))) 
                left join `costsgroups` on((`costsgroups`.`Code` = cast(substr(cast(`gadget3costs`.`Code` as char(100) charset utf8),1,1) as unsigned)))) 
                left join `applicantmaster` on((`applicantmaster`.`ApplicantMasterID` = `invoicemaster`.`ApplicantMasterID`))) 
                left join `costpricelistmaster` on((`costpricelistmaster`.`CostPriceListMasterID` = `applicantmaster`.`CostPriceListMasterID`))) 
                left join `costpricelistdetail` on(((`costpricelistdetail`.`CostPriceListMasterID` = `costpricelistmaster`.`CostPriceListMasterID`) and (`costpricelistdetail`.`Gadget3ID` = `gadget3costs`.`Gadget3ID`)))) 
                left join `sizeunits` `sizeunitszavietoolsorattabaghe` on((`sizeunitszavietoolsorattabaghe`.`SizeUnitsID` = `gadget3kala`.`zavietoolsorattabagheUnitsID`))) 
                left join `sizeunits` `sizeunitsfesharzekhamathajm` on((`sizeunitsfesharzekhamathajm`.`SizeUnitsID` = `gadget3kala`.`fesharzekhamathajmUnitsID`))) 
                left join `sizeunits` on((`sizeunits`.`SizeUnitsID` = `gadget3kala`.`sizeunitsID`))) 
                left join `operator` on((`operator`.`operatorID` = `gadget3kala`.`operatorid`))) 
                left join `spec2` on((`spec2`.`spec2ID` = `gadget3kala`.`spec2id`))) 
                left join `spec3` on((`spec3`.`spec3ID` = `gadget3kala`.`spec3id`))) 
                left join `sizeunits` `spec3sizeunits` on((`spec3sizeunits`.`SizeUnitsID` = `gadget3kala`.`spec3sizeunitsid`))) 
                left join `materialtype` on((`materialtype`.`MaterialTypeID` = `gadget3kala`.`MaterialTypeID`))) 
                where gadget3costs.Code > 0 and CostCoef>0 and invoicemaster.ApplicantMasterID='$ApplicantMasterID' and ifnull(invoicedetail.deactive,0)=0
                order by gadget3costs.Code
                ;";
                //print $sql;
                $result = mysql_query($sql);


                
                $globalprint.=  "<table  align=\"left\">";
                    
                
	 
 if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost))		
		$globalprint.= " 

        <p id='psh_rizfehrestbbahaab' ></p>
        <table width='$Rwidth%' id='ish_rizfehrestbbahaab'>
           <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
			         onclick=\"showhidediv('sh_rizfehrestbbahaab');\">ریز فهرست بهای آبیاری تحت فشار
					 </td></tr>
					 </table>
                    
            ";
			
    $globalprint.= "
    <table id='sh_rizfehrestbbahaab' style='display:none;' width='$Pwidth%' > 
                  <tr>  
                  <td ></td>
				  <td colspan='8'><input class='no-print' id='chksh_rizfehrestbbahaab' type='checkbox' 
                  onChange=\"setpagereak('psh_rizfehrestbbahaab')\"/><label class='no-print'><font size='1'>چاپ در ابتدای صفحه</font></label></td>
                  <td ></td>
				   
			     
				  </tr>
				 
                 <tr>
                
                        
                        		<td colspan='1'><div style=\"width:  $Rmargin;\"></td>
						<td colspan='5' class='f6_font'> ریز فهرست بهای آبیاری تحت فشار</td>
						<td colspan='2' class='f6_font'></td>
						<td colspan='1' class='f3_font'>$printdate</td>
							<td colspan='1'><div style=\"width:  $Lmargin;\"></td>
                            
					
                    </tr>
                    
                     <tr>
                        	<th align='center' class='f21_font' style=\" background-color:#ffffff;border:0px solid black;border-color:#0000ff #0000ff \"></th>
							<th align='center' class='f21_font'  >کد</th>
                            <th align='center' class='f21_font'  >ردیف</th>
                            <th align='center' class='f21_font' style='width: $P1title%;'>عنوان</th>
                            <th align='center' class='f21_font' >تعداد</th>
                            <th align='center' class='f21_font' >ضریب تبدیل</th>
                            <th align='center' class='f21_font' >واحد</th>
                            <th align='center' class='f21_font' >فی</th>
                            <th align='center' class='f21_font' >مبلغ</th>
							<td ></td>
                        </tr>";
                      
                      
                        $rown=0;
                        $rowd=0;
                        $totaltotr=0;
                        $Titleold='';
                        $sum1=0;
                    while ($row = mysql_fetch_assoc($result))
                    {
                        if ($Titleold<>$row['Title'])
                        {
                            $rown++;
                            if ($Titleold<>'')
                            {
                              $globalprint.= "   <tr>
                            <td></td>
                            <td class='f11_font' colspan='7'></td>
                            <td class='f11_font' >".number_format($sum1)."</td>
                            <td></td>
                            </tr> ";  
                            }
                           $globalprint.= "   <tr>
                            <td></td>
                            <td class='f11_font'>$row[Code]</td>
                            <td class='f11_font'></td>
                            <td class='f11_font'>$row[Title]</td>
                            <td class='f11_font' colspan='5'></td>
                            <td></td>
                            </tr> ";
                            $rowd=0;
                            $sum1=0;
                           $Titleold = $row['Title']; 
                        }
                       
                        $Number = $row['Number'];
                        $unit = $row['unit'];
                        $Price = $row['Price'];
                        $Total = $row['Total'];
                        $invoicemasterTitle = $row['invoicemasterTitle'];
                        $gadget3Title = $row['gadget3Title'];
                        $rowd++;
                        $totaltotr+=round($Total);
                        $sum1+=round($Total);
                    $globalprint.= "   <tr>
                    <td></td>
                            <td class='f13_font'></td>
                            <td class='f13_font'>$rowd</td>
                            <td class='f13_font'>$gadget3Title ($invoicemasterTitle)</td>
                            <td class='f13_font'>$Number</td>
                            <td class='f13_font'> $row[CostCoef]</td>
                            <td class='f13_font'>$unit</td>
                            <td class='f13_font'>".number_format($Price)."</td>
                            <td class='f13_font'>".number_format(round($Total))."</td>
                            <td></td>
                            </tr> ";
                                  
                        
                        
                    }
                     
                              $globalprint.= "   <tr>
                            <td></td>
                            <td class='f11_font' colspan='7'></td>
                            <td class='f11_font' >".number_format($sum1)."</td>
                            <td></td>
                            </tr> ";
                    
                    $globalprint.=  "
                      <tr><td>&nbsp;</td></tr></table>  
                ";
                
                
                //////////////////////////////////
                
                
                
                
                
                
                if ($othercosts5>0)
                {
                    $globalprint.= "<p id='psh_bring' ></p>
                    <table width='$Rwidth%' id='ish_bring'>
                    <tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;
					border:1px solid black;border-color:#D1D1D1;
					\" ><td colspan=11 class='no-print'
					onclick=\"showhidediv('sh_bring');\">".str_replace(' ', '&nbsp;', "آورده متقاضی").
					"
                    </td></tr>
					</table>
    		
                    <table id='sh_bring' style='display:none;' > 
                    
                    
                    <tr><td colspan='1'></td>
				     <td colspan='3' class='f14_font'> آورده متقاضی</td>
                     <td colspan='1'></td>
					</tr>
                    
                    
                    <tr>
                        
                		<td colspan='1'><div style=\"width:  $Rmargin;\" /></td>
                    
                       	<th align='center' class='f21_font' >ردیف</th>
                        <th align='center' class='f21_font' style='width: $P1title%;'>عنوان</th>
                        <th align='center' class='f22_font'  >بها(ریال)</th>
							<td colspan='1'><div style=\"width:  $Lmargin;\" /></td>
                    </tr>
                    
                    ";
                    
                    $sql = "SELECT * FROM appfarmerbring where ApplicantMasterID = '".$ApplicantMasterID."' order by rown" ;
                    $resultwhile = mysql_query($sql);
                    $Rowcnt=0;
                    while ($row = mysql_fetch_assoc($resultwhile))
                    {
                        $Title = $row['Title'];
                        $price = number_format($row['price']);
                        $Rowcnt++;
                        $globalprint.= " <tr>
                        <th align='center' class='f14_font' style=\" background-color:#ffffff;border:0px solid black;border-color:#0000ff #0000ff \"></th>
                       	<th align='center' class='f14_font' >$Rowcnt</th>
                        <th align='center' class='f14_font' ' >$Title</th>
                        <th align='center' class='f14_font' ;'>$price</th>
                        </tr>"; 
                    }
                        
                         
         $globalprint.= "</table>";
          
                }
                
                
                $globalprint.="</table><table class='no-print' >
                <tr ><td class='no-print'><div  style='width:20px;height:20px;border:0px;background-color:#fecde3;'></div></td><td style='width:100%;'>فهرست بهای دستی</td></tr>
                <tr><td class='no-print'><div style='width:20px;height:20px;border:0px;background-color:#ffffcc;'></div></td><td>آیتم های قابل تغییر توسط ناظر/مشاور</td></tr>
                <tr><td class='no-print'><div style='width:20px;height:20px;border:0px;background-color:#ffff00;'></div></td><td>آیتم های ثبت شده دستی</td></tr>
                <tr><td class='no-print'><div  style='width:20px;height:20px;border:0px;background-color:#ff0000;'></div></td><td>حذف هزینه اجرایی</td></tr>
                <tr><td class='no-print' colspan=2>(+) پیش فاکتور دارای ارزش افزوده</td></tr>
                <tr><td class='no-print' colspan=2>(.) حذف ارزش افزوده پیش فاکتور</td></tr>
                <tr>
                <input name='highprice' type='hidden' class='textbox' id='highprice'  value='$highprice'   /></tr>
                </table>   
                
                
                   </div>
                
                ";
                
                echo $globalprint;
?>

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

