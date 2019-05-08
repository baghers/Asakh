<?php 
/*
reorts/reports_alllist4.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
include('../includes/functions.php');

  
$formename='reports_pipealllist';
if ($login_Permission_granted==0 && substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-22,18)!='viewapplicantstate'
&& substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-strlen($formename.strstr($_SERVER['HTTP_REFERER'],'.php')),strlen($formename))!=$formename) header("Location: ../login.php");


/*
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    applicantstates.title عنوان وضعیت پروژه
    hektar سطح پروژه
    prjtypeid نوع پروژه
    nazerID ناظر پروژه
    creditsourceTitle عنوان منبع تامین اعتبار
    ApplicantMasterIDmaster شناسه طرح اجرایی
    DesignerCoID شناسه مشاور طراح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    designsystemgroupsdetail جدول ریز سیستم های آبیاری
    appstatesee لیست وضعیت هایی که هر نقش می بیند
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignSystemGroupsID نوع سیستم آبیاری
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر
    */


if ($_POST){
    
        $ProducersID1=$_POST["ProducersID"];
        $ProducersID2=$_POST["ProducersID2"];
        $ProducersID3=$_POST["ProducersID3"];
        $ProducersID4=$_POST["ProducersID4"];
        
        $PriceListMasterID1=$_POST["PriceListMasterID"];
        $PriceListMasterID2=$_POST["PriceListMasterID2"];
        $PriceListMasterID3=$_POST["PriceListMasterID3"];
        $PriceListMasterID4=$_POST["PriceListMasterID4"];
        
    	
	   $cond1 ='';
	   $cond2 ='';
	   $cond3 ='';
	   $cond4 ='';   
	if ($_POST['shownzero']=='on') 
    {
	   $shownzero=1;
       $cond1 =" and  ifnull(pricelistdetail.Price,0)>0 ";
    }
   	if ($_POST['shownzero2']=='on') 
    {
	   $shownzero2=1;
       $cond2 =" and  ifnull(pricelistdetail2.Price,0)>0 ";
    }
   	if ($_POST['shownzero3']=='on') 
    {
	   $shownzero3=1;
       $cond3 =" and  ifnull(pricelistdetail3.Price,0)>0 ";
    }
   	if ($_POST['shownzero4']=='on') 
    {
	   $shownzero4=1;
       $cond4 =" and  ifnull(pricelistdetail4.Price,0)>0 ";
    }
        
    $condg='';$condm='';
	if ($_POST['g2id']>0) {$g2id=$_POST['g2id'];$condg =" and gadget2.gadget2id=$g2id ";}
           
 	if ($_POST['m2id']>0) {$m2id=$_POST['m2id'];$condm =" and  marks.marksid=$m2id ";}
           
                          

    $field2="replace(concat(gadget2.Title,' ',ifnull(materialtype.title,''),' ',ifnull(spec1,''),' ',ifnull(gadget3.Title,''),' ',ifnull(size11,'')
    ,ifnull(operator.Title,''),ifnull(size12,''),' ',ifnull(size13,''),ifnull(sizeunits.title,''),' ',ifnull(zavietoolsorattabaghe,''),' ',ifnull(sizeunitszavietoolsorattabaghe.title,'')
    ,' ',ifnull(spec2.title,''),' ',ifnull(fesharzekhamathajm,''),' ',ifnull(sizeunitsfesharzekhamathajm.title,''),' '
    ,ifnull(spec3.Title,''),' ',ifnull(spec3size,''),' ',ifnull(spec3sizeunits.title,'')),'  ',' ' )";
    
	
	
     	
    $sql="SELECT producers.title producerstitle ,marks.marksid,marks.title markstitle
    ,marks2.marksid marksid2,marks2.title markstitle2
    ,marks3.marksid marksid3,marks3.title markstitle3
    ,marks4.marksid marksid4,marks4.title markstitle4
    
    ,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title
    ,gadget2.gadget2id,gadget3.title Gadget3Title, units.title UnitsTitle
        
        ,pricelistdetail.Price,primarypricelistdetail.Price primmaryPrice
        ,pricelistdetail2.Price Price2,primarypricelistdetail2.Price  primmaryPrice2
        ,pricelistdetail3.Price Price3,primarypricelistdetail3.Price  primmaryPrice3
        ,pricelistdetail4.Price Price4,primarypricelistdetail4.Price  primmaryPrice4
        
        ,gadget3.Code, toolsmarks.ProducersID, toolsmarks.Gadget3ID,
        $field2 FullTitle
        ,toolsmarks.toolsmarksID
        FROM toolsmarks
        inner join marks on marks.marksid=toolsmarks.marksid
        inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
        inner join producers on producers.ProducersID=toolsmarks.ProducersID and producers.ProducersID='$ProducersID1' 
        
        left outer join units on units.Unitsid=gadget3.Unitsid
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        
        left outer join toolsmarks toolsmarks2 on toolsmarks2.ProducersID='$ProducersID2' and 
        toolsmarks2.gadget3id=toolsmarks.gadget3id 
        
        left outer join toolsmarks toolsmarks3 on toolsmarks3.ProducersID='$ProducersID3' and 
        toolsmarks3.gadget3id=toolsmarks.gadget3id 
        
        left outer join toolsmarks toolsmarks4 on toolsmarks4.ProducersID='$ProducersID3' and 
        toolsmarks4.gadget3id=toolsmarks.gadget3id 
        
        left outer join marks marks2 on marks2.marksid=toolsmarks2.marksid
        left outer join marks marks3 on marks3.marksid=toolsmarks3.marksid
        left outer join marks marks4 on marks4.marksid=toolsmarks4.marksid
        
        left outer join primarypricelistdetail on  primarypricelistdetail.PriceListMasterID='$PriceListMasterID1' 
        and primarypricelistdetail.ToolsMarksID=toolsmarks.toolsmarksID 
        left outer join pricelistdetail on  pricelistdetail.PriceListMasterID='$PriceListMasterID1' 
        and pricelistdetail.ToolsMarksID=toolsmarks.toolsmarksID $cond1
        
        left outer join primarypricelistdetail primarypricelistdetail2 on  primarypricelistdetail2.PriceListMasterID='$PriceListMasterID2' 
        and primarypricelistdetail2.ToolsMarksID=toolsmarks2.toolsmarksID 
        left outer join pricelistdetail pricelistdetail2 on  pricelistdetail2.PriceListMasterID='$PriceListMasterID2' 
        and pricelistdetail2.ToolsMarksID=toolsmarks2.toolsmarksID $cond2
        
        left outer join primarypricelistdetail primarypricelistdetail3 on  primarypricelistdetail3.PriceListMasterID='$PriceListMasterID3' 
        and primarypricelistdetail3.ToolsMarksID=toolsmarks3.toolsmarksID 
        left outer join pricelistdetail pricelistdetail3 on  pricelistdetail3.PriceListMasterID='$PriceListMasterID3' 
        and pricelistdetail3.ToolsMarksID=toolsmarks3.toolsmarksID $cond3
        
        left outer join primarypricelistdetail primarypricelistdetail4 on  primarypricelistdetail4.PriceListMasterID='$PriceListMasterID4' 
        and primarypricelistdetail4.ToolsMarksID=toolsmarks4.toolsmarksID 
        left outer join pricelistdetail pricelistdetail4 on  pricelistdetail4.PriceListMasterID='$PriceListMasterID4' 
        and pricelistdetail4.ToolsMarksID=toolsmarks4.toolsmarksID $cond4
        
        
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        where gadget3.gadget3id not in (select gadget3ID from gadget3synthetic) and 
        (ifnull(primarypricelistdetail.Price,0)>0 or ifnull(pricelistdetail.Price,0)>0)
	    $cond
		$condg
		$condm
		order by FullTitle COLLATE utf8_persian_ci,markstitle COLLATE utf8_persian_ci,producerstitle  COLLATE utf8_persian_ci
    ";
