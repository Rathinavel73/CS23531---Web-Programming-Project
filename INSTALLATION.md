# Installation & Quick Start Guide

## Project Overview

**Evaluation of Academic Performance System** is a comprehensive web-based platform for evaluating student academic performance using:
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+

---

## Quick Start (5 Minutes)

### Step 1: Install Database
```bash
mysql -u root -p
CREATE DATABASE academic_db;
EXIT;
mysql -u root -p academic_db < database/schema.sql
```

### Step 2: Configure Backend
Edit `backend/config.php`:
```php
define('DB_USER', 'root');
define('DB_PASS', 'your_password');  // Update with your MySQL password
```

### Step 3: Start Web Server
```bash
# Using PHP built-in server
cd d:\SC Project\frontend
php -S localhost:8000

# OR place in Apache htdocs and visit:
# http://localhost/SC Project/frontend/
```

### Step 4: Access Application
Open browser and navigate to:
```
http://localhost:8000
```

---

## Full Project Structure

```
SC Project/
│
├── frontend/                      # Frontend application
│   ├── index.html                # Main page with content
│   ├── css/
│   │   └── style.css             # Responsive styling
│   └── js/
│       └── main.js               # Frontend interactivity
│
├── backend/                       # API & Business Logic
│   ├── config.php                # Database configuration
│   └── api/
│       ├── evaluate-student.php   # Evaluation endpoint
│       ├── get-status.php         # Student status endpoint
│       └── get-students.php       # List students endpoint
│
├── database/
│   └── schema.sql                # MySQL database schema
│
├── README.md                      # Project documentation
└── BACKEND_SETUP.md              # Backend API documentation
```

---

## Key Features

### Evaluation of Academic Performance

The system evaluates student performance through:

#### 1. Annual Evaluation Process
- Evaluates CGPAs against program requirements
- Considers Overall CGPA and Major CGPA
- Analyzes specific course grades

#### 2. Outcome Determination
Students receive status classification:
- **✅ Good Standing** - Meets all requirements
- **⚠️ Academic Warning** - Below acceptable standards
- **↔️ Continue in Alternate** - Conditional continuation
- **❌ Dismissed from Program** - Fails requirements

#### 3. Evaluation Criteria
Factors considered:
- Overall CGPA (min. 2.0 for continuation, 3.0 for good standing)
- Major CGPA (min. 1.5 for continuation, 2.5 for good standing)
- Course completion status
- Individual course grades
- Semester performance trends

---

## API Endpoints

### Get Student Status
```
GET /backend/api/get-status.php?student_id=1
```
Returns: Current academic status and CGPA

### List All Students
```
GET /backend/api/get-students.php
```
Returns: All active students with status

### Evaluate Student
```
POST /backend/api/evaluate-student.php
Body: {
    "student_id": 1,
    "academic_year": 2024,
    "notes": "Annual evaluation"
}
```

---

## Database Setup Details

### Main Tables
- **students** - Student information
- **programs** - Degree programs with requirements
- **courses** - Course catalog
- **enrollments** - Student registrations
- **grades** - Course grades
- **student_cgpa** - Calculated CGPA
- **student_evaluations** - Evaluation records
- **evaluation_status** - Status types
- **academic_actions** - Triggered actions

### Automatic Indexes & Views
- Fast lookups by student, program, course
- Performance summaries view
- Course history view

---

## Configuration Options

### Bachelor's Programs (Editable)
Edit `database/schema.sql` to adjust:
- **Good Standing CGPA:** 3.0 overall, 2.5 major
- **Minimum CGPA:** 2.0 overall, 1.5 major
- **Program requirements**

### Status Actions
Configure automatic triggers in database:
- Academic probation emails
- Warning letter generation
- Dismissal notifications

---

## Browser Compatibility

✅ Supported Browsers:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

Responsive design works on:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

---

## Troubleshooting

### Issue: "Connection failed" error
**Solution:** 
- Verify MySQL is running
- Check credentials in `backend/config.php`
- Ensure database exists: `SHOW DATABASES;`

### Issue: Blank page displayed
**Solution:**
- Check browser console for JavaScript errors
- Verify all files are in correct paths
- Clear browser cache (Ctrl+Shift+Del)

### Issue: API returns 404
**Solution:**
- Verify student exists in database
- Check student_id parameter
- Confirm database tables are populated

---

## Sample Data

Insert test student for evaluation:

```sql
-- Add test program
INSERT INTO programs (program_name, program_code) VALUES 
('Bachelor of Science in Computer Science', 'BSCS');

-- Add test student
INSERT INTO students (student_number, first_name, last_name, email, program_id, enrollment_date)
VALUES ('STU001', 'John', 'Doe', 'john@university.edu', 1, '2023-09-01');

-- Add test CGPA
INSERT INTO student_cgpa (student_id, overall_cgpa, major_cgpa, total_credits, major_credits)
VALUES (1, 3.45, 3.60, 60, 24);

-- Create evaluation
INSERT INTO student_evaluations (student_id, evaluation_date, academic_year, overall_cgpa, major_cgpa, status_id)
VALUES (1, NOW(), 2024, 3.45, 3.60, 1);
```

---

## Security Checklist

- [ ] Change default MySQL password
- [ ] Update credentials in `config.php`
- [ ] Set file permissions (chmod 755 for directories)
- [ ] Use HTTPS in production
- [ ] Implement user authentication
- [ ] Add input validation

---

## Performance Tips

1. **Database:** Create indexes on frequently queried columns ✅ (Done)
2. **Frontend:** Enable gzip compression in web server
3. **API:** Implement query caching for student lists
4. **Images:** Optimize if adding logo/images
5. **CSS:** Minify for production

---

## Deployment Checklist

### Before Going Live
- [ ] Test all API endpoints
- [ ] Populate database with real data
- [ ] Set up HTTPS/SSL certificate
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up database backups
- [ ] Test on multiple browsers
- [ ] Configure error logging
- [ ] Set up monitoring/alerts

### Production Settings
```php
// In config.php for production:
define('DISPLAY_ERRORS', false);
error_reporting(0);
ini_set('log_errors', 1);
```

---

## Support & Contact

**Technical Support:** academic@university.edu
**Documentation:** See README.md and BACKEND_SETUP.md
**Version:** 1.0.0
**Last Updated:** April 5, 2024

---

## Next Steps

1. ✅ Complete Quick Start setup
2. 📚 Review BACKEND_SETUP.md for API details
3. 🗄️ Populate database with student data
4. 🧪 Test evaluation endpoints
5. 🚀 Deploy to web server
6. 📊 Monitor and optimize performance

---
