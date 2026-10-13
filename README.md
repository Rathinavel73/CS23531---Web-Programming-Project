# Evaluation of Academic Performance System

A comprehensive web-based system for evaluating student academic performance using HTML, CSS, JavaScript frontend and MySQL backend.

## Project Overview

The Academic Performance Evaluation System automates the process of evaluating students' academic progress, determining their status, and triggering appropriate academic interventions based on predefined criteria.

---

## Key Features

### Evaluation of Academic Performance

The system implements comprehensive academic performance evaluation through the following components:

#### Academic Performance Evaluation Process
- **CGPA Comparison**: Evaluates academic performance by comparing CGPAs to minimum requirements specified by the student's degree program
- **Annual Review**: Evaluation process occurs annually
- **Multi-Factor Analysis**: Considers:
  - Overall CGPA (Cumulative Grade Point Average)
  - Major CGPA (Major-specific Grade Point Average)
  - Specific Course Grades

#### Outcome Determination
The system determines student status based on evaluation results:
- **Good Standing** - Meets all academic requirements
- **Academic Warning** - Below acceptable standards
- **Continue in Alternate** - Conditional continuation in alternate program
- **Dismissed from Program** - Fails to meet minimum requirements

Each outcome triggers specific actions:
- Academic probation for warning status
- Program dismissal for failed requirements
- Advisor meetings and support programs
- Tutoring and remedial course requirements

#### Evaluation Criteria
The system considers various factors for evaluation:

**Evaluation Factors:**
- Overall CGPA (Cumulative Grade Point Average)
- Major CGPA (Major-specific Grade Point Average)
- Course Completion Status
- Specific Course Grades
- Semester Performance Trends

**Status Classification Criteria:**
- **Good Standing**: Overall CGPA ≥ 3.0 AND Major CGPA ≥ 2.5
- **Academic Warning**: Overall CGPA 2.0-2.99 OR Major CGPA 1.5-2.49
- **Continue in Alternate**: Conditional based on major requirements
- **Dismissal**: Overall CGPA < 2.0 OR Major CGPA < 1.5

---

## Project Structure

```
SC Project/
├── frontend/
│   ├── index.html          # Main application page
│   ├── css/
│   │   └── style.css       # Styling and responsive design
│   └── js/
│       └── main.js         # Frontend logic and interactivity
├── backend/
│   ├── config.php          # Database configuration
│   └── api/                # API endpoints
└── database/
    └── schema.sql          # MySQL database schema
```

---

## Technology Stack

### Frontend
- **HTML5** - Semantic markup and structure
- **CSS3** - Responsive design with flexbox and grid
- **JavaScript (ES6)** - Dynamic interactivity and validation

### Backend
- **PHP** - Server-side processing
- **MySQL** - Data persistence and management

---

## Getting Started

### Prerequisites
- Web Server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern Web Browser

### Installation Steps

1. **Clone/Extract Project**
   ```bash
   cd SC Project
   ```

2. **Set Up Database**
   - Create a MySQL database named `academic_db`
   - Import the schema from `database/schema.sql`
   ```sql
   mysql -u root -p academic_db < database/schema.sql
   ```

3. **Configure Backend**
   - Edit `backend/config.php` with your database credentials
   ```php
   $host = 'localhost';
   $user = 'root';
   $password = 'your_password';
   $database = 'academic_db';
   ```

4. **Start Server**
   - Place the `frontend` folder in your web root (htdocs, www, etc.)
   - Access via: `http://localhost/SC Project/frontend/`

---

## Features in Detail

### Academic Performance Evaluation
- Automated annual evaluation of all students
- Multi-criteria assessment based on CGPA and course grades
- Transparent evaluation criteria aligned with academic regulations

### Status Determination
- Automatic classification into four status categories
- Triggered actions based on status:
  - **Warning**: Probation placement, advisor meetings
  - **Alternate**: Program change options
  - **Dismissed**: Enrollment termination

### Criteria Management
- Configurable minimum CGPA requirements
- Support for major-specific requirements
- Course-level grade considerations

---

## Database Schema (High-Level)

### Main Tables
- **Students** - Student information and demographics
- **Enrollments** - Course registrations
- **Grades** - Course grades and CGPA calculations
- **Evaluations** - Annual evaluation results
- **Status_History** - Status change tracking

---

## Future Enhancements

- [ ] Student dashboard for personal evaluation tracking
- [ ] Real-time CGPA calculator
- [ ] Email notifications for status changes
- [ ] Admin panel for criteria management
- [ ] API documentation and integration guides
- [ ] Mobile-responsive improvements
- [ ] Advanced reporting and analytics

---

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## Support

For technical support or questions regarding the evaluation criteria, contact the Academic Affairs Office.

---

## License

This project is proprietary and confidential for academic use only.

---

## Version History

- **v1.0** (2024) - Initial release with core evaluation functionality

---

## Contact & Documentation

**Academic Affairs Office**
- Email: academic@university.edu
- Phone: +1 (XXX) XXX-XXXX
- Office Hours: Monday - Friday, 9 AM - 5 PM

---

**Last Updated**: April 5, 2024
