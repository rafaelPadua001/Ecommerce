<template>
    <v-row>
        <v-col cols="12">
            <v-window v-model="onboarding" show-arrows="hover">
                <v-window-item v-for="discount in discounts" :key="discount.id">
                    <v-card max-height="120" v-if="discount.is_used == 0 && discount.is_displayed == 1" color="#952175"
                        theme="dark">

                        <v-card-title class="text-h6">
                            clique no botão <strong>{{ discount.code }}</strong>
                            e receba <strong>{{ discount.discount_percentage * 100 }}%</strong> de desconto

                        </v-card-title>
                        <v-card-subtitle>
                            valido até {{ discount.end_date }}

                        </v-card-subtitle>
                        <v-btn variant="text" @click="rescueDiscount(discount)">

                            {{ discount.code }}
                        </v-btn>


                    </v-card>
                </v-window-item>
            </v-window>

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
        onboarding: 0,
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