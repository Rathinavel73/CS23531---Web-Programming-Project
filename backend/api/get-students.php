<?php
// =====================================================
// Get All Students API
// GET /api/get-students.php
// =====================================================

header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$conn = getDBConnection();

// Get all students with their current status
$query = "
    SELECT 
        s.student_id,
        s.student_number,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,
        p.program_name,
        sc.overall_cgpa,
        sc.major_cgpa,
        COALESCE(es.status_name, 'Not Evaluated') AS status,
        COALESCE(es.color_code, '#6b7280') AS color
    FROM students s
    LEFT JOIN programs p ON s.program_id = p.program_id
    LEFT JOIN student_cgpa sc ON s.student_id = sc.student_id
    LEFT JOIN (
        SELECT student_id, status_id 
        FROM student_evaluations 
        WHERE evaluation_date = (
            SELECT MAX(evaluation_date) 
            FROM student_evaluations se2 
            WHERE se2.student_id = student_evaluations.student_id
        )
    ) latest_eval ON s.student_id = latest_eval.student_id
    LEFT JOIN evaluation_status es ON latest_eval.status_id = es.status_id
    WHERE s.status = 'Active'
    ORDER BY s.last_name, s.first_name
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'id' => (int)$row['student_id'],
            'number' => $row['student_number'],
            'name' => $row['student_name'],
            'program' => $row['program_name'],
            'overall_cgpa' => (float)$row['overall_cgpa'],
            'major_cgpa' => (float)$row['major_cgpa'],
            'status' => $row['status'],
            'color' => $row['color']
        ];
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => count($students),
        'students' => $students
    ]);
} else {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'count' => 0,
        'students' => []
    ]);
}

closeDBConnection($conn);

?>
