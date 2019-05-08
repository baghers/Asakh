<?php 
/*
tools/toolsmarksaving_level4_groupedit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level4_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");


if (! $_POST)
{
    $Gadget3IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    if (substr($Gadget3IDProducersID,0,2)=='0,')
        $Gadget3IDProducersID=substr($Gadget3IDProducersID,2);
    
    $Gadget3ID='0';//جدول سطح سوم ابزار
    $ProducersID='';// شناسه تولید کننده
        
    $alllinearray = explode(',',$Gadget3IDProducersID);   
    foreach ($alllinearray as $value) 
    {
        $linearray = explode('_',$value);
        $Gadget3ID.=','.$linearray[0];//جدول سطح سوم ابزار
        $ProducersID=$linearray[1];// شناسه تولید کننده
    }
    
    if (substr($Gadget3ID,0,2)=='0,')
        $Gadget3ID=substr($Gadget3ID,2);//جدول سطح سوم ابزار
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
        gadget2 جدول سطح دوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        gadget3 جدول سطح سوم ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        Code کد
        Title عنوان
        unitsID شناسه واحد کالا
        sizeunitsID شناسه اندازه کالا
        spec1 مشخصه اول
        opsize اندازه عملیاتی
        UnitsID2 شناسه واحد فرعی
        UnitsCoef2 ضریب اجرایی 2
        MaterialTypeID شناسه نوع مواد
        zavietoolsorattabaghe مقدار زاویه/طول/سرعت/طبقه
        zavietoolsorattabagheUnitsID واحد  زاویه/طول/سرعت/طبقه
        fesharzekhamathajm مقدار فشار/ضخامت/حجم
        fesharzekhamathajmUnitsID واحد فشار/ضخامت/حجم
        operatorid شناسه عملگر
        spec2id خصوصیت 2
        spec3id خصوصیت 3
        spec3sizeunitsid واحد خصوصیت 3
        IsHide غیر فعال شدن کالا
*/        
    
    $query ="select gadget2.Gadget2ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title,
      replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
      from producers,gadget2 
            inner join gadget1 on gadget2.gadget1ID=gadget1.gadget1ID
            inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            where 
            ProducersID='$ProducersID' and gadget3ID in ($Gadget3ID)
            
            
        ";
		//print $query;
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
	$PTitle = $resquery["PTitle"];
	$g1Title = $resquery["g1Title"];
	$g2Title = $resquery["g2Title"];
	$Title = $resquery["FullTitle"];
	$Gadget2ID = $resquery["Gadget2ID"];
    
                     
}

$register = false;

