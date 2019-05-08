<?php 
/*
pricesaving/pricesaving1masterlist_refs.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/compareprices.php
*/
include('../includes/connect.php');  ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");



$per_page = 25;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
$currpage=$page;
                            
$g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;//شناسه جدول سطح دوم ابزار
$pid=is_numeric($_GET["pid"]) ? intval($_GET["pid"]) : 0;//شناسه تولیدکننده
$tblname='pricelistdetail';
$othertblname='primarypricelistdetail';//لیت قیمت اولیه تولیدکنندگان

if ($login_ProducersID>0)
{
    $pid=$login_ProducersID;
    $tblname='primarypricelistdetail';
    $othertblname='pricelistdetail';//لیست قیمت تایید شده
}



else $Approved=0;

$mid=is_numeric($_GET["mid"]) ? intval($_GET["mid"]) : 0;//مارک
$IDorderval=is_numeric($_GET["IDorder"]) ? intval($_GET["IDorder"]) : 3;//ترتیب


$showzero=is_numeric($_GET["showzero"]) ? intval($_GET["showzero"]) : 0;//نمایش مبالغ صفر
$shownzero=is_numeric($_GET["shownzero"]) ? intval($_GET["shownzero"]) : 0;//نمایش مبالغ غیرصفر
$shownzeroold=is_numeric($_GET["shownzeroold"]) ? intval($_GET["shownzeroold"]) : 0;//نمایش مبالغ غیرصفر تولیدکننده
$showzero2=is_numeric($_GET["showzero2"]) ? intval($_GET["showzero2"]) : 0;//نمایش مبالغ صفر

$shownzero2=is_numeric($_GET["shownzero2"]) ? intval($_GET["shownzero2"]) : 0;//نمایش مبالغ صفر تولیدکننده
$showm=is_numeric($_GET["showm"]) ? intval($_GET["showm"]) : 0;//نمایش فقط مراجع
$shownp=is_numeric($_GET["shownp"]) ? intval($_GET["shownp"]) : 0;//فروشنده حاوی قیمت
$shownapr=is_numeric($_GET["shownapr"]) ? intval($_GET["shownapr"]) : 0;//نمایش قیمت های تایید نشده
$showqm=is_numeric($_GET["showqm"]) ? intval($_GET["showqm"]) : 0;//نمایش ع س


                
                
