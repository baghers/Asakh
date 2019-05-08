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
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3" ></li>
      <li data-target="#myCarousel" data-slide-to="4"></li>
      <li data-target="#myCarousel" data-slide-to="5"></li>
      <li data-target="#myCarousel" data-slide-to="6" ></li>
      <li data-target="#myCarousel" data-slide-to="7"></li>
      <li data-target="#myCarousel" data-slide-to="8"></li>
      <li data-target="#myCarousel" data-slide-to="9"></li>
    </ol>

	
	
	
	
    <!-- Wrapper for slides -->

<?php 

//print "(((($login_Domain )))";

if ($login_Domain=='rkh' || $login_Domain=='loc') { ?>	
	
    <div class="carousel-inner" role="listbox">
    
    <?php 
       /* $query = "SELECT * FROM img ";
    $result = mysql_query($query);
    $i=1;
    while($row = mysql_fetch_array($result))
      {
      	if($i==1)
      	  $class='item active';
      	else 
      	  $class='item';
      ?>
      <div class="<?php echo $class; ?>">مجوز راه اندازی سامانه توسط معاون فنی مجری طرح جناب آقای مهندس گرجی
        <img src="img/slider/<?php echo $row['nam'];?>"  >
      </div>
       <?php
       $i++;
      }*/
      ?>
      <div class="item active">بازدید از پروژه های سیستم های نوین آبیاری
        <img src="img/slider/img1.gif" >
      </div>
	  <div class="item">مجوز راه اندازی سامانه توسط معاون فنی مجری طرح جناب آقای مهندس گرجی
        <img src="img/slider/img6.gif"  >
      </div>
   <div class="item">مجوز بهره برداری از سامانه توسط معاون وزیر جناب آقای مهندس اکبری
        <img src="img/slider/img8.gif"  >
      </div>
      <div class="item">بازدید از پروژه های سیستم های نوین آبیاری
        <img src="img/slider/img2.gif" >
      </div>
	  <div class="item">مجوز بهره برداری از سامانه توسط ریاست سازمان جناب آقای مهندس مزروعی
        <img src="img/slider/img8.gif"  >
      </div>
	  
      <div class="item">بازدید از پروژه های سیستم های نوین آبیاری
        <img src="img/slider/img3.gif"  >
      </div>
	  <div class="item">بازدید از نمایشگاه بین المللی مشهد
        <img src="img/slider/img4.gif"  >
      </div>
   <div class="item">تقدیرنامه استفاده از سامانه در سیستم های نوین آبیاری
        <img src="img/slider/img9.gif"  >
      </div>
	  
	  <div class="item">بازدید از نمایشگاه بین المللی مشهد
        <img src="img/slider/img5.gif"  >
      </div>
   <div class="item">بازدید از نمایشگاه بین المللی مشهد
        <img src="img/slider/img7.gif"  >
      </div>
    </div>
	
<?php } 

if ($login_Domain=='yazd') 
{ 
print "<div class=\"carousel-inner\" role=\"listbox\">
      <div class=\"item active\">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src=\"img/slider/img11.gif\" >
      </div>
      <div class=\"item\">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src=\"img/slider/img12.gif\" >
      </div>
    </div>";    
    
    
}  


if ($login_Domain=='nkh') 
{ 
print "<div class=\"carousel-inner\" role=\"listbox\">
      <div class=\"item active\">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src=\"img/slider/img11.gif\" >
      </div>
      <div class=\"item\">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src=\"img/slider/img12.gif\" >
      </div>
    </div>";    
    
    
}  

 if ($login_Domain=='skh') { ?>	
	
    <div class="carousel-inner" role="listbox">
      <div class="item active">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src="img/slider/img21.gif" >
      </div>
      <div class="item">جلسه معرفی سامانه مدیریت کنترل پروژه سیستم های نوین آبیاری
        <img src="img/slider/img22.gif" >
      </div>
    </div>
	
<?php }  ?>	
	
	
	
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


