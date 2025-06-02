-- Xbox Games Catalog Database Schema
-- Created for managing Xbox Series X|S games collection

-- Create database
CREATE DATABASE IF NOT EXISTS xbox_games_catalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE xbox_games_catalog;

-- Games table
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    cover_url VARCHAR(500) DEFAULT NULL,
    release_year YEAR NOT NULL,
    genre VARCHAR(100) NOT NULL,
    developer VARCHAR(150) NOT NULL,
    publisher VARCHAR(150) NOT NULL,
    language VARCHAR(100) DEFAULT 'Italiano',
    support_type ENUM(
        'Fisico',
        'Digitale',
        'Entrambi'
    ) DEFAULT 'Digitale',
    status ENUM(
        'Da iniziare',
        'In corso',
        'Completato'
    ) DEFAULT 'Da iniziare',
    personal_rating DECIMAL(3, 1) CHECK (
        personal_rating >= 0
        AND personal_rating <= 10
    ),
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_genre (genre),
    INDEX idx_status (status),
    INDEX idx_support_type (support_type),
    INDEX idx_release_year (release_year)
);

-- Insert sample Xbox Series X|S games
INSERT INTO
    games (
        title,
        cover_url,
        release_year,
        genre,
        developer,
        publisher,
        language,
        support_type,
        status,
        personal_rating,
        notes
    )
VALUES (
        'Halo Infinite',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co2foa.jpg',
        2021,
        'Sparatutto',
        '343 Industries',
        'Microsoft Studios',
        'Italiano',
        'Entrambi',
        'Completato',
        9.2,
        'Eccellente gameplay, grafica mozzafiato'
    ),
    (
        'Forza Horizon 5',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co2zsk.jpg',
        2021,
        'Racing',
        'Playground Games',
        'Microsoft Studios',
        'Italiano',
        'Entrambi',
        'In corso',
        9.5,
        'Il miglior gioco di guida open world'
    ),
    (
        'Gears 5',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co1z1k.jpg',
        2019,
        'Azione',
        'The Coalition',
        'Microsoft Studios',
        'Italiano',
        'Digitale',
        'Completato',
        8.8,
        'Ottima campagna single player'
    ),
    (
        'Microsoft Flight Simulator',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co1wyy.jpg',
        2020,
        'Simulazione',
        'Asobo Studio',
        'Microsoft Studios',
        'Inglese',
        'Digitale',
        'In corso',
        9.0,
        'Simulazione di volo incredibilmente realistica'
    ),
    (
        'Sea of Thieves',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co1nc4.jpg',
        2018,
        'Avventura',
        'Rare Ltd.',
        'Microsoft Studios',
        'Italiano',
        'Digitale',
        'Da iniziare',
        8.5,
        'Perfetto per giocare con gli amici'
    ),
    (
        'Cyberpunk 2077',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co2c9u.jpg',
        2020,
        'RPG',
        'CD Projekt RED',
        'CD Projekt',
        'Italiano',
        'Fisico',
        'Completato',
        7.5,
        'Buono dopo gli aggiornamenti'
    ),
    (
        'Assassins Creed Valhalla',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co2a7x.jpg',
        2020,
        'Azione/RPG',
        'Ubisoft Montreal',
        'Ubisoft',
        'Italiano',
        'Entrambi',
        'In corso',
        8.2,
        'Ambientazione vichinga molto immersiva'
    ),
    (
        'The Witcher 3: Wild Hunt',
        'https://images.igdb.com/igdb/image/upload/t_cover_big/co2nuv.jpg',
        2015,
        'RPG',
        'CD Projekt RED',
        'CD Projekt',
        'Italiano',
        'Digitale',
        'Completato',
        9.8,
        'Uno dei migliori RPG di sempre'
    );