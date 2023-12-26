CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(64),
    password VARCHAR(64),
    groupId INT,
)

CREATE TABLE singers(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(64),
    info VARCHAR(128),
    image VARCHAR(64),
)ENGINE=INNODB;

CREATE TABLE songs(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(64),
    filePath VARCHAR(64),
    imgPath VARCHAR(64),
    singerId INT,
    CONSTRAINT fk_songs_singers FOREIGN KEY (singerId) REFERENCES singers(id)
)