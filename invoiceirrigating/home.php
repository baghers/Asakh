<?php

/*
home.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

//print_r($_SESSION);
 include('includes/connect.php'); 

 include('includes/check_user.php');

//print $login_OperatorCoID.'$'.$login_DesignerCoID.'$'.$login_CityId;exit;
 include('includes/elements.php');
 

// include('Chart.php');


 	$condition="and substring(applicantmaster.CityId,1,2)=19";
    



 $msg ="";
 $win=0;


 ?>
<!DOCTYPE html>
<html>

<head>
  	<title>صفحه اصلي</title>
<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="assets/style.css" type="text/css" />
      <script language='javascript' src='assets/jquery.js'></script>

  
</head>
<body 



 >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
             <div id="wrapper">

             <!-- top -->
               

             <?php 
             include('includes/top.php'); ?>
             <!-- /top -->
            

            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->
            
            <?php include('includes/subnavigation.php'); ?>
             <!-- header -->
             
             
            
             <!-- /header -->

             <!-- content -->
             <div id="content" >
                   <table BORDER="3" style="text-align: center;font-size:18.0pt;font-family:'B Nazanin';">
                <tr>
                  
                    <td  class="leftsite">
                    <br />
                   
                    <table style="width:200px;text-align: center;font-size:18.0pt;font-family:'B Nazanin';">
                       <!-- <td><img align="middle" style = 'width: 100%;display: block;margin-left: auto;margin-right: auto;' src='img/commercial/connect.jpg' title='تبليغات در سامانه صدور ليست لوازم' ></td>
                        <tr><td><img align="middle" style = 'width: 100%;display: block;margin-left: auto;margin-right: auto;' src='img/commercial/555555.jpg' title='تبليغات در سامانه صدور ليست لوازم' ></td></tr>
                        <tr><td><img align="middle" style = 'width: 100%;display: block;margin-left: auto;margin-right: auto;' src='img/commercial/00901701.gif' title='تبليغات در سامانه صدور ليست لوازم' ></td></tr>
                        <tr><td><a href="" target="_blank"><img align="middle" style = 'width: 100%;display: block;margin-left: auto;margin-right: auto;' src="img/commercial/555.jpg" alt="تبليغات" /></a></td></tr>
                        <tr><td><a href="http://myfreefarm.ir/?ref=iranjib" target="_blank"><img align="middle" src="img/commercial/5865.gif" style="display: block;margin-left: auto;margin-right: auto;" title="مزرعه رايگان من" alt="مزرعه رايگان من" /></a></td></tr>
	 		
                    -->

<?php if (strtoupper($_SERVER[SERVER_NAME])=='TOOSRAHAM.IR' || strtoupper($_SERVER[SERVER_NAME])=='WWW.TOOSRAHAM.IR') 
{?>
		<tr><td>
		<img id='jxlzesgtjxlzapfusizpjzpejzpe' style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=1015977&p=rfthobpdrfthdshwpfvljyoejyoe", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt='logo-samandehi' src='https://logo.samandehi.ir/logo.aspx?id=1015977&p=nbpdlymanbpdujynbsiyyndtyndt'/>
		</td></tr>

			
			
<?php } else
{?>
      <tr><td><a href="http://WWW.TOOSRAHAM.IR/" target="_blank" title="مشاهده نماد الکترونیک در سایت "><img alt="مشاهده نماد الکترونیک در سایت اصلی" 
	  style = 'display: block;margin-left: auto;margin-right: auto;'  src="img/commercial/enamad.png" /></a></td></tr>
    	<?php }   
	?>				
<tr><td>					
<script type="text/javascript" src="http://1abzar.ir/abzar/tools/time-date/date-fa.php?color=333333&font=8&bg=FCFCF7&kc=CAE09D&kadr=0"></script><div style="display:none">
</div>
</td></tr>
                    
                    <tr><td> <div class="module_menu">
                        <div>
                            <div>
                             <div><table><tr><td><h3><!--حاضرین در سایت --></h3></td></tr>
                    <?php 
						    
                    /*
                            echo "
                            
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;&nbsp;<img align='middle' src='img/3.gif'>&nbsp;&nbsp;&nbsp;افراد آنلاين: $online&nbsp;</td></tr>
                            <tr><td>&nbsp;&nbsp;<img align='middle' src='img/2.gif'>&nbsp;&nbsp;بازديد 24 ساعت جاري: $todayvisit&nbsp;</td></tr>
                            <tr><td>&nbsp;&nbsp<img align='middle' src='img/1.gif'>&nbsp;&nbsp;بازديد ماه جاري: $monthlyvisit&nbsp;</td></tr>
                            <tr><td>&nbsp;&nbsp;<img align='middle' src='img/4.gif'>&nbsp;&nbsp;بازديد کل: $totalvisit&nbsp;</td></tr>
                             <tr><td>&nbsp;</td></tr>";
                             */
                            ?>
                            
                    </table>
                     
			
