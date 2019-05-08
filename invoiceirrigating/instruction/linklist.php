<?php 

/*
instruction/linklist.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
 
*/

include('../includes/connect.php'); 
include('../includes/check_user.php');

if ($login_Permission_granted==0 || $login_isfulloption!=1) header("Location: ../login.php");





 ?>

	                  					

<!DOCTYPE html>
<html>
<head>
  	<title>فرم ها</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
<style>
legend {
		color:#BBB;

}
table.menu {
	width:100%;
}

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f12_font{
		border:1px solid black;border-color:#000000 #000000;font-size:12pt;line-height:140%;font-family:'B Nazanin':font-color:#ffffff;                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f12_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;font-size:12pt;line-height:140%;font-family:'B Nazanin':font-color:#ffffff;                           
  }

.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

  
</style>
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
                <br />
            <?php 
			 {
			 
	$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
	//print_r ($linearray);
    $ApplicantMasterID=$linearray[0];
    $type=$linearray[1];
    $applicantstatesID=$linearray[2];
	$linkID=1;
	if ($type==5) $linkID='-1';			

	
			 
			?>
    
      	<legend>فرم ها</legend>
				 <table class="menu" align='center' border='1'>              
                   <thead>
				 
				   
                        <tr>

                            <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th 
                           	<span class="f14_font"> شرح فرم ها  </span> </th>
							<th  
                            <span class="f14_font" >توضیحات</span></th>
			                <th  
                           	<span class="f14_font" > لینک </span> </th>
						    <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th 
                           	<span class="f14_font"> شرح فرم ها  </span> </th>
							<th  
                           <span class="f14_font" >توضیحات</span></th>
			                 <th  
                           	<span class="f14_font" > لینک </span> </th>
						    
                           
                        </tr>
                       </thead> 
 
 
				
				<?php 
				//print $linkID;
				//print  $login_RolesID;
				 if ($login_designerCO==1){
				 $sql="SELECT  menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu 
					left outer join menuroles on menu.MenuID=menuroles.MenuID 
					left outer join roles on menuroles.RolesID=roles.RolesID 
					where linkID='$linkID'
					group by menu.menuID  
					";
					}
				else
				{
                $sql="SELECT  menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu 
					left outer join menuroles on menu.MenuID=menuroles.MenuID 
					left outer join roles on menuroles.RolesID=roles.RolesID 
					where linkID='$linkID' and (
					menuroles.RolesID='$login_RolesID' or menuroles.RolesID='100' or menuroles.RolesID='101' or menuroles.RolesID='0' 
					or menuroles.RolesID='')
					
 					group by menu.menuID 
					";
				}
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
   
					//$hrefhttp="http://koaj.ir/";

				  while($row = mysql_fetch_assoc($result) ){
				    if ($row['link']){ 
                	
                    $link='';
					$rown++;
					$fn='';
					if ($row['linkID']=='-1')
					  { 
					  $fn="فرم شماره (".$row['ordering'].") &nbsp;------&nbsp;";
							if ($row['ordering']==3)
								{
									$code=$ApplicantMasterID.'_1';		
									$tablepng='../img/table.png';
								}
							else if ($row['ordering']==6)
								{
									$code=$ApplicantMasterID."_5_".$applicantstatesID;
									$tablepng='../img/folder_accept.png';
								}
							else	
								{
									$code=$ApplicantMasterID."_5_".$applicantstatesID;
									$tablepng='../img/attachment.png';
									$fn="&nbsp;------&nbsp;";
								}
                                //if (strlen(strstr(strtoupper($row['link']),'HTTPS'))>0)
								
						$link= "<a target='_blank' href='../$row[link]?uid=".rand(10000,99999).rand(10000,99999).
								rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).$code.rand(10000,99999).
								"'><img style = 'width: 25px;' src=$tablepng title='$row[name]'></a>"; 
						
                    
					  }
					
					
                      
					  
				      if ($rown%2==1)
							{
							 
                             if (strlen(strstr(strtoupper($row['link']),'HTTPS'))>0)
                                $rowlink='<a href="'.$row['link'].'" target="_blank">';
                                else 
                                $rowlink='<a href="http://'.$row['link'].'" target="_blank">';
                    
								$rown1++;if ($rown1%2==1) $b='b';else $b='';	?>		
								<tr>    
								<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
								<td	<span class="f12_font<?php echo $b; ?>">
									<?php print $rowlink;?> 
									<?php echo $fn.'&nbsp;'.$row['name']; ?> </span> </td>
								<td <span class="f12_font<?php echo $b; ?>"  >  <?php echo ''; ?> </span>  </td>
								<td><?php echo $link; ?></td>
							<?php
							}
						else 
                            { 
                                
                        
                    
							?>							

							<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
                            <td <span class="f12_font<?php echo $b; ?>">
								<?php print $rowlink;?> 
								<?php echo $fn.'&nbsp;'.$row['name']; ?> </span> </td>
                            <td <span class="f12_font<?php echo $b; ?>"  >  <?php echo ''; ?> </span>  </td>
							<td><?php echo $link; ?></td>
        



                     		</tr>
		
		
		  	 <?php }}}?>
				   
                </table>
	          
			  <?php 
              
              
              
              } ?>    
              
                   
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
