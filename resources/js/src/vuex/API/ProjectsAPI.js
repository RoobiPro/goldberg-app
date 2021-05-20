import { APIConfig } from './APIConfig';
import axios from 'axios';


function projectusers(id) {
  return axios.get(`/getProjectUsers/`+id)
    .then(response => {
      return response.data;
    });
}

function getAll(params) {
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`/api/projects`)
    .then(response => {
      // console.log(response.data.meta);
      return {
        // list: jsona.deserialize(response.data),
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function show(id) {

  return axios.get(`/api/projects/${id}`, options)
    .then(response => {
      console.log(response.data)
      // let user = jsona.deserialize(response.data);
      let user = response.data;
      delete user.links;
      return user;
    });
}

function create(user) {
  const payload = {
    stuff: user,
    includeNames: null
  };

  return axios.post(`${url}/users`, payload, options)
    .then(response => {
      return response.data;
    });
}

function update(user) {
  const payload = {
    stuff: user,
    includeNames: []
  };

  return axios.patch(`${url}/users/${user.id}`, payload, options)
    .then(response => {
      return response.data;
    });
}

function destroy(id) {
  return axios.delete(`${url}/user/delete/${id}`, options);
}

function upload(user, image) {
  const bodyFormData = new FormData();
  bodyFormData.append('attachment', image);

  return axios.post(`${url}/uploads/users/${user.id}/profile-image`, bodyFormData)
    .then(response => {
      return response.data.url;
    });
}


export default {
  getAll,
  show,
  create,
  update,
  destroy,
  upload,
  projectusers
};
