<?php 
/*
reorts/reports_PipeProduction.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php //include('Chartsql.php'); ?>
<?php  

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
$where="";		
$cond="";
$creditsourceID=0;
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);  
if ($login_Permission_granted==0) header("Location: ../login.php");
if ($login_ProducersID>0 && $login_PipeProducer<>1) header("Location: ../login.php");
  				
if ($_POST)
{
    $creditsourceID=$_POST['creditsourceID'];
    $Datefrom=$_POST['Datefrom'];
    $Dateto=$_POST['Dateto'];

}
else
{
    $Datefrom ='1395/01/01';
    $Dateto=gregorian_to_jalali(date('Y-m-d'));
}
        if (strlen($Datefrom)>0)
        $cond.=" and producerapprequest.Windate>='".jalali_to_gregorian($Datefrom)."'";
    if (strlen($Dateto)>0)
        $cond.=" and producerapprequest.Windate<='".jalali_to_gregorian($Dateto)."'";
    if ($creditsourceID>0) $cond.=" and applicantmaster.creditsourceID='$creditsourceID' ";
if ($login_ProducersID)  
 $cond.=" and producers.ProducersID= $login_ProducersID and producers.PipeProducer=1 ";   
/*
$query = "select producers.Title as _key,producers.Title as _value from producers
inner join  producerapprequest on producers.ProducersID=producerapprequest.ProducersID $cond
$where
order by producers.Title
";
$producersID = get_key_value_from_query_into_array($query);
*/

    $sql="select producers.Title,applicantmasterdetail.prjtypeid,case invoicetiming.ApproveA<>'' when 1 then 1 else 0 end done,count(*) cnt,
    round((sum(ifnull(PE32tonaj,0)+ifnull(PE40tonaj,0)+ifnull(PE80tonaj,0)+ifnull(PE100tonaj,0))/1000),1) tonaj,round(sum(tot)/1000000) tot
	,producers.rank corank,producers.emtiaz emtiaz,round(producers.guaranteepayval/10000000) guaranteepayval1,producers.guaranteeExpireDate guaranteeExpireDate1
	,case producers.rank when 1 then 'A' when 2 then 'A' when 3 then 'B'  when 4 then 'B'  when 5 then 'C' else '' end corankstr
	,round(guarantee.guaranteepayval/10000000) guaranteepayval2,guarantee.guaranteeExpireDate guaranteeExpireDate2 ,producers.ProducersID PID
    ,creditsource.creditsourceid,creditsource.title creditsourcetitle
	,sum((invoicetiming.score1+invoicetiming.score2+invoicetiming.score3)/3) score
    ,sum(case (invoicetiming.score1+invoicetiming.score2+invoicetiming.score3)>0 when 1 then 1 else 0 end ) cntscored
	,designerco.DesignerCoID,designerco.title DesignerCotitle
     from producerapprequest
inner join applicantmasterdetail on 
case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end=producerapprequest.applicantmasterid
    
    left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID,sum(tot) tot from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  on invoicemaster.ApplicantMasterID=producerapprequest.ApplicantMasterID 
    
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
    left outer join producers on producers.ProducersID=producerapprequest.ProducersID
    
    inner join applicantmaster on applicantmaster.applicantmasterid=producerapprequest.applicantmasterid 
    and ifnull(applicantmaster.applicantstatesID,0) not in (34)

    left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
      left outer join clerk on clerk.clerkID=invoicetiming.ClerkIDexaminer
		left outer join designerco on designerco.DesignerCoID=clerk.MMC															

    left outer join guarantee on guarantee.CoID=producers.ProducersID
    where producerapprequest.state=1 and producers.ProducersID>0 $cond
    group by producers.Title,applicantmasterdetail.prjtypeid,done";

try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
    
    
    //print 	$sql;
 $ID1[' ']=' ';  
 $producersID[' ']=' ';                   
 while($row = mysql_fetch_assoc($result))
 {
	$ID1[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);
	$ID2[trim($row['DesignerCotitle'])]=trim($row['DesignerCoID']);
	$producersID[trim($row['Title'])]=trim($row['PID']);
 }
