<?php

/**
 * @package pkg_nextgcyber NextgCyber for Joomla
 * @subpackage com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber. All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 */
defined('_JEXEC') or die;
JForm::addFieldPath(JPATH_THEMES . '/kiwierp/html/joomla/forms/fields');
JHtml::addIncludePath(JPATH_THEMES . '/kiwierp/html/joomla/jhtml');
// Set labelclass
foreach ($this->form->getFieldset() as $field):
        if ($field->type == "Calendar") {
                $this->form->setFieldAttribute($field->fieldname, 'labelclass', 'col-sm-3 control-label');
                $this->form->setFieldAttribute($field->fieldname, 'class', 'form-control');
                $this->form->setFieldAttribute($field->fieldname, 'type', 'Calendar2');
        }
endforeach;

include_once JPATH_COMPONENT . '/views/affiliateform/tmpl/default_item.php';
?>
