<?php

/*

insert/producer_notapprovedinvoice_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
reports/reports_PipeProduction.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$PipeProducer=1;
$where="1=1";

$PID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
 if ($PID>0) 
    {
        $login_ProducersID=$PID;//شناسه تولید کننده
        $login_PipeProducer=1;//تولیدکننده لوله بودن
    }

		$style="style='display:none'";
if ($login_ProducersID>0) 
	{
	//invoicemaster جدول لیست پیش فاکتور
    //producers جدول تولیدکننده
		$where.=" and invoicemaster.ProducersID='$login_ProducersID'";
		$sql = "SELECT Title FROM `producers` where `producers`.`ProducersID` ='$login_ProducersID' ";		
		
		  	   				  	try 
								  {		
									$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		$row = mysql_fetch_assoc($result);
		$Title=$row['Title'];	
	}
 
if ($login_RolesID==29)	//کاربر دمو
//invoicetiming جدول زمانبندی
    $where.=" and invoicetiming.ClerkIDexaminer='$login_userid'";
	
	
if ($login_Permission_granted==0) header("Location: ../login.php");
$proposablecond="";
$proposablejoin="";


if($login_PipeProducer==1)
{
    /*
    
    applicantstatesID شناسه وضعیت طرح
    */
    $proposablecond="and invoicemaster.proposable=1  and applicantmasterop.proposestatep=3
    and ifnull(applicantmasterop.applicantstatesID,0) not in (34) ";
    $proposablejoin="
        inner join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemasterpipe  
    on invoicemasterpipe.ApplicantMasterID=invoicemaster.ApplicantMasterID and invoicemasterpipe.invoicemasterid=invoicemaster.invoicemasterid";
    
}
     

$proposablecond.=' and ifnull(applicantmasterop.applicantstatesID,0) not in (23)';
	 
/*
    invoicemaster جدول پیش فاکتورها
    invoicemasterid شناسه پیش فاکتور
    InvoiceDate تاریخ پیش فاکتور
    applicantmasterop جدول مشخصات طرح
    ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
    DesignArea مساحت
    shahrcityname نام شهر طرح
    prjtypetitle عنوان نوع پروژه
    prjtypeid شناسه نوع پروژه
    tax_tbcity7digit جدول شهرهای مختلف
    invoicetiming جدول زمانبندی
    */
    	 
if ($login_ProducersID>0) 
$sql = "SELECT invoicemaster.invoicemasterid,invoicemaster.Title,invoicemaster.InvoiceDate,applicantmasterop.DesignArea,applicantmasterop.ApplicantFName,
applicantmasterop.ApplicantName,shahr.cityname shahrcityname,operatorco.title operatorcotitle 
,invoicetiming.BOLNO,invoicetiming.ApproveP,invoicetiming.ApproveA,prjtype.Title prjtypeTitle
,invoicemaster.ApplicantMasterID,invoicemaster.ProducersID
from invoicemaster



    inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end=invoicemaster.applicantmasterid



inner join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=invoicemaster.applicantmasterid 
and   substring(applicantmasterop.cityid,1,2)=substring('$login_CityId',1,2)
$proposablejoin

left outer join prjtype on prjtype.prjtypeid=applicantmasterdetail.prjtypeid

left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) and substring(shahr.id,5,3)='000' 
and substring(shahr.id,3,5)<>'00000'
left outer join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid
where invoicemaster.ProducersID='$login_ProducersID' 
and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
and (ifnull(invoicetiming.ApproveA,'')='') and ifnull(pricenotinrep,0)=0 and ifnull(costnotinrep,0)=0 $proposablecond
order by prjtype.prjtypeid,InvoiceDate DESC";
else 
$sql = "SELECT invoicemaster.invoicemasterid,invoicemaster.Title,invoicemaster.InvoiceDate,applicantmasterop.DesignArea,applicantmasterop.ApplicantFName,
applicantmasterop.ApplicantName,shahr.cityname shahrcityname,operatorco.title operatorcotitle 
,producers.PipeProducer,producers.Title ProducersTitle 
,invoicetiming.BOLNO,invoicetiming.ApproveP,invoicetiming.ApproveA,prjtype.Title prjtypeTitle
,invoicemaster.ApplicantMasterID,invoicemaster.ProducersID
from invoicemaster



    inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end=invoicemaster.applicantmasterid


inner join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=invoicemaster.applicantmasterid 
and   substring(applicantmasterop.cityid,1,2)=substring('$login_CityId',1,2)


left outer join prjtype on prjtype.prjtypeid=applicantmasterdetail.prjtypeid

inner join producers on producers.ProducersID=invoicemaster.ProducersID

