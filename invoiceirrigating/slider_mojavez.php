 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="js/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
<style>
  .carousel-inner > .item > img,
  .carousel-inner > .item > a > img {
      width: 510px;
	  height: 210px;
      margin: auto;
	  padding:0;
  }
  .carousel-inner > .item
  { 
	  font-size:16px;
	  font-family:B Titr;
  }
</style>
<?php 
    require_once('funcs.php');
    require_once 'class/upload.class.php';	
    $upfile=new Upload(); 
    $con="";
    $query = "SELECT * FROM mojavez where showhide=1 $con order by cod";
    $result = mysql_query($query)or die(mysql_error());
    $num = mysql_num_rows($result) ;
	//print $query;
    ?>	
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <?php
        for($i=1;$i<$num;$i++)
        {
		  if($i==0)
      	    $class='class="active"';
      	  else 
      	    $class='';?>
      	  <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>"  <?php echo $class; ?> ></li>
      	  <?php
		}   
      ?>
      
    </ol>
 <div class="carousel-inner" role="listbox" style="border-style: solid;border-width: 2px;border-color: #259bda">
    <?php 
    $i=0;
    while($row = mysql_fetch_array($result))
      {
      	if($i==0)
      	  $class='item active';
      	else 
      	  $class='item';
      	  $id=$row['mojavezID'];
    ?>
    <div class="<?php echo $class; ?>"><?php echo $row['HeaderTitle'];?>
        <?php $upfile->disply2('mojavez','../upfolder/mojavez',$id,'1');?>
    </div>
       <?php
       $i++;
      }
      ?>
    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>


