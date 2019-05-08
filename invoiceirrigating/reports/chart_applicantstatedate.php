<?php 
/*
reorts/chart_applicantstatedate.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php

$uid=$_GET["uid"];
$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);

$appcnt=$linearray[0];
$DesignArea=$linearray[1];
$shahrcityname=$linearray[2];
$ApplicantName=$linearray[3];
if ($ApplicantName<>'')
$appname=" $ApplicantName شهرستان $shahrcityname $DesignArea هکتار";

   
            
          	        
            
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار پيشرفت زماني پيش فاكتورهاي اجرايي</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		.highcharts-title {
		font-family:'B lotus';
		font-size:25px;
		}
		#container {
		border:1px dotted #ccc;
		}
		
		</style>
		
		
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        
        tooltip: {
            pointFormat: '% <b>{point.percentage:.1f}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 60,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'پيش فاكتور اجرايي',
            data: [
            
            <?php
            $i1=5;
            $sum=0;
            while ($linearray[$i1]<>'')
            {
             
                print "['".$linearray[$i1]." ".round($linearray[$i1+1]/$appcnt)."',".round($linearray[$i1+1]/$appcnt)."]";   
                $sum+=round($linearray[$i1+1]/$appcnt);
                $i1+=2;
                if ($linearray[$i1]<>'') print ",";
            }
             ?>
               
            ]
        }]
        ,title: {
            text: 'نمودار پيشرفت زماني پيش فاكتورهاي اجرايي  <?php echo 'طرح '.$appname.$sum
			;?> روز'
        }
    });
});
		</script>
	</head>
<body >
		
            
                        
         
				  

<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="height: 400px"></div>
	</body>
</html>
                 