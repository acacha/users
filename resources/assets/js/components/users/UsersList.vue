<template>
    <div id="users-list">

        <!-- TODO Modal adminlte-->
        <div class="modal modal-danger" id="confirm-user-deletion-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Confirm User deletion</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="user_id" value=""/>
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline" id="confirm-user-deletion-button" @click="deleteResource()"><i v-if="this.deleting" id="deleting-user-spinner" class="fa fa-refresh fa-spin"></i>  Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!--TODO adminlte box component-->
        <div class="box box-success" id="users-list-box" :class="{ 'collapsed-box': collapsed }">
            <div class="box-header with-border">
                <h3 class="box-title">Users Lists</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i v-if="collapsed" class="fa fa-plus"></i>
                        <i v-else class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <filter-bar></filter-bar>
                <div class="table-responsive">
                    <vuetable ref="vuetable"
                              :api-url="apiUrl"
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
                <div class="vuetable-pagination">
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
            </div>
            <div class="overlay" v-if="loading">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

</template>


<script>
  import Vuetable from 'vuetable-2/src/components/Vuetable'

  import UsersListFilterBar from './UsersListFilterBar'
  import UserDetailRow from './UserDetailRow'
  import UserListCustomActions from './UsersListCustomActions'
  import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
  import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

  import VueEvents from 'vue-events'
  Vue.use(VueEvents)

  Vue.component('filter-bar', UsersListFilterBar)
  Vue.component('user-detail-row', UserDetailRow)
  Vue.component('users-list-custom-actions', UserListCustomActions)

  import User from './mixins/User'

  import store from './Store'

  export default {
    mixins: [
      User
    ],
    components: {
      Vuetable,
      VuetablePagination,
      VuetablePaginationInfo
    },
    props: {
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/management/users'
      },
      collapsed: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        loading: false,
        deleting: false,
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
        css: {
          table: {
            tableClass: 'table table-bordered table-striped table-hover',
            ascendingIcon: 'glyphicon glyphicon-chevron-up',
            descendingIcon: 'glyphicon glyphicon-chevron-down'
          },
          pagination: {
            wrapperClass: 'pagination',
            activeClass: 'active',
            disabledClass: 'disabled',
            pageClass: 'page',
            linkClass: 'link',
            icons: {
              first: 'glyphicon glyphicon-step-backward',
              prev: 'glyphicon glyphicon-chevron-left',
              next: 'glyphicon glyphicon-chevron-right',
              last: 'glyphicon glyphicon-step-forward',
            },
          }
        },
        moreParams: {}
      }
    },
    methods: {
      reload() {
        this.$refs.vuetable.reload()
      },
      refresh() {
        this.$refs.vuetable.refresh()
      },
      detailRowEditing(id, editing) {
        if (!this.$refs.vuetable.isVisibleDetailRow(id)) {
          store.editing[id] = editing
        }
        this.$refs.vuetable.toggleDetailRow(id)
      },
      showDeleteResourceDialog(id) {
        $('#confirm-user-deletion-modal').on('show.bs.modal', function (event) {
          var modal = $(this)
          modal.find('.modal-footer input#user_id').val(id)
        })
        $('#confirm-user-deletion-modal').modal('show')
      },
      deleteResource () {
        this.deleting = true;
        var id = document.querySelector('div#users-list div.modal div.modal-footer input#user_id').value
        var component = this
        axios.delete(this.apiUrl + '/' + id)
          .then(function (response) {
            component.reload()
            $('#confirm-user-deletion-modal').modal('hide')
            component.deleting = false;
          })
          .catch(function (error) {
            console.log(error);
            component.deleting = false;
          });
      },
      toogle () {
        $("#user-invitations-list-box").toggleBox()
      },
      onChangePage (page) {
        this.$refs.vuetable.changePage(page)
      },
      onPaginationData (paginationData) {
        this.$refs.pagination.setPaginationData(paginationData)
        this.$refs.paginationInfo.setPaginationData(paginationData)
      },
      onCellClicked (data, field, event) {
        this.$refs.vuetable.toggleDetailRow(data.id)
      },
      showLoader() {
        this.loading = true
      },
      hideLoader() {
        this.loading = false
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
      'show-delete-user-dialog' (id) {
        this.showDeleteResourceDialog(id)
      },
      'toogle-show-user' (id) {
        this.detailRowEditing(id,false)
      },
      'toogle-edit-user' (id) {
        this.detailRowEditing(id,true)
      }
    }
  }
</script>
<style src="./css/pagination.css"></style>
