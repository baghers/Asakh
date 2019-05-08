<?php

/*

insert/producerinvoicemaster_list2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/producerinvoicemaster_list2.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='producerinvoicemaster';
$tblname='primaryinvoicemaster';//پیش فاکتور صادره تولید کننده

if ($login_Permission_granted==0) header("Location: ../login.php");
//if ($login_RolesID==3 && ($login_username!='namadtest')) header("Location: ../login.php");

 
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
//----------

    
if ($login_ProducersID>0) $condition1=" where ProducersID='$login_ProducersID'";
/*
primaryinvoicemaster  پیش فاکتور صادره تولید کننده
operatorco مجری
operatorcoID شناسه مجری
*/
        
$sql = "
SELECT $tblname.*,$tblname.primaryinvoicemasterID,operatorco.Title as OTitle
FROM $tblname 
left outer join operatorco on operatorco.operatorcoID=$tblname.operatorcoID
$condition1
ORDER BY $tblname.invoicedate DESC ;";


 	  			  	try 
								  {		
									   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }



        
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست سفارشات</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
    
 
    

    <script>
    
    
    function fillform(txturl)
    {
        var selectedIID=document.getElementById('allproducerinvoicemasterID').value;
        var selectedAID=document.getElementById('txtproducersID').value;
        var selectedCID=document.getElementById('txtuserid').value;
    
    //alert(selectedCID);
        
        
            $.post(txturl,{selectedIID:selectedIID, selectedAID:selectedAID,selectedCID:selectedCID},function(data){  
           if (data.error>0) 
            alert( "خطا در ثبت" ); 
            
           else alert( "ثبت انجام شد" );
        
        
       }, 'json');

        alert( "" );
        location.reload();
        
                       
    }
                            
            
    $(function() {
                $("#txtinvoicedatenew, #simpleLabel").persiandatepicker();   
				
            });
            
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
function add() {
 
    var myDiv = document.getElementById("mydiv");
    var currindex = myDiv.children.length;
        
    if (currindex>=6) return;
    
    var element1 = document.createElement("input");
    
    
    var element2 = document.createElement("input");
    var element3 = document.createElement("input");
    var element4 = document.createElement("input");
    
    var element5 = document.createElement("input");
    var element6 = document.createElement("input");
 
    element1.setAttribute("name", "txtCodeTarh");
    
 
    
    element1.setAttribute("value", document.getElementById("txtmaxSerial").value);
    element3.setAttribute("value", document.getElementById("txtinvoicedate").value);
    

    
    element1.setAttribute("size", "4");
 
 
    element2.setAttribute("name", "txtYear");
    element2.style.width = '200px';
    
	element3.style.width = '100px';
    
    element4.style.width = '100px';
    
    element5.setAttribute("name", "txtMoteghazi");
    element5.setAttribute("size", "50");
    
    
    element6.type = "button";
    element6.value = "درج"; // Really? You want the default value to be the type string?
    element6.name = "button";  // And the name too?
 
 
    element6.onclick = function() 
    { // Note this is a function
    
        //var searchEles = document.getElementById("myDiv").children;
        var buttonid=this.id;
        var myDiv = document.getElementById("mydiv");
        var searchEles = myDiv.children;
        
        $Rowcnt = searchEles[buttonid-3].value;
        if ($Rowcnt>25)
        {
            alert("تعداد ردیف های پیش فاکتور/لیست لوازم حد اکثر 25 ردیف می باشد");
            return;
        }
          
          var in1=searchEles[buttonid-8].value;
          var in2=searchEles[buttonid-7].value;
          var in3=searchEles[buttonid-6].options[searchEles[buttonid-6].selectedIndex].value;
          var in9=searchEles[buttonid-5].options[searchEles[buttonid-5].selectedIndex].value;
          var in4=searchEles[buttonid-4].value;
          var in5=searchEles[buttonid-3].value;
          var in6=searchEles[buttonid-2].value;
        var txturl = document.getElementById("txturl").value;
       
       
     var in7=document.getElementById("txtuserid").value;
     var in8=document.getElementById("txtproducersID").value;
      
      //alert(in9);
      
      $("#loading-div-background").show();
        $.post(txturl, { in1: in1, in2: in2,in3: in3,in4: in4,in5: in5,in6: in6,in7: in7,in8: in8,in9: in9 } ,function(data){          
           
            location.reload();
           if (data.error>0) 
            alert( "خطا در ثبت" ); 
            
           else alert( "ثبت انجام شد" );
         $("#loading-div-background").hide(); 
            
       }, 'json');

        
        
     
    };
    
    
    

//Create and append select list
var selectList = document.createElement("select");
for (var i = 0; i < document.getElementById("operatorcoID").length; i++) {
    var option = document.createElement("option");
    option.value = document.getElementById("operatorcoID").options[i].value;
    option.text = document.getElementById("operatorcoID").options[i].text;
    selectList.appendChild(option);
}
if (document.getElementById("operatorcoID").length==2)
{
    
    selectList.selectedIndex=1;
    selectList.style.visibility='hidden';
    $('#resceiverlbl').hide();
    
selectList.style.width = '1px';
}
else selectList.style.width = '100px';

var selectList2 = document.createElement("select");
for (var i = 0; i < document.getElementById("PriceListMasterID").length; i++) {
    var option = document.createElement("option");
    option.value = document.getElementById("PriceListMasterID").options[i].value;
    option.text = document.getElementById("PriceListMasterID").options[i].text;
    selectList2.appendChild(option);
}
if (document.getElementById("PriceListMasterID").length==2)
{
    selectList2.selectedIndex=1;  
    selectList2.style.visibility='hidden';
    selectList2.style.width = '1px';
}
else selectList2.style.width = '70px';



element1.id = currindex+1;currindex=currindex+1;
element2.id =  currindex+1;currindex=currindex+1;
selectList.id = currindex+1;currindex=currindex+1;
selectList2.id = currindex+1;currindex=currindex+1;
element3.id = currindex+1;currindex=currindex+1;
element4.id =  currindex+1;currindex=currindex+1;
element5.id =  currindex+1;currindex=currindex+1;
element6.id =  currindex+1;currindex=currindex+1;
    
    
    element6.style.width = '53px';
    element6.style.height = '28px';
    element5.style.height = '28px';
    element4.style.height = '28px';
    element3.style.height = '28px';
    element2.style.height = '28px';
    element1.style.height = '28px';
    selectList.style.height = '28px';
    selectList2.style.height = '28px';
    
    myDiv.appendChild(element1);
    myDiv.appendChild(element2);
    myDiv.appendChild(selectList);
    myDiv.appendChild(selectList2);
    myDiv.appendChild(element3);
    myDiv.appendChild(element4);
    myDiv.appendChild(element5);
    myDiv.appendChild(element6);
    
    element2.focus();
    

    
}

    </script>
	<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

  
