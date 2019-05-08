<?php 

/*
codding/codding4loginhistory.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 

*/ 

include('../includes/connect.php');  ?>
<?php 
include('../includes/check_user.php');  ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");




//
$selectedCityId=$login_CityId;//شناسه شهر

if ($_POST){
    
    if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];//استان
    
    $Datefrom=$_POST['Datefrom'];//از تاریخ
    $Dateto=$_POST['Dateto'];//تا تاریخ
    
    
    if (strlen($_POST['Datefrom'])>0)
        $str.=" and (date(loginhistory.login_time)>='".jalali_to_gregorian($_POST['Datefrom'])."')";
    if (strlen($_POST['Dateto'])>0)
        $str.=" and (date(loginhistory.login_time)<='".jalali_to_gregorian($_POST['Dateto'])."')";
        
        
    if ($_POST['ostan']>0) $str.=" and  substring(clerk.cityid,1,2)=substring('$_POST[ostan]',1,2) ";

    $cond="";
    
    
    if ($_POST['midlogin']>0)
        $cond.=" and clerk.clerkid='$_POST[midlogin]' ";
        
if ($login_isfirstsixmonth==1)
    $addingyimr='12600';
else
    $addingyimr='9000';

    
    $addingyimr='900';  
    /*
    loginhistory جدول تاریخه ورود
    Clerkid شناسه کاربر
    clerk جدول کاربران
    */  
$sql = "SELECT loginhistory.Clerkid,user_ip,logout_time,DATE_ADD(login_time, INTERVAL $addingyimr SECOND) login_time  
,status,ifnull(lastactivity_time,'0') lastactivity_time, clerk.CPI,clerk.DVFS
,date(DATE_ADD(login_time, INTERVAL $addingyimr SECOND) ) login_timed,time(DATE_ADD(login_time, INTERVAL $addingyimr SECOND) ) login_timet,
date(loginhistory.lastactivity_time) lastactivity_timed,time(loginhistory.lastactivity_time) lastactivity_timet,
date(DATE_ADD(logout_time, INTERVAL $addingyimr SECOND)) logout_timed,time(DATE_ADD(logout_time, INTERVAL $addingyimr SECOND)) logout_timet

FROM loginhistory
INNER JOIN clerk ON clerk.clerkid = loginhistory.clerkid $cond where 1=1 $str
ORDER BY loginhistory.login_time DESC";

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

    $allmidlogin[' ']=' ';
    while($row = mysql_fetch_assoc($result))
    {
        $allmidlogin[decrypt($row['CPI'])." ".decrypt($row['DVFS'])]=trim($row['Clerkid']);
    }
    
    $allmidlogin=mykeyvalsort($allmidlogin);
    
mysql_data_seek( $result, 0 );



	
    //print $sql;
    //exit;
}


        



?>


<!DOCTYPE html>
<html>
<head>
  	<title>تاریخچه ورود کاربران</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#Datefrom, #simpleLabel").persiandatepicker();   
                $("#Dateto, #simpleLabel").persiandatepicker();   
				
            });
        
        
    </script>
    
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
                <form action="codding4loginhistory.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">تاریخچه ورود کاربران</h1>
                         <br></br>
                        </tr>
                       
                     
                      
                      <tr>
                      <td  class="label">از تاریخ:</td>
                      <td  class="data"><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" value="<?php if (strlen($Datefrom)>0) echo $Datefrom; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="10" maxlength="10" /></td>
                        <span id="span1"></span>
                     <td class="label">تا تاریخ:</td>
                      <td class="data"><input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" /></td>
                     <span id="span2"></span>
                     </tr>
                     
                     <?php 
                     $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                     where substring(ostan.id,3,5)='00000'
                     order by _key  COLLATE utf8_persian_ci";
                     $allg1id = get_key_value_from_query_into_array($sqlselect);

                     
                     print select_option('midlogin','کاربر',',',$allmidlogin,0,'','','4','rtl',0,'',$midlogin).
                     select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId);
                     
                     ?>
                     
                      <tr>
                                  
        
                      <td  colspan="4"></td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="جستجو" /></td>
                     
                     </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">ردیف</th>
                            <th width="20%">کاربر</th>
                            <th width="20%">زمان ورود</th>
                            <th width="20%">زمان خروج</th>
                            <th width="15%">آی پی</th>
                            <th width="5%">وضعیت</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                     $stateno=0;
                    while($row = mysql_fetch_assoc($result))
                    {
                        $stateno++;
                        $tout='';
                        $tlactivity='';
                        if($row['logout_time']!='0000-00-00 00:00:00') $tout=gregorian_to_jalali($row['logout_timed']).' '.$row['logout_timet'];
                        if($row['lastactivity_time']!='0') $tlactivity=gregorian_to_jalali($row['lastactivity_timed']).' '.$row['lastactivity_timet'];
                        
                        
                        print
                        " <tr>
                            
                            <td>$stateno</td>
                            <td>".decrypt($row['CPI'])." ".decrypt($row['DVFS'])."</td>
                            <td>".gregorian_to_jalali($row['login_timed']).' '.$row['login_timet']."</td>
                            <td>$tout</td>
                            <td>$row[user_ip]</td>
                            <td>$row[status]</td>
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
