<?php
    require 'connection.php';

    $user_id=$_POST['id'];
    $rating=$_POST['rating'];
    $product_id=$_POST['product_id'];  
    
    $sql = "SELECT id, user_id, product_id, rating FROM rating WHERE user_id =". $user_id." AND product_id = ".$product_id;
    $result = $con->query($sql); 
    if ($result->num_rows > 0) {
        // output data of each row
        $sql2="UPDATE rating SET rating='". $rating ."' WHERE user_id=$user_id AND product_id = ".$product_id;
        $result2 = $con->query($sql2);
    }
    else
    {
        $add_to_rating_query="INSERT INTO rating(user_id,rating,product_id) VALUES ('$user_id','$rating','$product_id')";
        $add_to_rating_result=mysqli_query($con,$add_to_rating_query) or die(mysqli_error($con));
    }

    $total_rating = 0;
    $i = 0;
    $sql3 = "SELECT id, rating FROM rating WHERE product_id =". $product_id;    
    $result3 = $con->query($sql3);
    while($row = $result3->fetch_assoc()) 
    {
        $total_rating += $row['rating'];
        $i++;
    }
    $ave_rating = $total_rating / $i;

    $sql4="UPDATE items SET rating='". $ave_rating ."' WHERE id=$product_id";
    $result4 = $con->query($sql4);

    
?>