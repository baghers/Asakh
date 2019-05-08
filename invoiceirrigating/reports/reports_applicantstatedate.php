<?php 
/*
reorts/reports_applicantstatedate.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');
require ('../includes/functiong.php');



$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);  

$showa=0;

if ($_POST['showa']=='on')
    $showa=1;
if ($login_RolesID=='17') 
    $str=" and substring(applicantmasterall.cityid,1,4)=substring('$login_CityId',1,4) and applicantstates.applicantstatesID<>34 ";
    
if (strlen(trim($_POST['ApplicantName']))>0)
    $str.=" and applicantmasterall.ApplicantName like '%$_POST[ApplicantName]%'";
if (strlen(trim($_POST['DesignArea']))>0)
    $str.=" and applicantmasterall.DesignArea='$_POST[DesignArea]'";
if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)
    $str.=" and designsystemgroups.title='$_POST[DesignSystemGroupstitle]'";
if (strlen(trim($_POST['shahrcityname']))>0)
    $str.=" and shahr.cityname='$_POST[shahrcityname]'";	   
if (strlen(trim($_POST['operatorcotitle']))>0)
    $str.=" and operatorco.title like '%$_POST[operatorcotitle]%'";   	   
if (strlen(trim($_POST['applicantstatestitle']))>0)
    $str.=" and applicantstates.title='$_POST[applicantstatestitle]'";  
if (strlen(trim($_POST['DesignerCoIDnazerTitle']))>0)
    $str.=" and DesignerCoIDnazer.Title='$_POST[DesignerCoIDnazerTitle]'";   
if ($_POST['ClerkIDwin']>0)
    $str.=" and clerkwin.ClerkID='$_POST[ClerkIDwin]'"; 

if (strlen(trim($_POST['creditsourcetitle']))>0)
    $str.=" and creditsource.title like '%$_POST[creditsourcetitle]%'"; 
    
//print $str;
//$str.=" and 1=1 ";

$orderby=" order by applicantstates.title";
  
$sql=sql_reports_applicantstatedate($login_CityId,$str,$orderby).$login_limited;                 

try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }                


while ($row2 = mysql_fetch_array($result, MYSQL_NUM)) 
//while ($row2 = mysql_fetch_assoc($result))
{
    /*
    print '<br>0='.$row2[0].'<br>1='.$row2[1].'<br>2='.$row2[2].'<br>3='.$row2[3].'<br>4='.$row2[4].'<br>5='.$row2[5]
    .'<br>6='.$row2[6]
    .'<br>7='.$row2[7]
    .'<br>8='.$row2[8]
    .'<br>9='.$row2[9]
    .'<br>10='.$row2[10]
    .'<br>11='.$row2[11]
    .'<br>12='.$row2[12]
    .'<br>13='.$row2[13]
    .'<br>14='.$row2[14]
    .'<br>15='.$row2[15]
    .'<br>32='.$row2[32]
    
    .'<br>';*/
    //exit;
    $dvs=calculatedv($row2[14],$row2[15],$row2[4],$row2[6],
                        $row2[8],$row2[12],$row2[16],
                        $Permissionvals['deadlineerj'],$Permissionvals['deadlineselectop'],$Permissionvals['deadlinefirstsave'],
                        $Permissionvals['deadlineapprove'],$Permissionvals['deadlinetempdel'],$Permissionvals['deadlinepermanentdel']);                    
    $numfield2array = explode('_',$dvs);
   
    
    $dv1=$numfield2array[0];
    $dv2=$numfield2array[1];
    $dv3=$numfield2array[2];
    $dv4=$numfield2array[3];
    $dv5=$numfield2array[4];
    $dv6=$numfield2array[5];
    $temporarydel=$numfield2array[6];
    $permanentfree=$numfield2array[7];
     
    $all_data[] = array_merge($row2,array($dv1,$dv2,$dv3,$dv4,$dv5,$dv6,$temporarydel,$permanentfree));
     
}

  switch ($_POST['IDorder']) 
  {
    case 1: usort($all_data, function ($a, $b){if($a[26]==$b[26]) return 0;return $a[26] < $b[26]?1:-1;}); break; 
    case 2: usort($all_data, function ($a, $b){if($a[27]==$b[27]) return 0;return $a[27] < $b[27]?1:-1;}); break;
    case 3: usort($all_data, function ($a, $b){if($a[28]==$b[28]) return 0;return $a[28] < $b[28]?1:-1;}); break;
    case 4: usort($all_data, function ($a, $b){if($a[29]==$b[29]) return 0;return $a[29] < $b[29]?1:-1;}); break; 
    case 5: usort($all_data, function ($a, $b){if($a[30]==$b[30]) return 0;return $a[30] < $b[30]?1:-1;}); break;
    case 6: usort($all_data, function ($a, $b){if($a[31]==$b[31]) return 0;return $a[31] < $b[31]?1:-1;}); break;
    default: usort($all_data, function ($a, $b){if($a[26]==$b[26]) return 0;return $a[26] < $b[26]?1:-1;}); break; 
  }
  


