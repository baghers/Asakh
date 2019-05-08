<!doctype html>
<style>
.limg
{
   width:100px;
 }
</style>
<html lang="en-us">
<head>
  <meta charset="utf-8">
  <meta name="description" lang="en" content="Lightbox for Bootstrap<, because it just works." />
  <meta name="author" content="Dan Jones">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/ico" href="favicon.png" />
</head>
<?php
        
?>
<body data-spy="scroll" data-target=".navbar">
<marquee  direction="down" scrollamount="2">       
 <ul class="thumbnails" data-toggle="lightbox" style="width:180px;text-align: center;margin: 0 auto" >
          <li >
           <?php 
             include('includes/connect.php');
             include('includes/check_user.php');
             require_once 'class/upload.class.php';	
             $upfile=new Upload();
             require_once('funcs.php');
             $con="";
             $Permissionvals=supervisorcoderrquirement_sql($login_ostanId);
             
             $result = mysql_query("select * from instructionmeet where 1=1 $con ");
             while($row = mysql_fetch_assoc($result))
             {
			 	?>
			 	 <a title=""   data-description="<?php echo $row['Description'];?>" class="thumbnail"  href="<?php $upfile->paths('instructionmeet','../upfolder/instructionmeet',$row['instructionID'],'1'); ?>" ><?php echo $row['HeaderTitle'];?></a>
			 	<?php
			 }  
            ?>
            
            </li>
        </ul>
        
         <ul class="thumbnails" data-toggle="lightbox" style="width:180px;text-align: center;margin: 0 auto" >
          <li >
           	 <a title=""    class="thumbnail"   ><?php
                if ($login_ostanId>0)
                {
                    /*
                     echo 
                    " دامنه قیمت لوله های پلی اتیلن PE100 بین ".
                    number_format($Permissionvals['maxpe100pipeprice']-$Permissionvals['lowrange'])." و ".
                    number_format($Permissionvals['maxpe100pipeprice']+$Permissionvals['uprange'])." ریال";
                    
                    echo 
                    "<br> دامنه قیمت لوله های پلی اتیلن PE80 بین ".
                    number_format($Permissionvals['maxpe80pipeprice']-$Permissionvals['lowrange'])." و ".
                    number_format($Permissionvals['maxpe80pipeprice']+$Permissionvals['uprange'])." ریال";
                    echo 
                    "<br> دامنه قیمت لوله های پلی اتیلن PE40 بین ".
                    number_format($Permissionvals['maxpe40pipeprice']-$Permissionvals['lowrange'])." و ".
                    number_format($Permissionvals['maxpe40pipeprice']+$Permissionvals['uprange'])." ریال";
                    echo 
                    "<br> دامنه قیمت لوله های پلی اتیلن PE32 بین ".
                    number_format($Permissionvals['maxpe32pipeprice']-$Permissionvals['lowrange'])." و ".
                    number_format($Permissionvals['maxpe32pipeprice']+$Permissionvals['uprange'])." ریال";     
                    */
                    
                    echo 
                    " سقف ق لوله های 100 برابر".
                    number_format($Permissionvals['maxpe100pipeprice'])." ریال"
                    .
                    "<br> سقف ق لوله های 80 برابر".
                    number_format($Permissionvals['maxpe80pipeprice'])." ریال"
                    . 
                    "<br> سقف ق لوله های 40 برابر".
                    number_format($Permissionvals['maxpe40pipeprice'])." ریال"
                    . 
                    "<br> سقف ق لوله های 32 برابر".
                    number_format($Permissionvals['maxpe32pipeprice'])." ریال";             
                } 

                
                ;?></a>  
          </li>
        </ul>
        
        </marquee>
  
 
<link href="css/bootstrap.lightbox.css" rel="stylesheet" media="screen">
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
<script src="js/bootstrap.lightbox.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.js"></script>
<script>
// Load code prettifier
$(document).ready(function(){
  prettyPrint();
});

// Fancy text animation
var ta = (function(a){
  var a = $(a), b = a.text(), c = b.length, d = 200;
  a.empty()
  for(i=0;i<c;++i){
    a.append($("<span/>").text(b[i]).fadeIn(1300+(d*i)));
  }
})("header p");
$("abbr[data-toggle=tooltip]").tooltip();
</script>
</body>
</html>