<?php 
/*

insert/summaryinvoiceprint.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/producerinvoicedetail_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
//supervisorcoderrquirement جدول تنظیمات پیکربندی سیستم
 $query = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='operatorProducersID' and ostan='$login_ostanId'";
	
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
    $operatorProducersID=$row['ValueInt'];
    
    if ($_POST['primaryInvoiceMasterID']>0) $primaryInvoiceMasterID=$_POST['primaryInvoiceMasterID']; else
 $primaryInvoiceMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        /*
       primaryinvoicemaster  پیش فاکتور تولید کننده
       Discont تخفیف
       TransportCost هزینه حمل
       primaryInvoiceMasterID شناسه پیش فاکتور
       producers تولیدکننده
       operatorcoID پیمانکار
       */ 
 $query = "SELECT primaryinvoicemaster.operatorcoID,primaryinvoicemaster.ProducersID,primaryinvoicemaster.TransportCost,
    primaryinvoicemaster.Discont,primaryinvoicemaster.invoiceDate,primaryinvoicemaster.Rowcnt,primaryinvoicemaster.Serial,primaryinvoicemaster.Title
    ,producers.Title as PTitle,primaryinvoicemaster.Description,PriceListMasterID FROM primaryinvoicemaster 
inner join producers on producers.ProducersID=primaryinvoicemaster.ProducersID

        where  primaryinvoicemaster.primaryInvoiceMasterID ='$primaryInvoiceMasterID' " ;
							try 
								  {		
									  	  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
    $resquery = mysql_fetch_assoc($result);	
print $query;

   $PriceListMasterID=$resquery['PriceListMasterID'];
    $operatorcoID = $resquery['operatorcoID'];
    $masterProducersID = $resquery['ProducersID'];
    $TransportCost = $resquery['TransportCost'];
    $Discont = $resquery['Discont'];                        
    $np = $resquery['Rowcnt'];
    if ($np<1) $np=1;
    
    $Serial = $resquery['Serial'];
    $Title = $resquery['Title'];
    $PTitle = $resquery['PTitle'];
    $Description = $resquery['Description'];
    $invoiceDate = $resquery['invoiceDate'];
                        
    
        if (strlen($resquery['invoiceDate'])>0)
    {
        $primaryinvoiceYear = substr($resquery['invoiceDate'],0,4);
        $query = "SELECT taxpercent.value FROM taxpercent 
        inner join year on year.YearID=taxpercent.YearID
        where  year.Value = '" . $primaryinvoiceYear."'" ;
        //print $query;
        
        						try 
								  {		
									  	  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
        $resquery = mysql_fetch_assoc($result);
        $TAXPercent = $resquery['value'];
        
    }
    
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
    $sql = "
            SELECT primaryinvoicedetail.primaryinvoiceDetailID,primaryinvoicedetail.ToolsMarksID,
            gadget3.Code,ifnull(gadget3.gadget3ID,0) gadget3ID,ifnull(gadget2.gadget2ID,0) gadget2ID,ifnull(toolsmarks.ProducersID,0) ProducersID,ifnull(toolsmarks.marksID,0) marksID,units.
        title utitle,primaryinvoicedetail.Number,primaryinvoicedetail.Description,
        
        case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else pricelistdetail.Price end Price
        
        FROM primaryinvoicedetail 
        inner join primaryinvoicemaster on primaryinvoicemaster.primaryInvoiceMasterID=primaryinvoicedetail.primaryInvoiceMasterID
        left outer join toolsmarks on toolsmarks.ToolsMarksID=primaryinvoicedetail.ToolsMarksID
        left outer join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        left outer join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        left outer join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID
        left outer join units on gadget3.unitsID=units.unitsID
        left outer join pricelistmaster on pricelistmaster.PriceListMasterID='$PriceListMasterID'
        left outer join pricelistdetail on  pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID and 
                                            pricelistdetail.ToolsMarksID=primaryinvoicedetail.ToolsMarksID 
        left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  
        Date<=(select invoiceDate from primaryinvoicemaster where primaryInvoiceMasterID =$primaryInvoiceMasterID)) and toolsmarks.ProducersID=pipeprice.ProducersID
                                                
        where  primaryinvoicedetail.primaryInvoiceMasterID ='$primaryInvoiceMasterID'
        ORDER BY primaryinvoicedetail.primaryinvoiceDetailID;";
    


 
						try 
								  {		
									  	     $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 



?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	
    <style>
    p.page { page-break-after: always; }


.f1_font{
	border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                       
}
.f2_font{
	border:0px solid black;width: 10%;                        
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
.f12_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f13_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f14_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
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
    background-color:#b0eab9;width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
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
    width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f27_font{
    width: 80px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f28_font{
    width: 200px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';

}
.f29_font{
    width: 120px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
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
.f43_font{
    border-left: 1px solid black;border-color:#0000ff #0000ff;
}
.f44_font{
    border:0px solid black;background-color:#ffff00;border-color:#0000ff #0000ff;text-align:right;width: 30px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f45_font{
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

      </style>
	<!-- scripts -->


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
       
              
  <?php
   while(1){
                            $primaryinvoiceDetailID = 0;
                            $Code =  '';
                            $gadget3ID =  0;
                            $gadget2ID =  0;
                            $ProducersID =  0;
                            $ToolsMarksID =0;
                            $marksID =  0;
                            $utitle =  '';
                            $Number =  '';
                            $Price =  '';
                            $SumPrice =  '';
                            $Description =  '';
                            $IDgadget3ID='';
                        if ($result)    
                        $row = mysql_fetch_assoc($result);
                        if ($row)
                        {
                            $primaryinvoiceDetailID = $row['primaryinvoiceDetailID'];
                            $ToolsMarksID = $row['ToolsMarksID'];
                            
                            $Code = $row['Code'];
                            $gadget3ID = $row['gadget3ID'];
                            $gadget2ID = $row['gadget2ID'];
                            $ProducersID = $row['ProducersID'];
                            $marksID = $row['marksID'];
                            $utitle = $row['utitle'];
                            $Number = number_format($row['Number'], 0, '', '');
                            $Price = number_format($row['Price']);
                            $SumPrice = number_format($row['Number']*$row['Price']);
                            $Description = $row['Description'];
                            $sum+=$row['Number']*$row['Price'];
                            
                            
                            
                            $query="select producersID as _value,producers.Title as _key from producers
                                where ProducersID='$ProducersID'
                                order by _key";
                            $IDProducersID = get_key_value_from_query_into_array($query);
                            
                            
                            $query="select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
                                    inner join gadget3 on gadget3.gadget2ID='$gadget2ID'
                                    order by _key";
                            $IDgadget2ID = get_key_value_from_query_into_array($query);                            
                            
                            $query="select marks.marksID as _value,marks.Title as _key from marks where marksID='$marksID'
                                 order by _key COLLATE utf8_persian_ci";
                                    
                            $IDmarksID = get_key_value_from_query_into_array($query);   
                            
                            
                            
                            
                            
                            
                            
                            $query="select gadget3.gadget3ID as _value,
                                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key from gadget3 
                                    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
                                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    left outer join operator on operator.operatorID=gadget3.operatorID
                                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid

                                    where gadget3.gadget3ID='$gadget3ID'
                                    order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title";
                                    $IDgadget3ID = get_key_value_from_query_into_array($query);
                                
                                
                        }
                        else
                        {
                           $IDgadget3ID = $allIDgadget3ID;
                        }
                        
                        if ($ProducersID==0)
                            $ProducersID=$selectedPID ;
                            
                            
                        if ($cnt>=$np) 
                        break;
                        $cnt++;
                        
                        $rown++;
                        
                               
?>
                        <tr>
                        <td >
                            <div   id="divprimaryinvoiceDetailID<?php echo $cnt; ?>"   style='visibility: hidden;width:1px;'>
                            <input name="primaryinvoiceDetailID<?php echo $cnt; ?>" class="textbox" id="primaryinvoiceDetailID<?php echo $cnt; ?>"  value="<?php echo $primaryinvoiceDetailID; ?>"  size="30" maxlength="15" />
                            </div></td>
                            
                            <td >
                            <div   id="divToolsMarksID<?php echo $cnt; ?>" style='visibility: hidden;width:1px;'>
                            <input name="ToolsMarksID<?php echo $cnt; ?>"  class="textbox" id="ToolsMarksID<?php echo $cnt; ?>" value="<?php echo $ToolsMarksID; ?>" size="18" maxlength="18" readonly />
                            </div></td>
                            
                            <td > <input type="checkbox" name="chk<?php echo $cnt; ?>" value="1"><td >
                            <div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 22px' maxlength="6" readonly /></div></td>
                            <?php
                                
                                
                                
                                $tabindex++;
                               
                                print select_option('ProducersID'.$cnt,'',',',$IDProducersID,++$tabindex,'','','1','rtl',0,'',$ProducersID,
                                "onchange = \"
                                if (document.getElementById('ProducersID$cnt').value.length<=1)document.getElementById('marksID$cnt').selectedIndex=0;
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',0,this.tabIndex);\"",
                                95);

                                //print $query;
                                
                                
                                print select_option('marksID'.$cnt,'',',',$IDmarksID,++$tabindex,'','','1','rtl',0,'',$marksID,
                               "onchange = \"
                                if (document.getElementById('marksID$cnt').value.length<=1)document.getElementById('ProducersID$cnt').selectedIndex=0;
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',2,this.tabIndex);\""
	                            ,75);
                                $tabindex++;

	           				  ?>

                            <td class="data"><div id="divutitle<?php echo $cnt; ?>"><input  onmouseover="Tip(<?php echo '(\''.$utitle.'\')'; ?>)" name="utitle<?php echo $cnt; ?>" type="text" class="textbox" id="utitle<?php echo $cnt; ?>" value="<?php echo $utitle; ?>" style='width: 75px'  readonly /></div></td>
                            
                            <td class="data"><div id="divNumber<?php echo $cnt; ?>"><input  
                            
                             onmouseover="Tip(<?php echo '(\''.$Number.'\')'; ?>)" name="Number<?php echo $cnt; ?>" tabindex="<?php echo $tabindex; ?>" type="text" class="textbox" id="Number<?php echo $cnt; ?>" value="<?php echo $Number; ?>" style='width: 75px' maxlength="12"
                            
                            <?php echo 
                            "onchange = \"FilterNextCombobox('$cnt');\"
                             
                             onblur=\" whiteelements();\"
                             onfocus=\"whiteelements();document.getElementById('Number$cnt').style.backgroundColor = 'yellow';\"
                            /></div></td>";
                            
                            if ($blacklist!=1)
                            echo 
                            "<td class='data'><div id='divPrice$cnt'><input  name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price' style='width: 99px' maxlength='12'  readonly /></div></td>
                            <td class='data'><div id='divSumPrice$cnt'><input  name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice' style='width: 124px' readonly /></div>
                            
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptyPrice$cnt'><input  name='EmptyPrice$cnt' type='text' class='textbox' id='EmptyPrice$cnt'  maxlength='12'  readonly /></div></td>
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptySumPrice$cnt'><input  name='EmptySumPrice$cnt' type='text' class='textbox' id='EmptySumPrice$cnt'  readonly /></div>
                            ";
                            else
                            echo 
                            "<td class='data'><div id='divEmptyPrice$cnt'><input  name='EmptyPrice$cnt' type='text' class='textbox' id='EmptyPrice$cnt' style='width: 99px' maxlength='12'  readonly /></div></td>
                            <td class='data'><div id='divEmptySumPrice$cnt'><input  name='EmptySumPrice$cnt' type='text' class='textbox' id='EmptySumPrice$cnt' style='width: 124px' readonly /></div>
                            
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divPrice$cnt'><input  name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price'  maxlength='12'  readonly /></div></td>
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divSumPrice$cnt'><input  name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice'  readonly /></div>
                            ";
                                         
                                                print select_option('gadget3ID'.$cnt,'',',',$IDgadget3ID,0,'','','1','rtl',0,'',$gadget3ID,
                                "onchange = \"
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',3,this.tabIndex);
                                
                                
                                \"
                                "
	                           ,1,'').
                               
                                select_option('gadget2ID'.$cnt,'',',',$IDgadget2ID,0,'','','1','rtl',0,'',$gadget2ID,
                                "onchange = \"FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicedetail_list_jr.php',1,this.tabIndex);\""
                                ,1)
                               ."</td><td><input name='btn$cnt'  type='button' id='btn$cnt' value='c' onclick=\"
                               
                               
                                document.getElementById('marksID$cnt').selectedIndex=0;
                                document.getElementById('ProducersID$cnt').selectedIndex=0;
                                document.getElementById('gadget3ID$cnt').selectedIndex=0;
                                document.getElementById('gadget2ID$cnt').selectedIndex=0;
                                document.getElementById('suggest$cnt').value='';
                                document.getElementById('utitle$cnt').value='';
                                document.getElementById('Number$cnt').value='';
                                document.getElementById('Price$cnt').value='';
                                document.getElementById('SumPrice$cnt').value='';
                                
                               
                               \" /></td></tr>";

                    }
					
					?>
    
   
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
