<?php 
/*
pricesaving/pricesavingpipe.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingpipe_delete.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($_POST && ($login_RolesID==1 || $login_RolesID==18) ) 
 if ($_POST['trans']=='on')
{

/*
piprpricehistory تاریخچه قیمت لوله
maxdate تاریخ
maxhdazad سقف قیمت لوله با چگالی بالا
maxhdburs سقف ضخامت لوله با چگالی بالا
coefhd  ضریب لوله با چگالی بالا
transporthd  هزینه حمل لوله با چگالی بالا
producehd  هزینه تولید لوله با چگالی بالا
profithd  سود تولید لوله با چگالی بالا

maxldazad سقف قیمت لوله با چگالی پایین
maxldburs سقف ضخامت لوله با چگالی پایین
coefld  ضریب لوله با چگالی پایین
transportld  هزینه حمل لوله با چگالی پایین
produceld  هزینه تولید لوله با چگالی پایین
profitld  سود تولید لوله با چگالی پایین

maxpe100pipeprice  سقف قیمت لوله 100
maxpe80pipeprice سقف قیمت لوله 80
maxpe32pipeprice سقف قیمت لوله 32
maxpe40pipeprice سقف قیمت لوله 40
*/

    $maxdatePOST = jalali_to_gregorian($_POST['maxdate']);
    $SaveTime=date('Y-m-d H:i:s');
    mysql_query("INSERT INTO piprpricehistory(`maxhdazad`, `maxhdburs`, `coefhd`, `transporthd`, `producehd`, 
    `profithd`, `maxldazad`, `maxldburs`, `coefld`, `transportld`, `produceld`, `profitld`, `maxpe100pipeprice`, `maxpe80pipeprice`, `maxpe32pipeprice`, `maxpe40pipeprice`, `SaveDate`, `SaveTime`, `ClerkID`) VALUES(
    '".str_replace(',', '', $_POST['maxhdazad'])."', 
	'".str_replace(',', '', $_POST['maxhdburs'])."', 
	'".str_replace(',', '', $_POST['coefhd'])."', 
    '".str_replace(',', '', $_POST['transporthd'])."', 
	'".str_replace(',', '', $_POST['producehd'])."', 
    '".str_replace(',', '', $_POST['profithd'])."', 
	'".str_replace(',', '', $_POST['maxldazad'])."', 
	'".str_replace(',', '', $_POST['maxldburs'])."', 
    '".str_replace(',', '', $_POST['coefld'])."', 
	'".str_replace(',', '', $_POST['transportld'])."', 
	'".str_replace(',', '', $_POST['produceld'])."', 
    '".str_replace(',', '', $_POST['profitld'])."',
	  '".str_replace(',', '', $_POST['maxpe100pipeprice'])."',
	  '".str_replace(',', '', $_POST['maxpe80pipeprice'])."',
	  '".str_replace(',', '', $_POST['maxpe32pipeprice'])."',
	  '".str_replace(',', '', $_POST['maxpe40pipeprice'])."',
	
	'$maxdatePOST','$SaveTime','$login_userid');");
    

            
		if (!($_FILES["file1"]["error"] > 0))
		{
			$query = "SELECT piprpricehistoryID FROM piprpricehistory where piprpricehistoryID = last_insert_id() and SaveTime='$SaveTime' 
					and ClerkID='$login_userid'";        
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

			$row = mysql_fetch_assoc($result);
			
			$ext = end((explode(".", $_FILES["file1"]["name"])));
			$attachedfile=$row['piprpricehistoryID'].'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
			move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/pipeprice/" .$attachedfile);   
		}
		//supervisorcoderrquirement جدول پیکربندی سیستم
	if ($_POST['hiden']=='1') {
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxpe100pipeprice'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxpe100pipeprice' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxpe80pipeprice'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxpe80pipeprice' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxpe32pipeprice'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxpe32pipeprice' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxpe40pipeprice'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxpe40pipeprice' and ostan='$login_ostanId'");}
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxhdburs'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxhdburs' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxhdazad'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxhdazad' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxldburs'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxldburs' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['maxldazad'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='maxldazad' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueStr = '".str_replace(',', '', $_POST['coefhd'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='coefhd' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['transporthd'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='transporthd' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['producehd'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='producehd' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['profithd'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='profithd' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueStr = '".str_replace(',', '', $_POST['coefld'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='coefld' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['transportld'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='transportld' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['produceld'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='produceld' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['profitld'])."', SaveDate = '$maxdatePOST' WHERE KeyStr='profitld' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['lowrange'])."', SaveDate = '$maxdatePOST' WHERE keystr ='lowrange' and ostan='$login_ostanId'");
    mysql_query("UPDATE supervisorcoderrquirement SET ValueInt = '".str_replace(',', '', $_POST['uprange'])."', SaveDate = '$maxdatePOST' WHERE keystr ='uprange' and ostan='$login_ostanId'");

    
}
        $query = "SELECT max(piprpricehistoryID) piprpricehistoryID FROM piprpricehistory ";        
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

        $row = mysql_fetch_assoc($result);
        

$fstr1="";
$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/pipeprice/';
$handler = opendir($directory);
while ($file = readdir($handler)) 
{
    // if file isn't this directory or its parent, add it to the results
    if ($file != "." && $file != "..") 
    {                               
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if (($ID==$row['piprpricehistoryID']) )
            $fstr1="<a target='_blank' href='../../upfolder/pipeprice/$file' ><img style = 'width: 30px;' 
            src='../img/attachment.png' title='فایل ' ></a>";
                                          
    }
}
                            
$tblname='pipeprice';
$formname='pricesavingpipe';


if ($login_Permission_granted==0) header("Location: ../login.php");
if ($login_RolesID==3 && $login_PipeProducer==0) header("Location: ../login.php");

$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

/*
piprpricehistory تاریخچه قیمت لوله
maxdate تاریخ
maxhdazad سقف قیمت لوله با چگالی بالا
maxhdburs سقف ضخامت لوله با چگالی بالا
coefhd  ضریب لوله با چگالی بالا
transporthd  هزینه حمل لوله با چگالی بالا
producehd  هزینه تولید لوله با چگالی بالا
profithd  سود تولید لوله با چگالی بالا

maxldazad سقف قیمت لوله با چگالی پایین
maxldburs سقف ضخامت لوله با چگالی پایین
coefld  ضریب لوله با چگالی پایین
transportld  هزینه حمل لوله با چگالی پایین
produceld  هزینه تولید لوله با چگالی پایین
profitld  سود تولید لوله با چگالی پایین

maxpe100pipeprice  سقف قیمت لوله 100
maxpe80pipeprice سقف قیمت لوله 80
maxpe32pipeprice سقف قیمت لوله 32
maxpe40pipeprice سقف قیمت لوله 40
*/

 $querymax1 = "select distinct s0.ValueInt maxpe100pipeprice,s0.SaveDate maxdate
 ,s1.ValueInt maxpe80pipeprice
 ,s2.ValueInt maxpe32pipeprice
 ,s3.ValueInt maxpe40pipeprice
 ,s4.ValueInt maxhdburs
 ,s5.ValueInt maxhdazad
 ,s6.ValueInt maxldburs
 ,s7.ValueInt maxldazad
 ,s8.ValueInt lowrange
 ,s9.ValueInt uprange
 ,s10.ValueStr coefhd
 ,s11.ValueInt transporthd
 ,s12.ValueInt producehd
 ,s13.ValueInt profithd
 ,s14.ValueStr coefld
 ,s15.ValueInt transportld
 ,s16.ValueInt produceld
 ,s17.ValueInt profitld
 
  from supervisorcoderrquirement s0 
 left outer join supervisorcoderrquirement s1 on s1.KeyStr='maxpe80pipeprice' and s1.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s2 on s2.KeyStr='maxpe32pipeprice' and s2.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s3 on s3.KeyStr='maxpe40pipeprice' and s3.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s4 on s4.KeyStr='maxhdburs' and s4.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s5 on s5.KeyStr='maxhdazad' and s5.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s6 on s6.KeyStr='maxldburs' and s6.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s7 on s7.KeyStr='maxldazad' and s7.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s8 on s8.KeyStr='lowrange' and s8.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s9 on s9.KeyStr='uprange' and s9.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s10 on s10.KeyStr='coefhd' and s10.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s11 on s11.KeyStr='transporthd' and s11.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s12 on s12.KeyStr='producehd' and s12.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s13 on s13.KeyStr='profithd' and s13.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s14 on s14.KeyStr='coefld' and s14.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s15 on s15.KeyStr='transportld' and s15.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s16 on s16.KeyStr='produceld' and s16.ostan='$login_ostanId'
 left outer join supervisorcoderrquirement s17 on s17.KeyStr='profitld' and s17.ostan='$login_ostanId'
   
 where s0.KeyStr='maxpe100pipeprice'  and s0.ostan='$login_ostanId';";
 //print $querymax1;
 
     $resultmax1 = mysql_query($querymax1);  
    
	 $rowmax1 = mysql_fetch_assoc($resultmax1); 		
     $maxpe100=$rowmax1['ValueInt'];	   
     
	 if (!($rowmax1['maxdate']>0))
        $maxdate=gregorian_to_jalali(date('Y-m-d')) ;
     else if (substr($rowmax1['maxdate'],0,2)==13)
		$maxdate=($rowmax1['maxdate']) ; 
     else
        $maxdate=gregorian_to_jalali($rowmax1['maxdate']) ;  
        
    //    print gregorian_to_jalali($rowmax1['maxdate']);
        
         
     $maxpe100pipeprice=$rowmax1['maxpe100pipeprice'];
     $maxpe80pipeprice=$rowmax1['maxpe80pipeprice'];
     $maxpe32pipeprice=$rowmax1['maxpe32pipeprice'];
     $maxpe40pipeprice=$rowmax1['maxpe40pipeprice'];
     $maxhdburs=$rowmax1['maxhdburs'];
     $maxhdazad=$rowmax1['maxhdazad'];
     $maxldburs=$rowmax1['maxldburs'];
     $maxldazad=$rowmax1['maxldazad'];
     $lowrange=$rowmax1['lowrange'];
     $uprange=$rowmax1['uprange'];
     $coefhd=$rowmax1['coefhd'];
     $transporthd=$rowmax1['transporthd'];
     $producehd=$rowmax1['producehd'];
     $profithd=$rowmax1['profithd'];
     $coefld=$rowmax1['coefld'];
     $transportld=$rowmax1['transportld'];
     $produceld=$rowmax1['produceld'];
     $profitld=$rowmax1['profitld'];
 

//----------
if ($login_ProducersID>0)    
$sql = "SELECT COUNT(*) as count FROM ".$tblname."  where ProducersID='$login_ProducersID'";
else $sql = "SELECT COUNT(*) as count FROM ".$tblname;
 
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------



if ($login_ProducersID>0)    
	$sql = "SELECT pipeprice.*,producers.title producerstitle FROM pipeprice 
	inner join producers on producers.ProducersID=pipeprice.ProducersID
	where producers.ProducersID='$login_ProducersID'
	ORDER BY Date DESC LIMIT " . $start . ", " . $per_page . ";";
else if ($login_RolesID==1 || $login_RolesID==18) 
    $sql = "SELECT pipeprice.*,producers.title producerstitle FROM pipeprice 
	inner join producers on producers.ProducersID=pipeprice.ProducersID
	ORDER BY Date DESC LIMIT " . $start . ", " . $per_page . ";";
else 
	$sql = "select * from (SELECT distinct (producers.Title) producerstitle, pipeprice.* FROM pipeprice 
	inner join producers on producers.ProducersID=pipeprice.ProducersID
	where pipeprice.Date>='$maxdate' 
	ORDER BY Date DESC LIMIT " . $start . ", " . $per_page . ") as v1 
	group by ProducersID 
	ORDER BY Date DESC;";
							try 
								  {		
									   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }


