import React from 'react';
import ReactDOM from 'react-dom'
import 'bootstrap/dist/css/bootstrap.css';
import {BrowserRouter} from "react-router-dom";
import {Route, Switch} from "react-router";
import {FourOhFour} from "./pages/FourOhFour";
import {Signup} from "./pages/Signup";
import {Home} from "./pages/home/Home";
import {NavBar} from "./shared/components/Header";

const Routing = () => (
	<>
		<BrowserRouter>
			<Switch>
				<Route component ={Home}/>
				<Route component={FourOhFour}/>

			</Switch>
		</BrowserRouter>
	</>
);
ReactDOM.render(<Routing/>, document.querySelector('#root'));