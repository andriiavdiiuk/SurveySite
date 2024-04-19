CREATE TABLE IF NOT EXISTS users
(
    user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email   VARCHAR(255) UNIQUE,
    password varchar(255),
    is_admin tinyint(1),
    access_only_by_url tinyint(1),
    PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS categories
(
    category_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_text TEXT,
    category_whom_this_quiz TEXT,
    category_what_you_get TEXT,
    PRIMARY KEY (category_id)
);

CREATE TABLE IF NOT EXISTS surveys
(
    survey_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id INT UNSIGNED NOT NULL,
    survey_title VARCHAR(255),
    survey_thanks_title Text,
    survey_video_url VARCHAR(255),
    PRIMARY KEY (survey_id),
    CONSTRAINT
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
            ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS questions
(
    question_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    survey_id     INT UNSIGNED NOT NULL,
    question_text VARCHAR(255),
    is_plain_text TINYINT(1),
    PRIMARY KEY (question_id),
    CONSTRAINT
        FOREIGN KEY (survey_id) REFERENCES surveys (survey_id)
            ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS offered_answers
(
    offered_answer_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    question_id         INT UNSIGNED NOT NULL,
    offered_answer_text VARCHAR(255),

    PRIMARY KEY (offered_answer_id),
    CONSTRAINT
        FOREIGN KEY (question_id) REFERENCES questions (question_id)
            ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS user_answers
(
    user_answer_id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    question_id       INT UNSIGNED NOT NULL,
    user_id           INT UNSIGNED NOT NULL,
    offered_answer_id INT UNSIGNED,
    user_answer_text  VARCHAR(255),
    PRIMARY KEY (user_answer_id),
    CONSTRAINT
        FOREIGN KEY (question_id) REFERENCES questions (question_id)
            ON DELETE CASCADE,
    CONSTRAINT
        FOREIGN KEY (user_id) REFERENCES users (user_id)
            ON DELETE CASCADE,
    CONSTRAINT
        FOREIGN KEY (offered_answer_id) REFERENCES offered_answers (offered_answer_id)
            ON DELETE CASCADE
);