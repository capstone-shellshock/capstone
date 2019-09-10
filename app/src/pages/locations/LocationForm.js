import React, {useState} from 'react';
import {httpConfig} from "../../shared/utils/http-config.js";
import {Formik} from "formik/dist/index";
import * as Yup from "yup";
import {Location} from "./LocationFormContent";
import {Redirect} from "react-router";

export const LocationForm = () => {

	// state variable to handle redirect to posts page on sign in
	const [toHome, setToHome] = useState(null);

	const  validator = Yup.object().shape({
		locationTitle: Yup.string()
			.require("email must be a valid email"),
		locationImdbUrl: Yup.string()
			.required("Password is required")

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
		httpConfig.post("/apis/location/", values)
			.then(reply => {
				let {message, type} = reply;
				if(reply.status === 200 && reply.headers["x-jwt-token"]) {
					window.localStorage.removeItem("jwt-token");
					window.localStorage.setItem("jwt-token", reply.headers["x-jwt-token"]);
					resetForm();
					setTimeout(() => {
						setToHome(true);
					}, 1500);
				}
				setStatus({message, type});
			});
	};

	return (
		<>
			{/* redirect user to posts page on sign in */}
			{toHome ? <Redirect to="/home" /> : null}

			<Formik
				initialValues={location}
				onSubmit={submitLocation}
				validationSchema={validator}
			>
				{LocationFormContent}
			</Formik>
		</>
	)
};