<?php 
/*
reorts/reports_maxsale.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php //include('Chartsql.php'); ?>
<?php  
include('../Chart.php');
		
	
if ($_POST)
{

   // valin = valin.replace(/ك/g, \"ک\"); 

    $dope=$_POST['dope'];    
       $healthy = array("ی", "ك");
       $yummy   = array("ي", "ک");
    $ftextboxsearch=str_replace($healthy, $yummy, $_POST['ftextboxsearch']);
    $mtextboxsearch=str_replace($healthy, $yummy, $_POST['mtextboxsearch']);
    $ltextboxsearch=str_replace($healthy, $yummy, $_POST['ltextboxsearch']);
    $nottextboxsearch=str_replace($healthy, $yummy, $_POST['nottextboxsearch']);   
	
	if ($_POST['marksid']>0) {$marksid=$_POST['marksid'];}
	if ($_POST['producTitle']>0) {$producTitle=$_POST['producTitle'];}
    if ($_POST['product']>0) {$product=$_POST['product'];}
	
    $Datefroml=compelete_date($_POST['Datefrom']);
    $Datetol=compelete_date($_POST['Dateto']);
    
    $Datefrom=$_POST['Datefrom'];
    $Dateto=$_POST['Dateto'];	
//	$ftextboxsearch='';$mtextboxsearch='لوله';$ltextboxsearch='';$nottextboxsearch='';$marksid='';$producTitle='';
//	$product=1;$Datefroml='/0/0';$Datetol='1395/04/28';$Dateto='1395/04/28';$login_ProducersID=0;
if  ($login_RolesID==1 || $login_RolesID==18)
{
	$sql=searchgadget_sql($ftextboxsearch,$mtextboxsearch,$ltextboxsearch,$nottextboxsearch
	,$marksid,$producTitle,$product,$Datefroml,$Datetol,$Dateto,$login_ProducersID,$dope);
    
 try 
    {		
        $result = mysql_query($sql);
    $sumnumber=chartgadget_sqle($sql);  
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }   
    
}

	//	print $ftextboxsearch.',<br>'.$mtextboxsearch.',<br>'.$ltextboxsearch.',<br>'.$nottextboxsearch.',<br>'.$marksid.',<br>'.$producTitle.',<br>'.$product.',<br>'.$Datefroml.',<br>'.$Datetol.',<br>'.$Dateto.',<br>'.$login_ProducersID;
	//	exit;
	  
	$ID5[' ']=' ';
	//$ID6[' ']=' ';

	while($row = mysql_fetch_assoc($result)){
	if ($row['marksid']<>128){
	$ID5[trim($row['markstitle'])]=trim($row['marksid']);
   // $ID6[trim($row['producTitle'])]=trim($row['product']);
		}
	}
//if ($dasrow)

mysql_data_seek( $result, 0 );
 

	}

	
	
?>
<!DOCTYPE html>
<html>
<head>
  	<title>گزارش میزان استفاده کالاها در طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#Datefrom, #simpleLabel").persiandatepicker();   
                $("#Dateto, #simpleLabel").persiandatepicker();   
				
            });
        
        
    </script>
    
</head>
<body>

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
                <form action="reports_maxsale.php" method="post" >
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">گزارش میزان استفاده کالاها در طرح ها</h1>
                         <br></br>
                        </tr>
                        
                      <tr>
                      <td class="label">ابتدای نام کالا</td>
                      <td class="data"><input name="ftextboxsearch" type="text" class="textbox" 
                      id="ftextboxsearch" value="<?php echo $ftextboxsearch; ?>"   size="15" maxlength="50" /></td>
                      
                      <td class="label">میانه نام کالا</td>
                      <td class="data"><input name="mtextboxsearch" type="text" class="textbox" 
                      id="mtextboxsearch" value="<?php echo $mtextboxsearch; ?>"   size="15" maxlength="50" /></td>
                      
                      <td class="label">انتهای نام کالا</td>
                      <td class="data"><input name="ltextboxsearch" type="text" class="textbox" 
                      id="ltextboxsearch" value="<?php echo $ltextboxsearch; ?>"   size="15" maxlength="50" /></td>
                      
                      <td class="label">عدم وجود عبارت</td>
                      <td class="data"><input name="nottextboxsearch" type="text" class="textbox" 
                      id="nottextboxsearch" value="<?php echo $nottextboxsearch; ?>"   size="15" maxlength="50" /></td>
                      
                     
                     </tr>
                     
                      
                      <tr>
                      <td  class="label">از تاریخ:</td>
                      <td  class="data"><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" value="<?php echo $Datefrom ?>" size="10" maxlength="10" /></td>
                        <span id="span1"></span>
                     <td class="label">تا تاریخ:</td>
                      <td class="data"><input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" /></td>
                     <span id="span2"></span>
                     

		<td class="data">گروه کالا:
		<?php 
		
		
$query="
select 'لوله پلی اتیلن' _key,1 as _value union all
select 'نوار تیپ' _key,2 as _value union all 
select 'فیلتراسیون' _key,3 as _value union all
select 'پمپ و الکتروموتور' _key,4 as _value union all
select 'دستگاه بارانی' _key,5 as _value union all
select 'سایر اتصالات' _key,6 as _value";

$product = get_key_value_from_query_into_array($query);

if (!$_POST['product'])
    $ID6val=7;
else $ID6val=$_POST['product'];
	             
		print select_option('product','',',',$product,0,'','','1','rtl',0,'',$ID6val,'','100');
		 print select_option('marksid','مارک:',',',$ID5,0,'','','1','rtl',0,'',$marksid,'','100');
         
         
         
         
    $ID1[' ']='0';
    $ID1[' کالاهای مطالعاتی  در فاز مطالعات']='1';
    $ID1['کالاهای مطالعاتی  در فاز اجرا']='2';
    
		 print select_option('dope','',',',$ID1,0,'','','1','rtl',0,'',$dope,'','100');
         
         ?>
		
		
					     </td>
<?php if ($sumnumber==1 || $sumnumber==12) {?>						 
						 
						         <td class=\"f7_font$b'\"><a  target='_blank' href='../temp/producepercent.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار حجم کالا'></a></td>
        <?php } ?>         
<?php if ($sumnumber==2 || $sumnumber==12) {?>						 
						 
						         <td class=\"f7_font$b'\"><a  target='_blank' href='../temp/producenum.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار تعداد پیش فاکتور'></a></td>
        <?php } ?>         
		
</tr>

                     
                      <tr>
                                  
        
                      <td  colspan="4"></td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="جستجو" /></td>
                     
                     </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="2%">دیف</th>
                        	<th width="40%">عنوان</th>
                            <th width="15%">مارک</th>
                            <th width="10%">تعداد</th>
                            <th width="5%">واحد</th>
                            <th width="10%">ضریب</th>
                            <th width="10%">واحد</th>
						    <th width="10%">حجم</th>
                            <th width="10%"></th>
                      
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    $rown=0;
					$sum=0;$sumu=0;
					
                    //if (($login_usermaxDatepermision>=date('Y-m-d') && $login_userdebt>=0)||($login_designerCO==1 || $login_RolesID==13 || $login_RolesID==18))
						if ($login_isfulloption==1)
                    while($row = mysql_fetch_assoc($result))
                    {
                        $rown++;
                         $sum=$row['cnt']+$sum; 
						 $sumu=$row['UnitsCoef2']*$row['cnt']+$sumu; 
						 
?>                      
                        <tr>
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $row['fulltitle']; ?></td>
                            <td><?php echo $row['markstitle']; ?></td>
							
                            <td><?php echo $row['cnt']; ?></td>
                            <td><?php echo $row['unitstitle']; ?></td>
						
							<td><?php echo $row['UnitsCoef2']; ?></td>
					        <td><?php echo $row['unitstitle2']; ?></td>
							<td><?php echo round($row['UnitsCoef2']*$row['cnt']); ?></td>
					        
                    
						
                <td><a target="_blank" href=<?php print "../tools/toolsmarksaving_level5_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $row['gadget3id'].'_'.$row['ProducersID'].rand(10000,99999); ?>>
                            <img style = 'width: 20px;' src='../img/search.png' title='مشاهده گردش'>  </a></td>
                        </tr>
						<?php } ?>
                   <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $sum; ?></td>
							 <td></td>
                    		 <td></td>
                    		 <td></td>
                            <td><?php echo round($sumu); ?></td>
							
							
							
                        </tr>
						 
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
