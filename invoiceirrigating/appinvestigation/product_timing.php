<?php 
/*

//appinvestigation/product_timing.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantstatesop.php

*/
include('../includes/connect.php'); 
include('../includes/check_user.php');
include('../includes/elements.php');


if ($_GET) 
{
    $uid=$_GET["uid"];
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $numpage=$linearray[1];//تعداد صفحات گزارش
    $type=$linearray[1];//نوع صفحه 6 آبرسانی در غیر اینصورت آبیاری تحت فشار
    $DesignerCoID=$linearray[2];//شناسه شرکت طراح
    $operatorcoID=$linearray[3];//شناسه شرکت پیمانکار
}
	
if ($_POST)//در صورتی که دکمه ثبت کلیک شده بود
{
    $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح
    $type=$_POST['type'];//نوع صفحه
}
if ($type==6) $hektar='متر'; else $hektar='هکتار';
    $linearray = explode('_',returnpipeproducetiming ($ApplicantMasterID));//استخراج اطلاعات زمانبندی پروژه
    $val_ApplicantFName=$linearray[0];//نام متقاضی طرح
    $val_ApplicantName=$linearray[1];//عنوان پیمانکار
    $val_DesignArea=$linearray[2];//مساحت طرح
    $val_shahrcityname=$linearray[3];//نام شهر
    $val_operatorcoTitle=$linearray[4];//پیمانکار
    $val_DesignerCotitle=$linearray[5];//شرکت طراح 
    $val_proposedate=$linearray[6];//تاریخ پیشنهاد قیمت
    $val_Windate=$linearray[7];//تاریخ انتخاب مجری
    $val_InvoiceDate=$linearray[8];//تاریخ پیش فاکتور
    $val_Title=$linearray[9];//عنوان پیش فاکتور
    $val_producersTitle=$linearray[10];//عنوان تولید کننده
    $val_producedateP=$linearray[11];//تاریخ اعلامی تولید کننده جهت تولید
    $val_producedateA=$linearray[12];//تاریخ تاییدشده  بازرس جهت تولید
    $val_testdateP=$linearray[13];//تاریخ اعلامی تولید کننده جهت تست
    $val_testdateA=$linearray[14];//تاریخ تاییدشده  بازرس جهت تست
    $val_ApproveP=$linearray[15];//تاریخ اعلامی تولید کننده جهت ارسال لوازم
    $val_ApproveA=$linearray[16];//تاریخ تاییدشده  بازرس جهت ارسال لوازم
    $val_BOLNO=$linearray[17];//شماره بارنامه
    $val_tonajP=$linearray[18];//تناژ اعلامی تولیدکننده 
    $val_tonajA=$linearray[19];//تناژ تاییدشده بازرس
    $val_score1=$linearray[20];//امتیاز کنترل کیفیت
    $val_score2=$linearray[21];//امتیاز توانمندی تولید
    $val_score3=$linearray[22];//امتیاز تشکیلات سازمانی
    $val_Description=$linearray[23];//شرح
    $val_invoicetimingID=$linearray[24];//شناسه جدول زمانبندی
    $CheckDate=$linearray[25];//تاریخ چک
    $totalscore=$linearray[26];//امتیاز کل
    $D1=$linearray[27];//فاصله زمانی بر حسب روز بین انتخاب مجری و ثبت طرح
    $D2=$linearray[28];//فاصله زمانی بر حسب روز بین انتخاب مجری و آزادسازی طرح 
    $D3=$linearray[29];//تناژ پروژه
    $D4=$linearray[30];//فاصله زمانی بر حسب روز بین آزادسازی و تست پروژه اعلامی تولیدکننده
    $D5=$linearray[31];//فاصله زمانی بر حسب روز بین آزادسازی و تست پروژه تایید شده بازرس
    $D6=$linearray[32];//فاصله زمانی بر حسب روز بین تاریخ تولید و تاریخ ارسال اعلامی تولیدکننده
    $D7=$linearray[33];//فاصله زمانی بر حسب روز بین تاریخ تولید و تاریخ ارسال تایید شده توسط بازرس

	if (!$val_DesignerCotitle)//در صورتی که شناسه شرکت طراح بزرگتر از صفر باشد
	{
	   /*
       DesignerCoID شرکت طراح
       Title عنوان طراح
       designerco شرکت های طراح
       clerk جدول کاربران
       ClerkID شناسه کاربر
       */
		$sqlc="select DesignerCoID,Title from designerco left outer join clerk on  clerk.MMC=designerco.DesignerCoID where clerk.ClerkID='$DesignerCoID' ";  
	    try 
        {		
        	$resultc = mysql_query($sqlc);
    		$rowc = mysql_fetch_assoc($resultc);
    		$val_DesignerCotitle=$rowc['Title'];//عنوان شرکت طراح
        }
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 
	}

                            
