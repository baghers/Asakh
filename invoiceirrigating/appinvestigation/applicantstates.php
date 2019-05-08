<?php

/*

//appinvestigation/applicantstates.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/aaapplicantfree.php
 
 
-
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
//echo 'g'.$login_RolesID;
$tblname='applicantmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST)
    { 
  	//print $_POST['srow'];
	
		$i=0;
        while ($i++<=$_POST['srow'])
        {
			if ($_POST['hide'.$i]=='on') $archive=1; else $archive=0;//بایگانی
        //applicantmaster جدول مشخصات طرح
		 if ($_POST['app'.$i]!='')  mysql_query("update applicantmaster set 
         SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
         archive='$archive' where ApplicantMasterID='".$_POST['app'.$i]."'");
		 
		}

	header("Location: $_server_httptype://$_SERVER[HTTP_HOST]/invoiceirrigating/appinvestigation/".$_POST['uid']);
		
	}
	
if (! $_POST)
{
    $uid=$_GET["uid"];
}

$linearr = explode('^',$_GET["uid"]);
$uid1=$linearr[0];

$showm=$linearr[1];
	$showm=$_GET["showm"];
	$IDorder=$_GET["IDorder"];

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
         
$linearray = explode('_',$ids);
$IDco=$linearray[0];//شناسه شرککت
$type=$linearray[1];//نوع
 
if ($login_RolesID=='1') $login_CityId=$linearray[2];
        
$DesignerCoID =0;
$operatorcoID =0;
if ($type==1) 
{
    $DesignerCoID=$IDco;    
    if ($login_RolesID=='17') //ناظر مقمیم
        $condition=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
        else
            if ($login_RolesID=='14') //ناظر عالی
        $condition=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
        $condition.=" and applicantmaster.DesignerCoID='$DesignerCoID'";
    $permitrolsidprivate = array("1", "2","9", "10");//مجری  یا طراح
    if (! in_array($login_RolesID, $permitrolsidprivate))
        $condition.=" and ifnull(applicantmaster.private,0)=0 ";
    
}
else if ($type==2) 
{
    $operatorcoID=$IDco;   
    if ($login_RolesID=='17') //ناظر مقمیم
        $condition=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
        else
            if ($login_RolesID=='14') //ناظر عالی
        $condition=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
        $condition.=" and applicantmaster.operatorcoID='$operatorcoID'"; 
    $permitrolsidprivate = array("1", "2","9", "10");//مجری  یا طراح
    if (! in_array($login_RolesID, $permitrolsidprivate))
        $condition.=" and ifnull(applicantmaster.private,0)=0 ";
    
}

$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
$sql = "SELECT COUNT(*) as count FROM ".$tblname;
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------
//apptype 1=surat 2=pishfactor 3=tarahi


 switch ($IDorder) 
  {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by applicantmaster.DesignArea'; break;
	case 4: $orderby=' order by applicantmaster.Debi'; break;
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by designername COLLATE utf8_persian_ci'; break;
    case 7: $orderby=' order by applicantstatestitle COLLATE utf8_persian_ci'; break;
    case 8: $orderby=' order by applicantmaster.TMDate'; break;
    case 9: $orderby=' order by cast(applicantmaster.Code as decimal)'; break;
    default: 
        $orderby=' order by ifnull(applicantmasterdetail.prjtypeid,0),shahrcityname COLLATE utf8_persian_ci,cast(applicantmaster.Code as decimal),applicantmaster.savetime '; break; 
  }
 
  /*
    applicantmaster جدول مشخصات طرح
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    proposestatep وضعیت پیشنهاد قیمت
    ApplicantMasterID شناسه طرح
    creditsourcetitle عنوان منبع تامین اعتبار
    credityear سال اعتبار طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    ADate تاریخ شروع پیشنهاد قیمت
    BankCode کد رهگیری طرح
    designername عنوان طراح
    designsystemgroupstitle سیستم آبیاری
    shahrcityname نام شهر
    designer.LName نام خانوادگی طراح
    designer.FName نام طراح
    operatorapprequest جدول پیشنهاد قیمت های طرح
    state برنده شدن یا نشدن
    clerk جدول کاربران
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    private شخصی بودن طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    year جدول سال
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    designer جدول طراحان
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    operatorcoid شناسه پیمانکار
    DesignerCoID شناسه مشاور طراح
    costpricelistmaster جدول فهرست بها
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignerID شناسه طراح طرح
    applicantstatesID شناسه وضعیت طرح
    corank رتبه شرکت
    firstperiodcoprojectarea مجموع مساحت پروژه های انجام داده اول دوره شرکت
    firstperiodcoprojectnumber تعداد  پروژه های انجام داده اول دوره شرکت
    coprojectsum مجموع تعدادی پروژه های شرکت
    projecthektardone پروژه های انجام داده شرکت
    simultaneouscnt تعداد پروژه های همزمان
    thisyearprgarea مساحت پرژه های امسال
    above20cnt تعداد پروژه های بالای 20 هکتار
    above55cnt تعداد پروژه های بالای 55 هکتار
    currentprgarea مساحت پروژه های جاری
    projectcountdone تعداد پروژه های انجام داده شرکت
    clerk.clerkid شناسه کاربر
    designerinfo.designercnt تعداد کارشناسان طراح شرکت
    designerinfo.dname نام کارشناس طراح
    designerinfo.duplicatedesigner داشتن کارشناسی که در دو شرکت فعالیت نماید
    membersinfo.duplicatemembers عضو هیئت مدیره که در دو شرکت فعالیت نماید
    allreq.cnt reqcnt تعداد پیشنهادات ارسال شده
    allwinreq.wincnt تعداد پیشنهادات انتخاب شده
    avgpmreq.avg میانگین ظرایب پیشنهاد قیمت های شرکت
    avgpmreqa.avga میانگین ظرایب پیشنهاد قیمت های انتخابی
    coef1 ضریب اول اجرای طرح
    coef2 ضریب دوم اجرای طرح
    coef3 ضریب سوم اجرای طرح
    ent_DateFrom شروع انتظامی بودن شرکت
    ent_DateTo پایان انتظامی بودن شرکت
    ent_Hectar هکتار انتظامی بودن شرکت
    ent_Num تعداد انتظامی بودن شرکت
    percentapplicantsize درصد افزایش اندازه پروژه
    applicantmasterdetail جدول ارتباطی طرح ها
    */ 

