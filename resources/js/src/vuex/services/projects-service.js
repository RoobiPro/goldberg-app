import qs from 'qs';
import axios from 'axios';
import Jsona from 'jsona';

const url = process.env.VUE_APP_API_BASE_URL;
const jsona = new Jsona();

function list(params) {
  const options = {
    params: params,
    paramsSerializer: function (params) {
      return qs.stringify(params, {encode: false});
    }
  };
  // options = '?page[number]=1&page[size]=5'
  return axios.get(`${url}/projects?page[number]=${options.params.currentPage}&page[size]=${options.params.perPage}`, options)
    .then(response => {
      // console.log(response.data.meta.page);
      return {
        list: jsona.deserialize(response.data),
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

  return axios.get(`${url}/projects/${id}`, options)
    .then(response => {
      let user = jsona.deserialize(response.data);
      delete project.links;
      return project;
    });
}

function add(project) {
  const payload = jsona.serialize({
    stuff: project,
    includeNames: null
  });

  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

  return axios.post(`${url}/project`, payload, options)
    .then(response => {
      return jsona.deserialize(response.data);
    });
}

function update(project) {
  const payload = jsona.serialize({
    stuff: project,
    includeNames: []
  });

  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

  return axios.patch(`${url}/project/${user.id}`, payload, options)
    .then(response => {
      return jsona.deserialize(response.data);
    });
}

function destroy(id) {
  const options = {
    headers: {
      'Accept': 'application/vnd.api+json',
      'Content-Type': 'application/vnd.api+json',
    }
  };

  return axios.delete(`${url}/project/delete/${id}`, options);
}

function upload(project, image) {
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
