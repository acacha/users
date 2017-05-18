<template>
    <div id="users-list">
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
                        <button type="button" class="btn btn-outline" id="confirm-user-deletion-button" @click="deleteUser()"><i v-if="this.deletingUser" id="deleting-user-spinner" class="fa fa-refresh fa-spin"></i>  Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-success" id="user-list-box" :class="{ 'collapsed-box': collapsed }">
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
                <vuetable ref="vuetable"
                          :api-url="apiUrl"
                          :fields="columns"
                          pagination-path=""
                          :css="css.table"
                          :api-mode="true"
                          row-class="um-user-row"
                          :append-params="moreParams"
                          :multi-sort="true"
                          detail-row-component="my-detail-row"
                          @vuetable:pagination-data="onPaginationData"
                          @vuetable:cell-clicked="onCellClicked"
                ></vuetable>
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
        </div>
    </div>

</template>


<script>
  import Vuetable from 'vuetable-2/src/components/Vuetable'

  import FilterBar from './FilterBar'
  import DetailRow from './UserDetailRow'
  import UserListCustomActions from './UsersListCustomActions'
  import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
  import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

  import VueEvents from 'vue-events'
  Vue.use(VueEvents)

  Vue.component('filter-bar', FilterBar)
  Vue.component('my-detail-row', DetailRow)
  Vue.component('users-list-custom-actions', UserListCustomActions)

  export default {
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
        deletingUser: false,
        userIdToDelete : null,
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
      deleteUser () {
        this.deletingUser = true;
        var id = document.querySelector('div#users-list div.modal div.modal-footer input#user_id').value
        var component = this
        axios.delete(this.apiUrl + '/' + id)
          .then(function (response) {
            component.$refs.vuetable.reload()
            $('#confirm-user-deletion-modal').modal('hide')
            component.deletingUser = false;
          })
          .catch(function (error) {
            console.log(error);
            component.deletingUser = false;
          });
      },
      onChangePage (page) {
        this.$refs.vuetable.changePage(page)
      },
      onPaginationData (paginationData) {
        this.$refs.pagination.setPaginationData(paginationData)
        this.$refs.paginationInfo.setPaginationData(paginationData)
      },
      onCellClicked (data, field, event) {
        console.log('cellClicked: ', field.name)
        this.$refs.vuetable.toggleDetailRow(data.id)
      }
    },
    events: {
      'filter-set' (filterText) {
        this.moreParams = {
          filter: filterText
        }
        Vue.nextTick( () => this.$refs.vuetable.refresh() )
      },
      'filter-reset' () {
        this.moreParams = {}
        Vue.nextTick( () => this.$refs.vuetable.refresh() )
      }
   }
  }
</script>
<style>
    .pagination {
        margin: 0;
        float: right;
    }
    .pagination a.page {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.page.active {
        color: white;
        background-color: #337ab7;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }
    .pagination a.btn-nav {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }
    .pagination a.btn-nav.disabled {
        color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }
    .pagination-info {
        float: left;
    }
</style>
