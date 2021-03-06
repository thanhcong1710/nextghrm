<?php

/**
 * @version 2.0.20
 * @package PWebFBLinkArticleImages
 * @copyright © 2014 Perfect Web sp. z o.o., All rights reserved. http://www.perfect-web.co
 * @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
 * @author Piotr Moćko
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class plgContentFbLinkContentImage extends JPlugin {

        protected $images = 0;
        protected $min_score = -1;
        protected $tags = array();
        protected $debug = false;
        protected $debug_images = array();
        protected $regExps = array('/<img [^<>]*src=[\\"\']?([^\\"\']+\.(png|jpg|gif))[\\"\']?/i');

        protected function _addOpenGraphTags($title = '') {
                if (!defined('PLG_PWEB_FBARTICLEIMAGES_OG')) {
                        define('PLG_PWEB_FBARTICLEIMAGES_OG', 1);

                        $cfg = JFactory::getConfig();

                        if ($this->params->get('fb_appid')) {
                                $this->tags['appid'] = array('property' => 'fb:app_id', 'content' => $this->params->get('fb_appid'));
                        }
                        if ($this->params->get('fb_admins')) {
                                $this->tags['admins'] = array('property' => 'fb:admins', 'content' => $this->params->get('fb_admins'));
                        }

                        if ($this->params->get('og_details', 1)) {
                                $this->tags['title'] = array('property' => 'og:title', 'content' => htmlentities($title ? $title : $cfg->get('sitename'), ENT_QUOTES, 'UTF-8'));
                                $this->tags['type'] = array('property' => 'og:type', 'content' => 'article');
                                $this->tags['url'] = array('property' => 'og:url', 'content' => JUri::getInstance()->toString());
                                $this->tags['site_name'] = array('property' => 'og:site_name', 'content' => $cfg->get('sitename'));
                        }
                }
        }

        protected function _addOpenGraphArticleImages($text = '') {
                //add only one image found in first article on current page
                if (preg_match_all($this->regExp, $text, $image)) {
                        //add found image
                        if (array_key_exists(1, $image)) {
                                $img_urls = $image[1];
                                $i = 0;
                                foreach ($img_urls as $img_url) {
                                        if ($i > 3) {
                                                break;
                                        }
                                        //add scheme to image URL if missing
                                        $scheme = strtolower(substr($img_url, 0, 7));
                                        if (!JFile::exists($img_url)) {
                                                continue;
                                        }
                                        list($width, $height, $type, $attr) = getimagesize(JUri::root() . $img_url);
                                        $ratio = $width / $height;
                                        if ($ratio > 3 || $width < 300 || $ratio < 1) {
                                                continue;
                                        }

                                        if ($scheme != 'http://' AND $scheme != 'https:/' AND strpos($scheme, '//') !== 0) {
                                                $img_url = ltrim($img_url, '/');
                                                $this->images++;
                                                $i++;
                                                $this->tags[] = array('property' => 'og:image', 'content' => JUri::root() . $this->_encodeURIComponent($img_url));
                                        } else {
                                                $this->images++;
                                                $i++;
                                                $this->tags[] = array('property' => 'og:image', 'content' => $this->_encodeURIComponent($img_url));
                                        }
                                }
                                return;
                        }
                }

                //add default image if current page is article and no image was found
                if (!$this->images)
                        $this->_addOpenGraphDefaultImage();
        }

        protected function _addOpenGraphDefaultImage() {
                if (!defined('PLG_PWEB_FBARTICLEIMAGES_DEFAULT') AND $image_default = $this->params->get('image_default')) {
                        define('PLG_PWEB_FBARTICLEIMAGES_DEFAULT', 1);

                        $this->images++;
                        $this->tags['image'] = array('property' => 'og:image', 'content' => JUri::root() . $this->_encodeURIComponent($image_default));
                }
        }

        protected function _addCustomTags() {
                $doc = JFactory::getDocument();
                //$doc->addCustomTag('<!-- Perfect Link with Article Images on Facebook -->');
                foreach ($this->tags as $tag)
                        $doc->addCustomTag('<meta property="' . $tag['property'] . '" content="' . $tag['content'] . '"/>');

                if ($this->debug)
                        $this->_displayDebug();
        }

        protected function _encodeURIComponent($str) {
                $str = preg_replace('/\s/u', '%20', $str);
                return htmlspecialchars(htmlspecialchars_decode($str));
        }

        protected function _displayDebug() {
                $app = JFactory::getApplication();

                if (!defined('PLG_PWEB_FBARTICLEIMAGES_DEBUG')) {
                        define('PLG_PWEB_FBARTICLEIMAGES_DEBUG', 1);
                        $app->enqueueMessage(JText::_('PLG_PWEB_FBARTICLEIMAGES_DEBUG_OUTPUT'), 'message');
                        $app->enqueueMessage(
                                '<a href="https://developers.facebook.com/tools/debug/og/object?q='
                                . rawurlencode(isset($this->tags['url']) ? $this->tags['url']['content'] : JUri::getInstance()->toString())
                                . '" target="_blank">' . JText::_('PLG_PWEB_FBARTICLEIMAGES_DEBUG_FB_DEBUGGER') . '</a>'
                                , 'message');

                        JHtml::_('behavior.modal');
                }

                $message = '';
                foreach ($this->tags as $tag) {
                        if ($tag['property'] == 'og:image')
                                $tag['content'] = '<a href="' . $tag['content'] . '" class="modal">' . str_replace(JUri::root(), '', $tag['content']) . '</a>';

                        $message .= '<strong>' . $tag['property'] . '</strong> - ' . $tag['content'] . '<br/>';
                }

                if ($message)
                        $app->enqueueMessage($message, 'message');
        }

        protected function _contentPrepare(&$article) {
                if (!defined('PLG_PWEB_FBARTICLEIMAGES')) {
                        $app = JFactory::getApplication();

                        $view = $app->input->getCmd('view');
                        $show = (int) $this->params->get('add_og_tags');
                        if ($show > 0 AND $view == 'article') {
                                $this->tags = array();
                                $this->debug = ((int) $this->params->get('debug') OR $app->input->getInt('debug'));
                                if ($this->debug)
                                        $this->loadLanguage();

                                // Select regular expression
                                $this->regExp = $this->regExps[0];

                                // add default OpenGraph tags
                                $this->_addOpenGraphTags($article->title);

                                $this->_addOpenGraphArticleImages($article->text);
                                define('PLG_PWEB_FBARTICLEIMAGES', 1);

                                $this->_addCustomTags();
                        }
                }
        }

        public function onContentPrepare($context, &$article, &$params, $offset = 0) {
                if (in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured')) AND $this->params->get('og_content_prepare') == 1) {
                        $this->_contentPrepare($article);
                }
        }

        public function onContentAfterTitle($context, &$article, &$params, $offset = 0) {
                $app = JFactory::getApplication();
                $view = $app->input->getCmd('view');

                if (in_array($context, array('com_content.article', 'com_content.category', 'com_content.featured')) AND ( !$this->params->get('og_content_prepare') OR $view == 'category' OR $view == 'featured')) {
                        $this->_contentPrepare($article);
                }
        }

}
