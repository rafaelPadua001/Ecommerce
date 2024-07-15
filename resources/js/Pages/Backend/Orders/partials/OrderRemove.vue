<template>
    <v-dialog>
        <v-card>
            <v-card-text>Tem certeza que quer remover este pedido ? {{ order.order_id }}</v-card-text>
            <v-card-actions>
                <v-btn variant="plain" @click="closeOrderDialog">Cancel</v-btn>
                <v-btn variant="plain" color="red" @click="removeOrder">Refund</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    export default {
        name: 'OrderRemove',
        props: ['order'],
        methods: {
            closeOrderDialog(){
                return this.$emit('close-order-dialog');
            },
            removeOrder(item){
               
                return this.$emit('remove-order', this.order);
            //    axios.post(`/api/melhorenvio/delete/${this.order.id}`)
            //    .then((response) => {
            //       // this.orders.splice(item, 1);
            //        this.closeOrderDialog();
            //        return this.order.splice(item, 1);
            //    })
            //    .catch((response) => {
            //        alert('Error :' + response);
            //    });
            },
        }
    }
</script>