$ID1=mykeyvalsort($ID1);
$producersID=mykeyvalsort($producersID);
mysql_data_seek( $result, 0 );
	  $permitrolsid = array("1","18","3","32");
	  if (in_array($login_RolesID, $permitrolsid))
		{$display='';$cols=26;}else {$display='display:none';$cols=11;}
	  $permitrolsid = array("1","18");
	  if (in_array($login_RolesID, $permitrolsid))
		$displayall=''; else $displayall='display:none';
 


    $rep_p=array();
    while($row = mysql_fetch_assoc($result))
    {
        $rep_p["$row[Title]"]["$row[prjtypeid]"]["$row[done]"]["cnt"]=$row['cnt'];
        $rep_p["$row[Title]"]["$row[prjtypeid]"]["$row[done]"]["tonaj"]=$row['tonaj'];
        $rep_p["$row[Title]"]["$row[prjtypeid]"]["$row[done]"]["tot"]=$row['tot'];
       
			$rep_p["$row[Title]"]["0"]["0"]["0"]=$row['corankstr'];
			$rep_p["$row[Title]"]["0"]["0"]["1"]=$row['emtiaz'];
            $rep_p["$row[Title]"]["0"]["0"]["7"]+=$row['cntscored'];
            $rep_p["$row[Title]"]["0"]["0"]["6"]+=$row['score'];
								//$Permissionvals['pdeliverytonday'];
 
		
							if ($row["corank"]==1) $rep_p["$row[Title]"]["0"]["0"]["2"]=$Permissionvals['p1Zpishhamzaman'];
                                else if ($row["corank"]==2) $rep_p["$row[Title]"]["0"]["0"]["2"]=$Permissionvals['p2Zpishhamzaman'];
                                else if ($row["corank"]==3) $rep_p["$row[Title]"]["0"]["0"]["2"]=$Permissionvals['p3Zpishhamzaman'];
                                else if ($row["corank"]==4) $rep_p["$row[Title]"]["0"]["0"]["2"]=$Permissionvals['p4Zpishhamzaman'];
                                else if ($row["corank"]==5) $rep_p["$row[Title]"]["0"]["0"]["2"]=$Permissionvals['p5Zpishhamzaman'];
                         
		
							if ($row["corank"]==1) $rep_p["$row[Title]"]["0"]["0"]["3"]=$Permissionvals['p1Zpishhamzamanvol'];
                                else if ($row["corank"]==2) $rep_p["$row[Title]"]["0"]["0"]["3"]=$Permissionvals['p2Zpishhamzamanvol'];
                                else if ($row["corank"]==3) $rep_p["$row[Title]"]["0"]["0"]["3"]=$Permissionvals['p3Zpishhamzamanvol'];
                                else if ($row["corank"]==4) $rep_p["$row[Title]"]["0"]["0"]["3"]=$Permissionvals['p4Zpishhamzamanvol'];
                                else if ($row["corank"]==5) $rep_p["$row[Title]"]["0"]["0"]["3"]=$Permissionvals['p5Zpishhamzamanvol'];
                         
							 
							 $guaranteepayval1=$row["guaranteepayval1"];$guaranteepayval2=$row["guaranteepayval2"];
							 if ($row["guaranteeExpireDate1"]<gregorian_to_jalali(date('Y-m-d'))) $guaranteepayval1='---';
							 if ($row["guaranteeExpireDate2"]<gregorian_to_jalali(date('Y-m-d'))) $guaranteepayval2='---';
							 
							 $rep_p["$row[Title]"]["0"]["0"]["4"]=$guaranteepayval1.'<br> ('.$guaranteepayval2.')';
							 
							 
							 $rep_p["$row[Title]"]["0"]["0"]["5"]=$row['PID'];
		                    
		
		
		
    }    
	
