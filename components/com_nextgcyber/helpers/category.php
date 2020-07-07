<?php

defined('_JEXEC') or die;
jimport('joomla.application.categories');

class NextgCyberCategories extends JCategories {

        public function __construct($options = array()) {
                $options['table'] = '#__content';
                $options['extension'] = 'com_nextgcyber';
                parent::__construct($options);
        }

}
