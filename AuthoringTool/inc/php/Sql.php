<?php
	if(!defined("CLASS_mysql")) {
		define ("CLASS_mysql", true);
		
		define('SQL_SERVER', 'localhost');
		define('SQL_USERNAME', 'h7993hga_user');
		define('SQL_PASSWORD', 'secret2012');
		
		include_once('Esme.php');
		//include_once('daemon/FDebugger.php');
		
		class Sql {
		    //Configurations variables:
		    private $bDieOnErrors = false;
		    private $bPrintErrors = true;
		    private $sErrorMessage = null;
		    private $iErrorCode   = 0;
		    
		    //Runtime vars:
			private $link = null;
			private $resultset = null;
			private $databaseserver = null;
            private $database = null;
            private $sLogfile = "/var/log/mysql_";
			private $aIgnoredCodes = null;
			private $sTempSqlFile = '';
			private $debuggerlog = null;
			private $debugger = null;
			
            //###########################################################################
			function Sql($database, $server = null){
			    $this->setErrorhandling();
				$this->_initCodes();
				if($server !== null) $this->databaseserver = $server;
				$this->_connect();
				$this->sdb($database);
			}
			function enableDebugger($file) {
				$this->debugger = new Debugger();
				$this->debugger->setSharedFile($file);
			}
            //###########################################################################
			function _initCodes() {
				$this->aIgnoredCodes = array(
					"1146" => "Table does not exist",
					"1062" => "Duplicate entry",
				);
			}
			function throwError($iErrorCode, $sErrormessage, $sString = "") {
				if(!is_array($this->aIgnoredCodes)) $this->_initCodes();
			     //Makeing the errors fetchable
			     $this->sErrorMessage = $sErrormessage;
			     $this->iErrorCode = $iErrorCode;
				 
				 //should we log to file?
				 if(!isset($this->aIgnoredCodes[$iErrorCode]) || $this->bDieOnErrors == true) {
					 $file = $this->sLogfile.date("d.m.Y").".log";
					 $f = @fopen($file, "a");
					 if($f) {
						 @fwrite($f, date("H:i:s >").sprintf("ERROR[%d] (%s): %s\n", $iErrorCode, $_SERVER["SCRIPT_FILENAME"], ($sString != "") ? $sErrormessage.":".$sString : $sErrormessage));
						 @fclose($f);
					 }
				 }
				 
				 $description = sprintf("ERROR[%d]: %s\n", $iErrorCode, ($sString != "") ? $sErrormessage.":".$sString : $sErrormessage);
				 if($this->debugger !== null) $this->debugger->log( $description );
			     //should we print to stdout?
			     if($this->bPrintErrors == true)
			         echo $description;
			     //should we continue or die?
			     if($this->bDieOnErrors == true) exit;
			}
			function isError() {
			  if($this->iErrorCode != 0) return(true);
			  else return(false);
			}
			function fetchError() {
			    return(array($this->iErrorCode, $this->sErrorMessage));
			}
			function setErrorhandling($bPrintErrors = false, $bDieOnErrors = false) {
			     //setting the class error handling
			     $this->bPrintErrors = $bPrintErrors;
			     $this->bDieOnErrors = $bDieOnErrors;
			}
            //###########################################################################
			function _connect($host = SQL_SERVER, $user = SQL_USERNAME, $pass = SQL_PASSWORD) {
			    if($this->databaseserver != "") $host = $this->databaseserver;
				
                ($this->link = @mysql_pconnect($host, $user, $pass))
                    			|| $this->throwError (mysql_errno(), mysql_error(), "Database error: cannot connect");
			}
			function sdb($database) {
				$this->database = $database;
   		        @mysql_select_db($database, $this->link)
    					OR $this->throwError (mysql_errno(), mysql_error(), "could not select database ".$database.".");
  		    }
            //###########################################################################
			function query($query) {
				($this->resultset = @mysql_query($query, $this->link))
   						or $this->throwError (mysql_errno(), mysql_error(), "Could not execute ".$query." on database ".$this->database.".");
			    return($this->resultset);
			}
			function q($query) {
				return($this->query($query));
            }
			function fr() {
				return(@mysql_fetch_row($this->resultset));
			}
			function fa() {
				return @mysql_fetch_array($this->resultset, MYSQL_ASSOC);
			}
			function fo() {
				return @mysql_fetch_object($this->resultset);
			}
            //###########################################################################
			function num_rows() {
				return(@mysql_num_rows($this->resultset));
			}
			function nr() {
				return($this->num_rows($this->resultset));
			}
			function insertId() {
				return(@mysql_insert_id($this->link));
			}
			function escape($string) {
				return mysql_real_escape_string($string);
			}
			
			
            //###########################################################################
            //###########################################################################
            //###########################################################################
			function import($sDatabase = "", $sFilename = "") {
				$sUsername = "root";

				if($this->databaseserver != null) $sHost = $this->databaseserver;
				else $sHost = SQL_SERVER;
				
				if($sFilename == "") $sFilename = $this->sTempSqlFile;

				if ($sDatabase == "" || $sFilename == "" || !@file_exists($sFilename)) {
					$this->throwError(7000, "Could not find data to import", "Could not find data to import");
				} else {
					$sCmd = "mysql -u ".$sUsername." --host=".$sHost." ".$sDatabase." <".$sFilename;
					@exec($sCmd);
					#@unlink($sFilename);
				}
			}
			function qw($sSql, $sFilename = "") {
				if ($sFilename == "") {
					if ($this->sTempSqlFile == "") {
						$this->sTempSqlFile = "/home/web/smsc/_tmp/SQL_".time().".sql";
						@unlink($this->sTempSqlFile);
						@touch($this->sTempSqlFile);
					}
					$sFilename = $this->sTempSqlFile;
				} else {
					$this->sTempSqlFile = $sFilename;
				}
				if (!ereg(";",$sSql)) {
					$sSql.=";";
				}
				$sCmd = "echo '".$sSql."'>>".$sFilename;
				exec($sCmd);
			}
			function close($x=null) {
				//mysql_close($this->$link);
			}
		}//Class
	}//defined
?>
