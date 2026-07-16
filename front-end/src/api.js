import axios from 'axios';

const api = axios.create({ //creating axious instance
  baseURL: 'http://localhost/taskmanager/back-end/', // your CodeIgniter backend
  withCredentials: true, // If cookies or sessions exist, send them with the request.
  headers: {
    'Content-Type': 'application/json'
  }
});

export default api;