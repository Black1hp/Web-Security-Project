import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { Card, Button, Row, Col, Alert, Spinner, Badge, Tab, Tabs, Table, ListGroup } from 'react-bootstrap';

const CourseDetail = () => {
  const { courseId } = useParams();
  const navigate = useNavigate();
  const [course, setCourse] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');
  const [prerequisites, setPrerequisites] = useState([]);
  const [professor, setProfessor] = useState(null);
  const [syllabus, setSyllabus] = useState(null);
  const [waitlistPosition, setWaitlistPosition] = useState(null);

  useEffect(() => {
    fetchCourseDetails();
  }, [courseId]);

  const fetchCourseDetails = () => {
    setLoading(true);
    setError(null);

    axios.get(`/api/courses/${courseId}`)
      .then(response => {
        setCourse(response.data.data);
        
        // Fetch prerequisites
        return axios.get(`/api/courses/${courseId}/prerequisites`);
      })
      .then(response => {
        setPrerequisites(response.data.data);
        
        // Fetch professor details if available
        if (course?.professor_id) {
          return axios.get(`/api/users/${course.professor_id}`);
        }
        return Promise.resolve(null);
      })
      .then(response => {
        if (response) {
          setProfessor(response.data.data);
        }
        
        // Fetch syllabus if available
        return axios.get(`/api/courses/${courseId}/syllabus`);
      })
      .then(response => {
        setSyllabus(response.data.data);
        
        // Check waitlist position if user is waitlisted
        if (course?.is_waitlisted) {
          return axios.get(`/api/courses/${courseId}/waitlist-position`);
        }
        return Promise.resolve(null);
      })
      .then(response => {
        if (response) {
          setWaitlistPosition(response.data.position);
        }
        setLoading(false);
      })
      .catch(error => {
        console.error('Error fetching course details:', error);
        setError('Failed to load course details. Please try again later.');
        setLoading(false);
      });
  };

  const registerForCourse = () => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/register`)
      .then(response => {
        setSuccessMessage('Successfully registered for the course!');
        // Refresh course details
        fetchCourseDetails();
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

  const joinWaitlist = () => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/waitlist`)
      .then(response => {
        setSuccessMessage('Successfully joined the waitlist!');
        // Refresh course details
        fetchCourseDetails();
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

  const dropCourse = () => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/drop`)
      .then(response => {
        setSuccessMessage('Successfully dropped the course.');
        // Refresh course details
        fetchCourseDetails();
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

  const leaveWaitlist = () => {
    setLoading(true);
    axios.post(`/api/courses/${courseId}/waitlist/leave`)
      .then(response => {
        setSuccessMessage('Successfully left the waitlist.');
        // Refresh course details
        fetchCourseDetails();
      })
      .catch(error => {
        console.error('Error leaving waitlist:', error);
        if (error.response && error.response.data && error.response.data.message) {
          setError(error.response.data.message);
        } else {
          setError('Failed to leave the waitlist. Please try again later.');
        }
        setLoading(false);
      });
  };

  if (loading && !course) {
    return (
      <div className="text-center my-5">
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      </div>
    );
  }

  if (error && !course) {
    return (
      <Alert variant="danger">
        {error}
        <div className="mt-3">
          <Button variant="secondary" onClick={() => navigate('/courses')}>
            Back to Course List
          </Button>
        </div>
      </Alert>
    );
  }

  if (!course) {
    return (
      <Alert variant="warning">
        Course not found.
        <div className="mt-3">
          <Button variant="secondary" onClick={() => navigate('/courses')}>
            Back to Course List
          </Button>
        </div>
      </Alert>
    );
  }

  return (
    <div className="course-detail-container">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Course Details</h2>
        <Button variant="secondary" onClick={() => navigate('/courses')}>
          Back to Course List
        </Button>
      </div>

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
        <Card.Header className="d-flex justify-content-between align-items-center">
          <div>
            <h3>
              {course.code} - {course.title}
              {course.is_full && (
                <Badge bg="warning" className="ms-2">Full</Badge>
              )}
              {!course.is_registration_open && (
                <Badge bg="secondary" className="ms-2">Closed</Badge>
              )}
            </h3>
          </div>
          <div>
            <Badge bg="info" className="me-2">{course.credits} Credits</Badge>
            <Badge bg="primary">{course.semester}</Badge>
          </div>
        </Card.Header>
        <Card.Body>
          <Row>
            <Col md={8}>
              <Tabs defaultActiveKey="details" className="mb-4">
                <Tab eventKey="details" title="Details">
                  <Card.Text className="mt-3">{course.description}</Card.Text>
                  <Table className="mt-4">
                    <tbody>
                      <tr>
                        <th>Department</th>
                        <td>{course.department?.name}</td>
                      </tr>
                      <tr>
                        <th>Schedule</th>
                        <td>{course.meeting_days} {course.start_time} - {course.end_time}</td>
                      </tr>
                      <tr>
                        <th>Location</th>
                        <td>{course.location}</td>
                      </tr>
                      <tr>
                        <th>Available Seats</th>
                        <td>{course.available_seats} / {course.capacity}</td>
                      </tr>
                      <tr>
                        <th>Professor</th>
                        <td>{professor ? `${professor.title} ${professor.first_name} ${professor.last_name}` : 'TBA'}</td>
                      </tr>
                      <tr>
                        <th>Registration Period</th>
                        <td>
                          {new Date(course.registration_start_date).toLocaleDateString()} - 
                          {new Date(course.registration_end_date).toLocaleDateString()}
                        </td>
                      </tr>
                    </tbody>
                  </Table>
                </Tab>
                
                <Tab eventKey="prerequisites" title="Prerequisites">
                  {prerequisites.length === 0 ? (
                    <Alert variant="info" className="mt-3">
                      This course has no prerequisites.
                    </Alert>
                  ) : (
                    <ListGroup className="mt-3">
                      {prerequisites.map(prereq => (
                        <ListGroup.Item key={prereq.id}>
                          <div className="d-flex justify-content-between align-items-center">
                            <div>
                              <strong>{prereq.code}</strong> - {prereq.title}
                            </div>
                            <div>
                              {prereq.is_completed ? (
                                <Badge bg="success">Completed</Badge>
                              ) : (
                                <Badge bg="danger">Not Completed</Badge>
                              )}
                            </div>
                          </div>
                        </ListGroup.Item>
                      ))}
                    </ListGroup>
                  )}
                </Tab>
                
                <Tab eventKey="syllabus" title="Syllabus">
                  {syllabus ? (
                    <div className="mt-3">
                      <h4>Course Syllabus</h4>
                      <div dangerouslySetInnerHTML={{ __html: syllabus.content }} />
                      
                      {syllabus.file_url && (
                        <div className="mt-3">
                          <Button 
                            variant="outline-primary" 
                            href={syllabus.file_url}
                            target="_blank"
                          >
                            Download Syllabus PDF
                          </Button>
                        </div>
                      )}
                    </div>
                  ) : (
                    <Alert variant="info" className="mt-3">
                      Syllabus is not available for this course yet.
                    </Alert>
                  )}
                </Tab>
              </Tabs>
            </Col>
            
            <Col md={4}>
              <Card className="mb-3">
                <Card.Header>Registration Status</Card.Header>
                <Card.Body>
                  {course.is_enrolled ? (
                    <div className="text-center">
                      <Badge bg="success" className="p-2 mb-3 d-block">
                        Currently Enrolled
                      </Badge>
                      <Button 
                        variant="danger" 
                        onClick={dropCourse}
                        disabled={loading}
                      >
                        {loading ? (
                          <Spinner animation="border" size="sm" />
                        ) : (
                          'Drop Course'
                        )}
                      </Button>
                    </div>
                  ) : course.is_waitlisted ? (
                    <div className="text-center">
                      <Badge bg="warning" className="p-2 mb-3 d-block">
                        On Waitlist (Position: {waitlistPosition || 'Unknown'})
                      </Badge>
                      <Button 
                        variant="outline-danger" 
                        onClick={leaveWaitlist}
                        disabled={loading}
                      >
                        {loading ? (
                          <Spinner animation="border" size="sm" />
                        ) : (
                          'Leave Waitlist'
                        )}
                      </Button>
                    </div>
                  ) : course.is_full ? (
                    <div className="text-center">
                      <Alert variant="warning">
                        This course is currently full.
                      </Alert>
                      <Button 
                        variant="warning" 
                        onClick={joinWaitlist}
                        disabled={!course.is_registration_open || loading}
                      >
                        {loading ? (
                          <Spinner animation="border" size="sm" />
                        ) : (
                          'Join Waitlist'
                        )}
                      </Button>
                    </div>
                  ) : (
                    <div className="text-center">
                      <Alert variant={course.is_registration_open ? 'info' : 'secondary'}>
                        {course.is_registration_open 
                          ? 'Registration is open for this course.' 
                          : 'Registration is not currently open for this course.'}
                      </Alert>
                      <Button 
                        variant="primary" 
                        onClick={registerForCourse}
                        disabled={!course.is_registration_open || loading}
                      >
                        {loading ? (
                          <Spinner animation="border" size="sm" />
                        ) : (
                          'Register'
                        )}
                      </Button>
                    </div>
                  )}
                </Card.Body>
              </Card>
              
              {prerequisites.length > 0 && !prerequisites.every(p => p.is_completed) && (
                <Alert variant="danger">
                  <strong>Warning:</strong> You have not completed all prerequisites for this course.
                </Alert>
              )}
              
              {professor && (
                <Card className="mb-3">
                  <Card.Header>Professor Information</Card.Header>
                  <Card.Body>
                    <div className="text-center mb-3">
                      {professor.profile_image ? (
                        <img 
                          src={professor.profile_image} 
                          alt={`${professor.first_name} ${professor.last_name}`}
                          className="rounded-circle"
                          style={{ width: '100px', height: '100px', objectFit: 'cover' }}
                        />
                      ) : (
                        <div 
                          className="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white"
                          style={{ width: '100px', height: '100px', margin: '0 auto' }}
                        >
                          {professor.first_name[0]}{professor.last_name[0]}
                        </div>
                      )}
                    </div>
                    <h5 className="text-center">
                      {professor.title} {professor.first_name} {professor.last_name}
                    </h5>
                    <p className="text-center">{professor.department?.name}</p>
                    {professor.email && (
                      <p className="text-center">
                        <a href={`mailto:${professor.email}`}>{professor.email}</a>
                      </p>
                    )}
                    {professor.office_hours && (
                      <div className="mt-3">
                        <h6>Office Hours</h6>
                        <p>{professor.office_hours}</p>
                      </div>
                    )}
                  </Card.Body>
                </Card>
              )}
            </Col>
          </Row>
        </Card.Body>
      </Card>
    </div>
  );
};

export default CourseDetail;
