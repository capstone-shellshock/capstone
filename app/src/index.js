import React from 'react';
import ReactDOM from 'react-dom'
import 'bootstrap/dist/css/bootstrap.css';
import {BrowserRouter} from "react-router-dom";
import {Route, Switch} from "react-router";

import {Home} from "./pages/home/Home";
import {FourOhFour} from "./pages/four-oh-four/FourOhFour";
import {Footer} from "./shared/components/Footer"
import {About} from "./pages/about/About";
import {NavBar} from "./shared/components/Header";
import {Location} from "./pages/locations/Locations";
import {Splash} from "./pages/splash/Splash";
import './index.css';


const Routing = () => (
	<>
		<BrowserRouter>
			<Switch>
				<Route exact path="/home" component={Home}/>
				<Route exact path="/about" component={About}/>
				<Route exact path="/" component={Splash}/>
				<Route component={FourOhFour}/>
			</Switch>
		</BrowserRouter>
	</>
);
ReactDOM.render(<Routing/>, document.querySelector('#root'));