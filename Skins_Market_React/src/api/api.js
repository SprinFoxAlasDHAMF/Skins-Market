import axios from "axios";

const api = axios.create({
  // 1. Asegúrate de que esta URL sea la de tu servidor PHP
  baseURL: "http://localhost:8000/api", 
});

// 2. Este bloque es el más importante: envía el Token de login a Laravel
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;