import { APIConfig } from './APIConfig';

import axios from 'axios';


function listClients(params) {

  // options = '?page[number]=1&page[size]=5'
  return axios.get(`/getClients`)
    .then(response => {
      return {
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function listUsers(params) {

  // options = '?page[number]=1&page[size]=5'
  return axios.get(`/getUsers`)
    .then(response => {
      return {
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function list(params) {
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`/api/users`)
    .then(response => {
      // console.log(response.data.data);
      // console.log(response.data.meta);
      return {
        // list: jsona.deserialize(response.data),
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function get(id) {

  return axios.get(`/users/${id}`, )
    .then(response => {
      // let user = jsona.deserialize(response.data);
      let user = response.data;
      delete user.links;
      return user;
    });
}

function add(user) {
  console.log(user);
  return axios.post(`/register`, user)
    .then(response => {
      console.log(response);
      return response;
    }).catch(error => {
      if (error.response.status != 200){
        console.log(error);
        return error;
        // commit('SET_RESPONSE', response);
      }
    });

}

function update(user) {
  const payload = {
    stuff: user,
    includeNames: []
  };


  return axios.patch(`/users/${user.id}`, payload)
    .then(response => {
      return response.data;
    });
}

function destroy(id) {

  return axios.delete(`/api/users/${id}`)
    .then(response => {
      console.log(response);

      return response;
    });
}

function upload(user, image) {
  const bodyFormData = new FormData();
  bodyFormData.append('attachment', image);

  return axios.post(`/uploads/users/${user.id}/profile-image`, bodyFormData)
    .then(response => {
      return response.data.url;
    });
}

export default {
  listUsers,
  listClients,
  list,
  get,
  add,
  update,
  destroy,
  upload
};
