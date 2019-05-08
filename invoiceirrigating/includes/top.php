<style>
  div #headimage {
   background:url(../assets/head6.jpg) no-repeat;
   height: 110px;
    width:100%;
   margin-left:5px;
   font-family: B Nazanin; 
}
 
  div #headimageold {
   background:url(../assets/headasakh.jpg) no-repeat;
   height: 110px;
   font-family: B Nazanin; 
}
 div #head2 {
   margin-left:80px;
}

</style>

	<?php 

       function ConvertFileToArray($FileName='')
          {
          	$ArrayName='';
        	$fcontents = file ($FileName);
          	while (list ($line_num, $line) = each ($fcontents))
        	{
          		 $lineKey=htmlspecialchars ($line);
          		 $Key =substr ($lineKey,0,strlen($line)-strlen(strstr ($lineKey, '=')));
          		 $Value= substr ($lineKey,strlen($line)-strlen(strstr ($lineKey, '='))+1, strlen(strstr ($lineKey, '='))-1);
          		 $ArrayName[$Key]=$Value;
            }
            return($ArrayName);
          }

        	$currentFile = $_SERVER["PHP_SELF"];
        	$parts = Explode('/', $currentFile);
        	$page = $parts[count($parts) - 1];
        
           
    
            $Array=ConvertFileToArray($_SERVER['DOCUMENT_ROOT'].'/cfg.txt');
	        $home_path_iri=trim("$Array[home_path_iri]");

       
	
        {$headimage='headimage';$style="";}

	   $style="";  
   
	
 ?>         
   <link rel="stylesheet" type="text/css" media="all" href="<?php echo ("http://". $_SERVER['HTTP_HOST']."/invoiceirrigating/style.css"); ?>" />
    <script type="text/javascript" src="<?php echo ("http://". $_SERVER['HTTP_HOST']."/invoiceirrigating/js/menu.js");?>"></script>
     <script type="text/javascript" src="../js/menu.js"></script>
      <link rel="stylesheet" type="text/css" media="all" href="../style.css" />
       <script type="text/javascript" src="js/menu.js"></script>
      <link rel="stylesheet" type="text/css" media="all" href="style.css" />


    
    
<div id="top" class='no-print'>

  <div id="<?php echo $headimage;?>">   
      <div id="main_container">
        <div id="head2">
         <?php 
         
         if (!$login_user) 
			{
				
               
                print "<a class='login' href='$home_path_iri/login.php' title='ورود به سایت'></a>";
			} 
			else 
			{ 
				print "<a class='logout' href='$home_path_iri/logout.php' title='خروج'></a>";
			} 
		
		
		
		?>
       <a class="contact1" href="<?php echo $home_path_iri."/"."contactus.php";?>" title="تماس با ما"></a>
       <a <?php echo $style;?>  class="contact" href="<?php echo $home_path_iri."/"."message/ticket.php";?>" title="تیکت پیام"></a>
       <a <?php echo $style;?>  class="help" href="<?php echo $home_path_iri."/"."help/help2.php";?>" title="سوالات متداول"></a>
       <a <?php echo $style;?>  class="help1" href="<?php echo $home_path_iri."/"."help/help1.php";?>" title="فایل های راهنما"></a>
       <a <?php echo $style;?>  class="home" href="<?php echo $home_path_iri."/"."home.php";?>" title="خانه"></a>
	   
        

       
<?php 
/*     
    echo "<br/><br /><br/><br/><br />
       
       <marquee behavior=\"scroll\" direction=\"center\" width=\"950\">";
        if ($login_user && $login_messagecnt==0)  echo "<p></p><p></p>";
            if ($login_messagecnt>0) 
            echo "<img style = 'width: 25px; float:right;' src='http://$_SERVER[HTTP_HOST]/invoiceirrigating/img/mail.png' /><a style='float:right; margin-right:5px;' href=\"http://$_SERVER[HTTP_HOST]/invoiceirrigating/message/msgsending4.php\"><font color='red' size='3'>شما $login_messagecnt پیام خوانده نشده دارید</font></a>";
            else echo "<br /><p></p>";
            
    echo "</marquee> ";
    
    */
?>
         
             


        </div>
		</br>
		
		
		<div id="menudiv">
     <ul class="menu" id="menul">
           <?php 
		     
           if ($login_RolesID=="") {$login_RolesID=0; $RolesID=0;}
               else if ($login_user) {$RolesID=100;}
  		
           if ($backdoor==1) $cond="";
              else $cond="(menuroles.RolesID=$login_RolesID or menuroles.RolesID=$RolesID) and";
              
            if (strtoupper($_SERVER[SERVER_NAME])!='ASAKH.NET' && strtoupper($_SERVER[SERVER_NAME])!='WWW.ASAKH.NET')  
                $cond.=" menu.MenuID not in (259) and";

		
		if ($login_RolesID==2)
		{		
			
			    $cond.=" menu.MenuID not in (62) and";
		}		
				
