import { APIConfig } from './APIConfig';

import axios from 'axios';

function deleteAllSessions() {
  return axios.get('/api/deleteallsessions/')
    .then(response => {
      // console.log(response)
      return response.data
    });
}

function deleteUserSessions(id) {
  return axios.get('/api/deleteusersessions/'+id)
    .then(response => {
      console.log(response)
      return response.data
    });
}


function getUserSessions(id) {
  return axios.get('/api/getUserSessions/'+id)
    .then(response => {
      // console.log(response)
      return response.data
    });
}


function listClients(params) {
  return axios.get(`/api/getClients`)
    .then(response => {
      return response.data.data

    });
}

function getClientProjects(id){
  return axios.get('/api/getClientProjects/'+id)
    .then(response => {
      return response.data;
    });
}

function getUserProjects(id){
  return axios.get('/api/getUserProjects/'+id)
    .then(response => {
      return response.data;
    });
}

function updatePassword (userid, password){ return axios.patch(`/api/users/`+userid, password) }

function listUsers(params) {

  return axios.get(`/api/getUsers`)
    .then(response => {
      return {
        list: response.data.data,
      };
    });
}

function getAll(params) {
  return axios.get(`/api/users`)
    .then(response => {
      return response.data.data

    });
}

function get(id) {

  return axios.get(`/api/users/${id}`, )
    .then(response => {
      // let user = jsona.deserialize(response.data);
      let user = response.data;
      delete user.links;
      return user;
    });
}

function add(user) {
  return axios.post(`/api/register`, user)
    .then(response => {
      if (response.status == 200) {
        return {
          type: 'green',
          msg: response.data.msg
        }
      } else {
        return {
          type: 'red',
          msg: response.data.msg
        }
      }
    });
}

function update(user) {
  return axios.patch('/api/users/'+user.id, user)

}

function destroy(id) {

  return axios.delete(`/api/users/${id}`)
    .then(response => {
      if (response.status == 200) {
        return {
          type: 'green',
          msg: response.data.msg
        }
      } else {
        return {
          type: 'red',
          msg: response.data.msg
        }
      }
    });
}

// function upload(user, image) {
//   const bodyFormData = new FormData();
//   bodyFormData.append('attachment', image);
//
//   return axios.post(`/uploads/users/${user.id}/profile-image`, bodyFormData)
//     .then(response => {
//       return response.data.url;
//     });
// }

export default {
  deleteAllSessions,
  deleteUserSessions,
  getUserSessions,
  getClientProjects,
  getUserProjects,
  listUsers,
  listClients,
  getAll,
  get,
  add,
  update,
  destroy
};
