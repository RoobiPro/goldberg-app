import qs from 'qs';
import axios from 'axios';
// import Jsona from 'jsona';

// const url = process.env.VUE_APP_API_BASE_URL;
const url = 'http://goldberg.local/api'
// const jsona = new Jsona();

function list(params) {
  const options = {
    params: params,
    paramsSerializer: function (params) {
      return qs.stringify(params, {encode: false});
    }
  };
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`${url}/users`)
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
  // const payload = jsona.serialize({
  //   stuff: user,
  //   includeNames: null
  // });
  const payload = {
    stuff: user,
    includeNames: null
  };

  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
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
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

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
  list,
  get,
  add,
  update,
  destroy,
  upload
};
