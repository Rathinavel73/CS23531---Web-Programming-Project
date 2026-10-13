# Backend Setup & API Documentation

## Overview
The backend provides REST API endpoints for the Academic Performance Evaluation System using PHP and MySQL.

## Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache with mod_rewrite recommended)

## Installation

### 1. Configure Database
Edit `backend/config.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'academic_db');
```

### 2. Create Database
```bash
mysql -u root -p < database/schema.sql
```

### 3. File Permissions
Ensure proper read/write permissions:
```bash
chmod 755 backend/
chmod 644 backend/config.php
chmod 755 backend/api/
```

## API Endpoints

### 1. Evaluate Student
**Endpoint:** `POST /backend/api/evaluate-student.php`

**Description:** Create a new evaluation for a student

**Request:**
```json
{
    "student_id": 1,
    "academic_year": 2024,
    "notes": "Annual evaluation"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Evaluation created successfully",
    "evaluation_id": 1,
    "status_id": 1
}
```

**GET Request:** `GET /backend/api/evaluate-student.php?student_id=1`

Returns latest evaluation for a student.

---

### 2. Get Student Status
**Endpoint:** `GET /backend/api/get-status.php?student_id=1`

**Description:** Retrieve current status of a student

**Response:**
```json
{
    "success": true,
    "student": {
        "id": 1,
        "name": "John Doe",
        "number": "STU001",
        "program": "Bachelor of Science in Computer Science"
    },
    "academic_standing": {
        "overall_cgpa": 3.45,
        "major_cgpa": 3.60,
        "status": "Good Standing",
        "color": "#10b981",
        "description": "Student is in good academic standing",
        "action_required": false,
        "last_evaluation": "2024-04-05"
    }
}
```

---

### 3. Get All Students
**Endpoint:** `GET /backend/api/get-students.php`

**Description:** Retrieve list of all active students with status

**Response:**
```json
{
    "success": true,
    "count": 2,
    "students": [
        {
            "id": 1,
            "number": "STU001",
            "name": "John Doe",
            "program": "Bachelor of Science in Computer Science",
            "overall_cgpa": 3.45,
            "major_cgpa": 3.60,
            "status": "Good Standing",
            "color": "#10b981"
        },
        {
            "id": 2,
            "number": "STU002",
            "name": "Jane Smith",
            "program": "Bachelor of Science in Engineering",
            "overall_cgpa": 2.15,
            "major_cgpa": 1.95,
            "status": "Academic Warning",
            "color": "#f59e0b"
        }
    ]
}
```

---

## Evaluation Criteria

### Status Determination Logic

The system evaluates students based on their CGPA and determines status:

#### Good Standing
- Overall CGPA ≥ 3.0
- Major CGPA ≥ 2.5

#### Academic Warning
- Overall CGPA 2.0 - 2.99
- OR Major CGPA 1.5 - 2.49

#### Continue in Alternate
- Overall CGPA 1.5 - 1.99
- OR condition for alternate program

#### Dismissed from Program
- Overall CGPA < 2.0
- OR Major CGPA < 1.5

### Criteria Factors Considered
1. **Overall CGPA** - Cumulative GPA across all courses
2. **Major CGPA** - GPA for major-specific courses only
3. **Course Completion** - Whether required courses are completed
4. **Grade Trends** - Progression throughout academic career
5. **Program Requirements** - Major-specific minimums

## Database Tables

### Students
Core student information and enrollment details

### Programs
Degree programs with minimum CGPA requirements

### Courses
Course catalog with major designation

### Enrollments
Student course registrations by semester

### Grades
Letter grades, numeric scores, and grade points

### Student CGPA
Calculated CGPA values (overall and major)

### Student Evaluations
Annual evaluation records with status determination

### Evaluation Status
Status types (Good Standing, Warning, etc.)

### Status History
Tracking of status changes over time

### Academic Actions
Automated actions based on status (probation, dismissal, etc.)

## Error Handling

### Common HTTP Status Codes
- `200` - Success
- `201` - Created (new record)
- `400` - Bad Request (invalid parameters)
- `404` - Not Found (student/record not found)
- `405` - Method Not Allowed
- `500` - Server Error

### Error Response Format
```json
{
    "success": false,
    "error": "Description of error"
}
```

## Security Notes

1. **Prepared Statements** - All queries use prepared statements to prevent SQL injection
2. **Input Validation** - Validate all incoming data
3. **CORS Headers** - Set appropriate CORS headers for frontend communication
4. **Database Users** - Create dedicated database user with limited privileges
5. **Password Protection** - Change default credentials in config.php

## Performance Optimization

### Indexes Created
- Student email and program ID
- Enrollment student and course
- CGPA records
- Evaluation dates and students
- Status history tracking

### Views Available
- `student_performance_summary` - Quick student overview
- `student_course_performance` - Course history

## Sample Data

To add sample data, use SQL insert statements:

```sql
INSERT INTO programs (program_name, program_code) VALUES 
('Bachelor of Science in Computer Science', 'BSCS');

INSERT INTO students (student_number, first_name, last_name, email, program_id) VALUES 
('STU001', 'John', 'Doe', 'john@university.edu', 1);
```

## Testing

Use tools like Postman or curl to test endpoints:

```bash
# Get student status
curl "http://localhost/SC Project/backend/api/get-status.php?student_id=1"

# Get all students
curl "http://localhost/SC Project/backend/api/get-students.php"

# Create evaluation
curl -X POST http://localhost/SC Project/backend/api/evaluate-student.php \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"academic_year":2024}'
```

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check credentials in config.php
- Ensure database 'academic_db' exists
- Check user privileges

### API Returns Empty Results
- Verify student ID exists
- Check student status is 'Active'
- Confirm program is assigned

### File Not Found
- Check file paths are correct
- Verify file permissions
- Ensure backend folder is in correct location

## Future Enhancements
- Authentication and authorization
- Advanced filtering and searching
- Pagination for large datasets
- Export to CSV/PDF
- Data validation rules
- Audit logging
- Rate limiting

