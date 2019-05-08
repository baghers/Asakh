<?php 

/*

//codding/codding1pricelistmaster.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/codding/codding1pricelistmaster.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='codding1pricelistmaster';
$tblname='pricelistmaster';

if ($login_Permission_granted==0) header("Location: ../login.php");

$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

/*
پرس و جوی استخراج فهرست بهای مختلف
costpricelistmaster فهرست بها
month ماه ها
year سال ها
MonthID شناسه ماه
YearID شناسه سال
*/
        
$sql = "
SELECT ".$tblname.".*,month.Title monthtitle,year.Value year 
FROM $tblname 
inner join month on month.MonthID=$tblname.MonthID
inner join year on year.YearID=$tblname.YearID
 
ORDER BY year.Value DESC ,month.Code DESC ;";


//print $sql;

							try 
							  {		
								 $result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست قیمت ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
function add() {
 
    var myDiv = document.getElementById("mydiv");
    var currindex = myDiv.children.length;
        
    if (currindex>=6) return;
    
    
    
var element2 = document.createElement("select");
    var element6 = document.createElement("input");
 
    
 

for (var i = 0; i < document.getElementById("YearID").length; i++) {
    var option = document.createElement("option");
    option.value = document.getElementById("YearID").options[i].value;
    option.text = document.getElementById("YearID").options[i].text;
    element2.appendChild(option);
}

    
    element2.style.width = '65px';
    
    
    element6.type = "button";
    element6.value = "درج"; // Really? You want the default value to be the type string?
    element6.name = "button";  // And the name too?
    element6.onclick = function() 
    { // Note this is a function
    
        
        //var searchEles = document.getElementById("myDiv").children;
        var buttonid=this.id;
 
        var myDiv = document.getElementById("mydiv");
        var searchEles = myDiv.children;
          
          var in1=searchEles[buttonid-3].options[searchEles[buttonid-3].selectedIndex].value;
            
          var in2=searchEles[buttonid-2].selectedIndex+1;
          
     var in3=document.getElementById("txtuserid").value;
        var txturl = document.getElementById("txturl");
       
          $.post(txturl.value, { in1: in1, in2: in2, in3: in3 } );

        $(this).parent().remove();
        alert( "ثبت انجام شد" );
        location.reload();
          //myDiv.removeChild(searchEles[buttonid-1].id);
            
        
        
     
    };
    
    element6.style.width = '80px';
    element6.style.height = '30px';
    element2.style.height = '29px';
    
    
//Create array of options to be added
var array = ["فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند"];

//Create and append select list
var selectList = document.createElement("select");


//Create and append the options
for (var i = 0; i < array.length; i++) {
    var option = document.createElement("option");
    option.value = array[i];
    option.text = array[i];
    selectList.appendChild(option);
}
    selectList.style.width = '80px';

element2.id =  currindex+1;currindex=currindex+1;
selectList.id = currindex+1;currindex=currindex+1;
element6.id =  currindex+1;currindex=currindex+1;
    
    myDiv.appendChild(element2);
    myDiv.appendChild(selectList);
    myDiv.appendChild(element6);
    
    element2.focus();
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
            <?php include('../includes/header.php'); ?>
			<!-- /header --> 

			<!-- content -->
			<div id="content">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">  لیست قیمت ها </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txtProducersID" value="<?php print $login_ProducersID; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/codding/codding1pricelistmaster_jr.php"; ?>"/>
                           <div style = "text-align:left;">
                            <button title='افزودن لیست قیمت جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div>
                          
                           
                            <td width="50%" align="left"><?php

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
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="20%">سال</th>
                            <th width="40%">ماه</th>
                            <th width="10%">مجاز برای مشاورین</th>
							<th width="10%">مجاز برای توليد كننده</th>
							<th width="10%">مجاز برای مجري</th>
                        	<th width="10%">مجاز برای کاربر</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $year = $row['year'];
                        $monthtitle = $row['monthtitle'];
                        $ID=$row['PriceListMasterID'];
                        //print "salam".$ID;
?>                      
                        <tr>
                            
                            <td><?php echo $year; ?></td>
                            <td><?php echo $monthtitle; ?></td>
                            <td><?php echo $row['pfd']; ?></td>
							<td><?php echo $row['pfp']; ?></td>
							<td><?php echo $row['pfo']; ?></td>
							<td><?php echo $row['pfm']; ?></td>
                            <td><a href=<?php print $formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                             <img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '> </a></td>
                            <td><a 
                            href=<?php      print $formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 60%;' src='../img/delete.png' title='حذف'> </a></td>
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                <div style='visibility: hidden'>
                          <?php

					 $query='select YearID as _value,Value as _key from year';
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('YearID','',',',$ID,0,'','','1','rtl',0,'',$YearID);

					  ?>
                      </div>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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
