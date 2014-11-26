<?php

ob_start();

#############################################################################
#                                                                           #
# GXSecurity Version 1.8                                                    #
# Copyright 2007-2014 Gerd Xhonneux                                         #
# http://xtc.xhonneux.com                                                   #
# modified by manu www.pixolution.ch - look for #EM[+|-|~]                  #
#                                                                           #
# This is a modified and enhanced version of original "CCISecurity":        #
# Cafe CounterIntelligence PHP Website Security Script 1.8                  #
# Copyright 2002, 2003 Mike Parniak                                         #
# www.cafecounterintelligence.com                                           #
#                                                                           #
# The robot file is copyright www.bbclone.de                                #
#                                                                           #
# This program is free software; you can redistribute it and/or modify      #
# it under the terms of the GNU General Public License as published by      #
# the Free Software Foundation; either version 2 of the License, or         #
# (at your option) any later version.                                       #
#                                                                           #
# This program is distributed in the hope that it will be useful,           #
# but WITHOUT ANY WARRANTY; without even the implied warranty of            #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             #
# GNU General Public License for more details.                              #
#                                                                           #
# You should have received a copy of the GNU General Public License         #
# along with this program; if not, write to the Free Software               #
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA   #
#                                                                           #
#############################################################################


function GXSecurity() {

global $su, $sl, $plugin_cf, $plugin_tx, $pth, $plugin;

##################
#
# Configuration Section - Set these variables first (or use default if you want)
#
##################

$usehtaccessbans      = $plugin_cf['gxsecurity']['usehtaccessbans'];                      # 1 = modify .htaccess to ban IPs, 0 = don't ban IPs.
$filterGETvars        = $plugin_cf['gxsecurity']['filterGETvars'];                        # 1 = sterilize HTML tags in GET variables, 0 = don't
$filterCOOKIEvars     = $plugin_cf['gxsecurity']['filterCOOKIEvars'];                     # 1 = sterilize HTML tags in COOKIE variables, 0 = don't
$filterPOSTvars       = $plugin_cf['gxsecurity']['filterPOSTvars'];                       # 1 = sterilize HTML tags in POST variables, 0 = don't
$extraPOSTprotection  = $plugin_cf['gxsecurity']['extraPOSTprotection'];                  # 1 = use the extra POST protection, 0 = don't
$extraGETprotection   = $plugin_cf['gxsecurity']['extraGETprotection'];                   # 1 = use the extra GET protection, 0 = don't (not recommended!)
$checkmultiPOST       = $plugin_cf['gxsecurity']['checkmultiPOST'];                       # 1 = only allow maxmultiPOST number of successive POSTs, 0 = don't care
$maxmultiPOST         = $plugin_cf['gxsecurity']['maxmultiPOST'];                         # Maximum number of POST operations in a row, if checkmultipost is on.
$zipcompress          = $plugin_cf['gxsecurity']['zipcompress'];                          # 1 = Compress pages using GZIP library (lower bandwidth, higher CPU), 0 = don't
$compresslevel        = $plugin_cf['gxsecurity']['compresslevel'];                        # Compression level for zipcompressing, from 1 (low) to 9 (maximum)
$cpuloadmonitor       = $plugin_cf['gxsecurity']['cpuloadmonitor'];                       # 1 = block access if over a certain system load, 0 = don't
$cpumaxload           = $plugin_cf['gxsecurity']['cpumaxload'];			                      # Maximum 5 minute system load average before blocking access
$gxsessionpath        = $plugin_cf['gxsecurity']['gxsessionpath'];		 	                  # if not blank, sets a directory path to store session files.
$filterblacklistedIPs = $plugin_cf['gxsecurity']['filterblacklistedIPs'];                 # 1 = filter IPs that are blacklisted, 0 = don't 
$dnsbl_lists          = explode(",", $plugin_cf['gxsecurity']['dnsbl_lists']);            # DNSBL list
$filtercountries      = $plugin_cf['gxsecurity']['filtercountries'];                      # 1 = filter countries from "countries.txt", 0 = don't  
$crackcheck           = $plugin_cf['gxsecurity']['crackcheck'];                           # 1 = check for cracks and injections, 0 = don't
$gxsecurity_plugin            = basename(dirname(__FILE__),"/");
$gxsecurity_pluginfolder      = $pth['folder']['plugins'].$gxsecurity_plugin;                             # folder where the plugin resides
$countryfile          = $gxsecurity_pluginfolder."/config/".$plugin_cf['gxsecurity']['countryfile']; # file that contains the countries to ban
$crackfile            = $gxsecurity_pluginfolder."/config/".$plugin_cf['gxsecurity']['crackfile'];   # file that contains the crack and injection keywords
$htaccessfolder       = $pth['folder']['base'];  
$badattempts          = $plugin_cf['gxsecurity']['badattempts'];                          # number of bad attempts (before banning the ip in .htaccess or give a message that too many rapid accesses were done)
$httpBL_key           = $plugin_cf['gxsecurity']['httpBL_key'];                           # http:BL API key
$httpBL_spamthreat    = $plugin_cf['gxsecurity']['httpBL_spamthreat'];                    # Comment spammer threat level
$httpBL_otherthreat   = $plugin_cf['gxsecurity']['httpBL_otherthreat'];                   # Other types threat level
$checkbothost         = $plugin_cf['gxsecurity']['checkbothost'];                         # 1 = check host, 0 = don't
$hostchecktype        = $plugin_cf['gxsecurity']['hostchecktype'];                        # 1 = standard ; 2 = *nix ; 3 = win 
$copyrightmsg         = "<p><i><small>Site protected by <a href='http://xtc.xhonneux.com/?Projekte:GXSecurity'>GXSecurity</a></small></i></p>";  

require $gxsecurity_pluginfolder."/lib/robot.php"; // Robot file from bbclone to ignore them in cookie test to prevent that they are banned

$a = array_keys($robot);

// Check if it is a robot/spider (for cookies) 
$isBot = 0;
$hostname = "";
$ip = $_SERVER["REMOTE_ADDR"];
foreach ($a as $bot) {
  if(ereg(strtolower(trim($bot)), strtolower($_SERVER['HTTP_USER_AGENT']))) {
   if ($checkbothost) {     // recheck host to confirm that's really an IP from a robot and not just a banterer who modified his user agent to avoid cookies 
    if ($hostchecktype == '2'){
       $hostname = GXgethost_nix($ip);
    } elseif ($hostchecktype == '3'){
       $hostname = GXgethost_win($ip);
    } else {
       $hostname = gethostbyaddr($ip);
    }
    if(ereg(strtolower(trim($bot)), strtolower($hostname))) {
      $isBot = 1; # 1 = It's a robot/spider
    }
   } else {
     $isBot = 1; # 1 = It's a robot/spider
   }
  }
}

##### Encryption/Encoding Variables

$javababble = 0;				# 1 = Use Encoding/Encrypting (Must be on for any), 0 = Don't
$javaencrypt = 0;				# Do actual encrypting of HTML, not just escaping (warning: may slow display)
$preservehead = 1;				# 1 = Only encode/encrypt between BODY tags, 0 = encode/encrypt whole document

##################
#
# Check for in-script overrides
#
##################

if (isset($zipoverride)) {
  if (!isset($_REQUEST["zipoverride"])) {
    $zipcompress = $zipoverride;
    unset($zipoverride);
  }
}

if (isset($babbleoverride)) {
  if (!isset($_REQUEST["babbleoverride"])) {
    $javababble = $babbleoverride;
    unset($babbleoverride);
  }
}

##################
#
# Function: GXJavaBabble
#
# Usage: Takes some HTML, url-encodes it (jumbles it) then returns the javascript needed to display it properly.
#
##################

function GXJavaBabble($myoutput) {
  global $mycrypto, $myalpha2, $javaencrypt, $preservehead;
  $s = $myoutput;
  $s = ereg_replace("\n","",$s);

  if ($preservehead) {  
    eregi("(^.+<body[^>]*>)",$s,$chunks);
    $outputstring = $chunks[1];
    eregi_replace($headpart,"",$s);

    eregi("(</body[^>]*>.*)",$s,$chunks);
    $outputend = $chunks[1];
    eregi_replace($footpart,"",$s);
  } else {
    $outputstring = "";
    $outputend = "";
  }
  
  if ($javaencrypt) {
    $s = strtr($s,$myalpha2,$mycrypto);
    $s = rawurlencode($s);
    $outputstring .= "<script>var cc=unescape('$s'); ";
    $outputstring .= "var index = document.cookie.indexOf('" . md5($_SERVER["REMOTE_ADDR"] . $_SERVER["SERVER_ADDR"]) . "='); " .
      "var aa = '$myalpha2'; " .
      "if (index > -1) { " .
      "  index = document.cookie.indexOf('=', index) + 1; " .
      "  var endstr = document.cookie.indexOf(';', index); " .
      "  if (endstr == -1) endstr = document.cookie.length; " .
      "  var bb = unescape(document.cookie.substring(index, endstr)); " .
      "} " .
      "cc = cc.replace(/[$myalpha2]/g,function(str) { return aa.substr(bb.indexOf(str),1) }); document.write(cc);";
  } else {
    $outputstring .= "<script>document.write(unescape('" . rawurlencode($s) . "'));";
  }
  $outputstring .= "</script><noscript>".$plugin_tx['gxsecurity']['enable_javascript']."</noscript>" . $outputend;
       
  return $outputstring;
}

##################
#
# Function: GXClearSession
#
# Format: GXClearSession()
# Returns: Nothing
#
# Usage: Clears all the data out of the session record other than data used for this script
#
##################

function GXClearSession() {
  $getvariables = array_keys($_SESSION);
  $count = 0;
  while($count < count($getvariables)) {
    if (substr($getvariables[$count],0,6) != "gxsec-") {
      session_unregister($getvariables[$count]); 
      if (ini_get('register_globals')) unset($$getvariables[$count]);
    }
    $count++;
  }
}

##################
#
# Function: GXBanIP
#
# Format: GXBanIP(IPAddress)
# Returns: Nothing
#
# Usage: Will open and add a deny line to the .htaccess file in the same directory to deny all
#        accessing by a given IP address.
#
##################

function GXBanIP($base,$banip) {
  $filelocation = $base.".htaccess";
  $limitend = "# End of GX Security Section\n";
  $newline = "deny from $banip\n";
  if (file_exists($filelocation)) {
    $mybans = file($filelocation);
    $lastline = "";
    if (in_array($newline,$mybans)) exit();
    if (in_array($limitend,$mybans)) {      
      $i = count($mybans)-1;
      while ($mybans[$i] != $limitend) {
        $lastline = array_pop($mybans) . $lastline;
        $i--;
      }
      $lastline = array_pop($mybans) . $lastline;
      $lastline = array_pop($mybans) . $lastline;
      $lastline = array_pop($mybans) . $lastline;
      array_push($mybans,$newline,$lastline);
    } else {
      array_push($mybans,"\n\n# GX Security Script\n","<Limit GET POST>\n","order allow,deny\n",$newline,"allow from all\n","</Limit>\n",$limitend);
    }
  } else {
    $mybans = array("# GX Security Script\n","<Limit GET POST>\n","order allow,deny\n",$newline,"allow from all\n","</Limit>\n",$limitend);
  }  
  $myfile = fopen($filelocation,"w");
  fwrite($myfile,implode($mybans,""));
  fclose($myfile);
    
}

##################
#
# Function: GXFloodCheck
#
# Format: GXFloodCheck("identifier",interval,threshold)
# Returns: 1 if requested without minimum interval, a threshold number of times.  0 if not.
#
# Usage: For functions that require flood control pass a unique identifier, the minimum number of
#        seconds that should be waited between repeats of the function, and a number of times that
#        function can be called too quickly before it sets off the flood trapping.
#
##################

function GXFloodCheck($identifier,$interval,$threshold=1) {
  $myresult = 0;
  if (isset($_SESSION["gxsec-" . $identifier])) {
    if ($_SESSION["gxsec-" . $identifier] > (time()-$interval)) {
      if ($threshold<2) {
        $myresult = 1;
      } else {
        if (!isset($_SESSION["gxsec-" . $identifier . "-counter"])) {
          $_SESSION["gxsec-" . $identifier . "-counter"] = 1;
        } else {
          $_SESSION["gxsec-" . $identifier . "-counter"]++;
          if ($_SESSION["gxsec-" . $identifier . "-counter"] >= $threshold) {
            $myresult = 1;
          }
        }
      }
    }
    $_SESSION["gxsec-" . $identifier] = time();
  }
  return $myresult; 
}

##################
#
# Function: GXWhois
#
# Format: GXWhois(IP)
# Returns: Array with the whois informations about the IP.
#
##################

function GXWhois($ip) {
  $server="whois.ripe.net";
  // echo "Verbinde zu $server:43...<br>\n";
  $fp=@fsockopen($server,43,$errno,$errstr,15); //EM~
  if(!$fp) {
    // echo "Verbindung zu $server:43 konnte nicht hergestellt werden.<br>\n";
    // echo "$errno: $errstr<br>\n";
    return false;
  } else {
    $info=array();
    // echo "Verbunden mit $server:43 hergestellt, sende Anfrage...<br>\n";
    // echo "(IP/Domain: $ip)<br>\n";
    fputs($fp,"$ip\r\n");
    while(!feof($fp)) {
      $s = fgets($fp,256);
      // echo "<br>$s<br>";
      if ((substr($s,0,5)=="descr") AND (!isset($info['descr'])))
        $info['descr']=trim(substr($s,6));
      if ((substr($s,0,7)=="country") AND (!isset($info['country'])))
        $info['country']=substr(trim(substr($s,8)),0,2);
    }
    fclose($fp);
    // echo "Verbindung beendet.<br>\n";
    return $info;
  }
}

##################
#
# Function: GXBlacklisted
#
# Format: GXBlacklisted(IP,dnsbl_lists)
# Returns: If and where IP is blacklisted.
#          Ex: 250.145.186.203.list.dsbl.org
#
##################

function GXBlacklisted($ip,$dnsbl_lists) {
//  $dnsbl_lists = array("bl.spamcop.net", "list.dsbl.org", "sbl.spamhaus.org","xbl.spamhaus.org");
//   if ($ip && preg_match('/^([0-9]{1, 3})\.([0-9]{1, 3})\.([0-9]{1, 3})\.([0-9]{1, 3})/', $ip)) {
       $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
       $on_win = substr(PHP_OS, 0, 3) == "WIN" ? 1 : 0;
       foreach ($dnsbl_lists as $dnsbl_list){
           if (function_exists("checkdnsrr")) {
               if (checkdnsrr($reverse_ip . "." . $dnsbl_list . ".", "A")) {
                   return $reverse_ip . "." . $dnsbl_list;
               }
           } else if ($on_win == 1) {
               $lookup = "";
               @exec("nslookup -type=A " . $reverse_ip . "." . $dnsbl_list . ".", $lookup);
               foreach ($lookup as $line) {
                   if (strstr($line, $dnsbl_list)) {
                       return $reverse_ip . "." . $dnsbl_list;
                   }
               }
           }
       }
//   }
   return false;
}


##################
#
# Function: GXhttpBL
#
# Format: GXhttpBL(httpBL_key,IP,spamthreat,otherthreat)
# Returns: If the IP is on http:BL from http://www.projecthoneypot.org/ 
#
##################

function GXhttpBL($key,$ip,$spamthreat,$otherthreat) {
$lookup = $key . '.' . implode('.', array_reverse(explode ('.', $ip ))) . '.dnsbl.httpbl.org';

// check query response
$result = explode( '.', gethostbyname($lookup));

if ($result[0] == 127) {
	// query successful !
	$activity = $result[1];
	$threat = $result[2];
	$type = $result[3];
	// if ($type & 0) $typemeaning .= 'Search Engine, ';
	// if ($type & 1) $typemeaning .= 'Suspicious, ';
	// if ($type & 2) $typemeaning .= 'Harvester, ';
	// if ($type & 4) $typemeaning .= 'Comment Spammer, ';
  // Blocking policy
  if (
  ($type>= 4 && $threat> $spamthreat) || // Comment spammer with very low threat level
  ($type <4 && $threat> $otherthreat)    // Other types, with threat level greater than 40
  ) {
  	return true;
  }
}
return false;
}

##################
#
# Function: GXgethost_win
#
# Format: GXgethost_win(IP)
# Returns: Returns the host of the IP 
#
##################

// If you're using a server on Windows, this is faster then gethostbyaddr()
function GXgethost_win($ip='') {
   if ($ip=='') $ip = $_SERVER['REMOTE_ADDR'];
   $longisp = @gethostbyaddr($ip);
   $isp = explode('.', $longisp);
   $isp = array_reverse($isp);
   $tmp = $isp[1];
   if (preg_match("/\<(org?|com?|net)\>/i", $tmp)) {
       $myisp = $isp[2].'.'.$isp[1].'.'.$isp[0];
   } else {
       $myisp = $isp[1].'.'.$isp[0];
   }
   if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $myisp))
      return 'ISP lookup failed.';
   return $myisp;
}

