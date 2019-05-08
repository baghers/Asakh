<?php
/*
funcviewapp.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
function sqlviewapp()//جستجوی طرح ها
{
    
        /*
    applicantmaster جدول مشخصات طرح
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    proposestatep وضعیت پیشنهاد قیمت
    ApplicantMasterID شناسه طرح
    creditsourcetitle عنوان منبع تامین اعتبار
    credityear سال اعتبار طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    ADate تاریخ شروع پیشنهاد قیمت
    BankCode کد رهگیری طرح
    designername عنوان طراح
    designsystemgroupstitle سیستم آبیاری
    shahrcityname نام شهر
    designer.LName نام خانوادگی طراح
    designer.FName نام طراح
    operatorapprequest جدول پیشنهاد قیمت های طرح
    state برنده شدن یا نشدن
    clerk جدول کاربران
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    private شخصی بودن طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    year جدول سال
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    designer جدول طراحان
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    operatorcoid شناسه پیمانکار
    DesignerCoID شناسه مشاور طراح
    costpricelistmaster جدول فهرست بها
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignerID شناسه طراح طرح
    applicantstatesID شناسه وضعیت طرح
    corank رتبه شرکت
    firstperiodcoprojectarea مجموع مساحت پروژه های انجام داده اول دوره شرکت
    firstperiodcoprojectnumber تعداد  پروژه های انجام داده اول دوره شرکت
    coprojectsum مجموع تعدادی پروژه های شرکت
    projecthektardone پروژه های انجام داده شرکت
    simultaneouscnt تعداد پروژه های همزمان
    thisyearprgarea مساحت پرژه های امسال
    above20cnt تعداد پروژه های بالای 20 هکتار
    above55cnt تعداد پروژه های بالای 55 هکتار
    currentprgarea مساحت پروژه های جاری
    projectcountdone تعداد پروژه های انجام داده شرکت
    clerk.clerkid شناسه کاربر
    designerinfo.designercnt تعداد کارشناسان طراح شرکت
    designerinfo.dname نام کارشناس طراح
    designerinfo.duplicatedesigner داشتن کارشناسی که در دو شرکت فعالیت نماید
    membersinfo.duplicatemembers عضو هیئت مدیره که در دو شرکت فعالیت نماید
    allreq.cnt reqcnt تعداد پیشنهادات ارسال شده
    allwinreq.wincnt تعداد پیشنهادات انتخاب شده
    avgpmreq.avg میانگین ظرایب پیشنهاد قیمت های شرکت
    avgpmreqa.avga میانگین ظرایب پیشنهاد قیمت های انتخابی
    coef1 ضریب اول اجرای طرح
    coef2 ضریب دوم اجرای طرح
    coef3 ضریب سوم اجرای طرح
    ent_DateFrom شروع انتظامی بودن شرکت
    ent_DateTo پایان انتظامی بودن شرکت
    ent_Hectar هکتار انتظامی بودن شرکت
    ent_Num تعداد انتظامی بودن شرکت
    percentapplicantsize درصد افزایش اندازه پروژه
    applicantmasterdetail جدول ارتباطی طرح ها
    */
include('includes/check_user.php');
include('includes/connect.php');
//echo 'sqlviewapp:'.$login_RolesID.'*'.$login_CityId.'&'.$login_userid.'<br>';