if ($_POST)
    { 
	
       // print 'sa'.$g2id;

        if (($_POST['pid']>0) && ($login_moneyapprovepermit==1))
        {
            $_POST['showapproved']=$_POST['showapproved'];
            if ($_POST['showapproved']=='on') $val=1; else $val=0;
                /*
               primarypricelistdetail جدول قیمت های تایید نشده
               Approved تایید شده
               PriceListMasterID شناسه لیست قیمت
               toolsmarksid شناسه ابزار و مارک
               toolsmarks جدول ابزار و مارک
               producersid شناسه تولید کننده
               */
       
                $sql = "update primarypricelistdetail set Approved='$val' where PriceListMasterID='$_POST[PriceListMasterID]' and ToolsMarksID in ( select ToolsMarksID from toolsmarks where ProducersID='$_POST[pid]' ) ";
          
          
            	  	 	try 
								  {		
									  	  mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        }
        if ($_POST['pid']>0)
        {
         
           $sql = "
            SELECT max(Approved) Approved
            FROM primarypricelistdetail where PriceListMasterID='$_POST[PriceListMasterID]' and ToolsMarksID in ( select ToolsMarksID from toolsmarks where ProducersID='$_POST[pid]' ) 
            ";
           
			 	  	 	try 
								  {		
									  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

            $row = mysql_fetch_assoc($result);
            $Approved = $row['Approved'];
        }
        
        $PriceListMasterID = $_POST['PriceListMasterID'];
        $i=0;
          while (isset($_POST['toolsmarksID'.++$i]))
        {
            $ProducersID = $_POST['ProducersID'];
            $toolsmarksID = $_POST['toolsmarksID'.$i];
            $currpage=$_POST['currpage'];
            $currg2id=$_POST['g2id'];
            if ($login_ProducersID>0)
            {
                $currpid=$login_ProducersID;
            }
            else 
                $currpid=$_POST['pid'];
            $currmid=$_POST['mid'];
            $showzero=$_POST['showzero'];
            $shownzero=$_POST['shownzero'];
            $shownzeroold=$_POST['shownzeroold'];
            $showzero2=$_POST['showzero2'];
            $shownzero2=$_POST['shownzero2'];
            $showm=$_POST['showm'];
            $shownp=$_POST['shownp'];
            $shownapr=$_POST['shownapr'];
            $showqm=$_POST['showqm'];
            
            

               
            if (!($login_ProducersID>0) && ($_POST['PriceListDetailID'.$i]>0))
            {
                $_POST['hide'.$i] = $_POST['hide'.$i];                   
                mysql_query("update pricelistdetail set hide='".$_POST['hide'.$i]."' where PriceListDetailID='".$_POST['PriceListDetailID'.$i]."'") ;             
            }
            if ($login_RolesID==1)//مدیر پیگیری
            {
                //ثبت منقضی شدن قیمت کالا
                //MarksID=373 مارک منقضی
                if ($_POST['expire'.$i]=='on') 
                {
                    /*
                   toolsmarks جدول ابزار و مارک
                   gadget3id شناسه جدول سطح سوم ابزار
                   producersid شناسه تولید کننده
                   MarksID شناسه مارک
                   hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
                   */
       
                    $query = "
                      INSERT INTO toolsmarks (gadget3ID,ProducersID,MarksID,hide,SaveDate,SaveTime,ClerkID) 
                      select toolsmarks.gadget3ID,toolsmarks.ProducersID,toolsmarks.MarksID,0,'".date('Y-m-d H:i:s')."','".date('Y-m-d')."','".$login_userid."' 
                      from toolsmarks
                      where toolsmarksID='$toolsmarksID';";
                     
                    	 	 	try 
								  {		
									  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
                    mysql_query("update toolsmarks set MarksID=373,hide=1 where toolsmarksID='$toolsmarksID';");
                }
            }
            
        
            $register = true;
            
            $Price = str_replace(',', '', $_POST['Price'.$i]);
            
            
            $oldPrice = str_replace(',', '', $_POST['oldPrice'.$i]);
                
            if ($Price<>$oldPrice)	
        	{
        	    //print $Price."salam".$oldPrice.$i;
				//exit;
			   $sql = "SELECT ".$tblname."ID ID FROM ".$tblname." where 
                   ".$tblname.".toolsmarksID ='$toolsmarksID'  and ".$tblname.".PriceListMasterID ='$PriceListMasterID'";
                  
				    	 	 	try 
								  {		
									  	  $resultwhile = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
                    $row = mysql_fetch_assoc($resultwhile);
                    $tblID=$row['ID'];
               
                if ($tblID != 0)//update
                {
                    /*
                   price مبلغ
                   invoicedetail ریز پیش فاکتورها
                   toolsmarksid شناسه ابزار و مارک
                   invoicemaster عناوین پیش فاکتورها
                   InvoiceMasterID شناسه پیش فاکتور
                   PriceListMasterID شناسه لیست قیمت
                   toolspref جدول مرجع قیمتی
                   */
                   
            		$query = "
            		UPDATE ".$tblname." SET
            		Price = '" . $Price. "',  
            		SaveTime = '" . date('Y-m-d H:i:s') . "', 
            		SaveDate = '" . date('Y-m-d') . "', 
            		ClerkID = '" . $login_userid . "'
            		WHERE ".$tblname."ID = " . $tblID . " and  not exists 
                    (
                    select invoicedetail.toolsmarksID from invoicedetail 
                    inner join invoicemaster on invoicemaster.InvoiceMasterID=invoicedetail.InvoiceMasterID
                    and invoicemaster.PriceListMasterID ='$PriceListMasterID' and  invoicedetail.toolsmarksID='$toolsmarksID'
                    union all
                    select toolspref.ToolsMarksIDpriceref from invoicedetail 
                    inner join toolspref on toolspref.toolsmarksID=invoicedetail.toolsmarksID
                    inner join invoicemaster on invoicemaster.InvoiceMasterID=invoicedetail.InvoiceMasterID
                    and invoicemaster.PriceListMasterID ='$PriceListMasterID' and  toolspref.ToolsMarksIDpriceref='$toolsmarksID'
                    
                    ) ;";
                    
               
					 	 	try 
								  {		
									  	     $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
                    //print $query;exit;
            	   }
                else //insert
                {
                    if ($Price>0 && $PriceListMasterID>0 && $login_userid>0)
                    {
          			$query = "
                      INSERT INTO ".$tblname."(PriceListMasterID,toolsmarksID,Price,SaveTime,SaveDate,ClerkID) 
                      VALUES('" .
                      $PriceListMasterID . "', '" . 
                      $toolsmarksID . "', '" . 
                      $Price . "', '" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
                      
					  		 	try 
								  {		
									  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
    			     //header("Location: clerk.php");
    			     }
                    
                }
            }
            $spec="";
            if ($_POST['val1'.$i]<>"" || $_POST['val2'.$i]<>"" || $_POST['val3'.$i]<>"" || $_POST['val4'.$i]<>"" || $_POST['val5'.$i]<>"" 
				|| $_POST['val6'.$i]<>"" || $_POST['val7'.$i]<>"" || $_POST['val8'.$i]<>"" || $_POST['val9'.$i]<>"")
            $spec=$_POST['val1'.$i]."_".$_POST['val2'.$i]."_".$_POST['val3'.$i]."_".$_POST['val4'.$i]."_".$_POST['val5'.$i]."_".$_POST['val6'.$i]
			."_".$_POST['val7'.$i]."_".$_POST['val8'.$i]."_".$_POST['val9'.$i]; 
            if (($_POST['newPCode'.$i]!=$_POST['PCode'.$i])||($spec!=$_POST['spec'.$i]))
            {
                
                $PCode=$_POST['newPCode'.$i];
                if($_POST['toolsmarksspecID'.$i]>0)
                    $query = " UPDATE toolsmarksspec SET PCode='$PCode',spec='$spec'
                    ,SaveDate='" .date('Y-m-d'). "',SaveTime='".date('Y-m-d H:i:s')."',ClerkID='".$login_userid."' where toolsmarksID ='$toolsmarksID';";
                else
                    $query = " insert into toolsmarksspec (toolsmarksID,PCode,spec,SaveDate,SaveTime,ClerkID) 
                    VALUES ('$toolsmarksID','$PCode','$spec','" .date('Y-m-d'). "','".date('Y-m-d H:i:s')."','".$login_userid."');";
                    
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
         //exit(0);
        
        
        $query = " delete from  pricelistdetail where Price='0';";
        		  		 	try 
								  {		
									  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
        $query = " delete from  primarypricelistdetail where Price='0';";
        		  		 	try 
								  {		
									  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
        
        header("Location: $_server_httptype://$_SERVER[HTTP_HOST]/$_POST[ru]");        
     }
        
        
if (! $_POST)
{
    
    $PriceListMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $uid=$_GET["uid"];
    $linearray = explode('_',$PriceListMasterID);
    $PriceListMasterID=$linearray[0];    
    $pl='';
    if ($pid>0)
    {
     
       $sql = "
        SELECT max(Approved) Approved
        FROM primarypricelistdetail where PriceListMasterID='$PriceListMasterID' and ToolsMarksID in ( select ToolsMarksID from toolsmarks where ProducersID='$pid' ) 
        ";
        
				  		 	try 
								  {		
									  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
        $row = mysql_fetch_assoc($result);
        $Approved = $row['Approved'];
        
        $sql = "
        select max(view1.PriceListMasterID) PriceListMasterID,concat(month.Title,' ',year.Value) pl from (
        SELECT max(primarypricelistdetail.PriceListMasterID) PriceListMasterID
        FROM primarypricelistdetail 
        inner join toolsmarks on toolsmarks.toolsmarksid=primarypricelistdetail.toolsmarksid
        where  ProducersID='$pid' 
        union all
        SELECT max(pricelistdetail.PriceListMasterID) PriceListMasterID
        FROM pricelistdetail 
        inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid
        where  ProducersID='$pid') view1
        inner join pricelistmaster on pricelistmaster.PriceListMasterID=view1.PriceListMasterID
        inner join month on month.MonthID=pricelistmaster.MonthID
        inner join year on year.YearID=pricelistmaster.YearID";
        //print $sql;
        
								try 
								  {		
									  	$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
  
        $row = mysql_fetch_assoc($result);
        $pl = $row['pl'];
        
    }

    $sql = "
    SELECT pricelistmaster.*,month.Title monthtitle,year.Value year 
    FROM pricelistmaster 
    inner join month on month.MonthID=pricelistmaster.MonthID
    inner join year on year.YearID=pricelistmaster.YearID
    where PriceListMasterID='$PriceListMasterID' ;";


    //print $sql;
   
								try 
								  {		
									  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    if ($result)
    $row = mysql_fetch_assoc($result);
    
    $year = $row['year'];
    $monthtitle = $row['monthtitle'];
    
    $theader="ثبت لیست قیمت - $monthtitle $year  ";                        

    

    $Page1=0;
    if (!($ProducersID>0))
        $Page1=1;
    
    
 
 
    if (($g2id>0)|| ($pid>0) || ($mid>0) )
    {
        
        $cond=" and toolsmarks.marksid<>128";
        if ($g2id>0) $cond.=" and gadget2.gadget2id='$g2id' ";
        if ($pid>0) $cond.=" and toolsmarks.producersID='$pid' ";
        if ($mid>0) $cond.=" and toolsmarks.marksid='$mid' ";
        if ($showzero>0 && !($shownzero>0)) $cond.=" and  ifnull(".$tblname.".Price,0)=0 ";
        if ($shownzeroold>0) $cond.=" and  ifnull(pricelistdetailprevious.Price,0)>0 ";
        if ($showzero2>0 && !($shownzero2>0)) $cond.=" and  ifnull(".$othertblname.".Price,0)=0 ";
        if ($showm>0 ) $cond.=" and  case when ifnull(toolsprefref.ToolsMarksIDpriceref,0)=0 then '' else 'm' end='m' ";
        if ($showqm>0 ) $cond.=" and  ifnull(primarypricelistdetail.Approved,0)=0 ";
        
        if ($login_RolesID==2) $cond.=" and case gadget1.gadget1id when 68 then 1 else  ifnull(pricelistdetail.Price,0)<>0 end";
        
        
        if ($shownzero>0 && !($showzero>0)) $cond.=" and  ifnull(".$tblname.".Price,0)<>0 ";
        if ($shownzero2>0 && !($showzero2>0)) $cond.=" and  ifnull(".$othertblname.".Price,0)<>0 ";
        if ($shownapr>0 ) $cond.=" and  ifnull(".$tblname.".Price,0)<>ifnull(".$othertblname.".Price,0) ";
        
        
            $prevquery="left outer join 
            (SELECT ToolsMarksID,max(PriceListMasterID) PriceListMasterID FROM `pricelistdetail` 
            where price>0 and PriceListMasterID<'$PriceListMasterID'
            group by ToolsMarksID)
            pricelistdetaillast on toolsmarks.toolsmarksid=pricelistdetaillast.toolsmarksid
            left outer join pricelistdetail pricelistdetailprevious on pricelistdetaillast.ToolsMarksID=pricelistdetailprevious.ToolsMarksID and pricelistdetailprevious.PriceListMasterID=pricelistdetaillast.PriceListMasterID
            ";
    
    	switch ($IDorderval) 
  {
    case 1: $orderby=' order by Price'; break; 
    case 2: $orderby=' order by PCode '; break;
    case 3: $orderby=' order by FullTitle'; break; 
    default: $orderby=' order by FullTitle '; break; 
  }        
        /*
       producers جدول تولیدکننده
       producersid شناسه تولید کننده
       producers.Title عنوان تولید کننده
       pricelistdetail جدول قیمت های تایید شده
       marks جدول مارک ها
       toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
            ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
            gadget3ID شناسه سطح 3 ابزار
            ProducersID شناسه جدول تولیدکننده
            MarksID شناسه جدول مارک
       toolsmarksid شناسه ابزار و مارک
       gadget3 جدول سطح سوم ابزار
       gadget2 جدول سطح دوم ابزار
       gadget1 جدول سطح اول ابزار
       gadget3id شناسه جدول سطح سوم ابزار
       gadget2id شناسه جدول سطح دوم ابزار
       hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
       PriceListMasterID شناسه لیست قیمت
       price مبلغ
       units جدول واحدهای اندازه گیری کالا
       sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
       operator جدول عملگر های تشکیل دهنده نام کالا
       spec2 مشخصه 2 کالا ها
       spec3 مشخصه 3 کالا ها
       materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
       */        
       if ($login_RolesID==2)//مجری ها کدهای کالای لوازم پلی اتیلن را ببینند
        $gadget1filter="";
       else
        $gadget1filter="and gadget1.gadget1id<>68";
       
       
        $sql="SELECT producers.title producerstitle,marks.title markstitle,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) Gadget12Title,gadget2.gadget2id, 
            gadget3.title Gadget3Title, units.title UnitsTitle, ".$tblname.".Price,pricelistdetail.PriceListDetailID,case pricelistdetail.hide when 1 then '1' else '0' end hidestate,primarypricelistdetail.Approved
            , toolsmarksspec.PCode,toolsmarksspec.toolsmarksspecID,toolsmarksspec.spec,toolsmarks.marksid, toolsmarks.ProducersID, toolsmarks.Gadget3ID,".$tblname.".".$tblname."ID,
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle
            ,toolsmarks.toolsmarksID, ".$othertblname.".Price otherPrice,pricelistdetailprevious.Price previousPrice,case when ifnull(toolsprefref.ToolsMarksIDpriceref,0)=0 then '' else 'm' end isrefrence
            FROM toolsmarks
            left outer join toolsmarksspec on toolsmarksspec.toolsmarksID=toolsmarks.toolsmarksID
            inner join marks on marks.marksid=toolsmarks.marksid
            inner join producers on producers.producersID=toolsmarks.producersID
            inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id  and ifnull(gadget3.IsHide,0)=0
            inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id $gadget1filter 
            left outer join units on units.Unitsid=gadget3.Unitsid
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            left outer join ".$tblname." on  ".$tblname.".PriceListMasterID='$PriceListMasterID' 
            and ".$tblname.".ToolsMarksID=toolsmarks.toolsmarksID 
      
            left outer join ".$othertblname." on  ".$othertblname.".PriceListMasterID='$PriceListMasterID' 
            and ".$othertblname.".ToolsMarksID=toolsmarks.toolsmarksID 
            
            left outer join (select distinct ToolsMarksIDpriceref,PriceListMasterID from toolspref where PriceListMasterID='$PriceListMasterID') toolsprefref 
            on toolsprefref.ToolsMarksIDpriceref=toolsmarks.toolsmarksID
            
            
             $prevquery
            
            
            
            where ifnull(toolsmarks.hide,0)=0 and gadget3.gadget3id not in (select gadget3ID from gadget3synthetic) 
            and toolsmarks.toolsmarksID not in (select toolsmarksID from toolspref where PriceListMasterID='$PriceListMasterID' 
            and toolsmarksID<>ToolsMarksIDpriceref)  and producers.ProducersID<>142 $cond 
            $orderby
        ";
        //print $sql;
        
							try 
								  {		
									  	 $result = mysql_query($sql.$login_limited);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $count=0;
        $dasrow=0;
        $allg2id[' ']=' ';
        $allpid[' ']=' ';
        $allmid[' ']=' ';
        
        while($row = mysql_fetch_assoc($result))
        {
            $dasrow=1;
            $count++;
            $allg2id[trim($row['Gadget12Title'])]=trim($row['gadget2id']);
            $allpid[trim($row['producerstitle'])]=trim($row['ProducersID']);
            $allmid[trim($row['markstitle'])]=trim($row['marksid']);
        }
        $pages = ceil($count / $per_page);
        if ($dasrow) mysql_data_seek( $result, 0 );    
        $allg2id=mykeyvalsort($allg2id);
        $allpid=mykeyvalsort($allpid);
        $allmid=mykeyvalsort($allmid);

	
        
        //print $sql;
    }
    if ((!($g2id>0)&& !($pid>0) && !($mid>0) )||(!($result))||(!(array_sum($allmid)))||(!(array_sum($allpid)))||(!(array_sum($allg2id))))
    {
         $sqlselect="select CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) _key,gadget2.gadget2id _value from gadget2
            inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id $gadget1filter 
            
            order by _key  COLLATE utf8_persian_ci";
        $allg2id = get_key_value_from_query_into_array($sqlselect);
    
     
     
          if ($shownapr>0 ) 
        $sqlselect="select distinct producers.title _key,producers.ProducersID _value from producers
                    inner join toolsmarks on toolsmarks.ProducersID=producers.ProducersID
                    inner join primarypricelistdetail on primarypricelistdetail.toolsmarksid=toolsmarks.toolsmarksid 
                    and primarypricelistdetail.PriceListMasterID='$PriceListMasterID'
                    left outer join pricelistdetail on pricelistdetail.toolsmarksid=toolsmarks.toolsmarksid and 
                    pricelistdetail.PriceListMasterID='$PriceListMasterID'
                    where ifnull(primarypricelistdetail.Price,0)<>ifnull(pricelistdetail.Price,0)
                    order by  _key COLLATE utf8_persian_ci";
        
        else  if ($shownp>0 ) 
        $sqlselect="select distinct producers.title _key,producers.ProducersID _value from producers
                    inner join toolsmarks on toolsmarks.ProducersID=producers.ProducersID
                    inner join primarypricelistdetail on primarypricelistdetail.toolsmarksid=toolsmarks.toolsmarksid and primarypricelistdetail.PriceListMasterID='$PriceListMasterID'
                    where producers.ProducersID<>142 and ifnull(primarypricelistdetail.Price,0)<>0
                    order by  _key COLLATE utf8_persian_ci";
        
        else $sqlselect="select  producers.title _key,producers.ProducersID _value from producers
                    where ProducersID<>142
                    order by  _key COLLATE utf8_persian_ci";
        $allpid = get_key_value_from_query_into_array($sqlselect);
    
        $sqlselect="select  marks.title _key,marks.marksid _value from marks where marksid<>128
                    order by _key  COLLATE utf8_persian_ci";
        $allmid = get_key_value_from_query_into_array($sqlselect);
     
    }
    
}


$query="
select 'قیمت' _key,1 as _value union all
select 'کد' _key,2 as _value union all 
select 'عنوان کالا' _key,3 as _value  ";

$IDorder = get_key_value_from_query_into_array($query);





?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت لیست قیمت</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
<script type="text/javascript">
var txt1 = "Este é o texto dotooltip";

function TooltipTxt(n)
{
return "Este é o texto do " + n + " tooltip";
}
function showdiv(id)
{
    var str = document.getElementById('spec'+id ).value;
    var res = str.split("_");
    if (res[0])
        document.getElementById('val1'+id ).value=res[0];
    if (res[1])
        document.getElementById('val2'+id ).value=res[1];
    if (res[2])
        document.getElementById('val3'+id ).value=res[2];
    if (res[3])
        document.getElementById('val4'+id ).value=res[3];
    if (res[4])
        document.getElementById('val5'+id ).value=res[4];
    if (res[5])
        document.getElementById('val6'+id ).value=res[5];
    if (res[6])
        document.getElementById('val7'+id ).value=res[6];
    if (res[7])
        document.getElementById('val8'+id ).value=res[7];
    if (res[8])
        document.getElementById('val9'+id ).value=res[8];
    
    //alert('ss');
    var elem = document.getElementById(id + '_content');
    if(elem.style.display=='none')
    {
        elem.style.display='';
    }
    else
    {
        elem.style.display='none';
    }
}

</script> 

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    function DeleteAll(url)
    {
	   var vshowzero=0;
       var vshownzero=0;
       var vshownzeroold=0;
	   var vshowzero2=0;
       var vshownzero2=0;
	   var vshowm=0;
	   var vshowqm=0;
	   var vshownp=0;
       var vshownapr=0;
       
       
       
	   if (document.getElementById('showzero').checked) vshowzero=1;
       if (document.getElementById('shownzero').checked) vshownzero=1;
       if (document.getElementById('shownzeroold').checked) vshownzeroold=1;
	   if (document.getElementById('showzero2').checked) vshowzero2=1;
       if (document.getElementById('shownzero2').checked) vshownzero2=1;
	   if (document.getElementById('showm').checked) vshowm=1;
	   if (document.getElementById('showqm').checked) vshowqm=1;
	   if (document.getElementById('shownp').checked) vshownp=1;
	   if (document.getElementById('shownapr').checked) vshownapr=1;
       
        var codes;
        if ($('#pagination').length > 0)
        
           codes='-'+document.getElementById('pagination').value
        + '-' + document.getElementById('g2id').value
        + '-' + document.getElementById('pid').value
        + '-' + document.getElementById('mid').value
        + '-' + document.getElementById('IDorder').value
        
        + '-' + vshowzero
        + '-' + vshownzero
        + '-' + vshownzeroold
        + '-' + vshowzero2
        + '-' + vshownzero2
        + '-' + vshowm
        + '-' + vshowqm
        + '-' + vshownp
        + '-' + vshownapr;
        else
           codes='-1'+ '-' + document.getElementById('g2id').value
        + '-' + document.getElementById('pid').value
        + '-' + document.getElementById('mid').value
        + '-' + document.getElementById('IDorder').value
        
        + '-' + vshowzero
        + '-' + vshownzero
        + '-' + vshownzeroold
        + '-' + vshowzero2
        + '-' + vshownzero2
        + '-' + vshowm
        + '-' + vshowqm
        + '-' + vshownp
        + '-' + vshownapr;
        


         if (! confirm('مطمئن هستید که حذف شود ؟')) return;
        //alert(document.getElementById('records').rows.length);
        var stid='0';
		
        		
        for (var j=1;j<=(document.getElementById('records').rows.length-4);j++)
			if (document.getElementById('cb'+j).checked)
			stid = stid + ',' + document.getElementById('cb'+j).name.substr(3);
		
	//	alert(stid+','+document.getElementById('cb'+j).name.substr(3));
		
		
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="http://"+stid.substring(7).replace("//","/");
        //alert(stid2);
        location.href=stid2;
        }
        
    }
    
     function SelectAll()
                {
                    if ($("input[id^='cb']:checked").length == $("input[id^='cb']").length)
                    $("input[id^='cb']").prop('checked', false);
                    else
                    $("input[id^='cb']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
    function checkval(id)
                {
				if (!document.getElementById('val1'+id).value)
					{alert('لطفا مشخصات فنی کالا را تکمیل نمایید!');return false;}

				}
     
	
	
    </script>

<script language='javascript' src='../assets/jquery.js'></script>
    <!-- /scripts -->
</head>
<body >

    <script type="text/javascript" src="../assets/wz_tooltip.js"></script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php 
            

				if ($_POST){
					if ($register){
						$Serial = "";
                        
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}
include('../includes/top.php'); 
?>
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
			<div id="content" >
            <form action="pricesaving1masterlist_refs.php" method="post" >
                <table width="95%" align="center">
                    <tbody>
                    
                    
                        <tr>
                            <td>
                                                   

<?php   print "<script type='text/javascript'> 


	function selectpage(){
	   var vshowzero=0;
       var vshownzero=0;
       var vshownzeroold=0;
	   var vshowzero2=0;
       var vshownzero2=0;
	   var vshowm=0;
	   var vshowqm=0;
	   var vshownp=0;
	   var vshownapr=0;
	   if (document.getElementById('showzero').checked) vshowzero=1;
       if (document.getElementById('shownzero').checked) vshownzero=1;
       if (document.getElementById('shownzeroold').checked) vshownzeroold=1;
	   if (document.getElementById('showzero2').checked) vshowzero2=1;
       if (document.getElementById('shownzero2').checked) vshownzero2=1;
	   if (document.getElementById('showm').checked) vshowm=1;
	   if (document.getElementById('showqm').checked) vshowqm=1;
	   if (document.getElementById('shownp').checked) vshownp=1;
	   if (document.getElementById('shownapr').checked) vshownapr=1;
       
	   if ($('#pagination').length > 0)
        window.location.href ='?uid=' +document.getElementById('uid').value+ '&page=' + document.getElementById('pagination').value
        + '&g2id=' + document.getElementById('g2id').value
        + '&pid=' + document.getElementById('pid').value
        + '&mid=' + document.getElementById('mid').value
        + '&IDorder=' + document.getElementById('IDorder').value
        + '&showzero=' + vshowzero
        + '&shownzero=' + vshownzero
        + '&shownzeroold=' + vshownzeroold
        + '&showzero2=' + vshowzero2
        + '&shownzero2=' + vshownzero2
        + '&showm=' + vshowm
        + '&showqm=' + vshowqm
        + '&shownp=' + vshownp
        + '&shownapr=' + vshownapr;
        else 
        window.location.href ='?uid=' +document.getElementById('uid').value+ '&g2id=' + document.getElementById('g2id').value
        + '&pid=' + document.getElementById('pid').value
        + '&mid=' + document.getElementById('mid').value
        + '&IDorder=' + document.getElementById('IDorder').value
        + '&showzero=' + vshowzero
        + '&shownzero=' + vshownzero
        + '&shownzeroold=' + vshownzeroold
        + '&showzero2=' + vshowzero2
        + '&shownzero2=' + vshownzero2
        + '&showm=' + vshowm
        + '&showqm=' + vshowqm
        + '&shownp=' + vshownp
        + '&shownapr=' + vshownapr;
        
	}


function p_tarkib(_value)
{
 var _len;var _inc;var _str;var _char;var _oldchar;_len=_value.length;_str='';
 for(_inc=0;_inc<_len;_inc++)
 {
   _char=_value.charAt(_inc);
   if (_char=='1' || _char=='2' || _char=='3' || _char=='4' || _char=='5' || _char=='6' || _char=='7' || _char=='8' || _char=='9' || _char=='0' || _char=='-') 
      _str=_str+_char;
   else
      if (_char!=',') return 'error';
 }
 return _str;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    
    function transfer(cnt)
    {
       
             
        if (cnt==-1)
        {
             for (var j=1;j<=(document.getElementById('records').rows.length-3);j++)
             {
                
                 if (document.getElementById('coefadmin'))
                document.getElementById('Price'+j).value=numberWithCommas(p_tarkib(document.getElementById('otherPrice'+j).value)*1-Math.round(document.getElementById('coefadmin').value*p_tarkib(document.getElementById('otherPrice'+j).value)/100));
           
                if ((document.getElementById('txtProducersID').value==0) && (document.getElementById('Approved'+j).value==1))
                    document.getElementById('Price'+j).value=document.getElementById('otherPrice'+j).value;
               
               if (document.getElementById('coef'))
                    document.getElementById('Price'+j).value=numberWithCommas(p_tarkib(document.getElementById('previousPrice'+j).value)*1+Math.round(document.getElementById('coef').value*p_tarkib(document.getElementById('previousPrice'+j).value)/100));
             
               
             }
             
        }
        else if ((document.getElementById('txtProducersID').value==0) && (document.getElementById('Approved'+cnt).value==1))
        {
            if (document.getElementById('coefadmin'))
            document.getElementById('Price'+cnt).value=numberWithCommas(p_tarkib(document.getElementById('otherPrice'+cnt).value)*1-Math.round(document.getElementById('coefadmin').value*p_tarkib(document.getElementById('otherPrice'+cnt).value)/100));
           
            else
            document.getElementById('Price'+cnt).value=document.getElementById('otherPrice'+cnt).value;
        }   
        
    }
    
</script>
"; 
 ?>

          <td colspan="11" class="label">
          <h2  align="center" style = "border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:150%;
		  font-family:'B Nazanin';"><?php echo $theader ;?> </h2></td>
                <tr>
                          <INPUT type="hidden" id="txtProducersID" value="<?php print $login_ProducersID; ?>"/>
                            <div style = "text-align:left;"><a  href=<?php 
                           print "pricesaving1masterlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ProducersID.rand(10000,99999).
                            "><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";?>
                            
                            
                            <td align="left"><?php
							
                            //print $g2id;
                            if ($pages > 1){
								echo 'صفحه<select name="pagination" id="pagination" onChange="selectpage();">';
								
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($currpage == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
								echo 'از  '.$pages;
							}
							
                             print select_option('g2id','گروه کالا',',',$allg2id,0,'','','4','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213').
                            select_option('pid','تولیدکننده',',',$allpid,0,'','','4','rtl',0,'',$pid,"onChange=\"selectpage();\"",'213').
                            select_option('mid','مارک',',',$allmid,0,'','','4','rtl',0,'',$mid,"onChange=\"selectpage();\"",'133').
						    select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"onChange=\"selectpage();\"",'80');
						 				
                ?></td>
                        </tr>
						<?php
                        if ($login_ProducersID>0 || $login_RolesID==2)
                            $hidep="style=\"display:none;\"";
                        else
                            $hidep=""; 
 						?>
						
                        <tr >
                            <td <?php if ($login_RolesID==2) echo $hidep;?> colspan="2" class="label">نمایش مبالغ صفر:</td>
                            <td <?php if ($login_RolesID==2) echo $hidep;?> class="data"><input name="showzero" type="checkbox" id="showzero" onChange="selectpage()" value='<?php echo $showzero."'"; ?>' <?php if ($showzero>0) echo "checked"; ?> /></td>
                       
                            <td <?php echo $hidep;?> colspan="2" class="label">نمایش مبالغ صفر <?php if ($othertblname=='pricelistdetail') echo 'تایید شده'; else echo 'فروشنده'; ?>:</td>
                            <td <?php echo $hidep;?> class="data"><input name="showzero2" type="checkbox" id="showzero2" onChange="selectpage()" value='<?php echo $showzero2."'"; ?>' <?php if ($showzero2>0) echo "checked"; ?> /></td>
                       
                        </tr>
                        <tr>
                            <td <?php if ($login_RolesID==2)  echo $hidep;?> colspan="2" class="label">نمایش مبالغ غیر صفر:</td>
                            <td <?php if ($login_RolesID==2)  echo $hidep;?> class="data"><input name="shownzero" type="checkbox" id="shownzero" onChange="selectpage()" value='<?php echo $shownzero."'"; ?>' <?php if ($shownzero>0) echo "checked"; ?> /></td>
                       
                            <td <?php echo $hidep;?> colspan="2" class="label">نمایش مبالغ غیر صفر:</td>
                            <td <?php echo $hidep;?> class="data"><input name="shownzero2" type="checkbox" id="shownzero2" onChange="selectpage()" value='<?php echo $shownzero2."'"; ?>' <?php if ($shownzero2>0) echo "checked"; ?> /></td>
                       
                            
                       
                        </tr>
                     <tr <?php echo $hidep;?>>
                            <td colspan="2" class="label">نمایش فقط مراجع:</td>
                            <td class="data"><input name="showm" type="checkbox" id="showm" onChange="selectpage()" value='<?php echo $showm."'"; ?>' <?php if ($showm>0) echo "checked"; ?> /></td>
                      
                            <td colspan="2" class="label">نمایش قیمت های تایید نشده:</td>
                            <td class="data"><input name="shownapr" type="checkbox" id="shownapr" onChange="selectpage()" value='<?php echo $shownapr."'"; ?>' <?php if ($shownapr>0) echo "checked"; ?> /></td>
                        </tr>
                        
                     <tr <?php echo $hidep;?>>
                            <td colspan="2" class="label">فروشنده حاوی قیمت:</td>
                            <td class="data"><input name="shownp" type="checkbox" id="shownp" onChange="selectpage()" value='<?php echo $shownp."'"; ?>' <?php if ($shownp>0) echo "checked"; ?> /></td>
                      
                            <td colspan="2" class="label">نمایش ع س:</td>
                            <td class="data"><input name="showqm" type="checkbox" id="showqm" onChange="selectpage()" value='<?php echo $showqm."'"; ?>' <?php if ($showqm>0) echo "checked"; ?> /></td>
                       
                        </tr>
                        
                        <?php 
                        if ($login_moneyapprovepermit==1)
                        {
                         
                        echo "
                          <td colspan='2' class='label'>تایید لیست قیمت فروشنده:</td>
                          <td class='data'><input name='showapproved' type='checkbox' id='showapproved'";   
                          if ($Approved>0) echo 'checked'; 
                          echo " /></td>
                          
                      <td ><input name='submitp' type='submit' class='button' tabindex='$tabindex' id='submitp' value='ثبت' /></td>    
                        ";
                        echo 'آخرین لیست قیمت :'.$pl;   
                        }
                        ?>
                        
                            
                    
                   </tbody>
                </table>
                <table id="records" width="100%" align="center">
                    <thead>
                      <tr>
                      <?php if ($login_RolesID<>2)
                      {?>
                        <td colspan="4">
							<a onclick="SelectAll();"><img style = 'width: 30px;' src='../img/accept_page.png' title='  Select All '>  </a>
							<a onclick="DeleteAll('<?php print"http://$_SERVER[HTTP_HOST]/$home_path_iri/pricesaving/pricesaving1masterlist_refs_groupdelete.php";?>');">
							<img style = 'width: 30px;' src='../img/app-delete-icon.png' title='حذف'>
						</td>
                     <?php  }
                      ?>
					    
                        
                    
					<?php if ($login_ProducersID>0) 
                   echo "<td colspan='5'></td><td colspan='1' class='label' >درصد&nbsp;تغییرات:</td>";
				   else echo "<td colspan='6'></td>";
				   
                     ?>
					 
					  
					  <td >
                             <a onclick="transfer(-1);">
                            <img style = 'width: 25px;' src='../img/next.png' 
                            title='انتقال همه'></a>
                    
                    </td>
                    
                   <?php if ($login_ProducersID>0) 
                   echo "
				      <td class='data' colspan='2'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                       name='coef' type='text' class='textbox' id='coef'    /></td>";
                   else
                   echo "
				      <td class='data' colspan='2'><input style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 50px\"
                       name='coefadmin' type='text' class='textbox' id='coefadmin'    /></td>";
                    
                   ?>
				   
				   
                    </tr>
                    
                    
                       
                       
                       
                        <tr>
                        	<th colspan="3" width="1%"></th>
                        	<th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin'"
                             width="1%">کد</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin'"
                             width="1%">مشخصات فنی  H;L;F;T=mm  W=kg</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%">فروشنده</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%">عنوان کالا</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%">مارک</th>
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:12;line-height:120%;font-family:'B Nazanin';"
                             width="1%"></th>
                            <th style = "border:0px solid blaاck;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"><?php  if ($login_ProducersID>0) echo 'قیمت فروشنده'; else echo 'قیمت تایید شده';  ?></th>
                            <th width="1%">
                             
                             
                             </th>
                            
                            
                            <th style = "border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';"
                             width="1%"><?php  if ($login_ProducersID>0) echo 'ق تایید شده'; else echo 'قیمت فروشنده';  ?>
                             
                            <?php
                            
                                $chked='';
                                if ($shownzeroold>0)
                                    $chked='checked';
                                   /*   
                            if ($login_ProducersID>0) 
                            {
                                echo "<input name='shownzeroold' type='checkbox' id='shownzeroold' onChange=\"selectpage()\" value='$shownzeroold' $chked /></th>";    
                            }
                            else */
                            echo "
                            <th style = \"border:0px solid black;border-color:#ffffff #0000ff;text-align:center;font-size:14;line-height:120%;font-family:'B Nazanin';\"
                             '>ق لیست قبل
                             <input name='shownzeroold' type='checkbox' id='shownzeroold' onChange=\"selectpage()\" value='$shownzeroold' $chked />
                             </th>";  ?>
                            
                            
                            
                            
                        </tr>
                    </thead>
                   <tbody><?php
                    $cnt=0;
                    $totcnt=0;
                    $tabindex=0;
                    $maxcnt=$start+$per_page;
                    if ($result)
                    while($row = mysql_fetch_array($result))
                    {
                      $totcnt++;
                      if ($totcnt<=$start) continue;
                      if ($totcnt>$maxcnt) break;
                      
                      $row['Price'] = number_format($row['Price']);
                      $row['otherPrice'] = number_format($row['otherPrice']);
                      $row['previousPrice'] = number_format($row['previousPrice']);
                     
                     
                        //print $row['hidestate'].'sa';
                        $hidestate="";
                        if ($row['hidestate']>0)      
                           $hidestate="checked";
           
           
                     
                        $cnt++;
                        $tabindex++;
                        
                        if ($login_RolesID==2)
                            $row['PCode']=$row['toolsmarksID'];
?>
                        <tr>
                            <td > <input type="checkbox" id="cb<?php echo $cnt; ?>" name="chk<?php echo $row['toolsmarksID']; ?>" value="1"/></td >
                            
                            <td ><div style = "border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:25px;font-family:'B Nazanin';width: 25px"
                            ><?php echo ++$start; ?></div></td>
                            
                            <td ><div id="divPCode<?php echo $cnt; ?>"><input 
                            style = "border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 48px"
                            onmouseover="Tip(<?php echo '('.$row['PCode'].')'; ?>)" name="PCode<?php echo $cnt; ?>" type="hidden" class="textbox" id="PCode<?php echo $cnt; ?>" value="<?php echo $row['PCode']; ?>"    /></div></td>
                            <td ><div id="divnewPCode<?php echo $cnt; ?>"><input 
                            style = "background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 60px"
                            onmouseover="Tip(<?php echo '('.$row['PCode'].')'; ?>)" name="newPCode<?php echo $cnt; ?>" type="text" class="textbox" id="newPCode<?php echo $cnt; ?>" value="<?php echo $row['PCode']; ?>"  maxlength="50"  /></div></td>
                           
                           
                           <td >
                            
                            <?php
                            
                            $oval="";
                            $specarray = explode('_',$row['spec']);
                            $sval1=$specarray[0];
                            $sval2=$specarray[1];
                            $sval3=$specarray[2];
                            $sval4=$specarray[3];
                            $sval5=$specarray[4];
                            if ($sval1>0)
                                $oval.=" W=$sval1   ";
                            if ($sval2>0)
                                $oval.=" H=$sval2   ";
                            if ($sval3>0)
                                $oval.="L=$sval3   ";
                            if ($sval4>0)
                                $oval.="F=$sval4   ";
							if ($sval5>0)
                                $oval.="T=$sval5   ";
                            if ($sval6>0)
                                $oval.="Q=$sval6   ";
                            	
                                       echo "<div onclick=\"showdiv('$cnt');\"><input readonly 
                            style = \"border:1px solid black;border-color:#777;word-wrap: break-word;word-break: break-all;background-color:light-blue;
							text-align:left;font-size:10;line-height:190%;font-family:'B Nazanin';width: 200px\"
                             type=\"text\" class=\"textbox\"  value='$oval'    /></div >";
                             ?>
                               
                                <input name="spec<?php echo $cnt;?>" type="hidden" id="spec<?php echo $cnt;?>" value="<?php echo $row['spec'];?>"/>
                                <input name="toolsmarksspecID<?php echo $cnt;?>" type="hidden" id="toolsmarksspecID<?php echo $cnt;?>" value="<?php echo $row['toolsmarksspecID'];?>"/>
                            
                            </td>
                            
                            <td ><div style = "border:1px solid black;border-color:#777;text-align:center;font-size:11;line-height:25px;font-family:'B Nazanin';width: 105px">
                             <?php echo $row['producerstitle']; ?>    </div></td>
                            
                            <td ><div style = "border:0.5px solid black;border-color:#777;text-align:right;font-size:17;line-height:25px;font-family:'B Nazanin';
                            <?php   echo 'width: 350px';  ?>">
                            <?php echo $row['FullTitle']; ?></div></td>
                            
                            
                            <td ><div style = "<?php if ($row['isrefrence']=='m') print "background-color:#ff00b8;" ?>border:1px solid black;border-color:#777;text-align:center;font-size:10;line-height:25px;font-family:'B Nazanin';width: 60px"
                            ><?php echo $row['markstitle']; ?></div></td>
                            
                            
                            <td ><div style = "border:1px solid black;border-color:#777;text-align:center;font-size:12;line-height:25px;font-family:'B Nazanin';width: 34px"
                            ><?php echo $row['UnitsTitle']; ?></div></td>
                            
                            
                            
                            <td class="data"><input  
                            style = "background-color:#<?php if ($row['otherPrice']==$row['Price'] && $row['Price']>0 ) echo '88ff88'; else echo 'f1f5b8'; ?>;border:1px solid black;border-color:#777;text-align:right;font-size:16;line-height:120%;font-family:'B Nazanin';width: 100px"
                            onmouseover="Tip(<?php echo '(\''.($row['Price']).'\')'; ?>)" name="Price<?php echo $cnt; ?>" <?php 
                            if (!($login_ProducersID>0) && !($login_designerCO==1) ) echo 'readonly'; ?> type="text" class="textbox" id="Price<?php echo $cnt; ?>" value="<?php echo $row['Price']; ?>" size="10" maxlength="15" tabindex='<?php echo $tabindex; ?>' onKeyUp="convert('Price<?php echo $cnt; ?>')"  /></div></td>
                            <td class="data"><a onclick="transfer('<?php if ($row['Approved']==1) echo $cnt; else echo ''; ?>');">
                            <img style = 'width: 20px;' src='<?php if ($row['Approved']==1) echo '../img/next.png'; else echo '../img/help.png'; ?>' 
                            title='انتقال'></td>
                            
                          <?php
                          if ($row['otherPrice']!=$row['Price'])
                          $clr='background-color:#f1f5b8;'; else $clr='';
                          
                           if ($login_ProducersID>0) 
                   echo "<td class='data'><input  
                            style = \"$clr;border:1px solid black;border-color:#777;text-align:right;font-size:16;line-height:120%;font-family:'B Nazanin';width: 100px\"
                             
                            value='$row[otherPrice]'   /></div></td>";
                   else echo "<td class='data'><input  
                            style = \"$clr;border:1px solid black;border-color:#777;text-align:right;font-size:16;line-height:120%;font-family:'B Nazanin';width: 100px\"
                            onmouseover=\"Tip('$row[otherPrice]')\" name='otherPrice$cnt' type='text' class='textbox' id='otherPrice$cnt' 
                            value='$row[otherPrice]'   /></div></td>
                           "; 
                       ?>
                          
                            
                            <td class="data"><input  
                            style = "border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:140%;font-family:'B Nazanin';width: 100px;"
                            onmouseover="Tip(<?php echo '(\''.($row['previousPrice']).'\')'; ?>)" name="previousPrice<?php echo $cnt; ?>" type="text" class="textbox" id="previousPrice<?php echo $cnt; ?>" value="<?php echo $row['previousPrice']; ?>" size="10" maxlength="19" onKeyUp="convert('previousPrice<?php echo $cnt; ?>')" readonly /></div></td>
                           
                            
                            <td class="data"><input  onmouseover="Tip(<?php echo '(\''.($row['Price']).'\')'; ?>)" name="oldPrice<?php echo $cnt; ?>" type="hidden" class="textbox" id="oldPrice<?php echo $cnt; ?>" value="<?php echo $row['Price']; ?>"   /></div></td>
                            
                           
                           <?php
                           if (!($login_ProducersID>0)) 
                           echo "<td > <input type='checkbox' id='hide$cnt' name='hide$cnt' value='1' $hidestate /></td >";
                           
                           if ($login_RolesID==1) 
                           echo "<td > <input type='checkbox' id='expire$cnt' name='expire$cnt'   /></td >";
                           
                           ?>
                            
                           
                            <td class="data"><input name="toolsmarksID<?php echo $cnt; ?>" type="hidden" class="textbox" id="toolsmarksID<?php echo $cnt; ?>"  value="<?php echo $row['toolsmarksID']; ?>"  /></td>
                            
							
							
                            <td class="data"><input name="Approved<?php echo $cnt; ?>" type="hidden" class="textbox" id="Approved<?php echo $cnt; ?>"  value="<?php echo $row['Approved']; ?>"  /></td>
                            <td class="data"><input name="PriceListDetailID<?php echo $cnt; ?>" type="hidden" class="textbox" id="PriceListDetailID<?php echo $cnt; ?>"  value="<?php echo $row['PriceListDetailID']; ?>"  /></td>
                            
                            <?php 
                            

                            print " 
							    
							 
                             
                             
                              <tr>  
                                <td colspan='16' >   
                                    <table id='".$cnt."_content' style='display:none;' class='f13_font'>
                                       <tr>
                                        <td colspan='16'>&nbsp&nbsp&nbsp&nbsp&nbsp	وزن:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 50px\"
                                        name='val1$cnt' type='text' class='textbox' id='val1$cnt' value='$sval1' size=2 onmouseout='checkval($cnt)'/>
                                        کیلوگرم &nbsp
                                        ارتفاع:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 50px\"
                                        name='val2$cnt' type='text' class='textbox' id='val2$cnt' value='$sval2' size=2 />
										م.م &nbsp&nbsp	
                                        طول:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 50px\"
                                        name='val3$cnt' type='text' class='textbox' id='val3$cnt' value='$sval3' size=2/>
										م.م&nbsp&nbsp
                                        عرض:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 45px\"
                                        name='val4$cnt' type='text' class='textbox' id='val4$cnt' value='$sval4' size=2/>
										م.م&nbsp&nbsp
                                        ضخامت:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 45px\"
                                        name='val5$cnt' type='text' class='textbox' id='val5$cnt' value='$sval5' size=2/>
										م.م&nbsp&nbsp
                                       آبدهی:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 45px\"
                                        name='val6$cnt' type='text' class='textbox' id='val6$cnt' value='$sval6' size=2/>
										لیتردرساعت&nbsp
                                        پوشش:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 45px\"
                                        name='val7$cnt' type='text' class='textbox' id='val7$cnt' value='$sval7' size=2/>
										 &nbsp
                                        استاندارد:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 45px\"
                                        name='val8$cnt' type='text' class='textbox' id='val8$cnt' value='$sval8' size=2/>
										 &nbsp
                                        سایر:
                                        <input style = \"background-color:#f1f5b8;border:1px solid black;border-color:#777;text-align:center;font-size:14;line-height:140%;font-family:'B Nazanin';width: 270px\"
                                        name='val9$cnt' type='text' class='textbox' id='val9$cnt' value='$sval9' size=2/></td>
                                        </tr>
                                        
                                    </table>
			 
                                </td>  
                            </tr> 
                        </tr>
                              ";
						  
                        
                        $tabindex++;

                    }

?>
                      <td class="data"><input name="currpage<?php echo $cnt; ?>" type="hidden" class="textbox" id="currpage<?php echo $cnt; ?>"  value="<?php echo $currpage; ?>"  /></td>
                      <td class="data"><input name="ProducersID<?php echo $cnt; ?>" type="hidden" class="textbox" id="ProducersID<?php echo $cnt; ?>"  value="<?php echo $ProducersID; ?>"  /></td>
							
                    </tbody>
                    
                    <tfoot>
                      
                      
                       <tr>
                      <td colspan='4'></td>
                      
                      <?php 
					  $aryanjom=array('1','3','18');
					  
                     if(in_array($login_RolesID,$aryanjom) || ($login_moneyapprovepermit==2))
                      echo 
                      "<td ><input name=\"submit\" type=\"submit\" class=\"button\" tabindex='$tabindex' id=\"submit\" value=\"ثبت\" /></td>"; ?>
                      
                      
                      
                      <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                      <td class="data"><input name="ru" type="hidden" class="textbox" id="ru"  value="<?php echo $_SERVER['REQUEST_URI']; ?>"  /></td>
                      
                      
                      
                      <td class="data"><input name="PriceListMasterID" type="hidden" class="textbox" id="PriceListMasterID"  value="<?php echo $PriceListMasterID; ?>"  /></td>
                                  
                      </tr>
                      
                      
                      
                    </tfoot>
                    
                </table>
            
                </form>
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php');   ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
