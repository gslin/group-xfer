<?

session_start();

if (isset($_SESSION["email"]) && $_SESSION["email"] == "gslin@ccca.nctu.edu.tw") {
    $_SESSION["email"] = stripslashes($_GET["user"]);
}

header("Location: /");

?>
