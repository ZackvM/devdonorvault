<?php 

//ini_set('display_errors',1);
//error_reporting(-1);

/* DONORVAULT-SCIENCESERVER MAIN INDEX */
session_start();
define("scienceserverTagVersion","v7.0.5invty");

/*   PRODUCTION VARS */
define("serverkeys", "/var/www/cgi-bin");
define("genAppFiles", "/var/www/cgi-bin");
define("treeTop","https://chtn2017.uphs.upenn.edu");
define("dataTree","https://chtn2017.uphs.upenn.edu");

/*   TEST VARS    
define("serverkeys","/var/www/devvault.chtneast.org/cgi-bin");
define("genAppFiles","/var/www/devvault.chtneast.org/cgi-bin");
define("treeTop","https://devvault.chtneast.org");
define("dataTree","https://devvault.chtneast.org");
 */

define("dataTreeSS","https://dev.chtneast.org/data-services");
define("dbu","T1FtbG9VZkxGUCt1aGxSSDE5dEJxdz09\U0IxeHNIQmpXR1Y4anRvNEMxYTlNUT09");

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

require( serverkeys . '/asprt.php');
require( serverkeys . '/serverid.zck');
require( serverkeys . '/sspdo.zck');
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
    //$vuser = {"responsecode":200,"userguid":"bd1dbfde-1a27-4587-a190-c2296b68901a","userid":"zacheryv@mail.med.upenn.edu","friendlyname":"Zack","oaccount":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":43,"holder":""}
    $vuser = new vaultuser();
    if ( (int)$vuser->responsecode === 200 ) { 
        if ( trim($request[1]) === "" ) { 
          $obj = "root";
        } else { 
          $obj = trim($request[1]);
        }		
        $pageBld = new pagebuilder($obj, $originalRequest, $vuser);
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
          $pgControls = $pageBld->pagecontrols;
          $pgBody = $pageBld->bodycontent;
          //$pgMenu = $pageBld->menucontent;
          $pgModal = $pageBld->modalrs;
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
       //LOGOUT
       session_regenerate_id(true);
       session_unset();
       session_destroy();
       $rt = "USER HAS BEEN LOGGED OUT";
    }
  }
  echo $rt;
  break;
case 'POST':
  header('Content-type: application/json; charset=utf8');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Header: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Max-Age: 3628800');
  header('Access-Control-Allow-Methods: POST');
  $responseCode = 401;
  $data = json_encode(array("MESSAGE" => "","ITEMSFOUND" => 0, "DATA" => ""));
  $authuser = $_SERVER['PHP_AUTH_USER'];
  $authpw = $_SERVER['PHP_AUTH_PW'];
  
  if ( $originalRequest == '/datadoers/stashconsent') {
 //     $responseCode = 500; 
 //     if (isset($_FILES['file'])) {
 //        $msgArr[] =$_FILES['file']['name'];
         //$msgArr[] =$_FILES['file']['size'];
         //$msgArr[] =$_FILES['file']['tmp_name'];
         //$msgArr[] =$_FILES['file']['type'];
//	 $msgArr[] =strtolower(end(explode('.',$_FILES['file']['name'])));
//	 $msgArr[] = $temp_file = sys_get_temp_dir();
//	 $img = file_get_contents( $_FILES['file']['tmp_name'] );
//	 require( serverkeys . '/sspdo.zck');

	 $selector = generateRandomString(25);
	 move_uploaded_file($_FILES['file']['tmp_name'], "/var/www/cgi-bin/stash/{$selector}.pdf");
//         $docInsSQL = "insert into ORSCHED.ut_informed_consents_documents(icid, selector, documentstring) values(0, :selector, :documentstring)";
//	 $docInsRS = $conn->prepare($docInsSQL);
//	 $f = base64_encode( $img ); 
//         $docInsRS->execute(array(':selector' => $selector, ':documentstring' => $f )); 
//	 $msgArr[] = $selector;	
//	 $msgArr[] = $f; 
//         $msgArr[] = $authuser;
      }
 //     $data = json_encode(array("MESSAGE" => $msgArr,"ITEMSFOUND" => 0, "DATA" => ""));
 // } else {  

    if ((int)checkPostingUser($authuser,$authpw) === 200 ) {
      //require( genAppFiles . '/dvposter.php');
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
      $msgArr[] = "SPECIFIED USER NOT ALLOWED.";
      $data = json_encode(array("MESSAGE" => $msgArr,"ITEMSFOUND" => 0, "DATA" => ""));
    }
  //}

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

class vaultuser { 
    
    public $responsecode = 401; 
    public $userguid = "";
    public $userid = ""; 
    public $friendlyname = ""; 
    public $oaccount = "";
    public $accesslevel = "";
    public $accessnbr = 0;
    public $holder = "";
    
    function __construct() {      
      if ( (!isset($_SESSION['loggedid']) || trim($_SESSION['loggedid']) === '')    ) { 
        //BAD USER        
      } else {	      
        $pdta['pxiguidency'] = cryptservice($_SESSION['loggedid'] . '::' . date('YmdHis'),'e'); 
        $passedData = json_encode($pdta); 
	$rsltdta = json_decode(callrestapi("POST", dataTreeSS . "/data-doers/vault-get-user",serverIdent, serverpw, $passedData), true);  
	if ( (int)$rsltdta['RESPONSECODE'] === 200 ) {
	  $this->responsecode = $rsltdta['RESPONSECODE'];
	  $u = $rsltdta['DATA'];
	  $this->userid = $u['userid'];
	  $this->userguid = $_SESSION['loggedid'];
	  $this->friendlyname = $u['friendlyname'];
	  $this->oaccount = $u['origacctname'];
	  $this->accesslevel = $u['accesslevel'];
	  $this->accessnbr = (int)$u['accessnbr'];
	}
      }
    } 
 
}

class dataposters { 

  public $responseCode = 400;
  public $rtnData = "";

  function __construct() { 
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 
    $this->rtnData = $args[0];    
    if (trim($args[0]) === "") { 
    } else { 
        $request = explode("/",  str_replace("-","", strtolower($args[0]))    ); 
        $this->rtnData = $request[1];
      if (trim($request[2]) === "") { 
        $this->responseCode = 404; 
        $this->rtnData = json_encode(array("MESSAGE" => "DATA NAME MISSING " . json_encode($request),"ITEMSFOUND" => 0, "DATA" => array()    ));
      } else { 
        $dp = new $request[1](); 
        if (method_exists($dp, $request[2])) { 
          $funcName = trim($request[2]); 
          $dataReturned = $dp->$funcName($args[0], $args[1]); 
          $this->responseCode = $dataReturned['statusCode']; 
          $this->rtnData = json_encode($dataReturned['data']);
        } else { 
          $this->responseCode = 404; 
          $this->rtnData = json_encode(array("MESSAGE" => "END-POINT FUNCTION NOT FOUND: {$request[2]}","ITEMSFOUND" => 0, "DATA" => ""));
        }
      }
    }
  }

}

class datadoers {

