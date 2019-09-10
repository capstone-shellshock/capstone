import React from 'react';
import {Route} from 'react-router';
import Card from "react-bootstrap/Card";

const LocationCardComponent = (props) => {


	//this component takes advantage of the render prop pattern
	return (
		<>

				{/*<Card className="card text-center">*/}
				{/*<div className="card-body">*/}
				{/*		<Card className="text-left">*/}
				{/*			<Card.Header>{location.locationProfileId}</Card.Header>*/}
				{/*			<Card.Body>*/}
				{/*				<Card.Title>{location.locationTitle}</Card.Title>*/}
				{/*				<Card.Text>{location.locationText}</Card.Text>*/}
				{/*				<Card.Text>{location.locationImdbUrl}</Card.Text>*/}
				{/*				<Card.Img src="{location.locationCloudinaryUrl}" alt="location image"/>*/}
				{/*			</Card.Body>*/}
				{/*		</Card>*/}
				{/*</div>*/}
				{/*</Card>*/}
			</>
	)
};

export const LocationCard = (LocationCardComponent);