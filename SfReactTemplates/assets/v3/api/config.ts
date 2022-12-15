import axios from "axios";
import { toast } from '@newageerp/v3.templates.templates-core';

export const axiosInstance = axios.create({
  baseURL: '',
  timeout: 300 * 1000,
  headers: {
    // @ts-ignore
    "Authorization": window.localStorage.getItem("token"),
    "Content-Type": "application/json",
  }
});

axiosInstance.interceptors.response.use(undefined, function (error) {
  toast.error('Something went wrong');

  return Promise.reject(error);
})