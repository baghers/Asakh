<?php 

/*

codding/codding4table_detail_new.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding4table_detail.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php



if (! $_POST)//در صورتی که دکمه سابمیت کلیک نشده باشد
{
$ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        
       // print_r($linearray);
        
        $TBLNAME=$linearray[0];//نام جدول
        $TBLTITLE=$linearray[1];//عنوان فارسی جدول
        $TBLID=$linearray[2];//شناسه جدول
        $tblkey=$linearray[3];//کلید اصلی جدول
        $tblval=$linearray[4];//مقدار کلید اصلی جدول
    
    $permitrolsid=array("1","18","19");// 
    
    /*
    1 مدیر پیگیری
    18 مدیر آب و خاک
    19 مدیریت پرونده ها
    این نقش ها امکان ثبت در تمام جداول را دارا می باشند و در غیر ان صورت
    فقط امکان ثبت در جداول زیر را دارند
    applicantsurvey جدول تفکیک سطح
    applicantsystemtype الگوی کشت پروژه ها
    applicantwsource منابع آبی پروژه ها
    appsubprj زیرپروژه ها
    */
    if (!in_array($login_RolesID, $permitrolsid) && !in_array($TBLNAME,array("applicantsurvey","applicantsystemtype","applicantwsource","appsubprj")))
    header("Location: ../login.php");
    
    //پرس و جوی استخراج نام ستون های جدولی که می خواهیم در آن درج انجام دهیم 
    if ($tblkey!='')//در صورتی که جدول کلید اصلی داشته باشد از ستون هایی که از کاربر می خواهیم اطلاعات بگیریم حذف می شود
    $query = "  SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) not in ('SAVETIME','".
                strtoupper($tblkey)."','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
    else
    
    $query = "  SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) 
                not in ('SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
    try 
      {		
        $result = mysql_query($query);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }
  
    
        //print $query;
    $fields="";
    $fieldcnt=0;
    while($row = mysql_fetch_assoc($result))//در این حلقه می خواهیم نام فارسی ستون را از توضیحات استخراج کرده و به نام هر ستون انتساب دهیم
    {
        $fieldcnt++;
        if ($fieldcnt==1) 
        {
            $fields.=$row['COLUMN_NAME'];// نام های لاتین ستون ها  که با کاما جدا شده
            if ($row['COLUMN_COMMENT']!='') $captions=$row['COLUMN_COMMENT']; else $captions=$row['COLUMN_NAME'];// عنوان فارسی ستون ها که با کاما جدا شده   
        }
        else
        {
            $fields.=",".$row['COLUMN_NAME'];
            if ($row['COLUMN_COMMENT']!='') $captions.=",".$row['COLUMN_COMMENT']; else $captions.=",".$row['COLUMN_NAME'];
        }
    }
    $fieldsarray = explode(',',$fields);//آرایه نام ستون ها
    $captionsarray = explode(',',$captions);//آرایه عنوان فارسی ستون ها

    //پرس و جوی استخراج سریال جدید جدول
    
    $query = "SELECT max(CAST(Code AS UNSIGNED))+1 maxcode FROM $TBLNAME ";
    //print $query;	
    try 
      {		
        $result = mysql_query($query);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }
    $row = mysql_fetch_assoc($result);  		
    if ($row['maxcode']>0)
		  $Code = $row['maxcode'];
    else $Code = 1;//سریال
    
}

$register = false;//متغیری که تعیین می کند درج انجام شده یا خیر

