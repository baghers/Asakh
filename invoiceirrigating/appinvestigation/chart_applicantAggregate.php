<?php 
/*

//appinvestigation/chart_applicantAggregate.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/allapplicantquotaaggregated.php
 -
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php


$larray=explode("_", $_POST['strallval']);

		$i=0;
		$j=0;
			while($larray[$j])
		{     
		//print $larray[$i]."<br>";
	              $i++;
				  $arrCity[$i]= $larray[$j++];
				  $arrProgress[$i]= $larray[$j++];
    }
	
        ?>                    
		

		
		

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>نمودار پيشرفت طرح هاي سامانه های نوین آبیاری</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
		.highcharts-title {
		font-size:14pt;
		font-family:'B Titr';
		}
		
		.highcharts-subtitle {
		font-size:14pt;
		font-family:'B Nazanin';
		}
		
		
		
		#container {
		border:1px dotted #ccc;
		}
		                        
}

		
		</style>
		<script type="text/javascript">


		
	$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'گزارش عملکرد طرح های سامانه های نوین آبیاری <?php print $yearvalue."<br>".$login_CityName;?> '
        },
        subtitle: {
            text: 'درصد پیشرفت پروژه نسبت به سهمیه هر شهرستان'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Tahoma, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'درصد'

            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'درصد پیشرفت پروژه:% <b>{point.y:.1f} </b>'
        },
        series: [{
            name: 'درصد پیشرفت پروژه',
            data: [
			
			
				['  <?php echo "".$arrCity[1]."  "; ?>',   <?php echo $arrProgress[1]; ?>],
				['  <?php echo "".$arrCity[2]."  "; ?>',   <?php echo $arrProgress[2]; ?>],
				['  <?php echo "".$arrCity[3]."  "; ?>',   <?php echo $arrProgress[3]; ?>],
				['  <?php echo "".$arrCity[4]."  "; ?>',   <?php echo $arrProgress[4]; ?>],
				['  <?php echo "".$arrCity[5]."  "; ?>',   <?php echo $arrProgress[5]; ?>],
				['  <?php echo "".$arrCity[6]."  "; ?>',   <?php echo $arrProgress[6]; ?>],
				['  <?php echo "".$arrCity[7]."  "; ?>',   <?php echo $arrProgress[7]; ?>],
				['  <?php echo "".$arrCity[8]."  "; ?>',   <?php echo $arrProgress[8]; ?>],
				['  <?php echo "".$arrCity[9]."  "; ?>',   <?php echo $arrProgress[9]; ?>],
				['  <?php echo "".$arrCity[10]."  "; ?>',   <?php echo $arrProgress[10]; ?>],
				['  <?php echo "".$arrCity[11]."  "; ?>',   <?php echo $arrProgress[11]; ?>],
				['  <?php echo "".$arrCity[12]."  "; ?>',   <?php echo $arrProgress[12]; ?>],
				['  <?php echo "".$arrCity[13]."  "; ?>',   <?php echo $arrProgress[13]; ?>],
				['  <?php echo "".$arrCity[14]."  "; ?>',   <?php echo $arrProgress[14]; ?>],
				['  <?php echo "".$arrCity[15]."  "; ?>',   <?php echo $arrProgress[15]; ?>],
				['  <?php echo "".$arrCity[16]."  "; ?>',   <?php echo $arrProgress[16]; ?>],
				['  <?php echo "".$arrCity[17]."  "; ?>',   <?php echo $arrProgress[17]; ?>],
				['  <?php echo "".$arrCity[18]."  "; ?>',   <?php echo $arrProgress[18]; ?>],
				['  <?php echo "".$arrCity[19]."  "; ?>',   <?php echo $arrProgress[19]; ?>],
				['  <?php echo "".$arrCity[20]."  "; ?>',   <?php echo $arrProgress[20]; ?>],
				['  <?php echo "".$arrCity[21]."  "; ?>',   <?php echo $arrProgress[21]; ?>],
				['  <?php echo "".$arrCity[22]."  "; ?>',   <?php echo $arrProgress[22]; ?>],
				['  <?php echo "".$arrCity[23]."  "; ?>',   <?php echo $arrProgress[23]; ?>],
				['  <?php echo "".$arrCity[24]."  "; ?>',   <?php echo $arrProgress[24]; ?>],
				['  <?php echo "".$arrCity[25]."  "; ?>',   <?php echo $arrProgress[25]; ?>],
				['  <?php echo "".$arrCity[26]."  "; ?>',   <?php echo $arrProgress[26]; ?>],
				['  <?php echo "".$arrCity[27]."  "; ?>',   <?php echo $arrProgress[27]; ?>],
				['  <?php echo "".$arrCity[28]."  "; ?>',   <?php echo $arrProgress[28]; ?>],
				['  <?php echo "".$arrCity[29]."  "; ?>',   <?php echo $arrProgress[29]; ?>]
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: -15, // 10 pixels down from the top
                style: {
                    fontSize: '12px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
});
	

		
		
		
		
		</script>
	</head>
	<body>

<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="height: 400px"></div>
	</body>
</html>
                 