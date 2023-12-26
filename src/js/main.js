const cards = document.querySelectorAll(".card");
const songsTitle = document.querySelectorAll(".song");

const playBtn = document.querySelector("#play");
const prevBtn = document.querySelector("#prev");
const nextBtn = document.querySelector("#next");
const muteBtn = document.querySelector("#mute");

const audio = document.querySelector("#audio");
const coverImg = document.querySelector("#imgCover");
const title = document.querySelector("#title");
const singerName = document.querySelector("#singerName");
const progressContainer = document.querySelector(".progressContainer");
const progress = document.querySelector(".progress");
const volumeInfo = document.querySelector(".volumeInfo");
const volume = document.querySelector(".volume");

const inputSearchs = document.querySelectorAll(".search");

let isPlaying = false;
let currentVol = 1;
let playingQueue = [];
let songIndex = 0;

const profilePics = document.querySelectorAll(".logo");
profilePics.forEach(pic => {
    pic.addEventListener("click", () => {
        const links = document.querySelectorAll(".logo-links");

        links.forEach(link => {
            link.classList.toggle("logo-active");
        });
    });
});

function goToSingerPage(){
    const singerLinks = document.querySelectorAll(".singerPage");
    singerLinks.forEach(link => {
        link.addEventListener("click", () => {
            const singerId = link.getAttribute("data-singer");
            window.history.pushState("", "", pageUrl + "/" + "singer.php" + "?singerId=" + singerId);
            showContent("singer");

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.responseText !== ""){
                        var data = JSON.parse(this.responseText);
                        const singerUI = document.getElementById("singer");

                        const sImg = singerUI.querySelector(".cover img");
                        sImg.src = data['image'];

                        const sName = singerUI.querySelector(".coverDetail h1");
                        sName.innerText = data['name'];

                        const sDescription = singerUI.querySelector(".description p");
                        sDescription.innerText = data['info'];

                        const sDesImg = singerUI.querySelector(".description img");
                        sDesImg.src = data['image'];

                        const allSingerSongs = singerUI.querySelector('.products');
                        allSingerSongs.innerHTML = "";
                        data['songs'].forEach((song, index) => {
                            const newTitle = makeSongTitle(index, song);
                            allSingerSongs.appendChild(newTitle);
                        });

                        const pulseBtn = document.querySelector(".pulse");
                        const newPulseBtn = pulseBtn.cloneNode(true);
                        pulseBtn.parentNode.replaceChild(newPulseBtn, pulseBtn);
                        newPulseBtn.addEventListener("click", () => {
                            playingQueue = data['songs'];
                            songIndex = 0;
                            playQueue();
                        });
                    }
                }
            };

            xmlhttp.open("GET", "./utils/getSingerInfo.php?singerId=" + singerId, true);
            xmlhttp.send();
        });
    });
}

function loadSong(song){
    audio.src = song["audio"];
    coverImg.src = song['img'];
    title.innerText = song['title'];
    singerName.innerText = song['singerName'];
}

function playSong(){
    if(audio.src.includes('#')) return;

    playBtn.querySelector("i.fas").classList.remove("fa-play");
    playBtn.querySelector("i.fas").classList.add("fa-pause");
    audio.play();
    isPlaying = true;
}

function playQueue(){
    loadSong(playingQueue[songIndex]);
    playSong();
    resetPlayingQueue();
}

function playImmediate(song){
    songIndex = 0;
    playingQueue = [];
    playQueue.push(song);
    playQueue();
    resetPlayingQueue();
}

function pauseSong(){
    playBtn.querySelector("i.fas").classList.add("fa-play");
    playBtn.querySelector("i.fas").classList.remove("fa-pause");
    audio.pause();
    isPlaying = false;
}

function nextSong(){
    songIndex++;

    if(songIndex > playingQueue.length - 1){
        songIndex = 0;
    }

    loadSong(playingQueue[songIndex]);
    playSong();
    resetPlayingQueue();
}

