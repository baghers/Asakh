<?php 

/*

insert/foundation_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/foundation_lis.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $appfoundationID=$linearray[0];//شناسه سازه
    $ApplicantMasterID=$linearray[1];//شناسه طرح
 
       
    //print $ApplicantMasterID;
$query="
select 'فونداسیون' _key,1 as _value union all
select 'اتاقک پمپاژ' _key,2 as _value union all 
select 'حوضچه شیر' _key,3 as _value union all
select 'حوضچه پمپاژ' _key,4 as _value union all
select 'استخر' _key,5 as _value union all
select 'لوله گذاری' _key,6 as _value";
$IDsel = get_key_value_from_query_into_array($query);
/*
appfoundation سازه ها
appfoundationID شناسه سازه
len طول
width عرض
heigh ارتفاع
thickness ضخامت
number تعداد
ApplicantMasterID شناسه طرح
manuallistpriceall فهارس بها
*/
$sql = "select distinct appfoundation.farmerduty,appfoundation.appfoundationID,Title,len,width,heigh,thickness,appfoundation.number,groupcode,
        case groupcode 
        when 1 then 'فونداسیون'
        when 2 then 'اتاقک پمپاژ'
        when 3 then 'حوضچه شیر'
        when 4 then 'حوضچه پمپاژ'
        when 5 then 'استخر' end grouptitle,
        case ifnull(manuallistpriceall.appfoundationID,0) when 0 then 0 else 1 end gardesh
         from appfoundation 
        left outer join manuallistpriceall on manuallistpriceall.appfoundationID=appfoundation.appfoundationID and 
        manuallistpriceall.ApplicantMasterID=appfoundation.ApplicantMasterID
        WHERE appfoundation.appfoundationID = '" . $appfoundationID . "'
        ";

           	 	 		  	try 
								  {		
									   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

//print $query;
    $row = mysql_fetch_assoc($result);
    $groupcode=$row['groupcode'];
    $Title=$row['Title']; 
    $len=$row['len']; 
    $width=$row['width'];
    $heigh=$row['heigh']; 
    $thickness=$row['thickness']; 
    $number=$row['number']; 
    $farmerduty= $row['farmerduty'];
    if ($farmerduty>0)      
       $farmerdutyselected="checked";
}

$register = false;

if ($_POST){
    
    $ApplicantMasterID=$_POST['ApplicantMasterID'];
    $appfoundationID=$_POST['appfoundationID'];
    
    $IDsel=$_POST['IDsel'];
    $Title=$_POST['Title'];
    $len=$_POST['len'];
    $width=$_POST['width'];
    $heigh=$_POST['heigh'];
    $thickness=$_POST['thickness'];
    $number=$_POST['number'];  
   	$_POST['farmerduty'] = $_POST['farmerduty'];
    $farmerduty= $_POST['farmerduty'];
    

/*
appfoundation سازه ها
appfoundationID شناسه سازه
len طول
width عرض
heigh ارتفاع
thickness ضخامت
number تعداد
ApplicantMasterID شناسه طرح
manuallistpriceall فهارس بها
*/
                                              
		$query = "
		UPDATE appfoundation SET
		groupcode = '" . $IDsel . "', 
		Title = '" . $Title . "', 
		len = '" . $len . "',
		width = '" . $width . "',
		heigh = '" . $heigh . "',
		thickness = '" . $thickness . "',
		number = '" . $number . "',
		farmerduty = '" . $farmerduty. "',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
        WHERE appfoundation.appfoundationID = '$appfoundationID'";
        
      
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
  	<title>تصحیح اطلاعات </title>
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
						$Code = "";
						$YearID = "";
                        
                                header("Location: "."foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.rand(10000,99999)); 
                        
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
                <form action="foundation_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print 
                    "foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.'_1'.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            <th colspan="1" ></th>
                        	<th >گروه</th>
                        	<th >عنوان</th>
                        	<th >طول</th>
                        	<th >عرض</th>
                            <th >ارتفاع</th>
                            <th >ضخامت</th>
                            <th >تعداد</th>
                            <th ></th>
                            
                             </tr>			  
                            <?php print 
                            "<td class='data'><input name='ApplicantMasterID' type='hidden' id='ApplicantMasterID' value='$ApplicantMasterID'  /></td>".
                            select_option('IDsel','',',',$IDsel,0,'','','1','rtl',0,'',$groupcode,"",'100').
                            "<td class='data'><input name='Title' type='text' id='Title' size='50' value='$Title' /></td>
                            <td class='data'><input name='len' type='text' id='len' size='5' value='$len' /></td>
                            <td class='data'><input name='width' type='text' id='width' size='5' value='$width'  /></td>
                            <td class='data'><input name='heigh' type='text' id='heigh' size='5' value='$heigh'  /></td>
                            <td class='data'><input name='thickness' type='text' id='thickness' size='5' value='$thickness'  /></td>
                            <td class='data'><input name='number' type='text' id='number' size='5' value='$number'  /></td>"
                            ;
                            
                            ?>
                            
                            
                     <tr>
                      <td class="data"><input name="ApplicantMasterID" type="hidden" class="textbox" id="ApplicantMasterID"  value="<?php echo $ApplicantMasterID; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="appfoundationID" type="hidden" class="textbox" id="appfoundationID"  value="<?php echo $appfoundationID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     <tr>
                     <td colspan="1" ></td>
                      <td class="label">در تعهد پیمانکار/متقاضی:</td>
                      <td class="data"><input name="farmerduty" type="checkbox" id="farmerduty"  value="1" <?php echo $farmerdutyselected; ?> /></td>
                     </tr>
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح" /></td>
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