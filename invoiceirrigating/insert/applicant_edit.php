<?php 

/*

insert/applicant_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
appinvestigation/applicant_manageredit.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php include('../includes/functiong.php'); ?>
<?php
//if (!($login_userid>0)) header("Location: ../login.php");
//if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    $refpage=$_SERVER['HTTP_REFERER'];
	$id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    //پرس و جوی استخراج آخرین سریال ثبت شده برای هر کاربر که از طریق شروط فوق محدود شده است
    /*
	applicantmaster.Code سریال طرح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
    لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
    این جدول دارای ستون های ارتباطی زیر می باشد
    ApplicantMasterID شناسه طرح مطالعاتی
    ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
    prjtype جدول نوع پروژه ها
    
    */
	$query = "SELECT applicantmaster.*,applicantmasterdetail.prjtypeid,
	concat(substring(applicantmaster.cityid,1,2),'00000') ostanid,
	concat(substring(applicantmaster.cityid,1,4),'000') shahrid,applicantmaster.cityid bakhshid FROM applicantmaster 
	left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
	applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
	applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
	WHERE applicantmaster.ApplicantMasterID = " . $id . ";";
	//print $query;
	
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
    
    $prjtypeid=$resquery['prjtypeid'];//نوع پروژه
    $XUTM1=$resquery['XUTM1'];
    $YUTM1=$resquery['YUTM1'];
    $linearray = explode('_',$resquery['StationNumber']);//شماره ایستگاه
    $YUTM2=$linearray[0];
    $StationNumber=$linearray[1];    
    
    $SurveyArea=$resquery['SurveyArea'];//مساحت نقشه برداری
    $surveyDate=$resquery['surveyDate'];//تاریخ نقشه برداری
    $numfield=$resquery['numfield'];//شماره پرونده
    //ترکیب نام روستا محل صدور شناسنامه نام پدر تاریخ تولد و ش شناسنامه
    $linearray = explode('_',$resquery['CountyName']);
    $CountyName=$linearray[0];
    $registerplace=$linearray[1];
    $fathername=$linearray[2];
    $birthdate=$linearray[3];
    $shenasnamecode=$linearray[4];
    $apps=$linearray[5];
    $cappacityless=$linearray[6];
  // print_r($linearray);
    
	$mobile= $resquery['mobile'];
	$melicode= $resquery['melicode'];
  	$Code = $resquery["Code"];
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    $linearray = explode('-',$resquery["BankCode"]);//کد رهگیری
    $BankCode1=$linearray[0];
    $BankCode2=$linearray[1];
    $BankCode3=$linearray[2];

	$ApplicantName = $resquery["ApplicantName"];//عنوان پروژه
	$ApplicantFName = $resquery["ApplicantFName"];//نام متقاضی
	$SelectedMonthID = $resquery["MonthID"];//ماه  
    $CostPriceListMasterID=$resquery['CostPriceListMasterID'];//فهرست بها
	$creditsourceID=$resquery["creditsourceID"];//منبع تامین اعتبار
	$applicantstatesID=$resquery["applicantstatesID"];//وضعیت
	$DesignArea = $resquery["DesignArea"];//مساحت
	$DesignAreamax = $resquery["DesignAreamax"];//خداکثر مساحت
	$Debi = $resquery["Debi"];//دبی
	$DesignSystemGroupsID = $resquery["DesignSystemGroupsID"];//سیستم آبیاری
	$TransportCostTableMasterID = $resquery["TransportCostTableMasterID"];//شناسه جدول هزینه حمل طرح
	$RainDesignCostTableMasterID = $resquery["RainDesignCostTableMasterID"];//شناسه جدول هزینه های طراحی طرح های بارانی
	$DropDesignCostTableMasterID = $resquery["DropDesignCostTableMasterID"];//شناسه جدول هزینه های طراحی طرح های قطره ای
    $soo=$resquery["ostanid"];//استان
    $sos=$resquery["shahrid"];//شهر
    $sob=$resquery["bakhshid"];//بخش
    $DesignerID=$resquery["DesignerID"];//طراح
    $DesignerCoID=$resquery["DesignerCoID"];//شرکت طراح
    $operatorcoid=$resquery["operatorcoid"];//مجری
    $DesignerCoIDnazer=$resquery["DesignerCoIDnazer"];//ناظر
	$proposestate=$resquery["proposestate"];//وضعیت پیشنهاد قیمت اجرا
	$proposestatep=$resquery["proposestatep"];//وضعیت پیشنهاد قیمت لوله
	$Datebandp=$resquery["Datebandp"];//تاریخ ترک تشریفات
	
    $private= $resquery['private'];//شخصی بودن طرح
    if ($private>0) $private="checked";
    //در صورتی که طرح تجمیع باشد مقدار یک می گیرد
    $criditType= $resquery['criditType'];
    if ($criditType>0) $criditType="checked";
    	
    $ApplicantMasterID=$id;  
}

