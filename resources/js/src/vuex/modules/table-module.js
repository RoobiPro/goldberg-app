import service from '@/vuex/api/projects-service';

const state = {
  headers: [
    {
        text: 'ID',
        align: 'start',
        value: 'id',
      },
      {
        text: 'Name',
        value: 'name'
      },
      {
        text: 'E-mail',
        value: 'created_at'
      },
      {
        text: 'Role',
        value: 'updated_at'
      },
      {
        text: 'Actions',
        value: 'actions',
        sortable: false
      },
      {
        text: '',
        value: 'data-table-expand'
      },
  ]
};

const mutations = {


};

const actions = {

};

const getters = {

};

const projects = {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};

export default projects;
