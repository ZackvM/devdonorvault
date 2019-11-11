<?php

class bldssvaultuser { 
    
    public $statuscode = 404;
    public $loggedsession = "";
    public $friendlyname = "";
    public $userid = "";
    public $ssxtime = "";
    public $oaccount = "";
    public $accesslevel = "";
    public $accessnbr = 0;
    
    function __construct() {
        //$args = func_get_args(); 
        $userelements = self::getUserInformation();
        $this->statuscode = $userelements['responsecode'];  
        $this->loggedsession = $userelements['sssession'];
        $this->friendlyname = $userelements['friendlyname'];
        $this->userid = $userelements['userid'];
        $this->ssxtime = $userelements['xtime'];
        $this->oaccount = $userelements['oaccount'];
        $this->accesslevel = $userelements['accesslevel'];
        $this->accessnbr = $userelements['accessnbr'];
    }
    
    function getUserInformation() { 
        $elArr = array();
        session_start(); 

        $passedData['pxiguidency'] = cryptservice( $_SESSION['pxiguid'] . "::" . date('YmdHis') ); 
        $rsltdta = json_decode( callrestapi("POST", dataTreeSS . "/data-doers/vault-get-user",serverIdent, serverpw, json_encode($passedData)), true);   

        //{"RESPONSECODE":200,"MESSAGE":[],"ITEMSFOUND":0,"DATA":{"friendlyname":"Zack","userid":"zacheryv@mail.med.upenn.edu","expiretime":"12:50","origacctname":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":"43"}}
        if ( (int)$rsltdta['RESPONSECODE'] === 200 ) {
          $elArr['responsecode'] = 200;  
          $elArr['sssession'] =  $_SESSION['pxiguid'];
          $elArr['friendlyname'] =  $rsltdta['DATA']['friendlyname'];
          $elArr['userid'] =  $rsltdta['DATA']['userid'];
          $elArr['xtime'] =  $rsltdta['DATA']['expiretime'];
          $elArr['oaccount'] =  $rsltdta['DATA']['origacctname'];
          $elArr['accesslevel'] =  $rsltdta['DATA']['accesslevel'];
          $elArr['accessnbr'] =  $rsltdta['DATA']['accessnbr'];
        }   else {
          $elArr['responsecode'] = 503;
          $elArr['sssession'] = "";             
          $elArr['friendlyname'] =  "";
          $elArr['userid'] =  "";
          $elArr['xtime'] =  "";
          $elArr['oaccount'] =  "";
          $elArr['accesslevel'] = "";
          $elArr['accessnbr'] =  0;
        }     
        return $elArr;
    }
        
}
