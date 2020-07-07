<?php

/**
 * @package mod_kiwicreateinstance
 * @subpackage  mod_kiwicreateinstance
 *
 * @copyright Copyright (C) 2015 NextG-ERP. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE . '/components/com_content/helpers/route.php';

class ModKiwiCreateInstanceHelper {

        public static function getAjax() {
                // Get module parameters
                jimport('joomla.application.module.helper');

                return array();
        }

        public static function encodeString($string) {
                $str = preg_replace('/\s/u', '%20', $string);
                return htmlspecialchars(htmlspecialchars_decode($str));
        }

        /**
         * Method add fb image to header
         * @param string $html
         * @param object $params
         * @return void
         * @since 1.1
         */
        public static function addFacebookImage($html, $params = null) {
                $reg = '/<img [^<>]*src=[\\"\']?([^\\"\']+\.(png|jpg|gif))[\\"\']?/i';
                $tags = [];
                if (preg_match_all($reg, $html, $image)) {
                        //add found image
                        if (array_key_exists(1, $image)) {
                                $img_urls = $image[1];
                                foreach ($img_urls as $img_url) {
                                        //add scheme to image URL if missing
                                        $scheme = strtolower(substr($img_url, 0, 7));
                                        if ($scheme != 'http://' AND $scheme != 'https:/' AND strpos($scheme, '//') !== 0) {
                                                $img_url = ltrim($img_url, '/');
                                                $tags[] = array('property' => 'og:image', 'content' => JUri::root() . static::encodeString($img_url));
                                        } else {
                                                $tags[] = array('property' => 'og:image', 'content' => static::encodeString($img_url));
                                        }
                                }
                        }
                }

                $background = $params->get('background');
                if ($background) {
                        $tags[] = array('property' => 'og:image', 'content' => JUri::root() . static::encodeString($background));
                }

                if (!empty($tags)) {
                        $doc = JFactory::getDocument();
                        foreach ($tags as $tag) {
                                $doc->addCustomTag('<meta property="' . $tag['property'] . '" content="' . $tag['content'] . '"/>');
                        }
                }
        }

}