##################
#
# Function: GXgethost_nix
#
# Format: GXgethost_nix(IP)
# Returns: Returns the host of the IP 
#
##################


// If your server is on a *nix system, this is faster then gethostbyaddr()
function GXgethost_nix ($ip) {
 $host = `host $ip`;
 return (($host ? end ( explode (' ', $host)) : $ip));
}

##################
#
# Function: GXLogfile
#
# Format: GXLogfile(Basefolder,Log cycle,Log header,CSV-Data)
# Returns: 
#
##################

function GXLogfile($basefolder,$logcycle,$logheader,$logdelimiter,$csvdata) {
   $date       = date("Y-m-d");
   $hourdiff   = "0"; // hours difference between server time and local time
   $timeadjust = ($hourdiff * 60 * 60);
   $time       = trim(date(" H:i",time() + $timeadjust));

   if ($logcycle == "Y") {
     $filename = date("Y");
   } else if ($logcycle == "M") {
     $filename = date("Y-m");
   } else {
     $filename = date("Y-m-d");
   }

   $logfile =  $basefolder . "/log/" . $filename . ".csv";
   $is_first = ! file_exists($logfile);

   $rec =  '';   
   if ($is_first) {
      $rec .= $logheader."\n"; // Header 
   }  
   $rec .=  $date . $logdelimiter;
   $rec .=  $time . $logdelimiter;
   $rec .=  $csvdata;
   $rec .= "\n";
    
   $fd = @fopen($logfile, 'a');
   fwrite($fd,$rec);
   fclose($fd);
   return true;
}


