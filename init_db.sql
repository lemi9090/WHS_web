-- init_db.sql

CREATE DATABASE thang;
USE thang;


-- users 테이블
CREATE TABLE users (
    user_id VARCHAR(255) PRIMARY KEY,
    user_pw VARCHAR(255) NOT NULL,
    email_adr VARCHAR(255) NOT NULL
);

-- bulletin_boards 테이블
CREATE TABLE `bulletin_boards` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 게시판 종류 추가
INSERT INTO bulletin_boards (name) VALUES
('free_bulletin'),
('HIHI'),
('dict');

-- free_bulletin 테이블
CREATE TABLE `free_bulletin` (
    `id` int NOT NULL AUTO_INCREMENT,
    `subject` varchar(500) NOT NULL,
    `contents` text NOT NULL,
    `writer` varchar(50) NOT NULL,
    `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `delete_date` datetime DEFAULT NULL,
    `file_path` varchar(200) DEFAULT NULL,
    `board_id` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_free_bulletin_board` (`board_id`),
    CONSTRAINT `fk_free_bulletin_board` FOREIGN KEY (`board_id`) REFERENCES `bulletin_boards` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- comments 테이블
CREATE TABLE comments (
    id INT NOT NULL AUTO_INCREMENT,
    post_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    writer VARCHAR(50) NOT NULL,
    contents TEXT NOT NULL,
    create_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    delete_date DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    KEY fk_comments_post (post_id),
    KEY fk_comments_parent_id (parent_id),
    CONSTRAINT comments_ibfk_1 FOREIGN KEY (post_id) REFERENCES free_bulletin (id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_parent_id FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 더미 유저 추가 -- 발표때는 좀 더 그럴듯하게 바꿀게요~
INSERT INTO users (user_id, user_pw, email_adr) VALUES
('alex2024', 'qwer1234', 'alex@example.com'),
('bruce001', 'qwer1234', 'bruce@example.com'),
('carla999', 'qwer1234', 'carla@example.com'),
('daisy777', 'qwer1234', 'daisy@example.com'),
('evanx333', 'qwer1234', 'evan@example.com');


-- db2 --
-- 직원 전용 테이블 생성
CREATE TABLE employees (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    position ENUM('사원', '대리', '팀장', '부장', '차장', '사장', '회장') NOT NULL,
    account_number VARCHAR(50) NOT NULL,
    salary INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 직원 더미 데이터 --
INSERT INTO employees (name, email, position, account_number, salary, phone, address) VALUES
('홍길동', 'ceo1@company.com', '회장', '110-1234-0001', 15000000, '010-0000-0001', '서울시 종로구 1번지'),
('이사장', 'ceo2@company.com', '사장', '110-1234-0002', 12000000, '010-0000-0002', '서울시 종로구 2번지'),
('김사장', 'ceo3@company.com', '사장', '110-1234-0003', 12000000, '010-0000-0003', '서울시 종로구 3번지'),

('박차장', 'vp1@company.com', '차장', '110-1234-0004', 9500000, '010-0000-0004', '서울시 강남구 4번지'),
('이차장', 'vp2@company.com', '차장', '110-1234-0005', 9300000, '010-0000-0005', '서울시 강남구 5번지'),
('최차장', 'vp3@company.com', '차장', '110-1234-0006', 9200000, '010-0000-0006', '서울시 강남구 6번지'),
('정차장', 'vp4@company.com', '차장', '110-1234-0007', 9100000, '010-0000-0007', '서울시 강남구 7번지'),

('한부장', 'dir1@company.com', '부장', '110-1234-0008', 8000000, '010-0000-0008', '서울시 서초구 8번지'),
('윤부장', 'dir2@company.com', '부장', '110-1234-0009', 7900000, '010-0000-0009', '서울시 서초구 9번지'),
('장부장', 'dir3@company.com', '부장', '110-1234-0010', 7800000, '010-0000-0010', '서울시 서초구 10번지'),
('이부장', 'dir4@company.com', '부장', '110-1234-0011', 7700000, '010-0000-0011', '서울시 서초구 11번지'),
('조부장', 'dir5@company.com', '부장', '110-1234-0012', 7600000, '010-0000-0012', '서울시 서초구 12번지'),
('신부장', 'dir6@company.com', '부장', '110-1234-0013', 7500000, '010-0000-0013', '서울시 서초구 13번지'),
('문부장', 'dir7@company.com', '부장', '110-1234-0014', 7400000, '010-0000-0014', '서울시 서초구 14번지'),

('안팀장', 'lead1@company.com', '팀장', '110-1234-0015', 6500000, '010-0000-0015', '서울시 마포구 15번지'),
('서팀장', 'lead2@company.com', '팀장', '110-1234-0016', 6400000, '010-0000-0016', '서울시 마포구 16번지'),
('조팀장', 'lead3@company.com', '팀장', '110-1234-0017', 6300000, '010-0000-0017', '서울시 마포구 17번지'),
('노팀장', 'lead4@company.com', '팀장', '110-1234-0018', 6200000, '010-0000-0018', '서울시 마포구 18번지'),
('하팀장', 'lead5@company.com', '팀장', '110-1234-0019', 6100000, '010-0000-0019', '서울시 마포구 19번지'),

('이대리', 'staff1@company.com', '대리', '110-1234-0020', 5200000, '010-0000-0020', '서울시 송파구 20번지'),
('고대리', 'staff2@company.com', '대리', '110-1234-0021', 5100000, '010-0000-0021', '서울시 송파구 21번지'),
('김대리', 'staff3@company.com', '대리', '110-1234-0022', 5000000, '010-0000-0022', '서울시 송파구 22번지'),
('임대리', 'staff4@company.com', '대리', '110-1234-0023', 4950000, '010-0000-0023', '서울시 송파구 23번지'),
('백대리', 'staff5@company.com', '대리', '110-1234-0024', 4900000, '010-0000-0024', '서울시 송파구 24번지'),
('남대리', 'staff6@company.com', '대리', '110-1234-0025', 4800000, '010-0000-0025', '서울시 송파구 25번지'),

('한사원', 'emp1@company.com', '사원', '110-1234-0026', 4000000, '010-0000-0026', '서울시 동작구 26번지'),
('정사원', 'emp2@company.com', '사원', '110-1234-0027', 3950000, '010-0000-0027', '서울시 동작구 27번지'),
('최사원', 'emp3@company.com', '사원', '110-1234-0028', 3900000, '010-0000-0028', '서울시 동작구 28번지'),
('노사원', 'emp4@company.com', '사원', '110-1234-0029', 3850000, '010-0000-0029', '서울시 동작구 29번지'),
('진사원', 'emp5@company.com', '사원', '110-1234-0030', 3800000, '010-0000-0030', '서울시 동작구 30번지');

