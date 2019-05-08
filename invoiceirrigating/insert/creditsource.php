<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); ?>
<?php 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
include('../includes/check_user.php'); ?>
<?php 
// توابع مرتبط با المنت های اچ تی ام ال صفحات
include('../includes/elements.php'); ?>
<?php

//if (!$login_is_admin && ($login_RolesID!=7 && $login_RolesID!=16)) header("Location: login.php");
if ($login_Permission_granted==0) header("Location: ../login.php");
 //نام جدول منابع اعتباری که در پرس و جو ها استفاده می شود
  $TBLNAME='creditsource';
  
  //عنوان گزارش
  $TITLE='اعتبارات (میلیون تومان)';
$per_page = 1000;

//استخراج شماره صفحه ای که در متغیر گت وجود دارد که در محدود کردن کوئری استفاده می شود
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

//استخراج تعداد ردیف های منابع اعتباری جهت تعیین اینکه گزارش چند صفحه دارد
$sql = "SELECT COUNT(*) as count FROM $TBLNAME;";
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------
//کوئری استخراج نام ستون های جدول که در ادامه جهت تولید کوئری استفاده می شود
$query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) 
			not in ('SAVETIME','SAVEDATE','CLERKID', concat( upper( TABLE_NAME ) , 'ID') );
			";
  //print $query;

$result = mysql_query($query);

$fields="";
$fieldcnt=0;
//حلقه ایجاد رشته ستون های مورد استفاده در گزارش بعدی
while($row = mysql_fetch_assoc($result))
{
    $fieldcnt++;
    if ($fieldcnt==1) $fields.=$row['COLUMN_NAME']; else $fields.=",".$row['COLUMN_NAME'];
    //حد اکثر تعداد ستون های قابل نمایش 21 ستون می باشد
    if ($fieldcnt>=21) break;
}
//تبدیل رشته به آرایه
$fieldsarray = explode(',',$fields);
//$showb=0;$shows=0;
if ($_POST)
{
    //$showb منابع اعتباری بانک نمایش داده شود
    if ($_POST['showb']=='on')   {$showb=1;}
    //$shows منابع اعتباری صندوق نمایش داده شود
	if ($_POST['shows']=='on')   {$shows=1;}
}

$creditbank='';
//اگر کاربر وارد شده شرکت مهندسین مشاور نباشد یعنی مدیر وارد شده و باید استان کاربر وارد شده نمایش داده شود
if ($login_designerCO==1) $log_ost=""; else $log_ost="and ostan=$login_ostanId";

//در صورتی که شرکت مهندسین مشاور باشد و استان از کومبوباکس انتخاب شده باشد به شرط کوئری اضافه می شود
$g1id=substr($_POST['g1id'],0,2);
if ($login_designerCO==1 && $g1id>0) $log_ost="and ostan=$g1id";

//$showb منابع اعتباری بانک نمایش داده شود
if ($login_RolesID==7 || $showb==1)  $creditbank='and creditbank in (1,12)';
//$shows منابع اعتباری صندوق نمایش داده شود
if ($login_RolesID==16 || $shows==1) $creditbank='and creditbank in (2,12)';

// هم منابع اعتباری بانک و هم منابع اعتباری صندوق نمایش داده شود
if ($showb==1 && $shows==1) $creditbank='and creditbank in (1,2,12)';
//print $creditbank;
//print $login_ostan;

//استخراج اطلاعات منابع اعتباری
$sql = "
SELECT ".$TBLNAME."ID,$fields
FROM $TBLNAME
where 1=1 $log_ost $creditbank
ORDER BY $fields LIMIT " . $start . ", " . $per_page . ";";
$result = mysql_query($sql);
//print $sql;


?>
<style>
.CSSTable table{ border-collapse: collapse;width:600px;height:auto;margin:10px;font-family:'B Nazanin'; }
	