################################################################################

srand(time());

if (eregi("gxsecurity\.php",$_SERVER["SCRIPT_NAME"])) exit();

if ($gxsessionpath != "") session_save_path($gxsessionpath);
session_name(md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_HOST"] . "GX"));

session_write_close(); //close random open sessions #EM+

ini_set("session.use_only_cookies","1");
ini_set("session.use_trans_sid","0");

if (($zipcompress) && (eregi("gzip",$_SERVER["HTTP_ACCEPT_ENCODING"]))) {
  ini_set("zlib.output_compression","On");
  ini_set("zlib.output_compression_level",$compresslevel);
  ob_start("ob_gzhandler"); 
}
if ($javababble) {
  if ($javaencrypt) {
    $myalpha = array_merge(range("a","z"),range("A","Z"),range("0","9"));
    $myalpha2 = implode("",$myalpha);
    shuffle($myalpha);
    $mycrypto = implode("",$myalpha);
    setcookie(md5($_SERVER["REMOTE_ADDR"] . $_SERVER["SERVER_ADDR"]),$mycrypto);
    unset($myalpha);
  }
  ob_start("gxJavaBabble");
}

if (substr_count($_SERVER["SERVER_NAME"],".")>1) {
  $cookiedomain = eregi_replace("^[^\.]+\.",".",$_SERVER["SERVER_NAME"]);
} else $cookiedomain = "." . $_SERVER["SERVER_NAME"];


// $ip = $_SERVER["REMOTE_ADDR"];
$mykeyname = md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_HOST"] . $_SERVER["DOCUMENT_ROOT"] . "GX");
$myposthashname = md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_HOST"] . $_SERVER["PATH"] . "GX");

$myhash = md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . 
						$_SERVER["HTTP_HOST"] . $_SERVER["DOCUMENT_ROOT"] . 
						$_SERVER["SERVER_SOFTWARE"] . $_SERVER["PATH"] . "X");
											
