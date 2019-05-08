<?php 
/*
reorts/reports_pipeallweight.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

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

$orderby=' order by case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end  ';
    
if ($_POST)
{   
    //print $_POST['IDorder'].$_POST['IDorder1'].$_POST['IDorder2'];exit;      
      switch ($_POST['IDorder']) 
	  {
		case 1: $orderby=' order by cast(size11*1000 as decimal) '; break; 
		case 2: $orderby=' order by cast(fesharzekhamathajm*1000 as decimal) '; break;
		case 3: $orderby=' order by case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end'; break;
		default: $orderby=' order by case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end  '; break; 
	  }
	  switch ($_POST['IDorder1']) 
	  {
		case 1: $orderby.=' ,cast(size11*1000 as decimal) '; break; 
		case 2: $orderby.=' ,cast(fesharzekhamathajm*1000 as decimal) '; break;
		case 3: $orderby.=' ,case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end'; break;
		default: $orderby.=' ,case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end  '; break; 
	  }
      switch ($_POST['IDorder2']) 
	  {
		case 1: $orderby.=' ,cast(size11*1000 as decimal) '; break; 
		case 2: $orderby.=' ,cast(fesharzekhamathajm*1000 as decimal) '; break;
		case 3: $orderby.=' ,case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end'; break;
		default: $orderby.=' ,case gadget2.gadget2id when 495 then 1 when 494 then 2 when 202 then 3 when 376 then 4 end  '; break; 
	  }
}
    //print $orderby;
     	
    $sql="SELECT gadget3.UnitsCoef2,gadget2.gadget2id,size11,fesharzekhamathajm,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' ',''),'  ',' ' ) FullTitle
        FROM gadget3
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id  and gadget1.gadget1id=68 
        left outer join units on units.Unitsid=gadget3.Unitsid
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join sizeunits sizeunitsw on sizeunitsw.SizeUnitsID=gadget3.UnitsID2 
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid where ifnull(gadget3.IsHide,0)=0  $orderby        ";
try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
        
   //print $sql;
	

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

  
    p.page { page-break-after: always; }
</style>

  
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
			<?php 
			$query="
		select 'سايز' _key,1 as _value union all
		select 'فشار' _key,2 as _value union all 
		select 'مواد' _key,3 as _value ";
		$IDorder = get_key_value_from_query_into_array($query);
		$IDorder1 = get_key_value_from_query_into_array($query);
		$IDorder2 = get_key_value_from_query_into_array($query);

		if (!$_POST['IDorder'])
			$IDorderval=3;
		else $IDorderval=$_POST['IDorder'];
		
		?>
		    <form action="reports_pipeallweight.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                      
                     
                     
                     
                     
                     
                     
                      
                     
				 			                                       
                   </tr>
			   
                   </tbody>
                                     
                </table>
			
    			<form action="reports/pipeallweight.php" method="post">
				
		        <table id="records" align='center' class="page">
				<?php print select_option('IDorder','ترتیب1',',',$IDorder,0,'','','3','rtl',0,'',$_POST['IDorder'],"",'100');?> 
                <?php print select_option('IDorder1','ترتیب2',',',$IDorder1,0,'','','3','rtl',0,'',$_POST['IDorder1'],"",'100');?> 
                <?php print select_option('IDorder2','ترتیب3',',',$IDorder2,0,'','','3','rtl',0,'',$_POST['IDorder2'],"",'100');?> 
				  <td colspan="1"><input  type="submit" name="submit" value="جستجو"/></td>
                    
                </table>
				
			  </form>
			  
              <table id="records" width="95%" align="center">
                   
              <thead>
                        
              </table>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                  
                <table align='center' class="page" border='1'>              
                   
				  
                            
                             <?php
                   
                               
                               $rown=0;
                               $alldata=array();
                                while($row = mysql_fetch_assoc($result))
                                {
                                    $rown++;
                                    $alldata[$rown][0]=$row['FullTitle'];
                                    $alldata[$rown][1]=$row['UnitsCoef2'];
                                    $alldata[$rown][2]=$row['size11'];
                                    $alldata[$rown][3]=$row['fesharzekhamathajm'];
                                }
                                    $theaderall="
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> وزن (کیلوگرم)</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> بهای یک متر بر اساس کیلو 67000 +ارزش افزوده</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> خودیاری یک متر</span>
        							  </th>
                                    
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> وزن (کیلوگرم)</span>
        							 </th>
                                      <th  
                                    <span class=\"f12_fontb\"> بهای یک متر بر اساس کیلو 67000 +ارزش افزوده</span>
        							  </th>
                                      <th  
                                    <span class=\"f12_fontb\"> خودیاری یک متر</span>
        							  </th>
                                </tr>"
                                ?>
                      
                   <?php
                    $pagenumber=1;
                    echo "<table width=\"95%\" align=\"center\"><tr> 
                                       <td colspan=\"5\"
                                                <span class=\"f14_font\" >  لیست وزن لوله 
                                                
                                                
                                                </span> </td>
                                                <td class=\"f14_font\" >$pagenumber </td>
                    				   </tr>
                                      
                                    <tr>".$theaderall;
                    $j=0;
                    
                    
                    $rownj=$rown;
					for($i=1;$i<=(ceil($rown/2));$i++)
                    {
                    
    					$j=$j+1;
    					if ($j>45) 
                        {
                            $pagenumber++;  
        					$j=1;
                            $rownj-=90;
                            
                            echo "</table><table width=\"95%\" align=\"center\"><p class=\"page\"></p><tr> 
                                           <td colspan=\"5\"
                                                    <span class=\"f14_font\" >  لیست وزن لوله  
                                                    
                                                    
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
							<span class="f13_font<?php echo $b; ?>">  <?php echo  $alldata[($pagenumber-1)*45+$i][0]; ?> </span> </td>
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i][1]; ?> </span> </td>
       
       
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo number_format(round($alldata[($pagenumber-1)*45+$i][1]*67000*1.09)); ?> </span> </td>
                            
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo 
                            
                            
                            number_format((selfws($alldata[($pagenumber-1)*45+$i][2],$alldata[($pagenumber-1)*45+$i][3]))); ?> </span> </td>
                            
                            <td
                            <span class="f9_font<?php echo $b; ?>"  >  <?php  
                            if ($rownj>=90)  $index=45; else   $index=ceil(($rownj)/2);
                           // echo "($rownj $index)";
                            echo $j+$index; ?> </span>  </td>
							
                            <td 
							<span class="f13_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][0]; ?> </span> </td>
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][1]; ?> </span> </td>
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo number_format(round($alldata[($pagenumber-1)*45+$i+$index][1]*67000*1.09)); ?> </span> </td>
                                              
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo number_format((selfws($alldata[($pagenumber-1)*45+$i+$index][2],$alldata[($pagenumber-1)*45+$i+$index][3]) )); ?> </span> </td>
                                                     
                            
							  

							 
                        </tr><?php

                    }
                    echo "</table>";
                    
                    

?>
 
                   
                </table>
                    </tbody>
                   
                      
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
