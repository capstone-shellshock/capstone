import React from "react";

import {SignUpForm} from "./SignUpForm";

import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Badge from "react-bootstrap/Badge";
import {FontAwesome} from "@fortawesome/react-fontawesome";

export const Signup = () => {
	return (
		<>
			<main className="d-flex align-tems-center mh-100 my-5 my-md-0">
				<Container fluid="true" classNme="py-5">
					<Row>
						<col md={6} lg={{span: 4, offset:1}}>
							<h3>Sign Up!</h3>
							<SignUpForm/>
						</col>
						<Col md={6} lg={{span: 5, offset: 1}}>
							<h3>Privacy Notice:</h3>
							<p>This app has been created for public education purposes. <span className="font-weight-bold">Profile usernames, email addresses, and posts created here will be publicly viewable via the API</span>, so please keep this in mind before you sign up.</p>
							<p>We will never spam you, nor use any data here for nefarious purposes. Promise. But we can't promise the same for others.</p>
							<p>If you'd like to generate an anonymous private email address to use here give <a href="https://www.sharklasers.com" target="_blank">Sharklasers</a> a try!</p>
						</Col>

					</Row>
				</Container>
			</main>
			</>
			)
};


