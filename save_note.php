<?php
    require 'connection.php';

    $user_id=$_POST['id'];
    $note=$_POST['note'];
    $product_id=$_POST['product_id'];  
    
    $add_to_note_query="INSERT INTO note(user_id,note,product_id) values ('$user_id','$note','$product_id')";
    $add_to_note_result=mysqli_query($con,$add_to_note_query) or die(mysqli_error($con));
    
?>