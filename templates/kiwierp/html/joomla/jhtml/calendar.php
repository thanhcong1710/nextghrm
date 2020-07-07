<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Content Component HTML Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_content
 * @since       1.5
 */
abstract class JHtmlCalendar extends JHtml
{

        /**
         * Displays a calendar control field
         *
         * @param   string  $value    The date value
         * @param   string  $name     The name of the text field
         * @param   string  $id       The id of the text field
         * @param   string  $format   The date format
         * @param   mixed   $attribs  Additional HTML attributes
         *
         * @return  string  HTML markup for a calendar field
         *
         * @since   1.5
         */
        public static function calendar($value, $name, $id, $format = '%Y-%m-%d', $attribs = null)
        {
                static $done;

                if ($done === null)
                {
                        $done = array();
                }

                $readonly = isset($attribs['readonly']) && $attribs['readonly'] == 'readonly';
                $disabled = isset($attribs['disabled']) && $attribs['disabled'] == 'disabled';

                if (is_array($attribs))
                {
                        $attribs['class'] = isset($attribs['class']) ? $attribs['class'] : 'input-medium';
                        $attribs['class'] = trim($attribs['class'] . ' hasTooltip');

                        $attribs = JArrayHelper::toString($attribs);
                }

                static::_('bootstrap.tooltip');

                // Format value when not '0000-00-00 00:00:00', otherwise blank it as it would result in 1970-01-01.
                if ((int) $value)
                {
                        $tz = date_default_timezone_get();
                        date_default_timezone_set('UTC');
                        $inputvalue = strftime($format, strtotime($value));
                        date_default_timezone_set($tz);
                } else
                {
                        $inputvalue = '';
                }

                // Load the calendar behavior
                static::_('behavior.calendar');

                // Only display the triggers once for each control.
                if (!in_array($id, $done))
                {
                        $document = JFactory::getDocument();
                        $document
                                ->addScriptDeclaration(
                                        'jQuery(document).ready(function($) {Calendar.setup({
			// Id of the input field
			inputField: "' . $id . '",
			// Format of the input field
			ifFormat: "' . $format . '",
			// Trigger for the calendar (button ID)
			button: "' . $id . '_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: ' . JFactory::getLanguage()->getFirstDay() . '
			});});'
                        );
                        $done[] = $id;
                }

                // Hide button using inline styles for readonly/disabled fields
                $btn_style = ($readonly || $disabled) ? ' style="display:none;"' : '';
                $div_class = (!$readonly && !$disabled) ? ' class="input-group"' : '';

                return '<div' . $div_class . '>'
                        . '<input type="text" title="' . (0 !== (int) $value ? static::_('date', $value, null, null) : '')
                        . '" name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($inputvalue, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />'
                        . '<div class="input-group-btn"><a class="btn btn-default" id="' . $id . '_img"' . $btn_style . '><i class="fa fa-calendar"></i></a></div>'
                        . '</div>';
        }

}
