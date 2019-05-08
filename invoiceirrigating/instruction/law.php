<?php 
 
/*
instruction/law.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/law_delete.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
$path = "../../upfolder/law/";

$permitrolsid = array("1","20","23");
if (in_array($login_RolesID, $permitrolsid))
if ($_POST )
    {   
        /*
        roles جدول نقش ها
        rolesID شناسه نقش
        Title عنوان نقش
        */
        $query='select rolesID as _value,Title as _key from roles order by Title COLLATE utf8_persian_ci';
        $ID = get_key_value_from_query_into_array($query);
        $hasrow=0;
        foreach ($ID as $key => $value)
        {
            //print $_POST["role$value"]."salam";
            if ($_POST["role$value"]=='on')
            {
               $hasrow=1; 
            }
        }
        if ($hasrow==0)
        {
            print "لطفا یک نقش انتخاب نمایید.";
            exit;
        } 
        
	if ($_FILES["filep"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file2"]["error"] . "<br>";
        } 
        else 
        {
		   	    $IDUser = $_POST['IDUser'];
 	      		$path = $_POST['path'];
				
    		 if (($_FILES["filep"]["size"] / 1024)>20)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
            }
                $ext = end((explode(".", $_FILES["filep"]["name"])));
                $attachedfile=$IDUser.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                //print $path.$attachedfile;
                foreach (glob($path. $IDUser.'_1*') as $filename) 
                {
                    unlink($filename);
                }move_uploaded_file($_FILES["filep"]["tmp_name"],$path.$attachedfile);
				
        }
	 
    
        /*
        law جدول قوانین
        lawno شماره
        lawtype نوع
        HeaderTitle عنوان
        Description شرح
        MenuID شناسه منو
        */
	
        $ostan=$_POST['g1id'];
        $dom=$_POST['dom'];
        $lawno=$_POST['lawno'];
        $lawtypes=$_POST['lawtype'];
        if ($lawtypes=="information") $lawtype=0; else if ($lawtypes=="election") $lawtype=1; else if ($lawtypes=="selectform") $lawtype=2;
	        $HeaderTitle=$_POST['HeaderTitle'];
        $Description=$_POST['Description'];     
        $MenuID=$_POST['MenuID'];    
        $sql="INSERT INTO law(lawno,lawtype,HeaderTitle, Description,MenuID,SaveTime,SaveDate,ClerkID,ostan,dom)
            values ('$lawno','$lawtype','$HeaderTitle','$Description','$MenuID','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid'
			,'$ostan','$dom');";
            //print $sql;
            //exit;
        mysql_query($sql); 
         		            	try 
								  {		
									  	  	    mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    
            
        $query = "select lawID from law where lawID = last_insert_id()";
        		           	 	try 
								  {		
									  	  	    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
        $row = mysql_fetch_assoc($result);
        $lawID = $row['lawID'];
              
            
        $query='select rolesID as _value,Title as _key from roles order by Title COLLATE utf8_persian_ci';
        $ID = get_key_value_from_query_into_array($query);
        foreach ($ID as $key => $value)
        {
            //print $_POST["role$value"]."salam";
            if ($_POST["role$value"]=='on')
            {
                //lawrole جدول قوانین برای یک نقش
                mysql_query("
                INSERT INTO lawrole(lawID,RolesID,SaveTime,SaveDate,ClerkID) 
                VALUES('$lawID','$value','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
        } 
              
                  

            
            
    }
$disabled='';
  if ($login_RolesID<>1) {$strrol="and roles.rolesID in	(2,3,4,10,19,20,21)";$disabled='disabled';
                          $strrol=" and substring(clerk.CityId,1,2)=substring($login_CityId,1,2)";}
/*
law جدول قوانین
lawrole جدول قوانین نقشها
roles نقش ها
*/				    
  $sql = "SELECT law.*,roles.Title,menu.name linktarget,clerk.CityId from law 
  left outer join lawrole on lawrole.lawID=law.lawID
  left outer join roles on roles.rolesID=lawrole.rolesID
  left outer join menu on menu.MenuID=law.MenuID
  left outer join clerk on clerk.ClerkID=law.ClerkID
  where ifnull(lawrole.rolesID,0)>0 $strrol
  order by lawno DESC";
 

     		           	 	try 
								  {		
									  	  	   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
 //print $sql;
 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست دستورالعمل ها</title>

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

    <script>
    function CheckForm()
{
    if (document.getElementById('HeaderTitle').value)
    return confirm('مطمئن هستید که تغییر  اعمال شود ؟');
	else 
	{
	alert('لطفا عنوان و شرح ابلاغیه را تکمیل نمایید.');
	return false;
	}
}

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
            
            <form action="law.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
			 <table style='border:0px solid;'>
  		
                    <tbody>
                        
                  <?php

                   $permitrolsid = array("1","20","23");
                   if (in_array($login_RolesID, $permitrolsid))
                    {
					if ($login_RolesID==1) 
					{$query='select rolesID as _value,Title as _key from roles order by Title COLLATE utf8_persian_ci';}
					if ($login_RolesID<>1) 
					{$query='select rolesID as _value,Title as _key from roles where roles.rolesID in (2,3,4,10,19,20,21) order by Title COLLATE utf8_persian_ci';}
					
                        $allrolesID = get_key_value_from_query_into_array($query);
                        $cnt=0;
						
                        print "<td colspan='2' class='label'>ثبت ابلاغیه </td>";
		                
?>
<td> <input type="radio" name="lawtype" <?php  if (isset($lawtype) && $lawtype=="information") echo "checked";?>  value="information">اطلاع رسانی</td>
<td> <input type="radio" name="lawtype"   value="election">نظرخواهی</td>
<td> <input type="radio" name="lawtype"   value="selectform">نظرسنجی</td>
<?php
					$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) :  $login_ostanId.'00000';
					$sqlselect="select distinct ostan.CityName _key,substring(ostan.id,1,2) _value  FROM clerk
					inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
					order by _key  COLLATE utf8_persian_ci";
					$allg1id = get_key_value_from_query_into_array($sqlselect);
			 	    print select_option('g1id',' ',',',$allg1id,0,'',$disabled,'4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
					
		  $sqlc = "SELECT count(*) count from law ";
		  $resultc = mysql_query($sqlc);
		  $rowc = mysql_fetch_assoc($resultc);$count=$rowc['count']+7;mysql_data_seek( $resultc, 0 );
		  $IDUser =$count.'_'.$count;
            
		  
?>					
	<td colspan="2"></td>
	<td colspan="3" class='data'><input type='file' name='filep' id='filep' value='123' accept='application/jpg'>
		<td> <input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>"></td>
		<td> <input type="hidden" name="path" value ="<?php echo $path; ?>"></td> </tr>
					
</tr>
<?php

			foreach ($allrolesID as $key => $value)
                     {
                        if ($value>0)
                        {
                            $cnt++;
	                        print "<td class='data' colspan=2><input type='checkbox' name='role$value'>&nbsp;$key &nbsp;&nbsp;&nbsp;&nbsp;</input></td>";
                            if (($cnt%7)==0)
                            print "</tr><tr>";
                     }
                    }
                      print "<tr></tr></table></tr><tr><table><tr><br></tr>";
 
	                if ($login_RolesID==1) 
				     $query="select MenuID _value,name _key from menu 
                     where parent>0 order by _key  COLLATE utf8_persian_ci";
				    if ($login_RolesID<>1) 
				     $query="select menu.MenuID _value,menu.name _key from menu 
                     inner join menuroles on  menuroles.RolesID in (2,3,4,9,10,19,20,21)
					 where parent>0 order by _key  COLLATE utf8_persian_ci";
					 $MenuID = get_key_value_from_query_into_array($query);
            
                            
                       print  "
                          <tr>
						  <td  class='label'>ترتیب:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 30px\"
                          name='lawno' type='text' class='textbox' id='lawno'  value='$count'   /></td>
                                
                          <td  class='label'>عنوان:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 150px\"
                          name='HeaderTitle' type='text' class='textbox' id='HeaderTitle'    /></td>
                         
                         <td  class='label'>شرح:</td>
                          <td class='data' colspan='1'><textarea id='Description' colspan='1' name='Description' rows='3' cols='80'  ></textarea>
                         ".select_option('MenuID','','',$MenuID,0,'',$disabled,'1','rtl',0,'',0,'','120')."
						 
						  
                         <td> <input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
						 <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 30px\"
                          name='dom' type='text' class='textbox' id='dom'  value='0'   /></td>
                          
					    
                         
                                ";   
                    }
 
                    ?>
                  
                          
                   </tbody>
                </table>
				
                <table id="records" width="95%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                        
                        	<th width="5%">ردیف </th>
                        	<th width="5%">ترتیب </th>
                        	<th width="5%">عنوان </th>
                        	<th width="30%">شرح</th>
                        	<th width="25%">نقش</th>
							<th width="10%">تاریخ ابلاغیه</th>
						    <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
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
                        
                        if ($lawtype==1) $lawtypeTitle='نظرخواهی'; else $lawtypeTitle='';
                        
                        if ($lawIDold==0)
                            $lawIDold=$row['lawID'];
                        
                        if ($lawIDold==$row['lawID'])
                            $Title.= '، '.$row['Title'];
                        else
                        {
                            $rown++;
                            $deletestr="";
                            $detailstr="";
                            $permitrolsid = array("1","20","23");
                            if (in_array($login_RolesID, $permitrolsid))
                            {
                                $deletestr="<a 
                            href='law_delete.php?uid=".'1'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                            
                                $detailstr="<a target='_blank'
                            href='law_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
                            "' 
                            > <img style = 'width: 75%;' src='../img/search.png' title='مشاهده'> </a>";
                            }
                        
                        
                            print "
                            <tr>
							<td>$rown</td>
                            <td>$lawno</td>
                            <td>$HeaderTitle </td>
                            <td>$Description</td>
                            <td>$Title</td>
                            <td>$SaveDate $lawtypeTitle</td>
                            <td>$linktarget</td>
                            <td>$deletestr</td>
                            <td>$detailstr</td>
                            </tr>";
                                                   
                           
                           $lawIDold=$lawID;
                            $Title=$row['Title']; 
                        }
                        
                        $lawno = $row['lawno'];
                        $lawtype = $row['lawtype'];
                        $HeaderTitle = $row['HeaderTitle'];
                        $Description=$row['Description'];
                        $lawID = $row['lawID'];
                        $linktarget=$row['linktarget'];
		     			$SaveDate = gregorian_to_jalali($row['SaveDate']);
        	
                    }
                      $rown++;
                            $deletestr="";
                            $detailstr="";
                            $permitrolsid = array("1");
                            if (in_array($login_RolesID, $permitrolsid))
                            {
                                $deletestr="<a 
                            href='law_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                            
                                $detailstr="<a 
                            href='law_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
                            "' 
                            > <img style = 'width: 75%;' src='../img/search.png' title='مشاهده'> </a>";
                            }
                            
                        
                        
                            print "
                            <tr><td>$rown</td>
                            <td>$lawno</td>
                            <td>$HeaderTitle</td>
                            <td>$Description</td>
                            <td>$Title</td>
							<td>$SaveDate $lawtypeTitle</td>
                            <td>$linktarget </td>
                            <td>$deletestr</td>
                            <td>$detailstr</td>
                            </tr>";

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
