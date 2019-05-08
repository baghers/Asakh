<?php 

/*
appinvestigation/invoicemasterfree_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
appinvestigation/invoicemasterfree_list.php

*/

include('../includes/connect.php'); ?>
<?php  include('../includes/check_user.php'); ?>
<?php  include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $applicantfreedetailID=$linearray[0];//شناسه ریز قسط آزادسازی
    $ApplicantMasterID=$linearray[1];//شناسه طرح
    $OperatorCoID=$linearray[2];//شناسه پیمانکار
    $operatorcoTitle=$linearray[3];//عنوان پیمانکار
    
    /*
    applicantfreedetail جدول ریز آزادسازی
    ApplicantName هنوان پروژه
    DesignArea مساحت پروژه
    applicantmaster جدول مشخصات طرح
    applicantmasterid شناسه طرح
    applicantfreedetailID شناسه ریز قسط آزادسازی
    */
    $query = "SELECT applicantfreedetail.*,applicantmaster.ApplicantName,applicantmaster.DesignArea from applicantfreedetail
    inner join applicantmaster on applicantmaster.applicantmasterid=applicantfreedetail.applicantmasterid
     where applicantfreedetailID='$applicantfreedetailID'";
    
    
      try 
      {		
        $result = mysql_query($query);
        $resquery = mysql_fetch_assoc($result);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        exit;
      }
    $freestateID=$resquery['freestateID'];//شناسه شماره قسط
    $ProducersID=$resquery['ProducersID'];//شناسه تولید کننده
    $paytype=$resquery['paytype'];//paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
    $Price=$resquery['Price'];//مبلغ
    $CheckNo=$resquery['CheckNo'];//شماره چک
    $CheckDate=$resquery['CheckDate'];//تاریخ چک
    $letterdate=$resquery['letterdate'];//تاریخ نامه آزادسازی
    $letterno=$resquery['letterno'];//شماره نامه آزادسازی
    $CheckBank=$resquery['CheckBank'];//بانک
    $Description=$resquery['Description'];//توضیحات
    $AccountNo=$resquery['AccountNo'];//شماره حساب
    $AccountBank=$resquery['AccountBank'];//بانک حساب
    $ApplicantName =$resquery['ApplicantName']." ".$resquery['DesignArea']." هکتار ";//عنوان و مساحت پروژه
}

