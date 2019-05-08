<?php

require ('functions.php'); 


  //این تابع با دریافت یک سری ویژگی های المنت اینپوت آرا ایجاد می نماید
  /*
  $name نام المنت
  $lable لیبل کنار المنت اینپوت
  $accesskey کلید شورتکات المنت که با آن کلید فوکوس روی آن المنت می شود
  $kind نوع المنت اینپوت مثلا فقط عدد باشد یا زمان باشد و ... که در ادامه توضیح کامل داده شده است
  $tabindex ایندکس تب
  $maxlength طول حداکثر اینپوت
  $size اندازه اینپوت
  $onblur ایونت خروج از المنت
  $disabled_str اینکه المنت غیر فعال شود
  $colspan تعداد ستون های  اشغالی اینپوت در صفحه
  $type نوع المنت اینپوت که تکست چکباکس و... باشد
  $dir جهت المنت که از راست به چپ یا از چپ به راست باشد
  $access_etitle شورتکات دسترسی به المنت
  $width عرض المنت با واحد پیکسل
  $Originalonblure ایونت خروج از المنت برای نوع تاریخ
  */
  function input($name,$lable='',$accesskey='',$kind='number,readonly,persian,date,zero_mask,goodscode,accountcode,time,latindate',
  $tabindex=0,$maxlength=10,$size=10,$onblur='return true;',$disabled_str='',$colspan=1,$type='text',$dir='rtl',$access_etitle='',
  $width=0,$Originalonblure='return true;')
  {
    switch ($kind)//بر اساس نوع المنت عملیات خاصی انجام می شود
    {
	    case 'number'://اگر نوع نامبر باشد موقع فشردن کلید عدد بودن آن چک می شود
        $force_events="onkeypress='return onlynumber(this,event)' onchange=\"$onblur\" ";
	      break;
	    case 'persian':// در صورت فارسی بودن نوع ایونت خروج از المنت $onblur اجرا می شود
        $force_events=" onchange=\"$onblur\" ";
	      break;
	    case 'readonly'://در صورتی که نوع فقط خواندنی باشد امکان دریافت هیچ کلیدی وجود
	      $force_events="onkeypress='return nonekey(this,event)' onchange=\"$onblur\"";
	      break;
	    case 'zero_mask'://در صورتی که عدد باشد و پیش فرض مسک صفر خواسته باشند
	      $force_events="onkeypress='return onlynumber(this,event)' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'goodscode'://در صورتی که مسک کالا که شامل کد سه بخشی می شود و  با کاراکتر ویرگول جدا شده باشند
	      $force_events="onkeypress='return goodscode(this,event);' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'accountcode'://در صورتی که کد سه سطحی حسابداری باشد
	      $force_events="onkeypress='return accountcode(this,event);' onchange=\"msktostr(this);$onblur\"";
	      break;
	    case 'date'://در صورتی که تاریخ باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"$onblur\" ; onblur=\"$Originalonblure\"  ";
	      break;
	    case 'time'://در صورتی که نوع زمان باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return timemask(this,event);' onchange=\"$onblur\" ";
	      break;
	    case 'latindate'://در صورتی که نوع تاریخ میلادی باشد
//	      $force_events="onkeypress='return persiandate(this,event);' onchange=\"msktostr(this);$onblur\"";
	      $force_events="onkeypress='return onlylatindate(this,event);' onchange=\"$onblur\" ";
	      break;
    	default ://در صورتی که نوع اینپوت مشخص نباشد فقط به المنت ایونت افزوده می شود
        $force_events="onchange=\"$onblur\"";
	      break;
    }
    //-------------------------------------------------------------
		$result='';//رشته خروجی
    //-------------------------------------------------------------
    //-------------------------------------------------------------

    	if ($width>0)//در صورتی که سایز پیکسلی ارسال شده باشد به اتریبیوت های المنت افزوده می شود
	$stylestr=" style='width:$width"."px'"; else $stylestr='';

	if ($size>0)//در صورتی که سایز عددی ارسال شده باشد به اتریبیوت های المنت افزوده می شود
	$sizeStr=" size='$size'"; else $sizeStr='';

    //-------------------------------------------------------------
    if (strtolower($type)=='checkbox')//در صورتی که نوع اینپوت چکباکس باشد 
    {
      if ($lable!='')//در صورتی که لیبل چک باکس ارسال شده باشد یک ستون برای لیبل آن اختصاص داده می شود
      {
        $colspan++;//تعداد ستون های المنت اینپوت

      }
     $result.="<td colspan='$colspan'>";//افزودن اتریبیوت تعداد ستون های المنت
     
     //تخصیص اتریبیوت های ارسالی به تابع  به رشته خروجی
      $result=$result."<input class='no_print'   type='$type' name='$name' id='$name' $sizeStr $stylestr maxlength='$maxlength' dir='$dir' $force_events onFocus='this.select();' tabindex=\"$tabindex\" value='1' ";

      
      if ($lable!='' and $type!='hidden')//در صورتی که نوع اینپوت پنهان نبود و دارای لیبل بود یک المنت لیبل به رشته خروجی افزوده می شود
        $result=$result.">&nbsp;<label   for=$name accesskey=$accesskey>$lable</label>";
    }
    else//در صورتی که اینپوت چکباکس نباشد
    {
      if ($lable!='')//
      {//در صورتی که نوع اینپوت پنهان نبود و دارای لیبل بود یک المنت لیبل به رشته خروجی افزوده می شود
        $result=$result."<td>";
        if ($type!='hidden')
          $result=$result."<label   for=$name accesskey=$accesskey>$lable</label>";
        $result=$result."</td>";
      }

      $result.="<td colspan='$colspan'>";//افزودن اتریبیوت تعداد ستون های المنت

        //تخصیص اتریبیوت های ارسالی به تابع  به رشته خروجی
	  	$result.="<div id='div$name' ><input    $disabled_str type='$type' 
          name='$name' id='$name'  $sizeStr $stylestr maxlength='$maxlength' dir='$dir' $force_events
           onFocus=\"this.select();\" tabindex='$tabindex' value=\"$_POST[$name]\" >";

	}
    //بستن تگ های رشته خروجی
    $result=$result."</div></td>";
    //-------------------------------------------------------------

    return $result;
  }

  //تابع افزودن یک شهر به جدول شهر ها tax_tbcity7digit
  //$stateid شناسه شهرستان 
  //$inputtitle عنوان
  function addcity($stateid,$inputtitle)
  {
    //ساختار شهر به این ترتیب است که دو رقم اول شناسه معرف استان است
    // دو رقم بعدی کد شهرستان آن استان می باشد
    // سه رقم بعدی شهر یا بخش را مشخص می کند
    // در کوئری زیر می خواهیم بزرگترین کد سه رقمی استفاده شده در شهرستان مورد نظر را بیابیم
    $query="select distinct substring(id,5,3) id from tax_tbcity7digit where substring(id,1,4)='$stateid' and substring(id,6,2)='00' order by id";
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
        
    
    
    $selectedid=0;//یک کد کوچکتر از 1000 برای تخصیص به بخش جدید
    $selectedidstr="000";//رشته کد بخش جدید
    $preid="000";//متغیر اشاره گر قبلی
    $curid="000";//متغیر اشاره گر بعدی
    $inserted=0;//متغیری که نشان می دهد درج انجام شده یا خیر
    $row = mysql_fetch_assoc($result);
    if ($row)//در صورتی که برای شهرستان مورد نظر قبلا شهری ثبت شده باشد
    {
        while($row)
        {
            if ($inserted==0)//در صورتی که هنوز درج انجام نشده باشد
            {
                //در این قسمت می خواهیم شناسه سه رقمی خالی را بیابیم  و جایابی کرده و به شهر جدید اختصاص دهیم
                $preid=$curid;//اشاره گر قبلی برابر اشاره گر پیمایش شده تا کنون می شود
                $curid = $row['id'];//اشاره گر فعلی برابر شناسه سه رقمی بخش فعلی می شود
                $selectedid++;//متغیر شناسه شهر پیشنهادی افزایش می یابد
                
                //در این بخش شناسه شهر پیشنهادی سه کاراکتره می شود
                if ($selectedid<10) $selectedidstr='00'.$selectedid;
                    else if ($selectedid<100) $selectedidstr='0'.$selectedid;
                            else $selectedidstr=$selectedid;
                            
                //در صورتی که شناسه شهر پیشنهادی معتبر بود و از اشاره گر قبلی بزرگتر و از اشاره گر فعلی کوچکتر باشد
                //شناسه قابل استفاده بوده و بخش جدید با این شنایه درج می شود
                if (($selectedidstr>$preid) && ($selectedidstr<$curid) && ($selectedid<1000) )
                {
                    $SaveTime=date('Y-m-d H:i:s');//زمان
                    $SaveDate=date('Y-m-d');//تاریخ
                    $ClerkID=$login_userid;//شناسه کاربر
                    $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                    '".$stateid.$selectedidstr."','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
                    try 
                    {		
                        mysql_query($query); 
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    
                     //print    $query;
                    // exit;
                    $inserted=1;
                    break;
                }                
            }
            $row = mysql_fetch_assoc($result);
        }
        if ($inserted==0)//در صورتی که هنوز درج انجام نشده باشد
            {
                //درصورتی که حلقه بالا کد خالی پیدا نکند بعد از آخرین کد درج می شود
                $selectedid++;//متغیر شناسه شهر پیشنهادی افزایش می یابد
                
                
                //در این بخش شناسه شهر پیشنهادی سه کاراکتره می شود
                if ($selectedid<10) $selectedidstr='00'.$selectedid;
                    else if ($selectedid<100) $selectedidstr='0'.$selectedid;
                            else $selectedidstr=$selectedid;
                
                if ($selectedid<1000)//در صورتی که شناسه بخش سه رقمی بود درج انجام می شود
                {
                    $SaveTime=date('Y-m-d H:i:s');//زمان
                    $SaveDate=date('Y-m-d');//تاریخ
                    $ClerkID=$login_userid;//شناسه کاربر
                    $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                    '".$stateid.$selectedidstr."','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
                     
                    
                    try 
                    {		
                        mysql_query($query); 
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    
                     //print    $query;
                    // exit;
                    $inserted=1;
                }                
            }
            
    }
    else//درصورتی که شهرستان مورد نظر هنوز شهر یا بخشی برای آن قبلا ثبت نشده باشد با شناسه یک ثبت می شود
    {
        $SaveTime=date('Y-m-d H:i:s');//زمان
        $SaveDate=date('Y-m-d');//تاریخ
        $ClerkID=$login_userid;//شناسه کاربر
        $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                '".$stateid."001','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
        
        try 
        {		
            mysql_query($query); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
    }
    
  }

/////////////////////////sms////////////////////////////
//تابع ارسال پیامک
//$recipient شماره تلفن گیرنده
//$message متن پیامک
function SendSMS($recipient,$message)
{
    //ارسال پیامک از طریق کامپوننت
    //ActiveXperts
    //انجام می شود
    //در ابتدا  یک آبجکت ایجاد می شود
    $_objSmsProtocolGsm = new Com("ActiveXperts.SmsProtocolGsm" , NULL, CP_UTF8 );    
    //create the nessecairy com objects
    //آبجکت پیغام ایجاد می شود
    $objMessage   = new Com ("ActiveXperts.SmsMessage" , NULL, CP_UTF8 );
    //آبجکت ثوابت ایجاد می شود
    $objConstants = new Com ("ActiveXperts.SmsConstants" , NULL, CP_UTF8 );   
     //تعداد دستگاه های جی اس ام خوانده می شود
	$intDevices = $_objSmsProtocolGsm->GetDeviceCount();    
    //در صورتی که تعداد دستگاه های جی اس ام یکی بود دستگاه خوانده شده والا از تابع خارج می شود
	if ($intDevices==1) $device= $_objSmsProtocolGsm->GetDevice(0);
    else return ;
    
    //پین کد سیمکارت
    $pincode= 3740;       
		//اختصاص لاگ فایل برای ثبت پیغام ها
		$_objSmsProtocolGsm->Logfile = "C:\SMSMMSToolLog.txt";
		//ابتدا آبجکت پیغام خالی می شود
		$objMessage->Clear();
		//گیرنده تعیین می شود
		if( $recipient == "" ) die("No recipient address filled in."); 
		$objMessage->Recipient = $recipient;
		//فرمت پیغام مشخص می شود
		$objMessage->Format = $objConstants->asMESSAGEFORMAT_UNICODE;
		//بدنه پیغام تعیین می شود
		$objMessage->Data = $message;
		//آبجکت جی اس ام خالی می شود
		$_objSmsProtocolGsm->Clear();
		//نام دستگاه تعیین می شود
		$_objSmsProtocolGsm->Device = $device;
		//سرعت دستگاه تعیین می شود
		$_objSmsProtocolGsm->DeviceSpeed = 0;
		//پین کد سیمکارت ارسال می شود
		if( $pincode != "" ) $_objSmsProtocolGsm->EnterPin( $pincode );
		//پیغام ارسال می شود
		if( $_objSmsProtocolGsm->LastError == 0 ){
        	$_objSmsProtocolGsm->Send( $objMessage );
		}
		//کد نتیجه ارسال دریافت می شود
		$LastError        = $_objSmsProtocolGsm->LastError;
        //توضیحات مرتبط با کد نتیجه دریافت می شود
		$ErrorDescription = $_objSmsProtocolGsm->GetErrorDescription( $LastError );
        return $ErrorDescription;
}


/////////////////////end sms ////////////////////////////







   
?>