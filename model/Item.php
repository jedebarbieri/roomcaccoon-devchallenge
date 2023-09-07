<?php

class Item implements JsonSerializable {
	public $id;
	public $name;
	public $done;
	
	public function __construct($id = NULL) {
		if (!empty($id)) {
			$this->seleccionar($id);
		}
	}
	
	public function seleccionar($id = NULL) {
		if (!empty($id)) {
			$this->id = $id;
		}
		
		Conexion::conectar();

		Conexion::preparar_sentencia("SELECT * FROM item WHERE id=?");
				
		Conexion::$sentencia->bind_param("i", $this->id);

		Conexion::ejecutar();

		if (Conexion::$sentencia->num_rows < 1) {
			throw new \Exception("Item not found");
		}

		$resultado = Conexion::extraer_fila();
		$this->asignarDatos($resultado);
	}
	
	public function asignarDatos($arreglo) {
		$this->id = $arreglo["id"];
		$this->name = $arreglo["name"];
		$this->done = $arreglo["done"];
	}

    /**
     * Returns the complete list of items ordered by first the undone items and then the done
     * 
     */
    public static function list() {
        
		Conexion::conectar();
		Conexion::preparar_sentencia("
			SELECT *
			FROM 
				item
			ORDER BY done
		");
        
		Conexion::ejecutar();
		$lista = array();

		while($fila = Conexion::extraer_fila()) {
			$item = new Item();
			$item->asignarDatos($fila);
			$lista[] = $item;
		}
		return $lista;
    }
	
    /**
     * Adds a new item in the database. The done status is 0 by default
     */
	public function insert() {
		if (empty($this->name)) {
			throw new \Exception("You must enter a name.");
		}
		Conexion::conectar();
		Conexion::preparar_sentencia("
			INSERT INTO item (
				name,
				done
			) 
			VALUES (?,0)");
		
		$this->name = $this->name;
		$this->done = 0;
		
		Conexion::$sentencia->bind_param("s",
			$this->name
		);
		
		Conexion::ejecutar();
		$this->id = Conexion::$sentencia->insert_id;
	}
	
    /**
     * Only updates the name
     */
	public function updateName() {
		if (empty($this->name)) {
			throw new \Exception("You must enter a name.");
		}
		if (empty($this->id)) {
			throw new \Exception("Item is not defined.");
		}
		Conexion::conectar();
		Conexion::preparar_sentencia("
			UPDATE item
			SET
				name = ?
			WHERE id = ?");
		
		Conexion::$sentencia->bind_param("si",
			$this->name,
			$this->id
		);
		Conexion::ejecutar();
	}
	
	/**
	 * Removes an item
	 */
	public function delete() {
		if (empty($this->id)) {
			throw new \Exception("Item is not defined.");
		}

		Conexion::conectar();
		Conexion::preparar_sentencia("
			DELETE FROM item 
			WHERE
				id = ?
		");
		
		Conexion::$sentencia->bind_param("i", $this->id);
		Conexion::ejecutar();
	}

    /**
     * Marks or unmarks this item as done
     */
    public function toggleDone() {
		if (empty($this->id)) {
			throw new \Exception("Item is not defined.");
		}
		Conexion::conectar();
		Conexion::preparar_sentencia("
			UPDATE item
			SET
				done = ?
			WHERE id = ?");
		$this->done = !$this->done;
		Conexion::$sentencia->bind_param("ii",
			$this->done,
			$this->id
		);
		Conexion::ejecutar();
    }

	public function jsonSerialize() {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "done" => boolval($this->done)
        );
	}

}
