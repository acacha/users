<template>
    <adminlte-vue-modal :show="show" color="danger" @hide="onHide" :id="id">
        <h4 slot="title">{{title}}</h4>
        <p>{{body}}</p>
        <div class="modal-footer" slot="footer">
            <input type="hidden" id="user_id" value=""/>
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-outline" id="confirm-action-button"
                    @click="confirm">
                <i v-if="this.acting" id="acting-user-spinner" class="fa fa-refresh fa-spin"></i>  {{confirmText}}
            </button>
        </div>
    </adminlte-vue-modal>
</template>

<script>
  export default {
    props: {
      acting: {
        type: Boolean,
        default: false
      },
      show: {
        type: Boolean,
        default: false
      },
      title: {
        type: String,
        default: 'Confirm dialog'
      },
      body: {
        type: String,
        default: 'Are you sure you want to execute this action?'
      },
      id: {
        type: String,
        default: 'confirm-dialog'
      },
      confirmText: {
        type: String,
        default: 'Delete'
      }
    },
    methods: {
      onHide() {
        this.$emit('hide')
      },
      confirm() {
        this.$emit('confirm')
      }
    }
  }
</script>