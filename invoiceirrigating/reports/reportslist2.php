<?php include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>


<!DOCTYPE html>
<html>
<head>
  	<title>مديريت گزارشات</title>
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
			?>
    
      	<legend>مديريت گزارشات</legend>
				 <table class="menu" align='center' border='1'>              
                   <thead>
				 
				   
                        <tr>

                            <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th 
                           	<span class="f14_font"> لیست گزارشات  </span> </th>
							<th  
                            <span class="f14_font" >  شرح گزارش </span></th>
			                <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th 
                           	<span class="f14_font"> لیست گزارشات  </span> </th>
							<th  
                            <span class="f14_font" >  شرح گزارش </span></th>
			
                           
                        </tr>
                       </thead> 
 
 
				
				<?php 
				//print  $login_RolesID;
				 if ($login_designerCO==1){
				 $sql="SELECT  menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu 
					left outer join menuroles on menu.MenuID=menuroles.MenuID 
					left outer join roles on menuroles.RolesID=roles.RolesID 
					group by menu.link 
					";
					}
				else
				{
                $sql="SELECT  menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu 
					left outer join menuroles on menu.MenuID=menuroles.MenuID 
					left outer join roles on menuroles.RolesID=roles.RolesID 
					where 
					menuroles.RolesID='$login_RolesID' or menuroles.RolesID='100' or menuroles.RolesID='0' 
					or menuroles.RolesID=''
 					group by menu.link 
					";
				}



				$result = mysql_query($sql);
					
					//print $sql;

				  while($row = mysql_fetch_assoc($result) ){
				    if ($row['link']){ 
                	

					$rown++;
                        
				         if ($rown%2==1){$rown1++;if ($rown1%2==1) $b='b';else $b='';	?>		
	                        <tr>    
                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
                            <td
							<span class="f12_font<?php echo $b; ?>">
							<?php print '<a href="../'.$row['link'].'" target="_blank">';?> 
							<?php echo '&nbsp;------&nbsp;'.$row['name']; ?> </span> </td>
                            <td
                             <span class="f12_font<?php echo $b; ?>"  >  <?php echo ''; ?> </span>  </td>
							
							
							<?php
							}
						else 
                            { 							
							?>							

                          <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
                            <td
							<span class="f12_font<?php echo $b; ?>">
							<?php print '<a href="../'.$row['link'].'" target="_blank">';?> 
							<?php echo '&nbsp;------&nbsp;'.$row['name']; ?> </span> </td>
                            <td
                             <span class="f12_font<?php echo $b; ?>"  >  <?php echo ''; ?> </span>  </td>
        



                     		</tr>
		
		
		  	 <?php }}}?>
				   
                </table>
	          
			  <?php } ?>    
              
                   
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
