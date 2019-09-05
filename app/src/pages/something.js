import FormControl from "react-bootstrap/es/FormControl";
import InputGroup from "react-bootstrap/InputGroup";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

export const Location = () => {
	return (
		<>

<InputGroup">
	<div className="input-group-prepend">
	</>
	<div className="custom-file">
		<input type="file" className="custom-file-input" aria-describedby="inputGroupFileAddon01"/>
		<label className="custom-file-label" htmlFor="inputGroupFile01">a picture of the scene</label>
	</>
</InputGroup>

			<InputGroup>
				<FormControl placeholder="What did you see? keep it reel."/>
				<input type="file" className="custom-file-input"/>
			</InputGroup>

</>