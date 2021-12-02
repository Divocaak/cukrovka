<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../home.php");
    exit;
}

require_once "../utils/config.php";
require_once "../objects/player.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Prosím, uživatelské jméno.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím, zadejte heslo.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT p.id, p.username, p.password, p.level, l.name FROM players p INNER JOIN levels l ON l.id=p.level WHERE p.username='" . $_POST["username"] . "';";

        if ($result = mysqli_query($link, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                if (password_verify($_POST["password"], $row[2])) {
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $player = new Player($row[0], $row[1], $row[3], $row[4]);
                    $_SESSION["player"] = serialize($player);

                    header("location: ../home.php");
                } else {
                    echo "Nesprávné uživatelské jméno/heslo.";
                }
            }
            mysqli_free_result($result);
        } else {
            echo "ERROR";
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Přihlaste se</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body>
    <br><br><br>
    <div class="d-flex align-items-center justify-content-center">
        <h2>Přihlaste se</h2>
    </div>
    <div class="d-flex align-items-center justify-content-center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group text-danger <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input placeholder="Uživatelské jméno" type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group text-danger <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input placeholder="Heslo" type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Přihlásit se">
                <a class="btn btn-outline-primary" href="register.php">Zaregistrujte se</a>
            </div>
        </form>
    </div>
</body>

</html>