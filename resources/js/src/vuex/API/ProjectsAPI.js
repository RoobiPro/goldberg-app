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
      // console.log(response)
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

function unassignUser(project){
  return axios.post(`/unassignUser`, project)
    .then( response => {
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

function reassignUser(project){
  return axios.post(`/reassignUser`, project)
    .then( response => {
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

function assignClient(client) {
  return axios.post(`/assignclient`, client)
    .then(response => {
      // console.log(response)
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
      // console.log(response)
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

function create(project) {
  return axios.post('/api/projects', project)
    .then( response => {
      // console.log(response)
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

function update(project) {
  return axios.patch('/api/projects/'+project.id, project)
  .then( response => {
    // console.log(response)
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

function destroy(project) {
  // console.log(project)
  return axios.delete('/api/projects/' + project)
    .then( response => {
      // console.log(response)
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
    });
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
  assignUser,
  assignClient,
  unassignUser,
  reassignUser
};
