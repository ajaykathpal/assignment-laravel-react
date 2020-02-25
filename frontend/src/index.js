import React from "react";
import { render } from "react-dom";
import { BrowserRouter, Route, Switch, withRouter } from "react-router-dom";
import Home from "./Home";
import Login from "./Login";
// import Register from "./Register";
// import NotFound from "./NotFound";

import axios from "axios";
import $ from "jquery";
class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoggedIn: false,
      user: {},
      showPage: 'profile',
      isAuthenticating: true
    };

  }
  _loginUser = (email, password) => {
    $("#login-form button")
      .attr("disabled", "disabled")
      .html(
        '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>'
      );
    var formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    axios
      .post("http://localhost:8000/api/user/login/", formData)
      .then(response => {
        return response;
      })
      .then(json => {
        if (json.data.success) {
          const { name, id, email, auth_token, role, tabs_show } = json.data.data;

          let userData = {
            name,
            id,
            email,
            auth_token,
            role,
            tabs_show,
            timestamp: new Date().toString()
          };
          let appState = {
            isLoggedIn: true,
            user: userData,
            showPage: 'profile'
          };
          // save app state with user date in local storage
          localStorage["appState"] = JSON.stringify(appState);
          this.setState({
            isLoggedIn: appState.isLoggedIn,
            user: appState.user
          });
        } else alert("Login Failed! Please check your credentials and try again!");

        $("#login-form button")
          .removeAttr("disabled")
          .html("Login");
      })
      .catch(error => {
        alert(`An Error Occured! ${error}`);
        $("#login-form button")
          .removeAttr("disabled")
          .html("Login");
      });
  };

  // _registerUser = (name, email, password) => {
  //   $("#email-login-btn")
  //     .attr("disabled", "disabled")
  //     .html(
  //       '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>'
  //     );

  //   var formData = new FormData();
  //   formData.append("type", "email");
  //   formData.append("username", "usernameee");
  //   formData.append("password", password);
  //   formData.append("phone", 33322212231);
  //   formData.append("email", email);
  //   formData.append("address", "address okoko");
  //   formData.append("name", name);
  //   formData.append("id", 76);

  //   axios
  //     .post("http://localhost:8000/api/user/register", formData)
  //     .then(response => {
  //       console.log(response);
  //       return response;
  //     })
  //     .then(json => {
  //       if (json.data.success) {
  //         alert(`Registration Successful!`);
  //         const { name, id, email, auth_token } = json.data.data;
  //         let userData = {
  //           name,
  //           id,
  //           email,
  //           auth_token,
  //           timestamp: new Date().toString()
  //         };
  //         let appState = {
  //           isLoggedIn: true,
  //           user: userData
  //         };
  //         // save app state with user date in local storage
  //         localStorage["appState"] = JSON.stringify(appState);
  //         this.setState({
  //           isLoggedIn: appState.isLoggedIn,
  //           user: appState.user
  //         });
  //         // redirect home
  //         //this.props.history.push("/");
  //       } else {
  //         alert(`Registration Failed!`);
  //         $("#email-login-btn")
  //           .removeAttr("disabled")
  //           .html("Register");
  //       }
  //     })
  //     .catch(error => {
  //       alert("An Error Occured!" + error);
  //       console.log(`${formData} ${error}`);
  //       $("#email-login-btn")
  //         .removeAttr("disabled")
  //         .html("Register");
  //     });
  // };

  _logoutUser = () => {
    let appState = {
      isLoggedIn: false,
      user: {}
    };
    // save app state with user date in local storage
    localStorage["appState"] = JSON.stringify(appState);
    this.setState(appState);
  };

  _showProfile = () => {
      //select profile tab and set data in content div
      $(".tablinks")
      .removeClass("active");

      $(".tablinks.profile")
      .addClass("active");


      let appStateNew1 = {
        isLoggedIn: true,
        user: JSON.parse(localStorage["appState"]).user,
        showPage : 'profile'
      };
      // save app state with user date in local storage
      localStorage["appState"] = JSON.stringify(appStateNew1);
      this.setState(appStateNew1);
  };

  _showRolePage = () => {
    $(".tablinks")
      .removeClass("active");

      $(".tablinks.role")
      .addClass("active");

      // let appState = JSON.parse(localStorage["appState"]);

      let appStateNew2 = {
        isLoggedIn: true,
        user: JSON.parse(localStorage["appState"]).user,
        showPage : 'role'
      };
      // save app state with user date in local storage
      localStorage["appState"] = JSON.stringify(appStateNew2);
      this.setState(appStateNew2);

    // $("#login-form button")
    //   .attr("disabled", "disabled")
    //   .html(
    //     '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>'
    //   );
    // var formData = new FormData();
    // formData.append("email", email);
    // formData.append("password", password);


  };

  _showUserPage = () => {
    $(".tablinks")
      .removeClass("active");

      $(".tablinks.user")
      .addClass("active");

      let appStateNew3 = JSON.parse(localStorage["appState"]);

      appStateNew3 = {
        isLoggedIn: appStateNew3.isLoggedIn,
        user: appStateNew3.user,
        showPage : 'user'
      };
      // // save app state with user date in local storage
      localStorage["appState"] = JSON.stringify(appStateNew3);
      this.setState({showPage: 'user'});
      // alert(appStateNew3.auth_token);
      // if(appStateNew3.auth_token !== '' || (typeof appStateNew3.auth_token !== 'undefined')) {
      //   axios
      //     .get(`http://localhost:8000/api/user/list?token=${this.state.token}`)
      //     .then(response => {
      //       console.log(response);
      //       return response;
      //     })
      //     .then(json => {
      //       if (json.data.success) {
      //         this.setState({ users: json.data.data });
              
      //       } else alert("Login Failed!");
      //     })
      //     .catch(error => {
      //       // alert(`An Error Occured! ${error}`);
      //     });
      // }

  };

  componentDidMount() {
    let state = localStorage["appState"];
    if (state) {
      let AppState = JSON.parse(state);
      console.log(AppState);
      this.setState({ isLoggedIn: AppState.isLoggedIn, user: AppState, isAuthenticating: false });
    }
  };

  render() {
    if (this.state.isAuthenticating) return null;

    if (
      !this.state.isLoggedIn &&
      this.props.location.pathname !== "/login"
    ) {
      this.props.history.push("/login");
    }
    if (
      this.state.isLoggedIn &&
      this.props.location.pathname === "/login"
    ) {
      this.props.history.push("/");
    }
    // if (
    //   this.state.isLoggedIn &&
    //   this.props.location.pathname === "/(profile|user|role)"
    // ) {
    //   this.props.history.push("/"+this.state.showPage);
    // }
    return (
      <Switch data="data">
        <div id="main">
          <Route
            exact
            path="/"
            render={props => (
              <Home
                {...props}
                logoutUser={this._logoutUser}
                showProfile={this._showProfile}
                showUserPage={this._showUserPage}
                showRolePage={this._showRolePage}
                user={this.state.user}
              />
            )}
          />

          <Route
            exact
            path="/login"
            render={props => <Login {...props} loginUser={this._loginUser} />}
          />

        </div>
      </Switch>
    );
  }
}

const AppContainer = withRouter(props => <App {...props} />);
// console.log(store.getState())
render(
  <BrowserRouter>
    <AppContainer />
  </BrowserRouter>,

  document.getElementById("root")
);
