<?php
    if(!defined("CLASS_daemon")) {
        define("CLASS_daemon", true);
        
        include("SMSC.php");
        include("Thread.php");
        include("Processcheck.php");
        include("SmscDebugger.php");
        
        define("DAEMON_USR", 99);//nobody
        define("DAEMON_GRP", 99);//nobody
        
        
        Class Daemon extends Thread {
            var $oProcess;
            var $sDaemonname;
            var $bDaemonize       = true;
            var $iDaemonSleeptime = 5000;
            var $bDebugmode 	  = false;
            var $bNoKill          = false; //Is killing allowed?            
            
            function Daemon($bDaemonize = true, $sDaemonname_extension = "")
            {
                if($bDaemonize === false) {
                    $this->bDaemonize = false;
                }
                $this->setDaemonname($sDaemonname_extension);
                $this->startupDaemon();
            }//
            
            function setKillmode($bMode = false)
            {
                $this->bNoKill = $bMode;
            }
            
            function _daemonChangeIdentity($iUid, $iGid)
            {
                global $pid_file;
                if(!posix_setgid($gid)) {
                    printf("Unable to setgid to %d\n", $iGid);
                    @unlink($pid_file);
                    exit;
                }
                if(!posix_setuid($uid)) {
                    printf("Unable to setuid to %d\n", $iUid);
                    @unlink($pid_file);
                    exit;
                }
            }//
            
            function daemonize()
            {
                set_time_limit(0);
//				ini_set("output_buffering", "0");
//				ini_set("output_handler", NULL);
                ob_implicit_flush();
                
                if($this->bDaemonize === true) {
                    $this->_daemonChangeIdentity(DAEMON_USR, DAEMON_GRP);
                    $iChild = pcntl_fork();
                    if($iChild) {
                        exit; //kill parent
                    }
                    posix_setsid(); //become session leader
                    chdir("/");
                    umask(0); //clear umask
                }

                Signallistener::Signallistener();
                $this->setMaxThreads(10);
                $this->startProcesscheck($this->getDaemonname());
                $this->initProcess();
            }//
            
            function _start()
            {
                printf("Starting...");
                $oProcess = new Processcheck($this->getDaemonname());
                if($oProcess->checkProcess()) {
                    printf("[failed]\n");
                    printf("%s is already running as %d.\n", $this->getDaemonname(), $oProcess->_getPid());
                    exit;
                } else {
                    printf("[ok]\n");
                    $this->daemonize();
                }
            }
            
            function _stop()
            {
                printf("Stoping...");
                $oProcess = new Processcheck($this->getDaemonname());
                if($oProcess->checkProcess()) {
                    $iPid = $oProcess->_getPid();
                    if(posix_kill($iPid, SIGTERM)) {
                        $iRunner = 0;
                        while($this->_checkPid($iPid)) {
                            $this->_sleep(10000);
                            if($iRunner == 10) {
                                posix_kill($iPid, SIGKILL);
                                if($this->_checkPid($iPid)) {
                                    printf("[failed]\n%s won't die.\n", $this->getDaemonname());
                                    return(false);
                                } else {
                                    printf("[ok]\n%s was killed by SIGKILL.\n", $this->getDaemonname());
                                    return(true);
                                }
                                break;
                            } else if($iRunner == 9 && $this->bNoKill == true) {
                                printf("[failed]\n%s won't die.\n", $this->getDaemonname());
                                return(false);
                            } else {
                                $iRunner ++;
                            }
                        }
                        printf("[ok]\n");
                        return(true);
                    } else {
                        printf("[failed]\n");
                        return(false);
                    }
                } else {
                    printf("[failed]\n");
                    return(true);
                }
            }
            
            function _status()
            {
                $oProcess = new Processcheck($this->getDaemonname());
                if($oProcess->checkProcess()) {
                    printf("%s is running as %d.\n", $this->getDaemonname(), $oProcess->_getPid());
                } else {
                    printf("No Process found.\n");
                }
            }
            
            function debugMode() {
            	return($this->bDebugmode);
            }
            
            function setDebugMode($bool) {
            	$this->bDebugmode = $bool;
            }
            
            function startupDaemon()
            {
                $argc  = $GLOBALS["argc"];
                $argv  = $GLOBALS["argv"];
                
                //getting debug status:
                if(in_array("debug", $argv)) {
                	$this->setDebugMode(true);
                } else {
                	$this->setDebugMode(false);
                }
                
                //getting action:
                if(!(in_array("stop", $argv) || in_array("restart", $argv) || in_array("status", $argv)) ) {
                    $sAction = "start";
                } else if(in_array("stop", $argv)) {
                    $sAction = "stop";
                } else if(in_array("restart", $argv)) {
                    $sAction = "restart";
                } else if(in_array("status", $argv)) {
                    $sAction = "status";
                }
                
                switch($sAction) {
                    case "start":
                        $this->_start();
                        break;
                    case "stop":
                        $this->_stop();
                        exit;
                        break;
                    case "status":
                        $this->_status();
                        exit;
                        break;
                    case "restart":
                        if($this->_stop()) {
                            $this->_start();
                        }
                        break;
                    default:
                        printf("Unknown startup action.\n");
                        exit;
                        break;
                }//switch
            }//startupDaemon
            
            function setDaemonname($sNameExtension = "")
            {
                $sName = str_replace(".php", "", basename($_ENV["_"]));
                $sName = str_replace("./", "", $sName);
                $this->sDaemonname = $sName.$sNameExtension;
            }//
            
            function getDaemonname()
            {
                return($this->sDaemonname);
            }//
            
            function setDaemonsleeptime($iSleeptime = 5000)
            {
                if($iSleeptime < 2500) {
                    $iSleeptime = 2500;
                } elseif ($iSleeptime > 100000) {
                    $iSleeptime = 100000;
                }
                $this->iDaemonSleeptime = $iSleeptime;
            }//
            
            function getDaemonsleeptime()
            {
                return($this->iDaemonSleeptime);
            }//
            
            function _sleep($iSleeptime = 0)
            {
                if($iSleeptime === 0) {
                    usleep($this->getDaemonsleeptime());
                } else {
                    usleep($iSleeptime);
                }
            }//
            
            function triggerDaemon()
            {
                $this->touchPid();
                $this->_sleep();
                if( $this->checkSignals() === true ) {
                    return;
                } else {
                    $this->reactOnSignal();
                }
            }//
            
            function run()
            {
                while (true) {
                    $this->triggerDaemon();
                    $this->main();
                }
            }//
            
            function stopDaemon()
            {
                $this->unlinkPid();
                exit;
            }//
            
            function _sigterm()
            {
                $this->stopDaemon();
            }//
            
            /**
                main() will run the whole application, therefor it MUST be overwritten.
            */
            function main()
            {
                ;
            }//
        }//class
        
        function test_daemon()
        {
            $oDaemon = new Daemon();
            $oDaemon->run();
        }//
        
        //test_daemon();
        
    }//!defined
?>
