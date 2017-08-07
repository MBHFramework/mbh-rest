<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

namespace Mbh;

use Mbh\Helpers\Functions as Functions;

# Security
 defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

/**
 * created by Federico Ramón Gasquez
 */
final class Debug
{
    const HEAD = '<style>
     #debug {
       margin-bottom: 18px;
       margin-left: auto;
       margin-right: auto;
       width: 95%;
       background: #f7f7f9;
       border: 1px solid #e1e1e8;
       padding: 8px;
       border-radius: 4px;
       -moz-border-radius: 4px;
       -webkit-border radius: 4px;
       display: block;
       font-size: 12.05px;
       white-space: pre-wrap;
       word-wrap: break-word;
       color: #333;
       font-family: \'Menlo\',\'Monaco\',\'Consolas\',\'Courier New\',\'monospace\';
     }
     .variable {
       font-weight: bold!important;
       color: #e06a60!important;
     }
     .cab {
       font-weight: bold!important;
       color: #3864c6!important;
     }
     .b {
       color: #9eb2a9!important;
     }
     </style>
     <br /><div id="debug">';

    const FOOT = '</div>';

    /**
      * Start time
      *
      * @return Start-Time, Start time of code execution
      */
    final public static function startime()
    {
        $startime = explode(" ", microtime());
        return $startime[0] + $startime[1];
    }


    /**
      * List an array, displaying its contents
      *
      * @param array $VAR: Array to display
      * @param string $variable: Written form of the variable being broken down
      *
      * @return void
      */
    final private function listVar(array $VAR, string $variable)
    {
        echo '<strong class="cab">',$variable,':</strong> <br />';
        echo '<ul>';
        foreach ($VAR as $key => $value) {
            if ($key != '___QUERY_DEBUG___') {
                echo '<li><span class="variable">', $variable ,'</span><span class="b">[\'</span>', $key ,'<span class="b">\']</span>', d($value) ,'</li>';
            }
        }
        echo "</ul>";
    }

    /**
      * Constructor, initialize debug mode
      *
      * @param int $startime: Start-Time, Start time of code execution
      *
      * @return void
      */
    final public function __construct(int $startime)
    {
        #Templates names
        $template_engine = ['PlatesPHP','Twig'];

        # End of Speed test
        $endtime = explode(" ", microtime());
        $endtime = $endtime[0] + $endtime[1];
        $memory = Functions::convert(memory_get_usage());



        echo self::HEAD;
        echo "<b class=\"cab\">File:</b> {$_SERVER['PHP_SELF']}<br />";
        echo "<b class=\"cab\">PHP:</b> " . phpversion() . "<br />";
        echo "<b class=\"cab\">FRAMEWORK:</b> " . VERSION . "<br />";
        echo "<b class=\"cab\">Template Engine:</b> {$template_engine[(int) TWIG_TEMPLATE_ENGINE]} <br />";



        if (isset($_SESSION)) {
            $this->listVar($_SESSION, '$_SESSION');
        } else {
            echo 'No <span class="variable">$_SESSION</span> variables<br />';
        }
        if ($_POST) {
            $this->listVar($_POST, '$_POST');
        } else {
            echo 'No <span class="variable">$_POST</span> variables<br />';
        }
        if ($_GET) {
            $this->listVar($_GET, '$_GET');
        } else {
            echo 'No <span class="variable">$_GET</span> variables<br />';
        }
        if ($_FILES) {
            $this->listVar($_FILES, '$_FILES');
        } else {
            echo 'No <span class="variable">$_FILES</span> variables<br />';
        }


        if (isset($_SESSION['___QUERY_DEBUG___']) and sizeof($_SESSION['___QUERY_DEBUG___']) > 0) {
            echo '<br /><strong class="cab">QUERYS:</strong><br />';
            echo '<ul style="list-style:none;padding:0;">';
            foreach ($_SESSION['___QUERY_DEBUG___'] as $query) {
                echo "<li><ul><li><span class=\"variable\">query: </span>${query}</li></ul></li>";
            }
            echo '</ul>';
        }


        echo "<br /><b class=\"cab\">DB_HOST:</b> " . DATABASE['host'];
        echo "<br /><b class=\"cab\">DB_NAME:</b> " . DATABASE['name'] .  "<br />";
        echo "<br /><b class=\"cab\">Firewall:</b> " . (FIREWALL ? "True" : "False");
        echo "<br /><b class=\"cab\">Total time:</b> " . ($endtime - $startime) . " seconds" ;
        echo "<br /><b class=\"cab\">RAM consumed:</b> ${memory}";
    }

    /**
      * End debug mode
      *
      * @return void
      */
    final public function __destruct()
    {
        echo self::FOOT;
    }
}
