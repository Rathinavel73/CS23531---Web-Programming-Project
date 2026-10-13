-- =====================================================
-- Academic Performance Evaluation System
-- MySQL Database Schema
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS academic_db;
USE academic_db;

-- =====================================================
-- Students Table
-- =====================================================
CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    student_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    date_of_birth DATE,
    enrollment_date DATE NOT NULL,
    program_id INT NOT NULL,
    status ENUM('Active', 'Inactive', 'Graduated', 'Dismissed') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(program_id)
);

-- =====================================================
-- Programs Table
-- =====================================================
CREATE TABLE programs (
    program_id INT PRIMARY KEY AUTO_INCREMENT,
    program_name VARCHAR(100) NOT NULL,
    program_code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    min_overall_cgpa DECIMAL(3, 2) DEFAULT 2.00,
    min_major_cgpa DECIMAL(3, 2) DEFAULT 1.50,
    good_standing_cgpa DECIMAL(3, 2) DEFAULT 3.00,
    good_standing_major_cgpa DECIMAL(3, 2) DEFAULT 2.50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- Courses Table
-- =====================================================
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    program_id INT NOT NULL,
    is_major_course BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(program_id)
);

-- =====================================================
-- Enrollments Table
-- =====================================================
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    academic_year INT NOT NULL,
    status ENUM('Enrolled', 'Completed', 'Dropped', 'Transferred') DEFAULT 'Enrolled',
    enrollment_date DATE NOT NULL,
    UNIQUE KEY unique_enrollment (student_id, course_id, academic_year, semester),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- =====================================================
-- Grades Table
-- =====================================================
CREATE TABLE grades (
    grade_id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    letter_grade VARCHAR(2),
    numeric_grade DECIMAL(5, 2),
    grade_points DECIMAL(3, 2),
    grading_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(enrollment_id)
);

-- =====================================================
-- Student CGPA Table
-- =====================================================
CREATE TABLE student_cgpa (
    cgpa_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL UNIQUE,
    overall_cgpa DECIMAL(3, 2) DEFAULT 0.00,
    major_cgpa DECIMAL(3, 2) DEFAULT 0.00,
    total_credits INT DEFAULT 0,
    major_credits INT DEFAULT 0,
    last_calculated DATE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- =====================================================
-- Evaluation Status Table
-- =====================================================
CREATE TABLE evaluation_status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    color_code VARCHAR(7),
    action_required BOOLEAN DEFAULT FALSE
);

-- =====================================================
-- Student Evaluations Table
-- =====================================================
CREATE TABLE student_evaluations (
    evaluation_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    evaluation_date DATE NOT NULL,
    academic_year INT NOT NULL,
    overall_cgpa DECIMAL(3, 2),
    major_cgpa DECIMAL(3, 2),
    status_id INT NOT NULL,
    notes TEXT,
    evaluated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (status_id) REFERENCES evaluation_status(status_id)
);

-- =====================================================
-- Status History Table
-- =====================================================
CREATE TABLE status_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    previous_status_id INT,
    new_status_id INT NOT NULL,
    change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT,
    changed_by VARCHAR(100),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (previous_status_id) REFERENCES evaluation_status(status_id),
    FOREIGN KEY (new_status_id) REFERENCES evaluation_status(status_id)
);

-- =====================================================
-- Academic Actions Table
-- =====================================================
CREATE TABLE academic_actions (
    action_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    action_type ENUM('Probation', 'Warning Letter', 'Remedial Course', 'Program Change', 'Dismissal') NOT NULL,
    status_id INT NOT NULL,
    action_date DATE NOT NULL,
    required_by DATE,
    completed_date DATE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (status_id) REFERENCES evaluation_status(status_id)
);

-- =====================================================
-- Insert Default Evaluation Status Values
-- =====================================================
INSERT INTO evaluation_status (status_name, description, color_code, action_required) VALUES
('Good Standing', 'Student meets all academic requirements', '#10b981', FALSE),
('Academic Warning', 'Student is below acceptable standards', '#f59e0b', TRUE),
('Continue in Alternate', 'Student may continue in alternate program', '#3b82f6', TRUE),
('Dismissed from Program', 'Student does not meet minimum requirements', '#ef4444', TRUE);

-- =====================================================
-- Insert Sample Program Data
-- =====================================================
INSERT INTO programs (program_name, program_code, description, min_overall_cgpa, min_major_cgpa, good_standing_cgpa, good_standing_major_cgpa) VALUES
('Bachelor of Science in Computer Science', 'BSCS', 'Four-year Computer Science degree', 2.00, 1.50, 3.00, 2.50),
('Bachelor of Science in Engineering', 'BSENG', 'Four-year Engineering degree', 2.00, 1.50, 3.00, 2.50),
('Bachelor of Arts in Business', 'BAB', 'Four-year Business Administration degree', 2.00, 1.50, 3.00, 2.50);

-- =====================================================
-- Create Indexes for Performance
-- =====================================================
CREATE INDEX idx_student_email ON students(email);
CREATE INDEX idx_student_program ON students(program_id);
CREATE INDEX idx_enrollment_student ON enrollments(student_id);
CREATE INDEX idx_enrollment_course ON enrollments(course_id);
CREATE INDEX idx_grades_enrollment ON grades(enrollment_id);
CREATE INDEX idx_cgpa_student ON student_cgpa(student_id);
CREATE INDEX idx_evaluation_student ON student_evaluations(student_id);
CREATE INDEX idx_evaluation_date ON student_evaluations(evaluation_date);
CREATE INDEX idx_history_student ON status_history(student_id);
CREATE INDEX idx_actions_student ON academic_actions(student_id);

-- =====================================================
-- Create Views for Easy Data Retrieval
-- =====================================================

-- Student Performance Summary View
CREATE VIEW student_performance_summary AS
SELECT 
    s.student_id,
    s.student_number,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    p.program_name,
    sc.overall_cgpa,
    sc.major_cgpa,
    es.status_name AS current_status,
    es.color_code
FROM students s
JOIN programs p ON s.program_id = p.program_id
JOIN student_cgpa sc ON s.student_id = sc.student_id
LEFT JOIN (
    SELECT student_id, status_id 
    FROM student_evaluations 
    WHERE evaluation_date = (SELECT MAX(evaluation_date) FROM student_evaluations se WHERE se.student_id = student_evaluations.student_id)
) latest_eval ON s.student_id = latest_eval.student_id
LEFT JOIN evaluation_status es ON latest_eval.status_id = es.status_id;

-- Course Performance View
CREATE VIEW student_course_performance AS
SELECT 
    s.student_id,
    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
    c.course_code,
    c.course_name,
    g.letter_grade,
    g.numeric_grade,
    g.grade_points,
    e.academic_year,
    e.semester
FROM students s
JOIN enrollments e ON s.student_id = e.student_id
JOIN courses c ON e.course_id = c.course_id
LEFT JOIN grades g ON e.enrollment_id = g.enrollment_id
ORDER BY e.academic_year DESC, e.semester DESC;

-- =====================================================
-- Database Setup Complete
-- =====================================================
