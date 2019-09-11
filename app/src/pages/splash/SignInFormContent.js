import {FormDebugger} from "../components/FormDebugger.js";
import React from "react";

export const SignInFormContent = (props) => {
	const {
		status,
		values,
		errors,
		touched,
		dirty,
		isSubmitting,
		handleChange,
		handleBlur,
		handleSubmit,
		handleReset
	} = props;
	return (
		<>
			<form onSubmit={handleSubmit}>
				{/*contrilId must match what is passed to the initialValues prop*/}
				<div className="form-group">
					<label htmlFor="profileEmail" className="text-white font-weight-bold">Email</label>
					<div className="input-group">
						<div className="input-group-prepend">
							<div className="input-group-text">

							</div>

						</div>

						<input
							className="form-control"
							id="profileEmail"
							type="email"
							value={values.profileEmail}
							placeholder="Enter email"
							onChange={handleChange}
							onBlur={handleBlur}
						/>

					</div>
					{
						errors.profileEmail && touched.profileEmail && (
							<div className="alert alert-danger">
								{errors.profileEmail}
							</div>
						)
					}
				</div>
				{/*control Id must match what is defined by the initialValues object*/}
				<div className="form-group">

					<label htmlFor="profilePassword" className="text-white font-weight-bold">Password</label>

					<div className="input-group">

						<div className="input-group-prepend">

							<div className="input-group-text">
							</div>

						</div>

						<input
							id="profilePassword"
							className="form-control"
							type="password"
							placeholder="Password"
							value={values.profilePassword}
							onChange={handleChange}
							onBlur={handleBlur}
						/>

					</div>

					{errors.profilePassword && touched.profilePassword && (
						<div className="alert alert-danger">{errors.profilePassword}</div>
					)}
				</div>

				<div className="form-group">

					<button className="btn btn-primary mb-2" type="submit">Submit</button>
					<button
						
						className="btn btn-danger mb-2"
						onClick={handleReset}
						disabled={!dirty || isSubmitting}

					>Reset
					</button>

				</div>
			</form>
			{status && (<div className={status.type}>{status.message}</div>)}
		</>
	)
};
