<?php 
/*
reorts/reports_applicantstatedateDesign.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');
require ('../includes/functiong.php');

$showa=0;  

if ($_POST['showa']=='on')
    $showa=1;
    
  $str="";
  $sos=$_POST['sos'];
  if (($showa!=1)) $str.=" and applicantmaster.applicantstatesID not in (12,22,36,37) ";
  
  if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)
        $str.=" and designsystemgroups.title='$_POST[DesignSystemGroupstitle]'";	
		
	if (strlen(trim($_POST['ApplicantFname']))>0)
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
		
	if (strlen(trim($_POST['ApplicantName']))>0)
        $str.=" and applicantmaster.ApplicantName like'%$_POST[ApplicantName]%'";
		
	 if (strlen(trim($_POST['sos']))>0)
        $str.=" and shahr.id='$_POST[sos]'";
		
	if (strlen(trim($_POST['DesignerCoID']))>0)
        $str.=" and applicantmaster.DesignerCoID='$_POST[DesignerCoID]'";
		
	if (strlen(trim($_POST['dateID']))>0)
        $str.=" and appchangestate.SaveDate='$_POST[dateID]'";  
		
    if (strlen(trim($_POST['IDArea']))>0)
		if (trim($_POST['IDArea'])==1)
        $str.=" and applicantmaster.DesignArea>0 and applicantmaster.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $str.=" and applicantmaster.DesignArea>10 and applicantmaster.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $str.=" and applicantmaster.DesignArea>20 and applicantmaster.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $str.=" and applicantmaster.DesignArea>50 and applicantmaster.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $str.=" and applicantmaster.DesignArea>100 and applicantmaster.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $str.=" and applicantmaster.DesignArea>200 and applicantmaster.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $str.=" and applicantmaster.DesignArea>500 and applicantmaster.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $str.=" and applicantmaster.DesignArea>1000";
	
    if (strlen(trim($_POST['IDprice']))>0)	
        if (trim($_POST['IDprice'])==1)
		$str.=" and applicantmaster.LastTotal>0 and applicantmaster.LastTotal<=1000000000";
		else if (trim($_POST['IDprice'])==2)
		$str.=" and applicantmaster.LastTotal>1000000000 and applicantmaster.LastTotal<=1500000000";
		else if (trim($_POST['IDprice'])==3)
		$str.=" and applicantmaster.LastTotal>1500000000 and applicantmaster.LastTotal<=2000000000";
		else if (trim($_POST['IDprice'])==4)
		$str.=" and applicantmaster.LastTotal>2000000000 and applicantmaster.LastTotal<=3000000000";
		else if (trim($_POST['IDprice'])==5)
		$str.=" and applicantmaster.LastTotal>3000000000 and applicantmaster.LastTotal<=5000000000";
		else if (trim($_POST['IDprice'])==6)
		$str.=" and applicantmaster.LastTotal>5000000000 and applicantmaster.LastTotal<=8000000000";
		else if (trim($_POST['IDprice'])==7)
		$str.=" and applicantmaster.LastTotal>8000000000 and applicantmaster.LastTotal<=10000000000";
		else if (trim($_POST['IDprice'])==8)
		$str.=" and applicantmaster.LastTotal>10000000000";
        
    if (strlen(trim($_POST['IDbela']))>0)	
        if (trim($_POST['IDbela'])==1)
		$str.=" and applicantmaster.belaavaz>0 and applicantmaster.belaavaz<=1000";
		else if (trim($_POST['IDbela'])==2)
		$str.=" and applicantmaster.belaavaz>1000 and applicantmaster.belaavaz<=1500";
		else if (trim($_POST['IDbela'])==3)
		$str.=" and applicantmaster.belaavaz>1500 and applicantmaster.belaavaz<=2000";
		else if (trim($_POST['IDbela'])==4)
		$str.=" and applicantmaster.belaavaz>2000 and applicantmaster.belaavaz<=3000";
		else if (trim($_POST['IDbela'])==5)
		$str.=" and applicantmaster.belaavaz>3000 and applicantmaster.belaavaz<=5000";
		else if (trim($_POST['IDbela'])==6)
		$str.=" and applicantmaster.belaavaz>5000 and applicantmaster.belaavaz<=8000";
		else if (trim($_POST['IDbela'])==7)
		$str.=" and applicantmaster.belaavaz>8000 and applicantmaster.belaavaz<=10000";
		else if (trim($_POST['IDbela'])==8)
		$str.=" and applicantmaster.belaavaz>10000";  

if ($login_RolesID=='10') 
		{$str.="and designerco.DesignerCoID='$login_DesignerCoID'";
        $hide='display:none';}
if ($_POST['ostan']>0) 
   {$selectedCityId=$_POST['ostan'];$str.="and substring(applicantmaster.cityid,1,2)=substring('$_POST[ostan]',1,2)";}
  else
   {$str.="and substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)";}

try 
    {		
        $result = mysql_query(sql_reports_applicantstatedateDesign($str).$login_limited);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }              



 

$query="
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDprice = get_key_value_from_query_into_array($query);
if ($_POST['IDprice']>0)
    $IDpriceval=$_POST['IDprice'];
    
$query="
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDbela= get_key_value_from_query_into_array($query);
if ($_POST['IDbela']>0)
    $IDbelaval=$_POST['IDbela'];


$query="
select '0-10' _key,1 as _value union all 
select '10-20' _key,2 as _value union all
select '20-50' _key,3 as _value union all
select '50-100' _key,4 as _value union all
select '100-200' _key,5 as _value union all
select '200-500' _key,6 as _value union all
select '500-1000' _key,7 as _value union all
select '<1000' _key,8 as _value ";
$IDArea = get_key_value_from_query_into_array($query);
if ($_POST['IDArea']>0)
    $IDAreaval=$_POST['IDArea'];
	
	

?>


<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پیشرفت طرح های مطالعاتی</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
            <form action="reports_applicantstatedateDesign.php" method="post">
             
                <table id="records" width="95%" align="center">
                   
                   
                   <tbody>
                   
                <table align='center' class="page" border='1' id="table2">              
                  <thead> 
				  <tr> 
				  
                            <td colspan="17"
                            <span class="f14_fontb" >گزارش پیشرفت طرح های مطالعاتی</span>  <a href="chart_applicantstatedateDesign.php" target="_blank">
							<img title=" نمودار درصد رتبه پيشنهاد دهندگان انتخابي " src="../img/chart.png" style="width: 25px;">
							</a></td>
				 <?php
				   $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                     where substring(ostan.id,3,5)='00000'
                     order by _key  COLLATE utf8_persian_ci";
                     $allg1id = get_key_value_from_query_into_array($sqlselect);
			
  				 if ($login_designerCO==1)
				  print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId);
					?>
                 			
				   </tr>
                        <tr>

                            <th  
                           	<span class="f14_fontb" > رديف  </span> </th>
							<th 
                           	<span class="f14_fontb"> نام  </span> </th>
							<th 
                           	<span class="f14_fontb"> نام خانوادگی </span> </th>
							<th  
                            <span class="f14_fontb"> مساحت </span>
							 (ha)  </th>
                            <th   class="f14_fontb"> نوع سیستم  </th>
						    <th 
                            <span class="f14_fontb">دشت/ شهرستان</span> </th>
							<th  
                            <span class="f14_fontb">همراه</span> </th>
							<th 
                            <span class="f14_fontb">شرکت طراح</span> </th>
							<th  
                            <span class="f14_fontb"> مبلغ کل </span>
						    <th  <span class="f14_fontb" style=<?php echo $hide; ?>>کمک بلاعوض</span> </th>
						   	<th  <span class="f14_fontb">تاریخ ثبت اولیه</span> </th>
							<th  <span class="f14_fontb">تاریخ ارسال به بازبین</span> </th>
							<th  <span class="f14_fontb">تاریخ عودت به مشاور</span> </th>
                            <th  <span class="f14_fontb">تاریخ ارسال اصلاحی به بازبین</span> </th>
						    <th  <span class="f14_fontb">تاریخ ارسال به مدیر آب و خاک </span> </th>
                             <th  <span class="f14_fontb" style=<?php echo $hide; ?>>تاریخ ارسال به صندوق</span> </th>
                            <th  <span class="f14_fontb" style=<?php echo $hide; ?>>تاریخ تایید نهایی طرح</span> </th>
						    <th></th>
						  
							
                        </tr>
						</thead>  
							<td class="f14_font"></td>
                            <?php /* print select_option('ApplicantFname','',',',$ID8,0,'','','1','rtl',0,'',$ApplicantFname,'','70'); ?>
							 <?php print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'','70'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'','30'); ?>
							<?php print select_option('DesignSystemGroupstitle','',',',$ID9,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'','80'); ?>
					       <?php print select_option('sos','',',',$ID2,0,'','','1','rtl',0,'',$sos,"",'65'); ?> 
					       <?php print select_option('DesignerCoID','',',',$ID4,0,'','','1','rtl',0,'',$DesignerCoID,'','70') ?> 
					       <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'','50'); ?>  
					       <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'','50');*/ ?> 
					   
                          <?php 
                          if ($login_RolesID==5 || $login_designerCO==1)
                             $cols= "8";
                             
                          echo "<td style=\"text-align:left;\" colspan=$cols><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\"
                           value=\"جستجو\" /></td>
                            <td colspan='1' class='label'>همه</td>
                                <td class='data'><input name='showa' type='checkbox' id='showa'";
                         if ($showa>0) echo 'checked';
                         print " /></td> </tr>";
					 
                  
                   $rown=0;
				   
				  						
                        while($row2 = mysql_fetch_assoc($result)){
						//print $row2['CityId'];
                        //exit;
                        if ($login_RolesID=='17' && substr($row2['CityId'],0,4)<>substr($login_CityId,0,4) ) 
						continue;
                        
                            $firstsave=$row2['firstsave'];
                            $sendtobazbin=$row2['sendtobazbin'];
                            $bazbintomoshaver=$row2['bazbintomoshaver'];
                            $lastsendtoBazbin=$row2['lastsendtoBazbin'];
                            $sendtabokhak=$row2['sendtabokhak'];
                            $abokhaktosandogh=$row2['abokhaktosandogh'];
                            $lasttaid=$row2['lasttaid'];
                            
                                            
                        $ApplicantName = $row2['ApplicantName'];
                        $ApplicantFName = $row2['ApplicantFName'];
                       
                        $sumL=$row2['LastTotal'];
                        
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f12_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							
                            <td 
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $row2['DesignSystemGroupstitle']); ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['mobile']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['DesignerCotitle']; ?> </span> </td>
                           
                            <td
							<span class="f13_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                           
                                              
                           
                            <td style=<?php echo $hide; ?> <span class="f13_font<?php echo $b; ?>">  <?php echo $row2['belaavaz']; ?> </span> </td>
                            
                                                     
						   <td
							<span class="f12_font<?php echo $b; ?>"> </span><?php if ($firstsave!="") echo gregorian_to_jalali($firstsave); ?> </td>
                            
						<td
							<span class="f12_font<?php echo $b; ?>"> </span><?php if ($sendtobazbin!="") echo gregorian_to_jalali( $sendtobazbin); ?> </td>
                           	
                            
						<td
							<span class="f12_font<?php echo $b; ?>"> </span><?php if ($bazbintomoshaver!="") echo gregorian_to_jalali($bazbintomoshaver); ?> </td>
                            <td
							<span class="f12_font<?php echo $b; ?>"> </span><?php if ($lastsendtoBazbin!="") echo gregorian_to_jalali($lastsendtoBazbin); ?> </td>
                            
            <td <span class="f12_font<?php echo $b; ?>"><?php if ($sendtabokhak!="") echo gregorian_to_jalali( $sendtabokhak); ?></span> </td>  
	<td style=<?php echo $hide; ?> <span class="f12_font<?php echo $b; ?>"><?php if ($abokhaktosandogh!="") echo gregorian_to_jalali( $abokhaktosandogh); ?></span> </td>
    <td  style=<?php echo $hide; ?> <span class="f12_font<?php echo $b; ?>"><?php if ($lasttaid!="") echo gregorian_to_jalali( $lasttaid); ?></span> </td>
                        
                        <?php
                        $ID = $row2['ApplicantMasterID'].'_0_'.$row2['DesignerCoID'].'_0_'.$row2['applicantstatesID']
                        .'_'.$login_RolesID;
                        
                        $permitrolsid = array("1", "13","14","5","11","18","19","20","7","16",'17','22','26','27','30');if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a  target='".$target."' href='../appinvestigation/applicantstates_detail.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = \"width: 20px;\" src=\"../img/refresh.png\" title=' مشاهده ریز عملیات ' ></a></td>";
                            echo "</tr>";
                    }
                 ?>

                </table>
				<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
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
