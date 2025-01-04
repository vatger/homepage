import axios, { AxiosInstance, AxiosRequestConfig } from "axios";

const axiosConfig: AxiosRequestConfig = {
    timeout: 5000,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]'),
    },
};

export const axiosInstance: AxiosInstance = axios.create(axiosConfig);
