<?php
include('./components/navbar.php');
?>

<?php
function reformData($queryResult){
    return ($queryResult['songId']);
}

$favSongsQuery = "SELECT favourites.songId FROM favourites WHERE favourites.uid = $uid";
$result = mysqli_query($conn, $favSongsQuery);
$queryResult = mysqli_fetch_all($result, MYSQLI_ASSOC);
$favSongs = array_map("reformData", $queryResult);
?>

<div class="fav">
    <h1>Favourites Songs</h1>
    <button>Play all</button>
    <div style="width: 100%;" class="titleContainer">
        <?php foreach($favSongs as $index => $songId) : ?>
            <div class="song" data="<?php echo $formatSongs[$songId]['id']; ?>">
                <div class="info">
                    <h4><?php echo $index + 1; ?></h4>
                    <img src="<?php $formatSongs[$songId]['img']; ?>" alt="">
                    <div class="detail">
                        <h4><?php echo $formatSongs[$songId]['title'] ?></h4>
                        <h5 data-singer="<?php echo $formatSongs[$songId]['singerId']; ?>"><?php echo $formatSongs[$songId]['singerName']; ?></h5>
                    </div>
                </div>
                <div class="func">
                    <i class="fas fa-trash"></i>
                    <i class="fas fa-list-ul"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    let favSongIds = JSON.parse('<?php echo json_encode($favSongs); ?>');
</script>