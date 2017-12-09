import UsersManagement from './components/UsersManagement.vue'
import UserInvitations from './components/invitations/UserInvitations.vue'
import CreateUser from './components/CreateUser.vue'
import CreateUserViaInvitation from './components/CreateUserViaInvitation.vue'
import UsersDashboard from './components/dashboard/UsersDashboard.vue'
import ModelTracking from './components/tracking/ModelTracking.vue'

//PROFILE
import UserProfile from './user-profile/UserProfileComponent.vue'
import UserProfileInfoBox from './user-profile/components/UserProfileInfoBoxComponent.vue'

//MAIN
import UserForm from './main/UserForm.vue'

//Full screen components:
import RegisterUserByEmail from './components/RegisterUserByEmail.vue'
import InviteUserFullScreen from './components/invitations/InviteUserFullScreen.vue'

// Google Apps vue components
import GoogleAppsDashboard from './components/google/GoogleAppsDashboard.vue'
import GoogleAppsUsersList from './components/google/GoogleAppsUsersList.vue'

// auto install
if (typeof window !== 'undefined' && window.Vue) {
  // Register components
  Vue.component('users-management', UsersManagement)
  Vue.component('users-invitations', UserInvitations)
  Vue.component('create-user', CreateUser)
  Vue.component('create-user-via-invitation', CreateUserViaInvitation)
  Vue.component('users-dashboard', UsersDashboard)
  Vue.component('model-tracking', ModelTracking)
  // // expose AdminlteVue functions if auto installed
  // window.Vue.$adminlte = {todo, todo2}

  //Full screen components:
  Vue.component('register-user-by-email', RegisterUserByEmail)
  Vue.component('invite-user-fullscreen', InviteUserFullScreen)

  // Google Apps vue components
  Vue.component('google-apps-dashboard', GoogleAppsDashboard)
  Vue.component('google-apps-users-list', GoogleAppsUsersList)

  //Profile
  Vue.component('user-profile', UserProfile)
  Vue.component('user-profile-info-box', UserProfileInfoBox)

  //Main
  Vue.component('user-form', UserForm)
}

import { config } from './config/ebre-escool-users-migration'

window.acacha_users = {}
window.acacha_users.config = config

//Components
export {
  UsersManagement,
  UserInvitations,
  CreateUser,
  CreateUserViaInvitation,
  UsersDashboard,
  ModelTracking,
  RegisterUserByEmail,
  InviteUserFullScreen,
  GoogleAppsDashboard,
  GoogleAppsUsersList,
  UserProfile,
  UserProfileInfoBox,
  UserForm
}

import UserProfileStore from './store'

//Stores
export {
  UserProfileStore
}

//Vuex Modules
import {AcachaUsersModule} from './store/modules/user'

export {
  AcachaUsersModule
}

import {UserProfileStoreComponent} from './user-profile/base-components/UserProfileStoreComponent'

export {
  UserProfileStoreComponent
}