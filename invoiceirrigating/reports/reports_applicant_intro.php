<?php 
/*
reorts/reports_applicant_intro.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
 require ('../includes/functions.php');
if ($login_Permission_granted==0) header("Location: ../login.php");

$showa=0;

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

//print $sql;
if ($_POST)
{
    if (strlen(trim($_POST['titlecmb']))>0)
        $str.=" and title='$_POST[titlecmb]'";
    if (strlen(trim($_POST['typecmb']))>0)
        $str.=" and sendtype='$_POST[typecmb]'";
    if (strlen(trim($_POST['typeonlycmb']))>0)
        $str.=" and type='$_POST[typeonlycmb]'";
}


$sqlsandogh="SELECT 'صندوق' sendtype, 'معرفی شده' type ,1 ord,creditsource.title,sum(LastTotal) LastTotal,sum(belaavaz) belaavaz,
sum(selfcashhelpval+selfnotcashhelpval) selfhelpval,sum(DesignArea) DesignArea,count(*) cnt FROM applicantmaster
inner join (select distinct appchangestate.ApplicantMasterID 
from appchangestate 

inner join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno  on 
appchangestatestateno.ApplicantMasterID=appchangestate.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
and appchangestate.applicantstatesID in (12,22)

) app37 on app37.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)
group by creditsource.title

union all
SELECT 'صندوق' sendtype, 'عقد قرارداد شده' type ,2 ord,creditsource.title,sum(LastTotal) LastTotal,sum(belaavaz) belaavaz,sum(selfcashhelpval+selfnotcashhelpval),sum(DesignArea) DesignArea,count(*) cnt FROM applicantmaster
inner join (select distinct appchangestate.ApplicantMasterID 
from appchangestate 

inner join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno  on 
appchangestatestateno.ApplicantMasterID=appchangestate.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
and appchangestate.applicantstatesID='22') app37 on app37.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)
group by creditsource.title

union all
SELECT 'صندوق' sendtype, 'تایید  پیش فاکتور' type ,3 ord,creditsource.title,sum(applicantmasterop.LastTotal) LastTotal,
sum(applicantmasterop.belaavaz) belaavaz,sum(applicantmasterop.selfcashhelpval+applicantmasterop.selfnotcashhelpval) selfpval
,sum(applicantmasterop.DesignArea) DesignArea,count(*) FROM applicantmaster applicantmasterop
inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode 
and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
 and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4) and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='22') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmasterop.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid
group by creditsource.title
union all
SELECT 'صندوق' sendtype, 'آزاد سازی شده' type ,4 ord,creditsource.title,sum(applicantfreedetail.Price) LastTotal,
sum(applicantmasterop.belaavaz) belaavaz,sum(applicantmasterop.selfcashhelpval+applicantmasterop.selfnotcashhelpval) selfpval
,sum(applicantmasterop.DesignArea) DesignArea,count(*) FROM applicantmaster applicantmasterop
inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
 and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)  and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='22') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmasterop.ApplicantMasterID
inner join (select applicantmasterid,sum(Price) Price from applicantfreedetail group by applicantmasterid) applicantfreedetail on applicantfreedetail.applicantmasterid =applicantmasterop.applicantmasterid
left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid
group by creditsource.title";

$sqlbank="
SELECT 'بانک' sendtype, 'معرفی شده' type ,1 ord,creditsource.title,sum(LastTotal) LastTotal,sum(belaavaz) belaavaz,sum(selfcashhelpval+selfnotcashhelpval) selfhelpval,sum(DesignArea) DesignArea,count(*) cnt FROM applicantmaster
inner join (select distinct appchangestate.ApplicantMasterID 
from appchangestate 

inner join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno  on 
appchangestatestateno.ApplicantMasterID=appchangestate.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
and appchangestate.applicantstatesID in (36,37)) app37 on app37.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)
group by creditsource.title
union all
SELECT 'بانک' sendtype, 'عقد قرارداد شده' type ,2 ord,creditsource.title,sum(LastTotal) LastTotal,
sum(belaavaz) belaavaz,sum(selfcashhelpval+selfnotcashhelpval),sum(DesignArea) DesignArea,count(*) cnt FROM applicantmaster
inner join (select distinct appchangestate.ApplicantMasterID 
from appchangestate 

inner join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno  on 
appchangestatestateno.ApplicantMasterID=appchangestate.ApplicantMasterID
and appchangestate.stateno=appchangestatestateno.stateno
and appchangestate.applicantstatesID='37') app37 on app37.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)
group by creditsource.title

union all
SELECT 'بانک' sendtype, 'تایید  پیش فاکتور' type ,3 ord,creditsource.title,sum(applicantmasterop.LastTotal) LastTotal,sum(applicantmasterop.belaavaz) belaavaz,sum(applicantmasterop.selfcashhelpval+applicantmasterop.selfnotcashhelpval) selfpval
,sum(applicantmasterop.DesignArea) DesignArea,count(*) FROM applicantmaster applicantmasterop
inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
 and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)  and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='37') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmasterop.ApplicantMasterID
left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid
group by creditsource.title

union all
SELECT 'بانک' sendtype, 'آزاد سازی شده' type ,4 ord,creditsource.title,sum(applicantfreedetail.Price) LastTotal,sum(applicantmasterop.belaavaz) belaavaz,sum(applicantmasterop.selfcashhelpval+applicantmasterop.selfnotcashhelpval) selfpval
,sum(applicantmasterop.DesignArea) DesignArea,count(*) FROM applicantmaster applicantmasterop
inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
 and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)  and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='37') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
inner join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='30') app30 on app30.ApplicantMasterID=applicantmasterop.ApplicantMasterID
inner join (select applicantmasterid,sum(Price) Price from applicantfreedetail group by applicantmasterid) applicantfreedetail on applicantfreedetail.applicantmasterid =applicantmasterop.applicantmasterid
left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid
group by creditsource.title";

if ($login_RolesID==16 )
$sql="
select * from ( $sqlsandogh
) view1
where 1=1 $str
order by sendtype,title,ord";
else if ($login_RolesID==7)
$sql="
select * from ( $sqlbank
) view1
where 1=1 $str
order by sendtype,title,ord";
else 
$sql="
select * from ( $sqlbank union all $sqlsandogh
) view1
where 1=1 $str
order by sendtype,title,ord";

$ID1[' ']=' ';
$ID2[' ']=' ';
$ID3[' ']=' ';

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

$dasrow=0;

while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;
    $ID1[trim($row['sendtype'])]=trim($row['sendtype']);
    $ID2[trim($row['title'])]=trim($row['title']);
    $ID3[trim($row['type'])]=trim($row['type']);
}

$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);


if ($dasrow)
mysql_data_seek( $result, 0 );
?>



<!DOCTYPE html>
<html>
<head>
  	<title>لیست طرح های معرفی شده از منابع اعتباری</title>
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
            
            <form action="reports_applicant_intro.php" method="post">
               
                <table id="records" width="95%" align="center">
                   
                    <thead>
                        
                </table>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                   
                <table align='center' class="page" border='1' width='100%'>              
                   
				  <tr> 
                  
                            <td colspan="13"
                            <span class="f14_fontb" > لیست طرح های معرفی شده از منابع اعتباری</span>  </td>
                            
				   </tr>
                        <tr>
                            <th  
                           	<span class="f14_fontb" >بانک/ صندوق </span> </th>
							<th  
                            <span class="f14_fontb">فنی و اعتباری</span> </th>
                            <th  
                           	<span class="f14_fontb" >شرح وضعیت </span> </th>
                            <th 
                           	<span class="f14_fontb"> تعداد  </span> </th>
							<th 
                           	<span class="f14_fontb"> سطح </span> </th>
							<th  
                            <span class="f14_fontb">  مبلغ کل </span>
							 (ha)  </th>
                            <th  class="f14_fontb"> مبلغ بلاعوض </th>
						    <th 
                            <span class="f14_fontb">خودیاری</span> </th>
							
                          
                           
                            
                            
                        </tr>
                        
                        <tr>    
						  
					       <?php print select_option('typecmb','',',',$ID1,0,'','','1','rtl',0,'',$typecmb,'','100')
                           .select_option('titlecmb','',',',$ID2,0,'','','1','rtl',0,'',$titlecmb,'','100')
                           .select_option('typeonlycmb','',',',$ID3,0,'','','1','rtl',0,'',$typeonlycmb,'','100');?>
					  <td colspan="1">
                      <input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
					     	<td colspan="5" class="f14_font"></td>
                          
                       
					 
					 </tr> 
                     
                        

<?php
$rown=0;
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
$type='';
$meaningless=0;
        while($row = mysql_fetch_assoc($result))
        {     
            if ($row['type']<>$type && strlen($type)>0)
                $meaningless=1;
            $type=$row['type'];
            $sum1+=$row['cnt'];
            $sum2+=floor($row['DesignArea']);
            $sum3+= floor(($row['LastTotal']/100000)/10);
            $sum4+=floor($row['belaavaz']);
            $sum5+= floor(($row['selfhelpval']/100000)/10);
            $rown++;
            if ($rown%2==1) 
            $b='b'; else $b='';                        
?>                      
                        <tr>    
                           
                            <td
                            <span class="f12_font<?php echo $b ?>"  >  <?php echo $row['sendtype']; ?> </span>  </td>
							
                            <td
							<span class="f12_font<?php echo $b ?>"><?php echo $row['title'];?></span> </td>
                            <td 
							<span class="f12_font<?php echo $b ?>"> <?php echo $row['type'];  ?>   </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b ?>">  <?php  echo $row['cnt'];   ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b ?>"><?php  echo floor($row['DesignArea']);  ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b ?>"> <?php echo   floor(($row['LastTotal']/100000)/10);    ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b ?>"><?php echo floor($row['belaavaz']);?></span> </td>
                            
                            <td colspan="2"
							<span class="f12_font<?php echo $b ?>"><?php echo floor(($row['selfhelpval']/100000)/10);  ?>  </span> </td>
                           
                        </tr>
                         
                     </tr>
                     
                     
                     <?php
                     } 
                     
                     if ($meaningless==0)
                     {
                        $b='b';
                        echo " <tr>    
                           
                            <td
                            <span class=\"f10_font$b\"  > </span>  </td>
							
                            <td
							<span class=\"f10_font$b\"></span> </td>
                            <td 
							<span class=\"f9_font$b\"> مجموع </span> </td>
                           
                            <td
							<span class=\"f9_font$b\"> $sum1</span> </td>
                           
                            <td
							<span class=\"f10_font$b\">$sum2</span> </td>
                            
                            <td
							<span class=\"f7_font$b\"> $sum3 </span> </td>
                           
                            <td
							<span class=\"f10_font$b\"> $sum4</span> </td>
                            
                            <td 
							<span class=\"f7_font$b\"> $sum5</span> </td>
                           
                        </tr>";
                     }
                     

                     
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