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
                            <v-btn :to="'/dashboard'" variant="plain" size="small">
                                <v-icon icon="fas fa-home"></v-icon>
                            </v-btn>
                        </div>
                        <v-btn variant="plain" size="small">
                            Blog
                        </v-btn>

                        <div>


                            <v-menu :activator="$refs.menuBtn" open-on-hover>
                                <template v-slot:activator="{ props }">
                                    <v-btn ref="menuBtn" variant="plain" size="small" v-bind="props">
                                        <!-- <v-icon icon="fa-solid fa-grip-vertical fa-2xs">
                                </v-icon> -->
                                        categories
                                        <template v-slot:append>
                                            <v-icon icon="fa-solid fa-chevron-down fa-2xs"></v-icon>
                                        </template>


                                    </v-btn>
                                </template>
                                <v-list elevation="0">
                                    <v-list-item v-if="!categories || categories.length === 0">
                                        no categories found
                                    </v-list-item>
                                    <v-list-item v-else v-for="category in categories" :key="category.id">
                                        <v-btn variant="flat" size="small" 
                                            :to="{path: `/subcategories/all/${category.id}`, query: {customer: this.user}}">
                                            <span>
                                                <v-avatar color="surface-variant" size="28px">
                                                    <v-img v-if="category.thumbnail"
                                                        :src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                                                        :lazy-src="`./storage/Categories/Thumbnails/${category.thumbnail}`"
                                                        :alt="`${category.name}`"></v-img>
                                                </v-avatar>
                                            </span>
                                            <!-- <v-icon v-else :icon="category.icon"></v-icon> -->
                                            <span>{{ category.name }}</span>
                                        </v-btn>
                                    </v-list-item>
                                </v-list>
                            </v-menu>


                        </div>



                        <!-- <v-btn class="mr-2" variant="plain" to="/">
                            <v-icon icon="fas fa-globe fa-2xs"></v-icon>
                        </v-btn> -->
                    </template>
                    <v-spacer></v-spacer>



                    <!-- Cart Button-->
                    <div class="d-flex justify-space-around" v-if="Object.keys(carts).length >= 1">
                        <v-row fluid>
                            <v-col cols="auto">
                                <v-menu>
                                    <template v-slot:activator="{ props }">
                                        <v-btn icon v-bind="props">
                                            <v-icon icon="fa-solid fa-cart-shopping"></v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list :items="carts" item-props lines="three"
                                        style="margin-top: 10px; margin-right: -55px">
                                        <v-list-item v-for="item in carts" :key="item.id" :value="item.id">
                                            <div v-if="item && item.is_active == 1">
                                                <v-row>
                                                    <v-col cols="auto" md="12" sm="4">
                                                        <v-card class="mx-auto" elevation="0">
                                                            <template v-slot:append>
                                                                <v-btn-group>

                                                                    <v-btn v-bind="props" icon size="x-small"
                                                                        variant="plain">
                                                                        <v-icon icon="fas fa-close fa-2xs"
                                                                            @click="removeItem(item)"></v-icon>
                                                                    </v-btn>
                                                                </v-btn-group>

                                                            </template>

                                                            <v-card-text>
                                                                <v-row no-gutters>
                                                                    <v-col cols="auto" md="2" sm="4">
                                                                        <div v-for="(image, index) in JSON.parse(item.images)"
                                                                            :key="index">
                                                                            <v-avatar rounded="0" v-if="index === 0">
                                                                                <v-img :vid-id="image"
                                                                                    class="align-end text-white"
                                                                                    aspect-ratio="1"
                                                                                    :src="`./storage/products/${image}`"
                                                                                    :lazy-src="`./storage/products/${image}`">
                                                                                </v-img>

                                                                            </v-avatar>
                                                                        </div>


                                                                    </v-col>
                                                                    <v-col cols="6" md="4" sm="2">
                                                                        <div>
                                                                            <span>{{ item.name }}</span>
                                                                        </div>
                                                                    </v-col>


                                                                </v-row>
                                                                <v-row>
                                                                    <v-col cols="auto" md="2" sm="1">
                                                                        <v-card v-bind="props" :color="item.color"
                                                                            :width="30">
                                                                            <template v-slot:append>

                                                                            </template>
                                                                        </v-card>

                                                                    </v-col>
                                                                    <v-col cols="auto" md="5" sm="2">
                                                                        <span>Price: {{ item.price }}</span>
                                                                    </v-col>
                                                                    <v-col cols="auto" md="5" sm="2">
                                                                        Quantity: {{ item.quantity }}
                                                                    </v-col>
                                                                    <v-col cols="auto" sm="2">

                                                                    </v-col>



                                                                </v-row>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-col>
                                                </v-row>

                                                <v-divider></v-divider>
                                            </div>

                                        </v-list-item>

                                        <v-btn :color="this.appBarColor ?? 'trasparent'" variant="tonal"
                                            block>Checkout</v-btn>

                                    </v-list>
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
import AddressForm from '../Dialogs/Address.vue';
import DiscountWindow from '../Coupons/partials/Window.vue';
export default {
    components: { AddressForm, DiscountWindow },
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
                    return this.user = response.data;
                })
                .catch((response) => {

                    return false;
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
        removeItem(item) {
            axios.delete(`/cartItem/delete/${item.id}`)
                .then((response) => {
                    return this.carts.splice(this.carts.indexOf(item), 1);
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });

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
    }
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