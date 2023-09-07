<?php

class Constantes {
	
	const DEFAULT_TIMEZONE = "America/Lima";
	const DATETIME_FORMAT = 'Y-m-d H:i:s';
	/**
	 * Indica el ambiente de producción
	 */
	const PRODUCTION = "prod";
	/**
	 * Indica el ambiente de desarrollo local con subdominio LDEV
	 */
	const DEVELOPMENT = "dev";
	
	/**
	 * Local
	 */
	const ABSOLUTE_URL = "http://roomcaccoon-devchallenge.com/";
	const NOMBRE_BD = "roomcaccoon";
	const HOST = "localhost";
	const AMBIENTE = self::DEVELOPMENT;
	const DBUSER = "root";
	const DBPASS = "";
	
	/**
	 * Prod
	 */
	
	// const ABSOLUTE_URL = "http://roomcaccoon.dulcejardin.com/";
	// const NOMBRE_BD = "dulcejardin_roomcaccoon";
	// const HOST = "localhost";
	// const AMBIENTE = self::PRODUCTION;
	// const DBUSER = "roomcaccoon";
	// const DBPASS = "59JWH6i3nZt7kuP";
	
	
	const TITULO_CMS = "CMS - Revista Virutal de Derecho";
}