    function markmasterrecordconsents ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
          $pdta = json_decode($passedData, true); 
          foreach ( $pdta as $key => $value ) {
              if ( !cryptservice($key,'d') ) { 
                 $locarr[ $key ] = $value; 
              } else { 
                 $locarr[ cryptservice($key,'d') ] = chtndecrypt( $value );
              }
          }
          ( !array_key_exists('fileselector', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fileselector' DOES NOT EXIST.")) : ""; 
          ( !array_key_exists('bgroupdelimit', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'bgroupdelimit' DOES NOT EXIST.")) : ""; 
          ( !array_key_exists('icid', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'icid' DOES NOT EXIST.")) : ""; 

          if ( $errorInd === 0 ) {  
          
              ( trim($locarr['fileselector']) === '' ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FILE SELECTOR CANNOT BE EMPTY.")) : "";
              ( trim($locarr['icid']) === '' ) ? (list( $errorInd, $msgArr[] ) = array(1 , "INFORMED CONSENT ID MUST BE SUPPLIED.")) : "";
              ( trim($locarr['bgroupdelimit']) === '' ) ? (list( $errorInd, $msgArr[] ) = array(1 , "YOU HAVE NOT SPECIFIED ANY BIOGROUPS.")) : "";

              if ( $errorInd === 0 ) {

                $pdta['vaultuser'] = $vuser->userid; 
                $passedData = json_encode( $pdta );
                  
                $icDta = json_decode( callrestapi("POST", dataTreeSS . "/data-doers/vault-ic-biogroup-update", serverIdent, serverpw, $passedData  ) , true);                       
                if ( (int)$icDta['RESPONSECODE'] <> 200 ) {
                  foreach ( $icDta['MESSAGE'] as $mk => $mv ) {    
                      $msgArr[] = $mv;
                  }
                } else {
                  //SUCCESS FROM MASTERRECORD
                  require( serverkeys . '/sspdo.zck'); 
                  $insSQL = "insert into ORSCHED.ut_informed_consents_biogroups (icid, chtnnbr, uploadedon, uploadedby) values (:icid, :chtnnbr, now(), :uploadedby)";
                  $insRS = $conn->prepare( $insSQL ); 
                  $bgdlist = explode(',',str_replace(' ','',$locarr['bgroupdelimit']));
                  foreach ( $bgdlist as $bgv ) {
                    $bgv = strtoupper($bgv);  
                    $insRS->execute( array(':icid' => $locarr['icid'], ':chtnnbr' => $bgv, ':uploadedby' => $vuser->userid ));
                  }
                  $responseCode = 200;  
                }
              }
          }
      }  else { 
          $dta = "USER NOT ALLOWED";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;      
    }

    function getconsentdocument ( $request, $passedData )  { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
          $pdta = json_decode($passedData, true); 
          foreach ( $pdta as $key => $value ) {
              if ( !cryptservice($key,'d') ) { 
                 $locarr[ $key ] = $value; 
              } else { 
                 $locarr[ cryptservice($key,'d') ] = chtndecrypt( $value );
              }
          }
          ( !array_key_exists('fileselector', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fileselector' DOES NOT EXIST.")) : ""; 

          if ( $errorInd === 0 ) {  
            require( serverkeys . '/sspdo.zck');
            $docSQL = "SELECT icd.documentstring FROM ORSCHED.ut_informed_consents ic left join ORSCHED.ut_informed_consents_documents icd on ic.icid = icd.icid where binary ic.selector = :selector";
            $docRS = $conn->prepare($docSQL);
            $docRS->execute(array( ':selector' => $locarr['fileselector']  ));
            if ( $docRS->rowCount() < 1 ) { 
              $msgArr[] = 'CONSENT DOCUMENT NOT FOUND';
            } else {
              $doc = $docRS->fetch(PDO::FETCH_ASSOC);
              $dta['pdfstring'] =  $doc['documentstring'];
              $dta['dialogid'] = generateRandomString();
              //$dta['dialogid'] = 'XXXDDDXXX';
              $responseCode = 200;
            }
          }
      }  else { 
          $dta = "USER NOT ALLOWED";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;      
    }

    function stashconsent ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;

      $msgArr[] = $_FILES['file']['tmp_name'];
      
      if ( (int)$vuser->responsecode === 200 ) { 	 
          if ( trim($_FILES['file']['tmp_name']) !== "" ) { 
           //$msgArr[] =$_FILES['file']['type'];  //CHECK TO MAKE SURE IT IS A PDF
           //$msgArr[] =strtolower(end(explode('.',$_FILES['file']['name'])));
           $msgArr[] = $_FILES['file']['name'] ;
           $selector = generateRandomString(25);
           move_uploaded_file($_FILES['file']['tmp_name'], "/var/www/cgi-bin/stash/{$selector}.pdf");
        } else { 
            
            list( $errorInd, $msgArr[] ) = array( 1, "NO CONSENT PDF HAS BEEN SPECIFIED");
        }
          

//        foreach ( $_POST as $key => $value ) {
//          $msgArr[] = $key . " ... " . $value;
//        }


        
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;      
    }

    function saveconsentdocument ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
//          $pdta = json_decode($passedData, true); 
//          foreach ( $pdta as $key => $value ) {
//              if ( !cryptservice($key,'d') ) { 
//                 $locarr[ $key ] = $value; 
//              } else { 
//                 $locarr[ cryptservice($key,'d') ] = chtndecrypt( $value );
//              }
//          }
//
//	  ( !array_key_exists('fldDonorFName', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldDonorFName' DOES NOT EXIST.")) : ""; 
//          ( !array_key_exists('fldDonorLName', $locarr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldDonorLName' DOES NOT EXIST.")) : ""; 
//
//
//          if ( $errorInd === 0 ) {
//             ( trim($locarr['fldDonorFName']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1,"THE DONOR'S FIRST NAME MUST BE SPECIFIED.")) : "";
//
//            if ( $errorInd === 0 ) {
//              require( serverkeys . '/sspdo.zck');
//	      $insSQL = "insert into ORSCHED.ut_informed_consents (selector, donorfname, donorlname, uploadon ) values (:selector, :donorfname, :donorlname , now())";
//              $insRS = $conn->prepare($insSQL); 
//	      $selector = generateRandomString(25);
//	      $insRS->execute(array(':selector' =>  $selector, ':donorfname' => trim($locarr['fldDonorFName']), ':donorlname' =>  trim($locarr['fldDonorLName'])    ));
	      //$insRS->execute(array(':selector' =>  $selector, ':donorfname' => trim($locarr['fldDonorFName']), ':donorlname' =>  trim($locarr['fldDonorLName']), ':mrn' => trim($locarr['fldDonorMRN']),':age' =>  trim($locarr['fldDnrAge']), ':ageuom' =>  trim($locarr['fldDnrAgeUOM']) , ':race' =>  trim($locarr['fldDnrRace']), ':sex' =>  trim($locarr['fldDnrSex']), ':rqstrfname' =>  trim($locarr['fldIFName']), ':rqstrlname' => trim($locarr['fldILName']), ':anticprocdate' => date('Y-m-d',strtotime( $anticProcDate )), ':consentdoctype' =>  trim($locarr['consentdoc']), ':uploadby' => $vuser->userid));
//	      $icid = $conn->lastInsertId(); 
//
//              $icid = 100;
//              $docInsSQL = "insert into ORSCHED.ut_informed_consents_documents(icid, documentstring) values(:icid, :documentstring)";
//              $docInsRS = $conn->prepare($docInsSQL); 
//              $docInsRS->execute(array(':icid' => $icid, ':documentstring' => $locarr['consentfile'] )); 
// 
//              (list( $errorInd, $msgArr[] ) = array(1 , $locarr['consentfile']  ));
//             //$responseCode = 200;
//            }
//          }
      }  else { 
          $dta = "USER NOT ALLOWED";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;      
    }

    function sendprmarkno ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
      //{"responsecode":200,"userguid":"6ad4cb09-4eb1-44b2-b400-45bf59a4f9d9","userid":"zacheryv@mail.med.upenn.edu","friendlyname":"Zack","oaccount":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":43,"holder":""}
        $pdta = json_decode($passedData, true);
        $newPDta['user'] = $vuser->userid;
        ( !array_key_exists('bglist', $pdta) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'bglist' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('reason', $pdta) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'reason' DOES NOT EXIST.")) : ""; 
        if ( $errorInd === 0 ) {
          ( count(json_decode($pdta['bglist']),true) < 1 ) ?  (list( $errorInd, $msgArr[] ) = array(1 , "BG List is blank ... Won't Continue")) : ""; 
          ( trim($pdta['reason']) === "" ) ?  (list( $errorInd, $msgArr[] ) = array(1 , "You Must Supply a reason why you are marking these biogroups as Pathology Report No")) : ""; 
          if ( $errorInd === 0 ) { 
            $newPDta['bglist'] = $pdta['bglist']; 
            $newPDta['reason'] = trim($pdta['reason']);
            $cqDta = json_decode( callrestapi("POST", dataTreeSS . "/data-doers/vault-mark-pr-no", serverIdent, serverpw, json_encode( $newPDta )  ) , true);                       
            if ( (int)$cqDta['RESPONSECODE'] === 200 ) { 
                //SUCCESS
                $responseCode = 200;
            } else {  
              foreach ( $cqDta['MESSAGE'] as $k => $dspv ) {  
                  $msgArr[] = $dspv;
              }
            }
          }
        }
      } else { 
          $dta = "USER NOT ALLOWED";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;
    }

    function makeexclusionrqst ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
        require( serverkeys . '/sspdo.zck');
        $pdta = json_decode( $passedData, true); 
        foreach ( $pdta as $k => $v ) {
          $locArr[cryptservice( $k, 'd')] = chtndecrypt($v);  
        }
        //{"fldExFName":"Diane","fldExLName":"mcgarvey","fldExMRN":"23232323","fldExDate":"","fldExNote":""}
        ( !array_key_exists('fldExFName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExFName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExLName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExLName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExMRN', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExMRN' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExDate', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExDate' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExNote', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExNote' DOES NOT EXIST.")) : ""; 
        if ( $errorInd === 0 ) {
           ( trim($locArr['fldExMRN']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "THIS FUNCTION NEEDS THE POTENTIAL DONOR'S MEDICAL RECORD NUMBER (MRN).  PLEASE SUPPLY THIS VALUE")) : "";
           ( trim($locArr['fldExFName']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "THIS FUNCTION NEEDS THE POTENTIAL DONOR'S FIRST NAME.  PLEASE SUPPLY THIS VALUE")) : ""; 
           ( trim($locArr['fldExLName']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "THIS FUNCTION NEEDS THE POTENTIAL DONOR'S LAST NAME.  PLEASE SUPPLY THIS VALUE")) : "";                      
           ( trim($locArr['fldExDate']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "THIS FUNCTION NEEDS THE DATE OF OPT-OUT FOR THIS POTENTIAL DONOR.  PLEASE SUPPLY THIS VALUE")) : ""; 
           ( trim($locArr['fldExDate']) !== "" && !ssValidateDate(trim($locArr['fldExDate']),'m/d/Y' )) ? (list( $errorInd, $msgArr[] ) = array(1 , "THE OPT-OUT DATE FOR THIS POTENTIAL DONOR IS NOT A VALID DATE.")) : "";
         if ( $errorInd === 0 ) {  
          $chkr = self::checkclearexclusiontitle ( "" , $passedData );
          switch ( (int)$chkr['data']['RESPONSECODE'] ) {
            case 200:
                //200 MRN FOUND NO BIOGROUPS - Add Exclusion
                $updSQL = "update ORSCHED.ut_zck_ORSchDetail set TissueTargetStatus = 'EXC', TissueTargetOn = now(), TissueTargetBy = :vuser, procdetails = concat('DONOR HAS OPTED OUT OF BIOGROUP COLLECTION.  DO NOT COLLECT FROM THIS DONOR!\n',ifnull(procdetails,'')), memo = concat(:optmsg,ifnull(memo,'')), targetnotefromweb = :exnote where mrn = :mrn";
                $updRS = $conn->prepare($updSQL);
                $updRS->execute ( array(':vuser' => $vuser->userid,':optmsg' => "OPT OUT ON {$locArr['fldExDate']} \n", ':exnote' =>  trim($locArr['fldExNote']), ':mrn' => trim($locArr['fldExMRN'])));
                $msgArr[] = "DONOR WAS FOUND ON A SCHEDULE BUT HAD NO BIOGROUPS REFERENCED IN THE PAST.  THE EXCLUSION/OPT-OUT HAS BEEN MARKED.  REFRESH YOUR SCREEN TO SEE THE UPDATED EXCLUSION LIST.";
                break;
            case 404:
                //NO MRN FOUND - Add Exclusion    
                $orschSQL = "insert into ORSCHED.ut_zck_ORSchDetail (ORSchdid, ORSchdtid, patlast, patfirst, mrn, procdetails, memo, tissuetargetstatus, tissuetargeton, tissuetargetby, targetnotefromweb) values ('EXCLUSIONLISTING',:guid, :patlast, :patfirst, :mrn, :procdetails, :memo, :tissuetargetstatus, now(), :tissuetargetby, :targetnotefromweb)";
                $orschRS = $conn->prepare($orschSQL);
                $orschRS->execute(array( ':guid' => guidv4(), ':patlast' => strtoupper(trim($locArr['fldExLName'])), ':patfirst' => strtoupper(trim($locArr['fldExFName'])), ':mrn' => trim($locArr['fldExMRN']), ':procdetails' => 'DONOR HAS OPTED OUT OF BIOGROUP COLLECTION.  DO NOT COLLECT FROM THIS DONOR!', ':memo' => 'OPT OUT ON ' . trim($locArr['fldExDate']), ':tissuetargetstatus' => 'EXC', ':tissuetargetby' => $vuser->userid, ':targetnotefromweb' => trim($locArr['fldExNote']) ));
                $msgArr[] = "DONOR WAS NOT FOUND ON ANY SCHEDULE AND HAS NO BIOGROUPS REFERENCED IN THE PAST.  THE EXCLUSION/OPT-OUT HAS BEEN MARKED.  REFRESH YOUR SCREEN TO SEE THE UPDATED EXCLUSION LIST.";
                break;
            case 406: 
                //BIOGROUPS FOUND - DISPLAY ERROR
                $msgArr[] = "<b>SEE A CHTNEASTERN MANAGER IMMEDIATELY!!</b><br>(EXCLUSION NOT ADDED TO DONOR VAULT)<br>BIOGROUP(s) FOUND: <br>";
                foreach ( $chkr['data']['MESSAGE'][0] as $k => $v ) {
                    $msgArr[] = "&nbsp;&nbsp;&nbsp;&nbsp; {$v}";
                }
                break;
            default:
            $msgArr[] = "<b>ERROR:</b><br>THERE WAS AN ERROR IN THE PROCESS.  SEE CHTNEASTERN INFORMATICS (error: 20200106a)";
          }
         }
        }
        $responseCode = 200;
      }
      $msg = "MESSAGE:  ";
      foreach ( $msgArr as $k => $mv ) {
        $msg .= "<br>- {$mv}";
      }
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;   
    }
    
    function checkclearexclusiontitle ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      if ( (int)$vuser->responsecode === 200 ) { 
        require( serverkeys . '/sspdo.zck');
        $pdta = json_decode( $passedData, true); 
        foreach ( $pdta as $k => $v ) {
          $locArr[cryptservice( $k, 'd')] = chtndecrypt($v);  
        }
        ( !array_key_exists('fldExFName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExFName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExLName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExLName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExMRN', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExMRN' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExDate', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExDate' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldExNote', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldExNote' DOES NOT EXIST.")) : ""; 
        //TODO:   WRITE A SEARCH TRACKER INSTEAD OF JUST 'CLEAR'
        if ( $errorInd === 0 ) { 
         ( trim($locArr['fldExMRN']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "THE SEARCH FUNCTION NEEDS THE POTENTIAL DONOR'S MEDICAL RECORD NUMBER (MRN).  PLEASE SUPPLY THIS VALUE")) : "";
         if ( $errorInd === 0 ) {
            if ( $errorInd === 0 ) {
              //GET ALL REFERENCES TO THIS MRN 
              $chkSQL = "SELECT ORSchdtid as pxiid FROM ORSCHED.ut_zck_ORSchDetail where mrn = :mrn";
              $chkRS = $conn->prepare($chkSQL); 
              $chkRS->execute( array ( ':mrn' => $locArr['fldExMRN'] ));  
              if ( $chkRS->rowCount() < 1 ) { 
                //NO DONOR RECORD FOUND
                //CLEAR TITLE  
                $responseCode = 404;
              } else { 
                  //CHECK MASTERRECORD
                  $pxis = array();
                  while ($r = $chkRS->fetch(PDO::FETCH_ASSOC)) { 
                    $pxis[] = $r['pxiid'];   
                  } 
                  $pxilist['pxilist'] = $pxis;
                  $cqDta = json_decode( callrestapi("POST", dataTreeSS . "/data-doers/vault-check-pxiids", serverIdent, serverpw, json_encode( $pxilist )  ) , true);                       
                  if ( (int)$cqDta['ITEMSFOUND'] < 1 ) {
                    $responseCode = 200;
                  } else {
                    $responseCode = 406;
                    $msgArr[] = $cqDta['DATA']; 
                  } 
              }
            }
         }
        }
      } else { 
          $dta = "USER NOT ALLOWED";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;        
    }
    
    function generatedialog ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      if ( (int)$vuser->responsecode === 200 ) { 
        $pdta = json_decode($passedData, true);
        $dlogs = new dialogs();  
        if ( method_exists( $dlogs,str_replace("-","", $pdta['dialog'])) ) { 
          $funcName = trim( str_replace("-","", $pdta['dialog']) ); 
          $d = $dlogs->$funcName( $passedData ); 
          $dta = array("pageElement" => $d['dialog'], "dialogID" => $d['dialogid'], 'left' => $d['left'], 'top' => $d['top'], 'primeFocus' => $d['primeFocus']);
          $responseCode = 200;
        } else { 
          $msgArr[] = "{$pdta['dialog']} DOES NOT EXIST IN FRAMEWORK.  SEE CHTNEASTERN INFORMATICS";
          $responseCode = 404;
        }
      } else { 
        $msgArr[] = "USER NOT ALLOWED";
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
    } 

    function consentdocquestions ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      if ( (int)$vuser->responsecode === 200 ) { 
        $pdta = json_decode( $passedData, true); 
        $cqDta = json_decode(callrestapi("GET", dataTreeSS . "/vault-consent-doc-questions/" . (int)$pdta['consentid'], serverIdent, serverpw, ""), true);
        if ( (int)$cqDta['ITEMSFOUND'] > 0 ) {
          //\"MESSAGE\":\"820\",\"ITEMSFOUND\":3,\"DATA\":[{\"menuvalue\":\"Q2.1\",\"dspvalue\":\"Consent Document Name\",\"additionalInformation\":\"TXT\"},{\"menuvalue\":\"Q2.2\",\"dspvalue\":\"Is Consent Signed?\",\"additionalInformation\":\"YN\"},{\"menuvalue\":\"Q2.3\",\"dspvalue\":\"Date of Signature\",\"additionalInformation\":\"TXD\"}]}}


          $consentQList = "";
          if ( (int)$cqDta['ITEMSFOUND'] > 0 ) { 
            foreach ( $cqDta['DATA'] as $cqk => $cqv ) { 

              switch ( $cqv['additionalInformation'] ) { 
                case "YN":
                  $ynMnu = "<table border=0 class=menuDropTbl>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}',1,'Yes');\" class=ddMenuItem>Yes</td></tr>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}',0,'No');\" class=ddMenuItem>No</td></tr>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}',3,'illegible/No Specified');\" class=ddMenuItem>illegible/No Specified</td></tr>";
                  $ynMnu .= "</table>";

                  $ansElemt = "<div><div class=elementmenu> <div class=elemental><input type=hidden id=\"ans{$cqv['mennuvalue']}Value\" value=\"\"><input type=text id=\"ans{$cqv['menuvalue']}\" value=\"\" style=\"width: 8vw;\"><div class=optionlisting style=\"width: 8vw;\">{$ynMnu}</div></div></div></div>";  
                  break;
                case "TXD":
                  $ansElemt = "<div><input type=\"text\" id=\"ans{$cqv['menuvalue']}\" style=\"width: 8vw;\"></div>";  
                  break;
                case "TXT":
                  $ansElemt = "<div><input type=\"text\" id=\"ans{$cqv['menuvalue']}\" style=\"width: 8vw;\"></div>";  
                  break;
                default:
                $ansElemt = "<div>&nbsp;</div>";  
              }         
              $consentQList .= "<div class=cqLine id='{$cqv['menuvalue']}'><div class=cqQstn>{$cqv['dspvalue']}</div>{$ansElemt}</div>";
            }
          }

          $dta = $consentQList;  
          $responseCode = 200;
        } else {
          $msgArr[] = "NO QUESTIONS FOR CONSENT FOUND ... SEE A CHTN INFORMATICS STAFF MEMBER (ERROR: VAULT001)";
        }
      } else { 
        $msgArr[] = "USER NOT ALLOWED";
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
    }

    function donorlookup ( $request, $passedData ) { 

      $responseCode = 503;  
      $vuser = new vaultuser();
      //{"responsecode":200,"userguid":"6ad4cb09-4eb1-44b2-b400-45bf59a4f9d9","userid":"zacheryv@mail.med.upenn.edu","friendlyname":"Zack","oaccount":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":43,"holder":""}
      if ( (int)$vuser->responsecode === 200 ) { 
        require( serverkeys . '/sspdo.zck');
        $pdta = json_decode( $passedData, true); 
        foreach ( $pdta as $k => $v ) {
          $locArr[cryptservice( $k, 'd')] = chtndecrypt($v);  
        }
        //{"fldFName":"z","fldLName":"z","fldMRN":"","fldORDte":"","fldCHTNNbr":""}

        ( !array_key_exists('fldFName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldFName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldLName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldLName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldMRN', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldMRN' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldORDte', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldORDte' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('fldCHTNNbr', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'fldCHTNNbr' DOES NOT EXIST.")) : "";        

        $srchid = generateRandomString(15);
        $srchRqSQL = "insert into ORSCHED.srchrequests (srchid, onwhen, reqby, srchrequests ) values(:srchid, now(), :reqby, :srchrequests )";
        $srchRqRS = $conn->prepare($srchRqSQL); 
        $srchRqRS->execute(array(':srchid' => $srchid, ':reqby' => json_encode($vuser), ':srchrequests' => json_encode($locArr) ));
        $dta = cryptservice($srchid,'e');
        $responseCode = 200;
      } else { 
        $responseCode = 501;  
        $msgArr[] = "USER NOT ALLOWED FUNCTION";
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
    } 

    function savenewdonor ( $request, $passedData ) { 
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      //{"responsecode":200,"userguid":"6ad4cb09-4eb1-44b2-b400-45bf59a4f9d9","userid":"zacheryv@mail.med.upenn.edu","friendlyname":"Zack","oaccount":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":43,"holder":""}
      if ( (int)$vuser->responsecode === 200 ) { 
        require( serverkeys . '/sspdo.zck');
        $pdta = json_decode( $passedData, true); 
        //{"nwdInstitution":"Hospital of The University of Pennsylvania","nwdProcDte":"12\/10\/2019","nwdFName":"Shawn","nwdLName":"Yakobina","nwdMRN":"234234234","nwdAge":"43","nwdAgeUOM":"Years","nwdRace":"White","nwdSex":"Male","nwdProcNote":"TEST TEST","nwdEncounterId":"912012-0293023-0992832","nwdDialogId":"sh3SN7TDnwdCFOZ"}
        foreach ( $pdta as $k => $v ) {
          $locArr[cryptservice( $k, 'd')] = chtndecrypt($v);  
        }

        ( !array_key_exists('nwdInstitution', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdInstitution' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdProcDte', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdProcDte' DOES NOT EXIST.")) : "";
        ( !array_key_exists('nwdFName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdFName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdLName', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdLName' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdMRN', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdMRN' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdAge', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdAge' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdAgeUOM', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdAgeUOM' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdRace', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdRace' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdSex', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdSex' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdProcNote', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdProcNote' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdEncounterId', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdEncounterId' DOES NOT EXIST.")) : ""; 
        ( !array_key_exists('nwdDialogId', $locArr) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  ARRAY KEY 'nwdDialogId' DOES NOT EXIST.")) : ""; 

        if ( $errorInd === 0 ) { 
          //Continue
          ( trim($locArr['nwdInstitution']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify an institution (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdProcDte']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify a Procedure/Schedule Date (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdFName']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the Donor's First Name (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdLName']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the Donor's Last Name (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdMRN']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the donor's Medical Record Number (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdAge']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the Donor's Age (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdAgeUOM']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify a Unit of Measure (UOM) for the age field (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdRace']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the Donor's Race (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdSex']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "You must specify the Donor's Sex (All fields denoted with * are required)")) : "";
          ( trim($locArr['nwdDialogId']) === "" )  ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  The Dialog Id is Blank")) : "";

          if ( $errorInd === 0 ) {
              //CONTINUE

             ( !ssValidateDate( $locArr['nwdProcDte'], 'm/d/Y') )  ? (list( $errorInd, $msgArr[] ) = array(1 , "Procedure/Schedule Date ({$locArr['nwdProcDte']}) is not valid!")) : ""; 
             ( !is_numeric($locArr['nwdAge']) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "The Donor's Age must be numeric ({$locArr['nwdAge']})")) : "";
             
             //TODO:  CHECK VALUES OF INSTITUTION / MENU DROP-DOWNS
             
             
                
              if ( $errorInd === 0 ) {
                  
                //CHECK OR SCHED
                $ORSchSQL = "SELECT orschedid FROM ORSCHED.ut_zck_ORSchds where forlocation = :location and date_format(ORDate,'%m/%d/%Y') = :dte";
                $ORSchRS = $conn->prepare($ORSchSQL);
                $ORSchRS->execute(array(':location' => trim($locArr['nwdInstitution']), ':dte' => trim($locArr['nwdProcDte']) ));
                if ( $ORSchRS->rowCount() <> 1 ) { 
                    //TODO: CREATE A NEW OR SCHEDULE
                    (list( $errorInd, $msgArr[] ) = array(1 , "An OR Schedule does not exist for this criteria - See CHTNEastern Informatics staff ({$locArr['nwdProcDte']} / {$locArr['nwdInstitution']})"));
                } else { 
                    $ORRS = $ORSchRS->fetch(PDO::FETCH_ASSOC);
                    $ORID = $ORRS['orschedid'];
                }
                  
                if ( $errorInd === 0 ) {
                  $donorid = "";  
                  if ( trim($locArr['nwdEncounterId']) !== "") { 
                    //ADD DONOR ID FROM SS
                    $donorid = trim($locArr['nwdEncounterId']);
                  }  else { 
                    //GENERATE NEW DONOR ID
                    $donorid = guidv4();
                  }
                  
                  $insSQL = "insert into ORSCHED.ut_zck_ORSchDetail (ORSchdid, ORSchdtid, patlast, patfirst, mrn, age, race, sex, procdetails, memo) values (:ORSchdid, :ORSchdtid, :patlast, :patfirst, :mrn, :age, :race, :sex, :procdetails, :memo)";
                  $insRS = $conn->prepare($insSQL);
                  $insRS->execute(array(  
                      ':ORSchdid' => $ORID
                      ,':ORSchdtid' => $donorid
                      ,':patlast' => strtoupper(trim($locArr['nwdLName']))
                      ,':patfirst' => strtoupper(trim($locArr['nwdFName']))
                      ,':mrn' => trim($locArr['nwdMRN'])
                      ,':age' => (int)trim($locArr['nwdAge'])
                      ,':race' => trim($locArr['nwdRace'])
                      ,':sex' => substr(trim($locArr['nwdSex']),0,1)
                      ,':procdetails' => trim($locArr['nwdProcNote']) . "\n\n-----------------\nAdded on " . date('m/d/Y') . ' through Donor Vault by ' .  $vuser->userid
                      ,':memo' => "Added on " . date('m/d/Y') . ' through Donor Vault by ' .  $vuser->userid
                  ));
                  
                  $msgArr[] = $locArr['nwdInstitution'] . " ... " . $donorid . " ... " . $ORID;
                  $responseCode = 200;
                }
              }
          }
        }
      } else { 
        $msgArr[] = "USER NOT ALLOWED";
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
    }

    function consentdocumentsearch ( $request, $passedData ) { 
      require( serverkeys . '/sspdo.zck');
      $responseCode = 503; 
      $pdta = json_decode($passedData,true);     
      //MENU OPTIONS FOR 'INFORMEDCONSENTWATCHSEARC' MENU
      $dtaSQL = "SELECT icid, selector, ucase(concat(ifnull(donorlname,''),', ', ifnull(donorfname,''))) as donorname, mrn, age, race, sex, if( ifnull(rqstrlname,'') ='','',concat(ifnull(rqstrlname,''),', ', ifnull(rqstrfname,''))) as rqstname, date_format(anticprocdate,'%m/%d/%Y') as anticprocdate, date_format(uploadon,'%m/%d/%Y') as uploaddate, consentdoctype FROM ORSCHED.ut_informed_consents";
      switch ( $pdta['typeOfConsentSearch'] ) { 
          case 'lastupload':
              $dtaSQL .= " where dspind = 1 order by icid desc limit 100";
              $dtaRS = $conn->prepare($dtaSQL);
              $dtaRS->execute();              
              $tos = 'Last 100 Uploaded Consents';
              break;
          case 'chtnnbr':
              $dtaSQL = "SELECT distinct ic.icid, selector, ucase(concat(ifnull(donorlname,''),', ', ifnull(donorfname,''))) as donorname, mrn, age, race, sex, if( ifnull(rqstrlname,'') ='','',concat(ifnull(rqstrlname,''),', ', ifnull(rqstrfname,''))) as rqstname, date_format(anticprocdate,'%m/%d/%Y') as anticprocdate, date_format(uploadon,'%m/%d/%Y') as uploaddate, consentdoctype FROM ORSCHED.ut_informed_consents ic left join ORSCHED.ut_informed_consents_biogroups cbg on ic.icid = cbg.icid where cbg.chtnnbr like :chtnnbr";
              $dtaRS = $conn->prepare($dtaSQL); 
              $dtaRS->execute( array( ':chtnnbr' => str_replace(" ","%",trim(chtndecrypt($pdta['sterm'])))  . "%"));
              $tos = 'CHTN Biosample Number Search';              
              break;
          case 'lastname':
              $dtaSQL .= " where dspind = 1 and donorlname like :lname order by donorlname";
              $dtaRS = $conn->prepare($dtaSQL);
              $dtaRS->execute( array( ':lname' => "%" . str_replace(" ","%",trim(chtndecrypt($pdta['sterm'])))  . "%")       );              
              $tos = 'Last Name Search';              
              break;
          case 'rqstr':
              $dtaSQL .= " where dspind = 1 and rqstrlname like :lname order by donorlname";
              $dtaRS = $conn->prepare($dtaSQL);
              $dtaRS->execute( array( ':lname' => "%" . str_replace(" ","%",trim(chtndecrypt($pdta['sterm'])))  . "%")       );              
              $tos = 'Consenter/Investigator Search';              
              break;
          case 'procdate':
              $dtaSQL .= " where dspind = 1 and date_format(anticprocdate,'%m/%d/%Y') = :pdte order by donorlname";
              $dtaRS = $conn->prepare($dtaSQL);
              $dtaRS->execute( array( ':pdte' => trim(chtndecrypt($pdta['sterm']))));              
              $tos = 'Anticipated Procedure Date Search';               
              break;
      }

      
      $itemsfound = $dtaRS->rowCount();
      $dnrCntr = 0;

      $qSQL = "select  concat(ifnull(qtxt,''),' [', ifnull(qid,''),']') as qtxt, answertxt FROM ORSCHED.ut_informed_consents_answers where icid = :icid";
      $qRS = $conn->prepare($qSQL);

      $bgSQL = "SELECT chtnnbr, uploadedby, date_format(uploadedon,'%m/%d/%Y') as uploadedon FROM ORSCHED.ut_informed_consents_biogroups where icid = :icid";
      $bgRS = $conn->prepare( $bgSQL ); 

      if ( $dtaRS->rowCount() > 0 ) {
        while ( $r = $dtaRS->fetch(PDO::FETCH_ASSOC)) { 
          $dta['donorlisting'][$dnrCntr] = $r;

          $qRS->execute(array( ':icid' => $r['icid'] ));
          if ( $qRS->rowCount() > 0 ) {
            while ( $a = $qRS->fetch(PDO::FETCH_ASSOC)) { 
              $dta['donorlisting'][$dnrCntr]['docAnswers'][] = $a;
            }
          } else { 
              $dta['donorlisting'][$dnrCntr]['docAnswers'][] = array( 'qtxt' => '', 'answertxt' => '' );
          }

          $bgRS->execute(array(':icid' => $r['icid']));
          if ( $bgRS->rowCount() > 0 ) {
            while ( $b = $bgRS->fetch(PDO::FETCH_ASSOC) ) { 
              $dta['donorlisting'][$dnrCntr]['bioGroups'][] = $b;
            }
          } else { 
            $dta['donorlisting'][$dnrCntr]['bioGroups'][] = array('chtnnbr' => '', 'uploadedby' => '', 'uploadedon' => '' );
          }

          $responseCode = 200;
          $dta['typeOfSearch'] = $tos;
          $dnrCntr++;
        }
      } else { 
        $msgArr[] = "NO CONSENT DOCUMENTS FOUND MATCHING YOUR CRITERIA";
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound,  'DATA' => $dta);
      return $rows;                 
    }

    function retrieveexclusionlisting ( $request, $passedData ) { 
      require( serverkeys . '/sspdo.zck');
      $responseCode = 503; 
      $itemsfound = 0;
      
      $exSQL = "SELECT ucase(concat(ifnull(patlast,''),', ',ifnull(patfirst,''))) as donor, mrn, memo, date_format(TissueTargetOn,'%m/%d/%Y') optouttargetdate, TissueTargetBy optoutaddedby  FROM ORSCHED.ut_zck_ORSchDetail where tissuetargetstatus = 'EXC' OR orschdid = 'EXCLUSIONLISTING' order by patlast"; 
      $exRS = $conn->prepare($exSQL); 
      $exRS->execute();
      
      $itemsfound = $exRS->rowCount();
      $dspTbl = <<<DSPTHIS
<div id=PNDPRTitle>UPHS DONORS OPT-OUT BIOSAMPLE SAMPLE COLLECTION - DO NOT COLLECT!</div>
<div id=resultCnt>Individual(s) Found: {$itemsfound}</div>    
<div id=dspWorkBenchTbl>
<table cellspacing=0 cellpadding=0>
DSPTHIS;
      if ( $itemsfound > 0 ) { 
          //BUILD TABLE
          while ( $r = $exRS->fetch(PDO::FETCH_ASSOC)) { 
             $dspTbl .= "<tr><td>{$r['mrn']}</td><td>{$r['donor']}</td><td>{$r['optouttargetdate']}</td><td>{$r['optoutaddedby']}</td><td>{$r['memo']}</td></tr>";   
          }
      }
      $dspTbl .= "</table></div>";
      $dta = $dspTbl;
      $responseCode = 200;
      
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound,  'DATA' => $dta);
      return $rows;                 
    }
    
    function retrievependingprlisting( $request, $passedData ) { 
        
      require( serverkeys . '/sspdo.zck');
      $responseCode = 503; 
      $rsltdta = callrestapi("POST", dataTreeSS . "/data-doers/vault-retrieve-pending-prs",serverIdent, serverpw, $passedData);          
      $dta = json_decode($rsltdta, true);
      
      if ((int)$dta['RESPONSECODE'] === 200 ) { 
          $dspTbl = <<<DSPTHIS
<div id=warningbar>THIS PAGE CONTAINS HIPAA INFORMATION. DO NOT PRINT!</div>
<div id=PNDPRTitle>BIOSAMPLES NEEDING PATHOLOGY REPORTS</div>
<div id=resultCnt>Items Found: {$dta['ITEMSFOUND']}</div>    
<div id=dspWorkBenchTbl>
DSPTHIS;

          $dspTbl .= "<table border=0 id=masterPRPTbl>";
	  $procdate = "";
	  $pxiSQL = "select distinct concat(ifnull(patLast,''),', ', ifnull(patfirst,'')) as pxiname, ifnull(mrn,'') as mrn, concat(ifnull(age,'-'),'/', ifnull(race,'-'),'/', ifnull(sex,'-')) as ars from ORSCHED.ut_zck_ORSchDetail where ORSchdtid = :pxiid";
          $pxiRS = $conn->prepare($pxiSQL);
          $rowcnt = 0; 
          foreach ( $dta['DATA'] as $ky => $v ) { 

            $pxiRS->execute(array(':pxiid' => $v['pxiid'] ));
            if ( $pxiRS->rowCount() > 0 ) { 
              $pxi = $pxiRS->fetch(PDO::FETCH_ASSOC);
	      $pxiname = strtoupper( $pxi['pxiname'] ) ;
	      $pximrn = $pxi['mrn'];
	    } else { 
	      $pxiname = "NO RECORD";
	      $pximrn = "NO RECORD";
	    }

            if ( $procdate !== $v['procurementdatedsp']) { 
              $dspTbl .= "<tr><td class=procMnth colspan=10>{$v['procurementdatedsp']}</td></tr>";
              $dspTbl .= "<tr><th>CHTN #</th><th>QMS<br>Status</th><th>Procedure</th><th>Institution</th><th>Procedure<br>Date</th><th>Diagnosis<br>Designation</th><th>Donor<br>Name</th><th>Donor<br>MRN</th><th>ScienceServer<br>ARS</th></tr>";
              $procdate = $v['procurementdatedsp'];
            }
               
            $dspTbl .= "<tr id=\"datarow{$rowcnt}\" class=PRDataRow data-pbiosample=\"{$v['readlabel']}\" data-desig=\"{$v['dx']}\" data-pxiid=\"{$v['pxiid']}\" data-selected=\"false\" onclick=\"rowselector(this.id);\">"
                     . "<td>{$v['readlabel']}</td>"
                     . "<td>{$v['qmsprocstatus']}</td>"
                     . "<td>{$v['proctype']}</td>"
                     . "<td>{$v['procureinstitution']}</td>"
                     . "<td>{$v['proceduredate']}</td>"
                     . "<td>{$v['dx']}</td>"
                     . "<td>{$pxiname}</td>"
                     . "<td>{$pximrn}</td>"
                     . "<td>{$v['ars']}</td>"
                     . "</tr>";
                     //. "<td>{$v['pxiid']}</td>"
            $rowcnt++;
 
          }
          $dspTbl .= "</table></div>";

//<div class=buttonContainer onclick="alert('click');"><div class=controlBarButton><i class="material-icons">description</i></div><div class=popupToolTip>Upload Pathology Report</div></div>

          $dspTbl .= <<<THISBAR
              <div id=prLocalBBar>
                <div class=buttonContainer onclick="markPRNo();"><div class=controlBarButton><i class="material-icons">block</i></div><div class=popupToolTip>Mark Pathology Report 'No'</div></div>
              </div>
THISBAR;
  
        $dta = $dspTbl;
        $responseCode = 200;          
      } else { 
        $msgArr[] = "UNSPECIFIED ERROR:  SEE CHTNED INFORMATICS";
        $msgArr[] = " " . json_encode( $dta );
      }      
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
    }
    
    function sendpxitoschedule ( $request, $passedData ) {
      $responseCode = 503;  
      $vuser = new vaultuser();
      $errorInd = 0;
      //{"responsecode":200,"userguid":"6ad4cb09-4eb1-44b2-b400-45bf59a4f9d9","userid":"zacheryv@mail.med.upenn.edu","friendlyname":"Zack","oaccount":"proczack","accesslevel":"ADMINISTRATOR","accessnbr":43,"holder":""}
      if ( (int)$vuser->responsecode === 200 ) { 
        require( serverkeys . '/sspdo.zck');
        $pdta = json_decode( $passedData, true); 
        
        $lookupSQL = <<<ORSCHEDSQL
SELECT 
ors.ORSchedid
, date_format(now(),'%Y-%m-%d') as ORDate
, ors.ForLocation as forLocation
, orschdtid
, ucase(concat(substr(ifnull(PatFirst,''),1,1),'.',substr(ifnull(patlast,''),1,1),'.')) as paini
, '' as starttime
, '' as surgeon
, '' as room
, ifnull(ord.Age,'') as age
, ifnull(ord.Race,'') as race
, ifnull(ord.sex,'') as sex 
, concat('ADDED ON DEMAND FROM DONORVAULT \n', ifnull(ord.ProcDetails,'')) as procdetails
, 'T' as proctargetstatus
, 0 as informedconsent
, substr(ifnull(ord.MRN,''),-4) as callbackref  
from ORSCHED.ut_zck_ORSchDetail ord
left join ORSCHED.ut_zck_ORSchds ors on ord.ORSchdid = ors.ORSchedid
where orschdtid = :pxiid      
ORSCHEDSQL;
        $orRS = $conn->prepare($lookupSQL);
        $cntr = 0;
        foreach ( $pdta as $v ) { 
            $orRS->execute(array(':pxiid' => $v ));
            while ($r = $orRS->fetch(PDO::FETCH_ASSOC)) { 
                $rtnArr[$cntr] = $r;
                $cntr++;
            }
        }
        if ( $cntr > 0 ) { 
            $env['philisting'] = $rtnArr;
            $sendThis = json_encode($env);
            $rslt = json_decode(callrestapi("POST", dataTreeSS . "/data-doers/save-linux-orsched-phi",serverIdent, serverpw,$sendThis),true);
            
            $responseCode = 200;
            
        } else { 
            $msgArr[] = "ERROR:  NO DONOR RECORDS FOUND MATCHING REQUEST.  SEE CHTNEASTERN INFORMATICS.";
        }
      } else { 
        $msgArr[] = "USER NOT ALLOWED (Logged Out)";
      }  
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;         
    } 
    
}

class dialogs { 

    public $closeBtn = "<i class=\"material-icons\">close</i>";


    /***********************************
    function dialogtemplate ( $dialogid ) {  
      $did = generateRandomString(15);
      $titleBar = "CHTNEastern Donor Vault - Add Donor Record";
      $closerAction = "closeThisDialog('{$did}')";
      //$closerAction = "alert('{$did}');";
      $innerDialog = "ZACK WAS HERE {$did} ";
      $footerBar = "";      
      $dialogContent = <<<DIALOGCONTENT
<table border=0 cellspacing=0 cellpadding=0>
<tr><td id=systemDialogTitle>{$titleBar}</td><td onclick="{$closerAction}" id=systemDialogClose>{$this->closeBtn}</td></tr>
<tr><td colspan=2>
  {$innerDialog}
</td></tr>
<tr><td colspan=2>{$footerBar}</td></tr>
</table>
DIALOGCONTENT;
      return array( "dialog" => $dialogContent, "dialogid" => $did, "left" => "15vw", "top" => "15vh", "primeFocus" => "" );
    }
    *********************************/
    
    function waiterthis ( $dialogid ) {  
      $did = generateRandomString(15);
      $titleBar = "<div id=dialogTitleBar>Please Wait ... </div>";
      
      
      $innerDialog = "<div id=donorClearTitleStatusDsp> This process searches both the entire CHTN Donor database, and if a donor's MRN is found, the data coordinator database is searched for a reference match.  This could take a while.  Please wait ... </div>";


      $footerBar = "";      
      $dialogContent = <<<DIALOGCONTENT
<table border=0 cellspacing=0 cellpadding=0>
<tr><td id=systemDialogTitle>{$titleBar}</td><td id=systemDialogClose>&nbsp;</td></tr>
<tr><td colspan=2>
  {$innerDialog}
</td></tr>
<tr><td colspan=2>{$footerBar}</td></tr>
</table>
DIALOGCONTENT;
      return array( "dialog" => $dialogContent, "dialogid" => $did, "left" => "15vw", "top" => "15vh", "primeFocus" => "" );
    }

    function markprno ( $passeddata ) {  
      $did = generateRandomString(15);
      $titleBar = "Mark Biogroup Pathology Report 'No'";
      $closerAction = "closeThisDialog('{$did}')";
      $pdta = json_decode( $passeddata, true); 
      $rqstDta = json_decode( $pdta['passeddata'], true);

      $bgListDsp = "";
      $bgArr = "";
      foreach ($rqstDta as $k => $v ) {
        $lbl = explode("::",$v);
        $cntrDsp = (int)$k + 1;
        $bgArr[] = $lbl[0];
        $bgListDsp .= "<div class=bgListItem><div class=bgNbring>{$cntrDsp}</div><div class=bgNbrDsp>{$lbl[0]}</div><div class=dxdesig>{$lbl[1]}</div></div>";
      }
      $bgArrStr = json_encode($bgArr);
      
      
      $innerDialog = <<<INNERDLOG
<input type=hidden value={$bgArrStr} id=bgListing>
<div id=mprnHolder>
<div class=tagLbls>Biogroups selected</div><div class=tagLbls>Reason for marking Biogroups Pathology Report=No</div>
<div id=bgListDsp>{$bgListDsp} </div>
<div id=workSide><textarea id=reasonGiven></textarea></div>
<div></div><div align=right> <table><tr><td><button onclick="sendPRMarkNo();">Update</button></td><td><button onclick="{$closerAction}">Cancel</button></td></tr></table> </div>
</div>
INNERDLOG;

      $footerBar = "";     
      $dialogContent = <<<DIALOGCONTENT
<table border=0 cellspacing=0 cellpadding=0>
<tr><td id=systemDialogTitle>{$titleBar}</td><td onclick="{$closerAction}" id=systemDialogClose>{$this->closeBtn}</td></tr>
<tr><td colspan=2>
  {$innerDialog}
</td></tr>
<tr><td colspan=2>{$footerBar}</td></tr>
</table>
DIALOGCONTENT;
      return array( "dialog" => $dialogContent, "dialogid" => $did, "left" => "15vw", "top" => "15vh", "primeFocus" => "" );
    }



    function createdonor( ) {  
      $did = generateRandomString(15);
      $titleBar = "CHTNEastern Donor Vault - Add Donor Record";
      $closerAction = "closeThisDialog('{$did}')";

      $tDte = date('m/d/Y');

      $instdta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/all-institutions",serverIdent, serverpw,""),true);  
      if ( (int)$instdta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $proc = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qryProcInst','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($instdta['DATA'] as $procval) { 
          $proc .= "<tr><td onclick=\"fillField('qryProcInst','{$procval['lookupvalue']}','{$procval['menuvalue']}');\" class=ddMenuItem>{$procval['menuvalue']} {$procval['lookupvalue']}</td></tr>";
        }
        $proc .= "</table>";
        $iMenu = "<div class=menuHolderDiv><input type=hidden id=qryProcInstValue><input type=text id=qryProcInst READONLY class=\"inputFld\" style=\"width: 15vw;\"><div class=valueDropDown style=\"min-width: 20vw;\">{$proc}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $agedta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/age-uoms",serverIdent, serverpw,""),true);  
      if ( (int)$agedta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $ageuom = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qryAgeUOM','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($agedta['DATA'] as $aval) { 
          $ageuom .= "<tr><td onclick=\"fillField('qryAgeUOM','{$aval['lookupvalue']}','{$aval['menuvalue']}');\" class=ddMenuItem>{$aval['menuvalue']}</td></tr>";
        }
        $ageuom .= "</table>";
        $aMenu = "<div class=menuHolderDiv><input type=hidden id=qryAgeUOMValue><input type=text id=qryAgeUOM READONLY class=\"inputFld\" style=\"width: 8vw;\"><div class=valueDropDown style=\"min-width: 15vw;\">{$ageuom}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $racedta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/pxi-race",serverIdent, serverpw,""),true);  
      if ( (int)$racedta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $r = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qryRace','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($racedta['DATA'] as $aval) { 
          $r .= "<tr><td onclick=\"fillField('qryRace','{$aval['lookupvalue']}','{$aval['menuvalue']}');\" class=ddMenuItem>{$aval['menuvalue']}</td></tr>";
        }
        $r .= "</table>";
        $rMenu = "<div class=menuHolderDiv><input type=hidden id=qryRaceValue><input type=text id=qryRace READONLY class=\"inputFld\"><div class=valueDropDown style=\"min-width: 15vw;\">{$r}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $sexdta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/pxi-sex",serverIdent, serverpw,""),true);  
      if ( (int)$sexdta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $s = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qrySex','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($sexdta['DATA'] as $sval) { 
          $s .= "<tr><td onclick=\"fillField('qrySex','{$sval['lookupvalue']}','{$sval['menuvalue']}');\" class=ddMenuItem>{$sval['menuvalue']}</td></tr>";
        }
        $s .= "</table>";
        $sMenu = "<div class=menuHolderDiv><input type=hidden id=qrySexValue><input type=text id=qrySex READONLY class=\"inputFld\"><div class=valueDropDown style=\"min-width: 15vw;\">{$s}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $innerDialog = <<<INNER
<div id=instructionHeader>All Fields Marked with an * Are Required</div>
<div id=donorAddHolder>
  <div class=elementhold>
    <div class=elementlbl>Institution <span class=rqrd>*</span></div>
    <div class=element>{$iMenu}</div>
  </div>
  <div class=elementhold>
    <div class=elementlbl>Procedure/Schedule Date <span class=rqrd>*</span></div>
    <div class=element><input type=text id=fldProcDate value="{$tDte}"></div>
  </div>
</div>

<div id=donorAddHolderLine2>
  <div class=elementhold>
    <div class=elementlbl>First Name <span class=rqrd>*</span></div>
    <div class=element><input type=text id=fldDnrFName value=""></div>
  </div>
  <div class=elementhold>
    <div class=elementlbl>Last Name <span class=rqrd>*</span></div>
    <div class=element><input type=text id=fldDnrLName value=""></div>
  </div>
  <div class=elementhold>
    <div class=elementlbl>Medical Record Nbr <span class=rqrd>*</span></div>
    <div class=element><input type=text id=fldDnrMRN value=""></div>
  </div>
</div>

<div id=donorAddHolderLine3>
  <div class=elementhold>
    <div class=elementlbl>Age <span class=rqrd>*</span></div>
    <table border=0 cellspacing=0 cellpadding=0><tr>
      <td><div class=element><input type=text id=fldDnrAge value="" style="width: 2vw; text-align: right;"></div></td>
      <td style="padding: 0 0 0 2px;"><div class=element>{$aMenu}</div></td>
    </tr>
    </table>
  </div>
  <div class=elementhold>
    <div class=elementlbl>Race <span class=rqrd>*</span></div>
    <div class=element>{$rMenu}</div>
  </div>
  <div class=elementhold>
    <div class=elementlbl>Sex <span class=rqrd>*</span></div>
    <div class=element>{$sMenu}</div>
  </div>
</div>

<div id=donorAddHolderLine5>
  <div class=elementhold>
    <div class=elementlbl>Procedure Notes</div>
    <div class=element><input type=text id=fldProcNote style="width: 100%;"></div>
  </div>
</div>

<div id=donorAddHolderLine4>
  <div class=elementhold>
    <div class=elementlbl>Encounter ID</div>
    <div class=element><input type=text id=fldPXIID style="width: 100%;"></div>
  </div>
</div>

<input type=hidden id=dialogIdentifier value="{$did}">

<div align=right id=buttonHolder>
  <table><tr><td><button onclick="saveNewDonor();">Save</button></td><td><button onclick="{$closerAction}">Cancel</button></td></tr></table>
</div>

INNER;



      $footerBar = "";      
      $dialogContent = <<<DIALOGCONTENT
<table border=0 cellspacing=0 cellpadding=0>
<tr><td id=systemDialogTitle>{$titleBar}</td><td onclick="{$closerAction}" id=systemDialogClose>{$this->closeBtn}</td></tr>
<tr><td colspan=2>
  {$innerDialog}
</td></tr>
<tr><td colspan=2>{$footerBar}</td></tr>
</table>
DIALOGCONTENT;
      return array( "dialog" => $dialogContent, "dialogid" => $did, "left" => "15vw", "top" => "15vh", "primeFocus" => "" );
    }

}

class systemposts {
    
    function sessionlogin($request, $passedData) {
      //{"ency":"e0qTMLncdiuAuKyAkZZ5Kfw3N9l2Z28HYSEa6GS8MCVvY6kRYVkuep9dipJ+mv4MIiqt2hEDDnC/Pl7gnxV3FOVfGU3F1jumwDOt4S0J9GH2/3VbKyQVEBZ4NagijWJWTi3Zf1vltWP6SDGYIvKVxn4rF869VkJEBLip5wwTbe8Fb8Qx//mRUzJ054iNvD8wvRFvpdyJBzGFXaj5csm3mPQvV3W5vATCNFAHTNOSplL3MFVj1A5c24Hm+HVuuBdB9n9j4ihIwzaUYxeO/j9tBQyEVRp8vW68KvZvlj1jxPggdnUjPB3MRroHl1Fh0lVwRx6OMnxXjiWO11nZ546XhQ=="}
      session_start();   
      $responseCode = 503;            
      $rsltdta = callrestapi("POST", dataTreeSS . "/data-doers/vault-user-login-pwcheck",serverIdent, serverpw, $passedData);  
      $rslt = json_decode( $rsltdta, true );
      if ( (int)$rslt['RESPONSECODE'] === 200 ) { 
        $dta = $rslt['DATA'];
        session_regenerate_id(true);
        $_SESSION['loggedin'] = 'true';
        $_SESSION['loggedid'] = $rslt['DATA'];
        $responseCode = 200;  
      } else {
        //{"RESPONSECODE":503,"MESSAGE":["USER NOT FOUND OR NOT ALLOWED ACCESS"],"ITEMSFOUND":0,"DATA":[]}  
        foreach ( $rslt['MESSAGE'] as $mky => $mvl ) {    
            $msgArr[] = $mvl;
        }
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;              
    }
    
} 

////////PAGE BUILDER SECTION 

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
  private $registeredPages = array('login','root','pendingpathologyreportlisting','donorlookup', 'consentwatch','donorexclusion');
  //THE SECURITY EXCPETIONS ARE THOSE PAGES THAT DON'T REQUIRE USER RIGHTS TO ACCESS
  private $securityExceptions = array('login');

function __construct() {
  $args = func_get_args();
   if (trim($args[0]) === "") {
   } else {
     if ( ( !isset($_SESSION['loggedid']) || trim($_SESSION['loggedid']) === "" ) && $args[0] !== "login") {
         $this->statusCode = 403;
     } else {
       $usrmetrics = $args[2];  //$usrmetric->username - Class from the index file defining the user
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
         //$this->menucontent = $pageElements['menu'];
         $this->modalrs = $pageElements['modalscreen'];
       } else {
         $this->statusCode = 404;

       }
     }
   }
}

function getPageElements($whichpage, $rqststr, $usrmetrics) {
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
  if ($whichpage !== "login") {
    $elArr['scripts']  =   (method_exists($js,'globalscripts') ? $js->globalscripts( "", "") : "");
  }
  $elArr['scripts']     .=   (method_exists($js,$whichpage) ? $js->$whichpage($rqststr) : "//NO JAVASCRIPT FILE FOUND ZZZZZZZZZZ");

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
    $chtnlogo = base64file("{$at}/chtn-microscope-white.png", "controlBarCHTNTopper", "png", true);
    $tt = treeTop;

    $globalcontrols = <<<CONTROLBARS

<div id=leftSideBar>
  <div class=buttonContainer onclick="navigateSite('');"><div id=logoHolder>{$chtnlogo}</div><div class=popupToolTip>Back to Root Screen</div></div>
  <div class=buttonContainer onclick="navigateSite('donor-lookup');"><div class=controlBarButton><i class="material-icons">account_circle</i></div><div class=popupToolTip>Donor Lookup</div></div>
  <div class=buttonContainer onclick="navigateSite('consent-watch');"><div class=controlBarButton><i class="material-icons">watch_later</i></div><div class=popupToolTip>Informed Consent Upload</div></div>
  <div class=buttonContainer onclick="navigateSite('donor-exclusion');"><div class=controlBarButton><i class="material-icons">warning</i></div><div class=popupToolTip>Donor Consent Exclusions</div></div>
  <div class=buttonContainer onclick="navigateSite('pending-pathology-report-listing');"><div class=controlBarButton><i class="material-icons">format_list_bulleted</i></div><div class=popupToolTip>Pending PR List</div></div>
  <div class="buttonContainer" onclick="window.location.href = '{$tt}/lgdestroy.php';"><div class="controlBarButtonExit"><i class="material-icons">exit_to_app</i></div><div class=popupToolTip>Log-Out</div></div>
<div id="countdown"></div>
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
    $this->teststring = "ZACK WAS HERE IN THE TEST STRING";
    $this->sessid = session_id();
//    $this->regcode = registerServerIdent(session_id());
  }

  function donorexclusion ($rqststr) { 
    $tt = treeTop;
    $dtaTree = dataTree . "/data-doers/";
    $dtaGTree = dataTreeSS;
    $eMod = eModulus;
    $eExpo = eExponent;
    
    $fldExFName = cryptservice('fldExFName','e');
    $fldExLName = cryptservice('fldExLName','e');
    $fldExMRN = cryptservice('fldExMRN','e');    
    $fldExDate = cryptservice('fldExDate','e');
    $fldExNote = cryptservice('fldExNote','e');
    $fldExTitle = cryptservice('fldClearTitle','e');
    
    $rtnThis = <<<JAVASCR
            
var key;
function bodyLoad() {
  setMaxDigits(262);
  key = new RSAKeyPair("{$eExpo}","{$eExpo}","{$eMod}", 2048);
}

document.addEventListener('DOMContentLoaded', function() {
  bodyLoad();
  if ( byId('fldExFName') ) { 
    byId('fldExFName').focus();
  }
  
  if ( byId('fldExMRN') ) {
    byId('fldExMRN').addEventListener('keyup',function() { 
      byId('fldClearTitle').value = "";
     byId('fldExMRN').style.background = "#ffffff";
    }, false);  
  }
  
  byId('standardModalBacker').style.display = 'block';
  universalAJAX("POST", "retrieveexclusionlisting", "", dspExclusionList, 1);
}, false);            
  
function dspExclusionList ( rtnData ) { 
  if ( byId('dataDsp') ) { 
    var rt = JSON.parse ( rtnData['responseText'] );
    if ( rt['ITEMSFOUND'] > 0 ) { 
       byId('dataDsp').innerHTML = rt['DATA'];
    }
  }
  if ( byId('standardModalBacker') ) { 
    byId('standardModalBacker').style.display = 'none';
  }
}
  
function sendExclusionRqst() { 
   generateDialog('waiterthis');
   var pdta = new Object();  
   pdta['{$fldExFName}'] = window.btoa( encryptedString ( key, byId('fldExFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExLName}'] = window.btoa( encryptedString ( key, byId('fldExLName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExMRN}'] = window.btoa( encryptedString ( key, byId('fldExMRN').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExDate}'] = window.btoa( encryptedString ( key, byId('fldExDate').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );   
   pdta['{$fldExNote}'] = window.btoa( encryptedString ( key, byId('fldExNote').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );   
   var passdata = JSON.stringify( pdta );
   universalAJAX("POST", "make-exclusion-rqst", passdata, exclusionrqstrslt, 2);   
}
            
function exclusionrqstrslt ( rtnData ) { 
    if (parseInt(rtnData['responseCode']) == 200) {
       //{"responseCode":200,"responseText":"{\"RESPONSECODE\":200,\"MESSAGE\":503,\"ITEMSFOUND\":0,\"DATA\":null}"}  
       var msgs = JSON.parse(rtnData['responseText']);
       if ( byId('dialogTitleBar') ) { 
          byId('dialogTitleBar').innerHTML = "Response From Server";
       }
       
       byId('donorClearTitleStatusDsp').innerHTML = "<div>"+msgs['MESSAGE']+"</div><div><button onclick=\"closeThisDialog('"+thisDspDialogId.trim()+"');\">Close</button></div>";
   }
}   
   
function searchPast() {
   byId('fldClearTitle').value = "";
   generateDialog('waiterthis');  
   var pdta = new Object();  
   pdta['{$fldExFName}'] = window.btoa( encryptedString ( key, byId('fldExFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExLName}'] = window.btoa( encryptedString ( key, byId('fldExLName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExMRN}'] = window.btoa( encryptedString ( key, byId('fldExMRN').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
   pdta['{$fldExDate}'] = window.btoa( encryptedString ( key, byId('fldExDate').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );   
   pdta['{$fldExNote}'] = window.btoa( encryptedString ( key, byId('fldExNote').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );   
   var passdata = JSON.stringify( pdta );
   universalAJAX("POST", "check-clear-exclusion-title", passdata, statussearchpast, 2);
}
   
function statussearchpast ( rtnData ) { 
  if (parseInt(rtnData['responseCode']) !== 200) {
    if (parseInt(rtnData['responseCode']) === 404) {
     byId('fldClearTitle').value = "CLEAR";
     byId('fldExMRN').style.background = "#d8ffc2";
     byId('donorClearTitleStatusDsp').innerHTML = "<div>The entered MRN does NOT exist in the donor vault.  If the entered MRN is correct then you may enter this donor to the exclusion list.</div><div><button onclick=\"closeThisDialog('"+thisDspDialogId.trim()+"');\">Close</button></div>"; 
    } else {  
      var msgs = JSON.parse(rtnData['responseText']);
      var dspMsg = ""; 
      msgs['MESSAGE'].forEach(function(element) { 
       dspMsg += element;
      });
      //if ( thisDspDialogId.trim() !== "" ) { 
      //  byId(thisDspDialogId.trim()).parentNode.removeChild(byId(thisDspDialogId.trim()));
      //}
      var bglist = dspMsg.split(",");
      var bgs = "";
      bglist.forEach(function(bgelem) {
        bgs += "<br>- "+bgelem
      });
      byId('donorClearTitleStatusDsp').innerHTML = "<b>IMMEDIATELY NOTIFY A CHTN MANAGER!</b><p>THIS EXCLUSION HAS HAD TISSUE COLLECTED AT A POINT! THE FOLLOWING IS A BIOGROUP LISTING: <br>"+bgs+"<p><button onclick=\"closeThisDialog('"+thisDspDialogId.trim()+"');\">Close</button>";
      byId('fldClearTitle').value = "BAD";
      byId('fldExMRN').style.background = "#ffbeb5"; 
      //byId('standardModalBacker').style.display = 'none'; 
    } 
   } else {
     byId('fldClearTitle').value = "CLEAR";
     byId('fldExMRN').style.background = "#d8ffc2";
     byId('donorClearTitleStatusDsp').innerHTML = "No Donor Reference Found. You can add this donor to the exclusion list without an issue.<button onclick=\"closeThisDialog('"+thisDspDialogId.trim()+"');\">Close</button>"; 
   }   
}
               
JAVASCR;
    return $rtnThis;
      
  }
  
  function globalscripts( $rqststr ) {

    $tt = treeTop;
    $dtaTree = dataTree . "/data-doers/";
    $dtaGTree = dataTreeSS;
    $regUsr = session_id();
    $regCode = dvRegisterServerIdent ( $regUsr );

    $si = serverIdent; 
    $sp = serverpw;
    
    $rtnThis = <<<JAVASCR

window.addEventListener("unload", logData, false);

function logData() {
  //navigator.sendBeacon("lgdestroy.php", "");
}

var byId = function( id ) { return document.getElementById( id ); };
var treeTop = "{$tt}";
var dataPath = "{$dtaTree}";
var dataGPath = "{$dtaGTree}";
var mousex;
var mousey;
var regu = "{$regUsr}";
var regc = "{$regCode}";

var httpage = getXMLHTTPRequest();
var httpdialog = getXMLHTTPRequest();
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

function dialogAJAX(methd, url, passedDataJSON, callbackfunc, dspBacker) {
  if (dspBacker === 1) {
    byId('standardModalBacker').style.display = 'block';
  }
  var rtn = new Object();
  var grandurl = dataPath+url;
  httpdialog.open(methd, grandurl, true);
  httpdialog.setRequestHeader("Authorization","Basic " + btoa("{$regUsr}:{$regCode}"));
  httpdialog.onreadystatechange = function() {
    if (httpdialog.readyState === 4) {
      rtn['responseCode'] = httpdialog.status;
      rtn['responseText'] = httpdialog.responseText;
      if (parseInt(dspBacker) < 2) {
        byId('standardModalBacker').style.display = 'none';
      }
      callbackfunc(rtn);
    }
  };
  httpdialog.send(passedDataJSON);
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

document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('keypress', function(event) {
    timeleft = 600;
  }, false);

}, false);

var timeleft = 600;
var downloadTimer = setInterval(function(){
  byId("countdown").innerHTML = Math.floor ( timeleft / 60 ) + ":" + ( timeleft )      ;
  timeleft -= 1;
  if(timeleft <= 0){
    clearInterval(downloadTimer);
    window.location.replace('{$tt}/lgdestroy.php');
    byId("countdown").innerHTML = "Finished"
  }
}, 1000);

document.addEventListener('mousemove', function(e) {
  mousex = e.pageX;
  mousey = e.pageY;
  timeleft = 600;
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

function fillField( whichfieldId, whatvalue, whatdsp ) { 
  if ( byId(whichfieldId) ) { 
    if ( byId(whichfieldId+'Value') ) { 
      byId(whichfieldId+'Value').value = whatvalue;
    }
    if ( byId(whichfieldId+'Val') ) { 
      byId(whichfieldId+'Val').value = whatvalue;
    }
    byId(whichfieldId).value = whatdsp;
  }
  switch ( whichfieldId ) { 
    case 'cDoc':
      byId('consentquestions').innerHTML = "";
      var pdta = new Object();  
      pdta['consentid'] = whatvalue;
      var passdata = JSON.stringify(pdta);
      universalAJAX("POST", "consent-doc-questions", passdata, chngConsentQuestions, 1);
    break;
  }
}

function chngConsentQuestions ( rtnData ) {

  if (parseInt(rtnData['responseCode']) !== 200) { 
    var msgs = JSON.parse(rtnData['responseText']);
    var dspMsg = ""; 
    msgs['MESSAGE'].forEach(function(element) { 
       dspMsg += "\\n - "+element;
    });
    alert("ERROR:\\n"+dspMsg);
    byId('standardModalBacker').style.display = 'none';    
   } else {
     var dta = JSON.parse( rtnData['responseText'] ); 
     byId('consentquestions').innerHTML = dta['DATA'];
   }

}


function generateDialog ( whichdialog, passedData = "" ) {
  closeThisDialog(thisDspDialogId)
  thisDspDialogId = ""; 
  var pdta = new Object();  
  pdta['dialog'] = whichdialog;
  pdta['passeddata'] = passedData;
  var passdata = JSON.stringify(pdta);
  dialogAJAX("POST", "generate-dialog",passdata, dspDialog, 1);
}

var thisDspDialogId = "";
function dspDialog( rtnData ) { 
  if (parseInt(rtnData['responseCode']) !== 200) { 
    var msgs = JSON.parse(rtnData['responseText']);
    var dspMsg = ""; 
    msgs['MESSAGE'].forEach(function(element) { 
       dspMsg += "\\n - "+element;
    });
    alert("ERROR:\\n"+dspMsg);
    byId('standardModalBacker').style.display = 'none';    
   } else {
        var dta = JSON.parse(rtnData['responseText']);         
        //TODO: MAKE SURE ALL ELEMENTS EXIST BEFORE CREATION
        var d = document.createElement('div');
        d.setAttribute("id", dta['DATA']['dialogID']); 
        thisDspDialogId = dta['DATA']['dialogID'];
        d.setAttribute("class","floatingDiv");
        d.style.left = dta['DATA']['left'];
        d.style.top = dta['DATA']['top'];
        d.innerHTML = dta['DATA']['pageElement']; 
        document.body.appendChild(d);
        byId(dta['DATA']['dialogID']).style.display = 'block';
        if ( dta['DATA']['primeFocus'].trim() !== "" ) { 
          byId(dta['DATA']['primeFocus'].trim()).focus();
        }
        byId('standardModalBacker').style.display = 'block';
  }
}

function closeThisDialog(dlog) {
  if ( byId(dlog) ) {
   byId(dlog).parentNode.removeChild(byId(dlog));
  }
  byId('standardModalBacker').style.display = 'none';
}

JAVASCR;
    return $rtnThis;
  }

  function pendingpathologyreportlisting ($rqststr) {

    $rtnThis = <<<JAVASCR

document.addEventListener('DOMContentLoaded', function() {
  universalAJAX("POST", "retrievependingprlisting", "", dspRetrievedPRs, 1);
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

function rowselector ( whichrow ) { 
  if ( byId( whichrow ).dataset.selected == 'true'  ) {
    byId( whichrow ).dataset.selected = "false";
  } else { 
    byId( whichrow ).dataset.selected = "true";
  }
}

function markPRNo() { 
  var pxlist = [];
  var pxicnt = 0;       
  if ( byId('masterPRPTbl') ) {
     var tds = document.querySelectorAll('.PRDataRow');
        for (var c = 0; c < tds.length; c++) {  
          if ( tds[c].dataset.selected === 'true') { 
             pxlist.push(tds[c].dataset.pbiosample+"::"+tds[c].dataset.desig);
             pxicnt++;
          }
        }            
  if ( parseInt(pxicnt) > 0 ) { 
    var passdata = JSON.stringify(pxlist);
    generateDialog('mark-pr-no', passdata); 
  } else { 
    alert("You have not selected any biogroups to mark as NOT needing a Pathology Report");
  }                        
  }
}
            
function sendPRMarkNo() { 
  var pdta = new Object();  
  pdta['bglist'] = byId('bgListing').value;
  pdta['reason'] = byId('reasonGiven').value; 
  var passdata = JSON.stringify(pdta);
  universalAJAX("POST", "send-pr-mark-no", passdata, statussendprmarkno, 2);
}

function statussendprmarkno ( rtnData ) { 
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        location.reload(true);
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

//{"MESSAGE":"q1.1","ITEMSFOUND":1,"DATA":[{"menuvalue":"Q1.1_yn","dspvalue":"Allow to collect, store and distribute tissue?","additionalInformation":"YN"}]}


  function consentwatch ( $rqststr ) { 
    $tt = treeTop;
    $eMod = eModulus;
    $eExpo = eExponent;
    $fldDFName = cryptservice('fldDonorFName','e');
    $fldDLName = cryptservice('fldDonorLName','e');
    $fldDMRN = cryptservice('fldDonorMRN','e');
    $fldCHTNList = cryptservice('fldCHTNList','e');

    $fldDAge = cryptservice('fldDnrAge','e');
    $fldDAgeUOM = cryptservice('fldDnrAgeUOM','e');
    $fldDRace = cryptservice('fldDnrRace','e');
    $fldDSex = cryptservice('fldDnrSex','e');
    
    $fldIFName = cryptservice('fldIFName','e');
    $fldILName = cryptservice('fldILName','e');
    $fldProcDte = cryptservice('fldProcDte','e');

    $fldconsent = cryptservice('consentfile','e');   
    $fileselector = cryptservice('fileselector','e');
////pdta['{$fldCHTNList}'] = window.btoa( encryptedString ( key, byId('fldCHTNList').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
    $rtnThis = <<<JAVASCR

var key;
function bodyLoad() {
  setMaxDigits(262);
  key = new RSAKeyPair("{$eExpo}","{$eExpo}","{$eMod}", 2048);
}

document.addEventListener('DOMContentLoaded', function() {
  bodyLoad();
  if ( byId('fldDonorFName') ) { 
    byId('fldDonorFName').focus();
  }
}, false);

function consentcancel() { 
  location.reload(true);
}

function validateConsentForm() { 
  var form = document.forms.namedItem("consentDocFrm");
  oData = new FormData(form);
  oData.append("{$fldDFName}", window.btoa( encryptedString ( key, byId('fldDonorFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDLName}", window.btoa( encryptedString ( key, byId('fldDonorLName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDMRN}", window.btoa( encryptedString ( key, byId('fldDonorMRN').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDAge}", window.btoa( encryptedString ( key, byId('fldDnrAge').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDAgeUOM}", window.btoa( encryptedString ( key, byId('qryAgeUOMValue').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDRace}", window.btoa( encryptedString ( key, byId('qryRaceValue').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldDSex}", window.btoa( encryptedString ( key, byId('qrySexValue').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldIFName}", window.btoa( encryptedString ( key, byId('fldIFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldILName}", window.btoa( encryptedString ( key, byId('fldILName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append("{$fldProcDte}", window.btoa( encryptedString ( key, byId('fldProcDte').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ));
  oData.append( 'consentdoc', byId('cDoc').value );
  var named = byId("consentquestions"); 
  var elem = named.getElementsByTagName("input");
  for (var i = 0, n = elem.length; i < n; i = i + 1) {
    oData.append( elem[i].id , elem[i].value.trim());
   }  
  byId('standardModalBacker').style.display = 'block';
  var oReq = new XMLHttpRequest();
  oReq.open("POST",dataPath+"stash-consent", true);
  oReq.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regc));
  oReq.onload = function(oEvent) {
  if ( parseInt(oReq.status) === 200) {

    } else {
      alert('ERROR EXISTS');
    }
    byId('standardModalBacker').style.display = 'none';
  };
  oReq.send(oData);
}
  
function updateConsentResponse( rtnData ) {
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        alert('Informed Consent Document Saved to Database');     
        location.reload(true);
      }
}

function openAnswers( whichicid ) { 
  var divsToHide = document.getElementsByClassName("answerDisplayer"); //divsToHide is an array
  for (var i = 0; i < divsToHide.length; i++){
    divsToHide[i].style.display = "none";
  }
  byId(whichicid).style.display = 'block';
}

function openBiogroups( whichicid ) { 
  var divsToHide = document.getElementsByClassName("answerDisplayer"); //divsToHide is an array
  for (var i = 0; i < divsToHide.length; i++){
    divsToHide[i].style.display = "none";
  }
  byId(whichicid).style.display = 'block';
}


function closeAnswers( whichicid ) { 
  byId('aDisplayer'+whichicid).style.display = 'none';
}

function closeBiogroups( whichicid ) { 
  byId('bDisplayer'+whichicid).style.display = 'none';
}

function addConsentBiogroup ( selector, icid ) {
  var pdta = new Object();  
  pdta['{$fileselector}'] = window.btoa( encryptedString ( key, selector, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
  pdta['bgroupdelimit'] =   byId('bGroupDelimit'+icid).value.trim();
  pdta['icid'] =            icid;
  var passdata = JSON.stringify(pdta);
  universalAJAX("POST", "mark-masterrecord-consents", passdata, verifymarkconsents, 1);
}

function verifymarkconsents ( rtnData ) { 
  var r = JSON.parse(rtnData['responseText']);
    if ( parseInt(r['RESPONSECODE']) !== 200 ) {
      var msg = r['MESSAGE'];
      var dspMsg = "";
      msg.forEach(function(element) {
        dspMsg += "\\n - "+element;
      });
      alert(dspMsg);
    } else {
      alert('ScienceServer Main Database has been updated with consents specified.  To see changes, refresh your screen or re-run your query');
   }
}

function getPDFDoc( selector ) { 
  var pdta = new Object();  
  pdta['{$fileselector}'] = window.btoa( encryptedString ( key, selector, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
  var passdata = JSON.stringify ( pdta );
  universalAJAX("POST", "get-Consent-Document", passdata, displayConsentDocument, 1);
}

function displayConsentDocument( rtnData ) {
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        var d = document.createElement('div');
        d.setAttribute("id", r['DATA']['dialogid']  );
        d.setAttribute("class","floatingDiv");
        d.style.width = '80vw';
        d.style.marginLeft = '-40vw';
        d.style.left = '50%';
        d.style.marginTop = '-35vh';
        d.style.top = '50%';
        d.innerHTML = '<div class=closeDialogBar style=\"height: 2vh;\" onclick="closeThisDialog(\''+r['DATA']['dialogid']+'\');">&times; close</div><object data="' + r['DATA']['pdfstring'] + '" style="width: 100%; height: 67vh;">';
        document.body.appendChild(d);
        byId(r['DATA']['dialogid']).style.display = 'block';  
        byId('standardModalBacker').style.display = 'block';
      }
}

  function closeThisDialog( dialogid ) { 
   byId(dialogid).parentNode.removeChild(byId(dialogid));
   byId('standardModalBacker').style.display = 'none'; 
  }

  function ondemandSearchConsent() { 
    var pdta = new Object();  
    pdta['typeOfConsentSearch'] = byId('qryICDocSrchValue').value;
    pdta['sterm'] = window.btoa( encryptedString ( key, byId('qryICDocTerm').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
    var passdata = JSON.stringify ( pdta );
    universalAJAX("POST", "consent-document-search", passdata, displayConsentList, 1);
  }
  
  function displayConsentList ( rtnData )  {
      byId('typeOfICDSearch').innerHTML = "";
      byId('rsltOfICDSearch').innerHTML = "";
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        byId('typeOfICDSearch').innerHTML = r['DATA']['typeOfSearch'] + " ("+r['ITEMSFOUND']+" donor(s) found)";
        var icdRsltTbl = "<table border=0 id=icSearchRsltTbl><tr><th>Consent Type</th><th>Ans</th><th>Bio</th><th>Doc</th><th>Donor</th><th>MRN</th><th>A/R/S</th><th>Uploaded</th><th>Procedure Date</th><th>Investigator From</th></tr>";


        r['DATA']['donorlisting'].forEach ( function (element) {

          var bioTbl = "<table border=0 style=\"width: 30vw;\" ><tr><td colspan=2 class=closeDialogBar onclick=\"closeBiogroups('"+element['icid']+"');\">&times; close</td></tr>";
          bioTbl += "<tr><td style=\"background: rgba(255,255,255,1); border: none;\"><div><div class=fieldLabel>CHTN Biogroup (Comma Delimit)</div><div><input type=text id='bGroupDelimit"+element['icid']+"' style='width: 25vw;'><button style=\"margin-left: .3vw;\" onclick=\"addConsentBiogroup('"+element['selector']+"','"+element['icid']+"');\">Attach</button></div></div></td></tr><tr><td class=sectionBar>ATTACHED BIOGROUPS</td></tr>";
          element['bioGroups'].forEach( function ( bv ) {
            if ( bv['chtnnbr'].trim() !== "" ) {
              bioTbl += "<tr><td>"+ bv['chtnnbr'] + " (" +bv['uploadedby']+"::"+bv['uploadedon'] +")</td></tr>";
            }
          });
          bioTbl += "</table>" ;

          var ansTbl = "<table border=0 style=\"width: 30vw;\" ><tr><td colspan=2 class=closeDialogBar onclick=\"closeAnswers('"+element['icid']+"');\">&times; close</td></tr><tr><th>Consent Question</th><th>Answer</th></tr>";
          element['docAnswers'].forEach( function ( av ) {
            ansTbl += "<tr><td>"+av['qtxt']+"</td><td>"+av['answertxt']+"</td></tr>";
          });
          ansTbl += "</table>";

          var aprocDte = ( element['anticprocdate'].trim() === '01/01/1900' ) ? "-" : element['anticprocdate'];
          var rqstr = ( element['rqstname'].trim() === '' ) ? "-" : element['rqstname'].trim().toUpperCase();

          icdRsltTbl += "<tr><td>" + element['consentdoctype'] + "</td>"; 
          icdRsltTbl += "<td><div class=addInfoHolder><div onclick=\"openAnswers('aDisplayer"+ element['icid'] +"');\"><center><i class=\"material-icons\">contact_support</i></div><div class=answerDisplayer id='aDisplayer"+ element['icid'] + "'>"+ansTbl+"</div></div></td>";
          icdRsltTbl += "<td><div class=addInfoHolder><div onclick=\"openBiogroups('bDisplayer"+ element['icid'] +"');\"><center><i class=\"material-icons\">extension</i></div><div class=answerDisplayer id='bDisplayer"+element['icid'] + "'>"+bioTbl+"</div></div></td>"
          icdRsltTbl += "<td><div class=addInfoHolder><div onclick=\"getPDFDoc('"+element['selector']+"');\"><center><i class=\"material-icons\">description</i></div><div class=answerDisplayer id='aDisplayer"+element['icid']+"'>PDF Doc</div></div></td>";
          icdRsltTbl += "<td>"+element['donorname']+"</td><td>"+element['mrn']+"</td><td>"+element['age']+"/"+element['race']+"/"+element['sex']+"</td><td>"+element['uploaddate']+"</td><td>"+aprocDte+"</td><td>"+rqstr+"</td>";
          icdRsltTbl += "</tr>";
        });

        icdRsltTbl += "</table>";
        byId('rsltOfICDSearch').innerHTML = icdRsltTbl;
      }     
  }
  
JAVASCR;
    return $rtnThis;
  }

  function donorlookup ( $rqststr ) {
    $tt = treeTop;
    $eMod = eModulus;
    $eExpo = eExponent;
    $fldFName = cryptservice('fldFName','e');
    $fldLName = cryptservice('fldLName','e');
    $fldMRN = cryptservice('fldMRN','e');
    $fldORDte = cryptservice('fldORDte','e');
    $fldCHTNNbr = cryptservice('fldCHTNNbr','e');

    $nwdInstitution = cryptservice('nwdInstitution','e');
    $nwdProcDte = cryptservice('nwdProcDte','e');
    $nwdFName = cryptservice('nwdFName','e');
    $nwdLName = cryptservice('nwdLName','e');
    $nwdMRN = cryptservice('nwdMRN','e');
    $nwdAge = cryptservice('nwdAge','e');
    $nwdAgeUOM = cryptservice('nwdAgeUOM','e');
    $nwdRace = cryptservice('nwdRace','e');
    $nwdSex = cryptservice('nwdSex','e');
    $nwdProcNote = cryptservice('nwdProcNote','e');
    $nwdEncounterId = cryptservice('nwdEncounterId','e');
    $nwdDialogId = cryptservice('nwdDialogId','e');

    $rtnThis = <<<JAVASCR

    var key;
    function bodyLoad() {
      setMaxDigits(262);
      key = new RSAKeyPair("{$eExpo}","{$eExpo}","{$eMod}", 2048);
    }

    document.addEventListener('DOMContentLoaded', function() {

      bodyLoad();

      if ( byId('fldFName') ) { 
        byId('fldFName').focus();
      }

    }, false);
    
  function saveNewDonor() { 
    var pdta = new Object();  
    pdta['{$nwdInstitution}'] = window.btoa(encryptedString(key, byId('qryProcInstValue').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdProcDte}'] = window.btoa(encryptedString(key, byId('fldProcDate').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdFName}'] = window.btoa(encryptedString(key, byId('fldDnrFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdLName}'] = window.btoa(encryptedString(key, byId('fldDnrLName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdMRN}'] = window.btoa(encryptedString(key, byId('fldDnrMRN').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdAge}'] = window.btoa(encryptedString(key, byId('fldDnrAge').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdAgeUOM}'] = window.btoa(encryptedString(key, byId('qryAgeUOM').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdRace}'] = window.btoa(encryptedString(key, byId('qryRace').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdSex}'] = window.btoa(encryptedString(key, byId('qrySex').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdProcNote}'] = window.btoa(encryptedString(key, byId('fldProcNote').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdEncounterId}'] = window.btoa(encryptedString(key, byId('fldPXIID').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    pdta['{$nwdDialogId}'] = window.btoa(encryptedString(key, byId('dialogIdentifier').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding));
    var passdata = JSON.stringify(pdta);
    universalAJAX("POST", "save-new-donor", passdata, savedonorcomplete, 2);
  }

  function savedonorcomplete( rtnData ) { 
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        location.reload(true);
      }
  }

    function sendSrchRqst() {
      var pdta = new Object();  
      pdta['{$fldFName}'] = window.btoa( encryptedString ( key, byId('fldFName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) );
      pdta['{$fldLName}'] = window.btoa( encryptedString ( key, byId('fldLName').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ); 
      pdta['{$fldMRN}'] = window.btoa( encryptedString ( key, byId('fldMRN').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ); 
      pdta['{$fldORDte}'] = window.btoa( encryptedString ( key, byId('fldORDte').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ); 
      pdta['{$fldCHTNNbr}'] = window.btoa( encryptedString ( key, byId('fldCHTNNbr').value, RSAAPP.PKCS1Padding, RSAAPP.RawEncoding ) ); 
      var passdata = JSON.stringify(pdta);
      universalAJAX("POST", "donor-lookup", passdata, dspdonorlookupresults, 1);
    }
 
    function dspdonorlookupresults( rtnData ) { 
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        navigateSite('donor-lookup/'+r['DATA']); 
      }
    }

    function rowselector ( whichrow ) { 
      if ( byId( whichrow ).dataset.selected == 'true'  ) {
        byId( whichrow ).dataset.selected = "false";
      } else { 
        byId( whichrow ).dataset.selected = "true";
      }
    }

    function sendToTodaysSchedule() {  
      var pxlist = [];
      var pxicnt = 0;
      if ( byId('donorDataDsp') ) { 
        var tds = document.querySelectorAll('.pxidatarow');
        for (var c = 0; c < tds.length; c++) {  
          if ( tds[c].dataset.selected === 'true') { 
             pxlist.push(tds[c].dataset.pxiid);
             pxicnt++;
          }
        }
        if ( parseInt(pxicnt) > 0 ) { 
          var passdata = JSON.stringify(pxlist);
          console.log ( passdata ); 
          universalAJAX("POST", "send-pxi-to-schedule", passdata, acknowledgepxisend, 2);
        } else { 
         alert("You have not selected any donor's to send to the schedule");
        }
      }
  }      
      
  function acknowledgepxisend( rtnData ) { 
      var r = JSON.parse(rtnData['responseText']);
      if ( parseInt(r['RESPONSECODE']) !== 200 ) {
        var msg = r['MESSAGE'];
        var dspMsg = "";
        msg.forEach(function(element) {
          dspMsg += "\\n - "+element;
        });
        alert(dspMsg);
      } else {
        alert('Donor added to today\'s schedule.  Refresh the \'Procure Biogroup\' screen in ScienceServer to see the updated schedule');
      }
   }


JAVASCR;
    return $rtnThis;
  }

}

class pagecontent {

  function generateHeader( $whichpage ) {
    $tt = treeTop;
    $at = genAppFiles;
    $jsscript =  base64file( "{$at}/Barrett.js" , "", "js", true);
    $jsscript .= "\n" . base64file( "{$at}/BigInt.js" , "", "js");
    $jsscript .= "\n" . base64file( "{$at}/RSA.js" , "", "js");
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


  //<button id=btnSrch class=btnAction onclick="searchPast();">Search</button>
  function donorexclusion ( $rqst, $usr ) { 



    $rt = <<<PGCONTENT

<div id=exclusionTitle>Donor Consent Opt-Out/Exclusions</div>
<div id=instructionBlock><b>Instructions</b>:  On this screen, enter donors who have informed UPHS of consent opt-out/exclusion.  <p>Anyone on this list MAY NOT HAVE ANY BIOSAMPLES COLLECTED FROM THEIR PROCEDURES!  Immediately notify CHTNEastern Management if an 'Add Exclusion' action results in biosamples returned.  


</div>

<div id=frmHolder>
<input type=hidden id=fldClearTitle value="">
<div class=elementHolder>
  <div class=elementLbl>First Name</div>
  <div><input type=text id=fldExFName></div>
</div>

<div class=elementHolder>
  <div class=elementLbl>Last Name</div>
  <div><input type=text id=fldExLName></div>
</div>

<div class=elementHolder>
  <div class=elementLbl>MRN</div>
  <div><input type=text id=fldExMRN></div>
</div>

<div class=elementHolder>
  <div class=elementLbl>Date Opt-Out (mm/dd/YYYY)</div>
  <div><input type=text id=fldExDate></div>
</div>

<div class=elementHolder>
  <div class=elementLbl>Exclusion/Opt-Out Notes</div>
  <div><input type=text id=fldExNote></div>
</div>
            
            
<div class=elementHolderA>  </div>
<div class=elementHolderA> <button id=btnAdd class=btnAction onclick="sendExclusionRqst();">Add Exclusion</button> </div>

</div>
  <div id=warningbar>THIS PAGE CONTAINS HIPAA INFORMATION. DO NOT PRINT!</div>
<div id=dataDsp>
    Please wait as we retrieve the exclusion listing ...
</div>
PGCONTENT;
    return $rt;
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
   
    $r = explode('/', $_SERVER['REQUEST_URI']);
    $valFN = "";
    $valLN = "";
    $valMR = "";
    $valOR = "";
    $valCH = "";
    if ( trim($r[2]) !== "" ) {
      require( serverkeys . '/sspdo.zck');
      $sql = "SELECT reqby, srchrequests FROM ORSCHED.srchrequests where srchid = :srchid";
      $rs = $conn->prepare($sql); 
      $rs->execute(array(':srchid' => cryptservice($r[2],'d') ));
      if ( $rs->rowCount() < 1 ) { 
        $rdsp = "<div id=datadspdiv>FATAL ERROR:  NO SEARCH ID FOUND IN DATABASE.</div>";
      } else {
        $s = $rs->fetch(PDO::FETCH_ASSOC);
        $rqstr = json_decode($s['reqby'], true);
        $rq = json_decode($s['srchrequests'], true);
 
        //DATA CHECKS HERE
        $errorInd = 0;
        ( $usr->userguid  !== $rqstr['userguid'] ) ? (list( $errorInd, $msgArr[] ) = array(1 , "FATAL ERROR:  REQUESTING USER DOES NOT MATCH SESSION LOGGED ON")) : "";
        ( trim($rq['fldFName']) === "" && trim($rq['fldLName']) === "" && trim($rq['fldMRN']) === "" && trim($rq['fldORDte']) === "" && trim($rq['fldCHTNNbr']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "AT LEAST ONE QUERY FIELD MUST HAVE A SUPPLIED VALUE")) : "";
        ( ( trim($rq['fldLName']) !== "" && trim($rq['fldFName']) === "" ) || ( trim($rq['fldLName']) === "" && trim($rq['fldFName']) !== "" ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "WHEN SEARCHING BY NAME, AT LEAST PARTIALS OF BOTH FIRST AND LAST NAME MUST BE SUPPLIED")) : "";
        ( strlen(trim($rq['fldLName'])) === 1 ) ? (list( $errorInd, $msgArr[] ) = array(1 , "SUPPLIED LAST NAME MUST BE LONGER THAN ONE (1) CHARACTER.")) : "";
        ( trim($rq['fldMRN']) !== "" && preg_match('/[^0-9\%]/',trim($rq['fldMRN'])) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "MRNs MUST BE NUMERIC REPRESENTATIONS")) : "";       
        ( trim($rq['fldORDte']) !== "" && !ssValidateDate($rq['fldORDte'],'m/d/Y') ) ? (list( $errorInd, $msgArr[] ) = array(1 , "O.R. DATE FORMAT IS INCORRECT")) : "";        

        ( (trim($rq['fldFName']) !== "" || trim($rq['fldLName']) !== "" || trim($rq['fldMRN']) !== "" || trim($rq['fldORDte']) !== "" ) && trim($rq['fldCHTNNbr']) !== "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "SEARCHES FOR CHTNEASTERN BIOGROUP DONOR INFORMATION CANNOT BE PERFORMED WITH SEARCHES IN THE GENERAL CATEGORY.")) : ""; 

        $valFN = $rq['fldFName'];
        $valLN = $rq['fldLName'];
        $valMR = $rq['fldMRN'];
        $valOR = $rq['fldORDte'];
        $valCH = $rq['fldCHTNNbr'];
        if ( $errorInd === 0 ) { 
          if ( (trim($rq['fldFName']) === "" && trim($rq['fldLName']) === "" && trim($rq['fldMRN']) === "" && trim($rq['fldORDte']) === "" ) && trim($rq['fldCHTNNbr']) !== "" ) {
  
              if ( strlen($valCH) < 4 ) {
                (list( $errorInd, $msgArr[] ) = array(1 , "SEARCHES FOR CHTNEASTERN BIOGROUP  DONOR INFORMATION CANNOT BE PERFORMED WITH SEARCHES IN THE GENERAL CATEGORY."));
              } else {   
                //{"MESSAGE":["87106"],"ITEMSFOUND":1,"DATA":[{"pxiID":"0dc6d821-0056-49ee-b15d-48a71df676d7"}]}     
		$ssdta = json_decode(callrestapi("GET", dataTreeSS . "/vault-search-bg/{$valCH}",serverIdent, serverpw,""),true);  

                $chk = "<div id=warningbar>THIS PAGE CONTAINS HIPAA INFORMATION. DO NOT PRINT!</div>";
                //$chk .= "<table border=0 id=donorDataDsp><tr><td colspan=9 id=headerLine>Donors Found: " . $lookupRS->rowCount() . "</td></tr><tr><th>Schedule<br>Date</th><th>Schedule<br>Institution</th><th>Procedure<br>Start-time</th><th>Donor Name</th><th>MRN</th><th>Donor Record<br>Age/Race/Sex</th><th>Surgeon</th><th>Procedure<br>Description</th></tr><tbody>";  
                $chk .= "<table border=0 id=donorDataDsp><tr><td colspan=9 id=headerLine>Donors Found: </td></tr><tr><th>Schedule<br>Date</th><th>Schedule<br>Institution</th><th>Procedure<br>Start-time</th><th>Donor Name</th><th>MRN</th><th>Donor Record<br>Age/Race/Sex</th><th>Surgeon</th><th>Procedure<br>Description</th></tr><tbody>";  
                $rowcnt = 0;  

		if ( (int)$ssdta['ITEMSFOUND'] > 0 ) { 
                  //LOOK FOR PXI IN DATABASE
                  $lookupSQL = "SELECT schd.ORSchdtid as pxiid, schd.ORSchdid, ifnull(date_format(ors.ordate,'%m/%d/%Y'),'') as ordate, ifnull(ors.forlocation,'') orlocation, ifnull(schd.starttime,'') as procedurestart, ifnull(schd.surgeon,'') as surgeon, concat(ifnull(schd.patlast,'ERROR'),', ', ifnull(schd.PatFirst,'ERROR')) as donorname, ifnull(schd.mrn,'0000000') as mrn, concat(ifnull(schd.age,'-'),'/',ifnull(schd.race,'-'),'/',ifnull(schd.sex,'-')) donorars, ifnull(schd.procdetails,'') as procdetails FROM ORSCHED.ut_zck_ORSchDetail schd left join ORSCHED.ut_zck_ORSchds ors on schd.ORSchdid = ors.ORSchedid where 1=1 and ORSchdtid = :pxiid";
		  $lookupRS = $conn->prepare($lookupSQL);

                  foreach ( $ssdta['DATA'] as $k => $v ) { 
                    $lookupRS->execute(array(':pxiid' => $v['pxiID']));
                    if ( $lookupRS->rowCount() < 1 ) { 
                      $chk .= "<tr><td colspan=8>NO DONOR INFORMATION FOUND MATCHING YOUR QUERY PARAMETERS ({$valCH}) IN ORSCHED</td></tr>";
                    } else {

		      while ($r = $lookupRS->fetch(PDO::FETCH_ASSOC)) { 
                        $chk .= "<tr class=pxidatarow id=\"datarow{$rowcnt}\" data-pxiid=\"{$r['pxiid']}\" data-selected=\"false\" onclick=\"rowselector(this.id);\">"
                          . "<td valign=top>{$r['ordate']}</td>"
                          . "<td valign=top>{$r['orlocation']}</td>"
                          . "<td valign=top>{$r['procedurestart']}</td>"
                          . "<td valign=top>{$r['donorname']}</td>"
                          . "<td valign=top>{$r['mrn']}</td>"
                          . "<td valign=top>{$r['donorars']}</td>"
                          . "<td valign=top>{$r['surgeon']}</td>"
                          . "<td valign=top>{$r['procdetails']}</td>"
                          . "</tr>";
                        $rowcnt++;
		      }

                    }  
                  } 
		}

                $elogSQL = "select 'C2L-DATA' fld1, 'C2L-DATA' fld2, 'C2L-DATA' as fld3, concat(ifnull(sd.patlast,''),', ',ifnull(sd.patfirst,'')) as donorname, ifnull(sd.mrn,'') as mrn, concat(ifnull(sd.age,'-'),'/',ifnull(sd.race,'-'),'/',ifnull(sd.sex,'-')) as ars, ifnull(sd.surgeon,'') as surgeon, 'ELOG and/or HISTORIC C2L-DATA' as proctext from ORSCHED.ut_Case2Label cl left join ORSCHED.ut_zck_ORSchDetail sd on cl.pxi = sd.orschdtid where biogroup like :chtnnbr"; 
                $elogRS = $conn->prepare( $elogSQL ); 
                $elogRS->execute( array( ':chtnnbr' => "{$valCH}%"));
                if ( $elogRS->rowCount() > 0 ) { 
		  while ( $r = $elogRS->fetch(PDO::FETCH_ASSOC) ) { 
                    $chk .= "<tr class=pxidatarow id=\"datarow{$rowcnt}\" data-pxiid=\"{$r['pxiid']}\" data-selected=\"false\" onclick=\"rowselector(this.id);\">"
                        . "<td valign=top>{$r['fld1']}</td>"
                        . "<td valign=top>{$r['fld2']}</td>"
                        . "<td valign=top>{$r['fld3']}</td>"
                        . "<td valign=top>{$r['donorname']}</td>"
                        . "<td valign=top>{$r['mrn']}</td>"
                        . "<td valign=top>{$r['ars']}</td>"
                        . "<td valign=top>{$r['surgeon']}</td>"
                        . "<td valign=top>{$r['proctext']}</td>"
                        . "</tr>";
                      $rowcnt++;
		  }
		} else { 
                  $chk .= "<tr><td colspan=8>NO DONOR INFORMATION FOUND MATCHING YOUR QUERY PARAMETERS ({$valCH}) IN HISTORIC ELOG DATA</td></tr>";
		}

                $ffSQL = "select dnchtnid, dnlastname, dnfirsname, dnrace, dngnder, dndob, dnrefnum, dncreated, dnloc, dnauthor from ORSCHED.ut_Donor where dnchtnid like :chtnnbr"; 
                $ffRS = $conn->prepare( $ffSQL );
		$ffRS->execute( array( ':chtnnbr' => "{$valCH}%" ) );
                if ( $ffRS->rowCount() > 0 ) { 
     	          while ( $r = $ffRS->fetch(PDO::FETCH_ASSOC) ) { 
                        $chk .= "<tr class=pxidatarow id=\"datarow{$rowcnt}\" data-pxiid=\"--\" data-selected=\"false\" onclick=\"rowselector(this.id);\">"
                          . "<td valign=top>FOXFIRE-DATA</td>"
                          . "<td valign=top>{$r['dnloc']}</td>"
                          . "<td valign=top>FOXFIRE-DATA</td>"
                          . "<td valign=top>{$r['dnlastname']}, {$r['dnfirstname']}</td>"
                          . "<td valign=top>{$r['dnrefnum']}</td>"
                          . "<td valign=top>-/{$r['dnrace']}/{$r['dngnder']}</td>"
                          . "<td valign=top>FIREFOX-DATA</td>"
                          . "<td valign=top>{$r['dnchtnid']} created on {$r['dncreated']} by {$r['dnauthor']}</td>"
                          . "</tr>";
                        $rowcnt++;
                  }
                } else { 
                  $chk .= "<tr><td colspan=8>NO DONOR INFORMATION FOUND MATCHING YOUR QUERY PARAMETERS ({$valCH}) IN HISTORIC FOXFIRE DATA</td></tr>";
                }

                $chk .= "</tbody></table>";
              }

          } else {
            $sql = "SELECT schd.ORSchdtid as pxiid, schd.ORSchdid, ifnull(date_format(ors.ordate,'%m/%d/%Y'),'') as ordate, ifnull(ors.forlocation,'') orlocation, ifnull(schd.starttime,'') as procedurestart, ifnull(schd.surgeon,'') as surgeon, concat(ifnull(schd.patlast,'ERROR'),', ', ifnull(schd.PatFirst,'ERROR')) as donorname, ifnull(schd.mrn,'0000000') as mrn, concat(ifnull(schd.age,'-'),'/',ifnull(schd.race,'-'),'/',ifnull(schd.sex,'-')) donorars, ifnull(schd.procdetails,'') as procdetails FROM ORSCHED.ut_zck_ORSchDetail schd left join ORSCHED.ut_zck_ORSchds ors on schd.ORSchdid = ors.ORSchedid where 1=1";
            if ( trim($rq['fldFName']) !== "" ) { 
              $sql .= " and patfirst like :firstname";
              $varr[':firstname'] = "{$rq['fldFName']}%";
            }
            if ( trim($rq['fldLName']) !== "" ) { 
              $sql .= " and patlast like :lastname";
              $varr[':lastname'] = "{$rq['fldLName']}%";
            }
            if ( trim($rq['fldMRN']) !== "" ) { 
              $sql .= " and schd.mrn like :mrn";
              $varr[':mrn'] = "{$rq['fldMRN']}%";
            }
            if ( trim($rq['fldORDte']) !== "" ) { 
              $sql .= " and date_format(ors.ordate,'%m/%d/%Y') = :ordte";
              $varr[':ordte'] = "{$rq['fldORDte']}";
            }

            $sql .= " limit 500";

            $rs = $conn->prepare($sql);
            $rs->execute($varr);  
            if ( $rs->rowCount() < 1 ) { 
                $chk = "<div id=prLocalBBar><div class=buttonContainer onclick=\"generateDialog('create-donor');\"><div class=controlBarButton><i class=\"material-icons\">create</i></div><div class=popupToolTip>Create New Donor Record</div></div></div>NO DONOR INFORMATION FOUND MATCHING YOUR QUERY PARAMETERS";
            } else { 
                $chk = "<div id=warningbar>THIS PAGE CONTAINS HIPAA INFORMATION. DO NOT PRINT!</div>";
                $chk .= "<table border=0 id=donorDataDsp><tr><td colspan=9 id=headerLine>Donors Found: " . $rs->rowCount() . "</td></tr><tr><th>Schedule<br>Date</th><th>Schedule<br>Institution</th><th>Procedure<br>Start-time</th><th>Donor Name</th><th>MRN</th><th>Donor Record<br>Age/Race/Sex</th><th>Surgeon</th><th>Procedure<br>Description</th></tr><tbody>";  
              $rowcnt = 0;  
              while ($r = $rs->fetch(PDO::FETCH_ASSOC)) { 
                //. "<td valign=top>{$r['pxiid']}</td>"
                $chk .= "<tr class=pxidatarow id=\"datarow{$rowcnt}\" data-pxiid=\"{$r['pxiid']}\" data-selected=\"false\" onclick=\"rowselector(this.id);\">"
                      . "<td valign=top>{$r['ordate']}</td>"
                      . "<td valign=top>{$r['orlocation']}</td>"
                      . "<td valign=top>{$r['procedurestart']}</td>"
                      . "<td valign=top>{$r['donorname']}</td>"
                      . "<td valign=top>{$r['mrn']}</td>"
                      . "<td valign=top>{$r['donorars']}</td>"
                      . "<td valign=top>{$r['surgeon']}</td>"
                      . "<td valign=top>{$r['procdetails']}</td>"
                      . "</tr>";
                $rowcnt++;
              }
              $chk .= "</tbody></table>";
              $chk .= <<<THISBAR
              <div id=prLocalBBar>
                <div class=buttonContainer onclick="sendToTodaysSchedule();"><div class=controlBarButton><i class="material-icons">send</i></div><div class=popupToolTip>Send Donor to today's schedule</div></div>
                <div class=buttonContainer onclick="generateDialog('create-donor');"><div class=controlBarButton><i class="material-icons">create</i></div><div class=popupToolTip>Create New Donor Record</div></div>
              </div>
THISBAR;
            }
          } 
        } else { 
          $chk = "ERROR(s):";  
          foreach ( $msgArr as $evl ) { 
            $chk .= "<br>{$evl}";
          }
        }
        $rdsp = "<div id=datadspdiv>{$chk}</div>";
      }      
    } else { 
        $rdsp = "";
    }
  
    $rt = <<<PGCONTENT
<div id=pgHolder>
   <div id=instructionBlock>Use this screen to look up possible CHTN Donors.  If a donor is not found in the system, You will be presented with an add donor button at the end of a search. (Wildcard: '%')  </div>
   <div class=elementHolder>
       <div class=fldLabel>First Name</div>
       <div class=fldHolder><input type=text id=fldFName value="{$valFN}"></div>
   </div>
   <div class=elementHolder>
       <div class=fldLabel>Last Name</div>
       <div class=fldHolder><input type=text id=fldLName value="{$valLN}"></div>
   </div>
   <div class=elementHolder>
       <div class=fldLabel>MRN</div>
       <div class=fldHolder><input type=text id=fldMRN value="{$valMR}"></div>
   </div>
   <div class=elementHolder>
       <div class=fldLabel>O.R. Date (mm/dd/YYYY)</div>
       <div class=fldHolder><input type=text id=fldORDte value="{$valOR}"></div>
   </div>
   <div class=elementHolder>
       <div class=fldLabel>CHTN # (Biogroup # Search)</div>
       <div class=fldHolder><input type=text id=fldCHTNNbr value="{$valCH}"></div>
   </div>
    <div><button onclick="sendSrchRqst();" id=btnSrchDonor>Search</button></div>
</div>
{$rdsp}
PGCONTENT;
    return $rt;
  }

  function consentwatch ( $rqst, $usr ) {

      $rsltdta = callrestapi("POST", dataTreeSS . "/data-doers/vault-consent-document-listing",serverIdent, serverpw, $passedData);   
      $dta = json_decode($rsltdta, true);
      if ( (int)$dta['RESPONSECODE'] === 200 ) { 
          //BUILD MENU
        $cdocdftdsp = "";
        $cdocdftval = "";
        $cDocMnu = "<table border=0 class=menuDropTbl>";
        foreach ( $dta['DATA'] as $k => $v ) {  
          if ( (int)$v['useasdefault'] === 1 ) {
            $cdocdftdsp = $v['dspvalue'];
            $cdocdftval = $v['menuvalue'];
          }
          $cDocMnu .= "<tr><td onclick=\"fillField('cDoc',{$v['menuvalue']},'{$v['dspvalue']}');\" class=ddMenuItem>{$v['dspvalue']}</td></tr>";
        }
        $cDocMnu .= "</table>";
      } else { 
        $cDocMnu = "ERROR:  Error in Retreiving Listing";
      }

      $racedta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/pxi-race",serverIdent, serverpw,""),true);  
      if ( (int)$racedta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $r = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qryRace','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($racedta['DATA'] as $aval) { 
          $r .= "<tr><td onclick=\"fillField('qryRace','{$aval['lookupvalue']}','{$aval['menuvalue']}');\" class=ddMenuItem>{$aval['menuvalue']}</td></tr>";
        }
        $r .= "</table>";
        $rMenu = "<div class=menuHolderDiv><input type=hidden id=qryRaceValue><input type=text id=qryRace READONLY class=\"inputFld\"><div class=valueDropDown style=\"min-width: 15vw;\">{$r}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $sexdta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/pxi-sex",serverIdent, serverpw,""),true);  
      if ( (int)$sexdta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $s = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qrySex','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($sexdta['DATA'] as $sval) { 
          $s .= "<tr><td onclick=\"fillField('qrySex','{$sval['lookupvalue']}','{$sval['menuvalue']}');\" class=ddMenuItem>{$sval['menuvalue']}</td></tr>";
        }
        $s .= "</table>";
        $sMenu = "<div class=menuHolderDiv><input type=hidden id=qrySexValue><input type=text id=qrySex READONLY class=\"inputFld\"><div class=valueDropDown style=\"min-width: 15vw;\">{$s}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $agedta = json_decode(callrestapi("GET", dataTreeSS . "/global-menu/age-uoms",serverIdent, serverpw,""),true);  
      if ( (int)$agedta['ITEMSFOUND'] > 0 ) { 
        //BUILD MENU
        $ageuom = "<table border=0 class=menuDropTbl><tr><td align=right onclick=\"fillField('qryAgeUOM','','');\" class=ddMenuClearOption>[clear]</td></tr>";
        foreach ($agedta['DATA'] as $aval) { 
          $ageuom .= "<tr><td onclick=\"fillField('qryAgeUOM','{$aval['lookupvalue']}','{$aval['menuvalue']}');\" class=ddMenuItem>{$aval['menuvalue']}</td></tr>";
        }
        $ageuom .= "</table>";
        $aMenu = "<div class=menuHolderDiv><input type=hidden id=qryAgeUOMValue><input type=text id=qryAgeUOM READONLY class=\"inputFld\" style=\"width: 7vw;\"><div class=valueDropDown style=\"min-width: 7vw;\">{$ageuom}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

      $consentQList = "";
      if ( trim($cdocdftval) !== "" && is_numeric($cdocdftval) ) { 
        $cqDta = json_decode(callrestapi("GET", dataTreeSS . "/vault-consent-doc-questions/" . (int)$cdocdftval, serverIdent, serverpw, ""), true); 
        if ( (int)$cqDta['ITEMSFOUND'] > 0 ) { 
            foreach ( $cqDta['DATA'] as $cqk => $cqv ) { 

              switch ( $cqv['additionalInformation'] ) { 
                case "YN":
                  $ynMnu = "<table border=0 class=menuDropTbl>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}','1','Yes');\" class=ddMenuItem>Yes</td></tr>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}','0','No');\" class=ddMenuItem>No</td></tr>";
                  $ynMnu .= "<tr><td onclick=\"fillField('ans{$cqv['menuvalue']}','3','illegible/Not Specified');\" class=ddMenuItem>illegible/Not Specified</td></tr>";
                  $ynMnu .= "</table>";

                  $ansElemt = "<div><div class=elementmenu> <div class=elemental><input type=text id=\"ans{$cqv['menuvalue']}\" value=\"\" style=\"width: 8vw;\" READONLY><div class=optionlisting style=\"width: 8vw;\">{$ynMnu}</div></div></div></div>";  
                  break;
                case "TXD":
                  $ansElemt = "<div><input type=\"text\" id=\"ans{$cqv['menuvalue']}\" style=\"width: 8vw;\"></div>";  
                  break;
                case "TXT":
                  $ansElemt = "<div><input type=\"text\" id=\"ans{$cqv['menuvalue']}\" style=\"width: 8vw;\"></div>";  
                  break;
                default:
                $ansElemt = "<div>&nbsp;</div>";  
              }         
              $consentQList .= "<div class=cqLine id='{$cqv['menuvalue']}'><div class=cqQstn>{$cqv['dspvalue']}</div>{$ansElemt}</div>";
            }
        }
      }

    $uploadside = <<<UPLOADSIDE
<div id=cwTitle>Upload Consent Form</div>
<div id=cwDirections>Fill out the form below.  This will add the donor to the 'Consent Watch List' unless you list CHTN Biogroup Numbers.  If you list CHTN Numbers, those numbers will be marked 'Informed Consent YES' in the ScienceServer Database.  </div>
<p>
<form id=consentDocFrm name=consentDocFrm  onsubmit="event.preventDefault(); validateConsentForm();" method="POST" enctype="multipart/form-data" >
<div class=cwSectionHeader>Upload File *</div>
<div>
  <div>Choose a File</div>
  <div><input type="file" name="file" id="file" class="inputfile" accept=".pdf" onchange="btoathisfile('btoIConsent', this.files[0]);" /><TEXTAREA id="btoIConsent" style="display: block;"></textarea></div>
</div>
<div align=right style="width: 30vw;">
<table><tr><td> <button id=btnConsentSave >Save</button> </td><td> <button id=btnConsentCancel onclick="consentcancel();">Cancel</button> </td></tr></table>
</div>


<div class=cwSectionHeader>Donor Information</div>
<div id=elementlineOne>
  <div class=element><div class=elementLabel>Donor First Name *</div><div class=elemental><input type=text id=fldDonorFName></div></div>
  <div class=element><div class=elementLabel>Donor Last Name *</div><div class=elemental><input type=text id=fldDonorLName></div></div>
  <div class=element><div class=elementLabel>Donor MRN</div><div class=elemental><input type=text id=fldDonorMRN></div></div>
</div>
<div id=elementlineFour>
  <div class=element><div class=elementLabel>Donor Age *</div><div class=elemental><table border=0 cellspacing=0 cellpadding=0><tr>
      <td><div class=element><input type=text id=fldDnrAge value="" style="width: 2vw; text-align: right;"></div></td>
      <td style="padding: 0 0 0 2px;"><div class=element>{$aMenu}</div></td>
    </tr>
    </table></div></div>
  <div class=element><div class=elementLabel>Donor Race *</div><div class=elemental>{$rMenu}</div></div>
  <div class=element><div class=elementLabel>Donor Sex *</div><div class=elemental>{$sMenu}</div></div>
</div>
<p>
<div class=cwSectionHeader>Surgeon/Investigator</div>
<div id=elementlineTwo>
  <div class=element><div class=elementLabel>First Name</div><div class=elemental><input type=text id=fldIFName></div></div>
  <div class=element><div class=elementLabel>Last Name</div><div class=elemental><input type=text id=fldILName></div></div>
  <div class=element><div class=elementLabel>Anticipated Procedure Date</div><div class=elemental><input type=text id=fldProcDte></div><div style="font-size: .8vh;">(Format: mm/dd/yyyy)</div></div>
</div>
<p>
<div class=cwSectionHeader>Document Questions *</div>
<div id=elementlineThree>
  <div class=elementmenu>
    <div class=elementLabel>Consent Document</div>
    <div class=elemental><input type=hidden id=cDocVal value={$cdocdftval}><input type=text id=cDoc value="{$cdocdftdsp}" READONLY></div>
    <div class=optionlisting style="width: 34vw;">{$cDocMnu}</div>
  </div>
  <div id=consentquestions>{$consentQList}</div>
</div>
  </form>
<p>


UPLOADSIDE;

//onclick="consentwatchsave();"

           $icdsrchopt = json_decode (callrestapi("GET", dataTreeSS . "/global-menu/icd-doc-search-options",serverIdent, serverpw,""), true);
            if ( (int)$icdsrchopt['ITEMSFOUND'] > 0 ) { 
              //BUILD MENU
              $icdsrch = "<table border=0 class=menuDropTbl>";           
              $defaultvalue = "";
              $defaultcode = "";
              foreach ($icdsrchopt['DATA'] as $icval) { 
                if ( (int)$icval['useasdefault'] === 1 )  {
                    $defaultvalue = $icval['menuvalue'];
                    $defaultcode = $icval['lookupvalue'];
                }
                $icdsrch .= "<tr><td onclick=\"fillField('qryICDocSrch','{$icval['lookupvalue']}','{$icval['menuvalue']}');\" class=ddMenuItem>{$icval['menuvalue']}</td></tr>";
              }
              $icdsrch .= "</table>";
              $icdsrchfld = "<div class=menuHolderDiv><input type=hidden id=qryICDocSrchValue value=\"{$defaultcode}\"><input type=text id=qryICDocSrch READONLY class=\"inputFld\" value=\"{$defaultvalue}\"><div class=valueDropDown style=\"min-width: 7vw;\">{$icdsrch}</div></div>";
      } else { 
        //TODO: BUILD ERROR
      }

       $pdta = json_encode(array('typeOfConsentSearch' => 'lastupload' )); //DEFAULT last 100 uploaded Documents
       $icdRslt = json_decode(callrestapi("POST", dataTree . "/data-doers/consent-document-search",serverIdent, serverpw,$pdta), true);
       $icdRsltType = "UNKNOWN";
       if ( (int)$icdRslt['RESPONSECODE'] === 200 ) { 
            $icdRsltType = $icdRslt['DATA']['typeOfSearch'] . " ({$icdRslt['ITEMSFOUND']} donor(s) found)";
            if ( (int)$icdRslt['ITEMSFOUND'] > 0 ) { 
               $icdRsltTbl = "<table border=0 id=icSearchRsltTbl><tr><th>Consent Type</th><th>Ans</th><th>Bio</th><th>Doc</th><th>Donor</th><th>MRN</th><th>A/R/S</th><th>Uploaded</th><th>Procedure Date</th><th>Investigator From</th></tr>";
               foreach ( $icdRslt['DATA']['donorlisting'] as $k => $v) { 
                 $aprocDte = ( trim($v['anticprocdate']) === '01/01/1900' ) ? "-" : $v['anticprocdate']; 
                 $rqstr = ( trim($v['rqstname']) === '' ) ? "-" : strtoupper($v['rqstname']);

                 $bioTbl = "<table border=0 style=\"width: 30vw;\"><tr><td class=closeDialogBar onclick=\"closeBiogroups('{$v['icid']}');\">&times; close</td></tr>";
                 $bioTbl .= "<tr><td style=\"background: rgba(255,255,255,1); border: none;\"><div><div class=fieldLabel>CHTN Biogroup (Comma Delimit)</div><div><input type=text id='bGroupDelimit{$v['icid']}' style=\"width: 25vw;\"><button style=\"margin-left: .3vw;\" onclick=\"addConsentBiogroup('{$v['selector']}','{$v['icid']}');\">Attach</button></div></div></td></tr><tr><td class=sectionBar>ATTACHED BIOGROUPS</td></tr>";
                 foreach ( $v['bioGroups'] as $bk => $bv ) {
                   if ( trim( $bv['chtnnbr'] ) !== "" ) {  
                       $bioTbl .= "<tr><td>{$bv['chtnnbr']} ({$bv['uploadedby']}::{$bv['uploadedon']})</td></tr>";
                   }
                 }
                 $bioTbl .= "</table>" ;

                 $ansTbl = "<table border=0 style=\"width: 30vw;\"><tr><td colspan=2 class=closeDialogBar onclick=\"closeAnswers('{$v['icid']}');\">&times; close</td></tr><tr><th>Consent Question</th><th>Answer</th></tr>";
                 foreach ( $v['docAnswers'] as $ak => $av ) { 
                   $ansTbl .= "<tr><td>{$av['qtxt']}</td><td>{$av['answertxt']}</td></tr>";
                 }
                 $ansTbl .= "</table>";

                 $icdRsltTbl .= "<tr>"
                              . "<td>{$v['consentdoctype']}</td>"
                              . "<td><div class=addInfoHolder><div onclick=\"openAnswers('aDisplayer{$v['icid']}');\"><center><i class=\"material-icons\">contact_support</i></div><div class=answerDisplayer id='aDisplayer{$v['icid']}'>{$ansTbl}</div></div></td>"
                              . "<td><div class=addInfoHolder><div onclick=\"openBiogroups('bDisplayer{$v['icid']}');\"><center><i class=\"material-icons\">extension</i></div><div class=answerDisplayer id='bDisplayer{$v['icid']}'>{$bioTbl}</div></div></td>"
                              . "<td><div class=addInfoHolder><div onclick=\"getPDFDoc('{$v['selector']}');\"><center><i class=\"material-icons\">description</i></div><div class=answerDisplayer id='aDisplayer{$v['icid']}'>PDF Doc</div></div></td>"
                              . "<td>{$v['donorname']}</td>"
                              . "<td>{$v['mrn']}</td>"
                              . "<td>{$v['age']}/{$v['race']}/{$v['sex']}</td>"
                              . "<td>{$v['uploaddate']}</td>"
                              . "<td>{$aprocDte}</td>"
                              . "<td>{$rqstr}</td>"
                              . "</tr>";                   
               }                                           
               $icdRsltTbl .= "</table>"; 
            }
       }

      $consentListing = <<<ICLISTING
 <div id=ICDocDsp>
 <div id=warningbar>THIS PAGE CONTAINS HIPAA INFORMATION. DO NOT PRINT!</div>
 <div id=ICDTitle>Informed Consent Search</div>
 <div align=right>
    <table border=0><tr>
            <td><div><div class=fieldLabel>Type of Search</div><div>{$icdsrchfld}</div></div></td>              
            <td><div><div class=fieldLabel>Search Term</div><div><input type=text id=qryICDocTerm></div></div></td>
            <td><button onclick="ondemandSearchConsent();" id=btnICSearch>Search</button></td></tr></table>
 </div>
 <div id=ICDRsltGrid>
     <div id=typeOfICDSearch>{$icdRsltType}</div>
     <div id=rsltOfICDSearch>{$icdRsltTbl}</div>
 </div>    
              
              
 </div>


ICLISTING;



    $rt = <<<PGCONTENT
<div id=pgHolder>
<div id=uploadSide>{$uploadside}</div>
<div id=consentDspSide>{$consentListing}</div>
</div>
PGCONTENT;
return $rt;
}

function root ( $rqst, $usr ) {

  $r = json_encode( $rqst );
  $u = json_encode( $usr );
$rt = <<<PGCONTENT

<div id=rootTitle>CHTN Eastern Secure Donor Vault</div>
<div id=rootDesc>This dataserver contains HIPAA information on the donors of biosample research material collected by the Cooperative Human Tissue Network Eastern Division (CHTNED). You must have explicit data access credentials to utilize this dataserver.  If you do not have the requisite access level, please click the logout button on the control bar on the left of this screen.<p><b>REMINDER</b>: DO NOT PRINT / COPY / OTHERWISE DOWNLOAD ANY INFORMATION FROM THIS SERVICE.  When you are finished with your session, click the log-out button (Red Button) on the left of the screen!

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
    <div class=fldHolder><input type=text id=dvPWD></div>

  </div>
  <div id=actionBtnLoginHolder>
    <button id=actionBtnLogin><i class="material-icons">verified_user</i> Login</button>
  </div>

 <!--REMOVE THIS DIV WARNING IN PRODUCTION //-->
 <div id=devHIPAAWarning>THIS IS A DEVELOPMENT SITE - DO NOT USE ANY HIPAA DATA</div>   
    
  <div id=announcerText>
    <p><b>Disclaimer</b>: This site is for the use of CHTNED/UPHS Employees only.  Use of this site is monitored closely.  Do Not attempt to access unless you have permission from CHTNED Management.  You must have a ScienceServer Login and be logged into ScienceServer to access the data in this vault. This is the Specimen Management Data Application (SMDA) Donor Vault for the Eastern Division of the Cooperative Human Tissue Network.  It provides access to donor data for reference by authorized UPHS-specific employees of the CHTNEastern Division.   You must have a valid username and password to access this system.  If you need credentials for this application, please contact a CHTNED Manager.  Unauthorized activity is tracked and reported! To contact the offices of CHTNED, please call (215) 662-4570 or email chtnmail /at/ uphs.upenn.edu.
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

function donorexclusion () { 

$rtnThis = <<<STYLESHEET

#exclusionTitle { font-size: 3vh; font-weight: bold; color: rgba({$this->color_dblue},1); border-bottom: 1px solid rgba({$this->color_dblue},1); text-align: center; margin-bottom: 1vh; margin-top: 1vh; }
#instructionBlock { font-size: 1.8vh; line-height: 1.8em; margin-bottom: 1vh;  } 

#frmHolder { display: grid; grid-template-columns: repeat( auto-fit, minmax(1vw, 1fr)); margin-bottom: 1vh; grid-gap: 4px; } 
.elementLbl { font-size: 1.3vh; color: rgba{$this->color_dblue},1); font-weight: bold;  }
.elementHolderA { text-align: center; } 

#fldExFName { width: 100%; font-size: 1.7vh; } 
#fldExLName { width: 100%; font-size: 1.7vh; } 
#fldExMRN { width: 100%;  font-size: 1.7vh; } 
#fldExDate { width: 100%;  font-size: 1.7vh; } 
#fldExNote { width: 100%;  font-size: 1.7vh; } 
.btnAction { height: 100%;  font-size: 1.7vh; width: 10vw; } 


#warningbar { background: rgba({$this->color_bred},.8); font-size: 2.2vh; text-align: center; color: rgba({$this->color_white},1); font-weight: bold; padding: 1vh 0; width: 95vw;   } 

#donorClearTitleStatusDsp { width: 40vw; font-size: 1.8vh; text-align: justify; line-height: 1.6em; padding: 1vh 1vw;  } 


#PNDPRTitle { border-bottom: 1px solid rgba({$this->color_zackgrey},1); text-align: center; font-family: Roboto;  font-size: 2vh; padding-top: 1vh; }
#resultCnt { font-family: Roboto; font-size: 1.4vh;   }
#dspWorkBenchTbl table { font-family: Roboto; font-size: 1.4vh; }  
#dspWorkBenchTbl table tr:nth-child( even ) { background: rgba({$this->color_grey},.7); }
#dspWorkBenchTbl table tr td { padding: .8vh .6vw .8vh .2vw; border-bottom: 1px solid rgba({$this->color_grey},1); border-right: 1px solid rgba({$this->color_grey},1);   }


STYLESHEET;
    return $rtnThis;
}



function pendingpathologyreportlisting () { 

$rtnThis = <<<STYLESHEET
#dataDsp { padding: 2vh 1vw 2vh 0; } 

#warningbar { background: rgba({$this->color_bred},.8); font-size: 2.2vh; text-align: center; color: rgba({$this->color_white},1); font-weight: bold; padding: 1vh 0; position: fixed; top: 1vh; left: 4vw; width: 95vw;   } 
#PNDPRTitle { font-size: 2vh; text-align: center; font-weight: bold; border-bottom: 1px solid rgba({$this->color_dblue},1); padding-top: 5vh;  }
#resultCnt { font-size: 1.4vh; color: rgba({$this->color_zackgrey},1); padding: 1vh 0 0 0; text-align: right; } 

#masterPRPTbl { font-size: 1.4vh; color: rgba({$this->color_zackgrey},1); margin-left: 3vw; width: 95%;  }
#masterPRPTbl tr:nth-child(even) { background: rgba({$this->color_neongreen},.1); } 
#masterPRPTbl tr:hover { background: rgba({$this->color_lamber},.7); cursor: pointer; }  
#masterPRPTbl tr[data-selected='true'] { background: rgba({$this->color_cornflowerblue},.3);    }

#masterPRPTbl .procMnth { background: rgba({$this->color_white},1); padding: .8vh 0 .4vh 0; border-top: 1px solid rgba({$this->color_zackgrey},1); border-bottom: 1px solid rgba({$this->color_zackgrey},1); border-right: none; text-align: center; font-size: 2vh; color: rgba({$this->color_dblue},1); font-weight: bold; } 
#masterPRPTbl tr th { font-size: 1vh; background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); padding: .4vh 0;  } 
#masterPRPTbl tr td { padding: .5vh .3vw; border-right: 1px solid rgba({$this->color_zackgrey},.5); border-bottom: 1px solid rgba({$this->color_zackgrey},.5); } 

#prLocalBBar {  position: fixed; z-index: 28; top: 13vh; left: 3.5vw; }



#mprnHolder { display: grid; width: 30vw;  grid-template-columns: 1fr 2fr; grid-gap: 5px; }
#mprnHolder #bgListDsp { height: 20vh; width: 100%; overflow: auto; border: 1px solid rgba({$this->color_zackgrey},1); } 

.bgListItem { display: grid; grid-template-columns: 1fr 6fr; margin-top: .2vh; }
.bgListItem:nth-child(odd) { background: rgba({$this->color_grey},.7); } 

.bgListItem div {  } 
.bgListItem .bgNbring { grid-row: 1 / 3; } 

#reasonGiven { width: 100%; resize: none; height: 20vh; padding: 8px;  } 

#mprnHolder { font-size: 1.2vh; color: rgba({$this->color_zackgrey},1); } 
.tagLbls { font-size: 1.1vh; font-weight: bold; padding: 1vh 0 0 0; } 
.bgNbring { font-size: 1.8vh; text-align: center; }
.bgNbrDsp { font-weight: bold; color: rgba({$this->color_dblue},1); } 
.dxdesig { font-size: 1vh;    }
#reasonGiven { font-family: Roboto; font-size: 1.5vh; line-height: 1.8em; } 

STYLESHEET;
    return $rtnThis;
  }


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
input { font-size: 1.2vh; padding: 8px 4px 8px 4px; border: 1px solid rgba({$this->color_zackgrey},1); }
input:focus, input:active { background: rgba({$this->color_lamber},.5); border: 1px solid rgba({$this->color_dblue},.5);  outline: none;  }

.floatingDiv {  z-index: 101;  background: rgba({$this->color_white},1); border: 1px solid rgba({$this->color_zackgrey},1); position: fixed; padding: 2px; top: 150px ; left: 150px;  }


#systemDialogClose { width: .5vw; background: rgba({$this->color_zackgrey},1); color: rgba({$this->color_white},1); font-size: 2vh;text-align: right; padding: .3vh .1vw; }
#systemDialogClose:hover {cursor: pointer; color: rgba({$this->color_bred},1); }

#systemDialogTitle { background: rgba({$this->color_zackgrey},1); color: rgba({$this->color_white},1); font-size: 1.3vh; padding: .3vh .3vw; }

/* DROP DOWN TABLES */
.menuHolderDiv { position: relative; }
.menuHolderDiv:hover .valueDropDown { display: block; cursor: pointer; }
.valueDropDown {background: rgba({$this->color_white},1);position: absolute; border: 1px solid rgba({$this->color_zackgrey},1); box-sizing: border-box; margin-top: .07vh; min-height: 15vh; max-height: 33vh; overflow: auto; display: none; z-index: 25; }
.menuDropTbl { font-size: 1.3vh; padding: .6vh .1vw .6vh .1vw; white-space: nowrap; background: rgba({$this->color_white},1); width: 100%; }

.inputiconcontainer { position: relative; }
.inputmenuiconholder { position: absolute; top: 0; left: 0; width: 100%; height: 100%; text-align: right; padding: 9px 6px; box-sizing: border-box; }
.menuDropIndicator { font-size: 2vh; color: rgba({$this->color_grey},1); }
.menuHolderDiv:hover .menuDropIndicator { color: rgba({$this->color_cornflowerblue},1); }

.valueDisplayHolder { position: relative; } 
.valueDisplayDiv { background: rgba({$this->color_white},1);position: absolute; border: 1px solid rgba({$this->color_zackgrey},1); box-sizing: border-box; margin-top: .1vh; min-height: 15vh; max-height: 33vh; overflow: auto; display: none; z-index: 25; }

.ddMenuItem {padding: .3vh .2vw;}
.ddMenuItem:hover { cursor: pointer;  background: rgba({$this->color_lblue},1); color: rgba({$this->color_white},1);  }
.ddMenuClearOption { font-size: 1.1vh; }
.ddMenuClearOption:hover {color: rgba({$this->color_bred},1); }


.addInfoHolder { position: relative; } 
.answerDisplayer {background: rgba({$this->color_white},1);position: absolute; border: 1px solid rgba({$this->color_zackgrey},1); box-sizing: border-box; min-height: 15vh; overflow: auto; display: none; z-index: 25; } 

#countdown { display: none; } 
STYLESHEET;
    return $rtnThis;
  }

  function consentwatch() { 
    $rtnThis = <<<stylesheets
#pgHolder { display: grid; grid-template-columns: 35vw 2fr; }

#uploadSide { width: 35vw; } 
#cwTitle { width: 34vw; text-align: center; font-size: 1.8vh; padding: 1vh 0 .3vh 0; font-weight: bold; border-bottom: 1px solid rgba({$this->color_dblue},1); color: rgba({$this->color_dblue},1);  }
#cwDirections { font-size: 1.3vh; text-align: justify; width: 30vw;  }
.cwSectionHeader { font-size: 1.6vh; width: 34vw; background: rgba({$this->color_cornflowerblue}, .6); color: rgba({$this->color_white}, 1); padding: .4vh 0 .4vh .2vw; border: 1px solid rgba({$this->color_dblue},1);  } 


#elementlineOne { display: grid; grid-template-columns: repeat( auto-fit, minmax(1vw, 1fr)); width: 34vw; }
#elementlineTwo { display: grid; grid-template-columns: repeat( auto-fit, minmax(1vw, 1fr)); width: 34vw; }
#elementlineFour { display: grid; grid-template-columns: repeat( auto-fit, minmax(1vw, 1fr)); width: 34vw; }

.elementLabel { padding: .8vh 0 0 0; font-size: 1.2vh; font-weight: bold;    } 

.elementwithbtn { display: grid; grid-template-columns: 2fr 1fr; }
.elementwithbtn .elementLabel { grid-column: 1 / 2; grid-row: 1; } 
.elementwithbtn .elementbtn { grid-row: 1 / 3; grid-column: 3 / 4; text-align: center; padding: 14px 4px; }
.elementwithbtn .elemental { grid-row: 2; } 


#elementlineThree { width: 34vw; }
#cDoc { width: 34vw; } 


.elementmenu { position: relative; }

.elementmenu:hover .optionlisting { display: block; } 
.optionlisting { border: 1px solid rgba({$this->color_zackgrey},1); box-sizing: border-box; height: 8vh; margin-top: 1px; overflow-y: auto; overflow-x: hidden; display: none; position: absolute; z-index: 10; background: rgba({$this->color_white},1);  } 

#consentquestions { width: 34vw; height: 35vh; margin-top: 1vh;  border: 1px solid rgba({$this->color_zackgrey},.1); overflow: auto; } 


#consentDspSide { border-left: 1px solid rgba({$this->color_zackgrey},1); }  

.cqLine { display: grid; grid-template-columns: 2fr 1fr; width: 33vw; margin-top: .2vh; }
.cqQstn { padding: .5vh 0 .5vh .3vw; } 
.cqLine:nth-child(even) { background: rgba({$this->color_grey},.4); } 
.cqQstn { font-size: 1.3vh; color: rgba({$this->color_zackgrey},1); } 

#ICDocDsp { padding: .3vh .5vw;  font-size: 1.5vh;  } 
#ICDocDsp #ICDTitle { font-size: 2vh; font-weight: bold; color: rgba({$this->color_dblue},1); text-align: center; padding: 1vh 0 0 0; }
#btnICSearch { height: 4vh; margin-top: 2vh;  } 
.fieldLabel { font-size: 1.3vh; font-weight: bold; color: rgba({$this->color_dblue},1); padding: 1vh 0 0 0; } 

#ICDRsltGrid { margin-top: 1vh;  } 
#typeOfICDSearch { font-size: 1.3vh; font-weight: bold; color: rgba({$this->color_dblue},1); padding: 1vh 0 1vh 0;   }

#icSearchRsltTbl { font-size: 1.4vh; color: rgba({$this->color_zackgrey},1); width: 98%;   }
#icSearchRsltTbl tr:nth-child(even) { background: rgba({$this->color_neongreen},.1); } 
#icSearchRsltTbl tr:hover { background: rgba({$this->color_lamber},.7); cursor: pointer; } 
#icSearchRsltTbl tr th { font-size: 1vh; background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); padding: .4vh 0;  } 
#icSearchRsltTbl tr td { padding: .5vh .3vw; border-right: 1px solid rgba({$this->color_zackgrey},.5); border-bottom: 1px solid rgba({$this->color_zackgrey},.5); } 


#warningbar { background: rgba({$this->color_bred},.8); font-size: 2.2vh; text-align: center; color: rgba({$this->color_white},1); font-weight: bold; padding: 1vh 0; width: 60vw;   } 
#qryICDocSrch { width: 15vw; }
#qryICDocTerm { width: 30vw; } 

.closeDialogBar { text-align: right; background: rgba({$this->color_black},1); color: rgba({$this->color_white},1); font-size: 1.2vh;padding: .2vh .3vw; }
.closeDialogBar:hover { color: rgba({$this->color_bred},1); cursor: pointer;  } 

.sectionBar { text-align: center; border: none; font-weight: bold; font-size: 1.1vh; background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); }

stylesheets;
    return $rtnThis;
  }

  function donorlookup() {

    $rtnThis = <<<stylesheets

#pgHolder { display: grid; grid-template-columns: repeat(7, 1fr); padding: 1vh 0 0 0; }
#instructionBlock { grid-column: 1 / 8; grid-row: 1; font-size: 1.8vh; padding: 1vh 0 1vh 0; }

.fldLabel { font-size: 1.1vh; font-weight: bold; color: rgba({$this->color_dblue},1); } 
.fldHolder input { font-size: 1.5vh; padding: 8px 4px 8px 4px; border: 1px solid rgba({$this->color_zackgrey},1); } 

#fldFName { width: 13vw; } 
#fldLName { width: 13vw; }
#fldMRN { width: 13vw; } 
#fldORDte { width: 13vw; } 
#fldCHTNNbr { width: 13vw; } 

#btnSrchDonor { border: 1px solid rgba({$this->color_zackgrey},1); font-size: 1.5vh; padding: 6px 8px 6px 8px; margin-top: 1.3vh; }

#warningbar { background: rgba({$this->color_bred},.8); font-size: 2.2vh; text-align: center; color: rgba({$this->color_white},1); font-weight: bold; padding: 1vh 0; position: fixed; top: 11vh; left: 4vw; width: 95vw;   } 

#donorDataDsp { font-size: 1.5vh; color: rgba({$this->color_zackgrey},1); margin-top: 7vh; margin-left: 3vw; width: 92vw; }
#donorDataDsp #headerLine { background: rgba({$this->color_white},1); }
#donorDataDsp tr th { background: rgba({$this->color_dblue},1); color: rgba({$this->color_white},1); font-size: 1.1vh; padding: 8px 4px; }  
#donorDataDsp tbody tr:nth-child(odd) { background: rgba({$this->color_neongreen},.1); } 
#donorDataDsp tbody tr:hover { background: rgba({$this->color_lamber},.7); cursor: pointer; }  
#donorDataDsp tbody tr[data-selected='true'] { background: rgba({$this->color_cornflowerblue},.3);    }

#donorDataDsp tbody tr td { border-bottom: 1px solid rgba({$this->color_zackgrey},1); border-right: 1px solid rgba({$this->color_zackgrey},1); padding: 8px 4px; }

#prLocalBBar {  position: fixed; z-index: 28; top: 16vh; left: 3.5vw; }

#donorAddHolder { display: grid; grid-template-columns: repeat( 2, 1fr ); grid-gap: 3px; }
#donorAddHolderLine2 { display: grid; grid-template-columns: repeat( 3, 1fr ); grid-gap: 3px; }
#donorAddHolderLine3 { display: grid; grid-template-columns: repeat( 3, 1fr ); grid-gap: 3px; }

.elementhold { padding: .5vh .2vw; }
.elementlbl { font-size: 1.3vh; color: rgba({$this->color_zackgrey},1);  }  
.element input { width: 10vw; } 

#instructionHeader { font-size: 1.5vh; padding: .5vh .3vw;  } 
#buttonHolder { padding: 1vh .3vw 1vh 0; } 


stylesheets;
    return $rtnThis;
  }


  function root() {
    $at = genAppFiles;
    $bgPic = base64file("{$at}/bg.png","background","bgurl",true);
    $rtnThis = <<<STYLESHEET
body { background: {$bgPic} repeat; padding-left: 4vw; padding-right: 4vw;  }

#rootTitle { text-align: center; font-size: 3vh; padding: 3vh 0 .5vh 0; font-weight: bold; color: rgba({$this->color_dblue},1); border-bottom: 2px solid rgba({$this->color_dblue},1);  }
#rootDesc { font-size: 2vh; text-align: justify; padding: 1vh 25vw 1vh 25vw; line-height: 1.8em; color: rgba({$this->color_bred},1); }

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
#announcerText { font-size: 1vh; padding: .8vh .5vw; background: rgba({$this->color_lightgrey},.6); border-top: 1px solid rgba({$this->color_darkgrey},1); text-align: justify; line-height: 1.8em; }

#loginvnbr { font-family: Roboto; font-size: .7vh; padding: .4vh .4vw .4vh .8vw; text-align: right; background: rgba({$this->color_lightgrey},1); }

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

