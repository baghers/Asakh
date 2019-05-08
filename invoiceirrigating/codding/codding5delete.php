<?php

/*

codding/codding5delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5desert.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه شهر
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    applicantmaster جدول مشخصات طرح
    */
    
    //exit(0);
    $query = "SELECT applicantmaster.CityId FROM applicantmaster WHERE applicantmaster.CityId  ='$id'
    union all 
    select id CityId from tax_tbcity7digit
    where  substring(tax_tbcity7digit.id,1,4)=substring($id,1,4) and substring($id,5,3)='000' and tax_tbcity7digit.id<>'$id'
    ";
    						try 
								  {		
									$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $resquery = mysql_fetch_assoc($result);
	$CityId = $resquery["CityId"];
    
    if (strlen($CityId)>0) 
    {
        print " این مقدار    گردش دارد ";
        exit;
    }

    $query = " DELETE FROM tax_tbcity7digit where id='$id'";
    						try 
								  {		
									$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //print $query;
    if (substr($id,4,3)=='000')
    header("Location: codding5desert.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).substr($id,0,2)."00000".rand(10000,99999));
    else
    header("Location: codding5countries.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).substr($id,0,4)."000".rand(10000,99999));
                                            
                            
?>
