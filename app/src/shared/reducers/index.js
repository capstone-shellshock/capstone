import {combineReducers} from "redux"
import locationReducer from "./location-reducer";
import profileReducer from "./profile-reducer";
import likeReducer from "./like-reducer"

export default combineReducers({
	posts: locationReducer,
	profile: profileReducer,
	likes: likeReducer
})