<template>
 <!-- <v-app-bar name="app-bar" color="deep-purple-accent-4" v-if="user">
      Welcome back - Admin {{ user.name }}
  </v-app-bar>
-->
  <appbar v-if="user"/>

    <v-navigation-drawer expand-on-hover rail class="bg-deep-purple-accent-4"
      gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)">
      <v-list>
        <v-list-item prepend-avatar="https://randomuser.me/api/portraits/lego/1.jpg" :title="user.name"
          :subtitle="user.email"></v-list-item>
      </v-list>

      <v-divider></v-divider>

      <v-list density="compact" nav v-model:opened="open">
        <v-list-group value="Products">
          <template v-slot:activator="{ props }">
            <v-list-item v-bind="props" prepend-icon="fas fa-bag-shopping fa-2xs fa-bounce" title="To sell"></v-list-item>
          </template>
          <v-list-item prepend-icon="fas fa-folder fa-2xs" title="Categories" value="categories" to="/categories"></v-list-item>
          <v-list-item prepend-icon="fas fa-folder fa-2xs" title="Subcategories" value="subcategories" to="/subcategories"></v-list-item>
          <v-list-item prepend-icon="fa-brands fa-product-hunt fa-2xs" title="Products" value="myfiles" to="/products"></v-list-item>
          <v-list-item prepend-icon="fas fa-code fa-2xs" title="SEO" value="SEO" to="/product_seo"></v-list-item>
          <v-list-item prepend-icon="fas fa-boxes-stacked" title="Stock" value="myfiles" to="/stock"></v-list-item>
         
        </v-list-group>
        <v-divider></v-divider>

        <v-list-group value="Medias">
          <template v-slot:activator="{ props }">
            <v-list-item v-bind="props" prepend-icon="fas fa-photo-film fa-2xs fa-bounce" title="Medias"></v-list-item>
          </template>
          <v-list-item prepend-icon="fas fa-images fa-2xs" title="Images" value="images" to="/images"></v-list-item>
          <v-list-item prepend-icon="fas fa-film" title="Videos" value="videos" to="/videos"></v-list-item>
        </v-list-group>
        
        <v-divider></v-divider>
        <v-list-item prepend-icon="fa-brands fa-first-order fa-2xs fa-bounce" title="Orders" value="orders" to="/allOrders"></v-list-item>
        <v-divider></v-divider>

        <v-list-item prepend-icon="fas fa-regular fa-tag fa-2xs fa-bounce" title="Coupons" value="Coupons" to="/coupons"></v-list-item>
        <v-divider></v-divider>

        <v-list-item prepend-icon="fas fa-store fa-2xs fa-bounce" title="Store" value="Store"></v-list-item>
        <v-divider></v-divider>

        <v-list-item prepend-icon="fas fa-address-card fa-2xs fa-bounce" title="Profile" value="Profile"></v-list-item>
        <v-divider></v-divider>

        <v-list-item prepend-icon="fas fa-users fa-xs fa-bounce" title="Users" value="Settings"></v-list-item>
        <v-divider></v-divider>
        <v-list-item prepend-icon="fas fa-gear fa-xs fa-spin" title="Settings" value="Settings"></v-list-item>


      </v-list>
    </v-navigation-drawer>
    <v-main>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-main>
</template>
<script>
import { useLayout } from 'vuetify'
import Appbar from '../Layout/Appbar.vue'

const Child = {
  setup(props, ctx) {
    const { getLayoutItem } = useLayout()

    function print(key) {
      alert(JSON.stringify(getLayoutItem(key), null, 2))
    }
    return {
      print,
    };
  },
}

export default {
  components: { Child, Appbar },
  data: () => ({
    open: ['Users'],
    user: [],
  }),
  methods: {
    getAuth() {
      axios.get('/users')
        .then((response) => {
          this.user = response.data;
          
        })
        .catch((response) => {
          console.log(response.data);
          return false;
        });
    },
  },
  created() {
    this.getAuth();

  }

}
</script>