// عدم مشاهده منوها در استانها          
		   if ($login_ostanId==31 || $login_ostanId==21) $conds="and menu.MenuID not in (62)";else $conds="";
					  
              $querymenulevel1 = "SELECT distinct menu.* from menu
                  inner join menuroles on menu.MenuID=menuroles.MenuID 
                  where $cond menu.published=1 and menu.menutype='topmenu'  
				  $conds
                  order By menu.parent,menu.ordering";
                
				//print $querymenulevel1;
                //exit;
				$menulevel1 = mysql_query($querymenulevel1);
				
				$i=0;
							      
				while($resmenulevel1 = mysql_fetch_assoc($menulevel1))
				{
					$a1[$i]['name']=$resmenulevel1['name'];
	if ($resmenulevel1['linkID']>0)	$linkID='/'.rand(10000,99999).rand(10000,99999).$login_userid.'b'.rand(10000,99999).rand(10000,99999).$login_RolesID;else $linkID='';
					
					$a1[$i]['link']=$resmenulevel1['link'].$linkID;
					$a1[$i]['parent']=$resmenulevel1['parent'];
					$a1[$i]['MenuID']=$resmenulevel1['MenuID'];
					$i++;
					
					
				}
				//print $login_userid;				
				//print $i;
				//exit;
				$i1=0;
            	while($i1<$i)
                {
				
				
				
					if ($a1[$i1]['parent']==0)
					{
					 		
							if ($a1[$i1]['MenuID']==176)
							{
								if ($login_userid ==28)
								print "<li><a href=".$home_path_iri."/".$a1[$i1]['link'].">".$a1[$i1]['name']."</a>";
							}
						else if ($a1[$i1]['MenuID']==177)
							{
								if ($login_userid ==28 || $login_userid ==22)
									print "<li><a href=".$home_path_iri."/".$a1[$i1]['link'].">".$a1[$i1]['name']."</a>";
							}
						else 
						print "<li><a href=".$home_path_iri."/".$a1[$i1]['link'].">".$a1[$i1]['name']."</a>";
						


					$parent = $a1[$i1]['MenuID'];
						$p=0;
						$i2=0;
						while($i2<$i)
						{
						
							if ($a1[$i2]['parent']==$parent)
							{
								
								
							 if($p==0) { print "<ul>"; $p=1;}  
							 
                	           if($parent==177)
				                {
                                   if($login_userid ==28 || $login_userid ==22)
                                    print "<li><a href=".$home_path_iri."/".$a1[$i2]['link'].">".$a1[$i2]['name']."</a>";
                                }
				                else 
                                print "<li><a href=".$home_path_iri."/".$a1[$i2]['link'].">".$a1[$i2]['name']."</a>";
								
								
                                $parent1 = $a1[$i2]['MenuID'];
								
								$q=0;
								$i3=0;
								while($i3<$i)
								{
								
								
									if ($a1[$i3]['parent']==$parent1)
									{
									
										if ($q==0) { print "<ul>"; $q=1;}
										print "<li><a href=".$home_path_iri."/".$a1[$i3]['link'].">".$a1[$i3]['name']."</a></li>";
										
									}
									
									
									
									
									$i3++;
								}
								if ($q==1) {print "</ul>"; $q=0;} 
								print("</li>");
							}
							$i2++;
						}
						if ($p==1) {print "</ul>"; $p=0;}
						print("</li>");
					}
					$i1++;             
                }
                print("</ul>"); 
?>
     </div>

                <script type="text/javascript">
                	var menul=new menul.dd("menul");
                	menul.init("menul","menuhover");
                    $(document).ready(function(){
                		$(".home_works_section:first a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'slow',theme:'light_rounded',slideshow:2000, autoplay_slideshow: false});
                		$(".home_works_section:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',slideshow:10000});
                	});
                </script>



             
            <!-- /main navigation -->   
          </div>
	</div>
	
<div class="breadcrumb">
<?php

	$currentFile = $_SERVER["PHP_SELF"];
    $parts = Explode('/', $currentFile);
    $page = $parts[count($parts) - 1];
    $pageparrent = $parts[count($parts) - 2];
	$pageparrent2 = $parts[count($parts) - 3];
    if ($pageparrent=='invoiceirrigating')
		$sitemasir=$page;
    else if ($pageparrent2=='invoiceirrigating') 
	$sitemasir=$pageparrent.'/'.$page;
	else
	$sitemasir=$pageparrent2.'/'.$pageparrent.'/'.$page;
	$img='<img src='.$home_path_iri.'/'.'img/arrow_rtl.png >';
 $i1=0;
    $str="";
	while($i1<$i)
    {
		if ($a1[$i1]['link']==$sitemasir)
		{
			$i2=0;
			$MenuID=$a1[$i1]['parent'];
			$str="<a class=hoverlink href=".$home_path_iri."/".$a1[$i1]['link'].">".$a1[$i1]['name']."</a>";
	
			while($i2<$i)
			{
				if ($a1[$i2]['MenuID']==$MenuID)
				{
			
					$MenuID=$a1[$i2]['parent'];
					$str="<a class=link href=".$home_path_iri."/".$a1[$i2]['link'].">".$a1[$i2]['name']."</a>".$img.$str;
					
					
				
				}
				$i2++;
			
			}
			break;
		
		}
		$i1++;
	}
	
	
if ($str==null) echo $str;
    else 
	echo "شما اینجا هستید : " .$str;
	
?> 
</div> 
<div class="welcome">
<?php 

                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/profile/';
		         	$handler = opendir($directory);
					while ($file = readdir($handler)) 
                    {
					    // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDfile=$linearray[0];
							$Nofile=$linearray[1];
							if (($IDfile==$login_userid) && ($Nofile==1)) $imgprofile=$file;
		               }
				    }
				   
//print $imgprofile;
$permitrolsid = array("1",  "4", "10", "11", "12", "5", "13", "14", "6", "15", "16", "16", "7","18");
	if (! in_array($login_RolesID, $permitrolsid) && (!($login_userdebt>=0))) 
	     $lbl="<font color='red' size='1px'>" .$login_fullname ." عزیز خوش آمدید </font>";
    else $lbl="<font color='green' size='1px'>" .$login_fullname ." عزیز خوش آمدید </font>";
	
	if ($login_user) {
		if($imgprofile)
		print $lbl . '<img src='."/upfolder/profile/".$imgprofile.' width=25 height=25>';
		else
		print $lbl;
		   }
   else 
    print 'میهمان عزیز خوش آمدید';
	?>
</div>

</div>









