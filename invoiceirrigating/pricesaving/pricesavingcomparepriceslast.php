<?php 
/*
pricesaving/pricesavingcomparepriceslast.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingcomparepriceslast.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

    
    /*
    نقش های مجاز ثبت
    1: مدیر پیگیری
    5: مدیریت آب و خاک
    13: مدیر آبیاری
    14: ناظر عالی
    3: مجری
    4: ادمین
    18: مدیر آبیاری تحت فشار
    */

$permitrolsid = array("1", "5", "13", "14", "3","4","18");
if ($login_Permission_granted==0) header("Location: ../login.php");


$per_page = 200;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;//شماره صفحه
$cntfirst = is_numeric($_GET["cnt"]) ? intval($_GET["cnt"]) : 0;//تعداد ردیف



$start = ($page - 1) * $per_page;
$currpage=$page;
$pages=50;                 
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;//شناسه سط دوم ابزار
$mid=is_numeric($_GET["mid"]) ? intval($_GET["mid"]) : 0;//مارک



if (! $_POST)
{
    
    $PriceListMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه لیست قیمت
    $uid=$_GET["uid"];
    $linearray = explode('_',$PriceListMasterID);
    $PriceListMasterID=$linearray[0];    

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
       month جدول ماه
       year جدول سال
       */
        
        $cond="";
        if ($g2id>0) $cond.=" and gadget2.gadget2id='$g2id' ";
        if ($mid>0) $cond.=" and toolsmarks.marksid='$mid' ";
        //if ($login_ProducersID>0)
        //    $cond.=" and toolsmarks.ProducersID='$login_ProducersID'";
        
        $y1=substr(gregorian_to_jalali(date('Y-m-d')),0,4);
        $y2=$y1-1;
        $y3=$y2-1;
        $sql="SELECT producers.title producerstitle,marks.title markstitle,gadget2.gadget2id, 
            units.title UnitsTitle, toolsmarks.ProducersID, toolsmarks.Gadget3ID,toolsmarks.marksid,
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(
            CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),
            CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),'')
            ,ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title
            ,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT
            (CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
            ,pricelistdetail.Price,primarypricelistdetail.Price primaryPrice,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title 
            ,concat(month.title,' ',year.value) fb,concat(month2.title,' ',year2.value) fb2 
            FROM toolsmarks
            inner join 
            (
            
            SELECT primarypricelistdetail.ToolsMarksID,max(pricelistdetail.PriceListMasterID) PriceListMasterID
            ,max(primarypricelistdetail.PriceListMasterID) primaryPriceListMasterID FROM `primarypricelistdetail`
            left outer join pricelistdetail on  pricelistdetail.ToolsMarksID=primarypricelistdetail.ToolsMarksID
            where pricelistdetail.Price>0 or primarypricelistdetail.Price>0
            group by ToolsMarksID
            
            )
            pricelistdetaillast on toolsmarks.toolsmarksid=pricelistdetaillast.ToolsMarksID
            left outer join pricelistdetail on pricelistdetaillast.ToolsMarksID=pricelistdetail.ToolsMarksID and pricelistdetail.PriceListMasterID=pricelistdetaillast.PriceListMasterID
            
            left outer join pricelistmaster pricelistmasterlast on pricelistmasterlast.PriceListMasterID=pricelistdetaillast.PriceListMasterID
            left outer join year on year.yearid=pricelistmasterlast.yearid
            left outer join month on month.monthid=pricelistmasterlast.monthid
            
            left outer join primarypricelistdetail on pricelistdetaillast.ToolsMarksID=primarypricelistdetail.ToolsMarksID 
            and primarypricelistdetail.PriceListMasterID=pricelistdetaillast.primaryPriceListMasterID
            
            left outer join pricelistmaster pricelistmaster2 on pricelistmaster2.PriceListMasterID=pricelistdetaillast.primaryPriceListMasterID
            left outer join year year2 on year2.yearid=pricelistmaster2.yearid
            left outer join month month2 on month2.monthid=pricelistmaster2.monthid
             
            
            inner join marks on marks.marksid=toolsmarks.marksid
            inner join producers on producers.producersID=toolsmarks.producersID
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
            where 1=1 $cond 
        "; 
        
            
        
        
        $sql.=" 
        order by FullTitle COLLATE utf8_persian_ci,toolsmarks.gadget3ID,toolsmarks.MarksID,Price
         
        ";
         if ($cond=='')
            $sql.=" LIMIT  $start,$per_page";
         $sql.=";";
        
							try 
								  {		
									  	$resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        //print $sql;

        if ($login_ProducersID>0)
        {
            $sqlselect="select distinct CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68
            inner join gadget3 on gadget3.gadget2id=gadget2.gadget2id
            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.ProducersID='$login_ProducersID' 
            order by _key  COLLATE utf8_persian_ci";
            
            $sqlselect2="select distinct marks.title _key,marks.marksid _value from marks 
            inner join toolsmarks on toolsmarks.marksid=marks.marksid and toolsmarks.ProducersID='$login_ProducersID' 
                    order by _key  COLLATE utf8_persian_ci";
                    
        }
        else
        {
            $sqlselect="select CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
            order by _key  COLLATE utf8_persian_ci";
            $sqlselect2="select  marks.title _key,marks.marksid _value from marks 
                    order by _key  COLLATE utf8_persian_ci";
        }
        $allg2id = get_key_value_from_query_into_array($sqlselect);
        $allmid = get_key_value_from_query_into_array($sqlselect2);
     
    
    
}




?>
<!DOCTYPE html>
<html>
<head>
  	<title>مقایسه قیمت ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}