left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) and substring(shahr.id,5,3)='000' 
and substring(shahr.id,3,5)<>'00000'
left outer join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid
where ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
and (ifnull(invoicetiming.ApproveA,'')='')  and ifnull(pricenotinrep,0)=0 and ifnull(costnotinrep,0)=0  $proposablecond
and ifnull(producers.PipeProducer,0)=$PipeProducer
order by producers.ProducersID,prjtype.prjtypeid,InvoiceDate DESC";

//print $sql;exit; 
 
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

$sqldown = "SELECT invoicemaster.invoicemasterid,invoicemaster.Title,invoicemaster.InvoiceDate,applicantmasterop.DesignArea
,applicantmasterop.ApplicantFName,
applicantmasterop.ApplicantName,'' shahrcityname,operatorco.title operatorcotitle 
,producers.PipeProducer,producers.Title ProducersTitle 
,invoicemaster.ApplicantMasterID
,designerco.title DesignerCotitle
,case ifnull(invoicetiming.ApproveA,'') when '' then 
case ifnull(invoicetiming.ApproveP,'') when '' then 'ثبت اولیه' else 'ارسال شده' end
else 'تایید' end sendstate
,invoicetiming.BOLNO,invoicetiming.ApproveP,invoicetiming.ApproveA
,round((invoicetiming.score1+invoicetiming.score2+invoicetiming.score3)/3,1) score
from invoicemaster

    inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end=invoicemaster.applicantmasterid



inner join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=invoicemaster.applicantmasterid 

left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID

inner join producers on producers.ProducersID=invoicemaster.ProducersID

$proposablejoin







left outer join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid

    left outer join clerk on clerk.clerkID=invoicetiming.ClerkIDexaminer
		left outer join designerco on designerco.DesignerCoID=clerk.MMC															
	


where  $where  and ifnull(invoicetiming.ApproveA,'')<>''  $proposablecond
and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0
order by invoicemaster.ProducersID,InvoiceDate DESC";

//print $sqldown;exit;


 	   				  	try 
								  {		
									$resultdown = mysql_query($sqldown);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }



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

