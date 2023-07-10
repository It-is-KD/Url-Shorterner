<?php 
  include "php/config.php";
  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }
      $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
        $sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
        if($sql2){
            $full_url = mysqli_fetch_assoc($sql);
            header("Location:".$full_url['full_url']);
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--==================== UNICONS ====================-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <!--==================== CSS ====================-->
    <link rel="stylesheet" href="CSS/style.css">

    <title>URL Shortner</title>
</head>
    <body>
        <div class="wrapper">
            <form action="#" autocomplete="off">
                <input type="text" spellcheck="false" name="full_url" placeholder="Enter the URL to be shortened" required>
                <i class="uil uil-link url-icon"></i>
                <button>Shorten</button>
            </form>

            <?php
                $sql2 = mysqli_query($conn, "SELECT * FROM url ORDER BY id DESC");
                if(mysqli_num_rows($sql2) > 0){;
                    ?>
                    <div class="statistics">
                        <?php
                        $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url");
                        $res = mysqli_fetch_assoc($sql3);

                        $sql4 = mysqli_query($conn, "SELECT clicks FROM url");
                        $total = 0;
                        while($count = mysqli_fetch_assoc($sql4)){
                            $total = $count['clicks'] + $total;
                        }
                        ?>
                        <span style="color: white">Total Links: <span><?php echo end($res) ?></span> & Total Clicks: <span><?php echo $total ?></span></span>
                        <a href="php/delete.php?delete=all">Clear All</a>
                    </div>
                    <div class="url-area">
                    <div class="title">
                        <li>Shortened URL</li> <!--flipped-->
                        <li>Original URL</li>  <!--flipped-->
                        <li>Clicks</li>
                        <li>Action</li>
                    </div>
                    <?php
                        while($row = mysqli_fetch_assoc($sql2)){
                        ?>
                            <div class="data">
                            <li>
                            <a href="<?php echo $domain.$row['shorten_url'] ?>" target="_blank">
                            <?php
                                if($domain.strlen($row['shorten_url']) > 50){
                                echo $domain.substr($row['shorten_url'], 0, 50) . '...';
                                }else{
                                echo $domain.$row['shorten_url'];
                                }
                            ?>
                            </a>
                            </li> 
                            <li>
                        <?php
                            if(strlen($row['full_url']) > 60){
                            echo substr($row['full_url'], 0, 60) . '...';
                            }else{
                            echo $row['full_url'];
                            }
                        ?>
                        </li> 
                    </li>
                        <li><?php echo $row['clicks'] ?></li>
                        <li><a href="php/delete.php?id=<?php echo $row['shorten_url'] ?>">Delete</a></li>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            <?php
                }
            ?> 
        </div>
        <div class="blur-effect"></div>
        <div class="popup-box">
            <div class="info-box">Your shortened link is ready. Only editable before saving.</div>
            <form action="#" autocomplete="off">
                <label>Edit your shortened URL</label>
                <input class="shorten-url" type="text" spellcheck="false">
                <i class="uil uil-copy copy-icon"></i>
                <button>Save</button>
            </form>
        </div>
    
        <script src="JS/main.js"></script>
    </body>
</html>