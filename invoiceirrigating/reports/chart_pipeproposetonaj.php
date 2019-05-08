<?php 
/*
reorts/chart_pipeproposetonaj.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php


 		$sump=chartpipe_sqlpa(1);
        $s0=0;
        $s1=0;
        foreach ( $sump as $key => $valuenum ) {
		if ($valuenum) {
		  //print_r($valuenum);echo $valuenum[0].'<br>';
          if (!($valuenum[0]>0)) $valuenum[0]=0;$s0+=$valuenum[0];
          if (!($valuenum[1]>0)) $valuenum[1]=0;$s1+=$valuenum[1];
		}
        }
        print $s0.'<br>';
        print $s1;
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
                $data1.=round(($valuenum[0]/$s0)*100,1);
                $data2.=round(($valuenum[1]/$s1)*100,1);
              }
                else 
              {
                $categories.=",'$key'";
                $data1.=",".round(($valuenum[0]/$s0)*100,1);
                $data2.=",".round(($valuenum[1]/$s1)*100,1);
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
        text: 'نمودار تناژ تولیدی قبل و بعد از فرآیند پیشنهاد قیمت'
    },
xAxis: {
            categories: [
			<?php echo $categories; ?>]
        },
        
    yAxis: [{
        className: 'highcharts-color-0',
            min:0,
            max:50,
        title: {
            text: 'تن'
        }
    }, {
        className: 'highcharts-color-1',
            min:0,
            max:50,
        opposite: true,
        title: {
            text: 'تن'
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




	