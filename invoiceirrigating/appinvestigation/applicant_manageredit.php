<?php 

/*

//appinvestigation/applicant_manageredit.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantstates.php

*/

include('../includes/connect.php'); 
include('../includes/check_user.php');
include('../includes/elements.php'); 

/*
نقش ها:
1: مدیر پیگیری
13: مدیر آبیاری تحت فشار
14 ناظر عالی
17: ناظر مقیم
31: کارشناس آبرسانی
*/
$permitrolsidforselectproposable = array("1","13","14","17","31");//نقش هایی که می توانند لوله های طرح را به پیشنهاد قیمت ببرند
 $permitrolsidforselectproposablevals=implode(",", $permitrolsidforselectproposable);//تبدیل آرایه به رشته
if ($login_Permission_granted==0) header("Location: ../login.php");
$register = false;//متغیر انجام شدن ثبت یا خیر
if ($_POST)//در صورت کلیک دکمه سابمیت
	{
        //بارگذاری نامه شماره نامه ارسال پرونده
        if (!($login_userid>0)) header("Location: ../login.php");  		 
            if ($_FILES["filep"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file2"]["error"] . "<br>";
            } 
            else 
            {
                $IDUser = $_POST['IDUser'];
                $numname = $_POST['numname'];
                $path = $_POST['path'];		
                if (($_FILES["filep"]["size"] / 1024)>200)
                {
                    print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                }
                $ext = end((explode(".", $_FILES["filep"]["name"])));
                $attachedfile=$IDUser.'_1_'.$numname.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                //print $path.$attachedfile;
                foreach (glob($path. $IDUser.'_1*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["filep"]["tmp_name"],$path.$attachedfile);
            }
			$ids = $_POST['ids'];
			$linearray = explode('_',$ids);
			$ApplicantMasterID=$linearray[0];//شناسه طرح
			$type=$linearray[1];//نوع صفحه
			$DesignerCoID=$linearray[2];//شناسه شرکت مهندسین مشاور
			$operatorcoID=$linearray[3];//شناسه شرکت پیمانکار
			if ($DesignerCoID>0) 
				$ID=$DesignerCoID.'_1';
			else if ($operatorcoID>0) 
				$ID=$operatorcoID.'_2';
			
			$levelstr="";
			if ($_POST["level"]>0)//مرحله ارسالی به بانک طرح های آبرسانی
				$levelstr=",level = '$_POST[level]'";
			
            /*
            applicantmasterdetail جدول ارتباطی طرح ها
            nazerID شناسه مشاور ناظر
            bazrasID شناسه بازرس
            ApplicantMasterID شناسه طرح
            ApplicantMasterIDmaster شناسه طرح اجرایی
            */
            try 
                {		
                    mysql_query("update applicantmasterdetail set nazerID='$_POST[nazerID]',bazrasID='$_POST[bazrasID]' $levelstr
					where ApplicantMasterID='$ApplicantMasterID' or ApplicantMasterIDmaster='$ApplicantMasterID'");
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                } 				   
					
			 
			 if (in_array($login_RolesID, $permitrolsidforselectproposable))
			 if ($operatorcoID>0 && $_POST['issurat']!=1 && $login_userid>0)
			{
                //بروز رسانی ارسال پیشنهاد قیمت لوله های طرح
                /*
                invoicemaster لیست پیش فاکتورها
                proposable پیش فاکتور به پیشنهاد قیمت برود
                invoicemaster.Title عنوان پیش فاکتور
                invoicemaster.InvoiceMasterID شناسه پیش فاکتور
                producers جدول تولید کنندگان
                producersid شناسه تولید کننده
                PipeProducer تولید کننده لوله می باشد یا خیر
                ApplicantMasterID شناسه پیش فاکتور
                pricenotinrep مبلغ در هزینه های طر اعمال نشود
                invoicedetail ریز لوازم پیش فاکتور
                appstatesee جدول وضعیت هایی که هر نقش می بیند
                applicantstatesID شناسه وضعیت طرح
                RolesID شناسه نوع نقش
                */
				$sql = "SELECT invoicemaster.proposable,invoicemaster.Title,invoicemaster.InvoiceMasterID from invoicemaster 
								inner join producers on producers.producersid=invoicemaster.producersid and PipeProducer=1
								 where ApplicantMasterID='$ApplicantMasterID' and ifnull(pricenotinrep,0)=0
								 and invoicemaster.InvoiceMasterID in (select InvoiceMasterID from invoicedetail)
						and exists (select * from appstatesee where applicantstatesID='$_POST[applicantstatesID]' and RolesID in ($permitrolsidforselectproposablevals))
				;";
                try 
                {		
                    $result = mysql_query($sql);
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                } 
                
				
				while($row = mysql_fetch_assoc($result))
				{
                    try 
                        {
                            /*
                            invoicemaster لیست پیش فاکتورها
                            proposable پیش فاکتور به پیشنهاد قیمت برود
                            invoicemaster.Title عنوان پیش فاکتور
                            invoicemaster.InvoiceMasterID شناسه پیش فاکتور
                            ApplicantMasterID شناسه طرح
                            applicantmaster شناسه جدول مشخصات طرح
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            RDate تاریخ شروع پیشنهاد قیمت
                            */
        					mysql_query("update invoicemaster set proposable='".$_POST['invoice'.$row['InvoiceMasterID']]."' 
        					where InvoiceMasterID='$row[InvoiceMasterID]' and ApplicantMasterID not in 
        					(select ApplicantMasterID from producerapprequest where ApplicantMasterID='$ApplicantMasterID')");
        					mysql_query("update applicantmaster set 
        					SaveTime = '" . date('Y-m-d H:i:s') . "', 
       						SaveDate = '" . date('Y-m-d') . "', 
       						ClerkID = '" . $login_userid . "',
        					RDate='".date('Y-m-d')."' where ApplicantMasterID='$ApplicantMasterID'");
                        }
                        //catch exception
                        catch(Exception $e) 
                        {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                        }
                	    

				}
			}
			$permid = array("1","13","18");//تعدیل ضریب برنده پیشنهاد
			if (in_array($login_RolesID, $permid) 
            && ($DesignerCoID>0) //شناسه شرکت مهندسین مشاور
            && $login_userid>0  //شناسه کاربر لاگین کرده
            && $_POST['coef3']!=$_POST['coefold']) //ضریب سوم پیشنهاد قیمت
			{
                /*
                operatorapprequest جدول پیشنهاد قیمت های طرح
                applicantmaster جدول مشخصات طرح
                ApplicantMasterID شناسه طرح
                coef3 ضریب سوم اجرای طرح
                coefold ضریب سوم اجرای طرح قبل از تغییر
                coef3changedescription توضیحات تغییر وضعیت
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                */
                $query = "
				UPDATE operatorapprequest SET
				SaveTime = '" . date('Y-m-d H:i:s') . "', 
				SaveDate = '" . date('Y-m-d') . "', 
				ClerkID = '" . $login_userid . "',
				coef3 = '$_POST[coef3]', 
				coefold = '$_POST[coefold]',
				coef3changedescription = '$_POST[coef3changedescription]'
				WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
                $query2 = "
				UPDATE applicantmaster SET
				SaveTime = '" . date('Y-m-d H:i:s') . "', 
				SaveDate = '" . date('Y-m-d') . "', 
				ClerkID = '" . $login_userid . "',
				coef3 = '$_POST[coef3]'
				WHERE ApplicantMasterID='$_POST[amidmaster]';";
                try 
                    {
                        mysql_query($query);
                        mysql_query($query2);    
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                    }
			}                 
			
			if ($login_RolesID=='16' || //نقش کاربر صندوق
            $login_RolesID=='7' ||  // نقش کاربر بانک
            $login_RolesID=='1' || //نقش مدیر پیگیری
            $login_RolesID=='6'|| //نقش کارشناس سرمایه گذاری
            $login_RolesID=='17') //ناظر مقیم
			{
			 //ثبت خودیاری های غیر نقدی
				$_POST['selfnotcashhelpval1']=str_replace(',', '', $_POST['selfnotcashhelpval1']);//خودیاری های غیر نقدی 1
				$_POST['selfnotcashhelpval2']=str_replace(',', '', $_POST['selfnotcashhelpval2']);//خودیاری های غیر نقدی 2
				$_POST['selfnotcashhelpval3']=str_replace(',', '', $_POST['selfnotcashhelpval3']);//خودیاری های غیر نقدی 3
				
				$selfnotcashhelpdetail="$_POST[selfnotcashhelpval1]_$_POST[selfnotcashhelpdate1]_$_POST[selfnotcashhelpval2]_$_POST[selfnotcashhelpdate2]_$_POST[selfnotcashhelpval3]_$_POST[selfnotcashhelpdate3]";
				$selfnotcashhelpval=$_POST['selfnotcashhelpval1']+$_POST['selfnotcashhelpval2']+$_POST['selfnotcashhelpval3'];//مجموع خودیاری غیر نقدی
				$selfnotcashhelpdate=$_POST['selfnotcashhelpdate1'];//تاریخ پرداخت خودیاری غیر نقدی
				if (!($login_userid>0)) header("Location: ../login.php");
                $updatestrsand="";
                if ($_POST["creditsourceID"]>0) $updatestrsand.=",creditsourceID = '$_POST[creditsourceID]'";//مبلغ خودیاری غیر نقدی
											//else $updatestr.=",creditsourceID = ''";
				$belaavaz = str_replace(',', '', $_POST['belaavaz']);//بلاعوض
				if ($belaavaz>=0 && $belaavaz!='')
				    $updatestrsand.=",belaavaz = '$belaavaz'";
						
                /*
                applicantmaster جدول مشخصات طرح
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                selfcashhelpval مبلغ خودیاری نقدی
                selfnotcashhelpval مبلغ خودیاری غیرنقدی
                selfcashhelpdate تاریخ پرداخت خودیاری نقدی
                selfnotcashhelpdate تاریخ پرداخت خودیاری غیر نقدی
                selfcashhelpdescription شرح پرداخت خودیاری نقدی 
                selfnotcashhelpdetail ریز پرداخت خودیاری غیر نقدی
                letterno شماره نامه خودیاری
                letterdate تاریخ نامه خودیاری
                sandoghcode کد صندوق
                ClerkIDApproved کاربر ثبت کننده خودیاری
                ApplicantMasterID شناسه طرح
                */        
				$query = "
				UPDATE applicantmaster SET
				SaveTime = '" . date('Y-m-d H:i:s') . "', 
				SaveDate = '" . date('Y-m-d') . "', 
				ClerkID = '" . $login_userid . "',
				selfcashhelpval = '" .  str_replace(',', '', $_POST['selfcashhelpval']) . "', 
				selfnotcashhelpval = '$selfnotcashhelpval', 
				selfcashhelpdate = '" . $_POST['selfcashhelpdate'] . "', 
				selfnotcashhelpdate = '$selfnotcashhelpdate', 
				selfcashhelpdescription = '" . $_POST['selfcashhelpdescription'] . "', 
				selfnotcashhelpdetail='$selfnotcashhelpdetail',
				letterno = '$_POST[letterno]',
				letterdate = '$_POST[letterdate]',
				sandoghcode  = '$_POST[sandoghcode]'
                $updatestrsand,
				ClerkIDApproved = '" . $login_userid . "'
				WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";
                try 
                    {
                        mysql_query($query);   
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                    }
                    
			}
			//بارگذاری اسناد پروژه شامل دفترچه طراحی، محاسبات و نقشه
			if (($login_userid=='683' || //کاربر میز خدمت
            $login_RolesID=='17')&& //ناظر مقیم
            $ApplicantMasterID>0) //شناسه طرح بزرگتر از صفر باشد
			{
			     //فایل نقشه
				if ($_FILES["file1"]["error"] > 0) 
				{
					//echo "Error: " . $_FILES["file1"]["error"] . "<br>";
				} 
				else 
				{
					$ext = end((explode(".", $_FILES["file1"]["name"])));
					if ($ext=='zip')
					{
						foreach (glob("../../upfolder/" . $ApplicantMasterID.'_1*') as $filename) 
						{
							unlink($filename);
						}
						move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/" . $ApplicantMasterID.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);   
					}
				}
			     //فایل دفترچه
				if ($_FILES["file2"]["error"] > 0) 
				{
					//echo "Error: " . $_FILES["file2"]["error"] . "<br>";
				} 
				else 
				{
					$ext = end((explode(".", $_FILES["file2"]["name"])));
					
					if ($ext=='zip')
					{   
						foreach (glob("../../upfolder/" . $ApplicantMasterID.'_2*') as $filename) 
						{
							unlink($filename);
						}
						move_uploaded_file($_FILES["file2"]["tmp_name"],"../../upfolder/" . $ApplicantMasterID.'_2_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
					}
				}
				//فایل محاسبات
				if ($_FILES["file3"]["error"] > 0) 
				{
					//echo "Error: " . $_FILES["file3"]["error"] . "<br>";
				} 
				else 
				{
					$ext = end((explode(".", $_FILES["file3"]["name"])));
					if ($ext=='zip')
					{
						foreach (glob("../../upfolder/" . $ApplicantMasterID.'_3*') as $filename) 
						{
							unlink($filename);
						}
						move_uploaded_file($_FILES["file3"]["tmp_name"],"../../upfolder/" .$ApplicantMasterID.'_3_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
					}
				}
                //شماره نامه قرارداد
    			if (!($_FILES["file4"]["error"] > 0)) 
    			{   
    					if (($_FILES["file4"]["size"] / 1024)>200)
    					{
    						print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
    						exit;
    					}
    					$ext = end((explode(".", $_FILES["file4"]["name"])));
    					foreach (glob("../../upfolder/contract/" . $ApplicantMasterID.'*') as $filename) 
    					{
    						unlink($filename);
    					}
    					move_uploaded_file($_FILES["file4"]["tmp_name"],"../../upfolder/contract/" .$ApplicantMasterID.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
    			}
                //اسکن مجوز
    			if (!($_FILES["filem"]["error"] > 0)) 
    			{   
    					if (($_FILES["filem"]["size"] / 1024)>200)
    					{
    						print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
    						exit;
    					}
    					$ext = end((explode(".", $_FILES["filem"]["name"])));
    					foreach (glob("../../upfolder/proposm/" . $ApplicantMasterID.'*') as $filename) 
    					{
    						unlink($filename);
    					}
    					move_uploaded_file($_FILES["filem"]["tmp_name"],"../../upfolder/proposm/" .$ApplicantMasterID.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext);
    			//print $_FILES["filem"]["tmp_name"];exit;
    			}
			}
	       //بررسی اینکه کد رهگیری تکراری نباشد
		  if (!($_POST["ApplicantMasterIDmaster"]>0))
			{
			    /*
                applicantmaster جدول مشخصات طرح
                ApplicantMasterID شناسه جدول مشخصات طرح
                DesignerCoID شرکت مشاور طراح
                BankCode کد رهگیری طرح
                */
			   if ($DesignerCoID>0)//اگر شرکت طراح بود
					$query = "SELECT count(*) cnt 
						 FROM applicantmaster 
						 where ApplicantMasterID<>'$ApplicantMasterID' and ifnull(DesignerCoID,0)>0  
						 and BankCode='".$_POST["BankCode"]."' and ifnull(BankCode,0)>0 ";
				 else //در غیر اینصورت     
                /*
                applicantmaster جدول مشخصات طرح
                ApplicantMasterID شناسه جدول مشخصات طرح
                DesignerCoID شرکت مشاور طراح
                BankCode کد رهگیری طرح
                cityid شناسه شهر
                operatorapprequest جدول پیشنهاد قیمت های طرح
                state وضعیت طرح
                operatorcoID شناسه شرکت پیمانکار
                ApplicantMasterIDmaster شناسه طرح اجرایی
                */
                    $query = "SELECT count(*) cnt 
						 FROM applicantmaster 
						 inner join applicantmaster applicantmasterall on applicantmaster.BankCode=applicantmasterall.BankCode 
						 and substring(applicantmaster.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
						 inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
						 and applicantmaster.operatorcoID=operatorapprequest.operatorcoID
						 where applicantmaster.ApplicantMasterID<>'$ApplicantMasterID' and ifnull(applicantmaster.operatorcoID,0)>0  
						 and applicantmaster.BankCode='".$_POST["BankCode"]."' and ifnull(applicantmaster.BankCode,0)>0 
						 and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0";
                try 
                    {
                        $result = mysql_query($query);   
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                    }  
				
				$row = mysql_fetch_assoc($result);
				if ($row['cnt']>0)
				{
					echo "کد رهگیری وارد شده به طرح دیگری اختصاص داده شده است";
					exit;
				}       
			}
		  if ($_POST['inproposing']>0)
			{
				echo "طرح در حال دریافت پیشنهاد قیمت می باشد و امکان تغییرات فعلا وجود ندارد";
				exit;
			}
			  if (!($login_userid>0)) header("Location: ../login.php");
			/*
            applicantmaster جدول مشخصات طرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            ClerkIDApproved کاربر بروزکننده
            
            */
            $updatestr="UPDATE applicantmaster SET 
				SaveTime = '" . date('Y-m-d H:i:s') . "', 
						SaveDate = '" . date('Y-m-d') . "', 
						ClerkID = '" . $login_userid . "',
				ClerkIDApproved = '$login_userid'";
			/*
            applicantstatesID=22
            applicantstatesID=37
            */
				if (! in_array($_POST['applicantstatesID'],array(22,37)))
				{
					if ($_POST["private"]!="")//شخصی بودن طرح
						$updatestr.=",private = '$_POST[private]'";
					else  
						$updatestr.=",private = '0'";    
				}
                if ($_POST['prjtypeid']==1)//نوع پروژه آبرسانی
                {
                    if ($_POST['operatorcoIDbandp']>0)//پیمانکار ترک تشریفات
                    $updatestr.=",operatorcoIDbandp = '$_POST[operatorcoIDbandp]'"; 
                }
                    		
				
				$apps=0;//بزرگ بودن پروژه
                $cappacityless=0;//خارج ظرفیت بودن طرح
				if ($login_RolesID==1)
				{
					if ($_POST["apps"]=='on') $apps=1;
					if ($_POST["cappacityless"]=='on') $cappacityless=1;
				}
                //مشخصات ثبتی متقاضی  
				$CountyName= "$_POST[CountyName]_$_POST[registerplace]_$_POST[fathername]_$_POST[birthdate]_$_POST[shenasnamecode]_$apps"."_"."$cappacityless";
				$melicode= $_POST['melicode'];//کد ملی
			
				$updatestr.=",CountyName = '$CountyName'";///روستای طرح
				$updatestr.=",melicode = '$melicode'";// کد ملی طرح
				if ($_POST["mobile"]!="")
					$updatestr.=",mobile = '$_POST[mobile]'";//موبایل
					
				if ($_POST["XUTM1"]!="")//یو تی ام
				{	
					$StationNumber=$_POST['YUTM2']."_".$_POST['StationNumber'];
					$XUTM1=$_POST['XUTM1'];
					$YUTM1=$_POST['YUTM1'];
					$updatestr.= " ,StationNumber='$StationNumber',XUTM1='$XUTM1',YUTM1='$YUTM1' " ;
				}    
					
				if ($_POST["criditType"]!="")//تجمیع بودن طرح
					$updatestr.=",criditType = '$_POST[criditType]'";
				else  
					$updatestr.=",criditType = '0'"; 
				   
				if ($_POST["numfield"]!="")//تعداد سیستم ها
					$updatestr.=",numfield = '$_POST[numfield]'";
				 if ($_POST["contletterno"]!="" || $_POST["contletterdate"]!="")//تاریخ و شماره نامه قرارداد
					$updatestr.=",numfield2 = '".$_POST["contletterno"]."_".$_POST["contletterdate"]."'";
				if ($_POST["proposestate"]!="")//وضعیت پیشنهاد قیمت اجرا
					$updatestr.=",proposestate = '$_POST[proposestate]'";
				if ($_POST["DesignerCoIDnazer"]>0)//شرکت مشاور ناظر
					$updatestr.=",DesignerCoIDnazer = '$_POST[DesignerCoIDnazer]'";
				if ($_POST["ApplicantName"]!="")//نام خانوادگی متقاضی
					$updatestr.=",ApplicantName = '$_POST[ApplicantName]'";
				if ($_POST["ApplicantFName"]!="")//نام متقاضی
					$updatestr.=",ApplicantFName = '$_POST[ApplicantFName]'";
				if ($_POST["DesignSystemGroupsID"]>0 ||$_POST["DesignSystemGroupsID"]==-1)//شناسه سیستم آبیاری طرح
					$updatestr.=",DesignSystemGroupsID = '$_POST[DesignSystemGroupsID]'";
				if ($_POST["YearID"]>0)//سال طرح
					$updatestr.=",YearID = '$_POST[YearID]'";
					
				if ($_POST["DesignerCoIDchange"]>0 //شرکت مشاور طراح تغییریافته
                && ($DesignerCoID>0) // شرکت طراح
                && ($DesignerCoID!=$_POST["DesignerCoIDchange"]) ) 
				{
				    //بروز رسانی شرکت مشاور ناظر طرح
					$updatestr.=",DesignerCoID = '$_POST[DesignerCoIDchange]'";   
                    try 
                    {
                        $result = mysql_query($query);   
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                    }  
				}
				  if ($_POST["DesignerID"]>0)//شناسه طراح
					$updatestr.=",DesignerID = '$_POST[DesignerID]'";
				if ($_POST["Debi"]>0)//دبی
					$updatestr.=",Debi = '$_POST[Debi]'";
				if ($_POST["BankCode"]!="")//کد رهگیری
					$updatestr.=",BankCode = '$_POST[BankCode]'";
				/*
                applicantstatesID=22 انعقاد قرارداد
                applicantstatesID=24 دریافت پیشنهاد قیمت
                applicantstatesID=30 تایید پیش فاکتور
                applicantstatesID=37 انعقاد قرارداد (بانک)
                applicantstatesID=38 تحویل موقت
                applicantstatesID=45 تاييد صورت وضعيت
                $login_RolesID==1  مدیر پیگیری
                */
				if ((! in_array($_POST['applicantstatesID'],array(22,24,30,37,38,45))) || $login_RolesID==1)
					{
						if ($_POST["DesignArea"]>0) $updatestr.=",DesignArea = '$_POST[DesignArea]'";//مسات طرح
						if ($_POST["creditsourceID"]>0) $updatestr.=",creditsourceID = '$_POST[creditsourceID]'";//شناسه منبع اعتباری
						$belaavaz = str_replace(',', '', $_POST['belaavaz']);//مبلغ بلاعوض
						if ($belaavaz>=0 && $belaavaz!='')
							$updatestr.=",belaavaz = '$belaavaz'";
						if ($belaavaz=='') 
							$updatestr.=",belaavaz = ''";
					}
					$updatestr.=" WHERE ApplicantMasterID = " . $ApplicantMasterID . ";";//شناسه طرح
				    try 
                    {
                        mysql_query($updatestr);   
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                    }
                	
					
				if ($operatorcoID>0 && $login_userid>0)
					{
						try 
                        {
                            /*
                            applicantmaster جدول مشخصات طرح
                            DesignerCoIDnazer مشاور ناظر
                            BankCode کد رهگیری
                            ApplicantMasterID شناسه طرح
                            operatorcoid شناسه پیمانکار
                            SaveTime زمان
                            SaveDate تاریخ
                            ClerkID کاربر
                            */
                            if ($_POST["DesignerCoIDnazer"]>0)//مشاور ناظر
                                    mysql_query("UPDATE applicantmaster SET 
                                    SaveTime = '" . date('Y-m-d H:i:s') . "', 
                                    SaveDate = '" . date('Y-m-d') . "', 
                                    ClerkID = '" . $login_userid . "',
                                    DesignerCoIDnazer = '$_POST[DesignerCoIDnazer]'  
									WHERE 
									BankCode in (select BankCode from (select BankCode from applicantmaster where ApplicantMasterID = '$ApplicantMasterID') view1)
									and operatorcoid>0
									");   
                        }
                        //catch exception
                        catch(Exception $e) 
                        {
                            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                        }
                        
                              
					}
				
				
					if ($operatorcoID>0)//شناسه پیمانکار
					{
						$temporarydeliverydate=$_POST['temporarydeliverydate'];//تاریخ آزادسازی ظرفیت
						$Descriptiontemporarydeliverydate=$_POST["Descriptiontemporarydeliverydate"];//توضیحات  آزادسازی ظرفیت
						$temporarydissdate=$_POST['temporarydissdate'];//تاریخ انصراف از اجرا
						$Descriptiontemporarydissdate=$_POST["Descriptiontemporarydissdate"];//توضیحات انصراف از اجرا
					   if(($temporarydeliverydate>0))
						{
							$ApplicantstatesID=35;//وضعیت آزادسازی ظرفیت 
							$maxstateno=1000;//شماره ترتیب تغییر وضعیت
                            /*
                            applicantstatesID شناسه وضعیت طرح
                            appchangestate جدول تغییر وضعیت طرح ها
                            ApplicantMasterID شناسه طرح
                            */
							 $query = "SELECT applicantstatesID 
							 FROM appchangestate 
							 where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID='$ApplicantstatesID'  ";
							$result = mysql_query($query);
                            
                            try 
                            {
                                $row = mysql_fetch_assoc($result);   
                            }
                            //catch exception
                            catch(Exception $e) 
                            {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                            }
							if (($ApplicantMasterID>0) && ($row['applicantstatesID']!=$ApplicantstatesID))
							{
    							 /*
                                applicantstatesID شناسه وضعیت طرح
                                appchangestate جدول تغییر وضعیت طرح ها
                                ApplicantMasterID شناسه طرح
                                stateno شماره ترتیب تغییر وضعیت
                                Description توضیحات
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                                */
								$query = "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES('" .
								$ApplicantMasterID . "',$maxstateno,'$ApplicantstatesID','$Descriptiontemporarydeliverydate', '" . date('Y-m-d H:i:s'). "',
								'" . date('Y-m-d'). "','".$login_userid."');";
								
                                try 
                                {
                                    mysql_query($query);   
                                }
                                //catch exception
                                catch(Exception $e) 
                                {
                                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                                }
                                   
						   // print $query;
							}
                             /*
                                applicantstatesID شناسه وضعیت طرح
                                appchangestate جدول تغییر وضعیت طرح ها
                                ApplicantMasterID شناسه طرح
                                stateno شماره ترتیب تغییر وضعیت
                                Description توضیحات
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                             */
							 $query = "update appchangestate set SaveDate='" . date('Y-m-d'). "',
							 Description='$Descriptiontemporarydeliverydate',SaveTime='".date('Y-m-d H:i:s')."'  
							 where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID='$ApplicantstatesID'";
							       
							try 
                            {
                                mysql_query($query);   
                            }
                            //catch exception
                            catch(Exception $e) 
                            {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                            }
                            
						}   
					  else if(($temporarydissdate>0))
						{
							$ApplicantstatesID=34;//شناسه وضعیت انصراف از اجرا 
							$maxstateno=1100;//شماره ترتیب تغییر وضعیت
                            /*
                            applicantstatesID شناسه وضعیت طرح
                            appchangestate جدول تغییر وضعیت طرح ها
                            ApplicantMasterID شناسه طرح
                            */
							 $query = "SELECT applicantstatesID 
							 FROM appchangestate 
							 where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID='$ApplicantstatesID'  ";
							 //print $query;
							try 
                            {
                                $result = mysql_query($query);
                                $row = mysql_fetch_assoc($result);  
                            }
                            //catch exception
                            catch(Exception $e) 
                            {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                            } 
							
							if (($ApplicantMasterID>0) && ($row['applicantstatesID']!=$ApplicantstatesID))
							{
							     /*
                                applicantstatesID شناسه وضعیت طرح
                                appchangestate جدول تغییر وضعیت طرح ها
                                ApplicantMasterID شناسه طرح
                                stateno شماره ترتیب تغییر وضعیت
                                Description توضیحات
                                SaveTime زمان
                                SaveDate تاریخ
                                ClerkID کاربر
                                */
								$query = "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES('" .
								$ApplicantMasterID . "',$maxstateno,'$ApplicantstatesID','$Descriptiontemporarydissdate', '" . date('Y-m-d H:i:s'). "','".
								jalali_to_gregorian($temporarydissdate)."','".$login_userid."');";
								try 
                                {
                                    mysql_query($query);    
                                }
                                //catch exception
                                catch(Exception $e) 
                                {
                                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                                }
							 
							}
							 $query = "update appchangestate set SaveDate='".jalali_to_gregorian($temporarydissdate)."',
							 Description='$Descriptiontemporarydissdate',SaveTime='".date('Y-m-d H:i:s')."'  
							 where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID='$ApplicantstatesID'";
							       
							try 
                            {
                                mysql_query($query);   
                            }
                            //catch exception
                            catch(Exception $e) 
                            {
                                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                            }
                            
						}   
                        //تابع تغییر در مشخصات پیمانکار بعد از آزادسازی ظرفیت
                        //$DesignArea مساحت
                        //$operatorcoID شناسه پیمانکار
                        //$login_ostanId شناسه استان
						freeproject($DesignArea,$operatorcoID,3,$login_ostanId);
					}
				 $register = true;//ثبت شدن یا خیر
	}


        
		$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
		$linearray = explode('_',$ids);
		$ApplicantMasterID=$linearray[0];//شناسه طرح
		$type=$linearray[1];//نوع نمایش اطلاعات

		/*
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        level مرحله ارسالی به بانک
        nazerID شناسه ناظر
        applicantmasterdetail جدول ارتباطی طرح ها
        bazrasID شناسه بازرس
        prjtypeid شناسه نوع پروژه
        Description شرح
        SaveDate تاریخ
        ostanid شناسه استان
        shahrid شناسه شهر
        bakhshid شناسه بخش
        applicantstatesID شناسه وضعیت طرح
        ApplicantMasterIDmaster شناسه طرح اجرایی
        freestateid شناسه مرحله آزادسازی
        stateno شماره ترتیب تغییر وضعیت
        coef3 ضریب سوم پیشنهادی
        coef3changedescription ضریب سوم تصحیح شده توسط ناظر عالی
        ApplicantMasterIDsurat شناسه طرح صورت وضعیت
        applicantstatesID شناسه وضعیت طرح
        appchangestate جدول تغییر وضعیت طرح ها
        operatorapprequest جدول پیشنهاد قیمت های طرح
        state وضعیت طرح
        operatorcoID شناسه شرکت پیمانکار
        */	
		$query = "SELECT applicantmaster.*,operatorapprequesting.ApplicantMasterID operatorapprequestingApplicantMasterID,applicantmasterdetail.level
		,applicantmasterdetail.nazerID,applicantmasterdetail.bazrasID,applicantmasterdetail.prjtypeid
		,appchangestateTD.Description DescriptionTD,appchangestateTS.Description DescriptionTS
		,appchangestateTD.SaveDate temporarydeliverydateTD,appchangestateTS.SaveDate temporarydissdateTS
		,appchangestateR.Description DescriptionR,appchangestateTM.Description DescriptionTM ,appchangestateTM.SaveDate TechDate
		,ostan.id ostanid,shahr.id shahrid,bakhsh.id bakhshid,applicantstates.applicantstatesID
		,applicantmaster.ApplicantMasterIDmaster,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat,max(applicantfreedetail.freestateID) freestate
		,appchangestatestateno.stateno statenom,operatorapprequest.coef3,operatorapprequest.coef3changedescription 
		,case applicantmasterdetail.ApplicantMasterIDsurat>0 when 1 then applicantmasterdetail.ApplicantMasterIDsurat else 
		applicantmasterdetail.ApplicantMasterIDmaster end amidmaster 
		,applicantmasterdetail.ApplicantMasterID ApplicantMasterIDd,applicantmasterdetail.ApplicantMasterIDmaster ApplicantMasterIDop,
		applicantmasterdetail.ApplicantMasterIDsurat ApplicantMasterIDoplist
		 FROM applicantmaster 
		left outer join applicantmasterdetail on 
		(applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID'
		or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
		left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
		left outer join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid
		left outer join appchangestate appchangestateR on appchangestateR.ApplicantMasterID='$ApplicantMasterID' and appchangestateR.applicantstatesID=24 
		left outer join appchangestate appchangestateTM on appchangestateTM.ApplicantMasterID='$ApplicantMasterID' and appchangestateTM.applicantstatesID=1 
		left outer join appchangestate appchangestateTS on appchangestateTS.ApplicantMasterID='$ApplicantMasterID' and appchangestateTS.applicantstatesID=34
		 and appchangestateTS.stateno=(select max(stateno) from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID=34) 
		left outer join appchangestate appchangestateTD on appchangestateTD.ApplicantMasterID='$ApplicantMasterID' and appchangestateTD.applicantstatesID=35 
		and appchangestateTD.stateno=(select max(stateno) from appchangestate where ApplicantMasterID='$ApplicantMasterID' and applicantstatesID=35)
		left outer join operatorapprequest on operatorapprequest.ApplicantMasterID='$ApplicantMasterID' and state=1
		left outer join (select distinct ApplicantMasterID from operatorapprequest)operatorapprequesting on 
		operatorapprequesting.ApplicantMasterID='$ApplicantMasterID'
		left outer join (select ApplicantMasterID, max(stateno) stateno from appchangestate group by ApplicantMasterID) appchangestatestateno 
		 on appchangestatestateno.ApplicantMasterID=applicantmaster.ApplicantMasterID
		inner join appchangestate  on appchangestate.ApplicantMasterID=applicantmaster.ApplicantMasterID
		and appchangestate.stateno=appchangestatestateno.stateno
		inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
		left outer join applicantfreedetail on applicantfreedetail.ApplicantMasterID = applicantmaster.ApplicantMasterIDmaster
		WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID';";
        try 
            {
                $result = mysql_query($query);   
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }
			$resquery = mysql_fetch_assoc($result);
			
			if ($resquery["operatorapprequestingApplicantMasterID"]>0 && !($resquery["coef3"]>0))//ضریب سوم پیشنهادی
			{
				$inproposing=1;
				
			}
			$DesignerCoID=0;//شناسه مهندسین مشاور
			$operatorcoID=0;//شناسه پیمانکار
			if ($resquery["ApplicantMasterIDd"]==$ApplicantMasterID)//شناسه طرح
				$DesignerCoID=$resquery["DesignerCoID"];
			else $operatorcoID=$resquery["operatorcoid"];
			
			
			$ids = $ApplicantMasterID."_".$type."_".$DesignerCoID."_".$operatorcoID;

		if ($DesignerCoID>0) 
			$ID=$DesignerCoID.'_1';
		else if ($operatorcoID>0) 
			$ID=$operatorcoID.'_2';
			//مشخصات جغرافیایی
			$XUTM1=$resquery['XUTM1'];
			$YUTM1=number_format($resquery['YUTM1'],0,0,"");
			$linearray = explode('_',$resquery['StationNumber']);
			$YUTM2=number_format($linearray[0]); 
			$StationNumber=$linearray[1];    
		 
			$melicode= $resquery['melicode'];//شناسه ملی
			$level= $resquery['level']; //مرحله ارسال به بانک طرح های آبرسانی
			$mobile= $resquery['mobile']; //تلفن همراه   
			$amidmaster=$resquery["amidmaster"];//شناسه طرح اجرایی
			$coef3=$resquery["coef3"];//ضریب سوم پیشنهادی
			$coef3changedescription=$resquery["coef3changedescription"];//ضریب سوم تصحیح شده توسط ناظر عالی
			$applicantstatesID=$resquery["applicantstatesID"];// شناسه وضعیت طرح
			$criditType=$resquery["criditType"];//تجمیع بودن
			$Code = $resquery["Code"];//سریال طرح
			$issurat=$resquery["issurat"];//طرح فعلی صورت وضعیت است
			$SelectedYearID = $resquery["YearID"];//سال
			$ApplicantName = $resquery["ApplicantName"];//نام خانوادگی
			$ApplicantFName = $resquery["ApplicantFName"];//نام
			$SelectedMonthID = $resquery["MonthID"];  //ماه
			$CostPriceListMasterID=$resquery['CostPriceListMasterID'];//شناسه هزینه های اجرایی طرح
			$DesignArea = $resquery["DesignArea"];//مسات
			$statenom = $resquery["statenom"];//شماره ردیف تغییر وضعیت
			$ApplicantMasterIDmaster = $resquery["ApplicantMasterIDmaster"];//شناسه طرح اجرایی
			$freestate = $resquery["freestate"];//وضعیت آزادسازی
			$proposestate = $resquery["proposestate"];	//وضعیت پیشنهاد قیمت
			$numfield2array = explode('_',$resquery["numfield2"]);//مشخصات قرارداد
			$contletterno=$numfield2array[0];//شماره قرارداد
			$contletterdate=$numfield2array[1];//تاریخ قرارداد
			$BankCode = $resquery["BankCode"];//کد رهگیری
			$belaavaz = ($resquery["belaavaz"]);//بلاعوض
			$numfield=$resquery["numfield"];//تعداد محصولات
			$Debi = $resquery["Debi"];//دبی
			$DesignSystemGroupsID = $resquery["DesignSystemGroupsID"];//شناسه سیستم آبیاری
			$TransportCostTableMasterID = $resquery["TransportCostTableMasterID"];
			$RainDesignCostTableMasterID = $resquery["RainDesignCostTableMasterID"];
			$DropDesignCostTableMasterID = $resquery["DropDesignCostTableMasterID"];
			
			$linearray = explode('_',$resquery['CountyName']);
			$CountyName=$linearray[0];
			$registerplace=$linearray[1];
			$fathername=$linearray[2];
			$birthdate=$linearray[3];
			$shenasnamecode=$linearray[4];
			$apps=$linearray[5];
			$cappacityless=$linearray[6];
			
			$soo=$resquery["ostanid"];//شناسه استان
			$sos=$resquery["shahrid"];//شناسه شهر
			$sob=$resquery["bakhshid"];//شناسه بخش
			$RDate=$resquery["RDate"];//تاریخ دریافت دفترجه مطالعات
			if ($resquery["temporarydeliverydateTD"]<>"") $temporarydeliverydate=gregorian_to_jalali($resquery["temporarydeliverydateTD"]);//تاریخ تحویل دائم
			if ($resquery["temporarydissdateTS"]<>"") $temporarydissdate=gregorian_to_jalali($resquery["temporarydissdateTS"]);//تاریخ تحویل موقت
			$Descriptiontemporarydissdate=$resquery["DescriptionTS"];//شرح تحویل موقت
			$Descriptiontemporarydeliverydate=$resquery["DescriptionTD"];//شرح تحویل دائم
			if ($resquery["TechDate"]<>"")//تاریخ جلسه کمیته فنی
                $TechDate=gregorian_to_jalali($resquery["TechDate"]);
			$DescriptionR=$resquery["DescriptionR"];//شرح دریافت دفترچه مطالعات
			$DescriptionTM=$resquery["DescriptionTM"];//شرح جلسه کمیته فنی
			$creditsourceID=$resquery["creditsourceID"];//شناسه منبع تامین اعتبار
			$selfcashhelpdate=$resquery["selfcashhelpdate"];//تاریخ دریافت خودیاری نقدی
			$selfcashhelpval=number_format($resquery["selfcashhelpval"]);//مبلغ دریافت خودیاری نقدی
			$selfcashhelpdescription=$resquery["selfcashhelpdescription"];//شرح دریافت خودیاری نقدی
			$letterno=$resquery["letterno"];//شماره نامه صندوق
			$letterdate=$resquery["letterdate"];//تاریخ نامه صندوق
			$sandoghcode=$resquery["sandoghcode"];//کد صندوق
			if (strlen(trim($resquery["selfnotcashhelpdetail"]))>0)//خودیاری غیر نقدی
			{
				$larr = explode('_',$resquery["selfnotcashhelpdetail"]);
				if ($larr[0]>0)
				$selfnotcashhelpval1=number_format($larr[0]);//مقدار خودیاری غیر نقدی 1
				$selfnotcashhelpdate1=$larr[1]; //تاریخ خودیاری غیر نقدی 1
				if ($larr[2]>0)  
				$selfnotcashhelpval2=number_format($larr[2]);//مقدار خودیاری غیر نقدی 2 
				$selfnotcashhelpdate2=$larr[3];//تاریخ خودیاری غیر نقدی 2
				if ($larr[4]>0)
				$selfnotcashhelpval3=number_format($larr[4]);//مقدار خودیاری غیر نقدی3 
				$selfnotcashhelpdate3=$larr[5];//تاریخ خودیاری غیر نقدی 3
			}
			else
			{
				$selfnotcashhelpval1=number_format($resquery["selfnotcashhelpval"]);//مقدار خودیاری غیر نقدی 
				$selfnotcashhelpdate1=$resquery["selfnotcashhelpdate"];//تاریخ خودیاری غیر نقدی     
			}
			$DesignerCoIDnazer=$resquery["DesignerCoIDnazer"];//شناسه مشاور ناظر
			$nazerID=$resquery["nazerID"];//شناسه ناظر
			$bazrasID=$resquery["bazrasID"];//شناسه بازرس
			$operatorcoIDbandp=$resquery["operatorcoIDbandp"];//شناسه پیمانکار ترک تشریفات
			$prjtypeid=$resquery["prjtypeid"];//نوع پروژه
			if ($resquery["creditsourceID"]>0)//منبع تامین اعتبار
				$selectedcreditsourceID=$resquery["creditsourceID"];
			else $selectedcreditsourceID=4;    
			$DesignerID=$resquery["DesignerID"];//شناسه طراح
			$private= $resquery['private'];//شخصی بودن طرح
			if ($private>0)      
			   $private="checked";
			$sumsurat=$resquery['LastTotal'];//مبلغ کل هزینه های طرح 
			$criditType= $resquery['criditType'];//تجمیع بودن طرح
			$criditTypes= $resquery['criditType'];
			if ($criditType>0)      
			   $criditType="checked";
		$sysbelaavaz=0;//بلاعوض
        //تابع محاسبه بلاعوض سیستمی
        //$selectedcreditsourceID شناسه منبع تامین اعتبار
        //$ApplicantMasterID شناسه طرح
        //$sumsurat مجموع مبلغ صورت وضعیت
        //$criditTypes تجمیع بودن طرح
		$sysbelaavaz=calculatebelavaz($selectedcreditsourceID,$ApplicantMasterID,$sumsurat,$criditTypes);
		$path = "../../upfolder/sandugh/";//مسیر بارگذاری قرارداد صندوق
  
	
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>تصحیح طرح</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
    
    function CheckForm()
{
    if (document.getElementById('DesignerCoIDchange').value.substr(1,4)=='9999' && '<?php echo $login_RolesID; ?>'!='1' && '<?php echo $login_RolesID; ?>'!='5')
    {
        alert ('ظرفیت قرارداد مطالعاتی شرکت مشاور طراح انتخاب شده تکمیل می باشد. لطفا جهت ثبت به  معاون محترم آب و خاک جناب آقای فاطمی مراجعه فرمایید.');    
        return false;
    }
    
    return confirm('مطمئن هستید که تغییر  اعمال شود ؟');;
}

    
            $(function() {
                $("#RDate, #simpleLabel").persiandatepicker();  
                $("#TechDate, #simpleLabel").persiandatepicker(); 
                $("#temporarydeliverydate, #simpleLabel").persiandatepicker(); 
                $("#temporarydissdate, #simpleLabel").persiandatepicker(); 
                $("#selfcashhelpdate, #simpleLabel").persiandatepicker();    
                $("#selfnotcashhelpdate, #simpleLabel").persiandatepicker();  
            });
            
            
            
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
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
    <?php if($login_RolesID==17) echo 'selectedsos='.$login_CityId;?>
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
    //alert (data.val0);
    document.getElementById('ApplicantFName').value=data.val0;
    document.getElementById('ApplicantName').value=data.val1;
    document.getElementById('shenasnamecode').value=data.val2;
    document.getElementById('registerplace').value=data.val3;
    document.getElementById('fathername').value=data.val4;
    document.getElementById('birthdate').value=data.val5;
    document.getElementById('mobile').value=data.val6;    
       }, 'json');                      
}
        
        
    </script>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
	
				if ($_POST){
				
					if ($register){
						//echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Code = "";
						$YearID = "";
						
                        if ($type==2)
                        {
                            header("Location: applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                        }
                        else if ($type==1)
                        {
                            header("Location: allapplicantstates.php");
                        }
                        
                        else if ($type==3)
                        {
						
                            header("Location: allapplicantstatesop.php");
						
                        }
                        else if ($type==4)
                        {
                            header("Location: allapplicantstatesoplist.php");
                        }
                        else if ($type==5)
                        {
                            header("Location: allapplicantrequestws.php");
                        }
                        else header("Location: home.php");
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
                    header("Location: home.php");
				}
 
 
 

$uid="?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
             rand(10000,99999).$ApplicantMasterID.'_'.$type.'_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID'].rand(10000,99999);


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
                <form action="applicant_manageredit.php<?php echo $uid;?>" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data" >
                   <table width="600" align="center" class="form">
                    <tbody>
					<div style = "text-align:rigth;">
                 
				 <?php 
				
                    $permitrolsid = array("1","5", "19");
                    if (in_array($login_RolesID, $permitrolsid) && $DesignerCoID>0 )
                    {
					$imgfile='';
					$numname='';
			        $IDUser =$SelectedYearID.'p'.$ApplicantMasterID;
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/sandugh/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                     {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDU=$linearray[0];
                            $No=$linearray[1];
							$num=$linearray[2];
				            if (($IDU==$IDUser) && ($No==1) ) {$imgfile=$file;$numname=$num;}
			            }
				     }
                  ?> 
	<td colspan="5" class='data'><input type='file' name='filep' id='filep' value='123' >شماره نامه ارسال پرونده:
	<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\" 
			name='numname' type='text' class='textbox' id='numname' value='<?php echo $numname; ?>' size='10' maxlength='10' /></td>	
		<td><?php print '<img src='.'/upfolder/sandugh/'.$imgfile.' width=35 height=25>';?></td>
		<td> <input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>"></td>
		<td> <input type="hidden" name="path" value ="<?php echo $path; ?>"></td>
		<td> <input type="hidden" name="inproposing" value ="<?php echo $inproposing; ?>"></td>
        
        

          		<?php
                        echo "<a  target='".$target."' href='applicant_tosandogh.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).
                                    "'><img style = 'width: 3%;' 
                                    src='../img/mail_send.png' title=' نامه ارسال پرونده طرح به صندوق جهت تامین اعتبار '></a>";
                     
					}
                    $permitrolsid = array("1","5", "19","13","14","11");
                    if (in_array($login_RolesID, $permitrolsid))
                    {
					echo "<a  target='".$target."' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1_0_0_'.$applicantstatesID.rand(10000,99999)."'>
                            <img style = 'width: 3%;' src='../img/search_page.png' title=' ريز '></a>"; 
                    }
                    $permitrolsid = array("1","19");
                    if (in_array($login_RolesID, $permitrolsid))
                    
                    echo
                    "<a target='_blank' href='../insert/applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999)."'>
										<img style = 'width: 3%;' src='../img/file-edit-icon.png' title=' ویرایش طرح '></a>";
                    
                    $permitrolsid = array("1","19");
                    if (in_array($login_RolesID, $permitrolsid))
                    
                    echo
                    "<a target='_blank' href='prjcontracts.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999)."'>
										<img style = 'width: 3%;' src='../img/law.png' title=' قراردادهای مشاورین '></a>";
                                          
                    $permitrolsid = array("1","14", "17","10","5","8","13","20","21","23");
                    if (in_array($login_RolesID, $permitrolsid)  && $issurat==1)
                    {
                        
                        echo "<a  target='".$target."' href='applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID."_5_".$applicantstatesID.rand(10000,99999).
                                    "'><img style = 'width: 3%;' 
                                    src='../img/folder_accept.png' title='صورتجلسه تحویل موقت'></a>";
                                    
                    }
			if ($prjtypeid==1)
                    {
                        
                        echo "<a  target='".$target."' href='applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID."_5_".$applicantstatesID.rand(10000,99999).
                                    "'><img style = 'width: 3%;' 
                                    src='../img/folder_accept.png' title='صورتجلسه تحویل موقت'></a>";
									
                    
						echo "<a 
                                   target='_blank' href='../appinvestigation/applicant_form10.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/mail_send.png' title='فرم تاییدیه کمیته فنی'></a>";
                  					
                    }



$ID = $ApplicantMasterID.'_5_'.$DesignerCoID.'_'.$operatorcoid.'_'.$applicantstatesID.'_'.$ApplicantMasterIDmaster;
  // print $ID;exit;
 if ($ApplicantMasterIDmaster>0 && (in_array($applicantstatesID, array("30","40","45")) && in_array($login_RolesID, array("13","1","18"))))
                             {
							 
                                if ($freestate=='' && $applicantstatesID==30)
                                    print " <a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('پیش فاکتور طرح تایید گردیده است. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 25px;' src='../img/next.png' title='برگشت به کارتابل'> </a>";
                                else if (($freestate!='143' && $applicantstatesID==45) || ($login_RolesID==18 && $applicantstatesID==45))
                                    print "<a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm(' صورت وضعیت طرح تایید گردیده است. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 25px;' src='../img/nextr.png' title='برگشت به کارتابل'> </a>";    
								 else 
                                    print "<a 
                                    href='allapplicantstatesoplist.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('امکان تغییر وضعیت وجود ندارد!');\"
                                    > <img style = 'width: 25px;' src='../img/nextr.png' title='برگشت به کارتابل'> </a>";    
                             }


					
                     ?>
                     <a  href=<?php 
                    if ($type==2)
                        {
                            print "applicantstates.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); 
                        }
                        else if ($type==1)
                        {
                            print "allapplicantstates.php"; 
                        } 
                        else if ($type==3)
                        {
                            print "allapplicantstatesop.php"; 
                        } 
                        else if ($type==4)
                        {
                            print "allapplicantstatesoplist.php"; 
                        }
						else if ($type==5)
                        {
                            print "allapplicantrequestws.php"; 
                        }
                    ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a>
					
					
                    </div>
                            
                    
                     <?php
					 if ((! in_array($applicantstatesID,array(22,24,30,37,38,45))) || in_array($login_RolesID, array("1", "16")))
						{$rbla="";$disabled='';}
					 else
						{$disabled='disabled';$rbla="readonly";}
						
						 
                     
                       $permitrolsid = array("1", "5", "11", "19","16","7","31");
                     if (in_array($login_RolesID, $permitrolsid))
                        $readonly="";
                     else $readonly="readonly";   
                         
                     print "
					 </tr>
                         <tr>
                         <td colspan='8' >
                       شخصیت:
                       <input  onclick = \"Filter('1');\" name=\"personality\" type=\"radio\" id=\"personality\" value=\"0\" checked >حقیقی </input>
                       <input   onclick = \"Filter('2');\" name=\"personality\" type=\"radio\" id=\"personality\" value=\"1\" >حقوقی </input>
                      
                      
                       کد ملي:
                      <input 
                      onblur = \"FilterComboboxes3('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 80px\" name='melicode' type='text' class='textbox' id='melicode' value='$melicode' size='15' maxlength='50' pattern=\"[0-9]{1,2}[0-9]{9}\" title=\"(10 رقم)\" required />
					  
                      
                          نام خانوادگی:
                          <input readonly value='$ApplicantName' $readonly
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='ApplicantName' type='text' class='textbox' id='ApplicantName'    size='15'  />
						  
                          
						  &nbsp;&nbsp;نام: 
						  <input readonly value='$ApplicantFName' $readonly
                          style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='ApplicantFName' type='text' class='textbox' id='ApplicantFName'    size='15'  />
                       
                       
                      			  
					  شماره شناسنامه/ثبت:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='shenasnamecode' type='text' class='textbox' id='shenasnamecode' value='$shenasnamecode' size='15' maxlength='50'  required />
					  </td></tr><td colspan='8'>
                      محل صدور/ثبت:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='registerplace' type='text' class='textbox' id='registerplace' value='$registerplace' size='15' maxlength='50'  required />
					  
                      نام پدر:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='fathername' type='text' class='textbox' id='fathername' value='$fathername' size='15' maxlength='50'  required />
					  
                      تاریخ تولد:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\" name='birthdate' type='text' class='textbox' id='birthdate' value='$birthdate' size='15' maxlength='50'  required />
					  
                      تلفن همراه:
                      <input readonly
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='mobile' type='text' class='textbox' id='mobile' value='$mobile' size='16' maxlength='50' />
                      
                      
					      &nbsp;&nbsp;مساحت (هکتار):
                          <input  value='$DesignArea' $rbla $readonly style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 70px\"
                           name='DesignArea' type='text' class='textbox' id='DesignArea'  />
                          
						  &nbsp;&nbsp;دبی L/s:
                          <input  style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 65px\"
                           name='Debi'  value='$Debi' $readonly type='text' class='textbox' id='Debi'    /></td> </tr>
                         
                         <td class='label'>روستا:</td>
                            <td colspan='1' class='data'><input readonly value='$CountyName'
                            style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='CountyName' type='text' class='textbox' id='CountyName'    size='5' maxlength='50' /></td>
                            
                        
                         <td class='label'>مرحله ارسالی:</td>
                            <td colspan='1' class='data'><input  value='$level'
                            style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='level' type='text' class='textbox' id='level'    size='5' maxlength='50' /></td>
                        
                            
                         
                            ";
        					
				 ?>    
						     </tbody>
                             <tbody	>
             <?php
    
                         
                     $permitrolsid = array("1","11");//
					 
                     if (in_array($login_RolesID, $permitrolsid) && $DesignerCoID>0)
                    {
                        echo " 
                        <tr><td  colspan='12' >تاریخ وصول پرینت دفترچه طراحی:
                        <input placeholder='انتخاب تاریخ'  name='RDate' type='text' class='textbox' id='RDate' value='$RDate' size='10' maxlength='10' />
                        <span id='span1'></span>
						توضیحات:<textarea id='DescriptionR' name='DescriptionR' rows='2'  cols='50' >$DescriptionR</textarea></td></tr>
                        	
                        ";
						      print "     <tr><td class='label'>نام و مختصات منبع آبی :</td>
                      <td colspan='1' class='data'><input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='StationNumber' type='text' class='textbox' id='StationNumber' value='$StationNumber'   size='15' maxlength='50' /></td>
                      
                      <td colspan='1' class='data'>Xutm:<input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 135px\" name='XUTM1' type='text' class='textbox' id='XUTM1'    size='15' maxlength='50' value='$XUTM1' /></td>
					  	    <td class='label' colspan=\"1\">Yutm:
                     <input 
                      style = \"border:1px solid black;border-color:#D1D1D1;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='YUTM1' type='text' class='textbox' id='YUTM1' size='15' maxlength='50' value='$YUTM1' /></td>
					  
                      <td class='data'>Zone:
					    <select name=\"YUTM2\" value='$YUTM2'>
						  <option value=\"40\">40</option>
						  <option value=\"41\">41</option>
						</select>
					
                      </td></tr>	";
              
						
						
                    }
                     
                     
                    $permitrolsid = array("1","13","14","17");//
                    if (in_array($login_RolesID, $permitrolsid) && !($DesignerCoID>0) && !($issurat>0))
                    {
							$linearray = explode('_',returnerrnumemtiaz ($ApplicantMasterID));
							$errnum=$linearray[0];
							$emtiaz=$linearray[2];
							if ($login_userid==22)
							{
								$errnum=10;
								$emtiaz=100;
							}
						if ($type!=4) 
						{
							if ($applicantstatesID!=35)
							if ($applicantstatesID!=30 && $issurat<1)
							{ echo "<tr><td colspan='6' style='color:red;'>آزادسازی ظرفیت به دلیل عدم تایید ناظر طرح امکانپذیر نمی باشد.</td></tr>";
							}
							if ($applicantstatesID!=35 && $errnum<8)
							{ echo "<tr><td colspan='6' style='color:red;'>آزادسازی ظرفیت به دلیل عدم تکمیل جدول زمانبندی اجرای طرح توسط مشاور ناظر امکانپذیر نمی باشد.</td> <td colspan='2'>امتیاز ارزشیابی: $emtiaz</td></tr>";
							}
							if ($applicantstatesID!=35 && $emtiaz<=0)
							{ echo "<tr><td colspan='7' style='color:red;'>آزادسازی ظرفیت به دلیل عدم تکمیل جدول ارزشیابی پیمانکار امکانپذیر نمی باشد.</td>";
							}
							else 
							if ($applicantstatesID!=35 && $emtiaz<=65)
							{ echo "<tr><td colspan='7' style='color:red;'>آزادسازی ظرفیت به دلیل پایین بودن مجموع امتیاز (ناظرعالی- ناظر مقیم- دستگاه نظارت) ارزشیابی امکانپذیر نمی باشد.</td>";
							}
							else if ($statenom<>1100) 
							{
								if ($statenom<>1000) {$temporarydeliverydate='';$Descriptiontemporarydeliverydate='';}
								else $hazf="<td><a 
									href=\"dissdatedetail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
									rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_1_".$DesignerCoID."_".$operatorcoID."_".$DesignArea."_".$login_ostanId.rand(10000,99999)."\"
									onClick=\"return confirm('مطمئن هستید حذف شود ؟');\"
									> <img style = 'width: 25px;' src='../img/delete.png' title='حذف آزادسازی ظرفیت'> </a></td><td colspan='1'></td>";
								
							echo "<td  colspan='5'>تاریخ آزادسازی ظرفیت:
							<input placeholder='انتخاب تاریخ'  name='temporarydeliverydate' type='text' class='textbox' id='temporarydeliverydate' value='$temporarydeliverydate' size='10' maxlength='10' />
							<span id='span1'></span> توضیحات:<textarea id='Descriptiontemporarydeliverydate' name='Descriptiontemporarydeliverydate' rows='2'  cols='50' >$Descriptiontemporarydeliverydate</textarea></td>
							".$hazf."
							<td colspan='2'>امتیاز ارزشیابی: $emtiaz</td></tr>";
							
							}
						}
						else {
							echo "<tr> 
							<td  colspan='5'>تاریخ آزادسازی ظرفیت:
							<input id='temp' name='temp' value='$temporarydeliverydate' size=10 readonly></input>
										توضیحات:<textarea readonly id='Descriptiontemporarydeliverydate' name='Descriptiontemporarydeliverydate' rows='2'  cols='50' >$Descriptiontemporarydeliverydate</textarea></td>
							<td colspan='2'>امتیاز ارزشیابی: $emtiaz</td>
							</tr>";
						}
						
                    }
					 
					 
					        					
				 ?>    
						     </tbody>
                             <tbody	>
             <?php
    
   
                      $permitrolsid = array("1", "5", "19" );
                     if (in_array($login_RolesID, $permitrolsid))
                     {
                        if ($DesignerCoID>0 && ($applicantstatesID==8 || $applicantstatesID==15 || $applicantstatesID==23))
                            $ronly='';
                        else 
                            $ronly='readonly';
                       
                       
                        echo " 
                        <tr><td  colspan='2' >کد&nbsp;رهگیری:
                      <input  value='$BankCode' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 120px\"
                       name='BankCode' type='text' class='textbox' id='BankCode'  $ronly /></td>

					<td colspan='2' >شماره پرونده:
                      <input 
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\" name='numfield' type='text' class='textbox' id='numfield' value='$numfield' size='15' maxlength='50' /></td>				   ";
                     }
                     
                     
                      $permitrolsid = array("1","5","13","14","19","20","27","17");//شاه تقی
					  
                     if (in_array($login_RolesID, $permitrolsid) )
                     {
                        $fstr1="";
                        $fstr2="";
                        $fstr3="";
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
                                    $fstr1="<td><a href='../../upfolder/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='فایل اتوکد' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>فایل نقشه با موفقیت بارگذاری شد.</font></td>
                                    ";
                                
                                if (($ID==$ApplicantMasterID) && ($No==2) )
                                    $fstr2="<td><a href='../../upfolder/$file' ><img style = 'width:20px;' src='../img/accept.png' title='دفترچه طراحی' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>فایل دفترچه با موفقیت بارگذاری شد.</font></td>
                                    ";
                                
                                if (($ID==$ApplicantMasterID) && ($No==3) )
                                    $fstr3="
                                    <td><a href='../../upfolder/$file' ><img style = 'width: 20px;' src='../img/accept.png' title='دفترچه محاسبات' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>فایل محاسبات با موفقیت بارگذاری شد.</font></td>
                                    ";        
                                
                            }
                        }
                        
                        ///////////////////فایل قرارداد/////////////////////////
                        $contfilename="";
                        if ($DesignerCoID>0)
                            $contfilename="قرارداد";
                            else if ($issurat>0)
                                    $contfilename="تحویل دائم";
                                    else
                                        $contfilename="تحویل موقت";
                        $fstr4="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/contract/';
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
                                    $fstr4="<td><a href='../../upfolder/contract/$file' ><img style = 'width: 20px;' src='../img/accept.png' 
                                    title='اسکن' ></a></td>
                                    <td colspan='3'><font color='green' size='2'>اسکن $contfilename با موفقیت بارگذاری شد.</font></td>
                                    ";
                                }
                            }
                        }
                        //////////////////////////////////////////////////////
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
                     
                        
                     $permitrolsid = array("1", "5", "13", "14", "19", "27");
                     if (in_array($login_RolesID, $permitrolsid))
                     {
    					 $query="SELECT DesignSystemGroupsID AS _value, Title AS _key
                                FROM designsystemgroups
                                WHERE DesignSystemGroupsID <>4
                                UNION ALL SELECT -1 _value, 'قطره اي/ باراني' _key";
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td>سیستم آبیاری:</td>".
                         select_option('DesignSystemGroupsID','',',',$ID,0,'','','1','rtl',0,'',$DesignSystemGroupsID,'','120');
                            
                     }
					 
					 echo "</tr><tr>";
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
                            <td  class='data'><input readonly name='apps' type='checkbox' id='apps'  value='0'  /></td>";
                    
                    
                    $chked="";
                     if ($cappacityless==1)
						 {
							$chked="checked";
							print "
							<td colspan='1' class='label'>پروژه خارج از ظرفیت</td>
								<td  class='data'><input readonly name='cappacityless' type='checkbox' id='cappacityless'  value='1' $chked /></td>";
						 }
					  else print "
                        <td colspan='1' class='label'>پروژه خارج از ظرفیت </td>
                            <td  class='data'><input readonly name='cappacityless' type='checkbox' id='cappacityless'  value='0'  /></td>";
							
							print "
							<td colspan='2' class='label'>اسکن مچوزدار</td>
                             
                             <td colspan='2' class='data'><input type='file' name='filem' id='filem' accept='application/jpg'></td>
                             $fstrm
                            ";
							
							
                    echo "</tr><tr>";
					$permitrolsid = array("1","13","18");//تعدیل ضریب برنده پیشنهاد
                     if (in_array($login_RolesID, $permitrolsid) && ($DesignerCoID>0))
                     {
                        echo " 
                        <tr><td  colspan='12' >تعدیل ضریب پیمان:
                        <input   name='coef3' type='text' class='textbox' id='coef3' value='$coef3' size='2'  />
                        
                        <input   name='amidmaster' type='hidden' class='textbox' id='amidmaster' value='$amidmaster' size='2'  />
                        <input   name='coefold' type='hidden' class='textbox' id='coefold' value='$coef3' size='2'  />
                        <span id='span1'></span>
						توضیحات:<textarea id='coef3changedescription' name='coef3changedescription' rows='2'  cols='50' >$coef3changedescription</textarea></td></tr>
                        	
                        ";
                     }
                     
                    $permitrolsid = array("1","13","18","19","17");
                    	 
    					if (in_array($login_RolesID, $permitrolsid) && $DesignerCoID>0)
                         {
                          
    					 print "</tr><tr><td colspan='10'>--------------------------------------------------------------------------------------------------------------------------------------------</td>";
                         
                            echo " 
                             <tr>
                            <td colspan='2' class='label'>فایل&nbspنقشه&nbsp(با&nbspفرمت&nbspAutoCAD2007)</td>
                             
                             <td colspan='2' class='data'><input type='file' name='file1' id='file1' accept='application/zip'></td>
                             $fstr1
                             </tr>
                             
                             <tr>
                            <td colspan='2' class='label'>فایل&nbspدفترچه&nbsp(با&nbspفرمت&nbspOffice2007)</td>
                            
                            <td colspan='2' class='data'><input type='file' name='file2' id='file2' accept='application/zip'></td>
                            
                            $fstr2
                            </tr>
                             
                             <tr>
                            <td colspan='2' class='label'>فایل&nbspمحاسبات&nbsp(با&nbspفرمت&nbspOffice2007)</td>
                             <td colspan='2' class='data'><input type='file' name='file3' id='file3' accept='application/zip'></td>
                             
                             $fstr3
                             </tr>";
                         }
                         if ($login_RolesID!=17)
                         print " <tr>
                         <td>شماره نامه &nbsp$contfilename:</td>
                         <td><input  value='$contletterno' size='10' class='f52_font' name='contletterno' type='text' class='textbox' id='contletterno'    /></td>
                         <td>تاریخ:</td>
                         <td><input  value='$contletterdate'  size='10' class='f52_font' name='contletterdate' type='text' class='textbox' id='contletterdate'   /></td>
                        <td colspan='1' class='label'>اسکن (حداکثر 100 کیلوبایت)</td>
                        <td colspan='1' class='data'><input type='file' name='file4' id='file4' ></td>
                        $fstr4
                        </tr>";
					 }
					 
					 print "</tr><tr><td colspan='16'>--------------------------------------------------------------------------------------------------------------------------------------------</td><tr>";
                     
					
					 $permitrolsid = array("1", "5","19","6","7","16");
                     if (in_array($login_RolesID, $permitrolsid))
                     {
                         $query="select creditsourceID as _value,title as _key from creditsource 
                             where ostan=substring($soo,1,2)
							 ORDER BY sortorder Desc";
                           //print $query;
        				 $ID = get_key_value_from_query_into_array($query);
                         print "</tr><tr><td id='creditsourceIDlbl' colspan='1'>منبع تامین اعتبار:".
                         select_option('creditsourceID','',',',$ID,0,'',$disabled,'1','rtl','','',$creditsourceID,'','125');
                     
                     }
                     $permitrolsid = array("1", "5","19","6","16","7","13","14");
                     if (in_array($login_RolesID, $permitrolsid))
                     {
                        
                     
                         $query="SELECT YearID as _value,Value as _key FROM `year` 
                         ORDER BY year.Value DESC";
						 
        				 $ID = get_key_value_from_query_into_array($query);
                         print "<td id='YearIDlbl'  colspan='1'>سهمیه شهرستانی:".
                         select_option('YearID','',',',$ID,0,'','','1','rtl',0,'',$SelectedYearID,'');
                         
                         
                         print " 
                        <td id='creditsourceIDlbl'  >بلاعوض محاسباتی:</td>
                         <td class='data'><input  
                                style = 'border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:140%;font-family:'B Nazanin';width: 85px;'
                                name='sysbelaavaz' type='text' class='textbox' id='sysbelaavaz' value='$sysbelaavaz' size='10' 
                                maxlength='19' readonly /></td>
                        <td id='creditsourceIDlbl'  class='label'>بلاعوض تایید شده:</td>
                         <td class='data'><input  
                                style = 'border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:140%;font-family:'B Nazanin';width: 85px;'
                                name='belaavaz' type='text' class='textbox' id='belaavaz' $rbla value='$belaavaz' size='10' 
                                maxlength='19' /></td>
                              ";  
                          
                     }
                     
                     $permitrolsid = array("1", "16","6","7","19");
					 if ($login_RolesID==7) {$lbl='کد بانک(پروژه):';$titr1='سهم نقدی شریک(ریال):';$titr2='سهم بانک(ریال):';}
					 else
                     {$lbl='کد صندوق:';$titr1='خودیاری نقدی (ریال):';$titr2='خودیاری غیرنقدی (ریال):';}
					 
					 
                     if (in_array($login_RolesID, $permitrolsid))
                     {
                        echo "
                          <tr>
                            <td  colspan='3'>شماره نامه تاییدیه:
                      <input  value='$letterno'
                      class='f52_font'
                             name='letterno' type='text' class='textbox' id='letterno'    /></td>
                              <tr>
                              </tr>
                            
                            
                             <td  colspan='3'>تاریخ نامه تاییدیه:
                      <input  value='$letterdate'
                      class='f52_font'
                             name='letterdate' type='text' class='textbox' id='letterdate'    /></td>
                            
                            <tr>
                              </tr>
                            
                             <td  class='label'>$lbl</td>
                      <td ><input  value='$sandoghcode'
                      class='f52_font'
                             name='sandoghcode' type='text' class='textbox' id='sandoghcode'    /></td>
                              </tr>
                              
                        <tr>
                    
 					    <td  >تاریخ تصویب:
                        <input placeholder='انتخاب تاریخ'  name='TechDate' type='text' class='textbox' id='TechDate' 
                        value='$TechDate' size='10' maxlength='10' />
                        <span id='span1'></span>
                        </td>
                      
                        <td colspan='10'>
                        توضیحات:<textarea id='DescriptionTM' name='DescriptionTM' rows='2'  cols='89' >$DescriptionTM</textarea>
                        </td>
                        </tr> 
                        
                         <tr>
                         <td  id='creditsourceIDlbl'  colspan='15'>$titr1
                         <input name='selfcashhelpval' type='text' class='textbox' id='selfcashhelpval' value='$selfcashhelpval' onKeyUp=\"convert('selfcashhelpval')\" size='12' />
                         تاریخ:
                         <input placeholder='انتخاب تاریخ'  name='selfcashhelpdate' type='text' class='textbox' id='selfcashhelpdate' value='$selfcashhelpdate' size='10'/>
                         </tr>
                         
                         <tr>
                         <td   colspan='15'>$titr2 1
                         <input name='selfnotcashhelpval1' type='text' class='textbox' id='selfnotcashhelpval1' value='$selfnotcashhelpval1' onKeyUp=\"convert('selfnotcashhelpval1')\" size='12' />
                         تاریخ:
						<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate1' type='text' class='textbox' id='selfnotcashhelpdate1' value='$selfnotcashhelpdate1' size='10'/>
                        
                        </tr>
                        <tr>
                         <td    colspan='15'>$titr2 2
                         <input name='selfnotcashhelpval2' type='text' class='textbox' id='selfnotcashhelpval2' value='$selfnotcashhelpval2' onKeyUp=\"convert('selfnotcashhelpval2')\" size='12' />
                         تاریخ:
						<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate2' type='text' class='textbox' id='selfnotcashhelpdate2' value='$selfnotcashhelpdate2' size='10'/>
                        
                        </tr>
                        <tr>
                         <td    colspan='15'>$titr2 3
                         <input name='selfnotcashhelpval3' type='text' class='textbox' id='selfnotcashhelpval3' value='$selfnotcashhelpval3' onKeyUp=\"convert('selfnotcashhelpval3')\" size='12' />
                         تاریخ:
						<input placeholder='انتخاب تاریخ'  name='selfnotcashhelpdate3' type='text' class='textbox' id='selfnotcashhelpdate3' value='$selfnotcashhelpdate3' size='10'/>
                        
                        </tr>
                        
                        
                        <tr>
                        <td colspan='10'>
                        &nbsp;&nbsp;توضیحات:
                         <textarea id='selfcashhelpdescription' name='selfcashhelpdescription' rows='2'  cols='50' >$selfcashhelpdescription</textarea></td>
                        </td>
                        </tr>
                        
                        <tr>
                        <td colspan='10'>
                        <a  target='_blank' href=../insert/appfarmerbring_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".rand(10000,99999)."'>
                             مبلغ برآورد کالا و خدمات متقاضی (آورده متقاضی) </a></td>
                        </td>
                        </tr>
                         
                         
                             
                        ";
                     }
				 print "</tr><tr><td colspan='16'>--------------------------------------------------------------------------------------------------------------------------------------------</td><tr>";
          			 
                    $permitrolsid = array("1", "13", "14","19");
                     if (in_array($login_RolesID, $permitrolsid) || ($prjtypeid==1 && $login_RolesID==17) )
                     {
                        $query="select DesignerCoID as _value,Title as _key from designerco where isnazer=1 ORDER BY _key";
                            $ID = get_key_value_from_query_into_array($query);
                            print "<td  class='label'>مشاور ناظر:</td>".
                            select_option('nazerID','',',',$ID,0,'','','1','rtl','','',$nazerID,'','125');
                            
                            
                            
                            $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=29 and  substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)";
                            $result = mysql_query($query);
                             $allclerkID[' ']=' ';
                             while($row = mysql_fetch_assoc($result))
                                if (decrypt($row['DVFS'])<>'ج')
                                $allclerkID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                             $allclerkID=mykeyvalsort($allclerkID);
                             
                            
                            print "<td  class='label'>بازرس کنترل کیفیت:</td>".
                            select_option('bazrasID','',',',$allclerkID,0,'','','1','rtl','','',$bazrasID,'','125');
                            
                            if ($prjtypeid==1)
                            {
                                $queryselect='select operatorcoID as _value,Title as _key from operatorco  order by _key  COLLATE utf8_persian_ci';
                                $result = mysql_query($queryselect);
                                $IDselect[' ']=' ';
        	                    while($row = mysql_fetch_assoc($result))
                                {
                                    $IDselect[$row['_key']]=$row['_value'];
                                }
                                echo select_option('operatorcoIDbandp','شرکت&nbspمجری',',',$IDselect,0,'','','2','rtl',0,'',$operatorcoIDbandp,'','125');
                            }
                     }
                     if ($DesignerCoID>0)
                     {
                        $permitrolsid = array("1", "5", "9", "10", "20","19");
                        if (in_array($login_RolesID, $permitrolsid) || ($prjtypeid==1 && $login_RolesID==17))
                        {
                            //if ($login_designerCO==1)
                            //    $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11";
                            //else
                                $query="SELECT clerkID,clerk.CPI,DVFS  FROM clerk where city=11 and  substring(clerk.cityid,1,2)=substring('$login_CityId',1,2)";
                            $result = mysql_query($query);
                             $allclerkID[' ']=' ';
                             while($row = mysql_fetch_assoc($result))
                                if (decrypt($row['DVFS'])<>'ج')
                                $allclerkID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                             $allclerkID=mykeyvalsort($allclerkID);
                             
                             
                            print "<td  class='label'>بازبین:</td>".
                            select_option('DesignerCoIDnazer','',',',$allclerkID,0,'','','1','rtl','','',$DesignerCoIDnazer,'','125');
                        }
                    }
                        
						
						
                    print "</tr><tr>";
                    $permitrolsid = array("1","5","19","31");
                     if (in_array($login_RolesID, $permitrolsid) || ($prjtypeid==1 && $login_RolesID==17))
                       { 
                            
                            if ($operatorcoID>0)
							$query="select designerID as _value,CONCAT(LName,' ',FName) as _key from designer
                            where operatorcoid='$operatorcoID'
                             ORDER BY LName";
                             else
                            $query="select designerID as _value,CONCAT(LName,' ',FName) as _key from designer
                            where DesignerCoID='$DesignerCoID'
                             ORDER BY LName";
                             
                            $ID = get_key_value_from_query_into_array($query);
							print "<td id='DesignerIDlbl'  class='label'>طراح:</td>".
                            select_option('DesignerID','',',',$ID,0,'','','1','rtl','','',$DesignerID,'','125')."";
                            
                            
                            
                            if ($DesignerCoID>0 && $applicantstatesID!=23) 
                            {
                                
                                $sqlcont= sqlcont($login_CityId);
                                $query="	
                                    select designerco.DesignerCoID ,designerco.Title from designerco
                                    inner join designercocontract on designercocontract.DesignerCoID=designerco.DesignerCoID 
                                    and designercocontract.contracttypeID='4' and designercocontract.prjtypeid='0'
                                    left outer join ($sqlcont) contractprogress 
                                    on contractprogress.designercocontractID=designercocontract.designercocontractID
                                    where designercocontract.area>=ifnull(contractprogress.cocontractprogressDesignArea,0)
                                    ";
                                   // print $query;
                                
                                $query="select 
                                case permit.DesignerCoID>0 when 1 then designerco.DesignerCoID else concat('99999',designerco.DesignerCoID) end
                                 as _value,
                                case permit.DesignerCoID>0 when 1 then designerco.Title else concat('(فاقد ظرفیت)',designerco.Title) end
                                 as _key from designerco 
                                left outer join ($query) permit on permit.DesignerCoID=designerco.DesignerCoID
                                
                                ORDER BY _key";   
                                     // print $query;                
                                $ID = get_key_value_from_query_into_array($query);
    							print "<td id='DesignerCoIDchangelbl'  class='label'>شرکت مشاور طراح دارای ظرفیت:</td>".
                                select_option('DesignerCoIDchange','',',',$ID,0,'','','1','rtl','','',$DesignerCoID,'','125')."";
                            
                            }
                            else echo "<td id='DesignerCoIDchangelbl' colsan=2 class='label'>
                            <font color='red'>
                            جهت ثبت مشاور طراح، طرح از م ج شهرستان ارجاع نشده است
                            </font></td>";
    
							if ($proposestate>0 && $login_RolesID==1)
							print "
                            <td >کدپیشنهاد:</td>
                            <td class='data'><input value='$proposestate' style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 75px\"
                            name='proposestate' type='text' class='textbox' id='proposestate'    /></td></tr><tr>";
							
							$query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' order by _key  COLLATE utf8_persian_ci";
            				$ID1 = get_key_value_from_query_into_array($query);
                            $query="
                            select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($soo,1,2)
                            and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
            				$ID2 = get_key_value_from_query_into_array($query);
                            $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring('$sob',1,4)
                            and substring(id,6,2)='00' order by _key  COLLATE utf8_persian_ci ";
            				$ID3 = get_key_value_from_query_into_array($query);
                            print select_option('soo','استان:',',',$ID1,0,'','disabled','1','rtl',0,'',$soo,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'135').
                            select_option('sos','دشت/شهرستان:',',',$ID2,0,'','disabled','1','rtl',0,'',$sos,"onchange = \"FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\"",'75').
                            select_option('sob','شهر/بخش:',',',$ID3,0,'','disabled','1','rtl',0,'',$sob,'','75').
                            $limited = array("9");
                            if ( in_array($login_RolesID, $limited))
        					$query="SELECT CostPriceListMasterID as _value,
                                     year.Value as _key FROM `costpricelistmaster` 
                                     inner join year on year.YearID=costpricelistmaster.YearID
                                     inner join month on month.MonthID=costpricelistmaster.MonthID
                                     where pfd=1
                                     ORDER BY year.Value DESC ,month.Code DESC ";
                            else $query="SELECT CostPriceListMasterID as _value,
                                     year.Value as _key FROM `costpricelistmaster` 
                                     inner join year on year.YearID=costpricelistmaster.YearID
                                     inner join month on month.MonthID=costpricelistmaster.MonthID
									 where pfo=1
                                     ORDER BY year.Value DESC ,month.Code DESC ";
            				$ID = get_key_value_from_query_into_array($query);
                            print "<tr >
                            <td id='CostPriceListMasterIDlbl'  >فهرست بها:</td>".
                            select_option('CostPriceListMasterID','',',',$ID,0,'','disabled','1','rtl',0,'',$CostPriceListMasterID,'','60');
        					$limited = array("9");
                           
                            $query="select TransportCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from transportcosttablemaster
                                    inner join year on year.YearID=transportcosttablemaster.YearID
                                     inner join month on month.MonthID=transportcosttablemaster.MonthID
                                     where pfd=1 and ostan='$login_ostanId' ORDER BY year.Value DESC ,month.Code DESC";
            				$ID = get_key_value_from_query_into_array($query);
                            print "<td id='TransportCostTableMasterIDlbl' class='label'>جدول هزینه حمل:</td>".
                            select_option('TransportCostTableMasterID','',',',$ID,0,'','disabled','1','rtl',0,'',$TransportCostTableMasterID,'','75');
                            
							
							
							$query="select RainDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from raindesigncosttablemaster
                                    inner join year on year.YearID=raindesigncosttablemaster.YearID
                                     inner join month on month.MonthID=raindesigncosttablemaster.MonthID
                                     where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
            				$ID = get_key_value_from_query_into_array($query);
                            print "<td id='RainDesignCostTableMasterIDlbl' colspan='2'>جدول حق الزحمه طراحی بارانی:</td>".
                            select_option('RainDesignCostTableMasterID','',',',$ID,0,'','disabled','1','rtl',0,'',$RainDesignCostTableMasterID,'','75')   ;
                            $query="select DropDesignCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from dropdesigncosttablemaster
                                    inner join year on year.YearID=dropdesigncosttablemaster.YearID
                                     inner join month on month.MonthID=dropdesigncosttablemaster.MonthID
                                     where pfd=1 ORDER BY year.Value DESC ,month.Code DESC";
            				$ID = get_key_value_from_query_into_array($query);
                            print "</tr><tr ><td id='DropDesignCostTableMasterIDlbl'  colspan='3'>جدول حق الزحمه طراحی قطره ای/تلفیقی:</td>".
                            select_option('DropDesignCostTableMasterID','',',',$ID,0,'','disabled','1','rtl',0,'',$DropDesignCostTableMasterID,'','75')."
                            <td colspan='1' class='label'>بایگانی</td>
                            <td  class='data'><input readonly name='private' type='checkbox' id='private'  value='1' $private /></td>";
                            $query='select MonthID as _value,Title as _key from month';
            				$ID = get_key_value_from_query_into_array($query);
                            print select_option('MonthID','',',',$ID,0,'','','1','rtl',0,'',$SelectedMonthID,'','','hidden');
					}
					
					if (in_array($login_RolesID, $permitrolsid) ||  $login_RolesID==11)
                    { 
                 		
                            print "<td colspan='1' class='label'>تجمیع</td>
                            <td  class='data'><input  name='criditType' type='checkbox' id='criditType'  value='1' $criditType /></td>";
                    }
                     
                    $permitrolsid = array("1","13","14");//
                     if (in_array($login_RolesID, $permitrolsid) && !($DesignerCoID>0))
                     {

						 $hazf='';
						 if ($statenom<>1100) {$temporarydissdate='';$Descriptiontemporarydissdate='';}
							else {$hazf="<td><a 
								href=\"dissdatedetail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_2_".$DesignerCoID."_".$operatorcoID."_".$DesignArea."_".$login_ostanId.rand(10000,99999)."\"
								onClick=\"return confirm('مطمئن هستید حذف شود ؟');\"
								> <img style = 'width: 25px;' src='../img/delete.png' title='حذف انصراف از اجرا'> </a></td><td colspan='1'></td></tr>";
								}
					 
				        if (!$temporarydeliverydate)
								echo "<tr><td  colspan='5'>تاریخ انصراف از اجرا:
								<input placeholder='انتخاب تاریخ'  name='temporarydissdate' type='text' class='textbox' id='temporarydissdate' value='$temporarydissdate' size='10' maxlength='10' />
								<span id='span1'></span> توضیحات:<textarea id='Descriptiontemporarydissdate' name='Descriptiontemporarydissdate' rows='2'  cols='50' >$Descriptiontemporarydissdate</textarea></td>
								".$hazf;
					
                     }                     
					 
					 $loginexists='';
					 if ($login_RolesID<>1) $loginexists="and exists (select * from appstatesee where applicantstatesID='$applicantstatesID' and RolesID  in ($permitrolsidforselectproposablevals))";
                     if (in_array($login_RolesID, $permitrolsidforselectproposable))
                     if ($operatorcoID>0 && $issurat!=1)
                     {
                        $sql = "SELECT invoicemaster.proposable,invoicemaster.Title,invoicemaster.InvoiceMasterID from invoicemaster 
                        inner join producers on producers.producersid=invoicemaster.producersid and PipeProducer=1  and ifnull(pricenotinrep,0)=0
                        and invoicemaster.InvoiceMasterID in (select InvoiceMasterID from invoicedetail)
                        where invoicemaster.ApplicantMasterID='$ApplicantMasterID'
						$loginexists;";
                        //print $sql; exit;
                        $result = mysql_query($sql);
                         echo "</tr><tr><td colspan='10'><font color='blue' size='3.5' face='B Nazanin'> لیست پیش فاکتورهای قابل پیشنهاد قیمت (وضعیت پیش فاکتور: مشاور ناظر به ناظر عالی):</font>";
                        while($row = mysql_fetch_assoc($result))
                        {
                            
                            $ID = $ApplicantMasterID."_11_0_0_".$applicantstatesID."_1_1_$row[InvoiceMasterID]_";
                        
                            $proposable= $row['proposable'];
                            if ($proposable>0)      
                                $proposable="checked";
							if ($login_RolesID==17 && $DesignArea>10.99)	$typechx='hidden'; else $typechx='checkbox';
								
                            echo "</tr><tr>
                            <td  class='data'><input readonly name='invoice$row[InvoiceMasterID]' type='$typechx' id='invoice$row[InvoiceMasterID]'  value='1' $proposable /></td>
                            <td colspan='2' >لیست $row[Title]
						    ";
							echo "
							<a  target='_blank' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/full_page.png' title=' لوله ها '></a></td>";   
                        } 
                     }



                   
                     
                     
                     echo "
                     </tr>
                     </tbody>
                    <tfoot>
                      <td colspan='1'><input name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                      <td class='data'><input name='ids' type='hidden' class='textbox' id='ids'  value='$ids'  /></td>
                      <td class='data'><input name='prjtypeid' type='hidden' class='textbox' id='prjtypeid'  value='$prjtypeid'  /></td>
                      <td class='data'><input name='issurat' type='hidden' class='textbox' id='issurat'  value='$issurat'  /></td>
                      <td class='data'><input name='applicantstatesID' type='hidden' class='textbox' id='applicantstatesID'  value='$applicantstatesID'  /></td>
                      <td class='data'><input name='ApplicantMasterIDmaster' type='hidden' class='textbox' id='ApplicantMasterIDmaster'  value='$ApplicantMasterIDmaster'  /></td>
                     </tr>
                     </tfoot>";

                    $oldval="
                    
                     <tr><td class='label'>تاریخ تایید کمیته فنی:</td>
                      <td class='data'><input placeholder='انتخاب تاریخ' name='ADate' type='text' class='textbox' id='ADate' 
                      value='$ADate' size='10' maxlength='10' /></td>
                     <span id='span2'></span>
                       <td  class='label'>توضیحات:</td><td colspan='5'><textarea id='DescriptionTM' name='DescriptionTM' rows='2'  cols='89' >$DescriptionTM</textarea></td>
                      ";


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
</html