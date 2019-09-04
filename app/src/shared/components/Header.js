import React from "react";
import {Link} from "react-router-dom";

import Navbar from "react-bootstrap/Navbar";


export const NavBar = () => {
	return (
		<>
			<Navbar bg="dark" fixed="top" variant="dark">
				<Navbar.Brand href="#home">
					<img
						src="../img/film-reel.png"
						alt="Film Reel"
						width="50"
						height="50"
						className="d-inline-block align-top"
					/>
					{'ABQ On the Reel'}
				</Navbar.Brand>
			</Navbar>
		</>
	)
};