//$maxdate='';
//print $sql;

?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت قیمت لوله</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function CheckForm()
    {
	
	if (document.getElementById('login_RolesID').value==18 || document.getElementById('login_RolesID').value==1)
		{   

			if (!(document.getElementById('trans').checked))
			{
				alert('لطفاً لیست قیمت مواد پلی اتیلن را تایید نمایید.');return false;
			}

			/*
			if (!(document.getElementById('file1').value != "">0))
			{
				alert('لطفا اسکن فایل پیشنهاد قیمت را انتخاب نمایید!');
				return false;
			}  */
		}
    }
	
	    function calc()
    {
		document.getElementById('pricehd').value =numberWithCommas(
        Math.floor(
        ( numberWithoutCommas(document.getElementById('maxhdazad').value)*1+
		numberWithoutCommas(document.getElementById('maxhdburs').value)*1)*numberWithoutCommas(document.getElementById('coefhd').value)*1+
        numberWithoutCommas(document.getElementById('transporthd').value)*1+
        numberWithoutCommas(document.getElementById('producehd').value)*1+
        numberWithoutCommas(document.getElementById('profithd').value)*1)
        );
        
        document.getElementById('priceld').value =numberWithCommas(
        Math.floor(
        ( numberWithoutCommas(document.getElementById('maxldazad').value)*1+
		numberWithoutCommas(document.getElementById('maxldburs').value)*1)*numberWithoutCommas(document.getElementById('coefld').value)*1+
        numberWithoutCommas(document.getElementById('transportld').value)*1+
        numberWithoutCommas(document.getElementById('produceld').value)*1+
        numberWithoutCommas(document.getElementById('profitld').value)*1)
        );
		
		
		return ;
		
    }
	
    
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
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
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
    
    
function add() {
 
    var myDiv = document.getElementById("mydiv");
    var currindex = myDiv.children.length;
        
        
    if (currindex>=4) return;
    
    var element1 = document.createElement("input");
    var element2 = document.createElement("input");
    var element3 = document.createElement("input");
    var element4 = document.createElement("input");
    var element5 = document.createElement("input");
    var element6 = document.createElement("input");
 
    
    element1.setAttribute("value", document.getElementById("txtdate").value);
    
    
    
    
    element1.setAttribute("size", "10");
 
 
    
    element2.onkeyup=function()
    {
        var number = element2.value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        element2.value=numberWithCommas(number);    
    }
    
    element3.onkeyup=function()
    {
        var number = element3.value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        element3.value=numberWithCommas(number);    
    }


    element4.onkeyup=function()
    {
        var number = element4.value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        element4.value=numberWithCommas(number);    
    }

    element5.onkeyup=function()
    {
        var number = element5.value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        element5.value=numberWithCommas(number);    
    }    

    element2.style.width = '90px';
    element3.style.width = '90px';
    element4.style.width = '90px';
    element5.style.width = '90px';
    
    
    element6.type = "button";
    element6.value = "درج"; // Really? You want the default value to be the type string?
    element6.name = "button";  // And the name too?
    
    
       
    
    element6.onclick = function() 
    { // Note this is a function
    
        
        //var searchEles = document.getElementById("myDiv").children;
        var buttonid=this.id;
 
        var myDiv = document.getElementById("mydiv");
        var searchEles = myDiv.children;
          
          var in1=searchEles[buttonid-6].value;
          var in2= searchEles[buttonid-5].value.replace(/,/g, "");
          var in3=searchEles[buttonid-4].value.replace(/,/g, "");
          var in4=searchEles[buttonid-3].value.replace(/,/g, "");
          var in5=searchEles[buttonid-2].value.replace(/,/g, "");
          var in6=document.getElementById("txtuserid").value;
        var txturl = document.getElementById("txturl").value;
       
       
       //alert(txturl);
      $("#loading-div-background").show();
          $.post(txturl, { in1: in1, in2: in2,in3: in3,in4: in4,in5: in5,in6: in6} ,function(data){
            $("#loading-div-background").hide(); 
           if (data.error==1) 
            alert( "قیمت این روز قبلا ثبت شده است" ); 
            /*
            else if (data.error==3) 
            alert( "مبلغ قیمت لوله PE80 بیشتر از سقف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==2) 
            alert( "مبلغ قیمت لوله PE100 بیشتر از سقف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            */
            else if (data.error==4) 
            alert( "فروشنده مشخص نمی باشد" ); 
            /*else if (data.error==5) 
            alert( "مبلغ قیمت لوله PE32 بیشتر از سقف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==6) 
            alert( "مبلغ قیمت لوله PE40 بیشتر از سقف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==7) 
            alert( "مبلغ قیمت لوله PE32 کمتر از کف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==8) 
            alert( "مبلغ قیمت لوله PE40 کمتر از کف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==9) 
            alert( "مبلغ قیمت لوله PE80 کمتر از کف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            else if (data.error==10) 
            alert( "مبلغ قیمت لوله PE100 کمتر از کف مجاز می باشد لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید" ); 
            */
            
           else alert( "ثبت انجام شد" );
        
            $(this).parent().remove();
            location.reload();
        
        
       }, 'json');

        
          //myDiv.removeChild(searchEles[buttonid-1].id);
            
        
        
     
    };
    
    element6.style.height = '29px';
    element5.style.height = '29px';
    element4.style.height = '29px';
    element3.style.height = '29px';
    element2.style.height = '29px';
    element1.style.height = '29px';
    element1.id = currindex+1;currindex=currindex+1;
    element2.id =  currindex+1;currindex=currindex+1;
    element3.id = currindex+1;currindex=currindex+1;
    element4.id =  currindex+1;currindex=currindex+1;
    element5.id =  currindex+1;currindex=currindex+1;
    element6.id =  currindex+1;currindex=currindex+1;
    
    myDiv.appendChild(element1);
    myDiv.appendChild(element2);
    myDiv.appendChild(element3);
    myDiv.appendChild(element4);
    myDiv.appendChild(element5);
    myDiv.appendChild(element6);
    
    element2.focus();
}

    </script>
    <!-- /scripts -->
