<?php
/*
includes/functiong.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
تمام صفحات
*/
function splitimplant($ApplicantMasterID)//تابع استخراج مصولات طرح بر اساس سیستم های آبیاری
{ 
    $query="select concat(implanttype.Title,' ',PlantArea,' هکتار ') Title,DesignSystemGroupsID from applicantsystemtype 
    inner join implanttype on implanttype.ImplantTypeID=applicantsystemtype.ImplantTypeID
            where applicantsystemtype.ApplicantMasterID ='$ApplicantMasterID'";
    $result = mysql_query($query); 
    $ghatre="";
    $sathi="";
    $barani="";
    while($row = mysql_fetch_assoc($result))
    {
        if ($row['DesignSystemGroupsID']==2)
			$ghatre.=" ،".$row['Title'];
        else if ($row['DesignSystemGroupsID']==5)	
			$ghatre.=" ،".$row['Title'];
        else if ($row['DesignSystemGroupsID']==7)	
			$ghatre.=" ،".$row['Title'];
        else if ($row['DesignSystemGroupsID']==4)
			$sathi.=" ،". $row['Title'];
        else if ($row['DesignSystemGroupsID']==3)
			$sathi.=" ،". $row['Title'];
        else if ($row['DesignSystemGroupsID']==1)
			$barani.=" ،". $row['Title'];
        else if ($row['DesignSystemGroupsID']==6)
			$barani.=" ،". $row['Title'];
    }	
    return $ghatre."_".$sathi."_".$barani;
}

function splithektar($ApplicantMasterID)//تابع استخراج مساحت طرح بر اساس سیستم های آبیاری
{
    $query="select ApplicantMasterID,hektar,DesignSystemGroupsID from designsystemgroupsdetail 
            where ApplicantMasterID ='$ApplicantMasterID'";
    $result = mysql_query($query); 
    $ghatre=0;
    $sathi=0;
    $barani=0;
    while($row = mysql_fetch_assoc($result))
    {
        if ($row['DesignSystemGroupsID']==2)
			$ghatre+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==5)	
			$ghatre+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==7)	
			$ghatre+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==4)
			$sathi+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==3)
			$sathi+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==1)
			$barani+= $row['hektar'];
        else if ($row['DesignSystemGroupsID']==6)
			$barani+= $row['hektar'];
    }	
    return $ghatre."_".$sathi."_".$barani;
}

function calculatebelavaz($selectedcreditsourceID,$ApplicantMasterID,$sumsurat,$criditType)//محاسبه بلاعوض طرح با توجه به نوع سیستم
{
       $sysbelaavaz=0; 
        $queryb = "SELECT designsystemgroupsdetail.DesignSystemGroupsID,hektar,price
        ,case designsystemgroupsdetail.DesignSystemGroupsID 
        when 1 then creditsource.baranival 
        when 2 then creditsource.ghatreeval 
        when 3 then creditsource.sathival 
        when 4 then creditsource.sathival 
        when 5 then creditsource.ghatreeval 
        when 6 then creditsource.centerval 
        when 7 then creditsource.zirval 
        else 0 end belaavazval
        ,case designsystemgroupsdetail.DesignSystemGroupsID 
        when 1 then creditsource.baraniper 
        when 2 then creditsource.ghatreeper 
        when 3 then creditsource.sathiper 
        when 4 then creditsource.sathiper 
        when 5 then creditsource.ghatreeper 
        when 6 then creditsource.centerper 
        when 7 then creditsource.zirper 
        else 0 end belaavazper
        FROM designsystemgroupsdetail
        inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or 
        applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID' or
        applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
        inner join applicantmaster on applicantmaster.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
        left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
        where hektar>0 and designsystemgroupsdetail.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID;";
       
    //print $queryb;
	    $resultb = mysql_query($queryb);
        
        while ($row = mysql_fetch_assoc($resultb))
        {
             $sysbelaavaz+=floor($row['belaavazval']*$row['hektar']);            
        }
		
        //print $sumsurat."_".$sysbelaavaz."_".$criditType;
        
		if ($criditType>0) $sysbelaavaz = floor(($sumsurat*0.85)/100000)/10;
		else 
        {
            if ($sysbelaavaz<=($sumsurat*0.85))
                $sysbelaavaz = floor($sysbelaavaz/100000)/10;
            else
                $sysbelaavaz = floor(($sumsurat*0.85)/100000)/10;    
		}

	
   return $sysbelaavaz;  
    
}

function calculatec1c2($resquery2,$tend)//محاسبه ضرایب  دامنه پیشنهاد قیمت
{
	//print_r($resquery2);
	$rown=1;
	$Pb=floor($resquery2[1][2]/100000)/10;
	$YearID=$resquery2[1][3];
	 while($resquery2[$rown][3]>0)
                    {
     			$AipriceCo[$rown]=$resquery2[$rown][0];		
				$Aiprice[$rown]=floor($resquery2[$rown][1]*10)/10;		
				$rown++;
				
			        }
	$rown--;		
			//print 'sa'.$YearID.'sa';
			//print_r ($Aiprice);
			$sqlf="SELECT * FROM `costpricelistmaster` where MonthID>0 ORDER BY `costpricelistmaster`.`YearID`  DESC"; 	
            $resultf = mysql_query($sqlf); 
			$in=1;$rowni=0;
	//print $YearID=14;
   while($resqueryf = mysql_fetch_assoc($resultf))
                   { $rowni++;
					if ($rowni==1) {$YearIDi[1]=$resqueryf['YearID'];$I[1]=$resqueryf['Ii'];$Idate[1]=$resqueryf['Idate'];}
					if ($rowni==2) {$YearIDi[2]=$resqueryf['YearID'];$I[2]=$resqueryf['Ii'];$Idate[2]=$resqueryf['Idate'];}
					if ($rowni==3) {$YearIDi[3]=$resqueryf['YearID'];$I[3]=$resqueryf['Ii'];$Idate[3]=$resqueryf['Idate'];}
		            if ($resqueryf['YearID']==$YearID)
							{
  							    $YearIDi[4]=$resqueryf['YearID'];
								$I[4]=$resqueryf['Ii'];
								$Idate[4]=$resqueryf['Idate'];
							}	
					} 
							
   
   /* while($resqueryf = mysql_fetch_assoc($resultf))
                   { $rown++;
				    if ($in>3) continue;
					if ($rown==1) {$YearIDi[1]=$resqueryf['YearID'];$I[1]=$resqueryf['Ii'];$Idate[1]=$resqueryf['Idate'];}
			       
	                  if ($resqueryf['YearID']<=$YearID)
					   {				
							if ($resqueryf['YearID']==$YearID)
							{
  							    $YearIDi[4]=$resqueryf['YearID'];
								$I[4]=$resqueryf['Ii'];
								$Idate[4]=$resqueryf['Idate'];
							}	
							else 
							{	if ($in<3){
								$YearIDi[$in+1]=$resqueryf['YearID'];
								$I[$in+1]=$resqueryf['Ii'];
								$Idate[$in+1]=$resqueryf['Idate'];
								$in++;
										}				
	
							}
						} 
						   
				  }
	*/			  

	//	print_r($I);print '</br>';print_r($Idate);print '</br>';print_r($YearIDi);
	
	for ($k=0; $k < 4; ++$k)
       {
	   //$tend
	    $T1=(strtotime($tend) - strtotime($Idate[1]))/31536000;
	   	if ($Pb<=200) $T2=0.2;if ($Pb>200 && $Pb<=500) $T2=0.15+$Pb/2000;if ($Pb>500 && $Pb<=1000) $T2=0.5;if ($Pb>1000) $T2=0.05+$Pb/2000;
    
		$Betazarib=round(($I[1]/$I[4]),3);
		$yzarib=round((1+((0.5*($I[1]-$I[2])*0.5*$T2)/(($I[1]+$I[2]+$I[3])/3+($I[1]-$I[3])/2+0.5*($I[1]-$I[3])*$T1))),2);
		}	
		
	$Po=round($Pb*$Betazarib*$yzarib,1);
	if ($rown<7) $tzarib=1.1;else if ($rown<=10 && $rown>=7) $tzarib=1.3;else if ($rown>10) $tzarib=1.5;
//	print_r($Aiprice);	print $rown;
	$maxprice=$Aiprice[$rown];
	if ($Aiprice[1]>($Pb*0.15)) $minprice=$Aiprice[1]; else $minprice=($Pb*0.15);
	$minXi=round(100*$minprice/$Po,1);
	$maxXi=round(100*$maxprice/$Po,1);
	//print $maxprice.'*'.$maxXi;
$k=0;	
//	for ($k=0; $k < $rown; ++$k)
$nix=$rown;
 while ($k<=$nix)
{ $k++;
	$sumXi=0;$nix=0;
	foreach ($Aiprice as $key => $value ) {
	//echo $key . ' => ' . $value . '<br/>';
	    if ($value<=$maxprice && $value>=$minprice) {$nix++;$Xindex[$value]=round(100*$value/$Po,1);$sumXi=$Xindex[$value]+$sumXi; } 
		}
        if ($nix>0)
	$average=round($sumXi/$nix,2);
    //print " $rown  $k $nix  </br>";
	$sumstdev=0;$ix=0;
    if ($Xindex)
  foreach ( $Xindex as $key => $value ) {
  //echo $key . ' => ' . $value . '<br/>';
		if ($value<=$maxXi && $value>=$minXi) {$ix++;$sumstdev=($value-$average)*($value-$average)+$sumstdev;}
		  }
  if ($ix<=1) $stdev=0; else $stdev=round(sqrt($sumstdev/($ix-1)),1);
  if ($average<=115) $Bzarib=1.25*$average; else $Bzarib=1.1*$average;
  $maxXi=$Bzarib;
  $maxprice=$Po*$maxXi/100;
  $minXi=$Bzarib*0.15;
  $minprice=$Po*$minXi/100;
			  
//	 print  '*'.$maxXi.'*'.$maxprice.'*';
 }
 
	$C1=round (($average-$tzarib*$stdev),2);
	$C2=round (($average+$tzarib*$stdev),2);
if 	($rown<5) $C1=round((0.97*$C1),2);
	return $C1."_".$C2."_".$Po."_".$Betazarib."_".$yzarib."_".$average."_".$stdev."_".$tzarib;
}

function is_connected()//تابع بررسی اتصال اینترنت
{
    $connected = 1; 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}


  function gregorian_to_jalali($dat)//تابع تبدیل میلادی به شمسی
  {
     $d=array();
     for($row=1;$row<=12;$row++)
        $d[$row]=0;
  
     $dy=array();
     for($row=1;$row<=7;$row++)
        $dy[$row]='';
  
  $yy=year($dat);
  $d[1]=31;
  if (($yy % 4)==0) 
     $d[2]=29;
  else
     $d[2]=28;
  
  $dy[1]='يکشنبه';       
  $dy[2]='دوشنبه';        
  $dy[3]='سه شنبه';       
  $dy[4]='چهار شنبه';    
  $dy[5]='پنج شنبه';     
  $dy[6]='جمعه';          
  $dy[7]='شنبه';              
  $d[3]=31;
  $d[4]=30;
  $d[5]=31;
  $d[6]=30;
  $d[7]=31;
  $d[8]=31;
  $d[9]=30;
  $d[10]=31 ;
  $d[11]=30;
  $d[12]=31;
  
  $mm=0;
  for ($i=1;$i<=(month($dat)-1);$i++) 
      $mm=$mm+$d[$i];
  
  $yy=$yy-1;
  $dd=day($dat);
  $ldays =($yy*365)+floor(($yy-1)/4)+$mm+$dd;
  //print "<br>ldays:".$ldays;
  $idays=$ldays-226899;
  
  $ff=ltrim((month($dat)));
  $gg=ltrim((day($dat)));
  
  if (strlen($ff)<2)
     $ff='0'.$ff;
  
  if (strlen($gg)<2)
     $gg='0'.$gg;
  $hh=$ff.$gg;
  
  
  if ($hh<'0320' or ($hh=='0320' and year($dat)/4!=floor(year($dat)/4)))
     $yy=year($dat)-622;
  else
     $yy=year($dat)-621;
  
  $mm=$idays-floor(($yy-1)/4)-(($yy-1)*365);
  
  $yy1=year($dat)-1;
  if (($yy1/4)==floor($yy1/4) and $hh<='0320')
     $mm=$mm+1;
  
  if ((year($dat)/2)==floor(year($dat)/2) and (year($dat)/4)!=floor(year($dat)/4) and $hh<='0320')
     $mm=$mm-1;
  
  if ($mm<=186)
     {$dd=$mm%31;
     if ($dd==0)
        {$dd=31;
        $mm=floor($mm/31);}
     else
        $mm=floor($mm/31)+1;}
  else
     {
      $mm=$mm-186;
      $dd=$mm%30;
      if ($dd==0)
        {
  	   $dd=30;
         $mm=floor($mm/30)+6;
  	  }
      else
        $mm=floor($mm/30)+7;
     }
  
     $qstr=strr($yy,2).'/';
     
     if ($mm<10) 
        $qstr=$qstr.'0'.strr($mm,1).'/';
     else
        $qstr=$qstr.strr($mm,2).'/';
     
     if ($dd<10)
        $qstr=$qstr.'0'.strr($dd,1);
     else
        $qstr=$qstr.strr($dd,2);
  
  return($qstr);
  }
  Function year($datee='')
  {
   return(substr($datee,0,4));
  }
  Function month($datee='')
  {
   return(substr($datee,5,2));
  }
  Function day($datee='')
  {
   return(substr($datee,8,2));
  }
  Function strr($strx='',$count=10)
  {
    $strx=Trim($strx);
    $len=strlen($strx);
    return(space($count-$len).$strx);
  }
   function Space($NumberOfStringOfSpace=0)
  {  
    $strd='';
    for ($inc=1;$inc<=$NumberOfStringOfSpace;$inc++)
        $strd=$strd.' ';
    return($strd);
  }	

function mykeyvalsort ($input_arr, $function="asort")//تابع مرتب سازی یک آرایه کلید و مقدار
{
	$converted = $result = array();
	
	$alphabet = array(
		'$A$' => "?",	'$B$' => "?",	'$C$' => "?",
		'$D$' => "?",	'$E$' => "?",	'$F$' => "?",
		'$G$' => "?",	'$H$' => "?",	'$I$' => "?",
		'$J$' => "?",	'$K$' => "آ",	'$L$' => "ا",
		'$M$' => "أ",	'$N$' => "إ",	'$O$' => "ؤ",
		'$P$' => "ئ",	'$Q$' => "ء",	'$R$' => "ب",
		'$S$' => "پ",	'$T$' => "ت",	'$U$' => "ث",
		'$V$' => "ج",	'$W$' => "چ",	'$X$' => "ح",
		'$Y$' => "خ",	'$Z$' => "د",	'$a$' => "ذ",
		'$b$' => "ر",	'$c$' => "ز",	'$d$' => "ژ",
		'$e$' => "س",	'$f$' => "ش",	'$g$' => "ص",
		'$h$' => "ض",	'$i$' => "ط",	'$j$' => "ظ",
		'$k$' => "ع",	'$l$' => "غ",	'$m$' => "ف",
		'$n$' => "ق",	'$o$' => "ک",	'$p$' => "گ",
		'$q$' => "ل",	'$r$' => "م",	'$s$' => "ن",
		'$t$' => "و",	'$u$' => "ه",	'$v$' => "ي",
		'$w$' => "ي",	'$x$' => "?",	'$y$' => "ة"
	);
	
	foreach($input_arr as $input_str => $input_key)
	{
		if(is_string($input_str))
			foreach($alphabet as $e_letter => $f_letter)
				$input_str = str_replace($f_letter , $e_letter, $input_str);
		
		if(is_array($input_str))
			$input_str = psort($input_str, $function);
		
		$converted[$input_key] = $input_str;
	}
	
	$ret = $function($converted);	// Run function
	$converted = is_array($ret) ? $ret : $converted;	// Check for function output. Some functions affect input itself and retuen bool...
	
	foreach($converted as $converted_key => $converted_str)
	{
		if(is_string($converted_str))
			foreach($alphabet as $e_letter => $f_letter)
				$converted_str = str_replace($e_letter , $f_letter, $converted_str);
		
		if(is_array($converted_str))
			$converted_str = psort($converted_str, $function);
		
		$result[$converted_str] = $converted_key;
	}
	
	return $result;
 } 
	function get_key_value_from_query_into_array($query)
  {
    $returned_array='';
    $result = mysql_query($query);

	$returned_array[' ']=' ';
    if ($result)
	while($row = mysql_fetch_assoc($result))
      $returned_array[$row['_key']]=$row['_value'];
    //print "salam".$query;
    
    
     return $returned_array;
  }
  
  
  //تابع ایجاد کومبو باکس
  function select_option($name,$lable='',$accesskey='',$option=array('title'=>'value'),$tabindex=0,$empty_title_string='',
  $disabled_str='',$colspan=1,$dir='rtl',$size='0',$class='',$default_number='',$event='',$width='',$type='',$border='1; border-color: #D1D1D1; ',$height='')
  {

    $result='';
    //-------------------------------------------------------------
    //-------------------------------------------------------------
    if ($height!='')
    $heightstr='height:$height;';    
 if ($width=='' && $type!='hidden')
    {
	     $width="100%";
		
	}
    if ($width!='' && $type!='hidden')
    {
        //print $width;
        //print strstr('%',$width);
        //exit;
        //if (strstr($width,'%')>0)
    	$width="style='width: ".$width.";border:$border;$heightstr'";
	}

    if ($lable!='')
    {
      $result=$result."<td  style='text-align:left' >$lable</td>";
    }
    if ($size > 0 && $type!='hidden')
	  $size="size='$size'";
	else
	  $size=" ";

	  $result=$result."<td class='data' colspan='$colspan'>";

	if ( is_array($option))
    {
    $selectedTitle="";
	foreach($option as $title => $value)
    {
        
      if (($default_number<>'') and ($default_number==$value))
        $selectedTitle='('.$title.')';   
    }
    }
    if ($type!='hidden')
		  $result=$result."<div id='div$name'><select $width $disabled_str  name='$name' id='$name' $size dir='$dir' tabindex=\"$tabindex\" class='$class' $event  onmouseover=\"Tip('$selectedTitle')\">";
          else 
          $result=$result."<div id='div$name' style='visibility: hidden;width:1px;'><select $width $disabled_str  name='$name' id='$name' $size dir='$dir' tabindex=\"$tabindex\" class='$class' $event  onmouseover=\"Tip('$selectedTitle')\">";
    //-------Empty Option Check---------
      if (isset($_POST[$name]))
      if (($_POST[$name]==0) and ($default_number==0))
        $is_selected='selected="selected"';
			else
			  $is_selected='';
			if ($empty_title_string!='')
        $result=$result."<option $width value='0' $is_selected>$empty_title_string</option>";
    //---------Option Array---------
		
	if ( is_array($option))
	foreach($option as $title => $value)
    {
        
        	if (isset($_POST[$name]))
            if ($_POST[$name]==$value)
        $is_selected='selected="selected"';
      if (($default_number<>'') and ($default_number==$value))
        $is_selected='selected="selected"';
      else
        $is_selected='';

      if ($type!='hidden') $result.="<option  value='$value' $is_selected>$title</option>";
    }
    //---------------------------------
    if ($type!='hidden') $result=$result."</select></div>";

    $result=$result."</td>";

    return $result;
  }
