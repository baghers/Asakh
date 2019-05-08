<?php 

/*

insert/approveaccessories.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
members_producers.php
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
    $IDproducer=$linearray[2];//شناسه تولید کننده
    if ($IDproducer>0)
    {
        //clerk جدول کاربران
        //BR شناسه تولیدکننده
        $countquery  = "SELECT ClerkID FROM clerk where BR='$IDproducer'";
    		
						   		try 
								  {		
									       $result = mysql_query($countquery);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		$row = mysql_fetch_assoc($result);

        $userid=$row['ClerkID'];   
    }
    else
        $userid=$login_userid; //شناسه کاربر   
	}
  //اسناد تایید شده
  //ClerkID شناسه کاربر 
$countquery  = "SELECT * FROM `approvement` where ClerkID='$userid'";

						   		try 
								  {		
									      $resultrows = mysql_query($countquery);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$resultcount = mysql_num_rows($resultrows);
 
//print $countquery;

if ($_POST)
{  
	for ($i = 1; $i <= $resultcount; $i++)
	{
	  $Title = $_POST["Title$i"];//عنوان 
	  $approveno = $_POST["approveno$i"];//شماره
	  $approvedate = $_POST["approvedate$i"];//تاریخ
	  $approvevalidationdate = $_POST["approvevalidationdate$i"];//تاریخ اعتبار
	  $approveIssuer = $_POST["approveIssuer$i"];//مرجع صدور
	  $ApproveID = $_POST["ApproveID$i"];//شناسه جدول
	  //approvement جدول اسناد تایید شده
	$sql = "UPDATE approvement SET Title = '$Title',
		approvedate = '$approvedate',
		approvevalidationdate = '$approvevalidationdate',
		approveno = '$approveno',
		approveIssuer = '$approveIssuer' WHERE ApproveID = $ApproveID";
		
						   		try 
								  {		
									  $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
	
}

$insert = "INSERT INTO approvement (ApproveID ,
ClerkID ,
Title ,
approvedate ,
approvevalidationdate ,
approveno ,
approveIssuer
) ";

$j=0;
for ($i = $resultcount+1; $i <= 15; $i++)
 {
  if ($j==1) $str=","; else { $j=1; $str=" VALUES "; }
  if ($_POST["Title$i"]<>"") 
  {
	  $Title = $_POST["Title$i"]; 
	  $approveno = $_POST["approveno$i"];
	  $approvedate = $_POST["approvedate$i"];
	  $approvevalidationdate = $_POST["approvevalidationdate$i"];
	  $approveIssuer = $_POST["approveIssuer$i"];
	  $strquery.= $str ."(
	NULL , '$userid', '$Title', '$approvedate', '$approvevalidationdate', '$approveno', '$approveIssuer'
	)";   
  }
  }
  
   $query = $insert . $strquery;
   
								try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
   
    if ( $login_ProducersID>0)
    {
		
        if ($_FILES["file1"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file1"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file1"]["size"] / 1024)>200)//تائیدیه گزارش آزمون ماشینها وادوات کشاورزی
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file1"]["name"])));
            $attachedfile=$login_ProducersID.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_1*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file1"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
 		
        if ($_FILES["file2"]["error"] > 0)//تاییدیه تولید 
        {
            //echo "Error: " . $_FILES["file2"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file2"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file2"]["name"])));
            $attachedfile=$login_ProducersID.'_2_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_2*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file2"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file3"]["error"] > 0)//تائیدیه گزارش آزمون تیپ 20 سانت 
        {
            //echo "Error: " . $_FILES["file3"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file3"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file3"]["name"])));
            $attachedfile=$login_ProducersID.'_3_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_3*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file3"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file4"]["error"] > 0)//تائیدیه گزارش آزمون تیپ 30 سانت 
        {
            //echo "Error: " . $_FILES["file4"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file4"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file4"]["name"])));
            $attachedfile=$login_ProducersID.'_4_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_4*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file4"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file5"]["error"] > 0)//پروانه بهره برداری 
        {
            //echo "Error: " . $_FILES["file5"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file5"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file5"]["name"])));
            $attachedfile=$login_ProducersID.'_5_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_5*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file5"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file6"]["error"] > 0)//پروانه تاسیس 
        {
            //echo "Error: " . $_FILES["file6"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file6"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file6"]["name"])));
            $attachedfile=$login_ProducersID.'_6_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_6*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file6"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file7"]["error"] > 0) //مجوز عرضه لوله های پلی اتیلن
        {
            //echo "Error: " . $_FILES["file7"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file7"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file7"]["name"])));
            $attachedfile=$login_ProducersID.'_7_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_7*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file7"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file8"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file8"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file8"]["size"] / 1024)>200)//انجمن دارندگان نشان استاندارد
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file8"]["name"])));
            $attachedfile=$login_ProducersID.'_8_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_8*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file8"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file9"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file9"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file9"]["size"] / 1024)>200)//گواهینامه ایزو
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file9"]["name"])));
            $attachedfile=$login_ProducersID.'_9_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_9*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file9"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file10"]["error"] > 0) 
        {
            //echo "Error: " . $_FILES["file10"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file10"]["size"] / 1024)>200)//مجوز آب و خاک
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file10"]["name"])));
            $attachedfile=$login_ProducersID.'_10_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_10*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file10"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file11"]["error"] > 0) //پروانه کاربرد علامت استاندارد
        {
            //echo "Error: " . $_FILES["file11"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file11"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file11"]["name"])));
            $attachedfile=$login_ProducersID.'_11_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_11*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file11"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file12"]["error"] > 0) //پروانه کاربرد علامت استاندارد اجباری
        {
            //echo "Error: " . $_FILES["file12"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file12"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file12"]["name"])));
            $attachedfile=$login_ProducersID.'_12_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_12*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file12"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file13"]["error"] > 0) //تاییدیه گزارش آزمون ماشینها و ادوات کشاورزی
        {
            //echo "Error: " . $_FILES["file13"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file13"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file13"]["name"])));
            $attachedfile=$login_ProducersID.'_13_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_13*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file13"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file14"]["error"] > 0) //تاییدیه آزمون ماشین های کشاورزی
        {
            //echo "Error: " . $_FILES["file14"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file14"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file14"]["name"])));
            $attachedfile=$login_ProducersID.'_14_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_14*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file14"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }
        		
        if ($_FILES["file15"]["error"] > 0)//پروانه کاربرد علامت استاندارد تشویقی 
        {
            //echo "Error: " . $_FILES["file15"]["error"] . "<br>";
            //exit;
        } 
        else 
        {
		 if (($_FILES["file15"]["size"] / 1024)>200)
        {
            print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل پیشنهاد قیمت را کاهش دهید";
            exit;
        }
            $ext = end((explode(".", $_FILES["file15"]["name"])));
            $attachedfile=$login_ProducersID.'_15_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
            foreach (glob("../../upfolder/producerapproval/accessories/".$login_ProducersID.'_15*') as $filename) 
            {
                unlink($filename);
            }
                
            move_uploaded_file($_FILES["file15"]["tmp_name"],"../../upfolder/producerapproval/accessories/" .$attachedfile);   
        }       
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست تاییدیه ها</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    <script>



                
    </script>
	
	 <script type="text/javascript">
            $(function() {
                $("#approvedate1, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate1, #simpleLabel").persiandatepicker();   
				$("#approvedate2, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate2, #simpleLabel").persiandatepicker();   
				$("#approvedate3, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate3, #simpleLabel").persiandatepicker();   
				$("#approvedate4, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate4, #simpleLabel").persiandatepicker();   
				$("#approvedate5, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate5, #simpleLabel").persiandatepicker();   
				$("#approvedate6, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate6, #simpleLabel").persiandatepicker();   
				$("#approvedate7, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate7, #simpleLabel").persiandatepicker();   
				$("#approvedate8, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate8, #simpleLabel").persiandatepicker();   
				$("#approvedate9, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate9, #simpleLabel").persiandatepicker();   
				$("#approvedate10, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate10, #simpleLabel").persiandatepicker();   
				$("#approvedate11, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate11, #simpleLabel").persiandatepicker();   
				$("#approvedate12, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate12, #simpleLabel").persiandatepicker();   
				$("#approvedate13, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate13, #simpleLabel").persiandatepicker();   
				$("#approvedate14, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate14, #simpleLabel").persiandatepicker();   
				$("#approvedate15, #simpleLabel").persiandatepicker();   
                $("#approvevalidationdate15, #simpleLabel").persiandatepicker();   
				
            });
                
    </script>
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="approveaccessories.php" method="post" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                <br/>
                <table id="records" width="95%" align="center">
                       
                   <tbody>           
               

                   <?php         
                   $fstr = array();
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/accessories/';
                    $handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
                            if (($ID==$login_ProducersID) && ($No==1) )
                                $fstr['1']="<a target='blank' href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png'  ></a>";
                            if (($ID==$login_ProducersID) && ($No==2) )
                                $fstr['2']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";
                            if (($ID==$login_ProducersID) && ($No==3) )
                                $fstr['3']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==4) )
                                $fstr['4']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==5) )
                                $fstr['5']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==6) )
                                $fstr['6']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==7) )
                                $fstr['7']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==8) )
                                $fstr['8']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==9) )
                                $fstr['9']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==10) )
                                $fstr['10']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==11) )
                                $fstr['11']="<td><a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a></td>";        
                            if (($ID==$login_ProducersID) && ($No==12) )
                                $fstr['12']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==13) )
                                $fstr['13']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==14) )
                                $fstr['14']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            if (($ID==$login_ProducersID) && ($No==15) )
                                $fstr['15']="<a target='blank'  href='../../upfolder/producerapproval/accessories/$file' ><img style = 'width: 15%;' src='../img/accept.png' ></a>";        
                            
                        }
						
                    }
					$sql = "SELECT * FROM approvement where ClerkID=$userid";
					$resultP = mysql_query($sql);
					?>
								<tr>
									<td  style="text-align:center; height:25px;">رديف</td>
									<td  style="text-align:center;">عنوان</td>
									<td  style="text-align:center;">تاريخ</td>
									<td  style="text-align:center;">شماره</td>
									<td  style="text-align:center;">تاريخ اعتبار</td>
									<td  style="text-align:center;">مرجع صادر كننده</td>
									<td  style="text-align:center;">اسكن</td>
									<td></td>
								</tr>					  
					<?php for ($i = 1; $i <= 15; $i++)
					{
					$rowp = mysql_fetch_assoc($resultP);
					?>
						<tr>
							<td class='label'><?php print $i;?></td>
							<td><input  name=<?php echo "Title$i";?> type="text" class="textbox" id=<?php echo  "Title$i";?> 
									value="<?php echo $rowp['Title']; ?>" size="25" maxlength="50" /></td>
							<td><input placeholder="انتخاب تاریخ"  name=<?php echo "approvedate$i";?> type="text" class="textbox" id=<?php echo "approvedate$i"; ?> 
									value="<?php if (strlen($rowp['approvedate'])>0) echo $rowp['approvedate'];?>" size="8" maxlength="10" /></td>
							<td><input  name=<?php echo "approveno$i"; ?> type="text" class="textbox" id=<?php echo "approveno$i";?> 
									value="<?php echo $rowp['approveno']; ?>" size="5" maxlength="10" /></td>
							<td><input placeholder="انتخاب تاریخ"  name=<?php echo "approvevalidationdate$i"; ?> type="text" class="textbox" id=<?php echo "approvevalidationdate$i"; ?> 
									value="<?php if (strlen($rowp['approvevalidationdate'])>0) echo $rowp['approvevalidationdate'];?>" size="8" maxlength="10" /></td>
							<td><input  name=<?php echo "approveIssuer$i"; ?> type="text" class="textbox" id=<?php echo "approveIssuer$i" ;?> 
									value="<?php echo $rowp['approveIssuer']; ?>" size="25" maxlength="50" /></td>
							<td class='data'><input type='file' name=<?php echo "file$i";?> id=<?php echo "file$i"; ?> accept='application/zip'>
							<input type="hidden" name=<?php echo "ApproveID$i"; ?> value = "<?php echo $rowp['ApproveID']; ?>"/></td>
							<td><?php print $fstr["$i"];?></td>
						</tr>
                        <?php } ?>   
                           <tr> 
                            <td colspan="8"><input  name='submit' type='submit' class='button' id='submit' value='ثبت' /></td></tr>
                     
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colspan="1" id="fooBar">  &nbsp;</span>
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
