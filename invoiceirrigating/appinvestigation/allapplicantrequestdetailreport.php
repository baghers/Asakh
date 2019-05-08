<?php
/*
appinvestigation/allapplicantrequestdetailreport.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/functions.php');

  if ($login_Permission_granted==0) header("Location: ../login.php");
    
if ($login_RolesID=='17') //نقش ناظر مقیم
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";//محدودیت مشاهده شهر ناظر مقیم مربوطه
else if (($login_RolesID=='14'))//ناظر عالی 
        $str.=" and substring(applicantmaster.cityid,1,4) in 
	    (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";//فیلتر مشاهده شهرهای ناظر عالی مربوطه

		  if ($login_RolesID=='10')//نقش مشاور ناظر
            $str.=" and case ifnull(applicantmasterejra.DesignerCoIDnazer,0) 
            when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer 
            else applicantmasterejra.DesignerCoIDnazer end='$login_DesignerCoID'";//فیلتر مشاهده طرح هایی که نظارت آنها برعهده شرکت مشاور ناظر مربوطه است
    /*
    operatorapprequest جدول پیشنهاد قیمت های طرح
    ApplicantMasterID شناسه طرح
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    applicantmaster جدول مشخصات طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    shahrcityname نام شهر
    state برنده شدن یا نشدن
    clerk جدول کاربران
    applicantmaster جدول مشخصات طرح
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    coef1 ضریب اول اجرای طرح
    coef2 ضریب دوم اجرای طرح
    coef3 ضریب سوم اجرای طرح
    */ 
 $sql = "SELECT operatorapprequest.ApplicantMasterID,operatorco.Title operatorcoTitle,
		applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,
		operatorapprequest.Windate,operatorapprequest.costyear,operatorapprequest.coef1,operatorapprequest.coef2,operatorapprequest.coef3,
		operatorapprequest.state,operatorapprequest.C1,operatorapprequest.C2,operatorapprequest.Po,
		operatorapprequest.errors,operatorapprequest.price,operatorapprequest.apval,operatorapprequest.costyear,operatorapprequest.costprice
		,shahr.cityname shahrcityname
		from operatorapprequest
	inner join applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID
    left outer join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
    left outer join applicantmaster applicantmasterejra on applicantmasterejra.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
    left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmasterejra.cityid,1,4) 
    and substring(tax_tbcity7digitnazer.id,5,3)='000'
	inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
   	inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
