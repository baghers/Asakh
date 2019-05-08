<?php 

/*

//appinvestigation/guarantee_level1_jr.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/contractfree_list.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
  function div($a,$b) {
	return (int) ($a / $b);
}
function compelete_date($dat)///تابع کامل کردن ده رقمی تاریخ
{
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
        
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        
        
    return $j_y.'/'.$j_m.'/'.$j_d ;
    
}
function jalali_to_gregorian($dat)//تاریخ تبدیل شمسی به میلادی
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
  
  $dy[1]='í˜ÔäÈå';       
  $dy[2]='ÏæÔäÈå';        
  $dy[3]='Óå ÔäÈå';       
  $dy[4]='åÇÑ ÔäÈå';    
  $dy[5]='äÌ ÔäÈå';     
  $dy[6]='ÌãÚå';          
  $dy[7]='ÔäÈå';              
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

  
	function get_key_value_from_query_into_array($query)//تابع تبدیل پرس و جو به کلید و مقدار
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
  
    $type=$_POST['type'];
    $ID=$_POST['ID'];
    $Price=str_replace(',', '', $_POST['Price']);
    
    if ($type==1)//ÝÑæÔäÏÇä
    {
        $sql = "SELECT '$type' type,ProducersID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeExpireDate,guaranteeDescription,AccountNo,AccountBank FROM producers 
                where ProducersID='$ID'";
    }
    else if ($type==2)//ÔÑ˜Ê åÇí ãÌÑí
    {
        $sql = "SELECT '$type' type,operatorcoID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeExpireDate,guaranteeDescription,AccountNo,AccountBank FROM operatorco 
                where operatorcoID='$ID'";
    }
    /*else if ($type==3)//˜ÔÇæÑÒÇä
    {
        $sql = "SELECT '$type' type,ApplicantMasterID ID,concat(ApplicantFName,' ',ApplicantName,' - ',ltrim(cast(DesignArea as char)),' å˜ÊÇÑ ',' - ÔåÑÓÊÇä ',shahr.CityName)  Title,
                guaranteeUp,guaranteepayval,guaranteeNo,guaranteeExpireDate,guaranteeDescription,'' AccountNo,'' AccountBank FROM applicantmaster 
                left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
                and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
                where ApplicantMasterID='$ID' ";
    }*/
    $result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $resquery = mysql_fetch_assoc($result);
	$type= $resquery["type"];
	$ID = $resquery["ID"];
	$Title = $resquery["Title"];
	$guaranteeUp = $resquery["guaranteeUp"];
	$guaranteepayval = $resquery["guaranteepayval"];
	$guaranteeNo = $resquery["guaranteeNo"];
	$guaranteeExpireDate = $resquery["guaranteeExpireDate"];
	$guaranteeExpireDate = $resquery["guaranteeDescription"];
    
	$AccountBank = $resquery["AccountBank"];
	$AccountNo = $resquery["AccountNo"];  
    
    
     $errors="";
     
     $date = new DateTime(date('Y-m-d'));
     $date->modify('+10 day');                   
           
            
     if(jalali_to_gregorian($guaranteeExpireDate)<$date->format('Y-m-d'))
      $errors="4";//
     
     if ($guaranteepayval<$guaranteeUp && $Price>$guaranteepayval)
            $errors="3";
     
     if(jalali_to_gregorian($guaranteeExpireDate)<date('Y-m-d'))
        $errors="2";//
        
     if($guaranteeExpireDate=="")
        $errors="1";//  
     
    $temp_array = array('AccountNo' => $AccountNo,'AccountBank' => $AccountBank,'errors' => $errors,'guaranteepayval' => number_format($guaranteepayval));    
	echo json_encode($temp_array);
	exit();
    			
	
   
   
   
			
			
		
	

?>



