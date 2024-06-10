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
                                <v-col class="" cols="10">
                                    <v-form ref="form">
                                        <v-alert v-model="errorDialog" text="zip code is required" type="warning"
                                            closable>

                                        </v-alert>
                                        <v-text-field v-model="zip_code" v-maska:[options] aria-required
                                            variant="outlined" placeholder="zip code" :rules="zipCodeRules">
                                            <template v-slot:append>
                                                <v-btn-group>
                                                    <v-btn variant="text" color="primary"
                                                        @click="calculateDelivery(delivery)" icon>
                                                        <v-icon icon="fas fa-people-carry-box"></v-icon>
                                                    </v-btn>
                                                    <v-btn variant="text" size="xs" color="primary"
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
                                                {{ delivery}}
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
            console.log(this.selectedShippment);
            if (this.zip_code.length < 8) {
                return this.errorDialog = true;
            }
            const data = {
                postal_code: this.zip_code,
                height: this.selectProduct.height,
                width: this.selectProduct.width,
                length: this.selectProduct.length,
                weight: this.selectProduct.weight,
                price: this.selectProduct.price,
                quantity: this.quantity,
                shippment: this.delivery_name,
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