$str="";  
   
   if ($login_RolesID=='17') //ناظر مقیم
    $str=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
   else if ($login_RolesID=='14')//ناظر عالی
    $str=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
   elseif ($login_RolesID=='6' || $login_RolesID=='15') //کاربر 
     $str=" and applicantstates.applicantstatesID in (1,12,13,19,20,21,22,35,36,37,34,38) ";  
   elseif ($login_RolesID=='7') //ادمین
     $str=" and applicantstates.applicantstatesID in (1,19,30,35,36,37,38,44,34,38) ";
   elseif ($login_RolesID=='16') //صندوق
     $str=" and applicantstates.applicantstatesID in (1,10,12,20,22,30,35,34,38) ";
   elseif ($login_RolesID=='11' || $login_RolesID=='9') //طراح و مدیر طراح
     $str=" and applicantstates.applicantstatesID in (2,3,4,5,6,7,8,9,11,23,24,25,50) ";
   elseif ($login_RolesID=='2') //مجری
     $str=" and applicantstates.applicantstatesID in (23,26,27,30,31,32,35,39,40,41,42,43,44,46,47,34,38) ";
   elseif ($login_RolesID=='10') //ناظر
     $str=" and applicantstates.applicantstatesID in (30,40,41,42,43,44,46,47) ";
    

  $sql = "SELECT invoicetiming.ApproveA,invoicetiming.BOLNO,invoicetiming.ApproveP,applicantmaster.cityid,applicantmaster.BankCode,applicantmaster.ApplicantFName,applicantmaster.ApplicantName
  ,applicantmaster.private
  ,applicantmaster.LastTotal,applicantmaster.belaavaz belaavaz
  ,applicantmasterop.LastTotal LastTotalop,applicantmasterop.belaavaz belaavazop
  ,applicantmastersurat.LastTotal LastTotals,applicantmastersurat.belaavaz belaavazs
  ,applicantmaster.ApplicantMasterID,applicantmasterop.ApplicantMasterID ApplicantMasterIDop,applicantmastersurat.ApplicantMasterID ApplicantMasterIDs
  ,case applicantmastersurat.LastFehrestbahawithcoef>0 when 1 then applicantmastersurat.LastFehrestbahawithcoef 
else case applicantmasterop.LastFehrestbahawithcoef>0 when 1 then applicantmasterop.LastFehrestbahawithcoef 
else applicantmaster.LastFehrestbahawithcoef end  end LastFehrestbahawithcoef,

  applicantstates.title applicantstatestitle,designerco.DesignerCoID,(applicantfreedetailID.maxfreestateID) maxfreestateID,
  applicantmaster.DesignArea,designerco.title DesignerCotitle,applicantstates.applicantstatesID,
  operatorco.Title OperatorcoTitle,operatorco.operatorcoID operatorcoID,shahr.cityname shahrcityname, designercos.Title nazercoTitle
  , designsystemgroups.title DesignSystemGroupstitle,case ifnull(applicantmaster.ApplicantMasterIDmaster,0) when 0 then 0 else 1 end issurat
  ,applicantmaster.othercosts1,applicantmaster.othercosts2,applicantmaster.othercosts3,applicantmaster.othercosts4,applicantmaster.othercosts5
  ,applicantmaster.TMDate SaveDate
  ,applicantmaster.applicantstatesID applicantstatesIDd,applicantmasterop.applicantstatesID applicantstatesIDop,
  applicantmastersurat.applicantstatesID applicantstatesIDs
  ,applicantmaster.CostPriceListMasterID CostPriceListMasterIDd,applicantmasterop.CostPriceListMasterID CostPriceListMasterIDop,
  applicantmastersurat.CostPriceListMasterID CostPriceListMasterIDs  
  
  
FROM applicantmaster 
inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
left outer join applicantmaster applicantmastersurat on applicantmastersurat.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDsurat

left outer join (select ApplicantMasterID,max(freestateID) maxfreestateID from applicantfreedetail group by ApplicantMasterID) applicantfreedetailID  
on applicantfreedetailID.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
left outer join operatorco on operatorco.operatorcoID=applicantmasterop.operatorcoID
left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID

inner join applicantstates on applicantstates.applicantstatesID=case applicantmastersurat.applicantstatesID>0 
when 1 then applicantmastersurat.applicantstatesID 
else case applicantmasterop.applicantstatesID>0 when 1 then applicantmasterop.applicantstatesID else applicantmaster.applicantstatesID end  end

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join designerco designercos on designercos.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end


left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 
UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on 
designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid

left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  on invoicemaster.ApplicantMasterID=applicantmasterop.ApplicantMasterID 
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID


where 1=1 
$str 

";
//print $sql;

return $sql;
}