function rettotalsumtarh($ApplicantMasterID='',$l_ProducersID="0")//تابع دریافت هزینه کل طرح
  {
    
        $sql = "SELECT applicantmaster.costpricelistmasterID,applicantmaster.transportless,applicantmaster.DesignArea,ifnull(applicantmaster.DesignerCoID,0) DesignerCoID ,
        ApplicantName,othercosts1,othercosts2,othercosts3,othercosts4,othercosts5,transportcosttable.unpredictedcost,
        case ifnull(applicantmaster.coef1,0) when 0 then transportcosttable.coef1 else applicantmaster.coef1 end coef1,
        case ifnull(applicantmaster.coef2,0) when 0 then transportcosttable.coef2 else applicantmaster.coef2 end coef2,
        case ifnull(applicantmaster.coef3,0) when 0 then transportcosttable.coef3 else applicantmaster.coef3 end coef3,
        case ifnull(applicantmaster.coef4,0) when 0 then transportcosttable.coef4 else applicantmaster.coef4 end coef4,
        case ifnull(applicantmaster.coef5,0) when 0 then transportcosttable.coef5 else applicantmaster.coef5 end coef5,
        
        transportcosttable.Cost,case DesignSystemGroupsID when 1 then ROUND(raindesigncosttable.cost*applicantmaster.DesignArea) 
        else ROUND(dropdesigncosttable.cost*applicantmaster.DesignArea) end designcost 
        ,concat(designer.Lname,' ',designer.Fname) designerTitle,designerco.Title DesignerCoTitle,operatorco.Title operatorcoTitle, applicantmaster.operatorcoid 
        ,yearcost.Value fb
        ,operatorco.AccountNo operatorcoAccountNo,operatorco.AccountBank operatorcoAccountBank
        ,substring(applicantmaster.cityid,1,5) cityid15
        FROM applicantmaster 
        
        left outer join transportcosttable on transportcosttable.TransportCostTableMasterID=applicantmaster.TransportCostTableMasterID
        and applicantmaster.DesignArea between transportcosttable.MinArea and transportcosttable.MaxArea
        
        left outer join raindesigncosttable on raindesigncosttable.RainDesignCostTableMasterID=applicantmaster.RainDesignCostTableMasterID
        and applicantmaster.DesignArea between raindesigncosttable.MinArea and raindesigncosttable.MaxArea
        
        
        left outer join dropdesigncosttable on dropdesigncosttable.DropDesignCostTableMasterID=applicantmaster.DropDesignCostTableMasterID
        and applicantmaster.DesignArea between dropdesigncosttable.MinArea and dropdesigncosttable.MaxArea
        
        left outer join costpricelistmaster on costpricelistmaster.costpricelistmasterID=applicantmaster.costpricelistmasterID
        left outer join month as monthcost on monthcost.MonthID=costpricelistmaster.MonthID 
        left outer join year as yearcost on yearcost.YearID=costpricelistmaster.YearID 

        
        left outer join designer on designer.designerid=applicantmaster.designerid
        left outer join designerco on designerco.DesignerCoid=applicantmaster.DesignerCoid
        left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
    
        WHERE ApplicantMasterID = '$ApplicantMasterID'";
        
        //print $sql;
        //exit;
        $count = mysql_fetch_assoc(mysql_query($sql));
        $ApplicantName = $count['ApplicantName'];
        $designerTitle = $count['designerTitle'];
        $cityid15=$count['cityid15'];
        $opacc="";
        if ($count['operatorcoid']>0)
        {
            $CoTitle = $count['operatorcoTitle'];
            $opacc = "($CoTitle $count[operatorcoAccountNo] $count[operatorcoAccountBank])";
        }
        else $CoTitle = $count['DesignerCoTitle'];
        $operatorcoid=$count['operatorcoid'];
        
        
            $transportless= $count['transportless'];
        if ($transportless>0)      
           $transportless="checked";
        $costpricelistmasterID=$count['costpricelistmasterID'];
        $DesignArea= $count['DesignArea'];
        $othercosts1=$count['othercosts1'];
        $othercosts2=$count['othercosts2'];
        $othercosts3=$count['othercosts3'];
        $othercosts4=$count['othercosts4'];
        $othercosts5=$count['othercosts5'];
        $Cost=$count['Cost'];
        //$designcost=$count['designcost'];
        $designcost=0;
        
        if ($wincoef1>0) $coef1=round($wincoef1,2); else $coef1=round($count['coef1'],2);
        if ($wincoef2>0) $coef2=round($wincoef2,2); else $coef2=round($count['coef2'],2);
        if ($wincoef3>0) $coef3=round($wincoef3,3); else $coef3=round($count['coef3'],3);
        $coef4=round($count['coef4'],2);
        $coef5=round($count['coef5'],2);
        $unpredictedcost=$count['unpredictedcost'];
        $DesignerCoID=$count['DesignerCoID'];
        $fb=$count['fb'];
        $pr=$count['pr'];
        
        $arrayinvoices = array();
        $arrayindexinvoice=0;
  
    ////////////////بخش چاپ پيش فاکتور/ليست لوازم ها
    $condlimited='';
        
        //if ($DesignerCoID>0) $condlimited=' and pfd=1 '; 
        if ($l_ProducersID!="0")
        $pcond="  and ifnull(invoicemaster.proposable,0)=1";
        else $pcond="";
        
        $sql2="select PE32app,PE40app,PE80app,PE100app,ProducersID from producerapprequest where state=1 and ApplicantMasterID='$ApplicantMasterID'";    
       
       //print $sql2;
        $result2 = mysql_query($sql2);
        $row2 = mysql_fetch_assoc($result2);
        if ($row2['PE32app']>0 or $row2['PE40app']>0 or $row2['PE80app']>0 or $row2['PE100app']>0 )
        {
            $guerypipeprice="left outer join (select '$row2[ProducersID]' ProducersID, '$row2[PE32app]' PE32,'$row2[PE40app]' PE40,'$row2[PE80app]' PE80,'$row2[PE100app]' PE100 )
             pipeprice on pipeprice.ProducersID=toolsmarks.ProducersID";
        }
        else $guerypipeprice="left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=invoicemaster.InvoiceDate $condlimited) and pipeprice.ProducersID=toolsmarks.ProducersID"; 
        
        
    $sql = "
        SELECT taxpercent.value taxpercentvalue,ifnull(invoicemaster.taxless,0) taxless,invoicemaster.costnotinrep,invoicemaster.pricenotinrep,invoicemaster.InvoiceMasterID,invoicemaster.ProducersID,invoicemaster.TransportCost,
    invoicemaster.Discont,invoicemaster.InvoiceDate,invoicemaster.Rowcnt,invoicemaster.Serial,invoicemaster.Title
    ,producers.Title as PTitle,producers.AccountNo,producers.AccountBank,producers.PipeProducer,invoicemaster.Description,toolsmarks.ProducersID,invoicedetail.InvoiceDetailID,
            gadget3.Code,gadget3.gadget3ID,gadget2.gadget2ID,toolsmarks.MarksID,units.
        title utitle,invoicedetail.Number,pricelistdetail.pricelistdetailID
        ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         gadget3Title
        ,marks.Title as MarksTitle
        ,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else case ifnull(syntheticgoodsprice.gadget3ID,0) when 0 then pricelistdetail.Price else 
            syntheticgoodsprice.price end  end Price,invoicemaster.PriceListMasterID
            
        ,case gadget3.gadget2id when 495 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe32num
        ,case gadget3.gadget2id when 494 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe40num
        ,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe80num
        ,case gadget3.gadget2id when 376 then ROUND(gadget3.UnitsCoef2*invoicedetail.Number*1000)/1000 else 0 end pe100num
        
        FROM invoicedetail 
        inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
        inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        left outer join units on gadget3.unitsID=units.unitsID
        inner join marks on marks.MarksID=toolsmarks.MarksID
        inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID and invoicemaster.ApplicantMasterID ='$ApplicantMasterID'
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join toolspref on toolspref.PriceListMasterID=invoicemaster.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistdetail.PriceListMasterID=invoicemaster.PriceListMasterID and 
                                            pricelistdetail.toolsmarksID = (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        
         left outer join syntheticgoodsprice on syntheticgoodsprice.PriceListMasterID=invoicemaster.PriceListMasterID and 
        syntheticgoodsprice.gadget3ID=gadget3.gadget3ID
        
        inner join producers on producers.ProducersID=invoicemaster.ProducersID $pcond
        
        $guerypipeprice
        
                
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        left outer join year on year.Value = substring(invoicemaster.InvoiceDate,1,4)
        left outer join taxpercent on year.YearID=taxpercent.YearID
        ORDER BY cast(invoicemaster.Serial as decimal),invoicedetail.invoicemasterID,invoicedetail.InvoiceDetailID;
        ";
        //print $sql;
        //exit;
    $InvoiceMasterIDold=0;
    $totalpe32num=0;
    $totalpe40num=0;
    $totalpe80num=0;
    $totalpe100num=0;
    $totalnetvalue=0;
    $result = mysql_query($sql);
    while($resquery = mysql_fetch_assoc($result))
    {
        $totalpe32num+=$resquery['pe32num'];
        $totalpe40num+=$resquery['pe40num'];
        $totalpe80num+=$resquery['pe80num'];
        $totalpe100num+=$resquery['pe100num'];
        $totalnetvalue+=$resquery['Number']*$resquery['Price'];
        
        if ($InvoiceMasterIDold<>$resquery['InvoiceMasterID'])
        {
            
            if ($InvoiceMasterIDold>0)
            {
               
                  
                
                if (! $pricenotinrep) 
                {
                    $arrayindexinvoice++;
                    if ($operatorcoid>0)
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);  
                    else
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100); 
                         
                }
            }

            
            
            
            $InvoiceMasterIDold=$resquery['InvoiceMasterID']; 
            $taxless=$resquery['taxless'];
            $masterProducersID = $resquery['ProducersID'];
            $TransportCost = $resquery['TransportCost'];
            $Discont = $resquery['Discont'];                        
            $np = $resquery['Rowcnt'];
            $Serial = $resquery['Serial'];
            $Title = $resquery['Title'];
            $AccountNo = $resquery['AccountNo'];
            $AccountBank = $resquery['AccountBank'];
            $PTitle = $resquery['PTitle'];
            $Description = $resquery['Description'];
            $InvoiceDate = $resquery['InvoiceDate'];
            $pricenotinrep = $resquery['pricenotinrep'];
            $costnotinrep = $resquery['costnotinrep'];
            $owncost='';
            if ($pricenotinrep) $owncost='خريد لوازم با هزينه شخصي متقاضي';
            if ($costnotinrep) $owncost.='  لوازم اجرا شده ';
            if ($owncost!='') $owncost="($owncost)";
            $TAXPercent=0;
            if (strlen($resquery['InvoiceDate'])>0 && $taxless==0)
                $TAXPercent = $resquery['taxpercentvalue'];     
                
           
                
              
                $cnt=0;
                $rown=0;
                $sum=0;
                
        }
        
            $InvoiceDetailID = $row['InvoiceDetailID'];
            $Code = $resquery['Code'];
            $gadget3ID = $resquery['gadget3ID'];
            $gadget2ID = $resquery['gadget2ID'];
            $gadget1ID = $resquery['gadget1ID'];
            $ProducersID = $resquery['ProducersID'];
            $MarksTitle='';
            //if ($resquery['MarksTitle']!='--' && $resquery['MarksTitle']!='..')
            $MarksTitle=$resquery['MarksTitle'];
            $utitle = trim($resquery['utitle']);
            $gadget3Title = $resquery['gadget3Title'];
            $Number = ($resquery['Number']);
            $Price = number_format($resquery['Price']);
            $SumPrice = number_format($resquery['Number']*$resquery['Price']);
            $Description = $resquery['Description'];
            $sum+=$resquery['Number']*$resquery['Price'];     
            $readonlydesc='';        
            if ($login_RolesID!=11 && $login_RolesID!=14)
                $readonlydesc='readonly';
            
            if ($Number>0)
            {
                   
                $rown++;  
            } 
        

        
           
        
        
    
    }
                      
                
                if (! $pricenotinrep) 
                {
                    $arrayindexinvoice++;
                    if ($operatorcoid>0)
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);  
                    else
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100); 
                         
                }
    /////////////////////////بخش هزينه هاي اجرايي
        
    if (in_array($login_RolesID, $permitrolsidforviewdetail) ||in_array($login_RolesID, $permitrolsidforviewdetailcost) ) $globalprint.= "</table><table width=\"95%\" align=\"center\"><p class=\"page\"></p> 
    
    <tr >        
                     <td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                   <td colspan='12' align='center' class='f1_font'>$titles هزینه های اجرایی </td>
                   

                <tr >  
                
                
  
               <tr >        
                   <td ></td>
                    <td colspan='12' align='center' class='f1_font'>طرح $ApplicantName </td> 
                    
                 </tr> 
                 
                      ";
        $fcond="";
    if ($type==12)
        {
            $fcond=" and appfoundationID='$PCoID'";
        }
        $sqlouterauto="";
    $fautomatic=1;
    $fmandal=1;
    $sqlouter=fehrestquery($fautomatic,$fmandal,$ApplicantMasterID,$costpricelistmasterID,$cityid15,$fcond).$login_limited;
    
    //print $sqlouter;exit;
    //$globalprint.= $sqlouter;
    $oldToolsGroupsCode=0;
    $oldftype='';
    $oldCostsGroupsTitle='';
    $rown=0;
    $sumin=0;
    $resultouter = mysql_query($sqlouter);
    $arraycosts = array();
    $rownf=1;                    
    while($resquery = mysql_fetch_assoc($resultouter))
    {
        $SumPrice = $resquery['Total'];
        $Price = $resquery['Price'];
        $Price2 = $resquery['price2'];
        $appfoundationtitle=$resquery['appfoundationtitle'];
        $unit = $resquery['unit'];
        $Number = $resquery['Number'];
        $Title = $resquery['Title'];
        $CostsGroupsTitle = $resquery['ftype'].' '.$resquery['ToolsGroupsCode'].': '.$resquery['CostsGroupsTitle'];
        $Code = $resquery['Code'];
        $ToolsGroupsCode = $resquery['ToolsGroupsCode'];
        $ID=$resquery['Code'].'_'.$ApplicantMasterID;
        $ftype=$resquery['ftype'];
        $sazetitle='';
        if ($ftype<>'آبیاری تحت فشار')
            $sazetitle='سازه';
        
        
        if ($oldToolsGroupsCode<>$ToolsGroupsCode)
        {
            
            if ($oldToolsGroupsCode>0 || ($oldftype!=''))
            {
                if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "<tr>
                      <td colspan='1' class='f20_font' ></td>
                      <td colspan='11' class='f11_fontt' ></td>
                      <td colspan='1'  class='f11_fontt'>".number_format($sumin)."</td>
                      </tr>
                      
                      
                      </div>"; 
                if (isset($CostsGroupsTitle))
                $arraycosts[($rownf++)."-".$oldCostsGroupsTitle]=$sumin;
                $rown=0;
                $sumin=0;
            }
            
            
            $oldToolsGroupsCode=$ToolsGroupsCode;
            $oldCostsGroupsTitle=$CostsGroupsTitle;
            
        }
        
        if ($oldftype<>$ftype)
        {
            /*
            if ($oldftype!='')
        if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "<tr>
                      <td colspan='1' class='f20_font' ></td>
                      <td colspan='11' class='f11_fontt' ></td>
                      <td colspan='1'  class='f11_fontt'>".number_format($sumin)."</td>
                      </tr>
                      
                      <tr>
                      <td colspan='13' >&nbsp</td>
                      </tr>
                      
                      </div>"; */
                      
            if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "<div >
            
                        
                <tr>
                
                <td colspan='1'></td>
                    <td colspan='3' class='f6_font'>   فهرست بهای:  </td>
                    <td colspan='9' class='f6_font'>    $ftype   $fb</td>
                    
                    </tr>
                    
                    
                    <tr>
                        	<th align='center'  ></th>
                        	<th align='center' class='f21_font' >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th align='center' class='f21_font'>شرح</th>
                            <th align='center' class='f21_font'>$sazetitle</th>
                            <th align='center' class='f22_font'>واحد</th>
                            <th align='center' colspan='3' class='f22_font'>مقدار</th>
                            <th align='center' colspan='3' class='f22_font'>بها(ریال)</th>
                            <th align='center' class='f22_font'>بهای کل(ریال)</th>
                    </tr>
                        
                        
                ";
                $oldToolsGroupsCode=0;
                
            $sumin=0;
        }
        
        
        
        $oldftype=$ftype;
        if ($Number>0)
        {
            $rown++;
            $sumin+=$SumPrice;
            if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost))
            {
                
                
                if ($resquery['TCode']==3 || $resquery['TCode']==4)
                {
                    $pval2='';
                    $pval3='';
                    $nval2='';
                    $nval3='';
                    
                    $nval1=round($resquery['nval1'],2);
                    if ($resquery['nval3']>0)
                    $nval3=round($resquery['nval3'],2);
                    if ($resquery['nval2']>0)
                    $nval2=round($resquery['nval2'],2);
                    
                    $pval1=($resquery['pval1']);
                    if ($resquery['pval2']>0)
                    $pval2=($resquery['pval2']);
                    if ($resquery['pval3']>0)
                    $pval3=($resquery['pval3']);
                    if ($resquery['TCode']==3)
                    $fntcl="f21_fontr";
                    else 
                    $fntcl="f24_font";
                    $Number=round($Number,2);
                    
                     $globalprint.= "     <tr>
                            <td rowspan='2' class='print'  class='f9_font'></td>
                            <td rowspan='2' class='$fntcl'>$rown</td>
                            <td rowspan='2' class='$fntcl'>$ToolsGroupsCode</td>
                            <td rowspan='2' class='$fntcl'>$Code</td>
                            <td rowspan='2' class='$fntcl'><div style=\"width: 260px;\">$Title</div></td>
                            <td rowspan='2' class='$fntcl'>$appfoundationtitle</td>
                            <td rowspan='2' class='$fntcl'>$unit</td>
                            <td colspan='3'  >
                           
                            <div id='divFNumber$resquery[tblid]' style=\"width: 130px;\">      
                            <input  $readonlydesc  name='FNumber$resquery[tblid]' type='text' class='f11_fontn' style=\"width: 127px;\" 
                                  id='FNumber$resquery[tblid]'     value='".$Number."'  /></div></td> ";
                                  
                                  if ($Price2<>$Price)
                                  $globalprint.=  "

                            
                           <td colspan='3'  >
                            <div id='divPrice$resquery[tblid]' style=\"width: 130px;\">      
                            <input  $readonlydesc  name='Price$resquery[tblid]' type='text' class='f11_fontnR' style=\"width: 127px;\" 
                                  id='Price$resquery[tblid]'     value='".number_format($Price)."'  /></div></td>";
                                  else
                                  $globalprint.= "
                                  <td colspan='3'  >
                            <div  style=\"width: 130px;\">      
                            <input  readonly   type='text' class='f11_fontnRf' style=\"width: 127px;\" 
                                       value='".number_format($Price)."'  /></div></td>
                                  ";
                                  
                                  
                                  $globalprint.=  "
                                  
                                  
                                  
                                                        
                            <td rowspan='2' class='$fntcl'>".number_format($SumPrice)."
                                  <input  $readonlydesc  name='TCode$resquery[tblid]' type='hidden'
                                  id='TCode$resquery[tblid]'     value='$resquery[TCode]'  /></td>
                            ";
                        
                            if ($Code>0 && ($resquery['TCode']==2))
                                if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                                    $globalprint.= "<td><a class='no-print' href=summaryinvoice_detail.php?np=10&uid=".
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).">ریز</a></td>";
                            print "</tr>";
                    
					
						$th1='';$th2='';$th3='';$th4='';$th5='';$th6='';
                        
                        
                        
			    if ($nval1>0 && $Number<>$nval1)  $th1='h';
				if ($nval2>0 && $Number<>$nval2) $th2='h';
				if ($nval3>0 && $Number<>$nval3) $th3='h';
                if ($pval1>0 && $Number<>$pval1)  $th4='h';
				if ($pval2>0 && $Number<>$pval2) $th5='h';
				if ($pval3>0 && $Number<>$pval3) $th6='h';
             
					
                            if ( ($nval1>0 && $Number<>$nval1) 
                            
                            || ($nval2>0 && $Number<>$nval2) || ($nval3>0 && $Number<>$nval3) || ($nval2>0 && $nval1<>$nval2) || ($nval3>0 && $nval1<>$nval3)
                            || ($pval1>0 && $Price<>$pval1) || ($pval2>0 && $Price<>$pval2) || ($pval3>0 && $Price<>$pval3) 
                             || ($pval2>0 && $pval1<>$pval2) || ($pval3>0 && $pval1<>$pval3)
                             
                             
                              )
                            {
                                if (!($nval3>0))
                                    $nval3='';
                                $globalprint.= "<tr>
                                    <td class='f11_fontt$th1'><font color='blue'>$nval1</font></td>
                                    <td class='f11_fontt$th2'><font color='red'>$nval2</font></td>
                                    <td class='f11_fontt$th3'><font color='green'>$nval3</font></td>
                                    <td class='f11_fontt$th4'><font color='blue'>".number_format($pval1)."</font></td>
                                    <td class='f11_fontt$th5'><font color='red'>".number_format($pval2)."</font></td>
                                    <td class='f11_fontt$th6'><font color='green'>".number_format($pval3)."</font></td>
                                </tr>"; 

                            }
    
                            else
                            {
                                $globalprint.= "<tr>
                                <td class='f11_fontt'><font color='blue'></font></td>
                                <td class='f11_fontt'><font color='red'></font></td>
                                <td class='f11_fontt'><font color='green'></font></td>
                                <td class='f11_fontt'><font color='blue'></font></td>
                                <td class='f11_fontt'><font color='red'></font></td>
                                <td class='f11_fontt'><font color='green'></font></td>
                                </tr>";
    
                            }
                        
                }
                else    
                {
                    
                        $readonlydesc='readonly';   
                    if (($type==3) && ($login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ||$login_RolesID==4
                    ||$login_RolesID==2||$login_RolesID==9) )
                        $readonlydesc=''; 
            
                    $globalprint.= "<tr>
                                <td class='f23_font'></td>
                                <td class='f24_font'>$rown</td>
                                <td class='f25_font'>
                                <div id='divGCode$resquery[gadget3operationalID]' style=\"width: 30px;\">      
                            <input  $readonlydesc  name='GCode$resquery[gadget3operationalID]' type='text' class='f11_fontn' style=\"width: 27px;\" 
                                  id='GCode$resquery[gadget3operationalID]'     value='$ToolsGroupsCode'  />
                                  <input  $readonlydesc  name='oldGCode$resquery[gadget3operationalID]' type='hidden'
                                  id='oldGCode$resquery[gadget3operationalID]'     value='$ToolsGroupsCode'  />
                                  </div>
                            
                                  
                                </td>
                                <td class='f25_font'>
                                <div id='divCode$resquery[gadget3operationalID]' style=\"width: 45px;\">      
                            <input  $readonlydesc  name='Code$resquery[gadget3operationalID]' type='text' class='f11_fontn' style=\"width: 43px;\" 
                                  id='Code$resquery[gadget3operationalID]'     value='$Code'  />
                                  <input  $readonlydesc  name='oldCode$resquery[gadget3operationalID]' type='hidden'
                                  id='oldCode$resquery[gadget3operationalID]'     value='$Code'  />
                                  </div>
                                  </td>
                                  
                                <td class='f26_font'  ><div style=\"width:260px;\">$Title</div></td>
                                <td class='f24_font'>$appfoundationtitle</td>
                                <td class='f27_font'>$unit</td>
                                <td colspan='3' class='f30_font'>".round($Number,2)."</td>
                                <td colspan='3' class='f28_font'>".number_format($Price)."</td>
                                <td class='f29_font'>".number_format($SumPrice)."</td>
                            ";      
                    if ($Code>0  && ($resquery['TCode']==2))
                        if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                            $globalprint.= "<td><a class='no-print' href=summaryinvoice_detail.php?np=10&uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ID.rand(10000,99999).">ریز</a></td>";
                      
                }         
              
            }
            
            if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
                $globalprint.= "</tr>";

         
        }
         
    
    }

        if (in_array($login_RolesID, $permitrolsidforviewdetail)||in_array($login_RolesID, $permitrolsidforviewdetailcost)) $globalprint.= "<tr>
                      <td colspan='12'></td>
                      <td colspan='1'  class='f11_fontt'>".number_format($sumin)."</td>
                      </tr>
                      
                      <tr>
                      <td colspan='13' >&nbsp</td>
                      </tr>
                      
                      </div>"; 
    if (isset($CostsGroupsTitle)) 
        $arraycosts[($rownf++)."-".$oldCostsGroupsTitle]=$sumin;

			
    ////////////////////////////////////////////////تجهیز/////////////////////////////////////////
    
    $sqlt = "SELECT equip.Code,equip.Title,equip.equipID,appequip.Price,appequip.appequipID FROM equip 
    left outer join appequip on equip.equipID=appequip.equipID and ApplicantMasterID ='$ApplicantMasterID'
    where appequip.Price>0
    order by equip.Code" ;
    $resultt = mysql_query($sqlt);
    $r=mysql_num_rows($resultt);
    if ($r>0)
    {
        if (in_array($login_RolesID, $permitrolsidforviewdetail)|| in_array($login_RolesID, $permitrolsidforviewdetailcost)) 
        {
            $globalprint.= "<div >
                <tr>
                    <td colspan='1'></td>
                    <td colspan='2' class='f6_font'>   فهرست بهای:  </td>
                    <td colspan='10' class='f6_font'>    تجهیز کارگاه   </td>
                </tr>
                    <tr>
                        	<th align='center'  ></th>
                        	<th align='center' class='f21_font' >ردیف</th>
                            <th align='center' class='f21_font' >فصل</th>
                            <th align='center' class='f21_font' >کد</th>
                            <th colspan='7' align='center' class='f21_font'>شرح</th>
                            <th align='center' class='f22_font'>واحد</th>
                            <th align='center'  class='f22_font'>بها(ریال)</th>
                    </tr>";
            $rown=0;
            $sumtajhiz=0;
            while($resqueryt = mysql_fetch_assoc($resultt))
            {
                $rown++;
                $Price = $resqueryt['Price'];
                $sumtajhiz+=$Price;
                $Title = $resqueryt['Title'];
                $Code = $resqueryt['Code'];
                $globalprint.= "<tr>
                                <td class='f23_font'></td>
                                <td class='f24_font'>$rown</td>
                                <td class='f24_font'>42</td>
                                <td class='f24_font'>$Code</td>
                                <td colspan='7'  class='f26_font'  >$Title</td>
                                <td class='f27_font'>مقطوع</td>
                                <td  class='f28_font'>".number_format($Price)."</td>";
            }
            
                $globalprint.= "<tr>
                                <td class='f23_font'></td>
                                <td colspan='11' class='f24_font'>مجموع</td>
                                <td  class='f28_font'>".number_format($sumtajhiz)."</td>";      
        }
              
    }

    
    /////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
		 
         
        $globalprint.= " </table><table width=\"95%\" align=\"center\"><p class=\"page\"></p> 
        <div >
               <tr >        
                     <td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                             <td class='f2_font'></td>
                   <td colspan='4' align='center' class='f1_font'> خلاصه $titles هزینه های طرح </td>
                    <td class='f2_font'></td>
                    <td class='f2_font'></td>

                <tr >        
                   <td class='f2_font'></td>
                    <td colspan='7' align='center' class='f1_font'> $ApplicantName </td>
                 </tr>
                      <tr>
                      <td colspan='8'  >&nbsp</td>
                      </tr>
                      
                            
                ";
                $rown=0;
 		
		 
			$globalprint.= " <table width='50%' align='center' border=1>
                 ";


        $rowcounter=1;
	   foreach ($arrayinvoices as $i => $value)
        $rowcounter++;
				
		   $TotlaValues=0;
        foreach ($arrayinvoices as $i => $value) 
        {
            $rown++;
            if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
            {
                 if($rown==1)
    			$globalprint.= "   
                
                <tr >  
                                     <td  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                
                                        <td colspan=10 class='f31_font'>شرح هزینه</td> 
                                        <td colspan=1 class='f31_font'>قیمت (ریال)</td> 
                                        
                                    </tr>
                                    
                  <tr > 
    
                               <td  ></td>
                                
                                <td rowspan='$rowcounter'  align='center' class='f33_font'><div style=\"width:120px\">  خرید لوازم طرح و ایستگاه پمپاژ </div></td>
    							<td colspan=6  align='center' class='f34_font'><div >$i</div></td>
    							<td colspan=3 class='f35_font' ><div id='divSumPrice$rown' > <input  class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='20' readonly /></div></td>
                                <td class='f36_font'></td>
                            </tr>";
                else 
    			$globalprint.= "     <tr > 
    
                               <td  >&nbsp;&nbsp;&nbsp;</td>
                               
                                <td colspan=6  align='center' class='f34_font'><div >$i</div></td>
    							<td colspan=3 class='f35_font' ><div id='divSumPrice$rown' > <input  class='f37_font'  name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='20' readonly /></div></td>
                                <td class='f36_font'></td>
                            </tr>";
                //$globalprint.= $i." ".$value;               
            }

			$TotlaValues+=$value;


			
        }
        $TotlainvoiceValues=$TotlaValues;
        
        if (! in_array($login_RolesID, $permitrolsidforviewdetailcost))
        $globalprint.= " <tr > 
        <td  >&nbsp;&nbsp;&nbsp;</td>
                           
                            <td colspan=9  align='center' class='f34_font'><div >جمع لوازم(ریال)</div></td>
							<td class='f38_font'><div id='divSumPrice$rown' >      <input  class='f39_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($TotlainvoiceValues)."' readonly /></div></td>
                            
                        </tr>
                           
						   ";

        /////////////////////////////////محاسبه حمل
       //if ($issurat==1 && $pipeproposeval>0)
       //print "salam -$TotlainvoiceValues- $pipeproposeval";
       if ($pipeproposeval>0)
       $TransportCostunder=round($Cost*($TotlainvoiceValues-$pipeproposeval)/100); 
       else
        $TransportCostunder=round($Cost*$TotlainvoiceValues/100);   
        if (($operatorcoid>0) && ($transportless=="checked")) 
            $TransportCostunder=0;
        //$globalprint.= $TransportCost.'sa';
        
        ////////////////////////////////////////////
        
        if ($coef4==1)
            $rowcounter=3;
        else
            $rowcounter=4;
	   foreach ($arraycosts as $i => $value)
        $rowcounter++;
        
                $rown=0;
                $sumcosts=0;
				
        foreach ($arraycosts as $i => $value) 
        {
            $rown++;
			if($rown==1)
			$globalprint.= "     <tr >
           <td  class='f32_font'></td>
                                               
                            <td rowspan='$rowcounter'  align='center' class='f40_font'>عملیات اجرایی</td>
							<td colspan=6  align='center' class='f41_font'>$i</td>
							<td colspan='3' class='f42_font' ><div id='divSumPrice$rown'>      <input class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='23' readonly /></div></td>
                            <td style=\"border-top: 1px solid black;border-left: 1px solid black;border-color:#0000ff #0000ff;\"></td>
                        
                        </tr>";
            else
            $globalprint.= "     <tr >
                           
                           <td  >&nbsp;&nbsp;&nbsp;</td>
                            <td colspan=6  align='center' class='f41_font'>$i</td>
							<td colspan='3' class='f42_font' ><div id='divSumPrice$rown'>      <input class='f37_font' name='SumPrice$rown' type='text' class='textbox'       id='SumPrice$rown'       value='".number_format($value)."' maxlength='23' readonly /></div></td>
                            <td class='f43_font'></td>
                        
                        </tr>";
            //$globalprint.= $i." ".$value;
            $sumcosts+=$value;
        }
            $globalprint.= "     <tr > 
                           <td  >&nbsp;&nbsp;&nbsp;</td>
                            <td colspan=6  align='center' class='f41_font'><div >جمع فهرست بها(ریال) $opacc</div></td>
                            <td colspan='3' class='f42_font'><div id='divsumcosts'>      <input class='f37_font' name='sumcosts' type='text' class='textbox'       id='sumcosts'       value='".number_format($sumcosts)."' maxlength='23' readonly /></div></td>
                            <td class='f43_font'></td>
                        </tr>";
                        
            $rown++;
                
            if ($type!=1 )
                $readonlycoef="readonly";
            else if ($operatorcoid>0)    
                $readonlycoef=""; 
                 
            if ($coef4==1)
           $globalprint.="
           <tr >
                           
                           <td  >&nbsp;&nbsp;&nbsp;</td>
                            <td colspan=6  align='center' class='f41_font'>تجهیز و برچیدن کارگاه مقطوع</td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'       
                              value='".number_format($sumtajhiz)."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr>
            <tr > 
                            <td  >&nbsp;&nbsp;&nbsp;</td>
                           <td align='center' class='f14_font'><div style=\"width:180px\">جمع فهرست بها با اعمال ضرایب</div></td>
							<td class='f14_font'><div id='divcoef1'>      <input    name='coef1' type='text'  class='f44_font'      id='coef1'  $readonlycoef     value='$coef1'  maxlength='12' onchange=\"summ()\"  /></div></td>
                            <td class='f14_font'><div id='divcoef2' >      <input   name='coef2' type='text'    class='f44_font'     id='coef2' $readonlycoef      value='$coef2'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef3'>      <input  name='coef3' type='text'    class='f44_font'    id='coef3'   $readonlycoef    value='$coef3'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef4'>      <input  name='coef4' type='text'    class='f44_font'     id='coef4'  readonly     value='1'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef5'>      <input  name='coef5' type='text'    class='f44_font'     id='coef5'  readonly     value='$coef5'  maxlength='12' onchange=\"summ()\" /></div></td>
                           <td colspan='3' class='f14_font'></td>
                           <td >
                           <div id='divSumPricecosts'>      <input class='f45_font'
                            name='SumPricecosts' type='text' class='textbox'       id='SumPricecosts'       
                            value='".number_format($sumcosts*$coef1*$coef2*$coef3*1*$coef5+$sumtajhiz)."'  readonly /></div></td>
                        
                        </tr>";
           else
            $globalprint.= "     <tr > 
                            <td  >&nbsp;&nbsp;&nbsp;</td>
                           <td align='center' class='f14_font'><div style=\"width:180px\">جمع فهرست بها با اعمال ضرایب</div></td>
							<td class='f14_font'><div id='divcoef1'>      <input    name='coef1' type='text'  class='f44_font'      id='coef1'  $readonlycoef     value='$coef1'  maxlength='12' onchange=\"summ()\"  /></div></td>
                            <td class='f14_font'><div id='divcoef2' >      <input   name='coef2' type='text'    class='f44_font'     id='coef2' $readonlycoef      value='$coef2'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef3'>      <input  name='coef3' type='text'    class='f44_font'    id='coef3'   $readonlycoef    value='$coef3'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef4'>      <input  name='coef4' type='text'    class='f44_font'     id='coef4'  readonly     value='1'  maxlength='12' onchange=\"summ()\" /></div></td>
                            <td class='f14_font'><div id='divcoef5'>      <input  name='coef5' type='text'    class='f44_font'     id='coef5'  readonly     value='$coef5'  maxlength='12' onchange=\"summ()\" /></div></td>
                           <td colspan=3>
                           <div id='divSumPricecosts'>      <input class='f37_font'
                            name='SumPricecosts' type='text' class='textbox'       id='SumPricecosts'       
                            value='".number_format($sumcosts*$coef1*$coef2*$coef3*1*$coef5)."'  readonly /></div></td>
                            <td class='f43_font'> </td>
                            
                            
                        </tr>
                        
                         <tr >
                           
                           <td  >&nbsp;&nbsp;&nbsp;</td>
                            <td colspan=6  align='center' class='f41_font'>تجهیز و برچیدن کارگاه مقطوع</td>
							<td colspan='3' class='f42_font' >      
                            <input class='f37_font' type='text' class='textbox'       
                              value='".number_format($sumtajhiz)."' maxlength='23' readonly /></td>
                            <td class='f43_font'></td>
                        
                        </tr>
                        
                        <tr > 
        <td  >&nbsp;&nbsp;&nbsp;</td>
                           
                            <td colspan=9  align='center' class='f34_font'><div >جمع با ارزش افزوده ($coef4)</div></td>
							<td class='f38_font'><div id='divSumPrice$rown' >      
                            <input  class='f39_font' name='SumPrice$rown' type='text' class='textbox'      
                             id='SumPrice$rown'       value='".number_format(($sumcosts*$coef1*$coef2*$coef3*$coef5+$sumtajhiz)*$coef4 )."' readonly /></div></td>
                            
                        </tr>
                        
                        ";
                        
            $TotlaValues+=($sumcosts*$coef1*$coef2*$coef3*$coef5+$sumtajhiz)*$coef4 ;
            
            //$globalprint.= $login_OperatorCoID.'salam';
            if ($operatorcoid>0)
                $unpredictedval=0;
            else $unpredictedval=round($TotlaValues*$unpredictedcost/100);

            $TotlaValues=$TotlaValues+$othercosts1+$othercosts2+$othercosts3+$othercosts4+$othercosts5+$TransportCostunder+$designcost+$unpredictedval;
            if ($type!=1)
                $readonlyother="readonly";
            else
                $readonlyother="";
           
           
           if (($type==3) && ($login_RolesID==10 ||$login_RolesID==13  ||$login_RolesID==14 ||$login_RolesID==27 ) )
                $readonlyother=''; 
                             
         if ($operatorcoid>0) 
            $strtransportless="<input name='transportless' type='checkbox' id='transportless'  value='1' $transportless />";   
        
		
                $TotlaValuesperha='0';
                if ($DesignArea>0)
                    $TotlaValuesperha=$TotlaValues/$DesignArea;
            
    
       return $TotlaValues.'_'.$sumcosts.'_'.$coef1.'_'.$coef2.'_'.$coef3.'_'.$totalfehrestbaha.'_'.$TotlainvoiceValues.'_'.
       $TransportCostunder .'_'.$unpredictedcost.'_'.$totalpe32num.'_'.$totalpe40num.'_'.$totalpe80num.'_'.$totalpe100num.'_'.$totalnetvalue;
       //return '';          
  }

  function div($a,$b) {
	return (int) ($a / $b);
}
function compelete_date($dat)//ده رقمی نمودن تاریخ
{
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
        
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
        
    return $j_y.'/'.$j_m.'/'.$j_d ;
    
}

