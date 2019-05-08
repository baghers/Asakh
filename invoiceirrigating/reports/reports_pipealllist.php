<?php 
/*
reorts/reports_pipealllist.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php

if ($_POST && $login_RolesID<>'3')  $login_ProducersID=$_POST["ProducersID"];

	function get_key_value_from_query_into_array($query)//تبدیل کوئری به آرایه
  {
    $returned_array='';
    $result = mysql_query($query);

	$returned_array[' ']=' ';
    if ($result)
	while($row = mysql_fetch_assoc($result))
      $returned_array[$row['_key']]=$row['_value'];
    //print "salam".$query;
    
    
     return $returned_array;
  }
  
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

$formename='reports_pipealllist';
if ($login_Permission_granted==0 && substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-22,18)!='viewapplicantstate'
&& substr($_SERVER['HTTP_REFERER'],strlen($_SERVER['HTTP_REFERER'])-strlen($formename.strstr($_SERVER['HTTP_REFERER'],'.php')),strlen($formename))!=$formename) header("Location: ../login.php");

if ($_POST){
    
        
        $PDate=$_POST["PDate"];
    
    
    $theader=" $PDate  ";                        

/*
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    applicantstates.title عنوان وضعیت پروژه
    hektar سطح پروژه
    prjtypeid نوع پروژه
    nazerID ناظر پروژه
    creditsourceTitle عنوان منبع تامین اعتبار
    ApplicantMasterIDmaster شناسه طرح اجرایی
    DesignerCoID شناسه مشاور طراح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    designsystemgroupsdetail جدول ریز سیستم های آبیاری
    appstatesee لیست وضعیت هایی که هر نقش می بیند
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    
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
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
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
    */
     	
    $sql="SELECT producers.title producerstitle ,marks.marksid,marks.title markstitle,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title,
        gadget2.gadget2id, 
        gadget3.title Gadget3Title, units.title UnitsTitle, 
        
        case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
               end Price
        
        , gadget3.Code, toolsmarks.ProducersID, toolsmarks.Gadget3ID,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' ',marks.title),'  ',' ' ) FullTitle
        ,toolsmarks.toolsmarksID
        FROM toolsmarks
        inner join marks on marks.marksid=toolsmarks.marksid
        inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id  and gadget1.gadget1id=68 
        inner join producers on producers.ProducersID=toolsmarks.ProducersID 
        
        left outer join units on units.Unitsid=gadget3.Unitsid
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        
        
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        
        left outer join pipeprice on pipeprice.Date='$PDate'  and pipeprice.ProducersID=toolsmarks.ProducersID
        
        where toolsmarks.ProducersID ='$login_ProducersID'  
        
        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
        
    ";
try 
    {		
        $result = mysql_query($sql); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
        
        //print $sql;
	
}
?>



<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;width:350px ;text-align:right;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#eaeaea;border:1px solid black;width:350px ;border-color:#000000 #000000;text-align:right;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#ececec;border:1px solid black;width:75px ;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#ececec;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#ececec;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f12_fontb{
		background-color:#a0fabe;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:140%;font-weight: bold;font-family:'B Nazanin';                           
  }

  
    p.page { page-break-after: always; }
