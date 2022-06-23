<?php 

    $link = mysqli_connect('localhost', 'root', '', 'studentpointsystem');

    if(!$link){
        echo 'Connection error: ' . mysqli_connect_error();
    }   
?>