function jalali_to_gregorian($dat)//تبدیل تاریخ شمسی به میلادی
{
    
    $linearray = explode('/',$dat);
                            
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
 
 
   $jy = (int)($j_y)-979;
   $jm = (int)($j_m)-1;
   $jd = (int)($j_d)-1;
 
   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
   
   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $j_days_in_month[$i];
 
   $j_day_no += $jd;
 
   $g_day_no = $j_day_no+79;
 
   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;
 
   $leap = true;
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      $g_day_no--;
      $gy += 100*div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;
 
      if ($g_day_no >= 365)
         $g_day_no++;
      else
         $leap = false;
   }
 
   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
   $g_day_no %= 1461;
 
   if ($g_day_no >= 366) {
      $leap = false;
 
      $g_day_no--;
      $gy += div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
   }
 
   for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
   $gm = $i+1;
   $gd = $g_day_no+1;
    //if($str) 
    

         if ($gd<10) 
        $gd='0'.$gd;
        
         if ($gm<10) 
        $gm='0'.$gm;
        
        
    return $gy.'-'.$gm.'-'.$gd ;
    //return array($gy, $gm, $gd);
}


function returncolumns($tble,$_server_db)//تابع دریافت ستون های یک جدول
{
 $sqlt="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$_server_db' AND TABLE_NAME = '$tble' "; 
 $result = mysql_query($sqlt);
    while ($row = mysql_fetch_assoc($result))
    {
        if (strlen($coles)>0)
            $coles.=",".$row['COLUMN_NAME'];
        else
        $coles=$row['COLUMN_NAME'];    
    }  
    return $coles;
}
function insertsurat($applicantmasteridold,$Description,$login_userid,$_server, $_server_user, $_server_pass,$_server_db)//تابع درج صورت وضعیت
{
    $SaveTime=date('Y-m-d H:i:s');
    $SaveDate=date('Y-m-d');
    $ClerkID=$login_userid;
    
    $query = "SELECT *  FROM ApplicantMaster  where ApplicantMasterIDmaster='$applicantmasteridold'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if ($row['ApplicantMasterID']>0) return;         
    
    
    $query = "select  ClerkIDexaminer  from producers
    inner join invoicemaster on invoicemaster.ApplicantMasterID='$applicantmasteridold' and proposable=1 and producers.ProducersID=invoicemaster.ProducersID";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $ClerkIDexaminer=$row['ClerkIDexaminer'];
    
    
    $query = "SELECT max(stateno)+1 stateno FROM appchangestate 
             where ApplicantMasterID='$applicantmasteridold' ";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $maxstateno=$row['stateno'];
            
    if (!($login_userid>0)) header("Location: ../login.php");
                    $querytr= "
                    INSERT INTO applicantmaster(`Code`, `ApplicantName`, `ApplicantFName`, `CityId`, `DesignArea`, `operatorcoIDbandp`,
                     `Datebandp`, `isbandp`,  `othercosts4text`, `ClerkIDsurveyor`, `SurveyArea`, `surveyDate`, 
                      `DesignAreamax`, `SaveDate`, `SaveTime`, `ClerkID`, `SoilLimitation`, `Freestate`, `StationNumber`, 
                     `XUTM1`, `unpredictedcost`, `YUTM1`, `archive`, `CountyName`, `DesignerCoID`, `YearID`, `MonthID`, `CostPriceListMasterID`, `PriceListMasterID`, 
                     `othercosts1`, `othercosts2`, `othercosts3`, `othercosts4`, `othercosts5`, `coef1`, `coef2`, `coef3`, `coef4`, `coef5`, `DropDesignCostTableMasterID`, 
                     `RainDesignCostTableMasterID`, `TransportCostTableMasterID`, `DesignSystemGroupsID`, `Debi`, `private`, `operatorcoid`, `RDate`, `ADate`, `TMDate`,
                      `ClerkIDApproved`, `DesignerID`, `LastChangeDate`, `LastTotal`, `LastFehrestbaha`, `creditsourceID`, `BankCode`, `temporarydeliverydate`, `proposestate`, 
                      `transportless`, `letterno`, `letterdate`, `sandoghcode`, `belaavaz`, `selfcashhelpdate`, `selfcashhelpval`, `selfcashhelpdescription`, 
                      `selfnotcashhelpdate`, `selfnotcashhelpval`, `DesignerCoIDnazer`
                      , `mobile`, `numfield`, `melicode`, `ApplicantMasterIDmaster`) 
					  
                      select `Code`, `ApplicantName`, `ApplicantFName`, `CityId`, `DesignArea`, `operatorcoIDbandp`,
                     `Datebandp`, `isbandp`,  `othercosts4text`, '$ClerkIDexaminer', `SurveyArea`, `surveyDate`, 
                      `DesignAreamax`, '$SaveDate', '$SaveTime', '$ClerkID', `SoilLimitation`, `Freestate`, `StationNumber`, 
                     `XUTM1`, `unpredictedcost`, `YUTM1`, `archive`, `CountyName`, `DesignerCoID`, `YearID`, `MonthID`, `CostPriceListMasterID`, `PriceListMasterID`, 
                     `othercosts1`, `othercosts2`, `othercosts3`, `othercosts4`, `othercosts5`, `coef1`, `coef2`, `coef3`, `coef4`, `coef5`, `DropDesignCostTableMasterID`, 
                     `RainDesignCostTableMasterID`, `TransportCostTableMasterID`, `DesignSystemGroupsID`, `Debi`, `private`, `operatorcoid`, `RDate`, `ADate`, `TMDate`,
                      `ClerkIDApproved`, `DesignerID`, `LastChangeDate`, `LastTotal`, `LastFehrestbaha`, `creditsourceID`, `BankCode`, `temporarydeliverydate`, `proposestate`, 
                      `transportless`, `letterno`, `letterdate`, `sandoghcode`, `belaavaz`, `selfcashhelpdate`, `selfcashhelpval`, `selfcashhelpdescription`, 
                      `selfnotcashhelpdate`, `selfnotcashhelpval`, `DesignerCoIDnazer`
                      ,  `mobile`, `numfield`, `melicode`,'$applicantmasteridold'
					  from applicantmaster where applicantmaster.applicantmasterid='$applicantmasteridold';";
                    //mysql_query($querytr);
                    //print $query;
                    //exit; 
             

$querytr.= "INSERT INTO applicantcostcodechange(`ApplicantMasterID`, `gadget3operationalID`, `GCode`,TCode, `SaveDate`, 
                    `SaveTime`, `ClerkID`) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `gadget3operationalID`, `GCode`,TCode,  `SaveDate`, 
                    `SaveTime`, `ClerkID`
                      from applicantcostcodechange where ApplicantMasterID='$applicantmasteridold'
                    ;";
                    

                $querytr.= "INSERT INTO appfarmerbring(`ApplicantMasterID`, `rown`, `Title`,price, `SaveDate`, 
                    `SaveTime`, `ClerkID`) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `rown`, `Title`,price,  `SaveDate`, 
                    `SaveTime`, `ClerkID`
                      from appfarmerbring where ApplicantMasterID='$applicantmasteridold'
                    ;";
                    
                    $querytr.= "INSERT INTO appequip(`ApplicantMasterID`, `equipID`, `price`, `SaveDate`, 
                    `SaveTime`, `ClerkID`) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `equipID`, `price`,  `SaveDate`, 
                    `SaveTime`, `ClerkID`
                      from appequip where ApplicantMasterID='$applicantmasteridold'
                    ;";

                    $querytr.= "INSERT INTO manuallistpriceall(`ApplicantMasterID`, `Number`, `Price`, `fehrestsID`, `SaveDate`, 
                    `SaveTime`, `ClerkID`,appfoundationID,Number2,Number3,Number4,Number5,Number6) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `Number`, `Price`, `fehrestsID`, `SaveDate`, 
                    `SaveTime`, `ClerkID`,appfoundationID,Number2,Number3,Number4,Number5,Number6
                      from manuallistpriceall where ApplicantMasterID='$applicantmasteridold'
                    ;";
                    
                    
                    $querytr.= "INSERT INTO manuallistprice(`ApplicantMasterID`, `Number`, `Price`, `Description`, `SaveDate`, 
                    `SaveTime`, `ClerkID`, `CostsGroupsID`, `AddOrSub`, `Code`, `Title`, `Unit`,fehrestsfaslsID,appfoundationID) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `Number`, `Price`, `Description`, `SaveDate`, 
                    `SaveTime`, `ClerkID`, `CostsGroupsID`, `AddOrSub`, `Code`, `Title`, `Unit`,fehrestsfaslsID,appfoundationID
                      from manuallistprice where ApplicantMasterID='$applicantmasteridold'
                    ;";

                    $querytr.= "INSERT INTO invoicemaster(`ApplicantMasterID`, `ProducersID`, `Serial`, `Title`, `Description`, `TransportCost`, `Discont`, 
                    `SaveDate`, `SaveTime`, `ClerkID`, `InvoiceDate`, `Rowcnt`, `pricenotinrep`, `costnotinrep`, `taxless`, `PriceListMasterID`,
                    InvoiceMasterIDmaster,proposable) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster 
                     where ApplicantMasterIDmaster='$applicantmasteridold'), `ProducersID`, `Serial`, `Title`, `Description`, `TransportCost`, `Discont`, 
                    `SaveDate`, `SaveTime`, `ClerkID`, `InvoiceDate`, `Rowcnt`, `pricenotinrep`, `costnotinrep`, `taxless`, `PriceListMasterID`,
                    InvoiceMasterID,proposable
                      from invoicemaster where ApplicantMasterID='$applicantmasteridold'
                    ;";
                    
                    
                    
                    $querytr.= "INSERT INTO invoicedetail(`InvoiceMasterID`, `ToolsMarksID`, `Number`,  `SaveDate`, `SaveTime`, `ClerkID`,deactive) 
                      select invoicemasternew.InvoiceMasterID, invoicedetail.`ToolsMarksID`, invoicedetail.`Number`, invoicedetail.`SaveDate`, 
                      invoicedetail.`SaveTime`, invoicedetail.`ClerkID`,deactive
                      from invoicedetail 
                      inner join invoicemaster invoicemasternew on invoicemasternew.ApplicantMasterID=(SELECT max(applicantmasterid) 
                     FROM applicantmaster 
                     where ApplicantMasterIDmaster='$applicantmasteridold')
                      inner join invoicemaster on 
                      invoicemasternew.InvoiceMasterIDmaster=invoicemaster.InvoiceMasterID and invoicemaster.ApplicantMasterID='$applicantmasteridold' 
                      and invoicedetail.invoicemasterid=invoicemasternew.InvoiceMasterIDmaster
                    ;";
                    
                    $querytr.= "INSERT INTO designsystemgroupsdetail(`ApplicantMasterID`, `DesignSystemGroupsID`, `hektar`, `price`, `yeild`, `SaveDate`, 
                    `SaveTime`, `ClerkID`) 
                      select (SELECT max(applicantmasterid) 
                     FROM applicantmaster 
                     where ApplicantMasterIDmaster='$applicantmasteridold'),`DesignSystemGroupsID`, `hektar`, `Price`, `yeild`, `SaveDate`, 
                    `SaveTime`, `ClerkID`
                      from designsystemgroupsdetail where ApplicantMasterID='$applicantmasteridold'
                    ;";

                    
              
              
                if (!($login_userid>0)) header("Location: ../login.php");
                
                
                    
                $coni=mysqli_connect($_server, $_server_user, $_server_pass,$_server_db);
                // Check connection
                if (mysqli_connect_errno())
                  {
                  echo "Failed to connect to MySQL: " . mysqli_connect_error();
                  }
                  if (!mysqli_multi_query($coni,$querytr))
                   {
                    
                    print "START TRANSACTION;".$querytr.";COMMIT;";
                    exit;          
                    
                   }
                mysqli_close($coni);
                
                $query="SELECT max(applicantmasterid) applicantmasteridmax
                     FROM applicantmaster 
                     where ApplicantMasterIDmaster='$applicantmasteridold'";
                $result = mysql_query($query);
                $row = mysql_fetch_assoc($result);
    
                    $querytr= "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,SaveTime,SaveDate,ClerkID) VALUES(
                    '$row[applicantmasteridmax]',1,40, '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','$ClerkID');";
                
                //print $querytr;
                //exit;
                mysql_query($querytr);  
                $querytr= "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES('" .
                $_POST['ApplicantMasterID'] . "',$maxstateno,'30','$Description', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".
                $login_userid."');";
                mysql_query($querytr); 
                  
                  
                  
                  $querytr= "INSERT INTO appequip(`ApplicantMasterID`, `equipID`, `price`, `SaveDate`, 
                    `SaveTime`, `ClerkID`) 
                      select '$row[applicantmasteridmax]', `equipID`, `price`,  `SaveDate`, 
                    `SaveTime`, `ClerkID`
                      from appequip where ApplicantMasterID='$applicantmasteridold'
                      and '$row[applicantmasteridmax]' not in (select ApplicantMasterID from appequip where ApplicantMasterID='$row[applicantmasteridmax]')
                    ;";  
                    mysql_query($querytr);
}
                
                    
    function encrypt($pure_string, $encryption_key="!@#$%^&*") //کد کردن
    {
        $string=$pure_string;
     $ascii = NULL;
         if ((strlen($string)+5)<10)
            $ascii =$ascii.'00'. (strlen($string)+5);
        else if ((strlen($string)+5)<100)
            $ascii =$ascii.'0'. (strlen($string)+5);    
        else $ascii =$ascii.(strlen($string)+5);
        
    for ($i = 0; $i < strlen($string); $i++)
    {
        if (ord($string[$i])<10)
            $ascii =$ascii.'00'. ord($string[$i]);
        else if (ord($string[$i])<100)
            $ascii =$ascii.'0'. ord($string[$i]);    
        else $ascii =$ascii.ord($string[$i]);
    }
    while (strlen($ascii)<120)
        $ascii =$ascii.rand(100,999);
        
        return $ascii;
    }
    
    
    function decrypt($encrypted_string, $encryption_key="!@#$8^&*") //دیکود کردن
    {
        $decrypted_string="";
        for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
        return $decrypted_string;
    }
    
  function getnamefromclerkid($ID)//دریافت نام از شناسه کاربر
  {
    $query="SELECT clerk.CPI,DVFS  FROM clerk where clerkID='$ID'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    return trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']));
  }            
                
  
	

	
	

 
 function supervisorcoderrquirement($login_CityId)//تابع دریافت تنظیمات پیکربندی سیستم
 {
    $querys = "SELECT KeyStr,ValueInt FROM supervisorcoderrquirement 
    WHERE ostan=substring('$login_CityId',1,2) or ostan=0";
    $results = mysql_query($querys);
    $Permissionvals=array();
    while ($rows = mysql_fetch_assoc($results))
    $Permissionvals[$rows['KeyStr']]=$rows['ValueInt'];
    return $Permissionvals;							 
 }
 
	
	
	
	
function supervisorcoderrquirement_sql($login_ostanId)//پرس و جوی دریافت تنظیمات پیکربندی سیستم
 {
	$querys = "SELECT KeyStr,case KeyStr 
	when 'hmmp5' then round(ValueInt*(SELECT 1+ValueInt/100 ValueInt FROM `supervisorcoderrquirement` where KeyStr='percentapplicantsize' and ostan='$login_ostanId'))
	when 'hmmp4' then round(ValueInt*(SELECT 1+ValueInt/100 ValueInt FROM `supervisorcoderrquirement` where KeyStr='percentapplicantsize4' and ostan='$login_ostanId'))
	when 'hmmp3' then round(ValueInt*(SELECT 1+ValueInt/100 ValueInt FROM `supervisorcoderrquirement` where KeyStr='percentapplicantsize3' and ostan='$login_ostanId'))
	when 'hmmp2' then round(ValueInt*(SELECT 1+ValueInt/100 ValueInt FROM `supervisorcoderrquirement` where KeyStr='percentapplicantsize2' and ostan='$login_ostanId'))
	when 'hmmp1' then round(ValueInt*(SELECT 1+ValueInt/100 ValueInt FROM `supervisorcoderrquirement` where KeyStr='percentapplicantsize1' and ostan='$login_ostanId'))
else ValueInt end ValueInt
	FROM `supervisorcoderrquirement` 
	WHERE ostan='$login_ostanId' ";
	$results = mysql_query($querys);
    $Permissionvals=array();
    while ($rows = mysql_fetch_assoc($results))
    $Permissionvals[$rows['KeyStr']]=$rows['ValueInt'];
    return $Permissionvals;							 
 }

					
function freeproject ($DesignArea,$operatorcoID,$type,$login_ostanId)//تابع آزادسازی پروژه
{

/// آپدیت اعضا
//   type=1  انتخاب برنده پیشنهاد: پرشدن تعداد و هکتار جاری و سالیانه       type=2 حذف پیشنهاد برنده - خالی شدن تعداد و هکتار جاری و سالینه          
//   type=3   آزادسازی ظرفیت : خالی شدن تعداد و هکتار جاری- پرشدن تعداد و هکتار انجام داده        type=4     برگشت آزادسازی ظرفیت - برعکس

	$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);    				
	$smallapplicantsize=$Permissionvals['smallapplicantsize'];

	
/*
	$query = " select 
	        above20cnt,above55cnt,projecthektardone,projectcountdone,thisyearprgarea,simultaneouscnt,currentprgarea
			from operatorco 
			WHERE operatorcoID ='$operatorcoID' ;";
			print $query;  exit;
	print $login_ostanId;exit;
*/	

if ($type==2 || $type==3) $zarib=-1; else $zarib=1;
if ($DesignArea>$smallapplicantsize)
 $above=",above20cnt=ifnull(operatorco.above20cnt,0)+$zarib*1";

if ($DesignArea>55)
   $above.=",above55cnt=ifnull(operatorco.above55cnt,0)+$zarib*1";   

if ($type==3 || $type==4) 
			$above.=",projecthektardone=ifnull(operatorco.projecthektardone,0)-$zarib*$DesignArea,
					projectcountdone=ifnull(operatorco.projectcountdone,0)-$zarib*1";
	else	$above.=",thisyearprgarea=ifnull(operatorco.thisyearprgarea,0)+$zarib*$DesignArea";			

	$query = " update operatorco set  
    SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
		  simultaneouscnt=ifnull(operatorco.simultaneouscnt,0)+$zarib*1
		 ,currentprgarea=ifnull(operatorco.currentprgarea,0)+$zarib*$DesignArea
			$above
			WHERE operatorcoID ='$operatorcoID' ;";
