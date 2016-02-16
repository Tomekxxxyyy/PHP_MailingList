<?php

function baza(){
   global $mysqli;
   $mysqli = new mysqli('localhost', 'tomek', '', 'mail_list');
   $mysqli->set_charset("utf8");
   
   if($mysqli->connect_errno){
       echo "Nie mozna się połączyc z bazą ".$mysqli->connect_error;
       exit();
   }
}
function testerAdresow($email){
    global $mysqli, $jaki_wynik;
    
    $sprawdz = "SELECT id FROM subskrybenci WHERE email = '".$email."'";
    $jaki_wynik = $mysqli->query($sprawdz) or die($mysqli->error);
}

if(!$_POST){
    $wyswietlany_blok = <<<LONG
    <form method = "POST" action = "{$_SERVER["PHP_SELF"]}">
    <p>Podaj swój email</p>
    <input type="text" name="email">
    <p><strong>Co zrobić?</strong></p>
    <input type="radio" name="action" value="sub" checked>zapisać
    <input type="radio" name="action" value="unsub">odwołać
    <p><input type="submit" value="Przeslij dane"></p>
    </form>
LONG;
}
else if($_POST && $_POST["action"] == "sub"){
    if($_POST["email"] == ""){
        header("Location: manage.php");
        exit;
    }
    else{
        baza();
        testerAdresow($_POST["email"]);
        
        if($jaki_wynik->num_rows < 1){
            $jaki_wynik->free_result();
            
            $add_sql = "INSERT INTO subskrybenci(email) VALUES('".$_POST["email"]."')";
            $add_res = $mysqli->query($add_sql) or die($mysqli->error);
            $wyswietlany_blok = "<p>"."Dodoano do bazy email: ".$_POST["email"]."</p>";
            $mysqli->close();
        }
        else{
            $wyswietlany_blok = "<p>Jesteś juz zapisany</p>";
        }
    }
}
else if($_POST && $_POST["action"] == "unsub"){
    if($_POST["email"] == ""){
        header("Location: manage.php");
        exit;
    }
    else{
        baza();
        testerAdresow($_POST["email"]);
        
        if($jaki_wynik->num_rows < 1){
            $jaki_wynik->free_result();
            $wyswietlany_blok = "<p>Twojego adresu nie ma na liście</p>"."<p>Nie odwołano subskrypcji</p>";
        }
        else{
            $wiersz =  $jaki_wynik->fetch_array();
            $id = $wiersz["id"];
            $sql_usuwajacy = "DELETE FROM subskrybenci WHERE id=".$id;
            $usuniecie_wynik = $mysqli->query($sql_usuwajacy) or die($mysqli->error);
            $wyswietlany_blok = "<p>Subskrypcja została odwołana</p>";
        }
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Zarządzanie subskrypcjami</title>
    <meta charset="utf-8">
</head>    
<body>
    <h1>Zamoawianie i odwoływanie subskrypcji</h1>
    <?php
        echo $wyswietlany_blok;
    ?>
</body>    
</html>
