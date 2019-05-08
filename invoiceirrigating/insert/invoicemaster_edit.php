<?php 

/*

insert/invoicemaster_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicemaster_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

//print $login_RolesID.'';
if ($login_Permission_granted==0) header("Location: ../login.php");
if (! $_POST)
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    //invoicedetail ریز لوازم
    $query = "SELECT count(*) cnt 
    FROM invoicedetail 
    where  invoicemasterID = '".$id ."'";
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
    $actualcnt = $resquery['cnt'];

    /*
    invoicemaster جدول  لیست پیش فاکتورها
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    invoicemasterID شناسه لیست
    */  

    $query = "SELECT invoicemaster.*,case applicantmasterdetail.ApplicantMasterIDsurat=invoicemaster.ApplicantMasterID 
												when 1 then 1 else 0 end issurat 
    FROM invoicemaster 
    inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID or
											applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID or 
												applicantmasterdetail.ApplicantMasterIDsurat=invoicemaster.ApplicantMasterID)
    where  invoicemasterID = '".$id ."'";
  //  print $query;exit;
       				  	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    
    $resquery = mysql_fetch_assoc($result);
	
    $issurat = $resquery['issurat'];
    
    $Serial = $resquery['Serial'];
    $invoicemasterID = $resquery['InvoiceMasterID'];
    $Title = $resquery['Title'];
    $linearray = explode('_',$resquery['Description']);
    $Description=$linearray[0];
    $Description2=$linearray[1];
    $Description3=$linearray[2];
    $Gadget1ID=$linearray[3];
    $subprj=$resquery['appsubprjID'];
    $Rowcnt = $resquery['Rowcnt'];
    $producersid = $resquery['ProducersID'];
    $ApplicantMasterID= $resquery['ApplicantMasterID'];
    $InvoiceDate= $resquery['InvoiceDate'];
    $pricenotinrep= $resquery['pricenotinrep'];
    $costnotinrep= $resquery['costnotinrep'];
    $InvoiceMasterIDmaster= $resquery['InvoiceMasterIDmaster'];
    $PriceListMasterID=$resquery['PriceListMasterID'];
    $taxless=$resquery['taxless'];
    $Discont=$resquery['Discont'];
    $TransportCost=$resquery['TransportCost'];
    
	
	
    //print $pricenotinrep;
    if ($pricenotinrep>0)      
       $pricenotinrepselected="checked";
       
    if ($costnotinrep>0)      
       $costnotinrepselected="checked";
                        
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
        if  ($issurat==1 || $login_RolesID==1)
            $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID";
                
        else if ($operatorProducersID==$producersid)//شرکت مجری
        {
            $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                WHERE   (pfo=1 or pricelistmaster.PriceListMasterID='$PriceListMasterID')";
        }
        else//فروشنده
        {
            if ($login_RolesID==9)//مشاور
                $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                inner join pricelistdetail on pricelistdetail.PriceListMasterID=pricelistmaster.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$producersid'  and 
                
                ( pfd=1 or pricelistmaster.PriceListMasterID='$PriceListMasterID')";
            else if ($login_RolesID==2) //مجری
                $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                inner join pricelistdetail on pricelistdetail.PriceListMasterID=pricelistmaster.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$producersid'  and 
                
                ( pfo=1 or pricelistmaster.PriceListMasterID='$PriceListMasterID')";
            else if ($login_RolesID==3) //فروشنده
                $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                inner join pricelistdetail on pricelistdetail.PriceListMasterID=pricelistmaster.PriceListMasterID
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.producersid ='$producersid'  and 
                
                ( pfp=1 or pricelistmaster.PriceListMasterID='$PriceListMasterID')";
            else
                $queryPriceListMasterID = "
                select pricelistmaster.PriceListMasterID as _value,CONCAT(year.Value,' ',month.Title) as _key from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                WHERE   pricelistmaster.pricelistmasterid 
                in (select pricelistmasterid from pricelistmasterenabled where substring(ostan,1,2)='$login_ostanId')
                or pricelistmaster.PriceListMasterID='$PriceListMasterID'";
        }
        
        //print $queryPriceListMasterID;
                        
    if (!$invoicemasterID) header("Location: ../logout.php");
}

    $register = false;

