<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JFormHelper::loadFieldClass('list');

// The class name must always be the same as the filename (in camel case)
class JFormFieldArticles extends JFormFieldList {

    //The field class must know its own type through the variable $type.
    protected $type = 'Articles';

    /**
     * Cached array of the items.
     *
     * @var    array
     * @since  1.0
     */
    protected static $options = array();

    public function getOptions() {
        // Accepted modifiers
        $hash = md5($this->element);

        if (!isset(static::$options[$hash])) {
            static::$options[$hash] = parent::getOptions();

            $options = array();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            // Construct the query
            $query->select('a.id AS value');
            $query->select('(' . $query->concatenate(['p.title', 'a.title'], ' - ') . ') AS text');
            $query->leftJoin('#__categories AS p ON p.id = a.catid');
            $query->from('#__content AS a');
            // Setup the query
            $db->setQuery($query);
            $options = $db->loadObjectList();
            // Return the result
            if ($options) {
                static::$options[$hash] = array_merge(static::$options[$hash], $options);
            }
        }

        return static::$options[$hash];
    }

}
