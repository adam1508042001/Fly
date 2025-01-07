import axios from 'axios';


const axiosInstance = axios.create({
    baseURL: 'http://localhost/api',  // L'URL de base de ton API Laravel
    headers: {
      'Content-Type': 'application/json',
    },
  });
  
  export default axiosInstance;