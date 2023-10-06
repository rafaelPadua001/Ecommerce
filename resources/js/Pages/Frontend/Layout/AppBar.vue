<template>
    <v-app-bar color="teal-darken-4">
        <template v-slot:image>
            <v-img gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"></v-img>
        </template>

        <template v-slot:prepend>
            <!-- adicionar Logo aqui -->
            <a href="/"><v-icon icon="fa-solid fa-house fa-2xs" color="white"></v-icon></a>
        </template>

        <v-app-bar-title> <v-btn class="plain" color="white" href="/">EcomerceClone</v-btn></v-app-bar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
            <v-icon icon="fa-solid fa-magnifying-glass"></v-icon>
        </v-btn>
        <!-- Cart Button-->
        <div class="d-flex justify-space-around">
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
                    <div>
                        <v-card>
                            <v-card-title>
                                <v-toolbar class="bg-transparent">
                                    {{ item.name }}
                                    <template v-slot:append>
                                        <v-btn-group>

                                            <v-btn v-bind="props" icon size="x-small" variant="plain">
                                                <v-icon icon="fas fa-close fa-2xs"></v-icon>
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
                                                <v-img v-if="index === 0" :vid-id="image" class="align-end text-white"
                                                    aspect-ratio="1/1" :src="`./storage/products/${image}`"
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
                <v-list-item to="/login">
                    <v-list-item-title link>
                        <span>
                            <v-icon icon="fa-solid fa-right-to-bracket fa-xs"></v-icon>
                        </span>
                        <span>Login</span>
                    </v-list-item-title>
                </v-list-item>

                <!-- logout button -->

                <v-list-item v-if="user" @click="logout()">
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
        // this.getUser();
        this.getCarts();
    }
}

</script>