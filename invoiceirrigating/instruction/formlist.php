<?php 

/*

instruction/formlist.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
home.php.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php	
 
function fstr1($IDx//شناسه المنت لینک
,$order//ترتیب
,$title// عنوان
)//ایجاد لینک دانلود
{
						//$resquery['operatorapprequestID']
						
						$fstr1="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/propose/';
                        $handler = opendir($directory);
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if ($ID==$IDx)
                                    $fstr1="<a target='blank' href='../../upfolder/propose/$file' ><img style = 'width: 25px;' src='../img/Editinf.jpg' title='$title' ></a>";
								else 
                                    $fstr1="<a target='blank'  ><img style = 'width: 25px;' src='../img/photo_2016-12-10_15-46-20.jpg' title='$title' ></a>";
								
                            }
                        }
				return ($fstr1); 
 } 




// $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
   // $linearray = explode('_',$ids);
	//print_r ($linearray);
  //  $operatorcoID=$linearray[0];
//    $type=$linearray[1];
//print $login_userid;	
//print $login_RolesID;	
//print $login_OperatorCoID;	
$operatorcoID='';$AppID='';$where = " ";     
$operatorcoID=is_numeric($_GET["OpId"]) ? intval($_GET["OpId"]) : 0;
$AppID=is_numeric($_GET["ApId"]) ? intval($_GET["ApId"]) : 0;
 
if ($login_RolesID==2)	{$where = " and applicantmaster.operatorcoID='$login_OperatorCoID' ";$disabled='disabled';}
else if ($operatorcoID)	$where = " and applicantmaster.operatorcoID='$operatorcoID' ";
if ($AppID)	$where = " and applicantmasterdetail.ApplicantmasterID='$AppID' ";

$selectedCityId=$login_CityId;
 /*
 applicantmaster جدول مشخصات طرح
 ApplicantmasterID شناسه طرح
 ApplicantName عنوان پروژه
 operatorcoID شناسه مجری
 operatorco.Title عنوان مجری
 operatorapprequest جدول پیشنهاد قیمت
 operatorapprequestID شناسه جدول پیشنهاد قیمت    
 applicantmasterdetail جدول ارتباطی طرح ها
 ApplicantMasterIDsurat شناسه طرح صورت وضعیت
 ApplicantMasterIDmaster شناسه طرح اجرایی
 cityid شناسه شهر
 */
$sql = "SELECT applicantmaster.ApplicantmasterID,applicantmaster.ApplicantName,applicantmaster.operatorcoID,operatorco.Title
				,operatorapprequest.operatorapprequestID,applicantmasterdetail.ApplicantMasterIDsurat,applicantmasterdetail.ApplicantmasterID
	FROM applicantmaster 
	inner join operatorco on operatorco.operatorcoID=applicantmaster.operatorcoID 
	left outer join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.ApplicantMasterID
	left outer join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterdetail.ApplicantmasterID and operatorapprequest.operatorcoID=applicantmaster.operatorcoID
	where substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)  $where
		";	
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
 	
	$IDo[' ']=' ';
	$ID1[' ']=' ';
	while($row = mysql_fetch_assoc($result))
	{
		$ID1[trim($row['ApplicantName'])]=trim($row['ApplicantmasterID']);
		$IDo[trim($row['Title'])]=trim($row['operatorcoID']);
		$operatorapprequestID=$row['operatorapprequestID'];
	}	 
	$ID1=mykeyvalsort($ID1);
	$IDo=mykeyvalsort($IDo);
?>
<!DOCTYPE html>
<html>
<head>
  	<title>فرم ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

<script type='text/javascript'> 


	function selectpage(){
	
			//alert(document.getElementById('operatorcoID').value);
			var operatorcoID=document.getElementById('operatorcoID').value;
			var ApplicantMasterID=document.getElementById('ApplicantMasterID').value;
			var uid=document.getElementById('uid').value;
			
	       window.location.href ='?uid=' +document.getElementById('uid').value 
			+
			'&OpId=' + document.getElementById('operatorcoID').value 
			+ 
			'&ApId=' + document.getElementById('ApplicantMasterID').value
        ;

    }
	function showhidediv(id)
{
    var elem = document.getElementById(id);
    if(elem.style.display=='none')
    {
        elem.style.display='';
   	    document.getElementById('i'+id).style.color='blue';
		document.getElementById('i'+id).style.height = '40px';

    }
    else
    {
        elem.style.display='none';
	    document.getElementById('i'+id).style.color='';
		document.getElementById('i'+id).style.height = '';
    }
    
}

    
</script>
	
