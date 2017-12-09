import axios from 'axios'

function getCrsfToken() {
  // Get if exists CSRF TOKEN from HTML meta (as Laravel do)
  let tokenMeta = document.head.querySelector('meta[name="csrf-token"]')
  if (tokenMeta) return tokenMeta.content
  return null
}

function configureAxios() {
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  let token = getCrsfToken()
  if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

function httpClient(){
  if (window.axios) {
    return window.axios
  } else {
    configureAxios()
    return axios
  }
}

export default {
  getUser (cb) {
    let http = httpClient()
    http.get('/api/v1/user').then(response => {
      cb(response.data)
    }).catch( error => {
      console.log(error)
    })
  }
}
