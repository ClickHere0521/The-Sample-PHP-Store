<?php
    require 'connection.php';
    $sel1 = isset($_POST['sel1']) ? $_POST['sel1'] : null; 
    $sel2 = isset($_POST['sel2']) ? $_POST['sel2'] : null; 

    if($sel2 != null)
    {
        switch($sel2)
        {
            case "0" : $sql =  "SELECT id, `name`, price, src, rating FROM items WHERE category = 1"; break;
            case "1" : $sql =  "SELECT id, `name`, price, src, rating FROM items WHERE category = 2"; break;
            case "2" : $sql =  "SELECT id, `name`, price, src, rating FROM items WHERE category = 3"; break;
            case "3" : $sql =  "SELECT id, `name`, price, src, rating FROM items"; break;
        }
        if($sel1 != null)
        {
            switch($sel1)
            {
                case "0" : $sql = $sql . " ORDER BY name ASC"; break;
                case "1" : $sql = $sql . " ORDER BY name DESC"; break;
                case "2" : $sql = $sql . " ORDER BY price ASC"; break;
                case "3" : $sql = $sql . " ORDER BY price DESC"; break;
                case "4" : $sql = $sql . " ORDER BY rating ASC"; break;
                case "5" : $sql = $sql . " ORDER BY rating DESC"; break;
            }
        }        
    }
    else
    {
        if($sel1 != null)
        {
            switch($sel1)
            {
                case "0" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `name` ASC"; break;
                case "1" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `name` DESC"; break;
                case "2" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `price` ASC"; break;
                case "3" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `price` DESC"; break;
                case "4" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `rating` ASC"; break;
                case "5" : $sql = "SELECT id, `name`, price, src, rating FROM items ORDER BY `rating` DESC"; break;
            }
        }
        else
        {
            header('location : products.php');
        }        
    }

    $result = $con->query($sql);
    $i=0;
    while($row = $result->fetch_assoc())
    {
        $resultArray[$i] = array(
            "id"      => $row['id'],
            "name"    => $row['name'],            
            "price"   => $row['price'],
            "src"     => $row['src'],
            "rating"  => $row['rating']
        );
        $i++;        
    }
    echo json_encode($resultArray);
?>