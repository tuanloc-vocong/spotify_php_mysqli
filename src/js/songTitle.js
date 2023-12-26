const insertToQueue = (song) => {
    if(!playingQueue.includes(song)){
        playingQueue.push(song);
        alert(`Songs ${song['title']} is added to queue!`);
        resetPlayingQueue();
    }else{
        alert(`Songs ${song['title']} is already in playing queue!`);
    }
};

const addToFav = (song, isFav) => {
    if(!authenticated){
        loginPopup();
    }else{
        const ajaxFile = isFav ? "delFromFav" : "addToFav";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", `./utils/${ajaxFile}.php?songId=${song.id}`, true);
        xmlhttp.send();

        if(isFav){
            const index = favSongIds.indexOf(song.id);
            if(index > -1){
                favSongIds.splice(index, 1);
            }
            removeTitleFromFav();
        }else{
            favSongIds.push(song.id);
            makeSongTitleForFav(favSongIds.length - 1, song);
        }
    }
};

const makeSongTitle = (index, song) => {
    const titleContainer = document.createElement("div");
    titleContainer.classList.add("song");
    titleContainer.setAttribute("data", song["id"]);

    let heartIcon = `<i class="far fa-heart"></i>`;

    if(authenticated){
        if(favSongIds.includes(song['id'])){
            heartIcon = `<i class="fas fa-heart" fav="1"></i>`;
        }
    }

    titleContainer.innerHTML = `
        <div class="info">
            <h4>${index + 1}</h4>
            <img src="${song["img"]}"/>
            <div class="detail">
                <h4>${song["title"]}</h4>
                <h5 class="singerPage" data-singer="${song['singerId']}">${song['singerName']}</h5>
            </div>
        </div>
        <div class="func">
            ${heartIcon}
            <i class="fas fa-list-ul"></i>
        </div>
    `;

    const playButton = titleContainer.querySelector("h4");
    const favIcon = titleContainer.querySelector("i.fa-heart");
    const queueIcon = titleContainer.querySelector("i.fa-list-ul");

    playButton.addEventListener("click", () => {
        playImmediate(song);
    });

    favIcon.addEventListener("click", () => {
        addToFav(song, favIcon.classList.contains("fas"));
        if(authenticated){
            favIcon.className = favIcon.classList.contains("fas") ? "far fa-heart" : "fas fa-heart";
        }
    });

    queueIcon.addEventListener("click", () => {
        insertToQueue(song);
    });

    return titleContainer;
};

const makeSongTitleForFav = (index, song) => {
    const favContent = document.querySelector(".fav .titleContainer");
    const titleContainer = document.createElement("div");
    titleContainer.classList.add("song");
    titleContainer.setAttribute("data", song['id']);

    titleContainer.innerHTML = `
        <div class="info">
            <h4>${index + 1}</h4>
            <img src="${song["img"]}"/>
            <div class="detail">
                <h4>${song['title']}</h4>
                <h5 class="singerPage" data-singer="${song['singerId']}">${song['singerName']}</h5>
            </div>
        </div>
        <div class="func">
            <i class="fas fa-trash"></i>
            <i class="fas fa-list-ul"></i>
        </div>
    `;

    const playButton = titleContainer.querySelector("h4");
    const trashIcon = titleContainer.querySelector("i.fa-trash");
    const queueIcon = titleContainer.querySelector("i.fa-list-ul");

    playButton.addEventListener("click", () => {
        playImmediate(song);
    });

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

    queueIcon.addEventListener("click", () => {
        insertToQueue(song);
    });

    favContent.appendChild(titleContainer);
};

const removeTitleFromFav = () => {
    const favContent = document.querySelector.apply(".fav .titleContainer");
    favContent.innerHTML = "";
    favSongIds.forEach((id, index) => {
        makeSongTitleForFav(index, songDetails[id]);
    });
};