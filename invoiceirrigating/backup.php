<?php include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); 
      include('includes/elements.php'); ?><?php
       include('Mysqldump.php');

if ($login_Permission_granted==0) header("Location: login.php");

 //phpinfo();exit;

foreach (glob("temp/asakhnet_invir_*") as $filename) 
  {
    @unlink($filename);
  }


/*
for($i=0;$i<128;$i++) {
    echo "$i>" . bin2hex(chr($i)) . "<" . PHP_EOL;
}
*/


use Ifsnop\Mysqldump as IMysqldump;

$dumpSettings = array(
    'compress' => IMysqldump\Mysqldump::NONE,
    'no-data' => false,
    'add-drop-table' => true,
    'single-transaction' => true,
    'lock-tables' => true,
    'add-locks' => true,
    'extended-insert' => false,
    'disable-keys' => true,
    'add-drop-trigger' => true,
    'databases' => false,
    'add-drop-database' => false,
    'hex-blob' => true,
    'no-create-info' => false,
    'where' => ''
    );

$dump = new IMysqldump\Mysqldump(
    "$_server_db",
    "$_server_user",
    "$_server_pass",
    "$_server",
    "mysql",
    $dumpSettings);


//print "d0$_server_db $_server_user $_server_pass $_server";
  
$dump->start("temp/backup.sql");




//print "d1";




  $suffix =str_replace('/', '',  gregorian_to_jalali(date('Y-m-d')));
  #Execute the command to create backup sql file
  //exec("mysqldump -u $_server_user -p $_server_pass $_server_db  >  temp/backup.sql");
 
  #Now zip that file
  $zip = new ZipArchive();
  $filename = "temp/asakhnet_invir_$suffix.zip";
  if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
   exit("cannot open <$filename>n");
  }
  
  
//print "d2";

  $zip->addFile("temp/backup.sql" , "backup.sql");
  $zip->close();
  #Now delete the .sql file without any warning
  @unlink("temp/backup.sql");
  #Return the path to the zip backup file
  

//print "d3";
?>
<!DOCTYPE html>
<html>
<head>
  	<title>پشتیبان گیری</title>
    <meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
    <link rel="stylesheet" href="assets/style.css" type="text/css" />


    <!-- /scripts -->
    
    <script >
 
    </script>
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
            
			<!-- top -->
        	<?php include('includes/top.php'); ?>
            <!-- /top -->
            
            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->

            <!-- /main navigation -->
            
            <?php include('includes/subnavigation.php'); ?>
            
			<!-- header -->
            <?php include('includes/header.php'); ?> 
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <?php echo "<a  href='$filename'><img style = \"width: 4%;\" src=\"img/mail_receive.png\" title='دریافت' ></a>"; ?>
               
            </div> 
			<!-- /content -->
            
            <!-- footer -->
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>