import { createApp } from 'vue'
import { Quasar } from 'quasar'

import '@quasar/extras/material-icons/material-icons.css'

import 'quasar/src/css/index.sass'

// @ts-ignore: no declaration file for .vue modules
import App from './App.vue'
import router from './router'

const myApp = createApp(App)
myApp.use(Quasar, {
    plugins: {}
})

myApp.use(router)

myApp.mount('#app')