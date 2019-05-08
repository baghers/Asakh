<?php 
/*
pricesaving/pricesaving1masterlist_files.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesaving1masterlist.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

$UID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
if ($_POST) $UID = $_POST['uid'];
    
    $linearray = explode('_',$UID);
    $PID=$linearray[0];//شناسه تولید کننده
	$PriceListMasterID=$linearray[1];//لیست قیمت
	$year=$linearray[2];//سال
	$monthtitle=$linearray[3];//ماه
    /*
    نقش های مجاز بارگذاری
    1: مدیر پیگیری
    2: تولید کننده
    13: مدیر آبیاری
    14: ناظر عالی
    17: ناظر مقیم
    18: مدیر آبیاری تحت فشار
    */
    $permitrols = array("1","2","13","14","17","18");
	
	
if ($_POST)
{     
 
      $i=0;
    while (isset($_POST['PID'.++$i]))
        {
					$PID = $_POST['PID'.$i];
			  		$PriceListMasterID = $_POST['PriceListMasterID'];
					$Pdate=date('Y-m-d');
					$CID ='';$Cdate='';
				if (in_array($login_RolesID, $permitrols))
					{
						$CID ='';$Cdate='';$chkold=$_POST['chkold'.$i];
						if ($_POST['chk'.$i]=='on') $chknew=1; else $chknew=0;
						if (($chknew==1 && $chkold==0) || ($chknew==0 && $chkold==1))  
						{
							
							if ($_POST['chk'.$i]=='on')  
							{
								$CID = $_POST['CID'.$i];
								$Cdate = $_POST['Cdate'.$i];
								$Pdate = $_POST['Pdate'.$i];
							}
							if ($chknew==0 && $chkold==1) $Pdate = $_POST['Pdate'.$i];
						
									$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/files/';
									$handler = opendir($directory);
									while ($file = readdir($handler)) 
									{
										// if file isn't this directory or its parent, add it to the results
										if ($file != "." && $file != "..") 
										{
											 $linearray = explode('_',$file);
											 $ID=$linearray[0];
											 $No=$linearray[1];
											 if (($ID==$PID) && ($No==$PriceListMasterID) )
												{
													
													$ext = end((explode(".", $file)));
													$newfile=$PID.'_'.$PriceListMasterID.'_'.$Pdate.'_'.$CID.'_'.$Cdate.'_1.'.$ext;
													//print $file.'<br>'.$newfile.'<br>=';
													rename($directory.$file, $directory.$newfile);
												}
										 }
									}				
						}
					}
					else if (!in_array($login_RolesID, $permitrols))//فایل لیست قیمت
					{
										if ($_FILES["file$i"]["error"] > 0) 
										{
											//echo "Error: " . $_FILES["file1"]["error"] . "<br>";
											//exit;
										} 
										else 
										{
										 if (($_FILES["file$i"]["size"] / 1024)>5000)
										{
											print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
											exit;
										}
											$ext = end((explode(".", $_FILES["file$i"]["name"])));
											$attachedfile=$PID.'_'.$PriceListMasterID.'_'.$Pdate.'_'.$CID.'_'.$Cdate.'_1.'.$ext;
														
											foreach (glob("../../upfolder/producerapproval/files/".$PID.'_'.$PriceListMasterID.'*') as $filename) 
											{
												unlink($filename);
											}
											move_uploaded_file($_FILES["file$i"]["tmp_name"],"../../upfolder/producerapproval/files/" .$attachedfile);   
										}
					}
					
		}
 	

}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست قیمت </title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

       <script>
  function checkchange(id){
  
		   if (document.getElementById('chk'+id ).checked)
		   {  

			//alert(id);

			} 
	}			
    </script>
	
	 <script type="text/javascript">
            $(function() {
                $("#approvedate1, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate1, #simpleLabel").persiandatepicker();   
            });
                
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="pricesaving1masterlist_files.php" method="post" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                <br/>
                <table id="records" width="95%" align="center">
                       
                   <tbody>           
               

                            
    						
								<tr>
									<td  style="text-align:center; height:25px;">رديف</td>
									<td  style="text-align:center;">نام</td>
									<td  style="text-align:center;">تاريخ ارسال</td>
									<td  style="text-align:center;">اعتبار مجوز</td>
									<td  style="text-align:center;">اعتبار ضمانتنامه</td>
									<td  style="text-align:center;">اسكن لیست قیمت با فرمت pdf <?php echo $monthtitle.$year?></td>
									<td></td>
									
					<?php if ($login_RolesID<>2){ ?>  <td  style="text-align:center;">تاريخ تایید</td> <?php } ?>
					<?php if ($login_moneyapprovepermit==2  || $login_moneyapprovepermit==1 ){ ?>  
									<td  style="text-align:center;">تاییدکننده</td> <?php } ?>
									<td></td>
									<td></td><td></td><td></td>
								</tr>	
					<?php 
					//$Pdate=date('Y-m-d');
					$condition="  ";$readonlydesc=' '; 
					
					if (!in_array($login_RolesID, $permitrols)) {$condition=" and producers.ProducersID='$PID' ";$readonlydesc='disabled ';} 
 					$sql = "SELECT producers.*  FROM producers	where 1=1 $condition ;";
 				
				  	 	try 
								  {		
									  		$resultc = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

					$rown=0;$active=0;
				    while($resquery = mysql_fetch_assoc($resultc))
                    {
					$copermisionvalidate=$resquery["copermisionvalidate"];
						$Pdate='';$Cdate='';$CID='';$cl='';
						if (compelete_date($resquery["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
						$cl='ff0000';
					//		if ($login_moneyapprovepermit!=2  && $login_moneyapprovepermit!=1 && $login_RolesID!=3)
						//	if ($cl!='' ) continue;

						if (in_array($login_RolesID, $permitrols)) $ProducersID=$resquery["ProducersID"]; else $ProducersID=$PID;
						
					               $fstr = array();
									$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/files/';
									$handler = opendir($directory);
									while ($file = readdir($handler)) 
									{
									
										// if file isn't this directory or its parent, add it to the results
										if ($file != "." && $file != "..") 
										{
											 $linearray = explode('_',$file);
											 $ID=$linearray[0];
											 $No=$linearray[1];
											//print $linearray[2].'<br>' ;
											
											if (($ID==$ProducersID) && ($No==$PriceListMasterID) )
												{
												$Pdate=$linearray[2];$CID=$linearray[3];$Cdate=$linearray[4];$chk="checked";$chkold=1;
												if (!$linearray[4]>0) {$chk="";$chkold=0;}
												if (in_array($login_RolesID, $permitrols) && !$linearray[4]>0) {$CID=$login_userid;$Cdate=date('Y-m-d');}
												$fstr[$ProducersID]="<a target='blank' href='../../upfolder/producerapproval/files/$file' ><img style = 'width: 25px;' src='../img/accept.png'  ></a>";
												
												}
										 }
										 
									}
							if ($login_RolesID<>3)
							if (!$Pdate>0 ) continue;
							if ($login_moneyapprovepermit!=2  && $login_moneyapprovepermit!=1 && $login_RolesID!=3)
							if (!$chkold>0 ) continue;
							
					
			$query = "SELECT clerk.* FROM clerk WHERE ClerkID = '" . $CID . "';";
			
				  	 	try 
								  {		
									  	$resultcid = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

			$user = mysql_fetch_assoc($resultcid);
							    $rown++;
								if ($rown%2==1) $b='b'; else $b='';
	 						?>
							<tr>
							   
						
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.$rown; ?></td>
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo   '<br>'. $resquery["Title"]; ?></td>
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'.gregorian_to_jalali($Pdate); ?></td>
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'. $copermisionvalidate; ?></td>
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  '<br>'. $resquery["guaranteeExpireDate"]; ?></td>
						
									
									<?php
								if (!$chk>0 && !in_array($login_RolesID, $permitrols)) print '<td>'."<input name='file$rown' type='file' id='file$rown' accept='application/pdf'/></td>" ;
								else  print "<td></td>";
								print '<td>'.$fstr["$ProducersID"];
								if ($login_RolesID<>2){
								?>
									</td>
									
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($chk) echo  '<br>'.gregorian_to_jalali($Cdate); ?></td>
									<?php if ($login_moneyapprovepermit==2  || $login_moneyapprovepermit==1 ){ ?>  
									<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($chk) echo  '<br>'.	 decrypt($user['CPI']).' '.decrypt($user['DVFS']); ?></td>
							  <?php   }
							  if ($Pdate>0)
									echo "<td  class='f11_font'> <input   type=\"checkbox\"  onChange=\"checkchange('$rown')\" name='chk$rown' id='chk$rown' $readonlydesc  $chk> </td>"; 
									
							?>
                               	<td colspan="1"><input type="hidden" name="PriceListMasterID" value ="<?php echo $PriceListMasterID; ?>"></td>
								<td colspan="1"><input type="hidden" name="uid" value ="<?php echo $UID; ?>"></td>

							    <td class="data"><input name="PID<?php echo $rown; ?>" type="hidden" class="textbox" id="PID<?php echo $rown; ?>"  value="<?php echo $ProducersID; ?>"  /></td>
							    <td class="data"><input name="Pdate<?php echo $rown; ?>" type="hidden" class="textbox" id="Pdate<?php echo $rown; ?>"  value="<?php echo $Pdate; ?>"  /></td>
							    <td class="data"><input name="CID<?php echo $rown; ?>" type="hidden" class="textbox" id="CID<?php echo $rown; ?>"  value="<?php echo $CID; ?>"  /></td>
							    <td class="data"><input name="Cdate<?php echo $rown; ?>" type="hidden" class="textbox" id="Cdate<?php echo $rown; ?>"  value="<?php echo $Cdate; ?>"  /></td>
								<td class="data"><input name="chkold<?php echo $rown; ?>" type="hidden" class="textbox" id="chkold<?php echo $rown; ?>"  value="<?php echo $chkold; ?>"  /></td>
												
							<?php 
							}
 					 }
					 
					if (!$chk>0 || in_array($login_RolesID, $permitrols))
					if ($login_moneyapprovepermit==2  || $login_moneyapprovepermit==1 || $login_RolesID==3)
						{?>   
                           <tr><td colspan="10"><input  name='submit' type='submit' class='button' id='submit' value='ثبت' /></td></tr>
                    <?php }	 ?>    
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colspan="1" id="fooBar">  &nbsp;</span>
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
