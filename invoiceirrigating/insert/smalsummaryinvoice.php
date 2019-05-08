<?php 

/*

insert/smalsummaryinvoice.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
appinvestigation/sendtoanjoman.php
*/


include('../includes/connect.php');
include('../includes/check_user.php'); 
include('../includes/functions.php');



/////////////////////////////////////////////////


///////////////////////////////////////////////            
            

//if ($login_Permission_granted==0) header("Location: ../login.php");

if (!($login_RolesID>0)) $login_RolesID=0;
$register=0;
if ($_POST)
    {
        if (!($login_userid>0)) 
        {
            header("Location: ../login.php");
            exit;
        }       
        if ($_POST['ApplicantstatesID']==30)//وضعیت تایید پیش فاکتور
        {
            /*
            ApplicantMasterID شناسه طرح
            producerapprequest جدول پیشنهادات قیمت لوله
            state منتخب مشخص می باشد 
            
            در این پرس و جو بررسی می شود که آیا پروژه در حال انتخاب تولید کننده لوله پلی اتیلن می باشد یا خیر
            در صورتی که پروژه در حال انتخاب تولید کننده لوله پلی اتیلن باشد امکان تغییر اطلاعات طرح وجود ندارد
            */
            $query = "select  ApplicantMasterID from producerapprequest 
        where ApplicantMasterID='$_POST[ApplicantMasterID]' and ApplicantMasterID not in (select ApplicantMasterID from producerapprequest where state=1) ";
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
            if ($row['ApplicantMasterID']>0)
            {
                echo "پروژه در حال انتخاب تولید کننده لوله پلی اتیلن می باشد و امکان تایید نهایی پیش فاکتور وجود ندارد";
                exit;
            }
        }
        
        //در صورتی که کاربر لاگین شده پیمانکار، مشاور طراح و مدیریت آب و خاک باشد ثبت بودن کد رهگیری چک می شود
		if (in_array($login_RolesID, array(5,2,9)))
		{
		  /*
            applicantmaster جدول مشخصات طرح
            BankCode کد رهگیری
            ApplicantstatesID شناسه طرح
            TotlainvoiceValues جمع کل لوازم طرح
            LastFehrestbaha جمع کل فهرست بهای طرح
            */
            $query = "SELECT BankCode,ApplicantstatesID FROM applicantmaster where  ApplicantMasterID='$_POST[ApplicantMasterID]' ";
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
            $curbankcode=$row['BankCode'];
            
            $currApplicantstatesID=$row['ApplicantstatesID'];
    		if (strlen($row['BankCode'])==0)
            {
                print "لطفا قبل از تغییر وضعیت کد رهگیری طرح را ثبت نمایید.";
                exit;
            }   
        }
    
            
        
		if (substr($login_CityId,0,2)=='19')
        {
            if ($login_RolesID==10 && $_POST['operatorcoid']>0 )//نظارت بر اجرا
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    //print $_POST['designercocontractIDold'].$_POST['designercocontractID'];exit;
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                        /*
                        applicantcontracts جدول قرارداد های پروژه ها
                        ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                        designercocontractID شناسه قرارداد مشاور
                        designercocontract جدول قرارداد مشاوران
                        contracttypeID شناسه قرارداد
                        prjtypeid نوع پروژه
                        */
                        //حذف قرارداد ثبت شده
                        mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                         and designercocontractID in (select designercocontractID from designercocontract where 
                         designercocontract.contracttypeID='1' and 
                                        designercocontract.prjtypeid='$_POST[prjtypeid]' ); ");
                        //ثبت قرارداد جدید
                     mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                        VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                        date('Y-m-d H:i:s') . "','$login_userid')  ");
          
                    }
                    
                     
            
                }
                else
                {
                    echo "شرکت محترم ناظر، لطفا قرارداد نظارت این پروژه را انتخاب نمایید.";
                    exit;
                }
            }  
            if ($login_RolesID==9 )//قرارداد مطالعات
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    //print $_POST['designercocontractIDold'].$_POST['designercocontractID'];exit;
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                        /*
                        applicantcontracts جدول قرارداد های پروژه ها
                        ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                        designercocontractID شناسه قرارداد مشاور
                        designercocontract جدول قرارداد مشاوران
                        contracttypeID شناسه قرارداد
                        prjtypeid نوع پروژه
                        */
                        mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                     and designercocontractID in (select designercocontractID from designercocontract where 
                     designercocontract.contracttypeID='4' and 
                                    designercocontract.prjtypeid='$_POST[prjtypeid]' ); ");
                     mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                date('Y-m-d H:i:s') . "','$login_userid')  ");
          
                    }
                    
                     
            
                }
                else
                {
                    echo "شرکت محترم  لطفا قرارداد  این پروژه را انتخاب نمایید.";
                    exit;
                }
            } 
            
                                    
            if ($login_RolesID==11 )//قرارداد بازبینی
            {
                if ($_POST['designercocontractID']>0 && $_POST['applicantmasterdetailID']>0)
                {
                    //print $_POST['designercocontractIDold'].'s'.$_POST['designercocontractID'];exit;
                    if ($_POST['designercocontractID']!=$_POST['designercocontractIDold'])
                    {
                        /*
                        applicantcontracts جدول قرارداد های پروژه ها
                        ApplicantMasterdetailID شناسه جدول قرارداد پروژه
                        designercocontractID شناسه قرارداد مشاور
                        designercocontract جدول قرارداد مشاوران
                        contracttypeID شناسه قرارداد
                        prjtypeid نوع پروژه
                        */
                        mysql_query("delete from applicantcontracts where ApplicantMasterdetailID='$_POST[applicantmasterdetailID]' 
                     and designercocontractID in (select designercocontractID from designercocontract where 
                     designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$_POST[prjtypeid]'  ");
                     mysql_query("insert into applicantcontracts (ApplicantMasterdetailID,designercocontractID,SaveDate,SaveTime,ClerkID) 
                VALUES ('$_POST[applicantmasterdetailID]','$_POST[designercocontractID]','" . date('Y-m-d') . "','" . 
                date('Y-m-d H:i:s') . "','$login_userid')  ");
          
                    }
                    
                     
            
                }
                else
                {
                    echo "شرکت محترم  لطفا قرارداد  این پروژه را انتخاب نمایید.";
                    exit;
                }
            }  
        }
        
        
		        
        
  
             $ApplicantMasterID=$_POST['ApplicantMasterID'];
             $Description=$_POST['Description'];
             $ApplicantstatesID=$_POST['ApplicantstatesID'];
            $SaveTime=date('Y-m-d H:i:s');
            $SaveDate=date('Y-m-d');
            $ClerkID=$login_userid;
            $query = "SELECT *  FROM ApplicantMasterID  where ApplicantMasterIDmaster='$ApplicantMasterID'";
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
            if ($row['ApplicantMasterID']>0) return;   
            //پرس و جوی محاسبه شماره تغییر وضعیت بعدی طرح      
            $query = "SELECT max(stateno)+1 stateno FROM appchangestate 
                     where ApplicantMasterID='$ApplicantMasterID' ";
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
            $maxstateno=$row['stateno'];
            //پرس و جوی استخراج مشخصات تغییر وضعیت فعلی طرح
             $query = "SELECT applicantstatesID 
             FROM appchangestate 
             where ApplicantMasterID='$ApplicantMasterID' and stateno='".($maxstateno-1)."'";
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
             
             
            if (($ApplicantstatesID>0) &&($_POST['ApplicantMasterID']>0) && ($row['applicantstatesID']!=$ApplicantstatesID))
            {
                $applicantmasteridold=$_POST['ApplicantMasterID'];
				if ($ApplicantstatesID==30)
                {
                    $SaveTime=date('Y-m-d H:i:s');
                    $SaveDate=date('Y-m-d');
                    $ClerkID=$login_userid;
                    insertsurat($applicantmasteridold,$Description,$login_userid,$_server, $_server_user, $_server_pass,$_server_db);
                }
                else
                {
                     /*
                        appchangestate جدول تغییر وضعیت طرح
                        ApplicantMasterID شناسه طرح
                        stateno شماره تغییر وضعیت
                        applicantstatesID شناسه تغییر وضعیت
                        Description توضیح
                        SaveTime زمان
                        SaveDate تاریخ 
                        ClerkID کاربر
                    */
                    $querytr= "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES(
                    '$applicantmasteridold',$maxstateno,'$ApplicantstatesID','$Description', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".
                $login_userid."');";
               
                				try 
								  {		
									  	 mysql_query($querytr);		  		
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                
                
                }   
            }
            $register=1;            
            header("Location: ".$_POST['sref']);
            exit();     
        
    }
    else
    {
        $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        //print $ids.'salam';
        $linearray = explode('_',$ids);
        $ApplicantMasterID=$linearray[0];
        //print $type;
        if (!($ApplicantMasterID>0))
        {
            print "آی دی طرح ناشناخته است";
            exit;
        }
        /*
            invoicemaster جدول لیست لوازم و پیش فاکتورها
            invoicedetail جدول ریز لوازم و پیش فاکتور ها
            invoicemasterid شناسه پیش فاکتور و لیست لوازم
            ApplicantMasterID شناسه طرح
        */
        
                    $query = "
            select count(*) cnt from invoicemaster 
        inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
        where  ApplicantMasterID='$ApplicantMasterID' ";
           
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
            $cnt=$row['cnt'];
            
            
    		if ($cnt<=0)
            {
                print "لطفا قبل از تغییر وضغیت لیست لوازم/پیش فاکتور طرح را ثبت نمایید.";
            if ($login_RolesID!=1)
            exit;
            } 
            


        
        $DesignerCoID=$linearray[2];//شرکت مشاور طراح
        $OperatorCoID=$linearray[3];//شناسه شرکت پیمانکار
        $ApplicantstatesID=$linearray[4];//شناسه طرح
        $PCoID=$linearray[5];//شناسه سازه طرح
        $PCotitle=$linearray[6];//ععنوان
        $CoTitleinPrint="";
        
        /*
        applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
        لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
        این جدول دارای ستون های ارتباطی زیر می باشد
        ApplicantMasterID شناسه طرح مطالعاتی
        ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
        ApplicantMasterIDsurat شناسه طرح صورت وضعیت
        */
        
                $query1 ="
        select applicantmasterdetail.ApplicantMasterID,applicantmasterdetail.prjtypeid,ApplicantMasterdetailID from applicantmasterdetail
        where applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or 
        applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or
        applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID'";
        
						try 
								  {		
									  	  $result1 = mysql_query($query1);		  		
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  		$row1 = mysql_fetch_assoc($result1);     
        $ApplicantMasterIDd=$row1['ApplicantMasterID'];
        
        $ApplicantMasterdetailID=$row1['ApplicantMasterdetailID'];
        $prjtypeid=$row1['prjtypeid'];
        
        
        if ($DesignerCoID>0) 
        {
            $returnID=$DesignerCoID.'_1';
            $CoTitleinPrint="مهندسین مشاور طراح";
        }
        else if ($OperatorCoID>0) 
        {
            $returnID=$OperatorCoID.'_2';
            $CoTitleinPrint="شرکت مجری";
        }
         

        
        
                 /* 
           applicantmaster جدول مشخصات طرح
           BankCode کدرهگیری طرح
           belaavaz بلاعوض
           criditType تجمیع بودن یا نبودن
           LastTotal جمع کل هزینه های طرح
           private یکی از ویژگی های طرح می باشد که در صورتی که شرکت ها بخواهند طرح تستی و آزمایشی داشته باشند آنرا شخصی می کنند								
           CostPriceListMasterID شناسه سال هزینه های اجرایی طرح 
           creditsourceID شناسه جدول منبع تامین اعتبار
           DesignerCoIDnazer شناسه مشاور بازبین
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           costpricelistmaster هزینه های اجرایی طرح ها
           year جدول سال
           costpricelistmaster هزینه های اجرایی طرح ها
           creditsource جدول منابع اعتباری
           designerco جدول شرکت های طراح
           designer جدول طراحان
           designsystemgroups سیستم آبیاری
           manuallistprice جدول ثبت هزینه های اجرایی طرح
           manuallistpriceall جدول فهارس بها
           appfoundation جدول سازه های طرح ها
           applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
           لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
           این جدول دارای ستون های ارتباطی زیر می باشد
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           clerk جدول کاربران
           operatorapprequest جدول پیشنهاد قیمت های طرح
           applicantmaster جدول مشخصات طرح
           BankCode کد رهگیری طرح
           ApplicantMasterID شناسه طرح
           state=1 انتخاب شدن پیشنهاد توسط کشاورز
           operatorcoID شناسه پیمانکار
           coef1 ضریب اول اجرای طرح
           coef2 ضریب دوم اجرای طرح
           coef3 ضریب سوم اجرای طرح
           coef4 ضریب چهارم اجرای طرح
           coef5 ضریب پنجم اجرای طرح
           selfnotcashhelpval خودیاری غیر نقدی
           selfcashhelpval خودیاری نقدی
           selfcashhelpdescription توضیحات خودیاری نقدی
        */ 
     
            
        

                
        $sql = "SELECT applicantmaster.*,applicantmaster.cityid,substring(applicantmaster.cityid,1,4) cityid14,ostan.id ostanid FROM applicantmaster  
        
        left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
        WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID'";
        
        //print $sql;
       // exit;
        $count = mysql_fetch_assoc(mysql_query($sql));
        $creditsourceid = $count['creditsourceID'];
        //if ($count['ApplicantMasterIDprop1']>0)
        //$inproposing=1;
        //else 
        $inproposing=0;
        
        //print $count['ApplicantMasterIDprop2'];
        //exit;
        $soo=$count["ostanid"];
        $criditType = $count['criditType'];
        $ApplicantName = $count['ApplicantName'];
        $DesignerCoIDnazer=$count['DesignerCoIDnazer'];
        $cityid14=$count['cityid14'];
        
        
        $cityid15=$count['cityid15'];
        $designerTitle = $count['designerTitle'];
        $issurat= $count['issurat'];
        $ApplicantMasterIDmaster=$count['ApplicantMasterIDmaster'];
        $opacc="";
        if ($count['operatorcoid']>0)
        {
            $CoTitle = $count['operatorcoTitle'];
            $opacc = "($CoTitle $count[operatorcoAccountNo] $count[operatorcoAccountBank])";
        }
        else $CoTitle = $count['DesignerCoTitle'];
        $operatorcoid=$count['operatorcoid'];
        
        $cityid15=$count['cityid15'];
        
		   
        $costpricelistmasterID=$count['costpricelistmasterID'];
        $DesignArea= $count['DesignArea'];
        $othercosts1=$count['othercosts1'];
        $othercosts2=$count['othercosts2'];
        $othercosts3=$count['othercosts3'];
        $othercosts4=$count['othercosts4'];
        $othercosts5=$count['othercosts5'];
		
          	    
		
		
        $Cost=$count['Cost'];
        //$designcost=$count['designcost'];
        $designcost=0;
        
        //if ($wincoef1>0) $coef1=round($wincoef1,2); else 
        $coef1=round($count['coef1'],2);
        //if ($wincoef2>0) $coef2=round($wincoef2,2); else 
        $coef2=round($count['coef2'],2);
         if ($wincoef3>0) 
        {    
            if (round($count['coef3'],3)<round($wincoef3,3))
                $coef3=round($count['coef3'],3);
            else    
                $coef3=round($wincoef3,3);   
        }
         else $coef3=round($count['coef3'],3);
        $coef4=round($count['coef4'],2);
        $appcoef5=$count['appcoef5'];
        $unpredictedcost=$count['unpredictedcost'];
        $DesignerCoID=$count['DesignerCoID'];
        $fb=$count['fb'];
        $pr=$count['pr'];
        
        
    $selfcashhelpdate=$count["selfcashhelpdate"];
    $selfcashhelpval=number_format($count["selfcashhelpval"]);
    $selfcashhelpdescription=$count["selfcashhelpdescription"];
    $letterno=$count["letterno"];
    if ($issurat==1)
    {
   	    $hatarray = explode('_',$count["letterno"]);
        $hatval=str_replace(',', '', $hatarray[0]);
   	    $hattitle = $hatarray[1];
    }
    $letterdate=$count["letterdate"];
    $sandoghcode=$count["sandoghcode"];
    $Freestate=$count["Freestate"];
    $operatorcoIDbandp=$count["operatorcoIDbandp"];
    if ($count["Datebandp"]>0)
    $Datebandp=gregorian_to_jalali($count["Datebandp"]);
    $isbandp=$count["isbandp"];
    $selfnotcashhelpval=number_format($count["selfnotcashhelpval"]);
    $BankCode=$count['BankCode'];
        //print $query1."-".$coef1."-".$coef2."-".$coef3;
     
     
     

        //exit;
    }    
    
    

                                    
       
?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	
<STYLE >
p {
    margin: 0;
    padding: 0;
}
 p.page {
    page-break-after: always;
   }
   

</STYLE>
	
 
	<!-- scripts -->


    <!-- /scripts -->
</head>
<body>

 
     
	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
            if ($_POST){
					if ($register){
						$Serial = "";
						$ProducersID = "";
                        //header("Location: summaryinvoicemaster.php");
                        exit(); 
                        
					}else{
						print '<label class="error">خطا در ثبت...</label>';
					}
				}
                ?>
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
            
            <form action="smalsummaryinvoice.php" method="post" onSubmit="return CheckForm()">
                    <tbody>
                
  <?php
  
      if ($DesignerCoID>0) 
echo "<a  target='_blank' href='../appinvestigation/applicant_form10.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).
                                    "'><img style = 'width: 5%;' 
                                    src='../img/mail_send.png' title=' فرم شماره 10 '></a>";
                                    
                $globalprint= "<table width=\"95%\" align=\"left\">";
                
  
    $globalprint.= "<div style = 'text-align:left;'>";
    
    $globalprint.= "
         
         
         <a  href='../appinvestigation/sendtoanjoman.php'><img class='no-print' style = 'width: 2%;' src='../img/Return.png' ></a> 
         
         <input class='no-print' name='applicantmasterdetailID' type='hidden' class='textbox' id='applicantmasterdetailID'
                     value='$ApplicantMasterdetailID'  />  
         
         <input class='no-print' name='operatorcoid' type='hidden' class='textbox' id='operatorcoid'
                     value='$operatorcoid'  />
                     
         <input class='no-print' name='RolesID' type='hidden' class='textbox' id='RolesID'  value='$login_RolesID'  /> 
         <input class='no-print' name='uid' type='hidden' class='textbox' id='uid'  value='$uid'  /> 
         <input class='no-print' name='sref' type='hidden' class='textbox' id='sref'  value='$_SERVER[HTTP_REFERER]'  /> 
         <input class='no-print' name='issurat' type='hidden' class='textbox' id='issurat'  value='$issurat'  /> 
         <input class='no-print' name='prjtypeid' type='hidden' class='textbox' id='prjtypeid'  value='$prjtypeid'  />
                </div>
                ";
     

            if ($issurat==1)
            {$titles="✔ صورت وضعیت "; $nazer="ناظر";}
    		
            else
            {$titles="✔ لیست لوازم ";$nazer="بازبین";}
            $printdate=compelete_date(gregorian_to_jalali(date('Y-m-d')));
            $globalprint.= "<tr >
				       
                            <td  align='center' class='f1_font'>$titles و هزینه های اجرایی طرح $ApplicantName </td></td>
                
					    </tr>
                         
                        ";
            
         if ($issurat==1)
            $titles="✔ صورت وضعیت ";
            else
            $titles=""; 
            
           

                        //print $issurat.'sa';
                        if (($operatorcoid>0)&& ($login_RolesID==10))
                        {
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (43,42)
                                order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($issurat==1)
                        {
                              switch ($login_RolesID) 
                              {
                                case 2: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID=41
                                order by _key   COLLATE utf8_persian_ci"; break; 
                                
                                case 13: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (44,45)
                                order by _key   COLLATE utf8_persian_ci"; break;
                                case 14: $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstatesID as _value,title as _key from applicantstates where applicantstatesID in (44,45)
                                order by _key   COLLATE utf8_persian_ci"; break;    
                              }
                        }
                        else if ($login_RolesID=='13')
                        {
                            if ($operatorcoid>0) 
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (15,28,9)
                                and appstatedone.ostan=substring('$login_CityId',1,2)
                                order by _key   COLLATE utf8_persian_ci";
                            else
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (27,32,28,30,47)
                                and appstatedone.ostan=substring('$login_CityId',1,2)
                                order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($login_RolesID=='17' && $prjtypeid==0)
                        {
                            if ($issurat==1)
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (24,30)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            
                            else if ($operatorcoid>0) 
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (24,45)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                            else
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID not in (30,45,44)
                                and appstatedone.ostan=substring('$login_CityId',1,2) and appstatedone.prjtypeid='$prjtypeid'
                                order by _key   COLLATE utf8_persian_ci";
                        }
                        else if ($login_RolesID=='18')
                        {
                            $query18="Select applicantstatesID from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID in (10,39)
                            and stateno=(Select max(stateno) stateno from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID in (10,39))";
                            $result18 = mysql_query($query18);
                            $row18 = mysql_fetch_assoc($result18);
                            if ($row18['applicantstatesID']=='10')  
                                      
                            $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID<>36
                                and appstatedone.ostan=substring('$login_CityId',1,2)
                                        order by _key   COLLATE utf8_persian_ci";
                            else $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and applicantstates.applicantstatesID<>12
                                and appstatedone.ostan=substring('$login_CityId',1,2)
                                        order by _key   COLLATE utf8_persian_ci";
                            //print $query;
                                            
                        }
                        else
                        $query="Select '0' As _value, ' ' As _key Union All
                                select  applicantstates.applicantstatesID as _value,applicantstates.title as _key from appstatedone
                                inner join applicantstates on applicantstates.applicantstatesID=appstatedone.applicantstatesID 
                                and appstatedone.RolesID='$login_RolesID' and appstatedone.ostan=substring('$login_CityId',1,2)
                                and appstatedone.prjtypeid='$prjtypeid'
                                        order by _key   COLLATE utf8_persian_ci";
                        
                        //print $query;
                        
                        if ($prjtypeid==1)
                        if ($login_RolesID=='17' || $login_RolesID=='31')
                            {
                                if ($login_RolesID=='17')
                                $sql="select round(sum(substring(wsval,length(substring_index(wsval,'_',9))+2,length(substring_index(wsval,'_',10))-length(substring_index(wsval,'_',9))-1))
                                *90000*1.09/1000000) done,ifnull(wsquota.val,0)+ifnull(wsquota.val2,0) wsquotaval from applicantmaster
                                    
                                    inner join applicantmasterdetail on 
                        case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
                        applicantmasterdetail.ApplicantMasterIDmaster 
                        else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
                        and ifnull(applicantmasterdetail.prjtypeid,0)=1 
                        and substring(applicantmaster.cityid ,1,4)=substring('$cityid14' ,1,4)
                                    and applicantmaster.applicantstatesID not in (23,51,25)
                                    inner join wsquota on wsquota.creditsourceID= applicantmaster.creditsourceID 
                                    and substring(wsquota.CityId,1,4)=substring(applicantmaster.CityId,1,4)  
                                    where applicantmaster.creditsourceID='$creditsourceid'
                                    ";
                                    else if ($login_RolesID=='31')
                                    $sql="select round(sum(substring(wsval,length(substring_index(wsval,'_',9))+2,length(substring_index(wsval,'_',10))-length(substring_index(wsval,'_',9))-1))
                                *90000*1.09/1000000) done,ifnull(wsquota.val,0)+ifnull(wsquota.val2,0) wsquotaval from applicantmaster
                                    
                                    inner join applicantmasterdetail on 
                        case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
                        applicantmasterdetail.ApplicantMasterIDmaster 
                        else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
                        and ifnull(applicantmasterdetail.prjtypeid,0)=1 
                        and substring(applicantmaster.cityid ,1,4)=substring('$cityid14' ,1,4)
                                    and applicantmaster.applicantstatesID not in (23,52,46,51,25)
                                    inner join wsquota on wsquota.creditsourceID= applicantmaster.creditsourceID 
                                    and substring(wsquota.CityId,1,4)=substring(applicantmaster.CityId,1,4) 
                                    where applicantmaster.creditsourceID='$creditsourceid' 
                                    ";
                                //print $sql;
                               try 
								  {		
									  	   $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                                $row = mysql_fetch_assoc($result);
                                if ($row['done']>$row['wsquotaval'])
                                {
                                    print "به دلیل اتمام سهمیه اعتبار شهرستانی، امکان ارجاع  فراهم نمی باشد";
                                    print "<br>سهمیه:".$row['wsquotaval']." میلیون ریال";
                                    print "<br>ارسال شده:".$row['done']." میلیون ریال";
                                    $query="";
                                    if ($login_RolesID=='31')
                                    $query="Select '0' As _value, ' ' As _key Union All
                                    Select '51' As _value, 'کارشناس استان به م ج شهرستان' As _key
                                        order by _key   COLLATE utf8_persian_ci";
                                    //exit;
                                    
                                }
                            }
        
                        $IDapplicantstatesID='';
                        if ($inproposing==0 && $issurat!=1)
                        {
                        $result = mysql_query($query);

	                    $IDapplicantstatesID[' ']=' ';
	                    while($row = mysql_fetch_assoc($result))
                        {
                            $IDapplicantstatesID[$row['_key']]=$row['_value'];
                            
                        }
                        }
    
      
                            
                            $globalprint.= "
                             <tr>
                             ";
                             //if (in_array($login_RolesID, $permitrolsidforviewdetail)) 
                             
                             $globalprint.="
                              </tr>
                               <tr>
                             <td  >&nbsp;</td>
                
                              <td  colspan='1' class='label'>تاریخ</td>
                              <td  colspan='1' class='label'>وضعیت</td>
                              <td  colspan='8' class='label'>توضیحات:</td>
                              </tr>
                              ";
                              if ($login_RolesID=='16' )
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID' and appchangestate.applicantstatesID in (17,12)
        order by appchangestate.stateno";   
        
        else  if ($login_RolesID=='7')
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID' and appchangestate.applicantstatesID in (36,18)
        order by appchangestate.stateno";        
        else 
        $queryallstates = "SELECT appchangestate.*,applicantstates.title applicantstatestitle FROM appchangestate 
        inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
        where appchangestate.ApplicantMasterID='$ApplicantMasterID'
        order by appchangestate.stateno";
        
                             
							     try 
								  {		
									  	    $resultallstates = mysql_query($queryallstates);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                        while($rowallstates = mysql_fetch_assoc($resultallstates))
                        { 
                            $SaveDate = $rowallstates['SaveDate'];
                            $laststateno=$rowallstates['stateno'];
                            $lastDescription=$rowallstates['Description'];
                            $applicantstatestitle=$rowallstates['applicantstatestitle'];  
                            if (strlen($lastDescription)>1)
                            $globalprint.= "
                              
                              <tr>
                               <td  >&nbsp;</td>
                
                                <td><textarea id='Descriptiondate' name='Descriptiondate' rows='3'  cols='7' readonly>".gregorian_to_jalali($SaveDate)."</textarea></td>
                              
                              <td colspan='1' ><textarea id='laststatetitle' name='laststatetitle'   cols='28' readonly rows='3'>$applicantstatestitle</textarea></td>
                            
                              
                              <td  colspan='8'>
                              <textarea id='lastDescription' colspan='1' name='lastDescription' rows='3' cols='120' readonly >".$lastDescription."</textarea></td>
                              </tr>
                              "; 
                              else
                            $globalprint.= "
                              
                              <tr> <td  >&nbsp;</td>
                
                              <td colspan='1' ><input   value='".gregorian_to_jalali($SaveDate)."' size=10 readonly ></input></td>
                             
                              <td colspan='1' ><input id='laststatetitle' name='laststatetitle'   value='$applicantstatestitle' size=31 readonly ></input></td>
                             
                              
                              <td  colspan='8'>
                              <input id='lastDescription' colspan='1' name='lastDescription' value='$lastDescription' size=120 readonly ></input></td>
                              </tr>
                              "; 
                                
                        }
                     
                    
                    
                   if (substr($login_CityId,0,2)=='19')
                   {
                        if ($login_RolesID==10 && $operatorcoid>0 )//نظارت بر اجرا
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='1' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
                            
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
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='1' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                         
                         if ($login_RolesID==9)//قرارداد مطالعات
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='4' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
                          
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
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='4' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID='$login_DesignerCoID' ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                         
                         if ($login_RolesID==11)//قراردادبازبینی
                        {
                            $query="SELECT designercocontract.designercocontractID FROM designercocontract
                                    inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID='$ApplicantMasterdetailID'
                                    and designercocontract.designercocontractid=applicantcontracts.designercocontractid
                                    and  designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID=
                                    case '$login_userid'
                                    when 88 then 111
                                    when 662 then 69
                                    when 36 then 57
                                    else 0 end
                                     
                                     
                                     
                                     
                                     
                                     ";
                                     //print $query;exit;
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
                            $selecteddesignercocontractID=$row['designercocontractID'];
                            $query="SELECT designercocontractID AS _value, 
                        substring(concat(designercocontract.No,'-',designercocontract.contractDate,'-',designercocontract.Title),1,300) AS _key
                                    FROM designercocontract
                                    where  designercocontract.contracttypeID='5' and 
                                    designercocontract.prjtypeid='$prjtypeid' and designercocontract.DesignerCoID=case '$login_userid'
                                    when 88 then 111
                                    when 662 then 69
                                    when 36 then 57
                                    else 0 end ";
            				 $ID = get_key_value_from_query_into_array($query);
                             
                             $globalprint.= "<tr>
                                    <td colspan='1'>قرارداد</td>
                                    
                                    ".select_option("designercocontractID",'',',',$ID,0,'','','3','rtl',0,'',$selecteddesignercocontractID,"",'635')."
                                    <input class='no-print' name='designercocontractIDold' type='hidden' class='textbox' id='designercocontractIDold'
                         value='$selecteddesignercocontractID'  />
                                    </tr>";
                         }
                   }
                   
                    
                      $globalprint.= "<tr>".select_option('ApplicantstatesID','وضعیت&nbspجدید',',',$IDapplicantstatesID,0,'','','2','rtl',0,'',0,'','215')."
                      
                      <td  >
                      <textarea id='Description' name='Description' rows='3' cols='120'></textarea></td>
                      
                      <td><input   name='submit' type='submit' class='button' id='submit' value='ارسال/ثبت' /></td>
                      <td ><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  value='$ApplicantMasterID'   /></td>
                      </tr>
                        </table>
                          "; 
             
         
         
         
         
         
         
         
                echo $globalprint;
?>

                    </tbody>
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

