import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      user: 'acacha-users/user'
    })
  },
  watch: {
    user () {
      this.fillForm()
    }
  }
}
