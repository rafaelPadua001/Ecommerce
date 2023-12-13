<template>
    <v-row>
        <v-col class="d-flex child-flex" cols="12" md="12" sm="3">
            <v-text-field v-model="zip_code" v-maska:[options] label="zip code" aria-required>

            </v-text-field>
        </v-col>
        <v-col cols="2">

            <v-btn variant="text" size="x-small" @click="calculateDelivery()">Calcular frete</v-btn>

        </v-col>
    </v-row>
    <v-row>
        <v-col class="d-flex justify-center mb-2 flex-column" cols="12" md="6" sm="4">
            <v-card class="mx-auto">
                <v-card-text>
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
                </v-card-text>
            </v-card>
            
        </v-col>
    </v-row>
</template>
<script setup>
import { ref } from "vue";
import { vMaska } from "maska";


const options = { mask: '#####-####' };
const myValue = ref('');


</script>
<script>
export default {
    props: ['selectProduct', 'quantity'],
    data: () => ({
        zip_code: '',
        shipping_companys: [],
        selectedShippment: '',
    }),
    methods: {
        calculateDelivery() {
            const data = {
                postal_code: this.zip_code,
                height: this.selectProduct.height,
                width: this.selectProduct.width,
                length: this.selectProduct.length,
                weight: this.selectProduct.weight,
                price: this.selectProduct.price,
                quantity: this.quantity,
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
    
}
</script>