import React from "react";
import Container from "react-bootstrap/es/Container";
import Card from "react-bootstrap/Card";
import CardColumns from "react-bootstrap/CardColumns";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import FormControl from "react-bootstrap/es/FormControl";
import Button from "react-bootstrap/Button";
import logo from "../../shared/img/reel.jpg";
import img from "../../shared/img/official-logo.png";
import {Image} from "react-bootstrap";
import '../../index.css';

export const Splash = () => {
	return (
		<>
		<Container fluid id="Splash">
			<Row id="topRow">
				<Container fluid id="justin">
					<img src={img} className="img-fluid" alt="logo"/>
				</Container>
			</Row>
			<Container>
				<Row>
					<Col lg={4}>
						<Form>
							<h4 className="text-white font-weight-bold">Crew Sign In</h4>
							<Form.Group controlId="formGroupEmail">
								<Form.Control type="email" placeholder="Enter email"/>
							</Form.Group>
							<Form.Group controlId="formGroupPassword">
								<Form.Control type="password" placeholder="Password"/>
							</Form.Group>
							<Button className="button">Sign In</Button>
						</Form>
					</Col>
					<Col lg={1}>
						<Container className="justify-content-center" id="line">
						</Container>
					</Col>
						<Col lg={4}>
							<Form border="dark">
								<h4 className="text-white font-weight-bold">Join the Crew</h4>
								<Form.Group controlId="formGroupUsername">
									<Form.Control type="username" placeholder="create Username"/>
								</Form.Group>
								<Form.Group controlId="formGroupEmail">
									<Form.Control type="email" placeholder="Enter email"/>
								</Form.Group>
								<Form.Group controlId="formGroupPassword">
									<Form.Control type="password" placeholder="Password"/>
								</Form.Group>
								<Form.Group controlId="formGroupRePassword">
									<Form.Control type="password" placeholder="Re-Enter Password"/>
								</Form.Group>
								<Button className="button">Join</Button>
							</Form>
						</Col>
				</Row>
			</Container>
		</Container>
	</>
)
};