<?php 

/* DONORVAULT-SCIENCESERVER MAIN INDEX */
session_start();

define("scienceserverTagVersion","v7.0.5invty");
define("serverkeys", "/dataconn");
define("treeTop","https://devvault.chtneast.org");
define("dataTreeSS","https://dev.chtneast.org/data-services");

define("eModulus","C7D2CD63A61A810F7A220477B584415CABCF740E4FA567D0B606488D3D5C30BAE359CA3EAA45348A4DC28E8CA6E5BCEC3C37A429AB3145D70100EE3BB494B60DA522CA4762FC2519EEF6FFEE30484FB0EC537C3A88A8B2E8571AA2FC35ABBB701BA82B3CD0B2942010DECF20083A420395EF4D40E964FA447C9D5BED0E91FC35F12748BB0715572B74C01C791675AF024E961548CE4AA7F7D15610D4468C9AC961E7D6D88A6B0A61D2AD183A9DFE2E542A50C1C5E593B40EC62F8C16970017C68D2044004F608E101CD30B69310A5EE550681AB411802806409D04F2BBB3C49B1483C9B9E977FCEBA6F4C8A3CB5F53AE734FC293871DCE95F40AD7B9774F4DD3");
define("eExponent","10001");

//DEFINE THE REQUEST PARAMETERS
$requesterIP = $_SERVER['REMOTE_ADDR']; 
$method = $_SERVER['REQUEST_METHOD'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$host = $_SERVER['HTTP_HOST'];
$https = ($_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$originalRequest = str_replace("-","", strtolower($_SERVER['REQUEST_URI']));
$request = explode("/",str_replace("-","", strtolower($_SERVER['REQUEST_URI']))); 

require( genAppFiles . '/dataconn/serverid.zck');
define("serverIdent",$serverid);
define("servertrupw", $serverpw);
define("serverpw", cryptservice($serverpw) );

switch ( $method ) {

case 'GET':    
  if ( !isset($_SESSION['loggedid']) || trim($_SESSION['loggedid']) === "" ) { 
    //LOG IN
    $obj = "login";
    $pageBld = new pagebuilder($obj, $originalRequest, "");  
    http_response_code($pageBld->statusCode);
    if ((int)$pageBld->statusCode <> 200) {
      //LOGIN PAGE NOT FOUND!
      $rt = <<<RTNTHIS
<!DOCTYPE html><html><head><title>PAGE NOT FOUND</title></head><body><h1>Requested Page ({$obj}) @ CHTN Eastern Division - Not Found!</h1></body></html>
RTNTHIS;
    } else { 
      //BUILD LOGIN PAGE  
      $pgIcon = (trim($pageBld->pagetitleicon) !== "") ? $pageBld->pagetitleicon : "";
      $pgHead = (trim($pageBld->headr) !== "") ? $pageBld->headr : "";
      $pgTitle = (trim($pageBld->pagetitle) !== "") ? "<title>" . $pageBld->pagetitle . "</title>" : "<title>CHTN Eastern</title>";
      $pgStyle = (trim($pageBld->stylr) !== "") ? "<style>" . $pageBld->stylr . "\n</style>" :  "";
      $pgScriptr = (trim($pageBld->scriptrs) !== "") ? "<script lang=javascript>" . $pageBld->scriptrs . "</script>" : "";
      //$pgControls = $pageBld->pagecontrols;
      $pgBody = $pageBld->bodycontent;
      //$pgMenu = $pageBld->menucontent;
      //$pgModal = $pageBld->modalrs;
      //$pgDialogs = $pageBld->modalrdialogs;
      $rt = <<<RTNTHIS
<!DOCTYPE html>
<html><head>
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
    }
  } else { 
    //CHECK USER - BEFORE BUILDING PAGE
    require( genAppFiles . '/bldvuser.php');
    $vuser = new vaultuser(); 

    echo "LOGGED IN " . $_SESSION['loggedid'] . " :: " . json_encode( $vuser );
  }
  echo $rt;
  break;
case 'POST':
  $responseCode = 401;
  $data = json_encode(array("MESSAGE" => "","ITEMSFOUND" => 0, "DATA" => ""));
  $authuser = $_SERVER['PHP_AUTH_USER']; 
  $authpw = $_SERVER['PHP_AUTH_PW'];
  if ((int)checkPostingUser($authuser,$authpw) === 200 ) {  
    require( genAppFiles . '/dvposter.php');
    $postedData = file_get_contents('php://input');
    $passedPayLoad = "";
    if (trim($postedData) !== "") { 
      $passedPayLoad = trim($postedData);
    } 
    $doer = new dataposters($originalRequest, $passedPayLoad);
    $responseCode = $doer->responseCode; 
    $data = $doer->rtnData;
  } else { 
    $responseCode = 401;
    $msgArr[] = "SPECIFIED USER NOT ALLOWED";
    $data = json_encode(array("MESSAGE" => $msgArr,"ITEMSFOUND" => 0, "DATA" => ""));
  }

  header('Content-type: application/json; charset=utf8');
  header('Access-Control-Allow-Origin: *'); 
  header('Access-Control-Allow-Header: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Max-Age: 3628800'); 
  header('Access-Control-Allow-Methods: POST');  
  http_response_code( $responseCode );
  echo $data;
  break;
default:
  header('Content-type: application/json; charset=utf8');
  header('Access-Control-Allow-Origin: *'); 
  header('Access-Control-Allow-Header: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Max-Age: 3628800'); 
  header('Access-Control-Allow-Methods: GET, POST');  
  http_response_code( 503 );
  echo json_encode(array('MESSAGE' => 'ONLY GET/POST METHODS ARE ALLOWED AT THIS END POINT'));
}

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
     if ( ( !isset($_SESSION['loggedid']) || trim($_SESSION['loggedid']) === "" ) && $args[0] !== "login") {
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
  $elArr['scripts']     =   (method_exists($js,$whichpage) ? $js->$whichpage($rqststr) : "");
  if ($whichpage !== "login") {  
    $elArr['scripts']      =   (method_exists($js,'globalscripts') ? $js->globalscripts( "", "") : "");
  }

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

class defaultpageelements {
    
  function pagetabs($whichpage) { 
    switch($whichpage) { 
      case 'home':
        $thisTab = "CHTN Eastern Division";
      break;
      case 'datacoordinator':
        $thisTab = "Data Coordinator @ CHTNEastern";
        break;  
      default: 
        $thisTab = "CHTNEastern Donor Vault"; 
      break; 
    }
    return $thisTab;
  }
    
  function faviconBldr($whichpage) {  
    $at = genAppFiles;
    $favi = base64file("{$at}/chtnblue.ico", "favicon", "favicon", true);
    //$favi = "<link href=\"graphics/icons/chtnblue.ico\" rel=\"icon\" type=\"image/x-icon\">";
    return $favi;
  }    

  function modalbackbuilder($whichpage) {
    $thisModBack = "";
    switch ($whichpage) {     
      default: 
        $thisModBack = "<div id=standardModalBacker></div>";    
    }                
    return $thisModBack;
  }

  function controlbars ( $whichpage, $usr ) { 
    $at = genAppFiles;
    $chtnlogo = base64file("{$at}/publicobj/graphics/chtn-microscope-white.png", "controlBarCHTNTopper", "png", true);
    $tt = treeTop;

//<div id=titleish>DONOR VAULT</div>
    $globalcontrols = <<<CONTROLBARS

<div id=leftSideBar> 
  <div class=buttonContainer onclick="navigateSite('');"><div id=logoHolder>{$chtnlogo}</div><div class=popupToolTip>Back to Root Screen</div></div>  
  <div class=buttonContainer onclick="navigateSite('donor-lookup');"><div class=controlBarButton><i class="material-icons">account_circle</i></div><div class=popupToolTip>Donor Lookup</div></div>
  <div class=buttonContainer onclick="navigateSite('consent-watch');"><div class=controlBarButton><i class="material-icons">watch_later</i></div><div class=popupToolTip>Specify Consent Watch</div></div>
  <div class=buttonContainer onclick="navigateSite('pending-pathology-report-listing');"><div class=controlBarButton><i class="material-icons">format_list_bulleted</i></div><div class=popupToolTip>Pending PR List</div></div>
  <div class="buttonContainer" onclick="window.location.href = '{$tt}/lgdestroy.php';"><div class="controlBarButtonExit"><i class="material-icons">exit_to_app</i></div><div class=popupToolTip>Log-Out</div></div>

</div> 

CONTROLBARS;
    return $globalcontrols;
  }


}

class javascriptr {

  private $teststring = "";
  private $sessid = "";
  private $regcode = "";

  function __construct() {
    session_start();    
    $this->teststring = "ZACK WAS HERE IN THE TEST STRING";
    $this->sessid = session_id();
//    $this->regcode = registerServerIdent(session_id()); 
  }

  function globalscripts( $rqststr ) { 

    $tt = treeTop;
    $dtaTree = dataTree . "/data-doers/";
    $regUsr = session_id();      
    $regCode = dvRegisterServerIdent ( $regUsr ); 
    $rtnThis = <<<JAVASCR

window.addEventListener("unload", logData, false);

function logData() {
  //navigator.sendBeacon("lgdestroy.php", "");
}

var byId = function( id ) { return document.getElementById( id ); };
var treeTop = "{$tt}";
var dataPath = "{$dtaTree}";
var mousex;
var mousey;

var httpage = getXMLHTTPRequest();
var httpageone = getXMLHTTPRequest();
function getXMLHTTPRequest() {
try {
req = new XMLHttpRequest();
} catch(err1) {
        try {
	req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(err2) {
                try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch(err3) {
                  req = false;
                }
        }
}
return req;
}

function universalAJAX(methd, url, passedDataJSON, callbackfunc, dspBacker) { 
  if (dspBacker === 1) { 
    byId('standardModalBacker').style.display = 'block';
  }
  var rtn = new Object();
  var grandurl = dataPath+url;
  httpage.open(methd, grandurl, true); 
  httpage.setRequestHeader("Authorization","Basic " + btoa("{$regUsr}:{$regCode}"));
  httpage.onreadystatechange = function() { 
    if (httpage.readyState === 4) { 
      rtn['responseCode'] = httpage.status;
      rtn['responseText'] = httpage.responseText;
      if (parseInt(dspBacker) < 2) { 
        byId('standardModalBacker').style.display = 'none';
      }
      callbackfunc(rtn);
    }
  };
  httpage.send(passedDataJSON);
}

document.addEventListener('mousemove', function(e) { 
  mousex = e.pageX;
  mousey = e.pageY;
}, false);

function openOutSidePage(whatAddress) {
    var myRand = parseInt(Math.random()*9999999999999);
    window.open(whatAddress,myRand);
}

function openPageInTab(whichURL) { 
  if (whichURL !== "") {
    window.location.href = whichURL;
  }
}

function navigateSite(whichURL) {
    byId('standardModalBacker').style.display = 'block';
    if (whichURL) {
      window.location.href = treeTop+'/'+whichURL;
    } else {     
      window.location.href = treeTop;
    }
}

JAVASCR;
    return $rtnThis;
  }

  function pendingpathologyreportlisting ($rqststr) { 
    session_start(); 
    $tt = treeTop;
    $ott = ownerTree;
    $si = serverIdent;
    $sp = serverpw;
    
    $rtnThis = <<<JAVASCR

document.addEventListener('DOMContentLoaded', function() {             
  universalAJAX("POST", "retrievePendingPRListing", "", dspRetrievedPRs, 1);              
}, false);            
            
function dspRetrievedPRs ( rtnData ) { 
  var r = JSON.parse(rtnData['responseText']);
  if ( parseInt(r['RESPONSECODE']) !== 200 ) { 
    var msg = r['MESSAGE'];
    var dspMsg = ""; 
   msg.forEach(function(element) { 
      dspMsg += "\\n - "+element; 
    });    
    if ( byId('dataDsp') ) {         
      byId('dataDsp').innerHTML = "";        
    }
    alert(dspMsg);
  } else { 
    if ( byId('dataDsp') ) { 
      byId('dataDsp').innerHTML = r['DATA'];
    }
  }                        
}
            
JAVASCR;
    return $rtnThis;
      
  }
  
  function login($rqstrstr) { 
    $tt = treeTop;
    $regUsr = session_id();  
    $regCode = dvRegisterServerIdent ( $regUsr ); 
    $eMod = eModulus; 
    $eExpo = eExponent;  
 
$rtnThis = <<<JAVASCR

var byId = function( id ) { return document.getElementById( id ); };
var treeTop = "{$tt}";

var httpage = getXMLHTTPRequest();
var httpageone = getXMLHTTPRequest();
function getXMLHTTPRequest() {
try {
req = new XMLHttpRequest();
} catch(err1) {
        try {
	req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(err2) {
                try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch(err3) {
                  req = false;
                }
        }
}
return req;
}

var key; 
function bodyLoad() { 
   setMaxDigits(262);
   key = new RSAKeyPair("{$eExpo}","{$eExpo}","{$eMod}", 2048);
}


document.addEventListener('DOMContentLoaded', function() { 

bodyLoad();

if ( byId('actionBtnLogin') ) { 
   byId('actionBtnLogin').addEventListener('click', doLogin);
}

if ( byId('dvPWD') ) { 
  byId('dvPWD').focus();
}

document.addEventListener('keypress', function(event) { 
  if (event.which === 13) { 
    doLogin();
  }
}, false);


}, false);

function doLogin() { 
  var crd = new Object(); 
  crd['user'] = byId('dvUser').value; 
  crd['pword'] = byId('dvPWD').value;
  var cpass = JSON.stringify(crd);
  var ciphertext = window.btoa( encryptedString(key, cpass, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding) ); 
  var dta = new Object(); 
  dta['ency'] = ciphertext;
  var passdata = JSON.stringify(dta);
  var mlURL = "{$tt}/system-posts/session-login";
  httpage.open("POST",mlURL,true);
  httpage.setRequestHeader("Authorization","Basic " + btoa("{$regUsr}:{$regCode}"));
  httpage.onreadystatechange = function () { 
    if (httpage.readyState === 4) {
      if (httpage.status === 200) { 
         window.location.replace('{$tt}');  //TRUE - RELOAD FROM SERVER
         //location.reload(true);
      } else {
         var rcd = JSON.parse(httpage.responseText);
         var msgs = rcd['MESSAGE'];
         var dspMsg = ""; 
         msgs.forEach(function(element) { 
           dspMsg += "\\n - "+element;
         });
         alert("LOGIN ERROR:\\n"+dspMsg);
      }
  }
  };
  httpage.send( passdata );
}



JAVASCR;
    return $rtnThis;
  } 

  function donorlookup ( $rqststr ) { 
    session_start(); 
    $tt = treeTop;
    $ott = ownerTree;
    $si = serverIdent;
    $sp = serverpw;
    
    $rtnThis = <<<JAVASCR
    
    function sendSrchRqst() { 
        alert('CLICKED');                    
     }            
            
JAVASCR;
    return $rtnThis;
  }

}

class pagecontent { 

  function generateHeader( $whichpage ) {
    $tt = treeTop;      
    $at = genAppFiles;
    $jsscript =  base64file( "{$at}/extlibs/Barrett.js" , "", "js", true);
    $jsscript .= "\n" . base64file( "{$at}/extlibs/BigInt.js" , "", "js");
    $jsscript .= "\n" . base64file( "{$at}/extlibs/RSA.js" , "", "js");
    //$jsscript .= "\n" . base64file( "{$at}/publicobj/extjslib/tea.js" , "", "js");
    $rtnThis = <<<STANDARDHEAD
<!-- <META http-equiv="refresh" content="0;URL={$tt}"> //-->
<!-- SCIENCESERVER IDENTIFICATION: {$tt}/{$whichpage} //-->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="refresh" content="28800">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script lang=javascript>
var scienceserveridentification = '{$tt}/{$whichpage}';
</script>
{$jsscript}
STANDARDHEAD;
    return $rtnThis;
  }    


  function pendingpathologyreportlisting ( $rqst, $usr ) { 

      $r = json_encode( $rqst );
      $u = json_encode( $usr );

    $rt = <<<PGCONTENT

<div id=dataDsp>
    Please wait as we retrieve this information ...
</div>

PGCONTENT;
    return $rt;

  } 

  function donorlookup ( $rqst, $usr ) { 
      $r = json_encode( $rqst );
      $u = json_encode( $usr );

    $rt = <<<PGCONTENT

<div id=pgHolder>
  
   <div id=instructionBlock>These are instructions for how to use this screen</div>         
            
   <div class=elementHolder>
       <div class=fldLabel>First Name</div>
       <div class=fldHolder><input type=text id=fldFName></div>
   </div>

   <div class=elementHolder>
       <div class=fldLabel>Last Name</div>
       <div class=fldHolder><input type=text id=fldLName></div>
   </div>   

   <div class=elementHolder>
       <div class=fldLabel>MRN</div>
       <div class=fldHolder><input type=text id=fldMRN></div>
   </div>            
            
   <div class=elementHolder>
       <div class=fldLabel>O.R. Date</div>
       <div class=fldHolder><input type=text id=fldORDte></div>
   </div>            
            
   <div class=elementHolder>
       <div class=fldLabel>CHTN #</div>
       <div class=fldHolder><input type=text id=fldCHTNNbr></div>
   </div>
   
    <div><button onclick="sendSrchRqst();">Search</button></div>            
            
</div>            
PGCONTENT;
    return $rt;
  }

  function consentwatch ( $rqst, $usr ) { 
      $r = json_encode( $rqst );
      $u = json_encode( $usr );

    $rt = <<<PGCONTENT

Consent Watch Page 

PGCONTENT;
    return $rt;
  }

  function root ( $rqst, $usr ) { 

      $r = json_encode( $rqst );
      $u = json_encode( $usr );
    $rt = <<<PGCONTENT

<div id=rootTitle>CHTN Eastern Secure Donor Vault</div> 
<div id=rootDesc>This data server contains data on the donors of biosample research material of the Cooperative Human Tissue Network Eastern Division (CHTNED).  You must have explicit data access credentials to utilize this data server.  If you do not have the requisite access level, please click the logout button on the control bar on the left of this screen.  

</div>   

PGCONTENT;
    return $rt;
  } 

  function login( $rqst ) {
    $rqst = explode("/",$_SERVER['REQUEST_URI']);  
    $usrsssession =  cryptservice($rqst[1],'d');
    $pdta['usrency'] = $rqst[1];
    $rsltdta = json_decode(callrestapi("POST", dataTreeSS . "/data-doers/vault-get-user-email",serverIdent, serverpw, json_encode($pdta)), true);  
//    $rsltdta = callrestapi("POST", dataTreeSS . "/data-doers/vault-get-user-email",serverIdent, serverpw, json_encode($pdta));  
    $dts = $rsltdta['DATA'];
    $ssversion = scienceserverTagVersion;

    $rt = <<<PGCONTENT
    <div id=announcer>
      <div id=announceTitle>ScienceServer Donor Vault</div>
      <div>
        <div class=fldLabel>Identified User</div>
        <div class=fldHolder><input type=text value="{$dts}" READONLY id=dvUser></div>
      </div>
      <div>
        <div class=fldLabel>Single-Use Password</div>
        <div class=fldHolder><input type=password id=dvPWD></div>
      </div>
      <div id=actionBtnLoginHolder>
        <button id=actionBtnLogin><i class="material-icons">verified_user</i> Login</button>
      </div>
  
      <!--REMOVE THIS DIV WARNING IN PRODUCTION //-->
      <div id=devHIPAAWarning>THIS IS A DEVELOPMENT SITE - DO NOT USE ANY HIPAA DATA</div>
  

      <div id=announcerText>
        This is the <b>Donor Vault</b> Development Application for the Cooperative Human Tissue Network Eastern Division (CHTNED).  THIS IS A DEVELOPMENT SITE - <b>DO NOT</b> ENTER ANY HIPAA INFORMATION ON THIS WEBSITE!  This site is for the use of UPHS Systems only.  Use of this site is monitored closely.  Do Not attempt to access unless you have permission from CHTNED Management.  You must have a ScienceServer Login and be logged into ScienceServer to access the data in this vault. 
<p><b>Disclaimer</b>: This is the Specimen Management Data Application (SMDA) Donor Vault for the Eastern Division of the Cooperative Human Tissue Network.  It provides access to donor data for reference by authorized UPHS-specific employees of the CHTNEastern Division.   You must have a valid username and password to access this system.  If you need credentials for this application, please contact a CHTNED Manager.  Unauthorized activity is tracked and reported! To contact the offices of CHTNED, please call (215) 662-4570 or email chtnmail /at/ uphs.upenn.edu.
      <div id=loginvnbr>(Donor Vault Version: {$ssversion} [ScienceServer])</div>    
      </div>
    </div>

PGCONTENT;
    return $rt;
  }


}

class stylesheets {

  public $color_white = "255,255,255";
  public $color_black = "0,0,0";
  public $color_grey = "224,224,224";
  public $color_lgrey = "245,245,245";
  public $color_brwgrey = "239,235,233";
  public $color_ddrwgrey = "189,185,183";
  public $color_lamber = "255,248,225";
  public $color_dullyellow = "226,226,125";
  public $color_mamber = "204,197,175";
  public $color_mgrey = "160,160,160";
  public $color_dblue = "0,32,113";
  public $color_mblue = "13,70,160";
  public $color_lblue = "84,113,210";
  public $color_cornflowerblue = "100,149,237";
  public $color_lightblue = "209, 219, 255";
  public $color_zgrey = "48,57,71";
  public $color_neongreen = "57,255,20";
  public $color_bred = "237, 35, 0";
  public $color_darkgreen = "0, 112, 13";
  public $color_lightgrey = "239, 239, 239";
  public $color_darkgrey = "145,145,145";
  public $color_zackgrey = "48,57,71";  //#303947 
  public $color_zackcomp = "235,242,255"; //#ebf2ff
  public $color_deeppurple = "107, 18, 102";
  public $color_aqua = "203, 232, 240";

  //Z-INDEX:  0-30 - Base - Level // 40-49  - Floating level // 100 Black wait screen // 100+ dialogs above wait screen 
  
  function globalstyles($mobileInd) {
    $rtnThis = <<<STYLESHEET

@import url(https://fonts.googleapis.com/css?family=Roboto|Material+Icons|Fira+Sans);
* { box-sizing: border-box; } 
html { height: 100%; width: 100%; font-family: Roboto; font-size: 1vh; color: rgba({$this->color_black},1); }
body { margin: 0; padding-left: 4vw;  } 

#leftSideBar { position: fixed; z-index: 30; top: .5vh; left: 0; }
 
.buttonContainer { position: relative;  }

.buttonContainer #logoHolder { width: 3vw; text-align: center; border: 1px solid #000; margin-left: .2vw; margin-top: .2vh; padding: .5vh 0 .4vh 0; background: rgba({$this->color_dblue},1);  } 
.buttonContainer #logoHolder #controlBarCHTNTopper { width: 2.5vw; }

.buttonContainer .controlBarButton  { width: 3vw; text-align: center; border: 1px solid #000; margin-left: .2vw; margin-top: .2vh; padding: .5vh 0 .4vh 0; background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); }
.buttonContainer .controlBarButtonExit  { width: 3vw; text-align: center; border: 1px solid #000; margin-left: .2vw; margin-top: .2vh; padding: .5vh 0 .4vh 0; background: rgba({$this->color_bred},1); color: rgba({$this->color_white},1); }
.buttonContainer .controlBarButtonExit .material-icons { font-size: 3vh; }
.buttonContainer .controlBarButton .material-icons { font-size: 3vh; }


.popupToolTip { display: none; position: absolute; top: 0; left: 3.4vw; z-index: 31; white-space: nowrap; background: #88b7d5; border: 3px solid #c2e1f5; font-size: 1.4vh; color: rgba({$this->color_dblue},1); padding: .7vh .2vw;  }  
.popupToolTip:after, .popupToolTip:before { right: 100%; top: 50%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none;  }
.popupToolTip:after { border-color: rgba(136, 183, 213, 0); border-right-color: #88b7d5; border-width: 1vh; margin-top: -1vh; } 
.popupToolTip:before { border-color: rgba(194, 225, 245, 0); border-right-color: #c2e1f5; border-width: 1.2vh; margin-top: -1.2vh; }  

.buttonContainer:hover .popupToolTip { display: block; } 
.buttonContainer:hover { cursor: pointer; } 


#standardModalBacker { position: fixed; top: 0; left: 0;  z-index: 100; background: rgba({$this->color_zackgrey},.7); height: 100vh; width: 100vw; display: none; }
STYLESHEET;
    return $rtnThis;
  }

  function donorlookup() { 
      
    $rtnThis = <<<stylesheets

#pgHolder { display: grid; grid-template-columns: repeat(7, 1fr); padding: 1vh 0 0 0;   }             
#instructionBlock { grid-column: 1 / 8; grid-row: 1; }    
            
            
stylesheets;
    return $rtnThis; 
  }
  
  
  function root() {
    $at = genAppFiles; 
    $bgPic = base64file("{$at}/bg.png","background","bgurl",true);  
    $rtnThis = <<<STYLESHEET
body { background: {$bgPic} repeat; padding-left: 4vw; padding-right: 4vw;  }

#rootTitle { text-align: center; font-size: 3vh; padding: 3vh 0 .5vh 0; font-weight: bold; color: rgba({$this->color_dblue},1); border-bottom: 2px solid rgba({$this->color_dblue},1);  } 
#rootDesc { font-size: 1.2vh; text-align: justify; padding: 1vh 0 1vh 0; line-height: 1.8em; }

STYLESHEET;
    return $rtnThis;
  }

  function login () {
    $at = genAppFiles; 
    $bgPic = base64file("{$at}/bg.png","background","bgurl",true);  
    $rtnThis = <<<STYLESHEET

@import url(https://fonts.googleapis.com/css?family=Roboto|Material+Icons|Fira+Sans);
* { box-sizing: border-box; } 
html { height: 100%; width: 100%; font-family: Roboto; font-size: 1vh; color: rgba({$this->color_black},1); }
body { margin: 0; background: {$bgPic} repeat; } 

#announcer { border: 1px solid rgba({$this->color_dblue},1); width: 27vw; position: absolute; margin-left: -13vw; left: 50%; top: 20%; -webkit-box-shadow: 7px 4px 12px 5px rgba(0,0,0,0.27); box-shadow: 7px 4px 12px 5px rgba(0,0,0,0.27); background: rgba({$this->color_white},1); }
#announceTitle { background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); font-size: 2.8vh; font-weight: bold; padding: .4vh .5vw; margin-bottom: 2vh; }
#announcerText { font-size: .8vh; padding: .8vh .5vw; background: rgba({$this->color_lightgrey},.6); border-top: 1px solid rgba({$this->color_darkgrey},1); text-align: justify; line-height: 1.8em; } 

#loginvnbr { font-family: Roboto; font-size: .9vh; padding: .4vh .4vw .4vh .8vw; text-align: right; background: rgba({$this->color_lightgrey},1); }

#actionBtnLoginHolder { padding: 1vh 0 4vh 0; text-align: center; }
#actionBtnLogin { font-size: 1.4vh; }  
#actionBtnLogin .material-icons { font-size: 1.3vh; } 

input {width: 25vw; box-sizing: border-box; font-family: Roboto; font-size: 1.4vh;color: rgba({$this->color_zackgrey},1); padding: 1vh .5vw; border: 1px solid rgba({$this->color_mgrey},1);  }
input:focus, input:active {background: rgba({$this->color_lamber},.5); border: 1px solid rgba({$this->color_dblue},.5);  outline: none;  }
textarea { box-sizing: border-box; font-family: Roboto; font-size: 1.8vh;color: rgba({$this->color_zackgrey},1); padding: 1.3vh .5vw 1.3vh .5vw; border: 1px solid rgba({$this->color_mgrey},1); resize: none; }

.fldLabel { font-size: 1.1vh; font-weight: bold; padding: .8vh 0 0 .5vw; } 
.fldHolder { padding: 0 0 .2vh .5vw; } 

#dvUser { background: rgba({$this->color_lightgrey},1); color: rgba({$this->color_darkgrey},1);   } 


#devHIPAAWarning { margin-bottom: 1vh; text-align: center; background: rgba({$this->color_bred},.4); padding: .8vh 0; color: rgba({$this->color_dblue},1); font-size: 1.5vh; border-top: 1px solid rgba({$this->color_bred},1); border-bottom: 1px solid rgba({$this->color_bred},1); } 


STYLESHEET;
    return $rtnThis;
  }



}

function testerfunction() { 
return "RETURN THIS VALUE";
}

function ssValidateDate($date, $format = 'Y-m-d') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
 
function checkPostingUser($usrname, $passwrd) { 
    $responseCode = 401;  //UNAUTHORIZED 
    if ($usrname === serverIdent) { 
      //CHECK SERVER CREDENTIALS
      if ( cryptservice( $passwrd , 'd' ) === servertrupw ) { 
          $responseCode = 200;
      }
    } else { 
      //CHECK CODE IN DATABASE   
      require(serverkeys . "/sspdo.zck"); 
      //TODO: MAKE THIS AWEBSERVICE
      $chkSQL = "SELECT sessid, accesscode FROM ORSCHED.dv_ss_srvIdents where sessid = :usrsess and datediff(now(), onwhen) < 1";  
      $rs = $conn->prepare($chkSQL); 
      $rs->execute(array(':usrsess' => $usrname));
      if ((int)$rs->rowCount() > 0) { 
          $r = $rs->fetch(PDO::FETCH_ASSOC);
          if ( cryptservice( $passwrd, 'd', true, $usrname ) === $usrname ) { 
            $responseCode = 200;
          }
      } 
    }
    return $responseCode;
}

function dvRegisterServerIdent($sesscode) {      
   $idcode = generateRandomString(20); 
   require(serverkeys . "/sspdo.zck");  
   $delSQL = "delete FROM ORSCHED.dv_ss_srvIdents where sessid = :sessioncode";
   $dr = $conn->prepare($delSQL); 
   $dr->execute(array(':sessioncode' => $sesscode));
   $insSQL = "insert into ORSCHED.dv_ss_srvIdents (sessid, accesscode, onwhen) values(:sesscode , :accesscode , now())";
   $iR = $conn->prepare($insSQL); 
   $iR->execute(array(':sesscode' => $sesscode, ':accesscode' => $idcode)); 
   $rtn = cryptservice( $sesscode , 'e' , true , $sesscode );
   return $rtn;  
}

function cryptservice( $string, $action = 'e', $usedbkey = false, $passedsid = "") {
    $output = false;
    require( serverkeys . "/serverid.zck");
    if ($usedbkey) {
        session_start(); 
        $sid = (trim($passedsid) === "") ? session_id() : $passedsid;
        require(serverkeys . "/sspdo.zck");
        $sql = "select accesscode from ORSCHED.dv_ss_srvIdents where sessid = :sid";
        $rs = $conn->prepare($sql); 
        $rs->execute(array(':sid' => $sid));
        if ($rs->rowCount() < 1) { 
            exit(); 
        } else { 
          $r = $rs->fetch(PDO::FETCH_ASSOC);
        }
        $localsecretkey = $r['accesscode']; 
        $secret_key = $localsecretkey;
        $secret_iv = $localsecretkey;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $localsecretkey );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        if ( $action == 'e' ) {
          $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        if( $action == 'd' ) {
          $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
    } else { 
      $secret_key = $secretkey;
      $secret_iv = $siv;
      $encrypt_method = "AES-256-CBC";
      $key = hash( 'sha256', $secret_key );
      $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
      if ( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
      }
      if( $action == 'd' ) {
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
      }
    }
    return $output;
}

function callrestapi($method, $url, $user = "", $apikeyencrypt = "", $data = false) { 
  try {
    $ch = curl_init(); 
    if (FALSE === $ch) { return Exception('failed to initialize'); } 
    switch ($method) { 
      case "POST": 
        curl_setopt($ch, CURLOPT_POST, 1); 
        if ($data) { 
          curl_setopt($ch,CURLOPT_POSTFIELDS, $data); 
        }
      break; 
      case "GET": 
        curl_setopt($ch, CURLOPT_GET, 1); 
      break;
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = array(
        'Content-Type:application/json'
       ,'Authorization: Basic '. base64_encode("{$user}:{$apikeyencrypt}")
    );  //ADD AUTHORIZATION HEADERS HERE
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $content = curl_exec($ch);
    if (FALSE === $content) { 
      return Exception(curl_error($ch),curl_errno($ch));
    } else {
      return $content;
    }
  } catch(Exception $e) { 
    return sprintf('CURL failed with error #%d: %s', $e->getCode(), $e->getMessage()); 
  } 
}

function base64file($path, $identifier, $expectedObject, $createObject = true, $additionals = "") { 
  $object = NULL;    
  if (!file_exists($path) || !is_file($path)) {
  } else { 
    ob_start(); 
    readfile($path);
    $filecontent = base64_encode(ob_get_clean()); 
    if ($createObject) { 
      $mime = mime_content_type($path);
      switch ($expectedObject) { 
        case "image": 
          $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";
        break;
        case "png":
          $object = "<img id=\"{$identifier}\" src=\"data:image/png;base64,{$filecontent}\" {$additionals}>";
        break;
        case "pdf":
          //NOT YET DONE
            $object = "<object style=\"width: 100%; height: 100%;\" data=\"data:application/pdf;base64,{$filecontent}\" type=\"application/pdf\" class=\"internal\" {$additionals} >  <embed  style=\"width: 100%; height: 100%;\" src=\"data:application/pdf;base64,{$filecontent}\"  type=\"application/pdf\" {$additionals} >";
        break;
        case "pdfhlp":
          //NOT YET DONE
            $object = "<object style=\"width: 100%; height: 75vh;\" data=\"data:application/pdf;base64,{$filecontent}\" type=\"application/pdf\" class=\"internal\" {$additionals} >  <embed  style=\"width: 100%; height: 100%;\" src=\"data:application/pdf;base64,{$filecontent}\"  type=\"application/pdf\" {$additionals} >";
        break;        
        case "favicon": 
          $object = "<link href=\"data:image/x-icon;base64,{$filecontent}\" rel=\"icon\" type=\"image/x-icon\" {$additionals}>";
        break;
        case "js":
          $object = "<script type=\"text/javascript\" src=\"data:text/javascript;base64,{$filecontent}\" {$additionals}></script>";
          break;
        case "bgurl":
          $object = " url('data:{$mime};base64,{$filecontent}') ";
          break; 
        case 'gif':
            $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";            
            break;
        default:
          $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";
      } 
    } else { 
      $object = $filecontent;
    }
  }    
  return $object;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function guidv4() {
    if (function_exists('com_create_guid') === true) { return trim(com_create_guid(), '{}');  }
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


