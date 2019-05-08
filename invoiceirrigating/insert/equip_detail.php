<?php 

/*

insert/equip_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
appinvestigation/sendtoanjoman.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='appequip';//جدول تجهیز و برچیدن

if ($login_Permission_granted==0) header("Location: ../login.php");
if (! $_POST)
{
        $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $fehrestsmasterID=$linearray[1];//شناسه فهرست بها
    $type=$linearray[2];//نوع
    $fehrestsfaslsID=$linearray[3];//شناسه فصل
    
    $retids=$ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$fehrestsfaslsID;
    //exit();

/*
applicantmaster جدول مشخصاتطرح
ApplicantName عنوان پروژه
ApplicantMasterID شناسه طرح
*/
$sql = "SELECT ApplicantName FROM applicantmaster WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$ApplicantName = $count['ApplicantName'];
/*
fehrestsmaster فصل فهرست بها
fehrestsmasterID شناسه فصل
Title عنوان
*/
    $sql = "SELECT Title FROM fehrestsmaster WHERE fehrestsmasterID = '" . $fehrestsmasterID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsfaslsTitle = "تجهیز و برچیدن کارگاه";
        
    /*
    equip جدول تجهیز و برچیدن
    appequip تجهیزات هر طرح
    ApplicantMasterID شناسه طرح
    */    

    $sql = "SELECT equip.Code,equip.Title,equip.equipID,appequip.Price,appequip.appequipID FROM equip 
    left outer join appequip on equip.equipID=appequip.equipID and ApplicantMasterID = '".$ApplicantMasterID."'
    order by equip.Code
    " ;
    
	 	 					  	try 
								  {		
									    $resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //print $sql;

}
if ($_POST)
    { 
        $ApplicantMasterID = $_POST['ApplicantMasterID'];
        $retids=$_POST['retids'];
        $typhid=$_POST['typhid'];
        
        //print 'salam'.$ApplicantMasterID;
        
        $i=0;
        while (isset($_POST['appequipID'.++$i]))
        {
        	$appequipID = $_POST['appequipID'.$i];
            $equipID= $_POST['equipID'.$i];
            $Price= str_replace(',', '', $_POST['Price'.$i]);
            $Price= str_replace('-', '', $Price);
            $Price= str_replace('+', '', $Price);
                   	
        	if ($appequipID != 0)//update
            { 
        		$query = "
        		UPDATE appequip SET
        		ApplicantMasterID = '" . $ApplicantMasterID . "', 
        		equipID = '" . $equipID . "', 
        		Price = '" . $Price. "',  
        		SaveTime = '" . date('Y-m-d H:i:s') . "', 
        		SaveDate = '" . date('Y-m-d') . "', 
        		ClerkID = '" . $login_userid . "'
        		WHERE appequipID = " . $appequipID . ";";
               
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
        	}
            else if ($Price>0) //insert
            {
                $sql = "SELECT count(*) cnt FROM appequip WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'
                and equipID='$equipID'";
                $count = mysql_fetch_assoc(mysql_query($sql));
                if (!($count['cnt']>0))
                {
      			$query = "
                  INSERT INTO appequip(ApplicantMasterID, equipID,Price,SaveTime,SaveDate,ClerkID) 
                  VALUES('".$ApplicantMasterID."', '" . 
                  $equipID . "', '" . 
                  $Price . "', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
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

        			//header("Location: clerk.php");
        			$register = true;                    
                }
        

          
            }
         }
         
            
    
     }


?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیش فاکتور </title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}
</script> 
<script language='javascript' src='../assets/jquery.js'></script>
    <!-- /scripts -->
</head>
<body >>

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
						$Serial = "";
						$ProducersID = "";
                        /*
                        print "manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$retids.rand(10000,99999);
                        exit;*/
                        if ($typhid<>10)
                        header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$retids.rand(10000,99999));
                        else
                        header("Location: ../appinvestigation/sendtoanjoman.php");
                        
                        
                        
                        
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
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content" >
            <form action="equip_detail.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 

$(function() {
  $('#divgadget3ID1').filterByText($('#textbox'), true);
}); 

//--------------------------------------------------------------------------------------------------------------------------------------------
function p_tarkib(_value)
{
 var _len;var _inc;var _str;var _char;var _oldchar;_len=_value.length;_str='';
 for(_inc=0;_inc<_len;_inc++)
 {
   _char=_value.charAt(_inc);
   if (_char=='1' || _char=='2' || _char=='3' || _char=='4' || _char=='5' || _char=='6' || _char=='7' || _char=='8' || _char=='9' || _char=='0') 
      _str=_str+_char;
   else
      if (_char!=',') return 'error';
 }
 return _str;
}



function summ()
{	
    var sumt=0;
		for (var i=1;i<document.getElementById('records').rows.length-2;i++)
			sumt += p_tarkib(document.getElementById('Price'+i).value)*document.getElementById('Number'+i).value*1;
    document.getElementById('AllSum').value=numberWithCommas(sumt);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }

