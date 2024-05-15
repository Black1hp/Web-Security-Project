import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Card, Button, Table, Alert, Spinner, Modal, Form } from 'react-bootstrap';

const CourseSchedule = () => {
  const [schedule, setSchedule] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');
  const [semester, setSemester] = useState('');
  const [semesters, setSemesters] = useState([]);
  const [showDropModal, setShowDropModal] = useState(false);
  const [courseToDropId, setCourseToDropId] = useState(null);
  const [courseToDropName, setCourseToDropName] = useState('');

  useEffect(() => {
    // Set available semesters (in a real app, this would be fetched from the API)
    setSemesters(['Fall 2025', 'Spring 2026', 'Summer 2026']);
    
    // Default to the current semester
    setSemester('Fall 2025');
    
    // Fetch the schedule
    fetchSchedule('Fall 2025');
  }, []);

  const fetchSchedule = (selectedSemester) => {
    setLoading(true);
    setError(null);

    axios.get('/api/schedule', { params: { semester: selectedSemester } })
      .then(response => {
        setSchedule(response.data.data);
        setLoading(false);
      })
      .catch(error => {
        console.error('Error fetching schedule:', error);
        setError('Failed to load schedule. Please try again later.');
        setLoading(false);
      });
  };

  const handleSemesterChange = (e) => {
    const selectedSemester = e.target.value;
    setSemester(selectedSemester);
    fetchSchedule(selectedSemester);
  };

  const confirmDropCourse = (courseId, courseCode, courseTitle) => {
    setCourseToDropId(courseId);
    setCourseToDropName(`${courseCode} - ${courseTitle}`);
    setShowDropModal(true);
  };

  const dropCourse = () => {
    setLoading(true);
    setShowDropModal(false);

    axios.post(`/api/courses/${courseToDropId}/drop`)
      .then(response => {
        setSuccessMessage(`Successfully dropped ${courseToDropName}`);
        // Refresh schedule
        fetchSchedule(semester);
      })
      .catch(error => {
        console.error('Error dropping course:', error);
        if (error.response && error.response.data && error.response.data.message) {
          setError(error.response.data.message);
        } else {
          setError('Failed to drop the course. Please try again later.');
        }
        setLoading(false);
      });
  };

  // Group courses by day for better visualization
  const groupCoursesByDay = () => {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const dayMap = {
      'M': 'Monday',
      'T': 'Tuesday',
      'W': 'Wednesday',
      'R': 'Thursday',
      'F': 'Friday',
      'S': 'Saturday',
      'U': 'Sunday'
    };

    // Initialize schedule object with empty arrays for each day
    const groupedSchedule = days.reduce((acc, day) => {
      acc[day] = [];
      return acc;
    }, {});

    // Add courses to the appropriate day(s)
    schedule.forEach(course => {
      if (course.meeting_days) {
        // Split the meeting days string into individual characters
        const meetingDays = course.meeting_days.split('');
        
        // Add the course to each day it meets
        meetingDays.forEach(day => {
          if (dayMap[day]) {
            groupedSchedule[dayMap[day]].push(course);
          }
        });
      } else {
        // If no meeting days specified, add to "Unscheduled"
        if (!groupedSchedule['Unscheduled']) {
          groupedSchedule['Unscheduled'] = [];
        }
        groupedSchedule['Unscheduled'].push(course);
      }
    });

    return groupedSchedule;
  };

  const formatTime = (timeString) => {
    if (!timeString) return 'N/A';
    
    // Convert from 24-hour format to 12-hour format
    const date = new Date(`2000-01-01T${timeString}`);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  const groupedSchedule = groupCoursesByDay();

  return (
    <div className="course-schedule-container">
      <h2 className="mb-4">My Course Schedule</h2>

      {successMessage && (
        <Alert variant="success" onClose={() => setSuccessMessage('')} dismissible>
          {successMessage}
        </Alert>
      )}

      {error && (
        <Alert variant="danger" onClose={() => setError(null)} dismissible>
          {error}
        </Alert>
      )}

      <Card className="mb-4">
        <Card.Header>
          <Form.Group>
            <Form.Label>Semester</Form.Label>
            <Form.Select
              value={semester}
              onChange={handleSemesterChange}
            >
              {semesters.map(sem => (
                <option key={sem} value={sem}>
                  {sem}
                </option>
              ))}
            </Form.Select>
          </Form.Group>
        </Card.Header>
        <Card.Body>
          {loading ? (
            <div className="text-center my-5">
              <Spinner animation="border" role="status">
                <span className="visually-hidden">Loading...</span>
              </Spinner>
            </div>
          ) : schedule.length === 0 ? (
            <Alert variant="info">
              You are not enrolled in any courses for {semester}.
            </Alert>
          ) : (
            <>
              <h4 className="mb-3">Weekly Schedule</h4>
              {Object.entries(groupedSchedule).map(([day, courses]) => (
                courses.length > 0 && (
                  <div key={day} className="mb-4">
                    <h5>{day}</h5>
                    <Table striped bordered hover>
                      <thead>
                        <tr>
                          <th>Course</th>
                          <th>Time</th>
                          <th>Location</th>
                          <th>Professor</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        {courses.map(course => (
                          <tr key={`${day}-${course.id}`}>
                            <td>
                              <strong>{course.code}</strong><br />
                              {course.title}
                            </td>
                            <td>
                              {formatTime(course.start_time)} - {formatTime(course.end_time)}
                            </td>
                            <td>{course.location || 'N/A'}</td>
                            <td>{course.professor || 'TBA'}</td>
                            <td>
                              <Button
                                variant="danger"
                                size="sm"
                                onClick={() => confirmDropCourse(course.id, course.code, course.title)}
                              >
                                Drop
                              </Button>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </Table>
                  </div>
                )
              ))}

              <h4 className="mt-4 mb-3">Course Summary</h4>
              <Table striped bordered hover>
                <thead>
                  <tr>
                    <th>Course</th>
                    <th>Department</th>
                    <th>Credits</th>
                    <th>Enrollment Date</th>
                  </tr>
                </thead>
                <tbody>
                  {schedule.map(course => (
                    <tr key={course.id}>
                      <td>
                        <strong>{course.code}</strong><br />
                        {course.title}
                      </td>
                      <td>{course.department}</td>
                      <td>{course.credits}</td>
                      <td>{new Date(course.enrollment_date).toLocaleDateString()}</td>
                    </tr>
                  ))}
                  <tr className="table-info">
                    <td colSpan="2"><strong>Total Credits</strong></td>
                    <td colSpan="2">
                      <strong>
                        {schedule.reduce((total, course) => total + course.credits, 0)}
                      </strong>
                    </td>
                  </tr>
                </tbody>
              </Table>
            </>
          )}
        </Card.Body>
      </Card>

      {/* Drop Course Confirmation Modal */}
      <Modal show={showDropModal} onHide={() => setShowDropModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Confirm Course Drop</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>Are you sure you want to drop <strong>{courseToDropName}</strong>?</p>
          <p className="text-danger">
            <strong>Warning:</strong> Dropping a course may affect your academic progress and financial aid. 
            Please consult with your academic advisor if you have any concerns.
          </p>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowDropModal(false)}>
            Cancel
          </Button>
          <Button variant="danger" onClick={dropCourse}>
            Drop Course
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default CourseSchedule;
