<?php
spl_autoload_register(function ($clase) {
	include_once '../model/' . str_replace("\\", "/", $clase) . '.php';
});

$success = true;
$message = "";

try {
	if (empty(intval($_POST["id"]))) {
		throw new \Exception("You must indicate the id of the item to be modified.");
	}
	if (empty($_POST["name"])) {
		throw new \Exception("The name of the item cannot be empty.");
	}

	$item = new Item(intval($_POST["id"]));
    $item->name = $_POST["name"];

    $item->updateName();

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

