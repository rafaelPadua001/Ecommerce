<template>
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
                                                    <v-col v-if="address">
                                                        <strong >endereco:</strong> {{ address[0].complemento }},
                                                        <strong>Bairro:</strong> {{ address[0].bairro }},
                                                        <strong>Logradouro:</strong> {{ address[0].logradouro }},
                                                        <strong>CEP:</strong> {{ address[0].cep }} ,
                                                        <strong>Localidade:</strong>  {{ address[0].localidade }},
                                                        <strong>UF:</strong>  {{ address[0].uf }}
                                                        <strong>DDD:</strong>  {{ address[0].ddd }} 
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
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import ZipCode from '../Layout/TextFields/ZipCode.vue';
export default {
    props: ['shippment', 'zip_code', 'customer'],
    components: {
        ZipCode,
    },
    data: () => ({
        product: [],
        confirm: true,
        dataConfirm: true,
        finish: true,
        productImages: false,
        snackbar: false,
        message: false,
        postal_code: false,
        address: [],
        //shippment: [],
    }),
    methods: {
        getProducts() {
            axios.get(`/cartItem/buy`)
                .then((response) => {
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
        returnConfirmDatas() {
            this.dataConfirm = true;
            this.finish = false;
        },
        searchToAddress() {
            const cepToFound = this.postal_code != false ? this.postal_code : this.zip_code;
            const data = { 
                postal_code: cepToFound,
            };
           
            axios.get(`https://viacep.com.br/ws/${cepToFound}/json/`)
                .then((response) => {
                  //  this.address.complemento = response.data[0].complemento;
                  
                    return this.address.push(response.data);
                  
                    //this.itemCart.cep = " ";
                    //this.itemCart.cep = response.data[0].cep;
                    //this.address.endereco = response.data[0].logradouro;
                    //this.address.bairro = response.data[0].bairro;
                    //this.address.cep = response.data[0].cep;
                    //this.address.cidade = response.data[0].cidade;
                    //this.address.UF = response.data[0].uf;
                    //this.address.complemento = response.data[0].complemento;
                    
                    //this.saveAddress(
                    //    this.itemCart.cep,
                    //    this.address.endereco,
                    //    this.address.bairro,
                    //    this.city,
                    //    response.data[0].uf,
                    //    response.data[0].ibge,
                    //    this.complemento,
                    //);

                   // return this.respSearchAddress.push(response.data[0]);
                    
                })  
                .catch((response) => {
                    return alert('Error :', response);
                })
        },

    },
    created() {
        this.getProducts();


    }

}
</script>