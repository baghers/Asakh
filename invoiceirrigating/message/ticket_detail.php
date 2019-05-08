<?php 

/*
message/ticket_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/ticket.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
 
$register = false;

    
if ($_POST){
       
           $_POST['endchk'] = $_POST['endchk'];
    $endchk= $_POST['endchk'];
    
         	$ticketIDmaster=$_POST['ticketIDmaster'];
	if ( $_POST['comments'] != "" && $login_userid>0 && $_POST['ticketIDmaster']>0 )
    {
        /*
ticket جدول تیکت ها
status وضعیت
Kind نوع
ReceiverID شناسه گیرنده
Header عنوان
comments شرح
MessagesIDReply کاربر پاسخ دهنده
*/
        $query = "INSERT INTO ticket (Kind, comments,ticketIDmaster,SaveTime,SaveDate,ClerkID) 
            VALUES( 
             '$_POST[RID]', 
            '$_POST[comments]', 
            '$_POST[ticketIDmaster]', '" . date('Y-m-d H:i:s'). "','".gregorian_to_jalali(date('Y-m-d'))."','$login_userid');";
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
    if ($endchk=='on' && ($login_RolesID==1 || $login_userid==683)) 
    {
        $query = "update ticket set status=1 where ticketID='$_POST[ticketIDmaster]';";
  		
			  		           	try 
								  {		
									  	  	 mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    }
}
else
{
    $ticketIDmaster = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
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
							echo "<p class='note'>پاسخ شما با شماره پیگیری  ".mysql_insert_id()." با موفقیت ثبت شد</p>";
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
                <form action="ticket_detail.php" method="post" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    
                            
                     <?php
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
                        where  ticketID='$ticketIDmaster'
                        order by SaveTime desc";
                        
                        
                         $result = mysql_query($query);
                        $resquery = mysql_fetch_assoc($result);
                         
                         
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
                        
                         
                         
                            echo "
                            <tr>
                                <td class='label'
                                ></td>
                                <td class='data'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><input  type='text' class='textbox' size='30' maxlength='50' value='$resquery[_key]' /></td>
                            
                                <td class='label'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">وضعیت:</td>
                                <td class='data'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><input  type='text' class='textbox' size='10' maxlength='50' value='$resquery[statustitle]' /></td>
                                <td class='label'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">عنوان:</td>
                                <td class='data'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><input  type='text' class='textbox' size='30' maxlength='50' value='$resquery[Header]' /></td>
                            
                            </tr>
                            <tr>
                                <td class='label'
                                ></td>
                                
                                <td class='data' colspan='5'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"><textarea   class='textbox'  cols='100' rows='6'>$resquery[comments]</textarea></td>
                                <tr>
                                
                                
                                
                                <td></td>
                                <td colspan='5'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                                ثبت در تاریخ $resquery[SaveDate] توسط  ".decrypt($resquery['CPI'])." ".decrypt($resquery['DVFS'])." $resquery[cotitle]
                                </td>
                                </tr>
                                
                                <tr>
                                <td></td>
                                <td colspan='5'>$fstr1</td>
                                </tr>
                                
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                            </tr>
                            
                            
                            ";
                   $closed=0;
                   if ($resquery['statustitle']!='')
                   $closed=1;
                        
                   
                   $masterid=$resquery['ticketID']; 
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
                        where  ticketIDmaster='$masterid'
                        order by SaveTime ";
                     
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
                        
                        echo "
                            <tr>
                                <td class='label'></td>
                                
                                <td 
                                
                                class='data' colspan='5'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"
                                ><textarea   class='textbox'  cols='100' rows='6'>$resquery[comments]</textarea></td>
                                
                                
                                <tr>
                                <td></td>
                                <td colspan='5'
                                class='f10_font$b'
                                style=\"color:#006800;background-color:#cfe7e7;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                                پاسخ در تاریخ $resquery[SaveDate] توسط  ".decrypt($resquery['CPI'])." ".decrypt($resquery['DVFS'])." $resquery[cotitle]
                                </td>
                                </tr>
                                <tr>
                                <td></td>
                                <td colspan='5'>$fstr1</td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                            </tr>";
                            
                     
                        
                   }
                   
                 
					 
					 
                     if ($closed=='' && ($login_RolesID==1 || $login_userid==683))
                     echo "
                      <tr>
                      <td class=\"label\">پاسخ:</td>
                      <td class=\"data\" colspan='5'>
                      <textarea  name=\"comments\" id=\"comments\"  class=\"textbox\" maxlength=\"1000\" cols=\"100\" rows=\"6\"></textarea>
                      </td>
                     </tr>
                     
					  
                     <tr>
                     <td class=\"data\"><input name=\"ticketIDmaster\" type=\"hidden\" class=\"textbox\" id=\"ticketIDmaster\"  
                     value='$ticketIDmaster'  /></td>
                     </tr>
                     
                      <tr>
                            <td colspan='1' class='label'>فایل پیوست</td>
                             
                             <td colspan='2' class='data'><input type='file' name='file1' id='file1' ></td>
                             
                             </tr>
                             
                     
                     <tr>
                      <td class=\"label\" colspan=\"3\">بستن تیکت</td>
                      <td class=\"data\"><input id=\"endchk\" name=\"endchk\" type='checkbox' /></td>
			         </tr>";
                     
                     
                     
                     
					 ?>
                            
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td>
                      <?php 
                      if ($closed=='')
                     echo "<input name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" value=\"ارسال\" />"; 
                      
                      ?>
                      </td>
                     </tr>
                    </tfoot>
                   </table>
                   
                   
                   
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