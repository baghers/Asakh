<?php
/*
includes/functiong.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
تمام صفحات
*/
function sql_reports_applicantstatedate($login_CityId,$cond,$orderby) //تابع ایجاد پرس و جوی گزارش وضعیت طرح ها
{
  $sql="SELECT  applicantmasterall.ApplicantMasterID,creditsource.title creditsourcetitle,
applicantmasterall.ApplicantName,applicantmasterall.ApplicantFName,apperja.SaveDate ADateejra,
clerkwin.CPI,applicantmasterall.TMDate SaveDateejra,clerkwin.DVFS
,operatorapprequest.WinDate WinDateejra,DesignerCoIDnazer.Title DesignerCoIDnazerTitle
,applicantmasterall.belaavaz,applicantmasterop.LastTotal,(applicantsavedate1.firstsave) firstsave,clerkwin.ClerkID ClerkIDwin
,applicantmasterop.numfield2 numfield2op,applicantmasteroplist.numfield2 numfield2oplist
,(applicantsavedate6.taidpishF) taidpishF,applicantmasterop.operatorcoid,applicantmasterall.DesignArea,
operatorco.title operatorcotitle 
,applicantstates.title applicantstatestitlem
,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
,designsystemgroups.title DesignSystemGroupstitle
,case case ifnull(applicantfree.Price,0)>=ifnull(applicantmasteroplist.LastTotal,0) and  applicantmasteroplist.applicantstatesID=45
            when 1 then 1 else 0 end
      when 1 then 1 else 
case applicantmasteroplist.applicantstatesID when 45 then 1 else 
case applicantmasterop.applicantstatesID when 35 then 1 else 0 end
end
end performed

FROM applicantmaster applicantmasterall
inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmasterall.ApplicantMasterID 


left outer join applicantmaster applicantmasterop on applicantmasterop.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
left outer join applicantstates on applicantstates.applicantstatesID=applicantmasterop.applicantstatesID
left outer join applicantmaster applicantmasteroplist on applicantmasteroplist.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDsurat
left outer join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and operatorapprequest.state=1
left outer join clerk clerkwin on clerkwin.ClerkID=operatorapprequest.ClerkID

left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid

left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where producersid<>-2  group by applicantmasterid) 
		applicantfree on applicantfree.applicantmasterid =applicantmasterop.applicantmasterid
        
left outer join (select ApplicantMasterID,SaveDate firstsave from appchangestate where applicantstatesID=23 group by ApplicantMasterID) applicantsavedate1 on applicantsavedate1.ApplicantMasterID =applicantmasterop.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate taidpishF from appchangestate where applicantstatesID=30 group by ApplicantMasterID) applicantsavedate6 on applicantsavedate6.ApplicantMasterID =applicantmasterop.applicantmasterid


left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterall.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

left outer join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmasterall.DesignSystemGroupsid
left outer join designerco DesignerCoIDnazer on DesignerCoIDnazer.DesignerCoID=
case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end

left outer join (select max(SaveDate) SaveDate,tID ApplicantMasterID from tbl_log 
where tName='applicantmaster' and colname='proposestate' and oldval=1 and newval='2' group by tID) apperja on apperja.ApplicantMasterID=applicantmasterall.ApplicantMasterID


where substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
and  ifnull(applicantmasterall.private,0)=0 
 and ifnull(applicantmasterdetail.prjtypeid,0)=0
 and applicantmasterall.applicantstatesID in (22,37,24) and ifnull(applicantmasterop.applicantstatesID,0) not in (34)
  $cond $orderby";
 return $sql;
}
function sql_reports_applicantstatedateDesign($cond)//تابع ایجاد پرس و جوی گزارش وضعیت طرح های طراحی
{
    $sql=" SELECT applicantmaster.*,applicantstates.title applicantstatestitle,designerco.DesignerCoID, 
designerco.title DesignerCotitle,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
,(applicantsavedate1.firstsave) firstsave,(applicantsavedate2.sendtobazbin) sendtobazbin,(applicantsavedate3.bazbintomoshaver) bazbintomoshaver,
(applicantsavedate4.lastsendtoBazbin) lastsendtoBazbin,(applicantsavedate5.sendtabokhak) sendtabokhak,(applicantsavedate6.abokhaktosandogh) abokhaktosandogh
,(applicantsavedate7.lasttaid) lasttaid
,designsystemgroups.title DesignSystemGroupstitle,applicantmaster.TMDate lastSaveDate
FROM applicantmaster 

inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID

left outer join (select ApplicantMasterID,max(SaveDate) firstsave from appchangestate where applicantstatesID=23 group by ApplicantMasterID) applicantsavedate1 on applicantsavedate1.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,min(SaveDate) sendtobazbin from appchangestate where applicantstatesID=5 group by ApplicantMasterID) applicantsavedate2 on applicantsavedate2.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,min(SaveDate) bazbintomoshaver from appchangestate where applicantstatesID=4 group by ApplicantMasterID) applicantsavedate3 on applicantsavedate3.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,max(SaveDate) lastsendtoBazbin from appchangestate where applicantstatesID=5 group by ApplicantMasterID) applicantsavedate4 on applicantsavedate4.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,max(SaveDate) sendtabokhak from appchangestate where applicantstatesID=8 group by ApplicantMasterID) applicantsavedate5 on applicantsavedate5.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,max(SaveDate) abokhaktosandogh from appchangestate where applicantstatesID=12 group by ApplicantMasterID) applicantsavedate6 on applicantsavedate6.ApplicantMasterID =applicantmaster.applicantmasterid
left outer join (select ApplicantMasterID,max(SaveDate) lasttaid from appchangestate where applicantstatesID=22 group by ApplicantMasterID) applicantsavedate7 on applicantsavedate7.ApplicantMasterID =applicantmaster.applicantmasterid

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

inner join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
where ifnull(applicantmaster.private,0)=0  $cond 
order by applicantstatestitle COLLATE utf8_persian_ci,applicantmaster.TMDate";;
    return $sql;
}
//تابع محاسبه تاخیرهای مجری و ناظر ها در انجام بررسی های مورد نیاز
function calculatedv($numfield2op,$numfield2oplist,$ADateejra,$SaveDateejra,$WinDateejra,$firstsave,$taidpishF
                    ,$deadlineerj,$deadlineselectop,$deadlinefirstsave,$deadlineapprove,$deadlinetempdel,$deadlinepermanentdel)
{
    $permanentfree='';
    $temporarydel='';
    $numfield2array='';
    
   	$numfield2array = explode('_',$numfield2op);
    if ($numfield2array[1]!='')
    $temporarydel=jalali_to_gregorian(compelete_date($numfield2array[1]));
                        
   	$numfield2array = explode('_',$numfield2oplist);
    if ($numfield2array[1]!='')
    $permanentfree=jalali_to_gregorian(compelete_date($numfield2array[1]));
                        
    $dv1='';
    $dv2='';
    $dv3='';
    $dv4='';
    $dv5='';
    $dv6='';
    if ($firstsave=='') $firstsave=date('Y-m-d');
    if ($WinDateejra=='') $WinDateejra=date('Y-m-d');
    if ($ADateejra!="") $dv1=round((strtotime( $ADateejra)-strtotime($SaveDateejra))/86400)-$deadlineerj;
    if ($ADateejra!="") $dv2=round((strtotime( $WinDateejra)-strtotime($ADateejra))/86400)-$deadlineselectop;
    $dv3=round((strtotime( $firstsave)-strtotime($WinDateejra))/86400)-$deadlinefirstsave;
                        
    if ($taidpishF>0) $dv4=round((strtotime( $taidpishF)-strtotime($firstsave))/86400)-$deadlineapprove;
    else $dv4=round((strtotime( date('Y-m-d'))-strtotime($firstsave))/86400)-$deadlineapprove;
    
    if ($taidpishF>0)
    {
        if ($temporarydel>0) $dv5=round((strtotime( $temporarydel)-strtotime($taidpishF))/86400)-$deadlinetempdel;
        else $dv5=round((strtotime( date('Y-m-d'))-strtotime($taidpishF))/86400)-$deadlinetempdel;
                            
    }
    else $dv5=round((strtotime( date('Y-m-d'))-strtotime($firstsave))/86400)-$deadlinetempdel-$deadlineapprove;
    
    if ($temporarydel>0)
    {
        if ($permanentfree>0) $dv6=round((strtotime( $permanentfree)-strtotime($temporarydel))/86400)-$deadlinepermanentdel;
        else $dv6=round((strtotime( date('Y-m-d'))-strtotime($temporarydel))/86400)-$deadlinepermanentdel;
                            
    }
    else if ($taidpishF>0)
        $dv6=round((strtotime( date('Y-m-d'))-strtotime($taidpishF))/86400)-$deadlinepermanentdel-$deadlinetempdel;
        else 
            $dv6=round((strtotime( date('Y-m-d'))-strtotime($firstsave))/86400)-$deadlinepermanentdel-$deadlinetempdel-$deadlineapprove;
    
    if ($dv1<=0) $dv1='';
    if ($dv2<=0) $dv2='';
    if ($dv3<=0) $dv3='';
    if ($dv4<=0) $dv4='';
    if ($dv5<=0) $dv5='';
    if ($dv6<=0) $dv6='';
    return $dv1."_".$dv2."_".$dv3."_".$dv4."_".$dv5."_".$dv6."_".$temporarydel."_".$permanentfree;
}


