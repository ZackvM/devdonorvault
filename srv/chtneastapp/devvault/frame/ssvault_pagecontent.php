<?php

class pagecontent { 

  function generateHeader( $whichpage ) {
    $tt = treeTop;      
    $at = genAppFiles;
    //$jsscript =  base64file( "{$at}/extlibs/Barrett.js" , "", "js", true);
    //$jsscript .= "\n" . base64file( "{$at}/extlibs/BigInt.js" , "", "js");
    //$jsscript .= "\n" . base64file( "{$at}/extlibs/RSA.js" , "", "js");
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

  function login( $rqst ) { 
    $usrsssession =  cryptservice($rqst[1],'d');
    $pdta['usrency'] = $rqst[1];
    $rsltdta = json_decode(callrestapi("POST", dataTreeSS . "/data-doers/vault-user-login-check",serverIdent, serverpw, json_encode($pdta)), true);  
    $dts = $rsltdta['DATA']['emailaddress'];

    $rt = <<<PGCONTENT
    <div id=announcer>
      <div id=announcerText>
        This is the <b>Donor Vault</b> Development Application for the Cooperative Human Tissue Network Eastern Division (CHTNED).  THIS IS A DEVELOPMENT SITE - <b>DO NOT</b> ENTER ANY HIPAA INFORMATION ON THIS WEBSITE!  This site is for the use of UPHS Systems only.  Use of this site is monitored closely.  Do Not attempt to access unless you have permission from CHTNED Management.  You must have a ScienceServer Login and be logged into ScienceServer to access the data in this vault.  
      </div>
      <div>
        <input type=text value="{$dts}" READONLY id=dvUser>
      </div>
      <div>
        <input type=password id=dvPWD>
      </div>
      <div>
        <input type=button value="Login">
      </div>
    </div>

PGCONTENT;
    return $rt;
  }


}
