<?php 

/*

insert/entezami.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
members_designercos.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php		
if ($login_Permission_granted==0) header("Location: ../login.php");
if (!$_POST) 
{ 
    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ID);
    $TBLNAME=$linearray[0];//نام جدول
    $TBLTITLE=$linearray[1];//عنوان
    $idval=$linearray[2];//مقدار
    $TYPECON = $linearray[3];//نوع

} 	
if(isset($_POST['sabt']))
{

	
    $tbl = $_POST['tbl'];
    if($tbl=='designerco')//طراح
    {
      $id='DesignerCoID';
	  $pg='members_designercos';
	  
	}
	elseif($tbl=='operatorco')//مجری
    {
      $id='operatorcoID';
	  $pg='members_operatorcos';
	}
	elseif($tbl=='producers')//تولید کننده
    {
      $id='ProducersID';
	  $pg='members_producers';
	  
	}

    $query = "UPDATE $tbl SET 
	         ent_DateFrom ='$_POST[ent_DateFrom]',ent_DateTo ='$_POST[ent_DateTo]',
	         ent_Hectar ='$_POST[ent_Hectar]',ent_Num ='$_POST[ent_Num]',ent_Desc ='$_POST[ent_Desc]',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "' 
     where $id='$_POST[idval]'  ";
	 
  header("Location:../".$pg.".php");        

 
  	 					  	try 
								  {		
									    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

//    		print $query;      
			
			
}		

?>
<!DOCTYPE html>
<html>
<head>
  	<title>انتظامی</title>

	   <meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>
		<script type="text/javascript" src="../lib/jquery2.js"></script>
		<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
		<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
		<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
		<script type='text/javascript' src='../jquery.autocomplete.js'></script>
		<script type='text/javascript' src='localdata.js'></script>
		<link rel="stylesheet" type="text/css" href="main.css" />
		<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#ent_DateFrom,#simpleLabel").persiandatepicker(); 
                $("#ent_DateTo,#simpleLabel").persiandatepicker();  
            });
                
    </script>
    <!-- /scripts -->
    <style>
    	.row
    	{
		  padding:15px 0 0 0 ;	
		}
		.col
		{
			padding:100px  ;
		}
		.lbl
		{
		   padding: 0 10px  ;
		   width:300px;
		   
		}
		.txt
		{
			 
		}
		.ttl
		{
			font-weight: bold;
			font-size: 16px;
			text-align: center;
		}
		.txtarea
		{
			
		}
		
    </style>
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content" align="center">
            <?php if ($result==1) print $secerror= '<p class="note">اطلاعات با موفقيت ذخيره شد.</p>'; ?>
            <form  method="post">
            <input type="hidden" name="tbl" id="tbl" value="<?php echo $TBLNAME;  ?>" >
            <input type="hidden" name="idval" id="idval" value="<?php echo $idval;  ?>" >
				<?php 
				 
				$sql="select * from $TBLNAME where $TBLNAME"."ID"."=$idval";	  
				 $result = mysql_query($sql); 
				 $res = mysql_fetch_assoc($result);
			    ?>
                
                 <div class="ttl"> فرم انتظامی  شرکت <?php echo $res['Title']; ?></div>
				 
				 <div class="row">
                      <td class="label">تعلیق&nbsp;شرکت:</td>
					</tr>
                 </div>   
				 
				     

                <div class="row">
                	 <span class="lbl">از تاریخ :</span>
                	 <span class="txt"><input placeholder="انتخاب تاریخ"  name="ent_DateFrom" type="text" class="textbox" id="ent_DateFrom" 
					 value="<?php if (strlen($res['ent_DateFrom'])>0) echo $res['ent_DateFrom'];?>" size="10" maxlength="10" /></span>
                	<span class="col"><span class="lbl">تا :</span>
                	<span class="txt"><input placeholder="انتخاب تاریخ"  name="ent_DateTo" type="text" class="textbox" id="ent_DateFrom" 
					value="<?php if (strlen($res['ent_DateTo'])>0) echo $res['ent_DateTo'];?>" size="10" maxlength="10" /></span></span> 
                </div>
				
                <div class="row">
                	 <span class="lbl">حداکثر مساحت قابل پیشنهاد :</span>
                	 <span class="txt"><input name="ent_Hectar" id="ent_Hectar"
					 value="<?php if ($res['ent_Hectar']>0) echo $res['ent_Hectar']; else echo '';?>"  />&nbsp هکتار</span>
                </div>
               
			   <div class="row">
                	 
					 <span class="lbl">حداکثر تعداد پروژه قابل انجام :</span>
                	 <span class="txt"><input name="ent_Num" id="ent_Num" 
					   value="<?php if($res['ent_Num']>0) echo $res['ent_Num']; else echo '';?>"  />&nbsp پروژه</span></span> 
                </div>
				
                <div class="row">
                	 <span class="lbl" style="vertical-align: top">شرح :</span>
                	 <span class="txtarea"><textarea  rows="2" cols="70"   name="ent_Desc" id="ent_Desc"    /><?php echo $res['ent_Desc'];?></textarea></span>
                </div>
				
                <div class="row" align="center">
                	<input   name='sabt' type='submit' class='button' id='sabt' value='ثبت' />
                </div>
            </form> 
            </div>
			<!-- /content -->

		</div>
		

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