$mysession = md5($_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_HOST"]);			

session_id($mysession);
session_start();


# Sneaky cookie-storing flooding programs tend to trip this - a cookie not meant to be returned.

if ((isset($_SESSION["gxsec-tripwire"])) && (isset($_COOKIE[$_SESSION["gxsec-tripwire"]]))) {
  GXBanIP($htaccessfolder,$ip);
  exit();
}
$tripwire = md5(uniqid(time()));
setcookie($tripwire,md5(uniqid(time())),time()-999999,"/",$cookiedomain);
$_SESSION["gxsec-tripwire"]=$tripwire;

# End of the tripwire routine


if (!isset($_SESSION["gxsec-errors"])) $_SESSION["gxsec-errors"] = 0;
if ($_SESSION["gxsec-errors"]>=$badattempts) {
  GXBanIP($htaccessfolder,$ip);
  exit();
}

if ($_SESSION["gxsec-myhash"] != $myhash) {		
  $_SESSION["gxsec-myhash"] = $myhash;
$_SESSION["gxsec-errors"]++;
  session_write_close();
Header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}

// Original line commented out
// if ((!isset($_COOKIE[$mykeyname])) || ($_COOKIE[$mykeyname] != $myhash)) {

// Check for cookies only if it is not a bot to prevent banning robots like Google etc.
if (((!isset($_COOKIE[$mykeyname])) || ($_COOKIE[$mykeyname] != $myhash)) && ($isBot != 1)) {

  if (!isset($_SESSION["gxsec-nocookie"])) {
    $_SESSION["gxsec-nocookie"] = 1;
  } else {
    $_SESSION["gxsec-nocookie"]++;
  }

  if (($usehtaccessbans) && ($_SESSION["gxsec-nocookie"]>$badattempts)) GXBanIP($htaccessfolder,$ip);
    
  setcookie($mykeyname,$myhash,0,"/",$cookiedomain);
        
  if ($_SESSION["gxsec-nocookie"]>2) {
    $logtext = "009".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Cookies disabled";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['cookies_disabled']." (IP: ".$ip.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
    session_write_close();
	exit();
  } 
  if ($extraGETprotection) {
    $_SESSION["gxsec-hash"] = md5(uniqid(time()));
    setcookie($myposthashname,$_SESSION["gxsec-hash"],0,"/",$cookiedomain);  
  } 
  GXClearSession();  
  session_write_close();
  Header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
} else $_SESSION["gxsec-nocookie"] = 0;

if (isset($_SESSION["gxsec-fastaccesses"])) { //#EM+
	if (($usehtaccessbans) && ($_SESSION["gxsec-fastaccesses"]>40)) GXBanIP($htaccessfolder,$ip);

	if ($_SESSION["gxsec-fastaccesses"]>$badattempts) {
	  if ((time()- $plugin_cf['gxsecurity']['waitingtime'] ) < $_SESSION["gxsec-lastaccess"]) {
	    $logtext = "008".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Too many rapid requests";
	    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
	    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['rapid_request']." (".$ip.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
	    $_SESSION["gxsec-fastaccesses"]++;
	    $_SESSION["gxsec-lastaccess"]=time();
	    exit();
	  }
	}
} //#EM+

if (!isset($_SESSION["gxsec-lastaccess"])) {
  $_SESSION["gxsec-lastaccess"]=time();
} else {
  if ((time()-2) < $_SESSION["gxsec-lastaccess"]) {
    if (!isset($_SESSION["gxsec-fastaccesses"])) $_SESSION["gxsec-fastaccesses"] = 0;
    $_SESSION["gxsec-fastaccesses"]++;
  } else {
    $_SESSION["gxsec-fastaccesses"] = 0;
  }
  $_SESSION["gxsec-lastaccess"]=time();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if ($checkmultiPOST) {
    if (isset($_SESSION['gxsec-lastoperation']) AND ($_SESSION["gxsec-lastoperation"] == "POST") && ($_SESSION["gxsec-opcount"] >= $maxmultiPOST)) {
      $logtext = "007".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Multiple POST operations in sequence";
      GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
      echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['multipost']."<br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
      $_SESSION["gxsec-errors"]++;
      exit(); 
    }
  }     

  if ($extraPOSTprotection) {
    if ((!isset($_COOKIE[$myposthashname])) || ($_COOKIE[$myposthashname] != $_SESSION["gxsec-hash"])) {
      $logtext = "006".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Not correct security data to complete POST operation";
      GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
      echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['secdata_post']."<br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
      $_SESSION["gxsec-errors"]++;
      exit();
    }
  }
} else if (($extraGETprotection) && ($_SERVER["REQUEST_METHOD"] == "GET")) {
  if ((!isset($_COOKIE[$myposthashname])) || ($_COOKIE[$myposthashname] != $_SESSION["gxsec-hash"])) {
    $logtext = "005".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Not correct security data to complete GET operation";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['secdata_get']."<br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
    $_SESSION["gxsec-errors"]++;
    exit();
  }
} else if ($_SERVER["REQUEST_METHOD"] != "GET") {
    exit();
}

if (($extraPOSTprotection) || ($extraGETprotection)) {
  srand(time());
  $_SESSION["gxsec-hash"] = md5(uniqid(time()));
  setcookie($myposthashname,$_SESSION["gxsec-hash"],0,"/",$cookiedomain);
}

//if ($_SESSION["gxsec-lastoperation"] == $_SERVER["REQUEST_METHOD"]) {
if (isset($_SESSION['gxsec-lastoperation']) AND $_SESSION["gxsec-lastoperation"] == $_SERVER["REQUEST_METHOD"]) {
  if (!isset($_SESSION["gxsec-opcount"])) {
    $_SESSION["gxsec-opcount"] = 1;
  } else {
    $_SESSION["gxsec-opcount"]++;
  }
} 
else {
	$_SESSION["gxsec-lastoperation"] = $_SERVER["REQUEST_METHOD"];
}

# Make special characters safe in any GET based cgi variables.

if ($filterGETvars) {
  $getvariables = array_keys($_GET);
  $count = 0;
  while($count < count($getvariables)) {
    $_GET[$getvariables[$count]] = htmlspecialchars($_GET[$getvariables[$count]]);
    if (ini_get('register_globals')) $$getvariables[$count] = $_GET[$getvariables[$count]];
    $count++;
  }
}

if ($filterPOSTvars) {
  $getvariables = array_keys($_POST);
  $count = 0;
  while($count < count($getvariables)) {
    $_POST[$getvariables[$count]] = htmlspecialchars($_POST[$getvariables[$count]]);
    if (ini_get('register_globals')) $$getvariables[$count] = $_POST[$getvariables[$count]];
    $count++;
  }
}

if ($filterCOOKIEvars) {
  $getvariables = array_keys($_COOKIE);
  $count = 0;
  while($count < count($getvariables)) {
    $_COOKIE[$getvariables[$count]] = htmlspecialchars($_COOKIE[$getvariables[$count]]);
    if (ini_get('register_globals')) $$getvariables[$count] = $_COOKIE[$getvariables[$count]];
    $count++;
  }
}

if ($cpuloadmonitor) {
  $myshelldata = shell_exec("uptime");
  $myshelldata = eregi_replace(".*average.*: ","",$myshelldata);
  $myshelldata = eregi_replace(", .*","",$myshelldata);
  if ($myshelldata >= $cpumaxload) {
    $logtext = "004".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."CPU overload";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['server_busy']."<br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
    exit();
  }
  unset($myshelldata);
}

//Check if the IP is blacklisted
if ($filterblacklistedIPs) {
  $IPblacklisted = GXBlacklisted($ip,$dnsbl_lists); // If and where IP is blacklisted (Ex: 250.145.186.203.list.dsbl.org)
  if (!empty($IPblacklisted)) {
    $logtext = "003".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Blacklisted IP";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['ip_blacklisted']." (".$ip.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
    $_SESSION["gxsec-errors"]++;
    exit();
  } 
}

// check if the IP is on http:BL
if (!empty($httpBL_key)) {
  $IsOnhttpBL = GXhttpBL($httpBL_key,$ip,$httpBL_spamthreat,$httpBL_otherthreat) ; // If the IP is on http:BL from http://www.projecthoneypot.org/
  if ($IsOnhttpBL) {
    $logtext = "010".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."IP on http:BL";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['ip_blacklisted']." (".$ip.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
    $_SESSION["gxsec-errors"]++;
    exit();
  } 
}

//Check if the country has to be filtered
if ($filtercountries) {
  $countries = file($countryfile);
  $whoisinfo = GXWhois($ip);
  $country = $whoisinfo['country']; // Country where the visitor comes from (Ex: BE)
  // Check if country has to be banned 
  foreach($countries as $ban) {
    if(ereg(strtolower(trim($ban)), strtolower($country))) {
      $logtext = "002".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Country ".strtoupper($country)." banned";
      GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
      echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['country_banned']." (".$country.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";
      exit();
    }
  }
}

//Check for cracks and injections
if ($crackcheck) {
  $cracktrack = $_SERVER['QUERY_STRING'];
  $wormprotector = file($crackfile);                   
  array_walk($wormprotector , create_function('&$temp', '$temp = strtolower(trim($temp));')); // trim the array to remove the line feeds
	$cracktrack=strtolower($cracktrack);
  $checkworm = str_replace($wormprotector, '*', $cracktrack);
  if ($cracktrack != $checkworm) {
    $logtext = "001".$plugin_cf['gxsecurity']['logdelimiter'].$ip.$plugin_cf['gxsecurity']['logdelimiter']."Crack or injection detected";
    GXLogfile($gxsecurity_pluginfolder,$plugin_cf['gxsecurity']['logcycle'],$plugin_cf['gxsecurity']['logheader'],$plugin_cf['gxsecurity']['logdelimiter'],$logtext);
    echo "<div id='gxsecurity'><b><h1>".$plugin_tx['gxsecurity']['access_denied']."</h1><br><br>".$plugin_tx['gxsecurity']['attack_detected']." (".$ip.") <br>".$plugin_tx['gxsecurity']['contact_admin']."</b>".$copyrightmsg."</div>";    
    $_SESSION["gxsec-errors"]++;
    exit();
  }
}

unset($count);
unset($getvariables);
unset($ip);
unset($cookiedomain);
unset($mykeyname);
unset($myposthashname);
unset($myhash);
unset($mysession);
unset($logtext);

$_SESSION["gxsec-errors"] = 0;
if (connection_aborted()) exit();
session_write_close(); //#EM+ close proprietary session
}
?>
