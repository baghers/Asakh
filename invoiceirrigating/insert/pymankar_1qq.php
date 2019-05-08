<?php 

/*

insert/pymankar_1qq.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/allapplicantstatesoplist.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>

<?php
//print "erwe";
		include('../includes/functions.php'); 
        $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ids);
        $id=$linearray[0];//شناسه طرح
        $type=$linearray[1];//نوع       
		$applicantstatesID=$linearray[2];//شناسه وضعیت طرح
		
		if ($type==6) $allapplicant='allapplicantstatesoplist'; else $allapplicant='allapplicantstatesop';
//	print	$type;
// $login_RolesID=20;
//print $login_RolesID.'d222';
 if ($_POST)
	{
		
		  $emtiaz=(2*$_POST['plan']+3*$_POST['technical']+3*$_POST['schedule']+4*$_POST['standard']+3*$_POST['equip']+3*$_POST['method']+2*$_POST['rules']+2*$_POST['environ']+1*$_POST['pay']+2*$_POST['doc']+2*$_POST['sug']+3*$_POST['review'])/30;
	
		 $id=$_POST['ApplicantMasterID'];
		 $RoleID = $_POST['timing_RoleID'];
		 $login_RolesID = $_POST['login_RolesID'];//نقش کاربر لاگین شده
		/*
        1 مدیر پیگیری
        13 مدیر آبیاری
        14 ناظر عالی
        18 مدیر آب و خاک
        */
		$arymodir=array('1','13','14','18');
		if(in_array($login_RolesID,$arymodir) || ($login_RolesID=='17'))//17 ناظر مقیم
        
        /*
        applicanttiming جدول زمانبندی
        plan داشتن نظام جامع برنامه ريزي و كنترل پروژه
        technical بكارگيري عوامل فني مجرب و پيمانكاران جزء
        schedule رعايت برنامه زماني
        standard رعايت استانداردها،دستورالعمل ها و مشخصات فني
        equip تجهيز به موقع و كامل كارگاه و به كارگيري ماشين آلات مناسب
        method بكاربستن روشها و سازمان اجرايي مناسب
        rules رعايت دستورالعمل هاي ايمني و حفاظتي كارگاه
        environ رعايت ملاحظات زيست محيطي
        pay پرداخت به موقع دستمزد عوامل كارگاهي و پيمانكاران جزء
        doc طبقه بندي مدارك كارگاهي و مستندسازي
        sug پيشنهادهاي اجرايي،براي بهبود كيفيت و كاهش هزينه هاي اجرايي
        review نظرهاي كلي نسبت به عملكردپيمانكار
        comments نظرهاي كلي در مورد پيمانكار
        emtiaz امتیاز
        */
		$sql="UPDATE applicanttiming SET 
		m_emtiaz='".$_POST['m_emtiaz']."'
		,SaveDate='".date('Y-m-d')."'
		,SaveTime='".date('Y-m-d H:i:s')."'
		,ClerkID='".$_POST['ClerkID']."' 
		where ApplicantMasterID=$id and RoleID=$RoleID
		"; 
       
	  else 
	   
	    $sql="update applicanttiming set 
		 plan='".$_POST['plan']."'
		,technical='".$_POST['technical']."'
		,schedule='".$_POST['schedule']."'
		,standard='".$_POST['standard']."'
		,equip='".$_POST['equip']."'
		,method='".$_POST['method']."'
		,rules='".$_POST['rules']."'
		,environ='".$_POST['environ']."'
		,pay='".$_POST['pay']."'
		,doc='".$_POST['doc']."'
		,sug='".$_POST['sug']."'
		,review='".$_POST['review']."'
		,comments='".$_POST['comments']."'
		,emtiaz='".$emtiaz."'
		,SaveDate='".date('Y-m-d H:i:s')."'
		,SaveTime='".date('Y-m-d')."'
		,ClerkID='".$_POST['ClerkID']."'
		where ApplicantMasterID=$id and RoleID=$RoleID
		";
 
 //print $sql;
		
			  			  	try 
								  {		
									  	$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		$resquery = mysql_fetch_assoc($result);
	   
	}

	   $sql=sql_apptiming($id);
    		$result = mysql_query($sql);
    		$resquery = mysql_fetch_assoc($result);
	   
 
		$LastTotali=$resquery['LastTotali'];//کل هزینه های طرح
		if($resquery['LastTotali']>$resquery['LastTotald']) $LastTotali=$resquery['LastTotald'];
		$ejra=$LastTotali-$resquery['TotlainvoiceValuesi'];
		$LastTotali ='(کل طرح :'.$LastTotali.') '.$ejra;
		
		$SaveDate=gregorian_to_jalali($resquery['SaveDatechange']);


