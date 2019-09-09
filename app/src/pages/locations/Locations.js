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
import Modal from "react-bootstrap/Modal";
import {library} from "@fortawesome/fontawesome-svg-core";

export const Location = () => {
	return (
		<>
			<Container>
				<Modal.Dialog>
					<Modal.Header closeButton>
						<Modal.Title>Make a Scene</Modal.Title>
					</Modal.Header>
							<Form>
								<Form.Group>
									<Container>
									<InputGroup>
										<FormControl placeholder="Project Title"/>
									</InputGroup>
									<br/>
									<InputGroup>
										<FormControl placeholder="Address (if you have it)"/>
									</InputGroup>
									<br/>
									<InputGroup>
										<FormControl placeholder="Imdb Url"/>
									</InputGroup>
									<InputGroup className="my-4">
										<FormControl as="textarea" placeholder="What did you see? keep it reel."/>
									</InputGroup>
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