else
{
    $applicantfreedetailID=$_POST['applicantfreedetailID'];//شناسه ریز قسط آزادسازی
    
    //بارگذاری نامه آزادسازی در پوشه
    //upfolder/free/
    if ($_FILES["file1"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file2"]["error"] . "<br>";
        } 
        else 
        
        {
        if (($_FILES["file1"]["size"] / 1024)>100)
        {
            print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
            exit;
        }
        
        $IDUser =$applicantfreedetailID;
        $path = "../../upfolder/free/";
			
				
        $ext = end((explode(".", $_FILES["file1"]["name"])));
        $attachedfile=$IDUser.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
        //print $path.$attachedfile;
        foreach (glob($path. $IDUser.'_*') as $filename) 
        {
			unlink($filename);
        }
        move_uploaded_file($_FILES["file1"]["tmp_name"],$path.$attachedfile);        
        }

                        
    $register = false;//انجام شدن عمل ثبت
    
    
    
    $OperatorCoID=$_POST['OperatorCoID'];//شناسه پیمانکار
	$operatorcoTitle=$_POST['operatorcoTitle'];//عنوان پیمانکار
	
    $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح
    $freestateID=$_POST['freestateID'];//شناسه شماره قسط
    $ProducersID=$_POST['ProducersID'];//شناسه تولید کننده
    $paytype=$_POST['paytype'];//paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
    $Price = str_replace(',', '', $_POST['Price']);//مبلغ
    $CheckNo=$_POST['CheckNo'];//شماره چک
    $CheckDate=$_POST['CheckDate'];//تاریخ چک
    $CheckBank=$_POST['CheckBank'];//بانک
    $letterdate=$_POST['letterdate'];//تاریخ نامه آزادسازی
    $letterno=$_POST['letterno'];//شماره نامه آزادسازی
    $Description=$_POST['Description'];//توضیحات
    $AccountBank=$_POST['AccountBank'];//بانک حساب
    $AccountNo=$_POST['AccountNo'];//شماره حساب
    $SaveTime=date('Y-m-d H:i:s');//زمان
    $SaveDate=date('Y-m-d');//تاریخ
    $ClerkID=$login_userid;//کاربر        
    if ($ApplicantMasterID>0)
    {
        $query ="update applicantfreedetail
            set AccountBank='$AccountBank',AccountNo='$AccountNo',ApplicantMasterID='$ApplicantMasterID',freestateID='$freestateID',
            paytype='$paytype',ProducersID='$ProducersID',Price='$Price',CheckNo='$CheckNo',letterdate='$letterdate',letterno='$letterno',CheckDate='$CheckDate',CheckBank='$CheckBank',
            Description='$Description',SaveTime='$SaveTime',SaveDate='$SaveDate',ClerkID='$ClerkID'
            where applicantfreedetailID='$applicantfreedetailID' ";
        
          try 
          {		
            $result = mysql_query($query);
            $register = true;
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
          
        
            
    }
        
     
	
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح پرداختی</title>
    <strong>	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


<script type="text/javascript">
            $(function()//ثبت تقویم برای فیلد های تاریخ {
                $("#CheckDate, #simpleLabel").persiandatepicker(); 
                $("#letterdate, #simpleLabel").persiandatepicker();  
				
            });
        
        
function numberWithCommas(x)//گذاشتن ویرگول برای مبلغ ارسالی {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa)//حذف ویرگول از یک مبلغ {
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
   
    
 function fillform(Url)//دریافت اطلاعات حساب جهت پر کردن مشخصات حساب
    {      
        var type=0,ID=0,Price;
        if (document.getElementById('ProducersID').value==-2)
        {
            type=3;//کشاورز
            ID=document.getElementById('ApplicantMasterIDd').value;
        }
        if (document.getElementById('ProducersID').value==-1)
        {
            type=2;//مجری
            ID=document.getElementById('OperatorCoID').value;
        }
        if (document.getElementById('ProducersID').value>0)
        {
            type=1;//فروشنده
            ID=document.getElementById('ProducersID').value;
        }
        Price=document.getElementById('Price').value;
        if (ID>0)
        {
            $("#loading-div-background").show();
            $.post(Url, {type:type,ID:ID,Price:Price}, function(data){
            $("#loading-div-background").hide();  
            if (data.errors==1) alert("تاریخ انقضاء ضمانت ثبت نشده است");   
            if (data.errors==2) alert("ضمانت به انقضا رسیده است");  
            if (data.errors==3) alert("مبلغ وارد شده بیشتر از مبلغ  ضمانت پرداختی "+data.guaranteepayval+" می باشد");  
            if (data.errors==4) alert("کمتر از ده روز به انقضاء ضمانت مانده است");
                       
            $('#AccountNo').val(data.AccountNo);
            $('#AccountBank').val(data.AccountBank);
            }, 'json');                           
        }
    }
                
    </script>
    
</strong>
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
						//echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Code = "";
						$YearID = "";
                        header("Location: invoicemasterfree_list.php?uid=".rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    $ApplicantMasterID.'_1_0_'.$OperatorCoID.
                    rand(10000,99999));
                        
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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="invoicemasterfree_edit.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data" >
                  <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "invoicemasterfree_list.php?uid=".rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    $ApplicantMasterID.'_1_0_'.$OperatorCoID.
                    rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                    <h1 align="center">تصحیح ثبت آزادسازی طرح <?php print $ApplicantName; ?> </h1>
                    
                      <tr>
                        
                            <th ></th>
                            <th class="f10_fontb" width="5%">مرحله آزادسازی</th>
                        	<th class="f10_fontb" width="10%" colspan="2">دریافت کننده</th>
                            <th class="f10_fontb" width="15%">مبلغ(ریال)</th>
                            <th class="f10_fontb" width="10%">ش حساب دریافت کننده</th>
                            <th class="f10_fontb" width="10%">بانک دریافت کننده</th>
                            <th class="f10_fontb" width="10%">ش چک صادره</th>
                            <th class="f10_fontb" width="10%">تاریخ</th>
                            <th class="f10_fontb" width="10%">بانک</th>
                            <th class="f10_fontb" width="20%">توضیحات</th>
                            <th class="f10_fontb" width="20%">تاریخ</th>
                            <th class="f10_fontb" width="20%">شماره</th>
                            <th ></th>
                        </tr>
                        
                     <?php
                          $query=" 
       select 0 _value, '+' _key
       union all select 1 _value, '-' _key";
	   $allpaytype = get_key_value_from_query_into_array($query);
                                  
                               $query="select producers.ProducersID as _value,concat('فروشنده: ',producers.Title) as _key 
                               from producers 
                               inner join invoicemaster on invoicemaster.ProducersID=producers.ProducersID and 
							   invoicemaster.ApplicantMasterID='$ApplicantMasterID' where producers.ProducersID<>135
                               union all select '-1' as _value, 'مجری:$operatorcoTitle' _key
                               union all select '-2' as _value, 'کشاورز (عودت خودیاری):$ApplicantFullName' _key
                               union all select '-3' as _value, 'کشاورز (انجام عملیات):$ApplicantFullName' _key
                               order by _value desc";
                               $allProducersID = get_key_value_from_query_into_array($query);
						 	   //print $query;
							   $query='select freestateID as _value,Title as _key from freestate order by Code';
                               $allfreestateID = get_key_value_from_query_into_array($query);
                                                                       
                                print "<tr><td/>".select_option('freestateID','',',',$allfreestateID,141,'','','1','rtl',0,'',$freestateID,'','85').
												  select_option('ProducersID','','',$allProducersID,0,'','','1','rtl',0,'',$ProducersID,"",'145').
                                                  select_option('paytype','',',',$allpaytype,0,'','','1','rtl',0,'',$paytype,"","40")."
                                <td class='data'><input onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/appinvestigation/guarantee_level1_jr.php');\" 
											value='$Price' name='Price' type='text' class='textbox' id='Price'   size='15' maxlength='50' onKeyUp=\"convert('Price')\"/></td>
                                <td class='data'><input name='AccountNo' type='text' class='textbox' id='AccountNo' value='$AccountNo'  size='12' maxlength='50' /></td>
                                <td class='data'><input name='AccountBank' type='text' class='textbox' id='AccountBank' value='$AccountBank'  size='12' maxlength='50' /></td>
                                <td class='data'><input name='CheckNo' type='text' class='textbox' id='CheckNo' value='$CheckNo'  size='8' maxlength='50' /></td>
                                <td  class='data'><input   name='CheckDate' type='text' class='textbox' id='CheckDate' value='$CheckDate' size='8' maxlength='10' /></td>
                                <td class='data'><input name='CheckBank' type='text' class='textbox' id='CheckBank' value='$CheckBank'  size='8' maxlength='50' /></td>
                                <td class='data'><input name='Description' type='text' class='textbox' id='Description' value='$Description'  size='18' maxlength='50' /></td>                  
                                <td  class='data'><input   name='letterdate' type='text' class='textbox' id='letterdate' value='$letterdate' size='8' maxlength='10' /></td>
                                <td  class='data'><input   name='letterno' type='text' class='textbox' id='letterno' value='$letterno' size='8' maxlength='10' /></td>
                                </tr>
                                <tr>";
                                
                                $fstr1="";
                            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/free/';
                            $handler = opendir($directory);
                            while ($file = readdir($handler)) 
                            {
                                // if file isn't this directory or its parent, add it to the results
                                if ($file != "." && $file != "..") 
                                {
                                    $linearray = explode('_',$file);
                                    $ID=$linearray[0];
                                    if (($ID==$applicantfreedetailID) )
                                        $fstr1="<td><a href='../../upfolder/free/$file' target='_blank' >
                                        <img name='file1img' id='file1img' style = 'width: 30px;' src='../img/accept.png' title='اسکن پروانه' ></a></td>";
                                    }
                            }
                            
                            
                            echo "<tr>
                            <td colspan='3' class='label'>اسکن نامه(حد اکثر 100 کیلوبایت)</td>
                             
                             <td colspan='3' class='data'><input type='file' name='file1' id='file1' </td> 
                             $fstr1</tr>
                                
                                <td colspan=2><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                                <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID' value='$ApplicantMasterID' /></td>   
                                <td class='data'><input name='applicantfreedetailID' type='hidden' class='textbox' id='applicantfreedetailID' value='$applicantfreedetailID' /></td>   
                                <td class='data'><input name='OperatorCoID' type='hidden' class='textbox' id='OperatorCoID' value='$OperatorCoID' /></td>   
                                
                                
                                </tr>
                                <tr>";
                                
                             
                             

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