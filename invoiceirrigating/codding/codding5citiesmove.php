<?php 

/*

codding/codding5citiesmove.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5countries.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    
    $linearray = explode(',',$id);
    $selectedid=$linearray[1];
        /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    applicantmaster جدول مشخصات طرح
    */
    $query = "select distinct id ,CityName,case ifnull(applicantmaster.CityId,0) when 0 then '' else 'دارد' end gardesh from tax_tbcity7digit 
        left outer join applicantmaster on applicantmaster.CityId=tax_tbcity7digit.id
        where id in ($id)
        and substring(id,5,3)<>'000' order by CityName  COLLATE utf8_persian_ci
    ";
    //print $query;
 

   try 
      {		
          $result = mysql_query($query);
	
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }

    if (!$id) header("Location: ../logout.php");
}

$register = false;

if ($_POST){
	$addIDs = $_POST["addIDs"];
    $linearray = explode(',',$addIDs);
    $oldstateid=substr($linearray[1],0,4);
    $sos = $_POST["sos"];
    $newstateid=substr($sos,0,4);
    
    $linearray = explode(',',$addIDs);
    $selectedid=$linearray[1];
    
    $sql = "select distinct id ,CityName from tax_tbcity7digit 
        left outer join applicantmaster on applicantmaster.CityId=tax_tbcity7digit.id
        where id in ($addIDs) and case ifnull(applicantmaster.CityId,0) when 0 then '' else 'دارد' end=''
        ";
		
      
		  try 
			  {		
				    $result = mysql_query($sql);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    while($row = mysql_fetch_assoc($result))
    {
        $inputtitle = $row['CityName'];
        addcity($newstateid,$inputtitle);   
    }    
	
	$query = "delete from tax_tbcity7digit where id in ( select id from ( select distinct id from tax_tbcity7digit 
        left outer join applicantmaster on applicantmaster.CityId=tax_tbcity7digit.id
        where id in ($addIDs) and case ifnull(applicantmaster.CityId,0) when 0 then '' else 'دارد' end='' )as view1 )";
       
			  try 
			  {		
				     $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

        $register = true;
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>انتقال شهرها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>

   <script >
    
function FilterComboboxes(Url,Tabindex)
{ 
    var selectedsoo=document.getElementById('soo').value;
    var selectedsos=document.getElementById('sos').value;
    $.post(Url, {selectedsoo:selectedsoo,selectedsos:selectedsos}, function(data){
    //alert (data.val1);
	       $('#divsos').html(data.val0);
       }, 'json');    

                               
}

    </script>

<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
                        header("Location: codding5countries.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$selectedid.rand(10000,99999));
                        
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
                <form action="codding5citiesmove.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php 
                    $sttools="";
                    while($row = mysql_fetch_assoc($result))
                    {
                        $sttools.="<br>$row[CityName]";
                        print '<br>'.$row['CityName'];
                    }
    
                    
                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' order by _key  COLLATE utf8_persian_ci";
    				 $ID1 = get_key_value_from_query_into_array($query);
                    
                    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring('$soo',1,2)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    				 $ID2 = get_key_value_from_query_into_array($query);
                    
                    
                     print select_option('soo','استان',',',$ID1,0,'','','1','rtl',0,'',$soo,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'135').
                     select_option('sos','دشت/شهرستان',',',$ID2,0,'','','1','rtl',0,'',$sos,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'75')
                     .
                     "
                    <td class='data'><input name='addIDs' type='' readonly class='textbox' id='addIDs'  value='$id'  /></td>";                     
                    
                    
                            
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