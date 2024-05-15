import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Card, Button, Form, Row, Col, Alert, Spinner, Badge } from 'react-bootstrap';

const CourseList = () => {
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [filters, setFilters] = useState({
    department_id: '',
    semester: '',
    registration_open: true,
  });
  const [departments, setDepartments] = useState([]);
  const [semesters, setSemesters] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');

  useEffect(() => {
    // Fetch departments for filter dropdown
    axios.get('/api/departments')
      .then(response => {
        setDepartments(response.data.data);
      })
      .catch(error => {
        console.error('Error fetching departments:', error);
      });

    // Set available semesters (in a real app, this would be fetched from the API)
    setSemesters(['Fall 2025', 'Spring 2026', 'Summer 2026']);

    // Fetch courses with initial filters
    fetchCourses();
  }, []);

  const fetchCourses = () => {
    setLoading(true);
    setError(null);

    // Build query parameters from filters
    const params = Object.entries(filters)
      .filter(([_, value]) => value !== '')
      .reduce((acc, [key, value]) => {
        acc[key] = value;
        return acc;
      }, {});

    axios.get('/api/courses', { params })
      .then(response => {
        setCourses(response.data.data);
        setLoading(false);
      })
      .catch(error => {
        console.error('Error fetching courses:', error);
        setError('Failed to load courses. Please try again later.');
        setLoading(false);
      });
  };

  const handleFilterChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFilters({
      ...filters,
      [name]: type === 'checkbox' ? checked : value
    });
  };

  const applyFilters = (e) => {
    e.preventDefault();
    fetchCourses();
  };

  const registerForCourse = (courseId) => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/register`)
      .then(response => {
        setSuccessMessage('Successfully registered for the course!');
        // Refresh course list to update availability
        fetchCourses();
      })
      .catch(error => {
        console.error('Error registering for course:', error);
        if (error.response && error.response.data && error.response.data.message) {
          setError(error.response.data.message);
        } else {
          setError('Failed to register for the course. Please try again later.');
        }
        setLoading(false);
      });
  };

  const joinWaitlist = (courseId) => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/waitlist`)
      .then(response => {
        setSuccessMessage('Successfully joined the waitlist!');
        // Refresh course list to update status
        fetchCourses();
      })
      .catch(error => {
        console.error('Error joining waitlist:', error);
        if (error.response && error.response.data && error.response.data.message) {
          setError(error.response.data.message);
        } else {
          setError('Failed to join the waitlist. Please try again later.');
        }
        setLoading(false);
      });
  };

  return (
    <div className="course-list-container">
      <h2 className="mb-4">Course Registration</h2>

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
        <Card.Header>Filter Courses</Card.Header>
        <Card.Body>
          <Form onSubmit={applyFilters}>
            <Row>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Department</Form.Label>
                  <Form.Select
                    name="department_id"
                    value={filters.department_id}
                    onChange={handleFilterChange}
                  >
                    <option value="">All Departments</option>
                    {departments.map(dept => (
                      <option key={dept.id} value={dept.id}>
                        {dept.name}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>Semester</Form.Label>
                  <Form.Select
                    name="semester"
                    value={filters.semester}
                    onChange={handleFilterChange}
                  >
                    <option value="">All Semesters</option>
                    {semesters.map(semester => (
                      <option key={semester} value={semester}>
                        {semester}
                      </option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={4}>
                <Form.Group className="mb-3">
                  <Form.Label>&nbsp;</Form.Label>
                  <div className="d-flex align-items-center">
                    <Form.Check
                      type="checkbox"
                      name="registration_open"
                      id="registration-open"
                      label="Open for Registration"
                      checked={filters.registration_open}
                      onChange={handleFilterChange}
                      className="me-3"
                    />
                  </div>
                </Form.Group>
              </Col>
            </Row>
            <Button variant="primary" type="submit">
              Apply Filters
            </Button>
          </Form>
        </Card.Body>
      </Card>

      {loading ? (
        <div className="text-center my-5">
          <Spinner animation="border" role="status">
            <span className="visually-hidden">Loading...</span>
          </Spinner>
        </div>
      ) : (
        <>
          <h3 className="mb-3">Available Courses</h3>
          {courses.length === 0 ? (
            <Alert variant="info">
              No courses found matching your criteria.
            </Alert>
          ) : (
            courses.map(course => (
              <Card key={course.id} className="mb-3">
                <Card.Header className="d-flex justify-content-between align-items-center">
                  <div>
                    <strong>{course.code}</strong> - {course.title}
                  </div>
                  <div>
                    {course.is_full && (
                      <Badge bg="warning" className="me-2">Full</Badge>
                    )}
                    {!course.is_registration_open && (
                      <Badge bg="secondary" className="me-2">Closed</Badge>
                    )}
                    <Badge bg="info">{course.credits} Credits</Badge>
                  </div>
                </Card.Header>
                <Card.Body>
                  <Row>
                    <Col md={8}>
                      <p>{course.description}</p>
                      <p><strong>Department:</strong> {course.department?.name}</p>
                      <p><strong>Semester:</strong> {course.semester}</p>
                      <p><strong>Schedule:</strong> {course.meeting_days} {course.start_time} - {course.end_time}</p>
                      <p><strong>Location:</strong> {course.location}</p>
                      <p><strong>Available Seats:</strong> {course.available_seats} / {course.capacity}</p>
                    </Col>
                    <Col md={4} className="d-flex flex-column justify-content-center">
                      {course.is_enrolled ? (
                        <Button variant="success" disabled className="mb-2">
                          Enrolled
                        </Button>
                      ) : course.is_waitlisted ? (
                        <div className="text-center">
                          <Badge bg="warning" className="p-2 mb-2">
                            Waitlisted (Position: {course.waitlist_position})
                          </Badge>
                          <Button 
                            variant="outline-danger" 
                            size="sm"
                            onClick={() => leaveWaitlist(course.id)}
                          >
                            Leave Waitlist
                          </Button>
                        </div>
                      ) : course.is_full ? (
                        <Button 
                          variant="warning" 
                          onClick={() => joinWaitlist(course.id)}
                          disabled={!course.is_registration_open}
                        >
                          Join Waitlist
                        </Button>
                      ) : (
                        <Button 
                          variant="primary" 
                          onClick={() => registerForCourse(course.id)}
                          disabled={!course.is_registration_open}
                        >
                          Register
                        </Button>
                      )}
                      <Button 
                        variant="outline-secondary" 
                        className="mt-2"
                        href={`/courses/${course.id}`}
                      >
                        View Details
                      </Button>
                    </Col>
                  </Row>
                </Card.Body>
              </Card>
            ))
          )}
        </>
      )}
    </div>
  );
};

export default CourseList;
