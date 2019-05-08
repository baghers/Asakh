<?php 

/*

//appinvestigation/contractfree_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/contractfree_delete.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST)
{

        $designercocontractID=$_POST['designercocontractID'];//شناسه جدول قرارداد
        $freestateID=$_POST['freestateID'];//شناسه آزادسازی
        $designercoID=$_POST['designercoID'];//طراح
        $type=$_POST['type'];//نوع  
     
        $Price = str_replace(',', '', $_POST['Price']);
        $CheckNo=$_POST['CheckNo'];//شماره چک
        $letterdate=$_POST['letterdate'];//تاریخ
        $letterno=$_POST['letterno'];//شماره نامه
        $CheckDate=$_POST['CheckDate'];//تاریخ چک
        $CheckBank=$_POST['CheckBank'];//بانک
        $Description=$_POST['Description'];//شرح
        $AccountBank=$_POST['AccountBank'];//بانک صاحب حساب
        $AccountNo=$_POST['AccountNo'];//شماره حساب
            
        $SaveTime=date('Y-m-d H:i:s');//زمان
        $SaveDate=date('Y-m-d');//تاریخ
        $ClerkID=$login_userid;//کاربر
		/*
        contractfree قرارداد آزادسازی
        contractfreeID شناسه قرارداد
        designercoID طراح
        Price مبلغ
        */	
        $query1 = "SELECT contractfreeID FROM contractfree 
        where freestateID='$freestateID' and designercoID='$designercoID' and Price='$Price'";
        $result1 = mysql_query($query1);
  		$row1 = mysql_fetch_assoc($result1);
        if ($row1['contractfreeID']>0)
            $isdup =1;
        else $isdup=0;
                        
                
        if ($isdup==0)      
        if ($_POST['true']>0)    
        if ($designercocontractID>0)
        {
            /*
            contractfree قرارداد آزادسازی
            contractfreeID شناسه قرارداد
            designercoID طراح
            Price مبلغ
            $designercocontractID=$_POST['designercocontractID'];//شناسه جدول قرارداد
            $freestateID=$_POST['freestateID'];//شناسه آزادسازی
            $designercoID=$_POST['designercoID'];//طراح
            $type=$_POST['type'];//نوع  
            $Price = str_replace(',', '', $_POST['Price']);
            $CheckNo=$_POST['CheckNo'];//شماره چک
            $letterdate=$_POST['letterdate'];//تاریخ
            $letterno=$_POST['letterno'];//شماره نامه
            $CheckDate=$_POST['CheckDate'];//تاریخ چک
            $CheckBank=$_POST['CheckBank'];//بانک
            $Description=$_POST['Description'];//شرح
            $AccountBank=$_POST['AccountBank'];//بانک صاحب حساب
            $AccountNo=$_POST['AccountNo'];//شماره حساب
            */	
            $query ="INSERT INTO contractfree
            (AccountBank,AccountNo,designercocontractID,freestateID,designercoID,Price,CheckNo,letterdate,letterno,CheckDate,CheckBank,Description,SaveTime,SaveDate,ClerkID)
             values('$AccountBank','$AccountNo','$designercocontractID','$freestateID','$designercoID','$Price','$CheckNo','$letterdate','$letterno','$CheckDate'
             ,'$CheckBank','$Description','$SaveTime','$SaveDate','$ClerkID')";
                
            $result = mysql_query($query);
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

            print "<p class='note'>ثبت با موفقیت انجام شد<p>";
            //exit;
			   
				
				  if ($_FILES["filep"]["error"] > 0) 
				{
					//echo "Error: " . $_FILES["file2"]["error"] . "<br>";
				} 
				else 
				{
  		            /*
                    contractfree قرارداد آزادسازی
                    contractfreeID شناسه قرارداد
                    designercoID طراح
                    Price مبلغ
                    */
				     $query = "SELECT contractfreeID FROM contractfree where contractfreeID = last_insert_id() and SaveTime='$SaveTime' 
                            and ClerkID='$ClerkID'";
                        
                        $result = mysql_query($query);
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                  		$row = mysql_fetch_assoc($result);
                        
						$IDUser =$row['contractfreeID'];
						$path = "../../upfolder/cfree/";
			
					 if (($_FILES["filep"]["size"] / 1024)>100)
					{
						print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
					}
						$ext = end((explode(".", $_FILES["filep"]["name"])));
						$attachedfile=$IDUser.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
						//print $path.$attachedfile;
						foreach (glob($path. $IDUser.'_1*') as $filename) 
						{
							unlink($filename);
						}move_uploaded_file($_FILES["filep"]["tmp_name"],$path.$attachedfile);
					
				}
		$_POST['true']=0; 
		}
		
		
		
}
else 
{
    $uid=$_GET["uid"];

		 	
$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$designercocontractID=$linearray[0];//شناسه جدول قرارداد
$type=$linearray[1];//نوع
$DesignerCoID=$linearray[2];//طراح
$OperatorCoID=$linearray[3];//مجری
$ProducersID=$linearray[4];//تولید کننده
    //if (!($OperatorCoID>0)) header("Location: ../login.php");   
    $g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;
}
/*
designercocontract جدول قراردادها
designercocontractID شناسه جدول قرارداد
contracttype جدول نوع قرارداد
designerco شرکت طراح
prjtype نوع پروژه

*/
 $sql="SELECT designercocontract.designercocontractID,
	designercocontract.Title,designercocontract.area,designercocontract.contractDate,designercocontract.price,designercocontract.duration,designercocontract.No
	,designerco.Title designercoTitle,designerco.DesignerCoID
	,prjtype.Title prjtypeTitle,prjtype.prjtypeid
	,contracttype.Title contractTitle,contracttype.contracttypeID
	FROM `designercocontract`
    left outer join  contracttype on contracttype.contracttypeID=designercocontract.contracttypeID 
    left outer join  designerco on designerco.DesignerCoID=designercocontract.DesignerCoID 
    left outer join  prjtype on prjtype.prjtypeid=designercocontract.prjtypeid 
    where designercocontract.designercocontractID='$designercocontractID'";

