<template>
  <div class="user-list-custom-actions">
    <button title="Open user profile"
            class="btn btn-sm btn-default" :id="'open-user-profile-' + rowData.id"
            @click="goToUserProfile(rowData.id)" :disabled="!laravel.user.can['see-other-users-profile']">
      <i class="fa fa-user"></i>
    </button>
    <button title="Reset password by email"
            class="btn btn-sm btn-warning" :id="'reset-user-password-' + rowData.id"
            @click="confirmDialog('reset-user-password',rowData)" :disabled="!laravel.user.can['reset-user-password']">
      <i class="fa fa-envelope-o"></i>
    </button>
    <button v-scroll-to="'#user-' + rowData.id + '-detail-row'" title="View"
            class="btn btn-sm btn-primary" :id="'show-user-' + rowData.id"
            @click="toogleShow('user',rowData)" :disabled="!laravel.user.can['view-users']">
      <i class="glyphicon glyphicon-zoom-in"></i>
    </button>
    <button v-scroll-to="'#user-' + rowData.id + '-detail-row'"
            class="btn btn-sm btn-success" :id="'edit-user-' + rowData.id" title="Edit"
            @click="toogleEdit('user',rowData)" :disabled="!laravel.user.can['edit-users']">
      <i class="glyphicon glyphicon-pencil"></i>
    </button>
    <button class="btn btn-sm btn-danger"  :id="'delete-user-' + rowData.id" title="Delete"
            @click="confirmDialog('delete-user',rowData)" :disabled="!laravel.user.can['delete-users']">
      <i class="glyphicon glyphicon-trash"></i>
    </button>
  </div>
</template>

<script>
  import VueScrollTo from 'vue-scrollto'
  Vue.use(VueScrollTo)

  import CustomActions from './mixins/CustomActions'
  import store from './Store';

  export default {
    data() {
      return {
        store : store
      }
    },
    mixins: [
      CustomActions
    ],
    methods: {
      goToUserProfile(id) {
        window.open('/user/profile/' + id)
      },
      getDialogByType(type) {
        switch(type) {
          case 'delete-user':
            return this.getDialogForDeleteUser()
          case 'reset-user-password':
            return this.getDialogForResetUserPassword()
        }
      },
      getDialogForDeleteUser() {
        return {
          title: 'Confirm user deletion',
          body: 'Are you sure you want to delete user?'
        }
      },
      getDialogForResetUserPassword() {
        return {
          title: 'Confirm user password reset by email',
          body: 'Are you sure you want to send and email to the user?',
          confirmText: 'Send'
        }
      }
    }
  }
</script>

<style src="./css/customActions.css"></style>