?>
<!DOCTYPE html>
<html>
<head>
  	<title>گزارش وضعیت تولیدکنندگان لوله پلی اتیلن</title>
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
                <form action="reports_PipeProduction.php" method="post" >
                <table width="95%" align="center">
                   
                  <thead>
                       
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
				  
                      <tr>
					  
                      <td  class="label">ازتاریخ:</td>
                      <td  class="data"><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1396/01/01'; } ?>" size="10" maxlength="10" /></td>
                        <span id="span1"></span>
                     <td class="label">تاتاریخ:</td>
                      <td class="data"><input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
                      
                      <?php
                      echo select_option('creditsourceID','',',',$ID1,0,'','','1','rtl',0,'',$creditsourceID,'','100%');
                      echo select_option('DesignerCoID','',',',$ID2,0,'','','1','rtl',0,'',$DesignerCoID,'','100%');
                      
                       ?>
                      
                     <span id="span2"></span>
                        <td><input name="submit" type="submit" class="button" id="submit" value="جستجو" /></td>
						
                      <td colspan=17></td>
			           <td  colspan=3 class="f7_font">تاریخ چاپ: <?php echo gregorian_to_jalali(date('Y-m-d'))."</td>";
                       
                       
                       
                       
                       
                       
                       
                       echo "</tr>";
                       
                            echo "
		                <tr> 
                            <td colspan=\"$cols\"
                            <span class=\"f14_fontcb\"  >گزارش وضعیت تولیدکنندگان لوله پلی اتیلن
                            </span>  </td>
                        </tr>
                            
                        <tr>
                            <th rowspan=\"3\"  class=\"f12_fontb\" >ردیف</th>
                            <th rowspan=\"3\"  class=\"f14_fontb\" >فروشنده/ تولیدکننده</th>
                            <th colspan=\"5\" rowspan=\"2\" class=\"f12_fontb\" >مجوز <br>(مطابق با فرایند پیشنهادی انتخاب تولیدکننده لوله پلی اتیلن)</th>
						   <th rowspan=\"3\"  class=\"f12_fontb\" >امتیاز ارزشیابی</th>
                         	
                            <th colspan=\"6\" class=\"f14_fontb\" style=\"$display\"> آبیاری تحت فشار</th>
                         	<th colspan=\"6\" class=\"f14_fontb\" style=\"$display\">  آبرسانی کم فشار</th>
							<th colspan=\"3\" class=\"f14_fontb\" >مجموع </th>
							<th colspan=\"3\" class=\"f14_fontb\" style=\"$display\">مجموع </th>
						</tr>
						<tr>
                        
						
                            <th colspan=\"3\" class=\"f14_fontb\" style=\"$display\">در دست تولید</th>
                            <th colspan=\"3\" class=\"f14_fontb\" style=\"$display\">تولید شده</th>
							<th colspan=\"3\" class=\"f14_fontb\"style=\"$display\" > در دست تولید</th>
							<th colspan=\"3\" class=\"f14_fontb\" style=\"$display\">تولید شده</th>
							<th colspan=\"3\" class=\"f14_fontb\" > در دست تولید</th>
							<th colspan=\"3\" class=\"f14_fontb\" style=\"$display\"> کل</th>
						</tr>
						<tr>
                            <th class=\"f10_fontb\" >رتبه</th>
                            <th class=\"f10_fontb\" >امتیاز</th>
                            <th class=\"f10_fontb\" >تعداد همزمان</th>
							<th class=\"f10_fontb\" >حجم تناژ همزمان</th>
                            <th class=\"f10_fontb\" >ضمانتنامه</th>
                       
                            <th class=\"f10_fontb\" style=\"$display\"><label >تعداد</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >تناژ</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >مبلغ</label></th>
                           
                            <th class=\"f10_fontb\" style=\"$display\"><label >تعداد</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >تناژ</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >مبلغ</label></th>
                    
                            <th class=\"f10_fontb\" style=\"$display\"><label >تعداد</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >تناژ</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >مبلغ</label></th>
                      
                            <th class=\"f10_fontb\" style=\"$display\"><label >تعداد</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >تناژ</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >مبلغ</label></th>
                   
                            
                            <th class=\"f10_fontb\" ><label >تعداد</label></th>
                            <th class=\"f10_fontb\" ><label >تناژ</label></th>
                            <th class=\"f10_fontb\" ><label >مبلغ</label></th>
                      
                            <th class=\"f10_fontb\" style=\"$display\"><label >تعداد</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >تناژ</label></th>
                            <th class=\"f10_fontb\" style=\"$display\"><label >مبلغ</label></th>
                        </tr><thead/>
                            ";
                   
        
                   $rown=0;
                   $s1=0;
                   $s2=0;
                   $s3=0;
                   $s4=0;
                   $s5=0;
                   $s6=0;
                   $s7=0;
                   $s8=0;
                   $s9=0;
                   $s10=0;
                   $s11=0;
                   $s12=0;
                   $s13=0;
                   $s14=0;
                   $s15=0;
                   $s16=0;
                   $s17=0;
                   $s18=0;
				   
