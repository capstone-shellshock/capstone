import React from "react";
import Container from "react-bootstrap/es/Container";
import Card from "react-bootstrap/Card";
import CardColumns from "react-bootstrap/CardColumns";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import FormControl from "react-bootstrap/es/FormControl";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal"

export const LocationFormContent = (props) => {

	const {
		submitStatus,
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
			<Container>
				<Modal.Dialog>
					<button type="button" className="btn btn-warning" data-toggle="modal" data-target="#locationModal"/>
					<Modal.Header closeButton>
						<Modal.Title>Make a Scene</Modal.Title>
					</Modal.Header>
							<Form>
								<Form.Group>
									<Container>
									<InputGroup>
										<FormControl
														 placeholder="Location Title"
														 id="locationTitle"
														 onChange={handleChange}
														 onBlur={handleBlur}
														 type="text"
														 value={values.locationTitle}
										/>
									</InputGroup>

										{
											errors.locationTitle && touched.locationTitle && (
												<div className="alert alert-danger">
													{errors.locationTitle}
												</div>
											)
										}

									<br/>
									<InputGroup>
										<FormControl
														 placeholder="Address (if you have it)"
														 id="locationAddress"
														 onChange={handleChange}
														 onBlur={handleBlur}
														 type="text"
														 value={values.locationAddress}
										/>
									</InputGroup>

										{
											errors.locationAddress && touched.locationAddress && (
												<div className="alert alert-danger">
													{errors.locationAddress}
												</div>
											)
										}

									<br/>
									<InputGroup>
										<FormControl
														 placeholder="Imdb Url"
														 id="locationImdbUrl"
														 onChange={handleChange}
														 onBlur={handleBlur}
														 type="text"
														 value={values.locationImdbUrl}
										/>
									</InputGroup>

										{
											errors.locationImdbUrl && touched.locationImdbUrl && (
												<div className="alert alert-danger">
													{errors.locationImdbUrl}
												</div>
											)
										}

									<InputGroup className="my-4">
										<FormControl as="textarea"
														 placeholder="What did you see? keep it reel."
														 id="locationText"
														 onChange={handleChange}
														 onBlur={handleBlur}
														 type="text"
														 value={values.locationText}
										/>
									</InputGroup>

										{
											errors.locationText && touched.locationText && (
												<div className="alert alert-danger">
													{errors.locationText}
												</div>
											)
										}

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
													Choose Picture
												</label>
											</div>
										</div>
									</Container>
								</Form.Group>
							</Form>
					<Modal.Footer>
						<InputGroup.Append>
						<Button className="button">Post your scene</Button>
						</InputGroup.Append>
					</Modal.Footer>
				</Modal.Dialog>
			</Container>
		</>
	)};