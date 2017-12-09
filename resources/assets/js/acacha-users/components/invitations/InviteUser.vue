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
  import InviteUser from '../forms/mixins/Form'

  export default {
    mixins: [InviteUser],
    methods: {
      submit () {
        this.form.post(this.apiUri)
          .then(response => {
            console.log('Invited ok')
            this.$events.fire('reload-user-invitations')
          })
          .catch(error => {
            console.log('Invited error: ' + error)
            console.log(this.form.errors.all())
          })
      }
    }
  }

</script>