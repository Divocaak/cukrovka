<?php
require_once "../utils/config.php";

$username = $password = $confirm_password = $discount = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Prosím, zadejte uživatelské jméno";
    } else if (strlen(trim($_POST["username"])) > 20) {
        $username_err = "Uživatelské jméno max. 20 znaků";
    } else {
        $sql = "SELECT id FROM players WHERE username = " . $_POST["username"];
        if (mysqli_query($link, $sql)) {
            $username_err = "Toto uživatelské jméno je již zabráno.";
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Prosím, zadejte heslo.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Heslo musí obsahovat minimálně 6 znaků.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Prosím, zopakujte heslo.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Hesla se neshodují.";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $param_username = $username;
        $passHash = password_hash($_GET["password"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO players (username, password) VALUES ('" . $_POST["username"] . "', '" . $passHash . "')";
        if (mysqli_query($link, $sql)) {
            $outputMessage = "Byl jste úspěšně zaregistrován, přihlaste se.";
        } else {
            echo "ERROR";
        }
    }
    // TODO při registraci insert data do Tree, do všech 1 bod
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body>
    <br><br><br>
    <div class="d-flex align-items-center justify-content-center">
        <h2>Registrujte se</h2>
    </div>
    <div class="d-flex align-items-center justify-content-center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group text-danger <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input placeholder="Uživatelské jméno" type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group text-danger <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input placeholder="Heslo" type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group text-danger <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input placeholder="Heslo znovu" type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrovat se">
                <a class="btn btn-outline-primary" href="login.php">Přihlaste se</a>
            </div>
        </form>
    </div>
</body>

</html>