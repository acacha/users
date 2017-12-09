import user from '../../../api/user'
import * as types from './mutation-types'

export const getUser = ({ commit }, forceApiUse) => {
  forceApiUse = forceApiUse || false
  if (! forceApiUse) {
    if (window)
      if (window.Laravel)
        if (window.Laravel.user) {
          commit(types.RECEIVE_USER, window.Laravel.user)
          return
        }
  }
  user.getUser(user => {
    commit(types.RECEIVE_USER, user)
  })
}
