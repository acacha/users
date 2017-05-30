<template>
  <div class="custom-actions">
    <button v-scroll-to="'#user-invitation-' + rowData.id + '-detail-row'" class="btn btn-sm btn-primary" :id="'show-user-invitation-' + rowData.id" @click="toogleShow('user-invitations',rowData)" :disabled="!laravel.user.can['view-user-invitations']"><i class="glyphicon glyphicon-zoom-in"></i></button>
    <button v-scroll-to="'#user-invitation-' + rowData.id + '-detail-row'" class="btn btn-sm btn-success" :id="'edit-user-invitation-' + rowData.id" @click="toogleEdit('user-invitations',rowData)" :disabled="!laravel.user.can['edit-user-invitations']"><i class="glyphicon glyphicon-pencil"></i></button>
    <button class="btn btn-sm btn-danger" :id="'delete-user-invitation-' + rowData.id" @click="deleteResource('user-invitations',rowData)" :disabled="!laravel.user.can['delete-user-invitations']"><i class="glyphicon glyphicon-trash"></i></button>
  </div>
</template>

<script>
  import VueScrollTo from 'vue-scrollto'
  Vue.use(VueScrollTo)

  import CustomActions from '../mixins/CustomActions'

  export default {
    mixins: [
      CustomActions
    ]
  }
</script>

<style src="../css/customActions.css"></style>
