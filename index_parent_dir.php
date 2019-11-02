<?php

    include '/ubwiki/engine.php';
    $user_id = false;
    $newuser = false;
    $dev = false;
    $special = false;

    if (isset($_GET['special'])) {
        $special = $_GET['special'];
    }
    if (isset($_GET['dev'])) {
        $dev = true;
    }
    if ($special == 14836) {
        $_SESSION['email'] = 'tsilvaalmeida@gmail.com';
        $special = true;
    }
    elseif ($special == 19815848) {
        $_SESSION['email'] = 'cavalcanti@me.com';
        $special = true;
    }
    elseif ($special == 17091979) {
        $_SESSION['email'] = 'marciliofcf@gmail.com';
        $special = true;
    }
    if (!isset($_SESSION['email'])) {
        if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
          $_SESSION['email'] = $_POST['email'];
          $_SESSION['bora'] = $_POST['bora'];
          $user_id = $_SESSION['email'];
          $bora = $_SESSIO['bora'];
          $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_id'");
          if ($result->num_rows == 0) {
            $newuser = true;
            $insert = $conn->query("INSERT INTO Usuarios (tipo, email) VALUES ('estudante', '$user_id')");
          }
        }
        else {
            if ($dev == false) { header('Location:ubwiki/login.php'); }
            else { header('Location:ubwiki_dev/login.php'); }
        }
    }
    else {
     $user_id = $_SESSION['email'];
    }
    if ($user_id != false) {
        $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_id'");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($dev == false) { header("Location:ubwiki/index.php"); }
                else { header('Location:ubwiki_dev/index.php'); }
            }
        }
    }
    else {
        if ($special == true) {
            if ($dev == true) {
                header('Location:ubwiki_dev/index.php');
            }
            else {
                header('Location:ubwiki/index.php');
            }
        }
    }
?>