//		print $query;  exit;
       $result = mysql_query($query);
	   
	   return;
}
function retqueryaggregated($cond,$orderby)//تابع ایجاد پرس و جوی گزارش تجمیعی
{
    $designercocontractIDstr="";
    if (strlen(strstr($cond,'designercocontractID'))>0)
        $designercocontractIDstr=",applicantcontracts.designercocontractID";
    
    
    
			return "select distinct applicantfree.Price sumfreep,applicantmaster.Bankcode
            ,round(applicantmaster.LastTotal/1000000,1) LastTotald,round(applicantmasterop.LastTotal/1000000,1) LastTotalop,round(applicantmasteroplist.LastTotal/1000000,1) LastTotaloplist
            ,applicantmaster.LastTotal LastTotaldfee,applicantmasterop.LastTotal LastTotalopfee,applicantmasteroplist.LastTotal LastTotaloplistfee
            ,round(applicantmaster.LastFehrestbaha/1000000,1) LastFehrestbahad,round(applicantmasterop.LastFehrestbaha/1000000,1) LastFehrestbahaop
			,round(applicantmasteroplist.LastFehrestbaha/1000000,1) LastFehrestbahaoplist
            ,round(applicantmaster.TotlainvoiceValues/1000000,1) TotlainvoiceValuesd,round(applicantmasterop.TotlainvoiceValues/1000000,1) TotlainvoiceValuesop
			,round(applicantmasteroplist.TotlainvoiceValues/1000000,1) TotlainvoiceValuesoplist
			,round(applicantmaster.selfcashhelpval/1000000,1) selfcashhelpval,
			round(applicantmaster.selfnotcashhelpval/1000000,1) selfnotcashhelpval,
			progress,case applicantmaster.DesignSystemGroupsID when 1 then 1 else 0 end DesignSystemGroupsID1,
		case applicantmaster.DesignSystemGroupsID when 3 then 1 else 0 end DesignSystemGroupsID3,
		substring(applicantmaster.CityId,1,4) CityId
        ,case operatorapprequest.ApplicantMasterID>0 when 1 then  22 else applicantmaster.applicantstatesID end applicantstatesIDd
        
        ,applicantmaster.DesignArea DesignAread
		,applicantmaster.belaavaz belaavazd,applicantmaster.creditsourceID,applicantmaster.criditType,applicantstates.Title applicantstatesTitle
		,applicantmasterop.applicantstatesID applicantstatesIDop,applicantmasterop.DesignArea DesignAreaop
		,case ifnull(applicantmasterop.belaavaz,0) when 0 then applicantmaster.belaavaz else applicantmasterop.belaavaz end belaavazop
		,applicantmasteroplist.applicantstatesID applicantstatesIDoplist,applicantmasteroplist.DesignArea DesignAreaoplist
		,case ifnull(applicantmasteroplist.belaavaz,0) when 0 then case ifnull(applicantmasterop.belaavaz,0) when 0 then applicantmaster.belaavaz else applicantmasterop.belaavaz end else applicantmasteroplist.belaavaz end  belaavazoplist
		,case ifnull(applicantfree.Price,0)>=ifnull(applicantmasteroplist.LastTotal,0) when 1 then 1 else 0 end permanentfree
        
		,substring(shahr.id,1,4) shahrid,shahr.cityname shahrcityname,ostan.cityname ostancityname,applicantmaster.melicode,
        substring_index(applicantmaster.CountyName,'_',1) CountyName,applicantmaster.CountyName CountyNameall
        ,applicantmaster.ApplicantName,applicantmaster.ApplicantFName,designsystemgroups.DesignSystemGroupsID,designsystemgroups.title DesignSystemGroupstitle
		,watersource.WaterSourceID,watersource.Title watersourceTitle,applicantmaster.XUTM1,applicantmaster.YUTM1,applicantmaster.Debi, hekbarani.hektar hekbarani, hekghatreei.hektar hekghatreei
		, hekkamfeshar.hektar hekkamfeshar,creditsource.Title creditsourceTitle 
		,case creditsource.creditbank when 2 then 'صندوق' when 1 then 'بانک' end creditbank,free.CheckDate,applicanttiming.tahvildate tahvildatezamin,workdeliveryend
		,case ifnull(applicantfree.Price,0)>=ifnull(applicantmasteroplist.LastTotal,0) when 1 then lastCheckDate else '' end permanentfreedate
		,applicantmaster.DesignerCoID DesignerCoIDd,DesignerCod.Title DesignerCoTitle
		,DesignerCoIDnazer.DesignerCoID DesignerCoIDnazerID,DesignerCoIDnazer.Title DesignerCoIDnazerTitle,operatorco.operatorcoid
        ,operatorco.title operatorcotitle,applicantstates.applicantstatesID,applicantmaster.ApplicantMasterID
        ,case ifnull(applicantmasteroplist.LastFehrestbahawithcoef,0) when 0 then case ifnull(applicantmasterop.LastFehrestbahawithcoef,0) 
        when 0 then applicantmaster.LastFehrestbahawithcoef else applicantmasterop.LastFehrestbahawithcoef end else applicantmasteroplist.LastFehrestbahawithcoef
         end  LastFehrestbahawithcoef
		,applicantmaster.sandoghcode,applicantmasterdetail.applicantmasterdetailid
		$designercocontractIDstr
	from applicantmasterdetail
        
        left outer join applicantcontracts on applicantcontracts.applicantmasterdetailid=applicantmasterdetail.applicantmasterdetailid
        
        
        left outer join (select distinct ApplicantMasterID from operatorapprequest) operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
        
        
		inner join applicantmaster on applicantmaster.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID 
		and ifnull(applicantmaster.private,0)=0  
		left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
		left outer join applicantmaster applicantmasteroplist on applicantmasteroplist.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDsurat
		left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where producersid<>-2  group by applicantmasterid) 
		applicantfree on applicantfree.applicantmasterid =applicantmasterop.applicantmasterid

		left outer join designerco DesignerCod on DesignerCod.DesignerCoID= applicantmaster.DesignerCoID
	
		inner join applicantstates on applicantstates.applicantstatesID=
        case applicantmasteroplist.applicantstatesID>0 when 1 then applicantmasteroplist.applicantstatesID else
        case applicantmasterop.applicantstatesID>0 when 1 then applicantmasterop.applicantstatesID else applicantmaster.applicantstatesID end
        end  
        
        left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
        
		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
		and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
		left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid

		left outer join (SELECT ApplicantMasterID,min(WaterSourceID) WaterSourceID FROM `applicantwsource` group by ApplicantMasterID) appWaterSource
		on appWaterSource.ApplicantMasterID=applicantmaster.ApplicantMasterID
		left outer join watersource on watersource.WaterSourceID=appWaterSource.WaterSourceID
		left outer join (select ApplicantMasterID,round(sum(hektar),1) hektar from designsystemgroupsdetail where DesignSystemGroupsID in (1,6) group by ApplicantMasterID) hekbarani
		on hekbarani.ApplicantMasterID=applicantmaster.ApplicantMasterID

		left outer join (select ApplicantMasterID,round(sum(hektar),1) hektar from designsystemgroupsdetail where DesignSystemGroupsID in (2,4,5,7) group by ApplicantMasterID) hekghatreei
		on hekghatreei.ApplicantMasterID=applicantmaster.ApplicantMasterID
		left outer join (select ApplicantMasterID,round(sum(hektar),1) hektar from designsystemgroupsdetail where DesignSystemGroupsID=3 group by ApplicantMasterID) hekkamfeshar
		on hekkamfeshar.ApplicantMasterID=applicantmaster.ApplicantMasterID
		left outer join creditsource on creditsource.creditsourceID=applicantmaster.creditsourceID

		left outer join (SELECT  ApplicantMasterID,case min(CheckDate) when '' then min(SaveDate) else min(CheckDate) end CheckDate
		,case max(CheckDate) when '' then max(SaveDate) else max(CheckDate) end lastCheckDate FROM `applicantfreedetail`
		group by ApplicantMasterID) free on free.ApplicantMasterID=applicantmasterop.ApplicantMasterID

		left outer join (SELECT  ApplicantMasterID,max(tahvildate) tahvildate,max(workdeliveryend) workdeliveryend FROM `applicanttiming`
		where RoleID=10
		group by ApplicantMasterID) applicanttiming on applicanttiming.ApplicantMasterID=applicantmasterop.ApplicantMasterID

		left outer join designerco DesignerCoIDnazer on DesignerCoIDnazer.DesignerCoID= applicantmasterdetail.nazerID
        
		left outer join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid
		where  applicantmaster.applicantstatesID<>23 and ifnull(applicantmasterdetail.prjtypeid,0)=0 $cond $orderby";
		
		
}
		
function returnpipeproducetiming ($ApplicantMasterID)//تابع مشخص کردن اینکه یک طرح در حال پیشنهاد قیمت می باشد یا خیر
    {
        $sqln = "SELECT distinct invoicetimingID,ApproveP,producedateP,testdateP,tonajP,ApproveA,producedateA,testdateA,tonajA,score1,score2,score3,invoicetiming.Description
						,invoicemaster.InvoiceMasterID,invoicemaster.InvoiceDate InvoiceDate,invoicemaster.Title,BOLNO
						,producers.Title producersTitle,applicantfreedetail.CheckDate,applicantfreedetail.SaveDate CheckDatetemp, operatorapprequest.SaveDate,operatorapprequest.Windate, applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,shahr.cityname shahrcityname,designerco.title DesignerCotitle
        FROM applicantmaster 
        
        
            inner join applicantmasterdetail on 
    case ifnull(applicantmasterdetail.prjtypeid,0) when 1 then 
    case ifnull(applicantmasterdetail.level,0) when 1 then applicantmasterdetail.ApplicantMasterIDmaster else applicantmasterdetail.ApplicantMasterID end else
    applicantmasterdetail.ApplicantMasterIDmaster end=applicantmaster.ApplicantMasterID

        
        
        left outer join operatorapprequest on operatorapprequest.operatorcoID=applicantmaster.operatorcoid and operatorapprequest.state=1 
        and operatorapprequest.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
        
    left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,max(pricenotinrep) pricenotinrep,max(Title) Title,max(InvoiceDate) InvoiceDate,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  on invoicemaster.ApplicantMasterID=applicantmaster.ApplicantMasterID
        inner join invoicedetail on invoicedetail.InvoiceMasterID=invoicemaster.InvoiceMasterID
        left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
        inner join producers on producers.producersid=invoicemaster.producersid and PipeProducer=1  and ifnull(pricenotinrep,0)=0
                                
        left outer join applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterID 
        and applicantfreedetail.ProducersID=invoicemaster.producersid
        
        left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID
        
       left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
        
        left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmaster.ClerkIDsurveyor,0) when 0 
        then producers.ClerkIDexaminer else applicantmaster.ClerkIDsurveyor end
        where applicantmaster.ApplicantMasterID='$ApplicantMasterID'
        order by invoicemaster.InvoiceMasterID desc 
        ";
               //print $sqln;
               //exit;
        $resultn = mysql_query($sqln);
        $row = mysql_fetch_assoc($resultn);
        
        $CheckDate="";
        if (strlen($row['CheckDate'])>0 && strlen($row['CheckDate'])<10) $CheckDate= "13".$row['CheckDate'];
        if (strlen($row['CheckDate'])>0)
            $CheckDate= $row['CheckDate'];
        else 
            $CheckDate= gregorian_to_jalali($row['CheckDatetemp']) ;
        $CheckDate=compelete_date($CheckDate);
                             
        $y=floor((strtotime($row['ApproveP'])-strtotime(jalali_to_gregorian($CheckDate)))/(60*60*24));
        $x=floor((strtotime($row['ApproveA'])-strtotime(jalali_to_gregorian($CheckDate)))/(60*60*24));
       // print $CheckDate;
        

	   
        if ($x>0)
        {
            if ((($row['tonajA']+5)/$x)>1) $s1=100;
            else
                $s1=round(($row['tonajA']+5)/$x*100,1);
            if (($y/$x)>1) $s2=100;
            else $s2=round($y/$x*100,1);
            $systematicscore=round(($s1+$s2)/2,1);
        }
 
 //        $totalscore=round(($systematicscore+$row['score1']+$row['score2']+$row['score3'])/4,1);
         $totalscore=round(($row['score1']+$row['score2']+$row['score3'])/3,1);
        $D1=0;
        $D2=0;
        $D3=0;
        $D4=0;
        $D5=0;
        $D6=0;
        $D7=0;
        
        if ($row['Windate']>$row['SaveDate'] && $row['Windate']<>"")
            $D1=floor((strtotime($row['Windate'])-strtotime($row['SaveDate']))/(60*60*24));
        if (jalali_to_gregorian($CheckDate)>$row['Windate'] && jalali_to_gregorian($CheckDate)<>"")
            $D2=floor((strtotime(jalali_to_gregorian($CheckDate))-strtotime($row['Windate']))/(60*60*24));
        $D3=$row['tonajA']+5;
        if ($row['testdateP']>jalali_to_gregorian($CheckDate) && $row['testdateP']<>"")
            $D4=floor((strtotime($row['testdateP'])-strtotime(jalali_to_gregorian($CheckDate)))/(60*60*24));
            
        if ($row['testdateA']>jalali_to_gregorian($CheckDate) && $row['testdateA']<>"")
            $D5=floor((strtotime($row['testdateA'])-strtotime(jalali_to_gregorian($CheckDate)))/(60*60*24));
            
        if ($row['ApproveP']>$row['producedateP'] && $row['ApproveP']<>"")
            $D6=floor((strtotime($row['ApproveP'])-strtotime($row['producedateP']))/(60*60*24));
        if ($row['ApproveA']>$row['producedateA'] && $row['ApproveA']<>"")
        $D7=floor((strtotime($row['ApproveA'])-strtotime($row['producedateA']))/(60*60*24));
        
        return
        $row['ApplicantFName']."_".
        $row['ApplicantName']."_".
        $row['DesignArea']."_".
        $row['shahrcityname']."_".
        $row['operatorcoTitle']."_".
        $row['DesignerCotitle']."_".
        $row['SaveDate']."_".
        $row['Windate']."_".
        $row['InvoiceDate']."_".
        $row['Title']."_".
        $row['producersTitle']."_".
        $row['producedateP']."_".
        $row['producedateA']."_".
        $row['testdateP']."_".
        $row['testdateA']."_".
        $row['ApproveP']."_".
        $row['ApproveA']."_".
        $row['BOLNO']."_".
        $row['tonajP']."_".
        $row['tonajA']."_".
        $row['score1']."_".
        $row['score2']."_".
        $row['score3']."_".
        $row['Description']."_".
        $row['invoicetimingID']."_".
        $CheckDate."_".
        $totalscore."_".
        $D1."_".
        $D2."_".
        $D3."_".
        $D4."_".
        $D5."_".
        $D6."_".
        $D7;
    }





function lawsubmit($HeaderTitle,$lawtype,$Description,$MenuID,$login_userid)//تابع ثبت یک ابلاغیه
	{
	
	  $sql = "SELECT count(*) count from law ";
	  $result = mysql_query($sql);
	  $row = mysql_fetch_assoc($result);$lawno=$row['count']+7;mysql_data_seek( $result, 0 );

 	
        $sql="INSERT INTO law(lawno,lawtype,HeaderTitle, Description,MenuID,SaveTime,SaveDate,ClerkID)
            values ('$lawno','$lawtype','$HeaderTitle','$Description','$MenuID','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');";
        //    print $sql;  exit;
        mysql_query($sql); 

		$query = "select lawID from law where lawID = last_insert_id()";
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $lawID = $row['lawID'];
        
 
	   return $lawID;
 
	}	
	