if ($_POST){
	$ProducersID = $_POST["ProducersID"];
	$Gadget3ID = $_POST["Gadget3ID"];
	$Gadget2ID = $_POST["Gadget2ID"];
    
    
    
    if ($_POST['showzero']=='on')
    {
    mysql_query("delete from toolsmarks WHERE Gadget3ID in ($Gadget3ID) and ProducersID='$ProducersID' 
                and toolsmarksid not in (
                select toolsmarksid from invoicedetail  union all 
                select toolsmarksid from toolspref union all 
                select ToolsMarksIDpriceref from toolspref
                
                )  ;");
    }            
    else
    mysql_query("delete from toolsmarks WHERE Gadget3ID in ($Gadget3ID) and ProducersID='$ProducersID' 
                and toolsmarksid not in (
                select toolsmarksid from invoicedetail  union all 
                select toolsmarksid from pricelistdetail union all 
                select toolsmarksid from toolspref union all 
                select ToolsMarksIDpriceref from toolspref
                
                )  ;");
    
    
    mysql_query("delete from pricelistdetail WHERE toolsmarksid not in 
        (select toolsmarksid from from toolsmarks )  ;");
                
                
    

        
    $query='select MarksID as _value,Title as _key from marks order by Title COLLATE utf8_persian_ci';
    $ID = get_key_value_from_query_into_array($query);
    foreach ($ID as $key => $value)
    {
        //print $_POST["Producer$value"]."salam";
            
        if ($_POST["marks$value"]=='on')
        {
            $sql="INSERT INTO toolsmarks(Gadget3ID,ProducersID, MarksID,SaveTime,SaveDate,ClerkID)
            select Gadget3ID,'$ProducersID','$value','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid' from gadget3
            where Gadget3ID in ($Gadget3ID) and Gadget3ID not in 
            (select Gadget3ID from toolsmarks where ProducersID='$ProducersID' and MarksID='$value');";
            //print $sql;
            
            mysql_query($sql); 
        }
    }
    //exit;
    $register=true;
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت مارک</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
         function SelectAll()
                {
                    if ($("input[id^='marks']:checked").length == $("input[id^='marks']").length)
                    $("input[id^='marks']").prop('checked', false);
                    else
                    $("input[id^='marks']").prop('checked', true);
                    //$("select[id^='marks']").selectedIndex=0;
                }
                
    
    </script>
    
	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
                        header("Location: toolsmarksaving_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999));
                        
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
                <form action="toolsmarksaving_level4_groupedit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "toolsmarksaving_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                     <tr>
                     <td width="20%" class="label">تولیدکننده:</td>
                      <td width="80%" class="data"><input readonly name="PTitle" type="text" class="textbox" id="PTitle" value="<?php echo $PTitle; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td width="20%" class="label">عنوان سطح1:</td>
                      <td width="80%" class="data"><input readonly name="g1Title" type="text" class="textbox" id="g1Title" value="<?php echo $g1Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <tr>
                     <td width="20%" class="label">عنوان سطح2:</td>
                      <td width="80%" class="data"><input readonly name="g2Title" type="text" class="textbox" id="g2Title" value="<?php echo $g2Title; ?>" size="50" maxlength="6" /></td>
                     </tr>
                     
                     <?php
                     
                            print "<tr>
                     <br>
                      <td width='80%' class='data'><input readonly name='Title' type='text' class='textbox' id='Title' value='$Title' size='50' maxlength='6' /></td>
                     </tr>
                     ";
                        while($resquery = mysql_fetch_assoc($result))
                        {
                            $PTitle = $resquery["PTitle"];
	                        $g1Title = $resquery["g1Title"];
	                        $g2Title = $resquery["g2Title"];
	                        $Title = $resquery["FullTitle"];
	                        $Gadget2ID = $resquery["Gadget2ID"];
                            
                            print "<tr>
                     
                      <td width='80%' class='data'><input readonly name='Title' type='text' class='textbox' id='Title' value='$Title' size='50' maxlength='6' /></td>
                     </tr>
                     ";
                            
                        }
       
    
                    print "
                        <tr>
                            <td class=\"label\">حذف مارک های قیمت دار:</td>
                            <td class=\"data\"><input name=\"showzero\" type=\"checkbox\" id=\"showzero\"   /></td>
                        </tr>";
                        
					 $query='select MarksID as _value,Title as _key from marks order by Title COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
                     
                     
					 $query="select MarksID as _value,MarksID as _key from toolsmarks where Gadget3ID in ($Gadget3ID) and ProducersID='$ProducersID'";
    				 $toolsmarks = get_key_value_from_query_into_array($query);
                     
                     $cnt=0;
                     
                            print "<tr><table style='border:2px solid;'><a onclick=\"SelectAll();\"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>مارک<tr>";
                     foreach ($ID as $key => $value)
                     {
                        if ($value>0)
                        {
                            $cnt++;
                            if (in_array($value, $toolsmarks))
                              print "<td class='data'><input type='checkbox' id='marks$value' name='marks$value' checked>$key</input></td>";
                            else 
                              print "<td class='data'><input type='checkbox' id='marks$value' name='marks$value'>$key</input></td>";
                        if (($cnt%6)==0)
                            print "</tr><tr>";
                        }
                     }
                    
                            print "</tr></table></tr>";
                            
					  ?>
                     
                     <tr>
                      <td class="data"><input name="ProducersID" type="hidden" class="textbox" id="ProducersID"  value="<?php echo $ProducersID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="Gadget3ID" type="hidden" class="textbox" id="Gadget3ID"  value="<?php echo $Gadget3ID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="Gadget2ID" type="hidden" class="textbox" id="Gadget2ID"  value="<?php echo $Gadget2ID ; ?>"  size="30" maxlength="15" /></td>
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