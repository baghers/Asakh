<?php 
/*
pricesaving/pricesavingpermision.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingpermision.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

                
/*
clerk کاربران
city نقش ها
moneyapprovepermit امکان ثبت قیمت
*/  

if ($_POST['submit1'])
{
    $cond=" and clerk.city in (5,13,14,20) ";
    $cond.=" and  substring(clerk.cityid,1,2)=substring('$login_CityId',1,2) "; 

    $query="select clerkID as _value,concat(clerk.CPI,' ',clerk.DVFS) as _key from clerk
        where  clerk.CPI<>'ج'
        $cond
        order by _key COLLATE utf8_persian_ci";
    $ID = get_key_value_from_query_into_array($query);
    foreach ($ID as $key => $value)
    {
        if ($_POST["clerk$value"]=='on')
			
			if ($value==570 || $value==65 || $value==80 || $value==82 || $value==84 || $value==487)
            $query="update clerk set moneyapprovepermit=1 where clerk.clerkid='$value'";
			else 
			$query="update clerk set moneyapprovepermit=2 where clerk.clerkid='$value'";

        else     
            $query="update clerk set moneyapprovepermit=0 where clerk.clerkid='$value'";
            
            //print $query;
                    
        					try 
								  {		
									    mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    

                    
    }
    
    
}
    


?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value+ '&g2id=' + document.getElementById('g2id').value;
        
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
            <?php include('../includes/header.php'); 
            
            print "
            
            <div id='content'>
                <form action='pricesavingpermision.php' method='post'>
                  <td colspan='3'></td>";
        
        $cond=" and clerk.city in (13,14,20) ";
        $cond.=" and  substring(clerk.cityid,1,2)=substring('$login_CityId',1,2) "; 

		 $query="select clerkID as _value,clerkID as _key from clerk where ifnull(moneyapprovepermit,0)>0";
		 $moneyapprovepermit = get_key_value_from_query_into_array($query);
        
        
        $query="select clerkID,clerk.CPI,DVFS from clerk 
                     where 1=1
        $cond";
                     $result = mysql_query($query);
                     $allclerkID[' ']=' ';
                     while($row = mysql_fetch_assoc($result))
                        if (decrypt($row['DVFS'])<>'ج')
                        $allclerkID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                     $allclerkID=mykeyvalsort($allclerkID);
                                  
        //print_r ($allclerkID);
                 
        $cnt=0;
        print "<tr>
               <table style='border:0px solid;'><tr>";
        foreach ($allclerkID as $key => $value)
        {
            
            if ($value>0)
            {
                $cnt++;
                if (in_array($value, $moneyapprovepermit))
                print "<td class='data'><input type='checkbox' id='clerk$value' checked name='clerk$value'>".($key)."</input></td>";
                else
                print "<td class='data'><input type='checkbox' id='clerk$value' name='clerk$value'>".($key)."</input></td>";
                if (($cnt%1)==0)
                    print "</tr><tr>";   
            }
        }
        print "</tr></table></tr>";
        
        print "<td colspan='2'><input name='submit1' type='submit' class='button' id='submit1' value='ثبت'/></td>";

            
            ?>      
                  
                  
                  <table id="records" width="95%" align="center">
                    <thead>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                             
                                        

                   
                    </tbody>
                   
                </table>
                  
                       
                </form>      
            </div>
			<!-- /header -->

			<!-- content -->
			
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
