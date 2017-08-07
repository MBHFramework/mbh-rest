<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Federico Ramón Gasquez
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */


namespace MBHF;

final class debug
{

 //------------------------------------------------

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

 //------------------------------------------------

 /**
  * List an array, displaying its contents
  *
  * @param array $VAR: Array to display
  * @param string $variable: Written form of the variable being broken down
  *
  * @return void
*/
  final private function list_var(array $VAR, string $variable ){
    echo '<strong class="cab">',$variable,':</strong> <br />';
    echo '<ul>';
    foreach ($VAR as $key => $value) {
      if($key != '___QUERY_DEBUG___'){
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

    //------------------------------------------------

    # End of Speed test
    $endtime = explode(" ",microtime());
    $endtime = $endtime[0] + $endtime[1];
    $memory = Func::convert(memory_get_usage());

    //------------------------------------------------

    echo self::HEAD;
    echo "<b class='cab'>File:</b> {$_SERVER['PHP_SELF']}<br />";
    echo "<b class='cab'>PHP:</b> {$phpversion()} <br />";
    echo "<b class='cab'>FRAMEWORK:</b> ${VERSION} , '<br />";
    echo "<b class='cab'>Template Engine:</b> {$template_engine[(int) TWIG_TEMPLATE_ENGINE]} <br />";
    //------------------------------------------------

    //------------------------------------------------
  if(isset($_SESSION)) {
    $this->listVar($_SESSION,'$_SESSION');
  } else {
    echo 'Sin variables <span class="variable">$_SESSION</span><br />';
  }
  if($_POST) {
    $this->listVar($_POST,'$_POST');
  } else {
    echo 'Sin variables <span class="variable">$_POST</span><br />';
  }
  if($_GET) {
    $this->listVar($_GET,'$_GET');
  } else {
    echo 'Sin variables <span class="variable">$_GET</span><br />';
  }
  if($_FILES) {
    $this->listVar($_FILES,'$_FILES');
  } else {
    echo 'Sin variables <span class="variable">$_FILES</span><br />';
  }

  //------------------------------------------------

  if(isset($_SESSION['___QUERY_DEBUG___']) and sizeof($_SESSION['___QUERY_DEBUG___']) > 0) {
    echo '<br /><strong class="cab">QUERYS:</strong><br />';
    echo '<ul style="list-style:none;padding:0;">';
    foreach ($_SESSION['___QUERY_DEBUG___'] as $query) {
      echo "<li><ul><li><span class='variable'>query: </span>${query}</li></ul></li>";
    }
    echo '</ul>';
  }

  //------------------------------------------------

  echo "<br /><b class='cab'>DB_HOST:</b> {$DATABASE['host']}";
  echo "<br /><b class='cab'>DB_NAME:</b> {$DATABASE['name']} <br />";
  echo "<br /><b class='cab'>Firewall:</b> ${FIREWALL}" ? 'True' : 'False';
  echo "<br /><b class='cab'>Total time :</b> ,{$endtime - $startime}, segundos" ;
  echo "<br /><b class='cab'>RAM consumida por cada usuario:</b> ${memory}";

  //------------------------------------------------

  /**
    * End debug mode
    *
    * @return void
  */
  final public function __destruct() {
    echo self::FOOT;
  }
}
