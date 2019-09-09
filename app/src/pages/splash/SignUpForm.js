import React, {useState} from 'react';
import {httpConfig} from "../../shared/utils/http-config.js";
import * as Yup from "yup";
import {Formik} from "formik";
import {Redirect} from "react-router";

import {SignUpFormContent} from "./SignUpFormContent";

export const SignUpForm = () => {

	// state variable to handle redirect to posts page on sign in
	const [toHome, setToHome] = useState(null);

	const signUp = {
		profileEmail: "",
		profilePassword: "",
		profilePasswordConfirm: "",
		profileUsername: "",
	};

	const [status, setStatus] = useState(null);
	const validator = Yup.object().shape({
		profileEmail: Yup.string()
			.email("Email must be a valid email")
			.required("Email is required"),
		profilePassword: Yup.string()
			.required("Password is required")
			.min(8, "Password must be at least eight characters long"),
		profilePasswordConfirm: Yup.string()
			.required("Password confirm is required")
			.min(8, "Password must be at least eight characters long"),
		profileUsername: Yup.string()
			.required("Profile username is required")
	});

	const submitSignUp = (values, {resetForm, setStatus}) => {
		httpConfig.post("/apis/sign-up/", values)
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
			{toHome ? <Redirect to="/home/" /> : null}

			<Formik
				initialValues={signUp}
				onSubmit={submitSignUp}
				validationSchema={validator}
			>
				{SignUpFormContent}
			</Formik>
		</>
	)
};