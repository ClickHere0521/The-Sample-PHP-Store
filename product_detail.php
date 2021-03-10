<?php
    session_start();
    require 'check_if_added.php';
    require 'connection.php';
    $item_id = $_GET['id']; 
    $sql = "SELECT id, name, price, src, rating FROM items WHERE id =". $item_id."";
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
            <input type="hidden" id="userID" value ="<?php echo $_SESSION['id'] ?>" > 
            <?php
                require 'header.php';
            ?>
            <?php
                if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                ?>  
            <div class="container">
                <input type="hidden" id="productID" value ="<?php echo $row['id'] ?>" >                                                                                     
                <div class="jumbotron">
                    <h2><?php echo $row['name'] ?></h2>                    
                </div>                                                
            </div>
            <div class="container">                                          
                <div class="thumbnail">
                    <a href="product_detail.php?id=<?php echo $row['id'] ?>">
                        <img src=<?php echo $row['src'] ?> alt="Cannon">
                    </a>
                    <center>
                        <div class="caption">
                            <h3><?php echo $row['name'] ?></h3>
                            <p>price: PKR.<?php echo $row['price'] ?></p>
                            <div class="stars-outer">
                                <div class="stars-inner" style="width:<?php echo $row['rating']/5*100 ?>%"></div>                                
                            </div>
                            <p><a href="#" id="giveRating" data-toggle="modal" data-target="#myModal">Rate Now</a></p>
                            <?php if(!isset($_SESSION['email'])){  ?>
                                <p><a href="login.php" role="button" class="btn btn-primary btn-block">Buy Now</a></p>                                
                                <?php
                                }
                                else{
                                    if(check_if_added_to_cart($row['id'])){
                                        echo '<a href="#" class=btn btn-block btn-success disabled>Added to cart</a>';
                                    }else{
                                        ?>
                                        <a href="cart_add.php?id=<?php echo $row['id']?>&detail=true" class="btn btn-block btn-primary" name="add" value="add" class="btn btn-block btr-primary" style="font-size:20px">Add to cart</a>
                                        <?php
                                    }
                                }
                            ?>                                    
                        </div>
                    </center>
                </div>     

                <div class="container2"> 
                    <div class="note-header"> <h3>Notes</h3> </div>
                <?php
                
                    $sql3 = "SELECT id, user_id , note FROM note WHERE product_id =". $row['id']; 
                    $result3 = $con->query($sql3);
                    while($notes = $result3->fetch_assoc())
                    {
                ?>                              
                    <div class="note">
                        <div class="second"> 
                            <div style="padding-top:10px">
                                <span class="text2">
                                    <?php $sql2 = "SELECT `name` FROM users WHERE id =". $notes['user_id']; 
                                            $result2 = $con->query($sql2);                    
                                            while($users = $result2->fetch_assoc())
                                            {echo $users['name']; } 
                                    ?>
                                </span>
                            </div>
                            <div class="textDiv"><span class="text1"><?php echo stripslashes($notes['note']) ?></span></div>
                        </div>
                    </div>
                <?php
                   }    
                ?>
                    <div class="inputNote"> 
                        <textarea name="text" placeholder="+ Add your note" class="form-control addtxt"></textarea> 
                        <button id="saveNote" class="btn btn-primary"> save </button>                                                
                    </div>
                </div> 
            </div>

            <?php
                }
                } else {
                    echo "0 results";
                }
            ?>
            <br><br><br><br><br><br><br><br>
            <?php
                require 'footer.php';
            ?>
        </div>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div class="stars">
                        <form action="">
                            <input class="star star-5" id="star-5" type="radio" name="star"/>
                            <label class="star star-5" for="star-5"></label>
                            <input class="star star-4" id="star-4" type="radio" name="star"/>
                            <label class="star star-4" for="star-4"></label>
                            <input class="star star-3" id="star-3" type="radio" name="star"/>
                            <label class="star star-3" for="star-3"></label>
                            <input class="star star-2" id="star-2" type="radio" name="star"/>
                            <label class="star star-2" for="star-2"></label>
                            <input class="star star-1" id="star-1" type="radio" name="star"/>
                            <label class="star star-1" for="star-1"></label>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="rateConfirm" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>

            </div>
        </div>
    </body>
</html>

<script>
    $('document').ready(function()
    {
        userID = $('#userID').val();
        productID = $('#productID').val();
        var radios = document.getElementsByName('star');

        $('#rateConfirm').on('click',function(){
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    
                    $.post("rating.php",
                    {
                        id           : userID,
                        product_id   : productID,
                        rating       : (5-i)
                    },
                    function(data, status){
                        location.reload();
                    });
                }
            }            
        });
        $('#saveNote').on('click', function(){
            userNote = $('.addtxt').val();
            
            $.post("save_note.php",
            {
                id          : userID,
                product_id   : productID,
                note        : userNote
            },
            function(data, status){
                location.reload();
            });
        });
    });
</script>
