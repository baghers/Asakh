<?php 

/*

insert/sinvoice.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicedetail_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (!$_POST) 
{ 
    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    
    $linearray = explode('_',$ID);
    $appID=$linearray[0];//شناسه طرح
    $appname=$linearray[1];//نام طرح
    $appcity=$linearray[2];//شهر
    $apphektar = $linearray[3];	//مساحت
    $appcode = $linearray[4];//شناسه سند
    $costpricelistmasterID = $linearray[5];//شناسه فهرست بها
    $cityid = $linearray[6];//شناسه شهر
    
    if (!($appID>0))
    {
        
        echo $ID."<br>شناسه طرح یافت نشد.";
        exit;
    }
} 
if ($_POST)
{     
    $appID = $_POST['appID'];
    $appname=$_POST['appname'];
    $appcity=$_POST['appcity'];
    $apphektar = $_POST['apphektar'];	
    $appcode = $_POST['appcode'];
    $costpricelistmasterID = $_POST['costpricelistmasterID'];
    $cityid = $_POST['cityid'];
    $linearray = explode('_',$_POST['allinvoicemasterid']);
    $i=0;
    while($linearray[$i]>0)
    {
        /*
        Description شرح
        permanenttaxless ذف ارزش افزوده
        TransportCost هزینه حمل
        Discont تخفیف
        invoicemaster جدول پیش فاکتورها
        */
        $Description = $_POST["Description".$linearray[$i]]."_".$_POST["Description2".$linearray[$i]]."_".$_POST["Description3".$linearray[$i]];
        $Title = $_POST["Title".$linearray[$i]];
        $Serial = $_POST["Serial".$linearray[$i]];
        
        $upstr="Description='$Description',Title='$Title',Serial='$Serial'";
        if ($_POST["permanenttaxless".$linearray[$i]] && $_POST["taxless".$linearray[$i]]<2)
            $upstr.=",taxless=2";
        
        else if (!($_POST["permanenttaxless".$linearray[$i]]) && $_POST["taxless".$linearray[$i]]==2)
            $upstr.=",taxless=0";
            
        
            
        if (strlen($_POST["TransportCost".$linearray[$i]]) >0)
            $upstr.=",TransportCost='".str_replace(',', '', $_POST["TransportCost".$linearray[$i]])."'";
        
        if (strlen($_POST["Discont".$linearray[$i]]) >0)
            $upstr.=",Discont='".str_replace(',', '', $_POST["Discont".$linearray[$i]])."'";
        
        
        $sql="update invoicemaster set $upstr where applicantmasterID='$appID' and invoicemasterid='$linearray[$i]'";
        if ($upstr!='')
        {
            //print $sql;exit;
            mysql_query($sql);    
        }
        
        //print $sql.$_POST["permanenttaxless".$linearray[$i]]."<br>";
        
        $i++;
    }
    $cnth=1;
    while(isset($_POST['tblid'.$cnth]))
    {
        if ($_POST['TCode'.$cnth]==3)
        {
            //manuallistprice جدول هزینه های اجرایی
            $sql="update manuallistprice set Number='".$_POST['Number'.$cnth]."',Number2='".$_POST['2Number'.$cnth]."',
            SaveTime = '" . date('Y-m-d H:i:s') . "',SaveDate = '" . date('Y-m-d') . "', ClerkID = '$login_userid'
             where applicantmasterID='$appID' and manuallistpriceid='".$_POST['tblid'.$cnth]."'";
             	  	try 
								  {		
									  	mysql_query($sql);		  		
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        }
        else if ($_POST['TCode'.$cnth]==4)
        {
            //manuallistpriceall جدول فهارس بها
            $sql="update manuallistpriceall set Number='".$_POST['Number'.$cnth]."',Number2='".$_POST['2Number'.$cnth]."',
            SaveTime = '" . date('Y-m-d H:i:s') . "',SaveDate = '" . date('Y-m-d') . "', ClerkID = '$login_userid'
             where applicantmasterID='$appID' and manuallistpriceallid='".$_POST['tblid'.$cnth]."'";
                    	  	try 
								  {		
									  	mysql_query($sql);		  		
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
           
        }
        $cnth++;
    }         
    header("Location: summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$appID.'_4_'.rand(10000,99999));
}
$sqlinvoicemaster = "SELECT invoicemasterid, CONCAT(month.Title,'  ',year.Value)PriceListMasterIDtitle ,invoicemaster.Title,invoicemaster.taxless,Serial,InvoiceDate,case invoicemaster.ProducersID when 148 then '' else producers.Title end producersTitle
,tot,Discont,TransportCost,Description,pricenotinrep,costnotinrep,taxless from invoicemaster
inner join producers on producers.ProducersID=invoicemaster.ProducersID
left outer join pricelistmaster on pricelistmaster.PriceListMasterID=invoicemaster.PriceListMasterID
				inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                

 where applicantmasterID='$appID'
 order by serial;
 ";

		  			  	try 
								  {		
									  $resultinvoicemaster = mysql_query($sqlinvoicemaster);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

//print $sqlinvoicemaster;


$fautomatic=0;
$fmandal=0;
$sqlouter=fehrestquery($fautomatic,$fmandal,$appID,$costpricelistmasterID,substr($cityid,0,5),"").$login_limited;//تابع دریافت پرس و جوی هزینه های اجرا
   
		  			  	try 
								  {		
									  	$resultmanual = mysql_query($sqlouter); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }


//print "2";
$sql="";    
?>
<style>
.CSSTable table{ border-collapse: collapse;width:90%;height:auto;margin:10px;font-family:'B Nazanin'; }

.CSSTable tr:nth-child(odd){ background-color:#f5f5f5; }

.CSSTable tr:nth-child(even){ background-color:#ffffff; }

.CSSTable td { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:right;padding:7px;font-size:15px;font-weight:bold;color:#000000;}
.CSSTable td.t2 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:center;padding:4px;font-size:12px;font-weight:bold;color:#000000;}
.CSSTable td.t3 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:right;padding:4px;font-size:12px;font-weight:bold;color:#000000;}

.CSSTable tr:first-child td { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:16px;
	font-weight:bold;color:#000;}

.CSSTable tr:first-child td.t2 { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:14px;
	font-weight:bold;color:#000;}
	
.CSSTable tr:first-child:hover td{background-color:#2cb7b7;}

.CSSTable tr:first-child td:first-child{border-width:0px 0px 1px 0px;}

.CSSTable tr:first-child td:last-child{border-width:0px 0px 1px 1px;}
</style>

<!DOCTYPE html>
<html>
<head>
  	<title>مشخصات پروژه</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />

<script>
    function numberWithoutCommas(x) {
    var number = x.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
    return number;    
 }
function numberWithCommas(x) {
    /*if (x.substr(0, 1)=='-')
    {
        x = x.replace('/-','/');
        
        return '-'+x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
    }*/
    
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
function sum(id)
{
    
    //alert((1*1+document.getElementById('TAXPercent'+id).value/100));
    document.getElementById('tot'+id).value=numberWithCommas(Math.round(numberWithoutCommas(document.getElementById('maintot'+id).value)*1*
    (1*1+document.getElementById('TAXPercent'+id).value/100)*1+
    numberWithoutCommas(document.getElementById('TransportCost'+id).value)*1-numberWithoutCommas(document.getElementById('Discont'+id).value)));
    
    //alert(2);
}
function sumprice(id)
{
    
    document.getElementById('Total'+id).value=numberWithCommas(
    Math.round(numberWithoutCommas(document.getElementById('Price'+id).value)*document.getElementById('Number'+id).value*
    document.getElementById('2Number'+id).value)
    );    
}

function convert(aa) {
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);   
    }
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
			  <div class='CSSTable'>
            <form action="sinvoice.php" method="post" enctype="multipart/form-data">
				<table id="recordtable" width="99%" align="center" class='CSSTable'>
                   <tbody>           
               

                   <?php         
                   
                   
			
                      echo "
                      <div class='CSSTable' id='dvlist' >
                      
                      <table id='dvdet' class='CSSTable'>
    	           
                    <tr><td colspan=13>  طرح :   $appname ، شهرستان  $appcity ،  مساحت  $apphektar هكتار ،     کد رهگیری  $appcode </td></tr>
                   ";
                   
                   if (mysql_num_rows($resultinvoicemaster)>0)
                   {
                        echo "
                        <table id='dvdet' class='CSSTable'>
                            <tr><td colspan=12>  لیست لوازم/ پیش فاکتور ها </td></tr>
                            <tr>
            						<td></td>
            						 
            					    <td>تولید کننده/فروشنده</td>
            						 <td>تاریخ / شماره</td> 
            					    <td>لیست قیمت</td> 
                                    <td>مبلغ خالص</td> 
                                    <td  >هزینه حمل</td>
                                    <td  >تخفیف/تعدیل</td>
                                    <td> ارزش افزوده</td>
                                    <td>مبلغ کل</td>
                                    <td>توضیحات</td>
            		               <td colspan=2>عملیات متقاضی</td>
            		               
            				</tr>
                            
                            ";
                            $cnt=0;
                            $allinvoicemasterid="";
                        while($resinvoicemaster = mysql_fetch_assoc($resultinvoicemaster))
                        {
                                $linearray = explode('_',$resinvoicemaster['Description']);
                                $Description=$linearray[0];
                                $Description2=$linearray[1];
                                $Description3=$linearray[2];
                                
                            $TAXPercent=0;
                            if (strlen($resinvoicemaster['InvoiceDate'])>0 && $resinvoicemaster['taxless']==0)
                            {
                                $InvoiceYear = substr($resinvoicemaster['InvoiceDate'],0,4);
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
                            
                            $allinvoicemasterid.=$resinvoicemaster['invoicemasterid']."_";
                            $cnt++;
                            if ($resinvoicemaster['taxless'])
                                $taxtitle="";
                            else  
                                $taxtitle="+";   
                                
                            if ($resinvoicemaster['pricenotinrep'])
                                {$toolsdone="لوازم با هزینه شخصی متقاضی"; $pricenotinrepselected="checked";}
                            else  
                                {$toolsdone=""; $pricenotinrepselected="";}
                            
                            if ($resinvoicemaster['costnotinrep'])
                                {$costdone="اجرا شده توسط متقاضی"; $costnotinrepselected="checked";}
                            else  
                                {$costdone=""; $costnotinrepselected="";        }
                            
                            if ($resinvoicemaster['taxless']==2)
                                $permanenttaxless="checked";
                            else  
                                $permanenttaxless=""; 
                            
                        if ($TAXPercent>0)
                           $maintot=round(($resinvoicemaster['tot']-$resinvoicemaster['TransportCost']+$resinvoicemaster['Discont'])/(1+$TAXPercent/100)); 
                           else
                           $maintot=$resinvoicemaster['tot'];    
                                    
				 $afzude=round($maintot*($TAXPercent/100));
                 
                            echo "  <tr>
            						<td>$cnt)  </td>
            						<td>
										$resinvoicemaster[producersTitle]
									    <input  
										value='$resinvoicemaster[Title]'
										name='Title$resinvoicemaster[invoicemasterid]' type='text' 
										class='textbox' id='Title$resinvoicemaster[invoicemasterid]'/>
                                	</td>
            						
									 <td>$resinvoicemaster[InvoiceDate] 
                                   
										<input  
										value='$resinvoicemaster[Serial]' size=1
										name='Serial$resinvoicemaster[invoicemasterid]' type='text' 
										class='textbox' id='Serial$resinvoicemaster[invoicemasterid]'/>
							        </td> 
            					    
                                    
                                	<td>	
										<font color='#99CCFF'> $resinvoicemaster[PriceListMasterIDtitle] </font>
									</td>						   
            				        
                                    
                                    <td >
                                    <input type='text' readonly style = 'background-color: #f2f2f2;width:95px;' id='maintot$resinvoicemaster[invoicemasterid]' name='maintot$resinvoicemaster[invoicemasterid]' 
                                    value ='".number_format($maintot)."'> <font size='1' color='#f2f2f2'>ریال</font></td >
                               
								    <td ><input style = 'width:85px;' onKeyUp=\"convert('TransportCost$resinvoicemaster[invoicemasterid]');sum('$resinvoicemaster[invoicemasterid]');\"  
                                    value='".number_format($resinvoicemaster['TransportCost'])."'
                                    name='TransportCost$resinvoicemaster[invoicemasterid]' type='text' class='textbox' 
                                    id='TransportCost$resinvoicemaster[invoicemasterid]'/>
                                    
                                    
                                    
                                   
                                    <input  
                                    value='$Description2' placeholder=' بابت'
                                    name='Description2$resinvoicemaster[invoicemasterid]' type='text' 
                                    class='textbox' id='Description2$resinvoicemaster[invoicemasterid]'/>
                                    
                                    </td>
                                    
                                    
                                    
                                    
                                    <td ><input style = 'width:85px;' onKeyUp=\"convert('Discont$resinvoicemaster[invoicemasterid]');sum('$resinvoicemaster[invoicemasterid]');\"  
                                    value='".number_format($resinvoicemaster['Discont'])."'
                                    name='Discont$resinvoicemaster[invoicemasterid]' type='text' class='textbox' id='Discont$resinvoicemaster[invoicemasterid]'/>
                                    
                                    <input  
                                    value='$Description3' placeholder=' بابت'
                                    name='Description3$resinvoicemaster[invoicemasterid]' type='text' 
                                    class='textbox' id='Description3$resinvoicemaster[invoicemasterid]'/>
                                    </td>
                                    
                                    
                      <td class='data'>
					  <input readonly style = 'background-color: #f2f2f2;width:100px;'  
                                    value='".number_format($afzude)."'
                                    name='tot$resinvoicemaster[invoicemasterid]' type='text' class='textbox' id='tot$resinvoicemaster[invoicemasterid]'/>
					  
					  <input name='permanenttaxless$resinvoicemaster[invoicemasterid]' type='checkbox' 
                      id='permanenttaxless$resinvoicemaster[invoicemasterid]'  value='1' $permanenttaxless /><font size='1' color='#ff0000'> حذف</font></td>
                      
                                    
                     </td>
                             <td ><input readonly style = 'background-color: #f2f2f2;width:100px;'  
                                    value='".number_format($resinvoicemaster['tot'])."'
                                    name='tot$resinvoicemaster[invoicemasterid]' type='text' class='textbox' id='tot$resinvoicemaster[invoicemasterid]'/>
                                    
                                    
                                    <input type='hidden' id='TAXPercent$resinvoicemaster[invoicemasterid]' name='TAXPercent$resinvoicemaster[invoicemasterid]' value ='$TAXPercent'>
                                    $taxtitle</td>
                                    
                       
                     
                                    <td ><input  
                                    value='$Description'
                                    name='Description$resinvoicemaster[invoicemasterid]' type='text' 
                                    class='textbox' id='Description$resinvoicemaster[invoicemasterid]'/>
                                    $toolsdone ،$costdone</td> 
                                    
              <td class='data'><input name='pricenotinrep$resinvoicemaster[invoicemasterid]' type='checkbox' 
              id='pricenotinrep$resinvoicemaster[invoicemasterid]'  value='1' $pricenotinrepselected /><font size='1' color='#ff0000'>خرید</font></td>
              <td class='data'><input name='costnotinrep$resinvoicemaster[invoicemasterid]' type='checkbox' 
               id='costnotinrep$resinvoicemaster[invoicemasterid]'  value='1' $costnotinrepselected /><font size='1' color='#ff0000'>اجرا</font></td>
                              
                                    
                             
                                    
                      <td class='data'><input name='taxless$resinvoicemaster[invoicemasterid]' type='hidden' 
                      id='taxless$resinvoicemaster[invoicemasterid]'  value='$resinvoicemaster[taxless]'  /></td>
                      
            						</tr>";
                        }    
                        echo "</table>";    
                   } 
                   
                   
                   
                   if (mysql_num_rows($resultmanual)>0)
                   {
                        echo "<table id='dvdet' class='CSSTable'>
                            <tr><td colspan=11>  فهرست بهای دستی/ سایر فهارس بها </td></tr>";
                            
                        $cnt=0;
                        echo "<tr>
            						<td></td>
            						<td>فصل</td>
            						<td>کد</td>
            						<td>شرح</td>
            						<td>مقدار جزء</td> 
            						<td>تعداد جزء</td> 
            					    <td>بهاء(ریال)</td> 
                                    <td>بهای کل(ریال)</td>
            				</tr>";
                        while($resmanual = mysql_fetch_assoc($resultmanual))
                        {
                            if ($resmanual['Total']>0)
                            {
                                $cnt++;
                                echo "  <tr>
            						<td>$cnt) 
                                    <input type='hidden' id='tblid$cnt' name='tblid$cnt' value ='$resmanual[tblid]'>
                                    <input type='hidden' id='TCode$cnt' name='TCode$cnt' value ='$resmanual[TCode]'>
                                     </td>
            						<td>$resinvoicemaster[ToolsGroupsCode]  </td>
            					    <td>$resmanual[Code]</td>
            						<td>$resmanual[Title] </td> 
            				        <td ><input   onKeyUp=\"sumprice('$cnt');\"  
                                    value='".($resmanual['Number'])."' 
                                    name='Number$cnt' type='text' class='textbox' id='Number$cnt'/></td>
                                    
                                    <td ><input onKeyUp=\"sumprice('$cnt');\"  
                                    value='".($resmanual['Number2'])."'
                                    name='2Number$cnt' type='text' class='textbox' id='2Number$cnt'/></td>
                                    
                                    
                             <td ><input readonly style = 'background-color: #f2f2f2;'  
                                    value='".number_format($resmanual['Price'])."'
                                    name='Price$cnt' type='text' class='textbox' id='Price$cnt'/>
                                    </td>
                            
                             <td ><input readonly style = 'background-color: #f2f2f2;' 
                                    value='".number_format($resmanual['Total'])."'
                                    name='Total$cnt' type='text' class='textbox' id='Total$cnt'/>
                                    </td>
                                            
                                    
            						</tr>";    
                            }
                            
                        }
                         echo "</table>";     
                   }
                   
                   echo " 
                    
                    <tr>
                      <td colspan='8'>
                      <input type='hidden' id='appID' name='appID' value ='$appID'>
                      <input type='hidden' id='appname'  name='appname' value ='$appname'>
                      <input type='hidden' id='appcity' name='appcity' value ='$appcity'>
                      <input type='hidden' id='apphektar' name='apphektar' value ='$apphektar'>
                      <input type='hidden' id='appcode' name='appcode' value ='$appcode'>
                      <input type='hidden' id='allinvoicemasterid' name='allinvoicemasterid' value ='$allinvoicemasterid'>
                      <input type='hidden' id='costpricelistmasterID' name='costpricelistmasterID' value ='$costpricelistmasterID'>
                      <input type='hidden' id='cityid' name='cityid' value ='$cityid'>
                      <input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                      
                      
                      
                      </tr>
                    </table>
                      </div>
                      "; 
                      
                      
    
                      ?>
                      
                        	
                 </tbody>
                   
                </table>
                </form> 
				
            </div>
			<!-- /content -->

		</div>
		

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
