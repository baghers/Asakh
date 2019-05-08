<?php 
/*
tools/tools_request.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools_request_delete.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
if ($login_Permission_granted==0) header("Location: ../login.php");

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
       units جدول واحدهای اندازه گیری کالا
       sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
       materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
       toolsrequest جدول ابزارهای درخواستی
       toolsrequestID شناسه ابزار درخواستی
    */
    
if ($_POST && ($login_ProducersID>0))
{
        $Title=$_POST['Title'];
        $MarkTitle=$_POST['MarkTitle'];
        $Unit=$_POST['unitsID'];
        $Description=$_POST['Description'];
        
        
        
            $sql="INSERT INTO toolsrequest(ProducersID,Title, MarkTitle,unitsID,Description,state,
            size,sizeunitsID,fesharzekhamathajm,fesharzekhamathajmUnitsID,MaterialTypeID,SaveTime,SaveDate,ClerkID)
            values ('$login_ProducersID','$Title','$MarkTitle','$Unit','$Description','1',
            '$_POST[size]','$_POST[sizeunitsID]','$_POST[fesharzekhamathajm]','$_POST[fesharzekhamathajmUnitsID]','$_POST[MaterialTypeID]',
            '".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";

        try 
								  {		
									    mysql_query($sql);  
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  } 
            
}

  
  $sql = "SELECT producers.title producerstitle,toolsrequestID,toolsrequest.Title,MarkTitle,units.title unitstitle ,Description,case state when 1 then 'ثبت درخواست' else 'کالا ثبت شد' end  state
  ,size,sizeunits.title sizeunitstitle,fesharzekhamathajm,fesharzekhamathajmUnits.title fesharzekhamathajmUnitstitle,materialtype.title MaterialTypetitle 
FROM toolsrequest
left outer join producers on producers.producersid=toolsrequest.producersid
left outer join units on units.unitsID=toolsrequest.unitsID
left outer join sizeunits on sizeunits.sizeunitsID=toolsrequest.sizeunitsID
left outer join sizeunits fesharzekhamathajmUnits on fesharzekhamathajmUnits.sizeunitsID=toolsrequest.fesharzekhamathajmUnitsID
left outer join materialtype on materialtype.MaterialTypeID=toolsrequest.MaterialTypeID
where toolsrequest.ProducersID='$login_ProducersID' 
order by toolsrequest.Title COLLATE utf8_persian_ci";
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
  	<title>لیست درخواست های کالا</title>

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
    function farsireplace(valin)
{
    valin.trim();
    valin = valin.replace(/ي/g, "ی"); 
    valin = valin.replace(/ك/g, "ک"); 
    return valin;
}

 function FilterComboboxes(Url)
                {
                    var Gadget3ID=document.getElementById('gadget3ID').value;
                    //alert(Gadget3ID);
                    
                    $.post(Url, {Gadget3ID:Gadget3ID}, function(data){
                    $('#divpmid').html(data.selectstr3);
                       }, 'json');
                }
                
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
            
            <form action="tools_request.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
               </div>
                   
                          
                        </tr>
                   </tbody>
                </table>
				
                <table id="records" width="15%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                            <th width="20%" height="10%" align="center" valign="center">شرح کالا</th>
                        	<th width="5%" height="10%" align="center">سایز</th>
							<th width="5%" height="10%"> واحد سايز</th>
                            <th colspan="2" width="15%" height="10%">فشار،ضخامت،حجم،  زاویه،طول،و...</th>
                            <th width="5%" height="10%">نوع مواد</th>
                            <th width="5%" height="10%" align="center">مارک</th>
                            <th width="5%" height="10%">واحد کالا</th>
                            <th width="20%" height="10%" align="center">توضیحات</th>
                            <th width="10%" height="10%"></th>
                            <th width="5%" height="10%"></th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>        
                   <?php
                   $query='select unitsID as _value,Title as _key from units order by Title   COLLATE utf8_persian_ci';
                    $allunitsID = get_key_value_from_query_into_array($query);
                    
                    $query='select sizeunitsID as _value,Title as _key from sizeunits order by Title  COLLATE utf8_persian_ci';
                    $allsizeunitsID = get_key_value_from_query_into_array($query);
                    
                    $query='select materialtypeid as _value,Title as _key from materialtype order by Title COLLATE utf8_persian_ci';
                    $allmaterialtypeid = get_key_value_from_query_into_array($query);
                    $heightcel=24.5;
					$heightcel=$heightcel+px;
                   print    "
                            <td class='data'><input name='Title' type='text' class='textbox'  id='Title'  style='width: 240px; height:$heightcel;'  /></td>
                            <td class='data'><input name='size' type='text' class='textbox' id='size'  style='width: 80px; height:$heightcel;' /></td>
                            ".select_option('sizeunitsID','',',',$allsizeunitsID,0,'','','1','rtl',0,'',0,'','60','0','60px')."
                            <td class='data'><input name='fesharzekhamathajm' type='text' class='textbox' id='fesharzekhamathajm'  style='width: 60px; height:$heightcel;' /></td>
                            ".select_option('fesharzekhamathajmUnitsID','',',',$allsizeunitsID,0,'','','1','rtl',0,'',0,'','60').
                              select_option('MaterialTypeID','',',',$allmaterialtypeid,0,'','','1','rtl',0,'',5,'','65',' ',' ')."
	                        <td class='data'><input name='MarkTitle' type='text' class='textbox' id='MarkTitle'  style='width: 100px; height:$heightcel;' /></td>
                             ".select_option('unitsID','',',',$allunitsID,0,'','','1','rtl',0,'',0,'','60')." 
							
							
							
                            <td class='data'><input name='Description' type='text' class='textbox' id='Description'  style='width: 120px; height:$heightcel;' /></td>
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' style= 'width: 40px;height:$heightcel;' /></td>
                            ";
                    while($row = mysql_fetch_assoc($result)){
                        $ID = $row['toolsrequestID'];
                        $Title = $row['Title'];
                        $MarkTitle = $row['MarkTitle'];
                        $Unit = $row['unitstitle'];
                        $Description=$row['Description'];
                        $state=$row['state'];
                        
?>                      
                        <tr>
                            
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $row['size']; ?></td>
                            <td><?php echo $row['sizeunitstitle']; ?></td>
                            <td><?php echo $row['fesharzekhamathajm']; ?></td>
                            <td><?php echo $row['fesharzekhamathajmUnitstitle']; ?></td>
                            <td><?php echo $row['MaterialTypetitle']; ?></td>
                            <td><?php echo $MarkTitle; ?></td>
                            
                            <td><?php echo $Unit; ?></td>
                            <td><?php echo $Description; ?></td>
                            <td><?php echo $state; ?></td>
                            <td><a 
                            href=<?php print "tools_request_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 125%;' src='../img/delete.png' title='حذف'> </a></td>
                        </tr><?php

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
