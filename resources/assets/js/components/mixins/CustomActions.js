import Dialog from './Dialog'

export default {
  mixins: [Dialog],
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  data() {
    return {
      laravel: window.Laravel
    }
  },
  methods: {
    toogleShow (name, data) {
      this.$events.fire('toogle-show-' + name, data.id)
    },
    toogleEdit (name, data) {
      this.$events.fire('toogle-edit-' + name, data.id)
    }
  }
}
