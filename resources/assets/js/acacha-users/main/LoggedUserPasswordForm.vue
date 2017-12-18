<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-password name="current_password" :form-name="formName"></adminlte-input-password>
            <adminlte-input-password :form-name="formName"></adminlte-input-password>
            <div class="box-footer">
                <button type="submit" class="btn btn-success" :disabled="isDisabled()">Change password</button>
            </div>
        </form>
    </div>
</template>

<script>
  import { AdminlteInputPasswordComponent } from 'acacha-adminlte-vue-forms'
  import {FlashMixin, DisabledSubmitMixin} from 'adminlte-vue'
  import Form, {FormsModule, UsesAcachaForms, registerForm, ClearErrorsMixin} from 'acacha-forms'
  import SubmitsFormField from './mixins/SubmitsFormField'

  const PasswordForm = new Form({
    current_password: '',
    password: ''
  })

  const API_ENDPOINT = '/api/v1/user/password'
  const ACACHA_FORM = FormsModule(PasswordForm)
  const ACACHA_FORM_VUEX_MODULE = 'logged-user-password-form'

  export default {
    name: 'LoggedUserPasswordForm',
    components: { AdminlteInputPasswordComponent },
    mixins: [FlashMixin, DisabledSubmitMixin, UsesAcachaForms, ClearErrorsMixin, SubmitsFormField],
    methods: {
      submit () {
        this.submitFormField('put', API_ENDPOINT, 'Password')
      }
    },
    beforeCreate () {
      registerForm(this, ACACHA_FORM_VUEX_MODULE, ACACHA_FORM)
    }
  }
</script>
