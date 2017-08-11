<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use PDO;
use PDOException;
use PDOStatement;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Connection extends PDO
{
    private static $id;
    private static $instance;


    /**
      * Starts the connection instance, if it has already been declared before, does not duplicate it and saves memory.
      *
      * @param string $DATABASE, A database other than the one defined in DATABASE ['name'] is optionally passed to connect
      *
      * @return connection instance
      */
    final public static function start(string $DATABASE = DATABASE['name'], string $MOTOR = DATABASE['motor'], bool $new_instance = false): Conexion
    {
        if (!self::$instance instanceof self or $new_instance) {
            self::$instance = new self($DATABASE, $MOTOR);
        }

        return self::$instance;
    }


    /**
      * Starts database connection
      *
      * @param string $DATABASE, A database other than the one defined in DATABASE ['name'] is optionally passed to connect
      *
      * @return void
      */
    final public function __construct(string $DATABASE = DATABASE['name'], string $MOTOR = DATABASE['motor'])
    {
        try {
            switch ($MOTOR) {
              case 'sqlite':
                parent::__construct('sqlite:'.$DATABASE);
              break;
              case 'cubrid':
                parent::__construct('cubrid:host='.DATABASE['host'].';dbname='.$DATABASE.';port='.DATABASE['port'], DATABASE['user'], DATABASE['pass'], array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
              break;
              case 'firebird':
                parent::__construct('firebird:dbname='.DATABASE['host'].':'.$DATABASE, DATABASE['user'], DATABASE['pass'], array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
              break;
              case 'odbc':
                parent::__construct('odbc:'.$DATABASE, DATABASE['user'], DATABASE['pass']);
              break;
              case 'oracle':
                parent::__construct(
                    'oci:dbname=(DESCRIPTION =
                  (ADDRESS_LIST =
                    (ADDRESS = (PROTOCOL = '.DATABASE['protocol'].')(HOST = '.$MOTOR.')(PORT = '.DATABASE['port'].'))
                  )
                  (CONNECT_DATA =
                    (SERVICE_NAME = '.$DATABASE.')
                  )
                );charset=utf8',
                    ['user'],
                    ['pass'],
                [
                  PDO::ATTR_EMULATE_PREPARES => false,
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
              break;
              case 'postgresql':
                parent::__construct('pgsql:host='.DATABASE['host'].';dbname='.$DATABASE.';charset=utf8', DATABASE['user'], DATABASE['pass'], [
                  PDO::ATTR_EMULATE_PREPARES => false,
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
              break;
              case 'mysql':
                parent::__construct('mysql:host='.DATABASE['host'].';dbname='.$DATABASE, DATABASE['user'], DATABASE['pass'], [
                  PDO::ATTR_EMULATE_PREPARES => false,
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                ]);
              break;
              default:
                if (IS_API) {
                    die(json_encode([
                      'success' => 0,
                      'message' => 'Unidentified connection engine.'
                    ]));
                } else {
                    die('Unidentified connection engine.');
                }
              break;
            }
        } catch (PDOException $e) {
            if (IS_API) {
                die(json_encode([
                  'success' => 0,
                  'message' => 'Error attempting to connect to database.'
                ]));
            } else {
                die('Error attempting to connect to database.');
            }
        } finally {
            unset($DATABASE, $MOTOR);
        }
    }

    final public function lastInsertId()
    {
        return parent::lastInsertId() ?? $this->id;
    }


    /**
      * Returns an associative array of all the results thrown by a query
      *
      * @param object PDOStatement $query, return value of the query
      *
      * @return associative array
      */
    final public function fetchArray(PDOStatement $query): array
    {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
      * Get the number of rows found after a SELECT
      *
      * @param object PDOStatement $query, return value of the query
      *
      * @return number of rows found
      */
    final public function rows(PDOStatement $query): int
    {
        return $query->rowCount();
    }


    /**
      * Heals a value to be later entered into a query
      *
      * @param string/int/float
      *
      * @return int/float/string
      */
    final public function escape($e)
    {
        if (!isset($e)) {
            return '';
        }

        if (is_numeric($e) and PHP_INT_MIN <= $e and $e <= PHP_INT_MAX) {
            if (explode('.', $e)[0] != $e) {
                return (float) $e;
            }
            return (int) $e;
        }

        return (string) trim(str_replace(['\\',"\x00",'\n','\r',"'",'"',"\x1a"], ['\\\\','\\0','\\n','\\r',"\'",'\"','\\Z'], $e));
    }

    /**
      * Performs a query, and if it is in debug mode it analyzes which query was executed
      *
      * @param SQL string, recieve a SQL query to execute
      *
      * @return object PDOStatement
      */
    final public function query(string $q): PDOStatement
    {
        try {
            if (DEBUG) {
                $_SESSION['___QUERY_DEBUG___'][] = (string) $q;
            }

            return parent::query($q);
        } catch (Exception $e) {
            if (defined(GENERATOR)) {
                $message = "\nError in query:\n $q \n\n" . $e->getMessage();
            } else {
                $message = 'Error in query: <b>' . $q . '<b/><br /><br />' . $e->getMessage();
            }
            die(IS_API ? json_encode(array('success' => 0, 'message' => $message)): $message);
        }
    }

    /**
      * Clears a series of items securely from a table in the database
      *
      * @param string $table: Table to which you want to remove an element
      * @param string $where: Deletion condition that defines who are those elements
      * @param string $limit: By default it is limited to deleting a single element that matches the $ where
      *
      * @return object PDOStatement
      */
    final public function delete(string $table, string $where, string $limit = 'LIMIT 1'): PDOStatement
    {
        return $this->query("DELETE FROM $table WHERE $where $limit;");
    }

    /**
      * Insert a series of elements into a table in the database
      *
      * @param string $table: Table to which elements are to be inserted
      * @param array $e: Associative arrangement of elements, with the 'field_en_la_tabla' => 'value_to_insertar_en_ese_campo',
      *                  all elements of the array $ e, will be healed by the method without having to do it manually when creating the array...
      *
      * @return object PDOStatement
      */
    final public function insert(string $table, array $e): PDOStatement
    {
        if (sizeof($e) == 0) {
            trigger_error('array passed in $this->db->insert(...) is empty.', E_ERROR);
        }

        $query = "INSERT INTO $table (";
        $values = '';
        foreach ($e as $campo => $v) {
            $query .= $campo . ',';
            $values .= '\'' . $this->escape($v) . '\',';
        }
        $query[strlen($query) - 1] = ')';
        $values[strlen($values) - 1] = ')';
        $query .= ' VALUES (' . $values . ';';

        self::$id = parent::lastInsertId($table);

        return $this->query($query);
    }

    /**
      * Updates elements of a table in the database according to a condition
      *
      * @param string $table: table to update
      * @param array $e: Arreglo asociativo de elementos, con la estrctura 'campo_en_la_tabla' => 'valor_a_insertar_en_ese_campo',
      *                  todos los elementos del arreglo $e, serán sanados por el método sin necesidad de hacerlo manualmente al crear el arreglo
      * @param string $where: Condition indicating who will be modified
      * @param string $limite: Limit modified elements, by default modifies them all
      *
      * @return object PDOStatement
      */
    final public function update(string $table, array $e, string $where, string $limit = ''): PDOStatement
    {
        if (sizeof($e) == 0) {
            trigger_error('El arreglo pasado por $this->db->update(...) está vacío.', E_ERROR);
        }

        $query = "UPDATE $table SET ";
        foreach ($e as $campo => $valor) {
            $query .= $campo . '=\'' . $this->escape($valor) . '\',';
        }
        $query[strlen($query) - 1] = ' ';
        $query .= "WHERE $where $limit;";

        return $this->query($query);
    }

    /**
      * Selects and lists in an associative / numeric array the results of a search in the database
      *
      * @param string $e: Elements to Select Comma Separated
      * @param string $table: Table from which you want to extract the elements $e
      * @param string $where: Condition that indicates who are the ones that are extracted, if not placed extracts all
      * @param string $limite: Limit of items to bring, by default brings ALL those that match $where
      *
      * @return False if you do not find any results, array associative / numeric if you get at least one
      */
    final public function select(string $e, string $table, string $where = '1 = 1', string $limit = "")
    {
        $sql = $this->query("SELECT $e FROM $table WHERE $where $limit;");
        $result = $sql->fetchAll();
        $sql->closeCursor();

        return $sql;
    }

    /**
      * Alert to avoid cloning
      *
      * @return void
      */
    final public function __clone()
    {
        trigger_error('Estás intentando clonar la Conexión', E_ERROR);
    }
}
