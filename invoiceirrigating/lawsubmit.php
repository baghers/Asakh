<?php 

/*
lawsubmit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

include('includes/connect.php');
include('includes/functions.php');

 

$useridid = substr($_GET["uid"],42,strlen($_GET["uid"])-47);
$login_ostanId= substr($_GET["uid"],0,2);
$linearr = explode('_',$useridid);

if ($_POST['userid']>0)
    $useridid=$_POST['userid'];
else $useridid=$linearr[0];

if ($_POST['Disable']>0)
    $Disable=$_POST['Disable'];
else $Disable=$linearr[1];


$lawIDnotviewed=$linearr[2];

	$Dom='and ifnull(law.dom,0)=0';



if ($Disable==3 
		&& strlen(strstr(strtoupper($_SERVER['SERVER_NAME']),'FCPM'))>0
		)
	{
		$HeaderTitle="دریافت فایل";
		$Description="لطفا فایل پیوست را دریافت و پس از تکمیل به آدرس toosraham@gmail.com ایمیل نمایید.";
		$Description.= "<br> تلفن تماس: 36015897";
		$row['lawno']=1;
	}
    else
    {
		if ($_POST['userid']>0 && $_POST['lawID']>0 )
		{
			if ($_POST["gender"]<>'')
			 {
					$gender = $_POST["gender"];
					if ($gender=="male") $yesno=0; else $yesno=1;
					if($yesno==0 || $yesno==1)
					{
					   //lawviewed جدول ابلاغیه های مشاهده شده
						$sql = "insert into lawviewed (lawID,yesno,ClerkIDsee,SaveTime,SaveDate,ClerkID)
							select  '$_POST[lawID]','$yesno','$_POST[userid]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$_POST[userid]' from law 
							 where lawID='$_POST[lawID]' and not exists (select * from lawviewed where lawID='$_POST[lawID]' and ClerkIDsee='$_POST[userid]')";
							// print $sql;
							// exit;  
						mysql_query($sql); 
					}
					$useridid = $_POST['userid'];
					$lawID=$_POST['lawID'];     
				 if (strlen($_POST['linktarget'])>0)
					header("Location: ".$_POST['linktarget']);
				 else 
					header("Location: home.php");        
			}  
		  else
			{
				$useridid = $_POST['userid'];
				$login_ostanId = $_POST['login_ostanId'];
			}   
		}
    //menu جدول منو ها
    //law جدول ابلاغیه ها
     $sql = "SELECT law.*,menu.link linktarget 
	 from law 
     left outer join menu on menu.MenuID=law.MenuID
     where lawID='$lawIDnotviewed' ";
    //print $sql;
    
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $HeaderTitle=$row['HeaderTitle'];
    $lawID=$row['lawID'];
    $Description=$row['Description'];
    $linktarget=$row['linktarget'];
    $lawtype=$row['lawtype'];
    $lawno=$row['lawno'];
    if ($row['SaveDate']) $lawSaveDate=gregorian_to_jalali($row['SaveDate']);
        
  }



    
 ?>
<!DOCTYPE html>
<html>
<head>
  	<title>ابلاغیه ها</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />


  <style>
   .leftc {
      float:left;
      width:33%;
      height:300px;
    }
    .centerc {
      float:left;
      width:34%;
      height:300px;
    }
    
    .rightc {
      float:left;
      width:33%;
      height:300px;
    }
    p {
     line-height: normal;
    }
  </style>
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
             <div id="wrapper">

             <!-- top -->
             <?php include('includes/top.php'); ?>
             <!-- /top -->
            

            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->
            
            <?php include('includes/subnavigation.php'); ?>
             <!-- header -->
             
             
            <?php include('includes/header.php'); ?> 
             <!-- /header -->

             <!-- content -->
             <div id="content" >
               <form action="lawsubmit.php" method="post">  
            <br />

                 <table BORDER="3" width="90%" style="text-align: center;font-size:18.0pt;font-family:'B Nazanin';">
                    <tr>
                    <td>&nbsp;</td>
                     <td >
                          
                            
                           <font style="line-height:20px;color:#ff0000; text-align:justify;font-size:12pt;font-family:'B Nazanin';">
                           <p><b><?php echo ' ابلاغیه شماره '.$lawno.' مورخ '.$lawSaveDate ?> </b></p> 
                           
                           <font style="line-height:20px;color:#000000; text-align:justify;font-size:12pt;font-family:'B Nazanin';">
                           <p style="background-color: cyan	;"><b><?php echo $HeaderTitle ?> </b></p> 
                           <p ><b><?php echo $Description ?></b></p>
                           </font>
                           
                          
                    </td>
                    </tr>
                         <tr>
                            <td></td>
                            <td>
                            
                            
                             <input name="lawID" type="hidden" value="<?php echo $lawID;?>" />
                             <input name="userid" type="hidden" value="<?php echo $useridid;?>" />
                             <input name="login_ostanId" type="hidden" value="<?php echo $login_ostanId;?>" />
                             <input name="linktarget" type="hidden" value="<?php echo $linktarget;?>" />
                             <input name="Disable" type="hidden" value="<?php echo $Disable;?>" />
                             
                             
<?php if ($lawtype==1) { ?>							 
  <p> <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?>  value="female">ضمن مطالعه نکات فوق با موارد بالا و <?php echo $HeaderTitle ?> موافقم. </p>
  <p> <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?>  value="male">ضمن مطالعه نکات فوق با موارد بالا و <?php echo $HeaderTitle ?> مخالفم. </p>
<?php } else { ?>							 
	<p> <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?>  value="female">بدینوسیله تایید می نمایم اطلاع رسانی در خصوص  <?php echo $HeaderTitle ?> به اینجانب/شرکت صورت پذیرفته است. </p>
        
		</td>
		<?php } 							 
  						
					$numname='';		
        	        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/law/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                     {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDlaw=$linearray[0];
                            $Nolaw=$linearray[1];
							if ($row['lawno']==$Nolaw) $numname=$file;
						}
				     }
		if ($numname)
		{?> 
			<td><?php print '<img src='.'/upfolder/law/'.$numname.' width=140 height=200>';?>
			 <a  target='_blank' href='/upfolder/law/<?php print $numname;?>'><?php print '</br>'.substr($HeaderTitle,0,54).'...'; ?></a>
			</td>	 
   <?php } ?>						
							
	                  <tr>
                    <td></td>
                        <td><p style="text-align: left;">
                        <input  name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                    </tr> 
                        </tr>  
                    <tr><td></td>
                          <td><p style="text-align: right; color:green;">
						  *جهت مشاهده ابلاغیه ها به سربرگ دستورالعمل/لیست ابلاغیه ها مراجعه فرمایید.
                       </td>
                    </tr> 
          
             </table>
              </form>   
             
            </div>
             
             <!-- /content -->
            
            <!-- footer -->
             <?php 
              include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

   
    

</body>

</html>