<?php
/*
user_new.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/

 session_start();
 include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); 
      include('includes/elements.php');
      require_once('class/fieldType.class.php');
      $drop=new fieldType();
     require_once('funcs.php');
       ?>
<?php
if ($login_Permission_granted==0) header("Location: ../login.php");

$register = false;
  $soo=$login_ostanId.'00000';
  $sos=$login_CityId;
	

if(isset($_POST["submit"])){
     $string=$_POST['username'];
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
            
    
   
    
    /*
    'نام'=>$first_name,
    'نام خانوادگی'=>$last_name,
    'ایمیل'=>$email,
    'نام کاربری'=>$username ,
    'کلمه عبور'=>$password ,
    'تکرار کلمه عبور'=>$_POST["passwordr"]  ,
    'کد ملی'=>$melicode ,
    'تلفن همراه'=>$mobile ,
    'نقش کاربر'=>$RolesID,
    'استان'=>$_POST["soo"],
    'دشت/شهرستان'=>$CityId 
    */
   
    
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$gender = $_POST["gender"];
	$username = $_POST["username"];
	$mobile = $_POST["mobile"];
	$password = $_POST["password"];
    if ($_POST["DesignerCoID"]>0)
	   $DesignerCoID = $_POST["DesignerCoID"];
    else
        $DesignerCoID=0;
    if ($_POST["ProducersID"]>0)
	   $ProducersID = $_POST["ProducersID"];
    else
       $ProducersID=0;
	$RolesID = $_POST["RolesID"];
    if ($_POST["operatorcoID"]>0)
	   $operatorcoID = $_POST["operatorcoID"];
    else
        $operatorcoID=0;
	$CityId = $_POST["city"];
	$Email = $_POST["Email"];
	$melicode = $_POST["melicode"];

	//clerk جدول کاربران
    //پرس و جوی بررسی تککراری بودن نام کاربری
	 $query = "SELECT clerk.* FROM clerk WHERE substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3);";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    //بررسی ایمیل
    $resmbil = @mysql_query("select * from clerk where mobile=".$_POST["mobile"]." and substring(CityId,1,2)= substring(".$CityId.",1,2) and city=".$RolesID." ");
    $mbil = @mysql_num_rows($resmbil);
    
     $duplicate=0;
     $cumbil=0;
    if ($row['ClerkID']>0)
      $duplicate=1;
    if($mbil>0) $cumbil=1;
	
////////////////////////////////////////////////////////////////////////////////////////////////
///php validation/////////////////////////////////////////////////////////////////////////////////
    require_once('class/validation.class.php');
    $valid = new validation;	
	$vals = array('نام'=>$first_name,'نام خانوادگی'=>$last_name,'ایمیل'=>$Email
      ,'نام کاربری'=>$username ,'کلمه عبور'=>$password ,'تکرار کلمه عبور'=>$_POST["passwordr"]  
      ,'کد ملی'=>$melicode ,'تلفن همراه'=>$mobile ,'نقش کاربر'=>$RolesID
      ,'استان'=>$_POST["soo"],'دشت/شهرستان'=>$CityId  
            );
             $valid->addSource($vals);
             $valid->addRule('نام', 'persian_str', true, 1, 50, true)
                   ->addRule('نام خانوادگی', 'persian_str', true,1, 50, true)
                   ->addRule('نام کاربری','string', true, 4, 8, true)
                   ->addRule('ایمیل', 'email', true, 1, 255, true)
                   ->addRule('کلمه عبور', 'password', true, 8, 15, true)
                   ->addRule('تکرار کلمه عبور', 'match', true, $password, 255, true)
                   ->addRule('کد ملی', 'melicode', true, 10, 10, true)
                   ->addRule('تلفن همراه', 'mobile', true, 10, 10, true)
                   ->addRule('نقش کاربر', 'string', true, '1', '50',true)
                   ->addRule('استان', 'string', true, '1', '50', true)
                   ->addRule('دشت/شهرستان', 'string', true,'1', '50',true);
           
            $valid->run();
        
 /////////////////////////////////////////////////////////////////////////////////////////////
	   $query = "INSERT INTO clerk(NOC, WN, CPI, DVFS,email,melicode, GE, MMC ,BR,HW 
            ,city,SaveTime,SaveDate,ClerkIDSaving,CityId,mobile,isglobal,isfulloption,isfulloptiondate) 
            VALUES('" . encrypt($username) . "', '" . encrypt($password) . "', '" . encrypt($first_name) . "', '" . encrypt($last_name) . "'
			, '" . encrypt($Email) . "', 
            '$melicode','$gender', '$DesignerCoID', '$ProducersID', '$operatorcoID', '$RolesID' 
            , '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','$login_userid','$CityId','$mobile',1,1,'2030-03-21');";
	   $_SESSION['erors']='';
	   $_SESSION['usreror']='';
	   $_SESSION['mbileror']='';
       //print $cumbil; exit;
       //echo $query;
	   if((sizeof($valid->errors)== 0) && ($duplicate==0) )
       {
            $result = mysql_query($query);
            
            echo 'ثبت با موفقیت انجام شد ';
            //exit;
       }
	   	  
	   else
	   {
	   	 if(sizeof($valid->errors) > 0)
	       $_SESSION['erors']=$valid->errors;
	     if($duplicate!=0)
	       $_SESSION['usreror']='نام کاربری تکراری است';
	     if($cumbil!=0)
	       $_SESSION['mbileror']='تلفن همراه تکراری است';
           
           echo $_SESSION['erors']."<br>";
           echo $_SESSION['usreror']."<br>";
           echo $_SESSION['mbileror']."<br>";
            //exit;
	   }
	   
}
$password=rand_Pass();;