function prevSong(){
    songIndex--;

    if(songIndex < 0){
        songIndex = playingQueue.length - 1;
    }

    loadSong(playingQueue[songIndex]);
    playSong();
    resetPlayingQueue();
}

function updateProgress(e){
    const { duration, currentTime } = e.srcElement;
    const progressPercent = (currentTime / duration) * 100;
    progress.style.width = `${progressPercent}%`;
}

function endSong(){
    nextSong();
}

function setProgress(e){
    const width = this.clientWidth;
    const clickX = e.offsetX;
    const duration = audio.duration;
    audio.currentTime = (clickX / width) * duration;
}

function setVolume(e){
    const width = this.clientWidth;
    const clickX = e.offsetX;
    volumePercent = (clickX / width) * 100;
    currentVol = (clickX / width) * 1;
    audio.volume = currentVol;
    volume.style.width = `${volumePercent}%`;
}

function search(e){
    let filterTexts = e.target.value;
    window.history.pushState("", "", pageUrl + "/" + "search.php?search=" + filterTexts);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if(this.responseText !== ""){
                var songs = JSON.parse(this.responseText);
                const songsContain = document.querySelector(".songsContain");
                songsContain.innerHTML = "";

                songs.forEach((song, index) => {
                    const newTitle = makeSongTitle(index, song);
                    songsContain.appendChild(newTitle);
                });
                goToSingerPage();
            }
        }
    };
    xmlhttp.open("GET", "./utils/getSongs.php?filter=" + filterTexts, true);
    xmlhttp.send();
}

cards.forEach(card => {
    card.addEventListener("click", () => {
        const songId = card.getAttribute("data");
        const song = songDetails[songId];
        playImmediate(song);
    });
});

songsTitle.forEach(title => {
    let info = title.querySelector(".info h4");
    const queueIcon = title.querySelector("i.fa-list-ul");
    const favIcon = title.querySelector("i.fa-heart");
    const trashIcon = title.querySelector("i.fa-trash");
    const songId = title.getAttribute("data");
    const song = songDetails[songId];

    info.addEventListener("click", () => {
        playImmediate(song);
    });

    queueIcon.addEventListener("click", () => {
        insertToQueue(song);
    });

    if(favIcon){
        favIcon.addEventListener("click", () => {
            addToFav(song, favIcon.classList.contains("fas"));
            if(authenticated){
                favIcon.className = favIcon.classList.contains("fas") ? "far fa-heart" : "fas fa-heart";
            }
        });
    }

    if(trashIcon){
        trashIcon.addEventListener("click", () => {
            const searchSongTitles = document.querySelectorAll("#search .song");
            searchSongTitles.forEach(title => {
                const songId = title.getAttribute("data");
                if(songId == song.id){
                    const heartIcon = title.querySelector(".func .fa-heart");
                    heartIcon.className = "far fa-heart";
                }
            });
            addToFav(song, true);
        });
    }
});

playBtn.addEventListener("click", () => {
    if(isPlaying){
        pauseSong();
    }else{
        playSong();
    }
});

nextBtn.addEventListener("click", () => {
    nextSong();
});

prevBtn.addEventListener("click", () => {
    prevSong();
});

mute.addEventListener("click", () => {
    if(audio.volume != 0){
        mute.classList.remove("fa-volume-up");
        mute.classList.add("fa-volume-mute");
        mute.style.color = "red";
        audio.volume = 0;
        volume.style.width = "0%";
    }else{
        mute.classList.add("fa-volume-up");
        mute.classList.remove("fa-volume-mute");
        mute.style.color = "lightgreen";
        audio.volume = currentVol;

        const volPercent = (currentVol / 1) * 100;
        volume.style.width = `${volPercent}%`;
    }
});

audio.addEventListener("timeupdate", updateProgress);
audio.addEventListener("ended", endSong);
progressContainer.addEventListener("click", setProgress);
volumeInfo.addEventListener("click", setVolume);
inputSearchs.forEach(inputSearch => {
    inputSearch.addEventListener("input", search);
});

goToSingerPage();