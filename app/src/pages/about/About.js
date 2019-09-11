import React from "react";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Container from "react-bootstrap/es/Container";
import photo from "../../shared/img/IMG_3485.jpg";
import {Image} from "react-bootstrap";
import {Footer} from "../../shared/components/Footer";
import {Header} from "../../shared/components/Header";



export const About = () => {
	return (
		<>
			<Container fluid>
				<Header/>
			<Container className="my-top-5 py-lg-5 content" id="aboutTop" >
						<h1>About ABQ On The Reel</h1>
				<p>ABQ The Reel was created and developed by a group of outstanding human beings, also known as Annalise, Lariah, and Justin! ABQ On The Reel is a social media based application that allows users to upload photos and information regarding the many film projects going on in the Albuquerque area. Users can also interact with a map to find locations of hundreds of film and television projects including Breaking Bad, In Plain Sight, and Hamlet 2. </p>
			</Container>
			<Container className="{photo}">
				<Image src={photo} className="img-fluid" />
			</Container>
				<Footer/>
			</Container>
		</>
	)};