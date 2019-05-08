<?php

/*
instruction/lawclerk.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/law_delete.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
$lawtype=0;
$path = "../../upfolder/law/";
/*
نقش های مجاز به ثبت
1: مدیر پیگیری
20: مدیریت پرونده ها
23: ادمین
*/
$permitrolsid = array("1","20","23");
if (in_array($login_RolesID, $permitrolsid))
if ($_POST )
{

 if ($_FILES["filep"]["error"] > 0)//بارگذاری فایل مرتبط با دستورالعمل ارسالی 
        {
            echo "Error: " . $_FILES["file2"]["error"] . "<br>";
        } 
        else 
        {
		   	    $IDUser = $_POST['IDUser'];
 	      		$path = $_POST['path'];
				
    		 if (($_FILES["filep"]["size"] / 1024)>100)
            {
                print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
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
	 
        $lawno=$_POST['lawno'];
        $HeaderTitle=$_POST['HeaderTitle'];
        $lawtypes=$_POST['lawtype'];
        if ($lawtypes=="information") $lawtype=0; else if ($lawtypes=="election") $lawtype=1;else if ($lawtypes=="selectform") $lawtype=2;
			$Description=$_POST['Description'];   
        $MenuID=$_POST['MenuID'];         
		
	
    $lawID=lawsubmit($HeaderTitle,$lawtype,$Description,$MenuID,$login_userid);//ذخیره دستورالعمل
	
       	$value=1;
        while($_POST["clerkID$value"]>0)
        {
		      //print "salam";
            if ($_POST["clerk$value"]=='on')
            {      
				lawclerk($login_userid,$_POST["clerkID$value"],'',$lawID);	
			} 
            $value++;  
        }        
	 
	 
	 
	 
        
//	print $HeaderTitle.','.$lawtype.','.$Description.','.$MenuID.','.$login_userid;exit;	
		//
	
             
}

$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) : $login_ostanId.'00000';//شهر
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;//نقش
$g3id=is_numeric($_GET["g3id"]) ? intval($_GET["g3id"]) : 0;//تولیدکننده لوله بودن
$cond="";
/*
clerk کاربران
city نقش
cityid شناسه شهر
*/
if ($g2id>0 || $login_RolesID<>1) $cond=" and clerk.city='$g2id' ";
if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 


if ($login_RolesID==1){ 
    $str='';
	$disabled='';
/*
clerk کاربران
roles نقش ها
city نقش
rolesid شناسه نقش
*/
	$sqlselect="
	SELECT distinct roles.rolesid _value,roles.title _key   FROM clerk
	inner join roles on roles.rolesid=clerk.city 
	order by _key  COLLATE utf8_persian_ci";
	}
if ($login_RolesID<>1) {
    $str=" and substring(clerk.CityId,1,2)=substring($login_CityId,1,2)";
    $str.='and law.ClerkID not in (4,22)';
	$disabled='disabled';
	$sqlselect="
	SELECT distinct roles.rolesid _value,roles.title _key   FROM clerk
	inner join roles on roles.rolesid=clerk.city 
	where roles.rolesID in (2,3,4,9,10,19,20,21)
	order by _key  COLLATE utf8_persian_ci";
	}
$allg2id = get_key_value_from_query_into_array($sqlselect);

/*
clerk کاربران
tax_tbcity7digit شهرها
CityName نام شهر
id شناسه شهر
*/

$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
order by _key  COLLATE utf8_persian_ci";
$allg1id = get_key_value_from_query_into_array($sqlselect);

  $sql = "SELECT count(*) count from law ";
 
       		            	try 
								  {		
									  	  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  $row = mysql_fetch_assoc($result);$count=$row['count']+7;mysql_data_seek( $result, 0 );
/*
clerk کاربران
lawrole نقش قانون
menu جدول منو
clerk.CPI نام کاربر
clerk.DVFS نام خانوادگی کاربر
*/	 
  $sql = "SELECT distinct law.*,clerk.CPI,clerk.DVFS,menu.name linktarget from law 
  left outer join lawrole on lawrole.lawID=law.lawID
  left outer join clerk on clerk.ClerkID=lawrole.ClerkIDR 
  left outer join menu on menu.MenuID=law.MenuID
  
  where ifnull(lawrole.ClerkIDR,0)>0 $str
  order by lawno DESC";
 
 //print $sql;
$result = mysql_query($sql);
if ($g2id==3 && $g3id>0) 
		{
			$join="inner join producers on producers.ProducersID=clerk.BR";
			$cond.="and producers.PipeProducer=$g3id";
		}	
		
 $sql="select clerk.clerkID,clerk.CPI,clerk.DVFS  from clerk 
        inner join roles on roles.RolesID=clerk.city
		$join
		
        where  1=1 
        $cond
        
        order by roles.title";
      
           		            	try 
								  {		
									  	  	   $result1 = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
   

// print $sql;

?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

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

     function SelectAll()
                {
                    if (($("input[id^='clerk']:checked").length*2) == $("input[id^='clerk']").length)
                    $("input[id^='clerk']").prop('checked', false);
                    else
                    $("input[id^='clerk']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value+ '&g2id=' + document.getElementById('g2id').value+ '&g3id=' + document.getElementById('g3id').value;
        
	}
    
    function checklenght(){
		document.getElementById('messagelen').value=(document.getElementById('comments').value.length);
        
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
            <?php include('../includes/header.php'); 
            
            print "
            
            <div id='content'>
                <form action='lawclerk.php' method='post' onSubmit='return CheckForm()' enctype='multipart/form-data'>
                  <td colspan='5'></td>";
        
        
		print select_option('g1id','استان',',',$allg1id,0,'',$disabled,'4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
        print select_option('g2id','نقش',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
		    print  "
                          <tr><td  class='label'>فروشنده:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='g3id' type='text' class='textbox' id='g3id' value='$g3id' onChange='selectpage()'   /></td>";
           
						$cnt=0;
					print "<tr>
        <a onclick=\"SelectAll();\"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                  
               <table width=90%; style='border:2px solid;'>
			  			   <tr>";
        while($row = mysql_fetch_assoc($result1))
        {
            //print '1';
            $clerkID=$row['clerkID'];
            $key=decrypt($row['CPI'])." ".decrypt($row['DVFS']);
            $msg=$row['msg'];
            $mobile=$row['mobile'];
            $password=$row['password'];
            if ($clerkID>0)
            {
                $cnt++;
                $value=$cnt;
                print "<td class='data'>
                <input type='checkbox' id='clerk$value' name='clerk$value'>$key</input></td>
                <td class='data'><input type='hidden' class='textbox' id='clerkID$value' name='clerkID$value' value='$clerkID'> </input></td>
                
                
                ";
                if (($cnt%4)==0)
                    print "</tr><tr>";   
                
            }
        }
		
        print "</tr></table></tr><table><tr>";
		print "<td colspan='2' class='label'>ثبت ابلاغیه :</td>";              
?>
<td colspan="2"> <input type="radio" name="lawtype" <?php  if (isset($lawtype) && $lawtype=="information") echo "checked";?>  value="information">اطلاع رسانی</td>
<td colspan="2"> <input type="radio" name="lawtype"   value="election">نظرخواهی</td>
<td colspan="3"> <input type="radio" name="lawtype"   value="selectform">نظرسنجی</td>
<?php

					$IDUser =$count.'_'.$count;
                  ?> 
	<td colspan="3"></td>
	<td colspan="3" class='data'><input type='file' name='filep' id='filep' value='123' accept='application/jpg'>
		<td> <input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>"></td>
		<td> <input type="hidden" name="path" value ="<?php echo $path; ?>"></td> </tr>
<?php



            
                     $query="select MenuID _value,name _key from menu where parent>0 order by _key  COLLATE utf8_persian_ci";
			         $MenuID = get_key_value_from_query_into_array($query);
             
               print  "
                          <tr><td  class='label'>ترتیب:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                          name='lawno' type='text' class='textbox' id='lawno' value='$count'    /></td>
                                
                          <td  class='label'>عنوان:</td>
                          <td class='data'><input
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                          name='HeaderTitle' type='text' class='textbox' id='HeaderTitle'    /></td>
                         
                         <td  class='label'>شرح:</td>
                          <td class='data' colspan=4><textarea id='Description' colspan='1' name='Description' rows='3' cols='90'  ></textarea></td>
                         
                         ".select_option('MenuID','','',$MenuID,0,'',$disabled,'1','rtl',0,'',0,'','120')."
                         
                         <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                         
                                ";  
                                
             

                         ?>          
                      
                   <table id="records" width="95%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                        
                        	<th width="5%">ردیف </th>
                        	<th width="5%">ترتیب </th>
                        	<th width="5%">عنوان </th>
                        	<th width="30%">شرح</th>
                        	<th width="25%">نقش</th>
                            <th width="10%">تاریخ ابلاغیه</th>
                            <th width="5%"></th>
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
                    $Title='';$rowi=0;
                    while($row = mysql_fetch_assoc($result)){
                        
                        
                      if ($lawtype==1) $lawtypeTitle='نظرخواهی'; else if ($lawtype==2) $lawtypeTitle='نظرسنجی'; else $lawtypeTitle='';
                      if ($lawIDold==0) $lawIDold=$row['lawID'];
                        
                      if ($lawIDold==$row['lawID'])
                             {$Title.= '، '.decrypt($row['CPI'])." ".decrypt($row['DVFS']);$rowi++;}
                       else
                       {
                            $rown++;
                            $deletestr="";
                            $detailstr="";
                            $permitrolsid = array("1","20","23");
                            if (in_array($login_RolesID, $permitrolsid))
                            {
									$deletestr="<a 
								href='law_delete.php?uid=".'2'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
								"' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
								> <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
								
									$detailstr="<a target='_blank'
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
                                                   
                           
                           $lawIDold=$row['lawID'];
                           $Title=decrypt($row['CPI'])." ".decrypt($row['DVFS']); 
                        }
                        
                        $lawno = $row['lawno'];
                        $HeaderTitle = $row['HeaderTitle'];
						$lawtype = $row['lawtype'];
                        $Description=$row['Description'];
                        $lawID = $row['lawID'];
                        $linktarget=$row['linktarget'];
						$SaveDate = gregorian_to_jalali($row['SaveDate']);
                    }
                      $rown++;
                            $deletestr="";
                            $deletestr="";
                            $detailstr="";
                            $permitrolsid = array("1","20","23");
                            if (in_array($login_RolesID, $permitrolsid))
                            {
                                $deletestr="<a 
                            href='law_delete.php?uid=".'2'.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$lawID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                            
                                $detailstr="<a target='_blank'
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
                  
                       
                </form>      
            </div>
			<!-- /header -->

			<!-- content -->
			
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
