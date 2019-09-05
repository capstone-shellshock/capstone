import FormControl from "react-bootstrap/es/FormControl";
import InputGroup from "react-bootstrap/InputGroup";

export const Location = () => {
	return (
		<>

<div className="input-group">
	<div className="input-group-prepend">
		<span><i className="fas fa-upload"></i></span>
	</div>
	<div className="custom-file">
		<input
			type="file"
			className="custom-file-input"
			id="inputGroupFile01"
			aria-describedby="inputGroupFileAddon01"
		/>
		<label className="custom-file-label" htmlFor="inputGroupFile01">a picture of the scene</label>
	</div>
</div>

<InputGroup className="my-4">
<FormControl as="textarea" placeholder="What did you see? keep it reel."/>
	</InputGroup>
		</>