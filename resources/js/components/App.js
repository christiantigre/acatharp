import React from 'react'
import ReactDOM from 'react-dom'
import {  BrowserRouter as Router, Switch, Route} from 'react-router-dom';
import Login from '../components/Login'
import Register from '../components/Register'
import SelectAuth from '../pages/selectAuth'


ReactDOM.render(
	<Router>
	    <Switch>
	    <Route exact path='/' component={Login}/>
	    <Route path='/login' component={Login}/>
	    <Route path='/register' component={Register}/>
	    <Route path='/selectAuth' component={SelectAuth}/>
	</Switch>
	</Router>,
    document.getElementById('app')
);