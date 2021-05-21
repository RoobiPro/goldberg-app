import { APIConfig } from './APIConfig';
import axios from 'axios';


function projectusers(id) {
  return axios.get(`/getProjectUsers/`+id)
    .then(response => {
      return response.data;
    });
}

function assignUser(user) {
  return axios.post(`/assignuser`, user)
    .then(response => {
      console.log(response)
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
    }).catch(error => {
      // if (error.response.status != 200) {
      //   return message = {
      //     type: 'red',
      //     text: response.data
      //   }
      // }
    });
}

function getAll(params) {
  return axios.get(`/api/projects`)
    .then(response => {
      console.log(response)
      return {
        projects: response.data.data,
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
  projectusers,
  assignUser
};
