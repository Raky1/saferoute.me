-- -----------------------------------------------------
-- Table tb_email
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_email (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (email)
);


-- -----------------------------------------------------
-- Table tb_user_info
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_user_info (
    email_id INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender TINYINT NOT NULL DEFAULT 0,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    active TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (email_id),
    FOREIGN KEY (email_id) REFERENCES tb_email (id)
);


-- -----------------------------------------------------
-- Table tb_marker
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_marker (
    id INT NOT NULL AUTO_INCREMENT,
    img_name VARCHAR(255) NOT NULL,
    img_shadow_name VARCHAR(255),

    width SMALLINT NOT NULL,
    height SMALLINT NOT NULL,
    anchorx SMALLINT NOT NULL,
    anchory SMALLINT NOT NULL,

    shadow_width SMALLINT,
    shadow_height SMALLINT,
    shadow_anchorx SMALLINT,
    shadow_anchory SMALLINT,

    popupx SMALLINT NOT NULL,
    popupy SMALLINT NOT NULL,

    shadow TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE (img_name)
);


-- -----------------------------------------------------
-- Table tb_occurrence
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_occurrence (
    id INT NOT NULL AUTO_INCREMENT,

    road VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,

    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    occurrence_day DATE NOT NULL,
    occurrence_time TIME NOT NULL,

    reported TINYINT NOT NULL,
    aggression TINYINT NOT NULL,
    complement VARCHAR(255) NULL,

    verified TINYINT NOT NULL DEFAULT 0,
    denounced INT NOT NULL DEFAULT 0,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    marker_id INT NOT NULL,
    email_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (marker_id) REFERENCES tb_marker (id),
    FOREIGN KEY (email_id) REFERENCES tb_email (id)
);


-- -----------------------------------------------------
-- Table tb_occurrence_report
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_occurrence_report (
    id INT NOT NULL AUTO_INCREMENT,
    note VARCHAR(255) NOT NULL,
    occurrence_id INT NOT NULL,
    email_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (occurrence_id) REFERENCES tb_occurrence (id),
    FOREIGN KEY (email_id) REFERENCES tb_email (id)
);


-- -----------------------------------------------------
-- pre insert
-- -----------------------------------------------------
INSERT INTO tb_marker (img_name, width, height, anchorx, anchory, popupx, popupy)
 VALUES ('marker.png', 31, 31, 16, 16, 0, -6);
INSERT INTO tb_marker (img_name, width, height, anchorx, anchory, popupx, popupy)
 VALUES ('marker1.png', 25, 44, 13, 43, 0, -40);