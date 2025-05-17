<?php
require_once "application/config/database.php";
require_once "cron_mail.php";

mysql_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']);
mysql_select_db($db['default']['database']);

class cron{

	function __construct(){
		error_reporting(E_ERROR);
	}
	
	function query($sql = ''){
		return mysql_query($sql);
	}
	
	function num_rows($sql = ''){
		return mysql_num_rows($sql);
	}

	function row_array($sql = ''){
		$return = array();
		$x = 0;
		
		while($data = mysql_fetch_array($sql)){
			for($i=0;$i<count($data);$i++){
				$index = key($data);
				$return[$index] = $data[$index];
				next($data);
			}
		}
		
		return $return;
	}
	
	function result($query = ''){
		$return = array();
		$x = 0;
		
		while($data = mysql_fetch_array($query)){
			for($i=0;$i<count($data);$i++){
				$index = key($data);
				$return[$x][$index] = $data[$index];
				next($data);
			}
			$x++;
		}
		
		return $return;
	}
	
	function send_email($to = '', $subject = '', $message = ''){
		
		$from = 'noreply@eprocurement.com';
		$from_name = 'E-Procurement System';
		$subject = 'Update data Penyedia Barang/Jasa : '.$subject;
 
		$header  = "Reply-To: E-Procurement System <noreply@eprocurement.com>\r\n"; 
    		$header .= "Return-Path: E-Procurement System <noreply@eprocurement.com>\r\n"; 
    		$header .= "From: E-Procurement System <noreply@eprocurement.com>\r\n"; 
    		$header .= "Organization: E-Procurement System\r\n"; 
    		$header .= "Content-Type: text/html; charset=UTF-8\r\n"; 
		$header .= "X-Mailer: PHP/" . phpversion()."\n";

		mail($to, $subject, $message, $header,"-f $from");
	}
}