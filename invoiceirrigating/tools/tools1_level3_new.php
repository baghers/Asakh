<?php 
/*
tools/tools1_level3_new.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

    /*
        gadget3 جدول سطح سوم ابزار
        gadget2 جدول سطح دوم ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
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

$Gadget2ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
if (! $_POST)
{
    $query = "SELECT max(CAST(Code AS UNSIGNED))+1 maxcode FROM gadget3 where Gadget2ID='$Gadget2ID'";

    try 
        {		
            $result = mysql_query($query);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        } 
        
		
		$row = mysql_fetch_assoc($result);
		
        if ($row['maxcode']>0)
		  $Code = $row['maxcode'];
        else $Code = 1;
        
        
           
}

$register = false;

if ($_POST){
	$Gadget2ID = $_POST["Gadget2ID"];
    $Code = $_POST["Code"];
	$Title = $_POST["Title"];
    $unitsID = $_POST["unitsID"];
    
      
    $sizeunitsID = $_POST["sizeunitsID"];
    $spec1 = $_POST["spec1"];
    $opsize = $_POST["opsize"];
    $UnitsID2 = $_POST["UnitsID2"];
    $UnitsCoef2 = $_POST["UnitsCoef2"];
    $materialtypeid = $_POST["materialtypeid"];
    $zavietoolsorattabaghe = $_POST["zavietoolsorattabaghe"];
    $zavietoolsorattabagheUnitsID = $_POST["zavietoolsorattabagheUnitsID"];
    $fesharzekhamathajm = $_POST["fesharzekhamathajm"];
    $fesharzekhamathajmUnitsID = $_POST["fesharzekhamathajmUnitsID"];
    $size2 = $_POST["size2"];
    $size11 = $_POST["size11"];
    $size12 = $_POST["size12"];
    $size13 = $_POST["size13"];
    $operatorid= $_POST["operatorid"];
    $spec2id = $_POST["spec2id"];
    $spec3id = $_POST["spec3id"];
    $spec3size = $_POST["spec3size"];
    $spec3sizeunitsid = $_POST["spec3sizeunitsid"];
    
	$savetime=date('Y-m-d H:i:s');

	if ($Code != ""){
	   $query="INSERT INTO gadget3(Code,Title, Gadget2ID,SaveTime,SaveDate,ClerkID,unitsID,sizeunitsID,spec1,opsize,UnitsID2,UnitsCoef2,materialtypeid,zavietoolsorattabaghe,zavietoolsorattabagheUnitsID,fesharzekhamathajm,fesharzekhamathajmUnitsID,size2,size11,size12,size13,operatorid,spec2id,spec3id,spec3size,spec3sizeunitsid) 
        VALUES('$Code','$Title','$Gadget2ID','$savetime','".date('Y-m-d')."','$login_userid'
        ,'$unitsID','$sizeunitsID','$spec1','$opsize','$UnitsID2','$UnitsCoef2','$materialtypeid','$zavietoolsorattabaghe','$zavietoolsorattabagheUnitsID','$fesharzekhamathajm','$fesharzekhamathajmUnitsID','$size2','$size11','$size12','$size13','$operatorid','$spec2id','$spec3id','$spec3size','$spec3sizeunitsid');";
        

        try 
        {		
            mysql_query($query); 
            $register = true;  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        } 
        
	}
    
    $query = "select gadget3ID from gadget3 where gadget3ID = last_insert_id() and SaveTime='$savetime'";

    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $Gadget3ID = $row['gadget3ID'];
              
              
    mysql_query("delete from gadget3operational where Gadget3ID = $Gadget3ID;");
     
    $query = "select gadget3.Code as _key,gadget3.Gadget3ID as _value from gadget3
                     inner join gadget2 on gadget2.gadget2ID=gadget3.Gadget2ID
                     inner join gadget1 on gadget1.gadget1ID=gadget2.Gadget1ID and ifnull(gadget1.ISCost,0)=1";
    $ID = get_key_value_from_query_into_array($query);
    //gadget3operational جدول هزینه های اجرای ابزارها
    if ($ID["$_POST[Gadget3codeOperational1]"]>0)
    {
        mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
        VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational1]"]."','$_POST[CostCoef1]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
    }
	if ($ID["$_POST[Gadget3codeOperational2]"]>0)
    {
        mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
        VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational2]"]."','$_POST[CostCoef2]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
    }
	if ($ID["$_POST[Gadget3codeOperational3]"]>0)
    {
        mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
        VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational3]"]."','$_POST[CostCoef3]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
    }
	if ($ID["$_POST[Gadget3codeOperational4]"]>0)
    {
        mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
        VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational4]"]."','$_POST[CostCoef4]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
    }
	if ($ID["$_POST[Gadget3codeOperational5]"]>0)
    {
        mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
        VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational5]"]."','$_POST[CostCoef5]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
    }
		         
              
              
              
              
    $query='select ProducersID as _value,Title as _key from producers where ifnull(DisableAddGadget,0)=0 order by Title';
    $ID = get_key_value_from_query_into_array($query);
    foreach ($ID as $key => $value)
    {
        //print $_POST["Producer$value"]."salam";
        if ($_POST["Producer$value"]=='on')
        {
            mysql_query("
            INSERT INTO toolsmarks(MarksID,gadget3ID, ProducersID,SaveTime,SaveDate,ClerkID) 
            VALUES('128','$Gadget3ID','$value','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
        }
    }
    
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت ابزار سطح 3</title>
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
						header("Location: tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.rand(10000,99999));
                        
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
                <form action="tools1_level3_new.php" method="post">
                
                <script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>
                <script type='text/javascript'>
                
                function fillform(Url)
                {
    var selectedgadget3ID=document.getElementById('allGadget3ID').value;
    $.post(Url, {selectedgadget3ID:selectedgadget3ID}, function(data){
    $('#tableproducers').html(data.boxstr);
    //$('#Code').val(data.Code);
    $('#Title').val(data.Title);
    $('#unitsID').val(data.unitsID);
    $('#Gadget3codecomboOperational1').val(data.Gadget3codeOperational1);
    $('#Gadget3codecomboOperational2').val(data.Gadget3codeOperational2);
    $('#Gadget3codecomboOperational3').val(data.Gadget3codeOperational3);
    $('#Gadget3codecomboOperational4').val(data.Gadget3codeOperational4);
    $('#Gadget3codecomboOperational5').val(data.Gadget3codeOperational5);
    $('#sizeunitsID').val(data.sizeunitsID);
    $('#spec1').val(data.spec1);
    $('#opsize').val(data.opsize);
    $('#UnitsID2').val(data.UnitsID2);
    $('#UnitsCoef2').val(data.UnitsCoef2);
    $('#CostCoef1').val(data.CostCoef1);
    $('#CostCoef2').val(data.CostCoef2);
    $('#CostCoef3').val(data.CostCoef3);
    $('#CostCoef4').val(data.CostCoef4);
    $('#CostCoef5').val(data.CostCoef5);
    $('#materialtypeid').val(data.materialtypeid);
    $('#zavietoolsorattabaghe').val(data.zavietoolsorattabaghe);
    $('#zavietoolsorattabagheUnitsID').val(data.zavietoolsorattabagheUnitsID);
    $('#fesharzekhamathajm').val(data.fesharzekhamathajm);
    $('#fesharzekhamathajmUnitsID').val(data.fesharzekhamathajmUnitsID);
    $('#size2').val(data.size2);
    $('#size11').val(data.size11);
    $('#size12').val(data.size12);
    $('#size13').val(data.size13);
    $('#operatorid').val(data.operatorid);
    $('#spec2id').val(data.spec2id);
    $('#spec3id').val(data.spec3id);
    $('#spec3size').val(data.spec3size);
    $('#spec3sizeunitsid').val(data.spec3sizeunitsid);
    $('#Gadget3codeOperational1').val(data.Gadget3codeOperational1);
    $('#Gadget3codeOperational2').val(data.Gadget3codeOperational2);
    $('#Gadget3codeOperational3').val(data.Gadget3codeOperational3);
    $('#Gadget3codeOperational4').val(data.Gadget3codeOperational4);
    $('#Gadget3codeOperational5').val(data.Gadget3codeOperational5);
    
    
    
    
	       
       }, 'json');
                    
                }
                
                
                </script>
                   <table  width="600" align="center" class="form">
                    <tbody>
                    <div  style = "text-align:left;"><a  href=<?php 
                    
                    $query = "
                    SELECT gadget3.Gadget3ID  as _value,
                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) _key
                    FROM gadget3  
                    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                    left outer join toolsmarks on toolsmarks.Gadget3ID=gadget3.Gadget3ID
                    left outer join invoicedetail on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
                    left outer join operator on operator.operatorID=gadget3.operatorID
                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid


                    where gadget3.Gadget2ID='$Gadget2ID'
                    order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
                    ";
                    $allGadget3ID = get_key_value_from_query_into_array($query);
                    
                    $query='select unitsID as _value,Title as _key from units order by Title   COLLATE utf8_persian_ci';
                    $allunitsID = get_key_value_from_query_into_array($query);
                    
                    $query='select sizeunitsID as _value,Title as _key from sizeunits order by Title   COLLATE utf8_persian_ci';
                    $allsizeunitsID = get_key_value_from_query_into_array($query);
                     
                    $query="select gadget3.Code as _value,
                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT('فصل',gadget2.Code,'-',ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
                    as _key from gadget3
                     inner join gadget2 on gadget2.gadget2ID=gadget3.Gadget2ID
                     inner join gadget1 on gadget1.gadget1ID=gadget2.Gadget1ID and ifnull(gadget1.ISCost,0)=1
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
                      order by _key  COLLATE utf8_persian_ci";
                    $IDOperational = get_key_value_from_query_into_array($query);
                    
                    $query='select ProducersID as _value,Title as _key from producers order by Title COLLATE utf8_persian_ci';
                    $allProducersID = get_key_value_from_query_into_array($query);
                     
                    $query='select operatorid as _value,Title as _key from operator order by Title COLLATE utf8_persian_ci';
                    $alloperatorid = get_key_value_from_query_into_array($query);
                    
                    
                    $query='select spec2id as _value,Title as _key from spec2 order by Title COLLATE utf8_persian_ci';
                    $allspec2id = get_key_value_from_query_into_array($query);
                    
                    
                    $query='select spec3id as _value,Title as _key from spec3 order by Title COLLATE utf8_persian_ci';
                    $allspec3id = get_key_value_from_query_into_array($query);
                                
                    
                    $query='select materialtypeid as _value,Title as _key from materialtype order by Title COLLATE utf8_persian_ci';
                    $allmaterialtypeid = get_key_value_from_query_into_array($query);
                    
                    
                       
                    print 
                    "tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.rand(10000,99999) 
                    ."><img style = \"width: 4%;\" src=\"../img/Return.png\" ></a></div>
                    
                    <tr>
                    ".select_option('allGadget3ID','کالا',',',$allGadget3ID,0,'','','6','rtl',0,'','','','400')."
                    <td><input type='button' value='افزودن' onclick=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools1_level3_new_jr.php');\"></td>
                    </tr>
                    <tr>
                      ".select_option('materialtypeid','نوع مواد',',',$allmaterialtypeid,0,'','','1','rtl',0,'',$materialtypeid,'','75')."
                      <td width='20%' class='label'>مشخصه1:</td>
                      <td colspan='6' width='80%' class='data'><input name='spec1' type='text' class='textbox' id='spec1' value='$spec1' size='55' maxlength='100' /></td>
                      <td width='20%' class='label'>عنوان:</td>
                      <td colspan='5' width='80%' class='data'><input name='Title' type='text' class='textbox' id='Title' value='$Title' size='27' maxlength='100' /></td>
                     
                    
                    
                    </tr>
                    
                    <tr>
                      ".select_option('unitsID','واحد کالا',',',$allunitsID,0,'','','1','rtl',0,'',$unitsID,'','75')."
                      <td width='20%' class='label'>اندازه:</td>
                      <td  width='80%' class='data'><input name='size11' type='text' class='textbox' id='size11' value='$size11' size='3' maxlength='100' /></td>
                      ".select_option('operatorid','',',',$alloperatorid,0,'','','1','rtl',0,'',$operatorid)."
                      <td width='80%' class='data'><input name='size12' type='text' class='textbox' id='size12' value='$size12' size='3' maxlength='100' /></td>
                      <td  width='80%' class='data'><input name='size13' type='text' class='textbox' id='size13' value='$size13' size='3' maxlength='100' /></td>
                      ".
                      select_option('sizeunitsID','',',',$allsizeunitsID,0,'','','2','rtl',0,'',$sizeunitsID,'','125').
                      "
                      <td width='20%' class='label'>زاویه/طول/سرعت:</td>
                      <td width='80%' class='data'><input name='zavietoolsorattabaghe' type='text' class='textbox' id='zavietoolsorattabaghe' value='$zavietoolsorattabaghe' size='3' maxlength='100' /></td>
                      ".
                      select_option('zavietoolsorattabagheUnitsID','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$zavietoolsorattabagheUnitsID).
                      "
                      
                    </tr>
                    
                    
                    <tr>
                      
                    
                      ".select_option('spec2id','مشخصه2',',',$allspec2id,0,'','','1','rtl',0,'',$spec2id,'','75')."
                      <td width='20%' class='label'>فشار/ضخامت/حجم:</td>
                      <td width='80%' class='data'><input name='fesharzekhamathajm' type='text' class='textbox' id='fesharzekhamathajm' value='$fesharzekhamathajm' size='3' maxlength='100' /></td>
                      ".select_option('fesharzekhamathajmUnitsID','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$fesharzekhamathajmUnitsID,'','156')."
                      
                      ".select_option('spec3id','مشخصه3',',',$allspec3id,0,'','','1','rtl',0,'',$spec3id,'','65')."
                      <td width='20%' class='label'>اندازه مشخصه3</td>
                      <td width='80%' class='data'><input name='spec3size' type='text' class='textbox' id='spec3size' value='$spec3size' size='3' maxlength='100' /></td>
                      ".select_option('spec3sizeunitsid','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$spec3sizeunitsid)."
                      
                    
                    </tr>  
                    
                    <tr>
                      <td width='20%' class='label'>اندازه فرعی:</td>
                      <td width='80%' class='data'><input name='size2' type='text' class='textbox' id='size2' value='$size2' size='8' maxlength='100' /></td>
                      ".select_option('UnitsID2','واحد فرعی ',',',$allsizeunitsID,0,'','','4','rtl',0,'',$UnitsID2,'','213')."
                      <td width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='UnitsCoef2' type='text' class='textbox' id='UnitsCoef2' value='$UnitsCoef2' size='6' maxlength='100' /></td>
                    <td width='20%' class='label'>کد کالا:</td>
                    <td  colspan='2'  width='80%' class='data'><input name='Code' type='text' class='textbox' id='Code' value='$Code' size='8' maxlength='6' /></td>
                      
                    </tr>
                    
                    
                     <tr>
                    <td width='20%' class='label'>اجرایی1:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational1' type='text' class='textbox' id='Gadget3codeOperational1' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational1').value=document.getElementById('Gadget3codeOperational1').value;
                    \"
                    value='$Gadget3codeOperational1' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational1','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational1,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational1').value=document.getElementById('Gadget3codecomboOperational1').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef1' type='text' class='textbox' id='CostCoef1' value='$CostCoef1' size='3' maxlength='100' /></td>
                    </tr>
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی2:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational2' type='text' class='textbox' id='Gadget3codeOperational2' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational2').value=document.getElementById('Gadget3codeOperational2').value;
                    \"
                    value='$Gadget3codeOperational2' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational2','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational2,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational2').value=document.getElementById('Gadget3codecomboOperational2').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef2' type='text' class='textbox' id='CostCoef2' value='$CostCoef2' size='3' maxlength='100' /></td>
                    </tr>
                    
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی3:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational3' type='text' class='textbox' id='Gadget3codeOperational3' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational3').value=document.getElementById('Gadget3codeOperational3').value;
                    \"
                    value='$Gadget3codeOperational3' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational3','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational3,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational3').value=document.getElementById('Gadget3codecomboOperational3').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef3' type='text' class='textbox' id='CostCoef3' value='$CostCoef3' size='3' maxlength='100' /></td>
                    </tr>
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی4:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational4' type='text' class='textbox' id='Gadget3codeOperational4' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational4').value=document.getElementById('Gadget3codeOperational4').value;
                    \"
                    value='$Gadget3codeOperational4' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational4','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational4,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational4').value=document.getElementById('Gadget3codecomboOperational4').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef4' type='text' class='textbox' id='CostCoef4' value='$CostCoef4' size='3' maxlength='100' /></td>
                    </tr>
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی5:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational5' type='text' class='textbox' id='Gadget3codeOperational5' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational5').value=document.getElementById('Gadget3codeOperational5').value;
                    \"
                    value='$Gadget3codeOperational5' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational5','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational5,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational5').value=document.getElementById('Gadget3codecomboOperational5').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef5' type='text' class='textbox' id='CostCoef5' value='$CostCoef5' size='3' maxlength='100' /></td>
                    </tr>
                    
                    <tr>
                    <td width='20%' class='label'>Gadget3ID:</td>
                    <td colspan=2 class='data'><input name='Gadget3ID' type='' readonly class='textbox' id='Gadget3ID'  value='$Gadget3ID'  size='18' maxlength='15' /></td>
                    <td class='data'><input name='Gadget2ID' type='hidden' readonly class='textbox' id='Gadget2ID'  value='$Gadget2ID'  size='2' maxlength='15' /></td>
                    </tr>
                    


                    ";
                    
                    

					 
                     
                     
                     $cnt=0;
                     
                            print "<tr><table id=tableproducers style='border:2px solid;'>تولیدکنندگان/واردکنندگان/<tr>";
                     foreach ($allProducersID as $key => $value)
                     {
                        if ($value>0)
                        {
                            $cnt++;
                            print "<td class='data'><input type='checkbox' name='Producer$value'>$key</input></td>";
                        if (($cnt%8)==0)
                            print "</tr><tr>";
                        }
                     }
                    
                    
                            print "</tr></table></tr>";
                            
                    
					  ?>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
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