function lawclerk($login_userid,$clerkID,$appcount,$lawID)//تابع ثبت ابلاغیه کاربر
	{
	            mysql_query("
                INSERT INTO lawrole(lawID,ClerkIDR,SaveTime,SaveDate,ClerkID,appcount) 
                VALUES('$lawID','".$clerkID."','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$appcount');"); 
    //   print $sql;
	   return;
 
	}	
	
	
function sql_apptiming($ApplicantMasterID)//تابع ایجاد پرس و جوی زمانبندی
{
  $sql="SELECT 
			applicantmaster.*,applicantmaster.Bankcode Bankcode,applicantmasterd.sandoghcode sandoghcode,
			
			shahr.CityName CityName,ostan.CityName Ostan,
            designsystemgroups.Title designsystemgroupsTitle,
			GROUP_CONCAT(DISTINCT designsystemgroupsdetail.yeild SEPARATOR ' ') designsystemgroupsdetailyeild,
			
		    operatorco.Title operatorcoTitle,operatorco.corank opcorank,
			applicantmasterd.applicantmasterid,
			
			round(applicantmasterd.LastTotal/1000000,1) LastTotald,
			
			case ifnull (applicantmasteri.LastTotal,0) when 0 then round(applicantmasterd.LastTotal/1000000,1) else round(applicantmasteri.LastTotal/1000000,1) end LastTotali,
		
	case ifnull (applicantmasteri.LastTotal,0) when 0 then round(applicantmasterd.TotlainvoiceValues/1000000,1) else round(applicantmasteri.TotlainvoiceValues/1000000,1) end TotlainvoiceValuesi,
				
			case ifnull (applicantmasteri.selfnotcashhelpval,0) when 0 then round(applicantmasterd.selfnotcashhelpval/1000000,1) else round(applicantmasteri.selfnotcashhelpval/1000000,1) end selfnotcashhelpvali,
			case ifnull (applicantmasteri.selfcashhelpval,0) when 0 then round(applicantmasterd.selfcashhelpval/1000000,1) else round(applicantmasteri.selfcashhelpval/1000000,1) end selfcashhelpvali,
			case ifnull (applicantmasteri.belaavaz,0) when 0 then round(applicantmasterd.belaavaz,1) else round(applicantmasteri.belaavaz,1) end belaavazdesign,
			
			designercos.Title nazercoTitle,designerco.Title designercoTitle,designerco.corank descorank,creditsource.title creditsourcetitle,
            applicantmasteri.ApplicantMasterID ApplicantMasterIDchange,
            max(appchangestate.SaveDate) SaveDatechange,appchangestate.applicantstatesID applicantstatesID,applicantmaster.operatorcoid  operatorcoid,concat(designercos.BossName,' ',designercos.bosslname) modir,designercos.Phone Phone,designercos.Phone2 Phone2
			
			FROM applicantmaster 
			
            inner join applicantmasterdetail on (applicantmasterdetail.applicantmasteridsurat=applicantmaster.applicantmasterid or 
            applicantmasterdetail.applicantmasteridmaster=applicantmaster.applicantmasterid)
            inner join applicantmaster applicantmasterd on applicantmasterd.applicantmasterid=applicantmasterdetail.applicantmasterid
            inner join applicantmaster applicantmasteri on applicantmasteri.applicantmasterid=applicantmasterdetail.applicantmasteridmaster
            
            left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
			
			
    		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
							
            inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
            
            
			
			left outer join designerco on designerco.DesignerCoID=applicantmasterd.DesignerCoID

			left outer join designerco designercos on designercos.DesignerCoid=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end

			
			left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
			
		
			left outer join designsystemgroupsdetail on designsystemgroupsdetail.ApplicantMasterID='$ApplicantMasterID'

			left outer join creditsource on creditsource.creditsourceid=applicantmasterd.creditsourceid
			inner join appchangestate on appchangestate.ApplicantMasterID=applicantmasteri.ApplicantMasterID 
			

			WHERE   applicantmaster.ApplicantMasterID = (SELECT 
case ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0  when 0 then applicantmaster.ApplicantMasterIDmaster else applicantmaster.ApplicantMasterID end ApplicantMasterIDmaster
			FROM applicantmaster 
			WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID') 
			";
 return $sql;
}
function returnerrnumemtiaz($ApplicantMasterID)//تابع دریافت امتیاز ارزشیابی یک طرح
{
    $query11="select applicanttiming.ApplicantMasterID,applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz emtiaz_moshaver
    ,applicanttiming.m_emtiaz emtiaz_nazerali,applicanttiming2.RoleID,applicanttiming2.emtiaz emtiaz_anjoman,applicanttiming2.m_emtiaz emtiaz_nazermoghim 
              from applicanttiming 
				left outer join (select applicanttiming.ApplicantMasterID ,applicanttiming.errnum ,applicanttiming.RoleID ,
                applicanttiming.emtiaz ,applicanttiming.m_emtiaz from applicanttiming 
                where applicanttiming.ApplicantMasterID='$ApplicantMasterID' and applicanttiming.RoleID='2') applicanttiming2 on  
                applicanttiming2.ApplicantMasterID=applicanttiming.ApplicantMasterID
						where applicanttiming.ApplicantMasterID='$ApplicantMasterID' and applicanttiming.RoleID='10'
						";
    $result11 = mysql_query($query11);
    $row11 = mysql_fetch_assoc($result11);
    $errnum=$row11['errnum'];
    $emtiaz=round(($row11['emtiaz_moshaver']+$row11['emtiaz_nazerali']+$row11['emtiaz_anjoman']+$row11['emtiaz_nazermoghim'])/4);
    
    $cnt=0;
    if ($row11['emtiaz_moshaver']>0)
        $cnt++;
    if ($row11['emtiaz_nazerali']>0)
        $cnt++;
    if ($row11['emtiaz_anjoman']>0)
        $cnt++;
    if ($row11['emtiaz_nazermoghim']>0)
        $cnt++;
    $avgemtiaz=round(($row11['emtiaz_moshaver']+$row11['emtiaz_nazerali']+$row11['emtiaz_anjoman']+$row11['emtiaz_nazermoghim'])/$cnt);
    
    return $errnum."_".$emtiaz."_".$avgemtiaz;                    
    
}
	
function searchgadget_sql($ftextboxsearch,$mtextboxsearch,$ltextboxsearch,$nottextboxsearch,$marksid,$producTitle,$product,$Datefroml
,$Datetol,$Dateto,$login_ProducersID,$dope)//تابع جستجوی یک ابزار 
	{	
	
//print $ftextboxsearch.'@'.$mtextboxsearch.'@'.$ltextboxsearch.'@'.$nottextboxsearch.'@'.$marksid.'@'.$producTitle.'@'.$product.'@'.$Datefroml.'@'.$Datetol.'@'.$Dateto.'@'.$login_ProducersID;
	
    $field="replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )";
    $field="replace(".$field.",'ی','ي')";
    $field="replace(".$field.",'ك','ک')";
    $str="";
    
 
    if ($login_ProducersID>0)
        $str.=" and gadget3.gadget3id in ( select gadget3id from toolsmarks where ProducersID='$login_ProducersID')";
    
    if (strlen($Datefrom)>0)
        $str.=" and (invoicemaster.InvoiceDate>='$Datefroml')";
    if (strlen($Dateto)>0)
        $str.=" and (invoicemaster.InvoiceDate<='$Datetol')";
        
    if (strlen($ftextboxsearch)>0)
        $str.=" and ($field like '$ftextboxsearch%')";
    
    if (strlen($mtextboxsearch)>0)
        $str.=" and ($field  like '%$mtextboxsearch%')";
    
    if (strlen($ltextboxsearch)>0)
        $str.=" and ($field  like '%$ltextboxsearch%')";
    
    if (strlen($nottextboxsearch)>0)
        $str.=" and ($field  not like '%$nottextboxsearch%')";

    if (strlen(trim($product))>0)
        $str.=" and producers.PipeProducer='$product'";
        
   if (strlen(trim($marksid))>0)
        $str.=" and marks.marksid='$marksid'";   
    	
    if ($dope==1)
        $joinmaster=" inner join applicantmaster on applicantmaster.ApplicantMasterID = invoicemaster.ApplicantMasterID and
        applicantmaster.DesignerCoID>0 and applicantmaster.applicantstatesID not in (22,37,23)";  
    else if ($dope==2)
        $joinmaster=" inner join applicantmaster on applicantmaster.ApplicantMasterID = invoicemaster.ApplicantMasterID and
        applicantmaster.DesignerCoID>0 and applicantmaster.applicantstatesID in (22,37)";  
    else 
    
        $joinmaster=" ";          


		//print $joinmaster;exit;
        
        $sql = "SELECT $field fulltitle,marks.marksid,marks.title markstitle,round(sum(invoicedetail.Number)) cnt 
                ,units.title unitstitle,toolsmarks.gadget3id,toolsmarks.ProducersID,gadget3.UnitsCoef2 
				,sizeunits2.Title unitstitle2,invoicedetail.InvoiceMasterID
 
 ,case producers.PipeProducer when 1 then '1' when 2 then '2' when 3 then '3' when 4 then '4' 
	                             when 5 then '5' when 6 then '6' 
								 when 101 then '1' when 102 then '2' when 103 then '3' when 104 then '4' 
	                             when 105 then '5' when 106 then '6' when 107 then '' end product 
 
 ,case producers.PipeProducer when 1 then 'لوله پلي اتيلن' when 2 then 'نوار تيپ' when 3 then 'فيلتراسيون' when 4 then 'پمپ و الكتروموتور' 
	                             when 5 then 'دستگاه باراني' when 6 then 'ساير اتصالات' 
								 when 101 then 'لوله پلي اتيلن' when 102 then 'نوار تيپ' when 103 then 'فيلتراسيون' when 104 then 'پمپ و الكتروموتور' 
	                             when 105 then 'دستگاه باراني' when 106 then 'ساير اتصالات' when 107 then '' end producTitle
    
	 ,case producers.PipeProducer when 1 then 'شرکت' when 2 then 'شرکت' when 3 then 'شرکت' when 4 then 'شرکت' when 5 then 'شرکت' when 6 then 'شرکت'
	                              
								  when 101 then 'فروشگاه' when 102 then 'فروشگاه' when 103 then 'فروشگاه' when 104 then 'فروشگاه' 
								  when 105 then 'فروشگاه' when 106 then 'فروشگاه' when 107 then 'فروشگاه' end producType 
    
				
				
				FROM `invoicedetail`
                
				inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.InvoiceMasterID and invoicemaster.pricenotinrep<>1
			
    			$joinmaster
                
				inner join toolsmarks on toolsmarks.ToolsMarksid=invoicedetail.ToolsMarksid
                inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id
                inner join marks on marks.marksid=toolsmarks.marksid
                inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
					
                left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                left outer join operator on operator.operatorID=gadget3.operatorID
                left outer join spec2 on spec2.spec2id=gadget3.spec2id
                left outer join spec3 on spec3.spec3id=gadget3.spec3id
                left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                left outer join units  on units.UnitsID=gadget3.UnitsID
			    left outer join sizeunits sizeunits2 on sizeunits2.SizeUnitsID=gadget3.UnitsID2
			    
                
                left outer join toolspref on toolspref.PriceListMasterID=invoicemaster.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            
             
            
            inner join toolsmarks toolsmarks2 on toolsmarks2.ToolsMarksid=
            (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)
            
                
                left outer join producers on producers.ProducersID=toolsmarks2.ProducersID
                
                where 1=1 $str
				
                group by $field,marks.title,units.title
                order by marks.marksid,cnt desc ";
return $sql;
	}				
		
		
function chartgadget_sqle($sql,$showchart,$date)//تابع ایجاد نمودار ابزار	 
	{			
	
      $result = mysql_query($sql);
      
	 $ID5[' ']=' ';
	 $ID6[' ']=' ';
     $dasrow=0;
	 $tempmarksid='';
     $number=0;    
    	while($row = mysql_fetch_assoc($result))
		{
			if ($row['marksid']<>128){

			$ton=1000;$UnitsCoef2=$row['UnitsCoef2'];
			//$titr=' *تن*'.$Datefrom.'تا'.$Dateto;$titrnum=''.$Datefrom.'تا'.$Dateto;
			if ($row['product']!=1) {$UnitsCoef2=1;$ton=1;$titr='';}
			$dasrow=1;
			$ID5[trim($row['markstitle'])]=trim($row['marksid']);
			$ID6[trim($row['producTitle'])]=trim($row['product']);
			if 	($tempmarksid!=trim($row['markstitle'])) {$tempmarksid=trim($row['markstitle']);$number=0;}
			$sumu[trim($row['markstitle'])]=(round($UnitsCoef2*$row['cnt']))/$ton+$sumu[trim($row['markstitle'])];
			$number++;
			$sumnumber[trim($row['markstitle'])]=$number;
			}
		}

		$arrayNumy='';
		$i=1;
		foreach ( $sumnumber as $key => $valuenum ) {
		if ($valuenum) {

					$arrayNumy[$i]['name']=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا', $key)));
					$arrayNumy[$i]['y']=$valuenum;
					//echo $key . ' => ' . $valuenum . '<br/>';
				$i++;	
			}
		}
			
		$Path='temp/producenum.html';
		$XMLPath='temp/producenum.xml';
  		$Chart=new Chart();
  		$Chart->arrayNamey=$arrayNumy;
  		$Chart->type=1;
  		$Chart->Path=$Path;
  		$Chart->XMLPath=$XMLPath;
  		$Chart->ChartTitle='تعداد پیشفاکتور'.$date  ;
		$Chart->CreateHtmlFile();
 
		$arrayNamey='';
		$i=1;
		foreach ( $sumu as $key => $value ) {
		     	if ($value) {
						$arrayNamey[$i]['name']=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا', $key)));
						$arrayNamey[$i]['y']=$value;
						//echo $key . ' => ' . $value . '<br/>';
					$i++;	
				}
			}

				//exit;
				$Path='temp/producepercent.html';
				$XMLPath='temp/producepercent.xml';
				$Chart=new Chart();
				$Chart->arrayNamey=$arrayNamey;
				$Chart->type=1;
				$Chart->Path=$Path;
				$Chart->XMLPath=$XMLPath;
				$Chart->ChartTitle='حجم کالا*1000تن '.$date  ;
				$Chart->CreateHtmlFile();
				
			
if ($sumnumber && $sumu) $num=12;else if ($sumu) $num=1;else if ($sumnumber) $num=2;		

		if ($showchart==3)
				{echo ("<SCRIPT LANGUAGE='JavaScript'> 	window.open('../temp/producepercent.html','_self');	</SCRIPT>");}		
		if ($showchart==2)
				{echo ("<SCRIPT LANGUAGE='JavaScript'> 	window.open('../temp/producenum.html','_self');	</SCRIPT>");}		
		
		return ($num);		
	}


	
	
function chartpipe_sqle($datefrom,$dateto)//تابع ایجاد نمودار لوله ها	 
{

/*
					inner join applicantmasterdetail on 
						applicantmasterdetail.ApplicantMasterIDmaster=invoicemaster.ApplicantMasterID
						or
						(applicantmasterdetail.ApplicantMasterID=invoicemaster.ApplicantMasterID and ifnull(level,0)>0)
	
*/	
	
if (!$dateto) $dateto=date('Y-m-d');

if ($dateto<'2016-04-03') 
{
		$sql="
		SELECT producers.ProducersID,producers.Title,COUNT(*) cnt,ROUND(sum(gadget3.UnitsCoef2*invoicedetail.Number)/1000,1) tonaj,emtiaz
		,case producers.rank when 1 then 'A' when 2 then 'A' when 3 then 'B' when 4 then 'B' when 5 then 'C' else 'C' end rank 
		,ROUND(sum(invoicemaster.tot)/10000000) tot
	
		 from invoicemaster 
								inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
								AND ifnull(invoicemaster.proposable,0)=0 and invoicemaster.InvoiceDate<'1395/01/23'
								 inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid
								inner join gadget3 on toolsmarks.gadget3id=gadget3.gadget3id and gadget3.gadget2id in (202,376,494,495)
								inner join producers on producers.producersid=invoicemaster.producersid and PipeProducer=1  
								and ifnull(pricenotinrep,0)=0 and producers.producersid<>148 
								 
								
								
		group by producers.title,proposable
		order by producers.emtiaz desc
		";
	$ChartTitlenum=' تعداد پیشفاکتور قبل از پیشنهاد قیمت';
	$ChartTitleton='حجم کالا*1000تن قبل از پیشنهاد قیمت';
	$ChartTitletot='مبلغ پیش فاکتورها قبل ازپیشنهاد قیمت -میلیون ریال ';

}
else
{
		$sql="	SELECT producers.ProducersID,Title,cnt,tonaj,emtiaz
						, case producers.rank when 1 then 'A' when 2 then 'A' when 3 then 'B' when 4 then 'B' when 5 then 'C' else 'C' end rank 
						,tot
		FROM `producers`
		left outer join
		(SELECT ProducersID,count(*) cnt,round(sum(PE100tonaj+PE80tonaj+PE40tonaj+PE32tonaj)/1000,1) tonaj,round(sum(price)/10) tot FROM `producerapprequest`
		where  state>0 and (Windate BETWEEN '$datefrom' AND '$dateto')
		group by ProducersID ) Prd on Prd.ProducersID=producers.ProducersID
		where cnt>0 
		order by producers.emtiaz desc
		";
		if($dateto) $dateto=gregorian_to_jalali($dateto);
		if($datefrom) $datefrom=gregorian_to_jalali($datefrom);

		$ChartTitlenum=' تعداد پیشفاکتور از تاریخ'.$datefrom.'تا تاریخ'.$dateto;
		$ChartTitleton='حجم کالا*1000تن از تاریخ'.$datefrom.'تا تاریخ'.$dateto;
		$ChartTitletot='مبلغ پیش فاکتور(میلیون ریال) از تاریخ'.$datefrom.'تا تاریخ'.$dateto;
		
		if (!$datefrom){
				 $ChartTitlenum=' تعداد پیشفاکتور تا تاریخ'.$dateto;
				 $ChartTitleton='حجم کالا*1000تن تا تاریخ'.$dateto;
				 $ChartTitletot='مبلغ پیش فاکتور(میلیون ریال)تا تاریخ'.$dateto;
		}

}
 
    $result = mysql_query($sql);
	//print $sql;
   	while($row = mysql_fetch_assoc($result))
		{
			$sumnumber[trim($row['Title']." ".$row['rank'])]=$row['cnt'];
			$sumu[trim($row['Title']." ".$row['rank'])]=$row['tonaj'];
			$sumutot[trim($row['Title']." ".$row['rank'])]=$row['tot'];
			//print_r($sumnumber);
		}
//exit;



/////////////////////////////////////////////////////////////////////////////////////
		$arrayNumy='';
		$i=1;
		foreach ( $sumnumber as $key => $valuenum ) {
		if ($valuenum) {

					$arrayNumy[$i]['name']=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا', $key)));
					$arrayNumy[$i]['y']=$valuenum;
					//echo $key . ' => ' . $valuenum . '<br/>';
				$i++;	
			}
		}
			
		$Path='temp/producepipenum.html';
		$XMLPath='temp/producepipenum.xml';
  		$Chart=new Chart();
  		$Chart->arrayNamey=$arrayNumy;
  		$Chart->type=1;
  		$Chart->Path=$Path;
  		$Chart->XMLPath=$XMLPath;
		$Chart->ChartTitle=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا',$ChartTitlenum)))  ;
		$Chart->CreateHtmlFile();
 		
	 ////////////////////////////////////////////////////////////////////////////////////////////////
		$arrayNamey='';
		$i=1;
		foreach ( $sumu as $key => $value ) {
		     	if ($value) {
						$arrayNamey[$i]['name']=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا', $key)));
						$arrayNamey[$i]['y']=$value;
						//echo $key . ' => ' . $value . '<br/>';
					$i++;	
				}
			}
				//exit;
				$Path='temp/producepipeton.html';
				$XMLPath='temp/producepipeton.xml';
				$Chart=new Chart();
				$Chart->arrayNamey=$arrayNamey;
				$Chart->type=1;
				$Chart->Path=$Path;
				$Chart->XMLPath=$XMLPath;
				$Chart->ChartTitle=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا',$ChartTitleton)))  ;
				$Chart->CreateHtmlFile();

	 ////////////////////////////////////////////////////////////////////////////////////////////////
		$arrayNamey='';
		$i=1;
		foreach ( $sumutot as $key => $value ) {
		     	if ($value) {
						$arrayNamey[$i]['name']=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا', $key)));
						$arrayNamey[$i]['y']=$value;
						//echo $key . ' => ' . $value . '<br/>';
					$i++;	
				}
			}
				//exit;
				$Path='temp/producepipetot.html';
				$XMLPath='temp/producepipetot.xml';
				$Chart=new Chart();
				$Chart->arrayNamey=$arrayNamey;
				$Chart->type=1;
				$Chart->Path=$Path;
				$Chart->XMLPath=$XMLPath;
				$Chart->ChartTitle=str_replace('ی', 'ي', str_replace('یک', 'يک', str_replace('یا', 'يا',$ChartTitletot)))  ;
				$Chart->CreateHtmlFile();

		return ;


		
		
	}
    
function echorowbank($resquery,$rown,$b)//تابع رشته یک ردیف در گزارش سامانه های نوین
{
    $progressM=$resquery["sumfreep"]/($resquery["belaavazd"]+$resquery["selfcashhelpval"]+$resquery["selfnotcashhelpval"]);
    $progressM=round($progressM/10000,1);
    $cr='';if ($resquery["criditType"]==1) $cr="+";	
    if ($resquery["hekbarani"]>0) $hek='ب:'.$resquery["hekbarani"];
    if ($resquery["hekghatreei"]>0) 
    {
        if ($resquery["hekbarani"]>0 ) $hek.= '<br>';
        $hek.=   'ق:'.$resquery["hekghatreei"];
    }
    if ($resquery["hekkamfeshar"]>0) 
    {
        if ($resquery["hekbarani"]>0 || $resquery["hekghatreei"]>0 ) $hek.= '<br>';
        $hek.=   'ک:'.$resquery["hekkamfeshar"];
    }
    if ($resquery["XUTM1"]>0) $UTM="X=".$resquery["XUTM1"]."<br>Y=".$resquery["YUTM1"];
    if (substr($resquery["CheckDate"],0,2)=='20') $CheckDate= gregorian_to_jalali($resquery["CheckDate"]); else if (strlen($resquery["CheckDate"])>0) 
    $CheckDate= compelete_date($resquery["CheckDate"]);
    if (substr($resquery["tahvildatezamin"],0,2)=='20') $tahvildatezamin= gregorian_to_jalali($resquery["tahvildatezamin"]); else if (strlen($resquery["tahvildatezamin"])>0) 
    $tahvildatezamin= compelete_date($resquery["tahvildatezamin"]);
    if (substr($resquery["workdeliveryend"],0,2)=='20') $workdeliveryend= gregorian_to_jalali($resquery["workdeliveryend"]); else if (strlen($resquery["workdeliveryend"])>0) 
    $workdeliveryend= compelete_date($resquery["workdeliveryend"]);
    if (substr($resquery["permanentfreedate"],0,2)=='20') $permanentfreedate= gregorian_to_jalali($resquery["permanentfreedate"]); 
    else if (strlen($resquery["permanentfreedate"])>0) $permanentfreedate= compelete_date($resquery["permanentfreedate"]);
    
    $linearray = explode('_',$resquery['CountyNameall']);
    $CountyName=$linearray[0];
    $registerplace=$linearray[1];
    $fathername=$linearray[2];
    $birthdate=$linearray[3];
    $shenasnamecode=$linearray[4];
    if ($resquery['operatorcotitle']!='')
    {
        $optik="&#10004;";
        $dtik="";
        $giver="پیمانکار";
    }
    else
    {
        $optik="";
        $dtik="&#10004;";
        $giver="";
    }
    
	$retstr="<tr >
     <td class=\"f10_font$b\"  style=\"color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".$rown.''.$cr."</td>
                            <td class=\"f10_font$b\"  style=\"color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[ApplicantFName]</td>
                            <td class=\"f10_font$b\"  style=\"color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[ApplicantName]</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[melicode]</td>
                            <td class=\"f10_font$b\"  style=\"color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$shenasnamecode</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$registerplace</td>
                            <td class=\"f10_font$b\"  style=\"color:#;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$fathername</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$birthdate</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">".substr($resquery[ostancityname],10)."</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[shahrcityname]</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[sandoghcode]</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$dtik</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$optik</td>
						
						<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[DesignAread]</td>
						
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[DesignSystemGroupstitle]</td>
                            <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[LastTotald]</td>
                            <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin';\">".($resquery['selfcashhelpval']+$resquery['selfnotcashhelpval'])."</td>
                            <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin';\">$resquery[belaavazd]</td>
                            <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin';\">$resquery[selfcashhelpval]</td>
                            <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin';\">$resquery[selfnotcashhelpval]</td>
                            
                            <td class=\"f9_font$b\"  style=\"color:#;text-align:	center;font-size:9.0pt;font-family:'B Nazanin';\"></td>
							<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\"></td>
							<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[operatorcotitle]</td>
							<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$giver</td>
                        	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">".(round($resquery['LastFehrestbahawithcoef']/1000000)/10)."</td>
                        	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\"></td>
                            <td><a target='_blank' href='../insert/applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery["ApplicantMasterID"].rand(10000,99999)."'>
										<img style = 'width: 15px' src='../img/file-edit-icon.png' title=' ویرایش طرح '></a></td>
                        </tr>";
    return $retstr;		     
                           
                   
}


function sqlcont($login_CityId,$wh)//تابع ایجاد پرس و جوی گزارش قراردادها
{
if($wh)  $sum=''; else $sum='sum';


$sql="
select designercocontract.designercocontractID,designercocontract.contracttypeID
						,round(tax_tbcity7digit.fzkargah,2) fzkargahf,applicantmasterdetail.ApplicantMasterdetailID
						,100*$sum(
									case designercocontract.contracttypeID
									when 1 then 
										case applicantmasteroplist.applicantstatesID
										when 	45 then 1*applicantmaster.DesignArea
										when	35 then 0.9*applicantmaster.DesignArea
										when	38 then 0.9*applicantmaster.DesignArea
										when	43 then 0.8*applicantmaster.DesignArea
										when	44 then 0.6*applicantmaster.DesignArea
										when	41 then 0.6*applicantmaster.DesignArea
										when	42 then 0.3*applicantmaster.DesignArea
										when	40 then 0.3*applicantmaster.DesignArea
											else 0
										end 
									when 4 then 
										case applicantmaster.applicantstatesID
										when 	23 then 0
										when	2 then 0
										when	3 then 0.5*applicantmaster.DesignArea
										when	4 then 0.5*applicantmaster.DesignArea
										when	5 then 0.75*applicantmaster.DesignArea
										when	11 then 0.75*applicantmaster.DesignArea
											else 1*applicantmaster.DesignArea
										end
									when 5 then 
										case applicantmaster.applicantstatesID
										when 	23 then 0
										when	2 then 0
										when	3 then 0.5*applicantmaster.DesignArea
										when	4 then 0.5*applicantmaster.DesignArea
										when	5 then 0.75*applicantmaster.DesignArea
										when	11 then 0.75*applicantmaster.DesignArea
											else 1*applicantmaster.DesignArea
										end 
									end
								)/designercocontract.area cocontractprogress

						   ,$sum(
									case designercocontract.contracttypeID
									when 1 then applicantmaster.DesignArea
									when 2 then applicantmaster.DesignArea
									when 3 then applicantmaster.DesignArea
									when 4 then applicantmaster.DesignArea
									when 5 then applicantmaster.DesignArea
										else 0
										end 
								) cocontractprogressDesignArea
									
							,$sum(
									case designercocontract.contracttypeID
									when 1 then applicantmaster.LastTotal
									when 2 then applicantmaster.LastTotal
									when 3 then applicantmaster.LastTotal
									when 4 then applicantmaster.LastTotal
									when 5 then applicantmaster.LastTotal
										else 0
										end 
								)/10000000 cocontractprogressLastTotal

							,$sum(
									case designercocontract.contracttypeID
									when 1 then 1
									when 2 then 1
									when 3 then 1
									when 4 then 1
									when 5 then 1
										else 0
										end 
								) cocontractprogresscnt

						,
						case designercocontract.contracttypeID
								when 1 then 
									$sum(
										case applicantmaster.DesignArea
										when ifnull(applicantmaster.DesignArea,0)>0 and ifnull(applicantmaster.DesignArea,0)<5 then fzkargah*0.022*applicantmaster.LastTotal
										when ifnull(applicantmaster.DesignArea,0)>5 and ifnull(applicantmaster.DesignArea,0)<20 then fzkargah*0.019*applicantmaster.LastTotal
										when ifnull(applicantmaster.DesignArea,0)>20 and ifnull(applicantmaster.DesignArea,0)<50 then fzkargah*0.016*applicantmaster.LastTotal
										when ifnull(applicantmaster.DesignArea,0)>50 and ifnull(applicantmaster.DesignArea,0)<100 then fzkargah*0.014*applicantmaster.LastTotal
											else fzkargah*0.012*applicantmaster.LastTotal
											end 
										)
								when 4 then 
									$sum(
										case applicantmaster.LastTotal
										when ifnull(applicantmaster.LastTotal,0)>0 and ifnull(applicantmaster.LastTotal,0)<500000000 then 1.25*500000000/100
										when ifnull(applicantmaster.LastTotal,0)>500000000 and ifnull(applicantmaster.LastTotal,0)<2000000000 then 1.25*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>2000000000 and ifnull(applicantmaster.LastTotal,0)<5000000000 then 1.1*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>5000000000 and ifnull(applicantmaster.LastTotal,0)<10000000000 then 0.95*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>10000000000 and ifnull(applicantmaster.LastTotal,0)<20000000000 then 0.85*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>20000000000 and ifnull(applicantmaster.LastTotal,0)<50000000000 then 0.75*applicantmaster.LastTotal/100
											else 0.7*applicantmaster.LastTotal/100
											end 
										)
								when 5 then 
									$sum(
										case applicantmaster.LastTotal
										when ifnull(applicantmaster.LastTotal,0)>0 and ifnull(applicantmaster.LastTotal,0)<2000000000 then 0.25*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>2000000000 and ifnull(applicantmaster.LastTotal,0)<5000000000 then 0.22*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>5000000000 and ifnull(applicantmaster.LastTotal,0)<10000000000 then 0.19*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>10000000000 and ifnull(applicantmaster.LastTotal,0)<20000000000 then 0.17*applicantmaster.LastTotal/100
										when ifnull(applicantmaster.LastTotal,0)>20000000000 and ifnull(applicantmaster.LastTotal,0)<50000000000 then 0.15*applicantmaster.LastTotal/100
											else 0.14*applicantmaster.LastTotal/100
											end 
										)
										
						else 0
						end 
						fzkargah

				 from applicantmasterdetail
					inner join applicantcontracts on applicantcontracts.ApplicantMasterdetailID=applicantmasterdetail.ApplicantMasterdetailID
					inner join designercocontract on designercocontract.designercocontractID=applicantcontracts.designercocontractID
					and designercocontract.prjtypeid=applicantmasterdetail.prjtypeid
					inner join applicantmaster on applicantmaster.applicantmasterid=applicantmasterdetail.applicantmasterid 
					and substring(applicantmaster.cityid,1,2)=substring($login_CityId,1,2) and applicantmaster.applicantstatesID<>23
					
					left outer join  tax_tbcity7digit on tax_tbcity7digit.id=applicantmaster.CityId 

					left outer join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=applicantmasterdetail.ApplicantMasterIDmaster
					left outer join applicantmaster applicantmasteroplist on applicantmasteroplist.applicantmasterid=applicantmasterdetail.ApplicantMasterIDsurat
					$wh
					group by designercocontract.designercocontractID,designercocontract.area
			
";
return $sql;
}

function fzk($contracttypeID,$LastTotal,$DesignArea,$applicantstatesID)//تابع محاسبه ضریب fzk در گزارش قرارداد ها
{
if ($contracttypeID==1)
	{
	if ($DesignArea>0 and $DesignArea<5) $fzk=0.022;
	if ($DesignArea>5 and $DesignArea<20) $fzk=0.019;
	if ($DesignArea>20 and $DesignArea<50) $fzk=0.016;
	if ($DesignArea>50 and $DesignArea<100) $fzk=0.014;
	if ($DesignArea>100 and $DesignArea<10000) $fzk=0.012;
	}
if ($contracttypeID==4)
	{
	if ($LastTotal>0 and $LastTotal<500000000) $fzk=1.25;
	if ($LastTotal>500000000 and $LastTotal<2000000000) $fzk=1.25;
	if ($LastTotal>2000000000 and $LastTotal<5000000000) $fzk=1.1;
	if ($LastTotal>5000000000 and $LastTotal<10000000000) $fzk=0.95;
	if ($LastTotal>10000000000 and $LastTotal<20000000000) $fzk=0.85;
	if ($LastTotal>20000000000 and $LastTotal<50000000000) $fzk=0.75;
	if ($LastTotal>50000000000 and $LastTotal<500000000000) $fzk=0.7;
	}	
if ($contracttypeID==5)
	{
	if ($LastTotal>0 and $LastTotal<2000000000) $fzk=0.25;
	if ($LastTotal>2000000000 and $LastTotal<5000000000) $fzk=0.22;
	if ($LastTotal>5000000000 and $LastTotal<10000000000) $fzk=0.19;
	if ($LastTotal>10000000000 and $LastTotal<20000000000) $fzk=0.17;
	if ($LastTotal>20000000000 and $LastTotal<50000000000) $fzk=0.15;
	if ($LastTotal>50000000000 and $LastTotal<500000000000) $fzk=0.14;
	}		
return $fzk;
}


function fprogress($contracttypeID,$applicantstatesID)//تابه ماسبه میزان پیشرفت طرح در گزارش قرارداد ها
{
if ($contracttypeID==1)
	{
	if ($applicantstatesID==45) $fprogress=100;
	else if ($applicantstatesID==35) $fprogress=90;
	else if ($applicantstatesID==38) $fprogress=90;
	else if ($applicantstatesID==43) $fprogress=80;
	else if ($applicantstatesID==44) $fprogress=60;
	else if ($applicantstatesID==41) $fprogress=60;
	else if ($applicantstatesID==42) $fprogress=30;
	else if ($applicantstatesID==40) $fprogress=30;
	else $fprogress=0;
	}
if ($contracttypeID==4)
	{
	if ($applicantstatesID==23) $fprogress=0;
	else if ($applicantstatesID==2) $fprogress=0;
	else if ($applicantstatesID==3) $fprogress=50;
	else if ($applicantstatesID==4) $fprogress=50;
	else if ($applicantstatesID==5) $fprogress=75;
	else if ($applicantstatesID==11) $fprogress=75;
	else $fprogress=100;
	}	
if ($contracttypeID==5)
	{
	if ($applicantstatesID==23) $fprogress=0;
	else if ($applicantstatesID==2) $fprogress=0;
	else if ($applicantstatesID==3) $fprogress=50;
	else if ($applicantstatesID==4) $fprogress=50;
	else if ($applicantstatesID==5) $fprogress=75;
	else if ($applicantstatesID==11) $fprogress=75;
	else $fprogress=100;
	}		
return $fprogress;
}



function echorow($resquery,$rown,$b,$hid)//تابع ایجاد رشته ردیف در گزارش سامان ها
{

    $progressM=$resquery["sumfreep"]/($resquery["belaavazd"]+$resquery["selfcashhelpval"]+$resquery["selfnotcashhelpval"]);
    $progressM=round($progressM/10000,1);
    $cr='';if ($resquery["criditType"]==1) $cr="+";	
    if ($resquery["hekbarani"]>0) $hek='ب:'.$resquery["hekbarani"];
    if ($resquery["hekghatreei"]>0) 
    {
        if ($resquery["hekbarani"]>0 ) $hek.= '<br>';
        $hek.=   'ق:'.$resquery["hekghatreei"];
    }
    if ($resquery["hekkamfeshar"]>0) 
    {
        if ($resquery["hekbarani"]>0 || $resquery["hekghatreei"]>0 ) $hek.= '<br>';
        $hek.=   'ک:'.$resquery["hekkamfeshar"];
    }
    $UTM="";
    
    if ($resquery["XUTM1"]>0) $UTM="X=".$resquery["XUTM1"]."<br>Y=".$resquery["YUTM1"];
    //if ($resquery["XUTM1"]>0) $UTM=rtrim(ltrim("X=".number_format($resquery["XUTM1"])." Y=".number_format($resquery["YUTM1"]) ));
                           
                           
    
    if (substr($resquery["CheckDate"],0,2)=='20') $CheckDate= gregorian_to_jalali($resquery["CheckDate"]); else if (strlen($resquery["CheckDate"])>0) 
    $CheckDate= compelete_date($resquery["CheckDate"]);
    if (substr($resquery["tahvildatezamin"],0,2)=='20') $tahvildatezamin= gregorian_to_jalali($resquery["tahvildatezamin"]); else if (strlen($resquery["tahvildatezamin"])>0) 
    $tahvildatezamin= compelete_date($resquery["tahvildatezamin"]);
    if (substr($resquery["workdeliveryend"],0,2)=='20') $workdeliveryend= gregorian_to_jalali($resquery["workdeliveryend"]); else if (strlen($resquery["workdeliveryend"])>0) 
    $workdeliveryend= compelete_date($resquery["workdeliveryend"]);
    if (substr($resquery["permanentfreedate"],0,2)=='20') $permanentfreedate= gregorian_to_jalali($resquery["permanentfreedate"]); 
    else if (strlen($resquery["permanentfreedate"])>0) $permanentfreedate= compelete_date($resquery["permanentfreedate"]);
    
	if ($hid==1) 
	{$display="display:none;";$displayoff="";
				$login_CityId=19;
				$designercocontractID=$resquery["designercocontractID"];
				$ApplicantMasterdetailID=$resquery["applicantmasterdetailid"];
				$wh=" where applicantmasterdetail.applicantmasterdetailid='$ApplicantMasterdetailID' 
						and designercocontract.designercocontractID='$designercocontractID' ";
				$sqlcont= sqlcont($login_CityId,$wh);
			//print $sqlcont;exit;
				$result = mysql_query($sqlcont);
				$row = mysql_fetch_assoc($result);
				$cocontractprogress=round($row['cocontractprogress'],2);
				$cocontractprogressLastTotal=round($row['cocontractprogressLastTotal'],2);
				
				$contracttypeID=$row['contracttypeID'];$LastTotald=$resquery['LastTotald']*1000000;$DesignAread=$resquery['DesignAread'];$applicantstatesID=$resquery['applicantstatesID'];
				
				$fprogress=fprogress($contracttypeID,$applicantstatesID);
			//applicantmaster.LastTotal LastTotaldfee,applicantmasterop.LastTotal LastTotalopfee,applicantmasteroplist.LastTotal LastTotaloplistfee
				if ($contracttypeID==1) $LastTotal=$resquery['LastTotaloplistfee'];else $LastTotal=$resquery['LastTotaldfee'];
				$fzk=fzk($contracttypeID,$LastTotal,$DesignAread,$applicantstatesID);
				
				$fzkargahf=$row['fzkargahf'];			
				$fees=round($LastTotal*$fzkargahf*$fzk*$fprogress/10000);
				$fees=number_format($fees);
				
			//print	$fzk;exit;
	} else {$display="";$displayoff="display:none;";}
	
	$retstr="<tr >
				<td class=\"f10_font$b\" style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">".$rown.''.$cr."</td>
                <td class=\"f10_font$b\" style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[ApplicantFName]</td>
							
                <td class=\"f10_font$b\" style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[ApplicantName]</td>
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin';\">$resquery[DesignSystemGroupstitle]</td>
                <td class=\"f10_font$b\" style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[DesignAread]</td>
							
                <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$hek</td>
                <td class=\"f8_font$b\" style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[shahrcityname]</td>
				<td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[CountyName]</td>
							
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[watersourceTitle]</td>
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$UTM</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[Debi]</td>
							
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[creditsourceTitle]</td>
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[creditbank]</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[LastTotald]</td>
			    <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[belaavazd]</td>
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">".$resquery['selfcashhelpval']."<br>".$resquery['selfnotcashhelpval']."</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[DesignerCoTitle]</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$resquery[bazbin]</td>
						
                <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin'; $display \">$CheckDate</td>
                <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin'; $display \">$tahvildatezamin</td>
                <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin'; $display \">$workdeliveryend</td>
                <td class=\"f8_font$b\"  style=\"color:#;text-align: center;font-size:8.0pt;font-family:'B Nazanin'; $display \">$permanentfreedate</td>
							
                <td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[DesignerCoIDnazerTitle]</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[operatorcotitle]</td>
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\"></td>
							
				<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:7.0pt;font-family:'B Nazanin';\">$resquery[applicantstatesTitle]</td>
               	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $display \">$resquery[progress]</td>
              	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $display \">$progressM</td>
				
			   	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $displayoff \">$fprogress</td>
              	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $displayoff \">$fzkargahf</td>
 
              	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $displayoff \">$fzk</td>
              	<td class=\"f9_font$b\"  style=\"color:#;text-align: center;font-size:9.0pt;font-family:'B Nazanin'; $displayoff \">$fees</td>
              	<td class=\"f9_font$b\"   $display \"><a target='_blank' href='../appinvestigation/prjcontracts.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery["ApplicantMasterID"].
                                        rand(10000,99999)."'>
										<img style = 'width: 30px;' src='../img/law.png' title=' قراردادهای مشاورین '></a></td>
                
        </tr>";
    return $retstr;		     
                           
                   
}


function freequery($ApplicantMasterID,$login_CityId,$kejra1,$kejra2,$kejra3,$kejra4,$sqlPricekejra,$Pricek,$Pricekejra,$strcond,$orderby)//تابع ایجاد پرس و جوی گزارش آزادسازی
{
    
    if ($ApplicantMasterID>0)
    $strcond="and applicantmaster.applicantmasterid='$ApplicantMasterID'";


 
    $sql = "SELECT distinct applicantmaster.applicantmasterid,applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.letterno
    ,applicantmastersurat.letterno letternosurat
    ,applicantmastersurat.applicantstatesID applicantstatesIDsurat,applicantmastersurat.belaavaz belaavazsurat
	,applicantmaster.errType,applicantmasterall.criditType
	,applicantmaster.DesignArea,applicantmaster.CityId,creditsource.title creditsourcetitle ,creditsource.creditsourceid
    ,applicantmasterall.belaavaz belaavaz
	,case ifnull(applicantmaster.belaavaz,0) when 0 then applicantmasterall.belaavaz else applicantmaster.belaavaz end belaavazlast
    ,applicantmasterall.sandoghcode,applicantmaster.LastTotal
	,(floor(applicantmasterall.LastTotal/100000)*100000) LastTotald
    ,applicantmasterall.LastFehrestbaha LastFehrestbahad
	,applicantmasterall.TotlainvoiceValues TotlainvoiceValuesd,applicantmastersurat.TotlainvoiceValues TotlainvoiceValues
    ,applicantmasterall.selfcashhelpval,applicantmasterall.selfnotcashhelpval
    ,ifnull(applicantmasterall.selfcashhelpval,0)+ifnull(applicantmasterall.selfnotcashhelpval,0) selfhelp
    ,(applicantfreedetail1.Price-$kejra1) Price1,(applicantfreedetail2.Price-$kejra2) Price2 ,(applicantfreedetail3.Price-$kejra3) Price3 
    ,(applicantfreedetail4.Price-$kejra4) Price4
	,(applicantfreedetail5.Price) Priceall
	
	,'$Pricek' Pricek
	,'$Pricekejra' Pricekejra

	,operatorco.title operatorcotitle,shahr.cityname shahrcityname,shahr.id shahrid,operatorco.operatorcoid
    ,designsystemgroups.Title designsystemgroupsTitle,designsystemgroups.DesignSystemGroupsid		
	,(applicantmastersurat.LastTotal+$sqlPricekejra) LastTotals,applicantmastersurat.LastFehrestbahawithcoef+ifnull(applicantmastersurat.othercosts1,0)+ifnull(applicantmastersurat.othercosts2,0)
    +ifnull(applicantmastersurat.othercosts4,0) LastFehrestbaha
	,(applicantmastersurat.othercosts1+applicantmastersurat.othercosts2+applicantmastersurat.othercosts3+applicantmastersurat.othercosts4) othercosts
    ,applicantmastersurat.othercosts5
    ,applicantstates.title applicantstatestitle
    
    ,applicantstates.applicantstatesID,applicantmastersurat.applicantmasterid applicantmasteridsurat
    ,applicantmasterall.applicantmasterid applicantmasteridd
	FROM `applicantmaster`
    inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid   
inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join (select applicantmasterid,sum(Price*case paytype when 1 then -1 else 1 end) Price from applicantfreedetail where freestateID=141 group by applicantmasterid) applicantfreedetail1 on applicantfreedetail1.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price*case paytype when 1 then -1 else 1 end) Price from applicantfreedetail where freestateID=142 group by applicantmasterid) applicantfreedetail2 on applicantfreedetail2.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price*case paytype when 1 then -1 else 1 end) Price from applicantfreedetail where freestateID=143 group by applicantmasterid) applicantfreedetail3 on applicantfreedetail3.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price*case paytype when 1 then -1 else 1 end) Price from applicantfreedetail where freestateID=144 group by applicantmasterid) applicantfreedetail4 on applicantfreedetail4.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price*case paytype when 1 then -1 else 1 end) Price from applicantfreedetail  group by applicantmasterid) applicantfreedetail5 on applicantfreedetail5.applicantmasterid =applicantmaster.applicantmasterid