$print='1';
$PermitSubmitRole=array('10','20','21','23');
$aryanjoman=array('20','21','23');
$arymodir=array(1,'13','14','18');

if(in_array($login_RolesID,$aryanjoman))
   $login_fullname = $login_fullname;
else  
    $login_fullname ='';

   $disp="style='display:none'";
   $disp2="style='display:none'";
   $show='';
   $showsubmit='';

if((in_array($login_RolesID,$PermitSubmitRole)) )
	 { $showsubmit=1;$show=1;
	  if($login_RolesID==10)    $timing_RoleID=10;
	                     else   $timing_RoleID=2;
 	 }
 elseif((in_array($login_RolesID,$arymodir)))   
    {$disp="";$disp2="style='display:inline;padding:0 10px''";$showsubmit=1;}

 else {$show='';//echo '<div class="head1">there is error</div>';
		}
	  
		

	   //$timing_RoleID="and (RoleID='10')";
	   $sql1="select * from  applicanttiming where (ApplicantMasterID=".$resquery['ApplicantMasterID'].") and (RoleID='10')    ";
		$sql2="select * from  applicanttiming where (ApplicantMasterID=".$resquery['ApplicantMasterID'].") and (RoleID='2')   ";
		  			  	try 
								  {		
									  	$result1 = mysql_query($sql1);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		
  	   $rows = mysql_fetch_assoc($result1);
	
  			  	try 
								  {		
									  	$result2 = mysql_query($sql2);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	   
  	   $rows2 = mysql_fetch_assoc($result2);
	   
	   
 if(in_array($login_RolesID,$arymodir))
	{ 
		$timing_RoleID=10; $m_emtiaz=$rows['m_emtiaz'];
	}
 elseif($login_RolesID==17)
	{
		$timing_RoleID=2; $showsubmit=1; $m_emtiaz=$rows2['m_emtiaz'];
	}

/*
plan داشتن نظام جامع برنامه ريزي و كنترل پروژه
technical بكارگيري عوامل فني مجرب و پيمانكاران جزء
schedule رعايت برنامه زماني
standard رعايت استانداردها،دستورالعمل ها و مشخصات فني
equip تجهيز به موقع و كامل كارگاه و به كارگيري ماشين آلات مناسب
method بكاربستن روشها و سازمان اجرايي مناسب
rules رعايت دستورالعمل هاي ايمني و حفاظتي كارگاه
environ رعايت ملاحظات زيست محيطي
pay پرداخت به موقع دستمزد عوامل كارگاهي و پيمانكاران جزء
doc طبقه بندي مدارك كارگاهي و مستندسازي
sug پيشنهادهاي اجرايي،براي بهبود كيفيت و كاهش هزينه هاي اجرايي
review نظرهاي كلي نسبت به عملكردپيمانكار
comments نظرهاي كلي در مورد پيمانكار
*/
if(in_array($login_RolesID,$aryanjoman))
	{
		$plan=$rows2['plan'];
    	$technical=$rows2['technical'];
		$schedule=$rows2['schedule'];
		$standard=$rows2['standard'];
		$equip=$rows2['equip'];
		$method=$rows2['method'];
		$rules=$rows2['rules'];
		$environ=$rows2['environ'];
		$pay=$rows2['pay'];
		$doc=$rows2['doc'];
		$sug=$rows2['sug'];
		$review=$rows2['review'];
		$comments=$rows2['comments'];
	}
	   else 
	{
		$plan=$rows['plan'];
    	$technical=$rows['technical'];
		$schedule=$rows['schedule'];
		$standard=$rows['standard'];
		$equip=$rows['equip'];
		$method=$rows['method'];
		$rules=$rows['rules'];
		$environ=$rows['environ'];
		$pay=$rows['pay'];
		$doc=$rows['doc'];
		$sug=$rows['sug'];
		$review=$rows['review'];
		$comments=$rows['comments'];
	}
	   
	   
$sql3=
"SELECT applicantmaster.ApplicantMasterID,Windate FROM applicantmaster
        JOIN operatorapprequest ON applicantmaster.ApplicantMasterID = operatorapprequest.ApplicantMasterID WHERE (BankCode = '".$resquery['BankCode']."') AND (DesignerCoID >0)
        AND(operatorapprequest.operatorcoid = ".$resquery['operatorcoid'].") AND(`state` = '1')";
    		
						  	try 
								  {		
									  	$result3 = mysql_query($sql3);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		$windat = mysql_fetch_assoc($result3);
	
	
?>


<!DOCTYPE html>
<html>
<head>
  	<title>ارزشیابی</title>

		
	
	<style>
	
	#header{width: 100%; position: relative; z-index: 1; color: white; padding:0 80px 0; height:100px;}
	#footer{width: 1200px; color: #999; padding: 10px 0; text-align: center; clear:both; font-size: 12px;}

#dvpymankar
{
	 padding:10px 30px 10px 0;
 
  direction:rtl;
  font-family:tahoma,arial;
  font-size:13px;
  width:95%;
  float:right;
 
}
.head1,.head3
{
  padding:10px 30px 10px 0;
  font-weight:bold;direction:rtl;
}
.head2
{ 
 padding:0 30px 0 0;
}
.part
{
 padding: 0 30px;
}
.part .row  ,.rowh
{
  padding:5px 0 ;
}
.part .rowh
{
  font-weight:bold;
}
.part .lbl
{
 
}
.part .txt
{
  color:blue;
  padding: 0 10px 0 10px;
}
.part .blnk
{
  padding:0 40px;
 
}
a
{
  text-decoration:none;
}
.part table
{
  border:1px;
  width:100%;
  text-align:center;
}
.bady
{
	
 font-size:12px;
}
.requir
{
   color:red;
   float:right;
  width: 10px; 
 
   
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
			
		<div id="dvpymankar" style="page-break-after: always">
  <div class="head1">پيوست دستورالعمل ارزشيابي پيمانكاران
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 
	  <a href=<?php print "../appinvestigation/".$allapplicant.".php"?>><img style = "width: 25px;" src="../img/Return.png" title='بازگشت' ></a>
	</div>					 
						
  <fieldset>
    <div class="head2">پرسشنامه ارزيابي خدمات پيمانكار</div>
	<div class="head3">فرم 1) ارزيابي توسط دستگاه نظارت (مشاور)</div>
		<div class="part">
	 <div class="rowh">
	    <span class="lbl">1- مشخصات پروژه: اجراي سيستم آبياري  </span>
		<span class="txt"><?php echo $resquery['designsystemgroupsTitle']; ?></span>
		<span class="lbl">اراضي  </span>
		<span class="txt"><?php echo $resquery['ApplicantFName'].' '.$resquery['ApplicantName']; ?></span>
	  </div> 
	  <div class="row">
	    <span class="lbl">1-1- كد و عنوان طرح :  </span>
		<span class="txt"><?php echo $resquery['creditsourcetitle']; ?></span>
	  </div>
      <div class="row">
	    <span class="lbl">1-2- كد و عنوان پروژه : </span>
		<span class="txt"><?php echo $resquery['Bankcode'].' - اجراي سيستم آبياري '.$resquery['designsystemgroupsTitle']; ?></span>
	  </div>
     <div class="row">
	    <span class="lbl">1-3- محل اجراي پروژه : </span>
		<span class="txt"><?php echo $resquery['Ostan'].' - '.$resquery['CityName']; ?></span>
	  </div> 	 	   
   </div>
   <div class="hr"><hr></div>
   <div class="part">
	  <div class="rowh">
	    <span class="lbl">2- مشخصات دستگاه اجرايي :  </span>
	  </div> 
	  <div class="row">
	    <span class="lbl">1-2- عنوان دستگاه اجرايي : سازمان جهادكشاورزي </span>
		<span class="txt"><?php echo $resquery['Ostan']; ?></span>
	  </div>
      <div class="row">
	    <span class="lbl">2-2- مجري طرح / پروژه : </span>
		<span class="txt"><?php echo $resquery['operatorcoTitle'].'('.$resquery['ApplicantFName'].' '.$resquery['ApplicantName'].')'; ?></span>
	  </div>
	  
   </div>
   <div class="hr"><hr></div>
   <div class="part">
	  <div class="rowh">
	    <span class="lbl">3- مشخصات دستگاه نظارت (مشاور) :  </span>
	  </div> 
	  <div class="row">
	    <span class="lbl">1-3- نام مشاور :  </span>
		<span class="txt"><?php echo $resquery['nazercoTitle']; ?></span>
	    <span class="lbl">2-3- رشته و رتبه : </span>
		<span class="txt"><?php echo 'مجوز دفتر سامانه هاي نوين آبياري- رتبه '.$resquery['descorank']; ?></span>
	  </div>
   </div>
    <div class="hr"><hr></div>
   <div class="part">
	  <div class="rowh">
	    <span class="lbl">4- مشخصات پيمانكار :  </span>
	  </div> 
	  <div class="row">
	    <span class="lbl">1-4- نام پيمانكار :  </span>
		<span class="txt"><?php echo $resquery['operatorcoTitle']; ?></span>
	    <span class="lbl">2-4- رشته و رتبه : </span>
		<span class="txt"><?php echo 'مجوز دفتر سامانه هاي نوين آبياري- رتبه '.$resquery['opcorank']; ?></span>
	  </div> 
   </div>
   <div class="hr"><hr></div>
   <div class="part">
	  <div class="rowh">
	    <span class="lbl">5- مشخصات قرارداد :  </span>
	  </div> 
	  <div class="row">
	    <span class="lbl">1-5- موضوع قرارداد:اجراي سيستم آبياري </span>
		<span class="txt"><?php echo $resquery['designsystemgroupsTitle']; ?></span>
		<span class="lbl">اراضي  </span>
		<span class="txt"><?php echo $resquery['ApplicantFName'].' '.$resquery['ApplicantName']; ?></span>
		<span class="lbl">كد:  </span>
		<span class="txt"><?php echo $resquery['applicantstatesID']; ?></span>
		
	 </div>
	 <div class="row">
	    <span class="lbl">2-5- شماره و تاريخ عقد قرارداد: </span>
		<span class="txt"><?php echo gregorian_to_jalali($windat['Windate']); ?></span>
		<span class="lbl">تاريخ تحويل زمين :  </span>
		<span class="txt"><?php echo gregorian_to_jalali($rows['tahvildate']); ?></span>
	 </div>
	 <div class="row">
	    <span class="lbl">3-5- مبلغ قرارداد:</span>
		<span class="txt"><?php echo $LastTotali.' ميليون ريال'; ?></span>
		<span class="lbl">4-5- زمان خاتمه قرارداد :  </span>
		<span class="txt"><?php echo $SaveDate ?></span>
	 </div>
	 <div class="row">
	    <span class="lbl">5-5- درصد پيشرفت فيزيكي هنگام تكميل فرم:</span>
		<span class="txt"><?php echo '100%'; ?></span>
	 </div>
	 <div class="row">
	    <span class="lbl">نام تكميل كننده فرم:</span>
		<span class="txt"><?php echo $login_fullname; ?></span>
		<span class="lbl">امضاء:</span>
		<span class="blnk"></span>
		<span class="lbl">تاريخ:</span>
		<span class="txt"><?php echo gregorian_to_jalali($rows['SaveDate']); ?></span>
	 </div>
	 
	 <div class="row">
	    <span class="lbl">نام مديرعامل:</span>
		<span class="txt">
		   <?php if(in_array($login_RolesID,$aryanjoman))
		           echo 'بازرس';
		         else
        		   echo $resquery['modir'];
			?>
		 </span>
		<span class="lbl">امضاء:</span>
		<span class="blnk"></span>
		<span class="lbl">تاريخ:</span>
		<span class="txt"></span>
	 </div>
	 </div>
	 <div class="hr"><hr></div>
	 <div class="part">
	 <div class="row">
	    <span class="lbl"><a href="<?php  ?>">فرم 1) صفحه 1 از 2</a></span>
		<span class="txt" style="float:left"><?php
		$phone='';
		 if($resquery['Phone']!='0')
		  $phone.=$resquery['Phone']; 
		 if($resquery['Phone2']!='0')
		  $phone.=' '.$resquery['Phone2']; 
		echo $phone ; ?></span>
		<span class="lbl" style="float:left">تلفن تماس:  </span>
	 </div>
	 </div>
	  <div class="hr"><hr></div>
	  </fieldset>
  
   </div>

 
 <form action="pymankar_1qq.php" method="post" enctype="multipart/form-data">
  
<div id="dvpymankar">
  <div class="head1">پيوست دستورالعمل ارزشيابي پيمانكاران</div>
  <fieldset>
    <div class="head2">پرسشنامه ارزيابي خدمات پيمانكار</div>
	<div class="part">
	 <div class="rowh">
	    <span class="lbl">عنوان پروژه:اجراي سيستم آبياري   </span>
		<span class="txt"><?php echo $resquery['designsystemgroupsTitle']; ?></span>
		<span class="lbl">اراضي  </span>
		<span class="txt"><?php echo $resquery['ApplicantFName'].' '.$resquery['ApplicantName']; ?></span>
	  </div> 
	 <div class="head3">فرم 1) ارزيابي توسط دستگاه نظارت (مشاور)</div>
     <div>
	 <input type="hidden" name="timing_RoleID" value="<?php echo $timing_RoleID; ?>" >
	 <input type="hidden" name="login_RolesID" value="<?php echo $login_RolesID; ?>" >
	   <table border="1">
	     <tr class="hed">
		   <td >رديف</td>
		   <td>عناصركيفيت</td>
		   <td>امتياز (0-100)'</td>
		   <td <?php echo $disp;?>>امتياز (0-100)''</td>
		   <td>ضريب وزني (b)</td>
		 </tr>
		 <tr class="bady">
		  <td>1</td>
		   <td>داشتن نظام جامع برنامه ريزي و كنترل پروژه</td>
		   <td><?php if($show==1) echo "<input name='plan' id='plan' type='text' value='$plan'/>"; else echo $rows['plan']; ?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['plan']; ?></td>
		   <td>2</td>
		 </tr>
		 <tr class="bady">
		  <td >2</td>
		   <td>بكارگيري عوامل فني مجرب و پيمانكاران جزء</td>
		   <td><?php if($show==1) echo "<input name='technical' id='technical' type='text' value='$technical'/>"; else echo $rows['technical']; ?></td>
		   <td <?php echo $disp;?>><?php    echo $rows2['technical']; ?></td>
		   <td>3</td>
		 </tr>
		 <tr class="bady">
		  <td>3</td>
		   <td>رعايت برنامه زماني *</td>
		   <td><?php if($show==1) echo "<input name='schedule' id='schedule' type='text' value='$schedule'/>"; else echo $rows['schedule']; ?></td>
		   <td <?php echo $disp;?>><?php    echo $rows2['schedule']; ?></td>
		   <td>3</td>
		 </tr>
		 <tr class="bady">
		  <td>4</td>
		   <td>رعايت استانداردها،دستورالعمل ها و مشخصات فني</td>
		   <td><?php if($show==1) echo "<input name='standard' id='standard' type='text' value='$standard'/>"; else echo $rows['standard'];?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['standard'];?></td>
		   <td>4</td>
		 </tr>
		 <tr class="bady">
		  <td>5</td>
		   <td>تجهيز به موقع و كامل كارگاه و به كارگيري ماشين آلات مناسب</td>
		   <td><?php if($show) echo "<input name='equip' id='equip' type='text' value='$equip'/>"; else echo $rows['equip'];?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['equip'];?></td>
		   <td>3</td>
		 </tr>
		 <tr class="bady">
		  <td>6</td>
		   <td>بكاربستن روشها و سازمان اجرايي مناسب</td>
		   <td><?php if($show) echo "<input name='method' id='method' type='text' value='$method'/>"; else echo $rows['method'];?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['method'];?></td>
		   <td>3</td>
		 </tr>
		 <tr class="bady">
		  <td>7</td>
		   <td>رعايت دستورالعمل هاي ايمني و حفاظتي كارگاه</td>
		   <td><?php if($show) echo "<input name='rules' id='rules' type='text' value='$rules'/>"; else echo $rows['rules'];?></td>
		   <td <?php echo $disp;?>><?php   echo $rows2['rules'];?></td>
		   <td>2</td>
		 </tr>
		 <tr class="bady">
		  <td>8</td>
		   <td>رعايت ملاحظات زيست محيطي</td>
		   <td><?php if($show) echo "<input name='environ' id='environ' type='text' value='$environ'/>"; else echo $rows['environ'];?></td>
		   <td <?php echo $disp;?>><?php   echo $rows2['environ'];?></td>
		   <td>2</td>
		 </tr>
		 <tr class="bady">
		  <td>9</td>
		   <td>پرداخت به موقع دستمزد عوامل كارگاهي و پيمانكاران جزء</td>
		   <td><?php if($show) echo "<input name='pay' id='pay' type='text' value='$pay'/>"; else echo $rows['pay'];?></td>
		   <td <?php echo $disp;?>><?php   echo $rows2['pay'];?></td>
		   <td>1</td>
		 </tr>
		 <tr class="bady">
		  <td>10</td>
		   <td>طبقه بندي مدارك كارگاهي و مستندسازي</td>
		   <td><?php if($show) echo "<input name='doc' id='doc' type='text' value='$doc'/>"; else echo $rows['doc'];?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['doc'];?></td>
		   <td>2</td>
		 </tr>
		 <tr class="bady">
		  <td>11</td>
		  
		  
		  
		   <td>پيشنهادهاي اجرايي،براي بهبود كيفيت و كاهش هزينه هاي اجرايي</td>
		   <td><?php if($show) echo "<input name='sug' id='sug' type='text' value='$sug'/>";else echo $rows['sug']; ?></td>
		   <td <?php echo $disp;?>><?php   echo $rows2['sug']; ?></td>
		   <td>2</td>
		 </tr>
		 <tr class="bady">
		  <td>12</td>
		   <td>نظرهاي كلي نسبت به عملكردپيمانكار**</td>
		   <td><?php if($show) echo "<input name='review' id='review' type='text' value='$review'/>";else echo $rows['review'];?></td>
		   <td <?php echo $disp;?>><?php  echo $rows2['review'];?></td>
		   <td>3</td>
		 </tr>
		 <tr class="bady">
		   <td colspan="2">**نظرهاي كلي در مورد پيمانكار</td>
		   <td colspan="1"><?php if($show) echo "<textarea id='comments' name='comments' rows='2'  cols='50' >$comments</textarea>" ;else 
		   echo $rows['comments']; ?>
		   <td colspan="2" <?php echo $disp;?>><?php   echo $rows2['comments']; ?>
		   </td>
		 </tr>
		<input type="hidden" value="<?php echo $login_userid; ?>" name="ClerkID">
		<input type="hidden" value="<?php echo $resquery['ApplicantMasterID']; ?>" name="ApplicantMasterID">
		
	   </table>
	<?php if(in_array($login_RolesID,$PermitSubmitRole) ){?>
		 <tr class="bady">
		   <td colspan="5" align="right">
		   <?php if($showsubmit && $type<>6) echo "<input name='updat' id='updat' type='submit' value='ثبت'/>"; ?></td>
		 </tr>
		 <?php }?>
	   
	 </div> 	
   </div>
   <?php if($print==1){ ?>
		   <div class="part">
			 <div class="row">
				<span class="lbl">*اظهارنظر در رابطه با رعايت برنامه زماني،بايد با درنظرگرفتن آثار منفي ناشي اخير از عملكرد كارفرما در زمينه ايفاي به موقع تعهدات،مانند تأخير در تحويل زمين،پرداختها و ابلاغ دستور كارها،انجام شود.</span>
			 </div>
				
		  </div>
		  <div class="hr"><hr></div>
		  <div class="part">
		   <div class="row">
				<span class="lbl" >aibi /∑bi∑ </span>
				<span class="lbl" style="padding-right:20px">امتیاز ارزشیابی مشاور ناظر'=</span>
				<span class="txt"><?php echo ''.$rows['emtiaz']; ?></span>
				
				<span class="lbl"> بازرس''=</span>
				<span class="txt" <?php echo $disp;?>><?php echo ''.$rows2['emtiaz']; ?></span>
		   
			 <?php if(in_array($login_RolesID,$arymodir) || ($login_RolesID=='17') ){?>
				 <tr class="bady">
				   <td colspan="2">امتیاز کلی ارزشیابی مدیریت آب و خاک استان/شهرستان=</td>
				   		<span class="txt"><?php if ($login_RolesID!='17') echo ''.$rows2['m_emtiaz']; ?></span>
		
				   <td colspan="1" align="right">
					 <input  type="text" name="m_emtiaz" id="m_emtiaz" value="<?php echo $m_emtiaz; ?>" style="border-color:orange;">
					 
				   <?php if($applicantstatesID==35) $type=6;//echo "<input name='m_emtiaz' id='m_emtiaz' value='$rows[m_emtiaz]'/>";?>
				   <?php if($showsubmit && $type<>6) echo "<input name='updat' id='updat' type='submit' value='ثبت'/>";  ?></td>
				 </tr>
				 <?php }?>
				
				 
			</div>
			
			
		  </div>
		  <div class="hr"><hr></div>
		  <div class="part">
		   <div class="row">
				<span class="lbl">نام تكميل كننده فرم:</span>
				<span class="txt"><?php echo $login_fullname; ?></span>
				<span class="lbl">امضاء:</span>
				<span class="blnk"></span>
				<span class="lbl">تاريخ:</span>
				<span class="txt"><?php echo gregorian_to_jalali($rows['SaveDate']); ?></span>
			 </div>
		  </div>
		  <div class="hr"><hr></div>
		  <div class="part">
		  <div class="row">
				<span class="lbl">نام مديرعامل:</span>
				<span class="txt">
				   <?php if(in_array($login_RolesID,$aryanjoman))
						   echo 'بازرس';
						 else
						   echo $resquery['modir'];
					?>
				 </span>
				<span class="lbl">امضاء:</span>
				<span class="blnk"></span>
				<span class="lbl">تاريخ:</span>
				<span class="txt"></span>
		  </div>
		  </div>
		  <div class="part">
			 <div class="row">
				<span class="lbl"><a href="">فرم 1) صفحه 2 از 2</a></span>
			 </div>
			 </div>
			  <div class="hr"><hr></div>
	  <?php } ?>
  </fieldset>
</div>
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
