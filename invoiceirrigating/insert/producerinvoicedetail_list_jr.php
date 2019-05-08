<?php 

/*

insert/producerinvoicedetail_list_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/producerinvoicedetail_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php




function ConvertFileToArray($FileName='')//تابع تبدیل فایل تنظیمات به آرایه
  {
  	$ArrayName='';
	$fcontents = file ($FileName);
  	while (list ($line_num, $line) = each ($fcontents))
	{
  		 $lineKey=htmlspecialchars ($line);
  		 $Key =substr ($lineKey,0,strlen($line)-strlen(strstr ($lineKey, '=')));
  		 $Value= substr ($lineKey,strlen($line)-strlen(strstr ($lineKey, '='))+1, strlen(strstr ($lineKey, '='))-1);
  		 $ArrayName[$Key]=$Value;
    }
    return($ArrayName);
  }


  
$Array=ConvertFileToArray($_SERVER['DOCUMENT_ROOT'].'/cfg.txt');
	$home_path_iri=trim("$Array[home_path_iri]");
    

   $font_style_string="";

	$rowNumber=$_POST['rowNumber'];//شماره ردیف
	$Tabindex=$_POST['Tabindex'];//اندیس تب
	$masterProducersID=$_POST['masterProducersID'];//تولید کننده ردیف کالا
	$selectedmarksID=$_POST['selectedmarksID'];//مارک
    $selectedgadget3ID=$_POST['selectedgadget3ID'];//ابزار سطح 3
    $selectedgadget2ID=$_POST['selectedgadget2ID'];//ابزار سطح 2
    
    $selectedProducersID=$_POST['selectedProducersID'];//تولید کننده کالا
    $primaryInvoiceMasterID=$_POST['primaryInvoiceMasterID'];//پیش فاکتور
    
    
    
    
    $con2="";
    $con3="";
    $condition="";
    $strgadget3synthetic="";
    
    $pricelessinvoice=0;
        
        
    if ($pricelessinvoice==0)
        $strgadget3=' gadget3 ';
    else
    {
         /*
    applicantmaster جدول مشخصات طرح
    ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
    freestateid شناسه مرحله آزادسازی
    yearcost.Value سال فهرست بهای آبیاری تحت فشار
    applicantstatestitle عنوان وضعیت طرح
    applicantstatesID شناسه وضعیت طرح
    errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
    RoleID نقش کاربر ثبت کننده جدول زمانبندی
    emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
    ostancityname نام استان طرح
    shahrcityname نام شهر طرح
    bakhshcityname نام بخش طرح
    privatetitle شخصی بودن طرح
    prjtypetitle عنوان نوع پروژه
    prjtypeid شناسه نوع پروژه
    RolesID نقش کاربر
    applicantstatesID شناسه وضعیت طرح
    applicantstates جدول تغییر وضعیت های طرح
    costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
    costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
    year جدول سال ها
    YearID شناسه سال طرح
    tax_tbcity7digit جدول شهرهای مختلف
    applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
    freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
    applicanttiming جدول زمانبندی اجرای طرح
    
    -- private یکی از ویژگی های طرح می باشد که در صورتی که شرکت ها بخواهند طرح تستی و آزمایشی داشته باشند آنرا شخصی می کنند								

    -- CostPriceListMasterID شناسه سال هزینه های اجرایی طرح 
    
    --  creditsourceID شناسه جدول منبع تامین اعتبار
    
    -- شناسه مشاور بازبین
    
    
    ,''  appstatesID -- وضعیت طرح
    ,'' ApplicantName -- عنوان پروژه
    ,'$login_DesignerCoID' DesignerCoIDnazer -- شرکت مهندسین مشاور
    
    
    -- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
    -- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
    -- این جدول دارای ستون های ارتباطی زیر می باشد
    -- ApplicantMasterID شناسه طرح مطالعاتی
    -- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
    -- ApplicantMasterIDsurat شناسه طرح صورت وضعیت
    
    
    
    -- clerk جدول کاربران
    -- costpricelistmaster هزینه های اجرایی طرح ها
    -- year جدول سال
    -- costpricelistmaster هزینه های اجرایی طرح ها
    -- designerco جدول شرکت های طراح
    -- designer جدول طراحان
    -- designsystemgroups سیستم آبیاری
    
    -- لیست عناوین پیش فاکتور
    inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid
    -- جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
    -- ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
    -- gadget3ID شناسه سطح 3 ابزار
    -- ProducersID شناسه جدول تولیدکننده
    -- MarksID شناسه جدول مارک
    -- جدول سطح سوم لوازم طرح
    -- جدول سطح دوم لوازم طرح
    -- جدول واحدهای اندازه گیری کالا
    -- جدول مارک های کالا
    --  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
    -- جدول عملگر های تشکیل دهنده نام کالا
    -- مشخصه 2 کالا ها
    -- مشخصه 3 کالا ها
    --  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
    --  نوع مواد ابزار مانند چدنی، پلی اتیلن و
    -- جدول تولیدکننده کالا
    
    */ 
        $strgadget3synthetic="select gadget3.* from gadget3 where gadget3.gadget3ID in 
                                    (
                                    select gadget3ID from gadget3synthetic 
                                    where gadget3ID not in (
                                                            select gadget3ID from gadget3synthetic 
                                                            where gadget3syntheticID not in ( 
                                                            select gadget3syntheticID from gadget3synthetic
                                                            inner join toolsmarks on toolsmarks.toolsmarksid=gadget3synthetic.ToolsMarksIDpriceref and ifnull(toolsmarks.hide,0)=0
                                                            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                                            inner join pricelistdetail on   pricelistdetail.ToolsMarksID=
                                                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) and pricelistdetail.Price>0
                                                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]' 
                                                                                            )
                                                           )                     
                                    )";
        $strgadget3="($strgadget3synthetic union all
        select * from gadget3 where gadget2id in (202,376) union all 
        select gadget3.* from gadget3
                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and ifnull(toolsmarks.hide,0)=0
                            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                            inner join pricelistdetail on   
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]') gadget3";
                            
        }
    if ($operatorProducersID!=$masterProducersID)
    {
        $condition.=" inner join toolsmarks toolsmarksp on gadget3.gadget3ID=toolsmarksp.gadget3ID and ifnull(toolsmarksp.hide,0)=0 and toolsmarksp.ProducersID='$masterProducersID' ";
        $con2=" and ifnull(toolsmarks.hide,0)=0 and toolsmarks.producersID='$masterProducersID'";
        $con3=" and ifnull(toolsmarks.hide,0)=0 and producers.producersID='$masterProducersID' ";
        
        if ($pricelessinvoice==0)
            $strgadget3=' gadget3 ';
        else
         {
            $strgadget3synthetic="select gadget3.* from gadget3 
                                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$masterProducersID'
                                            where gadget3.gadget3ID in 
                                            (
                                            select gadget3ID from gadget3synthetic 
                                            where gadget3ID not in (
                                                                    select gadget3ID from gadget3synthetic 
                                                                    where gadget3syntheticID not in ( 
                                                                    select gadget3syntheticID from gadget3synthetic
                                                                    inner join toolsmarks on toolsmarks.toolsmarksid=gadget3synthetic.ToolsMarksIDpriceref 
                                                                    left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                                                    inner join pricelistdetail on   pricelistdetail.ToolsMarksID=
                                                                    (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) and pricelistdetail.Price>0
                                                                    inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]' 
                                                                                                    )
                                                                   )                     
                                            )";
            $strgadget3="($strgadget3synthetic union all
                                            
            select * from gadget3 where gadget2id in (202,376) union all 
            select gadget3.* from gadget3
                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$masterProducersID'
                            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                            inner join pricelistdetail on   
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]') gadget3";
        }
        
    }
    else if ($selectedProducersID>0)
    {
        $condition.=" inner join toolsmarks toolsmarksp on gadget3.gadget3ID=toolsmarksp.gadget3ID and ifnull(toolsmarksp.hide,0)=0 and toolsmarksp.producersID='$selectedProducersID' ";
        $con2=" and ifnull(toolsmarks.hide,0)=0 and toolsmarks.producersID='$selectedProducersID'";
        $con3=" and ifnull(toolsmarks.hide,0)=0 and producers.producersID='$selectedProducersID' ";    
        
        if ($pricelessinvoice==0)
            $strgadget3=' gadget3 ';
        else
        {
            $strgadget3synthetic=" select gadget3.* from gadget3 
                                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$selectedProducersID'
                                            where gadget3.gadget3ID in 
                                            (
                                            select gadget3ID from gadget3synthetic 
                                            where gadget3ID not in (
                                                                    select gadget3ID from gadget3synthetic 
                                                                    where gadget3syntheticID not in ( 
                                                                    select gadget3syntheticID from gadget3synthetic
                                                                    inner join toolsmarks on toolsmarks.toolsmarksid=gadget3synthetic.ToolsMarksIDpriceref 
                                                                    left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                                                    inner join pricelistdetail on   pricelistdetail.ToolsMarksID=
                                                                    (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) and pricelistdetail.Price>0
                                                                    inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]' 
                                                                                                    )
                                                                   )                     
                                            )";
            $strgadget3="($strgadget3synthetic union all
                                            
            select * from gadget3 where gadget2id in (202,376) union all 
            select gadget3.* from gadget3
                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$selectedProducersID'
                            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                            inner join pricelistdetail on   
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]') gadget3";
        }
    }
    
    $state=0;
    if ($selectedProducersID>0)
        $state+=1;
    
    if ($selectedgadget2ID>0)
        $state+=2;
        
    if ($selectedmarksID>0)
        $state+=4;
        
    if ($selectedgadget3ID>0)
        $state+=8;
        
    switch ($state) {
    case 0:
    if ($pricelessinvoice==0)
            $query1="Select '0' As _value, ' ' As _key Union All 
            select distinct producersID as _value,producers.Title as _key from producers
             where 1=1 $con3
             order by _key  COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) union all 
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id union all 
            
            
            
            
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID $con2
        inner join marks on marks.marksID=toolsmarks.marksID    
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from $strgadget3
                                        
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0
        
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                                        
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm 
        ";
        
        break;
    case 1:
        $query1="Select '0' As _value, ' ' As _key Union All select  distinct  producersID as _value,producers.Title as _key from producers
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID $con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from $strgadget3
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0
                
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 2:
        $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join $strgadget3 on gadget3.gadget2ID='$selectedgadget2ID'
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.producersID=producers.producersID
        
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID  and gadget2.gadget2ID='$selectedgadget2ID'
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID' 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID $con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID' and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";

        break;
    case 3:
        $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join $strgadget3 on gadget3.gadget2ID='$selectedgadget2ID'
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.producersID=producers.producersID
        
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID   and gadget2.gadget2ID='$selectedgadget2ID'
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID'
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID $con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID' and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 4:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID 
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID and marks.marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 5:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID 
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID  
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID and marks.marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and IsCost=0 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
    
        break;
    case 6:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join $strgadget3 on gadget3.gadget2ID='$selectedgadget2ID'
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID and toolsmarks.gadget3ID=gadget3.gadget3ID
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID  and gadget2.gadget2ID='$selectedgadget2ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID'
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID and marks.marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID'  and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        break;
    case 7:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join $strgadget3 on gadget3.gadget2ID='$selectedgadget2ID'
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID and toolsmarks.gadget3ID=gadget3.gadget3ID
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID    and gadget2.gadget2ID='$selectedgadget2ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID    and gadget2.gadget2ID='$selectedgadget2ID'
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID and marks.marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID    and gadget2.gadget2ID='$selectedgadget2ID'  and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        break;
    case 8:
    if ($pricelessinvoice==0)
            $query1="Select '0' As _value, ' ' As _key Union All 
        select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join  toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
        
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
             
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        $condition order by _key COLLATE utf8_persian_ci";
            

    if ($pricelessinvoice==0)
        $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID  and gadget3.gadget3ID='$selectedgadget3ID'$con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
        else
            $query3="Select '0' As _value, ' ' As _key Union All  
            
            select distinct marks.marksID as _value,marks.Title as _key from marks
            inner join toolsmarks on toolsmarks.marksID=marks.marksID $con2
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
           
           select distinct marks.marksID as _value,marks.Title as _key from marks
            inner join toolsmarks on toolsmarks.marksID=marks.marksID $con2
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
        select distinct marks.marksID as _value,marks.Title as _key from $strgadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID  and gadget3.gadget3ID='$selectedgadget3ID'$con2
        left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
           inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";  

            
    $query4="select distinct gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from $strgadget3
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition 
        where gadget3.gadget3ID='$selectedgadget3ID'
         order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 9:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join  toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
        
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
                 
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID    and gadget3.gadget3ID='$selectedgadget3ID'
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 10:
    if ($pricelessinvoice==0)
            $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join  toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
 			select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
             
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID  and gadget2.gadget2ID='$selectedgadget2ID' and gadget3.gadget3ID='$selectedgadget3ID'
        $condition order by _key COLLATE utf8_persian_ci";

    if ($pricelessinvoice==0)
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID'
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
        else
            $query3="Select '0' As _value, ' ' As _key Union All  
            
        select distinct marks.marksID as _value,marks.Title as _key from marks
        inner join toolsmarks on toolsmarks.marksID=marks.marksID $con2
        inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
        
        select distinct marks.marksID as _value,marks.Title as _key from marks
        inner join toolsmarks on toolsmarks.marksID=marks.marksID $con2
        inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID'
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";  
        
            

            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID'  and IsCost=0
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
         order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";

        break;
    case 11:
    if ($pricelessinvoice==0)
            $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join  toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
 			select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
             
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID   and gadget2.gadget2ID='$selectedgadget2ID'
        order by _key COLLATE utf8_persian_ci";
    
    
        if ($pricelessinvoice==0)
        $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID' 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";
        else
            $query3="Select '0' As _value, ' ' As _key Union All  
            select distinct marks.marksID as _value,marks.Title as _key from marks
            inner join toolsmarks on toolsmarks.marksID=marks.marksID and toolsmarks.producersID=$selectedProducersID $con2
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            select distinct marks.marksID as _value,marks.Title as _key from marks
            inner join toolsmarks on toolsmarks.marksID=marks.marksID and toolsmarks.producersID=$selectedProducersID $con2
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID' 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key COLLATE utf8_persian_ci";  
                       
            
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 12:
    if ($pricelessinvoice==0)
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID and toolsmarks.gadget3ID='$selectedgadget3ID'
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
 			select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID and toolsmarks.MarksID='$selectedmarksID' $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
 			select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID and toolsmarks.MarksID='$selectedmarksID' $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID and toolsmarks.gadget3ID='$selectedgadget3ID'
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
                 

    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from marks where marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'  and gadget3.gadget3ID='$selectedgadget3ID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        
        break;
    case 13:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
             inner join toolsmarks on toolsmarks.MarksID='$selectedmarksID' and toolsmarks.producersID=producers.producersID and toolsmarks.gadget3ID='$selectedgadget3ID'
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID    and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        $condition order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID$con2
        inner join marks on marks.marksID=toolsmarks.marksID and marks.marksID='$selectedmarksID'
        $condition
        order by _key COLLATE utf8_persian_ci";
            
    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID   and IsCost=0
        inner join $strgadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and marksID='$selectedmarksID'$con2
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        $condition order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
    
        break;
    case 14:
    if ($pricelessinvoice==0)
            $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key from producers
        inner join  toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
        and toolsmarks.marksID=$selectedmarksID
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
        else
            $query1="
            Select '0' As _value, ' ' As _key Union All 
            
 			select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.producersID=producers.producersID $con3
            inner join ($strgadget3synthetic) gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget3id='$selectedgadget3ID' union all 
            
            
            select distinct producers.producersID as _value,producers.Title as _key from producers
            inner join toolsmarks on toolsmarks.gadget3ID='$selectedgadget3ID' and toolsmarks.producersID=producers.producersID
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            inner join pricelistdetail on 
                            pricelistdetail.ToolsMarksID=
                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
                             and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and 
                            pricelistmaster.pricelistmasterid='$_POST[PriceListMasterID]'
                            
             where 1=1 $con3
             order by _key COLLATE utf8_persian_ci";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key 
        from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID' 
        order by _key COLLATE utf8_persian_ci";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID  and gadget2.gadget2ID='$selectedgadget2ID'
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID $con2
        inner join marks on marks.marksID=toolsmarks.marksID
        $condition
        order by _key  COLLATE utf8_persian_ci";
            



    $query4="select distinct  gadget3.gadget3ID as _value,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID  and gadget3.gadget3ID='$selectedgadget3ID'
        
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm ";
        break;
    case 15:
    $query1="Select '0' As _value, ' ' As _key Union All select distinct producers.producersID as _value,producers.Title as _key 
             from producers where producersID=$selectedProducersID ";
    
    $query2="Select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID and gadget2.gadget2ID='$selectedgadget2ID'";
            
    $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from marks where marksID='$selectedmarksID' ";
            
    $query4="select distinct gadget3.gadget3ID as _value,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
        from gadget1
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID and gadget3.gadget3ID='$selectedgadget3ID'
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        ";
        break;
}    
                
      
                                                  
        $cnt1=0;                                     
        $cnt2=0;                                     
        $cnt3=0;                                     
        $cnt4=0;                                     
        $v1=0;                                     
        $v2=0;                                     
        $v3=0;                                     
        $v4=0;                                   
        $key1=0;                                     
        $key2=0;                                     
        $key3=0;                                     
        $key4=0;
   
        $query=$query1;
                                
	   $result = mysql_query($query);
       $width=95;
       $width="style='width: ".$width."px'";         		
	   $selectstr1="<select $font_style_string $width Tabindex=\"".($_POST['Tabindex']+1)."\" name='ProducersID$rowNumber'  onmouseover=\"Tip('$selectedTitle')\" id='ProducersID$rowNumber' \" onchange = \"FilterComboboxes('$rowNumber', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',0,this.tabIndex);\" >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		if ($selectedProducersID==$row['_value'])
              $options1.="<option  value='$row[_value]' selected=\"selected\"> $row[_key] </option>";
            else
              $options1.="<option  value='$row[_value]'> $row[_key] </option>";  
            $cnt1++;$v1=$row['_value'];$key1=$row['_key'];
	    }
        if ($cnt1==2)
        {
            $options1="<option  value='0'>  </option><option  value='$v1' selected=\"selected\"> $key1 </option>";
            $selectedProducersID=$v1;
        }
        $selectstr1.=$options1."</select>";
       
       
        //$selectstr1=$query;
       
       
       
       
        $query=$query2 ;
                                
	  
	   
				  	try 
								  {		
									 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

       $width=1;
       $width="style='width: ".$width."px'";         		
	   $selectstr2="<select $font_style_string $width Tabindex=\"0\" name='gadget2ID$rowNumber'  onmouseover=\"Tip('$selectedTitle')\" id='gadget2ID$rowNumber' \" onchange = \"FilterComboboxes('$rowNumber', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',1,0);\" >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		if ($selectedgadget2ID==$row['_value'])
              $options2.="<option  value='$row[_value]' selected=\"selected\"> $row[_key] </option>";
            else
              $options2.="<option  value='$row[_value]'> $row[_key] </option>";  
              
            $cnt2++;$v2=$row['_value'];$key2=$row['_key'];
	    }
        if ($cnt2==2)
            $options2="<option  value='0'>  </option><option  value='$v2' selected=\"selected\"> $key2 </option>";
        $selectstr2.=$options2."</select>";
       
       
       
       
        $query=$query3;
                                
	  			  	try 
								  {		
									 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

       $width=75;
       $width="style='width: ".$width."px'";         		
	   $selectstr3="<select $font_style_string $width Tabindex=\"".($_POST['Tabindex']+2)."\" name='marksID$rowNumber'  onmouseover=\"Tip('$selectedTitle')\" id='marksID$rowNumber' \" onchange = \"FilterComboboxes('$rowNumber', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',2,0);\" >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		if ($selectedmarksID==$row['_value'])
              $options3.="<option  value='$row[_value]' selected=\"selected\"> $row[_key] </option>";
            else
              $options3.="<option  value='$row[_value]'> $row[_key] </option>";  
              
            $cnt3++;$v3=$row['_value'];$key3=$row['_key'];
	    }
        if ($cnt3==2)
        {   
            $options3="<option  value='0'>  </option><option  value='$v3' selected=\"selected\"> $key3 </option>";
            $selectedmarksID=$v3;
        }
        $selectstr3=$selectstr3.$options3."</select>";
        
              
       
       
       $healthy = array("í", "ß");
       $yummy   = array("í", "˜");

        $query=$query4;
                                
	   $result = mysql_query($query);
       $width=1;
       $width="style='width: ".$width."px'";         		
	   $selectstr4="<select $font_style_string $width  name='gadget3ID$rowNumber'  onmouseover=\"Tip('$selectedTitle')\" id='gadget3ID$rowNumber' \" onchange = \"FilterComboboxes('$rowNumber', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',3,0);\" >";
        $options4="<option  value='0'></option>";
        while($row = mysql_fetch_assoc($result))
	    {
	  		if ($selectedgadget3ID==$row['_value'])
              $options4.="<option  value='$row[_value]' selected=\"selected\">".str_replace($healthy, $yummy,$row['_key'])."</option>";
            else
              $options4.="<option  value='$row[_value]'>".str_replace($healthy, $yummy,$row['_key'])."</option>"; 
              
            $cnt4++;$v4=$row['_value'];$key4=$row['_key'];
	    }
        //$selectedgadget3ID=0;
        if ($cnt4==2)
        {
            $options4.="<option  value='$v4' selected=\"selected\">$key4</option>";
            $selectedgadget3ID=$v4;
        }
        $selectstr4.=$options4."</select>";
       



       
       $Price=0;
      
      $ToolsMarksID=0;
      if (($selectedmarksID>0) &&($selectedgadget3ID>0) && ($selectedProducersID>0))
      { 
            $ToolsMarksIDquery="Select ToolsMarksID from toolsmarks
              where ProducersID='$selectedProducersID' and gadget3ID='$selectedgadget3ID' and MarksID='$selectedmarksID' ";
                                    
            $result = mysql_query($ToolsMarksIDquery);  
            $row = mysql_fetch_assoc($result); 		
            $ToolsMarksID=($row['ToolsMarksID']); 
       }    
       $condlimited='';
      $limited = array("9");if ( in_array($_POST['login_RolesID'], $limited)) $condlimited=' and pfd=1 '; 
    	
        
        if ($ToolsMarksID>0)
        {
            //mysql_query("update clerk set ClerkActivity=ifnull(ClerkActivity,0)+1 where clerkid='$_POST[login_userid]'"); 
        
            $queryp="Select case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else  pricelistdetail.Price end Price 
            from toolsmarks
            INNER JOIN gadget3 ON gadget3.gadget3id = toolsmarks.Gadget3ID
            inner join primaryinvoicemaster on primaryinvoicemaster.primaryInvoiceMasterID='$primaryInvoiceMasterID'
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            
            left outer join pricelistdetail on pricelistdetail.PriceListMasterID='$_POST[PriceListMasterID]' 
            and pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
             
            left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=(select invoiceDate from primaryinvoicemaster where primaryInvoiceMasterID ='$primaryInvoiceMasterID') $condlimited) and pipeprice.ProducersID=toolsmarks.ProducersID
            where toolsmarks.ToolsMarksID='$ToolsMarksID'";                         
            
           
			  	try 
								  {		
									  $result = mysql_query($queryp); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
			
            $row = mysql_fetch_assoc($result); 			
            $Price=number_format($row['Price']); 
            
            
            $query="Select sum(pricelistdetail.Price*gadget3synthetic.Num) Price from gadget3synthetic
            left outer join toolspref on toolspref.PriceListMasterID='$_POST[PriceListMasterID]' and toolspref.ToolsMarksID=gadget3synthetic.ToolsMarksIDpriceref
            
            left outer join pricelistdetail on pricelistdetail.PriceListMasterID='$_POST[PriceListMasterID]' 
            and pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then gadget3synthetic.ToolsMarksIDpriceref else toolspref.ToolsMarksIDpriceref end)
            where  gadget3synthetic.gadget3ID='$selectedgadget3ID'
             ";                         
           
				  	try 
								  {		
									   $result = mysql_query($query);  
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
	
            $row = mysql_fetch_assoc($result); 	
            if ($row['Price']>0)		
            $Price=number_format($row['Price']); 
            
            
            $queryprice=$queryp; 
            
            
      }    
           
       
        $Utitle='';
        if ($selectedgadget3ID>0)
        { 
       
            $query="Select units.title from units
                    inner join gadget3 on units.unitsID=gadget3.unitsID and gadget3ID='$selectedgadget3ID'";
                                    
           		  	try 
								  {		
									   $result = mysql_query($query);  
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
	
           $row = mysql_fetch_assoc($result); 		
    	   
            
           $Utitle=$row['title']; 
           //$Price=$query; 
       }    
           

       
       $temp_array = array('val0' => $selectstr1, 'val1' => $selectstr2, 'val2' => $selectstr3, 'val3' => $selectstr4,'val4' =>  $Price,
       'val5' => $Utitle
       ,'val6' =>  $ToolsMarksID,'val7' =>  $selectedmarksID,'val8' =>  $selectedgadget3ID,'val9' =>  $selectedProducersID);
       //$temp_array = array('val0' => $query1, 'val1' => $query2, 'val2' => $query3, 'val3' => $query4);
        
        
        
        echo json_encode($temp_array);
		exit();
    
    
    			
	
   
   
   
			
			
		
	

?>



