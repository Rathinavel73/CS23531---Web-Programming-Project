<?php
// =====================================================
// Student Evaluation API
// GET /api/evaluate-student.php?student_id=X
// POST /api/evaluate-student.php
// =====================================================

header('Content-Type: application/json');
require_once '../config.php';

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
    
    if ($student_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid student ID']);
        exit;
    }
    
    $conn = getDBConnection();
    
    // Retrieve student evaluation
    $query = "
        SELECT 
            se.evaluation_id,
            s.student_id,
            CONCAT(s.first_name, ' ', s.last_name) AS student_name,
            s.student_number,
            p.program_name,
            se.evaluation_date,
            se.academic_year,
            sc.overall_cgpa,
            sc.major_cgpa,
            es.status_name,
            es.description,
            es.color_code,
            se.notes
        FROM student_evaluations se
        JOIN students s ON se.student_id = s.student_id
        JOIN programs p ON s.program_id = p.program_id
        JOIN student_cgpa sc ON s.student_id = sc.student_id
        JOIN evaluation_status es ON se.status_id = es.status_id
        WHERE s.student_id = ? 
        ORDER BY se.evaluation_date DESC 
        LIMIT 1
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Student not found'
        ]);
    }
    
    $stmt->close();
    closeDBConnection($conn);
}

// Handle POST request for new evaluation
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['student_id']) || !isset($data['academic_year'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    $conn = getDBConnection();
    
    $student_id = (int)$data['student_id'];
    $academic_year = (int)$data['academic_year'];
    
    // Get current CGPA
    $cgpa_query = "SELECT overall_cgpa, major_cgpa FROM student_cgpa WHERE student_id = ?";
    $cgpa_stmt = $conn->prepare($cgpa_query);
    $cgpa_stmt->bind_param('i', $student_id);
    $cgpa_stmt->execute();
    $cgpa_result = $cgpa_stmt->get_result();
    
    if ($cgpa_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Student CGPA record not found']);
        exit;
    }
    
    $cgpa_row = $cgpa_result->fetch_assoc();
    $overall_cgpa = (float)$cgpa_row['overall_cgpa'];
    $major_cgpa = (float)$cgpa_row['major_cgpa'];
    
    // Determine status based on evaluation criteria
    $status_id = determineStatus($conn, $student_id, $overall_cgpa, $major_cgpa);
    
    // Insert evaluation record
    $insert_query = "
        INSERT INTO student_evaluations (student_id, evaluation_date, academic_year, overall_cgpa, major_cgpa, status_id, notes)
        VALUES (?, NOW(), ?, ?, ?, ?, ?)
    ";
    
    $notes = isset($data['notes']) ? $data['notes'] : '';
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iiddis', $student_id, $academic_year, $overall_cgpa, $major_cgpa, $status_id, $notes);
    
    if ($insert_stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Evaluation created successfully',
            'evaluation_id' => $insert_stmt->insert_id,
            'status_id' => $status_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create evaluation']);
    }
    
    $cgpa_stmt->close();
    $insert_stmt->close();
    closeDBConnection($conn);
}

// Function to determine status
function determineStatus($conn, $student_id, $overall_cgpa, $major_cgpa) {
    // Get program minimum requirements
    $query = "
        SELECT min_overall_cgpa, min_major_cgpa, good_standing_cgpa, good_standing_major_cgpa
        FROM programs
        WHERE program_id = (SELECT program_id FROM students WHERE student_id = ?)
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $program = $result->fetch_assoc();
    
    // Evaluation Logic
    if ($overall_cgpa >= $program['good_standing_cgpa'] && $major_cgpa >= $program['good_standing_major_cgpa']) {
        return 1; // Good Standing
    } elseif ($overall_cgpa >= $program['min_overall_cgpa'] && $major_cgpa >= $program['min_major_cgpa']) {
        return 2; // Academic Warning
    } elseif ($overall_cgpa >= 1.5) {
        return 3; // Continue in Alternate
    } else {
        return 4; // Dismissed from Program
    }
}

?>
