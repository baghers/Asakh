<?php

/*

insert/invoicemaster_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/foundation_list.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php



$formname='invoicemaster';
$tblname='invoicemaster';//عنوان پیش فاکتور

if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
//----------

 $file = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
 $linearray = explode('_',$file);
 $id=$linearray[0];
 $No=$linearray[1];
 $ST=$linearray[2];

 $tekrar=substr(time(),9,1);
 if ($login_opDisabled==11 && $ST==23) //وضعیت 23
{ 
if ($tekrar<8)//تعداد خطاها
	header("Location: $_server_httptype://$_SERVER[HTTP_HOST]/invoiceirrigating/insert/invoice_list.php");
}

/*
proposable ارسال شده به پیشنهاد قیمت
proposestatep وضعیت پیشنهاد
ApplicantMasterIDmaster شناسه طرح اجرایی
ApplicantName عنوان پروژه
ApplicantMasterID شناسه طرح
operatorcoid شناسه پیمانکار
*/ 
$sql = "

SELECT case max(proposable)=1 and ifnull(proposestatep,0)<3 and ifnull(ApplicantMasterIDmaster,0)=0 when 1 then 1 else 0 end inpproposing
,ApplicantName,invoicemaster.ApplicantMasterID,applicantmaster.operatorcoid FROM `invoicemaster`
inner join applicantmaster on applicantmaster.ApplicantMasterID='$id'
 where invoicemaster.ApplicantMasterID='$id'
 group by ApplicantName,ApplicantMasterID,proposestatep";
//print $sql;
$count = mysql_fetch_assoc(mysql_query($sql));
$ApplicantName = $count['ApplicantName'];
$inpproposing=$count['inpproposing']; 
	if ($login_RolesID==1) 
	{
		$login_OperatorCoID=$count['operatorcoid'];
	}

       
$query = "SELECT max(CAST(Serial AS UNSIGNED))+1 maxSerial FROM invoicemaster WHERE ApplicantMasterID = '" . $id . "'";

	
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

        if ($row['maxSerial']>0)
		  $maxSerial = $row['maxSerial'];
        else $maxSerial=1;
     
/*
invoicemaster جدول  لیست پیش فاکتورها
gadget1 جدول  سطح 1 ابزار
appsubprj جدول  زیر پروژه ها
producers جدول  تولید کننده
producerapprequest جدول  پیشنهاد قیمت
pricelistmaster جدول  لیست قیمت
year جدول  سال
month جدول  ماه
*/        
$sql = " 
SELECT distinct concat(year.Value ,' ',month.Title) fb,invoicemaster.InvoiceMasterIDmaster, invoicemaster.Serial
,proposable,invoicemaster.Rowcnt,invoicemaster.InvoiceDate,invoicemaster.Title
,invoicemaster.Description,appsubprj.Title appsubprjTitle,gadget1.title gadget1title
,producerapprequest.ApplicantMasterID ApplicantMasterIDp,invoicemaster.invoicemasterID,producers.Title as PTitle
,producers.pipeproducer

FROM invoicemaster 
left outer join gadget1 on gadget1.gadget1id=SUBSTRING_INDEX(SUBSTRING_INDEX(invoicemaster.Description, '_', 4), '_', -1)
left outer join appsubprj on appsubprj.appsubprjID=invoicemaster.appsubprjID
left outer join producers on producers.ProducersID=invoicemaster.ProducersID
left outer join producerapprequest on producerapprequest.ApplicantMasterID='$id'
left outer join pricelistmaster on pricelistmaster.pricelistmasterid=invoicemaster.pricelistmasterid
left outer join year on year.YearID=pricelistmaster.YearID
left outer join month on month.MonthID=pricelistmaster.MonthID
        
