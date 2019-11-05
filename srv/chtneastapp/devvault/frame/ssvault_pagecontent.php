<?php

class pagecontent { 

  function generateHeader( $whichpage ) {
    $tt = treeTop;      
    $at = genAppFiles;
    $jsscript =  base64file( "{$at}/extlibs/Barrett.js" , "", "js", true);
    $jsscript .= "\n" . base64file( "{$at}/extlibs/BigInt.js" , "", "js");
    $jsscript .= "\n" . base64file( "{$at}/extlibs/RSA.js" , "", "js");
    $jsscript .= "\n" . base64file( "{$at}/publicobj/extjslib/tea.js" , "", "js");
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
    $usrsssession =  cryptservice($rqst[1],'d');
    $pdta['usrency'] = $rqst[1];
    $rsltdta = json_decode(callrestapi("POST", dataTreeSS . "/data-doers/vault-user-login-check",serverIdent, serverpw, json_encode($pdta)), true);  
    $dts = $rsltdta['DATA']['emailaddress'];
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
