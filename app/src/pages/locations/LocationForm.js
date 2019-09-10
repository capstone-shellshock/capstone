import React, {useState} from "react";
import {httpConfig} from "../../shared/utils/http-config";
import * as Yup from "yup";
import {Formik} from "formik";

import {LocationFormContent} from "./LocationFormContent";
import {handleSessionTimeout} from "../../shared/misc/handle-session-timeout";

export const PostForm = () => {

	const [status, setStatus] = useState(null);

	const location = {
		locationTitle: "",
		locationContent: ""
	};

	const validator = Yup.object().shape({
		locationTitle: Yup.string()
			.required("A title is required.")
			.max(64, "No titles longer than 64 characters."),
		locationContent: Yup.string()
			.required("What are you going to post?")
			.max(2000, "2000 characters max per location."),
		locationImdbUrl: Yup.string()
			.required("What is the URL for the location?")
	});

	const submitLocation = (values, {resetForm, setStatus}) => {
		// grab jwt token to pass in headers on post request
		const headers = {
			'X-JWT-TOKEN': window.localStorage.getItem("jwt-token")
		};

		httpConfig.post("/apis/location/", values, {
			headers: headers})
			.then(reply => {
				let {message, type} = reply;
				setStatus({message, type});
				if(reply.status === 200) {
					resetForm();
					setStatus({message, type});
					/*TODO: find a better way to re-render the post component!*/
					setTimeout(() => {
						window.location.reload();
					}, 1500);
				}
				// if there's an issue with a $_SESSION mismatch with xsrf or jwt, alert user and do a sign out
				if(reply.status === 401) {
					handleSessionTimeout();
				}
			});
	};

	return (
		<>
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