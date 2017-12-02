<template>
    <div class="filter-bar pull-right" id="user-invitations-list-global-actions">
        <form class="form-inline">
            <div class="form-group">
                <label>Global actions:</label>
                <button title="Massive accept invitations and create users"
                        class="btn btn-default" @click.prevent="massiveAcceptInvitations" id="massive-accept-invitations"
                        >
                    <i class="fa fa-user"></i>
                </button>
                <button title="Massive resend user invitations"
                        class="btn btn-warning" @click.prevent="massiveResendUserInvitations" id="massive-resend-user-invitation">
                    <i class="fa fa-envelope-o"></i>
                </button>
                <button title="Delete multiple user invitations"
                        class="btn btn-danger" @click.prevent="deleteMultipleUserInvitations" id="massive-remove-user-invitations">
                    <i class="glyphicon glyphicon-trash"></i>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
  import Dialog from '../mixins/Dialog'

  export default {
    mixins: [Dialog],
    data () {
      return {
        filterText: ''
      }
    },
    props: {
      selected: {
        type: Array,
        required: true
      }
    },
    methods: {
      massiveResendUserInvitations () {
        //TODO
        this.confirmDialog('massive-resent-user-invitations',this.selected)
      },
      deleteMultipleUserInvitations () {
        this.confirmDialog('delete-users',this.selected)
      },
      getDialogByType(type) {
        switch (type) {
          case 'delete-users':
            return this.getDialogForMultipleUsersDelete()
          case 'reset-users-passwords':
            return this.getDialogForResetMultipleUsersPassword()
        }
      },
      getDialogForMultipleUsersDelete() {
        return {
          title: 'Confirm massive user invitations deletion',
          body: 'Are you sure you want to delete multiple user invitations?'
        }
      },
      getDialogForResetMultipleUsersPassword() {
        return {
          title: 'Confirm massive user password reset by email',
          body: 'Are you sure you want to send an email to multiple users?',
          confirmText: 'Send'
        }
      }
    }
  }
</script>
