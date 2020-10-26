<?php
$serverName = "localhost";
$username = "rydezill_kwadmin";
$password = "84h^m}G-,gNt";
$dbName = "rydezill_db";

$conn = mysqli_connect($serverName, $username, $password, $dbName);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$row = 1;
if(($handle = fopen("rydezila.csv", "r")) !== FALSE){
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $num = count($data);

        $row++;
        for($c = 0; $c < $num; $c++){

            $sql = mysqli_query($conn, "SELECT id FROM language_strings WHERE name_key='" . $data[1] . "'");
            if(mysqli_num_rows($sql) == 0){
                mysqli_query($conn, "INSERT INTO language_strings(app_or_panel,screen_name,name_key) VALUES(1,'" . $data[0] . "','" . $data[1] . "')");

                $language_string_id = mysqli_insert_id($conn);

                mysqli_query($conn, "INSERT INTO language_string_translations(language_string_id,name,locale)
            values('" . $language_string_id . "','" . $data[2] . "','en')");

                mysqli_query($conn, "INSERT INTO language_string_translations(language_string_id,name,locale)
            values('" . $language_string_id . "','" . $data[2] . "','en')");
                echo $row;
                echo '<br>';
            }

        }
    }
    fclose($handle);
}
?>