.CSSTable tr:nth-child(odd){ background-color:#f5f5f5; }

.CSSTable tr:nth-child(even){ background-color:#ffffff; }

.CSSTable td { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:center;padding:7px;font-size:13px;font-weight:bold;color:#000000;}
.CSSTable td.t2 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:center;padding:4px;font-size:14px;font-weight:bold;color:#000000;}
.CSSTable td.t3 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:right;padding:4px;font-size:12px;font-weight:bold;color:#000000;}
.CSSTable tr:first-child td { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:10px;
	font-weight:bold;color:#000;}

.CSSTable tr:first-child td.t2 { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:16px;
	font-weight:bold;color:#000;}
	
.CSSTable tr:first-child:hover td{background-color:#2cb7b7;}

.CSSTable tr:first-child td:first-child{border-width:0px 0px 1px 0px;}

.CSSTable tr:first-child td:last-child{border-width:0px 0px 1px 1px;}

.f55_font{ text-align:right;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';}

.f9_font{ text-align:right;font-size:9.0pt;line-height:100%;font-family:'B Nazanin';}
  
</style>

<!DOCTYPE html>
<html>
<head>
  	<title><?php print $TITLE; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
   <!-- /scripts -->
   
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
			       <form action="creditsource.php" method="post">
            
                <table align="center" >
                    <tbody>
                        <tr>
                            <h1 align="center"><?php print $TITLE; ?>
                             <div style = "text-align:left;">
                   	  <?php $width="style = 'width: 4%;'"; 
        //اگر کاربر وارد شده مهندس مشاور طراح بود کومبوباکس انتخاب استان ظاهر شود					  
		 if ($login_designerCO==1)
            {
                //کوئری استان هایی که حد اقل یک کاربر برای آن ثبت شده جهت فیلتر نمودن گزارش
    			$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
    			inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
    			$ost
    			order by _key  COLLATE utf8_persian_ci";
    			$allg1id = get_key_value_from_query_into_array($sqlselect);
                print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,'','150');
		
	        }

				 
				 //اگر کاربر وارد شده بانک یا صندوق نباشند  چک باکسی اضافه می شود تا جهت فیلتر اضافه شود
					  if ($login_RolesID!=16 && $login_RolesID!=7)
                      { 
						//چاپ چک باکس بانک		 
                         print "<td colspan='2'  class='label'>بانک</td>
                                <td style = 'width: 10%;' class='data'><input name='showb' type='checkbox' id='showb'";
                                if ($showb>0) echo 'checked';
                                 print " /></td>";
                         //چاپ چک باکس صندوق
                         print "<td colspan='2' class='label'>صندوق</td>
                                <td style = 'width: 70%;' class='data'><input name='shows' type='checkbox' id='shows'";
                                if ($shows>0) echo 'checked';
                                 print " /></td>";
						?> <td ><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td> <td style = 'width: 25%;'>
                      <?php $width="style = 'width: 20%;'";} ?>	 
                            
							<a href=<?php print"creditsource_detail_new.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$TBLNAME.'_'.$TITLE.rand(10000,99999); ?>>
                             <img <?php print $width;?> src='../img/Actions-document-new-icon.png' title=' جدید '> </a>
                            </h1>
					
		
					
                          </div>
                          
                        </tr>
                   </tbody>
                </table>
				
<div class="CSSTable" >
	        		<table >
					
					
				<tr>
					<td colspan="8" class="t2"> اعتبارات </td>
                    <td	colspan="2" class="t2"> قطره ای</td>
                    <td	colspan="2" class="t2"> بارانی </td>
                    <td	colspan="2" class="t2"> کم فشار </td>
                    <td	colspan="2" class="t2">تجمیع </td>
					<td	colspan="2" class="t2">سنتر/لینیر</td>
					<td	colspan="2" class="t2">قطره ای زیرسطحی</td>
                   
                </tr>
        		<tr>
					<td class="t2"><?php echo str_replace(' ', '&nbsp;', '  عنوان اعتبارات '); ?></td>
                    <td class="t2">سال</td>
                    <td	class="t2"><?php echo str_replace(' ', '&nbsp;', ' شماره قرارداد'); ?></td>
                    <td	class="t2"><?php echo str_replace(' ', '&nbsp;', 'مبلغ قرارداد '); ?></td>
                    <td class="t2">   واریزی </td>
                    <td	class="t2"> خالص واریزی </td>
                    <td	class="t2">  برگشت خزانه </td>
                    <td	class="t2"> توضیحات </td>
                    <td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
                    <td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
                    <td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
					<td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
					<td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
					<td	class="t2"> سقف بلاعوض</td>
                    <td	class="t2"> درصد </td>
					
                </tr>
		
			

   			   <?php
                //چاپ ردیف های گزارش منابع اعتباری
                    while($row = mysql_fetch_assoc($result)){
                
                        $ID = $TBLNAME.'_'.$TITLE.'_'.$row[$TBLNAME.'ID'];
                        
                        $Code = $row['Code'];
                        $title = $row['Title'];
                        
                        $addfield="";
                        if (isset($row["addfield"]))
                        $addfield=$row["addfield"];
                
				?>
                        <tr>
                        <?php
                        foreach ($fieldsarray as $i => $value) 
						{ 
						 if ($i==3 || $i==4 || $i==5 || $i==6 || $i==8 || $i==10 || $i==12 || $i==14 || $i==16 || $i==18) 
							$rowval=$row[$value]/10000000; else $rowval=$row[$value];
						  echo "<td> $rowval</td>";
						  
						  }
						  
                         ?>
                            <td><a href="<?php print"creditsource_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                            <img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                            <td><a href="<?php print"creditsource_detail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>"
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"   >
                            <img style = 'width: 20px;' src='../img/delete.png' title='حذف'></a></td>
                        </tr>
				  <?php } ?>
                    </tbody>
                 </table>
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