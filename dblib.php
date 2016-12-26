<?php
/*
   Copyright 2007, 2008 Nicolás Gudiño

   This file is part of Asternic Call Center Stats.

    Asternic Call Center Stats is free software: you can redistribute it 
    and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 3 of the 
    License, or (at your option) any later version.

    Asternic Call Center Stats is distributed in the hope that it will be 
    useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Asternic Call Center Stats.  If not, see 
    <http://www.gnu.org/licenses/>.
*/

if (!isset($DB_MUERE)) { $DB_MUERE = false; }
if (!isset($DB_DEBUG)) { $DB_DEBUG = true; }

function conecta_db($dbhost, $dbname, $dbuser, $dbpass) {
/* conecta a la base de datos $dbname en el host $dbhost con el nombre y clave
 * $dbuser y $dbpass. */

	global $DB_MUERE, $DB_DEBUG;

	if (! $dbh = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)) {
		if ($DB_DEBUG) {
			echo "<h2>No pude conectar a $dbhost como $dbuser</h2>";
			echo "<p><b>Error de MySQL</b>: ", mysqli_error();
		} else {
			echo "<h2>Error </h2>";
		}

		if ($DB_MUERE) {
			echo "<p>Este script no puede continuar. Abortando...";
			die();
		}
	}
/*
	if (! mysqli_select_db($dbname)) {
		if ($DB_DEBUG) {
			echo "<h2>Imposible seleccionar la tabla $dbname</h2>";
			echo "<p><b>Error de MySQL</b>: ", mysql_error();
		} else {
			echo "<h2>Error en la Base de Datos</h2>";
		}

		if ($DB_MUERE) {
			echo "<p>Este script no puede continuar. Abortando...";
			die();
		}
	}
*/
	return $dbh;
}

function desconecta_db() {
/* desconecta de la base de datos, normalmente no se usa ya que PHP
 * lo hace por su cuenta */

	mysqli_close();
}

function consulta_db($query, $debug=false, $die_on_debug=true, $silent=false,$midb) {
/* ejecuta la consulta $query en la base de datos en uso. Si $debug es verdadero
 * vamos a mostrar la consulta en pantalla. Si $die_on_debug es verdadero, y
 * $debug es verdadero, detendremos el script luego de imprimir el error,
 * si no ejecutaremos la consulta. Si $silent es verdadero entonces suprimiremos
 * todos los mensajes de error, si no diremos que ha ocurrido un error
 * en la base de datos */
 
	global $DB_MUERE, $DB_DEBUG;

	if ($debug) {
		echo "<pre>" . htmlspecialchars($query) . "</pre>";

		if ($die_on_debug) die;
	}

	$qid = mysqli_query($midb,$query);

	if (! $qid && ! $silent) {
		if ($DB_DEBUG) {
			echo "<h2>No pude ejecutar la consulta</h2>";
			echo "<pre>" . htmlspecialchars($query) . "</pre>";
			echo "<p><b>Error de MySQL</b>: ", mysqli_error();
		} else {
//			echo "<h2>Error 1en la Base de Datos</h2>";
		}

		if ($DB_MUERE) {
			echo "<p>Este script no puede continuar. Abortando...";
			die();
		}
	}

	return $qid;
}

function db_fetch_array($qid) {
/* devuelve un array asociativo con la siguiente columna devuelta por la 
 * consulta identificada por $qid. Si no hay mas resultados, devuelve FALSE */

	return mysqli_fetch_array($qid);
}

function db_fetch_row($qid) {
/* grab the next row from the query result identifier $qid, and return it
 * as an array.  if there are no more results, return FALSE */

	return mysqli_fetch_row($qid);
}

function db_fetch_object($qid) {
/* grab the next row from the query result identifier $qid, and return it
 * as an object.  if there are no more results, return FALSE */

	return mysqli_fetch_object($qid);
}

function db_num_rows($qid) {
/* return the number of records (rows) returned from the SELECT query with
 * the query result identifier $qid. */

	return mysqli_num_rows($qid);
}

function db_affected_rows() {
/* return the number of rows affected by the last INSERT, UPDATE, or DELETE
 * query */

	return mysqli_affected_rows();
}

function db_insert_id() {
/* if you just INSERTed a new row into a table with an autonumber, call this
 * function to give you the ID of the new autonumber value */

	return mysqli_insert_id();
}

function db_free_result($qid) {
/* libera los recursos utilizados por la consulta identificada por $qid */

	mysqli_free_result($qid);
}

function db_num_fields($qid) {
/* devuelve el numero de campos devueltos por el SELECT identificado
 * por $qid */

	return mysqli_num_fields($qid);
}

function db_field_name($qid, $fieldno) {
/* devuelve el nombre del campo $fieldno devuelto por el SELECT identificado
 * por $qid */

	return mysqli_field_name($qid, $fieldno);
}

function db_data_seek($qid, $row) {
/* move the database cursor to row $row on the SELECT query with the identifier
 * $qid */

	if (db_num_rows($qid)) { return mysqli_data_seek($qid, $row); }
}
?>

