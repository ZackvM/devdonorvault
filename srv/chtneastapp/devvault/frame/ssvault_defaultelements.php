<?php


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
    $favi = base64file("{$at}/publicobj/graphics/icons/chtnblue.ico", "favicon", "favicon", true);
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

