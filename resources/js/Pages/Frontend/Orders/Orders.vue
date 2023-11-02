<template>
    <div>
        <Dashboard />
    </div>
    <v-row>
        <v-col>
            <v-sheet>
                <v-card>
                    <v-card-text>
                        
                        {{ orders }}
                        <p>Aqui vou carregar a data table</p>

                    </v-card-text>
                </v-card>
            </v-sheet>
        </v-col>
    </v-row>
    <h1></h1>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

    export default{
        components: {
            Dashboard
        },
        data: () => ({
            orders: [],
        }),
        methods: {
            getOrders(){
                axios.get('/orders')
                .then((response) => {
                    return this.orders.push(response.data);
                })
                .catch((response) => {
                    return alert('Error: ', response);
                })
            }
        },
        mounted() {
            this.getOrders();
        },
    }
</script>