<template>
    <div id="invite-user">
        <div class="alert alert-success alert-dismissible" v-show="form.succeeded" id="add-user-invitation-result">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>User invited!</strong> <br>
        </div>
        <form role="form" id="invite-user-form" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <div class="box-body">
                <div class="form-group" :class="{ 'has-error': form.errors.has('email') }">
                    <div class="col-xs-10">
                        <input type="email" class="form-control" id="inputUserInvitationEmail" placeholder="Enter email"
                               name="email" value="" v-model="form.email">
                        <transition name="fade">
                            <span class="help-block" id="inputUserInvitationEmailError" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                        </transition>
                    </div>
                    <div class="col-xs-2">
                        <button type="submit" class="btn btn-primary" id="add-user-invitation" :disabled="form.errors.any()"><i v-if="form.submitting" id="add-user-invitation-spinner" class="fa fa-refresh fa-spin"></i> Invite</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<style src="../css/fade.css"></style>

<script>

  import Form from 'acacha-forms'

  export default {
    data: function () {
      return {
        form: new Form({ email: '' })
      }
    },
    props: {
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/v1/management/users/invitations'
      }
    },
    methods: {
      submit () {
        const API_URL = this.apiUrl
        this.form.post(API_URL)
          .then(response => {
            console.log('Invited ok')
            this.$events.fire('reload-user-invitations')
          })
          .catch(error => {
            console.log('Invited error: ' + error)
            console.log(this.form.errors.all())
          })
      },
      clearErrors (name) {
        this.form.errors.clear(name)
      }
    },
    mounted () {
      this.form.clearOnSubmit = true
    }
  }

</script>