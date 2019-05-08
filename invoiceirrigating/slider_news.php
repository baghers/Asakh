<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>Carousel Side-Caption - Bootsnipp.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="js/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .carousel-caption {
  position: relative;
  left: 0%;
  right: 0%;
  bottom: 0px;
  z-index: 10;
  padding-top: 0px;
  padding-bottom: 0px;
  color: #000;
  text-shadow: none;
  & .btn {
    text-shadow: none; // No shadow for button elements in carousel-caption
  }
}

.carousel {
    position: relative;
}

.controllers {
    position: absolute;
    top: 0px;
}

.carousel-control.left, 
.carousel-control.right {
    background-image: none;
}
.ttl
{
	font-family: tahoma,arial;
	text-align: center;
	font-size: 18px;
	color: #34a6cb;
	font-weight: bold;
}
    </style>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<?php
 $query = "SELECT * FROM news ";
 $result = mysql_query($query);
 $num = mysql_num_rows($query);
 require_once 'class/upload.class.php';	
 $upfile=new Upload(); 
?>
<body>
<div style="border-style: solid;border-width: 2px;border-color: #259bda;margin-top:50px;width:90%">
<div class="ttl">اخبار</div>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
<!-- Wrapper for slides -->
<div class="carousel-inner" style="margin:1;">
<?php

    $i=1;
    while($row = mysql_fetch_array($result))
      {
      	if($i==1)
      	  $class='item active';
      	else 
      	  $class='item';
      	  $id=$row['newsID'];
      	  //echo $id;
      	 
      ?>
      <div class="<?php echo $class;?>"  >
  <div class="holder col-sm-11" >
    <?php $upfile->disply2('news','../upfolder/news',$id,'1');?>
  </div>
  <div class="col-sm-11">
    <div class="carousel-caption" >
        <p style="text-align: right"><?php echo $row['HeaderTitle'];
        
        
        for($i1=0;$i1<=200-strlen($row['HeaderTitle']);$i1++)
            echo "&nbsp;";
        ?></p>  
    </div>
  </div>
</div>
       <?php
       $i++;
      }
?>


</div>
<div class="controllers col-sm-12 col-xs-12">
<!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
  <!-- Indicators -->
  <ol class="carousel-indicators">
  <?php 
   for($i=0;$i<$num;$i++)
   {
   	  if($i==0)
   	    $class='class="active"';
   	  else 
   	     $class='';
   	 echo'<li data-target="#carousel-example-generic" data-slide-to='.$i.' '.$class.'></li>';
   }
  ?>
    
   
  </ol> 
</div>
</div>
<script type="text/javascript">
$(window).bind("load resize slid.bs.carousel", function() {
  var imageHeight = $(".active .holder").height();
  $(".controllers").height( imageHeight );
  console.log("Slid");
});
</script>
</body>
</html>
