<?php

if(isset($_SESSION['msg'])) {
    echo '<div style="background-color: green; width: 100%">'.$_SESSION['msg'].'</div>';
}
if(isset($_SESSION['error'])) {
    echo '<div style="background-color: red; width: 100%">'.$_SESSION['error'].'</div>';
}

?>