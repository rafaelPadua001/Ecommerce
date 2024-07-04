<template>
    <v-row no-gutters>
        <v-col class="d-flex justify-center flex-column" cols="auto">
            <v-card class="mx-auto elevation-0 text-black">
                <v-app-bar :color="this.appBarColor ?? 'trasparent'">

                    <!-- <template v-slot:image>
                        <v-img gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"></v-img>
                    </template> -->

                    <template v-slot:prepend>

                        <v-app-bar-title>
                            <v-img v-if="store.app_logo" :src="`./storage/app_icon/${JSON.parse(store.app_logo)}`"
                                :width="70" :height="70">
                            </v-img>

                            <v-btn v-else class="text" variant="text" href="/" size="small">{{ store.app_name ??
                    'ProjectName' }}</v-btn>
                        </v-app-bar-title>

                        <v-divider vertical :thickness="1"></v-divider>

                        <div>
                            <v-btn v-if="!user.original >= 1" :to="'/dashboard'" variant="plain" size="small">
                                <v-icon icon="fas fa-home"></v-icon>
                            </v-btn>
                        </div>
                        <v-btn variant="plain" size="small">
                            Blog
                        </v-btn>

                        <div>
                            <CategoriesComponent :categories="this.categories" :color="this.appBarColor">
                            </CategoriesComponent>



                        </div>

                    </template>
                    <v-spacer></v-spacer>

                    <!-- Cart Button-->
                    <div class="d-flex justify-space-around" v-if="Object.keys(carts).length >= 1">
                        <v-row fluid>
                            <v-col class="d-flex" cols="auto">
                                <v-menu offset-y absolute :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                        <v-btn icon v-bind="props">
                                                <v-badge 
                                                    :content="carts.length"
                                                    color="red"
                                                >
                                            <v-icon icon="fa-solid fa-cart-shopping" size="small"></v-icon>
                                        </v-badge>
                                       
                                        </v-btn>
                                    
                                    </template>
                                    <v-row>
                                        <v-col>
                                            <CartList :carts="carts"></CartList>
                                        </v-col>
                                    </v-row>
                                </v-menu>
                            </v-col>
                        </v-row>

                    </div>


                    <v-divider vertical :thickness="1"></v-divider>

                    <!-- Cria o botao de menu do usuario -->
                    <v-menu :color="this.appBarColor ?? 'trasparent'">
                        <template v-slot:activator="{ props }">
                            <v-btn class="mr-2" variant="plain" size="small" icon v-bind="props">
                                <v-icon icon="fa-solid fa-user fa-2xs"></v-icon>
                            </v-btn>

                        </template>
                        <v-list>
                            <!-- login button -->

                            <v-list-item v-if="!user.original == 0">
                                <v-list-item-title link>

                                    <v-btn to="/login" class="me-2" size="x-small" variant="flat">
                                        <v-icon icon="fa-solid fa-right-to-bracket"></v-icon>
                                        Login
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>



                            <v-list-item v-else @click="logout()">

                                <v-list-item-title link>
                                    <v-btn class="me-2" size="x-small" variant="flat">
                                        <v-icon icon="fas fa-right-from-bracket"></v-icon>
                                        Logout
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>


                            <v-list-item @click="openAddressDialog()">

                                <v-list-item-title link>
                                    <v-btn class="me-2" size="x-small" variant="flat">
                                        <v-icon icon="far fa-user"></v-icon>
                                        Profile
                                    </v-btn>
                                </v-list-item-title>
                            </v-list-item>

                        </v-list>
                    </v-menu>
                </v-app-bar>

            </v-card>

            <div>
                <AddressForm v-model="addressDialog" v-if="addressDialog" :customer="this.customers"
                    @close-dialog="closeAddressDialog" />
            </div>
        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';
import CategoriesComponent from '../Categories/Categories.vue';
import AddressForm from '../Dialogs/Address.vue';
import DiscountWindow from '../Coupons/partials/Window.vue';
import CartList from '../Cart/partials/CartList.vue';
import { EventBus } from '@/Event/EventBus';

export default {
    components: { CategoriesComponent, AddressForm, DiscountWindow, CartList },
    data: () => ({
        user: [],
        carts: [],
        categories: [],
        store: [],
        appBar: [],
        appBarColor: false,
        addressDialog: false,
    }),
    watch: {
        closeAddressDialog(val) {
            val || this.closeAddressDialog();
        }
    },
    methods: {
        getStore() {
            axios.get('/store')
                .then((response) => {
                    this.store.app_logo = JSON.parse(response.data.app_logo);
                    this.store = response.data
                })
                .catch((response) => {
                    //return alert('Error: ' + response);
                })
        },
        getAppBar() {
            axios.get(`/api/appBar`)
                .then((response) => {
                    this.appBarColor = JSON.parse(response.data.colors);
                    if (this.appBarColor) {
                        return EventBus.emit('app-bar-color', this.appBarColor);
                    }
                    return this.appBar = response.data;
                })
                .catch((response) => {
                    return false;
                })
        },
        getCategories() {
            axios.get('/categories')
                .then((response) => {
                    return this.categories = response.data;
                }).catch((response) => {
                    return false;
                });
        },
        getUser() {
            axios.get('/customer')
                .then((response) => {
                    console.log(response.data);
                    this.user = response.data;
                   
                    //console.log(Object.keys(this.user.original).length);
                    return this.user;
                })
                .catch((response) => {

                    return alert('Error: ' + response);
                });
        },
        getCarts() {
            axios.get('/carts')
                .then((response) => {
                    return this.carts = response.data;
                })
                .catch((response) => {
                    return false;;
                });
        },
        getLikes() {
            axios.get('/likes')
                .then((response) => {
                    return this.likes = response.data;
                })
                .catch((response) => {
                    return false;
                });
        },
        openAddressDialog() {
            this.addressDialog = true;
        },
        closeAddressDialog() {
            this.addressDialog = false;
        },
        logout() {
            axios.post('/logoutCustomer')
                .then((response) => {
                    if (response) {
                        this.$router.push('/login');
                    }
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
        },
    },
    mounted() {
        this.getStore();
        this.getAppBar();
        this.getUser();
        this.getCategories();
        this.getCarts();
    },
   
}

</script>

<style>
.custom-menu {
    position: absolute;
    top: calc(var(--v-toolbar-height) + 10px);
    /* altura da app-bar + espaço extra */
    z-index: 9999;
    /* ou um valor maior do que o z-index da app-bar */
}
</style>