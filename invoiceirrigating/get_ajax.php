<?php

/*
get_ajax.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

session_start();
require_once("includes/connect.php");
include('includes/elements.php');
require_once("funcviewapp.php");
require_once("funcs.php");
include_once('includes/check_user.php');

//اجرای کوئری های خواسته شده

if(isset($_GET["ajxselct"]))
{
 $rolesID=$_GET["ajxselct"];
 //echo $rolesID;
  if($rolesID==1)
   $city='9';
  elseif($rolesID==2)
   $city='10';
  elseif($rolesID==3)
   $city='2';
  elseif($rolesID==4)
   $city='3';
  elseif($rolesID==5)
   $city='11,12';
  else
   $city='1,4,5,6,7,8,13,14,15,16,17,18,19,20,21,22,23'; 
if(($city==9) || ($city==10))
   $query="Select '0' As _value, ' ' As _key Union All
           select DesignerCoID as _value,Title as _key from designerco  order by _key  COLLATE utf8_persian_ci ";
else if($city==2)
   $query="Select '0' As _value, ' ' As _key Union All
        select operatorcoID as _value,Title as _key from operatorco  order by _key  COLLATE utf8_persian_ci ";
else if($city==3)
   $query="Select '0' As _value, ' ' As _key Union All
        select ProducersID as _value,Title as _key from producers  order by _key  COLLATE utf8_persian_ci ";
$aryroles=array(2,3,9,10);
if(in_array($city,$aryroles))
{

echo'<select id="CoID1" name="CoID1" >';
$sql = mysql_query($query) or die(mysql_error());
while($ro = mysql_fetch_array($sql)){

	echo"<option value=".$ro['_value'].">".$ro['_key']."</option>";
 }
echo'</select>'; 
}
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["ajxcity"])
{
 $ostanID=$_GET["ajxcity"];
 $query="Select * from  tax_tbcity7digit  where  substring(id,1,2)=substring($ostanID,1,2)
                    and substring(id,5,3)='000' and  substring(id,3,4)!='0000'   ";
 if($ostanID>0)
 {
   echo'<select id="city" name="city" >';
  $sql = mysql_query($query) or die(mysql_error());
  while($ro = mysql_fetch_array($sql))
  {
	echo"<option value=".$ro['Id'].">".$ro['CityName']."</option>";
  }
   echo'</select>'; 
  }
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["retpass"])
{
  $rolesID=(int)($_GET["retpass"]);
  $CoID=(int)($_GET["CoID"]);
  $email=$_GET["email"];
  //$arycity=array('2','3','9','10','11','12');
  //$arycity=array('1','4','5','6','7','8','9','13','14','15','16','17','18','19','20','21','22','23');
  if($rolesID==1)
   $city='9';
  elseif($rolesID==2)
   $city='10';
  elseif($rolesID==3)
   $city='2';
  elseif($rolesID==4)
   $city='3';
  elseif($rolesID==5)
   $city='11,12';
  else
   $city='1,4,5,6,7,8,13,14,15,16,17,18,19,20,21,22,23'; 
  
//  $Cond2=" where city=".$rolesID." and mobile=".$mobile."  ";
  $Cond2=" where city in (".$city.")   ";
  
 // $Cond2=" where city=".$rolesID."  ";
  $wh="";
  if(($rolesID==1) || ($rolesID==2)) //مشاور
    $wh= "AND MMC = ".$CoID;
 elseif($rolesID==3)
    $wh= " AND HW = ".$CoID; 
 else if($rolesID==4)
    $wh= "AND BR = ".$CoID;
  $query ="SELECT * FROM clerk ".$Cond2.$wh;
  //echo $query;
  $result =mysql_query($query) or die(mysql_error());
  $row =mysql_fetch_assoc($result);
  $cunt =mysql_num_rows($result);
  $ClerkID=$row['ClerkID'];
  $mobile2=$row['mobile'];
  $user=decrypt($row['NOC']);
  $pass=decrypt($row['WN']);
  if($cunt==0)
   $roles=$query;
  else 
   $roles=decrypt($row['CPI'])." ".decrypt($row['DVFS']);
  $valus=$query.'~'.$pass.'~'.$roles.'~'.$ClerkID;
  if($mobile==$mobile2)
    echo $valus.'@1';
  else 
	 echo 'a@2';
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["ajxrep"])
{
$string=$_GET["ajxrep"];
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
  while(strlen($ascii)<120)
     $ascii =$ascii.rand(100,999);
            
  //$user=mysql_real_escape_string($_GET['ajxrep']);
  $res =mysql_query("SELECT clerk.* FROM clerk WHERE substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3);") or die(mysql_error());
  $cunt = mysql_num_rows($res);
  if($cunt!=0)
    $msg=1;
  else 
    $msg=0;
	echo $msg;
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["ajxmobile"])
{
  $mobile=$_GET["ajxmobile"];
  $RolesID=$_GET["RolesID"];
  $resmbil = @mysql_query("select * from clerk where mobile=".$mobile." and city=".$RolesID." ");
  $mbil = @mysql_num_rows($resmbil);
  if($mbil==0)
    $msg=1;
   else
     $msg=2;
  echo $msg;
}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["ajxsecure"])
{
$secure=$_GET["ajxsecure"];
if($secure!=$_SESSION['security_number'])
   $msg=0;
else 
  $msg=1;
  echo $msg;

}
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if($_GET["ajxadd"])
{
 $ids=$_GET["ajxadd"];
 $parm = explode('~',$ids);
 $string=$parm[3];
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
            
    
    
        $q = "SELECT clerk.* FROM clerk WHERE substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3);";
    $r = mysql_query($q);
    $cunt = mysql_num_rows($r);
	
	if($cunt==0)
	{	
      $qury= "INSERT INTO clerk(NOC, WN, CPI, DVFS, GE, MMC ,BR,HW 
            ,city,SaveTime,SaveDate,ClerkIDSaving,CityId,mobile) 
            VALUES('" . encrypt($parm[3]) . "', '" . encrypt($parm[4]) . "', '" . encrypt($parm[0]) . "', '" . encrypt($parm[1]) . "', 
            '$parm[2]', '$DesignerCoID', '$ProducersID', '$operatorcoID', '$parm[7]' 
            , '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','$login_userid','$parm[8]','$parm[5]');";
			//echo $qury;
	   $result = mysql_query($qury) or die(mysql_error());
	    if(mysql_affected_rows())
	      $ID=mysql_insert_id();
	    else 
		  $ID='';
	     echo $ID;
	 }
	  else 
	    echo 'msg';
	  
}
if(isset($_GET["ajaxview2"]))
{
 
//echo $login_RolesID.'tt'; 
 $txt=$_GET["ajaxview2"];
 $txt=cleanAll($txt);
 $and4='';
 //echo $txt;
 //$txt=str_replace(array('ی', 'ک'), array('ي', 'ك'), $txt); 
 $year=$_GET["year"];  
 if($txt!='')
 {
   $selop=$_GET["selop"]; 
   $selfild=$_GET["selfild"]; 
   
   if($selop=='content')
      $and=" like '%$txt%' ";
   elseif($selop=='notcontent')
      $and=" not like '%$txt%' ";
   else 
      $and="  $selop $txt";
   
   if($year!='') 
     $and3=' and applicantmaster.YearID='.$year.' ';
   else 
     $and3='';

	 if($login_RolesID=='2')
		$and4=" and operatorco.operatorcoID='$login_OperatorCoID'";
	elseif($login_RolesID=='9')
		$and4=" and designerco.DesignerCoID='$login_DesignerCoID'";  
	elseif($login_RolesID=='11')
		$and4=" and applicantmaster.DesignerCoID>0";  
		
  $and2=" and $selfild ".$and.$and3.$and4." order by applicantmaster.BankCode, applicantmaster.TMDate desc";
  
  if($_GET["chk"]==1)
    $rw=1; 
 else
   $rw=''; 	
 
	$sql=sqlviewapp();
	$sql=$sql.$and2;
	
 ///bagher echo $sql;
   $result = mysql_query($sql);
 dvlist($result,$rw,$sql);
 }
}
if(isset($_GET["ajaxview"]))
{
  $sql=sqlviewapp();
  $sql.=" and applicantmaster.ApplicantMasterID=".$_GET['ajaxview']." ";
 // echo $sql; 
  $result = mysql_query($sql) or die(mysql_error());
  $rowresult = mysql_fetch_array($result) or die(mysql_error());
 
  dvdet($rowresult); 
}







?>