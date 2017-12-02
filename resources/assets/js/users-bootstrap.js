// Register components
Vue.component('users-management', require('./components/UsersManagement.vue'))
Vue.component('users-invitations', require('./components/invitations/UserInvitations.vue'))
Vue.component('create-user', require('./components/CreateUser.vue'))
Vue.component('create-user-via-invitation', require('./components/CreateUserViaInvitation.vue'))
Vue.component('users-dashboard', require('./components/dashboard/UsersDashboard.vue'))
Vue.component('model-tracking', require('./components/tracking/ModelTracking.vue'))
Vue.component('user-profile', require('./components/profile/UserProfile.vue'))

//Full screen components:
Vue.component('register-user-by-email', require('./components/RegisterUserByEmail.vue'))
Vue.component('invite-user-fullscreen', require('./components/invitations/InviteUserFullScreen.vue'))


// Google Apps vue components
Vue.component('google-apps-dashboard', require('./components/google/GoogleAppsDashboard.vue'))
Vue.component('google-apps-users-list', require('./components/google/GoogleAppsUsersList.vue'))

import { config } from './config/ebre-escool-users-migration'

window.acacha_users = {}
window.acacha_users.config = config