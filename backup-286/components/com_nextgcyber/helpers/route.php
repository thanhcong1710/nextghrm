<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://domain.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * NextgCyber Component Route Helper
 * @static
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
abstract class NextgCyberHelperRoute {

    protected static $lookup = array();
    protected static $lang_lookup = array();
    protected static $route = array();

    /**
     * Method get link to dashboard page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getDashboardRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=dashboard';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to pricing page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getPricingRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=pricing';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to trial page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getTryingRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=trial';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to instance list page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getInstancesRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=instances';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to instance detail page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getInstanceRoute($instance_id, $language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=instance&id=' . $instance_id;
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();
            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }
        $urls = ['index.php?option=com_nextgcyber&view=instances', 'index.php?option=com_nextgcyber&view=dashboard'];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to order list page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getOrdersRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=orders';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to order detail page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getOrderRoute($id, $language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=order&id=' . $id;
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = ['index.php?option=com_nextgcyber&view=orders', 'index.php?option=com_nextgcyber&view=dashboard'];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to invoice list page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getInvoicesRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=invoices';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get register payment link
     * @param string $language
     * @return string
     * @since 1.1
     */
    public static function getRegisterPaymentRoute($invoice_id, $language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&task=invoice.registerpayment&id=' . $invoice_id;
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get cancel order link
     * @param string $return
     * @return string
     * @since 1.1
     */
    public static function getCancelOrderRoute($order_id, $return = null) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&task=order.cancel&id=' . $order_id;
        if ($return) {
            $link.= '&return=' . $return;
        }
        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get confirm order link
     * @param string $language
     * @return string
     * @since 1.1
     */
    public static function getConfirmOrderRoute($order_id, $language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&task=order.confirm&id=' . $order_id;
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to invoice detail page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getInvoiceRoute($id, $language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=invoice&id=' . $id;
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = ['index.php?option=com_nextgcyber&view=invoices', 'index.php?option=com_nextgcyber&view=dashboard'];
        return static::prepareUrl($link, $urls, $needles);
    }

    /**
     * Method get link to profile page
     * @param string $language
     * @return string
     * @since 1.0
     */
    public static function getProfileRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&view=profile';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();

            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }

        $urls = ['index.php?option=com_nextgcyber&view=dashboard'];
        return static::prepareUrl($link, $urls, $needles);
    }

    protected static function buildLanguageLookup() {
        if (count(self::$lang_lookup) == 0) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                    ->select('a.sef AS sef')
                    ->select('a.lang_code AS lang_code')
                    ->from('#__languages AS a');

            $db->setQuery($query);
            $langs = $db->loadObjectList();

            foreach ($langs as $lang) {
                self::$lang_lookup[$lang->lang_code] = $lang->sef;
            }
        }
    }

    protected static function _findItem($needles = null) {
        $app = JFactory::getApplication();
        $menus = $app->getMenu('site');
        $language = isset($needles['language']) ? $needles['language'] : '*';

        // Prepare the reverse lookup array.
        if (!isset(self::$lookup[$language])) {
            self::$lookup[$language] = array();

            $component = JComponentHelper::getComponent('com_nextgcyber');

            $attributes = array('component_id');
            $values = array($component->id);

            if ($language != '*') {
                $attributes[] = 'language';
                $values[] = array($needles['language'], '*');
            }

            $items = $menus->getItems($attributes, $values);

            foreach ($items as $item) {
                if (isset($item->query) && isset($item->query['view'])) {
                    $view = $item->query['view'];

                    if (!isset(self::$lookup[$language][$view])) {
                        self::$lookup[$language][$view] = array();
                    }

                    if (isset($item->query['id'])) {
                        /**
                         * Here it will become a bit tricky
                         * language != * can override existing entries
                         * language == * cannot override existing entries
                         */
                        if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*') {
                            self::$lookup[$language][$view][$item->query['id']] = $item->id;
                        }
                    }
                }
            }
        }

        if ($needles) {
            foreach ($needles as $view => $ids) {
                if (isset(self::$lookup[$language][$view])) {
                    foreach ($ids as $id) {
                        if (isset(self::$lookup[$language][$view][(int) $id])) {
                            return self::$lookup[$language][$view][(int) $id];
                        }
                    }
                }
            }
        }

        // Check if the active menuitem matches the requested language
        $active = $menus->getActive();

        if ($active && $active->component == 'com_nextgcyber' && ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled())) {
            return $active->id;
        }

        // If not found, return language specific home link
        $default = $menus->getDefault($language);

        return !empty($default->id) ? $default->id : null;
    }

    /**
     * Method remove item id in link. alllow gateway can acccess url
     * @param string $url
     * @return string
     * @since 1.0
     */
    public static function removeItemId($url) {
        return str_replace('Itemid', 'Itemids', $url);
    }

    /**
     * Method auto find item id for link
     * @param string $link e.g. index.php?option=com_nextgcyber
     * @param array $urls array url e.g. ['index.php?option=com_nextgcyber&view=xxx', 'index.php?option=com_nextgcyber&view=xxx']
     * @param array $needles
     * @return string
     * @since 1.0
     */
    protected static function prepareUrl($link, $urls = array(), $needles = array()) {
        $hash = md5($link);
        if (!isset(self::$route[$hash])) {
            $menu = JFactory::getApplication()->getMenu();
            // Get itemid if exist
            $item = $menu->getItems('link', $link, true);
            if (isset($item->id)) {
                self::$route[$hash] = $link . '&Itemid=' . $item->id;
                return self::$route[$hash];
            }

            if (!empty($urls)) {
                foreach ($urls as $url) {
                    $item = $menu->getItems('link', $url, true);
                    if (isset($item->id)) {
                        self::$route[$hash] = $link . '&Itemid=' . $item->id;
                        return self::$route[$hash];
                    }
                }
            }

            if ($item = self::_findItem($needles)) {
                self::$route[$hash] = $link .= '&Itemid=' . $item;
            }
            self::$route[$hash] = $link;
        }
        return self::$route[$hash];
    }

    /**
     * Method get confirm order link
     * @param string $language
     * @return string
     * @since 1.1
     */
    public static function getConfirmPromotionRoute($language = 0) {
        $needles = array();
        $link = 'index.php?option=com_nextgcyber&task=form.confirmPromotion';
        if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
            self::buildLanguageLookup();
            if (isset(self::$lang_lookup[$language])) {
                $link .= '&lang=' . self::$lang_lookup[$language];
                $needles['language'] = $language;
            }
        }
        $urls = [];
        return static::prepareUrl($link, $urls, $needles);
    }

}
