// =====================================================
// Academic Performance Evaluation System
// CGPA Calculator & Status Determiner
// =====================================================

// Evaluation Criteria
const EVALUATION_CRITERIA = {
    goodStanding: {
        overallCGPA: 3.0,
        majorCGPA: 2.5,
        status: 'Good Standing',
        color: 'good',
        emoji: '✅'
    },
    academicWarning: {
        overallCGPA: 2.0,
        majorCGPA: 1.5,
        status: 'Academic Warning',
        color: 'warning',
        emoji: '⚠️'
    },
    alternate: {
        overallCGPA: 1.5,
        majorCGPA: 0.0,
        status: 'Continue in Alternate',
        color: 'alternate',
        emoji: '↔️'
    },
    dismissed: {
        status: 'Dismissed from Program',
        color: 'dismissed',
        emoji: '❌'
    }
};

// Student Records for Admin
let studentRecords = [];

// =====================================================
// Student Calculator Form
// =====================================================

document.getElementById('studentCalcForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const studentName = document.getElementById('studentName').value;
    const studentNumber = document.getElementById('studentNumber').value;
    const overallCGPA = parseFloat(document.getElementById('overallCGPA').value);
    const majorCGPA = parseFloat(document.getElementById('majorCGPA').value);
    
    // Validate input
    if (isNaN(overallCGPA) || isNaN(majorCGPA)) {
        alert('Please enter valid CGPA values');
        return;
    }
    
    // Determine status
    const result = determineStatus(overallCGPA, majorCGPA);
    
    // Display result
    displayStudentResult(studentName, studentNumber, overallCGPA, majorCGPA, result);
});

// Determine Student Status Based on CGPA
function determineStatus(overallCGPA, majorCGPA) {
    if (overallCGPA >= EVALUATION_CRITERIA.goodStanding.overallCGPA && 
        majorCGPA >= EVALUATION_CRITERIA.goodStanding.majorCGPA) {
        return {
            status: EVALUATION_CRITERIA.goodStanding.status,
            color: EVALUATION_CRITERIA.goodStanding.color,
            emoji: EVALUATION_CRITERIA.goodStanding.emoji,
            message: 'Excellent! You are in Good Standing. Continue maintaining your academic performance.',
            actions: []
        };
    } 
    else if (overallCGPA >= EVALUATION_CRITERIA.academicWarning.overallCGPA && 
             majorCGPA >= EVALUATION_CRITERIA.academicWarning.majorCGPA) {
        return {
            status: EVALUATION_CRITERIA.academicWarning.status,
            color: EVALUATION_CRITERIA.academicWarning.color,
            emoji: EVALUATION_CRITERIA.academicWarning.emoji,
            message: 'Your academic performance requires attention. You are below acceptable standards.',
            actions: [
                'Contact your academic advisor for guidance',
                'Consider tutoring or academic support services',
                'Review your study habits and course load'
            ]
        };
    } 
    else if (overallCGPA >= EVALUATION_CRITERIA.alternate.overallCGPA) {
        return {
            status: EVALUATION_CRITERIA.alternate.status,
            color: EVALUATION_CRITERIA.alternate.color,
            emoji: EVALUATION_CRITERIA.alternate.emoji,
            message: 'You may continue in an alternate program. Please meet with your advisor.',
            actions: [
                'Mandatory meeting with academic advisor',
                'Explore alternate program options',
                'Complete additional academic requirements'
            ]
        };
    } 
    else {
        return {
            status: EVALUATION_CRITERIA.dismissed.status,
            color: EVALUATION_CRITERIA.dismissed.color,
            emoji: EVALUATION_CRITERIA.dismissed.emoji,
            message: 'Your CGPA does not meet the minimum requirements for continuation.',
            actions: [
                'Your enrollment has been terminated',
                'Contact the registrar for appeals process',
                'Seek alternative educational paths'
            ]
        };
    }
}

