<html>
<?php
//Config file connects to database and defines resource file strings.
$config_array = include '../config.php';


    ini_set('display_errors', '1');
    error_reporting(E_ALL);

//Connection to database
$mysqli = $config_array['conn'];

//Resources from config file
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];

session_start();
if(!isset($_SESSION['user_name'])){
   header("Location: https://math.uchicago.edu/~drewmoore/MathForum/Login/");
}



echo "
    <head>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400italic,400' rel='stylesheet'                   type='text/css'>

        <title>Submit
        </title>

        <!--Stylesheet-->
        <link rel='stylesheet' type='text/css' href='" . $stylesheet . "'/>

        <!--Icon - FIX -->
        <link rel='shortcut icon' href='../favicon.ico'/>
    ";

$mathjax_on = True; //k
if ($mathjax_on) {
    echo "
        <!--MathJaX-->
        <script type='text/javascript'
        src='https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML'>
        </script>
        ";
}

echo "
        <!--jQuery-->
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'>
        </script>
    </head>";

echo "<body>";


include $header;
?>

<main>
    <div>
<form class="submit_post" method='post' action='process_submit.php'>
<label>Subject:</label><textarea class ='content' name='subject' rows='2'></textarea>
<label>Content:</label><textarea class ='content' name='content' rows = '10'></textarea>
<label>Anonymous?</label> <input type="checkbox" name="is_anonymous">
<input type="submit">
        </form>
        </div>
</main>
</html>
