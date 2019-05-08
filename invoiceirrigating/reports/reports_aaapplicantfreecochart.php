<?php 
/*
reorts/reports_aaapplicantfreecochart.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: home.php");
$larraytype=explode("~", $_POST['strallval']);
$shown=$larraytype[1];
$sum=$larraytype[2];
$larray=explode("_", $larraytype[0]);
	$i=0;$j=0;
		while($larray[$j])
		{ $i++;    
          $operatorcotitle[$i]=$larray[$j++];
		  $Price[$i]=$larray[$j++];
		  $producersTitle[$i]=$larray[$j++];
		  $productTitle[$i]=$larray[$j++];
		  $PriceP[$i]=round(100*($Price[$i]/$sum),2);
		  

	if ($Price[$i]<=250000000 && $Price[$i]>0) {$price25+=$Price[$i];$num25++;}
	if ($Price[$i]<=500000000 && $Price[$i]>250000000) {$price50+=$Price[$i];$num50++;}
	if ($Price[$i]<=1000000000 && $Price[$i]>500000000) {$price100+=$Price[$i];$num100++;}
	if ($Price[$i]<=2000000000 && $Price[$i]>1000000000) {$price200+=$Price[$i];$num200++;}
	if ($Price[$i]<=3000000000 && $Price[$i]>2000000000) {$price300+=$Price[$i];$num300++;}
	if ($Price[$i]<=4000000000 && $Price[$i]>3000000000) {$price400+=$Price[$i];$num400++;}
	if ($Price[$i]<=5000000000 && $Price[$i]>4000000000) {$price500+=$Price[$i];$num500++;}
	if ($Price[$i]<=10000000000 && $Price[$i]>5000000000) {$price1000+=$Price[$i];$num1000++;}
	if ($Price[$i]<=50000000000 && $Price[$i]>10000000000) {$price5000+=$Price[$i];$num5000++;}
	}


$sumps=$sum;
//print $sum.'*'.$shown;exit;
$larray=explode("_", $larraytype[0]);
//$larray=explode("_", $_POST['strallval']);
//print_r($larray);exit;
	$i=0;$j=0;
		while($larray[$j])
		{ $i++;    
	              $operatorcoTitle[$i]=$larray[$j++];
				  $corank[$i]=$larray[$j++];
				  $projectcountdone[$i]=$larray[$j++];
				  $projecthektardone[$i]=$larray[$j++];
				  $simultaneouscnt[$i]=$larray[$j++];
				  $thisyearprgarea[$i]=$larray[$j++];
				  $projectcountdone92[$i]=$larray[$j++];
				  $projecthektardone92[$i]=$larray[$j++];
				
	    		  $projectcountdoneP[$i]=round(100*($projectcountdone[$i]/$sum),2);
				  $projecthektardoneP[$i]=round(100*($projecthektardone[$i]/$sum),2);
				  $simultaneouscntP[$i]=round(100*($simultaneouscnt[$i]/$sum),2);
				  $thisyearprgareaP[$i]=round(100*($thisyearprgarea[$i]/$sum),2);
				  $projectcountdone92P[$i]=round(100*($projectcountdone92[$i]/$sum),2);
				  $projecthektardone92P[$i]=round(100*($projecthektardone92[$i]/$sum),2);
	   }
	   
if ($shown==1 || $shown==2 || $shown==3 || $shown==4 || $shown==5 || $shown==6 ){	   
	$percent=0;
	$k=$i;
	for($i=1;$i<=$k;$i++)	 
       {  
				if ($shown==1) $percent=$projectcountdoneP[$i];
				if ($shown==2) $percent=$projecthektardoneP[$i];
				if ($shown==3) $percent=$simultaneouscntP[$i];
				if ($shown==4) $percent=$thisyearprgareaP[$i];
				if ($shown==5) $percent=$projectcountdone92P[$i];
				if ($shown==6) $percent=$projecthektardone92P[$i];
				
	              $operatorcoTitles.="'".$operatorcoTitle[$i].' %'.$percent."',";
				  $coranks.="'".$corank[$i]."',";
				  $projectcountdones.=$projectcountdone[$i].",";
				  $projecthektardones.=round($projecthektardone[$i],1).",";
				  $simultaneouscnts.=$simultaneouscnt[$i].",";
				  $thisyearprgareas.=round($thisyearprgarea[$i],1).",";
	     		  $projectcountdone92s.=$projectcountdone92[$i].",";
				  $projecthektardone92s.=round($projecthektardone92[$i],1).",";

					//print $operatorcoTitle[$i].'_'.$corank[$i].'_'.$projectcountdone[$i].'_'.$projecthektardone[$i].'_'.$simultaneouscnt[$i].'_'.$thisyearprgarea[$i].'_'.$projectcountdone92[$i].'_'.$projecthektardone92[$i].'<br>';
	   }
} 
else if ($shown==7 || $shown==8){
		
$condition="";
//$condition="and substring(clerk.CityId,1,2)=19";

if ($login_Domain=='rkh')
 	$condition="and substring(clerk.CityId,1,2)=19";
	else if ($login_Domain=='yazd')
 	$condition="and substring(clerk.CityId,1,2)=77";
	else if ($login_Domain=='nkh')
 	$condition="and substring(clerk.CityId,1,2)=21";
    else if ($login_Domain=='skh')
 	$condition="and substring(clerk.CityId,1,2)=31";

//sumprice قیمت پیشنهادی پیمانکار
//sumcostprice قیمت اجرا طرح مطالعاتی
//sumcostprice*1.3 قیمت اجرای طرح فهرست بهایی
//sumapval قیمت تایید ناظر عالی

}		
if ($shown==8) { 
			$operatorcoTitles="";	   
			$array_sum10 = array_sum($avgpmreq10);$array_sum10P = round(100*($array_sum10/$sum),2);
			$array_sum20 = array_sum($avgpmreq20);$array_sum20P = round(100*($array_sum20/$sum),2);
			$array_sum30 = array_sum($avgpmreq30);$array_sum30P = round(100*($array_sum30/$sum),2);
			$array_sum40 = array_sum($avgpmreq40);$array_sum40P = round(100*($array_sum40/$sum),2);
			$array_sum50 = array_sum($avgpmreq50);$array_sum50P = round(100*($array_sum50/$sum),2);
			$array_sum00 = $sum-$array_sum50-$array_sum40-$array_sum30-$array_sum20-$array_sum10;$array_sum00P = round(100*($array_sum00/$sum),2);
			$pishnahad=$array_sum00P.",".$array_sum10P.",".$array_sum20P.",".$array_sum30P.",".$array_sum40P.",".$array_sum50P;

			$array_sum10ps = array_sum($avgpmreq10ps);$array_sum10Pps = round(100*($array_sum10ps/$sumps),2);
			$array_sum20ps = array_sum($avgpmreq20ps);$array_sum20Pps = round(100*($array_sum20ps/$sumps),2);
			$array_sum30ps = array_sum($avgpmreq30ps);$array_sum30Pps = round(100*($array_sum30ps/$sumps),2);
			$array_sum40ps = array_sum($avgpmreq40ps);$array_sum40Pps = round(100*($array_sum40ps/$sumps),2);
			$array_sum50ps = array_sum($avgpmreq50ps);$array_sum50Pps = round(100*($array_sum50ps/$sumps),2);
			$array_sum00ps = $sumps-$array_sum50ps-$array_sum40ps-$array_sum30ps-$array_sum20ps-$array_sum10ps;$array_sum00Pps = round(100*($array_sum00ps/$sumps),2);
			$pishnahadps=$array_sum00Pps.",".$array_sum10Pps.",".$array_sum20Pps.",".$array_sum30Pps.",".$array_sum40Pps.",".$array_sum50Pps;

			$operatorcoTitles="'<10%  =(".$array_sum00.")','10-20%  =(".$array_sum10.")','20-30%  =(".$array_sum20.")','30-40%  =(".$array_sum30.")','40-50%  =(".$array_sum40.")','>50%  =(".$array_sum50.")'";
			$height='400px';$titr='از نظر درصد مینوس پیشنهادهای داده شده';$xaxis='(درصد مینوس(تعداد';
}
else if ($shown==7){
			$operatorcoTitles=rtrim($operatorcoTitles,',');
			$pishnahads=rtrim($pishnahads,',');
			$height='2400px';$titr='از نظر مبلغ مینوس پیشنهادهای داده شده';$xaxis='(درصد مینوس(تعداد';
}
else {
	

			$operatorcoTitles=rtrim($operatorcoTitles,',');
			$coranks=rtrim($coranks,',');
			$projectcountdones=rtrim($projectcountdones,',');
			$projecthektardones=rtrim($projecthektardones,',');
			$simultaneouscnts=rtrim($simultaneouscnts,',');
			$thisyearprgareas=rtrim($thisyearprgareas,',');
			$projectcountdone92s=rtrim($projectcountdone92s,',');
			$projecthektardone92s=rtrim($projecthektardone92s,',');
			$height='2400px';		
            
}

		
		
		
		?>                    


  
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>وضعیت پیمانکاران</title>

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
		       renderTo: 'container',
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                stops: [
                    [0, 'rgb(255, 255, 255)'],
                    [1, 'rgb(200, 200, 255)']
                ]
        },
	  
            type: 'bar'
        },
		
        title: {
 text: ' نمودار وضعیت شرکتهای پیمانکار  <?php echo $titr;?>' 		

        },
        subtitle: {
            text: ''
        },
			
        xAxis: {
            categories: [<?php echo $operatorcoTitles; ?>],
            title: {
                text: '<?php echo $xaxis;?>'
            }
        },
        yAxis: {
            min: 0,
            title: {
            <?php if ($shown==1 || $shown==3 || $shown==5) { ?>  text: 'تعداد',  align: 'high'  <?php } ?>
			<?php if ($shown==2 || $shown==4 || $shown==6) { ?>  text: '(مساحت (هکتار',  align: 'high'  <?php } ?>
			<?php if ($shown==8 ) { ?>  text: '%درصد',  align: 'high'  <?php } ?>
			<?php if ($shown==7 ) { ?>  text: 'میلیون ریال',  align: 'high'  <?php } ?>
			
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' '
        },
        plotOptions: {
            bar: {
                  dataLabels: {
                enabled: true,
                align: 'left',
                inside: false,
                overflow: 'crop',
                crop: false,
                style: {
                    color: '#0000Fb',
                    fontWeight: '',
                    fontSize: '9px',
                    textShadow: '0px 0px 3px white'
                }
            }
			
			 
          
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -1,
            y: 20,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
<?php if ($shown==1) { ?> name: 'تعداد پروژه های انجام داده   ', data: [<?php echo $projectcountdones; ?>] <?php } ?>	
<?php if ($shown==2) { ?> name: 'مساحت پروژه های انجام داده ', data: [<?php echo $projecthektardones; ?>] <?php } ?>	
<?php if ($shown==3) { ?> name: 'تعداد پروژه های درحال انجام', data: [<?php echo $simultaneouscnts; ?>] <?php } ?>	
<?php if ($shown==4) { ?> name: 'مساحت پروژه های درحال انجام', data: [<?php echo $thisyearprgareas; ?>] <?php } ?>	
<?php if ($shown==5) { ?> name: 'تعداد پروژه های انجام داده قبل از 92', data: [<?php echo $projectcountdone92s; ?>] <?php } ?>	
<?php if ($shown==6) { ?> name: 'مساحت پروژه های انجام داده قبل از 92', data: [<?php echo $projecthektardone92s; ?>] <?php } ?>	
<?php if ($shown==7) { ?> name: 'تخفیف مبلغ پیشنهادی نسبت به پیشنهاد برنده', data: [<?php echo $pishnahads; ?>] <?php 

} ?>

<?php if ($shown==8) { ?> name: 'کلاسه بندی پیشنهادات برنده شده', data: [<?php echo $pishnahad; ?>] <?php 
				  echo "}, {"; ?> name: 'کلاسه بندی پیشنهادات پیمانکاران پیشنهاد دهنده', data: [<?php echo $pishnahadps; ?>] , color: '#9dcebb'<?php 
} ?>	

        }]
		
		
		
    });
});
		</script>
	</head>
	<body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; max-width: 800px; height: <?php echo $height; ?>; margin: 0 auto"></div>

<?php if ($shown==7 || $shown==8) { ?>
             <!-- content -->
            <br />
            <br />
            <br />

                 <table BORDER="1" width="100%" style="text-align: center;font-size:14.0pt;font-family:'B Nazanin';">
                    <tr>
				    <td ><font style="line-height:14px;color:#0000FF; text-align:center;font-size:14pt;font-family:'B Nazanin';">
                           <p><b><?php echo $text1.'='.$sumcostprice_price.'م ریال' ?> </b></p> </font>
	                        <?php// echo round(100*($sumcostprice_price/$sumapval),1).'%' ?> </b> </font>
							 </td>
					<td ><font style="line-height:14px;color:#0000FF; text-align:center;font-size:14pt;font-family:'B Nazanin';">
                           <p><b><?php echo $text2.'='.$sumprice_apval.'م ریال' ?> </b></p> </font>
	                        <?php// echo round(100*($sumcostprice_price/$sumapval),1).'%' ?> </b> </font>
							 </td>

				    </tr>
<?php } ?>
	</body>
	
</html>



















<!DOCTYPE HTML>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<style type="text/css">
${demo.css}

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }



		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'وضعيت پروژه ها'
        },
        subtitle: {
            text: 'وضعيت پيشنهاد قيمت ها'
        },
        xAxis:
            {
            categories: [
			    'تعداد پروژه هاي در حال اجرا',
                'تعداد پيشنهادات انجام شده',
                'تعداد برنده انتخاب شده',
                'تعداد شركت پيشنهاد دهنده',
                'تعداد شركت مجاز'
				
            ]
			
        },
        yAxis: {
            min: 0,
            title: {
                text: 'تعداد '
            }
        },
        tooltip: {
            headerFormat: '<span class="f10_font" >{point.key}</span><table>',
            pointFormat: '<tr><span class="f10_font" ><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y: 1f} پروژه</b></td></span></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: ' ',
            data: [		 
			<?php echo $_POST['sum3'];?>,
		
			<?php echo $_POST['sum5'];?>, 
			<?php echo $_POST['sum6'];?>,
			<?php echo $_POST['npishnahad'];?>,
			<?php echo $_POST['nmojavez1'];?>
			]


        }]
    });
});
		</script>
	</head>
	<body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

	</body>
</html>
