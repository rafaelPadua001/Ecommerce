<template>
    <v-card>
        <v-card-title>delete item</v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                        Remove item ? {{ this.editedItem.product_id }}
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>

        <v-card-actions>
            <v-btn-group>
                <v-btn @click="deleteItemConfirm">Remove</v-btn>
                <v-btn @click="closeDeleteDialog">Cancel</v-btn>
            </v-btn-group>
        </v-card-actions>

    </v-card>
</template>

<script>
export default {
    name: 'DeleteDialog',
    props: ['editedItem'],
    methods: {
        deleteItemConfirm(){
            return this.$emit('delete-item-confirm', this.editedItem);
        },
        closeDeleteDialog(){
            return this.$emit('close-delete-dialog');
        }
    }
}
</script>