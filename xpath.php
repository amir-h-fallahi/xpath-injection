<?php
/**
 * This function returns error if input be malformed else returns input itself
 * 
 * Change regex for flexibility but don't allow single quote character becasue it breaks xpath query 
 */
function prevent_xpath_injection(string $input){
    $input = trim($input);
    if (preg_match('/[^a-zA-Z0-9]/',$input)){
        http_response_code(403);
        header('Content-Type: application/json'); 
        $message = array("message" => "Request couldn't process for some reasons");
        echo json_encode($message);
        die();
    }
    else {
        return $input;
    }
}
if (isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    // $username = prevent_xpath_injection($username);
    // $password = prevent_xpath_injection($password);

    $xml = simplexml_load_file('sensitive-data.xml');
    $result = $xml->xpath("//users/user[username = '$username' and password = '$password']");
    if (!empty($result)){
        http_response_code(200); 
        header('Content-Type: application/json'); 
        echo json_encode($result[0]);
    }
    else
    {
    http_response_code(401);
    header('Content-Type: application/json'); 
    $message = array("message" => "username or password doesn't correct");
    echo json_encode($message);
    }
}
else{
$html = <<<HTML
<form action="xpath.php" method="POST">
    Username <input type="text" name="username"><br><br>
    Password <input type="text" name="password">
    <input type="submit" value="submit" name="submit">
</form>
HTML;
echo $html;
}
?>