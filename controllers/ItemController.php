<?php

namespace controllers;

use model\Item;
use views\JsonResponse;

class ItemController {
    public static function list() {
        try {

            $list = Item::list();
            JsonResponse::output($list);

        } catch (\Exception $e) {
            JsonResponse::error($e->getMessage());
        }
    }


    public static function delete(int $id) {        
        try {

            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be deleted.");
            }
        
            $item = new Item($id);
            $item->delete();

            JsonResponse::output($item);

        } catch (\Exception $e) {
            JsonResponse::error($e->getMessage());
        }
    }

    public static function edit(int $id, string $name) {
        try {

            $name = trim($name);

            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be modified.");
            }
            if (empty($name)) {
                throw new \Exception("The name of the item cannot be empty.");
            }

            $item = new Item($id);
            $item->name = $name;

            $item->updateName();

            JsonResponse::output($item);

        } catch (\Exception $e) {
            JsonResponse::error($e->getMessage());
        }
    }

    public static function post(string $name) {
        try {

            $name = trim($name);
            if (empty($name)) {
                throw new \Exception("The name of the item cannot be empty.");
            }

            $item = new Item();
            $item->name = $name;

            $item->insert();

            JsonResponse::output($item);

        } catch (\Exception $e) {
            JsonResponse::error($e->getMessage());
        }
    }

    public static function toggleDone(int $id) {
        try {
            if (empty($id)) {
                throw new \Exception("You must indicate the id of the item to be toggled.");
            }

            $item = new Item($id);

            $item->toggleDone();

            JsonResponse::output($item);

        } catch (\Exception $e) {
            JsonResponse::error($e->getMessage());
        }
    }

}