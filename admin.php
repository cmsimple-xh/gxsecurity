<?php
/*
 * Register the plugin menu items.
 */
    if (function_exists('XH_registerStandardPluginMenuItems')) {
        XH_registerStandardPluginMenuItems(true);
    }

if(function_exists('XH_wantsPluginAdministration') 
    && XH_wantsPluginAdministration('gxsecurity') 
    || isset($gxsecurity) && $gxsecurity == 'true') 
{

 $gxsecurity_plugin=basename(dirname(__FILE__),"/");

 $o.=print_plugin_admin('on');
 if($admin<>'plugin_main'){$o.=plugin_admin_common($action,$admin,$gxsecurity_plugin);}
 if($admin=='')$o.="<h4>GXSecurity</h4>Security plugin ver 1.8 by Gerd Xhonneux (<a href=\"http://xtc.xhonneux.com\" target=\"_blank\">http://xtc.xhonneux.com</a>)";

  if ($admin == 'plugin_main') {
   $pth['file']['plugin_main'] = $pth['folder']['plugins'].$gxsecurity_plugin."/config/".$plugin_cf['gxsecurity']['countryfile'];  
   $o.="<h4>".$plugin_tx['gxsecurity']['mnu_country_file']."</h4> (".$pth['folder']['plugins'].$gxsecurity_plugin."/config/".$plugin_cf['gxsecurity']['countryfile'].")";
   $o .= plugin_admin_common($action,$admin,$gxsecurity_plugin);
  }

  if ($admin == 'plugin_main') {
   $pth['file']['plugin_main'] = $pth['folder']['plugins'].$gxsecurity_plugin."/config/".$plugin_cf['gxsecurity']['crackfile'];  
   $o.="<h4>".$plugin_tx['gxsecurity']['mnu_crack_file']."</h4> (".$pth['folder']['plugins'].$gxsecurity_plugin."/config/".$plugin_cf['gxsecurity']['crackfile'].")";
   $o .= plugin_admin_common($action,$admin,$gxsecurity_plugin);
  }
  
  if ($admin == 'plugin_main') {
   $o.="<h4>Logfiles</h4>";
   
   $dirname = $pth['folder']['plugins'].$gxsecurity_plugin."/log/";
   if ($dir = @opendir($dirname)) {
     while (($file = readdir($dir)) !== false)
       {
         if($file != ".." && $file != ".") {
           if (substr($file,-4) == ".csv")
             $filelist[] = substr($file,0,strlen($file)-4);
           //else echo substr($file,-4);
         }
       }
     closedir($dir);
   }

   $o.= "<form action='./' method='POST' target='_blank'>";
   $o.= "<select name='csvfile'>";
   arsort($filelist);
   while (list ($key, $val) = each ($filelist)) {
      $o.= "<option>$val</option>";
   }
   $o.= "</select>";
   $o.= "<input type='hidden' name='delimiter' value='".$plugin_cf['gxsecurity']['logdelimiter']."'>";
   $o.= "<input type='submit' value='".$plugin_tx['gxsecurity']['show_log']."'>";
   $o.= "</form>";
   }
}

/*
 * Show a log file.
 */
if ($adm && !empty($_POST['csvfile'])) {
 include($pth['folder']['plugin']."lib/csvhandler.php");
 $csvfile=$pth['folder']['plugin']."log/".$_POST['csvfile'].'.csv';
 echo '<a href="'.$csvfile.'"><strong>'.$csvfile.'</strong></a>';

 $data=new CSVHandler($csvfile,$_POST['delimiter'],"");
 $data->ListAll();
 exit;
}
?>
