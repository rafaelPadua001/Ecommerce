<template>
  <div>
    <AppBar />
  </div>

  <v-navigation-drawer expand-on-hover rail theme="dark">
   
    <v-list>
      
      <v-list-item v-if="!customers.avatarImage && !avatarUrl" prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
        :title="customers.first_name + ' ' + customers.last_name" :subtitle="customers.email"></v-list-item>
      <v-list-item v-else-if="avatarUrl && !customers.avatarImage" :prepend-avatar="`./storage/avatars/${avatarUrl.data.original.name}`"
        :title="customers.first_name + ' ' + customers.last_name" :subtitle="customers.email">
      </v-list-item>
      <v-list-item v-else-if="customers.avatarImage" :prepend-avatar="`./storage/avatars/${customers.avatarImage}`"
        :title="customers.first_name + ' ' + customers.last_name" :subtitle="customers.email">
      </v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list density="compact" nav>
      <v-list-item prepend-icon="fas fa-home fa-2xs" title="Home" value="dashboard" to="/dashboard"></v-list-item>
      <!--  <v-list-item prepend-icon="fas fa-user fa-2xs" title="Profile" value="Profile"
        :to="`/ProfileCustomer/${customers.id}`"></v-list-item> -->
      <v-list-item prepend-icon="fas fa-cart-shopping fa-2xs" title="Carts" value="carts" to="/carts"></v-list-item>
      <v-list-item prepend-icon="fas fa-bag-shopping" title="Pedidos" value="myfiles" to="/orders"></v-list-item>
      <v-list-item prepend-icon="fas fa-tag fa-2xs" title="Cupons" value="starred" to="/couponCustomer"></v-list-item>
      <v-list-item prepend-icon="fas fa-message fa-2xs" title="Messages" value="starred"></v-list-item>
    </v-list>
  </v-navigation-drawer>

  <v-main class="d-flex align-center justify-center" style="min-height: 300px;">

    <v-container>

      <router-view></router-view>
    </v-container>
  </v-main>

  <div>
    <AddressForm v-model="addressDialog" v-if="addressDialog" :customer="this.customers"
      @close-dialog="closeAddressDialog" />
  </div>

  <v-footer app name="footer">
    <v-btn class="mx-auto" variant="text" @click="print('footer')">
      Get data
    </v-btn>
  </v-footer>
</template>

<script>
import { useLayout } from 'vuetify'
import { EventBus } from '@/Event/EventBus';
import AppBar from '../Layout/AppBar.vue';
import axios from 'axios';
import AddressForm from '../Dialogs/Address.vue';


const Child = {
  setup(props, ctx) {
    const { getLayoutItem } = useLayout()

    function print(key) {
      alert(JSON.stringify(getLayoutItem(key), null, 2))
    }

    return () => ctx.slots.default({ print })
  },
}

export default {
  components: { Child, AppBar, AddressForm },
  data: () => ({
    customers: [],
    address: [],
    addressDialog: false,
    avatarUrl: false,

  }),
  watch: {
    closeAddessDialog(val) {
      val || this.closeAddressDialog();
    }
  },
  methods: {
    getUser() {
      axios.get('/customer')
        .then((response) => {
          //console.log(response);
          return this.customers = response.data;
        })
        .catch((response) => {
          alert('Error: ' + response);
        });
    },
    getAddress() {
      axios.get('/address')
        .then((response) => {
          if (Object.keys(response.data).length == 0) {

            this.addressDialog = true;
            return this.address = response.data;

          }
        })
        .catch((response) => {
          return alert('Erro :' + response);
        })
    },
    closeAddressDialog() {
      return this.addressDialog = false;
    },
    
  },
  mounted() {
    this.getUser();
    this.getAddress();
    
  },
  created() {
    EventBus.on('update-avatar-image', (response) => {
      this.avatarUrl = response;
    });
  },
  beforeDestroy() {
    EventBus.off('avatar-update-image');
  }
}


</script>