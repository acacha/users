<template>
    <div id="users-manager-panel">

        <create-user :api-url="apiUri" :collapsed="collapseCreateUser"></create-user>

        <user-invitations :api-url="apiUri" :collapsed="collapseUserInvitations"></user-invitations>

        <users-list :api-url="apiUri" :collapsed="collapseUsersList" resource="user"></users-list>

    </div>
</template>

<script>

  import QueryString from 'query-string'

  import UsersList from './UsersList'
  import CreateUser from './CreateUser'
  import UserInvitations from './invitations/UserInvitations'

  import store from './Store';

  export default {
    data() {
      return {
        store : store,
        collapseCreateUser: true,
        collapseUserInvitations: false,
        collapseUsersList : false
      }
    },
    components: {
      UsersList,
      CreateUser,
      UserInvitations
    },
    props: {
      // a number with default value
      apiUri: {
        type: String,
        default: '/api/v1/management/users'
      }
    },
    methods: {
      collapse() {
        this.$events.fire('collapse-create-user')
        this.$events.fire('collapse-user-invitations-list')
        this.collapseUserInvitations=true
        this.collapseUsersList=true
      },
      expand () {
        this.$events.fire('expand-create-user')
        this.$events.fire('expand-user-invitations-list')
        this.collapseUserInvitations=false
        this.collapseUsersList=false
      }
    },
    mounted() {
      this.store.apiUri = this.apiUri
      if (typeof QueryString.parse(location.search).expand !== 'undefined') {
        this.expand()
      }
      if (typeof QueryString.parse(location.search).collapse !== 'undefined') {
        this.collapse()
      }
    }
  }
</script>
