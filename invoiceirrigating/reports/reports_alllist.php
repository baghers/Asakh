<?php 
/*
reorts/reports_alllist.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
include('../includes/functions.php');

  
$formename='reports_pipealllist';
if ($login_Permission_granted==0 && substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-22,18)!='viewapplicantstate'
&& substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-strlen($formename.strstr($_SERVER['HTTP_REFERER'],'.php')),strlen($formename))!=$formename) header("Location: ../login.php");

$norown=25;

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
    if ($login_RolesID<>'3') 
        $ProducersID=$_POST["ProducersID"];
     else
        $ProducersID=$login_ProducersID;   

$norown=$_POST['norown'];
    	
	$cond ='';
	if ($_POST['shownzero']=='on') 
    {
	$shownzero=1;$cond =" and  ifnull(pricelistdetail.Price,0)>0 ";
    }
    if ($ProducersID>0)
        $cond.=" and  toolsmarks.ProducersID ='$ProducersID' ";
    $condg='';
	if ($_POST['g2id']>0) {$g2id=$_POST['g2id'];$condg =" and  gadget2.gadget2id=$g2id ";}
           
    
        $PriceListMasterID=$_POST["PriceListMasterID"];
    $sql = "
    SELECT pricelistmaster.*,month.Title monthtitle,year.Value year 
    FROM pricelistmaster 
    inner join month on month.MonthID=pricelistmaster.MonthID
    inner join year on year.YearID=pricelistmaster.YearID
    where PriceListMasterID=$PriceListMasterID ;";


    //print $sql;
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    
    $year = $row['year'];
    $monthtitle = $row['monthtitle'];
    
    $theader=" $monthtitle $year  ";                        

	$field="replace(concat(gadget2.Title,' ',ifnull(materialtype.title,''),' ',ifnull(spec1,''),' ',ifnull(gadget3.Title,''),' ',ifnull(size11,'')
    ,ifnull(operator.Title,''),ifnull(size12,''),' ',ifnull(size13,''),ifnull(sizeunits.title,''),' ',ifnull(zavietoolsorattabaghe,''),' ',ifnull(sizeunitszavietoolsorattabaghe.title,'')
    ,' ',ifnull(spec2.title,''),' ',ifnull(fesharzekhamathajm,''),' ',ifnull(sizeunitsfesharzekhamathajm.title,''),' '
    ,ifnull(spec3.Title,''),' ',ifnull(spec3size,''),' ',ifnull(spec3sizeunits.title,''),' ',producers.title,' (',marks.title,')'),'  ',' ' )";
    
    $field2="replace(concat(gadget2.Title,' ',ifnull(materialtype.title,''),' ',ifnull(spec1,''),' ',ifnull(gadget3.Title,''),' ',ifnull(size11,'')
    ,ifnull(operator.Title,''),ifnull(size12,''),' ',ifnull(size13,''),ifnull(sizeunits.title,''),' ',ifnull(zavietoolsorattabaghe,''),' ',ifnull(sizeunitszavietoolsorattabaghe.title,'')
    ,' ',ifnull(spec2.title,''),' ',ifnull(fesharzekhamathajm,''),' ',ifnull(sizeunitsfesharzekhamathajm.title,''),' '
    ,ifnull(spec3.Title,''),' ',ifnull(spec3size,''),' ',ifnull(spec3sizeunits.title,'')),'  ',' ' )";
    
    if ($_POST['textboxsearch'] != "")
    {
        $cond.=" and  ($field)
         like '%$_POST[textboxsearch]%' ";
    }
	
	
     	
    $sql="SELECT producers.title producerstitle ,marks.marksid,marks.title markstitle,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title,gadget2.gadget2id, 
        gadget3.title Gadget3Title, units.title UnitsTitle, pricelistdetail.Price,primarypricelistdetail.Price primmaryPrice, gadget3.Code, toolsmarks.ProducersID, toolsmarks.Gadget3ID,
        $field2 FullTitle
        ,toolsmarks.toolsmarksID
        FROM toolsmarks
        inner join marks on marks.marksid=toolsmarks.marksid
        inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
        inner join producers on producers.ProducersID=toolsmarks.ProducersID 
        
        left outer join units on units.Unitsid=gadget3.Unitsid
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        
        left outer join toolspref on toolspref.ToolsMarksID=toolsmarks.marksid and toolspref.PriceListMasterID='$PriceListMasterID'
        left outer join primarypricelistdetail on  primarypricelistdetail.PriceListMasterID='$PriceListMasterID' 
        and primarypricelistdetail.ToolsMarksID=toolsmarks.toolsmarksID 
        
        left outer join pricelistdetail on  pricelistdetail.PriceListMasterID='$PriceListMasterID' 
        and pricelistdetail.ToolsMarksID=toolsmarks.toolsmarksID 
        
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        where gadget3.gadget3id not in (select gadget3ID from gadget3synthetic) and 
        (ifnull(primarypricelistdetail.Price,0)>0 or ifnull(pricelistdetail.Price,0)>0)
	    $cond
		$condg
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
            
            <form action="reports_alllist.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                     <?php
                     

                    $query="SELECT PriceListMasterID as _value,
                             CONCAT(CONCAT(year.Value,' '),month.Title) as _key FROM `pricelistmaster` 
                             inner join year on year.YearID=pricelistmaster.YearID
                             inner join month on month.MonthID=pricelistmaster.MonthID
                    
                             ORDER BY year.Value DESC ,month.Code DESC ";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "".select_option('PriceListMasterID','لیست قیمت:',',',$ID,0,'','','1','rtl',0,'',$PriceListMasterID,'','');

					 
					 if ($login_RolesID<>'3') 
					 {
                     $query='select ProducersID as _value,Title as _key from producers order by _key  COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
					 print "".select_option('ProducersID','نام تولید کننده/فروشنده:',',',$ID,0,'','','1','rtl',0,'',$ProducersID,'','');
					 }
					 
					 
					$query="select CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
                                   inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and gadget1.gadget1id<>68 
							       order by _key  COLLATE utf8_persian_ci";
								   
                     $ID = get_key_value_from_query_into_array($query);
                     print "".select_option('g2id','گروه کالا:',',',$ID,0,'','','1','rtl',0,'',$g2id,'','50px');

					     print "
                         <td colspan='1' class=\"label\">قسمتی از نام ابزار/تولیدکننده/مارک را وارد نمایید:</td>
                      <td class=\"data\"><input name=\"textboxsearch\" type=\"text\" class=\"textbox\" id=\"textboxsearch\" 
                      value='$_POST[textboxsearch]'   size='30' maxlength='40' /></td>
                    
                    
                                <td colspan='2' class='data'>  تایید شده:<input name='shownzero' type='checkbox' id='shownzero'";
                                if ($shownzero>0) echo 'checked';
					
					
                      ?>
                      
							</td>
                      <td colspan="1"><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                      
                     <td  class="label">&nbsp;</td>
                      <td  class="data"><input  name="norown" type="text" class="textbox" id="norown" 
                      value="<?php echo $norown ?>" size="5" maxlength="5" /></td>
                      
				 			                                       
                   </tr>
			   
                   </tbody>
                                     
                </table>
                            
                             <?php
                   
                               
                               $rown=0;
                               $alldata=array();
                               if ($result)
                                while($row = mysql_fetch_assoc($result))
                                {
                                    $rown++;
                                    $alldata[$rown][0]=$row['FullTitle'];
                                    $alldata[$rown][1]=$row['producerstitle'];
                                    $alldata[$rown][2]=$row['markstitle'];
                                    $alldata[$rown][3]=$row['UnitsTitle'];
                                    if ($row['Price']>0) $alldata[$rown][4]="<font >".number_format($row['Price'])."</font>";
                                        else $alldata[$rown][4]="<font color='blue'>".number_format($row['primmaryPrice'])."</font>";
                                    
                                    
                                    if ($ProducersID>0)
                                    $producerstitle=$row['producerstitle'];
                                }
                                 $index=ceil(($rown)/2);
                                //print $rown;
                                    
                                    if ($ProducersID>0)
                                    $theaderall="
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							  <th  
                                    <span class=\"f12_fontb\"> مارک </span>
        							  </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت </span>
        							  </th>
                                    
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							
                                     <th  
                                    <span class=\"f12_fontb\"> مارک </span>
        							 </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
                                     <th  
                                    <span class=\"f12_fontb\"> قیمت (ریال)</span>
        							 </th>
                                     
                                </tr>";
                                else
                                    $theaderall="
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> ت </span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> مارک </span>
        							  </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
                                      <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت </span>
        							  </th>
                                    
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> ت </span>
        							 </th>
                                     <th  
                                    <span class=\"f12_fontb\"> مارک </span>
        							 </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
                                     <th  
                                    <span class=\"f12_fontb\">(ریال) قیمت </span>
        							 </th>
                                     
                                </tr>"
                                ?>
                      
                   <?php
				   
					$pagenumber=1;
                    if (!($ProducersID>0))
                        $colspan=11;
                        else $colspan=9;
  									
                $j=0;
                $rownj=$rown;
				for($i=1;$i<=(ceil($rown/2));$i++)
                {
				
				
							$j=$j+1;
				
                        if ($i%2==1) 
                        $b=''; else $b='b';
					if ($i%$norown==1)	
					{
				   		echo $headtable= "<table width=\"95%\" align=\"center\" style=\"page-break-after: always; \">
						<tr  > 
                                       <td colspan=\"$colspan\" 
                                           <span class=\"f14_font\" >  لیست قیمت لوازم  $theader $producerstitle </span> </td>
                                       <td class=\"f14_font\" >$pagenumber </td>
						</tr>
                    	
                        <tr>".$theaderall;
						$pagenumber++;  
					}	
				?>
                    <tr  height="50">    
                      <td <span class="f9_font<?php echo $b; ?>"  >  <?php echo $j; ?> </span>  </td>
				      <td <span class="f13_font<?php echo $b; ?>">  <?php echo  $alldata[$i][0]; ?> </span> </td>
				 <?php if (!($ProducersID>0)) echo "<td <span class=\"f9_font$b\">".$alldata[$i][1]."</span> </td>";?> 
					  <td <span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[$i][2]; ?> </span> </td>
                      <td <span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[$i][3]; ?> </span> </td>
                      <td <span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[$i][4]; ?> </span> </td>
					  
					  
                      <td <span class="f9_font<?php echo $b; ?>"  >  
							<?php  
                            
                            echo $i+$index; ?> </span>  </td>
				      <td <span class="f13_font<?php echo $b; ?>">  <?php echo $alldata[$i+$index][0]; ?> </span> </td>
                 
				 <?php if (!($ProducersID>0))   echo "<td <span class=\"f9_font$b\">".$alldata[$i+$index][1]."</span> </td>"; ?> 
                      <td <span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[$i+$index][2]; ?> </span> </td>
                      <td <span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[$i+$index][3]; ?> </span> </td>
                      <td <span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[$i+$index][4]; ?> </span> </td>
					  
             		</tr>	
		<?php
		
		if ($i==$norown)	
		echo "</table>";
                }
              
			  
/*				   
				   
				   
				   $norown=35;
                    $pagenumber=1;
                    if (!($ProducersID>0))
                        $colspan=11;
                        else $colspan=9;
                        
                    echo "<table width=\"95%\" align=\"center\">
									<tr> 
                                       <td colspan=\"$colspan\" 
                                           <span class=\"f14_font\" >  لیست قیمت لوازم  $theader $producerstitle </span> </td>
                                       <td class=\"f14_font\" >$pagenumber </td>
                    				</tr>
                                      
                                    <tr>".$theaderall;
                    $j=0;
                    $rownj=$rown;
					for($i=1;$i<=(ceil($rown/2));$i++)
                    {
                    
							$j=$j+1;
							if ($j>$norown) 
							{
								$pagenumber++;  
								$j=1;
								$rownj-=$norown;
								
								echo "</table>
								
								
								<table width=\"95%\" align=\"center\"><p class=\"page\"></p><tr> 
								 
											   <td colspan=\"$colspan\"
														<span class=\"f14_font\" >  لیست قیمت لوازم  $theader $producerstitle
														</span> </td>
														<td class=\"f14_font\" >$pagenumber </td>
											   </tr>
											  
											<tr>".$theaderall;
							}
							   
					                             
				  
					   
					   
					   
                        if ($i%2==1) 
                        $b=''; else $b='b';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f9_font<?php echo $b; ?>"  >  <?php echo $j; ?> </span>  </td>
							
                            <td 
							<span class="f13_font<?php echo $b; ?>">  <?php echo  $alldata[($pagenumber-1)*$norown+$i][0]; ?> </span> </td>
                           
                            <?php
                            
                            if (!($ProducersID>0))
                             echo "<td <span class=\"f9_font$b\">".$alldata[($pagenumber-1)*$norown+$i][1]."</span> </td>";
                            ?> 
       
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*$norown+$i][2]; ?> </span> </td>
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*$norown+$i][3]; ?> </span> </td>
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*$norown+$i][4]; ?> </span> </td>
                            
                            
                            <td
                            <span class="f9_font<?php echo $b; ?>"  >  <?php  
                            if ($rownj>=$norown)  $index=$norown; else   $index=ceil(($rownj)/2);
                            
                            echo $j+$index; ?> </span>  </td>
							
                            <td 
							<span class="f13_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][0]; ?> </span> </td>
                           
                           
                           
                            <?php
                            
                            if (!($ProducersID>0))
                             echo "<td <span class=\"f9_font$b\">".$alldata[($pagenumber-1)*45+$i+$index][1]."</span> </td>";
                            ?> 
                            
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][2]; ?> </span> </td>
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][3]; ?> </span> </td>
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][4]; ?> </span> </td>
                                              
                                                     
                            
							  

							 
                        </tr><?php

                    }
                    echo "</table>";
                    
 */                   

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
