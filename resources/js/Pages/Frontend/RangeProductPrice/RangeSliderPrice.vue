<template>
    <!-- {{ this.products }} -->
    <p class="h5">Filter Price</p>
    <v-divider></v-divider>
    <v-range-slider
        v-model="priceRange"
        :max="maxPrice"
        step="1"
         thumb-label="always"
        strict
        @change="displayProducts(priceRange)">
    </v-range-slider>

    {{ 'min value: R$' + priceRange[0] }}
    {{ 'max value: R$' + priceRange[1] }}
    <v-btn @click="displayProducts(priceRange)">Filter</v-btn>
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
        },
        minPrice() {
            return this.products.reduce((min, product) => {
                return product.price >= min ? product.price : min;
            }, 0);
        }
    },
    watch: {
        products: {
            handler() {
                const prices = this.products.map(product => product.price);
                this.priceRange = [Math.min(...prices), Math.max(...prices)];
            },
            immediate: true,
            deep: true

        }
    },
    methods: {
        displayProducts(priceRange){
            alert(priceRange);
            // alert(value);
            // alert(this.maxPrice);
        }
    }

}
</script>