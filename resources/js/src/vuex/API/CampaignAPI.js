import { APIConfig } from './APIConfig';
import axios from 'axios';



function getAll(params) {
  return "getAll"
}

function show(id) {
  return "Show Campaign"
 }

function create(campaign) {
  return "Create Campaign"
}

function update(campaign) {
  return "Update Campaign"
}

function destroy(campaign) {
  return "Remove Campaign"
}



export default {
  getAll,
  show,
  create,
  update,
  destroy
};