function sumrow(rowNumber)
{ 
    summ();
    var x=parseFloat($('input[name=\"Price'+rowNumber+'\"]').val().replace(/,/g, ''))*$('input[name=\"Number'+rowNumber+'\"]').val()*1;
    $('#divSumPrice'+rowNumber+' input:text ').val(numberWithCommas(x));
    $('#divSumPrice'+rowNumber).attr('onmouseover',\"Tip( '\"+(numberWithCommas(x)) +\"')\");
    x=$('input[name=\"Number'+rowNumber+'\"]').val();  
    $('#divNumber'+rowNumber).attr('onmouseover',\"Tip( '\"+(x) +\"')\");
}

</script>
";  ?>


                <div colspan="4">
                <tr >
                    <td align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-family:'B Nazanin';">فهرست بهای دستی</td>
                </tr>
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php 
                    print $fehrestsmasterTitle."  ".$fehrestsfaslsTitle; ?> </td>
                </tr>
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php print "&nbsp; طرح آقای/خانم  &nbsp;".$ApplicantName; ?> </td>
                </tr>
                    
                
                    
                </div>
                        	
                        
                        
    <br />
                            
                            
                            
                            
                            <?php
                            if ($type<>10)
                            print "<div style = \"text-align:left;\"><a  href='manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$retids.rand(10000,99999)."'><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";
                            else
                            print "<div style = \"text-align:left;\"><a  href='../appinvestigation/sendtoanjoman.php'><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";
                            
                            print "<td align=\"left\">";
							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%"></th>
                        	<th width="5%">کد</th>
                            <th width="15%">عنوان</th>
                            <th width="16%"> مبلغ</th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $cnt=0;
                    $rown=0;
                    $sum=0;
                    while($row = mysql_fetch_assoc($resultwhile)){
                            
                        $appequipID = 0;
                        $Price='';
                        $Code = '';
                        $Title = '';
                        $SumPrice='';
                        
                            $appequipID = $row['appequipID'];
                            $equipID = $row['equipID'];
                            
                            $Code = $row['Code'];
                            $Title = $row['Title'];
                            $Price = number_format($row['Price']);
                            $sum+=$row['Price'];
                        
                        
                        $cnt++;
                        
                        $rown++;
?>
                        <tr>
                            
                            <td class="data"><input name="appequipID<?php echo $cnt; ?>" type="hidden" class="textbox" id="appequipID<?php echo $cnt; ?>"  
                            value="<?php echo $appequipID; ?>"  /></td>
                           
                            
                            <td class="data"><input name="equipID<?php echo $cnt; ?>" type="hidden" class="textbox" id="equipID<?php echo $cnt; ?>"  
                            value="<?php echo $equipID; ?>"  /></td>
                            
                            <td ><div id="divrown<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$rown.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $rown; ?>" style='width: 20px' maxlength="6" readonly /></div></td>
                            
                            
                            <td ><div id="divCode<?php echo $cnt; ?>"><input onmouseover="Tip(<?php echo '('.$Code.')'; ?>)" name="Code<?php echo $cnt; ?>" type="text" class="textbox" id="Code<?php echo $cnt; ?>" value="<?php echo $Code; ?>" style='width: 55px' maxlength="8"  /></div></td>
                            
                            <td class="data"><div id="divTitle<?php echo $cnt; ?>"><input  onmouseover="Tip(<?php echo '(\''.$Title.'\')'; ?>)" 
                            name="Title<?php echo $cnt; ?>" type="text" class="textbox" id="Title<?php echo $cnt; ?>" value="<?php echo $Title; ?>" 
                            style='width: 900px' maxlength="100"  /></div></td>
                            <td class="data"><input  onmouseover="Tip(<?php echo '(\''.$Price.'\')'; ?>)" name="Price<?php echo $cnt; ?>" type="text" 
                            class="textbox" id="Price<?php echo $cnt; ?>" value="<?php echo $Price; ?>" size="15" onKeyUp="convert('Price<?php echo $cnt; ?>')" 
                            maxlength="15" <?php echo "onchange = \"sumrow('$cnt');\"" ?>  /></div></td>
                        </tr><?php

                    }
                        print "<td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' 
                        id='ApplicantMasterID'  value='$ApplicantMasterID' /></td>
                       ";
?>                      <td class="data"><input name="retids" type="hidden" class="textbox" id="retids"  
value="<?php echo $retids; ?>"  size="30" maxlength="30" /></td>
                        <td class="data"><input name="typhid" type="hidden" class="textbox" id="typhid"  
value="<?php echo $type; ?>"  size="30" maxlength="30" /></td>
                      
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td></td>
                      <td colspan='3'><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
                      <td colspan='1'>مجموع</td>
                      <td colspan='1' class="data"><div id="divAllSum"><input name="AllSum" type="text" class="textbox" id="AllSum" 
                      value="<?php echo number_format($sum); ?>" size="15" maxlength="15" readonly /></div></td>
                      </tr>
                      
                      
                      
                    </tfoot>
                    
                </table>
            
                </form>
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
