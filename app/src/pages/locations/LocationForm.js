import React, {useState} from 'react';
import {httpConfig} from "../../shared/utils/http-config.js";
import {Formik} from "formik/dist/index";
import * as Yup from "yup";
import {LocationFormContent} from "./LocationFormContent";
import {Redirect} from "react-router";
import {handleSessionTimeout} from "../../shared/misc/handle-session-timeout";
import {Image} from 'cloudinary-react'


export const LocationForm = () => {

	// state variable to handle redirect to posts page on sign in
	const [toHome, setToHome] = useState(null);

	const  validator = Yup.object().shape({
		locationTitle: Yup.string()
			.required("Title is required"),
		locationImdbUrl: Yup.string()
			.required("IMDb URL is required")

	});

	//the initial values object defines what the request payload is.
	const signIn = {
		locationTitle: "",
		locationAddress: "",
		locationImdbUrl:"",
		locationText:"",
		locationImageCloudinaryUrl:""
	};

	const submitLocation = (values, {resetForm, setStatus}) => {
		//grab jwt token to pass in headers on post request
		const headers = {
			'X-JWT-TOKEN': window.localStorage.getItem("jwt-token")
		};

		httpConfig.post("/apis/location/",values, {
			headers: headers})
			.then(reply => {
				let {message, type} = reply;
				setStatus({message, type});
				if(reply.status === 200) {
					resetForm();
					setStatus({message, type});
					setTimeout(() => {
						window.location.reload();
					}, 1500);
				}
				//if there's an issue with a $_SESSION minmatch with xsrf of jwt, alert user and do a sign out
				if(reply.status === 401) {
					handleSessionTimeout();}
			})
	};

	return (
		<>
			{/* redirect user to posts page on sign in */}
			{toHome ? <Redirect to="/home" /> : null}

			<Formik
				initialValues={Location}
				onSubmit={submitLocation}
				validationSchema={validator}
			>
				{LocationFormContent}
			</Formik>
		</>
	)
};