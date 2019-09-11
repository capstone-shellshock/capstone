import {FormDebugger} from "../components/FormDebugger";
import React from "react";

export const LocationFormContent = (props) => {
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

		// locationImageCloudinaryUrl

	return (
		<>
			<form onSubmit={handleSubmit}>
				{/*controlId must match what is passed to the initialValues prop*/}
				<div className="form-group">
					<label htmlFor="locationTitle">Location Title</label>
					<div className="input-group">
						<div className="input-group-prepend">
							<div className="input-group-text">
							</div>
						</div>

						<input
						className="form-control"
						id="locationTitle"
						type="title"
						value={values.locationTitle}
						placeholder="Enter Title"
						onChange={handleChange}
						onBlur={handleBlur}
						/>
					</div>

					{
						errors.locationTitle && touched.locationTitle && (
							<div className="alert alert-danger">
								{errors.locationTitle}
							</div>
						)
					}
				</div>

				{/*controlId must match what is passed to the initialValues prop*/}
				<div className="form-group">
					<label htmlFor="locationAddress">Location Address</label>
					<div className="input-group">
						<div className="input-group-prepend">
							<div className="input-group-text">
							</div>
						</div>

						<input
							className="form-control"
							id="locationAddress"
							type="address"
							value={values.locationAddress}
							placeholder="Enter Address (if you have it)"
							onChange={handleChange}
							onBlur={handleBlur}
						/>
					</div>

					{
						errors.locationAddress && touched.locationAddress && (
							<div className="alert alert-danger">
								{errors.locationAddress}
							</div>
						)
					}
				</div>

				{/*controlId must match what is passed to the initialValues prop*/}
				<div className="form-group">
					<label htmlFor="locationImdbUrl">Location IMDb URL</label>
					<div className="input-group">
						<div className="input-group-prepend">
							<div className="input-group-text">
							</div>
						</div>

						<input
							className="form-control"
							id="locationImdbUrl"
							type="imdbUrl"
							value={values.locationImdbUrl}
							placeholder="Enter IMDb URL"
							onChange={handleChange}
							onBlur={handleBlur}
						/>
					</div>

					{
						errors.locationImdbUrl && touched.locationImdbUrl && (
							<div className="alert alert-danger">
								{errors.locationImdbUrl}
							</div>
						)
					}
				</div>

				{/*controlId must match what is passed to the initialValues prop*/}
				<div className="form-group">
					<label htmlFor="locationText">Location Text</label>
					<div className="input-group">
						<div className="input-group-prepend">
							<div className="input-group-text">
							</div>
						</div>

						<input
							className="form-control"
							id="locationText"
							type="text"
							value={values.locationText}
							placeholder="What did you see?"
							onChange={handleChange}
							onBlur={handleBlur}
						/>
					</div>

					{
						errors.locationText && touched.locationText && (
							<div className="alert alert-danger">
								{errors.locationText}
							</div>
						)
					}
				</div>


				<div className="input-group">
					<div className="input-group-prepend">
    <span className="input-group-text" id="inputGroupFileAddon01">
      Upload
    </span>
					</div>
					<div className="custom-file">
						<input
							type="file"
							className="custom-file-input"
							id="inputGroupFile01"
							aria-describedby="inputGroupFileAddon01"
						/>
						<label className="custom-file-label" htmlFor="inputGroupFile01">
							Choose file
						</label>
					</div>
				</div>

				<div className="form-group">
					<button className="btn btn-warning mb-2" type="submit">Submit</button>
					<button
								className="btn btn-danger mb-2"
								onClick={handleReset}
								disabled={!dirty || isSubmitting}
					>Reset
					</button>
				</div>
				<FormDebugger {...props} />
			</form>
			{status && (<div className={status.type} > {status.message}</div> )}
		</>
	)
};