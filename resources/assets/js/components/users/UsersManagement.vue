<template>
    <div id="users-manager-panel">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Create User <i class="fa fa-plus"></i></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <create-user :api-url="apiUrl"></create-user>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Invitations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">

                <invite-user :api-url="apiUrl  + '/invitations'"></invite-user>

                <users-management-user-invitations-list :api-url="apiUrl + '/invitations'"></users-management-user-invitations-list>

            </div>
        </div>

        <users-management-users-list :api-url="apiUrl"></users-management-users-list>

    </div>

</template>

<script>

  import UsersManagementUsersList from './UsersList'
  import UsersManagementUserInvitationsList from './UserInvitationsList'
  import InviteUser from './InviteUser'
  import CreateUser from './CreateUser'

  export default {
    components: {
      UsersManagementUsersList,
      UsersManagementUserInvitationsList,
      InviteUser,
      CreateUser
    },
    props: {
      // a number with default value
      apiUrl: {
        type: String,
        default: 'http://localhost:8080/api/management/users'
      }
    }
  }
</script>
