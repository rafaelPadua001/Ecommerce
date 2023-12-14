<template>
   
    <v-row>
        <v-col class="d-flex justify-center mb-2 flex-column" cols="12" md="8" sm="4">
            <v-card class="mx-auto" width="350px">
                <v-card-title>Deliveries</v-card-title>
                <v-toolbar class="px-0" color="transparent">
                    <template v-slot:extension>
                        <v-tabs
                            v-model="tabs"
                            v-for="(delivery, index) in deliveries"
                            :key="index"
                            :value="delivery.id"
                            color="primary"
                            grow
                            
                        >
                            <v-tab
                               v-if="delivery.activated == 1"
                            >
                                <v-img
                                    :src="`./storage/delivery/${delivery.thumbnail}`"
                                    :lazy-src="`./storage/delivery/${delivery.thumbnail}`"
                                    cover
                                    :aspect-ratio="1/1"
                                    :width="25"
                                    :max-height="50"
                                >

                                </v-img>
                                {{ delivery.name }}
                                
                            </v-tab>
                        </v-tabs>
                    </template>  
                </v-toolbar>
                
                <v-card-text>
                    
                    <v-window v-model="tabs" v-for="delivery in deliveries"
                            :key="delivery.id"
                            :value="delivery.id"
                    >   
                        <v-window-item
                            v-if="delivery.activated == 1"
                        >
                        <v-row>
                        <v-col class="d-flex child-flex" cols="12" md="8" sm="3">
                            <v-text-field v-model="zip_code" v-maska:[options] label="zip code" aria-required>

                            </v-text-field>
                        </v-col>
                        <v-col cols="2">

                            <v-btn variant="text" size="x-small" @click="calculateDelivery(delivery)">Calcular frete</v-btn>

                        </v-col>
                    </v-row>
                        <v-list>
                <v-list-item v-for="shipping in shipping_companys" :key="shipping.id">
                    <div v-if="!shipping.error" v-for="delivery in shipping" :key="delivery.id">
                        <div v-if="!delivery.error">
                            <v-row no-gutters>
                                <v-col cols="auto">
                                    
                                    <v-radio-group v-model="selectedShippment">
                                        <v-radio :value="delivery" @change="selectShippment">
                                                        
                                        </v-radio>
                                    </v-radio-group>
                          
                                </v-col>
                                <v-col>
                                    <v-list-item-avatar>
                                            <v-img :max-width="200" :max-height="25" :src="delivery.company.picture" :lazy-src="delivery.company.picture"
                                                :placeholder="delivery.company.name">
                                            </v-img>
                                    </v-list-item-avatar>
                                </v-col>
                            </v-row>
                          
                               
                           
                            <p class="text-body">
                                {{ delivery.name }}
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
        tabs: true,
        deliveries: [],
        zip_code: '',
        shippment: false,
        shipping_companys: [],
        selectedShippment: {},
    }),
    methods: {
        getDeliveries(){
            axios.get('/api/delivery')
            .then((response) => {
                return this.deliveries = response.data;
            })
            .catch((response) => {
                return alert('Error :' + response);
            });
        },
        calculateDelivery(delivery) {
            const name = delivery.name;
           
            const data = {
                postal_code: this.zip_code,
                height: this.selectProduct.height,
                width: this.selectProduct.width,
                length: this.selectProduct.length,
                weight: this.selectProduct.weight,
                price: this.selectProduct.price,
                quantity: this.quantity,
                shippment: name,
            };
            axios.post('/api/calculateDelivery', data)
                .then((response) => {
                    return this.shipping_companys.push(response.data);
                })
                .catch((response) => {
                    return alert('Error: ', response.error);
                })
        },
        selectShippment(){
            console.log('Dados a serem enviados para o pai:', this.selectedShippment);
            return this.$emit('updateShippment', this.selectedShippment, this.zip_code);
        }
    },
    created() {
        this.getDeliveries();
    }
    
}
</script>