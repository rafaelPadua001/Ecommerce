<template>
    <v-dialog>
        <v-container>
            <v-row>
                <v-col class="d-flex justify-align-center flex-column">
                    <v-card>
                        <v-toolbar class="bg-transparent" title="Remove Item">
                            <template v-slot:append>
                                <v-btn icon>
                                    <v-icon icon="fas fa-close"></v-icon>
                                </v-btn>
                            </template>
                        </v-toolbar>
                        <v-card-text>
                          Tem certeza que gostaria de remover o stilo ?  
                        </v-card-text>

                        <v-card-actions>
                            <v-btn-group>
                                <v-btn class="me-2" color="error" variant="plain" @click="deleteItem(style)">
                                    Remove
                                </v-btn>
                                <v-btn>
                                    Close
                                </v-btn>
                            </v-btn-group>
                        </v-card-actions>

                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-dialog>
   
</template>

<script>
import axios from 'axios';

    export default{
        props: ['style'],
        methods:{
            deleteItem(item){
                axios.delete(`/store/style/remove/${item.store_id}`)
                .then((response) => {
                    this.$emit('remove-style', response);
                })
                .catch((response) => {
                    alert("Error: " + response);
                })
               
            }
        }
    }
</script>