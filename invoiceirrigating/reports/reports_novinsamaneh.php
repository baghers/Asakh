﻿<?php 
/*
reorts/reports_novinsamaneh.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
    $showa=0;
    $yearid='';
    $showc=0; 
if($_POST) 
{
    $id=$_POST['id'];
    $fees=$_POST['fees'];
    $showc=$_POST['showc'];
    $showa=$_POST['showa'];
    $creditsourceID=$_POST['creditsourceID'];
    $shahrid=$_POST['shahrid'];
    $ostan=$_POST['ostan'];
    $credityear=$_POST['credityear'];
    $designercocontractID=$_POST['designercocontractID'];
}
else 
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $id=$linearray[0];
    $yearid=$linearray[1];
    
    $showc=$linearray[2];
    $showa=$linearray[3];
    $creditsourceID=$linearray[4];
    $shahrid=$linearray[5];
    $ostan=$linearray[6];
    $credityear=$linearray[7];
    $designercocontractID=$linearray[8];
    $fees=$linearray[9];
	$contracttypeID=$linearray[10];
   
}
if ($yearid>0) $cond.="and applicantmaster.YearID='$yearid'";
if ($showc=='on') $showc=1;
if ($showa=='on') $showa=1;
         
 //print $id;
 
$selectedCityId=$login_CityId;
if ($ostan>0)
$selectedCityId=$ostan;
$cond="and substring(applicantmaster.cityid,1,2)=substring($selectedCityId,1,2)";



	if($login_RolesID==26) {$showc=1;$showm=1;$showt=1;}
	if ($showc==1) $cond.=" and ifnull(applicantmaster.criditType,0)=1 ";

	if ($login_RolesID=='17') 
		$cond.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
	else if (($login_RolesID=='14') && ($showa==0)) 
		$cond.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";


if ($credityear>0) $cond.=" and creditsource.credityear='$credityear' "; 

if ($designercocontractID>0) $cond.=" and applicantcontracts.designercocontractID='$designercocontractID' ";

    if ((trim($_POST['applicantstatesID']))>0)
        $cond.=" and applicantmaster.applicantstatesID='$_POST[applicantstatesID]'";  
    if ((trim($_POST['operatorcoid']))>0)
        $cond.=" and operatorco.operatorcoid='$_POST[operatorcoid]'";   
    if ((trim($_POST['DesignerCoIDnazerID']))>0)
        $cond.=" and DesignerCoIDnazer.DesignerCoID='$_POST[DesignerCoIDnazerID]'";
		
    if ((trim($_POST['DesignerCoIDd']))>0)
        $cond.=" and DesignerCod.DesignerCoID='$_POST[DesignerCoIDd]'";
		
    if (strlen(trim($_POST['creditbank']))>0)
        $cond.=" and case creditsource.creditbank when 2 then 'صندوق' when 1 then 'بانک' end='$_POST[creditbank]'"; 
    if ((trim($creditsourceID))>0)
        $cond.=" and applicantmaster.creditsourceID='$creditsourceID'";
    if ((trim($_POST['WaterSourceID']))>0)
        $cond.=" and watersource.WaterSourceID='$_POST[WaterSourceID]'";
    if (strlen(trim($_POST['DesignAread']))>0)
        $cond.=" and applicantmaster.DesignArea='$_POST[DesignAread]'";
    if ((trim($_POST['DesignSystemGroupsID']))>0)
        $cond.=" and designsystemgroups.DesignSystemGroupsID='$_POST[DesignSystemGroupsID]'";
    
    if (strlen(trim($_POST['ApplicantFName']))>0)
        $cond.=" and applicantmaster.ApplicantFName like '%$_POST[ApplicantFName]%'";
        
    if (strlen(trim($_POST['ApplicantName']))>0)
        $cond.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
    if ((trim($shahrid))>0)
        $cond.=" and substring(shahr.id,1,4)= substring('$shahrid',1,4)";
        
       if ((trim($login_DesignerCoID))>0)
		{
			if ($contracttypeID)
			{
				if ($contracttypeID==4)	$cond.=" and DesignerCod.DesignerCoID='$login_DesignerCoID'";
				if ($contracttypeID==1)	$cond.=" and DesignerCoIDnazer.DesignerCoID='$login_DesignerCoID'";
				if ($contracttypeID==5)	$cond.=" and DesignerCoIDnazer.DesignerCoID='$login_DesignerCoID'";
			}
			else
			 header("Location: ../login.php");

		}

 
	
///////////////////////////////////////
  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by ApplicantFName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by DesignArea'; break;
    case 4: $orderby=' order by DesignSystemGroupstitle'; break;    
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci'; break;
    case 7: $orderby=' order by applicantstatesTitle COLLATE utf8_persian_ci'; break;
    case 8: $orderby=' order by applicantmaster.TMDate'; break;
	case 9: $orderby=' order by cast(applicantmaster.sandoghcode as  decimal(10,0))'; break;
	
default: 
    if ($login_RolesID=='7' || $login_RolesID=='16')
        $orderby='order by cast(applicantmaster.sandoghcode as  decimal(10,0))';
    else     
        $orderby='order by applicantmaster.CityId,applicantstates.applicantstatesID DESC';
 break;  
  }
///////////////////////////////////////


if ($_POST)
{
     $Datefrom=$_POST['Datefrom'];
     $Dateto=$_POST['Dateto'];
}
else if (!($designercocontractID>0))
{
    //print $designercocontractID.'sa';
    $Datefrom ='1396/07/01';
    $Dateto=gregorian_to_jalali(date('Y-m-d'));
}
        if (strlen($Datefrom)>0)
        $cond.=" and applicantmaster.SaveDate>='".jalali_to_gregorian($Datefrom)."'";
		if (strlen($Dateto)>0)
        $cond.=" and applicantmaster.SaveDate<='".jalali_to_gregorian($Dateto)."'";


//print $cond;
//print retqueryaggregated($cond,$orderby.$login_limited);

    
try 
    {		
        $result = mysql_query(retqueryaggregated($cond,$orderby.$login_limited)); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    } 
    
$ID1[' ']=' ';
$ID2[' ']=' ';
$ID3[' ']=' ';
$ID4[' ']=' ';
$ID5[' ']=' ';
$ID8[' ']=' ';
$ID9[' ']=' ';
$ID10[' ']=' ';
$ID11[' ']=' ';
$ID12[' ']=' ';
$ID13[' ']=' ';
$ID14[' ']=' ';
$ID15[' ']=' ';
$ID16[' ']=' ';
$ID18[' ']=' ';
$ID19[' ']=' ';
$ID20[' ']=' ';




	

$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'نوع سیستم' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'کد' _key,9 as _value ";

$IDorder = get_key_value_from_query_into_array($query);

if (!$_POST['IDorder'])
    $IDorderval=7;
else $IDorderval=$_POST['IDorder'];



$subtitle="کامل";
                    switch ($id) 
                    {
                        case 1:$subtitle= "طرح های مطالعاتی";break;
                        case 2:$subtitle= "در دست طراحی";break;
                        case 3:$subtitle= "تکمیل پرونده";break;
                        case 4:$subtitle= " ارسال به صندوق/بانک";break;
                        case 5:$subtitle= "تکمیل تضامین";break;
                        case 6:$subtitle= "انعقاد قرارداد";break;
                        case 7:$subtitle= "در حال پیشنهاد قیمت";break;
                        case 8:$subtitle= "تهیه پیش فاکتور";break;
                        case 9:$subtitle= "تایید نهایی پیش فاکتور";break;
                        case 10:$subtitle= "در حال اجرا";break;
                        case 11:$subtitle= "آزادسازی ظرفیت";break;
                        case 12:$subtitle= "تحویل موقت";break;
                        case 13:$subtitle= "تحویل دائم";break;
                        case 14:$subtitle= "انصراف از اجرا و اختلافات";break;
                        case 15:$subtitle= "در دست اجرا";break;
                        case 16:$subtitle= "اجرا شده";break;
                    }
					
                 
  
?>
<!DOCTYPE html>
<html>
<head>
  	<title>گزارش جامع سامانه</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                
                $("#Datefrom, #simpleLabel").persiandatepicker();   
                
                $("#Dateto, #simpleLabel").persiandatepicker();   
				
            });
        
        
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
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="reports_novinsamaneh.php" method="post" enctype="multipart/form-data">
                
                <table align='center' border='1' >  
                 <thead>            
                            
				  <tr> 
                  
                          
                            
                            
                         <?php
                         
						 $query="SELECT credityear as _value,credityear as _key FROM `creditsource` order by credityear desc";
						 $ID2cy = get_key_value_from_query_into_array($query);
                           
                            $query="SELECT YearID as _value,Value as _key FROM `year` 
                             where YearID in (select YearID from cityquota)
                             
                             ORDER BY year.Value DESC";
            				 $IDs = get_key_value_from_query_into_array($query);
                             print 
                             select_option('YearID','سهمیه',',',$IDs,0,'','','1','rtl',0,'',$yearid,'','75');
                             
                             print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa'";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
    		           
                       select_option('credityear','سال',',',$ID2cy,0,'','','1','rtl',0,'',$credityear);
                       
    				   if ($login_designerCO==1)
                       {
                         $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                         where substring(ostan.id,3,5)='00000'
                         order by _key  COLLATE utf8_persian_ci";
                         $allg1id = get_key_value_from_query_into_array($sqlselect);
    			         print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId);
                       }
		 
			         $checked="";
                    if ($showc>0) $checked="checked";
                    print "<td colspan='1' class='label'>تجمیع</td>
                     <td class='data'><input name='showc' type='checkbox' id='showc' $checked /></td>";
                     
					print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');


                  $style="";$styleoff="style='display:none'";$col=0;$td="<td colspan='4'></td>";
				   if ($fees==1) 
				   {$subtitle= "حق الزحمه";$style="style='display:none'";$styleoff="";$col=-2;$td="";}
 					
					         ?>
                         </td>
						      <td  class="label">ازتاریخ:</td>
                      <td  class="data"><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1396/01/01'; } ?>" size="10" maxlength="10" /></td>
                        <span id="span1"></span>
                     <td class="label">تاتاریخ:</td>
                      <td class="data"><input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
            <td class="data"><input name="id" type="hidden" class="textbox" id="id"  value="<?php echo $id; ?>"  /></td>
            <td class="data"><input name="fees" type="hidden" class="textbox" id="fees"  value="<?php echo $fees; ?>"  /></td>
            <td class="data"><input name="designercocontractID" type="hidden" class="textbox" id="designercocontractID"  value="<?php echo $designercocontractID; ?>"  /></td>
         	<td colspan="20" style="text-align:left;" ><input name="submit" type="submit" class="button" id="submit"  value="جستجو" /></td>
     			   </tr>
				   
				 </thead>            
                   </table>
                <table align='center' border='1' id="table2">  
                 <thead>            
                            
		           <tr>
                              <td colspan=<?php echo 28-$col; ?>
                            <span class="f14_fontb" >گزارش جامع طرح های آبیاری (مبالغ به میلیون ریال) <?php echo "<br>($subtitle)"; ?></span>  
                            </td>
                    <tr>        
					
					 <tr>
                            <th rowspan="2" class="f8_fontb" > رديف   </th>
							<th colspan="2" class="f12_fontb"> مشخصات مالک </th>
							<th colspan="3" class="f12_fontb"> مشخصات سیستم آبیاری  </th>
							<th colspan="2" class="f12_fontb">محل پروژه</th>
						    <th colspan="3" class="f12_fontb">اطلاعات منبع آبی</th>
							<th colspan="7" class="f12_fontb">اطلاعات منبع اعتبار </th>
							<th <?php echo $style; ?>  colspan="4" class="f12_fontb">اطلاعات زمانبندی پروژه </th>
							<th colspan="3" class="f12_fontb">اطلاعات اجرایی پروژه </th>
							<th <?php echo $style; ?> colspan="3" class="f12_fontb">درصد پیشرفت </th>
							<th <?php echo $styleoff; ?> colspan="2" class="f12_fontb">درصد پیشرفت </th>
							<th <?php echo $styleoff; ?> colspan="3" class="f12_fontb">حق الزحمه </th>
											
                        </tr>
					
					
					 <tr>
                            <th class="f10_fontb"> نام   </th>
							<th class="f10_fontb"> نام خانوادگی  </th>
							<th class="f10_fontb"> نوع سیستم  </th>
							<th class="f10_fontb"> مساحت (ha) </th>
						    <th class="f10_fontb"> مساحت تفکیکی </th>
						    <th class="f10_fontb">شهرستان</th>
							<th class="f10_fontb">روستا</th>
							<th class="f10_fontb">نوع</th>
							<th class="f10_fontb">مختصات جغرافیایی</th>
							<th class="f10_fontb">دبی </th>
							<th class="f10_fontb">نوع اعتبار </th>
							<th class="f10_fontb">بانک/ صندوق </th>
							<th class="f10_fontb">جمع کل</th>
							<th class="f10_fontb">بلاعوض</th>
							<th class="f10_fontb">خودیاری</th>
							<th class="f10_fontb">طراح</th>
							<th class="f10_fontb">بازبین</th>
							<th <?php echo $style; ?> class="f10_fontb">اولین پرداختی </th>
							<th <?php echo $style; ?> class="f10_fontb">تحویل زمین </th>
							<th <?php echo $style; ?> class="f10_fontb"> تحویل موقت</th>
							<th <?php echo $style; ?> class="f10_fontb"> تحویل قطعی </th>
							<th class="f10_fontb">دستگاه نظارت</th>
							<th class="f10_fontb">شرکت مجری</th>
							<th class="f10_fontb">ناظر بهره برداری</th>
							
							<th class="f10_fontb">وضعیت</th>			
							<th class="f10_fontb">فیزیکی</th>			
							<th <?php echo $style; ?> class="f10_fontb">مالی</th>			
							
							<th <?php echo $styleoff; ?> colspan="2" class="f10_fontb">ضرایب</th>			
							<th <?php echo $styleoff; ?> class="f10_fontb">مبلغ(ریال)</th>			
												
                        </tr>
						 </thead>            
                
					 
		                   
                          
                           
<?php
 
                    $Total=0;
                    $rown=0;
                    $Description="";
                    $LastTotal = 0;
                    $hekbarani = 0;
                    $hekghatreei = 0;
                    $hekkamfeshar = 0;
					$AM = 0;
                    $pstr="";
                    $cnttot=0;
			
					$designercocontractIDfees=0;
                    while($row = mysql_fetch_assoc($result))
                    {
					
				      if ($id>0)
                        {
                            $total1++;
                            if ($id==1){if ($total1%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total1,$b,$fees);
                            
    							if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"]; 
									$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"];
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']);$ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);
							}
                            $hek1+=$row['DesignAread'];
                            $bela1+=$row['belaavazd'];
                            $lasttotal1+=$row['LastTotald'];
                            $lastfehrest1+=$row['LastFehrestbahad'];
                            $Totlainvoice1+=$row['TotlainvoiceValuesd'];
                            $self1+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                            if (in_array($row['applicantstatesIDd'],$indesignstates))
                            {
									$total2++;
								if ($id==2){if ($total2%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total2,$b,$fees);
								if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];
											$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
									$hek2+=$row['DesignAread'];
									$bela2+=$row['belaavazd'];
									$lasttotal2+=$row['LastTotald'];
									$lastfehrest2+=$row['LastFehrestbahad'];
									$Totlainvoice2+=$row['TotlainvoiceValuesd'];
									
									$self2+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
                            }
                            else  if (in_array($row['applicantstatesIDd'],array("12","36","22","37")))//ارسال به صندوق یا بانک
                            {
									$total4++;
									if ($id==4){if ($total4%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total4,$b,$fees);
									if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
									$hek4+=$row['DesignAread'];
									$bela4+=$row['belaavazd'];
									$lasttotal4+=$row['LastTotald'];
									$lastfehrest4+=$row['LastFehrestbahad'];
									$Totlainvoice4+=$row['TotlainvoiceValuesd'];
									$self4+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
									
									if (in_array($row['applicantstatesIDd'],array("12","36")))//تکمیل تضامین
									{
										$total5++;
										if ($id==5){if ($total5%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total5,$b,$fees);
										if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								 $ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']);$ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
										$hek5+=$row['DesignAread'];
										$bela5+=$row['belaavazd'];   
										$lasttotal5+=$row['LastTotald'];
										$lastfehrest5+=$row['LastFehrestbahad'];
										$Totlainvoice5+=$row['TotlainvoiceValuesd'];
										$self5+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
									}
									else //انعقاد قرارداد
									{
										$total6++;
										if ($id==6){if ($total6%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total6,$b,$fees);
										if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								 $ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']);$ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
										$hek6+=$row['DesignAread'];
										$bela6+=$row['belaavazd'];  
										$lasttotal6+=$row['LastTotald'];
										$lastfehrest6+=$row['LastFehrestbahad'];
										$Totlainvoice6+=$row['TotlainvoiceValuesd'];
										$self6+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];  
										if(!($row['applicantstatesIDop']>0))//درحال پیشنهاد قیمت
										{
											$total7++;
											if ($id==7){if ($total7%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total7,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$hek7+=$row['DesignAread'];
											$bela7+=$row['belaavazd'];
											$lasttotal7+=$row['LastTotald'];
											$lastfehrest7+=$row['LastFehrestbahad'];
											$Totlainvoice7+=$row['TotlainvoiceValuesd'];
											$self7+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
										}       
										else if (in_array($row['applicantstatesIDop'],array("30","35","38")))//تایید نهایی پیشفاکتور و آزادسازی 
										{
											$total15++;
											if ($id==15){if ($total15%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total15,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$total9++;
											if ($id==9){if ($total9%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total9,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$hek9+=$row['DesignAreaop'];
											$bela9+=$row['belaavazop'];   
											$lasttotal9+=$row['LastTotalop'];
											$lastfehrest9+=$row['LastFehrestbahaop'];
											$Totlainvoice9+=$row['TotlainvoiceValuesop'];
											$self9+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
											if ($row['permanentfree']==1 && $row['applicantstatesIDoplist']==45)//ـحویل دائم
											{
												$total16++;
												if ($id==16){if ($total16%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total16,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$total13++;
												if ($id==13){if ($total13%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total13,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								 $ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']);$ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$hek13+=$row['DesignAreaoplist'];
												$bela13+=$row['belaavazoplist'];  
												$lasttotal13+=$row['LastTotaloplist'];
												$lastfehrest13+=$row['LastFehrestbahaoplist'];
												$Totlainvoice13+=$row['TotlainvoiceValuesoplist'];
												$self13+=$row['selfcashhelpval']+$row['selfnotcashhelpval']; 
											}
											else if ($row['applicantstatesIDoplist']==45)//ـحویل موقت
											{
												$total16++;
												if ($id==16){if ($total16%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total16,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
							   $ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']);  $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$total12++;
												if ($id==12){if ($total12%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total12,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$hek12+=$row['DesignAreaoplist'];
												$bela12+=$row['belaavazoplist'];  
												$lasttotal12+=$row['LastTotaloplist']; 
												$lastfehrest12+=$row['LastFehrestbahaoplist'];
												$Totlainvoice12+=$row['TotlainvoiceValuesoplist'];
												$self12+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
											}
											else if ($row['applicantstatesIDop']==35)//آزادسازی ظرفیت
											{
												$total16++;
												if ($id==16){if ($total16%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total16,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$total11++;
												if ($id==11){if ($total11%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total11,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$hek11+=$row['DesignAreaoplist'];
												$bela11+=$row['belaavazoplist']; 
												$lasttotal11+=$row['LastTotaloplist'];
												$lastfehrest11+=$row['LastFehrestbahaoplist'];
												$Totlainvoice11+=$row['TotlainvoiceValuesoplist'];
												$self11+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];  
											}
											else //درحال اجرا
											{
												$total10++;
												if ($id==10){if ($total10%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total10,$b,$fees);
												if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
												$hek10+=$row['DesignAreaop'];
												$bela10+=$row['belaavazop'];   
												$lasttotal10+=$row['LastTotalop'];
												$lastfehrest10+=$row['LastFehrestbahaop'];
												$Totlainvoice10+=$row['TotlainvoiceValuesop'];
												$self10+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
											}
											
										}
										else if ($row['applicantstatesIDop']==34)//انصراف از اجرا
										{
											$total14++;
											if ($id==14){if ($total14%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total14,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$hek14+=$row['DesignAreaop'];
											$bela14+=$row['belaavazop'];
											$lasttotal14+=$row['LastTotalop'];
											$lastfehrest14+=$row['LastFehrestbahaop'];
											$Totlainvoice14+=$row['TotlainvoiceValuesop'];
											$self14+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
											
										}
											
										else//تهیه پیش فاکتور
										{
											$total15++;
											if ($id==15){if ($total15%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total15,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$total8++;
											if ($id==8){if ($total8%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total8,$b,$fees);
											if ($row["LastTotaloplist"]>0)  
									$LastTotal+= $row["LastTotaloplist"];
									else if ($row["LastTotalop"]>0)  
										$LastTotal+= $row["LastTotalop"];  
										else  
											$LastTotal+= $row["LastTotald"]; 
											
								if ($row["DesignAreaoplist"]>0)  
									$DesignArea+= $row["DesignAreaoplist"];
									else if ($row["DesignAreaop"]>0)  
										$DesignArea+= $row["DesignAreaop"];  
										else  
											$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
											
											
								if ($row["belaavazoplist"]>0)  
									$belaavaz+= $row["belaavazoplist"];
									else if ($row["belaavazop"]>0)  
										$belaavaz+= $row["belaavazop"];  
										else  
											$belaavaz+= $row["belaavazd"]; 
											
							   $sumfreep+= $row["sumfreep"];
							   $progressf+=$row['progress'];
							   
							   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
								$progressM=round($progressM/10000,1);	
								$cnttot++;
							   $progressma+=$progressM; 
							   
								$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
											$hek8+=$row['DesignAread'];
											$bela8+=$row['belaavazd'];
											$lasttotal8+=$row['LastTotald'];
											$lastfehrest8+=$row['LastFehrestbahad'];
											$Totlainvoice8+=$row['TotlainvoiceValuesd'];
											$self8+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
										}  
									}
							}
							else//تکمیل پرونده
							{
										$total3++;
										if ($id==3){if ($total3%2==1) $b='b'; else $b=''; $pstr.=echorow($row,$total3,$b,$fees);
										if ($row["LastTotaloplist"]>0)  
										$LastTotal+= $row["LastTotaloplist"];
										else if ($row["LastTotalop"]>0)  
											$LastTotal+= $row["LastTotalop"];  
											else  
												$LastTotal+= $row["LastTotald"]; 
												
									if ($row["DesignAreaoplist"]>0)  
										$DesignArea+= $row["DesignAreaoplist"];
										else if ($row["DesignAreaop"]>0)  
											$DesignArea+= $row["DesignAreaop"];  
											else  
												$DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
												
												
									if ($row["belaavazoplist"]>0)  
										$belaavaz+= $row["belaavazoplist"];
										else if ($row["belaavazop"]>0)  
											$belaavaz+= $row["belaavazop"];  
											else  
												$belaavaz+= $row["belaavazd"]; 
												
								   $sumfreep+= $row["sumfreep"];
								   $progressf+=$row['progress'];
								   
								   $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
									$progressM=round($progressM/10000,1);	
									$cnttot++;
								   $progressma+=$progressM; 
								   
									$ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);}
										$hek3+=$row['DesignAread'];
										$bela3+=$row['belaavazd'];
										$lasttotal3+=$row['LastTotald'];
										$lastfehrest3+=$row['LastFehrestbahad'];
										$Totlainvoice3+=$row['TotlainvoiceValuesd'];
										$self3+=$row['selfcashhelpval']+$row['selfnotcashhelpval'];
							}
                            
                        
                        }
                        else
                        {                    
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b=''; 
                            
                            $ID19[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoIDd']); $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);$ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);$ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);  $ID10[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsID']);$ID11[trim($row['DesignAread'])]=trim($row['DesignAread']);$ID12[trim($row['watersourceTitle'])]=trim($row['WaterSourceID']);$ID13[trim($row['creditsourceTitle'])]=trim($row['creditsourceID']);$ID14[trim($row['creditbank'])]=trim($row['creditbank']);$ID15[trim($row['DesignerCoIDnazerTitle'])]=trim($row['DesignerCoIDnazerID']);$ID16[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);$ID18[trim($row['applicantstatesTitle'])]=trim($row['applicantstatesID']);
                            //print_r ($row)."<br>".$row["LastTotal"];exit;
                            $pstr.=echorow($row,$rown,$b,$fees);
                            
                            if ($row["LastTotaloplist"]>0)  
                                $LastTotal+= $row["LastTotaloplist"];
                                else if ($row["LastTotalop"]>0)  
                                    $LastTotal+= $row["LastTotalop"];  
                                    else  
                                        $LastTotal+= $row["LastTotald"]; 
                                        
                            if ($row["DesignAreaoplist"]>0)  
                                $DesignArea+= $row["DesignAreaoplist"];
                                else if ($row["DesignAreaop"]>0)  
                                    $DesignArea+= $row["DesignAreaop"];  
                                    else  
                                        $DesignArea+= $row["DesignAread"];$hekbarani += $row["hekbarani"];$hekghatreei += $row["hekghatreei"];$hekkamfeshar += $row["hekkamfeshar"]; 
                                        
                                        
                            if ($row["belaavazoplist"]>0)  
                                $belaavaz+= $row["belaavazoplist"];
                                else if ($row["belaavazop"]>0)  
                                    $belaavaz+= $row["belaavazop"];  
                                    else  
                                        $belaavaz+= $row["belaavazd"]; 
                                        
    					   $sumfreep+= $row["sumfreep"];
                           $progressf+=$row['progress'];
                           
                           $progressM=$row["sumfreep"]/($row["belaavazd"]+$row["selfcashhelpval"]+$row["selfnotcashhelpval"]);
                            $progressM=round($progressM/10000,1);	
                            $cnttot++;
                           $progressma+=$progressM; 
                           
                         }
                             
                    }
				    $ID1=mykeyvalsort($ID1);
                    $ID2=mykeyvalsort($ID2);
                   	$ID4=mykeyvalsort($ID4);
                   	$ID10=mykeyvalsort($ID10);
                   	$ID11=mykeyvalsort($ID11);
                   	$ID12=mykeyvalsort($ID12);
                   	$ID13=mykeyvalsort($ID13);
                   	$ID14=mykeyvalsort($ID14);
                   	$ID15=mykeyvalsort($ID15);
                   	$ID16=mykeyvalsort($ID16);
                  	$ID17=mykeyvalsort($ID17);
                   	$ID18=mykeyvalsort($ID18);
                  	$ID19=mykeyvalsort($ID19);
                 	$ID20=mykeyvalsort($ID20);
                    
					
                    print "	 <tr class='no-print'>    
							<td class='f14_font'></td>".
							 select_option('ApplicantFName','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFName,'','100%')
							.select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','100%')
							.select_option('DesignSystemGroupsID','',',',$ID10,0,'','','1','rtl',0,'',$ID10,'','100%')
							.select_option('DesignAread','',',',$ID11,0,'','','2','rtl',0,'',$ID11,'','100%')
							.select_option('shahrid','',',',$ID1,0,'','','2','rtl',0,'',$shahrid,"",'100%')
							.select_option('WaterSourceID','',',',$ID12,0,'','','3','rtl',0,'',$ID12,'','100%')
							.select_option('creditsourceID','',',',$ID13,0,'','','1','rtl',0,'',$creditsourceID,'','100%')
							.select_option('creditbank','',',',$ID14,0,'','','4','rtl',0,'',$ID14,'','100%')
							
							.select_option('DesignerCoIDd','',',',$ID19,0,'','','1','rtl',0,'',$ID19,'','100%')
                        	.select_option('bazbin','',',',$ID20,0,'','','1','rtl',0,'',$ID20,'','100%')
                            .$td
							.select_option('DesignerCoIDnazerID','',',',$ID15,0,'','','1','rtl',0,'',$ID15,'','100%')
							.select_option('operatorcoid','',',',$ID16,0,'','','1','rtl',0,'',$ID16,'','100%')
							.select_option('ID17','',',',$ID17,0,'','','1','rtl',0,'',$ID17,'','100%')
							.select_option('applicantstatesID','',',',$ID18,0,'','','3','rtl',0,'',$applicantstatesID,'','100%').
                            "<td colspan='1'></td><td colspan='1''></td>";
                            
                    print $pstr;	  
                   
                         
?>

                     <tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'مساحت کل (هکتار)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo floor($DesignArea);   ?></td>
	                 </tr>
		             <tr>
                            <td colspan="16" class="f14_font" ><?php echo 'مساحت قطره ای (هکتار)';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo floor($hekghatreei);   ?></td>
	                 </tr>
                      <tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'مساحت بارانی (هکتار)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo floor($hekbarani);   ?></td>
	                 </tr>
                      <tr>
                            <td colspan="16" class="f14_font" ><?php echo 'مساحت کم فشار (هکتار)';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo floor($hekkamfeshar);   ?></td>
	                 </tr>
                        <tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'مجموع مبلغ كل (میلیون ریال)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo floor($LastTotal*10)/10;   ?></td>
	                 </tr>
		               <tr>
                            <td colspan="16" class="f14_font" ><?php echo 'مجموع مبلغ بلاعوض (میلیون ریال)';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo floor($belaavaz*10)/10;   ?></td>
	                 </tr>
				    <tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'مجموع مبلغ آزادسازي (میلیون ریال)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo floor($sumfreep/100000)/10;   ?></td>
	               </tr>
				    <tr>
                            <td colspan="16" class="f14_font" ><?php echo 'میانگین پیشرفت فیزیکی (درصد)';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo round($progressf/$cnttot,1);   ?></td>
	               </tr>
				    <tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'میانگین پیشرفت مالی (درصد)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo round($progressma/$cnttot,1);   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_font" ><?php echo 'مبلغ قرارداد(ریال';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo $per;   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_fontb" ><?php echo ' حق الزحمه مشاور(ریال)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo $per;   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_font" ><?php echo 'ضریب پیشنهادی';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo  $per;   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'مالیات برارزش افزوده';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo  $per;   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_font" ><?php echo 'جمع حق الزحمه مشاور(ریال)';   ?></td>
                            <td colspan="12" class="f14_font" ><?php echo  $per;   ?></td>
	               </tr>
					<tr>
                            <td colspan="16" class="f14_fontb" ><?php echo 'جمع پرداختی (ریال)';   ?></td>
                            <td colspan="12" class="f14_fontb" ><?php echo  $per;   ?></td>
	               </tr>
 	
  
                </table>
                
                	<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
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
