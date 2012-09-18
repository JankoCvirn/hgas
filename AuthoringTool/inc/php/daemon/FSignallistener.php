<?php
    if(!defined("CLASS_signallistener")) {
        define("CLASS_signallistener", true);
        define("CL_SIGLST_SIGNONE", -1);
        
        include("SMSC.php");
        
        function CL_SIGLST_HANDLER($iInputSignal)
        {
            static $iSignal = CL_SIGLST_SIGNONE;
            if($iInputSignal === false) {
                $iTemp   = (integer)$iSignal;
                $iSignal = (integer)CL_SIGLST_SIGNONE;
                return($iTemp);
            } else {
                $iSignal = $iInputSignal;
            }
        }
        
        
        Class Signallistener {
            var $iSignalStatus = CL_SIGLST_SIGNONE;
            
            function Signallistener() {
                pcntl_signal(SIGTERM, "CL_SIGLST_HANDLER");
                pcntl_signal(SIGHUP,  "CL_SIGLST_HANDLER");
                pcntl_signal(SIGUSR1, "CL_SIGLST_HANDLER");
                pcntl_signal(SIGUSR2, "CL_SIGLST_HANDLER");
            }//
            
            function setSignalStatus($iValue)
            {
                $this->iSignalStatus = $iValue;
            }//
            
            function getSignalStatus()
            {
                return($this->iSignalStatus);
            }//
            
            function fetchSignalStatus()
            {
                $this->setSignalStatus(CL_SIGLST_HANDLER(false));
                return($this->iSignalStatus);
            }//
            
            function checkSignals()
            {
                if($this->fetchSignalStatus() == CL_SIGLST_SIGNONE) {
                    return(true);
                } else {
                    return($this->getSignalStatus());
                }
            }//
            
            function sigreset()
            {
                $this->setStatus(CL_SIGLST_SIGNONE);
            }//
            
            function reactOnSignal()
            {
                switch($this->getSignalStatus()) {
                    case SIGTERM:
                        $this->_sigterm();
                        break;
                    case SIGHUP:
                        $this->_sighup();
                        break;
                    case SIGUSR1:
                        $this->_sigusr1();
                        break;
                    case SIGUSR2:
                        $this->_sigusr2();
                        break;
                }//
            }//
            
            function _sigterm()
            {
                exit;
            }//
            
            function _sighup()
            {
                ;
            }//
            
            function _sigusr1()
            {
                ;
            }//
            
            function _sigusr2()
            {
                ;
            }//
            
        }//class

        /***********************************************************************************/
        
        function test_signallistener()
        {
            set_time_limit(0);
            printf("PID: %d\n", posix_getpid());
            
            $oSig = new Signallistener();
            while (true) {
                if( ($iSignal = $oSig->checkSignals()) === true ) {
                    continue;
                } else {
                    switch($iSignal) {
                        case SIGTERM:
                            printf("SIGTERM\n");
                            break;
                        case SIGHUP:
                            printf("SIGHUP\n");
                            break;
                        case SIGUSR1:
                            printf("SIGUSR1\n");
                            break;
                        case SIGUSR2:
                            printf("SIGUSR2\n");
                            break;
                    }
                }
                sleep(1);
            }
        }//
//        test_signallistener();
    }//!defined
?>
