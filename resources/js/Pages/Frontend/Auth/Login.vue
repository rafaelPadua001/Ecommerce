<template>
    <div v-if="customer >= 1">
        usuario logado
    </div>
    <div v-else>
        <v-container>
            <v-row fluid>
                <v-col class="d-flex justify-center flex-column" cols="12" md="8" sm="6">
                    <v-sheet>
                        <v-card class="mx-auto elevation-0" :width="800">
                            <!--   <v-card-title align="center">Login Here</v-card-title> -->

                            <v-card-text>
                                <v-row fluid>
                                    <v-col cols="auto" md="6">
                                        <div id="formImg">
                                            <v-img width="410" height="500" aspect-ratio="4/3" cover
                                                src="https://picsum.photos/1920/1080?random">
                                            </v-img>

                                        </div>
                                    </v-col>
                                    <v-col cols="auto" md="5">
                                        <v-row>
                                            <v-col>
                                                <v-img class="mx-auto my-6" max-width="150"
                                                    src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg">
                                                </v-img>
                                                <v-divider></v-divider>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-form v-model="valid">

                                                    <v-row fluid>
                                                        <v-col cols="12" md="12" sm="12">


                                                            <v-text-field v-model="email" name="email" density="compact"
                                                                prepend-inner-icon="mdi-email-outline" :counter="15"
                                                                label="Email:" placeholder="johndoe@email.com" type="email"
                                                                required hide-details="auto">

                                                            </v-text-field>

                                                        </v-col>
                                                    </v-row>

                                                    <v-row fluid>
                                                        <v-col cols="12">
                                                            <v-responsive>
                                                                <v-text-field v-model="password"
                                                                    append-icon="show1 ? 'fas fa-eye' : 'fas fa-eye-off'"
                                                                    name="password" density="compact"
                                                                    placeholder="Enter your password"
                                                                    :rules="[rules.required, rules.min]"
                                                                    :type="show1 ? 'text' : 'password'" :counter="8"
                                                                    label="Password" hint="at last 8 characters" counter
                                                                    @click:prepend="show1 = !show1">

                                                                </v-text-field>
                                                                <div align="right">
                                                                    <a class="text-caption text-decoration-none text-blue"
                                                                        href="/forgot_password" rel="noopener noreferrer"
                                                                        target="_blank" align="text-right">
                                                                        Forgot login password?
                                                                    </a>

                                                                </div>
                                                            </v-responsive>

                                                        </v-col>
                                                    </v-row>
                                                    <v-divider></v-divider>

                                                    <v-row>
                                                        <v-col>
                                                            <v-btn-group>
                                                                <v-btn variant="plan" href="/register">joinUs</v-btn>
                                                                <v-btn variant="plan" @click="login()">Login</v-btn>

                                                            </v-btn-group>
                                                        </v-col>
                                                    </v-row>

                                                </v-form>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data: () => ({
        customer: [],
        show1: false,
        show2: true,
        password: '',
        email: '',
        visible: false,
        rules: {
            required: value => !!value || 'Required.',
            min: v => v.length >= 8 || 'Min 8 characters',

        }
    }),
    methods: {
        login() {
            const token = document.head.querySelector('meta[name="csrf-token"]');

            const data = { email: this.email, password: this.password };

            axios.post('/login', data,
                {
                    headers: {
                        'X-CSRF-TOKEN': token.content
                    }
                })
                .then((response) => {
                    this.$router.push('/dashboard');
                })
                .catch((response) => {
                    alert('Error:' + response);
                    return false;
                });
        },

    },
    mounted() {
        // this.getLoged();
    }

}
</script>

<style scoped>
#divImg {
    background: #b8a88e;
    margin: 0% 0%;
}

#divForm {
    width: 45%;
    height: auto;
    background: #fff;
    margin: 10% 2%;
    position: absolute;
    top: 0px;
    right: 0px;
}
</style>