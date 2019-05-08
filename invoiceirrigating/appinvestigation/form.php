<?php 

/*

//appinvestigation/form.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/members_operatorcos.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
include('../includes/functions.php');
  if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST)
{
    $ClerkIDinvestigation=$_POST['ClerkIDinvestigation'];//کاربری بررسی
    $TBLNAME=$_POST['tbl'];//جدول
    $idval=$_POST['idval'];//مقدار
    $cnth=1;
    while(isset($_POST['form3ID'.$cnth]))
    {    
        if ($_POST['score1'.$cnth]>0)
        {
            //form3detail جدول ریز آیتم های ارزشیابی
            if($_POST['form3detailID'.$cnth]>0)//update
                $query = "update form3detail set score1='".$_POST['score1'.$cnth]."',Description='".$_POST['description'.$cnth]."'
                ,SaveTime='".date('Y-m-d H:i:s'). "',SaveDate='".date('Y-m-d')."',ClerkID='$login_userid' 
                where form3detailID='".$_POST['form3detailID'.$cnth]."';";
            else          
            //form3detail جدول ریز آیتم های ارزشیابی  
                $query = "INSERT INTO form3detail(form3ID, ClerkIDinvestigation,score1,Description,SaveTime,SaveDate,ClerkID) VALUES(
                        '".$_POST['form3ID'.$cnth]."','".$_POST['ClerkIDinvestigation']."','".$_POST['score1'.$cnth]."','".
                        $_POST['description'.$cnth]."','".
                        date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                        //print $query;exit;
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

            $cnth++;
        }
              
            
        $cnth++;
    }
}
else
{
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ids);
       $ClerkIDinvestigation=$linearray[0];
		$TBLNAME=$linearray[1];
		$TITLE=$linearray[2];
		$idval=$linearray[3];
}    
    /*
    form1 سطح اول آیتم های ارزشیابی
    form2 سطح دوم آیتم های ارزشیابی
    form3 سطح سوم آیتم های ارزشیابی
    
    */
    $sql1="SELECT 
