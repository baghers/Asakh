<?php 

/*

insert/invoicedetail_list2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicedetail_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='invoicedetail';
$tblname='invoicedetail';//ریز لوازم

if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST['InvoiceMasterID']>0) $InvoiceMasterID=$_POST['InvoiceMasterID'];
    else $InvoiceMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
/*
invoicemaster لیست لوازم
applicantmaster جدول مشخصات طرح
ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
*/ 
$query = "SELECT invoicemaster.ApplicantMasterID,applicantmaster.ApplicantMasterIDmaster FROM invoicemaster 
            inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid
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
        if (!($_POST['InvoiceMasterID']>0))//بارگذاری اسکن
        {
            print "خطا در یافتن InvoiceMasterID";
            exit;
        }
    if (($_FILES["file1"]["size"] / 1024)<=200)
     {
        if ($_FILES["file1"]["error"] > 0) 
        {
            echo "Error: " . $_FILES["file1"]["error"] . "<br>";
        } 
        else 
        {
            $ext = end((explode(".", $_FILES["file1"]["name"])));
                foreach (glob("../../upfolder/invoice/" . $_POST["InvoiceMasterID"].'_1*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/invoice/" . $_POST["InvoiceMasterID"].'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
        
        }        
     }   

    
        $Discont = str_replace(',', '', $_POST['Discont']);
            $TransportCost = str_replace(',', '', $_POST['TransportCost']);
             //invoicemaster جدول لیست پیش فاکتورها
        	   $query = "
        		UPDATE invoicemaster SET
        		Discont = '" . $Discont . "', 
        		TransportCost = '" . $TransportCost . "'
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
                print $query;
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
                //invoicedetail جدول ریز پیش فاکتورها
        		$query = "
        		UPDATE invoicedetail SET
        		InvoiceMasterID = '" . $InvoiceMasterID . "', 
        		ToolsMarksID = '" . $ToolsMarksID . "',
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
            else //insert
            {
                $querycnt = "SELECT count(*) cnt FROM invoicedetail 
                        where InvoiceMasterID ='$InvoiceMasterID' and ToolsMarksID='$ToolsMarksID' " ;
    
                
								 try 
								  {		
									  $resultcnt = mysql_query($querycnt);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                $resquerycnt = mysql_fetch_assoc($resultcnt);
    	          // print $querycnt;
                if (!($resquerycnt['cnt']>0))
                {
          			$query = "
                      INSERT INTO invoicedetail(InvoiceMasterID,ToolsMarksID, val1, Number,SaveTime,SaveDate,ClerkID) 
                      VALUES('" .
                      $InvoiceMasterID . "', '" . 
                      $ToolsMarksID . "', '" . 
                      $Number . "', '" . 
                      $Number . "', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
    
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
         }
         
 
    
     }


if (! $_POST['submit'])
{

    //supervisorcoderrquirement جدول تنظیمات پیکربندی    
    $query = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='operatorProducersID' ";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
    $operatorProducersID=$row['ValueInt'];
    

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
    ,producers.Title as PTitle,invoicemaster.Description,PriceListMasterID FROM invoicemaster 
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
    
    $np = $resquery['Rowcnt'];
    //$np = 35;
    
    if ($np<1) $np=1;
    
    $Serial = $resquery['Serial'];
    $Title = $resquery['Title'];
    $PTitle = $resquery['PTitle'];
    $Description = $resquery['Description'];
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
        //if (time<=0) $("#loading-div-background").show();
    }, 1000);
}


countdown( "intimer", 10, 00 );
//$("#loading-div-background").hide();

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
            <form action="invoicedetail_list2.php" method="post" onSubmit="return CheckForm()"  enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                  
     

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
function CheckForm()
{
 //doing stuff
 
        for (var j=1;j<=(document.getElementById('records').rows.length-8);j++)
        {
            var selectedmarksID=document.getElementById('marksID'+j).value;
            var selectedgadget3ID=document.getElementById('gadget3ID'+j).value;
            
            var selectedgadget2ID=document.getElementById('gadget2ID'+j).value;
            var selectedProducersID=document.getElementById('ProducersID'+j).value;
            
            if (document.getElementById('Number'+j).value>0)
            {
                if ((!(selectedmarksID>0)) || (!(selectedgadget3ID>0)) || (!(selectedProducersID>0)))
                {
                                    
                                    
                    document.getElementById('suggest'+j).value=farsireplace(document.getElementById('suggest'+j).value);
                          
                    var sel = document.getElementById('gadget3ID'+j);
                                
                     
                          
								for(var i1 = 0; i1 < sel.options.length; i1++) 
                                {
									var selv=sel.options[i1].text;
                                    selv=farsireplace(selv);
                                    //alert(1); 
                                    if(selv === document.getElementById('suggest'+j).value) 
                                    {
                                       sel.selectedIndex = i1;
                                       FilterComboboxes(j, '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',3,0);
                                       
                                        
                                        break;
                                    }
                               }     
                                
                }
                                        
                if (!(selectedProducersID>0))
                {
                    alert(' تولید کننده ردیف  '+j+' را مشخص نمایید! ');return false;
                }
                if (!(selectedmarksID>0))
                {
                    alert('مارک ردیف'+j+' را مشخص نمایید! ');return false;
                }
                if (!(selectedgadget3ID>0))
                {
                    alert('عنوان کالای ردیف '+j+' را مشخص نمایید! ');return false;
                }
            }
        }
        
  return true;
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


function FilterComboboxes(rowNumber,Url,Level,Tabindex)
{ 
   //$(\"#loading-div-background\").show();
  
    //alert(1);
    
    var selectedmarksID=document.getElementById('marksID'+rowNumber).value;
    var selectedgadget3ID=document.getElementById('gadget3ID'+rowNumber).value;
    
    var selectedgadget2ID=document.getElementById('gadget2ID'+rowNumber).value;
    var selectedProducersID=document.getElementById('ProducersID'+rowNumber).value;
    //if (selectedmarksID>0 && selectedgadget3ID>0 && selectedgadget2ID>0 && selectedProducersID>0) return;
    
   
     
    var pipestr='$row2[ProducersID]-$row2[PE32app]-$row2[PE40app]-$row2[PE80app]-$row2[PE100app]';
    var InvoiceMasterID=document.getElementById('InvoiceMasterID').value;
           
    var masterProducersID=document.getElementById('masterProducersID').value;
    var login_userid=document.getElementById('login_userid').value;
    var login_RolesID=document.getElementById('login_RolesID').value;
    
    //alert(document.getElementById('ProducersID'+rowNumber).value);
    var PriceListMasterID=document.getElementById('PriceListMasterID').value;
    
    Tabindex=document.getElementById('suggest'+rowNumber).getAttribute('tabindex');
    //alert(selectedgadget3ID);
    //alert(selectedProducersID);
    //alert(selectedgadget3ID);
    //alert(selectedmarksID);
    
    if (!(selectedgadget3ID>0) && (document.getElementById('gadget3ID'+rowNumber).options[1])) 
    document.getElementById('gadget3ID'+rowNumber).options[1].text='';
    
    
    
    var selectstr1='<select  Tabindex=\''+(Tabindex+1)+'\' name=\'ProducersID'+rowNumber+'\' id=\'ProducersID'+rowNumber+'\' onchange = \"FilterComboboxes('+rowNumber+', \'\',0,this.tabIndex);\" ><option> </option>';
    var selectstr2='<select  Tabindex=\''+(Tabindex+1)+'\' name=\'marksID'+rowNumber+'\' id=\'marksID'+rowNumber+'\' onchange = \"FilterComboboxes('+rowNumber+', \'\',0,this.tabIndex);\" ><option> </option>';
    var selectstr3='<select style=\'width:1px\' Tabindex=\''+(Tabindex+1)+'\' name=\'gadget3ID'+rowNumber+'\' id=\'gadget3ID'+rowNumber+'\' onchange = \"FilterComboboxes('+rowNumber+', \'\',0,this.tabIndex);\" ><option> </option>';
    var optionp='';
    var optionm='';
    var optiong='';
    //alert(selectstr1);           
    var pcnt=0;
    var pv1=0;
    var pkey1='';
             
    var mcnt=0;
    var mv1=0;
    var mkey1='';

    var gcnt=0;
    var gv1=0;
    var gkey1='';
    
    for (var i = 0; i < document.getElementById('temppm').length; i++) 
        {
            var str =farsireplace(document.getElementById('temppm').options[i].text);
            var res1 = str.split(\"_\");
            var gadget3ID=res1[0];
            var ProducersID=res1[1];
            var producerstitle=res1[2];
            var MarksID=res1[3];
            var markstitle=res1[4];
            var Price=res1[5];
            var g3title=res1[6];
            var utitle=res1[7];
            if (selectedProducersID>0)
                if (selectedProducersID!=ProducersID) continue;
            if (selectedgadget3ID>0)
                if (selectedgadget3ID!=gadget3ID) continue;
            if (selectedmarksID>0)
                if (selectedmarksID!=MarksID) continue;
            if (masterProducersID!=135)  
                if (masterProducersID!=ProducersID) continue;  
           
           var curoption='';
           /////////////ایجاد کومبوباکس تولید کننده
           if (selectedProducersID==ProducersID)
           {
                curoption='<option  value=\''+ProducersID+'\' selected=\'selected\'> '+producerstitle+' </option>';
           } 
           else
           {
                curoption='<option  value=\''+ProducersID+'\' > '+producerstitle+' </option>';
           } 
           if (!(optionp.indexOf(curoption)>0))
           {
                pcnt++;
                pv1=ProducersID;
                pkey1=producerstitle;
                optionp+=curoption;
           }
           ////////////
           /////////////ایجاد کومبوباکس مارک 
           if (selectedmarksID==MarksID)
           {
                curoption='<option  value=\''+MarksID+'\' selected=\'selected\'> '+markstitle+' </option>';
           } 
           else
           {
                curoption='<option  value=\''+MarksID+'\' > '+markstitle+' </option>';
           } 
           if (!(optionm.indexOf(curoption)>0))
           {
                mcnt++;
                mv1=MarksID;
                mkey1=markstitle;
                optionm+=curoption;
           }
           ////////////
           /////////////ایجاد کومبوباکس ابزار 
           if (selectedgadget3ID==gadget3ID)
           {
                curoption='<option  value=\''+gadget3ID+'\' selected=\'selected\'> '+g3title+' </option>';
           } 
           else
           {
                curoption='<option  value=\''+gadget3ID+'\' > '+g3title+' </option>';
           } 
           if (!(optiong.indexOf(curoption)>0))
           {
                gcnt++;
                gv1=gadget3ID;
                gkey1=g3title;
                optiong+=curoption;
           }
           ////////////                              
        }
        
            if (pcnt==1)
            {
                selectstr1+='<option  value=\'0\'><option  value=\''+pv1+'\' selected=\'selected\' > '+pkey1+' </option>';
                selectedProducersID=pv1;
            }
            else
                selectstr1+=optionp;
                selectstr1+='</select>';
            if (mcnt==1)
            {
                selectstr2+='<option  value=\'0\'><option  value=\''+mv1+'\' selected=\'selected\' > '+mkey1+' </option>';
                selectedmarksID=mv1;
            }
            else
                selectstr2+=optionm;
            selectstr2+='</select>';     
            if (gcnt==1)
            {
                selectstr3+='<option  value=\'0\'><option  value=\''+gv1+'\' selected=\'selected\' > '+gkey1+' </option>';
                selectedgadget3ID=gv1;
            }
            else
                selectstr3+=optiong;
           selectstr3+='</select>'; 
           
           //alert(selectstr1);  
           $('#divProducersID'+rowNumber).html(selectstr1);
           //alert(selectstr2);
           $('#divmarksID'+rowNumber).html(selectstr2);
           $('#divgadget3ID'+rowNumber).html(selectstr3);
           var pricef=0;
           var utitle='';
           if (selectedgadget3ID>0 && selectedProducersID>0 && selectedmarksID>0)
           {
            //alert(1);
                for (var i = 0; i < document.getElementById('temppm').length; i++) 
                {
                    var str =farsireplace(document.getElementById('temppm').options[i].text);
                    var res1 = str.split(\"_\");
                    var gadget3ID=res1[0];
                    var ProducersID=res1[1];
                    var producerstitle=res1[2];
                    var MarksID=res1[3];
                    var markstitle=res1[4];
                    var Price=res1[5];
                    var g3title=res1[6];
                    
                    if (selectedgadget3ID==gadget3ID && selectedProducersID==ProducersID && selectedmarksID==MarksID)
                    {
                        
                        $('#divToolsMarksID'+rowNumber+' input:text ').val(document.getElementById('temppm').options[i].value);
                        //$('#divutitle'+rowNumber+' input:text ').val(data.val5);
                        pricef=Price;
                        utitle=res1[7];
                    }
                }                      
           }
           // alert(1);
           
        
        
   
    /*   
    $.post(Url, {selectedProducersID:selectedProducersID,selectedgadget2ID:selectedgadget2ID,selectedgadget3ID:selectedgadget3ID,
    selectedmarksID:selectedmarksID,Tabindex:Tabindex,rowNumber:rowNumber,masterProducersID:masterProducersID
    ,PriceListMasterID:PriceListMasterID,InvoiceMasterID:InvoiceMasterID,login_userid:login_userid,login_RolesID:login_RolesID,pipestr:pipestr}, 
    function(data)
    {*/
    
        
        ////////////////selectedmarksID=data.val7;
        ////////////////selectedgadget3ID=data.val8;
        ////////////////selectedProducersID=data.val9;
        ////////////////selectedgadget2ID=data.val1
        ////////////////$('#divProducersID'+rowNumber).html(data.val0);
	    ////////////////$('#divgadget2ID'+rowNumber).html(data.val1);
	    ///////////////$('#divmarksID'+rowNumber).html(data.val2);
	    /////////$('#divgadget3ID'+rowNumber).html(data.val3);
	    //$('#divdlist'+rowNumber).html(data.val10);
        //$('#divdlist'+rowNumber).html(data.val10);
        ///////////////////pricef=data.val4;
        $('#divutitle'+rowNumber+' input:text ').val(utitle);
        ///////////////$('#divToolsMarksID'+rowNumber+' input:text ').val(data.val6);
        
        
        
        if ($('#gadget3ID'+rowNumber+' option:selected').text().length>1)
            document.getElementById('suggest'+rowNumber).value=
            document.getElementById('gadget3ID'+rowNumber).options[document.getElementById('gadget3ID'+rowNumber).selectedIndex].text;
        var z = new Array(document.getElementById('gadget3ID'+rowNumber).length);
        for (var i = 0; i < document.getElementById('gadget3ID'+rowNumber).length; i++) 
        {
            //alert(document.getElementById('gadget3ID'+rowNumber).options[i].text);
            document.getElementById('gadget3ID'+rowNumber).options[i].text=
            farsireplace(document.getElementById('gadget3ID'+rowNumber).options[i].text);
            var str = document.getElementById('gadget3ID'+rowNumber).options[i].text;
            z[i] = str;
        }	
        
        
    //alert(10);return;
    
        
        //alert(2);
        $(\"#suggest\"+rowNumber).autocomplete(z, {matchContains: true,minChars: 0});	
       
    
        var x=pricef*$('input[name=\"Number'+rowNumber+'\"]').val();           
        $('#divPrice'+rowNumber+' input:text ').val(numberWithCommas(pricef));
    
    
    
        $('#divPrice'+rowNumber).attr('onmouseover',\"Tip( '\"+numberWithCommas(pricef) +\"')\");
    
    
        $('#divSumPrice'+rowNumber).attr('onmouseover',\"Tip( '\"+(numberWithCommas(x)) +\"')\");
    
        selectedgadget2ID=document.getElementById('gadget2ID'+rowNumber).value;
    //alert(document.getElementById('marksID'+rowNumber).value);return;
        selectedmarksID=document.getElementById('marksID'+rowNumber).value;
    //alert(11);return;
    
    //alert(111);return;
    
        /*return;
        
        if ($('#divProducersID'+rowNumber).is(':focus')) alert(1);
        if (!(selectedProducersID>0)  )
        {
            for (var jx=(document.getElementById('records').rows.length-8);jx>=1;jx--)
            {
                var smarksID=document.getElementById('marksID'+jx).value;
                var sgadget2ID=document.getElementById('gadget2ID'+jx).value;
                var sProducersID=document.getElementById('ProducersID'+jx).value;
                if ((selectedmarksID==smarksID) && (selectedgadget2ID==sgadget2ID) && (sProducersID>0))
                {
                    var fs=0;
                    for (var is = 0; is < document.getElementById('ProducersID'+rowNumber).length; is++) 
                    {
                        if(document.getElementById('ProducersID'+rowNumber).options[is].value==sProducersID)
                            fs=1;
                    }	
                    if (fs==1)
                    {
                        //alert(sProducersID);
                        document.getElementById('ProducersID'+rowNumber).value=sProducersID;
                        FilterComboboxes(rowNumber,Url,Level,Tabindex);
                        break;
                    }
                }
                        
            }
        }*/
           
    //alert(12);return;
    
    
        summ();
        $('#divDescription'+rowNumber).attr('onmouseover',\"Tip( '\"+($('input[name=\"Description'+rowNumber+'\"]').val()) +\"')\");
        $('#divProducersID'+rowNumber).attr('onmouseover',\"Tip( '\"+$('#ProducersID'+rowNumber+' option:selected').text() +\"')\");         
        $('#divgadget2ID'+rowNumber).attr('onmouseover',\"Tip( '\"+$('#gadget2ID'+rowNumber+' option:selected').text() +\"')\");
        $('#divgadget3ID'+rowNumber).attr('onmouseover',\"Tip( '\"+$('#gadget3ID'+rowNumber+' option:selected').text() +\"')\");
        $('#divmarksID'+rowNumber).attr('onmouseover',\"Tip( '\"+$('#marksID'+rowNumber+' option:selected').text() +\"')\");
        whiteelements();
        if (document.getElementById('ProducersID'+rowNumber).value<=0)
        {
            document.getElementById('ProducersID'+rowNumber).focus(); 
            document.getElementById('ProducersID'+rowNumber).style.backgroundColor = 'yellow';
        }
        else if (document.getElementById('marksID'+rowNumber).value<=0)
        {
            //alert(document.getElementById('marksID'+rowNumber).value);
            document.getElementById('marksID'+rowNumber).focus(); 
            document.getElementById('marksID'+rowNumber).style.backgroundColor = 'yellow';
        }
        else
        {
            document.getElementById('Number'+rowNumber).focus();
            document.getElementById('Number'+rowNumber).style.backgroundColor = 'yellow';
        }
        
        
    //////////////////}, 'json');    
        
    //$(\"#loading-div-background\").hide();


    
                               
}

function FilterNextCombobox(rowNumber)
{ 
    summ();
	       var x=parseFloat($('input[name=\"Price'+rowNumber+'\"]').val().replace(/,/g, ''))*$('input[name=\"Number'+rowNumber+'\"]').val()*1;
           $('#divSumPrice'+rowNumber+' input:text ').val(numberWithCommas(x));
           $('#divSumPrice'+rowNumber).attr('onmouseover',\"Tip( '\"+(numberWithCommas(x)) +\"')\");
           x=$('input[name=\"Number'+rowNumber+'\"]').val();  
           $('#divNumber'+rowNumber).attr('onmouseover',\"Tip( '\"+(x) +\"')\");
    
  
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
                    <td colspan="5" align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-family:'B Nazanin';">  پیش فاکتور/لیست لوازم فروش  <?php print $Title; ?></td>
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
                    while(1){
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
                        if ($result)    
                        $row = mysql_fetch_assoc($result);
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
                            $Number = number_format($row['Number'], 0, '', '');
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
                            
                            
                            
                            
                            
                            
                            
                            $query="select gadget3.gadget3ID as _value,
                                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key from gadget3 
                                    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
                                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    left outer join operator on operator.operatorID=gadget3.operatorID
                                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid

                                    where gadget3.gadget3ID='$gadget3ID'
                                    order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title";
                                    $IDgadget3ID = get_key_value_from_query_into_array($query);
                                
                                
                        }
                        else
                        {
                           $IDgadget3ID = $allIDgadget3ID;
                        }
                        
                        if ($ProducersID==0)
                            $ProducersID=$selectedPID ;
                            
                            
                        if ($cnt>=$np) 
                        break;
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
                            
                            <td > <input type="checkbox" name="chk<?php echo $cnt; ?>" value="1"><td >
                            <div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 32px' maxlength="6" readonly /></div></td>
                            <?php
                                
                                
                                
                                $tabindex++;
                                print 
                                "
                                
                                
                                <td class='data'><div id='divtxtlist$cnt'><input type='text' id='suggest$cnt' name='suggest$cnt'
                               
                               onkeydown=\"
                               
                                    document.getElementById('suggest$cnt').value=farsireplace(document.getElementById('suggest$cnt').value);
                                \"
                                 
                                onfocus=\" 
                                whiteelements();
                                document.getElementById('suggest$cnt').style.backgroundColor = 'yellow';
                                
                                if (((document.getElementById('suggest$cnt').value).length==0) && (document.getElementById('gadget3ID$cnt').options.length<=2)
                                
                                && (document.getElementById('gadget3ID$cnt').value==0)) 
                                {
                                    
                                    //alert(1);
                                    
                                    document.getElementById('gadget3ID$cnt').value=0;
                                    
                                    var select1 = document.getElementById('temp');
                                    var select2 = document.getElementById('gadget3ID$cnt');
                                    select2.innerHTML = select2.innerHTML+select1.innerHTML;
                                    var z = new Array(document.getElementById('gadget3ID$cnt').length);
                                    for (var i = 0; i < document.getElementById('gadget3ID$cnt').length; i++) 
                                    {
                                        document.getElementById('gadget3ID$cnt').options[i].text=
                                        farsireplace(document.getElementById('gadget3ID$cnt').options[i].text);
                                        var str = document.getElementById('gadget3ID$cnt').options[i].text;
                                        z[i] = str;
                                    }
                                    var rowNumber='$cnt';	
                                    $('#suggest'+'$cnt').autocomplete(z, {matchContains: true,minChars: 0});
                                    
                                }
                                \"
                                onblur=\"
                                if (document.getElementById('suggest$cnt').value.length==0) return;
                                    whiteelements();
                                    document.getElementById('suggest$cnt').value=farsireplace(document.getElementById('suggest$cnt').value);
                                    var v=document.getElementById('suggest$cnt').value;
                         
                                    var sel = document.getElementById('gadget3ID$cnt');
                                
                                
								for(var i1 = 0; i1 < sel.options.length; i1++) 
                                {
									var selv=sel.options[i1].text; 
                                    
                                    selv=farsireplace(selv);
                                    if(selv === v) 
                                    {
                                       sel.selectedIndex = i1;
                                       FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',3,this.tabIndex);
                                      
                                        break;
                                    }
                               } 
                               //alert('done');
                              
                               
                                \"  
                                
                                
                                 
                                 type='text' class=\"textbox\"  style='width: 100%' tabindex='$tabindex' /></div>"
                                 
                                
                                ."</td>";
                                print select_option('ProducersID'.$cnt,'',',',$IDProducersID,++$tabindex,'','','1','rtl',0,'',$ProducersID,
                                "onchange = \"
                                if (document.getElementById('ProducersID$cnt').value.length<=1)document.getElementById('marksID$cnt').selectedIndex=0;
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',0,this.tabIndex);\"",
                                '100%');

                                //print $query;
                                
                                
                                print select_option('marksID'.$cnt,'',',',$IDmarksID,++$tabindex,'','','1','rtl',0,'',$marksID,
                               "onchange = \"
                                if (document.getElementById('marksID$cnt').value.length<=1)document.getElementById('ProducersID$cnt').selectedIndex=0;
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',2,this.tabIndex);\""
	                            ,'100%');
                                $tabindex++;

	           				  ?>

                            <td class="data"><div id="divutitle<?php echo $cnt; ?>"><input  onmouseover="Tip(<?php echo '(\''.$utitle.'\')'; ?>)" name="utitle<?php echo $cnt; ?>" type="text" class="textbox" id="utitle<?php echo $cnt; ?>" value="<?php echo $utitle; ?>" style='width: 75px'  readonly /></div></td>
                            
                            <td class="data"><div id="divNumber<?php echo $cnt; ?>"><input  
                            
                             onmouseover="Tip(<?php echo '(\''.$Number.'\')'; ?>)" name="Number<?php echo $cnt; ?>" tabindex="<?php echo $tabindex; ?>" type="text" class="textbox" id="Number<?php echo $cnt; ?>" value="<?php echo $Number; ?>" style='width: 75px' maxlength="12"
                            
                            <?php echo 
                            "onchange = \"FilterNextCombobox('$cnt');\"
                             
                             onblur=\" whiteelements();\"
                             onfocus=\"whiteelements();document.getElementById('Number$cnt').style.backgroundColor = 'yellow';\"
                            /></div></td>";
                            
                            if ($blacklist!=1)
                            echo 
                            "<td class='data'><div id='divPrice$cnt'><input  name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price' style='width: 100%' maxlength='12'  readonly /></div></td>
                            <td class='data'><div id='divSumPrice$cnt'><input  name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice' style='width: 194px' readonly /></div>
                            
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptyPrice$cnt'><input  name='EmptyPrice$cnt' type='text' class='textbox' id='EmptyPrice$cnt'  maxlength='12'  readonly /></div></td>
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divEmptySumPrice$cnt'><input  name='EmptySumPrice$cnt' type='text' class='textbox' id='EmptySumPrice$cnt'  readonly /></div>
                            ";
                            else
                            echo 
                            "<td class='data'><div id='divEmptyPrice$cnt'><input  name='EmptyPrice$cnt' type='text' class='textbox' id='EmptyPrice$cnt' style='width: 109px' maxlength='12'  readonly /></div></td>
                            <td class='data'><div id='divEmptySumPrice$cnt'><input  name='EmptySumPrice$cnt' type='text' class='textbox' id='EmptySumPrice$cnt' style='width: 194px' readonly /></div>
                            
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divPrice$cnt'><input  name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price'  maxlength='12'  readonly /></div></td>
                            <td class='data'><div style='width: 1px; visibility: hidden' id='divSumPrice$cnt'><input  name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice'  readonly /></div>
                            ";
                            
                            ?>
                            
                             
                             
                            
                            
                            
                            
                            
                            
                        <?php
                        print select_option('gadget3ID'.$cnt,'',',',$IDgadget3ID,0,'','','1','rtl',0,'',$gadget3ID,
                                "onchange = \"
                                FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',3,this.tabIndex);
                                
                                
                                \"
                                "
	                           ,1,'').
                               
                                select_option('gadget2ID'.$cnt,'',',',$IDgadget2ID,0,'','','1','rtl',0,'',$gadget2ID,
                                "onchange = \"FilterComboboxes('$cnt', '$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicedetail_list_jr.php',1,this.tabIndex);\""
                                ,1)
                               ."</td><td><input name='btn$cnt'  type='button' style='width:20px' id='btn$cnt' value='c' onclick=\"
                               
                               
                                document.getElementById('marksID$cnt').selectedIndex=0;
                                document.getElementById('ProducersID$cnt').selectedIndex=0;
                                document.getElementById('gadget3ID$cnt').selectedIndex=0;
                                document.getElementById('gadget2ID$cnt').selectedIndex=0;
                                document.getElementById('suggest$cnt').value='';
                                document.getElementById('utitle$cnt').value='';
                                document.getElementById('Number$cnt').value='';
                                document.getElementById('Price$cnt').value='';
                                document.getElementById('SumPrice$cnt').value='';
                                
                               
                               \" /></td></tr>";

                    }
                    
                        $querys = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='pricelessinvoice' ";
                        $results = mysql_query($querys);
	                    $rows = mysql_fetch_assoc($results);
                        $pricelessinvoice=$rows['ValueInt'];
                        
                    
                            if ($operatorProducersID==$masterProducersID)
                            {
                                if ($pricelessinvoice==0)
                                    $strgadget3=' gadget3 ';
                                else
                                    $strgadget3="(
                                    select gadget3.* from gadget3 where gadget3.gadget3ID in 
                                    (
                                    select gadget3ID from gadget3synthetic 
                                    where gadget3ID not in (
                                                            select gadget3ID from gadget3synthetic 
                                                            where gadget3syntheticID not in ( 
                                                            select gadget3syntheticID from gadget3synthetic
                                                            inner join toolsmarks on toolsmarks.toolsmarksid=gadget3synthetic.ToolsMarksIDpriceref
                                                            left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                                            inner join pricelistdetail on  pricelistdetail.ToolsMarksID=
                                                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID 
                                                            else toolspref.ToolsMarksIDpriceref end) $hideprice and pricelistdetail.Price>0
                                                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$PriceListMasterID' 
                                                                                            )
                                                           )                     
                                    ) union all
                                    
                                    select * from gadget3 where gadget2id in (202,376,494,495) union all 
                                    select gadget3.* from gadget3
                                    inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id
                                    left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
 
                                    inner join pricelistdetail on pricelistdetail.ToolsMarksID=
                                    (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID 
                                    else toolspref.ToolsMarksIDpriceref end) $hideprice and pricelistdetail.Price>0
                                    inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid 
                                    and pricelistmaster.pricelistmasterid='$PriceListMasterID') gadget3";
                                $query4="
                                        select gadget3.gadget3ID as _value,
                                        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
                                        from $strgadget3
                                        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
                                        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0  $hidegadget3
                                        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
                                        left outer join operator on operator.operatorID=gadget3.operatorID
                                        left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                        left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid

                                        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm 
                                        ";    
                                
                                        //order by gadget2.Title COLLATE utf8_persian_ci,materialtype COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title 
                                        //exit;                            
                            }
                            else
                            {
                                
                             
                                        if ($pricelessinvoice==0)
                                            $strgadget3=' gadget3 ';
                                        else
                                            $strgadget3="(
                                            select gadget3.* from gadget3 
                                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$masterProducersID'
                                            where gadget3.gadget3ID in 
                                            (
                                            select gadget3ID from gadget3synthetic 
                                            where gadget3ID not in (
                                                                    select gadget3ID from gadget3synthetic 
                                                                    where gadget3syntheticID not in ( 
                                                                    select gadget3syntheticID from gadget3synthetic
                                                                    inner join toolsmarks on toolsmarks.toolsmarksid=gadget3synthetic.ToolsMarksIDpriceref 
                                                                    left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                                                    inner join pricelistdetail on   pricelistdetail.ToolsMarksID=
                                                                    (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else 
                                                                    toolspref.ToolsMarksIDpriceref end) $hideprice and pricelistdetail.Price>0
                                                                    inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$PriceListMasterID' 
                                                                                                    )
                                                                   )                     
                                            ) union all
                                    
                                            
                                            select * from gadget3 where gadget2id in (202,376,494,495) union all 
                                            select gadget3.* from gadget3
                                            inner join toolsmarks on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.producersID='$masterProducersID'
                                            left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                                            inner join pricelistdetail on  pricelistdetail.ToolsMarksID=
                                            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID 
                                            else toolspref.ToolsMarksIDpriceref end) $hideprice and pricelistdetail.Price>0
                                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pricelistmasterid='$PriceListMasterID') gadget3
                                            ";
                                            //print $strgadget3;
                                        $query4="select gadget3.gadget3ID as _value,
                                        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key 
                                        from $strgadget3
                                        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
                                        inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0  $hidegadget3
                                        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
                                        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and toolsmarks.producersID='$masterProducersID'
                                        left outer join operator on operator.operatorID=gadget3.operatorID
                                        left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                        left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                                        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,cast(fesharzekhamathajm as decimal),fesharzekhamathajm 
                                         ";
                                //print $query4;
   
                            }
                            $query4pm="
                            select _value,concat(_key,'_',replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) 
                                        ,'_',units.title)_key from (
                            select toolsmarks.ToolsMarksID _value,toolsmarks.gadget3ID,concat(toolsmarks.gadget3ID,'_',toolsmarks.ProducersID,'_',producers.title
                            ,'_',toolsmarks.MarksID,'_',marks.title,'_',
                            case gadget3.gadget2id
                            when 202 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemaster.proposable,0) when 0 then pipepricew.PE80 else pipeprice.PE80
                             end) 
            when 376 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemaster.proposable,0) when 0 then pipepricew.PE100 else pipeprice.PE100 end) 
            when 495 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemaster.proposable,0) when 0 then pipepricew.PE32 else pipeprice.PE32 end) 
            when 494 then ROUND(gadget3.UnitsCoef2*case ifnull(invoicemaster.proposable,0) when 0 then pipepricew.PE40 else pipeprice.PE40 end) end
                            
                            ) _key from toolsmarks
                            inner join producers on producers.ProducersID=toolsmarks.ProducersID
                            inner join marks on marks.marksID=toolsmarks.marksID
                            inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID and gadget3.gadget2id in (202,376,494,495)
            inner join invoicemaster on invoicemaster.invoicemasterID='$InvoiceMasterID'
                            $guerypipeprice
                            left outer join pipeprice pipepricew on pipepricew.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  
            Date<=(select InvoiceDate from invoicemaster where InvoiceMasterID ='$InvoiceMasterID') $condlimited) 
            and pipepricew.ProducersID=toolsmarks.ProducersID
            
                            union all 
                            
                            select toolsmarks.ToolsMarksID _value,toolsmarks.gadget3ID,concat(toolsmarks.gadget3ID,'_',toolsmarks.ProducersID,'_',producers.title,'_',toolsmarks.MarksID,'_',marks.title,'_',pricelistdetail.Price) _key from toolsmarks
                            inner join ($query4) g3 on g3._value=toolsmarks.gadget3ID
                            inner join producers on producers.ProducersID=toolsmarks.ProducersID
                            inner join marks on marks.marksID=toolsmarks.marksID
                            left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
                            inner join pricelistdetail on pricelistdetail.ToolsMarksID=
                                    (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID 
                                    else toolspref.ToolsMarksIDpriceref end) $hideprice and pricelistdetail.Price>0
                            inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid 
                            and pricelistmaster.pricelistmasterid='$PriceListMasterID') v12
                            
                            inner join gadget3 on gadget3.gadget3ID=v12.gadget3ID
                            inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
                            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
                            left outer join operator on operator.operatorID=gadget3.operatorID
                            left outer join spec2 on spec2.spec2id=gadget3.spec2id
                            left outer join spec3 on spec3.spec3id=gadget3.spec3id
                            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                            left outer join units on units.unitsID=gadget3.unitsID            
                                                
                            
                            
                            ";
                            //print $query4pm;exit;
                            $temp = get_key_value_from_query_into_array($query4);
                            $temppm = get_key_value_from_query_into_array($query4pm);
                            
                    $hidden="type='text'";
                    $limited = array("9");
                    if ( in_array($login_RolesID, $limited))
                    $hidden="type='hidden' style='visibility:hidden'";      

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
                     
                     
                      <td colspan='2'></td>
                      <td colspan='2' class='f13_fontb'>مجموع</td>
                      <td colspan='7' class='data'><div id='divAllSum'><input name='AllSum' type='text' class='textbox' id='AllSum' value='".
                      number_format($sum)."' size='33' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div style='visibility: hidden'  id='divEmptyAllSum'>
                      <input name='EmptyAllSum' type='text' class='textbox' id='EmptyAllSum'  size='20' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      
                      <tr>
                       <td colspan='8' 
                       style = \"color: red;border:0px solid black;width: 10%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \"
                       >اسکن باید کاملا خوانا و حداکثر اندازه 200 کیلوبایت باشد. پیش فاکتورهای با اسکن ناخوانا برگشت داده خواهد شد.</td>
                     
                      <td colspan='2' class='f13_fontb'>مالیات بر ارزش افزوده</td>
                      <td colspan='7' class='data'><div id='divTAX'><input name='TAX' type='text' class='textbox' id='TAX' value='".
                      number_format($TAXPercent*$sum/100)."' size='33' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div  style='visibility: hidden' id='divEmptyTAX'>
                      <input name='EmptyTAX' type='text' class='textbox' id='EmptyTAX'  size='20' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      ";
                   else
                    echo "  
                       <tr>
                      <td colspan='8'></td>
                      <td colspan='2' class='f13_fontb'>مجموع</td>
                      <td colspan='8' class='data'><div  id='divEmptyAllSum'>
                      <input name='EmptyAllSum' type='text' class='textbox' id='EmptyAllSum'  size='20' maxlength='20' readonly /></div></td>
                      
                      <td colspan='1' class='data'><div style='visibility: hidden'  id='divAllSum'><input name='AllSum' type='text' class='textbox' 
                      id='AllSum' value='".
                      number_format($sum)."' size='33' maxlength='20' readonly /></div></td>
                      
                      </tr>
                      
                      
                      <tr>
                      <td colspan='8'></td>
                      <td colspan='2' class='f13_fontb'>مالیات بر ارزش افزوده</td>
                      <td colspan='7' class='data'><div   id='divEmptyTAX'>
                      <input name='EmptyTAX' type='text' class='textbox' id='EmptyTAX'  size='20' maxlength='20' readonly /></div></td>
                      <td colspan='1' class='data'><div style='visibility: hidden' id='divTAX'><input name='TAX' type='text' class='textbox' id='TAX' value='".
                      number_format($TAXPercent*$sum/100)."' size='33' maxlength='20' readonly /></div></td>
                      </tr>
                      
                      ";
                      
                    
                    
?>
                      
                      
                      
                      
                      <tr>
                      <td colspan='8'></td>
                      <td colspan='2' class='f13_fontb' <?php echo $hidden; ?>> هزینه های جانبی </td>
                      <td class="data" colspan='6' ><div id="divTransportCost"><input onchange = "summ();" name="TransportCost" <?php echo $hidden; ?> class="textbox" id="TransportCost" value="<?php echo number_format($TransportCost); ?>" size="33" maxlength="20"  /></div></td>
                      </tr>
                      
                      
                      
                      
                      <tr>
                    
                    
                     
                      <td colspan='8'></td>
                      <td colspan='2' class='f13_fontb' <?php echo $hidden; ?>>تخفیف</td>
                      <td class="data" colspan='6' ><div id="divDiscont"><input onchange = "summ();" name="Discont" <?php echo $hidden; ?> class="textbox" id="Discont" value="<?php echo number_format($Discont); ?>" size="33" maxlength="20"  /></div></td>
                      </tr>
                      
                      <tr>
                      
                      <td colspan='3'></td>
                      <td colspan='2'><input name="tempsubmit" type="submit" class="button" id="tempsubmit" value="ثبت "/></td>
                      <td colspan='3'><input name="submit" type="submit" class="button" id="submit" value="ثبت  و خروج"
                            
                       /></td>
                      <?php 
                         if ($blacklist==0)
                    echo "  
                      <td colspan='2' class='f13_fontb'>مبلغ قابل پرداخت</td>
                      <td colspan='7' class='data'><div id='divTotal'><input name='Total' type='text' class='textbox' id='Total' value='".
                      number_format($sum+$TransportCost-$Discont+($TAXPercent*$sum/100))."' size='33'  readonly /></div></td>
                      
                      <td colspan='1' class='data'><div style='visibility: hidden' id='divEmptyTotal'><input name='EmptyTotal' type='text' class='textbox' id='EmptyTotal' size='20'  readonly /></div></td>
                      
                      ";
                   else
                    echo "  
                       <td colspan='2' class='f13_fontb'>مبلغ قابل پرداخت</td>
                      <td colspan='7' class='data'><div  id='divEmptyTotal'><input name='EmptyTotal' type='text' class='textbox' id='EmptyTotal' size='20'  readonly /></div></td>
                      <td colspan='1' class='data'><div style='visibility: hidden' id='divTotal'><input name='Total' type='text' class='textbox' id='Total'
                       value='".
                      number_format($sum+$TransportCost-$Discont+($TAXPercent*$sum/100))."' size='33'  readonly /></div></td>
                      
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
                      
                <tr>
                    <td colspan="10"  style = "color: blue;border:0px solid black;width: 10%;text-align:right;font-size:12.0pt;line-height:125%;font-family:'B Nazanin';">
                    &nbsp;&nbsp;&nbsp;&nbsp;  جهت حذف یک ردیف چک باکس سمت راست ردیف مورد نظر را انتخاب کرده و دکمه ثبت  را کلیک نمایید.</td>
                </tr>
                <tr>
                    <td colspan="10"  style = "color: blue;border:0px solid black;width: 10%;text-align:right;font-size:12.0pt;line-height:125%;font-family:'B Nazanin';">
                    &nbsp;&nbsp;&nbsp;&nbsp;  جهت پاک کردن اطلاعات یک ردیف از دکمه c سمت چپ هر ردیف استفاده نمایید.</td>
                </tr>
                
                      
                    </tfoot>
                    
                </table>
                
                    
                </form>
                
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->
            <?php  print select_option('temp','',',',$temp,0,'','','1','rtl',0,'',0,"",1,'').
            select_option('temppm','',',',$temppm,0,'','','1','rtl',0,'',0,"",1,'');
            
            
            ?>
		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