?>
<!DOCTYPE html>
<html>
<head>
  	<title>عضویت</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='assets/jquery2.js'></script>
<script type="text/javascript" src="lib/jquery2.js"></script>
<script type='text/javascript' src='lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="css/styl.css" />
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="lib/thickbox.css" />
<link rel="stylesheet" href="assets/style.css" type="text/css" />
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="funcs.js"></script>
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
			<!-- top -->
        	<?php include('includes/top.php'); ?>
            <!-- /top -->
            
            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->

            <!-- /main navigation -->
            
            <?php include('includes/subnavigation.php'); ?>
            
			<!-- header -->
            <?php include('includes/header.php'); ?> 
			<!-- /header -->

			<!-- content -->
			<div id="content">
            <div style = "text-align:left;">
                            <a  href='user_list.php'>
                            <img style = "width: 4%;" src="img/Return.png" title='بازگشت' ></a>
                            
                          </div>

                <form action="user_new.php" method="post"  >
                   <table width="600" align="center" class="form">
                    <tbody>
                     <tr>
                      <td width="20%" class="label">نام:</td>
                      <td class="data"><input name="first_name" type="text" class="textbox" id="first_name"  size="12" maxlength="50" title="لطفا حروف فارسی وارد نمایید" required /></td>
                      <td class="label">نام خانوادگی:</td>
                      <td class="data"><input name="last_name" type="text" class="textbox" id="last_name" size="12" maxlength="50" title="لطفا حروف فارسی وارد نمایید" required /></td>
                      <td class="label">جنسیت:</td>
					  
                      <td class="data">
                       <input name="gender" type="radio" id="gender" value="0"  >زن
                       <input name="gender" type="radio" id="gender" value="-1"  >مرد
                      </td>
                      <td class="label">نام کاربری:</td>
                      <td class="data"><input name="username" type="text" class="textbox" id="username" value="" dir="ltr" size="12" pattern="[a-zA-Z0-9]{4,8}" title="نام کاربری حداقل 4 و حداکثر 8 کاراکتر انگلیسی" required  /></td>
                     </tr>
                     <tr>
                      <td class="label">کلمه عبور:</td>
                   <td class="data"><input name="password" autocomplete="off" type="text" class="textbox" id="password"  value="<?php print $password; ?>"  dir="ltr" size="12" maxlength="20" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,15}"  required title="کلمه عبور حداقل 8 و حداکثر 15 و ترکیبی از عدد و کاراکتر انگلیسی" /></td>
                      <td class="label">تکرار کلمه عبور:</td>
                      <td class="data"><input name="passwordr" type="text" class="textbox" id="passwordr" value="<?php print $password; ?>" dir="ltr" size="12" maxlength="50" required onblur="return match_pass()"  /><span id='rpass'></span></td>
                      <td class="label">کد ملی</td>
                      <td class="data"><input name="melicode" type="text" class="textbox" id="melicode" dir="rtl"  size="10" maxlength="11" pattern="[0-9]{1,2}[0-9]{9}" title="(10 رقم)" required  />
                      
                      </td>
                      <td colspan="1" class="label">همراه(بدون صفرابتدا)</td>
                      <td class="data"><input name="mobile" type="text" class="textbox" id="mobile" dir="ltr"  size="10" maxlength="10"   pattern="[9]{1}[0-9]{9}" title="(رقم10)" required /></td>
                     </tr>
                     <?php
                     
                     
					 
					 if ($login_RolesID==17)
					    $query='select RolesID as _value,Title as _key from roles where rolesid in (26)';
                    else
                        $query='select RolesID as _value,Title as _key from roles';
					
    				 $ID = get_key_value_from_query_into_array($query);
                     echo "<tr><td>نقش کاربر</td><td>".$drop->dropDb2('RolesID','_key','_value',$query,'','required')."</td>"; 
             if ($login_RolesID!=17) 
             {
				
					 $query='select DesignerCoID as _value,Title as _key from designerco order by _key  COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
                    echo "<td>شرکت طراح</td><td>".$drop->dropDb('DesignerCoID','_key','_value',$query,'')."</td>"; 
                     
					 $query='select operatorcoID as _value,Title as _key from operatorco order by _key  COLLATE utf8_persian_ci';
    				 $ID = get_key_value_from_query_into_array($query);
                  
                     echo "<td>شرکت مجری</td><td>".$drop->dropDb('operatorcoID','_key','_value',$query,'')."</td>"; 
                     

                     $query='select ProducersID as _value,Title as _key from producers order by _key  COLLATE utf8_persian_ci';
    				
                    
                     echo "<td>فروشنده/تولیدکننده</td><td>".$drop->dropDb('ProducersID','_key','_value',$query,'')."</td>"; 

            }    
					 
                    $query="select id _value,CityName _key from tax_tbcity7digit where substring(id,3,5)='00000' 
					$ost
					order by _key  COLLATE utf8_persian_ci";
    				
                     echo "</tr><tr><td>استان</td><td>".$drop->dropDb2('soo','_key','_value',$query,'','required onclick="ajxcity(this.value)"')."</td>"; 
                    $query="
                    select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($soo,1,2)
                    and substring(id,5,3)='000' and substring(id,3,4)!='0000' order by _key  COLLATE utf8_persian_ci";
                    
    				          
       
                       echo"<td>دشت/شهرستان</td><td id='tdcity'>".$drop->dropDb2('city','_key','_value',$query,'','required')."</td>";
				
       			 print " 
	     <td  class='label'>ایمیل:</td>
                      <td colspan='3' class='data' ><input
                       name='Email'  type='email' title='واردکردن ایمیل' class='textbox' id='Email'  required   /></td>
                ";
								
	         	 
					  ?>
                     </tr>
                     
                    </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت کاربر جدید" /></td>
                     </tr>
                    </tfoot>
                   </table>
                  </form>
               <div style="padding:10px;color:red;text-align: center;">
                 <?php
                 if(isset($_POST["submit"]))
                 {
                  if(($_SESSION['erors']!='') || ($_SESSION['usreror']!=''))
                  {
                     if($_SESSION['erors']!='')
                     {
                       foreach($_SESSION['erors'] as $eror)
                          echo $eror.'<br>';
                     }
                     if($_SESSION['usreror']!='')
                        echo '<br>'.$_SESSION['usreror'];
                     if($_SESSION['mbileror']!='')
                        echo '<br>'.$_SESSION['mbileror'];
                  }
                  else 
                     echo 'ثبت با موفقیت انجام شد ';
                 }
                 ?>
               </div>
            </div> 
           
			<!-- /content -->
            
            <!-- footer -->
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>