<template>
    <div id="user-list">

        <confirm-dialog
                id="users-list-confirm-modal"
                :show="dialogHasToBeShown"
                :acting="performingAction"
                :title="confirmDialogTitle"
                :body="confirmDialogBody"
                @hide="onHide"
                @confirm="onConfirm"
                :confirm-text="confirmDialogText"
        ></confirm-dialog>

        <adminlte-vue-box color="success" :collapsed="isCollapsed" id="users-list-box" :loading="loading">
            <span slot="title">Users Lists</span>

            <users-list-filter-bar></users-list-filter-bar>
            <users-list-global-actions :selected="selectedItems()"></users-list-global-actions>

            <adminlte-vue-alert color="success" title="Done!" v-if="showResult" id="users-list-result"
                                style="clear: left;">
                {{ result }}
            </adminlte-vue-alert>

            <div class="table-responsive" style="clear: left;">
                <vuetable ref="vuetable"
                          :api-url="apiUri"
                          :fields="columns"
                          pagination-path=""
                          :css="css.table"
                          :api-mode="true"
                          row-class="um-user-row"
                          :append-params="moreParams"
                          :multi-sort="true"
                          detail-row-component="user-detail-row"
                          @vuetable:pagination-data="onPaginationData"
                          @vuetable:cell-clicked="onCellClicked"
                          @vuetable:loading="showLoader"
                          @vuetable:loaded="hideLoader"
                ></vuetable>
            </div>

            <div class="vuetable-pagination" id="users-list-vuetable-pagination">
                <vuetable-pagination-info ref="paginationInfo"
                                          info-class="pagination-info"
                                          infoTemplate="Displaying {from} to {to} of {total} users"
                ></vuetable-pagination-info>

                <vuetable-pagination ref="pagination"
                                     :css="css.pagination"
                                     :icons="css.icons"
                                     @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination>
            </div>
        </adminlte-vue-box>
    </div>

</template>

<script>

  import UsersListFilterBar from './UsersListFilterBar'
  import UsersListGlobalActions from './UsersListGlobalActions'
  import UserDetailRow from './UserDetailRow'
  import UserListCustomActions from './UsersListCustomActions'

  Vue.component('users-list-filter-bar', UsersListFilterBar)
  Vue.component('users-list-global-actions', UsersListGlobalActions)
  Vue.component('user-detail-row', UserDetailRow)
  Vue.component('users-list-custom-actions', UserListCustomActions)

  import List from './mixins/List.js'
  import HideDialog from './mixins/HideDialog.js'
  import PerformAction from './mixins/PerformAction.js'

  export default {
    mixins: [
      List, PerformAction, HideDialog
    ],
    components: {
      UsersListFilterBar
    },
    data() {
      return {
        columns: [
          {
            name: '__sequence',
            title: '#',
            titleClass: 'text-right',
            dataClass: 'text-right'
          },
          {
            name: '__checkbox',
            titleClass: 'text-center',
            dataClass: 'text-center',
          },
          {
            name: 'extra',
            visible: false,
          },
          {
            name: 'id',
            sortField: 'id',
          },
          {
            name: 'name',
            sortField: 'name',
          },
          {
            name: 'email',
          },
          {
            name: 'created_at',
          },
          {
            name: 'updated_at',
          },
          {
            name: '__component:users-list-custom-actions',
            title: 'Actions',
            titleClass: 'text-center',
            dataClass: 'text-center'
          }
        ],
      }
    },
    methods: {
      selectedItems () {
        if (this.$refs.vuetable) return this.$refs.vuetable.selectedTo
        return []
      },
      checkDialogType (type){
        return this.validDialogs().includes(type)
      },
      validDialogs() {
        return ['delete-user','reset-user-password','delete-users','reset-users-passwords']
      },
      executePostConfirmAction () {
        switch(this.confirmDialogType) {
          case 'delete-user':
            this.deleteUser(this.currentData)
            break;
          case 'reset-user-password':
            this.resetPassword(this.currentData)
            break;
          case 'delete-users':
            this.deleteUsers(this.currentData)
            break;
          case 'reset-users-passwords':
            this.resetPasswords(this.currentData)
            break;
        }
      },
      deleteUser (data) {
        this.performAction(
          data,
          '/' + data.id,
          [],
          'User ' + data.name + ' has been deleted!',
          'delete',
          'show-result-users-list'
        )
        this.reload()
      },
      resetPassword(data) {
        this.performAction(
          data,
          '/send/reset-password-email',
          {
            email: data.email
          },
          'Password reset email sent to user ' + data.name + '.',
          'post',
          'show-result-users-list'
        )
      },
      deleteUsers(data) {
        this.performAction(
          data,
          '-massive',
          {
            ids: data
          },
          'Selected users removed.',
          'post',
          'show-result-users-list'
        )
        this.reload()
      },
      resetPasswords(data) {
        this.performAction(
          data,
          '/send/reset-password-email/massive',
          {
            ids: data
          },
          'Password reset email sent to user all the selected users.',
          'post',
          'show-result-users-list')
      }
    },
    events: {
      'filter-set-users-list' (filterText) {
        this.moreParams = {
          filter: filterText
        }
        Vue.nextTick(() => this.refresh())
      },
      'filter-reset-users-list' () {
        this.moreParams = {}
        Vue.nextTick(() => this.refresh())
      },
      'reload-users-list' () {
        Vue.nextTick(() => this.reload())
      },
      'toogle-show-user' (id) {
        this.detailRowEditing(id,false)
      },
      'toogle-edit-user' (id) {
        this.detailRowEditing(id,true)
      },
      'reload-user-list' () {
        Vue.nextTick(() => this.refresh())
      },
      'show-result-users-list' (result) {
        this.showResult = true
        this.result = result
      },
      'hideDialog' () {
        this.onHide()
      },
      'show-confirm-dialog' (type, dialog, data) {
        this.showDialog(type, dialog, data)
      }
    }
  }
</script>
<style src="./css/pagination.css"></style>
