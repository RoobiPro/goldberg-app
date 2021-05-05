switch( process.env.NODE_ENV ){
  case 'production':
    var AUTH_API = 'https://goldbergresources.com/api';
    var APIV1 = 'https://goldbergresources.com/api/v1';
  break;
  case 'development':
    var AUTH_API = 'http://goldberg.local/api';
    var APIv1 = 'http://goldberg.local/api/v1';
  break;
}


export const APIConfig = {
  AuthAPI: AUTH_API,
  APIv1: APIV1
}
