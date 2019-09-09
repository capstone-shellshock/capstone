import {httpConfig} from "../utils/http-config";
import _ from "lodash";
import {getProfileByProfileId} from "./get-profile";

export const getAllLocations = () => async dispatch => {
	const {data} = await httpConfig('/apis/location/');
	dispatch({type: "GET_ALL_LOCATIONS", payload: data })
};
