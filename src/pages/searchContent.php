<?php
include('./components/nacbar.php');
?>

<?php
if(isset($_GET['search'])){
    $filterTexts = $_GET['search'];
    $songsFilterQuery = "SELECT songs.id, songs.title title, singers.name singerName, songs.filePath audio, songs.imgPath img, singers.id singerId
                        FROM songs LEFT JOIN singers ON singers.id = songs.singerId
                        WHERE title LIKE '%$filterTexts%' OR singers.name LIKE '%$filterTexts%'";
    $result = mysqli_query($conn, $songsFilterQuery);
    $songs = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<section>
    <h3 class="sectionTitle">Songs</h3>
    <div class="songsContain">
        <?php foreach($songs as $index => $songs) : ?>
            <?php
            $heartIcon = '<i class="far fa-heart"></i>';
            if($authenticated){
                if(in_array($song['id'], $favSongs)){
                    $heartIcon = '<i class="fas fa-heart" fav="1"></i>';
                }
            }
            ?>
            <div class="song" data="<?php echo $song['id']; ?>">
                <div class="info">
                    <h4><?php echo $index + 1; ?></h4>
                    <img src="<?php echo $song['img']; ?>" alt="">
                    <div class="detail">
                        <h4><?php echo $song['title']; ?></h4>
                        <h5 class="singerPage" data-singer="<?php echo $song["singerId"]; ?>"><?php echo $song['singerName']; ?></h5>
                    </div>
                </div>
                <div class="func">
                    <?php echo $heartIcon; ?>
                    <i class="fas fa-list-ul"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>