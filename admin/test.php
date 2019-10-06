<?php
    $start = "8:45";
    $end = "11:45";
    
    if ($end == date("G:i", strtotime($end))) {
        echo "yes";
    }