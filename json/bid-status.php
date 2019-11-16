***REMOVED***
require_once '../includes/common.php';

header("Content-Type: application/json");
$errors = [
    isMissingOrEmpty('r'),
    isMissingOrEmpty('token'), // Check which layer token validation is at!!
];

$errors = array_filter($errors);

// If pass first layer of validations i.e. r and token exists
if (!$errors) {
    $request = $_GET['r'];
    $token = $_GET['token'];

    $json = json_decode($request, true);

    $errors = [
        isMissingOrEmptyJson('course', $json),
        isMissingOrEmptyJson('section', $json),
    ];

    $errors = array_filter($errors);

    // If pass common validation...
    if (!$errors) {
        $course = $json['course'];
        $section = $json['section'];

        $courseDAO = new CourseDAO();
        $sectionDAO = new SectionDAO();

        if (!$courseDAO->retrieveByCode($course)) {
            $errors[] = "invalid course";
    ***REMOVED*** else if (!$sectionDAO->retrieveByCodeAndSection($course, $section)) {
            $errors[] = "invalid section";
    ***REMOVED***
***REMOVED***

    // If pass input validation...
    if (!$errors) {
        $roundDAO = new RoundDAO();
        $bidDAO = new BidDAO();
        $currentRound = $roundDAO->getCurrentRound()['round'];
        $size = $bidDAO->getCourseByCodeAndSection($course, $section)['size'];

        if (!$errors) {
            // During Round 1
            if ($currentRound == 1 and $roundDAO->roundIsActive()) {
                $bids = $bidDAO->retrieveAllBidsBySection($course, $section);
                $numberOfBids = count($bids);

                // Vacancy: the total available seats as all the bids are still pending.
                $vacancy = $size;
                
                // Minimum bid price: when #bid is less than the #vacancy, report the lowest bid amount.
                // Otherwise, set the price as the clearing price.
                // When there is no bid made, the minimum bid price will be 10.0 dollars.
                if ($numberOfBids == 0) {
                    $minbid = 10.0;
            ***REMOVED*** elseif ($numberOfBids < $vacancy) {
                    // Case of n = 0 has to be before this as min() function will throw an error if $bids is empty!
                    $minbid = (float)min(array_column($bids, 'amount'));
            ***REMOVED*** elseif ($numberOfBids >= $vacancy) {

                    // Derive the minimum clearing price based on the number of vacancies,
                    // (i.e. if the class has 35 vacancies, the 35th highest bid is the clearing price.)
                    // There is a clearing price only if there are at least n or more bids for a particular
                    // section, where n is the number of vacancies.
                    // (added note: don't need care if can accommodate or not)
                    $minbid = $bids[$vacancy-1]['amount'];
            ***REMOVED***
        ***REMOVED***
            // After Round 1 Ended
            elseif ($currentRound == 1 and !$roundDAO->roundIsActive()) {
                // Vacancy: (the total available seats) - (number of successful bid during round 1).
                $numberOfSuccessfulBids = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $vacancy = $size - $numberOfSuccessfulBids;

                // Minimum bid price: report the lowest successful bid.
                // If there was no bid made (or no successful bid) during round 1,
                // the value will be 10.0 dollars.
                if ($numberOfSuccessfulBids == 0) {
                    $minbid = 10.0;
            ***REMOVED*** else {
                    $minbid = (float)$bidDAO->getSuccessfulMinBidAmount($course, $section, 1)['amount'];
            ***REMOVED***
        ***REMOVED***
            // During Round 2
            elseif ($currentRound == 2 and $roundDAO->roundIsActive()) {
                /*$round1 = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $round2 = $bidDAO->getSuccessfulByCourseCode($course, $section, 2);
                $vacancy = $size - ($round1 + $round2);*/

                // Existing number of bids in round 2.
                $bidsInRound2 = count($bidDAO->getBidsInRound2($course, $section));

                $numberOfSuccessfulBids = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $vacancy = $size - $numberOfSuccessfulBids; // Remaining vacancies after round 1.
                
                $minbid = $bidDAO->getMinBid($course, $section);

                if ($minbid) {
                    $minbid = $minbid['bidAmount'];
            ***REMOVED***
                else {
                    $minbid = 10.0;
            ***REMOVED***

                /*if ($bidsInRound2 >= $vacancy) {
                    // More Bids than Vacancies
                    $minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
            ***REMOVED*** else {
                    $minbid = 10.0;
            ***REMOVED****/
        ***REMOVED***

            // After Round 2 Ended
            else {
                // Vacancy: (the total available seats) - (number of successfully enrolled students in round 1 and 2).
                $round1 = $bidDAO->getSuccessfulByCourseCode($course, $section, 1);
                $round2 = $bidDAO->getSuccessfulByCourseCode($course, $section, 2);
                $vacancy = $size - ($round1 + $round2);

                $bidsInRound2 = $bidDAO->getSuccessfulBidsInRound2($course, $section);

                if ($round2 > 0) {
                    //$minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
                    $minbid = (float)min(array_column($bidsInRound2, 'amount'));
            ***REMOVED*** else {
                    $minbid = 10.0;
            ***REMOVED***
        ***REMOVED***

            $reports = $bidDAO->retrieveBidsReport($course, $section);
    ***REMOVED***
***REMOVED***
}
if (!$errors) {
    $result = [
        "status" => "success",
        "vacancy" => $vacancy,
        "min-bid-amount" => (float)$minbid,
        "students" => $reports
    ];
} else {
    $result = [
        "status" => "error",
        "message" => array_values($errors)
    ];
}
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);
