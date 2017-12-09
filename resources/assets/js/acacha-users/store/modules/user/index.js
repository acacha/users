import * as getters from './getters.js'
import mutations from './mutations.js'
import * as actions from './actions.js'

const namespaced = true;

import Form from 'acacha-forms'

const initialForm = new Form({
  name: '',
  email: '',
  password: ''
})

const AcachaUsersModule  =  {
  namespaced,
  state() {
    return {
      user: {},
      user_form : initialForm
    }
  },
  getters,
  mutations,
  actions
}

export {
  AcachaUsersModule
}

export default AcachaUsersModule