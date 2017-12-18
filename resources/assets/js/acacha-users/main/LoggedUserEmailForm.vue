<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-password name="current_password" :form-name="formName"></adminlte-input-password>
            <adminlte-input-email :form-name="formName"></adminlte-input-email>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" :disabled="isDisabled()">Change email</button>
            </div>
        </form>
    </div>
</template>

<script>
  import { AdminlteInputEmailComponent, AdminlteInputPasswordComponent } from 'acacha-adminlte-vue-forms'
  import { FlashMixin, DisabledSubmitMixin } from 'adminlte-vue'
  import Form, { FormsModule, UsesAcachaForms, registerForm, ClearErrorsMixin } from 'acacha-forms'
  import InteractsWithUser from './mixins/interactsWithUser'
  import SubmitsFormField from './mixins/SubmitsFormField'

  const UserEmailForm = new Form({
    current_password: '',
    email: ''
  })

  const API_ENDPOINT = '/api/v1/user/email'
  const ACACHA_FORM = FormsModule(UserEmailForm)
  const ACACHA_FORM_VUEX_MODULE = 'logged-user-email-form'

  export default {
    name: 'LoggedUserEmailForm',
    components: { AdminlteInputEmailComponent, AdminlteInputPasswordComponent },
    mixins: [FlashMixin, DisabledSubmitMixin, InteractsWithUser, UsesAcachaForms, ClearErrorsMixin, SubmitsFormField],
    methods: {
      submit () {
        this.submitFormField('put', API_ENDPOINT, 'Email')
      },
      fillForm () {
        // Update Form object with Current User
        const fields = [
          {
            field: 'name',
            value: this.user.name
          },
          {
            field: 'email',
            value: this.user.email
          }
        ]
        this.updateForm(fields)
      }
    },
    beforeCreate () {
      registerForm(this, ACACHA_FORM_VUEX_MODULE, ACACHA_FORM)
    }
  }
</script>
