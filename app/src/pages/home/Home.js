import React from "react"
import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Button from "react-bootstrap/Button";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col"
import Card from "react-bootstrap/Card";


export const Home = () => {
	return (
     <>
			<Container className="my-top-5 py-lg-5 conte" >
				<Row>
					<Col md={3}>
			<h2>Reel Time</h2>
					</Col>
					<Col md={5}>
					<ButtonToolbar className="float-right">
						<button className="btn btn-danger">Make a Scene</button>
					</ButtonToolbar>
					</Col>
				</Row>
			</Container>
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
		  </>

)
};