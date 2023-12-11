<template>
    
    {{ product }}
    
</template>

<script>
import axios from 'axios';

    export default {
        data: () => ({
            product: [],
        }),
        methods: {
            getProducts(){
                axios.get(`/cartItem/buy`)
                .then((response) => {
                    return this.product = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                });
            }
        },
        created() {
            this.getProducts();
        }

    }
</script>