<?php 
    $fillName = $_POST["file_name"];
    if(isset($fillName)){
        $myfile = fopen("menu/$sql['MenuGroup']/$fillName.php", 'w');
        $myfile = fopen("menu/$sql['MenuGroup']/ajax/$fillName.php", 'w');
        $data = file_get_contents("data.txt");
        fwrite($myfile, $data);
        fclose($myfile);
    }
    header( "location: test.php" );
?>