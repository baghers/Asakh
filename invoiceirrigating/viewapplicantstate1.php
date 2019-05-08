<?php
/*
viewapplicantstate1.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
include('includes/connect.php');
include('includes/check_user.php');
include_once("class/search.class.php");
$search = new search;
include_once('class/fieldType.class.php');
$fildtyp=new fieldType();
?>
<div id="container">
  <div id="wrapper">
   <?php
  function get_key_value_from_query_into_array($query)//تبدیل نتیجه کوئری به آرایه کلید و مقدارجهت نمایش در کومبوباکس
  {
    $returned_array='';
    $result = mysql_query($query);

	$returned_array[' ']=' ';
    if ($result)
	while($row = mysql_fetch_assoc($result))
      $returned_array[$row['_key']]=$row['_value'];
     return $returned_array;
   }
  ?>
  	<link rel="stylesheet" href="assets/style.css" type="text/css" />
    <script type='text/javascript' src='ajax.js'></script>
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

.f55_font{ text-align:right;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';}

.f9_font{ text-align:right;font-size:9.0pt;line-height:100%;font-family:'B Nazanin';}
  
</style>
  <?php   
     include('includes/top.php');
     include('includes/navigation.php');
     include('includes/header.php');	 
   ?>
     <div id="content">
	 <?php //if ($login_user) { ?>
          <table style="text-align: center;direction:rtl;font-size:18.0pt;font-family:'B Nazanin';width:100%">
				<tr class="no-print">
						<h3 style="font-size:14px; font-family:B Nazanin;text-align: center; ">پيگيري وضعيت طرح</h3>
			
							<div style = "text-align:left;">
					<td>
					
					   <?php
					    echo $fildtyp->SelectDb('year','Value','YearID','year','where Value in(1390,1392,1393,1394,1395)');
					    echo'<input type="checkbox" name="chkstat" id="chkstat" checked >';	
						$tblhead= array("نام خانوادگي","نام","كد رهگيري","نوع سيستم","شرکت مجری","مشاور طرح","مشاور ناظر","وضعیت طرح"
                        ,"آی دی مطالعات","آی دی پیش فاکتور","آی دی صورت وضعیت");
                        $list_fields = array("applicantmaster.ApplicantName","applicantmaster.ApplicantFname","applicantmaster.bankcode"
                        ,"designsystemgroups.title","operatorco.Title",
                        "designerco.title","designercos.Title","applicantstates.title"
                        ,"applicantmaster.ApplicantMasterID"
                        ,"applicantmasterop.ApplicantMasterID"
                        ,"applicantmastersurat.ApplicantMasterID");
                        echo $search->fieldSelect($list_fields,$tblhead);
                        echo $search->whereSelect();
                        echo $search->fieldText('txtsrch1');
                       ?>
					   <input    type="button" class="button" size="14" value="جستجو" onclick="ajaxview2()" />
                    
					 </td>		
					 <td>	
					 			<a style = "text-align:left;" href=<?php print"home.php"; ?>>
                            <img style = "width:34px;" src="img/Return.png" title='بازگشت' ></a>
          			 </td>		
				 
			    </tr>
                
		   </table>
		    
		    <div id="dvdet" style="display:none"></div>
        	<div id="dvlist"></div>
		
  </div>
</div>