<?php if ($login_Domain=='skh') {$weathercity="IRXX0031";$sharcity='9-1';$hrefhttp="http://kj-agrijahad.ir/";$alt="سازمان جهاد کشاورزي خراسان جنوبی";
		                               $srcimg="img/commercial/sturgeonir116 - Copy.png";}
        else if ($login_Domain=='yazd')  {$weathercity="IRXX0042";$sharcity='11-2';$hrefhttp="http://yazdj.ir/";$alt="سازمان جهاد کشاورزي یزد ";
		                               $srcimg="img/commercial/sturgeonir115 - Copy.png";}
		 else if ($login_Domain=='nkh')  {$weathercity="IRXX0042";$sharcity='11-2';$hrefhttp="http://nkj.ir/";$alt="سازمان جهاد کشاورزي خراسان شمالی";
		                               $srcimg="img/commercial/sturgeonir115 - Copy.png";}
        else if ($login_Domain=='rkh') {$weathercity="IRXX0008";$sharcity='10-17';$hrefhttp="http://koaj.ir/";$alt="سازمان جهاد کشاورزي خراسان رضوي";
		                               $srcimg="img/commercial/sturgeonir114 - Copy.png";}
		else if ($login_Domain=='loc') {$weathercity="IRXX0008";$sharcity='10-17';$hrefhttp="http://koaj.ir/";$alt="سازمان جهاد کشاورزي خراسان رضوي";
		                               $srcimg="img/commercial/sturgeonir114 - Copy.png";}

?>
		 </div></div></div></div>
                       </td></tr>
                       <tr><td class="leftsite">
                        <div class="module_menu"><div><div><div><h3>صورتجلسه</h3>
                             <?php include("lightbox.php"); ?> 
		                </div>

      		  </div></div></div></div>
                        </td></tr>
  		           						
                        <tr><td class="leftsite"><div class="module_menu"><div><div><div><h3>اوقات شرعی</h3>
<script type="text/javascript" src="http://1abzar.ir/abzar/tools/azan/v2/?color1=333333&color2=F07022&bg=FFFFFF&kc=B3181D&kadr=0&shahr=<?php print $sharcity;?>"	
		 ></script><div style="display:none"></div>
