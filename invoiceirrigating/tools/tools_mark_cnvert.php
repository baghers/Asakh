<?php 
/*
tools/tools_mark_cnvert.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");



$register = false;

if ($_POST){
    
	$ProducersIDsource = $_POST["ProducersIDsource"];//شناسه تولید کننده
    $MarksID=$_POST["MarksID"];//شناسه مارک
    $MarksIDto=$_POST["MarksIDto"];//شناسه مارک به
    
    
    if ($ProducersIDsource>0 && $MarksID>0 && $MarksIDto>0 )
    {
        /*
            toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
                ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
                gadget3ID شناسه سطح 3 ابزار
                ProducersID شناسه جدول تولیدکننده
                MarksID شناسه جدول مارک
        */
        $query = "update toolsmarks set MarksID='$MarksIDto' where ProducersID='$ProducersIDsource' and MarksID='$MarksID'";
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

    

}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تغییر مارک   </title>
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
						header("Location: tools_mark_cnvert.php");
                        
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
                <form action="tools_mark_cnvert.php" method="post">
                
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
    $('#divMarksID').html(data.selectstr3);
    
    
	       
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
                    select_option('ProducersIDsource','تولیدکننده',',',$allProducersID,0,'','','2','rtl',0,'','0',
                    "onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools_producer_copy_jr.php');\"").
                    
                    select_option('MarksID','تغییر مارک از',',',$allMarksID,0,'','','1','rtl',0,'').
                    select_option('MarksIDto','به',',',$allMarksID,0,'','','1','rtl',0,'')
                    .
                    "<table id=tableproducers style='border:2px solid;'></table>"
                    
                    ;
                    
					  ?>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
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