where  invoicemaster.ApplicantMasterID = '$id' and invoicemaster.ApplicantMasterID>0
ORDER BY cast(invoicemaster.Serial as decimal) ;";

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



        
?>
<!DOCTYPE html>
<html>
<head>
  	<title>ليست پيش فاکتورهاي طرح</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
    
 
    

    <script>
    
    
    function fillform(txturl)
    {
        //alert(txturl);
        var selectedIID=document.getElementById('allinvoicemasterID').value;
        var selectedAID=document.getElementById('txtApplicantMasterID').value;
        var selectedCID=document.getElementById('txtuserid').value;
        var selectedRol=document.getElementById('txtrole').value;
		if (selectedRol=1)
		{
			alert(selectedRol); 
			$.post(txturl,{selectedIID:selectedIID, selectedAID:selectedAID,selectedCID:selectedCID},function(data){  
			if (data.error>0) 
				alert( "خطا در ثبت" ); 
			else alert( "ثبت انجام شد" );
		   }, 'json');
		}
		else 
			   alert( "خطا در ثبت" ); 
	
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
    
    if (document.getElementById("txtinvoicedate").value.length<10)
    {
        alert("تاریخ پیش فاکتور معتبر نمی باشد.");
        return;
    }
    
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
    element2.style.width = '150px';
	//element2.setAttribute("size", "150");
    element3.style.width = '60px';
    element4.style.width = '40px';
    element5.setAttribute("name", "txtMoteghazi");
    element5.setAttribute("size", "43");
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
        if ($Rowcnt>35 || $Rowcnt<=0)
        {
            alert("تعداد ردف پیش فاکتور نا معتبر می باشد. تعداد ردیف های پیش فاکتور/لیست لوازم حد اکثر 35 ردیف می باشد");
            return;
        }
          var in1=searchEles[buttonid-10].value;
          var in2=searchEles[buttonid-9].value;
          var in3=searchEles[buttonid-8].options[searchEles[buttonid-8].selectedIndex].value;
          if (searchEles[buttonid-7].selectedIndex>0)
          var selectedpl=searchEles[buttonid-7].options[searchEles[buttonid-7].selectedIndex].value;
          
          if (searchEles[buttonid-6].selectedIndex>0)
          var selectedp2=searchEles[buttonid-6].options[searchEles[buttonid-6].selectedIndex].value;
          
          if (searchEles[buttonid-5].selectedIndex>0)
          var selectedp3=searchEles[buttonid-5].options[searchEles[buttonid-5].selectedIndex].value;
          
          var in4=searchEles[buttonid-4].value;
          var in5=searchEles[buttonid-3].value;
          var in6=searchEles[buttonid-2].value;
		  var txturl = document.getElementById("txturl").value;
       
       if (!(in3>0))
       {
            alert("لطفا صادر کننده را انتخاب نمایید.");
            return;
       }
       
     var in7=document.getElementById("txtuserid").value;
     var in8=document.getElementById("txtApplicantMasterID").value;
     var selectedlogin_OperatorCoID=document.getElementById('txtlogin_OperatorCoID').value;
      
        $.post(txturl, { selectedp3:selectedp3,selectedp2:selectedp2,selectedpl:selectedpl,in1: in1, in2: in2,in3: in3,in4: in4,in5: in5,in6: in6,in7: in7,in8: in8,selectedlogin_OperatorCoID:selectedlogin_OperatorCoID } ,function(data){          
           
            location.reload();
           if (data.error>0) 
            alert( "خطا در ثبت" ); 
            
           else alert( "ثبت انجام شد" );
            
       }, 'json');
    };
    
    
var selectList4 = document.createElement("select");
for (var i = 0; i < document.getElementById("subprj").length; i++) 
{
    var option = document.createElement("option");
    option.value = document.getElementById("subprj").options[i].value;
    option.text = document.getElementById("subprj").options[i].text;
    selectList4.appendChild(option);
}    

var selectList3 = document.createElement("select");
for (var i = 0; i < document.getElementById("Gadget1ID").length; i++) 
{
    var option = document.createElement("option");
    option.value = document.getElementById("Gadget1ID").options[i].value;
    option.text = document.getElementById("Gadget1ID").options[i].text;
    selectList3.appendChild(option);
}

//Create and append select list
var selectList = document.createElement("select");
var selectList2 = document.createElement("select");


    //alert(document.getElementById("ProducersID"));
    
//Create and append the options
for (var i = 0; i < document.getElementById("ProducersID").length; i++) 
{
    var option = document.createElement("option");
    option.value = document.getElementById("ProducersID").options[i].value;
    option.text = document.getElementById("ProducersID").options[i].text;
    selectList.appendChild(option);
}
if (document.getElementById("ProducersID").length==2)
selectList.selectedIndex=1;

selectList.onchange=function(){
    
    var selectedProducersID =this.value;
    var Url= document.getElementById("txturl").value; 
    
    
    var myDiv0 = document.getElementById("mydiv");
    var searchEles0 = myDiv.children;
     
          for (var i=0; i<searchEles0[3].length; i++)
                searchEles0[3].remove(i);   
                    
    $.post(Url, {selectedProducersID:selectedProducersID}, function(data){
        
      
  
        
        
        var myDiv = document.getElementById("mydiv");
        var searchEles = myDiv.children;
        
     
        var res1 = data.selectstr1.split("-");
        var res2 = data.selectstr2.split("-"); 
        for (var i = 0; i < res1.length-1; i++) 
        {
    
        var option = document.createElement("option");
    option.value = res2[i];
    option.text = res1[i];
    searchEles[3].appendChild(option);
    }
    
	       
       }, 'json');
                    
                }; 
selectList.style.width = '120px';
selectList2.style.width = '80px';
selectList3.style.width = '80px';
selectList4.style.width = '40px';

