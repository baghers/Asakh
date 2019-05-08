<?php 

/*
instruction/instruction2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/instruction2_delete.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
 
$permitrolsid = array("1");
if (in_array($login_RolesID, $permitrolsid))
//instructionmeet دستورالعمل مشاهده شده
  $sql = "SELECT * from instructionmeet order by instructionno ";


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
  	<title>لیست دستورالعمل ها</title>

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

    <script>
    
    </script>
    <!-- /scripts -->
</head>
<body >

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
            
            <form action="inst_aml.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
                  <?php
                   
                   if (in_array($login_RolesID, $permitrolsid))
                    {
                    	$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
					inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
					order by _key  COLLATE utf8_persian_ci";
					$allg1id = get_key_value_from_query_into_array($sqlselect);
			 	    print select_option('g1id',' ',',',$allg1id,0,'',$disabled,'4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
                       print  "</tr><tr>
                          <td  class='label'>ترتیب:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='instructionno' type='text' class='textbox' id='instructionno'    /></td>
                                
                          <td  class='label'>عنوان:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 250px\"
                          name='HeaderTitle' type='text' class='textbox' id='HeaderTitle'    /></td>
                         
                         <td  class='label'>شرح:</td>
                          <td class='data'><textarea id='Description' colspan='2' name='Description' rows='3' cols='90'  ></textarea></td>
                         
                         
                         </tr>
                         <tr>
                        <td colspan='1' class='label'>فایل</td>
                         
                         <td colspan='4' class='data'><input type='file' name='file1' id='file1'></td>
                         
                         <td><input   name='sb_meet' type='submit' class='button' id='sb_meet' value='ثبت' /></td>
                         
                                ";   
                    }
                   
                    ?>
               </div>
                  
                          
                        </tr>
                   </tbody>
                </table>
				
                <table id="records" width="95%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                        
                        	<th width="5%">ردیف </th>
                        	<th width="5%">ترتیب </th>
                        	<th width="80%">عنوان</th>
                            <th width="5%"></th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>        
                   <?php
                   
                    $rown=0;        
                    while($row = mysql_fetch_assoc($result)){
                        $instructionID = $row['instructionID'];
                        $instructionno = $row['instructionno'];
                        $HeaderTitle = $row['HeaderTitle'];
                        $rown++;
                        $deletestr="";
                        if (in_array($login_RolesID, $permitrolsid))
                        $deletestr="<a 
                            href='inst_aml.php?meetid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$instructionID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                        
                        
?>                      
                        <tr>
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $instructionno; ?></td>
                            <td><?php echo $HeaderTitle ?></td>
                            <td><a href=<?php print "instruction2_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$instructionID.rand(10000,99999); ?>>
                            <img style = "width: 75%;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            
                            
                            <?php
                            print "<td>$deletestr</td>
                        </tr>";

                    }

?>

                        
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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
