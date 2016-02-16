<?php
    if(!$_POST){
        $wyswietlany_blok = <<<LONG
        <form method="POST" action="{$_SERVER["PHP_SELF"]}">
        <p>Temat</p>
        <input type="text" name="temat">
        <p>Tresc wiadomości</p>
        <textarea name="wiadomosc" cols="50" rows="10"></textarea>
        <input type="submit" value="Wyslij">
        </form>
LONG;
    }
    else if($_POST){
        if($_POST["temat"] == "" || $_POST["wiadomosc"] == ""){
            header("Location: sendmymail.php");
            exit();
        }
        $mysqli = new mysqli('localhost', 'tomek', '', 'mail_list');
        if($mysqli->connect_errno){
            echo "Nie mozna się połączyc z bazą ".$mysqli->connect_error;
            exit();
        }
        else{
            $sql = "SELECT email FROM subskrybenci";
            $rezultat = $mysqli->query($sql) or die($mysqli->error);
            
            while($wiersz = $rezultat->fetch_array()){
                set_time_limit(0);
                $email = $wiersz["email"];
                mail($email, stripslashes($_POST["temat"]), stripslashes($_POST["wiadomosc"]));
            }
            $wyswietlany_blok = "Wysłano email :)";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>Wysyłanei e-maila</title>
<meta charset="utf-8">
</head>
<body>
<?php
    echo $wyswietlany_blok;
?>
</body>
</html>

