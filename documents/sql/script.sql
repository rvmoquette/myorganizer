
DROP TABLE IF EXISTS user ;

CREATE TABLE user (Code_user int AUTO_INCREMENT NOT NULL,
user_Login VARCHAR,
user_Password VARCHAR,
user_Email VARCHAR,
PRIMARY KEY (Code_user) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS task ;

CREATE TABLE task (Code_task int AUTO_INCREMENT NOT NULL,
task_Name VARCHAR,
task_Date_creation DATE,
task_Description TEXT,
task_Workflow INT,
Code_user int NOT NULL,
PRIMARY KEY (Code_task) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS label ;

CREATE TABLE label (Code_label int AUTO_INCREMENT NOT NULL,
label_Name VARCHAR,
PRIMARY KEY (Code_label) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS a_task_label ;

CREATE TABLE a_task_label (Code_task int AUTO_INCREMENT NOT NULL,
Code_label int NOT NULL,
a_task_label_Link BOOL,
PRIMARY KEY (Code_task,
 Code_label) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS a_user_task ;

CREATE TABLE a_user_task (Code_user int AUTO_INCREMENT NOT NULL,
Code_task int NOT NULL,
a_user_task_Link BOOL,
PRIMARY KEY (Code_user,
 Code_task) ) ENGINE=InnoDB;
