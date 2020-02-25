import React from "react";
import { Link } from "react-router-dom";

const NotFound = () => {
//   let _email, _password;
//   const handleLogin = e => {
//     e.preventDefault();

//     if(_email.value === '' || _password.value === ''){
//       alert("please Enter Email/Password !!!");
//     }else{
//       loginUser(_email.value, _password.value);
//     }
//   };
  return (
    <div id="main" style={styles.main}>
      <p>404: PAGE NOT FOUND </p>

      <Link style={styles.link} to="/login">
        Login here
      </Link>
    </div>
  );
};
const styles = {
  main : {
    background: "cadetblue",
    position: "fixed",
    top: "50%",
    left: "50%",
    width: "30em",
    height: "18em",
    margin: "-14em -15em",
    border: "4px solid #9EB"
  },
  link: {
    width: "100%",
    float: "left",
    clear: "both",
    textAlign: "center"
  }
};

export default NotFound;
