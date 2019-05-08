<?php 
/*

//appinvestigation/chart_product_timing.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/product_timing.php
 -
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php

if ($_GET) 
{
	$uid=$_GET["uid"];
	$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	$linearray = explode('_',$ids);
    //print_r($linearray);
    
    $val_ApplicantFName=$linearray[0];//نام متقاضی
    $val_ApplicantName=$linearray[1];//عنوان پروژه
    $val_DesignArea=$linearray[2];//مساحت
    $val_shahrcityname=$linearray[3];//شهر
    $val_operatorcoTitle=$linearray[4];//مجری
    $val_DesignerCotitle=$linearray[5];//طراح
    $D1=$linearray[6];
    $D2=$linearray[7];
    $D3=$linearray[8];
    $D4=$linearray[9];
    $D5=$linearray[10];
    $D6=$linearray[11];
    $D7=$linearray[12];
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار جدول زمانبندي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		g.highcharts-axis-labels {
		font-family:tahoma;
		}
		g.highcharts-axis-labels text tspan {
		font-size:9px;
		}
		text.highcharts-title tspan  {
		font-family:'B Nazanin';
		font-size:25px;
		}
		text.highcharts-subtitle tspan {
		font-family:'B Nazanin';
		font-size:20px;
		}
		text.highcharts-yaxis-title tspan {
		font-family:'B Nazanin';
		font-size:18px;
		direction:rtl;
		}
		</style>
		<script type="text/javascript">
$(function () {

    $('#container').highcharts({

        chart: {
            type: 'columnrange',
            inverted: true
        },

        title: {
            text: 'نمودار پیشنهادی زمانبندي طرح آبياري آقاي <?php  print 
            $val_ApplicantFName.' ' .$val_ApplicantName .' '. $val_DesignArea .' هكتار ' . 'شهرستان '. $val_shahrcityname 
            . 'پیمانکار '. $val_operatorcoTitle .'<br/><br/>'.
               "".'مشاور بازرس کنترل کیفیت : '.$val_DesignerCotitle; ?>'
        },


        subtitle: {
            text: ' '
        },

        xAxis: {
            categories: [
			
		'پیشنهاد قیمت', 'واریز وجه', 'فرایند تولید' , 'تولید کالا (تولیدکننده)', 'تولید کالا (بازرس)', 'تحویل کالا (تولیدکننده)', 'تحویل کالا (بازرس)']
        },

        yAxis: {
            title: {
                text: 'تعداد روز'
            }
        },

        tooltip: {
		            valueSuffix: '',
			enabled:true
        },

        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
					
					     return ;
                    }
                }
            }
        },

        legend: {
            enabled: false
        },

        series: [{
            name: 'مدت زمان انجام كار',
            data: [
			
                [<?php echo 0;?>,<?php echo $D1;?>],
                [<?php echo $D1;?>,<?php echo $D1+$D2;?>],
                [<?php echo $D1+$D2;?>,<?php echo $D1+$D2+$D3;?>],
                [<?php echo $D1+$D2;?>,<?php echo $D1+$D2+$D4;?>],
                [<?php echo $D1+$D2;?>,<?php echo $D1+$D2+$D5;?>],
                [<?php echo $D1+$D2+$D5;?>,<?php echo $D1+$D2+$D5+$D6;?>],
                [<?php echo $D1+$D2+$D5;?>,<?php echo $D1+$D2+$D5+$D7;?>]
				
            ]
        }]
		

    });

});
		</script>
	</head>
	<body>
<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-more.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="min-width: 350px; height: 550px; margin: 0 auto"></div>

	</body>
</html>




	