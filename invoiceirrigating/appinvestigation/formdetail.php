<?php 

/*

//appinvestigation/formdetail.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
include('../includes/functions.php');
  if ($login_Permission_granted==0) header("Location: ../login.php");


    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ClerkIDinvestigation=$linearray[0];
    $form3ID=$linearray[1];
    $f1=$linearray[2];
    $f2=$linearray[3];
    $f3=$linearray[4];
    $f4=$linearray[5];
/*
    form3detail ریز مقادیر ارزشیابی
    CPI نام کاربر
    DVFS نام خانوادگی کاربر
*/ 
  
 $sql = "select Description,score1,form3detail.SaveDate,clerk.CPI,clerk.DVFS,form3detail.form3detailid from form3detail
 left outer join clerk on clerk.clerkid=form3detail.clerkid
where ClerkIDinvestigation='$ClerkIDinvestigation' and form3ID='$form3ID'
order by form3detail.SaveDate desc	 
	";	
    //print $sql;exit;
     
							try 
							  {		
								$result = mysql_query($sql);
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
  	<title>ریز ارزشیابی</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    

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
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="form.php" method="post">
			
                
                
               <table align='center' class="page" border='1' id="table2">              
               <thead>
	               
                     
                     <?php
            echo "
            <tr><th colspan='5'  class=\"f14_fontb\"  >$f1</th></tr>
            <tr><th colspan='5'  class=\"f14_fontb\"  >$f2</th></tr>
            <tr><th colspan='5'  class=\"f14_fontb\"  >$f3</th></tr>
            <tr><th colspan='5'  class=\"f14_fontb\"  >$f4</th></tr>
            <tr>
            
            
                            <th class=\"f14_fontb\"  >ردیف</th>
                        	<th class=\"f14_fontb\" style ='width: 100px;'>امتیاز</th>
							<th class=\"f14_fontb\" style ='width: 335px;' >شرح</th>
							<th class=\"f14_fontb\" style ='width: 100px;'>تاریخ</th>
							<th class=\"f14_fontb\" style ='width: 200px;'>کاربر</th>
						
					    </tr>";
                        $row=0;
	       while($resquery = mysql_fetch_assoc($result))
			{
			   $rown++;$row++;
                    if ($rown%2==1) 
                    {$b='b';$bg="background-color:#f3f3f3;";} else {$b='';$bg='';}

                    
						$score1=$resquery['score1'];
						$Description=$resquery['Description'];
						$SaveDate=$resquery['SaveDate'];
						
                        $encrypted_string=$resquery['CPI'];
                        $encryption_key="!@#$8^&*";
                        $decrypted_string="";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                        $encrypted_string=$resquery['DVFS'];
                        $encryption_key="!@#$8^&*";
                        $decrypted_string.=" ";
                        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                               
                               
                        print "<tr>
                        <td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$row</td>
                        <td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$score1</td>
                        <td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$Description</td>
                        <td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".gregorian_to_jalali($SaveDate)."</td>
                        <td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$decrypted_string
                        
                        <a 
                                        href='formdetaildel.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery['form3detailid'].rand(10000,99999)."'
                                        onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                                        > <img style = 'width: 20px;' src='../img/delete.png' title='حذف'> </a>
                        </td>
                        
                        
                        </tr>";
                        
                      ?>
				            
							
						</tr>
						
						
						
           <?php 
					
		//if ($rown==2) print $rown.'<='.$srown;exit;
 
		   }	
		   ?>
			  
                 </table>
				<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
                   
				
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
