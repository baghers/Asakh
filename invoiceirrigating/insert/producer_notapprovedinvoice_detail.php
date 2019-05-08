<?php 

/*

insert/producer_notapprovedinvoice_detail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/producerinvoicemaster_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>

<?php

 
if ($login_Permission_granted==0) header("Location: ../login.php");

if ($login_userid==28) //کابر دمو
    $hide='display:none';
//print $login_userid;
$paymentinvoice=0;
  if ($_POST && !$_POST['tempsubmit'])
  {
    if ($login_ProducersID>0)
    {
   		
		if ($_POST['ApproveP']) $ApproveP=jalali_to_gregorian(compelete_date($_POST['ApproveP']));else $ApproveP='';//تاریخ ارسال لوازم
		if ($_POST['settlementdate']) $settlementdate=jalali_to_gregorian(compelete_date($_POST['settlementdate']));else $settlementdate='';//تاریخ بارنامه
		if ($_POST['testdate']) $testdate=jalali_to_gregorian(compelete_date($_POST['testdate']));else $testdate='';//تاریخ تست
		
		// تاریخ ها بزرگتر از تاریخ روز باشند چک شود
		$query = "Delete from invoicetiming WHERE invoicemasterid = " . $_POST['invoicemasterid'] . ";";
		mysql_query($query);
        $SaveTime=date('Y-m-d H:i:s');
        $SaveDate=date('Y-m-d');
        $ClerkID=$login_userid;

		//invoicetiming جدول زمانبندی
        $query = "insert into  invoicetiming (invoicemasterid,ApproveP,producedateP,testdateP,BOLNO,tonajP,SaveTime,SaveDate,ClerkID) values (
                $_POST[invoicemasterid],'".$ApproveP."'
                ,'".$settlementdate."'
                ,'".$testdate."' 
                ,'$_POST[BOLNO]','$_POST[tonaj]', '$SaveTime','$SaveDate','$ClerkID');";
		  	   				  	try 
								  {		
									mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

							
    } else {
        $query = "UPDATE invoicetiming SET
				ApproveA = '".$ApproveP."'
				WHERE invoicemasterid = " . $_POST['invoicemasterid'] . ";";
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
          header("Location: producer_notapprovedinvoice_list.php");
} 
else 
{
$linearray = explode('_', substr($_GET["uid"],40,strlen($_GET["uid"])-45));
$invoicemasterid=$linearray[0];//شناسه لیست
$kind=$linearray[1];//نوع
$ApplicantMasterID=$linearray[2];//شناسه طرح
$ProducersID=$linearray[3];//تولیدکننده


if ($_POST['tempsubmit']) {
   $kind=3;
   $paymentinvoice=1;
   $invoicemasterid=$_POST['invoicemasterid'];   
   //header("Location:../payment/payment_master4.php");
  } 

if ($kind==1 || $kind==2 || $kind==3)
{
    $mastertbl='primaryinvoicemaster';
    $detailtbl='primaryinvoicedetail';   
    $join1="0";      
    $hect="";
    $join2="primaryinvoicemaster.operatorcoid";
	
}
else
{
   $viewPID=0;
	
    $mastertbl='invoicemaster';
    $detailtbl='invoicedetail';    
    $join1="$mastertbl.applicantmasterid";
    $hect="هکتار";
    $join2="applicantmasterop.operatorcoid";
}

    /*
    producerapprequestID شناسه جدول پیشنهاد قیمت
    PE32app مبلغ تایید شده برای لوله های 32
    PE40app مبلغ تایید شده برای لوله های 40
    PE80app مبلغ تایید شده برای لوله های 80
    PE100app مبلغ تایید شده برای لوله های 100
    ApplicantMasterID شناسه مطالعات
    invoicemaster لیست پیش فاکتور
    */    

    $sql2="select ApplicantMasterID,PE32app,PE40app,PE80app,PE100app,ProducersID from producerapprequest where state=1 and ApplicantMasterID
    in (select ApplicantMasterID from invoicemaster where invoicemasterid='$invoicemasterid')";    
        //print $sql2;
        
		  	   				  	try 
								  {		
									$result2 = mysql_query($sql2);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $row2 = mysql_fetch_assoc($result2);
        if ($row2['PE32app']>0 or $row2['PE40app']>0 or $row2['PE80app']>0 or $row2['PE100app']>0 )
        {
            $guerypipeprice="left outer join (select '$row2[ProducersID]' ProducersID, '$row2[PE32app]' PE32,'$row2[PE40app]' PE40,'$row2[PE80app]' PE80,'$row2[PE100app]' PE100 )
             pipeprice on pipeprice.ProducersID=toolsmarks.ProducersID";
        }
        else $guerypipeprice="left outer join pipeprice on pipeprice.Date=(select max(Date) from pipeprice where toolsmarks.ProducersID=pipeprice.ProducersID and  Date<=$mastertbl.InvoiceDate $condlimited) and pipeprice.ProducersID=toolsmarks.ProducersID"; 
        
	$search="SELECT InvoiceMasterID FROM `invoicemaster` where `invoicemaster`.`ProducersID`= $ProducersID and `invoicemaster`.`ApplicantMasterID`= $ApplicantMasterID"; 
	//$search="SELECT InvoiceMasterID FROM `invoicemaster` where `invoicemaster`.`ProducersID`= $row2[ProducersID] and `invoicemaster`.`ApplicantMasterID`= $row2[ApplicantMasterID]"; 
	//$search="$invoicemasterid";	
	/*
    taxpercent جدول درصد مالیات بر ارزش افزوده سالانه
    taxless حذف مالیات
    InvoiceDate تاریخ پیش فاکتور
    applicantmaster جدول مشخصات طرح
    ifnull(applicantmaster.ApplicantMasterIDmaster,0) در صورتی که صفر باشد طرح پیش فاکتور است والا صورت وضعیت می باشد
    freestateid شناسه مرحله آزادسازی
    yearcost.Value سال فهرست بهای آبیاری تحت فشار
    applicantstatestitle عنوان وضعیت طرح
    applicantstatesID شناسه وضعیت طرح
    errnum تعداد اشکالات گرفته شده توسط مشاور ناظر طرح
    RoleID نقش کاربر ثبت کننده جدول زمانبندی
    emtiaz امتیاز تخصیصی توسط مشاور ناظر برای پیمانکار
    ostancityname نام استان طرح
    shahrcityname نام شهر طرح
    bakhshcityname نام بخش طرح
    privatetitle شخصی بودن طرح
    prjtypetitle عنوان نوع پروژه
    prjtypeid شناسه نوع پروژه
    RolesID نقش کاربر
    applicantstatesID شناسه وضعیت طرح
    applicantstates جدول تغییر وضعیت های طرح
    costpricelistmaster جدول فهرست بها های آبیاری تحت فشار
    costpricelistmasterID شناسه فهرست بهای آبیاری تحت فشار طرح
    year جدول سال ها
    YearID شناسه سال طرح
    tax_tbcity7digit جدول شهرهای مختلف
    applicantfreedetail جدول ریز آزادسازی های انجام شده طرح ها
    freestateid=142 آزادسازی قسط دوم در وجه پیمانکار
    applicanttiming جدول زمانبندی اجرای طرح
    */ 
    
    $sql = "
        SELECT taxpercent.value taxpercentvalue,ifnull($mastertbl.taxless,0) taxless,$mastertbl.costnotinrep,$mastertbl.pricenotinrep,$mastertbl.$mastertbl"."ID InvoiceMasterID,$mastertbl.ProducersID,$mastertbl.TransportCost,
    $mastertbl.Discont,$mastertbl.InvoiceDate,$mastertbl.Rowcnt,$mastertbl.Serial,$mastertbl.Title
    ,producers.Title as PTitle,producers.AccountNo,producers.AccountBank,$mastertbl.Description,toolsmarks.ProducersID,$detailtbl.$detailtbl"."ID InvoiceDetailID,
            gadget3.Code,gadget3.gadget3ID,gadget2.gadget2ID,toolsmarks.MarksID,units.
        title utitle,$detailtbl.Number,pricelistdetail.pricelistdetailID
        ,replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
         gadget3Title
        ,marks.Title as MarksTitle 
        ,case gadget3.gadget2id when 202 then ROUND(gadget3.UnitsCoef2*pipeprice.PE80) 
            when 376 then ROUND(gadget3.UnitsCoef2*pipeprice.PE100) when 495 then ROUND(gadget3.UnitsCoef2*pipeprice.PE32) when 494 then ROUND(gadget3.UnitsCoef2*pipeprice.PE40)
            else pricelistdetail.Price  end Price,$mastertbl.PriceListMasterID,applicantmasterop.DesignArea,applicantmasterop.ApplicantFName,
applicantmasterop.ApplicantName,shahr.cityname shahrcityname,operatorco.title operatorcotitle
        FROM $detailtbl 
        inner join toolsmarks on toolsmarks.ToolsMarksID=$detailtbl.ToolsMarksID
        inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
        inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
        left outer join units on gadget3.unitsID=units.unitsID
        inner join marks on marks.MarksID=toolsmarks.MarksID
        inner join $mastertbl on $mastertbl.$mastertbl"."ID=$detailtbl.$mastertbl"."ID and $mastertbl.$mastertbl"."id in 
		($search)
        
        left outer join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=$join1 
        left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
        left outer join operatorco on operatorco.operatorcoid=$join2

        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        left outer join toolspref on toolspref.PriceListMasterID=$mastertbl.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
        left outer join pricelistdetail on  pricelistdetail.PriceListMasterID=$mastertbl.PriceListMasterID and 
                                            pricelistdetail.toolsmarksID = (case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        inner join producers on producers.ProducersID=$mastertbl.ProducersID
        $guerypipeprice
        
                
        left outer join operator on operator.operatorID=gadget3.operatorID
        left outer join spec2 on spec2.spec2id=gadget3.spec2id
        left outer join spec3 on spec3.spec3id=gadget3.spec3id
        left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
        left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
        left outer join year on year.Value = substring($mastertbl.InvoiceDate,1,4)
        left outer join taxpercent on year.YearID=taxpercent.YearID
        ORDER BY $detailtbl.$detailtbl"."ID;
        ";
      //  print $sql;
        //exit;
    $InvoiceMasterIDold=0;
    
		  	   				  	try 
								  {		
									$result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    while($resquery = mysql_fetch_assoc($result))
    {
        if ($InvoiceMasterIDold<>$resquery['InvoiceMasterID'])
        {
            
            if ($InvoiceMasterIDold>0)
            {
                 
                $globalprint.= "  <tr>
                                  <td class='print' colspan='1'   ></td>
                                  <td colspan='5' class='f12_font' >جمع  (ریال)</td>
                                  <td colspan='2'  class='f16_font' >".number_format($sum)."</td>
                                  <th class='no-print'  ></th>
                                  </tr>
                                  ";
                                  /*$globalprint.= "
                                  <tr>
                                  <td colspan='1' class='f20_font' ></td>
                                  <td colspan='5' class='f12_font' >مالیات بر ارزش افزوده(ریال)</td>
                                  
                                  <td colspan='2'  class='f16_font' >".number_format($TAXPercent*$sum/100)."</td>
                                  </tr>
                                  ";
                                  */
                                  if ($TransportCost>0)
                                  
                                  $globalprint.= "<tr>
                                  <td class='print' colspan='1'   ></td>
                                  <td colspan='5' class='f12_font' >هزینه های جانبی(ریال)</td>
                    
                                  <td  colspan='2' class='f16_font' >".number_format($TransportCost)."</td>
                                  <th class='no-print'  ></th>
                                  </tr>";
            if ($Discont>0)
                       $globalprint.= "<tr>
                      <td class='print' colspan='1'   ></td>
                      <td colspan='5' class='f12_font' >تخفیف(ریال)</td>
                      
                      <td  colspan='2' class='f16_font' >".number_format($Discont)."</td>
                      <th class='no-print'  ></th>
                      </tr>
                      ";
                      
                       $globalprint.= "
                      <td class='print' colspan='1'   ></td>
                      <td colspan='5' class='f17_font' >جمع  با ارزش افزوده(ریال)</td>
                      
                      <td colspan='2'  class='f18_font'>".number_format($sum+$TransportCost-$Discont+($TAXPercent*$sum/100))."</td>
                      <th class='no-print'  ></th>
                                  
                      </tr>
                      
                      <tr>
                      <td colspan='8' class='f19_font' >&nbsp</td>
                      </tr>
                      
                      
                </div>";        
                
                if (! $pricenotinrep) 
                {
                    $arrayindexinvoice++;
                    if ($operatorcoid>0)
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title."($PTitle $AccountNo $AccountBank)"]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100);  
                    else
                        $arrayinvoices[$arrayindexinvoice.'-'.$Title]=$sum+$TransportCost-$Discont+($TAXPercent*$sum/100); 
                         
                }
            }

            
            
            
            $InvoiceMasterIDold=$resquery['InvoiceMasterID']; 
            if ($kind==1)
                $taxless=0;
            else    $taxless=$resquery['taxless'];
            $masterProducersID = $resquery['ProducersID'];
            $TransportCost = $resquery['TransportCost'];
            $Discont = $resquery['Discont'];                        
            $np = $resquery['Rowcnt'];
            $Serial = $resquery['Serial'];
            $Title = $resquery['Title'];
            $AccountNo = $resquery['AccountNo'];
            $AccountBank = $resquery['AccountBank'];
            $PTitle = $resquery['PTitle'];
            $Description = $resquery['Description'];
            $InvoiceDate = $resquery['InvoiceDate'];
            $pricenotinrep = $resquery['pricenotinrep'];
            $costnotinrep = $resquery['costnotinrep'];
            $PriceListMasterID=$resquery['PriceListMasterID'];
            $owncost='';
            if ($pricenotinrep) $owncost='خرید لوازم با هزینه شخصی متقاضی';
            if ($costnotinrep) $owncost.='  لوازم اجرا شده ';
            if ($owncost!='') $owncost="($owncost)";
            $TAXPercent=0;
            if (strlen($resquery['InvoiceDate'])>0 && $taxless==0)
                $TAXPercent = $resquery['taxpercentvalue'];     
              
                $fstr1="";
                $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/invoice/';
                $handler = opendir($directory);
                while ($file = readdir($handler)) 
                {
                    if ($file != "." && $file != "..") 
                    {
                        $linearray = explode('_',$file);
                        $ID=$linearray[0];
                        $No=$linearray[1];
                        if (($ID==$InvoiceMasterIDold) && ($No==1) )
                            $fstr1="<a target='blank' href='../../upfolder/invoice/$file' ><img style = 'width: 30%;' src='../img/full_page.png' title='اسکن پیش فاکتور' ></a>";
                            
                            
                    }
                }
                 $queryPriceListMasterID = "
                select CONCAT(month.Title,' ',year.Value) pr from pricelistmaster
                inner join year on year.YearID=pricelistmaster.YearID
                inner join month on month.MonthID=pricelistmaster.MonthID
                WHERE   PriceListMasterID='$PriceListMasterID'";
                
				  	   		try 
								  {		
									$resultPriceListMasterID = mysql_query($queryPriceListMasterID);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	            $rowPriceListMasterID = mysql_fetch_assoc($resultPriceListMasterID);
                $pr=$rowPriceListMasterID['pr'];
                if (strlen($pr)>0) $pr="($pr)";
                
                $globalprint.= "<div colspan='8'  >
                                    <tr>
                                        <td class='f2_font'></td>
                                        <td colspan='5' class='f4_font'> $Title $owncost</td>
                                        <td class='f6_font'>   شماره:  </td>
                                        <td class='f6_font'>    $Serial   </td>
                                    </tr>
                                    <tr>
                                        <td class='f2_font'></td>
                                        <td colspan='5' class='f5_font'> $PTitle $pr</td>
                                        <td class='f6_font'>   تاریخ:  </td>
                                        <td class='f6_font'>    $InvoiceDate </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class='f2_font'></td>
                                        <td colspan='5' class='f5_font'> $resquery[ApplicantFName] - $resquery[ApplicantName] - $resquery[DesignArea] $hect - $resquery[shahrcityname]</td>
									";
								
								if ($login_isfulloption==1)
								  $globalprint.= 	"									
                                        <td class='f6_font'>   مجری:  </td>
                                        <td class='f6_font' style=$hide >    $resquery[operatorcotitle] </td>
                                    </tr> ";
                
                
                //if ($login_RolesID==1) print 'sa'.$fstr1.$InvoiceMasterID;
                if ($fstr1!='')
                    $globalprint.= "<tr>
                                        <td class='f2_font'></td>
                                        <td colspan='5' class='f5_font'>  </td>
                                        <td class='f6_font'>   اسکن:  </td>
                                        <td class='f6_font'>    $fstr1 </td>
                                    </tr> ";
                
                
                
                
                 $globalprint.= "<tr>
                    <!--<td colspan='2'            class='f7_font'>   توضیحات:  </td>
                    <td colspan='4' class='f8_font'> $Description </td>
                    -->
                    </tr>
                    <tr>
                      <td colspan='8' >&nbsp</td>
                      </tr>
                      
                ";   
                $cnt=0;
                $rown=0;
                $sum=0;
                
                $globalprint.= "<tr>
                                    <td class='print'  class='f9_font' ></td>
                                    <th align='center' class='f10_font' >ردیف</th>
                                    <th align='center' class='f10_font'>شرح </th>
                                    <th align='center' class='f10_font'>مارک</th>
                                    <th align='center' class='f10_font'>واحد</th>
                                    <th align='center' class='f10_font'>مقدار</th>
                                    <th align='center' class='f10_font'>فی(ریال)</th>
                                    <th align='center' class='f10_font'>جمع (ریال)</th>
                                	<th class='no-print' align='center' class='f10_font' ></th>
                                </tr>";
        }
        
            $InvoiceDetailID = $row['InvoiceDetailID'];
            $Code = $resquery['Code'];
            $gadget3ID = $resquery['gadget3ID'];
            $gadget2ID = $resquery['gadget2ID'];
            $gadget1ID = $resquery['gadget1ID'];
            $ProducersID = $resquery['ProducersID'];
            $MarksTitle='';
            //if ($resquery['MarksTitle']!='--' && $resquery['MarksTitle']!='..')
            $MarksTitle=$resquery['MarksTitle'];
            $utitle = trim($resquery['utitle']);
            $gadget3Title = $resquery['gadget3Title'];
            $Number = ($resquery['Number']);
            $Price = number_format($resquery['Price']);
            $SumPrice = number_format($resquery['Number']*$resquery['Price']);
            $Description = $resquery['Description'];
            $sum+=$resquery['Number']*$resquery['Price'];     
            $readonlydesc='';        
            
            
            if ($Number>0)
            {
                   
                $rown++;
                 $globalprint.= "     <tr>
                            <td class='print'  class='f9_font'></td>
                            <td class='f11_font'>$rown</td>
                            <td class='f12_font'>$gadget3Title</td>
                            <td class='f13_font'>$MarksTitle</td>
                            <td class='f11_font'>$utitle</td>
                            <td class='f11_font'>$Number</td>
                            <td class='f11_font'>$Price</td>
                            <td class='f11_font'>$SumPrice</td>
                            <td class='no-print' class='f14_font'></td>
                </tr>";   
            } 
        

        
           
        
        
    
    }
     
        $globalprint.= "  <tr>
                                  <td class='print' colspan='1'   ></td>
                                  <td colspan='5' class='f12_font' >جمع  (ریال)</td>
                                  <td colspan='2'  class='f16_font' >".number_format($sum)."</td>
                                  <th class='no-print'  ></th>
                                  </tr>
                                  ";
                                  /*$globalprint.= "
                                  <tr>
                                  <td colspan='1' class='f20_font' ></td>
                                  <td colspan='5' class='f12_font' >مالیات بر ارزش افزوده(ریال)</td>
                                  
                                  <td colspan='2'  class='f16_font' >".number_format($TAXPercent*$sum/100)."</td>
                                  </tr>
                                  ";
                                  */
                                  if ($TransportCost>0)
                                  
                                  $globalprint.= "<tr>
                                  <td class='print' colspan='1'   ></td>
                                  <td colspan='5' class='f12_font' >هزینه های جانبی(ریال)</td>
                    
                                  <td  colspan='2' class='f16_font' >".number_format($TransportCost)."</td>
                                  <th class='no-print'  ></th>
                                  </tr>";
            if ($Discont>0)
                       $globalprint.= "<tr>
                      <td class='print' colspan='1'   ></td>
                      <td colspan='5' class='f12_font' >تخفیف(ریال)</td>
                      
                      <td  colspan='2' class='f16_font' >".number_format($Discont)."</td>
                      <th class='no-print'  ></th>
                      </tr>
                      ";
                      
                       $globalprint.= "
                      <td class='print' colspan='1'   ></td>
                      <td colspan='5' class='f17_font' >جمع  با ارزش افزوده(ریال)</td>
                      
                      <td colspan='2'  class='f18_font'>".number_format($sum+$TransportCost-$Discont+($TAXPercent*$sum/100))."</td>
                      <th class='no-print'  ></th>
                                  
                      </tr>
                      
                      <tr>
                      <td colspan='8' class='f19_font' >&nbsp</td>
                      </tr>
                      
                      
                </div>
                
                
                      
                ";   
    
    

        
}



     
                
              
    









?>
    <style>
    p.page { page-break-after: always; }


.f1_font{
	border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';                       
}
.f2_font{
	border:0px solid black;width: 10%;                        
}
.f4_font{
	border:0px solid black;text-align:center;width: 100%;font-size:16.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                        
}
.f5_font{
    border:0px solid black;width: 80%;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f6_font{
    border:0px solid black;width: 5%;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f7_font{
    border:0px solid black;width: 10%;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f8_font{
    border:0px solid black;width: 90%;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f9_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f10_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
}
.f11_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f12_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f13_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f14_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f15_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;width: 130px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f16_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f17_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f18_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f19_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:300%;font-weight: bold;font-family:'B Nazanin';
}
.f20_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:left;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f21_font{
    background-color:#b0eab9;width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
}
.f22_font{
    background-color:#b0eab9;width: 50px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f23_font{
    width: 350px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f24_font{
    width: 50px;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:145%;font-weight: bold;font-family:'B Nazanin';
}
.f25_font{
    width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f26_font{
    width: 450px;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f27_font{
    width: 80px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f28_font{
    width: 200px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';

}
.f29_font{
    width: 120px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f30_font{
    width: 80px;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f31_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:16.0pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';
}
.f32_font{
    width: 550px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f33_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 400px;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f34_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;width: 195px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f35_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f36_font{
    border-left: 1px solid black;border-color:#0000ff #0000ff;
}
.f37_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;width: 120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f38_font{
    border-bottom: 1px solid black;border-left: 1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f39_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f40_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f41_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;width: 100%;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f42_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;width: 100%;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f43_font{
    border-left: 1px solid black;border-color:#0000ff #0000ff;
}
.f44_font{
    border:0px solid black;background-color:#ffff00;border-color:#0000ff #0000ff;text-align:right;width: 30px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f45_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f46_font{
    background-color:#ffff00;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f47_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:16.0pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';
}
.f48_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f49_font{
    background-color:#b0eab9;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width: 150px;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f50_font{

    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:16.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f51_font{
    background-color:#ffff00;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;width:120px;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f52_font{
    border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px;
}
.f53_font{
    width: 300px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f54_font{
    width: 215px;background-color:#b0eab9;border:0px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';
}
.f55_font{
    width: 20px;border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';
}
.f56_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:500%;font-weight: bold;font-family:'B Nazanin';
}

      </style>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست طرح ها</title>
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
                $("#ApproveP, #simpleLabel").persiandatepicker(); 
                $("#settlementdate, #simpleLabel").persiandatepicker(); 
                $("#testdate, #simpleLabel").persiandatepicker();  
				
            });
        
        
    </script>
    

    <script>
function CheckForm()
{
 var x=document.getElementById('ApprovePtemp').value.length;
 if (x>0)
 {
 
  if (!(document.getElementById('BOLNO').value>0))
    {
        alert('شماره آخرین بارنامه!');return false;
    }    
 if (!(document.getElementById('tonaj').value>0))
    {
        alert('جمع کل وزن باسکولها!');return false;
    }    
	
    return confirm('مطمئن هستید که جدول زمان بندی ثبت شود ؟');
 }
}

    </script>
	
  
    <!-- /scripts -->
</head>
<body onload="changelist();calculate();changebank();">

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
                <form action="producer_notapprovedinvoice_detail.php" method="post" onSubmit="return CheckForm()">
                    <tbody>
                        <table width="95%" align="center">
                   <div style = "text-align:left;">
                            <a  href='producer_notapprovedinvoice_list.php'>
                            <img style = "width: 2%;" src="../img/Return.png" title='بازگشت' ></a>
                            
                          </div>
		
            <?php
		if ($kind==4) $hide='display:none';	
            echo $globalprint;
            
            if ($kind!=1 && $kind!=2 && $kind!=3)
            {
                $query = "select * from invoicetiming where invoicemasterid='$invoicemasterid';";
                //print $query;
                
						try 
								  {		
									$result=mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                $row = mysql_fetch_assoc($result);
                if ($row['producedateP']>0)
                $producedateP=gregorian_to_jalali($row['producedateP']);
                if ($row['testdateP']>0)
                $testdateP=gregorian_to_jalali($row['testdateP']);
                if ($row['ApproveP']>0)
                $ApproveP=gregorian_to_jalali($row['ApproveP']);
			
				$readonly="required";
				if ($ApproveP=='' || $testdateP=='' || $producedateP=='')  $readonly="readonly";
				
				if ($ApproveP=='' || $testdateP=='' || $producedateP=='')  {$readonlyD="";$idD="";} else {$readonlyD="readonly";$idD="D";}
				
			
         echo
                    "
					<tr>		 
						<td  class='f24_font' colspan='9'   > جدول زمانبندی تولید و تحویل کالا</td>
                
        			</tr>		 
        
					<td class='print' colspan='1'   ></td>
                      <td   class='label' colspan=1>تاریخ  شروع تولید:</td> 
                      <td ><input $readonlyD placeholder='انتخاب تاریخ' value='$producedateP'  class='f52_font'
                             name='settlementdate' type='text' class='textbox' id='settlementdate".$idD."'  />
							 
					  تاریخ شروع ارسال کالا:</td> 
                      <td ><input $readonlyD placeholder='انتخاب تاریخ' value='$testdateP'  class='f52_font'
                             name='testdate' type='text' class='textbox' id='testdate".$idD."'   /></td>
                  
				  
                      <td  class='label' colspan=1>تاریخ تکمیل تحویل:</td> 
                      <td ><input $readonlyD placeholder='انتخاب تاریخ'  value='$ApproveP' class='f52_font'
                             name='ApproveP' type='text' class='textbox' id='ApproveP".$idD."'    /></td>
		
		<td></td>
		           
				   <tr>		 
					<td class='print' colspan='1'   ></td>
                    
                     <td  class='label' colspan=1>شماره آخرین بارنامه:</td> 
                      <td ><input   $readonly value='$row[BOLNO]'  class='f52_font'
                             name='BOLNO' type='text' class='textbox' id='BOLNO'    />
							 جمع کل وزن  باسکولها-تن:</td> 
                      <td ><input  $readonly value='$row[tonajP]' class='f52_font'
                             name='tonaj' type='text' class='textbox' id='tonaj'    /></td>
                                    
		<td></td>
		<td></td>

            <input class='no-print' name='ApprovePtemp' type='hidden' class='textbox' id='ApprovePtemp'  value='$ApproveP'  />
           <input class='no-print' name='invoicemasterid' type='hidden' class='textbox' id='invoicemasterid'  value='$invoicemasterid'  />
		<td style=$hide><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
							 
								</tr>		 
						<td  colspan='9'   >-----------------------------------------------------------------------</td>
    	
        						<tr>		 
        						</tr>		 
        						<tr>		 
						<td   colspan='9'   > * تاریخهای زمانبندی را بصورت همزمان، و قبل از تولید کالا وارد نمایید.</td>		 
        						</tr>		 
        						<tr>
						<td   colspan='9'   > * امکان ثبت شماره بارنامه و وزن باسکول بعد از ثبت هر سه تاریخ تولید، ارسال و تکمیل فعال می شود..</td>
            					
        						</tr>		 
        		  	";
					
		    }
        
        
            
            echo "      </table>
       
                    </tbody>
                ";
            
                if ($login_userid==28)
        print "کد پیگیری شما: ".($invoicemasterid*300020001);
            
        
        echo "</form>
                
            </div>";
             include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
