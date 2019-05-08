<?php 

/*

insert/summaryinvoice_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

        $linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
        $Code=$linearray[0];//سریال طرح
        $ApplicantMasterID=$linearray[1];//شناسه طرح
        $type=$linearray[2];//نوع
   

if ($_POST)//در صورتی که کلید سابمیت کلیک شده باشد
{			
    $Code=$_POST['Code'];
    $ApplicantMasterID=$_POST['ApplicantMasterID'];  		
}
$per_page = 1000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;


$gadget3operationalstr=retgadget3operational($ApplicantMasterID);
        
//        print $gadget3operationalstr;exit;

//print "sa2";
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
$sql = "select 
replace(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(`gadget2`.`Title`,_utf8' ')
,ifnull(`materialtype`.`Title`,_utf8'')),_utf8' ')
,ifnull(`gadget3kala`.`spec1`,_utf8'')),_utf8' ')
,ifnull(`gadget3kala`.`Title`,_utf8''))
,concat(_utf8' ',concat(concat(concat(concat(concat(concat(concat(ifnull(`gadget3kala`.`size11`,_utf8''),_utf8'')
,ifnull(`operator`.`Title`,_utf8'')),_utf8''),ifnull(`gadget3kala`.`size12`,_utf8'')),_utf8'')
,ifnull(`gadget3kala`.`size13`,_utf8' ')),concat(ifnull(`sizeunits`.`Title`,_utf8''),_utf8' '))))
,ifnull(`gadget3kala`.`zavietoolsorattabaghe`,_utf8'')),_utf8'')
,ifnull(`sizeunitszavietoolsorattabaghe`.`Title`,_utf8'')),_utf8'')
,ifnull(`spec2`.`Title`,_utf8'')),_utf8' '),ifnull(`gadget3kala`.`fesharzekhamathajm`,_utf8'')),_utf8'')
,ifnull(`sizeunitsfesharzekhamathajm`.`Title`,_utf8'')),_utf8' ')
,concat(concat(concat(concat(concat(ifnull(`spec3`.`Title`,_utf8''),_utf8''),ifnull(`gadget3kala`.`spec3size`,_utf8'')),_utf8'')
,ifnull(`spec3sizeunits`.`Title`,_utf8'')),_utf8' ')),_utf8''),_utf8' '),_utf8' '),_utf8'  ',_utf8' ') 
AS `gadget3Title`
,`gadget3costs`.`Title` AS `Title`
,`gadget3costs`.`Code` AS `Code`
,`invoicedetail`.`Number` AS `Number`,`invoicedetail`.`deactive` AS `deactive`,`invoicedetail`.`InvoiceDetailID` AS `InvoiceDetailID`
,`gadget3operational`.`CostCoef` AS `CostCoef`
,`units`.`Title` AS `unit`,`costpricelistdetail`.`Price` AS `Price`
,((`costpricelistdetail`.`Price` * `invoicedetail`.`Number`) * `gadget3operational`.`CostCoef`) AS `Total`
,`invoicemaster`.`ApplicantMasterID` AS `ApplicantMasterID`
,`invoicemaster`.`Title` AS `invoicemasterTitle` 
from ((((((((((((((((((((
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
where gadget3costs.Code > 0 and invoicemaster.ApplicantMasterID='$ApplicantMasterID' and  
gadget3costs.Code='$Code' and ifnull(invoicedetail.deactive,0)=0;";
//print $sql;

							try 
								  {		
									  	  $results = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
$result = mysql_query($sql);
$count = mysql_num_rows($result);
$pages = ceil($count / $per_page);

$row = mysql_fetch_assoc($result);
                
$gadget3Title = $row['gadget3Title'];
$Title = $row['Title'];
$Code = $row['Code'];
$Number = $row['Number'];
$deactive = $row['deactive'];
$unit = $row['unit'];
$Price = $row['Price'];
$Total = $row['Total'];
$invoicemasterTitle = $row['invoicemasterTitle'];
$InvoiceDetailID = $row['InvoiceDetailID'];

if ($_POST)//در صورتی که کلید سابمیت کلیک شده باشد
		{
                   

				       if ($_POST['chk'.$row['InvoiceDetailID']]=='on')
                        $query = "UPDATE invoicedetail SET deactive=1,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]';";
                        else
                        $query = "UPDATE invoicedetail SET deactive=0,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$row[InvoiceDetailID]';";
                        //    print $query."<br>";
                        try 
                          {		
                            mysql_query($query); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
   
                
          		while ($rows = mysql_fetch_assoc($results))
                {
                   
                 //print $_POST['chk'.$row['InvoiceDetailID']].'<br>';
                   // if (isset($_POST['Number'.$row['InvoiceDetailID']]))
                  //  {
                        /*
                        invoicedetail ریز لوازم طرح
                        deactive غیر فعال کردن هزینه اجرایی
                        SaveTime زمان
                        SaveDate تاریخ
                        ClerkID کاربر
                        */
                        
                        if ($_POST['chk'.$rows['InvoiceDetailID']]=='on')
                        $query = "UPDATE invoicedetail SET deactive=1,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$rows[InvoiceDetailID]';";
                        else
                        $query = "UPDATE invoicedetail SET deactive=0,SaveTime = '" . date('Y-m-d H:i:s') . "', 
                		SaveDate = '" . date('Y-m-d') . "', 
                		ClerkID = '" . $login_userid . "' WHERE InvoiceDetailID ='$rows[InvoiceDetailID]';";
                          //  print $query."<br>";
                        try 
                          {		
                            mysql_query($query); 
                          }
                          //catch exception
                          catch(Exception $e) 
                          {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                          }
                          
                                                    
                    //}
				}
				
					   $ID=$Code.'_'.$ApplicantMasterID;
					     
						 header("Location: summaryinvoice_detail.php?np=10&uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ID.rand(10000,99999)."
							");
          
		
		}




