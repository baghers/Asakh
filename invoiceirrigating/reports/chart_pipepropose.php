<?php 
/*
reorts/chart_pipepropose.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php


 		$sump=chartpipe_sqlpa(0);
        
		$categories='';
        $data1='';
        $data2='';
        $maxv=0;
		$i=1;
		foreach ( $sump as $key => $valuenum ) {
		if ($valuenum) {
		  //print_r($valuenum);echo $valuenum[0].'<br>';
          if (!($valuenum[0]>0)) $valuenum[0]=0;
          if (!($valuenum[1]>0)) $valuenum[1]=0;
		      if ($valuenum[0]>$maxv) $maxv=$valuenum[0];
		      if ($valuenum[1]>$maxv) $maxv=$valuenum[1];
		      if ($i==1)
              {
                $categories.="'$key'";
                $data1.=$valuenum[0];
                $data2.=$valuenum[1];
              }
                else 
              {
                $categories.=",'$key'";
                $data1.=",".$valuenum[0];
                $data2.=",".$valuenum[1];
              }
				$i++;	
			}
		}
		//print $categories;exit;	
	
//print 'sa';
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
        type: 'column'
    },

    title: {
        text: 'نمودار مبلغ تولیدی قبل و بعد از فرآیند پیشنهاد قیمت'
    },
xAxis: {
            categories: [
			<?php echo $categories; ?>]
        },
        
    yAxis: [{
        className: 'highcharts-color-0',
            min:0,
            max:<?php echo $maxv; ?>,
        title: {
            text: 'میلیون ریال'
        }
    }, {
        className: 'highcharts-color-1',
            min:0,
            max:<?php echo $maxv; ?>,
        opposite: true,
        title: {
            text: 'میلیون ریال'
        }
    }],

    plotOptions: {
        column: {
            borderRadius: 5
        }
    },

    series: [{
        name: 'قبل',
    	color:'orange',
        data: [<?php echo $data1;?>]
    },  {
        name: 'بعد',
    	color:'green',
        data: [<?php echo $data2;?>],
        yAxis: 1
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




	