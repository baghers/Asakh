<?php 

/*

insert/foundation_applicant_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/foundation_lis.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='foundation_applicant_list';
$tblname='applicantmaster';//جدول مشخصا طرح

if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
$sql = "SELECT COUNT(*) as count FROM ".$tblname;
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);


if (! $_POST)
{
$linearr = explode('^',$_GET["uid"]);
$type=$linearr[0];
$showm=$linearr[1];
$uid="foundation_applicant_list.php?uid=$type^";
if ($type==1)
        $typetitle= "کارهای قیمت جدید";
    else
        $typetitle= "سازه های";
        
}

//----------

if ($login_DesignerCoID>0) $condition1=" where DesignerCoID='$login_DesignerCoID'";
else $condition1=" where operatorcoid='$login_OperatorCoID' and ifnull(operatorcoid,0)<>0 ";
/*
    applicantmaster جدول مشخصات طرح
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    criditType تجمیع بودن یا نبودن طرح
    DesignSystemGroupsID نوع سیستم آبیاری
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر    
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    applicantstates.title عنوان وضعیت پروژه
    hektar سطح پروژه
    prjtypeid نوع پروژه
    nazerID ناظر پروژه
    creditsourceTitle عنوان منبع تامین اعتبار
    ApplicantMasterIDmaster شناسه طرح اجرایی
    DesignerCoID شناسه مشاور طراح
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    designsystemgroupsdetail جدول ریز سیستم های آبیاری
    appstatesee لیست وضعیت هایی که هر نقش می بیند
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح

    */ 
            
$sql = "SELECT applicantmaster.*,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
,applicantfreedetail.freestateid ,yearcost.Value fb
,applicantstates.title applicantstatestitle,applicantmaster.applicantstatesID 
,applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz
,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,case private when 1 then 'شخصی' else '' end privatetitle
,prjtype.title prjtypetitle
FROM applicantmaster 

inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID 
and (applicantstates.RolesID in ('$login_RolesID') or (applicantmaster.applicantstatesID =23 
and (applicantmaster.operatorcoid>0 or substring(applicantmaster.cityid,1,2)!=19))
or (applicantmaster.applicantstatesID =46 and applicantmaster.DesignerCoID>0))


left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join (select max(freestateid) freestateid,ApplicantMasterID from applicantfreedetail where freestateid=142 group by ApplicantMasterID)
 applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster
left outer join (select max(errnum) errnum,max(emtiaz) emtiaz,10 RoleID,ApplicantMasterID from applicanttiming where RoleID='10' group by ApplicantMasterID ) applicanttiming on applicanttiming.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster

left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)

$condition1  
ORDER BY applicantmaster.ApplicantName COLLATE utf8_persian_ci;";

 
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
  	<title>لیست طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    

  function checkchange(){
		   if (document.getElementById('showm').checked)
			{
			     window.location.href ='<?php print $uid."1"; ?>';
			}		
			else 
			{		
				window.location.href ='<?php print $uid."0"; ?>';
    		
			}	

	}
    </script>
    <!-- /scripts -->
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
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
 <?php 
    print "<h1 align=\"center\">  لیست  پروژه های مختلف جهت ثبت $typetitle طرح";
    
					
					?>    
                        
						<input name='showm' type='checkbox' id='showm' onChange='checkchange()' <?php if ($showm==1) echo "checked";?>>
				            

						
				    </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}
                    if (strlen($enrolmsg)>0)
                    {
                        echo $enrolmsg;
                        exit;
                    }
                    

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th  
                           	<span class="f9_fontb" > رديف  </span> </th>
							<th  
                           	<span class="f12_fontb"> كدطرح </span> </th>
							<th  
                            <span class="f12_fontb"> مساحت </span>
							<span class="f9_fontb"> (ha)  </span> </th>
                            <th  
                    		<span class="f12_fontb"> دبي </span>
							<span class="f9_fontb"> (l/s)  </span> </th>
						    <th  
                            <span class="f12_fontb" width="15%">نام متقاضي</span>
							<span class="f9_fontb"> (پروژه) </span> </th>
                            <th 
                            <span class="f12_fontb">دشت/شهرستان</span> </th>
							<th 
                            <span class="f12_fontb" >شهر/بخش</span> </th>
							<th 
							<span class="f12_fontb">پروژه</span> </th><th 
							<span class="f12_fontb">وضعیت</span> </th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                   $rown=0;
                    while($row = mysql_fetch_assoc($result)){
											  	   if($row['archive']==1 && $showm==0) {continue;}
												   
				

                        $rown++; 
                        
                        
                            
                        $Code = $row['Code'];
                        
                        $ApplicantName = $row['ApplicantFName'].' '.$row['ApplicantName'];
                        $year = $row['year'];
                        $monthtitle = $row['monthtitle'];
                        $BankCode=$row['BankCode'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $PriceListMasterID=$row['PriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
?>                      
                        <tr>
                            
                            <td
                            <span class="f10_font" >  <?php echo $rown; ?> </span> </td>
							
                            <td
							<span class="f10_font">  <?php echo $BankCode; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f10_font">  <?php echo $row['Debi']; ?> </span> </td>
                           
                            <td
							<span class="f11_font">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f9_font">  <?php echo $row['bakhshcityname']   ; ?> </span> </td>
                           
                            <td
							<span class="f9_font">  <?php echo $row['prjtypetitle'];   ?> </span> </td>
                            <td
							<span class="f9_font">  <?php echo $applicantstatestitle   ; ?> </span> </td>
                            <td><a href=<?php print "states_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_5'.rand(10000,99999); ?>>
                            <img style = "width: 25px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            
                            <?php
                            if ( ($row['issurat']==1 && $row['freestateid']!=142) && ($row['issurat']==1 && $row['errnum']<8)  )
						
                            echo "<td><a onClick=\"alert('جدول زمانبندی پیش فاکتور توسط شرکت مشاور ناظر یا شرکت مجری ثبت نشده است');\" >
                            <img style = 'width: 25px;' src='../img/new_page.png' title='  فهرست بهای دستی آبیاری تحت فشار  '></a></td>";
                            else
                            
                            
                             print "<td><a href='foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_'.$type.'_'.$typetitle.'_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/saze.png' title='$typetitle طرح'></a></td>";
                            
                             ?>
                            
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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
