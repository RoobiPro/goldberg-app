/*
    Defines the API route we are using.
*/

switch( process.env.APP_ENV ){
  case 'production':
    // var AUTH_API = 'https://goldbergresources.com/api';
    var API_URL = '/';
    // var APIV1 = 'https://goldbergresources.com/api/v1';
  break;
  case 'development':
    var API_URL = '/';
    // var APIv1 = '/api/v1';
  break;
}

const options = {
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    }
};


export const APIConfig = {
  // AuthAPI: AUTH_API,
  APIURL: API_URL,
  APIHeader: options,
  // APIv1: APIV1
}

// Example in modules to use APIURL
//APIConfig.APIURL + /campaign
