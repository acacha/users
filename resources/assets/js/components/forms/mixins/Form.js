import Form from 'acacha-forms'

export default {
  data: function () {
    return {
      form: new Form({ email: '' })
    }
  },
  props: {
    apiUri: {
      type: String,
      default: '/api/v1/users/invitations'
    }
  },
  methods: {
    clearErrors (name) {
      this.form.errors.clear(name)
    }
  },
  mounted () {
    this.form.clearOnSubmit = true
  }
}