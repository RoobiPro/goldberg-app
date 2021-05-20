import { APIConfig } from './APIConfig';
import axios from 'axios';


function signIn(credentials){
  console.log('/login')
  axios.get('/sanctum/csrf-cookie')

  axios.post('/login', credentials).then( (response) => {
        console.log(response)
        console.log('accessed this response')

        return response
      }).catch( (error) => {
        console.error(error)
        console.log('accessed this error')

        return error
      })
}


function signOut(){ return "Signed Out" }

// Need to find logical name for function !IMPORTANTE
function getAuthStatus(){ return "" }



export default {
  signIn,
  signOut,
  getAuthStatus
};
