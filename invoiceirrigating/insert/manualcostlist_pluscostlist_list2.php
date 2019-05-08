<?php 

/*

insert/manualcostlist_pluscostlist_list2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
 
$formname='manualcostlist_pluscostlist2';
$tblname='fehrestsmaster';//فصل فهرست بها

if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $type=$linearray[1];//نوع
    $appfoundationID=$linearray[2];//سازه
    
    
    
if ($type==3)
{
        $CostPriceListMasterID=$linearray[0];  
        if (!($CostPriceListMasterID>0)) exit;   
}
else
{
       /*
ApplicantName عنوان پیش فاکتور
applicantmaster مشخصات پیش فاکتور
ApplicantMasterID شناسه طرح
*/
$sql = "SELECT ApplicantName FROM applicantmaster WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$ApplicantName = $count['ApplicantName'];
}



//fehrestsmaster فصل فهرست بها
$sql = "SELECT COUNT(*) as count FROM fehrestsmaster";
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------

if ($type==3)
{
    //fehrestsmaster فصل فهرست بها
$sql = "SELECT fehrestsmaster.* FROM fehrestsmaster 

 order by Title COLLATE utf8_persian_ci ";     
}

/*
fehrestsfasls فصل فهرست بها
fehrestsfaslsID شناسه فصل
manuallistprice فهرست بهای دستی
fehrests آیتم های فصل ها
fehrestsmasterID شناسه فهرست بها
fehrestsmaster فصل فهرست بها
*/ 
else if ($appfoundationID>0)
$sql = "SELECT fehrestsmaster.*,allf.fehrestsmasterID allfgardesh,manf.fehrestsmasterID manfgardesh FROM fehrestsmaster 

 left outer join (
 SELECT distinct fehrestsmasterID FROM manuallistpriceall
inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID 
where `manuallistpriceall`.`ApplicantMasterID` ='$ApplicantMasterID' and manuallistpriceall.appfoundationID='$appfoundationID'
) allf on allf.fehrestsmasterID=fehrestsmaster.fehrestsmasterID

left outer join ( SELECT distinct fehrestsmasterID FROM manuallistprice 
inner join fehrestsfasls on fehrestsfasls.fehrestsfaslsID=manuallistprice.fehrestsfaslsID
where `manuallistprice`.`ApplicantMasterID` ='$ApplicantMasterID' and manuallistprice.appfoundationID='$appfoundationID') manf 
on manf.fehrestsmasterID=fehrestsmaster.fehrestsmasterID
 order by Title COLLATE utf8_persian_ci ";
//where fehrestsmaster.fehrestsmasterid<>2  order by Title COLLATE utf8_persian_ci ";
else if ($appfoundationID==-1)
    $sql = "SELECT * FROM fehrestsmaster order by Title COLLATE utf8_persian_ci ";
else 
$sql = "SELECT * FROM fehrestsmaster where fehrestsmasterid=2  order by Title COLLATE utf8_persian_ci ";
//print $sql;

       	   				  	try 
								  {		
									 	$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>سایر فهارس بها</title>
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
			<div id="content">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center"> <?php 
                        if ($type==1) print "فهرست بهای دستی طرح ";
                        else if ($type==2) print "فهارس بها طرح ";
                        else if ($type==3) print "فهارس بها ";
                        print $ApplicantName; ?> </h1>
                          <div style = "text-align:left;"><a  href=<?php 
                          if ($type==3)
                            print "../codding/codding2costpricelistmaster.php";
                            
                          else if (in_array($login_RolesID, array(2,9)))
                          {
                            if ($appfoundationID==-1)
                            print "foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.'_1'.rand(10000,99999);
                            else if ($appfoundationID<>0)
                                print "foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                        .$ApplicantMasterID.'_0'.rand(10000,99999);
                            else 
                                print "manualcostlist_applicant_list.php";    
                          }
                           else print "../appinvestigation/sendtoanjoman.php";  ?>>
                          <img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
                          <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxcode; ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
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
                            <th width="90%">عنوان فهرست بها</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $ID = $row['fehrestsmasterID'];
                        $Title = $row['Title'];
                        
?>                      
                        <tr>
                            
                            <td><?php echo $Title; ?></td>
                            <?php 
                            
                            
                        if ($type==1)
                        {
                            if ($row['manfgardesh']>0)
                                $timg="searchPg.png";
                                else
                                $timg="search.png";
                        }
                        else
                        {
                            
                            if ($row['allfgardesh']>0)
                                $timg="searchPg.png";
                                else
                                $timg="search.png";
                        }
                        
                            
                            if ($type==3)
                            print "<td><a href='../codding/codding2costpricelistmaster_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $CostPriceListMasterID.'_'.$ID.'_'.$type.rand(10000,99999)."'>
                            <img style = 'width: 40%;' src='../img/search_page.png' title=' ريز '></a></td>";
                            
                            else print "<td><a href='manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID.'_'.$ID.'_'.$type.'_'.$appfoundationID.rand(10000,99999)."'>
                            <img style = 'width: 40%;' src='../img/$timg' title=' ريز '></a></td>"; 
                            
                            ?>
                            
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
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
