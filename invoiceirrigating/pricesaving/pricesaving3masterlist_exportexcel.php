<?php 
/*
pricesaving/pricesaving3masterlist_exportexcel.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesaving3masterlist.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
$PriceListMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
if (! $_POST)
{
//pricelistmaster جدول لیس قیمت
$query = "SELECT * FROM pricelistmaster WHERE PriceListMasterID = '" . $id . "';";
$result = mysql_query($query);
//print $query;
    $resquery = mysql_fetch_assoc($result);
	$SelectedYearID = $resquery["YearID"];//سال
	$SelectedMonthID = $resquery["MonthID"];  //ماه
 
 /*
month جدول ماه
year جدول سال
*/      
        
    $sql = "SELECT CONCAT(CONCAT(CONCAT(' لیست-قیمت-',monthprice.Title),'-'),yearprice.Value) pr 
    FROM pricelistmaster 
    left outer join month as monthprice on monthprice.MonthID=pricelistmaster.MonthID  
    left outer join year as yearprice on yearprice.YearID=pricelistmaster.YearID 
    where pricelistmaster.PriceListMasterID ='$PriceListMasterID'";
    						try 
								  {		
									  	$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $row = mysql_fetch_assoc($result);
    $Title=$row['pr'];
}
$register = false;

if ($_POST)
{        
    //$allowedExts = array(".csv");
    //$temp = explode(".", $_FILES["file"]["name"]);
    //$extension = end($temp);
    $register=false;
    if (1)
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            return;
        }
        else
        {
            //echo "Upload salam: " . $_FILES["file"]["name"] . "<br>";
            //echo "Type: " . $_FILES["file"]["type"] . "<br>";
            //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

            if (file_exists("../temp/" . $_FILES["file"]["name"]))
            {
                echo $_FILES["file"]["name"] . " already exists. ";
                return;
            }
            else
            {
                move_uploaded_file($_FILES["file"]["tmp_name"],
                "../temp/" . $_FILES["file"]["name"]);
                //echo "Stored in: " . "../temp/" . $_FILES["file"]["name"];
                $csvfile="..\\temp\\". $_FILES["file"]["name"];
                
            }
        }
    }
    else
    {   
        echo "Invalid file";
        return;
    }
    ///////file uploaded
    
    
    
    
    $fieldseparator = "\t";
    $lineseparator = "\n";

    $addauto = 0;
    $save = 1;
    //$outputfile = "..\\temp\\output.sql";
    /********************************************************************************************/
    if(!file_exists($csvfile)) 
    {
        echo "File not found. Make sure you specified the correct path.\n";
        exit;
    }
    $file = fopen($csvfile,"r");
    if(!$file) 
    {
        echo "Error opening data file.\n";
        exit;
    }
    $size = filesize($csvfile);
    if(!$size) 
    {
        echo "File is empty.\n";
        exit;
    }
    $csvcontent = fread($file,$size);
    fclose($file);
    $lines = 0;
    $queries = "";
    $linearray = array();
    foreach(split($lineseparator,$csvcontent) as $line) 
    {
        $lines++;
        $line = trim($line,$fieldseparator);
        $line = str_replace("\r","",$line);
        $linearray = explode($fieldseparator,$line);
        
        //pricelistdetail ریز قیمت تایید شده
        mysql_query("delete from pricelistdetail WHERE PriceListMasterID ='$_POST[PriceListMasterID]' and toolsmarksID='$linearray[0]';");
        if ($linearray[4]>0)
        {
            $query = " insert into pricelistdetail (PriceListMasterID,toolsmarksID,Price,SaveTime,SaveDate,ClerkID) 
            values('$_POST[PriceListMasterID]', '$linearray[0]', '$linearray[4]','". date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
            $queries .= $query . "\n";
           
								try 
								  {		
									  	 mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        }
                
        
    }
    //print $queries;
    unlink($csvfile);
    
        //echo "Found a total of $lines records in this csv file.\n";
    $register=true;
    
    
}
?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح لیست قیمت</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST)
                {
					if ($register){
						echo '<p class="note">عملیات با موفقيت انجام شد</p>';
                        return;
						//header("Location: pricesaving3masterlist.php");
                        
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
                <form action="pricesaving3masterlist_exportexcel.php" method="post" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "pricesaving3masterlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$PriceListMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                         
                    <tr >
                    <td colspan="5" align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-family:'B Nazanin';"> <?php echo $Title; ?> </td>
                    </tr>
                    
                    
                     <tr>
                      <td class="data"><input name="PriceListMasterID" type="hidden" class="textbox" id="PriceListMasterID"  value="<?php echo $PriceListMasterID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                    
                     <tr>
                     <td class="data"><label for="file">Filename:</label><input type="file" name="file" id="file"></td>
                     </tr>
                    
 
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ارسال" /></td>
                            
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