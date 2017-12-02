<template>
    <adminlte-vue-box color="primary" :collapsed="isCollapsed" id="user-invitations">
        <span slot="title">Invitations</span>

        <invite-user :api-url="apiUri  + '-invitations'"></invite-user>

        <user-invitations-list :api-url="apiUri + '-invitations'" :collapsed="collapseUserInvitationsList" resource="user-invitation"></user-invitations-list>
    </adminlte-vue-box>
</template>

<script>

  import QueryString from 'query-string'

  import UserInvitationsList from './UserInvitationsList'
  import InviteUser from './InviteUser'

  export default {
    data() {
      return {
        collapseUserInvitationsList : this.collapsedList,
        isCollapsed: this.collapsed
      }
    },
    components: {
      UserInvitationsList,
      InviteUser,
    },
    props: {
      apiUri: {
        type: String,
        default: '/api/v1/users-invitations'
      },
      collapsed: {
        type: Boolean,
        default: false
      },
      collapsedList: {
        type: Boolean,
        default: true
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
    },
    events: {
      'collapse-invite-user' () {
        this.isCollapsed = true
      },
      'expand-invite-user' () {
        this.isCollapsed = false
      }
    }
  }
</script>