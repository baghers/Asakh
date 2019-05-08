<?php
/*
Chart Version 2011/09/01 - 1390/06/10
*/

//===============================================Class Begin============================================================
class Chart 
{
	public $arrayNamey;
	public $type=2;
 	public $Path='tmpchart.html';
 	public $XMLPath='chart/sample.xml';
 	public $ChartTitle;
 	public $Valuegauge;
 	public $Valuegauge2;
 	public $Titlegauge;
 	public $Titlegauge2;
 	public $Unit;
 	public $numDecimals;
 	public $minimum;
 	public $maximum;
 	public $major_interval;
 	public $minor_interval;
 	public $XNum;
 	public $YNum;
	public $arrayTitle;
	public $SeriesName;
	private $ProjectRoot='/invoiceirrigating';
	
 	
    //=========================================private Properties=========================================================
    //=========================================protect Properties=========================================================
    //=========================================Public Properties==========================================================
    //=======================================Class Constructor & Destructor==================================================
  	function __construct()
  	{  		
  	}
  	//-------------------------------------------Method Separator---------------------------------------------------------
  	function __destruct()
  	{
  	}
  	//-------------------------------------------Method Separator---------------------------------------------------------
  	public function CreateHtmlFile()
  	{ 
  		$arrayNamey=$this->arrayNamey;
  		$type=$this->type;
  		$Path=$this->Path;
  		$ChartTitle=$this->ChartTitle;
  		$XMLPath=$this->XMLPath;
  		$numDecimals=$this->numDecimals;
 		$minimum=$this->minimum;
 		$maximum=$this->maximum;
 		$major_interval=$this->major_interval;
 		$minor_interval=$this->minor_interval;
 		$arrayTitle=$this->arrayTitle;
 		$ProjectRoot=$this->ProjectRoot;
 	
		if ($type==1)//مودار ميله
		{
			// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
        
            
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$charts = $doc->createElement('charts');
	  		$charts = $root->appendChild($charts);
	  	
	  		$chart = $doc->createElement('chart');
	  		$chart = $charts->appendChild($chart);
	  		$chart->setAttribute('plot_type', 'CategorizedVertical');
	  	
	  		$data_plot_settings = $doc->createElement('data_plot_settings');
	  		$data_plot_settings = $chart->appendChild($data_plot_settings);
	  		$data_plot_settings->setAttribute('default_series_type', 'Bar');
	  		$data_plot_settings->setAttribute('enable_3d_mode', 'true');
	  		$data_plot_settings->setAttribute('z_aspect', '0.5');
	  	
	  		$bar_series = $doc->createElement('bar_series');
	  		$bar_series = $data_plot_settings->appendChild($bar_series);
	  		$bar_series->setAttribute('group_padding', '0.2');
	  	
	  		$label_settings = $doc->createElement('label_settings');
	  		$label_settings = $bar_series->appendChild($label_settings);
	  		$label_settings->setAttribute('enabled', 'true');
	  		$label_settings->setAttribute('rotation', '45');
	  	
	  		$tooltip_settings = $doc->createElement('tooltip_settings');
	  		$tooltip_settings = $bar_series->appendChild($tooltip_settings);
	  		$tooltip_settings->setAttribute('enabled', 'true');
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $chart->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  		$title->setAttribute('enabled', 'true');
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$axes = $doc->createElement('axes');
	  		$axes = $chart_settings->appendChild($axes);
	  		$axes->setAttribute('enabled', 'false');
	
	
	  		$x_axis = $doc->createElement('x_axis');
	  		$x_axis = $axes->appendChild($x_axis);
	
	  		$labels = $doc->createElement('labels');
	  		$labels = $x_axis->appendChild($labels);
	  		$labels->setAttribute('rotation', '30');
	  	
	  		$data = $doc->createElement('data');
	  		$data = $chart->appendChild($data);
	
	  		$series = $doc->createElement('series');
	  		$series = $data->appendChild($series);
	  		$series->setAttribute('name', 'Series 1');
	  		$series->setAttribute('palette', 'Default');

			
		
			$i=1;
			while(strlen($arrayNamey[$i]['name'])>0)
	    	{
	    		
	  			$point["$i"] = $doc->createElement('point');
	  			$point["$i"] = $series->appendChild($point["$i"]);
	  			$point["$i"]->setAttribute('name', $arrayNamey[$i]['name']);
	  			$point["$i"]->setAttribute('y', $arrayNamey[$i]['y']);
	  			$i++;
	    	}
	    		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
			
		}  	
	  	else if ($type==2)//گيج سه رنگ
	  	{
	  
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$circular = $doc->createElement('circular');
	  		$circular = $gauge->appendChild($circular);
	  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $circular->appendChild($axis);
	  		$axis->setAttribute('radius', '37');
	  		$axis->setAttribute('start_angle', '85');
	  		$axis->setAttribute('sweep_angle', '190');
	  		$axis->setAttribute('size', '3');
	  		
	  	
	  		$scale = $doc->createElement('scale');
	  		$scale = $axis->appendChild($scale);
	  		$scale->setAttribute('minimum', $minimum);
	  		$scale->setAttribute('maximum', $maximum);
	  		$scale->setAttribute('major_interval', $major_interval);
	  		$scale->setAttribute('minor_interval', $minor_interval);
	  	
	  	
	  		$labels = $doc->createElement('labels');
	  		$labels = $axis->appendChild($labels);
	  		$labels->setAttribute('align', 'Outside');
	  		$labels->setAttribute('padding', '6');
	  	
	  		$format = $doc->createElement('format');
	  		$format = $labels->appendChild($format);
	  		$value = $doc->createTextNode('  {%Value}{numDecimals:0}');
	  		$value = $format->appendChild($value);  	
	  	
	  		$scale_bar = $doc->createElement('scale_bar');
	  		$scale_bar = $axis->appendChild($scale_bar);
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $scale_bar->appendChild($fill);
	  		$fill->setAttribute('color', '#292929');
	  	
	  		$major_tickmark = $doc->createElement('major_tickmark');
	  		$major_tickmark = $axis->appendChild($major_tickmark);
	  		$major_tickmark->setAttribute('align', 'Center');
	  		$major_tickmark->setAttribute('length', '10');
			$major_tickmark->setAttribute('padding', '0');
	  	
	  		$minor_tickmark = $doc->createElement('minor_tickmark');
	  		$minor_tickmark = $axis->appendChild($minor_tickmark);
	  		$minor_tickmark->setAttribute('enabled', 'false');
	  	
	  		$color_ranges = $doc->createElement('color_ranges');
	  		$color_ranges = $axis->appendChild($color_ranges);
	  	
			/////////////////////////////////////color_range Red/////////////////////////////////// 
			$i=1;
			while(strlen($arrayNamey[$i]['end'])>0)
	    	{
	  			$color_range["$i"] = $doc->createElement('color_range');
	  			$color_range["$i"] = $color_ranges->appendChild($color_range["$i"]);
	  			$color_range["$i"]->setAttribute('start', $arrayNamey[$i]['start']);
	  			$color_range["$i"]->setAttribute('end', $arrayNamey[$i]['end']);
	  			$color_range["$i"]->setAttribute('align', 'Inside');
	  			$color_range["$i"]->setAttribute('start_size', '60');
	  			$color_range["$i"]->setAttribute('end_size', '60');
	  			$color_range["$i"]->setAttribute('padding', '6');
	  			$color_range["$i"]->setAttribute('color', $arrayNamey[$i]['color']);
		    	
	  			$border["$i"] = $doc->createElement('border');
	  			$border["$i"] = $color_range["$i"]->appendChild($border["$i"]);
	  			$border["$i"]->setAttribute('enabled', 'true');
	  			$border["$i"]->setAttribute('color', 'Black');
	  			$border["$i"]->setAttribute('opacity', '0.4');
		    	
	  			$label["$i"] = $doc->createElement('label');
	  			$label["$i"] = $color_range["$i"]->appendChild($label["$i"]);
	  			$label["$i"]->setAttribute('enabled', 'true');
	  			$label["$i"]->setAttribute('align', 'Inside');
	  			$label["$i"]->setAttribute('padding', '34');
	
	  			$format = $doc->createElement('format');
	  			$format = $label["$i"]->appendChild($format);
	  			$value = $doc->createTextNode($arrayNamey[$i]['Title']);
	  			$value = $format->appendChild($value); 	
		    	
	  			$position["$i"] = $doc->createElement('position');
	  			$position["$i"] = $label["$i"]->appendChild($position["$i"]);
	  			$position["$i"]->setAttribute('valign', 'Center');
	  			$position["$i"]->setAttribute('halign', 'Center');    
		    	
	  			$font["$i"] = $doc->createElement('font');
	  			$font["$i"] = $label["$i"]->appendChild($font["$i"]);
	  			$font["$i"]->setAttribute('bold', 'true');
	  			$font["$i"]->setAttribute('size', '11');   	
		    	
	  			$fill = $doc->createElement('fill');
	  			$fill = $color_range["$i"]->appendChild($fill);
	  			$fill->setAttribute('opacity', '0.6');
	  		
			  $i++;
	    	}
			
	  		//////////////////////////////////////////////////////////////////// 
	  	
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $circular->appendChild($pointers);
	  	
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  	
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  	
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '50');
	  		$position->setAttribute('y', '15');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode($this->Unit."  {%Value}{numDecimals:$numDecimals}");
	  		$value = $format->appendChild($value); 	  	
	  	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');
	
	  		$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  		$needle_pointer_style = $pointer->appendChild($needle_pointer_style);
	  		$needle_pointer_style->setAttribute('thickness', '7');  
	  		$needle_pointer_style->setAttribute('point_thickness', '5');  
	  		$needle_pointer_style->setAttribute('point_radius', '3');  	
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $needle_pointer_style->appendChild($fill);
	  		$fill->setAttribute('color', 'Rgb(230,230,230)');  	
	
	  		$border = $doc->createElement('border');
	  		$border = $needle_pointer_style->appendChild($border);
	  		$border->setAttribute('color', 'Black');  
	  		$border->setAttribute('opacity', '0.7');  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $needle_pointer_style->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '2');  
	  		$bevel->setAttribute('shadow_opacity', '0.6');  
	  		$bevel->setAttribute('highlight_opacity', '0.6');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '1');  
	  		$drop_shadow->setAttribute('blur_y', '1');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	
	  		$cap = $doc->createElement('cap');
	  		$cap = $needle_pointer_style->appendChild($cap);
	
	  		$background = $doc->createElement('background');
	  		$background = $cap->appendChild($background);
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');
	  	
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  		$gradient->setAttribute('type', 'Linear');
	  		$gradient->setAttribute('angle', '45');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('color', '#D3D3D3');
	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('color', '#6F6F6F');
	  	
	  		$border = $doc->createElement('border');
	  		$border = $background->appendChild($border);
	  		$border->setAttribute('color', 'Black');
	  		$border->setAttribute('opacity', '0.9');
	  	
	  		$effects = $doc->createElement('effects');
	  		$effects = $cap->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '2');  
	  		$bevel->setAttribute('shadow_opacity', '0.6');  
	  		$bevel->setAttribute('highlight_opacity', '0.6');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1.5');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	  	
	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '0.5');  
	  		$animation->setAttribute('interpolation_type', 'Bounce');    	
	  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
	
		}
		else if ($type==3)//گيج ساده'
	  	{
	  
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$circular = $doc->createElement('circular');
	  		$circular = $gauge->appendChild($circular);
	  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $circular->appendChild($axis);
	  		$axis->setAttribute('radius', '47');
	  		$axis->setAttribute('start_angle', '20');
	  		$axis->setAttribute('sweep_angle', '320');
	  	
	  		$scale = $doc->createElement('scale');
	  		$scale = $axis->appendChild($scale);
	  		$scale->setAttribute('minimum', $minimum);
	  		$scale->setAttribute('maximum', $maximum);
	  		$scale->setAttribute('major_interval', $major_interval);
	  		$scale->setAttribute('minor_interval', $minor_interval);
	  	
	  		$color_ranges = $doc->createElement('color_ranges');
	  		$color_ranges = $axis->appendChild($color_ranges);
	  	
	  		$color_range = $doc->createElement('color_range');
	  		$color_range = $color_ranges->appendChild($color_range);
	  		$color_range->setAttribute('start', 160*$maximum/210);
	  		$color_range->setAttribute('end', 200*$maximum/210);
	  		$color_range->setAttribute('start_size', 0);
	  		$color_range->setAttribute('end_size', 15);
	  		$color_range->setAttribute('align', "Inside");
	  		$color_range->setAttribute('padding', 35);
	  		
	  		$fill = $doc->createElement('fill');
	  		$fill = $color_range->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');
	  	
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  	
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Green');
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Yellow');
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Red');
	  		
	  		$border = $doc->createElement('border');
	  		$border = $color_range->appendChild($border);
	  		$border->setAttribute('enabled', 'true');
	  		$border->setAttribute('color', 'Gray');
	  		$border->setAttribute('opacity', '0.3');
			//////////////////////////////////////////////////////////////////// 
	  	
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $circular->appendChild($pointers);
	  	
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('type', 'Needle');
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  	
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  		$label->setAttribute('under_pointers', 'true');
	  	
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '50');
	  		$position->setAttribute('y', '35');
	  		$position->setAttribute('valign', 'Center');
	  		$position->setAttribute('halign', 'Center');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode($this->Unit."  {%Value}{numDecimals:$numDecimals}");
	  		$value = $format->appendChild($value); 	  	
	  	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');
	  		$fill->setAttribute('angle', '90');    	
	
	  		$key = $doc->createElement('key');
	  		$key = $fill->appendChild($key);
	  		$key->setAttribute('color', 'Rgb(200,200,200)');
	  		$key = $doc->createElement('key');
	  		$key = $fill->appendChild($key);
	  		$key->setAttribute('color', 'Rgb(255,255,255)');
	  		$key = $doc->createElement('key');
	  		$key = $fill->appendChild($key);
	  		$key->setAttribute('color', 'Rgb(200,200,200)');
	
	
	  		$border = $doc->createElement('border');
	  		$border = $background->appendChild($border);
	  		$border->setAttribute('enabled', 'true'); 
	  		$border->setAttribute('color', 'Gray');  
	  		$border->setAttribute('opacity', '0.8');  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $background->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	  		
	  		$inner_shadow = $doc->createElement('inner_shadow');
	  		$inner_shadow = $effects->appendChild($inner_shadow);
	  		$inner_shadow->setAttribute('enabled', 'true');
	  		$inner_shadow->setAttribute('distance', '2');
	  		$inner_shadow->setAttribute('blur_x', '2');
	  		$inner_shadow->setAttribute('blur_y', '2');
	  		$inner_shadow->setAttribute('opacity', '0.3');
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'false');
	
	  		$corners = $doc->createElement('corners');
	  		$corners = $background->appendChild($corners);
	  		$corners->setAttribute('type', 'Rounded');
	  		$corners->setAttribute('all', '5');
	
	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '1');  
	  		$animation->setAttribute('interpolation_type', 'Elastic');    	
	  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
	
		}
		else if ($type==4)//گیج و دماسنج'
	  	{
	  
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$circular = $doc->createElement('circular');
	  		$circular = $gauge->appendChild($circular);
	  		$circular->setAttribute('name', 'Main');
	  	
	  		$styles = $doc->createElement('styles');
	  		$styles = $circular->appendChild($styles);
	  		
	  		$color_range_style = $doc->createElement('color_range_style');
	  		$color_range_style = $styles->appendChild($color_range_style);
	  		$color_range_style->setAttribute('name', 'anychart_default');
	  		$color_range_style->setAttribute('align', 'Center');
	  		$color_range_style->setAttribute('start_size', '4');
	  		$color_range_style->setAttribute('end_size', '4');
	  	
		  	$fill = $doc->createElement('fill');
	  		$fill = $color_range_style->appendChild($fill);
	  		$fill->setAttribute('type', 'Solid');
	  		$fill->setAttribute('color', '%Color');
	  		$fill->setAttribute('opacity', '0.7');
	  	
		  	$border = $doc->createElement('border');
	  		$border = $color_range_style->appendChild($border);
	  		$border->setAttribute('enabled', 'true');
	  		$border->setAttribute('color', 'Black');
	  		$border->setAttribute('opacity', '0.15');
	  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $circular->appendChild($axis);
	  		$axis->setAttribute('radius', '50');
	  		$axis->setAttribute('start_angle', '0');
	  		$axis->setAttribute('sweep_angle', '180');
	  	
	  		$scale = $doc->createElement('scale');
	  		$scale = $axis->appendChild($scale);
	  		$scale->setAttribute('minimum', $minimum);
	  		$scale->setAttribute('maximum', $maximum);
	  		$scale->setAttribute('major_interval', $major_interval);
	  		
	  		$scale_bar = $doc->createElement('scale_bar');
	  		$scale_bar = $axis->appendChild($scale_bar);
	  		$scale_bar->setAttribute('enabled', 'false');
	  		
	  		$labels = $doc->createElement('labels');
	  		$labels = $axis->appendChild($labels);
	  		$labels->setAttribute('enabled', 'true');
	  		$labels->setAttribute('align', 'Inside');
	  		$labels->setAttribute('rotate_circular', false);
	  		
	  		$font = $doc->createElement('font');
	  		$font = $labels->appendChild($font);
	  		$font->setAttribute('family', Arial);
	  		$font->setAttribute('size', '11');
	  		$font->setAttribute('bold', true);
	
	  		$format = $doc->createElement('format');
	  		$format = $labels->appendChild($format);
	  		$value = $doc->createTextNode("  {%Value}{numDecimals:$numDecimals}");
	  		$value = $format->appendChild($value); 	   	
	  	
	  		$color_ranges = $doc->createElement('color_ranges');
	  		$color_ranges = $axis->appendChild($color_ranges);
	  	
	  		$color_range = $doc->createElement('color_range');
	  		$color_range = $color_ranges->appendChild($color_range);
	  		$color_range->setAttribute('start', 0);
	  		$color_range->setAttribute('end', 20);
	  		$color_range->setAttribute('color', 'Red');
	  	
	  		$color_range = $doc->createElement('color_range');
	  		$color_range = $color_ranges->appendChild($color_range);
	  		$color_range->setAttribute('start', 20);
	  		$color_range->setAttribute('end', 35);
	  		$color_range->setAttribute('color', 'Gold');
	  		
	  		$color_range = $doc->createElement('color_range');
	  		$color_range = $color_ranges->appendChild($color_range);
	  		$color_range->setAttribute('start', 35);
	  		$color_range->setAttribute('end', 100);
	  		$color_range->setAttribute('color', 'Green');
		  

			//////////////////////////////////////////////////////////////////// 
	  	
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $circular->appendChild($pointers);
	  	
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('type', 'Needle');
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  		$pointer->setAttribute('color', '#F0673B');
	  	
			$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  		$needle_pointer_style = $pointer->appendChild($needle_pointer_style);
	  		$needle_pointer_style->setAttribute('thickness', '6');  		
	
	  		$border = $doc->createElement('border');
	  		$border = $needle_pointer_style->appendChild($border);
	  		$border->setAttribute('color', 'Black');  
	  		$border->setAttribute('opacity', '0.5');  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $needle_pointer_style->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '2');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.3');   	
	
	  		$cap = $doc->createElement('cap');
	  		$cap = $needle_pointer_style->appendChild($cap);
	  		$cap->setAttribute('radius', '12');   
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $cap->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '2');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.3');   	
	  	
        
	  		$tooltip = $doc->createElement('tooltip');
	  		$tooltip = $pointers->appendChild($tooltip);
	  		$tooltip->setAttribute('enabled', 'true');
	
	  		$format = $doc->createElement('format');
	  		$format = $tooltip->appendChild($format);
	  		$value = $doc->createTextNode("  {%Value}");
	  		$value = $format->appendChild($value); 	  	
	
	  		$font = $doc->createElement('font');
	  		$font = $tooltip->appendChild($font);
	  		$font->setAttribute('bold', 'true');
            
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  		$label->setAttribute('under_pointers', 'true');
	  	
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '35');
	  		$position->setAttribute('y', '65');
	  		$position->setAttribute('valign', 'Center');
	  		$position->setAttribute('halign', 'Center');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		//$value = $doc->createTextNode($this->Unit."  {%Value}{numDecimals:$numDecimals}");
	  		//$value = $format->appendChild($value); 	
                	
	  	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Solid');
	  		$fill->setAttribute('color', 'White');
	  		$fill->setAttribute('opacity', '0.5');    	
	
	  		$border = $doc->createElement('border');
	  		$border = $background->appendChild($border);
	  		$border->setAttribute('type', 'Solid'); 
	  		$border->setAttribute('color', 'Gray');  
	  		$border->setAttribute('opacity', '0.6');  
	
	  		$corners = $doc->createElement('corners');
	  		$corners = $background->appendChild($corners);
	  		$corners->setAttribute('type', 'Rounded');
	  		$corners->setAttribute('all', '4');
	  		
	  		$effects = $doc->createElement('effects');
	  		$effects = $background->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	  		
			$inside_margin = $doc->createElement('inside_margin');
	  		$inside_margin = $background->appendChild($inside_margin);
	  		$inside_margin->setAttribute('left', '7');
	  		$inside_margin->setAttribute('top', '2');
	  		$inside_margin->setAttribute('right', '7');
	  		$inside_margin->setAttribute('bottom', '2');
	  		
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '0.7');  
	  		$animation->setAttribute('interpolation_type', 'Cubic');    
	  		
	  		$labels = $doc->createElement('labels');
	  		$labels = $circular->appendChild($labels);
	  		
	  		
	  		$label = $doc->createElement('label');
	  		$label = $labels->appendChild($label);
	  		$label->setAttribute('text_align', 'Center');
	  		$label->setAttribute('under_pointers', 'true');
			
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '35');
	  		$position->setAttribute('y', '35');
	  		$position->setAttribute('valign', 'Center');
	  		$position->setAttribute('halign', 'Center');			  
			  
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		//$value = $doc->createTextNode($this->Unit);
	  		//$value = $format->appendChild($value);  

	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');
			  	
	  		$linear = $doc->createElement('linear');
	  		$linear = $gauge->appendChild($linear);
	  		$linear->setAttribute('x', '55');
	  		$linear->setAttribute('y', '15');
	  		$linear->setAttribute('width', '30');
	  		$linear->setAttribute('height', '70');
	  		//$linear->setAttribute('parent', 'Main');
			  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $linear->appendChild($axis);
	  		$axis->setAttribute('size', '8');
	  		
	  		$scale_bar = $doc->createElement('scale_bar');
	  		$scale_bar = $axis->appendChild($scale_bar);
			  	
	  		$effects = $doc->createElement('effects');
	  		$effects = $scale_bar->appendChild($effects);
	  		$effects->setAttribute('enabled', 'false');
	  		
	  		
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $linear->appendChild($pointers);
	  	
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  		$pointer->setAttribute('type', 'Thermometer');
	  		$pointer->setAttribute('color', 'Rgb(240,40,40)');
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '1');  
	  		$animation->setAttribute('interpolation_type', 'Cubic'); 

	  		$labels = $doc->createElement('labels');
	  		$labels = $linear->appendChild($labels);
	  		
	  		$label = $doc->createElement('label');
	  		$label = $labels->appendChild($label);
	  		$label->setAttribute('rotation', '90');
			
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '65');
	  		$position->setAttribute('y', '5');
	  		$position->setAttribute('valign', 'Bottom');			  
			  
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode($this->Unit);
	  		$value = $format->appendChild($value);  

	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');

	  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
	
		}
		else if ($type==5)//گيج نیم دایره
	  	{
	  
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$circular = $doc->createElement('circular');
	  		$circular = $gauge->appendChild($circular);
	  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $circular->appendChild($axis);
	  		$axis->setAttribute('radius', '37');
	  		$axis->setAttribute('start_angle', '85');
	  		$axis->setAttribute('sweep_angle', '190');
	  		$axis->setAttribute('size', '3');
	  		
	  	
	  		$scale = $doc->createElement('scale');
	  		$scale = $axis->appendChild($scale);
	  		$scale->setAttribute('minimum', $minimum);
	  		$scale->setAttribute('maximum', $maximum);
	  		$scale->setAttribute('major_interval', $major_interval);
	  		$scale->setAttribute('minor_interval', $minor_interval);
	  	
	  	
	  		$labels = $doc->createElement('labels');
	  		$labels = $axis->appendChild($labels);
	  		$labels->setAttribute('align', 'Outside');
	  		$labels->setAttribute('padding', '6');
	  	
	  		$format = $doc->createElement('format');
	  		$format = $labels->appendChild($format);
	  		$value = $doc->createTextNode('  {%Value}{numDecimals:0}');
	  		$value = $format->appendChild($value);  	
	  	
	  		$scale_bar = $doc->createElement('scale_bar');
	  		$scale_bar = $axis->appendChild($scale_bar);
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $scale_bar->appendChild($fill);
	  		$fill->setAttribute('color', '#292929');
	  	
	  		$major_tickmark = $doc->createElement('major_tickmark');
	  		$major_tickmark = $axis->appendChild($major_tickmark);
	  		$major_tickmark->setAttribute('align', 'Center');
	  		$major_tickmark->setAttribute('length', '10');
			$major_tickmark->setAttribute('padding', '0');
	  	
	  		$minor_tickmark = $doc->createElement('minor_tickmark');
	  		$minor_tickmark = $axis->appendChild($minor_tickmark);
	  		$minor_tickmark->setAttribute('enabled', 'false');
	  	
	  		$color_ranges = $doc->createElement('color_ranges');
	  		$color_ranges = $axis->appendChild($color_ranges);
	  	
			/////////////////////////////////////color_range Red/////////////////////////////////// 
			
	  		$color_range = $doc->createElement('color_range');
	  		$color_range = $color_ranges->appendChild($color_range);
	  		$color_range->setAttribute('start', $minimum);
	  		$color_range->setAttribute('end', $maximum);
	  		$color_range->setAttribute('start_size', 15);
	  		$color_range->setAttribute('end_size', 15);
	  		$color_range->setAttribute('align', "Inside");
	  		$color_range->setAttribute('padding', 6);
	  		
	  		$fill = $doc->createElement('fill');
	  		$fill = $color_range->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');
	  	
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  	
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Red');
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Yellow');
	  		$key = $doc->createElement('key');$key = $gradient->appendChild($key);$key->setAttribute('color', 'Green');
	  		
	  		$border = $doc->createElement('border');
	  		$border = $color_range->appendChild($border);
	  		$border->setAttribute('enabled', 'true');
	  		$border->setAttribute('color', 'Black');
	  		$border->setAttribute('opacity', '0.4');
			
	  		//////////////////////////////////////////////////////////////////// 
	  	
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $circular->appendChild($pointers);
	  	
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  	
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  		$label->setAttribute('under_pointers', 'true');
	  	
        
        
	  		$tooltip = $doc->createElement('tooltip');
	  		$tooltip = $pointers->appendChild($tooltip);
	  		$tooltip->setAttribute('enabled', 'true');
	
	  		$format = $doc->createElement('format');
	  		$format = $tooltip->appendChild($format);
	  		$value = $doc->createTextNode($this->Unit."  {%Value}");
	  		$value = $format->appendChild($value); 	  	
	
	  		$font = $doc->createElement('font');
	  		$font = $tooltip->appendChild($font);
	  		$font->setAttribute('bold', 'true');
        
        
        
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '50');
	  		$position->setAttribute('y', '60');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode("");
	  		$value = $format->appendChild($value); 	  	
	  	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
	  		$background->setAttribute('enabled', 'false');
	
	  		$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  		$needle_pointer_style = $pointer->appendChild($needle_pointer_style);
	  		$needle_pointer_style->setAttribute('thickness', '7');  
	  		$needle_pointer_style->setAttribute('point_thickness', '5');  
	  		$needle_pointer_style->setAttribute('point_radius', '3');  	
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $needle_pointer_style->appendChild($fill);
	  		$fill->setAttribute('color', 'Rgb(230,230,230)');  	
	
	  		$border = $doc->createElement('border');
	  		$border = $needle_pointer_style->appendChild($border);
	  		$border->setAttribute('color', 'Black');  
	  		$border->setAttribute('opacity', '0.7');  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $needle_pointer_style->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '2');  
	  		$bevel->setAttribute('shadow_opacity', '0.6');  
	  		$bevel->setAttribute('highlight_opacity', '0.6');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '1');  
	  		$drop_shadow->setAttribute('blur_y', '1');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	
	  		$cap = $doc->createElement('cap');
	  		$cap = $needle_pointer_style->appendChild($cap);
	
	  		$background = $doc->createElement('background');
	  		$background = $cap->appendChild($background);
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');
	  	
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  		$gradient->setAttribute('type', 'Linear');
	  		$gradient->setAttribute('angle', '45');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('color', '#D3D3D3');
	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('color', '#6F6F6F');
	  	
	  		$border = $doc->createElement('border');
	  		$border = $background->appendChild($border);
	  		$border->setAttribute('color', 'Black');
	  		$border->setAttribute('opacity', '0.9');
	  	
	  		$effects = $doc->createElement('effects');
	  		$effects = $cap->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '1.5');  
	  		$bevel->setAttribute('shadow_opacity', '0.6');  
	  		$bevel->setAttribute('highlight_opacity', '0.6');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1.5');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	  	
	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '0.5');  
	  		$animation->setAttribute('interpolation_type', 'Bounce');    	
	  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
	
		}
		else if ($type==6)//گيج دو شاخصه
	  	{
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$circular = $doc->createElement('circular');
	  		$circular = $gauge->appendChild($circular);
	  	
	  		$axis = $doc->createElement('axis');
	  		$axis = $circular->appendChild($axis);
	  		$axis->setAttribute('radius', '48');
	  		$axis->setAttribute('size', '3');
	  		
	  		$scale = $doc->createElement('scale');
	  		$scale = $axis->appendChild($scale);
	  		$scale->setAttribute('type', 'Linear');
	  		$scale->setAttribute('minimum', $minimum);
	  		$scale->setAttribute('maximum', $maximum);
	  		$scale->setAttribute('major_interval', $major_interval);
	  	
	  		$scale_bar = $doc->createElement('scale_bar');
	  		$scale_bar = $axis->appendChild($scale_bar);
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $scale_bar->appendChild($fill);
	  		$fill->setAttribute('color', 'White');
	  		$fill->setAttribute('opacity', '0.6');
	  	
	  		$border = $doc->createElement('border');
	  		$border = $scale_bar->appendChild($border);
	  		$border->setAttribute('enabled', 'false');
	  	
	  		$major_tickmark = $doc->createElement('major_tickmark');
	  		$major_tickmark = $axis->appendChild($major_tickmark);
	  		$major_tickmark->setAttribute('enabled', 'true');
	  		$major_tickmark->setAttribute('shape', 'Line');
			$major_tickmark->setAttribute('length', '%AxisSize');
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $major_tickmark->appendChild($fill);
	  		$fill->setAttribute('enabled', 'True');
	  		$fill->setAttribute('color', 'White');
	  		$fill->setAttribute('opacity', '1');
	  	
	  		$border = $doc->createElement('border');
	  		$border = $scale_bar->appendChild($border);
	  		$border->setAttribute('enabled', 'True');
	  		$border->setAttribute('thickness', '2');
	  		$border->setAttribute('caps', 'None');
	  		
	  		$major_tickmark = $doc->createElement('major_tickmark');
	  		$major_tickmark = $axis->appendChild($major_tickmark);
	  		$major_tickmark->setAttribute('enabled', 'true');
	  		$major_tickmark->setAttribute('shape', 'Line');
			$major_tickmark->setAttribute('length', '%AxisSize');
	  	
	  		$fill = $doc->createElement('fill');
	  		$fill = $major_tickmark->appendChild($fill);
	  		$fill->setAttribute('enabled', 'True');
	  		$fill->setAttribute('color', 'White');
	  		$fill->setAttribute('opacity', '1');
	  	
	  		$border = $doc->createElement('border');
	  		$border = $scale_bar->appendChild($border);
	  		$border->setAttribute('enabled', 'True');
	  		$border->setAttribute('thickness', '1');
	  		$border->setAttribute('caps', 'None');
	  		$border->setAttribute('opacity', '0.2');
	  		
	  		$labels = $doc->createElement('labels');
	  		$labels = $axis->appendChild($labels);
	  		$labels->setAttribute('align', 'Inside');
	  	
	  		$pointers = $doc->createElement('pointers');
	  		$pointers = $circular->appendChild($pointers);
	  
	  		$tooltip = $doc->createElement('tooltip');
	  		$tooltip = $pointers->appendChild($tooltip);
	  		$tooltip->setAttribute('enabled', 'true');
	
	  		$format = $doc->createElement('format');
	  		$format = $tooltip->appendChild($format);
	  		$value = $doc->createTextNode($this->Unit."  {%Value}");
	  		$value = $format->appendChild($value); 	  	
	
	  		$font = $doc->createElement('font');
	  		$font = $tooltip->appendChild($font);
	  		$font->setAttribute('bold', 'true');
	  		
	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('type', 'Needle');
	  		$pointer->setAttribute('value', $this->Valuegauge);
	  	
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  		$label->setAttribute('under_pointers', 'true');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode($this->Titlegauge);
	  		$value = $format->appendChild($value); 	  	
	  	  	
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '50');
	  		$position->setAttribute('y', '60');
	  		$position->setAttribute('valign', 'Center');
	  		$position->setAttribute('halign', 'Center');
	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
		
	  		$corners = $doc->createElement('corners');
	  		$corners = $background->appendChild($corners);
	  		$corners->setAttribute('type', 'Rounded'); 
	  		$corners->setAttribute('all', '3'); 
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Solid');  	
	  		$fill->setAttribute('color', 'blue');  	
	  		$fill->setAttribute('opacity', '0.1');  	
	
	  		$border = $doc->createElement('border');
	  		$border = $fill->appendChild($border);
	  		$border->setAttribute('type', 'Solid');  
	  		$border->setAttribute('color', 'Black');  
	  		$border->setAttribute('opacity', '0.2');  
	
	  		$font = $doc->createElement('font');
	  		$font = $label->appendChild($font);
	  		$font->setAttribute('color', "#FF0000");  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $font->appendChild($effects);
	  		$effects->setAttribute('enabled', "true");
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', "true");
	  		$drop_shadow->setAttribute('distance', "1");
	  		$drop_shadow->setAttribute('opacity', "0.5");
	  		$drop_shadow->setAttribute('blur_x', "1");
	  		$drop_shadow->setAttribute('blur_y', "1");
	
	  		$inside_margin = $doc->createElement('inside_margin');
	  		$inside_margin = $label->appendChild($inside_margin);
	  		$inside_margin->setAttribute('top', "3");  
	  		$inside_margin->setAttribute('bottom', "3");  

	  		$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  		$needle_pointer_style = $pointer->appendChild($needle_pointer_style);
	  		$needle_pointer_style->setAttribute('thickness', '8');  
	  		$needle_pointer_style->setAttribute('point_thickness', '6');  
	  		$needle_pointer_style->setAttribute('point_radius', '2');  	  
	  		$needle_pointer_style->setAttribute('radius', '82');
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $needle_pointer_style->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');  	
		
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  		$gradient->setAttribute('angle', '90');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0');
	  		$key->setAttribute('color', 'red');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0.3');
	  		$key->setAttribute('color', 'red');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0.8');
	  		$key->setAttribute('color', 'DarkColor(red)');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '1');
	  		$key->setAttribute('color', 'DarkColor(red)');
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $needle_pointer_style->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '1');  
	  		$bevel->setAttribute('blur_x', '2');  
	  		$bevel->setAttribute('blur_y', '2');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	
	  		$cap = $doc->createElement('cap');
	  		$cap = $needle_pointer_style->appendChild($cap);
	  	
	  		$effects = $doc->createElement('effects');
	  		$effects = $cap->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	  	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '1');  
	  		$bevel->setAttribute('blur_x', '2');  
	  		$bevel->setAttribute('blur_y', '2');     
	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '0.7');  
	  		$animation->setAttribute('interpolation_type', 'Back');    	


	  		$pointer = $doc->createElement('pointer');
	  		$pointer = $pointers->appendChild($pointer);
	  		$pointer->setAttribute('type', 'Needle');
	  		$pointer->setAttribute('value', $this->Valuegauge2);
	  	
	  		$label = $doc->createElement('label');
	  		$label = $pointer->appendChild($label);
	  		$label->setAttribute('enabled', 'true');
	  		$label->setAttribute('under_pointers', 'true');
	
	  		$format = $doc->createElement('format');
	  		$format = $label->appendChild($format);
	  		$value = $doc->createTextNode($this->Titlegauge2);
	  		$value = $format->appendChild($value); 	  	
	  	  	
	  		$position = $doc->createElement('position');
	  		$position = $label->appendChild($position);
	  		$position->setAttribute('placement_mode', 'ByPoint');
	  		$position->setAttribute('x', '50');
	  		$position->setAttribute('y', '30');
	  		$position->setAttribute('valign', 'Center');
	  		$position->setAttribute('halign', 'Center');
	
	  		$background = $doc->createElement('background');
	  		$background = $label->appendChild($background);
		
	  		$corners = $doc->createElement('corners');
	  		$corners = $background->appendChild($corners);
	  		$corners->setAttribute('type', 'Rounded'); 
	  		$corners->setAttribute('all', '3'); 
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $background->appendChild($fill);
	  		$fill->setAttribute('type', 'Solid');  	
	  		$fill->setAttribute('color', 'blue');  	
	  		$fill->setAttribute('opacity', '0.1');  	
	
	  		$border = $doc->createElement('border');
	  		$border = $needle_pointer_style->appendChild($border);
	  		$border->setAttribute('type', 'Solid');  
	  		$border->setAttribute('color', 'Black');  
	  		$border->setAttribute('opacity', '0.2');  
	
	  		$font = $doc->createElement('font');
	  		$font = $label->appendChild($font);
	  		$font->setAttribute('color', "#000FF");  
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $font->appendChild($effects);
	  		$effects->setAttribute('enabled', "true");
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', "true");
	  		$drop_shadow->setAttribute('distance', "1");
	  		$drop_shadow->setAttribute('opacity', "0.5");
	  		$drop_shadow->setAttribute('blur_x', "1");
	  		$drop_shadow->setAttribute('blur_y', "1");
	
	  		$inside_margin = $doc->createElement('inside_margin');
	  		$inside_margin = $label->appendChild($inside_margin);
	  		$inside_margin->setAttribute('top', "3");  
	  		$inside_margin->setAttribute('bottom', "3");  
	
	  		$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  		$needle_pointer_style = $pointer->appendChild($needle_pointer_style);
	  		$needle_pointer_style->setAttribute('thickness', '8');  
	  		$needle_pointer_style->setAttribute('point_thickness', '6');  
	  		$needle_pointer_style->setAttribute('point_radius', '2');  	  
	  		$needle_pointer_style->setAttribute('radius', '82');
		
	  		$fill = $doc->createElement('fill');
	  		$fill = $needle_pointer_style->appendChild($fill);
	  		$fill->setAttribute('type', 'Gradient');  	
		
	  		$gradient = $doc->createElement('gradient');
	  		$gradient = $fill->appendChild($gradient);
	  		$gradient->setAttribute('angle', '90');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0');
	  		$key->setAttribute('color', 'blue');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0.3');
	  		$key->setAttribute('color', 'blue');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '0.8');
	  		$key->setAttribute('color', 'DarkColor(blue)');
	  	
	  		$key = $doc->createElement('key');
	  		$key = $gradient->appendChild($key);
	  		$key->setAttribute('position', '1');
	  		$key->setAttribute('color', 'DarkColor(blue)');
	
	  		$effects = $doc->createElement('effects');
	  		$effects = $needle_pointer_style->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '1');  
	  		$bevel->setAttribute('blur_x', '2');  
	  		$bevel->setAttribute('blur_y', '2');     
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	
	  		$cap = $doc->createElement('cap');
	  		$cap = $needle_pointer_style->appendChild($cap);
	  	
	  		$effects = $doc->createElement('effects');
	  		$effects = $cap->appendChild($effects);
	  		$effects->setAttribute('enabled', 'true');
	
	  		$drop_shadow = $doc->createElement('drop_shadow');
	  		$drop_shadow = $effects->appendChild($drop_shadow);
	  		$drop_shadow->setAttribute('enabled', 'true');
	  		$drop_shadow->setAttribute('distance', '1');  
	  		$drop_shadow->setAttribute('blur_x', '2');  
	  		$drop_shadow->setAttribute('blur_y', '2');  
	  		$drop_shadow->setAttribute('opacity', '0.4');   	
	  	
	  		$bevel = $doc->createElement('bevel');
	  		$bevel = $effects->appendChild($bevel);
	  		$bevel->setAttribute('enabled', 'true');
	  		$bevel->setAttribute('distance', '1');  
	  		$bevel->setAttribute('blur_x', '2');  
	  		$bevel->setAttribute('blur_y', '2');     
	
	  		$animation = $doc->createElement('animation');
	  		$animation = $pointer->appendChild($animation);
	  		$animation->setAttribute('enabled', 'true');
	  		$animation->setAttribute('start_time', '0');  
	  		$animation->setAttribute('duration', '0.7');  
	  		$animation->setAttribute('interpolation_type', 'Back');    	
	  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
	
		}	
		else if ($type==7)//گیج دوازده تایی
	  	{
	  		// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$templates = $doc->createElement('templates');
	  		$templates = $root->appendChild($templates);
	  	
			$template = $doc->createElement('template');
	  		$template = $templates->appendChild($template);
	  		$template->setAttribute('name', "gaugeTemplates");
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $template->appendChild($gauge);
	  		
			for($i=1;$i<=$this->XNum;$i++)
				for($j=1;$j<=$this->YNum;$j++)
	    		{
	  				$circular_template = $doc->createElement('circular_template');
	  				$circular_template = $gauge->appendChild($circular_template);
	  				$circular_template->setAttribute('name', "predefined$j$i");
	  	
	  				$margin = $doc->createElement('margin');
	  				$margin = $circular_template->appendChild($margin);
	  				$margin->setAttribute('all', '3');
	  	
	  				$styles = $doc->createElement('styles');
	  				$styles = $circular_template->appendChild($styles);

	  				$needle_pointer_style = $doc->createElement('needle_pointer_style');
	  				$needle_pointer_style = $styles->appendChild($needle_pointer_style);
	  				$needle_pointer_style->setAttribute('name', 'small'); 
	  				$needle_pointer_style->setAttribute('thickness', '7');  
	  		
	  				$cap = $doc->createElement('cap');
	  				$cap = $needle_pointer_style->appendChild($cap);
	  				$cap->setAttribute('radius', '5');
	
	  				$background = $doc->createElement('background');
	  				$background = $cap->appendChild($background);
		
	  				$fill = $doc->createElement('fill');
	  				$fill = $background->appendChild($fill);
	  				$fill->setAttribute('type', 'Solid');  	
	  				$fill->setAttribute('color', 'Rgb(220,220,220)');  	
	
	  				$border = $doc->createElement('border');
	  				$border = $fill->appendChild($border);
	  				$border->setAttribute('enabled', 'false');  
	
	  				$inner_stroke = $doc->createElement('inner_stroke');
	  				$inner_stroke = $cap->appendChild($inner_stroke);
	  				$inner_stroke->setAttribute('enabled', 'true');  
	  				$inner_stroke->setAttribute('thickness', '4');  
		
	  				$fill = $doc->createElement('fill');
	  				$fill = $inner_stroke->appendChild($fill);
	  				$fill->setAttribute('color', 'Rgb(150,150,150)');
	  	
	  				$axis = $doc->createElement('axis');
	  				$axis = $circular_template->appendChild($axis);
	  				$axis->setAttribute('start_angle', '180');
	  				$axis->setAttribute('sweep_angle', '90');
	  		
	  				$scale = $doc->createElement('scale');
	  				$scale = $axis->appendChild($scale);
	  				$scale->setAttribute('type', 'Linear');
	  				$scale->setAttribute('minimum', $minimum);
	  				$scale->setAttribute('maximum', $maximum);
	  				$scale->setAttribute('major_interval', $major_interval);
	  	
	  				$scale_bar = $doc->createElement('scale_bar');
	  				$scale_bar = $axis->appendChild($scale_bar);  
	  				$scale_bar->setAttribute('enabled', 'false');
	  	
	  				$scale_line = $doc->createElement('scale_line');
	  				$scale_line = $axis->appendChild($scale_line);  
	  				$scale_line->setAttribute('enabled', 'true');
	  				$scale_line->setAttribute('color', '#494949');
	  				$scale_line->setAttribute('opacity', '0.3');
		
	  				$pointers = $doc->createElement('pointers');
	  				$pointers = $circular_template->appendChild($pointers);
	  		
	  				$label = $doc->createElement('label');
	  				$label = $pointers->appendChild($label);
	  				$label->setAttribute('enabled', 'true');
	
	  				$font = $doc->createElement('font');
	  				$font = $label->appendChild($font);
	  				$font->setAttribute('color', '#0000FF');
					$font->setAttribute('bold', 'false');
	  				$font->setAttribute('family', 'Tahoma');
	  				$font->setAttribute('size', '14');
	  				
	
	  				$format = $doc->createElement('format');
	  				$format = $label->appendChild($format);
	  				$value = $doc->createTextNode(($arrayTitle[$j][$i]));
	  				$value = $format->appendChild($value); 	  	
	  	  	
	  				$position = $doc->createElement('position');
	  				$position = $label->appendChild($position);
	  				$position->setAttribute('placement_mode', 'ByPoint');
	  				$position->setAttribute('x', '55');
	  				$position->setAttribute('y', '2');
	
	  				$background = $doc->createElement('background');
	  				$background = $label->appendChild($background);
	  				$background->setAttribute('enabled', 'false');
		
	  				$tooltip = $doc->createElement('tooltip');
	  				$tooltip = $pointers->appendChild($tooltip);
	  				$tooltip->setAttribute('enabled', 'true');
	
	  				$format = $doc->createElement('format');
	  				$format = $tooltip->appendChild($format);
	  				$value = $doc->createTextNode($this->Unit."  {%Value}{numDecimals:$numDecimals}");
	  				$value = $format->appendChild($value); 	  	
	
	  				$font = $doc->createElement('font');
	  				$font = $tooltip->appendChild($font);
	  				$font->setAttribute('bold', 'true');
	  				$font->setAttribute('family', 'Tahoma');
	  				$font->setAttribute('size', '11');
	  		
				}
			
			$gauges = $doc->createElement('gauges');
	  		$gauges = $root->appendChild($gauges);
	  	
	  		$gauge = $doc->createElement('gauge');
	  		$gauge = $gauges->appendChild($gauge);
	  		$gauge->setAttribute('template', 'gaugeTemplates');
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $gauge->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	  		
	  				
			///////////////////////////////////////////////////
			
			for($i=1;$i<=$this->XNum;$i++)
				for($j=1;$j<=$this->YNum;$j++)
	    		{
	  				$circular = $doc->createElement('circular');
	  				$circular = $gauge->appendChild($circular);
			  		$circular->setAttribute('template', "predefined$j$i");
			  		$circular->setAttribute('x', 4*25*($i-1)/$this->XNum);
			  		$circular->setAttribute('y', 3*35*($j-1)/$this->YNum);
			  		$circular->setAttribute('width', 100/$this->XNum);
			  		$circular->setAttribute('height', 100/$this->YNum);
			
			  		$pointers = $doc->createElement('pointers');
			  		$pointers = $circular->appendChild($pointers);
			
			  		$animation = $doc->createElement('animation');
			  		$animation = $pointers->appendChild($animation);
			  		$animation->setAttribute('enabled', 'true');
			  		$animation->setAttribute('start_time', '0');
			  		$animation->setAttribute('duration', '0.2');
			  		$animation->setAttribute('interpolation_type', 'Cubic');
			
			  		$pointer = $doc->createElement('pointer');
			  		$pointer = $pointers->appendChild($pointer);
			  		$pointer->setAttribute('type', 'Needle');
			  		$pointer->setAttribute('value', $arrayNamey[$j][$i]);
			  		$pointer->setAttribute('style', 'small');
			  		$pointer->setAttribute('color', 
					  '#'.dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15))	);
				}
			///////////////////////////////////////////////////  		
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);
			
		}
		else if ($type==8)//نمودار میله ای تجمیعی
		{
			// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$charts = $doc->createElement('charts');
	  		$charts = $root->appendChild($charts);
	  	
	  		$chart = $doc->createElement('chart');
	  		$chart = $charts->appendChild($chart);
	  		$chart->setAttribute('plot_type', 'CategorizedVertical');
	  	
	  		$data_plot_settings = $doc->createElement('data_plot_settings');
	  		$data_plot_settings = $chart->appendChild($data_plot_settings);
	  		$data_plot_settings->setAttribute('default_series_type', 'Bar');
	  		$data_plot_settings->setAttribute('enable_3d_mode', 'true');
	  		$data_plot_settings->setAttribute('z_aspect', '2.5');
	  	
	  		$bar_series = $doc->createElement('bar_series');
	  		$bar_series = $data_plot_settings->appendChild($bar_series);
	  		$bar_series->setAttribute('group_padding', '0.2');
	  	
	  		
	  	
	  		$tooltip_settings = $doc->createElement('tooltip_settings');
	  		$tooltip_settings = $bar_series->appendChild($tooltip_settings);
	  		$tooltip_settings->setAttribute('enabled', 'true');

	  		$format = $doc->createElement('format');
	  		$format = $tooltip_settings->appendChild($format);
	  		$value = $doc->createTextNode('  {%Value}{numDecimals:0}');
	  		$value = $format->appendChild($value);  	
	  		$name = $doc->createTextNode('{%Title}');
	  		$name = $format->appendChild($name);  	
	  		  	
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $chart->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  		$title->setAttribute('enabled', 'true');
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
	  		$axes = $doc->createElement('axes');
	  		$axes = $chart_settings->appendChild($axes);
	  		$axes->setAttribute('enabled', 'true');
	
	
	  		$x_axis = $doc->createElement('x_axis');
	  		$x_axis = $axes->appendChild($x_axis);
	
	  		$labels = $doc->createElement('labels');
	  		$labels = $x_axis->appendChild($labels);
	  		$labels->setAttribute('rotation', '90');
	  	
	  		$y_axis = $doc->createElement('y_axis');
	  		$y_axis = $axes->appendChild($y_axis);
	  		$y_axis->setAttribute('position', 'Normal');
	  		
	  		$scale = $doc->createElement('scale');
	  		$scale = $y_axis->appendChild($scale);
	  		$scale->setAttribute('mode', 'Stacked');
	  		
	  	
	  		$data = $doc->createElement('data');
	  		$data = $chart->appendChild($data);
	
	
			foreach($arrayNamey as $key1 => $value1)
			{
	  			$series["$key1"] = $doc->createElement('series');
	  			$series["$key1"] = $data->appendChild($series["$key1"]);
	  			$series["$key1"]->setAttribute('name', "Series $key1");
				//-----------------------------------
				foreach($value1 as $key2 => $value2)
				{
				
					$point["$key2"] = $doc->createElement('point');
	  				$point["$key2"] = $series["$key1"]->appendChild($point["$key2"]);
	  				$point["$key2"]->setAttribute('name', $arrayNamey[$key1][$key2]['name']);
	  				$point["$key2"]->setAttribute('y', $arrayNamey[$key1][$key2]['y']);
				}	
			}    	
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);	
		}	
			else if ($type==9)//نمودار خطی
		{
			// create a new XML document
			$doc = new DomDocument('1.0');
	
			// create root node
			$root = $doc->createElement('anychart');
			$root = $doc->appendChild($root);
		
	  		$settings = $doc->createElement('settings');
	  		$settings = $root->appendChild($settings);
	  	
	  		$animation = $doc->createElement('animation');
	  		$animation = $settings->appendChild($animation);
	  		$animation->setAttribute('enabled', 'True');
	  	
	  		$charts = $doc->createElement('charts');
	  		$charts = $root->appendChild($charts);
	  	
	  		$chart = $doc->createElement('chart');
	  		$chart = $charts->appendChild($chart);
	  		$chart->setAttribute('plot_type', 'CategorizedVertical');
	  	
	  		$data_plot_settings = $doc->createElement('data_plot_settings');
	  		$data_plot_settings = $chart->appendChild($data_plot_settings);
	  		$data_plot_settings->setAttribute('default_series_type', 'Line');
	  		$data_plot_settings->setAttribute('enable_3d_mode', 'false');
	  		$data_plot_settings->setAttribute('z_aspect', '2.5');
	  		
	  		$bar_series = $doc->createElement('line_series');
	  		$bar_series = $data_plot_settings->appendChild($bar_series);
	  		$bar_series->setAttribute('group_padding', '0.2');
	  	
	  	
	  		$tooltip_settings = $doc->createElement('tooltip_settings');
	  		$tooltip_settings = $bar_series->appendChild($tooltip_settings);
	  		$tooltip_settings->setAttribute('enabled', 'true');

	  		/*$format = $doc->createElement('format');
	  		$format = $tooltip_settings->appendChild($format);
	  		$value = $doc->createTextNode('  {%Value}{numDecimals:0}');
	  		$value = $format->appendChild($value);  	
	  		$name = $doc->createTextNode('{%Title}');
	  		$name = $format->appendChild($name);  	
	  		*/  	
	  	
	  		$chart_settings = $doc->createElement('chart_settings');
	  		$chart_settings = $chart->appendChild($chart_settings);
	  	
	  		$title = $doc->createElement('title');
	  		$title = $chart_settings->appendChild($title);
	  		$title->setAttribute('enabled', 'true');
	  	
	  		$text = $doc->createElement('text');
	  		$text = $title->appendChild($text);
	  	
	  		$value = $doc->createTextNode($ChartTitle);
	  		$value = $text->appendChild($value);
	
			$legend = $doc->createElement('legend');
	  		$legend = $chart_settings->appendChild($legend);
	  		$legend->setAttribute('enabled', 'true');
			
			$title = $doc->createElement('title');
	  		$title = $legend->appendChild($title);
	  		$title->setAttribute('enabled', 'true');
			
			$icon = $doc->createElement('icon');
	  		$icon = $legend->appendChild($icon);
	  		
			$marker = $doc->createElement('marker');
	  		$marker = $icon->appendChild($marker);
	  		$marker->setAttribute('enabled', 'true');
			$marker->setAttribute('type', '%MarkerType');
			$marker->setAttribute('size', '12');
				  	
	  		$axes = $doc->createElement('axes');
	  		$axes = $chart_settings->appendChild($axes);
	  		$axes->setAttribute('enabled', 'true');
	
	
	  		$x_axis = $doc->createElement('x_axis');
	  		$x_axis = $axes->appendChild($x_axis);
	
			$labels = $doc->createElement('labels');
	  		$labels = $x_axis->appendChild($labels);
	  		$labels->setAttribute('rotation', '90');
	  		
	  	
	  		$y_axis = $doc->createElement('y_axis');
	  		$y_axis = $axes->appendChild($y_axis);
	  		$y_axis->setAttribute('position', 'Normal');
	  		
	  		/*$scale = $doc->createElement('scale');
	  		$scale = $y_axis->appendChild($scale);
	  		$scale->setAttribute('mode', 'Stacked');
	  		*/
	  	
	  		$data = $doc->createElement('data');
	  		$data = $chart->appendChild($data);
	
			if (strlen($arrayNamey)>0)
			foreach($arrayNamey as $key1 => $value1)
			{
	  			$series["$key1"] = $doc->createElement('series');
	  			$series["$key1"] = $data->appendChild($series["$key1"]);
	  			if (strlen($this->SeriesName["$key1"])>0)
	  			
				  $series["$key1"]->setAttribute('name', $this->SeriesName["$key1"]);
				else
					$series["$key1"]->setAttribute('name', "Series $key1");
				//-----------------------------------
				foreach($value1 as $key2 => $value2)
				{
				
					$point["$key2"] = $doc->createElement('point');
	  				$point["$key2"] = $series["$key1"]->appendChild($point["$key2"]);
	  				$point["$key2"]->setAttribute('name', $arrayNamey[$key1][$key2]['name']);
	  				$point["$key2"]->setAttribute('y', $arrayNamey[$key1][$key2]['y']);
				}	
			}    	
			$doc->saveHTMLFile($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$XMLPath);	
		}	
	
	  	$Chartstr= 
			"
			<script type='text/javascript' language='JavaScript' src='chart/AnyChart.js'></script>
			<script type='text/javascript' language='JavaScript' src='chart/GalleryItem.js'></script>
			<object id='chart' 
					name='chart' 
					classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' 
					width='100%' 
					height='100%' 
					codebase='http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab'>
				<param name='movie' value='chart/AnyChart.swf' />
				<param name='bgcolor' value='#FFFFFF' />
				<param name='allowScriptAccess' value='always' />
				<param name='flashvars' value='XMLFile=http://$_SERVER[HTTP_HOST]/$ProjectRoot/$XMLPath' />
				
				<embed type='application/x-shockwave-flash' 
					   pluginspage='http://www.adobe.com/go/getflashplayer' 
					   src='http://$_SERVER[HTTP_HOST]/$ProjectRoot/chart/AnyChart.swf' 
					   width='100%' 
					   height='100%' 
					   id='chart' 
					   name='chart' 
					   bgColor='#FFFFFF' 
					   allowScriptAccess='always' 
					   flashvars='XMLFile=http://$_SERVER[HTTP_HOST]/$ProjectRoot/$XMLPath' />
				
			</object>				
			<script type='text/javascript' language='JavaScript'>
			//<![CDATA[
				var chartSample = new AnyChart('http://$_SERVER[HTTP_HOST]/$ProjectRoot/chart/AnyChart.swf');
				chartSample.width = '100%';
				chartSample.height = '100%';
				chartSample.setXMLFile('http://$_SERVER[HTTP_HOST]/$ProjectRoot/$XMLPath');
				chartSample.write('sample');
				init();
				//]]>
			</script>
			
			<a   style='font-family=Tahoma;font-size=12;'   id='' title='' 
			href='http://$_SERVER[HTTP_HOST]/$ProjectRoot/$Path' rel='' target=_blank
			accesskey=''>
			Show </a>
		";
		  
	    $fp = fopen($_SERVER['DOCUMENT_ROOT']."$ProjectRoot/".$Path, 'w');
		fwrite($fp, $Chartstr);
		fclose($fp);
	  	
	}
  //-------------------------------------------Method Separator---------------------------------------------------------
}
//=================================================Class end=========================================================

?>