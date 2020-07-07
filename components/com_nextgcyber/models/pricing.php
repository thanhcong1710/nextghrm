<?php

/**
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @copyright Copyright (C) 2015 NextgCyber . All rights reserved.
 * @licence http://www.gnu.org/licences GNU/GPL version 2 or later; see LICENCE.txt
 * @author url: http://nextgerp.com
 * @author Daniel.Vu
 */
defined('_JEXEC') or die;

/**
 * This models supports retrieving lists of article plans.
 *
 * @package pkg_nextgcyber
 * @subpackage  com_nextgcyber
 * @since       1.0
 */
JLoader::register('NextgCyberIPHelper', JPATH_BASE . '/components/com_nextgcyber/helpers/iphelper.php');
JLoader::register('NextgCyberModelBaseList', JPATH_COMPONENT_ADMINISTRATOR . '/models/baselist.php');
JLoader::register('ContentHelperRoute', JPATH_BASE . '/components/com_content/helpers/route.php');

class NextgCyberModelPricing extends NextgCyberModelBaseList {

    protected $odoo_model = 'product.product';

    /**
     *
     * @var type
     */
    private $_items = null;

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.0
     */
    protected function populateState($ordering = null, $direction = null) {
        parent::populateState($ordering, $direction);
        $app = JFactory::getApplication();
        // List state information
        $this->setState('list.start', 0);
        $this->setState('list.limit', 0);
        $this->setState('filter.id', null);
        $params = $app->getParams();
        $this->setState('params', $params);
        $this->setState('filter.published', 1);
    }

    public function clearState() {
        $this->getState();
        $this->setState('filter.published', null);
        $this->setState('filter.search', null);
        $this->setState('filter.nc_type', null);
        $this->setState('filter.nc_module_id', null);
        $this->setState('filter.not', null);
        $this->setState('list.limit', 0);
        $this->setState('list.start', 0);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id	A prefix for the store id.
     *
     * @return  string  A store id.
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.published');
        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.0
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getOdooQuery(true);
        // Select the required fields from the table.
        $query->select('id, name, display_name, image, nc_type, type, price, lst_price, nc_module_type, nc_module_id, nc_module_parent_ids, nc_module_child_ids, nc_partner_price, taxes_id');
        $query->from($this->odoo_model);
        
        //$query->leftJoin('');
        
        // Filter by active state
        $query->where('active = 1');
        $query->where(array('nc_type', '!=', 'normal'));

        $nc_type = $this->getState('filter.nc_type');
        if ($nc_type) {
            $query->where(array('nc_type', '=', $nc_type));
        }

        $nc_module_id = $this->getState('filter.nc_module_id');
//        # only display module for hrm
//        $db2 = $this->getDbo();
//        $query2 = $this->getOdooQuery(true);
//        // Select the required fields from the table.
//        $query2->select('id');
//        $query2->from('ir.module.module');
//        $onlyModule = ['hr_vn', 'hr_vn_accident', 'hr_vn_business_trip', 
//            'hr_vn_contract', 'hr_vn_insurance', 'hr_vn_laudatory', 'hr_vn_payroll', 
//            'hr_vn_salary_advance', 'hr_vn_salary_payment', 'hr_vn_staffing',
//            'hr_vn_timesheet', 'hr_vn_training', 'hr_vn_training'
//            ];
//        $query2->where('name' , 'in', $onlyModule);
//        $db2->setQuery($query2);
//        $nc_module_id = $db2->loadAssocList('id');
        
        if (!empty($nc_module_id)) {
            if (is_array($nc_module_id)) {
                $query->where(array('nc_module_id', 'in', $nc_module_id));
            } elseif (is_numeric($nc_module_id)) {
                $query->where(array('nc_module_id', '=', $nc_module_id));
            }
        }

        $not = $this->getState('filter.not');
        if (!empty($not)) {
            if (is_array($not)) {
                $query->where(array('id', 'not in', $not));
            } elseif (is_numeric($not)) {
                $query->where(array('id', '!=', $not));
            }
        }
        
        

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('id = ' . (int) substr($search, 3));
            } elseif (stripos($search, 'name:') === 0) {
                $substr = substr($search, 5);
                $query->where('name = ' . $substr);
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('name like ' . $search);
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'asc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        return $query;
    }

    /**
     * Method to get a list of articles.
     *
     * @return  mixed  An array of objects on success, false on failure.
     */
    public function getItems() {
        if (!empty($this->_items)) {
            return $this->_items;
        }
        $this->_items = parent::getItems();
        foreach ($this->_items as &$item) {
            $item->content = $this->getContent($item->id);
            $item->taxes = $this->getTaxes($item->taxes_id);
            $item->in_tax = 0;
            $item->out_tax = 0;
            $item->out_tax_name = '';
            if (NextgCyberIPHelper::useTax()) {
                foreach ($item->taxes as $tax) {
                    if ($tax->price_include) {
                        $item->in_tax += $tax->amount;
                    } else {
                        $item->out_tax += $tax->amount;
                        $item->out_tax_name = $tax->name;
                    }
                }
            }
        }
        unset($item);
        return $this->_items;
    }

    protected function getContent($product_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.content_id AS id, c.catid')
                ->from('#__nextgcyber_product_content_rel AS a')
                ->leftJoin('#__content AS c ON c.id = a.content_id')
                ->select('c.alias, c.language')
                ->where('a.product_id = ' . (int) $product_id);
        $db->setQuery($query);
        $content = $db->loadObject();
        if (!empty($content)) {
            $content->slug = $content->id . ':' . $content->alias;
            $content->link = JRoute::_(ContentHelperRoute::getArticleRoute($content->slug, $content->catid, $content->language));
        }
        return $content;
    }

    /**
     * Method to get all taxes of product
     * @param array $tax_ids
     * @return array
     * @since 1.0
     * @author Daniel.Vu
     */
    protected function getTaxes($tax_ids) {
        $db = $this->getOdooDB();
        $query = $db->getQuery(true);
        $query->select('id, name, amount, price_include');
        $query->from('account.tax');
        $query->where(array('id', 'in', $tax_ids));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

}
