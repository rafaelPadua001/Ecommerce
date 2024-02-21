<template>
    <div v-if="customer >= 1">
        usuario logado
    </div>
    <div v-else>
        <v-container>
            <v-row>
                <v-col class="d-flex justify-center flex-column" cols="12" md="6" sm="2">
                    <v-sheet class="ma-2 pa-2">
                        <v-card class="mx-auto elevation-0" :width="900">
                            <v-card-text>
                                
                                <v-row fluid>
                                    <v-col cols="auto" md="6" sm="2">
                                        <div id="formImg">
                                            <v-img width="560" height="650"  cover
                                                src="https://picsum.photos/1920/1080?random">
                                            </v-img>
                                        </div>
                                    </v-col>
                                    <v-col cols="auto" md="6" sm="2">
                                        <v-row no-gutters>
                                            <v-col>
                                                <v-img class="mx-auto my-6" max-width="80"
                                                    :src="`./storage/app_icon/${app_icon}`"
                                                    :lazy-src="`./storage/app_icon/${app_icon}`"
                                                    :alt="`${app_icon}`"
                                                >
                                                </v-img>
                                                <v-spacer></v-spacer>
                                                <v-divider></v-divider>
                                                
                                            </v-col>
                                        </v-row>
                                        <v-row no-gutters>
                                            <v-col cols="auto" md="12">
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
                                                        <v-col cols="12" md="12" sm="12">
                                                          
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
        app_icon: [],
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
        getStoreLogo(){
            axios.get('/api/store/getAppIcon')
            .then((response) => {
               return this.app_icon = JSON.parse(response.data.app_logo);
            })
            .catch((response) => {
               return alert('Error: ' + response);
            })
        }

    },
    mounted() {
        this.getStoreLogo();
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