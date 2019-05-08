<?php 
/*
tools/toolsmarksaving_level4_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level3_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='toolsmarksaving_level4';


        

if ($login_Permission_granted==0) header("Location: ../login.php");

$Gadget2IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

$linearray = explode('_',$Gadget2IDProducersID);
$Gadget2ID=$linearray[0];//جدول سطح 2 ابزار
$ProducersID=$linearray[1];// شناسه تولید کننده

/*
        producers جدول تولیدکننده
        producersid شناسه تولید کننده
        producers.Title عنوان تولید کننده
        gadget1 جدول سطح 1 ابزار
        gadget2 جدول سطح دوم ابزار
*/
$query =   "select gadget1.Gadget1ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title  from producers,gadget2 
            inner join gadget1 on gadget2.gadget1ID=gadget1.gadget1ID
            where ProducersID='$ProducersID' and gadget2ID='$Gadget2ID' ";

$result = mysql_query($query);

if ($result)
$row = mysql_fetch_assoc($result);
$LevelTitle=$row['PTitle'].' و کالای '.$row['g1Title'].' - '.$row['g2Title'];
$Gadget1ID= $row['Gadget1ID'];       

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
$sql = "select distinct gadget3.Gadget3ID,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle 
        ,marks1.title marks1title ,marks2.title marks2title ,marks3.title marks3title ,marks4.title marks4title 
        ,marks1.marksid smarksid1
        ,marks2.marksid smarksid2
        ,marks3.marksid smarksid3
        ,marks4.marksid smarksid4
        from gadget3
        
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        inner join  toolsmarks toolsmarksp on toolsmarksp.gadget3ID=gadget3.gadget3ID and toolsmarksp.ProducersID='$ProducersID'
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            left outer join (select max(marksid) marksid,gadget3id from  toolsmarks where ProducersID='$ProducersID' GROUP BY gadget3id) toolsmarks1 on toolsmarks1.gadget3id=gadget3.gadget3id 
            left outer join marks marks1 on marks1.marksID=toolsmarks1.marksID
            
            left outer join (select max(marksid) marksid,gadget3id from  toolsmarks toolsmarksout where toolsmarksout.ProducersID='$ProducersID' 
            and toolsmarksout.marksid<(select max(marksid) from  toolsmarks toolsmarksin where toolsmarksin.ProducersID='$ProducersID' 
            and toolsmarksin.gadget3id=toolsmarksout.gadget3id) GROUP BY gadget3id) toolsmarks2 on 
            toolsmarks2.gadget3id=gadget3.gadget3id and toolsmarks2.marksID<toolsmarks1.marksID
            left outer join marks marks2 on marks2.marksID=toolsmarks2.marksID
            
            left outer join (select min(marksid) marksid,gadget3id from  toolsmarks where ProducersID='$ProducersID' GROUP BY gadget3id) toolsmarks4 
            on toolsmarks4.gadget3id=gadget3.gadget3id  and toolsmarks4.marksID<toolsmarks2.marksID 
            left outer join marks marks4 on marks4.marksID=toolsmarks4.marksID
            
            
            left outer join (select min(marksid) marksid,gadget3id from  toolsmarks toolsmarksout where toolsmarksout.ProducersID='$ProducersID' 
            and toolsmarksout.marksid>(select min(marksid) from  toolsmarks toolsmarksin where toolsmarksin.ProducersID='$ProducersID' 
            and toolsmarksin.gadget3id=toolsmarksout.gadget3id) GROUP BY gadget3id) toolsmarks3 on 
            toolsmarks3.gadget3id=gadget3.gadget3id and toolsmarks3.marksID<toolsmarks2.marksID and toolsmarks3.marksID>toolsmarks4.marksID
            left outer join marks marks3 on marks3.marksID=toolsmarks3.marksID
            
            
            
                    
        where gadget3.gadget2ID=$Gadget2ID
        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title  ";
//print $sql;
$result = mysql_query($sql);

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست ابزار سطح 3 جهت ثبت مارک <?php print $LevelTitle; ?></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    function DeleteAll(url)
    {
         if (! confirm('مطمئن هستید که حذف شود ؟')) return;
         
        var stid='0';
        for (var j=1;j<=(document.getElementById('records').rows.length-1);j++)
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="<?php echo $_server_httptype; ?>://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }

    function EditAll(url)
    {
        var stid='0';
        
        
        
        for (var j=1;j<=(document.getElementById('records').rows.length-1);j++)
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        //alert(url);
            
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="<?php echo $_server_httptype; ?>://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }
    
     function SelectAll()
                {
                    if ($("input[id^='c']:checked").length == $("input[id^='c']").length)
                    $("input[id^='c']").prop('checked', false);
                    else
                    $("input[id^='c']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
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
                            <h1 align="center">  لبست ابزار سطح 3 جهت ثبت مارک برای تولیدکننده <?php print $LevelTitle; ?> </h1>
                        
                            <div style = "text-align:left;">
                            <a href=<?php print "toolsmarksaving_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a>
                            
                            </div>
                            
                            
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
                        	<th width="5%"></th>
                        	<th width="5%">کد</th>
                            <th width="40%">عنوان</th>
                        	<th width="10%">مارک1</th>
                        	<th width="10%">مارک2</th>
                        	<th width="10%">مارک3</th>
                        	<th width="10%">مارک4</th>
                        	<th width="5%"></th>
                        	<th width="5%"></th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody>
                   
                <a onclick="SelectAll();"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                <a onclick="EditAll('<?php print"$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/toolsmarksaving_level4_groupedit.php";?>');"><img style = 'width: 4%;' src='../img/file-edit-icon.png' title='ویرایش'>
                <a onclick="DeleteAll('<?php print"$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/toolsmarksaving_level4_groupdelete.php";?>');"><img style = 'width: 4%;' src='../img/app-delete-icon.png' title='حذف'>
                                    
                        <?php
                    $cnt=0;
                    while($row = mysql_fetch_assoc($result)){
                    $cnt++;
                        $Code = $row['Code'];
                        $ID = $row['Gadget3ID'].'_'.$ProducersID;
                        $Title = $row['FullTitle'];
                        
                        
?>
                        <tr>
                            <td > <input type="checkbox" id="c<?php echo $cnt; ?>" name="chk<?php echo $ID; ?>" value="1"/></td >
                            <td><?php echo $Code; if($login_userid==4) echo "($ProducersID)"; ?></td>
                            <td><?php echo $Title; if($login_userid==4) echo "($row[Gadget3ID])"; ?></td>
                            <td><?php echo $row['marks1title']; if($login_userid==4) echo "($row[smarksid1])"; ?></td>
                            <td><?php echo $row['marks2title']; if($login_userid==4) echo "($row[smarksid2])"; ?></td>
                            <td><?php echo $row['marks3title']; if($login_userid==4) echo "($row[smarksid3])"; ?></td>
                            <td><?php echo $row['marks4title']; if($login_userid==4) echo "($row[smarksid4])"; ?></td>
                            <td><a href=<?php print "toolsmarksaving_level4_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 70%;' src='../img/tag_green.png' title='   ثبت مارک '>  </a></td>
                            
                            <td><a href=<?php print "toolsmarksaving_level5_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 70%;' src='../img/search.png' title='مشاهده گردش'>  </a></td>
                           
						   
                           
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
