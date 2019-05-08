<?php 
/*
tools/tools_mark_groupdelete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");



$register = false;

if ($_POST){
        /*
           gadget1 جدول سطح اول ابزار
           gadget1id شناسه جدول سطح اول ابزار
           gadget2 جدول سطح دوم ابزار
           gadget2id شناسه جدول سطح دوم ابزار
           gadget3 جدول سطح سوم ابزار
           gadget3id شناسه جدول سطح سوم ابزار
           toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
                ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
                gadget3ID شناسه سطح 3 ابزار
                ProducersID شناسه جدول تولیدکننده
                MarksID شناسه جدول مارک
           toolsmarksid شناسه ابزار و مارک
           toolspref جدول مرجع قیمتی
           invoicedetail جدول ریز آیتم های پیش فاکتور
           pricelistdetail جدول ریز قیمت لوازم
    */    
	$ProducersIDsource = $_POST["ProducersIDsource"];
    $MarksID=$_POST["MarksID"];
    
    $query = "select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and toolsmarks.producersID='$ProducersIDsource'
        ";
    $ID = get_key_value_from_query_into_array($query);
    $strgadget2ids="0";
    foreach ($ID as $key => $value)
        if ($_POST["Producer$value"]=='on')
            if ($value>0)
                $strgadget2ids=$strgadget2ids.",".$value;      


    $query ="delete from toolsmarks  
            where ProducersID='$ProducersIDsource' and MarksID='$MarksID' and gadget3ID in ( select gadget3ID from gadget3 where gadget2ID in ($strgadget2ids)) 
                and toolsmarksID not in (
                select toolsmarksID from invoicedetail  union all 
                select toolsmarksid from pricelistdetail union all 
                select ToolsMarksIDpriceref from toolspref union all 
                select toolsmarksID from toolspref)  ;";     
               try 
								  {		
									    $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    
    $register = true;
    

}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>حذف مارک برای گروه کالایی</title>
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
						header("Location: tools_mark_groupdelete.php");
                        
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
                <form action="tools_mark_groupdelete.php" method="post">
                
                <script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>
                <script type='text/javascript'>
                function SelectAll()
                {
                    $("input[id^='Producer']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
                function FilterComboboxes(Url)
                {
    var selectedProducersIDsource=document.getElementById('ProducersIDsource').value;
    
                    
                    
    $.post(Url, {selectedProducersIDsource:selectedProducersIDsource}, function(data){
    $('#tableproducers').html(data.selectstr2);
    
    
	       
       }, 'json');
                    
                }
                
                
                </script>
                   <table  width="600" align="center" class="form">
                    <tbody>
                    <?php 
                    
                    $query='select ProducersID as _value,Title as _key from producers order by Title COLLATE utf8_persian_ci';
                    $allProducersID = get_key_value_from_query_into_array($query);
                     
                    $query='select MarksID as _value,Title as _key from marks order by Title COLLATE utf8_persian_ci';
                    $allMarksID = get_key_value_from_query_into_array($query);
                       
                    print 
                    select_option('MarksID','مارک',',',$allMarksID,0,'','','1','rtl',0,'').
                    select_option('ProducersIDsource','برای تولیدکننده',',',$allProducersID,0,'','','2','rtl',0,'','0',
                    "onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools_producer_copy_jr.php');\"").
                    "<table id=tableproducers style='border:2px solid;'></table>"
                    
                    ;
                    
					  ?>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="حذف" /></td>
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