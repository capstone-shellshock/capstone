import React from "react"
import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Button from "react-bootstrap/Button";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col"
import Card from "react-bootstrap/Card";
import '../../index.css';


export const Home = () => {
	return (
     <Container id="home">
			<Container className="margin" >
				<Row>
					<Col md={3} className="float-right">
			<h2 className="p-3">Reel Time</h2>
					</Col>
					<Col md={7}>
					<ButtonToolbar className="float-right">
						<button className="btn button">Make a Scene</button>
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
		  </Container>

)
};