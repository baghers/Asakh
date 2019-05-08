<?php 

/*

insert/appfarmerbring_detail.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
$tblname='appfarmerbring';//جدول آورده متقاضی
if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST)
    { 
        $ApplicantMasterID = $_POST['ApplicantMasterID'];//شناسه طرح
        //appfarmerbring جدول آورده متقاضی
        mysql_query("delete from appfarmerbring WHERE ApplicantMasterID ='$ApplicantMasterID';");
        $i=0;
        while (isset($_POST['rown'.++$i]))
        {
        	$rown = $_POST['rown'.$i];
            $Title= $_POST['Title'.$i];
            $price= str_replace(',', '', $_POST['price'.$i]);
        	if ($price>0) //insert
            {
           	    $query = "INSERT INTO appfarmerbring(ApplicantMasterID,rown, Title,price,SaveTime,SaveDate,ClerkID) 
                VALUES('$ApplicantMasterID','$i','$Title','$price', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                $result = mysql_query($query);
        		$register = true; 
            }
         }
         $AllSum= str_replace(',', '', $_POST['AllSum']);
         //applicantmaster جدول مشخصات طرح
         //othercosts5 سایر هزینه های 5
        mysql_query("update applicantmaster set othercosts5='$AllSum' WHERE ApplicantMasterID ='$ApplicantMasterID';");
         
     }


if (! $_POST)
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
}
else 
    $ApplicantMasterID = $_POST['ApplicantMasterID'];
/*
applicantmaster  جدول مشخصات طرح
tax_tbcity7digit جدول شهرها
cityname نام شهر
ApplicantFName نام متقاضی
ApplicantName عنوان پروژه
id شناسه شهر
ApplicantMasterID شناسه طرح
*/
$sql = "SELECT shahr.cityname shahrcityname,ApplicantFName,ApplicantName
        FROM applicantmaster 
        left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
        left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
        and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
        WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID'";
        
        //print $sql;exit;
        $count = mysql_fetch_assoc(mysql_query($sql));
        
$ApplicantName = " آورده متقاضی طرح ";
$ApplicantName.= $count['ApplicantFName']." ".$count['ApplicantName']." شهرستان  ".$count['shahrcityname'];

$sql = "SELECT * FROM appfarmerbring where ApplicantMasterID = '".$ApplicantMasterID."' order by rown" ;



	  	  						try 
								  {		
									       $resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>آورده متقاضی </title>
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
            <form action="appfarmerbring_detail.php" method="post">
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
		for (var i=1;i<=10;i++)
			sumt += p_tarkib(document.getElementById('price'+i).value)*1;
    //alert(sumt);
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
    var x=parseFloat($('input[name=\"price'+rowNumber+'\"]').val().replace(/,/g, ''))*$('input[name=\"Number'+rowNumber+'\"]').val()*1;
    $('#divSumprice'+rowNumber+' input:text ').val(numberWithCommas(x));
    $('#divSumprice'+rowNumber).attr('onmouseover',\"Tip( '\"+(numberWithCommas(x)) +\"')\");
    x=$('input[name=\"Number'+rowNumber+'\"]').val();  
    $('#divNumber'+rowNumber).attr('onmouseover',\"Tip( '\"+(x) +\"')\");
}

</script>
";  ?>


                <div colspan="4">
                
                <tr>
                    <td style = "border:0px solid black;width: 80%;text-align:center;font-size:14.0pt;line-height:100%;font-family:'B Nazanin';" > <?php print $ApplicantName; ?> </td>
                </tr>
                    
                
                    
                </div>
                        	
                        
                        
    <br />
                          </td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%"></th>
                            <th width="50%">عنوان</th>
                            <th width="20%"> مبلغ</th>
                        </tr>
                    </thead>
                   <tbody><?php
                    $sum=0;
                    for ($cnt=1;$cnt<=10;$cnt++)
                    {
                        $row = mysql_fetch_assoc($resultwhile);
                        $price='';
                        $Title = '';    
                        $Title = $row['Title'];
                        $price = number_format($row['price']);
                        $sum+=$row['price'];
                        
                        
?>
                        <tr>
                            
                            <td ><div id="divrown<?php echo $cnt; ?>"><input size="7" onmouseover="Tip(<?php echo '('.$cnt.')'; ?>)" name="rown<?php echo $cnt; ?>" type="text" class="textbox" id="rown<?php echo $cnt; ?>" value="<?php echo $cnt; ?>" maxlength="6" readonly /></div></td>
                            
                            <td class="data"><div id="divTitle<?php echo $cnt; ?>"><input size="190" onmouseover="Tip(<?php echo '(\''.$Title.'\')'; ?>)" 
                            name="Title<?php echo $cnt; ?>" type="text" class="textbox" id="Title<?php echo $cnt; ?>" value="<?php echo $Title; ?>" 
                             maxlength="100"  /></div></td>
                            <td class="data"><input  onmouseover="Tip(<?php echo '(\''.$price.'\')'; ?>)" name="price<?php echo $cnt; ?>" type="text" 
                            class="textbox" id="price<?php echo $cnt; ?>" value="<?php echo $price; ?>" size="20" onblur="summ();" onKeyUp="convert('price<?php echo $cnt; ?>')" 
                            maxlength="15" <?php echo "onchange = \"sumrow('$cnt');\"" ?>   /></div></td>
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
                      <td colspan='1'><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
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
