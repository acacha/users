<template>
    <div class="row">
        <div class="col-md-3">
            <user-profile-info-box></user-profile-info-box>
        </div>
        <div class="col-md-9">
            <user-profile-form-tabs></user-profile-form-tabs>
        </div>
    </div>
</template>

<script>
  import UserProfileInfoBox from './components/UserProfileInfoBoxComponent.vue'
  import UserProfileFormTabs from './components/UserProfileFormTabsComponent.vue'

  export default {
    name: 'UserProfile',
    components: {
      'user-profile-info-box': UserProfileInfoBox,
      'user-profile-form-tabs': UserProfileFormTabs
    },
    created () {
      this.$store.dispatch('getUser', true)
    }
  }
</script>