if ($_POST) {
             $upstr="";
             //تاریخ اعلامی ارسال لوازم توسط تولیدکننده
             if ($_POST['ApproveP']>0) $upstr.=", ApproveP='".jalali_to_gregorian(compelete_date($_POST['ApproveP']))."'";  
             //تاریخ تولید اعلامی توسط تولیدکننده
             if ($_POST['producedateP']>0) $upstr.=", producedateP='".jalali_to_gregorian(compelete_date($_POST['producedateP']))."'";  
             //تاریخ تست اعلامی توسط تولیدکننده
             if ($_POST['testdateP']>0) $upstr.=", testdateP='".jalali_to_gregorian(compelete_date($_POST['testdateP']))."'";    
             //تناژ اعلامی تولیدکننده  
             if ($_POST['tonajP']>0) $upstr.=", tonajP='$_POST[tonajP]'";
             //تاریخ ارسال لوازم تایید شده بازرس
             if ($_POST['ApproveA']>0) $upstr.=", ApproveA='".jalali_to_gregorian(compelete_date($_POST['ApproveA']))."'";  
             //تاریخ تاییدشده  بازرس جهت تولید
             if ($_POST['producedateA']>0) $upstr.=", producedateA='".jalali_to_gregorian(compelete_date($_POST['producedateA']))."'"; 
             ////تاریخ تاییدشده  بازرس جهت تست 
             if ($_POST['testdateA']>0) $upstr.=", testdateA='".jalali_to_gregorian(compelete_date($_POST['testdateA']))."'";  
             //تناژ تاییدشده بازرس    
             if ($_POST['tonajA']>0) $upstr.=", tonajA='$_POST[tonajA]'";          
             
			 if ($_POST['testdateA']>0)
			 {
				 if ($_POST['score1']>0) $upstr.=", score1='$_POST[score1]'";//امتیاز کنترل کیفیت          
				 if ($_POST['score2']>0) $upstr.=", score2='$_POST[score2]'";//امتیاز کنترل کیفیت          
				 if ($_POST['score3']>0) $upstr.=", score3='$_POST[score3]'";//امتیاز تشکیلات سازمانی
				 $result1=1;
			 }
			 else 
				 $result1=0;
				
             if ($_POST['RolesID']==29) //نقش بازرس
                $upstr.=", ClerkIDexaminer='$_POST[userid]'";//فیلتر بازرس
             if (strlen($_POST['Description'])>0) $upstr.=", Description='$_POST[Description]'";//شرح
             //بروزرسانی ردیف جدول زمانبندی
              $query="update invoicetiming set invoicetimingID='$_POST[invoicetimingID]' $upstr  
                    where invoicetimingID='$_POST[invoicetimingID]'";
              try 
            {		
                mysql_query($query);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
            
            
    $err='امتیازات';$errnum=0;
 
    if ($result1==0) 
		echo "<script>alert('خطا در ثبت امتیازات!')</script>"; 
    else
    {   //صفحه بازگشتی 
    	if ($_POST['type']==5)
    	header("Location: ../reports/reports_PipeProduction.php");
    	else if ($_POST['type']==4)
    	header("Location: allapplicantstatesop.php");
    	else if ($_POST['type']==6)
    	header("Location: allapplicantrequestws.php");
    }

}



?>
<!DOCTYPE html>
<html>
<head>
  	<title>جدول زمانبندي طرح </title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    <script>



                
    </script>
	
	 <script type="text/javascript">
            $(function() {
                $("#pathstart, #simpleLabel").persiandatepicker();   
                $("#pathend, #simpleLabel").persiandatepicker();   
				$("#ApproveP, #simpleLabel").persiandatepicker();   
                $("#settlementdate, #simpleLabel").persiandatepicker();   
				$("#drillingstart, #simpleLabel").persiandatepicker();   
                $("#drillingend, #simpleLabel").persiandatepicker();   
				$("#rglazhstart, #simpleLabel").persiandatepicker();   
                $("#rglazhend, #simpleLabel").persiandatepicker();   
				$("#intubationstart, #simpleLabel").persiandatepicker();   
                $("#intubationend, #simpleLabel").persiandatepicker();   
				$("#pondstart, #simpleLabel").persiandatepicker();   
            });
                
    </script>
	<style>
		td.rowtable {
		text-align:center; height:30px; vertical-align:middle;
		}
	</style>
	
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
            <?php if ($result1==1) print $secerror= '<br/><p class="note">اطلاعات با موفقيت ذخيره شد.</p>'.$err; 
							//	else print $secerror= '<br/><p class="note">اطلاعات با موفقيت ذخيره نشد.</p>'.$err;
								?>
            
			<form action="product_timing.php" method="post" enctype="multipart/form-data">
                
                <br/>
                <table id="records" width="95%" align="center">
                     
                   <tbody>           
               <tr>
			   <td colspan="8" style="height:80px; vertical-align:middle; font-weight:bold; text-align:center;">جدول زمان بندي تولید و تحویل کالا طرح آبياري <?php 
               echo $val_ApplicantFName.'&nbsp' .$val_ApplicantName .'&nbsp;'. $val_DesignArea .'&nbsp;'. $hektar . 'شهرستان&nbsp;'. $val_shahrcityname . 'پیمانکار&nbsp;'. $val_operatorcoTitle .'<br/><br/>';
               
//               if ($login_RolesID<>2)
			          
                    	
				
                print "".'مشاور بازرس کنترل کیفیت : '.$val_DesignerCotitle ;   
                print "
                        <a  target=\"_blank\"  href='chart_product_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999)
                            .$val_ApplicantFName."_".
                            $val_ApplicantName."_".
                            $val_DesignArea."_".
                            $val_shahrcityname."_".
                            $val_operatorcoTitle."_".
                            $val_DesignerCotitle."_".
                            $D1."_".
                            $D2."_".
                            $D3."_".
                            $D4."_".
                            $D5."_".
                            $D6."_".
                            $D7.rand(1000,9999).'1'."'>
						<img style = 'width: 25px' src=\"../img/chart.png\" title='نمودار زمانبندی'></a>";
				         
				//$hideop='style=display:none;';$hideop1='display:none;';
				//$hidenazer='style=display:none;';$hidenazer1='display:none;';
			
				if ($login_RolesID==10 || $login_RolesID==1){$hideop='';$hideop1='';}
				if ($login_RolesID==1 || $login_RolesID==13 || $login_RolesID==14 || $login_RolesID==23) {$hidenazer='';$hidenazer1='';}
				
				 
	    //if (in_array($login_RolesID, $permitrolsidforviewdeliverydates))
                    
			//print $sql;
					?>
					
					
							<tr style="height:40px; font-weight:bold;">
									<td  style="text-align:center; width:5%;  vertical-align:middle; "></td>
									<td  style="text-align:center; width:35%;  vertical-align:middle;">تاریخ پیشنهاد قیمت</td>
									<td  style="text-align:center; width:15%;  vertical-align:middle;">تاریخ برنده پیشنهاد</td>
									<td  style="text-align:center; width:10%;  vertical-align:middle;">تاریخ پیش فاکتور</td>
									<td  style="text-align:center; width:10%;  vertical-align:middle;">تاریخ واریز وجه </td>
							<td></td>
							<td></td>
								
								</tr>					  
				

								
						<tr>
							<td class='label'></td>
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="SaveDate" type="text" class="textbox" id="SaveDate" readonly value="<?php if (strlen($val_proposedate)>0) echo gregorian_to_jalali($val_proposedate);?>" size="12" maxlength="10" /></td>
							
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="Windate" type="text" class="textbox" id="Windate" readonly value="<?php if (strlen($val_Windate)>0) echo gregorian_to_jalali($val_Windate);?>" size="12" maxlength="10" /></td>
							
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="InvoiceDate" type="text" class="textbox" id="InvoiceDate" readonly value="<?php if (strlen($val_InvoiceDate)>0) echo $val_InvoiceDate;?>" size="12" maxlength="10" /></td>
							
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="CheckDate" type="text" class="textbox" id="CheckDate" readonly value="<?php 
                            
                            echo $CheckDate;
                            
                            ?>" size="12" maxlength="10" /></td>
							
							<td></td>
							<td></td>
						</tr>
						
					
							<tr style="height:40px; font-weight:bold;">
									<td  style="text-align:center; width:5%;  vertical-align:middle; ">رديف</td> 
									<td  style="text-align:center; width:20%;  vertical-align:middle;">شرح عمليات  <?php echo $val_Title.' '.$val_producersTitle; ?></td>
									<td  style="text-align:center; width:15%;  vertical-align:middle;">تولید کننده</td>
									<td  style="text-align:center; width:10%;  vertical-align:middle;">بازرسی</td>
									<td  style="text-align:center; width:10%; <?php echo $hidenazer1;?>  vertical-align:middle;">پیشرفت  زمانی</td>
								<td></td>
								<td></td>
							</tr>					  
				      
						<tr>
							<td class='label'>1</td>
							<td >تاریخ شروع تولید </td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="producedateP" type="text" class="textbox" id="producedateP"  value="<?php if (strlen($val_producedateP)>0) echo gregorian_to_jalali($val_producedateP);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="producedateA" type="text" class="textbox" id="producedateA" value="<?php if (strlen($val_producedateA)>0) echo gregorian_to_jalali($val_producedateA);?>" size="12" maxlength="10" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent5" type="text" class="textbox" id="mpercent5" value="<?php if ($val_s1>0) echo $val_s1;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class='label'>2</td>
							<td>تاریخ شروع تحویل </td> 
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="testdateP" type="text" class="textbox" id="testdateP"  value="<?php if (strlen($val_testdateP)>0) echo gregorian_to_jalali($val_testdateP);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="testdateA" type="text" class="textbox" id="testdateA" value="<?php if (strlen($val_testdateA)>0) echo gregorian_to_jalali($val_testdateA);?>" size="12" maxlength="10" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent6" type="text" class="textbox" id="mpercent6" value="<?php if ($val_s2>0) echo $val_s2;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class='label'>3</td>
							<td>تاریخ تکمیل تحویل </td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="ApproveP" type="text" class="textbox" id="ApproveP"  value="<?php if (strlen($val_ApproveP)>0) echo gregorian_to_jalali($val_ApproveP);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="ApproveA" type="text" class="textbox" id="ApproveA" value="<?php if (strlen($val_ApproveA)>0) echo gregorian_to_jalali($val_ApproveA);?>" size="12" maxlength="10" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent7" type="text" class="textbox" id="mpercent7" value="<?php if ($val_s3>0) echo $val_s3;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
							
						
						</tr>
						<tr>
							<td class='label'>4</td>
							<td>شماره آخرین بارنامه</td>
							<td class="rowtable" <?php echo $hideop;?>><input placeholder="شماره"  name="BOLNO" type="textbox" readonly class="textbox" id="BOLNO"  value="<?php echo $val_BOLNO;?>" size="12" maxlength="12" /></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							
					
						</tr>
						<tr>
							<td class='label'>5</td>
							<td>مجموع وزن باسکولها -تن</td>
							<td class="rowtable"><input placeholder="تن"  name="tonajP" type="textbox"  class="textbox" id="tonajP"  value="<?php echo $val_tonajP ;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input placeholder="تن"  name="tonajA" type="textbox"  class="textbox" id="tonajA" value="<?php echo $val_tonajA;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
							<td></td>
						
						</tr>
						<tr>
							<td class='label'>6</td>
							<td>امتیاز کنترل کیفیت </td>
							<td></td>
							<td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="score1" type="textbox" max="100" min="1" class="textbox" id="score1" value="<?php echo $val_score1;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
							<td></td>
						
						<tr>
							<td class='label'>7</td>
							<td>امتیاز توانمندی تولید </td>
							<td></td>
							<td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="score2" type="textbox" max="100" min="25" class="textbox" id="score2" value="<?php echo $val_score2;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
							<td></td>
						
						</tr>
						<tr>
							<td class='label'>8</td>
							<td>امتیاز تشکیلات سازمانی</td>
							<td></td>
				<td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="score3" type="textbox" max="100" min="25" class="textbox" id="score3" value="<?php echo $val_score3;?>" size="6" maxlength="6" /></td>
														<td></td>
							<td></td>
							<td></td>
						
						</tr>
						<tr>
							<td class='label'>9</td>
							<td>توضیحات</td>
							<td colspan="2" class="rowtable"><input  name="Description" type="text" class="textbox" id="Description" value="<?php echo $val_Description; ?>" size="40" maxlength="50" /></td>
							
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly name="mpercentsum" type="text" class="textbox" id="mpercentsum" value="<?php if ($totalscore>0) echo $totalscore;?>" size="6" maxlength="6" /></td>
							<td></td>
							<td></td>
						
						</tr>
	
						
						
						<?php  $permitrolsid = array("1", "3","10","20","29");
						if ($type==5) $permitrolsid = array("1");
						if (in_array($login_RolesID, $permitrolsid)) {
						?>  <tr> 
                            <td colspan="7">
							<input name="invoicetimingID" type="hidden" value="<?php echo $val_invoicetimingID;?>">
							<input name="ApplicantMasterID" type="hidden" value="<?php echo $ApplicantMasterID;?> ">
							<input name="RolesID" id="RolesID" type="hidden" value="<?php echo $login_RolesID; ?>">
							<input name="userid"  id="userid" type="hidden" value="<?php echo $login_userid; ?>">
							<input name="type"  id="type" type="hidden" value="<?php echo $type; ?>">
						
							<input  name='submit' type='submit' class='button' id='submit' value='ثبت' />
							
							</td></tr>
							<?php } ?>
                     
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colspan="1" id="fooBar">  &nbsp;</span>
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
