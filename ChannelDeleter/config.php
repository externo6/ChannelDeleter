<?php
/* Fill in the TeamSpeak 3 Server Query Settings */
$cfg["host"]	= "10.11.12.13";			// the address of the TeamSpeak server
$cfg["query"]	= "10011";				// the TeamSpeak Server Query-Port (TCP)
$cfg["voice"]	= "9987";				// the TeamSpeak Server Voice-Port (UDP)
$cfg["user"]	= "serveradmin";			// the name of the QueryUser
$cfg["pass"]	= "querypass";				// the password for the QueryUser above

/* Fill in the MYSQL Database Settings */
$mysqldbhost	= "127.0.0.1";				// address where the MySQL server is hosted; default 127.0.0.1 (local)
$mysqldblogin	= "mysqluser";				// a MySQL user (Permissions: DROP, INSERT, UPDATE and SELECT)
$mysqldbpasswd	= "mysqlpass";				// the Password to the MySQL User above
$mysqldbname	= "ts3_channeldeleter";			// needn't to change, only if you wish
$table_channel	= "channellastuse";			// needn't to change, only if you wish
$table_update	= "upcheck";				// needn't to change, only if you wish

/* Fill in your wishes configuration */

// The Language, which should be use for the output
$language	= "en";					// possible choices are "en", "de" or "et"

// The Format which the date will be shown in
$dateformat	= "Y-m-d H:i";				// possible options -> http://php.net/manual/de/function.date.php

// The name, which the Query will use to connect
$queryname	= "Channeldeleter";			// its not the serverquery login name; you can name it free!
$queryname2	= "Channeldeleter2";			// Fallback name of query, if first one is already in use

// Time, which a channel have to be unused before this will deleted
$unusedtime	= "604800";				// time in seconds; example: 604800 = 1 week

// Time, which a channel have to be unused before warning on the list_delete.php
$warntime	= "43200";				// time in seconds; example: 432000 = 5 days

// Set a Icon to the channel, if the warntime is reached
$seticon	= "1";					// 1 = active; 0 = inactive
$deleteicons	= "0";					// 1 = active; 0 = inactive; delete all trash icons from server (perhaps reasonable if increase the warntime) -> for perfomance its not recommend to activate this permanent!

// Save spacer on the server -> will not delete spacer(rooms) if this is activate
$spacer		= "1";					// 1 = active; 0 = inactive

// A list of channels (id of the channel), which should not delete automatically
$nodelete	= array(358,359,360);			// seperate this with a komma

// You can save the delete_channel.php with a password, if you wish
$secure		= "1";					// 1 = active; 0 = inactive
$username	= "Username";				// Username to login
$password	= "098f6bcd4621d373cade4e832627b4f6";	// Password to login; its md5 encrypted -> use this to encrypt it http://bueltge.de/md5/
$accesswithurl	= "0";					// 1 = active; 0 = inactive; access the delete_channel.php with URL parameter -> not recommend! Example usage http://ts-n.net/chdel/delete_channel.php?user=Username&pass=Password

// Check for new Updates for the Channeldeleter
$update		= "1";					// 1 = active; 0 = inactive
$uniqueid	= array("xrTKhT/HDl4ea0WoFDQH2zOpmKg=","9odBYAU7z2E2feUz965sL0/MyBom=");	// a comma seperate liste of unique IDs of the Client, which should be informed on TeamSpeak
$updateinfotime	= "7200";				// time in seconds how often a message for update should send to unique ID
$currvers	= "2.01-beta 2014-06-09";		// current version of the Channeldeleter; you shouldn't change this
?>
