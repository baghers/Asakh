<?php

/*

insert/manualcostlist_applicant_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/manualcostlist_pluscostlist_list2.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='manualcostlist_applicant';
$tblname='applicantmaster';//جدول مشخصات طرح

if ($login_Permission_granted==0) header("Location: ../login.php");



if (! $_POST)
{
    $uid=$_GET["uid"];
$linearr = explode('^',$_GET["uid"]);
$uid1=$linearr[0];
$showm=$linearr[1];
}

//----------

if ($login_DesignerCoID>0) $condition1=" where DesignerCoID='$login_DesignerCoID' ";
else $condition1=" where operatorcoid='$login_OperatorCoID' and ifnull(operatorcoid,0)<>0 ";
/*
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
freestateid شناسه مرحله آزادسازی
yearcost.Value سال فهرست بهای آبیاری تحت فشار
applicantstatestitle عنوان وضعیت طرح
applicantstatesID شناسه وضعیت طرح
errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
RoleID نقش کاربر ثبت کننده جدول زمانبندی
emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
ostancityname نام استان طرح
shahrcityname نام شهر طرح
bakhshcityname نام بخش طرح
privatetitle شخصی بودن طرح
prjtypetitle عنوان نوع پروژه
prjtypeid شناسه نوع پروژه
RolesID نقش کاربر
applicantstatesID شناسه وضعیت طرح
applicantstates جدول تغییر وضعیت های طرح
costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
year جدول سال ها
YearID شناسه سال طرح
tax_tbcity7digit جدول شهرهای مختلف
applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
applicanttiming جدول زمانبندی اجرای طرح
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
						//var sysbelaavaz2=document.getElementById('sysbelaavaz').value;
						var uid3=document.getElementById('uid3').value;
						//alert(uid3);
						//<?php $sys ?> = sysbelaavaz2;
							window.location.href =document.getElementById('uid3').value;
			}		
			else 
			{		
				var uid=document.getElementById('uid1').value;
		        window.location.href =document.getElementById('uid1').value;
    		
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
					$uid3='';$uid3='manualcostlist_applicant_list.php?uid='.$uid.'^1';
			 		$uid1='manualcostlist_applicant_list.php?uid='.$uid1;
			     	$uid='manualcostlist_applicant_list.php?uid='.$uid;
					
					?>    
                        <h1 align="center">  لیست طرح های مختلف جهت ثبت فهرست بهای دستی
						<input name='showm' type='checkbox' id='showm' onChange='checkchange()' <?php if ($showm==1) echo "checked";?>>
						<input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  />
                    	<input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  />
						
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
				            

						
				    </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"><?php

							
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
							<span class="f9_font">  <?php echo $row['prjtypetitle']; ?> </span> </td>
                            <td
							<span class="f9_font">  <?php echo $applicantstatestitle   ; ?> </span> </td>
                            <td><a href=<?php print "states_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_3'.rand(10000,99999); ?>>
                            <img style = "width: 25px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            
                            <?php
                            if ( ($row['issurat']==1 && $row['freestateid']!=142) && ($row['issurat']==1 && $row['errnum']<8)  )
						
                            echo "<td><a onClick=\"alert('جدول زمانبندی پیش فاکتور توسط شرکت مشاور ناظر یا شرکت مجری ثبت نشده است');\" >
                            <img style = 'width: 25px;' src='../img/fm.png' title='  فهرست بهای دستی آبیاری تحت فشار  '></a></td>";
                            else
                            
                            
                             print "<td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fm.png' title=' فهرست بهای دستی'></a></td>
                            
                            <td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_2'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fs.png' title='  فهارس بها '></a></td>
                            ";
                            
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