function checkMelliCode($meli_code,$personality=0)//بررسی صحت کد/ شناسه ملی
{
    if (strlen($meli_code) == 11)
        $personality=1;
    
    if ($personality==0)
    {
        if (strlen($meli_code) == 10)
        {
            if($meli_code=='1111111111' ||
            $meli_code=='0000000000' ||
            $meli_code=='2222222222' ||
            $meli_code=='3333333333' ||
            $meli_code=='4444444444' ||
            $meli_code=='5555555555' ||
            $meli_code=='6666666666' ||
            $meli_code=='7777777777' ||
            $meli_code=='8888888888' ||
            $meli_code=='9999999999' )
            {
                return false;
            }
            $c = intval(substr($meli_code,9,1));
            $n = intval(substr($meli_code,0,1))*10 +
            intval(substr($meli_code,1,1))*9 +
            intval(substr($meli_code,2,1))*8 +
            intval(substr($meli_code,3,1))*7 +
            intval(substr($meli_code,4,1))*6 +
            intval(substr($meli_code,5,1))*5 +
            intval(substr($meli_code,6,1))*4 +
            intval(substr($meli_code,7,1))*3 +
            intval(substr($meli_code,8,1))*2;
            $r = $n - intval ($n/11)*11;
                if (($r == 0 && $r == $c) || ($r == 1 && $c == 1) || ($r > 1 && $c == 11 - $r))
                {
                    return true;
                }else
                {
                    return false;
                }
        }else
        {
            return false;
        }    
    }//comany
    else
    {
        if (strlen($meli_code) == 11)
        {
            if($meli_code=='11111111111' ||
            $meli_code=='00000000000' ||
            $meli_code=='22222222222' ||
            $meli_code=='33333333333' ||
            $meli_code=='44444444444' ||
            $meli_code=='55555555555' ||
            $meli_code=='66666666666' ||
            $meli_code=='77777777777' ||
            $meli_code=='88888888888' ||
            $meli_code=='99999999999' )
            {
                return false;
            }
            
            $c = intval(substr($meli_code,10,1));
            $n = intval(substr($meli_code,0,1))*29 +
            intval(substr($meli_code,1,1))*27 +
            intval(substr($meli_code,2,1))*23 +
            intval(substr($meli_code,3,1))*19 +
            intval(substr($meli_code,4,1))*17 +
            intval(substr($meli_code,5,1))*29 +
            intval(substr($meli_code,6,1))*27 +
            intval(substr($meli_code,7,1))*23 +
            intval(substr($meli_code,8,1))*19 +
            intval(substr($meli_code,9,1))*247;
            $n=$n+460;
            $r = $n - intval ($n/11)*11;
            if ($r==10) $r=0;
            if ($r == $c)
                return true;
            else
                return false;
        }
        else return false;
    }
    
    
}


?>
