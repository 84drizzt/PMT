<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to public directory   
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__) ));

    // Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/forms'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap();


$newTable = new Model_DbTable_ArtWorks();
$tagTable = new Model_DbTable_ArtworkToTag();
$subTable = new Model_DbTable_ArtworkToSubject();
$styleTable = new Model_DbTable_ArtworkToStyle();

$tagOTable = new Model_DbTable_Tags();
$styleOTable = new Model_DbTable_Styles();
$subjectOTable = new Model_DbTable_Subjects();

$old_connection = mysql_connect("localhost","root", "vvmylove");
$old_db = mysql_select_db("artdepot", $old_connection);

$att_sql = "SELECT * from art_work";
mysql_query("SET NAMES utf8");
$results = mysql_query($att_sql, $old_connection);

while ($result = mysql_fetch_assoc($results)){
	$value["id"] = $result["id"];
	$value["e_name"] = $result["name"];
	$value["artist"] = $result["artist"];
	$value["base_price"] = $result["base_price"];
	$value["usd_price"] = $result["usd_price"];
	$value["rmb_price"] = $result["rmb_price"];
	$value["uro_price"] = $result["uro_price"];
	$value["knockdown_price"] = $result["knockdown_price"];
	$value["knockdown_currency"] = $result["knockdown_currency"];
	$value["e_intro"] = $result["intro"];
	$value["c_size"] = $result["size"];
	$value["e_size"] = $result["inch_size"];
	$value["date_of_creation"] = $result["date_of_creation"];
	$value["photo"] = $result["photo"];
	$value["category_id"] = $result["category"];
	
	$tags = explode(",", $result["tags"]);
	$subjects = explode(",", $result["subject"]);
	$styles = explode(",", $result["style"]);
	
	$id = $newTable->insert($value);
	
	foreach ($subjects as $subject){
		if (!$subject){
			continue;
		}
		if ($subjectOTable->fetchAll("id = $subject")->current()) {
			$data = array("artwork_id" => $value["id"], "subject_id" => $subject);
			$subTable->insert($data);
		}
	}
	foreach ($tags as $tag){
		if (!$tag){
			continue;
		}
		if ($tagOTable->fetchAll("id = $tag")->current()) {
			$data = array("artwork_id" => $value["id"], "tag_id" => $tag);
			$tagTable->insert($data);
		}
		
	}
	foreach ($styles as $style){
	if (!$style){
			continue;
		}
		if ($styleOTable->fetchAll("id = $style")->current()) {
			$data = array("artwork_id" => $value["id"], "style_id" => $style);
			$styleTable->insert($data);
		}
	}
	
}



mysql_close($old_connection);
echo "Importing finished at ". date("Y-m-d H:i:s"). "\n";





