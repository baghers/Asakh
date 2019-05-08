<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
include('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی امال صفحات 
include('../includes/elements.php');
  
   




if ($_POST )
{
     if (!($login_userid>0)) header("Location: ../login.php");
     //$login_RolesID 19 شناسه مدیریت پرونده ها
     //$login_RolesID 24 نقشه بردار
     //$login_OperatorCoID شناسه پیمانکار لاگین شده
     //$login_DesignerCoID شناسه شرکت مشاور طراح لاگین شده
     
     //مدیریت پرونده ها و نقشه بردار امکان ثبت طرح را ندارند
      if ($login_RolesID<>24 && $login_RolesID<>19)
      //در صورتی که شناسه پیمانکار یا مشاور طراح حد اقل یکی مشخص نباشد از این صفحه خارج می شود
        if (!($login_OperatorCoID>0) && !($login_DesignerCoID>0) ) header("Location: ../login.php");
    
    //$_POST['sob'] شناسه شهرستان محل اجرای طرح
    if (!($_POST['sob']>0))
    {
            echo "شهرستان محل اجرای طرح را انتخاب نمایید";
            exit;
    } 
    
	   
    
    $DesignArea=$_POST['DesignArea'];//مساحت طرح
	$Debi=$_POST['Debi'];//دبی طرح
	$DesignSystemGroupsID=$_POST['DesignSystemGroupsID'];//شناسه سیستم آبیاری طرح
	$CityId=$_POST['sob'];
    
	$CountyName="$_POST[CountyName]";//روستای طرح
    
    
        
    $YearID=$_POST['YearID'];//سال اجرای طرح
    $SaveTime=date('Y-m-d H:i:s');//زمان
    $SaveDate=date('Y-m-d');//تاریخ
    $ClerkID=$login_userid;//کاربر ثبت
    //در صورتی که کاربر لاگین نکرده باشد یا جلسه کاری آن به پایان رسیده باشد
     if (!($login_userid>0)) header("Location: ../login.php");
    
    //پرس و جوی درج طرح در پایگاه داده
    $query = "INSERT INTO applicantmaster(Debi,DesignArea,SaveTime,SaveDate,ClerkID
    ,CityId,CountyName,YearID,DesignSystemGroupsID) 
    VALUES('$Debi','$DesignArea','$SaveTime','$SaveDate','$ClerkID','$CityId','$CountyName','$YearID','$DesignSystemGroupsID');";
    
        //print $query;
    //exit;
    try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
      
        //پرس و جوی استخراج شناسه طرح درج شده
        $query = "SELECT ApplicantMasterID FROM applicantmaster 
                    where ApplicantMasterID = last_insert_id()";
        try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
		
		$row = mysql_fetch_assoc($result);
    //  print $query;
    if ($row['ApplicantMasterID']>0)
    {  
        $query = "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,SaveTime,SaveDate,ClerkID) VALUES('" .
            $row['ApplicantMasterID'] . "',1,23, '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
        $temp_array = array('error' => '0');

        //  print $query;
        $result = mysql_query($query); 
        
        $query = "update applicantmasterdetail set prjtypeid='$_POST[prjtypeid]' where ApplicantMasterID='$row[ApplicantMasterID]'";

        $result = mysql_query($query);
         
        
        echo "طرح مورد نظر با کد ثبت سامانه".$row['ApplicantMasterID']." با موفقیت ثبت شد";
    }
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح مشخصات طرح</title>
<meta http-equiv="X-Frame-Options" content="deny" />
	
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


    <!-- /scripts -->
    
    <script >
    
    
    
function CheckForm()
{

    if ($('#DesignArea').length > 0)
    if (!(document.getElementById('DesignArea').value.length>0))
    {
        alert('مساحت طرح را وارد نمایید!');return false;
    }
    if ($('#Debi').length > 0)
    if (!(document.getElementById('Debi').value.length>0))
    {
        alert('دبی طرح را وارد نمایید!');return false;
    }
    if ($('#soo').length > 0)
    if (!(document.getElementById('soo').value>0))
    {
        alert('استان طرح را وارد نمایید!');return false;
    }
    if ($('#sos').length > 0)
    if (!(document.getElementById('sos').value>0))
    {
        alert('شهرستان طرح را وارد نمایید!');return false;
    }
    
    if ($('#sob').length > 0)
    if (!(document.getElementById('sob').value>0))
    {
        alert('شهر/بخش طرح را وارد نمایید!');return false;
    }
    
    if ($('#CountyName').length > 0)
    if (!(document.getElementById('CountyName').value.length>0))
    {
        alert('روستای طرح را وارد نمایید!');return false;
    }
    
    
    if ($('#DesignSystemGroupsID').length > 0)
    if (!(document.getElementById('DesignSystemGroupsID').value>0) && !(document.getElementById('DesignSystemGroupsID').value==-1))
    {
        alert('سیستم آبیاری طرح را وارد نمایید!');return false;
    }
    
    
  return true;
}
    
    
function FilterComboboxes(Url,Tabindex)
{ 
    //alert(1);
    var selectedCostPriceListMasterID;
    //alert(<?php print $login_ostanId; ?>);
    if ($('#CostPriceListMasterID').length > 0)
        selectedCostPriceListMasterID=document.getElementById('CostPriceListMasterID').value;
    if (selectedCostPriceListMasterID>0)
    selectedCostPriceListMasterID=selectedCostPriceListMasterID;
    else
    selectedCostPriceListMasterID=0;
    $.post(Url, {ostanid:<?php print $login_ostanId; ?>,selectedCostPriceListMasterID:selectedCostPriceListMasterID}, function(data){
    //alert (data.val1);
           
               
           if ($('#divTransportCostTableMasterID').length > 0)
           {
            if (selectedCostPriceListMasterID>0)
	           $('#divTransportCostTableMasterID').html(data.val2);
           }
       }, 'json');                      
}
function FilterComboboxes2(Url,Tabindex)
{ 
    //alert(2);
    var selectedsoo=document.getElementById('soo').value;
    var selectedsos=document.getElementById('sos').value;
    <?php if($login_RolesID==17) echo 'selectedsos='.$login_CityId;?>
    //alert(selectedsos);
    
    $.post(Url, {selectedsoo:selectedsoo,ostanid:<?php print $login_ostanId; ?>,selectedsos:selectedsos}, function(data){
    //alert (data.val1);
           
    $('#divsos').html(data.val0);
    $('#divsob').html(data.val1);
               
          
       }, 'json');                      
}


function FilterComboboxes3(Url,Tabindex)
{ 
    var type=1;
    var melicode=document.getElementById('melicode').value;
    $.post(Url, {type:type,melicode:melicode}, function(data){
        if (!(data.val2>0))
            alert('کد/شناسه ملی یافت نشد. لطفا از منوی ثبت کشاورز مشخصات متقاضی را ثبت نمایید');
    //alert (data.val0);
    document.getElementById('ApplicantFName').value=data.val0;
    document.getElementById('ApplicantName').value=data.val1;
    document.getElementById('shenasnamecode').value=data.val2;
    document.getElementById('registerplace').value=data.val3;
    document.getElementById('fathername').value=data.val4;
    document.getElementById('birthdate').value=data.val5;
    document.getElementById('mobile').value=data.val6;    
       }, 'json');                      
}
    </script>
</head>
<body>

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
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="applicant_edit.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data" >
                 <?php require_once('../includes/csrf_pag.php'); ?>
                   <table width="650" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "applicant_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                    
                     <?php
 
                     
                    
                        //if($login_RolesID==17) $heklbl='متراژ'; else 
						$heklbl='مساحت (هکتار)'; 
                        print "<td   class='label'>$heklbl:</td>
                      <td class='data'><input value='$DesignArea' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='DesignArea' type='text' class='textbox' id='DesignArea'   /></td>
                      <td  class='label'>دبی L/s:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='Debi'  value='$Debi' type='text' class='textbox' id='Debi'    /></td>";
                     
					 
                    	 $query="SELECT DesignSystemGroupsID AS _value, Title AS _key FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 _value, 'قطره اي/ باراني' _key";
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td id='DesignSystemGroupsIDlbl'  class='label'>سیستم آبیاری:</td>".
                         select_option('DesignSystemGroupsID','',',',$ID,0,'','','1','rtl',0,'',$DesignSystemGroupsID,'','100');
                     
				    print "</tr><tr>";
					 

                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' 
                    and  substring(id,1,2)=substring('$login_CityId',1,2) order by _key  COLLATE utf8_persian_ci";
    				 $ID1 = get_key_value_from_query_into_array($query);
                    
                    
    if($login_RolesID==17) 
    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring($login_CityId,1,4)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    
    else
                    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($soo,1,2)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    				 $ID2 = get_key_value_from_query_into_array($query);
                    
                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring('$sob',1,4)
        and substring(id,6,2)='00' order by _key  COLLATE utf8_persian_ci ";
    				 $ID3 = get_key_value_from_query_into_array($query);
                    
                     print select_option('soo','استان',',',$ID1,0,'','','1','rtl',0,'',$soo,"",'135').
                     select_option('sos','دشت/شهرستان',',',$ID2,0,'','','1','rtl',0,'',$sos,"onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'80').
                     select_option('sob','شهر/بخش',',',$ID3,0,'','','1','rtl',0,'',$sob,'','95').
                     " <td class='label'>روستا:</td>
                      <td colspan='1' class='data'><input value='$CountyName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width:200px\" name='CountyName' type='text' class='textbox' id='CountyName'    size='5' maxlength='50' /></td>
                     ";
                     
                     if (!($login_userid>0))
                     {
                        print "<tr>
                      <td colspan='2'><input name='submit' type='submit' class='button' id='submit' value='ثبت مشخصات طرح' /></td>
                     </tr>
                     </tfoot>";
                     exit;
                     }
                     
                     
                    print "<tr>
                      <td colspan='2'><input name='submit' type='submit' class='button' id='submit' value='تصحیح مشخصات طرح' /></td>
                     </tr>
                     </tfoot>";

					  ?>

                     
                     
                    
                   </table>
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