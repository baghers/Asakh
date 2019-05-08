<?php

/*

//appinvestigation/guarantee_level2_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/guarantee_level2_list.php
 -
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

$prjtypeid=is_numeric($_GET["prjtypeid"]) ? intval($_GET["prjtypeid"]) : 0;//نوع پروژه
$uid=$_GET["uid"];

if (! $_POST)
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

    $linearray = explode('_',$ids);
    $type=$linearray[0];//نوع
    $ID=$linearray[1];//شناسه شرکت
    
    if ($type==1)//فروشندگان
    {
        if ($prjtypeid>0)//آبرسانی
        {
            /*
            producers جدول تولید کننده
            producersID شناسه تولید کننده
            Title عنوانن شرکت
            guarantee جدول تضامین
            guaranteeUp سقف ضمانت
            guaranteepayval مقدار ضمانت سپرده شده
            guaranteeNo شماره ضمانت
            guaranteeDescription شرح
            guaranteeExpireDate تاریخ اعتبار
            prjtypeid نوع پروژه
            */
            $sql=" SELECT '$type' type,producersID ID,producers.Title,guarantee.guaranteeUp,guarantee.guaranteepayval,guarantee.guaranteeNo,
            guarantee.guaranteeDescription,guarantee.guaranteeExpireDate FROM producers  
            left outer join guarantee on producers.producersid=CoID and CoType=1 and prjtypeid='$prjtypeid'
                where producers.producersid='$ID' ";
        }
        else $sql = "SELECT '$type' type,ProducersID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeDescription,guaranteeExpireDate FROM producers 
                where ProducersID='$ID'";
    }
    else if ($type==2)//شرکت های مجری
    {
        if ($prjtypeid>0)//آبرسانی
        {
            /*
            operatorco مجریان
            operatorcoID شناسه شرکت 
            Title عنوانن شرکت
            guarantee جدول تضامین
            guaranteeUp سقف ضمانت
            guaranteepayval مقدار ضمانت سپرده شده
            guaranteeNo شماره ضمانت
            guaranteeDescription شرح
            guaranteeExpireDate تاریخ اعتبار
            */
            $sql=" SELECT '$type' type,operatorcoID ID,operatorco.Title,guarantee.guaranteeUp,guarantee.guaranteepayval,guarantee.guaranteeNo,
            guarantee.guaranteeDescription,guarantee.guaranteeExpireDate FROM operatorco  
            left outer join guarantee on operatorco.operatorcoid=CoID and CoType=2 and prjtypeid='$prjtypeid'
                where operatorco.operatorcoid='$ID' ";
        }
        else $sql = "SELECT '$type' type,operatorcoID ID,Title,guaranteeUp,guaranteepayval,guaranteeNo,guaranteeDescription,guaranteeExpireDate FROM operatorco 
                where operatorcoID='$ID'";
    }
  
							try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $resquery = mysql_fetch_assoc($result);
	$type= $resquery["type"];
	$ID = $resquery["ID"];
	$Title = $resquery["Title"];
	$guaranteeUp = number_format($resquery["guaranteeUp"]);
	$guaranteepayval = number_format($resquery["guaranteepayval"]);
	$guaranteeNo = $resquery["guaranteeNo"];
	$guaranteeDescription = $resquery["guaranteeDescription"];
    
	$guaranteeExpireDate = $resquery["guaranteeExpireDate"];   
}

$register = false;

