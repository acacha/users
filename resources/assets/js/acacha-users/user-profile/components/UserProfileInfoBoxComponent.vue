<template>
    <adminlte-box color="primary" :no-title="true">
        <user-profile-avatar></user-profile-avatar>

        <h3 class="profile-username text-center avoid-overflow" data-toggle="tooltip" :title="user.name"> {{ user.name }} </h3>

        <p class="text-muted text-center avoid-overflow" data-toggle="tooltip" :title="user.email"> {{ user.email }} </p>

        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b>Created at</b> <a class="pull-right" data-toggle="tooltip" :title="user.created_at">{{ created_at }} </a>
            </li>
            <li class="list-group-item">
                <b>Updated at</b> <a class="pull-right" data-toggle="tooltip" :title="user.updated_at">{{ updated_at }} </a>
            </li>
        </ul>
    </adminlte-box>
</template>

<style>
    .avoid-overflow {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
  import {AdminlteBoxComponent} from 'adminlte-vue'
  import UserProfileAvatar from './UserProfileAvatarComponent.vue'
  import { mapGetters } from 'vuex'
  import moment from 'moment'

  export default {
    name: 'UserProfileInfoBox',
    components: {
      'adminlte-box': AdminlteBoxComponent,
      'user-profile-avatar': UserProfileAvatar
    },
    computed: {
      created_at () {
        return moment(this.user.created_at).fromNow()
      },
      updated_at () {
        return moment(this.user.updated_at).fromNow()
      },
      ...mapGetters({
        user: 'acacha-users/user'
      })
    }
  }
</script>