where 1=1 $str
ORDER BY operatorapprequest.ApplicantMasterID ASC,operatorapprequest.price ASC
			 
	";	
        try 
            {		
                $result = mysql_query($sql);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            }  
     

	$ApplicantMasterIDold=0;//شناسه پیشنهاد قیمت اولیه
	$numUp=0;//تعداد طرح های بالاتر از دامنه قیمت
    $coef3Up=3;//بالاترین ظریب پیشنهاد شده برای یک طرح
    $numLow=0;//تعداد طرح های پایینتر از دامنه
    $coef3Low=0;//پایینترین ظریب پیشنهاد شده برای یک طرح
	$rown=1;//شماره ردیف
    $num=1;//تعداد
    $sumcoef3=0;//مجموع ضرایب سوم پیشنهادات یک طرح
		while($resquery = mysql_fetch_assoc($result))
		{
					
			 if ($ApplicantMasterIDold==$resquery["ApplicantMasterID"])
				{
     			  $sumcoef3old=$priceold[$rown-1]/($resquery["costprice"]*1.3*$resquery["coef2"]);
	    		  $C1[$rown]=$resquery["C1"];$C2[$rown]=$resquery["C2"];$Po[$rown]=$resquery["Po"];
			     if ($C2[$rown]>0)//تعداد طرح های بالاتر از دامنه قیمت
				   if ((floor(100*$resquery["price"]/$Po[$rown]*10)/10)>$C2[$rown])
						{/*if ($resquery["Windate"]>'2014-06-22')*/	
							$numUp++;
							$coef3Upnew=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
							if ($coef3Upnew<$coef3Up) {$avgcoef3Up[$rown]=$coef3Upnew;$coef3Up=$coef3Upnew;}
							$operatorcoTitleEUp[$rown].='*'.$resquery["operatorcoTitle"].'</br>';
						}	
 							
				 if ($C1[$rown]>0)//پایینترین ظریب پیشنهاد شده برای یک طرح
				   if ((floor(100*$resquery["price"]/$Po[$rown]*10)/10)<$C1[$rown])
					   {
							$coef3Lownew=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
							if ($coef3Lownew>$coef3Low) {$avgcoef3Low[$rown]=$coef3Lownew;$coef3Low=$coef3Lownew;}
							$operatorcoTitleELow[$rown].='*'.$resquery["operatorcoTitle"].'</br>';
						}
				 if ($C1[$rown]>0)//محاسبه میانگین ضریب سوم
				   if ((floor(100*$priceold[$rown-1]/$Po[$rown]*10)/10)<$C1[$rown])
					   {
						   if ($sumcoef3old>$coef3Low) {$avgcoef3Low[$rown]=$sumcoef3old;$coef3Low=$sumcoef3old;
								$operatorcoTitleELow[$rown].='*'.$operatorcoTitleold[$rown-1].'</br>';}
					    }
	
	
				   if ($resquery["errors"])//عدم صلاحیت ها 
                    {
                        $errors[$rown].=$resquery["errors"];
                        $operatorcoTitleE[$rown].='*'.$resquery["operatorcoTitle"].'->'.$resquery["errors"].'</br>';
                    }
				   if ($resquery["state"]==1)//وضعیت انتخاب شدن 
                    {
                        $operatorcoTitleB[$rown]=$resquery["operatorcoTitle"];
                        $coef3B[$rown]=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
                        $coef3Apval[$rown]=$resquery["apval"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
                        $Windate[$rown]=$resquery["Windate"];
                    }
					$num++;//تعداد پیشنهادات	
					if (($resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]))<2)
					      $sumcoef3+=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
						else $sumcoef3+=2;
					    
					$avgcoef3[$rown]=($sumcoef3+$sumcoef3old)/$num;// میانگین ضریب سوم
					$ApplicantMasterIDR[$rown]=$resquery["ApplicantMasterID"];//شناسه طرح
					$sumnum[$rown]=$num;//تعداد
					$area[$rown]=$resquery["DesignArea"];//مساحت طرح
					$ApplicantFName[$rown]=$resquery["ApplicantFName"];//نام متقاضی
					$ApplicantName[$rown]=$resquery["ApplicantName"];//عنوان پروژه
					$costyear[$rown]=$resquery["costyear"];//سال فهرست بها
					$costprice[$rown]=$resquery["costprice"];//مبلغ فهرست بها
					$shahrcityname[$rown]=$resquery["shahrcityname"];//شهر
				}
				else
				{
						$operatorcoTitleold[$rown]=$resquery["operatorcoTitle"];//عنوان پیمانکار
						$priceold[$rown]=$resquery["price"];//مبلغ
						$num=1;$sumcoef3=0;$rown++;
						$numUp=0;$coef3Up=3;$numLow=0;$coef3Low=0;
						$ApplicantMasterIDold=$resquery["ApplicantMasterID"];//شناسه طرح
						if ($resquery["errors"]) {//عدم صلاحیت ها
								$errors[$rown].=$resquery["errors"];
								$operatorcoTitleE[$rown].='*'.$resquery["operatorcoTitle"].'->'.$resquery["errors"].'</br>';
							}
						if ($resquery["state"]==1) {//انتخاب شده
								$operatorcoTitleB[$rown]=$resquery["operatorcoTitle"];//پیمانکار
								$coef3B[$rown]=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);//ضریب پیمان
								$coef3Apval[$rown]=$resquery["apval"]/($resquery["costprice"]*1.3*$resquery["coef2"]);//مبلغ
								$Windate[$rown]=$resquery["Windate"];//تاریخ انتخاب
							}
				}
				if ($num==1) $mincoef3[$rown]=$resquery["price"]/($resquery["costprice"]*1.3*$resquery["coef2"]);
		}
	
    mysql_data_seek( $result, 0 );

