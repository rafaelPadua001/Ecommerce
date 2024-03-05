<template>
    <v-container>
        <v-row no-gutters justify="center">
        <v-col class="d-flex justify-center flex-column" cols="auto">
            <v-sheet class="px-2 py-2">
                <v-card class="mx-auto" :width="800">
                    <v-card-title>
                        Login Admin
                    </v-card-title>
                    <v-card-text>
                        <v-row fluid>
                            <v-col cols="auto">
                                <div id="formImg">
                                    <v-img :width="405" height="500" aspect-ratio="16/9" cover
                                        src="https://picsum.photos/1920/1080?random">
                                    </v-img>

                                </div>
                            </v-col>
                            <v-col>
                                <div id="divForm">

                                    <v-img class="mx-auto my-6" max-width="228"
                                        src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg">
                                    </v-img>
                                    <v-divider></v-divider>
                                    <v-form v-model="valid">
                                        <v-container fluid>
                                            <v-row no-gutters>
                                                <v-col  sm="12">
                                                       <v-text-field v-model="email" name="email" density="compact"
                                                            prepend-inner-icon="mdi-email-outline" :counter="15"
                                                            label="Email:" placeholder="johndoe@email.com" type="email"
                                                            required hide-details="auto">

                                                        </v-text-field>
                                                 </v-col>
                                            </v-row>

                                            <v-row no-gutters>
                                                <v-col sm="12">
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
                                                        <v-btn variant="plan" to="/registerAdmin">joinUs</v-btn>
                                                        <v-btn variant="plan" @click="login()">Login</v-btn>

                                                    </v-btn-group>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-form>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>


                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
    </v-container>
    


</template>

<script>
export default {
    data: () => ({
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
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            } else {
                console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
            }
            const data = {
                email: this.email,
                password: this.password
            };

            axios.post('/loginAdmin', data)
                .then((response) => {
                    this.$router.push('/adminDashboard');
                })
                .catch((response) => {
                    alert('Error:' + response);
                    return false;
                });
        }
    },

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