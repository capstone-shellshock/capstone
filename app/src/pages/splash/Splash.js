import React, {useEffect} from "react";
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
import {SignUpForm} from "./SignUpForm";
import {SignInForm} from "./SignInForm";
import {httpConfig} from "../../shared/utils/http-config";

export const Splash = () => {
	useEffect(() => {
		httpConfig.get("/apis/earl-grey/")
	});
	return (
		<>
		<Container className="align-content-center" fluid id="Splash">
			<Row id="topRow">
				<Container className="col-lg-7 col-md-12 col-sm-12 m-4"  fluid id="justin">
					<img src={img} className="img-fluid" id="logo" alt="logo"/>
				</Container>
			</Row>
			<Container className="my-5">
				<Row>
					<Col lg={4}>
							<h4 className="text-white font-weight-bold">Crew Sign In</h4>
						<SignInForm/>
					</Col>
					<Col lg={1}>
						<Container className="justify-content-center" id="line">
						</Container>
					</Col>
						<Col lg={4}>
								<h4 className="text-white font-weight-bold">Join the Crew</h4>
							<SignUpForm/>
						</Col>
				</Row>
			</Container>
		</Container>
	</>
)
};