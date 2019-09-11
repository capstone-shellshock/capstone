import React from 'react';
import {Route} from 'react-router';
import Card from "react-bootstrap/Card";
import Container from "react-bootstrap/es/Container";
import {Home} from "./Home";
import {Image} from 'cloudinary-react'


export const LocationCardComponent = ({location}) => {

	const formatDate = new Intl.DateTimeFormat('en-US', {
		day: 'numeric',
		month: 'numeric',
		year: '2-digit',
		hour: 'numeric',
		minute: 'numeric',
		second: '2-digit',
		timeZoneName: 'short'
	});

	//this component takes advantage of the render prop pattern
	return (
		<>
			{/*<CardColumns>*/}


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
		</>
	)
};

