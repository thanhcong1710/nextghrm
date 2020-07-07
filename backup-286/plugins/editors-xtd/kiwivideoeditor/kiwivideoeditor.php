<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.kiwivideoeditor
 *
 * @copyright   Copyright (C) 2005 - 2015 NextG-ERP, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Editor Article buton
 *
 * @since  3.0
 */
class PlgButtonKiwiVideoEditor extends JPlugin {

        private $_alias = 'kiwivideoeditor';
        private $_init = false;
        private $_helper = null;

        /**
         * Load the language file on instantiation.
         *
         * @var    boolean
         * @since  3.1
         */
        protected $autoloadLanguage = true;

        /**
         * Display the button
         *
         * @param   string  $name  The name of the button to add
         *
         * @return array A four element array of (article_id, article_title, category_id, object)
         */
        public function onDisplay($name) {
                $js = "
		function videoselect(id, title, catid, object, link, lang)
		{
			var hreflang = '';
			if (lang !== '')
			{
				var hreflang = ' hreflang = \"' + lang + '\"';
			}
			var tag = '{video=' + id + '}';
			jInsertEditorText(tag, '" . $name . "');
			jModalClose();
		}";

                $doc = JFactory::getDocument();
                $doc->addScriptDeclaration($js);

                JHtml::_('behavior.modal');

                /*
                 * Use the built-in element view to select the article.
                 * Currently uses blank class.
                 */
                $link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1&function=videoselect';

                $button = new JObject;
                $button->modal = true;
                $button->class = 'btn';
                $button->link = $link;
                $button->text = JText::_('PLG_ARTICLE_BUTTON_KIWIVIDEO');
                $button->name = 'file-add';
                $button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";
                return $button;
        }

}
