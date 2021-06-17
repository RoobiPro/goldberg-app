import { APIConfig } from './APIConfig';

import axios from 'axios';

function get(id) {
  return axios.get(`/api/getimports/${id}`)
    .then(response => {
      return response.data;
    });
}

function revert(id){
  return axios.post(`/api/deletecsv/${id}`)
    .then(response => {
      return response.data;
    });
}

export default {
  get,
  revert
};
