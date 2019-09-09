import React, {useState} from 'react';
import {httpConfig} from "../../../misc/http-config";
import * as Yup from "yup";
import {Formik} from "formik";

import {SignUpFormContent} from "./SignUpFormContent";

export const SignUpForm = () => {
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

	const submitSignUp = (values, {resetForm}) => {
		httpConfig.post("/apis/sign-up/", values)
			.then(reply => {
				let {message, type} = reply;
				setStatus({message, type});
				if(reply.status === 200) {
					resetForm();
				}
			})
	};

	return (
		<Formik
			onSubmit={submitSignUp}
			initialValues={signUp}
			validationSchema={validator}
			>
			{signUpFormContent}
		</Formik>
	)
};