</script> 

    <script>
   	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    function DeleteAll(url)
    {
	   var vshowzero=0;
       var vshownzero=0;
	   var vshowzero2=0;
       var vshownzero2=0;
	   var vshowm=0;
	   var vshownp=0;
       var vshownapr=0;
       
       
       
	   if (document.getElementById('showzero').checked) vshowzero=1;
       if (document.getElementById('shownzero').checked) vshownzero=1;
	   if (document.getElementById('showzero2').checked) vshowzero2=1;
       if (document.getElementById('shownzero2').checked) vshownzero2=1;
	   if (document.getElementById('showm').checked) vshowm=1;
	   if (document.getElementById('shownp').checked) vshownp=1;
	   if (document.getElementById('shownapr').checked) vshownapr=1;
       
        var codes;
        if ($('#pagination').length > 0)
        
           codes='-'+document.getElementById('pagination').value
        + '-' + document.getElementById('g2id').value
        + '-' + document.getElementById('pid').value
        + '-' + document.getElementById('mid').value
        
        + '-' + vshowzero
        + '-' + vshownzero
        + '-' + vshowzero2
        + '-' + vshownzero2
        + '-' + vshowm
        + '-' + vshownp
        + '-' + vshownapr;
        else
           codes='-1'+ '-' + document.getElementById('g2id').value
        + '-' + document.getElementById('pid').value
        + '-' + document.getElementById('mid').value
        
        + '-' + vshowzero
        + '-' + vshownzero
        + '-' + vshowzero2
        + '-' + vshownzero2
        + '-' + vshowm
        + '-' + vshownp
        + '-' + vshownapr;
        
        

         if (! confirm('مطمئن هستید که حذف شود ؟')) return;
         //alert(document.getElementById('records').rows.length);
        var stid='0';
        for (var j=1;j<=(document.getElementById('records').rows.length-4);j++)
            if (document.getElementById('cb'+j).checked)
                stid=stid+','+document.getElementById('cb'+j).name.substr(3)+codes;
        
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="http://"+stid.substring(7).replace("//","/");
        //alert(stid2);
        location.href=stid2;
        }
        
    }
    
     function SelectAll()
                {
                    if ($("input[id^='cb']:checked").length == $("input[id^='cb']").length)
                    $("input[id^='cb']").prop('checked', false);
                    else
                    $("input[id^='cb']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
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

			<!-- top -->
        	<?php 
            

				if ($_POST){
					if ($register){
						$Serial = "";
                        header("Location: pricesaving1masterlist_refs.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$PriceListMasterID.rand(10000,99999)."&g2id=$currg2id&pid=$currpid&mid=$currmid&showzero2=$showzero2&showzero=$showzero&shownapr=$shownapr&shownp=$shownp&showm=$showm&shownzero2=$shownzero2&shownzero=$shownzero");
                        
                        
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}
include('../includes/top.php'); 
?>
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
            <form id='myform' action="pricesavingcomparepriceslast.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                    
                    
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 


function p_tarkib(_value)
{
 var _len;var _inc;var _str;var _char;var _oldchar;_len=_value.length;_str='';
 for(_inc=0;_inc<_len;_inc++)
 {
   _char=_value.charAt(_inc);
   if (_char=='1' || _char=='2' || _char=='3' || _char=='4' || _char=='5' || _char=='6' || _char=='7' || _char=='8' || _char=='9' || _char=='0' || _char=='-') 
      _str=_str+_char;
   else
      if (_char!=',') return 'error';
 }
 return _str;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
    function transfer(cnt)
    {
        if (cnt==-1)
        {
             for (var j=1;j<=(document.getElementById('records').rows.length-3);j++)
             {
                
                if ((document.getElementById('txtProducersID').value==0) && (document.getElementById('Approved'+j).value==1))
                    document.getElementById('Price'+j).value=document.getElementById('otherPrice'+j).value;
               
               if (document.getElementById('coef'))
                    document.getElementById('Price'+j).value=numberWithCommas(p_tarkib(document.getElementById('previousPrice'+j).value)*1+Math.round(document.getElementById('coef').value*p_tarkib(document.getElementById('previousPrice'+j).value)/100));
               
               
             }
             
        }
        else if ((document.getElementById('txtProducersID').value==0) && (document.getElementById('Approved'+cnt).value==1))
        document.getElementById('Price'+cnt).value=document.getElementById('otherPrice'+cnt).value;
    }
    
</script>
";  ?>


                <td colspan="11" class="label">
                                <h2  align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:250%;font-family:'B Nazanin';"><?php echo 'مقایسه قیمت ها' ;?>
                    </h2></td>
                <tr>
                          <INPUT type="hidden" id="txtProducersID" value="<?php print $login_ProducersID; ?>"/>
                            <div style = "text-align:left;"><a  href=<?php 
                           print "pricesaving1masterlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ProducersID.rand(10000,99999).
                            "><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";?>
                            
                            
                            <td align="left"><?php
							
                            if ($pages > 1){
								echo 'صفحه<select name="pagination" id="pagination" onChange="selectpage();">';
								
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($currpage == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
								echo 'از  '.$pages;
							}
                            
                            //print $g2id;
							
                            print select_option('g2id','گروه کالا',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213').
                            select_option('mid','مارک',',',$allmid,0,'','','4','rtl',0,'',$mid,"onChange=\"selectpage();\"",'213');
							
                ?></td>
                        </tr>
                        
                     
                     
                            
                    
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                      
                    
                    
                       
                       
                       
                        <tr>
                        	<th colspan="1" width="1%"></th>
                        	<th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%">عنوان</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%">مارک</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:12;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            
                            
                        </tr>
                    </thead>
                   <tbody>	<?php
				   
                    $cnt=0;
                    $tabindex=0;
                    if ($resultwhile)
                    $pregadget3=0;
                    $premark=0;
                    $pcnt=0;
                    $arrayproducer=array();
                    $arrayp=array();
                    $arrayhint=array();
                    $start=$cnt;
					
					if ($login_isfulloption==1)
					while($row = mysql_fetch_array($resultwhile))
                    {
                        if (($premark==$row['marksid'] && $pregadget3==$row['Gadget3ID'])||($premark==0) )
                        {
                            $arrayproducer[$pcnt]=$row['producerstitle'];
                            $arrayp[$pcnt]=$row['Price'];
                            $arrayhint[$pcnt]=$row['fb']." و قیمت اولیه فروشنده:".number_format($row['primaryPrice'])." ".$row['fb2'];
                            $FullTitle=$row['FullTitle'];
                            $markstitle=$row['markstitle'];
                            $UnitsTitle=$row['UnitsTitle'];
                            $premark=$row['marksid'];
                            $pregadget3=$row['Gadget3ID'];
                            $pcnt++;
                            continue;
                        }
                        /*
                        if (($arrayp[0]!=$arrayp[1] && $arrayp[1]>0) || 
                        ($arrayp[1]!=$arrayp[2] && $arrayp[2]>0) ||
                        ($arrayp[2]!=$arrayp[3] && $arrayp[3]>0) ||
                        ($arrayp[3]!=$arrayp[4] && $arrayp[4]>0) ||
                        ($arrayp[4]!=$arrayp[5] && $arrayp[5]>0) ||
                        ($arrayp[5]!=$arrayp[6] && $arrayp[6]>0) ||
                        ($arrayp[6]!=$arrayp[7] && $arrayp[7]>0)
                        )
                        */
                        if ($pcnt>1)
                        {
                        
                        $cnt++;     
?>
                        <tr>
                            <td colspan="4" ></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[0]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[1]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[2]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[3]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[4]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[5]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[6]; ?></td >
                            <td style = "border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 70px"><?php echo $arrayproducer[7]; ?></td >
                        </tr>
                        
                        <tr>
                            
                            <td ><div id="divrown<?php echo $cnt; ?>"><input 
                            style = "border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 35px"
                            onmouseover="Tip(<?php echo '('.$cnt.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo ++$start; ?>"  maxlength="6" readonly /></div></td>
                            <td ><div id="divGadget3Title<?php echo $cnt; ?>"><input 
                            style = "border:1px solid black;border-color:#777;text-align:right;font-size:17;line-height:120%;font-family:'B Nazanin';<?php  if ($login_ProducersID>0) echo 'width: 507px'; else echo 'width: 507px';  ?>"
                            onmouseover="Tip(<?php echo '('.$FullTitle.')'; ?>)" name="Gadget3Title<?php echo $cnt; ?>" type="text" class="textbox" id="Gadget3Title<?php echo $cnt; ?>" value="<?php echo $FullTitle; ?>"   /></div></td>
                            <td ><div id="divmarkstitle<?php echo $cnt; ?>"><input 
                            style = "<?php if ($row['isrefrence']=='m') print "background-color:#ff00b8;" ?>border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:200%;font-family:'B Nazanin';width: 50px"
                            onmouseover="Tip(<?php echo '('.$markstitle.')'; ?>)" name="markstitle<?php echo $cnt; ?>" type="text" class="textbox" id="markstitle<?php echo $cnt; ?>" value="<?php echo $markstitle; ?>"  maxlength="6"  /></div></td>
                            <td ><div id="divUnitsTitle<?php echo $cnt; ?>"><input 
                            style = "border:1px solid black;border-color:#777;text-align:center;font-size:12;line-height:170%;font-family:'B Nazanin';width: 34px"
                            onmouseover="Tip(<?php echo '('.$UnitsTitle.')'; ?>)" name="UnitsTitle<?php echo $cnt; ?>" type="text" class="textbox" id="UnitsTitle<?php echo $cnt; ?>" value="<?php echo $UnitsTitle; ?>" maxlength="6"  /></div></td>
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['0']); ?>"  title="<?php echo $arrayhint['0']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['1']); ?>" title="<?php echo $arrayhint['1']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['2']); ?>" title="<?php echo $arrayhint['2']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['3']); ?>" title="<?php echo $arrayhint['3']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['4']); ?>" title="<?php echo $arrayhint['4']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['5']); ?>" title="<?php echo $arrayhint['5']; ?>" size="10" /></div></td>
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['6']); ?>" title="<?php echo $arrayhint['6']; ?>" size="10" /></div></td>
                            
                            
                            <td class="data"><input  
                            style = "background-color:##ff00b8;border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 72px"
                             readonly type="text" class="textbox"  value="<?php echo number_format($arrayp['7']); ?>" title="<?php echo $arrayhint['7']; ?>" size="10" /></div></td>
                            
                        </tr><?php
                        }
                        $arrayproducer=array();
                        $arrayp=array();
                        $arrayhint=array();
                        
                        $arrayproducer[0]=$row['producerstitle'];
                        $arrayp[0]=$row['Price'];
                        $arrayhint[0]=$row['fb']." و قیمت اولیه فروشنده:".number_format($row['primaryPrice'])." ".$row['fb2'];
                        $FullTitle=$row['FullTitle'];
                        $markstitle=$row['markstitle'];
                        $UnitsTitle=$row['UnitsTitle'];
                        $premark=$row['marksid'];
                        $pregadget3=$row['Gadget3ID'];
                        $pcnt=1;
                        
                        
                        
                    }
                    
                    print "<script type='text/javascript'> 


	function selectpage(){
	   window.location.href ='?uid=' +document.getElementById('uid').value+ '&g2id=' + document.getElementById('g2id').value
        + '&mid=' + document.getElementById('mid').value+'&page='+document.getElementById('pagination').value+'&cnt=$cnt'
        ;
        
	}
    
    </script>";

?>
                      
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                      
                      <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                            
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
