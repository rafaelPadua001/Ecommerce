<template>
    <v-card>
        <v-card-title>Update Form</v-card-title>
        
        <v-card-text>
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" md="4">
                      
                        <v-text-field v-model="editedItem.name" label="Shippment name"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.company" label="Company"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.price" label="Price" prefix="R$ ">
                        </v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-text-field v-model="editedItem.deadline" label="Deadline"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                        <v-responsive width="150" max-width="165" justify="start">

                            <v-text-field v-model="editedItem.quantity" label="Quantity" placeholder="1">
                                <template v-slot:append>
                                    <v-icon class="grey-lighten-4" size="x-small" @click="quantityIncrement">
                                        fas fa-plus fa-2xs
                                    </v-icon>
                                </template>
                                <template v-slot:prepend>
                                    <v-icon class="grey-lighten-4" size="x-small" @click="quantityDecrement">
                                        fas fa-minus
                                    </v-icon>
                                </template>
                            </v-text-field>
                        </v-responsive>
                    </v-col>

                </v-row>
            </v-container>
        </v-card-text>

        <v-card-actions>
            <v-btn-group>
                <v-btn @click="save">Save</v-btn>
                <v-btn @click="close">Cancel</v-btn>
            </v-btn-group>
        </v-card-actions>

    </v-card>
</template>

<script>
export default {
    name: 'UpdateDialog',
    props: ['editedItem', 'editedIndex'],
    methods: {
        quantityIncrement() {
            this.editedItem.quantity++;
        },
        quantityDecrement() {
            if (this.editedItem.quantity >= 1) {
                this.editedItem.quantity--;
            }
            else {
                alert('apenas numeros inteiros são aceitos');
            }

        },
        save(){
            return this.$emit('update-shippment', this.editedItem, this.editedIndex);
        },
        close(){
            return this.$emit('close-update-dialog');
        }
    }
}
</script>