inner join applicantmaster applicantmasterall on applicantmasterdetail.ApplicantMasterID=applicantmasterall.applicantmasterid
left outer join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='22') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
left outer join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='37') app37 on app37.ApplicantMasterID=applicantmasterall.ApplicantMasterID
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmaster.operatorcoID=operatorapprequest.operatorcoID   

left outer join applicantmaster applicantmastersurat on applicantmasterdetail.ApplicantMasterIDsurat=applicantmastersurat.applicantmasterid

inner join applicantstates on applicantstates.applicantstatesID=applicantmastersurat.applicantstatesID
left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
left outer join creditsource on creditsource.creditsourceid=case 
ifnull(applicantmaster.creditsourceid,0) when 0 then applicantmasterall.creditsourceid else applicantmaster.creditsourceid end
where   substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
$strcond
$orderby
;";
//print $sql;

return $sql;
}	

function retgadget3operational($ApplicantMasterID)//تابه ایجاد رشته هزینه اجرایی هر ابزار
{
    return "
    (select view1.gadget3operationalID,view1.gadget3ID,view1.Gadget3IDOperational,appcoef.invoicemasterid,CostCoef from(
SELECT gadget3operationalID,gadget3ID,Gadget3IDOperational,CostCoef,'2000-09-13 17:04:42' SaveTime FROM `gadget3operational`
union all
SELECT gadget3operationalID,gadget3operational.gadget3ID,gadget3operational.Gadget3IDOperational,newcoef CostCoef,gadget3operationalnewcoefs.SaveTime FROM `gadget3operational`
inner join gadget3operationalnewcoefs on gadget3operationalnewcoefs.Gadget3IDOperationalold=gadget3operational.Gadget3IDOperational
and gadget3operationalnewcoefs.gadget3IDold=gadget3operational.gadget3ID) view1
inner join 
(
select view1.gadget3ID,invoicemaster.invoicemasterid,gadget3operationalID,max(view1.SaveTime) SaveTime from 

(
SELECT gadget3operationalID,gadget3ID,Gadget3IDOperational,CostCoef,'2000-09-13 17:04:42' SaveTime FROM `gadget3operational`
union all
SELECT gadget3operationalID,gadget3operational.gadget3ID,gadget3operational.Gadget3IDOperational,newcoef CostCoef,gadget3operationalnewcoefs.SaveTime FROM `gadget3operational`
inner join gadget3operationalnewcoefs on gadget3operationalnewcoefs.Gadget3IDOperationalold=gadget3operational.Gadget3IDOperational
and gadget3operationalnewcoefs.gadget3IDold=gadget3operational.gadget3ID

)view1 
inner join toolsmarks on toolsmarks.gadget3ID=view1.gadget3ID
inner join invoicedetail on invoicedetail.toolsmarksID=toolsmarks.toolsmarksID
inner join invoicemaster on invoicemaster.invoicemasterID=invoicedetail.invoicemasterID and invoicemaster.ApplicantMasterID='$ApplicantMasterID' 
where view1.SaveTime<=invoicemaster.SaveTime
group by view1.gadget3ID,invoicemaster.invoicemasterid,gadget3operationalID
) appcoef on appcoef.gadget3ID=view1.gadget3ID and appcoef.gadget3operationalID=view1.gadget3operationalID
and appcoef.SaveTime=view1.SaveTime
)
 ";

 
 
}
function fehrestquery($fautomatic,$fmandal,$ApplicantMasterID,$costpricelistmasterID,$cityid15,$fcond,$fcond2)//تابعع ایجاد پرس و جوی هزینه های اجرایی پروژه
{
        if ($fautomatic==1)
    {
        $gadget3operationalstr=retgadget3operational($ApplicantMasterID);
       //print $gadget3operationalstr;exit;
        if ($fmandal==1)
             $sqlouterauto="select 'آبیاری تحت فشار' ftype,2 fehrestsmasterID,0 nval1, 0 nval2,0 nval3,0 pval1,0 pval2,0 pval3,0 tblid,_utf8'فهرست بها' AS `Type`,2 AS `TCode`,
                        cast(`costsgroups`.`Code` as decimal(10,0)) AS `ToolsGroupsCode`,`costsgroups`.`Title` AS `CostsGroupsTitle`,`gadget3costs`.`Title` AS `Title`,
                        `gadget3costs`.`Code` AS `Code`,
                        round(sum((`invoicedetail`.`Number` * `gadget3operational`.`CostCoef`)),2) AS `Number`,'' FNumber,'' Number2
						,'' Number3,'' Number4,'' Number5,'' Number6
					
						,'' Description,`units`.`Title` AS `unit`,
                        `costpricelistdetail`.`Price` AS `Price`,
                        (`costpricelistdetail`.`Price` * round(sum((`invoicedetail`.`Number` * `gadget3operational`.`CostCoef`)),2)) AS `Total`,
                        `invoicemaster`.`ApplicantMasterID` AS `ApplicantMasterID`,ifnull(applicantcostcodechange.GCode,'') NGCode,ifnull(applicantcostcodechange.TCode,'') NTCode
                        ,gadget3operational.gadget3operationalID,'' price2,'' appfoundationtitle,'' appfoundationID,invoicemaster.appsubprjID,-1 aut,'' fehrestsfaslsID
                        from `invoicedetail` 
                        join `toolsmarks` on `toolsmarks`.`ToolsMarksID` = `invoicedetail`.`ToolsMarksID` 
                        join `invoicemaster` on `invoicemaster`.`InvoiceMasterID` = `invoicedetail`.`InvoiceMasterID` and invoicemaster.ApplicantMasterID='$ApplicantMasterID' and (ifnull(`invoicemaster`.`costnotinrep`,0) = 0) 
                        join `gadget3` on `gadget3`.`Gadget3ID` = `toolsmarks`.`gadget3ID` 
                        left join $gadget3operationalstr gadget3operational 
                        on gadget3operational.gadget3ID = gadget3.Gadget3ID and gadget3operational.invoicemasterid=invoicemaster.invoicemasterid
                        left outer join applicantcostcodechange on applicantcostcodechange.ApplicantMasterID='$ApplicantMasterID' 
                        and applicantcostcodechange.gadget3operationalID=gadget3operational.gadget3operationalID
                        
                        left join `gadget3` `gadget3costs` on `gadget3costs`.`Gadget3ID` = `gadget3operational`.`Gadget3IDOperational` 
                        left join `gadget2` `gadget2costs` on `gadget3costs`.`Gadget2ID` = `gadget2costs`.`Gadget2ID` 
                        left join `gadget2` on `gadget2`.`Gadget2ID` = `gadget3costs`.`Gadget2ID` and gadget2.Gadget1ID=12
                        left join `units` on `units`.`UnitsID` = `gadget3costs`.`unitsID` 
                        left join `costsgroups` on `costsgroups`.`Code` = case ifnull(applicantcostcodechange.GCode,'')='' when 1 then gadget2costs.Code else applicantcostcodechange.GCode end 
                        left join `costpricelistdetail` on `costpricelistdetail`.`CostPriceListMasterID` = '$costpricelistmasterID'
                        and `costpricelistdetail`.`Gadget3ID` = `gadget3costs`.`Gadget3ID` 
                        where (ifnull(`gadget3costs`.`Code`,0) > 0) and ifnull(invoicedetail.deactive,0)=0 
                        $fcond
                        group by `gadget3costs`.`Code`,`costsgroups`.`Code`,`costsgroups`.`Title`,`gadget3costs`.`Title`,
                        `units`.`Title`,`costpricelistdetail`.`Price` 
                        union all
                        ";
         else
             $sqlouterauto="select 'آبیاری تحت فشار' ftype,2 fehrestsmasterID,0 nval1, 0 nval2,0 nval3,0 pval1,0 pval2,0 pval3,0 tblid,_utf8'فهرست بها' AS `Type`,2 AS `TCode`,
                        cast(`costsgroups`.`Code` as decimal(10,0)) AS `ToolsGroupsCode`,`costsgroups`.`Title` AS `CostsGroupsTitle`,`gadget3costs`.`Title` AS `Title`,`gadget3costs`.`Code` AS `Code`,
                        round(sum((`invoicedetail`.`Number` * `gadget3operational`.`CostCoef`)),2) AS `Number`,'' FNumber,'' Number2
						,'' Number3,'' Number4,'' Number5,'' Number6
						,'' Description,`units`.`Title` AS `unit`,`costpricelistdetail`.`Price` AS `Price`,
                        (`costpricelistdetail`.`Price` * round(sum((`invoicedetail`.`Number` * `gadget3operational`.`CostCoef`)),2)) AS `Total`,
                        `invoicemaster`.`ApplicantMasterID` AS `ApplicantMasterID`,ifnull(applicantcostcodechange.GCode,'') NGCode,ifnull(applicantcostcodechange.TCode,'') NTCode
                        ,gadget3operational.gadget3operationalID,'' price2,'' appfoundationtitle,'' appfoundationID,invoicemaster.appsubprjID,-1 aut,'' fehrestsfaslsID
                        from `invoicedetail` 
                        join `toolsmarks` on `toolsmarks`.`ToolsMarksID` = `invoicedetail`.`ToolsMarksID` 
                        join `invoicemaster` on `invoicemaster`.`InvoiceMasterID` = `invoicedetail`.`InvoiceMasterID` and invoicemaster.ApplicantMasterID='$ApplicantMasterID' and (ifnull(`invoicemaster`.`costnotinrep`,0) = 0) 
                        join `gadget3` on `gadget3`.`Gadget3ID` = `toolsmarks`.`gadget3ID` 
                        join $gadget3operationalstr gadget3operational 
                        on gadget3operational.gadget3ID = gadget3.Gadget3ID and gadget3operational.invoicemasterid=invoicemaster.invoicemasterid 
                        left outer join applicantcostcodechange on applicantcostcodechange.ApplicantMasterID='$ApplicantMasterID' 
                        and applicantcostcodechange.gadget3operationalID=gadget3operational.gadget3operationalID
                        
                        inner join `gadget3` `gadget3costs` on `gadget3costs`.`Gadget3ID` = `gadget3operational`.`Gadget3IDOperational`
                        left join `gadget2` `gadget2costs` on `gadget3costs`.`Gadget2ID` = `gadget2costs`.`Gadget2ID` 
                        left join `gadget2` on `gadget2`.`Gadget2ID` = `gadget3costs`.`Gadget2ID`  and gadget2.Gadget1ID=12
                        left join `units` on `units`.`UnitsID` = `gadget3costs`.`unitsID` 
                        left join `costsgroups` on `costsgroups`.`Code` = case ifnull(applicantcostcodechange.GCode,'')='' when 1 then gadget2costs.Code else applicantcostcodechange.GCode end 
                        left join `costpricelistdetail` on `costpricelistdetail`.`CostPriceListMasterID` = '$costpricelistmasterID'
                        and `costpricelistdetail`.`Gadget3ID` = `gadget3costs`.`Gadget3ID` 
                        where (ifnull(`gadget3costs`.`Code`,0) > 0) and ifnull(invoicedetail.deactive,0)=0 
                        and substring(gadget3costs.Title,1,1)<>'*'
                        $fcond
                        group by `gadget3costs`.`Code`,`costsgroups`.`Code`,`costsgroups`.`Title`,`gadget3costs`.`Title`,
                        `units`.`Title`,`costpricelistdetail`.`Price` 
                        union all
                        ";                           
    }
   
        
    return " SELECT ftype,fehr.fehrestsmasterID,regioncoef.val regioncoefval,gadget3operationalID,price2,appfoundationtitle,appfoundationID,appsubprjID,aut,fehrestsfaslsID,nval1,
    nval2,nval3,pval1,pval2,pval3,tblid,TCode,Total,Price,unit,Number,FNumber,Number2,Number3,Number4,Number5,Number6,Description,Title,CostsGroupsTitle
    ,case trim(NGCode)<>'' when 1 then cast(trim(NGCode) as decimal(10,0)) else  ToolsGroupsCode end ToolsGroupsCode
    ,case trim(NTCode)<>'' when 1 then cast(trim(NTCode) as decimal(10,0)) else cast(Code as decimal) end Code FROM 
    (
    
    
$sqlouterauto    
    
 select  'آبیاری تحت فشار' ftype,2 fehrestsmasterID,nval1,nval2,nval3
,case manuallistprice.AddOrSub>=1 when 1 then pval1 else -1*pval1 end pval1 
,case manuallistprice.AddOrSub>=1 when 1 then pval2 else -1*pval2 end pval2
,case manuallistprice.AddOrSub>=1 when 1 then pval3 else -1*pval3 end pval3
,manuallistprice.ManualListPriceID tblid,_utf8'فهرست بهاي دستي' AS `Type`,3 AS `TCode`,cast(`costsgroups`.`Code` as decimal(10,0)) AS `ToolsGroupsCode`,
`costsgroups`.`Title` AS `CostsGroupsTitle`,
concat(
case manuallistprice.CostsGroupsID<>14 when 1 then 
(case when (`manuallistprice`.`AddOrSub` = 1 ) then _utf8' اضافه بها ' when (`manuallistprice`.`AddOrSub` = 2 ) then '*' else _utf8' کسربها ' end) else '' end
,`manuallistprice`.`Title`) AS `Title`,
`manuallistprice`.`Code` AS `Code`,`manuallistprice`.`Number` AS `Number`,'' FNumber
,case ifnull(manuallistprice.Number2,0)=0 when 1 then 1 else manuallistprice.Number2 end Number2
,'' Number3,'' Number4,'' Number5,'' Number6

,manuallistprice.Description,`manuallistprice`.`Unit` AS `unit`,
((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) AS Price,(((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) * `manuallistprice`.`Number`*case ifnull(manuallistprice.Number2,0)=0 when 1 then 1 else manuallistprice.Number2 end) AS `Total`
,`manuallistprice`.`ApplicantMasterID` AS `ApplicantMasterID`,'' NGCode,'' NTCode,'' gadget3operationalID,'' price2,'' appfoundationtitle,'' appfoundationID,
manuallistprice.appsubprjID,manuallistprice.fehrestsfaslsID,1 aut
from `manuallistprice` 
inner join `costsgroups` on `costsgroups`.`CostsGroupsID` = `manuallistprice`.`CostsGroupsID` 
where manuallistprice.ApplicantMasterID ='$ApplicantMasterID' and manuallistprice.appfoundationID<>-1


union all select  fehrestsmaster.Title ftype,fehrestsmaster.fehrestsmasterID,nval1,nval2,nval3
,case manuallistprice.AddOrSub>=1 when 1 then pval1 else -1*pval1 end pval1 
,case manuallistprice.AddOrSub>=1 when 1 then pval2 else -1*pval2 end pval2
,case manuallistprice.AddOrSub>=1 when 1 then pval3 else -1*pval3 end pval3
,manuallistprice.ManualListPriceID tblid,_utf8'فهرست بهاي دستي' AS `Type`,3 AS `TCode`,cast(fehrestsfasls.fasl as decimal(10,0)) AS `ToolsGroupsCode`,
fehrestsfasls.Title AS `CostsGroupsTitle`,
concat(
case when (`manuallistprice`.`AddOrSub` = 1 ) then _utf8' اضافه بها ' when (`manuallistprice`.`AddOrSub` = 2 ) then '*' else _utf8' کسربها ' end
,`manuallistprice`.`Title`) AS `Title`,
`manuallistprice`.`Code` AS `Code`,`manuallistprice`.`Number` AS `Number`,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber
,case ifnull(manuallistprice.Number2,0) when 0 then 1 else manuallistprice.Number2 end Number2
,'' Number3,'' Number4,'' Number5,'' Number6

,manuallistprice.Description,`manuallistprice`.`Unit` AS `unit`,
((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) AS Price,(((case when (`manuallistprice`.`AddOrSub` >= 1) 
then 1 else -(1) end) * `manuallistprice`.`Price`) * `manuallistprice`.`Number`*case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end*case ifnull(manuallistprice.Number2,0) when 0 then 1 else manuallistprice.Number2 end) AS `Total`
,`manuallistprice`.`ApplicantMasterID` AS `ApplicantMasterID`,'' NGCode,'' NTCode,'' gadget3operationalID,'' price2,concat(appfoundation.title,'_',appfoundation.appfoundationID,'_',ifnull(appfoundationmoderate.Price,0),'_',ifnull(appfoundationmoderate.fehrestsmasterID,0)) appfoundationtitle
,manuallistprice.appfoundationID appfoundationID,manuallistprice.appsubprjID,manuallistprice.fehrestsfaslsID,1 aut
from `manuallistprice` 
inner join fehrestsfasls on fehrestsfasls.fehrestsfaslsID = manuallistprice.fehrestsfaslsID
inner join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrestsfasls.fehrestsmasterID
left outer join appfoundation on appfoundation.appfoundationID=manuallistprice.appfoundationID
left outer join appfoundationmoderate on appfoundationmoderate.appfoundationID=manuallistprice.appfoundationID
where manuallistprice.ApplicantMasterID ='$ApplicantMasterID' and manuallistprice.appfoundationID<>-1

union all select  fehrestsmaster.Title ftype,fehrestsmaster.fehrestsmasterID,nval1,nval2,nval3,pval1 ,pval2,pval3
,manuallistpriceall.ManualListPriceAllID tblid,_utf8'فهارس بها' AS `Type`,4 AS `TCode`,cast(fehrestsfasls.fasl as decimal(10,0)) AS `ToolsGroupsCode`,
fehrestsfasls.Title AS `CostsGroupsTitle`,
fehrests.Title AS `Title`,
fehrests.Code AS `Code`,manuallistpriceall.Number,case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end FNumber
,case ifnull(manuallistpriceall.Number2,0) when 0 then 1 else manuallistpriceall.Number2 end Number2
,manuallistpriceall.Number3,manuallistpriceall.Number4,manuallistpriceall.Number5,manuallistpriceall.Number6

,manuallistpriceall.Description
,fehrests.UnitTitle AS `unit`,case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end Price,
case pricelistdetailall.price>0 when 1 then pricelistdetailall.price else manuallistpriceall.Price end*manuallistpriceall.Number*case ifnull(appfoundation.Number,0) when 0 then 1 else ifnull(appfoundation.Number,0) end*case ifnull(manuallistpriceall.Number2,0) when 0 then 1 else manuallistpriceall.Number2 end  Total
,manuallistpriceall.ApplicantMasterID,'' NGCode,'' NTCode,'' gadget3operationalID,pricelistdetailall.price price2,concat(appfoundation.title,'_',appfoundation.appfoundationID,'_',ifnull(appfoundationmoderate.Price,0),'_',ifnull(appfoundationmoderate.fehrestsmasterID,0)) appfoundationtitle
,manuallistpriceall.appfoundationID appfoundationID,manuallistpriceall.appsubprjID,fehrestsfasls.fehrestsfaslsID,2 aut

from manuallistpriceall 
inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID
inner join fehrestsmaster on fehrestsmaster.fehrestsmasterID=fehrests.fehrestsmasterID
inner join fehrestsfasls on fehrestsfasls.fasl = substring(fehrests.Code,1,2) and fehrestsfasls.fehrestsmasterID=fehrests.fehrestsmasterID
left outer join pricelistdetailall on pricelistdetailall.fehrestsID=fehrests.fehrestsID and pricelistdetailall.CostPriceListMasterID='$costpricelistmasterID'
left outer join appfoundation on appfoundation.appfoundationID=manuallistpriceall.appfoundationID
left outer join appfoundationmoderate on appfoundationmoderate.appfoundationID=manuallistpriceall.appfoundationID
where manuallistpriceall.ApplicantMasterID ='$ApplicantMasterID' and manuallistpriceall.appfoundationID<>-1


) fehr

left outer join regioncoef on fehr.fehrestsmasterID=regioncoef.fehrestsmasterID 
and regioncoef.costpricelistmasterID='$costpricelistmasterID' and regioncoef.cityID='$cityid15'
where 1=1 $fcond $fcon2   
order by ftype,ToolsGroupsCode,ifnull(appfoundationtitle,''),cast(Code as decimal) ";
}
//تابع محاسبه انقضای مجوز های شرکت های طراح
function member_de_error($resquerycopermisionvalidate,$dateYmd,$resqueryboardvalidationdate,$resquerydesignercnt,$resqueryduplicatedesigner)
{
    $errors=array();
    if ($resquerycopermisionvalidate<$dateYmd) 
    {
	   $errors[0]="<br>*انقضاء تاريخ مجوز دفتر بهبود $resquerycopermisionvalidate.";
    }
    if (($resqueryboardvalidationdate)<$dateYmd)
        $errors[1]="<br>*انقضاء تاريخ آگهي تغييرات شركت $resqueryboardvalidationdate.";
					
    if (!($resquerydesignercnt>=1))
        $errors[2]="<br>شرکت فاقد کارشناس طراح است.";
                      
    if (($resqueryduplicatedesigner>=1))
        $errors[3]="<br>کارشناس طراح اين شرکت در بيش از يک شرکت شاغل مي باشد.";
 return ($errors);                           
}
//تابع محاسبه انقضای مجوز های شرکت های مجری
function member_op_error (
$resquerycopermisionvalidate,$dateYmd,$resqueryboardvalidationdate,
$resquerydesignercnt,$resqueryStarCo,$resqueryduplicatedesigner,$resquerysimultaneouscnt,$Permissionvalstmphtp,
$resquerycorank,$resqueryDesignArea,$Permissionvalssmallapplicantsize,$resqueryabove20cnt,$Permissionvalstmtb10hp1,$Permissionvalstmtb10hp2
,$Permissionvalstmtb10hp3,$Permissionvalstmtb10hp4,$Permissionvalstmtb10hp5,$resqueryabove55cnt,$Permissionvalstmtb50hp5,$Permissionvalshmmp1
,$Permissionvalshmmp2,$Permissionvalshmmp3,$Permissionvalshmmp4,$Permissionvalshmmp5,$resquerythisyearprgarea,$Permissionvalshmmsmp1
,$Permissionvalshmmsmp2,$Permissionvalshmmsmp3,$Permissionvalshmmsmp4,$Permissionvalshmmsmp5,$resqueryengineersystemvalidate,$ent_Num
,$ent_DateTo,$valueaddedvalidate)
{
    $errors=array();
    
					    if ($resquerycopermisionvalidate<$dateYmd) 
                           {
						   $errors[0]="<br>*انقضاء تاريخ مجوز دفتر بهبود $resquerycopermisionvalidate.";
						   }
                        if (($resqueryboardvalidationdate)<$dateYmd)
                            $errors[1]="<br>*انقضاء تاريخ آگهي تغييرات شركت $resqueryboardvalidationdate.";
					
						if (!($resquerydesignercnt>=1))
                            $errors[2]="<br>شرکت فاقد کارشناس طراح است .";
                        
                        if ($resqueryStarCo==1)  
							{  
							     $errors[3]="<br>شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت نمي باشد.";  
							}
                            else if ($ent_Num>0 && $ent_DateTo>=$dateYmd)
                                $errors[3]="<br> شرکت طبق مصوبه کميته فني آب و خاک و آيين نامه  مجاز به پيشنهاد قيمت انتظامی میباشد .";
                        
                        if (($resqueryduplicatedesigner>=1))
                            $errors[4]="<br>کارشناس طراح اين شرکت در بيش از يک شرکت شاغل مي باشد.";
                        if (($resquerysimultaneouscnt>=$Permissionvalstmphtp) && $resqueryDesignArea>0)
                            $errors[5]="<br>تعداد پروژه هاي جاري اين شرکت 5 يا بيشتر مي باشد.";
                        if (($resquerycorank==1) && ($resqueryDesignArea>$Permissionvalssmallapplicantsize) && ($resqueryabove20cnt>=$Permissionvalstmtb10hp1))  
                            $errors[6]="<br>تعداد مجاز طرح هاي بزرگ پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                        if (($resquerycorank==2) && ($resqueryDesignArea>$Permissionvalssmallapplicantsize) && ($resqueryabove20cnt>=$Permissionvalstmtb10hp2))  
                            $errors[7]="<br>تعداد مجاز طرح هاي بزرگ پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                        if (($resquerycorank==3) && ($resqueryDesignArea>$Permissionvalssmallapplicantsize) && ($resqueryabove20cnt>=$Permissionvalstmtb10hp3))  
                            $errors[8]="<br>تعداد مجاز طرح هاي بزرگ پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                        if (($resquerycorank==4) && ($resqueryDesignArea>$Permissionvalssmallapplicantsize) && ($resqueryabove20cnt>=$Permissionvalstmtb10hp4))  
                            $errors[9]="<br>تعداد مجاز طرح هاي بزرگ پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                        if (($resquerycorank==5) && ($resqueryDesignArea>$Permissionvalssmallapplicantsize) && ($resqueryabove20cnt>=$Permissionvalstmtb10hp5))  
                            $errors[10]="<br>تعداد مجاز طرح هاي بزرگ پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                        if (($resquerycorank==5) && ($resqueryDesignArea>55) && ($resqueryabove55cnt>=$Permissionvalstmtb50hp5))  
                            $errors[11]="<br>تعداد مجاز طرح هاي بالاي 55 هکتار پايه $resquerycorank بيشتر از حد مجاز مي باشد.";  
                            
                        if (($resquerycorank==1) && ($resqueryDesignArea>$Permissionvalshmmp1))  
                            $errors[12]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==2) && ($resqueryDesignArea>$Permissionvalshmmp2))  
                            $errors[13]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==3) && ($resqueryDesignArea>$Permissionvalshmmp3))  
                            $errors[14]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==4) && ($resqueryDesignArea>$Permissionvalshmmp4))  
                            $errors[15]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==5) && ($resqueryDesignArea>$Permissionvalshmmp5))  
                            $errors[16]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر مجاز پايه $resquerycorank مي باشد.";  
                            
                        if (($resquerycorank==1) && $resqueryDesignArea>0 && (($resqueryDesignArea+$resquerythisyearprgarea)>$Permissionvalshmmsmp1))  
                            $errors[17]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر سالانه مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==2) && $resqueryDesignArea>0 && (($resqueryDesignArea+$resquerythisyearprgarea)>$Permissionvalshmmsmp2))  
                            $errors[18]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر سالانه مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==3) && $resqueryDesignArea>0 && (($resqueryDesignArea+$resquerythisyearprgarea)>$Permissionvalshmmsmp3))  
                            $errors[19]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر سالانه مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==4) && $resqueryDesignArea>0 && (($resqueryDesignArea+$resquerythisyearprgarea)>$Permissionvalshmmsmp4))  
                            $errors[20]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر سالانه مجاز پايه $resquerycorank مي باشد.";  
                        if (($resquerycorank==5) && $resqueryDesignArea>0 && (($resqueryDesignArea+$resquerythisyearprgarea)>$Permissionvalshmmsmp5))  
                            $errors[21]="<br>مساحت اين پروژه بيشتر از مساحت حداکثر سالانه مجاز پايه $resquerycorank مي باشد.";  
                        
			             /*if ((compelete_date($resqueryengineersystemvalidate)<$dateYmd) )
                           {
						    $errors[22]="<br>*تاریخ مجوز سازمان نظام مهندسی شرکت منقضی شده است."; 
						   }*/
						//print $valueaddedvalidate.$dateYmd;
                        
                	    if ($valueaddedvalidate>0 && $valueaddedvalidate<$dateYmd) 
                           {
						   $errors[0]="<br>*انقضاء تاريخ  ارزش افزوده $valueaddedvalidate.";
						   }

                            
 							
 return ($errors);
                       
}
//تابع محاسبه انقضای مجوز های تولیدکنندگان  
function producer_error($Permissionvalsp1Zemanat,$Permissionvalsp2Zemanat,$Permissionvalsp3Zemanat,$Permissionvalsp4Zemanat
,$Permissionvalsp5Zemanat,$Permissionvalsp1Zpishhamzaman,$Permissionvalsp2Zpishhamzaman,$Permissionvalsp3Zpishhamzaman
,$Permissionvalsp4Zpishhamzaman,$Permissionvalsp5Zpishhamzaman,$Permissionvalsp1Zpishhamzamanvol,$Permissionvalsp2Zpishhamzamanvol
,$Permissionvalsp3Zpishhamzamanvol,$Permissionvalsp4Zpishhamzamanvol,$Permissionvalsp5Zpishhamzamanvol,$dateYmd
,$resquerycorank,$resqueryguaranteeExpireDate,$resqueryguaranteepayval
,$resqueryinvoicenotdeliveredcnt,$resquerytonajval,$resqueryprojtonajval,$resqueryboardvalidationdate,$resquerycopermisionvalidate
,$valueaddedvalidate
)
{
    
    $errors=array();
    if (($resquerycorank<1)||($resquerycorank>5)) 
        $errors[0]="<br>*رتبه شرکت نامعتبر می باشد ";
                             
    if ($resqueryguaranteeExpireDate<$dateYmd)
        $errors[1]="<br>*انقضاء تاریخ اعتبار ضمانتنامه بانکی (".$resqueryguaranteeExpireDate.")";
                       
    if ($resquerycorank==1 && ($resqueryguaranteepayval<$Permissionvalsp1Zemanat*10))      $errors[2]="<br>*مقدار ضمانت نامه (".$resqueryguaranteepayval.")کافی نیست"; 
        else if ($resquerycorank==2 && ($resqueryguaranteepayval<$Permissionvalsp2Zemanat*10)) $errors[2]="<br>*مقدار ضمانت نامه (".$resqueryguaranteepayval.")کافی نیست";
        else if ($resquerycorank==3 && ($resqueryguaranteepayval<$Permissionvalsp3Zemanat*10)) $errors[2]="<br>*مقدار ضمانت نامه (".$resqueryguaranteepayval.")کافی نیست";
        else if ($resquerycorank==4 && ($resqueryguaranteepayval<$Permissionvalsp4Zemanat*10)) $errors[2]="<br>*مقدار ضمانت نامه (".$resqueryguaranteepayval.")کافی نیست";
        else if ($resquerycorank==5 && ($resqueryguaranteepayval<$Permissionvalsp5Zemanat*10)) $errors[2]="<br>*مقدار ضمانت نامه (".$resqueryguaranteepayval.")کافی نیست"; 
                            
                            
    if ($resquerycorank==1 && ($resqueryinvoicenotdeliveredcnt>=$Permissionvalsp1Zpishhamzaman))      $errors[3]="<br>*تعداد پیش فاکتور همزمان (".$resqueryinvoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==2 && ($resqueryinvoicenotdeliveredcnt>=$Permissionvalsp2Zpishhamzaman)) $errors[3]="<br>*تعداد پیش فاکتور همزمان (".$resqueryinvoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==3 && ($resqueryinvoicenotdeliveredcnt>=$Permissionvalsp3Zpishhamzaman)) $errors[3]="<br>*تعداد پیش فاکتور همزمان (".$resqueryinvoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==4 && ($resqueryinvoicenotdeliveredcnt>=$Permissionvalsp4Zpishhamzaman)) $errors[3]="<br>*تعداد پیش فاکتور همزمان (".$resqueryinvoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==5 && ($resqueryinvoicenotdeliveredcnt>=$Permissionvalsp5Zpishhamzaman)) $errors[3]="<br>*تعداد پیش فاکتور همزمان (".$resqueryinvoicenotdeliveredcnt.") بیشتر از حد مجاز است"; 
                       
    if ($resquerycorank==1 && ( ($resquerytonajval+$resqueryprojtonajval)>$Permissionvalsp1Zpishhamzamanvol ))      $errors[4]="<br>*تناز پیش فاکتور همزمان (".($resquerytonajval+$resqueryprojtonajval).") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==2 && (($resquerytonajval+$resqueryprojtonajval)>$Permissionvalsp2Zpishhamzamanvol)) $errors[4]="<br>*تناز پیش فاکتور همزمان (".($resquerytonajval+$resqueryprojtonajval).") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==3 && (($resquerytonajval+$resqueryprojtonajval)>$Permissionvalsp3Zpishhamzamanvol)) $errors[4]="<br>*تناز پیش فاکتور همزمان (".($resquerytonajval+$resqueryprojtonajval).") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==4 && (($resquerytonajval+$resqueryprojtonajval)>$Permissionvalsp4Zpishhamzamanvol)) $errors[4]="<br>*تناز پیش فاکتور همزمان (".($resquerytonajval+$resqueryprojtonajval).") بیشتر از حد مجاز است"; 
        else if ($resquerycorank==5 && (($resquerytonajval+$resqueryprojtonajval)>$Permissionvalsp5Zpishhamzamanvol)) $errors[4]="<br>*تناز پیش فاکتور همزمان (".($resquerytonajval+$resqueryprojtonajval).") بیشتر از حد مجاز است"; 
                       
                       
                                      
    if (compelete_date($resqueryboardvalidationdate)<$dateYmd)
        $errors[6]="<br>انقضاء تاریخ اعتبار هیئت مدیره $resqueryboardvalidationdate.";
    if (compelete_date($resquerycopermisionvalidate)<$dateYmd)
        $errors[7]="<br>انقضاء تاریخ مجوز شرکت $resquerycopermisionvalidate.";
   if (compelete_date($valueaddedvalidate)<$dateYmd)
        $errors[8]="<br>انقضاء تاریخ ارزش افزوده  $valueaddedvalidate .";
		
		
    return $errors;    
}
function gadfly($Disable,$login_RolesID)//غیر فعال
{
    
}
function selfws($size,$feshar)//تابع محاسبه خودیاری طرح های انتقال آب
{
                        if ($size==63 && $feshar==6) return 10000;
                        elseif ($size==90 && $feshar==4) return 7500;
                        else if ($size==90 && $feshar==6) return 15000;
                        else if ($size==110 && $feshar==4) return 15000;
                        else if ($size==110 && $feshar==6) return 20000;
                        else if ($size==125 && $feshar==4) return 15000;
                        else if ($size==125 && $feshar==6) return 25000;
                        else if ($size==160 && $feshar==4) return 25000;
                        else if ($size==160 && $feshar==6) return 35000;
                        else if ($size==200 && $feshar==4) return 40000;
                        else if ($size==200 && $feshar==6) return 55000;
                        else if ($size==250 && $feshar==4) return 55000;
                        else if ($size==250 && $feshar==6) return 85000;
                        else if ($size==315 && $feshar==4) return 85000;
                        else if ($size==315 && $feshar==6) return 130000;
                        else if ($size==160 && $feshar==8) return 45000;
}
function savewsvals ($ApplicantMasterID,$prjtypeid,$ApplicantstatesID)//ذخیره اطلاععات آبرسانی در جدول ارتباطی
{
    if ($prjtypeid==1 && $ApplicantMasterID>0)
        {
            $queryws="select max(invoicemaster.InvoiceMasterID) InvoiceMasterID,max(invoicemaster.ProducersID) ProducersID
                    ,ApplicantMasterID,max(invoicemaster.proposable) proposable,max(taxpercent.value) taxpercentvalue
                    ,sum(invoicedetail.Number) Number,max(gadget3.size11) size11
                    ,max(case Gadget2ID when 376 then 'PE100'
                    when 202 then 'PE80'
                    else '' end) material,max(fesharzekhamathajm) fesharzekhamathajm,round(sum(UnitsCoef2*invoicedetail.Number)) tonaj
                    ,sum(invoicemaster.tot) tot
                    from invoicemaster
                    inner join invoicedetail on invoicedetail.InvoiceMasterID=invoicemaster.InvoiceMasterID
                    inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid
                    inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.Gadget2ID in (376,202)
                    inner join year on year.Value=substring((invoicemaster.InvoiceDate),1,4)
                    inner join taxpercent on year.YearID=taxpercent.YearID
                    where ApplicantMasterID='$ApplicantMasterID'
                    group by ApplicantMasterID";
                    
                    //print $queryws;
                    //exit;
            $resultws = mysql_query($queryws);
  		    $rowws = mysql_fetch_assoc($resultws);
           
                   $query = "UPDATE applicantmasterdetail SET 
                   wsval='$rowws[InvoiceMasterID]_$rowws[ProducersID]_$rowws[ApplicantMasterID]_$rowws[proposable]_$rowws[taxpercentvalue]_$rowws[Number]_$rowws[size11]_$rowws[material]_$rowws[fesharzekhamathajm]_$rowws[tonaj]'
		                  WHERE ApplicantMasterID = '$ApplicantMasterID';";
                         // print $query;exit;
                mysql_query($query);
                
                if ($ApplicantstatesID==16)
                {
                    $selfws=$rowws['Number']*selfws($rowws['size11'],$rowws['fesharzekhamathajm']);
                    $tot=$rowws['tot'];
                    $query = "UPDATE applicantmaster SET 
                   selfcashhelpval='$selfws',belaavaz='$tot'
		                  WHERE ApplicantMasterID = '$ApplicantMasterID';";
                         // print $query;exit;
                mysql_query($query);
                }
        }
}