selectList2.name='4';
selectList3.name='44';
selectList4.name='444';
element1.id = currindex+1;currindex=currindex+1;
element2.id =  currindex+1;currindex=currindex+1;
selectList.id = currindex+1;currindex=currindex+1;
selectList2.id = currindex+1;currindex=currindex+1;
selectList3.id = currindex+1;currindex=currindex+1;
selectList4.id = currindex+1;currindex=currindex+1;
element3.id = currindex+1;currindex=currindex+1;
element4.id =  currindex+1;currindex=currindex+1;
element5.id =  currindex+1;currindex=currindex+1;
element6.id =  currindex+1;currindex=currindex+1;
    
    
    element5.style.width = '133px';
    element6.style.width = '33px';
    element6.style.height = '28px';
    element5.style.height = '28px';
    element4.style.height = '28px';
    element3.style.height = '28px';
    element2.style.height = '28px';
    element1.style.height = '28px';
    selectList.style.height = '28px';
    selectList2.style.height = '28px';
    selectList3.style.height = '28px';
    selectList4.style.height = '28px';
    
    myDiv.appendChild(element1);
    myDiv.appendChild(element2);
    myDiv.appendChild(selectList);
    myDiv.appendChild(selectList2);
    myDiv.appendChild(selectList3);
    myDiv.appendChild(selectList4);
    myDiv.appendChild(element3);
    myDiv.appendChild(element4);
    myDiv.appendChild(element5);
    myDiv.appendChild(element6);
    
    element2.focus();
}

document.addEventListener('DOMContentLoaded', function() {
   add();
}, false);

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
            
                
                <table width="95%" align="center">
                    <tbody>
                        <td></td>
                            <h1 align="center">  لیست پیش فاکتور/لیست لوازم های <?php print $ApplicantName; ?> </h1>
                        
                            <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxSerial; ?>"/>
                            <INPUT type="hidden" id="txtinvoicedate" value="<?php print gregorian_to_jalali(date('Y-m-d')); ?>"/>
                            <INPUT type="hidden" id="txtApplicantMasterID" value="<?php print $id; ?>"/>
                            <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
							<INPUT type="hidden" id="txtrole" value="<?php print $login_RolesID; ?>"/>
						    <INPUT type="hidden" id="txtlogin_OperatorCoID" value="<?php print $login_OperatorCoID; ?>"/>
							<INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoicemaster_list_jr.php"; ?>"/>
                           
                           
                            <div style = "text-align:left;">
                            <!-- button title='پیش فاکتور جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button --> 
                          <a href=<?php print "invoice_list.php"; ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a>
                            
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
                    
                     
                    
                        <tr>
                        <td></td>  <td>سريال</td>
           <td>عنوان</td>
           <td>صادر کننده</td>
           <td>لیست قیمت </td>
           <td>گروه کالا</td>
           <td>زیر پروژه</td>
           <td>تاریخ</td>
           <td>ردیف</td>
         <td>توضیحات</td>
   
						</tr>
	<!--					<tr>
                        	<th width="5px"  colspan="12">سريال&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;عنوان
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;صادر کننده
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;لیست قیمت 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;گروه کالا
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;زیر پروژه
                            &nbsp;&nbsp;&nbsp;&nbsp;تاریخ
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ردیف
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;توضیحات
                            </th>
                        </tr>
        -->                
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="12"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead> 
                   <tbody>
				   
				   <?php
                    
                    
                    
                    while($row = mysql_fetch_assoc($result)){

                        $Serial = $row['Serial'];
                        $ID = $row['invoicemasterID'];
                        $Title = $row['Title'];
                        $PTitle = $row['PTitle'];
                        
                         $linearray = explode('_',$row['Description']);
                         $Description=$linearray[0];
                        $Description2=$linearray[1];
                        $Description3=$linearray[2];
                        $Gadget1ID=$linearray[3];
                        $subprj=$row['appsubprjTitle'];
                        
                        
                        $Rowcnt = $row['Rowcnt'];
                        $InvoiceDate = $row['InvoiceDate'];
                        $bf = $row['fb'];

?> 

                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                           <td><?php echo $Serial ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $Title; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $PTitle; ?>&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $bf; ?>&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $row['gadget1title']; ?>&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo "&nbsp;&nbsp&nbsp;&nbsp;$subprj&nbsp;&nbsp&nbsp;&nbsp"; ?></td>
                            <td><?php echo $InvoiceDate; ?>&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $Rowcnt; ?>&nbsp;&nbsp;&nbsp;</td>
                            <td><?php echo $Description."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>"; 
                            
                            //if ( (($row['ApplicantMasterIDp']>0) && $row['pipeproducer']==1) || ( $row['proposable']==1 )  )
                           /* if ($row['proposable']==1)
                            echo "<td colspan=3 >پیشنهاد قیمت</td>";
                                else
                            { */
                            //print $row['proposable'];
                            
                            if ($inpproposing!=1 || $row['proposable']!=1 && !($login_RolesID==10 && $row['proposable']==1) )
                            
                            print "<td><a href='invoicedetail_list.php?np=10&uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'
                            ><img style = 'width: 25%;' src='../img/search.png' title=' بارگذاری فایل لوازم '></a></td>
                            
                            <td><a href='invoicemaster_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."
                            '><img style = 'width: 25%;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                            ";
                            else print "<td></td><td></td>";
                            if ( ($inpproposing==1 && $row['proposable']==1) || $row['InvoiceMasterIDmaster']>0 || $row['proposable']==1)
                            
                            print "<td> </td> <td> </td>";
                            else 
                            print "<td><a 
                            href='invoicemaster_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که کاملا حذف شود ؟');\"
                            > <img style = 'width: 25%;' src='../img/delete.png' title='حذف کامل '> </a>
                            </td>
                            
                            <td><a 
                            href='invoicemaster_deletedetail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)."im_".$ID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که ریز لوازم حذف شود ؟');\"
                            > <img style = 'width: 25%;' src='../img/new_page.png' title='حذف ریز لوازم '> </a>
                            </td>
                            
                            ";
                           
							
					                 $fstr1="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/invoice/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDinvoice=$linearray[0];
                            $No=$linearray[1];
                            if (($IDinvoice==$row['invoicemasterID']) && ($No==1) )
                                $fstr1="<a target='blank' href='../../upfolder/invoice/$file' ><img style = 'width: 30%;' src='../img/full_page.png' title='اسکن پیش فاکتور' ></a>";
                              
                        }
                    }
		
	 				      print "<td>$fstr1 </td>";
                    		
						 print "</tr>";	
							
							

                    }
                    

