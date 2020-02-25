import React from "react";
import './Home.css';
import axios from "axios";

const styles = {
  fontFamily: "sans-serif",
  textAlign: "center"
};

export default class Home extends React.Component {
  constructor(props) {
    super(props);
    
    this.user = JSON.parse(localStorage["appState"]).user;

    this.tabsToShow = JSON.parse(localStorage["appState"]).user.tabs_show;

    this.showPage = JSON.parse(localStorage["appState"]).showPage;

    this.users = [];
    this.roles = [];
    this.userpageactions = [];
    this.rolepageactions = [];

    this.state = {
      token: JSON.parse(localStorage["appState"]).user.auth_token
    };
  }

  getUserList() {

    if(this.state.token !== '' || (typeof this.state.token !== 'undefined')) {
      axios
        .get(`http://localhost:8000/api/user/list?token=${this.state.token}`)
        .then(response => {
          return response;
        })
        .then(json => {
          if (json.data.success) {
            this.users = json.data.data;
            
          } else alert("Unable to get User List!!!");
        })
        .catch(error => {
          // alert(`An Error Occured! ${error}`);
        });

        //get user page actions
        axios
        .get(`http://localhost:8000/api/user/action?token=${this.state.token}`)
        .then(response => {
          return response;
        })
        .then(json => {
          if (json.data.success) {
            this.userpageactions = json.data.data;
            
          } else alert("Unable to get User List!!!");
        })
        .catch(error => {
          // alert(`An Error Occured! ${error}`);
        });
    }
  }

  getUserPage

  getRoleList() {

    if(this.state.token !== '' || (typeof this.state.token !== 'undefined')) {
      axios
        .get(`http://localhost:8000/api/role/list?token=${this.state.token}`)
        .then(response => {
          return response;
        })
        .then(json => {
          if (json.data.success) {
            this.roles = json.data.data;
            
          } else alert("Unable to get role list!!!");
        })
        .catch(error => {
          // alert(`An Error Occured! ${error}`);
        });

        //get user role actions
        axios
        .get(`http://localhost:8000/api/role/action?token=${this.state.token}`)
        .then(response => {
          return response;
        })
        .then(json => {
          if (json.data.success) {
            this.rolepageactions = json.data.data;
            
          } else alert("Unable to get User List!!!");
        })
        .catch(error => {
          // alert(`An Error Occured! ${error}`);
        });
    }
  }

  deleteUser(){
    alert("delete user here");
  }

  editUser() {
    alert("here user edit");
  }

  addUser() {
    alert("add user");
  }

  deleteRole() {
    alert("here");
  }

  addRole() {
    alert("here");
  }

  editRole() {
    alert("here");
  }

  render() {

    this.showPage = JSON.parse(localStorage["appState"]).showPage;

    if(this.showPage === 'user'){
      this.getUserList();
    }

    if(this.showPage === 'role'){
      this.getRoleList();
    }

    return (
      <div style={styles}>

        <div class="tab">
          {this.tabsToShow.profile ? <button class="tablinks profile active" onClick={this.props.showProfile}>Profile Page</button>:''}
          {this.tabsToShow.user ? <button class="tablinks user" onClick={this.props.showUserPage}>User Page</button>:''}
          {this.tabsToShow.role ? <button class="tablinks role" onClick={this.props.showRolePage}>Role page</button>:''}
        </div>

        <div id="page-content">

          {this.showPage === 'profile' ? 
            <div id="profile-page">
              <h2>Welcome {this.user.name} {"\u2728"}</h2>
              <div class="profile-content">
                <span><b>Name : </b> {this.user.name}</span><br/>
                <span><b>Email : </b> {this.user.email}</span><br/>
                <span><b>Role : </b> {this.user.role}</span>
              </div><br/>
              <button
                style={{ padding: 10, backgroundColor: "red", color: "white" }}
                onClick={this.props.logoutUser}
              >
                Logout{" "}
              </button>
            </div>
            :''}

          {this.showPage === 'user' ? 
            <div id="user-page">

              <table style={{width:"100%", border:"1"}}>
                <tr>
                  <th>NAME</th>
                  <th>EMAIL</th> 
                  <th>ROLE</th>
                  <th>ACTIONS</th>
                </tr>
                
                {this.users.map(user => 
                    <tr>
                      <td>{user.name}</td>
                      <td>{user.email}</td>
                      <td>{user.role}</td>
                      <td>
                      {this.userpageactions.map(action => {

                            if(action.action === 'delete'){
                              return <button style={{ margin:"10px"}} onClick={this.deleteUser}>
                                      {action.action}
                                      </button>
                            }
                            if(action.action === 'edit'){
                              return <button style={{ margin:"10px"}} onClick={this.editUser}>
                                      {action.action}
                                      </button>
                            }
                            if(action.action === 'add'){
                              return <button style={{ margin:"10px"}} onClick={this.addUser}>
                                    {action.action}
                                    </button>
                            }
                        }
                        )}
                      </td>
                    </tr>)
                  }

              </table>
            </div>:''}

          {this.showPage === 'role' ? 
            <div id="role-page">
        
              <table style={{width:"100%"}}>
                <tr>
                  <th>ROLE</th>
                  <th>ACTIONS</th>
                </tr>
                
                {this.roles.map(role => 
                    <tr>
                      <td>{role.role}</td>
                      <td>
                      {this.rolepageactions.map(action => {

                          if(action.action === 'delete'){
                            return <button style={{ margin:"10px"}} onClick={this.deleteRole}>
                                    {action.action}
                                    </button>
                          }
                          if(action.action === 'edit'){
                            return <button style={{ margin:"10px"}} onClick={this.editRole}>
                                    {action.action}
                                    </button>
                          }
                          if(action.action === 'add'){
                            return <button style={{ margin:"10px"}} onClick={this.addRole}>
                                  {action.action}
                                  </button>
                          }
                        }
                        )}
                      </td>
                    </tr>)
                  }

              </table>
            </div>:''}
        </div>
      </div>
    );
  }
}