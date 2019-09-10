import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Button from "react-bootstrap/Button";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import React, {useEffect} from 'react';
import Col from "react-bootstrap/Col"

import Card from "react-bootstrap/Card";
import '../../index.css';
import Modal from "react-bootstrap/Modal";
import Location, {LocationFormContent} from "../locations/LocationFormContent"
import Form from "react-bootstrap/Form";
import InputGroup from "react-bootstrap/InputGroup";
import FormControl from "react-bootstrap/es/FormControl";
import {useSelector, useDispatch} from "react-redux";
import {LocationCard} from "./LocationCard";
import {getAllLocations, getAllUsers} from "../../shared/actions/get-locations";
import {CardColumns} from "react-bootstrap";

export const Home = () => {

// returns the users store from Redux and assigns it to the users variable
	const locations = useSelector(state => state.locations ? state.locations : []);

	console.log(locations);

	// assigns useDispatch reference to the dispatch variable for later use.
	const dispatch = useDispatch();


	// Define the side effects that will occur in the application.
	// E.G code that handles dispatches to redux, API requests, or timers.
	function sideEffects() {
		// The dispatch function takes actions as arguments to make changes to the store/redux.
		dispatch(getAllLocations())
	}

	// Declare any inputs that will be used by functions that are declared in sideEffects.
	const sideEffectInputs = [];

	/**
	 * Pass both sideEffects and sideEffectInputs to useEffect.
	 * useEffect is what handles rerendering of components when sideEffects resolve.
	 * E.g when a network request to an api has completed and there is new data to display on the dom.
	 */
	useEffect(sideEffects, sideEffectInputs);

	return (
		<Container id="home">
			<Container className="margin">
				<Row>
					<Col md={3} className="float-right">
						<h2 className="p-3">Reel Time</h2>
					</Col>
					<Col md={7}>
						<ButtonToolbar className="float-right">
							<button className="btn btn-warning">Make a Scene</button>
						</ButtonToolbar>
					</Col>
				</Row>
			</Container>


			<Container>
				<>
					{/*<CardColumns>*/}

						{locations.map(location => (
							<Card className="card text-center" key={location.locationId}>
								<div className="card-body">
									<Card className="text-left">
										<Card.Header>JALLovesTheABQFilmScene</Card.Header>
										<Card.Body>
											<Card.Title>{location.locationTitle}</Card.Title>
											<Card.Text>{location.locationAddress}</Card.Text>
											<Card.Text>{location.locationText}</Card.Text>
											<Card.Text><a href={location.locationImdbUrl}>IMDB</a></Card.Text>
											<Card.Img/>
										</Card.Body>
									</Card>
								</div>
							</Card>
						))}

					{/*</CardColumns>*/}
				</>
			</Container>
		</Container>

	)
};