$register = false;
if ($_POST)
{
		if ($_FILES["file1"]["error"] > 0) //بارگذاری نقشه 
		{
			echo "Error: " . $_FILES["file1"]["error"] . "<br>";
		} 
		else 
		{
			
			$ext = end((explode(".", $_FILES["file1"]["name"])));
			if ($ext=='zip')
			{
				foreach (glob("../../upfolder/" . $_POST["ApplicantMasterID"].'_1*') as $filename) 
				{
					unlink($filename);
				}
				move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/" . $_POST["ApplicantMasterID"].'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
			}
		}

		if ($_FILES["file2"]["error"] > 0) //بارگذاری دفترچه 
		{
			echo "Error: " . $_FILES["file2"]["error"] . "<br>";
		} 
		else 
		{
			$ext = end((explode(".", $_FILES["file2"]["name"])));
			
			if ($ext=='zip')
			{   
				foreach (glob("../../upfolder/" . $_POST["ApplicantMasterID"].'_2*') as $filename) 
				{
					unlink($filename);
				}
				move_uploaded_file($_FILES["file2"]["tmp_name"],"../../upfolder/" . $_POST["ApplicantMasterID"].'_2_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
			}
		}
    
		if ($_FILES["file3"]["error"] > 0) //بارگذاری دفترچه محاسبات
		{
			echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		} 
		else 
		{
			$ext = end((explode(".", $_FILES["file3"]["name"])));
			if ($ext=='zip')
			{
				foreach (glob("../../upfolder/" . $_POST["ApplicantMasterID"].'_3*') as $filename) 
				{
					unlink($filename);
				}
				move_uploaded_file($_FILES["file3"]["tmp_name"],"../../upfolder/" . $_POST["ApplicantMasterID"].'_3_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
			}
		}
		
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 if ($_FILES["file4"]["error"] > 0)//فایل نامه منابع طبيعي
		 { 
			echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		 }
		 else 
		 {
				if (($_FILES["file4"]["size"] / 1024)>200)
				{
					print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
					exit;
				}
				
				$ext = end((explode(".", $_FILES["file4"]["name"])));
				if ($ext=='jpg')
				{
					foreach (glob("../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_4*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["file4"]["tmp_name"],"../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_4_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
				}
		  }
  
		 if ($_FILES["file5"]["error"] > 0)//بارگذاری فايل مالكيت زمين
		 { 
			echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		 }
		 else 
		 {
				if (($_FILES["file5"]["size"] / 1024)>200)
				{
					print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
					exit;
				}
				$ext = end((explode(".", $_FILES["file5"]["name"])));
				if ($ext=='jpg')
				{
					foreach (glob("../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_5*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["file5"]["tmp_name"],"../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_5_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
				}
		  }
  
		 if ($_FILES["file6"]["error"] > 0)//بارگذاری فايل شناسنامه
		 { 
			echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		 }
		 else 
		 {
				if (($_FILES["file6"]["size"] / 1024)>200)
				{
					print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
					exit;
				}
				$ext = end((explode(".", $_FILES["file6"]["name"])));
				if ($ext=='jpg')
				{
					foreach (glob("../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_6*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["file6"]["tmp_name"],"../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_6_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
				}
		  }
		  
		  if ($_FILES["file7"]["error"] > 0)//بارگذاری فايل كارت ملي
		 { 
			echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		 }
		 else 
		 {
				if (($_FILES["file7"]["size"] / 1024)>200)
				{
					print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
					exit;
				}
				$ext = end((explode(".", $_FILES["file7"]["name"])));
				if ($ext=='jpg')
				{
					foreach (glob("../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_7*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["file7"]["tmp_name"],"../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_7_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
				}
		  }    
   
		  if ($_FILES["file8"]["error"] > 0)//بارگذاری اسکن نامه برنده پیشنهاد
		 { 
			//echo "Error: " . $_FILES["file3"]["error"] . "<br>";
		 }
		 else 
		 {
				if (($_FILES["file8"]["size"] / 1024)>200)
				{
					print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
					exit;
				}
				$ext = end((explode(".", $_FILES["file8"]["name"])));
				if ($ext=='jpg')
				{
					foreach (glob("../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_8*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["file8"]["tmp_name"],"../../upfolder/madarek/" . $_POST["ApplicantMasterID"].'_8_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
				}
		  }
    
		if (!($_FILES["filem"]["error"] > 0))//بارگذاری اسکن مچوزدار 
			{   
					if (($_FILES["filem"]["size"] / 1024)>200)
					{
						print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
						exit;
					}
					$ext = end((explode(".", $_FILES["filem"]["name"])));
					foreach (glob("../../upfolder/proposm/" .$_POST["ApplicantMasterID"].'*') as $filename) 
					{
						unlink($filename);
					}
					move_uploaded_file($_FILES["filem"]["tmp_name"],"../../upfolder/proposm/" .$_POST["ApplicantMasterID"].'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
			//print $_FILES["filem"]["tmp_name"];exit;
			}
		
			$melicode= $_POST['melicode'];
		if (!checkMelliCode($melicode))   
		{
			$errprint.= "کد ملی وارد شده نا معتبر می باشد";
			//exit;
		} 
    $refpage=$_POST['refpage'];//آدرس صفحه ارجاع شده
    $DesignerCoID=$_POST["DesignerCoID"];//شرکت طراح
    $operatorcoid=$_POST["operatorcoid"];//شرکت مجری
        if (!($_POST['sob']>0))
         {
            $errprint.= "</br> شهرستان محل اجرای طرح را انتخاب نمایید";
            //exit;
        }  
   
		if ($login_RolesID!=24)//مدیریت پرونده ها
		{
			$BankCode=trim("$_POST[BankCode1]-$_POST[BankCode2]-$_POST[BankCode3]");
			if ($login_RolesID!=17 && $login_RolesID!=26)//ناظر مقیم
			if ($operatorcoid>0 || $DesignerCoID>0)
			{
			     /*
                applicantmaster جدول مشخصات طرح
                ApplicantMasterID شناسه طرح مطالعاتی
                BankCode کد رهگیری
                OperatorCoID مجری
                DesignerCoID طراح
                */
    
				if ($operatorcoid>0)
				$query = "SELECT count(*) cnt 
						 FROM applicantmaster 
						 where ApplicantMasterID<>'$_POST[ApplicantMasterID]' and ifnull(OperatorCoID,0)='$operatorcoid'  
						 and BankCode='$BankCode' and ifnull(BankCode,0)>0 ";
				else if ($DesignerCoID>0)
				$query = "SELECT count(*) cnt 
					 FROM applicantmaster 
					 where ApplicantMasterID<>'$_POST[ApplicantMasterID]' and ifnull(DesignerCoID,0)>0  
					 and BankCode='$BankCode' and ifnull(BankCode,0)>0 ";
				
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
				if ($row['cnt']>0)
				{
					$errprint.= "</br> کد رهگیری وارد شده به طرح دیگری اختصاص داده شده است";
					//exit;
				}
				
				
			}
			//TransportCostTableMaster جدول هزینه حمل طرح
			$query = "SELECT count(*) cnt FROM `TransportCostTableMaster`
			where TransportCostTableMasterID='$_POST[TransportCostTableMasterID]' and CostPriceListMasterID<>'$_POST[CostPriceListMasterID]'";
			
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
			if ($row['cnt']>0)
			{
				$errprint.= "</br> جدول هزینه های حمل را انتخاب نمایید.";
				//exit;
			}
			
		}
    
    $StationNumber=$_POST['YUTM2']."_".$_POST['StationNumber'];
    $XUTM1=$_POST['XUTM1'];
    $YUTM1=$_POST['YUTM1'];
	$DesignAreamax = $_POST["DesignAreamax"];
	$SelectedMonthID = $_POST["MonthID"]; 
    $SurveyArea=$_POST['SurveyArea'];//مساحت نقشه برداری شده
    $surveyDate=compelete_date($_POST['surveyDate']);//تاریخ نقشه برداری
    
    $numfield=$_POST['numfield'];//شماره پرونده
    $Code=$_POST['Code'];//سریال طرح
	$DesignArea=$_POST['DesignArea'];//مساحت قطعی طرح
	$Debi=$_POST['Debi'];//دبی طرح
	$ApplicantName=$_POST['ApplicantName'];//نام خانوادگی /مدیر عامل
	$ApplicantFName=$_POST['ApplicantFName'];// نام/ عنوان شرکت
	$DesignSystemGroupsID=$_POST['DesignSystemGroupsID'];//سیستم آبیاری طرح
	$CostPriceListMasterID=$_POST['CostPriceListMasterID'];//شناسه فهرست بهای آبیاری تحت فشار طرح
	$TransportCostTableMasterID=$_POST['TransportCostTableMasterID'];//شناسه جدول هزینه حمل طرح
	$RainDesignCostTableMasterID=$_POST['RainDesignCostTableMasterID'];//شناسه جدول هزینه های طراحی طرح های بارانی
	$DropDesignCostTableMasterID=$_POST['DropDesignCostTableMasterID'];//شناسه جدول هزینه های طراحی طرح های قطره ای
	$CityId=$_POST['sob'];//شناسه شهرستان طرح
	
    $_POST['private'] = $_POST['private'];//شخصی بودن طرح
    $private= $_POST['private'];
    
    $ApplicantMasterID=$_POST["ApplicantMasterID"]; // شناسه طرح
	
    if ($login_RolesID==17 || $login_RolesID==26)
    $private=0;

    //در صورتی که طرح تجمیع باشد مقدار یک می گیرد
    $_POST['criditType']=$_POST['criditType'];
    if ($_POST['criditType']=='on')
	$criditType= 1;
    else
	$criditType= $_POST['criditType'];
    
    $countrysql="";
    if ($_POST["criditType"]!="")
            $countrysql.="criditType = '$_POST[criditType]',";
        else  
            $countrysql.="criditType = '0',";
            
    //print $_POST["creditsourceID"];exit;
    
	if ($_POST["creditsourceID"]>0) $countrysql.="creditsourceID = '$_POST[creditsourceID]',";
	if ($_POST["applicantstatesID"]>0) $countrysql.="applicantstatesID = '$_POST[applicantstatesID]',";

	
	if ($_POST["proposestate"]!="")	$countrysql.="proposestate = '$_POST[proposestate]',";
	if ($_POST["proposestatep"]!="") $countrysql.="proposestatep = '$_POST[proposestatep]',";
	if ($_POST["Datebandp"]!="") $countrysql.="Datebandp = '$_POST[Datebandp]',";

	
	$DesignerCoIDnazer= $_POST['DesignerCoIDnazer'];
    if ($DesignerCoIDnazer>0)
        $strDesignerCoIDnazer="DesignerCoIDnazer='$DesignerCoIDnazer',";
    
    $DesignerID=$_POST['DesignerID'];
    if ($DesignerID>0)
        $strDesignerID="DesignerID='$DesignerID',";
    $DesignerCoID=$_POST['DesignerCoID'];
    if ($DesignerCoID>0)
        $strDesignerCoID="DesignerCoID='$DesignerCoID',";
        
    $apps=0;
    $cappacityless=0;
	
	if ($login_RolesID==1)
	{
	   //$_POST["apps"]=$_POST["apps"];
	   if ($_POST["apps"]=='on') $apps=1;
	   if ($_POST["cappacityless"]=='on') $cappacityless=1;
	} 
    
    //ترکیب نام روستا محل صدور شناسنامه نام پدر تاریخ تولد و ش شناسنامه                       
	$CountyName= "$_POST[CountyName]_$_POST[registerplace]_$_POST[fathername]_$_POST[birthdate]_$_POST[shenasnamecode]_$apps"."_"."$cappacityless";
    $mobile= $_POST['mobile'];
    
    if ($operatorcoid>0)    
      $privateupdate="";
      else 
      $privateupdate="private = '$private',";
    
    if ($numfield!='' && $login_RolesID==24)
        $numfieldstr="numfield='$numfield',";
	else
        $numfieldstr;
	
    if ($login_RolesID==1)
    $countrysql.="CountyName='$CountyName',";
    	
		if ($errprint) print $errprint;
		
	if ($ApplicantName != ""  && $Code != "" && $login_userid>0)
    {
        /*
        applicantmaster جدول مشخصات طرح
        Debi دبی طرح
        DesignArea مساحت طرح
        Code سریال طرح
        BankCode کد رهگیری طرح
        ApplicantName عنوان طرح
        ApplicantFName عنوان اول طرح
        SaveTime زمان ثبت طرح
        SaveDate تاریخ ثبت طرح
        ClerkID کاربر ثبت
        CityId شناسه شهر طرح
        CountyName روستای طرح
        private شخصی بودن طرح
        numfield شماره پرونده طرح
        criditType تجمیع بودن یا نبودن طرح
        ClerkIDsurveyor شناسه کاربر نقشه بردار
        YearID سال طرح
        mobile تلفن همراه
        melicode کد/شناسه ملی
        SurveyArea مساحت نقشه برداری شده
        surveyDate تاریخ نقشه برداری
        coef5 ضریب منطقه ای طرح
        DesignerCoIDnazer شناسه مشاور ناظر طرح
        operatorcoid شناسه پیمانکار
        DesignerCoID شناسه مشاور طراح
        CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
        DesignSystemGroupsID نوع سیستم آبیاری
        TransportCostTableMasterID شناسه جدول هزینه حمل طرح
        RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
        DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
        DesignerID شناسه طراح طرح
        StationNumber تعداد ایستگاه های طرح
        XUTM1 یو تی ام ایکس
        YUTM1 یو تی ام وای
        SoilLimitation محدودیت بافت خاک دارد یا خیر
        creditsourceID منبع تامین اعتبار طرح
        */
		if ($login_RolesID==24 || $login_RolesID==19)
			$query = "
			UPDATE applicantmaster SET
			Code = '" . $Code . "',
			ApplicantName = '" . $ApplicantName . "', 
			mobile= '" . $mobile . "', 
			melicode= '" . $melicode . "', 
			ApplicantFName = '" . $ApplicantFName . "', 
			DesignArea = '" . $DesignArea . "', 
			DesignAreamax = '" . $DesignAreamax . "', 
			Debi = '" . $Debi . "', 
			SurveyArea='$SurveyArea',
			surveyDate='$surveyDate',
			$strDesignerCoID
			$numfieldstr
			
			CityId='$CityId',
			$countrysql
			SaveTime = '" . date('Y-m-d H:i:s') . "'
			,StationNumber='$StationNumber',XUTM1='$XUTM1',YUTM1='$YUTM1', 
			SaveDate = '" . date('Y-m-d') . "', 
			ClerkID = '" . $login_userid . "'
			WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
        else
			$query = "
			UPDATE applicantmaster SET
			CostPriceListMasterID = '" . $CostPriceListMasterID . "', 
			Code = '" . $Code . "',
			BankCode = '" . $BankCode . "', 
			DesignArea = '" . $DesignArea . "', 
			DesignAreamax = '" . $DesignAreamax . "', 
			Debi = '" . $Debi . "', 
			criditType = '" . $criditType . "', 
			DesignSystemGroupsID = '" . $DesignSystemGroupsID . "', 
			TransportCostTableMasterID = '" . $TransportCostTableMasterID . "', 
			RainDesignCostTableMasterID = '" . $RainDesignCostTableMasterID . "', 
			DropDesignCostTableMasterID = '" . $DropDesignCostTableMasterID . "', 
			MonthID = '" . $SelectedMonthID . "', 
			ApplicantName = '" . $ApplicantName . "', 
			ApplicantFName = '" . $ApplicantFName . "', 
			mobile= '" . $mobile . "', 
			melicode= '" . $melicode . "', 
			numfield= '" . $numfield . "'
			,StationNumber='$StationNumber',XUTM1='$XUTM1',YUTM1='$YUTM1'
			,CityId='$CityId',
			$countrysql
			$privateupdate
			$strDesignerID
			$strDesignerCoID
			$strDesignerCoIDnazer
			SaveTime = '" . date('Y-m-d H:i:s') . "', 
			SaveDate = '" . date('Y-m-d') . "', 
			ClerkID = '" . $login_userid . "'
			WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
        
			//print $query;exit;
       	  	  					try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		
        $register = true;
    
	if (strlen($_POST['prjtypeid'])>0)
		{
		      /*
    applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
    لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
    این جدول دارای ستون های ارتباطی زیر می باشد
    ApplicantMasterID شناسه طرح مطالعاتی
    prjtype جدول نوع پروژه ها
    
    */
    
			$query = "
			UPDATE `applicantmasterdetail` SET `prjtypeid` = '$_POST[prjtypeid]' WHERE `applicantmasterdetail`.`ApplicantMasterID` = $ApplicantMasterID; ";
				  	  			try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

			//  print $query;exit;	
		}
    
	if ($_POST["applicantstatesIDold"]<>$_POST["applicantstatesID"]) 
		{
		  //appchangestate جدول تغییر وضعیت طرح
			$query = "select max(stateno) stateno from appchangestate where ApplicantMasterID=$ApplicantMasterID ";
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
			if ($row['stateno']>0)			
			{
				$maxstateno=$row['stateno'];
				print $query = "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES('" .
					$ApplicantMasterID . "',".($maxstateno+1).",'$_POST[applicantstatesID]','', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
								try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
			}
		}

	if (strlen($_POST['errors'])>0)
		{
		  //operatorapprequest جدول پیشنهاد قیمت
			$query = "
				UPDATE `operatorapprequest` SET `errors` = '$_POST[errors]' 
				WHERE state=1 and `operatorapprequest`.`ApplicantMasterID` = $ApplicantMasterID; ";
				 
				//print $query;
								try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		}	
		
        if ($_POST['makezero']=='true')
        {
            //manuallistpriceall جدول فهارس بها
            mysql_query("update manuallistpriceall set price=0,pval1=0,pval2=0,pval3=0,SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID ='$login_userid' where ApplicantMasterID='$ApplicantMasterID' ");
        //manuallistprice جدول فهرست بهای آبیاری
            mysql_query("update manuallistprice set price=0,pval1=0,pval2=0,pval3=0,SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID ='$login_userid' where ApplicantMasterID='$ApplicantMasterID' ");
        }
        if ($register){
					    header("Location: ".$refpage);
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح مشخصات طرح</title>
	<meta http-equiv="X-Frame-Options" content="deny" />
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
 <!-- /scripts -->
    
    <script >
    
function CheckForm()
{
    if (document.getElementById('CostPriceListMasterID').value!=document.getElementById('CostPriceListMasterIDold').value)
    {
        document.getElementById('makezero').value=confirm('با توجه به تغییر فهرست بها آیا مبالغ آیتم های دستی صفر شود');
   }
        
    if ($('#ApplicantFName').length > 0)
    if (!(document.getElementById('ApplicantFName').value.length>0))
    {
        alert('نام متقاضی را وارد نمایید!');return false;
    }    
    if ($('#ApplicantName').length > 0)
    if (!(document.getElementById('ApplicantName').value.length>0))
    {
        alert('نام خانوادگی متقاضی را وارد نمایید!');return false;
    }
    if ($('#DesignArea').length > 0)
    if (!(document.getElementById('DesignArea').value.length>0))
    {
        alert('مساحت طرح را وارد نمایید!');return false;
    }
    if ($('#Debi').length > 0)
    if (!(document.getElementById('Debi').value.length>0))
    {
        alert('دبی طرح را وارد نمایید!');return false;
    }
    if ($('#soo').length > 0)
    if (!(document.getElementById('soo').value>0))
    {
        alert('استان طرح را وارد نمایید!');return false;
    }
    if ($('#sos').length > 0)
    if (!(document.getElementById('sos').value>0))
    {
        alert('شهرستان طرح را وارد نمایید!');return false;
    }
    
    if ($('#sob').length > 0)
    if (!(document.getElementById('sob').value>0))
    {
        alert('شهر/بخش طرح را وارد نمایید!');return false;
    }
    
    if ($('#CountyName').length > 0)
    if (!(document.getElementById('CountyName').value.length>0))
    {
        alert('روستای طرح را وارد نمایید!');return false;
    }
    /*
	if ($('#BankCode1').length > 0)
    if (!(document.getElementById('BankCode1').value.length>0) && !(document.getElementById('operatorcoid').value>0))
    {
        alert('لطفا بخش اول كد رهگيري را وارد نماييد!');return false;
    }
    if ($('#BankCode2').length > 0)
    if (!(document.getElementById('BankCode2').value.length>0) && !(document.getElementById('operatorcoid').value>0))
    {
        alert('لطفا بخش دوم كد رهگيري را وارد نماييد!');return false;
    }
    if ($('#BankCode3').length > 0)
    if (!(document.getElementById('BankCode3').value.length>0) && !(document.getElementById('operatorcoid').value>0))
    {
        alert('لطفا بخش سوم كد رهگيري را وارد نماييد!');return false;
    }
 
    if ($('#XUTM1').length > 0)
    if (!(document.getElementById('XUTM1').value>0))
    {
        alert('لطفا XUTM1 را وارد نماييد');return false;
    }
    if ($('#YUTM1').length > 0)
    if (!(document.getElementById('YUTM1').value>0))
    {
        alert('لطفا YUTM1 را وارد نماييد!');return false;
    }*/	
  
    if ($('#CostPriceListMasterID').length > 0)
    if (!(document.getElementById('CostPriceListMasterID').value>0))
    {
        alert('فهرست بهای طرح را وارد نمایید!');return false;
    }
    
    if ($('#DesignSystemGroupsID').length > 0)
    if (!(document.getElementById('DesignSystemGroupsID').value>0) && !(document.getElementById('DesignSystemGroupsID').value==-1))
    {
        alert('سیستم آبیاری طرح را وارد نمایید!');return false;
    }
    
    if ($('#TransportCostTableMasterID').length > 0)
    if (!(document.getElementById('TransportCostTableMasterID').value>0))
    {
        alert('جدول جدول ضرایب (حمل،تجهیز و...)  طرح را وارد نمایید!');return false;
    }
    if ($('#RainDesignCostTableMasterID').length > 0)
    if (!(document.getElementById('RainDesignCostTableMasterID').value>0))
    {
        alert('جدول حق الزحمه طراحی بارانی طرح را وارد نمایید!');return false;
    }
    if ($('#DropDesignCostTableMasterID').length > 0)
    if (!(document.getElementById('DropDesignCostTableMasterID').value>0))
    {
        alert('جدول حق الزحمه طراحی قطره ای/تلفیقی طرح را وارد نمایید!');return false;
    } 
    if ($('#DesignerID').length > 0)
    if (!(document.getElementById('DesignerID').value>0))
    {
        alert('لطفا کارشناس طراح را وارد نمایید!');return false;
    }    
    
  return true;
}
    
    
function FilterComboboxes(Url,Tabindex)
{ 
    //alert(1);
    var selectedCostPriceListMasterID;
    //alert(<?php print $login_ostanId; ?>);
    if ($('#CostPriceListMasterID').length > 0)
        selectedCostPriceListMasterID=document.getElementById('CostPriceListMasterID').value;
    if (selectedCostPriceListMasterID>0)
    selectedCostPriceListMasterID=selectedCostPriceListMasterID;
    else
    selectedCostPriceListMasterID=0;
    $.post(Url, {ostanid:<?php print $login_ostanId; ?>,selectedCostPriceListMasterID:selectedCostPriceListMasterID}, function(data){
    //alert (data.val1);
           
               
           if ($('#divTransportCostTableMasterID').length > 0)
           {
            if (selectedCostPriceListMasterID>0)
	           $('#divTransportCostTableMasterID').html(data.val2);
           }
       }, 'json');                      
}
function FilterComboboxes2(Url,Tabindex)
{ 
    //alert(2);
    var selectedsoo=document.getElementById('soo').value;
    var selectedsos=document.getElementById('sos').value;
    <?php if($login_RolesID==17 || $login_RolesID==26) echo 'selectedsos='.$login_CityId;?>
    //alert(selectedsos);
    
    $.post(Url, {selectedsoo:selectedsoo,ostanid:<?php print $login_ostanId; ?>,selectedsos:selectedsos}, function(data){
    //alert (data.val1);
           
    $('#divsos').html(data.val0);
    $('#divsob').html(data.val1);
               
          
       }, 'json');                      
}


function FilterComboboxes3(Url,Tabindex)
{ 
    var type=1;
    var melicode=document.getElementById('melicode').value;
    $.post(Url, {type:type,melicode:melicode}, function(data){
        if (!(data.val2>0))
            alert('کد/شناسه ملی یافت نشد. لطفا از منوی ثبت کشاورز مشخصات متقاضی را ثبت نمایید');
        else
        {
            //alert (data.val0);
            document.getElementById('ApplicantFName').value=data.val0;
            document.getElementById('ApplicantName').value=data.val1;
            //document.getElementById('shenasnamecode').value=data.val2;
            document.getElementById('registerplace').value=data.val3;
            document.getElementById('fathername').value=data.val4;
            document.getElementById('birthdate').value=data.val5;
            document.getElementById('mobile').value=data.val6;              
        }
  
       }, 'json');                      
}
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
            <?php include('../includes/subnavigation.php'); ?>
			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->
			<!-- content -->
			<div id="content">
                <form action="applicant_edit.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data" >
                <?php require_once('../includes/csrf_pag.php'); ?>
                   <table width="650" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "applicant_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                <?php
		if (($login_userid>0))
			print " <tr>
                      <td colspan='10' class='data'>سريال:
                      <input  value='$Code'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 80px\"
                      name='Code' type='text' class='textbox' id='Code'    />
                       
                      شخصیت:
                       <input  onclick = \"Filter('1');\" name=\"personality\" type=\"radio\" id=\"personality\" value=\"0\" checked >حقیقی </input>
                       <input   onclick = \"Filter('2');\" name=\"personality\" type=\"radio\" id=\"personality\" value=\"1\" >حقوقی </input>
                      
                      کد/شناسه ملي:
                      <input 
                      onblur = \"FilterComboboxes3('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 80px\" name='melicode' type='text' class='textbox' id='melicode' value='$melicode' size='15' maxlength='50' pattern=\"[0-9]{1,2}[0-9]{9}\" title=\"(10 رقم)\" required />
	                   
                      نام خانوادگی:
                      <input   readonly  value='$ApplicantName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 120px\" name='ApplicantName' type='text' class='textbox' id='ApplicantName'    size='15' maxlength='50' />
                      نام :
                      <input  readonly value='$ApplicantFName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 90px\" name='ApplicantFName' type='text' class='textbox' id='ApplicantFName'    size='15' maxlength='50' />
                       			  
					  شماره شناسنامه/ثبت:
                      <input    
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='shenasnamecode' type='text' class='textbox' id='shenasnamecode' value='$shenasnamecode' size='15' maxlength='50'  required />
					  
                      محل صدور/ثبت:
                      <input  readonly  
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='registerplace' type='text' class='textbox' id='registerplace' value='$registerplace' size='15' maxlength='50'  required />
					  
                      نام پدر:
                      <input  readonly  
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='fathername' type='text' class='textbox' id='fathername' value='$fathername' size='15' maxlength='50'  required />
					  
                      تاریخ تولد:
                      <input  readonly  
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='birthdate' type='text' class='textbox' id='birthdate' value='$birthdate' size='15' maxlength='50'  required />
					  
                      تلفن همراه:
                      <input  readonly  
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='mobile' type='text' class='textbox' id='mobile' value='$mobile' size='16' maxlength='50' />
                      </td>
					</tr><tr >
                      ";
               if ($login_RolesID==24)
                    {
                        
                        print "<td colspan='1'  class='label'>مساحت نقشه (هکتار):</td>
                      <td class='data'><input value='$SurveyArea' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='SurveyArea' type='text' class='textbox' id='SurveyArea'   /></td>
                       
                        ";
                    }
                     else
                    {
                       //if($login_RolesID==17) $heklbl='متراژ'; else 
						$heklbl='مساحت (هکتار)'; 
                        print "<td   class='label'>$heklbl:</td>
                      <td class='data'><input value='$DesignArea' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='DesignArea' type='text' class='textbox' id='DesignArea'   /></td>
                      <td  class='label'>دبی L/s:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='Debi'  value='$Debi' type='text' class='textbox' id='Debi'    /></td>";
                    }
					 
				if ($login_RolesID==19 || $login_RolesID==5 || $login_RolesID==1)
                    {
                       print "<td   colspan=2 class='label'>حداکثر مساحت (هکتار):</td>
                      <td class='data'><input value='$DesignAreamax' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                       name='DesignAreamax' type='text' class='textbox' id='DesignAreamax'   /></td>
                       
                        ";
                    }
                if ($login_RolesID!=24)
                    {
                    	 $query="SELECT DesignSystemGroupsID AS _value, Title AS _key FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 _value, 'قطره اي/ باراني' _key";
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td id='DesignSystemGroupsIDlbl'  class='label'>سیستم آبیاری:</td>".
                         select_option('DesignSystemGroupsID','',',',$ID,0,'','','1','rtl',0,'',$DesignSystemGroupsID,'','100');
                     
				    }
					print "</tr><tr>";
					 

                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' 
                    and  substring(id,1,2)=substring('$login_CityId',1,2) order by _key  COLLATE utf8_persian_ci";
    				 $ID1 = get_key_value_from_query_into_array($query);
                    
                    
    if($login_RolesID==17 || $login_RolesID==26) 
    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring($login_CityId,1,4)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    
    else
                    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($soo,1,2)
        and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
    				 $ID2 = get_key_value_from_query_into_array($query);
                    
                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring('$sob',1,4)
        and substring(id,6,2)='00' order by _key  COLLATE utf8_persian_ci ";
    				 $ID3 = get_key_value_from_query_into_array($query);
                    
                     print select_option('soo','استان',',',$ID1,0,'','','1','rtl',0,'',$soo,"",'135').
                     select_option('sos','دشت/شهرستان',',',$ID2,0,'','','1','rtl',0,'',$sos,"onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'80').
                     select_option('sob','شهر/بخش',',',$ID3,0,'','','1','rtl',0,'',$sob,'','95').
                     " <td class='label'>روستا:</td>
                      <td colspan='1' class='data'><input value='$CountyName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width:200px\" name='CountyName' type='text' class='textbox' id='CountyName'    size='5' maxlength='50' /></td>
                     ";
                     
                     if (!($login_userid>0))
                     {
                        print "<tr>
                      <td colspan='2'><input name='submit' type='submit' class='button' id='submit' value='ثبت مشخصات طرح' /></td>
                     </tr>
                     </tfoot>";
                     exit;
                     }
                      
					 if ($login_RolesID!=24 && $login_RolesID!=19)
                     {
					  $limited = array("9","1");
					if ($login_RolesID==17 || $login_RolesID==26)
                    $query="SELECT max(CostPriceListMasterID) as _value,
                             max(year.Value) as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfd=1 
                             ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                    else if ( in_array($login_RolesID, $limited))
					   $query="SELECT CostPriceListMasterID as _value,
                             year.Value as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfd=1
                             ORDER BY year.Value DESC ,month.Code DESC 
                             ";
                     else $query="SELECT CostPriceListMasterID as _value,
                             year.Value as _key FROM `costpricelistmaster` 
                             inner join year on year.YearID=costpricelistmaster.YearID
                             inner join month on month.MonthID=costpricelistmaster.MonthID
                             where pfo=1
                             ORDER BY year.Value DESC ,month.Code DESC ";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<tr >
                     
                     <td id='CostPriceListMasterIDlbl'  class='label'>فهرست بها:</td>".
                     select_option('CostPriceListMasterID','',',',$ID,0,'','','1','rtl',0,'',$CostPriceListMasterID,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'100');

					 $limited = array("9");
                     $query="select TransportCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from transportcosttablemaster
                            inner join year on year.YearID=transportcosttablemaster.YearID
                             inner join month on month.MonthID=transportcosttablemaster.MonthID
                             where pfd=1  and ostan='$login_ostanId' ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='TransportCostTableMasterIDlbl'  class='label'> جدول ضرایب (حمل،تجهیز و...):</td>".
                     select_option('TransportCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',$TransportCostTableMasterID,'','95');

					 $query="select RainDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from raindesigncosttablemaster
                            inner join year on year.YearID=raindesigncosttablemaster.YearID
                             inner join month on month.MonthID=raindesigncosttablemaster.MonthID
                             where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td colspan=2 id='RainDesignCostTableMasterIDlbl'  class='label'>جدول حق الزحمه طراحی بارانی:</td>".
                     select_option('RainDesignCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',$RainDesignCostTableMasterID,'','95')   ;

					 $query="select DropDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from dropdesigncosttablemaster
                            inner join year on year.YearID=dropdesigncosttablemaster.YearID
                             inner join month on month.MonthID=dropdesigncosttablemaster.MonthID
                             where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td colspan=1 id='DropDesignCostTableMasterIDlbl'  class='label'>طراحی قطره ای/تلفیقی:</td>".
                     select_option('DropDesignCostTableMasterID','',',',$ID,0,'','','1','rtl',0,'',$DropDesignCostTableMasterID,'','95').
                     "<tr>
                     ";
                    if ($login_RolesID!=19 && ($DesignerCoID>0 || $operatorcoid>0))
                    {
                    if ($DesignerCoID>0)
                        $query="select designerID as _value,CONCAT(LName,' ',FName,' ') as _key from designer 
                        where DesignerCoID='$DesignerCoID' ORDER BY LName";
                        else if ($operatorcoid>0)
                        $query="select designerID as _value,CONCAT(LName,' ',FName,' ') as _key from designer 
                        where OperatorCoID='$operatorcoid' ORDER BY LName";
                       //print $query;
  					    
    				 $ID = get_key_value_from_query_into_array($query);
                    
                     print "<td id='DesignerIDlbl'  class='label'>طراح:</td>";
                     if (count($ID)>1)
                        print select_option('DesignerID','',',',$ID,0,'','','1','rtl','','',$DesignerID,'','100');
                     else print "<td><input name='DesignerID' readonly id='DesignerID' value='$DesignerID' /></td>";
                        
                    }
                    //else
		            print "<td colspan='3' class='label'>+پروژه تجمیع
                      <input name='criditType' type='checkbox' id='criditType' $criditType />";
         
                     print " طرح شخصی است
                      <input name='private' type='checkbox' id='private'  value='1' $private /></td>";
                      
                    print "<td   class='label'>کدرهگیری:</td>
                      <td  colspan='2'  class='data'>
                       <input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode3' value='$BankCode3' min='1' max='99' type='number' class='textbox' id='BankCode3'    />
                       
                      -
                      <input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 90px\"
                       name='BankCode2' value='$BankCode2' min='1' max='9999999999' type='number' class='textbox' id='BankCode2'    />
                       -
                                            <input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 40px\"
                       name='BankCode1' value='$BankCode1' min='25' max='99' type='number' class='textbox' id='BankCode1'    />
                       </td>
                      ";
                     	$query="SELECT prjtypeid as _value,Title as _key from prjtype";
					   
					   $ID = get_key_value_from_query_into_array($query);
					   echo "<td class='label' id='prjtypeidlbl'>نوع پروژه:".
                       select_option('prjtypeid','',',',$ID,0,'','','1','rtl',0,'',$prjtypeid,'','80').
                       "</td>";
                
                    $fstr1="";$fstr2="";$fstr3="";$fstr4="";$fstr5="";$fstr6="";$fstr7="";$proposewinfilestr="";
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
								if (($ID==$ApplicantMasterID) && ($No==1) )
                                $fstr1="<td colspan='4'><font color='green' size='1'>فایل نقشه با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='فایل اتوکد' ></a></td>";
                            
								if (($ID==$ApplicantMasterID) && ($No==2) )
                                $fstr2="<td colspan='4'><font color='green' size='1'>فایل دفترچه با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='دفترچه طراحی' ></a></td>";
                            
								if (($ID==$ApplicantMasterID) && ($No==3) )
                                $fstr3="<td colspan='4'><font color='green' size='1'>فایل محاسبات با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='دفترچه محاسبات' ></a></td>";
						}
                    }
					$IDold=0;
                    $idstr="0";
		            $directory2 = $_SERVER['DOCUMENT_ROOT'].'/upfolder/madarek/';
                    $handler2 = opendir($directory2);
                    while ($file = readdir($handler2)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
					       if ($ID<>$IDold) $idstr.=",".$ID;
                           $IDold=$ID;
         					   if (($ID==$ApplicantMasterID) && ($No==4) )
                                $fstr4="<td colspan='4'><font color='green' size='1'>فایل منابع طبیعی با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/madarek/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='نامه منابع طبیعی' ></a></td>";
     
	                           if (($ID==$ApplicantMasterID) && ($No==5) )
                                $fstr5="<td colspan='4'><font color='green' size='1'>فایل مالکیت زمین با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/madarek/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='مالکیت زمین' ></a></td>";
     
	                           if (($ID==$ApplicantMasterID) && ($No==6) )
                                $fstr6="<td colspan='4'><font color='green' size='1'>فایل شناسنامه با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/madarek/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='شناسنامه' ></a></td>";
     
	                           if (($ID==$ApplicantMasterID) && ($No==7) )
                                $fstr7="<td colspan='4'><font color='green' size='1'>فایل کارت ملی با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/madarek/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='کارت ملی' ></a></td>";
                      	       
                              if (($ID==$ApplicantMasterID) && ($No==8) )
                                $proposewinfilestr="<td colspan='4'><font color='green' size='1'>فایل برنده پیشنهاد قیمت با موفقیت بارگذاری شد.</font></td><td><a href='../../upfolder/madarek/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='فایل برنده پیشنهاد قیمت' ></a></td>";
                      	 }
                    }
                    //print $idstr;exit;
					 print "
                     </tr> <tr>";
                      if ($DesignerCoID>0)
                     {
                        $permitrolsid = array("1", "5", "9", "10", "20");
                        if (in_array($login_RolesID, $permitrolsid))
                        {
                            if ($login_designerCO==1)
                                $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11";
                            else
                                $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11 and  
                                substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)";
                            	try 
								  {		
									       $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                             $allclerkID[' ']=' ';
                             while($row = mysql_fetch_assoc($result))
                                if (decrypt($row['DVFS'])<>'ج')
                                $allclerkID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                             $allclerkID=mykeyvalsort($allclerkID);
                             
                             
                            print "<tr><td  class='label'>بازبین:</td>".
                            select_option('DesignerCoIDnazer','',',',$allclerkID,0,'','','1','rtl','','',$DesignerCoIDnazer,'','125');
                        }
                     }
                     echo "</tr><tr>
                     <td colspan='1' class='label'></td>
					 <td><font color='red' size='3'></font></td>
                     <td colspan='3' class='data'>===========================</td>
					 </tr>";
                     
                     if ($operatorcoid>0)
                     echo "                     <tr>
                       <td colspan='2' style='text-align:right' class='label'>اسکن نامه برنده پیشنهاد</td>
					   <td><font color='red' size='3'>فرمت jpg</font></td>
                       <td colspan='2' class='data'><input type='file' name='file8' id='file8' accept='application/jpg'></td>
                       $proposewinfilestr
                     </tr>";
                     else 
                     echo "<tr>
                     <td colspan='2' class='label'>فایل&nbspنقشه&nbsp(با&nbspفرمت&nbspAutoCAD2007)</td>
					 <td><font color='red' size='3'>فرمت zip</font></td>
                     <td colspan='2' class='data'><input type='file' name='file1' id='file1' accept='application/zip'></td>
					 $fstr1
                     </tr>
                     
                    <tr>
						<td colspan='2' class='label'>فایل&nbspدفترچه&nbsp(با&nbspفرمت&nbspOffice2007)</td>
						<td><font color='red' size='3'>فرمت zip</font></td>
						<td colspan='2' class='data'><input type='file' name='file2' id='file2' accept='application/zip'></td>
						$fstr2
					</tr>
                     
                     <tr>
						 <td colspan='2' class='label'>فایل&nbspمحاسبات&nbsp(با&nbspفرمت&nbspOffice2007)</td>
						 <td><font color='red' size='3'>فرمت zip</font></td>
						 <td colspan='2' class='data'><input type='file' name='file3' id='file3' accept='application/zip'></td>
						 $fstr3
                     </tr>
                     <tr>
						 <td colspan='2' style='text-align:right' class='label'>فایل نامه منابع طبيعي</td>
						 <td><font color='red' size='3'>فرمت jpg</font></td>
						 <td colspan='2' class='data'><input type='file' name='file4' id='file4' accept='application/jpg'></td>
						 $fstr4
                     </tr>
                     <tr>
                         <td colspan='2' style='text-align:right' class='label'>فايل مالكيت زمين</td>
				    	 <td><font color='red' size='3'>فرمت jpg</font></td>
                         <td colspan='2' class='data'><input type='file' name='file5' id='file5' accept='application/jpg'></td>
                         $fstr5
                     </tr>
                      <tr>
                       <td colspan='2' style='text-align:right' class='label'>فايل شناسنامه</td>
				       <td><font color='red' size='3'>فرمت jpg</font></td>
                       <td colspan='2' class='data'><input type='file' name='file6' id='file6' accept='application/jpg'></td>
                       $fstr6
                     </tr>
                     <tr>
                       <td colspan='2' style='text-align:right' class='label'>فايل كارت ملي</td>
					   <td><font color='red' size='3'>فرمت jpg</font></td>
                       <td colspan='2' class='data'><input type='file' name='file7' id='file7' accept='application/jpg'></td>
                       $fstr7
                     </tr>";
                     echo"
                     </tbody>
                    <tfoot>";
                    }
                    print "<tr>";
					
		                ///////////////////فایل مجوز/////////////////////////
                        $fstrm="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/proposm/';
                        $handler = opendir($directory);
        
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if ($ID==$ApplicantMasterID)
                                {
                                    $fstrm="<td><a href='../../upfolder/proposm/$file' ><img style = 'width: 20px;' src='../img/accept.png' 
                                    title='اسکن' ></a></td>
                                     ";
                                }
                            }
                        }
                        //////////////////////////////////////////////////////
                   $permitrolsid = array("1");
                 if (in_array($login_RolesID, $permitrolsid))
                    {
						//    if ($apps==1)
						//       {
						//     	   $chked="checked";
						//		    print "	<td colspan='1' class='label'>پروژه کوچک درنظر گرفته شود</td>
						//			<td  class='data'><input readonly name='apps' type='checkbox' id='apps'  value='1' $chked /></td>
						//          ";   
						//        }
						//        else print "	<input name='apps' type='hidden' readonly class='textbox' id='apps'  value='$apps'  />";
                       
	                  $chked="";
                     if ($apps==1)
						 {
							$chked="checked";
							print "
							<td colspan='1' class='label'>پروژه کوچک</td>
								<td  class='data'><input readonly name='apps' type='checkbox' id='apps'  value='1' $chked /></td>";
						 }
					  else print "
							<td colspan='1' class='label'>پروژه کوچک </td>
                            <td  class='data'><input readonly name='apps' type='checkbox' id='apps'    /></td>";
    
                      $chked="";
                     if ($cappacityless==1)
						 {	$chked="checked";
							print "
							<td colspan='1' class='label'>پروژه خارج از ظرفیت</td>
								<td  class='data'><input readonly name='cappacityless' type='checkbox' id='cappacityless'  value='1' $chked /></td>";
						 }
					  else print "
							<td colspan='1' class='label'>پروژه خارج از ظرفیت </td>
                            <td  class='data'><input readonly name='cappacityless' type='checkbox' id='cappacityless'   /></td>";
							
						print "
							<td colspan='2' class='label'>اسکن مچوزدار</td>
                            <td colspan='2' class='data'><input type='file' name='filem' id='filem' accept='application/jpg'></td>
                             $fstrm ";
						print "<tr>
                            <td >کدپیشنهاد:</td>
                            <td class='data'><input value='$proposestate' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                            name='proposestate' type='text' class='textbox' id='proposestate'    /></td>";
						print "
                            <td >pکدپیشنهاد:</td>
                            <td class='data'><input value='$proposestatep' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                            name='proposestatep' type='text' class='textbox' id='proposestatep'    /></td>";
						print "
                            <td >ایزبند:</td>
                            <td class='data'><input value='$Datebandp' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                            name='Datebandp' type='text' class='textbox' id='Datebandp'    /></td></tr><tr>";
	
                    }
                     // print $ApplicantMasterID;
							$query = "SELECT operatorapprequest.errors FROM operatorapprequest 
									WHERE state=1 and operatorapprequest.ApplicantMasterID = $ApplicantMasterID";
								//print $query;
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
							$errors=$resquery['errors'];
			
					if ($DesignerCoID>0)
				    print "
						<tr>
                           <td colspan='8'  class='label'>توضیحات:			   
            		       <textarea id='errors' name='errors' rows='6' cols='100'>".$errors."</textarea>
						   </td></tr>
                      <td class='data'>
                      ";
					   
					   print "
                      <input name='refpage' type='hidden' class='textbox' id='refpage'  value='$refpage'  />
                      <input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID'  value='$ApplicantMasterID'  />
                      <input name='DesignerCoID' type='hidden' class='textbox' id='DesignerCoID'  value='$DesignerCoID'  />
                      <input name='operatorcoid' type='hidden' class='textbox' id='operatorcoid'  value='$operatorcoid'  />
                      <input name='CostPriceListMasterIDold' type='hidden' class='textbox' id='CostPriceListMasterIDold'  value='$CostPriceListMasterID'  />
                      <input name='applicantstatesIDold' type='hidden' class='textbox' id='applicantstatesIDold'  value='$applicantstatesID'  />
                      <input name='makezero' type='hidden' class='textbox' id='makezero'  value='false'  />
                      </td>
                     </tr>";
                    
                    if ($login_RolesID==24)
                    {
                        print "<tr><td  class='label'>تاریخ نقشه برداری:</td>
                        <td class='data'><input placeholder='انتخاب تاریخ'  name='surveyDate' type='text' class='textbox' id='surveyDate' 
                        value='$surveyDate' size='10' maxlength='10' /> </td>
						<td colspan='2' >شماره پرونده:
						<input 
						style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\" name='numfield' type='text' class='textbox' id='numfield' value='$numfield' size='15' maxlength='50' /></td>
						</tr> ";
	                }
                     
                     print " <td colspan='2' class='label'>نام و مختصات منبع آبی :</td>
                      <td colspan='1' class='data'><input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='StationNumber' type='text' class='textbox' id='StationNumber' value='$StationNumber'   size='15' maxlength='50' /></td>
                      
                      <td colspan='2' class='data'>Xutm:<input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='XUTM1' type='text' class='textbox' id='XUTM1'    size='15' maxlength='50' value='$XUTM1' /></td>
					  	    <td class='label' colspan=\"1\">Yutm:
                     <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='YUTM1' type='text' class='textbox' id='YUTM1' size='15' maxlength='50' value='$YUTM1' /></td>
					  
                      <td class='data'>Zone:
					    <select name=\"YUTM2\" value='$YUTM2'>
						  <option value=\"40\">40</option>
						  <option value=\"41\">41</option>
						</select>
                      </td>	";
                     
                     if ($prjtypeid==1)
                     $query="select creditsourceID as _value,title as _key from creditsource 
                             where creditsourceID in (20,21)
							 ORDER BY sortorder Desc";
                    else
                         $query="select creditsourceID as _value,title as _key from creditsource 
                             where ostan=substring($soo,1,2)
							 ORDER BY sortorder Desc";
                           //print $query;
        				 $ID = get_key_value_from_query_into_array($query);
                         print "</tr><tr><td id='creditsourceIDlbl' colspan='1'>منبع تامین اعتبار:".
                         select_option('creditsourceID','',',',$ID,0,'',$disabled,'1','rtl','','',$creditsourceID,'','125');
                       
				        $query="select applicantstatesID as _value,title as _key from applicantstates 
                                where 1=1
							 ORDER BY RolesID Desc";
                           //print $query;
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td id='applicantstatesIDlbl' colspan='1'>وضعیت:".
                         select_option('applicantstatesID','',',',$ID,0,'',$disabled,'1','rtl','','',$applicantstatesID,'','125');
                     
                    if ($login_RolesID==19 || $login_RolesID==17 )
                     { 
                     $query='select DesignerCoID as _value,Title as _key from designerco  order by _key  COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
                     print "".select_option('DesignerCoID',' شرکت طراح',',',$ID,0,'','','1','rtl',0,'',$DesignerCoID,'','130','');
                     }
                    print "<tr>
                      <td colspan='2'><input name='submit' type='submit' class='button' id='submit' value='تصحیح مشخصات طرح' /></td>
                     </tr>
                     </tfoot>";
				  ?>
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