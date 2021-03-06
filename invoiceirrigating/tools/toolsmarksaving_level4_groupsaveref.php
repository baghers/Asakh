﻿<?php 
/*
tools/toolsmarksaving_level4_groupsaveref.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level4_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");


if (! $_POST)
{
    $linearray = explode('_', substr($_GET["uid"],40,strlen($_GET["uid"])-45));
    if ($linearray[2]=='1')
    {
        $Gadget1ID=$linearray[0];//جدول سطح 1 ابزار
        $ProducersID=$linearray[1];// شناسه تولید کننده
 /*
        producers جدول تولیدکننده
        producersid شناسه تولید کننده
        producers.Title عنوان تولید کننده
        pricelistdetail جدول قیمت های تایید شده
        marks جدول مارک ها
        toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
            ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
            gadget3ID شناسه سطح 3 ابزار
            ProducersID شناسه جدول تولیدکننده
            MarksID شناسه جدول مارک
        toolsmarksid شناسه ابزار و مارک
        gadget3 جدول سطح سوم ابزار
        gadget2 جدول سطح دوم ابزار
        gadget1 جدول سطح اول ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
        PriceListMasterID شناسه لیست قیمت
        price مبلغ
        units جدول واحدهای اندازه گیری کالا
        sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
        operator جدول عملگر های تشکیل دهنده نام کالا
        spec2 مشخصه 2 کالا ها
        spec3 مشخصه 3 کالا ها
        materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
        gadget2 جدول سطح دوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        gadget3 جدول سطح سوم ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        Code کد
        Title عنوان
        unitsID شناسه واحد کالا
        sizeunitsID شناسه اندازه کالا
        spec1 مشخصه اول
        opsize اندازه عملیاتی
        UnitsID2 شناسه واحد فرعی
        UnitsCoef2 ضریب اجرایی 2
        MaterialTypeID شناسه نوع مواد
        zavietoolsorattabaghe مقدار زاویه/طول/سرعت/طبقه
        zavietoolsorattabagheUnitsID واحد  زاویه/طول/سرعت/طبقه
        fesharzekhamathajm مقدار فشار/ضخامت/حجم
        fesharzekhamathajmUnitsID واحد فشار/ضخامت/حجم
        operatorid شناسه عملگر
        spec2id خصوصیت 2
        spec3id خصوصیت 3
        spec3sizeunitsid واحد خصوصیت 3
        IsHide غیر فعال شدن کالا
*/       
            $query ="
        select distinct gadget3.Gadget3ID,gadget2.Gadget2ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
        ,marks.title markstitle,toolsmarks.toolsmarksid
        from gadget3
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1ID='$Gadget1ID' and gadget1.gadget1ID=gadget2.gadget1ID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.ProducersID='$ProducersID'
        inner join marks on marks.marksID=toolsmarks.marksID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        left outer join producers on producers.producersid=toolsmarks.producersid           
        ";
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
	$PTitle = $resquery["PTitle"];
	$g1Title = $resquery["g1Title"];
	$g2Title = $resquery["g2Title"];
	$Title = $resquery["FullTitle"];
	$markstitle = $resquery["markstitle"];
	$Gadget2ID = $resquery["Gadget2ID"];  
	$Gadget3IDunique = $resquery["Gadget3ID"];  
	$toolsmarksid = $resquery["toolsmarksid"]; 
        
    }
    else
    {
     
    $Gadget3IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    if (substr($Gadget3IDProducersID,0,2)=='0,')
        $Gadget3IDProducersID=substr($Gadget3IDProducersID,2);
    
    $Gadget3ID='0';
    $ProducersID='';
    $toolsmarksid='0';
        
    $alllinearray = explode(',',$Gadget3IDProducersID);   
    foreach ($alllinearray as $value) 
    {
        $linearray = explode('_',$value);
        $Gadget3ID.=','.$linearray[0];
        $ProducersID=$linearray[1];
        $toolsmarksid.=','.$linearray[2];
    }
    
    
    $query ="
        select distinct gadget3.Gadget3ID,gadget2.Gadget2ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
        ,marks.title markstitle,toolsmarks.toolsmarksid
        from gadget3
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget2.gadget1ID=gadget1.gadget1ID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.ProducersID='$ProducersID'
        inner join marks on marks.marksID=toolsmarks.marksID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        left outer join producers on producers.producersid=toolsmarks.producersid   
        where toolsmarks.toolsmarksid in ($toolsmarksid)        
        ";
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
	$PTitle = $resquery["PTitle"];
	$g1Title = $resquery["g1Title"];
	$g2Title = $resquery["g2Title"];
	$Title = $resquery["FullTitle"];
	$markstitle = $resquery["markstitle"];
	$Gadget2ID = $resquery["Gadget2ID"];  
	$Gadget3IDunique = $resquery["Gadget3ID"];  
	$toolsmarksid = $resquery["toolsmarksid"];  
    }          
}

