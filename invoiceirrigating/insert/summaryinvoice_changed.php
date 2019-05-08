<?php 

/*

insert/summaryinvoice_changed.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

        $linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
        $ApplicantMasterID=$linearray[0];//شناسه طرح
        
        
//----------

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
select InvoiceMasterID, vin.ToolsMarksID,vin.Title,Number,
units.title utitle
        ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(
		gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),
		CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),
		ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),
		ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),
		ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),
		ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         gadget3Title
        ,marks.Title as MarksTitle 
 from (
 
 SELECT invoicedetailviewed.InvoiceMasterID, ToolsMarksID,Number,invoicemaster.Title FROM invoicedetailviewed
inner join invoicemaster on invoicemaster.InvoiceMasterid=invoicedetailviewed.InvoiceMasterid and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
WHERE (invoicedetailviewed.InvoiceMasterID, ToolsMarksID,Number) NOT IN
( SELECT invoicedetail.InvoiceMasterID, ToolsMarksID,Number
  FROM invoicedetail
inner join invoicemaster on invoicemaster.InvoiceMasterid=invoicedetail.InvoiceMasterid and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
)

) vin

        inner join toolsmarks on toolsmarks.ToolsMarksID=vin.ToolsMarksID
        inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID
		left outer join units on gadget3.unitsID=units.unitsID
        left outer join marks on marks.MarksID=toolsmarks.MarksID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        
";

//print $sql;

$sql2 = "
select InvoiceMasterID, vin.ToolsMarksID,vin.Title,Number,
units.title utitle
        ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(
		gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),
		CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),
		ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),
		ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),
		ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),
		ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         gadget3Title
        ,marks.Title as MarksTitle 
 from (
 
SELECT invoicedetail.InvoiceMasterID, ToolsMarksID,Number ,invoicemaster.Title FROM invoicedetail
inner join invoicemaster on invoicemaster.InvoiceMasterid=invoicedetail.InvoiceMasterid and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
WHERE (invoicedetail.InvoiceMasterID, ToolsMarksID,Number) NOT IN
( SELECT invoicedetailviewed.InvoiceMasterID, ToolsMarksID,Number
  FROM invoicedetailviewed
inner join invoicemaster on invoicemaster.InvoiceMasterid=invoicedetailviewed.InvoiceMasterid and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
)



) vin

        inner join toolsmarks on toolsmarks.ToolsMarksID=vin.ToolsMarksID
        inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID
		left outer join units on gadget3.unitsID=units.unitsID
        left outer join marks on marks.MarksID=toolsmarks.MarksID
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        
";
//print $sql;


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
                   
                            
                   <?php
                
         
       
							try 
								  {		
									  	   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

           
                           $cnt=0;
        $rown=0;
        $sum=0;
        print "مقادیر اولیه <table width='100%' align='center'><tr>
                        	<th align='center'  ></th>
                        	<th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\" >ردیف</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\">شرح </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">مارک</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">واحد</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">مقدار</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">لیست</th>
                        </tr>";
        while($row = mysql_fetch_assoc($result))
        {
            
            
            $MarksTitle=$row['MarksTitle'];
            $utitle = trim($row['utitle']);
            $gadget3Title = $row['gadget3Title'];
            $Number = ($row['Number']);
            
            $Title=$row['Title'];
            $rown++;
            
            if ($Number>0)  print "     <tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$rown&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:130%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$gadget3Title&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsmark;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$MarksTitle&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$utitle&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Number&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Title&nbsp;</td>
                        </tr>";

        }
            print " </table>";      
            
            
            
                     
        
         
							try 
								  {		
									  	   $result = mysql_query($sql2);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
                           $cnt=0;
        $rown=0;
        $sum=0;
        print "<br>مقادیر جدید<table width='100%' align='center'><tr>
                        	<th align='center'  ></th>
                        	<th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\" >ردیف</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:120%;font-weight: bold;font-family:'B Nazanin';\">شرح </th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">مارک</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">واحد</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">مقدار</th>
                            <th align='center' style = \"background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">لیست</th>
                        </tr>";
        while($row = mysql_fetch_assoc($result))
        {
            
            
            $MarksTitle=$row['MarksTitle'];
            $utitle = trim($row['utitle']);
            $gadget3Title = $row['gadget3Title'];
            $Number = ($row['Number']);
            
            $Title=$row['Title'];
            $rown++;
            
            if ($Number>0)  print "     <tr>
                            <td  style= \"border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$rown&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:$fsgoods;line-height:130%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$gadget3Title&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsmark;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$MarksTitle&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$utitle&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Number&nbsp;</td>
                            <td style = \"border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:$fsgoods;line-height:95%;font-weight: bold;font-family:'B Nazanin';\">&nbsp;$Title&nbsp;</td>
                        </tr>";

        }
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