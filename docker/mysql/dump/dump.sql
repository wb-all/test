CREATE TABLE comments
(
    id         INT AUTO_INCREMENT NOT NULL,
    parent_id  INT DEFAULT NULL,
    topic_id   INT      NOT NULL,
    body       text     NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    INDEX      idx_topic_id (topic_id),
    INDEX      idx_parent_id (parent_id)
) DEFAULT CHARACTER SET utf8mb4
    COLLATE `utf8mb4_unicode_ci`
    ENGINE = InnoDB;

ALTER TABLE comments ADD CONSTRAINT fk_parent_id FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE;