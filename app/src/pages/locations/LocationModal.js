import React, {useState} from "react";
import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";
import {LocationForm} from "./LocationForm";
import {Image} from 'cloudinary-react'


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
				<Modal.Body><LocationForm/></Modal.Body>
				<Modal.Footer>
				</Modal.Footer>
			</Modal>
		</>
	);
}