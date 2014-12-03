<?php
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU Library General Public License as published by
# the Free Software Foundation either version 2 of the License, or

//=====================================================================================================================
//classdef  CSVHandler 0.91 :: CSV Handling Wrapper
//=====================================================================================================================
#
#
# Copyright (C) 2005 by Andreas Müller
class CSVHandler {
	var $Separator;		//
	var $DataFile;
	var $DataKey;
	var $HeaderData;	//
	var $ItemsList;	//
	var $Items_Count;
	var $color;
    var $whois;

// Standard User functions
	function CSVHandler($Filename, $Separator, $KeyFieldName) {		//Constructor
        global $plugin_cf;
		$this->DataFile=$Filename;
		$this->DataKey=$KeyFieldName;
		$this->Separator=$Separator;
		$this->color="#FFFFFF";
//        $this->whois='http://who.is/whois-ip/ip-address/';
        $this->whois=$plugin_cf['gxsecurity']['ShowLog_Whois'];
	}
	function ReadCSV() {			//read data into this->ItemsList and return it in an array 
		$this->Items_Count=0;
		$this->ItemsList=array();
		$Item=array();
		$fp = fopen ($this->DataFile,"r");
		$this->HeaderData = fgetcsv ($fp, 3000, $this->Separator);
		while ($DataLine = fgetcsv ($fp, 3000, $this->Separator)) {
			for($i=0;$i<count($this->HeaderData);$i++){
				$Item[$this->HeaderData[$i]]=$DataLine[$i];
			}
			array_push($this->ItemsList,$Item);
			$this->Items_Count++;
		}
		fclose($fp);
		return ($this->ItemsList);
	}    

	function ListAll() {					//prints a list of all Data
		$Data=$this->ReadCSV();
		reset ($this->ItemsList);
		reset ($this->HeaderData);
		$HHeaders="";
		$HData="";       
		while(list($HKey,$HVal)=each($this->HeaderData)) {			//Create Headers Line
			$HHeaders.=$this->HTTH($HVal);
		}
		$HHeaders=$this->HTTR($HHeaders);
		$HHeaders=$this->HTTHead($HHeaders);
		while(list($LineKey,$LineVal)=each($this->ItemsList)) {	//Read Data Lines
			$HDataLine="";
			while(list($DataKey,$DataVal)=each($LineVal)) {			//Dissect one Data Line
                if ($DataKey==='IP') $DataVal = $this->whois($DataVal);
				$HDataLine.=$this->HTTD($DataVal);
			}
			$HData.=$this->HTTR($HDataLine);	//and add HTML to Data
		}
		$HData=$this->HTTBody($HData);
		print ($this->HTPage($this->HTTable($HHeaders.$HData)));
	}

//	Accessory HTML output functions
	function HTPage($value) {	// Places $value into BODY of HTML Page
    		global $pth,$hjs;
	    if(!file_exists($pth['folder']['plugins'].'jquery/jquery.inc.php')) 
		    return '<div class="cmsimplecore_warning">'.
		    '<b>Ups!</b>'.tag('br').
		    'Plugin '.ucfirst(basename(dirname(__FILE__))).
		    ' requires jQuery4CMSimple - Plugin!'.tag('br').
		    'Please download and install jQuery4CMSimple'.tag('br').
		    'from <a href='.
		    '"http://www.cmsimple-xh.org/wiki/doku.php/extend:jquery4cmsimple">'.
		    ' www.cmsimple-xh.org/wiki</a>.'.
		    '</div>';

	    include_once($pth['folder']['plugins'].'jquery/jquery.inc.php'); //include jQuery to the <head>
	    include_jQuery();    
	    include_jQueryPlugin('tablesorter',$pth['folder']['plugins'].'gxsecurity/lib/jquery.tablesorter.min.js');
	    include_jQueryPlugin('gxsecurity',$pth['folder']['plugins'].'gxsecurity/lib/jquery.gxsecurity.js');
		
	    
		$result = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		$result.="<html><head><title>".$this->DataFile." Editor</title>\n";
		$result.="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
		$result.="<link rel=\"stylesheet\" href=\"./plugins/gxsecurity/lib/tablesort.css\" type=\"text/css\">";
		$result.=$hjs;
		$result.="</head>\n";
		$result.="<body>\n".$value."</body>\n</html>";
		return $result;
	}
	function HTForm($item,$data) {	//places $data into form $item
		return "<form name=\"".$item."\" method=\"post\">\n".$data."</form>\n";
	}
	function HTTable($value) {		//places $value into TABLE
		return "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n".$value."</table>\n";
	}
	function HTTHead($value) {			//places $value into THEAD
		return "<thead>".$value."</thead>\n";
	}
	function HTTBody($value) {			//places $value into TBODY
		return "<tbody>".$value."</tbody>\n";
	}
	function HTTR($value) {			//places $value into TR
		return "<tr>".$value."</tr>\n";
	}
	function HTTH($value) {			//places $value into TH
		return "<th class=\"header\">".$value."</th>\n";
	}
	function HTTD($value) {	// places $value into TD
		return "<td>".$value."</td>";
	}
	function whois($value) {	// generates a who.is link with ip address
		return '<a href="'.sprintf($this->whois,$value).'" target="whois">'.$value.'</a>';
	}
	function HTInput($field,$value) {	//returns TD input field
		$Olen=strlen($value);
		if($Olen<3) {
			$Ilen=12;
		} else {
			$Ilen=$Olen;
		}
		return "<td bgcolor=\"".$this->color."\"><input name=\"".$field."\" type=\"text\" id=\"".$field."\" value=\"".$value."\" size=\"".$Ilen."\"></td>\n";
	}	
	function HTButton($value) {	// returns "$value" button
		return "<td><input name=\"".$value."\" type=\"submit\" id=\"".$value."\" value=\"".$value."\"></td>\n";
	}
	function SRotate() {		//rotating colors for more readability of tables
		if($this->color=="#FFFFFF") {
			$this->color="#CCEEFF";
		} else {
			$this->color="#FFFFFF";
		}
	}
}
?>