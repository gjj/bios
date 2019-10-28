<?php

class RoundClearingDAO {
    public function roundClearing($round) {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        if ($round == 1) {
            $result = [];
            
            $sql = "SELECT DISTINCT course, section FROM bids WHERE round = :round AND result = '-'";

            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':round', $round, PDO::PARAM_STR);
            $query->execute();
            $courseSections = $query->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($courseSections as $courseSection) {
                $resultCourseSection = array (
                    "course" => $courseSection['course'],
                    "section" => $courseSection['section'],
                    "in" => 0,
                    "out" => 0
                );

                $sql = "SELECT * FROM bids WHERE round = :round AND result = '-' AND course = :courseCode AND section = :section ORDER BY course, section, amount DESC";
                $query = $db->prepare($sql);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->bindParam(':round', $round, PDO::PARAM_STR);
                $query->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                $query->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                $query->execute();
                $bids = $query->fetchAll(PDO::FETCH_ASSOC);
                
                $numberOfBids = $query->rowCount();

                $sql2 = "SELECT size FROM sections WHERE course = :courseCode AND section = :section";
                $query2 = $db->prepare($sql2);
                $query2->setFetchMode(PDO::FETCH_ASSOC);
                $query2->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                $query2->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                $query2->execute();
                $size = $query2->fetch(PDO::FETCH_ASSOC);
                
                // If no. of bids > size of class
                if ($numberOfBids >= $size['size']) {
                    // Derive the minimum clearing price based on the number of vacancies, (i.e. if the class has 35 vacancies,
                    // the 35th highest bid is the clearing price.)
                    // There is a clearing price only if there are at least n or more bids for a particular section, where n is the number of vacancies.
                    $clearingPrice = $bids[$size['size']-1]['amount'];

                    // If there is only one bid at the clearing price, it will be successful.
                    // Otherwise, all bids at the clearing price will be dropped regardless of
                    // whether they can technically all be accommodated (refer to example).
                    $bidsCount = array_count_values(array_column($bids, 'amount'));

                    $bidsAtClearingPrice = $bidsCount[$clearingPrice];
                    
                    // Find the rank of the FIRST bid with the clearing price.
                    // $rankOfClearingPrice = array_search($clearingPrice, array_column($bids, 'amount'));

                    if ($bidsAtClearingPrice > 1) {
                        // amount > $clearingPrice would exclude clearingPrice. Since all bids at clearing price will be dropped.
                        $sql3 = "UPDATE bids SET result = 'in' WHERE course = :courseCode AND section = :section AND round = :round AND amount > :clearingPrice;";
                        $query3 = $db->prepare($sql3);
                        $query3->setFetchMode(PDO::FETCH_ASSOC);
                        $query3->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query3->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query3->bindParam(':round', $round, PDO::PARAM_STR);
                        $query3->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query3->execute();
                        $resultCourseSection["in"] = $query3->rowCount();

                        $sql4 = "UPDATE bids SET result = 'out' WHERE course = :courseCode AND section = :section AND round = :round AND amount <= :clearingPrice;";
                        $query4 = $db->prepare($sql4);
                        $query4->setFetchMode(PDO::FETCH_ASSOC);
                        $query4->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query4->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query4->bindParam(':round', $round, PDO::PARAM_STR);
                        $query4->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query4->execute();
                        $resultCourseSection["out"] = $query4->rowCount();

                        // Refund only for those who didn't get it.
                        $sql5 = "SELECT user_id, amount FROM bids WHERE course = :courseCode AND section = :section AND round = :round AND amount <= :clearingPrice;";
                        $query5 = $db->prepare($sql5);
                        $query5->setFetchMode(PDO::FETCH_ASSOC);
                        $query5->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query5->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query5->bindParam(':round', $round, PDO::PARAM_STR);
                        $query5->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query5->execute();
                        $refundList = $query5->fetchAll(PDO::FETCH_ASSOC);
                    }
                    else {
                        // amount > $clearingPrice would exclude clearingPrice. Since all bids at clearing price will be dropped.
                        $sql3 = "UPDATE bids SET result = 'in' WHERE course = :courseCode AND section = :section AND round = :round AND amount >= :clearingPrice;";
                        $query3 = $db->prepare($sql3);
                        $query3->setFetchMode(PDO::FETCH_ASSOC);
                        $query3->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query3->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query3->bindParam(':round', $round, PDO::PARAM_STR);
                        $query3->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query3->execute();
                        $resultCourseSection["in"] = $query3->rowCount();

                        $sql4 = "UPDATE bids SET result = 'out' WHERE course = :courseCode AND section = :section AND round = :round AND amount < :clearingPrice;";
                        $query4 = $db->prepare($sql4);
                        $query4->setFetchMode(PDO::FETCH_ASSOC);
                        $query4->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query4->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query4->bindParam(':round', $round, PDO::PARAM_STR);
                        $query4->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query4->execute();
                        $resultCourseSection["out"] = $query4->rowCount();

                        // Refund only for those who didn't get it.
                        $sql5 = "SELECT user_id, amount FROM bids WHERE course = :courseCode AND section = :section AND round = :round AND amount < :clearingPrice;";
                        $query5 = $db->prepare($sql5);
                        $query5->setFetchMode(PDO::FETCH_ASSOC);
                        $query5->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                        $query5->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                        $query5->bindParam(':round', $round, PDO::PARAM_STR);
                        $query5->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                        $query5->execute();
                        $refundList = $query5->fetchAll(PDO::FETCH_ASSOC);
                        
                    }

                    // Refund only for those who didn't get it.
                    $sql6 = "";
                    foreach ($refundList as $refund) {
                        $sql6 .= "UPDATE users SET edollar = edollar + {$refund['amount']} WHERE user_id = '{$refund['user_id']}';";
                    }

                    // Fixed bug on 22 Oct
                    if ($refundList) {
                        $db->exec($sql6);
                    }
                    
                    
                }
                else {
                    $minPrice = min(array_column($bids, 'amount'));

                    $sql3 = "UPDATE bids SET result = 'in' WHERE course = :courseCode AND section = :section AND round = :round AND amount >= :minPrice;";
                    $query3 = $db->prepare($sql3);
                    $query3->setFetchMode(PDO::FETCH_ASSOC);
                    $query3->bindParam(':courseCode', $courseSection['course'], PDO::PARAM_STR);
                    $query3->bindParam(':section', $courseSection['section'], PDO::PARAM_STR);
                    $query3->bindParam(':round', $round, PDO::PARAM_STR);
                    $query3->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
                    $query3->execute();
                    
                    $resultCourseSection["in"] = $query3->rowCount();
                }

                array_push($result, $resultCourseSection);
            }


            // Clear all cart items.
            $sql = "DELETE FROM bids WHERE round = :round AND result = 'cart'";
            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':round', $round, PDO::PARAM_STR);
            $query->execute();