try 
    {		
        $result = mysql_query($sql.$login_limited);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
        
  //print $sql;
	
}
?>



<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;width:350px ;text-align:right;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	 background-color:#eaeaea;border:1px solid black;width:350px ;border-color:#000000 #000000;text-align:right;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#ececec;border:1px solid black;width:75px ;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#ececec;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#ececec;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f12_fontb{
		background-color:#a0fabe;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

  @media all {
.page-break { display: none; }
}

@media print {
.page-break { display: block; page-break-before: always; }
}	
    p.page { page-break-after: always; }
</style>

  
</head>


<body >
 <script>
    </script>

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
            
            <form action="reports_alllist4.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                     <?php
                     

                    $query="SELECT PriceListMasterID as _value,
                             CONCAT(CONCAT(year.Value,' '),month.Title) as _key FROM `pricelistmaster` 
                             inner join year on year.YearID=pricelistmaster.YearID
                             inner join month on month.MonthID=pricelistmaster.MonthID                    
                             ORDER BY year.Value DESC ,month.Code DESC ";
    				 $IDpl = get_key_value_from_query_into_array($query);

                     $query='select ProducersID as _value,Title as _key from producers order by _key  COLLATE utf8_persian_ci';
    				 $IDp = get_key_value_from_query_into_array($query);
					 
					$query="select CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
                                   inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
							       order by _key  COLLATE utf8_persian_ci";
								   
                     $IDg = get_key_value_from_query_into_array($query);
					 
					       $query='select marksid as _value,Title as _key from marks order by _key  COLLATE utf8_persian_ci';
    				 $IDm = get_key_value_from_query_into_array($query);
			 
                    
                    if ($shownzero>0)  $shownzerostr1= 'checked'; else $shownzerostr1="";
                    if ($shownzero2>0) $shownzerostr2= 'checked'; else $shownzerostr2="";
                    if ($shownzero3>0) $shownzerostr3= 'checked'; else $shownzerostr3="";
                    if ($shownzero4>0) $shownzerostr4= 'checked'; else $shownzerostr4="";

                     print  "<tr>"	.select_option('g2id','گروه کالا:',',',$IDg,0,'','','1','rtl',0,'',$g2id,'','200px')
									."<td colspan='1' class='data'></td>"
									.select_option('m2id','مارک کالا:',',',$IDm,0,'','','1','rtl',0,'',$m2id,'','200px').
                            "
                            ";
                             
                      ?>
                      
					  <td colspan="2"><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                      
                      
				 			                                       
                   </tr>
			   
                   </tbody>
                                     
                </table>
                            
                             <?php
                        
                               print "<table width=\"95%\" align=\"center\" style=\"page-break-after: always; \">
						<tr  > 
                                       <td colspan='11' 
                                           <span class=\"f14_font\" >  لیست قیمت لوازم  $theader $producerstitle </span> </td>
                                       
						</tr>
                    	
						";
						  print  "<tr><td colspan='3'> لیست قیمت فروشنده/تاییدشده:</td>"
						  .select_option('PriceListMasterID','',',',$IDpl,0,'','','1','rtl',0,'',$PriceListMasterID1,'','').
						  "<td colspan='1' class='data'> <input name='shownzero' type='checkbox' id='shownzero' $shownzerostr1 </td>
						  "
						  .select_option('PriceListMasterID2','',',',$IDpl,0,'','','1','rtl',0,'',$PriceListMasterID2,'','').
						  "<td colspan='1' class='data'> <input name='shownzero2' type='checkbox' id='shownzero2' $shownzerostr2 </td>
						  "
						  .select_option('PriceListMasterID3','',',',$IDpl,0,'','','1','rtl',0,'',$PriceListMasterID3,'','').
						  "<td colspan='1' class='data'> <input name='shownzero3' type='checkbox' id='shownzero3' $shownzerostr3 </td>
						  "
						  .select_option('PriceListMasterID4','',',',$IDpl,0,'','','1','rtl',0,'',$PriceListMasterID4,'','').
						  "<td colspan='1' class='data'> <input name='shownzero4' type='checkbox' id='shownzero4' $shownzerostr4 </td>
						   </tr>
                    
						<tr><td colspan='3' >نام تولید کننده/فروشنده:</td>"
						.select_option('ProducersID','',',',$IDp,0,'','','2','rtl',0,'',$ProducersID1,'','')
						
						.select_option('ProducersID2','',',',$IDp,0,'','','2','rtl',0,'',$ProducersID2,'','')
						
						.select_option('ProducersID3','',',',$IDp,0,'','','2','rtl',0,'',$ProducersID3,'','')
						
						.select_option('ProducersID4','',',',$IDp,0,'','','2','rtl',0,'',$ProducersID4,'','').
						"
						  </tr>
						";
						print "						
                        <tr>
                        <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							  
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
                                      <th  
                                    <span class=\"f12_fontb\"> مارک 1</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت 1 </span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> مارک 2</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت 2 </span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> مارک 3</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت 3 </span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> مارک 4</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت 4 </span>
        							  </th>";
                               $dif1=0;
                               $cnt1=0;
                               $dif2=0;
                               $cnt2=0;
                               $dif3=0;
                               $cnt3=0;
                                      
                               $rown=0;
                               $alldata=array();
                               if ($result)
                                while($row = mysql_fetch_assoc($result))
                                {
                                    $rown++;
                                    if ($rown%2==1) 
                                    $b=''; else $b='b';
                                    echo "<tr  height='50'>
                                    <td <span class='f10_font$b'>$rown</span></td>
                                    <td <span class='f10_font$b'>$row[FullTitle]</span></td>
                                    
                                    <td <span class='f10_font$b'>$row[UnitsTitle]</span></td>";
                                    
                                    if ($shownzero>0)  $Price1= ($row['Price']); else $Price1=($row['primmaryPrice']);
                                    if ($shownzero2>0) $Price2= ($row['Price2']); else $Price2=($row['primmaryPrice2']);
                                    if ($shownzero3>0) $Price3= ($row['Price3']); else $Price3=($row['primmaryPrice3']);
                                    if ($shownzero4>0) $Price4= ($row['Price4']); else $Price4=($row['primmaryPrice4']);
                                    
                                    if ($Price2>0) $markstitle2=$row['markstitle2']; else $markstitle2='';
                                    if ($Price3>0) $markstitle3=$row['markstitle3']; else $markstitle3='';
                                    if ($Price4>0) $markstitle4=$row['markstitle4']; else $markstitle4='';
                                    
                                    if ($Price1>0 && $Price2>0) {$dif1+=(($Price2-$Price1)/$Price1);$cnt1++;}
                                    if ($Price1>0 && $Price3>0) {$dif2+=(($Price3-$Price1)/$Price1);$cnt2++;}
                                    if ($Price1>0 && $Price4>0) {$dif3+=(($Price4-$Price1)/$Price1);$cnt3++;}
                                    
                                    if ($Price1>0) $Price1=number_format($Price1);
                                    if ($Price2>0) $Price2=number_format($Price2);
                                    if ($Price3>0) $Price3=number_format($Price3);
                                    if ($Price4>0) $Price4=number_format($Price4);
                                    
                                    echo "<td <span class='f10_font$b'>$row[markstitle]</span></td>
                                     <td <span class='f10_font$b'>$Price1</span></td>";
                                    
                                    if ((($Price2-$Price1)/$Price1)>0.1)
                                        echo "<td <span class='f10_font$b'><font color='red'>$markstitle2</font></span></td><td <span class='f10_font$b'><font color='red'>$Price2</font></span></td>";
                                     else if ((($Price2-$Price1)/$Price1)<-0.1)
                                        echo "<td <span class='f10_font$b'><font color='blue'>$markstitle2</font></span></td><td <span class='f10_font$b'><font color='blue'>$Price2</font></span></td>";
                                    
                                     else
                                        echo "<td <span class='f10_font$b'>$markstitle2</span></td><td <span class='f10_font$b'>$Price2</span></td>";
                                     
                                    if ((($Price3-$Price1)/$Price1)>0.1)
                                        echo "<td <span class='f10_font$b'><font color='red'>$markstitle3</font></span></td><td <span class='f10_font$b'><font color='red'>$Price3</font></span></td>";
                                    else if ((($Price3-$Price1)/$Price1)<-0.1)
                                        echo "<td <span class='f10_font$b'><font color='blue'>$markstitle3</font></span></td><td <span class='f10_font$b'><font color='blue'>$Price3</font></span></td>";
                                    
                                     else 
                                        echo "<td <span class='f10_font$b'>$markstitle3</span></td><td <span class='f10_font$b'>$Price3</span></td>";
                                    
                                    if ((($Price4-$Price1)/$Price1)>0.1)
                                        echo "<td <span class='f10_font$b'><font color='red'>$markstitle4</font></span></td><td <span class='f10_font$b'><font color='red'>$Price4</font></span></td>";
                                    else if ((($Price4-$Price1)/$Price1)<-0.1)
                                        echo "<td <span class='f10_font$b'><font color='blue'>$markstitle4</font></span></td><td <span class='f10_font$b'><font color='blue'>$Price4</font></span></td>";
                                    
                                     else
                                        echo "<td <span class='f10_font$b'>$markstitle4</span></td><td <span class='f10_font$b'>$Price4</span></td>";
                                    echo
                                    "
                                     </tr>
                                    ";
                                }
                                echo "
                                <tr>
                                <td colspan=5><span class='f10_font$b'></span></td>
                                <td colspan=2 <span class='f10_font$b'> ".round(($dif1/$cnt1)*100,2)."</span></td>
                                <td colspan=2 <span class='f10_font$b'>".round(($dif2/$cnt2)*100,2)."</span></td>
                                <td colspan=2 <span class='f10_font$b'>".round(($dif3/$cnt3)*100,2)."</span></td>
                                </tr>
                                </table>";
                               
  									
                
              
			  
                 

?>
 
                   
                      
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
