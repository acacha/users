import store from '../Store.js'

export default {
  data() {
    return {
      store : store
    }
  },
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  computed: {
    // a computed getter
    editing: function () {
      return this.store.editing[this.rowData.id]
    }
  },
}
