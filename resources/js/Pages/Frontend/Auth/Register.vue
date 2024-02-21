<template>
    <div>
        <v-container>
            <v-row no-gutters>
                <v-col class="d-flex justify-center mb-6 flex-column" cols="12" md="12" sm="2">
                    <v-sheet>
                        <v-card class="bg-transparent-accent-4 mx-auto elevation-0" :width="900" >
                            <v-card-text>
                                <v-row>
                                    <v-col cols="auto" md="6" sm="2">

                                        <v-img width="560" height="650" cover
                                            src="https://picsum.photos/1920/1080?random">
                                        </v-img>

                                    </v-col>

                                    <v-col cols="auto" md="6" sm="2">
                                        <v-row v-if="!app_icon">
                                            <v-col>
                                                <v-img class="mx-auto my-6" max-width="150"
                                                    src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg">
                                                </v-img>
                                                <v-divider></v-divider>
                                            </v-col>
                                        </v-row>
                                        <v-row no-gutters v-else>
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
                                        <v-row>
                                            <v-col cols="auto" md="12">
                                                <v-form>
                                                    <v-card-text>
                                                        <v-row no-gutters>
                                                            <v-col class="d-flex justify-center flex-column" cols="auto">
                                                                <div v-if="!error">
                                                                    <v-alert title="success !" :text="message"
                                                                        v-model="alert" type="success">
                                                                    </v-alert>
                                                                </div>

                                                                <div v-else>
                                                                    <v-alert type="error" :text="error" v-model="alert">
                                                                    </v-alert>
                                                                </div>
                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col class="d-flex justify-center flex-column" cols="12"
                                                                md="12" sm="4">

                                                                <v-text-field ref="first_name" v-model="first_name"
                                                                    :rules="[() => !!first_name || 'This field is required']"
                                                                    :error-messages="errorMessages" label="First Name"
                                                                    placeholder="John" required></v-text-field>


                                                            </v-col>
                                                        </v-row>
                                                        <v-row>
                                                            <v-col class="d-flex justify-center flex-column">

                                                                <v-text-field ref="last_name" v-model="last_name"
                                                                    :rules="[() => !!last_name || 'This field is required']"
                                                                    :error-messages="errorMessages" label="Last Name"
                                                                    placeholder="Doe" required></v-text-field>

                                                            </v-col>
                                                        </v-row>

                                                        <v-row>
                                                            <v-col class="d-flex justify-center flex-column">


                                                                <v-text-field v-model="email" name="email" density="compact"
                                                                    prepend-inner-icon="mdi-email-outline" :counter="15"
                                                                    label="Email:" placeholder="johndoe@email.com"
                                                                    type="email" required hide-details="auto">

                                                                </v-text-field>

                                                            </v-col>

                                                        </v-row>
                                                        <v-row>
                                                            <v-col class="d-flex justify-center flex-column">
                                                                <v-text-field v-model="password"
                                                                    append-icon="show1 ? 'fas fa-eye' : 'fas fa-eye-off'"
                                                                    name="password" density="compact"
                                                                    placeholder="Enter your password"
                                                                    :rules="[rules.required, rules.min]"
                                                                    :type="show1 ? 'text' : 'password'" :counter="8"
                                                                    label="Password" hint="at last 8 characters" counter
                                                                    @click:prepend="show1 = !show1">

                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>


                                                    </v-card-text>

                                                    <v-divider></v-divider>
                                                    <v-card-actions>
                                                        <v-btn variant="text" href="/">
                                                            Cancel
                                                        </v-btn>
                                                        <v-spacer></v-spacer>
                                                        <v-slide-x-reverse-transition>
                                                            <v-tooltip v-if="formHasErrors" location="left">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-btn icon class="my-0" v-bind="attrs"
                                                                        @click="resetForm" v-on="on">
                                                                        <v-icon>mdi-refresh</v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Refresh form</span>
                                                            </v-tooltip>
                                                        </v-slide-x-reverse-transition>
                                                        <v-btn color="primary" variant="text" @click="submit">
                                                            Submit
                                                        </v-btn>
                                                    </v-card-actions>
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
export default {
    data: () => ({
        countries: ['Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Anguilla', 'Antigua &amp; Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia &amp; Herzegovina', 'Botswana', 'Brazil', 'British Virgin Islands', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Cape Verde', 'Cayman Islands', 'Chad', 'Chile', 'China', 'Colombia', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D Ivoire', 'Croatia', 'Cruise Ship', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Polynesia', 'French West Indies', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kuwait', 'Kyrgyz Republic', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Mauritania', 'Mauritius', 'Mexico', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Namibia', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Pierre &amp; Miquelon', 'Samoa', 'San Marino', 'Satellite', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'South Africa', 'South Korea', 'Spain', 'Sri Lanka', 'St Kitts &amp; Nevis', 'St Lucia', 'St Vincent', 'St. Lucia', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', `Timor L'Este`, 'Togo', 'Tonga', 'Trinidad &amp; Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks &amp; Caicos', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Venezuela', 'Vietnam', 'Virgin Islands (US)', 'Yemen', 'Zambia', 'Zimbabwe'],
        app_icon: [],
        errorMessages: '',
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        confirmPassoword: null,
        alert: null,
        message: false,
        error: null,
        //address: null,
        //city: null,
        //state: null,
        // zip: null,
        // country: null,
        formHasErrors: false,
        rules: {
            required: value => !!value || 'Required.',
            min: v => v.length >= 8 || 'Min 8 characters',
        },
    }),
    computed: {
        form() {
            return {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                password: this.password,
                /* address: this.address,
                 city: this.city,
                 state: this.state,
                 zip: this.zip,
                 country: this.country, */
            }
        },
    },
    watch: {
        name() {
            this.errorMessages = ''
        },
    },

    methods: {
        /* addressCheck () {
           this.errorMessages = this.address && !this.name
             ? `Hey! I'm required`
             : ''
   
           return true
         }, */
        getStoreLogo(){
            axios.get('/api/store/getAppIcon')
            .then((response) => {
               return this.app_icon = JSON.parse(response.data.app_logo);
            })
            .catch((response) => {
               return alert('Error: ' + response);
            })
        },
        resetForm() {
            this.errorMessages = []
            this.formHasErrors = false

            Object.keys(this.form).forEach(f => {
                this.$refs[f].reset()
            })
        },
        submit() {
            this.formHasErrors = false
            Object.keys(this.form).forEach(f => {
                if (!this.form[f]) this.formHasErrors = true;

                //  this.$refs[f].validate(true);
            });

            const data = {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                password: this.password,
            };

            axios.post('/registerCustomer', data)
                .then((response) => {
                    this.message = 'Usuario cadastrado com sucesso, você será redirecionado em breve.';
                    this.alert = true;
                    setTimeout(() => {
                        window.location.href = "/login";
                    }, 10000);

                    return true;
                })
                .catch((response) => {
                    if (response.response.status === 500) {
                        this.alert = true;
                        this.error = 'this email is already registered here...';
                        setTimeout(() => {
                            this.alert = false;

                        }, 3500);
                    }
                    return false;
                })
        },
    },
    mounted() {
        this.getStoreLogo();
    }
}
</script>