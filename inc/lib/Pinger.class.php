<?php if(!defined('GX_LIB')) die("Direct Access Not Allowed!");
/**
* GeniXCMS - Content Management System
* 
* PHP Based Content Management System and Framework
*
* @package GeniXCMS
* @since 0.0.1 build date 20150202
* @version 0.0.8
* @link https://github.com/semplon/GeniXCMS
* @link http://genixcms.org
* @author Puguh Wijayanto (www.metalgenix.com)
* @copyright 2014-2016 Puguh Wijayanto
* @license http://www.opensource.org/licenses/mit-license.php MIT
*
*/


class Pinger
{

    private static $myBlogName;
    private static $myBlogUrl;
    private static $myBlogUpdateUrl;
    private static $myBlogRSSFeedUrl;


    public function __construct () {
        self::$myBlogName          = Options::v('sitename');
        self::$myBlogUrl           = Options::v('siteurl');
        self::$myBlogUpdateUrl     = Options::v('siteurl');
        self::$myBlogRSSFeedUrl    = Url::rss();
    }

    public static function rpc ($url) {
        new Pinger();
        //require_once( GX_LIB.'/Vendor/IXR_Library.php' );
        $url = 'http://'.$url;
        $client = new IXR_Client( $url );
        $client->timeout = 3;
        $client->useragent .= ' -- PingTool/1.0.0';
        $client->debug = false;
        if( $client->query( 'weblogUpdates.extendedPing', self::$myBlogName, self::$myBlogUrl, self::$myBlogUpdateUrl, self::$myBlogRSSFeedUrl ) )
        {
            return $client->getResponse();
        }
        //echo 'Failed extended XML-RPC ping for "' . $url . '": ' . $client->getErrorCode() . '->' . $client->getErrorMessage() . '<br />';
        if( $client->query( 'weblogUpdates.ping', self::$myBlogName, self::$myBlogUrl ) )
        {
            return $client->getResponse();
        }
        //echo 'Failed basic XML-RPC ping for "' . $url . '": ' . $client->getErrorCode() . '->' . $client->getErrorMessage() . '<br />';
        return false;
    }

    public static function run ($vars) {
        if( is_array($vars) ) {
            foreach ( $vars as $v ) {
                # code...
                self::rpc($v);
            }
        }else{
            $pinger = str_replace("\r\n", "\n", $vars);
            $pinger = explode("\n", $pinger);
            $pinger = str_replace("\r\n", "", $pinger);
            $pinger = str_replace("{{domain}}", Options::v('sitedomain'), $pinger);
            foreach ($pinger as $p) {
                # code...
                self::rpc($p);
                //echo "'$p'<br>";
            }
        }
    }
}