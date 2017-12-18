export default {
  methods: {
    submitFormWithMessage (action, uri, message) {
      this.send(action, uri).then(response => {
        this.success(message)
      }).catch(error => {
        if (error.response.status === 422) return
        this.flash('' + error, 'Oooppssss something went wrong!', 'danger', 'fa-ban')
      })
    }
  }
}