<style>

  
</style>
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
         <form action="formlist.php" method="post" >
		 
	 <?php echo"<p id='psh_nezarat' ></p>
		<table width='100%' id='ish_nezarat'>
						<tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;border:1px solid black;border-color:#D1D1D1;\" >
							<td colspan=4 class='no-print'	onclick=\"showhidediv('sh_nezarat');\">لیست فرم های نظارت بر اجرای طرح 
							</td>
						</tr>
		</table>
		<table id='sh_nezarat' style='display:none;' width='100%'>      
                ";?>
		 
		                <tr>
                            <th <span colspan="2" class="f14_font" > </span> </th>
							<?php   
							echo  "".select_option('operatorcoID','',',',$IDo,0,'',$disabled,'1','rtl',0,'',$operatorcoID,"onChange=\"selectpage();\"",'100%'); 
							echo  "".select_option('ApplicantMasterID','',',',$ID1,0,'','','1','rtl',0,'',$AppID,"onChange=\"selectpage();\"",'100%'); ?>
			            </tr>
                        <tr>
                            <th <span class="f14_fontb" > رديف  </span> </th>
							<th <span class="f14_fontb"> شماره فرم  </span> </th>
							<th <span class="f14_fontb" >  شرح فرم </span></th>
							<th <span class="f14_fontb" >توضیح </span></th>
                        </tr>
					<?php 
										$uid="formlist.php?uid=".rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).$AppID."_6_".$applicantstatesIDsurat.rand(10000,99999);
						//			./../upfolder/forms
						//$hrefhttp="http://koaj.ir/";
					?>
          					    <input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
       		
	                        <tr>    
								<td <span class="f10_font"  > 1 </span>  </td>
								<td <span class="f12_font" >فرم (1)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/tahvilzamin.docx' ><?php  print $title='  صورتجلسه تحویل زمین'; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
		                        <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
								  <td > </td>

							
							</tr>
	                        <tr>    
								<td <span class="f10_fontb"  > 2 </span>  </td>
								<td <span class="f12_fontb" >فرم (2)</a></td>
								<td <span class="f12_fontb"  ><a target='blank' href='../../upfolder/forms/arzeshyabi.pdf' > <?php  print $title='فرم ارزشیابی پیمانکار'; ?> </span>  </td>
								<td <span class="f10_fontb"  >  </span>  </td>
		                        <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
								<td > </td>
					
							</tr>
							
	                        <tr>   
							<?php 
							$rown=3;$cod=3;$dir='../../upfolder/forms/';$filename='jadval.jpg';$des='../insert/applicant_timing.php';$title='  جدول زمانبندی ';?>
								<td <span class="f10_font"  > <?php  print $cod; ?> </span>  </td>
								<td <span class="f12_font" >فرم (<?php  print $rown; ?>)</a></td>
								<td <span class="f12_font"  ><a target='blank' href=<?php  print $dir.$filename; ?> ><?php  print $title; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
						        <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
								<?php  $tablepng='../img/table.png';
								if ($AppID) echo "<td><a href='".$des."?uid=".rand(10000,99999).rand(10000,99999).
												rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
												rand(10000,99999).$AppID.'_5'.rand(10000,99999)."'>
												<img style = 'width: 25px;' src=$tablepng title=' $title'></a></td>"; else echo "<td></td>";?> 
						
							</tr>
							<tr>    
							
								<td <span class="f10_fontb"  > 4 </span>  </td>
								<td <span class="f12_fontb" >فرم (4)</a></td>
								<td <span class="f12_fontb"  ><a target='blank' href='../../upfolder/forms/surathaml.docx' ><?php  print $title=' صورتجلسه حمل لوازم '; ?></span>  </td>
								<td <span class="f10_fontb"  >  </span>  </td>
						        <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
			 <td > </td>
		                 
							</tr>
							<tr>    
								<td <span class="f10_font"  > 5 </span>  </td>
								<td <span class="f12_font" >فرم (5)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/suratmovaghat.docx' ><?php  print $title=' صورتجلسه تحویل موقت '; ?></span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
							
							    <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
				<?php 	if ($AppID) echo "<td><a  target='_blank' href='../appinvestigation/applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$AppID."_9_".$applicantstatesID.rand(10000,99999).
                                    "'><img style = 'width: 30px;' src='../img/folder_accept.png' title='$title'></a></td>"; else echo "<td></td>";?> 
		                    
							</tr>
		
							<tr>    
								<td <span class="f10_fontb"  > 6 </span>  </td>
								<td <span class="f12_fontb" >فرم (6)</a></td>
								<td <span class="f12_fontb"  ><a target='blank' href='../../upfolder/forms/suratdaem.docx' ><?php  print $title=' صورتجلسه تحویل دائم';?></span>  </td>
								<td <span class="f10_fontb"  >  </span>  </td>
							
						        <td ><?php  print fstr1($operatorapprequestID,$order,$title);   ?></td>
							<?php if ($AppID)	echo "<td><a  target='_blank' href='../appinvestigation/applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$AppID."_9_".$applicantstatesID.rand(10000,99999).
                                    "'><img style = 'width: 30px;' src='../img/folder_accept.png' title='$title'></a></td>"; 
									else echo "<td></td>";?> 
		                
							</tr>
		
		  		   
                </table>
		<?php echo"<p id='psh_entekhabtable' ></p>
		<table width='100%' id='ish_entekhabtable'>
						<tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;border:1px solid black;border-color:#D1D1D1;\" >
							<td colspan=4 class='no-print'	onclick=\"showhidediv('sh_entekhabtable');\">لیست فرمهای انتخاب پروژه 
							</td>
						</tr>
						
						
						
		</table>
		<table id='sh_entekhabtable' style='display:none;' width='100%'>      
                ";?>
			                       <tr>
                            <th <span class="f14_fontb" > رديف  </span> </th>
							<th <span class="f14_fontb"> شماره فرم  </span> </th>
							<th <span class="f14_fontb" >  شرح فرم </span></th>
							<th <span class="f14_fontb" >توضیح </span></th>
                        </tr>

	
								<tr>    
								<td <span class="f10_font"  > 1 </span>  </td>
								<td <span class="f12_font" >فرم (1)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/formpishnahad.docx' ><?php  print $title=' فرم پیشنهاد قیمت';?></span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
								<td ><?php  print fstr1($operatorapprequestID,$order,$title);   ?></td>
							<td > </td>
		        			
							</tr>
							<tr>    
								<td <span class="f10_fontb"  > 2 </span>  </td>
								<td <span class="f12_fontb" >فرم (2)</a></td>
								<td <span class="f12_fontb"  ><a target='blank' href='../../upfolder/forms/peyman.docx' ><?php  print $title=' پیمان اجرایی';?></span>  </td>
								<td <span class="f10_fontb"  >  </span>  </td>
								<td ><?php  print fstr1($operatorapprequestID,$order,$title);   ?></td>
							<td > </td>
		        			
							</tr>
							<tr>    
								<td <span class="f10_font"  > 3 </span>  </td>
								<td <span class="f12_font" >فرم (3)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/entekhabmojri.doc' ><?php  print $title=' انتخاب مجری';?></span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
								<td ><?php  print fstr1($operatorapprequestID,$order,$title);   ?></td>
							<td > </td>
		        			
							</tr>

		
                      </table>
           
		<?php echo"<p id='psh_tamdidtable' ></p>
		<table width='100%' id='ish_tamdidtable'>
						<tr style = \"background-color: #f2f2f2; font-family:'B Nazanin';font-size:12.0pt;line-height:150%;border:1px solid black;border-color:#D1D1D1;\" >
							<td colspan=4 class='no-print'	onclick=\"showhidediv('sh_tamdidtable');\">لیست فرمهای تمدید مدارک 
							</td>
						</tr>
						
						
						
		</table>
		<table id='sh_tamdidtable'  width='100%'>      
                ";?>
		                       <tr>
                            <th <span class="f14_fontb" > رديف  </span> </th>
							<th <span class="f14_fontb"> شماره فرم  </span> </th>
							<th <span class="f14_fontb" >  شرح فرم </span></th>
							<th <span class="f14_fontb" >توضیح </span></th>
                        </tr>

	          	            <tr>    
								<td <span class="f10_font"  > 1 </span>  </td>
								<td <span class="f12_font" >فرم (1)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/listmadarek.xlsx' > <?php  print $title='لیست مدارک مورد نیاز تمدید گواهی صلاحیت پیمانکار'; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
		                        <td ><?php  print fstr1($IDx,$order,$title);   ?></td>
								<td > </td>
					
							</tr>

	          	            <tr>    
								<td <span class="f10_font"  > 2 </span>  </td>
								<td <span class="f12_font" >فرم (2)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/arzyabi.pdf' > <?php  print $title='فرم ارزیابی'; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
		                        <td ></td>
								<td > </td>
					
							</tr>

	          	            <tr>    
								<td <span class="f10_font"  > 2 </span>  </td>
								<td <span class="f12_font" >فرم (3)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/peymankar.pdf' > <?php  print $title='آیین نامه تشخیص صلاحیت پیمانکاران'; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
		                        <td ></td>
								<td > </td>
					
							</tr>

	          	            <tr>    
								<td <span class="f10_font"  > 2 </span>  </td>
								<td <span class="f12_font" >فرم (4)</a></td>
								<td <span class="f12_font"  ><a target='blank' href='../../upfolder/forms/moshaver.pdf' > <?php  print $title='آیین نامه تشخیص صلاحیت مشاوران'; ?> </span>  </td>
								<td <span class="f10_font"  >  </span>  </td>
		                        <td ></td>
								<td > </td>
					
							</tr>
                                                        
                      </table>
           
            </div>
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
