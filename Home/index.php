<html>
<?php
//Home/index.php
//


//Config file connects to database and defines resource file strings.
$config_array = include '/ta/drewmoore/public_html/MathForum/config.php';


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

        <title>All Posts
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

echo "<main>";


echo  " <div class='posts'>";

echo Post::display_top_posts($mysqli, 25);





echo "</div>";





//Tests


//$post = new Post($mysqli, 1);
//echo $post->display_in_list($mysqli);
//
//echo "<br>";
//echo "<br>";
//
//echo print_r($post->get_children_id_array($mysqli));
//echo "<br>";
//echo "<br>";
//echo $post->count_children($mysqli);

//$response1 = new Response($mysqli, 1, True);
//$response2 = new Response($mysqli, 2, True);
//$response5 = new Response($mysqli, 5, True);
//echo "<br>";
//echo "<br>";
//echo $response1->count_children($mysqli);
//echo "<br>";
//echo $response2->count_children($mysqli);
//echo "<br>";
//echo $response5->count_children($mysqli);
//echo "<br>";
//echo "<br>";
//echo print_r($response1->get_children_id_array($mysqli));
//echo count($response1->get_children_id_array($mysqli));
//echo "<br>";
//echo print_r($response2->get_children_id_array($mysqli));
//echo count($response2->get_children_id_array($mysqli));
//echo "<br>";
//echo print_r($response5->get_children_id_array($mysqli));
//echo count($response5->get_children_id_array($mysqli));
//

?>
</div>
</main>
</html>
