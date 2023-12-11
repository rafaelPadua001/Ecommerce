<template>
    <v-row>
        <v-col class="d-flex child-flex" cols="12" md="12" sm="3">
            <v-text-field v-model="zip_code" label="zip code:" >

            </v-text-field>
        </v-col>
        <v-col cols="2">

            <v-btn variant="text" size="x-small" @click="calculateDelivery()">Calcular frete</v-btn>

        </v-col>
    </v-row>
    <v-row no-gutters>
        <v-col class="d-flex flex-column" cols="12">
            <v-list>
                <v-list-item v-for="shipping in shipping_companys" :key="shipping.id">
                    <div v-if="!shipping.error" v-for="delivery in shipping" :key="delivery.id">
                        <div v-if="!delivery.error">
                            <v-list-item-avatar>
                                <v-img :max-width="120" :max-height="15" :src="delivery.company.picture" :lazy-src="delivery.company.picture"
                                    :placeholder="delivery.company.name">
                                </v-img>
                                
                            </v-list-item-avatar>

                            <p class="text-body">
                                <v-radio-group v-model="selectedShippment">
                                    <v-radio :value="delivery" @change="selectShippment"></v-radio>
                                </v-radio-group>
                            </p>

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
        </v-col>
    </v-row>
</template>

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
            return this.$emit('updateShippment', this.selectedShippment);
        }
    },
    
}
</script>