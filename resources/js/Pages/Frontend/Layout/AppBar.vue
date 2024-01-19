<template>
    <v-row no-gutters>
        <v-col class="d-flex justify-center flex-column" cols="auto">
            <v-card max-width="448" class="mx-auto elevation-0" color="transparent-lighten-3">
                <v-app-bar color="transparent-darken-4" image="https://picsum.photos/1920/1080?random">

                    <template v-slot:image>
                        <v-img gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"></v-img>
                    </template>

                    <template v-slot:prepend>
                        <v-app-bar-title>
                            <v-btn class="text" color="white" href="/">{{ store.app_name ?? 'EcomerceClone'}}</v-btn></v-app-bar-title>
                        <div v-if="user === 1 || user">
                            <v-btn :to="'/dashboard'" variant="plain">
                                <v-icon icon="fa-solid fa-house fa-2xs" color="white"></v-icon>
                            </v-btn>
                        </div>

                        <div>
                            <v-btn id="menu-categories" variant="plain">
                                <v-icon icon="fa-solid fa-grip-vertical fa-2xs" color="white">
                                </v-icon>
                            </v-btn>

                            <v-menu activator="#menu-categories">
                                <v-list>
                                    <v-list-item v-if="!categories">
                                        no categories registered
                                    </v-list-item>
                                    <v-list-item v-else v-for="category in categories" :key="category.id">
                                        <v-btn icon variant="text" size="xs" :to="`/subcategories/all/${category.id}`">
                                            <v-icon :icon="category.icon"></v-icon>
                                            <span>{{ category.name }}</span>
                                        </v-btn>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>

                        <!-- adicionar Logo aqui -->

                    </template>
                    <v-spacer></v-spacer>

                    <v-btn icon variant="plain">
                        <v-icon icon="fas fa-magnifying-glass"></v-icon>
                    </v-btn>
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

                                        <v-btn color="cyan-darken-4" variant="tonal" block>Checkout</v-btn>

                                    </v-list>
                                </v-menu>
                            </v-col>
                        </v-row>

                    </div>

                    <!-- Cria o botao de menu do usuario -->
                    <v-menu>
                        <template v-slot:activator="{ props }">

                            <v-btn icon v-bind="props">
                                <v-icon icon="fa-solid fa-user"></v-icon>
                            </v-btn>

                        </template>
                        <v-list>
                            <!-- login button -->
                            <v-list-item v-if="user === 0 || !user">
                                <v-list-item-title link>
                                    <span>
                                        <v-btn icon variant="plain" to="/login">
                                            <v-icon icon="fa-solid fa-right-to-bracket fa-xs"></v-icon>
                                        </v-btn>

                                    </span>
                                    <span>Login</span>
                                </v-list-item-title>
                            </v-list-item>



                            <v-list-item v-else @click="logout()">

                                <v-list-item-title link>
                                    <span>
                                        <v-icon icon="fas fa-right-from-bracket"></v-icon>
                                    </span>

                                    <span>Logout</span>
                                </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click="openAddressDialog()">

                                <v-list-item-title link>
                                    <span>
                                        <v-icon icon="far fa-user"></v-icon>
                                    </span>

                                    <span>Profile</span>
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
export default {
    components: { AddressForm },
    data: () => ({
        user: [],
        carts: [],
        categories: [],
        store: [],
        addressDialog: false,
    }),
    watch: {
        closeAddressDialog(val) {
            val || this.closeAddressDialog();
        }
    },
    methods: {
        getStore(){
            axios.get('/store')
            .then((response) => {
                this.store = response.data
            })
            .catch((response) => {
                return alert('Error: ' + response);
            })
        },
        getCategories() {
            axios.get('/categories')
                .then((response) => {
                    return this.categories = response.data;
                }).catch((response) => {
                    return alert('Error: ' + response);
                });
        },
        getUser() {
            axios.get('/customer')
                .then((response) => {
                    if (!Object.keys(response.data.original).length) {
                        return this.user = 0;

                    }
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
                    return alert('Error :' + response);
                });
        },
        getLikes() {
            axios.get('/likes')
                .then((response) => {
                    return this.likes = response.data;
                })
                .catch((response) => {
                    alert('Error: ' + response);
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
        this.getUser();
        this.getCategories();
        this.getCarts();
    }
}

</script>

