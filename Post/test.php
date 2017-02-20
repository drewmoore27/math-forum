
<html>
<main>
    hello
    <?php
//Post/index.php


    ini_set('display_errors', '1');
    error_reporting(E_ALL);




//Config file connects to database and defines resource file strings.
$config_array = include '/ta/drewmoore/public_html/MathForum/MathForumResources/config.php';

$text = "kjkj\n\n\nkjkj";

echo "hi";

echo "<br>";



echo my_parse($text);

?>
    </main>
</html>
