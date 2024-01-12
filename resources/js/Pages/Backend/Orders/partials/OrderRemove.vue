<template>
    <v-dialog>
        <v-card>
            <v-card-text>Tem certeza que quer remover este pedido ? {{ value }}</v-card-text>
            <v-card-actions>
                <v-btn variant="plain">Cancel</v-btn>
                <v-btn variant="plain" color="red" @click="removeOrder">Refund</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    export default{
        methods: {
            removeOrder(item){
                alert(item);
               // axios.post(`/api/melhorenvio/delete/${this.order.id}`)
               // .then((response) => {
               //    // this.orders.splice(item, 1);
               //     this.closeOrderDialog();
               //     return this.orders.splice(item, 1);
               // })
               // .catch((response) => {
               //     alert('Error :', response);
               // });
            },
        }
    }
</script>