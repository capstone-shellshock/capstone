export default (state = [], action) => {
	switch(action.type) {
		case "GET_ALL_LOCATIONS":
			return action.payload;
		default:
			return state;
	}
}