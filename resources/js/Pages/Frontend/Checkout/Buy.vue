<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-row no-gutters>
            <v-col class="d-flex flex-column justify-center">
                <v-timeline direction="horizontal" side="center" line-inset="12">
                    <v-timeline-item v-mode="confirm" v-if="confirm" dot-color="blue-darken-2" icon="fas fa-home" fill-dot
                        size="x-small">
                        <template v-slot="opposite">
                            <v-card :max-width="800" elevation="0">
                                <v-card-text>
                                    <v-row>
                                        <v-col v-for="(image, index) in this.productImages" :key="index">
                                            <v-img v-if="index === 0" :src="`/storage/products/${image}`"
                                                :lazy-src="`/storage/products/${image}`" :alt="image" aspect-ratio="16/9"
                                                :width="200" cover>
                                                <v-row justify="end">
                                                    <v-col cols="auto">
                                                        <v-chip class="ma-2 bg-white">
                                                            {{ product.quantity }}
                                                        </v-chip>
                                                    </v-col>
                                                </v-row>
                                            </v-img>
                                        </v-col>
                                        <v-col cols="auto">
                                            <div>
                                                <strong>{{ product.name }}</strong>
                                            </div>
                                            <div>
                                                <v-card :color="product.color" v-if="product.color">
                                                    color
                                                </v-card>
                                            </div>
                                            <div>
                                                <p>
                                                    <strong>Price</strong> {{ product.price }}

                                                <div v-for="item in shippment" :key="item.id">
                                                    <strong>Delivery:</strong>
                                                    {{ item.price }}

                                                    <p>
                                                        <strong>Total: </strong>
                                                        {{ parseFloat(item.price) + parseFloat(product.price) }}
                                                    </p>
                                                    <p>
                                                        <strong>Shippment: </strong> {{ item.name }}
                                                    </p>
                                                    <p>
                                                        <v-row fluid>
                                                            <v-col>
                                                                <v-text-field v-model="postal_code" v-maska:[options]
                                                                    label="postal code" :placeholder="zip_code"
                                                                    :value="zip_code" v-if="zip_code">
                                                                </v-text-field>
                                                                <v-text-field v-model="postal_code" v-maska:[options]
                                                                    label="postal code" :placeholder="zip_code" v-else>
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col col="8" sm="4">
                                                                <v-btn size="x-small" variant="text" color="primary"
                                                                    @click="calculateDelivery">calculate</v-btn>
                                                                <v-btn size="x-small" variant="text" color="warning"
                                                                    @click="itemCart.cep = ''">Clear</v-btn>
                                                                <v-btn size="x-small" variant="text" color="blue"
                                                                    @click="searchToAddress">Buscar Cep</v-btn>


                                                            </v-col>
                                                        </v-row>
                                                    </p>
                                                    <p>
                                                        <v-col v-if="address.length >= 1">
                                                            <strong>Logradouro:</strong> {{ address[0].logradouro }},
                                                            <strong
                                                                v-if="address[0].complemento.length >= 1">endereco:</strong>
                                                            {{ address[0].complemento }},
                                                            <div v-if="address[0].complemento == ''">
                                                                <strong v-if="address[0].complemento == ''">
                                                                    <v-btn class="mb-2" size="xs" variant="text"
                                                                        color="blue" @click="openAddressDialog">
                                                                        Click to add address
                                                                    </v-btn></strong>
                                                            </div>
                                                            <strong>Bairro:</strong> {{ address[0].bairro }},

                                                            <strong>CEP:</strong> {{ address[0].cep }} ,
                                                            <strong>Localidade:</strong> {{ address[0].localidade }},
                                                            <strong>UF:</strong> {{ address[0].uf }}
                                                            <strong>DDD:</strong> {{ address[0].ddd }}
                                                        </v-col>
                                                    </p>
                                                </div>
                                                </p>

                                            </div>
                                            <div>
                                            </div>
                                        </v-col>

                                    </v-row>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn-group>
                                        <v-btn @click="confirmNext()">Confirmar</v-btn>
                                    </v-btn-group>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>

                    <v-timeline-item dot-color="blue-darken-2" icon="fas fa-check" fill-dot size="xs">
                        <template v-slot:opposite>
                            <v-card
                                v-model="shippmentConfirm"
                                v-if="shippmentConfirm" 
                                :width="500"
                            >
                            <v-card-text>
                                <v-row>
                                    <v-col class="d-flex justify-center flex-column">
                                        <div>
                                            Aqui vou carregar a confirmação de entrega
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            
                            <v-card-actions>
                                <v-btn @click="returnConfirm()">Voltar</v-btn>
                            </v-card-actions>
                            </v-card>
                        </template>
                    </v-timeline-item>
                </v-timeline>
            </v-col>
        </v-row>

        <div class="text-center">
            <v-snackbar v-model="snackbar" :timeout="3500" color="cyan-darken-3" vertical>

                <div class="text-subtitle-1 pb-2">
                    {{ message }}
                </div>
                <template v-slot:actions>
                    <v-btn-group>
                        <v-btn size="small" variant="plain" color="white" to="/">back</v-btn>
                        <v-btn size="small" variant="plain" color="white" :to="`/login`">Login</v-btn>
                    </v-btn-group>
                </template>

            </v-snackbar>

            <v-dialog v-model="address_dialog">
                <v-card class="mx-auto" max-width="800">
                    <v-toolbar>
                        <v-toolbar-title class="bg-transparent-accent">Address form</v-toolbar-title>
                        <template v-slot:append>
                            <v-btn icon variant="plain">
                                <v-icon icon="fas fa-close" @click="closeAddressDialog"></v-icon>
                            </v-btn>
                        </template>
                    </v-toolbar>
                    <v-card-text>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-text-field v-model="addressToShippment" label="Address to shippment">

                                </v-text-field>
                            </v-col>
                            <v-col>
                                <v-btn variant="plain" color="primary" size="xs" @click="saveAddress">Salvar</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Dashboard from '../Auth/Dashboard.vue';