function dvlist($result,$rw,$sql)
{
    //print $sql;
    //exit;
    print "<div class=\"CSSTable\" id=\"dvlist\" >
    	<table>
    	           <tr>
    				    <td class=\"t2\">  رديف </td>
    				    <td class=\"t2\">  انتخاب </td>
    					<td class=\"t2\">  نام </td>
                        <td	class=\"t2\">  نام خانوادگی  </td>
                        <td	class=\"t2\"> مساحت </td>
                        <td class=\"t2\">   سیستم </td>
                        <td	class=\"t2\"> شهرستان </td>	
                        <td	class=\"t2\">  طراح </td>
                        <td	class=\"t2\"> ناظر </td>
                        <td	class=\"t2\"> مجری  </td>
                        <td	class=\"t2\"> کدرهگیری </td>
                        <td	class=\"t2\"> آخرین وضعیت </td>
                    </tr>";
    
    				
    
    $oldbank='';
    $i=1;
    while ($rowresult1 = mysql_fetch_assoc($result)) 
    {
        print "		<tr>
        				    <td class=\"t2\">$i</td>
        				    <td class=\"t2\" ><input type=\"radio\" id=\"r1\" name=\"r1\" onclick=\"ajaxview('$rowresult1[ApplicantMasterID]')\" > </td>
        					<td class=\"t2\"> $rowresult1[ApplicantFName]</td>
                            <td	class=\"t2\"> $rowresult1[ApplicantName] </td>
                            <td	class=\"t2\"> $rowresult1[DesignArea] </td>
                            <td class=\"t2\"> $rowresult1[DesignSystemGroupstitle] </td>
                            <td	class=\"t2\"> $rowresult1[shahrcityname]  </td>
                            <td	class=\"t2\"> $rowresult1[DesignerCotitle]</td>
                            <td	class=\"t2\"> $rowresult1[nazercoTitle]  </td>
                            <td	class=\"t2\"> $rowresult1[OperatorcoTitle] </td>
                            <td	class=\"t2\"> $rowresult1[BankCode] </td>
                            <td	class=\"t3\"> $rowresult1[applicantstatestitle] </td>        
        				</tr>"; 
        
        		
        				
         $i++;
     } 
     print "</table></div>";
}
function dvdet($rowresult)
{
    include('includes/check_user.php');
    include('includes/connect.php');
    $permitstate = array("22","26","27","30","31","32","35","37","39","40","41","42","43","44","45","46","47","34","38","50","17");
    $permiroles = array("1","9","7","13","14","16","11","12","19","17");
    if (in_array($rowresult['applicantstatesID'], $permitstate)) {$hidePropose=' ';} else {$hidePropose='style=display:none';} 
    if ((in_array($login_RolesID, $permiroles)) || (strlen($rowresult['OperatorcoTitle'])!=0 )) $hide=' '; else   $hide='style=display:none';
        $hideD=' ';	$hideP=' ';
    if($login_RolesID=='2' && $hide==' ') $hideD='style=display:none';
    if($login_RolesID=='9' && $hide==' ')  $hideP='style=display:none';
   
   if ($rowresult['ApproveA']>0)
                                $pipestate='تایید دستگاه نظارت';
                                else if ($rowresult['BOLNO']>0)
                                $pipestate='بارنامه شد- عدم تایید دستگاه نظارت';
                                else if ($rowresult['ApproveP']>0)
                                    $pipestate='ارسال اولیه توسط تولید کننده';
                                    else 
                                        $pipestate='';
                                        
    print "<div class=\"CSSTable\"  >
    <table id=\"dvdet\">
                    <tr><td colspan=11> وضعيت طرح :   $rowresult[ApplicantFName] $rowresult[ApplicantName]    --     کد رهگیری : $rowresult[BankCode] </td></tr>
                    <tr><td>نام  : $rowresult[ApplicantFName] $rowresult[ApplicantName]  </td>
						<td>شهرستان  : $rowresult[shahrcityname]  </td> 
						<td> مساحت طرح : $rowresult[DesignArea] &nbsp;هكتار</td> 
						<td> آخرين وضعيت : $rowresult[applicantstatestitle]</td> 	
                    <tr>
						<td>شركت طراح  :$rowresult[DesignerCotitle]  </td>
					    <td>شركت ناظر : $rowresult[nazercoTitle]</td>
						<td> سهم بلاعوض : $rowresult[belaavaz] &nbsp;م ر  </td> 
					    <td>مبلغ طراحی : ".number_format($rowresult['LastTotal'])."   </td> 
						
						</tr>
					<tr>
					<td $hide>شركت مجري : $rowresult[OperatorcoTitle]</td>
					    <td $hide>مبلغ پیش فاکتور : ".number_format($rowresult['LastTotalop'])."</td>
						<td $hide>مبلغ صورت وضعیت : ".number_format($rowresult['LastTotals'])."</td>
						<td $hide>هزینه اجرا : ".number_format($rowresult['LastFehrestbahawithcoef'])." </td>
						<td></td>
						</tr>
                        
                        <tr>
					<td >وضعیت تولید لوله : $pipestate </td>
					    <td></td>
                        <td></td>
                        <td></td>
						<td></td>
						</tr>
                        
                </table>";	

    if (!$login_user) 
    { 
        $permitlogin_user=1;
    }
    if (($login_user) ||  ($permitlogin_user>0)) 
    {
        include('includes/check_user.php');
        print "<table><tr><td class='no-print t2'>($rowresult[CostPriceListMasterIDd])لیست لوازم ($rowresult[ApplicantMasterID])<a  target='_blank' href='insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterID'].'_4_'.$rowresult['DesignerCoID'].'_0_'.$rowresult['applicantstatesIDd'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/full_page.png' title='ليست لوازم طراحي'></a></td>";  
        print "<td class='no-print t2' $hide>($rowresult[CostPriceListMasterIDop])پیش فاکتور ($rowresult[ApplicantMasterIDop])<a  target='_blank' href='insert/summaryinvoice.php?uid="     .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_4_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].'_'.$rowresult['applicantstatesIDop'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/search.png' title='پیش فاکتور'></a></td>";  
        print "<td class='no-print t2' $hide>($rowresult[CostPriceListMasterIDs])صورت وضعیت ($rowresult[ApplicantMasterIDs])<a  target='_blank' href='insert/summaryinvoice.php?uid="     .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterIDs'].'_4_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].'_'.$rowresult['applicantstatesIDs'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/accept_page.png' title='صورت وضعیت'></a></td>";  
        print "<td class='no-print t2' $hide>آزادسازیها<a  target='_blank' href='appinvestigation/invoicemasterfree_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_2_0_'.$rowresult['operatorcoID'].'_'.$rowresult['OperatorcoTitle'].rand(10000,99999).
									"'><img style = 'width: 25px;' src='img/mail_send.png' title='ليست آزادسازي'></a></td>";  
    //print "sa";
         print "<td class='no-print t2' $hidePropose>پیشنهاد قیمت اجرا<a  target='_blank' href='appinvestigation/allapplicantrequestdetail.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterID'].'_5_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].'_1901'.rand(10000,99999)."'>
									<img style = 'width: 25px;' src='img/mail_search.png' title='پیشنهاد قیمت اجرا'></a></td>"; 
        print "<td class='no-print t2' $hidePropose>پیشنهاد قیمت لوله<a  target='_blank' href='appinvestigation/allapplicantrequestdetail2.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_5_'.$rowresult['operatorcoid'].'_'.$rowresult['ProducersID'].'_1901'.rand(10000,99999)."'>
									<img style = 'width: 25px;' src='img/mail_search.png' title='پیشنهاد قیمت لوله'></a></td>";                             
        $ID = $rowresult['ApplicantMasterID'].'_4_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].'_'.$rowresult['applicantstatesID']
            .'_'.$login_RolesID;
        
            $permitrolsid = array("1", "13","14","5","11","18","19","20","7","16",'17','22',"23");if (in_array($login_RolesID, $permitrolsid))
        print "<td class='no-print t2' >ریز گردش<a  target='_blank' href='appinvestigation/applicantstates_detail.php?uid=".rand(10000,99999).rand(10000,99999)
            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
            "'>
									<img style = 'width: 25px;' src='img/mail_search.png' title='ریز گردش'></a></td>";                             
        
           
        print "	</tr><tr>";
                                    
		 $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
		 	print "<td colspan='9' $hide $hideD>
  		    &nbsp <a target='_blank' href='appinvestigation/allapplicantstates.php?uid='>لیست طرحهای طراحی :</a>";
			 
            $permitrolsid = array("1", "13","5","6","11","18","19","20","7","16",'17',"23");if (in_array($login_RolesID, $permitrolsid))
            {
                print "ويرايش<a target='_blank' href='appinvestigation/applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterID'].'_1_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/file-edit-icon.png' title=' ويرايش '></a>
                خلاصه پروژه<a target='_blank' href='insert/sinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)."$rowresult[ApplicantMasterID]_$rowresult[ApplicantFName] $rowresult[ApplicantName]_$rowresult[shahrcityname]_$rowresult[DesignArea]_$rowresult[BankCode]_$rowresult[CostPriceListMasterIDd]_$rowresult[cityid]_0".rand(10000,99999)."'><img style = 'width: 25px;' src='img/comment.png' title=' خلاصه پروژه '></a>";    
            }
			 
            
			 
                  
            
            if ($login_RolesID==1)
                             {
                                    print $nbsp."برگشت به کارتابل<a 
                                    href='appinvestigation/allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$rowresult['ApplicantMasterID']."_0_0_0_0_0_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 25px;' src='img/next.png' title='برگشت به کارتابل'> </a>";
                                
                             }
                             
        $permitrolsid = array("1","5", "19","13","14"); if (in_array($login_RolesID, $permitrolsid))
					{
                            print $nbsp."تامین اعتبار<a 
                                   target='_blank' href='appinvestigation/applicant_tosandogh.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterID'].rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send.png' title=' نامه ارسال پرونده طرح به صندوق جهت تامین اعتبار '></a>";
                                    
                    }
                            
        $permitrolsid = array("1","5", "11", "12","19"); if (in_array($login_RolesID, $permitrolsid))
					{
                            print $nbsp."فرم شماره 10 تاییدیه کمیته فنی<a 
                                   target='_blank' href='appinvestigation/applicant_form10.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterID'].rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send.png' title='فرم شماره 10 تاییدیه کمیته فنی'></a>";
                                    
                    }  
                        
                   print "</tr><tr>";     
          $ID = $rowresult['ApplicantMasterIDop'].'_4_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].'_'.$rowresult['applicantstatesIDop'];
		 //$nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
         $nbsp='';	 
		 	print "<td colspan='9' $hide $hideP>
  		    &nbsp <a target='_blank' href='appinvestigation/allapplicantstatesop.php?uid='>لیست طرحهای اجرایی :</a>";
					$permitrolsid = array("16", "19","7","13","14");if (in_array($login_RolesID, $permitrolsid))
							{                               
                                 if (in_array($rowresult['applicantstatesID'], array("30","35","34","38")))                               
                                print $nbsp."آزادسازی<a target='_blank' href='appinvestigation/invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_1_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoid'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='img/Actions-document-export-icon.png' title='آزادسازی'></a>";
                             }
                            
			if ($login_RolesID!='16' && $login_RolesID!='7') 
                {
							$permitrolsid = array("1","5","11","13","14","18","20","23");if (in_array($login_RolesID, $permitrolsid))
                            print $nbsp."ويرايش<a target='_blank' href='appinvestigation/applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_3_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 25px;' src='img/file-edit-icon.png' title=' ويرايش '></a>
                            خلاصه پروژه<a target='_blank' href='insert/sinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)."$rowresult[ApplicantMasterIDop]_$rowresult[ApplicantFName] $rowresult[ApplicantName]_$rowresult[shahrcityname]_$rowresult[DesignArea]_$rowresult[BankCode]_$rowresult[CostPriceListMasterIDop]_$rowresult[cityid]_0".rand(10000,99999)."'><img style = 'width: 25px;' src='img/comment.png' title=' خلاصه پروژه '></a>
                            ";?>
							
							
							<?php 
							
	$sqltiming = "SELECT applicanttiming.ApplicantMasterID _value,applicanttiming.ApplicantMasterID _key FROM applicanttiming";
	$IDtiming = get_key_value_from_query_into_array($sqltiming);
                            print $nbsp." جدول زمانبندي <a  target='_blank' href='insert/applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_3'.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='img/table.png' title=' ثبت جدول زمانبندي '></a>"; 
                        
    						if (in_array($login_RolesID, $permitrolsid))
                            print $nbsp." تغییرات اجرا نسبت به طراحی <a  target='_blank' href='appinvestigation/opchangestodesign.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$rowresult['BankCode'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='img/accept_page.png' title=' تغییرات اجرا نسبت به طراحی '></a>";
				}	
		
        $permitrolsid = array("1","14", "17","10","5","8","13");if (in_array($login_RolesID, $permitrolsid)  && $rowresult['issurat']==1)
                    {
                           print $nbsp." صورتجلسه تحویل<a 
                                   target='_blank' href='appinvestigation/applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop']."_5".rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='img/folder_accept.png' title='صورتجلسه تحویل موقت'></a>
									&nbsp</td>";
                    }         
					
					
				print "</tr><tr>";
                
		 $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        	 
		 	print "<td colspan='9' $hide $hideP>
  		    &nbsp <a target='_blank' href='appinvestigation/aaapplicantfree.php?uid='>لیست آزادسازی :</a>";

                        echo $nbsp."نامه آزادسازی قسط اول<a  target='_blank' href='appinvestigation/applicant_one.php?uid=".
                        rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop'].rand(10000,99999).'1'.
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send1.png' title=' نامه آزادسازی قسط اول '></a>";
                        
						echo "&nbsp&nbsp دوم <a  target='_blank' href='appinvestigation/applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop'].rand(10000,99999).'2'.
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send2.png' title=' نامه آزادسازی قسط دوم '></a>";
                                    
						echo "&nbsp&nbsp سوم <a  target='_blank' href='appinvestigation/applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop'].rand(10000,99999).'3'.
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send3.png' title=' نامه آزادسازی قسط سوم '></a>";
                                    
						echo "&nbsp&nbsp چهارم<a  target='_blank' href='appinvestigation/applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$rowresult['ApplicantMasterIDop'].rand(10000,99999).'4'.
                                    "'><img style = 'width: 25px;' 
                                    src='img/mail_send4.png' title=' نامه آزادسازی قسط چهارم '></a>";

									
					 print  $nbsp."پیشنهاد آزادسازی<a target='_blank' href='appinvestigation/aaapplicantfreep.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_1_'.$rowresult["applicantstatesIDs"].'_'.
                                $rowresult['operatorcoid']."_".$rowresult['ApplicantMasterIDs'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='img/process.png' title=' پیشنهاد آزادسازی'></a>";
                            

                            $permitrolsid = array("1","18", "16","17","7","13","14");
                            if ( in_array($login_RolesID, $permitrolsid))
                             {
                                 print  $nbsp."ثبت آزادسازی<a target='_blank' href='appinvestigation/invoicemasterfree_list2.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$rowresult['ApplicantMasterIDop'].'_2_0_'.$rowresult['operatorcoid'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='img/Actions-document-export-icon.png' title='آزادسازی'></a>";
                            
                             }
					
									
			print "&nbsp</td></tr><tr>";



         $ID = $rowresult['ApplicantMasterIDs'].'_4_'.$rowresult['DesignercoID'].'_'.$rowresult['operatorcoID'].'_'.$rowresult['applicantstatesIDs'];
		 $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        	 
		 	print "<td colspan='9' $hide $hideP>
  		    &nbsp <a target='_blank' href='appinvestigation/allapplicantstatesoplist.php?uid='>لیست صورت وضعیتها :</a>";

                             $permitrolsid = array("16", "19","7","13","14");if (in_array($login_RolesID, $permitrolsid))
                             {
                             if ($rowresult['applicantstatestitle']=='تایید نهایی پیش فاکتور')                               
                                print $nbsp."آزادسازی<a target='_blank' href='appinvestigation/invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$rowresult['ApplicantMasterIDs'].'_1_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/Actions-document-export-icon.png' title='آزادسازی'></a>";
                             }
                             
							 if ($login_RolesID!='16' && $login_RolesID!='7') 
                             {
                            $permitrolsid = array("1", "13","5","11","13","14","18","20","23");if (in_array($login_RolesID, $permitrolsid))
                            print $nbsp."ويرايش<a target='_blank' href='appinvestigation/applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$rowresult['ApplicantMasterIDs'].'_3_'.$rowresult['DesignerCoID'].'_'.$rowresult['operatorcoID'].rand(10000,99999)."'><img style = 'width: 25px;' src='img/file-edit-icon.png' title=' ويرايش '></a>
                            خلاصه پروژه<a target='_blank' href='insert/sinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)."$rowresult[ApplicantMasterIDs]_$rowresult[ApplicantFName] $rowresult[ApplicantName]_$rowresult[shahrcityname]_$rowresult[DesignArea]_$rowresult[BankCode]_$rowresult[CostPriceListMasterIDs]_$rowresult[cityid]_0".rand(10000,99999)."'><img style = 'width: 25px;' src='img/comment.png' title=' خلاصه پروژه '></a>"; ?>
							
                           
                            <?php
                            print $nbsp."تغییرات اجرا<a  target='_blank' href='appinvestigation/opchangestodesign.php?uid=".rand(10000,99999)
							.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
							.rand(10000,99999).$rowresult['BankCode']."_1".rand(10000,99999)."'>
							<img style = 'width: 25px;' src='img/accept_page.png' title=' تغییرات اجرا'></a>";
					
 					        
							
							}
							
               print "&nbsp</td></tr>

				</table>
				</div>";

            
            } 
				
				
		


}
?>