            return $result;
        }
        else {
            // round 2. think should be refund only?

            $sql = "SELECT * FROM bids WHERE round = :round AND result = 'out' ORDER BY course, section, amount DESC";
            $query = $db->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->bindParam(':round', $round, PDO::PARAM_STR);
            $query->execute();
            $refundList = $query->fetchAll(PDO::FETCH_ASSOC);

            $sql2 = "";
            foreach ($refundList as $refund) {
                $sql2 .= "UPDATE users SET edollar = edollar + {$refund['amount']} WHERE user_id = '{$refund['user_id']}';";
            }
            
            if ($refundList) {
                $db->exec($sql2);
            }
        }
    }

    // only used in round 2
    public function clearCourseSection($courseCode, $section) {
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $bidDAO = new BidDAO();
        $roundDAO = new RoundDAO();

        $allSuccessfulBids = $bidDAO->getSuccessfulByCourseCode($courseCode, $section, 1);

        $size = $bidDAO->getCourseByCodeAndSection($courseCode, $section)['size'];

        // Total Available Seats: Number of seats available for this section at the start of the round,
        // after accounting for the number of seats successfully taken up during the first round.
        // Number of seats could be updated depending if any students dropped a section that he/she bid for
        // successfully during the first round.
        $vacancy = (int)$size - (int)$allSuccessfulBids;
        
        $sql = "SELECT * FROM bids WHERE round = 2 AND result IN ('-', 'in', 'out') AND course = :courseCode AND section = :section ORDER BY amount DESC";
        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();
        $bids = $query->fetchAll(PDO::FETCH_ASSOC);
                
        $numberOfBids = $query->rowCount();

        // After each bid, do the following processing to re-compute the minimum bid value:

        // Case 1: If there are less than N bids for the section (where N is the total available seats),
        // the Current Vacancies are (N - number of bids). The minimum bid value remains the same as there
        // are still unfilled vacancies in this section that students can bid for using the minimum bid value.
        if ($numberOfBids < $vacancy) {
            $minBid = 10;
            $bidDAO->insertOrUpdateMinBid($courseCode, $section, $minBid);

            $sql2 = "UPDATE bids SET result = 'in' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount >= :clearingPrice;";
            $query2 = $db->prepare($sql2);
            $query2->setFetchMode(PDO::FETCH_ASSOC);
            $query2->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $query2->bindParam(':section', $section, PDO::PARAM_STR);
            $query2->bindParam(':clearingPrice', $minBid, PDO::PARAM_STR);
            $query2->execute();
        }
        else {
            // Case 2: If there are N or more bids for the section,
            $clearingPrice = $bids[$vacancy-1]['amount'];

            // the minimum bid value is 1 dollar more than the Nth bid.
            $minBid = $clearingPrice + 1;

            $bidDAO->insertOrUpdateMinBid($courseCode, $section, $minBid);

            $bidsCount = array_count_values(array_column($bids, 'amount'));
            $bidsAtClearingPrice = $bidsCount[$clearingPrice];

           

            // If there are other bids with the same bid price as the Nth bid
            // and the class is unable to accommodate all of them, all bids with the same price
            // will be unsuccessful.
            if ($bidsAtClearingPrice > 1) {
                // [CANNOT USE THIS] amount > $clearingPrice would exclude clearingPrice. Since all bids at clearing price will be dropped.
                
                $vacancy - $bidsAtClearingPrice; // 10-2
                $numberOfBids; // 15

                // $clearing = 18
                // $bidsAtClearing = 2
                // vacancy = 3
                // bids = 5
                // bids[]

                // check if can accommodate!
                // $bids = [20, 19, 18, 18, 17];
                // index:  [0,  1,  2,  3,  4]; i.e. if $bids[3-1] == $bids[3] would mean cannot accommodate!

                error_log(print_r($bids, true)."\n", 3, "bios.log");
                error_log($bids[$vacancy-1]['amount']."\n", 3, "bios.log");
                error_log($bids[$vacancy]['amount']. "\n", 3, "bios.log");

                if ((count($bids) > $vacancy) and ($bids[$vacancy-1]['amount'] == $bids[$vacancy]['amount'])) {
                    // Cannot accommodate

                    error_log("cannot accommodate"."\n", 3, "bios.log");

                    $sql3 = "UPDATE bids SET result = 'in' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount > :clearingPrice;";
                    $query3 = $db->prepare($sql3);
                    $query3->setFetchMode(PDO::FETCH_ASSOC);
                    $query3->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                    $query3->bindParam(':section', $section, PDO::PARAM_STR);
                    $query3->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                    $query3->execute();

                    $sql4 = "UPDATE bids SET result = 'out' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount <= :clearingPrice;";
                    $query4 = $db->prepare($sql4);
                    $query4->setFetchMode(PDO::FETCH_ASSOC);
                    $query4->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                    $query4->bindParam(':section', $section, PDO::PARAM_STR);
                    $query4->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                    $query4->execute();
                }
                else {
                    // Can accommodate!

                    $sql3 = "UPDATE bids SET result = 'in' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount >= :clearingPrice;";
                    $query3 = $db->prepare($sql3);
                    $query3->setFetchMode(PDO::FETCH_ASSOC);
                    $query3->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                    $query3->bindParam(':section', $section, PDO::PARAM_STR);
                    $query3->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                    $query3->execute();

                    $sql4 = "UPDATE bids SET result = 'out' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount < :clearingPrice;";
                    $query4 = $db->prepare($sql4);
                    $query4->setFetchMode(PDO::FETCH_ASSOC);
                    $query4->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                    $query4->bindParam(':section', $section, PDO::PARAM_STR);
                    $query4->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                    $query4->execute();
                }
            }
            else {
                $sql3 = "UPDATE bids SET result = 'in' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount >= :clearingPrice;";
                $query3 = $db->prepare($sql3);
                $query3->setFetchMode(PDO::FETCH_ASSOC);
                $query3->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                $query3->bindParam(':section', $section, PDO::PARAM_STR);
                $query3->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                $query3->execute();
                
                $sql4 = "UPDATE bids SET result = 'out' WHERE result NOT IN ('cart') AND course = :courseCode AND section = :section AND round = 2 AND amount < :clearingPrice;";
                $query4 = $db->prepare($sql4);
                $query4->setFetchMode(PDO::FETCH_ASSOC);
                $query4->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
                $query4->bindParam(':section', $section, PDO::PARAM_STR);
                $query4->bindParam(':clearingPrice', $clearingPrice, PDO::PARAM_STR);
                $query4->execute();
            }
        }
    }
}