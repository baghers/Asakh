<?php 

/*

codding/codding5countries.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5desert.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST )
{
    $stateid=$_POST['stateid'];//شناسه شهر
    $inputtitle=$_POST['inputtitle'];//عنوان
    addcity($stateid,$inputtitle);//تابع ذخیره شهر   
}
else
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $stateid=substr($id,0,4);
}
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    */
    $sql="select tax_tbcity7digit.CityName,ostan.CityName ostanCityName from tax_tbcity7digit 
          inner join tax_tbcity7digit ostan on ostan.id='".substr($stateid,0,2)."00000' where tax_tbcity7digit.id='$stateid"."000'";
    
	 try 
      {		
			$result = mysql_query($sql);
	  }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }

    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    applicantmaster جدول مشخصات طرح
    fzkargah کد
    */
    
    $row = mysql_fetch_assoc($result);
    $TITLE = $row['CityName'];
    $ostanCityName = $row['ostanCityName'];
    
$sql = "select distinct id ,fzkargah,CityName,case ifnull(applicantmaster.CityId,0) when 0 then '' else 'دارد' end gardesh from tax_tbcity7digit 
        left outer join applicantmaster on applicantmaster.CityId=tax_tbcity7digit.id
        where substring(id,1,4)='$stateid'
        and substring(id,5,3)<>'000' order by id,CityName  COLLATE utf8_persian_ci
        ";

//print $sql;

 
 try 
      {		
			$result = mysql_query($sql);
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
  	<title>لیست شهر/بخش ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    function MoveAll(url)
    {
        
        var stid='0';
        
        for (var j=1;j<=(document.getElementById('records').rows.length-1);j++)
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
       // alert(url);
            
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="http://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }
    
         function SelectAll()
                {
                    if ($("input[id^='c']:checked").length == $("input[id^='c']").length)
                    $("input[id^='c']").prop('checked', false);
                    else
                    $("input[id^='c']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
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
            <form action="codding5countries.php" method="post">
			<div id="content">
                <table width="95%" align="center">
                    <tbody>
                     <a onclick="SelectAll();"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                 
                <a onclick="MoveAll('<?php print"http://$_SERVER[HTTP_HOST]/$home_path_iri/codding/codding5citiesmove.php";?>');">
                <img style = 'width: 4%;' src='../img/Actions-document-export-icon.png' title='انتقال'></a>
               
                        <tr>
                        
                        <h1 align="center"> لیست شهر/بخش های شهرستان <?php echo $TITLE.' '.$ostanCityName; ?> </h1>
                           <INPUT type="hidden" name="stateid" id="stateid" value="<?php print $stateid; ?>"/>
                            
                             <div style = "text-align:left;">
                            <a  href=<?php print 
                    "codding5desert.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).substr($stateid,0,2).rand(10000,99999) ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت'></a>
                          </div>
                           
                           <td class='label'>عنوان</td>
                            <td class='data'><input name='inputtitle' type='text' id='inputtitle'  /></td>
                    
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                        
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                            <th width="1%">&nbsp;</th>
                        	<th width="5%">ردیف</th>
                        	<th width="10%">گردش</th>
                        	<th width="10%">کد</th>
                        	<th width="35%">عنوان</th>
							<th width="25%">ضریب منطقه حقوق عوامل  نظارت</th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {

                        $ID = $row['id'];
                        $TITLE = $row['CityName'];
                        $deletestr='';
                        if ($row['gardesh']=='') 
                            $deletestr="<a href='codding5delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 60%;' src='../img/delete.png' title='حذف'> </a>";
                        $rown++;
?>                      
                        <tr>
                            
                            <td > <input type="checkbox" id="c<?php echo $rown; ?>" name="chk<?php echo $ID; ?>" value="1"/></td >
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $row['gardesh']; ?></td>
                            <td><?php echo $ID; ?></td>
                            <td><?php echo $TITLE; ?></td>
							<td><?php echo $row['fzkargah']; ?></td>
							<td><?php print $deletestr; ?></td>
                             <td><a href=<?php print "codding5countys_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                
                            
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                <div style='visibility: hidden'>
					  ?>
                      </div>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
            </div>
			<!-- /content -->
            </form>

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
