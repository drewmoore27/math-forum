<html>
<?php
$config_array = include '../config.php';
$mysqli = $config_array['conn'];
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];

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

 ?>

 <main>
 <body>
     <h3>Signup</h3>
     <form method='post' action = 'process_new_user.php'>
         <label>Username :</label><input type="text" name = "user_name"><br /><br />
         <label>Password :</label><input type="password" name = "user_pass"><br /><br />
         <label>Confirm Password :</label><input type="password" name = "confirm_pass"><br /><br />
         <input type='submit'>
     </form>
     <?php
     if (isset($_POST['name_taken'])) {
       echo "<p>Username taken. Try another name.</p>";
     }
      ?>
      <?php
      if (isset($_POST['wrong_confirm'])) {
        echo "<p>Passwords did not match. Try again.</p>";
      }
       ?>

 </body>
 </main>

 </html>
