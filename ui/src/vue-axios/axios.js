import axios from "axios";

// const API_URL = process.env.API_URL || "http://localhost";

export default axios.create({
  // baseURL: API_URL,
  //   headers: {
  //     apikey: localStorage.token
  //   }
});
