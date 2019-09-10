import React, {useState} from "react";
import {LocationFormContent} from "./LocationFormContent";
import Container from "react-bootstrap/es/Container";
import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";

export function LocationModal() {
	const [show, setShow] = useState(false);

	const handleClose = () => setShow(false);
	const handleShow = () => setShow(true);

	return (
		<>
			<Button variant="warning" onClick={handleShow}>
				Make A Scene
			</Button>

			<Modal show={show} onHide={handleClose} size="lg"
					 aria-labelledby="contained-modal-title-vcenter"
					 centered>
				<Modal.Header closeButton>
					<Modal.Title>Make A Scene</Modal.Title>
				</Modal.Header>
				<Modal.Body>Hi</Modal.Body>
				<Modal.Footer>
					<Button variant="secondary" onClick={handleClose}>
						Close
					</Button>
					<Button variant="primary" onClick={handleClose}>
						Submit
					</Button>
				</Modal.Footer>
			</Modal>
		</>
	);
}