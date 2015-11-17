<?php
/**
 * Copyright 2005-2011 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

require_once "../require.php";
require_once $centreon_path . 'www/class/centreon.class.php';
require_once $centreon_path . 'www/class/centreonSession.class.php';
require_once $centreon_path . 'www/class/centreonDB.class.php';
require_once $centreon_path . 'www/class/centreonWidget.class.php';

session_start();
if (!isset($_SESSION['centreon']) || !isset($_REQUEST['widgetId'])) {
    exit;
}
$centreon = $_SESSION['centreon'];
$widgetId = $_REQUEST['widgetId'];

try {
    $db = new CentreonDB();
    $widgetObj = new CentreonWidget($centreon, $db);
    $preferences = $widgetObj->getWidgetPreferences($widgetId);
    $autoRefresh = 0;
    if (isset($preferences['refresh_interval'])) {
        $autoRefresh = $preferences['refresh_interval'];
    }
    $broker = "broker";
    $res = $db->query("SELECT `value` FROM `options` WHERE `key` = 'broker'");
    if ($res->numRows()) {
        $row = $res->fetchRow();
        $broker = strtolower($row['value']);
    } else {
        throw new Exception('Unknown broker module');
    }
} catch (Exception $e) {
    echo $e->getMessage() . "<br/>";
    exit;
}
?>
<html>
    <head>
    	<title>Host Monitoring</title>
	<link href="<?php echo '../../Themes/Centreon-2/Color/blue_css.php';?>" rel="stylesheet" type="text/css"/>
        <link href="../../Themes/Centreon-2/style.css" type="text/css" rel="styleheet"/>    	
       <script type="text/javascript" src="../../include/common/javascript/jquery/jquery.js"></script>
    	<script type="text/javascript" src="../../include/common/javascript/jquery/jquery-ui.js"></script>
    	<script type="text/javascript" src="../../include/common/javascript/widgetUtils.js"></script>
	   <style type="text/css">
	   .ListTable{font-size:11px;border-color: #BFD0E2;}
	   </style>
    </head>
    <body>
    <div id='actionBar' style='width:100%;'>
	<span id='toolBar' style='float:left;width:30%;'></span>
	<span id='pagination' class='pagination' style='float:left;width:45%;text-align:center;'> </span>
        <span id='nbRows' style='float:left;width:24%;text-align:right;font-weight:bold;'></span>
    </div><br/><br/>
        <div id='eventLogsTable'></div>
    </body>
<script type="text/javascript">
var widgetId = <?php echo $widgetId; ?>;
var autoRefresh = <?php echo $autoRefresh;?>;
var timeout;
var itemsPerPage = <?php echo $preferences['entries'];?>;
var pageNumber = 0;
var clickedCb = new Array();
var broker = '<?php echo $broker;?>';

jQuery(function() {
    loadPage();
});

/**
 * Load Page
 */
function loadPage() {
    var indexPage = "index";
    jQuery.ajax("./src/"+indexPage+".php?widgetId="+widgetId+"&page="+pageNumber, {        
        success : function(htmlData) {
            jQuery("#eventLogsTable").html("");
            jQuery("#eventLogsTable").html(htmlData);
            var h = document.getElementById("eventLogsTable").scrollHeight + 30;
            parent.iResize(window.name, h);
        }
    });
    if (autoRefresh) {
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(loadPage, (autoRefresh * 1000));
    }
}

</script>
</html>
