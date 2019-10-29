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

  function login($rqstrstr) { 
    session_start(); 
    $tt = treeTop;
    $ott = ownerTree;
    //$si = serverIdent;
    //$sp = serverpw;

    //LOCAL USER CREDENTIALS BUILT HERE
    //$regUsr = session_id();  
    //$regCode = registerServerIdent($regUsr);  

    //$eMod = eModulus; 
    //$eExpo = eExponent;  
 
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
JAVASCR;
    return $rtnThis;
  } 



}
