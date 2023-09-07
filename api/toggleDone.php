<?php
spl_autoload_register(function ($clase) {
	include_once '../model/' . str_replace("\\", "/", $clase) . '.php';
});

$success = true;
$message = "";

try {
	if (empty(intval($_POST["id"]))) {
		throw new \Exception("You must indicate the id of the item to be toggled.");
	}

	$item = new Item(intval($_POST["id"]));

    $item->toggleDone();

} catch (\Exception $e) {
	$success = false;
	$message = $e->getMessage();
	unset($comentario);
}

$ajaxObj = array(
	"success" => $success,
	"message" => $message,
	"data" => $item
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($ajaxObj);

