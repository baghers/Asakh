<?php 

/*

insert/foundation_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");


if ($_POST )
{
    $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح
    $IDsel=$_POST['IDsel'];//شناسه انتخابی
    $Title=$_POST['Title'];//عنوان
    $len=$_POST['len'];//طول
    $width=$_POST['width'];//عرض
    $heigh=$_POST['heigh'];//ارتفاع
    $thickness=$_POST['thickness'];//ضخامت
    $number=$_POST['number'];//تعداد
    
    $SaveTime=date('Y-m-d H:i:s');
    $SaveDate=date('Y-m-d');
    $ClerkID=$login_userid;
    //appfoundation جدول سازه های طرح
    $query = "INSERT INTO appfoundation(ApplicantMasterID  ,groupcode,Title,len,width,heigh,thickness,number ,SaveTime,SaveDate,ClerkID) 
    VALUES('$ApplicantMasterID','$IDsel','$Title','$len','$width','$heigh','$thickness','$number','$SaveTime','$SaveDate','$ClerkID');";
    
             	 	 		  	try 
								  {		
									  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    header("Location: "."foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.rand(10000,99999));
                        
}
else
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $type=$linearray[1];//نوع
    $typetitle=$linearray[2];//عنوان نوع
}
/*
applicantmaster جدول مشخصاتطرح
ApplicantName عنوان پروژه
ApplicantMasterID شناسه طرح
OperatorCoID مجری
applicantstatesID وضعیت
*/

$sql = "SELECT ApplicantName,applicantstatesID,OperatorCoID FROM applicantmaster WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$TITLE = $count['ApplicantName'];
		$applicantstatesID = $count['applicantstatesID'];
		$OperatorCoID = $count['OperatorCoID'];

$query="
select 'فونداسیون' _key,1 as _value union all
select 'اتاقک پمپاژ' _key,2 as _value union all 
select 'حوضچه شیر' _key,3 as _value union all
select 'حوضچه پمپاژ' _key,4 as _value union all
select 'استخر' _key,5 as _value union all
select 'لوله گذاری' _key,6 as _value";
$IDsel = get_key_value_from_query_into_array($query);
/*
appfoundation سازه ها
appfoundationID شناسه سازه
len طول
width عرض
heigh ارتفاع
thickness ضخامت
number تعداد
ApplicantMasterID شناسه طرح
manuallistpriceall فهارس بها
*/
$sql = "select distinct appfoundation.appfoundationID,appfoundation.Title,len,width,heigh,thickness,appfoundation.number,
        case groupcode 
        when 1 then 'فونداسیون'
        when 2 then 'اتاقک پمپاژ'
        when 3 then 'حوضچه شیر'
        when 4 then 'حوضچه پمپاژ'
        when 5 then 'استخر' 
        when 6 then 'لوله گذاری'end grouptitle,appfoundation.ApplicantMasterID
         from appfoundation 
        
        WHERE appfoundation.appfoundationID in (
        select appfoundationID from manuallistpriceall where ApplicantMasterID='" . $ApplicantMasterID . "'
        union all select appfoundationID from manuallistprice where ApplicantMasterID='" . $ApplicantMasterID . "'
        ) or appfoundation.ApplicantMasterID='" . $ApplicantMasterID . "'
        
        ";

