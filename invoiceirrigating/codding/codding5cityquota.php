<?php 

/*

codding/codding5cityquota.php

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
        cityquota جدول سهمیه های  شهرستان ها
        creditsourceID اعتبار
        CityId شناسه شهر
        val سهمیه اولیه
        val2 سهمیه افزایشی
        
        */
        $id=$_POST['id'];
        $YearID=$_POST['YearID'];
        $val=$_POST['val'];   
		$valnum=$_POST['valnum'];   
        $val2=$_POST['val2'];   
		$valnum2=$_POST['valnum2']; 
        $query = "select cityquotaID from cityquota where YearID = '$YearID' and  CityId = '$id'";
      
	   try 
		  {		
				$result = mysql_query($query);
		  }
		  //catch exception
		  catch(Exception $e) 
		  {
			echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
		  }

        $row = mysql_fetch_assoc($result);
        $cityquotaID = $row['cityquotaID'];
        if ($cityquotaID>0)
        mysql_query("update cityquota set val='$val',valnum='$valnum',val2='$val2',valnum2='$valnum2',
        SaveTime='".date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='$login_userid' 
        where cityquotaID='$cityquotaID'");
        
        else
        mysql_query("INSERT INTO cityquota(YearID,CityId, val,valnum, val2,valnum2,SaveTime,SaveDate,ClerkID)
            values ('$YearID','$id','$val','$valnum','$val2','$valnum2','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');");
           

            
    }
    else $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
           /*
     cityquota جدول سهمیه های  شهرستان ها
        CityId شناسه شهر
           val سهمیه اولیه
        val2 سهمیه افزایشی
        tax_tbcity7digit شهرها
     */ 
  $sql = "SELECT year.Value,cityquota.val,valnum,cityquota.val2,valnum2,CityName from year 
  left outer join cityquota on cityquota.YearID=year.YearID and cityquota.CityId ='$id'
  left outer join tax_tbcity7digit on tax_tbcity7digit.id='$id'
  order by Value ";
 
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
            
            <form action="codding5cityquota.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
                  <?php
				    $permitrolsid = array("1","5","13","18","19");
                   if (in_array($login_RolesID, $permitrolsid))
                    {
                         $query="SELECT YearID as _value,Value as _key FROM `year` 
                         ORDER BY year.Value DESC";
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td id='YearIDlbl'  class='label'>سال:</td>".
                         select_option('YearID','',',',$ID,0,'','','1','rtl',0,'',$SelectedYearID,'','50');
                         
                       print  "
                          <td  class='label'>سهمیه تحت فشار:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='val' type='text' class='textbox' id='val'    /></td>
						  <td  class='label'>تعداد تحت فشار:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='valnum' type='text' class='textbox' id='valnum'    /></td>
                          
                          <td  class='label'>سهمیه کم فشار:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='val2' type='text' class='textbox' id='val2'    /></td>
						  <td  class='label'>تعداد کم فشار:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='valnum2' type='text' class='textbox' id='valnum2'    /></td>      
                         
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
                        
                        	<th width="30%">سال </th>
                        	<th width="30%">سهمیه تحت فشار</th>
							<th width="30%">تعداد تحت فشار</th>
                        	<th width="30%">سهمیه کم فشار</th>
							<th width="30%">تعدادکم فشار</th>
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
                        $Value = $row['Value'];
                        $cityquotaval = $row['val'];
						$cityquotavalnum = $row['valnum'];
                        $cityquotaval2 = $row['val2'];
						$cityquotavalnum2 = $row['valnum2'];
                        $CityName = $row['CityName'];
                        
                        
?>                      
                        <tr>
                            
                            <td><?php echo $Value; ?></td>
                            <td><?php echo $cityquotaval; ?></td>
							<td><?php echo $cityquotavalnum; ?></td>
                            <td><?php echo $cityquotaval2; ?></td>
							<td><?php echo $cityquotavalnum2; ?></td>
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
