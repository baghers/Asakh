<?php

/*

insert/creditsource_detail_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/creditsource.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{

    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $TBLNAME=$linearray[0];//نام جدول
        $TBLTITLE=$linearray[1];//عنوان جدول
        $TBLID=$linearray[2];//شناسه جدول
		
    $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) not in ('OSTAN','SAVETIME','SAVEDATE','CLERKID', concat( upper( TABLE_NAME ) , 'ID') );";
 					   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        //print $query;
    $fields="";
    $fieldcnt=0;
	if ($login_RolesID==1) $end=24;else $end=22;
    while($row = mysql_fetch_assoc($result))
    {
        $fieldcnt++;
        if ($fieldcnt==1) $fields.=$row['COLUMN_NAME']; else $fields.=",".$row['COLUMN_NAME'];
		if ($fieldcnt>=$end) break;
    }
    $fieldsarray = explode(',',$fields);

//print_r ($fieldsarray);
    $query = "SELECT * FROM $TBLNAME WHERE ".$TBLNAME."ID  ='$TBLID';";
  //print $query;
    					   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $resquery = mysql_fetch_assoc($result);
	    
}

$register = false;

if ($_POST)
{
    
    $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME) not in ('OSTAN','SAVETIME','SAVEDATE','CLERKID', concat( upper( TABLE_NAME ) , 'ID') );";
   // print $query;
    //exit;
   					   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    
    $fields="";
    $fieldcnt=0;
    $query = "UPDATE $_POST[TBLNAME] SET 
	 		ostan = '" . $login_ostanId . "', 
            SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', 
    		ClerkID = '" . $login_userid . "'";

  			
    while($row = mysql_fetch_assoc($result))
    {
	    $query.=",$row[COLUMN_NAME]='".str_replace(",","",$_POST[$row['COLUMN_NAME']])."'";    
    }    
 //print $query;
    
	$query .= " WHERE ".$_POST['TBLNAME']."ID ='$_POST[TBLID]';";
  //exit;
 	
   					   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $register = true;
    
    //print $query;
    //exit;
            
	$TBLNAME = $_POST["TBLNAME"];
    $TBLID= $_POST["TBLID"];
	$ID = $TBLNAME.'_'.$_POST["TBLTITLE"];
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print 'ویرایش اعتبارات' ?></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
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
                        header("Location: creditsource.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                        
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
                <form action="creditsource_detail_edit.php" method="post">
                   <table width="600" align="center" class="form">
				    <tbody>
					     <h1 align="center">ویرایش اعتبارات (مبالغ به ریال-درصد به اعشار)</h1>
                       
					<div style = "text-align:left;"><a  href=<?php print "creditsource.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    <?php 
					$fieldsarray1=array('عنوان اعتبار','سال','شماره قرارداد','مبلغ قرارداد','مبلغ واریزی','خالص واریزی','مبلغ برگشت به خزانه','توضیحات','بلاعوض قطره ای','درصد','بلاعوض بارانی','درصد','بلاعوض کم فشار',
					'درصد','بلاعوض تجمیع','درصد','بلاعوض سنتر/لینیر','درصد','بلاعوض زیرسطحی','درصد','ترتیب','صندوق/بانک','درصد تنزیل لوله','درصد تنزیل  اجرا');
					//print_r ($fieldsarray);

                        foreach ($fieldsarray as $i => $value) 
                        {
						$valueid=$value;
						if ($i==3 || $i==4 || $i==5 || $i==6 || $i==8 || $i==10 || $i==12 || $i==14 || $i==16 || $i==18)	
							$value=number_format($resquery[$value]);else $value=$resquery[$value];	
               
						 	echo "
	                                <td style='white-space:nowrap;' colspan='6' class='label'>$fieldsarray1[$i]:</td>
                                    <td  class='data'><input name='$valueid' type='text' class='textbox' id='$valueid' value='$value' size='40' /></td>
							";
					    if ($i%2==1){echo "</tr>";}
								
								
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
                     
                     
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                     </tr>
                    </tfoot>
                   </table>
				  			   <?php print "بانک=1 صندوق=2";?>
     
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