//mysql_data_seek( $result, 0 );
//print_r($all_data);

$ID1[' ']=' ';
$ID2[' ']=' ';
$ID3[' ']=' ';
$ID4[' ']=' ';
$ID5[' ']=' ';
$ID6[' ']=' ';
$ID7[' ']=' ';
$ID8[' ']=' ';
$ID9[' ']=' ';
$ID10[' ']=' ';
$ID11[' ']=' ';


$query="
select 'تاخیر ارجاع' _key,1 as _value union all
select 'تاخیر انتخاب مجری' _key,2 as _value union all 
select 'تاخیر ثبت اولیه' _key,3 as _value union all
select 'تاخیر تایید نهایی' _key,4 as _value union all
select 'تاخیر تحویل موقت' _key,5 as _value union all
select 'تاخیر تحویل دائم' _key,6 as _value ";

$IDorder = get_key_value_from_query_into_array($query);
if (!$_POST['IDorder'])
    $IDorderval=1;
else $IDorderval=$_POST['IDorder'];

?>



<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پیشرفت پیش فاکتور های اجرایی </title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
  
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
            
            <form action="reports_applicantstatedate.php" method="post">
             
                <table id="records" width="95%" align="center">
                   
                   
                   <tbody>
                   
                <table align='center' class="page" border='1'  id="table2">              
                   <thead>
				  
                        
                        
                        
                   <?php

                    $rown=0;
                    for($i=0;$i<count($all_data);$i++)
                    {        
                       
                        $row2['ApplicantMasterID']=$all_data[$i][0];
                        $row2['creditsourcetitle']=$all_data[$i][1];
                        $row2['ApplicantName']=$all_data[$i][2];
                        $row2['ApplicantFName']=$all_data[$i][3];
                        $row2['ADateejra']=$all_data[$i][4]; 
                        $row2['CPI']=$all_data[$i][5];
                        $row2['SaveDateejra']=$all_data[$i][6];
                        $row2['DVFS']=$all_data[$i][7];
                        $row2['WinDateejra']=$all_data[$i][8];
                        $row2['DesignerCoIDnazerTitle']=$all_data[$i][9];
                        
                        $row2['belaavaz']=$all_data[$i][10];
                        $row2['LastTotal']=$all_data[$i][11];
                        $row2['firstsave']=$all_data[$i][12];
                        $row2['ClerkIDwin']=$all_data[$i][13];
                        $row2['numfield2op']=$all_data[$i][14];
                        $row2['numfield2oplist']=$all_data[$i][15];
                        $row2['taidpishF']=$all_data[$i][16];
                        $row2['operatorcoid']=$all_data[$i][17];
                        $row2['DesignArea']=$all_data[$i][18];
                        $row2['operatorcotitle']=$all_data[$i][19];
                        $row2['applicantstatestitlem']=$all_data[$i][20];
                        $row2['applicantstatesID']=$all_data[$i][21];
                        $row2['shahrcityname']=$all_data[$i][22];
                        $row2['shahrid']=$all_data[$i][23];
                        $row2['DesignSystemGroupstitle']=$all_data[$i][24];
                        $row2['performed']=$all_data[$i][25];
                        $temporarydel=$all_data[$i][32];
                        $permanentfree=$all_data[$i][33];
                        
                        $dv1=$all_data[$i][26];
                        $dv2=$all_data[$i][27];
                        $dv3=$all_data[$i][28];
                        $dv4=$all_data[$i][29];
                        $dv5=$all_data[$i][30];
                        $dv6=$all_data[$i][31];
                         
                         
                         
                                $encrypted_string=$row2['CPI'];
                                $encryption_key="!@#$8^&*";
                                $nazerali="";
                                for ($i1=0;$i1<(substr($encrypted_string,0,3)-5);$i1++)
                                    $nazerali.=chr(substr($encrypted_string,3*$i1+3,3));
                                $encrypted_string=$row2['DVFS'];
                                $encryption_key="!@#$8^&*";
                                $nazerali.=" ";
                                for ($i1=0;$i1<(substr($encrypted_string,0,3)-5);$i1++)
                                    $nazerali.=chr(substr($encrypted_string,3*$i1+3,3));
                                    
                            
                              //  $nazerali= str_replace(' ', '&nbsp;', $nazerali);
                        
                           	$numfield2arrayt = explode('_',$row2['numfield2op']);
   	                        $numfield2arrayp = explode('_',$row2['numfield2oplist']);
                            
                            
                            if ($row2['performed']==1)
                                $lbldone='اجراشده';
                            else
                                $lbldone='اجرا نشده';
    
                            if (($_POST['ID11']=='اجراشده' && $lbldone!='اجراشده')||($_POST['ID11']=='اجرا نشده' && $lbldone!='اجرا نشده'))
                                continue;
                            
                        
                        if ($temporarydel!='')
                           $temporarydelcolor='black';
                           else         
                           $temporarydelcolor='blue';
                           
                        if ($permanentfree!='')
                           $permanentfreecolor='black';
                           else         
                           $permanentfreecolor='blue';
                                                
                                                
                        if ($row2["taidpishF"]!='') 
                            $colorall='black';
                        else   
                        {
                           $colorall='red';  
                        }
                            
                            
                        $applicantstatesTitles=array();
                        $printstrall.=$printstrcur;    
                        $printstrcur="";
                        $ID2[trim($row2['ApplicantName'])]=trim($row2['ApplicantName']);
                        if ($row2['DesignArea']>0)
                        $ID3[trim($row2['DesignArea'])]=trim($row2['DesignArea']);
                        $ID4[trim($row2['DesignSystemGroupstitle'])]=trim($row2['DesignSystemGroupstitle']);
                        $ID5[trim($row2['shahrcityname'])]=trim($row2['shahrcityname']);
                        $ID6[trim($row2['operatorcotitle'])]=trim($row2['operatorcotitle']);
                        $ID7[trim($row2['DesignerCoIDnazerTitle'])]=trim($row2['DesignerCoIDnazerTitle']);
                        $ID8[trim($nazerali)]=trim($row2['ClerkIDwin']);
                        $ID9[trim($row2['applicantstatestitlem'])]=trim($row2['applicantstatestitlem']);
                        
                        $ID10[trim($row2['creditsourcetitle'])]=trim($row2['creditsourcetitle']);
                        
                        $ApplicantMasterIDold=$row2["ApplicantMasterID"];
                        $applicantstatesTitleold=$row2["applicantstatesTitle"];
                        $savedateold=$row2["savedate"];
                        $rolesidold=$row2["rolesid"]; 
                        $DesignAreaold=$row2['DesignArea'];
                        $shahrcitynameold=$row2['shahrcityname'];
                        $ApplicantNameold=$row2['ApplicantName'].' '.$row2['ApplicantFName'];
                        $applicantmasterold=$row2['ApplicantMasterID']; 
                        $sendtonazerm=$row2["sendtonazerm"];
                        $sendtonazer=$row2["sendtonazer"];
                        $taidpishF1=$row2["taidpishF"];
                        $ApplicantName = $row2['ApplicantName'];
                        $ApplicantFName = $row2['ApplicantFName'];
                        $sumL=$row2['LastTotal'];
                        $rown++;
                        if ($rown%2==1) 
                            $b='b'; else $b=''; 
                        $SaveDateejra='';
                        if ($row2['SaveDateejra']!="") $SaveDateejra=gregorian_to_jalali( $row2['SaveDateejra']);
                        if ($row2['ADateejra']!="") $SaveDateejra.= '<br>'.gregorian_to_jalali($row2['ADateejra']); else $SaveDateejra.= '<br>-';
                        if ($row2['WinDateejra']!="") $SaveDateejra.='<br>'.gregorian_to_jalali( $row2['WinDateejra']);
                        if ($taidpishF1!="") $taidpishF= gregorian_to_jalali( $taidpishF1); else $taidpishF='-';
                        if ($temporarydel!="") $taidpishF.= '<br>'.gregorian_to_jalali( $temporarydel); else $taidpishF.='<br>-';
                        if ($permanentfree!="") $taidpishF.= '<br>'.gregorian_to_jalali( $permanentfree); else $taidpishF.='<br>-';
                        
                        
                        
                        if ($row2['firstsave']!='')
                        $firstsave=gregorian_to_jalali($row2['firstsave']);
                        else 
                        $firstsave='';
                        
                        if ($_POST['IDorder']==1 && ( $row2['ADateejra']!='' || $firstsave!='' || $row2['WinDateejra']!='') ) continue;
                        if ($_POST['IDorder']==2 && ($row2['WinDateejra']!='' || $firstsave!='') ) continue;
                        if ($_POST['IDorder']==3 && $firstsave!='') continue;
                        if ($_POST['IDorder']==4 && ($taidpishF1!='' || $temporarydel!='' || $permanentfree!='')) continue;
                        if ($_POST['IDorder']==5 && $temporarydel!='' || $permanentfree!='') continue;
                        if (($_POST['IDorder']==6 && $permanentfree!='')) continue;
                                                
                        if ($_POST['IDorder']==1 && $dv1<=0) continue;
                        if ($_POST['IDorder']==2 && $dv2<=0) continue;
                        if ($_POST['IDorder']==3 && $dv3<=0) continue;
                        if ($_POST['IDorder']==4 && $dv4<=0) continue;
                        if ($_POST['IDorder']==5 && $dv5<=0) continue;
                        if ($_POST['IDorder']==6 && $dv6<=0) continue;
                        
                        $printstrcur.= "<tr  > 
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$rown</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$ApplicantName <br> $ApplicantFName</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[DesignArea]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[DesignSystemGroupstitle]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[shahrcityname]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[operatorcotitle]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[DesignerCoIDnazerTitle]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$nazerali</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[applicantstatestitlem]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[creditsourcetitle]</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>".(floor($sumL/100000)/10)."</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$row2[belaavaz]</font></span>  </td>
                                        <td <span class=\"f10_font$b\" > <font color='$colorall'>$SaveDateejra</font></span>  </td>
                                        <td <span class=\"f10_font$b\" > <font color='$colorall'>$firstsave</font></span>  </td>
                                        <td <span class=\"f10_font$b\" > <font color='$colorall'>$taidpishF</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$dv1</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$dv2</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$dv3</font></span>  </td>
                                        <td <span class=\"f12_font$b\" > <font color='$colorall'>$dv4</font></span>  </td>
                                        <td <span class=\"f12_font$b\" ><font color='$temporarydelcolor'> $dv5 </font></span>  </td>
                                        <td <span class=\"f12_font$b\" ><font color='$permanentfreecolor'> $dv6 </font></span>  </td>";       
                                       // break;    
                }           
                
                    $ID11['اجراشده']='اجراشده';
                    $ID11['اجرا نشده']='اجرا نشده';
                                           
                   $printstrall.=$printstrcur;  
                   $ID2=mykeyvalsort($ID2);
                   $ID3=mykeyvalsort($ID3);
                   $ID4=mykeyvalsort($ID4);
                   $ID5=mykeyvalsort($ID5);
                   $ID6=mykeyvalsort($ID6);
                   $ID7=mykeyvalsort($ID7);
                   $ID8=mykeyvalsort($ID8);
                   $ID9=mykeyvalsort($ID9);
                   $ID10=mykeyvalsort($ID10);
                   $ID11=mykeyvalsort($ID11);
                   
                   $printstrtop=  "
                   <tr> 
                           
                            <td colspan='22'
                            <span class='f14_fontb'>گزارش پیشرفت پیش فاکتور های اجرایی</span> <a  target='".$target."' href='chart_applicantstatedate.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
							.rand(10000,99999).'1____'.$allvals.rand(10000,99999).
                            "'><img style = \"width: 25px;\" src=\"../img/chart.png\" title='نمودار پيشرفت زماني پيش فاكتور هاي اجرايي' ></a></td>
							                            
				   </tr>
                   
                   <tr>
                            <th  
                           	<span class='f11_fontb' > رديف  </span> </th>
							<th 
                           	<span class='f14_fontb'> نام  </span> </th>
							<th 
                            <span class='f12_fontb'> مساحت </span>
							 (ha)  </th>
                            <th   class='f12_fontb'> نوع سیستم  </th>
						    <th 
                            <span class='f12_fontb'>دشت/ شهرستان</span> </th>
							<th  
                            <span class='f12_fontb'>شركت مجری</span> </th>
							<th  
                            <span class='f12_fontb'>مشاور ناظر</span> </th>
							<th  
                            <span class='f12_fontb'>کارشناس</span> </th>
							<th  
                            <span class='f12_fontb'>وضعیت</span> </th>
							<th  
                            <span class='f12_fontb'>اعتبار</span> </th>
							<th  
                            <span class='f12_fontb'> مبلغ کل </span>
						    <th  <span class='f12_fontb'>کمک بلاعوض</span> </th>
						    <th  <span class='f12_fontb'>انعقاد/<br>ارجاع/<br>انتخاب</span> </th>
							<th  <span class='f12_fontb'>ثبت اولیه</span> </th>
						    <th  <span class='f12_fontb'> تایید&nbsp;نهایی/<br> تحویل&nbsp;موقت/<br> تحویل&nbsp;دائم</span> </th>
						    <th  <span class='f12_fontb'>تاخیر ارجاع</span> </th>
						    <th  <span class='f12_fontb'>تاخیر انتخاب مجری</span> </th>
						    <th  <span class='f12_fontb'>تاخیر ثبت اولیه</span> </th>
						    <th  <span class='f12_fontb'>تاخیر تایید نهایی</span> </th>
						    <th  <span class='f12_fontb'>تاخیر تحویل موقت</span> </th>
						    <th  <span class='f12_fontb'>تاخیر تحویل دائم</span> </th>
                            
                    	
                        </tr>
                       </thead> 
                    <tr class='no-print'>    
						    <td class=\"f14_font\"></td>".
                            select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'','','','100%').
							select_option('DesignArea','',',',$ID3,0,'','','1','rtl',0,'',$DesignArea,'','100%').
					        select_option('DesignSystemGroupstitle','',',',$ID4,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'','100%').
					        select_option('shahrcityname','',',',$ID5,0,'','','1','rtl',0,'',$shahrcityname,"",'100%').
					        select_option('operatorcotitle','',',',$ID6,0,'','','1','rtl',0,'',$operatorcotitle,'','100%'). 
					        select_option('DesignerCoIDnazerTitle','',',',$ID7,0,'','','1','rtl',0,'','','','100%').  
				            select_option('applicantstatestitle','',',',$ID9,0,'','','2','rtl',0,'',$applicantstatestitle,'','100%').
                           select_option('creditsourcetitle','',',',$ID10,0,'','','1','rtl',0,'',$creditsourcetitle,'','100%').
                            
		     				select_option('IDorder','ترتیب',',',$IDorder,0,'','','2','rtl',0,'',$IDorderval,"",'100%').
		     				select_option('ID11','وضعیت جرا',',',$ID11,0,'','','1','rtl',0,'',$_POST['ID11'],"",'100%');
                    $printstrtop.= "<td style=\"text-align:left;\" colspan=1><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\"
                           value=\"جستجو\" /></td>
                                <td class='f14_font' colspan=7>همه<input name='showa' type='checkbox' id='showa'";
                    if ($showa>0) $printstrtop.= 'checked';
                    $printstrtop.= " /></td>'</tr>";
                                 
                   print $printstrtop.$printstrall;
                   
?>

                </table>
				<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
                    </tbody>
                   
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
               </form>
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
