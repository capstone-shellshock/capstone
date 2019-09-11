import React from 'react';
import ReactDOM from 'react-dom'
import 'bootstrap/dist/css/bootstrap.css';
import {BrowserRouter} from "react-router-dom";
import {Route, Switch} from "react-router";
import thunk from "redux-thunk";
import {applyMiddleware, createStore} from "redux";
import combinedReducers from "./shared/reducers";
import {Provider} from "react-redux";
import {Home} from "./pages/home/Home";
import {FourOhFour} from "./pages/four-oh-four/FourOhFour";
import {About} from "./pages/about/About";
import {Splash} from "./pages/splash/Splash";
import './index.css';
import {LocationModal} from "./pages/locations/LocationModal";

const store = createStore(combinedReducers, applyMiddleware(thunk));

const Routing = () => (
	<>

		<Provider store={store}>
		<BrowserRouter>
			<Switch>
				<Route exact path="/home" component={Home}/>
				<Route exact path="/about" component={About}/>
				<Route exact path="/" component={Splash}/>
				<Route component={FourOhFour}/>
			</Switch>
		</BrowserRouter>
		</Provider>
	</>
);
ReactDOM.render(Routing(store), document.querySelector('#root'));