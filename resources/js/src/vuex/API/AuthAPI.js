import { APIConfig } from './APIConfig';
import axios from 'axios';



function update(user) { return axios.patch('/api/users/'+user.id, user) }

async function csrfToken(){ return await axios.get('/sanctum/csrf-cookie') }

async function login(credentials){ return await axios.post('/api/login', credentials) }

async function logout(){ return await axios.post('/api/logout') }

// Need to find logical name for function !IMPORTANTE
async function getAuthStatus(){ return await axios.get('/api/refresh') }



export default {
  csrfToken,
  login,
  logout,
  getAuthStatus,
  update
};
