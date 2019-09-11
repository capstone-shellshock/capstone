import React from "react";
import {Link} from "react-router-dom";
import img from "../../shared/img/official-logo.png";

import Navbar from "react-bootstrap/Navbar";


export const NavBar = () => {
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
				</Navbar.Brand>
			</Navbar>
		</>
	)
};