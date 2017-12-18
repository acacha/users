import {AcachaUsersModule} from '../../store/modules/user'

const UserProfileStoreComponent = {
  created () {
    const store = this.$store
    if (!(store && store.state && store.state['acacha-users'])) store.registerModule('acacha-users', AcachaUsersModule)
  }
}

export {
  UserProfileStoreComponent
}

export default UserProfileStoreComponent
