<?php

/**

 * @package nextgcyber NextgCyber for Joomla

 * @subpackage com_nextgcyber

 * @copyright Copyright (C) 2016 Daniel.Vu . All rights reserved.

 * @license http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENSE.txt

 * @author url: http://nextgcyber.com

 * @author Daniel.Vu

 */
// No direct access

defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldSubQuestion extends JFormFieldList {

    //The field class must know its own type through the variable $type.

    protected $type = 'SubQuestion';

    protected function getInput() {

        $html = array();

        $html['html'] = array();

        $attr = '';

        // Initialize some field attributes.

        $attr .= $this->element['class'] ? ' class="' . $this->id . ' groupparent ' . (string) $this->element['class'] . '"' : '';

        // To avoid user's confusion, readonly="true" should imply disabled="true".

        if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {

            $attr .= ' disabled="disabled"';
        }

        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

        $attr .= $this->multiple ? ' multiple="multiple"' : '';

        $attr .= $this->required ? ' required="required" aria-required="true"' : '';

        // Initialize JavaScript field attributes.

        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        $attr .= $this->element['view'] ? ' view="' . (string) $this->element['view'] . '"' : '';

        $attr .= $this->element['list'] ? ' list="' . (string) $this->element['list'] . '"' : '';

        // Build the script.

        $script = '
                jQuery(document).ready(function(){
                        jQuery(".' . $this->fieldname . 'item-list-add").click(function(){
                                var button = jQuery(this);
                                var nextkey = button.attr("nextkey");
                                var group = jQuery( ".' . $this->fieldname . 'item-list-group:eq(0)" ).clone();
                                var title = group.find(".' . $this->fieldname . 'item-list-title");
                                var id = group.find(".' . $this->fieldname . 'item-list-id");
                                title.attr("name", "' . $this->formControl . '[' . $this->fieldname . ']["+ nextkey +"][title]");
                                title.attr("value", "");
                                id.attr("name", "' . $this->formControl . '[' . $this->fieldname . ']["+ nextkey +"][id]");
                                id.attr("value", 0);
                                jQuery("#' . $this->fieldname . 'item-list-list").append(group);
                                nextkey = parseInt(nextkey) + 1;
                                button.attr("nextkey", nextkey);
                        });
                });';

        $html = $this->_showHtml();

        // Add the script to the document head.

        JFactory::getDocument()->addScriptDeclaration($script);

        return implode($html['html']);
    }

    private function _showHtml() {

        $html[] = '<div id="' . $this->fieldname . 'item-list-list">';

        $nextKey = 0;

        if (count($this->value) && is_array($this->value)) {

            foreach ($this->value as $key => $value) {

                $html[] = $this->_displayItem($value, $key);

                $nextKey = $key + 1;
            }
        } else {
            $valueItem = new stdClass();
            $valueItem->id = 0;
            $valueItem->title = '';
            $html[] = $this->_displayItem($valueItem, 0);

            $nextKey = 1;
        }

        $html[] = '</div>';

        $html[] = '<a class="btn ' . $this->fieldname . 'item-list-add" nextkey="' . $nextKey . '">' . JText::_('COM_NEXTGCYBER_ADD_ITEM_BUTTON') . '</a>';

        $htmlValue = array('html' => $html);

        return $htmlValue;
    }

    private function _displayItem($value, $key, $readonly = false) {

        if (is_array($value)) {

            $value = JArrayHelper::toObject($value);
        }

        $readonly = $readonly ? ' readonly' : '';
        return '<div class="' . $this->fieldname . 'item-list-group" style="margin-bottom:5px;">'
                . '<input type="text" name="' . $this->name . '[' . $key . '][title]" id="' . $this->id . '" class="inputbox ' . $this->fieldname . 'item-list-title" value="' . $value->title . '" ' . $readonly . '/>'
                . '&nbsp;<input type="hidden" class="' . $this->fieldname . 'item-list-id" name="' . $this->name . '[' . $key . '][id]" value="' . $value->id . '">'
                . '</div>';
    }

}