function chartpipe_sqlpa($istonaj)//تابع ایجاد نمودار لوله ها	 
{
	
$sql="SELECT 
ROUND(sum(gadget3.UnitsCoef2*invoicedetail.Number)/1000,1) tonaj,ROUND(sum(invoicemaster.tot)/1000000,1) tot,producers.title,
ifnull(invoicemaster.proposable,0) proposable
 from invoicemaster 
                        inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
                         inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid
                        inner join gadget3 on toolsmarks.gadget3id=gadget3.gadget3id and gadget3.gadget2id in (202,376,494,495)
                        inner join producers on producers.producersid=invoicemaster.producersid and PipeProducer=1  and ifnull(pricenotinrep,0)=0 and producers.producersid<>148
						
                        inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid
                        and ifnull(applicantmaster.applicantmasteridmaster,0)=0
						
group by producers.title,ifnull(invoicemaster.proposable,0)
order by producers.title,ifnull(invoicemaster.proposable,0)
";
    $result = mysql_query($sql);
	//print $sql;
   	while($row = mysql_fetch_assoc($result))
		{
		  $sump[trim($row['title'])][$row['proposable']]=$row['tot'];
		  $sumt[trim($row['title'])][$row['proposable']]=$row['tonaj'];
          
          
		}
    
    
    if ($istonaj==1)
        return $sumt;
    else return $sump;		
		
	}
function automated_propose_transfer ()//انتقال وضعیت پیشنهاد قیمت بعد از مدت و تعداد مشخص
{
    $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
        SaveDate = '" . date('Y-m-d') . "', 
        ClerkID = '25',
        proposestate=2,ADate2='".date('Y-m-d')."',ADate='".date('Y-m-d')."' 
        WHERE applicantmaster.ApplicantMasterID
        in (select applicantmasterid from (select applicantmaster.applicantmasterid  from applicantmaster
        inner join operatorapprequest on operatorapprequest.applicantmasterid=applicantmaster.applicantmasterid
        inner join (SELECT count(*) cnt,ApplicantMasterID FROM `operatorapprequest`group by ApplicantMasterID) proposecnt on proposecnt.ApplicantMasterID=applicantmaster.ApplicantMasterID
        inner join supervisorcoderrquirement supervisorcoderrquirementday on supervisorcoderrquirementday.ostan=19 and
        supervisorcoderrquirementday.KeyStr='elapseddayforautomatictransfer'
        inner join supervisorcoderrquirement supervisorcoderrquirementproosecnt on supervisorcoderrquirementproosecnt.ostan=19 and
        supervisorcoderrquirementproosecnt.KeyStr='proosecntforautomatictransfer'
        where applicantmaster.ApplicantMasterID not in (select ApplicantMasterID from operatorapprequest where state=1)
        and ifnull(applicantmaster.proposestate,0) in (0,1) and substring(applicantmaster.cityid,1,2)='19'
        and TIMESTAMPDIFF(Day,applicantmaster.ADate,substring(NOW(),1,10))>=supervisorcoderrquirementday.valueint
        and proposecnt.cnt>=supervisorcoderrquirementproosecnt.valueint)view1);";
        $result = mysql_query($query); 
}

function sqlticketkinds()//پرس و جوی انواع تیکت های ثبتی
{
    return "select 1 as _value,'ثبت لوازم' as _key 
                     union all SELECT 2 as _value,'بررسی مدارک شرکت' as _key
                     union all SELECT 5 as _value,'تغییر مشخصات طرح' as _key
                     union all SELECT 3 as _value,'درخواست ثبت نام' as _key
                     union all SELECT 4 as _value,'قرارداد' as _key
                     union all SELECT 6 as _value,'سایر' as _key";
}