</div></div></div></div></td></tr>

                     
                     <tr><td class="leftsite">
                     <div class="module_menu">
                        <div>
                            <div>
                             <div>
							 <h3>پيگيري وضعيت طرح</h3>
							  <form action="viewapplicantstate.php" method="post" onSubmit="return CheckForm()"  style="float:none;">
								 <p style="font-size:11px; font-family:Tahoma;">كد رهگيري طرح را در كادر زير وارد كنيد: </p>
							     <p><input  name="Bankcode" type="text" class="textbox" id="Bankcode" 
						  value="" size="20" maxlength="20" /></p>
								<input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" />
							  </form>
							 </div>
							 </div>
							 </div>
							 </div> </td></tr>
                             
                        <tr><td><a href="register_farmer.php" target="_blank" title="ثبت نام بهره بردار"><img alt="ثبت نام بهره بردار" 
                        style = 'display: block;margin-left: auto;margin-right: auto;'  src="img/commercial/farmer.jpg" /></a></td></tr>
                             
                             
                        
                        <tr><td><a href="http://www.maj.ir/" target="_blank" title="وزارت جهاد کشاورزي"><img alt="وزارت جهاد کشاورزي" 
                        style = 'display: block;margin-left: auto;margin-right: auto;'  src="img/commercial/1majbtn_114.png" /></a></td></tr>
                        
                        <tr><td><a href=<?php print $hrefhttp;?> target="_blank"><img alt=<?php print $alt;?> style = 'display: block;margin-left: auto;margin-right: auto;' src='<?php 
                        print $srcimg;?>' /></a> </td></tr>
                        
                        <tr><td><a href="http://novinabyari.maj.ir" target="_blank"><img alt="دفتر توسعه سامانه هاي نوين آبياري" 
                        style = 'display: block;margin-left: auto;margin-right: auto;' src="img/commercial/2.jpg" /></a></td></tr>
                        
                        
						<tr><td><a href="http://www.iaeo.ir/" target="_blank"><img alt="سازمان نظام مهندسي کشاورزي و منابع طبيعی کشور" 
                        style = 'display: block;margin-left: auto;margin-right: auto;' src="img/commercial/1 - Copy.jpg" /></a></td></tr>
                             
                              
                    </table>
                    <br />
                            
                    </td>
                    
                    
                    
                    
                    <td >
                    <br /><br />
                    <div style = "width: 95%;display: block;margin-left:5px;margin-right: 5px;text-align: center;font-size:9.0pt;font-family:'B Nazanin';">
                            <TABLE  >
                            <?php 
                            
                            if ($login_userid>0)
                            {
                                 if ($login_RolesID=="") {$login_RolesID=0; $RolesID=0;}
                                    else if ($login_user) {$RolesID=100;}
  		
                                    if ($backdoor==1) $cond="";
                                    else $cond="(menuroles.RolesID=$login_RolesID or menuroles.RolesID=$RolesID) and";         
		                          if ($login_ostanId==31 || $login_ostanId==21) $conds="and menu.MenuID not in (62)";else $conds="";
					  
                                $querymenulevel1 = "SELECT distinct menu.* from menu
                                inner join menuroles on menu.MenuID=menuroles.MenuID 
                                where $cond menu.published=1 and menu.menutype='topmenu' and `menu`.`parent` in (48,44,13,80,2) 
				                $conds
                                ORDER BY case parent when 48 then 1 when 44 then 2 when 2 then 3 when 13 then 4 when 80 then 5 end,menu.MenuID";
                                $menulevel1 = mysql_query($querymenulevel1);
				                $i=1; 
                                print "<div id='desktop' ><tr>";
                                while($resmenulevel1 = mysql_fetch_assoc($menulevel1))
                                {
                                    $mi=$resmenulevel1['MenuID'];
                                    print "<td><a title=\"".$resmenulevel1['name']."\" href=\"".$resmenulevel1['link']."\" target=\"_blank\">
                                    <figure><img  src='img/desktop/U$mi.jpg' width=90 height=90>
                                    <figcaption>".$resmenulevel1['name']."</figcaption>
                                    </figure>
                                    </a></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                                    if($i%8==0)
                                    print "</tr><tr><td>&nbsp;</td></tr><tr>";
                                    $i++;
                                    if ($i>24) break;
                                }
                                print "</tr></div>";
                
                            }
                            /////////////////////////////
                       ?>     
                              </TABLE  >
					</div>
					<div >						
                            <TABLE  >
                         <?php if (!($login_userid>0)) echo "<tr><td ><FONT style=\"text-align: right;font-size:12.0pt;font-family:'B Nazanin';\">
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <a href='instruction/formlist.php' target='_blank' >مدارک و فرمها</a></FONT></td></tr>"; ?> 
                         
                        <?php    
                            if ($login_Domain=='yazd'){
                            echo "<tr><FONT style=\"text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">
                             آغاز به کار سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری";
                            echo " توسط مديريت محترم آب و خاک و امور فني و مهندسي سازمان جهاد کشاورزي استان یزد <br>";
                            echo " </FONT><FONT style=\"color:#0000ff;text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">";
                            echo " </FONT><FONT style=\"text-align: center;font-size:12.0pt;font-family:'B Nazanin';\">";
                            echo "1394/07/17<br></FONT></tr>";
                         }   
                        else if ($login_Domain=='nkh'){
                            echo "<tr><FONT style=\"text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">
                             آغاز به کار سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری";
                            echo " توسط مديريت محترم آب و خاک و امور فني و مهندسي سازمان جهاد کشاورزي خراسان شمالی<br>";
                            echo " </FONT><FONT style=\"color:#0000ff;text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">";
                            echo " </FONT><FONT style=\"text-align: center;font-size:12.0pt;font-family:'B Nazanin';\">";
                            echo "1394/07/17<br></FONT></tr>";
                         }   
                        else if ($login_Domain=='skh'){
                            echo "<tr><td colspan=14><FONT style=\"text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">
                             آغاز به کار سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری";
                            echo " توسط مديريت محترم آب و خاک و امور فني و مهندسي سازمان جهاد کشاورزي خراسان جنوبی<br>";
                            echo " </FONT><FONT style=\"color:#0000ff;text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">";
                            echo " </FONT><FONT style=\"text-align: center;font-size:12.0pt;font-family:'B Nazanin';\">";
                            echo "1394/09/17<br></FONT><td/></tr>";
                         }   

                        else  if ($win==1){
                            echo "<tr><td colspan=14><FONT style=\"text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">
                             افتتاح رسمی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری";
                            echo " توسط معاونت آب و خاک و صنایع <br>";
                            echo " </FONT><FONT style=\"color:#0000ff;text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">";
                            echo "وزارت جهاد کشاورزی<br>";
                            echo " </FONT><FONT style=\"text-align: center;font-size:12.0pt;font-family:'B Nazanin';\">";
                            echo "1394/09/16<br></FONT><td/></tr>";
                         }   
						 
                        else {
                            echo "<tr><td colspan=14 style=\"text-align: center;\"><FONT style=\"text-align: center;font-size:18.0pt;font-family:'B Nazanin';\">
                            رونمایی از سامانه مدیریت کنترل پروژه طرحهای آب و خاک<br>";
                            echo " توسط مديريت محترم آب و خاک و امور فني و مهندسي سازمان جهاد کشاورزي خراسان رضوي<br>";
                            echo " </FONT><FONT style=\"color:#0000ff;text-align: center;font-size:20.0pt;font-family:'B Nazanin';\">";
                        	echo "<td/></tr>";
                         }   
                         
                            ?>
							               </TABLE  >
					</div>
					<div >						
              			   <TABLE  >
				
                            <tr><td colspan="7">
                             <?php include('slider_news2.php'); ?>
                            </td>
                            </tr><tr>
                             <td colspan="7">
                             <?php include('slider_news.php'); ?>
                            </td></tr>
                            <?php

						 
						 
						 
                            ?>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                            <td></td>
                           </tr>
                                 
                           	  
                                  
                            </TABLE>
                            </div>
                    </td>		
                    
                    
                   
                  
                </table>
            </div>
             
             <!-- /content -->
            
            <!-- footer -->
            <!-- /footer -->

    <!-- /container -->

   
