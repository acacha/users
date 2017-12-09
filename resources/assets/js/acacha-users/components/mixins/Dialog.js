export default {
  methods: {
    confirmDialog (type, data) {
      this.$events.fire('show-confirm-dialog', type, this.getDialogByType(type), data)
    }
  }
}
