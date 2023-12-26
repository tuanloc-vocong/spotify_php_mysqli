const addPlayingSong = (song, index) => {
    const newSong = document.createElement("li");

    if(index == songIndex){
        newSong.classList.add("playing");
    }
    newSong.setAttribute("song-index", index);

    newSong.innerHTML = `
        <div class="song-info">${index + 1}. ${song.title}</div>
        <div class="beat-container">
            <div class="stroke"></div>
            <div class="stroke"></div>
            <div class="stroke"></div>
        </div>
    `;

    newSong.addEventListener("click", () => {
        const nowPlayingSong = document.querySelector("li.playing");

        if(newSong !== nowPlayingSong){
            nowPlayingSong.classList.remove("playing");
            newSong.classList.add("playing");
            songIndex = index;
            loadSong(playingQueue[songIndex]);
            playSong();
        }
    });

    return newSong;
};

const resetPlayingQueue = () => {
    const songsContainer = document.querySelector(".queue .playing-songs");
    songsContainer.innerHTML = "";

    if(playingQueue.length !== 0){
        playingQueue.forEach((song, index) => {
            const newSong = addPlayingSong(song, index);
            songsContainer.appendChild(newSong);
        });
    }
};

const playingQueueIcon = document.getElementById("playlist");
playingQueueIcon.addEventListener("click", () => {
    const modal = document.querySelector(".queue");
    modal.classList.toggle("queue-active");
});

const collaspIcon = document.querySelector(".fa-chevron-up");
collaspIcon.addEventListener("click", () => {
    const modal = document.querySelector(".queue");
    modal.classList.remove("queue-active");
});