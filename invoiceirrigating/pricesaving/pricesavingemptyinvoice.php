<?php 
/*
pricesaving/pricesavingemptyinvoice.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingemptyinvoice.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='pricelistdetail';


if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 10;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
$currpage=$page;
                            
if (! $_POST)
{
    $pages = 10;
    /*
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       invoicedetail ریز پیش فاکتورها
       toolsmarksid شناسه ابزار و مارک
       toolspref جدول مرجع قیمتی
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
       month جدول ماه
       year جدول سال
       */
    $sql = "SELECT DISTINCT CONCAT(CONCAT(CONCAT(' لیست قیمت ',month.Title),' '),year.Value) pr, invoicemaster.PriceListMasterID, 
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle,toolsmarks.Gadget3ID, 
            marks.title markstitle,toolsmarks.MarksID, 
            producers.title producerstitle,toolsmarks.ProducersID,toolsmarks.ToolsMarksID
            FROM invoicedetail
            inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
            INNER JOIN invoicemaster ON invoicemaster.invoicemasterid = invoicedetail.invoicemasterid
            INNER JOIN applicantmaster ON applicantmaster.ApplicantMasterID = invoicemaster.ApplicantMasterID
            inner join PriceListMaster on PriceListMaster.PriceListMasterID=invoicemaster.PriceListMasterID
            inner join month on month.MonthID=PriceListMaster.MonthID
            inner join year on year.YearID=PriceListMaster.YearID
            inner join gadget3 on gadget3.gadget3id=toolsmarks.Gadget3ID
            inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id and gadget2.gadget1id<>68
             
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    
            inner join marks on marks.marksID=toolsmarks.MarksID 
            inner join producers on producers.producersid=toolsmarks.ProducersID		
            
            
        
        left outer join toolspref on toolspref.PriceListMasterID=PriceListMaster.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID and 
                                            pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        
            
            
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            WHERE ifnull( Price, 0 ) <=0 
            LIMIT $start , $per_page
            ";
    //$resultwhile = mysql_query($sql);
    //print $sql;


}
if ($_POST)
    { 
        
        $i=$start;
      
        while (isset($_POST['rown'.++$i]))
        {
            $PriceListMasterID = $_POST['PriceListMasterID'.$i];
            $ToolsMarksID = $_POST['ToolsMarksID'.$i];
            $Price = str_replace(',', '', $_POST['Price'.$i]);
            
               // print $Price."salam";
                
            if ($Price>0)	
        	{
                $query = "
                      INSERT INTO pricelistdetail(PriceListMasterID,ToolsMarksID,Price,SaveTime,SaveDate,ClerkID) 
                      VALUES('" .
                      $PriceListMasterID . "', '" . 
                      $ToolsMarksID . "', '" .  
                      $Price . "', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
            
               // print $query;
                
               
										try 
								  {		
									   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    			//header("Location: clerk.php");
    			$register = true;
                    
                
            }
         }
         //exit(0);
            
    
     }


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت لیست قیمت</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}
</script> 
<script language='javascript' src='../assets/jquery.js'></script>
    <!-- /scripts -->
</head>
<body >>

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Serial = "";
                        header("Location: pricesavingemptyinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ProducersID.rand(10000,99999));
                        
                        
                        
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
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content" >
            <form action="pricesavingemptyinvoice.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 


	function selectpage(obj){
		window.location.href ='?uid=' +document.getElementById('uid').value+ '&page=' + obj.value;
	}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    

</script>
";  ?>


                <div colspan="4">
                <tr >
                    <td align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-family:'B Nazanin';">ثبت لیست قیمت</td>
                </tr>
                    
                </div>
                        	
                        
                        
    <br />
                            
                            
                            
                            <td align="left"><?php
							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($currpage == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">R</th>
                        	<th width="20%">لیست قیمت</th>
                            <th width="45%">عنوان</th>
                            <th width="10%">تولید کننده</th>
                            <th width="10%">مارک</th>
                            <th width="10%">قیمت</th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $cnt=0;
                    if ($resultwhile)
                    while($row = mysql_fetch_assoc($resultwhile))
                    {
                      
                      $row['Price'] = 0;
                        $cnt++;
?>
                        <tr>
                            <td ><div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$cnt.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo ++$start; ?>" style='width: 35px' maxlength="6" readonly /></div></td>
                            <td ><div id="divpr<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$row['pr'].')'; ?>)" name="pr<?php echo $cnt; ?>" type="text" class="textbox" id="pr<?php echo $cnt; ?>" value="<?php echo $row['pr']; ?>" style='width: 140px' maxlength="6"  /></div></td>
                            <td ><div id="divGadget3Title<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$row['FullTitle'].')'; ?>)" name="Gadget3Title<?php echo $cnt; ?>" type="text" class="textbox" id="Gadget3Title<?php echo $cnt; ?>" value="<?php echo $row['FullTitle']; ?>" style='width: 370px' maxlength="6"  /></div></td>
                            <td ><div id="divproducerstitle<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$row['producerstitle'].')'; ?>)" name="producerstitle<?php echo $cnt; ?>" type="text" class="textbox" id="producerstitle<?php echo $cnt; ?>" value="<?php echo $row['producerstitle']; ?>" style='width: 140px' maxlength="6"  /></div></td>
                            <td ><div id="divmarkstitle<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$row['markstitle'].')'; ?>)" name="markstitle<?php echo $cnt; ?>" type="text" class="textbox" id="markstitle<?php echo $cnt; ?>" value="<?php echo $row['markstitle']; ?>" style='width: 140px' maxlength="6"  /></div></td>
                            
                            
                            <td class="data"><input  onmouseover="Tip(<?php echo '(\''.number_format($row['Price']).'\')'; ?>)" name="Price<?php echo $cnt; ?>" type="text" class="textbox" id="Price<?php echo $cnt; ?>" value="<?php echo $row['Price']; ?>" size="19" maxlength="19"  onKeyUp="convert('Price<?php echo $cnt; ?>')"  /></div></td>
                            <td class="data"><input name="ToolsMarksID<?php echo $cnt; ?>" type="hidden" class="textbox" id="ToolsMarksID<?php echo $cnt; ?>"  value="<?php echo $row['ToolsMarksID']; ?>"  /></td>
                            <td class="data"><input name="PriceListMasterID<?php echo $cnt; ?>" type="hidden" class="textbox" id="PriceListMasterID<?php echo $cnt; ?>"  value="<?php echo $row['PriceListMasterID']; ?>"  /></td>
                            
                            
                            
                            
                        </tr><?php

                    }

?>
                      
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                      
                      <td ><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                      <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                            
                      </tr>
                      
                      
                      
                    </tfoot>
                    
                </table>
            
                </form>
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
