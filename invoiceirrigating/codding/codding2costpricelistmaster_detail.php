<?php 

/*
codding/codding2costpricelistmaster_detail.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/manualcostlist_pluscostlist_list2.php

*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='costpricelistdetail';//جدول ریز فهرست بها

$per_page = 50;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
$currpage=$page;

$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;

if ($login_Permission_granted==0) header("Location: ../login.php");

$currg2id=$_POST['g2id'];

            
if (! $_POST)
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    
    
    $CostPriceListMasterID=$linearray[0];
    $fehrestsmasterID=$linearray[1];
    $type=$linearray[2];

    //print $CostPriceListMasterID;
    
            /*
       producers جدول تولیدکننده
       producersid شناسه تولید کننده
       producers.Title عنوان تولید کننده
       pricelistdetail جدول قیمت های تایید شده
       marks جدول مارک ها
       toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
            ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
            gadget3ID شناسه سطح 3 ابزار
            ProducersID شناسه جدول تولیدکننده
            MarksID شناسه جدول مارک
       toolsmarksid شناسه ابزار و مارک
       gadget3 جدول سطح سوم ابزار
       gadget2 جدول سطح دوم ابزار
       gadget1 جدول سطح اول ابزار
       gadget3id شناسه جدول سطح سوم ابزار
       gadget2id شناسه جدول سطح دوم ابزار
       hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
       PriceListMasterID شناسه لیست قیمت
       price مبلغ
       units جدول واحدهای اندازه گیری کالا
       sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
       operator جدول عملگر های تشکیل دهنده نام کالا
       spec2 مشخصه 2 کالا ها
       spec3 مشخصه 3 کالا ها
       materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
       */
    
    $cond="";

        if ($fehrestsmasterID==2)
        {
            if ($g2id>0)
            {
                $cond.=" and cast(gadget2.code as decimal)=$g2id ";
            }
   
            $sqlselect="select gadget2.title _key,gadget2.Code _value from gadget2
            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and IsCost=1 
            order by _key  COLLATE utf8_persian_ci";      
        }
        else
        {
            if ($g2id>0)
            {
                $cond.=" and cast(fehrestsfasls.fasl as decimal)=$g2id ";
            }
            
            $sqlselect="select fehrestsfasls.Title _key,fehrestsfasls.fasl _value from fehrestsfasls 
            where fehrestsmasterID='$fehrestsmasterID'
            order by _key  COLLATE utf8_persian_ci";
        }
        

    
    if ($fehrestsmasterID==2)
        $sql = "
        SELECT distinct gadget2.title Gadget2Title,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT('',ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         Gadget3Title,units.title UnitsTitle, 
        costpricelistdetail.Price,gadget3.code Code
        , gadget3.Gadget3ID GID,costpricelistdetail.CostPriceListDetailID tblid
        FROM gadget3  
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join units on units.UnitsID=gadget3.unitsID 
        left outer join toolsmarks on toolsmarks.Gadget3ID=gadget3.Gadget3ID
        left outer join invoicedetail on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        LEFT OUTER JOIN costpricelistdetail ON  costpricelistdetail.Gadget3ID = gadget3.Gadget3ID
        and costpricelistdetail.CostPriceListMasterID ='$CostPriceListMasterID' 
        where IsCost=1 $cond
        order by cast(gadget2.code as decimal),gadget3.code
        ";
     else $sql="Select fehrestsfasls.Title Gadget2Title,fehrests.Title Gadget3Title,fehrests.UnitTitle UnitsTitle, pricelistdetailall.Price,fehrests.Code
     ,fehrests.fehrestsID GID,pricelistdetailall.pricelistdetailallID tblid
    from fehrests
    left outer join fehrestsfasls on fehrestsfasls.fehrestsmasterID='$fehrestsmasterID' and substring(fehrests.Code,1,2)=fehrestsfasls.fasl
    left outer join pricelistdetailall on pricelistdetailall.CostPriceListMasterID='$CostPriceListMasterID' 
    and pricelistdetailall.fehrestsID=fehrests.fehrestsID
    
    where fehrests.fehrestsmasterID='$fehrestsmasterID' $cond
    order by cast(fehrests.Code as decimal) LIMIT  $start,$per_page";   
    
			 try 
			  {		
				 $resultwhile = mysql_query($sql);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    //print $sql;
    $pages = 50;



//print $sql;
        $allg2id = get_key_value_from_query_into_array($sqlselect);
        
        
}
if ($_POST)
    { 
        if ($login_RolesID!=1 //مدیر پیگیری
                     && $login_RolesID!=18 //مدیر آب و خاک
                      && $login_RolesID!=19 //مدیر پرونده ها
                      )
                        exit;
        //print 'salamaleyk';
        $fehrestsmasterID=$_POST['fehrestsmasterID'];
        
        print $_POST['CostPriceListMasterID']."_".$fehrestsmasterID;//exit;
        
        if (($_POST['CostPriceListMasterID']>0))
        {
            
            //print "sa0";exit;
        if ($fehrestsmasterID==2)
        {
            
            //print "sa1";exit;
            $i=0;
            $CostPriceListMasterID = $_POST['CostPriceListMasterID'];
            
            while (isset($_POST['rown'.++$i]))
            {
            	$tblid = $_POST['tblid'.$i];
                $GID = $_POST['GID'.$i];
                $Price = $_POST['Price'.$i];
                $Price=str_replace(',', '', $Price);   
                if (strlen($Price)>0)	
                if ($tblid != 0)//update
                {
            		$query = "
            		UPDATE costpricelistdetail SET
            		Price = '" . $Price. "',  
            		SaveTime = '" . date('Y-m-d H:i:s') . "', 
            		SaveDate = '" . date('Y-m-d') . "', 
            		ClerkID = '" . $login_userid . "'
            		WHERE CostPriceListDetailID = " . $tblid . ";";
                    
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
                else //insert
                {
          			$query = "
                      INSERT INTO costpricelistdetail(CostPriceListMasterID,Gadget3ID,Price,SaveTime,SaveDate,ClerkID) 
                      VALUES('" .
                      $CostPriceListMasterID . "', '" . 
                      $GID . "', '" . 
                      $Price . "', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                    
					               
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
             }
            
        }
        else
        {
            //print "sa";exit;
            $i=0;
            $CostPriceListMasterID = $_POST['CostPriceListMasterID'];
                //print $_POST['rown'.++$i];
            while (isset($_POST['rown'.++$i]))
            {
            	$tblid = $_POST['tblid'.$i];
                $GID = $_POST['GID'.$i];
                $Price = $_POST['Price'.$i];
                $Price=str_replace(',', '', $Price);   
                if (strlen($Price)>0)	
                if ($tblid != 0)//update
                {
            		$query = "
            		UPDATE pricelistdetailall SET
            		Price = '" . $Price. "',  
            		SaveTime = '" . date('Y-m-d H:i:s') . "', 
            		SaveDate = '" . date('Y-m-d') . "', 
            		ClerkID = '" . $login_userid . "'
            		WHERE pricelistdetailallID = " . $tblid . ";";
                    
                    //print $query.'<br>';
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
                else //insert
                {
          			$query = "
                      INSERT INTO pricelistdetailall(CostPriceListMasterID,fehrestsID,Price,SaveTime,SaveDate,ClerkID) 
                      VALUES('" .
                      $CostPriceListMasterID . "', '" . 
                      $GID . "', '" . 
                      $Price . "', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                    
                    //print $query.'<br>';
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
                //print $query."<br>";
             }
             
             //exit;        
        }
        //exit;
            
        }
        
         
           // exit;
    
     }


        if (!($CostPriceListMasterID>0)) exit; 

?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت لیست فهرست بها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}

	function selectpage(){
		window.location.href = '<?php echo "codding2costpricelistmaster_detail.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$CostPriceListMasterID.'_'.$fehrestsmasterID.'_'.$type.rand(10000,99999); ?>'+'&page='
                             + document.getElementById('pagination').value
                            +'&g2id=' + document.getElementById('g2id').value;
	}
    
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //alert(number);
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
</script> 
<script language='javascript' src='../assets/jquery.js'></script>
    <!-- /scripts -->
</head>
<body >>

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Serial = "";
						header("Location: ../insert/manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$CostPriceListMasterID.'_3'.rand(10000,99999));
                        
                        
                        
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
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content" >
            <form action="codding2costpricelistmaster_detail.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 



function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


</script>
";  ?>


                <div colspan="4">
                <tr >
                    <td align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-family:'B Nazanin';">ثبت لیست قیمت</td>
                </tr>
                    
                </div>
                        	
                        
                        
    <br />
                            
                            <div style = "text-align:left;"><a  href=<?php 
                            print "../insert/manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$CostPriceListMasterID.'_3'.rand(10000,99999); 
                            
                            ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            
                            <?php
                            print "<td align='left'>";
                            print select_option('g2id','فصل',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
                            print "</td><td align='left'>";
							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage();">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($currpage == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}
                            print "</td>";

                ?>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%"></th>
                        	<th width="5%">کد</th>
                            <th width="15%">فصل</th>
                            <th width="70%">عنوان</th>
                            <th width="5%">واحد</th>
                            <th width="10%">قیمت</th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $cnt=0;
                    $rown=0;
                    $sum=0;
                    if ($resultwhile)
                    while($row = mysql_fetch_assoc($resultwhile))
                    {
                      
                        $rown++;
                        $cnt++;
                       
                        
?>
                        <tr>
                            <td ><div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" 
                            name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 35px' maxlength="6" readonly /></div></td>
                            <td >
                            <label><?php echo $row['Code']; ?></label>
                            </td>
                            
                            <td >
                            <label><?php echo $row['Gadget2Title']; ?></label>
                            </td>
                            
                            <td >
                            <label><?php echo $row['Gadget3Title']; ?></label>
                            </td>
                            
                            <td >
                            <label><?php echo $row['UnitsTitle']; ?></label>
                            </td>
                            
                            
                            <td class="data"><input  onmouseover="Tip(<?php echo '(\''.number_format($row['Price']).'\')'; ?>)" 
                            name="Price<?php echo $cnt; ?>" type="text" class="textbox" id="Price<?php echo $cnt; ?>" value="<?php echo number_format($row['Price']); ?>" 
                            size="15" maxlength="15" onKeyUp="convert('Price<?php echo $cnt; ?>')"  /></div></td>
                            <td class="data"><input name="tblid<?php echo $cnt; ?>" type="hidden" class="textbox" id="tblid<?php echo $cnt; ?>"  value="<?php echo $row['tblid']; ?>"  size="5" /></td>
                            <td class="data"><input name="GID<?php echo $cnt; ?>" type="hidden" class="textbox" id="GID<?php echo $cnt; ?>"  value="<?php echo $row['GID']; ?>" size="5" /></td>
                            
                        </tr><?php

                    }
                    if ($login_RolesID!=1 //مدیر پیگیری
                     && $login_RolesID!=18 //مدیر آب و خاک
                      && $login_RolesID!=19 //مدیر پرونده ها
                      )
                        exit;

?>
                      
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                       <td class="data"><input name="CostPriceListMasterID" type="hidden" class="textbox" id="CostPriceListMasterID"  value="<?php echo $CostPriceListMasterID; ?>" size="5" /></td>
                       <td class="data"><input name="fehrestsmasterID" type="hidden" class="textbox" id="fehrestsmasterID"  value="<?php echo $fehrestsmasterID; ?>" size="5" /></td>
                           
                           
                      <td ><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                      </tr>
                      
                      
                      
                    </tfoot>
                    
                </table>
            
                </form>
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
