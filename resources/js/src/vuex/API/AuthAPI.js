import { APIConfig } from './APIConfig';
import axios from 'axios';





function csrfToken(){ return axios.get('/sanctum/csrf-cookie') }

function login(credentials){ return axios.post('/login', credentials) }

function logout(){ return "Signed Out" }

// Need to find logical name for function !IMPORTANTE
function getAuthStatus(){ return "" }



export default {
  csrfToken,
  login,
  logout,
  getAuthStatus
};