?>
                    </tbody>
                    
                      
                </table>
                <?php echo "* در لیست صادرکننده، فروشندگان/تولیدکنندگان ستاره دار فاقد ضمانت نامه بانکی در صندوق توسعه حمایت کشاورزی هستند."; ?>
                <div style='visibility: hidden' >
                          <?php

                     $limited = array("9","10","17");
                     if ( in_array($login_RolesID, $limited) || $login_userid==69)
					   $query='select ProducersID as _value,Title as _key from producers where ProducersID=148 order by Title   COLLATE utf8_persian_ci';
                     
        else $query=
                     "
                    select distinct _key,_value from (
                    select  producers.ProducersID _value,case producers.guaranteeExpireDate<'".gregorian_to_jalali(date('Y-m-d'))."' when 1 then concat(producers.title,'(*)') else producers.title end _key from producers
                    inner join toolsmarks on toolsmarks.ProducersID=producers.ProducersID
                    inner join pricelistdetail on ifnull(pricelistdetail.hide,0)=0 and  pricelistdetail.toolsmarksid=toolsmarks.toolsmarksid and 
                    ifnull(pricelistdetail.price,0)>0
                    inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid and pricelistmaster.pfo=1
                    where producers.ProducersID<>142 and producers.ProducersID<>148 and ifnull(pricelistdetail.Price,0)<>0
                    
                    union all
                    select  producers.producersID as _value,case producers.guaranteeExpireDate<'".gregorian_to_jalali(date('Y-m-d'))."' when 1 then concat(producers.title,'(*)') else producers.title end as _key from producers
                    inner join toolsmarks on toolsmarks.producersID=producers.producersID 
                    inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (202,376) 
                    where producers.ProducersID<>148 and producers.ProducersID<>142
                    
                    union all 
                    select  producers.producersID as _value,case producers.guaranteeExpireDate<'".gregorian_to_jalali(date('Y-m-d'))."' when 1 then concat(producers.title,'(*)') else producers.title end as _key from producers
                    where producers.producersID=135) view1
                    where _value in (select ProducersID from producers where ifnull(rank,0)>0)
                    
                    order by  _key COLLATE utf8_persian_ci";
                    
                    print $query;
                    
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('ProducersID','',',',$ID,0,'','','1','rtl',0,'',$ProducersID);
                    
                    $query=
                     "
                    select Title _key, Gadget1ID _value from gadget1 where IsCost<>1
                    
                    order by  _key COLLATE utf8_persian_ci";
                    
                    //print $query;
                    
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('Gadget1ID','',',',$ID,0,'','','1','rtl',0,'');

                    
                    $query=
                     "select Title _key, appsubprjID _value from appsubprj where ApplicantMasterID='$id'
                    
                    order by  _key COLLATE utf8_persian_ci";
                    
                    //print $query;
                    
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('subprj','',',',$ID,0,'','','1','rtl',0,'');
                    
					  ?>
                      </div>
                      
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php 
            
            
            include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
