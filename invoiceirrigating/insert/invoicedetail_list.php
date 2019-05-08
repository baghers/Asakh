<?php 

/*

insert/invoicedetail_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
appinvestigation/allinvoicemaster_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_RolesID==17)
    $login_DesignerCoID='67';
    
$formname='invoicedetail';
$tblname='invoicedetail';//ریز لوازم
      $Permissionvals=supervisorcoderrquirement_sql($login_ostanId);//دریافت اطلاعات پیکربندی سیستم

    
if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST['InvoiceMasterID']>0) $InvoiceMasterID=$_POST['InvoiceMasterID'];
    else $InvoiceMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
/*
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
applicantstatesID شناسه وضعیت طرح
shahrcityname نام شهر طرح
prjtypeid شناسه نوع پروژه
applicantstatesID شناسه وضعیت طرح
applicantstates جدول تغییر وضعیت های طرح
tax_tbcity7digit جدول شهرهای مختلف
*/ 
$query = "SELECT invoicemaster.ApplicantMasterID,applicantmaster.ApplicantMasterIDmaster,invoicemaster.Description,applicantstatesID,
ApplicantFName,ApplicantName,DesignArea,shahr.cityname shahrcityname,applicantmasterdetail.prjtypeid FROM invoicemaster 
            inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid
            inner join applicantmasterdetail on (applicantmasterdetail.applicantmasterid=invoicemaster.applicantmasterid or 
            applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.applicantmasterid or
            applicantmasterdetail.ApplicantMasterIDsurat=invoicemaster.applicantmasterid)
            inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
    
            where  invoicemaster.InvoiceMasterID = " . $InvoiceMasterID ;
//print $query;exit;

        	 	 		  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$resquery = mysql_fetch_assoc($result);

$ApplicantName="$resquery[ApplicantFName] $resquery[ApplicantName] - $resquery[DesignArea] هکتار شهرستان $resquery[shahrcityname]";
$DesignArea = $resquery['DesignArea'];

    $prjtypeid=$resquery['prjtypeid'];
    $applicantstatesID=$resquery['applicantstatesID'];
	$linearray = explode('_',$resquery['Description']);
    $Description=$linearray[0];
    $Description2=$linearray[1];
    $Description3=$linearray[2];
    $Gadget1IDstr="";
    if ($linearray[3]>0)
    $Gadget1IDstr=" and gadget2.Gadget1ID='".$linearray[3]."'";
    
$ApplicantMasterID = $resquery['ApplicantMasterID'];
$ApplicantMasterIDmaster=$resquery['ApplicantMasterIDmaster'];
if ($resquery['ApplicantMasterIDmaster']>0)
{
    $issurat=1;
    $hideprice="";  
    $hidegadget3="";   
}
else
{
    $issurat=0;
    $hideprice=" and ifnull(pricelistdetail.hide,0)=0";
    $hidegadget3=" and ifnull(gadget3.IsHide,0)=0";
    
}   
                
