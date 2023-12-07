<template>
    <v-row>
        <v-col cols="12" md="8" sm="6">
            <v-card max-width="448" class="mx-auto" color="transparent-lighten-3">
                <v-app-bar color="transparent-darken-4" image="https://picsum.photos/1920/1080?random">
                    <template v-slot:image>
                        <v-img gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"></v-img>
                    </template>

                    <template v-slot:prepend>
                        <v-app-bar-title><v-btn class="text" color="white" href="/">EcomerceClone</v-btn></v-app-bar-title>
                        <div v-if="user.length > 0">
                            <v-btn :to="'/dashboard'" variant="plain"><v-icon icon="fa-solid fa-house fa-2xs" color="white"
                                    v-if="user != null"></v-icon></v-btn>
                        </div>


                        <!-- adicionar Logo aqui -->

                    </template>
                    <v-spacer></v-spacer>

                    <v-btn icon variant="plain">
                        <v-icon icon="fas fa-magnifying-glass"></v-icon>
                    </v-btn>
                    <!-- Cart Button-->
                    <div class="d-flex justify-space-around" v-if="carts.length >= 1">
                        <v-menu>
                            <template v-slot:activator="{ props }">
                                <v-btn icon v-bind="props">
                                    <v-icon icon="fa-solid fa-cart-shopping"></v-icon>
                                </v-btn>
                            </template>
                            <v-list :items="carts" item-props lines="three" style="margin-top: 51px; margin-right: -55px">
                                <v-list-item v-for="item in carts" :key="item.id" :value="item.id">
                                    <div v-if="!item">
                                        <p>No item to show...</p>
                                    </div>
                                    <div v-else>
                                        <v-card>
                                            <v-card-title>
                                                <v-toolbar class="bg-transparent">
                                                    {{ item.name }}
                                                    <template v-slot:append>
                                                        <v-btn-group>

                                                            <v-btn v-bind="props" icon size="x-small" variant="plain">
                                                                <v-icon icon="fas fa-close fa-2xs"
                                                                    @click="removeItem(item)"></v-icon>
                                                            </v-btn>
                                                        </v-btn-group>

                                                    </template>
                                                </v-toolbar>


                                            </v-card-title>
                                            <v-card-text>
                                                <v-row>
                                                    <v-col cols="6" sm="4">
                                                        <div v-for="(image, index) in JSON.parse(item.images)" :key="index">

                                                            <v-avatar size="62" rounded="0">
                                                                <v-img v-if="index === 0" :vid-id="image"
                                                                    class="align-end text-white" aspect-ratio="1/1"
                                                                    :src="`./storage/products/${image}`"
                                                                    :lazy-src="`./storage/products/${image}`">
                                                                </v-img>

                                                            </v-avatar>
                                                        </div>


                                                    </v-col>
                                                    <v-col cols="6" sm="4">
                                                        Price: {{ item.price }}
                                                    </v-col>
                                                    <v-col cols="6" sm="4">
                                                        Quantity: {{ item.quantity }}
                                                    </v-col>

                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-card v-bind="props" :color="item.color" :width="60">
                                                            <template v-slot:append>

                                                            </template>
                                                        </v-card>

                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </v-card>
                                        <v-divider></v-divider>
                                    </div>
                                </v-list-item>

                                <v-btn color="cyan-darken-4" variant="tonal" block>Checkout</v-btn>

                            </v-list>
                        </v-menu>
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
                            <v-list-item v-if="!user.length > 0">
                                <v-list-item-title link>
                                    <span>
                                        <v-btn icon variant="plain" to="/login">
                                            <v-icon icon="fa-solid fa-right-to-bracket fa-xs"></v-icon>
                                        </v-btn>

                                    </span>
                                    <span>Login</span>
                                </v-list-item-title>
                            </v-list-item>

                            <!-- logout button -->

                            <v-list-item @click="logout()" v-else>
                                <v-list-item-title link>
                                    <span>
                                        <v-icon icon="fas fa-right-from-bracket"></v-icon>
                                    </span>

                                    <span>Logout</span>
                                </v-list-item-title>
                            </v-list-item>

                            <!-- logout admin button -->

                        </v-list>
                    </v-menu>
                </v-app-bar>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';



export default {
    data: () => ({
        user: [],
        carts: [],
    }),
    methods: {
        getUser() {
            axios.get('/customer')
                .then((response) => {
                    return this.user = response.data;
                })
                .catch((response) => {
                    console.log('not logged.');
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
                    alert(response);
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
        },
    },

    mounted() {
        this.getUser();
        this.getCarts();
    }
}

</script>

