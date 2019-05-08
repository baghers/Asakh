<?php 
/*
appinvestigation/allapplicantrequestdetailreportpipe.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/functions.php');

  if ($login_Permission_granted==0) header("Location: ../login.php");
    
if ($login_RolesID=='17') //نقش ناظر مقیم
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";//محدودیت مشاهده شهر ناظر مقیم مربوطه
else if (($login_RolesID=='14')) //ناظر عالی 
        $str.=" and substring(applicantmaster.cityid,1,4) in 
	    (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";//فیلتر مشاهده شهرهای ناظر عالی مربوطه
  //print $login_DesignerCoID;
  if ($login_RolesID=='10')//نقش مشاور ناظر
            $str.=" and case ifnull(applicantmaster.DesignerCoIDnazer,0) 
            when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer 
            else applicantmaster.DesignerCoIDnazer end='$login_DesignerCoID'";//فیلتر مشاهده طرح هایی که نظارت آنها برعهده شرکت مشاور ناظر مربوطه است
            
    /*
    producerapprequest جدول پیشنهاد قیمت های طرح
    ApplicantMasterID شناسه طرح
    producers جدول تولیدکنندگان
    producers.Title عنوان تولیدکنندگان
    ProducersID شناسه تولیدکنندگان
    applicantmaster جدول مشخصات طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    shahrcityname نام شهر
    */ 		
 $sql = "SELECT producerapprequest.*,producers.Title operatorcoTitle,
		applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea
		,shahr.cityname shahrcityname
		from producerapprequest
	inner join applicantmaster on applicantmaster.ApplicantMasterID=producerapprequest.ApplicantMasterID
    left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmaster.cityid,1,4) 
    and substring(tax_tbcity7digitnazer.id,5,3)='000'
	inner join producers on producers.ProducersID=producerapprequest.ProducersID
   	inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
    inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
    and substring(shahr.id,3,5)<>'00000'
where 1=1 $str
ORDER BY Windate,producerapprequest.ApplicantMasterID ASC,producerapprequest.price ASC
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
	
	
				   if ($resquery["errors"]) {
								$errors[$rown].=$resquery["errors"];
								$operatorcoTitleE[$rown].='*'.$resquery["operatorcoTitle"].'->'.$resquery["errors"].'</br>';
							}
				   if ($resquery["state"]==1) {
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
					$PE32[$rown]=$resquery["PE32"];
					$PE40[$rown]=$resquery["PE40"];
					$PE80[$rown]=$resquery["PE80"];
					$PE100[$rown]=$resquery["PE100"];
					$PE32app[$rown]=$resquery["PE32app"];
					$PE40app[$rown]=$resquery["PE40app"];
					$PE80app[$rown]=$resquery["PE80app"];
					$PE100app[$rown]=$resquery["PE100app"];
					$price[$rown]=$resquery["price"];
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
				$operatorcoTitleBT[$rown].=$resquery["operatorcoTitle"].' ';
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
            
            <form action="allapplicantrequestdetailreportpipe.php" method="post">
                
                
                
               <table align='center' class="page" border='1' id="table2">              
               <thead>
	                    
				  <tr> 
                            <td colspan="20"
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
                            <th colspan=\"13\" class=\"f10_fontb\" >مشخصات پیشنهادات</th>
                        </tr>
		
					 <tr>
                            <th class=\"f10_fontb\">ردیف</th>
                            <th class=\"f10_fontb\" >نام</th>
                            <th class=\"f10_fontb\" >نام خانوادگی</th>
                            <th class=\"f10_fontb\" >مساحت</th>
					        <th class=\"f10_fontb\" >شهرستان</th>
					        <th class=\"f10_fontb\" >مبلغ پیشنهادی</th>
                            <th class=\"f10_fontb\" >شرکت تولید کننده</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق پیشنهادی PE32</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق پیشنهادی PE40</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق پیشنهادی PE80</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق پیشنهادی PE100</th>
						    <th class=\"f10_fontb\" >تاریخ</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق برنده PE32</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق برنده PE40</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق برنده PE80</th>
						    <th class=\"f10_fontb\" style=$hideBP>ق برنده PE100</th>
                        
						
			     		";
                        if ($login_RolesID!='10')
                        print "
							<th class=\"f10_fontb\" >تعداد پیشنهاد</th>
							<th class=\"f10_fontb\" >پیشنهاددهندگان</th>
                            <th class=\"f10_fontb\" style=$hideC>کد</th>
                            <th class=\"f10_fontb\" style=$hideB>عدم صلاحیت</th>";
                        print "</tr>";    
                     
                ?>  </thead> <?php
	         			
				    
                    
      			//	{if ($tend>'2015-12-22')	$errors.="<br>*دردامنه متناسب پیشنهاد قیمت قرار ندارد.";}

				$srown=$rown;
				$rown=1;$sumnumber=0;
				$row=0;$rows=0;
	       while($resquery = mysql_fetch_assoc($result))
			{
			   if ($rown>=$srown) exit;
   
			   $rown++;$row++;
  				if ($Windate[$row]==0) continue;			 
$rows++;
                    if ($rown%2==1) 
                    $b='b'; else $b='';
					$sumnumber+= $sumnum[$rown];    

					if ($login_RolesID==18 || $login_designerCO==1) $hideOP='';
					else {
					if ($operatorcoTitleB[$rown]) $hideOP=''; else {$hideOP='style="display:none"';$row--; }
					 }
						  
                      ?>
						<tr <?php echo $hideOP; ?> >
				            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rows; ?></td>
					        <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $ApplicantFName[$rown]; ?></td>
	                         <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $ApplicantName[$rown]; ?></td>
	                        
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $area[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $shahrcityname[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $price[$rown]; ?></td>
							 
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl;?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $operatorcoTitleB[$rown]; ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  number_format($PE32[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  number_format($PE40[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  number_format($PE80[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  number_format($PE100[$rown]); ?></td>
							
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($Windate[$rown]) echo  gregorian_to_jalali($Windate[$rown]); ?></td>
							
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php  if ($PE32app[$rown]>0) echo number_format($PE32app[$rown]); else echo number_format($PE32[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($PE40app[$rown]>0) echo number_format($PE40app[$rown]); else echo number_format($PE40[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($PE80app[$rown]>0) echo number_format($PE80app[$rown]); else echo number_format($PE80[$rown]); ?></td>
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php if ($PE100app[$rown]>0) echo number_format($PE100app[$rown]); else echo number_format($PE100[$rown]); ?></td>
							
                            <?php
                            if ($login_RolesID!='10')
                            print " 
                            <td class='f10_font$b'  style='color:#$cl;text-align: center;font-size:8.0pt;font-family:'B Nazanin';'>$sumnum[$rown]</td>
							<td class='f8_font$b'  style='color:#$cl;text-align: center;font-size:8.0pt;font-family:'B Nazanin';'>$operatorcoTitleBT[$rown]</td>
							<td class='f10_font$b'  style='color:#$cl;$hideC;text-align: center;font-size:8.0pt;font-family:'B Nazanin';'>$ApplicantMasterIDR[$rown]</td>
							<td class='f10_font$b'  style='color:#$cl;$hideB;text-align: center;font-size:8.0pt;font-family:'B Nazanin';'>$operatorcoTitleE[$rown]</td>
							";
                            
                            ?>
							
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
