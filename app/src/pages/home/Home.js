import ButtonToolbar from "react-bootstrap/ButtonToolbar";
import Container from "react-bootstrap/es/Container";
import Row from "react-bootstrap/Row";
import React, {useEffect} from 'react';
import Col from "react-bootstrap/Col"
import '../../index.css';


import '../../index.css';
import {useSelector, useDispatch} from "react-redux";
import {getAllLocations, getAllUsers} from "../../shared/actions/get-locations";
import {LocationModal} from "../locations/LocationModal";
import {httpConfig} from "../../shared/utils/http-config";
import {LocationCardComponent} from "./LocationCard";
export const Home = () => {

// returns the users store from Redux and assigns it to the users variable
	const locations = useSelector(state => state.locations ? state.locations : []);

	console.log(locations);

	// assigns useDispatch reference to the dispatch variable for later use.
	const dispatch = useDispatch();



	// Define the side effects that will occur in the application.
	function sideEffects() {
		// The dispatch function takes actions as arguments to make changes to the store/redux.
		dispatch(getAllLocations());

	}

	// Declare any inputs that will be used by functions that are declared in sideEffects.
	const sideEffectInputs = [];

	function compareDates(locations) {
		locations.sort((a, b) => {
				if(a.locationDate > b.locationDate) {
					return -1
				}
				if(a.locationDate < b.locationDate) {
					return 1
				}
				return 0;
			}
			);
		return locations;
	}

	/**
	 * Pass both sideEffects and sideEffectInputs to useEffect.
	 * useEffect is what handles rerendering of components when sideEffects resolve.
	 * E.g when a network request to an api has completed and there is new data to display on the dom.
	 */
	useEffect(sideEffects, sideEffectInputs);

	return (
		<Container id="home">
				<Row id="reel-time">
						<h2 className="float-right  col-lg-7 col-sm-12">Reel Time</h2>
						<ButtonToolbar className="float-right col-lg-5 col-sm-12">
							<LocationModal/>
						</ButtonToolbar>
				</Row>



			<Container id="cards">
				<>
					{/*<CardColumns>*/}

						{compareDates(locations).map(location => (
							<LocationCardComponent location={location}/>
						))}

					{/*</CardColumns>*/}
				</>
			</Container>
		</Container>

	)
};