if ($_POST)//در صورتی که دکمه سابمیت کلیک شده باشد
{
    $SaveTime=date('Y-m-d H:i:s');//ساعت و تاریخ فعلی
    if ($_POST['tblkey']!='')//در صورتیکه جدول دارای کلید باشد
    {
        //پرس و جوی استخراج نام جداول جهت ایجاد رشته درج
        $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME) not in 
                    ('".strtoupper($_POST['tblkey'])."','SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
        try 
          {		
            $result = mysql_query($query);
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
        
        
        
    	$TBLNAME = $_POST["TBLNAME"];//نام جدول
    	$ID = $TBLNAME.'_'.$_POST["TBLTITLE"].'_0_'.$_POST['tblkey'].'_'.$_POST['tblval'];//اطلاعات مورد نیاز برای ایجاد لینک بازگشت
        
        $fields="";//ستون ها
        $fieldcnt=0;//تعداد ستون ها
        $queryvals="'$SaveTime','".date('Y-m-d')."','$login_userid'";//رشته ستون های مورد استفاده در رشته درج
        while($row = mysql_fetch_assoc($result))
        {
            $queryvals.=",'".$_POST[$row['COLUMN_NAME']]."'";//بخش اول رشته درج  مربوط به مقادیر  
            $fields.=",".$row['COLUMN_NAME'];//بخش دوم رشته درج مربوط به نام ستون ها
        } 
        //رشته درج
        try 
          {		
            mysql_query("INSERT INTO $TBLNAME($_POST[tblkey],SaveTime,SaveDate,ClerkID $fields) VALUES('$_POST[tblval]',$queryvals);"); 
            $register = true;
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }    
    }
    else
    {
         //پرس و جوی استخراج نام جداول جهت ایجاد رشته درج
            $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME) not in 
                ('SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
        
         try 
          {		
            $result = mysql_query($query);
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          } 
        
    	$TBLNAME = $_POST["TBLNAME"];//نام جدول
    	$ID = $TBLNAME.'_'.$_POST["TBLTITLE"].'_0_'.$_POST['tblkey'].'_'.$_POST['tblval'];//اطلاعات مورد نیاز برای ایجاد لینک بازگشت
        
        $fields="";//ستون ها
        $fieldcnt=0;//تعداد ستون ها
        $queryvals="'$SaveTime','".date('Y-m-d')."','$login_userid'";//رشته ستون های مورد استفاده در رشته درج
        while($row = mysql_fetch_assoc($result))
        {
            $queryvals.=",'".$_POST[$row['COLUMN_NAME']]."'";//بخش اول رشته درج  مربوط به مقادیر     
            $fields.=",".$row['COLUMN_NAME'];//بخش دوم رشته درج مربوط به نام ستون ها
        } 
        //رشته درج
        try 
          {		
            mysql_query("INSERT INTO $TBLNAME(SaveTime,SaveDate,ClerkID $fields) VALUES($queryvals);"); 
            $register = true;
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          } 
          
        
    }

    if ($_POST['TBLNAME']=='applicantwsource')
    {
        //در صورتی که جدول منابع آبی بود بررسی اندازه فایل باگذاری شده انجام می شود
    
        if (($_FILES["file1"]["size"] / 1024)>300)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
            exit;
        }
    
        //استخراج شناسه درج شده جهت استفاده در نام فایل بارگذاری
        $query = "SELECT applicantwsourceID FROM applicantwsource where applicantwsourceID = last_insert_id() and SaveTime='$SaveTime' 
            and ClerkID='$login_userid'";
        
        try 
          {		
            $result = mysql_query($query);
  		    $row = mysql_fetch_assoc($result);
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
          
        
            
        if (!($_FILES["file1"]["error"] > 0)) //بارگذاری فایل
        {
            $ext = end((explode(".", $_FILES["file1"]["name"])));
            $attachedfile=$row['applicantwsourceID'].'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            
        
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/parvane/" .$attachedfile);   
        }
    }    
	
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print 'ثبت '.$TBLTITLE; ?></title>
<meta http-equiv="X-Frame-Options" content="deny" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    <script type="text/javascript">

function CheckForm()
{
    
 	
    if (document.getElementById("file1"))
    {
		
        if ( (!(document.getElementById('file1').value != "">0)) && (!(document.getElementById("file1img"))))
        {
                alert('لطفا اسکن فایل را انتخاب نمایید!');return false;
        } 
        
        var inputs, index;

        inputs = document.getElementsByTagName('input');
        for (index = 0; index < inputs.length; ++index) 
        {
            if (inputs[index].value.length<=0 && inputs[index].id!='file1' && inputs[index].type!='hidden')
            {
                document.getElementById(inputs[index].id).focus();
                alert('لطفا اطلاعات مورد نیاز را کامل وارد نمایید'+inputs[index].id);
                return false;
            }
        }
    
               
    }

    return true;  
}
</script>
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Code = "";
						$YearID = "";
                        header("Location: codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="codding4table_detail_new.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <h1 align="center"><?php print $TBLTITLE; ?></h1>
                    <div style = "text-align:left;"><a  href=<?php print "codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                       
                    <?php
                        
                        foreach ($fieldsarray as $i => $value) 
                        {
                            if (strtoupper($value)=='CODE')
                            echo " <tr>
                                    <td  class='label'>$value:</td>
                                    <td  class='data'><input name='$value' type='text' class='textbox' id='$value' value='$Code'  size='100' /></td>
                                </tr>";
                            else if (substr($value,strlen($value)-2,2)=="ID" || substr($value,strlen($value)-2,2)=="id")
                            {
                                $cobotbl=strtolower(substr($value,0,strlen($value)-2));
                                $query="SELECT $value as _value,Title as _key from  ".$cobotbl;
								$ID = get_key_value_from_query_into_array($query);
								
					            echo "<tr>".select_option($value,$captionsarray[$i],',',$ID,0,'','','1','rtl',0,'',0,'','200')."</tr>";
                       
                            }  
                            else    
                            echo " <tr>
                                    <td  class='label'>".$captionsarray[$i]."</td>
                                    <td  class='data'><input name='$value' type='text' class='textbox' id='$value'  size='35' /></td>
                                </tr>";    
                        }
						 
                        if ($TBLNAME=='applicantwsource')
                        echo "<tr>
                        <td colspan='1' class='label'>اسکن پروانه(حداکثر 200 کیلوبایت)</td>
                         
                         <td colspan='1' class='data'><input type='file' name='file1' id='file1'  value='0' </td> </tr>";
                     ?>
                     
                     
                      <tr>
                      <td class="data"><input name="idsource" type="hidden" class="textbox" id="idsource"  value="<?php echo $idsource ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                      <tr>
                      <td class="data"><input name="TBLTITLE" type="hidden" class="textbox" id="TBLTITLE"  value="<?php echo $TBLTITLE ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="TBLNAME" type="hidden" class="textbox" id="TBLNAME"  value="<?php echo $TBLNAME ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="tblkey" type="hidden" class="textbox" id="tblkey"  value="<?php echo $tblkey ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="tblval" type="hidden" class="textbox" id="tblval"  value="<?php echo $tblval ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
        
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                     </tr>
                     * تاریخ ها به صورت 9999/99/99 وارد شود
					 </br>
					 * کلیه فیلدها تکمیل شود
                    </tfoot>
                   </table>
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