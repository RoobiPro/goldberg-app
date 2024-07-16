import { APIConfig } from './APIConfig';
import axios from 'axios';



function update(user) { return axios.patch('/api/users/'+user.id, user) }

async function csrfToken() {
  const response = await axios.get('/sanctum/csrf-cookie');
  // console.log('CSRF token response:', response);
  return response;
}

async function login(credentials) {
  const response = await axios.post('/api/login', credentials);
  // console.log('Login response:', response);
  return response;
}

async function logout() {
  const response = await axios.post('/api/logout');
  // console.log('Logout response:', response);
  return response;
}

async function getAuthStatus() {
  const response = await axios.get('/api/refresh');
  // console.log('Auth status response:', response);
  return response;
}



export default {
  csrfToken,
  login,
  logout,
  getAuthStatus,
  update
};
