<?php 

/*

codding/codding5cityquotaws.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5desert.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

    

if ($_POST )
    {
        /*
        wsquota جدول سهمیه های آبرسانی شهرستان ها
        creditsourceID اعتبار
        CityId شناسه شهر
        val سهمیه اولیه
        val2 سهمیه افزایشی
        
        */
        $id=$_POST['id'];
        $creditsourceID=$_POST['creditsourceID'];
        $val=$_POST['val'];   
		$val2=$_POST['val2'];   
	    $query = "select wsquotaID from wsquota where creditsourceID = '$creditsourceID' and  CityId = '$id'";
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $wsquotaID = $row['wsquotaID'];
        if ($wsquotaID>0)
        mysql_query("update wsquota set val='$val',val2='$val2',
        SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='$login_userid' 
        where wsquotaID='$wsquotaID'");
        
        else
        mysql_query("INSERT INTO wsquota(creditsourceID,CityId, val, val2,SaveTime,SaveDate,ClerkID)
            values ('$creditsourceID','$id','$val','$val2','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');");
           

            
    }
    else $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
     /*
     creditsource اعتبار
     credityear سال
     Title عنوان
     wsquota جدول سهمیه های آبرسانی شهرستان ها
        creditsourceID اعتبار
        CityId شناسه شهر
           val سهمیه اولیه
        val2 سهمیه افزایشی
        tax_tbcity7digit شهرها
     */ 
  $sql = "SELECT creditsource.credityear,creditsource.Title,wsquota.val,wsquota.val2,CityName 
	from `creditsource`  
  left outer join wsquota on wsquota.creditsourceID=creditsource.creditsourceID and wsquota.CityId ='$id'
  left outer join tax_tbcity7digit on tax_tbcity7digit.id='$id'
  order by credityear ";
 
 //print $sql;
 
  try 
      {		
			$result = mysql_query($sql);
	  }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }

 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>سهمیه شهرستان ها</title>

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
            
            <form action="codding5cityquotaws.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
                  <?php
				    $permitrolsid = array("1","5","13","18","19");
                   if (in_array($login_RolesID, $permitrolsid))
                    {
           	 $query="SELECT creditsourceID as _value,Title as _key FROM `creditsource`";
						 $ID1 = get_key_value_from_query_into_array($query);
                         
					        print  
							select_option('creditsourceID','اعتبارات',',',$ID1,0,'','','1','rtl',0,'',$creditsourceID);
					          
                                  
                       print  "
                          <td  class='label'>سهمیه:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='val' type='text' class='textbox' id='val'    /></td>
						  <td  class='label'>اضافه سهمیه:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='val2' type='text' class='textbox' id='val2'    /></td>
                          
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
                        
                        	<th width="30%">اعتبارات </th>
                        	<th width="30%">سال </th>
                        	<th width="30%">سهمیه </th>
							<th width="30%">اضافه سهمیه</th>
                          	<th width="40%">شهرستان </th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>        
                   <?php
                   echo "<td class='data'><input name='id' type='hidden' class='textbox' id='id'  value='$id' /></td>";
                    $rown=0;        
                    while($row = mysql_fetch_assoc($result)){
                        $Title = $row['Title'];
                        $credityear = $row['credityear'];
						$cityquotaval = $row['val'];
                        $cityquotaval2 = $row['val2'];
						$CityName = $row['CityName'];
                        
                        
?>                      
                        <tr>
                            
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $credityear; ?></td>
							<td><?php echo $cityquotaval; ?></td>
                            <td><?php echo $cityquotaval2; ?></td>
						     <td><?php echo $CityName; ?></td>
                            
                            
                            <?php
                            print "
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
