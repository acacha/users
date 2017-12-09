<template>
    <div id="google_apps_users_list">
        <adminlte-vue-box color="success" :collapsed="isCollapsed" id="users-list-box" :loading="loading">
            <span slot="title">Google Apps Users Lists</span>

            <div class="table-responsive" style="clear: left;">
                <vuetable ref="googleappsusersvuetable"
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

  import List from '../mixins/List.js'

  export default {
    mixins: [
      List
    ],
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
  }
</script>