<?php
    if(!defined("CLASS_smscdebugger")) {
        define("CLASS_smscdebugger", true);
        
        include("SMSC.php");
		define('ERR_CRITICAL', 	0);
		define('ERR_ERROR', 	1);
		define('ERR_WARNING', 	2);
        
        Class Debugger {
            var $sDebugfile = null;
		    var $sNameExtension = "";
			var $endpoint = 0;
            
            function Debugger($sNameExtension = "", $endpoint = 0) {
				$this->sNameExtension = $sNameExtension;
				if($endpoint !== 0) {
					$this->endpoint = $endpoint;
				}
            }
            
            function setDebugfile($sNameExtension = "") {
				if($this->sDebugfile === null) {
					$sName = str_replace(".php", "", basename($_ENV["_"]));
					$sName = str_replace("./", "", $sName);
					$this->sDebugfile = FSMSC_VAR."log/".$sName.$sNameExtension."_".date("d.m.y").".log";
					//echo "Log:".$this->sDebugfile."\n";
					@touch($this->sDebugfile);
				}
            }
			function setSharedFile($filename) {
                $this->sDebugfile = FSMSC_VAR."log/".$filename."_".date("d.m.y").".log";
				//echo "Log:".$this->sDebugfile."\n";
                @touch($this->sDebugfile);
			}
            
            function getDebugfile() {
                return($this->sDebugfile);
            }
            
            function log($sAction) {
                $this->setDebugfile($this->sNameExtension);
                $this->write(date("H:i:s,").$this->getUsec().'> '.$sAction);
				//$this->write2DB($sAction);
            }
			
            function write2DB($sAction) {
//				if($this->endpoint !== 0) {
//					$oSql = new Sql("smsc_log");
//					$sSql = "INSERT INTO debug_log (tstamp, endpoint, message) VALUES ('".time()."', '".$this->endpoint."', '".$sAction."')";
//					$oSql->q($sSql);
//				}
			}
			
            function write($sString) {
                if( $f = @fopen($this->getDebugfile(), "a")) {
                    fwrite($f, $sString."\n");
                    fclose($f);
                }
            }
			
			function getUsec() {
				list($usec) = explode(" ", microtime());
				$usec = sprintf('%003d', round($usec*1000));
				return($usec);
			}
			
			function logError($string, $errorType = 0) {
//				$filename = basename($_ENV["_"]);
				$filename = realpath($_ENV["_"]);
				
				$string = 'in '.$filename.': '.trim($string);
				
				switch($errorType) {
					case ERR_CRITICAL: $string = 'CRITICAL '.$string; break;
					case ERR_ERROR: $string = 'ERROR '.$string; break;
					case ERR_WARNING: $string = 'WARNING '.$string; break;
					default: $string = 'CRITICAL '.$string; break;
				}
				$string = date('d.m.Y H:i:s,').$this->getUsec().'> '.$string;
				
				$f = @fopen('/fsmsc/var/log/errors.log', 'a');
				if($f) {
					fwrite($f, $string."\n");
					fclose($f);
				}
			}
        }
        
    }//!defined
?>
