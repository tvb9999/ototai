<?php
/*
+-----------------------------------------------------------------------------
|   VS FRAMEWORK 3.0.0
|	Author: BabyWolf
|	Homepage: http://vietsol.net
|	If you use this code, please don't delete these comment line!
|	Start Date: 21/09/2004
|	Finish Date: 22/09/2004
|	Version 2.0.0 Start Date: 07/02/2007
|	Version 3.0.0 Start Date: 03/29/2009
+-----------------------------------------------------------------------------
*/

// Get system constant
require_once "./constant.php";

// Application constant
define ( 'SKIN_PATH'	, 'skins/user/' );
define ( "LANG_PATH"	, ROOT_PATH . 'langs/user/' );
define ( 'APPLICATION_TYPE', 'user' );

require_once LIBS_PATH."Debug.class.php";
require_once LIBS_PATH."Info.class.php";
require_once ROOT_PATH."Main.php";

?>