import ZipCode from '../Layout/TextFields/ZipCode.vue';
export default {
    props: ['shippment', 'zip_code', 'customer'],
    components: {
        Dashboard,
        ZipCode,
    },
    data: () => ({
        product: [],
        confirm: true,
        shippmentConfirm: false,
        addressToShippment: false,
        dataConfirm: true,
        finish: true,
        productImages: false,
        snackbar: false,
        message:  '',
        postal_code: false,
        address: [],
        address_dialog: false,
        //shippment: [],
    }),
    watch: {
        closeAddressDialog(val) {
            val || this.closeAddressDialog();
        }
    },
    methods: {
        getProducts() {
            axios.get(`/cartItem/buy`)
                .then((response) => {
                    if(!response.address){
                        this.message = 'No address found...';
                        this.snackbar = true;
                       
                    }
                    this.productImages = JSON.parse(response.data.images);
                    return this.product = response.data;
                })
                .catch((response) => {
                    this.snackbar = true;
                    console.log(response);
                    this.message = response;
                    return false;
                });
        },
        confirmNext() {
            this.dataConfirm = true;
            return this.confirm = false;
        },
        confirmDatas() {
            this.dataConfirm = false;
            return this.finish = true;
        },
        searchToAddress() {
            const cepToFound = this.postal_code != false ? this.postal_code : this.zip_code;
            const data = {
                postal_code: cepToFound,
            };
            axios.get(`https://viacep.com.br/ws/${cepToFound}/json/`)
                .then((response) => {
                    return this.address.push(response.data);
                })
                .catch((response) => {
                    return alert('Error :', response);
                });
        },
        openAddressDialog() {
            this.address_dialog = true;
        },
        saveAddress() {
            
            this.closeAddressDialog();
            return this.address[0].complemento = this.addressToShippment;
        },
        confirmNext(){
            this.createAddress(
                this.address[0].cep,
                this.addressToShippment,
                this.address[0].bairro,
                this.address[0].localidade,
                this.address[0].uf,
                this.address[0].ibge,
                this.address[0].complemento,
            );
            this.shippmentConfirm = true;
            return this.confirm = false;
        },
        returnConfirm(){
            this.confirm = true;
            return this.shippmentConfirm = false;
        },
        createAddress(cep, endereco, bairro, city, uf, ibge, complemento){
           
           const newData = {
               address: endereco,
               postal_code: cep,
               uf:  uf,
               city: city,
               bairro: bairro,
               code_ibge: ibge,
               complemento: complemento ? 'null' : false,
               
           };
          
              axios.post('/saveSearchAddress', newData)
              .then((response) => {
               
                 return this.address = response.data;
                 
              })
              .catch((response) => {
                  return alert('ERROR: ', response);
              });

              this.closeAddressDialog();
         },
        closeAddressDialog() {
            this.address_dialog = false;
        }

    },
    created() {
        this.getProducts();


    }

}
</script>