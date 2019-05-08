<?php 

/*
message/ticket.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/ticket_detail.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
 
$register = false;    
/*
ticket جدول تیکت ها
status وضعیت
Kind نوع
ReceiverID شناسه گیرنده
Header عنوان
comments شرح
MessagesIDReply کاربر پاسخ دهنده
*/

if ($_POST){
    
	$_POST['comments']='ایمیل: '.$_POST['email'].' همراه: '.$_POST['mobile'].'&#10;'.$_POST['comments'];      	
	if (($_POST['RID']>0) && $_POST['comments'] != "" && $login_userid>0)
    {
        $query = "INSERT INTO ticket (Header, Kind, comments,SaveTime,SaveDate,ClerkID) 
            VALUES( 
            '$_POST[msgHeader]'
            , '$_POST[RID]', 
            '$_POST[comments]', '" . date('Y-m-d H:i:s'). "','".gregorian_to_jalali(date('Y-m-d'))."','$login_userid');";
    			  		           	try 
								  {		
									  	  	 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		//header("Location: clerk.php");
			$register = true;
			//print $query;exit;
            
        $iid=mysql_insert_id();
        if ($_FILES["file1"]["error"] > 0) 
    	{
    					//echo "Error: " . $_FILES["file2"]["error"] . "<br>";
    	} 
    	else 
    	{
    						
            if (($_FILES["file1"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
            }
            $ext = end((explode(".", $_FILES["file1"]["name"])));
            foreach (glob("../../upfolder/ticket/" . $iid.'*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/ticket/" . $iid.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
    					
    	}
	}
                
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ارسال تیکت</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
				if ($_POST)
				{
					if ($register)
					{
							echo "<p class='note'>تیکت شما با شماره ".mysql_insert_id()." با موفقیت ثبت شد</p>";
							//header("Location: msgsending8.php");
					}
					else
					{
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
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="ticket.php" method="post" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    
                            
                     <?php
	             

					 $query="select _value,_key from (".sqlticketkinds().") v1
                     
                    ORDER BY _value";
                     $ID = get_key_value_from_query_into_array($query);
                     print "</tr><tr>".select_option('RID','درخواست:',',',$ID,0,'','','1','rtl',0,'',$IDn,'','','');
					 
					 
					 
					 
					 
				  ?>

                     <tr>
                      <td class="label">عنوان:</td>
                      <td class="data"><input name="msgHeader" type="text" class="textbox" id="msgHeader"    size="30" maxlength="50" value="<?php print $value; ?>" /></td>
                      <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"    size="5" maxlength="5" value="<?php print $uid; ?>" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">متن:</td>
                      <td class="data">
                      <textarea  name="comments" id="comments"  class="textbox" maxlength="1000" cols="100" rows="6"></textarea>
                      </td>
                     </tr>
                     
                     <tr>
                      <td class="label"> همراه:</td>
                      <td class="data"><input name="mobile" type="textbox" class="textbox" id="mobile"    size="30" maxlength="11" value="<?php print $mobile; ?>" /></td>
			         </tr>
					 
					 <tr>
                      <td class="label">ایمیل:</td>
				      <td class="data"><input name="email" type="textbox" class="textbox" id="email"    size="30" maxlength="50" value="<?php print $email; ?>" /></td>
			    	 </tr>
					  
                     <tr>
                            <td colspan='1' class='label'>فایل پیوست</td>
                             
                             <td colspan='2' class='data'><input type='file' name='file1' id='file1' ></td>
                             
                             </tr>
                             
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ارسال" /></td>
                     </tr>
                    </tfoot>
                   </table>
                   <?php
                   
                   
            
            echo "
            <table id='records' width='95%' align='left'>
            <thead style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">
            	 <tr>
                            <th style = 'text-align:center;'>شماره تیکت</th>
                            <th style = 'text-align:center;'>درخواست</th>
                            <th style = 'text-align:center;'>عنوان</th>
                            <th style = 'text-align:center;'>تاریخ</th>
                            <th style = 'text-align:center;'>کاربر</th>
                            <th style = 'text-align:center;'>شرکت</th>
                            <th style = 'text-align:center;'>وضعیت</th>
                            <th style = 'text-align:center;'></th>
                            <th style = 'text-align:center;'></th>
                         </tr>
            </thead>
            <tbody >";
            
            
            if ($login_RolesID==1 || $login_userid==683)
            $query="select ticket.*,v1._key,clerk.CPI,DVFS, 
            case clerk.BR>0 when 1 then producers.title else 
            case clerk.HW>0 when 1 then operatorco.title else 
            case clerk.MMC>0 when 1 then designerco.title else '' end end end cotitle
            ,case ifnull(ticket.status,0)>0 when 1 then 'بسته' else '' end statustitle
            from ticket  
            left outer join (".sqlticketkinds().") v1 on v1._value=ticket.Kind
            inner join clerk on clerk.ClerkID=ticket.ClerkID
           	left outer join producers on producers.ProducersID=clerk.BR 
        	left outer join operatorco on operatorco.operatorcoID=clerk.HW
        	left outer join designerco on designerco.designercoID=clerk.MMC
            where  ifnull(ticketIDmaster,0)=0
            order by ifnull(status,0),SaveTime desc";
            else
            $query="select ticket.*,v1._key,clerk.CPI,DVFS, 
            case clerk.BR>0 when 1 then producers.title else 
            case clerk.HW>0 when 1 then operatorco.title else 
            case clerk.MMC>0 when 1 then designerco.title else '' end end end cotitle
            ,case ifnull(ticket.status,0)>0 when 1 then 'بسته' else '' end statustitle
            
            from ticket  
            left outer join (".sqlticketkinds().") v1 on v1._value=ticket.Kind
            inner join clerk on clerk.ClerkID=ticket.ClerkID
           	left outer join producers on producers.ProducersID=clerk.BR 
        	left outer join operatorco on operatorco.operatorcoID=clerk.HW
        	left outer join designerco on designerco.designercoID=clerk.MMC
            where ticket.ClerkID='$login_userid' and ifnull(ticketIDmaster,0)=0
            order by ifnull(status,0),SaveTime desc";
            $result = mysql_query($query);
            while($resquery = mysql_fetch_assoc($result))
            {
                 
                     $fstr1="";
                     $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/ticket/';
                     $handler = opendir($directory);

                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if (($ID==$resquery['ticketID']) )
                                    $fstr1="<a href='../../upfolder/ticket/$file' ><img style = 'width: 20px;' target='_blank' src='../img/accept.png' title='فایل پیوست' ></a>
                                    ";
                                       
                                
                            }
                        }
                        
                        
                     
                echo "<tr>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[ticketID]</td>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[_key]</td>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Header]</td>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[SaveDate] </td>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".decrypt($resquery['CPI'])." ".decrypt($resquery['DVFS'])."</td>
					
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[cotitle] </td>
                    <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[statustitle] </td>
                    <td>$fstr1</td>
                    <td class='no-print'><a  target='_blank' href='ticket_detail.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['ticketID'].rand(10000,99999)."'><img style = 'width: 20px;' src='../img/search.png' title=' ريز '></a></td>
                    			";
                
            }

            echo "
            </tbody >
            </table>
            ";
                    ?>
                   
                  </form>
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