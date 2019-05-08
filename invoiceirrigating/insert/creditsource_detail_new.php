<?php 

/*

insert/creditsource_detail_new.php

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
    
    
    $query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) not in ('SAVETIME','SAVEDATE','CLERKID', concat( upper( TABLE_NAME ) , 'ID') );";
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
    while($row = mysql_fetch_assoc($result))
    {
        $fieldcnt++;
        if ($fieldcnt==1) $fields.=$row['COLUMN_NAME']; else $fields.=",".$row['COLUMN_NAME'];
		if ($fieldcnt>=20) break;
    }
    $fieldsarray = explode(',',$fields);
//print_r ($fieldsarray);
    
    $query = "SELECT max(CAST(sortorder AS UNSIGNED))+1 maxcode FROM $TBLNAME ";
//    print $query;
						   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		$row = mysql_fetch_assoc($result);
		
    if ($row['maxcode']>0)
		  $Code = $row['maxcode'];
    else $Code = 1;
	
//	if ($login_RolesID==7 )  $showb=1;
//	if ($login_RolesID==16) $shows=1;

}

$register = false;

$shows=0;$showb=0;$creditbank=0;
if ($_POST['showb']=='on') $showb=1;
if ($_POST['shows']=='on') $shows=1;

if ($showb==1 || $shows==1)
{
	
	if ($login_RolesID==16 || $shows==1) $creditbank=2;
    if ($login_RolesID==7 || $showb==1)  $creditbank=1;
    
	$query = "  SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$_POST[TBLNAME]' and upper(COLUMN_NAME)
				not in ('OSTAN','SAVETIME','SAVEDATE','CLERKID','CREDITBANK', concat( upper( TABLE_NAME ) , 'ID') );";
    					   		try 
								  {		
									      $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

//	print $query;
    $TBLNAME = $_POST["TBLNAME"];
	$ID = $TBLNAME.'_'.$_POST["TBLTITLE"];
    $fields="";
    $fieldcnt=0;
    $queryvals="'$login_ostanId','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid','$creditbank'";
    while($row = mysql_fetch_assoc($result))
    {
        $queryvals.=",'".$_POST[$row['COLUMN_NAME']]."'";    
        $fields.=",".$row['COLUMN_NAME'];
    } 
    mysql_query("INSERT INTO $TBLNAME(ostan,SaveTime,SaveDate,ClerkID,creditbank $fields) VALUES($queryvals);"); 
    $register = true;
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title><?php print 'ثبت '.$TBLTITLE; ?></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
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
                <form action="creditsource_detail_new.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
					<tr>
				    <h1 align="center">ثبت اعتبارات جدید (مبالغ به ریال- درصد به اعشار)</h1>
					<div>
				 	  <?php $width="style = 'width: 4%;'"; 
					  if ($login_RolesID!=16 && $login_RolesID!=7){ 
								 
                         print "<td colspan='2'  class='label'>بانک</td>
                                <td style = 'width: 20%;' class='data'><input name='showb' type='checkbox' id='showb'";
                                if ($showb>0) echo 'checked';
                                 print " /></td>";
                         print "<td colspan='2' class='label'>صندوق</td>
                                <td style = 'width: 20%;' class='data'><input name='shows' type='checkbox' id='shows'";
                                if ($shows>0) echo 'checked';
                                 print " /></td> <td>";
                         $width="style = 'width: 30%;'";} ?>	 
                   <h1 align="left">
					<a  href=<?php print "creditsource.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
					.$ID.rand(10000,99999); ?>><img style = "width: 30px" src="../img/Return.png" title='بازگشت' ></a>
					</td>
					</div>
                       
                     </tr>
                    <?php
  $fieldsarray1=array(	"عنوان اعتبار","سال",
  
						"شماره قرارداد","مبلغ قرارداد",
						"مبلغ واریزی","خالص واریزی",
						"مبلغ برگشت به خزانه","توضیحات",
						"بلاعوض قطره ای","درصد",
						"بلاعوض بارانی","درصد",
						"بلاعوض کم فشار","درصد",
						"بلاعوض تجمیع","درصد",
						"بلاعوض سنتر/لینیر","درصد",
						"بلاعوض زیرسطحی","درصد",
						"استان","ترتیب","صندوق/بانک",
						
						);
                                          
                        foreach ($fieldsarray as $i => $value) 
                        {
							//if ($i==4 || $i==5 || $i==6 || $i==8 || $i==10 || $i==12 || $i==14 || $i==16)	$value=number_format($value);	
                            echo " 
                                    <td  style='white-space:nowrap;' colspan='2' class='label'>$fieldsarray1[$i]:</td>
                                    <td  class='data'><input placeholder='' name='$value' type='text' class='textbox' id='$value'  size='40' /></td>
                                ";    
						    
			//if (strtoupper($value)=='CONTRACTCOMMENT') echo "</tr>";else 
							if ($i%2==1){echo "</tr>";}
						}
                     ?>
                     
                     
                      <tr>
                      <td class="data"><input name="TBLTITLE" type="hidden" class="textbox" id="TBLTITLE"  value="<?php echo $TBLTITLE ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="TBLNAME" type="hidden" class="textbox" id="TBLNAME"  value="<?php echo $TBLNAME ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input onClick="return confirm('مطمئن هستید که ثبت شود ؟');" name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                     </tr>
                    </tfoot>
                   </table>
	              </form>
            </div>
			<!-- /content -->

            <!-- footer -->
			<?php include('../includes/footer.php'); 
			
			?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>