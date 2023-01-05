import axios from "axios";
import { toast } from '@newageerp/v3.templates.templates-core';
import { transformErrorAlert } from "@newageerp/v3.bundles.widgets-bundle";

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
  toast.error(transformErrorAlert(error));

  return Promise.reject(error);
})