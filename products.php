<?php
    session_start();
    require 'check_if_added.php';
    require 'connection.php';
        $sql = "SELECT id, name, price, src, rating FROM items";
        $result = $con->query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="img/lifestyleStore.png" />
        <title>Kamran' s Store</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- latest compiled and minified CSS -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
        <!-- jquery library -->
        <script type="text/javascript" src="bootstrap/js/jquery-3.2.1.min.js"></script>
        <!-- Latest compiled and minified javascript -->
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
        <!-- External CSS -->
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        
    </head>
    <body>
        <div>
            <?php
                require 'header.php';
            ?>
            <div class="container">
                <div class="jumbotron">
                    <h1>Welcome to Kamran' s Store!</h1>
                    <p>We have the best cameras, watches and shirts for you. No need to hunt around, we have all in one place.</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="sel1">Sort by:</label>
                    <select class="form-control" id="sel1">
                        <option value="0">Name ascending</option>
                        <option value="1">Name descending</option>
                        <option value="2">Price ascending</option>
                        <option value="3">Price descending</option>
                        <option value="4">Rate ascending</option>
                        <option value="5">Rate descending</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="sel2">Filter by:</label>
                    <select class="form-control" id="sel2">
                        <option value="0">Cameras</option>
                        <option value="1">Watches</option>
                        <option value="2">Shirts</option>
                        <option value="3">All</option>
                    </select>
                </div>
            </div>
            <div class="container" id="products">
                <?php
                    if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                    ?>    
                    <div class="col-md-3 col-sm-6">
                        <div class="thumbnail">
                            <a href="product_detail.php?id=<?php echo $row['id'] ?>">
                                <img src=<?php echo $row['src'] ?> alt="Cannon">
                            </a>
                            <center>
                                <div class="caption">
                                    <h3><a href="product_detail.php?id=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></h3>
                                    <p>price: PKR.<?php echo $row['price'] ?></p>
                                    <div class="stars-outer">
                                        <div class="stars-inner" style="width:<?php echo $row['rating']/5*100 ?>%"></div>
                                    </div>
                                    <?php if(!isset($_SESSION['email'])){  ?>
                                        <p><a href="login.php" role="button" class="btn btn-primary btn-block">Buy Now</a></p>
                                        <?php
                                        }
                                        else{
                                            if(check_if_added_to_cart($row['id'])){
                                                echo '<a href="#" class=btn btn-block btn-success disabled>Added to cart</a>';
                                            }else{
                                                ?>
                                                <a href="cart_add.php?id=<?php echo $row['id'] ?>" class="btn btn-block btn-primary" name="add" value="add" class="btn btn-block btr-primary">Add to cart</a>
                                                <?php
                                            }
                                        }
                                    ?>                                    
                                </div>
                            </center>
                        </div>
                    </div>
                    <?php
                    }
                    } else {
                        echo "0 results";
                }
                ?>
            </div>
            <br><br><br><br><br><br><br><br>
           <?php
                require 'footer.php';
                ?>
        </div>
    </body>
</html>
<script>

$(document).ready(function(){
    var selSort, selFilter;
    var sel1 , sel2 ;
    
    $('#sel1').on('change', function() {
        sel1 = $('#sel1').val();
        sendGrid(sel1,sel2);
    });

    $('#sel2').on('change', function() {
        sel2 = $('#sel2').val();
        sendGrid(sel1,sel2);
    });
    function sendGrid(sel1,sel2){
        $.post("products_sort.php",
        {
            sel1 : sel1,
            sel2 : sel2
        },
        function(data, status){
            $('#products').html('');
            result = JSON.parse(data);
            count=0;
            result.forEach((element) => {
                count++
                if(count % 4 ==0){
                    $('#products').append(`
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="thumbnail">
                                <a href="product_detail.php?id=${element.id}">
                                    <img src=${element.src} alt="Cannon">
                                </a>
                                <center>
                                    <div class="caption">
                                        <h3><a href="product_detail.php?id=${element.id}">${element.name}</a></h3>
                                        <p>price: PKR.${element.price}</p>
                                        <div class="stars-outer">
                                            <div class="stars-inner" id="${element.id}"></div>
                                        </div>
                                        <a href="cart_add.php?id=${element.id}" class="btn btn-block btn-primary" name="add" value="add" class="btn btn-block btr-primary">Add to cart</a>
                                    </div>
                                </center>
                            </div>
                        </div>
                `);            
                }
                else{
                    $('#products').append(`                    
                        <div class="col-md-3 col-sm-6">
                            <div class="thumbnail">
                                <a href="product_detail.php?id=${element.id}">
                                    <img src=${element.src} alt="Cannon">
                                </a>
                                <center>
                                    <div class="caption">
                                        <h3><a href="product_detail.php?id=${element.id}">${element.name}</a></h3>
                                        <p>price: PKR.${element.price}</p>
                                        <div class="stars-outer">
                                            <div class="stars-inner" id="${element.id}"></div>
                                        </div>
                                        <a href="cart_add.php?id=${element.id}" class="btn btn-block btn-primary" name="add" value="add" class="btn btn-block btr-primary">Add to cart</a>
                                    </div>
                                </center>
                            </div>
                        </div>
                    `);            
                }        
                const starPercentage = (element.rating / 5) * 100;   
                const starPercentageRounded = `${(Math.round(starPercentage / 10) * 10)}%`;  
                $(`#${element.id} `).css({"width": starPercentageRounded});                  
            })            
        });
    }
});

</script>
