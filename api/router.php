<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);

spl_autoload_register(function ($clase) {
	include_once '../' . str_replace("\\", "/", $clase) . '.php';
});

use controllers\ItemController;

switch ($_GET["action"]) {
	case 'list':
		ItemController::list();
		break;
	case 'post':
		ItemController::post($_POST["name"]);
		break;
	case 'edit':
		ItemController::edit(intval($_POST["id"]), $_POST["name"]);
		break;
	case 'toggleDone':
		ItemController::toggleDone(intval($_POST["id"]));
		break;
	case 'delete':
		ItemController::delete(intval($_POST["id"]));
		break;
	
	default:
		header( "HTTP/1.1 404 Not Found" );
		break;
}