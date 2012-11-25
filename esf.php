<?php
 
 session_start();
 
 require_once('library/tools/tools.cookies.php');
 require_once('library/view/view.install.php');

 try
 {
	 // main esf/ directory
	 define('ESF_DIR',dirname(__FILE__));
	 // root directory (array)
	 $rt_dir_array  = explode('/', ESF_DIR);
	 // cut off last array (last or current directory)
	 array_pop($rt_dir_array);
	 // define the root directories
	 define('ESF_ROOT_DIR',implode('/',$rt_dir_array));
	 define('ESF_CONT_DIR',ESF_ROOT_DIR.'/esf_controllers');
	 define('ESF_ARCT_DIR',ESF_ROOT_DIR.'/esf_architect');
	 define('ESF_SETT_DIR',ESF_ROOT_DIR.'/esf_settings');
	 
	 // current view/page variable
	 $esf_pg = ((!isset($_GET['pg'])) ? 'index' : strtolower($_GET['pg']));
	 	 
	 // controller class
	 $controllerFile = ESF_CONT_DIR .'/' . $esf_pg . '.php';
	 if(file_exists($controllerFile))
	 {
		 require_once($controllerFile);
		 $controllerObj = new $esf_pg(esf_cookie('esf_alvl'));
	 }
	 else 
	 {
		 throw new Exception('ESF: controllerObj->[[ '.$controllerFile.' ]]');
	 }
	 
	 // enable call to database(s)
	 if ($controllerObj->esf_allow_database==true) require_once('library/db/db.install.php');
	 if ($controllerObj->esf_allow_database==true) $controllerObj->callDatabase();
	 
	 // call to page controller
	 $controllerObj->callViewController();
	 
	 // call headers
	 $controllerObj->callHeader();
	
	 // render entire view (display)
	 print $controllerObj->render($esf_pg);
	  
	 // print_r($controllerObj);
 } 
 catch(Exception $e) 
 {
	 die($e->getMessage());
 }
 
  