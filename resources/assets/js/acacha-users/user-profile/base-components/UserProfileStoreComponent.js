import {AcachaUsersModule} from '../../store/modules/user'

import {FormsModule} from 'acacha-forms'

import Form from 'acacha-forms'

const initialForm = new Form({
  name: '',
  email: '',
  password: ''
})

const Forms = FormsModule(initialForm)

const UserProfileStoreComponent =  {
  created() {
    const store = this.$store;
    if (!(store && store.state && store.state['acacha-users'])) store.registerModule('acacha-users', AcachaUsersModule);
    if (!(store && store.state && store.state['acacha-forms']))  store.registerModule('acacha-forms', Forms)
  }
}

export {
  UserProfileStoreComponent
}

export default UserProfileStoreComponent