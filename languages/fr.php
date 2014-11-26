<?php

	$plugin_tx['gxsecurity']['mnu_main']="Données GXSecurity";
	$plugin_tx['gxsecurity']['mnu_css']="Paramčtres de style";
	$plugin_tx['gxsecurity']['mnu_config']="Configuration";
	$plugin_tx['gxsecurity']['mnu_language']="Paramčtres de langue";
	$plugin_tx['gxsecurity']['mnu_help']="Aide";
	$plugin_tx['gxsecurity']['mnu_country_file']="Fichier pays";
	$plugin_tx['gxsecurity']['mnu_crack_file']="Fichier avec des expressions de crack";
  $plugin_tx['gxsecurity']['access_denied']="Accčs interdit";
	$plugin_tx['gxsecurity']['contact_admin']="Veuillez contacter le webmaster pour plus d'informations.";
	$plugin_tx['gxsecurity']['cookies_disabled']="Les cookies doivent ętre acceptés pour ce site web. Veuillez paramétrer votre navigateur avant de réessayer, sinon votre adresse IP sera bloquée.";
	$plugin_tx['gxsecurity']['country_banned']="L'accčs ŕ ce site est bloqué depuis ce pays.";
	$plugin_tx['gxsecurity']['ip_blacklisted']="Cette IP est sur une liste noire.";
	$plugin_tx['gxsecurity']['rapid_request']="Il y avait trop de demandes consécutives de cette IP. Veuillez réessayer plus tard.";
	$plugin_tx['gxsecurity']['server_busy']="Le serveur est momentanément surchargé. Veuillez nous excuser.";
	$plugin_tx['gxsecurity']['enable_javascript']="Vous devez accepter Javascript pour afficher ce site web.";
	$plugin_tx['gxsecurity']['multipost']="Vous ne pouvez pas faire plusieurs opérations POST consécutives. Veuillez réessayer plus tard.";
	$plugin_tx['gxsecurity']['secdata_post']="Votre navigateur n'a pas envoyé de données correctes pour exécuter l'opération POST.  Vérifiez l'acceptation des cookies et réessayez. Si vous croyez qu'il s'agit d'une erreur, veuillez contacter le webmaster.";
	$plugin_tx['gxsecurity']['secdata_get']="Votre navigateur n'a pas envoyé de données correctes pour exécuter l'opération GET. Vérifiez l'acceptation des cookies et réessayez. Si vous croyez qu'il s'agit d'une erreur, veuillez contacter le webmaster.";
  $plugin_tx['gxsecurity']['attack_detected']='Une attaque a été détectée et bloquée.';
  $plugin_tx['gxsecurity']['show_log']='Afficher le fichier log dans une nouvelle fenętre'; 
$plugin_tx['gxsecurity']['cf_usehtaccessbans'] ="1 = modifier le fichier .htaccess pour bannir des IPs, 0 = ne pas bannir d'IPs";

$plugin_tx['gxsecurity']['cf_filterGETvars'] ="1 = nettoyer les tags HTML dans les variables GET, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_filterCOOKIEvars'] ="1 = nettoyer les tags HTML dans les variables COOKIES, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_filterPOSTvars'] ="1 = nettoyer les tags HTML dans les variables POST, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_extraPOSTprotection'] ="1 = utiliser la protection extra POST, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_extraGETprotection'] ="1 = utiliser la protection extra GET, 0 = ne pas le faire (non recommandé !)";

$plugin_tx['gxsecurity']['cf_checkmultiPOST'] ="1 = autoriser seulement un nombre "maxmultiPOST" de POST successifs, 0 = sans limite";

$plugin_tx['gxsecurity']['cf_maxmultiPOST'] ="Nombre maximal d'opérations POST par file, si "checkmultiPOST" est activé";
$plugin_tx['gxsecurity']['cf_zipcompress'] ="1 = compression des pages avec la librairie PHP GZIP(diminution de la bande passante mais augmentation de l'occupation CPU), 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_compresslevel'] ="Niveau de compression GZIP, de 1 (faible) à 9 (maximum), 1 = bloque l'accès au dessus d'une certaine charge du système, 0 = ne pas le faire";


$plugin_tx['gxsecurity']['cf_cpumaxload'] ="5 minutes maximum de charge moyenne avant de bloquer l'accès";
$plugin_tx['gxsecurity']['cf_gxsessionpath'] ="si n'est pas vide, défini un chemin pour stocker les fichiers de session";
$plugin_tx['gxsecurity']['cf_filterblacklistedIPs'] ="1 = filtrer les IPs blacklistées, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_dnsbl_lists'] ="DNS-based Blackhole List";
$plugin_tx['gxsecurity']['cf_filtercountries'] ="1 = filtrer les pays d'après le fichier pays, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_crackcheck'] ="1 = vérifier les cracks et injections, 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_countryfile'] ="nom du fichier contenant les pays à bloquer";
$plugin_tx['gxsecurity']['cf_crackfile'] ="nom du fichier contenant les codes de cracks ou d'injections à détecter";
$plugin_tx['gxsecurity']['cf_badattempts'] ="nombre de mauvaises tentatives avant de bannir l'IP dans le fichier .htaccess ou d'avertir que des accès trop rapides ont eu lieu";
$plugin_tx['gxsecurity']['cf_logheader'] ="Entête des fichiers log";
$plugin_tx['gxsecurity']['cf_logcycle'] ="Cycle des fichiers log: <D>aily (un fichier par jour), <M>onthly (un fichier par mois) ou <Y>early (un fichier par année)";
$plugin_tx['gxsecurity']['cf_logdelimiter'] ="Séparateur entre les champs dans les fichiers log";
$plugin_tx['gxsecurity']['cf_httpBL_key'] ="Code d'accès http:BL personnel du Projet Honey Pot., Si la variable est vide, le test est passé.";

$plugin_tx['gxsecurity']['cf_httpBL_spamthreat'] ="Valeur de seuil pour des menaces de spammers (défault = 1), Informations détaillées voir http://www.projecthoneypot.org/httpbl_api.php";

$plugin_tx['gxsecurity']['cf_httpBL_otherthreat'] ="Valeur de seuil pour d'autres menaces (défault = 40), Informations détaillées voir http://www.projecthoneypot.org/httpbl_api.php";

$plugin_tx['gxsecurity']['cf_checkbothost'] ="1 = Vérifier le host pour des robots (user agent), 0 = ne pas le faire";

$plugin_tx['gxsecurity']['cf_hostchecktype'] ="1 = Standard (gethostbyaddr()), 2 = *nix (plus rapide que 1), 3 = Win (plus rapide que 1)";


$plugin_tx['gxsecurity']['cf_waitingtime'] ="Temps en secondes qu'il faut attendre avant qu'un nouveau acces soit possible après un bloquage";

  
?>