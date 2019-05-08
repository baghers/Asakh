<?php 

/*

insert/designer_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/designer_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
include('../class/fieldType.class.php');
$drop=new fieldType();
//echo $login_RolesID;
if ($login_Permission_granted==0) header("Location: ../login.php");
//نقش هایی که امکان مشاهده اطلاعات را دارند
$permitrolsid = array("1","2","5","9","10","20");
//نقش هایی که امکان ویرایش اطلاعات را دارند
$permitrolsidmodir = array("1","20");
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	//membersdate جدول تاریخ اعتبار مدارک اعضا
	$qmax = mysql_query("SELECT max(membersdateID) membersdateID FROM membersdate WHERE membersID = '$id'");
	$rmax = mysql_fetch_assoc($qmax);
	$membersdateID=$rmax['membersdateID'];//شناسه جدول
    //designer جدول طراحان
    //membersID شناسه جدول
	$qmjv = mysql_query("SELECT * FROM designer WHERE membersID = '$id'");
	$rmjv = mysql_fetch_assoc($qmjv);
	$PermisionNo=$rmjv['PermisionNo'];//شماره مجوز
    $PermisionDate=$rmjv['PermisionDate'];//تاریخ
    $issuerID=$rmjv['issuerID'];//مرجع صدور
    
	   /* members جدول اعضای شرکت ها
        Position 1 مدیرعامل
        Position 2 رئیس هیئت مدیره
        Position 3 هیئت مدیره
        Position 4 کارمند
        Position در غیر اینصورت سایر
        designerco جدول شرکت های طراح
        operatorco جدول شرکت های پیمانکار
        Farmers جدول شرکت های بهره بردار
       */	    
    $query = "SELECT members.*,membersdate.membersdateID,membersdate.StartDate,membersdate.EndDate from members 
	left outer join membersdate on membersdate.membersdateID='$membersdateID'
	where members.membersID='$id' order by membersdateID desc ";
	
		 					  	try 
								  {		
									    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	//print $query;
    $resquery = mysql_fetch_assoc($result);
    $FName=$resquery['FName'];
    $LName=$resquery['LName'];
    $NationalCode=$resquery['NationalCode'];
   
	$BirthDate = $resquery['BirthDate'];
	$Position = $resquery['Position'];
	$InsuranceCode = $resquery['InsuranceCode'];
	$InsuranceHistory = $resquery['InsuranceHistory'];
	$Bstat = $resquery['Bstat'];
	$Mstat = $resquery['Mstat'];
	$Pstat = $resquery['Pstat'];
	$Bbranch = $resquery['Bbranch'];
	$Mbranch = $resquery['Mbranch'];
	$Pbranch = $resquery['Pbranch'];
//print $Position;
    $BDate=$resquery['BDate'];
    $MDate=$resquery['MDate'];
    $PDate=$resquery['PDate'];
    $BLicenceNo=$resquery['BLicenceNo'];
    $MLicenceNo=$resquery['MLicenceNo'];
    $PLicenceNo=$resquery['PLicenceNo'];
    $BUniversity=$resquery['BUniversity'];
    $MUniversity=$resquery['MUniversity'];
    $PUniversity=$resquery['PUniversity'];
    $Phone=$resquery['Phone'];
    $Email=$resquery['Email'];             
    $membersID=$id;  
    $operatorcoID=$resquery['operatorcoid'];
    $designercoID=$resquery['DesignerCoID'];
	$fani=$resquery['mojavez'];
	$StartDate=$resquery['StartDate'];
	$EndDate=$resquery['EndDate'];
	//print $resquery['operatorcoid'];
	
	 require_once '../class/upload.class.php';	
	 $upfile=new Upload();
	 
?>


<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح مشخصات طراح</title>
    <strong>	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        <script type="text/javascript" src="../funcs.js"></script>
<style type="text/css">
#spdes,#spoprat
{
  display:none;
}
</style>

    <script type="text/javascript">
            $(function() {
                $("#PermisionDate, #simpleLabel").persiandatepicker();   
                $("#BDate, #simpleLabel").persiandatepicker();    
                $("#MDate, #simpleLabel").persiandatepicker();    
                $("#PDate, #simpleLabel").persiandatepicker();  
				$("#BirthDate, #simpleLabel").persiandatepicker(); 
				$("#StartDate, #simpleLabel").persiandatepicker(); 
				$("#EndDate, #simpleLabel").persiandatepicker(); 
				
            });
        
       
function CheckForm()
{
              
    var Pos = document.getElementById("Position");
    if (!(document.getElementById('FName').value.length>0))
    {
        alert('نام طراح را وارد نمایید!');return false;
    }
    if (!(document.getElementById('LName').value.length>0))
    {
        alert('نام خانوادگی طراح را وارد نمایید!');return false;
    }
    if (!(document.getElementById('NationalCode').value.length>0))
    {
        alert(' کد ملی را وارد نمایید!');return false;
    }
	if (Pos.options[Pos.selectedIndex].value==0)
    {
        alert('سمت را وارد نماييد');return false;
    }
	if (!(document.getElementById('StartDate').value.length>0))
    {
        alert('تاريخ شروع را وارد نماييد');return false;
    }
    if(document.getElementById("mojavez").checked == true) 
    {	
       if (!(document.getElementById('PermisionNo').value.length>0))
	   {
         alert('شماره مجوز طراح را وارد نمایید!');return false;
	   }
      if (!(document.getElementById('PermisionDate').value.length>0))
	  {
         alert('تاریخ صدور مجوز طراح را وارد نمایید!');return false;
	  }
	  if (!(document.getElementById('issuerID').value>0))
	  {
         alert('مرجع صادر کننده مجوز طراح را وارد نمایید!');return false; 
	  }
       return true;	 
	}
    if (!(document.getElementById('BDate').value.length>0))
    {
        alert('تاریخ  مدرک کارشناسی را وارد نمایید!');return false;
    }
    if (!(document.getElementById('BLicenceNo').value.length>0))
    {
        alert('شماره  مدرک کارشناسی را وارد نمایید!');return false;
    }
    if (!(document.getElementById('BUniversity').value.length>0))
    {
        alert('دانشگاه اخذ  مدرک کارشناسی را وارد نمایید!');return false;
    }
     
  return true;
}
        
    </script>
	
</strong>
    <!-- /scripts -->
</head>

<body onload="ShowHideDiv3('<?php echo $designercoID; ?>','<?php echo $fani; ?>')">

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
                <form action="amaliat.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data" >
			<input type="hidden" id="membersdateID" name="membersdateID" value="<?php echo $membersdateID; ?>" >
            <input type="hidden" id="login_RolesID" name="login_RolesID" value="<?php echo $login_RolesID; ?>" >
            <input type="hidden" id="login_Permission_granted" name="login_Permission_granted" value="<?php echo $login_Permission_granted; ?>" >        
				   <table width="600" align="center" class="form" >
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "designer_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                    
                     <?php

print "
                     
                      <tr>
                      <td  class='label'>نام خانوادگی:</td>
                      <td class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 125px\"
                             name='LName' type='text' class='textbox' id='LName' value='$LName'   /></td>
                      <td class='label'>نام :</td>
                      <td colspan='1' class='data'><input 
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" 
                      name='FName' type='text' class='textbox' id='FName' value='$FName'   size='15' maxlength='15' /></td>
					  <td  class='label'>تاريخ تولد:</td>
                      <td   class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='BirthDate' value='$BirthDate'  type='text' class='textbox' id='BirthDate'
                        /></td>
                     <td   class='label'>کد ملی:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='NationalCode' value='$NationalCode' type='text' class='textbox' id='NationalCode' maxlength='10'   /></td>
					   <td ></td>
                        <td colspan='1' class='data'><input type='file' name='file1' id='file1' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'1');
                        echo"</td>
					   </tr>
					<tr>
					 <td colspan='2'>ایمیل:<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 180px\"
                       name='Email' value='$Email' type='text' class='textbox' id='Email'    /></td>
                    <td  class='label'>تلفن:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='Phone' value='$Phone' type='text' class='textbox' id='Phone'    /></td>
                   <td  class='label'>كدبيمه :</td><td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='InsuranceCode'  value='$InsuranceCode'  type='text' class='textbox' id='InsuranceCode'    /></td>
					   <td  class='label'>بيمه مرتبط:</td><td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                       name='InsuranceHistory'  value='$InsuranceHistory' type='text' class='textbox' id='InsuranceHistory'    />ماه</td>
					   <td ></td>
					   <td colspan='1' class='data'><input type='file' name='file2' id='file2' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'2');
                        echo"</td>
					   </tr>
		               <tr >
                     <td colspan='1'  class='label'>کارشناسی:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='BDate' value='$BDate' type='text' class='textbox' id='BDate'
                        /></td>
                     <td  class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='BLicenceNo' value='$BLicenceNo' type='text' class='textbox' id='BLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td  colspan='1' class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='BUniversity' value='$BUniversity' type='text' class='textbox' id='BUniversity'    /></td>"; 
					$qryBranch = "SELECT 
									   '1' _value, 'آبياري' _key 
                      union all SELECT '2' _value, 'کشاورزی' _key 
                      union all SELECT '2' _value, 'عمران آب' _key 
                      union all SELECT '3' _value, 'سایر' _key 
                      order by _value  ";
					$dropBranch = get_key_value_from_query_into_array($qryBranch);         
                    $qryStat = "SELECT '1' _value, 'مرتبط' _key 
                      union all SELECT '2' _value, 'غيرمرتبط' _key 
					  union all SELECT '3' _value, 'زمينه' _key
                      order by _key  ";
					 $dropStat = get_key_value_from_query_into_array($qryStat);   
                     echo select_option('Bbranch','رشته:',',',$dropBranch,'','','','','rtl','','',$Bbranch,"",80);					 
					 echo select_option('Bstat','',',',$dropStat,'','','','','rtl','','',$Bstat,"",50);						 
                      echo"
					    <td  class='data'><input type='file' name='file4' id='file4' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'4');
                        echo"</td>
					  </tr>
                       
                       <tr >
                     <td colspan='1'  class='label'>کارشناسی ارشد:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='MDate' value='$MDate' type='text' class='textbox' id='MDate'
                        /></td>
                     <td   class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='MLicenceNo' value='$MLicenceNo' type='text' class='textbox' id='MLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td colspan='1' class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='MUniversity' value='$MUniversity' type='text' class='textbox' id='MUniversity'    /></td>";
                       echo select_option('Mbranch','رشته:',',',$dropBranch,'','','','','rtl','','',$Mbranch,"",80);	
                       echo select_option('Mstat','',',',$dropStat,'','','','','rtl','','',$Mstat,"",50);						   
                       echo"
					    <td  class='data'><input type='file' name='file5' id='file5' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'5');
                        echo"</td>
					   </tr>
                       
                       <tr >
                     <td colspan='1'  class='label'>دکتری:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='PDate' value='$PDate' type='text' class='textbox' id='PDate'
                        /></td>
                     <td   class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PLicenceNo' value='$PLicenceNo' type='text' class='textbox' id='PLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td  class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PUniversity' value='$PUniversity' type='text' class='textbox' id='PUniversity'    /></td>";
                       echo select_option('Pbranch','رشته:',',',$dropBranch,'','','','','rtl','','',$Pbranch,"",80);
                      echo select_option('Pstat','',',',$dropStat,'','','','','rtl','','',$Pstat,"",50);					   
                       echo"
					    <td  class='data'><input type='file' name='file6' id='file6' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'6');
                        echo"</td>	 
					       
                       </tr><tr>";
                    if(in_array($login_RolesID, $permitrolsidmodir))
                     {
					        echo"<td  class='label'>کارشناس فنی:
					<input name='mojavez' type='checkbox'   onclick='ShowHideDiv(this,this.id)'  id='mojavez'";if ($fani>0) echo 'checked';
				print "/></td>";
		
			//print $designercoID.'CHECKED'.$operatorcoID;
		            // $permitrolsidmodir = array("1","20");
					// echo $designercoID.'yy'.$operatorcoID;
                   
					    echo"<td class='data'  colspan='3'><input name='compny' id='compny'  value='1' ";if ($designercoID>0) echo 'checked';echo"
						type='radio' onclick='ShowHideDiv2(this,this.value)'  >مشاور
					    <input name='compny' id='compny' value=2 ";if ($operatorcoID>0) echo 'checked';echo" type='radio' 
						onclick='ShowHideDiv2(this,this.value)'> مجري ";	
				        $query='select designercoID as _value,Title as _key from designerco order by _key COLLATE utf8_persian_ci';
                        $query2='select operatorcoID as _value,Title as _key from operatorco order by _key COLLATE utf8_persian_ci';
  				        echo "<span id='spdes'   >".$drop->dropDb('designercoID','_key','_value',$query,$designercoID)."</span>"; 
    			        echo "<span id='spoprat' style='display:none' >".$drop->dropDb('operatorcoID','_key','_value',$query2,$operatorcoID)."</span></td>
						";                     
                     }  

					
					 
					 
					 
    				$qryPosition = "SELECT '1' _value, 'مديرعامل' _key 
                      union all SELECT '2' _value, 'رئيس هيئت مديره' _key 
                      union all SELECT '3' _value, 'هيئت مديره' _key  
                      union all SELECT '4' _value, 'كارمند' _key
                      union all SELECT '5' _value, 'پرسنل' _key order by _value  ";
					$dropPosition = get_key_value_from_query_into_array($qryPosition);         
					echo select_option('Position','سمت شركت:',',',$dropPosition,'','','','','rtl','','',$Position,"",100);
					 echo"<td   class='label'>تاريخ شروع:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 80px\"
                       placeholder='انتخاب تاریخ'  name='StartDate' type='text' class='textbox' id='StartDate' value='$StartDate'
                        /></td>
						<td   class='label'>تاريخ پايان:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 80px\"
                       placeholder='انتخاب تاریخ'  name='EndDate' type='text' class='textbox' id='EndDate' value='$EndDate'
                        /></td> 
					  </tr>
	
		
					  <tr id='spmojavez' style='visibility:hidden'><td></td><td></td>
	
					
					
                      <td  class='label'>شماره مجوز:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PermisionNo' value='$PermisionNo' type='text' class='textbox' id='PermisionNo'    /></td>
                       <td  class='label'>تاریخ صدور:</td>
                      <td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='PermisionDate' value='$PermisionDate' type='text' class='textbox' id='PermisionDate'
                        /></td>";
																																																										
					 $query='select issuerID as _value,Title as _key from issuer';
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='issuerIDlbl'  class='label'>مرجع صدور:</td>".
                     select_option('issuerID','',',',$ID,0,'','','1','rtl',0,'',$issuerID,'','100');
                     
                     
                    print "
					<td style='visibility:visible'>مجوز کاشناس فنی:</td>
            		   <td   style='visibility:visible' class='data'><input type='file' name='file3' id='file3' >";
                         $upfile->disply('designer','../../upfolder/designer',$id,'3');
                        echo"</td>
                    </tr>";
					
                    if((!in_array($login_RolesID, $permitrolsidmodir)) && ($fani==1))
                     {
                     	$qf=mysql_query('select  Title  from issuer where issuerID='.$issuerID.' ');
    				    $rf = mysql_fetch_array($qf);
    				    if($designercoID>0)
    				      $qury='select Title  from designerco where DesignerCoID='.$designercoID.' ';
    				    else
                          $qury='select Title  from operatorco where operatorcoID='.$operatorcoID.'';
                       
							  	try 
								  {		
									     $qf2=mysql_query($qury);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    				    $rf2 = mysql_fetch_array($qf2);
                        
                      echo"<tr>
                        <td  class='label'>";
                        if ($fani>0) echo 'کارشناس فنی';
                        echo"</td>
					
					    <td class='data'  colspan=2>";if ($designercoID>0) echo 'مشاور'; else echo"
						 مجري ".$rf2['Title']."</td>
                     	<td  class='label'>شماره مجوز:</td><td class='data'>".$PermisionNo."</td>
                        <td  class='label'>تاریخ صدور:</td><td  class='data'>".$PermisionDate."</td>
                        <td  class='label'>مرجع صدور:</td><td  class='data'colspan=3>".$rf['Title']."</td>
                     	</tr>";
					 } 
                      echo"<tr>
                      <td class='data'><input name='membersID' type='hidden' class='textbox' id='membersID'  value='$membersID'  /></td>
                     </tr>
                     <tr>
					  </tr>
					 <tr>
					 <td> </td>
					 </tr>
                     
                     
                     
                                      <th colspan=4 style = \"color:#ff0000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"> 
							حداکثر اندازه هر فایل 200 کیلوبایت می باشد
							</th>
                               
                     </tr>
                     </tfoot>
                     ";
                    if (($login_DesignerCoID>0) || ($login_OperatorCoID>0) || ($login_RolesID==1 || $login_RolesID==20) )
                    print "<td><input   name='des_edit' type='submit' class='button' id='des_edit' value='تصحیح' /></td>";



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