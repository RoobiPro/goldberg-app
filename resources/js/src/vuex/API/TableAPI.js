import { APIConfig } from './APIConfig';

import axios from 'axios';

function get(tablename) {
  return axios.get(`/api/tableheader/${tablename}`)
    .then(response => {
      return response.data;
    });
}

export default {
  get
};