<?php 

$permitrolsidforchart = array("1", "13","5","11","13","14","18","19","16");

if (in_array($login_RolesID, $permitrolsidforchart)) {


?>
 <div id="content" style="margin-top: 15px;">
    <br />
<?php 
echo "
  <iframe class=\"rightc\" src=\"temp/LastTotalchart.html\"></iframe>
  <iframe class=\"leftc\" src=\"temp/belaavazchart.html\"></iframe>
  <iframe class=\"rightc\" src=\"temp/LastTotalchartop.html\"></iframe>
  <iframe class=\"leftc\" src=\"temp/freechart.html\"></iframe>";
  
  ?>
   </div>
  <?php } ?>        
    
                            
             <?php 
              include('includes/footer.php'); ?>                 

    
   <center>
  
 <td><a href="../help/Adobe.Flash.Player.16.0.0.296.firefox_asakhdotnet.zip" ><img style = "width: 3.5%;" src="img/flash player.jpg" title='فلش پلير براي تمام مرورگرها به غير از اينترنت اکسپلورر' ></a></td>
       
       <td> <a target="_blank" href="../help/Firefox Setup 35.0.1_asakhdotnet.zip">
            <img title='دريافت مرورگر فايرفاکس' id="logo" style = "width: 3%;" pagespeed_url_hash="277148153" 
            src="img/firefox.png" data-g-label="Firefox-home" data-g-event="Firefox-logo" alt="دريافت مرورگر فايرفاکس"></img>
        </a></td>
        <td><a href="../help/dopdf-7_asakhdotnet.zip" ><img style = "width: 3.3%;" src="img/product_84_1_original.jpeg" title='نرم افزار چاپ پي دی اف' ></a></td>
        
        <td><a href="help/AnyDesk.exe" ><img style = "width: 3.3%;" src="img/anydesk.jpg" title='نرم افزار ارتباطی' ></a></td>
	<td>
		<img id='jxlzesgtjxlzapfusizpjzpejzpe' style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=1015977&p=rfthobpdrfthdshwpfvljyoejyoe", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt='logo-samandehi' src='https://logo.samandehi.ir/logo.aspx?id=1015977&p=nbpdlymanbpdujynbsiyyndtyndt'/>
    </td>
    

   </center>
           
            
 </div>

              	</div>
        <!-- /wrapper -->

</body>

</html>