<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
  + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  May 22, 2015
  ^
  + Project:    JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="panel-group" id="accordion2">
        <?php
        $emailLink = (isset($this->email)) ? '&email=' . $this->email : '';
        $menus = array();
        $menus[0] = [
            'title' => JText::_('Open'),
            'link' => JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $emailLink . '&lt=1'),
            'count' => (isset($this->ticketinfo)) ? $this->ticketinfo['open'] : 0,
            'lt' => 1,
            'icon' => 'fa fa-unlock'
        ];
        $menus[1] = [
            'title' => JText::_('Closed'),
            'link' => JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $emailLink . '&lt=4'),
            'count' => (isset($this->ticketinfo)) ? $this->ticketinfo['close'] : 0,
            'lt' => 4,
            'icon' => 'fa fa-lock'
        ];
        $menus[2] = [
            'title' => JText::_('Answered'),
            'link' => JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $emailLink . '&lt=3'),
            'count' => (isset($this->ticketinfo)) ? $this->ticketinfo['isanswered'] : 0,
            'lt' => 3,
            'icon' => 'fa fa-check-square-o'
        ];
        $menus[3] = [
            'title' => JText::_('My Tickets'),
            'link' => JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=mytickets' . $emailLink . '&lt=5'),
            'count' => (isset($this->ticketinfo)) ? $this->ticketinfo['mytickets'] : 0,
            'lt' => 5,
            'icon' => 'fa fa-ticket'
        ];
        $menus[4] = [
            'title' => JText::_('Create Ticket'),
            'link' => JRoute::_('index.php?option=com_jssupportticket&c=ticket&layout=formticket' . $emailLink . '&lt=0'),
            'count' => 0,
            'lt' => 0,
            'icon' => 'fa fa-ticket'
        ];
        ?>
        <?php foreach ($menus as $key => $menu) : ?>
                <?php
                $active = (isset($this->lt) && $this->lt == $menu['lt']) ? ' active' : '';
                ?>
                <div class="panel panel-default">
                        <div id="collapse<?php echo $key; ?>" class="panel-collapse ishead">
                                <div class="panel-body<?php echo $active; ?>">
                                        <a href="<?php echo $menu['link'] ?>">
                                                <i class="<?php echo $menu['icon'] ?>"></i>&nbsp;
                                                <?php
                                                echo $menu['title'];
                                                if ($this->config['show_count_tickets'] == 1 && $menu['count']) {
                                                        echo "&nbsp;(" . $menu['count'] . ")";
                                                }
                                                ?>
                                        </a>
                                </div>
                        </div>
                </div>
        <?php endforeach; ?>
</div>