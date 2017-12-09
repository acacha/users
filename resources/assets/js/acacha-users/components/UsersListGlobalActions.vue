<template>
    <div class="filter-bar pull-right" id="users-list-global-actions">
      <form class="form-inline">
        <div class="form-group">
          <label>Global actions:</label>
          <!--TODO-->
          <button title="Import users"
                  class="btn" @click.prevent="importUsers">
            Import
          </button>
          <button title="Export users"
                  class="btn" @click.prevent="exportUsers">
            Export
          </button>
          <button title="Massive reset password by email"
                  class="btn btn-warning" @click.prevent="resetMultipleUsersPasswords" id="massive-send-reset-passwords">
              <i class="fa fa-envelope-o"></i>
          </button>
          <button title="Delete multiple users"
                  class="btn btn-danger" @click.prevent="deleteMultipleUsers" id="massive-remove-users">
              <i class="glyphicon glyphicon-trash"></i>
          </button>
        </div>
      </form>
    </div>
</template>

<script>
  import Dialog from './mixins/Dialog'

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
      resetMultipleUsersPasswords () {
        this.confirmDialog('reset-users-passwords',this.selected)
      },
      deleteMultipleUsers () {
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
          title: 'Confirm massive user deletion',
          body: 'Are you sure you want to delete multiple users?'
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