<script type="text/javascript">
function showdiv(id)
{
//alert('ss');
var elem = document.getElementById(id + '_content');
if(elem.style.display=='none')
{
elem.style.display='';
}
else
{
elem.style.display='none';
}
}
</script>	
	
	
    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
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
                        
                        <h1 align="center">  لیست پیش فاکتور های  تایید نشده </br>
						* تاییدیه ارسال کالا را از مشاور بازرس کنترل کیفیت پیگیری نمایید
						      <a  href=<?php print "../reports/reports_PipeProduction.php";?>>
								<img align="left" style = "width: 2%;" src="../img/Return.png" title='بازگشت' >
							</a>
                      
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
                    
				if ($login_isfulloptionc!=1 && $login_PipeProducer==1) $styl="style='display:none'";
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
                           	<span class="f12_fontb"> عنوان پیش فاکتور </span> </th>
							<th  
                            <span class="f12_fontb" >تاریخ پیش فاکتور</span> </th>
							<th 
                            <span  class="f12_fontb">شرکت تولیدکننده</span> </th>
								
							<th 
                            <span <?php echo $styl;?> class="f12_fontb">شرکت مجری</span> </th>
							<th  
                            <span class="f12_fontb" width="15%">نام متقاضي</span>
							<span class="f9_fontb"> (پروژه) </span> </th>
                            <th 
                            <span class="f12_fontb"> مساحت </span>
							<span class="f9_fontb"> (ha)  </span> </th>
                            
					    	<th 
                            <span class="f12_fontb">نوع پروژه</span> </th>
						    
						    <th 
                            <span class="f12_fontb">دشت/شهرستان</span> </th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                   $rown=0;
				   if ($login_isfulloption==1 || $login_PipeProducer==1)
                   if ($result)
                    while($row = mysql_fetch_assoc($result)){
                        //print $rown;
						
                        $Code = $row['Code'];
                       // $ID = $row['invoicemasterid'];
                        $ID = $row['invoicemasterid'].'__'.$row['ApplicantMasterID'].'_'.$row['ProducersID'];

							$contin=0;
                            if ($row['ApproveA']>0)
									$imgt='searchPg.png';
                                else if ($row['BOLNO']>0)
									$imgt='searchPy.png';
                                else if ($row['ApproveP']>0)
                                    {$imgt='searchPb.png';$contin=1;}
                                else 
                                    {$imgt='search_page.png';$contin=1;}
							if ($login_isfulloptionc!=1 && $login_PipeProducer==1 && $contin==0)  continue;
						  $rown++; 
                      	
?>                      
                        <tr>
                            
                            <td
                            <span class="f10_font" >  <?php echo $rown; ?> </span> </td>
							
                            <td
							<span class="f10_font">  <?php echo $row['Title']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['InvoiceDate']; ?> </span> </td>
							
                            <td 
							<span class="f10_font">  <?php echo $row['ProducersTitle'].$Title; ?> </span> </td>
                            
                            <td
							<span <?php echo $styl;?> class="f10_font">  <?php echo $row['operatorcotitle']; ?> </span> </td>
                           
                            <td
							<span class="f11_font">  <?php echo $row['ApplicantFName']." ".$row['ApplicantName']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td
							<span class="f10_font">  <?php echo $row['prjtypeTitle']; ?> </span> </td>
                            
                            <td
							<span class="f9_font">  <?php echo $row['shahrcityname']   ; ?> </span> </td>
                           
                           
                            
                            <?php 
						                     
                            if ($login_RolesID<>29)
                            print "<td><a target='_blank' href='producer_notapprovedinvoice_detail.php?uid=".
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/$imgt' title=' ريز '></a></td></tr>";
                            
                                

                    }
	?>
	
	
	  <tr> <td colspan="9"> ----------------------------------------------------------</td> </tr>
	  <tr> <td colspan="9">  *قبل از تولید کالا برنامه زمانبندی تولید کالای هر طرح را ثبت نمایید. </td>  </tr>
	  <tr> <td colspan="9">  *پس از تولید کالا ، تاییدیه دستگاه نظارت را پیگیری نمایید. </td>  </tr>
	  <tr> <td colspan="9">  *پس از ارسال کالا شماره بارنامه  را ثبت نمایید. </td>  </tr>
						
                   
                    </tbody>
                   
                </table>
                
                <h1 align="center"> 	 <a href='javascript:void();' onclick='showdiv(id);' id='test'>
				 لیست پیش فاکتور های تایید شده </h1>
 <div id="test_content" style="display:none;">
               
                   <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th  
                           	<span class="f9_fontb" > رديف  </span> </th>
							<th  
                           	<span class="f12_fontb"> عنوان پیش فاکتور </span> </th>
							<th  
                            <span class="f12_fontb" >تاریخ پیش فاکتور</span> </th>
							<th <?php echo $style;?>
                            <span class="f12_fontb">شرکت تولیدکننده</span> </th>
							<th 
                            <span class="f12_fontb">شرکت مجری</span> </th>
							<th  
                            <span class="f12_fontb" width="15%">نام متقاضي</span>
							<span class="f9_fontb"> (پروژه) </span> </th>
                            <th 
                            <span class="f12_fontb"> مساحت </span>
							<span class="f9_fontb"> (ha)  </span> </th>
                            
						    <th 
                            <span class="f12_fontb">دشت/شهرستان</span> </th>
       				    <th 
                            <span class="f12_fontb">امتیاز</span> </th>
                            <th class="f12_fontb" width="5%">وضعیت</th>
					         <th class="f12_fontb" width="5%">کنترل کیفیت</th>
							
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                   $rown=0;
				     if ($login_isfulloption==1)
                   if ($resultdown)
                    while($row = mysql_fetch_assoc($resultdown)){
                        $rown++; 

                        $Code = $row['Code'];
                        $ID = $row['invoicemasterid'];
						
?>                      
                        <tr>
                            
                            <td
                            <span class="f10_font" >  <?php echo $rown; ?> </span> </td>
							
                            <td
							<span class="f10_font">  <?php echo $row['Title']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['InvoiceDate']; ?> </span> </td>
                            
                            <td <?php echo $style;?>
							<span class="f10_font">  <?php echo $row['ProducersTitle']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['operatorcotitle']; ?> </span> </td>
                           
                            <td
							<span class="f11_font">  <?php echo $row['ApplicantFName']." ".$row['ApplicantName']; ?> </span> </td>
                           
                            <td
							<span class="f10_font">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td <span class="f10_font">  <?php echo $row['shahrcityname']   ; ?> </span> </td>
                            <td <span class="f10_font">  <?php echo $row['score']   ; ?> </span> </td>
                           
                           <td <span class="f9_font">  <?php echo $row['sendstate']   ; ?> </span> </td>
                           <td <span class="f9_font">  <?php echo $row['DesignerCotitle']   ; ?> </span> </td>
                           
                     <?php print "<td><a target='_blank' href='producer_notapprovedinvoice_detail.php?uid=".
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).$ID.'_4'.rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/$imgt' title=' ريز '></a></td>";
					if ($login_RolesID<>3)		
					print "<td><a target='_blank' href='../appinvestigation/product_timing.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_5'.rand(10000,99999).
                            "'>
                            <img style = 'width: 20px;' src='../img/table.png' title='  زمانبندی و تحویل کالا '></a></td></tr>";
       
							
							
			        }
					?>
                   
                    </tbody>
                   
                </table>
                
    </div>
            
                
                
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
