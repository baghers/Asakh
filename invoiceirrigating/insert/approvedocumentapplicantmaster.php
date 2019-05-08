<?php 
/*
فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/applicant_list.php
*/
//اتصال به دیتا بیس
require_once('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
require_once('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی امال صفحات 
require_once('../includes/elements.php');
//صفحاتی که این صفه از طریق انها این صفحه فراخوانی می شود

//پوشه بارگذاری اسناد		
$path = "../../upfolder/appdoc/";

if ($login_Permission_granted==0) header("Location: ../login.php");
if (!$_POST)//در صورتی که دکمه ثبت کلیک نشده باشد 
{ 
   $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $DesignerCoID=$linearray[2];//شناسه شرکت طراح
    $OperatorCoID=$linearray[3];//شناسه شرکت پیمانکار
    $applicantstatesID=$linearray[4];//شناسه وضعیت طرح
    
    
} else if ($_POST)// در صورتی که دکمه ثبت کلیک شده باشد
{     
    $ApplicantMasterID = $_POST['ApplicantMasterID'];//شناسه طرح
    //پرس و جوی استخراج مدارک قابل بارگذاری
    //appdocmaster جدول مدارک بارگذاری شده
    //appdoc جدول لیست مدارک مختلف قابل بارگذاری
    /*
    appdoc جدول لیست مدارک مختلف قابل بارگذاری
    letterdate تاریخ مدرک
    letterNo شماره مدرک
    Description توضیح
    ApplicantMasterID شناسه طرح
    appdocID شناسه مدرک
    */
    $sql="select appdocmaster.Title,appdoc.appdocID,appdocmaster.appdocmasterID,letterdate,letterNo,Description from appdocmaster
    left outer join appdoc on appdoc.appdocmasterID=appdocmaster.appdocmasterID and ApplicantMasterID='$ApplicantMasterID'";
    
    try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }    
    while($row = mysql_fetch_assoc($result))
    {
        /*
            appdoc جدول لیست مدارک مختلف قابل بارگذاری
            letterdate تاریخ مدرک
            letterNo شماره مدرک
            Description توضیح
            SaveTime زمان ثبت
            SaveDate تاریخ ثبت
            ClerkID کاربر ثبت
            ApplicantMasterID شناسه طرح
            appdocID شناسه مدرک
        */
        if ($row['appdocID']>0)//update mode
        {
            $query = "update appdoc 
            set letterdate='".$_POST["v1_$row[appdocmasterID]"]."',letterNo='".$_POST["v2_$row[appdocmasterID]"]."',
            Description='".$_POST["v3_$row[appdocmasterID]"]."',SaveTime='" . date('Y-m-d H:i:s'). "',SaveDate='".date('Y-m-d')."',ClerkID='$login_userid'
            where ApplicantMasterID='$ApplicantMasterID' and appdocID='$row[appdocID]'; ";
            mysql_query($query);        
        }
        else if ($_POST["v1_$row[appdocmasterID]"]>0 || $_POST["v2_$row[appdocmasterID]"]>0 || $_POST["v3_$row[appdocmasterID]"]>0) //insert mode
        {
            $query = "insert into appdoc (ApplicantMasterID,appdocmasterID,letterdate,letterNo,Description,SaveTime,SaveDate,ClerkID) values 
            ( '$ApplicantMasterID','$row[appdocmasterID]','".$_POST["v1_$row[appdocmasterID]"]."', 
            '".$_POST["v2_$row[appdocmasterID]"]."','".$_POST["v3_$row[appdocmasterID]"]."',
            '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
            
            mysql_query($query);
            //print $query;    
        }
        
        //بارگذاری فایل مربوطه
  		if ($_FILES["file_$row[appdocmasterID]"]["error"] > 0)//درصورت خطا در بارگذاری 
        {
                echo "Error: " . $_FILES["file1"]["error"] . "<br>";
                //exit;
        } 
        else 
        {
            if (($_FILES["file_$row[appdocmasterID]"]["size"] / 1024)>200)//بررسی اندازه فایل
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
            $ext = end((explode(".", $_FILES["file_$row[appdocmasterID]"]["name"])));//پسوند فایل
            $attachedfile=$ApplicantMasterID."_$row[appdocmasterID]_".rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;//نام کامل فایل
            //print $path.$attachedfile;
            foreach (glob($path. $ApplicantMasterID."_$row[appdocmasterID]*") as $filename)//حذف فایل های همنام با شناسه مدرکک 
            {
                unlink($filename);//حذف فایل
            }
            move_uploaded_file($_FILES["file_$row[appdocmasterID]"]["tmp_name"],$path.$attachedfile);//انتقال از محل موقت به محل دائمی   
       }
        
    }
   
}	
    /*
    ApplicantName عنوان پروژه
    ApplicantFName نام /شرکت بهره بردار
    DesignArea مساحت
    CountyName روستای طرح
    shahrcityname نام شهر
    applicantmaster جدول مشخصات طرح
    tax_tbcity7digit جدول شهر ها
    ApplicantMasterID شناسه طرح
    */
    $querys = "SELECT ApplicantName,ApplicantFName,DesignArea,CountyName
    ,shahr.cityname shahrcityname
     from applicantmaster 
    
    left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

    where applicantmaster.ApplicantMasterID ='$ApplicantMasterID'  ";
    
    try 
    {		
        $results = mysql_query($querys);
        $rows = mysql_fetch_assoc($results);
        $ApplicantName="$rows[ApplicantFName] $rows[ApplicantName] - $rows[DesignArea] هکتار شهرستان $rows[shahrcityname]";//مشخصات طرح در عنوان صفحه
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
  	<title>لیست  مدارک طرح ها</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
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
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


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
			  <div style = "text-align:left;">
              <a  href="../appinvestigation/allapplicantstates.php"><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' /></a>
              </div>
              
            <?php if ($result==1) print $secerror= '<p class="note">اطلاعات با موفقيت ذخيره شد.</p>'; ?>
            <form action="approvedocumentapplicantmaster.php" method="post" enctype="multipart/form-data">
			    <div id="loading-div-background">
					<div id="loading-div" class="ui-corner-all" >
						 <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
						 <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
					</div>
			    </div>
				<br/>
				<table id="recordtable" width="99%" align="center">
                   <tbody>           
               

                   <?php         
                 
	
                
				
				
				print "<tr><td colspan='7' style='text-align:center; height:50px;'><br/><b> مدارك  ".$ApplicantName."</td>"; 
			   
                                
                                echo "
                                <tr><td colspan=7>* منظور از شماره و تاریخ شماره و تاریخ بالا سمت چپ مدرک (در صورت وجود)می باشد. </td></tr>
                                <tr><td colspan=7>* مدارک چند صفحه ای را به صورت یک فایل فشرده zip بارگذاری نمایید. </td></tr>
                                <tr>
				<td  style=\"text-align:center; height:25px;\">رديف</td>
				<td  style=\"text-align:center;\">عنوان</td>
				<td  style=\"text-align:center;\">تاريخ</td>
				<td  style=\"text-align:center;\">شماره</td>
				<td  style=\"text-align:center;\">توضیحات</td>
				<td  style=\"text-align:center;\">اسكن</td>
				<td></td>
				<td class=\"no\"></td>
				
				
				
				</tr>";
                $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/appdoc/';//پوشه بارگذاری اسناد
                
                //پرس و جوی استخراج مدارک قابل بارگذاری
                //appdocmaster جدول مدارک بارگذاری شده
                //appdoc جدول لیست مدارک مختلف قابل بارگذاری
                /*
                appdoc جدول لیست مدارک مختلف قابل بارگذاری
                letterdate تاریخ مدرک
                letterNo شماره مدرک
                Description توضیح
                ApplicantMasterID شناسه طرح
                appdocID شناسه مدرک
                */
                $sql="select appdocmaster.Title,appdoc.appdocID,appdocmaster.appdocmasterID,letterdate,letterNo,Description from appdocmaster
                left outer join appdoc on appdoc.appdocmasterID=appdocmaster.appdocmasterID and ApplicantMasterID='$ApplicantMasterID'";
                
                try 
                {		
                    $result = mysql_query($sql);
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                } 
                
                
                
                $rown=0;//ردیف
                while($row = mysql_fetch_assoc($result))
                {
                    $rown++;
                    //////////////////////////////////read scan files
				    $fstr="";
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) //خواندن مدارک
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $ID1=$linearray[0];//شناسه مدرک
                            $ID2=$linearray[1];// شناسه طرح
							$path1 = $path.$file;
                            if (($ID2==$row['appdocmasterID']) && $ID1==$ApplicantMasterID)
                                $fstr="<a target='blank' href='$path1' ><img style = 'width: 20px;' src='../img/accept.png'  ></a>";       
                        }
                    }
                    
                    
                    ///////////////////////////////////////////////
                    
                    /*
                    $row[Title] عنوان مدرک
                    $row[letterdate] تاریخ مدرک
                    $row[letterNo] شماره مدرک
                    $row[Description] توضیحات
                    $row['appdocmasterID'] شناسه مدرک
                    $fstr تگ اچ تی ام ال دانلود مدرک
                    */
                    echo "<tr >
						<td class='label'>$rown</td>
						<td>$row[Title]</td>
						<td><input  placeholder='انتخاب تاریخ'  
                        name='v1_".$row['appdocmasterID']."' type='text' class='textbox' 
                        id='v1_".$row['appdocmasterID']."' value='$row[letterdate]' size='10' maxlength='10' /></td>
                        
						<td><input   
                        name='v2_".$row['appdocmasterID']."' type='text' class='textbox' 
                        id='v2_".$row['appdocmasterID']."' value='$row[letterNo]' size='10' maxlength='50' /></td>
                        
						<td><input   
                        name='v3_".$row['appdocmasterID']."' type='text' class='textbox' 
                        id='v3_".$row['appdocmasterID']."' value='$row[Description]' size='50' maxlength='50' /></td>
                        
                        <td ><input type='hidden' name='v4_".$row['appdocmasterID']."' id='v4_".$row['appdocmasterID']."' value ='$row[appdocID]'>
                        
						<td class='data'><input type='file' name='file_".$row['appdocmasterID']."' id='file_".$row['appdocmasterID']."' accept='image/*'></td>
						<td>$fstr</td>
						
                    </tr>";   
                }
                                //print $sql;
                ?>
                			<tr>                     
                            <td colspan="7"><input type="hidden" name="ApplicantMasterID" value ="<?php echo $ApplicantMasterID;//شناسه طرح ?>">
						
                        <?php 
			
                      echo 
                      
                      "<input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>"; ?>
                      
                        	
							</tr>
                 </tbody>
                   
                </table>
				<div>
				</div>                     
                 <tr>
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form> 
				
            </div>
			<!-- /content -->

		</div>
		

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
