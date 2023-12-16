<template>
    <div>
        <v-row>
            <v-col>
                <Dashboard></Dashboard>
            </v-col>
        </v-row>
    </div>
    <v-container>
        <v-row>
            <v-col>
                <v-sheet>
                    <v-data-table
                        :headers="headers"
                        :items="shippments"
                        :sort-by="[{key: 'name', order:'asc'}]"
                    >
                        
                           
                        
                       <template v-slot:item.actions="{item}">
                            <v-icon 
                                icon="fas fa-pen-to-square"
                                size="small"
                                class="me-2"
                                @click="editItem(item)"
                            ></v-icon>
                            <v-icon
                                icon="fas fa-trash"
                                size="small"
                                @click="deleteItem(item)"
                            ></v-icon>
                       </template>
                       
                       <template v-slot:no-data>
                            <v-btn
                                color="primary"
                                @click="initialize"
                            >
                                Reset
                            </v-btn>
                       </template> 
                    </v-data-table>
                    
                 
                </v-sheet>
            </v-col>
        </v-row>

        <div>
            <v-dialog v-model="updateDialog" max-width="500px">
                                <v-card>
                                    <v-card-title>Update Form</v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-text-field
                                                        v-model="editedItem.name"
                                                        label="Dessert name"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-text-field
                                                        v-model="editedItem.company"
                                                        label="Calories"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-text-field
                                                        v-model="editedItem.price"
                                                        label="Fat (g)"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-text-field
                                                        v-model="editedItem.deadline"
                                                        label="Carbs (g)"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-text-field
                                                        v-model="editedItem.quantity"
                                                        label="Protein (g)"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-text-field
                                                        v-model="editedItem.quantity"
                                                        label="Protein (g)"
                                                        ></v-text-field>
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

                                
                            </v-dialog>

                            <v-dialog v-model="deleteDialog" max-width="500px">
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

                                
                            </v-dialog>
        </div>
    </v-container>
</template>

<script>
import Dashboard from '../Auth/Dashboard.vue';

    export default {
        components: {
            Dashboard
        },
        data: () => ({
            shippments: [],
            headers: [
                {
                    title: 'name',
                    align: 'start',
                    sortable: true,
                    key: 'name'
                },
                {
                    title: 'company',
                    key: 'company'
                },
                {
                    title: 'price',
                    key: 'price'
                },
                {
                    title: 'deadline',
                    key: 'deadline'
                },
                {
                    title: 'quantity',
                    key: 'quantity'
                },
                {
                    title: 'delivery_id',
                    key: 'delivery_id'
                },
                {
                    title: 'actions',
                    key: 'actions',
                    sortable: false,
                }
            ],
            updateDialog: false,
            deleteDialog: false,
            editedIndex: -1,
            editedItem: [],
            defaultItem: [],
        }),
        watch: {
            close(val){
                val || this.close();
            },
            closeDeleteDialog(val){
                val || this.deleteDialog();
            }
        },
        methods: {
            initialize(){
                this.shippments = [];
            },
            getShippments(){
                axios.get('/shippments')
                .then((response) => {
                    return this.shippments = response.data;
                })
                .catch((response) => {
                    return alert('Error:' + response);
                })
            },
            editItem(item){
                this.editedIndex = this.shippments.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.updateDialog = true;
            },
            close(){
                this.updateDialog = false;
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.default);
                    this.editedIndex = -1;
                });
            },
            deleteItem(item){
                this.editedIndex = this.shippments.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.deleteDialog = true;
            },
            deleteItemConfirm(){
                this.shippments.splice(this.editedIndex, 1);
                this.closeDeleteDialog();
            },
            closeDeleteDialog(){
                this.deleteDialog = false;
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.default);
                    this.editedIndex = -1;
                });
            },
            save(){
                Object.assign(this.shippments[this.editedIndex], this.editedItem);
                this.close();
            }
        },
        mounted(){
            this.getShippments();
            this.initialize();
        }

    }
</script>