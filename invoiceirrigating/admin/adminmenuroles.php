<?php 
/*

//admin/adminmenuroles.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

-

*/

include('../includes/connect.php');
include('../includes/check_user.php'); 

/*
تابع زیر رشته پرس و جو را دریافت کرده و نتیجه اجرای آن را در یک آرایه کلید و مقدار قرار می دهد
*/
function get_key_value_from_query_into_array($query)
{
    $returned_array='';
    
    try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			exit;
        } 
            
            
	$returned_array[' ']=' ';
    if ($result)
	while($row = mysql_fetch_assoc($result))
      $returned_array[$row['_key']]=$row['_value'];
   
    
    
     return $returned_array;
}
/*
  این تابع با دریافت یک سری ویژگی های المنت کومبوباکس را ایجاد می نماید
  
  $name نام المنت
  $lable لیبل کنار المنت اینپوت
  $accesskey کلید شورتکات المنت که با آن کلید فوکوس روی آن المنت می شود
  $option آرایه کلید مقدار ورودی
  $tabindex ایندکس تب
  $empty_title_string رشته جایگزین آیتم خالی
  $maxlength طول حداکثر اینپوت
  $size اندازه 
  $event ایونت   
  $disabled_str اینکه المنت غیر فعال شود
  $colspan تعداد ستون های  اشغالی اینپوت در صفحه
  $type نوع
  $dir جهت المنت که از راست به چپ یا از چپ به راست باشد
  $access_etitle شورتکات دسترسی به المنت
  $width عرض المنت با واحد پیکسل
  $Originalonblure ایونت خروج از المنت برای نوع تاریخ
  $class کلاس استایل
  $default_number مقدار پیش فرض
  $border اندازه حاشیه عنصر
  $height ارتفاع عنصر
*/
function select_option($name,$lable='',$accesskey='',$option=array('title'=>'value'),$tabindex=0,$empty_title_string='',
  $disabled_str='',$colspan=1,$dir='rtl',$size='0',$class='',$default_number=0,$event='',$width='',$type='',$border='1',$height='')
{

    $result='';
    //-------------------------------------------------------------
    //-------------------------------------------------------------
    if ($height!='')
        $heightstr='height:$height;';//تخصیص اندازه ارتفاع وارد شده    
    if ($width!='' && $type!='hidden')//در صورتی که سایز پیکسلی ارسال شده باشد به اتریبیوت های المنت افزوده می شود
    {
		$width="style='width: ".$width."px;border:$border;$heightstr'";
	}

	
    if ($lable!='')//تخصیص لیبل به کومبو باکس
    {
      $result=$result."<td class='label' >$lable</td>";
    }
    if ($size > 0 && $type!='hidden')
	  $size="size='$size'";
	else
	  $size=" ";
    //افزودن اتریبیوت تعداد ستون های المنت
	  $result=$result."<td class='data' colspan='$colspan'><br/>";

	if ( is_array($option))
    {
    $selectedTitle="";
	foreach($option as $title => $value)//افزودن عناصر به کومبو باکس
    {
        
      if (($default_number) and ($default_number==$value))
        $selectedTitle='('.$title.')';   
    }
    }
    //تخصیص اتریبیوت های ارسالی به تابع  به رشته خروجی
    if ($type!='hidden')
		  $result=$result."&nbsp;&nbsp;<select $width $disabled_str  name='$name' id='$name' $size dir='$dir' tabindex=\"$tabindex\" class='$class' $event  onmouseover=\"Tip('$selectedTitle')\">";
          else 
          $result=$result."&nbsp;&nbsp;<select $width $disabled_str  name='$name' id='$name' $size dir='$dir' tabindex=\"$tabindex\" class='$class' $event  onmouseover=\"Tip('$selectedTitle')\">";
    //-------Empty Option Check---------
      if (isset($_POST[$name]))
      if ((!$_POST[$name]) and (!$default_number))
        $is_selected='selected="selected"';
			else
			  $is_selected='';
			if ($empty_title_string!='')
        $result=$result."<option $width value='0' $is_selected>$empty_title_string</option>";
    //---------Option Array---------
		
	if ( is_array($option))
	foreach($option as $title => $value)//تعیین عنصر پیش فرض به عنوان انتخاب شده
    {
        
        if (isset($_POST[$name]))
            if ($_POST[$name]==$value)
        $is_selected='selected="selected"';
      if (($default_number) and ($default_number==$value))
        $is_selected='selected="selected"';
      else
        $is_selected='';
        
      if ($type!='hidden') $result.="<option  value='$value' $is_selected>$title</option>";
    }
    //---------------------------------
    //بستن تگ ها
    if ($type!='hidden') $result=$result."</select>";

    $result=$result."</td>";

    return $result;
} 

