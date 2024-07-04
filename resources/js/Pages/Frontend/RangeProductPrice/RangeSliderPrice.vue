<template>
    <div>
      <p class="h5">Filter Price</p>
      
      <v-divider></v-divider>
      <v-range-slider
        v-model="priceRange"
        :max="maxPrice"
        min="0"
        step="0.00"
        thumb-label="always"
        strict
       @change="displayProducts"
      >
        <template #thumb-label="{ modelValue }">
          <span>${{ formatCurrency(modelValue) }}</span>
        </template>
      </v-range-slider>
     
      <p>Selected Price Range: ${{ formatCurrency(this.priceRange[0]) }} - ${{ formatCurrency(this.priceRange[1]) }}</p>
   
      <v-btn @click="selectRange">Filter</v-btn>
    </div>
  </template>
  
  <script>
  export default {
    name: 'RangeSliderPrice',
    props: {
      products: {
        type: Array,
        required: true
      },
      subcategories: {
        type: Array,
        required: true
      },
    },
    data() {
      return {
        priceRange: [0, 0],
        subcategoryId: false,
        // selectedPriceRange: [0, 0],
      };
    },
    computed: {
      maxPrice() {
        return this.products.reduce((max, product) => {
          return product.price > max ? product.price : max;
        }, 0);
      },
      
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
      displayProducts(value) {
        
        this.priceRange[1] = value;
      },
      formatCurrency(value) {
        return parseFloat(value).toFixed(2);
      },
     
      selectRange(){
        const minPrice = `${this.formatCurrency(this.priceRange[0])}`;
        const maxPrice = `${this.formatCurrency(this.priceRange[1])}`;
        const subcategory = this.subcategories.map(subcategory => subcategory.subcategory_id);
        this.subcategoryId = subcategory[0];
        const data = {
          min: minPrice,
          max: maxPrice,
          subcategoryId: this.subcategoryId,
        };

        axios.post('/products/filter', data)
        .then((response) => {
            const filterProducts = response.data;
            return this.$emit('update-product-filter', filterProducts );
        })
        .catch((response) => {
          return alert('Error: ' + response);
        });
       
      }
     
    },
   
  }
  </script>
  