<?php 

/*

//appinvestigation/guarantee_level2_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/guarantee_level1_list.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='guarantee_level2_list';
$cond="";
if ($login_RolesID==3) //تولیدکننده
 $cond="and ProducersID='$login_ProducersID'";
if ($login_RolesID==2)//مجری 
$cond="where operatorcoID='$login_OperatorCoID'";
if ($login_Permission_granted==0 || $login_isfulloption!=1) header("Location: ../login.php");

if ($login_Permission_granted==0) header("Location: ../login.php");
$type = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;

if ($type==1 || $login_RolesID==3)//فروشندگان
{
            /*
            producers جدول تولید کننده
            producersID شناسه تولید کننده
            Title عنوانن شرکت
            guarantee جدول تضامین
            guaranteeUp سقف ضمانت
            guaranteepayval مقدار ضمانت سپرده شده
            guaranteeNo شماره ضمانت
            guaranteeDescription شرح
            guaranteeExpireDate تاریخ اعتبار
            prjtypeid نوع پروژه
            */                
    $sql = "SELECT '$type' type,ProducersID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeDescription,guaranteeExpireDate,'' prjtypeTitle
			
			FROM producers 
            where ProducersID<>142 $cond
			union all
			SELECT '$type' type,producers.ProducersID ID,producers.Title,guarantee.guaranteeUp,guarantee.guaranteepayval,guarantee.guaranteeNo
			,guarantee.guaranteeDescription,guarantee.guaranteeExpireDate,prjtype.Title prjtypeTitle
			FROM producers 
            left outer join guarantee on producers.producersid=CoID and CoType=1 and ifnull(prjtypeid,0)>0
			left outer join prjtype on prjtype.prjtypeid=guarantee.prjtypeid 
			
			where ProducersID<>142 $cond
			
            order by guaranteeExpireDate desc,guaranteepayval desc,Title COLLATE utf8_persian_ci ";
}
else if ($type==2 || $login_RolesID==2)//شرکت های مجری
{   /*
            operatorco مجریان
            operatorcoID شناسه شرکت 
            Title عنوانن شرکت
            guarantee جدول تضامین
            guaranteeUp سقف ضمانت
            guaranteepayval مقدار ضمانت سپرده شده
            guaranteeNo شماره ضمانت
            guaranteeDescription شرح
            guaranteeExpireDate تاریخ اعتبار
            prjtypeid نوع پروژه
            prjtype انواع پروژه
            */
    $sql = "SELECT '$type' type,operatorcoID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeDescription,guaranteeExpireDate FROM operatorco 
            $cond
            order by guaranteeExpireDate desc ,Title COLLATE utf8_persian_ci ";
}


							try 
							  {		
								$result = mysql_query($sql.$login_limited);
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
  	<title>تضمین</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    </script>
    <!-- /scripts -->
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
                <table width="95%" align="center">
                    <tbody>
                        <td></td>
                        <h1 align="center" class="f14_fontb"> تضامین 
                            
                            <a  href=<?php print "guarantee_level1_list.php";?>>
								<img align="left" style = "width: 2%;" src="../img/Return.png" title='بازگشت' >
							</a>
                          
                        </h1>
                        
                            
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th  class="f14_fontb">ردیف</th>
                            <th  class="f14_fontb">عنوان</th>
                            <th  class="f14_fontb">نوع پروژه</th>
                            <th  class="f14_fontb">سقف تضمین</th>
                            <th  class="f14_fontb">مبلغ تضمین دریافتی</th>
                            <th  class="f14_fontb">شماره ضمانت نامه</th>
                            <th  class="f14_fontb">توضیحات</th>
                            <th  colspan="1" class="f14_fontb">تاریخ انقضاء</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody><?php
                    $cnt=0;
                    while($row = mysql_fetch_assoc($result)){
                        if ($login_RolesID!=16 && $login_RolesID!=1 && !($row['guaranteepayval']>0)) continue;
                    $cnt++;
						$color='';$colorval='';
					   if (compelete_date($row["guaranteeExpireDate"])<gregorian_to_jalali(date('Y-m-d')))
						$color='ff0000';
					if ($row['guaranteepayval']<$row['guaranteeUp'])
					$colorval='ffd000';
?>
                        <tr  >
                            <td style="text-align:center;color:#<?php echo $color;?>"><?php echo $cnt; ?></td>
                            <td style="color:#<?php echo $color;?> "><?php echo $row['Title']; ?></td>
							 <td style="text-align:left"><?php echo $row['prjtypeTitle']; ?></td>
                        
                             <td style="text-align:center;color:#<?php echo $color;?>"><?php echo number_format($row['guaranteeUp']); ?></td>
                            <td style="text-align:center;color:#<?php echo $colorval;?>"><?php echo number_format($row['guaranteepayval']); ?></td>
                            <td style="text-align:left;color:#<?php echo $color;?>"><?php echo $row['guaranteeNo']; ?></td>
                            <td style="text-align:center;color:#<?php echo $color;?>"><?php echo $row['guaranteeDescription']; ?></td>
                            
                            <td style="text-align:center;color:#<?php echo $color;?>"><?php echo $row['guaranteeExpireDate']; ?></td>
							
		            <?php  $permitrolsid = array("1","7","16","18");
                        if (in_array($login_RolesID, $permitrolsid))
                            { ?>
         		
                             <td><a href=<?php print "guarantee_level2_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                             rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['type']."_".$row['ID'].rand(10000,99999); ?>> 
                            <img style = 'width: 12%;' src='../img/file-edit-icon.png' title=' ويرايش '> </a></td>
                            
						<?php } ?>	
                        </tr><?php

                    }
                    
?>
                    </tbody>
                    
                      
                </table>
                
                      
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
