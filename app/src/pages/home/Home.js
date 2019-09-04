import React from "react"
import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Button from "react-bootstrap/Button";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col"

export const Home = () => {
	return (

		<Container>
			<Row>
				<Col md={5}>
		<h2>Reel Time</h2>
				</Col>
				<Col md={5}>
				<ButtonToolbar className="float-right">
					<button className="btn btn-light">Make a Scene</button>
				</ButtonToolbar>
				</Col>
			</Row>
		</Container>

)
};