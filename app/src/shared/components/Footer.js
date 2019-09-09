import React from "react";
import "bootstrap-css-only/css/bootstrap.min.css";
import "mdbreact/dist/css/mdb.css";
import { MDBCol, MDBContainer, MDBRow, MDBFooter } from "mdbreact";

export const Footer = () => {
	return (
		<div id="footer">
		<MDBFooter color="yellow" className="font-large pt-4 mt-4">
			<MDBContainer fluid className="text-center">
						<h5 className="title">ABQ On The Reel</h5>
			</MDBContainer>
			<div className="footer-copyright text-center py-3">
				<MDBContainer fluid>
					&copy; {new Date().getFullYear()} ShellShock Group
				</MDBContainer>
			</div>
		</MDBFooter>
		</div>
	);
};

export default Footer;