?>
<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پیشنهاد قیمت طرح</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    

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
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="allapplicantrequestdetailreport.php" method="post">
                
                
                
               <table align='center' class="page" border='1' id="table2">              
               <thead>
	                    
				  <tr> 
                            <td colspan="18"
                            <span class="f14_fontb" >لیست پیشنهاد قیمت های انجام شده  (مبالغ بر حسب میلیون ریال)</span>  
                        
						<?php print "
                        <a  target=\"_blank\"  href='allapplicantrequest_chart.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1'.rand(1000,9999).'1'."'>
						<img style = 'width: 25px' src=\"../img/chart.png\" title='نمودار رتبه بندی ضرایب'></a>
						";?>
                     	</td>
				
			                
				   </tr>
                     
                     <?php
                      //if ($resquery['isbandp']>0) 
						
                        $hideB='display:none';
						$hideUp='display:none';
						$hideLow='display:none';
				        $hideC='display:none';
					
				    //print $login_RolesID;
					if ($login_designerCO==1)
						{$hideB='';$hideUp='';$hideLow='';$hideC='';}
					else if ($login_RolesID==18)
						{$hideB='';$hideUp='';$hideLow='';}
					else if ($login_RolesID==13)
						{$hideB='';$hideBP='';$hideLow='';}
					else if ($login_RolesID==14 || $login_RolesID==17)
						{$hideLow='';$hideBP='';}
					
				  
                      //if ($done>0)
                        echo "<tr>
                            <th colspan=\"7\" class=\"f10_fontb\" >مشخصات طرح</th>
                            <th colspan=\"11\" class=\"f10_fontb\" >مشخصات پیشنهادات</th>
                        </tr>
		
					 <tr>
                            <th class=\"f10_fontb\">ردیف</th>
                            <th class=\"f10_fontb\" >نام</th>
                            <th class=\"f10_fontb\" >نام خانوادگی</th>
                            <th class=\"f10_fontb\" >مساحت</th>
					        <th class=\"f10_fontb\" >شهرستان</th>
					        <th class=\"f10_fontb\" >هزینه اجرا</th>
					        <th class=\"f10_fontb\" >فهرست بها</th>
                            <th class=\"f10_fontb\" >شرکت مجری</th>
						    <th class=\"f10_fontb\" style=$hideBP>ضریب پیشنهادی</th>
						    <th class=\"f10_fontb\" >تاریخ</th>
                            <th class=\"f10_fontb\" style=$hideB>ضریب برنده</th>
							<th class=\"f10_fontb\" style=$hideB>تعداد پیشنهاد</th>
                            <th class=\"f10_fontb\" style=$hideB>کمترین پیشنهاد</th>
                            <th class=\"f10_fontb\" >میانگین ضرایب</th>
                            <th class=\"f10_fontb\" >خارج حدپایین</th>
                            <th class=\"f10_fontb\" >خارج حدبالا</th>
                            <th class=\"f10_fontb\" style=$hideC>کد</th>
                            <th class=\"f10_fontb\" style=$hideB>عدم صلاحیت</th>
                        </tr>
						
			     		";
                     
                ?>  </thead> <?php
	         			
				    
                    
      			//	{if ($tend>'2015-12-22')	$errors.="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.";}

				$srown=$rown;
				$rown=1;$sumnumber=0;
				$row=0;
	       while($resquery = mysql_fetch_assoc($result))
			{
			   if ($rown>=$srown) exit;
			   $rown++;$row++;
                    if ($rown%2==1) 
                    $b='b'; else $b='';
					$sumnumber+= $sumnum[$rown];    

					if ($login_RolesID==18 || $login_designerCO==1) $hideOP='';
					else {
					if ($operatorcoTitleB[$rown]) $hideOP=''; else {$hideOP='style="display:none"';$row--; }
					 }
						  
                      ?>
						<tr <?php echo $hideOP; ?> >
				            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $row; ?></td>
					        <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $ApplicantFName[$rown]; ?></td>
	                         <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $ApplicantName[$rown]; ?></td>
	                        
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $area[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $shahrcityname[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $costprice[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $costyear[$rown]; ?></td>
							 
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl;?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $operatorcoTitleB[$rown]; ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl.';'.$hideBP; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  round($coef3B[$rown],3); ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($Windate[$rown]) echo  gregorian_to_jalali($Windate[$rown]); ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl.';'.$hideB; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  round($coef3Apval[$rown],3); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl.';'.$hideC;; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $sumnum[$rown]; ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl.';'.$hideB; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  round($mincoef3[$rown],3); ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  round($avgcoef3[$rown],3); ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  round($avgcoef3Low[$rown],3).'</br>'; 
							if (!$hideLow)echo $operatorcoTitleELow[$rown]; ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  round($avgcoef3Up[$rown],3).'</br>'; 
							if (!$hideUp)echo $operatorcoTitleEUp[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl.';'.$hideC;  ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $ApplicantMasterIDR[$rown]; ?></td>
					      
							<td  class="f10_font<?php echo $b; ?> ;no-print"  style="color:#<?php echo $cl.';'.$hideB; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  $operatorcoTitleE[$rown]; ?></td>
							
							
						</tr>
						
           <?php 
		//if ($rown==2) print $rown.'<='.$srown;exit;
 
		   }	
		   ?>
			  
                 </table>
				<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
                   
				
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
