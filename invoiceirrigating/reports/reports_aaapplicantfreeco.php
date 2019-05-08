<?php 
/*
reorts/reports_aaapplicantfreeco.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php require ('../includes/functions.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
    $showa=0;
    $yearid='';

	
 if ($login_RolesID=='3') 
        {$str.=" and producers.producersID='$login_ProducersID' ";
		$hide='display:none';}
if ($login_RolesID=='2') 
		{$str.="and applicantfreedetail.producersID='-1' and operatorco.operatorcoid='$login_OperatorCoID'";
        $hide='display:none';}
		
if ($_POST)
{   
    $Datefromst=$_POST['Datefrom'];
    $Datetost=$_POST['Dateto'];
    $yearid=$_POST['YearID'];
    if ($_POST['showa']=='on')
    $showa=1;
}

        
 if (strlen(trim($_POST['sos']))>0)
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)
        $str.=" and applicantmaster.operatorcoid='$_POST[operatorcoid]'";
    if (strlen(trim($_POST['applicantstatesID']))>0)
        $str.=" and applicantstates.applicantstatesID='$_POST[applicantstatesID]'";  
	if (strlen(trim($_POST['ApplicantFname']))>0)
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)
        $str.=" and applicantmaster.ApplicantName like'%$_POST[ApplicantName]%'";
    
	if (strlen(trim($_POST['IDpipe']))>0)
        $str.=" and case applicantfreedetail.producersID when -1 then 'مجری'  when -2 then 'کشاورز' else case producers.PipeProducer 
		when 1 then 'لوله پلي اتيلن' when 2 then 'نوار تيپ' when 3 then 'فيلتراسيون' when 4 then 'پمپ و الكتروموتور' when 5 then 'دستگاه باراني' when 6 then 'ساير اتصالات' 
		when 101 then 'لوله پلي اتيلن' when 102 then 'نوار تيپ' when 103 then 'فيلتراسيون' when 104 then 'پمپ و الكتروموتور' when 105 then 'دستگاه باراني' when 106 then 'ساير اتصالات' 
		end end like'%$_POST[IDpipe]%'";

	
	if (strlen(trim($_POST['producersTitle']))>0)
        $str.=" and producers.Title like'%$_POST[producersTitle]%'";
	
	
	if (strlen(trim($_POST['freestateTitle']))>0)
        $str.=" and freestate.Title like'%$_POST[freestateTitle]%'";
		
		
		
		
		
		
        
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

     if ($yearid>0) $str.=" and applicantmaster.yearid='$yearid' ";    
 

    if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0))
        $str.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid')
        and applicantfreedetail.producersID<>-1
         ";
  
  
    $sql = "SELECT value  FROM year where YearID='$yearid' ";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];
    
    $strjoin="";
    if ($login_RolesID=='16')
    {
        $strjoin="
        inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='22') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
        inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmaster.ApplicantMasterID ";   
    } 
    else   if ($login_RolesID=='7')
    {
        $strjoin="
        inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='37') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
        inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmaster.ApplicantMasterID

        ";   
        
    }

  if ($_POST['ostan']>0) 
   {$selectedCityId=$_POST['ostan'];$str.="and substring(applicantmasterall.cityid,1,2)=substring('$_POST[ostan]',1,2)";}
  else
   {$str.="and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)";}
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
    
    $sql = "SELECT applicantmaster.applicantmasterid,freestate.Title freestateTitle,freestate.Code
	freestatecode,applicantmaster.ApplicantName,producers.PipeProducer,applicantmaster.ApplicantFName,applicantfreedetail.Price,applicantfreedetail.CheckNo,applicantfreedetail.SaveDate,applicantfreedetail.CheckDate,applicantfreedetail.CheckBank,applicantfreedetail.Description,applicantfreedetail.AccountNo,
    applicantmaster.DesignArea,applicantmaster.CityId,applicantmaster.sandoghcode,applicantmaster.belaavaz,applicantmaster.LastTotal
,operatorco.title operatorcotitle,shahr.cityname shahrcityname,shahr.id shahrid,operatorco.operatorcoid,producers.Title producersTitle,
	case applicantfreedetail.producersID when -1 then 'مجری'  when -2 then 'کشاورز' else case producers.PipeProducer 
	when 1 then 'لوله پلي اتيلن' when 2 then 'نوار تيپ' when 3 then 'فيلتراسيون' when 4 then 'پمپ و الكتروموتور' when 5 then 'دستگاه باراني' when 6 then 'ساير اتصالات' 
	when 101 then 'لوله پلي اتيلن' when 102 then 'نوار تيپ' when 103 then 'فيلتراسيون' when 104 then 'پمپ و الكتروموتور' when 105 then 'دستگاه باراني' when 106 then 'ساير اتصالات' 
	end end productTitle
 FROM `applicantmaster`
inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'


inner join applicantmaster applicantmasterall on applicantmaster.BankCode=applicantmasterall.BankCode 
 and substring(applicantmaster.cityid,1,4)=substring(applicantmasterall.cityid,1,4)

inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmaster.operatorcoID=operatorapprequest.operatorcoID 

inner join applicantfreedetail on applicantfreedetail.ApplicantMasterID = applicantmaster.applicantmasterid
left outer join freestate on freestate.freestateID=applicantfreedetail.freestateID
left outer join producers on producers.producersID=applicantfreedetail.producersID
$strjoin

where ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and applicantfreedetail.Price>0

 $str
 order by applicantmaster.applicantmasterid,applicantmaster.ApplicantName COLLATE utf8_persian_ci,freestate.Code
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
    
	
    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';

	$ID8[' ']=' ';
$ID9[' ']=' ';


$dasrow=0;
while($row = mysql_fetch_assoc($result))
{
    $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID3[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);
    $ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);   
	$ID5[trim($row['productTitle'])]=trim($row['productTitle']);
    $ID8[trim($row['producersTitle'])]=trim($row['producersTitle']);
    $ID9[trim($row['freestateTitle'])]=trim($row['freestateTitle']);
    $dasrow=1;
}

    $ID1=mykeyvalsort($ID1);
	$ID2=mykeyvalsort($ID2);
	$ID3=mykeyvalsort($ID3);
	$ID4=mykeyvalsort($ID4);
	$ID5=mykeyvalsort($ID5);
	$ID8=mykeyvalsort($ID8);
	$ID9=mykeyvalsort($ID9);
	

if ($dasrow)
mysql_data_seek( $result, 0 );

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
  	<title>لیست آزادسازی</title>
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
            
            <form action="reports_aaapplicantfreeco.php" method="post" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                
                
                
                <table align='center' border='1' >  
                 <thead>            
                            
				  <tr> 
                  
                          
                            
                            
                         <?php  
                            $query="SELECT YearID as _value,Value as _key FROM `year` 
                             where YearID in (select YearID from cityquota)
                             
                             ORDER BY year.Value DESC";
            				 $ID = get_key_value_from_query_into_array($query);
                             print 
                             select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                             
                             print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa'";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
		           
				   $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                     where substring(ostan.id,3,5)='00000'
                     order by _key  COLLATE utf8_persian_ci";
                     $allg1id = get_key_value_from_query_into_array($sqlselect);
			 
  				 if ($login_designerCO==1)
				  print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId);
		 

                          ?>
                         </td>
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
				   </tr>
				   
				   
				 </thead>            
                   </table>
                <table align='center' border='1' id="table2">  
                 <thead>            
                            
		           <tr>
                              <td colspan="15"
                            <span class="f14_font" >لیست آزاد سازی (مبالغ به میلیون ریال)</span>  
                            </td>
                    <tr>        
					 <tr>
                            <th class="f11_fontb" > رديف   </th>
							<th class="f13_fontb"> نام   </th>
							<th class="f13_fontb"> نام خانوادگی  </th>
							<th class="f11_fontb"> مساحت </span>(ha)  </th>
						    <th class="f13_fontb">دشت/ شهرستان </th>
							<th class="f13_fontb">شركت مجری </th>
							<th class="f13_fontb"> مبلغ کل </th>
							<th class="f13_fontb">فروشنده</th>
							<th class="f13_fontb">دريافت كننده </th>
							<th class="f13_fontb">مرحله آزاد سازي</th>
							<th class="f13_fontb">مبلغ آزاد سازي </th>
							<th class="f13_fontb">تاريخ</th>			
							<th class="f13_fontb">ش ح دريافت كننده </th>
							<th class="f13_fontb" style=<?php echo $hide; ?>>ش چك صادره</th>
											
                            <th class="f13_fontb" >توضيحات </th>
                        </tr>
						 </thead>            
                
						 <tr class='no-print'>    
							<td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFname,'','100%'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','100%'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','100%'); ?>
					       <?php print select_option('sos','',',',$ID1,0,'','','1','rtl',0,'',$sos,"",'100%'); ?>  
					       <?php print select_option('operatorcoid','',',',$ID3,0,'','','1','rtl',0,'',$operatorcoid,'','100%') ?> 
						   
						   <td></td>
						   <?php print select_option('IDpipe','',',',$ID5,0,'','','1','rtl',0,'',$_POST['IDpipe'],'','100%') ?> 
						   <?php print select_option('producersTitle','',',',$ID8,0,'','','1','rtl',0,'',$producersTitle,'','100%') ?> 

						   <?php print select_option('freestateTitle','',',',$ID9,0,'','','1','rtl',0,'',$freestateTitle,'','100%') ?> 
						 <td/>
					 <td  class="data"><input placeholder="از تاریخ"  name="Datefrom" type="text" class="textbox" 
                     id="Datefrom" value="<?php if (strlen($Datefromst)>0) echo $Datefromst; ?>"
                      size="10" maxlength="10" /></td>
                      
                      <td class="data"><input placeholder="تا تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Datetost)>0) { echo $Datetost;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
                      </td>
                     
					       <td colspan="2" style="text-align:left;" ><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                           
                          
                           
<?php
 if ($login_isfulloption==1)
	{
                    $Total=0;
                    $rown=0;
                    $Description="";
                    $LastTotal = 0;
					$PriceTotal = 0;
					$AM = 0;
                    while($resquery = mysql_fetch_assoc($result))
                    { 
				$cls='';
				if ($resquery["CheckDate"]>0) $CheckDate=compelete_date($resquery["CheckDate"]); 
				else
				{$cls='8A2BE2';		
					$CheckDate=gregorian_to_jalali($resquery["SaveDate"]);
				 }       
                        //print compelete_date($Datetost).compelete_date($resquery["CheckDate"])."<br>";
                        if ($Datetost>0) 
                        {
                            if ( $CheckDate<compelete_date($Datefromst) ||   $CheckDate>compelete_date($Datetost))
                             
                                continue;
                        }
						
 //if (compelete_date($resquery["CheckDate"])<compelete_date($Datefromst) || compelete_date($resquery["CheckDate"])>compelete_date($Datetost))
   //     continue;
      //  print $resquery["CheckDate"];
              
       
						
                         $PriceTotal+= $resquery["Price"];              
						 if ($AM!= $resquery["applicantmasterid"]) { $AM = 0;	}
						 if ($AM == 0) {  $LastTotal+= $resquery["LastTotal"];  $AM = $resquery["applicantmasterid"]; }
											 
						            
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                             print "<tr '>";      
?>                      
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery['ApplicantFName']; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["ApplicantName"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["DesignArea"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["shahrcityname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcotitle"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastTotal"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["productTitle"]; ?></td>

							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';">
						  <?php 
						  if ($resquery["productTitle"]=='مجری')
                             echo $resquery["operatorcotitle"]; 
						 else if ($resquery["productTitle"]=='کشاورز')
						   echo $resquery["productTitle"]; 
						 else echo $resquery["producersTitle"]; 
                           ?></td>
							
							
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["freestateTitle"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo number_format($resquery["Price"]); ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cls; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php  echo $CheckDate; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align:	center;font-size:9pt;font-family:'B Nazanin';"><?php echo $resquery["AccountNo"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;<?php echo $hide; ?>; width:20px;text-align: center;font-size:9pt;font-family:'B Nazanin';"><?php echo $resquery["CheckNo"]; ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["Description"]; ?></td>
                       </tr>
                      <?php 
                     }
    }        					  
?>
                  <tr>
                            <td colspan="12" class="f14_fontb" ><?php echo 'مجموع مبلغ كل';   ?></td>
                            <td colspan="3" class="f14_fontb" ><?php echo floor($LastTotal/100000)/10;   ?></td>
                  </tr>
				  <tr>
                            <td colspan="12" class="f14_fontb" ><?php echo 'مجموع مبلغ آزادسازي';   ?></td>
                            <td colspan="3" class="f14_fontb" ><?php echo floor($PriceTotal/100000)/10;   ?></td>
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
