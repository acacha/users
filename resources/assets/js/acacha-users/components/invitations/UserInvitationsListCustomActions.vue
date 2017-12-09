<template>
  <div class="custom-actions">
    <button title="Accept invitation and create User"
            class="btn btn-sm btn-default" :id="'accept-user-invitation-' + rowData.id"
            @click="createUser()" :disabled="!laravel.user.can['create-users']">
      <i class="fa fa-user"></i>
    </button>
    <button title="Resend invitation"
            class="btn btn-sm btn-warning" :id="'resend-user-invitation-' + rowData.id"
            @click="resend()" :disabled="!laravel.user.can['send-user-invitations']">
      <i class="fa fa-envelope-o"></i>
    </button>
    <button v-scroll-to="'#user-invitation-' + rowData.id + '-detail-row'" title="View"
            class="btn btn-sm btn-primary" :id="'show-user-invitation-' + rowData.id"
            @click="toogleShow('user-invitations',rowData)" :disabled="!laravel.user.can['view-user-invitations']">
      <i class="glyphicon glyphicon-zoom-in"></i>
    </button>
    <button v-scroll-to="'#user-invitation-' + rowData.id + '-detail-row'" title="Edit"
            class="btn btn-sm btn-success"
            :id="'edit-user-invitation-' + rowData.id" @click="toogleEdit('user-invitations',rowData)"
            :disabled="!laravel.user.can['edit-user-invitations']">
      <i class="glyphicon glyphicon-pencil"></i>
    </button>
    <button class="btn btn-sm btn-danger" :id="'delete-user-invitation-' + rowData.id" title="Delete"
            @click="confirmDialog('delete-user-invitation',rowData)"
            :disabled="!laravel.user.can['delete-user-invitations']">
      <i class="glyphicon glyphicon-trash"></i>
    </button>
  </div>
</template>

<script>
  import VueScrollTo from 'vue-scrollto'
  Vue.use(VueScrollTo)

  import CustomActions from '../mixins/CustomActions'

  export default {
    mixins: [
      CustomActions
    ],
    methods: {
      getDialogByType(type) {
        switch(type) {
          case 'delete-user-invitation':
            return this.getDialogForDeleteUserInvitation()
//          case n:
//            return getDialogForDeleleteUserInvitation()
        }
      },
      getDialogForDeleteUserInvitation() {
        return {
          title: 'Confirm user invitation deletion',
          body: 'Are you sure you want to delete user invitation?'
        }
      }
    }
  }
</script>

<style src="../css/customActions.css"></style>
