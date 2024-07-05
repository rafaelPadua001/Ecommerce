<template>
    <v-row no-gutters justify="start">
        <v-col class="d-flex mb-2  justify-start flex-column" cols="12">
            <v-card class="mx-auto elevation-0" width="400px">
                <v-toolbar class="px-0" color="transparent">
                    <template v-slot:extension>
                        <v-tabs v-model="tabs" color="primary" grow v-for="(delivery, index) in deliveries" :key="index"
                            :value="index">
                            <v-tab v-if="delivery.activated == 1 && index >= 0">
                                <v-img justify="left" :src="`./storage/delivery/${delivery.thumbnail}`"
                                    :lazy-src="`./storage/delivery/${delivery.thumbnail}`" cover :aspect-ratio="1 / 1"
                                    :width="40" :max-height="40">

                                </v-img>


                            </v-tab>
                        </v-tabs>
                    </template>
                </v-toolbar>
                <v-card-text>
                    <v-window v-model="tabs">
                        <v-window-item v-for="(delivery, index) in deliveries" :key="index" :value="index">
                            <v-row v-model="delivery.id" v-if="delivery.activated == 1" no-gutters>
                                <v-col class="" cols="12">
                                    <v-form ref="form">
                                        <v-alert v-model="errorDialog" text="zip code is required" type="warning"
                                            closable>

                                        </v-alert>
                                        <v-text-field v-model="zip_code" v-maska:[options] aria-required
                                            variant="outlined" placeholder="zip code" :rules="zipCodeRules">
                                            <template v-slot:append>
                                                <v-btn-group>
                                                    <v-btn variant="text" color="primary"
                                                        @click="calculateDelivery(delivery)" size="x-small">
                                                        calculate
                                                    </v-btn>
                                                    <v-btn size="x-small" variant="text" color="blue"
                                                        @click="cepDialogOpen">Buscar
                                                        Cep
                                                    </v-btn>
                                                    <v-btn variant="text" size="x-small" color="primary"
                                                        @click="clearZipCode()">
                                                        reset
                                                    </v-btn>

                                                </v-btn-group>

                                            </template>
                                        </v-text-field>
                                    </v-form>
                                </v-col>
                            </v-row>
                            <v-list>
                                <v-list-item v-for="(shipping, index) in shipping_companys" :key="index">

                                    <div v-if="!shipping.error" v-for="delivery in shipping" :key="delivery.id">
                                        <div v-if="!delivery.error">
                                            <v-row no-gutters>
                                                <v-col cols="auto">
                                                    <v-radio-group v-model="selectedShippment">
                                                        <v-radio :value="delivery" @change="selectShippment"
                                                            color="primary">

                                                        </v-radio>
                                                    </v-radio-group>

                                                </v-col>
                                                <v-col>
                                                    <v-list-item-avatar>
                                                        <v-img :max-width="200" :max-height="25"
                                                            :src="delivery.company.picture"
                                                            :lazy-src="delivery.company.picture"
                                                            :placeholder="delivery.company.name">
                                                        </v-img>
                                                    </v-list-item-avatar>
                                                </v-col>
                                            </v-row>



                                            <p class="text-body">

                                                <span>
                                                    Price: {{ delivery.currency }}: {{ delivery.price }}
                                                </span>
                                            </p>

                                            <p class="text-body">
                                                <span>
                                                    Prazo de Entrega:
                                                    até {{ delivery.delivery_time }} dias uteis
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        Error

                                    </div>

                                    <template v-slot:prepend>

                                    </template>
                                </v-list-item>
                            </v-list>
                        </v-window-item>
                    </v-window>
                </v-card-text>
            </v-card>
        </v-col>

        <v-dialog v-model="cepDialog" persistent width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Busca Cep</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>

                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="address.uf" label="UF"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="address.city" label="Cidade"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="address.bairro" label="Bairro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="address.searchAddress" label="logradouro"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field v-model="address.complemento" label="Complemento"></v-text-field>
                            </v-col>

                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>


                    <div v-if="respSearchAddress.length >= 1">
                        <v-row>
                            <v-col>
                                CEP: {{ respSearchAddress[0].cep }} ,
                                Logradouro: {{ respSearchAddress[0].logradouro }},
                                Localidade: {{ respSearchAddress[0].localidade }},
                                UF: {{ respSearchAddress[0].uf }}
                                DDD: {{ respSearchAddress[0].ddd }}
                            </v-col>
                        </v-row>

                    </div>

                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cepDialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="searchToAddress">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>

