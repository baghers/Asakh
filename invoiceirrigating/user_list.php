<?php 
/*
user_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/
include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php include('includes/elements.php'); ?><?php

if ($login_Permission_granted==0) header("Location: ../login.php");


$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) : $login_ostanId.'00000';
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;



$cond="";
if ($g2id>0) $cond=" and clerk.city='$g2id' ";
if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";

if ($login_RolesID==17) //ناظر مقیم فقط نقش کشاورز را می بیند
{$cond.=" and roles.rolesid in (26) and ClerkIDSaving='$login_userid' ";$hide2='style=display:none';}
else {$cond.=" ";$hide=' ';}

/*
clerk جدول کاربران
producers جدول تولیدکنندگان
designerco جدول شرکت های طراح
operatorco جدول پیمانکار
*/
$sql = "
SELECT distinct clerk.*,roles.title rolestitle
,producers.title producerstitle,designerco.title designercotitle,operatorco.title operatorcotitle
,producers.bosslname producersbosslname,designerco.bosslname designercobosslname,operatorco.bosslname operatorcobosslname
FROM clerk
left outer join roles on roles.rolesid=clerk.city 
left outer join producers on producers.producersid=clerk.BR
left outer join designerco on designerco.designercoid=clerk.MMC
left outer join operatorco on operatorco.operatorcoid=clerk.HW


where 1=1 $cond
ORDER BY roles.title COLLATE utf8_persian_ci,clerk.clerkID ";

//print $sql;

if ($login_designerCO==1) {$Role='';$ost='';}
else if ($login_RolesID==1) {$Role=' and roles.rolesid not in (1,2,3,9,10) ';$ost="and substring(clerk.cityid,1,2)='$login_ostanId'";}
else {$Role=' and roles.rolesid in (2,3,9,10) ';$ost="and substring(clerk.cityid,1,2)='$login_ostanId'";}

$sqlselect="
SELECT distinct roles.rolesid _value,roles.title _key   FROM clerk
inner join roles on roles.rolesid=clerk.city $Role
order by _key  COLLATE utf8_persian_ci";
$allg2id = get_key_value_from_query_into_array($sqlselect);

//print $sqlselect;

$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
$ost
order by _key  COLLATE utf8_persian_ci";
$allg1id = get_key_value_from_query_into_array($sqlselect);

//print $sqlselect;

$result = mysql_query($sql);