//if ($login_Permission_granted==0) header("Location: ../login.php");
			
if ($_GET['task'])//اگر متغیر تسک آرایه گت  مقدار داشت 
{
    if ($_GET['task']=='deletegroup')//حذف نقش یک منو	
    {
        /*
        menuroles جدول نقش های مجاز هر منو
        MenuRolesID شناسه جدول نقش های مجاز هر منو
        */
        $idr = $_GET["idr"];
        $idm = $_GET["idm"];
        $querydelete="DELETE FROM menuroles where MenuRolesID='$idr'";
        try 
        {		
            mysql_query($querydelete);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 								
    }
    else if ($_GET['task']=='detailmenu')//نمایش ریز نقش های مرتبط با منو 
    {
        $idm = $_GET["idm"];//شناسه منو
        /*
        menu جدول منو
        MenuID شناسه منو
        menuroles جدول منو نقش
        RolesID شناسه نقش
        MenuRolesID شناسه منو نقش
        roles جدول نقش ها
        */
        $sql="SELECT menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu left outer join menuroles on menu.MenuID=menuroles.MenuID 
        left outer join roles on menuroles.RolesID=roles.RolesID where menu.MenuID='$idm' order by RolesID";						

        try 
        {		
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 								
    }
    else if ($_GET['task']=='deletemenu')//حذف منو
    {
        $idmdelete = $_GET['idm'];//شناسه منو
        try 
        {		
            /*
            menuroles جدول نقش های مجاز هر منو
            MenuRolesID شناسه جدول نقش های مجاز هر منو
            menuroles جدول منو نقش
            */
            $querydelete="DELETE FROM menu where MenuID='$idmdelete'";
            $resultdelete = mysql_query($querydelete);
            $querydelete1="DELETE FROM menuroles where MenuID='$idmdelete'";
            $resultdelete1 = mysql_query($querydelete1);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 	
        

								
    } 
								
}
if ($_POST['task']) 
{
    if ($_POST['task']=='addgroup')//افزودن نقش مجاز برای یک منو 
    {
        $RolesID = $_POST["RolesID"];//شناسه نقش
        $MenuID = $_POST["MenuID"];//شناسه منو
        /*
        menuroles جدول نقش های مجاز هر منو
        MenuRolesID شناسه جدول نقش های مجاز هر منو
        MenuID شناسه منو
        RolesID شناسه نقش
        */
        $queryinsert="INSERT INTO menuroles (MenuRolesID ,MenuID ,RolesID) VALUES (NULL ,'$MenuID', '$RolesID')";
        try 
        {		
            mysql_query($queryinsert);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }
        $idm = $MenuID;
							
    }
    else if ($_POST['task']=='addmenu')//افزودن منو
    {
        $menutype = $_POST['menutype'];//نوع منو
        $MenuID = $_POST['MenuID'];//شناسه منو
        $name = $_POST['name'];//عنوان منو
        $link = $_POST['link'];//لینک منو
        if ($_POST['linkID']=='on')  $linkID=1;//تاپ منو بودن
        $published = $_POST['published'];//انتشار یافته
        $parent = $_POST['parent'];//والد منو
        $ordering = $_POST['ordering'];//ترتیب		
        /*
        menuroles جدول نقش های مجاز هر منو
        MenuRolesID شناسه جدول نقش های مجاز هر منو
        MenuID شناسه منو
        RolesID شناسه نقش
        */
        try 
        {		
            /*
            menuroles جدول نقش های مجاز هر منو
            MenuRolesID شناسه جدول نقش های مجاز هر منو
            MenuID شناسه منو
            RolesID شناسه نقش
            menutype نوع منو
            name عنوان منو
            link لینک منو
            published انتشار یافته
            parent والد منو
            ordering ترتیب
            */    
            if ($MenuID=="")//منوی جدید 
            {
                $queryinsert="INSERT INTO menu (MenuID ,menutype ,name ,link ,published ,parent ,ordering,linkID) VALUES (NULL , '$menutype', '$name', '$link', '$published', '$parent', '$ordering', '$linkID')";
                mysql_query($queryinsert); 
            }
            else //بروزرسانی
            {
                $queryupdate="UPDATE menu SET menutype = '$menutype',name = '$name',link ='$link',published = '$published',parent = '$parent',ordering = '$ordering',linkID = '$linkID' WHERE menu.MenuID =$MenuID ";
                mysql_query($queryupdate); 
                $idm = $MenuID;
            }
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }
                

        
    }
}
	if ($login_RolesID==1) {$hide=' ';$strroles='';} else {$hide='style=display:none';$strroles='and menuroles.RolesID not in (1,2,3,9,10)';} 
					
		?>
<!DOCTYPE html>
<html>
<head>
  	<title>مديريت منو</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
<style>
fieldset {
    display: block;
    margin-left: 10px;
    margin-right: 10px;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 10px;
    padding-right: 10px;
    border: 1px solid #ccc;
} 
legend {
		color:#BBB;

}
table.menu {
	width:100%;
}

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
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
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

  
</style>
	</head>
<body>

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
                <br/>
            <?php
            if ($login_RolesID==1) $str='where'; else $str="left outer join menuroles on menuroles.MenuID=menu.MenuID
					   where menuroles.RolesID='$login_RolesID' and ";
		
			if ($idm>0 || $_GET['task']=='newmenu')
			{
				$sql="SELECT menu.*,menuroles.RolesID,roles.Title,menuroles.MenuRolesID FROM menu left outer join menuroles on menu.MenuID=menuroles.MenuID left outer join roles on menuroles.RolesID=roles.RolesID where menu.MenuID='$idm' 
				$strroles 
				order by RolesID";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				//print $sql;exit;				
			
				$query="select menu.MenuID _value,menu.name _key from menu 
				$str 1=1 order by _key  COLLATE utf8_persian_ci";
				$IDparent = get_key_value_from_query_into_array($query);
				$array2=array("تمام كاربران عضو و غير عضو"=>"101","تمام كاربران عضو"=>"100");
				if ($backdoor==1)
					$query="select RolesID _value,Title _key from roles order by _value";
				else 
					$query="select RolesID _value,Title _key from roles where 1=1 order by _value";
				$array1 = get_key_value_from_query_into_array($query);
				$IDroles = array_merge($array1,$array2);
				
				?>
				<fieldset>
				<legend>مشخصات منو انتخاب شده</legend>
				<form action="adminmenuroles.php" method="post">
						<table class="pmenu">              
					   <tr>
					   <td>عنوان منو</td>
					   <td >&nbsp;&nbsp;<input name="name" type="text" style="width:250px; color:#bbb;" value="<?php echo $row['name'];?>" ></td>
					   <td style="width:60%; text-align:left;">
					   <input name="MenuID" type="hidden" id="hidden" value="<?php echo $row['MenuID']; ?>" />
					   <input name="task" type="hidden" id="hidden" value="addmenu" />
					   <input name="submit" type="submit" class="button" id="submit" value="ذخيره" /></td>
					   </tr>
					   <tr>
					   <td><br/>نوع منو</td>
					    <td ><br/>&nbsp;&nbsp;<input name="menutype" type="text" value="<?php echo $row['menutype']; ?>" /></td>
						</tr>
					   <tr>
					   <td><br/>لينك منو</td>
					   <td><br/>&nbsp;&nbsp;<input name="link" type="text" style="width:250px; color:#bbb;" value="<?php echo $row['link'];?>" ></td>
					   <td><br/>&nbsp;&nbsp;<input name="linkID" type="checkbox" id="linkID" style="width:5px; color:#bbb;" 
					   <?php $linkID=$row['linkID']; if ($linkID>0) {?> checked <?php } ?> ></td>
					   
					   </tr>
					    <tr>
					   <td><br/>وضعيت انتشار</td>
					   <td><br/>&nbsp;&nbsp;<select name="published" value="<?php echo $row['published'];?> ">
								  <option value="0" <?php if($row['published']==0) echo "selected='selected'";?>>منتشر نشده</option>
								  <option value="1" <?php if($row['published']==1) echo "selected='selected'";?>>منتشر شده</option>
								  
								</select>
						</td>
					   </tr>
					   <tr>
					   <td <?php echo $hide; ?>><br/>والد</td>
					   <?php print select_option('parent','','',$IDparent,0,'','','1','rtl',0,'',$row['parent'],'','150');?>
						</tr>
						<tr>
					   <td><br/>ترتيب</td>
					   <td><br/>&nbsp;&nbsp;<input name="ordering" type="text" style="width:100px; color:#bbb;" value="<?php echo $row['ordering'];?>"></td>
						</tr>
					</table>
					</form>
					<br/>							
					<fieldset>
						<legend>ليست گروه هاي اختصاص داده شده به منو</legend>
						<table class="menu" align='center' border='1'>              
                   <thead>
				 
				   
                        <tr>

                            <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th <?php echo $hide; ?>
                           	<span class="f14_font"> كد گروه  </span> </th>
							<th  
                            <span class="f14_font" > عنوان </span></th>
						    <th  
                            <span class="f14_font" > حذف </span></th>
                           
                        </tr>
                       </thead> 
					   <?php 
					   do{
					    $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        if ($row['RolesID']<>NULL) {
?>                      
                        <tr>    
                            <td
                            <span class="f13_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							<td <?php echo $hide; ?>
							<span class="f13_font<?php echo $b; ?>"><?php echo '&nbsp;'.$row['RolesID']; ?> </span> </td>
                           	<td
							<span class="f13_font<?php echo $b; ?>"> <?php if ($row['RolesID']==100) echo 'تمام كاربران عضو'; else if ($row['RolesID']==101) echo 'تمام كاربران عضو و غير عضو'; else echo $row['Title']; ?> </span> </td>
							<td
							<span class="f13_font<?php echo $b; ?>"><a onClick="alert('آيا از حذف گروه مربوط به منو مطمئن هستيد؟')" href="<?php echo 'adminmenuroles.php?task=deletegroup&idr='.$row['MenuRolesID'].'&idm='.$idm.'">';?><img style ="width: 25px;" src="../img/delete.png" title=" حذف گروه" ></a></span> </td>
							
							</tr>
							<?php } ?>
						<?php }while($row = mysql_fetch_assoc($result))?>
					   </table>
					</fieldset>
					 <form action="adminmenuroles.php" method="post">
					<fieldset>
						<legend>اختصاص گروه جديد به منو</legend>
						<table class="menu">              
					   <tr>
					   <td><br/>عنوان گروه</td>
					    <?php print select_option('RolesID','','',$IDroles,0,'','','1','rtl',0,'','','','150');?>
						<input type="hidden" name="MenuID" value="<?php echo $idm;?>"> 
						<input type="hidden" name="task" value="addgroup"> 
						<td><br/><input name="submit" type="submit" class="button" id="submit" value="ثبت گروه" /></td>
						</tr>
						</table>
					</fieldset>
					</form>
			</fieldset>
			
			<?php }
			else {
			?>
           <fieldset>
			<legend>مديريت منو</legend>
			<?php print '<a href="adminmenuroles.php?task=newmenu'.'">';?><p class="button" style="text-align:center;width:60px;"> منو جديد</p></a>
				 <table class="menu" align='center' border='1'>              
                   <thead>
				 
				   
                        <tr>

                            <th  
                           	<span class="f14_font" > رديف  </span> </th>
							<th 
                           	<span class="f14_font"> نام  </span> </th>
							<th  
                            <span class="f14_font" > لينك </span></th>
						    <th <?php echo $hide; ?>
                            <span class="f14_font">شناسه والد</span> </th>
							<th  
                            <span class="f14_font">ترتيب</span> </th>
							<th  
                            <span class="f14_font"> منتشر شده </span></th>
							<th  <?php echo $hide; ?>
                            <span class="f14_font"> شناسه </span></th>
						    <th  
                            <span class="f14_font"> حذف </span></th>
                           
                        </tr>
                       </thead> 
                       <?php 
					   $sql="SELECT * FROM menu $str parent=0 order by ordering";
						$result = mysql_query($sql);
                        //print $sql;   
				while($row = mysql_fetch_assoc($result)){
			
               		    $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							<td
							<span class="f9_font<?php echo $b; ?>"><?php print '<a href="adminmenuroles.php?task=detailmenu&idm='.$row['MenuID'].'">';?> <?php echo '&nbsp;'.$row['name']; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>" style="text-align:left; font-family:arial;">  <?php echo $row['link']; ?> </span> </td>
                            
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo '-'; ?> </span> </td>
							<td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['ordering']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>">  <?php if ($row['published']==1) echo 'منتشر شده '; else echo 'منتشر نشده'; ?> </span> </td>
							<td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['MenuID']; ?> </span> </td>
							<td <td <?php echo $hide; ?>
							<span class="f13_font<?php echo $b; ?>"><a onClick="alert('آيا از حذف منو مطمئن هستيد؟')" href="<?php echo 'adminmenuroles.php?task=deletemenu&idm='.$row['MenuID'].'">';?><img style ="width: 15px;" src="../img/delete.png" title=" حذف گروه" ></a></span> </td>
							</tr>
					<?php
					 $parent=$row['MenuID'];			
					 $sqlchild="SELECT * FROM menu 
					 $str parent='$parent' order by ordering";
						$resultchild = mysql_query($sqlchild);
					  while($rowchild = mysql_fetch_assoc($resultchild)){
					    $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							
                            <td
							<span class="f9_font<?php echo $b; ?>"> <?php echo '<a href="adminmenuroles.php?task=detailmenu&idm='.$rowchild['MenuID'].'">';?> <?php echo '&nbsp;---&nbsp;'.$rowchild['name']; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>" style="text-align:left; font-family:arial;">  <?php echo $rowchild['link']; ?> </span> </td>
                            
                            <td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo  $rowchild['parent'];; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $rowchild['ordering']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>">  <?php if ($rowchild['published']==1) echo 'منتشر شده '; else echo 'منتشر نشده'; ?> </span> </td>
							<td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo $rowchild['MenuID']; ?> </span> </td>
							<td
							<span class="f13_font<?php echo $b; ?>"><a onClick="alert('آيا از حذف منو مطمئن هستيد؟')" href="<?php echo 'adminmenuroles.php?task=deletemenu&idm='.$rowchild['MenuID'].'">';?><img style ="width: 15px;" src="../img/delete.png" title=" حذف گروه" ></a></span> </td>
							</tr>
							<?php 
							
							$parentchild=$rowchild['MenuID'];			
					    $sqlchild2="SELECT * FROM menu $str
						parent='$parentchild' order by ordering";
						$resultchild2 = mysql_query($sqlchild2);
					  while($rowchild2 = mysql_fetch_assoc($resultchild2)){
					    $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							
                            <td
							<span class="f9_font<?php echo $b; ?>"> <?php print '<a href="adminmenuroles.php?task=detailmenu&idm='.$rowchild2['MenuID'].'">';?> <?php echo '&nbsp;------&nbsp;'.$rowchild2['name']; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>" style="text-align:left; font-family:arial;">  <?php echo $rowchild2['link']; ?> </span> </td>
                            
                            <td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo  $rowchild2['parent'];; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $rowchild2['ordering']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>">  <?php if ($rowchild2['published']==1) echo 'منتشر شده '; else echo 'منتشر نشده'; ?> </span> </td>
							<td <?php echo $hide; ?>
							<span class="f10_font<?php echo $b; ?>">  <?php echo $rowchild2['MenuID']; ?> </span> </td>
							<td
							<span class="f13_font<?php echo $b; ?>"><a onClick="alert('آيا از حذف منو مطمئن هستيد')" href="<?php echo 'adminmenuroles.php?task=deletemenu&idmdelete='.$rowchild2['MenuID'].'">';?><img style ="width: 15px;" src="../img/delete.png" title=" حذف گروه" ></a></span> </td>
							</tr>
							<?php 
					} 
					} 
				}
                   ?>
                </table>
			</fieldset>
              <?php } ?>    
              
                   
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