if ($_POST){
    $uid=$_POST["uid"];
    $prjtypeid=$_POST["prjtypeid"];
	$type= $_POST["type"];
	$ID = $_POST["ID"];
	$guaranteeUp = str_replace(',', '', $_POST["guaranteeUp"]) ;
	$guaranteepayval = str_replace(',', '', $_POST["guaranteepayval"]);
	$guaranteeNo = $_POST["guaranteeNo"];
	$guaranteeDescription = $_POST["guaranteeDescription"];
    
	$guaranteeExpireDate = $_POST["guaranteeExpireDate"];  
    
    if ($type==1)//فروشندگان
    {
        $TBL = "producers";
    }
    else if ($type==2)//شرکت های مجری
    {
        $TBL = "operatorco";
    }
    /*else if ($type==3)//کشاورزان
    {
        $TBL = "applicantmaster";
    }*/
    
    if ($prjtypeid>0)
    {
            /*
            Title عنوانن شرکت
            guarantee جدول تضامین
            guaranteeUp سقف ضمانت
            guaranteepayval مقدار ضمانت سپرده شده
            guaranteeNo شماره ضمانت
            guaranteeDescription شرح
            guaranteeExpireDate تاریخ اعتبار
            */        
        $query = "SELECT guaranteeID FROM guarantee  
        where CoID='$ID' and CoType='$type' and prjtypeid='$prjtypeid' ";
        $result = mysql_query($query);
       	$row = mysql_fetch_assoc($result);
        $guaranteeID=$row['guaranteeID'];
        if ($guaranteeID>0)//udate
        {
      		$query = "
    		UPDATE guarantee SET
    		guaranteeUp = '" . $guaranteeUp . "', 
    		guaranteepayval = '" . $guaranteepayval . "', 
    		guaranteeNo = '" . $guaranteeNo . "',
    		guaranteeDescription = '" . $guaranteeDescription . "',
            guaranteeExpireDate='" . $guaranteeExpireDate . "',
    		SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', 
    		ClerkID = '" . $login_userid . "'
    		WHERE guaranteeID = $guaranteeID and CoType='$type' and prjtypeid='$prjtypeid';";
            $result = mysql_query($query);
            $register = true;
        }
        else
        {
      		$query = "
            insert into guarantee (`CoType`, `CoID`, `prjtypeid`, `guaranteeUp`, `guaranteepayval`, `guaranteeNo`, `guaranteeExpireDate`,
             `guaranteeDescription`, `SaveDate`, `SaveTime`, `ClerkID`)
    		values ('$type','$ID','$prjtypeid','$guaranteeUp','$guaranteepayval','$guaranteeNo','$guaranteeExpireDate','$guaranteeDescription'
            ,'" . date('Y-m-d') . "', '" . date('Y-m-d H:i:s') . "','$login_userid');";
            $result = mysql_query($query);
            $register = true;            
        }
    }
    else
    {
		$query = "
		UPDATE $TBL SET
		guaranteeUp = '" . $guaranteeUp . "', 
		guaranteepayval = '" . $guaranteepayval . "', 
		guaranteeNo = '" . $guaranteeNo . "',
		guaranteeDescription = '" . $guaranteeDescription . "',
        guaranteeExpireDate='" . $guaranteeExpireDate . "',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE ".$TBL."ID = $ID;";
       
        $register = true;
        //print $query;
        //exit;        
    }

						try 
							  {		
								 $result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	
    
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت تضمین</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#guaranteeExpireDate, #simpleLabel").persiandatepicker();   
				
            });
            
            
            
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
        function selectpage()
{
    vprjtypeid=0;
    if (document.getElementById('prjtypeid'))
    {
        vprjtypeid=document.getElementById('prjtypeid').value;
    }
    
    
    window.location.href ='?uid=' +document.getElementById('uid').value+'&prjtypeid=' + vprjtypeid;
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
                        header("Location: guarantee_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$type.rand(10000,99999));
                        
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
                <form action="guarantee_level2_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php print 
                    "<div style = \"text-align:left;\"><a  href='guarantee_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).$type.rand(10000,99999).
                    "'><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";
                    
                    $query="select Title _key, prjtypeID _value from prjtype
    order by  _key COLLATE utf8_persian_ci";
                    
    $IDs = get_key_value_from_query_into_array($query);
    					try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	
    print select_option('prjtypeid',' پروژه',',',$IDs,0,'','','1','rtl',0,'',$prjtypeid,"onChange='selectpage()'",'135');
    
    
                    
                     ?>
                            
                     <tr>
                      <td width="20%" class="label">تضمین برای:</td>
                      <td width="80%" class="data"><input name="Title" type="text" readonly class="textbox" id="Title" value="<?php echo trim($Title); ?>" size="50" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">سقف تضمین(ریال):</td>
                      <td class="data"><input  onKeyUp="convert('guaranteeUp')" name="guaranteeUp" type="text" class="textbox" id="guaranteeUp" value="<?php echo $guaranteeUp; ?>"  size="15" maxlength="100" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">مبلغ تضمین دریافتی(ریال):</td>
                      <td class="data"><input onKeyUp="convert('guaranteepayval')" name="guaranteepayval" type="text" class="textbox" id="guaranteepayval" value="<?php echo $guaranteepayval; ?>"  size="15" maxlength="100" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">شماره ضمانت نامه:</td>
                      <td class="data"><input name="guaranteeNo" type="text" class="textbox" id="guaranteeNo" value="<?php echo $guaranteeNo; ?>"  size="15" maxlength="100" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">توضیحات:</td>
                      <td class="data"><input name="guaranteeDescription" type="text" class="textbox" id="guaranteeDescription" value="<?php echo $guaranteeDescription; ?>"  size="15" maxlength="100" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">تاریخ انقضاء:</td>
                      <td class="data"><input name="guaranteeExpireDate" type="text" class="textbox" id="guaranteeExpireDate" value="<?php echo $guaranteeExpireDate; ?>"  size="15" maxlength="100" /></td>
                     </tr>
                     
                     <tr>
                      <td class="data"><input name="ID" type="hidden" class="textbox" id="ID"  value="<?php echo $ID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                      
                    <input class='no-print' name='uid' type='hidden' class='textbox' id='uid'  value="<?php echo $uid ; ?>"  /> 
         
                     <tr>
                      <td class="data"><input name="type" type="hidden" class="textbox" id="type"  value="<?php echo $type ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح" /></td>
                     </tr>
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