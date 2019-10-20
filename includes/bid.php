<?php
    require_once 'common.php';

    function addBid() {
        
    }

    function deleteBid($userId, $course, $section) {
        $roundDAO = new RoundDAO();
        $bidDAO = new BidDAO();

        if ($roundDAO->roundIsActive()) {
            if ($bidDAO->refundbidamount($user['userid'], $code, $section)) {
                
            }
            else {
                
                // "invalid course"	Course code does not exist in the system's records
                // "invalid userid"	userid does not exist in the system's records
                // "invalid section"	No such section ID exists for the particular course. Only check if course is valid
                // "round ended"	The current bidding round has already ended.
                // "no such bid"	No such bid exists in the system's records. Check only if there is an (1) active bidding round, and (2) course, userid and section are valid and (3)the round is currently active.
            }
        }
    }