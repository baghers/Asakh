<?php 

/*
codding/codding4table_detail_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 codding/codding4table_detail.php

*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if (! $_POST)
{

    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $TBLNAME=$linearray[0];//نام جدول
        $TBLTITLE=$linearray[1];//عنوان فارسی جدول
        $TBLID=$linearray[2];//شناسه جدول
        $tblkey=$linearray[3];//کلید جدول
        $tblval=$linearray[4];//مقدار جدول
  
    if ($login_RolesID!=19 && $backdoor==0 && !in_array($TBLNAME,array("applicantsurvey","applicantsystemtype","applicantwsource","appsubprj"))) header("Location: ../login.php");
    
    if ($tblkey!='')
    $query = "  SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) not in ('SAVETIME','".
                strtoupper($tblkey)."','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
    else
    $query = "  SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) not in ('SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
                
					
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
        //exit;
        
    $fields="";
    $fieldcnt=0;
    while($row = mysql_fetch_assoc($result))
    {
        $fieldcnt++;
        if ($fieldcnt==1) 
        {
            $fields.=$row['COLUMN_NAME']; 
            if ($row['COLUMN_COMMENT']!='') $captions=$row['COLUMN_COMMENT']; else $captions=$row['COLUMN_NAME'];   
        }
        else
        {
            $fields.=",".$row['COLUMN_NAME'];
            if ($row['COLUMN_COMMENT']!='') $captions.=",".$row['COLUMN_COMMENT']; else $captions.=",".$row['COLUMN_NAME'];
        }
         
    }
    $fieldsarray = explode(',',$fields);
    $captionsarray = explode(',',$captions);


        
    $query = "SELECT * FROM $TBLNAME WHERE ".$TBLNAME."ID  ='$TBLID';";
    //print $query.$ID;
   
			 try 
			  {		
				       $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    $resquery = mysql_fetch_assoc($result);
    
}

$register = false;

if ($_POST)
{
    if ($_POST['TBLNAME']=='applicantwsource')
    {
        if (($_FILES["file1"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
            exit;
        }
    }
    
    if ($_POST['tblkey']!='')
    $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME) not in ('".
                strtoupper($_POST['tblkey'])."','SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
    else
    $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME) not in (
                'SAVETIME','SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";
                
   
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
    
    //exit;
    
    $fields="";
    $fieldcnt=0;
	$Saving='';if ($_POST['TBLNAME']=='clerk') $Saving='Saving';
    $query = "UPDATE $_POST[TBLNAME] SET 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', 
    		ClerkID$Saving = '" . $login_userid . "'";
    while($row = mysql_fetch_assoc($result))
    {
        $query.=",$row[COLUMN_NAME]='".$_POST[$row['COLUMN_NAME']]."'";    
    }    
    $query .= " WHERE ".$_POST['TBLNAME']."ID ='$_POST[TBLID]';";
    
  //  print $query;
   // exit;
    	 try 
			  {		
				       $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    
    $register = true;
    
    //print $query;
    //exit;
            
	$TBLNAME = $_POST["TBLNAME"];
    $TBLID= $_POST["TBLID"];
	$ID = $TBLNAME.'_'.$_POST["TBLTITLE"].'_0_'.$_POST['tblkey'].'_'.$_POST['tblval'];;
    
    if ($_POST['TBLNAME']=='applicantwsource')
    {
            
        if (!($_FILES["file1"]["error"] > 0)) 
        {
            $ext = end((explode(".", $_FILES["file1"]["name"])));
            $attachedfile=$TBLID.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/parvane/';
            $handler = opendir($directory);
            while ($file = readdir($handler)) 
            {
                // if file isn't this directory or its parent, add it to the results
                if ($file != "." && $file != "..") 
                {
                    $linearray = explode('_',$file);
                    $ID=$linearray[0];
                    if (($ID==$TBLID) )
                        unlink($directory.$file);
                }
            }   
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/parvane/" .$attachedfile);   
        }
    }  
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print 'ویرایش '.$TBLNAME; ?></title>
<meta http-equiv="X-Frame-Options" content="deny" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
<script type="text/javascript">
jQuery(function($) {
    $("input").on('focus', function(e) {
        $(this).animate({ borderColor: "#0e7796", boxShadow: '0 0 5px 3px rgba(100,100,200,0.4)' }, 'slow');
    }).on('blur', function(e) {
        $(this).animate({ borderColor: "#ccc", boxShadow: '0px 0px 0px #fff' }, 'slow');
    });
});

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
            if (inputs[index].value.length<=0 && inputs[index].id!='file1'  && inputs[index].type!='hidden')
            {
                document.getElementById(inputs[index].id).focus();
                alert('لطفا اطلاعات مورد نیاز را کامل وارد نمایید');
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
						if ($TBLNAME=="operatorco" || $TBLNAME=="designerco") { 
					    header("Location: ../members_".$TBLNAME ."s.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                    	} 
						else
					     header("Location: codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
					 	.rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                        
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
                <form id='register' name='register' action="codding4table_detail_edit.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <h1 align="center"><?php print $TBLTITLE; ?></h1>
					<?php if ($TBLNAME=="operatorco" || $TBLNAME=="designerco") { ?>
					 <div style = "text-align:left;"><a  href=<?php print "../members_".$TBLNAME ."s.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
					<?php } else if  ($TBLNAME=="producers")  { ?>
					 <div style = "text-align:left;"><a  href=<?php print "../members_".$TBLNAME .".php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
					 <?php } else { ?>
                    <div style = "text-align:left;"><a  href=<?php print "codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
					.rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                         
                    <?php }
	                if( $login_RolesID!=1) $hide="style=display:none"; else $hide="";  
                        
					    foreach ($fieldsarray as $i => $value) 
                        {
                         if ($value=="Disabled") $hidetr=$hide; else $hidetr="";					
                            
                            
                            if (substr($value,strlen($value)-2,2)=="ID" || substr($value,strlen($value)-2,2)=="id")
                            {
                                $cobotbl=substr($value,0,strlen($value)-2);
                                $query="SELECT $value as _value,Title as _key from  ".strtolower($cobotbl);
					            $ID = get_key_value_from_query_into_array($query);
					            echo "<tr>".select_option($value,$captionsarray[$i],',',$ID,0,'','','1','rtl',0,'',$resquery[$value],'','200')."</tr>";
                       
                            }  
                            else
                            echo " <tr $hidetr>
                                    <td  class='label'>".$captionsarray[$i]."</td>
                                    <td  class='data'><input name='$value' type='text' class='textbox' id='$value' value='$resquery[$value]' size='35' /></td>
                                </tr>";
								
                        }
                        
                        if ($TBLNAME=='applicantwsource')
                        {
                            $fstr1="";
                            $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/parvane/';
                            $handler = opendir($directory);
                            while ($file = readdir($handler)) 
                            {
                                // if file isn't this directory or its parent, add it to the results
                                if ($file != "." && $file != "..") 
                                {
                                    $linearray = explode('_',$file);
                                    $ID=$linearray[0];
                                    if (($ID==$TBLID) )
                                        $fstr1="<td><a href='../../upfolder/parvane/$file' target='_blank' >
                                        <img name='file1img' id='file1img' style = 'width: 30px;' src='../img/accept.png' title='اسکن پروانه' ></a></td>";
                                    }
                            }
                            
                            
                            echo "<tr>
                            <td colspan='1' class='label'>اسکن پروانه(حد اکثر 200 کیلوبایت)</td>
                             
                             <td colspan='1' class='data'><input type='file' name='file1' id='file1' value='0' </td> 
                             $fstr1</tr>";
                         
                        }
                         
                        
                     ?>     
                            
                      <tr>
                      <td class="data"><input name="TBLTITLE" type="hidden" class="textbox" id="TBLTITLE"  value="<?php echo $TBLTITLE ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="TBLNAME" type="hidden" class="textbox" id="TBLNAME"  value="<?php echo $TBLNAME ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="TBLID" type="hidden" class="textbox" id="TBLID"  value="<?php echo $TBLID ; ?>"  size="30" maxlength="15" /></td>
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