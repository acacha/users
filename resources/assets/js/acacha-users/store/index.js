import Vuex from 'vuex'
import user from './modules/user'

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    user
  },
  strict: debug,
})
