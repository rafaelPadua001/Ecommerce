<template>
    <div>
        <div>
            <v-card v-model="finish" class="d-flex align-center flex-column" :width="800">
                <v-card-text>
                    <v-row fluid v-for="(item, index) in parsedProduct" :key="index">
                        <v-col class="d-flex child-flex" cols="auto">
                            <div>
                                <v-row>
                                    <v-col class="avatar-stack" :style="{ 'z-index': parsedProduct.length - index }"
                                        cols="auto">
                                        <v-badge :content="item.quantity" color="primary">
                                            <v-avatar :image="`/storage/products/${item.images[0]}`"
                                                :lazy-src="`/storage/products/${item.images[0]}`"
                                                alt="product image here" aspect-ratio="16/9" :width="200"
                                                color="grey-lighten-2" no-gutters cover>

                                                <template>
                                                    <div class="d-flex align-center justify-center fill-height">
                                                        <v-progress-circular color="grey-lighten-4">
                                                        </v-progress-circular>
                                                    </div>
                                                </template>

                                            </v-avatar>
                                        </v-badge>

                                    </v-col>
                                    <v-col class="text-caption" cols="auto">
                                        {{ item.name }} R$ {{ item.total_price }}
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="auto">
                             <p class="text-subtitle-2">
                                <strong>Total Value</strong>
                                {{ totalPrice }}
                                
                            </p>
                        </v-col>
                        <v-col>
                            <div class="text-subtitle-2">
                                <strong>Cupom:</strong>
                                none
                            </div>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="8" sm="6">
                            <v-card class="mx-auto" :width="450">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="8" sm="8">
                                            <v-radio-group v-model="paymentType" inline>
                                                <v-radio label="Debit" value="debit">

                                                </v-radio>
                                                <v-radio label="Credit" value="credit">

                                                </v-radio>
                                                <v-radio label="Pix" value="pix">

                                                </v-radio>
                                            </v-radio-group>
                                        </v-col>
                                    </v-row>

                                    <v-row>
                                        <v-col>
                                            <div v-if="paymentType == 'debit'">
                                                <v-card>
                                                    <DebitForm :paymentType="paymentType" :carts="carts"
                                                        :billing_address="billing_address"
                                                        :shippment="this.shippment"
                                                        @updateCompleted="updateCompleted" />
                                                </v-card>
                                            </div>
                                            <div v-if="paymentType == 'credit'">
                                                <v-card>
                                                    <CreditForm :paymentType="paymentType" :carts="carts"
                                                        :billing_address="billing_address"
                                                        :shippment="this.shippment"
                                                        @updateCompleted="updateCompleted"
                                                     />


                                                </v-card>
                                            </div>
                                            <div v-if="paymentType == 'pix'">
                                                <v-card>
                                                    <PixForm :paymentType="paymentType" :carts="carts"
                                                        :billing_address="billing_address"
                                                        :shippment="this.shippment"
                                                    >
                                                    </PixForm>
                                                </v-card>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn-group>
                        <v-btn @click="returnConfirmDatas()">Voltar</v-btn>
                        

                    </v-btn-group>
                </v-card-actions>
            </v-card>
        </div>
        <p>

        </p>
    </div>
    <div>
        <div>Finalizar</div>
        <p>
            <!-- {{ itemCart.name }} -->
        </p>

    </div>
</template>

<script>
import DebitForm from '../../Payment/DebitForm.vue'
import CreditForm from '../../Payment/CreditForm.vue'
import PixForm from '../../Payment/PixForm.vue'

export default {
    name: 'Finish',
    props: ['carts', 'billing_address', 'shippment'],
    components: {
        DebitForm,
        CreditForm,
        PixForm,
    },
    data: () => ({
        paymentType: false,
        paymentResponse: false,
    }),
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
                return item.quantity * (acc + item.cart_item_price);
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
        returnConfirmDatas() {
            return this.$emit('returnConfirmDatas');
        },
        updateCompleted(response){
            this.paymentResponse = response;
            return this.$emit('updateCompleted', this.paymentResponse);
            
        }
    }

}
</script>