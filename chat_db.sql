/* Chat DB SQL Script
 * Written by    : Georgi D. Sotirov <gdsotirov@dir.bg>
 * Last modified : $Date: 2005/10/09 09:04:12 $
 * Designed for  : MySQL 3.23 and above
 * Conformance   : SQL-92 (SQL-2)
 */

/* Table   : Administrators
 * Purpose : Data for the chat administrators.
 */
CREATE TABLE administrators (
    AdminID     INT /*!UNSIGNED */ UNIQUE NOT NULL /*!AUTO_INCREMENT*/,
    Username    VARCHAR(32)        UNIQUE NOT NULL,
    Password    VARCHAR(64)               NOT NULL,
    AdmName     VARCHAR(96)               NOT NULL,
    Email       VARCHAR(255)              NOT NULL,
    Phone       VARCHAR(32)               NOT NULL,
    LLDate      DATE                      NOT NULL DEFAULT '0000-00-00',
    LLTime      TIME                      NOT NULL DEFAULT '00:00:00',
    LLHost      CHAR(15)                  NOT NULL DEFAULT '000.000.000.000',
    ModDate     DATE                      NOT NULL DEFAULT '0000-00-00',
    ModTime     TIME                      NOT NULL DEFAULT '00:00:00',
    ModByID     INT /*!UNSIGNED*/         NOT NULL DEFAULT 0,
    PRIMARY KEY (AdminID),
    INDEX (ModByID)
);

INSERT INTO administrators (Username,Password,AdmName,Email,Phone,ModDate,ModTime) VALUES ("root", password('chatadmin'), "Georgi D. Sotirov", "gdsotirov@dir.bg", "+35988371817", CURDATE(), CURTIME());

/* Table   : Colors
 * Purpose : Data for the available colors for messages.
 */
CREATE TABLE colors (
    ColorID     INT /*!UNSIGNED */ UNIQUE NOT NULL /*! AUTO_INCREMENT */,
    ClrName     VARCHAR(16)        UNIQUE NOT NULL,
    Red         SMALLINT /*!UNSIGNED*/    NOT NULL,
    Green       SMALLINT /*!UNSIGNED*/    NOT NULL,
    Blue        SMALLINT /*!UNSIGNED*/    NOT NULL,
    ModDate     DATE                      NOT NULL DEFAULT '0000-00-00',
    ModTime     TIME                      NOT NULL DEFAULT '00:00:00',
    AdminID     INT /*!UNSIGNED*/         NOT NULL DEFAULT 1,
    PRIMARY KEY (ColorID),
    INDEX (AdminID)
);

INSERT INTO colors (ClrName,Red,Green,Blue,ModDate,ModTime) VALUES ("Black", 0, 0, 0, CURDATE(), CURTIME());
INSERT INTO colors (ClrName,Red,Green,Blue,ModDate,ModTime) VALUES ("Red", 255, 0, 0, CURDATE(), CURTIME());
INSERT INTO colors (ClrName,Red,Green,Blue,ModDate,ModTime) VALUES ("Green", 0, 255, 0, CURDATE(), CURTIME());
INSERT INTO colors (ClrName,Red,Green,Blue,ModDate,ModTime) VALUES ("Blue", 0, 0, 255, CURDATE(), CURTIME());

/* Table   : Rooms
 * Purpose : Data for the rooms in the chat.
 */
CREATE TABLE rooms (
    RoomID      INT /*!UNSIGNED */ UNIQUE NOT NULL /*! AUTO_INCREMENT */,
    RoomName    VARCHAR(16)        UNIQUE NOT NULL,
    Descr       VARCHAR(255)              NOT NULL,
    ModDate     DATE                      NOT NULL DEFAULT '0000-00-00',
    ModTime     TIME                      NOT NULL DEFAULT '00:00:00',
    AdminID     INT /*!UNSIGNED*/         NOT NULL DEFAULT 1,
    PRIMARY KEY (RoomID),
    INDEX (AdminID)
);

INSERT INTO rooms (RoomName,Descr,ModDate,ModTime) VALUES ("Public", "Public chat room", CURDATE(), CURTIME());

/* Table   : Users
 * Purpose : Data for the users who can access the chat.
 */
CREATE TABLE users (
    UserID      INT /*!UNSIGNED */ UNIQUE NOT NULL /*!AUTO_INCREMENT */,
    Username    VARCHAR(32)        UNIQUE NOT NULL,
    Password    VARCHAR(64)               NOT NULL,
    Nickname    VARCHAR(32)        UNIQUE NOT NULL,
    UsrName     VARCHAR(96)               NOT NULL,
    Email       VARCHAR(255)              NOT NULL DEFAULT 'no@mail',
    Teacher     CHAR(1)                   NOT NULL DEFAULT '0',
    ColorID     INT /*!UNSIGNED*/         NOT NULL DEFAULT 1,
    Active      CHAR(1)                   NOT NULL DEFAULT '0',
    LLDate      DATE                      NOT NULL DEFAULT '0000-00-00',
    LLTime      TIME                      NOT NULL DEFAULT '00:00:00',
    LLHost      VARCHAR(16)               NOT NULL DEFAULT '000.000.000.000',
    ModDate     DATE                      NOT NULL DEFAULT '0000-00-00',
    ModTime     TIME                      NOT NULL DEFAULT '00:00:00',
    AdminID     INT /*!UNSIGNED*/         NOT NULL DEFAULT 1,
    PRIMARY KEY (UserID),
    INDEX (ColorID),
    INDEX (Active),
    INDEX (AdminID)
);

INSERT INTO users (Username,Password,Nickname,UsrName,Email,Teacher,ColorID,Active,ModDate,ModTime) VALUES ("chatbot", password('chatbot'), "ChatBot", "Chat Bot", "gdsotirov@dir.bg", '1', 2, '0', CURDATE(), CURTIME());

/* Table   : Messages
 * Purpose : Warehouse for the chat messages.
 */
CREATE TABLE messages (
    MessageID   INT /*!UNSIGNED*/  UNIQUE NOT NULL /*! AUTO_INCREMENT */,
    PostDate    DATE                      NOT NULL DEFAULT '0000-00-00',
    PostTime    TIME                      NOT NULL DEFAULT '00:00:00',
    RoomID      INT /*!UNSIGNED*/         NOT NULL,
    AuthorID    INT /*!UNSIGNED*/         NOT NULL,
    RecipientID INT UNSIGNED              NOT NULL,
    Message     VARCHAR(255)              NOT NULL DEFAULT '',
    PRIMARY KEY (MessageID),
    INDEX (RoomID),
    INDEX (AuthorID),
    INDEX (RecipientID)
);

INSERT INTO messages (PostDate,PostTime,RoomID,AuthorID,RecipientID,Message) VALUES (CURDATE(), CURTIME(), 1, 1, 0, "Wellcome to CHAT!");