// Display Student Result
function displayStudentResult(name, number, overallCGPA, majorCGPA, result) {
    document.getElementById('resultStudentName').textContent = name;
    document.getElementById('resultStudentNumber').textContent = number;
    document.getElementById('resultOverallCGPA').textContent = overallCGPA.toFixed(2);
    document.getElementById('resultMajorCGPA').textContent = majorCGPA.toFixed(2);
    
    const statusDisplay = document.getElementById('statusDisplay');
    statusDisplay.className = `status-badge ${result.color}`;
    statusDisplay.textContent = `${result.emoji} ${result.status}`;
    
    document.getElementById('statusMessage').textContent = result.message;
    
    const actionBox = document.getElementById('actionRequired');
    if (result.actions.length > 0) {
        actionBox.classList.remove('hidden');
        const actionList = document.getElementById('actionList');
        actionList.innerHTML = result.actions.map(action => `<li>${action}</li>`).join('');
    } else {
        actionBox.classList.add('hidden');
    }
    
    document.getElementById('studentResult').classList.remove('hidden');
    
    // Scroll to result
    document.getElementById('studentResult').scrollIntoView({ behavior: 'smooth' });
    
    // Save to records for admin dashboard
    studentRecords.unshift({
        name,
        number,
        overallCGPA,
        majorCGPA,
        status: result.status,
        color: result.color,
        timestamp: new Date().toLocaleString()
    });
}

// =====================================================
// Admin Dashboard Functions
// =====================================================

// Admin Form Submission
document.getElementById('adminForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const studentName = document.getElementById('adminStudentName').value;
    const studentNumber = document.getElementById('adminStudentNumber').value;
    const program = document.getElementById('adminProgram').value;
    const overallCGPA = parseFloat(document.getElementById('adminOverallCGPA').value);
    const majorCGPA = parseFloat(document.getElementById('adminMajorCGPA').value);
    
    if (isNaN(overallCGPA) || isNaN(majorCGPA)) {
        alert('Please enter valid CGPA values');
        return;
    }
    
    const result = determineStatus(overallCGPA, majorCGPA);
    
    // Add to records
    const newStudent = {
        name: studentName,
        number: studentNumber,
        program: program,
        overallCGPA: overallCGPA,
        majorCGPA: majorCGPA,
        status: result.status,
        color: result.color,
        timestamp: new Date().toLocaleString()
    };
    
    studentRecords.unshift(newStudent);
    
    // Reset form
    this.reset();
    
    // Show success message
    alert(`Student ${studentName} has been added and evaluated!`);
    
    // Refresh admin list if visible
    if (!document.getElementById('adminResult').classList.contains('hidden')) {
        displayStudentsList();
    }
});

// Load and Display All Students
function loadStudentsList() {
    displayStudentsList();
}

function displayStudentsList() {
    const adminResult = document.getElementById('adminResult');
    const studentsList = document.getElementById('studentsList');
    
    if (studentRecords.length === 0) {
        studentsList.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--text-light);">No students added yet.</p>';
    } else {
        studentsList.innerHTML = studentRecords.map((student, index) => `
            <div class="student-card">
                <h4>${student.name}</h4>
                <p><strong>ID:</strong> ${student.number}</p>
                <p><strong>Program:</strong> ${student.program || 'N/A'}</p>
                <p><strong>Overall CGPA:</strong> ${student.overallCGPA.toFixed(2)}</p>
                <p><strong>Major CGPA:</strong> ${student.majorCGPA.toFixed(2)}</p>
                <p><strong>Date:</strong> ${student.timestamp}</p>
                <div class="status-badge ${student.color}" style="margin-top: 1rem; padding: 0.75rem; font-size: 0.95rem;">
                    ${student.status}
                </div>
                <button class="btn btn-secondary" onclick="removeStudent(${index})" style="width: 100%; margin-top: 1rem;">Remove</button>
            </div>
        `).join('');
    }
    
    adminResult.classList.remove('hidden');
    adminResult.scrollIntoView({ behavior: 'smooth' });
}

// Remove Student Record
function removeStudent(index) {
    if (confirm('Are you sure you want to remove this student record?')) {
        studentRecords.splice(index, 1);
        displayStudentsList();
    }
}

// Export Results as JSON
function exportResults() {
    if (studentRecords.length === 0) {
        alert('No student records to export');
        return;
    }
    
    const dataStr = JSON.stringify(studentRecords, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `student_evaluations_${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    URL.revokeObjectURL(url);
}

// =====================================================
// Smooth Navigation
// =====================================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            const target = document.querySelector(href);
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// =====================================================
// Initialization
// =====================================================

console.log('Academic Performance Evaluation System - Initialized');
console.log('CGPA Calculator Ready');