</template>
<script setup>
import { ref } from "vue";
import { vMaska } from "maska";
import axios from "axios";


const options = { mask: '#####-####' };
const myValue = ref('');


</script>
<script>
export default {
    props: ['selectProduct', 'quantity'],
    data: () => ({
        tabs: null,
        deliveries: [],
        zip_code: '',
        zipCodeRules: [
            v => !!v || 'Zip code is required.',
            v => /^[0-9]{5}-[0-9]{3}$/.test(v) || 'zip code is inválid. Fomat type: 12345-678'
        ],
        shippment: false,
        shipping_companys: [],
        selectedShippment: {},
        delivery_name: false,
        cepDialog: false,
        respSearchAddress: [],
        address: {
            uf: null,
            searchAddress: null,
            city: null,
            bairro: null,
            complemento: null,
        },
        errorDialog: false,
    }),
    methods: {
        getDeliveries() {
            axios.get('/api/delivery')
                .then((response) => {
                    return this.deliveries = response.data;
                })
                .catch((response) => {
                    return alert('Error :' + response);
                });
        },
        calculateDelivery(delivery) {
            const deliveryId = delivery.id;
            this.delivery_name = delivery.name;

            if (this.zip_code.length < 8) {
                return this.errorDialog = true;
            }
            const items = this.selectProduct.map(item => ({
                height: item.height,
                width: item.width,
                length: item.length,
                weight: item.weight,
                price: item.cart_item_price,
                quantity: item.quantity,

            }));

            const data = {
                shippment: this.delivery_name,
                postal_code: this.zip_code,
                items: items,
            };

            axios.post('/api/calculateDelivery', data)
                .then((response) => {
                    return this.shipping_companys.push(response.data);
                })
                .catch((response) => {
                    return alert('Error: ', response.error);
                })
        },
        selectShippment() {
            console.log('Dados a serem enviados para o pai:', this.selectedShippment);
            return this.$emit('updateShippment', this.selectedShippment, this.zip_code, this.delivery_name);
        },
        cepDialogOpen() {
            this.cepDialog = true;

        },
        searchToAddress() {
            const data = {
                address: this.address.searchAddress,
                postal_code: this.address.postal_code,
                uf: this.address.uf,
                city: this.address.city,
                bairro: this.address.bairro,
                complemento: this.address.complemento
            };

            axios.get(`https://viacep.com.br/ws/${this.address.uf}/${this.address.city}/${this.address.searchAddress.replace(/\s/g, '+')}/json/`)
                .then((response) => {
                    this.zip_code = response.data[0].cep
                    const item = {
                        cep: response.data[0].cep,
                        endereco: response.data[0].logradouro,
                        bairro: response.data[0].bairro,
                        cep: response.data[0].cep,
                        cidade: response.data[0].cidade,
                        UF: response.data[0].uf,
                        complemento: response.data[0].complemento,
                    };
                    // this.itemCart.cep = " ";
                    // this.itemCart.cep = response.data[0].cep;
                    // this.address.endereco = response.data[0].logradouro;
                    // this.address.bairro = response.data[0].bairro;
                    // this.address.cep = response.data[0].cep;
                    // this.address.cidade = response.data[0].cidade;
                    // this.address.UF = response.data[0].uf;
                    //this.address.complemento = response.data[0].complemento;
                    this.cepDialog = false;
                    // this.saveAddress(
                    //     this.itemCart.cep,
                    //     this.address.endereco,
                    //     this.address.bairro,
                    //     this.city,
                    //     response.data[0].uf,
                    //     response.data[0].ibge,
                    //     this.complemento,
                    // );
                    this.$emit('updateAddressFields', item);
                    return this.respSearchAddress.push(response.data[0]);

                })
                .catch((response) => {
                    console.log(response);
                    return alert('Error :', response);
                })
        },
        saveAddress(cep, endereco, bairro, city, uf, ibge, complemento) {

            const newData = {
                address: endereco,
                postal_code: cep,
                uf: uf,
                city: city,
                bairro: bairro,
                code_ibge: ibge,
                complemento: complemento,

            };
            console.log(newData);
            axios.post('/saveSearchAddress', newData)
                .then((response) => {

                    return this.address = response.data;

                })
                .catch((response) => {
                    return alert('ERROR: ', response);
                });
        },
        clearZipCode() {
            this.zip_code = '';
            this.shipping_companys = [];
            this.selectShippment = '';
            return this.selectShippment();
        }
    },
    created() {
        this.getDeliveries();
    }

}
</script>