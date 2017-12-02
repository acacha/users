import store from '../Store'

import Vuetable from 'vuetable-2/src/components/Vuetable'

import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

import VueEvents from 'vue-events'
Vue.use(VueEvents)

export default {
  props: {
    apiUrl: {
      type: String,
      default: 'http://localhost:8080/api/management/users'
    },
    collapsed: {
      type: Boolean,
      default: false
    },
    resource: {
      type: String,
      required: true
    }
  },
  components: {
    Vuetable,
    VuetablePagination,
    VuetablePaginationInfo
  },
  data () {
    return {
      isCollapsed: this.collapsed,
      loading: false,
      deleting: false,
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
            last: 'glyphicon glyphicon-step-forward'
          }
        }
      },
      moreParams: {}
    }
  },
  methods: {
    toogle () {
      $('#' + this.resource + '-list-box').toggleBox()
    },
    deleteResource () {
      this.deleting = true
      var id = document.querySelector(
        'div#' + this.resource + '-list div.modal div.modal-footer input#' + this.resource + '_id').value

      var component = this
      axios.delete(this.apiUrl + '/' + id)
        .then(function (response) {
          component.$refs.vuetable.reload()
          $('#confirm-' + component.resource + '-deletion-modal').modal('hide')
          component.deleting = false
        })
        .catch(function (error) {
          component.deleting = false
        })
    },
    showDeleteDialog (id) {
      var component = this
      $('#confirm-' + this.resource + '-deletion-modal').on('show.bs.modal', function (event) {
        var modal = $(this)
        modal.find('.modal-footer input#' + component.resource + '_id').val(id)
      })
      $('#confirm-' + component.resource + '-deletion-modal').modal('show')
    },
    reload () {
      this.$refs.vuetable.reload()
    },
    refresh () {
      this.$refs.vuetable.refresh()
    },
    detailRowEditing (id, editing) {
      if (!this.$refs.vuetable.isVisibleDetailRow(id)) {
        store.editing[id] = editing
      }
      this.$refs.vuetable.toggleDetailRow(id)
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
    showLoader () {
      this.loading = true
    },
    hideLoader () {
      this.loading = false
    }
  }
}
