import * as types from './mutation-types'

export default {
  [types.RECEIVE_USER] (state, user) {
    state.user = user
  }
}