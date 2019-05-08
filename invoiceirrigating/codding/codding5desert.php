<?php 

/*

codding/codding5desert.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5cities.php
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
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    */
    $query="select distinct substring(id,3,2) id from tax_tbcity7digit where substring(id,1,2)='$stateid' and substring(id,3,2)<>'00' order by id";

   						try 
								  {		
									$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $selectedid=0;
    $selectedidstr="00";
    $preid="00";
    $curid="00";
    $inserted=0;
    $row = mysql_fetch_assoc($result);
    if ($row)
    {
        while($row)
        {
            $preid=$curid;
            $curid = $row['id'];
            $selectedid++;
            if ($selectedid<10) $selectedidstr='0'.$selectedid; else $selectedidstr=$selectedid;
            if (($selectedidstr>$preid) && ($selectedidstr<$curid) && ($selectedid<100) )
            {
                if ($inserted==0)
                {
                    $SaveTime=date('Y-m-d H:i:s');
                    $SaveDate=date('Y-m-d');
                    $ClerkID=$login_userid;
                    $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                    '".$stateid.$selectedidstr."000','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
                    mysql_query($query); 
                    $inserted=1;    
                    break;   
                }
            }
            $row = mysql_fetch_assoc($result);
        }   
            $selectedid++;
            if ($selectedid<10) $selectedidstr='0'.$selectedid; else $selectedidstr=$selectedid;
            if ($selectedid<100)
            {
                if ($inserted==0)
                {
                    $SaveTime=date('Y-m-d H:i:s');
                    $SaveDate=date('Y-m-d');
                    $ClerkID=$login_userid;
                    $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                    '".$stateid.$selectedidstr."000','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
                    mysql_query($query); 
                    $inserted=1;       
                }
            }
        
    } 
    else 
    {
        $SaveTime=date('Y-m-d H:i:s');
        $SaveDate=date('Y-m-d');
        $ClerkID=$login_userid;
        $query = "INSERT INTO tax_tbcity7digit(id ,CityName,SaveTime,SaveDate,ClerkID) VALUES(
                '".$stateid."01000','$inputtitle','$SaveTime','$SaveDate','$ClerkID');";
        mysql_query($query);
    }
    
    
    
 
}
else
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $stateid=substr($id,0,2);
}
    $sql="select CityName from tax_tbcity7digit where id='$stateid"."00000'";
    
	 						try 
								  {		
									$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $row = mysql_fetch_assoc($result);
    $TITLE = $row['CityName'];
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    clerk جدول کاربران
    clerk.CPI نام کاربر
    clerk.DVFS نام خانوادگی کاربر
    designerco جدول طراحان
    */                            
    $sql = "select distinct clerk.CPI,clerk.DVFS,tax_tbcity7digit.id ,tax_tbcity7digit.CityName
        ,case ifnull(TAX_tbCity7Digitgardesh.id,0) when 0 then '' else 'دارد' end  gardesh,designerco.Title designercoTitle from 
        tax_tbcity7digit 
        left outer join tax_tbcity7digit TAX_tbCity7Digitgardesh on substring(TAX_tbCity7Digitgardesh.id,1,4)=substring(tax_tbcity7digit.id,1,4) and substring(TAX_tbCity7Digitgardesh.id,5,3)!='000'
        
        left outer join clerk on clerk.clerkid=tax_tbcity7digit.ClerkIDExcellentSupervisor
        left outer join designerco on designerco.DesignerCoID=tax_tbcity7digit.DesignerCoIDnazer
        where substring(tax_tbcity7digit.id,1,2)='$stateid' and substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,4)!='0000' 
        order by tax_tbcity7digit.CityName  COLLATE utf8_persian_ci
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
  	<title>لیست دشت/شهرستان ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
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
            
            <form action="codding5desert.php" method="post">
            
			<div id="content">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">  لیست دشت/شهرستان های <?php echo $TITLE; ?> </h1>
                        <INPUT type="hidden" name="stateid" id="stateid" value="<?php print $stateid; ?>"/>
                            
                             <div style = "text-align:left;">
                            <a href=<?php print "codding5cities.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a>
                            
                          </div>

 <?php $permitrolsid = array ("1");
                   if (in_array($login_RolesID, $permitrolsid))
                    {?>
              						  
                           <td class='label'>عنوان</td>
                            <td class='data'><input name='inputtitle' type='text' id='inputtitle'  /></td>
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                    
					<?php } ?>
              	            
                            
                            
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th width="5%">ردیف</th>
                        	<th width="10%">گردش</th>
                        	<th width="10%">کد</th>
                        	<th width="25%">عنوان</th>
                        	<th width="15%">ناظر عالی</th>
                        	<th width="15%">مشاور ناظر</th>
                            <th width="5%"></th>
                            <th width="5%"></th>
                            <th width="5%"></th>
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
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $row['gardesh']; ?></td>
                            <td><?php echo $ID; ?></td>
                            <td><?php echo $TITLE; ?></td>
                            <td><?php print trim(decrypt($row['CPI'])." ".decrypt($row['DVFS'])); ?></td>
                            <td><?php print $row['designercoTitle']; ?></td>
                            <td><?php print $deletestr; ?></td>
                            <td><a href=<?php print "codding5countries.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/search.png' title=' مشاهده '></a></td>
                            
                            <td><a href=<?php print "codding5countries_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                            
                            <td><a href=<?php print "codding5cityquota.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/protection.png' title=' سهمیه فیزیکی'></a></td>
							
							<td><a href=<?php print "codding5cityquotaws.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 80%;' src='../img/dolar.jpg' title=' سهمیه اعتباری'></a></td>
                            
                            
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
