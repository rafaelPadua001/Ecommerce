<template>
    <v-card>
        <v-card-title class="text-h5">Remove </v-card-title>
        <v-card-text>
            Are you sure you want to delete this item ?
            {{ editedItem.name }}
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
            <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">Remove</v-btn>
            <v-spacer></v-spacer>
        </v-card-actions>
    </v-card>
</template>
<script>
export default {
    name: 'ProductRemove',
    props: ['editedItem'],
    methods: {
        closeDelete(){
            return this.$emit('close-delete');
        },
        deleteItemConfirm(){
            return this.$emit('remove-item', this.editedItem);
        }
    }
}
</script>