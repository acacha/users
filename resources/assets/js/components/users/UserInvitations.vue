<template>
    <div class="box box-primary" v-bind:class="{ 'collapsed-box': collapsed }" id="user-invitations">
        <div class="box-header with-border">
            <h3 class="box-title">Invitations</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i v-if="collapsed" class="fa fa-plus"></i>
                    <i v-else class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">

            <invite-user :api-url="apiUrl  + '/invitations'"></invite-user>

            <users-management-user-invitations-list :api-url="apiUrl + '/invitations'" :collapsed="collapseUserInvitationsList"></users-management-user-invitations-list>

        </div>
    </div>
</template>

<script>

  import QueryString from 'query-string'

  import UsersManagementUserInvitationsList from './UserInvitationsList'
  import InviteUser from './InviteUser'

  export default {
    data() {
      return {
        collapseUserInvitationsList : true
      }
    },
    components: {
      UsersManagementUserInvitationsList,
      InviteUser,
    },
    props: {
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/management/users/invitations'
      },
      collapsed: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      collapse() {
        this.collapseUserInvitationsList=true
      },
      expand () {
        this.collapseUserInvitationsList=false
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