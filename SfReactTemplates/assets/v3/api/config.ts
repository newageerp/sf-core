import axios from "axios";

export const axiosInstance = axios.create({
  baseURL: '',
  timeout: 30 * 1000,
  headers: {
    // @ts-ignore
    "Authorization": window.localStorage.getItem("token"),
    "Content-Type": "application/json",
  }
});
