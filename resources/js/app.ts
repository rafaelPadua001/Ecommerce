import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import "@fortawesome/fontawesome-free/css/all.css"

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, fa } from 'vuetify/iconsets/fa'
//import money from 'v-money3'

import * as labsComponents from 'vuetify/labs/components'

//import Router
import router from './Router'

//import product seo tags
import productSeo from './Pages/Frontend/ProductSeo/productSeo.vue'

const vuetify = createVuetify({
  components: {
    ...components,
    ...labsComponents,
  },
  directives,
  icons: {
    defaultSet: 'fa',
    aliases,
    sets: {
      fa,
    },
  },
})

createApp(App)
  .use(vuetify)
  .use(router)
  .component('productSeo', productSeo)
 // .use(money)
  .mount('#app');