</head>
<body onload="calc();">

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
                        <tr>
                        
                        <h1 align="center"> لیست قیمت لوله </h1>
                          <INPUT type="hidden" id="txtdate" value="<?php print gregorian_to_jalali(date('Y-m-d')); ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/pricesaving/pricesavingpipe_jr.php"; ?>"/>
                           
                           <?php
                            if ($login_PipeProducer==1)
                            echo "
                            <div style = 'text-align:left;'>
                            <button title='افزودن قیمت لوله امروز' style=\"cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;\" type=\"button\" onclick=\"add()\">
                            <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                            
                          </div>";
                           ?>
                           
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
								
		<table width="95%" align="center">
        <tbody>
	    <?php if ($login_RolesID==1 || $login_RolesID==18) {  ?>
                
                  <tr>
                    <th >نوع مواد</th>
                    <th colspan="3" >قیمت مواد(ریال)</th>
        			<th colspan="1" > </th>
        			<th colspan="3" >سقف قیمتهای پیشنهادی مواد پلی اتیلن (ریال)</th>
				 </tr>
				   	
			      <tr>
                    <th > </th>
                    <th width="8%">عرضه بورس</th>
                    <th width="8%">بازار آزاد</th>
                    <th width="8%">ضریب</th>
                    <th width="8%">کرایه حمل</th>
                    <th width="10%">هزینه تمام شده تولید</th>
                    <th width="8%">سود تولید کننده</th>
                    <th width="8%">قیمت لوله</th>
					<th width="8%"> تاریخ </th>
					<th width="8%"> تاییدیه قیمتها </th>
					
                   </tr>
                 <tr>
                 	<th colspan="8"><div id="mydiv" >  </div></th>
                 </tr>
					
		<form action="pricesavingpipe.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
       		    <tr>
	                <td width="6%" >هایدن</td>
                    <td ><input type="text" name="maxhdburs" id="maxhdburs" onKeyUp="convert('maxhdburs')" value="<?php echo number_format($maxhdburs);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="maxhdazad" id="maxhdazad" onKeyUp="convert('maxhdazad')" value="<?php echo number_format($maxhdazad);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="coefhd" id="coefhd" value="<?php echo $coefhd;?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="transporthd" id="transporthd" onKeyUp="convert('transporthd')" value="<?php echo number_format($transporthd);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="producehd" id="producehd" onKeyUp="convert('producehd')" value="<?php echo number_format($producehd);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="profithd" id="profithd" onKeyUp="convert('profithd')" value="<?php echo number_format($profithd);?>" size="7" class='textbox' onblur="calc();"/></td>
                	<td ><input type="text" readonly name="pricehd" id="pricehd"  size="7" class='textbox' onblur="calc();"/></td>
                	<td ><input type="text" readonly name="hddate" value="<?php echo gregorian_to_jalali(date('Y-m-d')); ?>" size="10" class='textbox' /></td>
                	<td ><input type="checkbox" name="trans" size="10" class='textbox'id='trans' /></td>
                				
                </tr>
     		
			    <tr>
                    <td>لودن</td>
                  <td ><input type="text" name="maxldburs" id="maxldburs" onKeyUp="convert('maxldburs')" value="<?php echo number_format($maxldburs);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="maxldazad" id="maxldazad" onKeyUp="convert('maxldazad')" value="<?php echo number_format($maxldazad);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="coefld" id="coefld" value="<?php echo $coefld;?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="transportld" id="transportld" onKeyUp="convert('transportld')" value="<?php echo number_format($transportld);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="produceld" id="produceld" onKeyUp="convert('produceld')" value="<?php echo number_format($produceld);?>" size="7" class='textbox' onblur="calc();"/></td>
					<td ><input type="text" name="profitld" id="profitld" onKeyUp="convert('profitld')" value="<?php echo number_format($profitld);?>" size="7" class='textbox' onblur="calc();"/></td>
                	<td ><input type="text" readonly name="priceld" id="priceld"  size="7" class='textbox' onblur="calc();"/></td>
                	<td ><input type='file' name='file1' id='file1' ></td>
                    <td style = "text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';"><?php echo $fstr1; ?></td>
				</tr>
     		
               

		 <?php
         /*
         echo "<tr>
                    <th width=\"10%\" colspan=\"3\">حد پایین پیشنهاد قیمت لوله</th>
                    <th width=\"10%\"><input type=\"text\" id=\"lowrange\" name=\"lowrange\" onKeyUp=\"convert('lowrange')\"  
                    value=\"".number_format($lowrange)."\" size=\"7\" class='textbox' /></th>
                </tr>  
                <tr>
                    <th width=\"10%\" colspan=\"3\">حد بالای پیشنهاد قیمت لوله</th>
                    <th width=\"10%\"><input type=\"text\" id=\"uprange\" name=\"uprange\" onKeyUp=\"convert('uprange')\" 
                    value=\"".number_format($uprange)."\" size=\"7\" class='textbox' /></th>
                </tr>";
                */
         
          } ?>                      
  	    </tbody>
       </table>
				
				
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="10%">تاریخ</th>
                            <th width="10%">PE32 (ریال)</th>
                            <th width="10%">PE40 (ریال)</th>
                            <th width="10%">PE80 (ریال)</th>
                            <th width="12%">PE100 (ریال)</th>
                        	<th width="30%">تولید کننده</th>
                            <th width="35%">مجاز برای مشاورین</th>
                            <th width="7%">&nbsp;</th>
                            <th width="7%">&nbsp;</th>
                       </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    <?php 
							
					if ($login_RolesID==1 || $login_RolesID==18) { ?>
                                
                       <tr>
                            
                           <td>
							 <input type="text" name="maxdate" value="<?php echo $maxdate;?>" size="10"  class='textbox' /></td>
							 <td><input type="text" id="maxpe32pipeprice" name="maxpe32pipeprice" onKeyUp="convert('maxpe32pipeprice')" value="<?php echo number_format($maxpe32pipeprice);?>" size="7" class='textbox' /></td>
							 <td><input type="text" id="maxpe40pipeprice" name="maxpe40pipeprice" onKeyUp="convert('maxpe40pipeprice')" value="<?php echo number_format($maxpe40pipeprice);?>" size="7" class='textbox' /></td>
							 <td><input type="text" id="maxpe80pipeprice" name="maxpe80pipeprice" onKeyUp="convert('maxpe80pipeprice')" value="<?php echo number_format($maxpe80pipeprice);?>" size="7" class='textbox' /></td>
							<td><input type="text" id="maxpe100pipeprice" name="maxpe100pipeprice" onKeyUp="convert('maxpe100pipeprice')" value="<?php echo number_format($maxpe100pipeprice);?>" size="7" class='textbox' /></td>
               				<td><input type="text" id="hiden" name="hiden" size="2" class="textbox" style="border: none; background-color: transparent;"></td>
	                          <td class='data'><input name='login_RolesID' type='hidden' readonly class='textbox' id='login_RolesID' value="<?php echo $login_RolesID?>" /></td>
  						 
                            <td colspan="3"><input name='submit' type='submit' class='button' id='submit' value="ثبت سقف قيمت" />
							</form></td>
							</tr>
							<?php }
                    if ($result)
                    while($row = mysql_fetch_assoc($result)){

                        $Date = $row['Date'];
			            $ID = $row['PipePriceID'];
                        $PE80 = $row['PE80'];
                        $PE100 = $row['PE100'];
                        $PE32 = $row['PE32'];
                        $PE40 = $row['PE40'];

?>                      


                        <tr>
                            
                            <td><?php echo $Date; ?></td>
                            <td><?php echo number_format($PE32); ?></td>
                            <td><?php echo number_format($PE40); ?></td>
                            <td><?php echo number_format($PE80); ?></td>
                            <td><?php echo number_format($PE100); ?></td>
                            <td><?php echo $row['producerstitle']; ?></td>
                            <td><?php echo $row['pfd']; ?></td>
              <?php 
                                if ($login_RolesID==1) print "<td><a href='".$formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)
                                ."'><img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '> </a></td> "
                                ."<td><a href='".$formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)
                                ."'onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 60%;' src='../img/delete.png' title='حذف'> </a></td> 
                            </tr>"; 
                            
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
