<template>
    <v-card class="d-flex mx-auto flex-column" :width="600">
        <v-card-text>
            <v-row fluid justify="center">
                <div class="container-relative">

                    <v-col v-for="(item, index) in parsedProduct" :key="index" class="avatar-stack" cols="auto"
                        :style="{ 'z-index': parsedProduct.length - index, 'left': `${index * 10}px` }">
                        <v-badge color="red" :content="item.quantity">
                            <v-avatar :image="`/storage/products/${item.images[0]}`"
                                :lazy-src="`/storage/products/${item.images[0]}`" aspect-ratio="16/9" :width="200" cover
                                alt="Image Here" color="grey-lighten-2" no-gutters>


                                <template>
                                    <div class="d-flex align-center justify-center fill-height">
                                        <v-progress-circular color="grey-lighten-4">
                                        </v-progress-circular>
                                    </div>
                                </template>
                                <!-- {{ item.images[0] }} -->
                            </v-avatar>

                        </v-badge>


                    </v-col>
                </div>


                <div class="container-absolute">
                    <v-col cols="auto">
                        <v-row no-gutters>
                            <v-col class="text-body-1">
                                <strong>Customer name:</strong>
                                {{ customer.first_name }} {{ customer.last_name }}
                            </v-col>
                        </v-row>
                        <v-spacer></v-spacer>
                        <v-divider></v-divider>
                        <v-row no-gutters>
                            <v-col class="text-overline">
                                <strong>CPF:</strong>
                                {{ 'not found' }}
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col class="text-body-2">
                                <strong>Celular:</strong>
                                {{ 'not found' }}
                            </v-col>
                        </v-row>

                        <v-spacer></v-spacer>
                        <v-divider></v-divider>

                        <v-row no-gutters>
                            <v-col class="text-body-2">
                                <strong>Endereço:</strong>
                                {{ address.endereco ? 'not register' :
        billing_address.shippment_address }}
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col class="text-body-2">
                                <strong>Complemento:</strong>
                                {{ address.complemento ? 'not register' :
        billing_address.shippment_complement }}
                            </v-col>
                        </v-row>

                        <v-row no-gutters>
                            <v-col>
                                <strong>Cidade:</strong> {{ address.cidade ? 'not register'
        : billing_address.shippment_city }}
                            </v-col>
                        </v-row>


                        <div>
                            <p class="text-subtitle-2">
                                <strong>Zip Code</strong> {{
        billing_address.zip_code }}
                            </p>
                            <p class="text-subtitle-2">
                                <strong>UF:</strong> {{ address.uf ? 'not register'
        : billing_address.select_uf.uf }}
                            </p>
                        </div>
                        <div>
                            <!-- <p class="text-subtitle-2">
                                                                    <strong>Delivery: </strong>
                                                                    {{ selectedDelivery.currency }} {{
                selectedDelivery.price }}

                                                                </p> -->

                        </div>

                        <!-- <div>
                                                                <p>
                                                                    <strong>Total Value</strong>
                                                                    {{ selectedDelivery.currency }}
                                                                    {{ (parseFloat(selectedDelivery.price) +
                parseFloat(itemCart.price)).toFixed(2)
                                                                    }}
                                                                </p>

                                                            </div> -->

                    </v-col>
                </div>

            </v-row>

        </v-card-text>

        <v-card-actions>
            <v-btn-group>
                <v-btn @click="returnConfirm()">voltar</v-btn>
                <v-btn @click="confirmDatas()">Confirmar</v-btn>
            </v-btn-group>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'DataConfirm',
    props: ['carts', 'billing_address', 'address', 'customer'],
    data: () => ({}),
    computed: {
        parsedProduct() {
            return this.carts.map((item) => {
                return {
                    ...item,
                    cart_item_colors: JSON.parse(item.cart_item_colors),
                    cart_item_size: JSON.parse(item.cart_item_size),
                    images: item.images ? JSON.parse(item.images) : [],
                }
            });
        },
        subtotal() {
            return this.carts.reduce((acc, item) => {
                return acc + item.cart_item_price;
            }, 0)
        },
        formattedSubtotal() {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(this.subtotal);
        },
        formatedShippmentPrice() {
            const shippmentPrice = this.shippment && !isNaN(Number(this.shippment.price))
                ? Number(this.shippment.price)
                : 0;
            return shippmentPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        totalPrice() {

            const subtotal = !isNaN(Number(this.subtotal)) ? Number(this.subtotal) : 0;
            const shippmentPrice = this.shippment && !isNaN(Number(this.shippment.price))
                ? Number(this.shippment.price)
                : 0;
            const sumValues = subtotal + shippmentPrice;
            return sumValues.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        
    },
    methods: {
        confirmDatas(){
           
           return this.$emit('confirmDatas');
       },
       returnConfirm(){
           return this.$emit('returnConfirm');
       },
       
    }
}
</script>


<style scoped>
.avatar-stack {
    position: absolute;
    transition: transform 0.3s ease;

}

.avatar-stack:hover {
    transform: translateY(-10px);
}

.container-relative {
    position: relative;
    height: 100%;

}

.container-absolute {
    position: relative;
    margin-left: 50%;
}

.text-caption {
    display: flex;
    margin-left: 32%;
    /* Espaço entre nome e preço */
}
</style>