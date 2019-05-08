<?php 

/*
lawsubmitnazar.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('includes/connect.php');
include('includes/functions.php');


$useridid = substr($_GET["uid"],42,strlen($_GET["uid"])-47);
$login_ostanId= substr($_GET["uid"],0,2);
$linearr = explode('_',$useridid);

if ($_POST['userid']>0)    $useridid=$_POST['userid']; else $useridid=$linearr[0];
if ($_POST['login_ostanId']>0)    $login_ostanId=$_POST['login_ostanId']; 
$lawIDnotviewed=$linearr[2];
//menu جدول منو ها
//law جدول ابلاغیه ها		
     $sql = "SELECT law.*,menu.link linktarget 
	 from law 
     left outer join menu on menu.MenuID=law.MenuID
     where lawID='$lawIDnotviewed' and law.lawtype=2 ";
   
   //print $sql;
  		
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$lawtype=$row['lawtype'];
		$lawno=$row['lawno'];
		if ($row['SaveDate']) $lawSaveDate=gregorian_to_jalali($row['SaveDate']);
		$lawID = $row['lawID'];
		$rowDescription=$row['Description'];
		$HeaderTitle=$row['HeaderTitle'];
 	  	$Desarray = explode('_',$rowDescription);

	if ($_POST)
    { 
		//isset($_POST['answer'.++$i])
		$i=0;$yesno='';$cnt=0;
		while ($i<$_POST['rown'])
			{
				$i++;
				$ansarray = explode('_',$_POST['answer'.$i]);
				if ($ansarray[2]>0)  $cnt++;		
				$Desarray[($i-1)*3+2]=$ansarray[2];
				$yesno.=$_POST['answer'.$i];
			}
				
			$descript=$_POST['descript'];
		if ($cnt>($_POST['rown']-3))
			{
				if ($descript<>'')
				$yesno=$yesno.$_POST['description'].$_POST['descript'];
				
				$lawviewedID='';
                //lawviewed جدول ابلاغیه های مشاهده شده
				$sqlnazar = "SELECT lawviewed.*
							from lawviewed
							where lawID='$lawID' and ClerkIDsee='$_POST[userid]'	";
				$resultn = mysql_query($sqlnazar);
				$rownazer = mysql_fetch_assoc($resultn);
				$lawviewedID = $rownazer['lawviewedID'];
				
				//print $sqlnazar;
				if ($lawviewedID)
					$query = " UPDATE lawviewed SET ClerkIDsee='$_POST[userid]',yesno='$yesno'
						  ,SaveDate='" .date('Y-m-d'). "',SaveTime='".date('Y-m-d H:i:s')."',ClerkID='".$login_userid."' where lawviewedID ='$lawviewedID';";
				else
					$query = " insert into lawviewed (lawID,ClerkIDsee,yesno,SaveDate,SaveTime,ClerkID) 
						  VALUES ('$lawID','$_POST[userid]','$yesno','" .date('Y-m-d'). "','".date('Y-m-d H:i:s')."','".$login_userid."');";
				mysql_query($query);

				header("Location: home.php");
			}	
	}		
/*	

شرح سوال
_
نوع سوال
_
جواب سوال
_

*/	
	
	
	
    
 ?>
<!DOCTYPE html>
<html>
<head>
  	<title>فرم نظر سنجی</title>
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
               <form action="lawsubmitnazar.php" method="post">  
            <br />

           <table BORDER="3" width="90%" style="text-align: center;font-size:18.0pt;font-family:'B Nazanin';">
                    <tr>
						<td>&nbsp;</td>
						<td >
                            <font style="line-height:20px;color:#ff0000; text-align:justify;font-size:12pt;font-family:'B Nazanin';">
                           <p><b><?php echo ' فرم شماره '.$lawno.' مورخ '.$lawSaveDate ?> </b></p> 
                            <font style="line-height:20px;color:#000000; text-align:justify;font-size:12pt;font-family:'B Nazanin';">
                           <p style="background-color: cyan	;"><b><?php echo $HeaderTitle ?> </b></p> 
                           </font>
						</td>
					</tr>
						
					<tr>
                            <td>ردیف</td>
                            <td>شرح</td>
                            <td></td>
                            <td></td>
							<td>پاسخ</td>
							<td></td>
                    </tr>
           			 
		<?php 
			$rown=0;
			//print '<br>'.count($Desarray)/3;
			while($rown < count($Desarray)/3)
			{
			
				$Title =$Desarray[$rown*3];
				$nazartype=$Desarray[$rown*3+1];
				$valuenazr=$Desarray[$rown*3+2];
				$rown++;
				if ($rown%2==1) 
	                $b='b'; else $b='';
	
			if ($nazartype==1) { ?>
					<tr class="f7_font<?php echo $b;?>">
                            <td><?php echo $rown ?></td>
							<td><?php echo $Title ?></td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_1_';?>" <?php if ($valuenazr == 1) echo " checked"; ?>/>ضعیف</td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_2_';?>" <?php if ($valuenazr == 2) echo " checked"; ?>/>متوسط</td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_3_';?>" <?php if ($valuenazr == 3) echo " checked"; ?>/>خوب</td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_4_';?>" <?php if ($valuenazr == 4) echo " checked"; ?>/>عالی</td>
					</tr>

			<?php } else if ($nazartype==2){ ?>
					<tr class="f7_font<?php echo $b;?>">
                            <td ><?php echo $rown ?></td>
							<td><?php echo $Title ?></td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_5_';?>" <?php if ($valuenazr == 5) echo " checked"; ?>/>بله</td>
							<td><input name="answer<?php echo $rown;?>" type="radio" value="<?php echo $Title.'_'.$nazartype.'_6_';?>" <?php if ($valuenazr == 6) echo " checked"; ?>/>خیر</td>
                    </tr>

			<?php } else if ($nazartype==3){ ?>

					<tr class="f7_font<?php echo $b;?>">
                            <td><?php echo $rown ?></td>
							<td><?php echo $Title ?></td>
							<td colspan="4" >
							   توضیحات:<textarea id='descript' name='descript' rows='2'  cols='25' > <?php echo $descript;?> </textarea>
        					</td>
					          <input name="description" type="hidden" value="<?php echo $Title.'_'.$nazartype.'_';?>" />
                   		
					</tr>
					
			<?php  } ?>
		
<?php } ?>
					<tr>
                            <td>
                              <input name="userid" type="hidden" value="<?php echo $useridid;?>" />
                             <input name="login_ostanId" type="hidden" value="<?php echo $login_ostanId;?>" />
                             <input name="rown" type="hidden" value="<?php echo $rown;?>" />
                            </td>  
                    </tr>
       				
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