//print $sql;

$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$row = mysql_fetch_assoc($result);
$designercoID=$row['DesignerCoID'];
$ApplicantName =$row['No']." مورخ ".$row['contractDate']." مشاور ".$row['designercoTitle'];
 
 $cond="";
if ($g2id>0) $cond.=" and freestateID='$g2id' ";
  
  $cond.="and contractfree.designercocontractID='$designercocontractID'";
  /*
  contractfree جدول قرارداد آزادسازی
  designerco جدول طراح
  DesignerCoID شناسه طراح
  
  */
$sql="SELECT 
Price,CheckNo,letterdate,letterno,CheckDate,CheckBank,Description,contractfree.freestateID,contractfree.AccountNo
,contractfree.AccountBank,contractfree.contractfreeID,contractfree.designercocontractID 
,designerco.DesignerCoID

,case contractfree.DesignerCoID when -1 then 'حسن تعهدات' when -2 then 'تامین اجتماعی' when -3 then 'دارایی' 
else designerco.Title end designercoTitle

,case contractfree.freestateID when 141 then 'وضعیت یک' when 142 then 'وضعیت دو' when 143 then 'وضعیت سه' when 144 then 'وضعیت چهار'
when 145 then 'حسن انجام کار'  when 146 then 'پیش پرداخت' end freestateTitle


 FROM contractfree

 
 left outer join  designerco on designerco.DesignerCoID=contractfree.DesignerCoID 
  where 1=1  $cond     

        "; 
//print $sql;
$result = mysql_query($sql);
						try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }


