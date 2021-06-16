import { APIConfig } from './APIConfig';

import axios from 'axios';

function get(id) {
  return axios.get(`/getimports/${id}`)
    .then(response => {
      return response.data;
    });
}

function revert(id){
  return axios.post(`/deletecsv/${id}`)
    .then(response => {
      return response.data;
    });
}

export default {
  get,
  revert
};
