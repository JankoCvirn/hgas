<?php
    if(!defined("CLASS_thread")) {
        define("CLASS_thread", true);
        
        define("THREAD_IS_MAIN",  1);
        define("THREAD_IS_CHILD", 2);
        
        include("SMSC.php");
        include("daemon/FProcesscheck.php");
		
		declare(ticks=1);
        
        Class Thread extends Processcheck
        {
            var $iMaxThreads        = 10;
            var $iCurrentThreads;
            var $aThreads;
            
            function Thread() {
            }

            function addThread($iPid = 0, $iValue = 0)
            {
                if($iPid == 0) {
                    return(false);
                }
                if(!isset($this->aThreads[$iPid])) {
                    $this->iCurrentThreads ++;
                }
                $this->aThreads[$iPid] = (integer)$iValue;
                return(true);
            }
            
            function deleteThread($iPid)
            {
                if(isset($this->aThreads[$iPid])) {
                    unset($this->aThreads[$iPid]);
                    $this->iCurrentThreads --;
                    return(true);
                } else {
                    return(false);
                }
            }
            
            function isThreadPossible($iValue = 0) {
                //returns true, if a new child may be forked, else false.
                if($iValue != 0) {
                    $bArraycheck = (in_array($iValue, $this->aThreads) ? true : false);
                } else {
                    $bArraycheck = false;
                }
                if($this->iCurrentThreads <= $iMaxThreads && !$bArraycheck ) {
//echo "Forking allowed\n";
                    return(true);
                } else if ($bArraycheck) {
//echo "Forking forbidden\n";
                    return(false);
                } else {
//echo "Forking:".$this->checkChilds()."\n";
                    return($this->checkChilds());
                }
            }
            
            function checkThreadPids()
            {
                /**
                    if we get an error on waitpid, this might mean, we lost processes.
                    In this case we should loop through the array of stored pids and check
                    if this pid is still available.
                */
                foreach($this->aThreads AS $iPid => $something) {
                    $sCmd = "ps -ax|grep \"^.\{0,2\}".$iPid." \"|wc -l";
                    $sResult = trim(exec($sCmd));
                    if($sResult == 0 || $sResult == "0") {
                        $this->deleteThread($iPid);
                    }
                }
            }
            
            function checkChilds()
            {
                if($this->iCurrentThreads > $this->iMaxThreads) {
                    while( true ) {
                        //waitpid with WNOHANG will check and continue without delay:
                        $iChild_pid = pcntl_waitpid(-1, $iStatus, WNOHANG);//-1
                        if($iChild_pid > 0) {
                            //this means we got a result (and a dead child;-))
                            $this->deleteThread($iChild_pid);
                            if($this->iCurrentThreads < $this->iMaxThreads) {
                                return(true);
                            }
                        } else if(!$iChild_pid) {
                            //this means, we got no result
                            sleep(1);
                            return(false);
                        } else if($iChild_pid < 0) {
                            // -1 == error
#printf("hm...%d\n", $iChild_pid);
                            $this->checkThreadPids();
                            if($this->iCurrentThreads < $this->iMaxThreads) {
                                return(true);
                            } else {
                                return(false);
                            }
                        }
                    }
                } else {
                    return(true);
                }
                 //next line is to ensure that something is returned on method failure.
                 //do NOT remove as hundrets of childs may be forked on error!
                return(false);
            }
            
            function killChild($iPid = 0)
            {
                exit;
            }
            
            function checkThreadObject($oObject) {
                $aArray = get_class_methods($oObject);
                if(!in_array("run", $aArray)) {
                    return(false);
                }else if(!in_array("_die", $aArray)) {
                    return(false);
                } else {
                    return(true);
                }
            }
            
            function setMaxThreads($iMaxThreads = 10) {
                if(!is_integer($iMaxThreads)) {
                    $this->iMaxThreads = 10;
                    return(false);
                } else {
                    if($iMaxThreads < 0) {
                        $iMaxThreads = 0;
                    } else if($iMaxThreads > 30) {
                        $iMaxThreads = 30;
                    }
                    $this->iMaxThreads = $iMaxThreads;
                    return(true);
                }
            }
            
            function getMaxThreads() {
                return($this->iMaxThreads);
            }
            
            function isThreadActive($iValue = 0) {
                if(in_array((integer)$iValue, $this->aThreads)) {
                    return(true);
                } else {
                    return(false);
                }
            }
            
            function threadChild($oObject, $iValue = 0) {
                $iPid = -1;
                if(!is_object($oObject) || !$this->checkThreadObject($oObject)) {
//echo "HMx";
                    return(false);
                }
                
                if( $this->isThreadPossible($iValue) ) {
                    $iPid = pcntl_fork();
                    if(!is_numeric($iPid)) {
                        return(false);
                    } else {
                        $iPid = (integer)$iPid;
                    }
                    
                    if($iPid == -1) {
                        //could not fork
                        return(false);
                    } else if($iPid) {
                        //parent
                        $this->addThread($iPid, $iValue);
                        return(true);
                    } else {
                        //child
                        $oObject->run();
                        $oObject->_die();
                        // this is just to ensure the the child is really dying!
                        $this->killChild();
                        exit;
                    }
                } else {
                    return(false);
                }
                
                
                return(true); //this will never be reached and is just for security
            }//threadChild
    
            function threadSelf($iValue = 0)
            {
                if( $this->isThreadPossible($iValue) ) {
                    $iPid = pcntl_fork();
                    if($iPid > 0) {
                        $this->addThread($iPid, $iValue);
                        return(THREAD_IS_MAIN);
                    } else if($iPid == -1) {
                        return(false);  
                    } else if($iPid == 0) {
                        //no action for child. Has to be done in main program!
                        return(THREAD_IS_CHILD);
                    }
                } else {
                    return(false);
                }
                return(false); //this will never be reached and is just for security
            }
        }#class
        
        function fn_test_thread()
        {
            $oThread = new Thread();
            $oThread->setMaxThreads(10);
            $oThread->threadChild(new cl_test_thread());
        }
        
        Class cl_test_thread extends Thread
        {
            function cl_test_thread()
            {
                printf("Started.\n");
            }
        }
        
//        fn_test_thread();
    }#defined
?>
