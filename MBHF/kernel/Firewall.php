<?php

/**
 * MBHFramework
 *
 * @link      https://github.com/MBHFramework/mbh-framework
 * @copyright Copyright (c) 2017 Ulises Jeremias Cornejo Fandos
 * @license   https://github.com/MBHFramework/mbh-framework/blob/master/LICENSE (MIT License)
 */

# Security
defined('INDEX_DIR') or exit(APP_NAME . 'software says .i.');

namespace MBHF;

/**
 * created by Ulises Jeremias Cornejo Fandos
 */
final class Firewall
{
  const FCONF = [
    'WEBMASTER_EMAIL' => 'ulisescf.24@gmail.com',
    'PUSH_MAIL' => false,
    'LOG_FILE' => '__logs__',
    'PROTECTION_UNSET_GLOBALS' => true,
    'PROTECTION_RANGE_IP_DENY' => false,
    'PROTECTION_RANGE_IP_SPAM' => false,
    'PROTECTION_URL' => true,
    'PROTECTION_REQUEST_SERVER' => true,
    'PROTECTION_BOTS' => true,
    'PROTECTION_REQUEST_METHOD' => true,
    'PROTECTION_DOS' => true,
    'PROTECTION_UNION_SQL' => true,
    'PROTECTION_CLICK_ATTACK' => true,
    'PROTECTION_XSS_ATTACK' => true,
    'PROTECTION_COOKIES' => true,
    'PROTECTION_COOKIES_LOGS' => true,
    'PROTECTION_POST' => true,
    'PROTECTION_POST_LOGS' => true,
    'PROTECTION_GET' => true,
    'PROTECTION_GET_LOGS' => true,
    'PROTECTION_SERVER_OVH' => true,
    'PROTECTION_SERVER_KIMSUFI' => true,
    'PROTECTION_SERVER_DEDIBOX' => true,
    'PROTECTION_SERVER_DIGICUBE' => true,
    'PROTECTION_SERVER_OVH_BY_IP' => true,
    'PROTECTION_SERVER_KIMSUFI_BY_IP' => true,
    'PROTECTION_SERVER_DEDIBOX_BY_IP' => true,
    'PROTECTION_SERVER_DIGICUBE_BY_IP' => true,
    'PROTECTION_ROUTER_STRICT' => false
  ];

  /*
     List of blocked IP's. If the part of the IP of your server is here, you must remove it.
     To know the IP of your server, just do from any terminal "ping -a yourwebsite.com"
  */
  const IPLIST = [
    # If the first two digits of your IP match one of these, delete it.
    'SERVER_OVH_BY_IP' => ['87.98','91.121','94.23','213.186','213.251'],
    'DEDIBOX_BY_IP' => '88.191',
    'DIGICUBE_BY_IP' => '95.130',
    # If the first digit of your IP matches one of these, delete it.
    'RANGE_IP_DENY' => ['0', '1', '2', '5', '10', '14', '23', '27', '31', '36', '37', '39', '42', '46',
    '49', '50', '100', '101', '102', '103', '104', '105', '106', '107', '114', '172', '176', '177', '179',
    '181', '185', '192', '223', '224'],
    'RANGE_IP_SPAM' => ['24', '186', '189', '190', '200', '201', '202', '209', '212', '213', '217', '222']
  ];

  /**
    * Heal global variables by removing PHP and HTML from their content.
    *
    * @param string $s: index of the variable to heal
    *
    * @return retorna $r healed
  */
  final private function getEnv(string $s)
  {
    if(isset($_SERVER[$s])) {
      return strip_tags($_SERVER[$s]);
    } else if(isset($_ENV[$s])) {
      return strip_tags($_ENV[$s]);
    } else if(getenv($s)) {
      return strip_tags(getenv($s));
    } else if(function_exists('apache_getenv') and apache_getenv($s, true)) {
      return strip_tags(apache_getenv($s, true));
    }
    return '';
  }

  /**
    * Gets address of the page that the user agent uses for the current page
    *
    * @return $_SERVER['HTTP_REFERER'] healed
  */
  private function getReferer()
  {
    return $this->get_env('HTTP_REFERER');
	}

  /**
   * Gets ip
   *
   * @return ip
   */
  private function getIp()
  {
    if ($this->getEnv('HTTP_X_FORWARDED_FOR') ) {
      return $this->getEnv('HTTP_X_FORWARDED_FOR');
    } elseif ($this->getEnv('HTTP_CLIENT_IP')) {
      return $this->getEnv('HTTP_CLIENT_IP');
    }
    return $this->getEnv('REMOTE_ADDR');
  }

  /**
    * Gets user agent
    *
    * @return devuelve el agente de usuario
  */
  private function getUserAgent() {
		if($this->getEnv('HTTP_USER_AGENT')) {
      return $this->getEnv('HTTP_USER_AGENT');
    }
		return '-';
	}

  /**
    * Gets the request of the page request
    *
    * @return query of the request
  */
  private function getQueryString() {
    if(self::FCONF['PROTECTION_ROUTER_STRICT']) {
      return str_replace('%09', '%20', $_SERVER['REQUEST_URI']);
    }
    if($this->getEnv('QUERY_STRING')) {
      return str_replace('%09', '%20', $this->getEnv('QUERY_STRING'));
    }
    return '';
	}

  /**
    * 'GET', 'HEAD', 'POST', 'PUT'.
    *
    * @return get the request method.
  */
  private function getRequestMethod() {
    return $this->getEnv('REQUEST_METHOD');
  }

  /**
    * Gets Internet host name
    *
    * @return devuelve el host de Internet según la IP actual
  */
  private function getHostByAddr() {
    if(self::FCONF['PROTECTION_SERVER_OVH'] or self::FCONF['PROTECTION_SERVER_KIMSUFI'] or self::FCONF['PROTECTION_SERVER_DEDIBOX'] or self::FCONF['PROTECTION_SERVER_DIGICUBE']) {
      if(!isset($_SESSION['app_firewall_gethostbyaddr']) or empty($_SESSION['app_firewall_gethostbyaddr'])) {
        $_SESSION['app_firewall_gethostbyaddr'] = gethostbyaddr($this->getIp());
      }
      return strip_tags($_SESSION['app_firewall_gethostbyaddr']);
    }
    return '';
  }


  function __construct()
  {
    # code...
  }
}
