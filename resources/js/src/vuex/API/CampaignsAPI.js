import { APIConfig } from './APIConfig';
import axios from 'axios';

function getAll(params) {
  return "getAll"
}

function show(id) {
  return "Show Campaign"
 }

function getProjectDrillings(id){
  return axios.get('/api/getCampaign/' + id)
    .then(response => {
      return response.data;
    });
}

function create(campaign) {
   return axios.post(`/api/campaign`, campaign)
    .then(response => {
      if (response.status == 200) {
        return {
          type: 'green',
          text: response.data.msg
        }
      } else {
        return {
          type: 'red',
          text: response.data.msg
        }
      }
    })
}

function createDrilling(drilling){
  return axios.post('/api/drillings', drilling)
    .then(response => {
      if (response.status == 200) {
        return {
          type: 'green',
          text: response.data
        }
      } else {
        return {
          type: 'red',
          text: response.data
        }
      }
    })

}

function update(campaign) {
  return "Update Campaign"
}

function destroy(campaign) {
  return "Remove Campaign"
}



export default {
  getAll,
  show,
  create,
  update,
  destroy,
  getProjectDrillings,
  createDrilling
};
