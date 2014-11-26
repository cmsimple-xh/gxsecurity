<?php
  $plugin_tx['gxsecurity']['mnu_main']="GXSecurity data";
  $plugin_tx['gxsecurity']['mnu_css']="Stylesheet settings";
  $plugin_tx['gxsecurity']['mnu_config']="Configuration settings";
  $plugin_tx['gxsecurity']['mnu_language']="Language settings";
  $plugin_tx['gxsecurity']['mnu_help']="Help";
  $plugin_tx['gxsecurity']['mnu_country_file']="Country file";
  $plugin_tx['gxsecurity']['mnu_crack_file']="File with crack expressions";
  $plugin_tx['gxsecurity']['access_denied']='Access Denied';
  $plugin_tx['gxsecurity']['contact_admin']='For further informations contact the webmaster.';  
	$plugin_tx['gxsecurity']['cookies_disabled']='You must enable cookies in order to access this website. Please do so before returning, as continued attempts to access without cookies may result in a banning of this ip.';
	$plugin_tx['gxsecurity']['country_banned']='This country is banned.';
	$plugin_tx['gxsecurity']['ip_blacklisted']='This IP address is blacklisted.';	
	$plugin_tx['gxsecurity']['rapid_request']='There have been too many rapid requests from this IP address. Please wait a while before accessing this site again.';
	$plugin_tx['gxsecurity']['server_busy']='The server is currently too busy to serve your request.  We apologize for the inconvenience.';	
  $plugin_tx['gxsecurity']['enable_javascript']='You must enable Javascript in order to view this webpage.';	
  $plugin_tx['gxsecurity']['multipost']='You may not make multiple POST operations in sequence - please return to the website and try again.';  
  $plugin_tx['gxsecurity']['secdata_post']='Your browser did not send the correct security data needed to complete a POST operation.  Make sure that you have cookies enabled and then try again, or contact the administration if you feel you are receiving this message in error.';
  $plugin_tx['gxsecurity']['secdata_get']='Your browser did not send the correct security data needed to complete a GET operation.  Make sure that you have cookies enabled and then try again, or contact the administration if you feel you are receiving this message in error.';
  $plugin_tx['gxsecurity']['attack_detected']='An attack was detected and blocked.';      
  $plugin_tx['gxsecurity']['show_log']='Show log file in new window';
  $plugin_tx['gxsecurity']['cf_usehtaccessbans'] ="1 = modify .htaccess to ban IPs, 0 = don't ban IPs";

$plugin_tx['gxsecurity']['cf_filterGETvars'] ="1 = sterilize HTML tags in GET variables, 0 = don't";

$plugin_tx['gxsecurity']['cf_filterCOOKIEvars'] ="1 = sterilize HTML tags in COOKIE variables, 0 = don't";

$plugin_tx['gxsecurity']['cf_filterPOSTvars'] ="1 = sterilize HTML tags in POST variables, 0 = don't";

$plugin_tx['gxsecurity']['cf_extraPOSTprotection'] ="1 = use the extra POST protection, 0 = don't";

$plugin_tx['gxsecurity']['cf_extraGETprotection'] ="1 = use the extra GET protection, 0 = don't (not recommended!)";

$plugin_tx['gxsecurity']['cf_checkmultiPOST'] ="1 = only allow maxmultiPOST number of successive POSTs, 0 = don't care";

$plugin_tx['gxsecurity']['cf_maxmultiPOST'] ="Maximum number of POST operations in a row, if checkmultipost is on";
$plugin_tx['gxsecurity']['cf_zipcompress'] ="1 = Compress pages using GZIP library (lower bandwidth, higher CPU), 0 = don't";

$plugin_tx['gxsecurity']['cf_compresslevel'] ="Compression level for zipcompressing, from 1 (low) to 9 (maximum), 1 = block access if over a certain system load, 0 = don't";


$plugin_tx['gxsecurity']['cf_cpumaxload'] ="Maximum 5 minute system load average before blocking access";
$plugin_tx['gxsecurity']['cf_gxsessionpath'] ="if not blank, sets a directory path to store session files";
$plugin_tx['gxsecurity']['cf_filterblacklistedIPs'] ="1 = filter IPs that are blacklisted, 0 = don't";

$plugin_tx['gxsecurity']['cf_dnsbl_lists'] ="DNS-based Blackhole List";
$plugin_tx['gxsecurity']['cf_filtercountries'] ="1 = filter countries from countryfile, 0 = don't";

$plugin_tx['gxsecurity']['cf_crackcheck'] ="1 = check for cracks and injections, 0 = don't";

$plugin_tx['gxsecurity']['cf_countryfile'] ="name of the file with the country codes to block";
$plugin_tx['gxsecurity']['cf_crackfile'] ="name of the file with the crack and injection expressions to detect";
$plugin_tx['gxsecurity']['cf_badattempts'] ="number of bad attempts before banning the ip in .htaccess or give a message that too many rapid accesses were done";
$plugin_tx['gxsecurity']['cf_logheader'] ="header of the logfiles";
$plugin_tx['gxsecurity']['cf_logcycle'] ="Cycle of logfiles: <D>aily (one file per day), <M>onthly (one file per month) or <Y>early (one file per year)";
$plugin_tx['gxsecurity']['cf_logdelimiter'] ="Delimiter between fields in the logfiles";
$plugin_tx['gxsecurity']['cf_httpBL_key'] ="Personal http:BL access key from the Project Honey Pot., If the variable is empty, the test will be skiped.";

$plugin_tx['gxsecurity']['cf_httpBL_spamthreat'] ="Score of spamthreat (default = 1), Detailed informations see http://www.projecthoneypot.org/httpbl_api.php";

$plugin_tx['gxsecurity']['cf_httpBL_otherthreat'] ="Score of other threats (default = 40), Detailed informations see http://www.projecthoneypot.org/httpbl_api.php";

$plugin_tx['gxsecurity']['cf_checkbothost'] ="1 = recheck host for robots (user agent), 0 = don't";

$plugin_tx['gxsecurity']['cf_hostchecktype'] ="1 = Standard (gethostbyaddr()), 2 = *nix (faster then 1), 3 = Win (faster then 1)";


$plugin_tx['gxsecurity']['cf_waitingtime'] ="Time in seconds to wait before a new access is allowed after a blocking";
?>