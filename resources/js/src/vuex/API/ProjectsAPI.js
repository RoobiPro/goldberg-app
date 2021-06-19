import { APIConfig } from './APIConfig';
import axios from 'axios';

function wellsamples(id){
  return axios.get('/api/getwellsamplelist/'+id)
    .then(response => {
      console.log(response)
      return response.data;
    });
}

function drillingsamples(id){
  return axios.get('/api/getdrillingsamplelist/'+id)
    .then(response => {
      console.log(response)
      return response.data;
    });
}

function getprojectwells(id){
  return axios.get('/api/getwells/'+id)
    .then(response => {
      console.log(response)
      return response.data;
    });
}

function getprojecthandsamples(id){
  return axios.get('/api/gethandsamples/'+id)
    .then(response => {
      return response.data;
    });
}

function getprojectdrillings(id){
  return axios.get('/api/getdrillings/'+id)
    .then(response => {
      return response.data;
    });
}

function getprojectdata(id) {
  return axios.get(`/api/getprojectdata/`+id)
    .then(response => {
      return response.data;
    });
}

function projectspatials(id) {
  return axios.get(`/api/getProjectSpatials/`+id)
    .then(response => {
      return response.data;
    });
}

function projectusers(id) {
  return axios.get(`/api/getProjectUsers/`+id)
    .then(response => {
      return response.data;
    });
}

function assignUser(user) {
  return axios.post(`/api/assignuser`, user)
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
  return axios.post(`/api/unassignUser`, project)
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
  return axios.post(`/api/reassignUser`, project)
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
  return axios.post(`/api/assignclient`, client)
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

// function upload(user, image) {
//   const bodyFormData = new FormData();
//   bodyFormData.append('attachment', image);
//
//   return axios.post(`${url}/uploads/users/${user.id}/profile-image`, bodyFormData)
//     .then(response => {
//       return response.data.url;
//     });
// }


export default {
  wellsamples,
  drillingsamples,
  getprojectwells,
  getprojecthandsamples,
  getprojectdrillings,
  getprojectdata,
  projectspatials,
  getAll,
  show,
  create,
  update,
  destroy,
  projectusers,
  assignUser,
  assignClient,
  unassignUser,
  reassignUser
};
