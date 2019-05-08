<?php 

/*
instruction/instruction1_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/instruction1.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

        /* 
        instruction جدول دستور العمل ها
        clerk کاربر
        clerkid شناسه کاربر
        instructionID شناسه دستورالعمل
        */
        
$sql = "SELECT instruction.*
FROM instruction 
inner join clerk on clerk.clerkid=instruction.clerkid
where instruction.instructionID='$ids'";

//print $sql;


          	 				try 
								  {		
									  	  $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
  
$row = mysql_fetch_assoc($result);


?>
<!DOCTYPE html>
<html>
<head>
  	<title>شرح دستور العمل</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
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
			<div id="content">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">  شرح دستور العمل <?php print $row['ApplicantName'] ?> </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <div style = "text-align:left;"><a href='instruction1.php'><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                    $fstr1="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/instructions/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
                            if (($ID==$row['instructionID']) && ($No==1) )
                                $fstr1="<td><a href='../../upfolder/instructions/$file' >دانلود/مشاهده</a></td>
                                
                                ";
                                
                            
                        }
                    }
                                            
                        $HeaderTitle = $row['HeaderTitle'];
                        $Description = $row['Description'];
                        $SaveDate = $row['SaveDate'];
                        print "
                        <tr><td  class='label'>عنوان:</td>
                        <td class='data'><input  value='$HeaderTitle'
                        style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 500px\"
                        type='text' class='textbox'    /></td></tr>
                             
                        <tr><td  class='label'>تاریخ:</td>
                        <td class='data'><input  value='".gregorian_to_jalali($SaveDate)."'
                        style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                        type='text' class='textbox'    /></td></tr>
                        
                        <tr><td  class='label'>شرح دستورالعمل:</td>
                        <td class='data'><textarea id='Description' name='Description' rows='15'  cols='140' readonly='1'>$Description</textarea></td></tr>
                        <tr><td  class='label'>فایل دستور العمل:</td>
                        $fstr1</tr>
                      ";
?>                      
                        
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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
