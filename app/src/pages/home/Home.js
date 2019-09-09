import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Button from "react-bootstrap/Button";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import React, {useState} from 'react';
import Col from "react-bootstrap/Col"

import Card from "react-bootstrap/Card";
import '../../index.css';
import Modal from "react-bootstrap/Modal";
import Location from "../locations/Locations"
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import FormControl from "react-bootstrap/es/FormControl";

export const Home = () => {

	const [show, setShow] = useState(false);
	const handleClose = () => setShow(false);
	const handleShow = () => setShow(true);


	return (
     <Container id="home">
			<Container className="margin" >
				<Row>
					<Col md={3} className="float-right">
			<h2 className="p-3">Reel Time</h2>
					</Col>
					<Col md={7}>
					<ButtonToolbar className="float-right">
						<button onClick={handleShow} className="btn button">Make a Scene</button>
					</ButtonToolbar>
					</Col>
				</Row>
			</Container>

		  {/* Modal Window / Edit Post Form */}
		  <Modal show={show} onHide={handleClose} centered>
			  <Modal.Header closeButton>
				  <Modal.Title>Make a Scene</Modal.Title>
			  </Modal.Header>
			  <Modal.Body><Form>
				  <Form.Group>
					  <Container>
						  <InputGroup>
							  <FormControl placeholder="Project Title"/>
						  </InputGroup>
						  <br/>
						  <InputGroup>
							  <FormControl placeholder="Address (if you have it)"/>
						  </InputGroup>
						  <br/>
						  <InputGroup>
							  <FormControl placeholder="Imdb Url"/>
						  </InputGroup>
						  <InputGroup className="my-4">
							  <FormControl as="textarea" placeholder="What did you see? keep it reel."/>
						  </InputGroup>
						  <div className="input-group">
							  <div className="input-group-prepend">
    <span className="input-group-text" id="inputGroupFileAddon01">
      Upload
    </span>
							  </div>
							  <div className="custom-file">
								  <input
									  type="file"
									  className="custom-file-input"
									  id="inputGroupFile01"
									  aria-describedby="inputGroupFileAddon01"
								  />
								  <label className="custom-file-label" htmlFor="inputGroupFile01">
									  Choose Picture
								  </label>
							  </div>
						  </div>
					  </Container>
				  </Form.Group>
			  </Form></Modal.Body>
			  <Modal.Footer>
				  <Button variant="secondary" onClick={handleClose}>
					  Cancel
				  </Button>
				  <Button variant="primary" onClick={handleClose}>
					  Save Changes
				  </Button>
			  </Modal.Footer>
		  </Modal>

		  <Container>
		  <Card className="card text-center">
			  <div className="card-body">
				  <Card className="text-left">
					  <Card.Header>Username</Card.Header>
					  <Card.Body>
						  <Card.Title>Special title treatment</Card.Title>
						  <Card.Text>
							  With supporting text below as a natural lead-in to additional content.
						  </Card.Text>
					  </Card.Body>
				  </Card>
			  </div>
		  </Card>
	  </Container>
		  </Container>

)
};