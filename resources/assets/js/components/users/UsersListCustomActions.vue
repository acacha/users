  <template>
    <div class="user-list-custom-actions">
      <button class="btn btn-sm btn-primary" :id="'show-user-' + rowData.id" @click="itemAction('view-item', rowData, rowIndex)"><i class="glyphicon glyphicon-zoom-in"></i></button>
      <button class="btn btn-sm btn-success" :id="'edit-user-' + rowData.id" @click="itemAction('edit-item', rowData, rowIndex)"><i class="glyphicon glyphicon-pencil"></i></button>
      <button class="btn btn-sm btn-danger" :id="'delete-user-' + rowData.id" @click="deleteUser(rowData, rowIndex)"><i class="glyphicon glyphicon-trash"></i></button>
    </div>
  </template>

  <script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    methods: {
      deleteUser (data, index) {
        var component = this
        $('#confirm-user-deletion-modal').on('show.bs.modal', function (event) {
          var modal = $(this)
          modal.find('.modal-footer input#user_id').val(component.rowData.id)
        })
        $('#confirm-user-deletion-modal').modal('show')
      },
      itemAction (action, data, index) {
        console.log('user-list-custom-actions: ' + action, data.name, index)
      }
    }
  }
  </script>

  <style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
  </style>
