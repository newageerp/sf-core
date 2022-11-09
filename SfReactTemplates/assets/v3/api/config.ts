import axios from "axios";
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';

export const axiosInstance = axios.create({
  baseURL: '',
  timeout: 30 * 1000,
  headers: {
    // @ts-ignore
    "Authorization": window.localStorage.getItem("token"),
    "Content-Type": "application/json",
  }
});

axiosInstance.interceptors.response.use(undefined, function (error) {
  OpenApi.toast.error('Something went wrong');

  return Promise.reject(error);
})