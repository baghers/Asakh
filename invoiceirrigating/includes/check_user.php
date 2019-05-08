<?php
/*
includes/check_user.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/

session_start();

 	

/*
 $login_RolesID; نقش کاربر لاگین شده
 $login_ProducersID; شناسه تولیدکننده لاگین شده
 $login_CityId; شناسه شهر لاگین شده
 $login_OperatorCoID; شناسه مجری لاگین شده
 $login_DesignerCoID; شناسه طراح لاگین شده
 $login_userid; شناسه کاربر لاگین شده
*/
    
$login_isfirstsixmonth = 0;
$login_userid = 0;
$login_username = 'مهمان';
$login_user = false;
$login_is_admin = 0;
$login_week_p = false;
$backdoor=0;
$login_lawIDnotviewed=0;
$target="_blank"; 
$login_Domain='rkh';$login_ostan=19;

if (strtoupper($_SERVER[SERVER_NAME])=='LOCALHOST' || $_SERVER[SERVER_NAME]=='127.0.0.1' || substr($_SERVER[SERVER_NAME],0,7)=='192.168') 
{$login_Domain='loc';$login_ostan=101;$target="_self";}

    $login_userid = $_SESSION['userid'];

    
$times=time(); 


  /*
    menu جدول منو ها
    menuroles جدول منوهای هر نقش
    producers جدول مجری
    designerco جدول طراحان
    producers جدول تولیدکنندگان
    designer جدول شرکت های طراح
    members جدول اعضای هیئت مدیره
    operatorapprequest جدول پیشنهاد قیمت های طرح
    clerk جدول کاربران
    fundationYear تاریخ تاسیس شرکت 
    fundationno شماره مدرک تاسیس 
    fundationIssuer مرجع صادر کننده صلاحیت 
    boardchangeno شماره نامه آخرین تغییرات
    boardchangedate تاریخ آخرین تغییرات هیئت مدیره
    boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
    boardIssuer مرجع صادرکننده مدرک هیئت مدیره
    copermisionno تعداد پروژه های قابل انجام
    StarCo تعداد ستاره های شرکت
    ent_Num تعداد انتظامی بودن شرکت
    ent_DateTo پایان انتظامی بودن شرکت
    copermisiondate تاریخ مجوز شرکت
    copermisionvalidate تاریخ اعتبار مجوز شرکت
    copermisionIssuer مرجع صادر کننده مجوز شرکت
    contractordate تاریخ قرارداد شرکت
    contractorvalidate تاریخ اعتبار قرارداد شرکت
    contractorno شماره نامه قرارداد شرکت
    contractorIssuer مرجع صادرکننده قرارداد شرکت
    contractorRank1 رتبه شرکت نفر 1
    contractorField1 شرح رتبه شرکت نفر 1
    contractorRank2 رتبه شرکت نفر 2
    contractorField2 شرح رتبه شرکت نفر 2
    engineersystemdate تاریخ مدرک مهندس شرکت
    engineersystemvalidate تاریخ اعتبار مدرک مهندس شرکت
    engineersystemno شماره مدرک مهندس شرکت
    engineersystemIssuer مرجع صادر کننده مدرک مهندس شرکت
    engineersystemRank رتبه  مهندس شرکت
    engineersystemField شرح مهندس شرکت
    valueaddeddate تاریخ گواهی ارزش افزوده
    valueaddedvalidate تاریخ اعتبار گواهی ارزش افزوده
    valueaddedno شماره گواهی ارزش افزوده
    valueaddedIssuer مرجع گواهی ارزش افزوده
    operatorcoID شناسه شرکت مجری
    membersinfo.FName نام
    membersinfo.LName نام خانوادگی
    projectcount92 تعداد پروژه های اول دوره 
    projecthektar92 مساحت پروژه های انجام داده شده 
    Title عنوان شرکت
	CompanyAddress آدرس شرکت
    Phone2 تلفن دوم شرکت
    bossmobile موبایل مدیر عامل شرکت 
    corank رتبه شرکت

    */    
    
