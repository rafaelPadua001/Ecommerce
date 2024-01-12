<template>
    <v-dialog>
        <v-row fluid justify="center">
            <v-col class="d-flex justify-center flex-column" md="6"> 
                <v-sheet>
            <v-card class="mx-auto elevation-0">
            <v-card-title>
                <v-toolbar color="transparent">
                    <v-toolbar-title>Order</v-toolbar-title>

                    <template v-slot:append>
                        <v-btn class="me-2" icon variant="plain" size="small" @click="closeDialog()">
                            <v-icon icon="fas fa-close" ></v-icon>
                        </v-btn>
                        
                    </template>
                </v-toolbar>
            </v-card-title>

            <v-card-text v-if="order.order_id">
                <v-row>
                    <v-col>
                        <p><strong>OrderId</strong>: {{ order.id }}</p>
                    </v-col>
                    <v-col>
                        <p><strong>Client:</strong> {{ order.client }}</p>
                    </v-col>
                    <v-col>
                        <p><strong>Value: </strong>{{ order.value }}</p>
                    </v-col>
                    <v-col>
                        <p><strong>status:</strong> {{ order.status }}</p>
                    </v-col>
                </v-row>
                        
                <v-row fluid v-if="Object.keys(orderInfo).length >= 1">
                  
                    <v-col>
                        ReasonCode : {{ orderInfo.original.ReasonCode }}
                       
                    </v-col>
                    <v-col>
                        
                        ReasonMessage: {{ orderInfo.original.ReasonMessage }}
                      
                    </v-col>
                    <v-col>
                        
                        PaymentId: {{ orderInfo.original.Payments[0].PaymentId }}
                       
                    </v-col>
                    <v-col>
                        
                        
                      <!--  ReceveidData: {{ orderInfo['Payments']['ReceveidDate'] }} -->
                    </v-col>
                 
                    
                </v-row>

                <v-row v-if="Object.keys(orderInfo).length >= 1">
                    <v-col>
                        <v-btn class="me-2 elevation-0" variant="flat" color="primary" @click="doneOrder(orderInfo)">Done</v-btn>
                    </v-col>
                </v-row>
                 
            </v-card-text>

            <v-card-text v-else>
                Id não encontrada
            </v-card-text>

            <v-card-actions>
               <v-btn class="me-2" color="primary" variant="flat" @click="search()" block>search</v-btn>
            </v-card-actions>
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
    watch: {
        close(val){
            val || this.closeDialog();
        }
    },
    methods: {
        search(){
            axios.get(`/orders/getOrder/${this.order.id}`)
            .then((response) => {
                return this.orderInfo = response.data;
            })
            .catch((response) => {
                console.log('error: ' + response);
            });
        },
        closeDialog(){
            return this.$emit('close-dialog');
        },
        doneOrder(){
            console.log(this.order);
            console.log(this.orderInfo);
            alert('estamos aqui');
        }
    }
}
</script>