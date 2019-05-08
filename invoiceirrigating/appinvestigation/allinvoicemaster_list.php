<?php 

/*

//appinvestigation/allinvoicemaster_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicantstates.php
/insert/summaryinvoice.php
 
-
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

echo $login_RolesID;

$formname='invoicemaster';
$tblname='invoicemaster';//لیست عناوین پیش فاکتورها

//----------
$uid=$_GET["uid"];
 $file = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
 $linearray = explode('_',$file);
 $id=$linearray[0];//شناسه طرح
 $IDco=$linearray[1];//شناسه شرککت
 $type=$linearray[2];//نوع
 $login_CityId=$linearray[3];//شهر
/*
applicantmaster جدول مشخصات طرح
ApplicantName عنوان پروژه
ApplicantMasterID شناسه طرح
*/
$sql = "SELECT ApplicantName FROM applicantmaster WHERE ApplicantMasterID = '" . $id . "'";
						try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$count = mysql_fetch_assoc(mysql_query($sql));
		$ApplicantName = $count['ApplicantName'];
        
/*
month جدول ماه
invoicemaster لیست عناوین پیش فاکتورها
proposable ارسال به پیشنهاد قیمت
producerapprequest جدول پیشنهاد قیمت
producers جدول تولید کنندگان
pricelistmaster جدول لیست قیمت
applicantmaster جدول مشخصات طرح
year جدول سال ها
YearID شناسه سال طرح
ApplicantMasterID شناسه طرح
*/        
$sql = " 
SELECT distinct concat(year.Value ,' ',month.Title) fb
,invoicemaster.*
,case invoicemaster.proposable when 1 then 'پیشنهاد قیمت' else '' end proposableTitle
,producerapprequest.ApplicantMasterID ApplicantMasterIDp,producers.Title as PTitle
,producers.pipeproducer
FROM invoicemaster 
left outer join producers on producers.ProducersID=invoicemaster.ProducersID
left outer join producerapprequest on producerapprequest.ApplicantMasterID='$id'
left outer  join pricelistmaster on pricelistmaster.pricelistmasterid=invoicemaster.pricelistmasterid
left outer  join year on year.YearID=pricelistmaster.YearID
left outer  join month on month.MonthID=pricelistmaster.MonthID
        
where  invoicemaster.ApplicantMasterID = '$id' 
ORDER BY invoicemaster.Serial ;";

//print $sql;
$result = mysql_query($sql);
						try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }



        
?>
<!DOCTYPE html>
<html>
<head>
  	<title>ليست پيش فاکتورهاي طرح</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
    
 
    

    <script>
    
    </script>
    <!-- /scripts -->
</head>
<body onload="add();">

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
            
                <table width="95%" align="center">
                    <tbody>
                        <td></td>
                            <h1 align="center">  لیست پیش فاکتور/لیست لوازم های <?php print $ApplicantName; ?> </h1>
                        
                            <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxSerial; ?>"/>
                            <INPUT type="hidden" id="txtinvoicedate" value="<?php print gregorian_to_jalali(date('Y-m-d')); ?>"/>
                            <INPUT type="hidden" id="txtApplicantMasterID" value="<?php print $id; ?>"/>
                            <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                            <INPUT type="hidden" id="txtlogin_OperatorCoID" value="<?php print $login_OperatorCoID; ?>"/>
                            
                           
                            <div style = "text-align:left;">
                            <!-- button title='پیش فاکتور جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button --> 
                          <a href=<?php print "applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDco."_".$type."_".$login_CityId.rand(10000,99999) ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a>
                            
                          </div>
                            
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                    
                    
                        <tr>
						    <td width= '3%'>سريال</td>
                            <td width= '20%'>عنوان پیش فاکتور/لیست لوازم</td>
                            <td width= '10%'>صادر کننده</td>
                            <td width= '10%'>لیست قیمت</td>
                            <td width= '10%'>تاریخ</td>
                            <td width= '3%'>تعداد ردیف</td>
							<td width= '5%'>ارزش افزوده</td>
							<td width= '5%'>هزینه جانبی</td>
							<td width= '5%'>تخفیف</td>
							<td width= '5%'>وضعیت</td>
							
                            <td width= '10%'>توضیحات</td>
                    		<td width= '5%'> </td>
							<td width= '5%'> </td>
							
                        </tr>
                        
                    </thead>
                   <tbody><?php
                    
                    
                    
                    while($row = mysql_fetch_assoc($result)){

                        $Serial = $row['Serial'];
                        $ID = $row['InvoiceMasterID'];
                        $Title = $row['Title'];
                        $PTitle = $row['PTitle'];
                        $Description = $row['Description'];
                        $Rowcnt = $row['Rowcnt'];
                        $InvoiceDate = $row['InvoiceDate'];
                        $bf = $row['fb'];
						$taxless = $row['taxless'];
						$TransportCost = $row['TransportCost'];
						$Discont = $row['Discont'];		
						$proposableTitle = $row['proposableTitle'];

?>
                        <tr>
                            <td><?php echo $Serial ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $Title; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $PTitle; ?></td>
                            <td><?php echo $bf; ?></td>
                            <td><?php echo $InvoiceDate; ?></td>
                            <td><?php echo $Rowcnt; ?></td>
							 <td><?php echo $taxless; ?></td>
							 <td><?php echo $TransportCost; ?></td>
							 <td><?php echo $Discont; ?></td>
							 <td><?php echo $proposableTitle; ?></td>
							
                            <td><?php echo $Description."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>"; 
                            echo $login_RolesID;
                       if (in_array($login_RolesID, array("1","13","14","19"))) {
                            ?>
                            <td><a href=<?php print "../insert/invoicedetail_list.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 50%;' src='../img/search.png' title=' ریز اقلام پیش فاکتور/لیست لوازم '></a></td>
                            <td><a href=<?php print '../insert/'.$formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 50%;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                            <td><?php 
                            if (!($row['InvoiceMasterIDmaster']>0))
                            
                            print "<a 
                            href='../insert/".$formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 50%;' src='../img/delete.png' title='حذف'> </a>"; ?>
                            </td>
                        <?php
						}
                            echo "</tr>";

                    }

?>
                    </tbody>
                    
                      
                </table>
                
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php 
            
            
            include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
