<?php 

/* DONORVAULT-SCIENCESERVER MAIN INDEX */

/*
 * REQUIRED SERVER DIRECTORY STRUCTURE
 * /srv/chtneastapp/devvault = applicationTree - Can be changed if application files are moved
 *    +----accessfiles (Public/private key hold)
 *    +----appsupport (files/functions that do things to support all application frames) 
 *    +----frame (build/application files)
 *    +----dataconn (directory for data connection strings - Only to be used by PHP files under the applicationTree)
 *    +----tmp (application generated temporary files)
 *    +----publicobj (physical objects to pull 
 * 
 */

//START SESSSION FOR ALL TRACKING 
session_start(); 
//DEFINE APPLICATION PATH PARAMETERS
define("uriPath","devvault.chtneast.org");
define("scienceserverTagVersion","v7.0.5dv");
define("ownerTree","https://www.chtneast.org");
define("treeTop","https://devvault.chtneast.org");
define("dataTree","https://devvault.chtneast.org/data-services");
define("dataTreeSS","https://dev.chtneast.org/data-services");
define("applicationTree","/srv/chtneastapp/devvault/frame");
define("genAppFiles","/srv/chtneastapp/devvault");
define("serverkeys","/srv/chtneastapp/devvault/dataconn");
define("phiserver","https://devvault.chtneast.org");
//MODULUS HAS BEEN CHANGED TO DEV.CHTNEAST.ORG
define("eModulus","C7D2CD63A61A810F7A220477B584415CABCF740E4FA567D0B606488D3D5C30BAE359CA3EAA45348A4DC28E8CA6E5BCEC3C37A429AB3145D70100EE3BB494B60DA522CA4762FC2519EEF6FFEE30484FB0EC537C3A88A8B2E8571AA2FC35ABBB701BA82B3CD0B2942010DECF20083A420395EF4D40E964FA447C9D5BED0E91FC35F12748BB0715572B74C01C791675AF024E961548CE4AA7F7D15610D4468C9AC961E7D6D88A6B0A61D2AD183A9DFE2E542A50C1C5E593B40EC62F8C16970017C68D2044004F608E101CD30B69310A5EE550681AB411802806409D04F2BBB3C49B1483C9B9E977FCEBA6F4C8A3CB5F53AE734FC293871DCE95F40AD7B9774F4DD3");
define("eExponent","10001");

//Include functions file
require(genAppFiles . "/appsupport/generalfunctions.php");
//require(genAppFiles . "/extlibs/detectmobilelibrary.php");
require(serverkeys . "/serverid.zck");
define("serverIdent",$serverid);
define("servertrupw", $serverpw);
define("serverpw", cryptservice($serverpw) );

//DEFINE THE REQUEST PARAMETERS
$requesterIP = $_SERVER['REMOTE_ADDR']; 
$method = $_SERVER['REQUEST_METHOD'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$host = $_SERVER['HTTP_HOST'];
$https = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$originalRequest = $_SERVER['REQUEST_URI'];
$request = explode("/",str_replace("-","", strtolower($_SERVER['REQUEST_URI']))); 

///DETECT PLATFORM ON WHICH THE ACCESSOR IS VIEWING SITE
//$detect = new Mobile_Detect(); 
//$mobilePlatform = false; 
//$mobilePrefix = "w";
//if ($detect->isMobile()) { 
//    $mobilePlatform = true; 
//    $mobilePrefix = "m";
//    echo "<h1>SCIENCESERVER IS NOT PRESENTLY FORMATTED FOR MOBILE DEVICES.  USE DESKTOP BROWSERS ONLY";
//    exit();    
//}

switch ( $method ) { 

   case 'GET':

     require(applicationTree . "/bldvault.php");  
     if (!$_SESSION['loggedin'] || $_SESSION['loggedin'] !== "true")  { 
         $obj = "login";
         $rqst = explode("/",$originalRequest);
         if ( trim($rqst[1]) === "" ) { 
             echo "ERROR:  MALFORMED INDENTIFICATION";
             exit(); 
         }
     } else { 
       //CHECK USER HERE DON'T GO ON IF USER IS NOT ALLOWED
     }
     $pageBld = new pagebuilder($obj, $rqst, '');
     if ((int)$pageBld->statusCode <> 200) { 
       //PAGE NOT FOUND            
       http_response_code($pageBld->statusCode);     
       $rt = <<<RTNTHIS
<!DOCTYPE html>
<html>
<head>
<title>PAGE NOT FOUND</title>
</head>
<body><h1>Requested Page ({$obj}) @ CHTN Eastern Division - Not Found!</h1>
</body></html>
RTNTHIS;
//PAGE NOT FOUND END
     } else { 
      //PAGE FOUND AND DISPLAY HERE
      http_response_code($pageBld->statusCode);
      $pgIcon = (trim($pageBld->pagetitleicon) !== "") ? $pageBld->pagetitleicon : "";
      $pgHead = (trim($pageBld->headr) !== "") ? $pageBld->headr : "";
      $pgTitle = (trim($pageBld->pagetitle) !== "") ? "<title>" . $pageBld->pagetitle . "</title>" : "<title>CHTN Eastern</title>";
      $pgStyle = (trim($pageBld->stylr) !== "") ? "<style>" . $pageBld->stylr . "\n</style>" :  "";
      $pgScriptr = (trim($pageBld->scriptrs) !== "") ? "<script lang=javascript>" . $pageBld->scriptrs . "</script>" : "";
      //$pgControls = $pageBld->pagecontrols;
      $pgBody = $pageBld->bodycontent;
      //$pgMenu = $pageBld->menucontent;
      $pgModal = $pageBld->modalrs;
    $rt = <<<RTNTHIS
<!DOCTYPE html>
<html>
<head>
{$pgIcon}            
{$pgHead}
{$pgTitle}
{$pgStyle}
{$pgScriptr}
</head>
<body>
{$pgControls}
{$userAccount}
{$pgBody}
{$pgMenu}
{$pgModal}
{$pgDialogs}    
</body>
</html>
RTNTHIS;
//PAGE FOUND AND DISPLAY HERE END
        }
  echo $rt;
   break; 
   case 'POST':


   break;
   default: 
      echo "ONLY GET/POST METHODs ARE ALLOWED AT THIS END POINT.  STOP HACKING!"; 
}


