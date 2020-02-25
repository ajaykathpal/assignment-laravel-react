import React from "react";

const Login = ({ history, loginUser = f => f }) => {
  let _email, _password;
  const handleLogin = e => {
    e.preventDefault();

    if(_email.value === '' || _password.value === ''){
      alert("please Enter Email/Password !!!");
    }else{
      loginUser(_email.value, _password.value);
    }
  };
  return (
    <div id="main" style={styles.main}>
      <form id="login-form" action="" onSubmit={handleLogin} method="post">
        <h3 style={{ padding: 15 }}>Login Form</h3>
        <input
          ref={input => (_email = input)}
          style={styles.input}
          autoComplete="off"
          id="email-input"
          name="email"
          type="text"
          className="center-block"
          placeholder="email"
        />
        <input
          ref={input => (_password = input)}
          style={styles.input}
          autoComplete="off"
          id="password-input"
          name="password"
          type="password"
          className="center-block"
          placeholder="password"
        />
        <button
          type="submit"
          style={styles.button}
          className="landing-page-btn center-block text-center"
          id="email-login-btn"
        >
          Login
        </button>
      </form>
      {/* <Link style={styles.link} to="/register">
        Register
      </Link> */}
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
  input: {
    backgroundColor: "white",
    border: "1px solid #cccccc",
    padding: 15,
    float: "left",
    clear: "right",
    width: "80%",
    margin: 15
  },
  button: {
    height: 44,
    boxShadow: "0px 8px 15px rgba(0, 0, 0, 0.1)",
    border: "none",
    backgroundColor: "red",
    margin: 15,
    float: "left",
    clear: "both",
    width: "85%",
    color: "white",
    padding: 15
  },
  link: {
    width: "100%",
    float: "left",
    clear: "both",
    textAlign: "center"
  }
};

export default Login;
