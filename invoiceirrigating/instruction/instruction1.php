<?php 
/*
instruction/instruction1.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/instruction1_detail.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0 || $login_isfulloption!=1) header("Location: ../login.php");

 


$permitrolsid = array("1");
if (in_array($login_RolesID, $permitrolsid))
if ($_POST )
    {
        /*
        instruction جدول دستور العمل ها
        instructionno شماره
        HeaderTitle عنوان
        Description شرح
        */
        $instructionno=$_POST['instructionno'];
        $HeaderTitle=$_POST['HeaderTitle'];
        $Description=$_POST['Description'];        
        $sql="INSERT INTO instruction(instructionno,HeaderTitle, Description,SaveTime,SaveDate,ClerkID)
            values ('$instructionno','$HeaderTitle','$Description','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
            //print $sql;
            //exit;
                   
		  						try 
								  {		
									  	  mysql_query($sql); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

            
            
            $query = "select instructionID from instruction where instructionID = last_insert_id()";
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
            $instructionID = $row['instructionID'];
              
                
                    if ($_FILES["file1"]["error"] > 0) 
                    {
                        //echo "Error: " . $_FILES["file1"]["error"] . "<br>";
                    } 
                    else 
                    {
                        //echo "Upload: " . $_FILES["file1"]["name"] . "<br>";
                        //echo "Type: " . $_FILES["file1"]["type"] . "<br>";
                        //echo "Size: " . ($_FILES["file1"]["size"] / 1024) . " kB<br>";
                        //echo "Stored in: " . $_FILES["file1"]["tmp_name"];
                        
                        $ext = end((explode(".", $_FILES["file1"]["name"])));
                        foreach (glob("../../upfolder/instructions/" . $instructionID.'_1*') as $filename) 
                        {
                            unlink($filename);
                        }
                        move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/instructions/" . $instructionID.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
                        
                    }
              
                  

            
            
    }

  
  $sql = "SELECT * from instruction order by instructionno ";
 

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
            
            <form action="instruction1.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
                  <?php
                   $permitrolsid = array("1");
                   if (in_array($login_RolesID, $permitrolsid))
                    {
                       print  "
                          <td  class='label'>ترتیب:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='instructionno' type='text' class='textbox' id='instructionno'    /></td>
                                
                          <td  class='label'>عنوان:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                          name='HeaderTitle' type='text' class='textbox' id='HeaderTitle'    /></td>
                         
                         <td  class='label'>شرح:</td>
                          <td class='data'><textarea id='Description' colspan='2' name='Description' rows='3' cols='90'  ></textarea></td>
                         
                         
                         </tr>
                         <tr>
                        <td colspan='1' class='label'>فایل</td>
                         
                         <td colspan='4' class='data'><input type='file' name='file1' id='file1'></td>
                         
                         <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                         
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
					if ($instructionno==1 || $instructionno==13) continue;
				
                        $rown++;
                        $deletestr="";
					
                        $permitrolsid = array("1");
                        if (in_array($login_RolesID, $permitrolsid))
                        $deletestr="<a 
                            href='instruction1_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$instructionID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                        
                        
?>                      
                        <tr>
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $instructionno; ?></td>
                            <td><?php echo $HeaderTitle ?></td>
                            <td><a href=<?php print "instruction1_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$instructionID.rand(10000,99999); ?>>
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
