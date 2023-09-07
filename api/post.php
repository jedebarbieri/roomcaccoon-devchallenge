<?php
spl_autoload_register(function ($clase) {
	include_once '../model/' . str_replace("\\", "/", $clase) . '.php';
});

$success = true;
$message = "";

try {
	if (empty($_POST["name"])) {
		throw new \Exception("The name of the item cannot be empty.");
	}

	$item = new Item();
    $item->name = $_POST["name"];

    $item->insert();

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
echo json_encode($ajaxObj);