SUBSTRING(form3.title, 1, instr(form3.title, '_')-1) AS result,count(*)cnt from `form3`
INNER JOIN form2 ON form2.form2id = form3.form2id
INNER JOIN form1 ON form1.form1id = form2.form1id and form1.form1id =1
group by SUBSTRING(form3.title, 1, instr(form3.title, '_')-1)";
$result = mysql_query($sql1); 
							try 
							  {		
								mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	$arraystr4=array();
	
    while($resquery = mysql_fetch_assoc($result))
	{
	   $arraystr4[$resquery['result']]=$resquery['cnt'];
    }
    
if ($login_RolesID=='1') 
            $str.=" and case ifnull(applicantmasterejra.DesignerCoIDnazer,0) 
            when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer 
            else applicantmasterejra.DesignerCoIDnazer end='$login_DesignerCoID'";
            
    /*
    form1 سطح اول آیتم های ارزشیابی
    form2 سطح دوم آیتم های ارزشیابی
    form3 سطح سوم آیتم های ارزشیابی
    form3detail جدول ریز آیتم های ارزشیابی 
    */
 $sql = "SELECT form3.form3ID,form1.Title form1Title, form2.Title form2Title, form3.Title form3Title, form3.score,sc.cnt,sc.sum
 ,round(avg.score1,1) avgscore,round(form3detail.score1,1) clerkscore,form3detail.form3detailID,form3detail.Description,form3detail.ClerkID 
FROM form3
INNER JOIN (SELECT count(*) cnt,sum(score) sum,form2id FROM `form3`
group by form2id) sc ON sc.form2id = form3.form2id
INNER JOIN form2 ON form2.form2id = form3.form2id
INNER JOIN form1 ON form1.form1id = form2.form1id

left outer join (select form3ID,avg(score1) score1 from form3detail
where ClerkIDinvestigation='$ClerkIDinvestigation'
group by form3ID) avg on avg.form3ID=form3.form3ID
left outer join (select avg(score1) score1,max(form3detailID)form3detailID,max(Description)Description,ClerkIDinvestigation,form3ID,ClerkID 
from form3detail group by ClerkIDinvestigation,form3ID,ClerkID) form3detail on form3detail.ClerkIDinvestigation='$ClerkIDinvestigation' and 
form3detail.ClerkID='$login_userid' and form3detail.form3ID=form3.form3ID

WHERE form1.form1id =1
ORDER BY form2.order, form3.order	 
	";	
    //print $sql;
    //exit;
     
    
							try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	while($resquery = mysql_fetch_assoc($result))
	{
        $form1Title=$resquery['form1Title'];
    }
	
	
 mysql_data_seek( $result, 0 );

?>
<!DOCTYPE html>
<html>
<head>
  	<title>فرم ارزشیابی</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    

    <!-- /scripts -->
</head>
<body >

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
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="form.php" method="post">
            <input type="hidden" name="tbl" id="tbl" value="<?php echo $TBLNAME;  ?>" >
            <input type="hidden" name="idval" id="idval" value="<?php echo $idval;  ?>" >
			
                
                
               <table align='center' class="page" border='1' id="table2">              
               <thead>
	                    
				  <tr> 
                            <td colspan="9"
                            <span class="f14_fontb" style="color:blue"><?php echo $form1Title; ?></span>  
                        
						<?php print "
                        <a  target=\"_blank\"  href='allapplicantrequest_chart.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1'.rand(1000,9999).'1'."'>
						<img style = 'width: 25px' src=\"../img/chart.png\" title='نمودار'></a>
						";?>
                     	</td>
				
			                
				   </tr>
                     
                     <?php

				 
					$sqli="select * from $TBLNAME where $TBLNAME"."ID"."=$idval";	  
					$resulti = mysql_query($sqli); 
					$res = mysql_fetch_assoc($resulti);
					$CoTitle=$res['Title'];
					$CompanyAddress=$res['CompanyAddress'];
					$bossname=$res['BossName'].''.$res['bosslname'];
					$fundationYear=$res['fundationYear'];
					
					
					
    			        $hideC='display:none';
				        echo "
						
						<tr>
                            <th colspan=\"2\" class=\"f14_fontb\"  >پیمانکار: $CoTitle</th>
                        	<th colspan=\"4\" class=\"f14_fontb\" >تاریخ ثبت شرکت: $fundationYear</th>

							<th colspan=\"3\" class=\"f14_fontb\" >	ارزیاب: $login_fullname</th>
						
					    </tr>
						
						<tr style=\"color:blue\">
                            <th colspan=\"3\" class=\"f14_fontb\" >مدیرعامل: $bossname</th>
                            <th colspan=\"6\" class=\"f14_fontb\"  >آدرس: $CompanyAddress</th>
					    </tr>
						
		
					 <tr style=\"color:green\">
                            <th class=\"f12_fontb\">ردیف</th>
                            <th class=\"f14_fontb\" >شاخص مورد ارزیابی</th>
							<th class=\"f14_fontb\" >امتیاز شاخص</th>
						     <th colspan=\"2\" class=\"f14_fontb\" >جزئیات شاخص های معیار</th>
							 	<th class=\"f14_fontb\" >امتیاز جزء</th>
					   
					        <th class=\"f14_fontb\" >امتیاز ارزیاب</th>
					       <th class=\"f14_fontb\" >میانگین امتیازات</th>
							<th class=\"f14_fontb\" >توضیحات</th>
                    </tr>
						
			     		";
                     
                ?>  </thead> <?php
	         			
				$srown=$rown;
				$rown=0;
				$row=0;
				$i=0;
				//$form2Titleold='ابزار و دستگاههای عمومی آبیاری تحت فشار';
                $form2Titleold="";
                $cnt=0;
	       while($resquery = mysql_fetch_assoc($result))
			{
			   $rown++;$row++;
                    if ($rown%2==1) 
                    {$b='b';$bg="background-color:#f3f3f3;";} else {$b='';$bg='';}
	//				$sumnumber+= $sumnum[$rown];    

		//			if ($login_RolesID==18 || $login_designerCO==1) $hideOP='';
			//		else {
				//	if ($operatorcoTitleB[$rown]) $hideOP=''; else {$hideOP='style="display:none"';$row--; }
					// }
					
						$form2Title=$resquery['form2Title'];
						if ($form2Titleold==$form2Title) $form2Title='';
						if ($form2Title!='') 
                        {
                            $form2Titleold=$form2Title;
                            $rowspan=" rowspan='".$resquery['cnt']."'";
                            $cnt++;
                        }
                        else $rowspan="";
                
					
						$linearray = explode('_',$resquery['form3Title']);
						$form31Title=$linearray[0];
                        $form3Title=$linearray[1];
				
						
					//	if ($span) {$rowspan='rowspan="4"';$i++;}
					//	if ($rowspan && $i>1) $rowspan='';
                        
						
						if ($form31Titleold==$form31Title) $form31Title='';
						if ($form31Title!='') $form31Titleold=$form31Title;
                        
                        print "<tr $hideOP>";
                        if ($rowspan!='')    
                        print    "<td $rowspan  class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $cnt</td>
                            
                            <td $rowspan  class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $form2Title</td>
                            
                            
                            <td $rowspan  class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $resquery[sum]</td>";
                              
                        if ($form31Title!='')
                        {
                            if ($arraystr4[$form31Title]>0)
                            print " 
                            <td rowspan='$arraystr4[$form31Title]'  class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $form31Title</td>";
                            else
							
						    print " 
                            <td colspan=\"1\"  class='f14_font$b'  style=\"color:#$cl;text-align: right;font-size:10.0pt;font-family:'B Nazanin';\">
                            $form31Title</td>"  ;
					    }
						
                        print "<td   class='f14_font$b'  style=\"color:#$cl;text-align: right;font-size:10.0pt;font-family:'B Nazanin';\">
                            $form3Title </td>";
                            
					   print "<td   class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $resquery[score] </td>";
                    		
                         print "<td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            <input  style = \"border:0px solid black;$bg\" size=2
							id='score1$rown' name='score1$rown'  value='$resquery[clerkscore]'   type='text' class='textbox' id='score'/>
							</td>";
					   print "<td   class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            $resquery[avgscore] 
                            
                            <a  target='_blank' href='formdetail.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ClerkIDinvestigation."_$resquery[form3ID]_".$form1Title."_".$form2Title."_".$form31Title."_".$form3Title."_"
                                    .rand(10000,99999)."'><img style = 'width: 20px;' src='../img/search.png' title=' ريز '></a>
                            
                            </td>";
                    		
							print"<td class='f14_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">
                            <input  style = \"border:0px solid black;$bg\" size=50
							id='description$rown' name='description$rown'  value='$resquery[Description]'   type='text' class='textbox' id='score'/>
							<input id='form3ID$rown' name='form3ID$rown'  value='$resquery[form3ID]' type='hidden'/>
                            <input id='form3detailID$rown' name='form3detailID$rown'  value='$resquery[form3detailID]' type='hidden'/>
                            </td>
                            
                            
                            ";
                        
                      ?>
				            
							
						</tr>
						
						
						
           <?php 
					
		//if ($rown==2) print $rown.'<='.$srown;exit;
 
		   }	
		   echo"<tr>
                            <th colspan=\"2\" class=\"f14_fontb\" >جمع امتیازات</th>
                            <th colspan=\"1\" class=\"f14_fontb\" ></th>
							<th colspan=\"1\" class=\"f14_fontb\" ></th>
							<th colspan=\"1\" class=\"f14_fontb\" >	</th>
							<th colspan=\"1\" class=\"f14_fontb\" >	</th>
							<th colspan=\"1\" class=\"f14_fontb\" >	</th>
							<th colspan=\"1\" class=\"f14_fontb\" >	</th>
							<th colspan=\"1\" class=\"f14_fontb\" >	</th>
							
					    </tr>
                        
                        <tr>
                        <input id='ClerkIDinvestigation' name='ClerkIDinvestigation'  value='$ClerkIDinvestigation' type='hidden'/>
                        <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                        </tr>
                        
						";
		   ?>
			  
                 </table>
				<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
                   
				
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
				   
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
