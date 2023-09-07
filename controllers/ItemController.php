<?php

namespace controllers;

use model\Item;

class ItemController {
    public static function list() {

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
    }


    public static function delete(int $id) {

        $success = true;
        $message = "";
        
        try {
            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be deleted.");
            }
        
            $item = new Item($id);
            $item->delete();
        
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
            unset($item);
        }
        
        $ajaxObj = array(
            "success" => $success,
            "message" => $message,
            "data" => $item
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }

    public static function edit(int $id, string $name) {
        $success = true;
        $message = "";

        $name = trim($name);

        try {
            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be modified.");
            }
            if (empty($name)) {
                throw new \Exception("The name of the item cannot be empty.");
            }

            $item = new Item($id);
            $item->name = $name;

            $item->updateName();

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
            unset($item);
        }

        $ajaxObj = array(
            "success" => $success,
            "message" => $message,
            "data" => $item
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }

    public static function post(string $name) {
        $success = true;
        $message = "";

        $name = trim($name);

        try {
            if (empty($name)) {
                throw new \Exception("The name of the item cannot be empty.");
            }

            $item = new Item();
            $item->name = $name;

            $item->insert();

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
            unset($item);
        }

        $ajaxObj = array(
            "success" => $success,
            "message" => $message,
            "data" => $item
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }

    public static function toggleDone(int $id) {
        $success = true;
        $message = "";

        try {
            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be toggled.");
            }

            $item = new Item($id);

            $item->toggleDone();

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
            unset($item);
        }

        $ajaxObj = array(
            "success" => $success,
            "message" => $message,
            "data" => $item
        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ajaxObj);
    }

}