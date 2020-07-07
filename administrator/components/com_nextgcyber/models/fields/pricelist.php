<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JFormHelper::loadFieldClass('list');
JLoader::register('NextgCyberOdooDB', JPATH_ADMINISTRATOR . '/components/com_nextgcyber/helpers/odoodb.php');

// The class name must always be the same as the filename (in camel case)
class JFormFieldPricelist extends JFormFieldList {

    //The field class must know its own type through the variable $type.
    protected $type = 'Pricelist';

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

            $db = new NextgCyberOdooDB();
            $query = $db->getQuery(true);
            // Construct the query
            $query->select('id,name');
            $query->from('product.pricelist');
            $query->where('type = sale');
            // Setup the query
            $db->setQuery($query);
            $options = $db->loadObjectList();
            $formated = array();
            foreach ($options as $key => $value) {
                $item = new stdClass;
                $item->value = $value->id;
                $item->text = $value->name;
                $formated[] = $item;
            }

            // Return the result
            if ($formated) {
                static::$options[$hash] = array_merge(static::$options[$hash], $formated);
            }
        }

        return static::$options[$hash];
    }

}
