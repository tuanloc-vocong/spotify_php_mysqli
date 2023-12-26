const playFavButton = document.querySelector(".fav button");
playFavButton.addEventListener("click", () => {
    if(favSongIds.length > 0){
        let favouriteSongs = [];
        favSongIds.forEach(id => {
            favouriteSongs.push(songDetails[id]);
        })
        playingQueue = favouriteSongs;
        playQueue();
    }else{
        alert("You don't have any favourite song at the moment!");
    }
});