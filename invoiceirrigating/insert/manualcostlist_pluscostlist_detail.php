<?php 

/*

insert/manualcostlist_pluscostlist_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$appsubprjID = is_numeric($_GET["appsubprjID"]) ? intval($_GET["appsubprjID"]) : 0;
$tblname='manuallistprice';// جدول ثبت هزینه های اجرایی طرح

if ($login_Permission_granted==0) header("Location: ../login.php");

   if ($appsubprjID>0)//زیر پروژه
        $appsubprjstr=" and manuallistprice.appsubprjID='$appsubprjID' ";
    else     
        $appsubprjstr=" and ifnull(manuallistprice.appsubprjID,0)=0 ";
    $uid=$_GET["uid"];





if ($_POST)
    { 

        $appsubprjID=$_POST["appsubprjID"];
        $uid=$_POST["uid"];
      //  $retids=$_POST['retids'];
        $appfoundationID=$_POST['appfoundationID'];//سازه
        //print 'salam'.$ApplicantMasterID;

        if ($login_RolesID==2 || $login_RolesID==9)
        {
            $ApplicantMasterID = $_POST['ApplicantMasterID'];
            $fehrestsmasterID = $_POST['fehrestsmasterID'];
            $fehrestsfaslsID = $_POST['fehrestsfaslsID'];
            if (($_FILES["file2"]["size"] / 1024)<=200)//بارگذاری اسکن
             {
                if ($_FILES["file2"]["error"] > 0) 
                {
                    echo "Error: " . $_FILES["file2"]["error"] . "<br>";
                } 
                else 
                {
                    $ext = end((explode(".", $_FILES["file2"]["name"])));
                    $FName="../temp/".$ApplicantMasterID.'_2_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                    move_uploaded_file($_FILES["file2"]["tmp_name"],$FName);   
                    //$ApplicantMasterID=2294;
                    //echo $FName;
                    readfromexcel($FName,$ApplicantMasterID,$login_OperatorCoID,$login_DesignerCoID,0,0,0,$login_userid,
                    $appfoundationID,$fehrestsfaslsID,'man',$fehrestsmasterID);
                    echo "بارگذاری با موفقیت انجام شد<br>";
                    $retids=$_POST['retids'];
                    header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$retids.rand(10000,99999));
                }        
             }
        }        
		
        
        
        $i=0;
    while (isset($_POST['Price'.++$i]))
     {
        	$ManualListPriceID = $_POST['ManualListPriceID'.$i];
            $ApplicantMasterID = $_POST['ApplicantMasterID'.$i];
			
	   	
            $fehrestsID = $_POST['fehrestsID'.$i];//exit;
		    $Code = $_POST['Code'.$i];
			//print $Code.'s';
		
            if (!($Code>0))
                continue;
 	//	print $_POST['mygroup'.$i].' ';
         
		 if ($_POST['mygroup'.$i]=='yes')
                $AddOrSub=1;
          else if ($_POST['mygroup'.$i]=='no')
                $AddOrSub=0;
			else if ($_POST['mygroup'.$i]=='yesno')
                $AddOrSub=2;
  		  
		  if ($AddOrSub<>2)
			$Code = $fehrestsID.$Code;
 	
				
			$Number='';$Number2='';$Number3='';$Number4='';$Number5='';$Number6='';
			 $cont1=1;$cont2=1;$cont3=1;$cont4=1;;$cont5=1;;$cont6=1;
			 if ($_POST['Number2'.$i]>0)   $Number2 = $_POST['Number2'.$i]; else $cont2=0;
			 if ($_POST['Number3'.$i]>0)   $Number3 = $_POST['Number3'.$i]; else $cont3=0;
			 if ($_POST['Number4'.$i]>0)   $Number4 = $_POST['Number4'.$i]; else $cont4=0;
			 if ($_POST['Number5'.$i]>0)   $Number5 = $_POST['Number5'.$i]; else $cont5=0;
			 if ($_POST['Number6'.$i]!=0)   $Number6 = $_POST['Number6'.$i]; else $cont6=0;
			 
			 $cont=$cont1+$cont2+$cont3+$cont4+$cont5+$cont6;
			 if ($cont==0)
                continue;
			
           // else
          //      $Number2=1;
		  
			if ($Number2>0) $Num2=$Number2; else $Num2=1;// تعداد
			if ($Number3>0) $Num3=$Number3; else $Num3=1;
			if ($Number4>0) $Num4=$Number4; else $Num4=1;
			if ($Number5>0) $Num5=$Number5; else $Num5=1;
			if ($Number6!=0) $Num6=$Number6; else $Num6=1;
			
			if ($Number3>0 || $Number4>0 || $Number5>0 || $Number6>0)
			$Number=round($Num3*$Num4*$Num5*$Num6,2);
		  
		  	if ($Number>0) $Num=$Number; else $Num=1;

				
            $Description = $_POST['Description'.$i];
            $fehrestsfaslsID = $_POST['fehrestsfaslsID'.$i];
            
       	//print $Code;
            $Title = $_POST['Title'.$i];
            $Unit = $_POST['Unit'.$i];
            $Price= str_replace(',', '', $_POST['Price'.$i]);
            $Price= str_replace('-', '', $Price);
            $Price= str_replace('+', '', $Price);
            
            
            $_POST['chk'.$i] = $_POST['chk'.$i];
          //  print $_POST['chk'.$i];
            if (($_POST['chk'.$i]==1) && ($ManualListPriceID != 0))
            {//manuallistprice جدول ثبت هزینه های اجرایی طرح
                $query = " delete from manuallistprice WHERE ManualListPriceID ='$ManualListPriceID' ;";
                
				
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
                continue;
            }           
        	
            
                if (in_array($login_RolesID, array(13,14,27,5)))
                $insertroldindex=3;
                else if (in_array($login_RolesID, array(10,11)))
                $insertroldindex=2;
                else $insertroldindex=1;
       
		  if ($Number2 && ($Number3 || $Number4 || $Number5 || $Number6))	
			{   
				   
				if ($ManualListPriceID != 0)//update
				{ //manuallistprice جدول ثبت هزینه های اجرایی طرح
					$query = "
					UPDATE manuallistprice SET
					ApplicantMasterID = '" . $ApplicantMasterID . "', 
					Number = '" . $Number . "', 
					Number2 = '" . $Number2 . "',
					Number3 = '" . $Number3 . "', 
					Number4 = '" . $Number4 . "', 
					Number5 = '" . $Number5 . "', 
					Number6 = '" . $Number6 . "', 
								
					nval$insertroldindex = '" . $Num2*$Num3*$Num4*$Num5*$Num6 . "',  
					pval$insertroldindex = '" . $Price. "', 
					Description = '" . $Description. "',  
					fehrestsfaslsID = '" . $fehrestsfaslsID. "', 
					AddOrSub = '" . $AddOrSub. "', 
					appsubprjID='$appsubprjID',
					Code = '" . $Code. "', 
					Title = '" . $Title. "', 
					Unit = '" . $Unit. "',  
					Price = '" . $Price. "',  
					SaveTime = '" . date('Y-m-d H:i:s') . "', 
					SaveDate = '" . date('Y-m-d') . "', 
					ClerkID = '" . $login_userid . "'
					WHERE ManualListPriceID = " . $ManualListPriceID . ";";
					
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
        
        
            
    //exit;
    }



    $ids = substr($uid,40,strlen($uid)-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];
    $fehrestsmasterID=$linearray[1];
    $type=$linearray[2];
    $fehrestsfaslsID=$linearray[3];
    $appfoundationID=$linearray[4];
    
    $retids=$ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$appfoundationID;
    //exit();


$sql = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr = 'manualcostlistdetailnumber' and ostan='$login_ostanId'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$np = $count['ValueInt'];
        

$sql = "SELECT ApplicantName,year.Value fp FROM applicantmaster 
inner join costpricelistmaster on costpricelistmaster.CostPriceListMasterID=applicantmaster.CostPriceListMasterID
inner join year on year.YearID=costpricelistmaster.YearID
WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$ApplicantName = $count['ApplicantName'];
		$fp = $count['fp'];

    $sql = "SELECT Title FROM fehrestsmaster WHERE fehrestsmasterID = '" . $fehrestsmasterID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsfaslsTitle = $count['Title'];


    $sql = "SELECT Title,fasl FROM fehrestsfasls WHERE fehrestsfaslsID = '" . $fehrestsfaslsID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsmasterTitle = $count['Title'];       
    $fasl = $count['fasl']; 
    /*
    manuallistprice فهرست بهای دستی
    appfoundation سازه
    Number تعداد
    ApplicantMasterID شناسه طرح
    */
    if ($appfoundationID==-1)
    $sql = "SELECT distinct manuallistprice.*,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber 
    FROM manuallistprice
    left outer join appfoundation on appfoundation.ApplicantMasterID=appfoundation.ApplicantMasterID  and appfoundation.appfoundationID='$appfoundationID'
     
     where manuallistprice.ApplicantMasterID = '".$ApplicantMasterID."' 
     and manuallistprice.fehrestsfaslsID='".$fehrestsfaslsID."'
     and manuallistprice.appfoundationID='$appfoundationID' $appsubprjstr" ;
     else    
    if ($appfoundationID<>0)    
    $sql = "SELECT manuallistprice.*,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber 
    FROM manuallistprice
    left outer join appfoundation on manuallistprice.ApplicantMasterID=appfoundation.ApplicantMasterID 
    and fehrestsfaslsID='".$fehrestsfaslsID."' and ifnull(manuallistprice.appfoundationID,0)=appfoundation.appfoundationID
     where manuallistprice.ApplicantMasterID = '".$ApplicantMasterID."' and manuallistprice.appfoundationID='$appfoundationID' $appsubprjstr" ;
    else 
    $sql = "SELECT manuallistprice.*, 1 FNumber FROM manuallistprice where ApplicantMasterID = '".$ApplicantMasterID."' 
    and fehrestsfaslsID='".$fehrestsfaslsID."'
    and ifnull(manuallistprice.appfoundationID,0)=0 $appsubprjstr" ;
    
	  	   				  	try 
								  {		
									 	$resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  // print $sql;


	
	
	

?>
<!DOCTYPE html>
<html>
<head>
  	<title>فهرست بهای دستی </title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">

function selectpage(obj){
		window.location.href = '?uid='+document.getElementById('uid').value+'&appsubprjID=' + obj.value;
	}
    
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}

  function calculate(i)
    {
	
	//	for (var i=1;i<=document.getElementById('records').rows.length-3;i++)
	//		{ 
	//alert(i); 
			
				if (document.getElementById('yesno'+i).checked)
				$('#fehrestsID'+i).hide();
				else
				$('#fehrestsID'+i).show();
				//alert(i);
     //       }
	
	}

