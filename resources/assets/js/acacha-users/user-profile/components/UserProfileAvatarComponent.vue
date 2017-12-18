<template>
    <img class="profile-user-img img-responsive img-circle" :src="avatar_url" alt="User profile picture">
</template>

<script>
  import gravatar from 'gravatar'

  import { mapGetters } from 'vuex'

  export default {
    name: 'UserProfileAvatar',
    computed: {
      avatar_url () {
        return gravatar.url(this.user.email, {protocol: 'https', s: '80', d: 'mm', r: 'g'})
      },
      ...mapGetters({
        user: 'acacha-users/user'
      })
    }
  }
</script>