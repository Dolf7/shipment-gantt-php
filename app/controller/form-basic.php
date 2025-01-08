<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $name => $value) {
        echo "Name: $name, Value: $value<br>";
    }
    echo "<a href='../index.php?page=form-basic' > BACK </a>";
} else {
    echo "<script> 
            alert('NO Submitted Form'); 
            document.location='../index.php?page=form-basic'; 
            </script>";
}
