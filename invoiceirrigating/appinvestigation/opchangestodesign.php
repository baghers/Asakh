<?php 

/*

//appinvestigation/opchangestodesign.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/allapplicantstatesop.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
        $linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
        $BankCode=$linearray[0];//کد رهگیری
        $type=$linearray[1];
      if ($type>0) {$titr="تغییرات لیست لوازم و فهرست بهای صورت وضعیت نسبت به پیش فاکتورهای طرح";$tit2="صورت وضعیت";$tit1="پیش فاکتور";}
			else {$titr="تغییرات لیست لوازم و فهرست بهای پیش فاکتورها نسبت طرح مطالعاتی";$tit2="پیش فاکتور";$tit1="طراحی";}
			
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
  if ($type>0)
         $sql="SELECT applicantmasterop.costpricelistmasterID costpricelistmasterID,applicantmasterop.ApplicantName
		 ,applicantmasterop.ApplicantMasterID ApplicantMasterID
		 ,applicantmasterop.LastTotal LastTotal,applicantmasterop.LastFehrestbaha LastFehrestbaha,applicantmasterop.LastFehrestbahawithcoef LastFehrestbahawithcoef
		 ,(applicantmasterop.coef1*applicantmasterop.coef2*applicantmasterop.coef3*applicantmasterop.coef4*applicantmasterop.coef5) coef	 ,(applicantmasterop.othercosts1+applicantmasterop.othercosts2+applicantmasterop.othercosts3+applicantmasterop.othercosts4+applicantmasterop.othercosts5) othercosts
		 ,applicantmasterop.TotlainvoiceValues TotlainvoiceValues
		 ,applicantmasterop.TransportCostunder TransportCostunder
		 ,applicantmasterop.unpredictedcost unpredictedcost,substring(applicantmasterop.cityid,1,5) cityid15
		 
		 ,applicantmasterall.costpricelistmasterID costpricelistmasterIDd
		 ,applicantmasterall.ApplicantMasterID ApplicantMasterIDd
		 ,applicantmasterall.LastTotal LastTotald,applicantmasterall.LastFehrestbaha LastFehrestbahad,applicantmasterall.LastFehrestbahawithcoef LastFehrestbahawithcoefd
		 ,(applicantmasterall.coef1*applicantmasterall.coef2*applicantmasterall.coef3*applicantmasterall.coef4*applicantmasterall.coef5) coefd	 ,(applicantmasterall.othercosts1+applicantmasterall.othercosts2+applicantmasterall.othercosts3+applicantmasterall.othercosts4+applicantmasterall.othercosts5) othercostsd
		 ,applicantmasterall.TotlainvoiceValues TotlainvoiceValuesd
		 ,applicantmasterall.TransportCostunder TransportCostunderd
		 ,applicantmasterall.unpredictedcost unpredictedcostd,substring(applicantmasterall.cityid,1,5) cityid15d
		 
         from applicantmaster applicantmasterop
         inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=applicantmasterop.ApplicantMasterIDmaster
         inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmasterall.ApplicantMasterID
         and applicantmasterdetail.ApplicantMasterIDsurat=applicantmasterop.ApplicantMasterID
         where applicantmasterop.BankCode='$BankCode'  and  ifnull(applicantmasterop.ApplicantMasterIDmaster,0)>0";
         
     else  $sql = "SELECT applicantmasterop.costpricelistmasterID,applicantmasterop.ApplicantName
		 ,applicantmasterop.ApplicantMasterID ApplicantMasterID
		 ,applicantmasterop.LastTotal LastTotal
		 ,applicantmasterop.LastFehrestbaha LastFehrestbaha,applicantmasterop.LastFehrestbahawithcoef LastFehrestbahawithcoef
		 ,applicantmasterop.ApplicantMasterIDmaster
		 ,(applicantmasterop.coef1*applicantmasterop.coef2*applicantmasterop.coef3*applicantmasterop.coef4*applicantmasterop.coef5) coef, (applicantmasterop.othercosts1+applicantmasterop.othercosts2+applicantmasterop.othercosts3+applicantmasterop.othercosts4+applicantmasterop.othercosts5) othercosts
		 ,applicantmasterop.TotlainvoiceValues
		 ,applicantmasterop.TransportCostunder
		 ,applicantmasterop.unpredictedcost unpredictedcost,substring(applicantmasterop.cityid,1,5) cityid15
		 
	 
		 ,applicantmasterall.costpricelistmasterID costpricelistmasterIDd
		 ,applicantmasterall.ApplicantMasterID ApplicantMasterIDd
		 ,applicantmasterall.LastTotal LastTotald,applicantmasterall.LastFehrestbaha LastFehrestbahad,applicantmasterall.LastFehrestbahawithcoef LastFehrestbahawithcoefd
		 ,(applicantmasterall.coef1*applicantmasterall.coef2*applicantmasterall.coef3*applicantmasterall.coef4*applicantmasterall.coef5) coefd	 ,(applicantmasterall.othercosts1+applicantmasterall.othercosts2+applicantmasterall.othercosts3+applicantmasterall.othercosts4+applicantmasterall.othercosts5) othercostsd
		 ,applicantmasterall.TotlainvoiceValues TotlainvoiceValuesd
		 ,applicantmasterall.TransportCostunder TransportCostunderd
		 ,applicantmasterall.unpredictedcost unpredictedcostd,substring(applicantmasterall.cityid,1,5) cityid15d
		 
	  
		FROM operatorapprequest
		inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=operatorapprequest.ApplicantMasterID
		inner join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode and applicantmasterall.BankCode='$BankCode' 
		and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
		
        inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmasterall.ApplicantMasterID
         and applicantmasterdetail.ApplicantMasterIDmaster=applicantmasterop.ApplicantMasterID
         
        where operatorapprequest.state=1 and  ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0" ;

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

$row = mysql_fetch_assoc($result);

			$ApplicantName =$row['ApplicantName'];
			$ApplicantMasterID =$row['ApplicantMasterID'];
			$costpricelistmasterID =$row['costpricelistmasterID'];
			$PriceListMasterID =$row['PriceListMasterID'];
			$LastTotal =$row['LastTotal'];
            $LastFehrestbaha =$row['LastFehrestbaha'];
            $LastFehrestbahawithcoef =$row['LastFehrestbahawithcoef'];
			$TotlainvoiceValues=$row['TotlainvoiceValues'];
            $TransportCostunder=$row['TransportCostunder'];
            $cityid15=$row['cityid15'];
			
			$ApplicantMasterIDd =$row['ApplicantMasterIDd'];
			$costpricelistmasterIDd =$row['costpricelistmasterIDd'];
			$PriceListMasterIDd =$row['PriceListMasterIDd'];
			$LastTotald =$row['LastTotald'];
            $LastFehrestbahad =$row['LastFehrestbahad'];
            $LastFehrestbahawithcoefd =$row['LastFehrestbahawithcoefd'];
			$TotlainvoiceValuesd=$row['TotlainvoiceValuesd'];
            $TransportCostunderd=$row['TransportCostunderd'];
            $cityid15d=$row['cityid15d'];
			
	     
		 
$Totlainvoicedif=$TotlainvoiceValues-$TotlainvoiceValuesd;	
$LastFehrestdif=$LastFehrestbaha-$LastFehrestbahad;

$LastFehrestbahasdif=$LastFehrestbahawithcoef-$LastFehrestbahawithcoefd;
$othercosts=($LastTotal-$TotlainvoiceValues-$LastFehrestbahawithcoef);
$othercostsd=($LastTotald-$TotlainvoiceValuesd-$LastFehrestbahawithcoefd);
$othercostsdif=$othercostsd-$othercosts;

$LastTotaldif=$LastTotal-$LastTotald;



//print $ApplicantMasterIDd.'*'.print $ApplicantMasterID.'*'.$costpricelistmasterID.'*'.$costpricelistmasterIDd;exit;

//----------

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
$sql = "
select distinct vm.toolsmarksid,dif
,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) 
            when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) 
            when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
            syntheticgoodsprice.price end  end Price
,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) 
            when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) 
            when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else case ifnull(syntheticgoodspricep.gadget3ID,0) when 0 then pricelistdetailp.Price else 
            syntheticgoodspricep.price end  end Price2
                        
,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(
		gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),
		CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),
		ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),
		ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),
		ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),
		ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         gadget3Title,units.Title unitsTitle,marks.Title marksTitle  
         
          from (select alld.toolsmarksid,sumd,sumop,round(ifnull(sumop,0)-ifnull(sumd,0),2) dif  from (


select invoicedetail.toolsmarksid,sum(Number) sumd from invoicedetail 
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
and invoicemaster.ApplicantMasterID='$ApplicantMasterIDd' group by invoicedetail.toolsmarksid


) alld
left outer join (
select invoicedetail.toolsmarksid,sum(Number) sumop from invoicedetail 
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
and invoicemaster.ApplicantMasterID='$ApplicantMasterID' group by invoicedetail.toolsmarksid
) allop on allop.toolsmarksid=alld.toolsmarksid
where ifnull(alld.toolsmarksid,0)<>ifnull(allop.toolsmarksid,0) or (ifnull(sumd,0)-ifnull(sumop,0))<>0

union all

select alld.toolsmarksid,sumd,sumop,round(ifnull(sumd,0)-ifnull(sumop,0),2) dif  from (
select invoicedetail.toolsmarksid,sum(Number) sumd from invoicedetail 
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
and invoicemaster.ApplicantMasterID='$ApplicantMasterID' group by invoicedetail.toolsmarksid) alld
left outer join (select invoicedetail.toolsmarksid,sum(Number) sumop from invoicedetail 
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
and invoicemaster.ApplicantMasterID='$ApplicantMasterIDd' group by invoicedetail.toolsmarksid) allop on allop.toolsmarksid=alld.toolsmarksid
where ifnull(alld.toolsmarksid,0)<>ifnull(allop.toolsmarksid,0) or (ifnull(sumd,0)-ifnull(sumop,0))<>0) vm

        inner join toolsmarks on toolsmarks.toolsmarksid=vm.toolsmarksid
        inner join marks on marks.marksID=toolsmarks.marksID
        inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3id
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
		left outer join units on gadget3.unitsID=units.unitsID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        
        
       left outer join (select max(PriceListMasterID) PriceListMasterID,invoicedetail.toolsmarksid from invoicemaster 
       inner join invoicedetail on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid
       where ApplicantMasterID= '$ApplicantMasterID'
       group by invoicedetail.toolsmarksid) vp on vp.toolsmarksid=vm.toolsmarksid
       
        left outer join toolspref on toolspref.PriceListMasterID=vp.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistdetail.PriceListMasterID=vp.PriceListMasterID and 
                                            pricelistdetail.toolsmarksID = (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
         left outer join syntheticgoodsprice on syntheticgoodsprice.PriceListMasterID=vp.PriceListMasterID and 
        syntheticgoodsprice.gadget3ID=gadget3.gadget3ID
               
       left outer join (select max(PriceListMasterID) PriceListMasterID,invoicedetail.toolsmarksid from invoicemaster 
       inner join invoicedetail on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid
       where ApplicantMasterID= '$ApplicantMasterIDd'
       group by invoicedetail.toolsmarksid) vpp on vpp.toolsmarksid=vm.toolsmarksid
       
        left outer join toolspref toolsprefp on toolsprefp.PriceListMasterID=vpp.PriceListMasterID and toolsprefp.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail pricelistdetailp on  pricelistdetailp.PriceListMasterID=vpp.PriceListMasterID and 
                                            pricelistdetailp.toolsmarksID = (case ifnull(toolsprefp.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolsprefp.ToolsMarksIDpriceref end) 
        
         left outer join syntheticgoodsprice syntheticgoodspricep on syntheticgoodspricep.PriceListMasterID=vpp.PriceListMasterID and 
        syntheticgoodspricep.gadget3ID=gadget3.gadget3ID
        
        
        
        
        left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID) 
        and pipeprice.ProducersID=toolsmarks.ProducersID
        
        
        where dif<>0
       order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
  
";
//print $sql;exit;
	
$fehrestquery=fehrestquery(1,1,$ApplicantMasterID,$costpricelistmasterID,$cityid15,"");
$result = mysql_query($fehrestquery);
$fehrestquerystr="";
while($row = mysql_fetch_assoc($result))
{
      if ($row['Number2']<=0) $row['Number2']=1;
                        if ($row['Number3']<=0) $row['Number3']=1;
                        if ($row['Number4']<=0) $row['Number4']=1;
                        if ($row['Number5']<=0) $row['Number5']=1;
                        if ($row['Number6']<=0) $row['Number6']=1;
    if (($row['FNumber']*$row['Number2']*$row['Number3']*$row['Number4']*$row['Number5']*$row['Number6'])>$row['Number'])
        $row['Number']=$row['FNumber']*$row['Number2']*$row['Number3']*$row['Number4']*$row['Number5']*$row['Number6'];
        
    if ($fehrestquerystr=="")
    $fehrestquerystr="select '$row[Total]' Total,'$row[Price]' Price,'$row[Number]' Number,'$row[Title]' Title
    ,'$row[CostsGroupsTitle]' CostsGroupsTitle,'$row[Code]' Code,'$row[ToolsGroupsCode]' ToolsGroupsCode ";
    else
    $fehrestquerystr.=" union all select '$row[Total]' Total,'$row[Price]' Price,'$row[Number]' Number,'$row[Title]' Title
    ,'$row[CostsGroupsTitle]' CostsGroupsTitle,'$row[Code]' Code,'$row[ToolsGroupsCode]' ToolsGroupsCode ";    
}

$fehrestqueryd=fehrestquery(1,1,$ApplicantMasterIDd,$costpricelistmasterIDd,$cityid15,"");
$result = mysql_query($fehrestqueryd);
$fehrestquerystrd="";
while($row = mysql_fetch_assoc($result))
{
    
                        if ($row['Number2']<=0) $row['Number2']=1;
                        if ($row['Number3']<=0) $row['Number3']=1;
                        if ($row['Number4']<=0) $row['Number4']=1;
                        if ($row['Number5']<=0) $row['Number5']=1;
                        if ($row['Number6']<=0) $row['Number6']=1;
    if (($row['FNumber']*$row['Number2']*$row['Number3']*$row['Number4']*$row['Number5']*$row['Number6'])>$row['Number'])
        $row['Number']=$row['FNumber']*$row['Number2']*$row['Number3']*$row['Number4']*$row['Number5']*$row['Number6'];
    if ($fehrestquerystrd=="")
    $fehrestquerystrd="select '$row[Total]' Total,'$row[Price]' Price,'".round($row['Number'],3)."' Number,'$row[Title]' Title
    ,'$row[CostsGroupsTitle]' CostsGroupsTitle,'$row[Code]' Code,'$row[ToolsGroupsCode]' ToolsGroupsCode ";
    else
    $fehrestquerystrd.=" union all select '$row[Total]' Total,'$row[Price]' Price,'".round($row['Number'],3)."' Number,'$row[Title]' Title
    ,'$row[CostsGroupsTitle]' CostsGroupsTitle,'$row[Code]' Code,'$row[ToolsGroupsCode]' ToolsGroupsCode ";    
}
//print $sql; exit;
   $sql2 = "
select distinct CostsGroupsTitle,Title,Code,Number,Price, Numberop, Priceop from (
SELECT distinct fehrestbaha.CostsGroupsTitle,fehrestbaha.Title,fehrestbaha.Code,
round(fehrestbaha.Number,2) Number,fehrestbaha.Price,round(fehrestbahaop.Number,2) Numberop,fehrestbahaop.Price Priceop FROM 
(SELECT Price,sum(Number)Number,Title,CostsGroupsTitle,Code,ToolsGroupsCode,'$ApplicantMasterID' ApplicantMasterID FROM 
    ($fehrestquerystrd)
 fehr group by Price,Title,CostsGroupsTitle,Code,ToolsGroupsCode)
fehrestbaha
left outer join 
( SELECT Price,sum(Number)Number,Title,CostsGroupsTitle,Code,ToolsGroupsCode FROM 
    ($fehrestquerystr)
 fehr group by Price,Title,CostsGroupsTitle,Code,ToolsGroupsCode)
fehrestbahaop on fehrestbahaop.Code=fehrestbaha.Code and fehrestbahaop.Title=fehrestbaha.Title
WHERE ifnull(fehrestbaha.Number,0)<>ifnull(fehrestbahaop.Number,0) or ifnull(fehrestbaha.Price,0)<>ifnull(fehrestbahaop.Price,0)
union all
SELECT distinct fehrestbahaop.CostsGroupsTitle,fehrestbahaop.Title,fehrestbahaop.Code,
round(fehrestbaha.Number,2) Number,fehrestbaha.Price,round(fehrestbahaop.Number,2) Numberop,fehrestbahaop.Price Priceop FROM 
(SELECT Price,sum(Number)Number,Title,CostsGroupsTitle,Code,ToolsGroupsCode,'$ApplicantMasterIDd' ApplicantMasterID FROM 
    ($fehrestquerystrd)
 fehr group by Price,Title,CostsGroupsTitle,Code,ToolsGroupsCode)
fehrestbaha
right outer join 
( SELECT Price,sum(Number)Number,Title,CostsGroupsTitle,Code,ToolsGroupsCode FROM 
    ($fehrestquerystr)
 fehr group by Price,Title,CostsGroupsTitle,Code,ToolsGroupsCode)
fehrestbahaop on fehrestbahaop.Code=fehrestbaha.Code and fehrestbahaop.Title=fehrestbaha.Title
WHERE ifnull(fehrestbaha.Number,0)<>ifnull(fehrestbahaop.Number,0) or ifnull(fehrestbaha.Price,0)<>ifnull(fehrestbahaop.Price,0)
)view2



order by Code

        
";
//print $sql2;exit;
	?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print $TITLE; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
	function setpagereak(id)
{
    alert(document.getElementById(id).className);
    if (document.getElementById(id).className=="page")
        document.getElementById(id).className = "";
    else
        document.getElementById(id).className = "page";
    
}
    </script>
    <!-- /scripts -->
</head>
<body>

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
                   <tbody>
                   
                        <h1 align="center"> <?php print $titr.' '.$ApplicantName; ?> </h1>
                            
                   <?php
                
         
        
           					try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }


                           $cnt=0;
        $rown=0;
        $sum=0;
        print " <table  width='80%' align='right'>
		
						<tr>
					 <th align='center'  ></th>
                        	<th align='center' colspan=6 style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:150%;font-weight: bold;font-family:'B Nazanin';\">تغییرات لوازم</th>
                        </tr>

					
						<tr>
                        	
                        <th align='center'  ></th>
                        	<th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\" >ردیف</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\">شرح </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">واحد</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">تغییر</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">قیمت</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">جمع</th>
                        </tr>";
        $sumt=0;
        while($row = mysql_fetch_assoc($result))
        {
            if ($row['Price']>0)
            $Price=number_format($row['Price']);
            else
            $Price=number_format($row['Price2']);
            
            $sumtrow=$Price*$row['dif'];
            $sumt+=$sumtrow;
            $MarksTitle=$row['MarksTitle'];
            $utitle = trim($row['unitsTitle']);
            $gadget3Title = $row['gadget3Title']." (".$row['marksTitle'].")";
            if ($row['dif']>0) {$Number = '+'.$row['dif'];$rtl="left";}
            else {$Number = ($row['dif']);$rtl="right";}
            $rown++;
			
              print "     <tr>
			  
                              <td></td>
                             <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$rown&nbsp;$sumt</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:130%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$gadget3Title&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$utitle&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:$rtl;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Number&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Price&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$sumtrow&nbsp;</td>
                        </tr>";

        }
        
 if ($login_RolesID==1) print " 
 		
            <tr>
             <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">			 </td>
              <td colspan='4' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع اختلاف &nbsp;</td>
              <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($sumt)."&nbsp;</td>
            </tr>
            <tr>
             <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">
			 </td>
             <td colspan='4' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع اختلاف لوازم با احتساب ارزش افزوده،تخفیفات،...&nbsp;</td>
             <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($Totlainvoicedif)."&nbsp;</td>
            </tr>
			<tr></tr>
			
			";
			
       print "</table>";      
            
              			try 
							  {		
								$result = mysql_query($sql2);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                           $cnt=0;
        $rown=0;
        $sum=0;
        print "<table id='sh_invm$InvoiceMasterIDold' width='80%' align='center'>
				<tr><td></td><th align='center' colspan=9> &nbsp;</th></tr>
				<tr>
					 <td></td>
                        	<th align='center' colspan=9 style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:150%;font-weight: bold;font-family:'B Nazanin';\">تغییرات فهرست بها</th>
                </tr>

				<tr>
                        	<th align='center'  ></th>
                        	<th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\" >ردیف</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\">کد </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\">فصل </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">شرح</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> تعداد $tit1</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> مبلغ $tit1 </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">تعداد $tit2</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> مبلغ $tit2 </th>
                          <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">اختلاف </th>
                </tr>";
		$sumejra=0;$rowsumejra=0;				
        while($row = mysql_fetch_assoc($result))
        {
			if ($row['Priceop']*$row['Numberop']==0 && $row['Price']*$row['Number']==0) continue;
            $rown++;
            $rowsumejra=$row['Priceop']*$row['Numberop']-$row['Price']*$row['Number'];
			
			$sumejra+=$rowsumejra;
             print "     <tr>
			 
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$rown&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:130%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$row[Code]&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:130%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$row[CostsGroupsTitle]&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsmark;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$row[Title]&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".round($row['Number'],3)."&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$row[Price]&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".round($row['Numberop'],3)."&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$row[Priceop]&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".round($rowsumejra)."&nbsp;</td>
                        </tr>";

        }
		
 if ($login_RolesID==1)   print "    
						<tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">							</td>
                            <td colspan='7' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع اختلاف فهرست بها بدون ضرایب&nbsp;</td>
                            <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($sumejra)."&nbsp;</td>
                        </tr>
						 <tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">							</td>
                            <td colspan='7' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع اختلاف فهرست بها با ضرایب&nbsp;</td>
                            <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($LastFehrestbahasdif)."&nbsp;</td>
                        </tr>
						";

        
		
            print " </table>";  
			
			
			
	print "<table width='80%' align='center'>
				<tr><td></td><th align='center' colspan=9> &nbsp;</th></tr>
	
				<tr>
					 <td></td>
                        	<th align='center' colspan=5 style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:150%;font-weight: bold;font-family:'B Nazanin';\">تغییرات  سایر هزینه های و مجموع</th>
                </tr>

						<tr>
						<td></td>
                    	<td colspan='2' align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> شرح </td>
                      	<td align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> مبلغ $tit1 </td>
                        <td align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\"> مبلغ $tit2 </td>
                        <td colspan='2' align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">اختلاف </td>
                        </tr>
						
						<tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">							</td>
                            <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع اختلاف سایر هزینه ها&nbsp;</td>
        					<td colspan='1' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($othercostsd)."&nbsp;</td>
        					<td colspan='1' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($othercosts)."&nbsp;</td>
        					<td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($othercostsdif)."&nbsp;</td>
                        </tr>
						
						<tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">							</td>
                            <td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;جمع کل اختلاف&nbsp;</td>
        					<td colspan='1' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($LastTotald)."&nbsp;</td>
        					<td colspan='1' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($LastTotal)."&nbsp;</td>
        					<td colspan='2' style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;".number_format($LastTotaldif)."&nbsp;</td>
                        </tr>
						
						
						";
				
				
			
            print " </table>";  
	               
?>
                    </tbody>
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