</script> 
<script language='javascript' src='../assets/jquery.js'></script>
    <!-- /scripts -->
</head>
<body >

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php 

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Serial = "";
						$ProducersID = "";
                        /*
                        print "manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$retids.rand(10000,99999);
                        exit;*/
                        
                        header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$retids.rand(10000,99999));
                        
                        
                        
					}else{
						echo '<p class="error">خطا در ثبت... لطفا کد را وارد نمایید</p>';
					}
				}

?>
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
			<div id="content" >
            <form action="manualcostlist_pluscostlist_detail.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 

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
		for (var i=1;i<document.getElementById('records').rows.length-2;i++)
        {
            var FNum=document.getElementById('FNumber'+i).value;
            var Num6=document.getElementById('Number6'+i).value;
            var Num5=document.getElementById('Number5'+i).value;
            var Num4=document.getElementById('Number4'+i).value;
            var Num3=document.getElementById('Number3'+i).value;
            var Num2=document.getElementById('Number2'+i).value;
                
   	        if (FNum>0) FNum=FNum; else FNum=1;
        	if (Num2>0) Num2=Num2; else Num2=1;
        	if (Num3>0) Num3=Num3; else Num3=1;
        	if (Num4>0) Num4=Num4; else Num4=1;
        	if (Num5>0) Num5=Num5; else Num5=1;
        	if (Num6>0) Num6=Num6; else Num6=1;
            
            sumt += p_tarkib(
			document.getElementById('Price'+i).value)*
            FNum*1*
			Num6*1*
			Num5*1*
			Num4*1*
			Num3*1*
			Num2*1;
            
        }
			
        
    document.getElementById('AllSum').value=numberWithCommas(sumt);
    
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }

