<?php

require('ssvault_pagecontent.php');
require('ssvault_javascriptr.php');
require('ssvault_stylesheets.php');
require('ssvault_defaultelements.php');


class pagebuilder { 

  public $statusCode = 404;		
  public $pagetitle = "";
  public $pagetitleicon = "";
  public $headr = ""; 
  public $stylr = "";
  public $scriptrs = "";
  public $bodycontent = "";
  public $pagecontrols = "";
  public $acctdisplay = "";
  public $menucontent = "";
  public $modalrs = "";
  public $modalrdialogs = "";
  //PAGE NAME MUST BE REGISTERED IN THIS ARRAY - COULD DO A METHOD SEARCH - BUT I LIKE THE CONTROL OF NOT ALLOWING A PAGE THAT IS NOT READY FOR DISPL
  private $registeredPages = array('login','root','pendingpathologyreportlisting','donorlookup', 'consentwatch');  
  //THE SECURITY EXCPETIONS ARE THOSE PAGES THAT DON'T REQUIRE USER RIGHTS TO ACCESS
  private $securityExceptions = array('login');

function __construct() { 		  
  $args = func_get_args();   
   if (trim($args[0]) === "") {	  		
   } else {
     session_start();
     if ($_SESSION['loggedin'] !== "true" && $args[0] !== "login") {
         $this->statusCode = 403;
     } else {           
       //$usrmetrics = $args[2];  //$usrmetric->username - Class from the index file defining the user     
        
       if (in_array($args[0], $this->registeredPages)) {
         $pageElements = self::getPageElements( $args[0], $args[1], $args[2]);	  
         $this->statusCode = $pageElements['statuscode'];
         $this->pagetitle = $pageElements['tabtitle'];
         $this->pagetitleicon = $pageElements['tabicon'];
         $this->headr = $pageElements['headr'];
         $this->stylr = $pageElements['styleline'];
         $this->scriptrs = $pageElements['scripts'];
         $this->bodycontent = $pageElements['bodycontent'];
         $this->pagecontrols = $pageElements['controlbars'];
         $this->menucontent = $pageElements['menu'];
         $this->modalrs = $pageElements['modalscreen'];
       } else { 
         $this->statusCode = 404;

       }     
     }     
   }   
}

function getPageElements($whichpage, $rqststr, $usrmetrics) { 
  session_start();  
  $ss = new stylesheets(); 
  $js = new javascriptr();
  $oe = new defaultpageelements();
  $pc = new pagecontent();
  $elArr = array();
  
  //HEADER - TAB - ICON ---------------------------------------------
  $elArr['tabtitle']     =   (method_exists($oe,'pagetabs') ? $oe->pagetabs($whichpage) : "");
  $elArr['tabicon']      =   (method_exists($oe,'faviconBldr') ? $oe->faviconBldr($whichpage) : "");
  $elArr['headr']        =   (method_exists($pc,'generateHeader') ? $pc->generateHeader($whichpage) : "");
  //STYLESHEETS ---------------------------------------------------
  if ($whichpage !== "login") {
    $elArr['styleline']    =   (method_exists($ss,'globalstyles') ? $ss->globalstyles($mobileInd) : "");
  }
  $elArr['styleline']   .=   (method_exists($ss, $whichpage) ? $ss->$whichpage($rqststr) : " (STYLESHEET MISSING {$whichpage}) ");
  //JAVASCRIPT COMPONENTS -------------------------------------------
  if ($whichpage !== "login") {  
    $elArr['scripts']      =   (method_exists($js,'globalscripts') ? $js->globalscripts( "", "") : "");
  }
  $elArr['scripts']     .=   (method_exists($js,$whichpage) ? $js->$whichpage($rqststr) : "");

  ////CONTROL BARS GET BUILT HERE --------------------------------------------------------------   
  if ($whichpage !== "login") {  
    $elArr['controlbars']  =   (method_exists($oe,'controlbars') ? $oe->controlbars($whichpage, $usrmetrics ) : "");     
    $elArr['modalscreen']  =   (method_exists($oe,'modalbackbuilder') ? $oe->modalbackbuilder($whichpage) : "");
  }
  //PAGE CONTENT ELEMENTS  ------------------------------------
  //MAKE SURE USER IS ALLOWED ACCESS TO THE PAGE 
  $allowPage = 0;
  if (in_array($whichpage, $this->securityExceptions)) {
    $allowPage = 1;
  } else {     
    $allowPage = 1;
    //TODO:  THIS IS NOT ALLOWED ^^^^ CORRECT IT - THIS IS FOR TESTING
//      foreach ($usrmetrics->allowedmodules as $modval) { 
//          $allowPage =  ($whichpage === str_replace("-","",$modval[2])) ? 1 : $allowPage; 
//          foreach ($modval[3] as $allowPageLst) {
//              $allowPage = ($whichpage === str_replace("-","",$allowPageLst['pagesource'])) ? 1 : $allowPage; 
//          }
//      }
  } 
// 
 if ($allowPage === 1) { 
    $elArr['bodycontent'] = (method_exists($pc,$whichpage) ? $pc->$whichpage($rqststr, $usrmetrics) : "");   
 } else { 
   $elArr['bodycontent'] =  "<h1>USER NOT ALLOWED ACCESS TO THIS MODULE PAGE ({$whichpage})";
 }
  //END PAGE ELEMENTS ---------------------------


//RETURN STATUS - GOOD ---------------------------------------------------------------
  $elArr['statuscode'] = 200;
  return $elArr;
}


}




