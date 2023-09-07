<?php
namespace model;
abstract class Conexion {

	/**
	 * @var mysqli Conexión a la BD
	 */
	public static $conexion;

	/**
	 * @var mysqli_stmt Sentencia preparada para la consulta a la BD
	 */
	public static $sentencia;

	/**
	 * @var string Cadena de texto de la sentencia preparada
	 */
	public static $query;
	public static $mensaje_error;
	
	public static $FLAG_SEGUNDO_QUERY = FALSE;
	
	/**
	 * Constantes de ERROR de base de datos
	 */
	/**
	 * @var int Código de exceptión cuando se intenta eliminar un registro InnoDB padre que tiene dependencias que no
	 * pueden ser eliminadas.
	 */
	const ER_ROW_IS_REFERENCED = 1217;
	/**
	 * @var int Código de exceptión cuando se intenta eliminar un registro padre que tiene dependencias que no
	 * pueden ser eliminadas.
	 */
	const ER_ROW_IS_REFERENCED_2 = 1451;
	

	public static function conectar() {
		if (empty(self::$conexion)) {
			self::$conexion = new \mysqli(Constantes::HOST, Constantes::DBUSER, Constantes::DBPASS, Constantes::NOMBRE_BD);
			if (mysqli_connect_error()) {
				throw new \Exception("Error conectando a la base de datos. (" . mysqli_connect_errno() . ": " . mysqli_connect_error());
			} else {
				self::$conexion->set_charset("utf8mb4");
				date_default_timezone_set(Constantes::DEFAULT_TIMEZONE);
				return self::$conexion;
			}
		}
	}

	public static function preparar_sentencia($query_str) {
		if (self::$FLAG_SEGUNDO_QUERY) {
			throw new \Exception("Se ejecutó por segunda vez el query: ".$query_str);
		}
		self::$query = $query_str;
		if (!(self::$sentencia = self::$conexion->prepare($query_str))) {
			throw new \Exception("Error al crear la sentencia: " . (Constantes::AMBIENTE == Constantes::PRODUCTION ? $query_str : ""));
		}
	}

	/**
	 * Ejecuta la sentencia. Si hay error lo coloca en $mensaje_error
	 * @throws \Exception
	 */
	public static function ejecutar() {
		if (!self::$sentencia->execute()) {
			self::$mensaje_error = self::$sentencia->error;
			self::$sentencia->close();
			throw new \Exception("Conexion->ejecutar() : Error en la sentencia.<br/>" . (Constantes::AMBIENTE == Constantes::DEVELOPMENT ? (self::$mensaje_error ."<br/>" . self::$query) : ""), self::$sentencia->errno);
		}
		self::$sentencia->store_result();
	}

	public static function cerrar() {
		@self::$sentencia->close();
	}

	public function __destruct() {
		self::$conexion->close();
	}

	/**
	 * Devuelve un arreglo asociativo con el nombre de la columna y el valor
	 * @param boolean $por_tabla Indica si devolverá un arreglo unidimensional (false) o uno bidimensional (true) para cada tabla consultada.
	 * El nombre de la tabla estará dado por el alias de la tabla si lo tuviera.
	 * Estructura:
	 * Array (
	 *	"tabla1" => array(
	 *		"col1" => "val"
	 *	)
	 * )
	 * @return array
	 */
	public static function extraer_fila($por_tabla = false) {
		$columnas = self::$sentencia->result_metadata()->fetch_fields();
		$filaArreglo = array();
		$filaReferencias = array();

		/*
		 * Creamos un arreglo con los espacios para almacenar las variables y con los "keys" respectivos
		 * Además creamos un arreglo con las referencias a cada celda del anterior arreglo.
		 */
		foreach ($columnas as $col) {
			if ($por_tabla && !empty($col->table)) {
				$filaArreglo[$col->table][$col->name] = NULL;
				$filaReferencias[$col->table.".".$col->name] = &$filaArreglo[$col->table][$col->name];
			} else {
				$filaArreglo[$col->name] = NULL;
				$filaReferencias[$col->name] = &$filaArreglo[$col->name];
			}
		}

		/*
		 * Para cada valor del arreglo de referencias, ejecutamos la función bind_result
		 */
		call_user_func_array(array(self::$sentencia, "bind_result"), array_values($filaReferencias));
		

		/*
		 * Cogemos los datos de la fila en el arreglo filaArreglo y el éxito se asigna a retorno
		 */
		$retorno = self::$sentencia->fetch();

		if ($retorno) {
			return $filaArreglo;
		} else {
			return $retorno;
		}
	}

}
