<template>
  <div class="box box-primary" :id="'user-' + rowData.id + '-detail-row'">
    <div class="box-header with-border">
      <h3 class="box-title">User: {{rowData.name}} </h3>
    </div>
    <div class="box-body">
      <strong>Id</strong>

      <p class="text-muted">
        {{rowData.id}}
      </p>

      <hr>

      <strong><i class="fa fa-user margin-r-5"></i> Name</strong>

      <p class="text-muted">
        <editable resource="user" :id="rowData.id" :edit="editing" :content="rowData.name" name="name" :api-url="apiUrl" after-save-event="reload-users-list"></editable>
      </p>

      <hr>

      <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

      <p class="text-muted">
        <editable resource="user" :id="rowData.id" :edit="editing" :content="rowData.email" name="email" :api-url="apiUrl" after-save-event="reload-users-list"></editable>
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
  import DetailRow from './mixins/DetailRow'
  import Editable from './Editable'

  Vue.component('editable', Editable)

  export default {
    computed: {
      apiUrl: function () {
        return this.store.apiUrl
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
