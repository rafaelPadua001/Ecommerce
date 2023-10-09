<template>
    <div>
        <Dashboard />
    </div>
    <v-main>
        <div v-if="carts.length == 0">
            <h5>Você ainda não possui itens em seu carrinho</h5>
        </div>

        <div v-else>
            <h5>Aqui serão exibidos os itens do carrinho</h5>
        </div>
    </v-main>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

export default {
    components: { Dashboard },
    data: () => ({
        carts: [],
    }),
    methods: {
        getCart() {
            axios.get('/carts')
                .then((response) => {
                    return this.carts = response.data;
                })
                .catch((response) => {
                    return alert('Error: ' + response);
                });
        }
    }
}
</script>
