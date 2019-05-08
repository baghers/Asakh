<?php 

/*

codding/codding8sms.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding8sms.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

$g1id=is_numeric($_GET["g1id"]) ? intval($_GET["g1id"]) : 1900000;//شناسه شهر
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;//نقش

/*
clerk جدول کاربران
city نقش
cityid شناسه شهر
*/
$cond="";
if ($g2id>0) $cond=" and clerk.city='$g2id' ";
if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 
                       

if ($_POST['submit1'])
{
    $g1id=$_POST['g1id'];
    $g2id=$_POST['g2id'];
    $cond="";
    if ($g2id>0) $cond=" and clerk.city='$g2id' ";
    if ($g1id>0) $cond.=" and  substring(clerk.cityid,1,2)=substring('$g1id',1,2) ";                 
    
    
    
}

/*
clerk جدول کاربران
roles جدول نقش ها
tax_tbcity7digit جدول شهر ها
producers جدول تولیدکنندگان
designerco جدول طراحان
operatorco جدول پیمانکاران
*/
    
$sqlselect="
SELECT distinct roles.rolesid _value,roles.title _key   FROM clerk
inner join roles on roles.rolesid=clerk.city 
order by _key  COLLATE utf8_persian_ci";
$allg2id = get_key_value_from_query_into_array($sqlselect);


//print $sqlselect;

$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
order by _key  COLLATE utf8_persian_ci";
$allg1id = get_key_value_from_query_into_array($sqlselect);




        $sql="select clerk.clerkID,clerk.CPI,clerk.DVFS,WN
            
            ,clerk.mobile mobile from clerk 
            inner join roles on roles.RolesID=clerk.city
        left outer join producers on producers.producersid=clerk.BR
        left outer join designerco on designerco.designercoid=clerk.MMC
        left outer join operatorco on operatorco.operatorcoid=clerk.HW
        

        where  ifnull(notgetsms,0)=0 and length(ifnull(mobile,'0'))=10 
        
        
        
        $cond
        order by clerk.DVFS,clerk.CPI";
    
 
	  	  						try 
								  {		
									     $result1 = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
       
        //print $sql;

?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
     function SelectAll()
                {
                    if (($("input[id^='clerk']:checked").length*2) == $("input[id^='clerk']").length)
                    $("input[id^='clerk']").prop('checked', false);
                    else
                    $("input[id^='clerk']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value+ '&g2id=' + document.getElementById('g2id').value;
        
	}
    
    function checklenght(){
		document.getElementById('messagelen').value=(document.getElementById('comments').value.length);
        
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
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); 
            
            print "
            
            <div id='content'>
                <form action='http://178.131.48.167:8888/localsms.php' method='post'>
                  <td colspan='3'></td>";
        
        
        print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
        print select_option('g2id','نقش',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
                     

        $cnt=0;
        print "<tr>
        <a onclick=\"SelectAll();\"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                  
               <table style='border:2px solid;'><tr>";
        while($row = mysql_fetch_assoc($result1))
        {
            //print '1';
            $clerkID=$row['clerkID'];
            $key = decrypt($row['CPI'])." ".decrypt($row['DVFS']);
            $mobile=$row['mobile'];
            if ($clerkID>0)
            {
                $cnt++;
                $value=$cnt;
                print "<td class='data'>
                <input type='checkbox' id='clerk$value' name='clerk$value'>$key</input></td>
                <td class='data'><input type='hidden' class='textbox' id='mobile$value' name='mobile$value' value='$mobile'> </input></td>
                <td class='data'><input type='hidden' class='textbox' id='clerkID$value' name='clerkID$value' value='$clerkID'> </input></td>
                
                
                ";
                if (($cnt%4)==0)
                    print "</tr><tr>";   
                
            }
        }
        print "</tr></table></tr>";
        
            
            ?>      
          <tr>
                      <td class="label">متن:</td>
                      <td class="data">
                      <textarea  name="comments" id="comments"  class="textbox"  maxlength="1000" cols="100" rows="6" onChange="checklenght();"></textarea>
                      </td>
                     </tr>
                        <?php 
                        
                          print "
                          <td class='data'><input  
                       name='messagelen' type='text' class='textbox' id='messagelen' readonly  /></td>
                       
                       <td colspan='2'><input name='submit1' type='submit' class='button' id='submit1' value='ارسال'/></td>";

                         ?>          
                      
                 
                  
                       
                </form>      
            </div>
			<!-- /header -->

			<!-- content -->
			
			<!-- /content -->


            <!-- footer -->
			<?php 
            
            
            include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
