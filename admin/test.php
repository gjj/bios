***REMOVED***
$msg = " Hello ";
echo strlen($msg);
// if(ctype_space($msg)){
//     echo "has space";
//     $msg = trim($msg);
// }
$result = "";
for($j=0;$j<strlen($msg);$j++){
    if($msg[$j] == " "){
        $msg[$j] = "";
***REMOVED***
    $result += $msg[$j];
    // else{
        
    // }
}
echo strlen($result);
?>
