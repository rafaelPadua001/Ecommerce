<template>
    <v-row fluid>
        <v-col class="d-flex justify-center flex-column" cols="12">
            <v-carousel height="120" :show-arrows="false"  hide-delimiters cycle>
                <v-carousel-item v-for="discount in discounts" :key="discount.id" cover>
                    <v-card v-if="discount.is_used == 0 && discount.is_displayed == 1" theme="dark"
                        :image="`./storage/Coupons/${JSON.parse(discount.images)}`" elevation="0"
                    >
                        <template v-slot:append>
                            <v-btn icon size="x-small" variant="plain">
                                <v-icon icon="fas fa-close"></v-icon>
                            </v-btn>
                        </template>
                        <v-card-text class="justify-start">
                            <span>clique no botão
                            <v-btn variant="text" size="small" @click="rescueDiscount(discount)">
                             {{ discount.code }}
                            </v-btn>
                            e receba <strong>{{ discount.discount_percentage * 100 }}%</strong> de desconto
                        </span>
                        </v-card-text>

                        <v-card-subtitle>
                            valido até {{ discount.end_date }}
                        </v-card-subtitle>
                    </v-card>
                </v-carousel-item>
            </v-carousel>

            <div>
                <v-snackbar v-model="snackbar" :timeout="timeout" :color="this.variant">
                    {{ message }}

                    <template v-slot:actions>
                        <v-btn color="blue" variant="text" @click="snackbar = false">
                            Close
                        </v-btn>
                    </template>
                </v-snackbar>
            </div>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['discounts', 'customer'],
    data: () => ({
        length: 3,
        snackbar: false,
        message: false,
        timeout: 2000,
        variant: false,
    }),
    methods: {
        rescueDiscount(item) {
            const customerId = this.customer.id;
            const data = { coupon: item };

            if (!customerId) {
                return window.location.href = '/login';
            }
            axios.post(`/coupons/getCoupon/${customerId}`, data)
                .then((response) => {
                    this.snackbar = true;
                    this.message = response.data.coupon_name ?? response.data;

                    return true;

                })
                .catch((response) => {
                    this.snackbar = true;
                    this.message = response.data.coupon_name ?? response.data;

                    return false;

                });
        }
    }
}
</script>