$register = false;

if ($_POST){
	$ProducersID = $_POST["ProducersID"];
	$Gadget3ID = $_POST["Gadget3ID"];
	$Gadget2ID = $_POST["Gadget2ID"];
    $pmid=$_POST["pmid"];
    $pmid2=$_POST["pmid2"];
    $pmid3=$_POST["pmid3"];
    $pmid4=$_POST["pmid4"];
    $pmid5=$_POST["pmid5"];
    $pmid6=$_POST["pmid6"];
    $pmid7=$_POST["pmid7"];
    $pmid8=$_POST["pmid8"];
    $pmid9=$_POST["pmid9"];
    $pmid10=$_POST["pmid10"];
    $pmid11=$_POST["pmid11"];
    $pmid12=$_POST["pmid12"];
    
    $linearray = explode('_',$pmid);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid 
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
   
    $linearray = explode('_',$pmid2);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
   
    $linearray = explode('_',$pmid3);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
   
    $linearray = explode('_',$pmid4);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid5);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid6);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid7);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid8);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid9);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid10);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid11);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
   
    $linearray = explode('_',$pmid12);
    $Producersidref=$linearray[0];
    $Marksidref=$linearray[1];
    $i=0;   
    while (isset($_POST['toolsmarksid'.++$i]))
    {
        $toolsmarksid = $_POST['toolsmarksid'.$i];
        $Gadget3ID = $_POST['Gadget3ID'.$i];
            
  		$query = "
		UPDATE toolsmarks SET
		ToolsMarksIDpriceref =(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1), 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE toolsmarksid = $toolsmarksid  and ifnull(ToolsMarksIDpriceref,0)=0
        and exists(select toolsmarksid from (select toolsmarksid from toolsmarks where Gadget3ID='$Gadget3ID' and 
        Producersid='$Producersidref' and Marksid='$Marksidref') view1);";
        //print $query;exit;
        $result = mysql_query($query);
        $register = true;        
   }
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت مارک مرجع قیمتی</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
                        header("Location: toolsmarksaving_level4_detailedlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999));
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="toolsmarksaving_level4_groupsaveref.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "toolsmarksaving_level4_detailedlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                     <tr>
                     <td width="20%" class="label">تولیدکننده:</td>
                      <td width="80%" class="data"><input readonly name="PTitle" type="text" class="textbox" id="PTitle" value="<?php echo $PTitle; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td width="20%" class="label">عنوان سطح1:</td>
                      <td width="80%" class="data"><input readonly name="g1Title" type="text" class="textbox" id="g1Title" value="<?php echo $g1Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td width="20%" class="label">عنوان سطح2:</td>
                      <td width="80%" class="data"><input readonly name="g2Title" type="text" class="textbox" id="g2Title" value="<?php echo $g2Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <?php
                     $cnt=1;
                            print "<tr>
                     <br>
                     <td width='20%' class='label'>عنوان:</td>
                      <td width='80%' class='data'><input readonly name='Title' type='text' class='textbox' id='Title' value='$Title' size='50' maxlength='6' /></td>
                      <td width='80%' class='data'><input readonly name='markstitle' type='text' class='textbox' id='markstitle' value='$markstitle' size='10' maxlength='6' /></td>
                     <td class='data'><input name='toolsmarksid$cnt' type='hidden' class='textbox' id='toolsmarksid$cnt'  value='$toolsmarksid'  size='30' maxlength='15' /></td>
                     <td class='data'><input name='Gadget3ID$cnt' type='hidden' class='textbox' id='Gadget3ID$cnt'  value='$Gadget3IDunique'  size='30' maxlength='15' /></td>
                     </tr>
                     ";
                     $Gadget3ID=$Gadget3IDunique;
                        while($resquery = mysql_fetch_assoc($result))
                        {
                            $PTitle = $resquery["PTitle"];
	                        $g1Title = $resquery["g1Title"];
	                        $g2Title = $resquery["g2Title"];
	                        $Title = $resquery["FullTitle"];
	                        $Gadget2ID = $resquery["Gadget2ID"];
	                        $markstitle = $resquery["markstitle"];
	                        $toolsmarksid = $resquery["toolsmarksid"];  
	                        $Gadget3IDunique = $resquery["Gadget3ID"];
                            $Gadget3ID.=','.$resquery["Gadget3ID"];  
                            $cnt++;
                            print "<tr>
                     <td width='20%' class='label'>عنوان:</td>
                      <td width='80%' class='data'><input readonly name='Title' type='text' class='textbox' id='Title' value='$Title' size='50' maxlength='6' /></td>
                      <td width='80%' class='data'><input readonly name='markstitle' type='text' class='textbox' id='markstitle' value='$markstitle' size='10' maxlength='6' /></td>
                     <td class='data'><input name='toolsmarksid$cnt' type='hidden' class='textbox' id='toolsmarksid$cnt'  value='$toolsmarksid'  size='30' maxlength='15' /></td>
                     <td class='data'><input name='Gadget3ID$cnt' type='hidden' class='textbox' id='Gadget3ID$cnt'  value='$Gadget3IDunique'  size='30' maxlength='15' /></td>
                     </tr>
                     ";
                            
                        }
                    
                   // if (substr($Gadget3ID,0,2)=='0,')
                   //     $Gadget3ID=substr($Gadget3ID,2);
        
           
    
					 $query="select CONCAT(CONCAT(producers.Producersid,'_'),marks.Marksid) as _value,CONCAT(CONCAT(producers.title,'_'),marks.title) as _key 
                     from toolsmarks 
                     inner join marks on marks.Marksid=toolsmarks.Marksid
                     inner join producers on producers.Producersid=toolsmarks.Producersid
                     
                     where Gadget3ID in ($Gadget3ID) and toolsmarks.ProducersID<>'$ProducersID' and producers.ProducersID<>142 and toolsmarks.Marksid<>128
                     order by _key   COLLATE utf8_persian_ci";
    				 //print $query;
                     $toolsmarks = get_key_value_from_query_into_array($query);
                     print select_option('pmid','مارک مرجع قیمت',',',$toolsmarks);
                     print select_option('pmid2','مارک مرجع قیمت2',',',$toolsmarks);
                     print select_option('pmid3','مارک مرجع قیمت3',',',$toolsmarks);
                     print "<tr>".select_option('pmid4','مارک مرجع قیمت4',',',$toolsmarks);
                     print select_option('pmid5','مارک مرجع قیمت5',',',$toolsmarks);
                     print select_option('pmid6','مارک مرجع قیمت6',',',$toolsmarks);
                     print "</tr><tr>".select_option('pmid7','مارک مرجع قیمت7',',',$toolsmarks);
                     print select_option('pmid8','مارک مرجع قیمت8',',',$toolsmarks);
                     print select_option('pmid9','مارک مرجع قیمت9',',',$toolsmarks);
                     print "</tr><tr>".select_option('pmid10','مارک مرجع قیمت10',',',$toolsmarks);
                     print select_option('pmid11','مارک مرجع قیمت11',',',$toolsmarks);
                     print select_option('pmid12','مارک مرجع قیمت12',',',$toolsmarks)."</tr>";
                            
					  ?>
                     
                     <tr>
                      <td class="data"><input name="ProducersID" type="hidden" class="textbox" id="ProducersID"  value="<?php echo $ProducersID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="Gadget2ID" type="hidden" class="textbox" id="Gadget2ID"  value="<?php echo $Gadget2ID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح" /></td>
                     </tr>
                    </tfoot>
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