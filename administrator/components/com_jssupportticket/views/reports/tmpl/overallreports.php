<?php
/**
 * @Copyright Copyright (C) 2015 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 22, 2015
  ^
  + Project: 	JS Tickets
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawBarChart);
    function drawBarChart() {
        var data = google.visualization.arrayToDataTable([
         ['<?php echo JText::_('Status'); ?>', '<?php echo JText::_('Tickets by status'); ?>', { role: 'style' }],
         <?php echo $this->result['bar_chart']; ?>
      ]);
     var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "Density of Precious Metals, in g/cm^3",
        width: '95%',
        bar: {groupWidth: "95%"},        
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("bar_chart"));
      chart.draw(view, options);        
    }

    google.setOnLoadCallback(drawPie3d1Chart);
    function drawPie3d1Chart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo JText::_('Departments'); ?>', '<?php echo JText::_('Tickets by Departments'); ?>'],
          <?php echo $this->result['pie3d_chart1']; ?>
        ]);

        var options = {
          title: '<?php echo JText::_('Ticket by Departments'); ?>',
          chartArea :{width:450,height:350,top:80,left:80},
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart1'));
        chart.draw(data, options);
    }   
    
    google.setOnLoadCallback(drawPie3d2Chart);
    function drawPie3d2Chart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo JText::_('Priorities'); ?>', '<?php echo JText::_('Tickets by Priority'); ?>'],
          <?php echo $this->result['pie3d_chart2']; ?>
        ]);

        var options = {
          title: '<?php echo JText::_('Tickets by Priorities'); ?>',
          chartArea :{width:450,height:350,top:80,left:80},
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart2'));
        chart.draw(data, options);
    }   
    google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
        <?php
            echo $this->result['stack_chart_horizontal']['title'].',';
            echo $this->result['stack_chart_horizontal']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("stack_chart_horizontal"));
      chart.draw(view, options);
    }
</script>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-leftmenu">
        <?php include_once('components/com_jssupportticket/views/menu.php'); ?>
    </div>
    <div id="js-tk-cparea">
        <div id="js-tk-heading"><h4><img id="js-admin-responsive-menu-link" src="components/com_jssupportticket/include/images/c_p/left-icons/menu.png" /><?php echo JText::_('Overall Reports'); ?></h4></div> 
        <div id="bar_chart" style="height:500px;width:100%; "></div>
        <div class="js-col-md-6">
            <span class="js-admin-subtitle box1"><?php echo JText::_('Ticket By Departments'); ?></span>
            <div id="pie3d_chart1" style="height:400px;width:100%;"></div>
        </div>
        <div class="js-col-md-6">
            <span class="js-admin-subtitle box2"><?php echo JText::_('Tickets By Priorities'); ?></span>
            <div id="pie3d_chart2" style="height:400px;width:100%;"></div>
        </div>
        <div class="js-col-md-12">
            <span class="js-admin-subtitle box3"><?php echo JText::_('Tickets By Status And Priorities'); ?></span>
            <div id="stack_chart_horizontal" style="height:400px;width:100%;"></div>
        </div>
    </div>
</div>
<div id="js-tk-copyright">
    <img width="85" src="http://www.joomsky.com/logo/jssupportticket_logo_small.png">&nbsp;Powered by <a target="_blank" href="http://www.joomsky.com">Joom Sky</a><br/>
    &copy;Copyright 2008 - <?php echo date('Y'); ?>, <a target="_blank" href="http://www.burujsolutions.com">Buruj Solutions</a>
</div>
