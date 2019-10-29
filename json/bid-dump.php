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
        $round = 1;
        if ($currentRound['round'] == 2 and $currentRound['status'] == 'stopped') $round = 2;
        $bidDump = $bidDAO->retrieveBidsDump($section, $course, $round);
***REMOVED***
}
if (!$errors) {
    $result = [
        "status" => "success",
        "bids" => $bidDump
    ];
} else {
    $result = [
        "status" => "error",
        "message" => array_values($errors)
    ];
}
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);

?>