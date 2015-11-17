<?php

function getTypeLabels() {
    return array(
        0 => "SOFT",
        1 => "HARD"
    );
}

function getStatusLabels($object = 'host') {
    $statusLabels = array();
    if ($object == 'host') {
        $statusLabels = array(
            0 => "Up",
            1 => "Down",
            2 => "Unreachable",
            3 => "Pending"
        );
    } elseif ($object == 'service') {
        $statusLabels = array(
            0 => "Ok",
            1 => "Warning",
            2 => "Critical",
            3 => "Unknown",
            4 => "Pending"
        );
    }

    return $statusLabels;
}

function getStatusColors($db, $object = 'host') {
    $statusHColors = array(
        0 => "#13EB3A",
        1 => "#F91D05",
        2 => "#DCDADA",
        3 => "#2AD1D4"
    );
    $statusSColors = array(
        0 => "#13EB3A",
        1 => "#F8C706",
        2 => "#F91D05",
        3 => "#DCDADA",
        4 => "#2AD1D4"
    );
    $statusINColors = array(
        -1 => "#00bfb3",
    );

    $res = $db->query("SELECT `key`, `value` FROM `options` WHERE `key` LIKE 'color%'");
    while ($row = $res->fetchRow()) {
        if ($row['key'] == "color_ok") {
            $statusSColors[0] = $row['value'];
        } elseif ($row['key'] == "color_warning") {
            $statusSColors[1] = $row['value'];
        } elseif ($row['key'] == "color_critical") {
            $statusSColors[2] = $row['value'];
        } elseif ($row['key'] == "color_unknown") {
            $statusSColors[3] = $row['value'];
        } elseif ($row['key'] == "color_pending") {
            $statusSColors[4] = $row['value'];
        } elseif ($row['key'] == "color_up") {
            $statusHColors[4] = $row['value'];
        } elseif ($row['key'] == "color_down") {
            $statusHColors[4] = $row['value'];
        } elseif ($row['key'] == "color_unreachable") {
            $statusHColors[4] = $row['value'];
        }
    }

    $statusColors = array();
    if ($object == 'host') {
        $statusColors = $statusHColors;
    } elseif ($object == 'service') {
        $statusColors = $statusSColors;
    } elseif ($object == 'info') {
        $statusColors = $statusINColors;
    }

    return $statusColors;
}

