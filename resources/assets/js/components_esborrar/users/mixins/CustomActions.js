export default {
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
    deleteResource (name,data) {
      this.$events.fire('show-delete-' + name + '-dialog', data.id)
    },
    toogleShow(name,data) {
      this.$events.fire('toogle-show-' + name , data.id)
    },
    toogleEdit(name,data) {
      this.$events.fire('toogle-edit-' + name , data.id)
    }
  }
}
