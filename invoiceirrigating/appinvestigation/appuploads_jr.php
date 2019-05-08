<?php
/*

//appinvestigation/appuploads_jr.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/appuploads.php
 -
*/
    include('../includes/connect.php');
    include('../includes/functions.php');
    $target=$_server_fileserver;
    $ApplicantMasterIDd=$_POST["ApplicantMasterIDd"];
    $ApplicantMasterIDop=$_POST["ApplicantMasterIDop"];
    $ApplicantMasterIDoplist=$_POST["ApplicantMasterIDoplist"];
    $rowcount=5;
    //////////////////////////////////////////////////////////گزارش های طرح
    $linearrayapplicantreportsidd = explode('_',$_POST["applicantreportsidd"]);
    $linearrayapplicantreportsidop = explode('_',$_POST["applicantreportsidop"]);
    $linearrayapplicantreportsidoplist = explode('_',$_POST["applicantreportsidoplist"]);
    $strapplicantreportsidd="";
    $strapplicantreportsidop="";
    $strapplicantreportsidoplist="";
    $key='applicantreports';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    $cntd=0;
    $cntop=0;
    $cntoplist=0;
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0 && in_array($ID,$linearrayapplicantreportsidd)) 
        {     
            $cntd++;
            $strapplicantreportsidd.= "<a target='blank' href='$target/upfolder/$key/$file' >گزارش های دستگاه نظارت
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";   
            if(($cntd%$rowcount)==0)
                $strapplicantreportsidd.= "</td></tr><tr><td ><span class='f14_fontcb' >";            
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0 && in_array($ID,$linearrayapplicantreportsidop)) 
        {   
            $cntop++;
            $strapplicantreportsidop.= "<a target='blank' href='$target/upfolder/$key/$file' >گزارش های دستگاه نظارت
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";      
            if(($cntop%$rowcount)==0)
                $strapplicantreportsidop.= "</td></tr><tr><td ><span class='f14_fontcb' >";         
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0 && in_array($ID,$linearrayapplicantreportsidoplist)) 
        {     
            $cntoplist++;
            $strapplicantreportsidoplist.= "<a target='blank' href='$target/upfolder/$key/$file' >گزارش های دستگاه نظارت
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";   
            if(($cntoplist%$rowcount)==0)
                $strapplicantreportsidoplist.= "</td></tr><tr><td ><span class='f14_fontcb' >";            
        }
    } 
    ////////////////////////////////////////////////////////////قرارداد/////////////////////////
    $contractd="";
    $contractop="";
    $contractoplist="";
    $key='contract';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);

    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd) 
        {                
            $contractd.= "<a target='blank' href='$target/upfolder/$key/$file' >
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'>
            <br>قرارداد
            </a>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop) 
        {                
            $contractop.= "<a target='blank' href='$target/upfolder/$key/$file' >تحویل موقت
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDoplist) 
        {                
            $contractoplist.= "<a target='blank' href='$target/upfolder/$key/$file' >تحویل دائم
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";               
        }
    } 



    //////////////////////////////////////////////////////////آزادسازی
    $linearrayapplicantfreedetailop = explode('_',$_POST["applicantfreedetailop"]);
    $strapplicantfreedetailop="";
    $key='free';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0 && in_array($ID,$linearrayapplicantfreedetailop)) 
        {                
            $strapplicantfreedetailop.= "<a target='blank' href='$target/upfolder/$key/$file' >نامه آزادسازی
            <img style = 'width: 25px;' src='$target/upfolder/$key/$file'></a>";               
        }
    } 
    /////////////////////////////////////////////////منابع آبی
    $linearrayapplicantwsourced = explode('_',$_POST["applicantwsourced"]);
    $strapplicantwsourced="";
    $key='parvane';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    $cnt=0;
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0 && in_array($ID,$linearrayapplicantwsourced)) 
        {         
            $cnt++;
            $strapplicantwsourced.= "<a target='blank' href='$target/upfolder/$key/$file' >نامه پروانه بهره برداری منبع آبی
            <img style = 'width: 25px;' src='$target/upfolder/$key/$file'></a>"; 
            
            if(($cnt%$rowcount)==0)
                $strapplicantwsourced.= "</td></tr><tr><td ><span class='f14_fontcb' >";               
        }
    }             
            
            
            
    //////////////////////////////////////////////////////////اسکن پیش فاکتور
    $linearrayinvoiced = explode('_',$_POST["invoiced"]);
    $linearrayinvoiceop = explode('_',$_POST["invoiceop"]);
    $linearrayinvoiceoplist = explode('_',$_POST["invoiceoplist"]);
    $strinvoiced="";
    $strinvoiceop="";
    $strinvoiceoplist="";
    $key='invoice';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    $cntd=0;
    $cntop=0;
    $cntoplist=0;
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0) 
        {                
            foreach($linearrayinvoiced as $k=>$v)
            {
                
                if ($ID==$v)
                {
                    $cntd++;
                    $strinvoiced.= "<a target='blank' href='$target/upfolder/$key/$file' >".$linearrayinvoiced[$k+1]."
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>"; 
                    if(($cntd%$rowcount)==0)
                    $strinvoiced.= "</td></tr><tr><td ><span class='f14_fontcb' >";
                }
            }             
            foreach($linearrayinvoiceop as $k=>$v)
            {
                
                if ($ID==$v)
                {
                    $cntop++;
                    $strinvoiceop.= "<a target='blank' href='$target/upfolder/$key/$file' >".$linearrayinvoiceop[$k+1]."
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    if(($cntop%$rowcount)==0)
                    $strinvoiceop.= "</td></tr><tr><td ><span class='f14_fontcb' >";
                }
                     
            }            
            foreach($linearrayinvoiceoplist as $k=>$v)
            {
                
                if ($ID==$v)
                {
                    $cntoplist++;
                    $strinvoiceoplist.= "<a target='blank' href='$target/upfolder/$key/$file' >".$linearrayinvoiceoplist[$k+1]."
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    if(($cntoplist%$rowcount)==0)
                    $strinvoiceoplist.= "</td></tr><tr><td ><span class='f14_fontcb' >";
                }
                     
            }
                          
        }
    } 



    //////////////////////////////////////////////////////////پیشنهاد قیمت اجرا
    $linearrayoperatorapprequest = explode('_',$_POST["operatorapprequest"]);
    $stroperatorapprequest="";
    $key='propose';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    $cnt=0;
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0) 
        {                
            foreach($linearrayoperatorapprequest as $k=>$v)
            {
                if ($ID==$v)
                {
                    $cnt=$cnt+1;
                    if (strstr($linearrayoperatorapprequest[$k+1],'منتخب'))
                    $stroperatorapprequest.= "<a target='blank' href='$target/upfolder/$key/$file' > ".$linearrayoperatorapprequest[$k+1]." 
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    else
                    
                    $stroperatorapprequest.= "<a target='blank' href='$target/upfolder/$key/$file' > ".$linearrayoperatorapprequest[$k+1]." 
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    if(($cnt%$rowcount)==0)
                    $stroperatorapprequest.= "</td></tr><tr><td ><span class='f14_fontcb' >";
                } 
            }                            
        }
    } 
    //////////////////////////////////////////////////////////پیشنهاد قیمت لوله
    $linearrayproducerapprequest = explode('_',$_POST["producerapprequest"]);
    $strproducerapprequest="";
    $key='proposep';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    $cnt=0;
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        
        if ($file != "." && $file != ".." && $file != "index.html" && $ID>0) 
        {                
            foreach($linearrayproducerapprequest as $k=>$v)
            {
                if ($ID==$v)
                {
                    $cnt=$cnt+1;
                    if (strstr($linearrayproducerapprequest[$k+1],'منتخب'))
                    $strproducerapprequest.= "<a target='blank' href='$target/upfolder/$key/$file' > ".$linearrayproducerapprequest[$k+1]." 
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    else
                    
                    $strproducerapprequest.= "<a target='blank' href='$target/upfolder/$key/$file' > ".$linearrayproducerapprequest[$k+1]." 
                    <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";
                    if(($cnt%$rowcount)==0)
                    $strproducerapprequest.= "</td></tr><tr><td ><span class='f14_fontcb' >";
                    
                }
            }                            
        }
    }   
    ////////////////////////////////////////////////////////////نامه ارسال به صندوق/////////////////////////
    $sandughd="";
    $key='sandugh';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $linearrayp = explode('p',$linearray[0]);
        $ID=$linearrayp[1];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd) 
        {               
            $sandughd.= "<a target='blank' href='$target/upfolder/$key/$file' >نامه ارسال به صندوق جهت تامین اعتبار
            <img style = 'width: 50px;' src='$target/upfolder/$key/$file'></a>";               
        }
    } 

    ////////////////////////////////////////////////////////////دفترچه و نقشه و مدارک طرح/////////////////////////
    $appfilemapd="";
    $appfilemapop="";
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/";
    $handler = opendir($directory);
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        $No=$linearray[1];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==1) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$file' >فایل نقشه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 50px;' src='$target/upfolder/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==1) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$file' >فایل نقشه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==2) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$file' >فایل دفترچه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==2) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$file' >فایل دفترچه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==3) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$file' >فایل محاسبات&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==3) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$file' >فایل محاسبات&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$file'>";               
        }
    } 
    
    $key='madarek';
    $directory = $_SERVER['DOCUMENT_ROOT']."/upfolder/$key/";
    $handler = opendir($directory);
    while ($file = readdir($handler)) 
    {
        $linearray = explode('_',$file);
        $ID=$linearray[0];
        $No=$linearray[1];
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==4) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل منابع طبیعی&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==4) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل منابع طبیعی&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==5) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل مالکیت زمین</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==5) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل مالکیت زمین</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==6) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل شناسنامه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==6) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل شناسنامه&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDd && $No==7) 
        {               
            $appfilemapd.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل کارت ملی&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
        if ($file != "." && $file != ".." && $file != "index.html" && $ID==$ApplicantMasterIDop && $No==7) 
        {               
            $appfilemapop.= "<a target='blank' href='$target/upfolder/$key/$file' >فایل کارت ملی&nbsp;&nbsp;&nbsp;&nbsp;</a><img style = 'width: 35px;' src='$target/upfolder/$key/$file'>";               
        }
    }
    
              
    $temp_array = array('strapplicantreportsidd' => $strapplicantreportsidd,'strapplicantreportsidop' => $strapplicantreportsidop
                        ,'strapplicantreportsidoplist' => $strapplicantreportsidoplist,'strapplicantfreedetailop' => $strapplicantfreedetailop
                        ,'contractd' => $contractd,'contractop' => $contractop,'contractoplist' => $contractoplist
                        ,'strinvoiced' => $strinvoiced,'strinvoiceop' => $strinvoiceop,'strinvoiceoplist' => $strinvoiceoplist
                        ,'strapplicantwsourced' => $strapplicantwsourced,'stroperatorapprequest' => $stroperatorapprequest
                        ,'strproducerapprequest' => $strproducerapprequest,'sandughd' => $sandughd
                        ,'appfilemapd' => $appfilemapd,'appfilemapop' => $appfilemapop
                        
                        
                        
                        
                        );
                        
                         echo json_encode($temp_array);
		exit();
			
			
		
	

?>



