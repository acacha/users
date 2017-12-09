<template>
    <div id="user-invitation-list">

        <confirm-dialog
                id="user-invitations-list-confirm-modal"
                :show="dialogHasToBeShown"
                :acting="performingAction"
                :title="confirmDialogTitle"
                :body="confirmDialogBody"
                @hide="onHide"
                @confirm="onConfirm"
                :confirm-text="confirmDialogText"
        ></confirm-dialog>

        <adminlte-vue-box color="success" :collapsed="isCollapsed" id="user-invitations-list-box" :loading="loading">
            <span slot="title">Invitations Lists</span>

            <user-invitations-list-global-actions :selected="selectedItems()"></user-invitations-list-global-actions>

            <user-invitations-list-filter-bar></user-invitations-list-filter-bar>

            <adminlte-vue-alert color="success" title="Done!" v-if="showResult" id="user-invitations-list-result"
                                style="clear: both;">
                {{ result }}
            </adminlte-vue-alert>

            <div class="table-responsive" style="clear: left;">
                <vuetable ref="vuetable"
                          :api-url="apiUri"
                          :fields="columns"
                          pagination-path=""
                          :css="css.table"
                          :api-mode="true"
                          row-class="um-user-invitation-row"
                          :append-params="moreParams"
                          :multi-sort="true"
                          detail-row-component="user-invitations-detail-row"
                          @vuetable:pagination-data="onPaginationData"
                          @vuetable:cell-clicked="onCellClicked"
                          @vuetable:loading="showLoader"
                          @vuetable:loaded="hideLoader"
                ></vuetable>
            </div>

            <div class="vuetable-pagination" id="user-invitations-list-vuetable-pagination">
                <vuetable-pagination-info ref="paginationInfo"
                                          info-class="pagination-info"
                                          infoTemplate="Displaying {from} to {to} of {total} invitations"
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

  import UserInvitationsListFilterBar from './UserInvitationsListFilterBar'
  import UserInvitationsListGlobalActions from './UserInvitationsListGlobalActions'
  import UserInvitationDetailRow from './UserInvitationDetailRow'
  import UserInvitationsListCustomActions from './UserInvitationsListCustomActions'

  Vue.component('user-invitations-list-filter-bar', UserInvitationsListFilterBar)
  Vue.component('user-invitations-list-global-actions', UserInvitationsListGlobalActions)
  Vue.component('user-invitations-detail-row', UserInvitationDetailRow)
  Vue.component('user-invitations-list-custom-actions', UserInvitationsListCustomActions)

  import List from '../mixins/List.js'
  import PerformAction from '../mixins/PerformAction.js'
  import HideDialog from '../mixins/HideDialog.js'

  export default {
    mixins: [
      List,PerformAction, HideDialog
    ],
    components: {
      UserInvitationsListFilterBar
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
            name: 'id',
            sortField: 'id',
          },
          {
            name: 'email',
          },
          {
            name: 'state',
          },
          {
            name: 'user_id',
          },
          {
            name: 'created_at',
          },
          {
            name: 'updated_at',
          },
          {
            name: '__component:user-invitations-list-custom-actions',
            title: 'Actions',
            titleClass: 'text-center',
            dataClass: 'text-center'
          }
        ]
      }
    },
    methods: {
      selectedItems () {
        if (this.$refs.vuetable) return this.$refs.vuetable.selectedTo
        return []
      },
      checkDialogType (type) {
        return this.validDialogs().includes(type)
      },
      validDialogs() {
        //TODO
        return ['delete-user-invitation','']
      },
      executePostConfirmAction () {
        switch(this.confirmDialogType) {
          case 'delete-user-invitation':
            this.deleteUserInvitation(this.currentData)
            break;
          //TODO
          case 'reset-user-password':
            this.resetPassword(this.currentData)
            break;s
        }
      },
      deleteUserInvitation (data) {
        this.performAction(
          data,
          '/' + data.id,
          [],
          'User invitation for ' + data.email + ' has been deleted!',
          'delete',
          'show-result-user-invitations-list'
        )
        this.reload()
      },
      hideDialog() {
        this.performingAction = false
        this.dialogHasToBeShown = false
      }
    },
    events: {
      'filter-set-user-invitations-list' (filterText) {
        this.moreParams = {
          filter: filterText
        }
        Vue.nextTick(() => this.refresh())
      },
      'filter-reset-user-invitations-list' () {
        this.moreParams = {}
        Vue.nextTick(() => this.refresh())
      },
      'reload-user-invitations-list' () {
        Vue.nextTick(() => this.reload())
      },
      'show-delete-user-invitations-dialog' (id) {
        this.showDeleteDialog(id)
      },
      'toogle-show-user-invitations' (id) {
        this.detailRowEditing(id,false)
      },
      'toogle-edit-user-invitations' (id) {
        this.detailRowEditing(id,true)
      },
      'reload-user-invitations' () {
        Vue.nextTick(() => this.refresh())
      },
      'collapse-user-invitations-list' () {
        this.isCollapsed = true
      },
      'expand-user-invitations-list' () {
        this.isCollapsed = false
      },
      'show-confirm-dialog' (type, dialog, data) {
        this.showDialog(type, dialog, data)
      },
      'show-result-user-invitations-list' (result) {
        this.showResult = true
        this.result = result
      },
      'hideDialog' () {
        this.onHide()
      }
    }
  }
</script>
<style src="../css/pagination.css"></style>
