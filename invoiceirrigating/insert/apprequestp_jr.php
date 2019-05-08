<?php 

/*

insert/apprequestp_jr.php
فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/apprequestp.php
*/

include('../includes/connect.php'); ?>
<?php  include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');
  
  
  
  
  
	$selectedBankcode=trim($_POST['selectedBankcode']);//کد رهگیری
	
	$selectedlogin_ProducersID=$_POST['selectedlogin_ProducersID'];//تولید کننده
	
    
    
    
    if (!($selectedlogin_ProducersID>0 ))
        $temp_array = array('error' => '1'
            ,'errors' => '');
    else
    {
        /*
        producerapprequest جدول پیشنهاد قیمت لوله
        applicantmaster جدول مشخصات طرح
        ApplicantMasterID شناسه طرح
        producersID شناسه تولید کننده
        Bankcode کد رهگیری
        */
        $query = "
        select count(*) cnt from producerapprequest 
        inner join applicantmaster on applicantmaster.applicantmasterid=producerapprequest.applicantmasterid
        where producerapprequest.ProducersID='$selectedlogin_ProducersID' and TRIM(Bankcode)='$selectedBankcode'";
       
				      		try 
								  {		
									       $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
		
        $row = mysql_fetch_assoc($result); 		
        if ($row['cnt']>0)
        {
             $temp_array = array('error' => '2'
            ,'errors' => '');
        }
        else
        {
            
          /*
        producerapprequest جدول پیشنهاد قیمت لوله
        applicantmaster جدول مشخصات طرح
        invoicemaster جدول پیش فاکتورها
        ApplicantMasterID شناسه طرح
        producersID شناسه تولید کننده
        Bankcode کد رهگیری
        proposable شروع پیشنهاد
        proposestatep وضعیت پیشنهاد
        */          
            $query = "select count(*) cnt from applicantmaster
            inner join (select distinct applicantmasterid applicantmasteridallproposable from invoicemaster where proposable=1) allproposable on 
            allproposable.applicantmasteridallproposable=applicantmaster.applicantmasterid
            where TRIM(Bankcode)='$selectedBankcode' and ifnull(proposestatep,0)>0";
 			      		try 
								  {		
									       $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
           $row = mysql_fetch_assoc($result); 		
            if ($row['cnt']>0)
            {
                 $temp_array = array('error' => '3'
                ,'errors' => '');
            }
            else 
            {
          /*
        producerapprequest جدول پیشنهاد قیمت لوله
        applicantmaster جدول مشخصات طرح
        invoicemaster جدول پیش فاکتورها
        ApplicantMasterID شناسه طرح
        producersID شناسه تولید کننده
        Bankcode کد رهگیری
        proposable شروع پیشنهاد
        proposestatep وضعیت پیشنهاد
        */                  
                $query = "select count(*) cnt from applicantmaster
            inner join (select distinct applicantmasterid applicantmasteridallproposable from invoicemaster where proposable=1) allproposable on 
            allproposable.applicantmasteridallproposable=applicantmaster.applicantmasterid
            where TRIM(Bankcode)='$selectedBankcode' and ifnull(proposestatep,0)=0";
 			      		try 
								  {		
									       $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }
                $row = mysql_fetch_assoc($result); 
                if (!($row['cnt']>0))
                {
                     $temp_array = array('error' => '4'
                    ,'errors' => '');
                }            
                else
                {        
                  
					
					$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);  //تابع دریافت اطلاعات پیکربندی  				
				   
                    $currentdatefrom=jalali_to_gregorian(substr(gregorian_to_jalali(date('Y-m-d')),0,4)."/01/01");  
                    $currentdateto=jalali_to_gregorian(substr(gregorian_to_jalali(date('Y-m-d')),0,4)."/12/29");  
                    /*
                    producerapprequest جدول پیشنهادات قیمت
                    state وضعیت انتخابی
                    producers جدول مشخصات تولیدکنندگان
                    producers.rank رتبه تولید کننده
                    producers.Title عنوان تولید کننده
                    producers.CompanyAddress آدرس تولید کننده
                    SaveDate تاریخ
                    validday اعتبار پیشنهاد فیمت اعلامی
                    producerapprequestID شناسه جدول پیشنهاد قیمت
                    boardvalidationdate اعتبار تاریخ هیئت مدیره
                    copermisionvalidate تاریخ اعتبار مجوز شرکت
                    joinyear تاریخ تاسیس شرکت
                    errors پیغام های عدم صلایت کاربر
                    PE32 مبلغ  پیشنهادی برای لوله های 32
                    PE40 مبلغ  پیشنهادی   برای لوله های 40
                    PE80 مبلغ  پیشنهادی   برای لوله های 80
                    PE100 مبلغ  پیشنهادی   برای لوله های 100
                    PE32app مبلغ تایید شده برای لوله های 32
                    PE40app مبلغ تایید شده برای لوله های 40
                    PE80app مبلغ تایید شده برای لوله های 80
                    PE100app مبلغ تایید شده برای لوله های 100
                    prjtype.title عنوان نوع پروژه
                    producers.guaranteepayval مبلغ ضمانت نامه شرکت
                    producers.guaranteeExpireDate تاریخ اعتبار ضمانت نامه بانکی
                    applicantmasterdetail جدول ارتباطی  طرح ها
                    ApplicantMasterID شناسه مطالعات
                    ApplicantMasterIDmaster شناسه طر اجرایی
                    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
                    prjtype جدول انواع پروژه ها
                    applicantmaster جدول مشخصات طرح
                    creditsource جدول منابع اعتباری
                    creditsourcetitle عنوان منبع اعتباری
                    tanzilpipe نرخ تنزیل قیمت 
                    designer جدول مشخصات طراحان
                    shahr جدول شهرها
                
                */    
                    $sql = "SELECT distinct applicantmaster.*,CONCAT(designer.LName,' ',designer.FName) designername ,shahr.cityname shahrcityname
                ,designsystemgroups.title designsystemgroupstitle,producers.Title producercoTitle
                ,boardvalidationdate,prjtype.prjtypeid,
                yearcost.Value fb,copermisionvalidate,joinyear,yearcost.Value fb
                
                FROM applicantmaster 
            
                inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
                and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
                inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
                and substring(shahr.id,3,5)<>'00000'
                left outer join designer on designer.designerid=applicantmaster.designerid
                left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'ÞØÑå Çí/ ÈÇÑÇäí' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
                inner join producers on producers.ProducersID='$selectedlogin_ProducersID' 
                
                left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
                left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 
        
                inner join (select distinct applicantmasterid applicantmasteridallproposable from invoicemaster where proposable=1) allproposable on 
                allproposable.applicantmasteridallproposable=applicantmaster.applicantmasterid
                
				inner join applicantmasterdetail on applicantmaster.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
				
                left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
				
                where TRIM(applicantmaster.Bankcode)='$selectedBankcode'
                and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and  ifnull(applicantmaster.isbandp,0)=0";
                    
                    
						   		try 
								  {		
									       $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'ÇÌÑÇí ÑÓ æ Ìæ ÈÇ ÎØÇ ãæÇÌå ÔÏ: ' .$e->getMessage();
								  }

                    $resquery = mysql_fetch_assoc($result);
                    if ($resquery["DesignArea"]>0)
                    {                
                        $ApplicantName = $resquery["ApplicantName"];
                    	$DesignArea = $resquery["DesignArea"];
                        $designsystemgroupstitle= $resquery["designsystemgroupstitle"];  
                        $shahrcityname = $resquery["shahrcityname"];
                        $designername = $resquery["designername"];
                        $ApplicantMasterID = $resquery["ApplicantMasterID"];
						$prjtypeid = $resquery["prjtypeid"];
						$Datebandp = $resquery["Datebandp"];
					    $retf=rettotalsumtarh($ApplicantMasterID,$selectedlogin_ProducersID);
                        $linearray = explode('_',$retf);
                        $eval= ($linearray[13]) ; 
                        $totalpe32num=$linearray[9];
                        $totalpe40num=$linearray[10];
                        $totalpe80num=$linearray[11];
                        $totalpe100num=$linearray[12];
                                
                                
                                        
                        $temp_array = array(
                        'error' => '0'
                        ,'ApplicantName' => $ApplicantName
                        ,'shahrcityname' => $shahrcityname  
                        ,'ApplicantMasterID' => $ApplicantMasterID
                        ,'eval' => $eval
                        ,'totalpe32num' => $totalpe32num
                        ,'totalpe40num' => $totalpe40num
                        ,'totalpe80num' => $totalpe80num
                        ,'totalpe100num' => $totalpe100num
						,'prjtypeid' => $prjtypeid
						,'Datebandp' => $Datebandp);   
                    }
                    else
                    {
                         $temp_array = array('error' => '5'
                        ,'errors' => '');
                        
                    }    
                }
            }                
        }
    }    
	 
    
    

//$temp_array = array('error' => '5','errors' => '');
        echo json_encode($temp_array);
		exit();
    			
	
   
   
   
			
			
		
	

?>



