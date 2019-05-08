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
