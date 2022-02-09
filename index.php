<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="color: #ff6666;">Todo list wep application</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main-class">
        <div class="add-class">
            <h5 style="text-align: center;">Sadece Metezn için yapılmış Todo List</h5>
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                    <input style=" border-color: #ff6666;" name="title" type="text" placeholder="This field is required" />
                    <button type="submit">Add</button>
                <?php } else { ?>
                    <input style="padding-left: 10px;" name="title" type="text" name="title" placeholder="What do you need to do?" />
                    <button type="submit">Add &nbsp; <span>&#43;</span></button>
                <?php  } ?>
            </form>
        </div>

        <?php

        $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");


        ?>
        <div class="show-todo-class">
            <?php if ($todos->rowCount() <= 0) { ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="img/sebastian-svenson-LpbyDENbQQg-unsplash.jpg" width="100%">
                        <img src="img/Ellipsis.gif" width="60px">
                    </div>
                </div>

            <?php } ?>

            <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                    <?php if ($todo['checked']) { ?>
                        <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php } else { ?>
                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>created: <?php echo $todo['date_time'] ?></small>
                </div>
            <?php } ?>

        </div>
    </div>
    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.remove-to-do').click(function() {
                const id = $(this).attr('id');

                $.post("app/remove.php", {
                        id: id
                    },
                    (data) => {
                        if (data) {
                            $(this).parent().hide(600);
                        }
                    }
                );
            });

            $(".check-box").click(function(e) {
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php', {
                        id: id
                    },
                    (data) => {
                        if (data != 'error') {
                            const h2 = $(this).next();
                            if (data === '1') {
                                h2.removeClass('checked');
                            } else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>
</body>

</html>