?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print $TITLE; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	    <style>
    
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
         
            <form action="summaryinvoice_detail.php" method="post" >
  			
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <div style = "text-align:left;"><a  href=<?php print "summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            <h1 align="center"><?php print $Code.'-'.$Title; ?></h1>
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
	    $rown=1;
                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="35%">عنوان کالا</th>
                            <th width="25%">عنوان پیش فاکتور</th>
                            <th width="5%">تعداد</th>
                            <th width="5%">ضریب تبدیل</th>
                            <th width="5%">واحد</th>
                            <th width="10%">فی</th>
                            <th width="15%">مبلغ</th>
                        </tr>
                    </thead>
                   <tbody>
                   
                   <tr>
                            <td><?php echo $rown .' - '.$gadget3Title; ?></td>
                            <td><?php echo $invoicemasterTitle.'<br>'.$InvoiceDetailID; ?></td>
                            <td><?php echo $Number; ?></td>
                            <td><?php echo  $row['CostCoef']; ?></td>
                            <td><?php echo $unit; ?></td>
                            <td><?php echo number_format($Price); ?></td>
                            <td><?php echo number_format(round($Total)); ?></td>
                          		<?php
							  echo" 
						 <td >$chk </td>
						 </tr>
						 ";
				
                
                 
                    while($row = mysql_fetch_assoc($result)){
						$deactive = $row['deactive'];
                        $gadget3Title = $row['gadget3Title'];
                        $Number = $row['Number'];
                        $unit = $row['unit'];
                        $Price = $row['Price'];
                        $Total = $row['Total'];
                        $invoicemasterTitle = $row['invoicemasterTitle'];
						$readonlydesc='';
						$InvoiceDetailID = $row['InvoiceDetailID'];
						$rown++;
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
						
						 ?>
							<tr>
                            <td><?php echo $rown .' - '.$gadget3Title; ?></td>
                            <td><?php echo $invoicemasterTitle.'<br>'.$InvoiceDetailID; ?></td>
                            <td><?php echo $Number; ?></td>
                            <td><?php echo  $row['CostCoef']; ?></td>
                            <td><?php echo $unit; ?></td>
                            <td><?php echo number_format($Price); ?></td>
                            <td><?php echo number_format(round($Total)); ?></td>
								<?php echo" <td >$chk </td> 
								
								</tr> 
								";
	                    }
					 echo"	 <input  name='ApplicantMasterID' type='hidden' id='ApplicantMasterID' value='$ApplicantMasterID'  /> 
							<input  name='Code' type='hidden' id='Code' value='$Code'  />
					 ";  
                     if ($type==3 || $login_RolesID==1)
                     {
                        echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td ></tr>
                				<tr><td></td><td></td><td></td><td></td><td></td><td></td><td >
                				<input   name='submit' type='submit' class='no-print' id='submit' value='ثبت' ></td><td></td></tr>";
                     }           
?>
				
                    </tbody>
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