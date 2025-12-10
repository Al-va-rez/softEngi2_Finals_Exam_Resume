CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    password TEXT NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE aboutme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    interests TEXT,
    inspiration TEXT,
    life_motto TEXT,
    bucket_list TEXT,
    strengths TEXT,
    weaknesses TEXT,
    talents TEXT,
    greatest_fear TEXT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE techskills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert'),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    img_src TEXT,
    title VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    github_link TEXT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(100) NOT NULL,
    year_start INT,
    year_end INT,
    description TEXT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    img_src TEXT,
    title VARCHAR(100) NOT NULL,
    year_obtained INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);