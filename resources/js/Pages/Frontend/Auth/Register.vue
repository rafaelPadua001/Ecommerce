<template>
    <div>
        <v-card
            class="mx-auto"
            width="800"
            ref="form"
        >
        <v-card-title align='center'>Register user</v-card-title>
        <v-spacer></v-spacer>
        <v-divider></v-divider>
        <v-row justify='center'>
                <v-col
                cols="12"
                sm="10"
                md="8"
                lg="6"
                >
                <v-form>
                    @csrf
                    <v-card-text>
                    <v-row>
                        <v-col>
                            <v-responsive>
                                <div v-if="!error">
                                    <v-alert 
                                       title="success !"
                                        :text="this.response"
                                        v-model="alert"
                                        type="success"
                                    >
                                    </v-alert>
                                </div>

                                <div v-else>
                                    <v-alert
                                        title="Error"
                                        type="error"
                                        :text="error"
                                        v-model="alert"
                                    >
                                    </v-alert> 
                                </div>
                                
                            </v-responsive>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-responsive>
                                <v-text-field
                                    ref="first_name"
                                    v-model="first_name"
                                    :rules="[() => !!first_name || 'This field is required']"
                                    :error-messages="errorMessages"
                                    label="First Name"
                                    placeholder="John"
                                    required
                                ></v-text-field>
                            
                            </v-responsive>
                        </v-col>
                        <v-col>
                            <v-responsive>
                                <v-text-field
                                    ref="last_name"
                                    v-model="last_name"
                                    :rules="[() => !!last_name || 'This field is required']"
                                    :error-messages="errorMessages"
                                    label="Last Name"
                                    placeholder="Doe"
                                    required
                                ></v-text-field>
                            
                            </v-responsive>
                        </v-col>

                         
                    </v-row>

                    <v-row>
                         <v-col>
                            <v-responsive>
                                    
                                    <v-text-field
                                        v-model="email"
                                        name="email"
                                        density="compact"
                                        prepend-inner-icon="mdi-email-outline"
                                        :counter="15"
                                        label="Email:"
                                        placeholder="johndoe@email.com"
                                        type="email"
                                        required
                                        hide-details="auto"
                                    >

                                    </v-text-field>
                        
                            </v-responsive>
                        </v-col>
                        <v-col>
                            <v-responsive>
                                <v-text-field
                                    v-model="password"
                                    append-icon="show1 ? 'fas fa-eye' : 'fas fa-eye-off'"
                                    name="password"
                                    density="compact"
                                    placeholder="Enter your password"
                                    :rules="[rules.required, rules.min]"
                                    :type="show1 ? 'text' : 'password'"
                                    :counter="8"
                                    label="Password"
                                    hint="at last 8 characters"
                                    counter 
                                    @click:prepend="show1 = !show1"
                                    
                                >
                                    
                                </v-text-field>
                                
                            </v-responsive>
                                
                        </v-col>
                    </v-row>
                    
                   <!-- <v-text-field
                        ref="address"
                        v-model="address"
                        :rules="[
                        () => !!address || 'This field is required',
                        () => !!address && address.length <= 25 || 'Address must be less than 25 characters',
                        addressCheck
                        ]"
                        label="Address Line"
                        placeholder="Snowy Rock Pl"
                        counter="25"
                        required
                    ></v-text-field>
                    <v-text-field
                        ref="city"
                        v-model="city"
                        :rules="[() => !!city || 'This field is required', addressCheck]"
                        label="City"
                        placeholder="El Paso"
                        required
                    ></v-text-field>
                    <v-text-field
                        ref="state"
                        v-model="state"
                        :rules="[() => !!state || 'This field is required']"
                        label="State/Province/Region"
                        required
                        placeholder="TX"
                    ></v-text-field>
                    <v-text-field
                        ref="zip"
                        v-model="zip"
                        :rules="[() => !!zip || 'This field is required']"
                        label="ZIP / Postal Code"
                        required
                        placeholder="79938"
                    ></v-text-field>
                    <v-autocomplete
                        ref="country"
                        v-model="country"
                        :rules="[() => !!country || 'This field is required']"
                        :items="countries"
                        label="Country"
                        placeholder="Select..."
                        required
                    ></v-autocomplete> -->
                    </v-card-text>
                    <v-divider class="mt-12"></v-divider>
                    <v-card-actions>
                    <v-btn variant="text" href="/">
                        Cancel
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-slide-x-reverse-transition>
                        <v-tooltip
                        v-if="formHasErrors"
                        location="left"
                        >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            icon
                            class="my-0"
                            v-bind="attrs"
                            @click="resetForm"
                            v-on="on"
                            >
                            <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                        </template>
                        <span>Refresh form</span>
                        </v-tooltip>
                    </v-slide-x-reverse-transition>
                    <v-btn
                        color="primary"
                        variant="text"
                        @click="submit"
                    >
                        Submit
                    </v-btn>
                    </v-card-actions>
                </v-form>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
  export default {
    data: () => ({
      countries: ['Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Anguilla', 'Antigua &amp; Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia &amp; Herzegovina', 'Botswana', 'Brazil', 'British Virgin Islands', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Cape Verde', 'Cayman Islands', 'Chad', 'Chile', 'China', 'Colombia', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D Ivoire', 'Croatia', 'Cruise Ship', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Polynesia', 'French West Indies', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kuwait', 'Kyrgyz Republic', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Mauritania', 'Mauritius', 'Mexico', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Namibia', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Pierre &amp; Miquelon', 'Samoa', 'San Marino', 'Satellite', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'South Africa', 'South Korea', 'Spain', 'Sri Lanka', 'St Kitts &amp; Nevis', 'St Lucia', 'St Vincent', 'St. Lucia', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', `Timor L'Este`, 'Togo', 'Tonga', 'Trinidad &amp; Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks &amp; Caicos', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Venezuela', 'Vietnam', 'Virgin Islands (US)', 'Yemen', 'Zambia', 'Zimbabwe'],
      errorMessages: '',
      first_name: null,
      last_name: null,
      email: null,
      password: null,
      alert: null,
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
                     
            }
    }),

    computed: {
      form () {
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
      name () {
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
      resetForm () {
        this.errorMessages = []
        this.formHasErrors = false

        Object.keys(this.form).forEach(f => {
          this.$refs[f].reset()
        })
      },
      submit () {
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
                this.alert = true;
                return true;
            })
            .catch((response) => {
              
                if(response.response.status){
                    this.alert = true;
                    this.error = 'Error:' + 'revise todos os campos, não serão aceitos campos nulos ou talvez já possuamos seu email em nosso cadastro';
                    setTimeout(() => {
                    // Este código será executado após 30 segundos
                    this.alert = false;
                    console.log("Passaram-se 10 segundos.");
                    }, 10000);
                }
                
                return false;
            })
      },
    },
  }
</script>