//print $sql;exit;    

 

         	 	 		  	try 
								  {		
									   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

print "<!DOCTYPE html>
<html>
<head>
  	<title>لیست $typetitle </title>";


?>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
        function fillform(txturl)
    {
        //alert(txturl);
        var selectedappfoundationID=document.getElementById('allappfoundationID').value;
        var selectedAID=document.getElementById('ApplicantMasterID').value;
        var selectedCID=document.getElementById('txtuserid').value;
    
    //alert(selectedappfoundationID);
            $.post(txturl,{selectedappfoundationID:selectedappfoundationID, selectedAID:selectedAID,selectedCID:selectedCID},function(data){  
           if (data.error>0) 
            alert( "خطا در ثبت" ); 
            
           else alert( "ثبت انجام شد" );
        
        
       }, 'json');

        alert( "" );
        location.reload();
        
                       
    }
	
	function CheckForm()
{
    
    if ($('#Title').length > 0 )
    if (!(document.getElementById('Title').value.length>0) || (document.getElementById('Title').value)==0)
    {
        alert('عنوان سازه را وارد نمایید!');return false;
    }    
 
    if ($('#len').length > 0)
    if (!(document.getElementById('len').value.length>0))
    {
        alert('مشخصات سازه را تکمیل نمایید!');return false;
    }    

  if ($('#width').length > 0)
    if (!(document.getElementById('width').value.length>0))
    {
        alert('مشخصات سازه را تکمیل نمایید!');return false;
    }    

 if ($('#heigh').length > 0)
    if (!(document.getElementById('heigh').value.length>0))
    {
        alert('مشخصات سازه را تکمیل نمایید!');return false;
    }    

  return true;
}
	
    </script>
    <!-- /scripts -->
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
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            <form action="foundation_list.php" method="post" onSubmit="return CheckForm()">
            

 <?php 
        if ($type==1)
        {
        print "			<div id=\"content\">
                <table width=\"95%\" align=\"center\">
                    <tbody>
                        <tr>
                        
                        <h1 align=\"center\">  لیست $typetitle طرح $TITLE </h1>
                        <INPUT type=\"hidden\" name=\"stateid\" id=\"stateid\" value='$stateid'/>
                            
                             <div style = \"text-align:left;\">
                            <a href='$_SERVER[HTTP_REFERER]'><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a>
                            
                          </div>";
                         ?>
                    		
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
				
				        <thead>
                       
                            <th width="5%"></th>
                        	<th width="10%"></th>
                        	<th width="20%"></th>
							<th width="5%"></th>
                            <th width="5%"></th>
                        	<th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                         
                       
                    </thead>     
                   <tbody>
                    
                            
                    <tr>
                            
                            <td colspan='2'><?php echo $typetitle." طرح ".$TITLE; ?></td>
                            
                            
               
                           <?php 
                            
                            print "<td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1_-1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fm.png' title=' فهرست بهای دستی'></a></td>
                            
                            <td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_2_-1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/fs.png' title='  فهارس بها '></a></td>
                            ";
                            
                        print "</tr>
                        <INPUT type='hidden' id='txtuserid' value='$login_userid'/>  
                    </tbody>
                </table>
                <div style='visibility: hidden'>
                      </div>
                 <tr >   
                        <span colsapn='1' id='fooBar'>  &nbsp;</span>
                   </tr>
            </div>";



















            
        }
        else
        {
		
		if ($type==2) $ret='../appinvestigation/sendtoanjoman.php'; 
		else $ret='foundation_applicant_list.php?uid=$type^1';
		
			print "			<div id=\"content\">
                <table width=\"95%\" align=\"center\">
                    <tbody>
                        <tr>
                        
                        <h1 align=\"center\">  لیست $typetitle طرح $TITLE </h1>
                        <INPUT type=\"hidden\" name=\"stateid\" id=\"stateid\" value='$stateid'/>
                            
                             <div style = \"text-align:left;\">
                            <a href='$_SERVER[HTTP_REFERER]'><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a>
                            
                          </div>";
                        
                         ?>
                    
              			   <tr>
                             
								<th></th>
                        	    <th></th>
								<th></th>
								<th></th>
								<th colspan="3" > مشخصات سازه (cm)</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
                        
                           </tr>			  
                    	   <tr>
                             
                            <th width="5%"></th>
                        	<th width="10%"> گروه سازه</th>
                        	<th width="25%">عنوان سازه</th>
							<th width="5%">تعداد</th>
                            <th width="5%">طول</th>
                        	<th width="5%">عرض</th>
                            <th width="5%">ارتفاع</th>
                            <th width="5%">ضریب وزنی</th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                         
                             </tr> <tr>				  
                            <?php print 
                            "<td class='data'><input name='ApplicantMasterID' type='hidden' id='ApplicantMasterID' value='$ApplicantMasterID'  /></td>".
                            select_option('IDsel','',',',$IDsel,0,'','','1','rtl',0,'',0,"",'90')
                            ;
                            
                            ?>
                            <td class='data' ><input name='Title' type='text' id='Title' size="40"  /></td>
							<td class='data'><input name='number' type='text' id='number' size="5"  /></td>
							<td class='data'><input name='len' type='text' id='len' size="5" /></td>
                            <td class='data'><input name='width' type='text' id='width' size="5"  /></td>
                            <td class='data'><input name='heigh' type='text' id='heigh' size="5"  /></td>
                            <td class='data'><input name='thickness' type='text' id='thickness' size="5"  /></td>
                            
                            
                            <td ><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                    
					
              	            
                            
                            
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
				
				        <thead>
                       
                            <th width="5%"></th>
                        	<th width="10%"></th>
                        	<th width="25%"></th>
							<th width="5%"></th>
                            <th width="5%"></th>
                        	<th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                         
                       
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {
                        
        
                        $ID = $row['appfoundationID'];
                        $TITLE = $row['Title'];
                        $deletestr='';
                         if ($row['ApplicantMasterID']==$ApplicantMasterID)
                            $deletestr="<a href='foundation_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_'.$ApplicantMasterID.'_'.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف سازه'> </a>";
                            
                        
                        $rown++;
?>                      

                        <tr>
                            
                            <td width="5%"><?php echo $rown; ?></td>
                            <td width="10%"><?php echo $row['grouptitle']; ?></td>
                            <td width="25%" ><?php echo $TITLE; ?></td>
							<td width="6%"></td>
							<td width="6%"><?php print $row['number']; ?></td>
                            <td width="6%"><?php print $row['len']; ?></td>
                            <td width="6%"><?php print $row['width']; ?></td>
                            <td width="6%"><?php print $row['heigh']; ?></td>
                            <td width="6%" ><?php print $row['thickness']."</td>";
                            
                          //  print $ApplicantMasterID." ".$row['ApplicantMasterID']."<br>";
                            
                            if ($row['ApplicantMasterID']==$ApplicantMasterID)
                            {
                          //  print $ApplicantMasterID." ".$row['ApplicantMasterID'];
                              echo "<td><a href='foundation_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_'.
                            $ApplicantMasterID.'_'.rand(10000,99999)."'>
                            <img style ='width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>";  
                            }
                            else echo "<td></td>";
                             print '<td>'.$deletestr.'</td>'.
                           "<td  style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">
                        <a  target=\"_blank\"  href='summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.'_12_0_0_'.$applicantstatesID."_".$row['appfoundationID']."_سازه $TITLE"."_".rand(10000,99999).
                        "'><img style = 'width: 20px;' src='../img/print.png' title=' ريز '></a></td>";
                            
                            print "<td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1_'.$row['appfoundationID'].rand(10000,99999)."'>
                            <img style = 'width: 20px;' src='../img/fm.png' title=' فهرست بهای دستی'></a></td>
                            
                            <td><a href='manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_2_'.$row['appfoundationID'].rand(10000,99999)."'>
                            <img style = 'width: 20px;' src='../img/fs.png' title='  فهارس بها '></a></td>
                            
                            
                            <td><a href='foundation_delete_onlydetail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_'.$ApplicantMasterID.'_'.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 20px;' src='../img/reject.png' title='حذف آیتم های فهرست بها'> </a>";
                            
                        print "</tr>";

                    }

            print "<INPUT type='hidden' id='txtuserid' value='$login_userid'/>  
                    </tbody>
                </table>
                <div style='visibility: hidden'>
                      </div>
                 <tr >   
                        <span colsapn='1' id='fooBar'>  &nbsp;</span>
                   </tr>
            </div>";
            }

?>
                 
            
            
			<!-- /content -->
            
            </form>

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