function readfromexcel($filename,$ApplicantMasterID,$OperatorCoID,$DesignerCoID,$InvoiceMasterID,$PriceListMasterID,$masterProducersID,$userid
,$appfoundationID,$fehrestsfaslsID,$type,$fehrestsmasterID)
{
    $coID="";
    if ($OperatorCoID>0)
        $coID=$OperatorCoID;
    else if ($DesignerCoID>0)
        $coID=$DesignerCoID;
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    
    date_default_timezone_set('Europe/London');
    
    /** PHPExcel_IOFactory */
    require_once '../class/PHPExcel/IOFactory.php';
    $ext = end((explode(".", $filename)));
    if ($ext=='xls')
        $inputFileType = 'Excel5';
    else  if ($ext=='xlsx')   
	   $inputFileType = 'Excel2007';
    $Tools=array();
    $Toolscnt=0;
    $master=array();
    $mastercnt=0;
    
    $masterf=array();
   	$mastercntf=0;
    
    $masterman=array();
   	$mastercntman=0;
    
    
    $mastermana=array();
   	$mastercntmana=0;
           
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcelr = $objReader->load($filename);
    foreach ($objPHPExcelr->getWorksheetIterator() as $worksheet) 
    {
    	//echo 'Worksheet - ' , $worksheet->getTitle() , EOL;
        if ($worksheet->getTitle()=='IM' || ($type=='IM' && $worksheet->getTitle()!='f'  && $worksheet->getTitle()!='man' && $worksheet->getTitle()!='mana'))//بارگذاری لوازم
        {
            foreach ($worksheet->getRowIterator() as $row) 
        	{
        	   if ($row->getRowIndex()==1) continue;
        		//echo '    Row number - ' , $row->getRowIndex() , EOL;
                
        		$cellIterator = $row->getCellIterator();
        		$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $tempcnt=0;
                $Temparr=array();
        		foreach ($cellIterator as $cell) 
        		{
        		      
        			if (!is_null($cell)) 
                    {
                        $Temparr[$tempcnt++]=$cell->getCalculatedValue();
        				//echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , EOL;
        			}
        		}
                if ($Temparr[15]>0)//maser
                {
                    $mastercnt++;
                    $master[$mastercnt][0]=$Temparr[0];
                    $master[$mastercnt][1]=$Temparr[1];
                    $master[$mastercnt][2]=$Temparr[2];
                    $master[$mastercnt][3]=$Temparr[3];
                    $master[$mastercnt][4]=$Temparr[4];
                    $master[$mastercnt][5]=$Temparr[5];
                    $master[$mastercnt][6]=$Temparr[6];
                    $master[$mastercnt][7]=$Temparr[7];
                    $master[$mastercnt][8]=$Temparr[8];
                    $master[$mastercnt][9]=$Temparr[9];
                    $master[$mastercnt][10]=$Temparr[10];
                    $master[$mastercnt][11]=$Temparr[11];
                    $master[$mastercnt][12]=$Temparr[12];
                    $master[$mastercnt][13]=$Temparr[13];
                    $master[$mastercnt][14]=$Temparr[14];
                    $master[$mastercnt][15]=$Temparr[15];
                    
                }
                else//detail
                {
                    $Toolscnt++;
                    $Tools[$Toolscnt][0]=$Temparr[0];
                    $Tools[$Toolscnt][1]=$Temparr[1];
                    $Tools[$Toolscnt][2]=$Temparr[2];
                    $Tools[$Toolscnt][3]=$Temparr[3];
                }
        	}
            
        }
        
        if ($worksheet->getTitle()=='f' || ($type=='f' && $worksheet->getTitle()!='IM'  && $worksheet->getTitle()!='man' && $worksheet->getTitle()!='mana'))//سازه
        {
            foreach ($worksheet->getRowIterator() as $row) 
        	{
        	    
        		$cellIterator = $row->getCellIterator();
        		$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $tempcntf=0;
                $Temparrf=array();
        		foreach ($cellIterator as $cell) 
        		{
        		      
        			if (!is_null($cell)) 
                    {
                        $Temparrf[$tempcntf++]=$cell->getCalculatedValue();
        				//echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , EOL;
        			}
        		}
                    $mastercntf++;
                    $masterf[$mastercntf][0]=$Temparrf[0];
                    $masterf[$mastercntf][1]=$Temparrf[1];
                    $masterf[$mastercntf][2]=$Temparrf[2];
                    $masterf[$mastercntf][3]=$Temparrf[3];
                    $masterf[$mastercntf][4]=$Temparrf[4];
                    $masterf[$mastercntf][5]=$Temparrf[5];
                    $masterf[$mastercntf][6]=$Temparrf[6];
                    $masterf[$mastercntf][7]=$Temparrf[7];
                    $masterf[$mastercntf][8]=$Temparrf[8];   
        	}    
        }
        
        
        if ($worksheet->getTitle()=='man' || ($type=='man' && $worksheet->getTitle()!='IM'  && $worksheet->getTitle()!='f' && $worksheet->getTitle()!='mana') )//فهرست بها
        {
            foreach ($worksheet->getRowIterator() as $row) 
        	{
        	    if ($row->getRowIndex()==1) continue;
        		$cellIterator = $row->getCellIterator();
        		$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $tempcntf=0;
                $Temparrf=array();
        		foreach ($cellIterator as $cell) 
        		{
        		      
        			if (!is_null($cell)) 
                    {
                        $Temparrf[$tempcntf++]=$cell->getCalculatedValue();
        				//echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , EOL;
        			}
        		}
                    $mastercntman++;
                    $masterman[$mastercntman][0]=$Temparrf[0];
                    $masterman[$mastercntman][1]=$Temparrf[1];
                    $masterman[$mastercntman][2]=$Temparrf[2];
                    $masterman[$mastercntman][3]=$Temparrf[3];
                    $masterman[$mastercntman][4]=$Temparrf[4];
                    $masterman[$mastercntman][5]=$Temparrf[5];
                    $masterman[$mastercntman][6]=$Temparrf[6];
                    $masterman[$mastercntman][7]=$Temparrf[7];
                    $masterman[$mastercntman][8]=$Temparrf[8]; 
                    $masterman[$mastercntman][9]=$Temparrf[9];
                    $masterman[$mastercntman][10]=$Temparrf[10];
                    $masterman[$mastercntman][11]=$Temparrf[11];
                    $masterman[$mastercntman][12]=$Temparrf[12];
                    $masterman[$mastercntman][13]=$Temparrf[13];  
        	}    
        }
        
        if ($worksheet->getTitle()=='mana' || ($type=='mana' && $worksheet->getTitle()!='IM'  && $worksheet->getTitle()!='f' && $worksheet->getTitle()!='man') )//فهارس بها
        {
            foreach ($worksheet->getRowIterator() as $row) 
        	{
        	    if ($row->getRowIndex()==1) continue;
        		$cellIterator = $row->getCellIterator();
        		$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                $tempcntf=0;
                $Temparrf=array();
        		foreach ($cellIterator as $cell) 
        		{
        		      
        			if (!is_null($cell)) 
                    {
                        $Temparrf[$tempcntf++]=$cell->getCalculatedValue();
        				//echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , EOL;
        			}
        		}
                    $mastercntmana++;
                    $mastermana[$mastercntmana][0]=$Temparrf[0];
                    $mastermana[$mastercntmana][1]=$Temparrf[1];
                    $mastermana[$mastercntmana][2]=$Temparrf[2];
                    $mastermana[$mastercntmana][3]=$Temparrf[3];
                    $mastermana[$mastercntmana][4]=$Temparrf[4];
                    $mastermana[$mastercntmana][5]=$Temparrf[5];
                    $mastermana[$mastercntmana][6]=$Temparrf[6];
                    $mastermana[$mastercntmana][7]=$Temparrf[7];
                    $mastermana[$mastercntmana][8]=$Temparrf[8]; 
                    $mastermana[$mastercntmana][9]=$Temparrf[9]; 
                    $mastermana[$mastercntmana][10]=$Temparrf[10];
        	}    
        }
        
    }
    if ($mastercntf>1)
    {
        echo $masterf[1][8];        
         if ($coID!=substr($masterf[1][8],10,strlen($masterf[1][8])-20))
            exit;
        for($row=1;$row<=$mastercntf;$row++)
        {
            try 
            {		
                $query = "insert into appfoundation (ApplicantMasterID,Title,groupcode,len,width,heigh,thickness,number,SaveTime,SaveDate,ClerkID)
                values('$ApplicantMasterID',
                '".$masterf[$row][1]."',
                '".$masterf[$row][2]."',
                '".$masterf[$row][3]."',
                '".$masterf[$row][4]."',
                '".$masterf[$row][5]."',
                '".$masterf[$row][6]."',
                '".$masterf[$row][7]."',
                '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$userid."');";
                 mysql_query($query);
                 //print $query;
                $query = "SELECT appfoundationID FROM appfoundation where appfoundationID = last_insert_id() and ApplicantMasterID='$ApplicantMasterID'";
                $result = mysql_query($query);
        		$rowq = mysql_fetch_assoc($result);
                $last_insert_appfoundationID=$rowq['appfoundationID'];
                $masterf[$row][9]=$last_insert_appfoundationID;
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
        }    
    }
    
    //echo $mastercntman."sa";exit;
    for($r=1;$r<=$mastercntman;$r++)
    {
        $appfoundationIDtoinset=$appfoundationID;
        $fehrestsfaslsIDtoinset=$fehrestsfaslsID;
        if ($masterman[$r][12]>0)
            $fehrestsfaslsIDtoinset=$masterman[$r][12];
            for($j=1;$j<=$mastercntf;$j++)
            {
                if ($masterman[$r][11]==$masterf[$j][0])
                    $appfoundationIDtoinset=$masterf[$j][9];
              // echo "$r ".$appfoundationIDtoinset."sa  ".$masterman[$r][12]." as ".$masterf[$j][0]."<br>";         
            }
            //echo "  ".$masterman[$r][1];continue;
            
                try 
                {	
                    $query = "
					  INSERT INTO manuallistprice(ApplicantMasterID,appfoundationID
                      ,AddOrSub,Code,Title,Unit,Number2,Number3,Number4,Number5,Number6,Price
                      
					  ,Description,fehrestsfaslsID,SaveTime,SaveDate,ClerkID,Number,nval1,nval2,nval3,pval1,pval2,pval3) 
					  VALUES('$ApplicantMasterID','$appfoundationIDtoinset','" .
					  $masterman[$r][0] . "', '" . 
					  $masterman[$r][1] . "', '" . 
					  $masterman[$r][2] . "', '" . 
					  $masterman[$r][3] . "', '" .
					  $masterman[$r][4] . "', '" .
					  $masterman[$r][5] . "', '" .
					  $masterman[$r][6] . "', '" . 
					  $masterman[$r][7] . "', '" .
					  $masterman[$r][8] . "', '" .
					  $masterman[$r][9] . "', '" .
					  $masterman[$r][10] . "',  '$fehrestsfaslsIDtoinset', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$userid."', '" .
					  $masterman[$r][13] . "', '" .
					  $masterman[$r][14] . "', '" .
					  $masterman[$r][15] . "', '" .
					  $masterman[$r][16] . "', '" .
					  $masterman[$r][17] . "', '" .
					  $masterman[$r][18] . "', '" .
					  $masterman[$r][19] . "' );";
                    //print $query;exit;
                     mysql_query($query);
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                }    
            
    }

    for($r=1;$r<=$mastercntmana;$r++)
    {
        $appfoundationIDtoinset=$appfoundationID;
        $fehrestsfaslsIDtoinset=$fehrestsfaslsID;
            for($j=1;$j<=$mastercntf;$j++)
            {
                if ($mastermana[$r][8]==$masterf[$j][0])
                    $appfoundationIDtoinset=$masterf[$j][9];         
            }
            if ($mastermana[$r][0]>0)//جستجوی 
            {
                $qr="select fehrestsID from fehrests where fehrestsmasterID='$fehrestsmasterID' and (fehrests.Code='".$mastermana[$r][0]."'
                or fehrests.Code='0".$mastermana[$r][0]."')";
                
                
                $result = mysql_query($qr);  
                $rowqr = mysql_fetch_assoc($result);
                $fehrestsID=$rowqr['fehrestsID'];
            }
            else $fehrestsID=$mastermana[$r][9];
            
                try 
                {	
                    if ($mastermana[$r][2]>0) $Num2=$mastermana[$r][2]; else $Num2=1;// تعداد
			if ($mastermana[$r][3]>0) $Num3=$mastermana[$r][3]; else $Num3=1;
			if ($mastermana[$r][4]>0) $Num4=$mastermana[$r][4]; else $Num4=1;
			if ($mastermana[$r][5]>0) $Num5=$mastermana[$r][5]; else $Num5=1;
			if ($mastermana[$r][6]!=0 || $mastermana[$r][6]!='') $Num6=$mastermana[$r][6]; else $Num6=1;
			
			if ($mastermana[$r][3]>0 || $mastermana[$r][4]>0 || $mastermana[$r][5]>0 || $mastermana[$r][6])
			$Number=$Num3*$Num4*$Num5*$Num6;
		  
            if ($mastermana[$r][1]>0 && $mastermana[$r][1]<>1)
                $Number=$mastermana[$r][1];
            
                    $query = "
					  INSERT INTO manuallistpriceall(ApplicantMasterID,appfoundationID,fehrestsID,Number,Number2,Number3,Number4,Number5,Number6
                      ,Description,Price,SaveTime,SaveDate,ClerkID,nval1,nval2,nval3,pval1,pval2,pval3) 
					  VALUES('$ApplicantMasterID','$appfoundationIDtoinset','$fehrestsID','$Number','" .
					  $mastermana[$r][2] . "', '" . 
					  $mastermana[$r][3] . "', '" .
					  $mastermana[$r][4] . "', '" .
					  $mastermana[$r][5] . "', '" .
					  $mastermana[$r][6] . "', '" . 
					  $mastermana[$r][7] . "',  '" . 
					  $mastermana[$r][10] . "', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$userid."', '" . 
					  $mastermana[$r][11] . "', '" . 
					  $mastermana[$r][12] . "', '" . 
					  $mastermana[$r][13] . "', '" . 
					  $mastermana[$r][14] . "', '" . 
					  $mastermana[$r][15] . "', '" . 
					  $mastermana[$r][16] . "');";
                    //print $query;exit;
                     mysql_query($query);
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                }    
            
    }    
    
    if ($mastercnt>0)
    {
        if ($coID!=substr($master[1][15],10,strlen($master[1][15])-20))
            exit;
        
        for($row=1;$row<=$mastercnt;$row++)
        {
            try 
            {		
                $query = "insert into invoicemaster (ApplicantMasterID,appsubprjID,ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep,costnotinrep,taxless
                ,PriceListMasterID,InvoiceMasterIDmaster,SaveTime,SaveDate,ClerkID)
                values('$ApplicantMasterID',
                '".$master[$row][1]."',
                '".$master[$row][2]."',
                '".$master[$row][3]."',
                '".$master[$row][4]."',
                '".$master[$row][5]."',
                '".$master[$row][6]."',
                '".$master[$row][7]."',
                '".$master[$row][8]."',
                '".$master[$row][9]."',
                '".$master[$row][10]."',
                '".$master[$row][11]."',
                '".$master[$row][12]."',
                '".$master[$row][13]."',
                '".$master[$row][14]."',
                '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$userid."');";
                 mysql_query($query);
                 
                $query = "SELECT InvoiceMasterID FROM invoicemaster where InvoiceMasterID = last_insert_id() and ApplicantMasterID='$ApplicantMasterID'";
                $result = mysql_query($query);
        		$rowq = mysql_fetch_assoc($result);
                $last_insert_InvoiceMasterID=$rowq['InvoiceMasterID'];
                $master[$row][16]=$last_insert_InvoiceMasterID;
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
        }  
    }
    //echo $Toolscnt;exit;
    for($r=1;$r<=$Toolscnt;$r++)
    {
        $InvoiceMasterIDtoinset=$InvoiceMasterID;
        $PriceListMasterIDtoinset=$PriceListMasterID;
        $masterProducersIDtoinset=$masterProducersID;
        $hidecond="and ifnull(pricelistdetail.hide,0)=0";
        for($j=1;$j<=$mastercnt;$j++)
        {
            //echo $Tools[$r][2]." as ".$master[$j][0]." as ".$master[$j][16]."<br>";
            if ($Tools[$r][2]==$master[$j][0])
            {
                $InvoiceMasterIDtoinset=$master[$j][16];
                $PriceListMasterIDtoinset=$master[$j][13];
                $masterProducersIDtoinset=$master[$j][2];
                $hidecond="";
            }
                
                       
        }
            //echo "$r ".$InvoiceMasterIDtoinset."sa  ".$Tools[$r][2]." as ".$Tools[$r][2]."<br>"; continue;
            if ($InvoiceMasterIDtoinset>0)
            {
                try 
                {		
                    $query = "insert into invoicedetail (InvoiceMasterID,ToolsMarksID,Number,deactive,SaveTime,SaveDate,ClerkID)
                    select '$InvoiceMasterIDtoinset',
                    '".$Tools[$r][0]."',
                    '".$Tools[$r][1]."',
                    '".$Tools[$r][3]."',
                    '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$userid."' from toolsmarks    
                    inner join producers on producers.ProducersID='$masterProducersIDtoinset'
                    left outer join pricelistdetail on pricelistdetail.PriceListMasterID='$PriceListMasterIDtoinset'
                    and pricelistdetail.toolsmarksid=toolsmarks.toolsmarksid  $hidecond
                    
                    where toolsmarks.toolsmarksid='".$Tools[$r][0]."' and 
                    
                    case '$masterProducersIDtoinset'<>135 when 1 then
                    toolsmarks.ProducersID='$masterProducersIDtoinset'
                    else 1 end
                    and
                    case producers.PipeProducer=1 when 1 then 1 else ifnull(pricelistdetail.price,0)>0 end
                    ;";
                    //print $query."<br>";
                     mysql_query($query);
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                }    
            }
    }
    //exit;
    
}
function writetoexcel($ApplicantMasterID)
{
    
    $query="select case DesignerCoID>0 when 1 then DesignerCoID else operatorcoid end CoID,InvoiceMasterID,appsubprjID,ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep,costnotinrep,taxless
            ,invoicemaster.PriceListMasterID,InvoiceMasterIDmaster from invoicemaster
            inner join applicantmaster on applicantmaster.ApplicantMasterID=invoicemaster.ApplicantMasterID 
            and invoicemaster.ApplicantMasterID ='$ApplicantMasterID' and ifnull(invoicemaster.proposable,0)<>1";
    $result_invoicemaster = mysql_query($query); 

    $query="select invoicedetail.InvoiceMasterID,invoicedetail.ToolsMarksID,invoicedetail.Number,invoicedetail.deactive from invoicedetail
    inner join invoicemaster on invoicemaster.InvoiceMasterID=invoicedetail.InvoiceMasterID and invoicemaster.ApplicantMasterID ='$ApplicantMasterID'
    and ifnull(invoicemaster.proposable,0)<>1
    ";
    $result_invoicedetail = mysql_query($query); 

    $query="select appfoundationID,Title,groupcode,len,width,heigh,thickness,number from appfoundation where appfoundationID 
    in (select appfoundationID from manuallistprice where ApplicantMasterID ='$ApplicantMasterID'
    union all select appfoundationID from manuallistpriceall where ApplicantMasterID ='$ApplicantMasterID')";
    $result_appfoundation = mysql_query($query); 

    $query="select appfoundationID,`fehrestsfaslsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, 
    `Description`, `CostsGroupsID`, `AddOrSub`, `Code`, `Title`, `Unit`, `nval1`, `nval2`, `nval3`, `pval1`, `pval2`, `pval3`
            from manuallistprice where ApplicantMasterID ='$ApplicantMasterID'";
    $result_manuallistprice = mysql_query($query); 


    $query="select appfoundationID , `fehrestsID`,Number,Number2,Number3,Number4,Number5,Number6, `Price`, `nval1`, 
            `nval2`, `nval3`, `pval1`, `pval2`, `pval3`
            from manuallistpriceall where ApplicantMasterID ='$ApplicantMasterID'";
    $result_manuallistpriceall = mysql_query($query); 


    date_default_timezone_set('Europe/London');
    
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    
    /** Include PHPExcel */
    require_once '../class/PHPExcel/IOFactory.php';
    
    
    // Create new PHPExcel object
    echo date('H:i:s') , " Create new PHPExcel object" , EOL;
    $objPHPExcel = new PHPExcel();
    
    // Set document properties
    echo date('H:i:s') , " Set document properties" , EOL;
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    							 ->setLastModifiedBy("Maarten Balliauw")
    							 ->setTitle("PHPExcel Test Document")
    							 ->setSubject("PHPExcel Test Document")
    							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
    							 ->setKeywords("office PHPExcel php")
    							 ->setCategory("Test result file");

    $rowcnt=1;
     $objPHPExcel->setActiveSheetIndex()
                ->setCellValue('A'.$rowcnt, "کد کالا")
                ->setCellValue('B'.$rowcnt, "تعداد")
                ->setCellValue('C'.$rowcnt, "1شناسه")
                ->setCellValue('D'.$rowcnt, "شناسه 2");
    $rowcnt++; 
    while($row = mysql_fetch_assoc($result_invoicedetail))
    {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowcnt, $row['ToolsMarksID'])
                ->setCellValue('B'.$rowcnt, $row['Number'])
                ->setCellValue('C'.$rowcnt, $row['InvoiceMasterID'])
                ->setCellValue('D'.$rowcnt, $row['deactive']);
        $rowcnt++;
        
        
    }
    while($row = mysql_fetch_assoc($result_invoicemaster))
    {
        $CoID=$row['CoID'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowcnt, $row['InvoiceMasterID'])
                ->setCellValue('B'.$rowcnt, $row['appsubprjID'])
                ->setCellValue('C'.$rowcnt, $row['ProducersID'])
                ->setCellValue('D'.$rowcnt, $row['Serial'])
                ->setCellValue('E'.$rowcnt, $row['Title'])
                ->setCellValue('F'.$rowcnt, $row['Description'])
                ->setCellValue('G'.$rowcnt, $row['TransportCost'])
                ->setCellValue('H'.$rowcnt, $row['Discont'])
                ->setCellValue('I'.$rowcnt, $row['InvoiceDate'])
                ->setCellValue('J'.$rowcnt, $row['Rowcnt'])
                ->setCellValue('K'.$rowcnt, $row['pricenotinrep'])
                ->setCellValue('L'.$rowcnt, $row['costnotinrep'])
                ->setCellValue('M'.$rowcnt, $row['taxless'])
                ->setCellValue('N'.$rowcnt, $row['PriceListMasterID'])
                ->setCellValue('O'.$rowcnt, $row['InvoiceMasterIDmaster'])
                ->setCellValue('P'.$rowcnt, rand(10000,99999).rand(10000,99999).$CoID.rand(10000,99999).rand(10000,99999));
        $rowcnt++;
    }


    // Rename worksheet
    echo date('H:i:s') , " Rename worksheet" , EOL;
        $objPHPExcel->getActiveSheet()->setTitle('IM');
    
    ////////////////////////////////////////////////////////////////////////////////
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $rowcnt=1;
    while($row = mysql_fetch_assoc($result_appfoundation))
    {
        $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A'.$rowcnt, $row['appfoundationID'])
                ->setCellValue('B'.$rowcnt, $row['Title'])
                ->setCellValue('C'.$rowcnt, $row['groupcode'])
                ->setCellValue('D'.$rowcnt, $row['len'])
                ->setCellValue('E'.$rowcnt, $row['width'])
                ->setCellValue('F'.$rowcnt, $row['heigh'])
                ->setCellValue('G'.$rowcnt, $row['thickness'])
                ->setCellValue('H'.$rowcnt, $row['number'])
                ->setCellValue('I'.$rowcnt, rand(10000,99999).rand(10000,99999).$CoID.rand(10000,99999).rand(10000,99999));
                
                
        $rowcnt++;
    }    
    $objPHPExcel->getActiveSheet()->setTitle('f');

    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(2);
    $rowcnt=1;
    $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('A'.$rowcnt, "کسربها/اضافه بها/ستاره دار")
                ->setCellValue('B'.$rowcnt, "کد مرتبط̽فهرست بها")
                ->setCellValue('C'.$rowcnt, "عنوان")
                ->setCellValue('D'.$rowcnt, "واحد")
                ->setCellValue('F'.$rowcnt, "طول")
                ->setCellValue('G'.$rowcnt, "عرض")
                ->setCellValue('H'.$rowcnt, "ضخامت")
                ->setCellValue('I'.$rowcnt, "وزن")
                ->setCellValue('J'.$rowcnt, "ضریب")
                ->setCellValue('K'.$rowcnt, "فی ")
                ->setCellValue('L'.$rowcnt, "توضیحات")
                ->setCellValue('M'.$rowcnt, "شناسه 1")
                ->setCellValue('N'.$rowcnt, "شناسه 2")
                ->setCellValue('E'.$rowcnt, "تعداد");
    $rowcnt++;
    while($row = mysql_fetch_assoc($result_manuallistprice))
    {
        $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('A'.$rowcnt, $row['AddOrSub'])
                ->setCellValue('B'.$rowcnt, $row['Code'])
                ->setCellValue('C'.$rowcnt, $row['Title'])
                ->setCellValue('D'.$rowcnt, $row['Unit'])
                ->setCellValue('E'.$rowcnt, $row['Number2'])
                ->setCellValue('F'.$rowcnt, $row['Number3'])
                ->setCellValue('G'.$rowcnt, $row['Number4'])
                ->setCellValue('H'.$rowcnt, $row['Number5'])
                ->setCellValue('I'.$rowcnt, $row['Number6'])
                ->setCellValue('J'.$rowcnt, $row['Price'])
                ->setCellValue('K'.$rowcnt, $row['Description'])
                ->setCellValue('L'.$rowcnt, $row['appfoundationID'])
                ->setCellValue('M'.$rowcnt, $row['fehrestsfaslsID'])
                ->setCellValue('N'.$rowcnt, $row['Number'])
                ->setCellValue('O'.$rowcnt, $row['nval1'])
                ->setCellValue('P'.$rowcnt, $row['nval2'])
                ->setCellValue('Q'.$rowcnt, $row['nval3'])
                ->setCellValue('R'.$rowcnt, $row['pval1'])
                ->setCellValue('S'.$rowcnt, $row['pval2'])
                ->setCellValue('T'.$rowcnt, $row['pval3']);
        $rowcnt++;
    }    
    $objPHPExcel->getActiveSheet()->setTitle('man');

    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(3);
    $rowcnt=1;
        $objPHPExcel->setActiveSheetIndex(3)
                ->setCellValue('A'.$rowcnt, "کد فهرست بها")
                ->setCellValue('B'.$rowcnt, "تعداد")
                ->setCellValue('C'.$rowcnt, "طول")
                ->setCellValue('D'.$rowcnt, "عرض")
                ->setCellValue('E'.$rowcnt, "ضخامت")
                ->setCellValue('F'.$rowcnt, "وزن")
                ->setCellValue('G'.$rowcnt, "ضریب")
                ->setCellValue('H'.$rowcnt, "شرح")
                ->setCellValue('I'.$rowcnt, "شناسه 1")
                ->setCellValue('J'.$rowcnt, "شناسه 2")
                ->setCellValue('K'.$rowcnt, "شناسه 3");
        $rowcnt++;
    while($row = mysql_fetch_assoc($result_manuallistpriceall))
    {
        $objPHPExcel->setActiveSheetIndex(3)
                ->setCellValue('A'.$rowcnt, "0")
                ->setCellValue('B'.$rowcnt, $row['Number'])
                ->setCellValue('C'.$rowcnt, $row['Number2'])
                ->setCellValue('D'.$rowcnt, $row['Number3'])
                ->setCellValue('E'.$rowcnt, $row['Number4'])
                ->setCellValue('F'.$rowcnt, $row['Number5'])
                ->setCellValue('G'.$rowcnt, $row['Number6'])
                ->setCellValue('H'.$rowcnt, $row['Description'])
                ->setCellValue('I'.$rowcnt, $row['appfoundationID'])
                ->setCellValue('J'.$rowcnt, $row['fehrestsID'])
                ->setCellValue('K'.$rowcnt, $row['Price'])
                ->setCellValue('L'.$rowcnt, $row['nval1'])
                ->setCellValue('M'.$rowcnt, $row['nval2'])
                ->setCellValue('N'.$rowcnt, $row['nval3'])
                ->setCellValue('O'.$rowcnt, $row['pval1'])
                ->setCellValue('P'.$rowcnt, $row['pval2'])
                ->setCellValue('Q'.$rowcnt, $row['pval3']);
        $rowcnt++;
    }    
    $objPHPExcel->getActiveSheet()->setTitle('mana');


    ////////////////////////////////////////////////////////////////////////////////


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);


    // Save Excel 2007 file
    echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $callStartTime = microtime(true);
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save("../temp/f$ApplicantMasterID.xlsx");
    $callEndTime = microtime(true);
    $callTime = $callEndTime - $callStartTime;

   
}    
?>
