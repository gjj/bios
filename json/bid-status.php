***REMOVED***
require_once '../includes/common.php';

header("Content-Type: application/json");
$errors = [
    isMissingOrEmpty('r'),
    isMissingOrEmpty('token'), // Check which layer token validation is at!!
];
$errors = array_filter($errors);
if (!$errors) {
    $request = $_GET['r'];
    $token = $_GET['token'];
    $json = json_decode($request, true);
    $errors = [
        isMissingOrEmptyJson('course', $json),
        isMissingOrEmptyJson('section', $json),
    ];
    $errors = array_filter($errors);
    // If pass input validation...
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
        $roundDAO = new RoundDAO();
        $bidDAO = new BidDAO();
        $currentRound = $roundDAO->getCurrentRound();

        $bids = $bidDAO->retrieveAllBidsBySection($course, $section);
        $size = $sectionDAO -> retrieveSizeByCodeAndSection($course, $section);
        $size = $size[0]['size'];
        if(!$errors) {
            // During Round 1
            if($currentRound == "1" && $roundDAO -> roundIsActive()) {
                $vacancy = $size;
                $numBidsBySection = count($bids);
                if($numBidsBySection < $vacancy) {
                    $minbid = min(array_column($bids, 'amount'));
            ***REMOVED***
                elseif($numBidsBySection >= $vacancy) {
                    $minbid = $bids[$vacancy-1]['amount'];
            ***REMOVED***
                elseif($numBidsBySection == 0) {
                    $minbid = 10.0;
            ***REMOVED***
        ***REMOVED***
            // After Round 1 Ended
            elseif($round == 1 && $roundDAO -> roundIsActive() == false) {
                $numOfSuccessful = $bidDAO -> getSuccessfulByCourseCode($course, $section, $round = 1);
                $vacancy = $size  - $numOfSuccessful;
                if($numOfSuccessful == 0) {
                    $minbid = 10.0;
            ***REMOVED***
                else {
                    $minbid = $bidDAO -> getSuccessfulMinBidAmount($course, $section, $round = 1);
            ***REMOVED***
        ***REMOVED***
            // During Round 2
            elseif($round == 2 && $roundDAO -> roundIsActive() == True) {
                $round1 = $bidDAO -> getSuccessfulByCourseCode($course, $section, $round = 1);
                $round2 = $bidDAO -> getSuccessfulByCourseCode($course, $section, $round = 2);
                $vacancy = $size - ($round1 + $round2);

                if ($round2 >= $vacancy) {
                    // More Bids than Vacancies
                    $minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
            ***REMOVED***
                else {
                    $minbid = 10;
            ***REMOVED***
        ***REMOVED***

            // After Round 2 Ended
            else {
                $round1 = $bidDAO -> getSuccessfulByCourseCode($course, $section, $round = 1);
                $round2 = $bidDAO -> getSuccessfulByCourseCode($course, $section, $round = 2);  
                $vacancy = $size - ($round1 + $round2);

                if ($round2 > 0) {
                    $minbid = $bidDAO->getMinBid($course, $section)['bidAmount'];
            ***REMOVED***
                else {
                    $minbid = 10;
            ***REMOVED***
        ***REMOVED***
            $reports = $bidDAO -> retrieveBidsReport($course, $section);
    ***REMOVED***

***REMOVED***
}
if (!$errors) {
    $result = [
        "status" => "success",
        "vacancy" => $vacancy,
        "min-bid-amount" => $minbid,
        "students " => $reports
    ];
} else {
    $result = [
        "status" => "error",
        "message" => array_values($errors)
    ];
}
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

?>