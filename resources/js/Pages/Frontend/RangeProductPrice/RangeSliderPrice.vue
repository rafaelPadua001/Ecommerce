<template>
    <!-- {{ this.products }} -->
    <p class="h5">Filter Price</p>
    <v-divider></v-divider>
    <v-range-slider v-model="priceRange" :max="maxPrice" step="1" thumb-label="always" strict>
    </v-range-slider>
</template>

<script>
export default {
    name: 'RangeSliderPrice',
    props: ['products'],
    data: () => ({
        priceRange: [0, 0],
    }),
    computed: {
        maxPrice() {
            return this.products.reduce((max, product) => {
                return product.price > max ? product.price : max;
            }, 0);
        }
    },
    watch: {
        product: {
            handler() {
                const prices = this.products.map(product => product.price);
                this.priceRange = [Math.min(...prices), Math.max(...prices)];
            },
            immediate: true,
            deep: true

        }
    }

}
</script>