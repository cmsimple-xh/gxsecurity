<?php
	$plugin_cf['gxsecurity']['usehtaccessbans']=1;
	$plugin_cf['gxsecurity']['filterGETvars']=1;
	$plugin_cf['gxsecurity']['filterCOOKIEvars']=1;
	$plugin_cf['gxsecurity']['filterPOSTvars']=0;
	$plugin_cf['gxsecurity']['extraPOSTprotection']=0;	
	$plugin_cf['gxsecurity']['extraGETprotection']=0;
	$plugin_cf['gxsecurity']['checkmultiPOST']=1;
	$plugin_cf['gxsecurity']['maxmultiPOST']=5;
	$plugin_cf['gxsecurity']['zipcompress']=0;
	$plugin_cf['gxsecurity']['compresslevel']=9;
	$plugin_cf['gxsecurity']['cpuloadmonitor']=0;
	$plugin_cf['gxsecurity']['cpumaxload']=10.0;
	$plugin_cf['gxsecurity']['gxsessionpath']="";
	$plugin_cf['gxsecurity']['filterblacklistedIPs']=1;
	$plugin_cf['gxsecurity']['dnsbl_lists']='bl.spamcop.net,sbl.spamhaus.org,xbl.spamhaus.org';
	$plugin_cf['gxsecurity']['filtercountries']=1;
	$plugin_cf['gxsecurity']['crackcheck']=1;
	$plugin_cf['gxsecurity']['countryfile']='countries.txt';
	$plugin_cf['gxsecurity']['crackfile']='cracks.txt';
	$plugin_cf['gxsecurity']['badattempts']=10;
	$plugin_cf['gxsecurity']['logheader']='Date,Time,Event,IP,Remark';
	$plugin_cf['gxsecurity']['logcycle']='D';
	$plugin_cf['gxsecurity']['logdelimiter']=',';
	$plugin_cf['gxsecurity']['httpBL_key']='';
	$plugin_cf['gxsecurity']['httpBL_spamthreat']=1;	
	$plugin_cf['gxsecurity']['httpBL_otherthreat']=40;
	$plugin_cf['gxsecurity']['checkbothost']=0;	
	$plugin_cf['gxsecurity']['hostchecktype']=1;	
	$plugin_cf['gxsecurity']['waitingtime']=60;	
	$plugin_cf['gxsecurity']['ShowLog_Whois']='https://stat.ripe.net/%s';	
?>
