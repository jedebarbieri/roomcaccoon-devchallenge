<?php
spl_autoload_register(function ($clase) {
	include_once '../model/' . str_replace("\\", "/", $clase) . '.php';
});

$success = true;
$message = "";

try {
    $list = Item::list();
} catch (\Exception $e) {
	$success = false;
	$message = $e->getMessage();
	unset($list);
}

$ajaxObj = array(
	"success" => $success,
	"message" => $message,
	"data" => $list
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($ajaxObj);

