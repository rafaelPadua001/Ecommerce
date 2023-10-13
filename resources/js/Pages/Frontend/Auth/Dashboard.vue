<template>
    <div>
      <AppBar />
    </div>
   
    <v-navigation-drawer
        expand-on-hover
        rail
        class="bg-cyan-darken-2"
      >
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
            :title="customers.first_name + ' ' + customers.last_name"
            :subtitle="customers.email"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item prepend-icon="fas fa-home fa-2xs" title="Home" value="dashboard" to="/dashboard"></v-list-item>
          <v-list-item prepend-icon="fas fa-user fa-2xs" title="Profile" value="Profile" :to="`/ProfileCustomer/${customers.id}`"></v-list-item>
          <v-list-item prepend-icon="fas fa-cart-shopping fa-2xs" title="Carts" value="carts" to="/carts"></v-list-item>
          <v-list-item prepend-icon="fas fa-bag-shopping" title="Pedidos" value="myfiles"></v-list-item>
          <!-- <v-list-item prepend-icon="mdi-account-multiple" title="Pedidos" value="shared"></v-list-item> -->
          <v-list-item prepend-icon="fas fa-tag fa-2xs" title="Cupons" value="starred"></v-list-item>
          <v-list-item prepend-icon="fas fa-message fa-2xs" title="Messages" value="starred"></v-list-item>
        </v-list>
      </v-navigation-drawer>

    <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
 
      <v-container>
     
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-footer
      app
      name="footer"
    >
      <v-btn
        class="mx-auto"
        variant="text"
        @click="print('footer')"
      >
        Get data
      </v-btn>
    </v-footer>
  
</template>

<script>
  import { useLayout } from 'vuetify'
  import AppBar from '../Layout/AppBar.vue';
import axios from 'axios';

  const Child = {
    setup (props, ctx) {
      const { getLayoutItem } = useLayout()

      function print (key) {
        alert(JSON.stringify(getLayoutItem(key), null, 2))
      }

      return () => ctx.slots.default({ print })
    },
  }

  export default {
    components: { Child, AppBar },
    data: () => ({
      customers: [],
    }),
    methods: {
      getUser(){
                axios.get('/customer')
                .then((response) => {
                  console.log(response);
                    return this.customers = response.data;
                })
                .catch((response) => {
                    alert('Error: ' + response);
                });
            }
    },
    
    mounted() {
            this.getUser();
        }
  }
  
  
</script>