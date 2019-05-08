<?php 
/*
pricesaving/pricesaving_pl_copy.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesaving_pl_copy.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST){
    
 }                           

$register = false;

if ($_POST){
    if ($_POST["inc"]>0 && $_POST["dec"]>0)
    {
        echo "لطفا تنها یکی از درصد های افزایش یا کاهش را وارد نمایید.";
        exit;
    }
    $currg2id=$_POST['g2id'];
    
    if ($currg2id>0)
    //gadget3 جدول سطح سوم ابزار
        $gadget2cond=" and  gadget3.gadget2id='$currg2id' ";
    
	$PriceListMasterIDfrom = $_POST["PriceListMasterIDfrom"];//از لیست قیمت
	$PriceListMasterIDto = $_POST["PriceListMasterIDto"];//تا لیست قیمت
    
    if ($PriceListMasterIDto>0)
    {
       /*
       producers جدول تولیدکننده
       producersid شناسه تولید کننده
       producers.Title عنوان تولید کننده
       pricelistdetail جدول قیمت های تایید شده
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       gadget3 جدول سطح سوم ابزار
       gadget3id شناسه جدول سطح سوم ابزار
       gadget2id شناسه جدول سطح دوم ابزار
       hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
       PriceListMasterID شناسه لیست قیمت
       price مبلغ
       */
        $query = "SELECT distinct producers.producersid as _value,producers.Title  as _key FROM `pricelistdetail`
        inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid
        inner join producers on producers.producersid=toolsmarks.producersid
        inner join gadget3 on  gadget3.gadget3id=toolsmarks.gadget3id and gadget2id not in (202,376,494,495) $gadget2cond
        where ifnull(pricelistdetail.hide,0)=0 and `pricelistdetail`.`PriceListMasterID` ='$PriceListMasterIDfrom' and price>0 
            ";
        $ID = get_key_value_from_query_into_array($query);
        $strproducersids="";
        foreach ($ID as $key => $value)
            if ($_POST["Producer$value"]=='on')
                if ($value>0)
                    if (strlen($strproducersids)>0)
                        $strproducersids=$strproducersids.",".$value;  
                        else  
                        $strproducersids=$value;      
        
        $strp="pricelistdetail.Price";
        if ($_POST["inc"]>0)
        $strp="round(pricelistdetail.Price+(pricelistdetail.Price*$_POST[inc]/100))";
        else if ($_POST["dec"]>0)
        $strp="round(pricelistdetail.Price-(pricelistdetail.Price*$_POST[dec]/100))";
        
        
            
        $query = "
        INSERT INTO `pricelistdetail`(`PriceListMasterID`, `ToolsMarksID`, `Price`, `SaveDate`, `SaveTime`, `ClerkID`) 
        SELECT distinct '$PriceListMasterIDto', pricelistdetail.ToolsMarksID,$strp
        ,'".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid' FROM `pricelistdetail`
        inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid
        inner join producers on producers.producersid=toolsmarks.producersid and producers.producersid in ($strproducersids) 
        inner join gadget3 on  gadget3.gadget3id=toolsmarks.gadget3id and gadget2id not in (202,376,494,495) $gadget2cond
        where ifnull(pricelistdetail.hide,0)=0 and `pricelistdetail`.`PriceListMasterID` ='$PriceListMasterIDfrom' and price>0 
        and pricelistdetail.ToolsMarksID not in (select ToolsMarksID from pricelistdetail where PriceListMasterID='$PriceListMasterIDto')    
        ";
        //print $query;exit;
         
         			  	 	try 
								  {		
									  	  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
        $register = true;
    }

}


?>  
<!DOCTYPE html>
<html>
<head>
  	<title>کپی لیست قیمت </title>
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
						header("Location: pricesaving_pl_copy.php");
                        
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
                <form action="pricesaving_pl_copy.php" method="post">
                
                <script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>
                <script type='text/javascript'>
                function SelectAll()
                {
                    
                    
                    if ($("input[id^='Producer']:checked").length == $("input[id^='Producer']").length)
                    $("input[id^='Producer']").prop('checked', false);
                    else
                    $("input[id^='Producer']").prop('checked', true);
                    
                    //$("input[id^='Producer']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
                function FilterComboboxes(Url)
                {
    var selectedPriceListMasterIDfrom=document.getElementById('PriceListMasterIDfrom').value;
    
                    
                    
    $.post(Url, {selectedPriceListMasterIDfrom:selectedPriceListMasterIDfrom}, function(data){
    $('#tableproducers').html(data.selectstr2);
    
    
	       
       }, 'json');
                    
                }
                
                
                </script>
                   <table  width="600" align="center" class="form">
                    <tbody>
                    
                    <?php 
                    $query = "
                    select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                    inner join year on year.YearID=pricelistmaster.YearID
                    inner join month on month.MonthID=pricelistmaster.MonthID";
                
                    $allPriceListMasterID = get_key_value_from_query_into_array($query);
                     
                    $sqlselect="select CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
                        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
                        
                        order by _key  COLLATE utf8_persian_ci";
                    $allg2id = get_key_value_from_query_into_array($sqlselect);
                       
                    print 
                    select_option('PriceListMasterIDfrom','از لیست قیمت',',',$allPriceListMasterID,0,'','','2','rtl',0,'','0',
                    "onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools_producer_copy_jr.php');\"").
                    
                    
                    select_option('PriceListMasterIDto','به',',',$allPriceListMasterID,0,'','','1','rtl',0,'').
                    "
                    درصد افزایش قیمت: (یک عدد صحیح بین 0 تا 300) 
                      <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" 
                      name='inc' type='text' class='textbox' id='inc' value='0' size='1' maxlength='50'  pattern='[0-9]{1,}' />
		            
                    درصد کاهش قیمت: (یک عدد صحیح بین 0 تا 300)
                      <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" 
                      name='dec' type='text' class='textbox' id='dec' value='0' size='1' maxlength='50'  pattern='[0-9]{1,}' />
                    
                    ".select_option('g2id','گروه کالا',',',$allg2id,0,'','','4','rtl',0,'',0,"onChange=\"selectpage();\"",'213')."  
                    <table id=tableproducers style='border:2px solid;'></table>";
                    
					  ?>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="انتقال" /></td>
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