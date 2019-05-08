<?php 

/*

//appinvestigation/invoicemasterfree_list2.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/aaapplicantfree.php
 -
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

$permitrolsid = array("1","16", "19","7","13","14");
if (! in_array($login_RolesID, $permitrolsid)) exit(0);
if ($_POST)
{
    
    $arraydeliverychecks=array();
    $ApplicantMasterID=$_POST['ApplicantMasterID'];
    
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            */
            
    $sql1="SELECT concat(ProducersID,'_',freestateID) _key FROM applicantfreedetail
    where ifnull(applicantfreedetail.CheckNo,'')<>'' and ApplicantMasterID='$ApplicantMasterID'";
    //print $sql1;    
    
							try 
							  {		
								$result1 = mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $i=0;
    while($row1 = mysql_fetch_assoc($result1))
    {
        $arraydeliverychecks[$i]=$row1['_key']; 
        $i++;   
    }

    $arrayprimaryinsert=array();
    $ApplicantMasterID=$_POST['ApplicantMasterID'];
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            */
    $sql1="SELECT concat(ProducersID,'_',freestateID) _key FROM applicantfreedetail
    where ifnull(applicantfreedetail.CheckNo,'')='' and ApplicantMasterID='$ApplicantMasterID'";
    //print $sql1;    
   
								try 
							  {		
								 $result1 = mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $i=0;
    while($row1 = mysql_fetch_assoc($result1))
    {
        $arrayprimaryinsert[$i]=$row1['_key']; 
        $i++;   
    }
    $chkstr="";
    for($i=1;$i<=$_POST['cnt'];$i++)
    {
        if ($i>1)
        {
            if ($_POST['chk'.$i])
                $chkstr.='_1';
            else 
                $chkstr.='_0';   
        }
        else
        {
            if ($_POST['chk'.$i])
                $chkstr='1';
            else 
                $chkstr='0';  
            
        }   
        
        $Price= str_replace(',', '', $_POST['val1'.$i]);
        if (!in_array($_POST['ProducersID'.$i]."_141",$arraydeliverychecks)&& strlen($Price)>0) 
        {
            if (!in_array($_POST['ProducersID'.$i]."_141",$arrayprimaryinsert))//update
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            */
                $query="update applicantfreedetail set Price='$Price' 
                ,SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='".$login_userid."' 
                WHERE ApplicantMasterID='$ApplicantMasterID' and ProducersID='".$_POST['ProducersID'.$i]."' and freestateID='141';";
             
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
            else//insert
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="insert into applicantfreedetail (Price,SaveTime,SaveDate,ClerkID,ApplicantMasterID,ProducersID,freestateID)
                values ('$Price','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$ApplicantMasterID','".$_POST['ProducersID'.$i]."',141);";
               
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
        }
        $Price= str_replace(',', '', $_POST['val2'.$i]);
        if (!in_array($_POST['ProducersID'.$i]."_142",$arraydeliverychecks)&& strlen($Price)>0) 
        {
            if (!in_array($_POST['ProducersID'.$i]."_142",$arrayprimaryinsert))//update
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="update applicantfreedetail set Price='$Price' 
                ,SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='".$login_userid."' 
                WHERE ApplicantMasterID='$ApplicantMasterID' and ProducersID='".$_POST['ProducersID'.$i]."' and freestateID='142';";
                
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
            else//insert
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="insert into applicantfreedetail (Price,SaveTime,SaveDate,ClerkID,ApplicantMasterID,ProducersID,freestateID)
                values ('$Price','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$ApplicantMasterID','".$_POST['ProducersID'.$i]."',142);";
               
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
        }
        $Price= str_replace(',', '', $_POST['val3'.$i]);
        if (!in_array($_POST['ProducersID'.$i]."_143",$arraydeliverychecks)&& strlen($Price)>0)
        {
            if (!in_array($_POST['ProducersID'.$i]."_143",$arrayprimaryinsert))//update
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="update applicantfreedetail set Price='$Price' 
                ,SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='".$login_userid."' 
                WHERE ApplicantMasterID='$ApplicantMasterID' and ProducersID='".$_POST['ProducersID'.$i]."' and freestateID='143';";
               
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
            else//insert
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="insert into applicantfreedetail (Price,SaveTime,SaveDate,ClerkID,ApplicantMasterID,ProducersID,freestateID)
                values ('$Price','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$ApplicantMasterID','".$_POST['ProducersID'.$i]."',143);";
                
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
        }
        $Price= str_replace(',', '', $_POST['val4'.$i]);
        if (!in_array($_POST['ProducersID'.$i]."_144",$arraydeliverychecks)&& strlen($Price)>0)
        {
            if (!in_array($_POST['ProducersID'.$i]."_144",$arrayprimaryinsert))//update
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="update applicantfreedetail set Price='$Price' 
                ,SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='".$login_userid."' 
                WHERE ApplicantMasterID='$ApplicantMasterID' and ProducersID='".$_POST['ProducersID'.$i]."' and freestateID='144';";
            
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
            else//insert
            {
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
                $query="insert into applicantfreedetail (Price,SaveTime,SaveDate,ClerkID,ApplicantMasterID,ProducersID,freestateID)
                values ('$Price','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$ApplicantMasterID','".$_POST['ProducersID'.$i]."',144);";
                
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
        }
    }
            /*
            applicantfreedetail جدول ریز آزادسازی
            $ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            Price مبلغ
            */
            $query="update applicantmasterdetail set freechks='$chkstr'
            WHERE ApplicantMasterID='$ApplicantMasterID' or ApplicantMasterIDmaster='$ApplicantMasterID' or ApplicantMasterIDsurat='$ApplicantMasterID';";
  
               
	
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
else 
{
    $uid=$_GET["uid"];
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $type=$linearray[1];//نوع
    $DesignerCoID=$linearray[2];//طراح
    $OperatorCoID=$linearray[3];//مجری
    //if (!($OperatorCoID>0)) header("Location: ../login.php");   
    $g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;
    
}


if (!($ApplicantMasterID>0)) exit(0);

            /*
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
            */

$query="select freechks from applicantmasterdetail WHERE ApplicantMasterIDmaster='$ApplicantMasterID';";

					try 
							  {		
								$result1 = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$row1 = mysql_fetch_assoc($result1);    
$linearraychkstr = explode('_',$row1['freechks']);

    
$arraydeliverychecks=array();
            /*
            applicantfreedetail جدول ریز آزادسازی
            ProducersID شناسه تولید کننده
            freestateID شناسه شماره قسط
            CheckNo شماره چک
            applicantmasterid شناسه طرح
            */
            
$sql1="SELECT concat(ProducersID,'_',freestateID) _key FROM applicantfreedetail
where ifnull(applicantfreedetail.CheckNo,'')<>'' and ApplicantMasterID='$ApplicantMasterID'";
//print $sql1;    

					try 
							  {		
								$result1 = mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$i=0;
while($row1 = mysql_fetch_assoc($result1))
{
    $arraydeliverychecks[$i]=$row1['_key']; 
    $i++;   
}
/*
            ProducersID شناسه تولید کننده
            applicantfreedetail جدول ریز آزادسازی
            freestate وضعی آزادسازی
            applicantmasterid شناسه طرح
            applicantfreedetailID شناسه ریز قسط آزادسازی 
            freestateID شناسه شماره قسط
            $ProducersID شناسه تولید کننده
            paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
            OperatorCoID شناسه پیمانکار
            Price  مبلغ
            CheckNo شماره چک
            CheckDate تاریخ چک
            CheckBank بانک
            letterdate تاریخ نامه آزادسازی
            letterno شماره نامه آزادسازی
            Description توضیحات
            AccountBank بانک حساب
            AccountNo شماره حساب    
            invoicetiming جدول زمانبندی اجرای طرح ها
            ApproveA تایید ارسال لوله ها توسط بازرس
            BOLNO شماره بارنامه لوله
            ApproveP تاریخ اعلامی تولیدکننده جهت ارسال لوازم به محل پروژه
            creditsourceID منبع تامین اعتبار طرح
            creditsource جدول منابع اعتباری
            criditType تجمیع بودن یا نبودن طرح
            DesignSystemGroupsID نوع سیستم آبیاری
            DesignerCoIDnazer شناسه مشاور ناظر طرح
            ApplicantFName عنوان اول طرح
            SaveTime زمان ثبت طرح
            SaveDate تاریخ ثبت طرح
            ClerkID کاربر ثبت
            CityId شناسه شهر طرح
            CountyName روستای طرح
            numfield شماره پرونده طرح
            ClerkIDsurveyor شناسه کاربر نقشه بردار
            YearID سال طرح
            mobile تلفن همراه
            melicode کد/شناسه ملی
            SurveyArea مساحت نقشه برداری شده
            surveyDate تاریخ نقشه برداری
            coef5 ضریب منطقه ای طرح
            CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
            TransportCostTableMasterID شناسه جدول هزینه حمل طرح
            RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
            DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
            DesignerID شناسه طراح طرح
            StationNumber تعداد ایستگاه های طرح
            XUTM1 یو تی ام ایکس
            YUTM1 یو تی ام وای
            SoilLimitation محدودیت بافت خاک دارد یا خیر    
            proposable  پیشنهاد قیمت لوله
            applicantstatesID شناسه وضعیت پروژه
            TMDate تاریخ جلسه کمیته فنی
            applicantstates.title عنوان وضعیت پروژه
            hektar سطح پروژه
            prjtypeid نوع پروژه
            nazerID ناظر پروژه
            creditsourceTitle عنوان منبع تامین اعتبار
            ApplicantMasterIDmaster شناسه طرح اجرایی
            DesignerCoID شناسه مشاور طراح
            applicantmaster جدول مشخصات طرح
            applicantmasterdetail جدول ارتباطی طرح ها
            ApplicantMasterID شناسه طرح
            ApplicantMasterIDmaster شناسه طرح اجرایی
            designsystemgroupsdetail جدول ریز سیستم های آبیاری
            appstatesee لیست وضعیت هایی که هر نقش می بیند
            invoicemaster لیست پیش فاکتورها
            operatorcoid شناسه پیمانکار
            private شخصی بودن طرح
            Debi دبی طرح
            DesignArea مساحت طرح
            Code سریال طرح
            BankCode کد رهگیری طرح
            ApplicantName عنوان طرح

    */ 
$sql = "select prodfree.ProducersID,prodfree.Title,prodfree.freestateID,prodfree.FTitle,appTitle,tot,PipeProducer,sum(applicantfreedetail.Price)Price from(
select prods.ProducersID,prods.Title,freestate.freestateID,freestate.Title FTitle,appTitle,tot,PipeProducer from (

		select producers.ProducersID,concat('فروشنده: ',producers.Title) COLLATE utf8_general_ci as Title 
        , concat(ApplicantName,' ',ApplicantFName) COLLATE utf8_general_ci appTitle,sum(invoicemaster.tot) tot,producers.PipeProducer
       from producers 
       inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
                                            or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
	   inner join applicantmaster on applicantmaster.applicantmasterid=case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then 
       applicantmasterdetail.ApplicantMasterIDsurat else applicantmasterdetail.ApplicantMasterIDmaster end
       inner join invoicemaster on invoicemaster.ProducersID=producers.ProducersID and invoicemaster.ApplicantMasterID=applicantmaster.applicantmasterid
	   inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
       where producers.ProducersID<>135
       group by producers.ProducersID,concat('فروشنده: ',producers.Title) COLLATE utf8_general_ci 
        , concat(ApplicantName,' ',ApplicantFName) COLLATE utf8_general_ci,producers.PipeProducer
       
       
       union all 
	   select 0 as ProducersID, 'سایر' Title 
       , concat(ApplicantName,' ',ApplicantFName) COLLATE utf8_general_ci appTitle,0 tot,1001 PipeProducer from applicantmaster
       inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
                                            or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
       where applicantmaster.applicantmasterid=case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then 
       applicantmasterdetail.ApplicantMasterIDsurat else applicantmasterdetail.ApplicantMasterIDmaster end
       union all 
       
       select -1 as ProducersID, concat('مجری',' ',operatorco.title) COLLATE utf8_general_ci Title 
       , concat(applicantmaster.ApplicantName,' ',applicantmaster.ApplicantFName) COLLATE utf8_general_ci appTitle,
       LEAST(LEAST((applicantmasterd.selfcashhelpval+applicantmasterd.selfnotcashhelpval+applicantmasterd.belaavaz*1000000),applicantmaster.LastTotal)
       ,applicantmasterd.LastTotal) tot,1000 PipeProducer
       from applicantmaster 
       inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
                                            or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
       inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
       inner join applicantmaster applicantmasterd on applicantmasterd.applicantmasterid=applicantmasterdetail.applicantmasterid
	   where applicantmaster.applicantmasterid=case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then 
       applicantmasterdetail.ApplicantMasterIDsurat else applicantmasterdetail.ApplicantMasterIDmaster end
       union all 
       
       
       
       
       
	   select -2 as ProducersID, 
       concat('کشاورز (عودت خودیاری):',' ',applicantmaster.ApplicantName,' ',applicantmaster.ApplicantFName) COLLATE utf8_general_ci Title 
       , concat(applicantmaster.ApplicantName,' ',applicantmaster.ApplicantFName) COLLATE utf8_general_ci appTitle,
       (applicantmasterd.selfcashhelpval+applicantmasterd.selfnotcashhelpval)-
       (LEAST(applicantmaster.LastTotal,applicantmasterd.LastTotal)-(LEAST(applicantmasterd.belaavaz,applicantmaster.belaavaz)*1000000))
       tot,1002 PipeProducer
       from applicantmaster 
       inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
                                            or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
       inner join applicantmaster applicantmasterd on applicantmasterd.applicantmasterid=applicantmasterdetail.applicantmasterid                                     
	   where applicantmaster.applicantmasterid=case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then 
       applicantmasterdetail.ApplicantMasterIDsurat else applicantmasterdetail.ApplicantMasterIDmaster end
       
       
       
       union all select -3 as ProducersID, concat('کشاورز (انجام عملیات):',' ',ApplicantName,' ',ApplicantFName) COLLATE utf8_general_ci Title
       , concat(ApplicantName,' ',ApplicantFName) COLLATE utf8_general_ci appTitle ,othercosts5 tot,1003 PipeProducer
       from applicantmaster 
       inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' 
                                            or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'
	   where applicantmaster.applicantmasterid=case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then 
       applicantmasterdetail.ApplicantMasterIDsurat else applicantmasterdetail.ApplicantMasterIDmaster end
       
       ) prods,freestate)prodfree

	   left outer join applicantfreedetail on applicantfreedetail.freestateID=prodfree.freestateID and 
	   applicantfreedetail.ProducersID=prodfree.ProducersID and applicantfreedetail.applicantmasterid='$ApplicantMasterID'
	   group by prodfree.ProducersID,prodfree.Title,prodfree.freestateID,prodfree.FTitle,appTitle,tot,PipeProducer
       
       order by PipeProducer,prodfree.ProducersID";

//print $sql;
$sumother=0;
$arrayvals=array();
$arrayPtitles=array();
$arrayPtot=array();

							try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while($row = mysql_fetch_assoc($result))
{
    $ApplicantName=$row['appTitle'];
    $arrayvals["$row[ProducersID]"]["$row[freestateID]"]=$row['Price'];
    $arrayPtitles["$row[ProducersID]"]=$row['Title'];
    if ($row['ProducersID']!=-1 && $row['ProducersID']!=-2 && $row['freestateID']==141)
        $sumother+=$row['tot'];
    //print $sumother."<br>";    
    $arrayPtot["$row[ProducersID]"]=$row['tot'];
}
//print $sumother." ".$arrayPtot["-1"];
$arrayPtot["-1"]-=$sumother;



////////////////////////اسکن نامه های آزادسازی


$sql1="SELECT freestateID,applicantfreedetailID,letterdate,letterno FROM applicantfreedetail
    where  ApplicantMasterID='$ApplicantMasterID'";
$fstr1="";
$fstr2="";
$fstr3="";
$fstr4="";

						try 
							  {		
								$result1 = mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while($row1 = mysql_fetch_assoc($result1))
{
    $IDUser =$row1['applicantfreedetailID'];
    $freestateID =$row1['freestateID'];
    $letterdate =$row1['letterdate'];
    $letterno =$row1['letterno'];
    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/free/';
   	$handler = opendir($directory);
    while ($file = readdir($handler)) 
    {
        if ($file != "." && $file != "..") 
        {
            $linearray = explode('_',$file);
            $IDU=$linearray[0];
            $No=$linearray[1];
            $num=$linearray[2];
            if (($IDU==$IDUser))
            {
                if ($freestateID==141)
                    $fstr1.=" <a href='../../upfolder/free/$file' target='_blank' > (ش:$letterno&nbsp;ت:$letterdate)</a>";
                if ($freestateID==142)
                    $fstr2.=" <a href='../../upfolder/free/$file' target='_blank' > (ش:$letterno&nbsp;ت:$letterdate)</a>";
                if ($freestateID==143)
                    $fstr3.=" <a href='../../upfolder/free/$file' target='_blank' > (ش:$letterno&nbsp;ت:$letterdate)</a>";
                if ($freestateID==144) 
                    $fstr4.=" <a href='../../upfolder/free/$file' target='_blank' > (ش:$letterno&nbsp;ت:$letterdate)</a>";
            }
                                    
                            
                            
       }
    }

}

/////////////////////
//print_r($arrayPtitles);
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پرداختی ها</title>

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


    <script type="text/javascript">
         
        
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
    function selectpage(){
       
        window.location.href ='?uid=' +document.getElementById('uid').value
        + '&g2id=' + document.getElementById('g2id').value;
        
	}
    function startfunc()
    {
        
        for(i=1;i<=document.getElementById('cnt').value;i++)
            if (document.getElementById('chk'+i).checked)
                checkoxchange(i);
    }
function checkoxchange(i)
{
    var r=Math.round(p_tarkib(document.getElementById('htot'+i).value)-p_tarkib(document.getElementById('stval'+i).value));
    if (document.getElementById('chk'+i).checked)
    {
        for(j=1;j<=document.getElementById('cnt').value;j++)
            if(document.getElementById('ProducersID'+j).value==-1)
            {
                document.getElementById('tot'+j).value=p_tarkib(document.getElementById('tot'+j).value)*1+
                r*1;
                document.getElementById('tot'+j).value=numberWithCommas(document.getElementById('tot'+j).value);
            }
        document.getElementById('tot'+i).value=numberWithCommas(document.getElementById('stval'+i).value);   
                
    }
    else
    {
        for(j=1;j<=document.getElementById('cnt').value;j++)
            if(document.getElementById('ProducersID'+j).value==-1)
            {
                document.getElementById('tot'+j).value=p_tarkib(document.getElementById('tot'+j).value)*1-
                r;
                document.getElementById('tot'+j).value=numberWithCommas(document.getElementById('tot'+j).value);
            }
        document.getElementById('tot'+i).value=document.getElementById('htot'+i).value;
    }        
    
    calval();

}    
    function calval()
    {   
        //alert(1);
        
        var i=0;
        document.getElementById('sval1').value=0;
        document.getElementById('sval2').value=0;
        document.getElementById('sval3').value=0;
        document.getElementById('sval4').value=0;
        for(i=1;i<=document.getElementById('cnt').value;i++)
        {

                
            document.getElementById('sval1').value=document.getElementById('sval1').value*1+p_tarkib(document.getElementById('val1'+i).value)*1;
            document.getElementById('sval2').value=document.getElementById('sval2').value*1+p_tarkib(document.getElementById('val2'+i).value)*1;
            document.getElementById('sval3').value=document.getElementById('sval3').value*1+p_tarkib(document.getElementById('val3'+i).value)*1;
            document.getElementById('sval4').value=document.getElementById('sval4').value*1+p_tarkib(document.getElementById('val4'+i).value)*1;
            if (p_tarkib(document.getElementById('tot'+i).value)>0)
            {
                if ((Math.round((p_tarkib(document.getElementById('val1'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10)>0)
                    document.getElementById('pval1'+i).value=Math.round((p_tarkib(document.getElementById('val1'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10;
                if ((Math.round((p_tarkib(document.getElementById('val2'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10)>0)
                    document.getElementById('pval2'+i).value=Math.round((p_tarkib(document.getElementById('val2'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10;
                if ((Math.round((p_tarkib(document.getElementById('val3'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10)>0)
                    document.getElementById('pval3'+i).value=Math.round((p_tarkib(document.getElementById('val3'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10;
                if ((Math.round((p_tarkib(document.getElementById('val4'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10)>0)
                    document.getElementById('pval4'+i).value=Math.round((p_tarkib(document.getElementById('val4'+i).value)*100)/(p_tarkib(document.getElementById('tot'+i).value)*1)*10)/10;
       
            document.getElementById('ptval'+i).value=Math.round((p_tarkib(document.getElementById('val1'+i).value)*1+p_tarkib(document.getElementById('val2'+i).value)*1
            +p_tarkib(document.getElementById('val3'+i).value)*1+p_tarkib(document.getElementById('val4'+i).value)*1)*100/p_tarkib(document.getElementById('tot'+i).value)*10)/10;
                
            }
            else
            {
                document.getElementById('pval1'+i).value='';
                document.getElementById('pval2'+i).value='';
                document.getElementById('pval3'+i).value='';
                document.getElementById('pval4'+i).value='';
            }
            document.getElementById('stval'+i).value=numberWithCommas(p_tarkib(document.getElementById('val1'+i).value)*1+p_tarkib(document.getElementById('val2'+i).value)*1
            +p_tarkib(document.getElementById('val3'+i).value)*1+p_tarkib(document.getElementById('val4'+i).value)*1);
            document.getElementById('remainval'+i).value=numberWithCommas(Math.round(p_tarkib(document.getElementById('tot'+i).value)-p_tarkib(document.getElementById('stval'+i).value)));
            
        }   
        
        
        document.getElementById('psval1').value=Math.round((document.getElementById('sval1').value*100)/(p_tarkib(document.getElementById('stot').value)*1)*10)/10;
        document.getElementById('psval2').value=Math.round((document.getElementById('sval2').value*100)/(p_tarkib(document.getElementById('stot').value)*1)*10)/10;
        document.getElementById('psval3').value=Math.round((document.getElementById('sval3').value*100)/(p_tarkib(document.getElementById('stot').value)*1)*10)/10;
        document.getElementById('psval4').value=Math.round((document.getElementById('sval4').value*100)/(p_tarkib(document.getElementById('stot').value)*1)*10)/10;
        
        document.getElementById('sval1').value=numberWithCommas(document.getElementById('sval1').value);    
        document.getElementById('sval2').value=numberWithCommas(document.getElementById('sval2').value);    
        document.getElementById('sval3').value=numberWithCommas(document.getElementById('sval3').value);    
        document.getElementById('sval4').value=numberWithCommas(document.getElementById('sval4').value);    
        
         document.getElementById('sstval').value=numberWithCommas(p_tarkib(document.getElementById('sval1').value)*1+p_tarkib(document.getElementById('sval2').value)*1
            +p_tarkib(document.getElementById('sval3').value)*1+p_tarkib(document.getElementById('sval4').value)*1);
         document.getElementById('pptval').value=Math.round((p_tarkib(document.getElementById('sstval').value)*1)*100/p_tarkib(document.getElementById('stot').value)*10)/10;
       document.getElementById('remainval').value=numberWithCommas(Math.round(p_tarkib(document.getElementById('stot').value)-p_tarkib(document.getElementById('sstval').value)));
            
        
    }
                 
    </script>
    <!-- /scripts -->
</head>
<body onload="startfunc()">

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
            
            <form action="invoicemasterfree_list2.php" method="post"  enctype="multipart/form-data">
             
             
                
                <table width="95%" align="center">
                    <tbody >
                    <?php
                    
                    require_once("../funcviewapp.php");
                	$sql=sqlviewapp();
                	$sql=$sql." and applicantmasterop.ApplicantMasterID='$ApplicantMasterID'";
                    //print $sql;
                    
						try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                    $rowresult = mysql_fetch_assoc($result);
                    print "
                    <table >
                    <tr><td  class='f14_fontcb' colspan=4> مدیریت آزادسازی طرح :   $rowresult[ApplicantFName] $rowresult[ApplicantName]    -     کد رهگیری : $rowresult[BankCode] </td></tr>
                    <tr><td class='f14_fontcb' style='text-align: right;width: 350px;'>نام  : $rowresult[ApplicantFName] $rowresult[ApplicantName]  
						<td class='f14_fontcb' style='text-align: right;width: 350px;'> شهرستان  : $rowresult[shahrcityname]  </td> 
						<td class='f14_fontcb' style='text-align: right;width: 350px;'>  مساحت طرح : $rowresult[DesignArea] &nbsp;هكتار </td>
						<td class='f14_fontcb' style='text-align: right;width: 350px;'>  آخرين وضعيت : $rowresult[applicantstatestitle] </td> 	
                    
                    <tr><td class='f14_fontcb' style='text-align: right;width: 350px;'>شركت طراح  :$rowresult[DesignerCotitle]  
					    <td class='f14_fontcb' style='text-align: right;width: 350px;'>شركت ناظر : $rowresult[nazercoTitle]</td>
						<td class='f14_fontcb' style='text-align: right;width: 350px;'> سهم بلاعوض : $rowresult[belaavaz] &nbsp;م ر</td>  
					    <td class='f14_fontcb' style='text-align: right;width: 350px;'>مبلغ طراحی : ".number_format($rowresult['LastTotal'])."   </td> 
						
						</tr>
					<tr><td class='f14_fontcb' style='text-align: right;width: 350px;'>شركت مجري : $rowresult[OperatorcoTitle]</td>
					    <td class='f14_fontcb' style='text-align: right;width: 350px;'>مبلغ پیش فاکتور : ".number_format($rowresult['LastTotalop'])."</td>
						<td class='f14_fontcb' style='text-align: right;width: 350px;'>مبلغ صورت وضعیت : ".number_format($rowresult['LastTotals'])."</td>
						<td class='f14_fontcb' style='text-align: right;width: 350px;'>هزینه اجرا : ".number_format($rowresult['LastFehrestbahawithcoef'])."</td>
						
						</tr>
                        </table>
                        ";
                    
                     ?>
                        
                        
                        <tr>
                        
                        <div style = "text-align:left;">

				       <?php 
                //    $permitrolsid = array("1","5","19","13","14");
                  //  if (in_array($login_RolesID, $permitrolsid))
                  //  {
                        
                        
                                    
                   // }         
                   
                   print "<a  href='$_SERVER[HTTP_REFERER]'><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت'></a>";
                    
                     ?>
         
						
               
               </div>
               
                          <INPUT type="hidden" id="OperatorCoID" name="OperatorCoID" value="<?php print $OperatorCoID; ?>"/>
                          <INPUT type="hidden" id="type" name="type" value="<?php print $type; ?>"/>
                          
                          
                          <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!-- div style = "text-align:left;">
                            <button title='افزودن طرح جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div -->
                          
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr style='color:0000ff; background-color: #B2FFB7'>
                        
                            <th ></th>
                        	<th >دریافت کننده</th>
                            <th >مبلغ کل</th>
                            <th ></th>
                            <th >قسط اول</th>
                            <th >%</th>
                            <th >قسط دوم</th>
                            <th >%</th>
                            <th >قسط سوم</th>
                            <th >%</th>
                            <th >قسط چهارم</th>
                            <th >%</th>
                            <th >آزادسازی کل</th>
                            <th >%</th>
                            <th >آزادسازی نشده</th>
                        </tr>
                   
                        
                    
                                
   <?php
   print " <tr><td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID' value='$ApplicantMasterID' /></td></tr> ";
   $cn=1;
   $s1=0;
   $s2=0;
   $s3=0;
   $s4=0;
   $s5=0;
    foreach($arrayvals as $key=>$val)
    {
        $tot=$arrayPtot["$key"];
        if($arrayvals[$key][141]>0)
            $v1=number_format($arrayvals[$key][141]); else $v1='';
        if($arrayvals[$key][142]>0)
            $v2=number_format($arrayvals[$key][142]); else $v2='';
        if($arrayvals[$key][143]>0)
            $v3=number_format($arrayvals[$key][143]); else $v3='';
        if($arrayvals[$key][144]>0)
            $v4=number_format($arrayvals[$key][144]); else $v4='';
        if(round($arrayvals[$key][141]*100/$tot,1)>0)
            $p1=round($arrayvals[$key][141]*100/$tot,1); else $p1='';
        if(round($arrayvals[$key][142]*100/$tot,1)>0)
            $p2=round($arrayvals[$key][142]*100/$tot,1); else $p2='';
        if(round($arrayvals[$key][143]*100/$tot,1)>0)
            $p3=round($arrayvals[$key][143]*100/$tot,1); else $p3='';
        if(round($arrayvals[$key][144]*100/$tot,1)>0)
            $p4=round($arrayvals[$key][144]*100/$tot,1); else $p4='';
        
        $s1+=$tot;
        $s2+=$arrayvals[$key][141];
        $s3+=$arrayvals[$key][142];
        $s4+=$arrayvals[$key][143];
        $s5+=$arrayvals[$key][144];
        $st=$arrayvals[$key][141]+$arrayvals[$key][142]+$arrayvals[$key][143]+$arrayvals[$key][144];
        if(round($st*100/$tot,1)>0)
            $pt=round($st*100/$tot,1); else $pt='';
            
            //print_r($arraydeliverychecks);
            
         if (!in_array("$key"."_141",$arraydeliverychecks)) $val1dis="style='background-color : #f7fa8d;'"; else $val1dis="readonly";   
         if (!in_array("$key"."_142",$arraydeliverychecks)) $val2dis="style='background-color : #f7fa8d;'"; else $val2dis="readonly"; 
         if (!in_array("$key"."_143",$arraydeliverychecks)) $val3dis="style='background-color : #f7fa8d;'"; else $val3dis="readonly"; 
         if (!in_array("$key"."_144",$arraydeliverychecks)) $val4dis="style='background-color : #f7fa8d;'"; else $val4dis="readonly";     
            if ($linearraychkstr[$cn-1]==1) $chkstate='checked'; else $chkstate='';
        print "<tr>
        <td class='data'><input type='text' class='textbox' size='1' readonly value='$cn'  />
        <INPUT type='hidden' id='ProducersID$cn' name='ProducersID$cn' readonly value='$key'/></td>
        <td class='data'><input type='text' class='textbox' size='45' readonly value='$arrayPtitles[$key]'  /></td>
        <td class='data'>
        <input id='htot$cn'  name='htot$cn' type='hidden' class='textbox'  readonly value='".number_format($tot)."'  />
        <input id='tot$cn'  name='tot$cn' type='text' class='textbox' size='15' readonly value='".number_format($tot)."'  /></td>
        <td class='data'><input name='chk$cn' onchange=\"checkoxchange($cn);\" type='checkbox' id='chk$cn' $chkstate  /></td>
                       
                       <td ><input $val1dis onblur=\"calval();\" id='val1$cn'  name='val1$cn'  type='text'  size='15' onKeyUp=\"convert('val1$cn')\" value='".($v1)."'  /></td>
        <td class='data'><input   id='pval1$cn' name='pval1$cn' type='text' class='textbox' size='2' readonly  value='$p1'  /></td>
        <td ><input $val2dis onblur=\"calval();\"  id='val2$cn'  name='val2$cn'  type='text'  size='15' onKeyUp=\"convert('val2$cn')\"  value='".($v2)."'  /></td>
        <td class='data'><input   id='pval2$cn' name='pval2$cn' type='text' class='textbox' size='2'  readonly value='$p2'  /></td>
        <td ><input $val3dis onblur=\"calval();\"  id='val3$cn'  name='val3$cn'  type='text'  size='15' onKeyUp=\"convert('val3$cn')\"  value='".($v3)."'  /></td>
        <td class='data'><input   id='pval3$cn' name='pval3$cn' type='text' class='textbox' size='2'  readonly value='$p3'  /></td>
        <td ><input $val4dis onblur=\"calval();\"  id='val4$cn'  name='val4$cn'  type='text'  size='15' onKeyUp=\"convert('val4$cn')\"  value='".($v4)."'  /></td>
        <td class='data'><input   id='pval4$cn' name='pval4$cn' type='text' class='textbox' size='2'  readonly value='$p4'  /></td>
        <td class='data'><input  id='stval$cn'  name='stval$cn'  type='text' class='textbox' size='15' readonly value='".number_format($st)."'  /></td>
        <td class='data'><input   id='ptval$cn' name='pvalt$cn' type='text' class='textbox' size='2'  readonly value='$pt'  /></td>
        <td class='data'><input  id='remainval$cn'  name='remainval$cn'  type='text' class='textbox' size='15' readonly value='".number_format($tot-$st)."'  /></td>
        </tr>";
        
        $cn++;
    }
    print "<tr>
        <td class='data' colspan='2'><input type='text' readonly class='textbox' size='51'  value='مجموع'  /></td>
        <td class='data'colspan='2'><input id='stot' name='stot' readonly type='text' class='textbox'  size='15'  value='".number_format($s1)."'  /></td>
        <td class='data'><input id='sval1' name='sval1' readonly type='text' class='textbox' size='15'  value='".number_format($s2)."'  /></td>
        <td class='data'><input id='psval1' name='psval1' readonly type='text' class='textbox' size='2'  value='".round($s2*100/$s1,1)."'  /></td>
        <td class='data'><input id='sval2' name='sval2' readonly type='text' class='textbox' size='15'  value='".number_format($s3)."'  /></td>
        <td class='data'><input id='psval2' name='psval2' readonly type='text' class='textbox' size='2'  value='".round($s3*100/$s1,1)."'  /></td>
        <td class='data'><input id='sval3' name='sval3' readonly type='text' class='textbox' size='15'  value='".number_format($s4)."'  /></td>
        <td class='data'><input id='psval3' name='psval3' readonly type='text' class='textbox' size='2'  value='".round($s4*100/$s1,1)."'  /></td>
        <td class='data'><input id='sval4' name='sval4' readonly type='text' class='textbox' size='15'  value='".number_format($s5)."'  /></td>
        <td class='data'><input id='psval4' name='psval4' readonly type='text' class='textbox' size='2'  value='".round($s5*100/$s1,1)."'  /></td>
        <td class='data'><input id='sstval'  name='sstval' readonly type='text' class='textbox' size='15'  value='".number_format($s2+$s3+$s4+$s5)."'  /></td>
        <td class='data'><input id='pptval' name='ppvalt' readonly type='text' class='textbox' size='2'   value='".round(($s2+$s3+$s4+$s5)*100/$s1,1)."'  /></td>
        <td class='data'><input  id='remainval'  name='remainval' readonly type='text' class='textbox' size='15'  value='".number_format($s1-($s2+$s3+$s4+$s5))."'  /></td>
        </tr>
        <tr>
        <td class='data' colspan='4'><input type='text' readonly class='textbox' size='51'  value='نامه آزادسازی'  /></td>
        <td style = \"text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\" colspan='2'>$fstr1</td>
        <td style = \"text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\" colspan='2'>$fstr2</td>
        <td style = \"text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\" colspan='2'>$fstr3</td>
        <td style = \"text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\" colspan='2'>$fstr4</td>
        <td class='data' colspan='4'></td>
        </tr>
        ";
    
      
                    print "<tr><td colspan=2><input   name='submit' type='submit' class='button' id='submit' value='ثبت'  /></td></tr>";
				   ?>
                </table>
				
			
                          <INPUT type="hidden" id="cnt" name="cnt" value="<?php print $cn-1; ?>"/>
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
