import { APIConfig } from './APIConfig';
import axios from 'axios';

function projectspatials(id) {
  return axios.get(`/getProjectSpatials/`+id)
    .then(response => {
      return response.data;
    });
}

function projectusers(id) {
  return axios.get(`/getProjectUsers/`+id)
    .then(response => {
      return response.data;
    });
}

function assignUser(user) {
  return axios.post(`/assignuser`, user)
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
    });
}

function getAll(params) {
  return axios.get(`/api/projects`)
    .then(response => {
      return {
        projects: response.data.data,
      };
    });
}

function show(id) {

  return axios.get(`/api/projects/${id}`, options)
    .then(response => {
      let user = response.data;
      delete user.links;
      return user;
    });
}

function create(project) {
  return axios.post('/api/projects', project)
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

function update(project) {
  return axios.patch('/api/projects/'+project.id, project)
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

function destroy(project) {
  return axios.delete('/api/projects/' + project)
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
  projectspatials,
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