if ($_POST)
    {
        
        
        if (!($_POST['InvoiceMasterID']>0))
        {
            print "خطا در یافتن InvoiceMasterID";
            exit;
        }
        
        
        
    if (($_FILES["file1"]["size"] / 1024)<=200)//بارگذاری اسکن
     {
        if ($_FILES["file1"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file1"]["error"] . "<br>";
        } 
        else 
        {
            //echo "Upload: " . $_FILES["file1"]["name"] . "<br>";
            //echo "Type: " . $_FILES["file1"]["type"] . "<br>";
            //echo "Size: " . ($_FILES["file1"]["size"] / 1024) . " kB<br>";
            //echo "Stored in: " . $_FILES["file1"]["tmp_name"];
            
            $ext = end((explode(".", $_FILES["file1"]["name"])));
                foreach (glob("../../upfolder/invoice/" . $_POST["InvoiceMasterID"].'_1*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/invoice/" . $_POST["InvoiceMasterID"].'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
        
        }        
     }   
    
    
        if ($login_RolesID==2 || $login_RolesID==9 || $login_RolesID==10)
        {
            if (($_FILES["file2"]["size"] / 1024)<=200)//بارگذاری اسکن
             {
                if ($_FILES["file2"]["error"] > 0) 
                {
                    echo "Error: " . $_FILES["file2"]["error"] . "<br>";
                } 
                else 
                {
                    $ext = end((explode(".", $_FILES["file2"]["name"])));
                    $FName="../temp/".$_POST["InvoiceMasterID"].'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                    move_uploaded_file($_FILES["file2"]["tmp_name"],$FName);   
                    //$ApplicantMasterID=2294;
                    //echo $FName;
                    echo "1";
                    readfromexcel($FName,$ApplicantMasterID,$login_OperatorCoID,$login_DesignerCoID,$_POST['InvoiceMasterID'],$_POST['PriceListMasterID'],$_POST['masterProducersID'],$login_userid,0,0,'IM',0);
                    echo "بارگذاری با موفقیت انجام شد<br>";
                    header("Location: "."invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999));
                }        
             }
        }
    
        $Discont = str_replace(',', '', $_POST['Discont']);
            $TransportCost = str_replace(',', '', $_POST['TransportCost']);
             $Description = "$_POST[Description]_$_POST[Description2]_$_POST[Description3]";
             //invoicemaster جدول لیست پیش فاکتورها
        	   $query = "
        		UPDATE invoicemaster SET
        		Discont = '" . $Discont . "', 
        		TransportCost = '" . $TransportCost . "',
                Description='$Description'
        		WHERE InvoiceMasterID = " . $InvoiceMasterID . ";";
                    	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                $register = true;
        //print $query;
        //exit();
       
       
       
       
       $i=0;
        while (isset($_POST['Number'.++$i]))
        {
        	$InvoiceDetailID = $_POST['InvoiceDetailID'.$i];
            
            $ToolsMarksID = $_POST['ToolsMarksID'.$i];
            $Number = str_replace(',', '', $_POST['Number'.$i]);
            $Description = $_POST['Description'.$i];
            $_POST['chk'.$i] = $_POST['chk'.$i];
            //print $_POST['chk'.$i];
            if (($_POST['chk'.$i]==1) && ($InvoiceDetailID != 0))
            {
                $query = " delete from invoicedetail WHERE InvoiceDetailID ='$InvoiceDetailID' ;";
                //print $query;
               // exit;
                     		  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                continue;
            }
            
        	if ($ToolsMarksID != "" && $Number != "")	
        	if ($InvoiceDetailID != 0)//update
            {
                //invoicedetail ریز لوازم
        		$query = "
        		UPDATE invoicedetail SET
        		val1 = '" . $Number . "',     
        		Number = '" . $Number . "',     
        		SaveTime = '" . date('Y-m-d H:i:s') . "', 
        		SaveDate = '" . date('Y-m-d') . "', 
        		ClerkID = '" . $login_userid . "'
        		WHERE InvoiceDetailID = " . $InvoiceDetailID . ";";
                
                //print $query;
                 	 		  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                $register = true;
        	}
         }
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
        	 
            
        if ($prjtypeid==1 && $ApplicantMasterID>0)
        {
            savewsvals ($ApplicantMasterID,$prjtypeid);
        }
     }


if (! $_POST['submit'])
{

        
    $query = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='operatorProducersID' ";
					   		  	try 
								  {		
									   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	$row = mysql_fetch_assoc($result);
    $operatorProducersID=$row['ValueInt'];
    

    



   	
    //print $np;
    //exit();
/*
invoicemaster لیست پیش فاکتورها
taxless بدون مالیات
ApplicantMasterID شناسه طرح
producers تولیدکننده
TransportCost حمل و نقل
Discont تخفیف

*/

    $query = "SELECT ifnull(invoicemaster.taxless,0) taxless,invoicemaster.ApplicantMasterID,invoicemaster.ProducersID,invoicemaster.TransportCost,
    invoicemaster.Discont,invoicemaster.InvoiceDate,invoicemaster.Rowcnt,invoicemaster.Serial,invoicemaster.Title
    ,producers.Title as PTitle,invoicemaster.Description,PriceListMasterID,producers.PipeProducer FROM invoicemaster
left outer join producers on producers.ProducersID=invoicemaster.ProducersID

        where  invoicemaster.InvoiceMasterID ='$InvoiceMasterID' " ;

  //  print $query;
   // exit;
    
   					   		  	try 
								  {		
									   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $resquery = mysql_fetch_assoc($result);
	$taxless=$resquery['taxless'];
    $PriceListMasterID=$resquery['PriceListMasterID'];
    $masterProducersID = $resquery['ProducersID'];
    $TransportCost = $resquery['TransportCost'];
    $Discont = $resquery['Discont'];                        
    $PipeProducer = $resquery['PipeProducer'];
	
    $np = $resquery['Rowcnt'];
    //$np = 35;
    
    if ($np<1) $np=1;
    
    $Serial = $resquery['Serial'];
    $Title = $resquery['Title'];
    $PTitle = $resquery['PTitle'];
    
    $linearray = explode('_',$resquery['Description']);
    $Description=$linearray[0];
    $Description2=$linearray[1];
    $Description3=$linearray[2];
    
    $InvoiceDate = $resquery['InvoiceDate'];
                        
    $TAXPercent=0;
    if (strlen($resquery['InvoiceDate'])>0 && $taxless==0)
    {
        $InvoiceYear = substr($resquery['InvoiceDate'],0,4);
        $query = "SELECT taxpercent.value FROM taxpercent 
        inner join year on year.YearID=taxpercent.YearID
        where  year.Value = '" . $InvoiceYear."'" ;
        //print $query;
        
        			   		  	try 
								  {		
									   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $resquery = mysql_fetch_assoc($result);
        $TAXPercent = $resquery['value'];
        
    }
    $condlimited='';
    $limited = array("9");if ( in_array($login_RolesID, $limited)) $condlimited=' and pfd=1 '; 
    
    if ($ApplicantMasterIDmaster>0)
    {
    $sql2="select PE32app,PE40app,PE80app,PE100app,ProducersID,transportless from producerapprequest where state=1 
            and ApplicantMasterID='$ApplicantMasterIDmaster'";  
    $appmasterinv="left outer join invoicemaster invoicemastermaster on invoicemastermaster.invoicemasterID=invoicemaster.InvoiceMasterIDmaster";    
                
    }  
    else
    {
        $sql2="select PE32app,PE40app,PE80app,PE100app,ProducersID,transportless from producerapprequest where state=1 
        and ApplicantMasterID='$ApplicantMasterID'"; 
        $appmasterinv="left outer join invoicemaster invoicemastermaster on invoicemastermaster.invoicemasterID=invoicemaster.invoicemasterID";
    } 
            
        //print $sql2;
       
					   		  	try 
								  {		
									    $result2 = mysql_query($sql2);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $row2 = mysql_fetch_assoc($result2);
        if ($row2['PE32app']>0 or $row2['PE40app']>0 or $row2['PE80app']>0 or $row2['PE100app']>0 )
        {
            $guerypipeprice="left outer join (select '$row2[ProducersID]' ProducersID, '$row2[PE32app]' PE32,'$row2[PE40app]' PE40,'$row2[PE80app]' PE80,'$row2[PE100app]' PE100 )
             pipeprice on pipeprice.ProducersID=toolsmarks.ProducersID";
        }
        else $guerypipeprice="left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=invoicemaster.InvoiceDate $condlimited) and pipeprice.ProducersID=toolsmarks.ProducersID"; 
        
        //print $guerypipeprice;exit;
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
    $sql = "
            SELECT distinct invoicedetail.InvoiceDetailID,invoicedetail.ToolsMarksID,
            gadget3.Code,ifnull(gadget3.gadget3ID,0) gadget3ID,ifnull(gadget2.gadget2ID,0) gadget2ID,ifnull(toolsmarks.ProducersID,0) ProducersID,ifnull(toolsmarks.marksID,0) marksID,units.
        title utitle,invoicedetail.Number,
        
        case gadget3.gadget2id 
        when 202 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE80 else pipeprice.PE80 end) 
        when 376 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE100 else pipeprice.PE100 end) 
        when 495 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE32 else pipeprice.PE32 end) 
        when 494 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemastermaster.proposable,0) when 0 then pipepricew.PE40 else pipeprice.PE40 end)
            else case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
            syntheticgoodsprice.price end  end Price
        
        ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(
        CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),
        CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),
        ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
        fulltitle
        
        FROM invoicedetail 
        inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID
        left outer join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
        left outer join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        left outer join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        left outer join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID
        left outer join units on gadget3.unitsID=units.unitsID
        left outer join pricelistmaster on pricelistmaster.PriceListMasterID='$PriceListMasterID'
        
        left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID $hideprice and 
                                            pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid 
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        
        $guerypipeprice
        left outer join pipeprice pipepricew on pipepricew.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=invoicemaster.InvoiceDate $condlimited) and pipepricew.ProducersID=toolsmarks.ProducersID
        $appmasterinv
            
        left outer join syntheticgoodsprice on syntheticgoodsprice.PriceListMasterID='$PriceListMasterID' and 
        syntheticgoodsprice.gadget3ID=gadget3.gadget3ID
        
            
            
                                                
        where  invoicedetail.InvoiceMasterID = " . $InvoiceMasterID . "
        ORDER BY invoicedetail.InvoiceDetailID;";
    
    //print $sql;

   
		   		  	try 
								  {		
									    $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیش فاکتور </title>


	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
<script type="text/javascript">
$().ready(function() {

   
        
    
    
    for (var j=1;j<=(document.getElementById('records').rows.length-8);j++)
    {
        if (document.getElementById('gadget3ID'+j).selectedIndex>0)
	   $("#suggest"+j).val(document.getElementById('gadget3ID'+j).options[1].text);	
    }
        
    
    
	$("#clear").click(function() {
		$(":input").unautocomplete();
	});
});
</script>
	<script type="text/javascript">

setInterval(function () {document.getElementById("tempsubmit").click();}, 600000);


function countdown(element, minutes, seconds) {
    // set time for the particular countdown
    var time = minutes*60 + seconds;
    var interval = setInterval(function() {
        
        var el = document.getElementById(element);
        //alert(el);
        
        // if the time is 0 then end the counter
        if(time == 0) {
            el.innerHTML = "countdown's over!";    
            clearInterval(interval);
            return;
        }
        var minutes = Math.floor( time / 60 );
        if (minutes < 10) minutes = "0" + minutes;
        var seconds = time % 60;
        if (seconds < 10) seconds = "0" + seconds; 
        var text = minutes + ':' + seconds;
        //document.getElementById(element).value=text;
        el.value = text;
        time--;
        if (time<=0) $("#loading-div-background").show();
    }, 1000);
}


countdown( "intimer", 10, 00 );
$("#loading-div-background").hide();

var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}
</script> 





    <!-- /scripts -->
</head>
<body  >

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
            <?php 
            include('../includes/top.php');
            include('../includes/navigation.php'); 
              include('../includes/subnavigation.php');
               //include('../includes/header.php');
            ?> 
  	
			<!-- /header -->

			<!-- content -->
			<div id="content" ><?php

	
				if ($_POST){
	
					if ($register){
						//echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						
                        
                        $Serial = "";
                        if (!$_POST['tempsubmit'])
                        header("Location: "."invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999));
                        //print $_POST['tempsubmit'];
                        //print $_POST['submit'];
                        
                        
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}
	                            
             	    




?>
            <form action="invoicedetail_list.php" method="post" onSubmit="return CheckForm()"  enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                  
     <div id="loading-div-background">
    <div id="loading-div" class="ui-corner-all" >
      <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
      <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
     </div>
    </div>


<script type='text/javascript'>


         $(document).ready(function () {
            $("#loading-div-background").css({ opacity: 0.8 });
           
        });


</script>

                        <tr>
                            <td>
                                                   

<?php 


  print "<script type='text/javascript'> 




function farsireplace(valin)
{
    valin.trim();
    valin = valin.replace(/ي/g, \"ی\"); 
    valin = valin.replace(/ك/g, \"ک\"); 
    return valin;
}
function whiteelements()
{
    for (var j=1;j<=(document.getElementById('records').rows.length-8);j++)
    {
        document.getElementById('suggest'+j).style.backgroundColor = 'white';
        document.getElementById('ProducersID'+j).style.backgroundColor = 'white';
        document.getElementById('marksID'+j).style.backgroundColor = 'white';
        document.getElementById('Number'+j).style.backgroundColor = 'white';
    }  
    
}


$(function() {
  $('#divgadget3ID1').filterByText($('#textbox'), true);
}); 

//--------------------------------------------------------------------------------------------------------------------------------------------
function p_tarkib(_value)
{
 var _len;var _inc;var _str;var _char;var _oldchar;_len=_value.length;_str='';
 for(_inc=0;_inc<_len;_inc++)
 {
   _char=_value.charAt(_inc);
   if (_char=='1' || _char=='2' || _char=='3' || _char=='4' || _char=='5' || _char=='6' || _char=='7' || _char=='8' || _char=='9' || _char=='0') 
      _str=_str+_char;
   else
      if (_char!=',') return 'error';
 }
 return _str;
}



function summ()
{	    
    var sumt=0;
            
                                                  
		for (var i=1;i<=(document.getElementById('records').rows.length-8);i++)
        {
            
			sumt += p_tarkib(document.getElementById('Price'+i).value)*document.getElementById('Number'+i).value*1;
        
        }
            
                         
                                                    
                                                                               
    document.getElementById('AllSum').value=numberWithCommas(sumt);
    
    document.getElementById('Discont').value=numberWithCommas(p_tarkib(document.getElementById('Discont').value));
    document.getElementById('TransportCost').value=numberWithCommas(p_tarkib(document.getElementById('TransportCost').value));
    
    document.getElementById('TAX').value=numberWithCommas(Math.round(sumt*document.getElementById('TAXPercent').value/100));
    document.getElementById('Total').value=numberWithCommas(
                                                    sumt*1+p_tarkib(document.getElementById('TAX').value)*1-
                                                    p_tarkib(document.getElementById('Discont').value)*1+
                                                    p_tarkib(document.getElementById('TransportCost').value)*1
                                                    ); 
}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}



function openWinall()
{
    //alert(document.getElementById('home_path_iri').value+'/tools/toolssearch.php');
var myWindow = window.open(document.getElementById('home_path_iri').value+'/tools/toolssearch.php',\"\",\"width=1000,height=500\");
}
function openWin()
{
    //alert(document.getElementById('home_path_iri').value+'/tools/toolssearch.php');
var myWindow = window.open(document.getElementById('home_path_iri').value+'/tools/toolsonlysearch.php',\"\",\"width=1000,height=500,scrollbars=1\");
}
function cboxclick()
{
           
    for (var j=1;j<=(document.getElementById('records').rows.length-8);j++)
        {
            
    var selectedmarksID=document.getElementById('marksID'+j).value;
    var selectedgadget3ID=document.getElementById('gadget3ID'+j).value;
    
    var selectedgadget2ID=document.getElementById('gadget2ID'+j).value;
    var selectedProducersID=document.getElementById('ProducersID'+j).value;
    
    if (!(selectedmarksID>0)&& !(selectedgadget3ID>0)&& !(selectedgadget2ID>0)&& !(selectedProducersID>0)) 
        
        
        
    for (var i = 1; i < document.getElementById(\"allgadget3ID\").length; i++) 
    {
    var option = document.createElement(\"option\");
    option.value = document.getElementById(\"allgadget3ID\").options[i].value;
    option.text = document.getElementById(\"allgadget3ID\").options[i].text;
    document.getElementById('gadget3ID'+j).appendChild(option);
    }
    
        }
        
    
}
</script>
"; 
 ?>


                <div colspan="4">
                <tr >
                    <td colspan="5" align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-family:'B Nazanin';">  پیش فاکتور/لیست لوازم فروش  <?php print $Title."<br>$ApplicantName"; ?></td>
                </tr><tr>
                    <td style = "border:0px solid black;width: 10%;"></td>
                    <td colspan="2" style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';" >  </td>
                    <td style = "border:0px solid black;width: 5%;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';">   شماره:  </td>
                    <td style = "border:0px solid black;width: 5%;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';">   <?php print $Serial ?>  </td>
                </tr><tr>
                    <td style = "border:0px solid black;width: 10%;"></td>
                    <td colspan="2" style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';"> <?php print $PTitle; ?> </td>
                    <td style = "border:0px solid black;width: 5%;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';">   تاریخ:  </td>
                    <td style = "border:0px solid black;width: 5%;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';">   <?php print $InvoiceDate; ?>  </td>
                </tr>
                <tr>
                    <td  style = "border:0px solid black;width: 10%;text-align:left;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';">&nbsp;   توضیحات:  &nbsp;</td>
                    <td colspan="4" style = "border:0px solid black;width: 90%;font-size:14.0pt;line-height:95%;font-family:'B Nazanin';"> <?php print $Description; ?> </td>
                </tr>
                
                </div>
                
                
                

                        	
                        
                        
    <br />
    
                            <div style = "text-align:left;">
                            <input name="intimer" id="intimer" readonly  size="3" />
                            <button title='جستجوی کالا' style="cursor:pointer;width:50px;height:70px;background-color:transparent; border-color:transparent;" 
                            type="button" onclick="openWin()">
                           <img style = 'width: 60%;' src='../img/mail_search.png' ></button > 
                          <a  href=<?php print "invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a>
                          </div>
                          
                          
                            
                            
                            
                            <td align="left"><?php
                            if ($login_RolesID==2)
                            print "
                            <td colspan='1' class='label' style = \"color: red;border:0px solid black;width: 100%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \">
                            
                        <a  target=\"_blank\"  href='../../upfolder/help/registertools.pdf'>
                        راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه
						<img style = 'width: 25px' src=\"../img/help.png\" title='راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه'></a>
                        </td>
						";
                        else
                         print "
                            <td colspan='1' class='label' style = \"color: red;border:0px solid black;width: 100%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \">
                            
                        <a  target=\"_blank\"  href='../../upfolder/help/registertoolsdesigner.pdf'>
                        راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه
						<img style = 'width: 25px' src=\"../img/help.png\" title='راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه'></a>
                        </td>
						";
                        
                            
                            
                            if ($login_RolesID==2 || $login_RolesID==9  || $login_RolesID==10)
                            {
                                echo "<td colspan='1' class='label'>فایل اکسل لوازم</td>
                                    <td colspan='1' class='data'><input type='file' name='file2' id='file2' ></td>
                                    <td class=\"data\"><input name=\"InvoiceMasterID\" type=\"hidden\" class='textbox' id='InvoiceMasterID'  value='$InvoiceMasterID'  size='30' maxlength='15' /></td>
                      
                                    <td colspan='2'><input name=\"tempsubmit\" type=\"submit\" class=\"button\" id=\"tempsubmit\" value=\"ثبت \"/></td>
                                    ";
                            }
                            
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
                <!-- <input type="button" value="جستجوی کالا/تولیدکننده/مارک" onclick="openWinall()"> -->
                
                            
                            
                
                <table id="records" width="100%" align="center" border="1">
                    <thead>
                        <tr>
						<td></td><td></td>
						    <th class="f13_fontb" width="3%" colspan="2">ردیف</th>
                            <th class="f14_fontb" width="40%" colspan="1" align="center">عنوان کالا</th>
                            <th class="f13_fontb" width="15%">تولید کننده/ فروشنده</th>
                            <th class="f14_fontb" width="12%">مارک</th>
                            <th class="f14_fontb" width="5%">واحد</th>
                            <th class="f14_fontb" width="5%">مقدار</th>
                            <th class="f14_fontb" width="10%" colspan="1">فی</th>
                            <th class="f14_fontb" width="15%" colspan="2">جمع مبلغ</th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $cnt=0;
                    $rown=0;
                    $sum=0;
                    if ($operatorProducersID!=$masterProducersID)
                        $selectedPID=$masterProducersID;
                                

                                   
                                  
                                
                                
                                    $query="select '' as _value, 'لطفا برای پر شدن لیست اینجا را کلیک نمایید' as _key ";
                                    $allIDgadget3ID = get_key_value_from_query_into_array($query);
                              
                    if ($selectedPID>0)
                    $query="select producersID as _value,producers.Title as _key from producers
                                where ProducersID='$selectedPID'
                                order by _key";
                    else
                    $query="select producersID as _value,producers.Title as _key from producers
                                order by _key";
                    $IDProducersID = get_key_value_from_query_into_array($query);
                    
                    if ($operatorProducersID!=$masterProducersID )
                    { 
                        $query="select distinct marks.marksID as _value,marks.Title as _key from marks
                            inner join toolsmarks on  toolsmarks.ProducersID='$selectedPID' and toolsmarks.marksID=marks.marksID
                            order by _key COLLATE utf8_persian_ci";
                        //print $query;             
                        $IDmarksID = get_key_value_from_query_into_array($query);      
                    }
                                                    
                                                    
                                                    
                    $tabindex=1;
                    if ($result)            
                    while($row = mysql_fetch_assoc($result)){
                            $InvoiceDetailID = 0;
                            $Code =  '';
                            $gadget3ID =  0;
                            $gadget2ID =  0;
                            $ProducersID =  0;
                            $ToolsMarksID =0;
                            $marksID =  0;
                            $utitle =  '';
                            $Number =  '';
                            $Price =  '';
                            $SumPrice =  '';
                            $Description =  '';
                            $IDgadget3ID='';
                            
                        if ($row)
                        {
                            $InvoiceDetailID = $row['InvoiceDetailID'];
                            $ToolsMarksID = $row['ToolsMarksID'];
                            
                            $Code = $row['Code'];
                            $gadget3ID = $row['gadget3ID'];
                            $gadget2ID = $row['gadget2ID'];
                            $ProducersID = $row['ProducersID'];
                            $marksID = $row['marksID'];
                            $utitle = $row['utitle'];
                            $Number = $row['Number'];
                            $Price = number_format($row['Price']);
                            $SumPrice = number_format($row['Number']*$row['Price']);
                            $Description = $row['Description'];
                            $sum+=$row['Number']*$row['Price'];
                            
                            
                            
                            
                            $query="select producersID as _value,producers.Title as _key from producers
                                where ProducersID='$ProducersID'
                                order by _key";
                            $IDProducersID = get_key_value_from_query_into_array($query);
                            
                            
                            $query="select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID 
                                    inner join gadget3 on gadget3.gadget2ID='$gadget2ID'
                                    order by _key";
                            $IDgadget2ID = get_key_value_from_query_into_array($query);                            
                            
                            $query="select marks.marksID as _value,marks.Title as _key from marks where marksID='$marksID'
                                 order by _key COLLATE utf8_persian_ci";
                                    
                            $IDmarksID = get_key_value_from_query_into_array($query);   
                            
                                
                                
                        }
                        else
                        {
                           $IDgadget3ID = $allIDgadget3ID;
                        }
                        
                        if ($ProducersID==0)
                            $ProducersID=$selectedPID ;
                        if ($ProducersID==148 && $login_RolesID==17)    
                            $marksID=128;
                            
                        $cnt++;
                        
                        $rown++;
                        
                               
?>
                        <tr>
                        <td >
                            <div   id="divInvoiceDetailID<?php echo $cnt; ?>" style='visibility: hidden;width:1px;' >
                            <input name="InvoiceDetailID<?php echo $cnt; ?>" class="textbox" id="InvoiceDetailID<?php echo $cnt; ?>"  value="<?php echo $InvoiceDetailID; ?>"  size="30" maxlength="15" />
                            </div></td>
                            
                            <td >
                            <div   id="divToolsMarksID<?php echo $cnt; ?>" style='visibility: hidden;width:1px;'>
                            <input name="ToolsMarksID<?php echo $cnt; ?>"  class="textbox" id="ToolsMarksID<?php echo $cnt; ?>" value="<?php echo $ToolsMarksID; ?>" size="18" maxlength="18" readonly />
                            </div></td>
                            
                            <td ><input type="checkbox" name="chk<?php echo $cnt; ?>" id="chk<?php echo $cnt; ?>" value="1"/><td >
                            <div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 52px' maxlength="6" readonly /></div></td>
                            <?php
                                
                                    
                           // print $sz;
							// print $_SERVER['REMOTE_ADDR']."_$login_Domain";
							    $tabindex++;
                                print 
                                "
                                
                                
                                <td class='data'><div id='divtxtlist$cnt'><input type='text' id='suggest$cnt' name='suggest$cnt'
                               type='text' class=\"textbox\"  style='width: 100%' tabindex='$tabindex' value='$row[fulltitle]'/></div>"
                                 
                                
                                ."</td>";
                                print select_option('ProducersID'.$cnt,'',',',$IDProducersID,++$tabindex,'','','1','rtl',0,'',$ProducersID,
                                "",
                                '100%');

                                //print $query;
                                
                                
                                print select_option('marksID'.$cnt,'',',',$IDmarksID,++$tabindex,'','','1','rtl',0,'',$marksID,
                               ""
	                            ,'100%');
                                $tabindex++;

	           				  ?>

                            <td class="data"><div id="divutitle<?php echo $cnt; ?>"><input  onmouseover="Tip(<?php echo '(\''.$utitle.'\')'; ?>)" name="utitle<?php echo $cnt; ?>" type="text" class="textbox" id="utitle<?php echo $cnt; ?>" value="<?php echo $utitle; ?>" style='width: 75px'  readonly /></div></td>
                            
                            <td class="data"><div id="divNumber<?php echo $cnt; ?>"><input  
                            
                             onmouseover="Tip(<?php echo '(\''.$Number.'\')'; ?>)" name="Number<?php echo $cnt; ?>" tabindex="<?php echo $tabindex; ?>" type="text" class="textbox" id="Number<?php echo $cnt; ?>" value="<?php echo $Number; ?>" style='width: 75px' maxlength="12"
                            
                            <?php echo 
                            "onchange$sz = \"FilterNextCombobox('$cnt');\"
                             
                             onblur$sz=\" whiteelements();\"
                             onfocus$sz=\"whiteelements();document.getElementById('Number$cnt').style.backgroundColor = 'yellow';\"
                            /></div></td>";
                            
                            echo 
                            "<td class='data'><div id='divPrice$cnt'><input  name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price' style='width: 100%' maxlength='12'  readonly /></div></td>
                            <td class='data'><div id='divSumPrice$cnt'><input  name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice' style='width: 194px' readonly /></div>
                            
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptyPrice$cnt'><input  name='EmptyPrice$cnt' type='text' class='textbox' id='EmptyPrice$cnt'  maxlength='12'  readonly /></div></td>
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptySumPrice$cnt'><input  name='EmptySumPrice$cnt' type='text' class='textbox' id='EmptySumPrice$cnt'  readonly /></div>
                            ";
                            
                       

                    }
                        

                    echo "
                    </tbody>
                    
                    <tfoot>";  
                    
                    $fstr1="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/invoice/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
                            if (($ID==$InvoiceMasterID) && ($No==1) )
                                $fstr1="<a target='blank' href='../../upfolder/invoice/$file' ><img style = 'width: 30%;' src='../../upfolder/invoice/$file' title='اسکن پیش فاکتور' ></a>";
                            
                            
                        }
                    }
                    
                    if ($blacklist==0)
                    echo "  
                       
                      
                      <tr>
                      
                        <td colspan='4' class='label'>اسکن</td>
                     
                        <td colspan='1' class='data'><input type='file' name='file1' id='file1' ></td>
                        <td>$fstr1</td>
                     
                     
                      <td colspan='1'></td>
                      <td colspan='3' class='f13_fontb'>مجموع</td>
                      <td colspan='7' class='data'><div id='divAllSum'><input name='AllSum' type='text' class='textbox' id='AllSum' value='".
                      number_format($sum)."' size='33' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div style='visibility: hidden'  id='divEmptyAllSum'>
                      <input name='EmptyAllSum' type='text' class='textbox' id='EmptyAllSum'  size='20' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      
                      <tr>
                       <td colspan='7' 
                       style = \"color: red;border:0px solid black;width: 10%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \"
                       >اسکن باید کاملا خوانا و حداکثر اندازه 200 کیلوبایت باشد. پیش فاکتورهای با اسکن ناخوانا برگشت داده خواهد شد.</td>
                     
                      <td colspan='3' class='f13_fontb'>مالیات بر ارزش افزوده</td>
                      <td colspan='7' class='data'><div id='divTAX'><input name='TAX' type='text' class='textbox' id='TAX' value='".
                      number_format($TAXPercent*$sum/100)."' size='33' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div  style='visibility: hidden' id='divEmptyTAX'>
                      <input name='EmptyTAX' type='text' class='textbox' id='EmptyTAX'  size='20' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      ";
                   else
                    echo "  
                       <tr>
                      <td colspan='7'></td>
                      <td colspan='3' class='f13_fontb'>مجموع</td>
                      <td colspan='8' class='data'><div  id='divEmptyAllSum'>
                      <input name='EmptyAllSum' type='text' class='textbox' id='EmptyAllSum'  size='20' maxlength='20' readonly /></div></td>
                      
                      <td colspan='1' class='data'><div style='visibility: hidden'  id='divAllSum'><input name='AllSum' type='text' class='textbox' 
                      id='AllSum' value='".
                      number_format($sum)."' size='33' maxlength='20' readonly /></div></td>
                      
                      </tr>
                      
                      
                      <tr>
                      <td colspan='7'></td>
                      <td colspan='3' class='f13_fontb'>مالیات بر ارزش افزوده</td>
                      <td colspan='7' class='data'><div   id='divEmptyTAX'>
                      <input name='EmptyTAX' type='text' class='textbox' id='EmptyTAX'  size='20' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div style='visibility: hidden' id='divTAX'><input name='TAX' type='text' class='textbox' id='TAX' value='".
                      number_format($TAXPercent*$sum/100)."' size='33' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      ";
                      
                    
                    
?>
                      
                      
                      <tr>
                      <td colspan='7'></td>
                      <td colspan='1' <?php echo $hidden; ;?> > <?php  echo str_replace(' ', '&nbsp;', "هزینه های جانبی");?>  </td>
                      <td colspan='2' class='data'<?php echo $hidden; ?>> <input <?php echo $hidden; ?> name="Description2" placeholder=' بابت' id="Description2" class="textbox" value="<?php echo $Description2; ?>" size="40" /> </td>
                      <td class="data" colspan='6' <?php echo $hidden; ?>><div id="divTransportCost"><input onchange = "summ();" name="TransportCost" <?php echo $hidden; ?> class="textbox" id="TransportCost" value="<?php echo number_format($TransportCost); ?>" size="33" maxlength="20"  /></div></td>
                      </tr>
                      
                      
                      
                      
                      <tr>
                    
                    
                     
                      <td colspan='7'></td>
                      <td colspan='1'  <?php echo $hidden; ?>>تخفیف/تعدیل</td>
                      <td colspan='2' class='data' <?php echo $hidden; ?>> <input <?php echo $hidden; ?> name="Description3" placeholder=' بابت' id="Description3" class="textbox" value="<?php echo $Description3; ?>" size="40" /> </td>
                      <td class="data" colspan='6' <?php echo $hidden; ?>><div id="divDiscont"><input onchange = "summ();" name="Discont" <?php echo $hidden; ?> class="textbox" id="Discont" value="<?php echo number_format($Discont); ?>" size="33" maxlength="20"  /></div></td>
                      </tr>
                      
                        
                      
                      <tr>
                      
                      
                       <input name="Description"  id="Description" type="hidden" value="<?php echo $Description; ?>"  />
                       
                       </td>
                      <?php 
                    echo "  
                    <td colspan='7' ></td>
                      <td colspan='3' class='f13_fontb'>مبلغ قابل پرداخت</td>
                      <td colspan='7' class='data'><div id='divTotal'><input name='Total' type='text' class='textbox' id='Total' value='".
                      number_format($sum+$TransportCost-$Discont+($TAXPercent*$sum/100))."' size='33'  readonly /></div></td>
                      
                      <td colspan='1' class='data'><div style='visibility: hidden' id='divEmptyTotal'><input name='EmptyTotal' type='text' class='textbox' id='EmptyTotal' size='20'  readonly /></div></td>
                      
                      ";
                   
                      
                      ?>
                      
                      <td class="data"><input name="masterProducersID" type="hidden" class="textbox" id="masterProducersID"  value="<?php echo $masterProducersID; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="InvoiceMasterID" type="hidden" class="textbox" id="InvoiceMasterID"  value="<?php echo $InvoiceMasterID; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="PriceListMasterID" type="hidden" class="textbox" id="PriceListMasterID"  value="<?php echo $PriceListMasterID; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="TAXPercent" type="hidden" class="textbox" id="TAXPercent"  value="<?php echo $TAXPercent; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="home_path_iri" type="hidden" class="textbox" id="home_path_iri"  value="<?php echo $home_path_iri; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="login_userid" type="hidden" class="textbox" id="login_userid"  value="<?php echo $login_userid; ?>"  size="30" maxlength="15" /></td>
                      <td class="data"><input name="login_RolesID" type="hidden" class="textbox" id="login_RolesID"  value="<?php echo $login_RolesID; ?>"  size="30" maxlength="15" /></td>
                            
                            
                           
                      
                      </tr>
                      
               
                
                      
                    </tfoot>
                    
                </table>
                
                    
                </form>
                
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->
		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
