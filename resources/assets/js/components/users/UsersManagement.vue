<template>
    <div id="users-manager-panel">

        <create-user :api-url="apiUrl" :collapsed="collapseCreateUser"></create-user>

        <user-invitations :api-url="apiUrl" :collapsed="collapseUserInvitations"></user-invitations>

        <users-management-users-list :api-url="apiUrl" :collapsed="collapseUsersList"></users-management-users-list>

    </div>
</template>

<script>

  import QueryString from 'query-string'

  import UsersManagementUsersList from './UsersList'
  import CreateUser from './CreateUser'
  import UserInvitations from './UserInvitations'

  export default {
    data() {
      return {
        collapseCreateUser : true,
        collapseUserInvitations: false,
        collapseUsersList : false
      }
    },
    components: {
      UsersManagementUsersList,
      CreateUser,
      UserInvitations
    },
    props: {
      // a number with default value
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/management/users'
      }
    },
    methods: {
      collapse() {
        this.collapseCreateUser=true
        this.collapseUserInvitations=true
        this.collapseUsersList=true
      },
      expand () {
        this.collapseCreateUser=false
        this.collapseUserInvitations=false
        this.collapseUsersList=false
      }
    },
    mounted() {
      if (typeof QueryString.parse(location.search).expand !== 'undefined') {
        this.expand()
      }
      if (typeof QueryString.parse(location.search).collapse !== 'undefined') {
        this.collapse()
      }
    }
  }
</script>
