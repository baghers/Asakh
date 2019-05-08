<?php 

/*

codding/codding7sms.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding7sms.php
*/
 
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
$kind=0;$ids='';

if($_GET["uid"]!='')
{
	$ids = $_GET["uid"];
	$linearray = explode('_',$ids);
	$kind=$linearray[0];
	$valuec=$linearray[1]*3+1;
	$SaveTime=date('Y-m-d H:i:s');
	$SaveDate=date('Y-m-d');
	$ClerkID=$login_userid;
	if ($kind>0) {
		for($i=2;$i<=$valuec;$i+=3){		 
			 $message='ارسال نام کاربری:'.$linearray[$i];
			 $clerkIDR=$linearray[$i+1];
			 $ErrorDescription=$linearray[$i+2];

            /*
            smssent جدول پیامک های ارسالی
            kind نوع
            ClerkIDR کاربر دریافت کننده
            message متن
            ErrorDescription خطا
            */
			   $query = "INSERT INTO smssent(kind,ClerkIDR,message,ErrorDescription,SaveTime,SaveDate,ClerkID) 
				VALUES(3,'".$clerkIDR."','$message','$ErrorDescription','$SaveTime','$SaveDate','$ClerkID');";
				mysql_query($query);
			}
		}
}

$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) : $login_ostanId.'00000';
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;


      //print 'sa'.$_GET["g1id"];
      //exit;

      $query="select siteaddress  from tax_tbcity7digit
        where  id='$g1id' ";
      

	  	  						try 
								  {		
									          $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

      $row = mysql_fetch_assoc($result);
      $siteaddress=$row['siteaddress'];

$cond="";
if ($g2id>0) $cond=" and clerk.city='$g2id' ";
if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 
    
    /*
clerk جدول کاربران
city نقش
cityid شناسه شهر
*/                   

if ($_POST['submit1'])
{
    $g1id=$_POST['g1id'];
    $g2id=$_POST['g2id'];
    $cond="";
    if ($g2id>0) $cond=" and clerk.city='$g2id' ";
    if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 
    
            
}
if ($login_designerCO==1) {$selec='';$ost='';} else {
    $selec="where roles.rolesid not in (1)";
	$ost="and substring(clerk.cityid,1,2)='$login_ostanId'";
	}

/*
clerk جدول کاربران
roles جدول نقش ها
tax_tbcity7digit جدول شهر ها
producers جدول تولیدکنندگان
designerco جدول طراحان
operatorco جدول پیمانکاران
*/

$sqlselect="
SELECT distinct roles.rolesid _value,roles.title _key FROM clerk
inner join roles on roles.rolesid=clerk.city 
$selec $ost
order by _key  COLLATE utf8_persian_ci";
$allg2id = get_key_value_from_query_into_array($sqlselect);
//print $sqlselect;
                          
$sqlselect="select distinct ostan.CityName _key,ostan.id _value FROM clerk
inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
where 1=1 $ost
order by _key  COLLATE utf8_persian_ci";
$allg1id = get_key_value_from_query_into_array($sqlselect);
//print $sqlselect;
             
        
$sql = "select clerk.CPI,clerk.DVFS,smssent.message,smssent.ErrorDescription,smssent.SaveDate from smssent 
left outer join clerk on clerk.clerkid=smssent.ClerkIDR
where kind=3  $cond  $ost order by SaveDate desc";

//print $sql;


	  						try 
								  {		
									$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$cond11=" and clerk.city='$g2id' ";
$cond11.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 


        $sql="select clerk.clerkID,clerk.CPI,clerk.DVFS,WN,NOC,concat('$siteaddress
User:',NOC
        ,'
Pass:',WN
        ,'
نقش:',case clerk.city when 2 then 'مجری' when 3 then 'فروشنده' when 9 then 'طراح' when 10 then 'طراح' else 'مدیریت' end
        ,'
        ',case clerk.city when 2 then concat('شرکت:',operatorco.title) when 3 then concat('شرکت:',producers.title) 
        when 9 then concat('شرکت:',designerco.title) when 10 then concat('شرکت:',designerco.title) else '' end,'                         ') msg 
            
            ,clerk.mobile mobile from clerk 
            inner join roles on roles.RolesID=clerk.city
        left outer join producers on producers.producersid=clerk.BR
        left outer join designerco on designerco.designercoid=clerk.MMC
        left outer join operatorco on operatorco.operatorcoid=clerk.HW
        

        where  ifnull(notgetsms,0)=0 and length(ifnull(mobile,'0'))=10 $cond11 $ost
        order by clerk.DVFS,clerk.CPI";
       
								try 
								  {		
									 $result1 = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
        
		        //print $sql;

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
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value+ '&g2id=' + document.getElementById('g2id').value;
        
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
           //     <form action='http://178.131.48.167:8888/localsms.php' method='post'>
          	 //print $_SERVER[SERVER_NAME].'*'.$_SERVER[HTTP_HOST];
 print strtoupper($_SERVER[SERVER_NAME]);
 if (strtoupper($_SERVER[SERVER_NAME])=='LOCALHOST' || strtoupper($_SERVER[SERVER_NAME])=='127.0.0.1')
       print "            
            <div id='content'>
 	            	     <form action='http://localhost:8888/localsms.php' method='post'>
           <td colspan='3'></td>";
       else 
		      print "            
            <div id='content'>
 	            	     <form action='http://192.168.1.111:8888/localsms.php' method='post'>
           <td colspan='3'></td>";
       
		
if ($kind>0) {
$valuep=($valuec-1)/3;
echo '<p class="note">'.$message.'-'.$ErrorDescription.'-'.$valuep.'</p>';


}
        print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
        print select_option('g2id','نقش',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
                     

        $cnt=0;
        print "<tr>
               <table style='border:2px solid;'><tr>";
        while($row = mysql_fetch_assoc($result1))
        {
            //print '1';
            $clerkID=$row['clerkID'];
            $key = decrypt($row['CPI'])." ".decrypt($row['DVFS']);
			$msg=$row['msg'];
            $mobile=$row['mobile'];
            $password=$row['WN'];
            $user=$row['NOC'];
            
            if ($clerkID>0)
            {
                $cnt++;
                $value=$cnt;
				$kind=3;
				print "<td class='data'>
                <input type='checkbox' id='clerk$value' name='clerk$value'>$key</input></td>
                <td class='data'><input type='hidden' class='textbox' id='msg$value' name='msg$value' value='$msg'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='mobile$value' name='mobile$value' value='$mobile'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='password$value' name='password$value' value='$password'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='user$value' name='user$value' value='$user'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='clerkID$value' name='clerkID$value' value='$clerkID'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='kind' name='kind' value='$kind' > </input></td>
              
                
                ";
                if (($cnt%4)==0)
                    print "</tr><tr>";   
                
            }
        }
        print "</tr></table></tr>";
        
        print "<td colspan='2'><input name='submit1' type='submit' class='button' id='submit1' value='ارسال'/></td>";

            
            ?>      
                  
                  
                  <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">ردیف</th>
                        	<th width="10%">کاربر</th>
                        	<th width="60%">متن</th>
                            <th width="5%">وضعیت</th>
                        	<th width="10%">تاریخ</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                             
                   <?php
                    $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {

                        $name = decrypt($row['CPI'])." ".decrypt($row['DVFS']);
                        
                        $linearray = explode('Pass',$row['message']);
                        
                        
                        
                        $message = $linearray[0].'Pass:*****'.strstr($linearray[1],"نقش");
                        $ErrorDescription = $row['ErrorDescription'];
                        $SaveDate = gregorian_to_jalali($row['SaveDate']);
                        
                        $rown++;
?>                      

                        <tr>
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $message; ?></td>
                            <td><?php echo $ErrorDescription; ?></td>
                            <td><?php print $SaveDate; ?></td>
                        </tr><?php

                    }

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
