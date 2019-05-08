<?php 

/*

insert/manualcostlist_pluscostlist_detail2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$appsubprjID = is_numeric($_GET["appsubprjID"]) ? intval($_GET["appsubprjID"]) : 0;
$tblname='manuallistpriceall';// جدول ثبت هزینه های اجرایی طرح

if ($login_Permission_granted==0) header("Location: ../login.php");
    if ($appsubprjID>0)//زیر پروژه
        $appsubprjstr=" and manuallistpriceall.appsubprjID='$appsubprjID' ";
    else     
        $appsubprjstr=" and ifnull(manuallistpriceall.appsubprjID,0)=0 ";
$uid=$_GET["uid"];
    
	
if ($_POST)
    { 
        $appsubprjID=$_POST["appsubprjID"];
        $uid=$_POST["uid"];
 //       $retids=$_POST['retids'];
        $appfoundationID=$_POST['appfoundationID'];//سازه
        //print $appfoundationID;
        //exit;
        
        
        
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
                    $FName="../temp/".$ApplicantMasterID.'_3_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                    move_uploaded_file($_FILES["file2"]["tmp_name"],$FName);   
                    //$ApplicantMasterID=2294;
                    //echo $FName;
                    readfromexcel($FName,$ApplicantMasterID,$login_OperatorCoID,$login_DesignerCoID,0,0,0,$login_userid,
                    $appfoundationID,$fehrestsfaslsID,'mana',$fehrestsmasterID);
                    echo "بارگذاری با موفقیت انجام شد<br>";
                    $retids=$_POST['retids'];
                    header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$retids.rand(10000,99999));
                }        
             }
        }
        
        
        
        $i=0;
        while (isset($_POST['fehrestsID'.++$i]) )
        {
        	$ManualListPriceAllID = $_POST['ManualListPriceAllID'.$i];
            $ApplicantMasterID = $_POST['ApplicantMasterID'.$i];
            $fehrestsID = $_POST['fehrestsID'.$i];
            
            if (!($fehrestsID>0))
                continue;
			
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
			if ($Number6!=0 || $Number6!='') $Num6=$Number6; else $Num6=1;
			
			if ($Number3>0 || $Number4>0 || $Number5>0 || $Number6)
			$Number=$Num3*$Num4*$Num5*$Num6;
		  
		  	if ($Number>0) $Num=$Number; else $Num=1;
		
            $Description = $_POST['Description'.$i];
                
            //print $_POST['mygroup'.$i].' ';
            
            $Price= str_replace(',', '', $_POST['Price'.$i]);
            $Price= str_replace('-', '', $Price);
            $Price= str_replace('+', '', $Price);
            
            
            $_POST['chk'.$i] = $_POST['chk'.$i];
            //print $_POST['chk'.$i];
            if (($_POST['chk'.$i]==1) && ($ManualListPriceAllID != 0))
            {
                //manuallistpriceall فهارس بها
                $query = " delete from manuallistpriceall WHERE ManualListPriceAllID ='$ManualListPriceAllID' ;";
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
			if ($ManualListPriceAllID != 0)//update
            {
                //manuallistpriceall فهارس بها
        		$query = "
        		UPDATE manuallistpriceall SET
        		ApplicantMasterID = '" . $ApplicantMasterID . "', 
        		fehrestsID = '" . $fehrestsID . "',
        		Number = '" . $Number . "',  
        		Number2 = '" . $Number2 . "', 
				Number3 = '" . $Number3 . "', 
				Number4 = '" . $Number4 . "', 
				Number5 = '" . $Number5 . "', 
				Number6 = '" . $Number6 . "', 
        			Price = '" . $Price. "',  
        		nval$insertroldindex = '" . $Num2*$Num3*$Num4*$Num5*$Num6 . "',  
        		pval$insertroldindex = '" . $Price. "', 
                appsubprjID='$appsubprjID',
        		Description = '" . $Description. "',  
        		SaveTime = '" . date('Y-m-d H:i:s') . "', 
        		SaveDate = '" . date('Y-m-d') . "', 
        		ClerkID = '" . $login_userid . "'
        		WHERE ManualListPriceAllID = " . $ManualListPriceAllID . ";";
                
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
            
			 //  print $query.'<br>';
		  }
        }
    
    }
	
   $ids = substr($uid,40,strlen($uid)-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];
    $fehrestsmasterID=$linearray[1];
    $type=$linearray[2];
    $fehrestsfaslsID=$linearray[3];
    $appfoundationID=$linearray[4];
    
    //print_r($linearray);
    
    $retids=$ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$appfoundationID;

//print $retids;
    //print $CostsGroupsID;
    //exit();
    //supervisorcoderrquirement جدول تنظیمات پیکربندی
    $sql = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr = 'manualcostlistdetailnumber' and ostan='$login_ostanId'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $np = $count['ValueInt'];
    //appfoundation سازه    
    $sql = "SELECT case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber FROM appfoundation
    where appfoundationID='$appfoundationID'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $FNumber = ($count['FNumber']);
    if (!($FNumber>0)) $FNumber=1;
    /*
    ApplicantName عنوان پروژه
    CostPriceListMasterID فهرست بها
    costpricelistmaster فهرست بها
    Value سال
    applicantmaster مشخصات طرح
    */
    $sql = "SELECT ApplicantName,applicantmaster.CostPriceListMasterID,year.Value fp FROM applicantmaster 
    inner join costpricelistmaster on costpricelistmaster.CostPriceListMasterID=applicantmaster.CostPriceListMasterID
    inner join year on year.YearID=costpricelistmaster.YearID
    WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID'";
    
    $count = mysql_fetch_assoc(mysql_query($sql));
    $ApplicantName = $count['ApplicantName'];
    $CostPriceListMasterID = $count['CostPriceListMasterID'];
		$fp = $count['fp'];

    $sql = "SELECT Title FROM fehrestsmaster WHERE fehrestsmasterID = '" . $fehrestsmasterID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsfaslsTitle = $count['Title'];
    

    $sql = "SELECT Title,fasl FROM fehrestsfasls WHERE fehrestsfaslsID = '" . $fehrestsfaslsID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsmasterTitle = $count['Title'];       
    $fasl = $count['fasl'];    

    //print $sql;
    if ($appfoundationID<>0)
    $sql = "SELECT manuallistpriceall.*,case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end Price,fehrests.fehrestsID,fehrests.Code,
    fehrests.Title,fehrests.UnitTitle FROM manuallistpriceall   
    left outer join pricelistdetailall on pricelistdetailall.fehrestsID=manuallistpriceall.fehrestsID and pricelistdetailall.CostPriceListMasterID='$CostPriceListMasterID'
    inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID 
    and fehrests.fehrestsmasterID='$fehrestsmasterID' and substring(fehrests.Code,1,2)='$fasl'
    where manuallistpriceall.ApplicantMasterID = '".$ApplicantMasterID."' and manuallistpriceall.appfoundationID='$appfoundationID' $appsubprjstr
    " ;
    /*else if ($fehrestsmasterID==2)
    $sql = "
    Select distinct manuallistpriceall.ManualListPriceAllID, manuallistpriceall.ApplicantMasterID, manuallistpriceall.appfoundationID, 
    manuallistpriceall.appsubprjID, manuallistpriceall.fehrestsID, manuallistpriceall.Number, 
    manuallistpriceall.Number2, manuallistpriceall.Number3, manuallistpriceall.Number4, 
    manuallistpriceall.Number5, manuallistpriceall.Number6, 
    
    case costpricelistdetail.Price>0 when 1 then costpricelistdetail.Price else manuallistpriceall.Price end Price, 
    
    manuallistpriceall.nval1, manuallistpriceall.nval2, manuallistpriceall.nval3, 
    manuallistpriceall.pval1, manuallistpriceall.pval2, manuallistpriceall.pval3, 
    manuallistpriceall.Description
    ,fehrests.fehrestsID,fehrests.Code,fehrests.Title,fehrests.UnitTitle 
    from fehrests
    inner join gadget1 on gadget1.IsCost = 1
    inner join gadget2 on gadget2.Gadget1ID=gadget1.Gadget1ID
    inner join gadget3 on gadget3.Gadget2ID=gadget2.Gadget2ID and gadget3.code=fehrests.Code
    
    left outer join costpricelistdetail on costpricelistdetail.CostPriceListMasterID='$CostPriceListMasterID' 
    and costpricelistdetail.gadget3id=gadget3.gadget3id
    
    left outer join manuallistpriceall on fehrests.fehrestsID=manuallistpriceall.fehrestsID
    
    where manuallistpriceall.ApplicantMasterID = '".$ApplicantMasterID."'  and ifnull(manuallistpriceall.appfoundationID,0)=0 
    and fehrests.fehrestsmasterID='".$fehrestsmasterID."'
    and case costpricelistdetail.Price>0 when 1 then costpricelistdetail.Price else manuallistpriceall.Price end>0
     and substring(fehrests.Code,1,2)='$fasl' $appsubprjstr
    
    " ;*/
    
    else
    
    $sql = "SELECT manuallistpriceall.*,case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end Price,fehrests.fehrestsID,fehrests.Code,fehrests.Title,fehrests.UnitTitle FROM manuallistpriceall 
    left outer join pricelistdetailall on pricelistdetailall.fehrestsID=manuallistpriceall.fehrestsID and pricelistdetailall.CostPriceListMasterID='$CostPriceListMasterID'
    inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID
    
    where manuallistpriceall.ApplicantMasterID = '".$ApplicantMasterID."'  and ifnull(manuallistpriceall.appfoundationID,0)=0 
    and fehrests.fehrestsmasterID='".$fehrestsmasterID."' and substring(fehrests.Code,1,2)='$fasl' $appsubprjstr" ;
   
   //print $sql;
		   				  	try 
								  {		
									 	 $resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  //  print $sql;

 
	
	


?>
<!DOCTYPE html>
<html>
<head>
  	<title>سایر فهارس بها </title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
 <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>

<script type="text/javascript">

function selectpage(obj){
		window.location.href = '?uid='+document.getElementById('uid').value+'&appsubprjID=' + obj.value;
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
    //alert(document.getElementById('records').rows.length-3);
    
    var sumt=0;
		for (var i=1;i<=document.getElementById('records').rows.length-3;i++)
			{ //alert(i); 
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
				if (Num6!=0) Num6=Num6; else Num6=1;

				
				sumt += p_tarkib(document.getElementById('Price'+i).value)*
								FNum*
								Num6*1*
								Num5*1*
								Num4*1*
								Num3*1*
								Num2*1;
			}				
          
    //alert(1);
    document.getElementById('AllSum').value=numberWithCommas(Math.round(sumt));
 
}
    
function sumrow(rowNumber)
{ 
    summ();
	
	var FNum=$('input[name="FNumber'+rowNumber+'"]').val();
	var Num2=$('input[name="Number2'+rowNumber+'"]').val();
	var Num3=$('input[name="Number3'+rowNumber+'"]').val();
	var Num4=$('input[name="Number4'+rowNumber+'"]').val();
	var Num5=$('input[name="Number5'+rowNumber+'"]').val();
	var Num6=$('input[name="Number6'+rowNumber+'"]').val();
			
	
	if (FNum>0) FNum=FNum; else FNum=1;
	if (Num2>0) Num2=Num2; else Num2=1;
	if (Num3>0) Num3=Num3; else Num3=1;
	if (Num4>0) Num4=Num4; else Num4=1;
	if (Num5>0) Num5=Num5; else Num5=1;
	if (Num6!=0) Num6=Num6; else Num6=1;
	
    var x=parseFloat($('input[name="Price'+rowNumber+'"]').val().replace(/,/g, ''))*
						Num2*
						FNum*
						Num3*
						Num4*
						Num5*
						Num6*
						1;
									
		  var x=Math.round(x*100)/100;
    $('#divSumPrice'+rowNumber+' input:text ').val(numberWithCommas(x));
    $('#divSumPrice'+rowNumber).attr('onmouseover',"Tip( '"+(numberWithCommas(x)) +"')");
    x=$('input[name="Number'+rowNumber+'"]').val();  
    $('#divNumber'+rowNumber).attr('onmouseover',"Tip( '"+(x) +"')");
	
	       

}


</script> 
    <!-- /scripts -->
</head>
<body >
 	<!-- container -->
	<div id="container">
		<!-- wrapper -->
		<div id="wrapper">
<?php
				if ($_POST)
				{
					if ($register)
					{
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						/*	$Serial = "";
							$ProducersID = "";
							
							header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
							rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
							.$retids.rand(10000,99999));
							
							print "<div style = \"text-align:left;\"><a  href=\"manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).
								rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
								.$retids.rand(10000,99999)."\"><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>
								";
							exit;
						*/ 
					} else {
						echo '<p class="error">خطا در ثبت...</p>';
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
            <form id="theForm" name="theForm" action="manualcostlist_pluscostlist_detail2.php" method="post" enctype="multipart/form-data">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <td>



                <div colspan="4">
                <tr >
                    <td align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-family:'B Nazanin';"> <?php print "فهرست بهای ".$fehrestsfaslsTitle." ($fp)" ?></td>
                </tr>
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php print " فصل ".$fehrestsmasterTitle; ?> </td>
                </tr> <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php 
                    print "&nbsp; طرح آقای/خانم  &nbsp;".$ApplicantName; 
                    
                     print "
                            <td colspan='2' class='label' style = \"color: red;border:0px solid black;width: 100%;text-align:right;font-size:11.5pt;line-height:125%;font-family:'B Nazanin'; \">
                            
                        <a  target=\"_blank\"  href='../../upfolder/help/registerfahares.pdf'>
                        راهنمای بارگذاری فایل اکسل فهارس بها
						<img style = 'width: 25px' src=\"../img/help.png\" title='راهنمای بارگذاری فایل اکسل فهارس بها'></a>
                        </td>
						";
                        
                    ?> </td>
                </tr>
               
                    
                
                    
                </div>
                        	
                               <div style = "text-align:left;"><a  href=<?php print "manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .$retids.rand(10000,99999); ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            
                            <td align="left">
                            
                            <?php
                            
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
                            
                            
                ?>
                            </td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <?php
                    
                    print "<thead>
                        <tr>
                      		<th colspan=2 <span class=f8_fontb >ردیف</span> </th>
							<th <span class=f11_fontb >کد</span> </th>
							<th <span class=f11_fontb >عنوان</span> </th>
							<th <span class=f11_fontb >واحد</span> </th>
						    <th <span class=f11_fontb >مقدار<br>تعداد×طول×عرض×ضخامت/وزن×ضریب</span> </th>
						   <th <span class=f11_fontb >فی(ریال)</span> </th>
						         ";
                    if ($fehrestsmasterID==2) 
                        print "<th style='display:none'>تعداد<br> سازه</th>";
                        else 
                        print "<th <span class=f10_fontb >تعداد<br> سازه</span> </th>";
                    print "
							<th <span class=f11_fontb >جمع(ریال)</span> </th>
							<th <span class=f11_fontb >توضیح</span> </th>
						
                        </tr>
                    </thead>
                   <tbody>";
                    
                    
                    $cnt=0;
                    $rown=0;
                    $sum=0;
                    
                    
                    $query="SELECT fehrestsID AS _value, CONCAT(substring(Title,1,150),'.... : (',Code,')') AS _key
						FROM fehrests
						WHERE fehrestsmasterID='$fehrestsmasterID' and substring(fehrests.Code,1,2)='$fasl'
                        ORDER BY Title COLLATE utf8_persian_ci
                        ";
    				 $fehrests = get_key_value_from_query_into_array($query);
                     
                     //print $query;
                     
                    if ($resultwhile)
                    while($row = mysql_fetch_assoc($resultwhile)){
                            
                        $ManualListPriceAllID = 0;
                        $pricelistdetailallID = 0;
                        $fehrestsID=0;                        
                        $Number = '';
                        $Number2 = '';
                        $Number3 = '';$Number4 = ''; $Number5 = ''; $Number6 = '';
                        $Price='';
                        
                        $Code = '';
                        $Title = '';
                        $UnitTitle = '';
                        $SumPrice='';
                        $nochecked="";
                        $yeschecked="checked='checked'";
                        $Description="";
                       // $Number2=1;
                        
                        if ($row)
                        {
                            $ManualListPriceAllID = $row['ManualListPriceAllID'];
                            $fehrestsID = $row['fehrestsID'];
                            $Description = $row['Description'];
                            $Code = $row['Code'];
                            $Title = $row['Title'];
                            $UnitTitle = $row['UnitTitle'];
                            
                           // $Number2 = ($row['Number2']);
                          //  if (!($Number2>0)) $Number2=1;
							
                            $Number2 = ($row['Number2']);
							$Number3 = ($row['Number3']);
							$Number4 = ($row['Number4']);
							$Number5 = ($row['Number5']);
							$Number6 = ($row['Number6']);
							
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
						   $sum+=$FNumber*$Num2*$Num3*$Num4*$Num5*$Num6* $row['Price'];
					       }
						   
                      //  print $SumPrice;exit;
						   	 if ($row['price']>0) $readonly="readonly"; else $readonly="";
                  
                        if (!($Number2>0)) $Number2=1;
                        
                        if ($cnt>=$np) 
                        break;
                        $cnt++;
                        
                        $rown++;
?>
                        <tr>
                            <td > 
                            <input type="checkbox" name="chk<?php echo $cnt; ?>" value="1">
                            <td ><div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 20px' maxlength="6" readonly /></div></td>
                            
                            
                            
                            <td ><div id="divCode<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$Code.')'; ?>)"
                             name="Code<?php echo $cnt; ?>" type="text" class="textbox" id="Code<?php echo $cnt; ?>" value="<?php echo $Code; ?>" style='width: 55px' maxlength="8"  /></div></td>
                            
                            <?php 
                            print select_option("fehrestsID$cnt",'',',',$fehrests,0,'','disaled','1','rtl',0,'',$fehrestsID,
                            "onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]$home_path_iri/insert/invoice_list_jr.php','$cnt',2);\""
                            ,'350');
                            ?>
                            
                            <td class="data"><div id="divUnitTitle<?php echo $cnt; ?>"><input  onmouseover="Tip(<?php echo '(\''.$UnitTitle.'\')'; ?>)" name="UnitTitle<?php echo $cnt; ?>" type="text" class="textbox" id="UnitTitle<?php echo $cnt; ?>" value="<?php echo $UnitTitle; ?>" style='width: 50px' maxlength="8"  /></div></td>
                            <td class="data"><div id="divNumber<?php echo $cnt; ?>">
                            
                            <input onmouseover="Tip(<?php echo '(\''.$Number2.'\')'; ?>)" name="Number2<?php echo $cnt; ?>" type="text" class="textbox"
                             id="Number2<?php echo $cnt; ?>" value="<?php echo $Number2; ?>" size="4" maxlength="6"
                             <?php echo "onchange = \"sumrow('$cnt');\"" ?>/>
                           x
                            <input onmouseover="Tip(<?php echo '(\''.$Number3.'\')'; ?>)" name="Number3<?php echo $cnt; ?>" type="text" class="textbox"
                             id="Number3<?php echo $cnt; ?>" value="<?php echo $Number3; ?>" size="4" maxlength="6"
                             <?php echo "onchange = \"sumrow('$cnt');\"" ?>/> 
							<!-- ضخامت -->
							
                           x
                            <input onmouseover="Tip(<?php echo '(\''.$Number4.'\')'; ?>)" name="Number4<?php echo $cnt; ?>" type="text" class="textbox"
                             id="Number4<?php echo $cnt; ?>" value="<?php echo $Number4; ?>" size="4" maxlength="6"
                             <?php echo "onchange = \"sumrow('$cnt');\"" ?>/>  
                              <!-- عرض-->
                              
                            
							x
                            <input onmouseover="Tip(<?php echo '(\''.$Number5.'\')'; ?>)" name="Number5<?php echo $cnt; ?>" type="text" class="textbox"
                             id="Number5<?php echo $cnt; ?>" value="<?php echo $Number5; ?>" size="4" maxlength="6"
                             <?php echo "onchange = \"sumrow('$cnt');\"" ?>/>  
                            <!-- طول -->
							
							x
                            <input onmouseover="Tip(<?php echo '(\''.$Number6.'\')'; ?>)" name="Number6<?php echo $cnt; ?>" type="text" class="textbox"
                             id="Number6<?php echo $cnt; ?>" value="<?php echo $Number6; ?>" size="4" maxlength="6"
                             <?php echo "onchange = \"sumrow('$cnt');\"" ?>/>  
							 <!-- ضریب -->
                           </div></td>
                          
							
                            <td class="data"><div id="divPrice<?php echo $cnt; ?>">
                            <input  <?php echo $readonly;?> onmouseover="Tip(<?php echo '(\''.$Price.'\')'; ?>)" name="Price<?php echo $cnt; ?>" 
                            type="text" class="textbox" id="Price<?php echo $cnt; ?>" value="<?php echo $Price; ?>" 
                            size="8" onKeyUp="convert('Price<?php echo $cnt; ?>')" maxlength="15" <?php echo "onchange = \"sumrow('$cnt');\"" ?>  /></div></td>
                            
                            <?php 
                            if ($fehrestsmasterID==2)
                            print "<td style='display:none' class='data'><div id='divFNumber$cnt'><input  
                             name='FNumber$cnt' type='text' class='textbox' id='FNumber$cnt' value='$FNumber' size='4' readonly /></div></td>";
                             else 
                            print "<td class='data'><div id='divFNumber$cnt'><input  
                             name='FNumber$cnt' type='text' class='textbox' id='FNumber$cnt' value='$FNumber' size='4' readonly /></div></td>";
                            
                            print "
                            <td class='data'><div id='divSumPrice$cnt'><input  onmouseover=\"Tip('$SumPrice')\" name='SumPrice$cnt' type='text' class='textbox' id='SumPrice$cnt' value='$SumPrice' size='12' maxlength='15' readonly /></div></td>
                            <td class='data'><div id='divDescription$cnt'><input  onmouseover=\"Tip('$Description')\" name='Description$cnt' type='text' class='textbox' id='Description$cnt' value='$Description' size='30'  /></div></td>
                            ";
                             ?>
                            
                            <td class="data"><input name="ManualListPriceAllID<?php echo $cnt; ?>" type="hidden" class="textbox" id="ManualListPriceAllID<?php echo $cnt; ?>"  value="<?php echo $ManualListPriceAllID; ?>"  size="30" maxlength="30" /></td>
                            <td class="data"><input name="ApplicantMasterID<?php echo $cnt; ?>" type="hidden" class="textbox" id="ApplicantMasterID<?php echo $cnt; ?>"  value="<?php echo $ApplicantMasterID; ?>"  size="30" maxlength="30" /></td>
                            
                        </tr><?php

                    }

?>
                        <td class="data"><input name="retids" type="hidden" class="textbox" id="retids"  value="<?php echo $retids; ?>"  size="30" maxlength="30" /></td>
                        <td class="data"><input name="appfoundationID" type="hidden" class="textbox" id="appfoundationID"  value="<?php echo $appfoundationID; ?>"  size="30" maxlength="30" /></td>
                        <td class="data"><input name="uid" type="hidden"  id="uid"  value="<?php echo $uid; ?>"   /></td>
                        
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                      
                      <td colspan='4'>مجموع (ریال)</td>
                      <td colspan='2' class="data"><div id="divAllSum"><input name="AllSum" type="text" class="textbox" id="AllSum" 
					  value="<?php echo number_format($sum,1); ?>" size="20" maxlength="20" readonly /></div></td>
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
