<?php

/*
instruction/mojavez.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/amaliat.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
 
if ($login_Permission_granted==0) header("Location: ../login.php");

//mojavez جدول مجوزها
	$con="";    
  $sql = "SELECT * FROM mojavez where 1=1 $con order by cod ";

    		            	try 
								  {		
									  	  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>مجوزها</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="inst_aml.php" method="post"  enctype="multipart/form-data">
			 <table style='border:0px solid;'>
                <tbody> 
                  <?php
                  
                   require_once '../class/upload.class.php';	
 $upfile=new Upload();
 require_once('../funcs.php');
 
                   $permitrolsid = array("1","20","23");
                   if (in_array($login_RolesID, $permitrolsid))
                    {
						if ($login_RolesID==1) 
						{$query='select rolesID as _value,Title as _key from roles order by Title COLLATE utf8_persian_ci';}
						if ($login_RolesID<>1) 
						{$query='select rolesID as _value,Title as _key from roles where roles.rolesID in (2,3,4,10,19,20,21) order by Title COLLATE utf8_persian_ci';}
						
							$allrolesID = get_key_value_from_query_into_array($query);
							$cnt=0;

						$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) :  $login_ostanId.'00000';
						$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
						inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
						order by _key  COLLATE utf8_persian_ci";
						$allg1id = get_key_value_from_query_into_array($sqlselect);
						print select_option('g1id',' ',',',$allg1id,0,'',$disabled,'4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
		  
?>					
	
</tr>
<?php
		
                      print "<tr></tr></table></tr><tr><table><tr><br></tr>";      
                     print  "
                          
                            <tr>    
                          <td  class='label'>عنوان:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 250px\"
                          name='HeaderTitle' type='text' class='textbox' id='HeaderTitle' size='100'   /></td>
                         
                         <td  class='label'>ترتیب:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='cod' type='text' class='textbox' id='cod' size='3'   /></td>
                         
                       
                          </tr><tr> <td  class='label'>عکس :</td><td colspan=2><input type=file name=file1 id=file1></td>
                         
                         <td> <input   name='mojavez' type='submit' class='button' id='mojavez' value='ثبت' /></td>
					    
                         
                                ";   
                    }
 
                    ?>
                  
                          
                   </tbody>
                </table>
				
                <table id="records" width="95%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                        	<th width="5%">ردیف </th>
                        	<th>عنوان </th>
							<th >ترتیب </th>
							<th >استان </th>
							<th >تاریخ </th>
							<th >عکس </th>
							<th> </th>
						   
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>        
                   <?php
                   
                    $rown=0;  
                    $lawIDold=0; 
                    $Title='';
                    while($row = mysql_fetch_assoc($result)){
                       
                        $SaveDate = gregorian_to_jalali($row['SaveDate']);
                            $rown++;
                            $deletestr="";$deletehide="";
                            $permitrolsid = array("1","20","23");
                            //if (in_array($login_RolesID, $permitrolsid))
                           // {
                                $deletestr="<a 
                            href='inst_aml.php?mojavezid=".'3'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['mojavezID'].rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 25px;' src='../img/delete.png' title='حذف'> </a>";
                            
							if ($row['showhide']==1)
                                $deletehide="<a 
                            href='inst_aml.php?mojavezid=".'2'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['mojavezID'].rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که نمایش داده نشود ؟');\"
                            > <img style = 'width: 15px;' src='../img/play_16.png' title='عدم نمایش'> </a>";
							else
								$deletehide="<a 
                            href='inst_aml.php?mojavezid=".'1'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['mojavezID'].rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که نمایش داده شود ؟');\"
                            > <img style = 'width: 15px;' src='../img/pause_16.png' title='نمایش'> </a>";
                      					  
                          //  }
                            print "
                            <tr>
							<td>$rown</td>
						    <td>$row[HeaderTitle] </td>
                            <td>$row[cod] </td>
                           <td>$row[ostan] </td>
                           
                            <td>$SaveDate</td>
                            <td>";
                              echo $upfile->disply('mojavez','../../upfolder/mojavez',$row['mojavezID'],'1');
                             echo "</td>
                            <td>";
                            
                           echo " </td>
                            <td>$deletestr</td>
                            <td>$deletehide</td>
                             </tr>";
                                                   
                           
                         
                      
                        
                      
        	
                    }
                     

?>

                        
                   
                    </tbody>
                   
                </table>
                      
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