if ($login_isfulloption==1)
	
                    foreach($producersID as $producersTitle => $producersTitle)
                    {
                        if (trim($producersTitle)=='') continue;
                        //print "(".$producersTitle.")";
                        
                        
                   $s1+=$rep_p["$producersTitle"]["0"]["0"]["cnt"];
                   $s2+=$rep_p["$producersTitle"]["0"]["0"]["tonaj"];
                   $s3+=$rep_p["$producersTitle"]["0"]["0"]["tot"];
				   
                   $s4+=$rep_p["$producersTitle"]["0"]["1"]["cnt"];
                   $s5+=$rep_p["$producersTitle"]["0"]["1"]["tonaj"];
                   $s6+=$rep_p["$producersTitle"]["0"]["1"]["tot"];
				   
                   $s7+=$rep_p["$producersTitle"]["1"]["0"]["cnt"];
                   $s8+=$rep_p["$producersTitle"]["1"]["0"]["tonaj"];
                   $s9+=$rep_p["$producersTitle"]["1"]["0"]["tot"];
				   
                   $s10+=$rep_p["$producersTitle"]["1"]["1"]["cnt"];
                   $s11+=$rep_p["$producersTitle"]["1"]["1"]["tonaj"];
                   $s12+=$rep_p["$producersTitle"]["1"]["1"]["tot"];
				   
                   $s13=$s1+$s7;
                   $s14=$s2+$s8;
                   $s15=$s3+$s9;
                   
                   $s16=$s1+$s4+$s7+$s10;
                   $s17=$s2+$s5+$s8+$s11;
                   $s18=$s3+$s6+$s9+$s12;
				   
                  	$rown++;
                
					    if ($rown%2==1) 
                        $b=''; else $b='b';
						$colortitle='';
						if ($rep_p["$producersTitle"]["0"]["0"]["0"]=='') $colortitle="red";
						
                        ?> 
                    <tr>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rown; ?> </span> </td>
                            <td <span style="color:<?php echo $colortitle?>" class="f13_font<?php echo $b; ?>">  <?php echo $producersTitle; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["0"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["1"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["2"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["3"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["4"]; ?> </span> </td>
						    <td <span class="f13_font<?php echo $b; ?>">  <?php echo round($rep_p["$producersTitle"]["0"]["0"]["6"]/$rep_p["$producersTitle"]["0"]["0"]["7"],1); ?> </span> </td>
							
							<?php
							 if(($rep_p["$producersTitle"]["0"]["0"]["cnt"])> ($rep_p["$producersTitle"]["0"]["0"]["2"]))
							$color="red";
							else
                            $color="";
							?>
							
                            <td <span style="color:<?php echo $color .';'. $display;?>" class="f13_font<?php echo $b; ?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["cnt"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["tonaj"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["0"]["0"]["tot"]; ?> </span> </td>
							
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["0"]["1"]["cnt"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["0"]["1"]["tonaj"]   ; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["0"]["1"]["tot"]; ?> </span> </td>
							
							
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["0"]["cnt"]; ?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["0"]["tonaj"]; ?> </span> </td>
			                <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["0"]["tot"]; ?> </span> </td>
							
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["1"]["cnt"];?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["1"]["tonaj"];?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo $rep_p["$producersTitle"]["1"]["1"]["tot"];?> </span> </td> 
                            
							<?php
							 if(($rep_p["$producersTitle"]["0"]["0"]["tonaj"]+$rep_p["$producersTitle"]["1"]["0"]["tonaj"])> $rep_p["$producersTitle"]["0"]["0"]["3"])
							$color="red";
							else
                            $color="";
							?>
							
                            <td <span style="color:<?php echo $color?>;padding-left: 10px;padding-right: 10px;" class="f13_font<?php echo $b; ?>">  <?php echo 
                             $rep_p["$producersTitle"]["0"]["0"]["cnt"]+
                             $rep_p["$producersTitle"]["1"]["0"]["cnt"];?> </span> </td>
						
                            <td <span style="color:<?php echo $color?>;padding-left: 10px;padding-right: 10px;" class="f13_font<?php echo $b; ?>">  <?php echo 
                            $rep_p["$producersTitle"]["0"]["0"]["tonaj"]+
                            $rep_p["$producersTitle"]["1"]["0"]["tonaj"];?> </span> </td>
                            <td <span style="color:<?php echo $color?>;padding-left: 10px;padding-right: 10px;" class="f13_font<?php echo $b; ?>">  <?php 
                            echo 
                            $rep_p["$producersTitle"]["0"]["0"]["tot"]+
                            $rep_p["$producersTitle"]["1"]["0"]["tot"];
                            ?> </span> </td>
                            
                            
                            
                             <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo 
                             $rep_p["$producersTitle"]["0"]["0"]["cnt"]+
                             $rep_p["$producersTitle"]["0"]["1"]["cnt"]+
                             $rep_p["$producersTitle"]["1"]["0"]["cnt"]+
                             $rep_p["$producersTitle"]["1"]["1"]["cnt"];?> </span> </td>
						
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php echo 
                            $rep_p["$producersTitle"]["0"]["0"]["tonaj"]+
                            $rep_p["$producersTitle"]["0"]["1"]["tonaj"]+
                            $rep_p["$producersTitle"]["1"]["0"]["tonaj"]+
                            $rep_p["$producersTitle"]["1"]["1"]["tonaj"];?> </span> </td>
                            <td <span class="f13_font<?php echo $b; ?>" style="<?php echo $display;?>">  <?php 
                            echo 
                            $rep_p["$producersTitle"]["0"]["0"]["tot"]+
                            $rep_p["$producersTitle"]["0"]["1"]["tot"]+
                            $rep_p["$producersTitle"]["1"]["0"]["tot"]+
                            $rep_p["$producersTitle"]["1"]["1"]["tot"];
                            ?> </span> </td> 
							
							<td class='no-print'><a  target='<?php echo $target;?>' href=
							<?php print "../insert/producer_notapprovedinvoice_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$rep_p["$producersTitle"]["0"]["0"]["5"].rand(10000,99999); ?>>
                            <img style = "width: 20px;" src="../img/search.png" title=' مشاهده ریز ' ></a></td>
                                               

						 
                    </tr>
                            <?php
                        
                    }
 				       $rown++;
					    if ($rown%2==1) 
                        $b=''; else $b='b';
                             ?>
                   
				   <tr style="<?php echo $displayall;?>" >
                            <td <span rowspan="2" colspan="8" class="f14_font<?php echo $b; ?>">  <?php echo 'مجموع'; ?> </span> </td>
                            
							<td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s1; ?> </span> </td>
                            <td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s2; ?> </span> </td>
                
							<td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s4; ?> </span> </td>
                            <td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s5   ; ?> </span> </td>
                           
                            <td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s7; ?> </span> </td>
							<td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s8; ?> </span> </td>
			               
							<td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s10;?> </span> </td>
							<td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s11;?> </span> </td>
                            
							<td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s13; ?> </span> </td>
                            <td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s14; ?> </span> </td>
                            
                            <td <span rowspan="2" class="f13_font<?php echo $b; ?>">  <?php echo $s16; ?> </span> </td>
                            <td <span colspan="2" style="text-align:right" class="f13_font<?php echo $b; ?>">  <?php echo $s17; ?> </span> </td>
   					</tr>
                        
				   <tr style="<?php echo $displayall;?>" >
                            <td <span colspan="2" style="text-align:left" class="f13_font<?php echo $b; ?>">  <?php echo $s3; ?> </span> </td>
							
                            <td <span colspan="2" style="text-align:left" class="f13_font<?php echo $b; ?>">  <?php echo $s6; ?> </span> </td>
							
                            <td <span colspan="2" style="text-align:left" class="f13_font<?php echo $b; ?>">  <?php echo $s9; ?> </span> </td>
							
                            <td <span colspan="2" style="text-align:left" class="f13_font<?php echo $b; ?>">  <?php echo $s12;?> </span> </td> 
							
                            <td <span colspan="2" style="text-align:left"  class="f13_font<?php echo $b; ?>">  <?php echo $s15; ?> </span> </td>
							 
                            <td <span colspan="2" style="text-align:left; <?php echo $b; ?>" class="f13_font<?php echo $b; ?>">  <?php echo $s18; ?> </span> </td>
					</tr>
				   
                   
                   
				   <tr>
				   <td colspan="5">
				   <?php echo"--- اتمام اعتبار ضمانتنامه آبیاری تحت فشار";?>
				   </td>
                   </tr>
                   <tr>
				   <td colspan="5">
				   <?php echo"(---) اتمام اعتبار ضمانتنامه آبرسانی کم فشار";?>
				   </td>
                   </tr>
                   
                   
                   <tr>
				   <td colspan="15">
				   <?php echo"
                   حجم تناژ مطابق آخرین صورتجلسه کمیته مدیریت آب و خاک در نظر گرفته شده است.";?>
				   </td>
                   </tr>
                    <tr>
                   <td colspan="15">
				   <?php echo"امتیازات مطابق با جدول لیست تولیدکنندگان مجاز لوله پلی اتیلن مجری طرح سامانه های نوین آبیاری  در نظر گرفته شده است.";?>
				   </td>
                   </tr>
                 
                   
                   
                   
                	<script src="../js/jquery-1.9.1.js"></script>
					<script src="../js/jquery.freezeheader.js"></script>

					<script language="javascript" type="text/javascript">
	
						$(document).ready(function () {
						$("#table").freezeHeader();
							})
 					</script>
                   
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