</style>

    <!-- /scripts -->
</head>
<body onload="add();">

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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                <table width="95%" align="center">
                    <tbody>
					
                        <td colspan="5">
					
                            <h1 align="center">  لیست سفارش کالا</h1>
                        </td>
                        <td></td>
						    <td><a target='_blank' href=<?php print "producer_notapprovedinvoice_help.htm"; ?>> <img style = 'width: 30px;' src='../img/menu/help.png' title=' راهنما '></a></td>

                            <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxSerial; ?>"/>
                            <INPUT type="hidden" id="txtinvoicedate" value="<?php print gregorian_to_jalali(date('Y-m-d')); ?>"/>
                            <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                            <INPUT type="hidden" id="txtproducersID" value="<?php print $login_ProducersID; ?>"/>
                            <INPUT type="hidden" id="txtlogin_username" value="<?php print $login_username; ?>"/>
                        
					       
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicemaster_list_jr.php"; ?>"/>
						   
                            <div style = "text-align:left;">
                            <!-- button title='پیش فاکتور جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button --> 
                                                      </div>
                            
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

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                    
                        <?php if($login_userid<>28) { $hide='display:none'; $disabled='disabled';}
                        
                        if ($login_ProducersID>0) $condition1=" where ProducersID='$login_ProducersID'";
                        
                        $query = "
                    SELECT CONCAT(Title,'-',InvoiceDate) _key,primaryinvoicemasterID _value
                    FROM primaryinvoicemaster 
                    $condition1 order by _key  COLLATE utf8_persian_ci";
                    $allprimaryinvoicemasterID = get_key_value_from_query_into_array($query);
                    
                    print "<tr><td></td><td style =$hide 'text-align:left;'>سفارش کالا</td>
                    ".select_option('allproducerinvoicemasterID','',',',$allprimaryinvoicemasterID,'','',$disabled,'3')."
                    <td style=$hide><input type='button' value='کپی' onclick=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/producerinvoicemaster_list_jr.php');\"></td>
                    </tr>"; 
                    
					 ?>
					    <tr>
                        	<td>سریال</td>
							<td>    عنوان سبد کالا/خدمات</td>
							<td>تاریخ </td>
							<td>تعداد ردیف</td>
							<td>توضیحات</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					  			
                    
                    
                    </thead>
					  <?php if ($login_userid==28)
                     { ?>
                  
					
                    <thead>
                        <tr>
                        	<th colspan="9"><div id="mydiv" >  </div></th>
                        </tr>
						
                    </thead> 
					<tr><?php echo '<br><br><br><br>' ?> </tr>
                  	
                   <tbody><?php } 
                    
                    
                    
                    while($row = mysql_fetch_assoc($result)){

                        $Serial = $row['Serial'];
                        $ID = $row['primaryinvoicemasterID'];
                        $Title = $row['Title'];
                        $OTitle = $row['OTitle'];
                        $Description = $row['Description'];
                        $Rowcnt = $row['Rowcnt'];
                        $InvoiceDate = $row['InvoiceDate'];

?>
                        <tr>
                        
                            <td class="f10_font"  style="color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" >
							<?php echo $Serial ?></td>
                            <td class="f10_font"  style="color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" ><?php echo $Title; ?></td>
                            <td style="<?php if ($login_userid==28) echo "display:none" ?>"><?php  echo $OTitle; ?></td>
                            <td class="f10_font"  style="color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" ><?php echo $InvoiceDate; ?></td>
                            <td class="f10_font"  style="color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" ><?php echo $Rowcnt; ?></td>
                            <td class="f10_font"  style="color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" ><?php echo $Description; ?></td>
                            <td></td>
                            
                         <td><a target='_blank' href=<?php print "producer_notapprovedinvoice_detail.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_3'.rand(10000,99999); ?>>
                            <img style = 'width: 50px;' src='../img/payment.jpg' title=' پرداخت '></a></td>
                   
                          <td style="<?php echo $hide ?>"><a target='_blank' href=<?php print "producer_notapprovedinvoice_detail.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_1'.rand(10000,99999); ?>>
                            <img style = 'width: 50px;' src='../img/print.png' title=' چاپ '></a></td>
                   				   
                         <td><a href=<?php print "producerinvoicedetail_list2.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 50px;' src='../img/search.png' title=' ریز اقلام پیش فاکتور/لیست لوازم '></a></td>
                         
						 
						 <td style="<?php echo $hide ?>"><a href=<?php print $formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 50px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                         <td style="<?php echo $hide ?>"><a href=<?php print $formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 50px;' src='../img/delete.png' title='حذف'> </a></td>
                        
						</tr><?php

                    }

