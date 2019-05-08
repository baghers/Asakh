<?php 
/*
tools/toolsproduceradd.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود

*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
if ($login_Permission_granted==0) header("Location: ../login.php");
$uid=$_GET["uid"];

$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;


$limited = array("1","18","20","23");
if ( in_array($login_RolesID, $limited)) $disabled=''; else $disabled='disabled';

if ($_POST)
{
        $gadget3ID=$_POST['gadget3ID'];
        $MarksID=$_POST['MarksID'];
		if ($_POST['pid']) $producersID=$_POST['pid']; else $producersID=$login_ProducersID;
		
        if ($gadget3ID>0 && $MarksID>0)
        {
            $query ="INSERT INTO toolsmarks(MarksID,gadget3ID, ProducersID,SaveTime,SaveDate,ClerkID)
                    select distinct '$MarksID' MarksID,gadget3.gadget3ID,'$producersID' ProducersID,'".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid' 
                    from gadget3
                    
                    where gadget3ID='$gadget3ID' and gadget3ID not in (select gadget3ID from toolsmarks where ProducersID='$producersID' and MarksID='$MarksID')";
                
            $result = mysql_query($query);
            print "<p class='note'>ثبت با موفقیت انجام شد<p>";
            //exit;
        }
}

$cond="";
if ($g2id>0) 
	{
		$cond.=" and gadget2.gadget2id='$g2id' ";
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
	$sql="SELECT producers.title producerstitle,marks.title markstitle,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title,gadget2.gadget2id, 
	            gadget3.title Gadget3Title, units.title UnitsTitle,toolsmarks.marksid, toolsmarks.ProducersID, toolsmarks.Gadget3ID,
	            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
	            ,toolsmarks.toolsmarksID,case ifnull(gardesh.toolsmarksID,0) when 0 then '' else 'دارد' end hasgardesh
                ,toolsmarks.hide
	            FROM toolsmarks
	            inner join marks on marks.marksid=toolsmarks.marksid
	            inner join producers on producers.producersID=toolsmarks.producersID and producers.producersID='$login_ProducersID'
	            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
	            inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
	            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
	            left outer join units on units.Unitsid=gadget3.Unitsid
	            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
	            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
	            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
	            left outer join operator on operator.operatorID=gadget3.operatorID
	            left outer join spec2 on spec2.spec2id=gadget3.spec2id
	            left outer join spec3 on spec3.spec3id=gadget3.spec3id
	            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
	            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
	            left outer join ( select distinct toolsmarksID from (
	            select toolsmarksid from invoicedetail  union all 
	            select toolsmarksid from pricelistdetail union all 
	            select toolsmarksid from toolspref union all 
	            select ToolsMarksIDpriceref from toolspref union all
	            select toolsmarksid from primarypricelistdetail where Price>0) as view1 ) gardesh on gardesh.toolsmarksID=toolsmarks.toolsmarksID
	            where 1=1 $cond
	        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
	
	        "; 
	 
	
	//print $sql;
	
	        
	$result = mysql_query($sql);
}

	$sqlselect="select distinct CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value 
		            FROM toolsmarks
	            inner join producers on producers.producersID=toolsmarks.producersID and producers.producersID='$login_ProducersID'
	            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
	            inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
	            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
	 order by _key COLLATE utf8_persian_ci";
	$allg2id = get_key_value_from_query_into_array($sqlselect);
	//echo $sqlselect;

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست ابزار</title>

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

	function selectpage(){
       
        window.location.href ='?uid=' +document.getElementById('uid').value
        + '&g2id=' + document.getElementById('g2id').value;
        
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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="toolsproduceradd.php" method="post">
                <table align="center">
                    <tbody>
                    
                    <tr>
                            <td colspan='5' class='label'>
                                <h2  align='center' style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:250%;font-family:'B Nazanin';">
                                مدیریت ابزار/کالا
                                </h2></td>
                            
            <td style = "text-align:right;">	
               <a  href=<?php print 
                    "tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$gadget2id.rand(10000,99999) ?>><img style = "width: 35px;" src="../img/Return.png" title='بازگشت'></a>
			</td>
                            </tr>
                            
                        <tr>
                        
                        <div style = "text-align:center;">
                
			<td width='250px' style = "text-align:center;">گروه کالا </td>	
			<td width='400px' style = "text-align:center;">عنوان </td>	
			<td width='200px' style = "text-align:center;">تولید کننده </td>	
			<td width='80px' style = "text-align:center;">واحد </td>	
			<td width='100px' style = "text-align:center;">مارک </td>	
			
			
               </div>
               
                          <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxcode; ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!-- div style = "text-align:left;">
                            <button title='افزودن طرح جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div -->
                          
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records"  align="center">
                    <thead>
                      
                                
                   <?php
		$sqlselect="select  producers.title _key,producers.ProducersID _value from producers
                    where ProducersID<>142
                    order by  _key COLLATE utf8_persian_ci";
        $allpid = get_key_value_from_query_into_array($sqlselect);
          
                   $query="select gadget3.gadget3ID as _value,
                                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
                                    from gadget3 
                                    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id  
                                    inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and ifnull(iscost,0)=0
                                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    left outer join operator on operator.operatorID=gadget3.operatorID
                                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                                    
                                    order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title";
                    $IDgadget3ID = get_key_value_from_query_into_array($query);
                               
                               $sql = "select distinct gadget3.gadget3ID as _value,
                                    CONCAT(units.title,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',gadget3.gadget3ID) as _key from gadget3 
                                    left outer join units on units.unitsID=gadget3.unitsID
                                        ";
                      $IDunitsID = get_key_value_from_query_into_array($sql); 
                      
                    $query='select MarksID as _value,Title as _key from marks where marksid<>128 order by Title COLLATE utf8_persian_ci';
                    $allMarksID = get_key_value_from_query_into_array($query);
                                           
                     print 
                            "
                            
                            <tr>".
                            select_option('g2id','',',',$allg2id,0,'','','2','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213')."<td class='data' colspan='3'><div id='divtxtlist' ><input type='text' id='suggest' name='suggest'
                               onkeydown=\"document.getElementById('suggest').value=farsireplace(document.getElementById('suggest').value);\"
                                onfocus=\"
                                
                                    var z = new Array(document.getElementById('gadget3ID').length);
                                    for (var i = 0; i < document.getElementById('gadget3ID').length; i++) 
                                    {
                                        document.getElementById('gadget3ID').options[i].text=
                                        farsireplace(document.getElementById('gadget3ID').options[i].text);
                                        var str = document.getElementById('gadget3ID').options[i].text;
                                        z[i] = str;
                                    }
                                    $('#suggest').autocomplete(z, {matchContains: true,minChars: 0});
                                //alert(1);
                                \"
                                onblur=\"document.getElementById('suggest').value=farsireplace(document.getElementById('suggest').value);
                                    var v=document.getElementById('suggest').value;
                                var sel = document.getElementById('gadget3ID');
								for(var i1 = 0; i1 < sel.options.length; i1++) 
                                {
									var selv=sel.options[i1].text; 
                                    selv=farsireplace(selv);
                                    if(selv === v) 
                                    {
                                       sel.selectedIndex = i1;
                                       document.getElementById('unitsID').value=sel.value;
                                       //FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools1_level3_synthetic_jr.php');
                                       
                                        break;
                                    }
                               } 
                               \"  type='text' class=\"textbox\"  style='width: 450px'  /></div></td>
                            ".select_option('pid','',',',$allpid,0,'',$disabled,'4','rtl',0,'',0,'','')."
                            ".select_option('unitsID','',',',$IDunitsID,0,'','disabled','1','rtl',0,'',0,"",80,'')."
                            ".select_option('MarksID','',',',$allMarksID,0,'','','1','rtl',0,'')."
                            <td><input   name='submit' type='submit' class='button' id='submit' value='افزودن' /></td>
                            ".
                            select_option('gadget3ID','',',',$IDgadget3ID,0,'','','1','rtl',0,'',$gadget3ID,"",1,'')."
                            
                            
                            
                            <tr>
                    ";
                    
                    print 
                    '</tr>
                    
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>';
         
                     
                    
                    $cnt=0;
                    while($row = mysql_fetch_assoc($result))
                    {
                        $cnt++;
                        if ($row['hide']==1)
                        $color="red";
                        else
                        $color="black";
                        
                        print "
                        
                        <tr>
                            
                            <td><font color='$color'>$cnt</font></td>
                            <td><font color='$color'>$row[Gadget12Title]</font></td>
                            <td><font color='$color'>$row[FullTitle]</font></td>
                            <td><font color='$color'>$row[UnitsTitle]</font></td>
                            <td><font color='$color'>$row[markstitle]</font></td>
                            <td></td>
                            <td>"; 
                            
                            
                            if ($row['hasgardesh']=="") 
                            print "<a 
                            href='toolsproducerdelete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['toolsmarksID']."_1_".rand(10000,99999).
                            "'
                            onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width:25px;' src='../img/delete.png' title='حذف'> </a>";
                            else
                            print "<a 
                            href='toolsproducerdelete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['toolsmarksID']."_2_".rand(10000,99999).
                            "'
                            > <img style = 'width:25px;' src='../img/photo_2016-12-10_15-46-20.jpg' title='فعال/غیرفعال'> </a>";
                            
                            print "</td></tr>";

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
