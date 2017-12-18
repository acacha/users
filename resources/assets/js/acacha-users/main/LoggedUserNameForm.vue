<template>
    <div>
        <adminlte-alert :alert="alert"></adminlte-alert>
        <form role="form" method="post" @submit.prevent="submit" @keydown="clearErrors($event.target.name)">
            <adminlte-input-text name="name" :form-name="formName"></adminlte-input-text>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" :disabled="isDisabled()">Change name</button>
            </div>
        </form>
    </div>
</template>

<script>
  import { AdminlteInputTextComponent } from 'acacha-adminlte-vue-forms'
  import { FlashMixin, DisabledSubmitMixin } from 'adminlte-vue'
  import Form, { FormsModule, UsesAcachaForms, registerForm, ClearErrorsMixin } from 'acacha-forms'
  import InteractsWithUser from './mixins/interactsWithUser'
  import SubmitsFormField from './mixins/SubmitsFormField'

  const UserNameForm = new Form({
    name: ''
  })

  const API_ENDPOINT = '/api/v1/user/name'
  const ACACHA_FORM = FormsModule(UserNameForm)
  const ACACHA_FORM_VUEX_MODULE = 'logged-user-name-form'

  export default {
    name: 'LoggedUserNameForm',
    components: { AdminlteInputTextComponent },
    mixins: [FlashMixin, DisabledSubmitMixin, InteractsWithUser, UsesAcachaForms, ClearErrorsMixin, SubmitsFormField],
    methods: {
      submit () {
        this.submitFormField('put', API_ENDPOINT, 'Name')
      },
      fillForm () {
        // Update Form object with Current User
        const fields = [
          {
            field: 'name',
            value: this.user.name
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
