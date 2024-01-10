<template>
    <v-dialog>
        <v-row>
            <v-col> 
                <v-sheet>
            <v-card>
            <v-card-title>
                <v-toolbar>
                    <v-toolbar-title>Order</v-toolbar-title>

                    <template v-slot:append>
                        <v-icon icon="fas fa-close"></v-icon>
                    </template>
                </v-toolbar>
            </v-card-title>

            <v-card-text v-if="order.order_id">
                
                        <strong>OrderId</strong>: {{ order.id }}
                        <strong>Client:</strong> {{ order.client }}
                        <strong>value: </strong>{{ order.value }}
                        <strong>status:</strong> {{ order.status }}
                    
                
                  <v-btn @click="search()">search</v-btn>

                  {{ orderInfo }}
            </v-card-text>

            <v-card-text v-else>
                Id não encontrada
            </v-card-text>

            <v-card-actions></v-card-actions>
        </v-card>
        </v-sheet>
            </v-col>    
        </v-row>
        
       

    </v-dialog>
</template>

<script>
export default {
    props: ['order'],
    data: () => ({
        orderId: false,
        orderInfo: [], 
    }),
    methods: {
        search(){
            axios.get(`/orders/getOrder/${this.order.id}`)
            .then((response) => {
                return this.orderInfo.push(response.data);
            })
            .catch((response) => {
                console.log('error: ' + response);
            });
        }
    }
}
</script>