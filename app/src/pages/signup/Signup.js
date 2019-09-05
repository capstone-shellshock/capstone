import React from "react";
import {SignUpForm} from "./SignUpForm";

export const Signup = () => (
	<>
		<button type="button" className="btn btn-primary" data-toggle="modal" data-target="#Signup">
			Sign Up
			</button>

			<div className="modal fade" id="Signup" tabIndex="-1" role="dialog" aria-labelledby="exampleSignupLabel"
				  aria-hidden="true">
				<div className="modal dialog" role="document">
					<div className="modal-content">
					<div className="modal-header">
						<h5 className="modal-title" id="exampleModalModal"> Sign Up</h5>
						<button type="button" className="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div className="modal-body">
					<SignUpForm/>
					</div>
				</div>
			</div>gf
		</div>
		</>
	);