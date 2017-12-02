import store from '../Store'

import Vuetable from 'vuetable-2/src/components/Vuetable'

import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'

import ConfirmDialog from '../ConfirmDialog.vue'

import VueEvents from 'vue-events'
Vue.use(VueEvents)

export default {
  props: {
    apiUri: {
      type: String,
      default: '/api/v1/users'
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
    VuetablePaginationInfo,
    ConfirmDialog
  },
  data () {
    return {
      showResult: false,
      result: '',
      store: store,
      dialogHasToBeShown: false,
      isCollapsed: this.collapsed,
      loading: false,
      performingAction: false,
      confirmDialogTitle: 'Confirm action',
      confirmDialogBody: 'Are you sure you want to execute this action?',
      confirmDialogText: 'Delete',
      currentData: null,
      confirmDialogType: null,
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
    showDialog (type, dialog, data) {
      if (this.checkDialogType(type)) {
        this.confirmDialogTitle = dialog.title
        this.confirmDialogBody = dialog.body
        this.confirmDialogText = dialog.confirmText
        this.currentData = data
        this.confirmDialogType = type
        this.dialogHasToBeShown = true
      }
    },
    onHide () {
      this.dialogHasToBeShown = false
    },
    onConfirm () {
      this.executePostConfirmAction()
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
