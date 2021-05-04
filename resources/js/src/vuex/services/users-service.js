import qs from 'qs';
import axios from 'axios';
// import Jsona from 'jsona';

// const url = process.env.VUE_APP_API_BASE_URL;
const url = 'http://goldberg.local';
// const jsona = new Jsona();

function listClients(params) {
  const options = {
    params: params,
    paramsSerializer: function (params) {
      return qs.stringify(params, {encode: false});
    }
  };
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`${url}/getClients`)
    .then(response => {
      return {
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function listUsers(params) {
  const options = {
    params: params,
    paramsSerializer: function (params) {
      return qs.stringify(params, {encode: false});
    }
  };
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`${url}/getUsers`)
    .then(response => {
      return {
        list: response.data.data,
        meta: response.data.meta
      };
    });
}

function list(params) {
  const options = {
    params: params,
    paramsSerializer: function (params) {
      return qs.stringify(params, {encode: false});
    }
  };
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`${url}/api/users`)
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
  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

  return axios.get(`${url}/users/${id}`, options)
    .then(response => {
      // let user = jsona.deserialize(response.data);
      let user = response.data;
      delete user.links;
      return user;
    });
}

function add(user) {
  console.log(user);
  return axios.post(`${url}/register`, user)
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

  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

  return axios.patch(`${url}/users/${user.id}`, payload, options)
    .then(response => {
      return response.data;
    });
}

function destroy(id) {
  const options = {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    }
  };

  return axios.delete(`${url}/api/users/${id}`, options)
    .then(response => {
      console.log(response);

      return response;
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
  listUsers,
  listClients,
  list,
  get,
  add,
  update,
  destroy,
  upload
};
