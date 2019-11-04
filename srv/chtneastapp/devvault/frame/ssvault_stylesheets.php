<?php

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


  function root() {
    $at = genAppFiles; 
    $bgPic = base64file("{$at}/publicobj/graphics/bg.png","background","bgurl",true);  
    $rtnThis = <<<STYLESHEET
body { background: {$bgPic} repeat; padding-left: 4vw; padding-right: 4vw;  }

#rootTitle { text-align: center; font-size: 3vh; padding: 3vh 0 .5vh 0; font-weight: bold; color: rgba({$this->color_dblue},1); border-bottom: 2px solid rgba({$this->color_dblue},1);  } 
#rootDesc { font-size: 1.2vh; text-align: justify; padding: 1vh 0 1vh 0; line-height: 1.8em; }

STYLESHEET;
    return $rtnThis;
  }

  function login () {
    $at = genAppFiles; 
    $bgPic = base64file("{$at}/publicobj/graphics/bg.png","background","bgurl",true);  
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
