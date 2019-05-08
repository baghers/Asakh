<?php 

/*

insert/manualcostlist_pluscostlist_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/summaryinvoice.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='fehrestsfasls';//فصل فهرست بها

if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $fehrestsmasterID=$linearray[1];//فهرست بها
    $type=$linearray[2];//نوع
    $appfoundationID=$linearray[3];//سازه
    
$id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
/*
ApplicantName عنوان پیش فاکتور
applicantmaster مشخصات پیش فاکتور
ApplicantMasterID شناسه طرح
*/
$sql = "SELECT ApplicantName FROM applicantmaster WHERE ApplicantMasterID = '" . $ApplicantMasterID . "'";
$count = mysql_fetch_assoc(mysql_query($sql));
		$ApplicantName = $count['ApplicantName'];

        

//fehrestsfasls فصل فهرست بها
$sql = "SELECT COUNT(*) as count FROM fehrestsfasls where fehrestsmasterID=$fehrestsmasterID ";
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------
//fehrestsfasls فصل فهرست بها
    $sql = "SELECT Title FROM fehrestsmaster WHERE fehrestsmasterID = '" . $fehrestsmasterID . "'";
    $count = mysql_fetch_assoc(mysql_query($sql));
    $fehrestsmasterTitle = $count['Title'];
    
/*
fehrestsfasls فصل فهرست بها
fehrestsfaslsID شناسه فصل
manuallistprice فهرست بهای دستی
fehrests آیتم های فصل ها
fehrestsmasterID شناسه فهرست بها
*/          
                        
$sql = "SELECT distinct fehrestsfasls.fehrestsfaslsID,fehrestsfasls.fasl,Title,allf.fasl allfgardesh,manf.fehrestsfaslsID manfgardesh
 FROM fehrestsfasls 
 left outer join (
 SELECT substring(fehrests.Code,1,2) fasl FROM manuallistpriceall
inner join fehrests on fehrests.fehrestsID=manuallistpriceall.fehrestsID 
and fehrests.fehrestsmasterID='$fehrestsmasterID' 
where `manuallistpriceall`.`ApplicantMasterID` ='$ApplicantMasterID' and manuallistpriceall.appfoundationID='$appfoundationID'
) allf on allf.fasl=fehrestsfasls.fasl

left outer join ( SELECT fehrestsfaslsID FROM manuallistprice 
where `manuallistprice`.`ApplicantMasterID` ='$ApplicantMasterID' and manuallistprice.appfoundationID='$appfoundationID') manf on manf.fehrestsfaslsID=fehrestsfasls.fehrestsfaslsID

 where fehrestsfasls.fehrestsmasterID=$fehrestsmasterID  
 union all select '0' fehrestsfaslsID,'42' fasl,'تجهیز و برچیدن کارگاه' Title,0 allfgardesh,0 manfgardesh
 ORDER BY CAST(fasl AS UNSIGNED) ";

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
  	<title>فهرست بهای دستی</title>
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
                        
                        <h1 align="center">  <?php
                        if ($type==1) print "فهرست بهای دستی  ";
                        else if ($type==2) print "فهارس بها  ";
                        
                         print $fehrestsmasterTitle." طرح ".$ApplicantName; ?> </h1>
                          <div style = "text-align:left;"><a  href=<?php print "manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).
                          rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                          rand(10000,99999).$ApplicantMasterID.'_'.$type.'_'.$appfoundationID.rand(10000,99999); ?>><img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
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
                        	<th width="5%">فصل</th>
                            <th width="85%">عنوان</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $Code = $row['fasl'];
                        $fehrestsfaslsID= $row['fehrestsfaslsID'];
                        $Title = $row['Title'];
                        
                        if ($type==1)
                        {
                            if ($row['manfgardesh']>0)
                                $timg="searchPg.png";
                                else
                                $timg="search.png";
                                
                            $target="<td><a href=manualcostlist_pluscostlist_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
                            <img style = 'width: 40%;' src='../img/$timg' title=' ريز '></a></td>
                            
                            <td><a 
                            href='invoicemaster_deletedetail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            "man_".$ApplicantMasterID."_".$fehrestsmasterID."_".$fehrestsfaslsID."_".$appfoundationID."_".$type."_".rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 25%;' src='../img/delete.png' title='حذف آیتم های فهرست بها '> </a>
                            </td>
                            ";
                            
                            
                        }
                        else
                        {
                            
                            if ($row['allfgardesh']>0)
                                $timg="searchPg.png";
                                else
                                $timg="search.png";
                                
                            $target="<td><a href=manualcostlist_pluscostlist_detail2.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
                            <img style = 'width: 40%;' src='../img/$timg' title=' ريز '></a></td>
                            
                             <td><a 
                            href='invoicemaster_deletedetail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            "mana_".$ApplicantMasterID."_".$fehrestsmasterID."_".$fehrestsfaslsID."_".$appfoundationID."_".$type."_".rand(10000,99999).
                            "' onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 25%;' src='../img/delete.png' title='حذف آیتم های فهرست بها '> </a>
                            </td>";
                           
                            
                        }
                        
                        print "<tr><td>$Code</td>
                            <td>$Title</td>";
                        if ($row['fasl']==42)
                        print "<td><a href=equip_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $ApplicantMasterID."_".$fehrestsmasterID."_".$type."_".$fehrestsfaslsID."_".$appfoundationID."_".rand(10000,99999)."'>
                            <img style = 'width: 40%;' src='../img/search_page.png' title=' ريز '></a></td>
                            
                            ";
                        else
                        print $target;
                         
                        
                        print "</tr>";

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