if ($_POST){
    if (strlen($_POST['InvoiceDate'])<10 )
    {
        print strlen($_POST['InvoiceDate']);
        echo "تاریخ پیش فاکتور نا معتبر می باشد";
        exit;
    }
	
    $invoicemasterID = $_POST['invoicemasterID'];
    $PriceListMasterID=$_POST['PriceListMasterID'];
    /////////////////////////////////بررسی قیمت ها در لیست قیمت جدید
    
    $querycheck = "SELECT invoicedetail.ToolsMarksID
    ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) fullname
    ,
    case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
            syntheticgoodsprice.price end Price
            
     from invoicedetail
        left outer join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
        left outer join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
                                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    left outer join operator on operator.operatorID=gadget3.operatorID
                                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid

    left outer join pricelistmaster on pricelistmaster.PriceListMasterID='$PriceListMasterID'
        
        left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID 
        and ifnull(pricelistdetail.hide,0)=0 and 
        pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID 
        else toolspref.ToolsMarksIDpriceref end) 
        
        left outer join syntheticgoodsprice on syntheticgoodsprice.PriceListMasterID='$PriceListMasterID' and 
        syntheticgoodsprice.gadget3ID=gadget3.gadget3ID
    
    where invoicedetail.Number>0 and invoicemasterID='$invoicemasterID' 
    and ifnull(case  gadget3.gadget2id in (202,376,495,494) when 1 then 1 else
    case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
            syntheticgoodsprice.price end end,0)<=0";  
    
	   				  	  	try 
								  {		
									  $result = mysql_query($querycheck);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $Err="";
    while($row = mysql_fetch_assoc($result))
    {
            $Err.=$row['fullname']."<br>";
    }    
    if ($Err!="")
    {
        print "در این لیست قیمت کالاهای زیر فاقد قیمت می باشند<br>$Err";
        exit;    
    }
    
    
    ////////////////////////////////////////////////////////////////
    
    $Serial = $resquery['Serial'];
    
	$Serial = $_POST['Serial'];
    $Title = $_POST['Title'];
    $Description = "$_POST[Description]_$_POST[Description2]_$_POST[Description3]_$_POST[Gadget1ID]";
    $appsubprjID=$_POST['subprj'];
    $Rowcnt = $_POST['Rowcnt'];
    $producersid = $_POST['producersid'];
    $ApplicantMasterID = $_POST['ApplicantMasterID'];
    $InvoiceDate= $_POST['InvoiceDate'];
    
    $_POST['pricenotinrep'] = $_POST['pricenotinrep'];
    $pricenotinrep= $_POST['pricenotinrep'];
    
    
    $_POST['costnotinrep'] = $_POST['costnotinrep'];
    $costnotinrep= $_POST['costnotinrep'];
    
    $_POST['permanenttaxless']=$_POST['permanenttaxless'];
        $permanenttaxless=$_POST['permanenttaxless'];
 
 	$query = "SELECT operatorcoid FROM `applicantmaster` where `applicantmaster`.`ApplicantMasterID` ='$ApplicantMasterID' ";
			    $result = mysql_query($query);
				$row = mysql_fetch_assoc($result);
				$operatorcoid=$row['operatorcoid'];
	//print $operatorcoid.$query;
		
	 
    $taxlessstrup="";
   // print $producersid;exit;
    if ($_POST['taxless']!=2) //وضعیت بدون مالیات دائمی
    {
        //print $_POST['pricenotinrep'];
        //exit(0);
    
   	    $query = "SELECT ValueInt FROM supervisorcoderrquirement WHERE KeyStr ='operatorProducersID' ";
	    $result = mysql_query($query);
	    $row = mysql_fetch_assoc($result);
         $operatorProducersID=$row['ValueInt'];
		//print $operatorProducersID.'=='.$producersid.'=='.$login_RolesID.'=='.$operatorcoid.'=='.$login_OperatorCoID;
       if ($operatorProducersID==$producersid)//شرکت مجری
        {
			 if ($login_OperatorCoID)
				$queryp = "SELECT valueaddedvalidate FROM operatorco WHERE operatorcoid ='$login_OperatorCoID' "; 
				else
				$queryp = "SELECT valueaddedvalidate FROM operatorco WHERE operatorcoid ='$operatorcoid' ";   
			
			//	print $Description=$queryp; exit;				
        }
        else//فروشنده
        {
            $queryp = "SELECT valueaddedvalidate FROM producers WHERE producersid ='$producersid' ";
		//	print $queryp; exit;
        }
		
     //     print $Description=$queryp; exit;
    	
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
            $taxless='1';  // print $valueaddedvalidate.'-'.$InvoiceDate;
       
        
        
    
        if ($permanenttaxless)   
            $taxless='2'; 
        
        if ($_POST['InvoiceMasterIDmaster']>0 && $taxless==0) 
          $taxlessstrup="";
        else
          $taxlessstrup=",taxless='$taxless'";      
    }
	
	

        
       
          
		if ($PriceListMasterID>0)
        $PriceListMasterIDstr=",PriceListMasterID ='$PriceListMasterID',InvoiceDate='$InvoiceDate' ";
		$query = "
		UPDATE invoicemaster SET
		Serial = '" . $Serial . "' 
        $PriceListMasterIDstr
        $taxlessstrup
        ,Title = '" . $Title . "',
		Description = '" . $Description . "', 
        appsubprjID='$appsubprjID',
		Rowcnt = '" . $Rowcnt . "',  
		pricenotinrep = '" . $pricenotinrep. "',  
		costnotinrep = '" . $costnotinrep. "',  
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE invoicemasterID = " . $invoicemasterID . ";";
        
        
        //print $query;EXIT;
        
        
			   					try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $register = true;

}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح پیش فاکتور/لیست لوازم</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    
    <script>
