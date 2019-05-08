<?php

/*
codding/codding4table_detail.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 codding/codding4table_detail_delete.php

*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

//if (!$login_is_admin) header("Location: login.php");

$TBLNAME = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
        $TBLNAME=$linearray[0];//نام جدول
        $TBLTITLE=$linearray[1];//عنوان فارسی جدول
        $TBLID=$linearray[2];//شناسه جدول
        $tblkey=$linearray[3];//کلید جدول
        $tblval=$linearray[4];//مقدار جدول

    if ( ( $login_RolesID!=19 && $login_RolesID!=18 && $backdoor==0 && !in_array($TBLNAME,array("applicantsurvey","applicantsystemtype","applicantwsource","appsubprj"))) ) header("Location: ../login.php");        
//print         $TBLNAME.''.$TITLE;
        if ($tblkey!='')
        $gcond=" where $tblkey='$tblval'";
$per_page = 1000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
$sql = "SELECT COUNT(*) as count FROM $TBLNAME $gcond";
$count = mysql_fetch_assoc(mysql_query($sql));
$count = $count[count];
$pages = ceil($count / $per_page);
//----------
if( $login_RolesID!=1) $cond="'Code','RolesID','Wbsite'"; else $cond="' '"; 
$query = "  SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE  TABLE_SCHEMA = '$_server_db' and TABLE_NAME='$TBLNAME' and upper(COLUMN_NAME) 
            not in ('SAVETIME','".strtoupper($tblkey."',$cond").",'SAVEDATE','CLERKID', upper(concat(TABLE_NAME, 'ID')) );";



  
			 try 
			  {		
				 $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }


    //print $query;
$fields="";
$fieldcnt=0;
$joinstr="";
$orderfields="";
$captions="";
while($row = mysql_fetch_assoc($result))
{
    if ($TBLNAME=='applicantwsource' && $row['COLUMN_NAME']=='ApplicantMasterID')
        continue;
    
    $fieldcnt++;
    if (substr($row['COLUMN_NAME'],strlen($row['COLUMN_NAME'])-2,2)=="ID")
    {
        $curcol="j".$row['COLUMN_NAME'].".Title ".$row['COLUMN_NAME'];
    }
    else
    {
    $curcol="$TBLNAME.".$row['COLUMN_NAME'];
    }
    
    if ($fieldcnt==1) 
    {
        
        $fields=$curcol; 
        $orderfields=$row['COLUMN_NAME'];
        if ($row['COLUMN_COMMENT']!='') $captions=$row['COLUMN_COMMENT']; else $captions=$row['COLUMN_NAME'];
    }
    else
    {
        $fields.=",$curcol";
        $orderfields.=",".$row['COLUMN_NAME'];
        if ($row['COLUMN_COMMENT']!='') $captions.=",".$row['COLUMN_COMMENT']; else $captions.=",".$row['COLUMN_NAME'];
        
        
    }
    
    
    if ($TBLNAME!='applicantwsource' || $row['COLUMN_NAME']!='ApplicantMasterID')
    if (substr($row['COLUMN_NAME'],strlen($row['COLUMN_NAME'])-2,2)=="ID")
    {
        $joinstr.=" left outer join ".strtolower(substr($row['COLUMN_NAME'],0,strlen($row['COLUMN_NAME'])-2))." j".$row['COLUMN_NAME']." on j".
        $row['COLUMN_NAME'].".".
        $row['COLUMN_NAME']."=$TBLNAME.".$row['COLUMN_NAME'];
    //print $joinstr."$TBLNAME<br>";
    }
    if ($fieldcnt>=8) break;
}
$fieldsarray = explode(',',$orderfields);
$captionsarray = explode(',',$captions);


if ($TBLNAME=='designerco' || $TBLNAME=='operatorco' )
{

}

if($login_RolesID==20) $gcond="where roles.RolesID not in (1,5,6,7,8,13,14,15,16,17,18,26,27,28,29,30)";

$sql = "
SELECT $TBLNAME.".$TBLNAME."ID,$fields
FROM $TBLNAME
$joinstr 
 $gcond
ORDER BY ".$TBLNAME."ID,$orderfields LIMIT " . $start . ", " . $per_page . ";";


			 try 
			  {		
				$result = mysql_query($sql);
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
  	<title><?php print $TITLE; ?></title>
<meta http-equiv="X-Frame-Options" content="deny" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
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
                            <h1 align="center"><?php print $TITLE; ?></h1>
                             <div style = "text-align:left;">
                            <a href='<?php print"codding4table_detail_new.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$TBLNAME.'_'.$TITLE.'_0_'.$tblkey.'_'.$tblval.rand(10000,99999); ?>'>
                             <img style = 'width: 2%;' src='../img/Actions-document-new-icon.png' title=' جدید '> </a>
                            <a  href=<?php print "codding4table_list.php"; ?>>
                            <img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a>
                            
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
                        <th >شناسه</th>
                        <?php
                        foreach ($captionsarray as $i => $value) 
                        {
                            echo "<th >$value</th>";
                        }
                         ?>
                         <th ></th>
                         <th ></th>
                        </tr>
                    </thead>
                   <tbody><?php
                
                    while($row = mysql_fetch_assoc($result)){
                
                        $ID = $TBLNAME.'_'.$TITLE.'_'.$row[$TBLNAME.'ID'].'_'.$tblkey.'_'.$tblval;
                        
                        $Code = $row['Code'];
                        
						$title = $row['Title'];
                        
                        $addfield="";
                        if (isset($row["addfield"]))
                        $addfield=$row["addfield"];
                
?>
                        <tr>
                        <?php
                        echo "<td>".$row[$TBLNAME.'ID']."</td>";
                        foreach ($fieldsarray as $i => $value) 
                        {
                            echo "<td> $row[$value]</td>";
                        }
                         ?>
                         
                            
                            <td><a href="<?php print"codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                            <img style = 'width: 30px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                            <td><a href="<?php print"codding4table_detail_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>"
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"   >
                            <img style = 'width: 30px;' src='../img/delete.png' title='حذف'></a></td>
                        </tr><?php
                
                    }
                
?>
                    </tbody>
                </table>
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