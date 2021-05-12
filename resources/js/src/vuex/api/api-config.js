switch( process.env.NODE_ENV ){
  case 'production':
    var AUTH_API = 'https://goldbergresources.com/api';
    var APIV1 = 'https://goldbergresources.com/api/v1';
  break;
  case 'development':
    var AUTH_API = '/api';
    var APIv1 = '/api/v1';
  break;
}


export const APIConfig = {
  AuthAPI: AUTH_API,
  APIv1: APIV1
}
