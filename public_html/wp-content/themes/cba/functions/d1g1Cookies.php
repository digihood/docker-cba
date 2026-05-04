<?php
/**
 * PHP Cookie manipulation cclass.
 *
 * @author   Malik Umer Farooq <lablnet01@gmail.com>
 * @author-profile https://www.facebook.com/malikumerfarooq01/
 *
 * @license MIT
 *
 * @link    https://github.com/Lablnet/PHP-Cookie-manipulation-Class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'd1g1Cookies' ) )
{
    class d1g1Cookies
    {

        private static $path = '/'; // path of cookie

        /**
         * __Construct set the default values.
         *
         * @return void
         */
        public function __construct()
        {
        }

        /**
         * Set the cookie value.
         *
         * @param
         * $name of cookie
         * $value of cookie
         * $expire of cookie
         * $domain of cookie
         * $secure of cookie
         * $httponly of cookie
         *
         * @return bool
         */
        public static function set_cookie($params) {
            if (!is_array($params)) return false;
            $name = self::set_cookie_name($params);
            $expire = self::set_cookie_expire($params);
            $value = (valid_key_in_array($params, 'value') ? $params['value'] : '');
            $path = (valid_key_in_array($params, 'path') ? $params['path'] : self::$path);
            if (!$name || !$expire) return false;

            setcookie($name,  $value, $expire , $path /*, $this->domain,  $this->httponly*/);
            return true;
        }
        // vrátí cookie name na základě zaslaných dat
        private static function set_cookie_name($params) {
            if (!valid_key_in_array($params, 'name') ) return '';
            
            return $params['name'];  
        }

        // vrátí cookie expire na základě zaslaných dat
        private static function set_cookie_expire($params) {
            if (!valid_key_in_array($params, 'expire') ) return '';

            if ($params['expire'] instanceof \DateTime) {
                $expire = $params['expire']->format('U');
            } elseif (!is_numeric($params['expire'])) {
                $expire = strtotime($params['expire']);
            } else {
                $expire = $params['expire'];
            }
            return $expire;  
        }

        /**
         * Check if cookie set or not STATIC.
         *
         * @param  $name of cookie
         *
         * @return bool
         */
        public static function is_cookie($name)
        {
            if (!isset($_COOKIE[$name]) || empty($_COOKIE[$name])) return false;
            return true;
        }

        /**
         * Get the cookie value STATIC.
         *
         * @param  $name of cookie
         *
         * @return string | boolean
         */
        public static function get_cookie($name)
        {
            if ( !isset($_COOKIE[$name])) return false;
            return $_COOKIE[$name];
        }

        /**
         * Delete the cookie.
         *
         * @param  $name of cookie
         *
         * @return bool
         */
        public static function delete_cookie($name) {
            if (!self::is_cookie($name)) return false;
            $value = self::get_cookie($name);
            setcookie($name, $value, time() - 3600, self::$path );
            return true;
        }
    }
}