if ($login_userid>0){

    $AdminRolesID=1;
    
    $selectedfield='';
    if ($login_userid!=22 && $login_userid!=4)
    {
        $cond="
        inner join menu on menu.link='".substr($_SERVER['PHP_SELF'],19)."'
        inner join menuroles on  menu.MenuID=menuroles.MenuID and (menuroles.RolesID= clerk.city or menuroles.RolesID=100)";
        
     
	 $selectedfield=',issave';   
    }
	 
    if ($login_ostan==101) $login_where='1=1'; 
 	else 
    $login_where="substring(clerk.CityId,1,2)=case ifnull(law.ostan,0) when 0 then '$login_ostan' else law.ostan end  and 
    substring(clerk.CityId,1,2)=$login_ostan";
	
	$query = "SELECT distinct keycodeID,clerk.*	
				$selectedfield
				,msg.cnt msgcnt,producers.PipeProducer ,clerk.CPI,clerk.DVFS
				,operatorco.Disabled opDisabled,designerco.Disabled dDisabled ,operatorco.Code Codeop ,
                case designerco.designercoID>0 when 1 then designerco.corank else case  operatorco.operatorcoID>0 when 1 then operatorco.corank 
                else producers.rank end  end corank
                ,designerco.copermisionvalidate copermisionvalidated,designerco.boardvalidationdate boardvalidationdated
                ,designerco.CompanyAddress CompanyAddressd,designerco.AccountNo AccountNod,designerco.BossName BossNamed,designerco.Phone Phoned
                ,producers.guaranteeExpireDate guaranteeExpireDatep,producers.boardvalidationdate boardvalidationdatep
                ,producers.copermisionvalidate copermisionvalidatep,producers.CompanyAddress CompanyAddressp
		FROM clerk 
	$cond
	left outer join producers on producers.ProducersID=clerk.BR 
	left outer join operatorco on operatorco.operatorcoID=clerk.HW
	left outer join designerco on designerco.designercoID=clerk.MMC 
    left outer join (select ReceiverID,count(*) cnt from messages where ReceiverID='$login_userid' and status=1
					group by ReceiverID) msg on msg.ReceiverID=clerk.ClerkID
     left outer join keycode on keycode.user=clerk.ClerkID and keycode.user='$login_userid'
    and TIME_TO_SEC(TIMEDIFF(NOW(), keycode.SaveTime))<86400 and ifnull(keycode.disabled,0)=0
                    
	WHERE clerk.ClerkID = " . $login_userid;
	
    
    //print $query;exit;
    	
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
    
    $issave=$row['issave'];
	$login_DesignerCoID = $row['MMC'];
    
    $linearray = explode('_',$row["Codeop"]); 
    $login_Codeop1=$linearray[0];
    $login_Codeop2=$linearray[1];
    
    $login_Permission_granted=0;
	if ($row['ClerkID']>0 || $login_designerCO==1)
    $login_Permission_granted=1;
    
    
        $dat=$row["copermisionvalidated"];
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
    $resquerycopermisionvalidated=$j_y.'/'.$j_m.'/'.$j_d ;
    
    $dat=$row["boardvalidationdated"];
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
    $resqueryboardvalidationdated=$j_y.'/'.$j_m.'/'.$j_d ;
    $dateYmd=$row['SaveDate'];
        
    
    //////////////////////////////
    if ($login_ProducersID>0)
    {
        $dat=$row["guaranteeExpireDatep"];
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
    $resqueryguaranteeExpireDate=$j_y.'/'.$j_m.'/'.$j_d ;
    
    $dat=$row["boardvalidationdatep"];
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
    $resqueryboardvalidationdatep=$j_y.'/'.$j_m.'/'.$j_d ;
    $dat=$row["copermisionvalidatep"];
    $linearray = explode('/',$dat);                        
    $j_y=$linearray[0];
    $j_m=$linearray[1];
    $j_d=$linearray[2];
    if ($j_d<10 && (strlen($j_d)<=1)) 
        $j_d='0'.$j_d;
         if ($j_m<10 && (strlen($j_m)<=1)) 
        $j_m='0'.$j_m;
        if (strlen($j_y)==2)
            $j_y='13'.$j_y;
    $resquerycopermisionvalidatep=$j_y.'/'.$j_m.'/'.$j_d ;    
    
    
    $perror="";
    $dateYmd=$row['SaveDate'];
    $resquerycorank=$row['corank'];
    
    if (($resquerycorank<1)||($resquerycorank>5)) 
        $perror.="<br>*رتبه شرکت نامعتبر می باشد";
    if ($resqueryguaranteeExpireDate<$dateYmd)
        $perror.="<br>*انقضاء تاریخ اعتبار ضمانتنامه بانکی (".$resqueryguaranteeExpireDate.")";                              
    if (compelete_date($resqueryboardvalidationdatep)<$dateYmd)
        $perror.="<br>انقضاء تاریخ اعتبار هیئت مدیره.";
    if (compelete_date($resquerycopermisionvalidatep)<$dateYmd)
        $perror.="<br>انقضاء تاریخ مجوز شرکت.";
        
    //$vr=rand ( 1,10);
    //if ($vr==5)
        //if ((strlen($perror)>0 || strlen($row["CompanyAddressp"])<=0) && ($row['issave']>0) ) 
            //header("Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/members_producers.php");
    ////////////////////////////////////    
    }
    
    $Disable=$row['Disable'];
    
    $encrypted_string=$row['NOC'];
    $encryption_key="!@#$8^&*";
    $decrypted_string="";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
        
    $login_username = $decrypted_string;
    
    //print $row['NOC'].'sa';
    
    
    if ($row['WN']=='123') $login_week_p=true;
    if ($row['QOS']==1)
	   $login_is_admin = 1;
    else
       $login_is_admin = 0;   
    
	$login_keycodeID = $row['keycodeID'];
       
	$login_isfulloptionc = $row['isfulloption'];
    //print $row['isfulloptiondate'];exit;
    
    if ($row['isfulloptiondate']>date('Y-m-d'))
    $login_isfulloption =1;
    else
    $login_isfulloption =0;
    

    
	$login_ProducersID = $row['BR'];
	$login_OperatorCoID = $row['HW'];
    $login_corank = $row['corank'];
    if ($row['dDisabled']>0)
    $login_dDisabled = $row['dDisabled'];
    else
    $login_opDisabled = $row['opDisabled'];
    $encrypted_string=$row['CPI'];
    $encryption_key="!@#$8^&*";
    $decrypted_string="";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $encrypted_string=$row['DVFS'];
    $encryption_key="!@#$8^&*";
    $decrypted_string.=" ";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
	$login_fullname = $decrypted_string;
	$login_CityId = $row['CityId'];
    
    $login_ostanId = substr($row['CityId'],0,2);
    
    if ($row['city']==18 ||$row['city']==1) $login_moneyapprovepermit=1;
    else  $login_moneyapprovepermit = $row['moneyapprovepermit'];

    if ($login_username=='jhashem' || $login_username=='b.salami')
    {$backdoor=1;$login_designerCO=1;}
    else
	{$backdoor=0;$login_designerCO=0;}
    

	 
    
        

	if ($login_ostanId<>$login_ostan && $login_ostan<>101) $login_Permission_granted=0;

	
	$login_RolesID = $row['city'];
    
  
    
    if ($row['city']=='22')	
    $login_limited=" limit 0,5;";
    else 
    $login_limited="";
    
	$login_PipeProducer=0;
    
	$login_Producertype=$row['PipeProducer'];
    
	if ($row['PipeProducer']==1)  $login_PipeProducer=1;
	
	$login_messagecnt=$row['msgcnt'];
	
	$login_user = true;
    

	
 
 
    $login_where="substring(clerk.CityId,1,2)=case ifnull(law.ostan,0) when 0 then '$login_ostanId' else law.ostan end  and 
    substring(clerk.CityId,1,2)=$login_ostanId";
	
	
	$Dom='and ifnull(law.dom,0)=0';
if ($Disable==3)
if (strtoupper($_SERVER[SERVER_NAME])=='TOOSRAHAM.IR' || strtoupper($_SERVER[SERVER_NAME])=='WWW.TOOSRAHAM.IR') 
	$Dom='and ifnull(law.dom,0)=1';

	$query = "select lawID from lawviewed where ClerkIDsee='$login_userid'";
	 	
	$result = mysql_query($query);
    $r2c=0;
    $lawIDviewed_array=array();
	while( $row2 = mysql_fetch_assoc( $result))
    {
        $lawIDviewed_array[$r2c++] = $row2['lawID']; // Inside while loop
    }
    
    $query = "select law.lawID lawIDnotviewed,law.lawtype from law 
				inner join clerk on clerk.clerkid='$login_userid'
				inner join lawrole on lawrole.lawID=law.lawID and (lawrole.RolesID=clerk.city or lawrole.ClerkIDR='$login_userid')
				where $login_where $Dom   ";
	 	
	$result = mysql_query($query);
	while( $row2 = mysql_fetch_assoc($result))
    {
        if (! in_array($row2['lawIDnotviewed'], $lawIDviewed_array))  
        {
            $lawtype= $row2['lawtype'];
            $login_lawIDnotviewed= $row2['lawIDnotviewed'];
            break;
        }    
    }
	
 	//print $query;
     //exit;
	
    
    
    if($login_lawIDnotviewed>0 ) 
    {
        //print "Location: $_server_httptype://$_SERVER[HTTP_HOST]/invoiceirrigating/lawsubmit.php?uid=".$login_ostanId.rand(10000,99999).rand(10000,99999)
        //.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$login_userid.rand(10000,99999);
        //exit;
		if ($lawtype==2)
       header("Location: $_server_httptype://$_SERVER[HTTP_HOST]/invoiceirrigating/lawsubmitnazar.php?uid=".$login_ostanId.rand(10000,99999).rand(10000,99999)
        .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$login_userid."_".$Disable."_".$login_lawIDnotviewed.rand(10000,99999));
 		
		else
        header("Location: $_server_httptype://$_SERVER[HTTP_HOST]/invoiceirrigating/lawsubmit.php?uid=".$login_ostanId.rand(10000,99999).rand(10000,99999)
        .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$login_userid."_".$Disable."_".$login_lawIDnotviewed.rand(10000,99999));
        
    }
        
  
    
    $blacklist=0;
    $notpermitrolsid = array("0");
    $notsaveddesigner = '-1';
    //if (in_array($login_DesignerCoID, $notpermitrolsid)) $blacklist=1;

    
    $enrolmsg="";
    $_SESSION['login_RolesID'] = $login_RolesID;
    $_SESSION['login_ProducersID'] = $login_ProducersID;
    $_SESSION['login_CityId'] = $login_CityId;
    $_SESSION['login_OperatorCoID'] = $login_OperatorCoID;
    $_SESSION['login_DesignerCoID'] = $login_DesignerCoID; 
    $_SESSION['ClerkID'] =$login_userid;
	//echo $login_userid;
	
	//print_r($_SESSION);exit;
    //print $login_CityId;	

if ($login_DesignerCoID>0 || $login_OperatorCoID>0)
	{	
	
     		   if ($login_DesignerCoID>0)
				{
					$tbl="designerco";
					$tid="$login_DesignerCoID";
				}
				else if ($login_OperatorCoID>0)
				{
					$tbl="operatorco";
					$tid="$login_OperatorCoID";
				}   
				$query="select $tbl"."ID as _value,Title,CntError from $tbl  where $tbl"."ID='$tid'";
				$result = mysql_query($query);
				 
				$resquery = mysql_fetch_assoc($result);
				$cotitle = $resquery["Title"];
	            
				
//	print "salam0 $login_opDisabled $_SERVER[PHP_SELF]";
                    
			if ($login_opDisabled==8 && ((strlen(strstr($_SERVER['PHP_SELF'],'home.php'))>0) || 
            (strlen(strstr($_SERVER['PHP_SELF'],'apprequest.php'))>0)
            ||
            (strlen(strstr($_SERVER['PHP_SELF'],'applicant_list.php'))>0)
            ||
            (strlen(strstr($_SERVER['PHP_SELF'],'invoice_list.php'))>0) ) )
				{
					//print "salam1";
                    
					if ($CntError)	
					print "<script> alert(\"اطلاعیه $CntError : $enrolmsgt\"); </script>";
					if ($CntError>4 && $CntError<9)	
					echo "<script type=\"text/javascript\">	window.open('http://$_SERVER[HTTP_HOST]/invoiceirrigating/members_operatorpay.php', '_blank')	</script>";
					else if ($CntError>=9)
					echo "<script type=\"text/javascript\">	window.open('http://$_SERVER[HTTP_HOST]/invoiceirrigating/members_operatorpay.php', '_self')	</script>";
					
			//		header("Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/members_operatorpay.php");
		

					$query="update $tbl set CntError=$CntError, 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'  where $tbl"."ID='$tid'";
					$result = mysql_query($query);
				
				}
	
	
	}
    
	
if ($Disable==2 && $issave==1 )	$login_Permission_granted=0;
if ((strtoupper($_SERVER[SERVER_NAME])=='TOOSRAHAM.IR' || strtoupper($_SERVER[SERVER_NAME])=='WWW.TOOSRAHAM.IR') 
	&& $login_RolesID==2 
    )
    {
        if ($Disable!=3)
            {
                //$con = mysql_connect($_server, $_server_user, $_server_pass);
                //if (!$con) die('Could not connect: ' . mysql_error());
               // mysql_select_db("asakhnet_dead", $con);
                
            }
        $vr=rand ( 4,6);
        if ($vr==5)
	   $login_Permission_granted=0;
        
    }
	
    //////////////////////////////
    if ($login_DesignerCoID>0 && $login_RolesID==9)
    {

    
    
    
    $designererror="";
    
    if (!($resquerycopermisionvalidated>$dateYmd)) 
    {
	   $designererror.="<br>*انقضاء تاريخ مجوز دفتر بهبود $resquerycopermisionvalidated.";
    }
    if (!($resqueryboardvalidationdated>$dateYmd))
        $designererror.="<br>*انقضاء تاريخ آگهي تغييرات شركت $resqueryboardvalidationdated.";
    
    
    if (!(strlen(strstr($_SERVER['REQUEST_URI'],'approvedocumentcompany.php'))>0) && 
    !(strlen(strstr($_SERVER['REQUEST_URI'],'approvedocumentcompany1.php'))>0) && 
    !(strlen(strstr($_SERVER['REQUEST_URI'],'home.php'))>0)  && 
    !(strlen(strstr($_SERVER['REQUEST_URI'],'login.php'))>0)  && 
    !(strlen(strstr($_SERVER['REQUEST_URI'],'ticket.php'))>0) && 
    !(strlen(strstr($_SERVER['REQUEST_URI'],'ticket_detail.php'))>0) )
    
    {
        //print $login_Permission_granted.$dateYmd.$_SERVER['REQUEST_URI'];
        //header("Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/insert/approvedocumentcompany.php");
        //exit;
        //print $_SERVER['REQUEST_URI']."<br>";print "Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/insert/approvedocumentcompany.php";exit;
        
        if (strlen($designererror)>0 )
        {
            //echo "$designererror $resquerycopermisionvalidated $resqueryboardvalidationdated $dateYmd";exit;
            header("Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/insert/approvedocumentcompany.php");
        }
        else if (strlen($row["CompanyAddressd"])<=0 || strlen($row["AccountNod"])<=0 || strlen($row["BossNamed"])<=0 || strlen($row["Phoned"])<=0 ) 
        
                header("Location: http://$_SERVER[HTTP_HOST]/invoiceirrigating/insert/approvedocumentcompany1.php");
                
    
    }
    ////////////////////////////////////    
    }
    

		
}		
            
?>