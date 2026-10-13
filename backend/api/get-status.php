<?php
// =====================================================
// Get Student Status API
// GET /api/get-status.php?student_id=X
// =====================================================

header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

if ($student_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid student ID']);
    exit;
}

$conn = getDBConnection();

// Get latest student status
$query = "
    SELECT 
        s.student_id,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,
        s.student_number,
        p.program_name,
        sc.overall_cgpa,
        sc.major_cgpa,
        es.status_name,
        es.description,
        es.color_code,
        es.action_required,
        se.evaluation_date
    FROM students s
    JOIN programs p ON s.program_id = p.program_id
    JOIN student_cgpa sc ON s.student_id = sc.student_id
    LEFT JOIN student_evaluations se ON s.student_id = se.student_id 
        AND se.evaluation_id = (
            SELECT evaluation_id FROM student_evaluations 
            WHERE student_id = s.student_id 
            ORDER BY evaluation_date DESC LIMIT 1
        )
    LEFT JOIN evaluation_status es ON se.status_id = es.status_id
    WHERE s.student_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    
    // Prepare response
    $response = [
        'success' => true,
        'student' => [
            'id' => $data['student_id'],
            'name' => $data['student_name'],
            'number' => $data['student_number'],
            'program' => $data['program_name']
        ],
        'academic_standing' => [
            'overall_cgpa' => (float)$data['overall_cgpa'],
            'major_cgpa' => (float)$data['major_cgpa'],
            'status' => $data['status_name'] ?? 'Not Evaluated',
            'color' => $data['color_code'] ?? '#6b7280',
            'description' => $data['description'] ?? 'No evaluation on record',
            'action_required' => (bool)$data['action_required'],
            'last_evaluation' => $data['evaluation_date'] ?? 'Never'
        ]
    ];
    
    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Student not found'
    ]);
}

$stmt->close();
closeDBConnection($conn);

?>