?>
                    </tbody>
                    
                      
                </table>
                
                <div style='visibility: hidden'>
                          <?php
                          if ($login_username=='namadtest' || $login_userid==28)
                       $query='select operatorcoID as _value,Title as _key from operatorco where operatorcoID=1 order by Title  COLLATE utf8_persian_ci';
                       else 
                       $query='select operatorcoID as _value,Title as _key from operatorco order by Title   COLLATE utf8_persian_ci';
                     $ID = get_key_value_from_query_into_array($query);
                     print select_option('operatorcoID','',',',$ID,0,'','','1','rtl',0,'');
                  
                  
                    
                          if ($login_username=='namadtest'  || $login_userid==28)
                       $query="SELECT PriceListMasterID as _value,
                             CONCAT(CONCAT(year.Value,' '),month.Title) as _key FROM `pricelistmaster` 
                             inner join year on year.YearID=pricelistmaster.YearID
                             inner join month on month.MonthID=pricelistmaster.MonthID
                             where PriceListMasterID='19' ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                       else 
                    $query="SELECT PriceListMasterID as _value,
                             CONCAT(CONCAT(year.Value,' '),month.Title) as _key FROM `pricelistmaster` 
                             inner join year on year.YearID=pricelistmaster.YearID
                             inner join month on month.MonthID=pricelistmaster.MonthID
                             where pfp=1 ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                     $ID = get_key_value_from_query_into_array($query);
                     print select_option('PriceListMasterID','',',',$ID,0,'','','1','rtl',0,'',$PriceListMasterID);
                             
					  ?>
                      </div>
                      
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
