import React from "react";
import {Link} from "react-router-dom";
import img from "../../shared/img/official-logo.png";
import Navbar from "react-bootstrap/Navbar";
import {Home} from "../../pages/home/Home";
import {About} from "../../pages/about/About";
import {LinkContainer} from "react-router-bootstrap"


export const Header = () => {


	return (
		<>
			<Navbar bg="dark" fixed="top" variant="dark">
				<Navbar.Brand href="#home">
					<img
						src={img}
						alt="Film Reel"
						width="250"
						height="auto"
						className="d-inline-block align-top"
					/>

					<div className="collapse navbar-collapse" id="navbarNavDropdown">
						<ul className="navbar-nav">

							<LinkContainer exact to="/home">
								<li className="nav-item">
									<a className="nav-link" href="../../pages/home/Home.js">Home</a>
								</li>
							</LinkContainer>
							<LinkContainer to="/about">
								<li className="nav-item">
									<a className="nav-link" href="../../pages/about/About.js">About Us</a>
								</li>
							</LinkContainer>
						</ul>
					</div>

				</Navbar.Brand>
			</Navbar>
		</>
	)
};