<?php
if (!defined("CLASS_SMSC")) {
    define("CLASS_SMSC", true);
	
    define('MT_TEXT',    				0);
    define('MT_OPLOGO',  				1);
    define('MT_CLIICON', 				2);
    define('MT_PICTURE', 				3);
    define('MT_NSONG',   				4);
    define('MT_SSONG',   				5);
    define('MT_BINARY',  				6);
    define('MT_SERVICE_INDICATION', 	7);
    define('MT_WAP_BOOKMARK',  			8);
    define('MT_BROWSER_SETTINGS',  		9);
	//#########################################################################
    define("SMSC_BASE_PATH", "/smsc/");
    define("SMSC_CLASSES",   SMSC_BASE_PATH."include/php/");
    define("SMSC_APPS",      SMSC_BASE_PATH."include/php/");
    define("SMSC_DAEMONS",   SMSC_BASE_PATH."include/php/");
    define("SMSC_IN",        SMSC_BASE_PATH."_in/");
    define("SMSC_OUT",       SMSC_BASE_PATH."_out/");
    define("SMSC_SECURE",    SMSC_BASE_PATH."_secure/");
    define("SMSC_VAR",       SMSC_BASE_PATH."var/");
	
    define("FSMSC_BASE_PATH", "/fsmsc/");
    define("FSMSC_VAR",       FSMSC_BASE_PATH."var/");
    
//    define("MYSQL_SERVER", "localhost");
    
	
    define("MYSQL_USER", 			"nobody");
    define("MYSQL_PASSWORD", 		"");
	
	/*** Endpoint definitions ***/
	define("SMSC_EP_NONE", 				-1);
	define("SMSC_EP_MO_UNDEF", 			1);
	define("SMSC_EP_SYSTEM_LOG",		2);
	define("SMSC_EP_SYSTEM_SCHEDULE", 	3);
	define("SMSC_EP_SYSTEM_UNHANDLED", 	4);
	define("SMSC_EP_SYSTEM_UNKNOWN_EP",	5);
	define("SMSC_EP_SYSTEM_OPTIN",		8);
	define("SMSC_EP_SYSTEM_STATUS",		9);
	define("SMSC_EP_SYSTEM_HISTORY",	265);
	
	define("SMSC_EP_TYPE_SYSTEM", 		0);
	define("SMSC_EP_TYPE_REVERSE", 		1);
	define("SMSC_EP_TYPE_BULK", 		2);
	define("SMSC_EP_TYPE_APPLICATIONS",	3);
	define("SMSC_EP_TYPE_OFFLINE",		10);
	
    class SMSC {
        /**** MESSAGE-TYPE *****/
        var $MSG_MT_TEXT    = 0;
        var $MSG_MT_OPLOGO  = 1;
        var $MSG_MT_CLIICON = 2;
        var $MSG_MT_PICTURE = 3;
        var $MSG_MT_NSONG   = 4;
        var $MSG_MT_SSONG   = 5;
        var $MSG_MT_BINARY  = 6;
        var $MSG_MT_SERVICE_INDICATON = 7;
		var $MSG_MT_WAP_BOOKMARK = 8;
		var $MSG_MT_BROWSER_SETTING = 9;
	
        /**** MESSAGE-PRIOTITY *****/
        var $MSG_MP_ALL      = -1;
        var $MSG_MP_HIGH     = 0;
        var $MSG_MP_HIGHER   = 1;
        var $MSG_MP_NORMAL   = 2;
        var $MSG_MP_LOW      = 3;
        var $MSG_MP_LOWER    = 4;
        
        var $MSG_NO_OPERATOR = "00000";
        
        /*** MESSAGE-API ***/
        /*** Directions ***/
        var $MA_MO   = 0;
        var $MA_MT   = 1;
        
        /*** Actions ***/
        var $MA_AC_IN   = 0;
        var $MA_AC_PUSH = 1;
        var $MA_AC_POP  = 2;
        var $MA_AC_UPD  = 3;
        var $MA_AC_OUT  = 4;
        
        /*** Servicetypes ***/
        var $MA_ST_UNDEF        = -1; // MA_ST_UNSET means, message type is not defined.
        var $MA_ST_UNSET        = 0; // MA_ST_UNSET means, message type is not set.
        var $MA_ST_DELIVER      = 1000; // MA_ST_DELIVER means, sending out the message
        var $MA_ST_CHANGE       = 1001; // MA_ST_CHANGE means, the message is currently in work
        var $MA_ST_REQUEUE      = 1002; // MA_ST_REQUEUE means, the message should be requeued.
                                                           // The original servicetype will be taken from the message itself.
        var $MA_ST_STORE        = 1003; // MA_ST_STORE will store the message for later, manual corrections.
                                                           // This should be used only if we encounter a corrupt message that can
                                                           // not be handled by standart routines
        var $MA_ST_UNHANDLED    = 1004; 
        var $MA_ST_UNKNOWN_EP   = 1005;
        
        var $MA_ST_CHANNELS     = 1;
        var $MA_ST_SEXCHANNELS  = 5;
        var $MA_ST_POWERBOSS    = 10;
        var $MA_ST_SEXCHAT      = 6;
        var $MA_ST_WEBORDER     = 11;
        
        var $UNDEF_T_INT      = 0;
        var $UNDEF_T_CID      = -1;
        var $UNDEF_T_AID      = -1;
        var $UNDEF_T_LONG     = 0;
        var $UNDEF_T_STRING   = "";
        var $UNDEF_T_OPERATOR = "00000";
    
        /*** DB ***/
        var $DB_QUEUEAPI_MAINTB    = "queue";
        var $DB_QUEUEAPI_SPAMTB    = "queue_spam";
        var $DB_QUEUELOG_MAINTB    = "queue_log";
    }
}
?>
