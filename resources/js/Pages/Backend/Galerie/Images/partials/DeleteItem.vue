<template>
    <v-dialog>
        <v-card class="mx-auto">
            <v-card-title class="text-h5">
                Are you sure you want to
                delete this
                item ?
            </v-card-title>
            <v-card-text>
                {{ this.editedItem.name }}
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: 'DelteItem',
        props: ['editedItem'],
        methods: {
            closeDelete(){
                return this.$emit('close-delete');
            },
            deleteItemConfirm(){
                return this.$emit('delete-item-confirm');
            }
        }
    }
</script>