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


}