$sql = "SELECT applicantmaster.*,designer.LName designername 
,applicantstates.title applicantstatestitle,designerco.title designercotitle,operatorco.title operatorcotitle,applicantmaster.applicantstatesID 
,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,private,applicantmaster.archive archive
,case private when 1 then 'شخصی'  else '' end privatetitle
,case applicantmaster.archive when 1  then 'بایگانی' else '' end archivetitle,producerapprequest.ApplicantMasterID ApplicantMasterIDp
,case applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid when 1 then 1 else 
case applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid when 1 then 2 else 3 end end apptype 
,ifnull(applicantmasterdetail.prjtypeid,0) prjtypeid,prjtype.title prjtypetitle

FROM applicantmaster 
left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
left outer join designerco on designerco.designercoid=applicantmaster.designercoid
left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid

left outer join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID
left outer join (select distinct ApplicantMasterID  from producerapprequest) producerapprequest 
on producerapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID


left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid
left outer join designer on designer.designerid=applicantmaster.designerid
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
where substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2) $condition
$orderby ;";

$result = mysql_query($sql);
								try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

//print $sql;

$sqltiming10 = "SELECT ApplicantMasterID _value,ApplicantMasterID _key FROM applicanttiming
	where RoleID=10
	";
    $IDtiming10 = get_key_value_from_query_into_array($sqltiming10);
  
  
?>
<style>

.f14_font{
	border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:14pt;line-height:95%;font-weight: bold;font-family:'B lotus';                        
}
.f12_font{
	border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12pt;line-height:95%;font-weight: bold;font-family:'B lotus';                        
}
.f11_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:11pt;line-height:95%;font-weight: bold;font-family:'B lotus';                           
  }
.f10_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:9pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                           
  }
	
