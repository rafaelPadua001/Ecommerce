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

        <v-btn icon>
            <v-icon icon="fa-solid fa-cart-shopping"></v-icon>
        </v-btn>

        <!-- Cria o botao de menu do usuario -->
        <v-menu>
            <template v-slot:activator="{ props }">

                <v-btn icon v-bind="props">
                    <v-icon icon="fa-solid fa-user"></v-icon>
                </v-btn>

            </template>
            <v-list>
                <!-- login button -->
                <v-list-item  to="/login">
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
    }),
    methods: {
        getUser(){
            axios.get('/customer')
            .then((response) => {
                return this.user = response.data;
            })
            .catch((response) => {
                console.log('not logged.');
                return false;
            });
        } ,
        logout() {
            axios.post('/logoutCustomer')
                .then((response) => {
                    if(response){
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
    }
}
</script>