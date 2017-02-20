<?php
session_start();
session_destroy();
header('Location: https://math.uchicago.edu/~drewmoore/MathForum/Login/');
?>