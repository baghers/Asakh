<?php

/*

insert/foundation_delete_onlydetail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/foundation_lis.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $appfoundationID=$linearray[0];//شناسه سازه
    $ApplicantMasterID=$linearray[1];//شناسه طرح
    if ($appfoundationID>0)
    {
        /*
        appfoundationID شناسه سازه
        ApplicantMasterID شناسه طرح
        manuallistpriceall فهارس بها
        */

        $query = " DELETE  from manuallistpriceall where appfoundationID='$appfoundationID' and ApplicantMasterID='$ApplicantMasterID'";
              	 	 		  	try 
								  {		
									    $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        
        //print $query;
        
        $query = " DELETE  from manuallistprice where appfoundationID='$appfoundationID' and ApplicantMasterID='$ApplicantMasterID'";
              	 	 		  	try 
								  {		
									    $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
              
    }

    //print $query;
    
        header("Location: "."foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.rand(10000,99999));                                   
                            
?>
