<?php

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
        $this->rtnData = $request[3];
      if (trim($request[3]) === "") { 
        $this->responseCode = 404; 
        $this->rtnData = json_encode(array("MESSAGE" => "DATA NAME MISSING " . json_encode($request),"ITEMSFOUND" => 0, "DATA" => array()    ));
      } else { 
        $dp = new $request[2](); 
        if (method_exists($dp, $request[3])) { 
          $funcName = trim($request[3]); 
          $dataReturned = $dp->$funcName($args[0], $args[1]); 
          $this->responseCode = $dataReturned['statusCode']; 
          $this->rtnData = json_encode($dataReturned['data']);
        } else { 
          $this->responseCode = 404; 
          $this->rtnData = json_encode(array("MESSAGE" => "END-POINT FUNCTION NOT FOUND: {$request[3]}","ITEMSFOUND" => 0, "DATA" => ""));
        }
      }
    }
  }

}

class datadoers { 
    
    function retrievependingprlisting( $request, $passedData ) { 
        
      $responseCode = 503; 
      $rsltdta = callrestapi("POST", dataTreeSS . "/data-doers/vault-retrieve-pending-prs",serverIdent, serverpw, $passedData);          
      $dta = json_decode($rsltdta, true);
      
      if ((int)$dta['RESPONSECODE'] === 200 ) { 
        
          $dspTbl = <<<DSPTHIS
<div id=PNDPRTitle>Biosamples in 'Pending Pathology Report' Status</div>
<div id=resultCnt>Items Found: {$dta['ITEMSFOUND']}</div>    
<div id=dspWorkBenchTbl>
DSPTHIS;

          $dspTbl .= "<table border=1>";
          $procdate = "";
          foreach ( $dta['DATA'] as $ky => $v ) { 
              //"DATA":["pxiid":"ZCR-011985","associd":"2086fc7a-2a84-4fa6-add1-42973ec73b4b"},{"pBioSample":29602.01,"readlabel":"29602A2","proctype":"Surgery","qmsprocstatus":"QMS Complete","procureinstitution":"Hospital of The University of Pennsylvania","procurementdate":"8200-07-25","proceduredate":"07\/25\/2003","pxi":"WHITE \/ FEMALE \/ 58 YEARS","dx":"NORMAL :: LUNG ::","pxiid":"ZCR-020726","associd":"","procurementdatedsp":"07-8200" 
              
              if ( $procdate !== $v['procurementdatedsp']) { 
                  $dspTbl .= "<tr><td colspan=20>Procurement in {$v['procurementdatedsp']}</td></tr>";
                  $procdate = $v['procurementdatedsp'];
              }
              
              
              
              $dspTbl .= "<tr data-pbiosample=\"{$v['pBioSample']}\">"
                               . "<td>{$v['readlabel']}</td>"
                               . "<td>{$v['proctype']}</td>"
                               . "<td>{$v['procureinstitution']}</td>"
                               . "<td>{$v['proceduredate']}</td>"
                               . "<td>{$v['qmsprocstatus']}</td>"
                               . "<td>{$v['pxi']}</td>"                               
                               . "<td>{$v['dx']}</td>"
                               . "<td>{$v['pxiid']}</td>"

                               . "</tr>";
              
          }
          $dspTbl .= "</table></div>";
          

          
        $dta = $dspTbl;
        $responseCode = 200;          
      } else { 
        $msgArr[] = "UNSPECIFIED ERROR:  SEE CHTNED INFORMATICS";
      }      
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('RESPONSECODE' => $responseCode,  'MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;                      
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
        //GOOD - SESSION CREATE LOGGEDON - CAPTURE SYSTEM ACTIVITY - REDIRECT IN JAVASCRIPT WITH STATUSCODE = 200
        session_regenerate_id(true);
        $_SESSION['loggedin'] = 'true';
        $_SESSION['pxiguid'] = $rslt['DATA'];
        $responseCode = 200;  
      } else {
        $msgArr[] = $rsltdta;
      }
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array('MESSAGE' => $msg, 'ITEMSFOUND' => 0,  'DATA' => $dta);
      return $rows;              
    }
    
    
} 