</style>
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
				if (document.getElementById('IDorder').value>0)
				{
			    window.location.href =document.getElementById('uid1').value +'&showm=1' +'&IDorder=' +document.getElementById('IDorder').value;
				}
		    	else
				{
				window.location.href =document.getElementById('uid1').value +'&showm=1' ;
				}
			}		
			else 
			{	 		
				var uid =document.getElementById('uid1').value;
				
				if (document.getElementById('IDorder').value>0)
				{
			     window.location.href =document.getElementById('uid1').value +'&IDorder=' +document.getElementById('IDorder').value;
				}
		    	else
				{
			    window.location.href =document.getElementById('uid1').value;
				}
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
	           <form action="applicantstates.php"  method="post" enctype="multipart/form-data">
 		
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                    <?php 
					$uid1='applicantstates.php?uid='.$uid1;
			 
					$uid='applicantstates.php?uid='.$uid;
					
if (!$IDorder)
    $IDorderval=5;
else $IDorderval=$IDorder;
		

$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'دبی' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'سریال' _key,9 as _value ";
$IDorder = get_key_value_from_query_into_array($query);

		
					?>    
                        <h1 align="center">  لیست طرح های مختلف 
						<input name='showm' type='checkbox' id='showm' onChange='checkchange()' <?php if ($showm==1) echo "checked";?>>
					 	<input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  />
						
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
				            

						
				    </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <div style = "text-align:left;"><a href=<?php print "applicantstates_master.php"; ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="25px" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
					
                        <tr>
						
                        	<th  
                           	<span class="f9_font" > بایگانی</span> </th>
							<th  
                           	<span class="f9_font" > رديف  </span> </th>
							<th  
                           	<span class="f12_font"> طراح </span> </th>
							<th  
                           	<span class="f12_font"> كدطرح </span> </th>
							<th  
                            <span class="f12_font"> مساحت </span>
							<span class="f9_font"> (ha)  </span> </th>
                            <th  
                    		<span class="f12_font"> دبي </span>
							<span class="f9_font"> (l/s)  </span> </th>
						    <th  
                            <span class="f12_font">نام متقاضي</span>
							<span class="f9_font"> (پروژه) </span> </th>
                            <th 
                            <span class="f12_font">دشت/شهرستان</span> </th>
							<th 
                            <span class="f12_font" >شهر/بخش</span> </th>
							<th 
							<span class="f12_font">روستا</span> </th>
							 <?php 
							 
							 if(
								$login_OperatorCoID>0 && $login_isfulloption<>1
								)
								print "<td></td>";
								else
								{
							 
							 ?>
                            <th <span class="f12_font">وضعیت</span> </th>
                              <th <span class="f12_font">تاریخ</span> </th>
                            <th <span class="f12_font">نوع</span> </th>
							<?php } ?>
                                 <th width="5%">
							
								 <?php
								print select_option('IDorder','ترتیب',',',$IDorder,0,'','','5','rtl',0,'',$IDorderval,"onChange=\"checkchange();\"",'100');
								?>
             
							</th>
                         
            	      </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                 $rown=0;
                 while($row = mysql_fetch_assoc($result)){
					 
						   $checked="";
						   if($row['archive']==1 && $showm==0) {continue;}
                           if($row['archive']==1 && $showm==1) {$checked="checked";}
							
				
                        $rown++; 

                      

                        
                        
                        $Code = $row['Code'];
                        $ID = $row['ApplicantMasterID'].'_2_'.$DesignerCoID.'_'.$operatorcoID.'_'.$row['applicantstatesID'];
						$ApplicantName = $row['ApplicantFName'].' '.$row['ApplicantName'];
                        $BankCode=$row['BankCode'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
                        $permitId='';if ($login_designerCO==1) $permitId=$row['ApplicantMasterID'];
                        
						$permitstate = array("23","24","25","2","3","4","5","6","7","8","11","50","46");if ($login_DesignerCoID)
                        if (!(in_array($row['applicantstatesID'], $permitstate)))
						$applicantstatestitle='مدیریت آب و خاک';
						
					   
                        
                        
?>                      
                        <tr>
							
						    
                           <?php
						     
						   //if (!($login_ProducersID>0)) 
                           echo "<td > <input type='checkbox' id='hide$rown' name='hide$rown'  $checked />";
                           echo " <input type='hidden' id='app$rown' name='app$rown' value='$row[ApplicantMasterID]'  /></td>";
                        
                           ?>
                 
							<td
                            <span class="f10_font" >  <input  style = "border:0px solid black;border-color:#0000ff #0000ff;bg-color:#0000ff;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 25px"
                            type="text" class="textbox" id="srow" name="srow" value=<?php echo $rown; ?> > </span> </td>
							
                            <td
							<span class="f10_font">  <?php echo str_replace(' ', '&nbsp;', $row['designername'])."<br>".$row['ApplicantMasterID']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $BankCode; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f10_font">  <?php echo $row['Debi']; ?> </span> </td>
                           
                            <td
							<span class="f11_font">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['shahrcityname'].' '.$permitId; ?> </span> </td>
                            
                            <td
							<span class="f9_font">  <?php echo $row['bakhshcityname']   ; ?> </span> </td>
                           
                            <td
							<span class="f9_font">  <?php 
                            
                                $linearray = explode('_',$row['CountyName']);
                                $CountyName=$linearray[0];
    
                            
                            echo $CountyName   ; ?> </span> </td>
							
                            <td <span class="f9_font">  <?php 
						
						
                       
							if($continue==0)
                            if ($row['proposestatep']>=0 && $row['proposestatep']<3 && $row['ApplicantMasterIDp']>0
                            && $applicantstatestitle!='انصراف از اجرا'
									) echo "انتخاب تولید کننده لوله"; 
									else
							    	  echo str_replace(' ', '&nbsp;', $applicantstatestitle);
							?> </span> </td>
					
							  <td
							<span class="f9_font">  <?php echo gregorian_to_jalali($row['TMDate'])   ; ?> </span> </td>
                          
                           
						   <td <span class="f9_font">  <?php 
							if ($row['archive']==1) echo str_replace(' ', '&nbsp;',  $row['archivetitle']); 
                            
                            else if (!($operatorcoID>0) ) echo str_replace(' ', '&nbsp;',  $row['privatetitle']);
                                else 
                                {
                                    if ($row['apptype']==1) print "صورت وضعیت";
                                    if ($row['apptype']==2) print "پیش فاکتور";
                                    if ($row['apptype']==3) print "مطالعات";
                                    
                                }
                                echo '<br>'.$row['prjtypetitle'];
                          
                             ?> </span> </td>
                           	<?php
						
							
							if ($row['prjtypeid']!=0)
                            {
                                $row['errnum']=8;
                                $cunt_water=1;
                                $cunt_systyp=1;
                            }
                            else
                            {
                            $sql = "SELECT * FROM applicantsystemtype where ApplicantMasterID=".$row['ApplicantMasterID']."";
                            $cunt_systyp = mysql_num_rows(mysql_query($sql));
															try 
															  {		
																mysql_query($sql);
															  }
															  //catch exception
															  catch(Exception $e) 
															  {
																echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
															  }


							$sql2 = "SELECT * FROM applicantwsource where ApplicantMasterID=".$row['ApplicantMasterID']."";
                            $cunt_water = mysql_num_rows(mysql_query($sql2));  
															try 
															  {		
																mysql_query($sql);
															  }
															  //catch exception
															  catch(Exception $e) 
															  {
																echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
															  }
							
                            }

                             
							?>
                            <td>
							<?php 
						
						 $sqlc = "SELECT proposable FROM invoicemaster where proposable>0 and ApplicantMasterID=".$row['ApplicantMasterID']."";
                         $cunt = mysql_num_rows(mysql_query($sqlc));
					if($continue==0)
					{
						$searchpng='../img/search.png';
					 	if ($cunt>0) $searchpng='../img/searchPy.png';
					    if ($row['proposestatep']==1 || $row['proposestatep']==2) $searchpng='../img/searchPb.png';
					    if ($row['proposestatep']==3) $searchpng='../img/searchPg.png';
							
							
						if 	($backdoor==1 || $DesignerCoID==$login_DesignerCoID || $operatorcoID==$login_operatorcoID)
						{
						   //if(($login_RolesID==9) || ($login_RolesID==10))
							//if (($cunt_systyp==0 || $cunt_water==0))
							if (($cunt_systyp==0 || $cunt_water==0) && $login_RolesID<>2  && $login_RolesID<>1  && $login_RolesID<>19)
							{ ?>
			     			  <a onclick="alert('لطفا اطلاعات تكميلي را كامل نماييد')"><img style = 'width: 25px;' src=<?php echo $searchpng;?> title=' ريز '></a>
								  <?php
							}
							else
							{ ?>  
								<a href=<?php 
								 print "../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
								<img style = 'width: 25px;' src=<?php echo $searchpng;?> title=' ريز '></a></td>
								<?php 
								$permitrolsid = array("1", "13","5","11","18","19");if (in_array($login_RolesID, $permitrolsid))
								print "<td><a href='applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
								"'><img style = 'width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a>"; 
							} 
						} 
                        if ($row['apptype']==1) $typ="_1";else $typ="";
				
						print "</td>";
						  ?>
					        <td><a href=<?php print "applicantstates_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 25px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
						
                          <td class="f7_font<?php echo $b; ?>"><a  target='_blank' href=<?php
                             
                            print "opchangestodesign.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['BankCode'].$typ.rand(10000,99999); ?>>
                            <img style = 'width: 25px;' src='../img/accept_page.png' title=' تغییرات اجرا نسبت به طراحی '></a></td>
                            
              <?php } else {?>
					 
					          	  <td <span class="f9_font">  </span> </td>
								<td <span class="f9_font">  </span> </td>
                       
					  <?php }
                      //print $row['operatorcotitle'];
					  //print $row['ApplicantMasterIDmaster'].'br'.$row['applicantstatesID'].'<br>';	
					 $permitstate = array("40","41","42","44");
                     if ((( in_array($row['applicantstatesID'], $permitstate)) && $row['ApplicantMasterIDmaster']>0) )
					{   
					  echo " ";
					} else 
					{
					 if( (!( $row['ApplicantMasterIDmaster']>0) || $row['applicantstatesID']== 40) && ($row['operatorcotitle']<>'') )
						{
									if (in_array($row['ApplicantMasterID'], $IDtiming10) )
									$tablepng='../img/table.png';else $tablepng='../img/table2.png';
						
                            echo "<td><a href='../insert/applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_1'.rand(10000,99999)."'>
						   <img style = 'width: 20px;' src=$tablepng title=' مشاهده جدول زمانبندي '></a></td>";
						} 
									
					}
                    if ( $row['ApplicantMasterIDmaster']>0)
							echo "<td><a  target='_blank' href='applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$row['ApplicantMasterID']."_5_".$row['applicantstatesID'].rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/folder_accept.png' title='صورتجلسه تحویل موقت'></a></td>";
					  
					 $permitstate = array("30","31","32","35","38");
					 $permitroles = array("1","9","10","17");
					 if ( (in_array($row['applicantstatesID'], $permitstate)) || (in_array($login_RolesID, $permitroles)))
					  {   
                        
                        if ($login_RolesID<>2) 
						{  
                                        $ID = 'applicantsystemtype_t_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 25px;' src='../img/giah.jpg' title=' اطلاعات تكميلي سیستم و محصولات'></a></td>";
                                     
                                     
                                        $ID = 'applicantwsource_t_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 15px;' src='../img/ab.jpg' title=' اطلاعات تكميلي منبع آبی'></a></td>";
                                     
                                        $ID = 'applicantsurvey_survey_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
                                     echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 25px;' src='../img/nagshe.jpg' title=' اطلاعات نقشه برداری '></a></td>";
                                     
                                     
                        } 
                                     
						else echo "<td></td>";		  
                      }                  
					else echo "<td></td>";		  
                
					echo "<td><a  target='_blank' href='allinvoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    $row['ApplicantMasterID'].'_'.$IDco.'_'.$type.'_'.$login_CityId.rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/full_page.png' title='لیست پیشفاکتورها'></a></td>";
					  
					  
					  
					  
					$ID = $row['ApplicantMasterID'].'_2_'.$DesignerCoID.'_'.$operatorcoID.'_'.$row['applicantstatesID'];
					$permitrolsid = array("1", "2","9","10","19");if (in_array($login_RolesID, $permitrolsid))
								print "<td><a href='cont_upload.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                                .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
								"'><img style = 'width: 25px;' src='../img/up.png' title=' بارگذاری اسناد طرح '></a></td>"; 
                                
                        $ID = $row['ApplicantMasterID'];
                        $permitrolsid = array("1", "24","25","19");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a  target='".$target."' 
                            href='../insert/approvedocumentapplicantmaster.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/search.png' title=' مدارک طرح '></a></td>"; 
                        
                        
                        print "<td class='no-print' ><a  target='".$target."' href='appuploads.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/calendar_empty.png' title=' مدیریت فایل ها '></a></td>";
                            
                        if ($login_RolesID==1)
                        {
                            echo "<td><a 
                                        href='../insert/applicant_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'
                                        onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                                        > <img style = 'width: 20px;' src='../img/delete.png' title='حذف'> </a></td>";
                        }
						
                        echo "</tr>";
             }

			          echo 
                      "<tr><td colspan=2 ><input name=\"submit\" type=\"submit\" class=\"button\" tabindex='$tabindex' id=\"submit\" value=\"ثبت\" /></td></tr>"; 
                      
                      
?>
                    </tbody>
                   
                </table>
			</form>	
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
