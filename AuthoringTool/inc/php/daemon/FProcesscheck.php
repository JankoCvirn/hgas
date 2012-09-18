<?php
    if(!defined("CLASS_processcheck")) {
        define("CLASS_processcheck", true);
        
        include("SMSC.php");
        include("daemon/FSignallistener.php");
        
        Class Processcheck extends Signallistener
        {
            var $sPidFileDir;
            var $sPidFileExt = ".pid";
            var $sPidfile;
            var $iTimeSinceLastPidAccess = 10;                        //time in seconds
            
            function Processcheck ($sProcessName = "")
            {
                $this->startProcesscheck($sProcessName);
            }
            
            function startProcesscheck($sProcessName = "")
            {
                $this->setPidfiledir();
                if($sProcessName == "") {
                    printf("FATAL: can't work without valid PROCESSNAME.\n");
                    exit;
                } else {
                    $this->setPidfile($this->getPidfiledir().$sProcessName.$this->getPidfileExt());
                }
            }
            
            function setPidfileExt($sExt = ".pid")
            {
                $this->sPidFileExt = $sExt;
            }
            
            function getPidfileExt()
            {
                return($this->sPidFileExt);
            }
            
            function setPidfiledir()
            {
                $this->sPidFileDir = SMSC_VAR."run/";
                if(!file_exists($this->sPidFileDir)) {
                    @mkdir($this->sPidFileDir, 0777);
                }
            }
            
            function getPidfiledir()
            {
                return($this->sPidFileDir);
            }
            
            function setPidfile($sFile)
            {
                $this->sPidfile = $sFile;
            }
            
            function getPidfile()
            {
                return($this->sPidfile);
            }
            
            function setProcesstouchDelay($iTime = 10)
            {
                $this->iTimeSinceLastPidAccess = $iTime;
            }
            
            function getProcesstouchDelay()
            {
                return($this->iTimeSinceLastPidAccess);
            }
            
            function _getPid()
            {
                if($hFp = @fopen($this->getPidfile(), "r")) {
                    $sContent = fread($hFp, filesize($this->getPidfile()));
                    fclose($hFp);
                    return((integer)$sContent);
                } else {
                    return(false);
                }
            }
            
            function _checkPid($iPid = false)
            {
                if($iPid == false) {
                    return(false);
                } else {
                    $sCmd = "ps wax|grep \"^.\{0,2\}".$iPid." \"|wc -l";
                    $sResult = trim(exec($sCmd));
                    if($sResult == 0 || $sResult == "0") {
                        return(false);
                    } else {
                        return(true);
                    }
                }
            }
            
            function touchPid()
            {
                static $iLastTime;
                
                if($iLastTime < (time() - $this->getProcesstouchDelay()) ) {
                    $iLastTime = time();
                    if(!file_exists($this->getPidfile())) {
                        @touch($this->getPidfile());
                        if($hFp = @fopen($this->getPidfile(), "w")) {
                            @fwrite($hFp, posix_getpid());
                            @fclose($hFp);
                        }
                    } else {
                        @touch($this->getPidfile());
                    }
                }
            }
            
            function unlinkPid()
            {
                @unlink($this->getPidfile());
            }
            
            function initProcess()
            {
                    if($hFp = fopen($this->getPidfile(), "w")) {
                        @fwrite($hFp, posix_getpid());
                        @fclose($hFp);
                    } else {
                        printf("FATAL ERROR: Could not write to PID-File.\n");
                        exit;
                    }
            }
            
            function finalize()
            {
                $this->unlinkPid();
            }
            
            function checkProcess()
            {
                if(file_exists($this->getPidfile())) {
                    clearstatcache();
                    $iFiletime = fileatime($this->getPidfile());
                    if($iFiletime < (time() - $this->iTimeSinceLastPidAccess) ) {
                        //this would mean, the process is not updating any longer
                        $iPid = $this->_getPid();
                        if($this->_checkPid($iPid)) {
                            return(true);
                        } else {
                            //pid is NOT running
                            $this->unlinkPid();
                            return(false);
                        }
                    } else {
                        return(true);
                    }
                } else {
                    return(false);
                }
            }
            
            
        }#class
        
        function test_processcheck()
        {
            $oTest = new Processcheck("processcheck");
            $oTest->setProcesstouchDelay(4);
            while($oTest->checkProcess()) {
                printf("Process already running.\n");
                sleep($oTest->getProcesstouchDelay()+1);
            }
            $oTest->initProcess();
            while(true) {
                printf("Running. Time: %s\n", date("H:i:s"));
                $oTest->touchPid();
                sleep($oTest->getProcesstouchDelay());
            }
        }
        
    }#defined
?>