</style>

  
</head>
<body >

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
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
            
            <form action="reports_pipealllist.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                     <?php
                     
					 if ($login_RolesID<>'3') {
                     $query='select ProducersID as _value,Title as _key from producers  where producers.PipeProducer in (1,101) order by _key  COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('ProducersID','نام تولید کننده/فروشنده:',',',$ID,0,'','','1','rtl',0,'',$ProducersID,'','100','');
				
				    $squery="SELECT max(Date) maxDate FROM `pipeprice`
                             where ProducersID='$login_ProducersID'";
       		        $count = mysql_fetch_assoc(mysql_query($squery));
					$PDate = $count['maxDate'];
					
					}
                     
					
				    $query="SELECT Date as _value,
                             Date as _key FROM `pipeprice`
                             where ProducersID='$login_ProducersID'
                             ORDER BY _value DESC";
    				 $ID = get_key_value_from_query_into_array($query);
					 
                     print "<td id='PriceListMasterIDlbl'  class='label'>لیست قیمت لوله:</td>".
                     select_option('PDate','',',',$ID,0,'','','1','rtl',0,'',$PDate,'','135');

					
                      ?>
                      
                     
                     
                     
                     
                     
                      <td colspan="1"><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                      
                     
				 			                                       
                   </tr>
			   
                   </tbody>
                                     
                </table>
                <table id="records" width="95%" align="center">
                   
                    <thead>
                        
                </table>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                  
                <table align='center' class="page" border='1'>              
                   
				  
                            
                             <?php
                   
                               
                               $rown=0;
                               $alldata=array();
                                while($row = mysql_fetch_assoc($result))
                                {
                                    $rown++;
                                    $alldata[$rown][0]=$row['FullTitle'];
                                    $alldata[$rown][1]=$row['UnitsTitle'];
                                    if ($row['Price']>0)
                                    $alldata[$rown][2]=number_format($row['Price']);
                                    $producerstitle=$row['producerstitle'];
                                }
                                    $theaderall="
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> قیمت </span>
        							  </th>
                                    
                                    <th  
                                   	<span class=\"f12_fontb\" > رديف  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> شرح  </span> </th>
        							<th 
                                   	<span class=\"f12_fontb\"> واحد </span> </th>
        							<th  
                                    <span class=\"f12_fontb\"> قیمت </span>
        							 </th>
                                     
                                </tr>"
                                ?>
                      
                   <?php
                    $pagenumber=1;
                    echo "<table width=\"95%\" align=\"center\"><tr> 
                                       <td colspan=\"7\"
                                                <span class=\"f14_font\" >  لیست قیمت لوله  $theader $producerstitle
                                                
                                                
                                                </span> </td>
                                                <td class=\"f14_font\" >$pagenumber </td>
                    				   </tr>
                                      
                                    <tr>".$theaderall;
                    $j=0;
                    //print $rown;
                    
                    $rownj=$rown;
					for($i=1;$i<=(ceil($rown/2));$i++)
                    {
                    
					$j=$j+1;
					if ($j>45) 
                    {
                        $pagenumber++;  
    					$j=1;
                        $rownj-=90;
                        
                        echo "</table><table width=\"95%\" align=\"center\"><p class=\"page\"></p><tr> 
                                       <td colspan=\"7\"
                                                <span class=\"f14_font\" >  لیست قیمت لوله  $theader $producerstitle
                                                
                                                
                                                </span> </td>
                                                <td class=\"f14_font\" >$pagenumber </td>
                    				   </tr>
                                      
                                    <tr>".$theaderall;
                    }
                       
					                             
				  
					   
					   
					   
                        if ($i%2==1) 
                        $b=''; else $b='b';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f9_font<?php echo $b; ?>"  >  <?php echo $j; ?> </span>  </td>
							
                            <td 
							<span class="f13_font<?php echo $b; ?>">  <?php echo  $alldata[($pagenumber-1)*45+$i][0]; ?> </span> </td>
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i][1]; ?> </span> </td>
       
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i][2]; ?> </span> </td>
                            
                            
                            <td
                            <span class="f9_font<?php echo $b; ?>"  >  <?php  
                            if ($rownj>=45)  $index=45; else   $index=ceil(($rownj)/2);
                            
                            echo $j+$index; ?> </span>  </td>
							
                            <td 
							<span class="f13_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][0]; ?> </span> </td>
                           
                            <td
							<span class="f9_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][1]; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $alldata[($pagenumber-1)*45+$i+$index][2]; ?> </span> </td>
                                              
                                                     
                            
							  

							 
                        </tr><?php

                    }
                    echo "</table>";
                    
                    

?>
 
                   
                </table>
                    </tbody>
                   
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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