//exit;

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست کاربران سایت</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />
	<!-- scripts -->
    <script>
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value+ '&g2id=' + document.getElementById('g2id').value;
        
	}
    </script>
    <!-- /scripts -->
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
                <table width="95%" align="center">
                    <tbody>
                        <div style = "text-align:left;">
                            <a href="user_new.php">
                             <img style = 'width: 2%;' src='img/Actions-document-new-icon.png' title=' افزودن کاربر جدید '> </a>
                            
                          </div>
                          
                
                <tr <?php echo $hide2; ?>>
				 <?php
                 print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
                 print select_option('g2id','نقش',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
                 ?>
                </tr>      
                          
                   </tbody>
                </table>
				
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="2%"></th>
                            <th width="10%">نام کاربری</th>
							<th width="13%"> </th>
                         <th width="13%">نام</th>
                            <th width="15%">نام خانوادگی</th>
                            <th <?php echo $hide; ?> width="20%">نقش</th>
                           <th <?php echo $hide; ?> width="20%">شرکت</th>
                           <th <?php echo $hide; ?> width="20%">مدیر </th>
                        	<th <?php echo $hide; ?> width="10%">همراه</th>
                        	<th <?php echo $hide; ?> width="10%">شناسه/کدملی</th>
                        	<th <?php echo $hide; ?> width="3%">پیامک</th>
                            <th <?php echo $hide2; ?> width="3%">محلی/س <br>آپشن </th>
                            <th <?php echo $hide2; ?> width="3%">فعال</th>
                            <th width="3%"></th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $rown=0;

                    while($row = mysql_fetch_assoc($result)){
                
                        $ClerkID = $row['ClerkID'];
                        $username = decrypt($row['NOC']);
                        $first_name = decrypt($row['CPI']);
                        $last_name = decrypt($row['DVFS']);
						$Email=decrypt($row['email']);
                        $group_name = $row['group_name'];
						$cl='';
						if ($row['Disable']==1) 
						$cl='ff0000';
                        $rown++;
						$ID = 'clerk'.'_'.'کاربر'.'_'.$ClerkID;
?>

                        <tr >
							<td style="color:#<?php echo $cl; ?>;"> <?php echo $rown; ?></td>
							<td style="color:#<?php echo $cl; ?>;"><a target="_blank" href="<?php print "codding/codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999)
								.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>"><font color='purple'> <?php echo $username; ?></font></td>
						  	</a> <td style="color:#<?php echo $cl; ?>;" <?php echo $hide2; ?>><?php echo $ClerkID; ?></td>
						    
                     
                      
                            <td style="color:#<?php echo $cl; ?>;"><?php echo $first_name; ?></td>
							<td style="color:#<?php echo $cl; ?>;"><?php echo $last_name; ?></td>
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php echo $row['rolestitle']; ?></td>
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php echo $row['designercotitle'].$row['operatorcotitle'].$row['producerstitle']; ?></td>
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php echo $row['designercobosslname'].$row['operatorcobosslname'].$row['producersbosslname']; ?></td>
							
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php echo $row['mobile'].'</br>'.$Email; ?></td>
						    <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php echo $row['melicode']; ?></td>
                       	
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php  if ($row['notgetsms']==1) echo 'غیرفعال';  ?></td>
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php if ($row['isglobal']==1) echo 'س'; else echo 'م';echo '<br>';if ($row['isfulloption']==1) echo 'ف'; else echo '';?></td>
                            <td style="color:#<?php echo $cl; ?>;" <?php echo $hide; ?>><?php if ($row['isfulloptiondate']>date('Y-m-d')) echo gregorian_to_jalali($row['isfulloptiondate']); else echo ' ';?></td>
					       <?php
							
							if (!$row['Disable']) $reset='unreset.jpg'; else $reset='reset.jpg';
							
                            if (($login_designerCO==1 || $login_RolesID==19) && ($row['operatorcotitle']<>'') )
                            {
                                echo "<td><a href='user_key.php?uid=".rand(10000,99999)
							.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
							.rand(10000,99999).rand(10000,99999).$ClerkID.rand(10000,99999)."'>
                            <img style = \"width: 25px;\" src=\"img/protection.png\" title=' دریافت کد ورود ' ></a></td>";
                            }
                            else echo "<td/>";
							
                            if ($login_designerCO==1 || $login_RolesID==18 || $login_RolesID==5)
							{
									echo "<td>$row[Disable]<a onClick=\"return confirm('آیا از اجازه فعالیت  $first_name  $last_name مطمئنید؟ ')\" href=\"user_reset.php?dison=".$ClerkID."_".$row['Disable']."\"><img style = 'width: 20px;' src='img/$reset' title='اجازه' 
								 ></a></td>";
								 
									echo "<td>$row[Disable]<a onClick=\"return confirm('آیا از بازنشانی  محدودیت  $first_name  $last_name مطمئن هستید؟')\" href=\"user_reset.php?dis=$ClerkID\"><img style = 'width: 20px;' src='img/$reset' title='بازنشانی  محدودیت' 
								 ></a></td>";
								 
							    echo "<td><a href='user_edit.php?uid=".rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).$ClerkID."_0".rand(10000,99999)."'>
									<img style = \"width: 25px;\" src=\"img/file-edit-icon.png\" title='  ويرايش  ' ></a></td>";
						     
								echo "<td><a onClick=\"return confirm('آیا از بازنشانی کلمه عبور  $first_name  $last_name مطمئن هستید؟')\" href=\"user_reset.php?uid=$ClerkID\"><img style = 'width: 20px;' src='img/reset.jpg' title='بازنشانی کلمه عبور' 
								 ></a></td>";
								 
							if ($Email)
							  echo "<td><a onClick=\"return confirm('آیا ازارسال نام کاربری $first_name  $last_name به $Email مطمئن هستید؟')\" href='user_edit.php?uid=".rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).$ClerkID."_1".rand(10000,99999)."'>
									<img style = \"width: 25px;\" src=\"img/mail_lock.png\" title='  ارسال نام کاربری  ' ></a></td>";

							}
						else
                               echo "<td><a href='user_edit.php?uid=".rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
									.rand(10000,99999).rand(10000,99999).$ClerkID."_0".rand(10000,99999)."'>
									<img style = \"width: 25px;\" src=\"img/file-edit-icon.png\" title='  ويرايش  ' ></a></td>";
                            
                           
                            
                             ?>
                            
                        </tr><?php
                
                    }
                
?>
                    </tbody>
                </table>
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