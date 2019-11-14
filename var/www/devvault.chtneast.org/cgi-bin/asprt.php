<?php 

function testerfunction() { 
return "RETURN THIS VALUE";
}

function ssValidateDate($date, $format = 'Y-m-d') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}
 
function checkPostingUser($usrname, $passwrd) { 
    $responseCode = 401;  //UNAUTHORIZED 
    if ($usrname === serverIdent) { 
      //CHECK SERVER CREDENTIALS
      if ( cryptservice( $passwrd , 'd' ) === servertrupw ) { 
          $responseCode = 200;
      }
    } else { 
      //CHECK CODE IN DATABASE   
      require(serverkeys . "/sspdo.zck"); 
      //TODO: MAKE THIS AWEBSERVICE
      $chkSQL = "SELECT sessid, accesscode FROM ORSCHED.dv_ss_srvIdents where sessid = :usrsess and datediff(now(), onwhen) < 1";  
      $rs = $conn->prepare($chkSQL); 
      $rs->execute(array(':usrsess' => $usrname));
      if ((int)$rs->rowCount() > 0) { 
          $r = $rs->fetch(PDO::FETCH_ASSOC);
          if ( cryptservice( $passwrd, 'd', true, $usrname ) === $usrname ) { 
            $responseCode = 200;
          }
      } 
    }
    return $responseCode;
}

function dvRegisterServerIdent($sesscode) {      
   $idcode = generateRandomString(20); 
   require(serverkeys . "/sspdo.zck");  
   $delSQL = "delete FROM ORSCHED.dv_ss_srvIdents where sessid = :sessioncode";
   $dr = $conn->prepare($delSQL); 
   $dr->execute(array(':sessioncode' => $sesscode));
   $insSQL = "insert into ORSCHED.dv_ss_srvIdents (sessid, accesscode, onwhen) values(:sesscode , :accesscode , now())";
   $iR = $conn->prepare($insSQL); 
   $iR->execute(array(':sesscode' => $sesscode, ':accesscode' => $idcode)); 
   $rtn = cryptservice( $sesscode , 'e' , true , $sesscode );
   return $rtn;  
}

function cryptservice( $string, $action = 'e', $usedbkey = false, $passedsid = "") {
    $output = false;
    require( serverkeys . "/serverid.zck");
    if ($usedbkey) {
        //session_start(); 
        $sid = (trim($passedsid) === "") ? session_id() : $passedsid;
        require(serverkeys . "/sspdo.zck");
        $sql = "select accesscode from ORSCHED.dv_ss_srvIdents where sessid = :sid";
        $rs = $conn->prepare($sql); 
        $rs->execute(array(':sid' => $sid));
        if ($rs->rowCount() < 1) { 
            exit(); 
        } else { 
          $r = $rs->fetch(PDO::FETCH_ASSOC);
        }
        $localsecretkey = $r['accesscode']; 
        $secret_key = $localsecretkey;
        $secret_iv = $localsecretkey;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $localsecretkey );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        if ( $action == 'e' ) {
          $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        if( $action == 'd' ) {
          $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
    } else { 
      $secret_key = $secretkey;
      $secret_iv = $siv;
      $encrypt_method = "AES-256-CBC";
      $key = hash( 'sha256', $secret_key );
      $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
      if ( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
      }
      if( $action == 'd' ) {
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
      }
    }
    return $output;
}

function callrestapi($method, $url, $user = "", $apikeyencrypt = "", $data = false) { 
  try {
    $ch = curl_init(); 
    if (FALSE === $ch) { return Exception('failed to initialize'); } 
    switch ($method) { 
      case "POST": 
        curl_setopt($ch, CURLOPT_POST, 1); 
        if ($data) { 
          curl_setopt($ch,CURLOPT_POSTFIELDS, $data); 
        }
      break; 
      case "GET": 
        curl_setopt($ch, CURLOPT_GET, 1); 
      break;
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = array(
        'Content-Type:application/json'
       ,'Authorization: Basic '. base64_encode("{$user}:{$apikeyencrypt}")
    );  //ADD AUTHORIZATION HEADERS HERE
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $content = curl_exec($ch);
    if (FALSE === $content) { 
      return Exception(curl_error($ch),curl_errno($ch));
    } else {
      return $content;
    }
  } catch(Exception $e) { 
    return sprintf('CURL failed with error #%d: %s', $e->getCode(), $e->getMessage()); 
  } 
}

function base64file($path, $identifier, $expectedObject, $createObject = true, $additionals = "") { 
  $object = NULL;    
  if (!file_exists($path) || !is_file($path)) {
  } else { 
    ob_start(); 
    readfile($path);
    $filecontent = base64_encode(ob_get_clean()); 
    if ($createObject) { 
      $mime = mime_content_type($path);
      switch ($expectedObject) { 
        case "image": 
          $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";
        break;
        case "png":
          $object = "<img id=\"{$identifier}\" src=\"data:image/png;base64,{$filecontent}\" {$additionals}>";
        break;
        case "pdf":
          //NOT YET DONE
            $object = "<object style=\"width: 100%; height: 100%;\" data=\"data:application/pdf;base64,{$filecontent}\" type=\"application/pdf\" class=\"internal\" {$additionals} >  <embed  style=\"width: 100%; height: 100%;\" src=\"data:application/pdf;base64,{$filecontent}\"  type=\"application/pdf\" {$additionals} >";
        break;
        case "pdfhlp":
          //NOT YET DONE
            $object = "<object style=\"width: 100%; height: 75vh;\" data=\"data:application/pdf;base64,{$filecontent}\" type=\"application/pdf\" class=\"internal\" {$additionals} >  <embed  style=\"width: 100%; height: 100%;\" src=\"data:application/pdf;base64,{$filecontent}\"  type=\"application/pdf\" {$additionals} >";
        break;        
        case "favicon": 
          $object = "<link href=\"data:image/x-icon;base64,{$filecontent}\" rel=\"icon\" type=\"image/x-icon\" {$additionals}>";
        break;
        case "js":
          $object = "<script type=\"text/javascript\" src=\"data:text/javascript;base64,{$filecontent}\" {$additionals}></script>";
          break;
        case "bgurl":
          $object = " url('data:{$mime};base64,{$filecontent}') ";
          break; 
        case 'gif':
            $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";            
            break;
        default:
          $object = "<img id=\"{$identifier}\" src=\"data:{$mime};base64,{$filecontent}\" {$additionals}>";
      } 
    } else { 
      $object = $filecontent;
    }
  }    
  return $object;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function guidv4() {
    if (function_exists('com_create_guid') === true) { return trim(com_create_guid(), '{}');  }
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function chtnencrypt($pdata) {  
  $publicCert = openssl_pkey_get_public("file:///" . serverkeys . "/pubkey.pem");
  openssl_public_encrypt($pdata, $crypted, $publicCert);
  $crypted = base64_encode($crypted);  
  return "{$crypted}";
}

function chtndecrypt($pdata) {   
  $encMsg = base64_decode($pdata); 
  $privateKey = openssl_pkey_get_private("file:///" . serverkeys . "/privatekey.pem");     
  //openssl_pkey_export($privateKey, $pkeyout);  TO TEST AND OUTPUT PRIVATE KEY - TESTING AND REFERENCE ONLY
  openssl_private_decrypt($encMsg, $decrypted, $privateKey); 
  return $decrypted; 
}

