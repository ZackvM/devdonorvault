<?php

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
    session_start(); 
    $tt = treeTop;
    $ott = ownerTree;
    $si = serverIdent;
    $sp = serverpw;
    //LOCAL USER CREDENTIALS BUILT HERE
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
  var mlURL = "{$tt}/data-services/system-posts/session-login";
  httpage.open("POST",mlURL,true);
  httpage.setRequestHeader("Authorization","Basic " + btoa("{$regUsr}:{$regCode}"));
  httpage.onreadystatechange = function () { 
    if (httpage.readyState === 4) {
      if (httpage.status === 200) { 
         window.location.replace('{$tt}');  //TRUE - RELOAD FROM SERVER
         //location.reload(true);
      } else { 
         var rcd = JSON.parse(httpage.responseText);
         var msgs = JSON.parse(rcd['MESSAGE']);
         var dspMsg = ""; 
         msgs['MESSAGE'].forEach(function(element) { 
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



}