function sumrow(rowNumber)
{ 
    summ();
   	var FNum=$('input[name=\"FNumber'+rowNumber+'\"]').val();
	var Num2=$('input[name=\"Number2'+rowNumber+'\"]').val();
	var Num3=$('input[name=\"Number3'+rowNumber+'\"]').val();
	var Num4=$('input[name=\"Number4'+rowNumber+'\"]').val();
	var Num5=$('input[name=\"Number5'+rowNumber+'\"]').val();
	var Num6=$('input[name=\"Number6'+rowNumber+'\"]').val();
			
	
	if (FNum>0) FNum=FNum; else FNum=1;
	if (Num2>0) Num2=Num2; else Num2=1;
	if (Num3>0) Num3=Num3; else Num3=1;
	if (Num4>0) Num4=Num4; else Num4=1;
	if (Num5>0) Num5=Num5; else Num5=1;
	if (Num6>0) Num6=Num6; else Num6=1;
	
    var x=parseFloat($('input[name=\"Price'+rowNumber+'\"]').val().replace(/,/g, ''))*
						Num2*
						FNum*
						Num3*
						Num4*
						Num5*
						Num6*
						1;
                        
    
    $('#divSumPrice'+rowNumber+' input:text ').val(numberWithCommas(x));
    $('#divSumPrice'+rowNumber).attr('onmouseover',\"Tip( '\"+(numberWithCommas(x)) +\"')\");
    x=$('input[name=\"Number'+rowNumber+'\"]').val();  
    $('#divNumber'+rowNumber).attr('onmouseover',\"Tip( '\"+(x) +\"')\");
}

</script>
";  ?>


                <div colspan="4">
                <tr >
                    <td align="center" 
                    style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-family:'B Nazanin';"
                    >فهرست بهای دستی</td>
                </tr>
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > 
                    <?php print $fehrestsfaslsTitle."($fp) فصل ".$fehrestsmasterTitle; ?> </td>
                </tr>
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php 
                    print "&nbsp; طرح آقای/خانم  &nbsp;".$ApplicantName; 
                    
                     print "
                            <td colspan='2' class='label' style = \"color: red;border:0px solid black;width: 100%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \">
                            
                        <a  target=\"_blank\"  href='../../upfolder/help/registerfehrest.pdf'>
                        راهنمای بارگذاری فایل اکسل فهرست بهای دستی
						<img style = 'width: 25px' src=\"../img/help.png\" title='راهنمای بارگذاری فایل اکسل فهرست بهای دستی'></a>
                        </td>
						";
                    ?> </td>
                </tr>
                    
                
                    
                </div>
                        	
              
                            
                            <div style = "text-align:left;"><a  href=<?php print "manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$retids.rand(10000,99999); ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            
                            <td align="left"><?php
                      
                       
                        
                       if ($login_RolesID==2 || $login_RolesID==9)
                            {
                                echo "<td colspan='1' class='label'>فایل اکسل فهرست بها</td>
                                    <td colspan='1' class='data'><input type='file' name='file2' id='file2' ></td>
                                    <td class=\"data\"><input name=\"ApplicantMasterID\" type=\"hidden\" class='textbox' id='ApplicantMasterID'  value='$ApplicantMasterID'  size='30' maxlength='15' /></td>
                                    <td class=\"data\"><input name=\"appfoundationID\" type=\"hidden\" class='textbox' id='appfoundationID'  value='$appfoundationID'  size='30' maxlength='15' /></td>
                                    <td class=\"data\"><input name=\"fehrestsmasterID\" type=\"hidden\" class='textbox' id='fehrestsmasterID'  value='$fehrestsmasterID'  size='30' maxlength='15' /></td>
                                    <td class=\"data\"><input name=\"fehrestsfaslsID\" type=\"hidden\" class='textbox' id='fehrestsfaslsID'  value='$fehrestsfaslsID'  size='30' maxlength='15' /></td>
                                    <td class=\"data\"><input name=\"retids\" type=\"hidden\" class=\"textbox\" id=\"retids\"  value=\"$retids\"   /></td>
                        
                                    <td colspan='2'><input name=\"tempsubmit\" type=\"submit\" class=\"button\" id=\"tempsubmit\" value=\"ثبت \"/></td>";
                                
                                
                            }
                      
                            

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <?php
                    print "<thead align='center'>
                        <tr>
                        	<th colspan=3 class=f12_fontb>ردیف̽</th>
                        	 	<th class=f11_fontb>کد̽</th>
                        	 	<th class=f10_fontb>کد مرتبط̽<br>فهرست بها</th>
                            <th class=f12_fontb>عنوان</th>
                            <th class=f12_fontb>واحد</th>
                            <th class=f12_fontb>مقدار̽<br>تعداد×طول×عرض×ضخامت/وزن×ضریب</th>
                            <th class=f12_fontb>فی (ریال)̽</th>";
                    if ($fehrestsmasterID==2) 
                        print "<th class=f12_fontb style='display:none'>تعداد<br> سازه</th>";
                        else 
                        print "<th  class=f12_fontb>تعداد<br> سازه</th>";
                    print "
                            
                            <th class=f12_fontb>جمع مبلغ</th>
                            <th class=f12_fontb>توضیح</th>
                        </tr>
                    </thead>
                   <tbody>";    
                    $cnt=0;
                    $rown=0;
                    $sum=0;
				   $query="SELECT Code AS _value, CONCAT(Code,' : ',substring(Title,1,100)) AS _key
						FROM fehrests
						WHERE fehrestsmasterID='$fehrestsmasterID' and substring(fehrests.Code,1,2)='$fasl'
                        ORDER BY Code 
                        ";
    				 $fehrests = get_key_value_from_query_into_array($query);
               // print $query;
                    if ($resultwhile)
                    while($row = mysql_fetch_assoc($resultwhile)){
                            
                        $ManualListPriceID = 0;
                        $Number = ''; $Number2 = '';
				        $Number3 = '';$Number4 = ''; $Number5 = ''; $Number6 = '';
                        $Price='';
                        $Description = '';
                        $AddOrSub = '';
                        $Code = '';
                        $Title = '';
                        $Unit = '';
                        $SumPrice='';
                        $nochecked="";
                        $yeschecked="";
						$yesnochecked="";
                        $fehrestsID="";
						$hidden="";
                        
                        if ($row)
                        {
                            $ManualListPriceID = $row['ManualListPriceID'];
                            $Description = $row['Description'];
							
							$rowCode=str_replace("*","",$row['Code']);
                            $fehrestsID = substr($rowCode,0,6);
							  
                            $Code = substr($rowCode,6);
                            $Title = $row['Title'];
                            $Unit = $row['Unit'];
             
                            $AddOrSub = $row['AddOrSub'];
                            if ($row['AddOrSub']==0) 
                            {
                                $nochecked="checked='checked'";
                                $yeschecked="";
								$yesnochecked="";
								$hidden="";
                            }
                            else if ($row['AddOrSub']==1) 
                            {
                                $nochecked="";
                                $yeschecked="checked='checked'";
								$yesnochecked="";
								$hidden="";
                            }
							else if ($row['AddOrSub']==2) 
                            {
                                $nochecked="";
								$yeschecked="";
                                $yesnochecked="checked='checked'";
								//$hidden="hidden";
						        $fehrestsID = "";
								$Code = $rowCode;
                            }
							
                            
            
                            
                            $Number = ($row['Number']);
                            $Number2 = ($row['Number2']);
							$Number3 = ($row['Number3']);
							$Number4 = ($row['Number4']);
							$Number5 = ($row['Number5']);
							$Number6 = ($row['Number6']);
							
						if ($Number3*$Number4*$Number5*$Number6==0)
						if ($row['Price']>0)
						if ($fehrestsmasterID==2)
						$Number6=1;
					
			
							if ($FNumber>0) $FNum=$FNumber; else $FNum=1;
							if ($Number>0) $Num=$Number; else {$Num=1;$Number='';}
							if ($Number2>0) $Num2=$Number2; else {$Num2=1;$Number2='';}
							if ($Number3>0) $Num3=$Number3; else {$Num3=1;//if ($row['Number']>0) $Number3=$row['Number']; else 
																	$Number3='';}
							if ($Number4>0) $Num4=$Number4; else {$Num4=1;$Number4='';}
							if ($Number5>0) $Num5=$Number5; else {$Num5=1;$Number5='';}
							if ($Number6!=0) $Num6=$Number6; else {$Num6=1;$Number6='';}
							
							$Price = number_format($row['Price']);
							//print $FNum.'*'.$Num2.'*'.$Num3.'*'.$Num4.'*'.$Num5.'*'.$Num6.'*'.$row['Price'];
                           $SumPrice = number_format($FNum*$Num2*$Num3*$Num4*$Num5*$Num6*$row['Price'],1);
						   $sum+=($FNum*$Num2*$Num3*$Num4*$Num5*$Num6*$row['Price']);
		
						}
                        
							
                        if (!($FNumber>0)) $FNumber=1;
                        if (!($Number2>0)) $Number2=1;
						 if ($cnt>=$np) 
                        break;
                        $cnt++;
                        
                        $rown++;
                         if (!($Code>0)) $Code=$cnt;
                       
                        
                        print "<tr>
                            <td > 
							<input type='checkbox' name='chk$cnt' value='1'>	
                            <td ><div id='divrown$cnt'>
								<input onmouseover=\"Tip('$rown')\" name='rown$cnt' type='text' class='textbox' id='rown$cnt' value='$rown' 
								style='width: 20px' maxlength='6' readonly /></div></td>
                            <td class='data' dir='ltr'>
								<div id='divrbtn$cnt' style='width: 75px'>
								<label dir='ltr' for='no' style = 'border:0px solid black;text-align:center;width: 100%;font-size:10;line-height:95%;font-weight: bold;font-family:'B Nazanin';'>کسربها</label>
								<input  onChange='calculate($cnt)' type='radio' name='mygroup$cnt' id='no$cnt' value='no' $nochecked />
								<br>
								<label  for='yes' style = 'border:0px solid black;text-align:center;width: 100%;font-size:10;line-height:95%;font-weight: bold;font-family:'B Nazanin';'>اضافه بها</label>
								<input onChange='calculate($cnt)' type='radio' name='mygroup$cnt' id='yes$cnt' value='yes' $yeschecked />
								<br>
								<label  for='yesno' style = 'border:0px solid black;text-align:center;width: 100%;font-size:10;line-height:95%;font-weight: bold;font-family:'B Nazanin';'>ستاره دار</label>
								<input onChange='calculate($cnt)' type='radio' name='mygroup$cnt' id='yesno$cnt' value='yesno' $yesnochecked />
								</div>
                            </td>
                            <td ><div id='divCode$cnt'>*<input onmouseover=\"Tip('$Code')\" name='Code$cnt' type='text' class='textbox' id='Code$cnt' value='$Code' style='width: 25px' maxlength='2'  /></div></td>
							
							 
							".select_option("fehrestsID$cnt",'',',',$fehrests,0,'','','1','rtl',0,'',$fehrestsID,'','70px',$hidden)."	
							 
							
                            <td class='data'><div id='divTitle$cnt'><input  onmouseover=\"Tip('$Title')\" name='Title$cnt' type='text' class='textbox' id='Title$cnt' value='$Title' style='width: 300px' maxlength='350'  />
							</div></td>
							
                            <td class='data'><div id='divUnit$cnt'><input  onmouseover=\"Tip('$Unit')\" name='Unit$cnt' type='text' class='textbox' id='Unit$cnt' value='$Unit' style='width: 50px' maxlength='8'  /></div></td>
                            <td class='data'><div id='divNumber$cnt'>
                            <input  onmouseover=\"Tip('$Number2')\" name='Number2$cnt' type='text' class='textbox' id='Number2$cnt' value='$Number2' 
                            size='4' maxlength='6' onchange =\"sumrow('$cnt')\" />
                            x
                            <input  onmouseover=\"Tip('$Number3')\" name='Number3$cnt' type='text' class='textbox' id='Number3$cnt' value='$Number3' 
                            size='4' maxlength='6' onchange =\"sumrow('$cnt')\" />
                              x
                            <input  onmouseover=\"Tip('$Number4')\" name='Number4$cnt' type='text' class='textbox' id='Number4$cnt' value='$Number4' 
                            size='4' maxlength='6' onchange =\"sumrow('$cnt')\" />
                              x
                            <input  onmouseover=\"Tip('$Number5')\" name='Number5$cnt' type='text' class='textbox' id='Number5$cnt' value='$Number5' 
                            size='4' maxlength='6' onchange =\"sumrow('$cnt')\" />
                              x
                            <input  onmouseover=\"Tip('$Number6')\" name='Number6$cnt' type='text' class='textbox' id='Number6$cnt' value='$Number6' 
                            size='4' maxlength='6' onchange =\"sumrow('$cnt')\" />
                           
                            </div></td>
                            <td class='data'><input  onmouseover=\"Tip('$Price')\" name='Price$cnt' type='text' class='textbox' id='Price$cnt' value='$Price' size='8' onKeyUp='convert('Price$cnt')' maxlength='15' onchange =\"sumrow('$cnt')\"  /></div></td>
                            ";
                            
                            
                        
                            if ($fehrestsmasterID==2)
                                print "<td style='display:none' class='data'><div  id='divFNumber$cnt'><input name='FNumber$cnt' type='text' class='textbox' id='FNumber$cnt' 
                                        value='$FNumber'  readonly /></div></td>";
                                        else 
                                print "<td class='data'><div id='divFNumber$cnt'><input name='FNumber$cnt' type='text' class='textbox' id='FNumber$cnt' 
                                        value='$FNumber' size='4' readonly /></div></td>";
                             
                             print "<td class='data'><div id='divSumPrice$cnt'><input  onmouseover=\"Tip('$SumPrice')\" name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice' size='12' maxlength='15' readonly /></div></td>
                            
                            <td class='data'><div id='divDescription$cnt'><input  onmouseover=\"Tip('$Description')\" name='Description$cnt' type='text' class='textbox' id='Description$cnt' value='$Description' size='15'  /></div></td>
                            
                            <td class='data'><input name='fehrestsfaslsID$cnt' type='hidden' class='textbox' id='fehrestsfaslsID$cnt'  value='$fehrestsfaslsID'  size='30' maxlength='30' /></td>
                            <td class='data'><input name='ManualListPriceID$cnt' type='hidden' class='textbox' id='ManualListPriceID$cnt'  value='$ManualListPriceID'  size='30' maxlength='30' /></td>
                            <td class='data'><input name='ApplicantMasterID$cnt' type='hidden' class='textbox' id='ApplicantMasterID$cnt'  value='$ApplicantMasterID'  size='30' maxlength='30' /></td>
                        </tr>";

                    }
					

?>                      <td class="data"><input name="retids" type="hidden" class="textbox" id="retids"  value="<?php echo $retids; ?>"  size="30" maxlength="30" /></td>
                        <td class="data"><input name="appfoundationID" type="hidden" class="textbox" id="appfoundationID"  value="<?php echo $appfoundationID; ?>"  size="30" maxlength="30" /></td>
                        <td class="data"><input name="uid" type="hidden"  id="uid"  value="<?php echo $uid; ?>"   /></td>
                        
                      
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                      
                      
                      <td colspan='4'>مجموع</td>
                      <td colspan='2' class="data"><div id="divAllSum"><input name="AllSum" type="text" class="textbox" id="AllSum" value="<?php echo number_format($sum); ?>" size="20" maxlength="20" readonly /></div></td>
                      </tr>
                      فیلدهای الزامی̽
                      
                      
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
