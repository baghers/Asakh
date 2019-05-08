<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
 include('../includes/check_user.php'); 
 // توابع مرتبط با المنت های اچ تی امال صفحات 
  include('../includes/elements.php'); 
 // کلاسی دارای عملیات چاپ سلکت آپشن با ورودی های مختلف می باشد  
include('../class/fieldType.class.php');


 if ($login_Permission_granted==0) header("Location: ../login.php");

//نقش هایی که امکان مشاهده اطلاعات را دارند
$permitrolsid = array("1","2","5","9","10","20");

//نقش هایی که امکان ویرایش اطلاعات را دارند
$permitrolsidmodir = array("1","20");
$mojavez='';
//print $login_RolesID;

    //در پرس و جوی زیر پرس و جویی ایجاد می شود که اطلاعات را نمایش می دهد برای شرکت ها تنها اطلاعات شرکت خودشان و برای غیر شرکت ها اطلاعات همگی نشان داده می شود
    
    if ($login_DesignerCoID==0 && $login_OperatorCoID==0 && $login_FarmersID==0)
    {
	   /* members جدول اعضای شرکت ها
        Position 1 مدیرعامل
        Position 2 رئیس هیئت مدیره
        Position 3 هیئت مدیره
        Position 4 کارمند
        Position در غیر اینصورت سایر
        designerco جدول شرکت های طراح
        operatorco جدول شرکت های پیمانکار
        Farmers جدول شرکت های بهره بردار
       */	 
        $sql = "SELECT members.*, 
		case ifnull(members.Position,0) when 1 then 'مدیرعامل' when 2 then 'رئیس هیئت مدیره' when 3 then 'هیئت مدیره' when 4 
		then 'کارمند' else 'سایر' end  PoTitle, 
		case ifnull(operatorco.operatorcoid,0) when 0 then case ifnull(Farmers.FarmersID,0) when 0 then designerco.Title else Farmers.FName end else operatorco.Title end  CoTitle  
		from members 
        left outer join designerco on designerco.DesignerCoID=members.DesignerCoID 
        left outer join operatorco on operatorco.OperatorCoID=members.OperatorCoID
        left outer join Farmers on Farmers.FarmersID=members.FarmersID
         "; 
        /*
        $login_ostan شناسه استان کاربر لاگین شده
        $login_RolesID شناسه نقش مدیر پیگیری که فرا استانی پیگیری می نماید و محدودیت استان ندارد
        در غیر اینصورت به جدول کاربران clerk join
        زده می شود تا استان اعضا فیلتر شود
        */
	 	   if ($login_RolesID<>1)
              $and="left outer join clerk on members.ClerkID=clerk.ClerkID where substring('$login_ostan',1,2)= substring(cityid,1,2) 
	           order by CoTitle COLLATE utf8_persian_ci"; 
			else $and=' order by CoTitle COLLATE utf8_persian_ci ';
         
		 $sql.=$and;
		 
    }
	/* 
    در صورتی که کاربر لاگین شده مشاور طراح باشد
    شناسه شرکت لاگین شده در جدول ممبرز محدود می شود.
    DesignerCoID شناسه شرکت مشاور طراح
    */
    else if ($login_DesignerCoID>0) 
		$sql = "SELECT members.*, 
		case ifnull(members.Position,0) when 1 then 'مدیرعامل' when 2 then 'رئیس هیئت مدیره' when 3 then 'هیئت مدیره' when 4 
		then 'کارمند' else 'سایر' end  PoTitle,designerco.Title CoTitle from members 
        left outer join designerco on designerco.DesignerCoID='$login_DesignerCoID' 
		where members.DesignerCoID='$login_DesignerCoID' 
        order by LName COLLATE utf8_persian_ci ";
    /* 
    در صورتی که کاربر لاگین شده بهره بردار  باشد
    شناسه شرکت لاگین شده در جدول ممبرز محدود می شود.
    FarmersID شناسه پیمانکار
    */
    else if ($login_FarmersID>0) 
		$sql = "SELECT members.*, 
		case ifnull(members.Position,0) when 1 then 'مدیرعامل' when 2 then 'رئیس هیئت مدیره' when 3 then 'هیئت مدیره' when 4 
		then 'کارمند' else 'سایر' end  PoTitle,Farmers.FName CoTitle from members 
        left outer join Farmers on Farmers.FarmersID='$login_FarmersID' 
		where members.FarmersID='$login_FarmersID' 
        order by members.LName COLLATE utf8_persian_ci ";
    /* 
    در صورتی که کاربر لاگین شده پیمانکار  باشد
    شناسه شرکت لاگین شده در جدول ممبرز محدود می شود.
    OperatorCoID شناسه پیمانکار
    */
    else $sql = "SELECT members.*, 
		case ifnull(members.Position,0) when 1 then 'مدیرعامل' when 2 then 'رئیس هیئت مدیره' when 3 then 'هیئت مدیره' when 4 
		then 'کارمند' else 'سایر' end  PoTitle, operatorco.Title CoTitle 
		from members 
        left outer join operatorco on operatorco.OperatorCoID='$login_OperatorCoID'
		where members.OperatorCoID='$login_OperatorCoID' 
        order by LName COLLATE utf8_persian_ci ";
    try 
        {		
            $result = mysql_query($sql); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
 


 
 
 
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست اعضا</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        <script type="text/javascript" src="../funcs.js"></script>


    <script type="text/javascript">
            $(function() {
                //اختصاص تقویم فارسی به اینپوت های زیر
                $("#PermisionDate, #simpleLabel").persiandatepicker();   
                $("#BDate, #simpleLabel").persiandatepicker();    
                $("#MDate, #simpleLabel").persiandatepicker();    
                $("#PDate, #simpleLabel").persiandatepicker();  
				$("#BirthDate, #simpleLabel").persiandatepicker(); 
				$("#StartDate, #simpleLabel").persiandatepicker(); 
				
            });
        
function CheckForm()
{
	var Pos = document.getElementById("Position");
    if (!(document.getElementById('FName').value.length>0))
    {
        alert('نام  را وارد نمایید!');return false;
    }
    if (!(document.getElementById('LName').value.length>0))
    {
        alert('نام خانوادگی  را وارد نمایید!');return false;
    }
    if (!(document.getElementById('NationalCode').value.length>0))
    {
        alert(' کد ملی را وارد نمایید!');return false;
    }
	if (Pos.options[Pos.selectedIndex].value==0)
    {
        alert('سمت را وارد نماييد');return false;
    }
	if (!(document.getElementById('StartDate').value.length>0))
    {
        alert('تاريخ شروع را وارد نماييد');return false;
    }
   if(document.getElementById("mojavez").checked == true) 
    {	
       if (!(document.getElementById('PermisionNo').value.length>0))
	   {
         alert('شماره مجوز  را وارد نمایید!');return false;
	   }
      if (!(document.getElementById('PermisionDate').value.length>0))
	  {
         alert('تاریخ صدور مجوز  را وارد نمایید!');return false;
	  }
	  if (!(document.getElementById('issuerID').value>0))
	  {
         alert('مرجع صادر کننده مجوز  را وارد نمایید!');return false; 
	  }
       return true;	 
	}
    if (!(document.getElementById('BDate').value.length>0))
    {
        alert('تاریخ  مدرک کارشناسی را وارد نمایید!');return false;
    }
    if (!(document.getElementById('BLicenceNo').value.length>0))
    {
        alert('شماره  مدرک کارشناسی را وارد نمایید!');return false;
    }
     if (!(document.getElementById('BUniversity').value.length>0))
    {
        alert('دانشگاه اخذ  مدرک کارشناسی را وارد نمایید!');return false;
    }
     
  return true;
}

    </script>
	
    <!-- /scripts -->
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
            <!-- بعد از سابمیت صفحه اطلاعات پست شده به صفحه 
            amaliat.php
            ارسال می شود
            
            در سابمیت فرم هم تابع جاوا اسکریپت 
            CheckForm()
            اجرا می شود
             -->
            <form action="amaliat.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
            <!-- شناسه نقش کاربر لاگین شده 
            login_RolesID-->
            <input type="hidden" id="login_RolesID" name="login_RolesID" value='<?php echo $login_RolesID; ?>' >		
            
            <!-- متغیری که نشان می دهد کاربر لاگین شده مجوز این صفحه را داشته یا خیر
            login_Permission_granted
             -->
            <input type="hidden" id="login_Permission_granted" name="login_Permission_granted" value="<?php echo $login_Permission_granted; ?>" >  
				<table width="95%" align="center" height="45%">
                    <tbody>
                        <tr>
              	   
                        <div style = "text-align:right;">
                            <h1 align="center"> مشخصات اعضای شرکت <h1>
				   <?php
                  /*  if (strlen($enrolmsg)>0)
                    {
                        echo $enrolmsg;
                        exit;
                    }*/
					
                    //نقش هایی که امکان مشاهده اعضای شرکت ها را دارند
                    $permitrolsid = array("1","2","5","9","10","20");
                     if (in_array($login_RolesID, $permitrolsid))
                     {
                        /*
                        LName نام خانوادگی
                        FName نام
                        BirthDate تاريخ تولد
                        NationalCode کد ملی
                        file1 تصور کد ملی
                        Email ایمیل
                        Phone همراه
                        InsuranceHistory سوابق بیمه 
                        InsuranceCode كدبيمه
                        file2 تصویر سوابق بیمه 
                        
                        */
                        print "
                     
                      <tr BGCOLOR =#E7F5F0>
                      <td  class='label'>نام خانوادگی:</td>
                      <td class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 125px\"
                             name='LName' type='text' class='textbox' id='LName'    /></td>
                      <td class='label'>نام :</td>
                      <td colspan='1' class='data'><input 
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" 
                      name='FName' type='text' class='textbox' id='FName'    size='15' maxlength='15' /></td>
					  <td  class='label'>تاريخ تولد:</td>
                      <td  colspan='1' class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='BirthDate' type='text' class='textbox' id='BirthDate'
                        /></td>
					
                     <td   class='label'>کد ملی:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='NationalCode' type='text' class='textbox' id='NationalCode'   /></td>
					   <td></td>
					      <td colspan='2' class='label'>كارت ملي <font color='red' >(jpeg)</font></td>
                        <td colspan='1' class='data'><input type='file' name='file1' id='file1' ></td>
				      </tr>
                      <tr>
					  <td  colspan='2' class='data'>ایمیل:<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 155px\"
                       name='Email' type='text' class='textbox' id='Email'    /></td>
                	
						<td  class='label'> همراه:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='Phone' type='text' class='textbox' id='Phone'    /></td>
                       
					 <td  class='label'>كدبيمه :</td><td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='InsuranceCode' type='text' class='textbox' id='InsuranceCode'    /></td>
					   <td  class='label' colspan='2'>مرتبط بيمه :<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                       name='InsuranceHistory' type='text' class='textbox' id='InsuranceHistory'    />ماه</td>
					   <td></td><td></td>
					         <td colspan='1' class='label'> بيمه <font color='red' >(jpeg)</font></td>
                       <td colspan='1' class='data'><input type='file' name='file2' id='file2' ></td>
                  
					    </tr>";
                    
                    /*
                    BDate تاریخ مدرک کارشناسی
                    BLicenceNo شماره مدرک کارشناسی
                    BUniversity نام دانشگاه اخذ مدرک کارشناسی
                    */
                    echo "
                    <tr  BGCOLOR =#E7F5F0>
                     <td colspan='1'  class='label'>کارشناسی:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='BDate' type='text' class='textbox' id='BDate'
                        /></td>
                     <td  class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='BLicenceNo' type='text' class='textbox' id='BLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td   class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='BUniversity' type='text' class='textbox' id='BUniversity'    /></td>"; 
                    
                    /*پرس و جوی استخراج رشته های مختلف تحصیلی*/ 
                    $qryBranch = "SELECT 
									   '1' _value, 'آبياري' _key 
                      union all SELECT '2' _value, 'کشاورزی' _key 
                      union all SELECT '2' _value, 'عمران آب' _key 
                      union all SELECT '3' _value, 'سایر' _key 
                      order by _value  ";
                    $dropBranch = get_key_value_from_query_into_array($qryBranch);         
                    //Bbranch رشته کارشناسی
                    echo select_option('Bbranch','رشته:',',',$dropBranch,'','','','','rtl','','','',"",80);
                    
                    /*پرس و جوی استخراج ارتباط رشته های تحصیلی با کشاورزی*/ 
                    $qryStat = "SELECT '1' _value, 'مرتبط' _key 
                      union all SELECT '2' _value, 'غيرمرتبط' _key 
					  union all SELECT '3' _value, 'زمينه' _key
                      order by _value  ";
					 $dropStat = get_key_value_from_query_into_array($qryStat);  
                     //Bstat مرتبط بودن یا نبودن رشته کارشناسی
					 echo select_option('Bstat','',',',$dropStat,'','','','','rtl','','','',"",50);	
                     
                     //file4 تصویر مدرک
                     echo"<td></td><td  class='label'>تصویر<font color='red' >(jpeg)</font></td>
                        <td  class='data'><input type='file' name='file4' id='file4' ></td>";
                        
                     
                    /*
                    MDate تاریخ مدرک ارشد
                    MLicenceNo شماره مدرک ارشد
                    MUniversity نام دانشگاه اخذ مدرک ارشد
                    */
                        
					  echo"</tr>
                       <tr >
                     <td colspan='1'  class='label'> ارشد:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='MDate' type='text' class='textbox' id='MDate'
                        /></td>
                     <td   class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='MLicenceNo' type='text' class='textbox' id='MLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td  class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='MUniversity' type='text' class='textbox' id='MUniversity'    /></td>";
                        //Mbranch رشته ارشد
                        echo select_option('Mbranch','رشته:',',',$dropBranch,'','','','','rtl','','','',"",80);	
                        //Bstat مرتبط بودن یا نبودن رشته ارشد
                        echo select_option('Mstat','',',',$dropStat,'','','','','rtl','','','',"",50);
                        //file4 تصویر مدرک
                     						   
                        echo"<td></td><td  class='label'>تصویر<font color='red' >(jpeg)</font></td>
                        <td  class='data'><input type='file' name='file5' id='file5' ></td>";
					   /*
                    PDate تاریخ مدرک دکتری
                    PLicenceNo شماره مدرک دکتری
                    PUniversity نام دانشگاه اخذ مدرک دکتری
                    */   
                       
                       echo"</tr>
                       <tr  BGCOLOR =#E7F5F0>
                     <td colspan='1'  class='label'>دکتری:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='PDate' type='text' class='textbox' id='PDate'
                        /></td>
                     <td   class='label'>شماره:</td>
                      <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PLicenceNo' type='text' class='textbox' id='PLicenceNo'    /></td>
                     <td   class='label'>دانشگاه:</td>
                      <td  class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PUniversity' type='text' class='textbox' id='PUniversity'    /></td>";
                       
                       //Pbranch رشته دکتری
                      echo select_option('Pbranch','رشته:',',',$dropBranch,'','','','','rtl','','','',"",80);
                      //Pstat مرتبط بودن یا نبودن رشته دکتری 
                      echo select_option('Pstat','',',',$dropStat,'','','','','rtl','','','',"",50);
                      //تصویر مدرک	
                      echo"<td></td><td  class='label'>تصویر<font color='red' >(jpeg)</font></td>
                        <td  class='data'><input type='file' name='file6' id='file6' ></td>					  
                       </tr>";
                    //نقش های زیر امکان ثبت کارشناس فنی را دارند      
                    $permitrolsidmodir = array("1","20");
                    if (in_array($login_RolesID, $permitrolsidmodir))	
                    {		
                        echo"<tr><td class='data'  colspan=5>شرکت";
    					//کوئری جهت ایجاد سلکت آپشن شرکت های مهندسین مشاور
                        $query='select designercoID as _value,Title as _key from designerco order by _key COLLATE utf8_persian_ci';
                        //کوئری جهت ایجاد سلکت آپشن شرکت های پیمانکار
                        $query2='select operatorcoID as _value,Title as _key from operatorco order by _key COLLATE utf8_persian_ci';
                        //کوئری جهت ایجاد سلکت آپشن شرکت های بهره بردار
                        $query3='select FarmersID as _value,FName as _key from Farmers where personality=1 order by _key COLLATE utf8_persian_ci';
                        
                        //کلاس ایجاد سلکت آپشن
                        $drop=new fieldType();
                        echo"<input name='compny' id='compny' value=1 type='radio' onclick='ShowHideDiv2(this,this.value)'>مشاور";
    					//$drop->dropDb تابع چاپ سلکت آپشن
                        echo "<span id='spdes' style='display:none' >".$drop->dropDb('designercoID','_key','_value',$query,'')."</span>"; 
                        echo"<input name='compny' id='compny' value=2 type='radio' onclick='ShowHideDiv2(this,this.value)'> مجري   ";
    					//$drop->dropDb تابع چاپ سلکت آپشن
                        echo "<span id='spoprat' style='display:none' >".$drop->dropDb('operatorcoID','_key','_value',$query2,'')."</span>"; 
                        echo"<input name='compny' id='compny' value=3 type='radio' onclick='ShowHideDiv2(this,this.value)'>بهره&nbspبردار   ";
    					//$drop->dropDb تابع چاپ سلکت آپشن
                        echo "<span id='spoprat' style='display:none' >".$drop->dropDb('FarmersID','_key','_value',$query3,'')."</span></td>";
				
                    } 
                    else 
                    {
                        //خواندنیک ردیف از پرس و جود
                        $rows =mysql_fetch_assoc($result);
                        //انتقال اشاره گر آرایه نتایج به ابتدای آررایه جهت خواندن در حلقه بعدی
                        mysql_data_seek( $result, 0 );
					   //$rows[CoTitle] نام شرکت
    				    
                        //شناسه و نام شرکت در یک ورودی پنهان جهت پست استفاده می شود.
                        if ($login_RolesID==9 || $login_RolesID==10)
                        { 
                            $ID='designercoID'; 
                            $valu=$login_DesignerCoID;
                        }
                        else if ($login_RolesID==2)
                             { 
                                $ID='operatorcoID';
                                $valu=$login_OperatorCoID; 
                             }
                        else 
                        { 
                            $ID='FarmersID';
                            $valu=$login_FarmersID; 
                        }					
    					echo "				
    					<tr><td class='data'  colspan=1>شرکت :</td>
    					<td colspan=3>$rows[CoTitle]</td>
    					<input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\" name='$ID' type='hidden' class='textbox' id='$ID'   value='$valu'  />";
				    }

				    //پرس و جوی سمت شرکت
				    $qryPosition = "SELECT '1' _value, 'مديرعامل' _key 
                      union all SELECT '2' _value, 'رئيس هيئت مديره' _key 
                      union all SELECT '3' _value, 'هيئت مديره' _key  
                      union all SELECT '4' _value, 'كارمند' _key
                      union all SELECT '5' _value, 'سایر' _key order by _value  ";
					$dropPosition = get_key_value_from_query_into_array($qryPosition); 
                    //سلکت آپشن سمت شرکت
					echo select_option('Position','سمت شركت:',',',$dropPosition,'','','','','rtl','','','',"",100)."
					  <td   class='label'>تاريخ شروع:</td><td  class='data'><input
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='StartDate' type='text' class='textbox' id='StartDate'
                        /></td>
                      <td   class='label'>حق امضاء:</td><td  class='data'>  
                        <input id='signatureright' name='signatureright' type='checkbox'   id='signatureright' /></td>
				";
				
					echo "</tr><tr><td  class='label' colspan='1'>کارشناس فنی:";
                    //نقش هایی که امکان بررسی و تایید مجوز کارشناسان فنی را دارند
			     if (in_array($login_RolesID, $permitrolsidmodir))
                     {
                        //چک باکس تایید کارشناس فنی
    					echo"
    					<input name='mojavez' type='checkbox' onclick='ShowHideDiv(this,this.id)'  id='mojavez'";if ($mojavez>0) echo 'checked'.'/>';
                        echo "</td>"; 					
					 }  
				  else print "</td>";
                  
                  /*
                  file3 تصویر مجوز کارشناس فنی
                  PermisionNo شماره مجوز کارشناس فنی
                  PermisionDate تاریخ صدور مجوز کارشناس فنی
                  
                  */
				    print "
				
				  <td></td><td  class='label' style='visibility:visible'>مجوز<font color='red' >(jpeg)</font></td>
                       <td  class='data' style='visibility:visible'><input type='file' name='file3' id='file3' ></td>	  
				</tr>
					
				<tr BGCOLOR =#E7F5F0 id='spmojavez' style='visibility:hidden'>
					 <td  class='label'>شماره مجوز:</td>
                     <td class='data'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       name='PermisionNo' type='text' class='textbox' id='PermisionNo' /></td>
                      <td  class='label'>تاریخ صدور:</td>
                      <td  class='data'><input  style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                       placeholder='انتخاب تاریخ'  name='PermisionDate' type='text' class='textbox' id='PermisionDate' /></td>
					";
					 /*
                     issuer جدول مراجع صدور مجوزها
                     issuerID شناسه مرجع صادر کننده مجوز برای کارشناس فنی فوق
                     */ 
					 $query='select issuerID as _value,Title as _key from issuer';
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<td id='issuerIDlbl'  class='label'>مرجع صدور :</td>".
                     select_option('issuerID','',',',$ID,0,'','','1','rtl',0,'',0,'','100');
		            
                    //ثبت اطلاعات شرکت
					print "
					</tr>
					
					 <td><input   name='des_add' type='submit' class='button' id='des_add' value='ثبت' /></td>
                    </tr>
					 
                            ";   
                      
                     }
		              
                   
                    ?>
               </div>
                  
                          
                        </tr>
                   </tbody>
                </table>
				
                <!-- 
                جدول چاپ اعضای هیئت مدیره شرکت در لیست پایین ثبت نام
             -->
             
                <table id="records" width="95%" align="center" cellpadding='10' cellspacing='10'>
                    <thead>
                        <tr>
                        
                        	<th width="3%">ردیف </th>
                        	<th width="20%"><?php echo 'شرکت'; ?> </th>
                        	<th width="15%">سمت </th>
                        	<th width="15%"></th>
                        	<th width="30%">نام خانوادگی </th>
                        	<th width="30%">نام</th>
                        	<th width="20%">کد ملی</th>
                            <th width="5%"></th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>        
                   <?php
                   
                    $rown=0;        
                    while($row = mysql_fetch_assoc($result)){
                        $LName = $row['LName'];//نام خانوادگی
                        $FName = $row['FName'];//نام
                        $membersID = $row['membersID'];// شناسه عضو
                        $rown++;
                        //متغیری که لینک حذف عضو  را دارد
                        $deletestr="";
                        //نقش هایی که مجوز دازند امکان حذف عضو توسط لینک زیر فراهم می شود
                        if (in_array($login_RolesID, $permitrolsid))
                        $deletestr="<a 
                        href='designer_delete.php?uid=".rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).$membersID.'^'.$login_RolesID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 75%;' src='../img/delete.png' title='حذف'> </a>";
                        //designer_delete.php لینک حذف عضو
                            
                        
?>                      
                        <tr>
                            
                            <td><?php echo $rown;// شماره ردیف ?></td>
                            <td><?php echo $row['CoTitle'];// عنوان شرکت ?></td>
                            <td><?php echo $row['PoTitle'];// عنوان شرکت تولید کننده ?></td>
                            <td><?php if ($row['mojavez']==1) echo ' کارشناس فنی ';//$row['mojavez'] داشتن مجوز بررسی کارشناس فنی ?></td>
                            <td><?php echo $LName;//نام خانوادگی ?></td>
                            <td><?php echo $FName//نام ?></td>
                            <td><?php echo $row['NationalCode'];// شناسه ملی شرکت ?></td>
                           <?php  
                           
                           //نقش هایی که امکان ویرایش مشخصات اعضا را دارند
                           if (in_array($login_RolesID, $permitrolsidmodir)) ?>
							<td><a href=<?php  print "designer_edit.php?uid=".rand(10000,99999)
							.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
							.rand(10000,99999).rand(10000,99999).$membersID.rand(10000,99999); 
                            
                            //designer_edit.php لینک تصحیح مشخصات
                            
                            ?>>
                            <img style = "width: 75%;" src="../img/file-edit-icon.png" title=' تصحیح ' ></a></td>
                            
                            
                            <?php 
                            print "<td>$deletestr</td>
                        </tr";

                   
				}

?>

                        
                   
                    </tbody>
                   
                </table>
                      
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
