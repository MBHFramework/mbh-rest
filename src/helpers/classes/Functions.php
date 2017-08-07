<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace MBHF\Helpers\Functions;

final class Functions
{
    final public static function encrypt(string $e) : string
    {
        // Function made to be used in user passwords
        $str = '';
        for ($i = 0; $i < strlen($e); $i++) {
            $str .= ($i % 2) != 0 ? md5($e[$i]) : $i;
        }
        return md5($str);
    }

    final public static function encrypt_with_key(string $e, string $key) : string
    {
        // Function made to be used in data
        $result = '';
        for ($i = 0; $i < strlen($e); $i++) {
            $char = substr($e, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    final public static function decrypt_with_key(string $e, string $key) : string
    {
        // Function made to be used in data
        $result = '';
        $e = base64_decode($e);
        for ($i = 0; $i < strlen($e); $i++) {
            $char = substr($e, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    final public static function redirect(string $url = URL)
    {
        header('location: ' . $url);
    }
    /**
      * Alias of the "empty" function, more complete
      *
      * @param midex $var: Variable to analyze
      *
      * @return true if empty, false otherwise, empty space counts as empty
    */
    final public static function empty($var) : bool
    {
        return (isset($var) && empty(trim(str_replace(' ', '', $var))));
    }

    /**
      * It analyzes that ALL the elements of an array are full, useful to analyze for example
      * that all the elements of a form are filled passing as parameter $ _POST
      *
      * @param array $array, array to analyze
      *
      * @return true if they are all filled, false if at least one is empty
    */
    final public static function all_full(array $array) : bool
    {
        foreach ($array as $e) {
            if (self::emp($e) and $e != '0') {
                return false;
            }
        }
        return true;
    }

    /**
      * Alias of the "empty" function, but supports more than one parameter
      *
      * @param infinite parameters
      *
      * @return true if at least one is empty, false if all are full
    */
    final public static function e() : bool
    {
        for ($i = 0, $nargs = func_num_args(); $i < $nargs; $i++) {
            if (self::emp(func_get_arg($i)) and func_get_arg($i) != '0') {
                return true;
            }
        }
        return false;
    }

    /**
      * Reduce string
      *
      * @param string $string
      * @param int $limit
      *
      * @return string
    */
    final public static function reduce_string(string $string, int $limit) : string
    {
        if (strlen($string) <= $limit) {
            return $string;
        } else {
            $string = substr($string, 0, $limit);
            $words = explode(' ', $string);
            $output = implode(' ', $words);
            $output .= '...';
        }
        return $output;
    }


}