function CheckForm()
{
    //alert(document.getElementById('actualcnt').value);
    
    //alert(document.getElementById('Rowcnt').value);
    if ((document.getElementById('Rowcnt').value*1)>45 || (document.getElementById('Rowcnt').value*1)<=0)
    {
        alert("تعداد ردف پیش فاکتور نا معتبر می باشد. تعداد ردیف های پیش فاکتور/لیست لوازم حد اکثر 35 ردیف می باشد");return false;
    }
    if ((document.getElementById('Rowcnt').value*1)<(document.getElementById('actualcnt').value*1))
    {
        alert('تعداد ردیف های پیش فاکتور وارد شده کمتر از مقدار ثبت شده می باشد!');return false;
    }    
  return true;  
}

                
    </script>
    
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Serial = "";
						$ProducersID = "";
                        header("Location: "."invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999));
                        
                        
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="invoicemaster_edit.php" method="post"  onSubmit="return CheckForm()">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
                     <tr>
                      <td width="20%" class="label">سريال:</td>
                      <td width="80%" class="data"><input name="Serial" type="text" class="textbox" id="Serial" value="<?php echo $Serial ?>" size="6" maxlength="6" /></td>
                     </tr>
                     <tr>
                      <td class="label">عنوان پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="Title" type="text" class="textbox" id="Title" value="<?php echo $Title; ?>"  size="15" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">تاریخ پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="InvoiceDate"   <?php  
                      
                      if ($InvoiceMasterIDmaster>0)
                            print 'readonly';
                            
                      ?>   type="text" class="textbox" id="InvoiceDate"  value="<?php echo $InvoiceDate; ?>" size="10" maxlength="10" /></td>
                     </tr>
                     <tr>
                      <td class="label">قیمت در گزارش نهایی اعمال نشود:</td>
                      <td class="data"><input name="pricenotinrep" type="checkbox" id="pricenotinrep"  value="1" <?php echo $pricenotinrepselected; ?> /></td>
                     </tr>
                     <tr>
                      <td class="label"><?php echo str_replace(' ', '&nbsp;'," هزینه های اجرایی مربوط به این پیش فاکتور در گزارش نهایی اعمال نشود:");
                       ?></td>
                      <td class="data"><input name="costnotinrep" type="checkbox" id="costnotinrep"  value="1" <?php echo $costnotinrepselected; ?> /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">حذف مالیات بر ارزش افزوده:</td>
                      <td class="data"><input name="permanenttaxless" type="checkbox" id="permanenttaxless"  
                      value="1" <?php 
                      if ($taxless==2)
                      echo "checked"; 
                      
                      echo " /></td>
                      <td class=\"label\" >".str_replace(' ', '&nbsp;',"(در صورت حذف، برای اعمال دوباره مالیات با مشاور ناظر یا ناظر عالی تماس بگیرید.)")."</td>";
                      ?>
                     
                      
                     </tr>
                     
                     <?php
                     
                //print $PriceListMasterID;
                        //if ($InvoiceMasterIDmaster>0)
                            //$disabledstring='disabled';
                        //else 
                       //     $disabledstring='';
    				 $ID = get_key_value_from_query_into_array($queryPriceListMasterID);
                     print "<td id='PriceListMasterIDlbl'  class='label'>لیست قیمت:</td>".
                     select_option('PriceListMasterID','',',',$ID,0,'',"$disabledstring",'1','rtl',0,'',$PriceListMasterID,'','135');
                     
                     
                    $query=
                     "
                    select Title _key, Gadget1ID _value from gadget1 where IsCost<>1
                    
                    order by  _key COLLATE utf8_persian_ci";
                    
                    //print $query;
                    
    				 $ID = get_key_value_from_query_into_array($query);
                     print "</tr><tr>".select_option('Gadget1ID','گروه کالا',',',$ID,0,'','','1','rtl',0,'',$Gadget1ID,'','135');
                     
                     $query=
                     "select Title _key, appsubprjID _value from appsubprj where ApplicantMasterID='$ApplicantMasterID'
                    
                    order by  _key COLLATE utf8_persian_ci";
                    
    				 $ID = get_key_value_from_query_into_array($query);
                     print "</tr><tr>".select_option('subprj','زیر پروژه',',',$ID,0,'','','1','rtl',0,'',$subprj,'','135');
                      
                    /*
                     $limited = array("9");
                     if ( in_array($login_RolesID, $limited))
					   $query='select ProducersID as _value,Title as _key from producers where ProducersID=148 order by Title  COLLATE utf8_persian_ci';
                     else $query='select ProducersID as _value,Title as _key from producers order by Title  COLLATE utf8_persian_ci';
                            
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('ProducersID','صادرکننده',',',$ID,0,'','','1','rtl',0,'',$ProducersID,'','','');
                        */
					  ?>

                     <tr>
                      <td class="label">تعداد ردیف های پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="Rowcnt" type="text" class="textbox" id="Rowcnt"  value="<?php echo $Rowcnt; ?>"  size="10" maxlength="10" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">توضیحات:</td>
                      <td class="data"><input name="Description" type="text" class="textbox" id="Description"  value="<?php echo $Description; ?>"  size="30" maxlength="130" /></td>
                     </tr>
                     
                     <tr>
                      <td class="data"><input name="Description2" type="hidden" class="textbox" id="Description2"  value="<?php echo $Description2; ?>"  size="30" maxlength="130" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="Description3" type="hidden" class="textbox" id="Description3"  value="<?php echo $Description3; ?>"  size="30" maxlength="130" /></td>
                     </tr>
                     
			<?php if ($login_RolesID==1) {?>
                     <tr>
                      <td class="label">ارزش افزوده:</td>
                      <td class="data"><input name="taxless" type="text" class="textbox" id="taxless"  value="<?php echo $taxless; ?>"  size="5" maxlength="130" /></td>
                     </tr>
                     
					 <tr>
                      <td class="label">هزینه جانبی</td>
                      <td class="data"><input name="TransportCost" type="text" class="textbox" id="TransportCost"  value="<?php echo $TransportCost; ?>"  size="15" maxlength="130" /></td>
                     </tr>
                     <tr>
                      <td class="label">تخفیف:</td>
                      <td class="data"><input name="Discont" type="text" class="textbox" id="Discont"  value="<?php echo $Discont; ?>"  size="15" maxlength="130" /></td>
                     </tr>
			<?php } 
            else
            {
                echo "<tr>
                      <td class='data'><input name='taxless' type='hidden' class='textbox' id='taxless'  value='$taxless'   /></td>
                     </tr>";
            }
            
            ?>         
					 
                     <tr>
                      <td class="data"><input name="invoicemasterID" type="hidden" class="textbox" id="invoicemasterID"  value="<?php echo $invoicemasterID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     <tr>
                      <td class="data"><input name="InvoiceMasterIDmaster" type="hidden" class="textbox" id="InvoiceMasterIDmaster"  value="<?php echo $InvoiceMasterIDmaster; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     
                     <tr>
                      <td class="data"><input name="producersid" type="hidden" class="textbox" id="producersid"  value="<?php echo $producersid; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     <tr>
                      <td class="data"><input name="ApplicantMasterID" type="hidden" class="textbox" id="ApplicantMasterID"  value="<?php echo $ApplicantMasterID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="actualcnt" type="hidden" class="textbox" id="actualcnt"  value="<?php echo $actualcnt; ?>"   /></td>
                     </tr>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح پيش فاکتور " /></td>
                     </tr>
                    </tfoot>
                   </table>
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