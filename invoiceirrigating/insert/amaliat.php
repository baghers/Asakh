<?php
 include('../includes/connect.php');
 include('../includes/check_user.php');
 include('../includes/elements.php');
 $mojavez='';
 //نقش کاربر لاگین شده
 $login_RolesID=$_POST['login_RolesID'];
 //داشتن یا نداشتن مجوز مشاهده صفحات
 $login_Permission_granted=$_POST['login_Permission_granted'];
 //نقش هایی که امکان ورود به صفحه را دارند
 $permitrolsid = array("1","2","5","9","10","20");
 //نقش هایی که امکان ثبت و بروزرسانی اطلاعات را دارند
 $permitrolsidmodir = array("1","20");
 //درصورت نداشتن مجوز مشاهده صفحه به صفحه ورود منتقل می شود
if ($login_Permission_granted==0) header("Location: ../login.php");

 
if (isset($_POST['des_edit']) && (($login_DesignerCoID>0) || ($login_OperatorCoID>0) || ($login_FarmersID>0)  || in_array($login_RolesID, $permitrolsid)))//درصورتی که از صفحه ویرایش آمده باشیم
 {
		 
          $register = false;//انجام ثبت نام
          $operatorcoID=$_POST['operatorcoID'];//شناسه پیمانکار
          $designercoID=$_POST['designercoID'];//شناسه شرکت طراح
          $FarmersID=$_POST['FarmersID'];//شناسه شرکت بهره بردار
          $FName=$_POST['FName'];//نام
          $LName=$_POST['LName'];//نام خانوادگی
          $NationalCode=$_POST['NationalCode'];//شناسه  ملی
          $PermisionNo=$_POST['PermisionNo'];//شماره مجوز
          $PermisionDate=$_POST['PermisionDate'];//تاریخ مجوز
          $issuerID=$_POST['issuerID'];//شناسه مرجع صادر کننده مجوز برای کارشناس فنی فوق
          $BDate=$_POST['BDate'];//تاریخ مدرک کارشناسی
          $MDate=$_POST['MDate'];//تاریخ مدرک ارشد
          $PDate=$_POST['PDate'];//تاریخ مدرک دکتری
          $BLicenceNo=$_POST['BLicenceNo'];//شماره مدرک کارشناسی
          $MLicenceNo=$_POST['MLicenceNo'];//شماره مدرک ارشد
          $PLicenceNo=$_POST['PLicenceNo'];//شماره مدرک دکتری
          $BUniversity=$_POST['BUniversity'];//نام دانشگاه اخذ مدرک کارشناسی
          $MUniversity=$_POST['MUniversity'];//نام دانشگاه اخذ مدرک ارشد
          $PUniversity=$_POST['PUniversity'];//نام دانشگاه اخذ مدرک دکتری
          $Phone=$_POST['Phone'];//تلفن
          $Email=$_POST['Email'];//ایمیل
          $signatureright=$_POST['signatureright'];//حق امضاء
          $membersID=$_POST['membersID'];//شناسه عضو
		  $StartDate=$_POST['StartDate'];//تاریخ شروع مجوز
          $EndDate=$_POST['EndDate'];//تاریخ پایان مجوز
         $membersdateID=$_POST['membersdateID'];//شناسه جدول تغییر سمت
		  //نقش هایی که امکان بررسی و تایید مجوز کارشناسان فنی را دارند
          if(in_array($login_RolesID, $permitrolsidmodir))
		  {
		      //$mojavez تایید مجز کارشناس فنی
		  	if ($_POST['mojavez']=='on')  $mojavez=1;else $mojavez='';
		  }
		  else
		    $mojavez=$_POST['mojavez'];
		 //در پرس و جوی زیر می خواهیم بررسی کنیم که این عضو آیا در شرکت دیگر هم عضو می باشد یا خیر   
		 $querys="SELECT count(*) cnt FROM `designer` WHERE membersID='$membersID' and
         case '$designercoID' when '' then case '$operatorcoID' when '' then FarmersID='$FarmersID' else  operatorcoID='$operatorcoID' end
          else designercoID='$designercoID' end=1";  
        try 
        {		
            $results = mysql_query($querys); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        $rows = mysql_fetch_assoc($results);
        //$coworkingcnt تعداد شرکت هایی که عضو در آنها عضویت دارد
        $coworkingcnt=$rows['cnt'];
		
         
		if($_POST['compny']==1)//شرکت طراح می باشد
		{
		  $designercoID=$designercoID;//شناسه شرکت طراح
		  $operatorcoID=0;//شناسه شرکت پیمانکار
          $FarmersID=0;//شناسه شرکت بهره بردار
		}
		elseif($_POST['compny']==2)//شرکت پیمانکار می باشد
		{
		  $designercoID=0;//شناسه شرکت طراح
		  $operatorcoID=$operatorcoID;//شناسه شرکت پیمانکار
          $FarmersID=0;//شناسه شرکت بهره بردار
		}
        elseif($_POST['compny']==3)//شرکت بهره بردار می باشد
		{
		  $designercoID=0;//شناسه شرکت طراح
		  $operatorcoID=0;//شناسه شرکت پیمانکار
		  $FarmersID=$FarmersID;//شناسه شرکت بهره بردار
		}
    //نقش هایی که امکان ثبت و بروزرسانی اطلاعات را دارند
    if (in_array($login_RolesID, $permitrolsidmodir))
    {
        /*
        $query = "
		UPDATE members SET -- جدول اعضا
        designercoID='$designercoID', -- شناسه شرکت طراح
        operatorcoID='$operatorcoID', -- شناسه شرکت مجری
        FarmersID='$FarmersID', -- شناسه شرکت بهره بردار
		FName = '$FName',  -- نام
		LName = '$LName',  -- نام خانوادگی
		NationalCode = '$NationalCode', --  کدملی
	    BirthDate = '$_POST[BirthDate]', -- تاریخ تولد
		Position = '$_POST[Position]', -- سمت
		InsuranceCode = '$_POST[InsuranceCode]', -- ککد بیمه 
		InsuranceHistory = '$_POST[InsuranceHistory]', -- میزان سابقه بیمه
		Bstat = '$_POST[Bstat]', -- وضعیت مدرک کارشناسی
		Mstat = '$_POST[Mstat]', -- وضعیت مدرک ارشد
		Pstat = '$_POST[Pstat]', -- وضعیت مدرک دکتری
		Bbranch = '$_POST[Bbranch]', -- رشته کارشناسی
		Mbranch = '$_POST[Mbranch]', -- رشته ارشد
		Pbranch = '$_POST[Pbranch]', -- رشته دکتری
		BDate = '$BDate', --  تاریخ مدرک کارشناسی
		MDate = '$MDate', --  تاریخ مدرک ارشد
		PDate = '$PDate', --  تاریخ مدرک دکتری
		BLicenceNo = '$BLicenceNo', -- شماره مدرک کارشناسی 
		MLicenceNo = '$MLicenceNo',  -- شماره مدرک ارشد
		PLicenceNo = '$PLicenceNo', --  شماره مدرک دکتری
		BUniversity = '$BUniversity', -- دانشگاه کارشناسی 
		MUniversity = '$MUniversity', -- دانشگاه ارشد
		PUniversity = '$PUniversity', -- دانشگاه دکتری
		Phone = '$Phone', -- تلفن 
		Email = '$Email', -- ایمیل
		signatureright = '$signatureright', -- حق امضاء  
		mojavez = '$mojavez', -- تایید مجوز > 1 تایید 
		SaveTime = '" . date('Y-m-d H:i:s') . "', -- زمان ثبت 
		SaveDate = '" . date('Y-m-d') . "', --  تاریخ ثبت
		ClerkID = '" . $login_userid . "' -- کاربر ثبت کننده
		WHERE membersID = '$membersID' -- شناسه عضو ;";
        
        */
        
		$query = "
		UPDATE members SET
        designercoID='$designercoID',
        operatorcoID='$operatorcoID',
        FarmersID='$FarmersID',
		FName = '$FName', 
		LName = '$LName', 
		NationalCode = '$NationalCode', 
	    BirthDate = '$_POST[BirthDate]',
		Position = '$_POST[Position]',
		InsuranceCode = '$_POST[InsuranceCode]',
		InsuranceHistory = '$_POST[InsuranceHistory]',
		Bstat = '$_POST[Bstat]',
		Mstat = '$_POST[Mstat]',
		Pstat = '$_POST[Pstat]',
		Bbranch = '$_POST[Bbranch]',
		Mbranch = '$_POST[Mbranch]',
		Pbranch = '$_POST[Pbranch]',
		BDate = '$BDate', 
		MDate = '$MDate', 
		PDate = '$PDate', 
		BLicenceNo = '$BLicenceNo', 
		MLicenceNo = '$MLicenceNo', 
		PLicenceNo = '$PLicenceNo', 
		BUniversity = '$BUniversity', 
		MUniversity = '$MUniversity', 
		PUniversity = '$PUniversity', 
		Phone = '$Phone', 
		Email = '$Email', 
		signatureright = '$signatureright', 
		mojavez = '$mojavez', 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE membersID = '$membersID';";
		
		
        }
        else
        {
        $query = "
		UPDATE members SET
		FName = '$FName', 
		LName = '$LName', 
		NationalCode = '$NationalCode', 
	    BirthDate = '$_POST[BirthDate]',
		Position = '$_POST[Position]',
		InsuranceCode = '$_POST[InsuranceCode]',
		InsuranceHistory = '$_POST[InsuranceHistory]',
		Bstat = '$_POST[Bstat]',
		Mstat = '$_POST[Mstat]',
		Pstat = '$_POST[Pstat]',
		Bbranch = '$_POST[Bbranch]',
		Mbranch = '$_POST[Mbranch]',
		Pbranch = '$_POST[Pbranch]',
		BDate = '$BDate', 
		MDate = '$MDate', 
		PDate = '$PDate', 
		BLicenceNo = '$BLicenceNo', 
		MLicenceNo = '$MLicenceNo', 
		PLicenceNo = '$PLicenceNo', 
		BUniversity = '$BUniversity', 
		MUniversity = '$MUniversity', 
		PUniversity = '$PUniversity', 
		Phone = '$Phone', 
		Email = '$Email', 
		signatureright = '$signatureright', 
		mojavez = '$mojavez', 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE membersID = '$membersID';";
		
		}
		//پرس و جوی استخراج شرکت هایی که عضو مورد نظر در آنجا کار می نماید
        //یا تغییر سمت اتفاق افتاده است
	     $sql2="SELECT * from members left outer join membersdate on membersdate.membersdateID='$membersdateID'
							where members.membersID='$membersID'";
        
        try 
        {		
            $res = mysql_query($sql2); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
        	       	
        $resqu = mysql_fetch_assoc($res);
	  
	    
        //کلاس بارگذاری اسناد
		 require_once '../class/upload.class.php';	
        
          new Upload('file1', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_1_'.$_FILES['file1']['name']);//تصور کد ملی
		  new Upload('file2', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_2_'.$_FILES['file2']['name']);//تصویر سوابق بیمه
		  new Upload('file3', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_3_'.$_FILES['file3']['name']);//تصویر مجوز کارشناس فنی
          new Upload('file4', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_4_'.$_FILES['file4']['name']);//تصویر مدرک کارشناسی
		  new Upload('file5', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_5_'.$_FILES['file5']['name']);//تصویر مدرک ارشد
		  new Upload('file6', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_6_'.$_FILES['file6']['name']);//تصویر مدرک دکتری
		//درصورت تغییر سمت فعلی با سمت قبلی در جدول
        //membersdate
        //یک ردیف ثبت می شود
		if($_POST['Position']!=$resqu['Position'])
		{
		  /*
          membersdate جدول تغییر سمت یا شرکت
          membersID شناسه عضو
          DesignerCoID شناسه شرکت طراح
          operatorcoid شناسه شرکت پیمانکار
          FarmersID شناسه شرکت بهره بردار
          Position سمت
          StartDate شروع فعالیت
          EndDate پایان فعالیت
          SaveDate تاریخ ثبت
          SaveTime زمان ثبت
          ClerkID شناسه کاربر ثبت
          */
		  $sql2="INSERT INTO membersdate (membersID,DesignerCoID,operatorcoid,FarmersID,Position,StartDate,EndDate,SaveDate, SaveTime, ClerkID)
            values ('$membersID','$designercoID','$operatorcoID','$FarmersID','$_POST[Position]', '$_POST[StartDate]', '$_POST[EndDate]',
			'".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
            try 
            {		
                mysql_query($sql2);  
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            
		  
	    }
		//در صورت عدم تغییر سمت وضعیت فعلی بروز می شود
		else
		{
		  /*
          membersdate جدول تغییر سمت یا شرکت
          DesignerCoID شناسه شرکت طراح
          operatorcoid شناسه شرکت پیمانکار
          FarmersID شناسه شرکت بهره بردار
          Position سمت
          EndDate پایان فعالیت
          membersdateID شناسه جدول تغییر سمت یا شرکت
          */
				$sql2 = "
				UPDATE membersdate SET
				DesignerCoID = '$designercoID',
				operatorcoid = '$operatorcoID',
				FarmersID = '$FarmersID',
				Position = '$_POST[Position]',
				EndDate = '$_POST[EndDate]'
				WHERE membersdateID= '$membersdateID' ";
            try 
            {		
                mysql_query($sql2); 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }     
                
			 
	    }
     //پروس و جوی بروزرسانی ابتدای صفحه
     //به این دلیل اینجا اجرا شده که بررسی های لازم تا اینجا انجام شود و بعد بروز رسانی انجام شود   
     mysql_query($query);
    
    
    
    
    //در صورتی که عضو مورد نظر کارشناس فنی باشد این عضو باید در جدول کارشناسان فنی تایید شده نیز ثبت شود
    //designer  کارشناسان فنی تایید شده
    //membersID شناسه عضو
    //operatorcoID شناسه پیمانکار 
    //FarmersID شناسه شرکت بهره بردار
    //designercoID شناسه مشاور طراح
	 //در پرس و جوی زیر بررسی می شود که عضو مورد نظر در جدول کارشناسان فنی ثبت می باشد یا خیر
     $qde=mysql_query("SELECT * FROM designer WHERE membersID='$membersID'
     and case '$designercoID' when '' then case '$operatorcoID' when '' then FarmersID='$FarmersID' else  operatorcoID='$operatorcoID' end
          else designercoID='$designercoID' end=1  ");
     
          
     
	 $cntde = mysql_num_rows($qde);
	 //echo $mojavez;
//////////////////////////////////////////////////////////////////////////////////////////////////////
///mmoooodiir////////////////////////////////////////////////////////////////////////////////////////
 if(in_array($login_RolesID, $permitrolsidmodir))
 {
	if ($mojavez==1 /* کارشناس فنی توسط مدیر تایید شده */ 
    && $coworkingcnt==0 /* تعداد شرکت هایی که عضو در آنها عضویت دارد */ 
    && $cntde==0 /* تعداد شرکت هایی که در آنها کارشناس فنی می باشد */) 
    {
        /*
        
        designer -- جدول کارشناسان فنی
        designercoID -- شناسه شرکت طراح
        operatorcoID -- شناسه شرکت مجری
		FarmersID -- شناسه شرکت بهره بردار
        FName -- نام
		LName -- نام خانوادگی
		NationalCode --  کدملی
        PermisionNo -- شماره مجوز
		PermisionDate -- تاریخ مجوز
		issuerID -- مرجع صادرکننده مجوز  
        BDate = '$BDate', --  تاریخ مدرک کارشناسی
		MDate = '$MDate', --  تاریخ مدرک ارشد
		PDate = '$PDate', --  تاریخ مدرک دکتری
		BLicenceNo = '$BLicenceNo', -- شماره مدرک کارشناسی 
		MLicenceNo = '$MLicenceNo',  -- شماره مدرک ارشد
		PLicenceNo = '$PLicenceNo', --  شماره مدرک دکتری
		BUniversity = '$BUniversity', -- دانشگاه کارشناسی 
		MUniversity = '$MUniversity', -- دانشگاه ارشد
		PUniversity = '$PUniversity', -- دانشگاه دکتری
		Phone = '$Phone', -- تلفن 
		Email = '$Email', -- ایمیل 
        membersID -- شناسه عضو
        */
        $sqls="INSERT INTO designer (operatorcoid,DesignerCoID,FarmersID, FName, LName, NationalCode, PermisionNo, PermisionDate, issuerID,
			BDate, MDate, PDate, BLicenceNo, MLicenceNo, PLicenceNo, BUniversity, MUniversity, PUniversity, Wbsite, Phone, Email
            , membersID, SaveDate, SaveTime, ClerkID)
            values ('$operatorcoID','$designercoID','$FarmersID', '$FName', '$LName', '$NationalCode', '$PermisionNo', '$PermisionDate', '$issuerID',
			'$BDate', '$MDate', '$PDate', '$BLicenceNo', '$MLicenceNo',
			'$PLicenceNo', '$BUniversity', '$MUniversity', '$PUniversity', '$Wbsite', '$Phone', '$Email','$membersID','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
            try 
            {		
                mysql_query($sqls); 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            
             
    }
	elseif ($mojavez==1) //درصورتی که کارشناس طراح قبلا ثبت شده است
	{
		$sqls= "
		UPDATE designer SET
        designercoID='$designercoID',
        operatorcoID='$operatorcoID',
        FarmersID='$FarmersID',
		FName = '$FName', 
		LName = '$LName', 
		NationalCode = '$NationalCode', 
		BDate = '$BDate', 
		MDate = '$MDate', 
		PDate = '$PDate', 
		BLicenceNo = '$BLicenceNo', 
		MLicenceNo = '$MLicenceNo', 
		PLicenceNo = '$PLicenceNo', 
		BUniversity = '$BUniversity', 
		MUniversity = '$MUniversity', 
		PUniversity = '$PUniversity', 
		Phone = '$Phone', 
		Email = '$Email', 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "',
		PermisionNo = '$PermisionNo',
		PermisionDate = '$PermisionDate', 
		issuerID = '$issuerID' 
		WHERE membersID = '$membersID';
		";
        $result = mysql_query($sqls);
	}
	elseif ($mojavez=='')//درصورتی که تایید کارشناس فنی  حذف شود کارشناس فنی حذف می شود
	{
	    $sqls = "
		delete from designer 
		WHERE membersID = '$membersID';";
        $result = mysql_query($sqls);
	}
 }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////
 else// در صورتی که کاربرمدیر نباشد امکان بروزرسانی محدود وجود دارد
 {
 	$query1 = "
		UPDATE designer SET
		FName = '$FName', 
		LName = '$LName', 
		NationalCode = '$NationalCode', 
		BDate = '$BDate', 
		MDate = '$MDate', 
		PDate = '$PDate', 
		BLicenceNo = '$BLicenceNo', 
		MLicenceNo = '$MLicenceNo', 
		PLicenceNo = '$PLicenceNo', 
		BUniversity = '$BUniversity', 
		MUniversity = '$MUniversity', 
		PUniversity = '$PUniversity', 
		Phone = '$Phone', 
		Email = '$Email', 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE membersID = '$membersID';";
		//echo $query1.' yy';
		 mysql_query($query1)or die(mysql_error());
 }
	  //echo $sqls;
	  //exit();
      echo"<script>alert('عملیات بروزرسانی با موفقیت انجام شد');</script>";
      echo"<script>window.location='designer_list.php'</script>";  
      
 	
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//در صورتی که از صفحه افزودن عضو آمده باشیم
   if (in_array($login_RolesID, $permitrolsid) && (isset($_POST['des_add'])) && (($_POST['designercoID']>0) || ($_POST['FarmersID']>0) || ($_POST['operatorcoID']>0) ) )
  //  if ((isset($_POST['des_add'])) && (($_POST['designercoID']>0) || ($_POST['operatorcoID']>0)) )
  {
    	
        /*
        
        FName -- نام
		LName -- نام خانوادگی
		NationalCode --  کدملی
        PermisionNo -- شماره مجوز
		PermisionDate -- تاریخ مجوز
		issuerID -- مرجع صادرکننده مجوز  
        BDate = '$BDate', --  تاریخ مدرک کارشناسی
		MDate = '$MDate', --  تاریخ مدرک ارشد
		PDate = '$PDate', --  تاریخ مدرک دکتری
		BLicenceNo = '$BLicenceNo', -- شماره مدرک کارشناسی 
		MLicenceNo = '$MLicenceNo',  -- شماره مدرک ارشد
		PLicenceNo = '$PLicenceNo', --  شماره مدرک دکتری
		BUniversity = '$BUniversity', -- دانشگاه کارشناسی 
		MUniversity = '$MUniversity', -- دانشگاه ارشد
		PUniversity = '$PUniversity', -- دانشگاه دکتری
		Phone = '$Phone', -- تلفن 
		Email = '$Email', -- ایمیل 
        signatureright -- حق امضاء 
        designercoID -- شناسه شرکت طراح
        operatorcoID -- شناسه شرکت مجری
		FarmersID -- شناسه شرکت بهره بردار
        */
        
	      $FName=$_POST['FName'];
          $LName=$_POST['LName'];
          $NationalCode=$_POST['NationalCode'];
          $PermisionNo=$_POST['PermisionNo'];
          $PermisionDate=$_POST['PermisionDate'];
          $issuerID=$_POST['issuerID'];
          $BDate=$_POST['BDate'];
          $MDate=$_POST['MDate'];
          $PDate=$_POST['PDate'];
          $BLicenceNo=$_POST['BLicenceNo'];
          $MLicenceNo=$_POST['MLicenceNo'];
          $PLicenceNo=$_POST['PLicenceNo'];
          $BUniversity=$_POST['BUniversity'];
          $MUniversity=$_POST['MUniversity'];
          $PUniversity=$_POST['PUniversity'];
          $Phone=$_POST['Phone'];
          $Email=$_POST['Email'];
          $signatureright=$_POST['signatureright'];
          $DesignerCoID=$_POST['designercoID'];
          $OperatorCoID=$_POST['operatorcoID'];
          $FarmersID=$_POST['FarmersID'];
          if ($_POST['mojavez']=='on')  $mojavez=1;else $mojavez='';
//echo $mojavez.'yy';
  		  
          //پرس و جوی بررسی تعداد شرکت های پیمانکاری که این عضو در آنها مشغول به فعالیت می باشد
		  try 
            {		
                $qna=mysql_query("SELECT count(*) cnt FROM `members` WHERE NationalCode='$NationalCode' and operatorcoid='$_POST[operatorcoID]'");
                $rna = mysql_fetch_assoc($qna);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
           
          //پرس و جوی بررسی تعداد شرکت های مهندسین مشاور که این عضو در آنها مشغول به فعالیت می باشد
		  try 
            {		
                $qna1=mysql_query("SELECT count(*) cnt1 FROM `members` WHERE NationalCode='$NationalCode' and DesignerCoID='$_POST[designercoID]'");
                $rna1 = mysql_fetch_assoc($qna1);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
           
         
		$msg='';
		if(($rna['cnt']>0) || ($rna1['cnt1']>0)) $msg=1;//درصورتی که در یکی از شرکت های مشاور یا پیمانکار عضو باشد پیغام ثبت نام قبلا می دهد	
	if($msg==1)
			
		{ 
		  echo"<script>alert('كاربر با اين مشخصات قبلا ثبت شده است')</script>";
          echo"<script>window.location='designer_list.php'</script>";
		 
	    }
		else //در صورت عدم وجود مشکل ثبت می شود
		{
		 $msg='';
		  $sql1="INSERT INTO members (OperatorCoID,DesignerCoID,FarmersID, FName, LName, NationalCode,
			BirthDate,Position,InsuranceCode,InsuranceHistory,Bstat,Mstat,Pstat,Bbranch,Mbranch,Pbranch,
			BDate, MDate, PDate, BLicenceNo, MLicenceNo, PLicenceNo, BUniversity, MUniversity, PUniversity, Wbsite, Phone, Email,signatureright, mojavez,  SaveTime,SaveDate, ClerkID)
            values ('$OperatorCoID','$DesignerCoID','$FarmersID', '$FName', '$LName', '$NationalCode',
			'$_POST[BirthDate]','$_POST[Position]','$_POST[InsuranceCode]','$_POST[InsuranceHistory]','$_POST[Bstat]','$_POST[Mstat]',
			'$_POST[Pstat]','$_POST[Bbranch]','$_POST[Mbranch]','$_POST[Pbranch]','$BDate', '$MDate', '$PDate', '$BLicenceNo', '$MLicenceNo',
			'$PLicenceNo', '$BUniversity', '$MUniversity', '$PUniversity', '$Wbsite', '$Phone', '$Email', '$signatureright', '$mojavez','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
            
            
            try 
            {		
                mysql_query($sql1);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
             

            //استخراج آخرین شناسه درج شده
            $qmax = mysql_query("SELECT max(membersID) membersID FROM members ");
            $rmax = mysql_fetch_assoc($qmax);
            $membersID=$rmax['membersID'];
            //print $membersID;exit;

	  
		
			
			//درج سمت و شرکت عضو مورد نظر
			 $sql2="INSERT INTO membersdate (membersID,DesignerCoID,operatorcoid,FarmersID,Position,StartDate, SaveTime,SaveDate, ClerkID)
            values ('$membersID','$DesignerCoID','$OperatorCoID','$FarmersID','$_POST[Position]', '$_POST[StartDate]','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
			mysql_query($sql2); 
			
            // بارگذاری اسناد
		 require_once '../class/upload.class.php';	

          new Upload('file1', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_1_'.$_FILES['file1']['name']);//تصور کد ملی
		  new Upload('file2', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_2_'.$_FILES['file2']['name']);//تصویر سوابق بیمه
		  new Upload('file3', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_3_'.$_FILES['file3']['name']);//تصویر مجوز کارشناس فنی
          new Upload('file4', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_4_'.$_FILES['file4']['name']);//تصویر مدرک کارشناسی
		  new Upload('file5', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_5_'.$_FILES['file5']['name']);//تصویر مدرک ارشد
		  new Upload('file6', 'image/jpeg','0.2', '../../upfolder/designer/',$membersID.'_6_'.$_FILES['file6']['name']);//تصویر مدرک دکتری
            		 
	
		if($mojavez==1)//در صورتی که کارشناس طراح مورد تایید است در جدول کارشناسان فنی درج می شود
		{  
          $sql="INSERT INTO designer (OperatorCoID,DesignerCoID,FarmersID, FName, LName, NationalCode, PermisionNo, PermisionDate, issuerID,
			BDate, MDate, PDate, BLicenceNo, MLicenceNo, PLicenceNo, BUniversity, MUniversity, PUniversity, Wbsite, Phone, Email,signatureright, membersID,  SaveTime,SaveDate, ClerkID)
            values ('$OperatorCoID','$DesignerCoID','$FarmersID', '$FName', '$LName', '$NationalCode', '$PermisionNo', '$PermisionDate', '$issuerID',
			'$BDate', '$MDate', '$PDate', '$BLicenceNo', '$MLicenceNo',
			'$PLicenceNo', '$BUniversity', '$MUniversity', '$PUniversity', '$Wbsite', '$Phone', '$Email', '$signatureright','$membersID','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
           // print $sql;
           // exit;
            mysql_query($sql); 
		}
        echo"<script>alert('عملیات ثبت با موفقیت انجام شد');</script>";
        //لینک بازگشت به صفحه قبلی فراخواننده
	echo"<script>window.location='designer_list.php'</script>";
	}
			
		  
    }
?>