$sqlselect="select distinct freestateTitle _key,freestateID _value from ($sql)as view1 order by _key  COLLATE utf8_persian_ci";
$allg2id = get_key_value_from_query_into_array($sqlselect);
						try 
							  {		
								mysql_query($sqlselect);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پرداختی ها</title>

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
 <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#CheckDate, #simpleLabel").persiandatepicker();  
                $("#letterdate, #simpleLabel").persiandatepicker();   
				
            });
        
        
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
    function selectpage(){
       
        window.location.href ='?uid=' +document.getElementById('uid').value
        + '&g2id=' + document.getElementById('g2id').value;
        
	}
    
 function fillform(Url)
    {      
        var type=0,ID=0,Price;
        if (document.getElementById('designercoID').value==-3)
        {
            type=3;//کشاورز
            ID=document.getElementById('designercocontractID').value;
        }
        if (document.getElementById('designercoID').value==-2)
        {
            type=3;//کشاورز
            ID=document.getElementById('designercocontractID').value;
        }
        if (document.getElementById('designercoID').value==-1)
        {
            type=2;//مجری
            ID=document.getElementById('designercoID').value;
        }
        if (document.getElementById('designercoID').value>0)
        {
            type=1;//فروشنده
            ID=document.getElementById('designercoID').value;
        }
        Price=document.getElementById('Price').value;
            //alert(type);
            //alert(ID);
        if (ID>0)
        {
            
            $("#loading-div-background").show();
            $.post(Url, {type:type,ID:ID,Price:Price}, function(data){
            $("#loading-div-background").hide();  
            if (data.errors==1) alert("تاریخ انقضاء ضمانت ثبت نشده است");   
            if (data.errors==2) alert("ضمانت به انقضا رسیده است");  
            if (data.errors==3) alert("مبلغ وارد شده بیشتر از مبلغ  ضمانت پرداختی "+data.guaranteepayval+" می باشد");  
            if (data.errors==4) alert("کمتر از ده روز به انقضاء ضمانت مانده است");
                       
            $('#AccountNo').val(data.AccountNo);
            $('#AccountBank').val(data.AccountBank);
            }, 'json');                           
        }
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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="contractfree_list.php" method="post" enctype="multipart/form-data">
             <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                <table width="95%" align="center">
                    <tbody>
                    <h1 align="center">  لیست آزادسازی های انجام شده  قرارداد شماره <?php print $ApplicantName; ?> </h1>
                        
                        
                        <tr>
                        
                        <div style = "text-align:left;">

				       <?php 
              
				   $href=$_SERVER['HTTP_REFERER'];
				   $href='../reports/reports_contract.php';
				   
                   print "<a  href='$href'><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت'></a>";
                    
                     ?>
         
						
               
               </div>
               
                          <INPUT type="hidden" id="designercoID" name="designercoID" value="<?php print $designercoID; ?>"/>
                          <INPUT type="hidden" id="designercocontractID" name="designercocontractID" value="<?php print $designercocontractID; ?>"/>
                          <INPUT type="hidden" id="type" name="type" value="<?php print $type; ?>"/>
                          <INPUT type="hidden" id="type" name="type" value="<?php print $type; ?>"/>
                          <INPUT type="hidden" id="true" name="true" value="1"/>
                          
                          
                          <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!-- div style = "text-align:left;">
                            <button title='افزودن طرح جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div -->
                          
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr style='color:0000ff; background-color: #B2FFB7'>
                        
                            <th ></th>
                            <th width="5%">مرحله آزادسازی</th>
                        	<th width="10%">دریافت کننده</th>
                            <th width="15%">مبلغ(ریال)</th>
                            <th width="15%">ش حساب دریافت کننده</th>
                            <th width="20%">بانک دریافت کننده</th>
                            <th width="10%">ش چک صادره</th>
                            <th width="5%">تاریخ</th>
                            <th width="5%">بانک</th>
                            <th width="35%">توضیحات</th>
                            <th width="35%">تاریخ</th>
                            <th width="35%">شماره</th>
                            <th width="35%">نامه آزادسازی</th>
                            <th ></th>
                        </tr>
                   
                        
                    
                                
   <?php

     $permitrolsid2 = array("16","7","1");
     if (in_array($login_RolesID, $permitrolsid2))  $readonly=''; else $readonly='readonly';
   
   $permitrolsid = array("16","19","7","13","14");
  
 // if ($type==5 && (in_array($login_RolesID, $permitrolsid)))
 //    {          
       $query=" 
       select designerco.designercoID as _value,designerco.Title as _key 
       from designerco 
       where designerco.designercoID='$designercoID'
       union all select -1 as _value, 'حسن تعهدات' _key
        union all select -2 as _value, 'تامین اجتماعی' _key
        union all select -3 as _value, 'دارایی' _key
                         order by _value desc";
	$alldesignercoID = get_key_value_from_query_into_array($query);

//	}
	
  //  if (($type==1 || $type==5) && (in_array($login_RolesID, $permitrolsid)))
  //   {          
  
  $query="
select 'وضعیت یک' _key,141 as _value union all
select 'وضعیت دو' _key,142 as _value union all 
select 'وضعیت سه' _key,143 as _value union all
select 'وضعیت چهار' _key,144 as _value union all
select 'حسن انجام کار' _key,145 as _value union all
select 'پیش پرداخت' _key,146 as _value ";

     //  $query='select freestateID as _value,Title as _key from freestate order by Code';
       $allfreestateID = get_key_value_from_query_into_array($query);
                                               
        print "<tr><td/>".select_option('freestateID','',',',$allfreestateID,141,'','','1','rtl',0,'','','','60').
        select_option('designercoID','',',',$alldesignercoID,0,'','','1','rtl',0,'',0,"","120")."
        <td class='data'><input onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/appinvestigation/guarantee_level1_jr.php');\" 
        name='Price' type='text' class='textbox' id='Price'   size='15' maxlength='50' onKeyUp=\"convert('Price')\"/></td>
        <td class='data'><input name='AccountNo' type='text' class='textbox' id='AccountNo' size='10'   maxlength='50' /></td>
        <td class='data'><input name='AccountBank' type='text' class='textbox' id='AccountBank'  size='15'  maxlength='60' /></td>
        <td class='data' ><input name='CheckNo' type='text' class='textbox' id='CheckNo' size='8'   maxlength='50' $readonly /></td>
        <td class='data'><input   name='CheckDate' type='text' class='textbox' id='CheckDate'  size='6' maxlength='10' /></td>
        <td class='data'><input name='CheckBank' type='text' class='textbox' id='CheckBank'  value='کشاورزی' size='8' maxlength='50' /></td>
        <td class='data'><input name='Description' type='text' class='textbox' id='Description' size='16'   maxlength='90' /></td>                  
		
        <td class='data' ><input name='letterdate' type='text' class='textbox' id='letterdate' size='6'   maxlength='50'  /></td>
        <td class='data' ><input name='letterno' type='text' class='textbox' id='letterno' size='6'   maxlength='50'  /></td>
		<td class='data'><input name='filep' type='file' class='textbox' id='filep'   style='width:150px' /></td>                  
		
		
        <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
		
        <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID' value='$ApplicantMasterID' /></td>  
		
        </tr>
        <tr>";
        
  //   }    
                    
    //print select_option('g2id','',',',$allg2id,0,'','','2','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
    print
                    '</tr>
                    
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>';
         
                     
                    
                    $cnt=0;
                    $prefreestatecode=1;
                    $sum=0;
					$sumt=0;
                    while($row = mysql_fetch_assoc($result)){
					    $sumt+=$row['Price'];
                        $cnt++;
                        if ($prefreestatecode<>$row['freestatecode'])
                        {
							print "
                            <tr>
							<td colspan=3 style='color:0000ff; background-color: #B2FFB7'> مجموع ".  $freestateTitle. "</td>
                            <td colspan=11 style='color:0000ff; background-color: #B2FFB7'>".number_format($sum)."</td>
                            </tr>";
                            $sum=0;
                            $prefreestatecode=$row['freestatecode'];
							
                        }
                        $sum+=$row['Price'];
                        $freestateTitle=$row['freestateTitle'];
						
						
						   
					$fstr1='';
					$IDUser =$row['contractfreeID'];
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/cfree/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                     {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDU=$linearray[0];
                            $No=$linearray[1];
							$num=$linearray[2];
				            if (($IDU==$IDUser)  )
                                $fstr1="<a href='../../upfolder/cfree/$file' target='_blank' >
                                        <img name='file1img' id='file1img' style = 'width: 25px;' src='../img/accept.png' title='اسکن' ></a>";
                                    
                            
                            
			            }
				     }

   
   
   
   
						
						
						
?>                     
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $row['freestateTitle']; ?></td>
                            <td><?php echo $row['designercoTitle'];//str_replace(' ', '&nbsp;', $row['designercoTitle']); ?></td>
                            <td><?php echo number_format($row['Price']); ?></td>
                            <td><?php echo $row['AccountNo']; ?></td>
                            <td><?php echo $row['AccountBank']; ?></td>
                            <td><?php echo $row['CheckNo']; ?></td>
                            <td><?php echo $row['CheckDate']; ?></td>
                            <td><?php echo $row['CheckBank']; ?></td>
                            <td><?php echo $row['Description']; ?></td>
                            <td><?php echo $row['letterdate']; ?></td>
                            <td><?php echo $row['letterno']; ?></td>
                            <?php 
							print "<td class='no-print'>";	
                           $permitrolsid = array("16", "19","7","13","14","1");
	                 
		 if (!$row['CheckNo'] || (in_array($login_RolesID, $permitrolsid2))) 
                    {	
                          //   if (($type==1 || $type==5) && (in_array($login_RolesID, $permitrolsid)))
					        print "<a target='_blank'
                            href='contractfree_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['contractfreeID'].'_'.$designercocontractID.'_'.$designercoID.rand(10000,99999).
                            "'
                            onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 20px;' src='../img/delete.png' title='حذف'> </a>"; ?>
                            <?php 
                             
                            if (in_array($login_RolesID, $permitrolsid))
                             {
                                print "<a target='_blank' href='invoicemasterfree_edit.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$row['contractfreeID'].'_'.$designercocontractID.'_'.
                                $designercoID.'_'.$designercoTitle.rand(10000,99999).
                                "'><img style = 'width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a>";
                            
                             }
                     }
                        print "$fstr1</td><td></td>";           
                             ?>
                            
                            
                        </tr><?php

                    }
					    
                    if ($prefreestatecode<>$row['freestatecode'])
                    {
					    print "
                            <tr  >
                            <td colspan=3 style='color:0000ff; background-color: #B2FFB7' >مجموع ".$freestateTitle."</td>
                            <td colspan=11 style='color:0000ff; background-color: #B2FFB7'>".number_format($sum)."</td>
                            </tr>";
							
                    }
                        

?>

                        
                   
                    </tbody>
					<?php   print "
                            <tr >
                            <td colspan=3 style=color:009900 >مجموع آزادسازی</td>
                            <td colspan=11 style=color:009900>".number_format($sumt)."</td>
                            </tr>";
							
					if (in_array($login_RolesID, $permitrolsid2))		
				    print "   
                <tr></tr><tr><td colspan=7></td><td colspan=7 style=color:CC6666>
				درصورت تکمیل اطلاعات و ثبت چک صادره، امکان تغییرات توسط مدیریت آب و خاک وجود نخواهد داشت.</td></tr>";
				   
				   ?>
                </table>
				
				
				<tr><td > <?php echo '<font color=\"aa0000\"></font>';   ?></td></tr>
				</br>
                <tr><td > <?php echo '<font color=\"000000\"></font>';   ?></td></tr>
				   
				
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form>   
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
