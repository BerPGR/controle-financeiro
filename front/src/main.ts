import { createApp } from 'vue'
import { Quasar, Notify } from 'quasar'
import { createPinia } from 'pinia'
import HighchartsVue from 'highcharts-vue'
import '@quasar/extras/material-icons/material-icons.css'

import 'quasar/src/css/index.sass'

// @ts-ignore: no declaration file for .vue modules
import App from './App.vue'
import router from './router'

const pinia = createPinia()
const myApp = createApp(App)

myApp.use(Quasar, {
    plugins: {
        Notify
    }
})
myApp.use(pinia)
myApp.use(router)
myApp.use(HighchartsVue)

myApp.mount('#app')