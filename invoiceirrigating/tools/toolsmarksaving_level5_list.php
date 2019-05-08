<?php 
/*
tools/toolsmarksaving_level5_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level4_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='applicantmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");


$Gadget3IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

$linearray = explode('_',$Gadget3IDProducersID);
$Gadget3ID=$linearray[0];//جدول سطح 3 ابزار
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
    $query ="select gadget2.Gadget2ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title,
      replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
      from producers,gadget2 
	  
            inner join gadget1 on gadget2.gadget1ID=gadget1.gadget1ID
            inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            where 
            ProducersID='$ProducersID' and gadget3ID='$Gadget3ID'
         ";
		 
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
	$PTitle = $resquery["PTitle"];
	$g1Title = $resquery["g1Title"];
	$g2Title = $resquery["g2Title"];
	$Title = $resquery["FullTitle"];
	$Gadget2ID = $resquery["Gadget2ID"];
    
$sql = "

select marks.title Markstitle,operatorco.title operatorcotitle,designerco.title DesignerCotitle,ApplicantFName,ApplicantName,
CONCAT(invoicemaster.Title,'( لیست قیمت ',year.Value,' ',month.Title,')')  invoicemasterTitle,invoicedetail.number,applicantstates.title applicantstatestitle
,applicantmaster.TMDate laststatedate,designsystemgroups.title 
,bakhsh.cityname bakhshcityname,shahr.cityname shahrcityname ,invoicemaster.invoicemasterid invoicemasterID
from invoicedetail 
inner join toolsmarks on toolsmarks.ProducersID='$ProducersID' and toolsmarks.gadget3ID='$Gadget3ID'
inner join marks on marks.MarksID=toolsmarks.MarksID
left outer join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid  
						
left outer join pricelistmaster on pricelistmaster.pricelistmasterid=invoicemaster.pricelistmasterid 
inner join year on year.YearID=pricelistmaster.YearID
inner join month on month.MonthID=pricelistmaster.MonthID
                
left outer join applicantmaster on applicantmaster.ApplicantMasterID=invoicemaster.ApplicantMasterID 
left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID
left outer join operatorco on operatorco.operatorcoID=applicantmaster.operatorcoID
left outer join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID
left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 
DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid
where invoicedetail.ToolsMarksID =toolsmarks.ToolsMarksID


union all
select marks.title Markstitle,'' operatorcotitle,'لیست قیمت' DesignerCotitle ,'' ApplicantFName,'' ApplicantName,CONCAT(CONCAT(yearprice.Value,' ')
,monthprice.Title) invoicemasterTitle, '' number,'' applicantstatestitle,'' laststatedate,'' DesignSystemGroupstitle 
,'' bakhshcityname,'' shahrcityname ,'' invoicemasterID
from pricelistdetail
inner join toolsmarks on toolsmarks.ProducersID='$ProducersID' and toolsmarks.gadget3ID='$Gadget3ID'
inner join marks on marks.MarksID=toolsmarks.MarksID
inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid 
left outer join month as monthprice on monthprice.MonthID=pricelistmaster.MonthID  
left outer join year as yearprice on yearprice.YearID=pricelistmaster.YearID 

where pricelistdetail.toolsmarksid=toolsmarks.ToolsMarksID

";


//print $sql;

$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);


?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

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
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">   </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <div style = "text-align:left;"><a  href=<?php print "toolsmarksaving_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                      
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                    
                     <tr>
                     <td  class="label">تولیدکننده:</td>
                      <td colspan="10" width="80%" class="data"><input readonly name="PTitle" type="text" class="textbox" id="PTitle" value="<?php echo $PTitle; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td  class="label">عنوان سطح1:</td>
                      <td colspan="10" width="80%" class="data"><input readonly name="g1Title" type="text" class="textbox" id="g1Title" value="<?php echo $g1Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td  class="label">عنوان سطح2:</td>
                      <td  colspan="10" width="80%" class="data"><input readonly name="g2Title" type="text" class="textbox" id="g2Title" value="<?php echo $g2Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td  class="label">نام کالا:</td>
                      <td colspan="10" width="80%" class="data"><input readonly name="Title" type="text" class="textbox" id="Title" value="<?php echo $Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                        <tr>
                        	<th >طراح</th>
                        	<th >مجری</th>
                            <th >نام طرح</th>
                            <th >عنوان پیش فاکتور</th>
                            <th >مارک</th>
                            <th >مقدار</th>
                            <th >وضعیت</th>
                            <th >تاریخ</th>
                            <th >سیستم آبیاری</th>
                            <th >شهرستان</th>
                            <th >شهر</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   
                   
                   <?php
                    do{
                        $DesignerCotitle = $row['DesignerCotitle'];
                        $ApplicantName = $row['ApplicantName'].' '. $row['ApplicantFName'];
                        $invoicemasterTitle = $row['invoicemasterTitle']; 
?>                      
                        <tr>
                            <td><?php echo $DesignerCotitle; ?></td>
                            <td><?php echo $row['operatorcotitle']; ?></td>
                            <td><?php echo $ApplicantName; ?></td>
                            <td><?php echo $invoicemasterTitle; ?></td>
                            <td><?php echo $row['Markstitle']; ?></td>
                            <td><?php echo $row['number']; ?></td>
                            <td><?php echo $row['applicantstatestitle']; ?></td>
                            <td><?php echo gregorian_to_jalali($row['laststatedate']); ?></td>
                            <td><?php echo ($row['DesignSystemGroupstitle']); ?></td>
                            <td><?php echo $row['shahrcityname']; ?></td>
                            <td><?php echo $row['bakhshcityname']; ?></td>
                            
							<?php
					$limited = array("1","19");
                     if ( in_array($login_RolesID, $limited))
		
							 print "<td><a href='../insert/invoicedetail_list.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['invoicemasterID'].rand(10000,99999)."
							' $linksay
                            ><img style = 'width: 25%;' src='../img/search.png' title=' ریز اقلام پیش فاکتور/لیست لوازم '></a></td>
                        </tr>";

                    }
                    while($row = mysql_fetch_assoc($result));

?>
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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
