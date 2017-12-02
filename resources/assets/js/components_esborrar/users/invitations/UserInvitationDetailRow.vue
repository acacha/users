<template>
  <div class="box box-primary" :id="'user-invitation-' + rowData.id + '-detail-row'">
    <div class="box-header with-border">
      <h3 class="box-title">User Invitation: {{rowData.email}} </h3>
    </div>
    <div class="box-body">
      <strong>Id</strong>

      <p class="text-muted">
        {{rowData.id}}
      </p>

      <hr>

      <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

      <p class="text-muted">
        <editable resource="user-invitation" :id="rowData.id" :edit="editing" :content="rowData.email" name="email" :api-url="apiUrl" after-save-event="reload-user-invitations-list"></editable>
      </p>

      <hr>

      <strong><i class="fa fa-cog margin-r-5"></i> State</strong>

      <p class="text-muted">
        <!--TODO Dropdown with possible values!-->
        <editable resource="user-invitation" :id="rowData.id" :edit="editing" :content="rowData.state" name="state" :api-url="apiUrl" after-save-event="reload-user-invitations-list"></editable>
      </p>

      <hr>

      <strong><i class="fa fa-user margin-r-5"></i> User id</strong>

      <!--TODO Dropdown for changing user invitation? Or show user name instead of id!-->
      <p class="text-muted">
        {{rowData.user_id}}
      </p>

      <hr>


      <strong><i class="fa fa-calendar margin-r-5"></i> Created at</strong>

      <p class="text-muted">{{rowData.created_at}}</p>

      <hr>

      <strong><i class="fa fa-calendar margin-r-5"></i> Updated at</strong>

      <p class="text-muted">{{rowData.updated_at}}</p>
    </div>
  </div>
</template>

<script>
  import DetailRow from '../mixins/DetailRow'

  import Editable from '../Editable'

  Vue.component('editable', Editable)

  export default {
    computed: {
      apiUrl: function () {
        return this.store.apiUrl + '-invitations'
      }
    },
    mixins: [
      DetailRow
    ],
    components: {
      Editable
    }
  }
</script>
